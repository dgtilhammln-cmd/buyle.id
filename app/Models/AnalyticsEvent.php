<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AnalyticsEvent extends Model
{
    protected $fillable = [
        'event_type', 'page_url', 'page_title', 'referrer',
        'user_agent', 'ip_address', 'country', 'device_type'
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
     * Record an event
     */
    public static function record(string $type, ?string $url = null, array $extra = []): void
    {
        try {
            $ip = request()->ip();

            // If local/loopback IP, try to get the real public IP of the machine
            if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
                try {
                    $publicIp = \Illuminate\Support\Facades\Cache::remember('public_ip_machine', 3600, function() {
                        $r = \Illuminate\Support\Facades\Http::timeout(2)->get('https://api.ipify.org?format=json');
                        return $r->successful() ? $r->json('ip') : null;
                    });
                    if ($publicIp) $ip = $publicIp;
                } catch (\Exception $e) {}
            }

            $location = \Illuminate\Support\Facades\Cache::remember('ip_loc_'.$ip, 86400, function() use($ip) {
                try {
                    $res = \Illuminate\Support\Facades\Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=status,city,regionName,countryCode");
                    if ($res->successful() && $res['status'] === 'success' && !empty($res['city'])) {
                        return $res['city'] . ', ' . $res['countryCode'];
                    }
                } catch (\Exception $e) {}
                return 'Unknown';
            });

            static::create(array_merge([
                'event_type'  => $type,
                'page_url'    => $url ?? request()->fullUrl(),
                'page_title'  => $extra['page_title'] ?? null,
                'referrer'    => request()->header('referer'),
                'user_agent'  => request()->userAgent(),
                'ip_address'  => $ip,
                'country'     => $location,
                'device_type' => static::detectDevice(request()->userAgent() ?? ''),
            ], $extra));
        } catch (\Exception $e) {
            // Silently fail - analytics should not break the site
        }
    }
}
