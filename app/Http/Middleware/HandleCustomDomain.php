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
                // If accessing root '/' or product links on custom domain, render creator bio seamlessly
                if ($request->path() === '/') {
                    return response(app(\App\Http\Controllers\BioPageController::class)->show($profile->store_slug));
                }
            }
        }

        return $next($request);
    }
}
