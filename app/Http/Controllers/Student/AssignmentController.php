<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Show assignments for a given course to the authenticated student.
     */
    public function index($courseId)
    {
        $user = Auth::user();

        $course = Course::with('assignments')->findOrFail($courseId);

        // Check access: free course or student enrolled or has successful payment
        $isEnrolled = Enrollment::where('student_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        $hasPaid = Payment::where('student_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('payment_status', ['completed', 'settlement', 'capture', 'success', 'paid'])
            ->exists();

        if ($course->price > 0 && ! $isEnrolled && ! $hasPaid) {
            return redirect()->route('student.pay', $course->id)
                ->with('info', 'Silakan lakukan pembayaran untuk mengakses materi dan tugas (assignments).');
        }

        // Pass course + assignments to view
        return view('student.assignments.index', [
            'course' => $course,
            'assignments' => $course->assignments,
        ]);
    }
}
