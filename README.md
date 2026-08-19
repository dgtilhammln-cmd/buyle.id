# 🛒 buyle.id — E-Commerce & Platform Bisnis
**PT. Hiranatha Makmur Sukses** | [buyle.id](https://buyle.id)

Platform e-commerce dan website bisnis komprehensif untuk buyle.id. Dibangun menggunakan Laravel 13, dioptimalkan untuk performa tinggi, pengalaman berbelanja pengguna (UI/UX modern), SEO, dan kemudahan manajemen melalui panel admin yang lengkap.

---

## 🛠 Tech Stack

| Komponen | Detail |
|---|---|
| **Framework** | Laravel 13.x (PHP 8.3+) |
| **Database** | MySQL (production) / SQLite (development) |
| **Template Engine** | Blade (Laravel) |
| **CSS & UI** | Vanilla CSS + Design System Khusus (Interaktif, Grid/List view) |
| **Build Tool** | Vite |
| **Image Processing** | Intervention Image 4.0 (Otomatis konversi ke WebP) |
| **PDF Export** | barryvdh/laravel-dompdf 3.0 |
| **Excel Export** | maatwebsite/excel 3.1 |
| **Sitemap** | spatie/laravel-sitemap 8.0 |

---

## 🎨 Design System & Frontend

- **Primary Font:** `Montserrat` (Google Fonts) diimplementasikan secara global.
- **Tampilan Interaktif:** Dilengkapi micro-interactions, efek hover, popup lightbox, dan animasi scroll menggunakan AOS.
- **Layout Fleksibel:** Pengguna dapat beralih antara tampilan **Grid** dan **List** pada katalog produk, artikel, dan halaman admin, yang preferensinya disimpan di memori browser (localStorage).
- **Performa Tinggi:** Aset CSS dan JS eksternal (Swiper, GLightbox) dimuat secara *deferred* dan *non-blocking* untuk mempercepat *page load*.

---

## 🌐 Fitur Frontend (Publik)

### 1. Halaman Utama (Homepage)
- **Hero Slider:** Banner promosi utama dengan slide otomatis.
- **Flash Sale & Special Promo:** Bagian promosi dinamis dengan penghitung waktu mundur (countdown timer) dan harga coret (diskon).
- **Kategori Produk (USP):** Navigasi cepat berbasis kategori.
- **Katalog Produk & Jasa:** Menampilkan item unggulan dengan label kategori, harga diskon, rating, dan tombol "Tambah ke Keranjang".
- **Testimoni & Klien:** Slider ulasan pelanggan dan logo mitra.
- **Galeri & Artikel:** Cuplikan proyek/foto terbaru dan berita/blog terkini.

### 2. Fitur E-Commerce (Belanja)
- **Keranjang Belanja (Cart):** Pengguna dapat menambah, mengubah kuantitas, dan menghapus item. Tersimpan dalam sesi browser.
- **Checkout Pemesanan:** Pengisian alamat pengiriman dan pilihan kurir/metode.
- **Sistem Checkout ke WhatsApp:** Pesanan diformat secara otomatis menjadi pesan WhatsApp untuk kemudahan komunikasi dan konfirmasi pembayaran.
- **Tombol WhatsApp Floating:** Navigasi cepat untuk menghubungi CS.

### 3. Halaman Lainnya
- **Katalog Lengkap (`/products`):** Grid/List view produk dengan fitur pencarian dan filter kategori. Tersedia fitur pengurutan (sorting).
- **Detail Produk:** Gambar resolusi tinggi, spesifikasi, harga dinamis, opsi varian (jika ada), dan bagian FAQ khusus produk.
- **Artikel / Blog (`/articles`):** Daftar bacaan informatif untuk SEO content marketing.
- **Galeri (`/gallery`):** Dokumentasi foto menggunakan *Lightbox popup*.
- **Tentang Kami & Kontak:** Profil perusahaan, integrasi Google Maps, dan form hubungi kami.

---

## 🔒 Fitur Panel Admin (`/admin`)

Panel admin dilindungi oleh sistem autentikasi dan menyediakan manajemen data menyeluruh:

### E-Commerce & Promosi
1. **Manajemen Pesanan (`/admin/orders`):**
   - Melacak pesanan masuk, memproses status (Pending, Diproses, Dikirim, Selesai).
   - Tampilan pesanan mendukung **List View (Tabel)** dan **Grid View (Kartu)**.
   - Deteksi *Abandoned Cart* untuk mem-follow up calon pembeli via WA.
   - **Download Laporan:** Export data pesanan ke Excel, CSV, atau PDF dengan filter tanggal.
2. **Kategori Produk (`/admin/category-items`):** CRUD kategori untuk navigasi e-commerce.
3. **Manajemen Produk & Jasa (`/admin/services`):**
   - CRUD Produk/Layanan.
   - Mendukung Harga Normal, Harga Coret (Diskon), dan Manajemen Stok Cepat (*Inline Stock Edit*).
   - Tampilan katalog admin mendukung Grid/List view interaktif.
4. **Promo & Flash Sale (`/admin/promo-sections`):**
   - Membuat sesi diskon tematik atau Flash Sale dengan timer.
   - Opsi pemilihan produk: Manual (pilih satu-satu), Kategori Tertentu, Produk Diskon (Otomatis filter harga coret), atau Semua Produk.

### Manajemen Konten (CMS)
5. **Manajemen Artikel (`/admin/articles`):** Buat berita/blog dengan format Rich Text, optimasi SEO meta, dan tag. (Mendukung Grid/List view).
6. **Hero Slides (`/admin/hero-slides`):** Mengganti banner besar di Homepage.
7. **Manajemen Galeri (`/admin/gallery`):** Upload portofolio foto (otomatis jadi WebP).
8. **Klien & Testimoni:** Kelola logo klien berjalan dan ulasan teks/bintang.

### Analitik & Pengaturan Sistem
9. **Dashboard Analytics (`/admin/analytics`):**
   - Data trafik internal: Kunjungan halaman, tipe perangkat (Mobile/Desktop), dan sumber rujukan (*Referrer*).
   - Grafik 30 hari terakhir.
10. **Pengaturan Website (`/admin/settings`):**
    - Identitas (Logo, Nama Web, Alamat, Kontak).
    - SEO Global & Custom Script (Google Tag Manager, Facebook Pixel).
11. **Pengaturan WhatsApp (`/admin/wa-settings`):** Manajemen multi-nomor CS dan format template otomatis.
12. **Leads (`/admin/leads`):** Data inquiry yang masuk via form website (non-cart).

---

## ⚡ Optimalisasi Teknis

- **Otomatisasi WebP:** Setiap gambar yang diunggah via Admin otomatis dikonversi dan dikompresi ke format `.webp` untuk menghemat bandwidth.
- **Struktur SEO:** Dilengkapi dengan JSON-LD Schema Generator (`LocalBusiness`, `Product`, `Article`, `FAQPage`), meta tag OpenGraph, dan geo-tags.
- **Sitemap & Robots:** `/sitemap.xml` dibuat otomatis menyesuaikan konten produk dan artikel terbaru.
- **Keamanan:** Proteksi terhadap CSRF pada semua form, sanitasi input untuk XSS, dan validasi sisi server.

---

## 📂 Panduan Deployment

### Syarat Server Minimum
- PHP >= 8.3
- MySQL >= 8.0 / MariaDB >= 10.3
- Composer & NPM (Untuk build assets jika diperlukan)
- Ekstensi PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD/ImageMagick.

### Langkah Instalasi Lokal
```bash
# 1. Clone repositori
git clone [repository_url] buyle.id-ecommerce
cd buyle.id-ecommerce

# 2. Install dependensi
composer install
npm install

# 3. Pengaturan .env
cp .env.example .env
php artisan key:generate

# 4. Migrasi Database dan Seeder
php artisan migrate --seed

# 5. Link Storage (Penting untuk gambar)
php artisan storage:link

# 6. Build assets & jalankan server
npm run build
php artisan serve
```

### Tips Server Hosting (cPanel/Hostinger)
- Pastikan Document Root mengarah ke folder `/public`.
- Jalankan `php artisan storage:link` (atau gunakan symlink manual di File Manager).
- Gunakan perintah `php artisan optimize:clear` saat memperbarui file di server untuk menghapus cache konfigurasi dan view.

---
*Dikembangkan secara khusus untuk buyle.id*
