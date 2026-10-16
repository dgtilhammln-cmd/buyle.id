<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gate Scanner · {{ $event->name ?? 'buyle.id' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif; -webkit-tap-highlight-color: transparent; font-weight: 400; }
        
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #ecfdf5;
            --dark: #0f172a;
            --slate: #1e293b;
            --muted: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --card: #ffffff;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #d97706;
        }

        body {
            background-color: var(--bg);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ── Top Navbar ── */
        .gate-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 1rem;
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .brand-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .brand-logo {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #10b981, #047857);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }
        .brand-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -0.02em;
        }
        .brand-title span { color: var(--primary); }

        .gate-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #f1f5f9;
            color: #334155;
            padding: 0.3rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 700;
            border: 1px solid #cbd5e1;
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.25);
            animation: pulse-ring 1.8s infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        /* ── Event Header ── */
        .event-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 1rem;
        }
        .event-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        .event-meta {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
            font-size: 0.8rem;
            color: var(--muted);
        }
        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* ── Progress & Stats ── */
        .stats-bar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 1rem;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            text-align: center;
        }
        .stat-box {
            background: #f8fafc;
            border-radius: 0.75rem;
            padding: 0.5rem 0.25rem;
            border: 1px solid var(--border);
        }
        .stat-val {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
        }
        .stat-val.checked { color: var(--primary); }
        .stat-val.remain { color: #f59e0b; }
        .stat-lbl {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-top: 2px;
        }

        /* ── Main Layout ── */
        .container {
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .grid-view {
                display: grid;
                grid-template-columns: 1.15fr 0.85fr;
                gap: 1.25rem;
                align-items: start;
            }
        }

        /* ── Camera Viewport Card ── */
        .scanner-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            position: relative;
        }
        .card-header {
            padding: 0.85rem 1rem;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .card-header h2 {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .viewport-box {
            position: relative;
            background: #090d16;
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        #gate-reader {
            width: 100% !important;
            border: none !important;
        }
        #gate-reader video {
            width: 100% !important;
            height: auto !important;
            max-height: 480px !important;
            object-fit: contain !important;
            display: block !important;
            margin: 0 auto !important;
            transition: transform 0.2s ease;
        }
        .video-mirrored video {
            transform: scaleX(-1) !important;
        }

        /* Controls bar */
        .viewport-controls {
            padding: 0.75rem 1rem;
            background: #ffffff;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .ctrl-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 0.85rem;
            border-radius: 0.65rem;
            font-size: 0.78rem;
            font-weight: 700;
            border: 1px solid var(--border);
            background: #f8fafc;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .ctrl-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .ctrl-btn.active {
            background: var(--primary-light);
            border-color: #86efac;
            color: #166534;
        }

        /* ── Result & Verification Card ── */
        .result-panel {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (max-width: 767px) {
            /* Khusus Mobile: info hasil scan ditampilkan sebagai popup modal, bukan di bawah kamera */
            .result-panel {
                display: none !important;
            }
        }
        
        .result-banner {
            border-radius: 1rem;
            padding: 1.25rem;
            text-align: center;
            transition: all 0.3s ease;
            display: none;
        }
        .result-banner.show { display: block; animation: popIn 0.25s ease-out; }

        @keyframes popIn {
            0% { transform: scale(0.96); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .result-banner.status-valid {
            background: #f0fdf4;
            border: 2px solid #22c55e;
            color: #15803d;
        }
        .result-banner.status-used {
            background: #fffbeb;
            border: 2px solid #f59e0b;
            color: #b45309;
        }
        .result-banner.status-invalid {
            background: #fef2f2;
            border: 2px solid #ef4444;
            color: #b91c1c;
        }

        .result-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.65rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .status-valid .result-icon { background: #22c55e; color: white; }
        .status-used .result-icon { background: #f59e0b; color: white; }
        .status-invalid .result-icon { background: #ef4444; color: white; }

        .result-title { font-size: 1.25rem; font-weight: 800; margin-bottom: 0.25rem; }
        .result-msg { font-size: 0.88rem; line-height: 1.4; opacity: 0.95; }

        .ticket-info-card {
            background: #f8fafc;
            border-radius: 0.85rem;
            border: 1px solid var(--border);
            padding: 0.85rem;
            margin-top: 0.75rem;
            text-align: left;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.35rem 0;
            border-bottom: 1px dashed var(--border);
            font-size: 0.82rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: var(--muted); font-weight: 600; }
        .info-val { font-weight: 700; color: var(--dark); text-align: right; }

        /* Idle State */
        .idle-state {
            padding: 2.5rem 1rem;
            text-align: center;
            color: var(--muted);
        }
        .idle-icon {
            width: 54px;
            height: 54px;
            border-radius: 1rem;
            background: #f1f5f9;
            color: #94a3b8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        /* ── Manual Code Input ── */
        .manual-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #0f172a;
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            border: none;
            width: 100%;
            transition: all 0.15s ease;
        }
        .manual-btn:hover { background: #1e293b; }

        /* ── Modal Manual ── */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            z-index: 100;
        }
        .modal-overlay.open { display: flex; animation: fadeIn 0.2s ease; }
        .modal-box {
            background: #ffffff;
            border-radius: 1.25rem;
            max-width: 440px;
            width: 100%;
            padding: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .modal-header h3 { font-size: 1.1rem; font-weight: 800; color: var(--dark); }
        .btn-close {
            background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0.25rem;
        }
        .input-code {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 2px solid var(--border);
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            outline: none;
            transition: border-color 0.2s;
        }
        .input-code:focus { border-color: var(--primary); }

        .btn-verify-manual {
            margin-top: 1rem;
            background: var(--primary);
            color: white;
            padding: 0.85rem;
            border-radius: 0.75rem;
            font-weight: 800;
            font-size: 0.95rem;
            border: none;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        }
        .btn-verify-manual:hover { background: var(--primary-dark); }
    </style>
</head>
<body>
    @if($requiresPin)
    <!-- PIN Protection Lock Screen Overlay -->
    <div id="gate-pin-overlay" style="position:fixed; inset:0; z-index:99999; background:rgba(15,23,42,0.94); backdrop-filter:blur(10px); display:flex; align-items:center; justify-content:center; padding:1.25rem;">
        <div style="background:#ffffff; border-radius:1.5rem; max-width:400px; width:100%; padding:2rem; box-shadow:0 25px 50px -12px rgba(0,0,0,0.5); text-align:center;">
            <div style="width:60px; height:60px; border-radius:50%; background:#ecfdf5; border:2px solid #a7f3d0; color:#10b981; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <h2 style="font-size:1.2rem; font-weight:800; color:#0f172a; margin-bottom:0.35rem;">Akses Gate Dilindungi PIN</h2>
            <p style="font-size:0.82rem; color:#64748b; margin-bottom:1.25rem; line-height:1.5;">
                Masukkan PIN akses yang diberikan oleh penyelenggara event <strong>{{ $event->name }}</strong>.
            </p>
            <div id="pin-error-msg" style="display:none; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.6rem 0.85rem; border-radius:0.65rem; font-size:0.8rem; font-weight:700; margin-bottom:1rem;"></div>
            <form onsubmit="submitGatePin(event)">
                <input type="password" id="gate-pin-input" placeholder="Masukkan PIN Gate..." autofocus autocomplete="off" style="width:100%; box-sizing:border-box; padding:0.85rem 1rem; border:2px solid #e2e8f0; border-radius:0.75rem; font-size:1.15rem; font-weight:800; text-align:center; letter-spacing:0.2em; outline:none; transition:border-color 0.2s; margin-bottom:1.1rem;">
                <button type="submit" id="btn-submit-pin" style="width:100%; padding:0.85rem; background:linear-gradient(135deg, #10b981, #047857); color:white; border:none; border-radius:0.75rem; font-size:0.95rem; font-weight:800; cursor:pointer; box-shadow:0 4px 14px rgba(16,185,129,0.35); transition:all 0.15s;">
                    Buka Scanner
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Staff PIN Login Screen Overlay -->
    <div id="gate-staff-overlay" style="position:fixed; inset:0; z-index:99998; background:rgba(15,23,42,0.94); backdrop-filter:blur(10px); display:{{ $requiresStaffLogin && !$requiresPin ? 'flex' : 'none' }}; align-items:center; justify-content:center; padding:1.25rem;">
        <div style="background:#ffffff; border-radius:1.5rem; max-width:400px; width:100%; padding:2rem; box-shadow:0 25px 50px -12px rgba(0,0,0,0.5); text-align:center;">
            <div style="width:60px; height:60px; border-radius:50%; background:#ecfdf5; border:2px solid #a7f3d0; color:#10b981; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <h2 style="font-size:1.2rem; font-weight:800; color:#0f172a; margin-bottom:0.35rem;">Masuk sebagai Panitia Gate</h2>
            <p style="font-size:0.82rem; color:#64748b; margin-bottom:1.25rem; line-height:1.5;">
                Pilih nama Anda dan masukkan PIN 6 digit panitia untuk mencatat atribusi scan di gate.
            </p>
            <div id="staff-error-msg" style="display:none; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.6rem 0.85rem; border-radius:0.65rem; font-size:0.8rem; font-weight:700; margin-bottom:1rem;"></div>
            <form onsubmit="submitStaffAuth(event)">
                <div style="margin-bottom:1rem; text-align:left;">
                    <label style="font-size:0.75rem; font-weight:700; color:#334155; display:block; margin-bottom:0.35rem;">Nama Panitia:</label>
                    <select id="staff-select" required style="width:100%; box-sizing:border-box; padding:0.75rem 0.85rem; border:2px solid #e2e8f0; border-radius:0.75rem; font-size:0.88rem; font-weight:600; outline:none; background:#fff;">
                        <option value="">-- Pilih Nama Panitia --</option>
                        @foreach($activeStaffList as $stf)
                            <option value="{{ $stf->id }}">Gate {{ $stf->gate_number }} — {{ $stf->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:1.25rem; text-align:left;">
                    <label style="font-size:0.75rem; font-weight:700; color:#334155; display:block; margin-bottom:0.35rem;">PIN Panitia (6 Digit):</label>
                    <input type="password" id="staff-pin-input" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" required placeholder="Contoh: 123456" autocomplete="off" style="width:100%; box-sizing:border-box; padding:0.85rem 1rem; border:2px solid #e2e8f0; border-radius:0.75rem; font-size:1.15rem; font-weight:800; text-align:center; letter-spacing:0.25em; outline:none; transition:border-color 0.2s;">
                </div>
                <button type="submit" id="btn-submit-staff" style="width:100%; padding:0.85rem; background:linear-gradient(135deg, #10b981, #047857); color:white; border:none; border-radius:0.75rem; font-size:0.95rem; font-weight:800; cursor:pointer; box-shadow:0 4px 14px rgba(16,185,129,0.35); transition:all 0.15s;">
                    Masuk ke Gate
                </button>
            </form>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="gate-nav">
        <div class="brand-badge">
            <div class="brand-logo">B</div>
            <div class="brand-title">buyle<span>.id</span></div>
        </div>
        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
            @if($staffSession)
                <div id="staffActiveBadge" style="display:inline-flex; align-items:center; gap:0.35rem; background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:0.25rem 0.6rem; border-radius:999px; font-size:0.72rem; font-weight:700;">
                    <span style="width:6px; height:6px; background:#10b981; border-radius:50%;"></span>
                    <span>{{ $staffSession['name'] }} (Gate {{ $staffSession['gate'] }})</span>
                    <button type="button" onclick="logoutStaff()" title="Keluar / Ganti Panitia" style="background:none; border:none; cursor:pointer; color:#059669; font-size:0.72rem; font-weight:800; padding-left:4px; line-height:1;">✕</button>
                </div>
            @endif
            <div class="gate-pill">
                <span class="pulse-dot"></span>
                <span>Gate Live</span>
            </div>
        </div>
    </nav>

    <!-- Event Info Header -->
    <section class="event-header">
        <div style="max-width: 900px; margin: 0 auto;">
            <h1 class="event-title">{{ $event->name }}</h1>
            <div class="event-meta">
                @if($event->event_date)
                    <div class="meta-item">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span>{{ $event->event_date->format('d M Y') }}</span>
                    </div>
                @endif
                @if($event->event_time)
                    <div class="meta-item">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <span>{{ $event->event_time }}</span>
                    </div>
                @endif
                @if($event->event_location)
                    <div class="meta-item">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span>{{ $event->event_location }}</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Live Stats Bar -->
    <section class="stats-bar">
        <div class="stat-box">
            <div class="stat-val checked" id="stat-checked">{{ $stats['checked_in'] }}</div>
            <div class="stat-lbl">Hadir ({{ $stats['rate'] }}%)</div>
        </div>
        <div class="stat-box">
            <div class="stat-val remain" id="stat-remain">{{ $stats['unchecked'] }}</div>
            <div class="stat-lbl">Belum Hadir</div>
        </div>
        <div class="stat-box">
            <div class="stat-val" id="stat-total">{{ $stats['total'] }}</div>
            <div class="stat-lbl">Total Tiket</div>
        </div>
    </section>

    <!-- Main Workspace -->
    <main class="container">
        <div class="grid-view">
            
            <!-- Left Column: Scanner Viewport -->
            <div class="scanner-card">
                <div class="card-header">
                    <h2>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                            <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                            <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                            <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                        </svg>
                        Live QR Scanner
                    </h2>
                    <div style="display:flex; align-items:center; gap:0.4rem; flex-wrap:wrap;">
                        <select id="camera-select" style="font-family:'Montserrat',sans-serif; font-size:0.72rem; font-weight:600; padding:0.28rem 0.45rem; border-radius:0.5rem; border:1px solid #cbd5e1; outline:none; max-width:150px;">
                            <option value="">Pilih Kamera...</option>
                        </select>
                        <button type="button" class="ctrl-btn" id="btn-mirror" onclick="toggleMirror()" style="padding:0.28rem 0.55rem; font-size:0.7rem;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="15 3 21 3 21 9"></polyline>
                                <polyline points="9 21 3 21 3 15"></polyline>
                                <line x1="21" y1="3" x2="14" y2="10"></line>
                                <line x1="3" y1="21" x2="10" y2="14"></line>
                            </svg>
                            <span id="mirror-lbl">Mirror</span>
                        </button>
                        <label style="display:inline-flex; align-items:center; gap:0.3rem; font-size:0.7rem; font-weight:600; color:#166534; cursor:pointer; background:#ecfdf5; border:1px solid #86efac; border-radius:0.5rem; padding:0.28rem 0.55rem;">
                            <input type="checkbox" id="fast-mode" checked style="accent-color:#10b981; width:12px; height:12px;">
                            <span>Fast</span>
                        </label>
                        <button type="button" class="ctrl-btn" onclick="openManualModal()" style="padding:0.28rem 0.55rem; font-size:0.7rem;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Input Manual
                        </button>
                    </div>
                </div>

                <div class="viewport-box" id="viewport-box">
                    <div id="gate-reader"></div>
                </div>
            </div>

            <!-- Right Column: Verification Result Panel -->
            <div class="result-panel">
                
                <!-- Idle Placeholder -->
                <div class="idle-state" id="idle-box">
                    <div class="idle-icon">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <div style="font-weight:600; font-size:0.9rem; color:#334155;">Siap Memindai Tiket</div>
                </div>

                <!-- Dynamic Status Result Banner -->
                <div class="result-banner" id="result-banner">
                    <div class="result-icon" id="banner-icon">✓</div>
                    <h3 class="result-title" id="banner-title">Check-In Berhasil!</h3>
                    <p class="result-msg" id="banner-msg">Selamat datang di event ini.</p>

                    <div class="ticket-info-card" id="ticket-info-box">
                        <div class="info-row">
                            <span class="info-label">Nama Peserta</span>
                            <span class="info-val" id="val-holder">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Kode Tiket</span>
                            <span class="info-val" id="val-code">-</span>
                        </div>
                        <div class="info-row" id="row-nik">
                            <span class="info-label">NIK / Identitas</span>
                            <span class="info-val" id="val-nik">-</span>
                        </div>
                        <div class="info-row" id="row-staff" style="display:none;">
                            <span class="info-label">Discan Oleh</span>
                            <span class="info-val" id="val-staff">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Waktu Scan</span>
                            <span class="info-val" id="val-time">-</span>
                        </div>
                    </div>

                    <button type="button" class="btn-verify-manual" style="margin-top:0.85rem;" onclick="resumeScannerNow()">
                        Scan Berikutnya
                    </button>
                </div>

                <!-- Manual Input Compact Button -->
                <button type="button" onclick="openManualModal()" style="display:inline-flex; align-items:center; gap:0.4rem; background:#f1f5f9; color:#334155; border:1px solid #e2e8f0; border-radius:0.65rem; padding:0.5rem 0.9rem; font-size:0.78rem; font-weight:600; cursor:pointer; font-family:'Montserrat',sans-serif; transition:all 0.15s; width:auto; align-self:flex-start;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Input Manual
                </button>

            </div>

        </div>
    </main>

    <!-- Modal Ketik Manual -->
    <div class="modal-overlay" id="manual-modal" onclick="if(event.target===this) closeManualModal()">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Ketik Manual Kode Tiket</h3>
                <button type="button" class="btn-close" onclick="closeManualModal()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <input type="text" id="manual-code-input" class="input-code" placeholder="TKT-XXXXXX" autofocus>
            <button type="button" class="btn-verify-manual" onclick="submitManualCode()">
                Verifikasi Tiket Sekarang
            </button>
        </div>
    </div>

    <!-- POPUP RESULT MODAL KHUSUS MOBILE (INFO POP UP BUKAN DI BAWAH & TOMBOL KECIL NEXT) -->
    <div id="gateMobileResultModal" onclick="if(event.target===this) closeGateMobileModal()" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.72); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:1rem; opacity:0; transition:all 0.25s ease;">
        <div id="gateMobileResultBox" style="background:#ffffff; border-radius:22px; padding:1.35rem 1.25rem; width:100%; max-width:340px; text-align:center; box-shadow:0 25px 50px -12px rgba(0,0,0,0.35); transform:scale(0.92); transition:all 0.25s cubic-bezier(0.34,1.56,0.64,1);">
            
            <div id="gateMobBubble" style="width:54px; height:54px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:0.85rem;"></div>

            <h3 id="gateMobTitle" style="font-size:1.18rem; font-weight:800; margin:0 0 0.25rem; font-family:'Montserrat',sans-serif;"></h3>
            <p id="gateMobMsg" style="font-size:0.8rem; color:#64748B; margin:0 0 0.85rem; line-height:1.45;"></p>

            <div id="gateMobCard" style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:0.85rem; text-align:left; font-size:0.82rem; margin-bottom:1.15rem; display:none;">
                <div style="margin-bottom:6px; display:flex; justify-content:space-between; align-items:center;">
                    <span style="color:#64748B; font-size:0.78rem;">Peserta</span>
                    <strong id="mob-val-holder" style="color:#0F172A; font-size:0.82rem;">-</strong>
                </div>
                <div style="margin-bottom:6px; display:flex; justify-content:space-between; align-items:center;">
                    <span style="color:#64748B; font-size:0.78rem;">Kode Tiket</span>
                    <code id="mob-val-code" style="color:#166534; font-family:monospace; font-size:0.82rem; background:#f0fdf4; padding:2px 6px; border-radius:4px; border:1px solid #bbf7d0;">-</code>
                </div>
                <div id="mob-row-nik" style="margin-bottom:6px; display:none; justify-content:space-between; align-items:center;">
                    <span style="color:#64748B; font-size:0.78rem;">NIK</span>
                    <span id="mob-val-nik" style="color:#0F172A; font-size:0.8rem; font-weight:600;">-</span>
                </div>
                <div id="mob-row-staff" style="margin-bottom:6px; display:none; justify-content:space-between; align-items:center;">
                    <span style="color:#64748B; font-size:0.78rem;">Discan Oleh</span>
                    <span id="mob-val-staff" style="color:#0F172A; font-size:0.8rem; font-weight:600;">-</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; padding-top:6px; border-top:1px dashed #E2E8F0; margin-top:4px;">
                    <span style="color:#64748B; font-size:0.78rem;">Waktu</span>
                    <strong id="mob-val-time" style="color:#166534; font-size:0.8rem;">-</strong>
                </div>
            </div>

            {{-- Button kecil Next jika scan berhasil --}}
            <div style="display:flex; justify-content:center; align-items:center;">
                <button type="button" id="gateMobNextBtn" onclick="closeGateMobileModal()" style="display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:0.45rem 1.35rem; background:linear-gradient(135deg, #10b981, #059669); color:#fff; border:none; border-radius:999px; font-weight:700; font-size:0.82rem; cursor:pointer; box-shadow:0 3px 12px rgba(16,185,129,0.3); transition:all 0.15s ease;">
                    <span id="gateMobBtnText">Next</span>
                    <svg id="gateMobBtnIcon" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const VERIFY_URL = "{{ route('gate.scanner.verify', ['key' => $key]) }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        // In-Memory Client-side Cache for 0ms crowd scanning
        let localTickets = @json($cachedTickets);

        let html5QrCode = null;
        let isProcessing = false;
        let isMirrored = localStorage.getItem('gate_scanner_mirror') !== 'false'; // default ON for webcam

        // Audio Context Synthesizer (Instant 0ms sound, no mp3 file loading delay)
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

        function playSound(type) {
            try {
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);

                if (type === 'success') {
                    // Harmonious double chime
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.1); // A5
                    gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.35);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.35);
                } else if (type === 'used') {
                    // Warning low buzzer
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(320, audioCtx.currentTime);
                    osc.frequency.setValueAtTime(260, audioCtx.currentTime + 0.15);
                    gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.4);
                } else {
                    // Error buzz
                    osc.type = 'square';
                    osc.frequency.setValueAtTime(180, audioCtx.currentTime);
                    gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.35);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.35);
                }
            } catch(e) {}
        }

        function triggerHaptic(type) {
            if ('vibrate' in navigator) {
                if (type === 'success') navigator.vibrate(100);
                else if (type === 'used') navigator.vibrate([120, 60, 120]);
                else navigator.vibrate([200, 80, 200]);
            }
        }

        // Apply Mirror
        function applyMirror() {
            const vBox = document.getElementById('viewport-box');
            const lbl = document.getElementById('mirror-lbl');
            const btn = document.getElementById('btn-mirror');
            if (isMirrored) {
                vBox.classList.add('video-mirrored');
                lbl.innerText = 'Mirror: ON';
                btn.classList.add('active');
            } else {
                vBox.classList.remove('video-mirrored');
                lbl.innerText = 'Mirror: OFF';
                btn.classList.remove('active');
            }
        }

        function toggleMirror() {
            isMirrored = !isMirrored;
            localStorage.setItem('gate_scanner_mirror', isMirrored);
            applyMirror();
        }

        // Camera init
        async function initScanner() {
            applyMirror();
            const cameraSelect = document.getElementById('camera-select');

            try {
                const devices = await Html5Qrcode.getCameras();
                if (!devices || devices.length === 0) {
                    alert('Tidak ada kamera yang ditemukan pada perangkat ini.');
                    return;
                }

                cameraSelect.innerHTML = '';
                devices.forEach((dev, idx) => {
                    const opt = document.createElement('option');
                    opt.value = dev.id;
                    opt.text = dev.label || `Kamera ${idx + 1}`;
                    cameraSelect.appendChild(opt);
                });

                // Pick back camera by default on mobile, or first camera on desktop
                let preferredCamId = devices[0].id;
                const backCam = devices.find(d => /back|rear|environment/i.test(d.label));
                if (backCam) {
                    preferredCamId = backCam.id;
                    cameraSelect.value = preferredCamId;
                    // Auto turn off mirror on back cam
                    isMirrored = false;
                    applyMirror();
                }

                cameraSelect.addEventListener('change', () => {
                    startCamera(cameraSelect.value);
                });

                startCamera(preferredCamId);

            } catch (err) {
                console.error('Camera init error:', err);
            }
        }

        let gateJsQrInterval = null;

        async function startCamera(cameraId) {
            if (gateJsQrInterval) {
                clearInterval(gateJsQrInterval);
                gateJsQrInterval = null;
            }

            if (html5QrCode) {
                try {
                    await html5QrCode.stop();
                } catch(e) {}
            }

            html5QrCode = new Html5Qrcode("gate-reader");
            const config = {
                fps: 10,
                disableFlip: false,
                videoConstraints: {
                    facingMode: "environment",
                    width: { ideal: 1280, min: 640 },
                    height: { ideal: 720, min: 480 },
                }
            };

            html5QrCode.start(
                cameraId,
                config,
                onScanSuccess,
                () => {} // silent on frame skip
            ).then(() => {
                startGateJsQrAssistLoop();
            }).catch(err => {
                console.error("Camera start failed:", err);
            });
        }

        function startGateJsQrAssistLoop() {
            if (gateJsQrInterval) clearInterval(gateJsQrInterval);
            let gCanvas = null;
            let gCtx = null;

            gateJsQrInterval = setInterval(() => {
                if (isProcessing) return;
                if (typeof window.jsQR !== 'function') return;

                const video = document.querySelector('#gate-reader video');
                if (!video || video.readyState < 2 || video.paused || video.ended) return;

                const vw = video.videoWidth;
                const vh = video.videoHeight;
                if (!vw || !vh || vw < 50 || vh < 50) return;

                if (!gCanvas) {
                    gCanvas = document.createElement('canvas');
                    gCtx = gCanvas.getContext('2d', { willReadFrequently: true });
                }

                gCanvas.width = vw;
                gCanvas.height = vh;
                gCtx.drawImage(video, 0, 0, vw, vh);

                try {
                    const imgData = gCtx.getImageData(0, 0, vw, vh);
                    const code = window.jsQR(imgData.data, vw, vh, {
                        inversionAttempts: "attemptBoth"
                    });
                    if (code && code.data && !isProcessing) {
                        onScanSuccess(code.data);
                    }
                } catch(e) {}
            }, 120);
        }

        // Handle QR Scan
        async function onScanSuccess(decodedText) {
            if (isProcessing) return;
            isProcessing = true;
            await processVerification(decodedText);
        }

        let currentGatePin = '';
        const REQUIRES_PIN = {{ $requiresPin ? 'true' : 'false' }};
        const REQUIRES_STAFF_LOGIN = {{ $requiresStaffLogin ? 'true' : 'false' }};
        const AUTH_PIN_URL = "{{ route('gate.scanner.auth-pin', ['key' => $key]) }}";
        const STAFF_AUTH_URL = "{{ route('gate.scanner.staff-auth', ['key' => $key]) }}";
        const STAFF_LOGOUT_URL = "{{ route('gate.scanner.staff-logout', ['key' => $key]) }}";

        // Progressive PIN lockout state (persisted in sessionStorage)
        const LOCKOUT_KEY = 'gate_pin_lockout_{{ $key }}';

        function getPinState() {
            try { return JSON.parse(sessionStorage.getItem(LOCKOUT_KEY)) || { attempts: 0, lockedUntil: 0 }; }
            catch(e) { return { attempts: 0, lockedUntil: 0 }; }
        }
        function savePinState(s) { sessionStorage.setItem(LOCKOUT_KEY, JSON.stringify(s)); }

        function getLockoutDuration(attempts) {
            if (attempts >= 7) return 300;  // 5 menit setelah 7+ salah
            if (attempts >= 5) return 60;   // 1 menit setelah 5-6 salah
            if (attempts >= 3) return 30;   // 30 detik setelah 3-4 salah
            return 0;
        }

        function formatLockCountdown(secs) {
            if (secs >= 60) return `${Math.ceil(secs/60)} menit`;
            return `${secs} detik`;
        }

        let lockoutTimer = null;

        function checkAndApplyLockout() {
            const state = getPinState();
            const now = Math.floor(Date.now() / 1000);
            const remaining = state.lockedUntil - now;
            if (remaining > 0) {
                applyLockout(remaining, state.attempts);
                return true;
            }
            return false;
        }

        function applyLockout(remainingSecs, attempts) {
            const pinInput = document.getElementById('gate-pin-input');
            const submitBtn = document.getElementById('btn-submit-pin');
            const errMsg = document.getElementById('pin-error-msg');

            pinInput.disabled = true;
            submitBtn.disabled = true;

            let secs = remainingSecs;
            errMsg.style.display = 'block';

            function tick() {
                const attemptsText = attempts >= 7 ? 'Terlalu banyak percobaan' : `${attempts}x salah`;
                errMsg.innerText = `${attemptsText}. Coba lagi dalam ${formatLockCountdown(secs)}.`;
                if (secs <= 0) {
                    pinInput.disabled = false;
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Buka Scanner';
                    errMsg.innerText = 'Silakan coba lagi.';
                    pinInput.focus();
                    clearInterval(lockoutTimer);
                } else {
                    secs--;
                }
            }
            tick();
            clearInterval(lockoutTimer);
            lockoutTimer = setInterval(tick, 1000);
        }

        async function submitGatePin(e) {
            if (e) e.preventDefault();
            const pinInput = document.getElementById('gate-pin-input');
            const pin = pinInput.value.trim();
            const errMsg = document.getElementById('pin-error-msg');
            const submitBtn = document.getElementById('btn-submit-pin');

            if (!pin) {
                errMsg.innerText = 'Silakan ketik PIN Gate.';
                errMsg.style.display = 'block';
                return;
            }

            // Check active lockout before attempting
            if (checkAndApplyLockout()) return;

            submitBtn.disabled = true;
            submitBtn.innerText = 'Memverifikasi...';

            try {
                const res = await fetch(AUTH_PIN_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ pin: pin })
                });
                const data = await res.json();
                if (data.success) {
                    // Clear lockout on success
                    sessionStorage.removeItem(LOCKOUT_KEY);
                    currentGatePin = pin;
                    const overlay = document.getElementById('gate-pin-overlay');
                    if (overlay) overlay.style.display = 'none';
                    if (REQUIRES_STAFF_LOGIN) {
                        const staffOverlay = document.getElementById('gate-staff-overlay');
                        if (staffOverlay) staffOverlay.style.display = 'flex';
                    } else {
                        initScanner();
                    }
                } else {
                    const state = getPinState();
                    state.attempts++;
                    const lockDuration = getLockoutDuration(state.attempts);
                    if (lockDuration > 0) {
                        state.lockedUntil = Math.floor(Date.now() / 1000) + lockDuration;
                    }
                    savePinState(state);

                    if (lockDuration > 0) {
                        pinInput.value = '';
                        applyLockout(lockDuration, state.attempts);
                    } else {
                        const remaining = 3 - state.attempts;
                        errMsg.innerText = (data.message || 'PIN yang Anda masukkan salah.') + (remaining > 0 ? ` (${remaining}x lagi sebelum dikunci)` : '');
                        errMsg.style.display = 'block';
                        submitBtn.disabled = false;
                        submitBtn.innerText = 'Buka Scanner';
                        pinInput.select();
                    }
                }
            } catch(err) {
                errMsg.innerText = 'Gagal menghubungi server. Periksa koneksi internet.';
                errMsg.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerText = 'Buka Scanner';
            }
        }

        // Check lockout on page load
        if (REQUIRES_PIN) { checkAndApplyLockout(); }


        async function processVerification(rawCode) {
            const clean = rawCode.trim();
            const headers = {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            };
            if (currentGatePin) {
                headers['X-Gate-Pin'] = currentGatePin;
            }

            try {
                const res = await fetch(VERIFY_URL, {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({ code: clean, gate_pin: currentGatePin })
                });

                const data = await res.json();
                renderResult(data);

            } catch (err) {
                renderResult({
                    status: 'invalid',
                    title: 'Koneksi Terganggu',
                    message: 'Tidak dapat menghubungi server. Periksa koneksi internet.'
                });
            }
        }

        function renderResult(data) {
            const isMobile = window.innerWidth <= 767;

            // KHUSUS MOBILE: Munculkan info hasil scan dalam bentuk POP UP bukan di bawahnya,
            // dan jika scan berhasil, ada button kecil "Next" untuk lanjut scan.
            if (isMobile) {
                openGateMobileModal(data);
                return;
            }

            // DESKTOP: Render side-by-side panel
            const idle = document.getElementById('idle-box');
            const banner = document.getElementById('result-banner');
            const icon = document.getElementById('banner-icon');
            const title = document.getElementById('banner-title');
            const msg = document.getElementById('banner-msg');
            const infoBox = document.getElementById('ticket-info-box');

            idle.style.display = 'none';
            banner.className = `result-banner show status-${data.status}`;

            if (data.status === 'valid') {
                icon.innerHTML = '<svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                title.innerText = data.title || 'Check-In Berhasil!';
                msg.innerText = data.message || 'Tiket valid.';
                infoBox.style.display = 'block';

                const t = data.ticket || {};
                document.getElementById('val-holder').innerText = t.holder_name || '-';
                document.getElementById('val-code').innerText = t.code || '-';
                document.getElementById('val-time').innerText = t.checked_in_at || new Date().toLocaleTimeString();
                
                const nikRow = document.getElementById('row-nik');
                if (t.holder_nik) {
                    nikRow.style.display = 'flex';
                    document.getElementById('val-nik').innerText = t.holder_nik;
                } else {
                    nikRow.style.display = 'none';
                }

                const staffRow = document.getElementById('row-staff');
                if (t.scanned_by) {
                    staffRow.style.display = 'flex';
                    document.getElementById('val-staff').innerText = t.scanned_by;
                } else {
                    staffRow.style.display = 'none';
                }

                playSound('success');
                triggerHaptic('success');
                incrementCounter();

            } else if (data.status === 'used') {
                icon.innerHTML = '<svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
                title.innerText = data.title || 'Sudah Digunakan';
                msg.innerText = data.message || 'Tiket sudah dipindai sebelumnya.';
                infoBox.style.display = 'block';

                const t = data.ticket || {};
                document.getElementById('val-holder').innerText = t.holder_name || '-';
                document.getElementById('val-code').innerText = t.code || '-';
                document.getElementById('val-time').innerText = t.checked_in_at || '-';
                document.getElementById('row-nik').style.display = 'none';

                playSound('used');
                triggerHaptic('used');

            } else {
                icon.innerHTML = '<svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
                title.innerText = data.title || 'Tiket Tidak Valid';
                msg.innerText = data.message || 'Tiket tidak ditemukan.';
                infoBox.style.display = 'none';

                playSound('invalid');
                triggerHaptic('invalid');
            }

            // Fast auto resume after delay on desktop
            const isFast = document.getElementById('fast-mode').checked;
            const delay = data.status === 'valid' ? (isFast ? 800 : 1800) : 2200;

            setTimeout(() => {
                resumeScannerNow();
            }, delay);
        }

        function openGateMobileModal(data) {
            const overlay = document.getElementById('gateMobileResultModal');
            const box     = document.getElementById('gateMobileResultBox');
            const bubble  = document.getElementById('gateMobBubble');
            const title   = document.getElementById('gateMobTitle');
            const msg     = document.getElementById('gateMobMsg');
            const card    = document.getElementById('gateMobCard');
            const nextBtn = document.getElementById('gateMobNextBtn');
            const btnText = document.getElementById('gateMobBtnText');
            const btnIcon = document.getElementById('gateMobBtnIcon');

            const isValid = (data.status === 'valid');

            if (isValid) {
                bubble.style.background = '#f0fdf4';
                bubble.style.border = '1.5px solid #bbf7d0';
                bubble.innerHTML = '<svg width="30" height="30" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
                title.style.color = '#15803d';
                title.innerText = data.title || 'Check-In Berhasil!';
                msg.innerText = data.message || 'Tiket valid.';

                // Button kecil Next khusus scan berhasil di mobile
                if (nextBtn) {
                    nextBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                    nextBtn.style.boxShadow = '0 3px 12px rgba(16,185,129,0.3)';
                }
                if (btnText) btnText.innerText = 'Next';
                if (btnIcon) btnIcon.style.display = 'inline-block';

                playSound('success');
                triggerHaptic('success');
                incrementCounter();
            } else if (data.status === 'used') {
                bubble.style.background = '#fffbeb';
                bubble.style.border = '1.5px solid #fde68a';
                bubble.innerHTML = '<svg width="30" height="30" fill="none" stroke="#d97706" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
                title.style.color = '#b45309';
                title.innerText = data.title || 'Sudah Digunakan';
                msg.innerText = data.message || 'Tiket sudah dipindai sebelumnya.';

                if (nextBtn) {
                    nextBtn.style.background = '#0f172a';
                    nextBtn.style.boxShadow = 'none';
                }
                if (btnText) btnText.innerText = 'Tutup';
                if (btnIcon) btnIcon.style.display = 'none';

                playSound('used');
                triggerHaptic('used');
            } else {
                bubble.style.background = '#fef2f2';
                bubble.style.border = '1.5px solid #fecaca';
                bubble.innerHTML = '<svg width="30" height="30" fill="none" stroke="#dc2626" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
                title.style.color = '#b91c1c';
                title.innerText = data.title || 'Tiket Tidak Valid';
                msg.innerText = data.message || 'Tiket tidak ditemukan.';

                if (nextBtn) {
                    nextBtn.style.background = '#0f172a';
                    nextBtn.style.boxShadow = 'none';
                }
                if (btnText) btnText.innerText = 'Tutup';
                if (btnIcon) btnIcon.style.display = 'none';

                playSound('invalid');
                triggerHaptic('invalid');
            }

            const t = data.ticket;
            if (t) {
                card.style.display = 'block';
                document.getElementById('mob-val-holder').innerText = t.holder_name || '-';
                document.getElementById('mob-val-code').innerText = t.code || '-';
                document.getElementById('mob-val-time').innerText = t.checked_in_at || new Date().toLocaleTimeString();

                const nikRow = document.getElementById('mob-row-nik');
                if (t.holder_nik) {
                    nikRow.style.display = 'flex';
                    document.getElementById('mob-val-nik').innerText = t.holder_nik;
                } else {
                    nikRow.style.display = 'none';
                }

                const mobStaffRow = document.getElementById('mob-row-staff');
                if (t.scanned_by) {
                    mobStaffRow.style.display = 'flex';
                    document.getElementById('mob-val-staff').innerText = t.scanned_by;
                } else {
                    mobStaffRow.style.display = 'none';
                }
            } else {
                card.style.display = 'none';
            }

            overlay.style.display = 'flex';
            setTimeout(() => {
                overlay.style.opacity = '1';
                box.style.transform   = 'scale(1)';
            }, 20);
        }

        function closeGateMobileModal() {
            const overlay = document.getElementById('gateMobileResultModal');
            const box     = document.getElementById('gateMobileResultBox');

            if (overlay && box) {
                overlay.style.opacity = '0';
                box.style.transform   = 'scale(0.92)';
                setTimeout(() => {
                    overlay.style.display = 'none';
                    resumeScannerNow();
                }, 200);
            } else {
                resumeScannerNow();
            }
        }

        function resumeScannerNow() {
            isProcessing = false;
        }

        function incrementCounter() {
            const elChecked = document.getElementById('stat-checked');
            const elRemain = document.getElementById('stat-remain');
            const elTotal = document.getElementById('stat-total');

            let checked = parseInt(elChecked.innerText) || 0;
            let remain = parseInt(elRemain.innerText) || 0;
            let total = parseInt(elTotal.innerText) || 0;

            checked++;
            remain = Math.max(0, remain - 1);
            elChecked.innerText = checked;
            elRemain.innerText = remain;
        }

        // Manual modal
        function openManualModal() {
            document.getElementById('manual-modal').classList.add('open');
            setTimeout(() => {
                document.getElementById('manual-code-input').focus();
            }, 100);
        }

        function closeManualModal() {
            document.getElementById('manual-modal').classList.remove('open');
            document.getElementById('manual-code-input').value = '';
        }

        function submitManualCode() {
            const val = document.getElementById('manual-code-input').value.trim();
            if (!val) return;
            closeManualModal();
            processVerification(val);
        }

        // Enter key in manual input
        document.getElementById('manual-code-input').addEventListener('keydown', (e) => {
            if (e.key === 'Enter') submitManualCode();
        });

        async function submitStaffAuth(e) {
            if (e) e.preventDefault();
            const staffId = document.getElementById('staff-select').value;
            const pin = document.getElementById('staff-pin-input').value.trim();
            const errMsg = document.getElementById('staff-error-msg');
            const submitBtn = document.getElementById('btn-submit-staff');

            if (!staffId || !pin) {
                errMsg.innerText = 'Pilih nama panitia dan ketik PIN 6 digit.';
                errMsg.style.display = 'block';
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerText = 'Memverifikasi...';

            try {
                const res = await fetch(STAFF_AUTH_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ staff_id: staffId, pin: pin })
                });
                const data = await res.json();
                if (data.success) {
                    location.reload();
                } else {
                    errMsg.innerText = data.message || 'PIN staff salah.';
                    errMsg.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Masuk ke Gate';
                    document.getElementById('staff-pin-input').select();
                }
            } catch(err) {
                errMsg.innerText = 'Gagal menghubungi server.';
                errMsg.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerText = 'Masuk ke Gate';
            }
        }

        async function logoutStaff() {
            if (!confirm('Keluar dari sesi panitia ini?')) return;
            try {
                await fetch(STAFF_LOGOUT_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    }
                });
                location.reload();
            } catch(e) {
                location.reload();
            }
        }

        // Initialize on DOM load if no PIN / Staff required
        document.addEventListener('DOMContentLoaded', () => {
            if (!REQUIRES_PIN) {
                if (REQUIRES_STAFF_LOGIN) {
                    const staffOverlay = document.getElementById('gate-staff-overlay');
                    if (staffOverlay) staffOverlay.style.display = 'flex';
                } else {
                    initScanner();
                }
            }
        });
    </script>
</body>
</html>
