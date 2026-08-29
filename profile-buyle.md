# BuyLe.id — AI Agent Knowledge Base & System Context

## 1. Ikhtisar Platform (Overview)
* **Nama Platform:** BuyLe.id
* **Tipe Platform:** Digital Creator Marketplace & Monetization Suite.
* **Target Pengguna:** Kreator konten, desainer, penulis, pengembang/developer aplikasi/CBT, penyedia jasa digital, UMKM, dan ibu rumah tangga.
* **Visi & Misi:** Memperkuat ekosistem digital Indonesia dengan menyediakan platform tempat menjual produk digital, aset kreatif, dan layanan jasa secara instan, aman, dan tanpa biaya infrastruktur yang membebankan kreator.

---

## 2. Unique Selling Points (USP) & Fitur Utama

### A. Auto-Affiliate System (Pasukan Sales Bawaan)
* **Konsep:** Setiap produk digital yang diunggah kreator memiliki fitur komisi afiliasi terintegrasi.
* **Skema Komisi:** Kreator menentukan komisi sebesar 1% - 10% (Rekomendasi ideal dari sistem: 10%).
* **Mekanisme:** Pengguna lain/affiliate bisa membagikan link produk untuk mendapatkan komisi secara otomatis setelah transaksi berhasil.

### B. Mode Apresiasi (Pay What You Want + Minimum Price)
* **Konsep:** Kreator menetapkan harga minimal (misal: Rp10.000). Pembeli dapat membayar sesuai harga minimal atau memberikan nominal lebih tinggi sebagai bentuk apresiasi atas karya tersebut.

### C. Pemisahan Dua Jalur Halaman Kreator
1. **Store Catalog (`buyle.id/c/namakreator`):**
   * Fokus pada tampilan e-commerce & katalog produk/jasa.
   * Dioptimalkan penuh untuk SEO (Search Engine Optimization) & konversi penjualan.
2. **Link-in-Bio / Mikrosite (`buyle.id/p/namakreator`):**
   * Tampilan estetik yang fokus pada *personal branding* & portofolio (Rate card PDF, tombol sosmed, slot gambar promo).
   * **Fitur Cerdas Katalog:** Kreator/Influencer bisa memilih menampilkan produk milik sendiri **atau** langsung memasukkan produk *affiliate* dari marketplace BuyLe tanpa perlu upload ulang.

---

## 3. Alur Transaksi, Akses Produk, & Pengalaman Pengguna (UX)

### A. Autentikasi & Checkout Pembeli
* **Google One-Tap Login:** Pembeli cukup checkout menggunakan akun Google (tanpa registrasi form manual yang rumit).
* **Direct Access:** Akun pembeli otomatis terbuat secara *silent registration* di latar belakang.

### B. Penyimpanan & Pengiriman File (Zero-Host Cost Strategy)
* **Tanpa Upload Direct File Jumbo:** Untuk efisiensi server/hosting (fase awal), BuyLe.id **tidak menampung file fisik jumbo** (PDF/Zip) secara langsung di server.
* **Mekanisme Link:** Penjual menginputkan URL penyimpanan eksternal (Google Drive, Terabox, Dropbox, WA Link untuk jasa, dll.).
* **Halaman Pasca-Bayar & Dashboard:** Setelah pembayaran lunas, link akses produk ditampilkan di halaman pop-up sukses dan **tersimpan selamanya di Dashboard Pembeli** (berfungsi sebagai "Brankas Digital" riwayat transaksi).

---

## 4. Sistem Komunikasi & Notifikasi WhatsApp (Fonnte Integration)

### A. Fitur Chat / Inbox Internal
* **Tidak Menggunakan Short-Polling Berlebihan:** Sistem *inbox* tidak melakukan *auto-refresh* agresif (menghindari beban CPU server/Hostinger).
* **Strategi Polling:** Interval refresh disetel setiap **60 detik** (dilengkapi tombol *Manual Refresh*).
* **Smart Inactive Tab:** Polling otomatis **berhenti total** jika tab browser dalam posisi *background/inactive*.

### B. WhatsApp Notification Engine (Fonnte API)
* **Fungsi Utama:** Memberikan notifikasi WA *satu arah* saat:
  1. Pembayaran produk/jasa berhasil (*Order Success*).
  2. Kreator menerima pesan baru pertama dari calon pembeli.
* **Trik Hemat Kuota:** Pesan WA hanya berfungsi sebagai trigger notifikasi ("Ada pesan masuk di BuyLe.id"). Balasan pesan dilakukan sepenuhnya di dalam *Inbox Dashboard* platform.

---

## 5. Kategori Produk & Jargon Bahasa (Brand Voice)

### Karakteristik Bahasa
* Menggunakan bahasa yang **ramah, santai, solutif, dan komunikatif** (menghindari istilah koding/teknis yang kaku).

### Struktur Kategori Utama (Head Categories)
1. **Template & Desain Siap Pakai** (Feed IG, Canva, Undangan Digital, PPT, Excel Pembukuan).
2. **Panduan, E-Book & Kelas Ringkas** (Guide Olshop, Resep Masakan, Tutorial Freelance).
3. **Alat Bantu & Bahan Foto/Video** (Preset Lightroom, Filter VN/CapCut, Mentahan Video/Sound).
4. **Jasa & Layanan Titip** (Jasa Logo, Edit Video, Copywriting, Konsultasi 1-on-1).
5. **Website, Aplikasi & File Kerja Siap Pakai** (Ganti dari kata *Developer Tools/Code*; berisi Source Code CBT, Kasir Web, Landing Page).
6. **Produk Hiburan & Komunitas** (Akses WA/Telegram VIP, Voucher Game).

---

## 6. Batasan & Regulasi Internal AI (AI Guardrails)
* **Jasa / Nego Harga:** Jika pembeli menanyakan masalah jasa yang harganya bervariasi, arahkan pembeli untuk berdiskusi via *Inbox BuyLe* terlebih dahulu sebelum penjual membuatkan paket checkout.
* **Jangan Salah Memahami Keamanan Asset:** BuyLe.id saat ini mengandalkan reputasi penjual & batasan akses dashboard. Fitur *Dynamic Watermarking PDF* atau *Serial Key Generator* diproyeksikan sebagai fitur eksklusif untuk **Membership Berbayar (Ke Depan)**, bukan fitur gratisan awal.