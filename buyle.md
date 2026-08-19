# Dokumentasi Resmi Proyek: buyle.id

> **Tagline:** The Multi-Creator Marketplace  
> **Situs Utama:** https://buyle.id  
> **Pengembang & Arsitek:** HVM Digital Engineering  

---

## 1. Ringkasan Eksekutif (Executive Summary)

**buyle.id** adalah platform *Multi-Creator Commerce & Digital Product Marketplace* yang dirancang untuk memfasilitasi kreator konten, pemilik produk digital, dan edukator dalam menjual aset digital mereka secara langsung kepada audiens dalam satu link/toko terintegrasi. 

Berbeda dari layanan *link-in-bio* atau e-commerce konvensional yang berfokus pada barang fisik, **buyle.id** berfokus penuh pada **ekosistem produk digital (Digital Goods & Intellectual Property)** dengan alur transaksi instan, *zero-friction checkout*, serta arsitektur *lightweight* yang berfokus pada keamanan tautan (*link-only architecture*).

---

## 2. Definisi & Arsitektur Produk (Link-Only)

**buyle.id** mengadopsi arsitektur **Link-Only Delivery** untuk memastikan kecepatan, keamanan, dan efisiensi ruang penyimpanan. Platform tidak menyimpan file secara langsung (mengeliminasi risiko limit *bandwidth* atau penyimpanan penuh), melainkan bertindak sebagai jembatan transaksi (*transactional bridge*) yang aman menuju aset kreator.

### Kategori Produk Digital Utama:
1. **Cloud Hosted Files (via External Links):**
   * E-Book, Template Dokumen, Sheet/Excel Framework, Preset (Lightroom/LUTs), Source Code, Modul PDF, ZIP/RAR yang di-host di Google Drive, Dropbox, atau OneDrive.
2. **Access Links & Interactive Workspaces:**
   * Canva Template Link, Notion Workspace, Figma Assets, Miro Boards.
3. **Exclusive Contents & Communities:**
   * Loom Video Course, YouTube Unlisted Course, Link Grup Telegram/Discord Eksklusif.

---

## 3. Fitur Utama & Keunggulan Platform

### A. Untuk Pembeli (Buyer Experience)
* **Zero-Friction Checkout:** Pembeli cukup menginput Nama, Email, dan Nomor WhatsApp tanpa perlu registrasi manual yang rumit di awal.
* **Auto-Account Generation & Magic Login:** Akun pembeli dibuatkan otomatis oleh sistem pasca pembayaran. Pembeli bisa masuk ke dashboard dengan sistem *1-Click Magic Login* via email tanpa perlu menghafal *password*.
* **Instant Digital Delivery:** Akses link produk langsung terbuka secara otomatis di layar sukses checkout dan dikirimkan via email/WhatsApp.
* **Centralized Digital Library:** Seluruh riwayat pembelian dan tautan unduhan tersimpan rapi di dashboard pembeli (*My Library*) selamanya.

### B. Untuk Kreator (Seller / Creator Experience)
* **Custom Creator Store:** Kreator mendapatkan subdomain/link toko khusus yang menampilkan katalog produk mereka secara bersih dan responsif (contoh: `buyle.id/c/namakreator`).
* **Zero-Bandwidth Worry:** Kreator cukup menempelkan link Google Drive, Notion, atau Canva mereka. Tidak ada batasan ukuran file yang diunggah ke platform buyle.id.
* **Automated Sales Analytics:** Laporan penjualan, pendapatan bersih (GMV - Platform Fee), dan trafik produk diperbarui secara *real-time*.
* **Simple Payout Engine:** Sistem pengajuan penarikan saldo pendapatan (*withdraw request*) langsung ke rekening bank atau e-wallet lokal.

### C. Keamanan & Pengelola Platform (Super Admin)
* **Real-time URL Security Engine:** Validasi tautan otomatis dengan AJAX. Sistem memiliki:
  * **Whitelist:** Hanya mengizinkan platform terpercaya (Google Drive, Canva, Notion, dll).
  * **Blacklist:** Memblokir URL *shortener* (bit.ly, dll) dan situs file sharing yang rentan *malware* (MediaFire, dll).
  * **Anti-Phishing Detection:** Menganalisis pola URL (seperti keberadaan kata sandi atau *login*) untuk mencegah tautan berbahaya.
* **Centralized Analytics:** Pemantauan Total Gross Merchandise Value (GMV), pendapatan biaya platform, dan aktivitas pengguna.
* **Payout Approval:** Kontrol penuh atas pemrosesan pencairan dana kepada para kreator.

