<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::create([
            'name' => 'مدیریت',
            'code' => 'MNG',
            'description' => 'واحد مدیریت سازمان',
        ]);

        $position = Position::create([
            'department_id' => $department->id,
            'name' => 'مدیر سیستم',
        ]);

        $employee = Employee::create([
            'employee_code' => 'EMP-0001',
            'first_name' => 'علی',
            'last_name' => 'شاکری',
            'national_code' => '0012345678',
            'department_id' => $department->id,
            'position_id' => $position->id,
            'status' => 'active',
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@hastama.ir',
            'employee_id' => $employee->id,
            'password' => bcrypt('12345678'),
        ]);
    }
}