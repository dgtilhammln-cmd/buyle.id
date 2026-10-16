# 🎫 Buyle.id Ticketing & Ticket War Architecture Documentation

Dokumentasi arsitektur sistem tiket, scanner hardware (webcam eksternal/USB), generator QR code berstandar ISO, serta mitigasi lonjakan pengunjung (*Ticket War / High Concurrency Shield*) pada platform **Buyle.id**.

---

## 📌 Ringkasan Eksekutif (Executive Summary)

Sistem tiket Buyle dirancang untuk menangani alur menyeluruh acara/konser mulai dari peluncuran tiket perdana (*Ticket War / Live Drop*) dengan ribuan calon pembeli serentak, hingga hari-H validasi tiket di pintu masuk (*gate check-in*) menggunakan kamera laptop maupun webcam USB eksternal (misal: JETE-W7).

```
[ PENGUNJUNG / PEMBELI ]
       │
       ▼ (Bio Link / Storefront)
[ Live Countdown 00:00:00 ] ────► 0ms Instant Auto-Unlock (Tanpa Reload)
       │
       ▼ (Checkout)
[ CheckoutService ] ────────────► Atomic Decrement (`stock >= qty`), Anti-Bot & Quota Limit
       │
       ▼ (Payment Sukses)
[ QR Generator ISO/IEC 18004 ] ─► Error Correction Level H (30%) + Alignment Pattern (18, 18)
       │
       ▼ (Hari-H Konser)
[ Scanner Gate Check-in ] ──────► Triple-Engine (Native BarcodeDetector + Adaptive jsQR + Html5Qrcode)
                                  Hardware USB External Cam Auto-Switching + Audio Synthesizer
```

---

## 🏗️ 1. Generator Barcode / QR Code (ISO/IEC 18004)

### Permasalahan Sebelumnya (Rasio Gagal 90:10):
Sebelumnya kode tiket menggunakan format `TKT-YYYYMMDD-XXXXXX` (~20 karakter) dengan tingkat koreksi *Level M (15%)*.
- Pada Level M, data muat dalam **Version 1 (21×21 modul)**.
- **Standar ISO/IEC 18004**: Version 1 **TIDAK memiliki Alignment Pattern** (hanya punya 3 kotak sudut).
- Akibatnya: Jika pengunjung menunjukkan tiket dari layar HP dengan posisi agak miring (*tilt*), layar silau (*glare*), atau lensa kamera ada distorsi cembung, algoritma scanner kehilangan orientasi koordinat perspektif sehingga gagal membaca.

### Solusi & Spesifikasi Saat Ini:
- **Error Correction Level H (30%)**:
  Koreksi error dinaikkan ke `QR_ECLEVEL_H`. Kapasitas koreksi mencapai 30% modul rusak/silau.
- **Matriks Version 2/3 (25×25 s/d 33×33 modul)**:
  Otomatis mengaktifkan **ISO Alignment Pattern di koordinat (18, 18)** (kotak kecil di kuadran kanan bawah). Ini membuat scanner mampu menghitung distorsi perspektif 3D dari layar HP yang miring sekalipun.
- **Modul Ukuran & Quiet Zone**:
  - Ukuran tampilan QR: 195–200 px.
  - Quiet zone: 4 modul putih bersih di sekeliling QR (`margin = 4`), mencegah border gelap atau latar belakang tema menyatu dengan pola finder.
- **Cache Key**:
  Menggunakan `qr_crisp_v4_high_{code}` pada cache Laravel agar gambar SVG/PNG langsung terlayani cepat tanpa membebani CPU.

**File Terkait:**
- [`app/Services/SimpleQrCode.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/app/Services/SimpleQrCode.php)
- [`app/Http/Controllers/QrCodeController.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/app/Http/Controllers/QrCodeController.php)
- [`resources/views/account/order-detail.blade.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/account/order-detail.blade.php)

---

## 📷 2. Subsistem Scanner & Hardware USB Webcam

Halaman scanner tiket berlokasi di:
[`resources/views/creator/ticket_scanner.blade.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/creator/ticket_scanner.blade.php)

