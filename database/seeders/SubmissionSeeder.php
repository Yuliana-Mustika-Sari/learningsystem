<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submission;
use App\Models\User;
use App\Models\Assignment;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('role_id', '3')->first();
        $assignment = Assignment::first();

        Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'file_url' => 'submissions/student1_assignment1.zip',
            'submitted_at' => now(),
            'score' => 95,
            'feedback' => 'Great job! Clean code and clear structure.',
        ]);
    }
}
