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
            // Jika buyer mencoba membuka menu creator/seller, arahkan ke onboarding dengan pesan interaktif
            if ($role === 'seller' && $userRole === 'buyer') {
                return redirect()->route('creator.onboarding')->with('warning_onboarding', 'Yuk, lengkapi profil tokomu terlebih dahulu untuk membuka akses ke semua fitur Creator.');
            }

            // Jika seller membuka halaman buyer, izinkan akses
            if ($role === 'buyer' && $userRole === 'seller') {
                return $next($request);
            }

            abort(403, 'Akses Terbatas: Anda tidak memiliki izin untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
