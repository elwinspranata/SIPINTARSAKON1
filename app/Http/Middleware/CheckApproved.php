<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApproved
{
    /**
     * Handle an incoming request.
     * Redirect unapproved users to the pending approval page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_approved) {
            // Allow logout
            if ($request->routeIs('logout')) {
                return $next($request);
            }
            return redirect()->route('approval.pending');
        }

        return $next($request);
    }
}
