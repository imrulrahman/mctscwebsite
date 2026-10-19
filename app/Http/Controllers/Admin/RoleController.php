<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\PermissionManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    private function authorizeRoleAccess(): void
    {
        $user = auth()->user();
        abort_unless($user && ($user->isSystemAdmin() || $user->can('roles.manage')), 403, 'Unauthorized access. Only System Administrators can manage roles.');
    }

    public function index(): View
    {
        $this->authorizeRoleAccess();

        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $this->authorizeRoleAccess();

        $role = new Role();
        $role->permissions = [];
        $permissionGroups = PermissionManager::allGroups();

        return view('admin.roles.form', compact('role', 'permissionGroups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoleAccess();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:roles,slug'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        if (Role::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        Role::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'is_system' => false,
            'permissions' => $validated['permissions'] ?? [],
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'New role created successfully.');
    }

    public function edit(Role $role): View
    {
        $this->authorizeRoleAccess();

        $permissionGroups = PermissionManager::allGroups();
        return view('admin.roles.form', compact('role', 'permissionGroups'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $this->authorizeRoleAccess();

        $rules = [
            'name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($role->id)],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ];

        if (!$role->is_system) {
            $rules['slug'] = ['nullable', 'string', 'max:100', Rule::unique('roles', 'slug')->ignore($role->id)];
        }

        $validated = $request->validate($rules);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'permissions' => $validated['permissions'] ?? [],
        ];

        if (!$role->is_system && !empty($validated['slug'])) {
            $data['slug'] = Str::slug($validated['slug']);
        }

        $role->update($data);

        return redirect()->route('admin.roles.index')->with('success', 'Role and permissions updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorizeRoleAccess();

        if ($role->is_system) {
            return back()->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role while users are assigned to it. Reassign users first.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
