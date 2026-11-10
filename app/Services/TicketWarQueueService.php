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
     * Strict Atomic Lock & Active Lease Registry mencegah race condition pada lonjakan 1.000+ request.
     */
    public function getQueueStatus(int $productId, string $queueId, ?string $currentToken = null): array
    {
        $now = time();

        // 1. Cek apakah token yang dikirim client saat ini masih valid
        if ($currentToken && $this->validateToken($currentToken, $productId)) {
            $data = Cache::get("war_valid_token_{$currentToken}");
            $expiresAt = $data['expires_at'] ?? ($now + self::TOKEN_TTL);
            return [
                'status'      => 'admitted',
                'token'       => $currentToken,
                'expires_at'  => $expiresAt,
                'expires_in'  => max(0, $expiresAt - $now),
                'message'     => 'Sesi checkout tiket Anda aktif.',
            ];
        }

        // 2. Cek apakah queueId ini sudah pernah dialokasikan slot aktif
        $cachedToken = Cache::get("war_tok_by_queue_{$productId}_{$queueId}");
        if ($cachedToken && $this->validateToken($cachedToken, $productId)) {
            $data = Cache::get("war_valid_token_{$cachedToken}");
            $expiresAt = $data['expires_at'] ?? ($now + self::TOKEN_TTL);
            return [
                'status'      => 'admitted',
                'token'       => $cachedToken,
                'expires_at'  => $expiresAt,
                'expires_in'  => max(0, $expiresAt - $now),
                'message'     => 'Sesi checkout tiket Anda aktif.',
            ];
        }

        $maxConcurrent = (int) Setting::get('war_max_concurrent_checkout', self::DEFAULT_MAX_CONCURRENT_CHECKOUT);
        if ($maxConcurrent <= 0) {
            $maxConcurrent = self::DEFAULT_MAX_CONCURRENT_CHECKOUT;
        }

        // 3. ATOMIC LOCK untuk alokasi slot (Mencegah race condition read-then-write pada 1.000 request bersamaan)
        $allocLock = Cache::lock("war_alloc_lock_{$productId}", 3);
        $admitted = false;
        $admissionToken = null;
        $expiresAt = $now + self::TOKEN_TTL;

        if ($allocLock->get()) {
            try {
                // Baca dan bersihkan lease yang sudah kedaluwarsa (misal user tutup browser atau batal)
                $leases = Cache::get("war_active_leases_{$productId}", []);
                $validLeases = [];
                foreach ($leases as $qId => $expTime) {
                    if ($expTime > $now) {
                        $validLeases[$qId] = $expTime;
                    }
                }

                // Cek apakah kuota slot aktif masih di bawah batas maksimal
                if (count($validLeases) < $maxConcurrent) {
                    // Daftarkan lease untuk queueId ini
                    $validLeases[$queueId] = $expiresAt;
                    Cache::put("war_active_leases_{$productId}", $validLeases, self::TOKEN_TTL + 300);

                    // Terbitkan Cryptographic HMAC Admission Token (Single-Use Token)
                    $payload = "{$productId}|{$queueId}|{$expiresAt}";
                    $signature = hash_hmac('sha256', $payload, (string) config('app.key'));
                    $admissionToken = base64_encode("{$payload}|{$signature}");

                    Cache::put("war_valid_token_{$admissionToken}", [
                        'product_id' => $productId,
                        'queue_id'   => $queueId,
                        'expires_at' => $expiresAt,
                    ], self::TOKEN_TTL);

                    Cache::put("war_tok_by_queue_{$productId}_{$queueId}", $admissionToken, self::TOKEN_TTL);
                    $admitted = true;
                }
            } finally {
                $allocLock->release();
            }
        }

        if ($admitted && $admissionToken) {
            return [
                'status'      => 'admitted',
                'token'       => $admissionToken,
                'expires_at'  => $expiresAt,
                'expires_in'  => self::TOKEN_TTL,
                'message'     => 'Giliran Anda tiba! Mengalihkan ke pembayaran...',
            ];
        }

        // 4. Jika slot penuh, tempatkan pengunjung ke antrean terurut (Sequenced Queue)
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
     * Ambil data dari dalam token tanpa merusaknya
     */
    public function parseToken(?string $token): ?array
    {
        if (empty($token)) return null;
        $decoded = base64_decode($token, true);
        if (!$decoded) return null;
        $parts = explode('|', $decoded);
        if (count($parts) !== 4) return null;
        return [
            'product_id' => (int) $parts[0],
            'queue_id'   => $parts[1],
            'expires_at' => (int) $parts[2],
        ];
    }

    /**
     * Konsumsi Admission Token saat order berhasil dibuat (Single-Use Token).
     * Mencegah token yang sama digunakan untuk membuat pesanan kedua.
     * CATATAN: Slot checkout TIDAK langsung dilepas saat order dibuat, melainkan tetap
     * dipertahankan sampai pembayaran Midtrans berhasil / kedaluwarsa.
     */
    public function consumeTokenOnly(?string $token): void
    {
        if (empty($token)) {
            return;
        }
        Cache::forget("war_valid_token_{$token}");
    }

    /**
     * Lepaskan slot checkout saat order selesai dibayar atau kedaluwarsa/dibatalkan.
     * Memajukan antrean sehingga pengguna berikutnya dapat masuk.
     */
    public function releaseSlot(int $productId, ?string $queueId = null): void
    {
        $allocLock = Cache::lock("war_alloc_lock_{$productId}", 3);
        if ($allocLock->get()) {
            try {
                $leases = Cache::get("war_active_leases_{$productId}", []);
                if ($queueId && isset($leases[$queueId])) {
                    unset($leases[$queueId]);
                } else if (!empty($leases)) {
                    // Lepas 1 lease tertua
                    array_shift($leases);
                }
                Cache::put("war_active_leases_{$productId}", $leases, self::TOKEN_TTL + 300);

                // Majukan nomor antrean yang terlayani
                Cache::increment("war_served_seq_{$productId}");
            } finally {
                $allocLock->release();
            }
        }
    }
}
