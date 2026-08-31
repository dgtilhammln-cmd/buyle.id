<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Creator Studio') – buyle.id</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            background: #0b120c;
            color: #0f172a;
            height: 100vh;
            overflow: hidden;
            display: flex;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 500 !important;
        }

        strong, b { font-weight: 600 !important; }

        /* ── SIDEBAR (Dark Shell) ── */
        .cr-sidebar {
            width: 240px;
            background: #0b120c;
            display: flex;
            flex-direction: column;
            padding: 1.75rem 0 1.75rem 1.25rem;
            flex-shrink: 0;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .cr-brand-area {
            padding: 0 1.25rem 0 0;
            margin-bottom: 2.25rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .cr-brand-area img {
            height: 36px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            display: block;
        }

        .cr-brand-logo-fallback {
            height: 36px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
        }

        .cr-brand-logo-dot {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 0.85rem;
            color: #fff;
        }

        .cr-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .cr-nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 1.25rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #7e8d81;
            text-decoration: none;
            border-radius: 30px 0 0 30px;
            transition: color 0.2s;
            position: relative;
        }

        .cr-nav-link:hover {
            color: #fff;
        }

        /* Seamless Active Tab Cutout into White Canvas */
        .cr-nav-link.active {
            background: #ffffff;
            color: #0b120c !important;
            font-weight: 800;
            position: relative;
            z-index: 10;
        }

        .cr-nav-link.active svg {
            stroke: #0b120c !important;
            stroke-width: 2.2;
        }

        .cr-nav-link.active::before {
            content: '';
            position: absolute;
            top: -24px;
            right: 0;
            width: 24px;
            height: 24px;
            background: transparent;
            border-bottom-right-radius: 24px;
            box-shadow: 10px 10px 0 10px #ffffff;
            pointer-events: none;
        }

        .cr-nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -24px;
            right: 0;
            width: 24px;
            height: 24px;
            background: transparent;
            border-top-right-radius: 24px;
            box-shadow: 10px -10px 0 10px #ffffff;
            pointer-events: none;
        }

        .cr-nav-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            stroke-width: 2;
        }

        .cr-sidebar-bottom {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            padding-top: 1.5rem;
            margin-right: 1.25rem;
        }

        .cr-bottom-link-web {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            color: #a3e635;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            padding: 0.65rem 0.85rem;
            border-radius: 14px;
            transition: all 0.2s;
        }

        .cr-bottom-link-web:hover {
            background: rgba(163, 230, 53, 0.12);
        }

        .cr-bottom-link-logout {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            color: #f87171;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            padding: 0.65rem 0.85rem;
            border-radius: 14px;
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            font-family: inherit;
            transition: all 0.2s;
            text-align: left;
        }

        .cr-bottom-link-logout:hover {
            background: rgba(248, 113, 113, 0.12);
        }

        /* ── MAIN CANVAS (Large White Rounded Card) ── */
        .cr-main-wrapper {
            margin-left: 240px;
            flex: 1;
            min-width: 0;
            padding: 1.25rem 1.25rem 1.25rem 0;
            display: flex;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .cr-main-canvas {
            flex: 1;
            background: #ffffff;
            border-radius: 40px;
            padding: 2.75rem 3rem;
            min-height: calc(100vh - 2.5rem);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            display: flex;
            flex-direction: column;
            align-self: flex-start;
            width: 100%;
        }

        /* Global button style */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 0.65rem 1.35rem;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
        }

        /* Flash messages */
        .flash-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 16px;
            padding: 0.875rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: #15803d;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .flash-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 16px;
            padding: 0.875rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: #b91c1c;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        /* ── RESPONSIVE MOBILE ── */
        .cr-mobile-bar {
            display: none;
            background: #0b120c;
            padding: 1rem 1.25rem;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .cr-mobile-toggle {
            background: #111a13;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 12px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .cr-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 90;
        }

        @media (max-width: 1024px) {
            body {
                flex-direction: row;
                height: 100vh;
                overflow: hidden;
            }

            .cr-mobile-bar {
                display: flex;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 80;
                height: 56px;
            }

            .cr-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                z-index: 100;
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
                height: 100vh;
            }

            .cr-sidebar.open {
                transform: translateX(0);
            }

            .cr-overlay.open {
                display: block;
            }

            .cr-main-wrapper {
                margin-left: 0 !important;
                padding: 0.75rem;
                padding-top: calc(56px + 0.75rem);
            }

            .cr-main-canvas {
                border-radius: 28px;
                padding: 1.5rem;
                min-height: auto;
            }

            .cr-nav-link.active::before,
            .cr-nav-link.active::after {
                display: none;
            }

            .cr-nav-link {
                border-radius: 16px;
                margin-right: 1.25rem;
            }
        }
    </style>
    @yield('styles')
