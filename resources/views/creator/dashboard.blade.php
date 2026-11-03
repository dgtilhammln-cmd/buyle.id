@extends('creator.layout')

@section('title', 'Overview – Creator Studio')
@section('page_title', 'Overview')
@section('page_subtitle', 'Ringkasan performa toko, saldo real-time, dan transaksi terbaru.')

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
            overflow: hidden;
        }

        /* Baris Sapaan & Ikon Aksi Minimalist */
        .nina-card-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.1rem;
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
            padding: 2px 7px;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
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
@endsection

@php
    $storeName = $seller->name;
    $creatorProfile = $seller->creatorProfile;
    if ($creatorProfile && !empty($creatorProfile->store_name)) {
        $storeName = $creatorProfile->store_name;
    }

    // Pendapatan bulan ini
    $thisMonthSales = $recentSales->filter(function ($order) {
        return \Carbon\Carbon::parse($order->created_at)->isCurrentMonth();
    });
    $incomeThisMonth = $thisMonthSales->sum(fn($o) => $o->items->sum('subtotal'));
    if ($incomeThisMonth <= 0 && $gmv > 0) {
        $incomeThisMonth = $gmv;
    }

    $bioUrl = url('/' . $seller->username);

    $tierInfo = $creatorProfile ? $creatorProfile->getTierInfo($gmv ?? null) : [
        'name'     => 'PERINTIS',
        'badge'    => 'Perintis',
        'level'    => 1,
        'subtitle' => 'Matte Silver / Brushed Steel'
    ];
    $tierBadge = $tierInfo['badge'] ?? $tierInfo['name'] ?? 'Perintis';
@endphp

@section('content')
    <div class="nina-dash-root">

        <div class="nina-responsive-layout">

            {{-- ── KOLOM KIRI: MAIN BALANCE CARD + SHORTCUT & INCOME ROW ── --}}
            <div class="nina-col-left">

                {{-- 1. MAIN WALLET & BALANCE CARD (Gradasi Putih-Hijau, Minimalist Sesuai Referensi) --}}
                <div class="nina-balance-card">

                    {{-- Topbar: Sapaan Creator & Ikon Aksi Minimalist --}}
                    <div class="nina-card-topbar">
                        <div>
                            <div class="nina-greeting-title">
                                Halo, {{ $storeName }}!
                            </div>
                        </div>
                        <div class="nina-top-buttons">
                            <a href="{{ route('creator.app.index') }}" class="nina-circle-btn" title="Semua Aplikasi">
                                <i class="ph ph-squares-four"></i>
                            </a>
                            <a href="{{ route('creator.sales.report') }}" class="nina-circle-btn" title="Notifikasi Order">
                                <i class="ph ph-bell"></i>
                            </a>
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
                        @if(($holdBalance ?? 0) > 0)
                            <div style="margin-top: 0.5rem;">
                                <a href="{{ route('creator.payout.settings') }}" style="text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-size:0.72rem; color:#64748b; font-weight:600; background:#ffffff; padding:2px 10px; border-radius:999px; border:1px solid #e2e8f0; box-shadow:0 1px 2px rgba(0,0,0,0.03);">
                                    <span>(Rp {{ number_format($holdBalance, 0, ',', '.') }} Tertahan di Escrow)</span>
                                    <i class="ph ph-arrow-right" style="font-size:0.65rem;"></i>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Dark Action Bar: 2 Tombol Saja (Tarik Saldo & Bagikan - TANPA BARCODE) --}}
                    <div class="nina-action-bar">
                        <a href="{{ route('creator.payout.settings') }}" class="nina-action-btn">
                            <span>Tarik Saldo</span>
                            <i class="ph-bold ph-arrow-up-right" style="font-size: 0.85rem;"></i>
                        </a>

                        <button type="button" class="nina-action-btn"
                            onclick="navigator.clipboard.writeText('{{ $bioUrl }}'); alert('Link toko berhasil disalin ke clipboard!');">
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
                            <a href="{{ route('creator.products.index') }}" class="nina-mini-card" title="Kelola Produk">
                                <i class="ph ph-tote"></i>
                                <span>Produk</span>
                            </a>
                            <a href="{{ route('creator.products.create') }}" class="nina-mini-card" title="Tambah Produk">
                                <i class="ph ph-plus"></i>
                                <span>Tambah</span>
                            </a>
                            <a href="{{ route('creator.payout.settings') }}" class="nina-mini-card" title="Pencairan Dana">
                                <i class="ph ph-wallet"></i>
                                <span>Saldo</span>
                            </a>
                            <a href="{{ route('creator.bio.index') }}" class="nina-mini-card" title="Web Toko / Bio">
                                <i class="ph ph-globe"></i>
                                <span>Web Bio</span>
                            </a>
                        </div>
                    </div>

                    {{-- Kanan: Your Income (Card dengan Gelombang Hijau) --}}
                    <div class="nina-income-box">
                        <div class="nina-income-header">
                            <span class="nina-income-title">Your Income</span>
                            <span class="nina-income-period">Bulan Ini <i class="ph ph-caret-down"
                                    style="font-size:0.7rem;"></i></span>
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
                            <div class="nina-income-amount">
                                Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}
                            </div>
                            <div class="nina-income-badge">
                                <i class="ph ph-trend-up"></i>
                                <span>+100%</span>
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
@endsection