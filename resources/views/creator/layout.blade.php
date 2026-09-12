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
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            background-attachment: fixed;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 500 !important;
        }

        strong, b { font-weight: 600 !important; }

        /* ── Premium Sleek Gray Scrollbars ── */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        ::-webkit-scrollbar-button {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        ::-webkit-scrollbar-corner {
            background: transparent !important;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.4);
            border-radius: 10px;
            border: 2px solid transparent;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.6);
        }
        * {
            scrollbar-width: auto;
            scrollbar-color: rgba(255, 255, 255, 0.4) transparent;
        }

        /* Creator Sidebar Scrollbar */
        .cr-sidebar::-webkit-scrollbar {
            width: 8px;
        }
        .cr-sidebar::-webkit-scrollbar-button {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .cr-sidebar::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 10px;
            margin: 6px 0;
        }
        .cr-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        .cr-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        .cr-sidebar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        /* Creator Main Content Wrapper Scrollbar */
        .cr-main-wrapper::-webkit-scrollbar {
            width: 10px;
        }
        .cr-main-wrapper::-webkit-scrollbar-button {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .cr-main-wrapper::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 10px;
        }
        .cr-main-wrapper::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.4);
            border-radius: 10px;
            border: 2px solid transparent;
        }
        .cr-main-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.6);
        }
        .cr-main-wrapper {
            scrollbar-width: auto;
            scrollbar-color: rgba(255, 255, 255, 0.4) transparent;
        }

        /* ── SIDEBAR (Seamless Transparent over Body Gradient) ── */
        .cr-sidebar {
            width: 240px;
            background: transparent;
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
            color: rgba(255, 255, 255, 0.88);
            text-decoration: none;
            border-radius: 30px 0 0 30px;
            transition: all 0.2s;
            position: relative;
        }

        .cr-nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
        }

        /* Seamless Active Tab Cutout into Canvas */
        .cr-nav-link.active {
            background: #ffffff;
            color: #1eb349 !important;
            font-weight: 800;
            position: relative;
            z-index: 10;
        }

        .cr-nav-link.active svg {
            stroke: #1eb349 !important;
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

        /* ── MAIN CANVAS (Seamless Full White Canvas to Bottom) ── */
        .cr-main-wrapper {
            margin-left: 240px;
            flex: 1;
            min-width: 0;
            padding: 1.25rem 0 0 0;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .cr-main-canvas {
            flex: 1 0 auto;
            background: #ffffff;
            border-radius: 40px 0 0 0;
            padding: 1.75rem 2.25rem 4rem 2.25rem;
            min-height: calc(100vh - 1.25rem);
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            width: 100%;
            box-sizing: border-box;
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
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            padding: 1rem 1.25rem;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .cr-mobile-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
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

        /* ── Super App Mobile Bottom Capsule Navigation ── */
        .superapp-bottom-nav {
            display: none;
        }

        @media (max-width: 1024px) {
            body {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
                overflow-x: hidden;
                overflow-y: auto;
                padding-bottom: 88px;
            }

            .cr-mobile-bar {
                display: flex;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 80;
                height: 64px;
                background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
                border-radius: 0 0 24px 24px;
            }

            /* Hide hamburger from top bar since bottom nav has Menu button */
            .cr-mobile-toggle {
                display: none;
            }

            /* Hide canvas desktop profile button on mobile so avatar stays only at top-right mobile bar */
            #creator-profile-container {
                display: none !important;
            }

            .cr-sidebar {
                width: 280px;
                background: linear-gradient(180deg, #1eb349 0%, #a5cf37 100%) !important;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                z-index: 10000;
                box-shadow: 10px 0 40px rgba(0, 0, 0, 0.4);
                height: 100vh;
                padding-right: 1.25rem;
            }

            .cr-sidebar.open {
                transform: translateX(0);
            }

            .cr-overlay {
                backdrop-filter: blur(6px);
                z-index: 9999;
            }

            .cr-overlay.open {
                display: block;
            }

            .cr-main-wrapper {
                margin-left: 0 !important;
                padding: 0;
                padding-top: 54px;
                height: auto;
                min-height: calc(100vh - 64px - 88px);
                max-width: 100vw;
                overflow-x: hidden;
                overflow-y: visible;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                background: transparent;
            }

            .cr-main-canvas {
                border-radius: 20px 20px 20px 20px;
                padding: 1rem 1rem 1rem;
                margin: 0;
                min-height: calc(100vh - 64px - 88px);
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                overflow-x: hidden;
                align-self: stretch;
            }

            .cr-nav-link.active::before,
            .cr-nav-link.active::after {
                display: none;
            }

            .cr-nav-link {
                border-radius: 14px;
                margin-right: 1.25rem;
                color: rgba(255, 255, 255, 0.95);
            }

            .cr-nav-link.active {
                background: #ffffff !important;
                color: #1eb349 !important;
                font-weight: 800;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            }

            .cr-nav-link.active svg {
                stroke: #1eb349 !important;
            }

            /* ── Super App Floating Capsule Bottom Nav ── */
            .superapp-bottom-nav {
                display: flex;
                position: fixed;
                bottom: 14px;
                left: 50%;
                transform: translateX(-50%);
                width: calc(100% - 28px);
                max-width: 480px;
                background: linear-gradient(135deg, rgba(30, 179, 73, 0.97) 0%, rgba(165, 207, 55, 0.97) 100%);
                backdrop-filter: blur(20px) saturate(1.8);
                -webkit-backdrop-filter: blur(20px) saturate(1.8);
                border: 1.5px solid rgba(255, 255, 255, 0.22);
                border-radius: 999px;
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35), 0 4px 16px rgba(18, 104, 41, 0.5), inset 0 1px 0 rgba(255,255,255,0.15);
                z-index: 9998;
                padding: 8px 16px;
                justify-content: space-around;
                align-items: center;
            }

            .superapp-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                color: rgba(255, 255, 255, 0.65);
                font-size: 0.6rem;
                font-weight: 600;
                letter-spacing: 0.02em;
                gap: 3px;
                padding: 0.4rem 0.6rem;
                border-radius: 999px;
                transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
                background: transparent;
                border: none;
                cursor: pointer;
                font-family: 'Montserrat', sans-serif;
                min-width: 48px;
            }

            .superapp-nav-item:hover,
            .superapp-nav-item:active {
                color: #ffffff;
                transform: translateY(-2px);
            }

            .superapp-nav-item.active {
                color: #ffffff;
                background: rgba(255, 255, 255, 0.18);
                font-weight: 700;
                box-shadow: 0 2px 12px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.2);
            }

            .superapp-nav-item svg {
                stroke-width: 2;
                transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .superapp-nav-item.active svg {
                stroke-width: 2.5;
                transform: scale(1.1);
            }
        }
    </style>
    @yield('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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
        {{-- Profile icon on the right --}}
        @php
            $topbarUser = auth()->user();
            $topbarCp = $topbarUser->creatorProfile;
            $topbarRawAvatar = $topbarCp?->avatar ?? $topbarUser->avatar ?? null;
            $topbarAvatarUrl = null;
            if ($topbarRawAvatar) {
                $topbarAvatarUrl = \Illuminate\Support\Str::startsWith($topbarRawAvatar, ['http://', 'https://'])
                    ? $topbarRawAvatar
                    : asset('storage/' . $topbarRawAvatar);
            }
        @endphp
        <div style="position:relative;">
            <button type="button" onclick="toggleMobileProfileDropdown(event)"
               style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.15);border:2px solid rgba(255,255,255,0.35);overflow:hidden;cursor:pointer;flex-shrink:0;transition:all 0.2s;padding:0;"
               title="Profil Saya">
                @if($topbarAvatarUrl)
                    <img src="{{ $topbarAvatarUrl }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M20 21a8 8 0 1 0-16 0"/>
                    </svg>
                @endif
            </button>
            {{-- Mobile Profile Dropdown --}}
            <div id="mobile-profile-dropdown" style="display:none;opacity:0;transform:translateY(-8px);position:absolute;top:calc(100% + 10px);right:0;width:220px;background:#fff;border:1px solid #E2E8F0;border-radius:18px;box-shadow:0 12px 40px rgba(0,0,0,0.15);z-index:99999;overflow:hidden;transition:opacity .2s,transform .2s;padding:0.5rem 0;font-family:inherit;">
                <div style="padding:0.65rem 1rem;border-bottom:1px solid #F1F5F9;">
                    <div style="font-size:0.82rem;font-weight:700;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.72rem;color:#64748B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px;">{{ auth()->user()->email }}</div>
                </div>
                <div style="padding:0.25rem 0;">
                    <a href="{{ route('creator.profile.edit') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.55rem 1rem;color:#334155;font-size:0.8rem;font-weight:600;text-decoration:none;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                        Profil &amp; Toko
                    </a>
                    <a href="{{ route('creator.bio.index') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.55rem 1rem;color:#334155;font-size:0.8rem;font-weight:600;text-decoration:none;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        Link in Bio
                    </a>
                    <a href="{{ route('creator.payout.settings') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.55rem 1rem;color:#334155;font-size:0.8rem;font-weight:600;text-decoration:none;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="16" cy="12" r="2"/></svg>
                        Saldo &amp; Pencairan
                    </a>
                    <div style="border-top:1px solid #F1F5F9;margin:0.2rem 0;"></div>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" style="width:100%;display:flex;align-items:center;gap:0.6rem;padding:0.55rem 1rem;color:#DC2626;background:none;border:none;font-size:0.8rem;font-weight:600;cursor:pointer;text-align:left;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
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
                @if($isBuyer) <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:auto;opacity:0.7;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> @endif
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
                @if($isBuyer) <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:auto;opacity:0.7;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> @endif
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.groups.index') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.groups*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
                Kelompok Produk
                @if($isBuyer) <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:auto;opacity:0.7;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> @endif
            </a>

            <a href="{{ $isBuyer ? route('creator.onboarding') : route('creator.profile.edit') }}"
                class="cr-nav-link {{ request()->routeIs('creator.profile*', 'creator.onboarding') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M20 21a8 8 0 1 0-16 0"/>
                </svg>
                Profil & Store
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.bio.index') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.bio*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
                Link in Bio
                @if($isBuyer) <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:auto;opacity:0.7;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> @endif
            </a>

{{-- Hidden temporarily --}}
            {{-- <a href="{{ $isBuyer ? '#' : route('creator.membership') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.membership') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                Membership Seller
                @if($isBuyer) <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:auto;opacity:0.7;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> @endif
            </a> --}}

            <a href="{{ $isBuyer ? '#' : route('creator.sales.report') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.sales*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
                Laporan Penjualan
                @if($isBuyer) <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:auto;opacity:0.7;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> @endif
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.payout.settings') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.payout*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="2" y="6" width="20" height="12" rx="2"/>
                    <circle cx="16" cy="12" r="2"/>
                    <path d="M6 12h.01"/>
                </svg>
                Saldo & Pencairan
                @if($isBuyer) <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:auto;opacity:0.7;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> @endif
            </a>

            <a href="{{ $isBuyer ? '#' : route('creator.ticket.scanner') }}"
                onclick="{{ $isBuyer ? 'showLockedModal(event)' : '' }}"
                class="cr-nav-link {{ request()->routeIs('creator.ticket.scanner*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/>
                    <rect x="7" y="7" width="10" height="10" rx="1"/>
                </svg>
                Scan Tiket & Data Kehadiran
                @if($isBuyer) <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:auto;opacity:0.7;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> @endif
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 800; color: #0b120c; margin: 0 0 0.25rem; font-family: 'Montserrat', sans-serif;">
                        @yield('page_title', 'Dashboard')
                    </h1>
                    @hasSection('page_subtitle')
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0; font-weight: 500;">
                        @yield('page_subtitle')
                    </p>
                    @endif
                </div>
                <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                    @yield('topbar_actions')

                    @php
                        $crUser = auth()->user();
                        $crAvatar = $crUser->avatar ?? ($crUser->creatorProfile->avatar ?? null);
                        $crAvatarUrl = null;
                        if ($crAvatar) {
                            $crAvatarUrl = \Illuminate\Support\Str::startsWith($crAvatar, ['http://', 'https://']) ? $crAvatar : asset('storage/' . $crAvatar);
                        }
                    @endphp

                    {{-- Creator Desktop Profile Dropdown --}}
                    <div style="position:relative;" id="creator-profile-container">
                        <button id="creator-profile-btn" onclick="toggleCreatorProfile(event)" type="button"
                                style="border:none;background:none;padding:0;cursor:pointer;display:flex;align-items:center;outline:none;" title="Profil Saya">
                            <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg, #1eb349, #a5cf37);display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:0.9rem;flex-shrink:0;box-shadow:0 2px 8px rgba(30,179,73,0.25);overflow:hidden;border:2px solid #fff;">
                                @if($crAvatarUrl)
                                    <img src="{{ $crAvatarUrl }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    {{ strtoupper(substr($crUser->name ?? 'C', 0, 1)) }}
                                @endif
                            </div>
                        </button>

                        <div id="creator-profile-dropdown" style="display:none;opacity:0;transform:translateY(-8px);position:absolute;top:calc(100% + 10px);right:0;width:240px;background:#fff;border:1px solid #E2E8F0;border-radius:18px;box-shadow:0 12px 40px rgba(0,0,0,0.08), 0 2px 10px rgba(0,0,0,0.02);z-index:9999;overflow:hidden;transition:opacity .2s,transform .2s;padding:0.5rem 0;font-family:inherit;">
                            <div style="padding:0.75rem 1rem;border-bottom:1px solid #F1F5F9;">
                                <div style="font-size:0.875rem;font-weight:700;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $crUser->name }}
                                </div>
                                <div style="font-size:0.75rem;color:#64748B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px;">
                                    {{ $crUser->email }}
                                </div>
                            </div>
                            <div style="padding:0.35rem 0;">
                                <a href="{{ route('creator.profile.edit') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.6rem 1rem;color:#334155;font-size:0.825rem;font-weight:600;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                                    Profil & Toko
                                </a>
                                <a href="{{ route('creator.bio.index') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.6rem 1rem;color:#334155;font-size:0.825rem;font-weight:600;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                    Link in Bio
                                </a>
                                <a href="{{ route('creator.payout.settings') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.6rem 1rem;color:#334155;font-size:0.825rem;font-weight:600;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="16" cy="12" r="2"/></svg>
                                    Saldo & Pencairan
                                </a>
                                <div style="border-top:1px solid #F1F5F9;margin:0.25rem 0;"></div>
                                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                    @csrf
                                    <button type="submit" style="width:100%;display:flex;align-items:center;gap:0.6rem;padding:0.6rem 1rem;color:#DC2626;background:none;border:none;font-size:0.825rem;font-weight:600;cursor:pointer;text-align:left;transition:background 0.15s;" onmouseover="this.style.background='#FEF2F2'" onmouseout="this.style.background='transparent'">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
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

    {{-- ── Super App Bottom Navigation (Mobile Only) ── --}}
    <nav class="superapp-bottom-nav" id="superappBottomNav">
        @php
            $bottomNavIsBuyer = auth()->user()->role === 'buyer';
        @endphp

        {{-- Overview --}}
        <a href="{{ $bottomNavIsBuyer ? '#' : route('creator.dashboard') }}"
           onclick="{{ $bottomNavIsBuyer ? 'showLockedModal(event)' : '' }}"
           class="superapp-nav-item {{ request()->routeIs('creator.dashboard') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            <span>Overview</span>
        </a>

        {{-- Produk --}}
        <a href="{{ $bottomNavIsBuyer ? '#' : route('creator.products.index') }}"
           onclick="{{ $bottomNavIsBuyer ? 'showLockedModal(event)' : '' }}"
           class="superapp-nav-item {{ request()->routeIs('creator.products*') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="2" y="7" width="20" height="14" rx="2"/>
                <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
            </svg>
            <span>Produk</span>
        </a>

        {{-- Laporan Penjualan --}}
        <a href="{{ $bottomNavIsBuyer ? '#' : route('creator.sales.report') }}"
           onclick="{{ $bottomNavIsBuyer ? 'showLockedModal(event)' : '' }}"
           class="superapp-nav-item {{ request()->routeIs('creator.sales*') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
            <span>Laporan</span>
        </a>

        {{-- Saldo --}}
        <a href="{{ $bottomNavIsBuyer ? '#' : route('creator.payout.settings') }}"
           onclick="{{ $bottomNavIsBuyer ? 'showLockedModal(event)' : '' }}"
           class="superapp-nav-item {{ request()->routeIs('creator.payout*') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="2" y="6" width="20" height="12" rx="2"/>
                <circle cx="16" cy="12" r="2"/>
                <path d="M6 12h.01"/>
            </svg>
            <span>Saldo</span>
        </a>

        {{-- Menu (open sidebar drawer) --}}
        <button type="button" class="superapp-nav-item"
                onclick="document.getElementById('crSidebar').classList.toggle('open'); document.getElementById('crOverlay').classList.toggle('open');">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
            <span>Menu</span>
        </button>
    </nav>

    {{-- Interactive Locked Feature Modal for Buyers --}}
    <div id="lockedFeatureModal" style="display:none;position:fixed;inset:0;background:rgba(11,18,12,0.7);backdrop-filter:blur(6px);z-index:9999;align-items:center;justify-content:center;padding:1rem;">
        <div style="background:#fff;border-radius:28px;max-width:440px;width:100%;padding:2.25rem 2rem;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,0.25);position:relative;animation:modalScale 0.25s cubic-bezier(0.34,1.56,0.64,1);">
            <div style="width:64px;height:64px;border-radius:50%;background:#F0FDF4;color:#1eb349;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;border:2px solid #BBF7D0;">
                <svg width="30" height="30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h3 style="font-size:1.25rem;font-weight:800;color:#0F172A;margin:0 0 0.5rem;font-family:'Montserrat',sans-serif;display:flex;align-items:center;justify-content:center;gap:0.4rem;">
                Yuk, Jadi Creator Dulu!
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1eb349" stroke-width="2"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-3.05 11a22.35 22.35 0 0 1-3.95 2z"/></svg>
            </h3>
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

        // Toggle button (hamburger) - may not exist on mobile
        if(toggle) {
            toggle.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('open'); });
        }

        // Overlay always closes sidebar regardless of toggle existence
        if(overlay) {
            overlay.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); });
        }

        // Mobile topbar profile dropdown
        function toggleMobileProfileDropdown(e) {
            e.stopPropagation();
            const dd = document.getElementById('mobile-profile-dropdown');
            if (!dd) return;
            const isHidden = dd.style.display === 'none' || dd.style.display === '';
            if (isHidden) {
                dd.style.display = 'block';
                requestAnimationFrame(() => { dd.style.opacity = '1'; dd.style.transform = 'translateY(0)'; });
            } else {
                dd.style.opacity = '0'; dd.style.transform = 'translateY(-8px)';
                setTimeout(() => { dd.style.display = 'none'; }, 200);
            }
        }

        // Close mobile dropdown on outside click
        window.addEventListener('click', function(e) {
            const mdd = document.getElementById('mobile-profile-dropdown');
            if (mdd && !e.target.closest('.cr-mobile-bar')) {
                mdd.style.opacity = '0'; mdd.style.transform = 'translateY(-8px)';
                setTimeout(() => { mdd.style.display = 'none'; }, 200);
            }
        });

        function showLockedModal(e) {
            if(e) e.preventDefault();
            const modal = document.getElementById('lockedFeatureModal');
            if(modal) { modal.style.display = 'flex'; }
        }
        function closeLockedModal() {
            const modal = document.getElementById('lockedFeatureModal');
            if(modal) { modal.style.display = 'none'; }
            const firstInput = document.querySelector('#profileForm input[name="store_name"]');
            if(firstInput) firstInput.focus();
        }
    </script>
    
    {{-- ── Global Interactive Cropper.js Modal ── --}}
    <div id="cropperModal" style="display:none; position:fixed; inset:0; background:rgba(11,18,12,0.85); backdrop-filter:blur(8px); z-index:99999; align-items:center; justify-content:center; padding:1rem;">
        <div style="background:#fff; border-radius:24px; max-width:620px; width:100%; padding:1.5rem; box-shadow:0 24px 60px rgba(0,0,0,0.3); position:relative; display:flex; flex-direction:column; gap:1rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #f1f5f9; padding-bottom:0.75rem;">
                <h3 style="font-size:1.05rem; font-weight:800; color:#0f172a; margin:0; font-family:'Montserrat',sans-serif;" id="cropperModalTitle">Potong & Atur Ukuran Foto</h3>
                <button type="button" onclick="closeCropperModal()" style="border:none; background:transparent; font-size:1.5rem; color:#94a3b8; cursor:pointer; line-height:1;">&times;</button>
            </div>
            
            <div style="height:380px; width:100%; overflow:hidden; background:#0b120c; border-radius:14px; display:flex; align-items:center; justify-content:center;">
                <img id="cropperImageSrc" src="" style="max-width:100%; max-height:380px; display:block;">
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                <div style="display:flex; gap:0.4rem;">
                    <button type="button" onclick="if(cropperObj)cropperObj.zoom(0.1)" class="crop-btn-tool" title="Zoom In">🔍+</button>
                    <button type="button" onclick="if(cropperObj)cropperObj.zoom(-0.1)" class="crop-btn-tool" title="Zoom Out">🔍-</button>
                    <button type="button" onclick="if(cropperObj)cropperObj.rotate(-45)" class="crop-btn-tool" title="Rotate Left">↺</button>
                    <button type="button" onclick="if(cropperObj)cropperObj.rotate(45)" class="crop-btn-tool" title="Rotate Right">↻</button>
                    <button type="button" onclick="if(cropperObj)cropperObj.reset()" class="crop-btn-tool" title="Reset" style="display:inline-flex;align-items:center;gap:0.25rem;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                        Reset
                    </button>
                </div>
                <div style="display:flex; gap:0.6rem;">
                    <button type="button" onclick="closeCropperModal()" style="padding:0.6rem 1.25rem; border-radius:999px; border:1.5px solid #cbd5e1; background:#fff; color:#64748b; font-weight:700; font-size:0.82rem; cursor:pointer;">Batal</button>
                    <button type="button" id="applyCropBtn" style="padding:0.6rem 1.5rem; border-radius:999px; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; border:none; font-weight:700; font-size:0.82rem; cursor:pointer; box-shadow:0 4px 14px rgba(30,179,73,0.35);">Potong & Gunakan Foto</button>
                </div>
            </div>
        </div>
    </div>

    <style>
    .crop-btn-tool {
        padding: 0.4rem 0.75rem; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc;
        color: #334155; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s;
    }
    .crop-btn-tool:hover { background: #e2e8f0; color: #0f172a; }
    </style>

    <script>
    let cropperObj = null;

    function initImageCropper(fileInput, options = {}) {
        if (!fileInput || !fileInput.files || !fileInput.files[0]) return;

        const file = fileInput.files[0];
        if (!file.type.startsWith('image/')) return;

        const title = options.title || 'Potong & Atur Ukuran Foto';
        const aspectRatio = options.aspectRatio || 1;
        const targetWidth = options.width || (aspectRatio === 1 ? 600 : 1200);
        const targetHeight = options.height || (aspectRatio === 1 ? 600 : 400);

        document.getElementById('cropperModalTitle').innerText = title;

        const reader = new FileReader();
        reader.onload = function(e) {
            const image = document.getElementById('cropperImageSrc');
            image.src = e.target.result;

            const modal = document.getElementById('cropperModal');
            modal.style.display = 'flex';

            if (cropperObj) cropperObj.destroy();

            cropperObj = new Cropper(image, {
                aspectRatio: aspectRatio,
                viewMode: 1,
                autoCropArea: 0.9,
                responsive: true,
                restore: false,
                checkCrossOrigin: false,
            });

            document.getElementById('applyCropBtn').onclick = function() {
                const canvas = cropperObj.getCroppedCanvas({
                    width: targetWidth,
                    height: targetHeight,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                canvas.toBlob(function(blob) {
                    const croppedFile = new File([blob], file.name, { type: file.type || 'image/jpeg', lastModified: Date.now() });

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    fileInput.files = dataTransfer.files;

                    if (options.previewTarget) {
                        const prevEl = typeof options.previewTarget === 'string' ? document.querySelector(options.previewTarget) : options.previewTarget;
                        if (prevEl) {
                            if (prevEl.tagName === 'IMG') prevEl.src = canvas.toDataURL();
                            else prevEl.style.backgroundImage = `url(${canvas.toDataURL()})`;
                        }
                    }

                    closeCropperModal();
                }, file.type || 'image/jpeg', 0.92);
            };
        };
        reader.readAsDataURL(file);
    }

    function closeCropperModal() {
        const modal = document.getElementById('cropperModal');
        if (modal) modal.style.display = 'none';
        if (cropperObj) {
            cropperObj.destroy();
            cropperObj = null;
        }
    }

    function toggleCreatorProfile(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('creator-profile-dropdown');
        if (!dropdown) return;
        const isHidden = dropdown.style.display === 'none' || dropdown.style.display === '';
        if (isHidden) {
            dropdown.style.display = 'block';
            requestAnimationFrame(() => {
                dropdown.style.opacity = '1';
                dropdown.style.transform = 'translateY(0)';
            });
        } else {
            dropdown.style.opacity = '0';
            dropdown.style.transform = 'translateY(-8px)';
            setTimeout(() => { dropdown.style.display = 'none'; }, 200);
        }
    }

    window.addEventListener('click', function(e) {
        const profDropdown = document.getElementById('creator-profile-dropdown');
        if (profDropdown && !e.target.closest('#creator-profile-container')) {
            profDropdown.style.opacity = '0';
            profDropdown.style.transform = 'translateY(-8px)';
            setTimeout(() => { profDropdown.style.display = 'none'; }, 200);
        }
    });
    </script>
    @yield('scripts')
</body>

</html>