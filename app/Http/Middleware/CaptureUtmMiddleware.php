<?php

namespace App\Http\Middleware;

use App\Models\CreatorProfile;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CaptureUtmMiddleware
{
    /**
     * Tangkap UTM parameters & Referral Tracker Anti-Lepas.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Capture standard UTM parameters
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

        // 2. Capture Referral Parameter (Tracker Anti-Lepas)
        $refInput = $request->query('ref')
            ?? $request->query('aff')
            ?? $request->query('referral')
            ?? null;

        if (!empty($refInput) && is_string($refInput)) {
            $refCode = strtolower(trim($refInput));

            // Cari pemilik referral (User berdasarkan username atau referral_code, atau CreatorProfile)
            $referrerUser = User::where('username', $refCode)
                ->orWhere('referral_code', $refCode)
                ->first();

            if (!$referrerUser) {
                $creatorProfile = CreatorProfile::where('store_slug', $refCode)->with('user')->first();
                if ($creatorProfile && $creatorProfile->user) {
                    $referrerUser = $creatorProfile->user;
                }
            }

            // Simpan ke Session
            $request->session()->put('referral_code', $refCode);
            $request->session()->put('referral_source_type', 'creator_bio');
            $request->session()->put('referral_source_name', 'Bio Badge (@' . ($referrerUser ? $referrerUser->username : $refCode) . ')');

            if ($referrerUser) {
                $request->session()->put('referrer_id', $referrerUser->id);
                $request->session()->put('referrer_username', $referrerUser->username);
            }

            // Pasang Cookie Anti-Lepas (30 Hari) di root domain '/'
            Cookie::queue('buyle_referral', $refCode, 60 * 24 * 30, '/', null, false, false);
            if ($referrerUser) {
                Cookie::queue('buyle_referrer_id', (string)$referrerUser->id, 60 * 24 * 30, '/', null, false, false);
                Cookie::queue('buyle_referrer_username', (string)$referrerUser->username, 60 * 24 * 30, '/', null, false, false);
            }
        } else {
            // Jika tidak ada di query string, periksa apakah ada di Cookie browser
            $cookieRef = $request->cookie('buyle_referral');
            if (!empty($cookieRef) && !$request->session()->has('referral_code')) {
                $cookieRef = strtolower(trim($cookieRef));
                $cookieReferrerId = $request->cookie('buyle_referrer_id');
                $cookieReferrerUser = $request->cookie('buyle_referrer_username') ?? $cookieRef;

                $request->session()->put('referral_code', $cookieRef);
                $request->session()->put('referral_source_type', 'creator_bio');
                $request->session()->put('referral_source_name', 'Bio Badge (@' . $cookieReferrerUser . ')');
                if ($cookieReferrerId) {
                    $request->session()->put('referrer_id', (int)$cookieReferrerId);
                    $request->session()->put('referrer_username', $cookieReferrerUser);
                }
            }
        }

        return $next($request);
    }
}
