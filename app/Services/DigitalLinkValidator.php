<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * DigitalLinkValidator
 *
 * Memvalidasi URL produk digital yang diinput seller.
 * Mendeteksi phishing, shortener berbahaya, domain mencurigakan, dan URL malformed.
 *
 * Strategi keamanan (defense-in-depth):
 *  1. Whitelist: hanya domain tepercaya yang diizinkan
 *  2. Blacklist: domain berbahaya / shortener diblokir
 *  3. Pola phishing: deteksi keyword phishing di URL
 *  4. Struktur URL: validasi format dan skema
 */
class DigitalLinkValidator
{
    /**
     * Domain yang DIIZINKAN (whitelist).
     * Seller hanya bisa input link dari domain-domain ini.
     */
    private const ALLOWED_DOMAINS = [
        // Google
        'drive.google.com',
        'docs.google.com',
        'sheets.google.com',
        'slides.google.com',
        'forms.google.com',
        'sites.google.com',
        // Notion
        'notion.so',
        'notion.site',
        // Canva
        'canva.com',
        'www.canva.com',
        // Figma
        'figma.com',
        'www.figma.com',
        // Loom
        'loom.com',
        'www.loom.com',
        // Dropbox
        'dropbox.com',
        'www.dropbox.com',
        // OneDrive / SharePoint
        'onedrive.live.com',
        '1drv.ms',
        'sharepoint.com',
        // Gumroad
        'gumroad.com',
        'www.gumroad.com',
        // GitHub
        'github.com',
        'raw.githubusercontent.com',
        // YouTube (untuk produk video course)
        'youtu.be',
        'youtube.com',
        'www.youtube.com',
        // Teachable / Podia / Kajabi (learning platforms)
        'teachable.com',
        'podia.com',
        'kajabi.com',
        // Airtable
        'airtable.com',
        // Trello
        'trello.com',
        // Miro
        'miro.com',
        // Whimsical
        'whimsical.com',
        // Mega
        'mega.nz',
        'mega.io',
        // Box
        'app.box.com',
        // WhatsApp
        'wa.me',
        'api.whatsapp.com',
        'web.whatsapp.com',
        // Custom domain seller (domain buyle.id sendiri)
        'buyle.id',
    ];

    /**
     * Domain yang DIBLOKIR permanen (blacklist).
     * URL shortener, phishing, adult, malware host.
     */
    private const BLOCKED_DOMAINS = [
        // URL shorteners berbahaya
        'bit.ly', 'tinyurl.com', 'goo.gl', 'ow.ly', 'is.gd',
        'buff.ly', 'adf.ly', 'adfly.com', 'sh.st', 'shorten.gg',
        'cutt.ly', 't.co', 'rb.gy', 'shorturl.at', 'tiny.cc',
        'snipurl.com', 'clck.ru', 'urlz.fr', 'za.gl', 'qr.ae',
        // Phishing / malware host umum
        'filehosting.org', 'sendspace.com', '4shared.com',
        'mediafire.com',  // Banyak disalahgunakan
        'zippyshare.com', 'rapidshare.com', 'uploadfiles.io',
        'wetransfer.com', // Banyak abuse kasus phishing
        // Pastebin / code sharing yang sering disalahgunakan
        'pastebin.com', 'paste.ee', 'hastebin.com',
        // Suspicious TLD hosting
        '000webhostapp.com', 'weebly.com', 'wixsite.com',
    ];

    /**
     * Keyword phishing yang sering muncul di URL palsu.
     */
    private const PHISHING_PATTERNS = [
        'login', 'signin', 'sign-in', 'account', 'verify', 'verification',
        'secure', 'update', 'confirm', 'banking', 'paypal', 'wallet',
        'password', 'credential', 'suspended', 'unlock', 'recovery',
        'support', 'helpdesk', 'invoice-',
    ];

    // =========================================================================

    /**
     * Validasi URL produk digital.
     *
     * @return array{valid: bool, reason: string|null, domain: string|null}
     */
    public function validate(string $url): array
    {
        $url = trim($url);

        // 1. Format dasar
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $this->fail('URL tidak valid. Pastikan format URL benar (contoh: https://drive.google.com/...)');
        }

        // 2. Wajib HTTPS
        $parsed = parse_url($url);
        if (($parsed['scheme'] ?? '') !== 'https') {
            return $this->fail('Hanya URL dengan HTTPS yang diizinkan demi keamanan pembeli.');
        }

        $host = strtolower($parsed['host'] ?? '');

        if (empty($host)) {
            return $this->fail('Domain URL tidak valid.');
        }

        // 3. Blacklist check — harus sebelum whitelist
        if ($this->isBlocked($host)) {
            return $this->fail("Domain '{$host}' diblokir. Mohon gunakan layanan penyimpanan tepercaya seperti Google Drive, Notion, atau Canva.");
        }

        // 4. Whitelist check
        if (!$this->isAllowed($host)) {
            return $this->fail(
                "Domain '{$host}' belum ada dalam daftar yang diizinkan. " .
                "Gunakan Google Drive, Notion, Canva, Figma, Loom, Dropbox, OneDrive, atau platform tepercaya lainnya."
            );
        }

        // 5. Phishing pattern di path/query
        $urlLower = strtolower($url);
        foreach (self::PHISHING_PATTERNS as $pattern) {
            // Hanya flag jika ada di path, bukan hostname
            $pathAndQuery = strtolower(($parsed['path'] ?? '') . '?' . ($parsed['query'] ?? ''));
            if (str_contains($pathAndQuery, $pattern)) {
                Log::warning('[DigitalLinkValidator] Phishing pattern detected', [
                    'url'     => $url,
                    'pattern' => $pattern,
                ]);
                return $this->fail("URL terdeteksi mengandung pola yang mencurigakan ('{$pattern}'). Mohon periksa kembali URL Anda.");
            }
        }

        // 6. URL tidak boleh terlalu panjang (max 2000 karakter)
        if (strlen($url) > 2000) {
            return $this->fail('URL terlalu panjang. Maksimum 2000 karakter.');
        }

        // 7. Lulus semua validasi
        Log::info('[DigitalLinkValidator] URL validated OK', ['domain' => $host]);

        return ['valid' => true, 'reason' => null, 'domain' => $host];
    }

    /**
     * Shorthand untuk hasil gagal.
     */
    private function fail(string $reason): array
    {
        return ['valid' => false, 'reason' => $reason, 'domain' => null];
    }

    /**
     * Cek apakah host masuk whitelist (termasuk subdomain).
     */
    private function isAllowed(string $host): bool
    {
        foreach (self::ALLOWED_DOMAINS as $allowed) {
            if ($host === $allowed || str_ends_with($host, '.' . $allowed)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Cek apakah host masuk blacklist.
     */
    private function isBlocked(string $host): bool
    {
        foreach (self::BLOCKED_DOMAINS as $blocked) {
            if ($host === $blocked || str_ends_with($host, '.' . $blocked)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return daftar domain yang diizinkan (untuk ditampilkan di UI).
     */
    public static function getAllowedDomains(): array
    {
        return self::ALLOWED_DOMAINS;
    }
}
