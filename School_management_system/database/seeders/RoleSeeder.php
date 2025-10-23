<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'System Administrator'],
            ['name' => 'teacher', 'display_name' => 'Teacher', 'description' => 'Teacher'],
            ['name' => 'student', 'display_name' => 'Student', 'description' => 'Student'],
            ['name' => 'parent', 'display_name' => 'Parent', 'description' => 'Parent/Guardian'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}