<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::create([
            'name' => 'مدیریت',
        ]);


        $position = Position::create([
            'name' => 'مدیر سیستم',
        ]);


        $employee = Employee::create([
            'employee_code' => '1000',
            'first_name' => 'مدیر',
            'last_name' => 'سیستم',
            'national_code' => '0000000000',
            'mobile' => '09120000000',
            'department_id' => $department->id,
            'position_id' => $position->id,
            'status' => 'active',
        ]);


        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@hastama.ir',
            'password' => Hash::make('12345678'),
            'employee_id' => $employee->id,
        ]);
    }
}