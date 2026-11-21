# Cetak Biru Arsitektur Buyle Pay (Closed-Loop Virtual Ledger System)

Dokumen ini memuat rancangan arsitektur komprehensif, kepatuhan regulasi, skema basis data buku besar ganda (*double-entry virtual ledger*), alur pergerakan dana (*money flow*), mitigasi kejahatan siber, serta alur notifikasi internal untuk implementasi sistem dompet digital tertutup **Buyle Pay** di ekosistem Buyle.id.

---

## 1. Filosofi dan Konsep Dasar Buyle Pay

### 1.1 Closed-Loop Virtual Ledger
Buyle Pay dirancang sebagai sistem pencatatan buku besar tertutup (*Closed-Loop Virtual Ledger*), serupa dengan benchmark sistem BBO Pay atau saldo dompet tertutup pada platform e-commerce dan super-app kreator.

Dalam arsitektur tertutup ini:
1. **Rekening Penampung Riil (Escrow / Pool Account):** Seluruh dana riil yang masuk dari pembayaran konsumen (QRIS, Virtual Account, Kartu Kredit, E-Wallet) berkumpul di rekening penampung resmi Buyle.id pada payment gateway (Midtrans).
2. **Saldo Virtual (Virtual Liability):** Saldo yang tampil pada antarmuka akun pengguna (kreator, volunteer, vendor, atau pembeli) adalah saldo buku besar virtual (*ledger liability*) yang mencatat hak dana masing-masing pengguna di dalam platform.
3. **Penyelesaian Internal (Internal Settlement):** 
   - Perpindahan dana antar pengguna (misal: Kreator membayar honor kru/vendor) atau pembayaran belanja (misal: Pembeli membayar tiket via saldo Buyle Pay) murni merupakan operasi mutasi baris data atomik di basis data.
   - Tidak ada uang fisik yang keluar ke jaringan perbankan saat transaksi internal berlangsung, sehingga tidak timbul biaya transfer antar bank (*zero gateway fee*).

### 1.2 Keuntungan Strategis Model Ini
- **Bebas Biaya Transfer Antar Akun (Rp 0):** Kreator dapat mendistribusikan honor atau upah ke puluhan volunteer, fotografer, kru event, dan vendor lokal tanpa terpotong biaya transfer antar bank.
- **Penyelesaian Instan (0 Detik):** Perpindahan dana langsung selesai seketika saat 6-digit PIN keamanan divalidasi.
- **Perputaran Dana di Dalam Ekosistem (*High Money Velocity*):** Pengguna yang menerima honor atau cashback saldo dapat langsung membelanjakannya kembali untuk tiket event lain atau produk kreator lain di Buyle.id.
- **Efisiensi Likuiditas Platform:** Dana tunai tetap mengendap aman di rekening penampung resmi Buyle.id selama belum dicairkan ke bank luar, memperkuat likuiditas dan meminimalkan biaya *disbursement* perbankan.

---

## 2. Kepatuhan Regulasi & Batasan Hukum Ekosistem Closed-Loop

Untuk mematuhi kerangka hukum Bank Indonesia (PBI No. 23/6/PBI/2021 tentang Penyelenggara Jasa Pembayaran) serta mencegah potensi pelanggaran operasional:

### 2.1 Ruang Lingkup Tertutup (Closed-Loop Exemption)
1. **Bukan Rekening Giro/Tabungan Umum:** Buyle Pay ditegaskan sebagai dompet ekosistem tertutup. Fitur transfer saldo tidak diperuntukkan bagi transaksi perbankan bebas publik antar perorangan, melainkan dibatasi secara ketat untuk:
   - Pembayaran produk fisik/digital dan tiket event di Buyle.id.
   - Distribusi honor, gaji kru/volunteer event, dan pembayaran vendor resmi proyek Buyle.id.
   - Pengembalian dana (*refund*) resmi dan reward ekosistem.
2. **Ketentuan Layanan (Terms of Service):** Setiap pengguna wajib menyetujui bahwa saldo Buyle Pay bukan simpanan bank yang dijamin LPS dan tidak memberikan bunga simpanan (*0% interest*).

