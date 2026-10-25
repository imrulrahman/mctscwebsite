<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            return back()->withErrors(['email' => 'ইমেইল বা পাসওয়ার্ড সঠিক নয়।'])->onlyInput('email');
        }

        $user = Auth::user();

        if (! $user->canAccessAdmin()) {
            Auth::logout();
            if (($user->status ?? 'active') !== 'active') {
                return back()->withErrors(['email' => 'আপনার অ্যাকাউন্টটি নিষ্ক্রিয় (Inactive) রয়েছে। প্রধান প্রশাসকের সাথে যোগাযোগ করুন।'])->onlyInput('email');
            }
            return back()->withErrors(['email' => 'অ্যাডমিন প্যানেলে প্রবেশ করার অনুমতি আপনার অ্যাকাউন্টে নেই।'])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
