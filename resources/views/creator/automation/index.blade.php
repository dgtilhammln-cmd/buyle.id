@extends('creator.layout')

@section('title', 'Buyle Automation Reply · Auto DM & Instagram Engagement')
@section('page_title', 'Buyle Automation Reply')
@section('page_subtitle', 'Otomatisasi balasan komentar & DM Instagram untuk produk digital Anda.')

@section('styles')
<style>
    .auto-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
        width: 100%;
        font-family: 'Montserrat', sans-serif;
    }

    /* ── Hero Banner ───────────────────────────────────────────── */
    .auto-hero {
        background: linear-gradient(135deg, #1eb349 0%, #16a34a 45%, #0f766e 100%);
        border-radius: 20px;
        padding: 2rem 2.25rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 10px 30px rgba(30, 179, 73, 0.22);
    }

    .auto-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(225, 48, 108, 0.35) 0%, rgba(225, 48, 108, 0) 70%);
        pointer-events: none;
    }

    .auto-hero-header {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.25rem;
    }

    .auto-hero-title {
        font-size: 1.6rem;
        font-weight: 800;
        margin: 0 0 0.5rem;
        line-height: 1.25;
        letter-spacing: -0.01em;
    }

    .auto-hero-desc {
        font-size: 0.92rem;
        color: #f0fdf4;
        margin: 0;
        line-height: 1.6;
        max-width: 680px;
        font-weight: 500;
    }

    .auto-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
    }

    /* ── Metric Cards ──────────────────────────────────────────── */
    .metric-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
    }

    @media (max-width: 1024px) {
        .metric-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .metric-grid {
            grid-template-columns: 1fr;
        }
    }

    .metric-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1.5px solid #e2e8f0;
        padding: 1.35rem 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        gap: 1.1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    }

    .metric-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .metric-val {
        font-size: 1.45rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
    }

    .metric-label {
        font-size: 0.76rem;
        font-weight: 700;
        color: #64748b;
        margin: 0.2rem 0 0;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    /* ── Tab Bar Navigation ────────────────────────────────────── */
    .tab-bar-wrap {
        display: flex;
        align-items: center;
        background: #ffffff;
        padding: 6px;
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        gap: 6px;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
    }

    .tab-bar-wrap::-webkit-scrollbar {
        display: none;
    }

    .tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.75rem 1.35rem;
        border-radius: 12px;
        font-size: 0.84rem;
        font-weight: 700;
        color: #64748b;
        border: none;
        background: transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        font-family: 'Montserrat', sans-serif;
    }

    .tab-btn:hover {
        color: #1eb349;
        background: #f0fdf4;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #1eb349 0%, #16a34a 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.32);
    }

    /* ── Main Panel Cards ──────────────────────────────────────── */
    .panel-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        padding: 1.85rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .panel-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
        border-bottom: 1.5px solid #f1f5f9;
        padding-bottom: 1.25rem;
    }

    .panel-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .panel-subtitle {
        font-size: 0.82rem;
        color: #64748b;
        margin: 0.25rem 0 0;
        font-weight: 500;
    }

    /* ── Buttons ───────────────────────────────────────────────── */
    .btn-buyle-green {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        color: #ffffff !important;
        font-size: 0.85rem;
        font-weight: 800;
        padding: 0.75rem 1.5rem;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
        transition: all 0.2s ease;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-buyle-green:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
        filter: brightness(1.05);
    }

    .btn-ig-gradient {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        background: linear-gradient(135deg, #e1306c 0%, #f77737 100%);
        color: #ffffff !important;
        font-size: 0.85rem;
        font-weight: 800;
        padding: 0.75rem 1.5rem;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 4px 16px rgba(225, 48, 108, 0.35);
        transition: all 0.2s ease;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-ig-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 22px rgba(225, 48, 108, 0.45);
        filter: brightness(1.05);
    }

    .btn-outline-gray {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.25rem;
        border-radius: 999px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-outline-gray:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .btn-danger-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1.1rem;
        border-radius: 999px;
        border: 1.5px solid #fecaca;
        background: #fff;
        color: #dc2626;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-danger-outline:hover {
        background: #fef2f2;
        border-color: #f87171;
    }

    /* ── Form Inputs ───────────────────────────────────────────── */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #1e293b;
    }

    .form-desc {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: -0.15rem;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 0.8rem 1rem;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        font-size: 0.86rem;
        font-family: 'Montserrat', sans-serif;
        background: #f8fafc;
        color: #0f172a;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        background: #ffffff;
        border-color: #1eb349;
        outline: none;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
    }

    /* ── Account Status Widget ─────────────────────────────────── */
    .acc-widget {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.5rem;
        flex-wrap: wrap;
        gap: 1.25rem;
    }

    .acc-user-info {
        display: flex;
        align-items: center;
        gap: 1.1rem;
    }

    .acc-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        border: 3px solid #1eb349;
        object-fit: cover;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.25);
    }

    .acc-avatar-fallback {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e1306c, #f77737);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.4rem;
        box-shadow: 0 4px 14px rgba(225, 48, 108, 0.25);
    }

    .acc-handle {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .acc-meta {
        font-size: 0.8rem;
        color: #64748b;
        margin: 0.2rem 0 0;
        font-weight: 500;
    }

    /* ── Scope Chips ───────────────────────────────────────────── */
    .scopes-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-top: 0.6rem;
    }

    .scope-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 4px 10px;
        border-radius: 999px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        font-size: 0.72rem;
        font-weight: 700;
    }

    /* ── Table Styling ─────────────────────────────────────────── */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.84rem;
    }

    .custom-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-align: left;
        padding: 0.95rem 1.1rem;
        border-bottom: 1.5px solid #e2e8f0;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .custom-table td {
        padding: 1.1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        vertical-align: middle;
    }

    .custom-table tr:hover td {
        background: #f8fafc;
    }

    /* ── Spintax Variant Box ───────────────────────────────────── */
    .variant-card {
        background: #ffffff;
        border: 1.5px dashed #cbd5e1;
        border-radius: 14px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        position: relative;
    }

    .variable-tag {
        display: inline-block;
        background: #e0f2fe;
        color: #0369a1;
        padding: 2px 7px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        cursor: pointer;
        margin: 2px;
        transition: background 0.15s;
    }

    .variable-tag:hover {
        background: #bae6fd;
    }

    /* ── Slider ────────────────────────────────────────────────── */
    .slider-range {
        accent-color: #1eb349;
        width: 100%;
        height: 6px;
        border-radius: 4px;
        background: #e2e8f0;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="auto-wrapper">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div style="background:#f0fdf4; border:1.5px solid #86efac; border-radius:14px; padding:1rem 1.25rem; color:#15803d; font-size:0.86rem; font-weight:700; display:flex; align-items:center; gap:0.6rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#fef2f2; border:1.5px solid #fca5a5; border-radius:14px; padding:1rem 1.25rem; color:#b91c1c; font-size:0.86rem; font-weight:700; display:flex; align-items:center; gap:0.6rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Hero Intro Banner --}}
    <div class="auto-hero">
        <div class="auto-hero-header">
            <div>
                <div class="auto-badge-pill">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    Meta Graph API v19.0 Integration
                </div>
                <h1 class="auto-hero-title">Buyle Automation Reply Engine</h1>
                <p class="auto-hero-desc">
                    Ubah komentar di Postingan & Reels Instagram menjadi transaksi instan. Kirim Auto DM cerdas dengan variasi Spintax dan link checkout produk digital Buyle.id Anda.
                </p>
            </div>
            <div>
                @if($account && $account->is_connected)
                    <div style="display:flex; align-items:center; gap:0.75rem; background:rgba(255,255,255,0.18); padding:0.6rem 1.2rem; border-radius:999px; border:1px solid rgba(255,255,255,0.3);">
                        <span style="width:10px; height:10px; background:#4ade80; border-radius:50%; box-shadow:0 0 8px #4ade80;"></span>
                        <span style="font-size:0.82rem; font-weight:700;">@ {{ $account->username }} Terhubung</span>
                    </div>
                @else
                    <button type="button" onclick="openInstagramOAuth('{{ route('creator.automation-reply.connect') }}')" class="btn-ig-gradient">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                        Hubungkan Instagram Professional
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Metric Overview Cards --}}
    <div class="metric-grid">
        <div class="metric-card">
            <div class="metric-icon-box" style="background:#f0fdf4; color:#16a34a;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            </div>
            <div>
                <h3 class="metric-val">{{ number_format($totalComments) }}</h3>
                <p class="metric-label">Komentar Terdeteksi</p>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon-box" style="background:#fdf2f8; color:#db2777;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </div>
            <div>
                <h3 class="metric-val">{{ number_format($totalDms) }}</h3>
                <p class="metric-label">Auto DM Terkirim</p>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon-box" style="background:#eff6ff; color:#2563eb;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <h3 class="metric-val">{{ $overallCtr }}%</h3>
                <p class="metric-label">Link CTR (Klik DM)</p>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon-box" style="background:#fefce8; color:#ca8a04;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div>
                <h3 class="metric-val">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p class="metric-label">Direct Revenue</p>
            </div>
        </div>
    </div>

    {{-- Tab Bar Navigation --}}
    @php
        $activeTab = request()->query('tab', 'campaigns');
    @endphp
    <div class="tab-bar-wrap">
        <a href="?tab=campaigns" class="tab-btn {{ $activeTab === 'campaigns' ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            Campaign Saya ({{ $campaigns->count() }})
        </a>
        <a href="?tab=create" class="tab-btn {{ $activeTab === 'create' ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Buat Campaign Baru
        </a>
        <a href="?tab=account" class="tab-btn {{ $activeTab === 'account' ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
            Koneksi Akun Instagram
        </a>
        <a href="?tab=logs" class="tab-btn {{ $activeTab === 'logs' ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            Live Activity Log
        </a>
        <a href="?tab=simulation" class="tab-btn {{ $activeTab === 'simulation' ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Simulasi &amp; Live Tester
        </a>
    </div>

    {{-- TAB 1: DAFTAR CAMPAIGN --}}
    @if($activeTab === 'campaigns')
    <div class="panel-card">
        <div class="panel-card-header">
            <div>
                <h2 class="panel-title">
                    <svg width="22" height="22" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    Daftar Campaign Aktif
                </h2>
                <p class="panel-subtitle">Kelola seluruh campaign otomasi komentar dan auto DM produk Buyle.id Anda.</p>
            </div>
            <div>
                <a href="?tab=create" class="btn-buyle-green">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Campaign
                </a>
            </div>
        </div>

        @if($campaigns->isEmpty())
            <div style="text-align:center; padding:3.5rem 1.5rem; background:#f8fafc; border-radius:16px; border:1.5px dashed #cbd5e1;">
                <div style="width:64px; height:64px; background:#f0fdf4; color:#16a34a; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:1rem;">
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <h3 style="font-size:1.15rem; font-weight:800; color:#0f172a; margin:0 0 0.4rem;">Belum Ada Campaign Dibuat</h3>
                <p style="font-size:0.85rem; color:#64748b; max-width:480px; margin:0 auto 1.5rem;">
                    Tingkatkan omset penjualan produk Anda secara eksponensial. Buat campaign pertama untuk merespon komentar followers di Instagram secara otomatis.
                </p>
                <a href="?tab=create" class="btn-buyle-green">Mulai Buat Campaign</a>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Nama Campaign</th>
                            <th>Target Post</th>
                            <th>Trigger Keyword</th>
                            <th>Produk Digital</th>
                            <th>Statistik (Komen / DM / Klik)</th>
                            <th>Omset (Rp)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $camp)
                        <tr>
                            <td>
                                <form action="{{ route('creator.automation-reply.campaigns.toggle', $camp->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="background:none; border:none; cursor:pointer;" title="Klik untuk toggle status">
                                        @if($camp->is_active)
                                            <span style="display:inline-flex; align-items:center; gap:0.35rem; padding:4px 10px; border-radius:999px; background:#f0fdf4; color:#16a34a; font-size:0.74rem; font-weight:800; border:1px solid #bbf7d0;">
                                                <span style="width:7px; height:7px; background:#16a34a; border-radius:50%;"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span style="display:inline-flex; align-items:center; gap:0.35rem; padding:4px 10px; border-radius:999px; background:#f1f5f9; color:#64748b; font-size:0.74rem; font-weight:800; border:1px solid #cbd5e1;">
                                                <span style="width:7px; height:7px; background:#94a3b8; border-radius:50%;"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>
                                <strong style="font-size:0.92rem; color:#0f172a;">{{ $camp->name }}</strong>
                                <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">
                                    Delay: {{ $camp->delay_seconds }} detik • Spintax: {{ count($camp->spintax_variants ?? []) }} varian
                                </div>
                            </td>
                            <td>
                                @if($camp->target_type === 'all')
                                    <span style="display:inline-block; padding:3px 9px; border-radius:6px; background:#eff6ff; color:#1d4ed8; font-size:0.72rem; font-weight:700;">Semua Post &amp; Reels</span>
                                @else
                                    <span style="display:inline-block; padding:3px 9px; border-radius:6px; background:#fdf2f8; color:#be185d; font-size:0.72rem; font-weight:700;">Post Spesifik</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; flex-wrap:wrap; gap:3px;">
                                    @if($camp->trigger_type === 'any')
                                        <span style="font-size:0.72rem; color:#64748b; font-style:italic;">Semua Komentar</span>
                                    @else
                                        @foreach($camp->trigger_keywords ?? [] as $kw)
                                            <span style="background:#f1f5f9; color:#334155; padding:2px 7px; border-radius:6px; font-size:0.72rem; font-weight:700;">"{{ $kw }}"</span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($camp->product)
                                    <span style="font-weight:700; color:#0f172a;">{{ $camp->product->name }}</span>
                                    <div style="font-size:0.74rem; color:#16a34a; font-weight:700;">
                                        Rp {{ number_format($camp->product->price, 0, ',', '.') }}
                                    </div>
                                @else
                                    <span style="color:#94a3b8; font-size:0.8rem;">(Tanpa Produk)</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:0.82rem; font-weight:700; color:#0f172a;">
                                    {{ $camp->total_comments_detected }} <span style="font-weight:500; color:#64748b;">komen</span> / 
                                    {{ $camp->total_dms_sent }} <span style="font-weight:500; color:#64748b;">DM</span> / 
                                    {{ $camp->total_clicks }} <span style="font-weight:500; color:#64748b;">klik</span>
                                </div>
                                <div style="font-size:0.74rem; color:#2563eb; font-weight:700;">
                                    CTR: {{ $camp->ctr }}%
                                </div>
                            </td>
                            <td>
                                <strong style="color:#16a34a; font-size:0.9rem;">
                                    Rp {{ number_format($camp->total_revenue, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:0.4rem;">
                                    <form action="{{ route('creator.automation-reply.campaigns.destroy', $camp->id) }}" method="POST" onsubmit="return confirm('Hapus campaign ini? Data riwayat klik akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-outline" title="Hapus Campaign">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @endif

    {{-- TAB 2: BUAT CAMPAIGN BARU --}}
    @if($activeTab === 'create')
    <div class="panel-card">
        <div class="panel-card-header">
            <div>
                <h2 class="panel-title">
                    <svg width="22" height="22" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Campaign Builder
                </h2>
                <p class="panel-subtitle">Konfigurasi alur auto-reply komentar &amp; Auto DM penawaran produk digital Anda.</p>
            </div>
        </div>

        <form action="{{ route('creator.automation-reply.campaigns.store') }}" method="POST">
            @csrf

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">
                <div class="form-group">
                    <label class="form-label">Nama Campaign *</label>
                    <input type="text" name="name" class="form-input" placeholder="Contoh: Promo Ebook E-Commerce Reels" required>
                    <span class="form-desc">Identifikasi internal untuk tracking omset penjualan Anda.</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Produk Digital Buyle.id (Smart Checkout Link)</label>
                    <select name="product_id" id="campaignProductSelect" class="form-select">
                        <option value="">-- Pilih Produk Digital (Opsional) --</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">
                                {{ $prod->name }} — Rp {{ number_format($prod->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <span class="form-desc">Sistem otomatis men-generate shortlink checkout instan ke variabel <code>{link_product}</code>.</span>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-top:0.5rem;">
                <div class="form-group">
                    <label class="form-label">Target Postingan Instagram *</label>
                    <select name="target_type" id="targetTypeSelect" class="form-select" onchange="toggleSpecificMedia(this.value)">
                        <option value="all">Semua Postingan &amp; Reels Instagram</option>
                        <option value="specific">Postingan / Reels Tertentu Saja</option>
                    </select>
                </div>

                <div class="form-group" id="mediaIdWrap" style="display:none;">
                    <label class="form-label">Instagram Media ID</label>
                    <input type="text" name="target_media_id" class="form-input" placeholder="Contoh: 18029384819284712">
                    <span class="form-desc">Dapatkan Media ID dari Graph API atau URL postingan Anda.</span>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 2fr; gap:1.5rem; margin-top:0.5rem;">
                <div class="form-group">
                    <label class="form-label">Tipe Pencocokan Kata Kunci *</label>
                    <select name="trigger_type" class="form-select">
                        <option value="contains">Contains (Komentar mengandung kata kunci)</option>
                        <option value="exact">Exact Match (Hanya kata kunci persis)</option>
                        <option value="any">Any Comment (Semua komentar yang masuk)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Trigger Keywords (Pisahkan dengan tanda koma)</label>
                    <input type="text" name="trigger_keywords" class="form-input" placeholder="Contoh: mau, info, link, harga, spill, ebook, beli" value="mau, info, link, harga">
                    <span class="form-desc">Pemicu bot untuk mengirimkan Auto DM saat kata kunci tersebut diketik oleh followers.</span>
                </div>
            </div>

            {{-- SPINTAX MULTI-VARIANT SECTION --}}
            <div style="background:#f8fafc; border-radius:16px; border:1.5px solid #e2e8f0; padding:1.5rem; margin:1.5rem 0;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:0.5rem;">
                    <div>
                        <h4 style="font-size:0.95rem; font-weight:800; color:#0f172a; margin:0;">
                            ✨ Multi-Response Variant System (Spintax Engine)
                        </h4>
                        <p style="font-size:0.78rem; color:#64748b; margin:2px 0 0;">
                            Rotasi minimal 3 variasi pesan DM secara acak agar akun Anda terhindar dari deteksi spam Instagram.
                        </p>
                    </div>
                    <div>
                        <span style="font-size:0.75rem; color:#475569; font-weight:700;">Variabel Tersedia:</span>
                        <span class="variable-tag">{first_name}</span>
                        <span class="variable-tag">{username}</span>
                        <span class="variable-tag">{link_product}</span>
                        <span class="variable-tag">{creator_name}</span>
                    </div>
                </div>

                <div id="spintaxVariantsContainer">
                    <div class="variant-card">
                        <label style="font-size:0.78rem; font-weight:800; color:#1e293b; margin-bottom:4px; display:block;">Variasi DM #1 (Default)</label>
                        <textarea name="spintax_variants[]" rows="2" class="form-textarea" placeholder="Tulis template pesan...">Halo kak {first_name}! Link akses produk digital sudah kami kirimkan ya: {link_product}. Selamat belajar dan sukses selalu!</textarea>
                    </div>

                    <div class="variant-card">
                        <label style="font-size:0.78rem; font-weight:800; color:#1e293b; margin-bottom:4px; display:block;">Variasi DM #2</label>
                        <textarea name="spintax_variants[]" rows="2" class="form-textarea" placeholder="Tulis template pesan...">Hai @{first_name}! Terima kasih sudah mampir di postingan kami. Kamu bisa langsung checkout di sini kak: {link_product} 🚀</textarea>
                    </div>

                    <div class="variant-card">
                        <label style="font-size:0.78rem; font-weight:800; color:#1e293b; margin-bottom:4px; display:block;">Variasi DM #3</label>
                        <textarea name="spintax_variants[]" rows="2" class="form-textarea" placeholder="Tulis template pesan...">Spesial buat kamu kak {first_name}! Silakan klik link berikut untuk mendapatkan akses langsung: {link_product} ✨</textarea>
                    </div>
                </div>
            </div>

            {{-- PUBLIC COMMENT REPLY & SAFETY GUARDS --}}
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">
                <div style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:16px; padding:1.25rem;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                        <label class="form-label" style="margin:0;">Balas Komentar Publik Otomatis</label>
                        <input type="checkbox" name="auto_comment_enabled" value="1" id="publicReplyToggle" onchange="togglePublicReply(this.checked)" style="width:18px; height:18px; accent-color:#1eb349; cursor:pointer;" checked>
                    </div>
                    <span class="form-desc" style="display:block; margin-bottom:0.75rem;">
                        Bot akan otomatis membalas komentar publik di postingan memberitahu bahwa link sudah dikirim ke DM.
                    </span>
                    <div id="publicReplyBox">
                        <textarea name="auto_comment_variants[]" rows="2" class="form-textarea" placeholder="Contoh: Cek DM ya kak! Link produk sudah meluncur 📩">Cek DM ya kak! Link produk sudah meluncur ke inbox kamu 📩✨</textarea>
                    </div>
                </div>

                <div style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:16px; padding:1.25rem;">
                    <label class="form-label">🛡️ Anti-Spam Safety Layer &amp; Random Delay</label>
                    <span class="form-desc" style="display:block; margin-bottom:1rem;">
                        Jeda waktu acak antara penerimaan komentar dan pengiriman DM agar natural menyerupai interaksi manusia.
                    </span>

                    <div style="display:flex; justify-content:space-between; font-size:0.8rem; font-weight:700; color:#0f172a; margin-bottom:0.4rem;">
                        <span>Random Delay Slider:</span>
                        <span id="delayDisplay" style="color:#1eb349; font-weight:800;">5 Detik</span>
                    </div>
                    <input type="range" name="delay_seconds" min="3" max="8" value="5" class="slider-range" oninput="document.getElementById('delayDisplay').innerText = this.value + ' Detik'">

                    <div style="margin-top:1.25rem;">
                        <label class="form-label">Daily Limit Guard (Max DM / Hari)</label>
                        <input type="number" name="daily_limit" class="form-input" value="200" min="20" max="1000" style="padding:0.6rem 0.85rem;">
                        <span class="form-desc">Default aman: 200 DM/hari per akun Instagram.</span>
                    </div>
                </div>
            </div>

            <div style="margin-top:2rem; display:flex; justify-content:flex-end; gap:0.75rem;">
                <a href="?tab=campaigns" class="btn-outline-gray">Batal</a>
                <button type="submit" class="btn-buyle-green">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    Simpan &amp; Aktifkan Campaign
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- TAB 3: KONEKSI AKUN INSTAGRAM --}}
    @if($activeTab === 'account')
    <div class="panel-card">
        <div class="panel-card-header">
            <div>
                <h2 class="panel-title">
                    <svg width="22" height="22" fill="none" stroke="#e1306c" stroke-width="2.2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    Manajemen Koneksi Instagram Professional
                </h2>
                <p class="panel-subtitle">Hubungkan akun Instagram Creator atau Bisnis Anda dengan otentikasi resmi Meta OAuth 2.0.</p>
            </div>
        </div>

        @if($account && $account->is_connected)
            <div class="acc-widget">
                <div class="acc-user-info">
                    @if($account->profile_picture_url)
                        <img src="{{ $account->profile_picture_url }}" alt="{{ $account->username }}" class="acc-avatar">
                    @else
                        <div class="acc-avatar-fallback">{{ strtoupper(substr($account->username ?: 'IG', 0, 2)) }}</div>
                    @endif
                    <div>
                        <h3 class="acc-handle">
                            @ {{ $account->username }}
                            <span style="display:inline-flex; align-items:center; gap:0.3rem; background:#f0fdf4; color:#16a34a; font-size:0.7rem; padding:3px 9px; border-radius:999px; border:1px solid #bbf7d0;">
                                <span style="width:6px; height:6px; background:#16a34a; border-radius:50%;"></span> Terhubung
                            </span>
                        </h3>
                        <p class="acc-meta">
                            Tipe: <strong>{{ $account->account_type }}</strong> • ID Halaman: <code>{{ $account->page_id ?? '-' }}</code>
                        </p>
                        <p class="acc-meta">
                            Masa Berlaku Token: <strong>{{ $account->daysUntilTokenExpires() }} hari lagi</strong> 
                            ({{ $account->token_expires_at ? $account->token_expires_at->format('d M Y') : '60 Hari' }})
                        </p>
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
                    <form action="{{ route('creator.automation-reply.refresh-token') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-outline-gray" title="Perpanjang 60 hari">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                            Refresh Token
                        </button>
                    </form>

                    <form action="{{ route('creator.automation-reply.disconnect') }}" method="POST" onsubmit="return confirm('Yakin ingin memutuskan koneksi akun Instagram ini?')">
                        @csrf
                        <button type="submit" class="btn-danger-outline">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            Putuskan Koneksi
                        </button>
                    </form>
                </div>
            </div>

            {{-- SCOPES VERIFIED --}}
            <div style="margin-top:1.5rem; background:#ffffff; border:1.5px solid #e2e8f0; border-radius:16px; padding:1.25rem;">
                <h4 style="font-size:0.86rem; font-weight:800; color:#0f172a; margin:0 0 0.5rem;">5 Izin Resmi Meta Graph API Terverifikasi:</h4>
                <div class="scopes-wrap">
                    <span class="scope-chip">✓ instagram_basic</span>
                    <span class="scope-chip">✓ instagram_manage_messages (Auto DM)</span>
                    <span class="scope-chip">✓ instagram_manage_comments (Auto Public Reply)</span>
                    <span class="scope-chip">✓ instagram_manage_insights (Conversion Tracking)</span>
                    <span class="scope-chip">✓ instagram_content_publish (Auto Schedule)</span>
                </div>
            </div>
        @else
            <div style="text-align:center; padding:3.5rem 1.5rem; background:#f8fafc; border-radius:18px; border:1.5px dashed #cbd5e1;">
                <div style="width:68px; height:68px; background:linear-gradient(135deg, rgba(225,48,108,0.12), rgba(247,119,55,0.12)); color:#e1306c; border-radius:20px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:1.25rem;">
                    <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </div>
                <h3 style="font-size:1.25rem; font-weight:800; color:#0f172a; margin:0 0 0.5rem;">Hubungkan Instagram Anda Sekarang</h3>
                <p style="font-size:0.88rem; color:#64748b; max-width:540px; margin:0 auto 1.75rem; line-height:1.6;">
                    Gunakan akun Instagram Professional (Creator atau Bisnis) yang sudah ditautkan ke Facebook Page agar fitur Auto DM dan Auto Public Reply dapat berjalan secara otomatis.
                </p>
                <button type="button" onclick="openInstagramOAuth('{{ route('creator.automation-reply.connect') }}')" class="btn-ig-gradient" style="padding:0.95rem 2rem; font-size:0.92rem;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    Hubungkan Akun Instagram Professional
                </button>
            </div>
        @endif

        {{-- WEBHOOK URL INFO BOX --}}
        <div style="margin-top:1.5rem; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:16px; padding:1.25rem;">
            <div style="display:flex; align-items:center; gap:0.6rem; color:#166534; font-weight:800; font-size:0.86rem; margin-bottom:0.4rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Endpoint Webhook Meta Developers:
            </div>
            <p style="font-size:0.8rem; color:#15803d; margin:0 0 0.5rem;">
                Endpoint ini digunakan untuk menerima event komentar real-time dari Meta:
            </p>
            <code style="display:block; background:#ffffff; border:1px solid #bbf7d0; padding:0.6rem 0.9rem; border-radius:10px; font-size:0.82rem; color:#0f172a; word-break:break-all;">
                {{ url('/api/webhooks/instagram') }}
            </code>
        </div>
    </div>
    @endif

    {{-- TAB 4: LIVE ACTIVITY LOGS --}}
    @if($activeTab === 'logs')
    <div class="panel-card">
        <div class="panel-card-header">
            <div>
                <h2 class="panel-title">
                    <svg width="22" height="22" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Live Activity &amp; Audit Logs
                </h2>
                <p class="panel-subtitle">Rekam jejak real-time pengiriman Auto DM dan balasan komentar.</p>
            </div>
        </div>

        @if($logs->isEmpty())
            <div style="text-align:center; padding:3rem 1.5rem; background:#f8fafc; border-radius:16px;">
                <p style="color:#64748b; font-size:0.88rem; margin:0;">Belum ada aktivitas interaksi komentar yang tercatat.</p>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengirim Instagram</th>
                            <th>Komentar Masuk</th>
                            <th>Pesan DM Terkirim</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td style="white-space:nowrap; font-size:0.78rem; color:#64748b;">
                                {{ $log->created_at->format('d M Y, H:i') }}
                            </td>
                            <td>
                                <strong style="color:#0f172a;">{{ $log->sender_username ? '@' . $log->sender_username : 'User IG' }}</strong>
                            </td>
                            <td style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                "{{ $log->comment_text }}"
                            </td>
                            <td style="max-width:280px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:0.8rem; color:#334155;">
                                {{ $log->dm_text_sent ?: '-' }}
                            </td>
                            <td>
                                @if($log->status === 'success')
                                    <span style="display:inline-flex; align-items:center; gap:0.3rem; padding:3px 9px; border-radius:999px; background:#f0fdf4; color:#16a34a; font-size:0.72rem; font-weight:800;">
                                        ✓ Terkirim
                                    </span>
                                @elseif($log->status === 'rate_limited')
                                    <span style="display:inline-flex; align-items:center; gap:0.3rem; padding:3px 9px; border-radius:999px; background:#fefce8; color:#ca8a04; font-size:0.72rem; font-weight:800;">
                                        ⚠ Rate Limited
                                    </span>
                                @else
                                    <span style="display:inline-flex; align-items:center; gap:0.3rem; padding:3px 9px; border-radius:999px; background:#fef2f2; color:#dc2626; font-size:0.72rem; font-weight:800;">
                                        ✗ Gagal
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top:1.25rem;">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
    @endif

    {{-- TAB 5: SIMULASI & LIVE TESTER --}}
    @if($activeTab === 'simulation')
    <div class="panel-card">
        <div class="panel-card-header">
            <div>
                <h2 class="panel-title">
                    <svg width="22" height="22" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Simulasi &amp; Live Sandbox Tester
                </h2>
                <p class="panel-subtitle">Uji coba hasil rotasi Spintax dan generator link checkout sebelum dipublikasikan.</p>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.75rem;">
            <div>
                <div class="form-group">
                    <label class="form-label">Pilih Produk Digital untuk Uji Coba</label>
                    <select id="simProduct" class="form-select">
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->name }} (Rp {{ number_format($prod->price, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Template Pesan Spintax</label>
                    <textarea id="simTemplate" rows="4" class="form-textarea">{Halo|Hai|Hello} kak {first_name}! Link akses produk digital eksklusif: {link_product}. Selamat belajar ya kak!</textarea>
                    <span class="form-desc">Coba gunakan sintaks <code>{opsi1|opsi2|opsi3}</code> untuk variasi acak.</span>
                </div>

                <button type="button" class="btn-buyle-green" onclick="runSimulation()" style="width:100%; margin-top:0.5rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    Generate Preview Pesan DM
                </button>
            </div>

            <div>
                <label class="form-label">Hasil Simulasi Tampilan di Inbox Instagram:</label>
                <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:18px; padding:1.5rem; min-height:220px; display:flex; flex-direction:column; justify-content:center;">
                    <div style="background:#ffffff; border:1px solid #cbd5e1; border-radius:16px 16px 16px 4px; padding:1rem 1.25rem; box-shadow:0 2px 8px rgba(0,0,0,0.04); max-width:85%;">
                        <div style="font-size:0.75rem; font-weight:800; color:#e1306c; margin-bottom:0.35rem;">
                            @ {{ $account->username ?? 'buyle.creator' }}
                        </div>
                        <div id="simPreviewBox" style="font-size:0.86rem; color:#0f172a; line-height:1.5; white-space:pre-wrap;">
                            Klik tombol "Generate Preview Pesan DM" untuk melihat simulasi hasil pesan yang akan diterima pembeli.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    function openInstagramOAuth(connectUrl) {
        const w = 620;
        const h = 760;
        const left = Math.round((window.screen.width - w) / 2);
        const top = Math.round((window.screen.height - h) / 2);

        // Fetch URL OAuth dari server via AJAX (supaya dashboard tidak ter-redirect)
        fetch(connectUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Gagal mengambil URL OAuth');
            return res.json();
        })
        .then(data => {
            if (!data.url) throw new Error('URL OAuth tidak tersedia');
            window.open(
                data.url,
                'InstagramOAuth',
                `width=${w},height=${h},top=${top},left=${left},scrollbars=yes,status=no,toolbar=no,menubar=no`
            );
        })
        .catch(err => {
            alert('Gagal membuka koneksi Instagram: ' + err.message);
        });
    }

    function toggleSpecificMedia(val) {
        const wrap = document.getElementById('mediaIdWrap');
        if (wrap) {
            wrap.style.display = (val === 'specific') ? 'flex' : 'none';
        }
    }

    function togglePublicReply(checked) {
        const box = document.getElementById('publicReplyBox');
        if (box) {
            box.style.display = checked ? 'block' : 'none';
        }
    }

    function runSimulation() {
        const template = document.getElementById('simTemplate').value;
        const productId = document.getElementById('simProduct').value;
        const previewBox = document.getElementById('simPreviewBox');

        previewBox.innerHTML = '<span style="color:#64748b;">Memproses preview Spintax...</span>';

        fetch("{{ route('creator.automation-reply.simulation') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                template: template,
                product_id: productId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                previewBox.innerText = data.preview;
            }
        })
        .catch(err => {
            previewBox.innerText = 'Gagal memproses simulasi.';
        });
    }
</script>
@endsection
