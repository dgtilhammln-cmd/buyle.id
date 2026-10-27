<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $wedding->event_title }} &bull; {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}</title>
    <meta name="description" content="Undangan Pernikahan Digital {{ $wedding->groom_nickname }} & {{ $wedding->bride_nickname }}.">

    <!-- Open Graph for WhatsApp / Social Media Share -->
    <meta property="og:title" content="Undangan Pernikahan {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}">
    <meta property="og:description" content="Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir di hari bahagia kami.">
    <meta property="og:image" content="{{ asset('images/wedding/theme_blackgold_bg.jpg') }}">
    <meta property="og:type" content="website">

    <!-- Luxury Fonts: Cinzel, Alex Brush, Pinyon Script, Cormorant Garamond, Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@400;500;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Montserrat:wght@300;400;500;600;700;800&family=Pinyon+Script&display=swap" rel="stylesheet">

    <style>
        :root {
            --gold-light: #fef08a;
            --gold: #d4af37;
            --gold-gradient: linear-gradient(135deg, #fceda2 0%, #d4af37 50%, #aa7a1e 100%);
            --gold-border: rgba(212, 175, 55, 0.35);
            --bg-dark: #07090d;
            --surface-glass: rgba(10, 13, 19, 0.76);
            --surface-glass-card: rgba(14, 18, 25, 0.88);
            --text-gold: #e5c158;
            --text-cream: #f3edd8;
            --text-dim: #9aa1ad;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            background-color: #050608;
            background-image: radial-gradient(circle at 50% 20%, rgba(212, 175, 55, 0.08) 0%, transparent 60%),
                              url("{{ asset('images/wedding/theme_blackgold_bg.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: var(--text-cream);
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        /* ── Desktop Smartphone Frame Mockup ────────────────────────────── */
        .wedding-viewport-wrap {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 24px 0;
        }

        .wedding-app-container {
            width: 100%;
            max-width: 440px;
            min-height: 94vh;
            background-image: url("{{ asset('images/wedding/theme_blackgold_bg.jpg') }}");
            background-size: cover;
            background-position: center top;
            background-repeat: no-repeat;
            background-attachment: scroll;
            position: relative;
            box-shadow: 0 30px 100px rgba(0, 0, 0, 0.9), 0 0 0 1px rgba(212, 175, 55, 0.25);
            border-radius: 42px;
            overflow: hidden;
            border: 8px solid #14171d;
        }

        @media (max-width: 500px) {
            .wedding-viewport-wrap {
                padding: 0;
            }
            .wedding-app-container {
                max-width: 100vw;
                min-height: 100vh;
                border-radius: 0;
                border: none;
                box-shadow: none;
            }
        }

        /* ── Floating Gold Particles Effect ─────────────────────────────── */
        .particles-bg {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image: radial-gradient(1.5px 1.5px at 20px 30px, #fce08b, rgba(0,0,0,0)),
                              radial-gradient(1.2px 1.2px at 140px 120px, #d4af37, rgba(0,0,0,0)),
                              radial-gradient(1.8px 1.8px at 280px 220px, #fff, rgba(0,0,0,0)),
                              radial-gradient(1.4px 1.4px at 90px 340px, #fce08b, rgba(0,0,0,0)),
                              radial-gradient(1.6px 1.6px at 320px 480px, #e5c158, rgba(0,0,0,0));
            background-repeat: repeat;
            background-size: 360px 520px;
            opacity: 0.45;
            z-index: 1;
            animation: shimmerFloat 25s linear infinite;
        }

        @keyframes shimmerFloat {
            0% { background-position: 0 0; }
            100% { background-position: 0 520px; }
        }

        /* ── 1. COVER OVERLAY (BUKA UNDANGAN) ───────────────────────────── */
        .cover-overlay {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 440px;
            height: 100%;
            background-image: radial-gradient(circle at center, rgba(5,7,11,0.55) 0%, rgba(5,7,11,0.92) 80%),
                              url("{{ asset('images/wedding/theme_blackgold_bg.jpg') }}");
            background-size: cover;
            background-position: center;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            padding: 3.5rem 1.75rem 2.5rem;
            color: #ffffff;
            transition: transform 0.9s cubic-bezier(0.77, 0, 0.175, 1), opacity 0.7s ease;
        }

        @media (max-width: 500px) {
            .cover-overlay {
                max-width: 100vw;
            }
        }

        .cover-overlay.opened {
            transform: translate(-50%, -100%);
            opacity: 0;
            pointer-events: none;
        }

        /* Circular Monogram Header */
        .curved-names-wrapper {
            width: 240px;
            height: 240px;
            position: relative;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeInZoom 1.4s ease forwards;
        }

        .curved-svg {
            width: 100%;
            height: 100%;
            overflow: visible;
        }

        .curved-text {
            font-family: 'Cinzel', serif;
            font-size: 16px;
            font-weight: 700;
            fill: #f3edd8;
            letter-spacing: 0.38em;
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.35);
        }

        .botanical-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: var(--text-gold);
        }

        .botanical-center svg {
            width: 32px;
            height: 32px;
            filter: drop-shadow(0 0 8px rgba(212, 175, 55, 0.4));
        }

        .cover-guest-card {
            background: rgba(14, 18, 25, 0.75);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid var(--gold-border);
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            width: 100%;
            max-width: 340px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(212, 175, 55, 0.05);
            animation: fadeInUp 1.2s ease forwards;
        }

        .cover-guest-label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--text-dim);
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        .cover-guest-name {
            font-family: 'Cinzel', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.3rem;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cover-guest-pax {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7rem;
            color: var(--text-gold);
            background: rgba(212, 175, 55, 0.12);
            border: 1px solid rgba(212, 175, 55, 0.3);
            padding: 2px 10px;
            border-radius: 999px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .btn-open-envelope {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 0.85rem 2.2rem;
            background: var(--gold-gradient);
            color: #0a0d14;
            font-family: 'Cinzel', serif;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            box-shadow: 0 6px 24px rgba(212, 175, 55, 0.4), 0 0 15px rgba(212, 175, 55, 0.2);
            transition: all 0.25s ease;
            animation: pulseGlow 2.5s infinite;
        }

        .btn-open-envelope:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 8px 32px rgba(212, 175, 55, 0.6);
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 6px 24px rgba(212, 175, 55, 0.35); }
            50% { box-shadow: 0 8px 32px rgba(212, 175, 55, 0.65), 0 0 25px rgba(254, 240, 138, 0.4); }
        }

        /* ── 2. INTERACTIVE STORY / SLIDES SECTION (MIRRORING VIDEO) ──────── */
        .story-slider-section {
            width: 100%;
            height: 580px;
            position: relative;
            background-color: transparent;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
        }

        /* Top Progress Bars (Instagram/Story style) */
        .story-progress-bar {
            position: absolute;
            top: 14px;
            left: 16px;
            right: 16px;
            display: flex;
            gap: 5px;
            z-index: 50;
        }

        .story-bar-track {
            flex: 1;
            height: 2.5px;
            background: rgba(255, 255, 255, 0.22);
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

        .story-bar-track.active .story-bar-fill {
            background: var(--gold-gradient);
        }

        .story-bar-track.completed .story-bar-fill {
            width: 100% !important;
            background: #ffffff;
        }

        /* Individual Slides */
        .story-slide {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem 1.8rem 2.5rem;
            opacity: 0;
            visibility: hidden;
            transform: scale(0.96);
            transition: opacity 0.5s ease, transform 0.5s ease, visibility 0.5s;
            z-index: 10;
        }

        .story-slide.active {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
            z-index: 20;
        }

        /* Tap Touch Zones for Story Next / Previous */
        .story-touch-left, .story-touch-right {
            position: absolute;
            top: 40px;
            bottom: 40px;
            width: 32%;
            z-index: 45;
            cursor: pointer;
        }
        .story-touch-left { left: 0; }
        .story-touch-right { right: 0; }

        /* Slide 2: Save The Date Circle */
        .circle-date-badge {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            border: 1.5px solid rgba(243, 237, 216, 0.75);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            padding: 1rem;
            box-shadow: 0 0 25px rgba(212, 175, 55, 0.15);
            animation: fadeInZoom 0.8s ease;
        }

        .cd-day {
            font-family: 'Alex Brush', cursive;
            font-size: 1.6rem;
            color: #ffffff;
            line-height: 1.1;
        }

        .cd-number {
            font-family: 'Cinzel', serif;
            font-size: 3.2rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 0.95;
            margin: 2px 0 4px;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cd-month-year {
            font-family: 'Cinzel', serif;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            color: #f3edd8;
            text-transform: uppercase;
        }

        /* Slide 3: We Invite You */
        .invite-title-line {
            font-family: 'Cinzel', serif;
            font-size: 0.92rem;
            font-weight: 600;
            letter-spacing: 0.32em;
            color: var(--text-cream);
            text-transform: uppercase;
            margin-bottom: 0.85rem;
            line-height: 1.8;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        }

        /* Slide 4: Scripture & Quote */
        .scripture-verse {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.15rem;
            line-height: 1.7;
            color: #f3edd8;
            font-style: italic;
            margin-bottom: 1.4rem;
            max-width: 320px;
        }

        .scripture-ref {
            font-family: 'Cinzel', serif;
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            color: var(--text-gold);
            font-weight: 600;
            display: block;
            margin-top: 0.35rem;
            font-style: normal;
        }

        .invitation-request-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.05rem;
            color: rgba(243, 237, 216, 0.9);
            line-height: 1.6;
            max-width: 300px;
        }

        /* Slide 5: Couple Cursive Signature */
        .couple-signature-name {
            font-family: 'Alex Brush', cursive;
            font-size: 2.8rem;
            color: #ffffff;
            line-height: 1.15;
            text-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
        }

        .couple-signature-amp {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-style: italic;
            color: var(--text-gold);
            margin: 0.35rem 0;
        }

        /* Slide 6: Events (Holy Matrimony & Reception) */
        .story-event-block {
            margin-bottom: 1.4rem;
        }
        .story-event-block:last-child {
            margin-bottom: 0;
        }

        .story-event-title {
            font-family: 'Alex Brush', cursive;
            font-size: 2.1rem;
            color: #ffffff;
            line-height: 1;
            margin-bottom: 0.3rem;
            text-shadow: 0 0 12px rgba(212, 175, 55, 0.3);
        }

        .story-event-time {
            font-family: 'Cinzel', serif;
            font-size: 0.76rem;
            font-weight: 700;
            color: var(--text-gold);
            letter-spacing: 0.12em;
            margin-bottom: 0.35rem;
            text-transform: uppercase;
        }

        .story-event-venue {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            color: #f3edd8;
            margin-bottom: 2px;
        }

        .story-event-address {
            font-size: 0.72rem;
            color: var(--text-dim);
            line-height: 1.4;
            max-width: 280px;
            margin: 0 auto;
        }

        /* Slide 9: Thank You */
        .thank-you-calligraphy {
            font-family: 'Alex Brush', cursive;
            font-size: 4rem;
            color: #ffffff;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 2px 14px rgba(212, 175, 55, 0.4));
        }

        .btn-scroll-details {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0.72rem 1.6rem;
            background: rgba(14, 18, 25, 0.85);
            backdrop-filter: blur(8px);
            border: 1.5px solid var(--gold-border);
            border-radius: 999px;
            color: var(--text-gold);
            font-family: 'Cinzel', serif;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 18px rgba(0,0,0,0.4);
        }

        .btn-scroll-details:hover {
            border-color: var(--gold);
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* ── 3. SCROLLABLE DETAILS CONTAINER ─────────────────────────────── */
        .details-container {
            padding: 1.5rem 1.4rem 4rem;
            position: relative;
            z-index: 10;
        }

        .section-header-gold {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .section-badge-gold {
            display: inline-block;
            font-family: 'Cinzel', serif;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--text-gold);
            background: rgba(212, 175, 55, 0.12);
            border: 1px solid rgba(212, 175, 55, 0.3);
            padding: 3px 12px;
            border-radius: 999px;
            margin-bottom: 0.4rem;
        }

        .section-title-gold {
            font-family: 'Cinzel', serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.08em;
        }

        /* Luxury Glass Card */
        .glass-card {
            background: var(--surface-glass-card);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--gold-border);
            border-radius: 22px;
            padding: 1.5rem;
            margin-bottom: 1.4rem;
            box-shadow: 0 10px 32px rgba(0, 0, 0, 0.45);
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gold-gradient);
            opacity: 0.6;
        }

        /* Countdown Boxes */
        .countdown-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-top: 1rem;
        }

        .cd-box {
            background: rgba(5, 7, 11, 0.6);
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-radius: 14px;
            padding: 0.75rem 0.3rem;
            text-align: center;
        }

        .cd-box-num {
            font-family: 'Cinzel', serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: #ffffff;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
        }

        .cd-box-lbl {
            font-size: 0.62rem;
            font-weight: 600;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 2px;
        }

        /* QR Guest VIP Ticket */
        .qr-ticket-card {
            background: linear-gradient(145deg, rgba(14, 18, 25, 0.95) 0%, rgba(8, 10, 15, 0.95) 100%);
            border: 1.5px solid var(--gold);
            border-radius: 20px;
            padding: 1.5rem 1.25rem;
            text-align: center;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.6), 0 0 20px rgba(212, 175, 55, 0.15);
            margin-bottom: 1.4rem;
            position: relative;
        }

        .qr-ticket-badge {
            background: var(--gold-gradient);
            color: #000;
            font-family: 'Cinzel', serif;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 3px 14px;
            border-radius: 999px;
            display: inline-block;
            margin-bottom: 0.75rem;
        }

        .qr-code-img-wrap {
            background: #ffffff;
            border-radius: 14px;
            padding: 10px;
            display: inline-block;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.35);
            margin: 0.75rem 0;
        }

        /* Event Location Detail Card */
        .event-detail-item {
            padding: 1.1rem;
            background: rgba(5, 7, 11, 0.55);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 16px;
            margin-bottom: 1rem;
        }

        .event-detail-item:last-child {
            margin-bottom: 0;
        }

        .event-badge-gold {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: 'Cinzel', serif;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            color: var(--text-gold);
            margin-bottom: 0.4rem;
        }

        .btn-map-gold {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0.55rem 1.1rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid var(--gold-border);
            color: var(--text-cream);
            background: rgba(212, 175, 55, 0.08);
        }

        .btn-map-gold:hover {
            border-color: var(--gold);
            background: var(--gold-gradient);
            color: #000;
        }

        /* Form Controls */
        .form-input-dark {
            width: 100%;
            height: 42px;
            background: rgba(5, 7, 11, 0.65);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 12px;
            padding: 0 14px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.82rem;
            color: #ffffff;
            outline: none;
            transition: border-color 0.2s;
            margin-bottom: 0.75rem;
        }

        .form-input-dark:focus {
            border-color: var(--gold);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
        }

        .form-textarea-dark {
            width: 100%;
            min-height: 85px;
            background: rgba(5, 7, 11, 0.65);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 12px;
            padding: 10px 14px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.82rem;
            color: #ffffff;
            outline: none;
            transition: border-color 0.2s;
            resize: vertical;
            margin-bottom: 0.75rem;
        }

        .form-textarea-dark:focus {
            border-color: var(--gold);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
        }

        .btn-submit-gold {
            width: 100%;
            padding: 0.8rem;
            background: var(--gold-gradient);
            color: #07090d;
            font-family: 'Cinzel', serif;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(212, 175, 55, 0.35);
            transition: all 0.2s;
        }

        .btn-submit-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(212, 175, 55, 0.5);
        }

        /* Wish Bubble */
        .wish-item-card {
            background: rgba(5, 7, 11, 0.5);
            border: 1px solid rgba(212, 175, 55, 0.18);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            margin-bottom: 0.65rem;
        }

        .wish-item-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.35rem;
        }

        .wish-sender-name {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-gold);
        }

        .wish-attendance-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .att-hadir { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3); }
        .att-tidak { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.3); }
        .att-ragu { background: rgba(234, 179, 8, 0.15); color: #facc15; border: 1px solid rgba(250, 204, 21, 0.3); }

        /* Floating Audio Toggle Button */
        .floating-audio-btn {
            position: fixed;
            bottom: 22px;
            right: 22px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--gold-gradient);
            color: #07090d;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.5), 0 0 15px rgba(212, 175, 55, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 9998;
            transition: all 0.25s ease;
        }

        .floating-audio-btn.playing {
            animation: spinVinyl 6s linear infinite;
        }

        @keyframes spinVinyl {
            100% { transform: rotate(360deg); }
        }

        /* Floating Re-open Story Button */
        .floating-story-btn {
            position: fixed;
            bottom: 22px;
            left: 22px;
            padding: 0.55rem 1rem;
            border-radius: 999px;
            background: rgba(14, 18, 25, 0.9);
            backdrop-filter: blur(8px);
            border: 1px solid var(--gold-border);
            color: var(--text-gold);
            font-family: 'Cinzel', serif;
            font-size: 0.72rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            z-index: 9998;
            box-shadow: 0 4px 18px rgba(0,0,0,0.5);
            transition: all 0.2s;
        }

        .floating-story-btn:hover {
            border-color: var(--gold);
            color: #ffffff;
        }

        /* Keyframe Animations */
        @keyframes fadeInZoom {
            0% { opacity: 0; transform: scale(0.92); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(18px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="wedding-viewport-wrap">
        <div class="wedding-app-container">

            {{-- Background Particles Effect --}}
            <div class="particles-bg"></div>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- 1. COVER OVERLAY (BUKA UNDANGAN)                              --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <div class="cover-overlay" id="coverOverlay">
                {{-- Top Monogram: Arched Text & Floral Center --}}
                <div>
                    <div style="font-family:'Cinzel',serif; font-size:0.75rem; letter-spacing:0.3em; color:var(--text-gold); text-transform:uppercase; margin-bottom:1.5rem; text-shadow:0 2px 8px rgba(0,0,0,0.8);">
                        The Wedding Celebration of
                    </div>

                    <div class="curved-names-wrapper">
                        <svg viewBox="0 0 300 300" class="curved-svg">
                            <!-- Arc Paths for text curved on top & bottom -->
                            <path id="curveTop" d="M 40,150 A 110,110 0 0,1 260,150" fill="none" />
                            <path id="curveBottom" d="M 260,150 A 110,110 0 0,1 40,150" fill="none" />

                            <!-- Groom Name (Arched on top) -->
                            <text class="curved-text">
                                <textPath href="#curveTop" startOffset="50%" text-anchor="middle">
                                    {{ strtoupper($wedding->groom_nickname ?: 'GROOM') }}
                                </textPath>
                            </text>

                            <!-- Bride Name (Arched on bottom) -->
                            <text class="curved-text">
                                <textPath href="#curveBottom" startOffset="50%" text-anchor="middle">
                                    {{ strtoupper($wedding->bride_nickname ?: 'BRIDE') }}
                                </textPath>
                            </text>
                        </svg>

                        <!-- Center Floral Line Art & Hearts (Exact matching the video) -->
                        <div class="botanical-center">
                            <span style="font-size:9px; color:#fce08b; opacity:0.8;">&hearts;</span>
                            <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 009-9c0-4.97-4.03-9-9-9s-9 4.03-9 9a9 9 0 009 9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c1.5 3 4 5 7 6-3 1-5 3.5-7 7-2-3.5-4-6-7-7 3-1 5.5-3 7-6z"/>
                            </svg>
                            <span style="font-size:9px; color:#fce08b; opacity:0.8;">&hearts;</span>
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
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <span>Buka Undangan</span>
                    </button>
                    <div style="font-size:0.68rem; color:var(--text-dim); margin-top:0.75rem; letter-spacing:0.05em;">
                        Ketuk untuk melihat rangkaian acara &amp; musik
                    </div>
                </div>
            </div>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- 2. INTERACTIVE STORY / SLIDES SECTION (MIRRORING VIDEO)       --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <section class="story-slider-section" id="storySliderSection">
                <!-- Top Progress Bars -->
                <div class="story-progress-bar">
                    @for($i = 0; $i < 9; $i++)
                        <div class="story-bar-track {{ $i === 0 ? 'active' : '' }}" id="storyBar{{ $i }}">
                            <div class="story-bar-fill" id="storyFill{{ $i }}"></div>
                        </div>
                    @endfor
                </div>

                <!-- Touch Control Zones -->
                <div class="story-touch-left" onclick="prevStorySlide()" title="Slide Sebelumnya"></div>
                <div class="story-touch-right" onclick="nextStorySlide()" title="Slide Selanjutnya"></div>

                {{-- SLIDE 1: Curving Names & Floral Icon --}}
                <div class="story-slide active" id="slide0">
                    <div class="curved-names-wrapper">
                        <svg viewBox="0 0 300 300" class="curved-svg">
                            <path id="curveTopSlide" d="M 40,150 A 110,110 0 0,1 260,150" fill="none" />
                            <path id="curveBottomSlide" d="M 260,150 A 110,110 0 0,1 40,150" fill="none" />
                            <text class="curved-text">
                                <textPath href="#curveTopSlide" startOffset="50%" text-anchor="middle">
                                    {{ strtoupper($wedding->groom_nickname ?: 'ANDREW') }}
                                </textPath>
                            </text>
                            <text class="curved-text">
                                <textPath href="#curveBottomSlide" startOffset="50%" text-anchor="middle">
                                    {{ strtoupper($wedding->bride_nickname ?: 'LUCIA') }}
                                </textPath>
                            </text>
                        </svg>
                        <div class="botanical-center">
                            <span style="font-size:9px; color:#fce08b;">&hearts;</span>
                            <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 009-9c0-4.97-4.03-9-9-9s-9 4.03-9 9a9 9 0 009 9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c1.5 3 4 5 7 6-3 1-5 3.5-7 7-2-3.5-4-6-7-7 3-1 5.5-3 7-6z"/>
                            </svg>
                            <span style="font-size:9px; color:#fce08b;">&hearts;</span>
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

                {{-- SLIDE 4: Scripture & Quote --}}
                <div class="story-slide" id="slide3">
                    <div class="scripture-verse">
                        &ldquo;So, they are no longer two, but one.<br>
                        Therefore what God has joined together,<br>
                        let man not separate.&rdquo;
                        <span class="scripture-ref">&bull; Matthew 19:6 &bull;</span>
                    </div>
                    <div class="invitation-request-text">
                        Request the honour of your presence at the marriage of our beloved children
                    </div>
                </div>

                {{-- SLIDE 5: The Couple (Cursive Calligraphy) --}}
                <div class="story-slide" id="slide4">
                    <div class="couple-signature-name">{{ $wedding->groom_name }}</div>
                    @if($wedding->groom_parents)
                        <div style="font-size:0.75rem; color:var(--text-dim); margin-top:2px;">{{ $wedding->groom_parents }}</div>
                    @endif

                    <div class="couple-signature-amp">&amp;</div>

                    <div class="couple-signature-name">{{ $wedding->bride_name }}</div>
                    @if($wedding->bride_parents)
                        <div style="font-size:0.75rem; color:var(--text-dim); margin-top:2px;">{{ $wedding->bride_parents }}</div>
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

                {{-- SLIDE 7: Delighted Blessings --}}
                <div class="story-slide" id="slide6">
                    <div style="font-family:'Alex Brush', cursive; font-size:2.2rem; color:#ffffff; line-height:1.4; max-width:320px; text-shadow:0 0 15px rgba(212,175,55,0.3);">
                        We would be delighted to have your presence and kind blessing in this celebration
                    </div>
                </div>

                {{-- SLIDE 8: Kindly Deliver To You --}}
                <div class="story-slide" id="slide7">
                    <div style="font-family:'Alex Brush', cursive; font-size:2.4rem; color:#ffffff; margin-bottom:0.75rem;">
                        Kindly deliver to You
                    </div>
                    <div style="font-family:'Cinzel', serif; font-size:1.15rem; color:var(--text-gold); font-weight:700; letter-spacing:0.12em;">
                        {{ $guestName }}
                    </div>
                </div>

                {{-- SLIDE 9: Thank You & CTA --}}
                <div class="story-slide" id="slide8">
                    <div class="thank-you-calligraphy">Thank you</div>
                    <a href="#eventDetailsSection" class="btn-scroll-details" onclick="pauseStory()">
                        <span>Lihat Rincian &amp; RSVP</span>
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M7 13l5 5 5-5M12 4v14"/></svg>
                    </a>
                </div>
            </section>

            {{-- ═════════════════════════════════════════════════════════════ --}}
            {{-- 3. SCROLLABLE DETAILS SECTION (LOKASI, QR, AMPLOP, RSVP)     --}}
            {{-- ═════════════════════════════════════════════════════════════ --}}
            <div class="details-container" id="eventDetailsSection">

                {{-- ── COUNTDOWN TIMER ── --}}
                @if($wedding->event_date)
                    <div class="glass-card" style="text-align:center;">
                        <span class="section-badge-gold">Menuju Hari Bahagia</span>
                        <div style="font-family:'Cinzel',serif; font-size:1.05rem; font-weight:700; color:#fff; margin-top:2px;">
                            {{ $wedding->event_date->isoFormat('dddd, D MMMM Y') }}
                        </div>
                        <div class="countdown-grid" id="countdownTimer" data-target="{{ $wedding->event_date->toIso8601String() }}">
                            <div class="cd-box">
                                <div class="cd-box-num" id="cdDays">0</div>
                                <div class="cd-box-lbl">Hari</div>
                            </div>
                            <div class="cd-box">
                                <div class="cd-box-num" id="cdHours">0</div>
                                <div class="cd-box-lbl">Jam</div>
                            </div>
                            <div class="cd-box">
                                <div class="cd-box-num" id="cdMinutes">0</div>
                                <div class="cd-box-lbl">Menit</div>
                            </div>
                            <div class="cd-box">
                                <div class="cd-box-num" id="cdSeconds">0</div>
                                <div class="cd-box-lbl">Detik</div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── SMART GUEST BOOK QR CARD (KHUSUS TAMU TERDAFTAR) ── --}}
                @if($guest)
                    <div class="qr-ticket-card">
                        <span class="qr-ticket-badge">VIP GUEST ENTRY &bull; QR CHECK-IN</span>
                        <div style="font-family:'Cinzel',serif; font-weight:800; font-size:1.15rem; color:#ffffff; margin-bottom:2px;">
                            {{ $guest->name }}
                        </div>
                        <div style="font-size:0.75rem; color:var(--text-gold); margin-bottom:0.75rem;">
                            Tunjukkan kode QR ini ke meja panitia saat tiba di venue acara.
                        </div>

                        <div class="qr-code-img-wrap">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($guest->qr_code) }}"
                                 alt="QR Check-in" style="display:block; width:135px; height:135px;">
                        </div>

                        <div style="font-family:monospace; font-weight:800; font-size:1rem; color:#ffffff; letter-spacing:0.1em;">
                            {{ $guest->qr_code }}
                        </div>
                        <div style="font-size:0.72rem; color:var(--text-dim); margin-top:3px;">
                            Kuota: {{ $guest->pax ?: 1 }} Orang ({{ $guest->group_name ?: 'Tamu' }})
                        </div>
                    </div>
                @endif

                {{-- ── RANGKAIAN ACARA & PETA ── --}}
                <div class="glass-card">
                    <div class="section-header-gold">
                        <span class="section-badge-gold">Agenda Pernikahan</span>
                        <h3 class="section-title-gold">Waktu &amp; Lokasi</h3>
                    </div>

                    @if($wedding->ceremony_date)
                        <div class="event-detail-item">
                            <div class="event-badge-gold">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span>Akad Nikah / Pemberkatan</span>
                            </div>
                            <div style="font-family:'Cinzel',serif; font-size:0.85rem; font-weight:700; color:#fff; margin-bottom:2px;">
                                {{ $wedding->ceremony_date->isoFormat('dddd, D MMMM Y &bull; HH:mm') }} WIB
                            </div>
                            <div style="font-size:0.82rem; font-weight:700; color:var(--text-gold);">
                                {{ $wedding->ceremony_location_name }}
                            </div>
                            <div style="font-size:0.74rem; color:var(--text-dim); margin-top:2px;">
                                {{ $wedding->ceremony_address }}
                            </div>
                        </div>
                    @endif

                    <div class="event-detail-item">
                        <div class="event-badge-gold">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span>Resepsi Pernikahan</span>
                        </div>
                        <div style="font-family:'Cinzel',serif; font-size:0.85rem; font-weight:700; color:#fff; margin-bottom:2px;">
                            {{ $wedding->event_date ? $wedding->event_date->isoFormat('dddd, D MMMM Y &bull; HH:mm') . ' WIB' : '-' }}
                        </div>
                        <div style="font-size:0.82rem; font-weight:700; color:var(--text-gold);">
                            {{ $wedding->event_location_name }}
                        </div>
                        <div style="font-size:0.74rem; color:var(--text-dim); margin-top:2px;">
                            {{ $wedding->event_address }}
                        </div>

                        {{-- Navigasi Maps & Waze --}}
                        <div style="display:flex; gap:8px; margin-top:0.85rem; flex-wrap:wrap;">
                            @if($wedding->event_map_url)
                                <a href="{{ $wedding->event_map_url }}" target="_blank" class="btn-map-gold" style="flex:1;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span>Google Maps</span>
                                </a>
                            @endif
                            <a href="https://waze.com/ul?q={{ urlencode($wedding->event_location_name ?: $wedding->event_address) }}" target="_blank" class="btn-map-gold" style="flex:1;">
                                <span>Waze Navigation</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ── AMPLOP DIGITAL / TANDA KASIH ── --}}
                @if($wedding->digital_envelope_enabled)
                    <div class="glass-card" style="text-align:center;">
                        <div class="section-header-gold">
                            <span class="section-badge-gold">Tanda Kasih</span>
                            <h3 class="section-title-gold">Amplop Digital</h3>
                        </div>
                        <p style="font-size:0.78rem; color:var(--text-dim); margin-bottom:1.25rem; line-height:1.5;">
                            Doa restu Anda merupakan karunia terindah bagi kami. Namun apabila berkenan memberikan tanda kasih, Anda dapat mengirimkannya melalui:
                        </p>

                        @if($wedding->bank_name && $wedding->bank_account_number)
                            <div style="background:rgba(5, 7, 11, 0.65); border:1px solid rgba(212, 175, 55, 0.3); border-radius:16px; padding:1.2rem; margin-bottom:1rem;">
                                <div style="font-family:'Cinzel',serif; font-size:0.9rem; font-weight:800; color:var(--text-gold);">
                                    {{ $wedding->bank_name }}
                                </div>
                                <div style="font-family:monospace; font-size:1.25rem; font-weight:800; color:#ffffff; margin:0.4rem 0;" id="bankAccDisplay">
                                    {{ $wedding->bank_account_number }}
                                </div>
                                <div style="font-size:0.75rem; color:var(--text-dim); margin-bottom:0.75rem;">
                                    a.n. {{ $wedding->bank_account_name }}
                                </div>
                                <button type="button" onclick="copyAccount('{{ $wedding->bank_account_number }}')" class="btn-map-gold" style="padding:0.45rem 1.2rem;">
                                    Salin Nomor Rekening
                                </button>
                            </div>
                        @endif

                        @if($wedding->qris_image)
                            <div style="background:rgba(5, 7, 11, 0.65); border:1px solid rgba(212, 175, 55, 0.3); border-radius:16px; padding:1.2rem;">
                                <div style="font-family:'Cinzel',serif; font-size:0.85rem; font-weight:700; color:#fff; margin-bottom:0.6rem;">
                                    Scan QRIS Pembayaran
                                </div>
                                <img src="{{ asset('storage/' . $wedding->qris_image) }}" alt="QRIS" style="max-width:190px; border-radius:12px; margin-bottom:0.5rem; background:#fff; padding:6px;">
                                <div style="font-size:0.7rem; color:var(--text-dim);">
                                    Mendukung BCA, Mandiri, BRI, GoPay, OVO, ShopeePay, DANA
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- ── FORM DOA RESTU & RSVP ── --}}
                <div class="glass-card">
                    <div class="section-header-gold">
                        <span class="section-badge-gold">Kirim Ucapan</span>
                        <h3 class="section-title-gold">Doa Restu &amp; RSVP</h3>
                    </div>

                    @if(session('wish_success'))
                        <div style="background:rgba(34, 197, 94, 0.15); border:1px solid rgba(74, 222, 128, 0.4); color:#4ade80; padding:0.75rem; border-radius:12px; font-size:0.8rem; font-weight:600; margin-bottom:1rem; text-align:center;">
                            {{ session('wish_success') }}
                        </div>
                    @endif

                    @php
                        $isPreFilled = ($guest !== null || ($guestName !== 'Tamu Undangan' && !empty($guestName)));
                    @endphp

                    <form action="{{ route('wedding.wish', $wedding->slug) }}" method="POST">
                        @csrf

                        @if($isPreFilled)
                            <div style="background:rgba(212, 175, 55, 0.1); border:1px solid rgba(212, 175, 55, 0.35); border-radius:14px; padding:0.8rem 1rem; margin-bottom:0.85rem; display:flex; align-items:center; justify-content:space-between;">
                                <div style="display:flex; align-items:center; gap:0.65rem;">
                                    <div style="width:34px; height:34px; border-radius:50%; background:var(--gold-gradient); color:#000; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.9rem;">
                                        {{ strtoupper(mb_substr($guestName, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-size:0.65rem; color:var(--text-gold); font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">Tamu Terhormat</div>
                                        <div style="font-size:0.9rem; font-weight:800; color:#ffffff;">{{ $guestName }}</div>
                                    </div>
                                </div>
                                <span style="background:rgba(34, 197, 94, 0.2); color:#4ade80; border:1px solid rgba(74, 222, 128, 0.4); font-size:0.65rem; font-weight:700; padding:2px 8px; border-radius:999px;">
                                    Terdaftar
                                </span>
                            </div>
                            <input type="hidden" name="sender_name" value="{{ $guestName }}">
                            @if($guest)
                                <input type="hidden" name="guest_id" value="{{ $guest->id }}">
                            @endif
                        @else
                            <input type="text" name="sender_name" placeholder="Nama Lengkap Anda*" class="form-input-dark" required>
                        @endif

                        {{-- Domisili Kota --}}
                        <input type="text" name="sender_city" id="senderCity" list="cityDatalist" placeholder="Kota Asal / Domisili (misal: Sidoarjo)*" class="form-input-dark" required autocomplete="off">
                        <datalist id="cityDatalist">
                            @if(isset($citySuggestions))
                                @foreach($citySuggestions as $citySug)
                                    <option value="{{ $citySug }}"></option>
                                @endforeach
                            @else
                                <option value="Sidoarjo (Jawa Timur)"></option>
                                <option value="Surabaya (Jawa Timur)"></option>
                                <option value="Jakarta (DKI Jakarta)"></option>
                                <option value="Bandung (Jawa Barat)"></option>
                                <option value="Malang (Jawa Timur)"></option>
                            @endif
                        </datalist>

                        {{-- Status Kehadiran --}}
                        <select name="attendance_status" class="form-input-dark" required style="cursor:pointer;">
                            <option value="hadir">Saya akan Hadir</option>
                            <option value="tidak_hadir">Maaf, Tidak Bisa Hadir</option>
                            <option value="ragu">Masih Ragu</option>
                        </select>

                        {{-- Pesan Doa --}}
                        <textarea name="message" placeholder="Tuliskan ucapan dan doa restu untuk kedua mempelai..." class="form-textarea-dark" required></textarea>

                        <button type="submit" class="btn-submit-gold">
                            Kirim Doa Restu &amp; Konfirmasi
                        </button>
                    </form>

                    {{-- Wish Stream --}}
                    <div style="margin-top:2rem;">
                        <div style="font-family:'Cinzel',serif; font-size:0.85rem; font-weight:700; color:#ffffff; margin-bottom:0.75rem;">
                            Ucapan Doa Tamu ({{ $wishes->count() }})
                        </div>
                        <div style="max-height:360px; overflow-y:auto; padding-right:4px;">
                            @forelse($wishes as $w)
                                <div class="wish-item-card">
                                    <div class="wish-item-header">
                                        <div>
                                            <span class="wish-sender-name">{{ $w->sender_name }}</span>
                                            @if($w->sender_city)
                                                <span style="font-size:0.68rem; color:var(--text-dim); margin-left:4px;">
                                                    &bull; {{ $w->sender_city }}
                                                </span>
                                            @endif
                                        </div>
                                        @php
                                            $att = $w->attendance_status;
                                            $attClass = $att === 'hadir' ? 'att-hadir' : ($att === 'tidak_hadir' ? 'att-tidak' : 'att-ragu');
                                            $attLabel = $att === 'hadir' ? 'Hadir' : ($att === 'tidak_hadir' ? 'Tidak Hadir' : 'Ragu');
                                        @endphp
                                        <span class="wish-attendance-badge {{ $attClass }}">{{ $attLabel }}</span>
                                    </div>
                                    <p style="font-size:0.8rem; color:#e2e8f0; line-height:1.45;">&ldquo;{{ $w->message }}&rdquo;</p>
                                </div>
                            @empty
                                <div style="text-align:center; padding:1.5rem; color:var(--text-dim); font-size:0.8rem;">
                                    Jadilah yang pertama mengirimkan doa restu!
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <footer style="text-align:center; padding:1.5rem 0 0.5rem; font-size:0.72rem; color:var(--text-dim);">
                    <div style="font-family:'Cinzel',serif; font-size:0.8rem; font-weight:700; color:var(--text-gold); margin-bottom:2px;">
                        {{ $wedding->groom_nickname }} &amp; {{ $wedding->bride_nickname }}
                    </div>
                    Undangan Pernikahan Digital &bull; <strong>buyle.id</strong>
                </footer>

            </div>

        </div>
    </div>

    {{-- Floating Audio Toggle Button --}}
    @if($wedding->music_url)
        <audio id="weddingBgMusic" loop src="{{ $wedding->music_url }}"></audio>
        <button type="button" class="floating-audio-btn" id="audioToggleBtn" onclick="toggleMusic()" title="Putar / Jeda Musik">
            <svg id="musicIconPlay" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
            </svg>
        </button>
    @endif

    {{-- Floating Button to Re-Open Story --}}
    <button type="button" class="floating-story-btn" onclick="restartStory()" title="Lihat Animasi Undangan">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <polygon points="5 3 19 12 5 21 5 3"></polygon>
        </svg>
        <span>Putar Story</span>
    </button>

    <script>
        // ══════════════════════════════════════════════════════════
        // 1. OPEN INVITATION & MUSIC
        // ══════════════════════════════════════════════════════════
        function openInvitation() {
            document.getElementById('coverOverlay').classList.add('opened');
            startStoryAutoPlay();

            // Putar musik latar jika ada
            const music = document.getElementById('weddingBgMusic');
            const btn = document.getElementById('audioToggleBtn');
            if (music) {
                music.play().then(() => {
                    if (btn) btn.classList.add('playing');
                }).catch(e => console.log('Autoplay audio blocked:', e));
            }
        }

        function toggleMusic() {
            const music = document.getElementById('weddingBgMusic');
            const btn = document.getElementById('audioToggleBtn');
            if (!music) return;

            if (music.paused) {
                music.play();
                if (btn) btn.classList.add('playing');
            } else {
                music.pause();
                if (btn) btn.classList.remove('playing');
            }
        }

        // Copy Account Helper
        function copyAccount(accNum) {
            navigator.clipboard.writeText(accNum).then(() => {
                alert('Nomor rekening ' + accNum + ' berhasil disalin!');
            });
        }

        // ══════════════════════════════════════════════════════════
        // 2. STORY SLIDES CONTROLLER (MIRRORING VIDEO)
        // ══════════════════════════════════════════════════════════
        const totalSlides = 9;
        let currentSlide = 0;
        let storyInterval = null;
        let slideDuration = 4200; // ms per slide (mirip tempo video)
        let slideStartTime = 0;
        let slideElapsed = 0;
        let isPaused = false;

        function showSlide(index) {
            if (index < 0) index = 0;
            if (index >= totalSlides) index = totalSlides - 1;
            currentSlide = index;

            // Update Slides Visibility
            for (let i = 0; i < totalSlides; i++) {
                const s = document.getElementById('slide' + i);
                const bar = document.getElementById('storyBar' + i);
                const fill = document.getElementById('storyFill' + i);

                if (s) s.classList.toggle('active', i === currentSlide);
                if (bar) {
                    bar.classList.toggle('active', i === currentSlide);
                    bar.classList.toggle('completed', i < currentSlide);
                }
                if (fill) {
                    if (i < currentSlide) fill.style.width = '100%';
                    else if (i > currentSlide) fill.style.width = '0%';
                }
            }

            slideStartTime = Date.now();
            slideElapsed = 0;
        }

        function nextStorySlide() {
            if (currentSlide < totalSlides - 1) {
                showSlide(currentSlide + 1);
            } else {
                // Selesai slide terakhir: scroll halus ke detail
                pauseStory();
                const target = document.getElementById('eventDetailsSection');
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function prevStorySlide() {
            if (currentSlide > 0) {
                showSlide(currentSlide - 1);
            }
        }

        function startStoryAutoPlay() {
            showSlide(0);
            if (storyInterval) clearInterval(storyInterval);

            storyInterval = setInterval(() => {
                if (isPaused) return;

                const now = Date.now();
                slideElapsed += 50;
                const pct = Math.min(100, (slideElapsed / slideDuration) * 100);

                const activeFill = document.getElementById('storyFill' + currentSlide);
                if (activeFill) activeFill.style.width = pct + '%';

                if (slideElapsed >= slideDuration) {
                    nextStorySlide();
                }
            }, 50);
        }

        function pauseStory() {
            isPaused = true;
        }

        function resumeStory() {
            isPaused = false;
        }

        function restartStory() {
            const target = document.getElementById('storySliderSection');
            if (target) target.scrollIntoView({ behavior: 'smooth' });
            isPaused = false;
            startStoryAutoPlay();
        }

        // ══════════════════════════════════════════════════════════
        // 3. COUNTDOWN TIMER
        // ══════════════════════════════════════════════════════════
        const cdContainer = document.getElementById('countdownTimer');
        if (cdContainer) {
            const targetTime = new Date(cdContainer.getAttribute('data-target')).getTime();
            function updateCountdown() {
                const now = Date.now();
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
    </script>
</body>
</html>