---

## 4. Model Monetisasi (Monetization Strategy)

Sistem bisnis **buyle.id** mengadopsi model pendapatan *hybrid* yang berkelanjutan dan terukur:

| Sumber Pendapatan | Mekanisme & Skema Biaya | Proyeksi Kontribusi |
| :--- | :--- | :--- |
| **Transaction Fee (% Per Sale)** | Pemotongan **5% - 10%** (dinamis) dari setiap transaksi penjualan produk digital yang berhasil di platform. | **70% (Sumber Utama)** |
| **Payment Gateway Admin Fee** | Biaya penanganan transaksi (*flat fee*) senilai Rp 2.000 - Rp 4.500 per checkout (diteruskan/dibagi dengan *PG provider*). | **15%** |
| **Subscription Plan (Pro Creator)** | Fitur berlangganan bulanan untuk potongan *take-rate* lebih kecil (0%), analitik lanjutan, dan fitur email *broadcast* ke pembeli mereka. | **10%** |
| **Payout Withdrawal Fee** | Biaya administrasi pencairan dana dari saldo kreator ke rekening bank sebesar Rp 5.000 per penarikan. | **5%** |

---

## 5. Arsitektur Teknis & Optimasi Performa

Untuk memastikan platform tetap dapat menampung ribuan produk dan transaksi tanpa *lag* di shared hosting/VPS terjangkau (Hostinger):

* **Core Framework:** Laravel (PHP 11.x) dengan pendekatan RBAC (*Role-Based Access Control*).
* **Database Optimization:**
  * Penerapan *Database Indexing* pada kolom tinggi *query* (`user_id`, `seller_id`, `orders.status`).
  * *Simple Pagination* untuk memuat katalog masif tanpa membebani memori CPU/RAM.
* **Asynchronous Process (Queues):**
  * Proses pembuatan akun otomatis dan pengiriman email transaksional dikelola oleh *Laravel Background Queues* agar respon webhook pembayaran dari *Payment Gateway* berdurasi < 1 detik.
* **Link Security Layer:**
  * Penggunaan form request validasi khusus (`SafeDigitalUrl`) dan service injeksi untuk memastikan *sanitization* pada *output* link.
* **Integrasi Pihak Ketiga (Third-Party Services):**
  * **Payment Gateway:** Midtrans / Xendit (API QRIS, Virtual Account, E-Wallet).
  * **Transactional Email:** Resend API (SMTP Engine).

---

## 6. Proyeksi Pertumbuhan & Peta Jalan (Roadmap)

### Fase 1: MVP & Core Commerce (Bulan 1 - 2)
* Peluncuran fitur dasar: Zero-Friction Checkout, Auto-Account (Magic Login), Input Link Produk, Validator URL Keamanan, Midtrans Payment Gateway.
* Dashboard Kreator: Laporan penjualan sederhana dan fitur pengajuan penarikan dana (*Withdrawal*).
* *Internal Validation* & Onboarding 20 Kreator Perdana.
* Target GMV: Rp 10.000.000 / bulan.

### Fase 2: Scale-Up & Creator Tooling (Bulan 3 - 6)
* Pembukaan pendaftaran publik untuk kreator umum.
* Fitur *Bundling Product* & *Discount Voucher* untuk Seller.
* **Email Broadcast System:** Memungkinkan kreator mengirimkan email *marketing* langsung ke pembeli lama mereka melalui dashboard.
* Target Katalog: **3.000+ Produk Digital**.
* Target GMV: Rp 100.000.000 / bulan.

### Fase 3: Ecosystem & Affiliate Growth (Bulan 7 - 12)
* Peluncuran *Pro Creator Subscription*.
* **Affiliate Network Engine:** Sistem di mana pembeli bisa langsung mendaftar sebagai *affiliate* (afiliator) untuk mempromosikan produk kreator lain, dengan perhitungan bagi hasil komisi secara otomatis di level sistem.
* Mobile-App PWA (*Progressive Web App*) untuk pengalaman *native* di *smartphone*.

---

## 7. Penutup & Hak Cipta

**buyle.id** dikembangkan secara mandiri di bawah naungan **HVM Digital Engineering** sebagai wujud komitmen dalam menyediakan infrastruktur ekonomi digital yang mandiri, aman, efisien, dan berorientasi pada kemudahan pengguna (baik kreator maupun pembeli) di Indonesia.

*Dokumen ini merupakan panduan teknis dan operasional internal platform. Terakhir diperbarui: Agustus 2026.*