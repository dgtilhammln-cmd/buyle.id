<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResolveIpLocation implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;
    public int $timeout = 10;

    public function __construct(public readonly string $ip)
    {
    }

    /**
     * Resolve IP to city/country and cache it for 24 hours.
     * Runs in the background — does NOT block HTTP responses.
     */
    public function handle(): void
    {
        $locCacheKey = 'ip_loc_' . md5($this->ip);

        // Already resolved? Skip.
        if (Cache::has($locCacheKey) && Cache::get($locCacheKey) !== 'Unknown') {
            return;
        }

        try {
            $res = Http::timeout(5)->get("http://ip-api.com/json/{$this->ip}?fields=status,city,countryCode");

            if ($res->successful() && $res->json('status') === 'success' && !empty($res->json('city'))) {
                $location = $res->json('city') . ', ' . $res->json('countryCode');
            } else {
                $location = 'Unknown';
            }
        } catch (\Exception $e) {
            Log::debug('ResolveIpLocation failed: ' . $e->getMessage());
            $location = 'Unknown';
        }

        // Cache for 24 hours
        Cache::put($locCacheKey, $location, 86400);

        // Update any 'Unknown' analytics_events for this IP (optional, best effort)
        try {
            \App\Models\AnalyticsEvent::where('ip_address', $this->ip)
                ->where('country', 'Unknown')
                ->limit(50)
                ->update(['country' => $location]);
        } catch (\Exception $e) {
            // Non-critical, skip
        }
    }
}
