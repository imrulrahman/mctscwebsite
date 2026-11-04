<?php

namespace App\Http\Middleware;

use App\Services\VisitorTracker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Execute request first
        $response = $next($request);

        // Record visitor asynchronously/after response or if GET request
        if ($request->isMethod('GET')) {
            VisitorTracker::track($request);
        }

        return $response;
    }
}
