<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Determine whether the user can view any courses.
     */
    public function viewAny(User $user): bool
    {
        // Admin dan Instructor bisa lihat daftar course
        return in_array($user->role_id, [1, 2]);
    }

    /**
     * Determine whether the user can view the course.
     */
    public function view(User $user, Course $course): bool
    {
        // Admin bisa lihat semua, Instructor hanya course miliknya
        return $user->role_id === 1 || $course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can create courses.
     */
    public function create(User $user): bool
    {
        // Hanya Instructor yang bisa buat course
        return $user->role_id === 2;
    }

    /**
     * Determine whether the user can update the course.
     */
    public function update(User $user, Course $course): bool
    {
        // Admin bisa update semua, Instructor hanya course miliknya
        return $user->role_id === 1 || $course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can delete the course.
     */
    public function delete(User $user, Course $course): bool
    {
        // Admin bisa hapus semua, Instructor hanya course miliknya
        return $user->role_id === 1 || $course->instructor_id === $user->id;
    }
}
