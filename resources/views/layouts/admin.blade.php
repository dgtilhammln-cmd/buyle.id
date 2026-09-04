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

/* ── Global Scrollbar (content area) ── */
::-webkit-scrollbar { width: 7px; height: 7px; }
::-webkit-scrollbar-track { background: #f0fdf4; border-radius: 10px; }
::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #1eb349, #16a34a); border-radius: 10px; border: 1.5px solid #f0fdf4; }
::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #16a34a, #15803d); }
* { scrollbar-width: thin; scrollbar-color: #1eb349 #f0fdf4; }
/* ═══════ SIDEBAR PREMIUM BLUE ═══════ */
#sidebar{
  width:240px;height:100vh;
  background:linear-gradient(135deg, #1eb349, #a5cf37);
  position:fixed;top:0;left:0;z-index:200;
  display:flex;flex-direction:column;
  transition:transform .3s ease;
  box-shadow:4px 0 24px rgba(30,179,73,0.25);
}
/* Logo area */
.sb-logo{padding:1.5rem 1.25rem 1.25rem;display:flex;align-items:center;gap:.75rem}
.sb-logo-badge{min-width:38px;max-width:100px;height:38px;background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.15);padding:4px 8px}
.sb-logo-badge img{height:100%;width:auto;max-width:100%;object-fit:contain}
.sb-logo-text{font-size:.9rem;font-weight:800;color:#fff;line-height:1.15;letter-spacing:-.01em}
.sb-logo-sub{font-size:.65rem;color:rgba(255,255,255,.6);font-weight:500;margin-top:1px}
/* Search */
.sb-search{padding:.5rem 1rem .875rem}
.sb-search input{width:100%;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.15);border-radius:10px;padding:.5rem .875rem;color:#fff;font-size:.8rem;outline:none;font-family:inherit;transition:all .2s;}
.sb-search input:focus{background:rgba(255,255,255,.18);border-color:rgba(255,255,255,.35)}
.sb-search input::placeholder{color:rgba(255,255,255,.45)}
/* Nav */
.sb-nav{flex:1;padding:.25rem 0;overflow-y:auto;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.35) transparent;}
.sb-nav::-webkit-scrollbar{width:5px}
.sb-nav::-webkit-scrollbar-track{background:rgba(0,0,0,.12);border-radius:10px;margin:4px 0}
.sb-nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.35);border-radius:10px;border:1px solid rgba(255,255,255,.08)}
.sb-nav::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,.55)}
.sb-sec{font-size:.575rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.4);padding:.875rem 1.375rem .3rem}
/* Inactive link */
.sb-link{
  display:flex;align-items:center;gap:.75rem;
  padding:.625rem 1rem;margin:.125rem .875rem;
  font-size:.8125rem;font-weight:600;color:rgba(255,255,255,.75);
  border-radius:12px;text-decoration:none;
  transition:all .2s;cursor:pointer;border:none;background:none;
  width:calc(100% - 1.75rem);text-align:left;
}
.sb-link:hover{background:rgba(255,255,255,.1);color:#fff}
.sb-link svg{flex-shrink:0;opacity:.75;transition:opacity .2s}
.sb-link:hover svg{opacity:1}
/* Active link — white card with cutout style */
.sb-link.active{
  background:#fff;
  color:#1eb349;
  box-shadow:0 4px 18px rgba(0,0,0,0.12);
}
.sb-link.active svg{opacity:1;stroke:#1eb349}
/* Badge */
.sb-badge{margin-left:auto;background:#EF4444;color:#fff;font-size:.575rem;font-weight:800;padding:.2rem .5rem;border-radius:100px;line-height:1.4;box-shadow:0 2px 8px rgba(239,68,68,0.4)}
.sb-link.active .sb-badge{background:#EF4444;color:#fff}
/* Bottom */
.sb-bottom{padding:.875rem 1rem;border-top:1px solid rgba(255,255,255,.12)}
.sb-bottom-card{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);border-radius:14px;padding:1rem}
.sb-bottom-card p{font-size:.7rem;color:rgba(255,255,255,.6);margin:.25rem 0 .75rem;line-height:1.5}
.sb-bottom-card strong{font-size:.8125rem;color:#fff}

/* MAIN */
#main{margin-left:240px;flex:1;display:flex;flex-direction:column;min-height:100vh;min-width:0}
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

<!-- SIDEBAR -->
<aside id="sidebar">
  {{-- Logo --}}
  <div class="sb-logo">
    <div class="sb-logo-badge">
      @if($adminLogo)
        <img src="{{ asset('storage/'.$adminLogo) }}" alt="Logo" style="height:28px;width:auto;max-width:100%;object-fit:contain;border-radius:4px;">
      @else
        <svg width="22" height="22" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
      @endif
    </div>
    <div>
      <div class="sb-logo-text">buyle.id</div>
      <div class="sb-logo-sub">{{ str_ireplace('Cyclevent', 'buyle.id', session('admin_name', 'Admin buyle.id')) }}</div>
    </div>
  </div>

  {{-- Search --}}
  <div class="sb-search">
    <input type="text" placeholder="Cari menu..." id="sb-search-input" oninput="sbSearch(this.value)">
  </div>

  {{-- Navigation --}}
  <nav class="sb-nav" id="sb-nav">
    <div class="sb-sec">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>
      Dashboard
    </a>
    <a href="{{ route('admin.analytics') }}" class="sb-link {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      Analytics
    </a>

    <div class="sb-sec">Konten</div>
    <a href="{{ route('admin.services.index') }}" class="sb-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
      Produk & Layanan
    </a>

    <a href="{{ route('admin.articles.index') }}" class="sb-link {{ request()->routeIs('admin.articles*') ? 'active' : '' }}">
      <div class="sb-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
      Artikel
    </a>
    <a href="{{ route('admin.faqs.index') }}" class="sb-link {{ request()->routeIs('admin.faqs*') ? 'active' : '' }}">
      <div class="sb-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/></svg></div>
      FAQ
    </a>
    <a href="{{ route('admin.clients.index') }}" class="sb-link {{ request()->routeIs('admin.clients*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      Klien
    </a>
    <a href="{{ route('admin.testimonials.index') }}" class="sb-link {{ request()->routeIs('admin.testimonials*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
      Testimoni
    </a>
    @php $newLeads = \App\Models\Lead::where('status','new')->count(); @endphp
    <a href="{{ route('admin.leads.index') }}" class="sb-link {{ request()->routeIs('admin.leads*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      Laporan Chat
      @if($newLeads > 0)<span class="sb-badge">{{ $newLeads }}</span>@endif
    </a>
    <a href="{{ route('admin.hero_slides.index') }}" class="sb-link {{ request()->routeIs('admin.hero_slides*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 2l-4 5-4-5"/></svg>
      Banner Hero
    </a>

    <div class="sb-sec">E-Commerce</div>
    <a href="{{ route('admin.product-categories.index') }}" class="sb-link {{ request()->routeIs('admin.product-categories*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
      Kategori Marketplace
    </a>
    <a href="{{ route('admin.coupons.index') }}" class="sb-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
      Kupon / Voucher
    </a>
    <a href="{{ route('admin.couriers.index') }}" class="sb-link {{ request()->routeIs('admin.couriers*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      Pengiriman / Kurir
    </a>
    <a href="{{ route('admin.orders.index') }}" class="sb-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      Pesanan
    </a>
    <a href="{{ route('admin.users.index') }}" class="sb-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      Pengguna (Buyer)
    </a>

    <div class="sb-sec">Tampilan Beranda</div>
    <a href="{{ route('admin.usp.index') }}" class="sb-link {{ request()->routeIs('admin.usp*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
      USP Bar
    </a>
    <a href="{{ route('admin.promo-sections.index') }}" class="sb-link {{ request()->routeIs('admin.promo-sections*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      Promo & Deals
    </a>

    <div class="sb-sec">Pengaturan</div>
    <a href="{{ route('admin.settings') }}" class="sb-link {{ request()->routeIs('admin.settings*') || request()->routeIs('admin.wa*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
      Pengaturan
    </a>
    <a href="{{ route('admin.apikeys.index') }}" class="sb-link {{ request()->routeIs('admin.apikeys*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
      API & Integrasi
    </a>

    <div class="sb-sec">Aksi</div>
    <a href="{{ url('/') }}" target="_blank" class="sb-link">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      Lihat Website
    </a>
    <form method="POST" action="{{ route('admin.logout') }}" style="margin:0" id="logout-form">
      @csrf
      <button type="button" class="sb-link" onclick="handleLogoutClick()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </button>
    </form>
  </nav>

  {{-- Bottom card --}}
  <div class="sb-bottom">
    <div class="sb-bottom-card">
      <strong>buyle.id</strong>
      <p>Kelola konten & leads bisnis Anda</p>
      <a href="{{ url('/en/') }}" target="_blank" style="display:inline-flex;align-items:center;gap:.375rem;background:#fff;color:#1eb349;font-size:.75rem;font-weight:700;padding:.4rem .875rem;border-radius:8px;text-decoration:none;transition:all .2s;">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        Preview Website
      </a>
    </div>
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
        $newLeadsCount = \App\Models\Lead::where('status', 'new')->count();
        $recentLeads = \App\Models\Lead::where('status', 'new')->latest()->take(5)->get();
      @endphp
      <div style="position:relative;" id="notif-container">
        <button id="notif-btn" title="{{ $newLeadsCount }} lead baru" onclick="toggleNotif(event)" style="position:relative;width:38px;height:38px;border-radius:12px;border:1.5px solid var(--border,#E4E7F0);background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;box-shadow:0 2px 8px rgba(0,0,0,.04);">
          <svg width="16" height="16" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
          @if($newLeadsCount > 0)
          <span id="notif-badge" style="position:absolute;top:-4px;right:-4px;background:linear-gradient(135deg, #1eb349, #a5cf37);color:#fff;font-size:.55rem;font-weight:800;min-width:16px;height:16px;border-radius:100px;display:flex;align-items:center;justify-content:center;padding:0 3px;border:2px solid #F4F7FE;">{{ $newLeadsCount }}</span>
          @endif
        </button>

        {{-- Premium Notification Dropdown --}}
        <div id="notif-dropdown" style="display:none;opacity:0;transform:translateY(-8px);position:absolute;top:calc(100% + 10px);right:0;width:360px;background:#fff;border:1px solid #E2E8F0;border-radius:24px;box-shadow:0 12px 40px rgba(0,0,0,0.06), 0 2px 10px rgba(0,0,0,0.02);z-index:9999;overflow:hidden;transition:opacity .25s,transform .25s;font-family:inherit;">
          {{-- Header --}}
          <div style="padding:1.25rem 1.25rem .75rem;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:1rem;font-weight:600;color:#0F172A;letter-spacing:-.01em;">Pusat Notifikasi</div>
          </div>

          {{-- Segmented Tabs --}}
          <div style="padding:0 1.25rem 1rem;">
            <div style="display:flex;align-items:center;background:#F1F5F9;padding:.25rem;border-radius:12px;gap:.25rem;" id="notif-tabs">
              <div onclick="switchNotifTab(this)" class="notif-tab active" style="flex:1;text-align:center;padding:.375rem 0;background:#fff;border-radius:8px;font-size:.7rem;font-weight:600;color:#0F172A;box-shadow:0 1px 2px rgba(0,0,0,0.05);cursor:pointer;transition:all .2s;">Hari Ini</div>
              <div onclick="switchNotifTab(this)" class="notif-tab" style="flex:1;text-align:center;padding:.375rem 0;font-size:.7rem;font-weight:500;color:#64748B;cursor:pointer;transition:all .2s;">Minggu Ini</div>
              <div onclick="switchNotifTab(this)" class="notif-tab" style="flex:1;text-align:center;padding:.375rem 0;font-size:.7rem;font-weight:500;color:#64748B;cursor:pointer;transition:all .2s;">Sebelumnya</div>
            </div>
          </div>

          {{-- Notification Items --}}
          <div style="max-height:350px;overflow-y:auto;" class="notif-scroll">
            @if($newLeadsCount > 0)
              @foreach($recentLeads as $lead)
                <a href="{{ route('admin.leads.show', $lead) }}" style="display:flex;align-items:flex-start;gap:.875rem;padding:1.125rem 1.25rem;border-bottom:1px solid #F1F5F9;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                  {{-- Icon badge --}}
                  <div style="width:38px;height:38px;border-radius:50%;background:#fff;border:1.5px solid #F1F5F9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="18" height="18" fill="none" stroke="#64748B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                  </div>
                  {{-- Content --}}
                  <div style="flex:1;min-width:0;padding-top:2px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.25rem;">
                      <div style="display:flex;align-items:center;gap:.375rem;">
                        <div style="width:5px;height:5px;background:#8B5CF6;border-radius:50%;"></div>
                        <div style="font-size:.875rem;font-weight:600;color:#0F172A;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px;">{{ $lead->name }}</div>
                      </div>
                      <div style="font-size:.7rem;font-weight:500;color:#94A3B8;flex-shrink:0;">{{ \Carbon\Carbon::parse($lead->created_at)->locale('id')->diffForHumans(null, true) }} lalu</div>
                    </div>
                    <div style="font-size:.75rem;color:#64748B;line-height:1.4;">
                      Lead dari <strong>{{ $lead->source ?? 'Website' }}</strong>. Tertarik pada: {{ $lead->product ?? 'Inquiry Umum' }}
                    </div>
                  </div>
                </a>
              @endforeach
            @else
              <div style="padding:2.5rem 1.25rem;text-align:center;">
                <div style="width:40px;height:40px;background:#fff;border:1px solid #F1F5F9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                  <svg width="18" height="18" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div style="font-size:.875rem;font-weight:500;color:#0F172A;">Semua sudah dibaca</div>
                <div style="font-size:.75rem;color:#64748B;margin-top:.25rem;">Tidak ada notifikasi baru saat ini.</div>
              </div>
            @endif
          </div>
          
          {{-- Mark as read action --}}
          @if($newLeadsCount > 0)
          <div style="padding:.75rem 1.25rem;background:#F8FAFC;border-top:1px solid #F1F5F9;">
            <form action="{{ route('admin.leads.mark_read') }}" method="POST" style="margin:0;">
              @csrf
              <button type="submit" style="width:100%;background:transparent;border:none;color:#64748B;font-size:.75rem;cursor:pointer;font-weight:500;font-family:inherit;transition:color .2s;" onmouseover="this.style.color='#0F172A'" onmouseout="this.style.color='#64748B'">Tandai semua dibaca</button>
            </form>
          </div>
          @endif
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
