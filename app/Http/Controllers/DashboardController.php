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
                'departments' => Department::count(),
                'positions' => Position::count(),
            ],


            'latestEmployees' => Employee::with([
                'department',
                'position'
            ])
            ->latest()
            ->take(5)
            ->get(),

        ]);
    }
}