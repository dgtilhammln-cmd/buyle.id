<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Portal Tiket & Gatekeeper - Buyle.id</title>
    <meta name="description"
        content="Portal resmi penyelenggara event dan petugas panitia scanner tiket Buyle.id. Masuk dan validasi tiket dengan cepat dan aman.">
    <meta name="theme-color" content="#f8fafc">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --pg: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            --pg-shadow: rgba(30, 179, 73, 0.32);
            --pg-hover: linear-gradient(135deg, #18983e 0%, #95bd30 100%);
            --bg-page: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --card-border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: max(env(safe-area-inset-top, 0px), 1.25rem) 1rem max(env(safe-area-inset-bottom, 0px), 1.5rem);
            position: relative;
            overflow-x: hidden;
        }

        /* ── SUPER APP CONTAINER ── */
        .portal-wrapper {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 28px;
            padding: 2rem 1.65rem 2.25rem;
            box-shadow: 0 14px 45px rgba(15, 23, 42, 0.05), 0 2px 10px rgba(15, 23, 42, 0.02);
            border: 1.5px solid #f1f5f9;
            position: relative;
            z-index: 10;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* ── BRAND TOP BADGE ── */
        .portal-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .portal-icon-pill {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: var(--pg);
            box-shadow: 0 10px 24px var(--pg-shadow);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            margin-bottom: 0.9rem;
        }

        .portal-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        .portal-subtitle {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 0.35rem;
            font-weight: 500;
            line-height: 1.5;
        }

        /* ── SMOOTH TRANSITION VIEWS ── */
        .auth-view {
            display: none;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 0.28s ease, transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .auth-view.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* ── 3 PRIMARY ACTION BUTTON CARDS (INITIAL MENU) ── */
        .action-cards-grid {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .action-card-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.1rem 1.2rem;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            text-align: left;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .action-card-btn:hover {
            transform: translateY(-2px);
            border-color: #a5cf37;
            box-shadow: 0 8px 24px rgba(30, 179, 73, 0.12);
        }

        .action-card-btn:active {
            transform: scale(0.98);
        }

        .action-card-left {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            min-width: 0;
            flex: 1;
        }

        .action-card-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #f0fdf4;
            color: #1eb349;
            border: 1px solid #dcfce7;
            transition: transform 0.2s ease;
        }

        .action-card-btn:hover .action-card-icon {
            transform: scale(1.06);
            background: var(--pg);
            color: #ffffff;
            border-color: transparent;
        }

        .action-card-text {
            min-width: 0;
        }

        .action-card-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            display: block;
            margin-bottom: 0.2rem;
            letter-spacing: -0.01em;
        }

        .action-card-desc {
            font-size: 0.76rem;
            color: #64748b;
            font-weight: 500;
            line-height: 1.4;
            display: block;
        }

        .action-card-arrow {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .action-card-btn:hover .action-card-arrow {
            background: var(--pg);
            color: #ffffff;
            transform: translateX(3px);
        }

        /* ── TOP NAV BAR FOR FORMS (BACK BUTTON) ── */
        .form-top-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.35rem;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .btn-back-menu {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 0.45rem 0.8rem;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s ease;
        }

        .btn-back-menu:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .form-badge-tag {
            font-size: 0.7rem;
            font-weight: 800;
            color: #059669;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            letter-spacing: 0.02em;
        }

        /* ── FORM ELEMENTS ── */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .form-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input {
            width: 100%;
            height: 48px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 0 1rem;
            font-size: 0.88rem;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: #1eb349;
            box-shadow: 0 0 0 4px rgba(30, 179, 73, 0.12);
        }

        .form-input.has-icon-right {
            padding-right: 2.8rem;
        }

        .toggle-pwd {
            position: absolute;
            right: 0.75rem;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: color 0.18s ease;
        }

        .toggle-pwd:hover {
            color: #1eb349;
        }

        /* ── PRIMARY SUBMIT BUTTON WITH SIGNATURE GREEN GRADIENT ── */
        .btn-submit-gradient {
            width: 100%;
            height: 50px;
            border-radius: 14px;
            background: var(--pg);
            box-shadow: 0 8px 24px var(--pg-shadow);
            border: none;
            color: #ffffff;
            font-size: 0.92rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            margin-top: 1.25rem;
            letter-spacing: -0.01em;
        }

        .btn-submit-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(30, 179, 73, 0.42);
            filter: brightness(1.04);
        }

        .btn-submit-gradient:active {
            transform: scale(0.98);
        }

        .btn-submit-gradient:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        /* ── HELPER ROWS & FOOTERS ── */
        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            margin-top: 0.2rem;
            margin-bottom: 0.5rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            color: #475569;
            font-weight: 600;
        }

        .remember-label input[type="checkbox"] {
            accent-color: #1eb349;
            width: 15px;
            height: 15px;
            border-radius: 4px;
        }

        .switch-link-row {
            text-align: center;
            margin-top: 1.35rem;
            font-size: 0.8rem;
            color: #64748b;
        }

        .switch-link-btn {
            background: none;
            border: none;
            color: #1eb349;
            font-weight: 800;
            cursor: pointer;
            font-size: inherit;
            text-decoration: underline;
            padding: 0;
            font-family: inherit;
        }

        .switch-link-btn:hover {
            color: #15803d;
        }

        /* ── ALERTS / ERROR BANNERS ── */
        .alert-box {
            padding: 0.75rem 0.95rem;
            border-radius: 12px;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            line-height: 1.45;
        }

        .alert-danger {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            color: #b91c1c;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            color: #15803d;
        }

        .portal-bottom-footer {
            margin-top: 1.75rem;
            text-align: center;
            font-size: 0.74rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .portal-bottom-footer a {
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
        }

        .portal-bottom-footer a:hover {
            color: #1eb349;
        }
    </style>
</head>

<body>

    <div class="portal-wrapper">

        {{-- ══ BRAND TOP HEADER (Always Visible) ══ --}}
        <div class="portal-header">
            <div class="portal-icon-pill">
                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
            </div>
            <h1 class="portal-title">Buyle Ticketing & Gate</h1>
            <p class="portal-subtitle">Portal Resmi Penyelenggara Event & Panitia Scanner Tiket</p>
        </div>

        {{-- Global session alerts --}}
        @if(session('success'))
            <div class="alert-box alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-box alert-danger">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════════
             VIEW 1: MENU UTAMA (3 BUTTONS TAMPIL DI AWAL)
        ══════════════════════════════════════════════════════════════════ --}}
        <div id="viewMenu" class="auth-view {{ (!in_array($initialTab, ['register', 'organizer', 'staff']) && !$errors->any()) ? 'active' : '' }}">
            <div class="action-cards-grid">

                {{-- BUTTON 1: DAFTAR PENYELENGGARA --}}
                <button type="button" class="action-card-btn" onclick="showView('register')">
                    <div class="action-card-left">
                        <div class="action-card-icon">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="8.5" cy="7" r="4" />
                                <line x1="20" y1="8" x2="20" y2="14" />
                                <line x1="23" y1="11" x2="17" y2="11" />
                            </svg>
                        </div>
                        <div class="action-card-text">
                            <span class="action-card-title">Daftar Penyelenggara</span>
                            <span class="action-card-desc">Buka akun penyelenggara baru & buat tiket event</span>
                        </div>
                    </div>
                    <div class="action-card-arrow">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </div>
                </button>

                {{-- BUTTON 2: LOGIN PENYELENGGARA --}}
                <button type="button" class="action-card-btn" onclick="showView('organizer')">
                    <div class="action-card-left">
                        <div class="action-card-icon">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <div class="action-card-text">
                            <span class="action-card-title">Login Penyelenggara</span>
                            <span class="action-card-desc">Masuk kelola event, rekap tiket & pantau gate</span>
                        </div>
                    </div>
                    <div class="action-card-arrow">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </div>
                </button>

                {{-- BUTTON 3: LOGIN PANITIA --}}
                <button type="button" class="action-card-btn" onclick="showView('staff')">
                    <div class="action-card-left">
                        <div class="action-card-icon" style="background:#ecfdf5; color:#059669; border-color:#a7f3d0;">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2" />
                                <rect x="7" y="7" width="10" height="10" rx="1" />
                            </svg>
                        </div>
                        <div class="action-card-text">
                            <span class="action-card-title">Login Panitia Gate</span>
                            <span class="action-card-desc">Masuk scanner dengan Nama & Password panitia</span>
                        </div>
                    </div>
                    <div class="action-card-arrow">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </div>
                </button>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             VIEW 2: FORM DAFTAR PENYELENGGARA
        ══════════════════════════════════════════════════════════════════ --}}
        <div id="viewRegister" class="auth-view {{ $initialTab === 'register' ? 'active' : '' }}">
            <div class="form-top-nav">
                <button type="button" class="btn-back-menu" onclick="showView('menu')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Kembali</span>
                </button>
                <span class="form-badge-tag">Penyelenggara Baru</span>
            </div>

            <form method="POST" action="{{ route('ticketing.register.organizer') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Penyelenggara</label>
                    <div class="form-input-wrap">
                        <input type="text" name="name" class="form-input" placeholder="Contoh: Andi Pratama" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <div class="form-input-wrap">
                        <input type="email" name="email" class="form-input" placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">No. WhatsApp</label>
                    <div class="form-input-wrap">
                        <input type="tel" name="phone" class="form-input" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div class="form-input-wrap">
                        <input type="password" id="regPassword" name="password" class="form-input has-icon-right" placeholder="Minimal 6 karakter" required>
                        <button type="button" class="toggle-pwd" onclick="togglePwd('regPassword', this)" title="Lihat Password">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Ulangi Kata Sandi</label>
                    <div class="form-input-wrap">
                        <input type="password" id="regPasswordConfirm" name="password_confirmation" class="form-input has-icon-right" placeholder="Ketik ulang kata sandi" required>
                        <button type="button" class="toggle-pwd" onclick="togglePwd('regPasswordConfirm', this)" title="Lihat Password">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit-gradient">
                    <span>Daftar Sebagai Penyelenggara</span>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </form>

            <div class="switch-link-row">
                Sudah punya akun penyelenggara?
                <button type="button" class="switch-link-btn" onclick="showView('organizer')">Masuk di sini</button>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             VIEW 3: FORM LOGIN PENYELENGGARA
        ══════════════════════════════════════════════════════════════════ --}}
        <div id="viewOrganizer" class="auth-view {{ ($initialTab === 'organizer' || $errors->has('organizer_email')) ? 'active' : '' }}">
            <div class="form-top-nav">
                <button type="button" class="btn-back-menu" onclick="showView('menu')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Kembali</span>
                </button>
                <span class="form-badge-tag">Penyelenggara</span>
            </div>

            <form method="POST" action="{{ route('ticketing.login.organizer') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email atau No. WhatsApp</label>
                    <div class="form-input-wrap">
                        <input type="text" name="email" class="form-input" placeholder="Email atau 08xxxxxxxxxx" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div class="form-input-wrap">
                        <input type="password" id="orgPassword" name="password" class="form-input has-icon-right" placeholder="Masukkan kata sandi" required>
                        <button type="button" class="toggle-pwd" onclick="togglePwd('orgPassword', this)" title="Lihat Password">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="options-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1" checked>
                        <span>Ingat saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" style="color:#64748b; text-decoration:none; font-weight:600;">Lupa sandi?</a>
                </div>

                <button type="submit" class="btn-submit-gradient">
                    <span>Masuk Penyelenggara</span>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </form>

            <div class="switch-link-row">
                Belum punya akun penyelenggara?
                <button type="button" class="switch-link-btn" onclick="showView('register')">Daftar sekarang</button>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             VIEW 4: FORM LOGIN PANITIA (GATE SCANNER)
        ══════════════════════════════════════════════════════════════════ --}}
        <div id="viewStaff" class="auth-view {{ ($initialTab === 'staff' || $errors->has('staff_name') || $errors->has('staff_password')) ? 'active' : '' }}">
            <div class="form-top-nav">
                <button type="button" class="btn-back-menu" onclick="showView('menu')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Kembali</span>
                </button>
                <span class="form-badge-tag" style="background:#ecfdf5; color:#059669; border-color:#a7f3d0;">Panitia Gate</span>
            </div>

            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:0.65rem 0.85rem; margin-bottom:1.15rem; font-size:0.75rem; color:#166534; line-height:1.45; display:flex; align-items:center; gap:0.45rem;">
                <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Masukkan <strong>Nama Panitia</strong> dan <strong>Password</strong> yang didaftarkan oleh pihak Penyelenggara Event.</span>
            </div>

            <form method="POST" action="{{ route('ticketing.login.staff') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Panitia</label>
                    <div class="form-input-wrap">
                        <input type="text" name="name" class="form-input" placeholder="Contoh: Pintu Barat / Budi" value="{{ old('name') }}" required autofocus autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password Panitia</label>
                    <div class="form-input-wrap">
                        <input type="password" id="staffPassword" name="password" class="form-input has-icon-right" placeholder="Masukkan password panitia" required autocomplete="off">
                        <button type="button" class="toggle-pwd" onclick="togglePwd('staffPassword', this)" title="Lihat Password">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit-gradient">
                    <span>Masuk Layar Scanner</span>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2" />
                        <rect x="7" y="7" width="10" height="10" rx="1" />
                    </svg>
                </button>
            </form>

            <div class="switch-link-row">
                Anda Penyelenggara Event?
                <button type="button" class="switch-link-btn" onclick="showView('organizer')">Masuk di sini</button>
            </div>
        </div>

        {{-- ══ FOOTER ══ --}}
        <div class="portal-bottom-footer">
            <a href="/">© {{ date('Y') }} Buyle.id</a> · Sistem Validasi Tiket Digital
        </div>

    </div>

    <script>
        function showView(viewId) {
            const views = {
                'menu': document.getElementById('viewMenu'),
                'register': document.getElementById('viewRegister'),
                'organizer': document.getElementById('viewOrganizer'),
                'staff': document.getElementById('viewStaff')
            };

            Object.values(views).forEach(function (v) {
                if (v) {
                    v.classList.remove('active');
                    v.style.display = 'none';
                }
            });

            const target = views[viewId];
            if (target) {
                target.style.display = 'block';
                // Trigger smooth CSS animation
                setTimeout(function () {
                    target.classList.add('active');
                    const firstInput = target.querySelector('input:not([type=hidden])');
                    if (firstInput) firstInput.focus();
                }, 20);
            }

            // Update URL query cleanly
            try {
                const url = new URL(window.location);
                if (viewId === 'menu') {
                    url.searchParams.delete('mode');
                } else {
                    url.searchParams.set('mode', viewId);
                }
                window.history.replaceState({}, '', url);
            } catch (e) { }
        }

        function togglePwd(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
            } else {
                input.type = 'password';
                btn.innerHTML = '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
            }
        }
    </script>
</body>

</html>

