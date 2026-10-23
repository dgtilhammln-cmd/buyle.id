<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $wedding->event_title }} {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}</title>
    <meta name="description" content="Undangan Pernikahan {{ $wedding->groom_nickname }} & {{ $wedding->bride_nickname }}.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Leaflet.js Interactive Map (OpenStreetMap) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <style>
        :root {
            --primary: #1eb349;
            --gold: #d97706;
            --gold-light: #fef3c7;
            --dark: #0f172a;
            --slate: #334155;
            --bg-cream: #faf7f2;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-cream);
            color: var(--slate);
            line-height: 1.6;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
        }

        .wedding-app-container {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            min-height: 100vh;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.08);
            position: relative;
            padding-bottom: 5rem;
        }

        /* ── Cover Overlay (Buka Undangan) ── */
        .cover-overlay {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            height: 100vh;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.7) 0%, rgba(15, 23, 42, 0.95) 100%), 
                        url('{{ $wedding->cover_photo ? asset("storage/" . $wedding->cover_photo) : "https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80" }}') center/cover no-repeat;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2.5rem 1.5rem;
            color: #ffffff;
            transition: transform 0.8s cubic-bezier(0.77, 0, 0.175, 1), opacity 0.6s ease;
        }

        .cover-overlay.opened {
            transform: translate(-50%, -100%);
            opacity: 0;
            pointer-events: none;
        }

        .cover-tagline {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.25em;
            color: #fcd34d;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cover-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            color: #ffffff;
        }

        .cover-guest-box {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            width: 100%;
        }

        .cover-guest-label {
            font-size: 0.75rem;
            color: #cbd5e1;
            margin-bottom: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .cover-guest-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.02em;
        }

        .btn-open-invite {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #1eb349 0%, #16a34a 100%);
            color: #ffffff;
            border: none;
            padding: 0.85rem 2rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(30, 179, 73, 0.4);
            animation: pulseBtn 2s infinite;
        }

        @keyframes pulseBtn {
            0% { transform: scale(1); }
            50% { transform: scale(1.04); }
            100% { transform: scale(1); }
        }

        /* ── Sections Content ── */
        .hero-section {
            padding: 4rem 1.75rem 2.5rem;
            text-align: center;
            background: radial-gradient(circle at 50% 0%, rgba(217, 119, 6, 0.08) 0%, transparent 70%);
        }

        .section-tag {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 0.5rem;
        }

        .hero-names {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .hero-date {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
        }

        /* Countdown Box */
        .countdown-wrap {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            margin: 2rem 0;
        }

        .countdown-item {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.75rem 0.5rem;
            min-width: 65px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .countdown-num {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1eb349;
            line-height: 1.2;
        }

        .countdown-lbl {
            font-size: 0.65rem;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
        }

        /* QR Guest Card */
        @if($guest)
        .qr-card-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1.5px dashed #86efac;
            border-radius: 20px;
            padding: 1.5rem;
            margin: 1.5rem;
            text-align: center;
        }
        .qr-badge {
            display: inline-block;
            background: #166534;
            color: #fff;
            font-size: 0.68rem;
            font-weight: 800;
            padding: 2px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
        }
        @endif

        /* Couple Section */
        .couple-card {
            padding: 1.5rem;
            text-align: center;
        }

        .couple-avatar {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ffffff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            margin: 0 auto 1rem;
        }

        .couple-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--dark);
        }

        .couple-parents {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.25rem;
            line-height: 1.4;
        }

        /* Event Card */
        .event-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 1.5rem;
            margin: 0 1.5rem 1.5rem;
            text-align: center;
        }

        .event-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 3px 12px;
            border-radius: 999px;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }

        .event-time {
            font-size: 0.875rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.35rem;
        }

        .event-venue {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--dark);
        }

        .event-address {
            font-size: 0.8rem;
            color: #64748b;
            margin: 0.5rem 0 1rem;
        }

        .btn-map {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            padding: 0.5rem 1.25rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #0f172a;
            text-decoration: none;
        }

        /* Form Wish */
        .form-section {
            padding: 1.5rem;
        }

        .input-group {
            margin-bottom: 1rem;
        }

        .input-control {
            width: 100%;
            height: 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0 1rem;
            font-family: inherit;
            font-size: 0.85rem;
            outline: none;
        }

        textarea.input-control {
            height: 90px;
            padding: 0.75rem 1rem;
            resize: vertical;
        }

        /* Wish Stream */
        .wish-stream {
            max-height: 400px;
            overflow-y: auto;
            padding: 0.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .wish-bubble {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem;
        }

        /* Audio Player Float */
        .audio-toggle-btn {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            border: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
            cursor: pointer;
            color: #1eb349;
        }
    </style>
</head>
<body>

    <div class="wedding-app-container">

        {{-- ── 1. COVER OVERLAY (Buka Undangan) ── --}}
        <div class="cover-overlay" id="coverOverlay">
            <div class="cover-tagline">{{ $wedding->event_title }}</div>
            <h1 class="cover-title">{{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}</h1>

            <div class="cover-guest-box">
                <div class="cover-guest-label">Kepada Yth. Bapak/Ibu/Saudara/i:</div>
                <div class="cover-guest-name">{{ $guestName }}</div>
                @if($guest)
                    <div style="font-size:0.75rem; color:#fef08a; margin-top:0.35rem; font-weight:600;">
                        {{ $guest->group_name ?: 'Tamu Undangan' }} &bull; {{ $guest->pax ?: 1 }} Pax
                    </div>
                @endif
            </div>

            <button type="button" class="btn-open-invite" onclick="openInvitation()">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>Buka Undangan</span>
            </button>
        </div>

        {{-- ── 2. HERO & COUNTDOWN ── --}}
        <section class="hero-section">
            <div class="section-tag">{{ $wedding->event_title }}</div>
            <div class="hero-names">{{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}</div>
            <div class="hero-date">
                {{ $wedding->event_date ? $wedding->event_date->isoFormat('dddd, D MMMM Y') : 'Segera' }}
            </div>

            {{-- Countdown Timer --}}
            @if($wedding->event_date)
                <div class="countdown-wrap" id="countdownTimer" data-target="{{ $wedding->event_date->toIso8601String() }}">
                    <div class="countdown-item">
                        <div class="countdown-num" id="cdDays">0</div>
                        <div class="countdown-lbl">Hari</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-num" id="cdHours">0</div>
                        <div class="countdown-lbl">Jam</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-num" id="cdMinutes">0</div>
                        <div class="countdown-lbl">Menit</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-num" id="cdSeconds">0</div>
                        <div class="countdown-lbl">Detik</div>
                    </div>
                </div>
            @endif
        </section>

        {{-- ── 3. SMART GUEST BOOK QR CARD (Jika tamu terdaftar) ── --}}
        @if($guest)
            <div class="qr-card-box">
                <span class="qr-badge">Tiket Masuk &amp; QR Check-in</span>
                <div style="font-weight:800; font-size:1.1rem; color:#0f172a; margin-bottom:0.25rem;">{{ $guest->name }}</div>
                <div style="font-size:0.75rem; color:#166534; margin-bottom:1rem;">Tunjukkan kode QR ini ke meja panitia saat tiba di venue.</div>
                
                {{-- QR Code Image via API --}}
                <div style="background:#fff; padding:1rem; border-radius:14px; display:inline-block; box-shadow:0 4px 12px rgba(0,0,0,0.06); margin-bottom:0.75rem;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($guest->qr_code) }}" alt="QR Code" style="display:block; width:130px; height:130px;">
                </div>
                <div style="font-family:monospace; font-weight:800; font-size:0.95rem; color:#0f172a;">{{ $guest->qr_code }}</div>
                <div style="font-size:0.72rem; color:#64748b; margin-top:2px;">Kuota: {{ $guest->pax ?: 1 }} Orang ({{ $guest->group_name ?: 'Tamu' }})</div>
            </div>
        @endif

        {{-- ── 4. PASANGAN MEMPELAI ── --}}
        <section style="padding:1.5rem 0; border-top:1px solid #f1f5f9;">
            <div class="section-tag" style="text-align:center;">Pasangan Mempelai</div>

            <div class="couple-card">
                <img src="{{ $wedding->groom_photo ? asset('storage/' . $wedding->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80' }}" class="couple-avatar" alt="{{ $wedding->groom_name }}">
                <div class="couple-name">{{ $wedding->groom_name }}</div>
                <div class="couple-parents">{{ $wedding->groom_parents }}</div>
                @if($wedding->groom_instagram)
                    <div style="font-size:0.75rem; color:#0284c7; font-weight:700; margin-top:0.4rem;">{{ $wedding->groom_instagram }}</div>
                @endif
            </div>

            <div style="text-align:center; font-family:'Playfair Display',serif; font-size:1.8rem; font-style:italic; color:var(--gold); margin:0.5rem 0;">&amp;</div>

            <div class="couple-card">
                <img src="{{ $wedding->bride_photo ? asset('storage/' . $wedding->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80' }}" class="couple-avatar" alt="{{ $wedding->bride_name }}">
                <div class="couple-name">{{ $wedding->bride_name }}</div>
                <div class="couple-parents">{{ $wedding->bride_parents }}</div>
                @if($wedding->bride_instagram)
                    <div style="font-size:0.75rem; color:#e11d48; font-weight:700; margin-top:0.4rem;">{{ $wedding->bride_instagram }}</div>
                @endif
            </div>
        </section>

        {{-- ── 5. WAKTU & LOKASI ACARA ── --}}
        <section style="padding:1.5rem 0; border-top:1px solid #f1f5f9;">
            <div class="section-tag" style="text-align:center; margin-bottom:1.25rem;">Rangkaian Acara</div>

            @if($wedding->ceremony_date)
                <div class="event-card">
                    <span class="event-badge">Akad Nikah / Pemberkatan</span>
                    <div class="event-time">{{ $wedding->ceremony_date->isoFormat('dddd, D MMMM Y &bull; HH:mm') }} WIB</div>
                    <div class="event-venue">{{ $wedding->ceremony_location_name }}</div>
                    <div class="event-address">{{ $wedding->ceremony_address }}</div>
                </div>
            @endif

            <div class="event-card">
                <span class="event-badge" style="background:#dcfce7; color:#166534;">Resepsi Pernikahan</span>
                <div class="event-time">{{ $wedding->event_date ? $wedding->event_date->isoFormat('dddd, D MMMM Y &bull; HH:mm') . ' WIB' : '-' }}</div>
                <div class="event-venue">{{ $wedding->event_location_name }}</div>
                <div class="event-address">{{ $wedding->event_address }}</div>

                {{-- Komponen Peta Interaktif (Leaflet OSM) --}}
                <div style="margin: 1.25rem 0 0.5rem; text-align:left;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.45rem;">
                        <span style="font-size:0.75rem; font-weight:700; color:#166534; display:flex; align-items:center; gap:0.35rem;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                            Peta Lokasi Interaktif
                        </span>
                        <span style="font-size:0.68rem; color:#64748b; font-weight:600;">(Bisa digeser &amp; di-zoom)</span>
                    </div>
                    <div id="weddingInteractiveMap" style="width:100%; height:230px; border-radius:14px; border:1.5px solid #cbd5e1; z-index:1; background:#f1f5f9; box-shadow:0 4px 12px rgba(0,0,0,0.05);"></div>
                </div>

                <div style="display:flex; gap:0.5rem; justify-content:center; margin-top:0.75rem; flex-wrap:wrap;">
                    @if($wedding->event_map_url)
                        <a href="{{ $wedding->event_map_url }}" target="_blank" class="btn-map" style="flex:1; justify-content:center;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span>Buka Google Maps</span>
                        </a>
                    @endif
                    <a href="https://waze.com/ul?q={{ urlencode($wedding->event_location_name ?: $wedding->event_address) }}" target="_blank" class="btn-map" style="background:#f8fafc; border-color:#cbd5e1; color:#0284c7;">
                        <span>Navigasi Waze</span>
                    </a>
                </div>
            </div>
        </section>

        {{-- ── 6. AMPLOP DIGITAL (Jika Diaktifkan) ── --}}
        @if($wedding->digital_envelope_enabled)
            <section style="padding:1.5rem; border-top:1px solid #f1f5f9; text-align:center;">
                <div class="section-tag">Tanda Kasih</div>
                <div style="font-weight:800; font-size:1.2rem; color:var(--dark); margin-bottom:0.5rem;">Amplop Digital</div>
                <p style="font-size:0.8rem; color:#64748b; margin-bottom:1.25rem; line-height:1.4;">
                    Doa restu Anda merupakan karunia terindah bagi kami. Namun apabila berkenan memberikan hadiah tanda kasih, Anda dapat mengirimkannya melalui:
                </p>

                @if($wedding->bank_name && $wedding->bank_account_number)
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem; margin-bottom:1rem; text-align:center;">
                        <div style="font-weight:800; font-size:0.9rem; color:#0f172a;">{{ $wedding->bank_name }}</div>
                        <div style="font-size:1.15rem; font-weight:800; font-family:monospace; color:#1eb349; margin:0.4rem 0;" id="bankAccNum">{{ $wedding->bank_account_number }}</div>
                        <div style="font-size:0.75rem; color:#64748b; margin-bottom:0.75rem;">a.n. {{ $wedding->bank_account_name }}</div>
                        <button type="button" onclick="copyAccount('{{ $wedding->bank_account_number }}')" class="btn-map" style="font-size:0.75rem; padding:0.4rem 1rem;">
                            Salin Nomor Rekening
                        </button>
                    </div>
                @endif

                @if($wedding->qris_image)
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem; text-align:center;">
                        <div style="font-weight:700; font-size:0.85rem; color:#0f172a; margin-bottom:0.5rem;">Scan QRIS Pembayaran</div>
                        <img src="{{ asset('storage/' . $wedding->qris_image) }}" alt="QRIS" style="max-width:200px; border-radius:12px; margin-bottom:0.5rem;">
                        <div style="font-size:0.72rem; color:#64748b;">Mendukung BCA, Mandiri, BRI, GoPay, OVO, ShopeePay, DANA</div>
                    </div>
                @endif
            </section>
        @endif

        {{-- ── 7. UCAPAN DOA & RSVP ── --}}
        <section class="form-section" style="border-top:1px solid #f1f5f9;">
            <div class="section-tag" style="text-align:center;">Kirim Doa &amp; Harapan</div>
            <div style="font-weight:800; font-size:1.2rem; color:var(--dark); text-align:center; margin-bottom:1.25rem;">Doa Restu &amp; RSVP</div>

            @if(session('wish_success'))
                <div style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:0.75rem; border-radius:10px; font-size:0.8rem; font-weight:600; margin-bottom:1rem; text-align:center;">
                    {{ session('wish_success') }}
                </div>
            @endif

            <form action="{{ route('wedding.wish', $wedding->slug) }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" name="sender_name" value="{{ $guestName !== 'Tamu Undangan' ? $guestName : '' }}" placeholder="Nama Anda" class="input-control" required>
                </div>
                <div class="input-group">
                    <select name="attendance_status" class="input-control" style="background:#fff;" required>
                        <option value="hadir">Saya akan Hadir</option>
                        <option value="tidak_hadir">Maaf, Tidak Bisa Hadir</option>
                        <option value="ragu">Masih Ragu</option>
                    </select>
                </div>
                <div class="input-group">
                    <textarea name="message" placeholder="Tuliskan doa restu untuk kedua mempelai..." class="input-control" required></textarea>
                </div>
                <button type="submit" class="btn-open-invite" style="width:100%; justify-content:center; animation:none;">
                    Kirim Ucapan &amp; Doa
                </button>
            </form>

            {{-- Wish Stream --}}
            <div style="margin-top:2rem;">
                <div style="font-size:0.85rem; font-weight:700; color:#0f172a; margin-bottom:0.75rem;">
                    Ucapan dari Tamu Undangan ({{ $wishes->count() }})
                </div>
                <div class="wish-stream">
                    @forelse($wishes as $w)
                        <div class="wish-bubble">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem;">
                                <strong style="font-size:0.85rem; color:#0f172a;">{{ $w->sender_name }}</strong>
                                <span style="font-size:0.65rem; padding:2px 8px; border-radius:999px; background:{{ $w->attendance_status === 'hadir' ? '#dcfce7' : '#fef3c7' }}; color:{{ $w->attendance_status === 'hadir' ? '#166534' : '#92400e' }}; font-weight:700;">
                                    {{ ucfirst($w->attendance_status) }}
                                </span>
                            </div>
                            <p style="font-size:0.8rem; color:#475569; line-height:1.4;">"{{ $w->message }}"</p>
                        </div>
                    @empty
                        <div style="text-align:center; padding:1.5rem; color:#94a3b8; font-size:0.8rem;">
                            Jadilah orang pertama yang mengirimkan doa restu!
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer style="text-align:center; padding:2rem 1.5rem; font-size:0.75rem; color:#94a3b8; border-top:1px solid #f1f5f9;">
            Undangan Pernikahan Digital &bull; <strong>buyle.id</strong>
        </footer>

    </div>

    {{-- Audio Player Floating (Optional Wedding Song) --}}
    @if($wedding->music_url)
        <audio id="weddingBgMusic" loop src="{{ $wedding->music_url }}"></audio>
        <button type="button" class="audio-toggle-btn" id="audioToggleBtn" onclick="toggleMusic()">
            <svg id="musicIconPlay" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
        </button>
    @endif

    <script>
        // Buka Undangan
        function openInvitation() {
            document.getElementById('coverOverlay').classList.add('opened');
            
            // Putar musik jika ada
            const music = document.getElementById('weddingBgMusic');
            if (music) {
                music.play().catch(e => console.log('Autoplay blocked'));
            }
        }

        // Toggle Music
        function toggleMusic() {
            const music = document.getElementById('weddingBgMusic');
            if (!music) return;
            if (music.paused) {
                music.play();
                document.getElementById('audioToggleBtn').style.opacity = '1';
            } else {
                music.pause();
                document.getElementById('audioToggleBtn').style.opacity = '0.5';
            }
        }

        // Copy Account
        function copyAccount(accNum) {
            navigator.clipboard.writeText(accNum).then(() => {
                alert('Nomor rekening berhasil disalin!');
            });
        }

        // Countdown Timer
        const cdContainer = document.getElementById('countdownTimer');
        if (cdContainer) {
            const targetTime = new Date(cdContainer.getAttribute('data-target')).getTime();
            function updateCountdown() {
                const now = new Date().getTime();
                const diff = targetTime - now;

                if (diff <= 0) {
                    document.getElementById('cdDays').innerText = '0';
                    document.getElementById('cdHours').innerText = '0';
                    document.getElementById('cdMinutes').innerText = '0';
                    document.getElementById('cdSeconds').innerText = '0';
                    return;
                }

                const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);

                document.getElementById('cdDays').innerText = d;
                document.getElementById('cdHours').innerText = h;
                document.getElementById('cdMinutes').innerText = m;
                document.getElementById('cdSeconds').innerText = s;
            }
            setInterval(updateCountdown, 1000);
            updateCountdown();
        }

        // Inisialisasi Peta Interaktif Leaflet
        document.addEventListener('DOMContentLoaded', function() {
            const mapContainer = document.getElementById('weddingInteractiveMap');
            if (mapContainer && typeof L !== 'undefined') {
                const lat = {{ $wedding->event_latitude ?: -6.2088 }};
                const lng = {{ $wedding->event_longitude ?: 106.8456 }};
                const venueName = "{{ addslashes($wedding->event_location_name ?: 'Venue Resepsi') }}";
                const venueAddr = "{{ addslashes($wedding->event_address ?: '') }}";

                try {
                    const map = L.map('weddingInteractiveMap', {
                        center: [lat, lng],
                        zoom: 15,
                        scrollWheelZoom: false
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    const marker = L.marker([lat, lng]).addTo(map);
                    marker.bindPopup(`<strong>${venueName}</strong><br><small style="color:#64748b;">${venueAddr}</small>`).openPopup();

                    // Re-render map tiles saat cover undangan dibuka
                    window.addEventListener('click', function() {
                        setTimeout(() => map.invalidateSize(), 300);
                    }, { once: true });
                } catch(e) {
                    console.log('Leaflet Map init error:', e);
                }
            }
        });
    </script>
</body>
</html>
