<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramApiService
{
    protected string $apiBase;

    public function __construct()
    {
        $this->apiBase = MetaConfigService::getGraphApiBase();
    }

    /**
     * Tukar authorization code dengan short-lived user token.
     */
    public function exchangeCodeForToken(string $code, string $redirectUri): array
    {
        $cleanCode = preg_replace('/#_.*$/', '', $code);
        $appId     = MetaConfigService::getAppId();
        $appSecret = MetaConfigService::getAppSecret();

        // 1. Coba endpoint Instagram API (api.instagram.com)
        $igResponse = Http::asForm()->post('https://api.instagram.com/oauth/access_token', [
            'client_id'     => $appId,
            'client_secret' => $appSecret,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $redirectUri,
            'code'          => $cleanCode,
        ]);

        if ($igResponse->successful() && !empty($igResponse->json('access_token'))) {
            return $igResponse->json();
        }

        // 2. Fallback via Facebook Graph API
        $response = Http::asForm()->post("{$this->apiBase}/oauth/access_token", [
            'client_id'     => $appId,
            'client_secret' => $appSecret,
            'redirect_uri'  => $redirectUri,
            'code'          => $cleanCode,
        ]);

        if ($response->failed()) {
            Log::error('Instagram OAuth exchange code error', [
                'ig_res' => $igResponse->json(),
                'fb_res' => $response->json(),
            ]);
            throw new \Exception($response->json('error.message', $igResponse->json('error_message', 'Gagal menukarkan OAuth code.')));
        }

        return $response->json();
    }

    /**
     * Tukar short-lived token dengan Long-Lived Token (60 hari).
     */
    public function exchangeForLongLivedToken(string $shortLivedToken): array
    {
        $appId     = MetaConfigService::getAppId();
        $appSecret = MetaConfigService::getAppSecret();

        // 1. Coba endpoint Long-Lived Instagram API (graph.instagram.com)
        $igResponse = Http::get('https://graph.instagram.com/access_token', [
            'grant_type'    => 'ig_exchange_token',
            'client_secret' => $appSecret,
            'access_token'  => $shortLivedToken,
        ]);

        if ($igResponse->successful() && !empty($igResponse->json('access_token'))) {
            return $igResponse->json();
        }

        // 2. Fallback via Facebook Graph API
        $response = Http::get("{$this->apiBase}/oauth/access_token", [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $appId,
            'client_secret'     => $appSecret,
            'fb_exchange_token' => $shortLivedToken,
        ]);

        if ($response->failed()) {
            Log::error('Instagram Long-Lived Token error', [
                'ig_res' => $igResponse->json(),
                'fb_res' => $response->json(),
            ]);
            throw new \Exception($response->json('error.message', $igResponse->json('error_message', 'Gagal mendapatkan Long-Lived Access Token.')));
        }

        return $response->json();
    }

    /**
     * Ambil Profil Akun Instagram Bisnis/Creator yang terhubung.
     */
    public function fetchConnectedAccount(string $accessToken): array
    {
        // 1. Coba endpoint me di graph.instagram.com (Instagram API)
        $igMeRes = Http::get("https://graph.instagram.com/v19.0/me", [
            'fields'       => 'id,username,name,account_type,profile_picture_url',
            'access_token' => $accessToken,
        ]);

        if ($igMeRes->successful() && !empty($igMeRes->json('id'))) {
            $me = $igMeRes->json();
            return [
                'page_id'             => null,
                'page_name'           => $me['name'] ?? ($me['username'] ?? 'Instagram Creator'),
                'page_access_token'   => $accessToken,
                'ig_id'               => $me['id'],
                'ig_username'         => $me['username'] ?? '',
                'ig_name'             => $me['name'] ?? ($me['username'] ?? ''),
                'ig_profile_picture'  => $me['profile_picture_url'] ?? null,
            ];
        }

        // 2. Fallback via Facebook Pages /me/accounts
        $response = Http::get("{$this->apiBase}/me/accounts", [
            'fields'       => 'id,name,access_token,instagram_business_account{id,username,name,profile_picture_url}',
            'access_token' => $accessToken,
        ]);

        if ($response->successful()) {
            $pages = $response->json('data', []);
            foreach ($pages as $page) {
                if (!empty($page['instagram_business_account'])) {
                    return [
                        'page_id'             => $page['id'],
                        'page_name'           => $page['name'],
                        'page_access_token'   => $page['access_token'] ?? $accessToken,
                        'ig_id'               => $page['instagram_business_account']['id'],
                        'ig_username'         => $page['instagram_business_account']['username'] ?? '',
                        'ig_name'             => $page['instagram_business_account']['name'] ?? '',
                        'ig_profile_picture'  => $page['instagram_business_account']['profile_picture_url'] ?? null,
                    ];
                }
            }
        }

        // 3. Fallback endpoint /me Facebook
        $meRes = Http::get("{$this->apiBase}/me", [
            'fields'       => 'id,name,username',
            'access_token' => $accessToken,
        ]);

        if ($meRes->successful()) {
            $me = $meRes->json();
            return [
                'page_id'             => $me['id'] ?? null,
                'page_name'           => $me['name'] ?? 'Instagram Account',
                'page_access_token'   => $accessToken,
                'ig_id'               => $me['id'] ?? null,
                'ig_username'         => $me['username'] ?? ($me['name'] ?? 'kreator_buyle'),
                'ig_name'             => $me['name'] ?? '',
                'ig_profile_picture'  => null,
            ];
        }

        throw new \Exception('Tidak ditemukan akun Instagram Professional / Creator.');
    }

    /**
     * Kirim Auto DM ke inbox pengirim komentar Instagram.
     * Menggunakan Private Reply (recipient.comment_id) resmi dari Meta Graph API.
     */
    public function sendDirectMessage(string $accessToken, string $businessOrPageId, string $recipientId, string $messageText, ?string $commentId = null): array
    {
        $targetId = !empty($businessOrPageId) ? trim($businessOrPageId) : 'me';
        $url = "{$this->apiBase}/{$targetId}/messages";
        
        // 1. Prioritas utama: Private Reply resmi Meta via comment_id
        if (!empty($commentId)) {
            $response = Http::withToken($accessToken)->post($url, [
                'recipient' => ['comment_id' => $commentId],
                'message'   => ['text' => $messageText],
            ]);

            if ($response->successful()) {
                return [
                    'success'    => true,
                    'message_id' => $response->json('message_id'),
                ];
            }

            // Coba fallback ke /me/messages jika targetId sebelumnya bukan 'me'
            if ($targetId !== 'me') {
                $fallbackMeUrl = "{$this->apiBase}/me/messages";
                $fallbackMeRes = Http::withToken($accessToken)->post($fallbackMeUrl, [
                    'recipient' => ['comment_id' => $commentId],
                    'message'   => ['text' => $messageText],
                ]);

                if ($fallbackMeRes->successful()) {
                    return [
                        'success'    => true,
                        'message_id' => $fallbackMeRes->json('message_id'),
                    ];
                }
            }

            Log::warning('Instagram Private Reply with comment_id failed, trying recipient id fallback', [
                'comment_id' => $commentId,
                'target_id'  => $targetId,
                'response'   => $response->json(),
            ]);
        }

        // 2. Fallback via recipient user ID jika user pernah chat sebelumnya
        $response = Http::withToken($accessToken)->post($url, [
            'recipient' => ['id' => $recipientId],
            'message'   => ['text' => $messageText],
        ]);

        if ($response->failed() && $targetId !== 'me') {
            $fallbackMeRes = Http::withToken($accessToken)->post("{$this->apiBase}/me/messages", [
                'recipient' => ['id' => $recipientId],
                'message'   => ['text' => $messageText],
            ]);
            if ($fallbackMeRes->successful()) {
                return [
                    'success'    => true,
                    'message_id' => $fallbackMeRes->json('message_id'),
                ];
            }
        }

        if ($response->failed()) {
            Log::warning('Instagram DM send failed', [
                'url'      => $url,
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);
            return [
                'success' => false,
                'error'   => $response->json('error.message', 'Gagal mengirim pesan DM.'),
            ];
        }

        return [
            'success'    => true,
            'message_id' => $response->json('message_id'),
        ];
    }

    /**
     * Balas komentar publik di Post / Reels Instagram.
     */
    public function replyPublicComment(string $accessToken, string $commentId, string $replyText): array
    {
        $url = "{$this->apiBase}/{$commentId}/replies";

        $response = Http::withToken($accessToken)->post($url, [
            'message' => $replyText,
        ]);

        if ($response->failed()) {
            Log::warning('Instagram Comment Reply failed', [
                'comment_id' => $commentId,
                'response'   => $response->json(),
            ]);
            return [
                'success' => false,
                'error'   => $response->json('error.message', 'Gagal membalas komentar publik.'),
            ];
        }

        return [
            'success' => true,
            'id'      => $response->json('id'),
        ];
    }

    /**
     * Ambil daftar postingan/reels Instagram untuk picker campaign
     */
    public function fetchMediaList(string $accessToken, string $igBusinessId, int $limit = 24): array
    {
        $url = "{$this->apiBase}/{$igBusinessId}/media";

        $response = Http::get($url, [
            'fields'       => 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp',
            'limit'        => $limit,
            'access_token' => $accessToken,
        ]);

        if ($response->failed()) {
            return [];
        }

        return $response->json('data', []);
    }

    /**
     * Subscribe page to webhooks
     */
    public function subscribeWebhooks(string $pageId, string $pageAccessToken): bool
    {
        $url = "{$this->apiBase}/{$pageId}/subscribed_apps";

        $response = Http::post($url, [
            'subscribed_fields' => 'messages,feed,comments,mention',
            'access_token'      => $pageAccessToken,
        ]);

        return $response->successful();
    }
}
