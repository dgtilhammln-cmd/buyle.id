<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * StaticCacheHeaders Middleware
 *
 * Menambahkan Cache-Control headers untuk response HTML dan aset.
 * Ini signifikan meningkatkan performance di kunjungan ulang.
 *
 * Untuk aset statis (CSS/JS/gambar), Nginx harus di-config terpisah.
 * Middleware ini menangani response HTML Laravel.
 */
class StaticCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $contentType = $response->headers->get('Content-Type', '');

        // Hanya proses HTML responses
        if (!str_contains($contentType, 'text/html')) {
            return $response;
        }

        $statusCode = $response->getStatusCode();

        // Untuk halaman publik (bukan dashboard/admin/account)
        $isPublicPage = !$request->is('admin*', 'account*', 'creator*', 'checkout*', 'cart*', 'api*');

        if ($statusCode === 200 && $isPublicPage) {
            // Public pages: cache 60 detik di browser, 5 menit di shared cache (CDN/proxy)
            $response->headers->set('Cache-Control', 'public, max-age=60, s-maxage=300, stale-while-revalidate=600');
            $response->headers->set('Vary', 'Accept-Encoding');
        } elseif ($statusCode === 200) {
            // Private pages (user-specific): no-store
            $response->headers->set('Cache-Control', 'private, no-cache, no-store, must-revalidate');
        }

        return $response;
    }
}
