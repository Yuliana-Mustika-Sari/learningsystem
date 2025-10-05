<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discussion;
use App\Models\User;
use App\Models\Course;

class DiscussionSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::first();
        $student = User::where('role_id', '3')->first();

        Discussion::create([
            'course_id' => $course->id,
            'user_id' => $student->id,
            'content' => 'I love this course!',
            'parent_post_id' => null,
        ]);
    }
}
