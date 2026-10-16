# 💳 Buyle.id Withdrawal & Financial Management Architecture Documentation

Dokumentasi komprehensif arsitektur sistem **Pencairan Saldo (Withdrawal)**, pemisahan arus finansial (Platform Fee, Withdrawal Fee, Laba Bersih, Kewajiban Saldo), sistem cerdas **Holding Period 3x24 Jam**, manajemen ACC / Penolakan di dashboard Admin (`/admin/payouts`), serta tata kelola rekening & e-wallet berizin resmi pada platform **Buyle.id**.

---

## 📌 1. Ringkasan Eksekutif (Executive Summary)

Sistem keuangan dan pencairan saldo Buyle.id dirancang untuk memberikan transparansi finansial bagi **Admin/Owner** dan **Creator**, sekaligus memitigasi risiko *fraud*, sengketa pesanan (*buyer disputes*), dan beban operasional penarikan dana.

```
[ TRANSAKSI PEMBELI ]
       │
       ▼ (Pembeli Bayar: Subtotal Produk + 5% Platform Fee)
[ Saldo Masuk ke Buyle.id ]
       │
       ▼ (Payment Sukses / Confirmed)
[ HOLDING PERIOD: 3x24 JAM ] ────► Status: "Saldo Menunggu" (Aman dari dispute / sengketa)
       │
       ▼ (Setelah 72 Jam Transaksi)
[ SALDO SIAP DITARIK ] ──────────► Otomatis berpindah ke saldo yang dapat dicairkan
       │
       ▼ (Creator Mengajukan Withdrawal)
[ POTONGAN ADMIN FEE RP 5.000 ] ─► Nominal Bersih Siap Dikirim Admin
       │
       ▼ (Dashboard Admin: /admin/payouts)
┌─────────────────────────────────┴─────────────────────────────────┐
│                                                                   │
▼ (Tombol ACC / Kirim Dana)                                         ▼ (Tombol Tolak / Reject)
[ Status: Approved / Selesai ]                             [ Status: Rejected ]
• Admin transfer nominal bersih via bank                   • Saldo 100% otomatis REFUND ke akun creator
• Email notifikasi sukses terkirim ke Creator              • Alasan penolakan dikirim via email
```

---

## 💰 2. Arsitektur Pemisahan Finansial (Anti-Ribet & Transparan)

Untuk memastikan pemilik platform (Admin) tidak bingung membedakan antara **uang operasional milik platform** dengan **uang titipan milik creator**, sistem membagi finansial menjadi 5 pos terpisah:

### 2.1 Formula Pos Finansial

| Pos Finansial | Definisi | Rumus / Contoh | Kepemilikan |
|---|---|---|---|
| **1. GMV Penjualan** | Total akumulasi perputaran uang kotor dari seluruh produk terjual. | $\sum (\text{Subtotal Items})$ | Uang Perputaran |
| **2. Platform Fee (5%)** | Biaya layanan sistem yang ditanggung pembeli saat checkout e-commerce. | $5\% \times \text{Subtotal Produk}$ | **Murni Laba Buyle.id** |
| **3. Fee Penarikan (Admin Fee)** | Biaya administrasi setiap kali creator mencairkan saldo. | Flat **Rp 5.000** per withdrawal | **Murni Laba Buyle.id** (menutup biaya transfer BI-FAST) |
| **4. Total Laba Bersih Platform** | Total keuntungan bersih yang didapatkan Buyle.id dari operasional. | $\text{Platform Fee} + \text{Fee Penarikan}$ | **Kas Bersih Platform** |
| **5. Saldo Mengendap (Kewajiban)** | Total dana hasil penjualan milik seluruh creator yang belum dicairkan. | $\sum (\text{Saldo Siap Ditarik}) + \sum (\text{Saldo Menunggu})$ | **Uang Titipan Creator** |

---

## ⏳ 3. Logika Cerdas Holding Period 3x24 Jam (Smart Clearance)