### 2.2 Plafon Saldo Berjenjang (*Tiered Wallet Limits*)
Untuk memitigasi risiko penyalahgunaan dan memenuhi standar kepatuhan FinTech:
- **Tier 1 — Pengguna Standar (Unverified):**
  - Batas Maksimal Saldo Tersimpan: **Rp 2.000.000**
  - Batas Maksimal Transaksi Masuk/Keluar: **Rp 2.000.000 / bulan**
  - Hak Akses: Belanja produk/tiket internal, menerima transfer masuk, menerima top up. Belum diizinkan melakukan penarikan ke rekening bank luar (*Payout Locked*).
- **Tier 2 — Pengguna Terverifikasi (Verified KYC):**
  - Batas Maksimal Saldo Tersimpan: **Rp 20.000.000**
  - Batas Maksimal Transaksi Keluar/Transfer: **Rp 10.000.000 / transaksi** (maksimal Rp 40.000.000 / bulan).
  - Hak Akses: Pembayaran belanja internal, transfer internal penuh, dan penarikan dana ke rekening bank pribadi terverifikasi (*Payout Active*).

---

## 3. Kebijakan Anti-Money Laundering (AML) & Wajib KYC

Layanan saldo virtual rentan disalahgunakan sebagai sarana penampungan uang hasil kejahatan (seperti judi online atau penjualan tiket bodong). Oleh karena itu, diterapkan kontrol ketat:

1. **Wajib KYC Sebelum Penarikan Dana & Transfer Keluar Besar:**
   - Pengguna wajib mengunggah foto KTP/identitas resmi yang masih berlaku serta swafoto (*selfie*).
   - Nama pada akun bank penarikan dana **wajib identik 100%** dengan nama yang tercantum pada identitas KTP yang diverifikasi admin.
2. **Pendeteksian Pola Abnormal (*Velocity & Fraud Checks*):**
   - Transaksi transfer beruntun dalam waktu singkat di luar jam wajar otomatis memicu status peninjauan manual (*flagged for review*).
   - Akun yang baru menerima transfer masuk tidak dapat langsung melakukan *withdrawal* dalam kurun waktu kurang dari 2 jam (*holding protection*).

---

## 4. Arsitektur Alur Pergerakan Dana (Money Flow)

Sistem Buyle Pay mengelola 5 alur pergerakan dana utama:

```
                          ┌──────────────────────────┐
                          │   Konsumen / Pembeli     │
                          └─────────────┬────────────┘
                                        │
                         (1) Bayar Tiket/Produk via Gateway
                                        │
                                        ▼
┌────────────────────────────────────────────────────────────────────────┐
│ Rekening Penampung Riil Buyle.id (Midtrans Escrow Gateway)             │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ Webhook Settlement Terverifikasi
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│                       SISTEM BUYLE PAY LEDGER                          │
│                                                                        │
│  [Escrow Balance] ──(Masa Tahan H+1/H+2)──► [Available Balance]       │
│                                                     │                  │
│       ┌───────────────────────┬─────────────────────┼──────────────┐   │
│       ▼                       ▼                     ▼              ▼   │
│ (2) Belanja        (3) Transfer Internal    (4) Top-Up     (5) Payout  │
│     via Saldo          (Kreator ke Kru)         Mandiri        ke Bank │
└───────┬───────────────────────┬─────────────────────┬──────────────┬───┘
        │                       │                     │              │
        ▼                       ▼                     ▼              ▼
  Pesanan Lunas          Saldo Masuk ke       Saldo Siap Pakai   Admin Review &
  Rp 0 Fee Gateway       Volunteer / Vendor   Rp 0 Biaya         Transfer Bank
```

### 4.1 Alur 1: Uang Masuk dari Penjualan (Inflow Penjualan)
1. Pembeli membayar tiket event (TIX), produk fisik/digital (MKT), kasir resto (POS), atau amplop (AMP) senilai Rp 100.000 melalui payment gateway.
2. Webhook Midtrans terverifikasi mengirimkan sinyal `settlement`.
3. Basis data memisahkan:
   - **Platform Fee / Admin Fee** (Hak pendapatan Buyle.id): Rp 5.000.
   - **Saldo Tertahan Penjual (*Escrow Balance*):** Rp 95.000.
4. **Kebijakan Masa Tahan Dana (*Holding Period*):**
   - **Produk Digital / Traktir / POS:** Dana mengendap di `escrow_balance` selama 1x24 jam atau langsung cair sesuai konfigurasi toko.
   - **Tiket Event / Konser (BuyleTIX):** Seluruh dana penjualan tiket **wajib ditahan di `escrow_balance` hingga H+1 atau H+2 setelah tanggal event selesai**. Hal ini untuk menjamin ketersediaan dana pengembalian (*refund pool*) jika event mendadak dibatalkan atau terjadi sengketa promotor.
