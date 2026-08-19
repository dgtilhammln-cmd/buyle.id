<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CaptureUtmMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $utmParameters = [
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
        ];

        foreach ($utmParameters as $param) {
            if ($request->has($param)) {
                $request->session()->put($param, $request->get($param));
            }
        }

        return $next($request);
    }
}
