# Buyle.id Withdrawal and Financial Management Architecture Documentation

Dokumentasi komprehensif arsitektur sistem Pencairan Saldo (Withdrawal), pemisahan arus finansial (Platform Fee, Biaya Pencairan 0%, Laba Bersih, Kewajiban Saldo), sistem cerdas Holding Period 3x24 Jam, manajemen ACC / Penolakan di dashboard Admin (/admin/payouts), serta tata kelola rekening dan e-wallet berizin resmi pada platform Buyle.id.

Prinsip Utama Finansial Creator:
Dana hasil penjualan milik creator dicairkan 100% utuh tanpa potongan biaya penarikan (bebas biaya admin penarikan). Pendapatan platform Buyle.id berasal murni dari Platform Fee transaksi e-commerce saat pembeli melakukan pembayaran.

---

## 1. Ringkasan Eksekutif (Executive Summary)

Sistem keuangan dan pencairan saldo Buyle.id dirancang untuk memberikan transparansi finansial maksimal bagi Admin/Owner dan Creator. Creator mendapatkan 100% hak penjualan mereka tanpa potongan saat pencairan, sementara platform memitigasi risiko fraud dan sengketa pesanan melalui Holding Period 72 jam.

```
[ TRANSAKSI PEMBELI ]
       │
       ▼ (Pembeli Bayar: Subtotal Produk + 5% Platform Fee)
[ Saldo Masuk ke Buyle.id ]
       │
       ▼ (Payment Sukses / Terkonfirmasi)
[ HOLDING PERIOD: 3x24 JAM (72 JAM) ] ──► Status: Saldo Menunggu (Aman dari dispute / sengketa)
       │
       ▼ (Setelah 72 Jam Transaksi)
[ SALDO SIAP DITARIK ] ─────────────────► Otomatis berpindah ke saldo yang dapat dicairkan
       │
       ▼ (Creator Mengajukan Withdrawal)
[ BIAYA PENCAIRAN: GRATIS (RP 0) ] ─────► Dana Creator 100% Utuh Tanpa Potongan
       │
       ▼ (Dashboard Admin: /admin/payouts)
┌──────────────────────────────────────┴──────────────────────────────────────┐
│                                                                             │
▼ (Tombol ACC / Kirim Dana)                                                   ▼ (Tombol Tolak / Reject)
[ Status: Processed / Selesai ]                                       [ Status: Rejected ]
• Admin transfer 100% nominal pengajuan via bank                      • Saldo 100% otomatis refund ke akun creator
• Email notifikasi transfer berhasil ke Creator                       • Alasan penolakan terkirim via email notifikasi
```

---

## 2. Arsitektur Pemisahan Finansial (Anti-Ribet dan Transparan)

Untuk memastikan pengelola platform (Admin) memiliki pencatatan yang rapi antara uang kas operasional platform dengan uang titipan milik creator, sistem membagi finansial menjadi 5 pos terpisah:

### 2.1 Formula Pos Finansial

| Pos Finansial | Definisi | Rumus / Contoh | Kepemilikan |
|---|---|---|---|
| 1. GMV Penjualan | Total akumulasi perputaran uang kotor dari seluruh produk terjual. | Total Subtotal Order Sukses | Uang Perputaran |
| 2. Platform Fee (5%) | Biaya layanan sistem yang ditanggung saat transaksi produk e-commerce. | 5% x Subtotal Produk | Murni Laba Buyle.id |
| 3. Biaya Pencairan (Admin Fee) | Biaya administrasi penarikan saldo creator. | Rp 0 (Gratis / 100% Utuh) | Bebas Potongan |
| 4. Total Laba Bersih Platform | Total keuntungan bersih yang didapatkan Buyle.id dari operasional. | Platform Fee (5%) | Kas Bersih Platform |
| 5. Saldo Mengendap (Kewajiban) | Total dana hasil penjualan milik seluruh creator yang belum dicairkan. | Total Saldo Siap Ditarik + Total Saldo Menunggu | Uang Titipan Creator |

---

## 3. Logika Cerdas Holding Period 3x24 Jam (Smart Clearance)

