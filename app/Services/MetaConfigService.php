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
        return (string) Setting::get('instagram_app_id', '1601973478071042');
    }

    /**
     * Dapatkan App Secret dari database (/admin/settings) dengan fallback aman.
     */
    public static function getAppSecret(): string
    {
        return (string) Setting::get('instagram_app_secret', '0cfaccff27e5846187faf60c7b8a1d96');
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
        return (string) Setting::get('instagram_graph_version', 'v19.0');
    }

    /**
     * URL dasar Graph API
     */
    public static function getGraphApiBase(): string
    {
        return 'https://graph.facebook.com/' . self::getGraphVersion();
    }

    /**
     * 5 Scope Resmi Meta sesuai PRD
     */
    public static function getRequiredScopes(): array
    {
        return [
            'instagram_business_basic',
            'instagram_business_manage_messages',
            'instagram_business_manage_comments',
            'instagram_business_manage_insights',
            'instagram_business_content_publish',
            'pages_show_list',
            'pages_read_engagement',
        ];
    }

    /**
     * Generate URL Meta OAuth Login Dialog
     */
    public static function getOAuthDialogUrl(string $redirectUri, string $state = ''): string
    {
        $appId = self::getAppId();
        $scopes = implode(',', self::getRequiredScopes());

        $params = http_build_query([
            'client_id'     => $appId,
            'redirect_uri'  => $redirectUri,
            'scope'         => $scopes,
            'response_type' => 'code',
            'state'         => $state,
        ]);

        return "https://www.facebook.com/" . self::getGraphVersion() . "/dialog/oauth?" . $params;
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
