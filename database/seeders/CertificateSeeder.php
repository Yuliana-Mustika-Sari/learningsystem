<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;
use App\Models\User;
use App\Models\Course;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('role_id', '3')->first();
        $course = Course::first();

        Certificate::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'issue_date' => now(),
            'certificate_url' => 'certificates/laravel-basic.pdf',
        ]);
    }
}
