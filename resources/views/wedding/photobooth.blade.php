<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Photobooth InstaStory • {{ $wedding->groom_nickname ?? $wedding->groom_name }} & {{ $wedding->bride_nickname ?? $wedding->bride_name }}</title>
    <meta name="description" content="Live Camera Wedding Photobooth. Ambil foto dengan frame eksklusif pernikahan dan bagikan ke Instagram Story!">

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
            background-color: #090d16;
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
        }

        video#cameraVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            transform: scaleX(-1); /* Default mirrored for front camera */
            transition: transform 0.2s ease;
        }

        video#cameraVideo.back-camera {
            transform: scaleX(1);
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

        /* ── Header Controls ──────────────────────────────────────────────────────── */
        .top-bar {
            position: relative;
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: calc(env(safe-area-inset-top, 16px) + 12px) 16px 12px;
            background: linear-gradient(180deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
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
            padding: 12px 16px calc(env(safe-area-inset-bottom, 16px) + 14px);
            background: linear-gradient(0deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.5) 60%, rgba(0,0,0,0) 100%);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Frame Selector Carousel */
        .frame-carousel {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow-x: auto;
            scrollbar-width: none;
            padding: 4px 2px;
            -ms-overflow-style: none;
        }

        .frame-carousel::-webkit-scrollbar {
            display: none;
        }

        .frame-pill {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .frame-pill:active {
            transform: scale(0.95);
        }

        .frame-pill.active {
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            border-color: #fde68a;
            color: #000000;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.4);
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
        }

        .result-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: calc(env(safe-area-inset-top, 16px) + 12px) 16px 12px;
            background: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
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
        }

        #resultCanvas {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            box-shadow: 0 10px 40px rgba(0,0,0,0.8);
        }

        .result-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 80;
            padding: 16px 16px calc(env(safe-area-inset-bottom, 16px) + 16px);
            background: linear-gradient(0deg, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.6) 70%, rgba(0,0,0,0) 100%);
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
        }

        .btn-action-primary:active {
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
    </style>
</head>
<body>

    <div class="photobooth-container">

        {{-- ── 1. Viewfinder Camera Stream ────────────────────────────────────────── --}}
        <div class="viewfinder-wrapper">
            <video id="cameraVideo" autoplay playsinline muted></video>

            {{-- Frame SVG Overlay --}}
            <svg id="liveFrameSvg" class="frame-overlay-svg" viewBox="0 0 1080 1920" preserveAspectRatio="none">
                {{-- Dynamic SVG Frame Template injected by JS --}}
            </svg>

            {{-- Flash Effect --}}
            <div id="flashOverlay" class="flash-overlay"></div>

            {{-- Countdown Display --}}
            <div id="countdownOverlay" class="countdown-overlay">
                <span id="countdownNumber" class="countdown-number">3</span>
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

        {{-- ── 3. Bottom Frame Selector & Shutter ─────────────────────────────────── --}}
        <div class="bottom-bar">
            {{-- Carousel Frame Pilihan --}}
            <div class="frame-carousel">
                <button type="button" class="frame-pill active" onclick="selectFrame('gold_arch', this)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    Classic Gold
                </button>
                <button type="button" class="frame-pill" onclick="selectFrame('romantic_floral', this)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    Floral Romance
                </button>
                <button type="button" class="frame-pill" onclick="selectFrame('modern_arch', this)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="4"></rect>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    Minimalist Arch
                </button>
                <button type="button" class="frame-pill" onclick="selectFrame('polaroid_retro', this)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Polaroid Strip
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
                <button type="button" class="icon-btn" onclick="alert('Arahkan kamera ke wajah Anda, pilih frame estetik favorit di bawah, dan tekan tombol tengah untuk jepret foto siap share ke Instagram Story!')" title="Petunjuk">
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
                <button type="button" class="brand-badge" onclick="retakePhoto()" style="background:rgba(0,0,0,0.6); cursor:pointer;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path d="M1 4v6h6"/>
                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                    </svg>
                    <span>Foto Ulang</span>
                </button>
                <div style="font-size:0.75rem; font-weight:800; color:#fbbf24; text-transform:uppercase; letter-spacing:1px;">
                    Photobooth 9:16
                </div>
            </div>

            <div class="result-image-wrap">
                <canvas id="resultCanvas" width="1080" height="1920"></canvas>
            </div>

            <div class="result-footer">
                {{-- Share ke InstaStory (Web Share API) --}}
                <button type="button" id="btnShareStory" class="btn-action-primary" onclick="shareToInstaStory()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                    <span>Share ke Instagram Story</span>
                </button>

                <div class="btn-action-row">
                    {{-- Download Button --}}
                    <button type="button" class="btn-action-secondary" onclick="downloadPhoto()">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Unduh Foto</span>
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
            guestName: {!! json_encode($guestName) !!}
        };

        // Camera State
        let stream = null;
        let currentFacingMode = 'user'; // 'user' (front) or 'environment' (back)
        let selectedFrame = 'gold_arch';
        let timerSeconds = 0; // 0 (off), 3, or 5
        let isCapturing = false;
        let uploadedImageObj = null;

        const videoEl = document.getElementById('cameraVideo');
        const liveFrameSvg = document.getElementById('liveFrameSvg');
        const flashOverlay = document.getElementById('flashOverlay');
        const countdownOverlay = document.getElementById('countdownOverlay');
        const countdownNumber = document.getElementById('countdownNumber');
        const resultModal = document.getElementById('resultModal');
        const resultCanvas = document.getElementById('resultCanvas');
        const cameraNotice = document.getElementById('cameraNotice');

        // ── Inisialisasi Kamera ──────────────────────────────────────────────────────────
        async function startCamera() {
            cameraNotice.style.display = 'none';

            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            const constraints = {
                audio: false,
                video: {
                    facingMode: currentFacingMode,
                    width: { ideal: 1080 },
                    height: { ideal: 1920 }
                }
            };

            try {
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                videoEl.srcObject = stream;
                uploadedImageObj = null;

                if (currentFacingMode === 'user') {
                    videoEl.classList.remove('back-camera');
                } else {
                    videoEl.classList.add('back-camera');
                }
            } catch (err) {
                console.warn('Gagal membuka kamera:', err);
                cameraNotice.style.display = 'block';
            }
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
            } else if (timerSeconds === 3) {
                timerSeconds = 5;
                btn.classList.add('active');
                ind.style.display = 'block';
                ind.textContent = '5s';
            } else {
                timerSeconds = 0;
                btn.classList.remove('active');
                ind.style.display = 'none';
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
                    // Render langsung ke result modal
                    renderFinalSnapshot(img, true);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        // ── 4 Template Frame Photobooth 9:16 (SVG & Canvas Render) ─────────────────────
        function getFrameSvgContent(frameType, coupleName, dateStr) {
            if (frameType === 'gold_arch') {
                return `
                    <!-- Frame 1: Classic Royal Gold -->
                    <defs>
                        <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#fef08a" />
                            <stop offset="50%" stop-color="#eab308" />
                            <stop offset="100%" stop-color="#ca8a04" />
                        </linearGradient>
                    </defs>
                    <!-- Outer Decorative Border -->
                    <rect x="30" y="30" width="1020" height="1860" rx="36" fill="none" stroke="url(#goldGrad)" stroke-width="4" opacity="0.9" />
                    <rect x="46" y="46" width="988" height="1828" rx="26" fill="none" stroke="url(#goldGrad)" stroke-width="1.5" stroke-dasharray="10 8" opacity="0.7" />

                    <!-- Top Banner Arch -->
                    <path d="M 120 180 Q 540 80 960 180" fill="none" stroke="url(#goldGrad)" stroke-width="3" opacity="0.8" />
                    <text x="540" y="140" fill="#fef08a" font-family="'Playfair Display', serif" font-size="34" font-weight="700" letter-spacing="6" text-anchor="middle">THE WEDDING CELEBRATION</text>
                    <text x="540" y="240" fill="#ffffff" font-family="'Playfair Display', serif" font-size="64" font-weight="800" text-anchor="middle" filter="drop-shadow(0 4px 12px rgba(0,0,0,0.8))">${coupleName}</text>

                    <!-- Corner Ornaments -->
                    <circle cx="50" cy="50" r="14" fill="none" stroke="url(#goldGrad)" stroke-width="2" />
                    <circle cx="1030" cy="50" r="14" fill="none" stroke="url(#goldGrad)" stroke-width="2" />
                    <circle cx="50" cy="1870" r="14" fill="none" stroke="url(#goldGrad)" stroke-width="2" />
                    <circle cx="1030" cy="1870" r="14" fill="none" stroke="url(#goldGrad)" stroke-width="2" />

                    <!-- Bottom Bar Details -->
                    <rect x="240" y="1740" width="600" height="70" rx="35" fill="rgba(0,0,0,0.55)" stroke="url(#goldGrad)" stroke-width="1.5" />
                    <text x="540" y="1785" fill="#fef08a" font-family="'Montserrat', sans-serif" font-size="28" font-weight="600" letter-spacing="3" text-anchor="middle">${dateStr}</text>

                    <!-- Watermark -->
                    <text x="540" y="1855" fill="#ffffff" font-family="'Montserrat', sans-serif" font-size="20" font-weight="500" opacity="0.75" letter-spacing="4" text-anchor="middle">BUYLE.ID • WEDDING PHOTOBOOTH</text>
                `;
            } else if (frameType === 'romantic_floral') {
                return `
                    <!-- Frame 2: Romantic Floral Elegance -->
                    <defs>
                        <linearGradient id="roseGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#fda4af" />
                            <stop offset="100%" stop-color="#f43f5e" />
                        </linearGradient>
                    </defs>
                    <!-- Soft Border with Vignette -->
                    <rect x="40" y="40" width="1000" height="1840" rx="40" fill="none" stroke="#fecdd3" stroke-width="3" opacity="0.85" />

                    <!-- Top Floating Header -->
                    <g filter="drop-shadow(0 4px 16px rgba(0,0,0,0.7))">
                        <text x="540" y="130" fill="#fda4af" font-family="'Cormorant Garamond', serif" font-size="42" font-style="italic" font-weight="600" text-anchor="middle">Happy Ever After</text>
                        <text x="540" y="210" fill="#ffffff" font-family="'Playfair Display', serif" font-size="58" font-weight="800" text-anchor="middle">${coupleName}</text>
                    </g>

                    <!-- Bottom Vignette Card -->
                    <path d="M 80 1720 Q 540 1660 1000 1720 L 1000 1850 Q 540 1880 80 1850 Z" fill="rgba(15,23,42,0.65)" />
                    <text x="540" y="1775" fill="#fecdd3" font-family="'Montserrat', sans-serif" font-size="26" font-weight="700" letter-spacing="4" text-anchor="middle">${dateStr}</text>
                    <text x="540" y="1825" fill="#ffffff" font-family="'Montserrat', sans-serif" font-size="20" font-weight="600" opacity="0.7" letter-spacing="3" text-anchor="middle">#${coupleName.replace(/[^a-zA-Z]/g, '')}</text>
                `;
            } else if (frameType === 'modern_arch') {
                return `
                    <!-- Frame 3: Minimalist Modern Arch -->
                    <path d="M 60 1860 L 60 540 A 480 480 0 0 1 1020 540 L 1020 1860 Z" fill="none" stroke="#ffffff" stroke-width="3.5" opacity="0.9" />

                    <!-- Editorial Headline -->
                    <text x="540" y="140" fill="#ffffff" font-family="'Montserrat', sans-serif" font-size="26" font-weight="800" letter-spacing="10" text-anchor="middle">V O W S  &amp;  F O R E V E R</text>
                    <text x="540" y="215" fill="#ffffff" font-family="'Playfair Display', serif" font-size="62" font-weight="800" text-anchor="middle" filter="drop-shadow(0 4px 10px rgba(0,0,0,0.8))">${coupleName}</text>

                    <!-- Bottom Tag -->
                    <rect x="80" y="1740" width="920" height="75" fill="rgba(0,0,0,0.6)" />
                    <text x="120" y="1788" fill="#ffffff" font-family="'Montserrat', sans-serif" font-size="26" font-weight="700" letter-spacing="2">${dateStr}</text>
                    <text x="960" y="1788" fill="#10b981" font-family="'Montserrat', sans-serif" font-size="24" font-weight="800" text-anchor="end">BUYLE.ID</text>
                `;
            } else if (frameType === 'polaroid_retro') {
                return `
                    <!-- Frame 4: Polaroid Retro Strip -->
                    <!-- Solid Polaroid Frame border -->
                    <rect x="0" y="0" width="1080" height="1920" fill="none" stroke="#ffffff" stroke-width="60" />
                    <!-- Bottom White Polaroid Footer -->
                    <rect x="0" y="1560" width="1080" height="360" fill="#ffffff" />

                    <!-- Handwriting & Couple Tag in Black -->
                    <text x="540" y="1670" fill="#0f172a" font-family="'Playfair Display', serif" font-size="64" font-weight="800" text-anchor="middle">${coupleName}</text>
                    <text x="540" y="1740" fill="#475569" font-family="'Montserrat', sans-serif" font-size="30" font-weight="600" letter-spacing="4" text-anchor="middle">${dateStr}</text>

                    <line x1="280" y1="1780" x2="800" y2="1780" stroke="#cbd5e1" stroke-width="2" />
                    <text x="540" y="1840" fill="#94a3b8" font-family="'Montserrat', sans-serif" font-size="22" font-weight="700" letter-spacing="6" text-anchor="middle">INSTASTORY PHOTOBOOTH</text>
                `;
            }
            return '';
        }

        // Ganti Frame Aktif
        function selectFrame(frameType, btnEl) {
            selectedFrame = frameType;
            document.querySelectorAll('.frame-pill').forEach(el => el.classList.remove('active'));
            if (btnEl) btnEl.classList.add('active');

            const coupleTitle = `${WEDDING_DATA.groom} & ${WEDDING_DATA.bride}`;
            liveFrameSvg.innerHTML = getFrameSvgContent(selectedFrame, coupleTitle, WEDDING_DATA.date);
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

            // Shutter Beep via Web Audio API (Zero asset lag)
            playShutterSound();

            // Render ke canvas
            renderFinalSnapshot(videoEl, false);
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

        // ── Render Foto + Frame ke Canvas 1080x1920 (9:16 InstaStory) ──────────────────
        function renderFinalSnapshot(sourceMedia, isStaticImage) {
            const ctx = resultCanvas.getContext('2d');
            ctx.clearRect(0, 0, 1080, 1920);

            // 1. Gambar Video/Foto kamera ke Canvas 9:16 (Center Crop Cover)
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
            // Jika kamera depan, mirror horizontal
            if (!isStaticImage && currentFacingMode === 'user') {
                ctx.translate(cw, 0);
                ctx.scale(-1, 1);
            }
            ctx.drawImage(sourceMedia, offsetX, offsetY, renderW, renderH, 0, 0, cw, ch);
            ctx.restore();

            // 2. Overlay SVG Frame ke Canvas
            const coupleTitle = `${WEDDING_DATA.groom} & ${WEDDING_DATA.bride}`;
            const svgData = `
                <svg xmlns="http://www.w3.org/2000/svg" width="1080" height="1920" viewBox="0 0 1080 1920">
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Montserrat:wght@500;700;800&family=Playfair+Display:wght@700;800&display=swap');
                    </style>
                    ${getFrameSvgContent(selectedFrame, coupleTitle, WEDDING_DATA.date)}
                </svg>
            `;

            const img = new Image();
            const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(svgBlob);

            img.onload = function() {
                ctx.drawImage(img, 0, 0, 1080, 1920);
                URL.revokeObjectURL(url);
                resultModal.style.display = 'flex';
            };
            img.src = url;
        }

        // Retake / Foto Ulang
        function retakePhoto() {
            resultModal.style.display = 'none';
            if (!stream && !uploadedImageObj) {
                startCamera();
            }
        }

        // Unduh Foto 9:16
        function downloadPhoto() {
            const link = document.createElement('a');
            link.download = `Photobooth_${WEDDING_DATA.slug}_${Date.now()}.jpg`;
            link.href = resultCanvas.toDataURL('image/jpeg', 0.95);
            link.click();
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
                    } catch (err) {
                        if (err.name !== 'AbortError') {
                            downloadPhoto();
                        }
                    }
                } else {
                    // Fallback jika Web Share API files tidak didukung (e.g. desktop)
                    downloadPhoto();
                    alert('Foto berhasil diunduh ke galeri! Silakan buka Instagram dan pasang foto ini di Story Anda.');
                }
            }, 'image/jpeg', 0.95);
        }

        // Start default
        document.addEventListener('DOMContentLoaded', () => {
            selectFrame('gold_arch', document.querySelector('.frame-pill.active'));
            startCamera();
        });
    </script>
</body>
</html>
