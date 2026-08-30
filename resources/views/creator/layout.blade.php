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
            background: #0b120c;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* ── SIDEBAR (Dark Shell) ── */
        .cr-sidebar {
            width: 240px;
            background: #0b120c;
            display: flex;
            flex-direction: column;
            padding: 1.75rem 0 1.75rem 1.25rem;
            flex-shrink: 0;
            min-height: 100vh;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .cr-brand-box {
            background: #111a13;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin-right: 1.25rem;
            margin-bottom: 2.25rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .cr-brand-logo-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 900;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .cr-brand-text {
            font-size: 0.95rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
        }

        .cr-brand-sub {
            font-size: 0.62rem;
            color: #a3e635;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
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
            flex: 1;
            min-width: 0;
            padding: 1.25rem 1.25rem 1.25rem 0;
            display: flex;
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
                flex-direction: column;
            }

            .cr-mobile-bar {
                display: flex;
            }

            .cr-sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                z-index: 100;
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
            }

            .cr-sidebar.open {
                transform: translateX(0);
            }

            .cr-overlay.open {
                display: block;
            }

            .cr-main-wrapper {
                padding: 0.75rem;
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
        <div style="display:flex;align-items:center;gap:0.5rem;">
            <div class="cr-brand-logo-icon">B</div>
            <div class="cr-brand-text">buyle.id <span style="color:#a3e635;font-size:0.7rem;">CREATOR</span></div>
        </div>
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

        {{-- Brand Box --}}
        <div class="cr-brand-box">
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="buyle.id" style="height:26px;width:auto;max-width:32px;object-fit:contain;">
            @else
                <div class="cr-brand-logo-icon">B</div>
            @endif
            <div>
                <div class="cr-brand-text">buyle.id</div>
                <div class="cr-brand-sub">Creator Studio</div>
            </div>
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
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                Link Settings
                @if($isBuyer) <span style="margin-left:auto;font-size:0.7rem;">🔒</span> @endif
            </a>

            <a href="{{ $isBuyer ? route('creator.onboarding') : route('creator.profile.edit') }}"
                class="cr-nav-link {{ request()->routeIs('creator.profile*', 'creator.onboarding') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 19l7-7 3 3-7 7-3-3z"></path>
                    <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path>
                    <path d="M2 2l7.586 7.586"></path>
                    <circle cx="11" cy="11" r="2"></circle>
                </svg>
                Tema & Visual
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.sales.report') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.sales*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
                Analytics
                @if($isBuyer) <span style="margin-left:auto;font-size:0.7rem;">🔒</span> @endif
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.payout.settings') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.payout*', 'creator.groups*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                </svg>
                Premium & Saldo
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