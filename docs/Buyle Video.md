# 🎬 Buyle Video — Dokumentasi Fitur

> **Status:** Didokumentasikan, belum dibangun. Siap untuk dieksekusi.
> **Tanggal diskusi:** 2026-11-20
> **Working directory:** `c:\Users\dgtil\Downloads\PENTING\HVM Digital\CLIENT Local WEB & Deploy\buyle.id ex alaatrumah`
> **Deploy:** `curl.exe -s "https://buyle.id/deploy-hostinger"` (auto git pull + migrate --force + cache clear)

---

## 📌 Deskripsi Fitur

**Buyle Video** adalah fitur feed video shoppable mirip **Shopee Video** — pengguna menonton video TikTok yang di-embed langsung di halaman (tidak redirect ke TikTok), dan setiap video bisa di-tag dengan produk dari toko creator. Pengunjung bisa langsung beli / add to cart tanpa keluar dari halaman.

**Referensi visual:** Shopee Video, Instagram Reels + Shopping Tags, TikTok Shop Video.

---

## ✅ Keputusan Desain (Sudah Dikonfirmasi Owner)

| Aspek | Keputusan |
|---|---|
| **URL publik** | `/buyle-video` — feed global semua creator |
| **Sumber video** | TikTok URL saja (Instagram Reels = fase 2, belum sekarang) |
| **Cara play** | Lazy iframe inject via `IntersectionObserver` — play IN-PAGE, tidak redirect TikTok |
| **Autoplay** | Muted autoplay saat scroll masuk viewport (threshold 70%) |
| **Unmute** | 1× klik unmute → preferensi disimpan di `localStorage` → video berikutnya langsung bersuara |
| **Produk per video** | Maks 3 produk yang bisa di-tag |
| **Akses creator** | Semua creator bisa (tidak perlu e-KYC verified) |
| **Keranjang produk** | Tombol 🛒 kuning overlay di atas video → klik → slide-up panel produk → langsung checkout |

---

## 🗺️ Arsitektur Sistem

```
Creator paste TikTok URL di dashboard (/creator/videos)
        ↓
Backend fetch oEmbed TikTok API → dapat thumbnail_url, author, embed HTML
        ↓
Simpan ke DB: creator_shoppable_videos
        ↓
Creator tag 1–3 produk dari tokonya (pivot: shoppable_video_products)
        ↓
Public page /buyle-video menampilkan feed semua video aktif
        ↓
User scroll → IntersectionObserver → inject iframe TikTok → autoplay muted
        ↓
User klik 🛒 → slide-up panel produk yang di-tag → add to cart / beli
```

---

## 🗄️ Database Schema

### Tabel `creator_shoppable_videos`

```sql
id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
user_id          BIGINT UNSIGNED NOT NULL     -- FK → users (seller)
tiktok_url       VARCHAR(500) NOT NULL        -- URL asli TikTok
tiktok_video_id  VARCHAR(100) NOT NULL        -- Extracted video ID
video_type       ENUM('tiktok','reels') DEFAULT 'tiktok'
thumbnail_url    VARCHAR(500) NULL            -- Dari oEmbed API
caption          TEXT NULL                   -- Judul/caption video
author_name      VARCHAR(100) NULL            -- Nama akun TikTok
embed_html       TEXT NULL                   -- Raw embed HTML dari oEmbed
sort_order       INT DEFAULT 0
is_active        BOOLEAN DEFAULT TRUE
created_at       TIMESTAMP
updated_at       TIMESTAMP

INDEX: (user_id, is_active), (is_active, sort_order)
FK: user_id → users(id) ON DELETE CASCADE
```

### Tabel `shoppable_video_products` (Pivot)

```sql
id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
video_id    BIGINT UNSIGNED NOT NULL     -- FK → creator_shoppable_videos
product_id  BIGINT UNSIGNED NOT NULL     -- FK → services (tabel produk)
sort_order  INT DEFAULT 0

UNIQUE KEY: (video_id, product_id)
FK: video_id → creator_shoppable_videos(id) ON DELETE CASCADE
FK: product_id → services(id) ON DELETE CASCADE
```

> ⚠️ **PENTING:** Produk di Buyle.id disimpan di tabel **`services`** (bukan `products`).
> Model: `App\Models\Product` → `protected $table = 'services'`.

