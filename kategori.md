# Panduan Kloning & Re-Deployment Website (CLONE.md)

Dokumen ini adalah panduan teknis resmi bagi **Tim Developer HVM Digital** atau administrator yang ingin menggandakan (kloning) kode sumber (codebase) website ini untuk digunakan pada perusahaan, klien, atau domain yang berbeda.

Website ini menggunakan basis **Laravel 11** dengan sistem konfigurasi dinamis. Dengan mengikuti panduan ini, Anda bisa mengubah identitas website menjadi entitas bisnis baru (skema warna, logo, permetaan, database) secara aman tanpa merusak struktur dasar.

---

## Tahap 1: Persiapan File & Lingkungan (Environment)

### 1. Ekstrak & Pindahkan Codebase
1. Salin seluruh file proyek ini ke dalam folder tujuan di server/lokal Anda.
2. Jika Anda memindahkan ke server (VPS/Shared Hosting), usahakan **TIDAK** menyalin folder `vendor/` dan `node_modules/` untuk menghindari error kompatibilitas OS.
3. Pastikan server memenuhi persyaratan: PHP 8.2+ dan ekstensi GD Library aktif (untuk kompresi WebP).

### 2. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env` (jika belum ada), lalu ubah variabel krusial berikut:
```env
APP_NAME="Nama Perusahaan Klien Baru"
APP_ENV=production       # Gunakan 'local' jika sedang tahap development
APP_KEY=                 # (Biarkan kosong, akan di-generate pada tahap 3)
APP_DEBUG=false          # Wajib 'false' di server produksi demi keamanan
APP_URL=https://domainklien.com

# Pengaturan Database (Ubah sesuai dengan database klien baru)
DB_CONNECTION=mysql      # Atau sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_db_baru
DB_USERNAME=user_db_baru
DB_PASSWORD=password_db_baru
```

---

## Tahap 2: Instalasi & Setup Database

Buka terminal/Command Prompt/SSH di dalam direktori proyek, lalu jalankan perintah instalasi berurutan:

1. **Install Dependensi PHP:**
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

2. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

3. **Migrasi Struktur Tabel:**
   *(Pastikan database yang tertera di `.env` sudah Anda buat sebelumnya).*
   ```bash
   php artisan migrate:fresh
   ```

4. **Isi Data Awal (Seeding Admin & Pengaturan Default):**
   ```bash
   php artisan db:seed
   ```
   *Catatan:* Seeder default biasanya akan membuat akun Admin awal (misal: `admin@admin.com` / `password`). Pastikan Anda memeriksa file `database/seeders/DatabaseSeeder.php` jika butuh kustomisasi login.

---

## Tahap 3: Pemetaan Storage (Wajib untuk Gambar)

Sistem Laravel memerlukan pintasan folder penyimpanan agar file gambar (Logo, Artikel, Galeri) dapat diakses oleh publik.

**Perintah Standar:**
```bash
php artisan storage:link
```

> [!WARNING]
> **PENTING UNTUK PENGGUNA CPanel / HOSTINGER:**
> Jika fungsi PHP `exec()` dinonaktifkan oleh server Anda, perintah di atas akan memunculkan error *Call to undefined function Illuminate\Filesystem\exec()*.
> 
> **Solusi:** Buat symlink secara manual via command line SSH murni:
> ```bash
> cd /path/ke/folder/project
> rm -rf public_html/storage  # (hapus file/link lama yang rusak jika ada)
> ln -s /path/ke/folder/project/storage/app/public /path/ke/folder/project/public_html/storage
> ```

---

## Tahap 4: Mengubah Identitas (Skema & SEO Dasar)

Berkat fitur *Dynamic Settings*, Anda tidak perlu mengubah banyak *hard-code* HTML. Hampir semua skema warna, teks berjalan, CTA, permetaan (SEO), hingga Logo dapat diubah melalui Dashboard Admin.

1. **Login ke Panel Admin** menggunakan akun default.
2. Buka menu **Pengaturan Website**.
3. **Tab Identitas Dasar:**
   - Ubah Logo dan Favicon perusahaan yang baru.
   - Perbarui Email, Alamat, dan NPWP/NIB perusahaan klien.
