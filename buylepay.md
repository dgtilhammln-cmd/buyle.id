# Cetak Biru Arsitektur Buyle Pay (Closed-Loop Virtual Ledger System)

Dokumen ini memuat rancangan arsitektur, skema basis data, alur mutasi keuangan, serta strategi keamanan komprehensif untuk implementasi sistem dompet internal **Buyle Pay** di ekosistem Buyle.id.

---

## 1. Filosofi dan Konsep Dasar Buyle Pay

### 1.1 Closed-Loop Virtual Ledger
Buyle Pay dirancang sebagai sistem pencatatan buku besar tertutup (*Closed-Loop Virtual Ledger*), serupa dengan benchmark sistem seperti BBO Pay atau saldo dompet internal platform e-commerce.

Dalam arsitektur ini:
1. **Rekening Penampung Riil (Escrow / Pool Account):** Seluruh dana riil yang masuk dari pembayaran konsumen (QRIS, Virtual Account, E-Wallet) berkumpul di rekening penampung payment gateway resmi Buyle.id (Midtrans).
2. **Saldo Virtual (Virtual Liability):** Saldo yang tampil pada antarmuka akun kreator, vendor, atau sukarelawan (volunteer) adalah saldo buku besar (*ledger liability*) yang mencatat hak dana masing-masing pengguna.
3. **Penyelesaian Internal (Internal Settlement):** Perpindahan dana antar pengguna di dalam platform Buyle.id murni merupakan operasi mutasi baris data di basis data. Tidak ada uang fisik yang keluar ke jaringan perbankan saat transfer internal berlangsung.

### 1.2 Keuntungan Strategis Model Ini
- **Bebas Biaya Transfer Antar Akun:** Kreator dapat mendistribusikan honor atau upah ke volunteer, fotografer, kru event, dan vendor lokal dengan biaya Rp 0.
- **Penyelesaian Instan (0 Detik):** Perpindahan dana langsung selesai seketika saat tombol konfirmasi PIN divalidasi.
- **Efisiensi Likuiditas Platform:** Dana tunai tetap mengendap di rekening penampung Buyle.id selama belum dicairkan ke bank luar, memperkuat likuiditas dan meminimalkan biaya interbank gateway fee.

---

## 2. Arsitektur Alur Pergerakan Dana (Money Flow)

### 2.1 Alur 1: Uang Masuk (Inflow dari Penjualan)
1. Pembeli membayar pesanan tiket (TIX), produk fisik/digital (MKT), kasir (POS), atau amplop pernikahan (AMP) senilai Rp 100.000.
2. Webhook Midtrans terverifikasi mengirim sinyal `settlement`.
3. Basis data mencatat:
   - Platform Fee dan Admin Fee (Hak pendapatan Buyle.id): misal Rp 5.000.
   - Saldo Tertahan Kreator (*Escrow / Pending Balance*): Rp 95.000.
4. Setelah masa tahan berakhir (misal H+1 event selesai atau pesanan terkirim), sistem mengubah status saldo menjadi Saldo Aktif (*Available Balance*) yang siap digunakan atau dipindahkan.

### 2.2 Alur 2: Transfer Internal Antar Akun (Kreator ke Volunteer / Vendor)
1. Kreator ingin membayar vendor panggung atau volunteer senilai Rp 30.000 dari saldo aktifnya.
2. Kreator memasukkan username/email penerima, nominal, dan 6-digit PIN keamanan.
3. Basis data memproses mutasi atomik ganda:
   - Saldo aktif kreator didebit: -Rp 30.000.
   - Saldo aktif penerima dikreditkan: +Rp 30.000.
4. Notifikasi tanda terima dikirimkan seketika via email SMTP ke kedua belah pihak.
5. Uang fisik di rekening penampung Midtrans tetap utuh Rp 100.000.

### 2.3 Alur 3: Pencairan Dana Riil ke Bank (Outflow / Withdrawal)
1. Volunteer atau kreator mengajukan pencairan dana sebesar Rp 30.000 ke rekening bank mereka (BCA, BRI, Mandiri, dll).
2. Saldo aktif dipindahkan ke status Saldo Ditarik (*Locked for Payout*).
3. Pengajuan masuk ke antarmuka admin di [Admin Payouts](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/admin/payouts/index.blade.php).
4. Admin memproses transfer bank, mengunggah bukti transfer terkompresi, dan menyetujui transaksi.
5. Dana tunai riil keluar dari rekening bank Buyle.id menuju rekening penerima.

---

## 3. Skema Basis Data (Double-Entry Bookkeeping)

Untuk menjamin kepatuhan audit dan mencegah inkonsistensi saldo, data keuangan tidak boleh hanya disimpan sebagai satu kolom angka biasa. Sistem wajib menggunakan tabel dompet dan buku besar mutasi ganda.

