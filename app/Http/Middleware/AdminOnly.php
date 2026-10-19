<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->canAccessAdmin()) {
            if ($user) {
                auth()->logout();
            }
            return redirect()->route('admin.login')->with('error', 'অ্যাডমিন প্যানেলে এক্সেস করার অনুমতি নেই অথবা আপনার অ্যাকাউন্টটি নিষ্ক্রিয়।');
        }

        return $next($request);
    }
}