5. Setelah masa tahan terlampaui tanpa sengketa, cron job sistem otomatis memindahkan dana dari `escrow_balance` ke `available_balance` (Saldo Aktif).

### 4.2 Alur 2: Belanja Menggunakan Saldo Buyle Pay (*Pay with Buyle Pay*)
1. Pembeli yang memiliki saldo aktif di Buyle Pay memilih metode pembayaran **"Buyle Pay"** saat checkout tiket atau produk.
2. Sistem menampilkan modal verifikasi:
   - Menampilkan total tagihan dan sisa saldo saat ini.
   - Meminta input **6-digit PIN Keamanan**.
3. Jika PIN valid dan saldo mencukupi, transaksi atomik dieksekusi:
   - `available_balance` pembeli didebit sebesar nilai tagihan.
   - `escrow_balance` penjual dikreditkan sebesar nilai bersih setelah dipotong platform fee.
   - Hak platform fee Buyle.id dicatat ke buku besar sistem.
   - Status pesanan seketika berubah menjadi `paid` (lunas 0 detik).
4. **Keuntungan:** Pembeli tidak perlu membuka aplikasi bank/e-wallet eksternal, dan Buyle.id menghemat biaya MDR gateway perbankan.

### 4.3 Alur 3: Transfer Internal Antar Akun (Kreator ke Volunteer / Vendor)
1. Kreator ingin mendistribusikan honor atau upah sebesar Rp 150.000 dari saldo aktifnya ke volunteer atau vendor event.
2. Kreator memasukkan username/email penerima, nominal, catatan transfer, dan 6-digit PIN keamanan.
3. Basis data mengeksekusi mutasi atomik ganda dengan *Pessimistic Row Lock*:
   - Saldo aktif pengirim didebit: -Rp 150.000.
   - Saldo aktif penerima dikreditkan: +Rp 150.000.
4. Kode referensi mutasi unik dicatat permanen di buku besar audit.
5. Notifikasi real-time dikirimkan melalui **Email Resmi** dan **Notifikasi Dashboard Kreator** ke kedua akun.

### 4.4 Alur 4: Top Up Saldo Mandiri (Inflow via Midtrans)
1. Pengguna atau kreator yang saldonya kurang ingin melakukan isi ulang dompet (misal: untuk persiapan membayar vendor panggung).
2. Pengguna memilih menu **"Top Up Buyle Pay"**, memasukkan nominal (minimal Rp 20.000), dan memilih metode pembayaran (QRIS / Virtual Account BCA, Mandiri, BRI, BNI).
3. Snap pop-up Midtrans terbuka. Setelah pembayaran selesai, webhook Midtrans memproses kredensial mutasi:
   - `available_balance` pengguna bertambah instan.
   - Riwayat mutasi dicatat dengan kategori `topup`.

### 4.5 Alur 5: Pencairan Dana Riil ke Bank (Outflow / Withdrawal)
1. Kreator atau volunteer yang akunnya telah terverifikasi (Verified KYC) mengajukan pencairan dana ke rekening bank terdaftar mereka.
2. **Ketentuan Batas & Biaya:**
   - Minimal Penarikan: **Rp 50.000**.
   - Biaya Administrasi Transfer Bank: **Rp 2.500** (dipotong otomatis dari nominal saldo yang ditarik untuk menutup biaya transfer BI-FAST / gateway).
3. Saldo aktif sebesar `nominal + fee` seketika dipindahkan ke `locked_balance` (Saldo Ditarik) agar tidak dapat digunakan ganda saat proses berjalan.
4. Pengajuan masuk ke antarmuka admin di [Admin Payouts](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/admin/payouts/index.blade.php).
5. Setelah transfer bank diselesaikan admin dan bukti transfer diunggah:
   - `locked_balance` dikurangi permanen.
   - Mutasi kategori `withdrawal` dicatat.
   - Notifikasi sukses dikirimkan via email dan dashboard kreator.

---

## 5. Kebijakan Refund, Pembatalan Event, dan Sengketa