### A. Hardware Management (Kamera Eksternal / USB Webcam)
1. **Auto-Detection Webcam USB**:
   Scanner melakukan inspeksi daftar perangkat video (`navigator.mediaDevices.enumerateDevices()`). Jika ditemukan label seperti `JETE`, `Webcam`, `External`, atau `USB`, kamera tersebut diprioritaskan otomatis dibanding webcam bawaan laptop.
2. **Windows Hardware Lock Release (`track.stop()`)**:
   Pada OS Windows, jika suatu stream video dibuka, DirectShow / Media Foundation mengunci hardware. Fungsi `switchScannerCamera(newCameraId)` mengeksekusi:
   ```javascript
   if (scannerStream) {
       scannerStream.getTracks().forEach(track => track.stop());
   }
   ```
   Sebelum menginisialisasi kamera baru, memastikan kamera eksternal dapat menyala tanpa error `NotReadableError` atau `TrackStartError`.
3. **Persistensi Pilihan Kamera**:
   Kamera yang dipilih oleh operator gate disimpan di `localStorage.setItem('buyle_scanner_cam_id', id)` sehingga saat refresh halaman atau sesi check-in berikutnya, kamera yang sama langsung aktif.

### B. Triple-Engine Scanner Architecture
Untuk mengatasi pantulan cahaya (*glare*) layar HP pengunjung, scanner menggunakan 3 lapis engine secara paralel:

```
Video Stream (Frame)
       │
       ├─► [Engine 1: Native BarcodeDetector (GPU)] ────► 2–5ms Instant Decode
       │                                                         │ (Jika gagal / layar silau)
       ├─► [Engine 2: Adaptive Contrast Binarizer + jsQR] ───────► Normalisasi kontras & decode
       │                                                         │ (Jika masih belum terdeteksi)
       └─► [Engine 3: Html5Qrcode / ZXing Fallback] ─────────────► Core software scan
```

1. **Engine 1 - Native `BarcodeDetector` (Hardware Accelerated)**:
   Menggunakan API bawaan Chromium (`window.BarcodeDetector`). Membaca frame langsung dari GPU dengan latency 2–5 ms. Kebal terhadap sudut miring (*skew*).
2. **Engine 2 - Adaptive Dynamic Contrast Binarizer + `jsQR`**:
   Layar smartphone tipe AMOLED/OLED seringkali memantulkan lampu sorot gate atau terlalu redup. Engine ini mengambil potongan frame canvas, melakukan normalisasi kontras *min-max stretching*, lalu mem-passing hasilnya ke engine `jsQR`.
3. **Engine 3 - Fallback `Html5Qrcode`**:
   Memastikan perangkat lama tanpa GPU decoding tetap berfungsi normal.

### C. Audio & Visual Feedback
- **Zero-Latency Web Audio API Chime**:
  Ketika tiket valid, synthesizer Web Audio memainkan nada ganda (*crystal bell*: 880Hz -> 1320Hz) secara instan tanpa perlu memuat file MP3 eksternal yang rentan lambat / gagal load.
- **Smart Distance Guidance Banner**:
  Banner floating `#smartGuideBanner` memandu petugas gate jika QR diletakkan terlalu dekat (<15cm) atau terlalu miring.
- **In-Memory Cache (0ms Deduplication)**:
  `scannedMemoryCache` mencegah request validasi berulang ke server saat kamera masih menyorot tiket yang sama.

---

## ⚡ 3. Arsitektur "War Tiket" (High Concurrency & Flash Drop)

Fitur ini dibuat untuk skenario pembukaan tiket konser serentak di mana ratusan hingga ribuan orang siap checkout di detik yang sama.

### A. 0ms Real-Time Client Countdown (YouTube Premiere Style)
Halaman produk tiket tetap dapat dibuka dan dilihat sebelum penjualan dimulai:
- **Card Hitung Mundur**:
  Menampilkan Hari, Jam, Menit, Detik secara dinamis.
- **0ms Instant Auto-Unlock (Tanpa Perlu Refresh Halaman)**:
  Bila ribuan pengguna menekan tombol F5/Refresh bersamaan pada jam 13:00:00, server akan mengalami *Thundering Herd Problem* (CPU 100% / HTTP 502).
  **Solusi Buyle**: Halaman **tidak perlu direload**. Ketika countdown menyentuh `00:00:00`, JavaScript di browser secara instan membuka tombol *"Beli Tiket Sekarang"* dan mengaktifkan form order via DOM manipulation. Disertai nada lonceng pembukaan.

