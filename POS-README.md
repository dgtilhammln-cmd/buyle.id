# Dokumentasi Sistem Deposit & Laporan Kasir Digital (POS) buyle.id

Dokumen ini menjelaskan arsitektur, alur kerja, validasi bisnis, dan petunjuk teknis implementasi Sistem Saldo Deposit Kasir POS dan Laporan Terpisah POS pada platform buyle.id.

---

## 1. Latar Belakang & Masalah Bisnis

Pada transaksi Point of Sale (POS) offline:
- **Transaksi Non-Tunai (QRIS / Transfer Bank):** Uang pembayaran melewati payment gateway online sehingga potongan biaya platform (*platform fee*) dan biaya admin (*admin fee*) dapat otomatis dipotong oleh sistem.
- **Transaksi Tunai (CASH):** Uang fisik 100% langsung diterima oleh kasir / creator di lokasi. Platform tidak dapat memotong fee transaksi secara langsung dari uang fisik tersebut.
- **Solusi:** Creator wajib memiliki **Saldo Deposit POS** yang tersimpan di sistem. Setiap kali kasir memproses pembayaran **CASH**, fee platform dan fee admin akan langsung didebit dari saldo deposit creator tersebut. Jika saldo deposit tidak mencukupi, transaksi tunai diblokir sampai creator melakukan top up saldo deposit.

---

## 2. Arsitektur Database

### A. Tabel `creator_profiles`
Penambahan kolom:
- `pos_balance` (`DECIMAL(14, 2)`, default `0.00`): Menyimpan sisa saldo deposit aktif milik creator untuk operasional kasir POS.

### B. Tabel `pos_deposits`
Tabel pencatatan riwayat top up deposit:
- `id` (BigInt, PK)
- `creator_id` (BigInt, FK ke `creator_profiles.id`)
- `deposit_number` (String, unik, format: `DEP-YYYYMMDD-XXXX`)
- `amount` (Decimal 14, 2): Nominal top up
- `payment_method` (String: `qris`, `bank_transfer`, `manual`)
- `status` (String: `pending`, `success`, `failed`, `expired`)
- `snap_token` (String, nullable): Token pembayaran Midtrans
- `paid_at` (Timestamp, nullable)
- `created_at`, `updated_at`

### C. Tabel `pos_balance_mutations`
Tabel buku besar (ledger) mutasi saldo keluar-masuk deposit:
- `id` (BigInt, PK)
- `creator_id` (BigInt, FK ke `creator_profiles.id`)
- `type` (Enum: `topup`, `fee_deduction`, `adjustment`)
- `amount` (Decimal 14, 2): Nilai mutasi (+ atau -)
- `balance_before` (Decimal 14, 2)
- `balance_after` (Decimal 14, 2)
- `order_id` (BigInt, nullable, FK ke `orders.id`)
- `reference_number` (String, nullable): No Order atau No Deposit
- `notes` (Text, nullable): Keterangan mutasi
- `created_at`, `updated_at`

---

## 3. Alur Kerja & Aturan Bisnis

### A. Tampilan Saldo & Top Up Deposit di POS (`/creator/pos`)
1. Di bagian header POS, ditampilkan widget **Saldo Deposit POS** dengan indikator visual dan tombol **Isi Saldo Deposit**.
2. Creator dapat melakukan top up deposit instan via modal popup dengan pilihan nominal (Rp 20.000, Rp 50.000, Rp 100.000, Rp 250.000, Rp 500.000, atau input manual).
3. Pembayaran top up diproses instan melalui QRIS Midtrans Snap. Begitu pembayaran berhasil dikonfirmasi, saldo deposit langsung bertambah secara realtime.

