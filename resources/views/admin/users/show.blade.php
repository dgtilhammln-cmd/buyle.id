@extends('layouts.admin')
@section('title', 'Detail Pengguna — ' . $user->name)
@section('page-title', 'Detail Pengguna')
@section('content')

@php
  $personaLabels = [
      'digital_product'    => ['label' => 'Produk Digital', 'desc' => 'E-book, template, preset, karya digital', 'bg' => '#E0F2FE', 'color' => '#0369A1'],
      'service_freelance'  => ['label' => 'Jasa & Freelance', 'desc' => 'Desain, konsultan, dev, jasa kreatif', 'bg' => '#F3E8FF', 'color' => '#7E22CE'],
      'fnb_resto'          => ['label' => 'FnB & Resto / Kafe', 'desc' => 'Kasir POS, menu digital, pesanan resto', 'bg' => '#FFEDD5', 'color' => '#C2410C'],
      'event_ticket'       => ['label' => 'Event & Tiket', 'desc' => 'Konser, seminar, festival, workshop', 'bg' => '#FEF3C7', 'color' => '#B45309'],
      'wedding_invitation' => ['label' => 'Undangan Digital', 'desc' => 'Pernikahan, khitan, RSVP & QR tamu', 'bg' => '#FCE7F3', 'color' => '#BE185D'],
      'content_creator'    => ['label' => 'Content Creator', 'desc' => 'Bio link, monetize link, portofolio', 'bg' => '#DCFCE7', 'color' => '#15803D'],
      'media_partner'      => ['label' => 'Media Partner', 'desc' => 'Media info, promotor, agency event', 'bg' => '#CCFBF1', 'color' => '#0F766E'],
      'business_retail'    => ['label' => 'Bisnis & Ritel', 'desc' => 'B2B, korporasi, toko offline & online', 'bg' => '#F1F5F9', 'color' => '#334155'],
  ];

  $goalLabels = [
      'sell_digital'    => ['title' => 'Jual Produk Digital & Karya Kreatif', 'desc' => 'Monetisasi modul, template, preset atau e-book langsung ke pembeli.'],
      'web_builder'     => ['title' => 'Bangun Website & Landing Page Profesional', 'desc' => 'Katalog online, profil bisnis, dan bio link kustom dengan domain sendiri.'],
      'pos_cashier'     => ['title' => 'Kasir POS & Menu Resto Digital', 'desc' => 'Sistem kasir instan, cetak struk thermal, dan QR menu dine-in / take-away.'],
      'event_ticketing' => ['title' => 'Penjualan Tiket Konser & Manajemen Event', 'desc' => 'E-tiket QR anti-duplikasi dengan scanner gate check-in terintegrasi.'],
      'digital_wedding' => ['title' => 'Undangan Digital & Buku Tamu QR', 'desc' => 'Undangan online modern, RSVP instan, ucapan doa, dan scan tamu resepsi.'],
  ];

  $resolvedNik = $user->resolved_nik ?? $user->nik;
  $isNikFromTicket = empty($user->nik) && !empty($resolvedNik);
@endphp