---

## 📁 File Yang Perlu Dibuat / Dimodifikasi

### Baru — Migration
```
database/migrations/YYYY_MM_DD_create_creator_shoppable_videos_table.php
database/migrations/YYYY_MM_DD_create_shoppable_video_products_table.php
```

### Baru — Models
```
app/Models/ShoppableVideo.php
```

Relasi di `ShoppableVideo`:
```php
public function user()     { return $this->belongsTo(User::class); }
public function products() { return $this->belongsToMany(Product::class, 'shoppable_video_products', 'video_id', 'product_id')->withPivot('sort_order')->orderBy('shoppable_video_products.sort_order'); }
```

Tambahkan relasi di `App\Models\User`:
```php
public function shoppableVideos() { return $this->hasMany(ShoppableVideo::class); }
```

### Baru — Controllers
```
app/Http/Controllers/Creator/ShoppableVideoController.php
    index()       → list semua video creator (dashboard)
    store()       → tambah video baru (paste URL TikTok)
    update()      → edit caption + produk yang di-tag
    destroy()     → hapus video
    toggle()      → aktif/nonaktif (PATCH)
    reorder()     → ubah urutan (POST, JSON array of ids)
    fetchOembed() → AJAX: fetch info dari TikTok oEmbed API

app/Http/Controllers/BuyleVideoController.php
    index()       → public feed /buyle-video (semua creator, paginate 12)
```

### Baru — Views
```
resources/views/creator/videos/index.blade.php    → Dashboard kelola video
resources/views/buyle-video/index.blade.php       → Public feed
```

### Modifikasi — Routes (`routes/web.php`)

Dalam grup `Route::middleware(['auth', 'role:seller'])->prefix('creator')->name('creator.')`:
```php
// Shoppable Videos
Route::prefix('videos')->name('videos.')->group(function () {
    Route::get('/',                 [ShoppableVideoController::class, 'index'])->name('index');
    Route::post('/',                [ShoppableVideoController::class, 'store'])->name('store');
    Route::put('/{video}',          [ShoppableVideoController::class, 'update'])->name('update');
    Route::delete('/{video}',       [ShoppableVideoController::class, 'destroy'])->name('destroy');
    Route::patch('/{video}/toggle', [ShoppableVideoController::class, 'toggle'])->name('toggle');
    Route::post('/reorder',         [ShoppableVideoController::class, 'reorder'])->name('reorder');
    Route::post('/fetch-oembed',    [ShoppableVideoController::class, 'fetchOembed'])->name('fetch-oembed');
});
```

Di luar grup (public):
```php
Route::get('/buyle-video', [BuyleVideoController::class, 'index'])->name('buyle.video');
```

---

## 🔌 TikTok oEmbed API

**Endpoint:** `https://www.tiktok.com/oembed?url={encoded_tiktok_url}`

**Response penting:**
```json
{
  "title": "Video title / caption",
  "author_name": "@username",
  "thumbnail_url": "https://p16-sign.tiktokcdn-us.com/...",
  "thumbnail_width": 720,
  "thumbnail_height": 1280,
  "html": "<blockquote class=\"tiktok-embed\" ...>...</blockquote>",
  "provider_name": "TikTok"
}
```

**Extract Video ID dari URL:**
```php
// URL formats TikTok:
// https://www.tiktok.com/@user/video/7123456789012345678
// https://vm.tiktok.com/XXXXXXXX/ (short URL — perlu resolve redirect dulu)

preg_match('/video\/(\d+)/', $tiktokUrl, $matches);
$videoId = $matches[1] ?? null;
```

**Embed iframe URL (untuk lazy inject):**
```
https://www.tiktok.com/embed/v2/{video_id}?autoplay=1&muted=1
```

> ⚠️ **Infra yang sudah ada:** Route `creator.bio.tiktok-oembed` di `routes/web.php` ~line 1154 sudah menghandle fetch oEmbed TikTok. Lihat method `tiktokOembed()` di `app/Http/Controllers/Creator/CreatorBioController.php` dan reuse logikanya.

---

## ⚙️ JavaScript: Scroll Autoplay Mechanism

