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
        --bg-card-top: #eef0f4;
        --border-color: #e2e8f0;
        --text-muted: #64748b;
    }

    /* JANGAN TIMPA background body agar sidebar desktop tetap berwarna hijau gradasi dan teks putih terbaca */
    .nina-dash-root {
        width: 100%;
        max-width: 1140px;
        margin: 0 auto;
        padding-bottom: 2rem;
    }

    /* Responsive Two-Column Grid for Desktop, One Column for Mobile */
    .nina-responsive-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }

    @media (min-width: 992px) {
        .nina-responsive-layout {
            grid-template-columns: 1.1fr 0.9fr;
            gap: 1.5rem;
            align-items: start;
        }
    }

    .nina-col-left,
    .nina-col-right {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* ── CARD 1: MAIN BALANCE & GREETING CARD (LIGHT/LAVENDER-GRAY) ── */
    .nina-balance-card {
        background: var(--bg-card-top);
        border-radius: 36px;
        padding: 1.85rem 1.75rem 1.5rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.9);
        position: relative;
        overflow: hidden;
    }

    /* Baris Sapaan & Ikon Aksi */
    .nina-card-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .nina-greeting-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
        letter-spacing: -0.02em;
    }

    .nina-greeting-title span {
        font-weight: 500;
        color: #64748b;
    }

    .nina-greeting-sub {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        margin-top: 2px;
    }

    .nina-top-buttons {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nina-circle-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
    }

    .nina-circle-btn:hover {
        transform: scale(1.08);
        color: #1eb349;
    }

    /* Badge Currency Pill */
    .nina-currency-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #ffffff;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.04em;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        margin: 0 auto 0.5rem;
    }

    /* Nominal Saldo Raksasa */
    .nina-balance-center {
        text-align: center;
        padding: 0.5rem 0 1.25rem;
    }

    .nina-balance-amount {
        font-size: clamp(2.2rem, 5vw, 3rem);
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.035em;
        line-height: 1.1;
        margin-bottom: 0.75rem;
    }

    /* Badge Pertumbuhan Hijau */
    .nina-growth-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #1eb349;
        color: #ffffff;
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3);
    }

    /* Action Pill Bar (Hitam Sleek Sesuai Gambar 1) */
    .nina-action-bar {
        background: var(--dark-pill);
        border-radius: 22px;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.25rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.15);
        gap: 8px;
    }

    .nina-action-btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        padding: 12px 14px;
        border-radius: 18px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
        transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: none;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
    }

    .nina-action-btn:hover {
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;
        transform: translateY(-1px);
    }

    .nina-action-icon-center {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        background: #ffffff;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .nina-action-icon-center:hover {
        transform: scale(1.08);
        color: #1eb349;
    }

    /* ── SHORTCUT SECTION (GANTI SEND AGAIN) ── */
    .nina-section-box {
        background: #ffffff;
        border-radius: 28px;
        padding: 1.5rem;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
    }

    .nina-section-title {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .nina-section-title a {
        font-size: 0.88rem;
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s;
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 700;
    }

    .nina-section-title a:hover {
        color: #1eb349;
    }

    /* 2x2 Squircle Shortcut Grid */
    .nina-shortcuts-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.85rem;
    }

    @media (max-width: 640px) {
        .nina-shortcuts-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .nina-shortcut-card {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1.15rem 0.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.01);
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
    }

    .nina-shortcut-card:hover {
        transform: translateY(-3px);
        background: #ffffff;
        border-color: #1eb349;
        box-shadow: 0 8px 24px rgba(30, 179, 73, 0.14);
    }

    .nina-shortcut-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 0.5rem;
        transition: all 0.2s;
    }

    .nina-shortcut-card:hover .nina-shortcut-icon {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #ffffff;
        border-color: transparent;
    }

    .nina-shortcut-label {
        font-size: 0.82rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.01em;
        text-align: center;
    }

    /* ── YOUR INCOME CARD ── */
    .nina-income-card {
        background: #ffffff;
        border-radius: 28px;
        padding: 1.5rem;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .nina-income-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
        font-weight: 700;
        color: #64748b;
    }

    .nina-income-dropdown-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        font-size: 0.85rem;
    }

    /* Wave Curve SVG */
    .nina-income-wave {
        width: 100%;
        height: 70px;
        margin: 1rem 0;
    }

    .nina-income-val {
        font-size: 1.45rem;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .nina-income-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* ── RECENT ACTIVITY (RIWAYAT ORDERAN MASUK) ── */
    .nina-activity-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .nina-activity-item {
        background: #ffffff;
        border-radius: 20px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }

    .nina-activity-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        border-color: #cbd5e1;
    }

    .nina-act-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .nina-act-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #0f172a;
        flex-shrink: 0;
    }

    .nina-act-buyer {
        font-size: 0.94rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
    }

    .nina-act-sub {
        font-size: 0.76rem;
        color: #64748b;
        font-weight: 600;
        margin-top: 2px;
    }

    .nina-act-right {
        text-align: right;
        flex-shrink: 0;
    }

    .nina-act-amount {
        font-size: 1rem;
        font-weight: 800;
        color: #1eb349;
        letter-spacing: -0.01em;
    }

    .nina-act-status {
        font-size: 0.7rem;
        font-weight: 700;
        color: #15803d;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 999px;
        padding: 2px 8px;
        display: inline-block;
        margin-top: 3px;
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
    $thisMonthSales = $recentSales->filter(function($order) {
        return \Carbon\Carbon::parse($order->created_at)->isCurrentMonth();
    });
    $incomeThisMonth = $thisMonthSales->sum(fn($o) => $o->items->sum('subtotal'));
    if ($incomeThisMonth <= 0 && $gmv > 0) {
        $incomeThisMonth = $gmv;
    }

    $bioUrl = url('/' . $seller->username);
@endphp

@section('content')
<div class="nina-dash-root">

    <div class="nina-responsive-layout">

        {{-- ── KOLOM KIRI: MAIN BALANCE CARD + SHORTCUT GRID ── --}}
        <div class="nina-col-left">

            {{-- 1. MAIN WALLET & BALANCE CARD (100% Sesuai Gambar 1) --}}
            <div class="nina-balance-card">
                
                {{-- Topbar: Sapaan Creator & Ikon Aksi --}}
                <div class="nina-card-topbar">
                    <div>
                        <div class="nina-greeting-title">
                            Halo, {{ $storeName }}! <span>Selamat datang</span>
                        </div>
                        <div class="nina-greeting-sub">
                            di Creator Studio Anda
                        </div>
                    </div>
                    <div class="nina-top-buttons">
                        <a href="{{ route('creator.app.index') }}" class="nina-circle-btn" title="Semua Aplikasi">
                            <i class="ph ph-squares-four" style="font-size: 1.25rem;"></i>
                        </a>
                        <a href="{{ route('creator.sales.report') }}" class="nina-circle-btn" title="Notifikasi Order">
                            <i class="ph ph-bell" style="font-size: 1.25rem;"></i>
                        </a>
                    </div>
                </div>

                {{-- Saldo Utama (Nominal Rupiah Raksasa) --}}
                <div class="nina-balance-center">
                    <div class="nina-currency-badge">
                        <i class="ph-bold ph-coins" style="color:#1eb349;"></i>
                        <span>IDR / RUPIAH</span>
                    </div>

                    <div class="nina-balance-amount">
                        Rp {{ number_format($availableBalance, 0, ',', '.') }}
                    </div>

                    <div class="nina-growth-pill">
                        <i class="ph-bold ph-trend-up"></i>
                        <span>Saldo Siap Ditarik</span>
                    </div>
                </div>

                {{-- Dark Action Bar: Tarik Saldo, QR / Link Bio, Bagikan --}}
                <div class="nina-action-bar">
                    <a href="{{ route('creator.payout.settings') }}" class="nina-action-btn">
                        <span>Tarik Saldo</span>
                        <i class="ph-bold ph-arrow-up-right" style="font-size: 0.9rem;"></i>
                    </a>

                    <a href="{{ $bioUrl }}" target="_blank" class="nina-action-icon-center" title="Buka Toko / Web Bio">
                        <i class="ph ph-qr-code" style="font-size: 1.4rem;"></i>
                    </a>

                    <button type="button" class="nina-action-btn" onclick="navigator.clipboard.writeText('{{ $bioUrl }}'); alert('Link toko berhasil disalin ke clipboard!');">
                        <i class="ph-bold ph-share-network" style="font-size: 0.9rem;"></i>
                        <span>Bagikan</span>
                    </button>
                </div>

            </div>

            {{-- 2. SHORTCUT CARD (GANTI SEND AGAIN) --}}
            <div class="nina-section-box">
                <div class="nina-section-title">
                    <span>Shortcut Menu</span>
                </div>

                <div class="nina-shortcuts-grid">
                    {{-- 1. Produk --}}
                    <a href="{{ route('creator.products.index') }}" class="nina-shortcut-card">
                        <div class="nina-shortcut-icon">
                            <i class="ph ph-tote"></i>
                        </div>
                        <span class="nina-shortcut-label">Kelola Produk</span>
                    </a>

                    {{-- 2. Tambah Produk --}}
                    <a href="{{ route('creator.products.create') }}" class="nina-shortcut-card">
                        <div class="nina-shortcut-icon">
                            <i class="ph ph-plus-circle"></i>
                        </div>
                        <span class="nina-shortcut-label">Tambah Produk</span>
                    </a>

                    {{-- 3. Pencairan --}}
                    <a href="{{ route('creator.payout.settings') }}" class="nina-shortcut-card">
                        <div class="nina-shortcut-icon">
                            <i class="ph ph-wallet"></i>
                        </div>
                        <span class="nina-shortcut-label">Pencairan Dana</span>
                    </a>

                    {{-- 4. Web Builder / Toko --}}
                    <a href="{{ route('creator.bio.index') }}" class="nina-shortcut-card">
                        <div class="nina-shortcut-icon">
                            <i class="ph ph-globe"></i>
                        </div>
                        <span class="nina-shortcut-label">Web Builder</span>
                    </a>
                </div>
            </div>

        </div>

        {{-- ── KOLOM KANAN: YOUR INCOME + RECENT ACTIVITY ── --}}
        <div class="nina-col-right">

            {{-- 3. YOUR INCOME CARD (Grafik Gelombang & Pendapatan Bulan Ini) --}}
            <div class="nina-income-card">
                <div class="nina-income-header">
                    <span style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">Your Income</span>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span>Bulan Ini</span>
                        <div class="nina-income-dropdown-icon">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>
                </div>

                {{-- Smooth Wave Curve (Persis Gambar 1) --}}
                <svg class="nina-income-wave" viewBox="0 0 160 50" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="incomeWaveGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#1eb349" stop-opacity="0.35" />
                            <stop offset="100%" stop-color="#1eb349" stop-opacity="0.0" />
                        </linearGradient>
                    </defs>
                    <path d="M 0,35 Q 35,42 70,25 T 140,12 T 160,8 L 160,50 L 0,50 Z" fill="url(#incomeWaveGrad)" />
                    <path d="M 0,35 Q 35,42 70,25 T 140,12 T 160,8" fill="none" stroke="#1eb349" stroke-width="3" stroke-linecap="round" />
                </svg>

                <div class="nina-income-footer">
                    <div class="nina-income-val">
                        Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}
                    </div>
                    <div class="nina-growth-pill" style="padding: 3px 10px; font-size: 0.72rem;">
                        <i class="ph-bold ph-trend-up"></i>
                        <span>+100% Aktif</span>
                    </div>
                </div>
            </div>

            {{-- 4. RECENT ACTIVITY (RIWAYAT ORDERAN MASUK) --}}
            <div class="nina-section-box">
                <div class="nina-section-title">
                    <span>Recent Activity</span>
                    <a href="{{ route('creator.sales.report') }}" title="Lihat Semua Laporan">
                        <span>Lihat Semua</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>

                <div class="nina-activity-list">
                    @forelse($recentSales->take(5) as $order)
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
                                    <div class="nina-act-sub">{{ Str::limit($prodName, 26) }} · {{ $orderTime }}</div>
                                </div>
                            </div>
                            <div class="nina-act-right">
                                <div class="nina-act-amount">+Rp {{ number_format($orderTotal, 0, ',', '.') }}</div>
                                <span class="nina-act-status">Berhasil</span>
                            </div>
                        </a>
                    @empty
                        <div style="background:#f8fafc; border-radius:20px; padding:2rem 1.5rem; text-align:center; border:1px dashed #cbd5e1;">
                            <div style="width:48px; height:48px; border-radius:50%; background:#ffffff; color:#64748b; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; font-size:1.5rem; border:1px solid #e2e8f0;">
                                <i class="ph ph-receipt"></i>
                            </div>
                            <div style="font-weight:700; color:#0f172a; font-size:0.92rem;">Belum Ada Orderan Masuk</div>
                            <div style="font-size:0.78rem; color:#64748b; margin-top:3px; max-width:280px; margin:4px auto 0;">
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