{{-- HEADER & ACTIONS --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;font-family:'Montserrat',sans-serif;">
  <div style="display:flex;align-items:center;gap:1rem;">
    <a href="{{ route('admin.users.index') }}" style="display:flex;align-items:center;gap:.375rem;color:#94A3B8;text-decoration:none;font-size:.875rem;font-weight:600;">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      All User
    </a>
    <span style="color:#E2E8F0;">/</span>
    <span style="color:#1E293B;font-weight:700;">{{ $user->name }}</span>
  </div>

  {{-- Download & Print Buttons --}}
  <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
    <a href="{{ route('admin.users.export.single', $user) }}" style="display:inline-flex;align-items:center;gap:.4rem;padding:.55rem 1rem;background:#F0FDF4;color:#16A34A;border:1.5px solid #BBF7D0;border-radius:10px;font-size:.8rem;font-weight:700;text-decoration:none;transition:all .18s;">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
      Download Excel
    </a>
    <a href="{{ route('admin.users.export.single', [$user, 'format' => 'csv']) }}" style="display:inline-flex;align-items:center;gap:.4rem;padding:.55rem .9rem;background:#F8FAFC;color:#475569;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.8rem;font-weight:700;text-decoration:none;transition:all .18s;">
      CSV
    </a>
    <a href="{{ route('admin.users.print', $user) }}" target="_blank" style="display:inline-flex;align-items:center;gap:.4rem;padding:.55rem 1.1rem;background:#0F172A;color:#fff;border-radius:10px;font-size:.8rem;font-weight:700;text-decoration:none;transition:all .18s;">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
      Cetak / PDF
    </a>
  </div>
</div>

@if(session('success'))
  <div style="background:#F0FDF4;color:#166534;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;border:1px solid #BBF7D0;font-size:.875rem;font-family:'Montserrat',sans-serif;font-weight:600;">{{ session('success') }}</div>
@endif
@if($errors->any())
  <div style="background:#FEF2F2;color:#991B1B;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;border:1px solid #FCA5A5;font-size:.875rem;font-family:'Montserrat',sans-serif;">{{ $errors->first() }}</div>
@endif

<div style="display:grid;grid-template-columns:340px 1fr;gap:1.5rem;align-items:start;font-family:'Montserrat',sans-serif;">

  {{-- SIDEBAR INFO --}}
  <div style="display:flex;flex-direction:column;gap:1.5rem;">
    {{-- Profile Card --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);text-align:center;">
      @php
        $avatarUrl = null;
        if ($user->avatar) {
            $avatarUrl = str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar);
        } elseif ($user->creatorProfile && $user->creatorProfile->avatar) {
            $cAv = $user->creatorProfile->avatar;
            $avatarUrl = str_starts_with($cAv, 'http') ? $cAv : asset('storage/' . $cAv);
        }
      @endphp

      @if($avatarUrl)
        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" style="width:76px;height:76px;border-radius:50%;object-fit:cover;margin:0 auto 1rem;display:block;box-shadow:0 4px 14px rgba(0,0,0,0.08);border:3px solid #F1F5F9;">
      @else
        <div style="width:76px;height:76px;border-radius:50%;background:linear-gradient(135deg, #1eb349, #a5cf37);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.75rem;margin:0 auto 1rem;box-shadow:0 4px 14px rgba(30,179,73,0.25);">
          {{ strtoupper(substr($user->name,0,1)) }}
        </div>
      @endif
      <h2 style="font-size:1.15rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;">{{ $user->name }}</h2>
      <p style="font-size:.8rem;color:#94A3B8;margin:0 0 1rem;">{{ $user->email }}</p>
      <div style="display:flex;align-items:center;justify-content:center;gap:.375rem;font-size:.75rem;font-weight:700;color:{{ ($user->is_active ?? true) ? '#16A34A' : '#DC2626' }};">
        <span style="width:6px;height:6px;border-radius:50%;background:currentColor;"></span>
        {{ ($user->is_active ?? true) ? 'Akun Aktif' : 'Akun Nonaktif' }}
      </div>
    </div>

    {{-- Informasi Akun --}}
    <div style="background:#fff;border-radius:20px;padding:1.5rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
      <h3 style="font-size:.875rem;font-weight:700;color:#1E293B;margin:0 0 1rem;display:flex;align-items:center;gap:.4rem;">
        <svg width="15" height="15" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Informasi Akun
      </h3>
      @php
        $waNum = $user->phone ?? '';
        $waDisplay = $waNum ? $waNum : '—';
      @endphp
      
      @foreach([
        ['Nama', $user->name],
        ['Email', $user->email],
        ['No. WhatsApp', $waDisplay],
        ['NIK / Identitas', $resolvedNik ?: '—'],
        ['Username', $user->username ? '@'.$user->username : '—'],
        ['Role Pengguna', ucfirst($user->role ?? 'Buyer')],
        ['Login Google', $user->google_id ? 'Ya' : 'Tidak'],
        ['Terdaftar', $user->created_at ? $user->created_at->format('d M Y') : '—'],
        ['Total Order', $orders->count().' transaksi'],
        ['Total E-Tiket', $tickets->count().' tiket'],
      ] as [$label,$val])
      <div style="display:flex;justify-content:space-between;align-items:center;padding:.55rem 0;border-bottom:1px solid #F8FAFC;font-size:.78rem;">
        <span style="color:#94A3B8;font-weight:600;">{{ $label }}</span>
        <span style="color:#1E293B;font-weight:700;text-align:right;">
          @if($label === 'No. WhatsApp' && $user->phone)
            @php
              $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
              if (str_starts_with($cleanPhone, '0')) {
                  $cleanPhone = '62' . substr($cleanPhone, 1);
              }
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" style="color:#1eb349;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
              {{ $val }}
              <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
            </a>
          @elseif($label === 'NIK / Identitas' && $resolvedNik)
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:2px;">
              <span>{{ $val }}</span>
              @if($isNikFromTicket)
                <span style="font-size:.65rem;color:#16A34A;font-weight:700;background:#DCFCE7;padding:1px 6px;border-radius:4px;">Otomatis dari E-Tiket</span>
              @endif
            </div>
          @else
            {{ $val }}
          @endif
        </span>
      </div>
      @endforeach
    </div>

    {{-- Aksi Cepat --}}
    <div style="background:#fff;border-radius:20px;padding:1.5rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
      <h3 style="font-size:.875rem;font-weight:700;color:#1E293B;margin:0 0 1rem;">Aksi Cepat</h3>
      <form action="{{ route('admin.users.toggle', $user) }}" method="POST" style="margin-bottom:.5rem;" onsubmit="return confirm('Apakah Anda yakin ingin {{ ($user->is_active ?? true) ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ $user->name }}?')">
        @csrf
        <button type="submit" style="width:100%;padding:.48rem .75rem;background:{{ ($user->is_active ?? true) ? '#FEE2E2' : '#DCFCE7' }};color:{{ ($user->is_active ?? true) ? '#DC2626' : '#16A34A' }};border:none;border-radius:8px;font-weight:700;cursor:pointer;font-family:inherit;font-size:.785rem;display:flex;align-items:center;justify-content:center;gap:.375rem;">
          @if($user->is_active ?? true)
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            Nonaktifkan Akun
          @else
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            Aktifkan Akun
          @endif
        </button>
      </form>
      <button onclick="document.getElementById('pw-modal').style.display='flex'" style="width:100%;padding:.48rem .75rem;background:#f0fdf4;color:#16a34a;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-family:inherit;font-size:.785rem;display:flex;align-items:center;justify-content:center;gap:.375rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
        Ganti Password
      </button>
    </div>
  </div>

  {{-- MAIN CONTENT (RIGHT COLUMN) --}}
  <div style="display:flex;flex-direction:column;gap:1.5rem;">

    {{-- ══════════════════════════════════════════════════════════
         CARD 1: DATA ONBOARDING BUYLE (Terpisah & Rapi)
         ══════════════════════════════════════════════════════════ --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);border:1.5px solid #F1F5F9;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem;">
        <div style="display:flex;align-items:center;gap:.6rem;">
          <div style="width:36px;height:36px;border-radius:10px;background:#F0FDF4;color:#16A34A;display:flex;align-items:center;justify-content:center;">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </div>
          <div>
            <h3 style="font-size:1rem;font-weight:800;color:#0F172A;margin:0;">Data Onboarding Pengguna</h3>
            <p style="font-size:.74rem;color:#94A3B8;margin:2px 0 0;">Preferensi bisnis, atribusi sumber masuk, dan tujuan utama saat registrasi.</p>
          </div>
        </div>

        @if($user->onboarding_completed_at)
          <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .75rem;border-radius:50px;background:#DCFCE7;color:#15803D;font-size:.72rem;font-weight:700;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            Selesai Onboarding ({{ $user->onboarding_completed_at->format('d M Y, H:i') }})
          </span>
        @else
          <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .75rem;border-radius:50px;background:#FEF3C7;color:#92400E;font-size:.72rem;font-weight:700;">
            <span style="width:6px;height:6px;border-radius:50%;background:currentColor;"></span>
            Belum Menyelesaikan Onboarding
          </span>
        @endif
      </div>

      {{-- Grid Onboarding Items --}}
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:1rem;">
        
        {{-- Item 1: Tipe Bisnis / Persona --}}
        @php
          $pData = $personaLabels[$user->business_persona] ?? null;
        @endphp
        <div style="background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:14px;padding:1.1rem;">
          <div style="font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.4rem;">
            Tipe Bisnis / Aktivitas (Langkah 1)
          </div>
          @if($pData)
            <div style="display:inline-block;padding:.2rem .65rem;border-radius:6px;font-size:.75rem;font-weight:800;background:{{ $pData['bg'] }};color:{{ $pData['color'] }};margin-bottom:.35rem;">
              {{ $pData['label'] }}
            </div>
            <div style="font-size:.78rem;color:#475569;">{{ $pData['desc'] }}</div>
          @else
            <div style="font-size:.85rem;font-weight:700;color:#94A3B8;">Belum memilih persona</div>
          @endif
        </div>

        {{-- Item 2: Sumber Tahu Buyle & Media Partner --}}
        <div style="background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:14px;padding:1.1rem;">
          <div style="font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.4rem;">
            Sumber Tahu Buyle (Langkah 2)
          </div>
          @php
            $srcType = match($user->referral_source_type) {
                'media_partner' => 'Media Partner',
                'social_media'  => 'Media Sosial',
                'word_of_mouth' => 'Teman / Komunitas',
                'ads'           => 'Iklan Digital',
                'search_engine' => 'Google Search',
                default         => $user->referral_source_type ?: null
            };
          @endphp
          @if($srcType || $user->referral_source_name || $user->media_partner_name)
            <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.35rem;flex-wrap:wrap;">
              @if($srcType)
                <span style="padding:.2rem .6rem;border-radius:6px;font-size:.72rem;font-weight:800;background:#E0E7FF;color:#3730A3;">
                  {{ $srcType }}
                </span>
              @endif
              @if($user->media_partner_name)
                <span style="padding:.2rem .6rem;border-radius:6px;font-size:.72rem;font-weight:800;background:#CCFBF1;color:#0F766E;">
                  Partner: {{ $user->media_partner_name }}
                </span>
              @endif
            </div>
            <div style="font-size:.78rem;color:#475569;font-weight:600;">
              Detail: {{ $user->referral_source_name ?: ($user->media_partner_name ?: 'Tidak spesifik') }}
            </div>
          @else
            <div style="font-size:.85rem;font-weight:700;color:#94A3B8;">Belum mengisi sumber tahu</div>
          @endif
        </div>

        {{-- Item 3: Tujuan Utama (Primary Goal) --}}
        @php
          $gData = $goalLabels[$user->primary_goal] ?? null;
        @endphp
        <div style="background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:14px;padding:1.1rem;">
          <div style="font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.4rem;">
            Tujuan Utama Penggunaan (Langkah 3)
          </div>
          @if($gData)
            <div style="font-size:.85rem;font-weight:800;color:#0F172A;margin-bottom:.25rem;">
              {{ $gData['title'] }}
            </div>
            <div style="font-size:.75rem;color:#64748B;line-height:1.4;">{{ $gData['desc'] }}</div>
          @else
            <div style="font-size:.85rem;font-weight:700;color:#94A3B8;">Belum memilih tujuan</div>
          @endif
        </div>

        {{-- Item 4: Afiliasi & Referral Tracker --}}
        <div style="background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:14px;padding:1.1rem;">
          <div style="font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.4rem;">
            Afiliasi &amp; Kode Referral
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.35rem;">
            <span style="font-size:.75rem;color:#64748B;">Kode Akun:</span>
            <span style="font-size:.85rem;font-weight:800;color:#1EB349;">{{ $user->referral_code ?: '—' }}</span>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.75rem;color:#64748B;">Direferensikan Oleh:</span>
            @if($user->referrer)
              <a href="{{ route('admin.users.show', $user->referrer) }}" style="font-size:.78rem;font-weight:700;color:#0F172A;text-decoration:none;">
                {{ $user->referrer->name }} →
              </a>
            @else
              <span style="font-size:.78rem;color:#94A3B8;">Langsung (Tanpa Referral)</span>
            @endif
          </div>
        </div>

      </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         CARD 2: RIWAYAT E-TIKET (TICKET PASSES)
         ══════════════════════════════════════════════════════════ --}}
    @if($tickets->isNotEmpty())
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);border:1.5px solid #F1F5F9;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
        <h3 style="font-size:1rem;font-weight:800;color:#1E293B;margin:0;display:flex;align-items:center;gap:.5rem;">
          <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="10" x2="17" y2="10"/></svg>
          Riwayat E-Tiket Masuk Event ({{ $tickets->count() }})
        </h3>
        <span style="font-size:.75rem;color:#64748B;font-weight:600;">Data identitas &amp; check-in gate</span>
      </div>

      <div style="display:flex;flex-direction:column;gap:.75rem;">
        @foreach($tickets as $t)
        @php
          $isValid = ($t->status === 'valid');
          $isScanned = !is_null($t->scanned_at);
        @endphp
        <div style="display:flex;justify-content:space-between;align-items:center;padding:1rem;border-radius:12px;background:#F8FAFC;border:1px solid #E2E8F0;gap:1rem;flex-wrap:wrap;">
          <div>
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.25rem;">
              <span style="font-weight:800;font-size:.85rem;color:#1EB349;font-family:monospace;">{{ $t->ticket_code }}</span>
              <span style="padding:.15rem .5rem;border-radius:4px;font-size:.68rem;font-weight:700;background:{{ $isValid ? '#DCFCE7' : '#FEE2E2' }};color:{{ $isValid ? '#15803D' : '#991B1B' }};">
                {{ strtoupper($t->status ?? 'valid') }}
              </span>
            </div>
            <div style="font-weight:700;font-size:.82rem;color:#0F172A;">
              {{ $t->product->title ?? 'Tiket Event' }}
            </div>
            <div style="font-size:.75rem;color:#64748B;margin-top:.2rem;">
              Pemegang: <strong>{{ $t->holder_name }}</strong> • NIK/Identitas: <strong>{{ $t->holder_nik ?: '—' }}</strong>
            </div>
          </div>

          <div style="text-align:right;">
            <div style="font-size:.72rem;color:#64748B;">Status Check-in:</div>
            @if($isScanned)
              <div style="font-size:.78rem;font-weight:700;color:#15803D;margin-top:2px;">
                ✓ Ter-scan ({{ $t->scanned_at->format('d/m/Y H:i') }})
              </div>
            @else
              <div style="font-size:.78rem;font-weight:700;color:#D97706;margin-top:2px;">
                Belum Check-in
              </div>
            @endif
            @if($t->order)
              <div style="font-size:.7rem;color:#94A3B8;margin-top:2px;">Order #{{ $t->order->order_number ?? $t->order->id }}</div>
            @endif
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════
         CARD 3: RIWAYAT ORDER
         ══════════════════════════════════════════════════════════ --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
        <h3 style="font-size:1rem;font-weight:800;color:#1E293B;margin:0;">Riwayat Order ({{ $orders->count() }})</h3>
        <span style="font-size:.8rem;font-weight:700;color:#1EB349;">
          Total Transaksi: Rp {{ number_format($totalSpending, 0, ',', '.') }}
        </span>
      </div>

      @forelse($orders as $order)
      @php
        $firstItem = $order->items->first();
        $product = $firstItem?->product;
        $prodImage = null;
        if ($product && $product->image) {
            $prodImage = str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image);
        } elseif ($product && is_array($product->gallery) && count($product->gallery) > 0) {
            $firstGal = $product->gallery[0];
            $prodImage = str_starts_with($firstGal, 'http') ? $firstGal : asset('storage/' . $firstGal);
        }
        
        $itemCount = $order->items->count();
        $prodName = $firstItem ? $firstItem->full_name : 'Order #' . ($order->order_number ?? $order->id);
        if ($itemCount > 1) {
            $prodName .= ' (+ ' . ($itemCount - 1) . ' item lainnya)';
        }

        $statusObj = $order->status instanceof \App\Enums\OrderStatus ? $order->status : \App\Enums\OrderStatus::tryFrom((string)$order->status);
        $statusLabel = $statusObj ? $statusObj->label() : ucfirst((string)($order->status ?? 'Pending'));
        $statusValue = $statusObj ? $statusObj->value : (string)($order->status ?? 'pending');

        $badgeBg = match($statusValue) {
            'pending' => '#FEF3C7',
            'confirmed', 'processing', 'shipped', 'delivered' => '#DCFCE7',
            'cancelled', 'refunded' => '#FEE2E2',
            default => '#F1F5F9'
        };
        $badgeColor = match($statusValue) {
            'pending' => '#92400E',
            'confirmed', 'processing', 'shipped', 'delivered' => '#166534',
            'cancelled', 'refunded' => '#991B1B',
            default => '#475569'
        };
      @endphp
      <div style="display:flex;align-items:center;gap:1rem;padding:.875rem 0;border-bottom:1px solid #F1F5F9;">
        {{-- Foto Produk --}}
        <div style="width:48px;height:48px;border-radius:10px;overflow:hidden;background:#F8FAFC;flex-shrink:0;display:flex;align-items:center;justify-content:center;border:1px solid #E2E8F0;">
          @if($prodImage)
            <img src="{{ $prodImage }}" alt="{{ $prodName }}" style="width:100%;height:100%;object-fit:cover;">
          @else
            <svg width="20" height="20" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
          @endif
        </div>

        {{-- Product Info & ID/Timestamp --}}
        <div style="flex:1;min-width:0;">
          <div style="font-weight:700;color:#1E293B;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $prodName }}">
            {{ $prodName }}
          </div>
          <div style="display:flex;align-items:center;gap:.5rem;margin-top:.2rem;font-size:.75rem;color:#64748B;">
            <span style="font-weight:700;color:#0F172A;">#{{ $order->order_number ?? $order->id }}</span>
            <span>•</span>
            <span>{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}</span>
          </div>
        </div>

        {{-- Price & Status --}}
        <div style="text-align:right;flex-shrink:0;">
          <div style="font-weight:800;color:#1E293B;font-size:.875rem;">
            Rp {{ number_format($order->total ?? $order->total_amount ?? 0, 0, ',', '.') }}
          </div>
          <div style="margin-top:.2rem;">
            <span style="display:inline-block;padding:.15rem .55rem;border-radius:50px;font-size:.7rem;font-weight:700;background:{{ $badgeBg }};color:{{ $badgeColor }};">
              {{ $statusLabel }}
            </span>
          </div>
        </div>
      </div>
      @empty
      <p style="color:#94A3B8;font-size:.875rem;text-align:center;padding:1.5rem 0;">Belum ada riwayat order.</p>
      @endforelse
    </div>

    {{-- ══════════════════════════════════════════════════════════
         CARD 4: ALAMAT PENGIRIMAN
         ══════════════════════════════════════════════════════════ --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
      <h3 style="font-size:1rem;font-weight:800;color:#1E293B;margin:0 0 1.25rem;">Alamat Pengiriman ({{ $addresses->count() }})</h3>
      @forelse($addresses as $addr)
      <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:.875rem;border-radius:12px;background:#F8FAFC;margin-bottom:.625rem;gap:.5rem;">
        <div>
          <div style="font-weight:700;font-size:.875rem;color:#1E293B;">{{ $addr->receiver_name }} <span style="font-weight:400;color:#94A3B8;">— {{ $addr->label ?? 'Alamat' }}</span></div>
          <div style="font-size:.8rem;color:#64748B;margin-top:.25rem;">{{ $addr->full_address }}</div>
          <div style="font-size:.75rem;color:#94A3B8;">{{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}</div>
          
          <div style="display:flex; gap:1rem; align-items:center; margin-top:.5rem;">
              @if($addr->is_default)<span style="font-size:.7rem;color:#16a34a;font-weight:700;display:inline-flex;align-items:center;gap:0.25rem;"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>Utama</span>@endif
              @if($addr->latitude && $addr->longitude)
                  <a href="https://maps.google.com/?q={{ $addr->latitude }},{{ $addr->longitude }}" target="_blank" style="font-size:.75rem;color:#16A34A;text-decoration:none;font-weight:700;display:inline-flex;align-items:center;gap:0.25rem;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>Buka di Google Maps</a>
              @endif
          </div>
        </div>
        <form action="{{ route('admin.users.addresses.destroy', [$user, $addr]) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')" style="flex-shrink:0;">
          @csrf @method('DELETE')
          <button type="submit" style="background:#FEE2E2;color:#DC2626;border:none;border-radius:8px;padding:.35rem .75rem;font-size:.75rem;font-weight:600;cursor:pointer;">Hapus</button>
        </form>
      </div>
      @empty
      <p style="color:#94A3B8;font-size:.875rem;text-align:center;padding:1rem 0;">Belum ada alamat tersimpan.</p>
      @endforelse
    </div>

  </div>
</div>

{{-- PASSWORD MODAL --}}
<div id="pw-modal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);font-family:'Montserrat',sans-serif;">
  <div style="background:#fff;border-radius:20px;padding:2rem;width:100%;max-width:420px;box-shadow:0 24px 64px rgba(0,0,0,0.2);margin:1rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <h3 style="font-size:1.1rem;font-weight:800;color:#1E293B;margin:0;">Ganti Password</h3>
      <button onclick="document.getElementById('pw-modal').style.display='none'" style="background:#F1F5F9;border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem;color:#64748B;">✕</button>
    </div>
    <form action="{{ route('admin.users.password', $user) }}" method="POST">
      @csrf
      <div style="margin-bottom:1rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#475569;margin-bottom:.4rem;">Password Baru</label>
        <input type="password" name="password" required placeholder="Minimal 6 karakter" style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:inherit;">
      </div>
      <div style="margin-bottom:1.5rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#475569;margin-bottom:.4rem;">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required placeholder="Ulangi password baru" style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:inherit;">
      </div>
      <div style="display:flex;gap:.75rem;">
        <button type="button" onclick="document.getElementById('pw-modal').style.display='none'" style="flex:1;padding:.75rem;background:#F1F5F9;color:#475569;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Batal</button>
        <button type="submit" style="flex:2;padding:.75rem;background:#1E293B;color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Simpan</button>
      </div>
    </form>
  </div>
</div>
<script>
document.getElementById('pw-modal').addEventListener('click', function(e){ if(e.target===this) this.style.display='none'; });
@if($errors->any()) document.getElementById('pw-modal').style.display='flex'; @endif
</script>
@endsection
