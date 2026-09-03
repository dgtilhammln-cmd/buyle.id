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
            // Jika buyer mencoba membuka menu creator/seller, arahkan ke onboarding (yang sekarang akan mengubah role secara otomatis dan melempar ke /creator/profile)
            if ($role === 'seller' && $userRole === 'buyer') {
                return redirect()->route('creator.onboarding');
            }

            // Jika seller membuka halaman buyer, izinkan akses
            if ($role === 'buyer' && $userRole === 'seller') {
                return $next($request);
            }

            abort(403, 'Akses Terbatas: Anda tidak memiliki izin untuk membuka halaman ini.');
        }

        // Kunci di halaman profile jika profil seller belum lengkap (wajib isi nama toko), KECUALI jika tipe profil adalah affiliator
        if ($userRole === 'seller') {
            $profile = $request->user()->creatorProfile;
            $isAffiliator = $profile && $profile->bio_role === 'affiliator';
            $isProfileIncomplete = !$profile || empty($profile->store_name);

            $allowedRoutes = ['creator.profile.edit', 'creator.profile.update', 'logout', 'creator.onboarding', 'creator.bio.set-role'];
            $routeName = $request->route() ? $request->route()->getName() : '';
            
            if (!$isAffiliator && $isProfileIncomplete && !in_array($routeName, $allowedRoutes)) {
                return redirect()->route('creator.profile.edit')->with('error', 'Anda wajib melengkapi profil (terutama Nama Toko) sebelum dapat mengakses halaman lain.');
            }
        }

        return $next($request);
    }
}
