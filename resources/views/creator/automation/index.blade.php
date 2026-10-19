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

    {{-- Hero Intro Banner --}}
    <div class="auto-hero">
        <div class="auto-hero-header">
            <div>
                <div class="auto-badge-pill">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    Meta Graph API v26.0 Integration
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
                            <th>Produk / Link Tujuan</th>
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
                                @elseif(!empty($camp->custom_link))
                                    <div style="display:inline-flex; align-items:center; gap:0.25rem; font-weight:700; color:#2563eb; font-size:0.78rem;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                        Link Eksternal
                                    </div>
                                    <div style="font-size:0.7rem; color:#64748b; max-width:140px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        <a href="{{ $camp->custom_link }}" target="_blank" style="color:#64748b; text-decoration:none;">{{ $camp->custom_link }}</a>
                                    </div>
                                @else
                                    <span style="color:#94a3b8; font-size:0.8rem;">(Tanpa Link)</span>
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
                                    <button type="button" onclick="openEditModal({{ json_encode($camp) }})" class="btn-outline-gray" style="padding:0.4rem 0.65rem; font-size:0.75rem;" title="Edit Campaign">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit
                                    </button>
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

    {{-- TAB 2: BUAT CAMPAIGN BARU (COMPACT DESIGN) --}}
    @if($activeTab === 'create')
    <div class="panel-card" style="padding:1.25rem 1.5rem;">
        <div class="panel-card-header" style="margin-bottom:1.15rem; padding-bottom:0.75rem;">
            <div>
                <h2 class="panel-title" style="font-size:1.1rem;">
                    <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Campaign Builder
                </h2>
                <p class="panel-subtitle" style="font-size:0.78rem;">Konfigurasi alur balasan komentar &amp; Auto DM instan.</p>
            </div>
        </div>

        <form action="{{ route('creator.automation-reply.campaigns.store') }}" method="POST">
            @csrf

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.85rem 1.25rem;">
                <div class="form-group" style="margin-bottom:0.85rem;">
                    <label class="form-label" style="font-size:0.8rem;">Nama Campaign *</label>
                    <input type="text" name="name" class="form-input" style="padding:0.65rem 0.85rem; font-size:0.82rem;" placeholder="Contoh: Promo Ebook Reels" required>
                    <span class="form-desc" style="font-size:0.72rem;">Identifikasi internal untuk tracking performa.</span>
                </div>

                <div class="form-group" style="margin-bottom:0.85rem;">
                    <label class="form-label" style="font-size:0.8rem;">Tujuan Link di DM (Pilih Salah Satu)</label>
                    <div style="display:flex; gap:0.4rem; margin-bottom:0.4rem;">
                        <button type="button" id="btnLinkTypeProduct" onclick="setLinkType('product')" style="flex:1; padding:0.4rem 0.6rem; font-size:0.74rem; font-weight:700; border-radius:8px; border:1.5px solid #1eb349; background:#f0fdf4; color:#16a34a; cursor:pointer; font-family:'Montserrat',sans-serif;">Produk Digital Buyle</button>
                        <button type="button" id="btnLinkTypeCustom" onclick="setLinkType('custom')" style="flex:1; padding:0.4rem 0.6rem; font-size:0.74rem; font-weight:700; border-radius:8px; border:1.5px solid #e2e8f0; background:#ffffff; color:#64748b; cursor:pointer; font-family:'Montserrat',sans-serif;">Link Eksternal / Custom</button>
                    </div>

                    <div id="wrapProductSelect">
                        <select name="product_id" id="campaignProductSelect" class="form-select" style="padding:0.65rem 0.85rem; font-size:0.82rem;">
                            <option value="">-- Pilih Produk Digital (Opsional) --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">
                                    {{ $prod->name }} — Rp {{ number_format($prod->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="wrapCustomLink" style="display:none;">
                        <input type="url" name="custom_link" id="campaignCustomLinkInput" class="form-input" style="padding:0.65rem 0.85rem; font-size:0.82rem;" placeholder="https://wa.me/... atau https://landingpage.com">
                    </div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.85rem 1.25rem;">
                <div class="form-group" style="margin-bottom:0.85rem;">
                    <label class="form-label" style="font-size:0.8rem;">Target Postingan Instagram *</label>
                    <select name="target_type" id="targetTypeSelect" class="form-select" style="padding:0.65rem 0.85rem; font-size:0.82rem;" onchange="toggleSpecificMedia(this.value)">
                        <option value="all" selected>Semua Postingan &amp; Reels Instagram (Rekomendasi)</option>
                        <option value="specific">Postingan / Reels Tertentu Saja</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:0.85rem;">
                    <label class="form-label" style="font-size:0.8rem;">Trigger Kata Kunci (Koma pisahkan)</label>
                    <input type="text" name="trigger_keywords" class="form-input" style="padding:0.65rem 0.85rem; font-size:0.82rem;" placeholder="Contoh: mau, info, link, harga, beli" value="mau, info, link, harga">
                </div>
            </div>

            <div class="form-group" id="mediaIdWrap" style="display:none; margin-bottom:0.85rem;">
                <label class="form-label" style="font-size:0.8rem;">Link Reels / Postingan Instagram</label>
                <input type="text" name="target_media_id" class="form-input" style="padding:0.65rem 0.85rem; font-size:0.82rem;" placeholder="Contoh: https://www.instagram.com/reel/C8XYZ123/ atau Media ID">
                <span class="form-desc" style="font-size:0.72rem;">Tempel tautan Reels dari aplikasi Instagram Anda.</span>
            </div>

            {{-- SPINTAX MULTI-VARIANT SECTION (COMPACT) --}}
            <div style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; padding:1rem 1.15rem; margin:0.85rem 0;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem; flex-wrap:wrap; gap:0.4rem;">
                    <div>
                        <h4 style="font-size:0.86rem; font-weight:800; color:#0f172a; margin:0;">
                            ✨ Variasi Pesan DM (Spintax Engine)
                        </h4>
                        <p style="font-size:0.73rem; color:#64748b; margin:1px 0 0;">
                            Rotasi acak pesan DM agar aman dari filter spam Instagram.
                        </p>
                    </div>
                    <div style="font-size:0.72rem;">
                        <span style="color:#475569; font-weight:700;">Tag:</span>
                        <span class="variable-tag">{first_name}</span>
                        <span class="variable-tag">{link_product}</span>
                    </div>
                </div>

                <div id="spintaxVariantsContainer" style="display:flex; flex-direction:column; gap:0.5rem;">
                    <div class="variant-card" style="padding:0.65rem 0.85rem; margin:0;">
                        <label style="font-size:0.72rem; font-weight:800; color:#1e293b; margin-bottom:2px; display:block;">Variasi DM #1 (Default)</label>
                        <textarea name="spintax_variants[]" rows="2" class="form-textarea" style="padding:0.5rem 0.75rem; font-size:0.8rem;" placeholder="Tulis template pesan...">Halo kak {first_name}! Link aksesnya sudah kami kirimkan ya: {link_product}. Selamat mencoba!</textarea>
                    </div>

                    <div class="variant-card" style="padding:0.65rem 0.85rem; margin:0;">
                        <label style="font-size:0.72rem; font-weight:800; color:#1e293b; margin-bottom:2px; display:block;">Variasi DM #2</label>
                        <textarea name="spintax_variants[]" rows="2" class="form-textarea" style="padding:0.5rem 0.75rem; font-size:0.8rem;" placeholder="Tulis template pesan...">Hai kak {first_name}! Terima kasih sudah mampir. Kamu bisa langsung cek tautannya di sini ya: {link_product} 🚀</textarea>
                    </div>

                    <div class="variant-card" style="padding:0.65rem 0.85rem; margin:0;">
                        <label style="font-size:0.72rem; font-weight:800; color:#1e293b; margin-bottom:2px; display:block;">Variasi DM #3</label>
                        <textarea name="spintax_variants[]" rows="2" class="form-textarea" style="padding:0.5rem 0.75rem; font-size:0.8rem;" placeholder="Tulis template pesan...">Spesial buat kamu kak {first_name}! Klik link berikut untuk mendapatkan akses langsung: {link_product} ✨</textarea>
                    </div>
                </div>
            </div>

            {{-- PUBLIC COMMENT REPLY & SAFETY GUARDS --}}
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.85rem 1.25rem;">
                <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:0.95rem;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                        <label class="form-label" style="margin:0; font-size:0.8rem;">Balas Komentar Publik Otomatis</label>
                        <input type="checkbox" name="auto_comment_enabled" value="1" id="publicReplyToggle" onchange="togglePublicReply(this.checked)" style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer;" checked>
                    </div>
                    <div id="publicReplyBox">
                        <textarea name="auto_comment_variants[]" rows="2" class="form-textarea" style="padding:0.5rem 0.75rem; font-size:0.8rem;" placeholder="Contoh: Cek DM ya kak! Link produk sudah meluncur 📩">Cek DM ya kak! Linknya sudah meluncur ke inbox kamu 📩✨</textarea>
                    </div>
                </div>

                <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:0.95rem;">
                    <div style="display:flex; justify-content:space-between; font-size:0.78rem; font-weight:700; color:#0f172a; margin-bottom:0.35rem;">
                        <span>Delay Pengiriman DM:</span>
                        <span id="delayDisplay" style="color:#1eb349; font-weight:800;">5 Detik</span>
                    </div>
                    <input type="range" name="delay_seconds" min="3" max="8" value="5" class="slider-range" oninput="document.getElementById('delayDisplay').innerText = this.value + ' Detik'">

                    <div style="margin-top:0.75rem; display:flex; align-items:center; justify-content:space-between; gap:0.75rem;">
                        <label class="form-label" style="margin:0; font-size:0.78rem;">Batas Max DM/Hari:</label>
                        <input type="number" name="daily_limit" class="form-input" value="200" min="20" max="1000" style="width:110px; padding:0.4rem 0.6rem; font-size:0.8rem;">
                    </div>
                </div>
            </div>

            <div style="margin-top:1.25rem; display:flex; justify-content:flex-end; gap:0.75rem;">
                <a href="?tab=campaigns" class="btn-outline-gray" style="padding:0.55rem 1.15rem; font-size:0.82rem;">Batal</a>
                <button type="submit" class="btn-buyle-green" style="padding:0.55rem 1.35rem; font-size:0.84rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
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
                            {{ '@' . $account->username }}
                            <span style="display:inline-flex; align-items:center; gap:0.35rem; background:#f0fdf4; color:#16a34a; font-size:0.75rem; font-weight:700; padding:4px 10px; border-radius:999px; border:1px solid #bbf7d0;">
                                <span style="width:7px; height:7px; background:#16a34a; border-radius:50%; box-shadow:0 0 6px rgba(22,163,74,0.6);"></span> Terhubung &amp; Aktif
                            </span>
                        </h3>
                        <p class="acc-meta" style="color:#64748b; font-size:0.82rem; margin-top:4px; line-height:1.4;">
                            Otomatisasi balasan komentar &amp; DM sudah aktif dan siap bekerja untuk seluruh campaign Anda.
                        </p>
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
                    <form action="{{ route('creator.automation-reply.disconnect') }}" method="POST" onsubmit="return confirm('Yakin ingin memutuskan koneksi akun Instagram ini?')">
                        @csrf
                        <button type="submit" class="btn-danger-outline">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            Putuskan Koneksi
                        </button>
                    </form>
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
                                @elseif($log->status === 'skipped')
                                    <span style="display:inline-flex; align-items:center; gap:0.3rem; padding:3px 9px; border-radius:999px; background:#f1f5f9; color:#64748b; font-size:0.72rem; font-weight:800;">
                                        — Dilewati
                                    </span>
                                @else
                                    <span style="display:inline-flex; align-items:center; gap:0.3rem; padding:3px 9px; border-radius:999px; background:#fef2f2; color:#dc2626; font-size:0.72rem; font-weight:800;">
                                        ✗ Gagal
                                    </span>
                                @endif
                                @if(!empty($log->error_message) && $log->status !== 'success')
                                    <div style="font-size:0.68rem; color:#94a3b8; margin-top:3px; max-width:180px; word-break:break-word; line-height:1.3;">
                                        {{ Str::limit($log->error_message, 120) }}
                                    </div>
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

{{-- ====== MODAL EDIT CAMPAIGN ====== --}}
<div id="modalEditCampaign" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem;">
    <div style="background:#fff; border-radius:20px; width:100%; max-width:760px; max-height:90vh; overflow-y:auto; box-shadow:0 24px 64px rgba(0,0,0,0.18); font-family:'Montserrat',sans-serif;">
        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1.5px solid #f1f5f9;">
            <div style="display:flex; align-items:center; gap:0.6rem;">
                <div style="width:36px; height:36px; background:#f0fdf4; color:#1eb349; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div>
                    <h3 style="margin:0; font-size:1rem; font-weight:800; color:#0f172a;">Edit Campaign</h3>
                    <p id="editCampSubtitle" style="margin:0; font-size:0.75rem; color:#64748b;"></p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" style="background:none; border:none; cursor:pointer; color:#94a3b8; padding:0.25rem; border-radius:8px; line-height:1;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="formEditCampaign" method="POST" style="padding:1.25rem 1.5rem;">
            @csrf
            @method('PUT')
            <input type="hidden" name="_campaign_id" id="editCampId">

            {{-- Row 1: Nama Campaign + Link Tujuan --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem 1.1rem; margin-bottom:0.75rem;">
                <div>
                    <label style="font-size:0.79rem; font-weight:700; color:#1e293b; display:block; margin-bottom:0.3rem;">Nama Campaign *</label>
                    <input type="text" name="name" id="editCampName" class="form-input" style="padding:0.6rem 0.85rem; font-size:0.82rem;" required>
                </div>
                <div>
                    <label style="font-size:0.79rem; font-weight:700; color:#1e293b; display:block; margin-bottom:0.3rem;">Tujuan Link di DM</label>
                    <div style="display:flex; gap:0.35rem; margin-bottom:0.35rem;">
                        <button type="button" id="editBtnLinkTypeProduct" onclick="setEditLinkType('product')" style="flex:1; padding:0.38rem 0.55rem; font-size:0.73rem; font-weight:700; border-radius:8px; border:1.5px solid #1eb349; background:#f0fdf4; color:#16a34a; cursor:pointer; font-family:'Montserrat',sans-serif; transition:all .15s;">Produk Digital</button>
                        <button type="button" id="editBtnLinkTypeCustom" onclick="setEditLinkType('custom')" style="flex:1; padding:0.38rem 0.55rem; font-size:0.73rem; font-weight:700; border-radius:8px; border:1.5px solid #e2e8f0; background:#fff; color:#64748b; cursor:pointer; font-family:'Montserrat',sans-serif; transition:all .15s;">Link Eksternal</button>
                    </div>
                    <div id="editWrapProductSelect">
                        <select name="product_id" id="editCampProduct" class="form-select" style="padding:0.6rem 0.85rem; font-size:0.82rem;">
                            <option value="">-- Tanpa Produk --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }} — Rp {{ number_format($prod->price,0,',','.') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="editWrapCustomLink" style="display:none;">
                        <input type="url" name="custom_link" id="editCampCustomLink" class="form-input" style="padding:0.6rem 0.85rem; font-size:0.82rem;" placeholder="https://wa.me/... atau https://...">
                    </div>
                </div>
            </div>

            {{-- Row 2: Target Postingan + Trigger Keywords --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem 1.1rem; margin-bottom:0.75rem;">
                <div>
                    <label style="font-size:0.79rem; font-weight:700; color:#1e293b; display:block; margin-bottom:0.3rem;">Target Postingan *</label>
                    <select name="target_type" id="editTargetType" class="form-select" style="padding:0.6rem 0.85rem; font-size:0.82rem;" onchange="toggleEditMediaIdWrap(this.value)">
                        <option value="all">Semua Postingan & Reels (Rekomendasi)</option>
                        <option value="specific">Postingan / Reels Tertentu</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.79rem; font-weight:700; color:#1e293b; display:block; margin-bottom:0.3rem;">Kata Kunci Pemicu</label>
                    <input type="text" name="trigger_keywords" id="editCampKeywords" class="form-input" style="padding:0.6rem 0.85rem; font-size:0.82rem;" placeholder="mau, info, link, harga">
                </div>
            </div>

            {{-- Media ID (tampil jika specific) --}}
            <div id="editMediaIdWrap" style="display:none; margin-bottom:0.75rem;">
                <label style="font-size:0.79rem; font-weight:700; color:#1e293b; display:block; margin-bottom:0.3rem;">Link / Media ID Reels Spesifik</label>
                <input type="text" name="target_media_id" id="editCampMediaId" class="form-input" style="padding:0.6rem 0.85rem; font-size:0.82rem;" placeholder="https://www.instagram.com/reel/... atau Media ID">
            </div>

            {{-- Spintax Variants --}}
            <div style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; padding:0.9rem 1rem; margin-bottom:0.75rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.6rem; flex-wrap:wrap; gap:0.35rem;">
                    <div>
                        <h4 style="font-size:0.83rem; font-weight:800; color:#0f172a; margin:0;">✨ Variasi Pesan DM</h4>
                        <p style="font-size:0.71rem; color:#64748b; margin:1px 0 0;">Tag: <span style="background:#e0f2fe;color:#0369a1;padding:1px 5px;border-radius:4px;font-size:0.7rem;font-weight:700;">{first_name}</span> <span style="background:#e0f2fe;color:#0369a1;padding:1px 5px;border-radius:4px;font-size:0.7rem;font-weight:700;">{link_product}</span></p>
                    </div>
                    <button type="button" onclick="addEditVariant()" style="font-size:0.72rem; font-weight:700; color:#1eb349; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:0.3rem 0.7rem; cursor:pointer; font-family:'Montserrat',sans-serif;">+ Tambah Varian</button>
                </div>
                <div id="editSpintaxContainer" style="display:flex; flex-direction:column; gap:0.45rem;">
                    {{-- Diisi via JS --}}
                </div>
            </div>

            {{-- Row 3: Balasan Komentar + Delay --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem 1.1rem; margin-bottom:0.75rem;">
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.4rem;">
                        <label style="font-size:0.79rem; font-weight:700; color:#1e293b; margin:0;">Balas Komentar Publik</label>
                        <input type="checkbox" name="auto_comment_enabled" id="editAutoCommentToggle" value="1" onchange="toggleEditPublicReply(this.checked)" style="width:15px;height:15px;accent-color:#1eb349;cursor:pointer;">
                    </div>
                    <div id="editPublicReplyBox">
                        <textarea name="auto_comment_variants[]" id="editAutoCommentText" rows="2" class="form-textarea" style="padding:0.45rem 0.7rem; font-size:0.79rem;" placeholder="Cek DM ya kak! 📩"></textarea>
                    </div>
                </div>

                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem;">
                    <div style="display:flex; justify-content:space-between; font-size:0.78rem; font-weight:700; color:#0f172a; margin-bottom:0.3rem;">
                        <span>Delay Pengiriman DM:</span>
                        <span id="editDelayDisplay" style="color:#1eb349; font-weight:800;">5 Detik</span>
                    </div>
                    <input type="range" name="delay_seconds" id="editDelayRange" min="3" max="8" value="5" class="slider-range" oninput="document.getElementById('editDelayDisplay').innerText = this.value + ' Detik'">
                    <input type="hidden" name="trigger_type" value="contains">
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="display:flex; justify-content:flex-end; gap:0.7rem; margin-top:0.5rem; padding-top:0.9rem; border-top:1px solid #f1f5f9;">
                <button type="button" onclick="closeEditModal()" style="padding:0.5rem 1.1rem; font-size:0.82rem; border:1.5px solid #e2e8f0; background:#fff; color:#475569; border-radius:10px; cursor:pointer; font-family:'Montserrat',sans-serif; font-weight:700;">Batal</button>
                <button type="submit" class="btn-buyle-green" style="padding:0.5rem 1.25rem; font-size:0.84rem;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
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
            wrap.style.display = (val === 'specific') ? 'block' : 'none';
        }
    }

    function setLinkType(type) {
        const wrapProduct = document.getElementById('wrapProductSelect');
        const wrapCustom = document.getElementById('wrapCustomLink');
        const btnProduct = document.getElementById('btnLinkTypeProduct');
        const btnCustom = document.getElementById('btnLinkTypeCustom');
        const productSelect = document.getElementById('campaignProductSelect');
        const customInput = document.getElementById('campaignCustomLinkInput');

        if (type === 'product') {
            wrapProduct.style.display = 'block';
            wrapCustom.style.display = 'none';
            if (customInput) customInput.value = '';
            btnProduct.style.border = '1.5px solid #1eb349';
            btnProduct.style.background = '#f0fdf4';
            btnProduct.style.color = '#16a34a';
            btnCustom.style.border = '1.5px solid #e2e8f0';
            btnCustom.style.background = '#ffffff';
            btnCustom.style.color = '#64748b';
        } else {
            wrapProduct.style.display = 'none';
            wrapCustom.style.display = 'block';
            if (productSelect) productSelect.value = '';
            btnCustom.style.border = '1.5px solid #1eb349';
            btnCustom.style.background = '#f0fdf4';
            btnCustom.style.color = '#16a34a';
            btnProduct.style.border = '1.5px solid #e2e8f0';
            btnProduct.style.background = '#ffffff';
            btnProduct.style.color = '#64748b';
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

    /* ── EDIT CAMPAIGN MODAL ─────────────────────────────── */
    const BASE_UPDATE_URL = "{{ route('creator.automation-reply.campaigns.update', '__ID__') }}";

    function openEditModal(camp) {
        const modal = document.getElementById('modalEditCampaign');
        const form  = document.getElementById('formEditCampaign');

        // Set form action URL
        form.action = BASE_UPDATE_URL.replace('__ID__', camp.id);

        // Subtitle
        document.getElementById('editCampSubtitle').textContent = 'Campaign: ' + camp.name;

        // Isi field dasar
        document.getElementById('editCampId').value       = camp.id;
        document.getElementById('editCampName').value     = camp.name || '';
        document.getElementById('editCampKeywords').value = (camp.trigger_keywords || []).join(', ');
        document.getElementById('editCampMediaId').value  = camp.target_media_id || '';

        // Target type
        const targetTypeEl = document.getElementById('editTargetType');
        targetTypeEl.value = camp.target_type || 'all';
        toggleEditMediaIdWrap(targetTypeEl.value);

        // Delay
        const delay = camp.delay_seconds || 5;
        document.getElementById('editDelayRange').value = delay;
        document.getElementById('editDelayDisplay').innerText = delay + ' Detik';

        // Produk vs custom link
        const productId  = camp.product_id;
        const customLink = camp.custom_link || '';
        if (productId) {
            setEditLinkType('product');
            document.getElementById('editCampProduct').value = productId;
            document.getElementById('editCampCustomLink').value = '';
        } else if (customLink) {
            setEditLinkType('custom');
            document.getElementById('editCampCustomLink').value = customLink;
        } else {
            setEditLinkType('product');
            document.getElementById('editCampProduct').value = '';
        }

        // Spintax variants
        const container = document.getElementById('editSpintaxContainer');
        container.innerHTML = '';
        const variants = camp.spintax_variants || [''];
        variants.forEach(function(v, i) {
            container.appendChild(buildVariantCard(v, i + 1));
        });

        // Auto comment
        const autoCommentEnabled = camp.auto_comment_enabled === true || camp.auto_comment_enabled === 1;
        document.getElementById('editAutoCommentToggle').checked = autoCommentEnabled;
        toggleEditPublicReply(autoCommentEnabled);
        const commentVariants = camp.auto_comment_variants || [];
        document.getElementById('editAutoCommentText').value = commentVariants[0] || '';

        // Show modal
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        const modal = document.getElementById('modalEditCampaign');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Tutup modal jika klik di backdrop
    document.getElementById('modalEditCampaign').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEditModal();
    });

    function setEditLinkType(type) {
        const wrapProduct = document.getElementById('editWrapProductSelect');
        const wrapCustom  = document.getElementById('editWrapCustomLink');
        const btnProduct  = document.getElementById('editBtnLinkTypeProduct');
        const btnCustom   = document.getElementById('editBtnLinkTypeCustom');

        if (type === 'product') {
            wrapProduct.style.display = 'block';
            wrapCustom.style.display  = 'none';
            btnProduct.style.cssText += ';border:1.5px solid #1eb349;background:#f0fdf4;color:#16a34a;';
            btnCustom.style.cssText  += ';border:1.5px solid #e2e8f0;background:#fff;color:#64748b;';
        } else {
            wrapProduct.style.display = 'none';
            wrapCustom.style.display  = 'block';
            btnCustom.style.cssText  += ';border:1.5px solid #1eb349;background:#f0fdf4;color:#16a34a;';
            btnProduct.style.cssText += ';border:1.5px solid #e2e8f0;background:#fff;color:#64748b;';
            // Kosongkan product_id agar tidak dikirim saat custom link dipilih
            const prodSelect = document.getElementById('editCampProduct');
            if (prodSelect) prodSelect.value = '';
        }
    }

    function toggleEditMediaIdWrap(val) {
        const wrap = document.getElementById('editMediaIdWrap');
        if (wrap) wrap.style.display = (val === 'specific') ? 'block' : 'none';
    }

    function toggleEditPublicReply(checked) {
        const box = document.getElementById('editPublicReplyBox');
        if (box) box.style.display = checked ? 'block' : 'none';
    }

    let editVariantCount = 0;

    function buildVariantCard(text, index) {
        editVariantCount++;
        const div = document.createElement('div');
        div.style.cssText = 'background:#fff;border:1.5px dashed #cbd5e1;border-radius:12px;padding:0.6rem 0.8rem;position:relative;';
        div.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <label style="font-size:0.71rem;font-weight:800;color:#1e293b;">Variasi DM #${index}</label>
                ${index > 1 ? `<button type="button" onclick="this.closest('div[data-vc]').remove()" style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:0.7rem;font-weight:700;padding:0;">✕ Hapus</button>` : ''}
            </div>
            <textarea name="spintax_variants[]" rows="2" style="width:100%;padding:0.45rem 0.7rem;border-radius:10px;border:1.5px solid #e2e8f0;font-size:0.79rem;font-family:'Montserrat',sans-serif;background:#f8fafc;color:#0f172a;resize:vertical;box-sizing:border-box;">${text}</textarea>
        `;
        div.setAttribute('data-vc', editVariantCount);
        return div;
    }

    function addEditVariant() {
        const container = document.getElementById('editSpintaxContainer');
        const count = container.querySelectorAll('textarea').length + 1;
        container.appendChild(buildVariantCard('', count));
    }
</script>
@endsection