</head>

<body>

    {{-- MOBILE TOPBAR --}}
    <div class="cr-mobile-bar">
        @php $mobileLogo = \App\Models\Setting::get('logo'); @endphp
        @if($mobileLogo)
            <img src="{{ asset('storage/' . $mobileLogo) }}" alt="buyle.id" style="height:30px;width:auto;object-fit:contain;">
        @else
            <div style="display:flex;align-items:center;gap:0.4rem;">
                <div style="width:24px;height:24px;border-radius:6px;background:linear-gradient(135deg,#1eb349,#a5cf37);display:flex;align-items:center;justify-content:center;font-weight:900;font-size:0.8rem;color:#fff;">B</div>
                <span style="color:#fff;font-weight:700;font-size:0.95rem;">buyle.id</span>
            </div>
        @endif
        <button class="cr-mobile-toggle" id="crMobileToggle" aria-label="Menu">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </button>
    </div>


    <div class="cr-overlay" id="crOverlay"></div>

    {{-- SIDEBAR --}}
    <aside class="cr-sidebar" id="crSidebar">
        @php 
            $logo = \App\Models\Setting::get('logo'); 
            $isBuyer = auth()->user()->role === 'buyer';
            $cp = auth()->user()->creatorProfile;
            $storeSlug = $cp->store_slug ?? '';
        @endphp

        {{-- Brand / Logo Only --}}
        <div class="cr-brand-area">
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="buyle.id">
            @else
                <div class="cr-brand-logo-fallback">
                    <div class="cr-brand-logo-dot">B</div>
                    buyle.id
                </div>
            @endif
        </div>

        {{-- Nav Links --}}
        <nav class="cr-nav">
            <a href="{{ $isBuyer ? '#' : route('creator.dashboard') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Overview
                @if($isBuyer) <span style="margin-left:auto;font-size:0.7rem;">🔒</span> @endif
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.products.index') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.products*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    <line x1="12" y1="12" x2="12" y2="16"/>
                    <line x1="10" y1="14" x2="14" y2="14"/>
                </svg>
                Produk Digital
                @if($isBuyer) <span style="margin-left:auto;font-size:0.7rem;">🔒</span> @endif
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.groups.index') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.groups*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
                Kelompok Produk
                @if($isBuyer) <span style="margin-left:auto;font-size:0.7rem;">🔒</span> @endif
            </a>

            <a href="{{ $isBuyer ? route('creator.onboarding') : route('creator.profile.edit') }}"
                class="cr-nav-link {{ request()->routeIs('creator.profile*', 'creator.onboarding') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M20 21a8 8 0 1 0-16 0"/>
                </svg>
                Profil & Toko
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.bio.index') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.bio*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
                Link in Bio
                @if($isBuyer) <span style="margin-left:auto;font-size:0.7rem;">🔒</span> @endif
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.membership') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.membership') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                Membership Seller
                @if($isBuyer) <span style="margin-left:auto;font-size:0.7rem;">🔒</span> @endif
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.sales.report') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.sales*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
                Laporan Penjualan
                @if($isBuyer) <span style="margin-left:auto;font-size:0.7rem;">🔒</span> @endif
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.payout.settings') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.payout*', 'creator.groups*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                Saldo & Pencairan
                @if($isBuyer) <span style="margin-left:auto;font-size:0.7rem;">🔒</span> @endif
            </a>
        </nav>

        {{-- Bottom Actions --}}
        <div class="cr-sidebar-bottom">
            <a href="{{ $storeSlug ? route('store.show', $storeSlug) : url('/') }}" target="_blank" class="cr-bottom-link-web">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                    <polyline points="15 3 21 3 21 9" />
                    <line x1="10" y1="14" x2="21" y2="3" />
                </svg>
                Lihat Web
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="cr-bottom-link-logout">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                        <line x1="12" y1="2" x2="12" y2="12"></line>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CANVAS WRAPPER --}}
    <div class="cr-main-wrapper">
        <main class="cr-main-canvas">
            {{-- Header Area: Title & Actions --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1 style="font-size: 1.5rem; font-weight: 800; color: #0b120c; margin: 0; font-family: 'Montserrat', sans-serif;">
                    @yield('page_title', 'Dashboard')
                </h1>
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    @yield('topbar_actions')
                </div>
            </div>

            @if(session('success'))
                <div class="flash-success">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning_onboarding'))
                <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:18px;padding:1.25rem 1.5rem;margin-bottom:2rem;display:flex;align-items:flex-start;gap:0.85rem;box-shadow:0 2px 12px rgba(245,158,11,0.08);">
                    <div style="width:34px;height:34px;border-radius:50%;background:#FEF3C7;color:#D97706;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div>
                        <div style="font-size:0.95rem;font-weight:700;color:#92400E;margin-bottom:0.2rem;">Langkah Terakhir: Lengkapi Data Creator</div>
                        <div style="font-size:0.82rem;color:#B45309;line-height:1.5;">{{ session('warning_onboarding') }}</div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="flash-error">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Interactive Locked Feature Modal for Buyers --}}
    <div id="lockedFeatureModal" style="display:none;position:fixed;inset:0;background:rgba(11,18,12,0.7);backdrop-filter:blur(6px);z-index:9999;align-items:center;justify-content:center;padding:1rem;">
        <div style="background:#fff;border-radius:28px;max-width:440px;width:100%;padding:2.25rem 2rem;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,0.25);position:relative;animation:modalScale 0.25s cubic-bezier(0.34,1.56,0.64,1);">
            <div style="width:64px;height:64px;border-radius:50%;background:#F0FDF4;color:#1eb349;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;border:2px solid #BBF7D0;">
                <svg width="30" height="30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h3 style="font-size:1.25rem;font-weight:800;color:#0F172A;margin:0 0 0.5rem;font-family:'Montserrat',sans-serif;">Yuk, Jadi Creator Dulu! 🚀</h3>
            <p style="font-size:0.875rem;color:#64748B;line-height:1.6;margin:0 0 1.5rem;font-family:'Montserrat',sans-serif;">
                Fitur ini akan <strong style="color:#1eb349;font-weight:700;">langsung terbuka</strong> setelah Anda melengkapi form data toko di halaman ini. Gratis dan hanya butuh 1 menit!
            </p>
            <div style="display:flex;gap:0.75rem;justify-content:center;">
                <button type="button" onclick="closeLockedModal()" style="padding:0.75rem 1.75rem;border-radius:999px;background:linear-gradient(135deg, #1eb349, #a5cf37);color:#fff;border:none;font-weight:700;font-size:0.875rem;cursor:pointer;font-family:'Montserrat',sans-serif;box-shadow:0 4px 14px rgba(30,179,73,0.35);">
                    Isi Data Sekarang
                </button>
            </div>
        </div>
    </div>
    <style>
        @keyframes modalScale { from { opacity:0; transform:scale(0.92); } to { opacity:1; transform:scale(1); } }
    </style>

    <script>
        const toggle = document.getElementById('crMobileToggle');
        const sidebar = document.getElementById('crSidebar');
        const overlay = document.getElementById('crOverlay');
        if(toggle) {
            toggle.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('open'); });
            overlay.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); });
        }

        function showLockedModal(e) {
            if(e) e.preventDefault();
            const modal = document.getElementById('lockedFeatureModal');
            if(modal) {
                modal.style.display = 'flex';
            }
        }
        function closeLockedModal() {
            const modal = document.getElementById('lockedFeatureModal');
            if(modal) {
                modal.style.display = 'none';
            }
            const firstInput = document.querySelector('#profileForm input[name="store_name"]');
            if(firstInput) firstInput.focus();
        }
    </script>
    @yield('scripts')
</body>

</html>