<?php

namespace Tests\Feature;

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HolidayPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_role_has_holiday_permissions_after_seeding(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $adminRole = Role::findByName('admin');

        $this->assertTrue($adminRole->hasPermissionTo('holidays.view'));
        $this->assertTrue($adminRole->hasPermissionTo('holidays.create'));
        $this->assertTrue($adminRole->hasPermissionTo('holidays.edit'));
        $this->assertTrue($adminRole->hasPermissionTo('holidays.delete'));
    }
}
