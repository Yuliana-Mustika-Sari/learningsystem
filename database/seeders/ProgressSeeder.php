<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Progress;
use App\Models\User;
use App\Models\Course;
use App\Models\Module;

class ProgressSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('role_id', '3')->first();
        $course = Course::first();
        $module = Module::first();

        Progress::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'completion_rate' => 0.75, // 75% selesai
            'completed_at' => null,    // belum full complete
            'last_accessed' => now(),
        ]);
    }
}
