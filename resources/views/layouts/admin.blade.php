<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0">
@php
    $adminLogo = \App\Models\Setting::get('logo');
@endphp
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard') | buyle.id Admin</title>
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=3">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">
<link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=3">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stack('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
@php
    $tAccent = \App\Models\Setting::get('color_accent') ?? '#FFD700';
    $tMain   = \App\Models\Setting::get('color_main') ?? '#0F0F0F';
    $tText   = \App\Models\Setting::get('color_text') ?? '#FFFFFF';
@endphp
:root {
    --yellow: {{ $tAccent }} !important;
    --bg: #F4F7FE !important;
    --bg2: #FFFFFF;
    --bg3: #F8FAFC;
    --border: #E2E8F0;
    --text1: #1E293B !important;
    --text2: #475569;
    --text3: #A3AED0;
    --sb-bg: #1eb349;
    --sb-bg2: rgba(255,255,255,0.1);
    --sb-border: rgba(255,255,255,0.12);
    --sb-text: rgba(255,255,255,0.65);
}
body { font-family: 'Montserrat', sans-serif; background: var(--bg); color: var(--text1); min-height: 100vh; display: flex; }

/* ── Global Scrollbar (content area & main page) ── */
::-webkit-scrollbar { width: 10px; height: 10px; }
::-webkit-scrollbar-button { display: none !important; width: 0 !important; height: 0 !important; }
::-webkit-scrollbar-corner { background: transparent !important; }
::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 2px solid #f8fafc; }
::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
* { scrollbar-width: auto; scrollbar-color: #cbd5e1 #f8fafc; }
/* ═══════ CAPSULE FLOATING HOVER-EXPAND SIDEBAR ═══════ */
#sidebar {
  width: 76px;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 200;
  display: flex;
  flex-direction: column;
  padding: 0.85rem 0.65rem;
  background: #F4F7FE;
  transition: width 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease, transform 0.3s ease;
  box-shadow: 4px 0 20px rgba(0,0,0,0.03);
  overflow-x: hidden;
  overflow-y: auto;
  scrollbar-width: none;
}
#sidebar::-webkit-scrollbar { display: none; }
#sidebar:hover {
  width: 250px;
  box-shadow: 12px 0 35px rgba(0,0,0,0.12);
}

/* Capsule Section Card */
.sb-capsule-card {
  background: #ffffff;
  border-radius: 28px;
  padding: 8px 6px;
  margin-bottom: 0.75rem;
  box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
  border: 1px solid rgba(226, 232, 240, 0.8);
  display: flex;
  flex-direction: column;
  gap: 4px;
  align-items: center;
  transition: padding 0.3s ease;
}
#sidebar:hover .sb-capsule-card {
  align-items: stretch;
  padding: 10px 8px;
}

/* Brand Header Box */
.sb-brand-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  margin-bottom: 6px;
  padding: 4px;
}

.sb-brand-box {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: #ffffff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  border: 1px solid #E2E8F0;
  text-decoration: none;
  overflow: hidden;
  transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
  padding: 4px;
}

.sb-logo-favicon {
  width: 28px;
  height: 28px;
  object-fit: contain;
  display: block;
  transition: opacity 0.2s ease;
}

.sb-logo-full {
  height: 30px;
  width: auto;
  max-width: 150px;
  object-fit: contain;
  display: none;
  opacity: 0;
  transition: opacity 0.25s ease;
}

.sb-logo-fallback-badge {
  display: none;
  align-items: center;
  gap: 8px;
}

#sidebar:hover .sb-brand-box {
  width: 100%;
  height: 48px;
  border-radius: 16px;
  padding: 6px 14px;
  justify-content: center;
}

#sidebar:hover .sb-logo-favicon {
  display: none !important;
}

#sidebar:hover .sb-logo-full {
  display: block !important;
  opacity: 1 !important;
}

#sidebar:hover .sb-logo-fallback-badge {
  display: flex !important;
}

/* Nav Item Link */
.sb-link {
  display: flex;
  align-items: center;
  height: 42px;
  border-radius: 999px;
  padding: 0;
  width: 100%;
  color: #475569;
  text-decoration: none;
  font-size: 0.8125rem;
  font-weight: 600;
  transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
  cursor: pointer;
  border: none;
  background: transparent;
  position: relative;
  text-align: left;
}

