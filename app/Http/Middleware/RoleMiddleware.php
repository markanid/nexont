<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        // Not logged in
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // If user role not allowed
        if (!in_array($user->role, $roles)) {
            abort(403, 'Access denied');
        }
        return $next($request);
    }
}
