<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\TrackPageView;

$builder = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    );

return $builder->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies (required for correct IP detection on Hostinger, Rumahweb, etc.)
        $middleware->trustProxies(at: '*');

        // Register custom middleware aliases
        $middleware->alias([
            'admin.auth'     => AdminAuth::class,
            'track.pageview' => TrackPageView::class,
            'role'           => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            'payment/callback',
        ]);
        
        $middleware->web(append: [
            \App\Http\Middleware\CaptureUtmMiddleware::class,
            \App\Http\Middleware\OptimizeResponseMiddleware::class,
            \App\Http\Middleware\TrackUserActivity::class,
            \App\Http\Middleware\CheckLicenseStatus::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create()->usePublicPath(dirname(__DIR__).'/public_html');