.sb-link-icon {
  width: 44px;
  height: 42px;
  min-width: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  flex-shrink: 0;
  color: #475569;
  transition: color 0.2s ease;
}

.sb-link-text {
  opacity: 0;
  max-width: 0;
  overflow: hidden;
  white-space: nowrap;
  transition: opacity 0.25s ease, max-width 0.25s ease;
  color: inherit;
  font-size: 0.8125rem;
  font-weight: 600;
}
#sidebar:hover .sb-link-text {
  opacity: 1;
  max-width: 150px;
}

/* Hover state for inactive links */
.sb-link:hover {
  background: #F0FDF4;
  color: #1eb349;
}
.sb-link:hover .sb-link-icon {
  color: #1eb349;
}

/* ACTIVE LINK — Signature Green Gradient Pill */
.sb-link.active {
  background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
  color: #ffffff !important;
  box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35) !important;
}
.sb-link.active .sb-link-icon {
  color: #ffffff !important;
}
.sb-link.active .sb-link-text {
  color: #ffffff !important;
  font-weight: 700;
}
.sb-link.active svg {
  stroke: #ffffff !important;
}

/* Badge overlay */
.sb-badge {
  margin-left: auto;
  margin-right: 8px;
  background: #EF4444;
  color: #fff;
  font-size: 0.6rem;
  font-weight: 800;
  padding: 0.15rem 0.45rem;
  border-radius: 100px;
  opacity: 0;
  transition: opacity 0.25s ease;
}
#sidebar:hover .sb-badge {
  opacity: 1;
}

/* MAIN Container Adjust & Smooth Shift on Sidebar Hover */
#main {
  margin-left: 76px;
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  min-width: 0;
  transition: margin-left 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

#sidebar:hover ~ #main {
  margin-left: 250px !important;
}
#topbar{background:transparent;padding:1.5rem 2rem 0.5rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;backdrop-filter:blur(10px)}
.topbar-left{display:flex;align-items:center;gap:.75rem}
.topbar-breadcrumb{font-size:.75rem;color:var(--text3);display:flex;align-items:center;gap:.375rem;font-weight:600;}
.topbar-title{font-size:1.5rem;font-weight:700;color:var(--text1)}
.topbar-right{display:flex;align-items:center;gap:.75rem;background:#fff;padding:.375rem .375rem .375rem 1rem;border-radius:100px;box-shadow:0 4px 15px rgba(0,0,0,.03)}
.topbar-icon-btn{width:36px;height:36px;background:var(--bg3);border:none;border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--text3);cursor:pointer;transition:all .2s;text-decoration:none}
.topbar-icon-btn:hover{background:#e7f0e7;color:var(--text1)}
.avatar{width:36px;height:36px;background:linear-gradient(135deg, #1eb349, #a5cf37);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:.875rem;flex-shrink:0;box-shadow:0 2px 8px rgba(30,179,73,0.3)}
.success-toast{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80;font-size:.75rem;padding:.375rem .875rem;border-radius:100px}
#content{padding:1.75rem;flex:1}
.errors-box{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);padding:.875rem 1.25rem;margin-bottom:1.5rem;border-radius:6px}
.errors-box li{color:#f87171;font-size:.8125rem;margin-left:1rem}

/* Global buyle.id Green Gradient Buttons */
.btn-primary, .btn-submit, .btn-success, .u-btn-primary,
button.btn-primary, a.btn-primary {
  background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
  color: #fff !important;
  box-shadow: 0 4px 14px rgba(30,179,73,0.35) !important;
  border: none !important;
  border-radius: 999px !important;
  transition: transform .2s, box-shadow .2s !important;
}
.btn-primary:hover, .btn-submit:hover, .btn-success:hover, .u-btn-primary:hover,
button.btn-primary:hover, a.btn-primary:hover {
  transform: translateY(-1px) !important;
  box-shadow: 0 6px 20px rgba(30,179,73,0.45) !important;
}

/* MOBILE */
#sb-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:199}
#mobile-toggle{display:none;background:none;border:none;color:#fff;cursor:pointer;padding:.5rem}
@media(max-width:1024px){
  #sidebar{transform:translateX(-100%)}
  #sidebar.open{transform:translateX(0)}
  #sb-overlay.open{display:block}
  #main{margin-left:0!important}
  #mobile-toggle{display:flex!important}
  #content{padding:.875rem!important}
  #topbar{padding:.75rem 1rem!important}
}
@media(max-width:480px){
  #content{padding:.625rem!important}
  .admin-card{padding:1rem!important}
}
/* PREMIUM LOADER */
#premium-loader {
    position: fixed;
    inset: 0;
    background: #ffffff;
    z-index: 999999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #0F172A;
    transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.6s;
}
#premium-loader.hide {
    opacity: 0;
    visibility: hidden;
    transform: scale(1.02);
}
.pl-logo-wrap {
    width: 64px;
    height: 64px;
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1eb349;
    font-weight: 800;
    font-size: 1.25rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.03), 0 0 40px rgba(30,179,73,0.08);
    position: relative;
    animation: pl-bounce 2s infinite ease-in-out;
}
@keyframes pl-bounce {
    0%, 100% { transform: translateY(0); box-shadow: 0 10px 25px rgba(0,0,0,0.03), 0 0 40px rgba(30,179,73,0.08); }
    50% { transform: translateY(-6px); box-shadow: 0 15px 30px rgba(0,0,0,0.05), 0 0 50px rgba(30,179,73,0.12); }
}
.pl-progress-wrap {
    width: 220px;
    height: 4px;
    background: #F1F5F9;
    position: relative;
    overflow: hidden;
    margin-bottom: 1rem;
    border-radius: 4px;
}
.pl-progress-bar {
    position: absolute;
    top: 0; left: 0; height: 100%;
    background: #1eb349;
    width: 0%;
    transition: width 0.1s linear;
    border-radius: 4px;
    box-shadow: 0 0 10px rgba(30,179,73,0.3);
}
.pl-percent {
    font-size: 0.8rem;
    font-weight: 500;
    letter-spacing: 0.05em;
    color: #64748B;
}
.pl-percent span {
    color: #0F172A;
    font-weight: 700;
}
.pl-text {
    font-size: 0.7rem;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    margin-top: 1.5rem;
    font-weight: 600;
}
</style>
</head>
<body>

