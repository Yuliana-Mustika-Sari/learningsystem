<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $permissions = [
        // Admin
        ['name' => 'dashboard_admin', 'display_name' => 'Dashboard Admin', 'group' => 'admin'],
        ['name' => 'user_management', 'display_name' => 'User Management', 'group' => 'admin'],
        ['name' => 'course_management', 'display_name' => 'Course Management', 'group' => 'admin'],
        ['name' => 'reports', 'display_name' => 'Reports', 'group' => 'admin'],

        // Instructor
        ['name' => 'dashboard_instructor', 'display_name' => 'Dashboard Instructor', 'group' => 'instructor'],
        ['name' => 'course_instructor', 'display_name' => 'Course Management (Instructor)', 'group' => 'instructor'],
        ['name' => 'module_management', 'display_name' => 'Module Management', 'group' => 'instructor'],
        ['name' => 'assignment_management', 'display_name' => 'Assignment Management', 'group' => 'instructor'],
        ['name' => 'submission_review', 'display_name' => 'Submission Review', 'group' => 'instructor'],
        ['name' => 'discussion_management', 'display_name' => 'Discussion Management', 'group' => 'instructor'],
        ['name' => 'certificate_instuctor', 'display_name' => 'Certificate Management', 'group' => 'instructor'],

        // Student
        ['name' => 'dashboard_student', 'display_name' => 'Dashboard Student', 'group' => 'student'],
        ['name' => 'course_student', 'display_name' => 'Course Access (Student)', 'group' => 'student'],
        ['name' => 'submit_assignment', 'display_name' => 'Submit Assignment', 'group' => 'student'],
        ['name' => 'details_course', 'display_name' => 'Course Details', 'group' => 'student'],
        ['name' => 'discussion_student', 'display_name' => 'Discussion (Student)', 'group' => 'student'],
        ['name' => 'progress_tracking', 'display_name' => 'Progress Tracking', 'group' => 'student'],
        ['name' => 'certificate_student', 'display_name' => 'Download Certificate', 'group' => 'student'],
        ['name' => 'payment', 'display_name' => 'Payment', 'group' => 'student'],
    ];
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
