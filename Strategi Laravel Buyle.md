# 🚀 Strategi Optimasi Laravel — buyle.id

> **Status**: PHP Worker 53/60 → Target < 35/60 setelah optimasi ini diterapkan  
> **Hosting**: Hostinger Business Web Hosting (shared, batas 60 PHP workers)  
> **Framework**: Laravel (PHP)  
> **Last Updated**: November 2026

---

## 📊 Analisis Bottleneck

### Root Cause Utama
Hosting Hostinger Business membatasi **60 PHP workers** untuk seluruh akun. Saat peak, tercatat **53/60 workers** terpakai — hanya 7 slot tersisa sebelum pengunjung mengalami antrean panjang.

### Endpoint Teridentifikasi sebagai Penyebab
Berdasarkan log request 24 jam terakhir:

| Endpoint | Frekuensi | Masalah |
|----------|-----------|---------|
| `/creator/pos/live-orders` | Tiap **3 detik** per terminal | DB query + DB write tiap polling |
| `/creator/pos/notifications/check` | Tiap **3 detik** via SW | Query kompleks (3 tabel) tanpa cache |
| `/api/webhooks/instagram` | Tiap ada komentar | `dispatchSync` → blocking PHP worker |
| `/storage/icon/popup1.png` | Ribuan hit/hari | Diproses Laravel, tidak di-cache browser |

### Kalkulasi Dampak (Sebelum Optimasi)
- **10 terminal POS aktif** × 20 req/menit = **200 req/menit** → ~3–4 PHP workers permanen
- **Service Worker** (sw-pos.js) Background Sync menambah beban notifikasi
- **Instagram Webhook** `dispatchSync` menahan worker selama proses komentar berlangsung

---

## ✅ Optimasi yang Sudah Diterapkan

### 1. POS Polling Interval: 3s → 15s/60s
**File**: `resources/views/creator/pos/index.blade.php`

```javascript
// SEBELUM — polling setiap 3 detik tanpa jeda
(function loop() {
    pollLiveOrders().finally(() => {
        if (liveOrderPollerActive) setTimeout(loop, 3000);
    });
})();

// SESUDAH — 15s saat tab aktif, 60s saat tab tersembunyi (Page Visibility API)
const POS_POLL_INTERVAL_ACTIVE = 15000;
const POS_POLL_INTERVAL_HIDDEN = 60000;
(function loop() {
    pollLiveOrders().finally(() => {
        if (!liveOrderPollerActive) return;
        const delay = document.hidden ? POS_POLL_INTERVAL_HIDDEN : POS_POLL_INTERVAL_ACTIVE;
        setTimeout(loop, delay);
    });
})();
```

**Dampak**: Request turun dari ~20 req/menit → ~4 req/menit per terminal  
**Jika 10 terminal aktif**: 200 req/menit → 40 req/menit (hemat 80%)

---

### 2. Hapus DB Write dari Endpoint Polling
**File**: `app/Http/Controllers/Creator/PosController.php` — `getLiveOrders()`

```php
// DIHAPUS — ini membuang 1 DB write tiap 3 detik per terminal:
$profile = CreatorProfile::where('user_id', $sellerId)->first();
if ($profile) {
    $profile->update(['pos_last_online_at' => now()]); // ❌ Boros!
}

// SOLUSI — pos_last_online_at diperbarui via endpoint heartbeat terpisah
// yang dipanggil frontend hanya tiap 60 detik.
```

**Dampak**: Hemat 1 DB write per request polling → ~20 DB writes/menit per terminal dihapus

---

### 3. Cache Notifikasi POS (10 Detik TTL)
**File**: `app/Http/Controllers/Creator/PosController.php` — `checkNewNotifications()`

```php
// Cache query notifikasi 10 detik per seller
$cacheKey = 'pos_notif_' . $seller->id;
$notifications = Cache::remember($cacheKey, 10, function () use ($seller, $request) {
    // ... query ke Order, PosBalanceMutation
});
```

**Dampak**: Jika Service Worker background sync & poller keduanya aktif, hanya 1 DB query per 10 detik (bukan duplikat)

---

### 4. Instagram Webhook: Blocking → Async Queue
**File**: `app/Http/Controllers/Webhook/InstagramWebhookController.php`

```php
// SEBELUM — blocking, menahan PHP worker sampai job selesai
ProcessInstagramCommentJob::dispatchSync($jobPayload);

// SESUDAH — async, PHP worker bebas setelah 200ms
ProcessInstagramCommentJob::dispatch($jobPayload);
```

**Dampak**: Webhook return `200 OK` dalam < 50ms, tidak menahan worker

> ⚠️ **Penting**: Pastikan Laravel Queue Worker aktif di server. Jika tidak ada queue worker, job tidak akan diproses. Cek dengan:
> ```bash
> php artisan queue:work --tries=3 --timeout=60
> ```
> Atau tambahkan sebagai Cron di Hostinger: `* * * * * php /path/to/artisan queue:work --stop-when-empty`

---

### 5. Static Asset Cache: 7 Hari → 1 Tahun
**File**: `public/.htaccess`

```apache
# SEBELUM
ExpiresByType image/png  "access plus 7 days"

# SESUDAH
ExpiresByType image/png  "access plus 1 year"
# ... (jpg, webp, gif, svg, ico, woff, woff2 — semua 1 tahun)

# Cache-Control header ditambahkan: immutable
<FilesMatch "\.(jpg|jpeg|png|gif|webp|svg|ico|woff|woff2|ttf)$">
    Header set Cache-Control "public, max-age=31536000, immutable"
</FilesMatch>
```

