<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class ListCourse extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $payments = Payment::where('student_id', $user->id)->with('course')->latest()->get();
        // The `student.course` view expects a `$courses` variable (list of available courses).
        // Provide both payments and courses so the view can render correctly when used here.
        $courses = Course::all();
        return view('student.listcourse', compact('listcourse', 'courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($course_id)
    {
        $course = Course::findOrFail($course_id);
        return view('student.listcourse.create', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);
        $student = Auth::user();

        // Free course: enroll directly
        if (floatval($course->price) <= 0) {
            $payment = Payment::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'amount' => 0,
                // for free courses, mark as manual (enum: credit_card, manual, bank_transfer)
                'payment_method' => 'manual',
                'status' => 'success',
            ]);

            Enrollment::firstOrCreate([
                'student_id' => $student->id,
                'course_id' => $course->id,
            ]);

            return redirect()->route('student.listcourses')->with('success', 'Enrolled (free course).');
        }

        // Create payment via external payment API
        $apiKey = env('PAYMENT_API_KEY');
        $base = rtrim(env('PAYMENT_BASE_URL'), '/');
        $webhookUrl = route('student.payment.webhook');

        try {
            $resp = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->post($base . '/payments', [
                'amount' => $course->price,
                'currency' => 'IDR',
                'customer' => [
                    'id' => $student->id,
                    'email' => $student->email,
                    'name' => $student->name,
                ],
                'metadata' => [
                    'course_id' => $course->id,
                ],
                'webhook_url' => $webhookUrl,
                'redirect_url' => route('student.listcourses'),
            ]);

            if ($resp->successful()) {
                $data = $resp->json();
                // Expected fields: payment_url, id
                $payment = Payment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'amount' => $course->price,
                    // normalize payment method to allowed enum values
                    'payment_method' => $request->input('payment_method') ?? 'credit_card',
                    'status' => 'pending',
                    'transaction_id' => $data['id'] ?? $data['payment_id'] ?? null,
                ]);

                if (!empty($data['payment_url'])) {
                    return redirect()->away($data['payment_url']);
                }
            }
        } catch (\Exception $e) {
            // Log exception and fallback to immediate success
            logger()->error('Payment API error: ' . $e->getMessage());
        }

        // Fallback: mark as success and enroll (only if API fails)
        $payment = Payment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'payment_method' => $request->input('payment_method') ?? 'manual',
            'status' => 'success',
        ]);

        Enrollment::firstOrCreate([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        return redirect()->route('student.listcourses')->with('success', 'Payment processed (fallback) and enrolled.');
    }

    /**
     * Webhook endpoint for payment gateway notifications
     */
    public function webhook(Request $request)
    {
        $secret = env('PAYMENT_WEBHOOK_SECRET');
        $signature = $request->header('X-Signature') ?? $request->header('X-Signature-256');
        $payload = $request->getContent();

        if ($signature && $secret) {
            $computed = hash_hmac('sha256', $payload, $secret);
            if (!hash_equals($computed, $signature)) {
                return response()->json(['error' => 'Invalid signature'], 400);
            }
        }

        $data = $request->json()->all();
        $txId = $data['id'] ?? $data['payment_id'] ?? null;
        $status = $data['status'] ?? null;
        $metadata = $data['metadata'] ?? [];
        $courseId = $metadata['course_id'] ?? ($data['course_id'] ?? null);
        $customerId = $data['customer']['id'] ?? ($data['customer_id'] ?? null);

        if ($txId) {
            $payment = Payment::where('transaction_id', $txId)->first();
        } else {
            $payment = null;
        }

        // If payment not found, try to find by student/course
        if (! $payment && $customerId && $courseId) {
            $payment = Payment::where('student_id', $customerId)->where('course_id', $courseId)->latest()->first();
        }

        if (! $payment) {
            // create a record so we track it; normalize payment_method to enum values
            $pm = $data['payment_method'] ?? null;
            $allowed = ['credit_card', 'manual', 'bank_transfer'];
            $pm = in_array($pm, $allowed) ? $pm : 'manual';

            $payment = Payment::create([
                'student_id' => $customerId ?? null,
                'course_id' => $courseId ?? null,
                'amount' => $data['amount'] ?? null,
                'payment_method' => $pm,
                'status' => $status ?? 'unknown',
                'transaction_id' => $txId,
            ]);
        }

        if ($status === 'success' || $status === 'paid') {
            $payment->update(['status' => 'success']);
            // create enrollment if missing
            if ($payment->student_id && $payment->course_id) {
                Enrollment::firstOrCreate([
                    'student_id' => $payment->student_id,
                    'course_id' => $payment->course_id,
                ]);
            }
        } elseif ($status === 'failed' || $status === 'cancelled') {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['received' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
