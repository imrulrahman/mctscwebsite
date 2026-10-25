<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\PermissionManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    private function authorizeUserAccess(string $permission): void
    {
        $user = auth()->user();
        abort_unless($user && ($user->isSystemAdmin() || $user->can($permission)), 403, 'Unauthorized. You do not have permission to perform this user action.');
    }

    public function index(Request $request): View
    {
        $this->authorizeUserAccess('users.view');

        $query = User::with('role')->latest();

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    private function mirrorToPublic(string $path): void
    {
        try {
            $src = storage_path('app/public/' . $path);
            $localDest = public_path('storage/' . $path);
            $cpanelDest = '/home/mctscedu/public_html/storage/' . $path;

            foreach ([$localDest, $cpanelDest] as $dest) {
                $dir = dirname($dest);
                if (!file_exists($dir)) {
                    @mkdir($dir, 0755, true);
                }
                if (file_exists($src)) {
                    @copy($src, $dest);
                }
            }
        } catch (\Throwable $e) {
            // Ignore mirror copy errors
        }
    }

    public function create(): View
    {
        $this->authorizeUserAccess('users.create');

        $user = new User();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.form', compact('user', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeUserAccess('users.create');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $role = Role::find($validated['role_id']);
        $isAdmin = true;

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $this->mirrorToPublic($avatarPath);
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'avatar' => $avatarPath,
            'role_id' => $validated['role_id'],
            'status' => $validated['status'],
            'is_admin' => $isAdmin,
            'password' => Hash::make(trim($validated['password'])),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'New user account created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorizeUserAccess('users.edit');

        $roles = Role::orderBy('name')->get();
        return view('admin.users.form', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeUserAccess('users.edit');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        // Safety: Prevent locking out the system admin
        $newRole = Role::find($validated['role_id']);
        if ($user->isSystemAdmin() && ($newRole->slug !== 'system-admin' || $validated['status'] !== 'active')) {
            $otherSystemAdmins = User::where('id', '!=', $user->id)
                ->where('status', 'active')
                ->whereHas('role', fn($q) => $q->where('slug', 'system-admin'))
                ->count();

            if ($otherSystemAdmins === 0) {
                return back()->withInput()->with('error', 'Cannot demote or deactivate the last active System Administrator.');
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role_id' => $validated['role_id'],
            'status' => $validated['status'],
            'is_admin' => true,
        ];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
            $this->mirrorToPublic($data['avatar']);
        } elseif ($request->boolean('remove_avatar')) {
            $data['avatar'] = null;
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make(trim($validated['password']));
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User account updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeUserAccess('users.delete');

        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->isSystemAdmin()) {
            $otherSystemAdmins = User::where('id', '!=', $user->id)
                ->where('status', 'active')
                ->whereHas('role', fn($q) => $q->where('slug', 'system-admin'))
                ->count();

            if ($otherSystemAdmins === 0) {
                return back()->with('error', 'Cannot delete the last active System Administrator.');
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User account deleted successfully.');
    }

    /**
     * Show individual user permission customization screen.
     */
    public function permissions(User $user): View
    {
        $this->authorizeUserAccess('users.permissions');

        $user->load('role');
        $permissionGroups = PermissionManager::allGroups();
        $customPermissions = $user->custom_permissions ?? [];

        return view('admin.users.permissions', compact('user', 'permissionGroups', 'customPermissions'));
    }

    /**
     * Update individual user permission overrides.
     */
    public function updatePermissions(Request $request, User $user): RedirectResponse
    {
        $this->authorizeUserAccess('users.permissions');

        $submittedKeys = $request->input('permissions', []);
        if (!is_array($submittedKeys)) {
            $submittedKeys = [];
        }

        $role = $user->role;
        $allKeys = PermissionManager::allKeys();
        $customOverrides = [];

        foreach ($allKeys as $key) {
            $isChecked = in_array($key, $submittedKeys, true);
            $roleHasIt = $role ? $role->hasPermission($key) : false;

            if ($isChecked && !$roleHasIt) {
                // Explicitly granted override
                $customOverrides[$key] = true;
            } elseif (!$isChecked && $roleHasIt) {
                // Explicitly revoked override
                $customOverrides[$key] = false;
            }
            // If ($isChecked === $roleHasIt), matches role default, no override needed!
        }

        $user->update([
            'custom_permissions' => !empty($customOverrides) ? $customOverrides : null,
        ]);

        return redirect()->route('admin.users.permissions', $user)->with('success', 'User permissions updated successfully.');
    }

    /**
     * Reset individual user permission overrides back to role defaults.
     */
    public function resetPermissions(User $user): RedirectResponse
    {
        $this->authorizeUserAccess('users.permissions');

        $user->update(['custom_permissions' => null]);

        return redirect()->route('admin.users.permissions', $user)->with('success', 'User permissions have been reset to role defaults.');
    }
}
