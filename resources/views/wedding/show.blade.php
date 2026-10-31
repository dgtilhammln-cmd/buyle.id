<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ $wedding->event_title }} &bull; {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}
    </title>
    <meta name="description"
        content="Undangan Pernikahan Digital {{ $wedding->groom_nickname }} & {{ $wedding->bride_nickname }}.">
    <meta property="og:title"
        content="The Wedding of {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}">
    <meta property="og:description"
        content="Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir di hari bahagia kami.">
    <meta property="og:image" content="{{ asset('images/wedding/marmer.png') }}">
    <meta property="og:type" content="website">
    <!-- Cegah mobile browser auto-detect angka sebagai nomor telepon/link -->
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@400;500;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400;1,600&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #06080c;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* ── Phone Frame Wrapper ── */
        .phone-wrap {
            position: relative;
            width: 100%;
            max-width: 430px;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
        }

        @media (min-width: 500px) {
            .phone-wrap {
                height: min(880px, 96vh);
                border-radius: 44px;
                box-shadow: 0 30px 100px rgba(0, 0, 0, 0.9), 0 0 0 10px #101318;
            }
        }

        /* ── Fixed Marble Background with Ken Burns ── */
        .bg-marble {
            position: absolute;
            inset: -10%;
            background-image: url("{{ asset('images/wedding/marmer.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            animation: kenBurns 18s ease-in-out infinite alternate;
            z-index: 0;
        }

        @keyframes kenBurns {
            0% {
                transform: scale(1.0) translate(0, 0);
            }

            33% {
                transform: scale(1.06) translate(-2%, 1%);
            }

            66% {
                transform: scale(1.04) translate(2%, -1%);
            }

            100% {
                transform: scale(1.08) translate(0, 2%);
            }
        }

        /* ── Cover Layer ── */
        #coverLayer {
            position: absolute;
            inset: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 5rem 2rem 3.5rem;
            text-align: center;
            transition: transform 0.9s cubic-bezier(0.77, 0, 0.175, 1), opacity 0.7s ease;
        }

        #coverLayer.hide {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }

        /* ── Simple Elegant Centered Names ── */
        .cover-hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 1.5rem 0.5rem;
        }

        .cover-eyebrow {
            font-family: 'Cinzel', serif;
            font-size: 0.72rem;
            letter-spacing: 0.38em;
            color: rgba(255, 255, 255, 0.75);
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }

        .cover-names {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.2rem;
        }

        .c-name {
            font-family: 'Cinzel', serif;
            font-size: 2.35rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            line-height: 1.25;
            text-shadow: 0 4px 24px rgba(0, 0, 0, 0.85);
        }

        .c-amp {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: 1.7rem;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1;
            margin: 0.25rem 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
        }

        /* ── Fullscreen Looping Video ── */
        .video-wrapper {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            background: #06080c;
            overflow: hidden;
        }

        .wedding-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Cover guest card */
        .guest-card {
            background: rgba(8, 10, 16, 0.68);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 20px;
            padding: 1.2rem 1.8rem;
            width: 100%;
            max-width: 320px;
        }

        .guest-label {
            font-size: 0.65rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 0.4rem;
            font-weight: 500;
        }

        .guest-name {
            font-family: 'Cinzel', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.06em;
            margin-bottom: 0.3rem;
        }

        .guest-pax {
            font-size: 0.67rem;
            color: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.08);
            border-radius: 999px;
            padding: 2px 10px;
            display: inline-block;
        }

        .btn-open {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.85rem 2.4rem;
            background: #ffffff;
            color: #06080c;
            font-family: 'Cinzel', serif;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            box-shadow: 0 8px 28px rgba(255, 255, 255, 0.3);
            transition: all 0.22s ease;
        }

        .btn-open:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 36px rgba(255, 255, 255, 0.45);
        }

        .cover-hint {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 0.7rem;
            letter-spacing: 0.06em;
        }

        /* ── Content Layer (video fullscreen) ── */
        #contentLayer {
            position: absolute;
            inset: 0;
            z-index: 50;
            display: none;
        }

        #contentLayer.show {
            display: block;
        }

        /* ── Bottom Floating Controls ── */
        .bottom-controls {
            position: absolute;
            bottom: 16px;
            left: 16px;
            right: 16px;
            z-index: 80;
            display: flex;
            align-items: center;
            justify-content: space-between;
            pointer-events: none;
        }

        .btn-sound {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(8, 10, 16, 0.75);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            pointer-events: auto;
            transition: all 0.2s;
        }

        .btn-open-drawer {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1.1rem;
            border-radius: 999px;
            background: rgba(8, 10, 16, 0.75);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #ffffff;
            font-family: 'Cinzel', serif;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            cursor: pointer;
            pointer-events: auto;
            transition: all 0.2s;
        }

        .btn-open-drawer:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* ── Bottom Sheet Drawer ── */
        .drawer-overlay {
            position: absolute;
            inset: 0;
            z-index: 200;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.35s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .drawer-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .drawer-sheet {
            width: 100%;
            max-height: 86%;
            background: rgba(10, 13, 20, 0.97);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 26px 26px 0 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transform: translateY(100%);
            transition: transform 0.42s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .drawer-overlay.open .drawer-sheet {
            transform: translateY(0);
        }

        /* Scrollbar minimalis dark/gold theme */
        .drawer-body::-webkit-scrollbar {
            width: 3px;
        }

        .drawer-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .drawer-body::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 999px;
        }

        .drawer-body::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .drawer-body {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
        }

        .drawer-pill-wrap {
            padding: 0.85rem 1.4rem 0;
            flex-shrink: 0;
            background: rgba(10, 13, 20, 0.97);
        }

        .drawer-pill {
            width: 42px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 999px;
            margin: 0 auto 1rem;
            cursor: pointer;
        }

        /* Sticky header dalam drawer */
        .drawer-head {
            position: sticky;
            top: 0;
            z-index: 10;
            background: rgba(10, 13, 20, 0.98);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 1.4rem 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .drawer-head-title {
            font-family: 'Cinzel', serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #ffffff;
        }

        .btn-close-drawer {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Scrollable body drawer */
        .drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 0.85rem 1.4rem 2.5rem;
        }

        .d-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 1.2rem;
            margin-bottom: 1rem;
        }

        .d-card-head {
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: 'Cinzel', serif;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #ffffff;
            margin-bottom: 0.85rem;
        }

        /* Waktu & Lokasi event rows */
        .event-label {
            font-family: 'Cinzel', serif;
            font-size: 0.62rem;
            color: rgba(255, 255, 255, 0.45);
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-weight: 600;
            margin-bottom: 6px;
        }

        /* 3-baris proporsional: tanggal / jam / lokasi masing-masing 1 baris */
        .event-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-family: 'Cinzel', serif;
            font-size: 0.82rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.4;
            margin-bottom: 7px;
        }

        .event-row svg {
            flex-shrink: 0;
            margin-top: 2px;
            opacity: 0.75;
        }

        .event-row:last-child {
            margin-bottom: 0;
        }

        .event-row .event-subtext {
            display: block;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.7rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.55);
            margin-top: 2px;
            line-height: 1.4;
            letter-spacing: normal;
        }

        .event-divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            margin: 0.9rem 0;
        }

        /* Nomor rekening — paksa putih, blokir auto-link biru pada mobile Safari / Android */
        .acct-number,
        .acct-number *,
        a[href^="tel"],
        a[href^="tel"]:visited,
        a[href^="tel"]:hover,
        a[href^="tel"]:active {
            font-family: monospace !important;
            font-size: 1.15rem !important;
            font-weight: 800 !important;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
            text-decoration: none !important;
            border-bottom: none !important;
            user-select: text;
        }

        .acct-number-wrap {
            margin: 0.35rem 0 0.5rem;
            display: block;
        }

        .d-input {
            width: 100%;
            height: 40px;
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 0 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.8rem;
            color: #ffffff;
            outline: none;
            margin-bottom: 0.7rem;
        }

        .d-input:focus {
            border-color: #ffffff;
        }

        option {
            background: #10131a;
        }

        .d-textarea {
            width: 100%;
            min-height: 72px;
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 10px 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.8rem;
            color: #ffffff;
            outline: none;
            resize: vertical;
            margin-bottom: 0.7rem;
        }

        .d-textarea:focus {
            border-color: #ffffff;
        }

        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            background: #ffffff;
            color: #06080c;
            font-family: 'Cinzel', serif;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-submit:hover {
            opacity: 0.9;
        }

        .btn-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.46rem 1rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.28);
            color: #ffffff;
            background: rgba(255, 255, 255, 0.07);
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-pill:hover {
            background: #ffffff;
            color: #06080c;
        }

        /* Geolocation city input wrapper */
        .city-input-wrap {
            position: relative;
            margin-bottom: 0.7rem;
        }

        .city-input-wrap .d-input {
            margin-bottom: 0;
            padding-right: 44px;
        }

        .btn-detect-loc {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.55);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .btn-detect-loc:hover {
            color: #ffffff;
        }

        .btn-detect-loc.detecting {
            animation: spinLoc 1s linear infinite;
        }

        @keyframes spinLoc {
            100% {
                transform: translateY(-50%) rotate(360deg);
            }
        }

        .wish-box {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 11px;
            padding: 0.7rem 0.85rem;
            margin-bottom: 0.5rem;
        }

        .wish-sender {
            font-weight: 700;
            font-size: 0.78rem;
            color: #fff;
        }

        .wish-time {
            font-size: 0.62rem;
            color: rgba(255, 255, 255, 0.45);
        }

        .wish-msg {
            font-size: 0.73rem;
            color: rgba(255, 255, 255, 0.82);
            line-height: 1.4;
            margin-top: 2px;
        }

        /* ── QR Ticket ── */
        .qr-wrap {
            background: #fff;
            border-radius: 12px;
            padding: 8px;
            display: inline-block;
            margin: 0.6rem 0;
        }

        /* ── Toast ── */
        #toast {
            position: fixed;
            top: 22px;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            color: #06080c;
            padding: 8px 20px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.74rem;
            font-family: 'Cinzel', serif;
            letter-spacing: 0.1em;
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.5);
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }
    </style>
