<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            try {
                $user = auth()->user();
                // Only update once per minute per user (avoid every request DB hit)
                $cacheKey = 'last_seen_' . $user->id;
                if (!\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                    $user->updateQuietly(['last_seen_at' => now()]);
                    \Illuminate\Support\Facades\Cache::put($cacheKey, true, 60);
                }
            } catch (\Exception $e) {
                // Kolom mungkin belum ada, abaikan error
            }
        }
        return $next($request);
    }
}
