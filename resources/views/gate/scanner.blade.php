<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gate Scanner · {{ $event->name ?? 'buyle.id' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --primary: #1eb349;
            --primary-dark: #16a34a;
            --primary-light: #f0fdf4;
            --primary-border: #bbf7d0;
            --dark: #0f172a;
            --slate: #1e293b;
            --muted: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --card-radius: 20px;
        }

        body {
            background-color: var(--bg);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            font-size: 14px;
        }

        /* ── Top Navbar ── */
        .gate-nav {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            padding: 0.85rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .brand-badge {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
        }
        .brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1eb349, #15803d);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.15rem;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3);
        }
        .brand-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -0.03em;
        }
        .brand-title span { color: var(--primary); }

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

        /* ── Main Container ── */
        .main-container {
            max-width: 1140px;
            width: 100%;
            margin: 0 auto;
            padding: 1.25rem 1.25rem 2.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* ── Event Header ── */
        .event-header {
            text-align: center;
            padding: 0.5rem 0 0.25rem;
        }
        .event-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.35rem;
            letter-spacing: -0.02em;
        }
        .event-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.25rem;
            flex-wrap: wrap;
            font-size: 0.83rem;
            color: var(--muted);
            font-weight: 600;
        }
        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* ── Stats Bar (Curved Cards) ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        .stat-card {
            background: #ffffff;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 0.9rem 1rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
        }
        .stat-num {
            font-size: 1.45rem;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 2px;
        }
        .stat-num.hadir { color: #16a34a; }
        .stat-num.belum { color: #f59e0b; }
        .stat-num.total { color: var(--dark); }
        .stat-label {
            font-size: 0.72rem;
            font-weight: 800;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── Scanner Outer Card (Image 2 Style) ── */
        .prof-card {
            background: #ffffff;
            border: 1.5px solid var(--border);
            border-radius: var(--card-radius);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .prof-card-head {
            padding: 1.15rem 1.5rem;
            background: #ffffff;
            border-bottom: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .prof-card-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--dark);
        }
        .form-body {
            padding: 1.5rem;
        }

        /* ── Input Manual Top Bar ── */
        .form-input-code {
            height: 46px;
            padding: 0 1rem;
            border: 1.5px solid var(--border);
            border-radius: 12px 0 0 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            color: #0f172a;
            background: #ffffff;
            outline: none;
            flex: 1;
            min-width: 0;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .form-input-code:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
        }
        .btn-submit-scan {
            height: 46px;
            padding: 0 1.5rem;
            border-radius: 0 12px 12px 0;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            border: none;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.88rem;
            font-weight: 800;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.25);
        }
        .btn-submit-scan:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(30, 179, 73, 0.35);
        }

        /* ── 2-Column Responsive Workspace Grid ── */
        .scanner-workspace-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 1.5rem;
            align-items: stretch;
            margin-top: 0.5rem;
        }
        @media (max-width: 991px) {
            .scanner-workspace-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
        }

        .scanner-camera-col {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .scanner-data-col {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* ── Real-time Guidance Banner ── */
        .smart-guide-banner {
            margin-bottom: 0.75rem;
            padding: 0.6rem 0.9rem;
            border-radius: 12px;
            font-size: 0.78rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            transition: all 0.25s ease;
            border: 1.5px solid var(--border);
            background: #ffffff;
            color: #475569;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        /* ── Camera Viewport Container (#reader) ── */
        #reader {
            width: 100% !important;
            max-width: 100% !important;
            border-radius: 18px !important;
            overflow: hidden !important;
            border: 2px dashed #000000 !important;
            background: #0f172a !important;
            padding: 0.65rem !important;
            text-align: center;
            margin: 0 !important;
            box-sizing: border-box !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        #reader video {
            width: 100% !important;
            height: auto !important;
            max-height: 420px !important;
            object-fit: contain !important;
            border-radius: 14px !important;
            display: block !important;
            margin: 0 auto !important;
            transition: transform 0.2s ease;
        }
        #reader.mirror-on video {
            transform: scaleX(-1) !important;
        }
        #reader__scan_region {
            border-radius: 14px !important;
            overflow: hidden !important;
            background: #020617 !important;
        }
        #reader__dashboard {
            padding: 0.85rem 0.65rem 0.35rem !important;
            background: #ffffff !important;
            border-radius: 16px !important;
            margin-top: 0.65rem !important;
            border: 1.5px solid var(--border) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            text-align: center !important;
        }
        #reader__dashboard_section_csr span {
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            color: #334155 !important;
            display: block !important;
            margin-bottom: 0.4rem !important;
        }
        #reader select {
            height: 42px;
            padding: 0 2rem 0 0.85rem;
            border-radius: 12px;
            border: 1.5px solid var(--border) !important;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.83rem;
            font-weight: 600;
            color: #0f172a;
            background: #ffffff;
            outline: none;
            margin: 0.35rem 0;
            cursor: pointer;
            transition: all 0.2s;
            max-width: 100% !important;
            width: 100% !important;
            box-sizing: border-box !important;
            display: block;
        }
        #reader select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
        }
        #reader button {
            height: 44px;
            padding: 0 1.75rem !important;
            border-radius: 999px !important;
            background: linear-gradient(135deg, #22c55e, #16a34a) !important;
            border: none !important;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 800;
            color: #ffffff !important;
            cursor: pointer;
            margin: 0.5rem auto 0.25rem !important;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.35) !important;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            text-decoration: none !important;
            max-width: 100% !important;
        }
        #reader button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(34, 197, 94, 0.45) !important;
        }

        /* ── Scanner Result Panel (Right Column) ── */
        .scanner-result-panel {
            background: #ffffff;
            border: 1.5px solid var(--border);
            border-radius: 18px;
            padding: 1.5rem;
            height: 100%;
            min-height: 400px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .scanner-result-panel.status-valid {
            border-color: #86efac;
            background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);
            box-shadow: 0 8px 30px rgba(34, 197, 94, 0.14);
        }
        .scanner-result-panel.status-used {
            border-color: #fde68a;
            background: linear-gradient(180deg, #fffbeb 0%, #ffffff 100%);
            box-shadow: 0 8px 30px rgba(245, 158, 11, 0.14);
        }
        .scanner-result-panel.status-invalid {
            border-color: #fca5a5;
            background: linear-gradient(180deg, #fef2f2 0%, #ffffff 100%);
            box-shadow: 0 8px 30px rgba(239, 68, 68, 0.14);
        }

        /* Result details list */
        .detail-card {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 0.95rem;
            margin-bottom: 1rem;
            text-align: left;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.35rem 0;
            font-size: 0.83rem;
            border-bottom: 1px dashed var(--border);
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: var(--muted); font-weight: 600; font-size: 0.78rem; }
        .detail-val { font-weight: 700; color: var(--dark); text-align: right; }

        /* Animation */
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Mobile Adjustments */
        @media (max-width: 640px) {
            .gate-nav { padding: 0.75rem 1rem; }
            .main-container { padding: 1rem 0.85rem 2rem; gap: 1rem; }
            .form-body { padding: 1rem 0.85rem; }
            .stats-grid { gap: 0.5rem; }
            .stat-card { padding: 0.75rem 0.5rem; }
            .stat-num { font-size: 1.25rem; }
            .event-title { font-size: 1.15rem; }
        }
    </style>
</head>
<body>

    <!-- Gate PIN Screen Overlay (Jika event diproteksi PIN) -->
    @if($requiresPin)
    <div id="gate-pin-overlay" style="position:fixed; inset:0; z-index:99999; background:rgba(15,23,42,0.92); backdrop-filter:blur(10px); display:flex; align-items:center; justify-content:center; padding:1.25rem;">
        <div style="background:#ffffff; border-radius:1.5rem; max-width:380px; width:100%; padding:2rem; box-shadow:0 25px 50px -12px rgba(0,0,0,0.5); text-align:center;">
            <div style="width:58px; height:58px; border-radius:50%; background:#f0fdf4; border:2px solid #bbf7d0; color:#1eb349; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <h2 style="font-size:1.2rem; font-weight:800; color:#0f172a; margin-bottom:0.35rem;">PIN Gate Scanner</h2>
            <p style="font-size:0.82rem; color:#64748b; margin-bottom:1.25rem; line-height:1.5;">
                Scanner ini dilindungi PIN oleh penyelenggara acara. Silakan masukkan PIN untuk memulai.
            </p>
            <div id="pin-error-msg" style="display:none; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.6rem 0.85rem; border-radius:0.65rem; font-size:0.8rem; font-weight:700; margin-bottom:1rem;"></div>
            <form onsubmit="submitGatePin(event)">
                <input type="password" id="gate-pin-input" placeholder="Ketik PIN..." autofocus autocomplete="off" style="width:100%; box-sizing:border-box; padding:0.85rem 1rem; border:2px solid #e2e8f0; border-radius:0.75rem; font-size:1.15rem; font-weight:800; text-align:center; letter-spacing:0.2em; outline:none; transition:border-color 0.2s; margin-bottom:1.1rem;">
                <button type="submit" id="btn-submit-pin" style="width:100%; padding:0.85rem; background:linear-gradient(135deg, #1eb349, #15803d); color:white; border:none; border-radius:0.75rem; font-size:0.95rem; font-weight:800; cursor:pointer; box-shadow:0 4px 14px rgba(30,179,73,0.35); transition:all 0.15s;">
                    Buka Scanner
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Staff PIN Login Screen Overlay -->
    <div id="gate-staff-overlay" style="position:fixed; inset:0; z-index:99998; background:rgba(15,23,42,0.92); backdrop-filter:blur(10px); display:{{ $requiresStaffLogin && !$requiresPin ? 'flex' : 'none' }}; align-items:center; justify-content:center; padding:1.25rem;">
        <div style="background:#ffffff; border-radius:1.5rem; max-width:400px; width:100%; padding:2rem; box-shadow:0 25px 50px -12px rgba(0,0,0,0.5); text-align:center;">
            <div style="width:58px; height:58px; border-radius:50%; background:#f0fdf4; border:2px solid #bbf7d0; color:#1eb349; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <h2 style="font-size:1.2rem; font-weight:800; color:#0f172a; margin-bottom:0.35rem;">Masuk sebagai Panitia Gate</h2>
            <p style="font-size:0.82rem; color:#64748b; margin-bottom:1.25rem; line-height:1.5;">
                Pilih nama Anda dan ketikkan PIN 6 digit panitia untuk verifikasi tiket di gate.
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
                <button type="submit" id="btn-submit-staff" style="width:100%; padding:0.85rem; background:linear-gradient(135deg, #1eb349, #15803d); color:white; border:none; border-radius:0.75rem; font-size:0.95rem; font-weight:800; cursor:pointer; box-shadow:0 4px 14px rgba(30,179,73,0.35); transition:all 0.15s;">
                    Masuk ke Gate
                </button>
            </form>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="gate-nav">
        <a href="{{ url('/') }}" class="brand-badge">
            <div class="brand-logo">B</div>
            <div class="brand-title">buyle<span>.id</span></div>
        </a>
        <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
            @if($staffSession)
                <div id="staffActiveBadge" style="display:inline-flex; align-items:center; gap:0.4rem; background:#f0fdf4; border:1.5px solid #bbf7d0; color:#166534; padding:0.35rem 0.85rem; border-radius:999px; font-size:0.76rem; font-weight:700;">
                    <span style="width:7px; height:7px; background:#16a34a; border-radius:50%; display:inline-block;"></span>
                    <span>{{ $staffSession['name'] }} (Gate {{ $staffSession['gate'] }})</span>
                    <button type="button" onclick="logoutStaff()" title="Keluar / Ganti Panitia" style="background:none; border:none; cursor:pointer; color:#15803d; font-size:0.8rem; font-weight:800; padding-left:4px; line-height:1;">✕</button>
                </div>
            @endif
            <div style="display:inline-flex; align-items:center; gap:0.45rem; background:#f0fdf4; border:1.5px solid #bbf7d0; color:#166534; padding:0.35rem 0.85rem; border-radius:999px; font-size:0.76rem; font-weight:700;">
                <span class="pulse-dot"></span>
                <span>Gate Live</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-container">

        <!-- Event Info Header -->
        <section class="event-header">
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
        </section>

        <!-- Live Stats Grid (Curved Cards) -->
        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-num hadir" id="stat-checked">{{ $stats['checked_in'] }}</div>
                <div class="stat-label">Hadir ({{ $stats['rate'] }}%)</div>
            </div>
            <div class="stat-card">
                <div class="stat-num belum" id="stat-remain">{{ $stats['unchecked'] }}</div>
                <div class="stat-label">Belum Hadir</div>
            </div>
            <div class="stat-card">
                <div class="stat-num total" id="stat-total">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Tiket</div>
            </div>
        </section>

        <!-- Scanner Card Container (Image 2 Aesthetic) -->
        <div class="prof-card">
            
            <!-- Card Header -->
            <div class="prof-card-head">
                <div class="prof-card-title">
                    <svg width="22" height="22" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                    <span>Live Camera QR Ticket Scanner</span>
                </div>
            </div>

            <div class="form-body">
                
                <!-- Input Manual (Full Width di Atas) -->
                <form id="manualScanForm" style="margin-bottom: 1.25rem;" onsubmit="handleManualSubmit(event)">
                    <label style="font-size:0.83rem; font-weight:700; color:#334155; margin-bottom:0.5rem; display:block;">Pemeriksaan Kode Tiket / Token (Manual)</label>
                    <div style="display: flex;">
                        <input type="text" id="manualCodeInput" class="form-input-code" placeholder="Ketik Kode Tiket (misal: TKT-20260905-XXXX)...">
                        <button class="btn-submit-scan" type="submit">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                            Periksa
                        </button>
                    </div>
                </form>

                <!-- Subheader Bar: Viewport Label + Fast Mode + Mirror Toggle -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
                    
                    <!-- Kiri: Viewport Status + Signal Widget -->
                    <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                        <div style="font-weight: 700; font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 6px;">
                            <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                            Viewport Live QR Scanner
                        </div>
                        <div id="signalWidget" title="Kekuatan Sinyal Internet" style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.72rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:999px; background:#fef3c7; border:1.5px solid #fde68a; cursor:default; user-select:none;">
                            <div id="signalBars" style="display:flex; align-items:flex-end; gap:1.5px; height:13px;">
                                <div style="width:2.5px; height:3px; border-radius:1.5px; background:#d97706;"></div>
                                <div style="width:2.5px; height:5.5px; border-radius:1.5px; background:#d97706;"></div>
                                <div style="width:2.5px; height:8px; border-radius:1.5px; background:#d97706;"></div>
                                <div style="width:2.5px; height:10.5px; border-radius:1.5px; background:#cbd5e1;"></div>
                                <div style="width:2.5px; height:13px; border-radius:1.5px; background:#cbd5e1;"></div>
                            </div>
                            <span style="color:#b45309;">Standar</span>
                        </div>
                    </div>

                    <!-- Kanan: Fast Mode (Auto Next) + Mirror ON -->
                    <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                        <label style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.78rem; font-weight:700; color:#166534; background:#f0fdf4; padding:0.4rem 0.85rem; border-radius:999px; border:1.5px solid #bbf7d0; cursor:pointer; user-select:none;" title="Scan cepat otomatis">
                            <input type="checkbox" id="fast-mode" checked style="accent-color:#1eb349; width:16px; height:16px;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                            <span>Fast Mode (Auto Next)</span>
                        </label>
                        <button type="button" id="btn-mirror" onclick="toggleMirror()" style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.78rem; font-weight:700; color:#166534; background:#f0fdf4; padding:0.4rem 0.85rem; border-radius:999px; border:1.5px solid #bbf7d0; cursor:pointer; user-select:none; transition: all 0.2s;" title="Cerminkan kamera">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3M16 3h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-3M12 2v20"/><path d="M8 12h-.01M16 12h.01"/></svg>
                            <span id="mirror-lbl">Mirror: ON</span>
                        </button>
                    </div>

                </div>

                <!-- 2-Column Responsive Workspace Grid -->
                <div class="scanner-workspace-grid">

                    <!-- Kolom Kiri: Kamera Viewport -->
                    <div class="scanner-camera-col">
                        <div id="smartGuideBanner" class="smart-guide-banner">
                            <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <span id="smartGuideText" style="flex:1;">Pencahayaan Baik: Arahkan QR Code ke kamera</span>
                        </div>
                        <div id="reader"></div>
                    </div>

                    <!-- Kolom Kanan: Live Data Hasil Scan -->
                    <div class="scanner-data-col">
                        <div id="scannerResultPanel" class="scanner-result-panel">
                            
                            <!-- 1. State Idle (Menunggu Scan) -->
                            <div id="resStateIdle" style="display:flex; flex-direction:column; align-items:center; text-align:center; padding:1.25rem 0.75rem;">
                                <div style="width:64px; height:64px; border-radius:20px; background:#f0fdf4; border:1.5px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#1eb349; margin-bottom:1rem; box-shadow:0 8px 20px rgba(30,179,73,0.12);">
                                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1.5"/></svg>
                                </div>
                                <div style="display:inline-flex; align-items:center; gap:6px; padding:0.25rem 0.75rem; border-radius:999px; background:#e0f2fe; color:#0369a1; font-size:0.73rem; font-weight:700; margin-bottom:0.65rem;">
                                    <span style="width:7px; height:7px; border-radius:50%; background:#0284c7; display:inline-block;"></span>
                                    Kamera Siap Memindai
                                </div>
                                <h4 style="font-size:1.05rem; font-weight:800; color:#0f172a; margin:0 0 0.35rem 0;">Menunggu Scan Tiket</h4>
                                <p style="font-size:0.8rem; color:#64748b; line-height:1.5; margin:0 0 1.25rem 0; max-width:300px;">
                                    Arahkan QR Code pengunjung ke kamera di sebelah kiri, atau ketik kode tiket pada kolom di atas.
                                </p>
                                <div style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:12px; padding:0.75rem 0.85rem; width:100%; box-sizing:border-box; text-align:left;">
                                    <div style="font-size:0.74rem; font-weight:700; color:#334155; margin-bottom:0.25rem; display:flex; align-items:center; gap:5px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1eb349" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                        Tips Verifikasi Cepat:
                                    </div>
                                    <div style="font-size:0.73rem; color:#64748b; line-height:1.4;">
                                        Posisikan layar pengunjung lurus dengan kamera. Tiket valid akan langsung tercatat dan berbunyi lonceng sukses.
                                    </div>
                                </div>
                            </div>

                            <!-- 2. State Loading -->
                            <div id="resStateLoading" style="display:none; flex-direction:column; align-items:center; text-align:center; padding:2.5rem 1rem;">
                                <svg width="44" height="44" stroke="#1eb349" stroke-width="2.5" fill="none" viewBox="0 0 24 24" style="animation:spin 1s linear infinite; margin-bottom:1rem;">
                                    <circle cx="12" cy="12" r="10" stroke-dasharray="31" stroke-dashoffset="10"/>
                                </svg>
                                <h4 style="font-size:1rem; font-weight:800; color:#0f172a; margin-bottom:0.35rem;">Memverifikasi Tiket...</h4>
                                <p id="resLoadingCode" style="font-size:0.82rem; color:#64748b; font-family:monospace;"></p>
                            </div>

                            <!-- 3. State Active Result -->
                            <div id="resStateActive" style="display:none; flex-direction:column; justify-content:space-between; height:100%;">
                                <div>
                                    <!-- Status Banner -->
                                    <div id="resActiveBanner" style="display:flex; align-items:center; gap:0.75rem; padding:0.85rem 1rem; border-radius:14px; margin-bottom:0.85rem;">
                                        <div id="resActiveIcon" style="flex-shrink:0;"></div>
                                        <div style="flex:1; min-width:0;">
                                            <div id="resActiveStatusTag" style="font-size:0.7rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:2px;"></div>
                                            <h4 id="resActiveTitle" style="font-size:0.96rem; font-weight:800; margin:0; line-height:1.25;"></h4>
                                        </div>
                                    </div>

                                    <!-- Message -->
                                    <p id="resActiveMsg" style="font-size:0.8rem; color:#475569; line-height:1.45; margin:0 0 0.85rem 0;"></p>

                                    <!-- Detail Card -->
                                    <div class="detail-card">
                                        <div class="detail-row">
                                            <span class="detail-label">Nama Peserta</span>
                                            <span class="detail-val" id="resValHolder">-</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Kode Tiket</span>
                                            <span class="detail-val" id="resValCode" style="font-family:monospace; color:#16a34a;">-</span>
                                        </div>
                                        <div class="detail-row" id="resRowNik" style="display:none;">
                                            <span class="detail-label">NIK / Identitas</span>
                                            <span class="detail-val" id="resValNik">-</span>
                                        </div>
                                        <div class="detail-row" id="resRowStaff" style="display:none;">
                                            <span class="detail-label">Petugas Gate</span>
                                            <span class="detail-val" id="resValStaff">-</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Waktu Scan</span>
                                            <span class="detail-val" id="resValTime">-</span>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" onclick="resumeScannerNow()" style="width:100%; height:44px; background:linear-gradient(135deg, #1eb349, #15803d); color:#fff; border:none; border-radius:12px; font-weight:800; font-size:0.85rem; cursor:pointer; box-shadow:0 4px 14px rgba(30,179,73,0.3); transition:all 0.15s;">
                                    Scan Tiket Berikutnya
                                </button>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </main>

    <!-- POPUP MODAL KHUSUS MOBILE (Info Pop Up Cepat di Layar HP) -->
    <div id="gateMobileResultModal" onclick="if(event.target===this) closeGateMobileModal()" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.72); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:1rem; opacity:0; transition:all 0.25s ease;">
        <div id="gateMobileResultBox" style="background:#ffffff; border-radius:22px; padding:1.35rem 1.25rem; width:100%; max-width:340px; text-align:center; box-shadow:0 25px 50px -12px rgba(0,0,0,0.35); transform:scale(0.92); transition:all 0.25s cubic-bezier(0.34,1.56,0.64,1);">
            
            <div id="gateMobBubble" style="width:54px; height:54px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin:0 auto 0.85rem;"></div>

            <h3 id="gateMobTitle" style="font-size:1.18rem; font-weight:800; margin:0 0 0.25rem; font-family:'Montserrat',sans-serif;"></h3>
            <p id="gateMobMsg" style="font-size:0.8rem; color:#64748B; margin:0 0 0.85rem; line-height:1.45;"></p>

            <div id="gateMobCard" style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:14px; padding:0.85rem; text-align:left; font-size:0.82rem; margin-bottom:1.15rem; display:none;">
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
                    <span style="color:#64748B; font-size:0.78rem;">Petugas Gate</span>
                    <span id="mob-val-staff" style="color:#0F172A; font-size:0.8rem; font-weight:600;">-</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; padding-top:6px; border-top:1px dashed #E2E8F0; margin-top:4px;">
                    <span style="color:#64748B; font-size:0.78rem;">Waktu</span>
                    <strong id="mob-val-time" style="color:#166534; font-size:0.8rem;">-</strong>
                </div>
            </div>

            <div style="display:flex; justify-content:center; align-items:center;">
                <button type="button" id="gateMobNextBtn" onclick="closeGateMobileModal()" style="display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:0.5rem 1.5rem; background:linear-gradient(135deg, #1eb349, #15803d); color:#fff; border:none; border-radius:999px; font-weight:800; font-size:0.82rem; cursor:pointer; box-shadow:0 3px 12px rgba(30,179,73,0.3); transition:all 0.15s ease;">
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

        let localTickets = @json($cachedTickets);

        let html5QrcodeScanner = null;
        let isProcessing = false;
        let isMirrored = localStorage.getItem('gate_scanner_mirror') !== 'false';
        let jsQrAssistInterval = null;

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
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.1); // A5
                    gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.35);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.35);
                } else if (type === 'used') {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(320, audioCtx.currentTime);
                    osc.frequency.setValueAtTime(260, audioCtx.currentTime + 0.15);
                    gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.4);
                } else {
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

        function applyMirrorState() {
            const readerEl = document.getElementById('reader');
            const lbl = document.getElementById('mirror-lbl');
            if (isMirrored) {
                if (readerEl) readerEl.classList.add('mirror-on');
                if (lbl) lbl.innerText = 'Mirror: ON';
            } else {
                if (readerEl) readerEl.classList.remove('mirror-on');
                if (lbl) lbl.innerText = 'Mirror: OFF';
            }
        }

        function toggleMirror() {
            isMirrored = !isMirrored;
            localStorage.setItem('gate_scanner_mirror', isMirrored);
            applyMirrorState();
        }

        async function initScanner() {
            applyMirrorState();

            // Prioritaskan kamera eksternal USB jika ada
            try {
                const devices = await Html5Qrcode.getCameras();
                if (devices && devices.length > 0) {
                    let savedCam = localStorage.getItem('buyle_chosen_camera_id');
                    let chosenDev = devices.find(d => d.id === savedCam);

                    if (!chosenDev) {
                        chosenDev = devices.find(d => /jete|external|usb\s*webcam|webcam/i.test(d.label) && !/integrated|built-?in|internal|lsvcam|obs/i.test(d.label));
                    }

                    if (chosenDev) {
                        localStorage.setItem('buyle_chosen_camera_id', chosenDev.id);
                        try {
                            localStorage.setItem('HTML5_QRCODE_DATA', JSON.stringify({
                                hasCameraPermission: true,
                                lastUsedCameraId: chosenDev.id
                            }));
                        } catch(e) {}
                    }
                }
            } catch(e) {}

            try {
                html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                    fps: 15,
                    disableFlip: false,
                    rememberLastUsedCamera: true,
                    showTorchButtonIfSupported: true,
                    showZoomSliderIfSupported: true,
                    experimentalFeatures: {
                        useBarCodeDetectorIfSupported: true
                    },
                    videoConstraints: {
                        width: { ideal: 1280, min: 640 },
                        height: { ideal: 720, min: 480 },
                    }
                }, false);

                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                setupCameraPersistence();
                startJsQrAssistLoop();
                setTimeout(() => { applyMirrorState(); }, 800);

            } catch (err) {
                console.error('[Gate Scanner] Init failed:', err);
            }
        }

        function setupCameraPersistence() {
            const readerEl = document.getElementById('reader');
            if (!readerEl) return;

            const observer = new MutationObserver(() => {
                const sel = readerEl.querySelector('select');
                if (sel && !sel._buyleHooked) {
                    sel._buyleHooked = true;

                    const savedCam = localStorage.getItem('buyle_chosen_camera_id');
                    if (savedCam && sel.value !== savedCam) {
                        const optExists = Array.from(sel.options).some(o => o.value === savedCam);
                        if (optExists) sel.value = savedCam;
                    }

                    sel.addEventListener('change', function() {
                        if (this.value) {
                            localStorage.setItem('buyle_chosen_camera_id', this.value);
                            setTimeout(() => { applyMirrorState(); }, 600);
                        }
                    });
                }
            });
            observer.observe(readerEl, { childList: true, subtree: true });
        }

        function startJsQrAssistLoop() {
            if (jsQrAssistInterval) clearInterval(jsQrAssistInterval);
            let gCanvas = null;
            let gCtx = null;

            jsQrAssistInterval = setInterval(() => {
                if (isProcessing) return;
                if (typeof window.jsQR !== 'function') return;

                const video = document.querySelector('#reader video');
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

        function onScanSuccess(decodedText) {
            if (isProcessing) return;
            isProcessing = true;
            processVerification(decodedText);
        }

        function onScanFailure(error) {
            // Frame skip normal
        }

        // Manual scan submit
        function handleManualSubmit(e) {
            if (e) e.preventDefault();
            const input = document.getElementById('manualCodeInput');
            const val = input.value.trim();
            if (!val) return;
            isProcessing = true;
            processVerification(val);
        }

        let currentGatePin = '';
        const REQUIRES_PIN = {{ $requiresPin ? 'true' : 'false' }};
        const REQUIRES_STAFF_LOGIN = {{ $requiresStaffLogin ? 'true' : 'false' }};
        const AUTH_PIN_URL = "{{ route('gate.scanner.auth-pin', ['key' => $key]) }}";
        const STAFF_AUTH_URL = "{{ route('gate.scanner.staff-auth', ['key' => $key]) }}";
        const STAFF_LOGOUT_URL = "{{ route('gate.scanner.staff-logout', ['key' => $key]) }}";

        async function processVerification(rawCode) {
            const clean = rawCode.trim();

            // Set loading state
            document.getElementById('resStateIdle').style.display = 'none';
            document.getElementById('resStateActive').style.display = 'none';
            document.getElementById('resStateLoading').style.display = 'flex';
            document.getElementById('resLoadingCode').innerText = clean;

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
            const isMobile = window.innerWidth <= 768;

            // Render right side panel
            const idle = document.getElementById('resStateIdle');
            const loading = document.getElementById('resStateLoading');
            const active = document.getElementById('resStateActive');
            const panel = document.getElementById('scannerResultPanel');

            loading.style.display = 'none';
            idle.style.display = 'none';
            active.style.display = 'flex';

            panel.className = `scanner-result-panel status-${data.status}`;

            const banner = document.getElementById('resActiveBanner');
            const icon = document.getElementById('resActiveIcon');
            const statusTag = document.getElementById('resActiveStatusTag');
            const title = document.getElementById('resActiveTitle');
            const msg = document.getElementById('resActiveMsg');

            if (data.status === 'valid') {
                banner.style.background = '#f0fdf4';
                banner.style.border = '1px solid #bbf7d0';
                icon.innerHTML = '<div style="width:36px; height:36px; border-radius:50%; background:#22c55e; color:white; display:flex; align-items:center; justify-content:center;"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg></div>';
                statusTag.style.color = '#15803d';
                statusTag.innerText = 'TIKET VALID · CHECK-IN SUKSES';
                title.style.color = '#15803d';
                title.innerText = data.title || 'Selamat Datang di Acara!';
                msg.innerText = data.message || 'Tiket resmi dan sah terverifikasi.';

                const t = data.ticket || {};
                document.getElementById('resValHolder').innerText = t.holder_name || '-';
                document.getElementById('resValCode').innerText = t.code || '-';
                document.getElementById('resValTime').innerText = t.checked_in_at || new Date().toLocaleTimeString();

                const nikRow = document.getElementById('resRowNik');
                if (t.holder_nik) {
                    nikRow.style.display = 'flex';
                    document.getElementById('resValNik').innerText = t.holder_nik;
                } else {
                    nikRow.style.display = 'none';
                }

                const staffRow = document.getElementById('resRowStaff');
                if (t.scanned_by) {
                    staffRow.style.display = 'flex';
                    document.getElementById('resValStaff').innerText = t.scanned_by;
                } else {
                    staffRow.style.display = 'none';
                }

                playSound('success');
                triggerHaptic('success');
                incrementCounter();

            } else if (data.status === 'used') {
                banner.style.background = '#fffbeb';
                banner.style.border = '1px solid #fde68a';
                icon.innerHTML = '<div style="width:36px; height:36px; border-radius:50%; background:#f59e0b; color:white; display:flex; align-items:center; justify-content:center;"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg></div>';
                statusTag.style.color = '#b45309';
                statusTag.innerText = 'TIKET SUDAH DIGUNAKAN';
                title.style.color = '#b45309';
                title.innerText = data.title || 'Peringatan: Tiket Sudah Check-In';
                msg.innerText = data.message || 'Tiket ini telah dipindai sebelumnya.';

                const t = data.ticket || {};
                document.getElementById('resValHolder').innerText = t.holder_name || '-';
                document.getElementById('resValCode').innerText = t.code || '-';
                document.getElementById('resValTime').innerText = t.checked_in_at || '-';
                document.getElementById('resRowNik').style.display = 'none';

                const staffRow = document.getElementById('resRowStaff');
                if (t.scanned_by) {
                    staffRow.style.display = 'flex';
                    document.getElementById('resValStaff').innerText = t.scanned_by;
                } else {
                    staffRow.style.display = 'none';
                }

                playSound('used');
                triggerHaptic('used');

            } else {
                banner.style.background = '#fef2f2';
                banner.style.border = '1px solid #fecaca';
                icon.innerHTML = '<div style="width:36px; height:36px; border-radius:50%; background:#ef4444; color:white; display:flex; align-items:center; justify-content:center;"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></div>';
                statusTag.style.color = '#b91c1c';
                statusTag.innerText = 'TIKET TIDAK VALID';
                title.style.color = '#b91c1c';
                title.innerText = data.title || 'Akses Ditolak';
                msg.innerText = data.message || 'Tiket tidak terdaftar di sistem acara ini.';

                document.getElementById('resValHolder').innerText = '-';
                document.getElementById('resValCode').innerText = '-';
                document.getElementById('resValTime').innerText = '-';
                document.getElementById('resRowNik').style.display = 'none';
                document.getElementById('resRowStaff').style.display = 'none';

                playSound('invalid');
                triggerHaptic('invalid');
            }

            // Jika di mobile, tampilkan juga modal popup responsif
            if (isMobile) {
                openGateMobileModal(data);
            }

            // Fast auto-resume jika Fast Mode aktif
            const isFast = document.getElementById('fast-mode').checked;
            if (isFast && data.status === 'valid') {
                setTimeout(() => {
                    resumeScannerNow();
                }, 1200);
            }
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
                bubble.innerHTML = '<svg width="28" height="28" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
                title.style.color = '#15803d';
                title.innerText = data.title || 'Check-In Berhasil!';
                msg.innerText = data.message || 'Tiket valid.';

                if (nextBtn) {
                    nextBtn.style.background = 'linear-gradient(135deg, #1eb349, #15803d)';
                    nextBtn.style.boxShadow = '0 3px 12px rgba(30,179,73,0.3)';
                }
                if (btnText) btnText.innerText = 'Next';
                if (btnIcon) btnIcon.style.display = 'inline-block';
            } else if (data.status === 'used') {
                bubble.style.background = '#fffbeb';
                bubble.style.border = '1.5px solid #fde68a';
                bubble.innerHTML = '<svg width="28" height="28" fill="none" stroke="#d97706" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
                title.style.color = '#b45309';
                title.innerText = data.title || 'Sudah Digunakan';
                msg.innerText = data.message || 'Tiket sudah dipindai sebelumnya.';

                if (nextBtn) {
                    nextBtn.style.background = '#0f172a';
                    nextBtn.style.boxShadow = 'none';
                }
                if (btnText) btnText.innerText = 'Tutup';
                if (btnIcon) btnIcon.style.display = 'none';
            } else {
                bubble.style.background = '#fef2f2';
                bubble.style.border = '1.5px solid #fecaca';
                bubble.innerHTML = '<svg width="28" height="28" fill="none" stroke="#dc2626" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
                title.style.color = '#b91c1c';
                title.innerText = data.title || 'Tiket Tidak Valid';
                msg.innerText = data.message || 'Tiket tidak ditemukan.';

                if (nextBtn) {
                    nextBtn.style.background = '#0f172a';
                    nextBtn.style.boxShadow = 'none';
                }
                if (btnText) btnText.innerText = 'Tutup';
                if (btnIcon) btnIcon.style.display = 'none';
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
                }, 180);
            } else {
                resumeScannerNow();
            }
        }

        function resumeScannerNow() {
            isProcessing = false;
            document.getElementById('resStateActive').style.display = 'none';
            document.getElementById('resStateLoading').style.display = 'none';
            document.getElementById('resStateIdle').style.display = 'flex';
            document.getElementById('scannerResultPanel').className = 'scanner-result-panel';
            document.getElementById('manualCodeInput').value = '';
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
                    errMsg.innerText = data.message || 'PIN Gate yang dimasukkan salah.';
                    errMsg.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Buka Scanner';
                    pinInput.select();
                }
            } catch(err) {
                errMsg.innerText = 'Gagal menghubungi server.';
                errMsg.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerText = 'Buka Scanner';
            }
        }

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

        // Initialize scanner on DOM load
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