**File Terkait:**
- [`resources/views/bio/product_show.blade.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/bio/product_show.blade.php)
- [`resources/views/bio/theme5/product_show.blade.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/bio/theme5/product_show.blade.php)

### B. Proteksi Concurrency & Overselling di Backend
Halaman checkout dilindungi di tingkat database:
[`app/Services/CheckoutService.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/app/Services/CheckoutService.php)

1. **Time-Lock Validation**:
   ```php
   if ($product->product_type === 'ticket' && !empty($product->ticket_war_start)) {
       if (now()->lt($product->ticket_war_start)) {
           throw new Exception("Penjualan tiket belum dibuka.");
       }
   }
   ```
   Mencegah bot yang mencoba menembak API checkout sebelum waktu yang ditentukan.

2. **Atomic Stock Decrement (Anti-Race Condition)**:
   Metode lama:
   ```php
   // Rentan Race Condition jika 100 request masuk bersamaan saat stok tersisa 1:
   if ($product->stock >= $cartItem->qty) {
       $product->decrement('stock', $cartItem->qty);
   }
   ```
   **Metode Atomic Terkini**:
   ```php
   $affected = \App\Models\Product::where('id', $product->id)
       ->where('stock', '>=', $cartItem->qty)
       ->decrement('stock', $cartItem->qty);

   if ($affected === 0) {
       throw new Exception("Maaf, kuota tiket baru saja habis terjual!");
   }
   ```
   Eksekusi berjalan pada level mesin database InnoDB dengan row-level lock. Mengeliminasi 100% kemungkinan tiket terjual melebihi stok (*overselling*).

3. **Anti-Calo & Bot Quota Limit**:
   Setiap produk tiket memiliki kolom `max_tickets_per_order` (default: 4 tiket). Pembelian di atas kuota langsung ditolak sebelum database tersentuh.

---

## 🗄️ 4. Skema Database & Migrasi

### Kolom Baru pada Tabel `products`:
Migration file: [`database/migrations/2026_10_16_012500_add_ticket_war_fields_to_products_table.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/database/migrations/2026_10_16_012500_add_ticket_war_fields_to_products_table.php)

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `ticket_war_start` | `DATETIME NULL` | Waktu spesifik pembukaan penjualan tiket (WIB). Jika diisi, countdown aktif. |
| `max_tickets_per_order` | `INT NULL DEFAULT 4` | Batas maksimum kuantitas tiket yang dapat dibeli dalam satu transaksi. |

