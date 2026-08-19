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
            AnalyticsEvent::record('pageview', $request->fullUrl());
        }

        return $response;
    }
}
