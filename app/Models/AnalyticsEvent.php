<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class AnalyticsEvent extends Model
{
    protected $fillable = [
        'event_type', 'page_url', 'page_title', 'referrer',
        'user_agent', 'ip_address', 'country', 'device_type',
        'bio_block_id', 'bio_creator_id',
        'utm_source', 'utm_medium', 'utm_campaign', 'utm_content',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('event_type', $type);
    }

    public function scopeInDateRange(Builder $query, $from, $to): Builder
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Detect device type from User Agent
     */
    public static function detectDevice(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);
        if (str_contains($userAgent, 'mobile') || str_contains($userAgent, 'android')) {
            if (str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')) {
                return 'tablet';
            }
            return 'mobile';
        }
        if (str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Record an event — NON-BLOCKING VERSION
     *
     * Optimisasi: geolocation lookup dilakukan secara ASYNC (via dispatch() jika Queue tersedia),
     * atau dengan fallback cepat tanpa HTTP blocking call.
     * IP tidak lagi di-resolve ke ipify.org setiap request — hanya di-cache 24 jam per IP.
     */
    public static function record(string $type, ?string $url = null, array $extra = []): void
    {
        try {
            $ip = request()->ip() ?? '0.0.0.0';
            $ua = request()->userAgent() ?? '';

            // Resolusi geolocation: HANYA dari cache — tidak boleh blocking HTTP di sini
            // Geolocation akan diisi 'Unknown' jika belum pernah di-lookup
            $locCacheKey = 'ip_loc_' . md5($ip);
            $location = Cache::get($locCacheKey, 'Unknown');

            // Jika belum ada di cache, dispatch job async (tidak blokir request)
            if ($location === 'Unknown') {
                // Cek apakah Queue driver tersedia & ada job class
                if (class_exists('\\App\\Jobs\\ResolveIpLocation')) {
                    try {
                        \App\Jobs\ResolveIpLocation::dispatch($ip)->onQueue('default');
                    } catch (\Exception $e) {
                        // Queue tidak tersedia, biarkan 'Unknown'
                    }
                }
                // Jika tidak ada Queue, langsung simpan dengan 'Unknown' — resolve nanti via cron
            }

            static::create(array_merge([
                'event_type'  => $type,
                'page_url'    => $url ?? request()->fullUrl(),
                'page_title'  => $extra['page_title'] ?? null,
                'referrer'    => request()->header('referer'),
                'user_agent'  => $ua,
                'ip_address'  => $ip,
                'country'     => $location,
                'device_type' => static::detectDevice($ua),
            ], $extra));

        } catch (\Exception $e) {
            // Silently fail - analytics should never break the site
        }
    }
}
