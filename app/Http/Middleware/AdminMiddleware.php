<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the currently authenticated user
        $user = Auth::user();

        // 2. Check if a user is logged in AND if that user is an admin
        // This structure is safer and clearer for linters.
        if (!$user || $user->role !== 'admin') {
            // If there is no user, or the user is not an admin, block access.
            abort(403, 'Unauthorized Access');
        }

        // If the user is an admin, allow the request to proceed.
        return $next($request);
    }
}
