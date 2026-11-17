<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Aktivitas Pengguna - {{ $user->name }} - buyle.id</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #F8FAFC;
      color: #0F172A;
      font-size: 13px;
      line-height: 1.5;
      padding: 24px;
    }
    .print-container {
      max-width: 900px;
      margin: 0 auto;
      background: #FFFFFF;
      border: 1px solid #E2E8F0;
      border-radius: 16px;
      padding: 36px 40px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }
    /* Toolbar */
    .no-print-toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      padding: 12px 20px;
      background: #0F172A;
      border-radius: 12px;
      color: #fff;
    }
    .btn-action {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      border: none;
      font-family: inherit;
    }
    .btn-primary { background: #1EB349; color: #fff; }
    .btn-outline { background: rgba(255,255,255,0.1); color: #fff; }
    .btn-primary:hover { background: #17963d; }

    /* Header document */
    .doc-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      border-bottom: 2px solid #F1F5F9;
      padding-bottom: 20px;
      margin-bottom: 24px;
    }
    .brand-title {
      font-size: 22px;
      font-weight: 800;
      color: #0F172A;
      letter-spacing: -0.02em;
    }
    .brand-title span { color: #1EB349; }
    .doc-subtitle { font-size: 11px; color: #64748B; margin-top: 4px; font-weight: 500; }
    .doc-meta { text-align: right; font-size: 11px; color: #64748B; }
    .doc-meta strong { color: #0F172A; }

    /* Section Cards */
    .section-title {
      font-size: 13px;
      font-weight: 800;
      color: #0F172A;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .section-title::before {
      content: "";
      display: inline-block;
      width: 4px;
      height: 14px;
      background: #1EB349;
      border-radius: 2px;
    }

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 24px;
    }
    .info-card {
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-radius: 12px;
      padding: 16px;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
      border-bottom: 1px dashed #E2E8F0;
      font-size: 12px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #64748B; font-weight: 600; }
    .info-val { color: #0F172A; font-weight: 700; text-align: right; }

    /* Tables */
    .table-wrap {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 24px;
      font-size: 11.5px;
    }
    .table-wrap th {
      background: #0F172A;
      color: #FFFFFF;
      padding: 8px 12px;
      text-align: left;
      font-weight: 700;
      font-size: 11px;
    }
    .table-wrap td {
      padding: 9px 12px;
      border-bottom: 1px solid #F1F5F9;
      color: #334155;
    }
    .table-wrap tr:nth-child(even) td { background: #FAFAFA; }

    .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 50px;
      font-size: 10px;
      font-weight: 700;
    }
    .badge-success { background: #DCFCE7; color: #166534; }
    .badge-info    { background: #E0E7FF; color: #3730A3; }
    .badge-warning { background: #FEF3C7; color: #92400E; }

    /* Print media */
    @media print {
      body { background: #fff; padding: 0; }
      .no-print-toolbar { display: none !important; }
      .print-container {
        border: none;
        box-shadow: none;
        padding: 0;
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

  <div class="print-container">
    {{-- Top Toolbar (Hidden on Print) --}}
    <div class="no-print-toolbar">
      <div>
        <strong>Pratinjau Cetak / Ekspor PDF Pengguna</strong>
        <span style="opacity:.7;font-size:11px;margin-left:8px;">{{ $user->name }} (ID #{{ $user->id }})</span>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.users.show', $user) }}" class="btn-action btn-outline">← Kembali ke Detail</a>
        <a href="{{ route('admin.users.export.single', $user) }}" class="btn-action btn-outline">⬇ Unduh Excel</a>
        <button onclick="window.print()" class="btn-action btn-primary">🖨 Cetak / Simpan PDF</button>
      </div>
    </div>

    {{-- Document Header --}}
    <div class="doc-header">
      <div>
        <h1 class="brand-title">buyle<span>.id</span></h1>
        <div class="doc-subtitle">Dokumen Resmi Aktivitas Pengguna &amp; Data Onboarding</div>
      </div>
      <div class="doc-meta">
        <div>Nomor Akun: <strong>#BUY-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</strong></div>
        <div>Tanggal Cetak: <strong>{{ date('d F Y, H:i') }} WIB</strong></div>
        <div>Status Akun: <strong>{{ ($user->is_active ?? true) ? 'AKTIF' : 'NONAKTIF' }}</strong></div>
      </div>
    </div>

    {{-- Grid 1: Profil & Onboarding --}}
    <div class="grid-2">
      {{-- Card 1: Informasi Akun --}}
      <div class="info-card">
        <div class="section-title">Informasi Akun</div>
        <div class="info-row">
          <span class="info-label">Nama Lengkap</span>
          <span class="info-val">{{ $user->name }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Username</span>
          <span class="info-val">{{ $user->username ? '@' . $user->username : '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Email</span>
          <span class="info-val">{{ $user->email }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">No. WhatsApp / HP</span>
          <span class="info-val">{{ $user->phone ?: '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">NIK / Identitas</span>
          <span class="info-val" style="color:#1EB349;">{{ $user->resolved_nik ?? $user->nik ?? '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Terdaftar Pada</span>
          <span class="info-val">{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</span>
        </div>
      </div>

      {{-- Card 2: Data Onboarding Buyle --}}
      <div class="info-card">
        <div class="section-title">Data Onboarding Buyle</div>
        @php
          $personaLabels = [
              'digital_product'    => 'Produk Digital',
              'service_freelance'  => 'Jasa & Freelance',
              'fnb_resto'          => 'FnB & Resto / Kafe',
              'event_ticket'       => 'Event & Tiket',
              'wedding_invitation' => 'Undangan Digital & Wedding',
              'content_creator'    => 'Content Creator',
              'media_partner'      => 'Media Partner',
              'business_retail'    => 'Bisnis & Ritel',
          ];
          $goalLabels = [
              'sell_digital'    => 'Jual Produk Digital & Karya Kreatif',
              'web_builder'     => 'Bangun Website & Landing Page Profesional',
              'pos_cashier'     => 'Kasir POS & Menu Resto Digital',
              'event_ticketing' => 'Penjualan Tiket Konser & Manajemen Event',
              'digital_wedding' => 'Undangan Digital & Buku Tamu QR',
          ];
        @endphp
        <div class="info-row">
          <span class="info-label">Tipe Bisnis (Persona)</span>
          <span class="info-val">{{ $personaLabels[$user->business_persona] ?? ($user->business_persona ?: 'Belum Memilih') }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Sumber Tahu Buyle</span>
          <span class="info-val">{{ $user->referral_source_type ?: '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Platform / Mitra</span>
          <span class="info-val">{{ $user->media_partner_name ?: ($user->referral_source_name ?: '-') }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Tujuan Utama</span>
          <span class="info-val">{{ $goalLabels[$user->primary_goal] ?? ($user->primary_goal ?: '-') }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Kode Referral</span>
          <span class="info-val">{{ $user->referral_code ?: '-' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Waktu Onboarding</span>
          <span class="info-val">{{ $user->onboarding_completed_at ? \Carbon\Carbon::parse($user->onboarding_completed_at)->format('d M Y H:i') : 'Belum Selesai' }}</span>
        </div>
      </div>
    </div>

    {{-- Ringkasan Angka --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;">
      <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:10px;font-weight:700;color:#166534;text-transform:uppercase;">Total Pesanan</div>
        <div style="font-size:18px;font-weight:800;color:#15803D;margin-top:2px;">{{ $orders->count() }}x</div>
      </div>
      <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:10px;font-weight:700;color:#475569;text-transform:uppercase;">Total Transaksi</div>
        <div style="font-size:18px;font-weight:800;color:#0F172A;margin-top:2px;">Rp {{ number_format($totalSpending, 0, ',', '.') }}</div>
      </div>
      <div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:10px;font-weight:700;color:#1E40AF;text-transform:uppercase;">Total E-Tiket</div>
        <div style="font-size:18px;font-weight:800;color:#1D4ED8;margin-top:2px;">{{ $tickets->count() }} Tiket</div>
      </div>
    </div>

    {{-- Riwayat Order --}}
    <div class="section-title">Riwayat Pesanan ({{ $orders->count() }})</div>
    <table class="table-wrap">
      <thead>
        <tr>
          <th>No. Pesanan</th>
          <th>Tanggal</th>
          <th>Item / Produk</th>
          <th>Total (Rp)</th>
          <th>Status</th>
          <th>Metode</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $o)
        @php
          $statusVal = is_object($o->status) ? ($o->status->value ?? 'pending') : (string)($o->status ?? 'pending');
        @endphp
        <tr>
          <td style="font-weight:700;">#{{ $o->order_number ?? $o->id }}</td>
          <td>{{ $o->created_at ? $o->created_at->format('d/m/Y H:i') : '-' }}</td>
          <td>
            {{ $o->items->map(fn($it) => ($it->product->title ?? 'Produk') . ' (x'.$it->qty.')')->implode(', ') ?: 'Pesanan #'.$o->id }}
          </td>
          <td style="font-weight:700;">Rp {{ number_format($o->total ?? 0, 0, ',', '.') }}</td>
          <td><span class="badge badge-success">{{ strtoupper($statusVal) }}</span></td>
          <td>{{ strtoupper($o->payment->method ?? $o->payment_method ?? '-') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;color:#94A3B8;padding:16px;">Belum ada riwayat pesanan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    {{-- Riwayat E-Tiket --}}
    @if($tickets->isNotEmpty())
    <div class="section-title">Riwayat E-Tiket Masuk Event ({{ $tickets->count() }})</div>
    <table class="table-wrap">
      <thead>
        <tr>
          <th>Kode Tiket</th>
          <th>Nama Event</th>
          <th>Pemegang Tiket</th>
          <th>NIK / Identitas</th>
          <th>Status Tiket</th>
          <th>Check-in</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tickets as $t)
        <tr>
          <td style="font-weight:700;color:#1EB349;">{{ $t->ticket_code }}</td>
          <td>{{ $t->product->title ?? 'Tiket Event' }}</td>
          <td style="font-weight:600;">{{ $t->holder_name }}</td>
          <td>{{ $t->holder_nik ?: '-' }}</td>
          <td><span class="badge badge-info">{{ strtoupper($t->status ?? 'valid') }}</span></td>
          <td>{{ $t->scanned_at ? $t->scanned_at->format('d/m/Y H:i') : 'Belum Digunakan' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif

    {{-- Alamat Pengiriman --}}
    @if($addresses->isNotEmpty())
    <div class="section-title">Daftar Alamat Pengiriman ({{ $addresses->count() }})</div>
    <table class="table-wrap">
      <thead>
        <tr>
          <th>Label</th>
          <th>Penerima</th>
          <th>Alamat Lengkap</th>
          <th>Kota &amp; Provinsi</th>
          <th>Kode Pos</th>
        </tr>
      </thead>
      <tbody>
        @foreach($addresses as $addr)
        <tr>
          <td style="font-weight:700;">{{ $addr->label ?? 'Alamat' }}</td>
          <td>{{ $addr->receiver_name }} ({{ $addr->phone }})</td>
          <td>{{ $addr->full_address }}</td>
          <td>{{ $addr->city }}, {{ $addr->province }}</td>
          <td>{{ $addr->postal_code }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif

    {{-- Footer signature --}}
    <div style="margin-top:36px;border-top:1px solid #E2E8F0;padding-top:16px;display:flex;justify-content:space-between;align-items:center;font-size:10.5px;color:#94A3B8;">
      <div>Dokumen ini digenerate secara otomatis oleh sistem manajemen internal <strong>buyle.id</strong>.</div>
      <div>Halaman 1 / 1</div>
    </div>
  </div>

</body>
</html>
