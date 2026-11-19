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

        /* ── Top Header Buyle TIX ── */
        .gate-tix-header {
            background: linear-gradient(135deg, #1db349 0%, #84ce00 100%);
            box-shadow: 0 4px 20px rgba(29, 179, 73, 0.28);
            border-bottom: 1px solid rgba(255, 255, 255, 0.18);
            padding: 0.65rem 1rem;
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            overflow: hidden;
        }

        .gate-header-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 0;
            flex-shrink: 0;
        }

        .gate-tix-logo {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }
        .gate-tix-logo:hover {
            transform: scale(1.02);
        }

        .gate-event-badge-strip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(8px);
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 700;
            max-width: 140px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 640px) {
            .gate-event-badge-strip {
                display: none !important;
            }
            .gate-tix-header {
                padding: 0.5rem 0.75rem;
            }
        }

        .gate-header-right {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-shrink: 0;
        }

        .gate-staff-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #ffffff;
            color: #166534;
            padding: 0.25rem 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            flex-shrink: 0;
        }

        .gate-staff-name-text {
            max-width: 80px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }

        .gate-dot-online {
            width: 7px;
            height: 7px;
            background: #16a34a;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .gate-staff-logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #ef4444;
            font-size: 0.78rem;
            font-weight: 800;
            padding-left: 2px;
            line-height: 1;
        }

        .gate-live-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(255, 255, 255, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff;
            padding: 0.25rem 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            backdrop-filter: blur(8px);
            flex-shrink: 0;
            white-space: nowrap;
        }

        .btn-fs-toggle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ffffff;
            border: none;
            color: #166534;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .btn-fs-toggle:hover {
            transform: scale(1.05);
            background: #f0fdf4;
            color: #15803d;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #ffffff;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.4);
            animation: pulse-ring 1.8s infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.8); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(255, 255, 255, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
        }

        /* ── Main Container ── */
        .main-container {
            max-width: 1040px;
            width: 100%;
            margin: 0 auto;
            padding: 1rem 1.25rem 2.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* ── Clean Event Strip (No Stats Cards) ── */
        .event-clean-header {
            text-align: center;
            padding: 0.25rem 0 0.5rem;
        }
        .event-clean-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.35rem;
            letter-spacing: -0.02em;
        }
        .event-clean-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            font-size: 0.82rem;
            color: var(--muted);
            font-weight: 600;
        }
        .event-clean-meta span {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* ── Fullscreen Mode Styles ── */
        :fullscreen,
        :-webkit-full-screen,
        body.fullscreen-mode {
            background-color: #020617 !important;
        }
        body.fullscreen-mode .main-container {
            max-width: 100% !important;
            padding: 0.65rem !important;
            gap: 0.65rem !important;
        }
        body.fullscreen-mode .event-clean-header {
            padding: 0 0 0.25rem !important;
        }
        body.fullscreen-mode .event-clean-title {
            color: #ffffff !important;
            font-size: 1.05rem !important;
            margin-bottom: 0.2rem !important;
        }
        body.fullscreen-mode .event-clean-meta {
            color: #94a3b8 !important;
            font-size: 0.74rem !important;
        }
        body.fullscreen-mode .prof-card {
            border-color: #1e293b !important;
            background: #0f172a !important;
        }
        body.fullscreen-mode .form-body {
            background: #0f172a !important;
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

        /* ── Clean Toolbar Above Camera ── */
        .scanner-clean-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .scanner-status-tag {
            font-weight: 700;
            font-size: 0.82rem;
            color: #166534;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            border: 1px solid #bbf7d0;
        }
        .scanner-ctrl-group {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
        }
        .scanner-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.76rem;
            font-weight: 700;
            color: #334155;
            background: #f8fafc;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            border: 1.5px solid #e2e8f0;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s;
        }
        .scanner-pill-btn:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }
        .scanner-pill-check {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.76rem;
            font-weight: 700;
            color: #166534;
            background: #f0fdf4;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            border: 1.5px solid #bbf7d0;
            cursor: pointer;
            user-select: none;
        }
        .manual-scan-wrap {
            margin-top: 0.75rem;
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

        /* ════ FULLSCREEN QR SCANNER MODE (Mobile Superapp Style) ════ */
        @keyframes slideUpCard {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes scanLineAnim {
            0% { transform: translateY(8px); opacity: 0.65; }
            50% { transform: translateY(calc(min(260px, 72vw) - 16px)); opacity: 1; }
            100% { transform: translateY(8px); opacity: 0.65; }
        }

        #fullscreenScannerOverlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99990;
            background: #000;
            flex-direction: column;
        }

        #fullscreenScannerOverlay.active {
            display: flex !important;
        }

        #fsVideoWrapper {
            position: relative;
            width: 100%;
            flex: 1;
            overflow: hidden;
            background: #000;
        }

        #fsVideoEl {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        #fsGreenTint {
            position: absolute;
            inset: 0;
            background: rgba(30, 179, 73, 0.08);
            pointer-events: none;
        }

        #fsScanFrame {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -55%);
            width: min(260px, 72vw);
            height: min(260px, 72vw);
            pointer-events: none;
        }

        .fs-corner {
            position: absolute;
            width: 38px;
            height: 38px;
            border-color: #ffffff;
            border-style: solid;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.4));
        }

        .fs-corner.tl {
            top: 0;
            left: 0;
            border-width: 4px 0 0 4px;
            border-radius: 18px 0 0 0;
        }

        .fs-corner.tr {
            top: 0;
            right: 0;
            border-width: 4px 4px 0 0;
            border-radius: 0 18px 0 0;
        }

        .fs-corner.bl {
            bottom: 0;
            left: 0;
            border-width: 0 0 4px 4px;
            border-radius: 0 0 0 18px;
        }

        .fs-corner.br {
            bottom: 0;
            right: 0;
            border-width: 0 4px 4px 0;
            border-radius: 0 0 18px 0;
        }

        #fsScanLine {
            position: absolute;
            top: 0;
            left: 6px;
            right: 6px;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), #ffffff, #86efac, #ffffff, rgba(255, 255, 255, 0.4), transparent);
            border-radius: 999px;
            animation: scanLineAnim 2.4s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
            box-shadow: 0 0 14px rgba(255, 255, 255, 0.95), 0 0 24px rgba(34, 197, 94, 0.7);
            will-change: transform;
            pointer-events: none;
        }

        #fsTopBar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding-top: max(env(safe-area-inset-top, 0px), 14px);
            padding-left: 16px;
            padding-right: 16px;
            padding-bottom: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 10;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.5) 0%, transparent 100%);
        }

        #fsBackBtn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.18);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            outline: none;
            -webkit-tap-highlight-color: transparent;
            transition: all 0.15s;
        }

        #fsBackBtn:active { transform: scale(0.9); }

        #fsTopRight {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #fsSignalWidget {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            font-size: 0.7rem;
            font-weight: 700;
            color: #fff;
        }

        #fsFlashBtn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            outline: none;
            -webkit-tap-highlight-color: transparent;
            transition: all 0.2s;
        }

        #fsFlashBtn.on {
            background: rgba(255, 220, 0, 0.3);
            border-color: rgba(255, 220, 0, 0.5);
            color: #fde68a;
        }

        #fsGuideText {
            position: absolute;
            bottom: 42%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            text-align: center;
            white-space: nowrap;
            pointer-events: none;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
        }

        #fsScanResultCard {
            position: absolute;
            bottom: max(env(safe-area-inset-bottom, 0px), 18px);
            left: 14px;
            right: 14px;
            max-width: 430px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 26px;
            padding: 1rem 1.15rem 1.15rem;
            box-shadow: 0 16px 45px rgba(0, 0, 0, 0.35);
            display: none;
            animation: slideUpCard 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            z-index: 20;
        }

        #fsScanResultCard.show {
            display: block;
        }

        .fs-card-drag-pill {
            width: 36px;
            height: 4px;
            background: #cbd5e1;
            border-radius: 999px;
            margin: 0 auto 0.75rem;
        }

        .fs-card-event-row {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 0.75rem;
        }

        .fs-card-event-img {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            object-fit: cover;
            background: #f1f5f9;
            flex-shrink: 0;
            border: 1.5px solid #f1f5f9;
        }

        .fs-card-event-img-placeholder {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1.5px solid #bbf7d0;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1eb349;
        }

        .fs-card-event-info {
            flex: 1;
            min-width: 0;
        }

        .fs-card-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .fs-card-status-badge.valid {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .fs-card-status-badge.used {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .fs-card-status-badge.invalid {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .fs-card-event-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.1rem;
        }

        .fs-card-event-date {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 500;
        }

        .fs-card-data-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 0.9rem;
        }

        .fs-card-data-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 0.5rem 0.65rem;
        }

        .fs-card-data-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #94a3b8;
            margin-bottom: 2px;
        }

        .fs-card-data-value {
            font-size: 0.8rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fs-card-next-btn {
            width: 100%;
            height: 48px;
            border-radius: 999px;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            border: none;
            color: #ffffff;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(30, 179, 73, 0.4);
            transition: all 0.15s;
            -webkit-tap-highlight-color: transparent;
        }

        .fs-card-next-btn:active { transform: scale(0.97); }

        .fs-card-next-btn.error-btn {
            background: #0f172a;
            box-shadow: none;
        }

        /* ── Fullscreen Trigger Button ── */
        #openFullscreenBtn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 0.75rem;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.25);
            transition: all 0.2s;
            -webkit-tap-highlight-color: transparent;
            font-family: inherit;
        }
        #openFullscreenBtn:active {
            transform: scale(0.98);
        }

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

    <!-- Header Buyle TIX -->
    <header class="gate-tix-header">
        <div class="gate-header-left">
            <a href="{{ route('tix.index') }}" class="gate-tix-logo" title="Buyle TIX — Platform Tiket Event">
                <picture>
                    <source srcset="{{ asset('images/logobuyletix.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logobuyletix.png') }}" alt="Buyle TIX" height="26" style="height:26px; width:auto; display:block;">
                </picture>
            </a>
            <div class="gate-event-badge-strip">
                <span>{{ $event->name }}</span>
            </div>
        </div>

        <div class="gate-header-right">
            @if($staffSession)
                <div id="staffActiveBadge" class="gate-staff-pill" title="Petugas Aktif: {{ $staffSession['name'] }}">
                    <span class="gate-dot-online"></span>
                    <span class="gate-staff-name-text">{{ $staffSession['name'] }}</span>
                    <button type="button" onclick="logoutStaff()" title="Keluar / Ganti Panitia" class="gate-staff-logout-btn">✕</button>
                </div>
            @endif

            <div class="gate-live-pill" title="Status Gate Aktif">
                <span class="pulse-dot"></span>
                <span>Live</span>
            </div>

            <!-- Fullscreen Mode Button -->
            <button type="button" onclick="openFullscreenScanner()" class="btn-fs-toggle" id="btnFsToggle" title="Buka Mode Fullscreen Scanner">
                <span id="fsIconWrap" style="display:flex; align-items:center; justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3" />
                    </svg>
                </span>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">

        <!-- Clean Event Info Header -->
        <section class="event-clean-header">
            <h1 class="event-clean-title">{{ $event->name }}</h1>
            <div class="event-clean-meta">
                @if($event->event_date)
                    <span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        {{ $event->event_date->format('d M Y') }}
                    </span>
                @endif
                @if($event->event_time)
                    <span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        {{ $event->event_time }}
                    </span>
                @endif
                @if($event->event_location)
                    <span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        {{ $event->event_location }}
                    </span>
                @endif
            </div>
        </section>

        <!-- Scanner Card Container -->
        <div class="prof-card">
            <div class="form-body" style="padding: 1.15rem 1.25rem;">

                <!-- 2-Column Responsive Workspace Grid -->
                <div class="scanner-workspace-grid" style="margin-top: 0;">

                    <!-- Kolom Kiri: Kamera Viewport -->
                    <div class="scanner-camera-col">
                        <!-- Toolbar Ringkas -->
                        <div class="scanner-clean-toolbar">
                            <span class="scanner-status-tag">
                                <span class="pulse-dot" style="background:#16a34a; width:7px; height:7px;"></span>
                                Viewport Scanner
                            </span>
                            <div class="scanner-ctrl-group">
                                <label class="scanner-pill-check" title="Scan cepat otomatis tanpa klik tombol">
                                    <input type="checkbox" id="fast-mode" checked style="accent-color:#1eb349; width:15px; height:15px;">
                                    <span>⚡ Auto Next</span>
                                </label>
                                <button type="button" id="btn-mirror" onclick="toggleMirror()" class="scanner-pill-btn" title="Cerminkan kamera">
                                    <span id="mirror-lbl">Mirror: ON</span>
                                </button>
                            </div>
                        </div>

                        <!-- Guide Banner -->
                        <div id="smartGuideBanner" class="smart-guide-banner" style="margin-bottom: 0.65rem;">
                            <svg width="15" height="15" fill="none" stroke="#16a34a" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <span id="smartGuideText" style="flex:1;">Arahkan QR Code tiket pengunjung ke kamera</span>
                        </div>

                        <!-- Viewport Box -->
                        <div id="reader"></div>

                        <!-- Fullscreen Button Superapp Style (seperti di tab=scanner) -->
                        <button type="button" id="openFullscreenBtn" onclick="openFullscreenScanner()">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3" />
                            </svg>
                            Buka Mode Fullscreen Scanner
                        </button>

                        <!-- Input Manual Ringkas -->
                        <form id="manualScanForm" class="manual-scan-wrap" onsubmit="handleManualSubmit(event)" style="display:flex; margin-top:0.75rem;">
                            <input type="text" id="manualCodeInput" placeholder="Ketik kode tiket manual jika QR bermasalah..." autocomplete="off"
                                style="flex:1; height:40px; padding:0 0.85rem; border:1.5px solid #cbd5e1; border-radius:10px 0 0 10px; font-size:0.8rem; font-family:inherit; outline:none; background:#ffffff;">
                            <button type="submit"
                                style="height:40px; padding:0 1.25rem; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; border:none; border-radius:0 10px 10px 0; font-size:0.8rem; font-weight:700; cursor:pointer; flex-shrink:0; font-family:inherit;">
                                Periksa
                            </button>
                        </form>
                    </div>

                    <!-- Kolom Kanan: Live Data Hasil Scan (Muncul saat memindai) -->
                    <div class="scanner-data-col">
                        <div id="scannerResultPanel" class="scanner-result-panel" style="display:none;">

                            <!-- 1. State Loading -->
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

    {{-- ══ FULLSCREEN QR SCANNER OVERLAY (Mobile Superapp Style) ══ --}}
    <div id="fullscreenScannerOverlay">
        <div id="fsVideoWrapper">
            <video id="fsVideoEl" autoplay playsinline muted></video>
            <div id="fsGreenTint"></div>

            <div id="fsScanFrame">
                <div class="fs-corner tl"></div>
                <div class="fs-corner tr"></div>
                <div class="fs-corner bl"></div>
                <div class="fs-corner br"></div>
                <div id="fsScanLine"></div>
            </div>

            <div id="fsTopBar">
                <button type="button" id="fsBackBtn" onclick="closeFullscreenScanner()" aria-label="Tutup Fullscreen">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                </button>

                <div id="fsTopRight">
                    <div id="fsSignalWidget">
                        <div id="fsSignalBars" style="display:flex; align-items:flex-end; gap:1.5px; height:12px;">
                            <div id="fsBar1" style="width:2.5px; height:3px; border-radius:1.5px; background:rgba(255,255,255,0.4);"></div>
                            <div id="fsBar2" style="width:2.5px; height:5px; border-radius:1.5px; background:rgba(255,255,255,0.4);"></div>
                            <div id="fsBar3" style="width:2.5px; height:7.5px; border-radius:1.5px; background:rgba(255,255,255,0.4);"></div>
                            <div id="fsBar4" style="width:2.5px; height:10px; border-radius:1.5px; background:rgba(255,255,255,0.4);"></div>
                            <div id="fsBar5" style="width:2.5px; height:12px; border-radius:1.5px; background:rgba(255,255,255,0.4);"></div>
                        </div>
                        <span id="fsSignalLabel" style="font-size:0.68rem;">Live</span>
                    </div>
                    <button type="button" id="fsFlashBtn" onclick="toggleFsFlash()" aria-label="Flash">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                        <span id="fsFlashLabel">Flash off</span>
                    </button>
                </div>
            </div>

            <div id="fsGuideText">Arahkan QR Code ke dalam bingkai</div>

            <div id="fsScanResultCard">
                <div class="fs-card-drag-pill"></div>

                <div class="fs-card-event-row">
                    <div id="fsCardEventImgWrap" class="fs-card-event-img-placeholder">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div class="fs-card-event-info">
                        <div id="fsCardStatusBadge" class="fs-card-status-badge valid">
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            CHECK-IN BERHASIL
                        </div>
                        <div id="fsCardEventName" class="fs-card-event-name">{{ $event->name }}</div>
                        <div id="fsCardEventDate" class="fs-card-event-date">{{ $event->event_date ? $event->event_date->format('d M Y') : '' }}</div>
                    </div>
                </div>

                <div class="fs-card-data-grid">
                    <div class="fs-card-data-item">
                        <div class="fs-card-data-label">Pemegang Tiket</div>
                        <div id="fsCardHolderName" class="fs-card-data-value">—</div>
                    </div>
                    <div class="fs-card-data-item">
                        <div class="fs-card-data-label">Kode Tiket</div>
                        <div id="fsCardTicketCode" class="fs-card-data-value" style="font-family:monospace; color:#1eb349;">—</div>
                    </div>
                    <div class="fs-card-data-item">
                        <div class="fs-card-data-label">No. Identitas</div>
                        <div id="fsCardNik" class="fs-card-data-value" style="font-size:0.72rem; letter-spacing:0.5px;">—</div>
                    </div>
                    <div class="fs-card-data-item">
                        <div class="fs-card-data-label">Waktu Check-In</div>
                        <div id="fsCardTime" class="fs-card-data-value">—</div>
                    </div>
                </div>

                <button type="button" class="fs-card-next-btn" id="fsCardNextBtn" onclick="closeFsScanCard()">
                    <span id="fsCardNextLabel">Scan Berikutnya</span>
                    <svg id="fsCardNextIcon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </button>
            </div>

            <canvas id="fsCanvas" style="display:none;"></canvas>
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
            const panel = document.getElementById('scannerResultPanel');
            if (panel) panel.style.display = 'block';
            const active = document.getElementById('resStateActive');
            const loading = document.getElementById('resStateLoading');
            if (active) active.style.display = 'none';
            if (loading) loading.style.display = 'flex';
            const codeEl = document.getElementById('resLoadingCode');
            if (codeEl) codeEl.innerText = clean;

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
            const panel = document.getElementById('scannerResultPanel');
            const loading = document.getElementById('resStateLoading');
            const active = document.getElementById('resStateActive');

            if (panel) panel.style.display = 'block';
            if (loading) loading.style.display = 'none';
            if (active) active.style.display = 'flex';

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
            const active = document.getElementById('resStateActive');
            const loading = document.getElementById('resStateLoading');
            const panel = document.getElementById('scannerResultPanel');
            if (active) active.style.display = 'none';
            if (loading) loading.style.display = 'none';
            if (panel) {
                panel.style.display = 'none';
                panel.className = 'scanner-result-panel';
            }
            const input = document.getElementById('manualCodeInput');
            if (input) input.value = '';
        }

        // ════ FULLSCREEN QR SCANNER CONTROLLER (Mobile Superapp Mode) ════
        let fsStream = null;
        let fsTorchTrack = null;
        let fsFlashOn = false;
        let fsIsProcessing = false;
        let fsQrInterval = null;
        let fsNativeDetector = null;

        if ('BarcodeDetector' in window) {
            try { fsNativeDetector = new window.BarcodeDetector({ formats: ['qr_code'] }); } catch (e) { }
        }

        async function openFullscreenScanner() {
            if (audioCtx && audioCtx.state === 'suspended') {
                try { audioCtx.resume(); } catch(e) {}
            }
            const overlay = document.getElementById('fullscreenScannerOverlay');
            const videoEl = document.getElementById('fsVideoEl');
            if (!overlay || !videoEl) return;

            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Attempt native browser fullscreen if supported
            try {
                const docEl = document.documentElement;
                const req = docEl.requestFullscreen || docEl.webkitRequestFullscreen || docEl.mozRequestFullScreen || docEl.msRequestFullscreen;
                if (req && !document.fullscreenElement) {
                    req.call(docEl).catch(() => {});
                }
            } catch(e) {}

            try {
                if (screen.orientation && screen.orientation.lock) {
                    await screen.orientation.lock('portrait').catch(() => {});
                }
            } catch(e) {}

            fsFlashOn = false;
            fsIsProcessing = false;
            const flashLbl = document.getElementById('fsFlashLabel');
            const flashBtn = document.getElementById('fsFlashBtn');
            if (flashLbl) flashLbl.textContent = 'Flash off';
            if (flashBtn) flashBtn.classList.remove('on');
            closeFsScanCard();

            // Pause inline camera video to prevent camera resource conflict
            const inlineVideo = document.querySelector('#reader video');
            if (inlineVideo) {
                try { inlineVideo.pause(); } catch(e) {}
            }

            try {
                const constraints = {
                    video: {
                        facingMode: { ideal: 'environment' },
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                };
                fsStream = await navigator.mediaDevices.getUserMedia(constraints);
                videoEl.srcObject = fsStream;
                await videoEl.play();

                const tracks = fsStream.getVideoTracks();
                if (tracks.length > 0) {
                    fsTorchTrack = tracks[0];
                    const caps = fsTorchTrack.getCapabilities ? fsTorchTrack.getCapabilities() : {};
                    if (!caps.torch) {
                        if (flashBtn) {
                            flashBtn.style.opacity = '0.5';
                            flashBtn.style.pointerEvents = 'none';
                        }
                    } else {
                        if (flashBtn) {
                            flashBtn.style.opacity = '1';
                            flashBtn.style.pointerEvents = 'auto';
                        }
                    }
                }

                startFsQrLoop();
            } catch(err) {
                console.error('[FS Scanner] Camera error:', err);
                alert('Gagal mengakses kamera di mode fullscreen. Pastikan izin kamera aktif.');
                closeFullscreenScanner();
            }
        }

        function closeFullscreenScanner() {
            const overlay = document.getElementById('fullscreenScannerOverlay');
            if (overlay) overlay.classList.remove('active');
            document.body.style.overflow = '';

            try {
                const doc = document;
                const exit = doc.exitFullscreen || doc.webkitExitFullscreen || doc.mozCancelFullScreen || doc.msExitFullscreen;
                if (exit && (doc.fullscreenElement || doc.webkitFullscreenElement)) {
                    exit.call(doc).catch(() => {});
                }
            } catch(e) {}

            stopFsCamera();
            stopFsQrLoop();
            closeFsScanCard();

            // Resume inline camera video
            const inlineVideo = document.querySelector('#reader video');
            if (inlineVideo) {
                try { inlineVideo.play(); } catch(e) {}
            }
        }

        function stopFsCamera() {
            const videoEl = document.getElementById('fsVideoEl');
            if (videoEl) { videoEl.pause(); videoEl.srcObject = null; }
            if (fsStream) {
                fsStream.getTracks().forEach(t => t.stop());
                fsStream = null;
                fsTorchTrack = null;
            }
        }

        async function toggleFsFlash() {
            if (!fsTorchTrack) return;
            fsFlashOn = !fsFlashOn;
            try {
                await fsTorchTrack.applyConstraints({ advanced: [{ torch: fsFlashOn }] });
                const btn = document.getElementById('fsFlashBtn');
                const lbl = document.getElementById('fsFlashLabel');
                if (fsFlashOn) {
                    if (btn) btn.classList.add('on');
                    if (lbl) lbl.textContent = 'Flash on';
                } else {
                    if (btn) btn.classList.remove('on');
                    if (lbl) lbl.textContent = 'Flash off';
                }
            } catch (e) {
                console.warn('[FS Flash] torch not supported:', e);
            }
        }

        function startFsQrLoop() {
            stopFsQrLoop();
            const video = document.getElementById('fsVideoEl');
            const canvas = document.getElementById('fsCanvas');
            const ctx = canvas.getContext('2d', { willReadFrequently: true });
            const guideEl = document.getElementById('fsGuideText');

            fsQrInterval = setInterval(async () => {
                if (fsIsProcessing) return;
                if (!video || video.readyState < 2 || video.paused) return;
                const vw = video.videoWidth, vh = video.videoHeight;
                if (!vw || !vh) return;

                // Engine 1: Native BarcodeDetector (fastest)
                if (fsNativeDetector) {
                    try {
                        const barcodes = await fsNativeDetector.detect(video);
                        if (barcodes && barcodes.length > 0 && barcodes[0].rawValue) {
                            if (guideEl) guideEl.textContent = 'QR terdeteksi! Memverifikasi...';
                            fsScanSuccess(barcodes[0].rawValue);
                            return;
                        }
                    } catch (e) { }
                }

                // Engine 2: jsQR with contrast boost
                if (typeof window.jsQR !== 'function') return;
                canvas.width = vw;
                canvas.height = vh;
                ctx.drawImage(video, 0, 0, vw, vh);
                try {
                    const imgData = ctx.getImageData(0, 0, vw, vh);
                    const code = window.jsQR(imgData.data, vw, vh, { inversionAttempts: 'attemptBoth' });
                    if (code && code.data) {
                        if (guideEl) guideEl.textContent = 'QR terdeteksi! Memverifikasi...';
                        fsScanSuccess(code.data);
                        return;
                    }
                    // Contrast boost for glare
                    const d = new Uint8ClampedArray(imgData.data);
                    for (let i = 0; i < d.length; i += 4) {
                        const g = 0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2];
                        const b = g < 128 ? 0 : 255;
                        d[i] = d[i + 1] = d[i + 2] = b;
                    }
                    const code2 = window.jsQR(d, vw, vh, { inversionAttempts: 'attemptBoth' });
                    if (code2 && code2.data) {
                        if (guideEl) guideEl.textContent = 'QR terdeteksi! Memverifikasi...';
                        fsScanSuccess(code2.data);
                        return;
                    }
                    if (guideEl) guideEl.textContent = 'Arahkan QR Code ke dalam bingkai';
                } catch (e) { }
            }, 80);
        }

        function stopFsQrLoop() {
            if (fsQrInterval) { clearInterval(fsQrInterval); fsQrInterval = null; }
        }

        function fsScanSuccess(rawCode) {
            if (fsIsProcessing) return;
            fsIsProcessing = true;
            stopFsQrLoop();

            const clean = rawCode.trim();
            const headers = {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            };
            if (currentGatePin) headers['X-Gate-Pin'] = currentGatePin;

            showFsScanCard({
                status: 'loading',
                title: 'Memverifikasi Tiket...',
                ticket: null
            });

            fetch(VERIFY_URL, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ code: clean, gate_pin: currentGatePin })
            })
            .then(r => r.json())
            .then(data => {
                const sType = data.status === 'valid' ? 'success' : (data.status === 'used' ? 'used' : 'invalid');
                playSound(sType);
                triggerHaptic(sType);
                showFsScanCard(data);
            })
            .catch(() => {
                playSound('invalid');
                triggerHaptic('invalid');
                showFsScanCard({
                    status: 'invalid',
                    title: 'Koneksi Terganggu',
                    message: 'Tidak dapat menghubungi server gate.'
                });
            });
        }

        function showFsScanCard(data) {
            const card = document.getElementById('fsScanResultCard');
            const statusBadge = document.getElementById('fsCardStatusBadge');
            const eventNameEl = document.getElementById('fsCardEventName');
            const eventDateEl = document.getElementById('fsCardEventDate');
            const holderEl = document.getElementById('fsCardHolderName');
            const codeEl = document.getElementById('fsCardTicketCode');
            const nikEl = document.getElementById('fsCardNik');
            const timeEl = document.getElementById('fsCardTime');
            const imgWrap = document.getElementById('fsCardEventImgWrap');
            const nextBtn = document.getElementById('fsCardNextBtn');
            const nextLabel = document.getElementById('fsCardNextLabel');
            const nextIcon = document.getElementById('fsCardNextIcon');
            if (!card) return;

            if (data.status === 'loading') {
                card.classList.add('show');
                if (statusBadge) {
                    statusBadge.style.background = '#f8fafc';
                    statusBadge.style.color = '#64748b';
                    statusBadge.style.border = '1px solid #e2e8f0';
                    statusBadge.innerHTML = `<span style="animation:spin 1s linear infinite;display:inline-block;">⏳</span> Memverifikasi...`;
                }
                if (eventNameEl) eventNameEl.textContent = 'Sedang memeriksa tiket...';
                if (eventDateEl) eventDateEl.textContent = '';
                if (holderEl) holderEl.textContent = '—';
                if (codeEl) codeEl.textContent = '—';
                if (nikEl) nikEl.textContent = '—';
                if (timeEl) timeEl.textContent = '—';
                return;
            }

            const isValid = data.status === 'valid';
            const isUsed = data.status === 'used';

            if (statusBadge) {
                statusBadge.className = 'fs-card-status-badge ' + (isValid ? 'valid' : (isUsed ? 'used' : 'invalid'));
                if (isValid) {
                    statusBadge.style.background = '#dcfce7';
                    statusBadge.style.color = '#15803d';
                    statusBadge.style.border = '1.5px solid #86efac';
                    statusBadge.innerHTML = `<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> CHECK-IN BERHASIL`;
                } else if (isUsed) {
                    statusBadge.style.background = '#fef3c7';
                    statusBadge.style.color = '#b45309';
                    statusBadge.style.border = '1.5px solid #fde68a';
                    statusBadge.innerHTML = `<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> SUDAH CHECK-IN`;
                } else {
                    statusBadge.style.background = '#fee2e2';
                    statusBadge.style.color = '#b91c1c';
                    statusBadge.style.border = '1.5px solid #fca5a5';
                    statusBadge.innerHTML = `<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> TIKET TIDAK VALID`;
                }
            }

            if (imgWrap && data.ticket && data.ticket.event_image) {
                imgWrap.innerHTML = `<img src="${data.ticket.event_image}" class="fs-card-event-img" alt="Event">`;
            }

            if (eventNameEl) eventNameEl.textContent = (data.ticket && data.ticket.event_name) ? data.ticket.event_name : (data.title || 'Status Tiket');
            if (eventDateEl) eventDateEl.textContent = (data.ticket && data.ticket.event_date) ? data.ticket.event_date : (data.message || '');
            if (holderEl) holderEl.textContent = (data.ticket && data.ticket.holder_name) ? data.ticket.holder_name : (data.holder_name || '—');
            if (codeEl) {
                codeEl.textContent = (data.ticket && data.ticket.code) ? data.ticket.code : (data.code || '—');
                codeEl.style.color = isValid ? '#1eb349' : '#64748b';
            }
            if (nikEl) nikEl.textContent = (data.ticket && (data.ticket.holder_nik || data.ticket.holder_nik_masked)) ? (data.ticket.holder_nik || data.ticket.holder_nik_masked) : (data.holder_nik || data.holder_nik_masked || '—');
            if (timeEl) timeEl.textContent = (data.ticket && data.ticket.checked_in_at) ? data.ticket.checked_in_at : (isValid ? 'Baru saja' : '—');

            if (nextBtn) {
                nextBtn.className = 'fs-card-next-btn' + (!isValid ? ' error-btn' : '');
            }
            if (nextLabel) nextLabel.textContent = isValid ? 'Scan Berikutnya' : 'Tutup';
            if (nextIcon) nextIcon.style.display = isValid ? 'block' : 'none';

            card.classList.add('show');

            if (isValid) {
                setTimeout(() => closeFsScanCard(), 3500);
            }
        }

        function closeFsScanCard() {
            const card = document.getElementById('fsScanResultCard');
            if (card) card.classList.remove('show');
            fsIsProcessing = false;
            const overlay = document.getElementById('fullscreenScannerOverlay');
            if (overlay && overlay.classList.contains('active')) {
                setTimeout(() => startFsQrLoop(), 400);
            }
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
