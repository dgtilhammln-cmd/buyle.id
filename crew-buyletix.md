# 🎫 Dokumentasi Sistem Profil & Portofolio Crew Event (BuyleTix)

> **Status:** Draft Desain Arsitektur & Spesifikasi Fitur  
> **Target:** Ekosistem Tiket & Manajemen Penyelenggaraan Event Buyle.id (`buyle.id/creator/event-attendance`)

---

## 📌 1. Latar Belakang & Visi Produk

Di industri *event management*, salah satu tantangan terbesar bagi Event Organizer (EO) adalah menemukan **crew / volunteer lapangan** yang disiplin, berpengalaman, dan terbukti rekam jejaknya. Sebaliknya, ribuan crew freelance dan volunteer kesulitan membuktikan portofolio mereka karena sertifikat manual rentan hilang atau diragukan keasliannya.

**Visi Sistem Crew BuyleTix:**  
Membangun ekosistem portofolio terverifikasi (*LinkedIn-style for Event Crew*), di mana setiap jam terbang penugasan crew di event yang menggunakan BuyleTix **tercatat dan divalidasi langsung oleh sistem (Verified Badge)**.

---

## 🛡️ 2. Prinsip Isolasi Data (Anti-Bentrok Antar Creator)

Untuk mencegah data saling tertukar atau bocor antar penyelenggara (EO):

1. **Kepemilikan Berbasis `user_id` (Individu)**:
   - Data profil crew dan riwayat pengalaman **melekat pada akun User individu**, bukan pada toko atau akun Creator milik EO tertentu.
   - Crew adalah entitas independen yang bebas bertugas di event Creator A hari ini, dan event Creator B di waktu mendatang.
2. **Hak Akses Ketat (Strict RBAC)**:
   - **Crew**: Memiliki akses penuh (Create, Read, Update, Delete) terhadap profil dan pengalaman manual miliknya sendiri (`WHERE user_id = auth()->id()`).
   - **Creator / EO**: Hanya memiliki hak **Read-Only** untuk melihat profil & ringkasan portofolio crew yang terdaftar di daftar `gate_staff` event milik EO tersebut.
   - Creator **TIDAK BISA** mengubah, menghapus, atau mengedit data pribadi maupun pengalaman crew lain.

---

## 🗄️ 3. Rancangan Skema Database (Proposed Schema)

### A. Tabel `crew_profiles` (Data Pribadi & Lapangan Crew)
Tabel *one-to-one* dengan tabel `users` untuk menyimpan profil spesifik operasional lapangan:

| Field | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `bigint (PK)` | Auto increment |
| `user_id` | `bigint (FK)` | Relasi unik ke `users.id` (Unique, Indexed) |
| `domicile` | `varchar(100)` | Kota/Kabupaten domisili saat ini (misal: *Surabaya, Jawa Timur*) |
| `birth_place` | `varchar(100)` | Tempat lahir (misal: *Malang*) |
| `birth_date` | `date` | Tanggal lahir (sumber kalkulasi usia otomatis oleh sistem) |
| `gender` | `enum('L','P')` | Jenis kelamin |
| `shirt_size` | `enum('S','M','L','XL','XXL','XXXL')` | Ukuran kaos/baju seragam crew lapangan |
| `phone_whatsapp` | `varchar(20)` | Nomor WhatsApp aktif untuk koordinasi panitia |
| `emergency_contact_name` | `varchar(100)` | Nama kontak darurat (Orang Tua / Pasangan / Keluarga) |
| `emergency_contact_phone`| `varchar(20)` | Nomor HP kontak darurat |
| `preferred_divisions` | `json` | Minat/spesialisasi divisi (misal: `["Gate Scanner", "Ticketing Box Office", "Liaison Officer (LO)"]`) |
| `avatar_path` | `varchar(255)` | Foto profil formal/casual lapangan (opsional jika beda dg avatar akun) |
| `created_at`, `updated_at`| `timestamp` | Standar Laravel timestamps |

> 💡 **Kalkulasi Usia Otomatis:**  
> Usia tidak disimpan sebagai angka statis di database, melainkan menggunakan Accessor Laravel:  
> `Carbon::parse($this->birth_date)->age` sehingga setiap pergantian tahun usia otomatis bertambah akurat.

---

### B. Tabel `crew_experiences` (Riwayat Jam Terbang Event)
Tabel *one-to-many* untuk mencatat setiap event yang pernah ditangani crew:

