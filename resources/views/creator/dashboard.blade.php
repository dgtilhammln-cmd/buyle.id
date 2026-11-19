@extends('creator.layout')

@section('title', 'Creator Dashboard – buyle.id')

@php
    $storeName = $seller->name;
    $creatorProfile = $seller->creatorProfile;
    if ($creatorProfile && !empty($creatorProfile->store_name)) {
        $storeName = $creatorProfile->store_name;
    }

    $crAvatar = $seller->avatar ?? ($creatorProfile->avatar ?? null);
    $crAvatarUrl = null;
    if ($crAvatar) {
        $crAvatarUrl = \Illuminate\Support\Str::startsWith($crAvatar, ['http://', 'https://']) ? $crAvatar : asset('storage/' . $crAvatar);
    }

    // Pendapatan bulan ini
    $thisMonthSales = $recentSales->filter(function ($order) {
        return \Carbon\Carbon::parse($order->created_at)->isCurrentMonth();
    });
    $incomeThisMonth = $thisMonthSales->sum(fn($o) => $o->items->sum('subtotal'));
    if ($incomeThisMonth <= 0 && $gmv > 0) {
        $incomeThisMonth = $gmv;
    }

    $storeUrl = url('/' . ($seller->username ?? ''));
    $websiteUrl = url('/c/' . ($seller->username ?? ''));
    $bioUrl = $storeUrl;

    $tierInfo = $creatorProfile ? $creatorProfile->getTierInfo($gmv ?? null) : [
        'name' => 'PERINTIS',
        'badge' => 'Perintis',
        'level' => 1,
        'subtitle' => 'Matte Silver / Brushed Steel'
    ];
    $tierBadge = $tierInfo['badge'] ?? $tierInfo['name'] ?? 'Perintis';
@endphp

