<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class TicketWarQueueService
{
    public const DEFAULT_MAX_CONCURRENT_CHECKOUT = 20;
    public const TOKEN_TTL = 600; // 10 menit
    public const POLL_MIN_INTERVAL = 10; // 10 detik
    public const POLL_MAX_INTERVAL = 20; // 20 detik

    /**
     * Periksa status antrean untuk produk tiket war tertentu.
     * ZERO DATABASE WRITE — seluruh kalkulasi dilakukan di Cache untuk menjaga performa server.
     */
    public function getQueueStatus(int $productId, string $queueId, ?string $currentToken = null): array
    {
        // 1. Cek apakah token yang dikirim client saat ini masih valid
        if ($currentToken && $this->validateToken($currentToken, $productId)) {
            $data = Cache::get("war_valid_token_{$currentToken}");
            $expiresAt = $data['expires_at'] ?? (time() + self::TOKEN_TTL);
            return [
                'status'      => 'admitted',
                'token'       => $currentToken,
                'expires_at'  => $expiresAt,
                'expires_in'  => max(0, $expiresAt - time()),
                'message'     => 'Sesi checkout tiket Anda aktif.',
            ];
        }

        // 2. Cek apakah queueId ini sudah pernah diizinkan dan tokennya tersimpan di cache
        $cachedToken = Cache::get("war_tok_by_queue_{$productId}_{$queueId}");
        if ($cachedToken && $this->validateToken($cachedToken, $productId)) {
            $data = Cache::get("war_valid_token_{$cachedToken}");
            $expiresAt = $data['expires_at'] ?? (time() + self::TOKEN_TTL);
            return [
                'status'      => 'admitted',
                'token'       => $cachedToken,
                'expires_at'  => $expiresAt,
                'expires_in'  => max(0, $expiresAt - time()),
                'message'     => 'Sesi checkout tiket Anda aktif.',
            ];
        }

        // 3. Cek slot checkout aktif saat ini
        $activeSlots = (int) Cache::get("war_active_{$productId}", 0);
        $maxConcurrent = (int) Setting::get('war_max_concurrent_checkout', self::DEFAULT_MAX_CONCURRENT_CHECKOUT);
        if ($maxConcurrent <= 0) {
            $maxConcurrent = self::DEFAULT_MAX_CONCURRENT_CHECKOUT;
        }

        // 4. Jika masih ada slot kosong, terbitkan Admission Token bertandatangan kriptografi (HMAC)
        if ($activeSlots < $maxConcurrent) {
            $expiresAt = time() + self::TOKEN_TTL;
            $payload = "{$productId}|{$queueId}|{$expiresAt}";
            $signature = hash_hmac('sha256', $payload, (string) config('app.key'));
            $admissionToken = base64_encode("{$payload}|{$signature}");

            // Simpan validitas token di cache
            Cache::put("war_valid_token_{$admissionToken}", [
                'product_id' => $productId,
                'queue_id'   => $queueId,
                'expires_at' => $expiresAt,
            ], self::TOKEN_TTL);

            // Tautkan token ke queue ID
            Cache::put("war_tok_by_queue_{$productId}_{$queueId}", $admissionToken, self::TOKEN_TTL);

            // Increment kuota aktif
            Cache::increment("war_active_{$productId}");

            return [
                'status'      => 'admitted',
                'token'       => $admissionToken,
                'expires_at'  => $expiresAt,
                'expires_in'  => self::TOKEN_TTL,
                'message'     => 'Giliran Anda tiba! Mengalihkan ke pembayaran...',
            ];
        }

        // 5. Jika slot penuh, tempatkan pengunjung ke antrean terurut (Sequenced Queue)
        $position = (int) Cache::remember("war_pos_{$productId}_{$queueId}", 1800, function () use ($productId) {
            return (int) Cache::increment("war_seq_{$productId}");
        });

        $served = (int) Cache::get("war_served_seq_{$productId}", 0);
        $ahead = max(1, $position - $served);

        // Polling interval acak 10-20 detik (traffic jitter) agar request tidak membombardir server bersamaan
        $retryAfter = rand(self::POLL_MIN_INTERVAL, self::POLL_MAX_INTERVAL);

        return [
            'status'      => 'waiting',
            'position'    => $ahead,
            'retry_after' => $retryAfter,
            'message'     => 'Tingginya antrean sedang diproses. Mohon tunggu giliran Anda...',
        ];
    }

    /**
     * Validasi tanda tangan dan masa berlaku Admission Token.
     */
    public function validateToken(?string $token, int $productId): bool
    {
        if (empty($token)) {
            return false;
        }

        $decoded = base64_decode($token, true);
        if (!$decoded) {
            return false;
        }

        $parts = explode('|', $decoded);
        if (count($parts) !== 4) {
            return false;
        }

        [$tokenProductId, $tokenQueueId, $expiresAt, $signature] = $parts;

        // Validasi kesesuaian produk
        if ((int) $tokenProductId !== (int) $productId) {
            return false;
        }

        // Validasi masa berlaku (TTL)
        if (time() > (int) $expiresAt) {
            return false;
        }

        // Validasi tanda tangan kriptografi HMAC
        $expectedPayload = "{$tokenProductId}|{$tokenQueueId}|{$expiresAt}";
        $expectedSignature = hash_hmac('sha256', $expectedPayload, (string) config('app.key'));
        if (!hash_equals($expectedSignature, $signature)) {
            return false;
        }

        // Validasi apakah token masih tercatat di Cache (belum dikonsumsi / expired)
        return Cache::has("war_valid_token_{$token}");
    }

    /**
     * Konsumsi Admission Token saat order berhasil dibuat (Single-Use Token).
     * Membebaskan slot checkout aktif agar pengguna berikutnya dalam antrean bisa segera masuk.
     */
    public function consumeToken(?string $token, int $productId): void
    {
        if (empty($token)) {
            return;
        }

        Cache::forget("war_valid_token_{$token}");

        // Kurangi counter active checkout
        $currentActive = (int) Cache::get("war_active_{$productId}", 0);
        if ($currentActive > 0) {
            Cache::decrement("war_active_{$productId}");
        } else {
            Cache::put("war_active_{$productId}", 0, 3600);
        }

        // Majukan nomor antrean yang terlayani
        Cache::increment("war_served_seq_{$productId}");
    }

    /**
     * Bebaskan slot jika pengguna membatalkan antrean atau keluar dari sesi.
     */
    public function releaseToken(?string $token, int $productId): void
    {
        $this->consumeToken($token, $productId);
    }
}

