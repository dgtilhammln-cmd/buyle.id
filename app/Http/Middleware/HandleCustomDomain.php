<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CreatorProfile;
use Symfony\Component\HttpFoundation\Response;

class HandleCustomDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());

        // Skip main platform domains & local dev domains
        $mainDomains = ['buyle.id', 'www.buyle.id', 'localhost', '127.0.0.1'];
        if (!in_array($host, $mainDomains) && !str_ends_with($host, '.test') && !str_ends_with($host, '.local')) {
            $cleanHost = preg_replace('/^www\./i', '', $host);
            $profile   = CreatorProfile::where('custom_domain', $cleanHost)
                ->orWhere('custom_domain', 'www.' . $cleanHost)
                ->first();

            if ($profile) {
                $path = trim($request->path(), '/');

                // Pass static assets & API / webhooks / system routes through
                if (
                    str_starts_with($path, 'storage/') ||
                    str_starts_with($path, 'build/') ||
                    str_starts_with($path, 'assets/') ||
                    str_starts_with($path, 'api/') ||
                    str_starts_with($path, 'payment/') ||
                    str_starts_with($path, 'track') ||
                    str_starts_with($path, 'qr-code') ||
                    str_starts_with($path, 'checkout') ||
                    str_starts_with($path, 'keranjang')
                ) {
                    return $next($request);
                }

                // Dynamic robots.txt khusus domain creator
                if ($path === 'robots.txt') {
                    return app(\App\Http\Controllers\SitemapController::class)->customDomainRobots($profile);
                }

                // Dynamic sitemap.xml khusus domain creator
                if ($path === 'sitemap.xml') {
                    return app(\App\Http\Controllers\SitemapController::class)->customDomainSitemap($profile);
                }

                // If accessing root '/' on custom domain -> render creator's bio page
                if ($path === '' || $path === '/') {
                    return response(app(\App\Http\Controllers\BioPageController::class)->show($profile->store_slug));
                }

                // If accessing '/produk' listing page on custom domain
                if ($path === 'produk') {
                    return response(app(\App\Http\Controllers\BioProductsPageController::class)->index($request, $profile->store_slug));
                }

                // If accessing product detail link on custom domain, e.g. /produk/{identifier}, /p/{identifier}, or /{identifier}
                if (preg_match('#^(?:p/|produk/)?([a-zA-Z0-9_\-]+)$#', $path, $matches)) {
                    $identifier = $matches[1];
                    try {
                        return response(app(\App\Http\Controllers\BioProductController::class)->show($profile->store_slug, $identifier));
                    } catch (\Throwable $e) {
                        return response(app(\App\Http\Controllers\BioPageController::class)->show($profile->store_slug));
                    }
                }
            }
        }

        return $next($request);
    }
}