```javascript
// Preferensi mute user (ingat antar sesi)
let userWantSound = localStorage.getItem('buyle_video_sound') === '1';

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        const container = entry.target;

        if (entry.isIntersecting) {
            // Lazy inject iframe saat masuk viewport
            const videoId   = container.dataset.videoId;
            const muted     = userWantSound ? 0 : 1;

            container.innerHTML = `
                <iframe
                    src="https://www.tiktok.com/embed/v2/${videoId}?autoplay=1&muted=${muted}&rel=0"
                    allow="autoplay; fullscreen; encrypted-media"
                    allowfullscreen
                    style="width:100%;height:100%;border:none;border-radius:inherit;">
                </iframe>`;
        } else {
            // Buang iframe saat keluar viewport (hemat memori & CPU)
            const thumb = container.dataset.thumbnail;
            container.innerHTML = `
                <img src="${thumb}" alt="Video" style="width:100%;height:100%;object-fit:cover;">
                <div class="bv-play-btn">▶</div>`;
        }
    });
}, { threshold: 0.70 });

document.querySelectorAll('.bv-video-container').forEach(el => observer.observe(el));

// Toggle mute — klik icon 🔇/🔊
function bvToggleMute(btn) {
    userWantSound = !userWantSound;
    localStorage.setItem('buyle_video_sound', userWantSound ? '1' : '0');
    btn.textContent = userWantSound ? '🔊' : '🔇';
    // Re-inject iframe dengan setting baru untuk video yang sedang play
    const container = btn.closest('.bv-item').querySelector('.bv-video-container');
    const videoId = container.dataset.videoId;
    const muted = userWantSound ? 0 : 1;
    container.querySelector('iframe').src =
        `https://www.tiktok.com/embed/v2/${videoId}?autoplay=1&muted=${muted}&rel=0`;
}
```

**Fallback Safari iOS:**
Safari sangat ketat soal autoplay. Jika iframe gagal autoplay, tampilkan tombol ▶ besar di tengah thumbnail. User klik sekali → iframe load & play. Ini sudah cukup karena satu klik = user gesture yang diakui browser.

---

## 🎨 UI/UX Spec

### Public Feed (`/buyle-video`)

**Desktop:** Grid 2–3 kolom, card video 9:16 (portrait TikTok style), infinite scroll atau paginate 12.

**Mobile:** 1 kolom full-width, card pendek (tidak full height seperti Reels), scroll-snap optional.

**Tiap Card:**
```
┌──────────────────────────────────┐
│  [Thumbnail / TikTok iframe]     │  ← Aspect ratio 9:16, border-radius 16px
│                                  │
│  🔇                     [⚙ icon] │  ← Mute toggle pojok kiri, opsi pojok kanan
│                                  │
│  @namatoko ✓  Caption video...  │  ← Overlay bawah, gradient hitam
│  [🛒 2 Produk]                   │  ← Tombol keranjang kuning (#FFD700)
└──────────────────────────────────┘
```

### Slide-up Panel Produk

```
┌──────────────────────────────────┐
│  ─────── (drag handle)           │
│  Produk dalam video ini    ✕     │
│  ──────────────────────────────  │
│  [img] Nama Produk 1   Rp 50rb   │
│         ★★★★☆         [+ Keranjang] │
│  ──────────────────────────────  │
│  [img] Nama Produk 2   Rp 120rb  │
│         ★★★★★         [+ Keranjang] │
└──────────────────────────────────┘
```

Gunakan class CSS yang sudah ada di codebase untuk bottom sheet (cek di komponen modal yang sudah ada).

---

## 🖥️ Dashboard Creator (`/creator/videos`)

**Style:** Mirip `/creator/products` — header mobile + tombol back + judul, card list minimalis.

### Alur Tambah Video:
```
1. Paste URL TikTok di input
2. Klik "Ambil Info" → AJAX ke fetchOembed()
3. Preview: thumbnail + caption + nama akun TikTok
4. Pilih produk (search autocomplete dari produk sendiri, maks 3, tampil sebagai chip)
5. Klik Simpan → POST /creator/videos
6. Video tampil di feed publik
```

### List Video di Dashboard:
- Thumbnail kecil (60×80px) + judul + status aktif/nonaktif
- Toggle switch aktif/nonaktif
- Icon edit (caption + produk) + icon hapus
- Reorder: tombol ↑ ↓ atau drag handle

---

## 🔗 Integrasi Checkout / Cart

Gunakan sistem yang sudah ada di codebase:

```php
// Add to cart (Blade form)
<form action="{{ route('cart.add') }}" method="POST">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="btn-keranjang-kuning">+ Keranjang</button>
</form>

// Direct checkout
<a href="{{ route('checkout.show', $product->slug) }}">Beli Sekarang</a>
```

Cek route yang tersedia dengan: `php artisan route:list --name=cart,checkout`

---

## 📋 Checklist Implementasi (Urutan Prioritas)

### Fase 1 — Backend & Database
- [ ] Migration `create_creator_shoppable_videos_table`
- [ ] Migration `create_shoppable_video_products_table`
- [ ] Model `ShoppableVideo` + relasi `products()`, `user()`
- [ ] Tambah relasi `shoppableVideos()` di `User` model
- [ ] `ShoppableVideoController` lengkap (CRUD + fetchOembed)
- [ ] `BuyleVideoController` (public feed, paginate 12)
- [ ] Routes di `routes/web.php`

### Fase 2 — Creator Dashboard
- [ ] View `creator/videos/index.blade.php`
  - [ ] Mobile header (back + "Video Saya" + tombol tambah)
  - [ ] Form paste URL + fetch oEmbed AJAX
  - [ ] Preview thumbnail sebelum simpan
  - [ ] Search & pilih produk (maks 3, chips UI)
  - [ ] List video: thumbnail + status toggle + edit + hapus + reorder

### Fase 3 — Public Feed
- [ ] View `buyle-video/index.blade.php`
  - [ ] Grid card video (thumbnail mode default)
  - [ ] IntersectionObserver + lazy iframe inject
  - [ ] Autoplay muted + toggle mute + localStorage preference
  - [ ] Overlay: nama toko, caption, tombol 🛒 kuning
  - [ ] Slide-up panel produk (mobile-friendly bottom sheet)
  - [ ] Add to cart / direct checkout dari panel
  - [ ] Fallback Safari iOS (manual klik ▶)

### Fase 4 — Navigasi
- [ ] Tambah menu "Buyle Video" di creator sidebar/app launcher (`/creator/app`)
- [ ] (Opsional) Tambah icon "Video" di bottom navbar publik `resources/views/layouts/app.blade.php`

### Fase 5 — Deploy
- [ ] `php artisan view:clear`
- [ ] `git add -A && git commit -m "feat: Buyle Video — shoppable TikTok video feed"`
- [ ] `git push origin main`
- [ ] `curl.exe -s "https://buyle.id/deploy-hostinger"`

---

## ⚠️ Catatan Penting untuk Agent

1. **Jangan re-upload video ke server.** Semua video tetap di TikTok. Buyle.id hanya menyimpan URL + metadata.

2. **Tabel produk adalah `services`**, bukan `products`. Model `App\Models\Product` punya `protected $table = 'services'`.

3. **oEmbed TikTok bisa gagal** jika video private/dihapus/region-blocked. Wajib handle exception & tampilkan error jelas ke creator.

4. **Fetch oEmbed dari server (PHP)**, bukan dari JavaScript client-side (kena CORS). Gunakan `Http::get()` atau `file_get_contents()`.

5. **Short URL TikTok** (`vm.tiktok.com/xxx`) perlu di-resolve dulu via redirect sebelum extract video ID. Gunakan `Http::withOptions(['allow_redirects' => true])->get($url)->effectiveUri()`.

6. **Autoplay di Safari iOS** masih bermasalah. Implementasikan fallback: tampilkan thumbnail + tombol ▶ besar. Klik → load iframe.

7. **Existing oEmbed infra:** `app/Http/Controllers/Creator/CreatorBioController.php` method `tiktokOembed()` sudah berfungsi. Reuse logikanya.

8. **Cache thumbnail:** Simpan `thumbnail_url` ke DB saat video ditambah. Jangan fetch ulang setiap load feed (lambat & bisa rate-limit).

---

*Dokumentasi ini dibuat agar AI agent berikutnya dapat langsung eksekusi tanpa brainstorm ulang dari nol.*
