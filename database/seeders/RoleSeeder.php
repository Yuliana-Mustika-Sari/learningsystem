<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // matiin FK check
        Role::truncate();                           // kosongkan roles
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // hidupin lagi
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrator with full access',
                'is_active' => true,
            ],
            [
                'name' => 'instructor',
                'display_name' => 'Instructor',
                'description' => 'Instructor with course management permissions',
                'is_active' => true,
            ],
            [
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Student with access to enrolled courses',
                'is_active' => true,
            ],

        ];
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
