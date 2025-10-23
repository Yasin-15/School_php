<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
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

        $studentRole = Role::where('name', 'student')->first();
        $parentRole = Role::where('name', 'parent')->first();

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
                'joining_date' => '2020-01-15',
                'qualification' => 'Master of Education',
                'experience_years' => 5,
                'salary' => 50000,
                'is_active' => true,
            ]
        );

        // Create sample student
        $student = User::firstOrCreate(
            ['email' => 'student@school.com'],
            [
                'name' => 'Jane Student',
                'password' => Hash::make('password'),
                'role_id' => $studentRole->id,
                'phone' => '+1234567892',
                'date_of_birth' => '2010-03-20',
                'gender' => 'female',
                'is_active' => true,
            ]
        );

        // Create student profile
        $firstClass = SchoolClass::first();
        $firstSection = Section::first();
        
        if ($firstClass && $firstSection) {
            Student::firstOrCreate(
                ['user_id' => $student->id],
                [
                    'student_id' => 'STU001',
                    'school_class_id' => $firstClass->id,
                    'section_id' => $firstSection->id,
                    'admission_date' => '2024-01-15',
                    'roll_number' => '001',
                    'parent_name' => 'John Parent',
                    'parent_phone' => '+1234567893',
                    'parent_email' => 'parent@school.com',
                    'is_active' => true,
                ]
            );
        }

        // Create sample parent
        $parent = User::firstOrCreate(
            ['email' => 'parent@school.com'],
            [
                'name' => 'John Parent',
                'password' => Hash::make('password'),
                'role_id' => $parentRole->id,
                'phone' => '+1234567893',
                'date_of_birth' => '1980-07-10',
                'gender' => 'male',
                'is_active' => true,
            ]
        );
    }
}