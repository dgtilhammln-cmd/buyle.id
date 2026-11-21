<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOnboardingCompleted
{
    /**
     * Pastikan user yang login telah menyelesaikan onboarding sebelum mengakses fitur lain.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Admin & POS Cashier tidak perlu onboarding
        if (in_array($user->role, ['super_admin', 'admin', 'pos_cashier'])) {
            return $next($request);
        }

        // Jika sudah menyelesaikan onboarding, lanjutkan
        if (!empty($user->onboarding_completed_at)) {
            return $next($request);
        }

        // Kecualikan URL yang diizinkan saat onboarding belum selesai
        if (
            $request->is('onboarding*') ||
            $request->is('creator/onboarding*') ||
            $request->is('creator/event-attendance*') ||
            $request->is('creator/scanner*') ||
            $request->is('checkout*') ||
            $request->is('keranjang*') ||
            $request->is('cart*') ||
            $request->is('pesanan*') ||
            $request->is('orders*') ||
            $request->is('order*') ||
            $request->is('payment*') ||
            $request->is('p/*') ||
            $request->is('c/*') ||
            $request->is('logout') ||
            $request->is('deploy-hostinger') ||
            $request->is('payment/callback') ||
            $request->is('api/*') ||
            $request->is('track*')
        ) {
            return $next($request);
        }

        // Jika request berupa AJAX/JSON, kembalikan instruksi redirect
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'error'        => 'onboarding_required',
                'message'      => 'Silakan selesaikan onboarding terlebih dahulu.',
                'redirect_url' => route('onboarding.index'),
            ], 403);
        }

        return redirect()->route('onboarding.index');
    }
}