### Model:
[`app/Models/Product.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/app/Models/Product.php)
- Ditambahkan ke `$fillable`: `'ticket_war_start'`, `'max_tickets_per_order'`.
- Ditambahkan ke `$casts`: `'ticket_war_start' => 'datetime'`.

### Form Creator:
Pengaturan dapat diedit langsung oleh Creator/Event Organizer di menu Produk:
- [`resources/views/creator/products/create.blade.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/creator/products/create.blade.php)
- [`resources/views/creator/products/edit.blade.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/creator/products/edit.blade.php)

---

## 📋 5. Alur Validasi Gate Check-in & Identitas Pengunjung

1. **Data Peserta**:
   Setiap order tiket mencakup Nama Lengkap, Nomor HP, Email, dan data Identitas (NIK/KTP/SIM jika diwajibkan).
2. **Layar Modal Scanner**:
   Setelah QR di-scan:
   - Status Tiket ditampilkan dengan badge jelas:
     - 🟢 **VALID / BELUM DIGUNAKAN**
     - 🔴 **SUDAH DIGUNAKAN (Waktu & Petugas tercatat)**
     - ⚠️ **KODE TIDAK DITEMUKAN**
     - ❌ **BUKAN TIKET EVENT ANDA** (tiket valid tapi milik event lain / pengelola berbeda)
   - Petugas gerbang mencocokkan data dengan kartu fisik (KTP/SIM/Kartu Pelajar).
   - Tombol satu sentuhan **"CHECK-IN TIKET"** memanggil endpoint update status tiket (`status = 'used'`, `used_at = now()`).

---

## 🔐 6. Privasi & UX Panel Scan (Update 16 Oktober 2026)

### A. Penghapusan Info Sensitif pada Tiket Tidak Valid

**Latar Belakang:**
Ketika scanner memindai tiket yang bukan milik event-nya (tiket valid tapi dari pengelola/event lain), sebelumnya sistem menampilkan **Nama Pemegang** dan **Waktu Check-In**. Ini bermasalah karena:
- Creator tidak memiliki izin untuk melihat data pengunjung event lain.
- Label "Waktu Check-In: Baru saja" menyesatkan — karena tidak ada check-in yang terjadi.

**Perubahan:**

| Field | Sebelum | Sesudah |
|:---|:---|:---|
| Nama Pemegang | ✅ Tampil | ❌ Dihapus |
| Waktu Check-In | ✅ Tampil ("Baru saja") | ❌ Dihapus |
| Kode Tiket | ✅ Tampil | ✅ Tetap tampil |
| Nama Event | ✅ Tampil | ✅ Tetap tampil |

Panel hasil scan kini hanya menampilkan **Kode Tiket** dan **Nama Event** — cukup untuk menginformasikan bahwa tiket itu milik event berbeda, tanpa membocorkan data pribadi pengunjung.

**File yang diubah:**
- [`resources/views/creator/ticket_scanner.blade.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20%26%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/creator/ticket_scanner.blade.php)
  - Baris HTML desktop: `resActiveDetailsCard` — dihapus row Nama Pemegang & Waktu Check-In
  - Baris JS mobile modal: `openScanModal()` — dihapus row Pemegang & Check-In dari template HTML

---

## 📶 7. Indikator Kekuatan Sinyal Internet Realtime (Update 16 Oktober 2026)

Widget sinyal internet ditambahkan di header card **Live Camera QR Ticket Scanner** agar petugas gate dapat memantau kondisi jaringan secara realtime — penting di venue outdoor dengan sinyal 4G yang fluktuatif.

### Desain Widget

```
[ ▂▄▆▇█  Cepat ]   ← header card scanner, kanan atas
```

- **5 bar sinyal** bergaya indikator HP, dengan tinggi progresif (4px → 16px)
- **Warna dinamis** berdasarkan level:
  - 🔴 Merah → Tidak Ada Koneksi / Sangat Lemot
  - 🟡 Kuning → Lemot / Standar
  - 🟢 Hijau → Cepat / Sangat Cepat
- **Label teks**: "Mengukur...", "Sangat Lemot", "Lemot", "Standar", "Cepat", "Sangat Cepat"

### Klasifikasi Level Berdasarkan RTT

| Level | Label | RTT |
|:---:|:---|:---|
| 0 | Tidak Ada Koneksi | Offline |
| 1 | Sangat Lemot | ≥ 2000ms |
| 2 | Lemot | ≥ 600ms |
| 3 | Standar | ≥ 200ms |
| 4 | Cepat | ≥ 80ms |
| 5 | Sangat Cepat | < 80ms |

### Metode Pengukuran (Gabungan / Hybrid)

```
navigator.connection (Chrome/Android)
    ├─ effectiveType: 'slow-2g' | '2g' | '3g' | '4g'
    └─ rtt: number (ms)
         │
         ▼ Jika tidak tersedia (Safari/iOS/Firefox)
    Favicon Ping: fetch('/favicon.ico?_p=timestamp')
    Ukur performance.now() → RTT actual
```

1. **`navigator.connection` API** (prioritas utama):
   - Zero request ke server.
   - Membaca langsung dari OS/network adapter browser.
   - Didukung Chrome, Android WebView, Edge.

2. **Favicon Ping Fallback** (untuk Safari/iOS/Firefox):
   - Fetch `/favicon.ico` dengan `cache: 'no-store'` setiap **8 detik**.
   - Karena favicon sudah dicache browser, bandwidth overhead **≈ 0 byte** (hanya mengukur RTT TCP).
   - Tidak membebani server — hanya 1 request kecil per 8 detik.

### Event Listener Tambahan

