<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class LeaveStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_leave_can_be_created_without_explicit_days_field(): void
    {
        $user = User::factory()->create();
        Permission::create(['name' => 'leave.manage', 'guard_name' => 'web']);
        Permission::create(['name' => 'leave.view', 'guard_name' => 'web']);
        $user->givePermissionTo(['leave.manage', 'leave.view']);

        $employee = Employee::create([
            'employee_code' => 'EMP-001',
            'first_name' => 'Ali',
            'last_name' => 'Rahimi',
            'national_code' => '1234567890',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('leaves.store'), [
            'employee_id' => $employee->id,
            'type' => 'annual',
            'start_date' => '2026-07-20',
            'end_date' => '2026-07-22',
            'reason' => 'Test leave request',
        ]);

        $response->assertRedirect(route('leaves.index'));
        $this->assertDatabaseHas('leaves', [
            'employee_id' => $employee->id,
            'days' => 3,
        ]);
    }
}