4. **Tab Tampilan (Warna & Skema):**
   - Atur **Warna Utama (Base)** dan **Warna Aksen**. Seluruh tombol, link, hover, dan *glow effect* website akan otomatis mengikuti perpaduan warna baru ini.
   - Ubah **Background Hero Breadcrumb** sesuai dengan stok gambar perusahaan klien.
5. **Tab Sosial & Navigasi:**
   - Perbarui tautan Sosial Media dan *Running Text* di bar atas.
6. **Menu SEO Global:**
   - Masukkan *Meta Title*, *Meta Description*, dan *Open Graph Image* bawaan yang mewakili perusahaan klien baru.

---

## Tahap 5: Perubahan Hardcode (Jika Diperlukan)

Terdapat sebagian kecil teks yang mungkin sifatnya struktural dan harus diedit secara langsung (hardcoded) demi performa / struktur bisnis model, antara lain:

1. **Footer Copyright Name:**
   Buka file `resources/views/components/footer.blade.php`.
   Cari baris kode: `&copy; {{ date('Y') }} <span>CV. Karya Perdana Teknik.</span>` dan ubah menjadi nama klien.

2. **Daftar Hari Buka / Jam Operasional (Jika Berbeda):**
   Berada di file `resources/views/components/footer.blade.php` (Bagian Jam Buka / *Opening Hours*).

3. **Struktur Kategori / Label di Halaman:**
   Jika layanan klien baru sangat jauh berbeda (bukan lagi industri Teknik / Hoist / Crane), periksa kalimat statis di `resources/views/home/index.blade.php` atau `resources/views/services/index.blade.php`.

---

## Tahap 6: Finalisasi & Pembersihan

Setelah seluruh proses selesai dan website berjalan dengan sempurna di domain baru, wajib lakukan pembersihan cache server agar Laravel dapat berjalan dengan performa maksimal (Optimalisasi Production).

Jalankan perintah ini:
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

---

## Tahap 7: Fitur-Fitur Lanjutan & Dokumentasi

Website ini dilengkapi dengan berbagai fitur *custom* canggih yang dirancang khusus untuk meningkatkan performa marketing dan pengelolaan admin. Berikut adalah panduannya:

### 1. Manajemen Leads & Tracking (UTM)
- Semua data yang masuk melalui Form Kontak dan **Tombol WhatsApp Floating** akan terekam di menu **Leads**.
- **UTM Tracking:** Sistem secara otomatis mendeteksi parameter UTM dari URL (misal: `domain.com/?utm_source=ig&utm_medium=story&utm_campaign=promo`). Parameter ini disimpan di sesi dan akan muncul di detail Lead Admin. Ini sangat berguna jika Anda beriklan di Google Ads, Facebook Ads, atau TikTok.
- **Status & Catatan:** Admin dapat mengubah status Lead (Baru, Dihubungi, Selesai) dan menambahkan catatan internal untuk proses *follow up*.
- **Export:** Data Leads dapat diekspor ke file PDF untuk keperluan pelaporan tim *Sales*.

### 2. Tombol WhatsApp Dinamis (Floating & Inline)
- Terdapat tombol WhatsApp mengambang (Floating Button) yang otomatis muncul di seluruh halaman.
- Admin dapat mengganti nomor WhatsApp utama dari menu **Pengaturan Website** -> **WhatsApp**.
- Admin dapat mengatur jam operasional (*Buka/Tutup*). Jika diatur tutup/offline, maka floating button akan berubah status menjadi offline namun pesan tetap bisa masuk ke database Leads.
- Pesan otomatis diformat dengan mencantumkan nama, perusahaan, dan halaman asal (*page url*) sehingga tim CS dapat langsung mengidentifikasi kebutuhan pelanggan.

### 3. SEO & Structured Data (JSON-LD)
- Website menggunakan arsitektur SEO tingkat lanjut dengan *Structured Data* untuk Product (Layanan) dan Article.
- Secara otomatis *schema.org* JSON-LD akan merender informasi harga, rating, *return policy*, dan ketersediaan, yang membantu Google memunculkan cuplikan kaya (Rich Snippets).
- Halaman produk dilengkapi **FAQ Schema** (JSON-LD FAQPage) untuk meningkatkan kemungkinan muncul di fitur FAQ Google.