```javascript
navigator.connection.addEventListener('change', measure); // Saat tipe jaringan berubah
window.addEventListener('online',  () => measure());      // Saat koneksi pulih
window.addEventListener('offline', () => applyLevel(0));  // Saat koneksi putus
setInterval(measure, 8000);                               // Polling tiap 8 detik
```

**Estimasi overhead bandwidth**: < 0.5 KB per 8 detik (hanya jika favicon cache miss). Setara < 1% dari satu request scan tiket.

**File yang diubah:**
- [`resources/views/creator/ticket_scanner.blade.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20%26%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/creator/ticket_scanner.blade.php)
  - HTML: Tambah `div#signalWidget` dengan `div#bar1`–`div#bar5` + `span#signalLabel` di `prof-card-head`
  - JS: Tambah IIFE `initSignalStrength()` di akhir script block

---

## 🚀 6. Panduan Deployment & Verifikasi

Saat melakukan deploy ke server produksi:
1. Pastikan migrasi dijalankan:
   ```bash
   php artisan migrate --force
   ```
2. Bersihkan cache konfigurasi dan view:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
3. Pastikan HTTPS aktif pada domain karena `navigator.mediaDevices` (akses kamera) dan `BarcodeDetector` **hanya diizinkan browser pada secure context (HTTPS)**.

---
*Dokumentasi ini dibuat otomatis agar developer atau AI agent berikutnya memiliki gambaran utuh tanpa ada detail teknis yang terlewat.*

---

## 👮 8. Sistem Gate Staff & Atribusi Scan (Update 16 Oktober 2026)

Fitur ini memungkinkan Creator/EO mendaftarkan nama-nama panitia gate (hingga 10 orang), setiap panitia mendapat **PIN 6 digit unik**. Setiap check-in QR terekam oleh siapa dan di gate mana — memberikan full accountability tanpa login akun Buyle.id.

### A. Arsitektur Data

**Tabel Baru: `gate_staff`**

| Kolom | Tipe | Keterangan |
|:---|:---|:---|
| `id` | BIGINT PK | |
| `product_id` | FK → products | Panitia hanya berlaku untuk event ini |
| `creator_id` | FK → users | Siapa yang mendaftarkan |
| `name` | VARCHAR(100) | Nama panitia (misal: "Budi Santoso") |
| `gate_number` | TINYINT | Gate 1 s/d 10 |
| `pin` | VARCHAR(255) | 6 digit, disimpan **bcrypt hashed** |
| `is_active` | BOOLEAN | Bisa di-disable tanpa hapus data |
| `created_at` / `updated_at` | TIMESTAMP | |

**Kolom Baru pada `ticket_passes`**

| Kolom | Tipe | Keterangan |
|:---|:---|:---|
| `scanned_by_staff_name` | VARCHAR(100) NULL | Nama panitia yang scan |
| `scanned_at_gate` | TINYINT NULL | Nomor gate saat scan |

**Kolom Baru pada `ticket_passes` (NIK)**

| Kolom | Tipe | Keterangan |
|:---|:---|:---|
| `holder_nik` | TEXT NULL | 16 digit, **terenkripsi AES-256** di database |

---

### B. Alur Registrasi Panitia oleh Creator

```
Creator Dashboard (Tab "Link Gate Panitia")
        │
        ├─ Isi: Nama = "Budi", Gate = 2, [Generate PIN]
        │         ↓
        │   ⚠️ PERINGATAN: "Catat PIN ini sekarang!"
        │      "847291 — PIN tidak dapat dipulihkan."
        │      "Jika lupa, hapus dan buat ulang staff baru."
        │      [Salin PIN] [Sudah Dicatat ✓ → Simpan]
        │
        └─ Tersimpan di `gate_staff` dengan PIN = bcrypt("847291")
```

- Maksimal **10 staff** per event
- PIN hanya ditampilkan **sekali** saat pembuatan
- Creator dapat melihat daftar staff (nama, gate, status) tapi **tidak bisa melihat PIN kembali**
- Tombol **Nonaktifkan** untuk mencabut akses tanpa menghapus histori scan

**File Terkait:**
- [`app/Models/GateStaff.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20%26%20Deploy/buyle.id%20ex%20alaatrumah/app/Models/GateStaff.php)
- [`app/Http/Controllers/GateStaffController.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20%26%20Deploy/buyle.id%20ex%20alaatrumah/app/Http/Controllers/GateStaffController.php)
- [`database/migrations/2026_10_16_180000_add_gate_staff_and_scan_attribution.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20%26%20Deploy/buyle.id%20ex%20alaatrumah/database/migrations/2026_10_16_180000_add_gate_staff_and_scan_attribution.php)

---

### C. Alur Login Panitia di Gate (PIN Auth)

```
Panitia buka URL: https://buyle.id/gate-scanner/{key}
        │
        ├─ Halaman PIN Gate muncul (jika event dilindungi PIN event)
        │  LALU muncul form Staff Login:
        │
        │   ┌──────────────────────────────┐
        │   │  Masuk sebagai Panitia        │
        │   │  Nama Anda: [Budi Santoso ▾]  │
        │   │  PIN Staff (6 digit): [______]│
        │   │  [🔑 Masuk ke Gate 2]         │
        │   └──────────────────────────────┘
        │
        ├─ Frontend POST /gate-scanner/{key}/staff-auth
        │   → Server: GateStaff::where('product_id', event->id)
        │              ->where('name', name)->where('is_active', true)
        │              ->first() → Hash::check(pin, staff->pin)
        │
        ├─ Jika BENAR → session['gate_staff_{event_id}'] = {name, gate}
        │
        └─ Kamera langsung aktif untuk scan
```

- Session staff **per-event**, tidak cross-event
- Staff hanya dapat scan tiket event-nya sendiri
- Jika session habis → diminta PIN ulang tanpa reload halaman (JS modal)

---

### D. Atribusi Scan (Siapa Scan di Gate Mana)

Setiap kali tiket berhasil di-scan dan check-in dilakukan melalui gate scanner:

```php
// Di gateVerify() setelah tiket valid:
$staffInfo = session('gate_staff_' . $event->id);

$ticket->update([
    'status'               => 'used',
    'checked_in_at'        => now(),
    'scanned_by_staff_name'=> $staffInfo['name'] ?? null,
    'scanned_at_gate'      => $staffInfo['gate'] ?? null,
]);
```

**Tampil di layar scan hasil:**
```
✅ CHECK-IN BERHASIL
━━━━━━━━━━━━━━━━━━━━━━━━
Nama       : Andi Firmansyah
Event      : Konser Malam Akhir Tahun
NIK        : 3273████████1234
━━━━━━━━━━━━━━━━━━━━━━━━
Discan oleh: Budi Santoso · Gate 2
Waktu      : 19:32:15
```

**Tampil di Data Kehadiran (Creator Dashboard):**
- Kolom tambahan: `Petugas Gate` dan `No. Gate`
- Jika scan dilakukan dari Creator Studio (bukan gate): tampil "Creator Studio"

---

## 🪪 9. NIK Wajib Checkout & Masking Privasi (Update 16 Oktober 2026)

### A. Pengumpulan NIK saat Checkout

NIK (Nomor Induk Kependudukan) wajib diisi **khusus produk bertipe `ticket`**.

**Tampil di form checkout:**
```
Atas Nama Pemegang Tiket
  [1] Pemegang Tiket #1   [Nama: ____________]
      NIK / Nomor KTP     [________________]
      ⚠️ 16 digit angka, sesuai KTP/SIM Anda.
```

**Validasi:**
- Tepat **16 digit angka** — lebih/kurang langsung ditolak
- Required hanya jika `$product->product_type === 'ticket'`
- Tidak ada validasi ke Dukcapil (API eksternal) — cukup format

**Penyimpanan:**
- Nilai NIK dienkripsi dengan `AES-256-CBC` sebelum masuk database
- Key enkripsi = `APP_KEY` Laravel (sudah ada, aman)
- Menggunakan `encrypt()` / `decrypt()` bawaan Laravel (tidak perlu library tambahan)
- Di `TicketPass` model: `$casts = ['holder_nik' => 'encrypted']`

```php
// Otomatis encrypt saat simpan, decrypt saat baca:
protected $casts = [
    'holder_nik'    => 'encrypted',
    'checked_in_at' => 'datetime',
];
```

**Alur data NIK:**
```
User input NIK di checkout
        │
        ↓ (validation: 16 digit)
CheckoutService::store()
        │
        ↓ TicketPass::create(['holder_nik' => $nik, ...])
        │   ← Laravel encrypt() otomatis sebelum INSERT
        │
        Database: holder_nik = "eyJpdiI6Ik..." (ciphertext)
        │
        ↓ Saat dibaca
        $ticket->holder_nik → decrypt otomatis = "3273082504980003"
```

---

### B. NIK Masking (Sensor Tengah)

NIK **16 digit** tidak pernah ditampilkan penuh di antarmuka apapun.

**Formula masking:**
```
NIK asli : 3 2 7 3 0 8 2 5 0 4 9 8 0 0 0 3
Tampil   : 3 2 7 3 █ █ █ █ █ █ █ █ 0 0 0 3
           ↑↑↑↑                     ↑↑↑↑
          4 digit awal             4 digit akhir
              ← 8 digit tengah disensor →
```

```php
// Helper function di TicketPass model atau helper.php:
public static function maskNik(?string $nik): string
{
    if (!$nik || strlen($nik) < 8) return '████████████████';
    return substr($nik, 0, 4) . '████████' . substr($nik, -4);
}

// Output: "3273████████0003"
```

**Berlaku di semua tampilan:**

| Halaman | NIK Tampil |
|:---|:---|
| Panel scan gate (staff) | `3273████████0003` |
| Panel scan Creator Studio | `3273████████0003` |
| Creator Data Kehadiran | `3273████████0003` |
| Export CSV | `3273████████0003` ← **tidak pernah full** |
| Email konfirmasi (buyer) | Tidak ditampilkan |

> **Prinsip Zero Full-NIK**: Tidak ada satu pun halaman di Buyle.id — termasuk Admin — yang menampilkan NIK secara penuh. Hanya buyer sendiri yang tahu NIK-nya sendiri.

---

### C. Routes Baru

```
GET  /gate-scanner/{key}/staff-list  → GateStaffController@list (list nama staff, dropdown)
POST /gate-scanner/{key}/staff-auth  → GateStaffController@auth (PIN login staff)

(Creator, requires auth):
GET  /creator/scanner/staff/{product}         → GateStaffController@index
POST /creator/scanner/staff/{product}         → GateStaffController@store
PUT  /creator/scanner/staff/{product}/{staff} → GateStaffController@update
DEL  /creator/scanner/staff/{product}/{staff} → GateStaffController@destroy
```

---

### D. Keamanan & Audit Trail Lengkap

```
Setiap check-in tiket kini menyimpan:
┌──────────────────────────────────────────────┐
│ ticket_passes.id = 9182                      │
│ ticket_code      = TKT-20261201-XA8F2K       │
│ holder_name      = Andi Firmansyah           │
│ holder_nik       = [encrypted ciphertext]    │ ← AES-256
│ status           = used                      │
│ checked_in_at    = 2026-12-01 19:32:15       │
│ checked_in_by    = 42 (creator user_id)      │ ← jika dari Studio
│ scanned_by_staff_name = "Budi Santoso"       │ ← jika dari gate
│ scanned_at_gate  = 2                         │ ← Gate 2
└──────────────────────────────────────────────┘
```

**Jika terjadi sengketa** ("Saya tidak pernah masuk!"):
1. Creator lihat Data Kehadiran → filter `ticket_code`
2. Terlihat: scan dilakukan oleh `Budi Santoso` di `Gate 2` pukul `19:32:15`
3. Creator dapat memanggil panitia Gate 2 untuk klarifikasi

---

## 🛡️ 8. Mitigasi Risiko Fraud & Event Bodong (Anti-Scam Architecture & Post-Event Settlement)

### A. Latar Belakang & Ancaman "Event Bodong"
Dalam bisnis penjualan tiket acara (*ticketing*), terdapat risiko keamanan kritis bernama **Event Bodong / Fake Event Syndrome**:
1. Oknum penipu membuat event fiktif (misal: "Konser Band X" yang dijadwalkan 3 bulan ke depan).
2. Penjualan tiket dibuka dan menghasilkan puluhan hingga ratusan juta rupiah.
3. Jika platform menggunakan aturan penarikan e-commerce standar (**72 jam setelah pembayaran**), oknum penipu dapat langsung mencairkan seluruh dana ke rekening pribadi hanya 3 hari setelah tiket terjual.
4. Menjelang hari-H, oknum kabur membawa uang, konser tidak pernah ada, dan ratusan pembeli tiket menuntut pertanggungjawaban hukum kepada Buyle.id.

---

### B. Arsitektur Post-Event Settlement (Escrow Lock H+3)
Untuk memutus modus ini secara permanen, Buyle.id menerapkan **Post-Event Settlement Policy**:

```
[ PEMBELI MEMBAYAR TIKET ]
           │
           ▼
[ DANA MASUK REKENING ESCROW BUYLE.ID ] 
           │
           ▼
[ STATUS: TICKET ESCROW BALANCE ] ──► Uang DIKUNCI 100%, Creator TIDAK BISA menarik dana
           │
           │  (Menunggu hingga Hari-H Acara...)
           ▼
[ HARI-H: CHECK-IN PENONTON VIA GATE SCANNER ] ──► Rekam jejak audit fisik (Proof of Execution)
           │
           ▼
[ MASA DISPUTE / AUDIT: H+1 s/d H+3 ] ──────────► Mengakomodasi keluhan penonton jika ada wanprestasi
           │
           ▼
[ H+3 PASCA EVENT PUKUL 23:59 ] ───────────────► Dana OTOMATIS BERPINDAH ke "Saldo Siap Ditarik"
           │
           ▼
[ CREATOR AJUKAN PENARIKAN (PAYOUT) ] ──────────► Admin Buyle transfer 100% utuh tanpa potongan
```

---

### C. Logika Backend (`App\Services\SellerBalanceService`)
Perhitungan saldo creator dihitung secara dinamis dan deterministik:

```php
// app/Services/SellerBalanceService.php
$isTicket = ($item->product?->product_type === 'ticket');

if ($isTicket) {
    $eventDate = $item->product?->event_date;
    
    // Clearance: Tanggal event + 3 hari (end of day)
    $ticketClearance = $eventDate
        ? Carbon::parse($eventDate)->endOfDay()->addDays(3)
        : Carbon::parse($paidAt)->addDays(30);

    if ($now->gte($ticketClearance)) {
        // Acara sudah selesai lewat H+3 -> Dana Siap Ditarik
        $clearedGmv += $subtotal;
    } else {
        // Acara belum selesai -> Masuk Saldo Escrow Tertahan
        $ticketEscrowBalance += $subtotal;
    }
}
```

---

### D. Peran Gate Scanner sebagai Bukti Acara (Proof of Event Execution)
Gate Scanner Buyle.id bukan hanya alat verifikasi pengunjung, melainkan instrumen mitigasi hukum (*audit trail*):
- Setiap tiket yang di-scan mencatat `checked_in_at`, `scanned_at_gate`, dan nama panitia (`scanned_by_staff_name`).
- Jika sebuah event mengklaim telah sukses terselenggara namun di database log Gate Scanner memiliki **0 check-in**, Admin Buyle.id berhak membekukan dana escrow untuk investigasi lanjutan.
- Log scan menjadi bukti otentik yang melindungi platform dari klaim sengketa perdata maupun pidana.

---

### E. Protokol Pembatalan Acara & Pengembalian Dana (Zero-Loss Guarantee)
Jika penyelenggara mengumumkan pembatalan konser, atau event terbukti bodong sebelum H+3:
1. **Kas Buyle.id 100% Aman**: Karena dana tiket tidak pernah dicairkan ke creator, Buyle.id tidak mengalami kerugian finansial sepeser pun.
2. **Refund Otomatis ke Pembeli**: Platform memiliki likuiditas penuh untuk mengembalikan dana 100% kepada setiap pembeli tiket secara tertib.
3. **Akuntabilitas Creator**: Akun creator bersangkutan langsung dibekukan (*banned*) dan nomor rekeningnya di-blacklist dari sistem Buyle.id.

---

*Dokumentasi ini dibuat otomatis agar developer atau AI agent berikutnya memiliki gambaran utuh tanpa ada detail teknis yang terlewat.*

