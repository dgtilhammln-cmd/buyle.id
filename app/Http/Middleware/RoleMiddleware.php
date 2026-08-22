<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized access.');
        }

        $userRole = auth()->user()->role;

        // Admin can access everything
        if ($userRole === 'admin') {
            return $next($request);
        }

        if ($userRole !== $role) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
