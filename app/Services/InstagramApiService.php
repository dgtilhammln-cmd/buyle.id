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
        $response = Http::asForm()->post("{$this->apiBase}/oauth/access_token", [
            'client_id'     => MetaConfigService::getAppId(),
            'client_secret' => MetaConfigService::getAppSecret(),
            'redirect_uri'  => $redirectUri,
            'code'          => $code,
        ]);

        if ($response->failed()) {
            Log::error('Instagram OAuth exchange code error', ['body' => $response->json()]);
            throw new \Exception($response->json('error.message', 'Gagal menukarkan OAuth code.'));
        }

        return $response->json();
    }

    /**
     * Tukar short-lived token dengan Long-Lived Token (60 hari).
     */
    public function exchangeForLongLivedToken(string $shortLivedToken): array
    {
        $response = Http::get("{$this->apiBase}/oauth/access_token", [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => MetaConfigService::getAppId(),
            'client_secret'     => MetaConfigService::getAppSecret(),
            'fb_exchange_token' => $shortLivedToken,
        ]);

        if ($response->failed()) {
            Log::error('Instagram Long-Lived Token error', ['body' => $response->json()]);
            throw new \Exception($response->json('error.message', 'Gagal mendapatkan Long-Lived Access Token.'));
        }

        return $response->json();
    }

    /**
     * Ambil Halaman Facebook & Akun Instagram Bisnis yang terhubung.
     */
    public function fetchConnectedAccount(string $accessToken): array
    {
        $response = Http::get("{$this->apiBase}/me/accounts", [
            'fields'       => 'id,name,access_token,instagram_business_account{id,username,name,profile_picture_url}',
            'access_token' => $accessToken,
        ]);

        if ($response->failed()) {
            throw new \Exception($response->json('error.message', 'Gagal mengambil data akun Facebook / Instagram Bisnis.'));
        }

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

        // Jika tidak ditemukan linked IG business account melalui page
        // Coba endpoint /me?fields=id,username
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

        throw new \Exception('Tidak ditemukan akun Instagram Professional / Creator yang terhubung ke Halaman Facebook Anda.');
    }

    /**
     * Kirim Auto DM ke inbox pengirim komentar Instagram.
     */
    public function sendDirectMessage(string $accessToken, string $businessOrPageId, string $recipientId, string $messageText): array
    {
        // 1. Coba endpoint Graph API messaging
        $url = "{$this->apiBase}/{$businessOrPageId}/messages";
        
        $response = Http::withToken($accessToken)->post($url, [
            'recipient' => ['id' => $recipientId],
            'message'   => ['text' => $messageText],
        ]);

        if ($response->failed()) {
            Log::warning('Instagram DM send failed', [
                'url' => $url,
                'status' => $response->status(),
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