</head>

<body>

    <div class="phone-wrap" id="phoneWrap">

        <!-- ① Fixed Marble Background with Ken Burns animation -->
        <div class="bg-marble"></div>

        <!-- ② COVER LAYER -->
        <div id="coverLayer">
            <!-- Simple elegant centered names -->
            <div class="cover-hero">
                <div class="cover-eyebrow">The Wedding Celebration of</div>
                <div class="cover-names">
                    <span class="c-name">{{ strtoupper($wedding->groom_nickname ?: 'NAZIL') }}</span>
                    <span class="c-amp">&amp;</span>
                    <span class="c-name">{{ strtoupper($wedding->bride_nickname ?: 'FINDI') }}</span>
                </div>
            </div>

            <!-- Guest Card -->
            <div class="guest-card">
                <div class="guest-label">Kepada Yth. Bapak/Ibu/Saudara/i:</div>
                <div class="guest-name">{{ $guestName }}</div>
                @if($guest)
                    <div class="guest-pax">{{ $guest->group_name ?: 'Tamu Undangan' }} &bull; {{ $guest->pax ?: 1 }} Pax
                    </div>
                @else
                    <div class="guest-pax">Tamu Undangan Terhormat</div>
                @endif
            </div>

            <!-- Open Button -->
            <div>
                <button type="button" class="btn-open" onclick="openInvitation()">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2"
                        viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                    <span>Buka Undangan</span>
                </button>
                <div class="cover-hint">Ketuk untuk membuka &amp; memutar video</div>
            </div>
        </div>

        <!-- ③ CONTENT LAYER — fullscreen looping video -->
        <div id="contentLayer">
            <div class="video-wrapper">
                <video id="weddingVideo" src="{{ asset('videos/Undangan_Findi_Nazil_v7.mp4') }}" class="wedding-video"
                    playsinline loop preload="auto"></video>
            </div>
        </div>

        <!-- ④ Bottom Controls (visible after open) -->
        <div class="bottom-controls" id="bottomControls" style="display:none;">
            <button type="button" class="btn-sound" id="btnSound" onclick="toggleVideoMute()" title="Suara">
                <!-- Sound ON icon (default) -->
                <svg id="iconSoundOn" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07" />
                </svg>
                <!-- Sound OFF icon (hidden by default) -->
                <svg id="iconSoundOff" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" style="display:none;">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                    <line x1="23" y1="9" x2="17" y2="15" />
                    <line x1="17" y1="9" x2="23" y2="15" />
                </svg>
            </button>
            <button type="button" class="btn-open-drawer" onclick="openDrawer()">
                <span>Detail Acara</span>
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>

        <!-- ⑤ Bottom Sheet Drawer -->
        <div class="drawer-overlay" id="drawerOverlay" onclick="onDrawerBg(event)">
            <div class="drawer-sheet">
                <!-- Pill handle (outside sticky head, scrolls away) -->
                <div class="drawer-pill-wrap">
                    <div class="drawer-pill" onclick="closeDrawer()"></div>
                </div>

                <!-- STICKY HEADER -->
                <div class="drawer-head">
                    <div class="drawer-head-title">Informasi &amp; Kehadiran</div>
                    <button type="button" class="btn-close-drawer" onclick="closeDrawer()">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- SCROLLABLE BODY -->
                <div class="drawer-body">

                    {{-- VIP QR Ticket --}}
                    @if($guest)
                        <div class="d-card" style="text-align:center;">
                            <div class="d-card-head" style="justify-content:center;">TIKET MASUK VIP &bull; QR CHECK-IN
                            </div>
                            <div
                                style="font-family:'Cinzel',serif; font-size:1.05rem; font-weight:700; color:#fff; margin-bottom:3px;">
                                {{ $guest->name }}</div>
                            <div style="font-size:0.7rem; color:rgba(255,255,255,0.55); margin-bottom:0.75rem;">Tunjukkan QR
                                ini ke petugas saat tiba di lokasi.</div>
                            <div class="qr-wrap">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($guest->qr_code) }}"
                                    alt="QR" style="display:block; width:124px; height:124px;">
                            </div>
                            <div
                                style="font-family:monospace; font-weight:800; font-size:0.9rem; color:#fff; margin-top:4px;">
                                {{ $guest->qr_code }}</div>
                            <div style="font-size:0.65rem; color:rgba(255,255,255,0.5); margin-top:2px;">Kuota:
                                {{ $guest->pax ?: 1 }} orang &bull; {{ $guest->group_name ?: 'Tamu' }}</div>
                        </div>
                    @endif

                    {{-- Waktu & Lokasi --}}
                    <div class="d-card">
                        <div class="d-card-head">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg>
                            Waktu &amp; Lokasi Acara
                        </div>

                        @if($wedding->ceremony_date)
                            <div>
                                <div class="event-label">Akad Nikah / Pemberkatan</div>
                                <div class="event-row">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    <span>{{ $wedding->ceremony_date->isoFormat('dddd, D MMMM Y') }}</span>
                                </div>
                                <div class="event-row">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                    <span>{{ $wedding->ceremony_date->format('H:i') }} WIB</span>
                                </div>
                                <div class="event-row">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                    <div>
                                        <div>{{ $wedding->ceremony_location_name }}</div>
                                        @if($wedding->ceremony_address)
                                            <span class="event-subtext">{{ $wedding->ceremony_address }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <hr class="event-divider">
                        @endif

                        <div>
                            <div class="event-label">Resepsi Pernikahan</div>
                            <div class="event-row">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                <span>{{ $wedding->event_date ? $wedding->event_date->isoFormat('dddd, D MMMM Y') : '-' }}</span>
                            </div>
                            <div class="event-row">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                <span>{{ $wedding->event_date ? $wedding->event_date->format('H:i') . ' WIB' : '-' }}</span>
                            </div>
                            <div class="event-row">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                <div>
                                    <div>{{ $wedding->event_location_name }}</div>
                                    @if($wedding->event_address)
                                        <span class="event-subtext">{{ $wedding->event_address }}</span>
                                    @endif
                                </div>
                            </div>
                            <div style="display:flex; gap:8px; margin-top:0.85rem; flex-wrap:wrap;">
                                @if($wedding->event_map_url)
                                    <a href="{{ $wedding->event_map_url }}" target="_blank" class="btn-pill"
                                        style="flex:1;">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        Google Maps
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Amplop Digital --}}
                    @if($wedding->digital_envelope_enabled)
                        <div class="d-card" style="text-align:center;">
                            <div class="d-card-head" style="justify-content:center;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                </svg>
                                Amplop Digital
                            </div>
                            <p
                                style="font-size:0.72rem; color:rgba(255,255,255,0.55); margin-bottom:0.85rem; line-height:1.45;">
                                Doa restu Anda merupakan karunia terindah bagi kami.</p>

                            @if($wedding->bank_name && $wedding->bank_account_number)
                                <div
                                    style="background:rgba(0,0,0,0.45); border:1px solid rgba(255,255,255,0.15); border-radius:12px; padding:0.95rem; margin-bottom:0.75rem;">
                                    <div style="font-family:'Cinzel',serif; font-size:0.82rem; font-weight:700; color:#fff;">
                                        {{ $wedding->bank_name }}</div>
                                    <div class="acct-number-wrap">
                                        <span class="acct-number">{{ $wedding->bank_account_number }}</span>
                                    </div>
                                    <div style="font-size:0.68rem; color:rgba(255,255,255,0.5); margin-bottom:0.6rem;">a.n.
                                        {{ $wedding->bank_account_name }}</div>
                                    <button type="button" onclick="copyText('{{ $wedding->bank_account_number }}')"
                                        class="btn-pill">Salin Nomor</button>
                                </div>
                            @endif

                            @if($wedding->ewallet_name && $wedding->ewallet_number)
                                <div
                                    style="background:rgba(0,0,0,0.45); border:1px solid rgba(255,255,255,0.15); border-radius:12px; padding:0.95rem;">
                                    <div style="font-family:'Cinzel',serif; font-size:0.82rem; font-weight:700; color:#fff;">
                                        {{ $wedding->ewallet_name }}</div>
                                    <div class="acct-number-wrap">
                                        <span class="acct-number">{{ $wedding->ewallet_number }}</span>
                                    </div>
                                    <div style="font-size:0.68rem; color:rgba(255,255,255,0.5); margin-bottom:0.6rem;">a.n.
                                        {{ $wedding->ewallet_account_name }}</div>
                                    <button type="button" onclick="copyText('{{ $wedding->ewallet_number }}')"
                                        class="btn-pill">Salin Nomor</button>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- RSVP Form --}}
                    <div class="d-card">
                        <div class="d-card-head">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                            </svg>
                            Konfirmasi Kehadiran &amp; Ucapan
                        </div>

                        <form action="{{ route('wedding.wish', ['slug' => $wedding->slug]) }}" method="POST"
                            onsubmit="onSubmitRsvp(event)">
                            @csrf
                            <input type="hidden" name="guest_id" value="{{ $guest ? $guest->id : '' }}">
                            <input type="text" name="sender_name" class="d-input" placeholder="Nama Anda"
                                value="{{ $guest ? $guest->name : ($guestName !== 'Tamu Undangan' ? $guestName : '') }}"
                                required>

                            <!-- Kota Asal dengan deteksi lokasi -->
                            <div class="city-input-wrap">
                                <input type="text" name="city" id="cityInput" class="d-input" list="cityList"
                                    placeholder="Kota Asal (atau deteksi otomatis)">
                                <datalist id="cityList">
                                    <option value="Jakarta">
                                    <option value="Surabaya">
                                    <option value="Bandung">
                                    <option value="Medan">
                                    <option value="Semarang">
                                    <option value="Yogyakarta">
                                    <option value="Bali">
                                    <option value="Makassar">
                                    <option value="Purwokerto">
                                    <option value="Solo">
                                    <option value="Malang">
                                    <option value="Palembang">
                                </datalist>
                                <button type="button" class="btn-detect-loc" id="btnDetectLoc" onclick="detectMyCity()"
                                    title="Deteksi lokasi saya">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="3" />
                                        <path d="M12 2v3M12 19v3M2 12h3M19 12h3" />
                                        <path d="M12 2a10 10 0 1 0 10 10" />
                                    </svg>
                                </button>
                            </div>

                            <select name="attendance" class="d-input">
                                <option value="hadir">Saya Akan Hadir</option>
                                <option value="tidak">Maaf, Berhalangan Hadir</option>
                                <option value="ragu">Masih Ragu</option>
                            </select>
                            <textarea name="message" class="d-textarea" placeholder="Tulis doa restu & ucapan Anda..."
                                required></textarea>
                            <button type="submit" class="btn-submit" id="btnRsvp">Kirim Doa Restu</button>
                        </form>
                    </div>

                    {{-- Wishes --}}
                    @if(isset($wishes) && $wishes->count() > 0)
                        <div class="d-card">
                            <div class="d-card-head">Ucapan &amp; Doa ({{ $wishes->count() }})</div>
                            <div style="max-height:200px; overflow-y:auto;">
                                @foreach($wishes as $wish)
                                    <div class="wish-box">
                                        <div style="display:flex; justify-content:space-between; align-items:center;">
                                            <div class="wish-sender">{{ $wish->sender_name }}</div>
                                            <div class="wish-time">{{ $wish->created_at?->diffForHumans() }}</div>
                                        </div>
                                        <div class="wish-msg">{{ $wish->message }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div style="text-align:center; padding-top:0.75rem;">
                        <div
                            style="font-family:'Cinzel',serif; font-size:0.7rem; letter-spacing:0.18em; color:rgba(255,255,255,0.35);">
                            BUYLE.ID &bull; DIGITAL WEDDING
                        </div>
                    </div>

                </div>{{-- end .drawer-body --}}
            </div>{{-- end .drawer-sheet --}}
        </div>{{-- end .drawer-overlay --}}

    </div>{{-- end .phone-wrap --}}

    <!-- Toast -->
    <div id="toast">Nomor Berhasil Disalin!</div>

    <script>
        /* ── Video & state ── */
        const video = document.getElementById('weddingVideo');
        const iconSoundOn = document.getElementById('iconSoundOn');
        const iconSoundOff = document.getElementById('iconSoundOff');

        /* ── Open Invitation: autoplay dengan suara (user sudah interact) ── */
        function openInvitation() {
            document.getElementById('coverLayer').classList.add('hide');
            document.getElementById('contentLayer').classList.add('show');
            document.getElementById('bottomControls').style.display = 'flex';

            // Karena user sudah klik (ada gesture), langsung play dengan suara
            video.muted = false;
            video.play().catch(() => {
                // Fallback: jika browser masih blokir suara, play muted dulu
                video.muted = true;
                video.play().catch(() => { });
                // Update icon ke muted
                iconSoundOn.style.display = 'none';
                iconSoundOff.style.display = 'block';
            });
        }

        /* ── Toggle suara video ── */
        function toggleVideoMute() {
            video.muted = !video.muted;
            if (video.muted) {
                iconSoundOn.style.display = 'none';
                iconSoundOff.style.display = 'block';
            } else {
                iconSoundOn.style.display = 'block';
                iconSoundOff.style.display = 'none';
            }
        }

        /* ── Drawer ── */
        function openDrawer() {
            document.getElementById('drawerOverlay').classList.add('open');
        }
        function closeDrawer() {
            document.getElementById('drawerOverlay').classList.remove('open');
        }
        function onDrawerBg(e) {
            if (e.target.id === 'drawerOverlay') closeDrawer();
        }

        /* ── Geolocation detect city ── */
        function detectMyCity() {
            if (!navigator.geolocation) {
                showToast('Browser tidak mendukung deteksi lokasi');
                return;
            }
            const btn = document.getElementById('btnDetectLoc');
            const input = document.getElementById('cityInput');
            btn.classList.add('detecting');
            input.placeholder = 'Mendeteksi lokasi...';

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    const lat = pos.coords.latitude;
                    const lon = pos.coords.longitude;
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=10&accept-language=id`)
                        .then(r => r.json())
                        .then(data => {
                            btn.classList.remove('detecting');
                            const addr = data.address || {};
                            const city = addr.city || addr.town || addr.village || addr.county || addr.state || 'Lokasi Terdeteksi';
                            input.value = city;
                            input.placeholder = 'Kota Asal (atau deteksi otomatis)';
                        })
                        .catch(() => {
                            btn.classList.remove('detecting');
                            input.placeholder = 'Kota Asal (atau deteksi otomatis)';
                            showToast('Gagal mendapatkan nama kota');
                        });
                },
                function () {
                    btn.classList.remove('detecting');
                    input.placeholder = 'Kota Asal (atau deteksi otomatis)';
                    showToast('Izin lokasi ditolak');
                },
                { timeout: 8000, maximumAge: 60000 }
            );
        }

        /* ── Copy & Toast ── */
        function copyText(txt) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(txt);
            } else {
                const t = document.createElement('textarea');
                t.value = txt; document.body.appendChild(t);
                t.select(); document.execCommand('copy');
                document.body.removeChild(t);
            }
            showToast('Nomor Berhasil Disalin!');
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            toast.textContent = msg;
            toast.style.opacity = '1';
            setTimeout(() => toast.style.opacity = '0', 2400);
        }

        /* ── RSVP submit ── */
        function onSubmitRsvp(e) {
            const btn = document.getElementById('btnRsvp');
            if (btn) { btn.disabled = true; btn.innerText = 'Mengirim...'; }
        }
    </script>
</body>

</html>