### 3.1 Alasan Penerapan 3x24 Jam (72 Jam)
1. Proteksi Sengketa / Refund: Memberikan jeda waktu jika pembeli mengalami kendala pesanan, tiket bermasalah, atau kendala barang fisik.
2. Mitigasi Transaksi Bodong / Fraud: Mencegah penipuan pembayaran seperti kartu kredit curian yang ditarik seketika oleh oknum.
3. Standar Industri: Diterapkan oleh ekosistem marketplace modern untuk keamanan transaksi dua arah.

### 3.2 Klasifikasi Saldo di Dashboard Creator

Di halaman https://buyle.id/creator/payout-settings, saldo creator dibagi menjadi 2 kartu informatif:

```
┌──────────────────────────────────────┐  ┌──────────────────────────────────────┐
│  SALDO SIAP DITARIK                  │  │  SALDO MENUNGGU (HOLD 3 HARI)        │
│  Rp 1.500.000                        │  │  Rp 450.000                          │
│  Tersedia dari pesanan > 72 jam      │  │  Otomatis cair setelah 3x24 jam      │
└──────────────────────────────────────┘  └──────────────────────────────────────┘
```

### 3.3 Logika Backend (Eloquent Query)

```php
// 1. Pesanan Sukses Milik Creator
$sellerOrders = Order::whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
    ->whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success))
    ->get();

// 2. Transaksi yang sudah melewati 72 jam (Siap Ditarik)
$threshold = Carbon::now()->subHours(72);
$clearedGmv = $sellerOrders->filter(function($order) use ($threshold) {
    $paidTime = $order->payment?->paid_at ?? $order->created_at;
    return Carbon::parse($paidTime)->lte($threshold);
})->sum(fn($order) => $order->items->sum('subtotal'));

// 3. Transaksi dalam masa tunggu 72 jam (Menunggu Clearance)
$holdBalance = $sellerOrders->filter(function($order) use ($threshold) {
    $paidTime = $order->payment?->paid_at ?? $order->created_at;
    return Carbon::parse($paidTime)->gt($threshold);
})->sum(fn($order) => $order->items->sum('subtotal'));

// 4. Saldo Bersih Siap Ditarik (dikurangi pengajuan yang sedang diproses / disetujui)
$totalPayout = PayoutRequest::where('seller_id', $seller->id)
    ->whereIn('status', ['pending', 'approved', 'processed'])
    ->sum('amount');

$availableBalance = max(0, $clearedGmv - $totalPayout);
```

---

## 4. Standarisasi Bank dan E-Wallet Resmi

Sistem hanya mengizinkan institusi keuangan resmi berizin Bank Indonesia (BI) dan Otoritas Jasa Keuangan (OJK). Opsi tidak resmi dan koperasi dihilangkan untuk menjaga validitas nomor rekening.

### 4.1 Daftar Institusi Terverifikasi (+35 Opsi Resmi)

1. E-Wallet Resmi:
   - GoPay
   - OVO
   - Dana
   - ShopeePay
   - LinkAja
   - AstraPay

2. Bank Nasional / Terbesar:
   - Bank BCA
   - Bank Mandiri
   - Bank BRI
   - Bank BNI
   - Bank BSI (Syariah Indonesia)
   - CIMB Niaga
   - Permata Bank
   - Bank Danamon
   - Bank BTN
   - Bank Panin
   - OCBC NISP
   - Bank Maybank
   - Bank Mega
   - Bank Sinarmas

3. Bank Digital Populer:
   - Bank Jago
   - SeaBank
   - Blu by BCA Digital
   - Jenius / SMBC
   - Allo Bank
   - Bank Neo Commerce
   - Line Bank (KEB Hana)
   - Superbank

4. Bank Pembangunan Daerah:
   - Bank DKI
   - Bank BJB
   - Bank Jatim
   - Bank Jateng
   - Bank Sumut
   - Bank Nagari
   - Bank Kalbar
   - Bank Sulselbar

### 4.2 Komponen UI: Searchable Dropdown dengan Live Search
- Dropdown memiliki pencarian cepat di bagian atas.
- Creator cukup mengetik beberapa karakter untuk memfilter nama bank atau dompet digital.
- Menghindari kesalahan penulisan nama bank.

---

