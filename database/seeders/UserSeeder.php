<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $instructorRole = Role::where('name', 'instructor')->first();
        $studentRole = Role::where('name', 'student')->first();
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12345'),
                'role_id' => $adminRole->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'instructor@example.com'],
            [
                'name' => 'John Instructor',
                'password' => Hash::make('12345'),
                'role_id' => $instructorRole->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Jane Student',
                'password' => Hash::make('12345'),
                'role_id' => $studentRole->id,
            ]
        );
    }
}
