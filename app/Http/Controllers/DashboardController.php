<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [

            'stats' => [

                'employees' => Employee::count(),

                'activeEmployees' => Employee::where(
                    'status',
                    'active'
                )->count(),

                'inactiveEmployees' => Employee::where(
                    'status',
                    '!=',
                    'active'
                )->count(),

                'departments' => Department::count(),

                'positions' => Position::count(),

            ],


            'latestEmployees' => Employee::with([

                'department:id,name',

                'position:id,name',

            ])

            ->select([

                'id',

                'employee_code',

                'first_name',

                'last_name',

                'department_id',

                'position_id',

                'status',

                'annual_leave',

            ])

            ->latest()

            ->take(5)

            ->get()

            ->append('remaining_leave'),


            'authUser' => [

                'name' => auth()->user()->name,

                'email' => auth()->user()->email,

            ],

        ]);
    }
}