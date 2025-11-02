<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Menampilkan daftar kursus & pembayaran
     */
    public function index()
    {
        $user = Auth::user();
        $payments = Payment::where('student_id', $user->id)
            ->with('course')
            ->latest()
            ->get();

        $courses = Course::all();
        return view('student.listcourse', compact('payments', 'courses'));
    }

    /**
     * Halaman form pembayaran
     */
    public function create($course_id)
    {
        $course = Course::findOrFail($course_id);
        return view('student.payments.create', compact('course'));
    }

    /**
     * Proses pembayaran melalui Doovera API
     */
    public function store(Request $request, $course_id)
    {
        $student = Auth::user();
        $course = Course::findOrFail($course_id);

        // Kursus gratis langsung enroll
        if (floatval($course->price) <= 0) {
            Payment::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'amount' => 0,
                'payment_method' => 'manual',
                'status' => 'success',
            ]);

            Enrollment::firstOrCreate([
                'student_id' => $student->id,
                'course_id' => $course->id,
            ]);

            return redirect()->route('student.listcourse')->with('success', 'Enrolled (free course).');
        }

        // --- Doovera API Integration ---
        $apiKey = 'vvYmXK7hmX2UFKAypjDnxAqWhsfT5T1n';
        $baseUrl = 'http://payment-dummy.doovera.com/api/v1';
        $externalId = 'ORDER-' . uniqid();
        $webhookUrl = route('student.payment.webhook');

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->post("{$baseUrl}/virtual-account/create", [
                'external_id' => $externalId,
                'amount' => intval($course->price),
                'customer_name' => $student->name,
                'customer_email' => $student->email,
                'description' => "Payment for {$course->title}",
                'expired_duration' => 24,
                'metadata' => [
                    'course_id' => $course->id,
                    'student_id' => $student->id,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Simpan data payment
                $payment = Payment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'amount' => $course->price,
                    'payment_method' => 'bank_transfer',
                    'status' => $data['data']['status'] ?? 'pending',
                    'transaction_id' => $data['data']['va_number'] ?? null,
                ]);

                return redirect()->route('student.listcourse')->with('success', 'Virtual account created successfully. Please complete payment.');
            } else {
                return back()->with('error', 'Failed to create virtual account.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Payment API error: ' . $e->getMessage());
        }
    }

    /**
     * Webhook endpoint untuk notifikasi dari Doovera
     */
    public function webhook(Request $request)
    {
        $payload = $request->json()->all();
        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];

        if (!$event || empty($data['va_number'])) {
            return response()->json(['error' => 'Invalid webhook payload'], 400);
        }

        $payment = Payment::where('transaction_id', $data['va_number'])->first();

        if ($event === 'payment.success') {
            if ($payment) {
                $payment->update(['status' => 'success']);
                Enrollment::firstOrCreate([
                    'student_id' => $payment->student_id,
                    'course_id' => $payment->course_id,
                ]);
            }
        } elseif ($event === 'payment.expired') {
            $payment?->update(['status' => 'expired']);
        } elseif ($event === 'payment.cancelled') {
            $payment?->update(['status' => 'cancelled']);
        }

        return response()->json(['received' => true]);
    }
    public function show($id)
    {
        $course = Course::findOrFail($id);
        return view('student.course', compact('course'));
    }
}
