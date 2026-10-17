# 🤝 Buyle.id Storefront-Driven Affiliate System Architecture

Dokumentasi lengkap arsitektur sistem afiliasi terpadu (*Storefront-Driven Affiliate & Cookie Attribution Engine*) pada platform **Buyle.id**. Dokumen ini dirancang sebagai panduan tunggal (*single source of truth*) bagi tim pengembang (IT) dan AI Agent di masa mendatang agar memahami seluruh alur tanpa perlu membongkar puluhan file kode satu per satu.

---

## 📌 1. Ringkasan Eksekutif & Filosofi Ekosistem

Di platform lain, affiliator sering kali hanya diberikan tautan mentah berantakan dengan query parameter seperti `buyle.id/produk?ref=aff_987123_xyz`. Tautan semacam ini sering dicurigai spam oleh calon pembeli dan memiliki rasio konversi yang rendah.

**Buyle.id menggunakan pendekatan "Storefront-Driven Affiliate":**
Setiap affiliator di Buyle.id adalah kreator yang memiliki **Web / Link in Bio mandiri** (contoh: `buyle.id/ilhammaulana`).

1. Affiliator memilih produk dari kreator lain di katalog Buyle.id.
2. Produk langsung terpasang di etalase/toko bio affiliator dengan tautan resmi:
   ```
   https://buyle.id/{username_affiliator}/produk/{slug_produk}
   (atau via custom domain: https://tokobudi.com/produk/{slug_produk})
   ```
3. Calon pembeli membeli melalui toko affiliator tersebut dengan pengalaman berbelanja yang elegan, terpercaya, dan 100% berada dalam ekosistem Buyle.id.
4. Sistem otomatis mencatat rujukan, menahan cookie 30 hari, mengunci komisi saat checkout, dan membagi saldo secara otomatis saat pembayaran terverifikasi.

---

## 🗺️ 2. Diagram Alur Transaksi & Data (End-to-End)

```
┌────────────────────────────────────────────────────────────────────────┐
│ 1. SELLER (Pemilik Produk Asli)                                        │
│    - Menentukan harga produk (misal: Rp 100.000)                       │
│    - Mengaktifkan `affiliate_commission_rate` (misal: 15% / Rp 15.000) │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ Muncul di Katalog Affiliate
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│ 2. AFFILIATOR (Kreator Toko Bio, misal: ilhammaulana)                 │
│    - Memilih produk & klik [+ Pasang di Toko Saya]                     │
│    - Tautan Toko Terbentuk: buyle.id/ilhammaulana/produk/{slug}        │
│    - Membagikan link toko / produk ke Instagram, TikTok, WhatsApp, dll │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ Calon Pembeli Mengklik Link
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│ 3. PENGUNJUNG / CALON PEMBELI                                          │
│    - Membuka halaman `BioProductController@show`                       │
│    - Sistem pasang Cookie & Session: `affiliate_ref_{productId}` (30h)│
│    - Pembeli klik [Tambah Keranjang] atau [Beli Sekarang]              │
│    - `CartItem` merekam `affiliate_id = ilhammaulana->id`              │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ Menuju Checkout (`CheckoutService`)
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│ 4. CHECKOUT & ORDER CREATION                                           │
│    - Snapshot permanen di `order_items`:                               │
│      * `seller_id`                   = ID Pemilik Asli Produk          │
│      * `affiliate_id`                = ID Ilham Maulana                │
│      * `affiliate_commission_rate`   = 15%                             │
│      * `affiliate_commission_amount` = Rp 15.000                       │
│    - Pembeli membayar via QRIS / Virtual Account / Payment Gateway     │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ Pembayaran Lunas (`PaymentStatus::Success`)
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│ 5. SPLIT PAYMENT ENGINE (`SellerBalanceService`)                       │
│    Total Transaksi: Rp 100.000                                         │
│    ├── Platform Fee Buyle.id (misal 5%) : Rp  5.000                     │
│    ├── Komisi Affiliator (Ilham Maulana): Rp 15.000 -> Masuk Wallet     │
│    └── Hasil Bersih Seller Asli         : Rp 80.000 -> Masuk Wallet     │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 🔍 3. Akar Masalah "Komisi Bocor" (Root Cause Analysis)

Sebelum arsitektur ini dirapikan, terjadi **kebocoran komisi 100%** dengan rincian teknis berikut:

| Titik Masalah | Kondisi Sebelumnya | Akibat |
| :--- | :--- | :--- |
| **Halaman Produk Bio** | `BioProductController@show` menampilkan produk, namun tidak merekam Session maupun HTTP Cookie rujukan. | Begitu pembeli berpindah halaman atau me-refresh, identitas affiliator hilang. |
| **Form Tombol Keranjang** | Tombol `[Keranjang]` hanya mem-POST `product_id` dan `qty`. | `CartController@add` dan `CartService` tidak pernah menerima data ID affiliator. |
| **Tombol Checkout** | Tombol `[Checkout]` hanya link biasa ke `https://buyle.id/keranjang`. | Melewatkan seluruh context bio affiliator. |
| **Tabel Keranjang (`carts`)** | Tabel `carts` belum memiliki kolom `affiliate_id`. | Keranjang belanja sama sekali buta terhadap affiliator. |
| **Tabel Pesanan (`order_items`)**| Tabel `order_items` belum menyimpan `affiliate_id` dan `affiliate_commission_amount`. | Invoice pesanan tidak memiliki jejak komisi untuk diaudit. |
| **Pencairan Saldo (`SellerBalanceService`)** | Saldo GMV 100% hanya dihitung berdasarkan `where('seller_id', $seller->id)`. | Komisi affiliator tidak pernah dihitung, sehingga uang 100% masuk ke seller asli. |

