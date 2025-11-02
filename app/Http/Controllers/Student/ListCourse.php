<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class ListCourse extends Controller
{
    /**
     * Menampilkan semua course & status pembayaran/enrollment student.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil semua course
        $courses = Course::with(['instructor'])->get();

        // Ambil data pembayaran student
        $payments = Payment::where('student_id', $user->id)->get();

        // Ambil course yang sudah di-enroll student
        $enrollments = Enrollment::where('student_id', $user->id)->pluck('course_id')->toArray();

        return view('student.listcourse', compact('courses', 'payments', 'enrollments'));
    }

    /**
     * Menampilkan detail course.
     * Jika belum bayar, arahkan untuk melakukan pembayaran.
     */
    public function show($id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);

        // Cek apakah sudah punya payment sukses atau sudah enroll
        $hasPaid = Payment::where('student_id', $user->id)
            ->where('course_id', $id)
            ->where('status', 'completed')
            ->exists();

        $isEnrolled = Enrollment::where('student_id', $user->id)
            ->where('course_id', $id)
            ->exists();

        if ($course->price > 0 && !$hasPaid && !$isEnrolled) {
            return redirect()->route('student.pay', $course->id)
                ->with('info', 'Silakan lakukan pembayaran untuk mengakses course ini.');
        }

        return view('student.course', compact('course'));
    }
}
