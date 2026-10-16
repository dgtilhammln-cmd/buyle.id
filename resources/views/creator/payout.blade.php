@extends('creator.layout')
@section('title', 'Saldo & Pencairan · Creator Dashboard')
@section('page_title', 'Saldo & Pencairan')

@section('styles')
<style>
    .bio-layout {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
    }
    .bio-sidebar {
        width: 290px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 20px;
        padding: 1.25rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #f0fdf4;
        position: sticky;
        top: 1.5rem;
    }
    .bio-content { flex: 1; min-width: 0; }

    /* ── Sidebar balance cards ── */
    .saldo-grid {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .saldo-card {
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .saldo-card.cleared {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1.5px solid #86efac;
    }
    .saldo-card.hold {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1.5px solid #fde68a;
    }
    .saldo-card.escrow {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1.5px solid #93c5fd;
    }
    .saldo-card-label {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .saldo-card.cleared .saldo-card-label { color: #15803d; }
    .saldo-card.hold .saldo-card-label { color: #92400e; }
    .saldo-card.escrow .saldo-card-label { color: #1e40af; }
    .saldo-card-value {
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }
    .saldo-card.cleared .saldo-card-value { color: #14532d; }
    .saldo-card.hold .saldo-card-value { color: #78350f; }
    .saldo-card.escrow .saldo-card-value { color: #1e3a8a; }
    .saldo-card-sub {
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.3;
        margin-top: 0.15rem;
    }
    .saldo-card.cleared .saldo-card-sub { color: #16a34a; }
    .saldo-card.hold .saldo-card-sub { color: #d97706; }
    .saldo-card.escrow .saldo-card-sub { color: #2563eb; }

    /* ── Tab Sidebar ── */
    .sidebar-nav-label {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        padding: 0 0.5rem 0.5rem;
    }
    .tab-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.9rem 1.1rem;
        border: none;
        background: transparent;
        color: #475569;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 12px;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s;
        margin-bottom: 0.35rem;
    }
    .tab-btn:hover { background: #f8fafc; color: #1eb349; }
    .tab-btn.active {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(30,179,73,0.25);
    }
    .sidebar-info-card {
        margin-top: 1.75rem;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        padding: 1rem 1.1rem;
    }

    /* ── Content Cards ── */
    .prof-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e7f0e7;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .prof-card-head {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid #f3f7f3;
        font-size: 0.9rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #fafdfb;
    }

    /* ── Rekening Tersimpan Card ── */
    .rekening-saved {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }
    .rekening-info { display: flex; align-items: center; gap: 0.65rem; }
    .rekening-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .rekening-bank { font-weight: 800; font-size: 0.88rem; color: #0f172a; }
    .rekening-num  { font-size: 0.8rem; color: #64748b; font-weight: 600; letter-spacing: 0.03em; }
    .rekening-name { font-size: 0.72rem; color: #94a3b8; font-weight: 500; }
    .btn-ganti {
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        border-radius: 8px;
        padding: 0.4rem 0.875rem;
        font-size: 0.72rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-ganti:hover { border-color: #1eb349; color: #1eb349; }

    /* ── Breakdown Box ── */
    .breakdown-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        margin-bottom: 1.25rem;
    }
    .breakdown-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.55rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.82rem;
    }
    .breakdown-row:last-child { border-bottom: none; }
    .breakdown-key { color: #64748b; font-weight: 600; }
    .breakdown-val { font-weight: 700; color: #0f172a; }
    .breakdown-val.green { color: #1eb349; font-size: 1rem; }
    .breakdown-val.red   { color: #ef4444; }
    .breakdown-val.muted { color: #94a3b8; }

    /* ── Quick Amount Buttons ── */
    .quick-amounts {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.4rem;
        margin-bottom: 0.5rem;
    }
    .btn-quick {
        height: 36px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.15s;
    }
    .btn-quick:hover {
        border-color: #1eb349;
        color: #1eb349;
        background: #f0fdf4;
    }

    /* ── Form Elements ── */
    .form-body { padding: 1.5rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.1rem; }
    .form-label { font-size: 0.8rem; font-weight: 700; color: #334155; }
    .form-input {
        height: 44px;
        padding: 0 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.875rem;
        color: #1a1a1a;
        background: #f8fafc;
        outline: none;
        transition: all 0.2s;
        width: 100%;
        box-sizing: border-box;
    }
    .form-input:focus {
        border-color: #1eb349;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30,179,73,0.1);
    }
    .form-hint { font-size: 0.72rem; color: #94a3b8; margin-top: 0.25rem; }

    /* ── Submit Button ── */
    .btn-submit-payout {
        height: 48px;
        padding: 0 1.5rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.875rem;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(30,179,73,0.35);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        margin-top: 0.75rem;
    }
    .btn-submit-payout:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(30,179,73,0.45);
    }
    .btn-submit-payout:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    /* ── Custom Searchable Dropdown ── */
    .bank-select-wrap { position: relative; }
    .bank-select-trigger {
        height: 46px;
        padding: 0 1.1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        background: #f8fafc;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        transition: all 0.2s;
        user-select: none;
        box-sizing: border-box;
    }
    .bank-select-trigger:hover,
    .bank-select-trigger.open {
        border-color: #1eb349;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30,179,73,0.1);
    }
    .bank-select-trigger.open svg.chevron { transform: rotate(180deg); color: #1eb349; }
    .bank-select-trigger svg.chevron { transition: transform 0.2s; flex-shrink: 0; }
    .bank-select-display { display: flex; align-items: center; gap: 0.5rem; flex: 1; overflow: hidden; }
    .bank-select-display .bank-type-badge {
        font-size: 0.6rem;
        font-weight: 800;
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        flex-shrink: 0;
    }

    .bank-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0; right: 0;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 16px 40px rgba(0,0,0,0.12), 0 4px 12px rgba(30,179,73,0.08);
        z-index: 999;
        overflow: hidden;
    }
    .bank-dropdown.open { display: block; animation: dropIn 0.15s ease-out; }
    @keyframes dropIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .bank-search-wrap {
        padding: 0.75rem 0.875rem 0.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .bank-search {
        width: 100%;
        height: 38px;
        padding: 0 0.875rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        color: #0f172a;
        background: #f8fafc;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.15s;
    }
    .bank-search:focus { border-color: #1eb349; background: #fff; }
    .bank-list-scroll {
        max-height: 260px;
        overflow-y: auto;
        padding: 0.4rem;
    }
    .bank-list-scroll::-webkit-scrollbar { width: 5px; }
    .bank-list-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .bank-group-label {
        font-size: 0.62rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #94a3b8;
        padding: 0.5rem 0.65rem 0.25rem;
    }
    .bank-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 0.65rem;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.12s;
        font-size: 0.82rem;
        font-weight: 600;
        color: #334155;
    }
    .bank-option:hover { background: #f0fdf4; color: #1eb349; }
    .bank-option.selected {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #fff;
        font-weight: 700;
    }
    .bank-option-badge {
        font-size: 0.58rem;
        font-weight: 800;
        padding: 0.1rem 0.35rem;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-left: auto;
        flex-shrink: 0;
    }
    .bank-option.selected .bank-option-badge { background: rgba(255,255,255,0.25); color: #fff; }
    .bank-option:not(.selected) .bank-option-badge {
        background: #f1f5f9;
        color: #64748b;
    }
    .bank-no-result {
        text-align: center;
        padding: 1.25rem;
        color: #94a3b8;
        font-size: 0.8rem;
    }

    /* ── Alert Notice ── */
    .alert-notice {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 0.875rem 1.1rem;
        font-size: 0.8rem;
        color: #15803d;
        margin-bottom: 1.25rem;
        line-height: 1.6;
        display: flex;
        gap: 0.6rem;
        align-items: flex-start;
    }

    /* ── History Table ── */
    .hist-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
    }
    .hist-table th {
        padding: 0.7rem 1rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        white-space: nowrap;
    }
    .hist-table td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
        color: #334155;
    }
    .hist-table tr:last-child td { border-bottom: none; }
    .hist-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.6rem;
        border-radius: 99px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* ── Responsive ── */
    .tab-text-full { display: inline-block; }
    .tab-text-mob  { display: none; }

    @media (max-width: 1024px) {
        .bio-layout { flex-direction: column; gap: 1rem; }
        .bio-sidebar { width: 100%; position: static; border-radius: 14px; padding: 0.75rem; }
        .sidebar-balance-hide { display: none !important; }
        .bio-tabs-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3px;
            background: #f8fafc;
            padding: 3px;
            border-radius: 12px;
        }
        .tab-btn {
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0.5rem 0.25rem;
            gap: 3px;
            border-radius: 8px;
            margin-bottom: 0;
        }
        .tab-text-full { display: none !important; }
        .tab-text-mob  { display: block !important; font-size: 0.65rem; font-weight: 600; text-align: center; }
        .saldo-grid { display: none !important; }
        .quick-amounts { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 600px) {
        .hist-table th:nth-child(4),
        .hist-table td:nth-child(4) { display: none; }
    }
</style>
@endsection

@section('content')
@php
    $banks = [
        'E-Wallet' => ['GoPay','OVO','Dana','ShopeePay','LinkAja','AstraPay'],
        'Bank Nasional' => ['Bank BCA','Bank Mandiri','Bank BRI','Bank BNI','Bank BSI','CIMB Niaga','Permata Bank','Bank Danamon','Bank BTN','Bank Panin','OCBC NISP','Bank Maybank','Bank Mega','Bank Sinarmas'],
        'Bank Digital' => ['Bank Jago','SeaBank','Blu by BCA Digital','Jenius / SMBC','Allo Bank','Bank Neo Commerce','Line Bank (KEB Hana)','Superbank'],
        'Bank Daerah' => ['Bank DKI','Bank BJB','Bank Jatim','Bank Jateng','Bank Sumut','Bank Nagari','Bank Kalbar','Bank Sulselbar'],
    ];
    $bankTypeColors = [
        'E-Wallet'     => ['bg' => '#EFF6FF', 'color' => '#1D4ED8'],
        'Bank Nasional' => ['bg' => '#F0FDF4', 'color' => '#166534'],
        'Bank Digital' => ['bg' => '#FFF7ED', 'color' => '#C2410C'],
        'Bank Daerah'  => ['bg' => '#F5F3FF', 'color' => '#6D28D9'],
    ];
    $allBanks = collect($banks)->flatMap(fn($b) => $b)->values()->toArray();
    $savedBank  = $seller->bank_name ?? '';
    $savedNum   = $seller->bank_account_number ?? '';
    $savedName  = $seller->bank_account_name ?? '';
    $hasSaved   = !empty($savedBank) && !empty($savedNum);
@endphp

<div class="bio-layout">

    {{-- SIDEBAR --}}
    <div class="bio-sidebar">

        {{-- Saldo Cards (Desktop) --}}
        <div class="sidebar-balance-hide">
            <div class="saldo-grid">
                <div class="saldo-card cleared">
                    <div class="saldo-card-label">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Siap Ditarik
                    </div>
                    <div class="saldo-card-value">Rp {{ number_format($availableBalance, 0, ',', '.') }}</div>
                    <div class="saldo-card-sub">Dapat ditarik kapan saja</div>
                </div>
                <div class="saldo-card hold">
                    <div class="saldo-card-label">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Hold Reguler (3 Hari)
                    </div>
                    <div class="saldo-card-value">Rp {{ number_format($standardHoldBalance ?? 0, 0, ',', '.') }}</div>
                    <div class="saldo-card-sub">Otomatis cair setelah 3x24 jam</div>
                </div>
                @if(($ticketEscrowBalance ?? 0) > 0)
                <div class="saldo-card escrow">
                    <div class="saldo-card-label">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Escrow Tiket Event
                    </div>
                    <div class="saldo-card-value">Rp {{ number_format($ticketEscrowBalance, 0, ',', '.') }}</div>
                    <div class="saldo-card-sub">Cair H+3 setelah acara terlaksana</div>
                </div>
                @endif
            </div>
        </div>

        <div class="bio-tabs-list">
            <div class="sidebar-nav-label">Menu Navigasi</div>
            <button type="button" class="tab-btn active" onclick="switchPayoutTab('tab-withdraw', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="16" cy="12" r="2"/><path d="M6 12h.01"/></svg>
                <span class="tab-text-full">Tarik Saldo</span>
                <span class="tab-text-mob">Tarik</span>
            </button>
            <button type="button" class="tab-btn" onclick="switchPayoutTab('tab-bank', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span class="tab-text-full">Pengaturan Rekening</span>
                <span class="tab-text-mob">Rekening</span>
            </button>
            <button type="button" class="tab-btn" onclick="switchPayoutTab('tab-history', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span class="tab-text-full">Riwayat Pencairan</span>
                <span class="tab-text-mob">Riwayat</span>
            </button>
        </div>

        <div class="sidebar-info-card">
            <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.75rem;font-weight:800;color:#16a34a;margin-bottom:0.35rem;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                100% Bebas Potongan
            </div>
            <div style="font-size:0.7rem;color:#64748b;line-height:1.45;">
                Dana penjualan creator dicairkan penuh tanpa potongan biaya admin penarikan.
            </div>
        </div>
    </div>

    {{-- CONTENT AREA --}}
    <div class="bio-content">

        {{-- TAB 1: TARIK SALDO --}}
        <div id="tab-withdraw" class="payout-tab-pane" style="display:block">

            {{-- Mobile: Saldo Summary --}}
            <div style="display:none" class="mobile-saldo-bar">
                <div class="prof-card" style="margin-bottom:1rem;">
                    <div class="form-body" style="padding:1rem 1.25rem;">
                        <div class="saldo-grid" style="display:flex!important;flex-direction:row!important;flex-wrap:wrap;gap:0.5rem;">
                            <div class="saldo-card cleared" style="flex:1;min-width:130px;">
                                <div class="saldo-card-label">Siap Ditarik</div>
                                <div class="saldo-card-value">Rp {{ number_format($availableBalance, 0, ',', '.') }}</div>
                                <div class="saldo-card-sub">Dapat ditarik</div>
                            </div>
                            <div class="saldo-card hold" style="flex:1;min-width:130px;">
                                <div class="saldo-card-label">Hold Reguler</div>
                                <div class="saldo-card-value">Rp {{ number_format($standardHoldBalance ?? 0, 0, ',', '.') }}</div>
                                <div class="saldo-card-sub">Hold 3 hari</div>
                            </div>
                            @if(($ticketEscrowBalance ?? 0) > 0)
                            <div class="saldo-card escrow" style="flex:100%;min-width:100%;">
                                <div class="saldo-card-label">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    Escrow Tiket Event
                                </div>
                                <div class="saldo-card-value">Rp {{ number_format($ticketEscrowBalance, 0, ',', '.') }}</div>
                                <div class="saldo-card-sub">Cair H+3 setelah acara terlaksana</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="prof-card">
                <div class="prof-card-head">
                    <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="16" cy="12" r="2"/><path d="M6 12h.01"/></svg>
                    Ajukan Penarikan Dana
                </div>
                <div class="form-body">
                    {{-- Syarat --}}
                    <div class="alert-notice">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <div>
                            Dana produk reguler dapat ditarik setelah <strong>3x24 jam</strong> sejak pembayaran diterima. Khusus <strong>tiket event</strong>, dana di-hold hingga <strong>H+3 pasca event</strong> untuk keamanan bersama. Pencairan diproses <strong>1-3 hari kerja</strong> tanpa potongan biaya (100% utuh), minimal penarikan <strong>Rp 50.000</strong>.
                        </div>
                    </div>

                    @if(($ticketEscrowBalance ?? 0) > 0)
                    {{-- Escrow Security Protection Alert --}}
                    <div style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:14px;padding:1rem 1.15rem;margin-bottom:1.25rem;">
                        <div style="display:flex;align-items:center;gap:0.5rem;font-weight:800;color:#1e40af;font-size:0.82rem;margin-bottom:0.35rem;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Mitigasi Keamanan Dana Event (Escrow Protection)
                        </div>
                        <p style="font-size:0.75rem;color:#1e3a8a;line-height:1.5;margin:0 0 0.75rem 0;">
                            Untuk melindungi pembeli tiket serta menjamin acara terlaksana dengan aman, seluruh hasil penjualan tiket event dikunci di sistem Escrow dan <strong>baru dapat ditarik H+3 setelah tanggal pelaksanaan acara</strong>.
                        </p>
                        @if(!empty($upcomingEvents) && count($upcomingEvents) > 0)
                        <div style="background:#fff;border-radius:10px;border:1px solid #dbeafe;padding:0.6rem 0.85rem;">
                            <div style="font-size:0.68rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.4rem;">Jadwal Pelepasan Dana Event</div>
                            @foreach($upcomingEvents as $evt)
                            @php
                                $evtName = $evt['name'] ?? $evt['title'] ?? 'Event';
                                $evtAmount = $evt['amount'] ?? $evt['escrow_amount'] ?? 0;
                                $evtClearance = $evt['clearance_date'] ?? $evt['release_date'] ?? '-';
                                $evtDate = $evt['event_date'] ?? '-';
                            @endphp
                            <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.75rem;padding:0.35rem 0;border-bottom:1px dashed #f1f5f9;">
                                <div>
                                    <span style="font-weight:700;color:#1e293b;">{{ $evtName }}</span>
                                    <span style="font-size:0.68rem;color:#64748b;margin-left:0.35rem;">(Acara: {{ $evtDate }})</span>
                                </div>
                                <div style="text-align:right;">
                                    <span style="font-weight:700;color:#1e40af;">Rp {{ number_format($evtAmount, 0, ',', '.') }}</span>
                                    <span style="display:block;font-size:0.65rem;color:#059669;font-weight:600;">Cair: {{ $evtClearance }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Saldo Breakdown --}}
                    <div class="breakdown-box">
                        <div class="breakdown-row">
                            <span class="breakdown-key">Total Penjualan (GMV)</span>
                            <span class="breakdown-val">Rp {{ number_format($gmv, 0, ',', '.') }}</span>
                        </div>
                        @if(($standardHoldBalance ?? 0) > 0)
                        <div class="breakdown-row">
                            <span class="breakdown-key">Hold Reguler (3x24 jam)</span>
                            <span class="breakdown-val muted">Rp {{ number_format($standardHoldBalance, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if(($ticketEscrowBalance ?? 0) > 0)
                        <div class="breakdown-row">
                            <span class="breakdown-key" style="color:#1e40af;font-weight:700;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline;vertical-align:-1px;margin-right:2px"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                Escrow Tiket Event (H+3 Acara)
                            </span>
                            <span class="breakdown-val" style="color:#1e40af;font-weight:700;">Rp {{ number_format($ticketEscrowBalance, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="breakdown-row">
                            <span class="breakdown-key">Sudah Dicairkan</span>
                            <span class="breakdown-val muted">Rp {{ number_format($totalPayout, 0, ',', '.') }}</span>
                        </div>
                        <div class="breakdown-row" style="font-size:0.95rem;">
                            <span class="breakdown-key" style="font-weight:800;color:#0f172a;">Saldo Siap Ditarik</span>
                            <span class="breakdown-val green">Rp {{ number_format($availableBalance, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($availableBalance >= 50000)

                    {{-- Rekening Tujuan Tersimpan --}}
                    @if($hasSaved)
                    <div id="savedRekBlock" style="margin-bottom:1.25rem;">
                        <div style="font-size:0.75rem;font-weight:700;color:#334155;margin-bottom:0.5rem;">Rekening Tujuan Pencairan</div>
                        <div class="rekening-saved" style="background:#f0fdf4;border:1.5px solid #86efac;">
                            <div class="rekening-info">
                                <div class="rekening-icon" style="background:#16a34a;">
                                    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="16" cy="12" r="2"/><path d="M6 12h.01"/></svg>
                                </div>
                                <div>
                                    <div style="display:flex;align-items:center;gap:0.4rem;">
                                        <span class="rekening-bank">{{ $savedBank }}</span>
                                        <span style="background:#dcfce7;color:#15803d;font-size:0.6rem;font-weight:800;padding:0.1rem 0.4rem;border-radius:4px;">REKENING TERDAFTAR</span>
                                    </div>
                                    <div class="rekening-num" style="color:#0f172a;font-weight:700;font-size:0.85rem;">{{ $savedNum }}</div>
                                    <div class="rekening-name">Atas Nama: <strong>{{ $savedName ?: $seller->name }}</strong></div>
                                </div>
                            </div>
                            <button type="button" class="btn-ganti" onclick="gantiRekening()" id="btnGantiRek">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:-2px;margin-right:3px"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Gunakan Rekening Lain
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="alert-notice" style="background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8;margin-bottom:1.25rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <div>
                            Kamu belum mendaftarkan rekening. Silakan isi rekening tujuan di bawah ini. Rekening akan <strong>otomatis tersimpan</strong> agar penarikan berikutnya tidak perlu input ulang.
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('creator.payout.request') }}" method="POST" id="withdrawForm">
                        @csrf

                        {{-- Bank Fields: Tampil jika belum ada rekening tersimpan atau klik gunakan rekening lain --}}
                        <div id="bankFieldsBlock" style="{{ $hasSaved ? 'display:none;' : '' }}">
                            <div style="background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:14px;padding:1.1rem;margin-bottom:1.25rem;">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
                                    <span style="font-size:0.78rem;font-weight:700;color:#0f172a;">Input Rekening Penarikan</span>
                                    @if($hasSaved)
                                    <button type="button" onclick="batalGantiRekening()" style="border:none;background:transparent;color:#dc2626;font-size:0.72rem;font-weight:700;cursor:pointer;">Batal (Gunakan Rekening Terdaftar)</button>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Bank / E-Wallet Tujuan <span style="color:#ef4444">*</span></label>
                                    <div class="bank-select-wrap" id="bankSelectWrap">
                                        <div class="bank-select-trigger" id="bankTrigger" onclick="toggleBankDropdown()">
                                            <div class="bank-select-display" id="bankDisplay">
                                                @if($savedBank)
                                                    <span>{{ $savedBank }}</span>
                                                @else
                                                    <span style="color:#94a3b8">Pilih Bank atau E-Wallet</span>
                                                @endif
                                            </div>
                                            <svg class="chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                        </div>
                                        <div class="bank-dropdown" id="bankDropdown">
                                            <div class="bank-search-wrap">
                                                <input type="text" class="bank-search" id="bankSearch" placeholder="Cari nama bank atau e-wallet..." oninput="filterBanks()" autocomplete="off">
                                            </div>
                                            <div class="bank-list-scroll" id="bankList">
                                                @foreach($banks as $groupName => $groupBanks)
                                                <div class="bank-group-label bank-group" data-group="{{ $groupName }}">{{ $groupName }}</div>
                                                @foreach($groupBanks as $bankItem)
                                                <div class="bank-option {{ $savedBank === $bankItem ? 'selected' : '' }}" 
                                                     data-value="{{ $bankItem }}" 
                                                     data-group="{{ $groupName }}"
                                                     onclick="selectBank('{{ $bankItem }}', '{{ $groupName }}')">
                                                    {{ $bankItem }}
                                                    <span class="bank-option-badge">{{ $groupName === 'E-Wallet' ? 'EWALLET' : ($groupName === 'Bank Digital' ? 'DIGITAL' : ($groupName === 'Bank Daerah' ? 'DAERAH' : 'BANK')) }}</span>
                                                </div>
                                                @endforeach
                                                @endforeach
                                                <div class="bank-no-result" id="bankNoResult" style="display:none">Tidak ditemukan</div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="bank_name" id="bankHidden" value="{{ $hasSaved ? '' : $savedBank }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nomor Rekening / Akun <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="bank_account_number" id="bankNum" class="form-input" value="{{ $hasSaved ? '' : $savedNum }}" placeholder="Contoh: 1234567890">
                                </div>
                                <div class="form-group" style="margin-bottom:0">
                                    <label class="form-label">Nama Pemilik Rekening <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="bank_account_name" id="bankAccName" class="form-input" value="{{ $hasSaved ? '' : ($savedName ?: $seller->name) }}" placeholder="Nama sesuai buku tabungan">
                                </div>
                            </div>
                        </div>

                        {{-- Quick Amount --}}
                        <div class="form-group">
                            <label class="form-label">Jumlah Penarikan <span style="color:#ef4444">*</span></label>
                            <div class="quick-amounts">
                                <button type="button" class="btn-quick" onclick="setAmount(50000)">Rp 50rb</button>
                                <button type="button" class="btn-quick" onclick="setAmount(100000)">Rp 100rb</button>
                                <button type="button" class="btn-quick" onclick="setAmount(500000)">Rp 500rb</button>
                                <button type="button" class="btn-quick" onclick="setAmount({{ $availableBalance }})">Maks</button>
                            </div>
                            <input type="number" name="amount" id="withdrawAmount" class="form-input" 
                                min="50000" max="{{ $availableBalance }}" 
                                placeholder="Min. Rp 50.000" 
                                oninput="updateBreakdown()" required>
                        </div>

                        {{-- Live Breakdown --}}
                        <div class="breakdown-box" id="withdrawBreakdown" style="display:none">
                            <div class="breakdown-row">
                                <span class="breakdown-key">Jumlah Penarikan</span>
                                <span class="breakdown-val" id="bdGross">Rp 0</span>
                            </div>
                            <div class="breakdown-row">
                                <span class="breakdown-key">Biaya Pencairan</span>
                                <span class="breakdown-val" style="color:#1eb349;font-weight:700;">Gratis (Rp 0)</span>
                            </div>
                            <div class="breakdown-row">
                                <span class="breakdown-key" style="font-weight:800;color:#0f172a">Dana Diterima (100%)</span>
                                <span class="breakdown-val green" id="bdNet">Rp 0</span>
                            </div>
                            <div class="breakdown-row">
                                <span class="breakdown-key" style="color:#94a3b8">Estimasi Waktu</span>
                                <span class="breakdown-val muted">1 - 3 Hari Kerja</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit-payout" id="btnSubmit" onclick="return confirm('Konfirmasi pengajuan penarikan dana?')">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                            Ajukan Penarikan Dana
                        </button>
                    </form>

                    @else
                    <div style="text-align:center; padding:2.5rem 1rem; color:#94A3B8;">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem;opacity:0.35"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        <h4 style="font-size:0.95rem;font-weight:700;color:#374151;margin-bottom:0.4rem;">Saldo Belum Cukup</h4>
                        <p style="font-size:0.8rem;margin:0;">Minimum penarikan Rp 50.000. Saldo siap ditarik kamu saat ini Rp {{ number_format($availableBalance, 0, ',', '.') }}.</p>
                        @if($holdBalance > 0)
                        <div style="margin-top:0.875rem;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:0.75rem 1rem;font-size:0.78rem;color:#92400e;display:inline-block;">
                            Rp {{ number_format($holdBalance, 0, ',', '.') }} sedang dalam masa tunggu 3x24 jam dan akan segera tersedia.
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB 2: REKENING --}}
        <div id="tab-bank" class="payout-tab-pane" style="display:none">
            <div class="prof-card">
                <div class="prof-card-head">
                    <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Pengaturan Rekening
                </div>
                <div class="form-body">
                    @if($hasSaved)
                    <div class="alert-notice" style="background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8;margin-bottom:1.25rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span>Rekening tersimpan dan akan otomatis digunakan sebagai tujuan penarikan dana.</span>
                    </div>
                    @endif

                    <form action="{{ route('creator.payout.bank.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Bank / E-Wallet <span style="color:#ef4444">*</span></label>
                            <div class="bank-select-wrap" id="bankSelectWrap2">
                                <div class="bank-select-trigger" id="bankTrigger2" onclick="toggleBankDropdown2()">
                                    <div class="bank-select-display" id="bankDisplay2">
                                        @if($savedBank)
                                            <span>{{ $savedBank }}</span>
                                        @else
                                            <span style="color:#94a3b8">Pilih Bank atau E-Wallet</span>
                                        @endif
                                    </div>
                                    <svg class="chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                                <div class="bank-dropdown" id="bankDropdown2">
                                    <div class="bank-search-wrap">
                                        <input type="text" class="bank-search" id="bankSearch2" placeholder="Cari nama bank atau e-wallet..." oninput="filterBanks2()" autocomplete="off">
                                    </div>
                                    <div class="bank-list-scroll" id="bankList2">
                                        @foreach($banks as $groupName => $groupBanks)
                                        <div class="bank-group-label bank-group2" data-group="{{ $groupName }}">{{ $groupName }}</div>
                                        @foreach($groupBanks as $bankItem)
                                        <div class="bank-option2 bank-option {{ $savedBank === $bankItem ? 'selected' : '' }}" 
                                             data-value="{{ $bankItem }}" 
                                             data-group="{{ $groupName }}"
                                             onclick="selectBank2('{{ $bankItem }}')">
                                            {{ $bankItem }}
                                            <span class="bank-option-badge">{{ $groupName === 'E-Wallet' ? 'EWALLET' : ($groupName === 'Bank Digital' ? 'DIGITAL' : ($groupName === 'Bank Daerah' ? 'DAERAH' : 'BANK')) }}</span>
                                        </div>
                                        @endforeach
                                        @endforeach
                                        <div class="bank-no-result" id="bankNoResult2" style="display:none">Tidak ditemukan</div>
                                    </div>
                                </div>
                                <input type="hidden" name="bank_name" id="bankHidden2" value="{{ $savedBank }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Rekening / Akun <span style="color:#ef4444">*</span></label>
                            <input type="text" name="bank_account_number" class="form-input" value="{{ $savedNum }}" placeholder="Contoh: 1234567890" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Pemilik Rekening <span style="color:#ef4444">*</span></label>
                            <input type="text" name="bank_account_name" class="form-input" value="{{ $savedName ?: $seller->name }}" placeholder="Nama sesuai buku tabungan" required>
                            <div class="form-hint">Isi nama sesuai yang tertera di buku tabungan atau aplikasi bank.</div>
                        </div>
                        <button type="submit" class="btn-submit-payout">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Rekening
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- TAB 3: RIWAYAT --}}
        <div id="tab-history" class="payout-tab-pane" style="display:none">
            <div class="prof-card">
                <div class="prof-card-head">
                    <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Riwayat Pencairan
                </div>
                <div class="form-body" style="padding:0">
                    @if($requests->count() > 0)
                    <div style="overflow-x:auto;">
                        <table class="hist-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Bank</th>
                                    <th>Jumlah</th>
                                    <th>Bersih</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $req)
                                <tr>
                                    <td style="white-space:nowrap;font-size:0.78rem;color:#64748b">{{ $req->created_at->format('d M Y') }}</td>
                                    <td style="font-size:0.8rem;font-weight:600;">{{ $req->bank_name }}<br><span style="font-size:0.72rem;color:#94a3b8">{{ $req->bank_account_number }}</span></td>
                                    <td style="font-weight:700;white-space:nowrap">Rp {{ number_format($req->amount, 0, ',', '.') }}</td>
                                    <td style="font-weight:800;color:#1eb349;white-space:nowrap">Rp {{ number_format($req->net_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $styles = [
                                                'pending'   => 'background:#fef9c3;color:#854d0e',
                                                'approved'  => 'background:#dcfce7;color:#166534',
                                                'processed' => 'background:#d1fae5;color:#065f46',
                                                'rejected'  => 'background:#fee2e2;color:#991b1b',
                                            ];
                                            $labels = [
                                                'pending'   => 'Menunggu',
                                                'approved'  => 'Disetujui',
                                                'processed' => 'Selesai',
                                                'rejected'  => 'Ditolak',
                                            ];
                                        @endphp
                                        <span class="hist-badge" style="{{ $styles[$req->status] ?? '' }}">{{ $labels[$req->status] ?? $req->status }}</span>
                                        @if($req->admin_notes && $req->status === 'rejected')
                                        <div style="font-size:0.67rem;color:#94a3b8;margin-top:0.25rem;max-width:130px;line-height:1.3;">{{ $req->admin_notes }}</div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:0.875rem 1.25rem;font-size:0.78rem;color:#94a3b8;">{{ $requests->links() }}</div>
                    @else
                    <div style="text-align:center;padding:2.5rem 1rem;color:#94A3B8;">
                        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:0.3;margin-bottom:0.75rem"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <p style="font-size:0.83rem;margin:0;font-weight:600;">Belum ada riwayat pencairan</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<style>
@media (max-width:1024px) {
    .mobile-saldo-bar { display:block !important; }
    .sidebar-balance-hide { display:none !important; }
}
</style>

<script>
// ── Tab Switcher ──
function switchPayoutTab(tabId, btn) {
    document.querySelectorAll('.payout-tab-pane').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById(tabId).style.display = 'block';
    btn.classList.add('active');
}

// ── Breakdown Calculator ──
function updateBreakdown() {
    const val = parseFloat(document.getElementById('withdrawAmount').value) || 0;
    const box = document.getElementById('withdrawBreakdown');
    if (val >= 50000) {
        box.style.display = 'block';
        document.getElementById('bdGross').textContent = 'Rp ' + val.toLocaleString('id-ID');
        document.getElementById('bdNet').textContent   = 'Rp ' + val.toLocaleString('id-ID');
    } else {
        box.style.display = 'none';
    }
}

function setAmount(val) {
    const input = document.getElementById('withdrawAmount');
    if (input) { input.value = val; updateBreakdown(); }
}

// ── Ganti Rekening Toggle ──
function gantiRekening() {
    const saved = document.getElementById('savedRekBlock');
    const fields = document.getElementById('bankFieldsBlock');
    if (saved) saved.style.display = 'none';
    if (fields) fields.style.display = 'block';
}

function batalGantiRekening() {
    const saved = document.getElementById('savedRekBlock');
    const fields = document.getElementById('bankFieldsBlock');
    if (saved) saved.style.display = 'block';
    if (fields) fields.style.display = 'none';
    const hidden = document.getElementById('bankHidden');
    const num = document.getElementById('bankNum');
    if (hidden) hidden.value = '';
    if (num) num.value = '';
}

// ── Bank Dropdown (Form 1) ──
let selectedBank = '{{ $savedBank }}';

function toggleBankDropdown() {
    const trigger = document.getElementById('bankTrigger');
    const dropdown = document.getElementById('bankDropdown');
    const isOpen = dropdown.classList.contains('open');
    if (isOpen) {
        dropdown.classList.remove('open');
        trigger.classList.remove('open');
    } else {
        dropdown.classList.add('open');
        trigger.classList.add('open');
        document.getElementById('bankSearch').focus();
    }
}

function selectBank(val, group) {
    selectedBank = val;
    document.getElementById('bankHidden').value = val;
    document.getElementById('bankDisplay').innerHTML = '<span>' + val + '</span>';
    document.querySelectorAll('#bankList .bank-option').forEach(el => {
        el.classList.toggle('selected', el.dataset.value === val);
    });
    document.getElementById('bankDropdown').classList.remove('open');
    document.getElementById('bankTrigger').classList.remove('open');
}

function filterBanks() {
    const q = document.getElementById('bankSearch').value.toLowerCase();
    let anyVisible = false;
    document.querySelectorAll('#bankList .bank-option').forEach(el => {
        const match = el.dataset.value.toLowerCase().includes(q);
        el.style.display = match ? '' : 'none';
        if (match) anyVisible = true;
    });
    document.querySelectorAll('#bankList .bank-group').forEach(el => {
        const group = el.dataset.group;
        const anyInGroup = [...document.querySelectorAll(`#bankList .bank-option[data-group="${group}"]`)].some(o => o.style.display !== 'none');
        el.style.display = anyInGroup ? '' : 'none';
    });
    document.getElementById('bankNoResult').style.display = anyVisible ? 'none' : 'block';
}

// ── Bank Dropdown (Form 2) ──
function toggleBankDropdown2() {
    const trigger = document.getElementById('bankTrigger2');
    const dropdown = document.getElementById('bankDropdown2');
    const isOpen = dropdown.classList.contains('open');
    if (isOpen) {
        dropdown.classList.remove('open');
        trigger.classList.remove('open');
    } else {
        dropdown.classList.add('open');
        trigger.classList.add('open');
        document.getElementById('bankSearch2').focus();
    }
}

function selectBank2(val) {
    document.getElementById('bankHidden2').value = val;
    document.getElementById('bankDisplay2').innerHTML = '<span>' + val + '</span>';
    document.querySelectorAll('#bankList2 .bank-option2').forEach(el => {
        el.classList.toggle('selected', el.dataset.value === val);
    });
    document.getElementById('bankDropdown2').classList.remove('open');
    document.getElementById('bankTrigger2').classList.remove('open');
}

function filterBanks2() {
    const q = document.getElementById('bankSearch2').value.toLowerCase();
    let anyVisible = false;
    document.querySelectorAll('#bankList2 .bank-option2').forEach(el => {
        const match = el.dataset.value.toLowerCase().includes(q);
        el.style.display = match ? '' : 'none';
        if (match) anyVisible = true;
    });
    document.querySelectorAll('#bankList2 .bank-group2').forEach(el => {
        const group = el.dataset.group;
        const anyInGroup = [...document.querySelectorAll(`#bankList2 .bank-option2[data-group="${group}"]`)].some(o => o.style.display !== 'none');
        el.style.display = anyInGroup ? '' : 'none';
    });
    document.getElementById('bankNoResult2').style.display = anyVisible ? 'none' : 'block';
}

// ── Close dropdowns on outside click ──
document.addEventListener('click', function(e) {
    if (!e.target.closest('#bankSelectWrap')) {
        document.getElementById('bankDropdown')?.classList.remove('open');
        document.getElementById('bankTrigger')?.classList.remove('open');
    }
    if (!e.target.closest('#bankSelectWrap2')) {
        document.getElementById('bankDropdown2')?.classList.remove('open');
        document.getElementById('bankTrigger2')?.classList.remove('open');
    }
});
</script>
@endsection