### 3.1 Tabel `wallets`
Menyimpan ringkasan posisi saldo terkini dari tiap pengguna:
```sql
CREATE TABLE wallets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    available_balance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    escrow_balance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    locked_balance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    status ENUM('active', 'frozen', 'suspended') NOT NULL DEFAULT 'active',
    pin_hash VARCHAR(255) NULL,
    pin_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    pin_locked_until TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_wallets_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT chk_available_balance CHECK (available_balance >= 0),
    CONSTRAINT chk_escrow_balance CHECK (escrow_balance >= 0),
    CONSTRAINT chk_locked_balance CHECK (locked_balance >= 0)
);
```

### 3.2 Tabel `wallet_mutations`
Mencatat setiap mutasi debit dan kredit secara permanen (immutable audit trail):
```sql
CREATE TABLE wallet_mutations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    wallet_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('credit', 'debit') NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    balance_before DECIMAL(15, 2) NOT NULL,
    balance_after DECIMAL(15, 2) NOT NULL,
    category ENUM('sales_earning', 'internal_transfer_in', 'internal_transfer_out', 'withdrawal', 'refund', 'fee_adjustment') NOT NULL,
    reference_type VARCHAR(100) NOT NULL,
    reference_id VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mutations_wallet FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
    INDEX idx_user_mutations (user_id, created_at),
    INDEX idx_reference (reference_type, reference_id)
);
```

### 3.3 Tabel `internal_transfers`
Mencatat transaksi kirim saldo antar pengguna internal:
```sql
CREATE TABLE internal_transfers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    transfer_code VARCHAR(50) NOT NULL UNIQUE,
    sender_id BIGINT UNSIGNED NOT NULL,
    receiver_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    notes VARCHAR(255) NULL,
    status ENUM('success', 'failed', 'reversed') NOT NULL DEFAULT 'success',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transfer_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_transfer_receiver FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_sender_transfers (sender_id),
    INDEX idx_receiver_transfers (receiver_id)
);
```

---

## 4. Analisis Ancaman Kejahatan Siber dan Strategi Proteksi

Layanan keuangan virtual merupakan target utama bot otomatis dan eksploitasi peretas. Berikut adalah pemetaan ancaman serta pertahanan yang wajib diterapkan:

### 4.1 Ancaman 1: Race Condition dan Double Spending
- **Bentuk Serangan:** Bot mengirimkan puluhan permintaan transfer atau pencairan dana pada milidetik yang identik. Sistem yang tidak terkunci membaca saldo yang sama berkali-kali sebelum sempat menguranginya, memungkinkan saldo Rp 100.000 ditarik hingga bernilai berlipat ganda.
- **Strategi Proteksi:**
  1. Wajib menerapkan **Pessimistic Row Locking (`SELECT ... FOR UPDATE`)** di dalam transaksi basis data atomik.
  2. Implementasi logic backend Laravel:
  ```php
  DB::transaction(function () use ($senderId, $receiverId, $amount, $notes) {
      // Baris dompet pengirim dikunci secara eksklusif oleh database
      $senderWallet = Wallet::where('user_id', $senderId)->lockForUpdate()->firstOrFail();
      $receiverWallet = Wallet::where('user_id', $receiverId)->lockForUpdate()->firstOrFail();

      if ($senderWallet->available_balance < $amount) {
          throw new InsufficientBalanceException('Saldo tidak mencukupi.');
      }

      // Potong saldo pengirim
      $beforeSender = $senderWallet->available_balance;
      $senderWallet->available_balance -= $amount;
      $senderWallet->save();

      // Tambah saldo penerima
      $beforeReceiver = $receiverWallet->available_balance;
      $receiverWallet->available_balance += $amount;
      $receiverWallet->save();

      // Catat mutasi pengirim
      WalletMutation::create([
          'wallet_id'      => $senderWallet->id,
          'user_id'        => $senderId,
          'type'           => 'debit',
          'amount'         => $amount,
          'balance_before' => $beforeSender,
          'balance_after'  => $senderWallet->available_balance,
          'category'       => 'internal_transfer_out',
          'reference_type' => 'internal_transfers',
          'reference_id'   => $transferCode,
          'description'    => 'Transfer saldo ke pengguna #' . $receiverId,
      ]);

      // Catat mutasi penerima
      WalletMutation::create([
          'wallet_id'      => $receiverWallet->id,
          'user_id'        => $receiverId,
          'type'           => 'credit',
          'amount'         => $amount,
          'balance_before' => $beforeReceiver,
          'balance_after'  => $receiverWallet->available_balance,
          'category'       => 'internal_transfer_in',
          'reference_type' => 'internal_transfers',
          'reference_id'   => $transferCode,
          'description'    => 'Penerimaan saldo dari pengguna #' . $senderId,
      ]);
  });
  ```

### 4.2 Ancaman 2: Manipulasi Parameter Angka (Negative Value Exploit)
- **Bentuk Serangan:** Penyerang mengirimkan nilai nominal transfer negatif (contoh: `amount = -50000`). Pengurangan bilangan negatif (`saldo - (-50000)`) justru akan menambah saldo penyerang secara ilegal.
- **Strategi Proteksi:**
  1. Validasi form ketat: `rules: ['amount' => 'required|numeric|integer|min:10000|max:100000000']`.
  2. Pengecekan hardcoded: `if ($amount <= 0) { abort(422, 'Nominal tidak valid.'); }`.
  3. Constraint level basis data: `CHECK (available_balance >= 0)` memastikan database server menolak otomatis bila terjadi nilai minus.

