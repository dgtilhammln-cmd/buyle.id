<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckLicenseStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Bypass untuk semua URL admin agar admin tetap bisa akses
        if ($request->is('admin*')) {
            return $next($request);
        }

        // Bypass untuk deploy helper, sitemap, robots, dan payment callback
        if ($request->is('deploy-hostinger') || $request->is('sitemap.xml') || $request->is('robots.txt') || $request->is('payment/callback')) {
            return $next($request);
        }

        // Cek status lisensi dari cache (cache 1 menit agar tidak query DB tiap request)
        $status = Cache::remember('site_license_status', 60, function () {
            try {
                $setting = \App\Models\Setting::where('key', 'site_license_status')->first();
                return $setting ? $setting->value : 'active';
            } catch (\Exception $e) {
                return 'active';
            }
        });

        if ($status === 'suspended') {
            abort(503);
        }

        return $next($request);
    }
}
