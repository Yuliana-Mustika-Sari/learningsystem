<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Course;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::first();

        Module::create([
            'course_id' => $course->id,
            'title' => 'Getting Started with Laravel',
            'description' => 'Setup Laravel project and environment.',
            'due_date' => now()->addDays(7),
            'max_score' => 100,
            'content_type' => 'video',
            'status' => true,
        ]);
    }
}
