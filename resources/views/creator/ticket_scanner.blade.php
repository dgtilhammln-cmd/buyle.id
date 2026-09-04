@extends('creator.layout')
@section('title', 'Scan Tiket & Data Kehadiran · Creator Studio')
@section('page_title', 'Scan Tiket & Data Kehadiran')

@section('styles')
<style>
    .bio-layout {
        display: flex;
        gap: 1.75rem;
        align-items: flex-start;
    }

    .bio-sidebar {
        width: 280px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 20px;
        padding: 1.25rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #f0fdf4;
        position: sticky;
        top: 1.5rem;
    }

    .bio-content {
        flex: 1;
        min-width: 0;
        max-width: 100%;
    }

    .tab-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 0.9rem 1.1rem;
        border: none;
        background: transparent;
        color: #64748b;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 14px;
        cursor: pointer;
        text-align: left;
        transition: all 0.25s ease;
        margin-bottom: 0.35rem;
        text-decoration: none;
    }

    .tab-btn:hover {
        background: #f0fdf4;
        color: #1eb349;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 6px 18px rgba(30, 179, 73, 0.28);
    }

    .prof-card {
        background: #fff;
        border-radius: 22px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
        overflow: hidden;
        max-width: 100%;
        box-sizing: border-box;
    }

    .prof-card-head {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        background: #fafdfb;
    }

    .form-body {
        padding: 1.5rem;
        max-width: 100%;
        box-sizing: border-box;
    }

    /* ── SCANNER SPECIFIC ── */
    #reader {
        width: 100% !important;
        max-width: 100% !important;
        border-radius: 20px;
        overflow: hidden !important;
        border: 2px dashed #bbf7d0 !important;
        background: #f8fafc;
        padding: 1.25rem 0.75rem !important;
        text-align: center;
        margin-bottom: 1.5rem;
        box-sizing: border-box !important;
    }

    #reader select {
        height: 46px;
        padding: 0 2rem 0 1rem;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1 !important;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: #0f172a;
        background: #fff;
        outline: none;
        margin: 0.5rem 0;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: all 0.2s;
        max-width: 100% !important;
        width: 100% !important;
        box-sizing: border-box !important;
        display: block;
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
        color: #fff !important;
        cursor: pointer;
        margin: 0.5rem auto;
        box-shadow: 0 4px 14px rgba(30,179,73,0.35) !important;
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

    .res-valid { background: #F0FDF4; border: 1.5px solid #86EFAC; color: #166534; }
    .res-used { background: #FEFCE8; border: 1.5px solid #FDE047; color: #854D0E; }
    .res-invalid { background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; }

    .form-input-code {
        height: 46px;
        padding: 0 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px 0 0 14px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        color: #0f172a;
        background: #f8fafc;
        outline: none;
        flex: 1;
        min-width: 0;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .form-input-code:focus {
        border-color: #1eb349;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
    }

    .btn-submit-scan {
        height: 46px;
        padding: 0 1.35rem;
        border-radius: 0 14px 14px 0;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.2);
    }

    /* ── FILTER PANEL & CUSTOM CONTROLS ── */
    .filter-panel {
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
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
        border: 1.5px solid #cbd5e1;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.83rem;
        font-weight: 600;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        box-sizing: border-box;

        /* Custom dropdown arrow */
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
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
        background-color: #ffffff;
    }

    .btn-search {
        height: 44px;
        padding: 0 1.25rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        border: none;
        color: #fff;
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
        border: 1.5px solid #cbd5e1;
        color: #475569;
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
        background: #f1f5f9;
        color: #0f172a;
    }

    /* ── STATS GRID (PREMIUM CARDS) ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        border-radius: 18px;
        padding: 1.2rem;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.06);
    }

    .card-blue {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1.5px solid #bfdbfe;
    }

    .card-green {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1.5px solid #bbf7d0;
    }

    .card-amber {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1.5px solid #fde68a;
    }

    .card-red {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 1.5px solid #fecaca;
    }

    .stat-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.6rem;
    }

    .stat-icon-bubble {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .card-blue .stat-icon-bubble { background: #3b82f6; }
    .card-green .stat-icon-bubble { background: #16a34a; }
    .card-amber .stat-icon-bubble { background: #d97706; }
    .card-red .stat-icon-bubble { background: #dc2626; }

    .stat-title {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .card-blue .stat-title { color: #1e40af; }
    .card-green .stat-title { color: #166534; }
    .card-amber .stat-title { color: #92400e; }
    .card-red .stat-title { color: #991b1b; }

    .stat-val {
        font-size: 1.75rem;
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .card-blue .stat-val { color: #1e3a8a; }
    .card-green .stat-val { color: #14532d; }
    .card-amber .stat-val { color: #78350f; }
    .card-red .stat-val { color: #7f1d1d; }

    .stat-sub {
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .card-blue .stat-sub { color: #2563eb; }
    .card-green .stat-sub { color: #15803d; }
    .card-amber .stat-sub { color: #b45309; }
    .card-red .stat-sub { color: #b91c1c; }

    .pill-badge {
        display: inline-block;
        padding: 0.15rem 0.55rem;
        border-radius: 99px;
        font-size: 0.7rem;
        font-weight: 800;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    /* ── TABLE DESIGN ── */
    .table-container {
        width: 100%;
        overflow-x: auto;
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.83rem;
        text-align: left;
    }

    .custom-table th {
        background: #f8fafc;
        padding: 0.95rem 1.1rem;
        font-weight: 800;
        color: #475569;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .custom-table td {
        padding: 1rem 1.1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }

    .custom-table tr:last-child td {
        border-bottom: none;
    }

    .custom-table tr:hover {
        background: #f0fdf4;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.85rem;
        border-radius: 99px;
        font-size: 0.74rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-used { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-valid { background: #fef9c3; color: #a16207; border: 1px solid #fef08a; }
    .badge-cancelled { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

    .btn-action-sm {
        padding: 0.45rem 0.95rem;
        border-radius: 10px;
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
        color: #fff;
        box-shadow: 0 3px 10px rgba(30,179,73,0.25);
    }
    .btn-toggle-checkin:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(30,179,73,0.35);
    }

    .btn-toggle-undo {
        background: #ffffff;
        color: #475569;
        border: 1.5px solid #cbd5e1;
    }
    .btn-toggle-undo:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .btn-export {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1.5px solid #bbf7d0;
        color: #15803d;
        padding: 0.55rem 1.1rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(30, 179, 73, 0.1);
    }
    .btn-export:hover {
        background: #dcfce7;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.2);
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

    /* ── RESPONSIVE MOBILE FIXES ── */
    @media (max-width: 991px) {
        .bio-layout { flex-direction: column; width: 100%; gap: 1.25rem; }
        .bio-sidebar { width: 100%; position: static; box-sizing: border-box; }
        .bio-content { width: 100%; max-width: 100%; box-sizing: border-box; }
        .form-body { padding: 1.15rem 1rem; }
        
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
        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-radius: 16px; padding: 1.25rem; margin-bottom: 1.25rem;">
            <div style="font-size: 0.85rem; font-weight: 800; color: #15803d; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                Gatekeeper Studio
            </div>
            <div style="font-size: 0.78rem; color: #166534; line-height: 1.5; font-weight: 500;">
                Pemindaian live kamera & pemantauan data kehadiran pengunjung event secara real-time.
            </div>
        </div>

        <button type="button" class="tab-btn {{ $activeTab === 'scanner' ? 'active' : '' }}" onclick="switchTab('tab-scanner', this)">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
            Live QR Scanner
        </button>
        <button type="button" class="tab-btn {{ $activeTab === 'data' ? 'active' : '' }}" onclick="switchTab('tab-data', this)">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Data Kehadiran & Event
        </button>

        <div style="background: #fafdfb; border: 1px solid #e7f0e7; border-radius: 14px; padding: 1rem; font-size: 0.78rem; color: #475569; margin-top: 1.25rem;">
            <strong style="color: #0f172a; display: block; margin-bottom: 0.4rem;">💡 Tips Verifikasi:</strong>
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
                                <div id="ticketDetails" style="font-size: 0.8rem; background: rgba(255,255,255,0.8); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid rgba(0,0,0,0.05); display: none;"></div>
                            </div>
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
                    <a href="{{ route('creator.ticket.scanner.export', request()->all()) }}" class="btn-export">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export Excel/CSV
                    </a>
                </div>

                <div class="form-body">

                    {{-- Modern Filter Panel --}}
                    <form method="GET" action="{{ route('creator.ticket.scanner') }}" class="filter-panel">
                        <input type="hidden" name="tab" value="data">

                        <div class="filter-grid">
                            {{-- Event Selector --}}
                            <div class="input-icon-wrap">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="icon-left"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <select name="event_id" class="filter-select" onchange="this.form.submit()">
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
                                <select name="status" class="filter-select" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="used" {{ $statusFilter === 'used' ? 'selected' : '' }}>Checked-In (Hadir)</option>
                                    <option value="valid" {{ $statusFilter === 'valid' ? 'selected' : '' }}>Belum Hadir</option>
                                    <option value="cancelled" {{ $statusFilter === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            {{-- Search Input & Action Buttons --}}
                            <div style="display: flex; gap: 0.5rem; width: 100%;">
                                <div class="input-icon-wrap" style="flex: 1;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="icon-left"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                                    <input type="text" name="search" value="{{ $search }}" class="filter-input" placeholder="Cari nama, email, HP, kode...">
                                </div>
                                
                                <button type="submit" class="btn-search">
                                    Cari
                                </button>
                                
                                @if($selectedEventId || $statusFilter || $search)
                                    <a href="{{ route('creator.ticket.scanner', ['tab' => 'data']) }}" class="btn-reset">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    {{-- Premium Stat Cards Grid --}}
                    <div class="stat-grid">
                        {{-- Card 1: Total Tiket --}}
                        <div class="stat-card card-blue">
                            <div class="stat-head">
                                <span class="stat-title">Total Tiket Terbit</span>
                                <div class="stat-icon-bubble">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/><path d="M13 5v14"/></svg>
                                </div>
                            </div>
                            <div class="stat-val">{{ number_format($stats['total']) }}</div>
                            <div class="stat-sub">
                                <span>Tiket Terjual / Terdaftar</span>
                            </div>
                        </div>

                        {{-- Card 2: Checked-In --}}
                        <div class="stat-card card-green">
                            <div class="stat-head">
                                <span class="stat-title">Checked-In (Hadir)</span>
                                <div class="stat-icon-bubble">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </div>
                            </div>
                            <div class="stat-val">{{ number_format($stats['checked_in']) }}</div>
                            <div class="stat-sub">
                                <span class="pill-badge" style="color: #166534;">Tingkat Kehadiran: {{ $stats['rate'] }}%</span>
                            </div>
                        </div>

                        {{-- Card 3: Belum Check-In --}}
                        <div class="stat-card card-amber">
                            <div class="stat-head">
                                <span class="stat-title">Belum Check-In</span>
                                <div class="stat-icon-bubble">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </div>
                            </div>
                            <div class="stat-val">{{ number_format($stats['unchecked']) }}</div>
                            <div class="stat-sub">
                                <span>Tiket Belum Dipindai</span>
                            </div>
                        </div>

                        {{-- Card 4: Dibatalkan --}}
                        <div class="stat-card card-red">
                            <div class="stat-head">
                                <span class="stat-title">Tiket Dibatalkan</span>
                                <div class="stat-icon-bubble">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                </div>
                            </div>
                            <div class="stat-val">{{ number_format($stats['cancelled']) }}</div>
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
                            <tbody>
                                @forelse($tickets as $index => $ticket)
                                    <tr>
                                        <td>{{ $tickets->firstItem() + $index }}</td>
                                        <td>
                                            <span style="font-family: monospace; font-weight: 700; font-size: 0.88rem; color: #0f172a; background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid #e2e8f0;">
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
                                                    ⏳ Belum Hadir
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ticket->checked_in_at)
                                                <span style="font-weight: 700; color: #15803d; display: block; font-size: 0.8rem;">
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
                                                            ↩️ Batalkan
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

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrcodeScanner = null;
    let isProcessing = false;

    function switchTab(tabId, btn) {
        document.querySelectorAll('.payout-tab-pane').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        
        document.getElementById(tabId).style.display = 'block';
        btn.classList.add('active');

        const tabName = tabId === 'tab-data' ? 'data' : 'scanner';
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);
    }

    document.addEventListener("DOMContentLoaded", function() {
        html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            rememberLastUsedCamera: true
        }, false);

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        verifyCode(decodedText);
    }

    function onScanFailure(error) {
        // ignore scan failures
    }

    function handleManualSubmit(e) {
        e.preventDefault();
        const code = document.getElementById('manualCodeInput').value.trim();
        if (code) verifyCode(code);
    }

    function verifyCode(code) {
        isProcessing = true;

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
            renderResult(data);
            setTimeout(() => { isProcessing = false; }, 2000);
        })
        .catch(err => {
            renderResult({
                status: 'invalid',
                title: 'Gagal Memproses',
                message: 'Terjadi kesalahan sistem saat memverifikasi tiket.'
            });
            setTimeout(() => { isProcessing = false; }, 2000);
        });
    }

    function playScanBeep(status = 'valid') {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';
            
            if (status === 'valid') {
                osc.frequency.setValueAtTime(880, ctx.currentTime);
                gain.gain.setValueAtTime(0.25, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.14);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.14);
            } else {
                osc.frequency.setValueAtTime(320, ctx.currentTime);
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.25);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.25);
            }
        } catch(e) {
            console.log('Audio beep error:', e);
        }
    }

    function renderResult(data) {
        playScanBeep(data.status);

        const box = document.getElementById('resultBox');
        const iconDiv = document.getElementById('resultIcon');
        const titleEl = document.getElementById('resultTitle');
        const msgEl = document.getElementById('resultMsg');
        const detailsEl = document.getElementById('ticketDetails');

        box.className = 'res-box res-' + data.status;
        box.style.display = 'block';

        titleEl.textContent = data.title || 'Status Tiket';
        msgEl.textContent = data.message || '';

        if (data.status === 'valid') {
            iconDiv.innerHTML = `<svg width="36" height="36" fill="none" stroke="#166534" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`;
        } else if (data.status === 'used') {
            iconDiv.innerHTML = `<svg width="36" height="36" fill="none" stroke="#854D0E" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`;
        } else {
            iconDiv.innerHTML = `<svg width="36" height="36" fill="none" stroke="#991B1B" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
        }

        if (data.ticket) {
            detailsEl.style.display = 'block';
            detailsEl.innerHTML = `
                <div><strong>Kode Tiket:</strong> ${data.ticket.code || '-'}</div>
                <div><strong>Nama Event:</strong> ${data.ticket.event_name || '-'}</div>
                <div><strong>Pemegang Tiket:</strong> ${data.ticket.holder_name || '-'}</div>
                ${data.ticket.checked_in_at ? `<div><strong>Waktu Check-In:</strong> ${data.ticket.checked_in_at}</div>` : ''}
            `;
        } else {
            detailsEl.style.display = 'none';
        }
    }
</script>
@endsection
