<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ $wedding->event_title }} &bull; {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}</title>
    <meta name="description" content="Undangan Pernikahan Digital {{ $wedding->groom_nickname }} & {{ $wedding->bride_nickname }}.">

    <!-- Open Graph for WhatsApp / Social Media Share -->
    <meta property="og:title" content="The Wedding of {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}">
    <meta property="og:description" content="Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir di hari bahagia kami.">
    <meta property="og:image" content="{{ asset('images/wedding/marmer.png') }}">
    <meta property="og:type" content="website">

    <!-- Clean Fonts: Cinzel, Alex Brush, Cormorant Garamond, Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@400;500;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --white: #ffffff;
            --white-soft: #f4f4f5;
            --white-dim: rgba(255, 255, 255, 0.72);
            --gold-accent: #d4af37;
            --glass-bg: rgba(10, 13, 18, 0.88);
            --glass-border: rgba(255, 255, 255, 0.18);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            background-color: #07090e;
            background-image: url("{{ asset('images/wedding/marmer.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: var(--white);
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* ── Smartphone Frame Mockup (Desktop) ───────────────────────────── */
        .wedding-viewport-wrap {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            height: 100dvh;
            padding: 16px 0;
        }

        .wedding-app-container {
            width: 100%;
            max-width: 420px;
            height: 880px;
            max-height: 96vh;
            max-height: 96dvh;
            background-image: url("{{ asset('images/wedding/marmer.png') }}");
            background-size: cover;
            background-position: center top;
            background-repeat: no-repeat;
            position: relative;
            box-shadow: 0 25px 90px rgba(0, 0, 0, 0.95), 0 0 0 10px #12151c;
            border-radius: 46px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 500px) {
            .wedding-viewport-wrap {
                padding: 0;
            }
            .wedding-app-container {
                max-width: 100vw;
                height: 100vh;
                height: 100dvh;
                max-height: 100dvh;
                border-radius: 0;
                box-shadow: none;
                border: none;
            }
        }

        /* ── 1. COVER OVERLAY (BUKA UNDANGAN) ────────────────────────────── */
        .cover-overlay {
            position: absolute;
            inset: 0;
            background-image: url("{{ asset('images/wedding/marmer.png') }}");
            background-size: cover;
            background-position: center top;
            background-repeat: no-repeat;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            padding: 4.5rem 1.8rem 3rem;
            color: #ffffff;
            transition: transform 0.85s cubic-bezier(0.77, 0, 0.175, 1), opacity 0.6s ease;
        }

        .cover-overlay.opened {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }

        /* ── Circular Monogram SVG (Identik Video) ───────────────────────── */
        .curved-names-wrapper {
            width: 250px;
            height: 250px;
            position: relative;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
        }

        .curved-svg {
            width: 100%;
            height: 100%;
            overflow: visible;
        }

        .curved-text {
            font-family: 'Cinzel', serif;
            font-size: 19px;
            font-weight: 700;
            fill: #ffffff;
            letter-spacing: 0.35em;
            text-anchor: middle;
        }

        /* Botanical Branch + Hearts in Center */
        .botanical-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            pointer-events: none;
        }

        .side-heart {
            width: 9px;
            height: 9px;
            fill: #ffffff;
            filter: drop-shadow(0 0 4px rgba(255, 255, 255, 0.4));
        }

        .botanical-svg {
            width: 90px;
            height: 42px;
            filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.35));
        }

        /* Cover Guest Pill & Button */
        .cover-guest-card {
            background: rgba(14, 18, 25, 0.72);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 20px;
            padding: 1.1rem 1.6rem;
            width: 100%;
            max-width: 320px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .cover-guest-label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: var(--white-dim);
            font-weight: 500;
            margin-bottom: 0.35rem;
        }

        .cover-guest-name {
            font-family: 'Cinzel', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.3rem;
            letter-spacing: 0.08em;
        }

        .cover-guest-pax {
            display: inline-block;
            font-size: 0.68rem;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 2px 10px;
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-open-envelope {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.82rem 2.2rem;
            background: #ffffff;
            color: #07090e;
            font-family: 'Cinzel', serif;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            box-shadow: 0 6px 24px rgba(255, 255, 255, 0.25), 0 0 15px rgba(255, 255, 255, 0.2);
            transition: all 0.2s ease;
        }

        .btn-open-envelope:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 30px rgba(255, 255, 255, 0.4);
        }

        /* ── 2. FULLSCREEN STORY SECTION (MIRRORING VIDEO 100%) ───────────── */
        .story-slider-section {
            width: 100%;
            height: 100%;
            flex: 1;
            position: relative;
            background-color: transparent;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
        }

        /* Top Progress Bars (Identik Video & IG Story) */
        .story-progress-bar {
            position: absolute;
            top: 16px;
            left: 14px;
            right: 14px;
            display: flex;
            gap: 4.5px;
            z-index: 50;
        }

        .story-bar-track {
            flex: 1;
            height: 2.2px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        .story-bar-fill {
            height: 100%;
            width: 0%;
            background: #ffffff;
            border-radius: 999px;
            transition: width 0.05s linear;
        }

        .story-bar-track.completed .story-bar-fill {
            width: 100% !important;
        }

        /* Touch Control Zones (Left / Right) */
        .story-touch-left, .story-touch-right {
            position: absolute;
            top: 45px;
            bottom: 60px;
            width: 45%;
            z-index: 40;
            cursor: pointer;
        }
        .story-touch-left { left: 0; }
        .story-touch-right { right: 0; }

        /* Story Slide Card */
        .story-slide {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem 2rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.65s cubic-bezier(0.4, 0, 0.2, 1), transform 0.65s cubic-bezier(0.4, 0, 0.2, 1);
            transform: scale(0.96);
            z-index: 10;
        }

        .story-slide.active {
            opacity: 1;
            pointer-events: auto;
            transform: scale(1);
            z-index: 20;
        }

        /* Slide 2: Save The Date Circle */
        .circle-date-badge {
            width: 210px;
            height: 210px;
            border-radius: 50%;
            border: 1.5px solid rgba(255, 255, 255, 0.7);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            background: rgba(8, 11, 16, 0.25);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.1);
        }

        .cd-day {
            font-family: 'Cinzel', serif;
            font-size: 0.92rem;
            font-weight: 600;
            letter-spacing: 0.25em;
            color: #ffffff;
            text-transform: uppercase;
        }

        .cd-number {
            font-family: 'Cinzel', serif;
            font-size: 3.5rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 0.95;
            margin: 4px 0 6px;
        }

        .cd-month-year {
            font-family: 'Cinzel', serif;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            color: #ffffff;
            text-transform: uppercase;
        }

        /* Slide 3: We Invite You */
        .invite-title-line {
            font-family: 'Cinzel', serif;
            font-size: 1.05rem;
            font-weight: 600;
            letter-spacing: 0.35em;
            color: #ffffff;
            text-transform: uppercase;
            line-height: 1.9;
            text-shadow: 0 2px 12px rgba(0,0,0,0.8);
        }

        /* Slide 4: Scripture */
        .scripture-verse {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.25rem;
            line-height: 1.7;
            color: #ffffff;
            font-style: italic;
            margin-bottom: 1.5rem;
            max-width: 330px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        }

        .scripture-ref {
            font-family: 'Cinzel', serif;
            font-size: 0.78rem;
            letter-spacing: 0.22em;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 600;
            display: block;
            margin-top: 0.5rem;
            font-style: normal;
        }

        /* Slide 5: Couple Cursive Signature */
        .couple-signature-name {
            font-family: 'Alex Brush', cursive;
            font-size: 3.2rem;
            color: #ffffff;
            line-height: 1.15;
            text-shadow: 0 2px 14px rgba(0, 0, 0, 0.8);
        }

        .couple-signature-amp {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-style: italic;
            color: rgba(255, 255, 255, 0.85);
            margin: 0.35rem 0;
        }

        /* Slide 6: Events (Holy Matrimony & Reception) */
        .story-event-block {
            margin-bottom: 1.6rem;
        }
        .story-event-block:last-child {
            margin-bottom: 0;
        }

        .story-event-title {
            font-family: 'Alex Brush', cursive;
            font-size: 2.3rem;
            color: #ffffff;
            line-height: 1;
            margin-bottom: 0.3rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        }

        .story-event-time {
            font-family: 'Cinzel', serif;
            font-size: 0.8rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.15em;
            margin-bottom: 0.35rem;
            text-transform: uppercase;
        }

        .story-event-venue {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 2px;
        }

        .story-event-address {
            font-size: 0.74rem;
            color: var(--white-dim);
            line-height: 1.45;
            max-width: 290px;
            margin: 0 auto;
        }

        /* Slide 7: Kindly Deliver To You */
        .deliver-title {
            font-family: 'Alex Brush', cursive;
            font-size: 2.6rem;
            color: #ffffff;
            margin-bottom: 0.8rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        }

        .deliver-guest {
            font-family: 'Cinzel', serif;
            font-size: 1.25rem;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        }

        /* Slide 8: Thank You */
        .thank-you-calligraphy {
            font-family: 'Alex Brush', cursive;
            font-size: 4.5rem;
            color: #ffffff;
            line-height: 1.1;
            margin-bottom: 1.8rem;
            text-shadow: 0 4px 18px rgba(0, 0, 0, 0.8);
        }

        .btn-open-details {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.78rem 1.8rem;
            background: #ffffff;
            color: #07090e;
            font-family: 'Cinzel', serif;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.25);
            transition: all 0.2s;
        }

        .btn-open-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.4);
        }

        /* ── Floating Controls Bar (Bottom of screen) ────────────────────── */
        .story-bottom-controls {
            position: absolute;
            bottom: 14px;
            left: 16px;
            right: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 60;
            pointer-events: none;
        }

        .btn-audio-toggle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(10, 13, 18, 0.75);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            pointer-events: auto;
            transition: all 0.2s ease;
        }

        .btn-audio-toggle.playing {
            animation: spinVinyl 6s linear infinite;
        }

        @keyframes spinVinyl {
            100% { transform: rotate(360deg); }
        }

        .btn-drawer-peek {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.55rem 1.15rem;
            border-radius: 999px;
            background: rgba(10, 13, 18, 0.75);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            font-family: 'Cinzel', serif;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            cursor: pointer;
            pointer-events: auto;
            transition: all 0.2s ease;
        }

        .btn-drawer-peek:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: #ffffff;
        }

        /* ── 3. BOTTOM SHEET DETAILS DRAWER (RSVP, LOKASI, AMPLOP) ───────── */
        .details-drawer-modal {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9998;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.35s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .details-drawer-modal.active {
            opacity: 1;
            pointer-events: auto;
        }

        .details-drawer-content {
            width: 100%;
            max-height: 85%;
            background: rgba(12, 15, 22, 0.96);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1.5px solid rgba(255, 255, 255, 0.2);
            border-radius: 28px 28px 0 0;
            padding: 1rem 1.4rem 2.5rem;
            overflow-y: auto;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            color: #ffffff;
        }

        .details-drawer-modal.active .details-drawer-content {
            transform: translateY(0);
        }

        .drawer-drag-pill {
            width: 44px;
            height: 4px;
            background: rgba(255, 255, 255, 0.35);
            border-radius: 999px;
            margin: 0 auto 1.25rem;
            cursor: pointer;
        }

        .drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .drawer-title {
            font-family: 'Cinzel', serif;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #ffffff;
        }

        .btn-drawer-close {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Glass Card in Drawer */
        .drawer-glass-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 18px;
            padding: 1.25rem;
            margin-bottom: 1.1rem;
        }

        .card-header-line {
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: 'Cinzel', serif;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #ffffff;
            margin-bottom: 0.85rem;
        }

        .btn-action-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0.52rem 1.1rem;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 700;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            transition: all 0.2s;
        }

        .btn-action-pill:hover {
            background: #ffffff;
            color: #07090e;
        }

        /* Forms in Drawer */
        .drawer-input {
            width: 100%;
            height: 40px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 10px;
            padding: 0 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.82rem;
            color: #ffffff;
            outline: none;
            margin-bottom: 0.75rem;
        }
        .drawer-input:focus {
            border-color: #ffffff;
        }

        .drawer-textarea {
            width: 100%;
            min-height: 75px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 10px;
            padding: 10px 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.82rem;
            color: #ffffff;
            outline: none;
            resize: vertical;
            margin-bottom: 0.75rem;
        }
        .drawer-textarea:focus {
            border-color: #ffffff;
        }

        .btn-drawer-submit {
            width: 100%;
            padding: 0.75rem;
            background: #ffffff;
            color: #07090e;
            font-family: 'Cinzel', serif;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-drawer-submit:hover {
            opacity: 0.9;
        }

        /* Wish stream items */
        .wish-item-box {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 0.75rem 0.9rem;
            margin-bottom: 0.55rem;
        }
    </style>
</head>
<body>

    <div class="wedding-viewport-wrap">
        <div class="wedding-app-container" id="weddingAppFrame">

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- 1. COVER OVERLAY (BUKA UNDANGAN)                              --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <div class="cover-overlay" id="coverOverlay">
                {{-- Curved Names & Flower (Identik Video) --}}
                <div style="width:100%;">
                    <div style="font-family:'Cinzel',serif; font-size:0.75rem; letter-spacing:0.35em; color:rgba(255,255,255,0.8); text-transform:uppercase; margin-bottom:1.5rem;">
                        The Wedding Celebration of
                    </div>

                    <div class="curved-names-wrapper">
                        <svg viewBox="0 0 260 260" class="curved-svg">
                            <defs>
                                <path id="curveTopCover" d="M 45,130 A 85,85 0 0,1 215,130" fill="none" />
                                <path id="curveBottomCover" d="M 45,130 A 85,85 0 0,0 215,130" fill="none" />
                            </defs>
                            <text class="curved-text">
                                <textPath href="#curveTopCover" startOffset="50%">
                                    {{ strtoupper($wedding->groom_nickname ?: 'ANDREW') }}
                                </textPath>
                            </text>
                            <text class="curved-text">
                                <textPath href="#curveBottomCover" startOffset="50%">
                                    {{ strtoupper($wedding->bride_nickname ?: 'LUCIA') }}
                                </textPath>
                            </text>
                        </svg>

                        <!-- Center Botanical Flower & Hearts (Identik Image 1) -->
                        <div class="botanical-center">
                            <svg viewBox="0 0 24 24" class="side-heart">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <svg viewBox="0 0 110 50" class="botanical-svg" fill="none" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M 12,26 Q 36,22 64,25"/>
                                <path d="M 22,25 C 18,30 22,35 28,33 C 28,29 25,26 22,25 Z"/>
                                <path d="M 36,24 C 36,17 42,14 46,17 C 45,21 41,24 36,24 Z"/>
                                <path d="M 50,25 C 48,31 53,34 58,32 C 57,28 54,25 50,25 Z"/>
                                <path d="M 58,25 C 59,18 65,17 69,20 C 67,23 63,25 58,25 Z"/>
                                <path d="M 72,26 C 73,20 78,16 84,17 C 88,18 90,21 89,24 C 92,25 95,26 95,30 C 94,34 90,37 86,36 C 83,38 78,37 75,34 C 73,32 72,29 72,26 Z"/>
                                <path d="M 78,20 C 80,23 82,28 82,33" stroke-width="1.2"/>
                                <path d="M 84,22 C 86,25 87,29 85,33" stroke-width="1.2"/>
                            </svg>
                            <svg viewBox="0 0 24 24" class="side-heart">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Center Guest Card Box --}}
                <div class="cover-guest-card">
                    <div class="cover-guest-label">Kepada Yth. Bapak/Ibu/Saudara/i:</div>
                    <div class="cover-guest-name">{{ $guestName }}</div>
                    @if($guest)
                        <div class="cover-guest-pax">
                            {{ $guest->group_name ?: 'Tamu Undangan' }} &bull; {{ $guest->pax ?: 1 }} Pax
                        </div>
                    @else
                        <div class="cover-guest-pax">Tamu Undangan Terhormat</div>
                    @endif
                </div>

                {{-- Bottom Open Invitation Button --}}
                <div>
                    <button type="button" class="btn-open-envelope" onclick="openInvitation()">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <span>Buka Undangan</span>
                    </button>
                    <div style="font-size:0.68rem; color:var(--white-dim); margin-top:0.75rem; letter-spacing:0.05em;">
                        Ketuk untuk membuka &amp; mendengarkan musik
                    </div>
                </div>
            </div>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- 2. FULLSCREEN STORY SECTION (100% IDENTIK DENGAN VIDEO)       --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <section class="story-slider-section" id="storySliderSection">
                <!-- Top Progress Bars -->
                <div class="story-progress-bar">
                    @for($i = 0; $i < 8; $i++)
                        <div class="story-bar-track {{ $i === 0 ? 'active' : '' }}" id="storyBar{{ $i }}">
                            <div class="story-bar-fill" id="storyFill{{ $i }}"></div>
                        </div>
                    @endfor
                </div>

                <!-- Touch Control Zones -->
                <div class="story-touch-left" onclick="prevStorySlide()" title="Slide Sebelumnya"></div>
                <div class="story-touch-right" onclick="nextStorySlide()" title="Slide Selanjutnya"></div>

                {{-- SLIDE 1: Curving Names & Floral Icon (Persis Image 1) --}}
                <div class="story-slide active" id="slide0">
                    <div class="curved-names-wrapper">
                        <svg viewBox="0 0 260 260" class="curved-svg">
                            <defs>
                                <path id="curveTopSlide" d="M 45,130 A 85,85 0 0,1 215,130" fill="none" />
                                <path id="curveBottomSlide" d="M 45,130 A 85,85 0 0,0 215,130" fill="none" />
                            </defs>
                            <text class="curved-text">
                                <textPath href="#curveTopSlide" startOffset="50%">
                                    {{ strtoupper($wedding->groom_nickname ?: 'ANDREW') }}
                                </textPath>
                            </text>
                            <text class="curved-text">
                                <textPath href="#curveBottomSlide" startOffset="50%">
                                    {{ strtoupper($wedding->bride_nickname ?: 'LUCIA') }}
                                </textPath>
                            </text>
                        </svg>

                        <!-- Center Botanical Line Art & Hearts -->
                        <div class="botanical-center">
                            <svg viewBox="0 0 24 24" class="side-heart">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <svg viewBox="0 0 110 50" class="botanical-svg" fill="none" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M 12,26 Q 36,22 64,25"/>
                                <path d="M 22,25 C 18,30 22,35 28,33 C 28,29 25,26 22,25 Z"/>
                                <path d="M 36,24 C 36,17 42,14 46,17 C 45,21 41,24 36,24 Z"/>
                                <path d="M 50,25 C 48,31 53,34 58,32 C 57,28 54,25 50,25 Z"/>
                                <path d="M 58,25 C 59,18 65,17 69,20 C 67,23 63,25 58,25 Z"/>
                                <path d="M 72,26 C 73,20 78,16 84,17 C 88,18 90,21 89,24 C 92,25 95,26 95,30 C 94,34 90,37 86,36 C 83,38 78,37 75,34 C 73,32 72,29 72,26 Z"/>
                                <path d="M 78,20 C 80,23 82,28 82,33" stroke-width="1.2"/>
                                <path d="M 84,22 C 86,25 87,29 85,33" stroke-width="1.2"/>
                            </svg>
                            <svg viewBox="0 0 24 24" class="side-heart">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- SLIDE 2: Save The Date Circle --}}
                <div class="story-slide" id="slide1">
                    <div class="circle-date-badge">
                        <div class="cd-day">
                            {{ $wedding->event_date ? $wedding->event_date->isoFormat('dddd') : 'Saturday' }}
                        </div>
                        <div class="cd-number">
                            {{ $wedding->event_date ? $wedding->event_date->format('d') : '10' }}
                        </div>
                        <div class="cd-month-year">
                            {{ $wedding->event_date ? $wedding->event_date->isoFormat('MMMM Y') : 'July 2021' }}
                        </div>
                    </div>
                </div>

                {{-- SLIDE 3: We Invite You --}}
                <div class="story-slide" id="slide2">
                    <div class="invite-title-line">
                        WE INVITE YOU<br>
                        TO CELEBRATE OUR WEDDING
                    </div>
                </div>

                {{-- SLIDE 4: Scripture / Ayat Suci --}}
                <div class="story-slide" id="slide3">
                    <div class="scripture-verse">
                        &ldquo;So, they are no longer two, but one.<br>
                        Therefore what God has joined together,<br>
                        let man not separate.&rdquo;
                        <span class="scripture-ref">&bull; Matthew 19:6 &bull;</span>
                    </div>
                    <div style="font-family:'Cormorant Garamond',serif; font-size:1.05rem; color:rgba(255,255,255,0.85); line-height:1.6; max-width:300px;">
                        Request the honour of your presence at the marriage of our beloved children
                    </div>
                </div>

                {{-- SLIDE 5: The Couple (Alex Brush Calligraphy) --}}
                <div class="story-slide" id="slide4">
                    <div class="couple-signature-name">{{ $wedding->groom_name }}</div>
                    @if($wedding->groom_parents)
                        <div style="font-size:0.75rem; color:var(--white-dim); margin-top:2px;">{{ $wedding->groom_parents }}</div>
                    @endif

                    <div class="couple-signature-amp">&amp;</div>

                    <div class="couple-signature-name">{{ $wedding->bride_name }}</div>
                    @if($wedding->bride_parents)
                        <div style="font-size:0.75rem; color:var(--white-dim); margin-top:2px;">{{ $wedding->bride_parents }}</div>
                    @endif
                </div>

                {{-- SLIDE 6: Rangkaian Acara (Holy Matrimony & Reception) --}}
                <div class="story-slide" id="slide5">
                    @if($wedding->ceremony_date)
                        <div class="story-event-block">
                            <div class="story-event-title">Holy Matrimony</div>
                            <div class="story-event-time">at {{ $wedding->ceremony_date->format('H:i') }} WIB</div>
                            <div class="story-event-venue">{{ $wedding->ceremony_location_name }}</div>
                            <div class="story-event-address">{{ $wedding->ceremony_address }}</div>
                        </div>
                    @endif

                    <div class="story-event-block">
                        <div class="story-event-title">Wedding Reception</div>
                        <div class="story-event-time">at {{ $wedding->event_date ? $wedding->event_date->format('H:i') . ' WIB' : '18:00 WIB' }}</div>
                        <div class="story-event-venue">{{ $wedding->event_location_name }}</div>
                        <div class="story-event-address">{{ $wedding->event_address }}</div>
                    </div>
                </div>

                {{-- SLIDE 7: Kindly Deliver To You (VIP Guest) --}}
                <div class="story-slide" id="slide6">
                    <div class="deliver-title">Kindly deliver to You</div>
                    <div class="deliver-guest">{{ $guestName }}</div>
                    @if($guest)
                        <div style="font-size:0.75rem; color:var(--white-dim); margin-top:0.5rem; letter-spacing:0.1em;">
                            {{ $guest->group_name ?: 'Tamu Undangan' }} &bull; {{ $guest->pax ?: 1 }} Pax
                        </div>
                    @endif
                </div>

                {{-- SLIDE 8: Thank You & Open Details Button --}}
                <div class="story-slide" id="slide7">
                    <div class="thank-you-calligraphy">Thank you</div>
                    <button type="button" class="btn-open-details" onclick="openDetailsDrawer()">
                        <span>Rincian Acara &amp; RSVP</span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
            </section>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- FLOATING BOTTOM CONTROLS (MUSIC & QUICK RSVP)                 --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <div class="story-bottom-controls">
                <button type="button" class="btn-audio-toggle playing" id="audioToggleBtn" onclick="toggleAudio()" title="Musik Latar">
                    <svg id="audioIconPlay" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 18V5l12-2v13"/>
                        <circle cx="6" cy="18" r="3"/>
                        <circle cx="18" cy="16" r="3"/>
                    </svg>
                </button>

                <button type="button" class="btn-drawer-peek" onclick="openDetailsDrawer()">
                    <span>Rincian &amp; RSVP</span>
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M5 15l7-7 7 7"/>
                    </svg>
                </button>
            </div>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- 3. BOTTOM SHEET DETAILS DRAWER (LOKASI, QR, AMPLOP, RSVP)    --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <div class="details-drawer-modal" id="detailsDrawerModal" onclick="handleDrawerBackdropClick(event)">
                <div class="details-drawer-content" id="detailsDrawerContent">
                    <div class="drawer-drag-pill" onclick="closeDetailsDrawer()"></div>

                    <div class="drawer-header">
                        <div class="drawer-title">Informasi &amp; Kehadiran</div>
                        <button type="button" class="btn-drawer-close" onclick="closeDetailsDrawer()">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- ── 1. VIP QR CODE ENTRY (TAMU TERDAFTAR) ── --}}
                    @if($guest)
                        <div class="drawer-glass-card" style="text-align:center;">
                            <div class="card-header-line" style="justify-content:center;">
                                <span>TIKET MASUK VIP &bull; QR CHECK-IN</span>
                            </div>
                            <div style="font-family:'Cinzel',serif; font-size:1.1rem; font-weight:700; color:#ffffff; margin-bottom:0.25rem;">
                                {{ $guest->name }}
                            </div>
                            <div style="font-size:0.72rem; color:var(--white-dim); margin-bottom:0.75rem;">
                                Tunjukkan QR Code ini ke petugas resepsionis saat tiba di lokasi.
                            </div>
                            <div style="background:#ffffff; border-radius:12px; padding:8px; display:inline-block; margin-bottom:0.6rem;">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($guest->qr_code) }}"
                                     alt="QR Code" style="display:block; width:125px; height:125px;">
                            </div>
                            <div style="font-family:monospace; font-weight:800; font-size:0.95rem; color:#ffffff;">
                                {{ $guest->qr_code }}
                            </div>
                            <div style="font-size:0.68rem; color:var(--white-dim); margin-top:2px;">
                                Kuota: {{ $guest->pax ?: 1 }} Orang ({{ $guest->group_name ?: 'Tamu' }})
                            </div>
                        </div>
                    @endif

                    {{-- ── 2. AGENDA & NAVIGASI PETA ── --}}
                    <div class="drawer-glass-card">
                        <div class="card-header-line">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            <span>Waktu &amp; Lokasi Acara</span>
                        </div>

                        @if($wedding->ceremony_date)
                            <div style="margin-bottom:1rem; padding-bottom:0.85rem; border-bottom:1px solid rgba(255,255,255,0.08);">
                                <div style="font-size:0.72rem; color:var(--white-dim); text-transform:uppercase; letter-spacing:0.1em; font-weight:600;">
                                    Akad Nikah / Pemberkatan
                                </div>
                                <div style="font-family:'Cinzel',serif; font-size:0.9rem; font-weight:700; color:#fff; margin:2px 0;">
                                    {{ $wedding->ceremony_date->isoFormat('dddd, D MMMM Y &bull; HH:mm') }} WIB
                                </div>
                                <div style="font-size:0.8rem; font-weight:600; color:#fff;">{{ $wedding->ceremony_location_name }}</div>
                                <div style="font-size:0.72rem; color:var(--white-dim); margin-top:2px;">{{ $wedding->ceremony_address }}</div>
                            </div>
                        @endif

                        <div>
                            <div style="font-size:0.72rem; color:var(--white-dim); text-transform:uppercase; letter-spacing:0.1em; font-weight:600;">
                                Resepsi Pernikahan
                            </div>
                            <div style="font-family:'Cinzel',serif; font-size:0.9rem; font-weight:700; color:#fff; margin:2px 0;">
                                {{ $wedding->event_date ? $wedding->event_date->isoFormat('dddd, D MMMM Y &bull; HH:mm') . ' WIB' : '-' }}
                            </div>
                            <div style="font-size:0.8rem; font-weight:600; color:#fff;">{{ $wedding->event_location_name }}</div>
                            <div style="font-size:0.72rem; color:var(--white-dim); margin-top:2px;">{{ $wedding->event_address }}</div>

                            {{-- Tombol Navigasi --}}
                            <div style="display:flex; gap:8px; margin-top:0.85rem; flex-wrap:wrap;">
                                @if($wedding->event_map_url)
                                    <a href="{{ $wedding->event_map_url }}" target="_blank" class="btn-action-pill" style="flex:1;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span>Google Maps</span>
                                    </a>
                                @endif
                                <a href="https://waze.com/ul?q={{ urlencode($wedding->event_location_name ?: $wedding->event_address) }}" target="_blank" class="btn-action-pill" style="flex:1;">
                                    <span>Waze Navigation</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- ── 3. AMPLOP DIGITAL / TANDA KASIH ── --}}
                    @if($wedding->digital_envelope_enabled)
                        <div class="drawer-glass-card" style="text-align:center;">
                            <div class="card-header-line" style="justify-content:center;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                                <span>Amplop Digital</span>
                            </div>
                            <p style="font-size:0.74rem; color:var(--white-dim); margin-bottom:1rem; line-height:1.45;">
                                Doa restu Anda merupakan karunia terindah. Namun bila berkenan memberikan tanda kasih:
                            </p>

                            @if($wedding->bank_name && $wedding->bank_account_number)
                                <div style="background:rgba(0,0,0,0.4); border:1px solid rgba(255,255,255,0.18); border-radius:14px; padding:1rem; margin-bottom:0.75rem;">
                                    <div style="font-family:'Cinzel',serif; font-size:0.85rem; font-weight:700; color:#fff;">
                                        {{ $wedding->bank_name }}
                                    </div>
                                    <div style="font-family:monospace; font-size:1.15rem; font-weight:800; color:#ffffff; margin:0.3rem 0;">
                                        {{ $wedding->bank_account_number }}
                                    </div>
                                    <div style="font-size:0.72rem; color:var(--white-dim); margin-bottom:0.6rem;">
                                        a.n. {{ $wedding->bank_account_name }}
                                    </div>
                                    <button type="button" onclick="copyAccount('{{ $wedding->bank_account_number }}')" class="btn-action-pill" style="padding:0.4rem 1.1rem;">
                                        Salin Nomor Rekening
                                    </button>
                                </div>
                            @endif

                            @if($wedding->ewallet_name && $wedding->ewallet_number)
                                <div style="background:rgba(0,0,0,0.4); border:1px solid rgba(255,255,255,0.18); border-radius:14px; padding:1rem;">
                                    <div style="font-family:'Cinzel',serif; font-size:0.85rem; font-weight:700; color:#fff;">
                                        {{ $wedding->ewallet_name }}
                                    </div>
                                    <div style="font-family:monospace; font-size:1.15rem; font-weight:800; color:#ffffff; margin:0.3rem 0;">
                                        {{ $wedding->ewallet_number }}
                                    </div>
                                    <div style="font-size:0.72rem; color:var(--white-dim); margin-bottom:0.6rem;">
                                        a.n. {{ $wedding->ewallet_account_name }}
                                    </div>
                                    <button type="button" onclick="copyAccount('{{ $wedding->ewallet_number }}')" class="btn-action-pill" style="padding:0.4rem 1.1rem;">
                                        Salin Nomor E-Wallet
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- ── 4. FORM RSVP & DOA RESTU ── --}}
                    <div class="drawer-glass-card">
                        <div class="card-header-line">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <span>Konfirmasi Kehadiran &amp; Ucapan</span>
                        </div>

                        <form action="{{ route('wedding.wish', ['slug' => $wedding->slug]) }}" method="POST" id="rsvpForm" onsubmit="submitRsvp(event)">
                            @csrf
                            <input type="hidden" name="guest_id" value="{{ $guest ? $guest->id : '' }}">

                            <input type="text" name="sender_name" class="drawer-input" placeholder="Nama Anda"
                                   value="{{ $guest ? $guest->name : ($guestName !== 'Tamu Undangan' ? $guestName : '') }}" required>

                            <input type="text" name="city" class="drawer-input" list="citySuggestions" placeholder="Kota Asal (Contoh: Jakarta / Surabaya)">
                            <datalist id="citySuggestions">
                                <option value="Jakarta"><option value="Surabaya"><option value="Bandung"><option value="Medan">
                                <option value="Semarang"><option value="Yogyakarta"><option value="Bali"><option value="Makassar">
                            </datalist>

                            <select name="attendance" class="drawer-input" style="cursor:pointer;">
                                <option value="hadir" style="background:#111;">Saya Akan Hadir</option>
                                <option value="tidak" style="background:#111;">Maaf, Berhalangan Hadir</option>
                                <option value="ragu" style="background:#111;">Masih Ragu</option>
                            </select>

                            <textarea name="message" class="drawer-textarea" placeholder="Tulis doa restu & ucapan manis Anda..." required></textarea>

                            <button type="submit" class="btn-drawer-submit" id="btnSubmitWish">
                                Kirim Doa Restu
                            </button>
                        </form>
                    </div>

                    {{-- ── 5. LIVE UCAPAN & DOA RESTU ── --}}
                    @if(isset($wishes) && $wishes->count() > 0)
                        <div class="drawer-glass-card">
                            <div class="card-header-line">
                                <span>Doa Restu Tamu Undangan ({{ $wishes->count() }})</span>
                            </div>
                            <div style="max-height:220px; overflow-y:auto;">
                                @foreach($wishes as $wish)
                                    <div class="wish-item-box">
                                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2px;">
                                            <div style="font-weight:700; font-size:0.8rem; color:#fff;">{{ $wish->sender_name }}</div>
                                            <span style="font-size:0.65rem; color:var(--white-dim);">{{ $wish->created_at ? $wish->created_at->diffForHumans() : '' }}</span>
                                        </div>
                                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.85); line-height:1.4;">{{ $wish->message }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div style="text-align:center; padding-top:1rem;">
                        <div style="font-family:'Cinzel',serif; font-size:0.75rem; letter-spacing:0.18em; color:rgba(255,255,255,0.5);">
                            BUYLE.ID &bull; DIGITAL WEDDING
                        </div>
                    </div>
                </div>
            </div>

            {{-- Background Music Audio Tag --}}
            <audio id="bgMusicAudio" loop preload="auto">
                @if($wedding->music_url)
                    <source src="{{ $wedding->music_url }}" type="audio/mpeg">
                @else
                    <source src="https://assets.mixkit.co/music/preview/mixkit-romantic-wedding-love-680.mp3" type="audio/mpeg">
                @endif
            </audio>

        </div>
    </div>

    {{-- Toast Notification --}}
    <div id="toastCopyNotification" style="position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#ffffff; color:#07090e; padding:9px 20px; border-radius:999px; font-weight:700; font-size:0.78rem; font-family:'Cinzel',serif; letter-spacing:0.1em; box-shadow:0 6px 25px rgba(0,0,0,0.5); opacity:0; pointer-events:none; transition:opacity 0.3s; z-index:99999;">
        Nomor Berhasil Disalin!
    </div>

    <script>
        // ── State & Variables ──────────────────────────────────────────────
        const TOTAL_SLIDES = 8;
        const SLIDE_DURATION = 5000; // 5 Detik per slide
        let currentSlide = 0;
        let slideTimer = null;
        let slideStartTime = 0;
        let slideElapsed = 0;
        let isStoryPaused = false;
        let isAudioPlaying = false;

        const audio = document.getElementById('bgMusicAudio');
        const audioBtn = document.getElementById('audioToggleBtn');

        // ── 1. Buka Undangan ───────────────────────────────────────────────
        function openInvitation() {
            const cover = document.getElementById('coverOverlay');
            cover.classList.add('opened');

            // Play background audio
            playAudio();

            // Start Story
            startStory();
        }

        // ── 2. Story Engine ────────────────────────────────────────────────
        function startStory() {
            showSlide(0);
        }

        function showSlide(index) {
            if (index < 0) index = 0;
            if (index >= TOTAL_SLIDES) {
                // When story finishes -> pause and open details drawer
                openDetailsDrawer();
                return;
            }

            currentSlide = index;

            // Update Slide classes
            for (let i = 0; i < TOTAL_SLIDES; i++) {
                const slideEl = document.getElementById('slide' + i);
                const trackEl = document.getElementById('storyBar' + i);
                const fillEl = document.getElementById('storyFill' + i);

                if (i < index) {
                    slideEl.classList.remove('active');
                    trackEl.classList.add('completed');
                    trackEl.classList.remove('active');
                    fillEl.style.width = '100%';
                } else if (i === index) {
                    slideEl.classList.add('active');
                    trackEl.classList.add('active');
                    trackEl.classList.remove('completed');
                    fillEl.style.width = '0%';
                } else {
                    slideEl.classList.remove('active');
                    trackEl.classList.remove('active', 'completed');
                    fillEl.style.width = '0%';
                }
            }

            // Animate Current Slide Fill
            runSlideProgress();
        }

        function runSlideProgress() {
            clearTimeout(slideTimer);
            cancelAnimationFrame(window.progressAnimFrame);

            slideStartTime = performance.now();
            const fillEl = document.getElementById('storyFill' + currentSlide);

            function step(now) {
                if (isStoryPaused) {
                    window.progressAnimFrame = requestAnimationFrame(step);
                    return;
                }

                const progress = Math.min((now - slideStartTime) / SLIDE_DURATION, 1);
                if (fillEl) fillEl.style.width = (progress * 100) + '%';

                if (progress < 1) {
                    window.progressAnimFrame = requestAnimationFrame(step);
                } else {
                    nextStorySlide();
                }
            }

            window.progressAnimFrame = requestAnimationFrame(step);
        }

        function nextStorySlide() {
            if (currentSlide < TOTAL_SLIDES - 1) {
                showSlide(currentSlide + 1);
            } else {
                // End of slides -> Open details drawer
                openDetailsDrawer();
            }
        }

        function prevStorySlide() {
            if (currentSlide > 0) {
                showSlide(currentSlide - 1);
            } else {
                showSlide(0);
            }
        }

        function pauseStory() {
            isStoryPaused = true;
        }

        function resumeStory() {
            isStoryPaused = false;
        }

        // ── 3. Bottom Sheet Drawer ─────────────────────────────────────────
        function openDetailsDrawer() {
            pauseStory();
            document.getElementById('detailsDrawerModal').classList.add('active');
        }

        function closeDetailsDrawer() {
            document.getElementById('detailsDrawerModal').classList.remove('active');
            resumeStory();
        }

        function handleDrawerBackdropClick(event) {
            if (event.target.id === 'detailsDrawerModal') {
                closeDetailsDrawer();
            }
        }

        // ── 4. Audio Controller ────────────────────────────────────────────
        function playAudio() {
            if (!audio) return;
            audio.play().then(() => {
                isAudioPlaying = true;
                if (audioBtn) audioBtn.classList.add('playing');
            }).catch(e => {
                console.warn('Autoplay prevented:', e);
            });
        }

        function toggleAudio() {
            if (!audio) return;
            if (audio.paused) {
                audio.play();
                isAudioPlaying = true;
                if (audioBtn) audioBtn.classList.add('playing');
            } else {
                audio.pause();
                isAudioPlaying = false;
                if (audioBtn) audioBtn.classList.remove('playing');
            }
        }

        // ── 5. Copy Account & Toast ────────────────────────────────────────
        function copyAccount(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text);
            } else {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            }

            const toast = document.getElementById('toastCopyNotification');
            toast.style.opacity = '1';
            setTimeout(() => {
                toast.style.opacity = '0';
            }, 2500);
        }

        // ── 6. Form Submission (RSVP) ──────────────────────────────────────
        function submitRsvp(event) {
            const btn = document.getElementById('btnSubmitWish');
            if (btn) {
                btn.disabled = true;
                btn.innerText = 'Mengirim Doa...';
            }
        }
    </script>
</body>
</html>