<div id="premium-loader" style="display:none;">
    <div class="pl-logo-wrap">
       @if($adminLogo)
         <img src="{{ asset('storage/'.$adminLogo) }}" alt="Logo" style="width:36px;height:auto;object-fit:contain;">
       @else
         <svg width="28" height="28" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
       @endif
    </div>
    <div class="pl-progress-wrap">
        <div class="pl-progress-bar" id="pl-bar"></div>
    </div>
    <div class="pl-percent"><span id="pl-num">0</span>%</div>
    <div class="pl-text" id="pl-text">MEMUAT DASHBOARD...</div>
</div>

<div id="sb-overlay" onclick="closeSb()"></div>

  <!-- SIDEBAR CAPSULE HOVER-EXPAND -->
<aside id="sidebar">
  @php
    $uPerm = auth()->user();
  @endphp
  {{-- CARD 1: BRAND & UTAMA --}}
  <div class="sb-capsule-card">
    <div class="sb-brand-wrapper">
      <a href="{{ route('admin.dashboard') }}" class="sb-brand-box" title="buyle.id Admin">
        <img src="{{ asset('favicon.png') }}?v=3" alt="Favicon" class="sb-logo-favicon">
        @if($adminLogo)
          <img src="{{ asset('storage/'.$adminLogo) }}" alt="Logo" class="sb-logo-full">
        @else
          <div class="sb-logo-fallback-badge">
            <svg width="22" height="22" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
            <span style="font-weight:800;font-size:1rem;color:#1eb349;font-family:'Montserrat',sans-serif;">buyle.id</span>
          </div>
        @endif
      </a>
    </div>

    @if(!$uPerm || $uPerm->hasMenuPermission('dashboard'))
    <a href="{{ route('admin.dashboard') }}" class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>
      </div>
      <span class="sb-link-text">Dashboard</span>
    </a>

    <a href="{{ route('admin.analytics') }}" class="sb-link {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}" title="Analytics">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      </div>
      <span class="sb-link-text">Analytics</span>
    </a>
    @endif

    @if(!$uPerm || $uPerm->hasMenuPermission('articles'))
    <a href="{{ route('admin.articles.index') }}" class="sb-link {{ request()->routeIs('admin.articles*') ? 'active' : '' }}" title="Artikel">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
      </div>
      <span class="sb-link-text">Artikel</span>
    </a>

    <a href="{{ route('admin.faqs.index') }}" class="sb-link {{ request()->routeIs('admin.faqs*') ? 'active' : '' }}" title="FAQ">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/></svg>
      </div>
      <span class="sb-link-text">FAQ</span>
    </a>

    <a href="{{ route('admin.clients.index') }}" class="sb-link {{ request()->routeIs('admin.clients*') ? 'active' : '' }}" title="Klien">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      </div>
      <span class="sb-link-text">Klien</span>
    </a>

    <a href="{{ route('admin.testimonials.index') }}" class="sb-link {{ request()->routeIs('admin.testimonials*') ? 'active' : '' }}" title="Testimoni">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
      </div>
      <span class="sb-link-text">Testimoni</span>
    </a>
    @endif
  </div>

  {{-- CARD 2: E-COMMERCE & DATA --}}
  <div class="sb-capsule-card">
    @if(!$uPerm || $uPerm->hasMenuPermission('orders'))
    <a href="{{ route('admin.orders.index') }}" class="sb-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" title="Pesanan">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      </div>
      <span class="sb-link-text">Pesanan</span>
    </a>
    @endif

    @if(!$uPerm || $uPerm->hasMenuPermission('products'))
    <a href="{{ route('admin.coupons.index') }}" class="sb-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}" title="Kupon / Voucher">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
      </div>
      <span class="sb-link-text">Kupon / Voucher</span>
    </a>

    @php $pendingWlCount = \App\Models\Product::where('is_whitelabel', true)->where('whitelabel_approval_status', 'pending')->count(); @endphp
    <a href="{{ route('admin.whitelabel.index') }}" class="sb-link {{ request()->routeIs('admin.whitelabel*') ? 'active' : '' }}" title="Approval Whitelabel">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <span class="sb-link-text">Approval Whitelabel</span>
      @if($pendingWlCount > 0)<span class="sb-badge">{{ $pendingWlCount }}</span>@endif
    </a>

    <a href="{{ route('admin.product-categories.index') }}" class="sb-link {{ request()->routeIs('admin.product-categories*') ? 'active' : '' }}" title="Kategori Marketplace">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
      </div>
      <span class="sb-link-text">Kategori Marketplace</span>
    </a>

    <a href="{{ route('admin.couriers.index') }}" class="sb-link {{ request()->routeIs('admin.couriers*') ? 'active' : '' }}" title="Pengiriman / Kurir">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      </div>
      <span class="sb-link-text">Kurir & Pengiriman</span>
    </a>
    @endif

    @if(!$uPerm || $uPerm->hasMenuPermission('buyers'))
    <a href="{{ route('admin.users.index') }}" class="sb-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" title="Pengguna (Buyer)">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      </div>
      <span class="sb-link-text">Pengguna (Buyer)</span>
    </a>

    <a href="{{ route('admin.creator-resources.index') }}" class="sb-link {{ request()->routeIs('admin.creator-resources*') ? 'active' : '' }}" title="Audit Resource Creator">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
      </div>
      <span class="sb-link-text">Resource Creator</span>
    </a>
    @endif

    @if(!$uPerm || $uPerm->hasMenuPermission('leads'))
    @php $newLeads = \App\Models\Lead::where('status','new')->count(); @endphp
    <a href="{{ route('admin.leads.index') }}" class="sb-link {{ request()->routeIs('admin.leads*') ? 'active' : '' }}" title="Laporan Chat">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      </div>
      <span class="sb-link-text">Laporan Chat</span>
      @if($newLeads > 0)<span class="sb-badge">{{ $newLeads }}</span>@endif
    </a>
    @endif

    @if(!$uPerm || $uPerm->hasMenuPermission('reports'))
    @php $pendingReportsCount = \App\Models\Report::where('status', 'pending')->count(); @endphp
    <a href="{{ route('admin.reports.index') }}" class="sb-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" title="Laporan Penyalahgunaan">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
      </div>
      <span class="sb-link-text">Laporan Penyalahgunaan</span>
      @if($pendingReportsCount > 0)<span class="sb-badge">{{ $pendingReportsCount }}</span>@endif
    </a>
    @endif

    @if(!$uPerm || $uPerm->hasMenuPermission('banners'))
    <a href="{{ route('admin.hero_slides.index') }}" class="sb-link {{ request()->routeIs('admin.hero_slides*') ? 'active' : '' }}" title="Banner Hero">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 2l-4 5-4-5"/></svg>
      </div>
      <span class="sb-link-text">Banner Hero</span>
    </a>

    <a href="{{ route('admin.usp.index') }}" class="sb-link {{ request()->routeIs('admin.usp*') ? 'active' : '' }}" title="USP Bar">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
      </div>
      <span class="sb-link-text">USP Bar</span>
    </a>

    <a href="{{ route('admin.promo-sections.index') }}" class="sb-link {{ request()->routeIs('admin.promo-sections*') ? 'active' : '' }}" title="Promo & Deals">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      </div>
      <span class="sb-link-text">Promo & Deals</span>
    </a>
    @endif
  </div>

  {{-- CARD 3: PENGATURAN & AKSI --}}
  <div class="sb-capsule-card" style="margin-top: auto;">
    @if(!$uPerm || $uPerm->hasMenuPermission('settings'))
    <a href="{{ route('admin.notifications.index') }}" class="sb-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}" title="Notifikasi">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
      </div>
      <span class="sb-link-text">Notifikasi</span>
    </a>

    <a href="{{ route('admin.settings') }}" class="sb-link {{ request()->routeIs('admin.settings*') || request()->routeIs('admin.wa*') ? 'active' : '' }}" title="Pengaturan">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
      </div>
      <span class="sb-link-text">Pengaturan</span>
    </a>

    <a href="{{ route('admin.apikeys.index') }}" class="sb-link {{ request()->routeIs('admin.apikeys*') ? 'active' : '' }}" title="API & Integrasi">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
      </div>
      <span class="sb-link-text">API & Integrasi</span>
    </a>
    @endif

    @if(!$uPerm || $uPerm->hasMenuPermission('admin_users'))
    <a href="{{ route('admin.admin-users.index') }}" class="sb-link {{ request()->routeIs('admin.admin-users*') ? 'active' : '' }}" title="Akun Admin & Akses">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
      </div>
      <span class="sb-link-text">Akun Admin & Akses</span>
    </a>
    @endif

    <a href="{{ url('/') }}" target="_blank" class="sb-link" title="Lihat Website">
      <div class="sb-link-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      </div>
      <span class="sb-link-text">Lihat Website</span>
    </a>

    <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;width:100%" id="logout-form">
      @csrf
      <button type="button" class="sb-link" onclick="handleLogoutClick()" title="Logout">
        <div class="sb-link-icon">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </div>
        <span class="sb-link-text">Logout</span>
      </button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<div id="main">
  <!-- TOPBAR -->
  <header id="topbar">
    <div class="topbar-left">
      <button id="mobile-toggle" onclick="toggleSb()" aria-label="Menu">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <div>
        <div class="topbar-breadcrumb">
          <span>Admin Panel</span>
          <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
          <span style="color:var(--text2);">@yield('page-title','Dashboard')</span>
        </div>
      </div>
    </div>
    <div class="topbar-right">
      @if(session('success'))
      <span class="success-toast">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:4px;"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
      </span>
      @endif
      <a href="{{ url('/en/') }}" target="_blank" class="topbar-icon-btn" title="Lihat Website">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
      </a>
      <a href="{{ route('admin.settings') }}" class="topbar-icon-btn" title="Pengaturan">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
      </a>
      @php
        // Fetch 4 types of notifications
        $nUsers = \App\Models\User::latest()->take(3)->get()->map(fn($u) => (object)[
            'title' => 'Akun Baru: '.$u->name,
            'subtitle' => $u->email,
            'time' => $u->created_at,
            'link' => route('admin.users.show', $u->id),
            'bg' => '#dcfce7', 'color' => '#1eb349', 'type' => 'user'
        ]);
        $nOrders = \App\Models\Order::whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success->value))
            ->with('user')->latest()->take(3)->get()->map(fn($o) => (object)[
            'title' => 'Pembayaran Sukses #'.($o->order_number ?? $o->id),
            'subtitle' => 'Rp '.number_format($o->total, 0, ',', '.').' • '.($o->user->name ?? 'Guest'),
            'time' => $o->created_at,
            'link' => route('admin.orders.show', $o->id),
            'bg' => '#e0e7ff', 'color' => '#4f46e5', 'type' => 'payment'
        ]);
        $nWls = \App\Models\Product::where('is_whitelabel', true)->where('whitelabel_approval_status', 'pending')
            ->with('seller')->latest()->take(3)->get()->map(fn($p) => (object)[
            'title' => 'Approval Whitelabel: '.$p->name,
            'subtitle' => 'Dari Seller: '.($p->seller->name ?? 'Seller'),
            'time' => $p->updated_at ?? $p->created_at,
            'link' => route('admin.whitelabel.index', ['status' => 'pending']),
            'bg' => '#fef3c7', 'color' => '#d97706', 'type' => 'whitelabel'
        ]);
        $nLeads = \App\Models\Lead::where('status', 'new')->latest()->take(3)->get()->map(fn($l) => (object)[
            'title' => 'Lead Baru: '.$l->name,
            'subtitle' => 'Produk: '.($l->product ?? 'Inquiry Umum'),
            'time' => $l->created_at,
            'link' => route('admin.leads.show', $l->id),
            'bg' => '#f1f5f9', 'color' => '#475569', 'type' => 'lead'
        ]);

        $recentNotifs = collect()->concat($nUsers)->concat($nOrders)->concat($nWls)->concat($nLeads)->sortByDesc('time')->take(6);
        $totalNotifCount = $nUsers->count() + $nOrders->count() + $nWls->count() + $nLeads->count();
      @endphp
      <div style="position:relative;" id="notif-container">
        <button id="notif-btn" title="Notifikasi ({{ $totalNotifCount }})" onclick="toggleNotif(event)" style="position:relative;width:38px;height:38px;border-radius:12px;border:1.5px solid var(--border,#E4E7F0);background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;box-shadow:0 2px 8px rgba(0,0,0,.04);">
          <svg width="16" height="16" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
          @if($totalNotifCount > 0)
          <span id="notif-badge" style="position:absolute;top:-4px;right:-4px;background:linear-gradient(135deg, #1eb349, #a5cf37);color:#fff;font-size:.55rem;font-weight:800;min-width:16px;height:16px;border-radius:100px;display:flex;align-items:center;justify-content:center;padding:0 3px;border:2px solid #F4F7FE;">{{ $totalNotifCount }}</span>
          @endif
        </button>

        {{-- Notification Dropdown --}}
        <div id="notif-dropdown" style="display:none;opacity:0;transform:translateY(-8px);position:absolute;top:calc(100% + 10px);right:0;width:360px;background:#fff;border:1px solid #E2E8F0;border-radius:24px;box-shadow:0 12px 40px rgba(0,0,0,0.06), 0 2px 10px rgba(0,0,0,0.02);z-index:9999;overflow:hidden;transition:opacity .25s,transform .25s;font-family:inherit;">
          {{-- Header --}}
          <div style="padding:1.125rem 1.25rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #F1F5F9;">
            <div style="font-size:0.95rem;font-weight:700;color:#0F172A;letter-spacing:-.01em;">Notifikasi Baru</div>
            <a href="{{ route('admin.notifications.index') }}" style="font-size:0.75rem;font-weight:700;color:#1eb349;text-decoration:none;display:flex;align-items:center;gap:0.25rem;">
              Lihat Semua
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
          </div>

          {{-- Notification Items --}}
          <div style="max-height:350px;overflow-y:auto;" class="notif-scroll">
            @if($recentNotifs->count() > 0)
              @foreach($recentNotifs as $notif)
                <a href="{{ $notif->link }}" style="display:flex;align-items:flex-start;gap:.875rem;padding:0.9rem 1.25rem;border-bottom:1px solid #F1F5F9;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                  {{-- Icon badge --}}
                  <div style="width:36px;height:36px;border-radius:10px;background:{{ $notif->bg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                    @if($notif->type === 'user')
                      <svg width="16" height="16" fill="none" stroke="{{ $notif->color }}" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    @elseif($notif->type === 'payment')
                      <svg width="16" height="16" fill="none" stroke="{{ $notif->color }}" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    @elseif($notif->type === 'whitelabel')
                      <svg width="16" height="16" fill="none" stroke="{{ $notif->color }}" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    @else
                      <svg width="16" height="16" fill="none" stroke="{{ $notif->color }}" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    @endif
                  </div>
                  {{-- Content --}}
                  <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.15rem;">
                      <div style="font-size:.825rem;font-weight:700;color:#0F172A;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $notif->title }}</div>
                      <div style="font-size:.675rem;font-weight:500;color:#94A3B8;flex-shrink:0;margin-left:6px;">{{ \Carbon\Carbon::parse($notif->time)->locale('id')->diffForHumans(null, true) }} lalu</div>
                    </div>
                    <div style="font-size:.75rem;color:#64748B;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                      {{ $notif->subtitle }}
                    </div>
                  </div>
                </a>
              @endforeach
            @else
              <div style="padding:2.5rem 1.25rem;text-align:center;">
                <div style="width:40px;height:40px;background:#fff;border:1px solid #F1F5F9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                  <svg width="18" height="18" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div style="font-size:.875rem;font-weight:500;color:#0F172A;">Tidak ada notifikasi baru</div>
              </div>
            @endif
          </div>
          
          {{-- Footer --}}
          <div style="padding:.75rem 1.25rem;background:#F8FAFC;border-top:1px solid #F1F5F9;text-align:center;">
            <a href="{{ route('admin.notifications.index') }}" style="display:block;width:100%;color:#1eb349;font-size:.78rem;text-decoration:none;font-weight:700;font-family:inherit;">
              Lihat Semua Notifikasi ({{ $totalNotifCount }}) &rarr;
            </a>
          </div>
        </div>
      </div>
      <div class="avatar">{{ strtoupper(substr(session('admin_name','A'),0,1)) }}</div>
    </div>
  </header>

  <!-- CONTENT -->
  <main id="content">
    @if($errors->any())
    <div class="errors-box" style="margin-bottom:1.25rem;">
      <ul style="list-style:none;padding:0;display:flex;flex-direction:column;gap:.25rem;">
        @foreach($errors->all() as $e)
        <li style="display:flex;align-items:center;gap:.5rem;color:#f87171;font-size:.8125rem;">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          {{ $e }}
        </li>
        @endforeach
      </ul>
    </div>
    @endif
    @yield('content')
  </main>
