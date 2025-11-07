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
                'callback_url' => route('payment.success'),
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
                $payment->update([
                    'payment_status' => 'failed',
                ]);
                return redirect()->route('student.listcourse')->with('error', 'Payment initiation failed. Please try again.');
            }
        }
        catch (\Exception $e) {
            $payment->update([
                'payment_status' => 'failed',
            ]);
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
}