| Field | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `bigint (PK)` | Auto increment |
| `user_id` | `bigint (FK)` | Relasi ke `users.id` pemilik portofolio |
| `source_type` | `enum('buyle_system', 'manual')` | Asal data: otomatis dari sistem atau input mandiri |
| `is_verified` | `boolean` | `true` jika berasal dari event resmi BuyleTix |
| `event_name` | `varchar(200)` | Nama event yang diselenggarakan |
| `organizer_name` | `varchar(150)` | Nama Creator / EO penyelenggara |
| `division_role` | `varchar(100)` | Divisi / jabatan tugas (Gate Scanner, LO, Perlengkapan, dll.) |
| `event_date` | `date / varchar(50)` | Tanggal atau rentang tanggal pelaksanaan event |
| `documentation_photo` | `varchar(255)` | Path 1 foto dokumentasi kegiatan bertugas di venue |
| `description` | `text (nullable)` | Ringkasan tugas atau tanggung jawab yang dijalankan |
| `gate_staff_id` | `bigint (nullable, FK)` | Referensi ke ID `gate_staff` jika berasal dari BuyleTix |
| `product_id` | `bigint (nullable, FK)` | Referensi ke produk tiket event di BuyleTix |
| `created_at`, `updated_at`| `timestamp` | Standar Laravel timestamps |

---

## ⚡ 4. Alur Kerja Dua Model Pengalaman (Experiences)

```
                       ┌───────────────────────────────┐
                       │     PENAMBAHAN PENGALAMAN     │
                       └───────────────┬───────────────┘
                                       │
                ┌──────────────────────┴──────────────────────┐
                ▼                                             ▼
    [1. JALUR AUTO-SYNC BUYLETIX]                  [2. JALUR EXTERNAL MANUAL]
    • Trigger: Di-invite via Gate Staff            • Input mandiri oleh Crew
    • Data event, EO, tanggal terkunci             • Bebas tambah sebanyak-banyaknya
    • Status: "Verified by BuyleTix" (🟢)          • Status: "External Event" (⚪)
    • Crew hanya upload 1 foto menyusul            • Crew bisa edit / hapus mandiri
```

### Jalur 1: Auto-Sync BuyleTix (Sistem Otomatis)
1. **Trigger Penugasan**: Saat seorang Creator/EO menambahkan username crew di tab *Akses Gate Crew* (`gate_staff`), sistem secara otomatis membuat draft record di `crew_experiences` dengan relasi `product_id` dan `gate_staff_id`.
2. **Kunci Integritas Data**: Nama event, nama EO, tanggal acara, dan posisi divisi terisi otomatis dari master data produk tiket Buyle. Field ini **terkunci permanen (*read-only*)** agar kredibilitas sertifikasi tetap terjamin.
3. **Upload Foto Dokumentasi**: Crew dapat membuka menu profilnya kapan saja untuk mengunggah 1 foto dokumentasi saat bertugas di lokasi.

### Jalur 2: Manual External (Riwayat Masa Lalu)
1. Crew dapat mencatatkan pengalaman event di luar ekosistem BuyleTix untuk memperkaya CV (misal: volunteer konser festival musik, turnamen e-sports, pameran expo).
2. Form input ringkas:
   - Nama Event
   - Nama Penyelenggara (EO/Brand)
   - Tanggal Pelaksanaan
   - Divisi / Peran
   - Upload 1 Foto Dokumentasi / Id Card / Sertifikat

---

## 📱 5. Rencana Antarmuka (UI/UX) pada Mode Crew

Ketika pengguna berada di `buyle.id/creator/event-attendance` dan mengaktifkan **Mode Crew**:

1. **Menu Baru di Sidebar**:
   - `Profil & CV Crew` (dengan icon ID-Card / User Badge).
2. **Komponen Layar Profil Crew**:
   - **Header Ringkasan CV**:
     - Foto avatar & nama lengkap.
     - Badge verifikasi akun.
     - Usia dinamis (*"22 Tahun"*), kota domisili, dan total jam terbang (*"Total: 7 Event"*).
   - **Tab 1: Identitas & Kelengkapan Lapangan**:
     - Tempat, Tanggal Lahir (otomatis menghitung umur).
     - Ukuran Baju Seragam (S/M/L/XL/XXL/XXXL).
     - Kontak Darurat (Nama & No. HP).
     - Tombol Simpan Profil.
   - **Tab 2: Portofolio Event & Riwayat Kerja**:
     - Tombol `+ Tambah Pengalaman Eksternal`.
     - Timeline kartu pengalaman event:
       - Badge Hijau `Verified by BuyleTix` untuk event dari Buyle.
       - Thumbnail foto dokumentasi penugasan.
       - Tombol upload / ganti foto dokumentasi.

---

## 🚀 6. Potensi Fitur Lanjutan (Phase 2 Roadmap)

- **Public e-CV / Link Portofolio**:  
  Setiap crew memiliki tautan publik unik (misal: `buyle.id/crew/@username`) yang dapat dilampirkan saat melamar lowongan crew/volunteer di festival besar.
- **Talent Pool Search**:  
  EO yang sedang membutuhkan crew tambahan dapat memfilter database crew berdasarkan kota domisili dan keahlian divisi (misal: filter crew *Gate Scanner di Surabaya yang memiliki jam terbang > 3 event*).
