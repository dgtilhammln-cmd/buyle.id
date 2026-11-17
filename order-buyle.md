# Standar Arsitektur Transaksi, Order ID, dan Bagi Hasil Buyle.id

Dokumen ini mencatat format resmi kode transaksi (Order ID), alur bagi hasil (profit platform vs hak mitra), serta struktur antarmuka manajemen transaksi di Buyle.id.

---

## 1. Format Standar Order ID (4 Pilar Bisnis)

Setiap transaksi yang dibuat di Buyle.id dan dikirimkan ke payment gateway (Midtrans) maupun tercatat di database menggunakan format prefix 3 huruf yang terstandardisasi:

| Pilar Bisnis | Prefix | Format Order ID | Contoh | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| Marketplace | `MKT` | `MKT-YYYYMMDD-XXXX` | `MKT-20261117-0001` | Produk fisik dan produk digital kreator |
| Ticketing / Tix | `TIX` | `TIX-YYYYMMDD-XXXX` | `TIX-20261117-0001` | Pembelian e-tiket event, konser, seminar |
| Kasir POS | `POS` | `POS-YYYYMMDD-XXXX` | `POS-20261117-0001` | Transaksi kasir offline dan self-order meja |
| Undangan Digital | `WED` | `WED-YYYYMMDD-XXXX` | `WED-20261117-0001` | Pembayaran aktivasi / paket sewa undangan |
| Amplop Digital | `AMP` | `AMP-YYYYMMDD-XXXX` | `AMP-20261117-0001` | Uang kado pernikahan dari tamu ke pengantin |
| Custom Domain | `DOM` | `DOM-YYYYMMDD-XXXX` | `DOM-20261117-0001` | Pembelian domain kustom (.com, .id, dll) |

### Keunggulan Format Ini:
1. Sinkron 1 banding 1 dengan Midtrans: Ketika membuka dashboard Midtrans, admin langsung mengetahui jenis transaksi hanya dari 3 huruf pertama.
2. Tidak ada tumpang-tindih: Masing-masing layanan memiliki identitas yang mandiri.
3. Struktur nomor urut (`XXXX`) di-reset atau bertambah otomatis per tanggal (`YYYYMMDD`).

---

## 2. Skema Pembagian Hasil dan Profit Platform

### A. Marketplace (Produk Fisik dan Digital)
- Total Bayar Konsumen (Gross) = Subtotal Produk + Ongkir + Platform Fee + Admin Fee - Diskon
- Hak Penjual (Kreator) = Subtotal Produk - Platform Fee
- Profit Bersih Buyle.id = Platform Fee + Admin Fee

### B. Ticketing / Tix (E-Tiket Event)
- Total Bayar Konsumen (Gross) = Harga Tiket + Platform Fee Tiket + Admin Fee
- Hak Penyelenggara Event = Total Harga Tiket - Platform Fee Tiket
- Profit Bersih Buyle.id = Platform Fee Tiket + Admin Fee Tiket

### C. Kasir POS (Point of Sales)
- Total Transaksi (Gross) = Total Belanja Meja / Kasir
- Hak Merchant Kasir = Total Belanja - Fee Transaksi POS
- Profit Bersih Buyle.id = Platform Fee POS + Admin Fee POS

### D. Undangan dan Amplop Digital
- Amplop Digital: Tamu mentransfer nominal kado + Platform Fee (Rp 5.000).
- Hak Pengantin = 100% nominal amplop kado murni.
- Profit Bersih Buyle.id = Platform Fee (Rp 5.000 per transaksi settlement).
- Paket Undangan: Biaya sewa paket undangan masuk sebagai pendapatan platform.

---

## 3. Struktur Antarmuka Admin (4 Tab Pilar + Rekap Arus Kas)

Antarmuka Admin di menu Manajemen Pesanan (/admin/orders) dibagi menjadi 5 tab yang fokus dan tidak bertumpuk:

1. Tab Marketplace: Menampilkan riwayat transaksi produk fisik dan digital. Terdapat filter status pengiriman (Semua, Perlu Dikirim, Dikirim, Selesai).
2. Tab Tiket Event: Menampilkan pesanan tiket, nama pemesan, nama event, jumlah tiket, dan status e-tiket.
3. Tab Kasir POS: Menampilkan transaksi outlet kasir, self-order nomor meja, dan metode pembayaran kasir.
4. Tab Undangan dan Amplop: Menampilkan kado amplop digital dari para tamu serta pembayaran paket undangan.
5. Tab Rekap Arus Kas: Menampilkan ringkasan total uang masuk (gross), total hak mitra yang belum dicairkan, total profit platform, serta riwayat pencairan dana (payout) yang sudah ditransfer ke bank.

Setiap tab menampilkan 3 Kartu Ringkasan (KPI) utama yang konsisten:
- Total Omset (Gross)
- Jatah Penjual / Mitra
- Profit Bersih Buyle.id (Platform Fee + Admin Fee)

---

## 4. Kebijakan Desain dan Tampilan
- Responsif terhadap perangkat seluler (mobile friendly).
- Bebas dari karakter emotikon.
- Menggunakan tanda hubung standar (-) dan tidak menggunakan em-dash.
- Tipografi menggunakan font Montserrat dengan warna aksen hijau Buyle (#1eb349) dan latar belakang slate gelap (#0F172A) untuk konsistensi tema platform.
