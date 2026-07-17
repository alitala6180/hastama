<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()

            ->with([
                'employee:id,first_name,last_name',
                'roles:id,name',
            ])

            ->when($request->search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");

                });

            })

            ->latest()

            ->paginate(10)

            ->withQueryString();

        return Inertia::render('Users/Index', [

            'users' => $users,

            'filters' => [

                'search' => $request->search,

            ],

        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create', [

            'employees' => Employee::select(
                'id',
                'first_name',
                'last_name'
            )->get(),

            'roles' => Role::select(
                'id',
                'name'
            )->get(),

        ]);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [

            'user' => $user->load([
                'employee',
                'roles'
            ]),

            'employees' => Employee::select(
                'id',
                'first_name',
                'last_name'
            )->get(),

            'roles' => Role::select(
                'id',
                'name'
            )->get(),

        ]);
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with(
            'success',
            'کاربر حذف شد.'
        );
    }
}