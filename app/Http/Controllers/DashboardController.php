<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role_id == 1) {
            // Admin data
            $data = [
                'title' => 'Admin Dashboard',
                'stats' => [
                    'Users' => \App\Models\User::count(),
                    'Courses' => \App\Models\Course::count(),
                    'Assignment' => \App\Models\Assignment::count(),
                ],
            ];
        } elseif ($user->role_id == 2) {
            // Instructor data
            $data = [
                'title' => 'Instructor Dashboard',
                'stats' => [
                    'My_Course' => \App\Models\Course::where('instructor_id', $user->id)->count(),
                    'Assignments' => \App\Models\Assignment::count(),
                    'Students' => \App\Models\User::where('role_id', 3)->count(),
                ],
            ];
        } else {
            // Student data
            $totalAssignments = \App\Models\Assignment::count();
            $totalSubmissions = \App\Models\Submission::where('student_id', $user->id)->count();

            $progress = $totalAssignments > 0
                ? intval(($totalSubmissions / $totalAssignments) * 100) . '%'
                : '0%';

            $data = [
                'title' => 'Student Dashboard',
                'stats' => [
                    'Assignments_Submitted' => $totalSubmissions,
                    'progress' => $progress,
                    'Certificates' => \App\Models\Certificate::where('student_id', $user->id)->count(),
                ],
            ];
        }

        return view('dashboard.index', compact('user', 'data'));
    }
}