---

## 🛡️ 4. Mekanisme Anti-Bocor: 3-Lapis Proteksi (*Triple-Layer Attribution*)

Untuk menjamin hak affiliator tidak pernah hilang karena pembeli berganti tab atau menunda pembayaran, Buyle.id menerapkan **3-Lapis Proteksi**:

### Lapis 1: Direct Context Injection (Form & Button Level)
Pada halaman `buyle.id/{username}/produk/{slug}`:
- Sistem mendeteksi jika pemilik bio (`$profile->user_id`) berbeda dengan penjual produk (`$product->seller_id`).
- Form `[Keranjang]` secara otomatis menyertakan:
  ```html
  <input type="hidden" name="affiliate_id" value="{{ $affiliateUserId }}">
  ```
- Tombol `[Checkout]` otomatis menambahkan produk ke keranjang dengan parameter affiliator sebelum mengarahkan ke halaman checkout.

### Lapis 2: Persistent Storage (Cookie 30 Hari & Session Fallback)
Jika pembeli hanya membuka produk lalu menutup browser, dan baru kembali berbelanja beberapa hari kemudian:
- Sistem menanamkan HTTP Cookie terenkripsi:
  ```php
  cookie()->queue('buyle_aff_' . $product->id, $affiliateUserId, 60 * 24 * 30); // 30 hari
  session(['buyle_aff_' . $product->id => $affiliateUserId]);
  ```
- Saat pembeli memasukkan barang ke keranjang dari halaman mana pun, sistem memeriksa cookie tersebut sebagai fallback.

### Lapis 3: Immutable Database Snapshot (Order Item Level)
Setelah pesanan dibuat di `CheckoutService`:
- Data komisi disalin secara permanen ke baris `order_items`.
- Nilai persentase dan nominal rupiah komisi **terkunci permanen** (tidak akan terpengaruh jika di kemudian hari seller mengubah setting komisi produk).

### 🛑 Aturan Anti Self-Referral:
Affiliator dilarang mendapatkan komisi dari pembelian produk untuk dirinya sendiri menggunakan link tokonya sendiri:
```php
if (auth()->check() && (int) auth()->id() === (int) $affiliateUserId) {
    $affiliateUserId = null; // Batalkan afiliasi mandiri
}
```

---

## 🗄️ 5. Skema Basis Data (Database Schema)

### A. Tabel `carts`
Kolom pendukung affiliator pada tabel keranjang belanja:

```sql
ALTER TABLE carts ADD COLUMN affiliate_id BIGINT UNSIGNED NULL AFTER variant_value_id;
ALTER TABLE carts ADD CONSTRAINT fk_carts_affiliate FOREIGN KEY (affiliate_id) REFERENCES users(id) ON DELETE SET NULL;
```

### B. Tabel `order_items`
Kolom snapshot komisi affiliator pada setiap item pesanan:

```sql
ALTER TABLE order_items ADD COLUMN affiliate_id BIGINT UNSIGNED NULL AFTER reseller_id;
ALTER TABLE order_items ADD COLUMN affiliate_commission_rate DECIMAL(5, 2) NULL DEFAULT 0.00 AFTER reseller_margin;
ALTER TABLE order_items ADD COLUMN affiliate_commission_amount DECIMAL(15, 2) NULL DEFAULT 0.00 AFTER affiliate_commission_rate;
ALTER TABLE order_items ADD CONSTRAINT fk_order_items_affiliate FOREIGN KEY (affiliate_id) REFERENCES users(id) ON DELETE SET NULL;
```

