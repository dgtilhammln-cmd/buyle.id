<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    protected array $locales = ['id', 'en', 'ar', 'ko'];

    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from the first URL segment
        $segment = $request->segment(1);

        if (in_array($segment, $this->locales)) {
            $locale = $segment;
        } else {
            // Invalid or missing locale — default to Indonesian
            $locale = 'id';
        }

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        $response = $next($request);

        // Set Content-Language header
        $response->headers->set('Content-Language', $locale);

        // Save preference to cookie (1 year)
        // Also override old 'en' cookies to fix legacy redirect issues
        $cookieLocale = $request->cookie('preferred_locale');
        if (!$cookieLocale || $cookieLocale === 'en' || $cookieLocale !== $locale) {
            $response->headers->setCookie(
                cookie('preferred_locale', $locale, 60 * 24 * 365, '/', null, false, false)
            );
        }

        return $response;
    }
}