@section('styles')
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --brand-green: #1eb349;
            --brand-lime: #a5cf37;
            --brand-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            --dark-slate: #0f172a;
            --dark-pill: #181d27;
            --border-color: #e2e8f0;
            --text-muted: #64748b;
        }

        /* ── Sembunyikan Desktop Topbar Header Row (Overview + Subtitle) di Dashboard ── */
        .cr-topbar-header-row {
            display: none !important;
        }

        .nina-dash-root {
            width: 100%;
            max-width: 1140px;
            margin: 0 auto;
            padding-bottom: 2rem;
        }

        /* Responsive Grid: Desktop 2-Col (Left: Balance + Middle Row, Right: Recent Activity), Mobile 1-Col */
        .nina-responsive-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.15rem;
        }

        @media (min-width: 992px) {
            .nina-responsive-layout {
                grid-template-columns: 1.15fr 0.85fr;
                gap: 1.5rem;
                align-items: start;
            }
        }

        .nina-col-left,
        .nina-col-right {
            display: flex;
            flex-direction: column;
            gap: 1.15rem;
        }

        /* ── CARD 1: MAIN BALANCE CARD (GRADASI PUTIH HIJAU, MINIMALIST) ── */
        .nina-balance-card {
            background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);
            border-radius: 30px;
            padding: 1.5rem 1.4rem 1.25rem;
            box-shadow: 0 4px 20px rgba(30, 179, 73, 0.05), 0 1px 3px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(187, 247, 208, 0.7);
            position: relative;
            overflow: visible;
        }

        /* Baris Sapaan & Ikon Aksi Minimalist */
        .nina-card-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.1rem;
            position: relative;
        }

        .nina-greeting-wrap {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            position: relative;
        }

        .nina-avatar-container {
            position: relative;
            flex-shrink: 0;
        }

        .nina-avatar-trigger {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background: #0f172a;
            padding: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            outline: none;
        }

        .nina-avatar-trigger:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.3);
        }

        .nina-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .nina-avatar-initial {
            color: #ffffff;
            font-size: 0.92rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Dropdown Mirip 100% Image 2 ── */
        .nina-dropdown-card {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            width: 235px;
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 18px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12), 0 2px 10px rgba(0, 0, 0, 0.04);
            z-index: 9999;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-8px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            font-family: inherit;
        }

        .ndc-header {
            padding: 0.85rem 1.1rem 0.65rem;
            border-bottom: 1px solid #F1F5F9;
        }

        .ndc-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: #0F172A;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ndc-email {
            font-size: 0.76rem;
            color: #64748B;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ndc-body {
            padding: 0.4rem 0.35rem;
        }

        .ndc-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.85rem;
            color: #334155;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 10px;
            transition: background 0.15s;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            box-sizing: border-box;
            font-family: inherit;
        }

        .ndc-item:hover {
            background: #F8FAFC;
            color: #0f172a;
        }

        .ndc-divider {
            height: 1px;
            background: #F1F5F9;
            margin: 0.3rem 0.5rem;
        }

        .ndc-logout {
            color: #ef4444 !important;
            font-weight: 700 !important;
        }

        .ndc-logout:hover {
            background: #fef2f2 !important;
        }

        .nina-greeting-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.3;
            letter-spacing: -0.01em;
        }

        .nina-greeting-title span {
            font-weight: 400;
            color: #64748b;
        }

        .nina-greeting-sub {
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 400;
            margin-top: 1px;
        }

        .nina-top-buttons {
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .nina-circle-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffffff;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 1.05rem;
        }

        .nina-circle-btn:hover {
            color: #1eb349;
            border-color: #bbf7d0;
            transform: scale(1.05);
        }

        /* Badge Tier Pill (Real Synchronized Tier) */
        .nina-tier-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ffffff;
            padding: 3px 12px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            color: #1e293b;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            margin: 0 auto 0.5rem;
            letter-spacing: 0.02em;
        }

        .nina-tier-pill i {
            color: #1eb349;
            font-size: 0.85rem;
        }

        /* Badge Currency Pill */
        .nina-currency-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ffffff;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 500;
            color: #334155;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            margin: 0 auto 0.4rem;
        }

        .nina-balance-center {
            text-align: center;
            padding: 0.2rem 0 0.8rem;
        }

        .nina-balance-amount {
            font-size: clamp(2rem, 4.5vw, 2.5rem);
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.025em;
            line-height: 1.15;
            margin-bottom: 0.5rem;
        }

        .nina-growth-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #22c55e;
            color: #ffffff;
            padding: 3px 11px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        /* Action Pill Bar (Hitam Sleek Sesuai Gambar Referensi, TANPA BARCODE) */
        .nina-action-bar {
            background: #181d27;
            border-radius: 999px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            gap: 6px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
        }

        .nina-action-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.07);
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 0.84rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
        }

        .nina-action-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        /* ── MIDDLE ROW: SHORTCUT (2x2) + YOUR INCOME SIDE-BY-SIDE (PERSIS REFERENSI) ── */
        .nina-middle-row {
            display: grid;
            grid-template-columns: 1fr 1.18fr;
            gap: 0.85rem;
            align-items: stretch;
        }

        @media (max-width: 480px) {
            .nina-middle-row {
                grid-template-columns: 0.95fr 1.05fr;
                gap: 0.65rem;
            }
        }

        .nina-shortcut-box {
            display: flex;
            flex-direction: column;
        }

        .nina-row-title {
            font-size: 0.88rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.55rem;
        }

        .nina-mini-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            flex: 1;
        }

        .nina-mini-card {
            background: #ffffff;
            border-radius: 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            padding: 0.75rem 0.35rem;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
            gap: 0.35rem;
        }

        .nina-mini-card:hover {
            transform: translateY(-2px);
            border-color: #1eb349;
            box-shadow: 0 6px 16px rgba(30, 179, 73, 0.1);
        }

        .nina-mini-card i {
            font-size: 1.25rem;
            color: #0f172a;
            transition: color 0.2s;
        }

        .nina-mini-card:hover i {
            color: #1eb349;
        }

        .nina-mini-card span {
            font-size: 0.72rem;
            font-weight: 500;
            color: #475569;
            text-align: center;
            line-height: 1.2;
        }

        /* Your Income Card */
        .nina-income-box {
            background: #ffffff;
            border-radius: 22px;
            padding: 0.9rem 1rem;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .nina-income-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nina-income-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: #0f172a;
        }

        .nina-income-period {
            font-size: 0.7rem;
            font-weight: 500;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 3px;
            background: #f8fafc;
            padding: 3px 8px;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.15s ease;
            outline: none;
        }

        .nina-income-period:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        /* ── Traffic Period Picker Dropdown ── */
        .traffic-period-picker {
            position: relative;
        }

        .traffic-period-menu {
            position: absolute;
            top: calc(100% + 5px);
            right: 0;
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            padding: 4px;
            z-index: 100;
            min-width: 105px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .tpm-item {
            background: transparent;
            border: none;
            padding: 5px 9px;
            font-size: 0.72rem;
            font-weight: 600;
            color: #475569;
            text-align: left;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
            width: 100%;
            display: block;
            font-family: inherit;
        }

        .tpm-item:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .tpm-item.active {
            background: #f0fdf4;
            color: #1eb349;
            font-weight: 700;
        }

        .nina-income-wave {
            width: 100%;
            height: 48px;
            margin: 0.35rem 0;
        }

        .nina-income-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .nina-income-amount {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .nina-income-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: #22c55e;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 999px;
        }

        /* ── RECENT ACTIVITY ── */
        .nina-section-box {
            background: #ffffff;
            border-radius: 24px;
            padding: 1.25rem 1.25rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .nina-section-title {
            font-size: 0.92rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nina-section-title a {
            font-size: 0.78rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 3px;
            transition: color 0.15s;
        }

        .nina-section-title a:hover {
            color: #1eb349;
        }

        .nina-activity-list {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .nina-activity-item {
            background: #f8fafc;
            border-radius: 16px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #f1f5f9;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
        }

        .nina-activity-item:hover {
            background: #ffffff;
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .nina-act-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nina-act-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            color: #0f172a;
            flex-shrink: 0;
        }

        .nina-act-buyer {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.25;
        }

        .nina-act-sub {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 400;
            margin-top: 1px;
        }

        .nina-act-right {
            text-align: right;
            flex-shrink: 0;
        }

        .nina-act-amount {
            font-size: 0.88rem;
            font-weight: 600;
            color: #0f172a;
            letter-spacing: -0.01em;
        }

        .nina-act-status {
            font-size: 0.65rem;
            font-weight: 500;
            color: #15803d;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 999px;
            padding: 1px 7px;
            display: inline-block;
            margin-top: 2px;
        }
    </style>
    {{-- Interactive Share Modal (Compact 2 URL Cards: Digital Store & Website) --}}
    <div id="shareStoreModal"
        style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:1.25rem;"
        onclick="if(event.target===this) closeShareStoreModal()">
        <div
            style="background:#ffffff; border-radius:26px; max-width:410px; width:100%; padding:1.75rem 1.5rem 1.5rem; text-align:center; box-shadow:0 24px 60px rgba(0,0,0,0.22); position:relative; animation:modalPop 0.25s cubic-bezier(0.34,1.56,0.64,1);">
            
            {{-- Tombol X di Pojok Kanan Atas --}}
            <button type="button" onclick="closeShareStoreModal()"
                style="position:absolute; top:1.1rem; right:1.1rem; width:34px; height:34px; border-radius:50%; background:#f8fafc; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; color:#64748b; font-size:1.15rem; cursor:pointer; transition:all 0.15s ease;"
                onmouseover="this.style.background='#fee2e2'; this.style.color='#ef4444'; this.style.borderColor='#fca5a5';"
                onmouseout="this.style.background='#f8fafc'; this.style.color='#64748b'; this.style.borderColor='#e2e8f0';"
                title="Tutup Modal">
                <i class="ph-bold ph-x"></i>
            </button>

            <div
                style="width:48px; height:48px; border-radius:50%; background:#ecfdf5; color:#1eb349; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; font-size:1.4rem;">
                <i class="ph-bold ph-share-network"></i>
            </div>
            <h3 style="font-size:1.15rem; font-weight:700; color:#0f172a; margin:0 0 0.35rem;">Bagikan Digital Store</h3>
            <p style="font-size:0.78rem; color:#64748b; margin:0 0 1.35rem; line-height:1.4;">
                Salin tautan atau buka langsung Digital Store dan Website Anda.
            </p>

            {{-- CARD 1: DIGITAL STORE (https://buyle.id/username) --}}
            <div style="text-align:left; margin-bottom:1rem;">
                <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.35rem;">
                    Digital Store
                </label>
                <div style="display:flex; align-items:center; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:0.4rem 0.5rem 0.4rem 0.85rem; gap:0.45rem;">
                    <input type="text" id="shareStoreUrlInput" value="{{ $storeUrl }}" readonly
                        style="flex:1; border:none; background:transparent; font-family:'Montserrat',sans-serif; font-size:0.8rem; color:#334155; font-weight:600; outline:none; text-overflow:ellipsis; min-width:0;">
                    
                    {{-- Icon Salin --}}
                    <button type="button" onclick="copyStoreLink('shareStoreUrlInput', this)" title="Salin Link Digital Store"
                        style="width:34px; height:34px; border-radius:10px; background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; transition:all 0.15s ease;">
                        <i class="ph-bold ph-copy" style="font-size:1.05rem;"></i>
                    </button>
                    {{-- Icon Buka --}}
                    <a href="{{ $storeUrl }}" target="_blank" title="Buka Digital Store"
                        style="width:34px; height:34px; border-radius:10px; background:#0f172a; border:1px solid #0f172a; color:#ffffff; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; flex-shrink:0; transition:all 0.15s ease;">
                        <i class="ph-bold ph-arrow-square-out" style="font-size:1.05rem;"></i>
                    </a>
                </div>
            </div>

            {{-- CARD 2: WEBSITE (https://buyle.id/c/username) --}}
            <div style="text-align:left; margin-bottom:0.5rem;">
                <label style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.35rem;">
                    Website
                </label>
                <div style="display:flex; align-items:center; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:0.4rem 0.5rem 0.4rem 0.85rem; gap:0.45rem;">
                    <input type="text" id="shareWebsiteUrlInput" value="{{ $websiteUrl }}" readonly
                        style="flex:1; border:none; background:transparent; font-family:'Montserrat',sans-serif; font-size:0.8rem; color:#334155; font-weight:600; outline:none; text-overflow:ellipsis; min-width:0;">
                    
                    {{-- Icon Salin --}}
                    <button type="button" onclick="copyStoreLink('shareWebsiteUrlInput', this)" title="Salin Link Website"
                        style="width:34px; height:34px; border-radius:10px; background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; transition:all 0.15s ease;">
                        <i class="ph-bold ph-copy" style="font-size:1.05rem;"></i>
                    </button>
                    {{-- Icon Buka --}}
                    <a href="{{ $websiteUrl }}" target="_blank" title="Buka Website"
                        style="width:34px; height:34px; border-radius:10px; background:#0f172a; border:1px solid #0f172a; color:#ffffff; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; flex-shrink:0; transition:all 0.15s ease;">
                        <i class="ph-bold ph-arrow-square-out" style="font-size:1.05rem;"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
        function openShareStoreModal() {
            const m = document.getElementById('shareStoreModal');
            if (m) m.style.display = 'flex';
        }
        function closeShareStoreModal() {
            const m = document.getElementById('shareStoreModal');
            if (m) m.style.display = 'none';
        }
        function copyStoreLink(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            navigator.clipboard.writeText(input.value).then(() => {
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.className = 'ph-bold ph-check';
                    btn.style.background = '#16a34a';
                    btn.style.color = '#ffffff';
                    setTimeout(() => {
                        icon.className = 'ph-bold ph-copy';
                        btn.style.background = '#f0fdf4';
                        btn.style.color = '#16a34a';
                    }, 1800);
                }
            }).catch(() => {
                input.select();
                document.execCommand('copy');
            });
        }
    </script>
@endsection

@section('content')
    <div class="nina-dash-root">

        <div class="nina-responsive-layout">

            {{-- ── KOLOM KIRI: MAIN BALANCE CARD + SHORTCUT & INCOME ROW ── --}}
            <div class="nina-col-left">

                {{-- 1. MAIN WALLET & BALANCE CARD (Gradasi Putih-Hijau, Minimalist Sesuai Referensi) --}}
                <div class="nina-balance-card">

                    {{-- Topbar: Sapaan Creator & Ikon Aksi Minimalist --}}
                    <div class="nina-card-topbar">
                        <div class="nina-greeting-wrap">
                            {{-- Icon Avatar Bulat Mirip Image 2 --}}
                            <div class="nina-avatar-container">
                                <button type="button" class="nina-avatar-trigger" id="ninaAvatarBtn" onclick="toggleNinaDropdown(event)" title="Menu Profil">
                                    @if($crAvatarUrl)
                                        <img src="{{ $crAvatarUrl }}" alt="{{ $storeName }}" class="nina-avatar-img">
                                    @else
                                        <div class="nina-avatar-initial">
                                            {{ strtoupper(substr($storeName, 0, 1)) }}
                                        </div>
                                    @endif
                                </button>

                                {{-- Dropdown Mirip 100% Image 2 (Profil, Pesanan Saya, Logout) --}}
                                <div id="ninaDropdownMenu" class="nina-dropdown-card" style="display: none;">
                                    <div class="ndc-header">
                                        <div class="ndc-name">{{ $seller->name }}</div>
                                        <div class="ndc-email">{{ $seller->email }}</div>
                                    </div>
                                    <div class="ndc-body">
                                        {{-- 1. Profil -> /profile --}}
                                        <a href="{{ url('/profile') }}" class="ndc-item">
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="8" r="4" />
                                                <path d="M20 21a8 8 0 1 0-16 0" />
                                            </svg>
                                            <span>Profil</span>
                                        </a>

                                        {{-- 2. Pesanan Saya -> /akun/pesanan --}}
                                        <a href="{{ route('account.orders') }}" class="ndc-item">
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                                            </svg>
                                            <span>Pesanan Saya</span>
                                        </a>

                                        <div class="ndc-divider"></div>

                                        {{-- 3. Beranda Buyle -> / --}}
                                        <a href="{{ url('/') }}" class="ndc-item">
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                                <polyline points="9 22 9 12 15 12 15 22"/>
                                            </svg>
                                            <span>Beranda Buyle</span>
                                        </a>

                                        {{-- 4. Beranda BuyleTIX -> /creator/event-attendance --}}
                                        <a href="{{ route('creator.ticket.scanner') }}" class="ndc-item">
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/>
                                                <path d="M13 5v14"/>
                                            </svg>
                                            <span>Beranda BuyleTIX</span>
                                        </a>

                                        <div class="ndc-divider"></div>

                                        {{-- 5. Logout --}}
                                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="ndc-item ndc-logout">
                                                <svg width="17" height="17" fill="none" stroke="#ef4444" stroke-width="2.2" viewBox="0 0 24 24">
                                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                    <polyline points="16 17 21 12 16 7"></polyline>
                                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                                </svg>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="nina-greeting-title">
                                Halo, {{ $storeName }}!
                            </div>
                        </div>
                    </div>

                    {{-- Saldo Utama (Minimalist Clean Typography) --}}
                    <div class="nina-balance-center">
                        <div class="nina-tier-pill">
                            <i class="ph-bold ph-shield-check"></i>
                            <span>Tier: {{ $tierBadge }}</span>
                        </div>
                        <div class="nina-balance-amount">
                            Rp {{ number_format($availableBalance, 0, ',', '.') }}
                        </div>

                        <div class="nina-growth-pill">
                            <i class="ph-bold ph-trend-up"></i>
                            <span>Saldo Siap Ditarik</span>
                        </div>
                    </div>

                    {{-- Dark Action Bar: 2 Tombol Saja (Tarik Saldo & Bagikan - TANPA BARCODE) --}}
                    <div class="nina-action-bar">
                        <a href="{{ route('creator.payout.settings') }}" class="nina-action-btn">
                            <span>Tarik Saldo</span>
                            <i class="ph-bold ph-arrow-up-right" style="font-size: 0.85rem;"></i>
                        </a>

                        <button type="button" class="nina-action-btn" onclick="openShareStoreModal()">
                            <i class="ph-bold ph-share-network" style="font-size: 0.85rem;"></i>
                            <span>Bagikan</span>
                        </button>
                    </div>

                </div>

                {{-- 2. MIDDLE ROW: SHORTCUT (2x2) + YOUR INCOME SIDE-BY-SIDE (100% PERSIS REFERENSI) --}}
                <div class="nina-middle-row">

                    {{-- Kiri: Shortcut Grid 2x2 (Persis Send Again) --}}
                    <div class="nina-shortcut-box">
                        <div class="nina-row-title">Shortcut</div>
                        <div class="nina-mini-grid">
                            <a href="{{ route('creator.ticket.scanner') }}" class="nina-mini-card" title="Buyle TIX">
                                <i class="ph ph-ticket"></i>
                                <span>Buyle TIX</span>
                            </a>
                            <a href="{{ route('creator.pos.index') }}" class="nina-mini-card" title="Kasir POS">
                                <i class="ph ph-storefront"></i>
                                <span>POS</span>
                            </a>
                            <a href="{{ route('creator.bio.index') }}" class="nina-mini-card" title="Website Toko">
                                <i class="ph ph-globe"></i>
                                <span>Website</span>
                            </a>
                            <a href="{{ route('creator.sales.report') }}" class="nina-mini-card" title="Laporan Income">
                                <i class="ph ph-chart-line-up"></i>
                                <span>Income</span>
                            </a>
                        </div>
                    </div>

                    {{-- Kanan: Traffic (Card dengan Gelombang Hijau & Filter Periode) --}}
                    <div class="nina-income-box">
                        <div class="nina-income-header">
                            <span class="nina-income-title">Traffic</span>
                            <div class="traffic-period-picker">
                                <button type="button" class="nina-income-period" id="trafficPeriodBtn" onclick="toggleTrafficPeriod(event)">
                                    <span id="trafficPeriodLabel">Bulan Ini</span>
                                    <i class="ph ph-caret-down" style="font-size:0.7rem;"></i>
                                </button>
                                <div id="trafficPeriodMenu" class="traffic-period-menu" style="display: none;">
                                    <button type="button" class="tpm-item" onclick="setTrafficPeriod('week', 'Minggu Ini')">Minggu Ini</button>
                                    <button type="button" class="tpm-item active" onclick="setTrafficPeriod('month', 'Bulan Ini')">Bulan Ini</button>
                                    <button type="button" class="tpm-item" onclick="setTrafficPeriod('year', 'Tahun Ini')">Tahun Ini</button>
                                </div>
                            </div>
                        </div>

                        {{-- Smooth Delicate Wave Curve --}}
                        <svg class="nina-income-wave" viewBox="0 0 160 55" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="incomeWaveGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#22c55e" stop-opacity="0.25" />
                                    <stop offset="100%" stop-color="#22c55e" stop-opacity="0.0" />
                                </linearGradient>
                            </defs>
                            <path d="M 0,42 Q 30,48 60,30 T 115,16 T 160,8 L 160,55 L 0,55 Z" fill="url(#incomeWaveGrad)" />
                            <path d="M 0,42 Q 30,48 60,30 T 115,16 T 160,8" fill="none" stroke="#22c55e" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>

                        <div class="nina-income-bottom">
                            <div class="nina-income-amount" id="trafficCount">
                                {{ number_format($trafficData['month']['count'] ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="nina-income-badge" id="trafficBadge">
                                <i class="ph ph-trend-up"></i>
                                <span id="trafficGrowth">{{ $trafficData['month']['growth'] ?? '+100%' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- ── KOLOM KANAN: RECENT ACTIVITY (RIWAYAT ORDERAN MASUK) ── --}}
            <div class="nina-col-right">

                <div class="nina-section-box">
                    <div class="nina-section-title">
                        <span>Recent Activity</span>
                        <a href="{{ route('creator.sales.report') }}" title="Lihat Semua Laporan">
                            <span>Lihat Semua</span>
                            <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>

                    <div class="nina-activity-list">
                        @forelse($recentSales->take(6) as $order)
                            @php
                                $firstItem = $order->items->first();
                                $prodName = $firstItem?->product?->name ?? 'Produk Kreator';
                                $buyerName = $order->customer_name ?: ($order->user?->name ?: 'Pembeli');
                                $orderTime = \Carbon\Carbon::parse($order->created_at)->locale('id')->diffForHumans();
                                $orderTotal = $order->items->sum('subtotal') ?: $order->total_amount;
                            @endphp
                            <a href="{{ route('creator.sales.report') }}" class="nina-activity-item">
                                <div class="nina-act-left">
                                    <div class="nina-act-icon">
                                        <i class="ph-bold ph-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div class="nina-act-buyer">{{ $buyerName }}</div>
                                        <div class="nina-act-sub">{{ Str::limit($prodName, 24) }} · {{ $orderTime }}</div>
                                    </div>
                                </div>
                                <div class="nina-act-right">
                                    <div class="nina-act-amount">+Rp {{ number_format($orderTotal, 0, ',', '.') }}</div>
                                    <span class="nina-act-status">Berhasil</span>
                                </div>
                            </a>
                        @empty
                            <div
                                style="background:#f8fafc; border-radius:18px; padding:2rem 1.5rem; text-align:center; border:1px dashed #cbd5e1;">
                                <div
                                    style="width:44px; height:44px; border-radius:50%; background:#ffffff; color:#64748b; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; font-size:1.35rem; border:1px solid #e2e8f0;">
                                    <i class="ph ph-receipt"></i>
                                </div>
                                <div style="font-weight:600; color:#0f172a; font-size:0.88rem;">Belum Ada Orderan Masuk</div>
                                <div
                                    style="font-size:0.75rem; color:#64748b; margin-top:3px; max-width:280px; margin:4px auto 0;">
                                    Bagikan link toko atau produk Anda ke media sosial untuk mulai menerima pesanan.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script>
        // ── Traffic Stats Interactive Filter ──
        const trafficStats = {!! json_encode($trafficData ?? [
            'week'  => ['label' => 'Minggu Ini', 'count' => 0, 'growth' => '+100%'],
            'month' => ['label' => 'Bulan Ini',  'count' => 0, 'growth' => '+100%'],
            'year'  => ['label' => 'Tahun Ini',  'count' => 0, 'growth' => '+100%']
        ]) !!};

        function toggleTrafficPeriod(e) {
            e.stopPropagation();
            const menu = document.getElementById('trafficPeriodMenu');
            if (!menu) return;
            const isShown = menu.style.display === 'block';
            menu.style.display = isShown ? 'none' : 'block';
        }

        function setTrafficPeriod(key, label) {
            const item = trafficStats[key];
            if (item) {
                document.getElementById('trafficPeriodLabel').textContent = label;
                document.getElementById('trafficCount').textContent = new Intl.NumberFormat('id-ID').format(item.count);
                document.getElementById('trafficGrowth').textContent = item.growth;
                document.querySelectorAll('.tpm-item').forEach(el => {
                    el.classList.toggle('active', el.textContent.trim() === label);
                });
            }
            const menu = document.getElementById('trafficPeriodMenu');
            if (menu) menu.style.display = 'none';
        }

        // ── Nina Creator Profile Dropdown (100% Mirip Image 2) ──
        function toggleNinaDropdown(e) {
            e.stopPropagation();
            const dd = document.getElementById('ninaDropdownMenu');
            if (!dd) return;
            const isHidden = dd.style.display === 'none' || dd.style.display === '';
            if (isHidden) {
                dd.style.display = 'block';
                requestAnimationFrame(() => {
                    dd.style.opacity = '1';
                    dd.style.transform = 'translateY(0)';
                });
            } else {
                dd.style.opacity = '0';
                dd.style.transform = 'translateY(-8px)';
                setTimeout(() => { dd.style.display = 'none'; }, 180);
            }
        }

        // Global click listener to close dropdowns when clicking outside
        window.addEventListener('click', function(e) {
            const ninaDd = document.getElementById('ninaDropdownMenu');
            if (ninaDd && !e.target.closest('.nina-avatar-container')) {
                ninaDd.style.opacity = '0';
                ninaDd.style.transform = 'translateY(-8px)';
                setTimeout(() => { ninaDd.style.display = 'none'; }, 180);
            }

            const tpMenu = document.getElementById('trafficPeriodMenu');
            if (tpMenu && !e.target.closest('.traffic-period-picker')) {
                tpMenu.style.display = 'none';
            }
        });
    </script>
@endsection