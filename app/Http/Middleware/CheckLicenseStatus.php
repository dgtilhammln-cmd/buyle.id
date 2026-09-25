<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckLicenseStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Bypass untuk semua URL admin, auth/login, dan preview agar admin tetap bisa bekerja
        if ($request->is('admin*') || $request->is('login') || $request->is('logout') || $request->is('preview-coming-soon')) {
            return $next($request);
        }

        // Bypass jika user yang sedang aktif adalah Admin
        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')) {
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

        // Cek Status Coming Soon / Maintenance Mode
        $csEnabled = (string) \App\Models\Setting::get('cs_enabled', '0');

        if ($csEnabled === '1') {
            return response()->view('components.coming-soon', [], 503);
        }

        return $next($request);
    }
}
