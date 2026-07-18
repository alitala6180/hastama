<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index() { return Inertia::render('Roles/Index', ['roles' => Role::with('permissions:id,name')->orderBy('name')->get()]); }
    public function create() { return Inertia::render('Roles/Create', ['permissions' => Permission::orderBy('name')->get(['id', 'name'])]); }
    public function store(Request $request) { $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:roles,name'], 'permissions' => ['array'], 'permissions.*' => ['exists:permissions,name']]); $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']); $role->syncPermissions($data['permissions'] ?? []); return redirect()->route('roles.index')->with('success', 'نقش جدید ایجاد شد.'); }
    public function edit(Role $role) { return Inertia::render('Roles/Edit', ['role' => $role->load('permissions:id,name'), 'permissions' => Permission::orderBy('name')->get(['id', 'name'])]); }
    public function update(Request $request, Role $role) { $data = $request->validate(['name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($role)], 'permissions' => ['array'], 'permissions.*' => ['exists:permissions,name']]); $role->update(['name' => $data['name']]); $role->syncPermissions($data['permissions'] ?? []); return redirect()->route('roles.index')->with('success', 'نقش به‌روزرسانی شد.'); }
}
