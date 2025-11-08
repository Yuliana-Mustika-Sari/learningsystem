<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function confirm(Course $course)
    {
        return view('student.payments.confirm', compact('course'));
    }
    public function process(Course $course)
    {
        $expiredHours = (int) config('services.payment.expired_hours', 24);

        $payment = Payment::create([
            'student_id' => Auth::id(),
            'course_id' => $course->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'quantity' => 1,
            'price' => $course->price,
            'total_amount' => $course->price,
            'payment_status' => 'pending',
            'expired_at' => now()->addHours($expiredHours),
        ]);
        // validate payment service config
        $baseUrl = config('services.payment.base_url');
        $apiKey = config('services.payment.api_key');
        if (!$baseUrl || !$apiKey) {
            $payment->update(['payment_status' => 'failed']);
            return redirect()->route('student.listcourse')->with('error', 'Payment gateway not configured. Please contact the administrator.');
        }

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => config('services.payment.api_key'),
                'Accept' => 'application/json',
            ])->withoutVerifying()->post(config('services.payment.base_url') . '/virtual-account/create', [
                'external_id' => $payment->order_number,
                'amount' => $payment->total_amount,
                'customer_name' => Auth::user()->name,
                'customer_email' => Auth::user()->email,
                'customer_phone' => Auth::user()->phone ?? '081234567890',
                'description' => 'Payment for ' . $course->title,
                'expired_at' => $expiredHours,
                // use the public webhook endpoint (does not require a payment id)
                'callback_url' => route('payment.webhook'),
                'metadata' => [
                    'student_id' => Auth::id(),
                    'course_id' => $course->id,
                ],
            ]);
            if ($response->successful()){
                $data = $response->json();
                $payment->update([
                    'va_number' => $data['data']['va_number'],
                    'payment_url' => $data['data']['payment_url'],
                ]);
                return redirect()->route('student.payment.waiting',$payment);
            } else{
                // log API response for debugging
                Log::error('Payment initiation failed', ['status' => $response->status(), 'body' => $response->body()]);
                $payment->update(['payment_status' => 'failed']);
                return redirect()->route('student.listcourse')->with('error', 'Payment initiation failed. Please try again.');
            }
        }
        catch (\Exception $e) {
            Log::error('Payment initiation exception', ['exception' => $e->getMessage()]);
            $payment->update(['payment_status' => 'failed']);
            return redirect()->route('student.listcourse')->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }
    public function waiting(Payment $payment)
    {
        if ($payment->isPaid()){
            return redirect()->route('student.payment.success', $payment);
        }
        if ($payment->isExpired()){
            return redirect()->route('student.listcourse')->with('error', 'Payment has expired. Please try again.');
        }
        return view('student.payments.waiting', compact('payment'));
    }

    public function checkStatus(Payment $payment)
    {
        return response()->json([
            'status' => $payment->payment_status,
            'paid_at' => $payment->paid_at?->toISOString()
        ]);
    }
    public function success(Payment $payment)
    {
        if (!$payment->isPaid()) {
            return redirect()->route('student.listcourse')->with('error', 'Payment not completed yet.');
        }

        $enrollment = Enrollment::firstOrCreate([
            'student_id' => $payment->student_id,
            'course_id' => $payment->course_id,
        ], [
            'enrolled_at' => now(),
        ]);

        return view('student.payments.success', compact('payment'));
    }

    /**
     * Handle gateway callback (webhook or browser redirect).
     */
    public function callback(Request $request)
    {
        // Support both POST webhooks and GET browser redirects
        $status = $request->input('status', $request->query('status'));
        $va = $request->input('va_number', $request->query('va_number'));
        $externalId = $request->input('external_id', $request->query('external_id'));

        // Try to find payment by va_number first, then by external_id (order_number)
        $payment = null;
        if ($va) {
            $payment = Payment::where('va_number', $va)->first();
        }
        if (!$payment && $externalId) {
            $payment = Payment::where('order_number', $externalId)->first();
        }

        if (!$payment) {
            Log::warning('Payment callback received but payment not found', ['va' => $va, 'external_id' => $externalId, 'payload' => $request->all()]);
            // If browser redirect, send back to course list
            if ($request->isMethod('get')) {
                return redirect()->route('student.listcourse')->with('error', 'Payment record not found.');
            }
            return response()->json(['error' => 'payment not found'], 404);
        }

        // Map gateway status to our payment_status
        $map = [
            'success' => 'paid',
            'paid' => 'paid',
            'pending' => 'pending',
            'failed' => 'failed',
            'expired' => 'expired',
        ];

        $mapped = $map[strtolower($status ?? '')] ?? null;
        if ($mapped) {
            $payment->update([
                'payment_status' => $mapped,
                'paid_at' => $mapped === 'paid' ? now() : $payment->paid_at,
            ]);
        }

        if ($payment->isPaid()) {
            Enrollment::firstOrCreate([
                'student_id' => $payment->student_id,
                'course_id' => $payment->course_id,
            ], ['enrolled_at' => now()]);
        }

        // If browser redirect, take user to success page or list
        if ($request->isMethod('get')) {
            if ($payment->isPaid()) {
                return redirect()->route('student.payment.success', $payment);
            }
            return redirect()->route('student.listcourse')->with('error', 'Payment not completed.');
        }

        // For webhook POSTs respond with json OK
        return response()->json(['status' => 'ok']);
    }
}
