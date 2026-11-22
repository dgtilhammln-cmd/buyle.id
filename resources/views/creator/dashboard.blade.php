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

        /* Tombol Icon Lonceng Notifikasi Pojok Kanan Atas */
        .nina-notif-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1e293b;
            position: relative;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
        }

        .nina-notif-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .nina-notif-btn:active {
            transform: scale(0.93);
        }

        .nina-notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 5px rgba(239, 68, 68, 0.4);
            line-height: 1;
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

        /* Buyle Pay Balance Row (Logo di Kiri, Nominal di Paling Kanan + Icon Mata) */
        .nina-balance-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.75rem 0.2rem 0.6rem;
        }

        .nina-buylepay-logo-wrap {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-shrink: 0;
        }

        .nina-buylepay-logo {
            height: 34px;
            width: auto;
            max-width: 145px;
            object-fit: contain;
            display: block;
        }

        .nina-balance-amount-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 7px;
        }

        .nina-balance-amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin-bottom: 0;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .nina-btn-toggle-balance {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 0;
            flex-shrink: 0;
        }

        .nina-btn-toggle-balance:hover {
            color: #0f172a;
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: scale(1.05);
        }

        /* Badge Kuota Bebas Biaya Admin */
        .nina-free-slot-badge {
            display: inline-flex;
            align-items: center;
            gap: 4.5px;
            padding: 3.5px 9px;
            background: linear-gradient(135deg, rgba(30, 179, 73, 0.12) 0%, rgba(165, 207, 55, 0.18) 100%);
            border: 1px solid rgba(30, 179, 73, 0.28);
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 700;
            color: #15803d;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
        }

        .nina-free-slot-badge:hover {
            background: linear-gradient(135deg, rgba(30, 179, 73, 0.22) 0%, rgba(165, 207, 55, 0.28) 100%);
            color: #166534;
            transform: translateY(-1px);
        }

        /* ── MILESTONE LOGIN 7 HARI (DAILY STREAK REWARD) ── */
        .nina-milestone-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 1.15rem 1.25rem;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
        }

        .nina-milestone-card:hover {
            box-shadow: 0 8px 24px -4px rgba(30, 179, 73, 0.08);
            border-color: #cbd5e1;
        }

        .nina-milestone-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.95rem;
            gap: 0.75rem;
        }

        .nina-milestone-title-wrap {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .nina-milestone-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.25);
            flex-shrink: 0;
        }

        .nina-milestone-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }

        .nina-milestone-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            color: #166534;
            white-space: nowrap;
        }

        /* 7-DAY STREAK TRACK (100% PERSIS REFERENSI) */
        .nina-streak-track {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 7px;
            margin: 0.85rem 0 0;
        }

        .nina-streak-day {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0.25rem 0.6rem;
            border-radius: 14px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            min-height: 84px;
            position: relative;
            cursor: pointer;
            user-select: none;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nina-streak-day:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 14px rgba(30, 179, 73, 0.12);
            border-color: #86efac;
        }

        .nina-streak-day:active {
            transform: scale(0.95);
        }

        .nina-streak-day-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: -0.01em;
            text-align: center;
        }

        .nina-streak-coin-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            margin: 3px 0;
            position: relative;
        }

        .nina-streak-reward-val {
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            text-align: center;
        }

        /* Status: Checked (Hari Lalu Selesai) */
        .nina-streak-day.is-checked {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .nina-streak-day.is-checked .nina-streak-day-label {
            color: #16a34a;
        }

        .nina-streak-day.is-checked .nina-streak-reward-val {
            color: #15803d;
        }

        .nina-streak-check-circle {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #22c55e;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            box-shadow: 0 2px 6px rgba(34, 197, 94, 0.35);
        }

        /* Status: Today (Hari Ini - Persis Referensi Ber-Top Cap Hijau) */
        .nina-streak-day.is-today {
            background: #ffffff;
            border: 2px solid #1eb349;
            box-shadow: 0 4px 16px rgba(30, 179, 73, 0.2);
            transform: translateY(-2px);
            padding-top: 0;
            overflow: hidden;
        }

        .nina-streak-today-cap {
            width: 100%;
            background: #1eb349;
            color: #ffffff;
            font-size: 0.6rem;
            font-weight: 800;
            text-align: center;
            padding: 3px 0 2px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            margin-bottom: 2px;
        }

        .nina-streak-day.is-today .nina-streak-reward-val {
            color: #15803d;
            font-weight: 800;
        }

        .nina-streak-day.is-today .nina-coin-stack {
            animation: ninaCoinBounce 2s infinite ease-in-out;
        }

        /* Status: Upcoming */
        .nina-streak-day.is-upcoming {
            background: #f8fafc;
            border-color: #f1f5f9;
        }

        /* Day 7 (Grand Prize Day) */
        .nina-streak-day.is-grand-prize {
            background: linear-gradient(180deg, #fef9c3 0%, #ffffff 100%);
            border-color: #fde047;
        }

        .nina-streak-day.is-grand-prize.is-today {
            border: 2px solid #eab308;
            box-shadow: 0 4px 16px rgba(234, 179, 8, 0.35);
        }

        .nina-streak-day.is-grand-prize.is-today .nina-streak-today-cap {
            background: linear-gradient(135deg, #1eb349 0%, #ca8a04 100%);
        }

        @keyframes ninaCoinBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        /* Action / Footer (Clean & Spacious Single Row) */
        .nina-streak-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding-top: 0.85rem;
            border-top: 1px solid #f1f5f9;
        }

        .nina-streak-prog-text {
            font-size: 0.74rem;
            color: #64748b;
            font-weight: 600;
            margin: 0;
            line-height: 1.3;
        }

        .nina-streak-prog-text strong {
            color: #15803d;
        }

        .nina-btn-claim-streak {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 15px;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-size: 0.76rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(30, 179, 73, 0.35);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            animation: ninaClaimPulse 2s infinite ease-in-out;
            font-family: 'Montserrat', sans-serif;
            white-space: nowrap;
        }

        .nina-btn-claim-streak:hover {
            transform: translateY(-1px) scale(1.02);
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.45);
        }

        @keyframes ninaClaimPulse {
            0%, 100% { box-shadow: 0 2px 8px rgba(30, 179, 73, 0.3); }
            50% { box-shadow: 0 4px 18px rgba(30, 179, 73, 0.6); }
        }

        @media (max-width: 480px) {
            .nina-milestone-card {
                padding: 1rem 1rem;
            }
            .nina-streak-track {
                gap: 4px;
                margin: 0.75rem 0 0;
            }
            .nina-streak-day {
                min-height: 76px;
                padding: 0.4rem 0.15rem 0.5rem;
                border-radius: 11px;
            }
            .nina-streak-day-label {
                font-size: 0.58rem;
            }
            .nina-streak-reward-val {
                font-size: 0.62rem;
            }
            .nina-streak-coin-wrap {
                width: 26px;
                height: 26px;
            }
            .nina-streak-today-cap {
                font-size: 0.52rem;
                padding: 2px 0;
            }
            .nina-streak-footer {
                padding-top: 0.75rem;
            }
            .nina-streak-prog-text {
                font-size: 0.68rem;
            }
        }

        /* ── INTERACTIVE BUYLE STREAK TOAST (THEMED & SMOOTH) ── */
        .buyle-streak-toast {
            position: fixed;
            top: max(calc(env(safe-area-inset-top, 44px) + 24px), 80px);
            left: 50%;
            transform: translateX(-50%) translateY(-25px);
            z-index: 999999;
            background: #ffffff;
            border: 1.5px solid #86efac;
            box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.18), 0 4px 14px rgba(30, 179, 73, 0.22);
            border-radius: 999px;
            padding: 9px 20px 9px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1), transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            max-width: calc(100vw - 32px);
            box-sizing: border-box;
        }

        .buyle-streak-toast.is-show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
            pointer-events: auto;
        }

        .buyle-toast-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.35);
        }

        .buyle-toast-body {
            display: flex;
            flex-direction: column;
            line-height: 1.25;
        }

        .buyle-toast-title {
            font-size: 0.76rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
            font-family: 'Montserrat', sans-serif;
        }

        .buyle-toast-desc {
            font-size: 0.7rem;
            font-weight: 600;
            color: #15803d;
            font-family: 'Montserrat', sans-serif;
        }

        /* Action Pill Bar (Hitam Sleek 3 Tombol: Tarik Saldo, Top Up, Transfer) */
        .nina-action-bar {
            background: #181d27;
            border-radius: 999px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.85rem;
            gap: 5px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
        }

        .nina-action-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            background: rgba(255, 255, 255, 0.07);
            color: #ffffff;
            padding: 9px 10px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            white-space: nowrap;
        }

        .nina-action-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        .nina-action-btn.nina-action-btn-icon {
            flex: 0 0 38px;
            width: 38px;
            height: 38px;
            padding: 0;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .nina-action-btn.nina-action-btn-icon:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .nina-action-btn.nina-action-btn-icon i {
            font-size: 1.05rem;
        }

        @media (max-width: 480px) {
            .nina-buylepay-logo {
                height: 30px;
                max-width: 120px;
            }
            .nina-balance-amount {
                font-size: 1.12rem;
            }
            .nina-action-btn {
                padding: 8px 6px;
                font-size: 0.72rem;
                gap: 4px;
            }
            .nina-action-btn i {
                font-size: 0.78rem !important;
            }
            .nina-action-btn.nina-action-btn-icon {
                flex: 0 0 34px;
                width: 34px;
                height: 34px;
            }
            .nina-action-btn.nina-action-btn-icon i {
                font-size: 0.95rem !important;
            }
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
            min-height: 68px;
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

        .nina-mini-card-tix {
            background: linear-gradient(135deg, #1db349 0%, #84ce00 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.28) !important;
            box-shadow: 0 4px 14px rgba(29, 179, 73, 0.22) !important;
            padding: 0 0.5rem !important;
        }

        .nina-mini-card-tix:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.5) !important;
            box-shadow: 0 8px 20px rgba(29, 179, 73, 0.35) !important;
        }

        .nina-tix-logo {
            height: 22px;
            width: auto;
            max-width: 86%;
            object-fit: contain;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.12));
            display: block;
            margin: auto;
        }

        .nina-mini-card-pos {
            background: #ffffff !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            padding: 0 0.5rem !important;
        }

        .nina-mini-card-pos:hover {
            transform: translateY(-2px);
            border-color: #1eb349 !important;
            box-shadow: 0 6px 16px rgba(30, 179, 73, 0.1) !important;
        }

        .nina-pos-logo {
            height: 22px;
            width: auto;
            max-width: 86%;
            object-fit: contain;
            display: block;
            margin: auto;
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

        /* ── Swipe-left Recent Activity ── */
        .nina-swipe-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
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
            transition: transform 0.3s cubic-bezier(0.25,0.46,0.45,0.94);
            will-change: transform;
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
            position: relative;
            z-index: 1;
        }
        .nina-activity-item:hover {
            background: #f8fafc;
        }
        .nina-swipe-action {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            border-radius: 0 16px 16px 0;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.25,0.46,0.45,0.94);
            z-index: 0;
        }
        .nina-swipe-action a {
            color: #fff;
            font-weight: 700;
            font-size: 0.8rem;
            text-decoration: none;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        /* ── iOS Safe Area Fix for Midtrans Snap (Notch & Dynamic Island) ── */
        @media (max-width: 768px) {
            #snap-midtrans,
            iframe[id*="snap-midtrans"],
            iframe[name^="popup_"],
            div[id*="snap-container"] {
                top: max(50px, env(safe-area-inset-top, 50px)) !important;
                height: calc(100% - max(50px, env(safe-area-inset-top, 50px)) - max(16px, env(safe-area-inset-bottom, 16px))) !important;
                max-height: calc(100dvh - max(50px, env(safe-area-inset-top, 50px)) - max(16px, env(safe-area-inset-bottom, 16px))) !important;
                box-sizing: border-box !important;
                border-radius: 18px 18px 0 0 !important;
            }

            .snap-safe-top-bar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: max(50px, env(safe-area-inset-top, 50px));
                background: #ffffff;
                z-index: 999998;
                display: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            body.snap-open .snap-safe-top-bar {
                display: block;
            }

            #pinRequiredModal,
            #transferModal,
            #topupModal {
                padding-top: max(1.25rem, calc(env(safe-area-inset-top, 0px) + 0.75rem)) !important;
                padding-bottom: max(1.25rem, calc(env(safe-area-inset-bottom, 0px) + 0.75rem)) !important;
            }
        }
    </style>
    {{-- Interactive Share Modal (Compact 2 URL Cards: Digital Store & Website) --}}
    <style>
        /* ── Share Modal Smooth Animation ── */
        #shareStoreModal {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1),
                backdrop-filter 0.28s ease;
        }

        #shareStoreModal.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        #shareStoreModal .share-modal-card {
            transform: translateY(28px) scale(0.95);
            opacity: 0;
            transition: transform 0.38s cubic-bezier(0.34, 1.56, 0.64, 1),
                opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }

        #shareStoreModal.is-open .share-modal-card {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        #shareStoreModal.is-closing .share-modal-card {
            transform: translateY(18px) scale(0.96);
            opacity: 0;
            transition: transform 0.22s cubic-bezier(0.4, 0, 1, 1),
                opacity 0.18s cubic-bezier(0.4, 0, 1, 1);
        }

        #shareStoreModal.is-closing {
            opacity: 0;
            transition: opacity 0.22s cubic-bezier(0.4, 0, 1, 1);
        }
    </style>
    <div id="shareStoreModal"
        style="display:flex; position:fixed; inset:0; background:rgba(15,23,42,0.65); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:1.25rem;"
        onclick="if(event.target===this) closeShareStoreModal()">
        <div class="share-modal-card"
            style="background:#ffffff; border-radius:26px; max-width:410px; width:100%; padding:1.75rem 1.5rem 1.5rem; text-align:center; box-shadow:0 24px 60px rgba(0,0,0,0.22); position:relative;">

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
                Salin tautan untuk menjangkau banyak order, atau buka langsung Digital Store dan Website Anda.
            </p>

            {{-- CARD 1: DIGITAL STORE (https://buyle.id/username) --}}
            <div style="text-align:left; margin-bottom:1rem;">
                <label
                    style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.35rem;">
                    Digital Store
                </label>
                <div
                    style="display:flex; align-items:center; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:0.4rem 0.5rem 0.4rem 0.85rem; gap:0.45rem;">
                    <input type="text" id="shareStoreUrlInput" value="{{ $storeUrl }}" readonly
                        style="flex:1; border:none; background:transparent; font-family:'Montserrat',sans-serif; font-size:0.8rem; color:#334155; font-weight:600; outline:none; text-overflow:ellipsis; min-width:0;">

                    {{-- Icon Salin --}}
                    <button type="button" onclick="copyStoreLink('shareStoreUrlInput', this)"
                        title="Salin Link Digital Store"
                        style="width:34px; height:34px; border-radius:10px; background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; transition:all 0.15s ease;">
                        <i class="ph-bold ph-copy" style="font-size:1.05rem;"></i>
                    </button>
                    {{-- Icon Buka --}}
                    <a href="{{ $storeUrl }}" target="_blank" title="Buka Digital Store"
                        style="width:34px; height:34px; border-radius:10px; background:#0f172a; border:1px solid #0f172a; color:#ffffff; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; flex-shrink:0; transition:all 0.15s ease;">
                        <i class="ph-bold ph-arrow-square-out" style="font-size:1.05rem;"></i>
                    </a>
                    {{-- Icon Gerigi → Kelola Bio & Profil --}}
                    <a href="{{ route('creator.bio.index') }}" title="Kelola Bio & Profil Creator"
                        style="width:34px; height:34px; border-radius:10px; background:#f8fafc; border:1.5px solid #e2e8f0; color:#475569; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; flex-shrink:0; transition:all 0.15s ease;">
                        <i class="ph-bold ph-gear-six" style="font-size:1.05rem;"></i>
                    </a>
                </div>
            </div>

            {{-- CARD 2: WEBSITE (https://buyle.id/c/username) --}}
            <div style="text-align:left; margin-bottom:0.5rem;">
                <label
                    style="display:block; font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.35rem;">
                    Website
                </label>
                <div
                    style="display:flex; align-items:center; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:0.4rem 0.5rem 0.4rem 0.85rem; gap:0.45rem;">
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
                    {{-- Icon Gerigi → Kelola Profil Creator --}}
                    <a href="{{ route('creator.profile.edit') }}" title="Edit Profil Creator"
                        style="width:34px; height:34px; border-radius:10px; background:#f8fafc; border:1.5px solid #e2e8f0; color:#475569; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; flex-shrink:0; transition:all 0.15s ease;">
                        <i class="ph-bold ph-gear-six" style="font-size:1.05rem;"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- ── MODAL PIN KEAMANAN DIWAJIBKAN SEBELUM TARIK / TRANSFER ── --}}
    <style>
        #pinRequiredModal {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1), backdrop-filter 0.28s ease;
        }
        #pinRequiredModal.is-open {
            opacity: 1;
            pointer-events: auto;
        }
        #pinRequiredModal .pin-req-card {
            transform: translateY(24px) scale(0.95);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease;
        }
        #pinRequiredModal.is-open .pin-req-card {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    </style>

    <div id="pinRequiredModal"
        style="display:flex; position:fixed; inset:0; background:rgba(15,23,42,0.65); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:1.25rem;"
        onclick="if(event.target===this) closePinRequiredModal()">
        <div class="pin-req-card"
            style="background:#ffffff; border-radius:24px; max-width:390px; width:100%; padding:1.75rem 1.5rem; text-align:center; box-shadow:0 24px 60px rgba(0,0,0,0.22); position:relative; box-sizing:border-box;">
            
            <div style="width:58px; height:58px; border-radius:50%; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; margin:0 auto 1.1rem; color:#16a34a; font-size:1.75rem;">
                <i class="ph-bold ph-shield-check"></i>
            </div>

            <h3 style="font-size:1.15rem; font-weight:700; color:#0f172a; margin:0 0 0.5rem; font-family:'Montserrat',sans-serif;">
                Atur PIN Keamanan
            </h3>
            
            <p style="font-size:0.83rem; color:#64748b; line-height:1.55; margin:0 0 1.5rem;">
                Demi keamanan akun Buyle Pay Anda, Anda wajib mengatur <strong style="color:#0f172a;">6 digit PIN keamanan</strong> terlebih dahulu sebelum melakukan <span id="pinReqActionName">penarikan atau transfer</span> saldo.
            </p>

            <div style="display:flex; flex-direction:column; gap:0.65rem;">
                <a href="{{ route('creator.menu') }}#pin"
                    style="display:block; width:100%; padding:0.85rem; background:linear-gradient(135deg, #1eb349 0%, #16a34a 100%); color:#ffffff; font-weight:600; font-size:0.88rem; border-radius:14px; text-decoration:none; box-shadow:0 4px 14px rgba(30,179,73,0.35); box-sizing:border-box; transition:all 0.2s;">
                    Atur PIN Sekarang
                </a>
                <button type="button" onclick="closePinRequiredModal()"
                    style="width:100%; padding:0.75rem; background:transparent; border:none; color:#94a3b8; font-weight:600; font-size:0.82rem; cursor:pointer;">
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>

    {{-- ── MODAL TRANSFER SALDO BUYLE PAY ANTAR USER ── --}}
    <style>
        #transferModal {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1),
                backdrop-filter 0.28s ease;
        }

        #transferModal.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        #transferModal .transfer-modal-card {
            transform: translateY(28px) scale(0.95);
            opacity: 0;
            transition: transform 0.38s cubic-bezier(0.34, 1.56, 0.64, 1),
                opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
            max-height: 90vh;
            overflow-y: auto;
        }

        #transferModal.is-open .transfer-modal-card {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        #transferModal.is-closing .transfer-modal-card {
            transform: translateY(18px) scale(0.96);
            opacity: 0;
            transition: transform 0.22s cubic-bezier(0.4, 0, 1, 1),
                opacity 0.18s cubic-bezier(0.4, 0, 1, 1);
        }

        #transferModal.is-closing {
            opacity: 0;
            transition: opacity 0.22s cubic-bezier(0.4, 0, 1, 1);
        }

        .transfer-chip-btn {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            padding: 5px 9px;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            font-family: 'Montserrat', sans-serif;
            white-space: nowrap;
        }

        .transfer-chip-btn:hover {
            border-color: #1eb349;
            color: #1eb349;
            background: #f0fdf4;
        }
    </style>

    <div id="transferModal"
        style="display:flex; position:fixed; inset:0; background:rgba(15,23,42,0.65); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:1.25rem;"
        onclick="if(event.target===this) closeTransferModal()">
        <div class="transfer-modal-card"
            style="background:#ffffff; border-radius:24px; max-width:420px; width:100%; padding:1.5rem; text-align:center; box-shadow:0 24px 60px rgba(0,0,0,0.22); position:relative; box-sizing:border-box;">

            {{-- Tombol X di Pojok Kanan Atas --}}
            <button type="button" onclick="closeTransferModal()"
                style="position:absolute; top:1.1rem; right:1.1rem; width:34px; height:34px; border-radius:50%; background:#f8fafc; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; color:#64748b; font-size:1.15rem; cursor:pointer; transition:all 0.15s ease;"
                onmouseover="this.style.background='#fee2e2'; this.style.color='#ef4444'; this.style.borderColor='#fca5a5';"
                onmouseout="this.style.background='#f8fafc'; this.style.color='#64748b'; this.style.borderColor='#e2e8f0';"
                title="Tutup Modal">
                <i class="ph-bold ph-x"></i>
            </button>

            {{-- Brand Logo & Header --}}
            <div style="margin-bottom:0.85rem;">
                <img src="{{ asset('images/logobuylepay.png') }}" alt="Buyle Pay" style="height:22px; width:auto; object-fit:contain; margin-bottom:0.4rem; display:inline-block;">
                <h3 style="font-size:1.15rem; font-weight:700; color:#0f172a; margin:0;">Transfer</h3>
            </div>

            {{-- Info Saldo Pengirim --}}
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:0.6rem 0.85rem; margin-bottom:1rem; display:flex; align-items:center; justify-content:space-between; gap:0.5rem; flex-wrap:wrap;">
                <div style="text-align:left;">
                    <div style="font-size:0.66rem; font-weight:600; color:#166534; text-transform:uppercase; letter-spacing:0.02em;">Saldo Siap Ditarik</div>
                    <div id="transferModalAvailableBalance" style="font-size:0.95rem; font-weight:700; color:#15803d; font-family:'Montserrat',sans-serif;">
                        Rp {{ number_format($availableBalance, 0, ',', '.') }}
                    </div>
                </div>
                <div style="font-size:0.68rem; font-weight:600; color:#166534; background:#dcfce7; padding:3px 8px; border-radius:6px; border:1px solid #86efac;">
                    ⚡ Bebas Biaya
                </div>
            </div>

            {{-- FORM TRANSFER --}}
            <form id="transferForm" onsubmit="event.preventDefault(); submitTransfer();" style="text-align:left;">
                
                {{-- STEP 1: USERNAME TUJUAN --}}
                <div style="margin-bottom:0.85rem;">
                    <label style="display:block; font-size:0.72rem; font-weight:700; color:#334155; text-transform:uppercase; letter-spacing:0.03em; margin-bottom:0.35rem;">
                        Username Penerima <span style="color:#ef4444;">*</span>
                    </label>
                    <div style="position:relative; display:flex; align-items:center;">
                        <span style="position:absolute; left:12px; font-weight:700; color:#94a3b8; font-size:0.88rem; pointer-events:none;">@</span>
                        <input type="text" id="transferUsernameInput"
                            placeholder="username_tujuan"
                            autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                            oninput="onTransferUsernameInput(this.value)"
                            style="width:100%; box-sizing:border-box; padding:0.6rem 2.2rem 0.6rem 1.95rem; border-radius:12px; border:1.5px solid #e2e8f0; font-size:0.85rem; font-weight:600; color:#0f172a; outline:none; transition:all 0.15s ease; font-family:'Montserrat',sans-serif;">
                        
                        {{-- Loading / Status Spinner --}}
                        <div id="transferUserStatusIcon" style="position:absolute; right:12px; display:none; align-items:center; justify-content:center;">
                            <svg class="animate-spin" width="16" height="16" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#94a3b8" stroke-width="3" stroke-dasharray="32" stroke-linecap="round"></circle></svg>
                        </div>
                    </div>

                    {{-- User Preview Card (Muncul otomatis saat valid) --}}
                    <div id="transferUserPreviewCard" style="display:none; margin-top:0.5rem; background:#f8fafc; border:1.5px solid #86efac; border-radius:12px; padding:0.55rem 0.75rem; align-items:center; gap:0.65rem;">
                        <div id="transferAvatarWrap" style="width:38px; height:38px; border-radius:50%; background:#22c55e; color:#ffffff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.95rem; flex-shrink:0; overflow:hidden;">
                            <img id="transferAvatarImg" src="" alt="" style="width:100%; height:100%; object-fit:cover; display:none;">
                            <span id="transferAvatarInitial">U</span>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div id="transferUserName" style="font-weight:700; font-size:0.84rem; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"></div>
                            <div style="display:flex; align-items:center; gap:4px; margin-top:1px;">
                                <span id="transferUserBadge" style="font-size:0.7rem; color:#15803d; font-weight:600;"></span>
                                <svg width="13" height="13" fill="#16a34a" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Error Message Username --}}
                    <div id="transferUsernameError" style="display:none; color:#dc2626; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:0.45rem 0.65rem; font-size:0.72rem; margin-top:0.4rem; line-height:1.35;"></div>
                </div>

                {{-- STEP 2: NOMINAL TRANSFER --}}
                <div style="margin-bottom:0.85rem;">
                    <label style="display:block; font-size:0.72rem; font-weight:700; color:#334155; text-transform:uppercase; letter-spacing:0.03em; margin-bottom:0.35rem;">
                        Nominal Transfer (Rp) <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" id="transferAmountInput"
                        placeholder="Contoh: 50.000" inputmode="numeric"
                        oninput="onTransferAmountInput(this)"
                        style="width:100%; box-sizing:border-box; padding:0.6rem 0.85rem; border-radius:12px; border:1.5px solid #e2e8f0; font-size:0.95rem; font-weight:700; color:#0f172a; outline:none; transition:all 0.15s ease; font-family:'Montserrat',sans-serif;">

                    {{-- Quick Amount Chips --}}
                    <div style="display:flex; gap:0.35rem; margin-top:0.45rem; flex-wrap:wrap;">
                        <button type="button" class="transfer-chip-btn" onclick="setTransferAmount(10000)">10rb</button>
                        <button type="button" class="transfer-chip-btn" onclick="setTransferAmount(25000)">25rb</button>
                        <button type="button" class="transfer-chip-btn" onclick="setTransferAmount(50000)">50rb</button>
                        <button type="button" class="transfer-chip-btn" onclick="setTransferAmount(100000)">100rb</button>
                        <button type="button" class="transfer-chip-btn" onclick="setTransferAmount('all')">Semua Saldo</button>
                    </div>

                    <div id="transferAmountError" style="display:none; color:#dc2626; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:0.45rem 0.65rem; font-size:0.72rem; margin-top:0.4rem; line-height:1.35;"></div>
                </div>

                {{-- STEP 3: CATATAN (OPSIONAL) --}}
                <div style="margin-bottom:1.15rem;">
                    <label style="display:block; font-size:0.72rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.03em; margin-bottom:0.35rem;">
                        Catatan (Opsional)
                    </label>
                    <input type="text" id="transferNotesInput"
                        placeholder="Misal: Honor kru, bagi hasil tiket..." maxlength="150"
                        style="width:100%; box-sizing:border-box; padding:0.55rem 0.85rem; border-radius:12px; border:1.5px solid #e2e8f0; font-size:0.8rem; color:#334155; outline:none; font-family:'Montserrat',sans-serif;">
                </div>

                {{-- TOMBOL SUBMIT --}}
                <button type="submit" id="btnSubmitTransfer" disabled
                    style="width:100%; height:44px; border-radius:999px; background:#181d27; color:#ffffff; font-size:0.86rem; font-weight:700; border:none; cursor:not-allowed; display:inline-flex; align-items:center; justify-content:center; gap:6px; opacity:0.5; transition:all 0.2s ease; font-family:'Montserrat',sans-serif;">
                    <span>Kirim Saldo</span>
                    <i class="ph-bold ph-paper-plane-tilt" style="font-size:0.95rem;"></i>
                </button>
            </form>

            {{-- SUCCESS STATE VIEW --}}
            <div id="transferSuccessState" style="display:none; padding:0.5rem 0; text-align:center;">
                <div style="width:52px; height:52px; border-radius:50%; background:#dcfce7; color:#16a34a; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; font-size:1.6rem;">
                    <i class="ph-bold ph-check"></i>
                </div>
                <h4 style="font-size:1.1rem; font-weight:700; color:#0f172a; margin:0 0 0.35rem;">Transfer Berhasil!</h4>
                <p id="transferSuccessMsg" style="font-size:0.8rem; color:#475569; margin:0 0 1rem; line-height:1.45;"></p>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.75rem 1rem; margin-bottom:1.25rem; text-align:left;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.35rem; font-size:0.75rem;">
                        <span style="color:#64748b;">Nominal:</span>
                        <span id="transferSuccessAmount" style="font-weight:700; color:#0f172a;"></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.35rem; font-size:0.75rem;">
                        <span style="color:#64748b;">Penerima:</span>
                        <span id="transferSuccessReceiver" style="font-weight:700; color:#15803d;"></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:0.75rem;">
                        <span style="color:#64748b;">Kode Ref:</span>
                        <span id="transferSuccessCode" style="font-family:monospace; font-weight:700; color:#334155;"></span>
                    </div>
                </div>
                <button type="button" onclick="closeTransferModal(); window.location.reload();"
                    style="width:100%; height:44px; border-radius:999px; background:#181d27; color:#ffffff; font-size:0.86rem; font-weight:700; border:none; cursor:pointer; font-family:'Montserrat',sans-serif;">
                    Selesai
                </button>
            </div>

        </div>
    </div>

    {{-- ── MODAL TOP UP SALDO BUYLE PAY ── --}}
    <style>
        #topupModal {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1),
                backdrop-filter 0.28s ease;
        }

        #topupModal.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        #topupModal .topup-modal-card {
            transform: translateY(28px) scale(0.95);
            opacity: 0;
            transition: transform 0.38s cubic-bezier(0.34, 1.56, 0.64, 1),
                opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
            max-height: 90vh;
            overflow-y: auto;
        }

        #topupModal.is-open .topup-modal-card {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        #topupModal.is-closing .topup-modal-card {
            transform: translateY(18px) scale(0.96);
            opacity: 0;
            transition: transform 0.22s cubic-bezier(0.4, 0, 1, 1),
                opacity 0.18s cubic-bezier(0.4, 0, 1, 1);
        }

        #topupModal.is-closing {
            opacity: 0;
            transition: opacity 0.22s cubic-bezier(0.4, 0, 1, 1);
        }
    </style>

    <div id="topupModal"
        style="display:flex; position:fixed; inset:0; background:rgba(15,23,42,0.65); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:1.25rem;"
        onclick="if(event.target===this) closeTopUpModal()">
        <div class="topup-modal-card"
            style="background:#ffffff; border-radius:24px; max-width:420px; width:100%; padding:1.5rem; text-align:center; box-shadow:0 24px 60px rgba(0,0,0,0.22); position:relative; box-sizing:border-box;">

            {{-- Tombol X di Pojok Kanan Atas --}}
            <button type="button" onclick="closeTopUpModal()"
                style="position:absolute; top:1.1rem; right:1.1rem; width:34px; height:34px; border-radius:50%; background:#f8fafc; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; color:#64748b; font-size:1.15rem; cursor:pointer; transition:all 0.15s ease;"
                onmouseover="this.style.background='#fee2e2'; this.style.color='#ef4444'; this.style.borderColor='#fca5a5';"
                onmouseout="this.style.background='#f8fafc'; this.style.color='#64748b'; this.style.borderColor='#e2e8f0';"
                title="Tutup Modal">
                <i class="ph-bold ph-x"></i>
            </button>

            {{-- Brand Logo & Header --}}
            <div style="margin-bottom:0.85rem;">
                <img src="{{ asset('images/logobuylepay.png') }}" alt="Buyle Pay" style="height:22px; width:auto; object-fit:contain; margin-bottom:0.4rem; display:inline-block;">
                <h3 style="font-size:1.15rem; font-weight:700; color:#0f172a; margin:0;">Top Up</h3>
            </div>

            {{-- Saldo Saat Ini --}}
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.6rem 0.85rem; margin-bottom:1rem; display:flex; align-items:center; justify-content:space-between; gap:0.5rem;">
                <div style="text-align:left;">
                    <div style="font-size:0.66rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.02em;">Saldo Saat Ini</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#0f172a; font-family:'Montserrat',sans-serif;">
                        Rp {{ number_format($availableBalance, 0, ',', '.') }}
                    </div>
                </div>
                <div style="font-size:0.68rem; font-weight:600; color:#15803d; background:#f0fdf4; padding:3px 8px; border-radius:6px; border:1px solid #bbf7d0;">
                    Instan 24 Jam
                </div>
            </div>

            {{-- FORM TOP UP --}}
            <form id="topupForm" onsubmit="event.preventDefault(); submitTopUp();" style="text-align:left;">
                <div style="margin-bottom:0.85rem;">
                    <label style="display:block; font-size:0.72rem; font-weight:700; color:#334155; text-transform:uppercase; letter-spacing:0.03em; margin-bottom:0.35rem;">
                        Nominal Top Up (Rp) <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" id="topupAmountInput"
                        placeholder="Min. Rp 10.000" inputmode="numeric"
                        oninput="onTopUpAmountInput(this)"
                        style="width:100%; box-sizing:border-box; padding:0.6rem 0.85rem; border-radius:12px; border:1.5px solid #e2e8f0; font-size:0.95rem; font-weight:700; color:#0f172a; outline:none; transition:all 0.15s ease; font-family:'Montserrat',sans-serif;">

                    {{-- Quick Amount Chips --}}
                    <div style="display:flex; gap:0.35rem; margin-top:0.45rem; flex-wrap:wrap;">
                        <button type="button" class="transfer-chip-btn" onclick="setTopUpAmount(20000)">20rb</button>
                        <button type="button" class="transfer-chip-btn" onclick="setTopUpAmount(50000)">50rb</button>
                        <button type="button" class="transfer-chip-btn" onclick="setTopUpAmount(100000)">100rb</button>
                        <button type="button" class="transfer-chip-btn" onclick="setTopUpAmount(250000)">250rb</button>
                        <button type="button" class="transfer-chip-btn" onclick="setTopUpAmount(500000)">500rb</button>
                    </div>

                    <div id="topupAmountError" style="display:none; color:#dc2626; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:0.45rem 0.65rem; font-size:0.72rem; margin-top:0.4rem; line-height:1.35;"></div>
                </div>

                {{-- TOMBOL SUBMIT --}}
                <button type="submit" id="btnSubmitTopUp"
                    style="width:100%; height:44px; border-radius:999px; background:#181d27; color:#ffffff; font-size:0.86rem; font-weight:700; border:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:6px; margin-top:0.5rem; transition:all 0.2s ease; font-family:'Montserrat',sans-serif;">
                    <span>Lanjut Pembayaran</span>
                    <i class="ph-bold ph-arrow-right" style="font-size:0.95rem;"></i>
                </button>
            </form>

        </div>
    </div>

    {{-- Safe Area Top Bar for Midtrans Snap on iOS (Dynamic Island & Notch) --}}
    <div class="snap-safe-top-bar" id="snapSafeTopBar"></div>

    @php
        $midtransService = app(\App\Services\MidtransService::class);
        $isMidtransProd = $midtransService->isProduction();
        $snapJsUrl = $isMidtransProd ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $midtransClientKey = $midtransService->getClientKey();
    @endphp
    @if(!empty($midtransClientKey))
        <script src="{{ $snapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    @endif

    <script>
        function openTopUpModal() {
            const m = document.getElementById('topupModal');
            if (!m) return;
            document.getElementById('topupAmountInput').value = '';
            document.getElementById('topupAmountError').style.display = 'none';
            m.classList.remove('is-closing');
            void m.offsetWidth;
            m.classList.add('is-open');
        }

        function closeTopUpModal() {
            const m = document.getElementById('topupModal');
            if (!m) return;
            m.classList.remove('is-open');
            m.classList.add('is-closing');
            setTimeout(() => {
                m.classList.remove('is-closing');
            }, 240);
        }

        function onTopUpAmountInput(input) {
            let clean = input.value.replace(/\D/g, '');
            if (!clean) {
                input.value = '';
            } else {
                input.value = new Intl.NumberFormat('id-ID').format(clean);
            }
        }

        function setTopUpAmount(amt) {
            const input = document.getElementById('topupAmountInput');
            if (!input) return;
            input.value = new Intl.NumberFormat('id-ID').format(amt);
        }

        async function submitTopUp() {
            const input = document.getElementById('topupAmountInput');
            const amt = parseInt(input.value.replace(/\D/g, ''), 10) || 0;
            const err = document.getElementById('topupAmountError');
            if (amt < 10000) {
                err.textContent = 'Minimal top up saldo adalah Rp 10.000.';
                err.style.display = 'block';
                return;
            }
            err.style.display = 'none';

            const btn = document.getElementById('btnSubmitTopUp');
            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin" width="16" height="16" fill="none" viewBox="0 0 24 24" style="margin-right:6px;"><circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="3" stroke-dasharray="32" stroke-linecap="round"></circle></svg> <span>Menyiapkan Tagihan...</span>`;

            try {
                const res = await fetch(`{{ route('creator.pos.deposit') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ amount: amt })
                });
                const data = await res.json();

                if (data.success && data.snap_token) {
                    closeTopUpModal();

                    const triggerSnap = () => {
                        document.body.classList.add('snap-open');

                        // Enforce iOS Safe Area under Dynamic Island & Notch
                        let snapInterval = null;
                        const adjustSnapFrame = () => {
                            const snapIframe = document.getElementById('snap-midtrans') || document.querySelector('iframe[name^="popup_"]');
                            if (snapIframe) {
                                snapIframe.style.setProperty('top', 'max(50px, env(safe-area-inset-top, 50px))', 'important');
                                snapIframe.style.setProperty('height', 'calc(100% - max(50px, env(safe-area-inset-top, 50px)) - max(16px, env(safe-area-inset-bottom, 16px)))', 'important');
                                snapIframe.style.setProperty('box-sizing', 'border-box', 'important');
                            }
                        };

                        adjustSnapFrame();
                        snapInterval = setInterval(adjustSnapFrame, 150);

                        const cleanupSnap = () => {
                            if (snapInterval) clearInterval(snapInterval);
                            document.body.classList.remove('snap-open');
                        };

                        window.snap.pay(data.snap_token, {
                            onSuccess: function (result) {
                                cleanupSnap();
                                alert('Top up saldo berhasil!');
                                window.location.reload();
                            },
                            onPending: function (result) {
                                cleanupSnap();
                                alert('Menunggu pembayaran Anda selesaikan.');
                                window.location.reload();
                            },
                            onError: function (result) {
                                cleanupSnap();
                                alert('Pembayaran gagal atau dibatalkan.');
                            },
                            onClose: function () {
                                cleanupSnap();
                            }
                        });
                    };

                    if (window.snap) {
                        triggerSnap();
                    } else {
                        const script = document.createElement('script');
                        script.src = "{{ $snapJsUrl }}";
                        script.setAttribute('data-client-key', "{{ $midtransClientKey }}");
                        script.onload = () => {
                            if (window.snap) {
                                triggerSnap();
                            } else {
                                window.location.href = `https://app.midtrans.com/snap/v2/vtweb/${data.snap_token}`;
                            }
                        };
                        script.onerror = () => {
                            window.location.href = `https://app.midtrans.com/snap/v2/vtweb/${data.snap_token}`;
                        };
                        document.head.appendChild(script);
                    }
                } else {
                    err.textContent = data.message || 'Gagal membuat tagihan top up.';
                    err.style.display = 'block';
                }
            } catch (e) {
                err.textContent = 'Terjadi kesalahan jaringan.';
                err.style.display = 'block';
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<span>Lanjut Pembayaran</span> <i class="ph-bold ph-arrow-right"></i>`;
            }
        }
        function openShareStoreModal() {
            const m = document.getElementById('shareStoreModal');
            if (!m) return;
            m.classList.remove('is-closing');
            // Trigger reflow agar transisi berjalan dari state awal
            void m.offsetWidth;
            m.classList.add('is-open');
        }
        function closeShareStoreModal() {
            const m = document.getElementById('shareStoreModal');
            if (!m) return;
            m.classList.remove('is-open');
            m.classList.add('is-closing');
            // Tunggu animasi selesai baru sembunyikan
            setTimeout(() => {
                m.classList.remove('is-closing');
            }, 240);
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

        // ── HIDE / SHOW NOMINAL SALDO BUYLE PAY ──
        function initNinaBalanceState() {
            const isHidden = localStorage.getItem('buyle_hide_balance') === 'true';
            applyNinaBalanceState(isHidden);
        }

        function toggleNinaBalanceVisibility() {
            const currentHidden = localStorage.getItem('buyle_hide_balance') === 'true';
            const newHidden = !currentHidden;
            localStorage.setItem('buyle_hide_balance', newHidden ? 'true' : 'false');
            applyNinaBalanceState(newHidden);
        }

        function applyNinaBalanceState(isHidden) {
            const el = document.getElementById('ninaBalanceAmount');
            const icon = document.getElementById('ninaEyeIcon');
            if (!el) return;
            if (isHidden) {
                el.textContent = 'Rp ••••••••';
                if (icon) icon.className = 'ph-bold ph-eye-slash';
            } else {
                el.textContent = el.dataset.formatted || 'Rp 0';
                if (icon) icon.className = 'ph-bold ph-eye';
            }
        }

        // Jalankan saat script selesai dimuat
        document.addEventListener('DOMContentLoaded', initNinaBalanceState);
        setTimeout(initNinaBalanceState, 50);

        // ── CEK WAJIB SETTING PIN SEBELUM TARIK SALDO & TRANSFER ──
        const hasSecurityPin = {{ (!empty($seller->security_pin) || !empty($seller->pos_pin)) ? 'true' : 'false' }};

        function handleTarikSaldoClick(e) {
            if (!hasSecurityPin) {
                if (e) e.preventDefault();
                openPinRequiredModal('penarikan');
                return false;
            }
            return true;
        }

        function handleTransferClick(e) {
            if (!hasSecurityPin) {
                if (e) e.preventDefault();
                openPinRequiredModal('transfer');
                return false;
            }
            openTransferModal();
        }

        function openPinRequiredModal(actionName) {
            const m = document.getElementById('pinRequiredModal');
            if (!m) return;
            const actionEl = document.getElementById('pinReqActionName');
            if (actionEl && actionName) {
                actionEl.textContent = actionName;
            }
            m.classList.add('is-open');
        }

        function closePinRequiredModal() {
            const m = document.getElementById('pinRequiredModal');
            if (!m) return;
            m.classList.remove('is-open');
        }

        // ── LOGIKA MODAL TRANSFER SALDO BUYLE PAY ──
        let currentAvailableBalance = {{ (float) ($availableBalance ?? 0) }};
        let isTransferUserValid = false;
        let verifiedTransferUser = null;
        let transferCheckDebounce = null;

        function openTransferModal() {
            if (!hasSecurityPin) {
                openPinRequiredModal('transfer');
                return;
            }
            const m = document.getElementById('transferModal');
            if (!m) return;

            // Reset form
            document.getElementById('transferForm').style.display = 'block';
            document.getElementById('transferSuccessState').style.display = 'none';
            document.getElementById('transferUsernameInput').value = '';
            document.getElementById('transferAmountInput').value = '';
            document.getElementById('transferNotesInput').value = '';
            document.getElementById('transferUserPreviewCard').style.display = 'none';
            document.getElementById('transferUsernameError').style.display = 'none';
            document.getElementById('transferAmountError').style.display = 'none';
            isTransferUserValid = false;
            verifiedTransferUser = null;
            updateTransferSubmitButton();

            m.classList.remove('is-closing');
            void m.offsetWidth;
            m.classList.add('is-open');
        }

        function closeTransferModal() {
            const m = document.getElementById('transferModal');
            if (!m) return;
            m.classList.remove('is-open');
            m.classList.add('is-closing');
            setTimeout(() => {
                m.classList.remove('is-closing');
            }, 240);
        }

        function onTransferUsernameInput(val) {
            clearTimeout(transferCheckDebounce);
            isTransferUserValid = false;
            verifiedTransferUser = null;
            updateTransferSubmitButton();

            const previewCard = document.getElementById('transferUserPreviewCard');
            const errBox = document.getElementById('transferUsernameError');
            const statusIcon = document.getElementById('transferUserStatusIcon');

            previewCard.style.display = 'none';
            errBox.style.display = 'none';

            const cleanVal = val.replace(/^@/, '').trim();
            if (cleanVal.length < 2) {
                statusIcon.style.display = 'none';
                return;
            }

            statusIcon.style.display = 'flex';

            transferCheckDebounce = setTimeout(async () => {
                try {
                    const res = await fetch(`{{ route('creator.transfer.check-user') }}?username=${encodeURIComponent(cleanVal)}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    statusIcon.style.display = 'none';

                    if (data.success && data.valid) {
                        isTransferUserValid = true;
                        verifiedTransferUser = data;

                        // Tampilkan Preview Profil
                        document.getElementById('transferUserName').textContent = data.store_name ? `${data.name} (${data.store_name})` : data.name;
                        document.getElementById('transferUserBadge').textContent = '@' + data.username;

                        const imgEl = document.getElementById('transferAvatarImg');
                        const initEl = document.getElementById('transferAvatarInitial');
                        if (data.avatar_url) {
                            imgEl.src = data.avatar_url;
                            imgEl.style.display = 'block';
                            initEl.style.display = 'none';
                        } else {
                            imgEl.style.display = 'none';
                            initEl.textContent = data.initial || 'U';
                            initEl.style.display = 'inline';
                        }

                        previewCard.style.display = 'flex';
                        errBox.style.display = 'none';
                    } else {
                        isTransferUserValid = false;
                        verifiedTransferUser = null;
                        previewCard.style.display = 'none';
                        errBox.textContent = data.message || 'Username tidak valid.';
                        errBox.style.display = 'block';
                    }
                } catch (e) {
                    statusIcon.style.display = 'none';
                    isTransferUserValid = false;
                    errBox.textContent = 'Gagal memeriksa username. Periksa koneksi internet.';
                    errBox.style.display = 'block';
                }
                updateTransferSubmitButton();
            }, 350);
        }

        function onTransferAmountInput(input) {
            let clean = input.value.replace(/\D/g, '');
            if (!clean) {
                input.value = '';
            } else {
                input.value = new Intl.NumberFormat('id-ID').format(clean);
            }
            validateTransferAmount();
            updateTransferSubmitButton();
        }

        function setTransferAmount(val) {
            const input = document.getElementById('transferAmountInput');
            if (val === 'all') {
                input.value = new Intl.NumberFormat('id-ID').format(Math.floor(currentAvailableBalance));
            } else {
                input.value = new Intl.NumberFormat('id-ID').format(val);
            }
            validateTransferAmount();
            updateTransferSubmitButton();
        }

        function getTransferRawAmount() {
            const input = document.getElementById('transferAmountInput');
            if (!input) return 0;
            return parseInt(input.value.replace(/\D/g, ''), 10) || 0;
        }

        function validateTransferAmount() {
            const errBox = document.getElementById('transferAmountError');
            const amt = getTransferRawAmount();
            if (amt <= 0) {
                errBox.style.display = 'none';
                return false;
            }
            if (amt < 1000) {
                errBox.textContent = 'Minimal transfer saldo adalah Rp 1.000.';
                errBox.style.display = 'block';
                return false;
            }
            if (amt > currentAvailableBalance) {
                errBox.textContent = `Saldo tidak mencukupi. Maksimal: Rp ${new Intl.NumberFormat('id-ID').format(currentAvailableBalance)}.`;
                errBox.style.display = 'block';
                return false;
            }
            errBox.style.display = 'none';
            return true;
        }

        function updateTransferSubmitButton() {
            const btn = document.getElementById('btnSubmitTransfer');
            if (!btn) return;
            const amt = getTransferRawAmount();
            const isAmountValid = (amt >= 1000 && amt <= currentAvailableBalance);

            if (isTransferUserValid && isAmountValid) {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
                btn.style.background = '#1eb349';
            } else {
                btn.disabled = true;
                btn.style.opacity = '0.5';
                btn.style.cursor = 'not-allowed';
                btn.style.background = '#181d27';
            }
        }

        async function submitTransfer() {
            if (!isTransferUserValid || !verifiedTransferUser) return;
            const amt = getTransferRawAmount();
            if (amt < 1000 || amt > currentAvailableBalance) return;

            const btn = document.getElementById('btnSubmitTransfer');
            const notes = document.getElementById('transferNotesInput').value;

            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin" width="16" height="16" fill="none" viewBox="0 0 24 24" style="margin-right:6px;"><circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="3" stroke-dasharray="32" stroke-linecap="round"></circle></svg> <span>Memproses...</span>`;

            try {
                const res = await fetch(`{{ route('creator.transfer.process') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        username: verifiedTransferUser.username,
                        amount: amt,
                        notes: notes
                    })
                });
                const data = await res.json();

                if (data.success) {
                    // Update saldo lokal
                    currentAvailableBalance = data.new_balance;
                    const balDisplay = document.getElementById('ninaBalanceAmount');
                    if (balDisplay) {
                        balDisplay.dataset.raw = data.new_balance;
                        balDisplay.dataset.formatted = data.formatted_new_balance;
                        if (localStorage.getItem('buyle_hide_balance') !== 'true') {
                            balDisplay.textContent = data.formatted_new_balance;
                        }
                    }
                    const modalBal = document.getElementById('transferModalAvailableBalance');
                    if (modalBal) modalBal.textContent = data.formatted_new_balance;

                    // Switch view to success
                    document.getElementById('transferForm').style.display = 'none';
                    document.getElementById('transferSuccessMsg').textContent = data.message;
                    document.getElementById('transferSuccessAmount').textContent = data.formatted_amount;
                    document.getElementById('transferSuccessReceiver').textContent = `@${data.receiver.username}`;
                    document.getElementById('transferSuccessCode').textContent = data.transfer_code;
                    document.getElementById('transferSuccessState').style.display = 'block';
                } else {
                    alert(data.message || 'Gagal melakukan transfer.');
                    btn.disabled = false;
                    btn.innerHTML = `<span>Kirim Saldo</span> <i class="ph-bold ph-paper-plane-tilt"></i>`;
                }
            } catch (e) {
                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                btn.disabled = false;
                btn.innerHTML = `<span>Kirim Saldo</span> <i class="ph-bold ph-paper-plane-tilt"></i>`;
            }
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
                                <button type="button" class="nina-avatar-trigger" id="ninaAvatarBtn"
                                    onclick="toggleNinaDropdown(event)" title="Menu Profil">
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
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <circle cx="12" cy="8" r="4" />
                                                <path d="M20 21a8 8 0 1 0-16 0" />
                                            </svg>
                                            <span>Profil</span>
                                        </a>

                                        {{-- 2. Pesanan Saya -> /akun/pesanan --}}
                                        <a href="{{ route('account.orders') }}" class="ndc-item">
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                                            </svg>
                                            <span>Pesanan Saya</span>
                                        </a>

                                        <div class="ndc-divider"></div>

                                        {{-- 3. Beranda Buyle -> / --}}
                                        <a href="{{ url('/') }}" class="ndc-item">
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                <polyline points="9 22 9 12 15 12 15 22" />
                                            </svg>
                                            <span>Beranda Buyle</span>
                                        </a>

                                        {{-- 4. Beranda BuyleTIX -> /creator/event-attendance --}}
                                        <a href="{{ route('creator.ticket.scanner') }}" class="ndc-item">
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z" />
                                                <path d="M13 5v14" />
                                            </svg>
                                            <span>Beranda BuyleTIX</span>
                                        </a>

                                        <div class="ndc-divider"></div>

                                        {{-- 5. Logout --}}
                                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="ndc-item ndc-logout">
                                                <svg width="17" height="17" fill="none" stroke="#ef4444" stroke-width="2.2"
                                                    viewBox="0 0 24 24">
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

                        {{-- Aksi Kanan: Tombol Bagikan Toko & Lonceng Notifikasi --}}
                        <div class="nina-topbar-actions" style="display: flex; align-items: center; gap: 7px;">
                            <button type="button" class="nina-notif-btn" onclick="openShareStoreModal()" title="Bagikan Digital Store & Website">
                                <i class="ph-bold ph-share-network" style="font-size: 1.05rem; color: #334155;"></i>
                            </button>

                            <a href="{{ route('creator.notif.index') }}" class="nina-notif-btn" id="ninaNotifBtn"
                                title="Notifikasi Toko">
                                <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2.2"
                                    viewBox="0 0 24 24">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                                @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                                    <span class="nina-notif-badge"
                                        id="ninaNotifBadge">{{ $unreadNotifCount > 99 ? '99+' : $unreadNotifCount }}</span>
                                @endif
                            </a>
                        </div>
                    </div>

                    {{-- Saldo Utama Buyle Pay: Logo di Kiri, Nominal di Paling Kanan + Icon Mata --}}
                    <div class="nina-balance-row">
                        <div class="nina-buylepay-logo-wrap" title="Buyle Pay">
                            <img src="{{ asset('images/logobuylepay.png') }}" alt="Buyle Pay" class="nina-buylepay-logo">
                        </div>

                        <div class="nina-balance-amount-wrap">
                            <div class="nina-balance-amount" id="ninaBalanceAmount"
                                data-raw="{{ $availableBalance }}"
                                data-formatted="Rp {{ number_format($availableBalance, 0, ',', '.') }}">
                                Rp {{ number_format($availableBalance, 0, ',', '.') }}
                            </div>
                            <button type="button" class="nina-btn-toggle-balance" id="ninaToggleBalanceBtn"
                                onclick="toggleNinaBalanceVisibility()" title="Sembunyikan / Tampilkan Saldo">
                                <i class="ph-bold ph-eye" id="ninaEyeIcon" style="font-size: 0.92rem;"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Dark Action Bar: Tarik Saldo, Top Up, Transfer & Riwayat Icon --}}
                    <div class="nina-action-bar">
                        <a href="{{ route('creator.payout.settings') }}" class="nina-action-btn" onclick="return handleTarikSaldoClick(event)">
                            <span>Tarik Saldo</span>
                            <i class="ph-bold ph-arrow-up-right" style="font-size: 0.85rem;"></i>
                        </a>

                        <button type="button" class="nina-action-btn" onclick="openTopUpModal()">
                            <i class="ph-bold ph-plus-circle" style="font-size: 0.88rem;"></i>
                            <span>Top Up</span>
                        </button>

                        <button type="button" class="nina-action-btn" onclick="handleTransferClick(event)">
                            <i class="ph-bold ph-arrows-left-right" style="font-size: 0.85rem;"></i>
                            <span>Transfer</span>
                        </button>

                        <a href="{{ route('creator.payout.settings') }}?tab=history" class="nina-action-btn nina-action-btn-icon" title="Riwayat Transaksi">
                            <i class="ph-bold ph-clock-counter-clockwise"></i>
                        </a>
                    </div>

                </div>

                {{-- 2. MIDDLE ROW: SHORTCUT (2x2) + YOUR INCOME SIDE-BY-SIDE (100% PERSIS REFERENSI) --}}
                <div class="nina-middle-row">

                    {{-- Kiri: Shortcut Grid 2x2 (Persis Send Again) --}}
                    <div class="nina-shortcut-box">
                        <div class="nina-row-title">Shortcut</div>
                        <div class="nina-mini-grid">
                            <a href="{{ route('creator.ticket.scanner') }}" class="nina-mini-card nina-mini-card-tix" title="Buyle TIX">
                                <picture style="display: flex; align-items: center; justify-content: center; width: 100%;">
                                    <source srcset="{{ asset('images/logobuyletix.webp') }}" type="image/webp">
                                    <img src="{{ asset('images/logobuyletix.png') }}" alt="Buyle TIX" class="nina-tix-logo">
                                </picture>
                            </a>
                            <a href="{{ route('creator.pos.index') }}" class="nina-mini-card nina-mini-card-pos" title="Kasir POS">
                                <img src="{{ asset('images/logobuyleposv2.png') }}" alt="Buyle POS" class="nina-pos-logo">
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
                                <button type="button" class="nina-income-period" id="trafficPeriodBtn"
                                    onclick="toggleTrafficPeriod(event)">
                                    <span id="trafficPeriodLabel">Bulan Ini</span>
                                    <i class="ph ph-caret-down" style="font-size:0.7rem;"></i>
                                </button>
                                <div id="trafficPeriodMenu" class="traffic-period-menu" style="display: none;">
                                    <button type="button" class="tpm-item"
                                        onclick="setTrafficPeriod('week', 'Minggu Ini')">Minggu Ini</button>
                                    <button type="button" class="tpm-item active"
                                        onclick="setTrafficPeriod('month', 'Bulan Ini')">Bulan Ini</button>
                                    <button type="button" class="tpm-item"
                                        onclick="setTrafficPeriod('year', 'Tahun Ini')">Tahun Ini</button>
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

            {{-- ── KOLOM KANAN: MILESTONE LOGIN 7 HARI & RECENT ACTIVITY ── --}}
            <div class="nina-col-right">

                {{-- Toast Interaktif Khusus Tema Buyle --}}
                <div id="buyleStreakToast" class="buyle-streak-toast" style="display: none;">
                    <div class="buyle-toast-icon">
                        <i class="ph-bold ph-check"></i>
                    </div>
                    <div class="buyle-toast-body">
                        <div class="buyle-toast-title" id="buyleToastTitle">Check-in Harian</div>
                        <div class="buyle-toast-desc" id="buyleToastDesc">Hari Ini aktif ✓</div>
                    </div>
                </div>

                {{-- ── CARD MILESTONE LOGIN 7 HARI (DAILY STREAK) ── --}}
                <div class="nina-milestone-card" id="ninaMilestoneCard">
                    <div class="nina-milestone-header">
                        <span class="nina-milestone-title">Daily Streak</span>
                        <span class="nina-milestone-badge" id="milestoneStreakBadge">
                            <i class="ph-fill ph-fire" style="color: #ea580c;"></i>
                            <span>Hari {{ $loginStreak }}/7</span>
                        </span>
                    </div>

                    {{-- 7-Day Streak Track (100% Persis Referensi & Interaktif Per Hari) --}}
                    <div class="nina-streak-track">
                        @for($day = 1; $day <= 7; $day++)
                            @php
                                $isPast = $day < $loginStreak;
                                $isToday = $day == $loginStreak;
                                $isUpcoming = $day > $loginStreak;
                                $isGrand = ($day == 7);
                            @endphp
                            <div class="nina-streak-day {{ $isPast ? 'is-checked' : '' }} {{ $isToday ? 'is-today' : '' }} {{ $isUpcoming ? 'is-upcoming' : '' }} {{ $isGrand ? 'is-grand-prize' : '' }}"
                                onclick="handleStreakDayClick({{ $day }}, '{{ $isPast ? 'past' : ($isToday ? 'today' : 'upcoming') }}', {{ $isGrand ? 'true' : 'false' }})"
                                title="{{ $isToday ? 'Hari Ini' : ($isPast ? 'Hari ke-' . $day . ' Selesai' : ($isGrand ? 'Hari ke-7: Bebas Admin' : 'Hari ke-' . $day)) }}">
                                @if($isToday)
                                    <div class="nina-streak-today-cap">Today</div>
                                @else
                                    <div class="nina-streak-day-label">Day {{ $day }}</div>
                                @endif

                                <div class="nina-streak-coin-wrap">
                                    @if($isPast)
                                        <div class="nina-streak-check-circle">
                                            <i class="ph-bold ph-check"></i>
                                        </div>
                                    @elseif($isToday)
                                        @if($isGrand)
                                            <svg width="28" height="28" viewBox="0 0 32 32" fill="none" class="nina-coin-stack">
                                                <ellipse cx="14" cy="18" rx="8" ry="6" fill="#ca8a04"/>
                                                <ellipse cx="14" cy="16" rx="8" ry="6" fill="#eab308"/>
                                                <circle cx="19" cy="14" r="8" fill="#facc15"/>
                                                <circle cx="19" cy="14" r="6" stroke="#fef08a" stroke-width="1" stroke-dasharray="1.5 1.5"/>
                                                <path d="M19 10v8M15 14h8" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        @else
                                            <svg width="28" height="28" viewBox="0 0 32 32" fill="none" class="nina-coin-stack">
                                                <ellipse cx="14" cy="18" rx="8" ry="6" fill="#15803d"/>
                                                <ellipse cx="14" cy="16" rx="8" ry="6" fill="#16a34a"/>
                                                <circle cx="19" cy="14" r="8" fill="#22c55e"/>
                                                <circle cx="19" cy="14" r="6" stroke="#bbf7d0" stroke-width="1" stroke-dasharray="1.5 1.5"/>
                                                <path d="M19 10.5v7M15.5 14h7" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round"/>
                                            </svg>
                                        @endif
                                    @else
                                        @if($isGrand)
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <circle cx="12" cy="12" r="10" fill="#fef9c3" stroke="#eab308" stroke-width="1.2"/>
                                                <circle cx="12" cy="12" r="7.5" stroke="#ca8a04" stroke-width="1" stroke-dasharray="2 2"/>
                                                <path d="M12 8v8M8 12h8" stroke="#ca8a04" stroke-width="1.8" stroke-linecap="round"/>
                                            </svg>
                                        @else
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <circle cx="12" cy="12" r="10" fill="#22c55e"/>
                                                <circle cx="12" cy="12" r="7.5" stroke="#ffffff" stroke-width="1.2" stroke-dasharray="2 2"/>
                                                <path d="M12 8v8M8 12h8" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        @endif
                                    @endif
                                </div>

                                <div class="nina-streak-reward-val">
                                    @if($isGrand)
                                        +1 Slot
                                    @else
                                        {{ $isToday ? '40' : '20' }}
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>

                    @if($canClaimStreak)
                        <div class="nina-streak-footer" style="justify-content: center; padding-top: 0.75rem;">
                            <button type="button" class="nina-btn-claim-streak" id="claimStreakBtn" onclick="claimDailyStreak()">
                                <i class="ph-bold ph-gift"></i>
                                <span>Klaim Bebas Admin</span>
                            </button>
                        </div>
                    @endif
                </div>

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
                            <div class="nina-swipe-wrapper">
                                <div class="nina-activity-item" data-href="{{ route('creator.sales.report') }}">
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
                                </div>
                                <div class="nina-swipe-action">
                                    <a href="{{ route('creator.sales.report') }}">
                                        <i class="ph-bold ph-eye" style="margin-right:5px;"></i>View
                                    </a>
                                </div>
                            </div>
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
        'week' => ['label' => 'Minggu Ini', 'count' => 0, 'growth' => '+100%'],
        'month' => ['label' => 'Bulan Ini', 'count' => 0, 'growth' => '+100%'],
        'year' => ['label' => 'Tahun Ini', 'count' => 0, 'growth' => '+100%']
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
        window.addEventListener('click', function (e) {
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

        // Simpan cache unread notif ke perangkat user (tanpa request database berulang)
        try {
            const currentUnread = {{ (int) ($unreadNotifCount ?? 0) }};
            localStorage.setItem('buyle_cr_unread_notif', currentUnread);
        } catch (e) { }

        // ── Swipe-left Recent Activity ──
        (function () {
            const SWIPE_THRESHOLD = 55;
            const ACTION_WIDTH = 90;

            function setupSwipe(wrapper) {
                const item = wrapper.querySelector('.nina-activity-item');
                const action = wrapper.querySelector('.nina-swipe-action');
                if (!item || !action) return;

                let startX = 0, startY = 0, currentX = 0, dragging = false, opened = false, axis = null;

                item.addEventListener('touchstart', function (e) {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                    dragging = true;
                    axis = null;
                    item.style.transition = 'none';
                    action.style.transition = 'none';
                }, { passive: true });

                item.addEventListener('touchmove', function (e) {
                    if (!dragging) return;
                    const dx = e.touches[0].clientX - startX;
                    const dy = e.touches[0].clientY - startY;

                    if (!axis) {
                        axis = Math.abs(dx) > Math.abs(dy) ? 'x' : 'y';
                    }
                    if (axis !== 'x') return;

                    e.preventDefault();
                    currentX = dx;
                    const base = opened ? -ACTION_WIDTH : 0;
                    const newX = Math.min(0, Math.max(base + currentX, -ACTION_WIDTH));
                    item.style.transform = 'translateX(' + newX + 'px)';
                    action.style.transform = 'translateX(' + (100 + (newX / ACTION_WIDTH) * 100) + '%)';
                }, { passive: false });

                item.addEventListener('touchend', function () {
                    dragging = false;
                    item.style.transition = 'transform 0.3s cubic-bezier(0.25,0.46,0.45,0.94)';
                    action.style.transition = 'transform 0.3s cubic-bezier(0.25,0.46,0.45,0.94)';
                    const base = opened ? -ACTION_WIDTH : 0;
                    const finalX = base + currentX;

                    if (!opened && currentX < -SWIPE_THRESHOLD) {
                        item.style.transform = 'translateX(-' + ACTION_WIDTH + 'px)';
                        action.style.transform = 'translateX(0%)';
                        opened = true;
                    } else if (opened && currentX > SWIPE_THRESHOLD) {
                        item.style.transform = '';
                        action.style.transform = 'translateX(100%)';
                        opened = false;
                    } else {
                        item.style.transform = opened ? 'translateX(-' + ACTION_WIDTH + 'px)' : '';
                        action.style.transform = opened ? 'translateX(0%)' : 'translateX(100%)';
                    }
                    currentX = 0;
                    axis = null;
                });

                // Desktop click still works
                item.addEventListener('click', function () {
                    if (opened) return;
                    const href = item.dataset.href;
                    if (href) window.location.href = href;
                });
            }

            document.querySelectorAll('.nina-swipe-wrapper').forEach(setupSwipe);

            // Close all on outside touch
            document.addEventListener('touchstart', function (e) {
                if (!e.target.closest('.nina-swipe-wrapper')) {
                    document.querySelectorAll('.nina-activity-item').forEach(function (el) {
                        el.style.transition = 'transform 0.3s cubic-bezier(0.25,0.46,0.45,0.94)';
                        el.style.transform = '';
                    });
                    document.querySelectorAll('.nina-swipe-action').forEach(function (el) {
                        el.style.transition = 'transform 0.3s cubic-bezier(0.25,0.46,0.45,0.94)';
                        el.style.transform = 'translateX(100%)';
                    });
                }
            }, { passive: true });
        })();

        // ── Handler Klaim Milestone Login 7 Hari (Anti Double Claim) ──
        let isClaimingStreak = false;
        function claimDailyStreak() {
            if (isClaimingStreak) return;
            isClaimingStreak = true;

            const btn = document.getElementById('claimStreakBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Memproses...';
            }

            fetch('{{ route('creator.streak.claim') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                isClaimingStreak = false;
                if (data.success) {
                    showStreakFeedback('Klaim Berhasil! 🎉', data.message, 'ph-gift');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);
                } else {
                    showStreakFeedback('Info', data.message || 'Reward sudah pernah diklaim.', 'ph-info');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="ph-bold ph-gift"></i> Klaim Bebas Admin';
                    }
                }
            })
            .catch(err => {
                isClaimingStreak = false;
                console.error(err);
                showStreakFeedback('Kendala Jaringan', 'Gagal memproses klaim.', 'ph-warning');
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ph-bold ph-gift"></i> Klaim Bebas Admin';
                }
            });
        }

        // ── Handler Klik Interaktif Kartu Streak Harian (Themed Toast) ──
        let buyleToastTimer = null;
        function showStreakFeedback(title, desc, iconClass = 'ph-check') {
            const toast = document.getElementById('buyleStreakToast');
            if (!toast) return;

            const titleEl = document.getElementById('buyleToastTitle');
            const descEl  = document.getElementById('buyleToastDesc');
            const iconEl  = toast.querySelector('.buyle-toast-icon i');

            if (titleEl) titleEl.textContent = title;
            if (descEl)  descEl.textContent  = desc;
            if (iconEl)  iconEl.className    = 'ph-bold ' + iconClass;

            try {
                if (navigator.vibrate) navigator.vibrate(35);
            } catch (e) {}

            toast.style.display = 'flex';
            void toast.offsetWidth;
            toast.classList.add('is-show');

            if (buyleToastTimer) clearTimeout(buyleToastTimer);
            buyleToastTimer = setTimeout(() => {
                toast.classList.remove('is-show');
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 300);
            }, 2300);
        }

        function handleStreakDayClick(day, status, isGrand) {
            const currentStreak = {{ (int) ($loginStreak ?? 1) }};
            const canClaim = {{ !empty($canClaimStreak) ? 'true' : 'false' }};

            if (isGrand && canClaim) {
                claimDailyStreak();
                return;
            }

            if (status === 'today') {
                showStreakFeedback('Hari Ini Sudah Aktif', 'Check-in Hari ke-' + day + ' sudah tersimpan. Lanjutkan besok!', 'ph-check-circle');
            } else if (status === 'past') {
                showStreakFeedback('Hari ke-' + day, 'Selesai check-in ✓', 'ph-check');
            } else {
                if (isGrand) {
                    showStreakFeedback('Reward Hari ke-7', '+1 Kuota Bebas Biaya Penarikan', 'ph-gift');
                } else {
                    const daysLeft = day - currentStreak;
                    showStreakFeedback('Hari ke-' + day, 'Terbuka dalam ' + daysLeft + ' hari lagi', 'ph-lock-key');
                }
            }
        }
    </script>
@endsection