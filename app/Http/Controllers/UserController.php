<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
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

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $role = $data['role'];
        unset($data['role']);

        $user = User::create($data);
        $user->syncRoles([$role]);

        return redirect()->route('users.index')->with('success', 'کاربر با موفقیت ایجاد شد.');
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

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $role = $data['role'];
        unset($data['role']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);
        $user->syncRoles([$role]);

        return redirect()->route('users.index')->with('success', 'اطلاعات کاربر به‌روزرسانی شد.');
    }

    public function destroy(User $user)
    {
        abort_unless(request()->user()->can('users.delete'), 403);
        abort_if($user->is(auth()->user()), 422, 'امکان حذف حساب کاربری فعلی وجود ندارد.');

        $user->delete();

        return back()->with(
            'success',
            'کاربر حذف شد.'
        );
    }
}
