<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'phone' => '+1234567890',
                'is_active' => true,
            ]
        );

        // Create sample teacher
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@school.com'],
            [
                'name' => 'John Teacher',
                'password' => Hash::make('password'),
                'role_id' => $teacherRole->id,
                'phone' => '+1234567891',
                'date_of_birth' => '1985-05-15',
                'gender' => 'male',
                'is_active' => true,
            ]
        );

        // Create teacher profile
        Teacher::firstOrCreate(
            ['user_id' => $teacher->id],
            [
                'employee_id' => 'TCH001',
                'hire_date' => '2020-01-15',
                'qualification' => 'Master of Education',
                'experience_years' => 5,
                'salary' => 50000,
                'is_active' => true,
            ]
        );
    }
}