<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // کاربران
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.manage',


            // پرسنل
            'employees.view',
            'employees.create',
            'employees.edit',
            'employees.delete',


            // واحدها
            'departments.view',
            'departments.manage',


            // سمت ها
            'positions.view',
            'positions.manage',


            // حضور و غیاب
            'attendance.view',
            'attendance.manage',


            // مرخصی
            'leave.view',
            'leave.manage',


            // گزارشات
            'reports.view',

        ];



        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);

        }



        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */


        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);


        $hr = Role::firstOrCreate([
            'name' => 'hr_manager',
            'guard_name' => 'web',
        ]);


        $employee = Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'web',
        ]);



        /*
        |--------------------------------------------------------------------------
        | Assign permissions
        |--------------------------------------------------------------------------
        */


        $admin->givePermissionTo(
            Permission::all()
        );



        $hr->givePermissionTo([

            'employees.view',
            'employees.create',
            'employees.edit',
            'employees.delete',

            'departments.view',
            'positions.view',

            'attendance.view',
            'attendance.manage',

            'leave.view',
            'leave.manage',

            'reports.view',

        ]);



        $employee->givePermissionTo([

            'attendance.view',
            'leave.view',

        ]);



        /*
        |--------------------------------------------------------------------------
        | Assign admin role to first user
        |--------------------------------------------------------------------------
        */


        $user = User::first();


        if ($user) {

            $user->assignRole('admin');

        }

    }
}
