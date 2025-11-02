<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    /**
     * Inisialisasi pembayaran (redirect ke Snap Midtrans)
     */
    public function pay($course_id)
    {
        $course = Course::findOrFail($course_id);
        $student = Auth::user();

        // Jika gratis → langsung enroll
        if ($course->price <= 0) {
            Enrollment::firstOrCreate([
                'student_id' => $student->id,
                'course_id' => $course->id,
            ]);
            return redirect()->route('student.listcourse')->with('success', 'Kursus gratis berhasil di-enroll.');
        }

        // 🔧 Konfigurasi Midtrans — pastikan server key ada
        $serverKey = config('midtrans.server_key');
        if (empty($serverKey)) {
            abort(500, 'Midtrans server key is not configured. Please set MIDTRANS_SERVER_KEY in .env');
        }

        Config::$serverKey = $serverKey;
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 🔖 Buat order ID unik
        $orderId = 'ORDER-' . uniqid();

        // Data transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $course->price,
            ],
            'customer_details' => [
                'first_name' => $student->name,
                'email' => $student->email,
            ],
            'item_details' => [[
                'id' => $course->id,
                'price' => (int) $course->price,
                'quantity' => 1,
                'name' => $course->title,
            ]],
        ];

        // Dapatkan Snap Token
        $snapToken = Snap::getSnapToken($params);

        // Simpan transaksi awal. Use an enum-compliant payment_method and status.
        Payment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            // we'll default to credit_card here (Midtrans supports many channels; callback will update)
            'payment_method' => 'credit_card',
            'status' => 'pending',
            'transaction_id' => $orderId,
        ]);

        // Note: view lives at resources/views/student/pay.blade.php
        return view('student.pay', compact('snapToken', 'course'));
    }

    /**
     * Handle callback (webhook) dari Midtrans
     */
    public function callback(Request $request)
    {
        // ✅ Gunakan server key dari config, bukan hardcode
        $serverKey = config('midtrans.server_key');
        $signature = hash('sha512', ($request->order_id ?? '') . ($request->status_code ?? '') . ($request->gross_amount ?? '') . $serverKey);

        if ($signature !== ($request->signature_key ?? '')) {
            // invalid signature
            return response()->json(['success' => false, 'message' => 'invalid signature'], 400);
        }

        $payment = Payment::where('transaction_id', $request->order_id)->first();
        if (! $payment) {
            return response()->json(['success' => false, 'message' => 'payment not found'], 404);
        }

        $status = $request->transaction_status ?? '';

        // Map Midtrans statuses to our enum: pending | completed | failed
        if (in_array($status, ['capture', 'settlement'])) {
            $newStatus = 'completed';
        } elseif (in_array($status, ['pending'])) {
            $newStatus = 'pending';
        } else {
            // expire / cancel / deny / failure -> failed
            $newStatus = 'failed';
        }

        // Update payment_method if provided by Midtrans (maps to our enum)
        $paymentType = $request->payment_type ?? null;
        $method = $payment->payment_method;
        if ($paymentType === 'bank_transfer') {
            $method = 'bank_transfer';
        } elseif ($paymentType === 'credit_card') {
            $method = 'credit_card';
        }

        $payment->update([
            'status' => $newStatus,
            'payment_method' => $method,
        ]);

        if ($newStatus === 'completed') {
            // Enroll student otomatis
            Enrollment::firstOrCreate([
                'student_id' => $payment->student_id,
                'course_id' => $payment->course_id,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Client-side notification from the Snap JS (for immediate update on onSuccess/onPending)
     * Requires the user to be authenticated — called via fetch() from the payment page.
     */
    public function clientNotification(Request $request)
    {
        $user = Auth::user();
        $orderId = $request->order_id ?? $request->orderId ?? null;
        if (! $orderId) {
            return response()->json(['success' => false, 'message' => 'order_id missing'], 400);
        }

        $payment = Payment::where('transaction_id', $orderId)->first();
        if (! $payment) {
            return response()->json(['success' => false, 'message' => 'payment not found'], 404);
        }

        // Only the student who created the payment can update via client-side notification
        if ($payment->student_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 403);
        }

        $status = $request->transaction_status ?? '';
        if (in_array($status, ['capture', 'settlement'])) {
            $newStatus = 'completed';
        } elseif ($status === 'pending') {
            $newStatus = 'pending';
        } else {
            $newStatus = 'failed';
        }

        $method = $payment->payment_method;
        $paymentType = $request->payment_type ?? null;
        if ($paymentType === 'bank_transfer') {
            $method = 'bank_transfer';
        } elseif ($paymentType === 'credit_card') {
            $method = 'credit_card';
        }

        $payment->update(['status' => $newStatus, 'payment_method' => $method]);

        if ($newStatus === 'completed') {
            Enrollment::firstOrCreate([
                'student_id' => $payment->student_id,
                'course_id' => $payment->course_id,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
