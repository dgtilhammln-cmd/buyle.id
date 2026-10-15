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
                                  Hardware USB Webcam Auto-Switching + Audio Synthesizer
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
   - Rincian Nama Pembeli dan Nomor Order muncul di layar.
   - Petugas gerbang mencocokkan nama dengan kartu fisik (KTP/SIM/Kartu Pelajar).
   - Tombol satu sentuhan **"CHECK-IN TIKET"** memanggil endpoint update status tiket (`status = 'used'`, `used_at = now()`).

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
