@php
    $customFramePaths = $wedding->data_json['photobooth_frames'] ?? [];
    if (empty($customFramePaths)) {
        $customFramePaths = [
            'wedding/frames/minimalist_arch_wedding.png',
            'wedding/frames/vintage_digicam_wedding.png',
        ];
    }
    $customFrames = [];
    foreach ($customFramePaths as $idx => $path) {
        $isArch = strpos($path, 'minimalist_arch') !== false;
        $isDigicam = strpos($path, 'vintage_digicam') !== false;
        $label = $isArch ? 'Minimalist Arch (3 Foto)' : ($isDigicam ? 'Vintage Digicam' : ('Frame ' . ($idx + 1)));
        $url = $isArch
            ? asset('images/frames/minimalist_arch_wedding.png')
            : ($isDigicam
                ? asset('images/frames/vintage_digicam_wedding.png')
                : \Illuminate\Support\Facades\Storage::disk('public')->url($path));

        $customFrames[] = [
            'id'    => 'custom_' . $idx,
            'label' => $label,
            'url'   => $url,
            'type'  => 'image',
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Photobooth • {{ $wedding->groom_nickname ?? $wedding->groom_name }} &amp; {{ $wedding->bride_nickname ?? $wedding->bride_name }}</title>
    <meta name="description" content="Live Camera Wedding Photobooth. Ambil foto multi-shot dengan frame eksklusif pernikahan dan bagikan ke Instagram Story &amp; WhatsApp!">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,800;1,600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #07090e;
            color: #ffffff;
            overflow: hidden;
            height: 100vh;
            height: 100dvh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photobooth-container {
            position: relative;
            width: 100%;
            height: 100%;
            max-width: 480px;
            background: #000000;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Viewfinder Area (9:16 aspect) */
        .viewfinder-wrapper {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #090d16;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Ambient blurred live background */
        video#cameraVideoBg {
            position: absolute;
            inset: -40px;
            width: calc(100% + 80px);
            height: calc(100% + 80px);
            object-fit: cover;
            filter: blur(36px) brightness(0.38) saturate(1.2);
            pointer-events: none;
            z-index: 1;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        /* Main camera video */
        video#cameraVideo {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        /* Container for 3 sequential arch slots */
        .arch-slots-container {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 5;
            pointer-events: none;
        }

        .arch-slot {
            position: absolute;
            left: 24.074%;
            width: 51.852%;
            background: #0d121c;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.6);
            transition: box-shadow 0.3s ease;
        }

        .arch-slot canvas {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        /* Slot 1: Top Arch (Y: 4.6875%, H: 30.2083%) */
        .arch-slot-1 {
            top: 4.6875%;
            height: 30.2083%;
            border-top-left-radius: 9999px;
            border-top-right-radius: 9999px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        /* Slot 2: Middle Rectangle (Y: 36.9792%, H: 26.0417%) */
        .arch-slot-2 {
            top: 36.9792%;
            height: 26.0417%;
            border-radius: 2px;
        }

        /* Slot 3: Bottom Inverted Arch (Y: 65.1042%, H: 30.2083%) */
        .arch-slot-3 {
            top: 65.1042%;
            height: 30.2083%;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 9999px;
            border-bottom-right-radius: 9999px;
        }

        /* Active Slot Indicator Pulse Glow */
        .arch-slot.active-live {
            box-shadow: 0 0 0 2.5px rgba(236, 72, 153, 0.95), 0 0 24px rgba(236, 72, 153, 0.6);
        }

        /* Slot Badge Indicator */
        .arch-slot-badge {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.72);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #ffffff;
            font-size: 0.64rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 99px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            pointer-events: none;
            z-index: 6;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .arch-slot-1 .arch-slot-badge {
            top: 16px;
        }

        .arch-slot-badge .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #94a3b8;
        }

        .arch-slot-badge.active {
            background: rgba(236, 72, 153, 0.88);
            border-color: #fda4af;
            color: #ffffff;
            box-shadow: 0 2px 10px rgba(236, 72, 153, 0.5);
        }

        .arch-slot-badge.active .badge-dot {
            background: #ffffff;
            animation: pulseBadgeDot 1s ease-in-out infinite alternate;
        }

        .arch-slot-badge.done {
            background: rgba(16, 185, 129, 0.88);
            border-color: #a7f3d0;
            color: #ffffff;
        }

        .arch-slot-badge.done .badge-dot {
            background: #ffffff;
        }

        @keyframes pulseBadgeDot {
            0% { transform: scale(0.8); opacity: 0.7; }
            100% { transform: scale(1.3); opacity: 1; }
        }

        /* Digicam Slot Container */
        .digicam-slot-wrapper {
            position: absolute;
            left: 9.722%;
            top: 5.9375%;
            width: 79.63%;
            height: 69.8958%;
            border-radius: 18px;
            overflow: hidden;
            background: #000000;
            z-index: 5;
            box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.9);
        }

        .digicam-slot-wrapper canvas {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        /* Live Custom Frame Image Overlay: Selalu aktif pada live cam */
        .frame-overlay-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: fill;
            pointer-events: none;
            z-index: 12;
            display: block;
        }

        /* ── Multi-Shot Selector (1, 2, 3, 4 Foto) ─────────────────────────────────── */
        .shots-selector-bar {
            position: absolute;
            top: calc(env(safe-area-inset-top, 16px) + 52px);
            left: 50%;
            transform: translateX(-50%);
            z-index: 28;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            padding: 4px 6px;
            border-radius: 99px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5);
        }

        .shot-btn {
            border: none;
            background: transparent;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 11px;
            border-radius: 99px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .shot-btn.active {
            background: #ffffff;
            color: #06080c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
        }

        /* Live Multi-Shot Grid Overlay Lines */
        .live-grid-guide {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 6;
            display: none;
        }
        .live-grid-guide.grid-2 {
            display: block;
            border-bottom: 2px dashed rgba(255, 255, 255, 0.4);
            height: 50%;
        }
        .live-grid-guide.grid-3 {
            display: block;
            background: repeating-linear-gradient(
                to bottom,
                transparent,
                transparent calc(33.333% - 1px),
                rgba(255, 255, 255, 0.4) calc(33.333% - 1px),
                rgba(255, 255, 255, 0.4) 33.333%
            );
        }
        .live-grid-guide.grid-4 {
            display: block;
            background-image:
                linear-gradient(to right, transparent calc(50% - 1px), rgba(255, 255, 255, 0.4) calc(50% - 1px), rgba(255, 255, 255, 0.4) 50%, transparent 50%),
                linear-gradient(to bottom, transparent calc(50% - 1px), rgba(255, 255, 255, 0.4) calc(50% - 1px), rgba(255, 255, 255, 0.4) 50%, transparent 50%);
        }

        /* Multi-Shot Progress HUD */
        .shot-hud {
            position: absolute;
            top: calc(env(safe-area-inset-top, 16px) + 94px);
            left: 50%;
            transform: translateX(-50%);
            z-index: 29;
            pointer-events: none;
        }
        .shot-hud-pill {
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            color: #ffffff;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 5px 14px;
            border-radius: 99px;
            box-shadow: 0 4px 16px rgba(244, 63, 94, 0.5);
            letter-spacing: 0.5px;
            animation: pulseHud 1.2s ease-in-out infinite alternate;
            white-space: nowrap;
        }
        @keyframes pulseHud {
            0% { transform: scale(0.96); }
            100% { transform: scale(1.04); }
        }

        /* ── Header Controls ──────────────────────────────────────────────────────── */
        .top-bar {
            position: relative;
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: calc(env(safe-area-inset-top, 16px) + 10px) 16px 8px;
            background: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 6px 12px;
            border-radius: 99px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff;
            text-decoration: none;
        }

        .brand-badge svg {
            color: #10b981;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .icon-btn:hover {
            background: rgba(255, 255, 255, 0.28);
        }

        .icon-btn:active {
            transform: scale(0.92);
        }

        .icon-btn.active {
            background: #f59e0b;
            color: #06080c;
            border-color: #fbbf24;
        }

        /* ── Bottom Controls ──────────────────────────────────────────────────────── */
        .bottom-bar {
            position: relative;
            z-index: 30;
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 12px 16px calc(env(safe-area-inset-bottom, 16px) + 12px);
            background: linear-gradient(0deg, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.65) 60%, rgba(0,0,0,0) 100%);
        }

        /* Mode Tabs: Frame vs Filter */
        .mode-tab-bar {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .mode-tab {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.74rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 99px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .mode-tab.active {
            background: #ffffff;
            color: #06080c;
            border-color: #ffffff;
            box-shadow: 0 2px 10px rgba(255, 255, 255, 0.3);
        }

        /* Horizontal Carousel (Custom Frames & Filters) */
        .carousel-container {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 4px 4px 6px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            justify-content: flex-start;
        }

        .carousel-container::-webkit-scrollbar {
            display: none;
        }

        .selector-pill {
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 99px;
            padding: 7px 14px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(8px);
        }

        .selector-pill:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .selector-pill.active {
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            border-color: #fda4af;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(236, 72, 153, 0.45);
        }

        /* Shutter Bar */
        .shutter-row {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 4px 0;
        }

        .shutter-outer {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            border: 4px solid #ffffff;
            padding: 4px;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
        }

        .shutter-outer:hover {
            transform: scale(1.04);
        }

        .shutter-outer:active {
            transform: scale(0.92);
        }

        .shutter-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #ffffff;
            transition: all 0.15s ease;
        }

        .shutter-outer.capturing-sequence {
            border-color: #f43f5e;
            animation: pulseShutter 0.8s ease-in-out infinite alternate;
        }
        .shutter-outer.capturing-sequence .shutter-inner {
            background: #f43f5e;
        }
        @keyframes pulseShutter {
            0% { transform: scale(0.94); }
            100% { transform: scale(1.06); }
        }

        /* ── Overlays ─────────────────────────────────────────────────────────────── */
        .flash-overlay {
            position: absolute;
            inset: 0;
            background: #ffffff;
            opacity: 0;
            pointer-events: none;
            z-index: 50;
            transition: opacity 0.08s ease;
        }

        .flash-overlay.active {
            opacity: 0.95;
        }

        .countdown-overlay {
            position: absolute;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.4);
            z-index: 45;
        }

        .countdown-number {
            font-size: 7.5rem;
            font-weight: 800;
            color: #ffffff;
            text-shadow: 0 4px 24px rgba(0, 0, 0, 0.9);
            animation: pulseCount 0.9s ease-in-out infinite;
        }

        @keyframes pulseCount {
            0% { transform: scale(1.2); opacity: 1; }
            50% { transform: scale(0.95); opacity: 0.8; }
            100% { transform: scale(1.2); opacity: 1; }
        }

        .processing-overlay {
            position: absolute;
            inset: 0;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(7, 9, 14, 0.85);
            backdrop-filter: blur(12px);
            z-index: 60;
            gap: 16px;
        }

        .processing-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(255, 255, 255, 0.15);
            border-top-color: #f43f5e;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Camera Notice / Permission Error */
        .camera-notice-card {
            position: absolute;
            z-index: 25;
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(20px);
            padding: 24px 20px;
            border-radius: 20px;
            text-align: center;
            max-width: 320px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.7);
            display: none;
        }

        .camera-notice-card h3 {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #ffffff;
        }

        .camera-notice-card p {
            font-size: 0.8rem;
            color: #94a3b8;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .camera-notice-card button {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.84rem;
            border: none;
            padding: 10px 20px;
            border-radius: 99px;
            cursor: pointer;
        }

        /* ── Result Modal Preview (9:16) ─────────────────────────────────────────── */
        .result-modal {
            position: fixed;
            inset: 0;
            background: #000000;
            z-index: 100;
            display: none;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
        }

        .result-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: calc(env(safe-area-inset-top, 16px) + 8px) 16px 8px;
            z-index: 10;
        }

        .result-image-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            max-height: 72vh;
        }

        #resultCanvas {
            max-width: 100%;
            max-height: 100%;
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
            object-fit: contain;
        }

        .result-footer {
            padding: 12px 16px calc(env(safe-area-inset-bottom, 16px) + 12px);
            background: linear-gradient(0deg, rgba(7,9,14,0.98) 0%, rgba(7,9,14,0.8) 100%);
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-width: 440px;
            margin: 0 auto;
            width: 100%;
        }

        .btn-action-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ec4899, #f43f5e, #fb923c);
            color: #ffffff;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(236, 72, 153, 0.4);
        }

        .btn-action-wa {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 0.82rem;
            font-weight: 700;
            background: #22c55e;
            color: #ffffff;
            border: none;
            cursor: pointer;
        }

        .btn-action-row {
            display: flex;
            gap: 8px;
        }

        .btn-action-secondary {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
        }

        .toast-notif {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            padding: 8px 18px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            z-index: 200;
            display: none;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="photobooth-container">

        {{-- Toast Notif --}}
        <div id="toastNotif" class="toast-notif"></div>

        {{-- ── 1. Viewfinder Camera Stream ────────────────────────────────────────── --}}
        <div class="viewfinder-wrapper" id="viewfinderWrapper">
            {{-- Background ambient blur --}}
            <video id="cameraVideoBg" autoplay playsinline muted></video>

            {{-- Main live video --}}
            <video id="cameraVideo" autoplay playsinline muted></video>

            {{-- Sequential 3 Arch Slots for Minimalist Arch --}}
            <div id="archSlotsContainer" class="arch-slots-container" style="display:none;">
                <div id="archSlot1" class="arch-slot arch-slot-1 active-live">
                    <canvas id="archCanvas1" width="560" height="580"></canvas>
                    <div id="archBadge1" class="arch-slot-badge active"><span class="badge-dot"></span><span id="archBadgeText1">Pose 1 &bull; Atas</span></div>
                </div>
                <div id="archSlot2" class="arch-slot arch-slot-2">
                    <canvas id="archCanvas2" width="560" height="500"></canvas>
                    <div id="archBadge2" class="arch-slot-badge"><span class="badge-dot"></span><span id="archBadgeText2">Pose 2 &bull; Tengah</span></div>
                </div>
                <div id="archSlot3" class="arch-slot arch-slot-3">
                    <canvas id="archCanvas3" width="560" height="580"></canvas>
                    <div id="archBadge3" class="arch-slot-badge"><span class="badge-dot"></span><span id="archBadgeText3">Pose 3 &bull; Bawah</span></div>
                </div>
            </div>

            {{-- Digicam LCD Screen Slot for Vintage Digicam --}}
            <div id="digicamSlotContainer" class="digicam-slot-wrapper" style="display:none;">
                <canvas id="digicamCanvas" width="860" height="1342"></canvas>
            </div>

            {{-- Live Multi-Shot Grid Helper Lines --}}
            <div id="liveGridGuide" class="live-grid-guide"></div>

            {{-- Custom PNG Frame Overlay (Real-time over live camera) --}}
            <img id="liveCustomFrameImg" class="frame-overlay-img" alt="Custom Frame Overlay">

            {{-- Multi-Shot Progress HUD --}}
            <div id="shotHud" class="shot-hud" style="display:none;">
                <div class="shot-hud-pill">
                    <span id="shotHudText">Pose 1 dari 4</span>
                </div>
            </div>

            {{-- Flash Effect --}}
            <div id="flashOverlay" class="flash-overlay"></div>

            {{-- Countdown Display --}}
            <div id="countdownOverlay" class="countdown-overlay">
                <span id="countdownNumber" class="countdown-number">3</span>
            </div>

            {{-- Processing Loader --}}
            <div id="processingOverlay" class="processing-overlay">
                <div class="processing-spinner"></div>
                <div id="processingText" style="font-size:0.85rem; font-weight:800; color:#fbbf24; text-shadow:0 2px 8px rgba(0,0,0,0.8);">
                    Memproses Foto Estetik...
                </div>
            </div>

            {{-- Camera Permission Request / Fallback Notice --}}
            <div id="cameraNotice" class="camera-notice-card">
                <div style="width:48px; height:48px; border-radius:50%; background:#fee2e2; color:#ef4444; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </div>
                <h3>Izin Kamera Diperlukan</h3>
                <p>Klik tombol di bawah untuk mengizinkan browser mengakses kamera smartphone Anda.</p>
                <button type="button" onclick="startCamera()">Izinkan Kamera</button>
                <div style="margin-top:14px;">
                    <label for="fallbackFileInput" style="font-size:0.75rem; color:#38bdf8; text-decoration:underline; cursor:pointer;">
                        Atau pilih foto dari Galeri HP
                    </label>
                    <input type="file" id="fallbackFileInput" accept="image/*" style="display:none;" onchange="handleFallbackUpload(event)">
                </div>
            </div>
        </div>

        {{-- ── 2. Top Header Bar ─────────────────────────────────────────────────── --}}
        <div class="top-bar">
            <a href="{{ route('wedding.show', $wedding->slug) }}" class="brand-badge">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                <span>Undangan</span>
            </a>

            <div class="header-actions">
                {{-- Timer Selector (Off -> 3s -> 5s) --}}
                <button type="button" id="btnTimerToggle" class="icon-btn" onclick="toggleTimer()" title="Timer Hitung Mundur">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span id="timerIndicator" style="display:none; font-size:0.65rem; font-weight:800; position:absolute; bottom:3px; right:4px;">3s</span>
                </button>

                {{-- Flip Camera (Front / Back) --}}
                <button type="button" id="btnFlipCamera" class="icon-btn" onclick="flipCamera()" title="Putar Kamera">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M20 16v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-4"/>
                        <path d="M4 8V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/>
                        <polyline points="1 13 4 16 7 13"/>
                        <polyline points="23 11 20 8 17 11"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Multi-Shot Mode Bar (Kunci Auto 3 Foto / Mode 1 Foto) --}}
        <div id="topShotBadge" style="position:absolute; top:72px; z-index:15; display:inline-flex; align-items:center; gap:6px; background:rgba(0,0,0,0.55); backdrop-filter:blur(8px); padding:5px 14px; border-radius:99px; border:1px solid rgba(255,255,255,0.18); font-size:0.7rem; font-weight:700; color:#ffffff;">
            <span id="topShotDot" style="width:6px; height:6px; border-radius:50%; background:#22c55e;"></span>
            <span id="topShotLabel">Auto 3 Foto &bull; Strip Estetik</span>
        </div>
        <div class="shots-selector-bar" style="display:none !important;">
            <button type="button" class="shot-btn" data-shots="1" onclick="selectShotMode(3, this)">1 Foto</button>
            <button type="button" class="shot-btn" data-shots="2" onclick="selectShotMode(3, this)">2 Foto</button>
            <button type="button" class="shot-btn active" data-shots="3" onclick="selectShotMode(3, this)">3 Strip</button>
            <button type="button" class="shot-btn" data-shots="4" onclick="selectShotMode(3, this)">4 Grid</button>
        </div>

        {{-- ── 3. Bottom Frame / Filter Selector & Shutter ────────────────────────── --}}
        <div class="bottom-bar">
            {{-- Switcher Tab: Bingkai Custom vs Filter Warna --}}
            <div class="mode-tab-bar">
                <button type="button" class="mode-tab active" id="tabModeFrame" onclick="switchControlMode('frame')">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="3"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <path d="M21 15l-5-5L5 21"></path>
                    </svg>
                    <span>Bingkai Custom</span>
                </button>
                <button type="button" class="mode-tab" id="tabModeFilter" onclick="switchControlMode('filter')">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 3a9 9 0 0 0 0 18v-18z"></path>
                    </svg>
                    <span>Filter Warna</span>
                </button>
            </div>

            {{-- 1. Carousel Frame Custom (Hanya Frame Custom Buatan Pengantin) --}}
            <div id="carouselFrame" class="carousel-container">
                @forelse($customFrames as $idx => $cf)
                    <button type="button" class="selector-pill {{ $idx === 0 ? 'active' : '' }}" onclick="selectCustomFrame('{{ $cf['id'] }}', '{{ $cf['url'] }}', this)">
                        <svg width="13" height="13" fill="none" stroke="#f472b6" stroke-width="2.3" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        <span>{{ $cf['label'] }}</span>
                    </button>
                @empty
                    <div style="font-size:0.75rem; color:#94a3b8; padding:6px 12px; font-weight:600; text-align:center; width:100%;">
                        Belum ada frame custom diupload pengantin.
                    </div>
                @endforelse
            </div>

            {{-- 2. Carousel Filter Warna --}}
            <div id="carouselFilter" class="carousel-container" style="display:none;">
                <button type="button" class="selector-pill filter-pill active" onclick="selectFilter('normal', this)">
                    <span style="width:10px; height:10px; border-radius:50%; background:#ffffff; display:inline-block;"></span>
                    Normal (Asli)
                </button>
                <button type="button" class="selector-pill filter-pill" onclick="selectFilter('warm', this)">
                    <span style="width:10px; height:10px; border-radius:50%; background:#f59e0b; display:inline-block;"></span>
                    Warm Gold
                </button>
                <button type="button" class="selector-pill filter-pill" onclick="selectFilter('soft', this)">
                    <span style="width:10px; height:10px; border-radius:50%; background:#fb7185; display:inline-block;"></span>
                    Soft Glow
                </button>
                <button type="button" class="selector-pill filter-pill" onclick="selectFilter('rose', this)">
                    <span style="width:10px; height:10px; border-radius:50%; background:#ec4899; display:inline-block;"></span>
                    Rose Blush
                </button>
                <button type="button" class="selector-pill filter-pill" onclick="selectFilter('bw', this)">
                    <span style="width:10px; height:10px; border-radius:50%; background:#64748b; display:inline-block;"></span>
                    B&amp;W Classic
                </button>
                <button type="button" class="selector-pill filter-pill" onclick="selectFilter('vintage', this)">
                    <span style="width:10px; height:10px; border-radius:50%; background:#d97706; display:inline-block;"></span>
                    Vintage Film
                </button>
                <button type="button" class="selector-pill filter-pill" onclick="selectFilter('crisp', this)">
                    <span style="width:10px; height:10px; border-radius:50%; background:#38bdf8; display:inline-block;"></span>
                    Crisp Pop
                </button>
            </div>

            {{-- Shutter Bar --}}
            <div class="shutter-row">
                {{-- Upload Galeri Button --}}
                <label for="fallbackFileInput" class="icon-btn" title="Ambil dari Galeri" style="cursor:pointer;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                </label>

                {{-- Shutter Button --}}
                <button type="button" class="shutter-outer" id="mainShutterBtn" onclick="triggerCapture()" title="Jepret Foto">
                    <div class="shutter-inner"></div>
                </button>

                {{-- Help / Info Button --}}
                <button type="button" class="icon-btn" onclick="showHelpModal()" title="Petunjuk">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </button>
            </div>
        </div>

        {{-- ── 4. Snapshot Result Preview Modal ───────────────────────────────────── --}}
        <div id="resultModal" class="result-modal">
            <div class="result-header">
                <button type="button" class="brand-badge" onclick="retakePhoto()" style="background:rgba(0,0,0,0.65); cursor:pointer;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path d="M1 4v6h6"/>
                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                    </svg>
                    <span>Foto Ulang</span>
                </button>
                <div style="font-size:0.75rem; font-weight:800; color:#fbbf24; text-transform:uppercase; letter-spacing:1px;">
                    Hasil Photobooth 9:16
                </div>
            </div>

            <div class="result-image-wrap">
                <canvas id="resultCanvas" width="1080" height="1920"></canvas>
            </div>

            <div class="result-footer">
                {{-- Share ke Instagram Story --}}
                <button type="button" id="btnShareStory" class="btn-action-primary" onclick="shareToInstaStory()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                    <span>Share ke Instagram Story</span>
                </button>

                {{-- Kirim ke WhatsApp --}}
                <button type="button" id="btnShareWa" class="btn-action-wa" onclick="shareToWhatsApp()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                    </svg>
                    <span>Kirim ke WhatsApp</span>
                </button>

                <div class="btn-action-row">
                    {{-- Download Button --}}
                    <button type="button" class="btn-action-secondary" onclick="downloadPhoto()">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Unduh Foto HD</span>
                    </button>

                    {{-- Retake Button --}}
                    <button type="button" class="btn-action-secondary" onclick="retakePhoto()">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path d="M23 4v6h-6"></path>
                            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                        </svg>
                        <span>Ganti Gaya</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- Data Variabel Wedding dari Blade ke JS --}}
    <script>
        const WEDDING_DATA = {
            groom: {!! json_encode($wedding->groom_nickname ?? $wedding->groom_name) !!},
            bride: {!! json_encode($wedding->bride_nickname ?? $wedding->bride_name) !!},
            date: {!! json_encode($wedding->event_date ? $wedding->event_date->translatedFormat('d F Y') : date('d F Y')) !!},
            slug: {!! json_encode($wedding->slug) !!},
            guestName: {!! json_encode($guestName) !!},
            saveUrl: {!! json_encode(route('wedding.photobooth.save', $wedding->slug)) !!},
            csrfToken: {!! json_encode(csrf_token()) !!}
        };

        const CUSTOM_FRAMES = {!! json_encode($customFrames) !!};

        // Filter Definitions
        const FILTER_DEFINITIONS = {
            'normal':  { name: 'Normal (Asli)', css: 'none' },
            'warm':    { name: 'Warm Gold', css: 'sepia(0.22) saturate(1.28) brightness(1.05) contrast(1.03)' },
            'soft':    { name: 'Soft Glow', css: 'brightness(1.08) contrast(0.96) saturate(1.15)' },
            'rose':    { name: 'Rose Blush', css: 'hue-rotate(-10deg) saturate(1.25) brightness(1.04)' },
            'bw':      { name: 'B&W Classic', css: 'grayscale(1) contrast(1.22) brightness(1.05)' },
            'vintage': { name: 'Vintage Film', css: 'sepia(0.42) contrast(1.12) brightness(0.98) saturate(1.12)' },
            'crisp':   { name: 'Crisp Pop', css: 'contrast(1.18) saturate(1.22) brightness(1.02)' }
        };

        // Camera & Control State
        let stream = null;
        let currentFacingMode = 'user'; // 'user' (front) or 'environment' (back)
        let selectedFrame = '';
        let isCustomFrame = false;
        let activeCustomUrl = '';
        let currentFilterKey = 'normal';
        let timerSeconds = 0; // 0, 3, or 5
        let isCapturing = false;
        let uploadedImageObj = null;

        // Multi-Shot & Box Preview State
        let currentShotMode = 3;
        let isArchMode = true;
        let isDigicamMode = false;
        let archStep = 1; // 1 (atas), 2 (tengah), 3 (bawah)
        let archSnapshots = [null, null, null];
        let animFrameId = null;
        let isLoopRunning = false;

        const videoEl = document.getElementById('cameraVideo');
        const videoBgEl = document.getElementById('cameraVideoBg');
        const liveCustomFrameImg = document.getElementById('liveCustomFrameImg');
        const liveGridGuide = document.getElementById('liveGridGuide');
        const shotHud = document.getElementById('shotHud');
        const shotHudText = document.getElementById('shotHudText');
        const flashOverlay = document.getElementById('flashOverlay');
        const countdownOverlay = document.getElementById('countdownOverlay');
        const countdownNumber = document.getElementById('countdownNumber');
        const processingOverlay = document.getElementById('processingOverlay');
        const processingText = document.getElementById('processingText');
        const resultModal = document.getElementById('resultModal');
        const resultCanvas = document.getElementById('resultCanvas');
        const cameraNotice = document.getElementById('cameraNotice');
        const toastNotif = document.getElementById('toastNotif');
        const mainShutterBtn = document.getElementById('mainShutterBtn');

        function showToast(msg, duration = 2500) {
            toastNotif.textContent = msg;
            toastNotif.style.display = 'block';
            setTimeout(() => {
                toastNotif.style.display = 'none';
            }, duration);
        }

        // ── Helper: Draw Video Frame Cropped (Cover-Fit) to Target Canvas ────────
        function drawVideoCoverToCanvas(targetCanvas, filterCss, isFrontCamera) {
            if (!videoEl || videoEl.videoWidth === 0 || videoEl.videoHeight === 0) return;

            const ctx = targetCanvas.getContext('2d');
            const cw = targetCanvas.width;
            const ch = targetCanvas.height;

            ctx.save();
            ctx.clearRect(0, 0, cw, ch);

            if (filterCss && filterCss !== 'none') {
                ctx.filter = filterCss;
            }

            if (isFrontCamera) {
                ctx.translate(cw, 0);
                ctx.scale(-1, 1);
            }

            const vw = videoEl.videoWidth;
            const vh = videoEl.videoHeight;
            const canvasAspect = cw / ch;
            const videoAspect = vw / vh;

            let sx, sy, sw, sh;
            if (videoAspect > canvasAspect) {
                sh = vh;
                sw = vh * canvasAspect;
                sx = (vw - sw) / 2;
                sy = 0;
            } else {
                sw = vw;
                sh = vw / canvasAspect;
                sx = 0;
                sy = (vh - sh) / 2;
            }

            ctx.drawImage(videoEl, sx, sy, sw, sh, 0, 0, cw, ch);
            ctx.restore();
        }

        // Helper: Draw Aesthetic Dark Placeholder on Waiting Slot
        function drawPlaceholder(ctx, w, h, title, subtitle) {
            ctx.save();
            ctx.fillStyle = '#0b0f19';
            ctx.fillRect(0, 0, w, h);

            ctx.strokeStyle = 'rgba(255, 255, 255, 0.12)';
            ctx.lineWidth = 1.5;
            ctx.setLineDash([6, 6]);
            ctx.strokeRect(20, 20, w - 40, h - 40);

            ctx.font = '700 30px "Montserrat", sans-serif';
            ctx.fillStyle = 'rgba(255, 255, 255, 0.4)';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(title, w / 2, h / 2 - 12);

            ctx.font = '500 16px "Montserrat", sans-serif';
            ctx.fillStyle = 'rgba(255, 255, 255, 0.22)';
            ctx.fillText(subtitle, w / 2, h / 2 + 24);
            ctx.restore();
        }

        // Helper: Update Arch Badge Indicators on UI
        function updateArchBadges() {
            for (let i = 1; i <= 3; i++) {
                const slotEl = document.getElementById(`archSlot${i}`);
                const badgeEl = document.getElementById(`archBadge${i}`);
                const badgeText = document.getElementById(`archBadgeText${i}`);
                const posNames = { 1: 'Atas', 2: 'Tengah', 3: 'Bawah' };

                if (!slotEl || !badgeEl) continue;

                slotEl.classList.remove('active-live');
                badgeEl.classList.remove('active', 'done');

                if (archSnapshots[i - 1]) {
                    badgeEl.classList.add('done');
                    if (badgeText) badgeText.innerHTML = `Pose ${i} ✓ OK`;
                } else if (archStep === i) {
                    slotEl.classList.add('active-live');
                    badgeEl.classList.add('active');
                    if (badgeText) badgeText.innerHTML = `Pose ${i} (${posNames[i]}) • Kamera Aktif`;
                } else {
                    if (badgeText) badgeText.innerHTML = `Pose ${i} (${posNames[i]}) • Menunggu`;
                }
            }
        }

        // Helper: Clone / Freeze Canvas to Offscreen Canvas
        function captureSlotCanvas(canvas) {
            const copy = document.createElement('canvas');
            copy.width = canvas.width;
            copy.height = canvas.height;
            const ctx = copy.getContext('2d');
            ctx.drawImage(canvas, 0, 0);
            return copy;
        }

        // ── 60 FPS Live Box Preview Animation Loop ───────────────────────────────
        function startLiveRenderLoop() {
            if (isLoopRunning) return;
            isLoopRunning = true;

            function tick() {
                if (stream && videoEl.readyState >= 2) {
                    const filterDef = FILTER_DEFINITIONS[currentFilterKey];
                    const filterCss = filterDef ? filterDef.css : 'none';
                    const isFront = (currentFacingMode === 'user');

                    if (isArchMode) {
                        // Slot 1 (Atas)
                        const c1 = document.getElementById('archCanvas1');
                        if (c1) {
                            if (archSnapshots[0]) {
                                // Keep frozen snapshot
                            } else if (archStep === 1) {
                                drawVideoCoverToCanvas(c1, filterCss, isFront);
                            } else {
                                drawPlaceholder(c1.getContext('2d'), 560, 580, 'Pose 1 (Atas)', 'Bersiap foto pertama');
                            }
                        }

                        // Slot 2 (Tengah)
                        const c2 = document.getElementById('archCanvas2');
                        if (c2) {
                            if (archSnapshots[1]) {
                                // Keep frozen snapshot
                            } else if (archStep === 2) {
                                drawVideoCoverToCanvas(c2, filterCss, isFront);
                            } else if (archStep < 2) {
                                drawPlaceholder(c2.getContext('2d'), 560, 500, 'Pose 2 (Tengah)', 'Menunggu Pose 1');
                            }
                        }

                        // Slot 3 (Bawah)
                        const c3 = document.getElementById('archCanvas3');
                        if (c3) {
                            if (archSnapshots[2]) {
                                // Keep frozen snapshot
                            } else if (archStep === 3) {
                                drawVideoCoverToCanvas(c3, filterCss, isFront);
                            } else if (archStep < 3) {
                                drawPlaceholder(c3.getContext('2d'), 560, 580, 'Pose 3 (Bawah)', 'Menunggu Pose 2');
                            }
                        }
                    } else if (isDigicamMode) {
                        const cDigi = document.getElementById('digicamCanvas');
                        if (cDigi) {
                            drawVideoCoverToCanvas(cDigi, filterCss, isFront);
                        }
                    }
                }
                animFrameId = requestAnimationFrame(tick);
            }
            animFrameId = requestAnimationFrame(tick);
        }

        // ── Inisialisasi Kamera Native ───────────────────────────────────────────
        async function startCamera() {
            cameraNotice.style.display = 'none';

            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            const constraints = {
                audio: false,
                video: {
                    facingMode: currentFacingMode,
                    width: { ideal: 1920 },
                    height: { ideal: 1080 }
                }
            };

            try {
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                videoEl.srcObject = stream;
                if (videoBgEl) videoBgEl.srcObject = stream;
                uploadedImageObj = null;

                applyTransform();
                applyFilterToVideo();
                startLiveRenderLoop();
            } catch (err) {
                console.warn('Fallback ke constraint sederhana:', err);
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        audio: false,
                        video: { facingMode: currentFacingMode }
                    });
                    videoEl.srcObject = stream;
                    if (videoBgEl) videoBgEl.srcObject = stream;
                    uploadedImageObj = null;
                    applyTransform();
                    applyFilterToVideo();
                    startLiveRenderLoop();
                } catch (fallbackErr) {
                    console.warn('Gagal total membuka kamera:', fallbackErr);
                    cameraNotice.style.display = 'block';
                }
            }
        }

        // Apply scale & flip to video elements
        function applyTransform() {
            const flipX = currentFacingMode === 'user' ? -1 : 1;
            videoEl.style.transform = `scaleX(${flipX})`;
            if (videoBgEl) {
                videoBgEl.style.transform = `scale(1.15) scaleX(${flipX})`;
            }
            videoEl.style.objectFit = 'contain';
            if (videoBgEl) videoBgEl.style.display = 'block';
        }

        // Flip Camera (Front / Back)
        function flipCamera() {
            currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
            startCamera();
        }

        // Toggle Timer (Off -> 3s -> 5s -> Off)
        function toggleTimer() {
            const btn = document.getElementById('btnTimerToggle');
            const ind = document.getElementById('timerIndicator');

            if (timerSeconds === 0) {
                timerSeconds = 3;
                btn.classList.add('active');
                ind.style.display = 'block';
                ind.textContent = '3s';
                showToast('Timer: 3 Detik', 1400);
            } else if (timerSeconds === 3) {
                timerSeconds = 5;
                btn.classList.add('active');
                ind.style.display = 'block';
                ind.textContent = '5s';
                showToast('Timer: 5 Detik', 1400);
            } else {
                timerSeconds = 0;
                btn.classList.remove('active');
                ind.style.display = 'none';
                showToast('Timer dimatikan', 1400);
            }
        }

        // Multi-Shot Mode Selector
        function selectShotMode(mode, btnEl) {
            currentShotMode = parseInt(mode) || 1;
            document.querySelectorAll('.shot-btn').forEach(b => b.classList.remove('active'));
            if (btnEl) btnEl.classList.add('active');

            liveGridGuide.className = 'live-grid-guide';
            if (currentShotMode > 1) {
                liveGridGuide.classList.add('grid-' + currentShotMode);
            }

            const names = { 1: '1 Foto (Tunggal)', 2: '2 Foto (Split)', 3: '3 Foto (Strip)', 4: '4 Foto (Grid Instagram)' };
            showToast(`Mode: ${names[currentShotMode] || mode + ' Foto'}`, 1500);
        }

        // Switch bottom carousel: Frame vs Filter
        function switchControlMode(mode) {
            const tabFrame = document.getElementById('tabModeFrame');
            const tabFilter = document.getElementById('tabModeFilter');
            const carFrame = document.getElementById('carouselFrame');
            const carFilter = document.getElementById('carouselFilter');

            if (mode === 'frame') {
                tabFrame.classList.add('active');
                tabFilter.classList.remove('active');
                carFrame.style.display = 'flex';
                carFilter.style.display = 'none';
            } else {
                tabFilter.classList.add('active');
                tabFrame.classList.remove('active');
                carFilter.style.display = 'flex';
                carFrame.style.display = 'none';
            }
        }

        // Select Filter
        function selectFilter(filterKey, btnEl) {
            currentFilterKey = filterKey;
            document.querySelectorAll('.filter-pill').forEach(b => b.classList.remove('active'));
            if (btnEl) btnEl.classList.add('active');
            applyFilterToVideo();
            showToast(`Filter: ${FILTER_DEFINITIONS[filterKey]?.name || 'Normal'}`, 1400);
        }

        function applyFilterToVideo() {
            const filterDef = FILTER_DEFINITIONS[currentFilterKey];
            const cssFilter = filterDef ? filterDef.css : 'none';
            videoEl.style.filter = cssFilter;
            if (videoBgEl) {
                videoBgEl.style.filter = cssFilter !== 'none'
                    ? `${cssFilter} blur(36px) brightness(0.38)`
                    : 'blur(36px) brightness(0.38)';
            }
        }

        // Select Custom PNG Frame
        function selectCustomFrame(frameId, frameUrl, btnEl) {
            selectedFrame = frameId;
            isCustomFrame = true;
            activeCustomUrl = frameUrl;

            document.querySelectorAll('#carouselFrame .selector-pill').forEach(el => el.classList.remove('active'));
            if (btnEl) btnEl.classList.add('active');

            liveCustomFrameImg.src = frameUrl;
            liveCustomFrameImg.style.display = 'block';

            const isArch = frameUrl.includes('minimalist_arch');
            const isDigicam = frameUrl.includes('vintage_digicam');

            const topDot = document.getElementById('topShotDot');
            const topLabel = document.getElementById('topShotLabel');
            const archContainer = document.getElementById('archSlotsContainer');
            const digiContainer = document.getElementById('digicamSlotContainer');

            if (isArch) {
                isArchMode = true;
                isDigicamMode = false;
                currentShotMode = 3; // KUNCI AUTO 3 FOTO!
                archStep = 1;
                archSnapshots = [null, null, null];

                if (archContainer) archContainer.style.display = 'block';
                if (digiContainer) digiContainer.style.display = 'none';
                videoEl.style.display = 'none';
                if (videoBgEl) videoBgEl.style.display = 'none';

                if (topDot) topDot.style.background = '#22c55e';
                if (topLabel) topLabel.innerHTML = 'Auto 3 Foto &bull; Strip Estetik';

                updateArchBadges();
                showToast('Minimalist Arch: Preview langsung di tiap box (Auto 3 Foto)', 2200);
            } else if (isDigicam) {
                isArchMode = false;
                isDigicamMode = true;
                currentShotMode = 1; // KUNCI 1 FOTO GABISA DISETTING!

                if (archContainer) archContainer.style.display = 'none';
                if (digiContainer) digiContainer.style.display = 'block';
                videoEl.style.display = 'none';
                if (videoBgEl) videoBgEl.style.display = 'none';

                if (topDot) topDot.style.background = '#eab308';
                if (topLabel) topLabel.innerHTML = 'Mode 1 Foto &bull; Vintage Digicam';

                showToast('Vintage Digicam: Kunci 1 Foto estetik', 2000);
            } else {
                isArchMode = false;
                isDigicamMode = false;
                currentShotMode = 1;

                if (archContainer) archContainer.style.display = 'none';
                if (digiContainer) digiContainer.style.display = 'none';
                videoEl.style.display = 'block';
                if (videoBgEl) videoBgEl.style.display = 'block';

                if (topDot) topDot.style.background = '#38bdf8';
                if (topLabel) topLabel.innerHTML = 'Mode 1 Foto &bull; Custom';
            }
        }

        // Fallback: Jika tamu memilih upload foto dari galeri
        function handleFallbackUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    uploadedImageObj = img;
                    cameraNotice.style.display = 'none';
                    renderCollage([img], true);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        // ── Shutter Action & Multi-Shot Sequence Engine ──────────────────────────
        async function triggerCapture() {
            if (isCapturing) return;
            isCapturing = true;
            mainShutterBtn.classList.add('capturing-sequence');

            if (isArchMode) {
                // Minimalist Arch: 3-Pose Sequential Box Capture
                archSnapshots = [null, null, null];
                const posNames = { 1: 'Atas', 2: 'Tengah', 3: 'Bawah' };

                for (let step = 1; step <= 3; step++) {
                    archStep = step;
                    updateArchBadges();

                    shotHudText.textContent = `Pose ${step} (${posNames[step]}): Bersiap!`;
                    shotHud.style.display = 'block';

                    let countdownSec = timerSeconds > 0 ? timerSeconds : 3;
                    await runCountdown(countdownSec);

                    // Shutter flash
                    flashOverlay.classList.add('active');
                    setTimeout(() => flashOverlay.classList.remove('active'), 120);
                    playShutterSound();

                    // Freeze canvas snapshot
                    const activeCanvas = document.getElementById(`archCanvas${step}`);
                    const frozenSnap = captureSlotCanvas(activeCanvas);
                    archSnapshots[step - 1] = frozenSnap;

                    // Draw permanent frozen image onto slot canvas
                    const actCtx = activeCanvas.getContext('2d');
                    actCtx.drawImage(frozenSnap, 0, 0);

                    updateArchBadges();

                    if (step < 3) {
                        shotHudText.textContent = `Pose ${step} tersimpan! Ganti gaya untuk box ${posNames[step + 1]}...`;
                        await delay(1200);
                    }
                }

                shotHud.style.display = 'none';
                mainShutterBtn.classList.remove('capturing-sequence');
                isCapturing = false;

                processingText.textContent = 'Menyatukan 3 Pose Estetik...';
                processingOverlay.style.display = 'flex';

                setTimeout(() => {
                    renderCollage(archSnapshots, false);
                }, 150);

            } else if (isDigicamMode) {
                // Vintage Digicam: Strict 1 Photo
                if (timerSeconds > 0) {
                    shotHudText.textContent = 'Bersiap foto Vintage...';
                    shotHud.style.display = 'block';
                    await runCountdown(timerSeconds);
                }

                flashOverlay.classList.add('active');
                setTimeout(() => flashOverlay.classList.remove('active'), 120);
                playShutterSound();

                const digiCanvas = document.getElementById('digicamCanvas');
                const snap = captureSlotCanvas(digiCanvas);

                shotHud.style.display = 'none';
                mainShutterBtn.classList.remove('capturing-sequence');
                isCapturing = false;

                processingText.textContent = 'Memproses Foto Vintage Digicam...';
                processingOverlay.style.display = 'flex';

                setTimeout(() => {
                    renderCollage([snap], false);
                }, 150);

            } else {
                // Standard multi-shot or single photo fallback
                const totalShots = currentShotMode;
                const capturedList = [];

                for (let i = 1; i <= totalShots; i++) {
                    if (totalShots > 1) {
                        shotHudText.textContent = `Pose ${i} dari ${totalShots}: Bersiap!`;
                        shotHud.style.display = 'block';
                    }

                    let countdownSec = timerSeconds > 0 ? timerSeconds : (totalShots > 1 ? 3 : 0);
                    if (countdownSec > 0) {
                        await runCountdown(countdownSec);
                    }

                    flashOverlay.classList.add('active');
                    setTimeout(() => flashOverlay.classList.remove('active'), 120);
                    playShutterSound();

                    const frameSnapshot = captureVideoFrame();
                    capturedList.push(frameSnapshot);

                    if (i < totalShots) {
                        shotHudText.textContent = `Foto ${i} tersimpan! Ganti gaya berikutnya...`;
                        await delay(1200);
                    }
                }

                shotHud.style.display = 'none';
                mainShutterBtn.classList.remove('capturing-sequence');
                isCapturing = false;

                processingText.textContent = totalShots > 1 ? 'Membuat Kolase Photobooth...' : 'Memproses Foto Estetik...';
                processingOverlay.style.display = 'flex';

                setTimeout(() => {
                    renderCollage(capturedList, false);
                }, 100);
            }
        }

        function runCountdown(seconds) {
            return new Promise(resolve => {
                let count = seconds;
                countdownNumber.textContent = count;
                countdownOverlay.style.display = 'flex';

                const interval = setInterval(() => {
                    count--;
                    if (count > 0) {
                        countdownNumber.textContent = count;
                    } else {
                        clearInterval(interval);
                        countdownOverlay.style.display = 'none';
                        resolve();
                    }
                }, 1000);
            });
        }

        function delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        function captureVideoFrame() {
            const offscreen = document.createElement('canvas');
            offscreen.width = videoEl.videoWidth || 1080;
            offscreen.height = videoEl.videoHeight || 1920;
            const octx = offscreen.getContext('2d');

            const filterDef = FILTER_DEFINITIONS[currentFilterKey];
            if (filterDef && filterDef.css !== 'none') {
                octx.filter = filterDef.css;
            }

            if (currentFacingMode === 'user') {
                octx.translate(offscreen.width, 0);
                octx.scale(-1, 1);
            }

            octx.drawImage(videoEl, 0, 0, offscreen.width, offscreen.height);
            return offscreen;
        }

        function playShutterSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(800, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(300, ctx.currentTime + 0.08);
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.08);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.08);
            } catch (e) {}
        }

        // ── Helper: Draw Image with Rounded Corners & Cover Fit ───────────────────
        function drawCroppedToRect(ctx, src, rx, ry, rw, rh, radius = 20) {
            ctx.save();
            if (radius > 0) {
                ctx.beginPath();
                if (ctx.roundRect) {
                    ctx.roundRect(rx, ry, rw, rh, radius);
                } else {
                    ctx.rect(rx, ry, rw, rh);
                }
                ctx.clip();
            }

            const sw = src.width;
            const sh = src.height;
            const rectAspect = rw / rh;
            const srcAspect = sw / sh;
            let dw, dh, dx, dy;

            if (srcAspect > rectAspect) {
                dh = rh;
                dw = rh * srcAspect;
                dx = rx - (dw - rw) / 2;
                dy = ry;
            } else {
                dw = rw;
                dh = rw / srcAspect;
                dx = rx;
                dy = ry - (dh - rh) / 2;
            }

            ctx.drawImage(src, 0, 0, sw, sh, dx, dy, dw, dh);
            ctx.restore();
        }

        // ── Render Kolase Multi-Shot + Frame Custom ke Canvas 1080x1920 ──────────
        function renderCollage(capturedList, isStaticUpload) {
            const ctx = resultCanvas.getContext('2d');
            ctx.clearRect(0, 0, 1080, 1920);

            // Background canvas: Dark luxury marble
            ctx.fillStyle = '#090d16';
            ctx.fillRect(0, 0, 1080, 1920);

            const shots = capturedList.length;

            if (activeCustomUrl && activeCustomUrl.includes('vintage_digicam')) {
                // Layout Vintage Digicam: Crop foto tepat di dalam layar LCD Digicam (X:105, Y:114, W:860, H:1342)
                const src = capturedList[0];
                drawCroppedToRect(ctx, src, 105, 114, 860, 1342, 24);

            } else if (activeCustomUrl && activeCustomUrl.includes('minimalist_arch')) {
                // Layout 3: Minimalist Arch Shape Windows (Presisi Sesuai Template)
                // 1. Top Arch Window (Dome atas r=280 pada y=370, dasar datar y=670)
                ctx.save();
                ctx.beginPath();
                ctx.arc(540, 370, 280, Math.PI, 0, false);
                ctx.lineTo(820, 670);
                ctx.lineTo(260, 670);
                ctx.closePath();
                ctx.clip();
                drawCroppedToRect(ctx, capturedList[0], 260, 90, 560, 580, 0);
                ctx.restore();

                // 2. Middle Rectangle Window (y=710 s/d 1210)
                ctx.save();
                ctx.beginPath();
                ctx.rect(260, 710, 560, 500);
                ctx.clip();
                drawCroppedToRect(ctx, capturedList[1] || capturedList[0], 260, 710, 560, 500, 0);
                ctx.restore();

                // 3. Bottom Inverted Arch Window (Atap datar y=1250, Dome bawah r=280 pada y=1550)
                ctx.save();
                ctx.beginPath();
                ctx.moveTo(260, 1250);
                ctx.lineTo(820, 1250);
                ctx.lineTo(820, 1550);
                ctx.arc(540, 1550, 280, 0, Math.PI, false);
                ctx.lineTo(260, 1250);
                ctx.closePath();
                ctx.clip();
                drawCroppedToRect(ctx, capturedList[2] || capturedList[0], 260, 1250, 560, 580, 0);
                ctx.restore();

            } else if (shots === 1 || isStaticUpload) {
                    // Layout 3: Classic Photobooth 3-Strip
                    const marginX = 60;
                    const cardW = 960;
                    const gap = 20;
                    const topY = 60;
                    const cardH = 586;

                    for (let k = 0; k < 3; k++) {
                        const y = topY + k * (cardH + gap);
                        drawCroppedToRect(ctx, capturedList[k], marginX, y, cardW, cardH, 20);
                    }
                }

            } else if (shots === 4) {
                // Layout 4: Instagram 2x2 Grid Photobooth
                const marginX = 45;
                const cardW = 485;
                const gapX = 20;
                const topY = 100;
                const cardH = 848;
                const gapY = 24;

                // Cell 1: Top-Left
                drawCroppedToRect(ctx, capturedList[0], marginX, topY, cardW, cardH, 22);
                // Cell 2: Top-Right
                drawCroppedToRect(ctx, capturedList[1], marginX + cardW + gapX, topY, cardW, cardH, 22);
                // Cell 3: Bottom-Left
                drawCroppedToRect(ctx, capturedList[2], marginX, topY + cardH + gapY, cardW, cardH, 22);
                // Cell 4: Bottom-Right
                drawCroppedToRect(ctx, capturedList[3], marginX + cardW + gapX, topY + cardH + gapY, cardW, cardH, 22);
            }

            // ── OVERLAY FRAME CUSTOM (Di atas seluruh foto) ─────────────────────────
            if (isCustomFrame && activeCustomUrl) {
                const customImg = new Image();
                customImg.crossOrigin = 'anonymous';
                customImg.onload = function() {
                    ctx.drawImage(customImg, 0, 0, 1080, 1920);
                    finishRender();
                };
                customImg.onerror = function() {
                    console.warn('Gagal memuat frame custom ke canvas, lanjut render.');
                    finishRender();
                };
                customImg.src = activeCustomUrl;
            } else {
                // Jika belum ada frame custom, beri watermark footer minimalis
                ctx.font = "bold 28px 'Montserrat', sans-serif";
                ctx.fillStyle = "rgba(255,255,255,0.9)";
                ctx.textAlign = "center";
                ctx.fillText(`${WEDDING_DATA.groom} & ${WEDDING_DATA.bride} • ${WEDDING_DATA.date}`, 540, 1870);
                finishRender();
            }
        }

        function finishRender() {
            processingOverlay.style.display = 'none';
            resultModal.style.display = 'flex';

            // Simpan foto otomatis ke dashboard creator
            autoSavePhotoToCreator();
        }

        // ── Auto-Save Foto ke Dashboard Creator ──────────────────────────────────
        function autoSavePhotoToCreator() {
            try {
                const dataUrl = resultCanvas.toDataURL('image/jpeg', 0.92);

                fetch(WEDDING_DATA.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': WEDDING_DATA.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        image: dataUrl,
                        shots: currentShotMode,
                        guest_name: WEDDING_DATA.guestName || 'Tamu Undangan'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data && data.success) {
                        showToast('Foto otomatis tersimpan di galeri photobooth!', 2400);
                    }
                })
                .catch(err => {
                    console.warn('Auto-save photobooth error:', err);
                });
            } catch (err) {
                console.warn('Canvas export toDataURL error:', err);
            }
        }

        // Retake / Foto Ulang
        function retakePhoto() {
            resultModal.style.display = 'none';
            if (isArchMode) {
                archStep = 1;
                archSnapshots = [null, null, null];
                updateArchBadges();

                // Bersihkan slot canvas dan siapkan placeholder tunggu
                const c1 = document.getElementById('archCanvas1');
                if (c1) c1.getContext('2d').clearRect(0, 0, 560, 580);
                const c2 = document.getElementById('archCanvas2');
                if (c2) drawPlaceholder(c2.getContext('2d'), 560, 500, 'Pose 2 (Tengah)', 'Menunggu Pose 1');
                const c3 = document.getElementById('archCanvas3');
                if (c3) drawPlaceholder(c3.getContext('2d'), 560, 580, 'Pose 3 (Bawah)', 'Menunggu Pose 2');
            }
            if (!stream && !uploadedImageObj) {
                startCamera();
            }
        }

        // Unduh Foto 9:16 HD
        function downloadPhoto() {
            const link = document.createElement('a');
            link.download = `Photobooth_${WEDDING_DATA.slug}_${Date.now()}.jpg`;
            link.href = resultCanvas.toDataURL('image/jpeg', 0.95);
            link.click();
            showToast('Foto berhasil diunduh ke Galeri!', 2500);
        }

        // Bagikan ke Instagram Story
        async function shareToInstaStory() {
            resultCanvas.toBlob(async (blob) => {
                if (!blob) {
                    downloadPhoto();
                    return;
                }

                const file = new File([blob], `Story_${WEDDING_DATA.slug}.jpg`, { type: 'image/jpeg' });

                if (navigator.canShare && navigator.canShare({ files: [file] })) {
                    try {
                        await navigator.share({
                            title: `The Wedding of ${WEDDING_DATA.groom} & ${WEDDING_DATA.bride}`,
                            text: `Selamat atas pernikahan ${WEDDING_DATA.groom} & ${WEDDING_DATA.bride}!`,
                            files: [file]
                        });
                        showToast('Berhasil membagikan foto!', 2000);
                    } catch (err) {
                        if (err.name !== 'AbortError') {
                            downloadPhoto();
                            alert('Foto tersimpan di Galeri! Buka Instagram dan pilih foto ini pada Story Anda.');
                        }
                    }
                } else {
                    downloadPhoto();
                    alert('Foto berhasil diunduh ke galeri! Buka aplikasi Instagram dan bagikan foto ini ke Story Anda.');
                }
            }, 'image/jpeg', 0.95);
        }

        // Bagikan ke WhatsApp
        async function shareToWhatsApp() {
            resultCanvas.toBlob(async (blob) => {
                if (!blob) {
                    downloadPhoto();
                    return;
                }

                const file = new File([blob], `Wedding_${WEDDING_DATA.slug}.jpg`, { type: 'image/jpeg' });

                if (navigator.canShare && navigator.canShare({ files: [file] })) {
                    try {
                        await navigator.share({
                            title: `Photobooth Wedding of ${WEDDING_DATA.groom} & ${WEDDING_DATA.bride}`,
                            text: `Halo! Ini hasil foto photobooth pernikahan ${WEDDING_DATA.groom} & ${WEDDING_DATA.bride}. Coba photobooth juga di: ${window.location.href}`,
                            files: [file]
                        });
                        showToast('Berhasil membuka bagikan!', 2000);
                        return;
                    } catch (err) {
                        if (err.name === 'AbortError') return;
                    }
                }

                downloadPhoto();
                const waText = encodeURIComponent(`Halo! Ini hasil foto photobooth pernikahan ${WEDDING_DATA.groom} & ${WEDDING_DATA.bride}!\nFoto telah saya download. Kamu juga bisa coba photobooth di: ${window.location.href}`);
                window.open(`https://api.whatsapp.com/send?text=${waText}`, '_blank');
            }, 'image/jpeg', 0.95);
        }

        function showHelpModal() {
            alert('Petunjuk Penggunaan Photobooth:\n1. Pilih jumlah foto (1 Foto, 2 Foto, 3 Strip, atau 4 Grid Instagram) di atas.\n2. Pilih Bingkai Custom atau Filter Warna di bawah.\n3. Bingkai custom pengantin otomatis terpasang langsung pada kamera live.\n4. Tekan tombol bulat di tengah untuk mulai berfoto.\n5. Hasil foto otomatis tersimpan di dashboard dan siap dibagikan ke Instagram Story atau WhatsApp!');
        }

        // Inisialisasi awal saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            // Pasang custom frame pertama jika tersedia
            if (CUSTOM_FRAMES && CUSTOM_FRAMES.length > 0) {
                const firstPill = document.querySelector('#carouselFrame .selector-pill');
                selectCustomFrame(CUSTOM_FRAMES[0].id, CUSTOM_FRAMES[0].url, firstPill);
            } else {
                liveCustomFrameImg.style.display = 'none';
            }

            // Buka tab Filter Warna secara default agar tamu langsung bisa memilih filter
            switchControlMode('filter');

            startCamera();
        });
    </script>
</body>
</html>
