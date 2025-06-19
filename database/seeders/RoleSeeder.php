<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role_name' => 'Admin', 'description' => 'System administrator with full access'],
            ['role_name' => 'Manager', 'description' => 'Department manager with approval permissions'],
            ['role_name' => 'Employee', 'description' => 'Regular employee with standard access'],
            ['role_name' => 'Student', 'description' => 'Student user with limited access'],
            ['role_name' => 'Teacher', 'description' => 'Faculty member with teaching permissions'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
