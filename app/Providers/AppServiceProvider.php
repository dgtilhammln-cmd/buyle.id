<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gunakan custom pagination view dari folder components agar tidak ter-exclude saat deploy
        Paginator::defaultView('components.pagination');

        // Paksa HTTPS di Production agar tidak kena error Mixed Content di Hostinger
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Apply dynamic SMTP configuration
        \App\Services\MailConfigService::apply();

        // ── RATE LIMITERS (Proteksi Server Anti-Jebol & Spam Bot) ──
        RateLimiter::for('domain-check', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip())->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak pencarian domain. Silakan tunggu 1 menit sebelum mencoba lagi.'
                ], 429);
            });
        });

        RateLimiter::for('checkout-limit', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip())->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak permintaan transaksi. Silakan tunggu 1 menit.'
                ], 429);
            });
        });

        RateLimiter::for('login-limit', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('public-api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
