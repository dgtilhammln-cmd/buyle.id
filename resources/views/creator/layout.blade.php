<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Creator Dashboard') – buyle.id</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Montserrat', sans-serif; background: #f1f5f1; color: #1a1a1a; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .cr-sidebar {
            position: fixed; left: 0; top: 0; bottom: 0;
            width: 240px;
            background: #0f1f0f;
            z-index: 100;
            display: flex; flex-direction: column;
            transition: transform 0.3s;
        }
        .cr-sidebar-logo {
            padding: 1.25rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .cr-sidebar-logo img { height: 32px; width: auto; }
        .cr-sidebar-logo-text { font-size: 0.9rem; font-weight: 800; color: #fff; }
        .cr-sidebar-logo-sub { font-size: 0.65rem; font-weight: 500; color: #a5cf37; letter-spacing: 0.08em; text-transform: uppercase; }

        .cr-nav { flex: 1; padding: 1rem 0; overflow-y: auto; }
        .cr-nav-section { padding: 0.5rem 1.5rem 0.25rem; font-size: 0.6rem; font-weight: 700; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.12em; }
        .cr-nav-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.6);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        .cr-nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .cr-nav-link.active { color: #a5cf37; background: rgba(165,207,55,0.1); border-left-color: #a5cf37; }
        .cr-nav-link svg { flex-shrink: 0; opacity: 0.7; }
        .cr-nav-link.active svg { opacity: 1; }

        .cr-sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .cr-user-chip {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
        }
        .cr-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .cr-user-name { font-size: 0.8rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .cr-user-role { font-size: 0.65rem; color: #a5cf37; font-weight: 600; }
        .cr-logout-btn {
            display: flex; align-items: center; justify-content: center; gap: 0.4rem;
            width: 100%; margin-top: 0.5rem;
            padding: 0.5rem; border-radius: 8px;
            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);
            color: #f87171; font-family: 'Montserrat', sans-serif;
            font-size: 0.75rem; font-weight: 600; cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .cr-logout-btn:hover { background: rgba(239,68,68,0.2); }

        /* ── MAIN AREA ── */
        .cr-main { margin-left: 240px; min-height: 100vh; display: flex; flex-direction: column; }
        .cr-topbar {
            position: sticky; top: 0; z-index: 50;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e7f0e7;
            padding: 0 2rem;
            height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem;
        }
        .cr-topbar-title { font-size: 1rem; font-weight: 800; color: #0f1f0f; }
        .cr-topbar-breadcrumb { font-size: 0.75rem; color: #64748B; margin-top: 0.1rem; }
        .cr-topbar-actions { display: flex; align-items: center; gap: 0.75rem; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff; border: none; border-radius: 8px;
            padding: 0.5rem 1rem; font-family: 'Montserrat', sans-serif;
            font-size: 0.8rem; font-weight: 700; cursor: pointer;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(30,179,73,0.3);
            transition: all 0.2s;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(30,179,73,0.4); }

        .cr-content { flex: 1; padding: 2rem; }

        /* ── FLASH ── */
        .flash-success {
            background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px;
            padding: 0.75rem 1rem; margin-bottom: 1.5rem;
            font-size: 0.8rem; color: #15803d; display: flex; align-items: center; gap: 0.5rem;
        }
        .flash-error {
            background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
            padding: 0.75rem 1rem; margin-bottom: 1.5rem;
            font-size: 0.8rem; color: #b91c1c; display: flex; align-items: center; gap: 0.5rem;
        }

        /* ── MOBILE ── */
        .cr-mobile-toggle {
            display: none; position: fixed; top: 1rem; left: 1rem; z-index: 200;
            background: #0f1f0f; border: none; border-radius: 8px;
            width: 40px; height: 40px; align-items: center; justify-content: center;
            cursor: pointer; color: #fff;
        }
        .cr-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 90; }

        @media (max-width: 991px) {
            .cr-sidebar { transform: translateX(-100%); }
            .cr-sidebar.open { transform: translateX(0); }
            .cr-overlay.open { display: block; }
            .cr-main { margin-left: 0; }
            .cr-topbar { padding: 0 1rem 0 4rem; }
            .cr-content { padding: 1.25rem; }
            .cr-mobile-toggle { display: flex; }
        }
    </style>
    @yield('styles')
</head>
<body>

<button class="cr-mobile-toggle" id="crMobileToggle" aria-label="Menu">
    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
</button>
<div class="cr-overlay" id="crOverlay"></div>

{{-- SIDEBAR --}}
<aside class="cr-sidebar" id="crSidebar">
    <div class="cr-sidebar-logo">
        @php $logo = \App\Models\Setting::get('logo'); @endphp
        @if($logo)
            <img src="{{ asset('storage/'.$logo) }}" alt="buyle.id">
        @endif
        <div>
            <div class="cr-sidebar-logo-text">buyle.id</div>
            <div class="cr-sidebar-logo-sub">Creator Studio</div>
        </div>
    </div>

    <nav class="cr-nav">
        <div class="cr-nav-section">Utama</div>
        <a href="{{ route('creator.dashboard') }}" class="cr-nav-link {{ request()->routeIs('creator.dashboard') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Beranda
        </a>

        <div class="cr-nav-section" style="margin-top:0.75rem;">Katalog</div>
        <a href="{{ route('creator.products.index') }}" class="cr-nav-link {{ request()->routeIs('creator.products*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            Produk Digital
        </a>
        <a href="{{ route('creator.groups.index') }}" class="cr-nav-link {{ request()->routeIs('creator.groups*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
            Kelompok Produk
        </a>

        <div class="cr-nav-section" style="margin-top:0.75rem;">Keuangan</div>
        <a href="{{ route('creator.payout.settings') }}" class="cr-nav-link {{ request()->routeIs('creator.payout*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            Pencairan Dana
        </a>
        <a href="{{ route('creator.sales.report') }}" class="cr-nav-link {{ request()->routeIs('creator.sales*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            Laporan Penjualan
        </a>

        <div class="cr-nav-section" style="margin-top:0.75rem;">Toko</div>
        <a href="{{ route('creator.profile.edit') }}" class="cr-nav-link {{ request()->routeIs('creator.profile*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Profil & Pengaturan
        </a>
        <a href="{{ url('/') }}" target="_blank" class="cr-nav-link">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            Lihat Website
        </a>
    </nav>

    <div class="cr-sidebar-footer">
        <div class="cr-user-chip">
            <div class="cr-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div style="overflow:hidden;">
                <div class="cr-user-name">{{ auth()->user()->name }}</div>
                <div class="cr-user-role">Creator</div>
            </div>
        </div>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="cr-logout-btn">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
</aside>

{{-- MAIN --}}
<div class="cr-main">
    <header class="cr-topbar">
        <div>
            <div class="cr-topbar-title">@yield('page_title', 'Dashboard')</div>
            <div class="cr-topbar-breadcrumb">Creator Studio › @yield('breadcrumb', 'Beranda')</div>
        </div>
        <div class="cr-topbar-actions">
            @yield('topbar_actions')
        </div>
    </header>

    <div class="cr-content">
        @if(session('success'))
            <div class="flash-success">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-error">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    const toggle = document.getElementById('crMobileToggle');
    const sidebar = document.getElementById('crSidebar');
    const overlay = document.getElementById('crOverlay');
    toggle.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('open'); });
    overlay.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); });
</script>
@yield('scripts')
</body>
</html>