</div>

@stack('scripts')
<script>
function switchNotifTab(el) {
  document.querySelectorAll('#notif-tabs .notif-tab').forEach(t => {
    t.style.background = 'transparent';
    t.style.boxShadow = 'none';
    t.style.color = '#64748B';
    t.style.fontWeight = '500';
  });
  el.style.background = '#fff';
  el.style.boxShadow = '0 1px 2px rgba(0,0,0,0.05)';
  el.style.color = '#0F172A';
  el.style.fontWeight = '600';
}
function toggleSb(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('sb-overlay').classList.toggle('open')}
function closeSb(){document.getElementById('sidebar').classList.remove('open');document.getElementById('sb-overlay').classList.remove('open')}
function sbSearch(q){
  q=q.toLowerCase();
  document.querySelectorAll('#sb-nav .sb-link').forEach(function(l){
    l.style.display=l.textContent.toLowerCase().includes(q)?'flex':'none';
  });
}
function toggleNotif(e) {
  e.stopPropagation();
  const dropdown = document.getElementById('notif-dropdown');
  const btn = document.getElementById('notif-btn');
  const isHidden = dropdown.style.display === 'none' || dropdown.style.display === '';
  if (isHidden) {
    dropdown.style.display = 'block';
    requestAnimationFrame(() => {
      dropdown.style.opacity = '1';
      dropdown.style.transform = 'translateY(0)';
    });
    btn.style.background = '#f0fdf4';
    btn.style.borderColor = '#bbf7d0';
  } else {
    dropdown.style.opacity = '0';
    dropdown.style.transform = 'translateY(-8px)';
    setTimeout(() => { dropdown.style.display = 'none'; }, 200);
    btn.style.background = '#fff';
    btn.style.borderColor = 'var(--border, #E4E7F0)';
  }
}
window.addEventListener('click', function(e) {
  const dropdown = document.getElementById('notif-dropdown');
  const btn = document.getElementById('notif-btn');
  if (dropdown && !e.target.closest('#notif-container')) {
    dropdown.style.opacity = '0';
    dropdown.style.transform = 'translateY(-8px)';
    setTimeout(() => { dropdown.style.display = 'none'; }, 200);
    if (btn) { btn.style.background = '#fff'; btn.style.borderColor = 'var(--border, #E4E7F0)'; }
  }
});
</script>

