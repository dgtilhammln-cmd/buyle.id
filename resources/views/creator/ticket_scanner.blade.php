@extends('creator.layout')
@section('title', 'Scan Tiket & Data Kehadiran · Creator Studio')
@section('page_title', 'Scan Tiket & Data Kehadiran')

@section('styles')
<style>
    /* ── Keyframes ── */
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
    /* ── Bright Light Gray Scrollbar ── */
    ::-webkit-scrollbar { width: 10px; height: 10px; }
    ::-webkit-scrollbar-button { display: none !important; width: 0 !important; height: 0 !important; }
    ::-webkit-scrollbar-corner { background: transparent !important; }
    ::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 2px solid #f8fafc; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    * { scrollbar-width: auto; scrollbar-color: #cbd5e1 #f8fafc; }

    .bio-layout {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
        font-family: 'Montserrat', sans-serif;
    }

    .bio-sidebar {
        width: 260px;
        flex-shrink: 0;
        background: #ffffff;
        border-radius: 20px;
        padding: 1.25rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1.5px solid #e2e8f0;
        position: sticky;
        top: 1.5rem;
    }

    .bio-content {
        flex: 1;
        min-width: 0;
        max-width: 100%;
    }

    /* ── TAB BUTTONS (Matching Bio Light Theme) ── */
    .tab-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1.1rem;
        border: 1.5px solid transparent;
        background: transparent;
        color: #64748b;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.83rem;
        font-weight: 600;
        border-radius: 14px;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s ease;
        margin-bottom: 0.35rem;
        text-decoration: none;
    }

    .tab-btn:hover {
        background: #f0fdf4;
        color: #1eb349;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #ffffff;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.3);
    }

    /* ── UNIFORM LIGHT CARDS ── */
    .prof-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        margin-bottom: 1.5rem;
        overflow: hidden;
        max-width: 100%;
        box-sizing: border-box;
    }

    .prof-card-head {
        padding: 1.25rem 1.5rem;
        border-bottom: 1.5px solid #f1f5f9;
        font-size: 0.92rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        background: #ffffff;
    }

    .form-body {
        padding: 1.5rem;
        max-width: 100%;
        box-sizing: border-box;
    }

    /* ── SCANNER 2-COLUMN WORKSPACE (COMPACT & PROPORTIONAL) ── */
    .scanner-workspace-grid {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 1.25rem;
        align-items: stretch;
        margin-top: 0.25rem;
    }

    @media (max-width: 991px) {
        .scanner-workspace-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }

    @media (max-width: 768px) {
        /* Khusus Mobile: Info hasil scan tampil sebagai pop up modal, bukan di bawah kamera */
        .scanner-data-col {
            display: none !important;
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

    /* ── SCANNER VIEWPORT (SHARP & PROPORTIONAL — NO BLUR) ── */
    #reader {
        width: 100% !important;
        max-width: 100% !important;
        border-radius: 18px !important;
        overflow: hidden !important;
        border: 2px dashed #bbf7d0 !important;
        background: #0f172a !important;
        padding: 0.65rem !important;
        text-align: center;
        margin: 0 !important;
        box-sizing: border-box !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    /* VIDEO: JANGAN pakai object-fit:cover / max-height ketat = penyebab BLUR */
    #reader video {
        width: 100% !important;
        height: auto !important;
        max-height: 420px !important;
        object-fit: contain !important;
        border-radius: 14px !important;
        display: block !important;
        margin: 0 auto !important;
    }

    /* Mirror mode toggle via class */
    #reader.mirror-on video {
        transform: scaleX(-1) !important;
    }

    #reader__scan_region {
        border-radius: 14px !important;
        overflow: hidden !important;
        background: #020617 !important;
    }

    #reader__scan_region img {
        max-height: 240px !important;
        object-fit: contain !important;
    }

    #reader__dashboard {
        padding: 0.6rem 0.5rem 0.25rem !important;
        background: #ffffff !important;
        border-radius: 14px !important;
        margin-top: 0.5rem !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    #reader select {
        height: 40px;
        padding: 0 2rem 0 0.85rem;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0 !important;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
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
        border-color: #1eb349 !important;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
    }

    #reader button {
        height: 40px;
        padding: 0 1.25rem !important;
        border-radius: 999px !important;
        background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
        border: none !important;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        color: #ffffff !important;
        cursor: pointer;
        margin: 0.35rem auto;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35) !important;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        text-decoration: none !important;
        max-width: 100% !important;
    }

    /* ── REAL-TIME SMART GUIDANCE OVERLAY (Pencahayaan & Jarak QR Presisi) ── */
    .smart-guide-banner {
        margin-bottom: 0.65rem;
        padding: 0.55rem 0.85rem;
        border-radius: 12px;
        font-size: 0.76rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.55rem;
        transition: all 0.25s ease;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }
    .smart-guide-banner.guide-dark {
        background: #fffbeb !important;
        border-color: #fde68a !important;
        color: #b45309 !important;
    }
    .smart-guide-banner.guide-glare {
        background: #fef2f2 !important;
        border-color: #fecaca !important;
        color: #b91c1c !important;
    }
    .smart-guide-banner.guide-far {
        background: #fff7ed !important;
        border-color: #fed7aa !important;
        color: #c2410c !important;
    }
    .smart-guide-banner.guide-close {
        background: #fff7ed !important;
        border-color: #fed7aa !important;
        color: #c2410c !important;
    }
    .smart-guide-banner.guide-optimal {
        background: #f0fdf4 !important;
        border-color: #86efac !important;
        color: #15803d !important;
    }

    /* ── SCANNER RESULT PANEL (RIGHT COLUMN COMPACT) ── */
    .scanner-result-panel {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.25rem;
        height: 100%;
        min-height: 360px;
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

    .form-input-code {
        height: 44px;
        padding: 0 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px 0 0 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.83rem;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        flex: 1;
        min-width: 0;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .form-input-code:focus {
        border-color: #1eb349;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
    }

    .btn-submit-scan {
        height: 44px;
        padding: 0 1.25rem;
        border-radius: 0 12px 12px 0;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.83rem;
        font-weight: 700;
        color: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.2);
    }

    /* ── FILTER PANEL ── */
    .filter-panel {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.15rem;
        margin-bottom: 1.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1.2fr 2.5fr;
        gap: 0.85rem;
        align-items: center;
    }

    .input-icon-wrap {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .input-icon-wrap svg.icon-left {
        position: absolute;
        left: 0.85rem;
        color: #64748b;
        pointer-events: none;
        flex-shrink: 0;
    }

    .filter-select, .filter-input {
        width: 100%;
        height: 44px;
        padding: 0 0.85rem 0 2.5rem;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.83rem;
        font-weight: 600;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        transition: all 0.2s;
        box-sizing: border-box;

        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%64748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.85rem center;
        background-size: 16px;
    }

    .filter-input {
        padding-right: 0.85rem;
        background-image: none;
    }

    .filter-select:focus, .filter-input:focus {
        border-color: #1eb349;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
        background-color: #ffffff;
    }

    .btn-search {
        height: 44px;
        padding: 0 1.25rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        border: none;
        color: #ffffff;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.83rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.25);
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .btn-search:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(30, 179, 73, 0.35);
    }

    .btn-reset {
        height: 44px;
        padding: 0 0.9rem;
        border-radius: 12px;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        color: #64748b;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .btn-reset:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    /* ── STATS GRID (UNIFORM THEME CARDS) ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
        border-color: #cbd5e1;
    }

    .stat-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.6rem;
    }

    .stat-icon-bubble {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0fdf4;
        color: #1eb349;
        border: 1px solid #bbf7d0;
        flex-shrink: 0;
    }

    .stat-title {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
    }

    .stat-val {
        font-size: 1.65rem;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.02em;
        color: #0f172a;
    }

    .stat-sub {
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.4rem;
        color: #64748b;
    }

    .pill-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 99px;
        font-size: 0.72rem;
        font-weight: 700;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    /* ── TABLE DESIGN ── */
    .table-container {
        width: 100%;
        overflow-x: auto;
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.83rem;
        text-align: left;
    }

    .custom-table th {
        background: #f8fafc;
        padding: 0.9rem 1.1rem;
        font-weight: 800;
        color: #475569;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .custom-table td {
        padding: 0.95rem 1.1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }

    .custom-table tr:last-child td {
        border-bottom: none;
    }

    .custom-table tr:hover {
        background: #f8fafc;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.8rem;
        border-radius: 99px;
        font-size: 0.74rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-used { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .badge-valid { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
    .badge-cancelled { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    .btn-action-sm {
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-toggle-checkin {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(30,179,73,0.25);
    }
    .btn-toggle-checkin:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(30,179,73,0.35);
    }

    .btn-toggle-undo {
        background: #ffffff;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }
    .btn-toggle-undo:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .btn-export {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        color: #0f172a;
        padding: 0.5rem 1.1rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-export:hover {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #1eb349;
    }

    .tab-text-full { display: inline-block; }
    .tab-text-mob { display: none; }

    /* ── RESPONSIVE MOBILE FIXES ── */
    @media (max-width: 991px) {
        .bio-layout { flex-direction: column; width: 100%; gap: 1.25rem; }
        .bio-sidebar { width: 100%; position: static; box-sizing: border-box; padding: 0.5rem; border-radius: 14px; background: #ffffff; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); margin-bottom: 0.5rem; }
        .bio-content { width: 100%; max-width: 100%; box-sizing: border-box; }
        .form-body { padding: 1.15rem 1rem; }

        .sidebar-info-card, .sidebar-tips-card {
            display: none !important;
        }

        .bio-tabs-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 3px;
            background: #f8fafc;
            padding: 3px;
            border-radius: 12px;
        }

        .tab-btn {
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0.45rem 0.2rem;
            gap: 3px;
            border-radius: 8px;
            margin-bottom: 0;
            width: 100%;
        }

        .tab-btn svg {
            width: 18px;
            height: 18px;
        }

        .tab-text-full { display: none !important; }
        .tab-text-mob { display: block !important; font-size: 0.65rem; font-weight: 600; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; }

        .filter-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .stat-grid {
            grid-template-columns: 1fr;
        }
        .prof-card-head {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .btn-export {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="bio-layout">

    {{-- SUB SIDEBAR NAV --}}
    <div class="bio-sidebar">
        <div class="sidebar-info-card" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 1.1rem; margin-bottom: 1.25rem;">
            <div style="font-size: 0.85rem; font-weight: 800; color: #0f172a; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.4rem;">
                <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                Gatekeeper Studio
            </div>
            <div style="font-size: 0.78rem; color: #64748b; line-height: 1.5; font-weight: 500;">
                Pemindaian live kamera & pemantauan data kehadiran pengunjung event secara real-time.
            </div>
        </div>

        <div class="bio-tabs-list">
            <button type="button" class="tab-btn {{ $activeTab === 'settings' ? 'active' : '' }}" onclick="switchTab('tab-settings', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg>
                <span class="tab-text-full">Pengaturan & e-KYC</span>
                <small class="tab-text-mob">e-KYC</small>
                @if(isset($kyc) && $kyc->status === 'verified')
                    <span style="margin-left:auto; font-size:0.68rem; font-weight:800; background:#dcfce7; color:#166534; padding:2px 7px; border-radius:999px;">Terverifikasi</span>
                @elseif(isset($kyc) && $kyc->status === 'pending')
                    <span style="margin-left:auto; font-size:0.68rem; font-weight:800; background:#fef3c7; color:#92400e; padding:2px 7px; border-radius:999px;">Review</span>
                @elseif(isset($kyc) && $kyc->status === 'rejected')
                    <span style="margin-left:auto; font-size:0.68rem; font-weight:800; background:#fee2e2; color:#991b1b; padding:2px 7px; border-radius:999px;">Ditolak</span>
                @else
                    <span style="margin-left:auto; font-size:0.68rem; font-weight:800; background:#f1f5f9; color:#475569; padding:2px 7px; border-radius:999px;">Wajib</span>
                @endif
            </button>
            @if(isset($kyc) && $kyc->status === 'verified')
            <button type="button" class="tab-btn {{ $activeTab === 'scanner' ? 'active' : '' }}" onclick="switchTab('tab-scanner', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                <span class="tab-text-full">Live QR Scanner</span>
                <small class="tab-text-mob">Live Scanner</small>
            </button>
            <button type="button" class="tab-btn {{ $activeTab === 'data' ? 'active' : '' }}" onclick="switchTab('tab-data', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="tab-text-full">Data Kehadiran & Event</span>
                <small class="tab-text-mob">Data Kehadiran</small>
            </button>
            <button type="button" class="tab-btn {{ $activeTab === 'gate' ? 'active' : '' }}" onclick="switchTab('tab-gate', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                <span class="tab-text-full">Link Gate Panitia</span>
                <small class="tab-text-mob">Gate Staff</small>
            </button>
            @endif
        </div>

        <div class="sidebar-tips-card" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 1rem; font-size: 0.78rem; color: #64748b; margin-top: 1.25rem;">
            <strong style="color: #0f172a; display: flex; align-items: center; gap: 5px; margin-bottom: 0.4rem;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1eb349" stroke-width="2"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1.3.5 2.6 1.5 3.5.8.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                Tips Verifikasi:
            </strong>
            • Izinkan akses kamera browser saat diminta.<br>
            • Pastikan pencahayaan layar pengunjung cukup.<br>
            • Anda dapat menandai check-in manual pada tab Data Kehadiran jika pengunjung tidak membawa QR Code.
        </div>
    </div>

    {{-- CONTENT AREA --}}
    <div class="bio-content">

        @if(session('success'))
            <div style="background:#f0fdf4; border:1.5px solid #bbf7d0; color:#166534; padding:0.9rem 1.25rem; border-radius:16px; font-weight:600; font-size:0.85rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#fef2f2; border:1.5px solid #fca5a5; color:#991b1b; padding:0.9rem 1.25rem; border-radius:16px; font-weight:600; font-size:0.85rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- TAB 0: PENGATURAN & VERIFIKASI e-KYC --}}
        @include('creator.partials.kyc_tab')

        {{-- NOTICE: Fitur Scanner hanya untuk creator yang belum terverifikasi DAN sedang membuka tab selain Pengaturan e-KYC --}}
        @if((!isset($kyc) || is_null($kyc) || $kyc->status !== 'verified') && $activeTab !== 'settings')
        <div id="tab-unverified-notice" class="payout-tab-pane" style="display: block;">
            <div style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1.5px solid #fcd34d; border-radius: 18px; padding: 1.75rem 1.5rem; text-align: center; margin-top: 0.5rem;">
                <div style="width: 56px; height: 56px; background: #fef3c7; border: 2px solid #fcd34d; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg width="28" height="28" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg>
                </div>
                <h3 style="font-size: 1rem; font-weight: 800; color: #92400e; margin: 0 0 0.5rem;">Verifikasi e-KYC Diperlukan</h3>
                <p style="font-size: 0.82rem; color: #b45309; line-height: 1.6; margin: 0 0 1.25rem;">Fitur <strong>Live QR Scanner</strong>, <strong>Data Kehadiran & Event</strong>, dan <strong>Link Gate Panitia</strong> hanya tersedia untuk Creator Buyle yang telah menyelesaikan verifikasi identitas (e-KYC).</p>
                <button type="button" onclick="switchTab('tab-settings', document.querySelector('.tab-btn'))" style="background: linear-gradient(135deg, #d97706, #b45309); color: #fff; border: none; padding: 0.65rem 1.5rem; border-radius: 10px; font-size: 0.85rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(217,119,6,0.3);">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg>
                    Lengkapi e-KYC Sekarang
                </button>
            </div>
        </div>
        @endif

        {{-- TAB 1: SCANNER (hanya untuk creator terverifikasi) --}}
        @if(isset($kyc) && $kyc->status === 'verified')
        <div id="tab-scanner" class="payout-tab-pane" style="display: {{ $activeTab === 'scanner' ? 'block' : 'none' }};">
            <div class="prof-card">
                <div class="prof-card-head">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                        Live Camera QR Ticket Scanner
                    </div>
                </div>

                <div class="form-body">
                    {{-- Input Manual (Full Width di Atas) --}}
                    <form id="manualScanForm" style="margin-bottom: 1.25rem;" onsubmit="handleManualSubmit(event)">
                        <label class="form-label" style="font-size:0.82rem; font-weight:700; color:#334155; margin-bottom:0.5rem; display:block;">Pemeriksaan Kode Tiket / Token (Manual)</label>
                        <div style="display: flex;">
                            <input type="text" id="manualCodeInput" class="form-input-code" placeholder="Ketik Kode Tiket (misal: TKT-20260905-XXXX)...">
                            <button class="btn-submit-scan" type="submit">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                                Periksa
                            </button>
                        </div>
                    </form>

                    {{-- Header & Camera Switch Control --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
                        {{-- Kiri: Label + Signal Widget --}}
                        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                            <div style="font-weight: 700; font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 6px;">
                                <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                                Viewport Live QR Scanner
                            </div>
                            {{-- Signal Strength Widget (dekat label kamera, tidak nabrak kontrol) --}}
                            <div id="signalWidget" title="Kekuatan Sinyal Internet" style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.72rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:999px; background:#f8fafc; border:1.5px solid #e2e8f0; cursor:default; user-select:none; transition:all 0.3s;">
                                <div id="signalBars" style="display:flex; align-items:flex-end; gap:1.5px; height:13px;">
                                    <div id="bar1" style="width:2.5px; height:3px; border-radius:1.5px; background:#cbd5e1;"></div>
                                    <div id="bar2" style="width:2.5px; height:5.5px; border-radius:1.5px; background:#cbd5e1;"></div>
                                    <div id="bar3" style="width:2.5px; height:8px; border-radius:1.5px; background:#cbd5e1;"></div>
                                    <div id="bar4" style="width:2.5px; height:10.5px; border-radius:1.5px; background:#cbd5e1;"></div>
                                    <div id="bar5" style="width:2.5px; height:13px; border-radius:1.5px; background:#cbd5e1;"></div>
                                </div>
                                <span id="signalLabel" style="color:#64748b;">Mengukur...</span>
                            </div>
                        </div>
                        {{-- Kanan: Fast Mode + Mirror --}}
                        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                            <label style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.78rem; font-weight:700; color:#166534; background:#f0fdf4; padding:0.4rem 0.85rem; border-radius:999px; border:1.5px solid #bbf7d0; cursor:pointer; user-select:none;" title="Scan cepat otomatis tanpa popup modal yang memblokir">
                                <input type="checkbox" id="autoScanToggle" checked style="accent-color:#1eb349; width:16px; height:16px;" onchange="handleFastModeChange(this.checked)">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                <span>Fast Mode (Auto Next)</span>
                            </label>
                            <button type="button" id="mirrorToggleBtn" onclick="toggleMirror()" style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.78rem; font-weight:700; color:#166534; background:#f0fdf4; padding:0.4rem 0.85rem; border-radius:999px; border:1.5px solid #bbf7d0; cursor:pointer; user-select:none; transition: all 0.2s;" title="Cerminkan tampilan kamera (mirror) untuk webcam desktop">
                                <svg id="mirrorIcon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3M16 3h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-3M12 2v20"/><path d="M8 12h-.01M16 12h.01"/></svg>
                                <span id="mirrorLabel">Mirror: ON</span>
                            </button>
                        </div>
                    </div>

                    {{-- 2-COLUMN WORKSPACE: KIRI KAMERA, KANAN HASIL SCAN (COMPACT & PROPORTIONAL) --}}
                    <div class="scanner-workspace-grid">
                        {{-- Kolom Kiri: Kamera Viewport --}}
                        <div class="scanner-camera-col">
                            {{-- Smart Real-Time Video Guidance Banner (Pencahayaan & Jarak QR Presisi) --}}
                            <div id="smartGuideBanner" class="smart-guide-banner">
                                <svg id="smartGuideIcon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                                <span id="smartGuideText" style="flex:1;">Pencahayaan Baik: Arahkan QR Code ke kamera</span>
                            </div>
                            <div id="reader"></div>
                        </div>

                        {{-- Kolom Kanan: Live Data Hasil Scan --}}
                        <div class="scanner-data-col">
                            <div id="scannerResultPanel" class="scanner-result-panel">
                                
                                {{-- 1. State Idle (Menunggu Scan) --}}
                                <div id="resStateIdle" style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 1.25rem 0.75rem;">
                                    <div style="width: 64px; height: 64px; border-radius: 20px; background: #f0fdf4; border: 1.5px solid #bbf7d0; display: flex; align-items: center; justify-content: center; color: #1eb349; margin-bottom: 1rem; box-shadow: 0 8px 20px rgba(30, 179, 73, 0.12);">
                                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1.5"/></svg>
                                    </div>
                                    <div style="display: inline-flex; align-items: center; gap: 6px; padding: 0.25rem 0.75rem; border-radius: 999px; background: #e0f2fe; color: #0369a1; font-size: 0.73rem; font-weight: 700; margin-bottom: 0.65rem;">
                                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #0284c7; display: inline-block;"></span>
                                        Kamera Siap Memindai
                                    </div>
                                    <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem 0;">Menunggu Scan Tiket</h4>
                                    <p style="font-size: 0.8rem; color: #64748b; line-height: 1.5; margin: 0 0 1.25rem 0; max-width: 300px;">
                                        Arahkan QR Code pengunjung ke kamera di sebelah kiri, atau ketik kode tiket pada kolom di atas.
                                    </p>
                                    <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 0.75rem 0.85rem; width: 100%; box-sizing: border-box; text-align: left;">
                                        <div style="font-size: 0.74rem; font-weight: 700; color: #334155; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 5px;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1eb349" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                            Tips Verifikasi Cepat:
                                        </div>
                                        <div style="font-size: 0.73rem; color: #64748b; line-height: 1.4;">
                                            Posisikan layar pengunjung lurus dengan kamera. Tiket valid akan langsung tercatat dan berbunyi lonceng sukses.
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. State Loading --}}
                                <div id="resStateLoading" style="display: none; flex-direction: column; align-items: center; text-align: center; padding: 2.5rem 1rem;">
                                    <svg width="44" height="44" stroke="#1eb349" stroke-width="2.5" fill="none" viewBox="0 0 24 24" style="animation:spin 1s linear infinite; margin-bottom: 1rem;">
                                        <circle cx="12" cy="12" r="10" stroke-dasharray="31" stroke-dashoffset="10"/>
                                    </svg>
                                    <h4 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 0.35rem;">Memverifikasi Tiket...</h4>
                                    <p id="resLoadingCode" style="font-size: 0.82rem; color: #64748b; font-family: monospace;"></p>
                                </div>

                                {{-- 3. State Active Result (Muncul Data Saat Scan) --}}
                                <div id="resStateActive" style="display: none; flex-direction: column; justify-content: space-between; height: 100%;">
                                    <div>
                                        {{-- Status Banner --}}
                                        <div id="resActiveBanner" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-radius: 14px; margin-bottom: 0.85rem;">
                                            <div id="resActiveIcon" style="flex-shrink: 0;"></div>
                                            <div style="flex: 1; min-width: 0;">
                                                <div id="resActiveStatusTag" style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;"></div>
                                                <h4 id="resActiveTitle" style="font-size: 0.96rem; font-weight: 800; margin: 0; line-height: 1.25;"></h4>
                                            </div>
                                        </div>

                                        {{-- Message --}}
                                        <p id="resActiveMsg" style="font-size: 0.8rem; color: #475569; line-height: 1.45; margin: 0 0 0.85rem 0;"></p>

                                        {{-- Ticket Details Card --}}
                                        <div id="resActiveDetailsCard" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 0.85rem 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; flex-direction: column; gap: 0.6rem;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed #f1f5f9; padding-bottom: 0.45rem;">
                                                <span style="font-size: 0.74rem; font-weight: 600; color: #64748b;">Kode Tiket</span>
                                                <code id="resActiveCode" style="font-size: 0.83rem; font-weight: 700; color: #166534; background: #f0fdf4; padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid #bbf7d0;">-</code>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem;">
                                                <span style="font-size: 0.74rem; font-weight: 600; color: #64748b; flex-shrink: 0;">Event</span>
                                                <span id="resActiveEvent" style="font-size: 0.78rem; font-weight: 700; color: #334155; text-align: right;">-</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Footer Controls & Auto Next Indicator --}}
                                    <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                                        <span id="resActiveNextIndicator" style="font-size: 0.74rem; color: #166534; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                                            <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #16a34a;"></span>
                                            Siap scan berikutnya
                                        </span>
                                        <button type="button" onclick="resetToIdleState()" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 0.73rem; font-weight: 700; color: #475569; border-radius: 999px; padding: 0.3rem 0.75rem; cursor: pointer; transition: all 0.2s;">
                                            Reset View
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: DATA KEHADIRAN & EVENT --}}
        <div id="tab-data" class="payout-tab-pane" style="display: {{ $activeTab === 'data' ? 'block' : 'none' }};">
            <div class="prof-card">
                <div class="prof-card-head">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Data Kehadiran Pengunjung & Rekap Event
                    </div>
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <span id="cacheStatusBadge" style="display:inline-flex; align-items:center; gap:4px; font-size:0.74rem; font-weight:700; color:#166534; background:#f0fdf4; border:1px solid #bbf7d0; padding:0.25rem 0.65rem; border-radius:999px;">
                            <span style="width:6px; height:6px; background:#16a34a; border-radius:50%; display:inline-block;"></span>
                            Cache Cepat Aktif
                        </span>
                        <a href="{{ route('creator.ticket.scanner.export', request()->all()) }}" class="btn-export">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Export Excel/CSV
                        </a>
                    </div>
                </div>

                <div class="form-body">

                    {{-- Modern Instant Filter Panel (0ms Latency & Offline Cache) --}}
                    <div class="filter-panel">
                        <div class="filter-grid">
                            {{-- Event Selector --}}
                            <div class="input-icon-wrap">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="icon-left"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <select id="instantEventFilter" class="filter-select" onchange="runInstantFilter()">
                                    <option value="">— Semua Event Ticket —</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" {{ (string)$selectedEventId === (string)$event->id ? 'selected' : '' }}>
                                            {{ $event->name }} ({{ $event->event_date ? $event->event_date->format('d M Y') : 'Event' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Filter --}}
                            <div class="input-icon-wrap">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="icon-left"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <select id="instantStatusFilter" class="filter-select" onchange="runInstantFilter()">
                                    <option value="">Semua Status</option>
                                    <option value="used" {{ $statusFilter === 'used' ? 'selected' : '' }}>Checked-In (Hadir)</option>
                                    <option value="valid" {{ $statusFilter === 'valid' ? 'selected' : '' }}>Belum Hadir</option>
                                    <option value="cancelled" {{ $statusFilter === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            {{-- Search Input (0ms Live Filter, Tanpa Loading Page) --}}
                            <div style="display: flex; gap: 0.5rem; width: 100%;">
                                <div class="input-icon-wrap" style="flex: 1;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="icon-left"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                                    <input type="text" id="instantSearchInput" value="{{ $search }}" class="filter-input" placeholder="Ketik nama, email, no HP, kode (instan 0ms)..." oninput="runInstantFilter()" autocomplete="off">
                                </div>
                                
                                <button type="button" onclick="resetInstantFilter()" class="btn-reset" title="Reset Pencarian & Filter">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Clean Buyle Theme Stat Cards Grid --}}
                    <div class="stat-grid">
                        {{-- Card 1: Total Tiket --}}
                        <div class="stat-card">
                            <div class="stat-head">
                                <span class="stat-title">Total Tiket Terbit</span>
                                <div class="stat-icon-bubble">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/><path d="M13 5v14"/></svg>
                                </div>
                            </div>
                            <div class="stat-val" id="statTotalVal">{{ number_format($stats['total']) }}</div>
                            <div class="stat-sub">
                                <span>Tiket Terjual / Terdaftar</span>
                            </div>
                        </div>

                        {{-- Card 2: Checked-In --}}
                        <div class="stat-card">
                            <div class="stat-head">
                                <span class="stat-title">Checked-In (Hadir)</span>
                                <div class="stat-icon-bubble">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </div>
                            </div>
                            <div class="stat-val" id="statCheckedInVal">{{ number_format($stats['checked_in']) }}</div>
                            <div class="stat-sub">
                                <span class="pill-badge" id="statCheckedInRate">Tingkat Kehadiran: {{ $stats['rate'] }}%</span>
                            </div>
                        </div>

                        {{-- Card 3: Belum Check-In --}}
                        <div class="stat-card">
                            <div class="stat-head">
                                <span class="stat-title">Belum Check-In</span>
                                <div class="stat-icon-bubble">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </div>
                            </div>
                            <div class="stat-val" id="statUncheckedVal">{{ number_format($stats['unchecked']) }}</div>
                            <div class="stat-sub">
                                <span>Tiket Belum Dipindai</span>
                            </div>
                        </div>

                        {{-- Card 4: Dibatalkan --}}
                        <div class="stat-card">
                            <div class="stat-head">
                                <span class="stat-title">Tiket Dibatalkan</span>
                                <div class="stat-icon-bubble" style="background:#fef2f2; color:#dc2626; border-color:#fecaca;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                </div>
                            </div>
                            <div class="stat-val" id="statCancelledVal">{{ number_format($stats['cancelled']) }}</div>
                            <div class="stat-sub">
                                <span>Refund / Cancelled</span>
                            </div>
                        </div>
                    </div>

                    {{-- Table Attendance --}}
                    <div class="table-container">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Kode Tiket</th>
                                    <th>Nama Event</th>
                                    <th>Pemegang Tiket</th>
                                    <th>Status Kehadiran</th>
                                    <th>Waktu Check-In</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTableBody">
                                @forelse($tickets as $index => $ticket)
                                    <tr>
                                        <td>{{ $tickets->firstItem() + $index }}</td>
                                        <td>
                                            <span style="font-family: monospace; font-weight: 700; font-size: 0.85rem; color: #0f172a; background: #f8fafc; padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid #e2e8f0;">
                                                {{ $ticket->ticket_code }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong style="color: #0f172a; display: block; font-size: 0.84rem;">
                                                {{ $ticket->product?->name ?? 'Event' }}
                                            </strong>
                                            <span style="font-size: 0.73rem; color: #64748b;">
                                                {{ $ticket->product?->event_date ? $ticket->product->event_date->format('d M Y') : '' }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong style="color: #0f172a; display: block; font-size: 0.85rem;">{{ $ticket->holder_name ?? '-' }}</strong>
                                            <span style="font-size: 0.74rem; color: #64748b; display: block;">{{ $ticket->holder_email ?? '-' }}</span>
                                            <span style="font-size: 0.74rem; color: #64748b;">{{ $ticket->holder_phone ?? '' }}</span>
                                            @if($ticket->holder_nik)
                                                <span style="display:inline-flex;align-items:center;gap:3px;font-size:0.71rem;color:#0369a1;background:#e0f2fe;border:1px solid #bae6fd;padding:1px 6px;border-radius:4px;font-family:monospace;margin-top:2px;">
                                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                                    {{ \App\Models\TicketPass::maskNik($ticket->holder_nik) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ticket->status === 'used')
                                                <span class="badge-status badge-used">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline-block;vertical-align:-1px;margin-right:2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    Hadir (Checked-In)
                                                </span>
                                            @elseif($ticket->status === 'cancelled')
                                                <span class="badge-status badge-cancelled">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline-block;vertical-align:-1px;margin-right:2px;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                    Dibatalkan
                                                </span>
                                            @else
                                                <span class="badge-status badge-valid">
                                                    Belum Hadir
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ticket->checked_in_at)
                                                <span style="font-weight: 700; color: #166534; display: block; font-size: 0.8rem;">
                                                    {{ $ticket->checked_in_at->format('H:i:s WIB') }}
                                                </span>
                                                <span style="font-size: 0.73rem; color: #64748b;">
                                                    {{ $ticket->checked_in_at->format('d M Y') }}
                                                </span>
                                                @if($ticket->scanned_by_staff_name)
                                                    <span style="font-size:0.71rem; color:#475569; display:flex; align-items:center; gap:3px; margin-top:2px; font-weight:600;">
                                                        <svg width="10" height="10" fill="none" stroke="#10b981" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                        {{ $ticket->scanned_by_staff_name }} · Gate {{ $ticket->scanned_at_gate }}
                                                    </span>
                                                @elseif($ticket->checked_in_by)
                                                    <span style="font-size:0.71rem; color:#64748b; display:flex; align-items:center; gap:3px; margin-top:2px;">
                                                        Creator Studio
                                                    </span>
                                                @endif
                                            @else
                                                <span style="color: #94a3b8; font-style: italic; font-size: 0.8rem;">— Belum —</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if($ticket->status !== 'cancelled')
                                                <form action="{{ route('creator.ticket.scanner.toggle-checkin', $ticket->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @if($ticket->status === 'used')
                                                        <button type="submit" class="btn-action-sm btn-toggle-undo" onclick="return confirm('Batalkan status check-in tiket ini?')">
                                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-2px;margin-right:3px;"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/></svg> Batalkan
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn-action-sm btn-toggle-checkin">
                                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline-block;vertical-align:-1px;margin-right:2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                            Check-In
                                                        </button>
                                                    @endif
                                                </form>
                                            @else
                                                <span style="font-size: 0.75rem; color: #94a3b8;">Tidak Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 3rem 1rem; color: #94a3b8;">
                                            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 0.75rem; opacity: 0.4;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                            <div style="font-weight: 700; color: #334155; margin-bottom: 0.25rem; font-size: 0.95rem;">Belum Ada Data Tiket / Kehadiran</div>
                                            <div style="font-size: 0.8rem; color: #64748b;">Tiket pembeli yang sudah lunas akan otomatis tercantum pada daftar presensi ini.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($tickets->hasPages())
                        <div style="margin-top: 1.25rem;">
                            {{ $tickets->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- TAB 3: GATE SCANNER ACCESS FOR CREW/STAFF (SERAGAM TEMA DASHBOARD & NO EMOTICONS) --}}
        <div id="tab-gate" class="payout-tab-pane" style="display: {{ $activeTab === 'gate' ? 'block' : 'none' }};">
            <div class="prof-card">
                <div class="prof-card-head" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem; font-weight:800; font-size:1rem; color:#0f172a;">
                        <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                        Akses Scanner Panitia (Gate Staff Pass)
                    </div>
                </div>

                <div class="prof-card-body" style="padding:1rem 1.5rem;">

                    @if($events->count() === 0)
                        <div style="text-align:center; padding:2rem 1rem; color:#94a3b8;">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:0.5rem;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <div style="font-weight:600; color:#334155; font-size:0.9rem;">Belum Ada Event Tiket</div>
                            <p style="font-size:0.8rem; margin-top:0.2rem;">Buat produk bertipe tiket untuk mengaktifkan Gate Scanner.</p>
                        </div>
                    @else
                        <div style="display:flex; flex-direction:column; gap:0.85rem;">
                            @foreach($events as $ev)
                                @php
                                    $gateKey = $ev->getOrGenerateGateKey();
                                    $gateUrl = route('gate.scanner', ['key' => $gateKey]);
                                    $qrGateUrl = route('qr.code', ['data' => $gateUrl]);
                                    $gStatus = $ev->getGateStatus();
                                @endphp
                                <div class="gate-event-card" id="gate-card-{{ $ev->id }}" style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:0.9rem; box-shadow:0 2px 8px rgba(0,0,0,0.02);">
                                    
                                    {{-- Row 1: Title + meta + status + open button --}}
                                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.65rem;">
                                        <div>
                                            <h3 style="font-weight:600; font-size:0.95rem; color:#0f172a; margin:0 0 0.15rem;">{{ $ev->name }}</h3>
                                            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; font-size:0.74rem; color:#64748b;">
                                                <span style="display:inline-flex; align-items:center; gap:0.25rem;">
                                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                    {{ $ev->event_date ? $ev->event_date->format('d M Y') : 'Jadwal belum diset' }}
                                                </span>
                                                @if($ev->event_location)
                                                <span style="display:inline-flex; align-items:center; gap:0.25rem;">
                                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                                    {{ $ev->event_location }}
                                                </span>
                                                @endif
                                                <span id="gate-status-badge-{{ $ev->id }}" class="badge-status {{ $gStatus['is_open'] ? 'badge-used' : 'badge-cancelled' }}">{{ $gStatus['reason'] }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ $gateUrl }}" target="_blank" style="display:inline-flex; align-items:center; gap:0.3rem; background:#0f172a; color:#fff; padding:0.45rem 0.8rem; border-radius:8px; font-size:0.76rem; font-weight:600; text-decoration:none; white-space:nowrap; flex-shrink:0;">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                            Buka Layar Panitia
                                        </a>
                                    </div>

                                    {{-- Row 2: Link Scanner --}}
                                    <div style="display:flex; gap:0.4rem; align-items:center; margin-bottom:0.65rem; flex-wrap:wrap;">
                                        <input type="text" id="gate-url-{{ $ev->id }}" value="{{ $gateUrl }}" readonly style="flex:1; min-width:180px; padding:0.45rem 0.65rem; font-size:0.77rem; font-weight:500; color:#334155; background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; font-family:monospace; outline:none;" onclick="this.select()">
                                        <button type="button" onclick="copyGateUrl('gate-url-{{ $ev->id }}', this)" style="display:inline-flex; align-items:center; gap:0.3rem; background:#1eb349; color:#fff; border:none; padding:0.45rem 0.8rem; border-radius:8px; font-size:0.76rem; font-weight:600; cursor:pointer; white-space:nowrap;">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                            Salin Link
                                        </button>
                                        <button type="button" onclick="showGateQrModal('{{ $ev->name }}', '{{ $gateUrl }}', '{{ $qrGateUrl }}')" style="display:inline-flex; align-items:center; gap:0.3rem; background:#fff; color:#334155; border:1px solid #cbd5e1; padding:0.45rem 0.8rem; border-radius:8px; font-size:0.76rem; font-weight:600; cursor:pointer; white-space:nowrap;">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                            QR Link
                                        </button>
                                    </div>

                                    {{-- Row 3: PIN + Jadwal inline satu baris --}}
                                    <div style="display:flex; gap:0.65rem; flex-wrap:wrap; align-items:flex-start; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:0.7rem; margin-bottom:0.55rem;">

                                        {{-- PIN Box --}}
                                        <div style="flex:1; min-width:170px;">
                                            <div style="font-size:0.71rem; font-weight:600; color:#475569; margin-bottom:0.25rem; display:flex; align-items:center; gap:0.25rem;">
                                                <svg width="11" height="11" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                                PIN Gate
                                            </div>
                                            <div style="display:flex; gap:0.3rem;">
                                                <input type="text" id="gate-pin-{{ $ev->id }}" value="{{ $ev->gate_scanner_pin }}" placeholder="Kosong = tanpa PIN" style="flex:1; padding:0.4rem 0.55rem; font-size:0.78rem; font-weight:400; border:1px solid #cbd5e1; border-radius:7px; outline:none; background:#fff; min-width:0;">
                                                <button type="button" onclick="saveGatePin('{{ $ev->id }}', this)" style="background:#0f172a; color:#fff; border:none; padding:0.4rem 0.65rem; border-radius:7px; font-size:0.73rem; font-weight:600; cursor:pointer; white-space:nowrap;">Simpan</button>
                                            </div>
                                        </div>

                                        <div style="width:1px; background:#e2e8f0; align-self:stretch; flex-shrink:0;"></div>

                                        {{-- Jadwal Box --}}
                                        <div style="flex:1; min-width:200px;">
                                            <div style="font-size:0.71rem; font-weight:600; color:#475569; margin-bottom:0.25rem; display:flex; align-items:center; gap:0.25rem;">
                                                <svg width="11" height="11" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                Jadwal Gate
                                            </div>
                                            <div style="display:flex; gap:0.3rem; align-items:center; flex-wrap:wrap;">
                                                <div style="display:flex; align-items:center; gap:0.2rem;">
                                                    <span style="font-size:0.71rem; color:#64748b; font-weight:500;">Buka:</span>
                                                    <input type="time" id="gate-open-{{ $ev->id }}" value="{{ $ev->gate_open_time }}" style="padding:0.38rem 0.45rem; font-size:0.78rem; font-weight:400; border:1px solid #cbd5e1; border-radius:7px; outline:none; background:#fff;">
                                                </div>
                                                <div style="display:flex; align-items:center; gap:0.2rem;">
                                                    <span style="font-size:0.71rem; color:#64748b; font-weight:500;">Tutup:</span>
                                                    <input type="time" id="gate-close-{{ $ev->id }}" value="{{ $ev->gate_close_time }}" style="padding:0.38rem 0.45rem; font-size:0.78rem; font-weight:400; border:1px solid #cbd5e1; border-radius:7px; outline:none; background:#fff;">
                                                </div>
                                                <button type="button" onclick="saveGateSchedule('{{ $ev->id }}', this)" style="background:#1eb349; color:#fff; border:none; padding:0.38rem 0.65rem; border-radius:7px; font-size:0.73rem; font-weight:600; cursor:pointer;">Simpan</button>
                                            </div>
                                            <div style="display:flex; align-items:center; gap:0.5rem; margin-top:0.35rem; flex-wrap:wrap;">
                                                <button type="button" onclick="quickExtendGate('{{ $ev->id }}', this)" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; padding:0.22rem 0.5rem; border-radius:5px; font-size:0.7rem; font-weight:500; cursor:pointer; display:inline-flex; align-items:center; gap:0.2rem;">
                                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                    +30 Menit
                                                </button>
                                                <label style="display:inline-flex; align-items:center; gap:0.2rem; cursor:pointer; font-size:0.7rem; font-weight:500; color:#d97706;">
                                                    <input type="checkbox" onchange="toggleGateBypass('{{ $ev->id }}', this)" {{ $ev->gate_bypass_time ? 'checked' : '' }} style="accent-color:#d97706; width:12px; height:12px;">
                                                    Bypass Jam
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Row 3.5: Daftar Panitia Gate --}}
                                    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:0.75rem 0.9rem; margin-bottom:0.6rem;">
                                        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.5rem;">
                                            <div style="font-size:0.76rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:0.35rem;">
                                                <svg width="13" height="13" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                                Panitia Gate (<span id="staff-count-{{ $ev->id }}">0</span>/10)
                                            </div>
                                            <button type="button" onclick="openAddStaffModal('{{ $ev->id }}', '{{ addslashes($ev->name) }}')" style="display:inline-flex; align-items:center; gap:0.25rem; background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; font-size:0.72rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:6px; cursor:pointer;">
                                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                                + Tambah Panitia
                                            </button>
                                        </div>
                                        <div id="staff-list-container-{{ $ev->id }}" style="display:flex; flex-direction:column; gap:0.35rem;">
                                            <div style="font-size:0.73rem; color:#94a3b8; font-style:italic;">Memuat panitia...</div>
                                        </div>
                                    </div>

                                    {{-- Row 4: Footer Controls --}}
                                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.4rem; padding-top:0.45rem; border-top:1px dashed #e2e8f0;">
                                        <label style="display:inline-flex; align-items:center; gap:0.3rem; cursor:pointer; font-size:0.76rem; font-weight:500; color:#334155;">
                                            <input type="checkbox" onchange="toggleGateEnabled('{{ $ev->id }}', this)" {{ $ev->gate_scanner_enabled ? 'checked' : '' }} style="accent-color:#1eb349; width:13px; height:13px;">
                                            Akses Gate Scanner Aktif
                                        </label>
                                        <button type="button" onclick="resetGateKey('{{ $ev->id }}')" style="background:none; border:none; color:#ef4444; font-size:0.73rem; font-weight:500; cursor:pointer; display:inline-flex; align-items:center; gap:0.2rem; padding:0;">
                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                            Reset Kunci
                                        </button>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
        @endif {{-- end: verified only tabs --}}

    </div> {{-- Close .bio-content --}}

</div> {{-- Close .bio-layout --}}

{{-- POPUP MODAL RESULT SCANNER (KHUSUS MOBILE: INFO POP UP BUKAN DI BAWAH & TOMBOL KECIL NEXT) --}}
<div id="scanResultModal" onclick="if(event.target===this)closeScanModal()" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.72); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:1rem; opacity:0; transition:all 0.25s ease;">
    <div id="scanResultModalBox" style="background:#ffffff; border-radius:22px; padding:1.35rem 1.25rem; width:100%; max-width:350px; text-align:center; box-shadow:0 25px 50px -12px rgba(0,0,0,0.35); transform:scale(0.92); transition:all 0.25s cubic-bezier(0.34,1.56,0.64,1);">
        
        {{-- Icon Bubble --}}
        <div id="modalIconBubble" style="width:54px; height:54px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 0.85rem;"></div>

        {{-- Status Title & Message --}}
        <h3 id="modalTitle" style="font-size:1.18rem; font-weight:800; margin:0 0 0.25rem; font-family:'Montserrat',sans-serif;"></h3>
        <p id="modalMsg" style="font-size:0.8rem; color:#64748B; margin:0 0 0.85rem; line-height:1.45;"></p>

        {{-- Ticket Detail Card --}}
        <div id="modalDetailsCard" style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:0.85rem; text-align:left; margin-bottom:1.15rem; font-size:0.82rem; display:none;"></div>

        {{-- Action Button (Khusus Mobile: Button Kecil Next Saat Scan Berhasil) --}}
        <div style="display:flex; justify-content:center; align-items:center;">
            <button type="button" id="modalNextBtn" onclick="closeScanModal()" style="display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:0.45rem 1.35rem; background:linear-gradient(135deg, #1eb349, #15803d); color:#fff; border:none; border-radius:999px; font-weight:700; font-size:0.82rem; cursor:pointer; box-shadow:0 3px 12px rgba(30,179,73,0.3); transition:all 0.15s ease;">
                <span id="modalBtnText">Next</span>
                <svg id="modalBtnIcon" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>
    </div>
</div>

{{-- MODAL QR CODE UNTUK LINK PANITIA --}}
<div id="gateQrModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:1rem;" onclick="if(event.target===this) closeGateQrModal()">
    <div style="background:#ffffff; border-radius:20px; padding:2rem; max-width:380px; width:100%; text-align:center; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="font-weight:800; font-size:1.15rem; color:#0f172a; margin-bottom:0.25rem;">Scan untuk Buka Scanner Panitia</div>
        <div id="gateQrEventTitle" style="font-size:0.85rem; color:#64748b; margin-bottom:1.25rem; font-weight:600;"></div>
        <div style="background:#ffffff; padding:0.75rem; border:2px solid #e2e8f0; border-radius:16px; display:inline-block; margin-bottom:1.25rem;">
            <img id="gateQrImg" src="" alt="QR Gate Link" style="width:200px; height:200px; display:block;">
        </div>
        <p style="font-size:0.78rem; color:#64748b; line-height:1.5; margin-bottom:1.5rem;">
            Arahkan kamera HP panitia ke QR Code di layar ini untuk langsung membuka scanner tanpa harus login atau memasukkan password.
        </p>
        <button type="button" onclick="closeGateQrModal()" style="width:100%; padding:0.75rem; background:#0f172a; color:#ffffff; border:none; border-radius:999px; font-weight:700; font-size:0.88rem; cursor:pointer;">
            Tutup
        </button>
    </div>
</div>

{{-- MODAL TAMBAH PANITIA GATE --}}
<div id="addStaffModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:1rem;" onclick="if(event.target===this) closeAddStaffModal()">
    <div style="background:#ffffff; border-radius:20px; padding:1.75rem 1.5rem; max-width:400px; width:100%; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
            <div>
                <h3 style="font-weight:800; font-size:1.05rem; color:#0f172a; margin:0 0 0.15rem;">Tambah Panitia Gate</h3>
                <span id="addStaffEventName" style="font-size:0.76rem; color:#64748b; font-weight:600;"></span>
            </div>
            <button type="button" onclick="closeAddStaffModal()" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1.2rem; line-height:1;">✕</button>
        </div>
        <form onsubmit="submitAddStaff(event)">
            <input type="hidden" id="addStaffEventId">
            <div style="margin-bottom:0.85rem;">
                <label style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:0.35rem;">Nama Panitia:</label>
                <input type="text" id="addStaffName" placeholder="Contoh: Budi Santoso" required style="width:100%; box-sizing:border-box; padding:0.6rem 0.75rem; font-size:0.85rem; border:1px solid #cbd5e1; border-radius:8px; outline:none;">
            </div>
            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:0.35rem;">Nomor Gate Penugasan:</label>
                <select id="addStaffGate" style="width:100%; box-sizing:border-box; padding:0.6rem 0.75rem; font-size:0.85rem; border:1px solid #cbd5e1; border-radius:8px; outline:none; font-weight:600;">
                    @for($g = 1; $g <= 10; $g++)
                        <option value="{{ $g }}">Gate {{ $g }}</option>
                    @endfor
                </select>
            </div>
            <div style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:8px; padding:0.65rem 0.8rem; margin-bottom:1.25rem; font-size:0.73rem; color:#64748b; line-height:1.45;">
                <strong style="color:#0f172a;">Catatan:</strong> PIN 6 digit unik akan otomatis digenerate dan hanya ditampilkan <strong>SEKALI</strong> setelah pendaftaran.
            </div>
            <button type="submit" id="btnSubmitAddStaff" style="width:100%; padding:0.75rem; background:linear-gradient(135deg, #1eb349, #15803d); color:#ffffff; border:none; border-radius:10px; font-weight:700; font-size:0.85rem; cursor:pointer;">
                🔑 Generate PIN & Daftarkan
            </button>
        </form>
    </div>
</div>

{{-- MODAL REVEAL PIN SEKALI TAMPIL (PENTING & TIDAK BISA DILIHAT LAGI) --}}
<div id="staffPinSuccessModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.75); backdrop-filter:blur(6px); z-index:10000; align-items:center; justify-content:center; padding:1rem;">
    <div style="background:#ffffff; border-radius:22px; padding:2rem 1.75rem; max-width:420px; width:100%; text-align:center; box-shadow:0 25px 50px -12px rgba(0,0,0,0.4);">
        <div style="width:60px; height:60px; border-radius:50%; background:#fef3c7; border:2px solid #fde68a; color:#d97706; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
            <svg width="30" height="30" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <h3 style="font-weight:800; font-size:1.15rem; color:#0f172a; margin-bottom:0.25rem;">Catat PIN Panitia Ini Sekarang!</h3>
        <p style="font-size:0.79rem; color:#64748b; margin-bottom:1.25rem;">
            Panitia <strong id="revealStaffName" style="color:#0f172a;">-</strong> di <strong id="revealStaffGate" style="color:#0f172a;">Gate 1</strong>
        </p>

        {{-- PIN Box --}}
        <div style="background:#f8fafc; border:2px solid #e2e8f0; border-radius:12px; padding:1rem; margin-bottom:1rem; display:flex; align-items:center; justify-content:center; gap:0.75rem;">
            <span id="revealStaffPin" style="font-family:monospace; font-size:2rem; font-weight:800; letter-spacing:0.3em; color:#0f172a;">000000</span>
            <button type="button" onclick="copyStaffPin()" style="background:#1eb349; color:#fff; border:none; padding:0.4rem 0.65rem; border-radius:6px; font-size:0.75rem; font-weight:700; cursor:pointer;" id="btnCopyPin">Salin</button>
        </div>

        <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:0.75rem 0.85rem; margin-bottom:1.35rem; font-size:0.75rem; color:#991b1b; line-height:1.45; text-align:left;">
            <strong>⚠️ Peringatan Keamanan:</strong> Demi privasi dan keamanan acara, PIN ini <strong>HANYA DITAMPILKAN SEKALI INI SAJA</strong> dan tidak dapat dilihat kembali setelah jendela ini ditutup. Jika panitia lupa PIN, hapus nama staf dan buat kembali.
        </div>

        <button type="button" onclick="closeStaffPinSuccessModal()" style="width:100%; padding:0.75rem; background:#0f172a; color:#ffffff; border:none; border-radius:10px; font-weight:700; font-size:0.88rem; cursor:pointer;">
            ✓ Saya Sudah Mencatat / Membagikan PIN
        </button>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js" type="text/javascript"></script>
<script>
    // ══════════════════════════════════════════════════════════════════════
    // DATA CACHE & OFFLINE-READY DATASET (CROWDED & LOW INTERNET SHIELD)
    // ══════════════════════════════════════════════════════════════════════
    const CREATOR_ID = "{{ auth()->id() }}";
    const INITIAL_ACTIVE_TAB = "{{ $activeTab }}";
    const CACHE_STORAGE_KEY = 'buyle_tickets_cache_' + CREATOR_ID;

    // Ambil data server atau fallback ke LocalStorage jika offline
    let ALL_TICKETS = @json($allTicketsCached ?? []);
    try {
        if (ALL_TICKETS && ALL_TICKETS.length > 0) {
            localStorage.setItem(CACHE_STORAGE_KEY, JSON.stringify(ALL_TICKETS));
        } else {
            const localSaved = localStorage.getItem(CACHE_STORAGE_KEY);
            if (localSaved) {
                ALL_TICKETS = JSON.parse(localSaved);
            }
        }
    } catch (e) {
        console.warn('[Cache] LocalStorage error:', e);
    }
    window.ALL_TICKETS = ALL_TICKETS;

    // ══════════════════════════════════════════════════════════════════════
    // STATE SCANNER & KAMERA
    // ══════════════════════════════════════════════════════════════════════
    let html5QrcodeScanner = null;
    let isProcessing = false;

    // Fast Mode (Default ON & Tersimpan di LocalStorage)
    let isFastMode = true;
    try {
        const savedFast = localStorage.getItem('buyle_scanner_fast_mode');
        isFastMode = (savedFast !== null) ? (savedFast === '1') : true;
    } catch(e) {}

    function switchTab(tabId, btn) {
        document.querySelectorAll('.payout-tab-pane').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById(tabId).style.display = 'block';
        btn.classList.add('active');
        const tabName = tabId === 'tab-settings' ? 'settings' : (tabId === 'tab-data' ? 'data' : (tabId === 'tab-gate' ? 'gate' : 'scanner'));
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);

        if (tabId !== 'tab-settings') {
            if (typeof stopKtpCamera === 'function') stopKtpCamera();
            if (typeof stopFaceCamera === 'function') stopFaceCamera();
        }

        if (tabId === 'tab-data') {
            runInstantFilter();
        }
        if (tabId === 'tab-gate') {
            loadAllStaff();
        }
    }

    // ── Helper Gate Link & Modal ──
    function copyGateUrl(inputId, btn) {
        const input = document.getElementById(inputId);
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(() => {
            const origHtml = btn.innerHTML;
            btn.innerHTML = '✓ Disalin!';
            btn.style.background = '#059669';
            setTimeout(() => {
                btn.innerHTML = origHtml;
                btn.style.background = '#10b981';
            }, 2000);
        }).catch(() => {
            document.execCommand('copy');
            alert('Link berhasil disalin ke clipboard!');
        });
    }

    function showGateQrModal(name, url, qrUrl) {
        document.getElementById('gateQrEventTitle').innerText = name;
        document.getElementById('gateQrImg').src = qrUrl;
        document.getElementById('gateQrModal').style.display = 'flex';
    }

    function closeGateQrModal() {
        document.getElementById('gateQrModal').style.display = 'none';
    }

    async function toggleGateEnabled(eventId, checkbox) {
        const originalChecked = !checkbox.checked;
        try {
            const res = await fetch(`/creator/scanner/toggle-gate-enabled/${eventId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                const badge = document.getElementById(`gate-status-badge-${eventId}`);
                if (badge) {
                    badge.className = `badge-status ${data.enabled ? 'badge-used' : 'badge-cancelled'}`;
                    badge.innerText = data.enabled ? 'Gate Aktif' : 'Gate Dinonaktifkan';
                }
            } else {
                checkbox.checked = originalChecked;
                alert(data.message || 'Gagal mengubah status gate.');
            }
        } catch(e) {
            checkbox.checked = originalChecked;
            alert('Terjadi kesalahan jaringan.');
        }
    }

    async function resetGateKey(eventId) {
        if (!confirm('PERINGATAN: Mengganti kunci akan memutuskan akses SEMUA panitia lama yang sedang membuka link ini. Lanjutkan buat link baru?')) {
            return;
        }

        try {
            const res = await fetch(`/creator/scanner/reset-gate-key/${eventId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                const input = document.getElementById(`gate-url-${eventId}`);
                if (input) input.value = data.new_url;
                alert('Kunci Gate berhasil diperbarui! Bagikan link baru ke panitia.');
            } else {
                alert(data.message || 'Gagal mereset kunci.');
            }
        } catch(e) {
            alert('Terjadi kesalahan jaringan.');
        }
    }

    async function saveGatePin(eventId, btn) {
        const pinInput = document.getElementById(`gate-pin-${eventId}`);
        const pinVal = pinInput ? pinInput.value.trim() : '';
        const origText = btn.innerText;

        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        try {
            const res = await fetch(`/creator/scanner/update-pin/${eventId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ pin: pinVal })
            });
            const data = await res.json();
            if (data.success) {
                btn.innerText = 'Tersimpan!';
                btn.style.background = '#10b981';
                setTimeout(() => {
                    btn.innerText = origText;
                    btn.style.background = '#0f172a';
                    btn.disabled = false;
                }, 1800);
            } else {
                alert(data.message || 'Gagal menyimpan PIN.');
                btn.innerText = origText;
                btn.disabled = false;
            }
        } catch(e) {
            alert('Terjadi kesalahan jaringan.');
            btn.innerText = origText;
            btn.disabled = false;
        }
    }

    async function saveGateSchedule(eventId, btn) {
        const openInput = document.getElementById(`gate-open-${eventId}`);
        const closeInput = document.getElementById(`gate-close-${eventId}`);
        const openVal = openInput ? openInput.value : '';
        const closeVal = closeInput ? closeInput.value : '';
        const origText = btn.innerText;

        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        try {
            const res = await fetch(`/creator/scanner/update-schedule/${eventId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ open_time: openVal, close_time: closeVal })
            });
            const data = await res.json();
            if (data.success) {
                btn.innerText = 'Tersimpan!';
                if (data.gate_status) {
                    const badge = document.getElementById(`gate-status-badge-${eventId}`);
                    if (badge) {
                        badge.className = `badge-status ${data.gate_status.is_open ? 'badge-used' : 'badge-cancelled'}`;
                        badge.innerText = data.gate_status.reason;
                    }
                }
                setTimeout(() => {
                    btn.innerText = origText;
                    btn.disabled = false;
                }, 1800);
            } else {
                alert(data.message || 'Gagal menyimpan jadwal gate.');
                btn.innerText = origText;
                btn.disabled = false;
            }
        } catch(e) {
            alert('Terjadi kesalahan jaringan.');
            btn.innerText = origText;
            btn.disabled = false;
        }
    }

    async function quickExtendGate(eventId, btn) {
        const origText = btn.innerHTML;
        btn.disabled = true;

        try {
            const res = await fetch(`/creator/scanner/extend-gate/${eventId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                const closeInput = document.getElementById(`gate-close-${eventId}`);
                if (closeInput && data.new_close_time) {
                    closeInput.value = data.new_close_time;
                }
                if (data.gate_status) {
                    const badge = document.getElementById(`gate-status-badge-${eventId}`);
                    if (badge) {
                        badge.className = `badge-status ${data.gate_status.is_open ? 'badge-used' : 'badge-cancelled'}`;
                        badge.innerText = data.gate_status.reason;
                    }
                }
                alert(data.message);
            } else {
                alert(data.message || 'Gagal memperpanjang waktu gate.');
            }
        } catch(e) {
            alert('Terjadi kesalahan jaringan.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = origText;
        }
    }

    async function toggleGateBypass(eventId, checkbox) {
        try {
            const res = await fetch(`/creator/scanner/toggle-bypass/${eventId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success && data.gate_status) {
                const badge = document.getElementById(`gate-status-badge-${eventId}`);
                if (badge) {
                    badge.className = `badge-status ${data.gate_status.is_open ? 'badge-used' : 'badge-cancelled'}`;
                    badge.innerText = data.gate_status.reason;
                }
            }
        } catch(e) {
            alert('Terjadi kesalahan jaringan saat mengubah mode bypass.');
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    // GATE STAFF PANITIA MANAGEMENT
    // ══════════════════════════════════════════════════════════════════════
    let staffCurrentPin = '';

    function loadAllStaff() {
        @foreach($events as $ev)
            loadStaffForEvent('{{ $ev->id }}');
        @endforeach
    }

    async function loadStaffForEvent(eventId) {
        const container = document.getElementById(`staff-list-container-${eventId}`);
        const countBadge = document.getElementById(`staff-count-${eventId}`);
        if (!container) return;

        try {
            const res = await fetch(`/creator/scanner/staff/${eventId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                if (countBadge) countBadge.innerText = data.count || 0;

                if (!data.staff || data.staff.length === 0) {
                    container.innerHTML = `<div style="font-size:0.73rem; color:#94a3b8; font-style:italic; padding:0.25rem 0;">Belum ada panitia terdaftar. Klik "+ Tambah Panitia" untuk membuat PIN panitia.</div>`;
                    return;
                }

                let html = '';
                data.staff.forEach(s => {
                    const statusClass = s.is_active ? 'badge-used' : 'badge-cancelled';
                    const statusText = s.is_active ? 'Aktif' : 'Nonaktif';
                    const toggleTitle = s.is_active ? 'Nonaktifkan akses' : 'Aktifkan kembali';

                    html += `
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:0.4rem 0.65rem; font-size:0.75rem;">
                            <div style="display:flex; align-items:center; gap:0.45rem; min-width:0; flex:1;">
                                <span style="display:inline-flex; align-items:center; justify-content:center; width:20px; height:20px; border-radius:50%; background:#0f172a; color:#fff; font-size:0.68rem; font-weight:800; flex-shrink:0;">
                                    ${s.gate_number}
                                </span>
                                <div style="min-width:0;">
                                    <strong style="color:#0f172a; display:block; font-size:0.78rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${s.name}</strong>
                                    <span style="font-size:0.68rem; color:#64748b;">Gate ${s.gate_number}</span>
                                </div>
                            </div>
                            <div style="display:flex; align-items:center; gap:0.4rem; flex-shrink:0;">
                                <button type="button" onclick="toggleStaffActive('${eventId}', ${s.id})" title="${toggleTitle}" class="badge-status ${statusClass}" style="cursor:pointer; border:none; padding:0.18rem 0.5rem; font-size:0.68rem;">
                                    ${statusText}
                                </button>
                                <button type="button" onclick="deleteStaff('${eventId}', ${s.id}, '${s.name.replace(/'/g, "\\'")}')" title="Hapus Panitia" style="background:none; border:none; color:#ef4444; cursor:pointer; padding:2px; display:inline-flex; align-items:center;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }
        } catch(e) {
            container.innerHTML = `<div style="font-size:0.73rem; color:#ef4444;">Gagal memuat panitia.</div>`;
        }
    }

    function openAddStaffModal(eventId, eventName) {
        document.getElementById('addStaffEventId').value = eventId;
        document.getElementById('addStaffEventName').innerText = eventName;
        document.getElementById('addStaffName').value = '';
        document.getElementById('addStaffGate').value = '1';
        document.getElementById('addStaffModal').style.display = 'flex';
        setTimeout(() => document.getElementById('addStaffName').focus(), 80);
    }

    function closeAddStaffModal() {
        document.getElementById('addStaffModal').style.display = 'none';
    }

    async function submitAddStaff(e) {
        if (e) e.preventDefault();
        const eventId = document.getElementById('addStaffEventId').value;
        const name = document.getElementById('addStaffName').value.trim();
        const gate = document.getElementById('addStaffGate').value;
        const btn = document.getElementById('btnSubmitAddStaff');

        if (!name) return alert('Nama panitia wajib diisi.');

        btn.disabled = true;
        btn.innerText = 'Menyimpan & Generate PIN...';

        try {
            const res = await fetch(`/creator/scanner/staff/${eventId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: name, gate_number: gate })
            });
            const data = await res.json();
            if (data.success) {
                closeAddStaffModal();
                loadStaffForEvent(eventId);

                // Tampilkan Modal PIN Sekali Tampil
                staffCurrentPin = data.pin_once;
                document.getElementById('revealStaffName').innerText = data.staff_name;
                document.getElementById('revealStaffGate').innerText = 'Gate ' + data.gate;
                document.getElementById('revealStaffPin').innerText = data.pin_once;
                document.getElementById('btnCopyPin').innerText = 'Salin';
                document.getElementById('staffPinSuccessModal').style.display = 'flex';
            } else {
                alert(data.message || 'Gagal menambahkan panitia.');
            }
        } catch(err) {
            alert('Terjadi kesalahan jaringan.');
        } finally {
            btn.disabled = false;
            btn.innerText = '🔑 Generate PIN & Daftarkan';
        }
    }

    function copyStaffPin() {
        if (!staffCurrentPin) return;
        navigator.clipboard.writeText(staffCurrentPin).then(() => {
            const btn = document.getElementById('btnCopyPin');
            btn.innerText = '✓ Disalin';
            setTimeout(() => btn.innerText = 'Salin', 2000);
        });
    }

    function closeStaffPinSuccessModal() {
        document.getElementById('staffPinSuccessModal').style.display = 'none';
        staffCurrentPin = '';
    }

    async function toggleStaffActive(eventId, staffId) {
        try {
            const res = await fetch(`/creator/scanner/staff/${eventId}/${staffId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                loadStaffForEvent(eventId);
            } else {
                alert(data.message || 'Gagal mengubah status panitia.');
            }
        } catch(e) {
            alert('Terjadi kesalahan jaringan.');
        }
    }

    async function deleteStaff(eventId, staffId, name) {
        if (!confirm(`Hapus panitia "${name}"? Akses gate panitia ini akan dicabut permanen.`)) return;

        try {
            const res = await fetch(`/creator/scanner/staff/${eventId}/${staffId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                loadStaffForEvent(eventId);
            } else {
                alert(data.message || 'Gagal menghapus panitia.');
            }
        } catch(e) {
            alert('Terjadi kesalahan jaringan.');
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Inisialisasi checkbox Fast Mode
        const fastToggle = document.getElementById('autoScanToggle');
        if (fastToggle) {
            fastToggle.checked = isFastMode;
        }

        // Aktifkan Original Html5QrcodeScanner (Resmi & Terbukti Stabil di Desktop & HP)
        startOriginalScanner();

        // Load data staff untuk semua event
        loadAllStaff();

        if (INITIAL_ACTIVE_TAB === 'data') {
            runInstantFilter();
        }
    });

    function handleFastModeChange(checked) {
        isFastMode = checked;
        try {
            localStorage.setItem('buyle_scanner_fast_mode', checked ? '1' : '0');
        } catch(e) {}
    }

    // ══════════════════════════════════════════════════════════════════════
    // TRIPLE-ENGINE TICKETING SCANNER (NATIVE GPU + HTML5-QRCODE + JSQR GLARER)
    // ══════════════════════════════════════════════════════════════════════
    let jsQrAssistInterval = null;
    let isSwitchingCamera = false;
    let nativeBarcodeDetector = null;

    // Inisialisasi Native BarcodeDetector (Akselerasi GPU Browser Super Cepat & Tahan Silau)
    if ('BarcodeDetector' in window) {
        try {
            nativeBarcodeDetector = new window.BarcodeDetector({ formats: ['qr_code'] });
            console.log('[Scanner] Native BarcodeDetector engine ready');
        } catch(e) {
            console.warn('[Scanner] Native BarcodeDetector not supported:', e);
        }
    }

    async function startOriginalScanner() {
        try {
            if (jsQrAssistInterval) {
                clearInterval(jsQrAssistInterval);
                jsQrAssistInterval = null;
            }

            if (html5QrcodeScanner) {
                try { html5QrcodeScanner.clear(); } catch(e) {}
                html5QrcodeScanner = null;
            }

            // 1. Deteksi dan prioritaskan Kamera Eksternal USB (seperti JETE-W7)
            try {
                const devices = await Html5Qrcode.getCameras();
                if (devices && devices.length > 0) {
                    let savedCam = localStorage.getItem('buyle_chosen_camera_id');
                    let chosenDev = devices.find(d => d.id === savedCam);

                    // Jika belum ada preferensi, cari kamera eksternal USB (misal JETE-W7 / USB Webcam)
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

            // Inisialisasi Full-Frame Scanner dengan akselerasi hardware
            html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                fps: 15,                             // 15 FPS = frame capture lebih responsif
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

            // Pasang hook pengawas pergantian kamera live agar USB Camera (seperti JETE-W7) langsung switch
            setupCameraPersistence();

            // Jalankan Engine Assist: Native BarcodeDetector + jsQR Contrast Glare Enhancer
            startJsQrAssistLoop();

            setTimeout(() => {
                applyMirrorState();
            }, 1000);
        } catch (err) {
            console.error('[Scanner] Initialization failed:', err);
            try {
                html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                    fps: 15,
                    disableFlip: false,
                    rememberLastUsedCamera: true,
                }, false);
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                setupCameraPersistence();
                startJsQrAssistLoop();
                setTimeout(() => { applyMirrorState(); }, 1000);
            } catch(e2) {
                console.error('[Scanner] Fallback failed:', e2);
            }
        }
    }

    /**
     * Memastikan pergantian kamera di dropdown langsung mengaktifkan kamera baru (Release USB lama -> Nyalakan USB baru)
     */
    function setupCameraPersistence() {
        const readerEl = document.getElementById('reader');
        if (!readerEl) return;

        const observer = new MutationObserver(() => {
            const sel = readerEl.querySelector('select');
            if (sel && !sel._buyleHooked) {
                sel._buyleHooked = true;

                // Sync ke kamera yang tersimpan jika dropdown belum sinkron
                const savedCam = localStorage.getItem('buyle_chosen_camera_id');
                if (savedCam && sel.value !== savedCam) {
                    const optExists = Array.from(sel.options).some(o => o.value === savedCam);
                    if (optExists) {
                        sel.value = savedCam;
                    }
                }

                // EVENT PERGANTIAN KAMERA LIVE: Langsung ganti stream hardware tanpa perlu refresh
                sel.addEventListener('change', async function() {
                    const chosenId = this.value;
                    if (!chosenId) return;
                    await switchScannerCamera(chosenId);
                });
            }
        });
        observer.observe(readerEl, { childList: true, subtree: true });
    }

    /**
     * Switch Kamera Hardware Bersih (Stop Stream Lama -> Release Lock Windows USB -> Nyalakan Kamera Terpilih)
     */
    async function switchScannerCamera(newCameraId) {
        if (!newCameraId || isSwitchingCamera) return;
        isSwitchingCamera = true;
        console.log('[Scanner] Switching hardware camera to:', newCameraId);

        localStorage.setItem('buyle_chosen_camera_id', newCameraId);
        try {
            localStorage.setItem('HTML5_QRCODE_DATA', JSON.stringify({
                hasCameraPermission: true,
                lastUsedCameraId: newCameraId
            }));
        } catch(e) {}

        // 1. Jeda assist loop sementara saat switch
        if (jsQrAssistInterval) {
            clearInterval(jsQrAssistInterval);
            jsQrAssistInterval = null;
        }

        // 2. Hentikan scanner aktif
        if (html5QrcodeScanner && html5QrcodeScanner.html5Qrcode) {
            try {
                if (html5QrcodeScanner.html5Qrcode.isScanning) {
                    await html5QrcodeScanner.html5Qrcode.stop();
                }
            } catch(e) {
                console.warn('[Scanner] Stop warning:', e);
            }
        }

        // 3. RELEASE HARDWARE MEDIA TRACKS (Kritis di Windows agar USB webcam tidak terkunci)
        const vids = document.querySelectorAll('#reader video');
        vids.forEach(v => {
            if (v.srcObject) {
                try {
                    v.srcObject.getTracks().forEach(t => {
                        t.stop();
                        console.log('[Scanner] Released hardware track:', t.label);
                    });
                    v.srcObject = null;
                } catch(e) {}
            }
        });

        // Beri jeda 180ms agar driver USB Windows merilis handle kamera
        await new Promise(r => setTimeout(r, 180));

        // 4. Nyalakan kamera baru dengan deviceId terpilih
        if (html5QrcodeScanner && html5QrcodeScanner.html5Qrcode) {
            try {
                await html5QrcodeScanner.html5Qrcode.start(
                    newCameraId,
                    {
                        fps: 15,
                        disableFlip: false,
                        videoConstraints: {
                            deviceId: { exact: newCameraId },
                            width: { ideal: 1280, min: 640 },
                            height: { ideal: 720, min: 480 }
                        }
                    },
                    onScanSuccess,
                    onScanFailure
                );
                console.log('[Scanner] Kamera aktif:', newCameraId);
            } catch(err1) {
                console.warn('[Scanner] Constraint exact gagal, mencoba start standar:', err1);
                try {
                    await html5QrcodeScanner.html5Qrcode.start(
                        newCameraId,
                        { fps: 15, disableFlip: false },
                        onScanSuccess,
                        onScanFailure
                    );
                } catch(err2) {
                    console.error('[Scanner] Gagal menyalakan kamera baru:', err2);
                    alert('Gagal menyalakan kamera yang dipilih. Pastikan kabel USB terhubung dengan baik.');
                }
            }
        }

        applyMirrorState();
        startJsQrAssistLoop();
        isSwitchingCamera = false;
    }

    /**
     * Update Tampilan Smart Real-Time Video Guidance (Pencahayaan & Jarak QR Presisi)
     */
    let lastGuideType = '';
    let guideThrottleTimer = 0;

    function updateSmartGuide(className, type, message) {
        const now = Date.now();
        if (type !== lastGuideType && (now - guideThrottleTimer > 250)) {
            lastGuideType = type;
            guideThrottleTimer = now;

            const banner = document.getElementById('smartGuideBanner');
            const txt = document.getElementById('smartGuideText');
            const icon = document.getElementById('smartGuideIcon');
            if (!banner || !txt) return;

            banner.className = `smart-guide-banner ${className}`;
            txt.textContent = message;

            // Ikon presisi dinamis
            if (icon) {
                if (type === 'dark') {
                    icon.innerHTML = '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>';
                } else if (type === 'far') {
                    icon.innerHTML = '<polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>';
                } else if (type === 'close') {
                    icon.innerHTML = '<polyline points="4 14 10 14 10 20"/><polyline points="20 10 14 10 14 4"/><line x1="14" y1="10" x2="21" y2="3"/><line x1="3" y1="21" x2="10" y2="14"/>';
                } else if (type === 'optimal') {
                    icon.innerHTML = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>';
                } else {
                    icon.innerHTML = '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>';
                }
            }
        }
    }

    /**
     * Multi-Engine Assist Loop: Native BarcodeDetector (GPU) + jsQR Adaptive Glare Normalizer
     * Memecahkan masalah "layar HP silau / overexposed" sehingga pemindaian 100% instan seperti upload file
     */
    function startJsQrAssistLoop() {
        if (jsQrAssistInterval) clearInterval(jsQrAssistInterval);

        let assistCanvas = null;
        let assistCtx = null;

        jsQrAssistInterval = setInterval(async () => {
            if (isProcessing || isSwitchingCamera) return;

            const video = document.querySelector('#reader video');
            if (!video || video.readyState < 2 || video.paused || video.ended) return;

            const vw = video.videoWidth;
            const vh = video.videoHeight;
            if (!vw || !vh || vw < 50 || vh < 50) return;

            // ══════════════════════════════════════════════════════════════════
            // ENGINE 1: NATIVE BROWSER BARCODE DETECTOR (GPU ACCELERATED)
            // Membaca langsung dari video buffer — super cepat 2-5ms, tahan silau HP & pantulan
            // ══════════════════════════════════════════════════════════════════
            if (nativeBarcodeDetector && !isProcessing) {
                try {
                    const barcodes = await nativeBarcodeDetector.detect(video);
                    if (barcodes && barcodes.length > 0 && barcodes[0].rawValue && !isProcessing) {
                        console.log('[Native BarcodeDetector Detected QR]:', barcodes[0].rawValue);
                        updateSmartGuide('guide-optimal', 'optimal', 'Jarak Pas & Presisi! Memindai...');
                        onScanSuccess(barcodes[0].rawValue);
                        return;
                    }
                } catch(e) {}
            }

            // ══════════════════════════════════════════════════════════════════
            // ENGINE 2: jsQR + ADAPTIVE HIGH-CONTRAST FOR PHONE SCREENS
            // ══════════════════════════════════════════════════════════════════
            if (typeof window.jsQR !== 'function') return;

            if (!assistCanvas) {
                assistCanvas = document.createElement('canvas');
                assistCtx = assistCanvas.getContext('2d', { willReadFrequently: true });
            }

            assistCanvas.width = vw;
            assistCanvas.height = vh;
            assistCtx.drawImage(video, 0, 0, vw, vh);

            try {
                const imgData = assistCtx.getImageData(0, 0, vw, vh);
                const data = imgData.data;

                // 1. Analisis Pencahayaan Real-Time (Luminance ITU-R BT.601)
                const totalPixels = vw * vh;
                const step = Math.max(1, Math.floor(totalPixels / 1000));
                let sumLum = 0;
                let sampleCount = 0;
                for (let p = 0; p < data.length; p += step * 4) {
                    sumLum += (0.299 * data[p] + 0.587 * data[p+1] + 0.114 * data[p+2]);
                    sampleCount++;
                }
                const avgLum = sampleCount > 0 ? (sumLum / sampleCount) : 128;

                // 2. Normal Scan jsQR
                const code = window.jsQR(data, vw, vh, {
                    inversionAttempts: "attemptBoth"
                });

                if (code && code.location) {
                    const loc = code.location;
                    const wTop = Math.hypot(loc.topRightCorner.x - loc.topLeftCorner.x, loc.topRightCorner.y - loc.topLeftCorner.y);
                    const wBot = Math.hypot(loc.bottomRightCorner.x - loc.bottomLeftCorner.x, loc.bottomRightCorner.y - loc.bottomLeftCorner.y);
                    const qrWidth = (wTop + wBot) / 2;
                    const ratio = qrWidth / vw;

                    if (ratio < 0.20) {
                        updateSmartGuide('guide-far', 'far', 'QR Terlalu Jauh: Dekatkan layar HP ke arah kamera');
                    } else if (ratio > 0.82) {
                        updateSmartGuide('guide-close', 'close', 'QR Terlalu Dekat: Mundurkan sedikit HP agar seluruh QR terlihat');
                    } else {
                        updateSmartGuide('guide-optimal', 'optimal', 'Jarak Pas & Presisi! Memindai...');
                    }

                    if (code.data && !isProcessing) {
                        console.log('[Dual-Engine jsQR Detected]:', code.data);
                        onScanSuccess(code.data);
                        return;
                    }
                } else {
                    // 3. Fallback High-Contrast Binarizer khusus layar HP yang memantulkan cahaya / overexposed
                    if (avgLum > 100 && !isProcessing) {
                        const contrastData = new Uint8ClampedArray(data);
                        // Stretch contrast agar modul QR hitam yang pudar akibat backlight HP tetap terbaca tajam
                        for (let i = 0; i < contrastData.length; i += 4) {
                            const gray = 0.299 * contrastData[i] + 0.587 * contrastData[i+1] + 0.114 * contrastData[i+2];
                            const boosted = gray < 135 ? 0 : 255;
                            contrastData[i] = boosted;
                            contrastData[i+1] = boosted;
                            contrastData[i+2] = boosted;
                        }
                        const contrastCode = window.jsQR(contrastData, vw, vh, { inversionAttempts: "attemptBoth" });
                        if (contrastCode && contrastCode.data && !isProcessing) {
                            console.log('[Contrast-Boosted jsQR Caught Glare QR]:', contrastCode.data);
                            updateSmartGuide('guide-optimal', 'optimal', 'Jarak Pas & Presisi! Memindai...');
                            onScanSuccess(contrastCode.data);
                            return;
                        }
                    }

                    // Status Pencahayaan
                    if (avgLum < 45) {
                        updateSmartGuide('guide-dark', 'dark', 'Kamera Terlalu Gelap: Pastikan pencahayaan cukup atau nyalakan lampu');
                    } else if (avgLum > 225) {
                        updateSmartGuide('guide-glare', 'glare', 'Layar Terlalu Silau: Kurangi kecerahan layar HP pengunjung');
                    } else {
                        updateSmartGuide('guide-idle', 'idle', 'Pencahayaan Baik: Arahkan QR Code ke kamera');
                    }
                }
            } catch (err) {
                // Abaikan frame drop saat transisi
            }
        }, 80);
    }

    // ══════════════════════════════════════════════════════════════════════
    // MIRROR CAMERA TOGGLE (WEBCAM DESKTOP BUTUH MIRROR AGAR NATURAL)
    // ══════════════════════════════════════════════════════════════════════
    let isMirrorOn = true;
    try {
        const savedMirror = localStorage.getItem('buyle_scanner_mirror');
        isMirrorOn = (savedMirror !== null) ? (savedMirror === '1') : true;
    } catch(e) {}

    function toggleMirror() {
        isMirrorOn = !isMirrorOn;
        try { localStorage.setItem('buyle_scanner_mirror', isMirrorOn ? '1' : '0'); } catch(e) {}
        applyMirrorState();
    }

    function applyMirrorState() {
        const readerEl = document.getElementById('reader');
        const mirrorBtn = document.getElementById('mirrorToggleBtn');
        if (readerEl) {
            if (isMirrorOn) {
                readerEl.classList.add('mirror-on');
            } else {
                readerEl.classList.remove('mirror-on');
            }
        }
        const mirrorLabel = document.getElementById('mirrorLabel');
        if (mirrorLabel) {
            mirrorLabel.textContent = isMirrorOn ? 'Mirror: ON' : 'Mirror: OFF';
        }
        if (mirrorBtn) {
            mirrorBtn.style.background = isMirrorOn ? '#f0fdf4' : '#f8fafc';
            mirrorBtn.style.borderColor = isMirrorOn ? '#bbf7d0' : '#e2e8f0';
            mirrorBtn.style.color = isMirrorOn ? '#166534' : '#64748b';
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    // RESET PANEL HASIL SCAN KE IDLE (MENUNGGU SCAN)
    // ══════════════════════════════════════════════════════════════════════
    function resetToIdleState() {
        const panel = document.getElementById('scannerResultPanel');
        const stateIdle = document.getElementById('resStateIdle');
        const stateLoading = document.getElementById('resStateLoading');
        const stateActive = document.getElementById('resStateActive');

        if (panel) {
            panel.className = 'scanner-result-panel';
        }
        if (stateLoading) stateLoading.style.display = 'none';
        if (stateActive) stateActive.style.display = 'none';
        if (stateIdle) stateIdle.style.display = 'flex';
        isProcessing = false;
    }

    // ══════════════════════════════════════════════════════════════════════
    // PARSING & VERIFIKASI QR CODE / KODE TIKET
    // ══════════════════════════════════════════════════════════════════════
    function parseScannedToken(rawText) {
        if (!rawText) return '';
        let str = String(rawText).trim();

        if (str.startsWith('{') && str.endsWith('}')) {
            try {
                const json = JSON.parse(str);
                str = json.code || json.qr_token || json.token || json.ticket_code || json.id || str;
            } catch(e) {}
        }

        if (str.startsWith('http://') || str.startsWith('https://')) {
            try {
                const url = new URL(str);
                const p = url.searchParams;
                const tokenParam = p.get('code') || p.get('qr_token') || p.get('token') || p.get('t') || p.get('ticket');
                if (tokenParam) return tokenParam.trim();
                const segments = url.pathname.split('/').filter(Boolean);
                if (segments.length > 0) {
                    const last = segments[segments.length - 1];
                    if (last.length > 3) return last.trim();
                }
            } catch(e) {}
        }

        return str.replace(/^["']|["']$/g, '').trim();
    }

    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        const cleanToken = parseScannedToken(decodedText);
        verifyCode(cleanToken || decodedText);
    }

    function onScanFailure() {
        // Abaikan frame gagal saat kamera mencari QR
    }

    function handleManualSubmit(e) {
        e.preventDefault();
        if (isProcessing) return;
        const raw = document.getElementById('manualCodeInput').value.trim();
        const cleanToken = parseScannedToken(raw);
        if (cleanToken) verifyCode(cleanToken);
    }

    function verifyCode(code) {
        if (isProcessing) return;
        isProcessing = true;

        const panel = document.getElementById('scannerResultPanel');
        const stateIdle = document.getElementById('resStateIdle');
        const stateLoading = document.getElementById('resStateLoading');
        const stateActive = document.getElementById('resStateActive');
        const loadingCode = document.getElementById('resLoadingCode');

        if (panel) {
            panel.className = 'scanner-result-panel';
            if (stateIdle) stateIdle.style.display = 'none';
            if (stateActive) stateActive.style.display = 'none';
            if (stateLoading) {
                stateLoading.style.display = 'flex';
                if (loadingCode) loadingCode.textContent = code;
            }
        }

        fetch("{{ route('creator.ticket.scanner.verify') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(data => {
            // Update cache lokal langsung agar sinkron
            if (data.status === 'valid' && data.ticket && data.ticket.code) {
                const targetInCache = ALL_TICKETS.find(t => t.code === data.ticket.code);
                if (targetInCache) {
                    targetInCache.status = 'used';
                    targetInCache.checked_in_at = data.ticket.checked_in_at || 'Baru saja';
                    try { localStorage.setItem(CACHE_STORAGE_KEY, JSON.stringify(ALL_TICKETS)); } catch(e){}
                    updateStatsUI();
                }
            }
            renderResult(data);
        })
        .catch(err => {
            renderResult({
                status: 'invalid',
                title: 'Koneksi Lemot / Terputus',
                message: 'Gagal terhubung ke server. Silakan periksa kembali koneksi internet Anda.'
            });
        });
    }

    // ══════════════════════════════════════════════════════════════════════
    // FEEDBACK SUARA (WEB AUDIO SYNTHESIZER — ZERO ASSET LAG)
    // ══════════════════════════════════════════════════════════════════════
    function playScanBeep(status = 'valid') {
        if (navigator.vibrate) {
            try {
                navigator.vibrate(status === 'valid' ? [80, 40, 80] : [180, 60, 180]);
            } catch(e) {}
        }

        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();

            if (status === 'valid') {
                // Suara "CENTING" kristal bel ding yang sangat renyah, nyaring, dan bersih
                const now = ctx.currentTime;

                // Nada Utama (C7 ~2093Hz) dengan exponential decay alami
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(2093, now);
                osc1.frequency.exponentialRampToValueAtTime(2060, now + 0.45);

                gain1.gain.setValueAtTime(0.4, now);
                gain1.gain.exponentialRampToValueAtTime(0.0001, now + 0.5);

                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.5);

                // Harmoni Kilau Kristal Overtone (G7 ~3136Hz)
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(3136, now);
                gain2.gain.setValueAtTime(0.2, now);
                gain2.gain.exponentialRampToValueAtTime(0.0001, now + 0.3);

                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now);
                osc2.stop(now + 0.3);
            } else {
                // Warning low tone
                const now = ctx.currentTime;
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(220, now);
                osc.frequency.setValueAtTime(180, now + 0.1);
                gain.gain.setValueAtTime(0.3, now);
                gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.3);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.3);
            }
        } catch(e) {}
    }

    // ══════════════════════════════════════════════════════════════════════
    // RENDER HASIL SCAN (SIDE-BY-SIDE PANEL TANPA POPUP BLOCKING)
    // ══════════════════════════════════════════════════════════════════════
    function renderResult(data) {
        playScanBeep(data.status);

        const isMobile = window.innerWidth <= 768;
        const isFast = document.getElementById('autoScanToggle')?.checked ?? true;
        const panel = document.getElementById('scannerResultPanel');
        const stateIdle = document.getElementById('resStateIdle');
        const stateLoading = document.getElementById('resStateLoading');
        const stateActive = document.getElementById('resStateActive');

        // DESKTOP ONLY: Update side-by-side result panel
        if (!isMobile && panel && stateActive) {
            if (stateIdle) stateIdle.style.display = 'none';
            if (stateLoading) stateLoading.style.display = 'none';
            stateActive.style.display = 'flex';

            let statusClass = 'status-invalid';
            let statusTagText = 'TIKET DITOLAK';
            let iconSvg = '';
            let bannerBg = '#fef2f2';
            let bannerBorder = '1.5px solid #fecaca';
            let tagColor = '#991b1b';

            if (data.status === 'valid') {
                statusClass = 'status-valid';
                statusTagText = 'CHECK-IN BERHASIL';
                bannerBg = '#f0fdf4';
                bannerBorder = '1.5px solid #bbf7d0';
                tagColor = '#166534';
                iconSvg = `<svg width="34" height="34" fill="none" stroke="#166534" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`;
            } else if (data.status === 'used') {
                statusClass = 'status-used';
                statusTagText = 'SUDAH DIGUNAKAN';
                bannerBg = '#fffbeb';
                bannerBorder = '1.5px solid #fde68a';
                tagColor = '#b45309';
                iconSvg = `<svg width="34" height="34" fill="none" stroke="#b45309" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`;
            } else {
                statusClass = 'status-invalid';
                statusTagText = 'TIKET TIDAK VALID';
                bannerBg = '#fef2f2';
                bannerBorder = '1.5px solid #fecaca';
                tagColor = '#991b1b';
                iconSvg = `<svg width="34" height="34" fill="none" stroke="#991B1B" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
            }

            panel.className = 'scanner-result-panel ' + statusClass;

            const banner = document.getElementById('resActiveBanner');
            if (banner) {
                banner.style.background = bannerBg;
                banner.style.border = bannerBorder;
            }
            const tag = document.getElementById('resActiveStatusTag');
            if (tag) {
                tag.textContent = statusTagText;
                tag.style.color = tagColor;
            }
            const iconWrap = document.getElementById('resActiveIcon');
            if (iconWrap) iconWrap.innerHTML = iconSvg;

            const titleEl = document.getElementById('resActiveTitle');
            if (titleEl) {
                titleEl.textContent = data.title || (data.status === 'valid' ? 'Tiket Valid' : (data.status === 'used' ? 'Tiket Sudah Check-In' : 'Tiket Tidak Valid'));
                titleEl.style.color = tagColor;
            }

            const msgEl = document.getElementById('resActiveMsg');
            if (msgEl) msgEl.textContent = data.message || '';

            const detailsCard = document.getElementById('resActiveDetailsCard');
            if (data.ticket) {
                if (detailsCard) detailsCard.style.display = 'flex';
                const nameEl = document.getElementById('resActiveName');
                if (nameEl) nameEl.textContent = data.ticket.holder_name || '-';

                const codeEl = document.getElementById('resActiveCode');
                if (codeEl) codeEl.textContent = data.ticket.code || '-';

                const eventEl = document.getElementById('resActiveEvent');
                if (eventEl) eventEl.textContent = data.ticket.event_name || '-';

                const timeEl = document.getElementById('resActiveTime');
                if (timeEl) timeEl.textContent = data.ticket.checked_in_at || 'Baru saja';
            } else {
                if (detailsCard) detailsCard.style.display = 'none';
            }

            const nextIndicator = document.getElementById('resActiveNextIndicator');
            if (nextIndicator) {
                nextIndicator.innerHTML = isFast ? 
                    `<span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#16a34a;animation:pulse 1s infinite;"></span> Siap scan berikutnya (Auto)` :
                    `<span style="color:#64748b;">Mode Manual Aktif</span>`;
            }
        }

        // KHUSUS MOBILE:
        // Info hasil scan dimunculkan dalam bentuk POPUP (bukan di bawahnya),
        // lengkap dengan button kecil "Next" untuk melanjutkan scan jika berhasil.
        if (isMobile) {
            openScanModal(data);
        } else {
            // DESKTOP: Side-by-side mode
            if (isFast) {
                setTimeout(() => {
                    isProcessing = false;
                }, 900);
            } else {
                openScanModal(data);
            }
        }
    }

    function openScanModal(data) {
        const overlay = document.getElementById('scanResultModal');
        const box     = document.getElementById('scanResultModalBox');
        const bubble  = document.getElementById('modalIconBubble');
        const titleEl = document.getElementById('modalTitle');
        const msgEl   = document.getElementById('modalMsg');
        const card    = document.getElementById('modalDetailsCard');
        const nextBtn = document.getElementById('modalNextBtn');
        const btnText = document.getElementById('modalBtnText');
        const btnIcon = document.getElementById('modalBtnIcon');

        const isValid = (data.status === 'valid');

        if (isValid) {
            bubble.style.background = '#F0FDF4';
            bubble.style.border = '1.5px solid #BBF7D0';
            bubble.innerHTML = `<svg width="30" height="30" fill="none" stroke="#166534" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`;
            titleEl.style.color = '#166534';

            // Button kecil Next khusus mobile jika scan berhasil
            if (nextBtn) {
                nextBtn.style.background = 'linear-gradient(135deg, #1eb349, #15803d)';
                nextBtn.style.boxShadow = '0 3px 12px rgba(30,179,73,0.3)';
            }
            if (btnText) btnText.textContent = 'Next';
            if (btnIcon) btnIcon.style.display = 'inline-block';
        } else if (data.status === 'used') {
            bubble.style.background = '#FEF3C7';
            bubble.style.border = '1.5px solid #FDE68A';
            bubble.innerHTML = `<svg width="30" height="30" fill="none" stroke="#B45309" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`;
            titleEl.style.color = '#B45309';

            if (nextBtn) {
                nextBtn.style.background = '#0F172A';
                nextBtn.style.boxShadow = 'none';
            }
            if (btnText) btnText.textContent = 'Tutup';
            if (btnIcon) btnIcon.style.display = 'none';
        } else {
            bubble.style.background = '#FEE2E2';
            bubble.style.border = '1.5px solid #FECACA';
            bubble.innerHTML = `<svg width="30" height="30" fill="none" stroke="#DC2626" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
            titleEl.style.color = '#DC2626';

            if (nextBtn) {
                nextBtn.style.background = '#0F172A';
                nextBtn.style.boxShadow = 'none';
            }
            if (btnText) btnText.textContent = 'Tutup';
            if (btnIcon) btnIcon.style.display = 'none';
        }

        titleEl.textContent = data.title || (isValid ? 'Tiket Valid' : (data.status === 'used' ? 'Sudah Check-In' : 'Tiket Tidak Valid'));
        msgEl.textContent   = data.message || '';

        if (data.ticket) {
            card.style.display = 'block';
            const t = data.ticket;
            card.innerHTML = `
                <div style="margin-bottom:6px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="color:#64748B;font-size:0.78rem;">Kode Tiket</span>
                    <strong style="color:#166534;font-family:monospace;font-size:0.83rem;background:#f0fdf4;padding:2px 6px;border-radius:4px;border:1px solid #bbf7d0;">${t.code || '-'}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                    <span style="color:#64748B;font-size:0.78rem;flex-shrink:0;">Nama Event</span>
                    <strong style="color:#0F172A;font-size:0.8rem;text-align:right;">${t.event_name || '-'}</strong>
                </div>
            `;
        } else {
            card.style.display = 'none';
        }

        overlay.style.display = 'flex';
        setTimeout(() => {
            overlay.style.opacity = '1';
            box.style.transform   = 'scale(1)';
        }, 20);
    }

    function closeScanModal() {
        const overlay = document.getElementById('scanResultModal');
        const box     = document.getElementById('scanResultModalBox');

        if (overlay && box) {
            overlay.style.opacity   = '0';
            box.style.transform     = 'scale(0.92)';
            setTimeout(() => {
                overlay.style.display = 'none';
                isProcessing = false;
            }, 200);
        } else {
            isProcessing = false;
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    // PENCARIAN & FILTERING INSTAN 0ms (NO PAGE RELOAD / OFFLINE CACHE)
    // ══════════════════════════════════════════════════════════════════════
    function runInstantFilter() {
        const searchVal = (document.getElementById('instantSearchInput')?.value || '').toLowerCase().trim();
        const eventVal  = document.getElementById('instantEventFilter')?.value || '';
        const statusVal = document.getElementById('instantStatusFilter')?.value || '';

        const tbody = document.getElementById('attendanceTableBody');
        const pagWrap = document.getElementById('tablePaginationWrap');
        if (!tbody) return;

        // Filter dari in-memory dataset
        const filtered = ALL_TICKETS.filter(t => {
            if (eventVal && String(t.product_id) !== String(eventVal)) return false;
            if (statusVal && t.status !== statusVal) return false;
            if (searchVal) {
                const combined = [
                    t.code || '',
                    t.holder_name || '',
                    t.holder_email || '',
                    t.holder_phone || '',
                    t.event_name || ''
                ].join(' ').toLowerCase();
                return combined.includes(searchVal);
            }
            return true;
        });

        // Sembunyikan pagination server saat filter aktif
        if (pagWrap) {
            pagWrap.style.display = (searchVal || eventVal || statusVal) ? 'none' : 'block';
        }

        // Render rows instan
        if (filtered.length === 0) {
            tbody.innerHTML = `<tr>
                <td colspan="7" style="text-align: center; padding: 2.5rem 1rem; color: #94a3b8;">
                    <div style="font-weight: 700; color: #334155; margin-bottom: 0.25rem; font-size: 0.92rem;">Tidak Ada Tiket yang Cocok</div>
                    <div style="font-size: 0.78rem; color: #64748b;">Pencarian instan tidak menemukan data untuk filter yang dipilih.</div>
                </td>
            </tr>`;
        } else {
            let html = '';
            filtered.forEach((t, index) => {
                let badgeClass = 'badge-valid';
                let statusLabel = 'Belum Hadir';
                let btnHtml = '';

                if (t.status === 'used') {
                    badgeClass = 'badge-used';
                    statusLabel = 'Hadir (Checked-In)';
                    btnHtml = `<button type="button" class="btn-action-sm btn-toggle-undo" onclick="toggleCheckinInstant(${t.id}, '${t.toggle_url}', this)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-2px;margin-right:3px;"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/></svg> Batalkan
                    </button>`;
                } else if (t.status === 'cancelled') {
                    badgeClass = 'badge-cancelled';
                    statusLabel = 'Dibatalkan';
                    btnHtml = `<span style="font-size: 0.75rem; color: #94a3b8;">Tidak Aktif</span>`;
                } else {
                    btnHtml = `<button type="button" class="btn-action-sm btn-toggle-checkin" onclick="toggleCheckinInstant(${t.id}, '${t.toggle_url}', this)">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline-block;vertical-align:-1px;margin-right:2px;"><polyline points="20 6 9 17 4 12"></polyline></svg> Check-In
                    </button>`;
                }

                const timeDisplay = t.checked_in_at
                    ? `<span style="font-weight: 700; color: #166534; display: block; font-size: 0.8rem;">${t.checked_in_at}</span>`
                    : `<span style="color: #94a3b8; font-style: italic; font-size: 0.8rem;">— Belum —</span>`;

                html += `<tr id="row-ticket-${t.id}">
                    <td class="row-index">${index + 1}</td>
                    <td>
                        <span style="font-family: monospace; font-weight: 700; font-size: 0.85rem; color: #0f172a; background: #f8fafc; padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid #e2e8f0;">
                            ${t.code}
                        </span>
                    </td>
                    <td>
                        <strong style="color: #0f172a; display: block; font-size: 0.84rem;">${t.event_name}</strong>
                        <span style="font-size: 0.73rem; color: #64748b;">${t.event_date}</span>
                    </td>
                    <td>
                        <strong style="color: #0f172a; display: block; font-size: 0.85rem;">${t.holder_name}</strong>
                        <span style="font-size: 0.74rem; color: #64748b; display: block;">${t.holder_email}</span>
                        <span style="font-size: 0.74rem; color: #64748b;">${t.holder_phone || ''}</span>
                        ${t.holder_nik ? `<span style="display:inline-flex;align-items:center;gap:3px;font-size:0.71rem;color:#0369a1;background:#e0f2fe;border:1px solid #bae6fd;padding:1px 6px;border-radius:4px;font-family:monospace;margin-top:2px;">
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            ${t.holder_nik}
                        </span>` : ''}
                    </td>
                    <td class="row-status">
                        <span class="badge-status ${badgeClass}">${statusLabel}</span>
                    </td>
                    <td class="row-time">${timeDisplay}${t.scanned_by ? `<span style="font-size: 0.71rem; color: #475569; display: flex; align-items: center; gap: 3px; margin-top: 2px; font-weight: 600;"><svg width="10" height="10" fill="none" stroke="#10b981" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>${t.scanned_by}</span>` : ''}</td>
                    <td style="text-align: center;" class="row-action">${btnHtml}</td>
                </tr>`;
            });
            tbody.innerHTML = html;
        }

        // Update ringkasan stats secara instan
        updateStatsUI();
    }

    function resetInstantFilter() {
        const searchInput = document.getElementById('instantSearchInput');
        const eventSelect = document.getElementById('instantEventFilter');
        const statusSelect = document.getElementById('instantStatusFilter');
        if (searchInput) searchInput.value = '';
        if (eventSelect) eventSelect.value = '';
        if (statusSelect) statusSelect.value = '';

        runInstantFilter();
    }

    function updateStatsUI() {
        const eventVal = document.getElementById('instantEventFilter')?.value || '';
        const source = eventVal ? ALL_TICKETS.filter(t => String(t.product_id) === String(eventVal)) : ALL_TICKETS;

        const total = source.length;
        const checkedIn = source.filter(t => t.status === 'used').length;
        const unchecked = source.filter(t => t.status === 'valid').length;
        const cancelled = source.filter(t => t.status === 'cancelled').length;
        const rate = total > 0 ? (checkedIn / total * 100).toFixed(1) : 0;

        const totalEl = document.getElementById('statTotalVal');
        const checkedEl = document.getElementById('statCheckedInVal');
        const rateEl = document.getElementById('statCheckedInRate');
        const uncheckedEl = document.getElementById('statUncheckedVal');
        const cancelledEl = document.getElementById('statCancelledVal');

        if (totalEl) totalEl.textContent = total.toLocaleString('id-ID');
        if (checkedEl) checkedEl.textContent = checkedIn.toLocaleString('id-ID');
        if (rateEl) rateEl.textContent = `Tingkat Kehadiran: ${rate}%`;
        if (uncheckedEl) uncheckedEl.textContent = unchecked.toLocaleString('id-ID');
        if (cancelledEl) cancelledEl.textContent = cancelled.toLocaleString('id-ID');
    }

    // ══════════════════════════════════════════════════════════════════════
    // CHECK-IN MANUAL INSTAN (OPTIMISTIC UI UPDATE)
    // ══════════════════════════════════════════════════════════════════════
    async function toggleCheckinInstant(ticketId, toggleUrl, btn) {
        const ticket = ALL_TICKETS.find(t => t.id === ticketId);
        if (!ticket) return;

        const prevStatus = ticket.status;
        const newStatus = (prevStatus === 'used') ? 'valid' : 'used';
        
        // Optimistic UI update langsung 0ms
        ticket.status = newStatus;
        if (newStatus === 'used') {
            const now = new Date();
            ticket.checked_in_at = now.toLocaleTimeString('id-ID') + ' WIB';
        } else {
            ticket.checked_in_at = null;
        }

        try {
            localStorage.setItem(CACHE_STORAGE_KEY, JSON.stringify(ALL_TICKETS));
        } catch(e) {}

        runInstantFilter();

        // Background sync ke server
        try {
            const res = await fetch(toggleUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            });
            const data = await res.json();
            if (!data.success) {
                // Rollback jika server tolak
                ticket.status = prevStatus;
                runInstantFilter();
                alert(data.message || 'Gagal mengubah status check-in.');
            }
        } catch (err) {
            console.warn('[Sync] Offline toggle recorded, will keep in cache');
        }
    }

    const style = document.createElement('style');
    style.textContent = '@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
    document.head.appendChild(style);

    // ══════════════════════════════════════════════════════════════════════
    // SIGNAL STRENGTH INDICATOR (navigator.connection + favicon ping fallback)
    // Interval: 8s, bandwidth overhead minimal (favicon cached by browser)
    // ══════════════════════════════════════════════════════════════════════
    (function initSignalStrength() {
        const BARS = [1,2,3,4,5].map(i => document.getElementById('bar' + i));
        const LABEL = document.getElementById('signalLabel');
        const WIDGET = document.getElementById('signalWidget');

        // level: 0=offline, 1=sangat lemot, 2=lemot, 3=standar, 4=cepat, 5=sangat cepat
        function applyLevel(level) {
            if (!WIDGET) return;
            const configs = [
                { label: 'Tidak Ada Koneksi', barColor: '#ef4444', bgColor: '#fef2f2', borderColor: '#fecaca', textColor: '#dc2626' },
                { label: 'Sangat Lemot',      barColor: '#ef4444', bgColor: '#fef2f2', borderColor: '#fecaca', textColor: '#dc2626' },
                { label: 'Lemot',             barColor: '#f59e0b', bgColor: '#fffbeb', borderColor: '#fde68a', textColor: '#d97706' },
                { label: 'Standar',           barColor: '#f59e0b', bgColor: '#fffbeb', borderColor: '#fde68a', textColor: '#d97706' },
                { label: 'Cepat',             barColor: '#1eb349', bgColor: '#f0fdf4', borderColor: '#bbf7d0', textColor: '#166534' },
                { label: 'Sangat Cepat',      barColor: '#1eb349', bgColor: '#f0fdf4', borderColor: '#bbf7d0', textColor: '#166534' },
            ];
            const c = configs[level] || configs[3];
            BARS.forEach((bar, i) => {
                if (!bar) return;
                bar.style.background = (i < level) ? c.barColor : '#e2e8f0';
            });
            if (LABEL) { LABEL.textContent = c.label; LABEL.style.color = c.textColor; }
            WIDGET.style.background = c.bgColor;
            WIDGET.style.borderColor = c.borderColor;
        }

        function rttToLevel(rttMs) {
            if (rttMs === null) return 3; // unknown = standar
            if (rttMs >= 2000)  return 1; // sangat lemot
            if (rttMs >= 600)   return 2; // lemot
            if (rttMs >= 200)   return 3; // standar
            if (rttMs >= 80)    return 4; // cepat
            return 5;                      // sangat cepat
        }

        function fromConnection() {
            const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            if (!conn) return null;
            // effectiveType: slow-2g=1, 2g=2, 3g=3, 4g=5
            const typeMap = { 'slow-2g': 1, '2g': 2, '3g': 3, '4g': 5 };
            if (conn.effectiveType && typeMap[conn.effectiveType] !== undefined) {
                return typeMap[conn.effectiveType];
            }
            if (typeof conn.rtt === 'number') return rttToLevel(conn.rtt);
            return null;
        }

        let _pingUrl = '/favicon.ico?_s=' + Date.now();
        async function pingServer() {
            if (!navigator.onLine) return 0;
            try {
                const t0 = performance.now();
                await fetch('/favicon.ico?_p=' + Date.now(), { method: 'GET', cache: 'no-store', mode: 'no-cors' });
                const rtt = performance.now() - t0;
                return rttToLevel(rtt);
            } catch(e) { return 0; }
        }

        async function measure() {
            if (!navigator.onLine) { applyLevel(0); return; }
            const fromConn = fromConnection();
            if (fromConn !== null) {
                applyLevel(fromConn);
                // Also run ping in background for better accuracy
                pingServer().then(l => applyLevel(l)).catch(() => {});
            } else {
                const l = await pingServer();
                applyLevel(l);
            }
        }

        // Listen to connection changes (Chrome/Android)
        const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        if (conn) conn.addEventListener('change', measure);
        window.addEventListener('online',  () => measure());
        window.addEventListener('offline', () => applyLevel(0));

        // Initial measurement + repeat every 8s
        measure();
        setInterval(measure, 8000);
    })();
</script>
@endsection
