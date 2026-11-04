<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        return Auth::check() && Auth::user()->canAccessAdmin() ? redirect()->route('admin.dashboard') : view('admin.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            ActivityLogger::log(
                action: 'failed_login',
                module: 'Security & Auth',
                description: 'অ্যাডমিন প্যানেলে ব্যর্থ লগিন চেষ্টা (Email: ' . $credentials['email'] . ')',
                properties: ['attempted_email' => $credentials['email']]
            );
            return back()->withErrors(['email' => 'ইমেইল বা পাসওয়ার্ড সঠিক নয়।'])->onlyInput('email');
        }

        $user = Auth::user();

        if (! $user->canAccessAdmin()) {
            Auth::logout();
            ActivityLogger::log(
                action: 'failed_login',
                module: 'Security & Auth',
                description: 'অননুমোদিত বা নিষ্ক্রিয় ব্যবহারকারীর প্রবেশের চেষ্টা (User: ' . $user->name . ')',
                user: $user
            );
            if (($user->status ?? 'active') !== 'active') {
                return back()->withErrors(['email' => 'আপনার অ্যাকাউন্টটি নিষ্ক্রিয় (Inactive) রয়েছে। প্রধান প্রশাসকের সাথে যোগাযোগ করুন।'])->onlyInput('email');
            }
            return back()->withErrors(['email' => 'অ্যাডমিন প্যানেলে প্রবেশ করার অনুমতি আপনার অ্যাকাউন্টে নেই।'])->onlyInput('email');
        }

        ActivityLogger::log(
            action: 'login',
            module: 'Security & Auth',
            description: $user->name . ' অ্যাডমিন প্যানেলে সফলভাবে লগিন করেছেন।',
            user: $user
        );

        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            ActivityLogger::log(
                action: 'logout',
                module: 'Security & Auth',
                description: $user->name . ' অ্যাডমিন প্যানেল থেকে লগআউট করেছেন।',
                user: $user
            );
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