<!-- SweetAlert2 for Premium Popups -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Override window.alert
    window.alert = function(msg) {
        Swal.fire({
            text: msg,
            icon: 'info',
            confirmButtonColor: '#1eb349',
            confirmButtonText: 'Mengerti',
            background: '#ffffff',
            color: '#1E293B',
            customClass: { popup: 'premium-swal-popup' },
            showClass: { popup: 'animate__animated animate__fadeInDown animate__faster' },
            hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' }
        });
    };

    // Intercept form submissions that have confirm()
    document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
        const onsubmitStr = form.getAttribute('onsubmit');
        const match = onsubmitStr.match(/confirm\('([^']+)'\)/);
        const msg = match ? match[1] : 'Anda yakin?';
        
        form.removeAttribute('onsubmit'); // Remove native confirm
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                color: '#1E293B',
                customClass: { popup: 'premium-swal-popup' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Intercept onclick that have confirm()
    document.querySelectorAll('[onclick*="confirm"]').forEach(el => {
        const onclickStr = el.getAttribute('onclick');
        const match = onclickStr.match(/confirm\('([^']+)'\)/);
        const msg = match ? match[1] : 'Anda yakin?';
        
        // Remove native confirm so it doesn't trigger
        el.removeAttribute('onclick');
        
        el.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                color: '#1E293B',
                customClass: { popup: 'premium-swal-popup' }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Check if there's a submit action in the original onclick
                    const submitMatch = onclickStr.match(/document\.getElementById\('([^']+)'\)\.submit\(\)/);
                    if (submitMatch) {
                        document.getElementById(submitMatch[1]).submit();
                    } else if (onclickStr.includes('submit()')) {
                       // Try to execute the rest of the script if it's not a generic form
                       // This handles other random logic if present
                    }
                }
            });
        });
    });
});
</script>
<style>
.premium-swal-popup {
    border-radius: 20px !important;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08) !important;
    padding: 1.5rem !important;
}
div:where(.swal2-container) button:where(.swal2-styled) {
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    padding: 0.625rem 1.5rem !important;
}
div:where(.swal2-container) h2:where(.swal2-title) {
    font-size: 1.25rem !important;
    color: #1E293B !important;
}
div:where(.swal2-container) div:where(.swal2-html-container) {
    font-size: 0.95rem !important;
    color: #64748B !important;
}
</style>

