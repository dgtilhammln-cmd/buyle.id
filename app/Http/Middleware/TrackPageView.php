<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AnalyticsEvent;

class TrackPageView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track GET requests, skip admin, skip AJAX/assets
        if ($request->isMethod('GET')
            && !$request->is('admin*')
            && !$request->is('track*')
            && !$request->is('sitemap*')
            && !$request->ajax()
            && !$request->expectsJson()
        ) {
            // Dedup: only count 1 pageview per session per 10 minutes per path
            // Prevents browser prefetch, page reloads, & minor redirects from inflating count
            $sessionKey = 'pv_tracked_' . md5($request->getPathInfo());
            $lastTracked = session($sessionKey, 0);

            if ((time() - $lastTracked) > 600) {
                session([$sessionKey => time()]);
                AnalyticsEvent::record('pageview', $request->fullUrl());
            }
        }

        return $response;
    }
}
