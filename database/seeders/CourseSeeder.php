<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::where('role_id', '2')->first();
        Course::create([
            'title' => 'Intro to Laravel',
            'description' => 'Learn the basics of Laravel framework.',
            'instructor_id' => $instructor->id,
            'item_type' => 'module',
            'item_id' => 1,
            'price' => 10000,
            'order_number' => 1,
            'thumbnail' => 'laravel.png',
        ]);
    }
}