## 5. Desain UI/UX Halaman Creator (/creator/payout-settings)

### 5.1 Alur Rekening Tersimpan (Zero Friction)
1. Simpan Otomatis: Rekening yang diinput tersimpan pada profil user (bank_name, bank_account_number, bank_account_name).
2. Auto-Fill di Tab Penarikan: Tab penarikan langsung menyajikan kartu rekening aktif yang siap digunakan tanpa perlu mengetik ulang nomor rekening.

### 5.2 Rincian Penarikan 100% Tanpa Potongan
Form penarikan menyajikan informasi yang ringkas dan jelas:
1. Kartu Rekening Tujuan (Bank, Nomor Rekening, Atas Nama).
2. Input Nominal Penarikan:
   - Minimal penarikan: Rp 50.000
   - Maksimal penarikan: Sesuai Saldo Siap Ditarik
   - Tombol Quick Amount: Rp 50rb, Rp 100rb, Rp 500rb, Maks
3. Simulasi Rincian Real-time:
   - Jumlah Penarikan: Rp 100.000
   - Biaya Pencairan: Gratis (Rp 0)
   - Dana Diterima (100%): Rp 100.000
   - Estimasi Waktu: 1 sampai 3 Hari Kerja
4. Tombol: Ajukan Penarikan Dana.

---

## 6. Menu Admin: Keuangan dan Pencairan (/admin/payouts)

Menu diletakkan di Sidebar Admin pada grup E-Commerce & Data di bawah menu Pesanan.

### 6.1 Kartu Ringkasan Finansial
1. Total GMV: Akumulasi nilai kotor seluruh order sukses.
2. Platform Fee (5%): Akumulasi pendapatan platform dari transaksi produk.
3. Biaya Pencairan: Rp 0 (Gratis / 100% utuh untuk creator).
4. Total Laba Bersih Buyle.id: Nilai Platform Fee 5%.
5. Kewajiban Saldo Creator: Total saldo milik creator yang belum dicairkan.

### 6.2 Filter Tab Permintaan
- Semua Pengajuan
- Menunggu (Pending) dengan badge counter
- Selesai (Processed)
- Ditolak (Rejected)

### 6.3 Tabel dan Aksi Admin
- Kolom Tabel: Tanggal, Creator, Rekening Tujuan (+ Tombol Salin Nomor), Jumlah (100% Tanpa Potongan), Status, dan Aksi.
- Aksi ACC:
  1. Admin transfer nominal 100% penuh via bank/e-wallet ke nomor rekening creator.
  2. Klik tombol ACC, masukkan no referensi transfer (opsional) dan catatan (opsional).
  3. Status berubah menjadi processed dan notifikasi email transfer berhasil dikirim ke creator.
- Aksi Tolak:
  1. Admin klik tombol Tolak jika rekening tidak valid atau bermasalah.
  2. Masukkan alasan penolakan pada modal konfirmasi.
  3. Status berubah menjadi rejected, dana otomatis dikembalikan 100% ke saldo creator, dan notifikasi email penolakan dikirim.

---

## 7. Skema Database (payout_requests)

```sql
CREATE TABLE `payout_requests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `seller_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `admin_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(15,2) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_account_number` varchar(50) NOT NULL,
  `bank_account_name` varchar(100) NOT NULL,
  `admin_notes` text DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved','processed','rejected') NOT NULL DEFAULT 'pending',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payout_requests_seller_id_foreign` (`seller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Catatan: `admin_fee` diisi 0.00 sehingga `net_amount` bernilai sama persis dengan `amount` (100% utuh).

---

## 8. Notifikasi Email Terintegrasi

1. App\Notifications\PayoutRequestedNotification: Dikirim saat pengajuan penarikan berhasil dibuat (memuat rincian nominal 100% tanpa potongan).
2. App\Notifications\PayoutApprovedNotification: Dikirim saat admin menyelesaikan transfer (memuat konfirmasi transfer berhasil 100% utuh beserta nomor referensi).
3. App\Notifications\PayoutRejectedNotification: Dikirim saat admin menolak pengajuan (memuat alasan penolakan dan konfirmasi bahwa saldo telah 100% di-refund ke akun creator).
