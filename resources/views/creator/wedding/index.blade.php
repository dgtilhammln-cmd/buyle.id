@extends('creator.layout')
@section('title', 'Undangan Digital · Wedding Hub')
@section('page_title', 'Undangan Digital')

@section('topbar_actions')
    <a href="{{ route('creator.app.index') }}"
        style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 0.95rem; font-size:0.8rem; font-weight:700; color:#475569; background:#ffffff; border:1.5px solid #e2e8f0; border-radius:999px; text-decoration:none; transition:all 0.15s ease; font-family:'Montserrat',sans-serif; box-shadow:0 2px 6px rgba(0,0,0,0.03);"
        onmouseover="this.style.borderColor='#1eb349';this.style.color='#166534';"
        onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569';">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        Kembali ke Aplikasi
    </a>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        .map-filter-btn {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.35rem 0.75rem;
            border-radius: 99px;
            cursor: pointer;
            transition: all .15s;
            font-family: 'Montserrat', sans-serif;
        }
        .map-filter-btn.active, .map-filter-btn:hover {
            background: #10b981;
            color: #ffffff;
            border-color: #10b981;
        }
        .custom-guest-pin {
            background: transparent;
            border: none;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .bio-layout {
            display: flex;
            gap: 1.75rem;
            align-items: flex-start;
        }

        .bio-sidebar-column {
            width: 300px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            position: sticky;
            top: 1.5rem;
            max-height: calc(100vh - 3rem);
            overflow-y: auto;
            scrollbar-width: none;
        }

        .bio-sidebar-column::-webkit-scrollbar {
            display: none;
        }

        .bio-sidebar-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 1.15rem;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
        }

        .bio-content {
            flex: 1;
            min-width: 0;
        }

        .tab-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1.5px solid transparent;
            background: #ffffff;
            color: #475569;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 14px;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s ease;
            margin-bottom: 0.35rem;
            position: relative;
        }

        .tab-btn:hover {
            background: #F8FAFC;
            color: #0F172A;
            border-color: #E2E8F0;
            transform: translateX(2px);
        }

        .tab-btn:active {
            transform: scale(0.99);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            font-weight: 700;
            border-color: transparent !important;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.32);
        }

        .tab-btn.active svg {
            stroke: #ffffff !important;
        }

        .tab-btn svg {
            transition: stroke 0.2s ease;
            flex-shrink: 0;
            stroke: #64748B;
        }

        .tab-btn:hover svg {
            stroke: #1eb349;
        }

        .tab-text-full {
            display: inline-block;
        }

        .tab-text-mob {
            display: none;
        }

        .tab-pane {
            display: none;
            animation: fadeIn 0.25s ease;
        }

        .tab-pane.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card container */
        .prof-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .prof-card-head {
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            background: #ffffff;
        }

        .prof-card-head .head-title-wrap {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .prof-card-head .head-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: #f0fdf4;
            color: #166534;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-body {
            padding: 1.5rem 1.75rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .form-input {
            width: 100%;
            box-sizing: border-box;
            height: 44px;
            padding: 0 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: #1eb349;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
        }

        textarea.form-input {
            height: auto;
            padding: 0.75rem 1rem;
            resize: vertical;
            min-height: 85px;
        }

        .form-hint {
            font-size: 0.72rem;
            color: #64748b;
            line-height: 1.35;
        }

        /* Banner Web Builder */
        .wb-page-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            font-family: 'Montserrat', sans-serif;
        }

        .wb-page-banner-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .wb-page-banner-title {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .wb-page-banner-title h2 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .wb-page-banner-badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.68rem;
            font-weight: 700;
            color: #166534;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .wb-page-banner-desc {
            font-size: 0.78rem;
            color: #64748b;
            margin: 0;
            line-height: 1.4;
        }

        .wb-page-banner-actions {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-shrink: 0;
        }

        .wb-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 12px;
            padding: 0.6rem 1.15rem;
            font-size: 0.8rem;
            font-weight: 700 !important;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.32) !important;
            transition: all 0.15s ease;
        }

        .wb-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(30, 179, 73, 0.4) !important;
        }

        .wb-btn-danger-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #fff;
            color: #dc2626;
            border: 1.5px solid #fecaca;
            border-radius: 12px;
            padding: 0.58rem 1rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .wb-btn-danger-outline:hover {
            background: #fef2f2;
            border-color: #ef4444;
            color: #991b1b;
        }

        .wb-btn-preview-link {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #ffffff;
            color: #334155;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.58rem 1rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .wb-btn-preview-link:hover {
            border-color: #1eb349;
            color: #166534;
            background: #f0fdf4;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.15rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            display: flex;
            align-items: center;
            gap: 0.95rem;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.green {
            background: #dcfce7;
            color: #166534;
        }

        .stat-icon.blue {
            background: #e0f2fe;
            color: #0369a1;
        }

        .stat-icon.rose {
            background: #ffe4e6;
            color: #be123c;
        }

        .stat-icon.amber {
            background: #fef3c7;
            color: #b45309;
        }

        .stat-meta {
            display: flex;
            flex-direction: column;
        }

        .stat-val {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .stat-lbl {
            font-size: 0.72rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* Table Style */
        .data-table-wrap {
            overflow-x: auto;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.825rem;
            text-align: left;
        }

        .data-table th {
            background: #f8fafc;
            padding: 0.85rem 1rem;
            font-weight: 700;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .data-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #1e293b;
        }

        .data-table tr:hover td {
            background: #f8fafc;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .badge-pill.success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-pill.warning {
            background: #fef3c7;
            color: #92400e;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .bio-layout {
                flex-direction: column;
                gap: 0.75rem;
            }

            .bio-sidebar-column {
                width: 100%;
                position: sticky;
                top: 64px;
                z-index: 80;
                padding: 0;
            }

            .bio-sidebar-card {
                padding: 0.4rem;
                border-radius: 14px;
            }

            .bio-tabs-list {
                display: grid !important;
                grid-template-columns: repeat(5, 1fr) !important;
                gap: 4px !important;
                background: #f8fafc;
                padding: 4px;
                border-radius: 12px;
            }

            .tab-btn {
                padding: 0.45rem 2px !important;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 2px;
                border-radius: 8px;
                margin-bottom: 0;
                min-width: 0;
            }

            .tab-btn svg {
                width: 16px;
                height: 16px;
            }

            .tab-text-full {
                display: none !important;
            }

            .tab-text-mob {
                display: block !important;
                font-size: 0.58rem !important;
                font-weight: 700;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 100%;
                text-align: center;
            }

            .wb-page-banner {
                flex-direction: column;
                align-items: stretch;
                padding: 1rem;
                gap: 0.85rem;
            }

            .wb-page-banner-actions {
                flex-direction: column;
                width: 100%;
            }

            .wb-page-banner-actions>* {
                width: 100%;
                justify-content: center;
                box-sizing: border-box;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }

            .prof-card-head {
                padding: 0.85rem 1rem;
                font-size: 0.85rem;
            }

            .card-body {
                padding: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $activeTab = request('tab', 'tab-info');
        $publicWeddingUrl = $wedding ? route('wedding.show', $wedding->slug) : '#';
        $receptionUrl = $wedding ? route('wedding.reception', $wedding->slug) : '#';
        $photoboothUrl = $wedding ? route('wedding.photobooth', $wedding->slug) : '#';
        $midtransClientKeyJs = $midtransClientKey ?? '';
        $midtransEnv = ($midtransIsProduction ?? false) ? 'production' : 'sandbox';
    @endphp

    {{-- Alert Messages --}}
    @if(session('success'))
        <div
            style="background:#ecfdf5; border:1.5px solid #a7f3d0; color:#065f46; padding:0.9rem 1.25rem; border-radius:14px; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.65rem; font-size:0.85rem; font-weight:600;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Web Builder Banner --}}
    <div class="wb-page-banner">
        <div class="wb-page-banner-info">
            <div class="wb-page-banner-title">
                <h2>Wedding Hub & Undangan Digital</h2>
                <span class="wb-page-banner-badge">Smart Guest Book</span>
            </div>
            <p class="wb-page-banner-desc">Kelola undangan pernikahan digital, Smart Guest Book QR check-in, amplop digital,
                dan ucapan doa dalam satu tempat.</p>
        </div>
        <div class="wb-page-banner-actions">
            <a href="{{ $publicWeddingUrl }}" target="_blank" class="wb-btn-preview-link" title="Buka Halaman Undangan">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                </svg>
                <span>Lihat Undangan</span>
            </a>
            <a href="{{ route('creator.wedding.panic-pdf') }}" class="wb-btn-danger-outline"
                title="Cetak Buku Tamu Cadangan Fisik">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Panic Button PDF</span>
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         COUPLE SWITCHER + TAMBAH PASANGAN BARU
    ═══════════════════════════════════════════════════ --}}
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; margin-bottom:1.25rem;">
        {{-- Dropdown Pasangan --}}
        <div style="display:flex; align-items:center; gap:0.65rem;">
            <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <span style="font-size:0.8rem; font-weight:700; color:#334155; font-family:'Montserrat',sans-serif;">Pasangan Aktif:</span>
            @if($allWeddings->count() > 1)
                <div style="position:relative;">
                    <select onchange="window.location.href='{{ route('creator.wedding.index') }}?w=' + this.value + '&tab={{ $activeTab }}'"
                            style="appearance:none; background:#fff; border:1.5px solid #e2e8f0; border-radius:10px; padding:0.4rem 2.2rem 0.4rem 0.8rem; font-size:0.83rem; font-weight:700; color:#0f172a; cursor:pointer; font-family:'Montserrat',sans-serif;">
                        @foreach($allWeddings as $w)
                            <option value="{{ $w->id }}" {{ ($wedding && $wedding->id === $w->id) ? 'selected' : '' }}>
                                {{ $w->groom_nickname ?? $w->groom_name }} &amp; {{ $w->bride_nickname ?? $w->bride_name }}
                                @if($w->payment_status !== 'paid') [Pending] @endif
                            </option>
                        @endforeach
                    </select>
                    <svg style="position:absolute;right:8px;top:50%;transform:translateY(-50%);pointer-events:none;" width="12" height="12" fill="none" stroke="#64748b" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            @elseif($wedding)
                <span style="background:#ecfdf5; color:#166534; font-size:0.83rem; font-weight:700; padding:0.35rem 0.85rem; border-radius:8px; font-family:'Montserrat',sans-serif; display:inline-flex; align-items:center; gap:0.4rem;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    {{ $wedding->groom_nickname ?? $wedding->groom_name }} &amp; {{ $wedding->bride_nickname ?? $wedding->bride_name }}
                </span>
            @else
                <span style="color:#94a3b8; font-size:0.8rem;">Belum ada pasangan</span>
            @endif
        </div>

        {{-- Tombol Tambah Pasangan Baru --}}
        <button onclick="document.getElementById('modal-add-couple').style.display='flex'"
                style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.45rem 1rem;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;border:none;border-radius:10px;font-size:0.82rem;font-weight:700;cursor:pointer;font-family:'Montserrat',sans-serif;box-shadow:0 2px 8px rgba(30,179,73,0.25);transition:opacity .15s;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Pasangan Baru
        </button>
    </div>

    {{-- Layout Web Builder --}}
    <div class="bio-layout">

        {{-- Sidebar Tab Column --}}
        <div class="bio-sidebar-column">
            <div class="bio-sidebar-card">
                <div class="bio-tabs-list">
                    <button class="tab-btn {{ $activeTab === 'tab-info' ? 'active' : '' }}" data-tab="tab-info">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                        <span class="tab-text-full">Mempelai & Acara</span>
                        <small class="tab-text-mob">Acara</small>
                    </button>

                    <button class="tab-btn {{ $activeTab === 'tab-checkin' ? 'active' : '' }}" data-tab="tab-checkin">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        <span class="tab-text-full">Buku Tamu & Check-in</span>
                        <small class="tab-text-mob">Check-in</small>
                    </button>

                    <button class="tab-btn {{ $activeTab === 'tab-guests' ? 'active' : '' }}" data-tab="tab-guests">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="tab-text-full">Daftar Tamu & WA</span>
                        <small class="tab-text-mob">Tamu</small>
                    </button>

                    <button class="tab-btn {{ $activeTab === 'tab-map' ? 'active' : '' }}" data-tab="tab-map">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                            <line x1="8" y1="2" x2="8" y2="18"></line>
                            <line x1="16" y1="6" x2="16" y2="22"></line>
                        </svg>
                        <span class="tab-text-full">Peta Sebaran Tamu</span>
                        <small class="tab-text-mob">Peta</small>
                        @if(isset($mapGuests) && $mapGuests->count() > 0)
                            <span style="background:#10b981; color:#fff; font-size:0.65rem; font-weight:800; padding:1px 6px; border-radius:99px; margin-left:auto;">{{ $mapGuests->count() }}</span>
                        @endif
                    </button>

                    <button class="tab-btn {{ $activeTab === 'tab-envelope' ? 'active' : '' }}" data-tab="tab-envelope">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                        <span class="tab-text-full">Amplop Digital</span>
                        <small class="tab-text-mob">Amplop</small>
                    </button>

                    <button class="tab-btn {{ $activeTab === 'tab-wishes' ? 'active' : '' }}" data-tab="tab-wishes">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span class="tab-text-full">Doa &amp; Ucapan Tamu</span>
                        <small class="tab-text-mob">Doa</small>
                    </button>

                    <button class="tab-btn {{ $activeTab === 'tab-photobooth' ? 'active' : '' }}" data-tab="tab-photobooth">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        <span class="tab-text-full">Photobooth Barcode &amp; Frame</span>
                        <small class="tab-text-mob">Photobooth</small>
                    </button>
                </div>
            </div>

            {{-- Quota Progress Bar --}}
            @if($wedding)
            <div class="bio-sidebar-card" style="padding:1rem;">
                <div style="font-size:0.75rem;font-weight:700;color:#334155;margin-bottom:0.5rem;display:flex;justify-content:space-between;align-items:center;">
                    <span>Kuota Undangan</span>
                    <span style="color:#{{ $wedding->remainingGuestSlots() === 0 ? 'ef4444' : '1eb349' }};">
                        {{ $totalGuests }} / {{ $wedding->guest_limit }}
                    </span>
                </div>
                @php
                    $pct = $wedding->guest_limit > 0 ? min(100, round($totalGuests / $wedding->guest_limit * 100)) : 0;
                    $barColor = $pct >= 90 ? '#ef4444' : ($pct >= 70 ? '#f59e0b' : '#1eb349');
                @endphp
                <div style="background:#f1f5f9;border-radius:99px;height:8px;overflow:hidden;">
                    <div style="width:{{ $pct }}%;height:100%;background:{{ $barColor }};border-radius:99px;transition:width .4s;"></div>
                </div>
                <div style="font-size:0.7rem;color:#94a3b8;margin-top:0.35rem;">Tersisa {{ $wedding->remainingGuestSlots() }} slot</div>
                @if($wedding->remainingGuestSlots() < 20)
                <button onclick="document.getElementById('modal-add-couple').style.display='flex'"
                    style="margin-top:0.5rem;width:100%;padding:0.4rem;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;border:none;border-radius:8px;font-size:0.75rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.35rem;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                    Upgrade Kuota
                </button>
                @endif
            </div>
            @endif

            {{-- Reception Gate Quick Info Box --}}
            <div class="bio-sidebar-card" style="background:#f8fafc; border:1.5px dashed #cbd5e1;">
                <div
                    style="font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:0.4rem; display:flex; align-items:center; gap:0.4rem;">
                    <svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Akses Meja Penerima Tamu
                </div>
                <div style="font-size:0.72rem; color:#64748b; margin-bottom:0.75rem; line-height:1.4;">
                    Berikan link ini ke panitia di venue. Panitia cukup memasukkan 4-digit PIN tanpa perlu akun login.
                </div>
                <div style="display:flex; flex-direction:column; gap:0.4rem;">
                    <div
                        style="display:flex; justify-content:space-between; align-items:center; background:#fff; padding:0.4rem 0.6rem; border-radius:8px; border:1px solid #e2e8f0; font-size:0.75rem;">
                        <span style="color:#64748b; font-weight:600;">PIN:</span>
                        <span
                            style="font-weight:800; font-family:monospace; color:#0f172a; font-size:0.9rem;">{{ $wedding->reception_pin }}</span>
                    </div>
                    <a href="{{ $receptionUrl }}" target="_blank"
                        style="display:flex; align-items:center; justify-content:center; gap:0.35rem; font-size:0.75rem; font-weight:700; color:#166534; background:#dcfce7; padding:0.5rem; border-radius:8px; text-decoration:none;">
                        Buka Scanner Meja Tamu
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Content Column --}}
        <div class="bio-content">

            {{-- ═══════════════════════════════════════════════════════════
            TAB 1: MEMPELAI & DETAIL ACARA
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane {{ $activeTab === 'tab-info' ? 'active' : '' }}" id="tab-info">
                <form action="{{ route('creator.wedding.update-info') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Card URL Slug & Header --}}
                    <div class="prof-card">
                        <div class="prof-card-head">
                            <div class="head-title-wrap">
                                <div class="head-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="2" y1="12" x2="22" y2="12"></line>
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                        </path>
                                    </svg>
                                </div>
                                <span>Tautan Web Undangan &amp; PIN</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.25rem;">
                                <div class="form-group">
                                    <label class="form-label">Slug URL Undangan</label>
                                    <div
                                        style="display:flex; align-items:center; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                                        <span
                                            style="padding:0 0.85rem; font-size:0.8rem; font-weight:700; color:#64748b; background:#f1f5f9; border-right:1px solid #e2e8f0;">{{ url('/wedding') }}/</span>
                                        <input type="text" name="slug" value="{{ old('slug', $wedding->slug) }}"
                                            class="form-input" style="border:none; background:transparent;" required>
                                    </div>
                                    <span class="form-hint">Tautan resmi yang akan dibagikan ke seluruh tamu
                                        undangan.</span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">PIN Panitia Resepsi (4 Digit)</label>
                                    <input type="text" name="reception_pin"
                                        value="{{ old('reception_pin', $wedding->reception_pin) }}" maxlength="6"
                                        class="form-input"
                                        style="font-weight:800; font-family:monospace; text-align:center; font-size:1.05rem;"
                                        required>
                                    <span class="form-hint">Digunakan panitia untuk akses scan tamu.</span>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Judul Acara / Tagline</label>
                                <input type="text" name="event_title"
                                    value="{{ old('event_title', $wedding->event_title) }}"
                                    placeholder="Contoh: The Wedding Celebration of" class="form-input" required>
                            </div>
                        </div>
                    </div>

                    {{-- Card Pasangan Mempelai --}}
                    <div class="prof-card">
                        <div class="prof-card-head">
                            <div class="head-title-wrap">
                                <div class="head-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </div>
                                <span>Informasi Mempelai Pria &amp; Wanita</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">
                                {{-- Mempelai Pria --}}
                                <div
                                    style="background:#f8fafc; padding:1.25rem; border-radius:14px; border:1px solid #e2e8f0;">
                                    <div
                                        style="font-size:0.875rem; font-weight:800; color:#0f172a; margin-bottom:1rem; display:flex; align-items:center; gap:0.4rem;">
                                        <span style="width:8px; height:8px; border-radius:50%; background:#0284c7;"></span>
                                        Mempelai Pria (Groom)
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Lengkap &amp; Gelar</label>
                                        <input type="text" name="groom_name"
                                            value="{{ old('groom_name', $wedding->groom_name) }}"
                                            placeholder="Rian Pratama, S.Kom" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Panggilan</label>
                                        <input type="text" name="groom_nickname"
                                            value="{{ old('groom_nickname', $wedding->groom_nickname) }}" placeholder="Rian"
                                            class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Orang Tua</label>
                                        <input type="text" name="groom_parents"
                                            value="{{ old('groom_parents', $wedding->groom_parents) }}"
                                            placeholder="Putra pertama dari Bpk. Budi &amp; Ibu Siti" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Instagram Handle</label>
                                        <input type="text" name="groom_instagram"
                                            value="{{ old('groom_instagram', $wedding->groom_instagram) }}"
                                            placeholder="@rianpratama" class="form-input">
                                    </div>
                                    <div class="form-group" style="margin-bottom:0;">
                                        <label class="form-label">Foto Mempelai Pria</label>
                                        <input type="file" name="groom_photo" accept="image/*" class="form-input"
                                            style="padding-top:0.4rem;">
                                    </div>
                                </div>

                                {{-- Mempelai Wanita --}}
                                <div
                                    style="background:#fdf2f8; padding:1.25rem; border-radius:14px; border:1px solid #fbcfe8;">
                                    <div
                                        style="font-size:0.875rem; font-weight:800; color:#831843; margin-bottom:1rem; display:flex; align-items:center; gap:0.4rem;">
                                        <span style="width:8px; height:8px; border-radius:50%; background:#e11d48;"></span>
                                        Mempelai Wanita (Bride)
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Lengkap &amp; Gelar</label>
                                        <input type="text" name="bride_name"
                                            value="{{ old('bride_name', $wedding->bride_name) }}"
                                            placeholder="Anisa Rahmawati, S.E" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Panggilan</label>
                                        <input type="text" name="bride_nickname"
                                            value="{{ old('bride_nickname', $wedding->bride_nickname) }}"
                                            placeholder="Anisa" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Orang Tua</label>
                                        <input type="text" name="bride_parents"
                                            value="{{ old('bride_parents', $wedding->bride_parents) }}"
                                            placeholder="Putri kedua dari Bpk. Hendra &amp; Ibu Maria" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Instagram Handle</label>
                                        <input type="text" name="bride_instagram"
                                            value="{{ old('bride_instagram', $wedding->bride_instagram) }}"
                                            placeholder="@anisarahma" class="form-input">
                                    </div>
                                    <div class="form-group" style="margin-bottom:0;">
                                        <label class="form-label">Foto Mempelai Wanita</label>
                                        <input type="file" name="bride_photo" accept="image/*" class="form-input"
                                            style="padding-top:0.4rem;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Jadwal Akad & Resepsi --}}
                    <div class="prof-card">
                        <div class="prof-card-head">
                            <div class="head-title-wrap">
                                <div class="head-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                                <span>Waktu &amp; Lokasi Acara</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">
                                {{-- Akad / Pemberkatan --}}
                                <div>
                                    <div
                                        style="font-size:0.85rem; font-weight:700; color:#0f172a; margin-bottom:0.75rem; border-bottom:1.5px solid #e2e8f0; padding-bottom:0.4rem;">
                                        1. Akad Nikah / Pemberkatan
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Waktu Akad</label>
                                        <input type="datetime-local" name="ceremony_date"
                                            value="{{ $wedding->ceremony_date ? $wedding->ceremony_date->format('Y-m-d\TH:i') : '' }}"
                                            class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Tempat / Masjid / Gereja</label>
                                        <input type="text" name="ceremony_location_name"
                                            value="{{ old('ceremony_location_name', $wedding->ceremony_location_name) }}"
                                            placeholder="Masjid Agung Al-Falah" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea name="ceremony_address" class="form-input" rows="2"
                                            placeholder="Jl. Diponegoro No. 10...">{{ old('ceremony_address', $wedding->ceremony_address) }}</textarea>
                                    </div>
                                </div>

                                {{-- Resepsi Pernikahan --}}
                                <div>
                                    <div
                                        style="font-size:0.85rem; font-weight:700; color:#0f172a; margin-bottom:0.75rem; border-bottom:1.5px solid #e2e8f0; padding-bottom:0.4rem;">
                                        2. Resepsi Pernikahan
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Waktu Resepsi</label>
                                        <input type="datetime-local" name="event_date"
                                            value="{{ $wedding->event_date ? $wedding->event_date->format('Y-m-d\TH:i') : '' }}"
                                            class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Gedung / Ballroom</label>
                                        <input type="text" name="event_location_name"
                                            value="{{ old('event_location_name', $wedding->event_location_name) }}"
                                            placeholder="Grand Ballroom Hotel Mulia" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Alamat Lengkap Venue</label>
                                        <textarea name="event_address" class="form-input" rows="2"
                                            placeholder="Jl. Jenderal Sudirman No. 45...">{{ old('event_address', $wedding->event_address) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">URL Google Maps</label>
                                        <input type="url" name="event_map_url"
                                            value="{{ old('event_map_url', $wedding->event_map_url) }}"
                                            placeholder="https://maps.app.goo.gl/..." class="form-input">
                                    </div>
                                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.75rem;">
                                        <div class="form-group" style="margin-bottom:0;">
                                            <label class="form-label">Latitude Peta</label>
                                            <input type="text" name="event_latitude" id="inputLat"
                                                value="{{ old('event_latitude', $wedding->event_latitude) }}"
                                                placeholder="-6.2088" class="form-input">
                                        </div>
                                        <div class="form-group" style="margin-bottom:0;">
                                            <label class="form-label">Longitude Peta</label>
                                            <input type="text" name="event_longitude" id="inputLng"
                                                value="{{ old('event_longitude', $wedding->event_longitude) }}"
                                                placeholder="106.8456" class="form-input">
                                        </div>
                                    </div>
                                    <div style="margin-top:0.5rem; display:flex; justify-content:space-between; align-items:center;">
                                        <span class="form-hint">Digunakan untuk merender peta interaktif di halaman tamu.</span>
                                        <button type="button" onclick="getCurrentLocationGps()" style="background:#f1f5f9; border:1px solid #cbd5e1; border-radius:6px; font-size:0.7rem; font-weight:700; color:#334155; padding:2px 8px; cursor:pointer;">
                                            Ambil GPS HP / Laptop
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Musik Latar Undangan (Backsound MP3) --}}
                    <div class="prof-card">
                        <div class="prof-card-head">
                            <div class="head-title-wrap">
                                <div class="head-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M9 18V5l12-2v13"></path>
                                        <circle cx="6" cy="18" r="3"></circle>
                                        <circle cx="18" cy="16" r="3"></circle>
                                    </svg>
                                </div>
                                <span>Musik Latar Undangan (Backsound MP3)</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">
                                <div>
                                    <div class="form-group">
                                        <label class="form-label">Upload File Musik (.mp3, .wav, maks 20MB)</label>
                                        <input type="file" name="music_file" accept="audio/*" class="form-input"
                                            style="padding-top:0.4rem;">
                                        <span class="form-hint">Unggah rekaman audio romantis pilihan Anda dari HP /
                                            laptop.</span>
                                    </div>
                                    <div class="form-group" style="margin-bottom:0;">
                                        <label class="form-label">Atau Masukkan Tautan Musik (URL MP3)</label>
                                        <input type="url" name="music_url" id="musicUrlField"
                                            value="{{ old('music_url', $wedding->music_url) }}"
                                            placeholder="https://domain.com/musik-wedding.mp3" class="form-input">
                                        <span class="form-hint">Dapat diisi URL file MP3 online yang dapat diputar
                                            langsung.</span>
                                    </div>
                                </div>

                                <div>
                                    <div class="form-group">
                                        <label class="form-label">Pilih Lagu Rekomendasi (Bebas Royalti)</label>
                                        <select class="form-input"
                                            onchange="document.getElementById('musicUrlField').value = this.value; document.getElementById('audioPreviewElem').src = this.value; document.getElementById('previewWrap').style.display = 'block';">
                                            <option value="">-- Pilih Musik Rekomendasi --</option>
                                            <option
                                                value="https://assets.mixkit.co/music/preview/mixkit-wedding-waltz-piano-694.mp3">
                                                Romantic Wedding Waltz (Piano)</option>
                                            <option
                                                value="https://assets.mixkit.co/music/preview/mixkit-serene-view-443.mp3">
                                                Serene Acoustic Melody</option>
                                            <option
                                                value="https://assets.mixkit.co/music/preview/mixkit-love-story-piano-695.mp3">
                                                Love Story Ballad (Piano)</option>
                                        </select>
                                    </div>
                                    <div id="previewWrap"
                                        style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem; margin-top:0.5rem; {{ $wedding->music_url ? '' : 'display:none;' }}">
                                        <div
                                            style="font-size:0.75rem; font-weight:700; color:#166534; margin-bottom:0.4rem; display:flex; align-items:center; gap:0.35rem;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                                <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07">
                                                </path>
                                            </svg>
                                            Pratinjau Musik:
                                        </div>
                                        <audio id="audioPreviewElem" controls style="width:100%; height:36px;"
                                            src="{{ $wedding->music_url }}"></audio>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end;">
                        <button type="submit" class="wb-btn-primary" style="padding:0.75rem 2rem; font-size:0.9rem;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- ═══════════════════════════════════════════════════════════
            TAB 2: BUKU TAMU & CHECK-IN
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane {{ $activeTab === 'tab-checkin' ? 'active' : '' }}" id="tab-checkin">

                {{-- Stats Grid --}}
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2"
                                viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="stat-meta">
                            <span class="stat-val">{{ number_format($totalGuests) }}</span>
                            <span class="stat-lbl">Tamu Terdaftar</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon green">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2"
                                viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="stat-meta">
                            <span class="stat-val">{{ number_format($checkedInCount) }}</span>
                            <span class="stat-lbl">Tamu Check-in</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon amber">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div class="stat-meta">
                            <span
                                class="stat-val">{{ $totalGuests > 0 ? round(($checkedInCount / $totalGuests) * 100) : 0 }}%</span>
                            <span class="stat-lbl">Tingkat Kehadiran</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon rose">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2"
                                viewBox="0 0 24 24">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                        <div class="stat-meta">
                            <span class="stat-val">{{ number_format($totalWishes) }}</span>
                            <span class="stat-lbl">Doa &amp; Ucapan</span>
                        </div>
                    </div>
                </div>

                {{-- Fast Check-in Bar --}}
                <div class="prof-card" style="border: 2px solid #1eb349; background: #f0fdf4;">
                    <div class="card-body" style="padding:1.25rem 1.75rem;">
                        <div
                            style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                            <div>
                                <div
                                    style="font-weight:800; font-size:1rem; color:#166534; display:flex; align-items:center; gap:0.5rem;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2"
                                        viewBox="0 0 24 24">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    </svg>
                                    Cari &amp; Quick Check-in Tamu
                                </div>
                                <div style="font-size:0.75rem; color:#15803d; margin-top:2px;">
                                    Ketik kode QR tamu (misal: <code>WED-ABCD12</code>) atau ID tamu untuk verifikasi
                                    instan.
                                </div>
                            </div>
                            <div style="display:flex; gap:0.5rem; flex:1; max-width:420px;">
                                <input type="text" id="quickCheckinCode" placeholder="Masukkan Kode QR Tamu..."
                                    class="form-input"
                                    style="background:#fff; border-color:#86efac; font-family:monospace; font-weight:700;">
                                <button type="button" onclick="submitQuickCheckin()" class="wb-btn-primary"
                                    style="white-space:nowrap;">
                                    Check-in
                                </button>
                            </div>
                        </div>
                        <div id="quickCheckinResult" style="margin-top:0.75rem; display:none;"></div>
                    </div>
                </div>

                {{-- Tabel Tamu yang Sudah Hadir --}}
                <div class="prof-card">
                    <div class="prof-card-head">
                        <div class="head-title-wrap">
                            <div class="head-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                            </div>
                            <span>Daftar Tamu yang Telah Hadir (Check-in Realtime)</span>
                        </div>
                    </div>
                    <div class="card-body" style="padding:0;">
                        <div class="data-table-wrap" style="border:none;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama Tamu</th>
                                        <th>Kategori / Grup</th>
                                        <th>Pax Hadir</th>
                                        <th>Waktu Hadir</th>
                                        <th>Penerima Tamu</th>
                                        <th>Kode QR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $checkedGuests = $guests->where('is_checked_in', true);
                                    @endphp
                                    @forelse($checkedGuests as $g)
                                        <tr>
                                            <td>
                                                <div style="font-weight:700; color:#0f172a;">{{ $g->name }}</div>
                                                <div style="font-size:0.7rem; color:#64748b;">{{ $g->phone ?: '-' }}</div>
                                            </td>
                                            <td><span class="badge-pill warning">{{ $g->group_name ?: 'Tamu Undangan' }}</span>
                                            </td>
                                            <td><strong>{{ $g->actual_pax ?? $g->pax }}</strong> orang</td>
                                            <td>{{ $g->checked_in_at ? $g->checked_in_at->format('H:i') . ' WIB' : '-' }}</td>
                                            <td><span
                                                    style="font-size:0.75rem; color:#475569;">{{ $g->checked_in_by ?: 'Panitia' }}</span>
                                            </td>
                                            <td><code
                                                    style="font-size:0.75rem; background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $g->qr_code }}</code>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align:center; padding:2.5rem; color:#94a3b8;">
                                                Belum ada tamu yang check-in saat ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════════════════════════════════
            TAB 3: DAFTAR TAMU & WHATSAPP BLAST
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane {{ $activeTab === 'tab-guests' ? 'active' : '' }}" id="tab-guests">

                {{-- Form Input Tamu --}}
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">

                    {{-- Tambah 1 Tamu --}}
                    <div class="prof-card" style="margin-bottom:0;">
                        <div class="prof-card-head">
                            <div class="head-title-wrap">
                                <div class="head-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <line x1="20" y1="8" x2="20" y2="14"></line>
                                        <line x1="23" y1="11" x2="17" y2="11"></line>
                                    </svg>
                                </div>
                                <span>Tambah 1 Tamu Undangan</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('creator.wedding.guest.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Nama Tamu Undangan *</label>
                                    <input type="text" name="name" placeholder="Bpk. Ir. Joko &amp; Keluarga"
                                        class="form-input" required>
                                </div>
                                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.75rem;">
                                    <div class="form-group">
                                        <label class="form-label">Nomor WhatsApp</label>
                                        <input type="text" name="phone" placeholder="08123456789" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Jumlah Pax</label>
                                        <input type="number" name="pax" value="2" min="1" max="20" class="form-input">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Grup / Kategori</label>
                                    <input type="text" name="group_name" placeholder="Keluarga / Sahabat / Kantor"
                                        class="form-input">
                                </div>
                                <button type="submit" class="wb-btn-primary" style="width:100%; justify-content:center;">
                                    Simpan Tamu
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Import via CSV / Excel --}}
                    <div class="prof-card" style="margin-bottom:0;">
                        <div class="prof-card-head">
                            <div class="head-title-wrap">
                                <div class="head-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="12" y1="18" x2="12" y2="12"></line>
                                        <line x1="9" y1="15" x2="15" y2="15"></line>
                                    </svg>
                                </div>
                                <span>Import via CSV / Excel</span>
                            </div>
                            <a href="data:text/csv;charset=utf-8,Nama,No_WhatsApp,Pax,Grup%0ABpk.+Rahmat+%26+Rekan,081234567890,2,VIP%0ASiti+Nurhaliza%2C+M.Pd,085678901234,1,Keluarga%0ADimas+%26+Partner,081987654321,2,Teman+Kantor"
                                download="contoh_format_tamu_undangan.csv"
                                style="font-size:0.75rem; font-weight:700; color:#166534; background:#dcfce7; padding:0.35rem 0.75rem; border-radius:8px; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2"
                                    viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Unduh Template
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('creator.wedding.guest.import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Pilih File Spreadsheet (CSV / Excel)</label>
                                    <input type="file" name="file" accept=".csv, .xlsx, .xls, .txt" class="form-input"
                                        style="padding-top:0.4rem;">
                                    <span class="form-hint">Sistem otomatis mendeteksi kolom: <strong>Nama</strong>,
                                        <strong>No WhatsApp</strong>, <strong>Pax</strong>, dan
                                        <strong>Grup</strong>.</span>
                                </div>

                                {{-- Contoh Format Tabel Visual --}}
                                <div
                                    style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem; margin-bottom:1rem;">
                                    <div
                                        style="font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:0.5rem; display:flex; align-items:center; gap:0.35rem;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="16" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                        </svg>
                                        Contoh Struktur Kolom File Spreadsheet:
                                    </div>
                                    <div style="overflow-x:auto;">
                                        <table
                                            style="width:100%; border-collapse:collapse; font-size:0.72rem; text-align:left;">
                                            <thead>
                                                <tr style="background:#e2e8f0; color:#334155;">
                                                    <th style="padding:4px 6px; border:1px solid #cbd5e1;">Nama (Wajib)</th>
                                                    <th style="padding:4px 6px; border:1px solid #cbd5e1;">No WhatsApp</th>
                                                    <th
                                                        style="padding:4px 6px; border:1px solid #cbd5e1; text-align:center;">
                                                        Pax</th>
                                                    <th style="padding:4px 6px; border:1px solid #cbd5e1;">Grup / Kategori
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="padding:4px 6px; border:1px solid #e2e8f0; font-weight:600;">
                                                        Bpk. Rahmat &amp; Rekan</td>
                                                    <td style="padding:4px 6px; border:1px solid #e2e8f0;">081234567890</td>
                                                    <td
                                                        style="padding:4px 6px; border:1px solid #e2e8f0; text-align:center;">
                                                        2</td>
                                                    <td style="padding:4px 6px; border:1px solid #e2e8f0;">VIP</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:4px 6px; border:1px solid #e2e8f0; font-weight:600;">
                                                        Siti Nurhaliza, M.Pd</td>
                                                    <td style="padding:4px 6px; border:1px solid #e2e8f0;">085678901234</td>
                                                    <td
                                                        style="padding:4px 6px; border:1px solid #e2e8f0; text-align:center;">
                                                        1</td>
                                                    <td style="padding:4px 6px; border:1px solid #e2e8f0;">Keluarga</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:4px 6px; border:1px solid #e2e8f0; font-weight:600;">
                                                        Dimas &amp; Partner</td>
                                                    <td style="padding:4px 6px; border:1px solid #e2e8f0;">081987654321</td>
                                                    <td
                                                        style="padding:4px 6px; border:1px solid #e2e8f0; text-align:center;">
                                                        2</td>
                                                    <td style="padding:4px 6px; border:1px solid #e2e8f0;">Teman Kantor</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="font-size:0.68rem; color:#64748b; margin-top:0.4rem; line-height:1.3;">
                                        * Pax otomatis 1 jika tak diisi. Format nomor mendukung awalan 08 atau 62.
                                    </div>
                                </div>

                                <button type="submit" class="wb-btn-primary" style="width:100%; justify-content:center;">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3"
                                        viewBox="0 0 24 24">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Impor Tamu via CSV / Excel
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

                {{-- Tabel Seluruh Tamu Undangan --}}
                <div class="prof-card">
                    <div class="prof-card-head">
                        <div class="head-title-wrap">
                            <div class="head-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <span>Daftar Seluruh Tamu Undangan ({{ $totalGuests }})</span>
                        </div>
                        <div>
                            <form method="GET" action="{{ route('creator.wedding.index') }}"
                                style="display:flex; gap:0.4rem;">
                                <input type="hidden" name="tab" value="tab-guests">
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari nama atau telepon..." class="form-input"
                                    style="height:34px; font-size:0.75rem; width:180px;">
                                <button type="submit" class="wb-btn-primary"
                                    style="padding:0 0.75rem; height:34px; font-size:0.75rem;">Cari</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body" style="padding:0;">
                        <div class="data-table-wrap" style="border:none;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama Tamu</th>
                                        <th>Grup</th>
                                        <th>Pax</th>
                                        <th>Status Kehadiran</th>
                                        <th>Kode QR</th>
                                        <th style="text-align:right;">Aksi &amp; WhatsApp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($guests as $guest)
                                        @php
                                            $guestInviteUrl = route('wedding.show', ['slug' => $wedding->slug, 'to' => $guest->name, 'code' => $guest->qr_code]);
                                            $waText = "Halo {$guest->name},\n\nTanpa mengurangi rasa hormat, kami mengundang Bapak/Ibu/Saudara/i untuk hadir di acara pernikahan kami:\n\n*{$wedding->groom_nickname} & {$wedding->bride_nickname}*\n\nBuka tautan undangan berikut:\n{$guestInviteUrl}\n\nKode Masuk QR Anda: {$guest->qr_code}\n\nMerupakan suatu kehormatan dan kebahagiaan bagi kami apabila berkenan hadir.\n\nTerima kasih!";
                                            $waUrl = "https://api.whatsapp.com/send?" . ($guest->phone ? "phone=" . preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $guest->phone)) . "&" : "") . "text=" . urlencode($waText);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div style="font-weight:700; color:#0f172a;">{{ $guest->name }}</div>
                                                <div style="font-size:0.7rem; color:#64748b;">
                                                    {{ $guest->phone ?: 'Tanpa nomor telepon' }}
                                                </div>
                                            </td>
                                            <td><span
                                                    class="badge-pill warning">{{ $guest->group_name ?: 'Tamu Undangan' }}</span>
                                            </td>
                                            <td>{{ $guest->pax ?: 1 }} pax</td>
                                            <td>
                                                @if($guest->is_checked_in)
                                                    <span class="badge-pill success">Hadir
                                                        ({{ $guest->checked_in_at ? $guest->checked_in_at->format('H:i') : 'Yes' }})</span>
                                                @else
                                                    <span class="badge-pill" style="background:#f1f5f9; color:#64748b;">Belum
                                                        Hadir</span>
                                                @endif
                                            </td>
                                            <td>
                                                <code
                                                    style="font-size:0.75rem; background:#f1f5f9; padding:2px 6px; border-radius:4px; font-weight:700;">{{ $guest->qr_code }}</code>
                                            </td>
                                            <td style="text-align:right; white-space:nowrap;">
                                                <a href="{{ $waUrl }}" target="_blank" class="wb-btn-primary"
                                                    style="background:#25d366; padding:0.35rem 0.65rem; font-size:0.75rem;"
                                                    title="Kirim Undangan WhatsApp">
                                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z" />
                                                    </svg>
                                                    Kirim WA
                                                </a>
                                                <button type="button" onclick="copyGuestLink('{{ $guestInviteUrl }}')"
                                                    class="wb-btn-preview-link"
                                                    style="padding:0.35rem 0.65rem; font-size:0.75rem;" title="Salin Tautan">
                                                    Salin Link
                                                </button>
                                                <form action="{{ route('creator.wedding.guest.destroy', $guest->id) }}"
                                                    method="POST" style="display:inline;"
                                                    onsubmit="return confirm('Hapus tamu ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        style="background:none; border:none; color:#ef4444; cursor:pointer; padding:0.35rem;">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor"
                                                            stroke-width="2" viewBox="0 0 24 24">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path
                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align:center; padding:2rem; color:#94a3b8;">
                                                Belum ada data tamu. Silakan tambah tamu manual atau gunakan bulk import.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div style="padding:1rem;">
                            {{ $guests->links() }}
                        </div>
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════════════════════════════════
            TAB 4: AMPLOP DIGITAL & HADIAH
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane {{ $activeTab === 'tab-envelope' ? 'active' : '' }}" id="tab-envelope">

                {{-- Setup Rekening & QRIS --}}
                <div class="prof-card">
                    <div class="prof-card-head">
                        <div class="head-title-wrap">
                            <div class="head-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    <line x1="2" y1="10" x2="22" y2="10"></line>
                                </svg>
                            </div>
                            <span>Pengaturan Amplop Digital &amp; Donasi</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('creator.wedding.update-envelope') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group"
                                style="background:#f8fafc; padding:1rem; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:1.25rem;">
                                <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer;">
                                    <input type="checkbox" name="digital_envelope_enabled" value="1" {{ $wedding->digital_envelope_enabled ? 'checked' : '' }}
                                        style="width:18px; height:18px; accent-color:#1eb349;">
                                    <div>
                                        <div style="font-weight:700; font-size:0.875rem; color:#0f172a;">Aktifkan Fitur
                                            Amplop Digital</div>
                                        <div style="font-size:0.72rem; color:#64748b;">Tamu undangan dapat mengirimkan
                                            hadiah tanda kasih langsung via transfer bank atau scan QRIS.</div>
                                    </div>
                                </label>
                            </div>

                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem;">
                                <div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Bank</label>
                                        <input type="text" name="bank_name"
                                            value="{{ old('bank_name', $wedding->bank_name) }}"
                                            placeholder="Contoh: BCA / Bank Mandiri / BRI" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nomor Rekening</label>
                                        <input type="text" name="bank_account_number"
                                            value="{{ old('bank_account_number', $wedding->bank_account_number) }}"
                                            placeholder="Contoh: 1234567890" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Atas Nama Pemilik Rekening</label>
                                        <input type="text" name="bank_account_name"
                                            value="{{ old('bank_account_name', $wedding->bank_account_name) }}"
                                            placeholder="Contoh: Rian Pratama" class="form-input">
                                    </div>
                                </div>

                                <div>
                                    <div class="form-group">
                                        <label class="form-label">Unggah Gambar QRIS (JPG / PNG)</label>
                                        <input type="file" name="qris_image" accept="image/*" class="form-input"
                                            style="padding-top:0.4rem;">
                                        <span class="form-hint">Tamu dapat men-scan kode QRIS ini menggunakan aplikasi
                                            mobile banking atau e-wallet apa saja.</span>
                                    </div>
                                    @if($wedding->qris_image)
                                        <div
                                            style="margin-top:0.5rem; text-align:center; background:#f8fafc; padding:0.75rem; border-radius:12px; border:1px solid #e2e8f0;">
                                            <div
                                                style="font-size:0.75rem; font-weight:700; color:#64748b; margin-bottom:0.4rem;">
                                                Pratinjau QRIS Aktif:</div>
                                            <img src="{{ asset('storage/' . $wedding->qris_image) }}" alt="QRIS"
                                                style="max-height:140px; border-radius:8px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div style="display:flex; justify-content:flex-end; margin-top:1.25rem;">
                                <button type="submit" class="wb-btn-primary">
                                    Simpan Pengaturan Amplop
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Riwayat Amplop Digital Masuk --}}
                <div class="prof-card">
                    <div class="prof-card-head">
                        <div class="head-title-wrap">
                            <div class="head-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                            <span>Riwayat Amplop Digital Masuk (Total: Rp
                                {{ number_format($totalGifts, 0, ',', '.') }})</span>
                        </div>
                    </div>
                    <div class="card-body" style="padding:0;">
                        <div class="data-table-wrap" style="border:none;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama Pengirim</th>
                                        <th>Nominal</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                        <th>Pesan Tanda Kasih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($gifts as $gift)
                                        <tr>
                                            <td><strong>{{ $gift->sender_name }}</strong></td>
                                            <td><span style="font-weight:800; color:#166534;">Rp
                                                    {{ number_format($gift->amount, 0, ',', '.') }}</span></td>
                                            <td>{{ strtoupper($gift->payment_method ?: 'TRANSFER') }}</td>
                                            <td>
                                                @if($gift->payment_status === 'settlement')
                                                    <span class="badge-pill success">Berhasil</span>
                                                @else
                                                    <span class="badge-pill warning">{{ ucfirst($gift->payment_status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $gift->created_at->format('d/m/Y H:i') }}</td>
                                            <td style="color:#64748b; font-style:italic;">"{{ $gift->message ?: '-' }}"</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align:center; padding:2rem; color:#94a3b8;">
                                                Belum ada data amplop digital yang masuk.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════════════════════════════════
            TAB 5: DOA & INSTASTORY GENERATOR
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane {{ $activeTab === 'tab-wishes' ? 'active' : '' }}" id="tab-wishes">

                <div class="prof-card">
                    <div class="prof-card-head">
                        <div class="head-title-wrap">
                            <div class="head-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </div>
                            <span>Doa &amp; Ucapan Tamu (InstaStory Card Generator)</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:1rem;">
                            @forelse($wishes as $wish)
                                <div
                                    style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:16px; padding:1.25rem; display:flex; flex-direction:column; justify-content:space-between; box-shadow:0 2px 6px rgba(0,0,0,0.02);">
                                    <div>
                                        <div
                                            style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.5rem;">
                                            <div>
                                                <div style="font-weight:800; font-size:0.9rem; color:#0f172a;">
                                                    {{ $wish->sender_name }}
                                                </div>
                                                <div style="font-size:0.7rem; color:#64748b;">
                                                    {{ $wish->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                            <span
                                                class="badge-pill {{ $wish->attendance_status === 'hadir' ? 'success' : 'warning' }}">
                                                {{ ucfirst($wish->attendance_status) }}
                                            </span>
                                        </div>
                                        <p
                                            style="font-size:0.825rem; color:#334155; line-height:1.5; font-style:italic; margin:0.75rem 0;">
                                            "{{ $wish->message }}"
                                        </p>
                                    </div>
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f1f5f9; padding-top:0.75rem; margin-top:0.75rem;">
                                        <button type="button"
                                            onclick="generateStoryCard('{{ addslashes($wish->sender_name) }}', '{{ addslashes($wish->message) }}', '{{ $wedding->groom_nickname }} & {{ $wedding->bride_nickname }}')"
                                            class="wb-btn-primary"
                                            style="padding:0.4rem 0.85rem; font-size:0.75rem; background:linear-gradient(135deg, #e1306c 0%, #f77737 100%);">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                            </svg>
                                            Buat Story Card (9:16)
                                        </button>
                                        <form action="{{ route('creator.wedding.wish.destroy', $wish->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus ucapan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background:none; border:none; color:#ef4444; font-size:0.75rem; cursor:pointer; font-weight:600;">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div style="grid-column: 1 / -1; text-align:center; padding:3rem; color:#94a3b8;">
                                    Belum ada ucapan doa yang dikirimkan oleh tamu undangan.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════════════════════════════════
            TAB 6: PHOTOBOOTH BARCODE & FRAME GENERATOR
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane {{ $activeTab === 'tab-photobooth' ? 'active' : '' }}" id="tab-photobooth">
                @if($wedding)

                {{-- Photobooth Hero Card --}}
                <div class="prof-card" style="margin-bottom:1.25rem; overflow:hidden; position:relative;">
                    <div style="position:absolute; top:0; right:0; width:300px; height:100%; background:linear-gradient(135deg, rgba(30,179,73,0.08) 0%, rgba(165,207,55,0.04) 100%); pointer-events:none;"></div>
                    <div class="card-body" style="padding:1.5rem;">
                        <div style="display:flex; align-items:flex-start; gap:1.25rem; flex-wrap:wrap;">
                            {{-- Camera Icon --}}
                            <div style="flex-shrink:0; width:56px; height:56px; background:linear-gradient(135deg,#1eb349,#a5cf37); border-radius:16px; display:flex; align-items:center; justify-content:center; box-shadow:0 8px 24px rgba(30,179,73,0.3);">
                                <svg width="26" height="26" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                            </div>
                            <div style="flex:1; min-width:220px;">
                                <div style="font-size:1.1rem; font-weight:800; color:#0f172a; margin-bottom:0.25rem; font-family:'Montserrat',sans-serif;">Photobooth Live Camera</div>
                                <p style="font-size:0.82rem; color:#64748b; line-height:1.6; margin-bottom:1rem;">Tamu scan QR barcode di venue, kamera langsung terbuka, pilih frame pernikahan eksklusif, jepret foto, dan share ke Instagram Story secara instan — tanpa install aplikasi.</p>

                                <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                                    {{-- Open Photobooth --}}
                                    <a href="{{ $photoboothUrl }}" target="_blank"
                                        style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1.1rem; background:linear-gradient(135deg,#1eb349,#a5cf37); color:#fff; border-radius:10px; font-size:0.8rem; font-weight:700; text-decoration:none; box-shadow:0 4px 12px rgba(30,179,73,0.3); font-family:'Montserrat',sans-serif;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                        Preview Photobooth
                                    </a>
                                    {{-- Copy Link --}}
                                    <button type="button" onclick="copyGuestLink('{{ $photoboothUrl }}')"
                                        style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1.1rem; background:#f8fafc; border:1.5px solid #e2e8f0; color:#334155; border-radius:10px; font-size:0.8rem; font-weight:700; cursor:pointer; font-family:'Montserrat',sans-serif;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
                                        Salin Link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- QR Code Stand Banner & Info --}}
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem;" class="photobooth-grid">

                    {{-- QR Code Stand --}}
                    <div class="prof-card">
                        <div class="prof-card-head">
                            <div class="head-title-wrap">
                                <div class="head-icon" style="background:#eff6ff; color:#3b82f6;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                                    </svg>
                                </div>
                                <span>QR Code Barcode Stand</span>
                            </div>
                        </div>
                        <div class="card-body" style="text-align:center; padding:1.5rem 1.25rem;">
                            <div style="font-size:0.78rem; color:#64748b; margin-bottom:1rem; line-height:1.5;">
                                Print QR Code ini sebagai stand di venue. Tamu scan untuk langsung buka photobooth tanpa ketik link.
                            </div>
                            {{-- QR Code (pakai Google Chart API, no JS library needed) --}}
                            @php
                                $pbUrl = route('wedding.photobooth', $wedding->slug);
                                $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&format=png&ecc=M&data=' . urlencode($pbUrl);
                            @endphp
                            <div style="display:inline-block; padding:12px; background:#ffffff; border:2px solid #e2e8f0; border-radius:16px; box-shadow:0 4px 16px rgba(0,0,0,0.06);">
                                <img src="{{ $qrApiUrl }}" alt="QR Photobooth" width="200" height="200" style="display:block; border-radius:8px;" loading="lazy">
                            </div>
                            <div style="margin-top:1rem;">
                                <code style="font-size:0.65rem; color:#64748b; word-break:break-all; line-height:1.4; display:block;">{{ $pbUrl }}</code>
                            </div>
                            {{-- Download QR --}}
                            <a href="{{ $qrApiUrl }}" download="QR_Photobooth_{{ $wedding->slug }}.png" target="_blank"
                                style="display:inline-flex; align-items:center; gap:0.4rem; margin-top:0.85rem; padding:0.45rem 1rem; background:#eff6ff; color:#1d4ed8; border:1.5px solid #bfdbfe; border-radius:8px; font-size:0.78rem; font-weight:700; text-decoration:none;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Unduh QR Code (PNG)
                            </a>
                        </div>
                    </div>

                    {{-- Frame Preview Cards --}}
                    <div class="prof-card">
                        <div class="prof-card-head">
                            <div class="head-title-wrap">
                                <div class="head-icon" style="background:#fdf4ff; color:#a855f7;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="18" height="18" rx="3"></rect>
                                        <circle cx="12" cy="10" r="3"></circle>
                                        <path d="M3 20l4-4 2 2 4-4 5 6"></path>
                                    </svg>
                                </div>
                                <span>Template Frame Tersedia (9:16)</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">

                                {{-- Frame 1: Classic Gold --}}
                                <div style="border:1.5px solid #fde68a; border-radius:12px; overflow:hidden; aspect-ratio:9/16; position:relative; background:linear-gradient(160deg,#0f172a 0%, #1e293b 50%, #0b1215 100%);">
                                    <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:12px; gap:4px;">
                                        <div style="width:100%; height:100%; border:2px solid rgba(251,191,36,0.6); border-radius:6px; position:absolute; inset:4px; width:calc(100% - 8px); height:calc(100% - 8px);"></div>
                                        <div style="font-size:0.45rem; font-weight:800; color:#fbbf24; letter-spacing:1px; text-align:center; position:relative; z-index:1;">WEDDING</div>
                                        <div style="font-size:0.6rem; font-weight:800; color:#ffffff; text-align:center; font-family:serif; position:relative; z-index:1;">Ilham & Buyle</div>
                                    </div>
                                    <div style="position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,0.5); padding:4px; text-align:center;">
                                        <span style="font-size:0.55rem; color:#fbbf24; font-weight:700;">Classic Gold</span>
                                    </div>
                                </div>

                                {{-- Frame 2: Floral Romance --}}
                                <div style="border:1.5px solid #fecdd3; border-radius:12px; overflow:hidden; aspect-ratio:9/16; position:relative; background:linear-gradient(160deg,#1e0a12 0%, #2d1228 100%);">
                                    <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:12px; gap:4px;">
                                        <div style="font-size:0.45rem; font-weight:700; color:#fda4af; font-style:italic; text-align:center; position:relative; z-index:1;">Happy Ever After</div>
                                        <div style="font-size:0.6rem; font-weight:800; color:#ffffff; text-align:center; font-family:serif; position:relative; z-index:1;">Ilham & Buyle</div>
                                    </div>
                                    <div style="position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,0.5); padding:4px; text-align:center;">
                                        <span style="font-size:0.55rem; color:#fda4af; font-weight:700;">Floral Romance</span>
                                    </div>
                                </div>

                                {{-- Frame 3: Modern Arch --}}
                                <div style="border:1.5px solid #e2e8f0; border-radius:12px; overflow:hidden; aspect-ratio:9/16; position:relative; background:#0f172a;">
                                    <div style="position:absolute; inset:6px; border:2px solid rgba(255,255,255,0.8); border-radius:50% 50% 4px 4px / 30% 30% 4px 4px;"></div>
                                    <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:12px; gap:4px;">
                                        <div style="font-size:0.6rem; font-weight:800; color:#ffffff; text-align:center; font-family:serif; position:relative; z-index:1;">Ilham & Buyle</div>
                                    </div>
                                    <div style="position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,0.5); padding:4px; text-align:center;">
                                        <span style="font-size:0.55rem; color:#ffffff; font-weight:700;">Minimalist Arch</span>
                                    </div>
                                </div>

                                {{-- Frame 4: Polaroid --}}
                                <div style="border:2px solid #ffffff; border-radius:12px; overflow:hidden; aspect-ratio:9/16; position:relative; background:#1a1a2e;">
                                    <div style="position:absolute; bottom:0; left:0; right:0; height:30%; background:#ffffff; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:2px;">
                                        <div style="font-size:0.6rem; font-weight:800; color:#0f172a; text-align:center;">Ilham & Buyle</div>
                                        <div style="font-size:0.4rem; color:#64748b; letter-spacing:1px;">PHOTOBOOTH</div>
                                    </div>
                                    <div style="position:absolute; top:0; left:0; right:0; bottom:30%; display:flex; align-items:center; justify-content:center;">
                                        <div style="width:60%; height:60%; border:1px solid rgba(255,255,255,0.3); border-radius:4px;"></div>
                                    </div>
                                    <div style="position:absolute; top:4px; left:0; right:0; text-align:center;">
                                        <span style="font-size:0.5rem; color:#94a3b8; font-weight:700;">Polaroid Strip</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Custom Frame Upload Section ──────────────────────────── --}}
                <div class="prof-card" style="margin-top:1.25rem;">
                    <div class="prof-card-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                        <div class="head-title-wrap">
                            <div class="head-icon" style="background:#fdf4ff; color:#a855f7;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                            </div>
                            <div>
                                <span style="font-weight:800; color:#0f172a;">Upload Frame Custom (9:16 PNG/WebP)</span>
                                <div style="font-size:0.72rem; color:#64748b; margin-top:1px;">Buat desain frame InstaStory di Canva / Photoshop → export PNG → upload di sini. Tamu bisa pilih frame bawaan atau frame custom Anda. Maks 6 frame.</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Error / Success Flash --}}
                        @if(session('error'))
                            <div style="background:#fee2e2; border:1.5px solid #fca5a5; color:#991b1b; padding:0.75rem 1rem; border-radius:10px; font-size:0.82rem; font-weight:600; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                {{ session('error') }}
                            </div>
                        @endif

                        @php
                            $customFrames = $wedding->data_json['photobooth_frames'] ?? [];
                        @endphp

                        {{-- Galeri Custom Frame yang Sudah Diupload --}}
                        @if(count($customFrames) > 0)
                            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:0.85rem; margin-bottom:1.25rem;">
                                @foreach($customFrames as $idx => $framePath)
                                    <div style="position:relative; border-radius:12px; overflow:hidden; aspect-ratio:9/16; background:#0f172a; border:2px solid #a855f7; box-shadow:0 4px 16px rgba(168,85,247,0.2);">
                                        <img src="{{ Storage::url($framePath) }}" alt="Custom Frame {{ $idx + 1 }}"
                                            style="width:100%; height:100%; object-fit:cover; display:block;">
                                        {{-- Badge Custom --}}
                                        <div style="position:absolute; top:6px; left:6px; background:#a855f7; color:#fff; font-size:0.55rem; font-weight:800; padding:2px 7px; border-radius:99px; letter-spacing:0.5px; text-transform:uppercase;">CUSTOM</div>
                                        {{-- Delete Button --}}
                                        <form action="{{ route('creator.wedding.photobooth-frame.delete', $idx) }}?w={{ $wedding->id }}" method="POST"
                                              onsubmit="return confirm('Hapus frame custom ini?');"
                                              style="position:absolute; top:6px; right:6px;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="width:26px; height:26px; border-radius:50%; background:rgba(239,68,68,0.9); border:none; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Upload Form --}}
                        @if(count($customFrames) < 6)
                            <form action="{{ route('creator.wedding.photobooth-frame.upload') }}?w={{ $wedding->id }}"
                                  method="POST" enctype="multipart/form-data"
                                  id="frameUploadForm">
                                @csrf
                                <div id="frameDropZone"
                                    style="border:2px dashed #c084fc; border-radius:14px; padding:2rem; text-align:center; background:#fdf4ff; cursor:pointer; transition:all 0.2s ease; position:relative;"
                                    onclick="document.getElementById('frameFileInput').click()"
                                    ondragover="event.preventDefault(); this.style.borderColor='#9333ea'; this.style.background='#f3e8ff';"
                                    ondragleave="this.style.borderColor='#c084fc'; this.style.background='#fdf4ff';"
                                    ondrop="handleFrameDrop(event)">
                                    <input type="file" id="frameFileInput" name="frame" accept="image/png,image/webp,image/jpeg"
                                        style="display:none;" onchange="previewFrameUpload(event)">

                                    <div id="frameDropContent">
                                        <div style="width:44px; height:44px; border-radius:50%; background:#e9d5ff; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem;">
                                            <svg width="22" height="22" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="17 8 12 3 7 8"></polyline>
                                                <line x1="12" y1="3" x2="12" y2="15"></line>
                                            </svg>
                                        </div>
                                        <div style="font-size:0.85rem; font-weight:800; color:#581c87; margin-bottom:0.25rem;">Drag & Drop Frame PNG di sini</div>
                                        <div style="font-size:0.75rem; color:#7c3aed; margin-bottom:0.75rem;">atau klik untuk pilih file</div>
                                        <div style="font-size:0.7rem; color:#94a3b8; line-height:1.4;">
                                            Format: PNG / WebP / JPG &bull; Rasio: 9:16 (InstaStory) &bull; Maks: 4 MB<br>
                                            Tersisa <strong>{{ 6 - count($customFrames) }}</strong> slot dari 6 frame custom
                                        </div>
                                    </div>

                                    <div id="framePreviewWrap" style="display:none;">
                                        <img id="framePreviewImg" src="" alt="Preview"
                                            style="max-height:200px; border-radius:10px; margin:0 auto 0.75rem; display:block; box-shadow:0 4px 16px rgba(0,0,0,0.1);">
                                        <div id="framePreviewName" style="font-size:0.78rem; font-weight:700; color:#581c87; margin-bottom:0.75rem;"></div>
                                    </div>
                                </div>

                                {{-- Upload Submit Button (hidden until file selected) --}}
                                <div id="frameUploadActions" style="display:none; margin-top:0.85rem; gap:0.65rem; flex-wrap:wrap;">
                                    <button type="submit"
                                        style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.55rem 1.25rem; background:linear-gradient(135deg,#9333ea,#7c3aed); color:#fff; border:none; border-radius:10px; font-size:0.82rem; font-weight:700; cursor:pointer; font-family:'Montserrat',sans-serif; box-shadow:0 4px 12px rgba(124,58,237,0.3);">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                        </svg>
                                        Upload Frame Sekarang
                                    </button>
                                    <button type="button" onclick="resetFrameUpload()"
                                        style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.55rem 0.9rem; background:#f1f5f9; border:1.5px solid #e2e8f0; color:#475569; border-radius:10px; font-size:0.8rem; font-weight:600; cursor:pointer;">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        @else
                            <div style="text-align:center; padding:1.5rem; background:#f8fafc; border-radius:12px; color:#64748b; font-size:0.82rem; font-weight:600; border:1.5px dashed #e2e8f0;">
                                <svg width="24" height="24" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24" style="margin:0 auto 0.5rem; display:block;"><circle cx="12" cy="12" r="10"></circle><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Slot custom frame sudah penuh (6/6). Hapus salah satu untuk menambah frame baru.
                            </div>
                        @endif

                        {{-- Panduan Desain Frame --}}
                        <div style="margin-top:1rem; padding:0.85rem 1rem; background:#fffbeb; border:1px solid #fde68a; border-radius:10px;">
                            <div style="font-size:0.78rem; font-weight:700; color:#92400e; margin-bottom:0.4rem; display:flex; align-items:center; gap:0.4rem;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                Panduan Membuat Frame Custom di Canva:
                            </div>
                            <ol style="font-size:0.74rem; color:#78350f; line-height:1.7; margin:0; padding-left:1.25rem;">
                                <li>Buka Canva → pilih template <strong>Instagram Story (1080 x 1920 px)</strong></li>
                                <li>Desain frame dengan area tengah <strong>transparan</strong> (lubang untuk foto kamera)</li>
                                <li>Tambahkan ornamen, teks nama pasangan, tanggal, dll. di pinggiran frame</li>
                                <li>Download sebagai <strong>PNG dengan background transparan</strong></li>
                                <li>Upload di sini — frame langsung muncul sebagai pilihan tamu di photobooth</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- How it Works Steps --}}
                <div class="prof-card" style="margin-top:1.25rem;">
                    <div class="prof-card-head">
                        <div class="head-title-wrap">
                            <div class="head-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </div>
                            <span>Cara Kerja Photobooth di Venue</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem;">
                            @php
                                $pbSteps = [
                                    ['num'=>'1', 'color'=>'#eff6ff', 'stroke'=>'#3b82f6', 'icon'=>'<rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect>', 'title'=>'Scan QR di Venue', 'desc'=>'Panitia pasang stand QR code di pintu masuk atau meja resepsi'],
                                    ['num'=>'2', 'color'=>'#fdf4ff', 'stroke'=>'#a855f7', 'icon'=>'<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle>', 'title'=>'Kamera Terbuka', 'desc'=>'Browser langsung mengaktifkan kamera depan smartphone tamu'],
                                    ['num'=>'3', 'color'=>'#fff7ed', 'stroke'=>'#f97316', 'icon'=>'<rect x="3" y="3" width="18" height="18" rx="3"></rect><circle cx="12" cy="10" r="3"></circle><path d="M3 20l4-4 2 2 4-4 5 6"></path>', 'title'=>'Pilih Frame & Jepret', 'desc'=>'Tamu pilih template bawaan atau frame custom yang Anda buat'],
                                    ['num'=>'4', 'color'=>'#f0fdf4', 'stroke'=>'#22c55e', 'icon'=>'<rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>', 'title'=>'Share ke InstaStory', 'desc'=>'Foto langsung dapat didownload atau share ke Instagram Story'],
                                ];
                            @endphp
                            @foreach($pbSteps as $step)
                                <div style="display:flex; flex-direction:column; align-items:flex-start; gap:0.5rem; padding:1rem; background:{{ $step['color'] }}; border-radius:12px; border:1px solid {{ $step['stroke'] }}20;">
                                    <div style="width:32px; height:32px; border-radius:50%; background:{{ $step['stroke'] }}22; display:flex; align-items:center; justify-content:center;">
                                        <svg width="16" height="16" fill="none" stroke="{{ $step['stroke'] }}" stroke-width="2" viewBox="0 0 24 24">{!! $step['icon'] !!}</svg>
                                    </div>
                                    <div style="font-size:0.8rem; font-weight:800; color:#0f172a;">{{ $step['num'] }}. {{ $step['title'] }}</div>
                                    <div style="font-size:0.74rem; color:#475569; line-height:1.4;">{{ $step['desc'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @else
                <div style="text-align:center; padding:4rem 2rem; color:#94a3b8;">
                    <svg width="48" height="48" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 1rem;">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                    <div style="font-size:0.9rem; font-weight:700; color:#64748b;">Buat pasangan pernikahan terlebih dahulu</div>
                    <div style="font-size:0.78rem; margin-top:0.25rem;">Photobooth tersedia setelah Anda membuat dan mengaktifkan pasangan.</div>
                </div>
                @endif
            </div>

            {{-- ═══════════════════════════════════════════════════════════
            TAB 7: PETA INTERAKTIF SEBARAN TAMU & RSVP BERDASARKAN KOTA
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane {{ $activeTab === 'tab-map' ? 'active' : '' }}" id="tab-map">
                <div class="prof-card" style="margin-bottom:1.25rem;">
                    <div class="prof-card-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                        <div class="head-title-wrap">
                            <div class="head-icon" style="background:#ecfdf5; color:#10b981;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                    <line x1="8" y1="2" x2="8" y2="18"></line>
                                    <line x1="16" y1="6" x2="16" y2="22"></line>
                                </svg>
                            </div>
                            <div>
                                <span style="font-size:0.95rem; font-weight:800; color:#0f172a;">Peta Interaktif Sebaran Tamu &amp; RSVP</span>
                                <div style="font-size:0.75rem; color:#64748b; font-weight:500;">Pantau persebaran domisili &amp; kehadiran tamu undangan dari berbagai kota di Indonesia</div>
                            </div>
                        </div>
                        <button type="button" onclick="resetMapToIndonesia()" class="wb-btn-secondary" style="font-size:0.75rem; padding:0.4rem 0.85rem; border-radius:8px; display:inline-flex; align-items:center; gap:0.35rem;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            Reset Peta Indonesia
                        </button>
                    </div>

                    <div class="card-body">
                        {{-- Ringkasan Metrik Sebaran --}}
                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:0.75rem; margin-bottom:1.25rem;">
                            <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:0.75rem 1rem;">
                                <div style="font-size:0.7rem; color:#64748b; font-weight:700; text-transform:uppercase;">Total RSVP</div>
                                <div style="font-size:1.35rem; font-weight:800; color:#0f172a;">{{ $mapGuests->count() }}</div>
                            </div>
                            <div style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:0.75rem 1rem;">
                                <div style="font-size:0.7rem; color:#166534; font-weight:700; text-transform:uppercase; display:flex; align-items:center; gap:0.3rem;">
                                    <svg width="12" height="12" fill="none" stroke="#166534" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    Konfirmasi Hadir
                                </div>
                                <div style="font-size:1.35rem; font-weight:800; color:#166534;">{{ $mapGuests->where('status', 'hadir')->count() }}</div>
                            </div>
                            <div style="background:#fef2f2; border:1.5px solid #fecaca; border-radius:12px; padding:0.75rem 1rem;">
                                <div style="font-size:0.7rem; color:#991b1b; font-weight:700; text-transform:uppercase; display:flex; align-items:center; gap:0.3rem;">
                                    <svg width="12" height="12" fill="none" stroke="#991b1b" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Tidak Hadir
                                </div>
                                <div style="font-size:1.35rem; font-weight:800; color:#991b1b;">{{ $mapGuests->where('status', 'tidak_hadir')->count() }}</div>
                            </div>
                            <div style="background:#fffbeb; border:1.5px solid #fde68a; border-radius:12px; padding:0.75rem 1rem;">
                                <div style="font-size:0.7rem; color:#92400e; font-weight:700; text-transform:uppercase; display:flex; align-items:center; gap:0.3rem;">
                                    <svg width="12" height="12" fill="none" stroke="#92400e" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    Ragu-Ragu
                                </div>
                                <div style="font-size:1.35rem; font-weight:800; color:#92400e;">{{ $mapGuests->where('status', 'ragu')->count() }}</div>
                            </div>
                            <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:0.75rem 1rem;">
                                <div style="font-size:0.7rem; color:#475569; font-weight:700; text-transform:uppercase; display:flex; align-items:center; gap:0.3rem;">
                                    <svg width="12" height="12" fill="none" stroke="#475569" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    Kota Terdaftar
                                </div>
                                <div style="font-size:1.35rem; font-weight:800; color:#0f172a;">{{ $cityStats->count() }}</div>
                            </div>
                        </div>

                        {{-- Filter Status di Atas Peta --}}
                        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.75rem;">
                            <div style="display:flex; align-items:center; gap:0.4rem; flex-wrap:wrap;">
                                <span style="font-size:0.75rem; font-weight:700; color:#475569; margin-right:0.25rem;">Filter Pin:</span>
                                <button type="button" onclick="filterMapMarkers('all')" class="map-filter-btn active" data-filter="all">Semua ({{ $mapGuests->count() }})</button>
                                <button type="button" onclick="filterMapMarkers('hadir')" class="map-filter-btn" data-filter="hadir">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;"><polyline points="20 6 9 17 4 12"/></svg>
                                    Hadir ({{ $mapGuests->where('status', 'hadir')->count() }})
                                </button>
                                <button type="button" onclick="filterMapMarkers('tidak_hadir')" class="map-filter-btn" data-filter="tidak_hadir">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Tidak Hadir ({{ $mapGuests->where('status', 'tidak_hadir')->count() }})
                                </button>
                                <button type="button" onclick="filterMapMarkers('ragu')" class="map-filter-btn" data-filter="ragu">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    Ragu ({{ $mapGuests->where('status', 'ragu')->count() }})
                                </button>
                            </div>
                            <span style="font-size:0.72rem; color:#64748b; display:inline-flex; align-items:center; gap:0.3rem;">
                                <svg width="13" height="13" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                Klik pin untuk melihat nama tamu &amp; pesan doa
                            </span>
                        </div>

                        {{-- Leaflet Map Container --}}
                        <div style="position:relative; width:100%; height:500px; border-radius:16px; border:1.5px solid #cbd5e1; overflow:hidden; background:#e2e8f0; box-shadow:0 6px 20px rgba(0,0,0,0.06);">
                            <div id="guestDistributionMap" style="width:100%; height:100%;"></div>
                        </div>

                        {{-- Daftar Kota & Sebaran Tamu --}}
                        <div style="margin-top:1.5rem;">
                            <div style="font-size:0.85rem; font-weight:800; color:#0f172a; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.4rem;">
                                <svg width="16" height="16" fill="none" stroke="#10b981" stroke-width="2.3" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Sebaran Tamu per Kota ({{ $cityStats->count() }} Kota)
                            </div>

                            @if($cityStats->count() > 0)
                                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:0.75rem;">
                                    @foreach($cityStats as $cStat)
                                        <div style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:0.9rem; display:flex; align-items:center; justify-content:space-between; transition:all .2s;"
                                             onmouseover="this.style.borderColor='#10b981';this.style.boxShadow='0 4px 12px rgba(16,185,129,0.1)';"
                                             onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
                                            <div>
                                                <div style="font-weight:800; font-size:0.88rem; color:#0f172a; display:flex; align-items:center; gap:0.35rem;">
                                                    <svg width="13" height="13" fill="none" stroke="#10b981" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                    {{ $cStat['city'] }}
                                                </div>
                                                <div style="font-size:0.72rem; color:#64748b; margin-top:3px; display:flex; align-items:center; gap:0.4rem;">
                                                    <span style="color:#166534; font-weight:700;">{{ $cStat['hadir'] }} Hadir</span> &bull;
                                                    <span style="color:#991b1b; font-weight:700;">{{ $cStat['tidak_hadir'] }} Absen</span>
                                                    @if($cStat['ragu'] > 0)
                                                        &bull; <span style="color:#92400e; font-weight:700;">{{ $cStat['ragu'] }} Ragu</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <button type="button" onclick="focusCity({{ $cStat['lat'] }}, {{ $cStat['lng'] }}, '{{ addslashes($cStat['city']) }}')"
                                                    style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; font-size:0.72rem; font-weight:700; padding:0.35rem 0.65rem; border-radius:8px; cursor:pointer; display:inline-flex; align-items:center; gap:0.25rem;">
                                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                                Zoom
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div style="text-align:center; padding:2rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:14px; color:#64748b;">
                                    <div style="margin-bottom:0.4rem; color:#94a3b8;">
                                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
                                    </div>
                                    <div style="font-weight:700; font-size:0.85rem; color:#334155;">Belum Ada Tamu yang Mengisi RSVP Kota</div>
                                    <p style="font-size:0.75rem; margin:0.3rem 0 0.75rem; color:#94a3b8;">
                                        Ketika tamu membuka undangan dan memilih kota asal (misal: Sidoarjo), pin dan rincian tamu akan otomatis muncul di peta interaktif ini.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Hidden HTML5 Canvas untuk InstaStory Wish Card Generator --}}
    <canvas id="storyCanvas" width="1080" height="1920" style="display:none;"></canvas>

    {{-- Story Modal Preview --}}
    <div id="storyModal"
        style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.8); z-index:99999; align-items:center; justify-content:center; padding:1rem;">
        <div
            style="background:#fff; border-radius:20px; max-width:400px; width:100%; overflow:hidden; box-shadow:0 25px 50px rgba(0,0,0,0.4); text-align:center; padding:1.25rem;">
            <div style="font-weight:800; font-size:1rem; color:#0f172a; margin-bottom:0.75rem;">Kartu InstaStory Siap
                Diunduh!</div>
            <img id="storyPreviewImg"
                style="width:100%; max-height:480px; object-fit:contain; border-radius:12px; margin-bottom:1rem; box-shadow:0 4px 14px rgba(0,0,0,0.15);">
            <div style="display:flex; gap:0.5rem; justify-content:center;">
                <button type="button" onclick="downloadStoryImg()" class="wb-btn-primary"
                    style="flex:1; justify-content:center;">
                    Download PNG (1080x1920)
                </button>
                <button type="button" onclick="closeStoryModal()" class="wb-btn-danger-outline" style="padding:0 1rem;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        // Tab switching logic (identik dengan Web Builder)
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const targetTab = this.getAttribute('data-tab');
                if (!targetTab) return;

                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

                this.classList.add('active');
                const targetPane = document.getElementById(targetTab);
                if (targetPane) {
                    targetPane.classList.add('active');
                }

                // Invalidate map saat tab peta dibuka
                if (targetTab === 'tab-map') {
                    if (!guestMap) {
                        setTimeout(() => initGuestDistributionMap(), 150);
                    } else {
                        setTimeout(() => guestMap.invalidateSize(), 200);
                    }
                }

                // Update URL parameter tanpa reload
                const url = new URL(window.location);
                url.searchParams.set('tab', targetTab);
                window.history.replaceState({}, '', url);
            });
        });

        // Salin Link Tamu
        function copyGuestLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Tautan undangan tamu berhasil disalin!');
            }).catch(() => {
                prompt('Salin link undangan ini:', url);
            });
        }

        // Quick Check-in dari Dashboard Host
        function submitQuickCheckin() {
            const input = document.getElementById('quickCheckinCode');
            const code = input.value.trim();
            const resBox = document.getElementById('quickCheckinResult');

            if (!code) {
                alert('Silakan masukkan Kode QR atau ID Tamu.');
                return;
            }

            resBox.style.display = 'block';
            resBox.innerHTML = '<span style="color:#0284c7; font-size:0.8rem; font-weight:600;">Memproses check-in...</span>';

            fetch("{{ route('creator.wedding.guest.checkin') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        resBox.innerHTML = `<div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.6rem 1rem; border-radius:10px; font-size:0.8rem; font-weight:700; display:flex; align-items:center; gap:0.4rem;"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> ${data.message} (${data.guest.name} - ${data.guest.pax} Pax)</div>`;
                        input.value = '';
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        resBox.innerHTML = `<div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.6rem 1rem; border-radius:10px; font-size:0.8rem; font-weight:700; display:flex; align-items:center; gap:0.4rem;"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> ${data.message}</div>`;
                    }
                })
                .catch(err => {
                    resBox.innerHTML = `<div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.6rem 1rem; border-radius:10px; font-size:0.8rem; font-weight:700; display:flex; align-items:center; gap:0.4rem;"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Terjadi kesalahan jaringan.</div>`;
                });
        }

        // InstaStory Wish Card Generator (HTML5 Canvas 9:16)
        let generatedStoryDataUrl = null;

        function generateStoryCard(sender, message, couple) {
            const canvas = document.getElementById('storyCanvas');
            const ctx = canvas.getContext('2d');

            // 1. Background Gradient Mewah (Warm Wedding Rose Gold)
            const gradient = ctx.createLinearGradient(0, 0, 1080, 1920);
            gradient.addColorStop(0, '#0f172a');
            gradient.addColorStop(0.5, '#1e293b');
            gradient.addColorStop(1, '#0b1215');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, 1080, 1920);

            // 2. Lingkaran Aksen Glow Emas & Hijau
            ctx.save();
            ctx.fillStyle = 'rgba(217, 119, 6, 0.15)';
            ctx.beginPath();
            ctx.arc(200, 300, 400, 0, Math.PI * 2);
            ctx.fill();

            ctx.fillStyle = 'rgba(30, 179, 73, 0.12)';
            ctx.beginPath();
            ctx.arc(880, 1600, 450, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();

            // 3. Frame Kartu Tengah Elegan (Glassmorphism look)
            ctx.save();
            ctx.fillStyle = 'rgba(255, 255, 255, 0.08)';
            ctx.strokeStyle = 'rgba(217, 119, 6, 0.4)';
            ctx.lineWidth = 4;
            roundRect(ctx, 90, 240, 900, 1440, 32, true, true);
            ctx.restore();

            // 4. Header Badge
            ctx.textAlign = 'center';
            ctx.fillStyle = '#fbbf24';
            ctx.font = 'bold 34px Montserrat, sans-serif';
            ctx.fillText('DOA & UCAPAN PERNIKAHAN', 540, 360);

            // 5. Nama Pasangan Mempelai
            ctx.fillStyle = '#ffffff';
            ctx.font = '800 58px Montserrat, sans-serif';
            ctx.fillText(couple, 540, 450);

            // Garis Pembatas Emas
            ctx.strokeStyle = 'rgba(251, 191, 36, 0.4)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(340, 490);
            ctx.lineTo(740, 490);
            ctx.stroke();

            // 6. Kutipan Ucapan Tamu (Wrap text)
            ctx.fillStyle = '#f1f5f9';
            ctx.font = 'italic 44px Georgia, serif';
            wrapText(ctx, `“${message}”`, 540, 720, 760, 64);

            // 7. Nama Tamu Pengirim
            ctx.fillStyle = '#38bdf8';
            ctx.font = 'bold 42px Montserrat, sans-serif';
            ctx.fillText(`— ${sender}`, 540, 1400);

            ctx.fillStyle = '#94a3b8';
            ctx.font = '28px Montserrat, sans-serif';
            ctx.fillText('Tamu Undangan Terhormat', 540, 1450);

            // 8. Footer Watermark
            ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
            ctx.font = '600 28px Montserrat, sans-serif';
            ctx.fillText('Dibuat dengan Undangan Digital buyle.id', 540, 1800);

            // Tampilkan Modal
            generatedStoryDataUrl = canvas.toDataURL('image/png');
            document.getElementById('storyPreviewImg').src = generatedStoryDataUrl;
            document.getElementById('storyModal').style.display = 'flex';
        }

        function closeStoryModal() {
            document.getElementById('storyModal').style.display = 'none';
        }

        function downloadStoryImg() {
            if (!generatedStoryDataUrl) return;
            const link = document.createElement('a');
            link.download = 'InstaStory_Wish_Card.png';
            link.href = generatedStoryDataUrl;
            link.click();
        }

        // Helper Canvas Round Rectangle
        function roundRect(ctx, x, y, width, height, radius, fill, stroke) {
            ctx.beginPath();
            ctx.moveTo(x + radius, y);
            ctx.lineTo(x + width - radius, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
            ctx.lineTo(x + width, y + height - radius);
            ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
            ctx.lineTo(x + radius, y + height);
            ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
            ctx.lineTo(x, y + radius);
            ctx.quadraticCurveTo(x, y, x + radius, y);
            ctx.closePath();
            if (fill) ctx.fill();
            if (stroke) ctx.stroke();
        }

        // Helper Canvas Wrap Text
        function wrapText(ctx, text, x, y, maxWidth, lineHeight) {
            const words = text.split(' ');
            let line = '';
            for (let n = 0; n < words.length; n++) {
                const testLine = line + words[n] + ' ';
                const metrics = ctx.measureText(testLine);
                const testWidth = metrics.width;
                if (testWidth > maxWidth && n > 0) {
                    ctx.fillText(line, x, y);
                    line = words[n] + ' ';
                    y += lineHeight;
                } else {
                    line = testLine;
                }
            }
            ctx.fillText(line, x, y);
        }
        // Helper GPS Location
        function getCurrentLocationGps() {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung geolokasi.');
                return;
            }
            navigator.geolocation.getCurrentPosition(pos => {
                document.getElementById('inputLat').value = pos.coords.latitude.toFixed(6);
                document.getElementById('inputLng').value = pos.coords.longitude.toFixed(6);
                alert(`Koordinat berhasil diambil: ${pos.coords.latitude.toFixed(6)}, ${pos.coords.longitude.toFixed(6)}`);
            }, err => {
                alert('Gagal mengambil lokasi GPS: ' + err.message);
            });
        }
    </script>

    {{-- ════════════════════════════════════════════════════════════════
         MODAL: TAMBAH PASANGAN BARU + KALKULATOR OTOMATIS + MIDTRANS
    ════════════════════════════════════════════════════════════════ --}}
    <div id="modal-add-couple"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);backdrop-filter:blur(6px);z-index:9000;align-items:center;justify-content:center;padding:1rem;">
        <div style="background:#fff;border-radius:24px;width:100%;max-width:520px;padding:2rem;box-shadow:0 20px 60px rgba(0,0,0,0.2);position:relative;max-height:92vh;overflow-y:auto;">

            {{-- Close Button --}}
            <button onclick="document.getElementById('modal-add-couple').style.display='none'"
                    style="position:absolute;top:1rem;right:1rem;background:#f1f5f9;border:none;border-radius:99px;width:32px;height:32px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>

            {{-- Header --}}
            <div style="text-align:center;margin-bottom:1.5rem;">
                <div style="width:52px;height:52px;background:linear-gradient(135deg,#1eb349,#a5cf37);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
                    <svg width="24" height="24" fill="none" stroke="white" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <h3 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0 0 0.25rem;font-family:'Montserrat',sans-serif;">Tambah Pasangan Baru</h3>
                <p style="font-size:0.82rem;color:#64748b;">Isi data mempelai, pilih kuota undangan, dan selesaikan pembayaran.</p>
            </div>

            {{-- Form --}}
            <div id="form-add-couple">
                {{-- Nama Mempelai --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:0.75rem;">
                    <div>
                        <label style="font-size:0.75rem;font-weight:700;color:#334155;display:block;margin-bottom:0.3rem;">Nama Mempelai Pria*</label>
                        <input type="text" id="inp-groom-name" placeholder="Budi Santoso"
                               style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;padding:0.5rem 0.75rem;font-size:0.85rem;outline:none;box-sizing:border-box;"/>
                    </div>
                    <div>
                        <label style="font-size:0.75rem;font-weight:700;color:#334155;display:block;margin-bottom:0.3rem;">Nama Mempelai Wanita*</label>
                        <input type="text" id="inp-bride-name" placeholder="Sari Dewi"
                               style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;padding:0.5rem 0.75rem;font-size:0.85rem;outline:none;box-sizing:border-box;"/>
                    </div>
                </div>

                {{-- URL Slug --}}
                <div style="margin-bottom:0.75rem;">
                    <label style="font-size:0.75rem;font-weight:700;color:#334155;display:block;margin-bottom:0.3rem;">URL Undangan (Slug)*</label>
                    <div style="display:flex;align-items:center;border:1.5px solid #e2e8f0;border-radius:10px;overflow:hidden;">
                        <span style="background:#f8fafc;padding:0.5rem 0.65rem;font-size:0.75rem;color:#64748b;white-space:nowrap;border-right:1.5px solid #e2e8f0;">buyle.id/wedding/</span>
                        <input type="text" id="inp-slug" placeholder="budi-sari-2025"
                               style="flex:1;border:none;padding:0.5rem 0.75rem;font-size:0.85rem;outline:none;"/>
                    </div>
                    <p style="font-size:0.72rem;color:#94a3b8;margin-top:0.25rem;">Hanya huruf kecil, angka, dan tanda hubung (-).</p>
                </div>

                {{-- Kuota Slider --}}
                <div style="margin-bottom:0.75rem;">
                    <label style="font-size:0.75rem;font-weight:700;color:#334155;display:flex;justify-content:space-between;margin-bottom:0.3rem;">
                        <span>Kuota Undangan (min. 150)</span>
                        <strong id="slider-val" style="color:#1eb349;">150 Tamu</strong>
                    </label>
                    <input type="range" id="inp-guest-limit" min="150" max="2000" step="50" value="150"
                           oninput="updateWeddingPricing(this.value)"
                           style="width:100%;accent-color:#1eb349;cursor:pointer;"/>
                    <div style="display:flex;justify-content:space-between;font-size:0.7rem;color:#94a3b8;margin-top:0.2rem;">
                        <span>150</span><span>500</span><span>1.000</span><span>2.000</span>
                    </div>
                </div>

                {{-- Ringkasan Biaya --}}
                <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:14px;padding:1rem;margin-bottom:1.25rem;">
                    <div style="font-size:0.75rem;font-weight:700;color:#334155;margin-bottom:0.65rem;display:flex;align-items:center;gap:0.35rem;">
                        <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2.2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Ringkasan Biaya
                    </div>
                    <div id="pricing-breakdown">
                        <div style="display:flex;justify-content:space-between;font-size:0.82rem;color:#475569;margin-bottom:0.4rem;">
                            <span>Paket Undangan Digital (150 Tamu)</span>
                            <span>Rp 250.000</span>
                        </div>
                        <div id="extra-row" style="display:none;justify-content:space-between;font-size:0.82rem;color:#475569;margin-bottom:0.4rem;">
                            <span id="extra-label">Extra Kuota</span>
                            <span id="extra-val">Rp 0</span>
                        </div>
                        <hr style="border:none;border-top:1px dashed #e2e8f0;margin:0.6rem 0;"/>
                        <div style="display:flex;justify-content:space-between;font-size:0.95rem;font-weight:800;color:#0f172a;">
                            <span>Total</span>
                            <span id="total-price" style="color:#1eb349;">Rp 250.000</span>
                        </div>
                    </div>
                </div>

                {{-- Error --}}
                <div id="couple-error" style="display:none;background:#fef2f2;border:1.5px solid #fecaca;color:#dc2626;padding:0.65rem 0.85rem;border-radius:10px;font-size:0.82rem;margin-bottom:0.75rem;"></div>

                {{-- Pay Button --}}
                <button id="btn-pay-couple" onclick="submitOrderCouple()"
                        style="width:100%;padding:0.85rem;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;border:none;border-radius:12px;font-size:0.92rem;font-weight:800;cursor:pointer;font-family:'Montserrat',sans-serif;box-shadow:0 4px 15px rgba(30,179,73,0.3);display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    Bayar via Midtrans
                </button>
                <p style="text-align:center;font-size:0.72rem;color:#94a3b8;margin-top:0.6rem;">Mendukung QRIS, GoPay, ShopeePay, VA Bank, dll.</p>
            </div>
        </div>
    </div>

    {{-- Midtrans Snap Script --}}
    <script src="https://app.{{ $midtransEnv }}.midtrans.com/snap/snap.js"
            data-client-key="{{ $midtransClientKeyJs }}"></script>

    <script>
        const WEDDING_ORDER_URL      = "{{ route('creator.wedding.order-couple') }}";
        const WEDDING_SUCCESS_BASE   = "{{ url('/creator/wedding/payment-success') }}";
        const WEDDING_CALCULATE_URL  = "{{ route('creator.wedding.calculate-price') }}";
        const CSRF_TOKEN             = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '';

        // Auto-generate slug from couple names
        ['inp-groom-name', 'inp-bride-name'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', () => {
                const g = document.getElementById('inp-groom-name').value.trim().split(' ')[0].toLowerCase();
                const b = document.getElementById('inp-bride-name').value.trim().split(' ')[0].toLowerCase();
                const yr = new Date().getFullYear();
                document.getElementById('inp-slug').value =
                    [g, b, yr].filter(Boolean).join('-').replace(/[^a-z0-9\-]/g, '');
            });
        });

        function formatRupiah(n) {
            return 'Rp ' + parseInt(n).toLocaleString('id-ID');
        }

        function updateWeddingPricing(val) {
            val = Math.max(150, parseInt(val));
            document.getElementById('slider-val').textContent = val + ' Tamu';
            const base = 250000;
            const extra = Math.max(0, val - 150) * 1000;
            const total = base + extra;
            document.getElementById('total-price').textContent = formatRupiah(total);
            const extraRow = document.getElementById('extra-row');
            if (extra > 0) {
                extraRow.style.display = 'flex';
                document.getElementById('extra-label').textContent = 'Extra ' + (val - 150) + ' Tamu (@Rp 1.000)';
                document.getElementById('extra-val').textContent = formatRupiah(extra);
            } else {
                extraRow.style.display = 'none';
            }
        }

        async function submitOrderCouple() {
            const groom = document.getElementById('inp-groom-name').value.trim();
            const bride = document.getElementById('inp-bride-name').value.trim();
            const slug  = document.getElementById('inp-slug').value.trim();
            const limit = parseInt(document.getElementById('inp-guest-limit').value);
            const errEl = document.getElementById('couple-error');
            const btn   = document.getElementById('btn-pay-couple');

            errEl.style.display = 'none';

            if (!groom || !bride || !slug) {
                errEl.textContent = 'Nama mempelai dan URL wajib diisi.';
                errEl.style.display = 'block';
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" style="animation:spin 1s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memproses...';

            try {
                const res = await fetch(WEDDING_ORDER_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        groom_name:  groom,
                        bride_name:  bride,
                        slug:        slug,
                        guest_limit: limit,
                    }),
                });

                const data = await res.json();

                if (!data.success) {
                    throw new Error(data.message || 'Gagal membuat order.');
                }

                // Tampilkan Midtrans Snap popup
                snap.pay(data.snap_token, {
                    onSuccess: async (result) => {
                        // Aktivasi fallback dari frontend
                        await fetch(WEDDING_SUCCESS_BASE + '/' + data.wedding_id, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                        });
                        window.location.href = '/creator/wedding?w=' + data.wedding_id;
                    },
                    onPending: () => {
                        alert('Pembayaran sedang diproses. Undangan akan aktif otomatis setelah pembayaran dikonfirmasi Midtrans.');
                        document.getElementById('modal-add-couple').style.display = 'none';
                    },
                    onError: (err) => {
                        errEl.textContent = 'Pembayaran gagal. Silakan coba lagi.';
                        errEl.style.display = 'block';
                        btn.disabled = false;
                        btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> Bayar via Midtrans';
                    },
                    onClose: () => {
                        btn.disabled = false;
                        btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> Bayar via Midtrans';
                    }
                });

            } catch (e) {
                errEl.textContent = e.message || 'Terjadi kesalahan server.';
                errEl.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> Bayar via Midtrans';
            }
        }

        // ═══════════════════════════════════════════════════════════════
        // PETA INTERAKTIF SEBARAN TAMU & RSVP BERDASARKAN KOTA ASAL
        // ═══════════════════════════════════════════════════════════════
        let guestMap = null;
        let allGuestMarkers = [];
        const rawGuestMapData = @json($mapGuests ?? []);

        function initGuestDistributionMap() {
            const container = document.getElementById('guestDistributionMap');
            if (!container || guestMap || typeof L === 'undefined') return;

            // Default center: Sidoarjo / Jawa Timur (Pusat Peta Indonesia)
            guestMap = L.map('guestDistributionMap', {
                center: [-7.4478, 112.7183],
                zoom: 7,
                scrollWheelZoom: true,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(guestMap);

            renderMarkers('all');
        }

        function createPinIcon(status) {
            let color = '#10b981'; // hadir
            let svgIcon = '<svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2.8" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>';
            if (status === 'tidak_hadir') {
                color = '#ef4444';
                svgIcon = '<svg width="11" height="11" fill="none" stroke="#fff" stroke-width="2.8" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
            } else if (status === 'ragu') {
                color = '#f59e0b';
                svgIcon = '<svg width="11" height="11" fill="none" stroke="#fff" stroke-width="2.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';
            }

            return L.divIcon({
                className: 'custom-guest-pin',
                html: `
                    <div style="width:28px; height:28px; background:${color}; border-radius:50% 50% 50% 0; transform:rotate(-45deg); display:flex; align-items:center; justify-content:center; box-shadow:0 3px 10px rgba(0,0,0,0.25); border:2px solid #fff;">
                        <span style="transform:rotate(45deg); display:flex; align-items:center; justify-content:center;">${svgIcon}</span>
                    </div>
                `,
                iconSize: [28, 28],
                iconAnchor: [14, 28],
                popupAnchor: [0, -28]
            });
        }

        function renderMarkers(statusFilter) {
            if (!guestMap) return;

            // Bersihkan marker lama
            allGuestMarkers.forEach(m => guestMap.removeLayer(m));
            allGuestMarkers = [];

            const bounds = [];

            rawGuestMapData.forEach(item => {
                if (statusFilter !== 'all' && item.status !== statusFilter) return;

                const icon = createPinIcon(item.status);
                const marker = L.marker([item.lat, item.lng], { icon: icon });

                const badgeBg = item.status === 'hadir' ? '#dcfce7' : (item.status === 'tidak_hadir' ? '#fee2e2' : '#fef3c7');
                const badgeColor = item.status === 'hadir' ? '#166534' : (item.status === 'tidak_hadir' ? '#991b1b' : '#92400e');
                const statusSvg = item.status === 'hadir'
                    ? '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;"><polyline points="20 6 9 17 4 12"/></svg> Akan Hadir'
                    : (item.status === 'tidak_hadir'
                        ? '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Tidak Hadir'
                        : '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Masih Ragu');

                const popupHtml = `
                    <div style="font-family:'Montserrat',sans-serif; min-width:180px; padding:2px;">
                        <div style="font-size:0.68rem; color:#64748b; font-weight:700; text-transform:uppercase;">Tamu Undangan</div>
                        <div style="font-weight:800; font-size:0.95rem; color:#0f172a; margin:0.15rem 0;">${escapeHtml(item.name)}</div>
                        <div style="font-size:0.75rem; color:#059669; font-weight:700; margin-bottom:0.4rem; display:flex; align-items:center; gap:3px;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            ${escapeHtml(item.city)}
                        </div>
                        <div style="display:inline-block; font-size:0.68rem; font-weight:700; padding:2px 8px; border-radius:99px; background:${badgeBg}; color:${badgeColor}; margin-bottom:0.5rem;">
                            ${statusSvg}
                        </div>
                        ${item.message ? `<div style="font-size:0.75rem; color:#475569; font-style:italic; background:#f8fafc; padding:0.4rem 0.6rem; border-radius:8px; border-left:3px solid #10b981;">"${escapeHtml(item.message)}"</div>` : ''}
                        ${item.time ? `<div style="font-size:0.65rem; color:#94a3b8; margin-top:0.35rem; text-align:right;">${item.time}</div>` : ''}
                    </div>
                `;

                marker.bindPopup(popupHtml);
                marker.addTo(guestMap);
                allGuestMarkers.push(marker);
                bounds.push([item.lat, item.lng]);
            });

            if (bounds.length > 0) {
                guestMap.fitBounds(bounds, { padding: [50, 50], maxZoom: 13 });
            }
        }

        function filterMapMarkers(filter) {
            document.querySelectorAll('.map-filter-btn').forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-filter') === filter);
            });
            renderMarkers(filter);
        }

        function resetMapToIndonesia() {
            if (guestMap) {
                if (rawGuestMapData.length > 0) {
                    const bounds = rawGuestMapData.map(i => [i.lat, i.lng]);
                    guestMap.fitBounds(bounds, { padding: [50, 50], maxZoom: 12 });
                } else {
                    guestMap.setView([-7.4478, 112.7183], 7);
                }
            }
        }

        function focusCity(lat, lng, cityName) {
            if (!guestMap) return;
            guestMap.flyTo([lat, lng], 13, { duration: 1.2 });
            const match = allGuestMarkers.find(m => {
                const pos = m.getLatLng();
                return Math.abs(pos.lat - lat) < 0.05 && Math.abs(pos.lng - lng) < 0.05;
            });
            if (match) {
                setTimeout(() => match.openPopup(), 1300);
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        // Photobooth Frame Upload Handlers
        function previewFrameUpload(event) {
            const file = event.target.files ? event.target.files[0] : null;
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('framePreviewImg');
                const name = document.getElementById('framePreviewName');
                const drop = document.getElementById('frameDropContent');
                const wrap = document.getElementById('framePreviewWrap');
                const actions = document.getElementById('frameUploadActions');
                if (img) img.src = e.target.result;
                if (name) name.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
                if (drop) drop.style.display = 'none';
                if (wrap) wrap.style.display = 'block';
                if (actions) actions.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }

        function handleFrameDrop(event) {
            event.preventDefault();
            const file = event.dataTransfer && event.dataTransfer.files ? event.dataTransfer.files[0] : null;
            if (file) {
                const input = document.getElementById('frameFileInput');
                if (input) {
                    input.files = event.dataTransfer.files;
                    previewFrameUpload({ target: { files: [file] } });
                }
            }
            const zone = document.getElementById('frameDropZone');
            if (zone) {
                zone.style.borderColor = '#c084fc';
                zone.style.background = '#fdf4ff';
            }
        }

        function resetFrameUpload() {
            const input = document.getElementById('frameFileInput');
            const drop = document.getElementById('frameDropContent');
            const wrap = document.getElementById('framePreviewWrap');
            const actions = document.getElementById('frameUploadActions');
            if (input) input.value = '';
            if (drop) drop.style.display = 'block';
            if (wrap) wrap.style.display = 'none';
            if (actions) actions.style.display = 'none';
        }

        // Auto-init jika tab aktif saat dibuka adalah tab-map
        document.addEventListener('DOMContentLoaded', function () {
            @if($activeTab === 'tab-map')
                setTimeout(() => initGuestDistributionMap(), 300);
            @endif
        });
    </script>
@endsection