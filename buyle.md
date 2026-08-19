# Dokumentasi Resmi Proyek: buyle.id

> **Tagline:** The Multi-Creator Marketplace  
> **Situs Utama:** https://buyle.id  
> **Pengembang & Arsitek:** HVM Digital Engineering  

---

## 1. Ringkasan Eksekutif (Executive Summary)

**buyle.id** adalah platform *Multi-Creator Commerce & Digital Product Marketplace* yang dirancang untuk memfasilitasi kreator konten, pemilik produk digital, dan edukator dalam menjual aset digital mereka secara langsung kepada audiens dalam satu link/toko terintegrasi. 

Berbeda dari layanan *link-in-bio* atau e-commerce konvensional yang berfokus pada barang fisik, **buyle.id** berfokus penuh pada **ekosistem produk digital (Digital Goods & Intellectual Property)** dengan alur transaksi instan, *zero-friction checkout*, serta arsitektur *lightweight* yang aman dan efisien.

---

## 2. Definisi & Kategori Produk

**buyle.id** mengakomodasi berbagai format produk digital tanpa kerumitan pengiriman fisik (*zero shipping logistics*).

### Kategori Produk Digital Utama:
1. **Digital Files (Direct Download):**
   * E-Book, Template Dokumen, Sheet/Excel Framework, Preset (Lightroom/LUTs), Aset Desain (UI/UX, Vector, 3D Assets), Source Code, Modul PDF, ZIP/RAR.
2. **External Resource / Access Links:**
   * Akses Google Drive, Canva Template Link, Notion Workspace, Loom Video Course, Link Grup Telegram/Discord Eksklusif.
3. **Services & Micro-Consultation (Future Roadmap):**
   * Sesi konsultasi 1-on-1, Audit Portofolio, Jasa Desain/Kustomisasi Cepat.

---

## 3. Fitur Utama & Keunggulan Platform

### A. Untuk Pembeli (Buyer Experience)
* **Zero-Friction Checkout:** Pembeli cukup menginput Nama, Email, dan Nomor WhatsApp tanpa perlu registrasi manual yang rumit di awal.
* **Auto-Account Generation:** Akun portal pembeli (`/my-library`) dibuatkan secara otomatis oleh sistem saat transaksi pertama kali berhasil.
* **Instant Digital Delivery:** Akses file atau link produk langsung terbuka secara otomatis di layar sukses checkout dan dikirimkan via email/WhatsApp.
* **Centralized Digital Library:** Seluruh riwayat pembelian dan tautan unduhan tersimpan rapi di dashboard pembeli selamanya.

### B. Untuk Kreator (Seller / Creator Experience)
* **Custom Creator Store:** Kreator mendapatkan subdomain/link toko khusus yang menampilkan katalog produk mereka secara bersih dan responsif.
* **Multi-Format Support:** Mendukung pengunggahan file langsung (*Cloud Storage*) maupun pengalihan link *third-party*.
* **Automated Sales Analytics:** Laporan penjualan, pendapatan bersih, dan trafik produk diperbarui secara *real-time*.
* **Simple Payout Engine:** Sistem penarikan saldo pendapatan (*withdraw*) langsung ke rekening bank atau e-wallet lokal.

### C. Untuk Pengelola Platform (Super Admin - HVM)
* **Centralized Analytics:** Pemantauan Total Gross Merchandise Value (GMV), pendapatan biaya platform, dan aktivitas pengguna.
* **Seller Verification System:** Sistem persetujuan dan moderasi produk untuk menjaga legalitas dan kualitas produk digital di dalam platform.
* **Payout Approval:** Kontrol penuh atas pemrosesan pencairan dana kepada para kreator.

---

## 4. Model Monetisasi (Monetization Strategy)

Sistem bisnis **buyle.id** mengadopsi model pendapatan *hybrid* yang berkelanjutan dan terukur:

| Sumber Pendapatan | Mekanisme & Skema Biaya | Proyeksi Kontribusi |
| :--- | :--- | :--- |
| **Transaction Fee (% Per Sale)** | Pemotongan **5% - 8%** dari setiap transaksi penjualan produk digital yang berhasil di platform. | **70% (Sumber Utama)** |
| **Payment Gateway Admin Fee** | Biaya penanganan transaksi (*flat fee*) senilai Rp 2.000 - Rp 4.500 per checkout (diteruskan/dibagi dengan *PG provider*). | **15%** |
| **Subscription Plan (Pro Creator)** | Fitur berlangganan bulanan (misal: Rp 99.000/bulan) untuk potongan *take-rate* lebih kecil (0%), fitur kustom domain, dan analitik lanjutan. | **10%** |
| **Payout Withdrawal Fee** | Biaya administrasi pencairan dana dari saldo kreator ke rekening bank sebesar Rp 5.000 per penarikan. | **5%** |

---

## 5. Arsitektur Teknis & Optimasi Performa

Untuk memastikan platform tetap dapat menampung ribuan produk dan transaksi tanpa *lag* di infrastruktur terjangkau:

* **Core Framework:** Laravel (PHP 8.x) dengan pendekatan RBAC (*Role-Based Access Control*).
* **Database Optimization:**
  * Penerapan *Database Indexing* pada kolom tinggi *query* (`buyer_id`, `seller_id`, `orders.status`).
  * *Cursor Pagination* untuk memuat katalog masif tanpa membebani memori CPU/RAM.
* **Asynchronous Process (Queues):**
  * Proses pengiriman email transaksional dan notifikasi dilakukan di latar belakang (*Laravel Background Queues*) agar respon *checkout* berdurasi < 1 detik.
* **Asset & File Security:**
  * Penggunaan *Signed Temporary URLs* untuk unduhan file langsung agar link tidak bisa disebarkan atau dicuri secara tak terotorisasi.
* **Integrasi Pihak Ketiga (Third-Party Services):**
  * **Payment Gateway:** Midtrans / Xendit (API QRIS, Virtual Account, E-Wallet).
  * **Transactional Email:** Resend API / Brevo (SMTP Engine).
  * **WhatsApp Gateway:** Fonnte API (Notifikasi transaksional seller).

---

## 6. Proyeksi Pertumbuhan & Peta Jalan (Roadmap)

### Fase 1: MVP & Testing (Bulan 1 - 2)
* Peluncuran fitur dasar: Checkout, Auto-Account, Upload File & Link, Payment Gateway.
* *Internal Validation* & Onboarding 20 Kreator Perdana (Batch Terbatas).
* Target GMV: Rp 10.000.000 / bulan.

### Fase 2: Scale-Up & Expansion (Bulan 3 - 6)
* Pembukaan pendaftaran publik untuk kreator umum.
* Target Katalog: **3.000+ Produk Digital**.
* Target Pengguna: 500+ Active Sellers & 10.000+ Unique Buyers.
* Target GMV: Rp 100.000.000 / bulan.

### Fase 3: Ecosystem Growth (Bulan 7 - 12)
* Peluncuran *Pro Creator Subscription*.
* Peluncuran Sistem *Affiliate Program* (Kreator bisa saling mempromosikan produk kreator lain dengan sistem bagi hasil otomatis).
* Fitur *Bundling Product* & *Discount Voucher*.

---

## 7. Penutup & Hak Cipta

**buyle.id** dikembangkan secara mandiri di bawah naungan **HVM Digital Engineering** sebagai wujud komitmen dalam menyediakan infrastruktur ekonomi digital yang mandiri, efisien, dan berorientasi pada kemudahan pengguna di Indonesia.

*Dokumen ini merupakan panduan teknis dan operasional internal. Terakhir diperbarui: Agustus 2026.*