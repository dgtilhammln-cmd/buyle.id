<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ $wedding->event_title }} &bull; {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}</title>
    <meta name="description" content="Undangan Pernikahan Digital {{ $wedding->groom_nickname }} & {{ $wedding->bride_nickname }}.">
    <meta property="og:title" content="The Wedding of {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}">
    <meta property="og:description" content="Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir di hari bahagia kami.">
    <meta property="og:image" content="{{ asset('images/wedding/marmer.png') }}">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@400;500;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400;1,600&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }

        html, body {
            width: 100%; height: 100%;
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
                box-shadow: 0 30px 100px rgba(0,0,0,0.9), 0 0 0 10px #101318;
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
            0%   { transform: scale(1.0) translate(0, 0); }
            33%  { transform: scale(1.06) translate(-2%, 1%); }
            66%  { transform: scale(1.04) translate(2%, -1%); }
            100% { transform: scale(1.08) translate(0, 2%); }
        }

        /* ── Cover Layer (before open) ── */
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

        /* ── Curved SVG Names ── */
        .monogram-ring {
            width: 240px; height: 240px;
            position: relative;
            display: flex; align-items: center; justify-content: center;
        }

        .monogram-svg {
            width: 100%; height: 100%; overflow: visible;
        }

        .ring-name-text {
            font-family: 'Cinzel', serif;
            font-size: 17px;
            font-weight: 700;
            fill: #ffffff;
            letter-spacing: 0.38em;
        }

        /* Botanical center */
        .botanical {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            display: flex; align-items: center; gap: 10px;
        }

        .botanical svg.rose {
            width: 48px; height: 48px;
            filter: drop-shadow(0 0 8px rgba(255,255,255,0.3));
        }

        .botanical .dot {
            width: 5px; height: 5px;
            background: #ffffff;
            border-radius: 50%;
            opacity: 0.7;
        }

        /* Cover guest card */
        .guest-card {
            background: rgba(8, 10, 16, 0.68);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 20px;
            padding: 1.2rem 1.8rem;
            width: 100%; max-width: 320px;
        }

        .guest-label {
            font-size: 0.65rem; letter-spacing: 0.18em;
            text-transform: uppercase; color: rgba(255,255,255,0.6);
            margin-bottom: 0.4rem; font-weight: 500;
        }

        .guest-name {
            font-family: 'Cinzel', serif;
            font-size: 1.2rem; font-weight: 700;
            color: #ffffff; letter-spacing: 0.06em;
            margin-bottom: 0.3rem;
        }

        .guest-pax {
            font-size: 0.67rem; color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.25);
            background: rgba(255,255,255,0.08);
            border-radius: 999px; padding: 2px 10px; display: inline-block;
        }

        .btn-open {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 0.85rem 2.4rem;
            background: #ffffff; color: #06080c;
            font-family: 'Cinzel', serif; font-size: 0.8rem;
            font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase;
            border: none; border-radius: 999px; cursor: pointer;
            box-shadow: 0 8px 28px rgba(255,255,255,0.3);
            transition: all 0.22s ease;
        }
        .btn-open:hover { transform: translateY(-2px); box-shadow: 0 10px 36px rgba(255,255,255,0.45); }

        .cover-hint {
            font-size: 0.65rem; color: rgba(255,255,255,0.5);
            margin-top: 0.7rem; letter-spacing: 0.06em;
        }

        /* ── Content Panels (over marble bg) ── */
        #contentLayer {
            position: absolute;
            inset: 0; z-index: 50;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        #contentLayer.show {
            display: flex;
        }

        /* Each panel */
        .panel {
            position: absolute;
            inset: 0;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 3.5rem 2rem;
            opacity: 0;
            transition: opacity 0.9s ease;
            pointer-events: none;
        }

        .panel.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* ── Panel 1: Monogram Names ── */
        /* (reuses .monogram-ring above) */

        /* ── Panel 2: Save The Date Circle ── */
        .date-circle {
            width: 200px; height: 200px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.65);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 2px;
            background: rgba(6, 8, 12, 0.18);
            box-shadow: 0 0 40px rgba(0,0,0,0.3);
        }

        .date-day {
            font-family: 'Cinzel', serif;
            font-size: 0.82rem; font-weight: 600;
            letter-spacing: 0.28em; color: #ffffff;
            text-transform: uppercase;
        }

        .date-num {
            font-family: 'Cinzel', serif;
            font-size: 3.8rem; font-weight: 700;
            color: #ffffff; line-height: 1;
        }

        .date-month-year {
            font-family: 'Cinzel', serif;
            font-size: 0.78rem; font-weight: 600;
            letter-spacing: 0.2em; color: #ffffff;
            text-transform: uppercase;
        }

        /* ── Panel 3: We Invite You ── */
        .invite-line {
            font-family: 'Cinzel', serif;
            font-size: 1rem; font-weight: 600;
            letter-spacing: 0.38em; color: #ffffff;
            text-transform: uppercase; line-height: 2.0;
            text-shadow: 0 2px 14px rgba(0,0,0,0.9);
        }

        /* ── Panel 4: Scripture ── */
        .verse {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem; font-style: italic;
            line-height: 1.75; color: #ffffff;
            max-width: 310px;
            text-shadow: 0 2px 12px rgba(0,0,0,0.8);
            margin-bottom: 1rem;
        }

        .verse-ref {
            font-family: 'Cinzel', serif;
            font-size: 0.72rem; letter-spacing: 0.22em;
            color: rgba(255,255,255,0.8); font-style: normal;
            display: block; margin-top: 0.4rem; font-weight: 600;
        }

        .verse-sub {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem; color: rgba(255,255,255,0.8);
            line-height: 1.6; max-width: 290px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        }

        /* ── Panel 5: Couple Names ── */
        .couple-name {
            font-family: 'Alex Brush', cursive;
            font-size: 3.2rem; color: #ffffff; line-height: 1.15;
            text-shadow: 0 2px 14px rgba(0,0,0,0.8);
        }

        .couple-parents {
            font-size: 0.72rem; color: rgba(255,255,255,0.65); margin-top: 2px;
        }

        .couple-amp {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem; font-style: italic;
            color: rgba(255,255,255,0.8); margin: 0.35rem 0;
        }

        /* ── Panel 6: Events ── */
        .event-block { margin-bottom: 1.6rem; }
        .event-block:last-child { margin-bottom: 0; }

        .event-title {
            font-family: 'Alex Brush', cursive;
            font-size: 2.3rem; color: #ffffff; line-height: 1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8); margin-bottom: 0.25rem;
        }

        .event-time {
            font-family: 'Cinzel', serif; font-size: 0.76rem;
            font-weight: 700; color: #ffffff; letter-spacing: 0.14em;
            text-transform: uppercase; margin-bottom: 0.2rem;
        }

        .event-place { font-size: 0.82rem; font-weight: 700; color: #ffffff; }
        .event-addr { font-size: 0.7rem; color: rgba(255,255,255,0.65); line-height: 1.4; max-width: 280px; margin: 0 auto; }

        /* ── Panel 7: Kindly Deliver ── */
        .deliver-title {
            font-family: 'Alex Brush', cursive;
            font-size: 2.8rem; color: #ffffff;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8); margin-bottom: 0.85rem;
        }

        .deliver-guest {
            font-family: 'Cinzel', serif; font-size: 1.2rem;
            font-weight: 700; color: #ffffff; letter-spacing: 0.1em;
        }

        .deliver-pax {
            font-size: 0.7rem; color: rgba(255,255,255,0.65);
            margin-top: 0.4rem; letter-spacing: 0.08em;
        }

        /* ── Panel 8: Thank You ── */
        .thankyou-text {
            font-family: 'Alex Brush', cursive;
            font-size: 5rem; color: #ffffff; line-height: 1.05;
            text-shadow: 0 4px 20px rgba(0,0,0,0.8); margin-bottom: 2rem;
        }

        .btn-details {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 0.8rem 2rem;
            background: rgba(8,10,16,0.7); color: #ffffff;
            font-family: 'Cinzel', serif; font-size: 0.76rem;
            font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase;
            border: 1px solid rgba(255,255,255,0.3); border-radius: 999px;
            cursor: pointer; backdrop-filter: blur(10px);
            transition: all 0.2s;
        }
        .btn-details:hover { background: #ffffff; color: #06080c; }

        /* ── Navigation dots ── */
        .nav-dots {
            position: absolute; bottom: 55px; left: 50%;
            transform: translateX(-50%);
            display: flex; gap: 6px; z-index: 60;
        }

        .nav-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transition: all 0.35s ease; cursor: pointer;
        }

        .nav-dot.active {
            background: #ffffff;
            width: 18px; border-radius: 4px;
        }

        /* ── Tap zones ── */
        .tap-left, .tap-right {
            position: absolute; top: 0; bottom: 0;
            width: 40%; z-index: 55; cursor: pointer;
        }
        .tap-left { left: 0; }
        .tap-right { right: 0; }

        /* ── Bottom Floating Controls ── */
        .bottom-controls {
            position: absolute; bottom: 16px; left: 16px; right: 16px;
            z-index: 80; display: flex; align-items: center; justify-content: space-between;
            pointer-events: none;
        }

        .btn-music {
            width: 40px; height: 40px; border-radius: 50%;
            background: rgba(8,10,16,0.75); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.22); color: #ffffff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; pointer-events: auto; transition: all 0.2s;
        }

        .btn-music.spin { animation: spin 5s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        .btn-open-drawer {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.5rem 1.1rem; border-radius: 999px;
            background: rgba(8,10,16,0.75); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.22); color: #ffffff;
            font-family: 'Cinzel', serif; font-size: 0.68rem;
            font-weight: 700; letter-spacing: 0.1em; cursor: pointer;
            pointer-events: auto; transition: all 0.2s;
        }
        .btn-open-drawer:hover { background: rgba(255,255,255,0.15); }

        /* ── Bottom Sheet Drawer ── */
        .drawer-overlay {
            position: absolute; inset: 0; z-index: 200;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);
            opacity: 0; pointer-events: none;
            transition: opacity 0.35s ease;
            display: flex; flex-direction: column; justify-content: flex-end;
        }
        .drawer-overlay.open { opacity: 1; pointer-events: auto; }

        .drawer-sheet {
            width: 100%; max-height: 86%;
            background: rgba(10, 13, 20, 0.97);
            border-top: 1px solid rgba(255,255,255,0.15);
            border-radius: 26px 26px 0 0;
            padding: 0.85rem 1.4rem 2.5rem;
            overflow-y: auto;
            transform: translateY(100%);
            transition: transform 0.42s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .drawer-overlay.open .drawer-sheet { transform: translateY(0); }

        .drawer-pill {
            width: 42px; height: 4px; background: rgba(255,255,255,0.3);
            border-radius: 999px; margin: 0 auto 1.2rem; cursor: pointer;
        }

        .drawer-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.2rem; padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .drawer-head-title {
            font-family: 'Cinzel', serif; font-size: 1rem;
            font-weight: 700; letter-spacing: 0.1em; color: #ffffff;
        }

        .btn-close-drawer {
            width: 30px; height: 30px; border-radius: 50%;
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.18);
            color: #fff; display: flex; align-items: center; justify-content: center;
            cursor: pointer;
        }

        .d-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px; padding: 1.2rem; margin-bottom: 1rem;
        }

        .d-card-head {
            display: flex; align-items: center; gap: 7px;
            font-family: 'Cinzel', serif; font-size: 0.78rem;
            font-weight: 700; letter-spacing: 0.1em; color: #ffffff;
            margin-bottom: 0.85rem;
        }

        .d-input {
            width: 100%; height: 40px;
            background: rgba(0,0,0,0.45); border: 1px solid rgba(255,255,255,0.2);
            border-radius: 10px; padding: 0 12px;
            font-family: 'Montserrat', sans-serif; font-size: 0.8rem;
            color: #ffffff; outline: none; margin-bottom: 0.7rem;
        }
        .d-input:focus { border-color: #ffffff; }
        option { background: #10131a; }

        .d-textarea {
            width: 100%; min-height: 72px;
            background: rgba(0,0,0,0.45); border: 1px solid rgba(255,255,255,0.2);
            border-radius: 10px; padding: 10px 12px;
            font-family: 'Montserrat', sans-serif; font-size: 0.8rem;
            color: #ffffff; outline: none; resize: vertical; margin-bottom: 0.7rem;
        }
        .d-textarea:focus { border-color: #ffffff; }

        .btn-submit {
            width: 100%; padding: 0.75rem;
            background: #ffffff; color: #06080c;
            font-family: 'Cinzel', serif; font-size: 0.78rem;
            font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase;
            border: none; border-radius: 10px; cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-submit:hover { opacity: 0.9; }

        .btn-pill {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 0.46rem 1rem; border-radius: 999px;
            font-size: 0.72rem; font-weight: 700;
            border: 1px solid rgba(255,255,255,0.28);
            color: #ffffff; background: rgba(255,255,255,0.07);
            text-decoration: none; transition: all 0.2s;
        }
        .btn-pill:hover { background: #ffffff; color: #06080c; }

        .wish-box {
            background: rgba(0,0,0,0.35); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 11px; padding: 0.7rem 0.85rem; margin-bottom: 0.5rem;
        }

        .wish-sender { font-weight: 700; font-size: 0.78rem; color: #fff; }
        .wish-time { font-size: 0.62rem; color: rgba(255,255,255,0.45); }
        .wish-msg { font-size: 0.73rem; color: rgba(255,255,255,0.82); line-height: 1.4; margin-top: 2px; }

        /* ── QR Ticket ── */
        .qr-wrap {
            background: #fff; border-radius: 12px; padding: 8px;
            display: inline-block; margin: 0.6rem 0;
        }

        /* ── Toast ── */
        #toast {
            position: fixed; top: 22px; left: 50%; transform: translateX(-50%);
            background: #fff; color: #06080c; padding: 8px 20px;
            border-radius: 999px; font-weight: 700; font-size: 0.74rem;
            font-family: 'Cinzel', serif; letter-spacing: 0.1em;
            box-shadow: 0 6px 22px rgba(0,0,0,0.5); z-index: 9999;
            opacity: 0; pointer-events: none; transition: opacity 0.3s;
        }
    </style>
</head>
<body>

<div class="phone-wrap" id="phoneWrap">

    <!-- ① Fixed Marble Background with Ken Burns animation -->
    <div class="bg-marble"></div>

    <!-- ② COVER LAYER -->
    <div id="coverLayer">
        <!-- Curved Monogram -->
        <div>
            <div style="font-family:'Cinzel',serif; font-size:0.7rem; letter-spacing:0.35em; color:rgba(255,255,255,0.75); text-transform:uppercase; margin-bottom:1.6rem;">
                The Wedding Celebration of
            </div>

            <div class="monogram-ring">
                <svg viewBox="0 0 260 260" class="monogram-svg">
                    <defs>
                        <path id="arcTop" d="M 40,130 A 90,90 0 0,1 220,130" fill="none"/>
                        <path id="arcBot" d="M 40,130 A 90,90 0 0,0 220,130" fill="none"/>
                    </defs>
                    <!-- Thin ring guide -->
                    <circle cx="130" cy="130" r="90" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="0.8"/>
                    <text class="ring-name-text">
                        <textPath href="#arcTop" startOffset="50%" text-anchor="middle">
                            {{ strtoupper($wedding->groom_nickname ?: 'ANDREW') }}
                        </textPath>
                    </text>
                    <text class="ring-name-text">
                        <textPath href="#arcBot" startOffset="50%" text-anchor="middle">
                            {{ strtoupper($wedding->bride_nickname ?: 'LUCIA') }}
                        </textPath>
                    </text>
                </svg>

                <!-- Center Rose + Dots -->
                <div class="botanical">
                    <div class="dot"></div>
                    <svg class="rose" viewBox="0 0 64 64" fill="none" stroke="#ffffff" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Stem -->
                        <path d="M32 52 Q30 42 32 36"/>
                        <!-- Leaf left -->
                        <path d="M32 44 Q22 40 20 34 Q28 34 32 40"/>
                        <!-- Leaf right -->
                        <path d="M32 40 Q42 36 44 30 Q36 32 32 38"/>
                        <!-- Petals outer -->
                        <path d="M32 12 C28 16 22 18 22 24 C22 30 27 33 32 34 C37 33 42 30 42 24 C42 18 36 16 32 12Z"/>
                        <path d="M24 16 C18 14 14 18 15 24 C16 28 20 31 25 32"/>
                        <path d="M40 16 C46 14 50 18 49 24 C48 28 44 31 39 32"/>
                        <path d="M20 26 C16 30 16 36 20 38 C24 36 27 32 27 27"/>
                        <path d="M44 26 C48 30 48 36 44 38 C40 36 37 32 37 27"/>
                        <!-- Inner -->
                        <path d="M29 20 C29 24 30 27 32 28 C34 27 35 24 35 20 C34 18 30 18 29 20Z"/>
                    </svg>
                    <div class="dot"></div>
                </div>
            </div>
        </div>

        <!-- Guest Card -->
        <div class="guest-card">
            <div class="guest-label">Kepada Yth. Bapak/Ibu/Saudara/i:</div>
            <div class="guest-name">{{ $guestName }}</div>
            @if($guest)
                <div class="guest-pax">{{ $guest->group_name ?: 'Tamu Undangan' }} &bull; {{ $guest->pax ?: 1 }} Pax</div>
            @else
                <div class="guest-pax">Tamu Undangan Terhormat</div>
            @endif
        </div>

        <!-- Open Button -->
        <div>
            <button type="button" class="btn-open" onclick="openInvitation()">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <span>Buka Undangan</span>
            </button>
            <div class="cover-hint">Ketuk untuk membuka &amp; mendengarkan musik</div>
        </div>
    </div>

    <!-- ③ CONTENT LAYER (panels that fade over the same marble bg) -->
    <div id="contentLayer">

        <!-- Tap zones for prev/next -->
        <div class="tap-left" onclick="prevPanel()"></div>
        <div class="tap-right" onclick="nextPanel()"></div>

        <!-- PANEL 0: Monogram (same as cover) -->
        <div class="panel active" id="panel0">
            <div class="monogram-ring" style="width:260px;height:260px;">
                <svg viewBox="0 0 260 260" class="monogram-svg">
                    <defs>
                        <path id="arcTopC" d="M 40,130 A 90,90 0 0,1 220,130" fill="none"/>
                        <path id="arcBotC" d="M 40,130 A 90,90 0 0,0 220,130" fill="none"/>
                    </defs>
                    <circle cx="130" cy="130" r="90" fill="none" stroke="rgba(255,255,255,0.22)" stroke-width="0.8"/>
                    <text class="ring-name-text">
                        <textPath href="#arcTopC" startOffset="50%" text-anchor="middle">
                            {{ strtoupper($wedding->groom_nickname ?: 'ANDREW') }}
                        </textPath>
                    </text>
                    <text class="ring-name-text">
                        <textPath href="#arcBotC" startOffset="50%" text-anchor="middle">
                            {{ strtoupper($wedding->bride_nickname ?: 'LUCIA') }}
                        </textPath>
                    </text>
                </svg>
                <div class="botanical">
                    <div class="dot"></div>
                    <svg class="rose" viewBox="0 0 64 64" fill="none" stroke="#ffffff" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M32 52 Q30 42 32 36"/>
                        <path d="M32 44 Q22 40 20 34 Q28 34 32 40"/>
                        <path d="M32 40 Q42 36 44 30 Q36 32 32 38"/>
                        <path d="M32 12 C28 16 22 18 22 24 C22 30 27 33 32 34 C37 33 42 30 42 24 C42 18 36 16 32 12Z"/>
                        <path d="M24 16 C18 14 14 18 15 24 C16 28 20 31 25 32"/>
                        <path d="M40 16 C46 14 50 18 49 24 C48 28 44 31 39 32"/>
                        <path d="M20 26 C16 30 16 36 20 38 C24 36 27 32 27 27"/>
                        <path d="M44 26 C48 30 48 36 44 38 C40 36 37 32 37 27"/>
                        <path d="M29 20 C29 24 30 27 32 28 C34 27 35 24 35 20 C34 18 30 18 29 20Z"/>
                    </svg>
                    <div class="dot"></div>
                </div>
            </div>
        </div>

        <!-- PANEL 1: Save The Date -->
        <div class="panel" id="panel1">
            <div style="font-family:'Cinzel',serif; font-size:0.72rem; letter-spacing:0.3em; color:rgba(255,255,255,0.75); text-transform:uppercase; margin-bottom:1.5rem;">
                Save The Date
            </div>
            <div class="date-circle">
                <div class="date-day">
                    {{ $wedding->event_date ? $wedding->event_date->isoFormat('dddd') : 'Saturday' }}
                </div>
                <div class="date-num">
                    {{ $wedding->event_date ? $wedding->event_date->format('d') : '10' }}
                </div>
                <div class="date-month-year">
                    {{ $wedding->event_date ? $wedding->event_date->isoFormat('MMMM Y') : 'July 2021' }}
                </div>
            </div>
        </div>

        <!-- PANEL 2: We Invite You -->
        <div class="panel" id="panel2">
            <div class="invite-line">
                WE INVITE YOU<br>
                TO CELEBRATE<br>
                OUR WEDDING
            </div>
        </div>

        <!-- PANEL 3: Scripture -->
        <div class="panel" id="panel3">
            <div class="verse">
                &ldquo;So, they are no longer two, but one.<br>
                Therefore what God has joined together,<br>
                let man not separate.&rdquo;
                <span class="verse-ref">&bull; Matthew 19:6 &bull;</span>
            </div>
            <div class="verse-sub">
                Request the honour of your presence<br>at the marriage of our beloved children
            </div>
        </div>

        <!-- PANEL 4: The Couple -->
        <div class="panel" id="panel4">
            <div class="couple-name">{{ $wedding->groom_name }}</div>
            @if($wedding->groom_parents)
                <div class="couple-parents">{{ $wedding->groom_parents }}</div>
            @endif
            <div class="couple-amp">&amp;</div>
            <div class="couple-name">{{ $wedding->bride_name }}</div>
            @if($wedding->bride_parents)
                <div class="couple-parents">{{ $wedding->bride_parents }}</div>
            @endif
        </div>

        <!-- PANEL 5: Events -->
        <div class="panel" id="panel5">
            @if($wedding->ceremony_date)
                <div class="event-block">
                    <div class="event-title">Holy Matrimony</div>
                    <div class="event-time">{{ $wedding->ceremony_date->format('H:i') }} WIB</div>
                    <div class="event-place">{{ $wedding->ceremony_location_name }}</div>
                    <div class="event-addr">{{ $wedding->ceremony_address }}</div>
                </div>
            @endif
            <div class="event-block">
                <div class="event-title">Wedding Reception</div>
                <div class="event-time">{{ $wedding->event_date ? $wedding->event_date->format('H:i') . ' WIB' : '18:00 WIB' }}</div>
                <div class="event-place">{{ $wedding->event_location_name }}</div>
                <div class="event-addr">{{ $wedding->event_address }}</div>
            </div>
        </div>

        <!-- PANEL 6: Kindly Deliver -->
        <div class="panel" id="panel6">
            <div class="deliver-title">Kindly deliver to You</div>
            <div class="deliver-guest">{{ $guestName }}</div>
            @if($guest)
                <div class="deliver-pax">{{ $guest->group_name ?: 'Tamu Undangan' }} &bull; {{ $guest->pax ?: 1 }} Pax</div>
            @endif
        </div>

        <!-- PANEL 7: Thank You -->
        <div class="panel" id="panel7">
            <div class="thankyou-text">Thank you</div>
            <button type="button" class="btn-details" onclick="openDrawer()">
                <span>Rincian &amp; RSVP</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>

        <!-- Nav Dots -->
        <div class="nav-dots" id="navDots">
            @for($i = 0; $i < 8; $i++)
                <div class="nav-dot {{ $i === 0 ? 'active' : '' }}" onclick="goPanel({{ $i }})"></div>
            @endfor
        </div>
    </div>

    <!-- ④ Bottom Controls (visible after open) -->
    <div class="bottom-controls" id="bottomControls" style="display:none;">
        <button type="button" class="btn-music" id="btnMusic" onclick="toggleMusic()" title="Musik">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 18V5l12-2v13"/>
                <circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
            </svg>
        </button>
        <button type="button" class="btn-open-drawer" onclick="openDrawer()">
            <span>Rincian &amp; RSVP</span>
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M5 15l7-7 7 7"/>
            </svg>
        </button>
    </div>

    <!-- ⑤ Bottom Sheet Drawer -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="onDrawerBg(event)">
        <div class="drawer-sheet">
            <div class="drawer-pill" onclick="closeDrawer()"></div>

            <div class="drawer-head">
                <div class="drawer-head-title">Informasi &amp; Kehadiran</div>
                <button type="button" class="btn-close-drawer" onclick="closeDrawer()">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- VIP QR Ticket --}}
            @if($guest)
                <div class="d-card" style="text-align:center;">
                    <div class="d-card-head" style="justify-content:center;">TIKET MASUK VIP &bull; QR CHECK-IN</div>
                    <div style="font-family:'Cinzel',serif; font-size:1.05rem; font-weight:700; color:#fff; margin-bottom:3px;">{{ $guest->name }}</div>
                    <div style="font-size:0.7rem; color:rgba(255,255,255,0.55); margin-bottom:0.75rem;">Tunjukkan QR ini ke petugas saat tiba di lokasi.</div>
                    <div class="qr-wrap">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($guest->qr_code) }}" alt="QR" style="display:block; width:124px; height:124px;">
                    </div>
                    <div style="font-family:monospace; font-weight:800; font-size:0.9rem; color:#fff; margin-top:4px;">{{ $guest->qr_code }}</div>
                    <div style="font-size:0.65rem; color:rgba(255,255,255,0.5); margin-top:2px;">Kuota: {{ $guest->pax ?: 1 }} orang &bull; {{ $guest->group_name ?: 'Tamu' }}</div>
                </div>
            @endif

            {{-- Waktu & Lokasi --}}
            <div class="d-card">
                <div class="d-card-head">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    Waktu &amp; Lokasi Acara
                </div>

                @if($wedding->ceremony_date)
                    <div style="margin-bottom:0.9rem; padding-bottom:0.85rem; border-bottom:1px solid rgba(255,255,255,0.07);">
                        <div style="font-size:0.68rem; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:0.1em; font-weight:600;">Akad Nikah / Pemberkatan</div>
                        <div style="font-family:'Cinzel',serif; font-size:0.84rem; font-weight:700; color:#fff; margin:2px 0;">{{ $wedding->ceremony_date->isoFormat('dddd, D MMMM Y') }} &bull; {{ $wedding->ceremony_date->format('H:i') }} WIB</div>
                        <div style="font-size:0.78rem; font-weight:600; color:#fff;">{{ $wedding->ceremony_location_name }}</div>
                        <div style="font-size:0.7rem; color:rgba(255,255,255,0.5); margin-top:2px;">{{ $wedding->ceremony_address }}</div>
                    </div>
                @endif

                <div>
                    <div style="font-size:0.68rem; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:0.1em; font-weight:600;">Resepsi Pernikahan</div>
                    <div style="font-family:'Cinzel',serif; font-size:0.84rem; font-weight:700; color:#fff; margin:2px 0;">
                        {{ $wedding->event_date ? $wedding->event_date->isoFormat('dddd, D MMMM Y') . ' &bull; ' . $wedding->event_date->format('H:i') . ' WIB' : '-' }}
                    </div>
                    <div style="font-size:0.78rem; font-weight:600; color:#fff;">{{ $wedding->event_location_name }}</div>
                    <div style="font-size:0.7rem; color:rgba(255,255,255,0.5); margin-top:2px;">{{ $wedding->event_address }}</div>
                    <div style="display:flex; gap:8px; margin-top:0.85rem; flex-wrap:wrap;">
                        @if($wedding->event_map_url)
                            <a href="{{ $wedding->event_map_url }}" target="_blank" class="btn-pill" style="flex:1;">
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Google Maps
                            </a>
                        @endif
                        <a href="https://waze.com/ul?q={{ urlencode($wedding->event_location_name ?: $wedding->event_address) }}" target="_blank" class="btn-pill" style="flex:1;">Waze</a>
                    </div>
                </div>
            </div>

            {{-- Amplop Digital --}}
            @if($wedding->digital_envelope_enabled)
                <div class="d-card" style="text-align:center;">
                    <div class="d-card-head" style="justify-content:center;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                        Amplop Digital
                    </div>
                    <p style="font-size:0.72rem; color:rgba(255,255,255,0.55); margin-bottom:0.85rem; line-height:1.45;">Doa restu Anda merupakan karunia terindah bagi kami.</p>

                    @if($wedding->bank_name && $wedding->bank_account_number)
                        <div style="background:rgba(0,0,0,0.45); border:1px solid rgba(255,255,255,0.15); border-radius:12px; padding:0.95rem; margin-bottom:0.75rem;">
                            <div style="font-family:'Cinzel',serif; font-size:0.82rem; font-weight:700; color:#fff;">{{ $wedding->bank_name }}</div>
                            <div style="font-family:monospace; font-size:1.1rem; font-weight:800; color:#fff; margin:0.25rem 0;">{{ $wedding->bank_account_number }}</div>
                            <div style="font-size:0.68rem; color:rgba(255,255,255,0.5); margin-bottom:0.6rem;">a.n. {{ $wedding->bank_account_name }}</div>
                            <button type="button" onclick="copyText('{{ $wedding->bank_account_number }}')" class="btn-pill">Salin Nomor</button>
                        </div>
                    @endif

                    @if($wedding->ewallet_name && $wedding->ewallet_number)
                        <div style="background:rgba(0,0,0,0.45); border:1px solid rgba(255,255,255,0.15); border-radius:12px; padding:0.95rem;">
                            <div style="font-family:'Cinzel',serif; font-size:0.82rem; font-weight:700; color:#fff;">{{ $wedding->ewallet_name }}</div>
                            <div style="font-family:monospace; font-size:1.1rem; font-weight:800; color:#fff; margin:0.25rem 0;">{{ $wedding->ewallet_number }}</div>
                            <div style="font-size:0.68rem; color:rgba(255,255,255,0.5); margin-bottom:0.6rem;">a.n. {{ $wedding->ewallet_account_name }}</div>
                            <button type="button" onclick="copyText('{{ $wedding->ewallet_number }}')" class="btn-pill">Salin Nomor</button>
                        </div>
                    @endif
                </div>
            @endif

            {{-- RSVP Form --}}
            <div class="d-card">
                <div class="d-card-head">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Konfirmasi Kehadiran &amp; Ucapan
                </div>

                <form action="{{ route('wedding.wish', ['slug' => $wedding->slug]) }}" method="POST" onsubmit="onSubmitRsvp(event)">
                    @csrf
                    <input type="hidden" name="guest_id" value="{{ $guest ? $guest->id : '' }}">
                    <input type="text" name="sender_name" class="d-input" placeholder="Nama Anda"
                           value="{{ $guest ? $guest->name : ($guestName !== 'Tamu Undangan' ? $guestName : '') }}" required>
                    <input type="text" name="city" class="d-input" list="cityList" placeholder="Kota Asal">
                    <datalist id="cityList">
                        <option value="Jakarta"><option value="Surabaya"><option value="Bandung">
                        <option value="Medan"><option value="Semarang"><option value="Yogyakarta">
                        <option value="Bali"><option value="Makassar">
                    </datalist>
                    <select name="attendance" class="d-input">
                        <option value="hadir">Saya Akan Hadir</option>
                        <option value="tidak">Maaf, Berhalangan Hadir</option>
                        <option value="ragu">Masih Ragu</option>
                    </select>
                    <textarea name="message" class="d-textarea" placeholder="Tulis doa restu & ucapan Anda..." required></textarea>
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
                <div style="font-family:'Cinzel',serif; font-size:0.7rem; letter-spacing:0.18em; color:rgba(255,255,255,0.35);">
                    BUYLE.ID &bull; DIGITAL WEDDING
                </div>
            </div>
        </div>
    </div>

    <!-- Background Music -->
    <audio id="bgAudio" loop preload="auto">
        @if($wedding->music_url)
            <source src="{{ $wedding->music_url }}" type="audio/mpeg">
        @else
            <source src="https://assets.mixkit.co/music/preview/mixkit-romantic-wedding-love-680.mp3" type="audio/mpeg">
        @endif
    </audio>
</div>

<!-- Toast -->
<div id="toast">Nomor Berhasil Disalin!</div>

<script>
    /* ── Config ── */
    const TOTAL = 8;
    const DURATION = 5500; // ms per panel

    let cur = 0;
    let timer = null;
    let startTime = 0;
    let paused = false;
    let musicPlaying = false;
    const audio = document.getElementById('bgAudio');

    /* ── Open Invitation ── */
    function openInvitation() {
        document.getElementById('coverLayer').classList.add('hide');
        document.getElementById('contentLayer').classList.add('show');
        document.getElementById('bottomControls').style.display = 'flex';
        playMusic();
        startAutoPlay();
    }

    /* ── Auto-play engine ── */
    function startAutoPlay() {
        stopAutoPlay();
        startTime = performance.now();
        (function tick(now) {
            if (!paused) {
                const elapsed = now - startTime;
                if (elapsed >= DURATION) {
                    const next = cur + 1 < TOTAL ? cur + 1 : cur;
                    if (next !== cur) goPanel(next);
                    else stopAutoPlay();
                    return;
                }
            }
            timer = requestAnimationFrame(tick);
        })(startTime);
    }

    function stopAutoPlay() {
        if (timer) { cancelAnimationFrame(timer); timer = null; }
    }

    function goPanel(index) {
        if (index < 0 || index >= TOTAL) return;
        document.getElementById('panel' + cur).classList.remove('active');
        document.querySelectorAll('.nav-dot')[cur].classList.remove('active');
        cur = index;
        document.getElementById('panel' + cur).classList.add('active');
        document.querySelectorAll('.nav-dot')[cur].classList.add('active');
        if (cur < TOTAL - 1) startAutoPlay();
    }

    function nextPanel() { goPanel(cur < TOTAL - 1 ? cur + 1 : cur); }
    function prevPanel() { goPanel(cur > 0 ? cur - 1 : 0); }

    /* ── Drawer ── */
    function openDrawer() {
        paused = true;
        document.getElementById('drawerOverlay').classList.add('open');
    }
    function closeDrawer() {
        document.getElementById('drawerOverlay').classList.remove('open');
        paused = false;
        startAutoPlay();
    }
    function onDrawerBg(e) {
        if (e.target.id === 'drawerOverlay') closeDrawer();
    }

    /* ── Music ── */
    function playMusic() {
        audio.play().then(() => {
            musicPlaying = true;
            document.getElementById('btnMusic').classList.add('spin');
        }).catch(() => {});
    }
    function toggleMusic() {
        if (audio.paused) {
            audio.play(); musicPlaying = true;
            document.getElementById('btnMusic').classList.add('spin');
        } else {
            audio.pause(); musicPlaying = false;
            document.getElementById('btnMusic').classList.remove('spin');
        }
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
        const toast = document.getElementById('toast');
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