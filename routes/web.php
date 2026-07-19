<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\HolidayController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [
    DashboardController::class,
    'index'
])
->middleware(['auth', 'verified'])
->name('dashboard');



Route::middleware('auth')->group(function () {

    Route::post('attendances/{employee}/check-in', [AttendanceController::class, 'checkIn'])
        ->middleware('permission:attendance.manage')
        ->name('attendances.checkIn');

    Route::post('attendances/{employee}/check-out', [AttendanceController::class, 'checkOut'])
        ->middleware('permission:attendance.manage')
        ->name('attendances.checkOut');

    Route::get(
        'attendance-reports',
        [
            AttendanceReportController::class,
            'index'
        ]
    )
        ->middleware('permission:reports.view')
        ->name('attendance.reports');

    Route::patch(
        '/leaves/{leave}/approve',
        [LeaveController::class, 'approve']
    )->name('leaves.approve');

    Route::patch(
        '/leaves/{leave}/reject',
        [LeaveController::class, 'reject']
    )->name('leaves.reject');



    /*
    |--------------------------------------------------------------------------
    | Employee Management
    |--------------------------------------------------------------------------
    */

    Route::resource('employees', EmployeeController::class)
        ->middleware('permission:employees.view|employees.create|employees.edit|employees.delete');



    /*
    |--------------------------------------------------------------------------
    | Leave Management
    |--------------------------------------------------------------------------
    */

    Route::resource('leaves', LeaveController::class)
        ->only(['index', 'create', 'store'])
        ->middleware('permission:leave.view|leave.manage');



    /*
    |--------------------------------------------------------------------------
    | Organization Management
    |--------------------------------------------------------------------------
    */

    Route::resource('departments', DepartmentController::class)
        ->middleware('permission:departments.view|departments.manage');

    Route::resource('positions', PositionController::class)
        ->middleware('permission:positions.view|positions.manage');

    Route::resource(
        'shifts',
        ShiftController::class
    );

    Route::resource(
        'holidays',
        HolidayController::class
    );

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class)
        ->except('show')
        ->middleware('permission:users.view|users.create|users.edit|users.delete');

    Route::resource('roles', RoleController::class)
        ->except(['show', 'destroy'])
        ->middleware('permission:roles.manage');



    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])->name('profile.edit');



    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');



    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
    ])->name('profile.destroy');

    Route::resource(
        'attendances',
        AttendanceController::class
    )->middleware('permission:attendance.view|attendance.manage');

});



require __DIR__.'/auth.php';
