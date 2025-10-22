<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\SchoolClass;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $classes = SchoolClass::all();
        $sectionNames = ['A', 'B', 'C'];

        foreach ($classes as $class) {
            foreach ($sectionNames as $sectionName) {
                Section::firstOrCreate(
                    [
                        'name' => $sectionName,
                        'school_class_id' => $class->id,
                    ],
                    [
                        'max_students' => 25,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}