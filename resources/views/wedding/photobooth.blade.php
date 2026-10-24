@php
    $customFramePaths = $wedding->data_json['photobooth_frames'] ?? [];
    $customFrames = [];
    foreach ($customFramePaths as $idx => $path) {
        $customFrames[] = [
            'id'    => 'custom_' . $idx,
            'label' => 'Custom ' . ($idx + 1),
            'url'   => \Illuminate\Support\Facades\Storage::disk('public')->url($path),
            'type'  => 'image',
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Photobooth InstaStory • {{ $wedding->groom_nickname ?? $wedding->groom_name }} & {{ $wedding->bride_nickname ?? $wedding->bride_name }}</title>
    <meta name="description" content="Live Camera Wedding Photobooth. Ambil foto dengan frame eksklusif pernikahan dan bagikan ke Instagram Story & WhatsApp!">

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

        /* ── Container 9:16 Mobile First ─────────────────────────────────────────── */
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

        /* Ambient blurred live background - fills the vertical 9:16 frame so there are no raw black gaps */
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

        /* Main camera video - Default is 'contain' (100% bawaan kamera asli, ZERO CROP, NO UNNATURAL ZOOM) */
        video#cameraVideo {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
            object-fit: contain; /* Bawaan kamera alami tanpa zoom potong */
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        /* Live Frame Overlay SVG */
        .frame-overlay-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 10;
        }

        /* Live Custom Frame Image Overlay */
        .frame-overlay-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
            z-index: 11;
            display: none;
        }

        /* ── Fit Mode Pill (Bawaan Kamera vs Layar Penuh) ─────────────────────────── */
        .view-mode-pill {
            position: absolute;
            top: calc(env(safe-area-inset-top, 16px) + 54px);
            left: 50%;
            transform: translateX(-50%);
            z-index: 28;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            padding: 5px 12px;
            border-radius: 99px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 16px rgba(0,0,0,0.5);
        }

        .view-mode-pill:active {
            transform: translateX(-50%) scale(0.95);
        }

        .view-mode-pill svg {
            color: #fbbf24;
        }

        /* ── Header Controls ──────────────────────────────────────────────────────── */
        .top-bar {
            position: relative;
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: calc(env(safe-area-inset-top, 16px) + 12px) 16px 12px;
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
        }

        .icon-btn:active {
            transform: scale(0.9);
            background: rgba(255, 255, 255, 0.3);
        }

        .icon-btn.active {
            background: #10b981;
            border-color: #10b981;
            color: #ffffff;
        }

        /* ── Bottom Controls ──────────────────────────────────────────────────────── */
        .bottom-bar {
            position: relative;
            z-index: 30;
            padding: 10px 16px calc(env(safe-area-inset-bottom, 16px) + 12px);
            background: linear-gradient(0deg, rgba(0,0,0,0.94) 0%, rgba(0,0,0,0.65) 70%, rgba(0,0,0,0) 100%);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Mode Switcher: Frame vs Filter */
        .mode-tab-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 2px;
        }

        .mode-tab {
            padding: 5px 14px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #cbd5e1;
            font-size: 0.72rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .mode-tab.active {
            background: rgba(255, 255, 255, 0.95);
            border-color: #ffffff;
            color: #0f172a;
            box-shadow: 0 2px 10px rgba(255, 255, 255, 0.25);
        }

        /* Carousel Containers */
        .carousel-container {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            scrollbar-width: none;
            padding: 4px 2px;
            -ms-overflow-style: none;
        }

        .carousel-container::-webkit-scrollbar {
            display: none;
        }

        /* Frame & Filter Pills */
        .selector-pill {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 13px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 0.74rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .selector-pill:active {
            transform: scale(0.95);
        }

        .selector-pill.active {
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            border-color: #fde68a;
            color: #000000;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.4);
        }

        .selector-pill.filter-pill.active {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            border-color: #c084fc;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(124, 58, 237, 0.4);
        }

        .selector-pill.custom-pill.active {
            background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
            border-color: #f472b6;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(219, 39, 119, 0.4);
        }

        /* Shutter Controls Row */
        .shutter-row {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding-top: 4px;
        }

        /* Shutter Button */
        .shutter-outer {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            border: 4px solid #ffffff;
            padding: 3px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            background: transparent;
        }

        .shutter-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #ffffff;
            transition: all 0.15s ease;
        }

        .shutter-outer:active {
            transform: scale(0.92);
        }

        .shutter-outer:active .shutter-inner {
            background: #fbbf24;
            transform: scale(0.9);
        }

        /* Countdown Overlay */
        .countdown-overlay {
            position: absolute;
            inset: 0;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(2px);
        }

        .countdown-number {
            font-size: 8rem;
            font-weight: 900;
            color: #ffffff;
            text-shadow: 0 8px 32px rgba(0, 0, 0, 0.6);
            animation: pulseCount 0.9s infinite;
        }

        @keyframes pulseCount {
            0% { transform: scale(0.6); opacity: 0; }
            50% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(1); opacity: 0.9; }
        }

        /* Camera Flash Effect */
        .flash-overlay {
            position: absolute;
            inset: 0;
            background: #ffffff;
            opacity: 0;
            pointer-events: none;
            z-index: 60;
            transition: opacity 0.08s ease-out;
        }

        .flash-overlay.active {
            opacity: 1;
        }

        /* Processing Loader */
        .processing-overlay {
            position: absolute;
            inset: 0;
            z-index: 65;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(9, 13, 22, 0.85);
            backdrop-filter: blur(8px);
            gap: 14px;
        }

        .processing-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top-color: #fbbf24;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ── Preview Result Modal (9:16 Snapshot) ─────────────────────────────────── */
        .result-modal {
            position: absolute;
            inset: 0;
            z-index: 70;
            background: #090d16;
            display: none;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.98); }
            to { opacity: 1; transform: scale(1); }
        }

        .result-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: calc(env(safe-area-inset-top, 16px) + 12px) 16px 12px;
            background: linear-gradient(180deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 80;
        }

        .result-image-wrap {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000000;
            padding: calc(env(safe-area-inset-top, 16px) + 60px) 12px calc(env(safe-area-inset-bottom, 16px) + 160px);
        }

        #resultCanvas {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 14px;
            box-shadow: 0 12px 50px rgba(0,0,0,0.9);
        }

        .result-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 80;
            padding: 16px 16px calc(env(safe-area-inset-bottom, 16px) + 16px);
            background: linear-gradient(0deg, rgba(0,0,0,0.98) 0%, rgba(0,0,0,0.7) 70%, rgba(0,0,0,0) 100%);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-action-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #e1306c 0%, #f77737 50%, #fbad50 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 0.92rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(225, 48, 108, 0.4);
            font-family: 'Montserrat', sans-serif;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-action-primary:active {
            transform: scale(0.98);
        }

        .btn-action-wa {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 0.88rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(37, 211, 102, 0.35);
            font-family: 'Montserrat', sans-serif;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-action-wa:active {
            transform: scale(0.98);
        }

        .btn-action-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-action-secondary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            border-radius: 12px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-action-secondary:active {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Fallback Permission / Error Card */
        .camera-notice-card {
            position: absolute;
            inset: 20px;
            margin: auto;
            max-width: 360px;
            height: fit-content;
            background: rgba(15, 23, 42, 0.95);
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 24px 20px;
            text-align: center;
            z-index: 40;
            display: none;
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
        }

        .camera-notice-card h3 {
            font-size: 1.1rem;
            font-weight: 800;
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
            background: #10b981;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
        }

        /* Toast Notification */
        .toast-notif {
            position: absolute;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 99px;
            font-size: 0.78rem;
            font-weight: 700;
            z-index: 90;
            display: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.6);
            white-space: nowrap;
            pointer-events: none;
        }
    </style>
</head>
<body>

    <div class="photobooth-container">

        {{-- Toast Notif --}}
        <div id="toastNotif" class="toast-notif"></div>

        {{-- ── 1. Viewfinder Camera Stream ────────────────────────────────────────── --}}
        <div class="viewfinder-wrapper" id="viewfinderWrapper">
            {{-- Background ambient blur (mengisi ruang 9:16 agar tidak ada ruang kosong hitam kaku) --}}
            <video id="cameraVideoBg" autoplay playsinline muted></video>

            {{-- Main live video: Bawaan Kamera Asli (object-fit: contain, ZERO CROP) --}}
            <video id="cameraVideo" autoplay playsinline muted></video>

            {{-- Default SVG Frame Overlay --}}
            <svg id="liveFrameSvg" class="frame-overlay-svg" viewBox="0 0 1080 1920" preserveAspectRatio="none">
                {{-- Dynamic SVG Frame Template injected by JS --}}
            </svg>

            {{-- Custom PNG Frame Overlay --}}
            <img id="liveCustomFrameImg" class="frame-overlay-img" alt="Custom Frame Overlay">

            {{-- Toggle Mode: Bawaan Kamera (Alami) vs Layar Penuh (Zoom) --}}
            <button type="button" class="view-mode-pill" id="btnViewModeToggle" onclick="toggleViewMode()" title="Klik untuk beralih antara Kamera Alami atau Layar Penuh">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polygon points="12 8 8 12 12 16 12 8"></polygon>
                    <polygon points="12 8 16 12 12 16 12 8"></polygon>
                </svg>
                <span id="viewModeLabel">Kamera Asli (Alami)</span>
            </button>

            {{-- Flash Effect --}}
            <div id="flashOverlay" class="flash-overlay"></div>

            {{-- Countdown Display --}}
            <div id="countdownOverlay" class="countdown-overlay">
                <span id="countdownNumber" class="countdown-number">3</span>
            </div>

            {{-- Processing / Saving Loader --}}
            <div id="processingOverlay" class="processing-overlay">
                <div class="processing-spinner"></div>
                <div style="font-size:0.85rem; font-weight:800; color:#fbbf24; text-shadow:0 2px 8px rgba(0,0,0,0.8);">
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

        {{-- ── 3. Bottom Frame / Filter Selector & Shutter ────────────────────────── --}}
        <div class="bottom-bar">
            {{-- Switcher Tab: Bingkai (Frame) vs Filter Warna --}}
            <div class="mode-tab-bar">
                <button type="button" class="mode-tab active" id="tabModeFrame" onclick="switchControlMode('frame')">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="3"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <path d="M21 15l-5-5L5 21"></path>
                    </svg>
                    <span>Bingkai (Frame)</span>
                </button>
                <button type="button" class="mode-tab" id="tabModeFilter" onclick="switchControlMode('filter')">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 3a9 9 0 0 0 0 18v-18z"></path>
                    </svg>
                    <span>Filter Warna</span>
                </button>
            </div>

            {{-- 1. Carousel Frame Pilihan (Default + Custom PNG) --}}
            <div id="carouselFrame" class="carousel-container">
                {{-- Default SVG Frames --}}
                <button type="button" class="selector-pill active" onclick="selectFrame('gold_arch', this)">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    Classic Gold
                </button>
                <button type="button" class="selector-pill" onclick="selectFrame('romantic_floral', this)">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    Floral Romance
                </button>
                <button type="button" class="selector-pill" onclick="selectFrame('modern_arch', this)">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="4"></rect>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    Minimalist Arch
                </button>
                <button type="button" class="selector-pill" onclick="selectFrame('polaroid_retro', this)">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Polaroid Strip
                </button>

                {{-- Custom Frames Diupload Pengantin --}}
                @foreach($customFrames as $cf)
                    <button type="button" class="selector-pill custom-pill" onclick="selectCustomFrame('{{ $cf['id'] }}', '{{ $cf['url'] }}', this)">
                        <svg width="13" height="13" fill="none" stroke="#f472b6" stroke-width="2.3" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                        <span>{{ $cf['label'] }}</span>
                        <small style="font-size:0.6rem; background:#ec4899; color:#fff; padding:1px 5px; border-radius:99px;">Custom</small>
                    </button>
                @endforeach
            </div>

            {{-- 2. Carousel Filter Warna (Hidden by default, shown when mode is 'filter') --}}
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
                <button type="button" class="shutter-outer" onclick="triggerCapture()" title="Jepret Foto">
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
                {{-- 1. Share ke Instagram Story (Web Share API / Direct Download + Guide) --}}
                <button type="button" id="btnShareStory" class="btn-action-primary" onclick="shareToInstaStory()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                    <span>Share ke Instagram Story</span>
                </button>

                {{-- 2. Kirim ke WhatsApp --}}
                <button type="button" id="btnShareWa" class="btn-action-wa" onclick="shareToWhatsApp()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                    </svg>
                    <span>Kirim ke WhatsApp</span>
                </button>

                <div class="btn-action-row">
                    {{-- 3. Download Button --}}
                    <button type="button" class="btn-action-secondary" onclick="downloadPhoto()">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Unduh Foto HD</span>
                    </button>

                    {{-- 4. Retake Button --}}
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
            guestName: {!! json_encode($guestName) !!}
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
        let selectedFrame = 'gold_arch';
        let isCustomFrame = false;
        let activeCustomUrl = '';
        let currentFilterKey = 'normal';
        let timerSeconds = 0; // 0, 3, or 5
        let isCapturing = false;
        let uploadedImageObj = null;

        // View Mode: 'contain' = Bawaan Kamera Asli (Zero Crop / Alami), 'cover' = Layar Penuh (Zoom Crop)
        let activeViewMode = 'contain';

        const videoEl = document.getElementById('cameraVideo');
        const videoBgEl = document.getElementById('cameraVideoBg');
        const liveFrameSvg = document.getElementById('liveFrameSvg');
        const liveCustomFrameImg = document.getElementById('liveCustomFrameImg');
        const flashOverlay = document.getElementById('flashOverlay');
        const countdownOverlay = document.getElementById('countdownOverlay');
        const countdownNumber = document.getElementById('countdownNumber');
        const processingOverlay = document.getElementById('processingOverlay');
        const resultModal = document.getElementById('resultModal');
        const resultCanvas = document.getElementById('resultCanvas');
        const cameraNotice = document.getElementById('cameraNotice');
        const toastNotif = document.getElementById('toastNotif');

        function showToast(msg, duration = 2500) {
            toastNotif.textContent = msg;
            toastNotif.style.display = 'block';
            setTimeout(() => {
                toastNotif.style.display = 'none';
            }, duration);
        }

        // ── Inisialisasi Kamera Native Bebas Paksaan Crop ───────────────────────────────
        async function startCamera() {
            cameraNotice.style.display = 'none';

            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            // Gunakan constraint native alami dari perangkat tanpa memaksakan ukuran terbalik
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
                } catch (fallbackErr) {
                    console.warn('Gagal total membuka kamera:', fallbackErr);
                    cameraNotice.style.display = 'block';
                }
            }
        }

        // Apply scale, flip, and fit mode to video elements
        function applyTransform() {
            const flipX = currentFacingMode === 'user' ? -1 : 1;
            videoEl.style.transform = `scaleX(${flipX})`;
            if (videoBgEl) {
                videoBgEl.style.transform = `scale(1.15) scaleX(${flipX})`;
            }

            if (activeViewMode === 'contain') {
                videoEl.style.objectFit = 'contain';
                if (videoBgEl) videoBgEl.style.display = 'block';
            } else {
                videoEl.style.objectFit = 'cover';
                if (videoBgEl) videoBgEl.style.display = 'none';
            }
        }

        // Toggle View Mode: Bawaan Kamera (Alami / No Crop) vs Layar Penuh (Zoom Crop)
        function toggleViewMode() {
            activeViewMode = activeViewMode === 'contain' ? 'cover' : 'contain';
            const label = document.getElementById('viewModeLabel');
            if (activeViewMode === 'contain') {
                label.textContent = 'Kamera Asli (Alami)';
                showToast('Mode: Kamera Bawaan Asli (Tanpa Zoom / Crop)', 2000);
            } else {
                label.textContent = 'Layar Penuh (Zoom)';
                showToast('Mode: Layar Penuh (Di-crop menyesuaikan layar)', 2000);
            }
            applyTransform();
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

        // Select Default SVG Frame
        function selectFrame(frameType, btnEl) {
            selectedFrame = frameType;
            isCustomFrame = false;
            activeCustomUrl = '';

            document.querySelectorAll('#carouselFrame .selector-pill').forEach(el => el.classList.remove('active'));
            if (btnEl) btnEl.classList.add('active');

            liveCustomFrameImg.style.display = 'none';
            liveFrameSvg.style.display = 'block';

            const coupleTitle = `${WEDDING_DATA.groom} & ${WEDDING_DATA.bride}`;
            liveFrameSvg.innerHTML = getFrameSvgContent(selectedFrame, coupleTitle, WEDDING_DATA.date);
        }

        // Select Custom PNG Frame (uploaded by creator)
        function selectCustomFrame(frameId, frameUrl, btnEl) {
            selectedFrame = frameId;
            isCustomFrame = true;
            activeCustomUrl = frameUrl;

            document.querySelectorAll('#carouselFrame .selector-pill').forEach(el => el.classList.remove('active'));
            if (btnEl) btnEl.classList.add('active');

            liveFrameSvg.style.display = 'none';
            liveCustomFrameImg.src = frameUrl;
            liveCustomFrameImg.style.display = 'block';

            showToast('Memilih frame custom pernikahan', 1500);
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
                    renderFinalSnapshot(img, true);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        // ── 4 Template Frame Photobooth 9:16 (Bebas font network @import untuk mencegah CORS) ──
        function getFrameSvgContent(frameType, coupleName, dateStr) {
            if (frameType === 'gold_arch') {
                return `
                    <defs>
                        <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#fef08a" />
                            <stop offset="50%" stop-color="#eab308" />
                            <stop offset="100%" stop-color="#ca8a04" />
                        </linearGradient>
                    </defs>
                    <rect x="30" y="30" width="1020" height="1860" rx="36" fill="none" stroke="url(#goldGrad)" stroke-width="4" opacity="0.9" />
                    <rect x="46" y="46" width="988" height="1828" rx="26" fill="none" stroke="url(#goldGrad)" stroke-width="1.5" stroke-dasharray="10 8" opacity="0.7" />
                    <path d="M 120 180 Q 540 80 960 180" fill="none" stroke="url(#goldGrad)" stroke-width="3" opacity="0.8" />
                    <text x="540" y="140" fill="#fef08a" font-family="'Playfair Display', serif" font-size="34" font-weight="700" letter-spacing="6" text-anchor="middle">THE WEDDING CELEBRATION</text>
                    <text x="540" y="240" fill="#ffffff" font-family="'Playfair Display', serif" font-size="64" font-weight="800" text-anchor="middle">${coupleName}</text>
                    <circle cx="50" cy="50" r="14" fill="none" stroke="url(#goldGrad)" stroke-width="2" />
                    <circle cx="1030" cy="50" r="14" fill="none" stroke="url(#goldGrad)" stroke-width="2" />
                    <circle cx="50" cy="1870" r="14" fill="none" stroke="url(#goldGrad)" stroke-width="2" />
                    <circle cx="1030" cy="1870" r="14" fill="none" stroke="url(#goldGrad)" stroke-width="2" />
                    <rect x="240" y="1740" width="600" height="70" rx="35" fill="rgba(0,0,0,0.6)" stroke="url(#goldGrad)" stroke-width="1.5" />
                    <text x="540" y="1785" fill="#fef08a" font-family="'Montserrat', sans-serif" font-size="28" font-weight="600" letter-spacing="3" text-anchor="middle">${dateStr}</text>
                    <text x="540" y="1855" fill="#ffffff" font-family="'Montserrat', sans-serif" font-size="20" font-weight="500" opacity="0.75" letter-spacing="4" text-anchor="middle">BUYLE.ID &bull; PHOTOBOOTH</text>
                `;
            } else if (frameType === 'romantic_floral') {
                return `
                    <defs>
                        <linearGradient id="roseGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#fda4af" />
                            <stop offset="100%" stop-color="#f43f5e" />
                        </linearGradient>
                    </defs>
                    <rect x="40" y="40" width="1000" height="1840" rx="40" fill="none" stroke="#fecdd3" stroke-width="3" opacity="0.85" />
                    <text x="540" y="130" fill="#fda4af" font-family="'Cormorant Garamond', serif" font-size="44" font-style="italic" font-weight="600" text-anchor="middle">Happily Ever After</text>
                    <text x="540" y="210" fill="#ffffff" font-family="'Playfair Display', serif" font-size="58" font-weight="800" text-anchor="middle">${coupleName}</text>
                    <path d="M 80 1720 Q 540 1660 1000 1720 L 1000 1850 Q 540 1880 80 1850 Z" fill="rgba(15,23,42,0.65)" />
                    <text x="540" y="1775" fill="#fecdd3" font-family="'Montserrat', sans-serif" font-size="26" font-weight="700" letter-spacing="4" text-anchor="middle">${dateStr}</text>
                    <text x="540" y="1825" fill="#ffffff" font-family="'Montserrat', sans-serif" font-size="20" font-weight="600" opacity="0.7" letter-spacing="3" text-anchor="middle">#${coupleName.replace(/[^a-zA-Z]/g, '')}</text>
                `;
            } else if (frameType === 'modern_arch') {
                return `
                    <path d="M 60 1860 L 60 540 A 480 480 0 0 1 1020 540 L 1020 1860 Z" fill="none" stroke="#ffffff" stroke-width="3.5" opacity="0.9" />
                    <text x="540" y="140" fill="#ffffff" font-family="'Montserrat', sans-serif" font-size="26" font-weight="800" letter-spacing="10" text-anchor="middle">V O W S  &amp;  F O R E V E R</text>
                    <text x="540" y="215" fill="#ffffff" font-family="'Playfair Display', serif" font-size="62" font-weight="800" text-anchor="middle">${coupleName}</text>
                    <rect x="80" y="1740" width="920" height="75" rx="8" fill="rgba(0,0,0,0.6)" />
                    <text x="120" y="1788" fill="#ffffff" font-family="'Montserrat', sans-serif" font-size="26" font-weight="700" letter-spacing="2">${dateStr}</text>
                    <text x="960" y="1788" fill="#10b981" font-family="'Montserrat', sans-serif" font-size="24" font-weight="800" text-anchor="end">BUYLE.ID</text>
                `;
            } else if (frameType === 'polaroid_retro') {
                return `
                    <rect x="0" y="0" width="1080" height="1920" fill="none" stroke="#ffffff" stroke-width="60" />
                    <rect x="0" y="1560" width="1080" height="360" fill="#ffffff" />
                    <text x="540" y="1670" fill="#0f172a" font-family="'Playfair Display', serif" font-size="64" font-weight="800" text-anchor="middle">${coupleName}</text>
                    <text x="540" y="1740" fill="#475569" font-family="'Montserrat', sans-serif" font-size="30" font-weight="600" letter-spacing="4" text-anchor="middle">${dateStr}</text>
                    <line x1="280" y1="1780" x2="800" y2="1780" stroke="#cbd5e1" stroke-width="2" />
                    <text x="540" y="1840" fill="#94a3b8" font-family="'Montserrat', sans-serif" font-size="22" font-weight="700" letter-spacing="6" text-anchor="middle">INSTASTORY PHOTOBOOTH</text>
                `;
            }
            return '';
        }

        // ── Shutter Action & Timer Countdown ───────────────────────────────────────────
        function triggerCapture() {
            if (isCapturing) return;

            if (timerSeconds > 0) {
                isCapturing = true;
                let count = timerSeconds;
                countdownNumber.textContent = count;
                countdownOverlay.style.display = 'flex';

                const interval = setInterval(() => {
                    count--;
                    if (count > 0) {
                        countdownNumber.textContent = count;
                    } else {
                        clearInterval(interval);
                        countdownOverlay.style.display = 'none';
                        isCapturing = false;
                        executeSnap();
                    }
                }, 1000);
            } else {
                executeSnap();
            }
        }

        function executeSnap() {
            // Flash Effect
            flashOverlay.classList.add('active');
            setTimeout(() => {
                flashOverlay.classList.remove('active');
            }, 120);

            // Shutter Beep via Web Audio API
            playShutterSound();

            // Tampilkan loader memproses
            processingOverlay.style.display = 'flex';

            // Sedikit delay agar efek flash terasa natural
            setTimeout(() => {
                renderFinalSnapshot(videoEl, false);
            }, 80);
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

        // ── Render Foto + Frame + Filter ke Canvas 1080x1920 (9:16 InstaStory) ─────────
        function renderFinalSnapshot(sourceMedia, isStaticImage) {
            const ctx = resultCanvas.getContext('2d');
            ctx.clearRect(0, 0, 1080, 1920);

            const cw = 1080;
            const ch = 1920;
            let sw, sh;

            if (isStaticImage) {
                sw = sourceMedia.width;
                sh = sourceMedia.height;
            } else {
                sw = sourceMedia.videoWidth || 1080;
                sh = sourceMedia.videoHeight || 1920;
            }

            const filterDef = FILTER_DEFINITIONS[currentFilterKey];
            const filterCss = filterDef && filterDef.css !== 'none' ? filterDef.css : 'none';

            if (activeViewMode === 'contain') {
                // ── 1. AMBIENT BLUR BACKGROUND (Mempercantik rasio 9:16 InstaStory tanpa crop foto) ──
                ctx.save();
                ctx.filter = filterCss !== 'none'
                    ? `${filterCss} blur(40px) brightness(0.4) saturate(1.2)`
                    : 'blur(40px) brightness(0.4) saturate(1.2)';

                if (!isStaticImage && currentFacingMode === 'user') {
                    ctx.translate(cw, 0);
                    ctx.scale(-1, 1);
                }
                ctx.drawImage(sourceMedia, 0, 0, sw, sh, -100, -100, cw + 200, ch + 200);
                ctx.restore();

                // ── 2. FOTO ASLI ALAMI DI TENGAH (100% Kamera Asli, Nol Crop, Nol Zoom) ──
                ctx.save();
                ctx.filter = filterCss;
                if (!isStaticImage && currentFacingMode === 'user') {
                    ctx.translate(cw, 0);
                    ctx.scale(-1, 1);
                }

                // Fit contain math
                const scaleFit = Math.min(cw / sw, ch / sh);
                const drawW = sw * scaleFit;
                const drawH = sh * scaleFit;
                const drawX = (cw - drawW) / 2;
                const drawY = (ch - drawH) / 2;

                ctx.drawImage(sourceMedia, 0, 0, sw, sh, drawX, drawY, drawW, drawH);
                ctx.restore();
            } else {
                // ── MODE LAYAR PENUH (Cover Center Crop) ──
                const canvasAspect = cw / ch;
                const sourceAspect = sw / sh;
                let renderW, renderH, offsetX, offsetY;

                if (sourceAspect > canvasAspect) {
                    renderH = sh;
                    renderW = sh * canvasAspect;
                    offsetX = (sw - renderW) / 2;
                    offsetY = 0;
                } else {
                    renderW = sw;
                    renderH = sw / canvasAspect;
                    offsetX = 0;
                    offsetY = (sh - renderH) / 2;
                }

                ctx.save();
                ctx.filter = filterCss;
                if (!isStaticImage && currentFacingMode === 'user') {
                    ctx.translate(cw, 0);
                    ctx.scale(-1, 1);
                }
                ctx.drawImage(sourceMedia, offsetX, offsetY, renderW, renderH, 0, 0, cw, ch);
                ctx.restore();
            }

            // Selesai menggambar video, reset filter untuk overlay frame
            ctx.filter = 'none';

            // ── 3. OVERLAY FRAME (Custom PNG atau Default SVG) ──
            if (isCustomFrame && activeCustomUrl) {
                const customImg = new Image();
                customImg.crossOrigin = 'anonymous';
                customImg.onload = function() {
                    ctx.drawImage(customImg, 0, 0, 1080, 1920);
                    finishRender();
                };
                customImg.onerror = function() {
                    console.warn('Gagal load frame custom overlay, lanjut render foto.');
                    finishRender();
                };
                customImg.src = activeCustomUrl;
            } else {
                // Default Frame SVG
                const coupleTitle = `${WEDDING_DATA.groom} & ${WEDDING_DATA.bride}`;
                const svgContent = getFrameSvgContent(selectedFrame, coupleTitle, WEDDING_DATA.date);
                const svgData = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="1080" height="1920" viewBox="0 0 1080 1920">
                        ${svgContent}
                    </svg>
                `;

                const svgImg = new Image();
                const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
                const url = URL.createObjectURL(svgBlob);

                svgImg.onload = function() {
                    ctx.drawImage(svgImg, 0, 0, 1080, 1920);
                    URL.revokeObjectURL(url);
                    finishRender();
                };
                svgImg.onerror = function(err) {
                    console.warn('SVG frame rendering fallback:', err);
                    URL.revokeObjectURL(url);
                    finishRender();
                };
                svgImg.src = url;
            }
        }

        function finishRender() {
            processingOverlay.style.display = 'none';
            resultModal.style.display = 'flex';
        }

        // Retake / Foto Ulang
        function retakePhoto() {
            resultModal.style.display = 'none';
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

        // Bagikan ke Instagram Story (Web Share API)
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
                    // Fallback jika Web Share API files tidak didukung (e.g. Chrome desktop)
                    downloadPhoto();
                    alert('Foto berhasil diunduh ke galeri! Silakan buka aplikasi Instagram Anda dan bagikan di Story.');
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

                // Coba gunakan Web Share API agar langsung bisa memilih kontak WhatsApp
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

                // Fallback: Unduh foto dan buka WhatsApp dengan pesan teks
                downloadPhoto();
                const waText = encodeURIComponent(`Halo! Ini hasil foto photobooth pernikahan ${WEDDING_DATA.groom} & ${WEDDING_DATA.bride}!\nFoto telah saya download. Kamu juga bisa coba photobooth di: ${window.location.href}`);
                window.open(`https://api.whatsapp.com/send?text=${waText}`, '_blank');
            }, 'image/jpeg', 0.95);
        }

        function showHelpModal() {
            alert('Petunjuk Penggunaan Photobooth:\n1. Tampilan kamera menggunakan format asli (alami) tanpa zoom terpotong.\n2. Jika ingin tampilan layar penuh, klik tombol "Kamera Asli (Alami)" di atas untuk beralih.\n3. Pilih Bingkai (Frame) atau Filter Warna di bawah.\n4. Tekan tombol bulat di tengah untuk jepret foto.\n5. Hasil foto langsung siap di-share ke Instagram Story, WhatsApp, atau download foto HD!');
        }

        // Start default
        document.addEventListener('DOMContentLoaded', () => {
            selectFrame('gold_arch', document.querySelector('#carouselFrame .selector-pill.active'));
            startCamera();
        });
    </script>
</body>
</html>
