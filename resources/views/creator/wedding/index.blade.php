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
    <style>
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
            background: linear-gradient(135deg, #1eb349 0%, #16a34a 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.15rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.28);
            transition: all 0.15s ease;
        }

        .wb-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(30, 179, 73, 0.35);
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

        .stat-icon.green { background: #dcfce7; color: #166534; }
        .stat-icon.blue { background: #e0f2fe; color: #0369a1; }
        .stat-icon.rose { background: #ffe4e6; color: #be123c; }
        .stat-icon.amber { background: #fef3c7; color: #b45309; }

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

            .wb-page-banner-actions > * {
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
        $publicWeddingUrl = route('wedding.show', $wedding->slug);
        $receptionUrl = route('wedding.reception', $wedding->slug);
    @endphp

    {{-- Alert Messages --}}
    @if(session('success'))
        <div style="background:#ecfdf5; border:1.5px solid #a7f3d0; color:#065f46; padding:0.9rem 1.25rem; border-radius:14px; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.65rem; font-size:0.85rem; font-weight:600;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
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
            <p class="wb-page-banner-desc">Kelola undangan pernikahan digital, Smart Guest Book QR check-in, amplop digital, dan ucapan doa dalam satu tempat.</p>
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
            <a href="{{ route('creator.wedding.panic-pdf') }}" class="wb-btn-danger-outline" title="Cetak Buku Tamu Cadangan Fisik">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Panic Button PDF</span>
            </a>
        </div>
    </div>

    {{-- Layout Web Builder --}}
    <div class="bio-layout">

        {{-- Sidebar Tab Column --}}
        <div class="bio-sidebar-column">
            <div class="bio-sidebar-card">
                <div class="bio-tabs-list">
                    <button class="tab-btn {{ $activeTab === 'tab-info' ? 'active' : '' }}" data-tab="tab-info">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                        <span class="tab-text-full">Mempelai & Acara</span>
                        <small class="tab-text-mob">Acara</small>
                    </button>

                    <button class="tab-btn {{ $activeTab === 'tab-checkin' ? 'active' : '' }}" data-tab="tab-checkin">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        <span class="tab-text-full">Buku Tamu & Check-in</span>
                        <small class="tab-text-mob">Check-in</small>
                    </button>

                    <button class="tab-btn {{ $activeTab === 'tab-guests' ? 'active' : '' }}" data-tab="tab-guests">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="tab-text-full">Daftar Tamu & WA</span>
                        <small class="tab-text-mob">Tamu</small>
                    </button>

                    <button class="tab-btn {{ $activeTab === 'tab-envelope' ? 'active' : '' }}" data-tab="tab-envelope">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                        <span class="tab-text-full">Amplop Digital</span>
                        <small class="tab-text-mob">Amplop</small>
                    </button>

                    <button class="tab-btn {{ $activeTab === 'tab-wishes' ? 'active' : '' }}" data-tab="tab-wishes">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span class="tab-text-full">Doa & InstaStory</span>
                        <small class="tab-text-mob">Doa</small>
                    </button>
                </div>
            </div>

            {{-- Reception Gate Quick Info Box --}}
            <div class="bio-sidebar-card" style="background:#f8fafc; border:1.5px dashed #cbd5e1;">
                <div style="font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:0.4rem; display:flex; align-items:center; gap:0.4rem;">
                    <svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Akses Meja Penerima Tamu
                </div>
                <div style="font-size:0.72rem; color:#64748b; margin-bottom:0.75rem; line-height:1.4;">
                    Berikan link ini ke panitia di venue. Panitia cukup memasukkan 4-digit PIN tanpa perlu akun login.
                </div>
                <div style="display:flex; flex-direction:column; gap:0.4rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; background:#fff; padding:0.4rem 0.6rem; border-radius:8px; border:1px solid #e2e8f0; font-size:0.75rem;">
                        <span style="color:#64748b; font-weight:600;">PIN:</span>
                        <span style="font-weight:800; font-family:monospace; color:#0f172a; font-size:0.9rem;">{{ $wedding->reception_pin }}</span>
                    </div>
                    <a href="{{ $receptionUrl }}" target="_blank" style="display:flex; align-items:center; justify-content:center; gap:0.35rem; font-size:0.75rem; font-weight:700; color:#166534; background:#dcfce7; padding:0.5rem; border-radius:8px; text-decoration:none;">
                        Buka Scanner Meja Tamu
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
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
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                </div>
                                <span>Tautan Web Undangan &amp; PIN</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.25rem;">
                                <div class="form-group">
                                    <label class="form-label">Slug URL Undangan</label>
                                    <div style="display:flex; align-items:center; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                                        <span style="padding:0 0.85rem; font-size:0.8rem; font-weight:700; color:#64748b; background:#f1f5f9; border-right:1px solid #e2e8f0;">{{ url('/w') }}/</span>
                                        <input type="text" name="slug" value="{{ old('slug', $wedding->slug) }}" class="form-input" style="border:none; background:transparent;" required>
                                    </div>
                                    <span class="form-hint">Tautan resmi yang akan dibagikan ke seluruh tamu undangan.</span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">PIN Panitia Resepsi (4 Digit)</label>
                                    <input type="text" name="reception_pin" value="{{ old('reception_pin', $wedding->reception_pin) }}" maxlength="6" class="form-input" style="font-weight:800; font-family:monospace; text-align:center; font-size:1.05rem;" required>
                                    <span class="form-hint">Digunakan panitia untuk akses scan tamu.</span>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Judul Acara / Tagline</label>
                                <input type="text" name="event_title" value="{{ old('event_title', $wedding->event_title) }}" placeholder="Contoh: The Wedding Celebration of" class="form-input" required>
                            </div>
                        </div>
                    </div>

                    {{-- Card Pasangan Mempelai --}}
                    <div class="prof-card">
                        <div class="prof-card-head">
                            <div class="head-title-wrap">
                                <div class="head-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                </div>
                                <span>Informasi Mempelai Pria &amp; Wanita</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">
                                {{-- Mempelai Pria --}}
                                <div style="background:#f8fafc; padding:1.25rem; border-radius:14px; border:1px solid #e2e8f0;">
                                    <div style="font-size:0.875rem; font-weight:800; color:#0f172a; margin-bottom:1rem; display:flex; align-items:center; gap:0.4rem;">
                                        <span style="width:8px; height:8px; border-radius:50%; background:#0284c7;"></span>
                                        Mempelai Pria (Groom)
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Lengkap &amp; Gelar</label>
                                        <input type="text" name="groom_name" value="{{ old('groom_name', $wedding->groom_name) }}" placeholder="Rian Pratama, S.Kom" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Panggilan</label>
                                        <input type="text" name="groom_nickname" value="{{ old('groom_nickname', $wedding->groom_nickname) }}" placeholder="Rian" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Orang Tua</label>
                                        <input type="text" name="groom_parents" value="{{ old('groom_parents', $wedding->groom_parents) }}" placeholder="Putra pertama dari Bpk. Budi &amp; Ibu Siti" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Instagram Handle</label>
                                        <input type="text" name="groom_instagram" value="{{ old('groom_instagram', $wedding->groom_instagram) }}" placeholder="@rianpratama" class="form-input">
                                    </div>
                                    <div class="form-group" style="margin-bottom:0;">
                                        <label class="form-label">Foto Mempelai Pria</label>
                                        <input type="file" name="groom_photo" accept="image/*" class="form-input" style="padding-top:0.4rem;">
                                    </div>
                                </div>

                                {{-- Mempelai Wanita --}}
                                <div style="background:#fdf2f8; padding:1.25rem; border-radius:14px; border:1px solid #fbcfe8;">
                                    <div style="font-size:0.875rem; font-weight:800; color:#831843; margin-bottom:1rem; display:flex; align-items:center; gap:0.4rem;">
                                        <span style="width:8px; height:8px; border-radius:50%; background:#e11d48;"></span>
                                        Mempelai Wanita (Bride)
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Lengkap &amp; Gelar</label>
                                        <input type="text" name="bride_name" value="{{ old('bride_name', $wedding->bride_name) }}" placeholder="Anisa Rahmawati, S.E" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Panggilan</label>
                                        <input type="text" name="bride_nickname" value="{{ old('bride_nickname', $wedding->bride_nickname) }}" placeholder="Anisa" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Orang Tua</label>
                                        <input type="text" name="bride_parents" value="{{ old('bride_parents', $wedding->bride_parents) }}" placeholder="Putri kedua dari Bpk. Hendra &amp; Ibu Maria" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Instagram Handle</label>
                                        <input type="text" name="bride_instagram" value="{{ old('bride_instagram', $wedding->bride_instagram) }}" placeholder="@anisarahma" class="form-input">
                                    </div>
                                    <div class="form-group" style="margin-bottom:0;">
                                        <label class="form-label">Foto Mempelai Wanita</label>
                                        <input type="file" name="bride_photo" accept="image/*" class="form-input" style="padding-top:0.4rem;">
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
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                </div>
                                <span>Waktu &amp; Lokasi Acara</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">
                                {{-- Akad / Pemberkatan --}}
                                <div>
                                    <div style="font-size:0.85rem; font-weight:700; color:#0f172a; margin-bottom:0.75rem; border-bottom:1.5px solid #e2e8f0; padding-bottom:0.4rem;">
                                        1. Akad Nikah / Pemberkatan
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Waktu Akad</label>
                                        <input type="datetime-local" name="ceremony_date" value="{{ $wedding->ceremony_date ? $wedding->ceremony_date->format('Y-m-d\TH:i') : '' }}" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Tempat / Masjid / Gereja</label>
                                        <input type="text" name="ceremony_location_name" value="{{ old('ceremony_location_name', $wedding->ceremony_location_name) }}" placeholder="Masjid Agung Al-Falah" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea name="ceremony_address" class="form-input" rows="2" placeholder="Jl. Diponegoro No. 10...">{{ old('ceremony_address', $wedding->ceremony_address) }}</textarea>
                                    </div>
                                </div>

                                {{-- Resepsi Pernikahan --}}
                                <div>
                                    <div style="font-size:0.85rem; font-weight:700; color:#0f172a; margin-bottom:0.75rem; border-bottom:1.5px solid #e2e8f0; padding-bottom:0.4rem;">
                                        2. Resepsi Pernikahan
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Waktu Resepsi</label>
                                        <input type="datetime-local" name="event_date" value="{{ $wedding->event_date ? $wedding->event_date->format('Y-m-d\TH:i') : '' }}" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Gedung / Ballroom</label>
                                        <input type="text" name="event_location_name" value="{{ old('event_location_name', $wedding->event_location_name) }}" placeholder="Grand Ballroom Hotel Mulia" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Alamat Lengkap Venue</label>
                                        <textarea name="event_address" class="form-input" rows="2" placeholder="Jl. Jenderal Sudirman No. 45...">{{ old('event_address', $wedding->event_address) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">URL Google Maps</label>
                                        <input type="url" name="event_map_url" value="{{ old('event_map_url', $wedding->event_map_url) }}" placeholder="https://maps.app.goo.gl/..." class="form-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end;">
                        <button type="submit" class="wb-btn-primary" style="padding:0.75rem 2rem; font-size:0.9rem;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
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
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        </div>
                        <div class="stat-meta">
                            <span class="stat-val">{{ number_format($totalGuests) }}</span>
                            <span class="stat-lbl">Tamu Terdaftar</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon green">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="stat-meta">
                            <span class="stat-val">{{ number_format($checkedInCount) }}</span>
                            <span class="stat-lbl">Tamu Check-in</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon amber">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div class="stat-meta">
                            <span class="stat-val">{{ $totalGuests > 0 ? round(($checkedInCount / $totalGuests) * 100) : 0 }}%</span>
                            <span class="stat-lbl">Tingkat Kehadiran</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon rose">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
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
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                            <div>
                                <div style="font-weight:800; font-size:1rem; color:#166534; display:flex; align-items:center; gap:0.5rem;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                                    Cari &amp; Quick Check-in Tamu
                                </div>
                                <div style="font-size:0.75rem; color:#15803d; margin-top:2px;">
                                    Ketik kode QR tamu (misal: <code>WED-ABCD12</code>) atau ID tamu untuk verifikasi instan.
                                </div>
                            </div>
                            <div style="display:flex; gap:0.5rem; flex:1; max-width:420px;">
                                <input type="text" id="quickCheckinCode" placeholder="Masukkan Kode QR Tamu..." class="form-input" style="background:#fff; border-color:#86efac; font-family:monospace; font-weight:700;">
                                <button type="button" onclick="submitQuickCheckin()" class="wb-btn-primary" style="white-space:nowrap;">
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
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
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
                                            <td><span class="badge-pill warning">{{ $g->group_name ?: 'Tamu Undangan' }}</span></td>
                                            <td><strong>{{ $g->actual_pax ?? $g->pax }}</strong> orang</td>
                                            <td>{{ $g->checked_in_at ? $g->checked_in_at->format('H:i') . ' WIB' : '-' }}</td>
                                            <td><span style="font-size:0.75rem; color:#475569;">{{ $g->checked_in_by ?: 'Panitia' }}</span></td>
                                            <td><code style="font-size:0.75rem; background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $g->qr_code }}</code></td>
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
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                </div>
                                <span>Tambah 1 Tamu Undangan</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('creator.wedding.guest.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Nama Tamu Undangan *</label>
                                    <input type="text" name="name" placeholder="Bpk. Ir. Joko &amp; Keluarga" class="form-input" required>
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
                                    <input type="text" name="group_name" placeholder="Keluarga / Sahabat / Kantor" class="form-input">
                                </div>
                                <button type="submit" class="wb-btn-primary" style="width:100%; justify-content:center;">
                                    Simpan Tamu
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Bulk Import CSV / Text --}}
                    <div class="prof-card" style="margin-bottom:0;">
                        <div class="prof-card-head">
                            <div class="head-title-wrap">
                                <div class="head-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                </div>
                                <span>Bulk Import Tamu (Salin &amp; Tempel)</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('creator.wedding.guest.import') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Format: Nama, NoHP, Pax, Grup (1 baris per tamu)</label>
                                    <textarea name="bulk_data" class="form-input" style="height:120px; font-family:monospace; font-size:0.75rem;" placeholder="Budi Santoso, 0812345678, 2, Rekan Kerja&#10;Dr. Siti Aminah, 0856789012, 1, VIP&#10;Ahmad Fauzi &amp; Partner, , 2, Teman SMA" required></textarea>
                                    <span class="form-hint">Pisahkan tiap kolom dengan koma (,). Nomor HP dan grup bersifat opsional.</span>
                                </div>
                                <button type="submit" class="wb-btn-primary" style="width:100%; justify-content:center; background:linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                                    Impor Tamu Sekaligus
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
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                            </div>
                            <span>Daftar Seluruh Tamu Undangan ({{ $totalGuests }})</span>
                        </div>
                        <div>
                            <form method="GET" action="{{ route('creator.wedding.index') }}" style="display:flex; gap:0.4rem;">
                                <input type="hidden" name="tab" value="tab-guests">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau telepon..." class="form-input" style="height:34px; font-size:0.75rem; width:180px;">
                                <button type="submit" class="wb-btn-primary" style="padding:0 0.75rem; height:34px; font-size:0.75rem;">Cari</button>
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
                                            $guestInviteUrl = route('wedding.show', ['slug' => $wedding->slug, 'to' => $guest->name]);
                                            $waText = "Halo {$guest->name},\n\nTanpa mengurangi rasa hormat, kami mengundang Bapak/Ibu/Saudara/i untuk hadir di acara pernikahan kami:\n\n*{$wedding->groom_nickname} & {$wedding->bride_nickname}*\n\nBuka tautan undangan berikut:\n{$guestInviteUrl}\n\nKode Masuk QR Anda: {$guest->qr_code}\n\nMerupakan suatu kehormatan dan kebahagiaan bagi kami apabila berkenan hadir.\n\nTerima kasih!";
                                            $waUrl = "https://api.whatsapp.com/send?" . ($guest->phone ? "phone=" . preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $guest->phone)) . "&" : "") . "text=" . urlencode($waText);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div style="font-weight:700; color:#0f172a;">{{ $guest->name }}</div>
                                                <div style="font-size:0.7rem; color:#64748b;">{{ $guest->phone ?: 'Tanpa nomor telepon' }}</div>
                                            </td>
                                            <td><span class="badge-pill warning">{{ $guest->group_name ?: 'Tamu Undangan' }}</span></td>
                                            <td>{{ $guest->pax ?: 1 }} pax</td>
                                            <td>
                                                @if($guest->is_checked_in)
                                                    <span class="badge-pill success">Hadir ({{ $guest->checked_in_at ? $guest->checked_in_at->format('H:i') : 'Yes' }})</span>
                                                @else
                                                    <span class="badge-pill" style="background:#f1f5f9; color:#64748b;">Belum Hadir</span>
                                                @endif
                                            </td>
                                            <td>
                                                <code style="font-size:0.75rem; background:#f1f5f9; padding:2px 6px; border-radius:4px; font-weight:700;">{{ $guest->qr_code }}</code>
                                            </td>
                                            <td style="text-align:right; white-space:nowrap;">
                                                <a href="{{ $waUrl }}" target="_blank" class="wb-btn-primary" style="background:#25d366; padding:0.35rem 0.65rem; font-size:0.75rem;" title="Kirim Undangan WhatsApp">
                                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                                    Kirim WA
                                                </a>
                                                <button type="button" onclick="copyGuestLink('{{ $guestInviteUrl }}')" class="wb-btn-preview-link" style="padding:0.35rem 0.65rem; font-size:0.75rem;" title="Salin Tautan">
                                                    Salin Link
                                                </button>
                                                <form action="{{ route('creator.wedding.guest.destroy', $guest->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus tamu ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer; padding:0.35rem;">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
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
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                            </div>
                            <span>Pengaturan Amplop Digital &amp; Donasi</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('creator.wedding.update-envelope') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="form-group" style="background:#f8fafc; padding:1rem; border-radius:12px; border:1px solid #e2e8f0; margin-bottom:1.25rem;">
                                <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer;">
                                    <input type="checkbox" name="digital_envelope_enabled" value="1" {{ $wedding->digital_envelope_enabled ? 'checked' : '' }} style="width:18px; height:18px; accent-color:#1eb349;">
                                    <div>
                                        <div style="font-weight:700; font-size:0.875rem; color:#0f172a;">Aktifkan Fitur Amplop Digital</div>
                                        <div style="font-size:0.72rem; color:#64748b;">Tamu undangan dapat mengirimkan hadiah tanda kasih langsung via transfer bank atau scan QRIS.</div>
                                    </div>
                                </label>
                            </div>

                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem;">
                                <div>
                                    <div class="form-group">
                                        <label class="form-label">Nama Bank</label>
                                        <input type="text" name="bank_name" value="{{ old('bank_name', $wedding->bank_name) }}" placeholder="Contoh: BCA / Bank Mandiri / BRI" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nomor Rekening</label>
                                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $wedding->bank_account_number) }}" placeholder="Contoh: 1234567890" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Atas Nama Pemilik Rekening</label>
                                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $wedding->bank_account_name) }}" placeholder="Contoh: Rian Pratama" class="form-input">
                                    </div>
                                </div>

                                <div>
                                    <div class="form-group">
                                        <label class="form-label">Unggah Gambar QRIS (JPG / PNG)</label>
                                        <input type="file" name="qris_image" accept="image/*" class="form-input" style="padding-top:0.4rem;">
                                        <span class="form-hint">Tamu dapat men-scan kode QRIS ini menggunakan aplikasi mobile banking atau e-wallet apa saja.</span>
                                    </div>
                                    @if($wedding->qris_image)
                                        <div style="margin-top:0.5rem; text-align:center; background:#f8fafc; padding:0.75rem; border-radius:12px; border:1px solid #e2e8f0;">
                                            <div style="font-size:0.75rem; font-weight:700; color:#64748b; margin-bottom:0.4rem;">Pratinjau QRIS Aktif:</div>
                                            <img src="{{ asset('storage/' . $wedding->qris_image) }}" alt="QRIS" style="max-height:140px; border-radius:8px;">
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
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                            </div>
                            <span>Riwayat Amplop Digital Masuk (Total: Rp {{ number_format($totalGifts, 0, ',', '.') }})</span>
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
                                            <td><span style="font-weight:800; color:#166534;">Rp {{ number_format($gift->amount, 0, ',', '.') }}</span></td>
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
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            </div>
                            <span>Doa &amp; Ucapan Tamu (InstaStory Card Generator)</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:1rem;">
                            @forelse($wishes as $wish)
                                <div style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:16px; padding:1.25rem; display:flex; flex-direction:column; justify-content:space-between; box-shadow:0 2px 6px rgba(0,0,0,0.02);">
                                    <div>
                                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.5rem;">
                                            <div>
                                                <div style="font-weight:800; font-size:0.9rem; color:#0f172a;">{{ $wish->sender_name }}</div>
                                                <div style="font-size:0.7rem; color:#64748b;">{{ $wish->created_at->diffForHumans() }}</div>
                                            </div>
                                            <span class="badge-pill {{ $wish->attendance_status === 'hadir' ? 'success' : 'warning' }}">
                                                {{ ucfirst($wish->attendance_status) }}
                                            </span>
                                        </div>
                                        <p style="font-size:0.825rem; color:#334155; line-height:1.5; font-style:italic; margin:0.75rem 0;">
                                            "{{ $wish->message }}"
                                        </p>
                                    </div>
                                    <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f1f5f9; padding-top:0.75rem; margin-top:0.75rem;">
                                        <button type="button" onclick="generateStoryCard('{{ addslashes($wish->sender_name) }}', '{{ addslashes($wish->message) }}', '{{ $wedding->groom_nickname }} & {{ $wedding->bride_nickname }}')" class="wb-btn-primary" style="padding:0.4rem 0.85rem; font-size:0.75rem; background:linear-gradient(135deg, #e1306c 0%, #f77737 100%);">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                                            Buat Story Card (9:16)
                                        </button>
                                        <form action="{{ route('creator.wedding.wish.destroy', $wish->id) }}" method="POST" onsubmit="return confirm('Hapus ucapan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background:none; border:none; color:#ef4444; font-size:0.75rem; cursor:pointer; font-weight:600;">Hapus</button>
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

        </div>
    </div>

    {{-- Hidden HTML5 Canvas untuk InstaStory Wish Card Generator --}}
    <canvas id="storyCanvas" width="1080" height="1920" style="display:none;"></canvas>

    {{-- Story Modal Preview --}}
    <div id="storyModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.8); z-index:99999; align-items:center; justify-content:center; padding:1rem;">
        <div style="background:#fff; border-radius:20px; max-width:400px; width:100%; overflow:hidden; box-shadow:0 25px 50px rgba(0,0,0,0.4); text-align:center; padding:1.25rem;">
            <div style="font-weight:800; font-size:1rem; color:#0f172a; margin-bottom:0.75rem;">Kartu InstaStory Siap Diunduh!</div>
            <img id="storyPreviewImg" style="width:100%; max-height:480px; object-fit:contain; border-radius:12px; margin-bottom:1rem; box-shadow:0 4px 14px rgba(0,0,0,0.15);">
            <div style="display:flex; gap:0.5rem; justify-content:center;">
                <button type="button" onclick="downloadStoryImg()" class="wb-btn-primary" style="flex:1; justify-content:center;">
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
    <script>
        // Tab switching logic (identik dengan Web Builder)
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                if (!targetTab) return;

                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

                this.classList.add('active');
                const targetPane = document.getElementById(targetTab);
                if (targetPane) {
                    targetPane.classList.add('active');
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
                    resBox.innerHTML = `<div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:0.6rem 1rem; border-radius:10px; font-size:0.8rem; font-weight:700;">✅ ${data.message} (${data.guest.name} - ${data.guest.pax} Pax)</div>`;
                    input.value = '';
                    setTimeout(() => location.reload(), 1500);
                } else {
                    resBox.innerHTML = `<div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.6rem 1rem; border-radius:10px; font-size:0.8rem; font-weight:700;">❌ ${data.message}</div>`;
                }
            })
            .catch(err => {
                resBox.innerHTML = `<div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:0.6rem 1rem; border-radius:10px; font-size:0.8rem; font-weight:700;">❌ Terjadi kesalahan jaringan.</div>`;
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
    </script>
@endsection
