<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['name' => 'Grade 1', 'grade_level' => 1, 'academic_year' => '2024-2025', 'max_students' => 30],
            ['name' => 'Grade 2', 'grade_level' => 2, 'academic_year' => '2024-2025', 'max_students' => 30],
            ['name' => 'Grade 3', 'grade_level' => 3, 'academic_year' => '2024-2025', 'max_students' => 30],
            ['name' => 'Grade 4', 'grade_level' => 4, 'academic_year' => '2024-2025', 'max_students' => 30],
            ['name' => 'Grade 5', 'grade_level' => 5, 'academic_year' => '2024-2025', 'max_students' => 30],
            ['name' => 'Grade 6', 'grade_level' => 6, 'academic_year' => '2024-2025', 'max_students' => 30],
            ['name' => 'Grade 7', 'grade_level' => 7, 'academic_year' => '2024-2025', 'max_students' => 30],
            ['name' => 'Grade 8', 'grade_level' => 8, 'academic_year' => '2024-2025', 'max_students' => 30],
            ['name' => 'Grade 9', 'grade_level' => 9, 'academic_year' => '2024-2025', 'max_students' => 30],
            ['name' => 'Grade 10', 'grade_level' => 10, 'academic_year' => '2024-2025', 'max_students' => 30],
        ];

        foreach ($classes as $class) {
            SchoolClass::firstOrCreate(
                ['name' => $class['name'], 'academic_year' => $class['academic_year']],
                $class
            );
        }
    }
}