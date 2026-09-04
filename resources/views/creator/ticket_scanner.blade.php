@extends('creator.layout')
@section('title', 'Scan Tiket & Data Kehadiran · Creator Studio')
@section('page_title', 'Scan Tiket & Data Kehadiran')

@section('styles')
<style>
    .bio-layout {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
    }

    .bio-sidebar {
        width: 300px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 20px;
        padding: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
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
        padding: 0.85rem 1rem;
        border: none;
        background: transparent;
        color: #64748b;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 12px;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s;
        margin-bottom: 0.35rem;
        text-decoration: none;
    }

    .tab-btn:hover {
        background: #f8fafc;
        color: #1eb349;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.2);
    }

    .prof-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e7f0e7;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
        overflow: hidden;
        max-width: 100%;
        box-sizing: border-box;
    }

    .prof-card-head {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f7f3;
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        background: #fafdfb;
    }

    .form-body {
        padding: 1.5rem;
        max-width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
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
        height: 44px;
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
    #reader select:focus {
        border-color: #1eb349 !important;
        box-shadow: 0 0 0 3px rgba(30,179,73,0.15);
    }

    #reader button {
        height: 42px;
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

    #reader a, #reader__dashboard_section_swaplink {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: #1eb349 !important;
        text-decoration: none !important;
        display: inline-block;
        margin-top: 0.75rem;
        padding: 0.45rem 1rem;
        border-radius: 12px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        transition: all 0.2s;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    #reader video, #reader canvas, #reader img, #reader div, #reader table {
        border-radius: 14px;
        max-width: 100% !important;
        height: auto !important;
        box-sizing: border-box !important;
        -webkit-transform: scaleX(1) !important;
        transform: scaleX(1) !important;
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
        height: 44px;
        padding: 0 0.85rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px 0 0 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        color: #1a1a1a;
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
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
    }

    .btn-submit-scan {
        height: 44px;
        padding: 0 1.25rem;
        border-radius: 0 12px 12px 0;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        flex-shrink: 0;
    }

    /* ── STATS GRID & TABLE ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
        position: relative;
        overflow: hidden;
    }

    .stat-card-title {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.35rem;
    }

    .stat-card-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .stat-card-sub {
        font-size: 0.75rem;
        color: #166534;
        font-weight: 600;
        margin-top: 0.3rem;
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.83rem;
        text-align: left;
    }

    .custom-table th {
        background: #f8fafc;
        padding: 0.9rem 1rem;
        font-weight: 700;
        color: #475569;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
    }

    .custom-table td {
        padding: 0.9rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }

    .custom-table tr:hover {
        background: #f0fdf4;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.75rem;
        border-radius: 99px;
        font-size: 0.73rem;
        font-weight: 700;
    }

    .badge-used { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-valid { background: #fef9c3; color: #a16207; border: 1px solid #fef08a; }
    .badge-cancelled { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

    .btn-action-sm {
        padding: 0.4rem 0.85rem;
        border-radius: 10px;
        font-size: 0.75rem;
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
        background: #1eb349;
        color: #fff;
    }
    .btn-toggle-checkin:hover { background: #16963c; }

    .btn-toggle-undo {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }
    .btn-toggle-undo:hover { background: #e2e8f0; }

    .btn-export {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-export:hover {
        background: #dcfce7;
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 991px) {
        .bio-layout { flex-direction: column; width: 100%; }
        .bio-sidebar { width: 100%; position: static; box-sizing: border-box; }
        .bio-content { width: 100%; max-width: 100%; box-sizing: border-box; }
        .form-body { padding: 1rem 0.85rem; }
    }
</style>
@endsection

@section('content')
<div class="bio-layout">

    {{-- SUB SIDEBAR NAV --}}
    <div class="bio-sidebar">
        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-radius: 16px; padding: 1.25rem; margin-bottom: 1.25rem;">
            <div style="font-size: 0.85rem; font-weight: 800; color: #15803d; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                Gatekeeper Studio
            </div>
            <div style="font-size: 0.78rem; color: #166534; line-height: 1.5; font-weight: 500;">
                Kelola pemindaian tiket live kamera dan pantau data rekap kehadiran pengunjung event Anda secara real-time.
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

        <div style="background: #fafdfb; border: 1px solid #e7f0e7; border-radius: 14px; padding: 1rem; font-size: 0.8rem; color: #475569; margin-top: 1.25rem;">
            <strong style="color: #0f172a; display: block; margin-bottom: 0.4rem;">💡 Tips Verifikasi:</strong>
            • Izinkan akses kamera browser saat diminta.<br>
            • Pastikan pencahayaan layar pengunjung cukup.<br>
            • Anda dapat menandai check-in manual pada tab Data Kehadiran jika pengunjung lupa membawa QR Code.
        </div>
    </div>

    {{-- CONTENT AREA --}}
    <div class="bio-content">

        @if(session('success'))
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:0.9rem 1.25rem; border-radius:14px; font-weight:600; font-size:0.85rem; margin-bottom:1.25rem;">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; padding:0.9rem 1.25rem; border-radius:14px; font-weight:600; font-size:0.85rem; margin-bottom:1.25rem;">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        {{-- TAB 1: SCANNER --}}
        <div id="tab-scanner" class="payout-tab-pane" style="display: {{ $activeTab === 'scanner' ? 'block' : 'none' }};">
            <div class="prof-card">
                <div class="prof-card-head">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                        Live Camera QR Ticket Scanner
                    </div>
                </div>

                <div class="form-body">
                    {{-- Input Manual --}}
                    <form id="manualScanForm" class="mb-4" onsubmit="handleManualSubmit(event)">
                        <label class="form-label" style="font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:0.4rem; display:block;">Pemeriksaan Kode Tiket / Token (Manual)</label>
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
                        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Data Kehadiran Pengunjung & Rekap Event
                    </div>
                    <a href="{{ route('creator.ticket.scanner.export', request()->all()) }}" class="btn-export">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export Excel/CSV
                    </a>
                </div>

                <div class="form-body">

                    {{-- Filter Bar --}}
                    <form method="GET" action="{{ route('creator.ticket.scanner') }}" style="margin-bottom: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;">
                        <input type="hidden" name="tab" value="data">

                        <div style="flex: 1; min-width: 200px;">
                            <select name="event_id" class="form-input" style="height: 42px; border-radius: 12px; font-size: 0.83rem; width: 100%;" onchange="this.form.submit()">
                                <option value="">— Semua Event Ticket —</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ (string)$selectedEventId === (string)$event->id ? 'selected' : '' }}>
                                        🎟️ {{ $event->name }} ({{ $event->event_date ? $event->event_date->format('d M Y') : 'Event' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="width: 160px;">
                            <select name="status" class="form-input" style="height: 42px; border-radius: 12px; font-size: 0.83rem; width: 100%;" onchange="this.form.submit()">
                                <option value="">Status Kehadiran</option>
                                <option value="used" {{ $statusFilter === 'used' ? 'selected' : '' }}>Checked-In (Hadir)</option>
                                <option value="valid" {{ $statusFilter === 'valid' ? 'selected' : '' }}>Belum Hadir</option>
                                <option value="cancelled" {{ $statusFilter === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div style="flex: 1; min-width: 220px; display: flex; gap: 0.4rem;">
                            <input type="text" name="search" value="{{ $search }}" class="form-input" placeholder="Cari nama, email, HP, atau kode..." style="height: 42px; border-radius: 12px; font-size: 0.83rem; flex: 1;">
                            <button type="submit" style="height: 42px; padding: 0 1rem; border-radius: 12px; background: #1eb349; color: #fff; border: none; font-weight: 700; cursor: pointer;">
                                Cari
                            </button>
                            @if($selectedEventId || $statusFilter || $search)
                                <a href="{{ route('creator.ticket.scanner', ['tab' => 'data']) }}" style="height: 42px; padding: 0 0.85rem; border-radius: 12px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: 600; display: inline-flex; align-items: center; text-decoration: none; font-size: 0.8rem;">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>

                    {{-- Stats Summary --}}
                    <div class="stat-grid">
                        <div class="stat-card" style="border-left: 4px solid #3b82f6;">
                            <div class="stat-card-title">Total Tiket Terbit</div>
                            <div class="stat-card-value" style="color: #1e40af;">{{ number_format($stats['total']) }}</div>
                            <div class="stat-card-sub" style="color: #64748b;">Tiket Terjual / Terdaftar</div>
                        </div>

                        <div class="stat-card" style="border-left: 4px solid #22c55e;">
                            <div class="stat-card-title">Checked-In (Hadir)</div>
                            <div class="stat-card-value" style="color: #15803d;">{{ number_format($stats['checked_in']) }}</div>
                            <div class="stat-card-sub" style="color: #15803d;">Tingkat Kehadiran: {{ $stats['rate'] }}%</div>
                        </div>

                        <div class="stat-card" style="border-left: 4px solid #eab308;">
                            <div class="stat-card-title">Belum Check-In</div>
                            <div class="stat-card-value" style="color: #a16207;">{{ number_format($stats['unchecked']) }}</div>
                            <div class="stat-card-sub" style="color: #a16207;">Tiket Belum Dipindai</div>
                        </div>

                        <div class="stat-card" style="border-left: 4px solid #ef4444;">
                            <div class="stat-card-title">Tiket Dibatalkan</div>
                            <div class="stat-card-value" style="color: #b91c1c;">{{ number_format($stats['cancelled']) }}</div>
                            <div class="stat-card-sub" style="color: #b91c1c;">Refund / Cancelled</div>
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
                                            <span style="font-family: monospace; font-weight: 700; font-size: 0.88rem; color: #0f172a;">
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
                                        <td colspan="7" style="text-align: center; padding: 2.5rem 1rem; color: #94a3b8;">
                                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 0.5rem; opacity: 0.4;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                            <div style="font-weight: 700; color: #475569; margin-bottom: 0.2rem;">Belum Ada Data Tiket / Kehadiran</div>
                                            <div style="font-size: 0.78rem;">Tiket pembeli yang sudah lunas akan otomatis muncul di tabel ini.</div>
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

        // Update URL parameter without page reload
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
                // High clean "TIT!" beep (880Hz for 0.14s)
                osc.frequency.setValueAtTime(880, ctx.currentTime);
                gain.gain.setValueAtTime(0.25, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.14);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.14);
            } else {
                // Low warning beep for used/invalid (320Hz for 0.25s)
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

        // Clean SVG Icons
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