<script>
// --- Premium Loader Logic ---
document.addEventListener("DOMContentLoaded", () => {
    if (sessionStorage.getItem('play_intro') === '1') {
        const loader = document.getElementById('premium-loader');
        loader.style.display = 'flex';
        
        const num = document.getElementById('pl-num');
        const bar = document.getElementById('pl-bar');
        const text = document.getElementById('pl-text');
        
        text.innerText = "MEMUAT DASHBOARD...";
        
        let progress = 0;
        let interval = setInterval(() => {
            progress += Math.floor(Math.random() * 12) + 4;
            if (progress > 100) progress = 100;
            num.innerText = progress;
            bar.style.width = progress + '%';
            
            if (progress === 100) {
                clearInterval(interval);
                setTimeout(() => {
                    loader.classList.add('hide');
                    sessionStorage.removeItem('play_intro');
                    setTimeout(() => loader.style.display = 'none', 800);
                }, 400);
            }
        }, 60);
    }
});

function handleLogoutClick() {
    const loader = document.getElementById('premium-loader');
    loader.style.display = 'flex';
    loader.classList.remove('hide');
    
    const num = document.getElementById('pl-num');
    const bar = document.getElementById('pl-bar');
    const text = document.getElementById('pl-text');
    
    text.innerText = "MENGAKHIRI SESI...";
    let progress = 0;
    num.innerText = '0';
    bar.style.width = '0%';

    let interval = setInterval(() => {
        progress += Math.floor(Math.random() * 12) + 6;
        if (progress > 100) progress = 100;
        num.innerText = progress;
        bar.style.width = progress + '%';
        
        if (progress === 100) {
            clearInterval(interval);
            setTimeout(() => {
                document.getElementById('logout-form').submit();
            }, 300);
        }
    }, 50);
}
</script>

</body>
</html>
