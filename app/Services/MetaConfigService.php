<?php

namespace App\Services;

use App\Models\Setting;

class MetaConfigService
{
    /**
     * Dapatkan App ID dari database (/admin/settings) dengan fallback aman.
     */
    public static function getAppId(): string
    {
        return (string) Setting::get('instagram_app_id', '1642881397242052');
    }

    /**
     * Dapatkan App Secret dari database (/admin/settings) dengan fallback aman.
     */
    public static function getAppSecret(): string
    {
        return (string) Setting::get('instagram_app_secret', 'c5dfb3973596cac40db925898c0cfbfd');
    }

    /**
     * Dapatkan Verify Token untuk handshake webhook Meta.
     */
    public static function getVerifyToken(): string
    {
        return (string) Setting::get('instagram_verify_token', 'buyle_ig_webhook_secret_2026');
    }

    /**
     * Dapatkan versi Meta Graph API.
     */
    public static function getGraphVersion(): string
    {
        return (string) Setting::get('instagram_graph_version', 'v26.0');
    }

    /**
     * URL dasar Graph API
     */
    public static function getGraphApiBase(): string
    {
        return 'https://graph.facebook.com/' . self::getGraphVersion();
    }

    /**
     * 3 Scope Resmi Instagram API yang aktif di akun Meta Developer
     */
    public static function getRequiredScopes(): array
    {
        return [
            'instagram_business_basic',
            'instagram_business_manage_messages',
            'instagram_business_manage_comments',
        ];
    }

    /**
     * Generate URL Meta OAuth Login Dialog (Instagram Login for Business)
     */
    public static function getOAuthDialogUrl(string $redirectUri, string $state = ''): string
    {
        $appId = self::getAppId();
        $scopes = implode(',', self::getRequiredScopes());

        // Menggunakan endpoint resmi Instagram Authorize untuk Instagram API
        $params = http_build_query([
            'enable_fb_login'      => '0',
            'force_authentication' => '1',
            'client_id'            => $appId,
            'redirect_uri'         => $redirectUri,
            'response_type'        => 'code',
            'scope'                => $scopes,
            'state'                => $state,
        ]);

        return "https://www.instagram.com/oauth/authorize?" . $params;
    }

    /**
     * Verifikasi Signature Header X-Hub-Signature-256 dari Meta Webhook
     */
    public static function verifySignature(string $rawPayload, ?string $signatureHeader): bool
    {
        if (empty($signatureHeader)) {
            return false;
        }

        // Signature format: sha256={hash}
        $parts = explode('=', $signatureHeader, 2);
        if (count($parts) !== 2 || strtolower($parts[0]) !== 'sha256') {
            return false;
        }

        $expectedHash = hash_hmac('sha256', $rawPayload, self::getAppSecret());

        return hash_equals($expectedHash, $parts[1]);
    }
}
