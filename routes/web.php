<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
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



    /*
    |--------------------------------------------------------------------------
    | Employee Management
    |--------------------------------------------------------------------------
    */


    Route::resource('employees', EmployeeController::class);



    /*
    |--------------------------------------------------------------------------
    | Organization Management
    |--------------------------------------------------------------------------
    */


    Route::resource('departments', DepartmentController::class);


    Route::resource('positions', PositionController::class);





    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */


    Route::resource('users', UserController::class);





    /*
    |--------------------------------------------------------------------------
    | User Profile
    |--------------------------------------------------------------------------
    */


    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])
    ->name('profile.edit');



    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])
    ->name('profile.update');



    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
    ])
    ->name('profile.destroy');



});



require __DIR__.'/auth.php';