### B. Validasi Transaksi CASH saat Checkout POS
1. Saat kasir memilih metode pembayaran **CASH**:
   - Sistem menghitung `total_fee = platform_fee + admin_fee`.
   - **Kondisi Saldo Kurang:** Jika `pos_balance < total_fee`, sistem memblokir checkout dan menampilkan peringatan:
     *"Saldo deposit POS Anda tidak mencukupi untuk biaya admin & platform fee (Dibutuhkan Rp X, Sisa saldo Rp Y). Silakan lakukan top up saldo deposit terlebih dahulu."*
   - **Kondisi Saldo Cukup:** Checkout berhasil diproses.
2. Sistem otomatis memotong saldo deposit creator sebesar `total_fee` dan mencatat entri mutasi ke tabel `pos_balance_mutations`.

### C. Riwayat Transaksi Harian & Rekap Closing Shift di POS
1. Kasir dapat melihat daftar transaksi hari ini beserta rincian metode pembayaran dan nominal fee yang terpotong.
2. Fitur **Rekap Closing Kasir / Tutup Shift**:
   - Menghitung total omset kotor hari ini.
   - Menghitung total uang tunai fisik yang wajib ada di laci kasir (*Cash in Drawer*).
   - Menghitung total pembayaran digital (QRIS & Transfer).
   - Menghitung total fee yang terpotong dari saldo deposit.
   - Tombol **Cetak Struk Rekap Closing** dalam format struk thermal 58mm/80mm untuk serah terima kasir ke manajemen/owner.

---

## 4. Pemisahan Laporan Penjualan (`/creator/sales-report`)

Halaman laporan penjualan (`/creator/sales-report`) memiliki 3 tab terpisah yang tidak saling bercampur:

1. **Tab 1: Data Pembeli**
   - Khusus transaksi toko online dan marketplace (`orders.source != 'pos'`).
   - Menyajikan metrik pendapatan online, data pelanggan, resi pengiriman, dan produk digital.

2. **Tab 2: Data Leads**
   - Khusus formulir kontak dan prospek leads dari link bio / katalog.

3. **Tab 3: Transaksi POS (Kasir Offline)**
   - Khusus seluruh transaksi kasir digital (`orders.source == 'pos'`).
   - **Filter Fleksibel:** Filter waktu (Hari Ini, 7 Hari, 30 Hari, Bulan Ini, Custom Date) dan Filter Metode Pembayaran (Semua, Cash, QRIS, Transfer).
   - **4 Kartu Metrik POS:**
     * Total Omset Kasir POS
     * Total Pembayaran Tunai (Cash Fisik)
     * Total Pembayaran Non-Tunai (QRIS / Transfer)
     * Total Fee Terpotong dari Saldo Deposit
   - **Neraca Keuangan Kasir & Audit Saldo Deposit:**
     * Posisi Kas Fisik (Tunai di Laci Kasir)
     * Posisi Kas Digital (Gateway / Bank)
     * Audit Deposit POS: Saldo Awal, Total Top Up, Total Fee Terpotong, Sisa Saldo Deposit Aktif.
   - **Tabel Rinci Transaksi POS:**
     * Nomor Invoice POS (`#POS-xxxx`)
     * Waktu transaksi
     * Pelanggan & Catatan Meja
     * Daftar item produk & varian
     * Subtotal, diskon, dan fee terpotong
     * Total bayar
     * Tombol Cetak Ulang Struk
   - **Export Data POS:** Tombol download laporan POS ke file CSV/Excel untuk kebutuhan akuntansi.

---

## 5. Standar Tampilan & User Interface

- **Tanpa Emoticon:** Seluruh antarmuka menggunakan icon SVG bersih berbasis vector.
- **Tanpa Emdash:** Tidak menggunakan karakter emdash (`—`) atau endash (`–`).
- **Desain Seragam & Proporsional:** Mengikuti design language buyle.id (aksen hijau `#1eb349`, font Montserrat, radius kartu `14px-18px`, border netral `#e2e8f0`).
- **Mobile Friendly:** Seluruh grid, modal, dan tabel menggunakan pembungkus responsive (`overflow-x: auto` dan flex/grid collapse) sehingga nyaman diakses dari smartphone, tablet, maupun layar desktop kasir.
