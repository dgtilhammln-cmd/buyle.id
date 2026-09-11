<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Cek sesi admin (via admin login form)
        $hasAdminSession = session('admin_logged_in');

        // Cek role via Auth guard (double verifikasi)
        $hasAdminRole = Auth::check()
            && in_array(Auth::user()->role, ['admin', 'super_admin'])
            && (Auth::user()->is_active ?? true);

        if (!$hasAdminSession || !$hasAdminRole) {
            // Hapus sesi admin yang mungkin orphan
            session()->forget(['admin_logged_in', 'admin_id', 'admin_name', 'admin_email']);
            return redirect('/admin/login')->with('error', 'Akses terbatas. Silakan login dengan akun admin.');
        }

        return $next($request);
    }
}