### 4.3 Ancaman 3: Pemalsuan Webhook Pembayaran (Fake Callback Attack)
- **Bentuk Serangan:** Penyerang mengirimkan payload HTTP POST palsu ke alamat endpoint webhook buyle.id seolah-olah pembayaran telah lunas.
- **Strategi Proteksi:**
  1. **Validasi Signature SHA-512:** Wajib menghitung ulang hash:
     `hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'))`
     dan mencocokkannya dengan signature key dari Midtrans.
  2. **Reverse API Status Check:** Aplikasi wajib melakukan panggilan balik (query status langsung) ke API endpoint resmi Midtrans untuk memvalidasi apakah status pesanan tersebut benar-benar `settlement` pada server Midtrans sebelum menambahkan saldo virtual pengguna.

### 4.4 Ancaman 4: Pembobolan PIN dan Serangan Brute-Force
- **Bentuk Serangan:** Bot mencoba kombinasi 6 angka PIN transaksi korban secara terus-menerus hingga terbuka.
- **Strategi Proteksi:**
  1. Enkripsi PIN dengan algoritma Bcrypt kuat (`Hash::make($pin)`).
  2. Penghitung percobaan gagal (*Failure Counter*): Jika pengguna salah memasukkan PIN sebanyak 3 kali berturut-turut, kunci akses transfer dompet selama 30 menit (`pin_locked_until = now()->addMinutes(30)`).
  3. Pembatasan laju permintaan (*Rate Limiting*): Maksimal 5 permintaan transfer per menit per alamat IP / sesi login.

### 4.5 Ancaman 5: Pembajakan Akun dan Transaksi Tidak Sah (Account Takeover)
- **Strategi Proteksi:**
  1. Setiap transaksi mutasi keluar selalu memvalidasi token CSRF unik.
  2. Pencatatan alamat IP dan User Agent pada setiap baris mutasi untuk keperluan jejak audit forensik.
  3. Sistem email notification otomatis via SMTP terkirim kepada pemilik akun setiap kali ada transfer keluar, memungkinkan pemilik akun segera menyadari apabila terjadi akses mencurigakan.

---

## 5. Alur Konfirmasi Notifikasi Email (SMTP)

Setiap aksi pada sistem Buyle Pay diintegrasikan langsung dengan layanan SMTP email Buyle.id:

1. **Email Tanda Terima Transfer Pengirim:**
   - Memuat detail: Kode Transfer, Nama & Email Penerima, Nominal Terpotong, Sisa Saldo Terkini, Waktu Transaksi, dan Alamat IP.
2. **Email Pemberitahuan Saldo Masuk Penerima:**
   - Memuat detail: Nama Pengirim, Catatan/Pesan Transfer, Nominal Diterima, Total Saldo Terkini, dan tautan menuju dashboard Buyle Pay.
3. **Email Status Pencairan Dana (Payout):**
   - Mengirim konfirmasi otomatis saat pengajuan disetujui beserta nomor referensi bank dan catatan proses dari admin.

---

## 6. Rencana Tahapan Eksekusi Teknis (Roadmap Implementasi)

Pelaksanaan pembangunan sistem Buyle Pay dapat dibagi menjadi 4 tahapan terkontrol:

### Tahap 1: Fondasi Skema dan Enjin Ledger
- Pembuatan migrasi basis data untuk tabel `wallets`, `wallet_mutations`, dan `internal_transfers`.
- Pembuatan model Eloquent dan relasi antar entitas akun pengguna.
- Pembuatan class service `WalletLedgerService` yang mengelola mutasi debit, kredit, dan penguncian baris basis data.

### Tahap 2: Keamanan PIN dan Otentikasi Transaksi
- Pembuatan fitur pengaturan PIN 6 digit pada profil pengguna.
- Pembuatan middleware verifikasi PIN dan rate limiter anti brute-force.
- Integrasi notifikasi email peringatan keamanan jika PIN salah berulang kali.

### Tahap 3: Antarmuka Dompet dan Transfer Internal
- Desain halaman dashboard Buyle Pay untuk kreator, vendor, dan volunteer.
- Form pencarian pengguna penerima (autocompletion via username/email).
- Modal konfirmasi transfer disertai input PIN yang aman dan responsif di perangkat mobile.

### Tahap 4: Integrasi Modul Pencairan dan Laporan Keuangan
- Penyesuaian modul pencairan dana [Admin Payouts](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/admin/payouts/index.blade.php) dengan tabel saldo terpusat.
- Pembuatan laporan rekonsiliasi berkala antara total saldo virtual seluruh pengguna dengan saldo riil pada akun penampung payment gateway.
