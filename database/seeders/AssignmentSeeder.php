<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\Course;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::first();

        Assignment::create([
            'course_id' => $course->id,
            'title' => 'First Laravel Assignment',
            'description' => 'Build a simple CRUD app in Laravel.',
            'due_date' => now()->addDays(10),
            'max_score' => 100,
        ]);
    }
}