### 5.1 Refund Tiket Event Batal (Event Cancellation)
Jika konser atau acara dibatalkan oleh pihak penyelenggara:
1. Karena dana tiket masih tertahan di `escrow_balance` promotor (sesuai aturan holding H+1), saldo belum sempat dipindahkan atau ditarik ke bank luar.
2. Admin platform dapat mengaktifkan fitur *Mass Refund*:
   - Sistem mendebit `escrow_balance` promotor.
   - Sistem mengembalikan dana secara instan ke `available_balance` dompet Buyle Pay masing-masing pembeli (atau via refund gateway).
   - Pembeli dapat langsung membelanjakannya untuk tiket event lain atau mencairkannya ke bank.

### 5.2 Perlindungan Saldo Negatif & Pembekuan Akun (*Frozen Wallet*)
- Database menerapkan aturan `CHECK (available_balance >= 0)`.
- Jika terjadi sengketa penipuan di mana dana sudah terlanjur dicairkan sebagian:
  - Admin memiliki wewenang mengubah status dompet menjadi `frozen` atau `suspended`.
  - Akses transfer keluar, checkout, dan penarikan dana seketika terkunci otomatis.
  - Setiap dana masuk baru akan diprioritaskan untuk menutup kewajiban sengketa (*recovery offset*).

---

## 6. Skema Basis Data (Double-Entry Virtual Ledger)

Sistem wajib menggunakan struktur tabel buku besar mutasi ganda (*double-entry*) yang tak dapat diubah (*immutable*):

### 6.1 Tabel `wallets`
Menyimpan ringkasan posisi saldo terkini, status verifikasi, dan keamanan PIN:
```sql
CREATE TABLE wallets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    tier ENUM('standard', 'verified_kyc') NOT NULL DEFAULT 'standard',
    available_balance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    escrow_balance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    locked_balance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    status ENUM('active', 'frozen', 'suspended') NOT NULL DEFAULT 'active',
    pin_hash VARCHAR(255) NULL,
    pin_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    pin_locked_until TIMESTAMP NULL,
    pin_reset_token VARCHAR(100) NULL,
    pin_reset_expires_at TIMESTAMP NULL,
    transfer_cooldown_until TIMESTAMP NULL,
    kyc_submitted_at TIMESTAMP NULL,
    kyc_verified_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_wallets_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT chk_available_balance CHECK (available_balance >= 0),
    CONSTRAINT chk_escrow_balance CHECK (escrow_balance >= 0),
    CONSTRAINT chk_locked_balance CHECK (locked_balance >= 0)
);
```