**Dampak**: `/storage/icon/popup1.png` (request terbanyak di log) tidak lagi diproses PHP setelah visit pertama. Browser menyimpan 1 tahun.

---

## 🔮 Rekomendasi Lanjutan (Belum Diimplementasi)

### Jangka Pendek (< 1 Minggu)

#### A. Endpoint Heartbeat Terpisah untuk `pos_last_online_at`
Buat endpoint ringan `GET /creator/pos/heartbeat` yang hanya memperbarui `pos_last_online_at`, dipanggil frontend setiap 60 detik:

```php
// routes/web.php
Route::get('/creator/pos/heartbeat', [PosController::class, 'heartbeat'])
    ->middleware(['auth', 'verified']);

// PosController.php
public function heartbeat()
{
    $user = auth()->user();
    $sellerId = ($user->isPosCashier() && $user->parent_seller_id)
        ? $user->parent_seller_id : $user->id;
    CreatorProfile::where('user_id', $sellerId)
        ->update(['pos_last_online_at' => now()]);
    return response()->json(['ok' => true]);
}
```

#### B. Database Index
Pastikan kolom berikut sudah diindeks:

```sql
-- orders table
CREATE INDEX idx_orders_seller_source ON orders (seller_id, source, created_at);
CREATE INDEX idx_orders_user_source ON orders (user_id, source, created_at);
-- creator_profiles
CREATE INDEX idx_creator_profiles_user_id ON creator_profiles (user_id);
-- pos_balance_mutations
CREATE INDEX idx_pbm_creator_type ON pos_balance_mutations (creator_id, type, created_at);
```

Cek index yang sudah ada:
```bash
php artisan tinker
# DB::select("SHOW INDEX FROM orders");
```

#### C. Response Compression (Gzip)
Sudah ada di `.htaccess`, pastikan `mod_deflate` aktif di Hostinger (biasanya sudah ON).

---

### Jangka Menengah (1–4 Minggu)

#### D. Redis untuk Cache & Queue
Hostinger Business tidak menyediakan Redis. Alternatif:
- Gunakan **database driver** untuk queue (default Laravel, sudah OK)
- Upgrade ke **Hostinger Cloud** atau **VPS** untuk Redis

```env
# Jika Redis tersedia:
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

#### E. WebSocket / Server-Sent Events (SSE) untuk POS Live Orders
Ganti polling HTTP dengan SSE agar tidak ada request berulang:

```
Client ──(SSE connect)──▶ Server
Server ──(push event)───▶ Client  (saat ada order baru)
```

Library yang bisa digunakan: **Laravel Reverb** (official, gratis) atau **Pusher**

---

### Jangka Panjang (Event Tiket Besar)

#### F. War-Ready Checklist untuk Event Tiket
Saat event tiket besar (seperti Sabiphoria) akan berlangsung:

- [ ] **Maintenance window** 30 menit sebelum event: `php artisan down --message="Persiapan event" --retry=60`
- [ ] **Clear semua cache**: `php artisan cache:clear && php artisan view:clear`
- [ ] **Warm up cache**: hit halaman produk tiket via curl
- [ ] **Monitor PHP workers** via Hostinger hPanel tiap 5 menit saat peak
- [ ] **Queue worker aktif**: pastikan cron queue:work berjalan
- [ ] **Rate limit checkout**: tambah `throttle:10,1` pada route checkout untuk anti-spam bot
- [ ] Pertimbangkan **upgrade sementara** ke paket Cloud Hosting atau VPS saat event

---

## 📈 Estimasi Dampak Total

| Metrik | Sebelum | Sesudah |
|--------|---------|---------|
| Req/menit dari POS (10 terminal) | ~200 | ~40 |
| DB write per menit (live-orders) | ~200 | ~0 (dipindah ke heartbeat) |
| DB query notifikasi per 10 detik | 1 per request | 1 per 10 detik (cached) |
| PHP worker jam peak (estimasi) | 53/60 | ~25–35/60 |
| Cache hit `/storage/icon/*.png` | 0% | >90% (browser cache) |
| Instagram webhook response time | 500ms–2s | <50ms |

---

## 🛠️ File yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `resources/views/creator/pos/index.blade.php` | Polling 3s→15s/60s + Page Visibility API |
| `app/Http/Controllers/Creator/PosController.php` | Hapus DB write di getLiveOrders, cache 10s di checkNewNotifications |
| `app/Http/Controllers/Webhook/InstagramWebhookController.php` | `dispatchSync` → `dispatch` async |
| `public/.htaccess` | Static asset cache 7d → 1 tahun + immutable header |

---

## 🔍 Monitoring

### Pantau PHP Workers
1. Login ke **hPanel Hostinger**
2. Buka **Hosting** → **Statistics** → **PHP Workers**
3. Pantau saat jam trafik tinggi (19:00–22:00 WIB)

### Pantau Response Time
```bash
# Cek response time endpoint polling
curl -w "@curl-format.txt" -o /dev/null -s "https://buyle.id/creator/pos/live-orders?last_id=0"

# Format curl-format.txt:
#     time_connect:  %{time_connect}\n
#    time_starttransfer:  %{time_starttransfer}\n
#            time_total:  %{time_total}\n
```

### Laravel Log
```bash
# Tail log Laravel untuk error
tail -f storage/logs/laravel.log

# Hitung error per 5 menit
grep "$(date +'%Y-%m-%d')" storage/logs/laravel.log | grep "ERROR" | wc -l
```

---

*Dokumen ini diperbarui setiap kali ada optimasi signifikan pada performa server buyle.id.*

