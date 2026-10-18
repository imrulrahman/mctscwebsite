<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->is_admin) {
            return redirect()->route('admin.login')->with('error', 'অ্যাডমিন অ্যাকাউন্ট দিয়ে লগইন করুন।');
        }

        return $next($request);
    }
}
