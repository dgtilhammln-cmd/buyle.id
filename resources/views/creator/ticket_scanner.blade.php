@extends('creator.layout')
@section('title', 'Scan Tiket & Data Kehadiran · Creator Studio')
@section('page_title', 'Scan Tiket & Data Kehadiran')

@section('styles')
<style>
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

    /* ── SCANNER VIEWPORT ── */
    #reader {
        width: 100% !important;
        max-width: 100% !important;
        border-radius: 18px;
        overflow: hidden !important;
        border: 2px dashed #bbf7d0 !important;
        background: #f8fafc;
        padding: 1.25rem 0.75rem !important;
        text-align: center;
        margin-bottom: 1.5rem;
        box-sizing: border-box !important;
    }

    #reader select {
        height: 44px;
        padding: 0 2rem 0 1rem;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0 !important;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.83rem;
        font-weight: 600;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        margin: 0.5rem 0;
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
        height: 44px;
        padding: 0 1.5rem !important;
        border-radius: 999px !important;
        background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
        border: none !important;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: #ffffff !important;
        cursor: pointer;
        margin: 0.5rem auto;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35) !important;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        text-decoration: none !important;
        max-width: 100% !important;
    }

    .res-box {
        margin-top: 1rem;
        margin-bottom: 1.25rem;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        display: none;
        animation: fadeIn 0.3s ease;
        max-width: 100%;
        box-sizing: border-box;
    }

    .res-valid { background: #f0fdf4; border: 1.5px solid #bbf7d0; color: #166534; }
    .res-used { background: #f8fafc; border: 1.5px solid #e2e8f0; color: #475569; }
    .res-invalid { background: #fef2f2; border: 1.5px solid #fecaca; color: #991b1b; }

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

        {{-- TAB 1: SCANNER --}}
        <div id="tab-scanner" class="payout-tab-pane" style="display: {{ $activeTab === 'scanner' ? 'block' : 'none' }};">
            <div class="prof-card">
                <div class="prof-card-head">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                        Live Camera QR Ticket Scanner
                    </div>
                </div>

                <div class="form-body">
                    {{-- Input Manual --}}
                    <form id="manualScanForm" class="mb-4" onsubmit="handleManualSubmit(event)">
                        <label class="form-label" style="font-size:0.82rem; font-weight:700; color:#334155; margin-bottom:0.5rem; display:block;">Pemeriksaan Kode Tiket / Token (Manual)</label>
                        <div style="display: flex;">
                            <input type="text" id="manualCodeInput" class="form-input-code" placeholder="Ketik Kode Tiket (misal: TKT-20260905-XXXX)...">
                            <button class="btn-submit-scan" type="submit">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                                Periksa
                            </button>
                        </div>
                    </form>

                    {{-- Result Box (Terletak TEPAT di Atas Kamera) --}}
                    <div id="resultBox" class="res-box">
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <div id="resultIcon" style="flex-shrink: 0; margin-top: 2px;"></div>
                            <div style="flex: 1;">
                                <h5 id="resultTitle" style="font-weight: 800; font-size: 1rem; margin-bottom: 0.25rem;"></h5>
                                <p id="resultMsg" style="font-size: 0.85rem; margin-bottom: 0.75rem; line-height: 1.4;"></p>
                                <div id="ticketDetails" style="font-size: 0.8rem; background: #ffffff; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #e2e8f0; display: none;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Header & Camera Switch Control --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
                        <div style="font-weight: 700; font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 6px;">
                            <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                            Viewport Live QR Scanner
                        </div>
                        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                            {{-- Dropdown Pilih Kamera (Desktop & External Webcam Support) --}}
                            <div class="input-icon-wrap" style="width: auto;">
                                <select id="cameraDeviceSelect" class="filter-select" onchange="changeSelectedCamera(this.value)" style="height:36px; padding:0 0.85rem; border-radius:999px; border:1.5px solid #cbd5e1; font-size:0.78rem; font-weight:700; color:#1e293b; background:#fff; cursor:pointer; max-width:210px;" title="Pilih Kamera / Webcam">
                                    <option value="">Deteksi Kamera...</option>
                                </select>
                            </div>

                            <label style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.78rem; font-weight:700; color:#166534; background:#f0fdf4; padding:0.4rem 0.85rem; border-radius:999px; border:1.5px solid #bbf7d0; cursor:pointer; user-select:none;" title="Scan cepat otomatis tanpa popup modal yang memblokir">
                                <input type="checkbox" id="autoScanToggle" checked style="accent-color:#1eb349; width:16px; height:16px;" onchange="handleFastModeChange(this.checked)">
                                ⚡ Fast Mode (Auto Next)
                            </label>

                            <button type="button" id="toggleCamBtn" onclick="toggleCameraFacing()" style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; border: none; padding: 0.45rem 0.95rem; border-radius: 99px; font-weight: 700; font-size: 0.78rem; cursor: pointer; box-shadow: 0 4px 12px rgba(30,179,73,0.25); transition: all 0.2s;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M23 4v6h-6"/><path d="M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                                <span id="camFacingLabel">Balik Kamera</span>
                            </button>
                        </div>
                    </div>

                    {{-- Camera Viewport --}}
                    <div id="reader"></div>
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
                                        </td>
                                        <td>
                                            @if($ticket->status === 'used')
                                                <span class="badge-status badge-used">
                                                    ✓ Hadir (Checked-In)
                                                </span>
                                            @elseif($ticket->status === 'cancelled')
                                                <span class="badge-status badge-cancelled">
                                                    ✕ Dibatalkan
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
                                                            ✓ Check-In
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

    </div>

</div>

{{-- POPUP MODAL RESULT SCANNER (MOBILE FRIENDLY & TEMA BUYLE) --}}
<div id="scanResultModal" onclick="if(event.target===this)closeScanModal()" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.75); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:1rem; opacity:0; transition:all 0.25s ease;">
    <div id="scanResultModalBox" style="background:#ffffff; border-radius:24px; padding:1.75rem; width:100%; max-width:420px; text-align:center; box-shadow:0 25px 50px -12px rgba(0,0,0,0.3); transform:scale(0.9); transition:all 0.25s cubic-bezier(0.34,1.56,0.64,1);">
        
        {{-- Icon Bubble --}}
        <div id="modalIconBubble" style="width:72px; height:72px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;"></div>

        {{-- Status Title & Message --}}
        <h3 id="modalTitle" style="font-size:1.35rem; font-weight:800; margin:0 0 0.35rem; font-family:'Montserrat',sans-serif;"></h3>
        <p id="modalMsg" style="font-size:0.875rem; color:#64748B; margin:0 0 1.25rem; line-height:1.5;"></p>

        {{-- Ticket Detail Card --}}
        <div id="modalDetailsCard" style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:14px; padding:1rem; text-align:left; margin-bottom:1.5rem; font-size:0.85rem; display:none;"></div>

        {{-- Action Button --}}
        <button type="button" onclick="closeScanModal()" style="width:100%; padding:0.8rem; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; border:none; border-radius:999px; font-weight:700; font-size:0.95rem; cursor:pointer; box-shadow:0 4px 14px rgba(30,179,73,0.3); transition:all 0.2s;">
            Scan Tiket Berikutnya
        </button>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // ══════════════════════════════════════════════════════════════════════
    // DATA CACHE & OFFLINE-READY DATASET (CROWDED & LOW INTERNET SHIELD)
    // ══════════════════════════════════════════════════════════════════════
    const CREATOR_ID = "{{ auth()->id() }}";
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
    let html5QrCode = null;
    let availableCameras = [];
    let currentCameraId = null;
    let currentFacingMode = "environment";
    let isProcessing = false;
    let isCameraSwitching = false;

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
        const tabName = tabId === 'tab-data' ? 'data' : 'scanner';
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);

        if (tabId === 'tab-data') {
            runInstantFilter();
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Inisialisasi checkbox Fast Mode
        const fastToggle = document.getElementById('autoScanToggle');
        if (fastToggle) {
            fastToggle.checked = isFastMode;
        }

        // Jalankan inisialisasi kamera pintar
        requestCameraAndInit();
    });

    function handleFastModeChange(checked) {
        isFastMode = checked;
        try {
            localStorage.setItem('buyle_scanner_fast_mode', checked ? '1' : '0');
        } catch(e) {}
    }

    // ══════════════════════════════════════════════════════════════════════
    // INISIALISASI KAMERA & WEBCAM DESKTOP PINTAR
    // ══════════════════════════════════════════════════════════════════════
    async function requestCameraAndInit() {
        if (isCameraSwitching) return;
        isCameraSwitching = true;

        const reader = document.getElementById('reader');
        if (reader) {
            reader.innerHTML = `<div style="padding:2.5rem 1rem; text-align:center; color:#475569;">
                <svg width="32" height="32" stroke="#1eb349" stroke-width="2.5" fill="none" viewBox="0 0 24 24" style="animation:spin 1s linear infinite; margin-bottom:0.75rem;">
                    <circle cx="12" cy="12" r="10" stroke-dasharray="31" stroke-dashoffset="10"/>
                </svg>
                <div style="font-weight:700; font-size:0.9rem; color:#0f172a;">Menghubungkan Kamera...</div>
                <div style="font-size:0.78rem; color:#64748b; margin-top:4px;">Mendeteksi kamera laptop & webcam eksternal</div>
            </div>`;
        }

        try {
            // 1. Trigger permission prompt resmi browser jika belum diizinkan
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                try {
                    const tempStream = await navigator.mediaDevices.getUserMedia({ video: true });
                    tempStream.getTracks().forEach(track => track.stop());
                } catch (permErr) {
                    console.warn('[Camera] getUserMedia pre-check error:', permErr);
                }
            }

            // 2. Dapatkan seluruh daftar kamera (Laptop + Webcam USB)
            const cameras = await Html5Qrcode.getCameras();
            availableCameras = cameras || [];

            if (!availableCameras || availableCameras.length === 0) {
                showCameraError('Tidak ada kamera atau webcam yang terdeteksi di perangkat ini.');
                isCameraSwitching = false;
                return;
            }

            // 3. Populate Dropdown Kamera di UI
            populateCameraDropdown(availableCameras);

            // 4. Pilih kamera (prioritas yang pernah disimpan pengguna di localStorage)
            let chosenCamId = null;
            const savedCamId = localStorage.getItem('buyle_chosen_camera_id');
            if (savedCamId && availableCameras.some(c => c.id === savedCamId)) {
                chosenCamId = savedCamId;
            } else {
                // Pilih webcam eksternal jika ada, atau kamera pertama
                const usbOrBack = availableCameras.find(c => {
                    const l = (c.label || '').toLowerCase();
                    return l.includes('usb') || l.includes('external') || l.includes('webcam') || l.includes('back') || l.includes('rear');
                });
                chosenCamId = usbOrBack ? usbOrBack.id : availableCameras[0].id;
            }

            currentCameraId = chosenCamId;
            const camSelect = document.getElementById('cameraDeviceSelect');
            if (camSelect) camSelect.value = chosenCamId;

            // 5. Jalankan kamera
            await startCameraWithId(chosenCamId);

        } catch (err) {
            console.error('[Camera] Initialization error:', err);
            showCameraError(err.message || 'Gagal mengakses kamera. Silakan izinkan akses kamera di browser Anda.');
        } finally {
            isCameraSwitching = false;
        }
    }

    function populateCameraDropdown(cameras) {
        const select = document.getElementById('cameraDeviceSelect');
        if (!select) return;

        select.innerHTML = '';
        cameras.forEach((cam, idx) => {
            const opt = document.createElement('option');
            opt.value = cam.id;
            let label = cam.label || `Kamera ${idx + 1}`;
            if (label.toLowerCase().includes('usb') || label.toLowerCase().includes('external')) {
                opt.textContent = `📷 ${label}`;
            } else if (label.toLowerCase().includes('integrated') || label.toLowerCase().includes('front') || label.toLowerCase().includes('user')) {
                opt.textContent = `💻 ${label}`;
            } else {
                opt.textContent = `📹 ${label}`;
            }
            select.appendChild(opt);
        });
    }

    async function changeSelectedCamera(cameraId) {
        if (!cameraId || cameraId === currentCameraId) return;
        currentCameraId = cameraId;
        try {
            localStorage.setItem('buyle_chosen_camera_id', cameraId);
        } catch(e) {}
        await startCameraWithId(cameraId);
    }

    async function startCameraWithId(cameraId) {
        const config = {
            fps: 20,
            qrbox: function(viewfinderWidth, viewfinderHeight) {
                const minDim = Math.min(viewfinderWidth, viewfinderHeight);
                const boxSize = Math.max(220, Math.floor(minDim * 0.8));
                return { width: boxSize, height: boxSize };
            },
            aspectRatio: 1.0,
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            }
        };

        if (html5QrCode) {
            try {
                await html5QrCode.stop();
            } catch (e) {}
            try {
                html5QrCode.clear();
            } catch (e) {}
        }

        const reader = document.getElementById('reader');
        if (reader) reader.innerHTML = '';

        html5QrCode = new Html5Qrcode("reader");

        try {
            await html5QrCode.start(cameraId, config, onScanSuccess, onScanFailure);
            isCameraSwitching = false;
        } catch (err) {
            console.warn('[Camera] start by ID failed, trying fallback:', err);
            // Fallback: coba start dengan constraint generic
            try {
                await html5QrCode.start({ facingMode: currentFacingMode }, config, onScanSuccess, onScanFailure);
                isCameraSwitching = false;
            } catch (err2) {
                console.error('[Camera] start fallback also failed:', err2);
                showCameraError('Gagal memuat video stream kamera. Pastikan kamera tidak sedang dipakai oleh aplikasi lain.');
                isCameraSwitching = false;
            }
        }
    }

    function toggleCameraFacing() {
        if (isCameraSwitching) return;
        currentFacingMode = (currentFacingMode === "environment") ? "user" : "environment";
        const label = document.getElementById("camFacingLabel");
        if (label) label.textContent = (currentFacingMode === "environment") ? "Kamera Belakang" : "Kamera Depan";

        // Jika ada list kamera lebih dari 1, coba ganti device di dropdown
        if (availableCameras.length > 1) {
            const currentIndex = availableCameras.findIndex(c => c.id === currentCameraId);
            const nextIndex = (currentIndex + 1) % availableCameras.length;
            const nextCam = availableCameras[nextIndex];
            if (nextCam) {
                changeSelectedCamera(nextCam.id);
                return;
            }
        }

        startCameraWithId({ facingMode: currentFacingMode });
    }

    function showCameraError(msg) {
        isCameraSwitching = false;
        const reader = document.getElementById('reader');
        if (reader) {
            reader.innerHTML = `<div style="padding:2.5rem 1rem; text-align:center;">
                <div style="margin-bottom:0.75rem; display:flex; justify-content:center;">
                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.8"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </div>
                <div style="font-size:0.95rem; font-weight:800; color:#991b1b; margin-bottom:0.4rem;">Kamera Belum Dapat Diakses</div>
                <div style="font-size:0.8rem; color:#64748b; line-height:1.5; margin-bottom:1.25rem; max-width:380px; margin-left:auto; margin-right:auto;">
                    ${msg || 'Browser membutuhkan izin untuk membuka kamera laptop atau webcam USB Anda.'}
                </div>
                <div style="display:flex; justify-content:center; gap:0.5rem; flex-wrap:wrap;">
                    <button onclick="requestCameraAndInit()" style="display:inline-flex;align-items:center;gap:0.4rem;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;border:none;padding:0.6rem 1.5rem;border-radius:999px;font-size:0.84rem;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(30,179,73,0.35);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        Buka & Izinkan Kamera
                    </button>
                </div>
            </div>`;
        }
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

        const resultBox = document.getElementById('resultBox');
        if (resultBox) {
            resultBox.className = 'res-box';
            resultBox.style.background = '#f8fafc';
            resultBox.style.border = '1.5px solid #e2e8f0';
            resultBox.style.display = 'block';
            resultBox.innerHTML = `<div style="display:flex;align-items:center;gap:0.75rem;color:#334155;font-weight:600;font-size:0.88rem;">
                <svg width="20" height="20" stroke="#1eb349" stroke-width="2.5" fill="none" viewBox="0 0 24 24" style="animation:spin 1s linear infinite;">
                    <circle cx="12" cy="12" r="10" stroke-dasharray="31" stroke-dashoffset="10"/>
                </svg>
                Memverifikasi tiket: <strong style="color:#0f172a;margin-left:4px;">${code}</strong>...</div>`;
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
                message: 'Gagal terhubung ke server. Silakan coba periksa kembali dalam beberapa saat.'
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
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            if (status === 'valid') {
                // High bright double chime
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, ctx.currentTime);
                gain.gain.setValueAtTime(0.25, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.08);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.08);

                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(1174, ctx.currentTime + 0.1);
                gain2.gain.setValueAtTime(0.3, ctx.currentTime + 0.1);
                gain2.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.22);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(ctx.currentTime + 0.1);
                osc2.stop(ctx.currentTime + 0.22);
            } else {
                // Warning low buzzer
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(220, ctx.currentTime);
                gain.gain.setValueAtTime(0.25, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.25);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.25);
            }
        } catch(e) {}
    }

    // ══════════════════════════════════════════════════════════════════════
    // RENDER HASIL SCAN (FAST MODE TANPA POPUP BLOCKING)
    // ══════════════════════════════════════════════════════════════════════
    function renderResult(data) {
        playScanBeep(data.status);

        const isFast = document.getElementById('autoScanToggle')?.checked ?? true;
        const box = document.getElementById('resultBox');

        if (box) {
            box.style.display = 'block';
            let bgClass = 'res-invalid';
            let iconSvg = '';

            if (data.status === 'valid') {
                bgClass = 'res-valid';
                iconSvg = `<svg width="34" height="34" fill="none" stroke="#166534" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`;
            } else if (data.status === 'used') {
                bgClass = 'res-used';
                iconSvg = `<svg width="34" height="34" fill="none" stroke="#b45309" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`;
            } else {
                bgClass = 'res-invalid';
                iconSvg = `<svg width="34" height="34" fill="none" stroke="#991B1B" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
            }

            box.className = 'res-box ' + bgClass;

            let detailsHtml = '';
            if (data.ticket) {
                detailsHtml = `<div style="font-size:0.8rem;background:#ffffff;padding:0.6rem 0.85rem;border-radius:10px;border:1px solid #e2e8f0;margin-top:0.5rem;display:flex;gap:1rem;flex-wrap:wrap;">
                    <div><strong>Kode:</strong> ${data.ticket.code || '-'}</div>
                    <div><strong>Event:</strong> ${data.ticket.event_name || '-'}</div>
                    <div><strong>Pemegang:</strong> ${data.ticket.holder_name || '-'}</div>
                    ${data.ticket.checked_in_at ? `<div><strong>Jam:</strong> ${data.ticket.checked_in_at}</div>` : ''}
                </div>`;
            }

            box.innerHTML = `<div style="display:flex;align-items:flex-start;gap:0.85rem;">
                <div style="flex-shrink:0;margin-top:2px;">${iconSvg}</div>
                <div style="flex:1;">
                    <h5 style="font-weight:800;font-size:0.95rem;margin-bottom:0.2rem;">${data.title || 'Status Tiket'}</h5>
                    <p style="font-size:0.82rem;margin-bottom:0;line-height:1.4;">${data.message || ''}</p>
                    ${detailsHtml}
                </div>
            </div>`;
        }

        // MODE FAST: Siap scan tiket berikutnya dalam 800ms tanpa popup menghalangi
        if (isFast) {
            setTimeout(() => {
                isProcessing = false;
            }, 850);
        } else {
            // Mode Manual: Buka popup modal detail
            openScanModal(data);
        }
    }

    function openScanModal(data) {
        const overlay = document.getElementById('scanResultModal');
        const box     = document.getElementById('scanResultModalBox');
        const bubble  = document.getElementById('modalIconBubble');
        const titleEl = document.getElementById('modalTitle');
        const msgEl   = document.getElementById('modalMsg');
        const card    = document.getElementById('modalDetailsCard');

        if (data.status === 'valid') {
            bubble.style.background = '#F0FDF4';
            bubble.style.border = '1px solid #BBF7D0';
            bubble.innerHTML = `<svg width="40" height="40" fill="none" stroke="#166534" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`;
            titleEl.style.color = '#166534';
        } else if (data.status === 'used') {
            bubble.style.background = '#FEF3C7';
            bubble.style.border = '1px solid #FDE68A';
            bubble.innerHTML = `<svg width="40" height="40" fill="none" stroke="#B45309" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`;
            titleEl.style.color = '#B45309';
        } else {
            bubble.style.background = '#FEE2E2';
            bubble.style.border = '1px solid #FECACA';
            bubble.innerHTML = `<svg width="40" height="40" fill="none" stroke="#DC2626" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
            titleEl.style.color = '#DC2626';
        }

        titleEl.textContent = data.title || 'Status Tiket';
        msgEl.textContent   = data.message || '';

        if (data.ticket) {
            card.style.display = 'block';
            const t = data.ticket;
            card.innerHTML = `
                <div style="margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="color:#64748B;font-size:0.82rem;">Kode Tiket</span>
                    <strong style="color:#0F172A;font-family:monospace;font-size:0.88rem;">${t.code || '-'}</strong>
                </div>
                <div style="margin-bottom:8px;display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                    <span style="color:#64748B;font-size:0.82rem;flex-shrink:0;">Nama Event</span>
                    <strong style="color:#0F172A;font-size:0.82rem;text-align:right;">${t.event_name || '-'}</strong>
                </div>
                <div style="margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="color:#64748B;font-size:0.82rem;">Pemegang</span>
                    <strong style="color:#0F172A;font-size:0.82rem;">${t.holder_name || '-'}</strong>
                </div>
                ${t.checked_in_at ? `
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;border-top:1px solid #E2E8F0;margin-top:4px;">
                    <span style="color:#64748B;font-size:0.82rem;">Check-In</span>
                    <strong style="color:#166534;font-size:0.82rem;">${t.checked_in_at}</strong>
                </div>` : ''}
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

        overlay.style.opacity   = '0';
        box.style.transform     = 'scale(0.9)';
        setTimeout(() => {
            overlay.style.display = 'none';
            isProcessing = false;
        }, 220);
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
                    statusLabel = '✓ Hadir (Checked-In)';
                    btnHtml = `<button type="button" class="btn-action-sm btn-toggle-undo" onclick="toggleCheckinInstant(${t.id}, '${t.toggle_url}', this)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-2px;margin-right:3px;"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/></svg> Batalkan
                    </button>`;
                } else if (t.status === 'cancelled') {
                    badgeClass = 'badge-cancelled';
                    statusLabel = '✕ Dibatalkan';
                    btnHtml = `<span style="font-size: 0.75rem; color: #94a3b8;">Tidak Aktif</span>`;
                } else {
                    btnHtml = `<button type="button" class="btn-action-sm btn-toggle-checkin" onclick="toggleCheckinInstant(${t.id}, '${t.toggle_url}', this)">
                        ✓ Check-In
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
                    </td>
                    <td class="row-status">
                        <span class="badge-status ${badgeClass}">${statusLabel}</span>
                    </td>
                    <td class="row-time">${timeDisplay}</td>
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
</script>
@endsection