### C. Tabel `products` (`services`)
Kolom pengaturan komisi oleh seller pemilik produk:
- `affiliate_commission_rate`: `DECIMAL(5, 2)` (Default minimal: 5%, maksimal: 100%).

---

## 💻 6. Kode & File Inti yang Mengelola Sistem

| Modul | File Path | Fungsi Utama |
| :--- | :--- | :--- |
| **Bio Product Controller** | [`app/Http/Controllers/BioProductController.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/app/Http/Controllers/BioProductController.php) | Mendeteksi kunjungan etalase affiliator dan memasang Cookie 30 hari. |
| **Cart Service** | [`app/Services/CartService.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/app/Services/CartService.php) | Menyimpan `affiliate_id` ke tabel `carts` dengan validasi anti self-referral. |
| **Checkout Service** | [`app/Services/CheckoutService.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/app/Services/CheckoutService.php) | Mengunci snapshot `affiliate_commission_amount` ke dalam `order_items`. |
| **Balance Service** | [`app/Services/SellerBalanceService.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/app/Services/SellerBalanceService.php) | Memecah hasil penjualan: mendebit komisi ke affiliator dan mengkreditkan sisa bersih ke seller. |
| **Bio Product View** | [`resources/views/bio/theme5/product_show.blade.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/bio/theme5/product_show.blade.php) | Form `cart.add` yang membawa input `affiliate_id` dan tombol checkout instan. |
| **Creator Bio View** | [`resources/views/creator/bio/index.blade.php`](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/creator/bio/index.blade.php) | Katalog Affiliate buyle.id & tombol *Salin Link Promosi*. |

---

## 💰 7. Algoritma Perhitungan & Split Komisi

### Rumus Perhitungan per Item:
```php
$subtotal = $item->unit_price * $item->qty;

if ($item->affiliate_id && $item->affiliate_commission_rate > 0) {
    // Hitung komisi affiliator
    $affiliateCommission = round(($subtotal * $item->affiliate_commission_rate) / 100, 2);
    
    // Hasil bersih untuk seller sebelum fee platform
    $sellerNet = $subtotal - $affiliateCommission;
} else {
    $affiliateCommission = 0;
    $sellerNet = $subtotal;
}
```

### Rekonsiliasi di `SellerBalanceService`:
Saat menghitung saldo user:
1. **Sebagai Penjual Asli (`seller_id`)**:
   $$GMV_{seller} = \sum (\text{Subtotal Item} - \text{Komisi Affiliate})$$
2. **Sebagai Affiliator (`affiliate_id`)**:
   $$GMV_{affiliate} = \sum (\text{Komisi Affiliate})$$
3. **Total Saldo yang Dapat Ditarik**:
   $$Saldo_{tersedia} = (GMV_{seller} + GMV_{affiliate}) - Total Payout Selesai$$

Masa penahanan (*holding period*) mengikuti aturan standar platform:
- **Produk Digital / Fisik / Jasa**: Cair dalam 72 jam (3 hari) setelah pembayaran lunas.
- **Tiket Event**: Cair H+3 setelah tanggal pelaksanaan acara selesai.

---

## 🛠️ 8. Panduan Pengujian & Troubleshooting (Developer & AI Agent Checklist)

Saat melakukan audit atau pengujian fitur affiliate:

1. **Tes Kunjungan Toko Affiliator**:
   - Buka URL produk di toko affiliator: `http://localhost:8000/ilhammaulana/produk/sample-product`
   - Buka Inspect Element -> Storage -> Cookies.
   - Pastikan terdapat cookie `buyle_aff_{productId}` dengan nilai ID user `ilhammaulana`.

2. **Tes Tambah Keranjang**:
   - Klik tombol **[Keranjang]**.
   - Cek database tabel `carts`: baris baru wajib memiliki `affiliate_id` yang sesuai.

3. **Tes Checkout & Pembayaran**:
   - Lakukan checkout hingga selesai.
   - Cek database tabel `order_items`: pastikan kolom `affiliate_id`, `affiliate_commission_rate`, dan `affiliate_commission_amount` terisi angka yang valid.

4. **Tes Saldo & Wallet**:
   - Ubah status pembayaran order menjadi `success` di database atau via callback Midtrans sandbox.
   - Jalankan `\App\Services\SellerBalanceService::calculateBalance($affiliatorUser)`.
   - Pastikan nominal komisi masuk ke `cleared_gmv` atau `standard_hold_balance`.
