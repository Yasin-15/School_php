<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\SchoolClass;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $classes = SchoolClass::all();
        
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH', 'credits' => 4],
            ['name' => 'English', 'code' => 'ENG', 'credits' => 4],
            ['name' => 'Science', 'code' => 'SCI', 'credits' => 3],
            ['name' => 'Social Studies', 'code' => 'SS', 'credits' => 3],
            ['name' => 'Physical Education', 'code' => 'PE', 'credits' => 2],
            ['name' => 'Art', 'code' => 'ART', 'credits' => 2],
            ['name' => 'Music', 'code' => 'MUS', 'credits' => 2],
        ];

        foreach ($classes as $class) {
            foreach ($subjects as $subject) {
                Subject::firstOrCreate(
                    [
                        'code' => $subject['code'] . $class->grade_level,
                        'school_class_id' => $class->id,
                    ],
                    [
                        'name' => $subject['name'],
                        'credits' => $subject['credits'],
                        'description' => $subject['name'] . ' for ' . $class->name,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}