### 4. Custom Pagination & Tampilan Dinamis
- Jika Anda mengubah warna "Base Color" di pengaturan Admin (misalnya menjadi kuning `#F5A623`), tidak hanya tombol dan *border* yang berubah, tetapi navigasi paginasi (Pagination) dan efek *hover* juga akan secara otomatis menyesuaikan warna baru tersebut.

---

## Tahap 8: Menghubungkan ke Git & GitHub

Melakukan version control dengan Git sangat disarankan agar setiap perubahan kode bisa dilacak dan di-*rollback* jika terjadi kesalahan.

### Setup Pertama Kali

```bash
# 1. Konfigurasi identitas (sekali saja)
git config --global user.name "Nama Anda"
git config --global user.email "email@anda.com"

# 2. Staging semua file
git add .

# 3. Commit pertama
git commit -m "feat: initial commit - HVM Digital website"

# 4. Hubungkan ke repository GitHub
git branch -M main
git remote add origin https://github.com/username/nama-repo.git
git push -u origin main
```

### Push Update Selanjutnya

```bash
git add .
git commit -m "fix: deskripsi perubahan"
git push
```

> **Catatan Keamanan:** File `.env` sudah ada di `.gitignore` sehingga konfigurasi database dan secret key **TIDAK** akan ikut terupload ke GitHub.

---

## Tahap 9: Deploy ke Hostinger (Shared Hosting)

Metode yang digunakan adalah **pemisahan core Laravel dan public_html** untuk keamanan maksimal.

### Struktur Folder di Server
```
home/
├── core-web/          ← Semua file Laravel (kecuali /public)
│   ├── app/
│   ├── bootstrap/
│   ├── vendor/
│   ├── .env           ← File konfigurasi (AMAN, tidak bisa diakses publik)
│   └── ...
└── public_html/       ← Hanya isi dari folder /public
    ├── index.php
    ├── .htaccess
    └── storage → (symlink ke core-web/storage/app/public)
```

### Langkah Deploy

1. **Upload** semua file (kecuali `node_modules`) ke folder `core-web` via File Manager.
2. **Pindahkan** semua isi folder `core-web/public/` ke `public_html/`.
3. **Edit** `public_html/index.php`, ubah path:
   ```php
   // Sebelum:
   require __DIR__.'/../vendor/autoload.php';
   // Sesudah:
   require __DIR__.'/../core-web/vendor/autoload.php';

   // Sebelum:
   $app = require_once __DIR__.'/../bootstrap/app.php';
   // Sesudah:
   $app = require_once __DIR__.'/../core-web/bootstrap/app.php';
   ```
4. **Buat database** di hPanel → MySQL Databases, lalu update `.env` di `core-web`.
5. **Buat symlink storage** dengan mengunjungi URL `/buat-symlink` (buat route sementara).

---

## Changelog Pembaruan UI/UX

Berikut adalah daftar perubahan desain yang telah dilakukan:

| Area | Perubahan |
|---|---|
| **Admin Gallery Create/Edit** | Rombak total ke card layout premium (white card, blue icon, field focus blue) |
| **Notifikasi Admin** | Redesign menjadi "Pusat Notifikasi" — clean minimalist, tab Hari Ini/Minggu Ini/Sebelumnya yang bisa diklik |
| **Loading Login/Logout** | Rombak dari dark mode menjadi light mode premium dengan animasi bounce logo biru |
| **Footer Publik** | Warna seragam dengan breadcrumb (#F8FAFC), hapus warna merah, ikon hover biru |
| **Halaman Produk (Mobile)** | Fix horizontal overflow — tambah `min-width:0` pada grid column + `overflow:hidden` |
| **Hero Homepage (Mobile)** | Fix konten mepet ke header — tambah `padding-top` responsif di breakpoint 768px/480px |
| **FAQ Section** | Dipindah ke bawah Spesifikasi dengan styling tabel identik + JSON-LD schema |
| **Footer Watermark** | Link hvmdigital.id diarahkan ke halaman jasa website |
| **SEO Dashboard Admin** | Teks persuasif "Tampil teratas saat buyer cari produk = peluang deal lebih besar" |

---

### Selesai! 🎉
Website baru telah berhasil diluncurkan dengan fitur *lead tracking* dan SEO optimal tanpa jejak dari nama perusahaan yang lama.

