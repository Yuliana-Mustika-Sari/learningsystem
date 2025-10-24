<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->first();
        $admin->permissions()->sync(Permission::whereIn('name', [
            'dashboard_admin',
            'user_management',
            'course_management',
            'reports',
        ])->get());

        $instructor = Role::where('name', 'instructor')->first();
        $instructor->permissions()->sync(Permission::whereIn('name', [
            'dashboard_instructor',
            'course_instructor',
            'module_management',
            'assignment_management',
            'submission_review',
            'discussion_management',
            'certificate',
        ])->get());


        $student = Role::where('name', 'student')->first();
        $student->permissions()->sync(Permission::whereIn('name', [
            'dashboard_student',
            'course_student',
            'submit_assignment',
            'details_course',
            'discussion_student',
            'progress_tracking',
            'certificate',
            'payment',
        ])->get());
    }
}
