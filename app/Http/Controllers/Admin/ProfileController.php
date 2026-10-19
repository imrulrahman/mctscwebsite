<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
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

    public function edit(): View
    {
        $user = auth()->user()->load('role');
        return view('admin.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
            $this->mirrorToPublic($data['avatar']);
        } elseif ($request->boolean('remove_avatar')) {
            $data['avatar'] = null;
        }

        $user->update($data);

        return back()->with('profile_success', 'Your profile details have been updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.current_password' => 'The provided current password is incorrect.',
            'password.confirmed' => 'New password confirmation does not match.',
            'password.min' => 'The new password must be at least 6 characters long.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('password_success', 'Your password has been changed successfully.');
    }
}