### 3.1 Mengapa Harus 3x24 Jam (72 Jam)?
1. **Proteksi Sengketa / Refund**: Memberikan jeda waktu jika pembeli komplain produk digital rusak, tiket bermasalah, atau barang fisik belum sampai.
2. **Mitigasi Transaksi Bodong / Fraud**: Mencegah oknum pembeli nakal melakukan checkout dengan kartu kredit curian lalu seller langsung menarik uang seketika.
3. **Standar Industri E-Commerce**: Diadopsi oleh TikTok Shop, Tokopedia, Shopee, dan marketplace global (Stripe, Gumroad).

### 3.2 Klasifikasi Saldo di Dashboard Creator

Di halaman [https://buyle.id/creator/payout-settings](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/creator/payout.blade.php), saldo creator dipecah menjadi 2 kartu informatif:

```
┌──────────────────────────────────────┐  ┌──────────────────────────────────────┐
│  🟢 SALDO SIAP DITARIK               │  │  🟡 SALDO MENUNGGU (HOLD 3 HARI)     │
│  Rp 1.500.000                        │  │  Rp 450.000                          │
│  Tersedia dari pesanan > 72 jam      │  │  Otomatis cair setelah 3x24 jam      │
└──────────────────────────────────────┘  └──────────────────────────────────────┘
```

### 3.3 Logika Backend (Eloquent Query)

```php
// 1. Total Penjualan Kotor (GMV)
$allOrders = Order::whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
    ->whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success))
    ->get();

// 2. Transaksi yang SUDAH lewat 72 jam (Siap Ditarik)
$threshold = Carbon::now()->subHours(72);
$clearedGmv = $allOrders->filter(function($order) use ($threshold) {
    $paidTime = $order->payment?->paid_at ?? $order->created_at;
    return Carbon::parse($paidTime)->lte($threshold);
})->sum(fn($order) => $order->items->sum('subtotal'));

// 3. Transaksi yang MASIH dalam 72 jam (Menunggu Clearance)
$pendingGmv = $allOrders->filter(function($order) use ($threshold) {
    $paidTime = $order->payment?->paid_at ?? $order->created_at;
    return Carbon::parse($paidTime)->gt($threshold);
})->sum(fn($order) => $order->items->sum('subtotal'));

// 4. Saldo Bersih Siap Ditarik
$totalPayout = PayoutRequest::where('seller_id', $seller->id)
    ->whereIn('status', ['pending', 'approved', 'processed'])
    ->sum('amount');

$availableBalance = max(0, $clearedGmv - $totalPayout);
$holdBalance = $pendingGmv;
```

---

## 🏦 4. Standarisasi Bank & E-Wallet Resmi (Tanpa Koperasi)

Untuk meminimalisir kegagalan transfer dan risiko pencucian uang, **opsi "Lainnya" dan Koperasi/BPR tidak resmi DITIADAKAN**. Sistem hanya mengizinkan institusi keuangan resmi berizin Bank Indonesia (BI) dan Otoritas Jasa Keuangan (OJK).

### 4.1 Daftar Institusi Terverifikasi (+35 Opsi Resmi)

1. **E-Wallet Resmi (Instant):**
   - GoPay
   - OVO
   - Dana
   - ShopeePay
   - LinkAja
   - AstraPay

2. **Bank Nasional / Terbesar:**
   - Bank BCA
   - Bank Mandiri
   - Bank BRI
   - Bank BNI
   - Bank BSI (Syariah Indonesia)
   - CIMB Niaga
   - Permata Bank
   - Bank Danamon
   - Bank Tabungan Negara (BTN)
   - Bank Panin
   - OCBC NISP
   - Bank Maybank Indonesia
   - Bank Mega
   - Bank Sinarmas

3. **Bank Digital Populer:**
   - Bank Jago
   - SeaBank Indonesia
   - Blu by BCA Digital
   - Jenius / SMBC (BTPN)
   - Allo Bank
   - Bank Neo Commerce (BNC)
   - Line Bank (KEB Hana)
   - Superbank

4. **Bank Pembangunan Daerah (BPD Terpopuler):**
   - Bank DKI
   - Bank BJB
   - Bank Jatim
   - Bank Jateng
   - Bank Sumut
   - Bank Nagari
   - Bank Kalbar
   - Bank Sulselbar

### 4.2 Komponen UI: Searchable Dropdown dengan Live Search
- Dropdown memiliki kotak input pencarian instan di bagian atas.
- Creator mengetik huruf awal (misal: `"jag"` langsung menyorot **Bank Jago**, `"da"` langsung menyorot **Dana**).
- Mengurangi salah pilih bank dan mencegah salah transfer.

---

## 🖥️ 5. Desain UI/UX Halaman Creator (`/creator/payout-settings`)

### 5.1 Alur Simpan Rekening (Zero Friction)
1. **Simpan Otomatis**: Saat pertama kali creator mengisi rekening di tab *"Pengaturan Rekening"*, data tersimpan ke profil tabel `users` (`bank_name`, `bank_account_number`, `bank_account_name`).
2. **Auto-Fill di Tab Penarikan**:
   - Tab *"Penarikan Saldo"* langsung menampilkan **Kartu Rekening Tujuan Terpilih**.
   - Creator tidak perlu mengetik ulang nomor rekening setiap ingin menarik dana.
   - Tersedia tombol `[Ubah Rekening]` jika ingin mengganti ke rekening lain.

### 5.2 Form Penarikan Minimalis (Tanpa Catatan)
Sesuai arahan, elemen kartu/input catatan dihapus. Form hanya memuat:
1. **Kartu Rekening Tujuan**:
   - `BCA - 1234567890 a/n AHMAD DHANI`
2. **Input Nominal Penarikan**:
   - Minimal penarikan: **Rp 50.000**
   - Maksimal penarikan: Sesuai `Saldo Siap Ditarik`
   - Tombol Quick Amount: `[Rp 50.000]` `[Rp 100.000]` `[Rp 500.000]` `[Tarik Maksimal]`
3. **Simulasi Rincian Biaya Real-time**:
   - Nominal Penarikan: `Rp 100.000`
   - Biaya Admin Bank: `- Rp 5.000`
   - **Dana Bersih Diterima: `Rp 95.000`**
   - Estimasi Waktu: **1–3 Hari Kerja** (Senin–Jumat)
4. Tombol: **`[Tarik Dana Sekarang]`** (dengan konfirmasi modal aman).

---

## 🛠️ 6. Menu Baru Admin: Keuangan & Pencairan (`/admin/payouts`)

Menu ini ditempatkan di Sidebar Admin pada grup **E-Commerce & Data** di bawah menu *Pesanan*.

### 6.1 Top Financial Cards (Dashboard Overview)
1. **Total GMV Penjualan**: Akumulasi nilai kotor seluruh order sukses.
2. **Platform Fee (5%)**: Akumulasi laba platform dari transaksi produk.
3. **Fee Penarikan (Admin)**: Akumulasi fee Rp 5.000 dari transaksi pencairan sukses.
4. **Total Laba Bersih Buyle.id**: `(Platform Fee + Fee Penarikan)`.
5. **Kewajiban Saldo Creator**: Saldo mengendap yang masih menjadi hak para creator.

### 6.2 Filter Tab Permintaan Penarikan
- 📋 **Semua Pengajuan**
- 🟡 **Menunggu ACC (Pending)** (dengan badge counter alert)
- 🟢 **Selesai Ditransfer (Approved)**
- 🔴 **Ditolak (Rejected)**

### 6.3 Desain Kolom Tabel Admin
| Kolom | Keterangan & Fitur |
|---|---|
| **Tanggal** | Tanggal & jam diajukan (contoh: *16 Okt 2026, 15:30*) |
| **Creator** | Foto profil, Nama Lengkap, Nama Toko/Bio, & No WhatsApp |
| **Rekening Tujuan** | Bank & No Rekening + **Tombol [Copy No Rekening]** (1-klik salin tanpa typo) |
| **Atas Nama** | Nama pemilik rekening sesuai buku tabungan |
| **Jumlah Penarikan** | Pengajuan kotor: `Rp 100.000` |
| **Biaya Admin** | Biaya platform: `Rp 5.000` |
| **Transfer Bersih (Net)** | **`Rp 95.000`** *(Teks hijau bold besar - nominal pasti yang harus ditransfer admin)* |
| **Status** | Badge warna: Pending (Kuning), Approved (Hijau), Rejected (Merah) |
| **Aksi** | Tombol `[ACC & Selesai]` dan `[Tolak]` |

### 6.4 Aksi: ACC & Kirim Dana
1. Admin menyalin no rekening via tombol copy.
2. Admin transfer uang bersih via m-banking / internet banking / BI-FAST (Rp 2.500).
3. Admin klik **`[ACC & Selesai]`**:
   - Modal input opsional: No Referensi Transfer / Upload Bukti Screenshot.
   - Status berubah menjadi `approved` / `processed`.
   - **Otomatis Kirim Email Notifikasi** ke Creator: *"Pencairan Saldo Sebesar Rp X Berhasil Ditransfer ke Rekening Anda"*.

### 6.5 Aksi: Tolak Pengajuan (Non-ACC & Auto Refund)
Jika ada indikasi nomor rekening salah, nama tidak sinkron, atau kecurigaan akun:
1. Admin klik **`[Tolak Pengajuan]`**.
2. Modal mewajibkan input: **Alasan Penolakan** (contoh: *"Nomor rekening tidak terdaftar di BCA"* atau *"Nama pemilik rekening berbeda dengan identitas creator"*).
3. **Sistem Cerdas**:
   - Status menjadi `rejected`.
   - Dana sebesar `Rp 100.000` **otomatis dikembalikan (auto-refund)** ke saldo creator seketika.
   - **Otomatis Kirim Email Notifikasi** ke Creator: *"Pengajuan Pencairan Ditolak: [Alasan]. Saldo Anda telah dikembalikan secara utuh."*

---

## 🗄️ 7. Skema Database & Model

Tabel `payout_requests` yang telah ada didukung atribut status dan audit trail:

```sql
CREATE TABLE `payout_requests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `seller_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `admin_fee` decimal(10,2) NOT NULL DEFAULT 5000.00,
  `net_amount` decimal(14,2) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_account_number` varchar(50) NOT NULL,
  `bank_account_name` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `transfer_proof` varchar(255) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved','processed','rejected') NOT NULL DEFAULT 'pending',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payout_requests_seller_id_foreign` (`seller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🔔 8. Notifikasi Email Creator (Notification Classes)

1. **`PayoutRequestedNotification`**:
   - Terkirim saat creator submit form penarikan: konfirmasi permohonan sedang diproses (estimasi 1–3 hari kerja).
2. **`PayoutApprovedNotification`**:
   - Terkirim saat admin menekan tombol ACC: konfirmasi uang telah ditransfer beserta detail bank & bukti transfer.
3. **`PayoutRejectedNotification`**:
   - Terkirim saat admin menolak: penjelasan alasan penolakan dan pemberitahuan bahwa saldo telah di-refund ke akun.

---

## 🚀 9. Rencana Implementasi Bertahap (Next Steps)

1. **Tahap 1: Backend Controller Admin & Route**
   - Implementasikan method `index`, `approve`, dan `reject` di [AdminPayoutController.php](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/app/Http/Controllers/Admin/AdminPayoutController.php).
   - Daftarkan route `/admin/payouts` di [routes/web.php](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/routes/web.php).
   - Tambahkan menu di sidebar [resources/views/layouts/admin.blade.php](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/layouts/admin.blade.php).

2. **Tahap 2: Tampilan Admin Payouts**
   - Buat view `resources/views/admin/payouts/index.blade.php` dengan kartu finansial (GMV, Platform Fee, Fee Penarikan, Laba Bersih, Kewajiban Saldo) dan tombol aksi ACC/Tolak.

3. **Tahap 3: Pembaruan Halaman Creator Payout Settings**
   - Update [SellerPayoutController.php](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/app/Http/Controllers/Creator/SellerPayoutController.php) untuk menghitung holding period 3x24 jam (`subHours(72)`).
   - Perbarui [resources/views/creator/payout.blade.php](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/creator/payout.blade.php):
     - Tampilkan 2 kartu saldo (Siap Ditarik vs Menunggu).
     - Hapus input catatan opsional.
     - Sediakan searchable dropdown 35+ Bank/E-Wallet resmi (tanpa koperasi/lainnya).
     - Auto-fill kartu rekening tersimpan.

4. **Tahap 4: Email Notifikasi & Testing**
   - Buat class notification untuk Payout Approved & Rejected dengan template email buyle.id.
   - Uji coba simulasi submit, ACC, tolak, dan auto-refund saldo.