### 6.2 Tabel `wallet_mutations`
Mencatat seluruh mutasi debit dan kredit secara permanen (jejak audit tak dapat diubah):
```sql
CREATE TABLE wallet_mutations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    wallet_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('credit', 'debit') NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    balance_before DECIMAL(15, 2) NOT NULL,
    balance_after DECIMAL(15, 2) NOT NULL,
    category ENUM(
        'sales_earning',
        'topup',
        'purchase',
        'internal_transfer_in',
        'internal_transfer_out',
        'withdrawal',
        'withdrawal_fee',
        'refund',
        'fee_adjustment'
    ) NOT NULL,
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

### 6.3 Tabel `internal_transfers`
Mencatat transaksi kirim saldo antar pengguna internal beserta token idempotensi:
```sql
CREATE TABLE internal_transfers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    transfer_code VARCHAR(50) NOT NULL UNIQUE,
    idempotency_key VARCHAR(100) NOT NULL UNIQUE,
    sender_id BIGINT UNSIGNED NOT NULL,
    receiver_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    notes VARCHAR(255) NULL,
    status ENUM('success', 'failed', 'reversed') NOT NULL DEFAULT 'success',
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transfer_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_transfer_receiver FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_sender_transfers (sender_id),
    INDEX idx_receiver_transfers (receiver_id)
);
```

### 6.4 Tabel `wallet_topups`
Mencatat transaksi permintaan isi ulang saldo via gateway:
```sql
CREATE TABLE wallet_topups (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    topup_code VARCHAR(50) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    admin_fee DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    total_paid DECIMAL(15, 2) NOT NULL,
    payment_method VARCHAR(50) NULL,
    payment_status ENUM('pending', 'paid', 'expired', 'failed') NOT NULL DEFAULT 'pending',
    midtrans_snap_token VARCHAR(255) NULL,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_topups_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 7. Analisis Ancaman Kejahatan Siber & Protokol Pertahanan

### 7.1 Race Condition dan Double Spending
- **Bentuk Serangan:** Bot mengirimkan puluhan permintaan transfer atau pencairan dana pada milidetik yang identik sebelum database sempat mengurangi saldo.
- **Strategi Proteksi:**
  1. Wajib menggunakan **Pessimistic Row Locking (`SELECT ... FOR UPDATE`)** di dalam transaksi basis data atomik.
  2. Implementasi logic backend Laravel:
  ```php
  DB::transaction(function () use ($senderId, $receiverId, $amount, $notes, $idempotencyKey) {
      // 1. Verifikasi Idempotency Key (cegah pengiriman ganda)
      $existing = InternalTransfer::where('idempotency_key', $idempotencyKey)->first();
      if ($existing) {
          return $existing;
      }

      // 2. Kunci baris dompet pengirim & penerima secara eksklusif
      $senderWallet = Wallet::where('user_id', $senderId)->lockForUpdate()->firstOrFail();
      $receiverWallet = Wallet::where('user_id', $receiverId)->lockForUpdate()->firstOrFail();

      // 3. Validasi status akun dan masa pendinginan transfer (cool-down)
      if ($senderWallet->status !== 'active') {
          throw new \Exception('Akses dompet pengirim sedang dibatasi.');
      }
      if ($senderWallet->transfer_cooldown_until && now()->lt($senderWallet->transfer_cooldown_until)) {
          throw new \Exception('Akun dalam masa pendinginan keamanan setelah reset PIN. Transfer belum diizinkan.');
      }
      if ($senderWallet->available_balance < $amount) {
          throw new \Exception('Saldo aktif tidak mencukupi.');
      }

      // 4. Potong saldo pengirim
      $beforeSender = $senderWallet->available_balance;
      $senderWallet->available_balance -= $amount;
      $senderWallet->save();

      // 5. Tambah saldo penerima
      $beforeReceiver = $receiverWallet->available_balance;
      $receiverWallet->available_balance += $amount;
      $receiverWallet->save();

      // 6. Catat mutasi pengirim
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
          'ip_address'     => request()->ip(),
          'user_agent'     => request()->userAgent(),
      ]);

      // 7. Catat mutasi penerima
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
          'ip_address'     => request()->ip(),
          'user_agent'     => request()->userAgent(),
      ]);
  });
  ```

### 7.2 Manipulasi Parameter Angka (Negative Value Exploit)
- **Strategi Proteksi:**
  1. Validasi form ketat: `rules: ['amount' => 'required|numeric|integer|min:10000|max:10000000']`.
  2. Pengecekan hardcoded: `if ($amount <= 0) { abort(422, 'Nominal tidak valid.'); }`.
  3. Constraint level basis data: `CHECK (available_balance >= 0)`.

### 7.3 Pemalsuan Webhook Pembayaran (Fake Callback Attack)
- **Strategi Proteksi:**
  1. **Validasi Signature SHA-512:** Menghitung ulang hash:
     `hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'))`
     dan mencocokkannya dengan signature key dari payload Midtrans.
  2. **Reverse API Status Check:** Sistem melakukan panggilan balik (*status query*) langsung ke endpoint server resmi Midtrans sebelum mengkreditkan saldo virtual pengguna.

### 7.4 Pembobolan PIN dan Serangan Brute-Force
- **Strategi Proteksi:**
  1. Enkripsi PIN dengan algoritma Bcrypt kuat (`Hash::make($pin)`).
  2. Penghitung percobaan gagal: Jika salah memasukkan PIN sebanyak 3 kali berturut-turut, akses dompet otomatis dikunci selama 30 menit (`pin_locked_until = now()->addMinutes(30)`).
  3. Pembatasan laju permintaan (*Rate Limiting*): Maksimal 5 permintaan transfer per menit per sesi IP.

### 7.5 Alur Lupa PIN & Periode Pendinginan (*Cool-down Period*)
- Jika pengguna lupa PIN:
  1. Pengguna meminta tautan reset PIN melalui menu dompet.
  2. Sistem mengirimkan tautan verifikasi bertoken rahasia ke **Email Resmi terdaftar** (kedaluwarsa dalam 15 menit).
  3. Setelah pengguna berhasil membuat PIN baru melalui tautan email, sistem mengaktifkan **Masa Pendinginan Keamanan (Cool-down) selama 12–24 jam** (`transfer_cooldown_until = now()->addHours(24)`).
  4. Selama masa pendinginan ini, akun dapat menerima saldo masuk, tetapi **transfer keluar dan penarikan dana dibekukan sementara**. Hal ini memberikan waktu bagi pemilik asli akun untuk melapor ke admin jika emailnya baru saja dibajak.

---

## 8. Sistem Notifikasi Dual-Channel (Email Resmi & Dashboard Creator)

Untuk menjaga kenyamanan pengguna tanpa bergantung pada saluran pesan pihak ketiga yang rawan spam, Buyle Pay menggunakan integrasi **Email SMTP Resmi** dan **Notifikasi In-App di Dashboard Creator**:

### 8.1 Saluran 1: Email Resmi (SMTP)
Terkirim otomatis secara real-time pada kondisi:
1. **Email Tanda Terima Transfer Keluar:**
   - Dikirim ke pengirim: Kode Transfer, Nama & Email Penerima, Nominal Terpotong, Sisa Saldo Terkini, Waktu Transaksi, dan Alamat IP.
2. **Email Pemberitahuan Saldo Masuk:**
   - Dikirim ke penerima: Nama Pengirim, Catatan Transfer, Nominal Diterima, Total Saldo Terkini, dan tautan akses dashboard dompet.
3. **Email Status Pencairan Dana (Payout Approved / Rejected):**
   - Memuat status transfer bank, nomor referensi mutasi perbankan, dan lampiran bukti transfer.
4. **Email Peringatan Keamanan (Security Alert):**
   - Dikirim seketika jika PIN salah 3 kali berturut-turut atau jika ada permintaan reset PIN baru.

### 8.2 Saluran 2: Notifikasi In-App di Dashboard Creator
Tampil langsung pada lonceng notifikasi dan feed aktivitas dashboard kreator:
1. Notifikasi badge hijau saat saldo penjualan event/produk berhasil dicairkan dari `escrow` ke saldo aktif.
2. Notifikasi penerimaan transfer saldo dari kreator lain atau hasil pembayaran kru/volunteer.
3. Notifikasi konfirmasi pembayaran saat belanja produk menggunakan Buyle Pay.
4. Notifikasi status verifikasi KYC (Disetujui / Perlu Revisi Dokumen).

---

## 9. Rencana Tahapan Eksekusi Teknis (Roadmap Implementasi)

Pembangunan sistem Buyle Pay dibagi menjadi 4 fase terstruktur:

### Fase 1: Fondasi Skema, Model, dan Enjin Ledger
- Migrasi tabel basis data: `wallets`, `wallet_mutations`, `internal_transfers`, dan `wallet_topups`.
- Pembuatan class service `WalletLedgerService` lengkap dengan *Pessimistic Locking* dan *Idempotency Handler*.
- Seeder inisialisasi dompet otomatis untuk seluruh pengguna aktif yang sudah terdaftar.

### Fase 2: Fitur Keamanan PIN, Reset PIN, dan KYC
- Form pengaturan dan penggantian 6-digit PIN transaksi pada pengaturan profil.
- Alur verifikasi OTP/token reset PIN via email disertai *cool-down period*.
- Modul unggah KTP dan review verifikasi KYC pada panel Admin Buyle.id.

### Fase 3: Antarmuka Dompet, Transfer Antar Akun, dan Top Up
- Desain antarmuka dashboard Buyle Pay yang responsif di perangkat mobile dan desktop.
- Modal transfer saldo antar pengguna disertai autocomplete pencarian username/email penerima.
- Integrasi Snap Midtrans untuk fitur Top Up saldo mandiri.

### Fase 4: Integrasi Checkout (*Pay with Buyle Pay*) dan Pencairan Terpusat
- Penambahan opsi pembayaran "Buyle Pay" pada form checkout produk dan tiket event BuyleTIX.
- Penyempurnaan modul pencairan dana [Admin Payouts](file:///c:/Users/dgtil/Downloads/PENTING/HVM%20Digital/CLIENT%20Local%20WEB%20&%20Deploy/buyle.id%20ex%20alaatrumah/resources/views/admin/payouts/index.blade.php) yang memotong saldo dari `locked_balance`.
- Fitur audit rekonsiliasi antara total saldo liabilitas virtual dengan saldo fisik pada penampung payment gateway.
