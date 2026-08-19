@extends('layouts.app')
@section('content')

<style>
:root{--c-bg:#ffffff;--c-surface:#F8FAFC;--c-card:#ffffff;--c-border:#E2E8F0;--c-text:#0F172A;--c-muted:#64748B;--c-muted2:#94A3B8;--c-accent:#0EA5E9;--c-accent-h:#0284C7;--c-wa:#25D366;--font:'Montserrat',sans-serif;--ease:cubic-bezier(0.22,1,0.36,1);}
body{background:var(--c-bg);}

/* TOP BAR */
.sp-topbar{background:var(--c-surface);border-bottom:1px solid var(--c-border);padding:1rem 0;margin-top:80px;}
.sp-topbar-inner{max-width:1320px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;padding:0 1.5rem;}
.sp-topbar-left h1{font-size:1.25rem;font-weight:700;color:var(--c-text);margin:0 0 0.25rem;font-family:var(--font);letter-spacing:-0.01em;}
.sp-breadcrumb{display:flex;align-items:center;gap:0.4rem;font-size:0.75rem;font-family:var(--font);color:var(--c-muted);}
.sp-breadcrumb a{color:var(--c-muted);text-decoration:none;transition:color 0.2s;}
.sp-breadcrumb a:hover{color:var(--c-accent);}
.sp-breadcrumb-sep{opacity:0.4;}
.sp-breadcrumb-cur{color:var(--c-accent);font-weight:600;}
.sp-toolbar{display:flex;align-items:center;gap:1rem;flex-wrap:wrap;}
.sp-sort-wrap{display:flex;align-items:center;gap:0.5rem;font-size:0.8125rem;color:var(--c-muted);font-family:var(--font);}
.sp-sort-select{border:1.5px solid var(--c-border);border-radius:8px;padding:0.4rem 0.75rem;font-size:0.8125rem;font-family:var(--font);color:var(--c-text);background:var(--c-card);outline:none;cursor:pointer;transition:border-color 0.2s;}
.sp-sort-select:focus{border-color:var(--c-accent);}
.sp-view-btns{display:flex;gap:0.375rem;}
.sp-view-btn{width:34px;height:34px;display:flex;align-items:center;justify-content:center;border:1.5px solid var(--c-border);border-radius:8px;background:var(--c-card);color:var(--c-muted);cursor:pointer;transition:all 0.2s;}
.sp-view-btn.active,.sp-view-btn:hover{border-color:var(--c-accent);color:var(--c-accent);background:rgba(14,165,233,0.05);}
.sp-result-count{font-size:0.8125rem;color:var(--c-muted);font-family:var(--font);white-space:nowrap;}

/* LAYOUT */
.sp-layout{max-width:1320px;margin:0 auto;padding:2rem 1.5rem 4rem;display:grid;grid-template-columns:280px 1fr;gap:2rem;align-items:start;}

/* SIDEBAR */
.sp-sidebar{position:sticky;top:calc(72px + 1rem);display:flex;flex-direction:column;gap:1.25rem;}
.sp-sidebar-card{background:var(--c-card);border:1.5px solid var(--c-border);border-radius:16px;overflow:hidden;}
.sp-sidebar-head{padding:1rem 1.25rem;border-bottom:1px solid var(--c-border);display:flex;align-items:center;gap:0.625rem;font-size:1.15rem;font-weight:700;letter-spacing:-0.01em;color:var(--c-text);font-family:var(--font);}
.sp-sidebar-head-dot{width:5px;height:5px;border-radius:50%;background:var(--c-accent);flex-shrink:0;}
.sp-sidebar-body{padding:1.25rem;}
.sp-search-wrap{position:relative;}
.sp-search-wrap svg{position:absolute;left:0.875rem;top:50%;transform:translateY(-50%);color:var(--c-muted2);pointer-events:none;}
.sp-search-input{width:100%;padding:0.75rem 1rem 0.75rem 2.75rem;border:1.5px solid var(--c-border);border-radius:10px;font-size:0.875rem;font-family:var(--font);color:var(--c-text);background:var(--c-surface);outline:none;transition:all 0.2s;box-sizing:border-box;}
.sp-search-input:focus{border-color:var(--c-accent);background:#fff;}
.sp-type-options{display:flex;flex-direction:column;gap:0.5rem;}
.sp-type-opt{display:flex;align-items:center;gap:0.625rem;padding:0.625rem 0.875rem;border-radius:10px;cursor:pointer;border:1.5px solid var(--c-border);background:var(--c-surface);font-size:0.875rem;font-family:var(--font);color:var(--c-text);font-weight:600;text-decoration:none;transition:all 0.2s;}
.sp-type-opt:hover{border-color:var(--c-accent);color:var(--c-accent);}
.sp-type-opt.active{border-color:var(--c-accent);background:rgba(14,165,233,0.07);color:var(--c-accent);}
.sp-type-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.sp-type-dot.produk{background:var(--c-accent);}
.sp-type-dot.jasa{background:var(--c-wa);}
.sp-type-dot.semua{background:var(--c-muted2);}
.sp-price-inputs{display:grid;grid-template-columns:1fr 1fr;gap:0.625rem;margin-bottom:1rem;}
.sp-price-input{width:100%;padding:0.5rem 0.625rem;border:1.5px solid var(--c-border);border-radius:8px;font-size:0.8125rem;font-family:var(--font);color:var(--c-text);background:var(--c-surface);outline:none;box-sizing:border-box;transition:border-color 0.2s;}
.sp-price-input:focus{border-color:var(--c-accent);}
.sp-price-label{font-size:0.7rem;color:var(--c-muted2);margin-bottom:0.25rem;font-family:var(--font);}
.sp-apply-btn{width:100%;padding:0.625rem;background:var(--c-accent);color:#fff;border:none;border-radius:8px;font-family:var(--font);font-weight:700;font-size:0.8125rem;cursor:pointer;transition:background 0.2s;}
.sp-apply-btn:hover{background:var(--c-accent-h);}
.sp-cat-list{display:flex;flex-direction:column;}
.sp-cat-item{display:flex;align-items:center;gap:0.75rem;padding:0.625rem 0;border-bottom:1px solid var(--c-border);cursor:pointer;text-decoration:none;transition:all 0.2s;}
.sp-cat-item:last-child{border-bottom:none;}
.sp-cat-check{width:18px;height:18px;border:2px solid var(--c-border);border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.2s;background:#fff;}
.sp-cat-item.active .sp-cat-check,.sp-cat-item:hover .sp-cat-check{border-color:var(--c-accent);background:var(--c-accent);}
.sp-cat-check svg{opacity:0;transition:opacity 0.2s;}
.sp-cat-item.active .sp-cat-check svg,.sp-cat-item:hover .sp-cat-check svg{opacity:1;}
.sp-cat-name{font-size:0.8125rem;font-family:var(--font);color:var(--c-text);flex:1;font-weight:500;transition:color 0.2s;}
.sp-cat-item.active .sp-cat-name,.sp-cat-item:hover .sp-cat-name{color:var(--c-accent);font-weight:600;}
.sp-cat-badge{font-size:0.7rem;font-weight:700;background:var(--c-surface);border:1px solid var(--c-border);color:var(--c-muted);padding:0.125rem 0.5rem;border-radius:20px;font-family:var(--font);transition:all 0.2s;}
.sp-cat-item.active .sp-cat-badge,.sp-cat-item:hover .sp-cat-badge{background:rgba(14,165,233,0.1);border-color:rgba(14,165,233,0.3);color:var(--c-accent);}
.sp-reset-link{display:inline-flex;align-items:center;gap:0.375rem;font-size:0.8rem;color:var(--c-muted);font-family:var(--font);text-decoration:none;margin-top:0.5rem;transition:color 0.2s;}
.sp-reset-link:hover{color:#EF4444;}

/* ACTIVE CHIPS */
.sp-active-filters{display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;margin-bottom:1.5rem;}
.sp-filter-chip{display:inline-flex;align-items:center;gap:0.375rem;background:rgba(14,165,233,0.08);border:1px solid rgba(14,165,233,0.2);color:var(--c-accent);font-size:0.8rem;font-weight:600;font-family:var(--font);padding:0.35rem 0.75rem;border-radius:20px;text-decoration:none;transition:all 0.2s;}
.sp-filter-chip:hover{background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.2);color:#EF4444;}
.sp-filter-chip svg{width:12px;height:12px;}

/* GRID */
.sp-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;}
.sp-grid.list-view{grid-template-columns:1fr;}

/* CARD */
.sp-card{background:var(--c-card);border:1.5px solid var(--c-border);border-radius:18px;overflow:hidden;display:flex;flex-direction:column;transition:all 0.35s var(--ease);position:relative;}
.sp-card:hover{border-color:var(--c-accent);transform:translateY(-5px);box-shadow:0 16px 40px rgba(14,165,233,0.08);}
.sp-card-badge{position:absolute;top:0.75rem;left:0.75rem;z-index:2;font-size:0.65rem;font-weight:800;font-family:var(--font);letter-spacing:0.02em;text-transform:uppercase;padding:0.25rem 0.5rem;border-radius:6px;display:flex;align-items:center;gap:0.25rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);}
.sp-card-badge.produk{background:rgba(255,255,255,0.95);color:var(--c-accent);border:1px solid rgba(14,165,233,0.2);}
.sp-card-badge.jasa{background:rgba(255,255,255,0.95);color:var(--c-wa);border:1px solid rgba(37,211,102,0.2);}
.sp-card-badge.diskon{background:rgba(255,255,255,0.95);color:#EF4444;border:1px solid rgba(239,68,68,0.2);}
.sp-card-img{width:100%;aspect-ratio:4/3;overflow:hidden;background:var(--c-surface);position:relative;display:block;text-decoration:none;}
.sp-card-img img{width:100%;height:100%;object-fit:cover;transition:transform 0.5s var(--ease);display:block;}
.sp-card:hover .sp-card-img img{transform:scale(1.06);}
.sp-card-img-ph{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#E2E8F0,#F1F5F9);color:var(--c-muted2);}
.sp-card-body{padding:1.25rem;display:flex;flex-direction:column;flex:1;}
.sp-card-cat{font-size:0.68rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--c-muted);font-family:var(--font);margin-bottom:0.4rem;}
.sp-card-name{font-size:0.9375rem;font-weight:600;color:var(--c-text);font-family:var(--font);line-height:1.4;margin-bottom:0.4rem;transition:color 0.2s;}
.sp-card:hover .sp-card-name{color:var(--c-accent);}
.sp-card-desc{font-size:0.8rem;color:var(--c-muted);font-family:var(--font);font-weight:400;line-height:1.6;flex:1;margin-bottom:1rem;}
.sp-card-footer{border-top:1px solid var(--c-border);padding-top:1rem;}
.sp-card-price{margin-bottom:0.75rem;}
.sp-card-price-old{font-size:0.72rem;color:var(--c-muted2);text-decoration:line-through;font-family:var(--font);font-weight:400;}
/* Price always black */
.sp-card-price-main{font-size:1.1rem;font-weight:700;color:#0F172A;font-family:var(--font);letter-spacing:-0.01em;}
.sp-card-price-jasa{font-size:0.825rem;font-weight:600;color:#0F172A;font-family:var(--font);}
.sp-card-price-jasa-sub{font-size:0.72rem;color:var(--c-muted);font-family:var(--font);}
/* 2-button row */
.sp-card-meta-row{display:none;}
.sp-grid.list-view .sp-card-meta-row{display:flex;}
.sp-card-actions{display:grid;grid-template-columns:1fr auto;gap:0.5rem;margin-top:0.75rem;align-items:center;}
.sp-btn-main{display:flex;align-items:center;justify-content:center;gap:0.4rem;background:var(--c-text);color:#fff;padding:0.7rem 0.875rem;border-radius:10px;border:none;font-family:var(--font);font-weight:700;font-size:0.8rem;cursor:pointer;transition:all 0.25s;text-decoration:none;white-space:nowrap;}
.sp-btn-main:hover{background:var(--c-accent);transform:translateY(-1px);color:#fff;}
.sp-btn-main.wa{background:var(--c-wa);}
.sp-btn-main.wa:hover{background:#1EBE5D;}
.sp-btn-icon{display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;border:1.5px solid var(--c-border);background:#fff;color:var(--c-text);cursor:pointer;transition:all 0.25s;text-decoration:none;flex-shrink:0;}
.sp-btn-icon:hover{border-color:var(--c-accent);color:var(--c-accent);background:rgba(14,165,233,0.05);}
.sp-btn-icon.icon-wa{border-color:rgba(37,211,102,0.3);color:var(--c-wa);background:rgba(37,211,102,0.06);}
.sp-btn-icon.icon-wa:hover{background:rgba(37,211,102,0.12);}
/* LIST VIEW */
.sp-grid.list-view{grid-template-columns:1fr;}
.sp-grid.list-view .sp-card{flex-direction:row;min-height:180px;}
.sp-grid.list-view .sp-card-img{width:200px;min-height:180px;aspect-ratio:auto;flex-shrink:0;}
.sp-grid.list-view .sp-card-body{padding:1.25rem 1.5rem;display:flex;flex-direction:column;flex:1;}
.sp-grid.list-view .sp-card-name{font-size:1.125rem;}
.sp-grid.list-view .sp-card-desc{-webkit-line-clamp:2;display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;}
.sp-grid.list-view .sp-card-meta-row{display:flex;align-items:center;gap:1.5rem;margin-bottom:0.75rem;flex-wrap:wrap;}
.sp-grid.list-view .sp-card-meta-item{display:flex;align-items:center;gap:0.375rem;font-size:0.75rem;color:var(--c-muted);font-family:var(--font);}
.sp-grid.list-view .sp-card-footer{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-top:auto;}
.sp-grid.list-view .sp-card-price{margin-bottom:0;}
.sp-grid.list-view .sp-card-actions{grid-template-columns:auto auto;width:auto;}

/* EMPTY */
.sp-empty{text-align:center;padding:5rem 2rem;grid-column:1/-1;}
.sp-empty-icon{width:80px;height:80px;background:var(--c-surface);border:2px dashed var(--c-border);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:var(--c-muted2);}
.sp-empty h3{font-size:1.25rem;font-weight:700;color:var(--c-text);font-family:var(--font);margin:0 0 0.5rem;}
.sp-empty p{font-size:0.9375rem;color:var(--c-muted);font-family:var(--font);margin:0 0 1.5rem;}

/* MODAL */
.sp-modal-backdrop{display:none;position:fixed;inset:0;z-index:9998;background:rgba(15,23,42,0.72);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:1rem;opacity:0;transition:opacity 0.3s ease;}
.sp-modal{background:var(--c-card);border-radius:20px;width:100%;max-width:480px;padding:2rem;position:relative;transform:translateY(20px);transition:transform 0.3s ease;}

/* MOBILE FILTER BTN */
.sp-mobile-filter-btn{display:none;align-items:center;gap:0.5rem;background:var(--c-card);border:1.5px solid var(--c-border);border-radius:10px;padding:0.625rem 1rem;font-family:var(--font);font-size:0.875rem;font-weight:700;color:var(--c-text);cursor:pointer;transition:all 0.2s;}
.sp-mobile-filter-btn:hover{border-color:var(--c-accent);color:var(--c-accent);}

/* RESPONSIVE */
@media(max-width:1100px){.sp-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:900px){
    .sp-layout{grid-template-columns:1fr;gap:1.5rem;padding:1.5rem 1rem 3rem;}
    .sp-sidebar{position:static;display:none;}
    .sp-sidebar.mobile-open{display:flex;}
    .sp-mobile-filter-btn{display:flex;}
    .sp-toolbar{width:100%;justify-content:space-between;}
    .sp-topbar{margin-top:0.5rem;}
}
@media(max-width:640px){
    .sp-topbar{padding:0.875rem 0;margin-top:0.5rem;}
    .sp-topbar-inner{flex-direction:column;align-items:flex-start;gap:0.625rem;padding:0 1rem;}
    .sp-toolbar{gap:0.5rem;}
    
    /* MOBILE GRID VIEW (2 cols, compact) */
    .sp-grid{grid-template-columns:repeat(2,1fr);gap:0.625rem;}
    .sp-card-body{padding:0.625rem;}
    .sp-card-name{font-size:0.85rem;-webkit-line-clamp:2;}
    .sp-card-price-main{font-size:0.9rem;}
    .sp-card-badge{font-size:0.55rem;padding:0.15rem 0.35rem;top:0.35rem;left:0.35rem;}
    .sp-card-actions{grid-template-columns:1fr auto;gap:0.35rem;margin-top:0.5rem;}
    .sp-btn-main{padding:0.4rem 0.5rem;font-size:0.7rem;gap:0.25rem;}
    .sp-btn-main svg{width:14px;height:14px;}
    .sp-btn-icon{width:32px;height:32px;}
    .sp-btn-icon svg{width:16px;height:16px;}
    
    /* MOBILE LIST VIEW (1 col, horizontal) */
    .sp-grid.list-view{grid-template-columns:1fr;gap:0.5rem;}
    .sp-grid.list-view .sp-card{flex-direction:row;min-height:90px;border-radius:12px;}
    .sp-grid.list-view .sp-card-img{width:90px;min-height:90px;aspect-ratio:1/1;display:flex;align-items:center;justify-content:center;padding:0.25rem;}
    .sp-grid.list-view .sp-card-img img{object-fit:contain;width:100%;height:100%;border-radius:8px;}
    .sp-grid.list-view .sp-card-body{padding:0.5rem;flex:1;}
    .sp-grid.list-view .sp-card-name{font-size:0.85rem;margin-bottom:0.2rem;}
    .sp-grid.list-view .sp-card-desc{display:none;} /* hide desc to save space */
    .sp-grid.list-view .sp-card-meta-row{gap:0.5rem;margin-bottom:0.4rem;flex-wrap:wrap;}
    .sp-grid.list-view .sp-card-meta-item{font-size:0.68rem;gap:0.25rem;}
    .sp-grid.list-view .sp-card-footer{flex-direction:row;align-items:center;justify-content:space-between;gap:0.4rem;}
    .sp-grid.list-view .sp-card-actions{grid-template-columns:auto auto;margin-top:0;}
    .sp-grid.list-view .sp-btn-main{padding:0.35rem 0.6rem;font-size:0.65rem;}
    .sp-grid.list-view .sp-btn-icon{width:28px;height:28px;}
}
</style>

{{-- TOP BAR --}}
<div class="sp-topbar">
    <div class="sp-topbar-inner">
        <div class="sp-topbar-left">
            <h1>Cari Produk</h1>
            <nav class="sp-breadcrumb" aria-label="breadcrumb">
                <a href="{{ route_locale('home') }}">Beranda</a>
                <span class="sp-breadcrumb-sep">/</span>
                @if(request()->filled('q'))
                    <span class="sp-breadcrumb-cur">Hasil: "{{ request('q') }}"</span>
                @elseif(request('type') === 'jasa')
                    <span class="sp-breadcrumb-cur">Layanan Jasa</span>
                @elseif(request('type') === 'produk')
                    <span class="sp-breadcrumb-cur">Produk</span>
                @else
                    <span class="sp-breadcrumb-cur">Semua Produk &amp; Layanan</span>
                @endif
            </nav>
        </div>

        <div class="sp-toolbar">
            <button class="sp-mobile-filter-btn" onclick="toggleMobileFilter()">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                Filter
            </button>

            <div class="sp-sort-wrap">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="16" y1="4" x2="8" y2="4"/><line x1="20" y1="9" x2="4" y2="9"/><line x1="13" y1="14" x2="4" y2="14"/></svg>
                Urutkan:
                <form id="sortForm" method="GET" action="{{ route_locale('products') }}">
                    @foreach(request()->except('sort') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ is_array($v) ? implode(',', $v) : $v }}">
                    @endforeach
                    <select class="sp-sort-select" name="sort" onchange="document.getElementById('sortForm').submit()">
                        <option value="default" {{ request('sort','default')==='default' ? 'selected' : '' }}>Default</option>
                        <option value="newest"  {{ request('sort')==='newest'   ? 'selected' : '' }}>Terbaru</option>
                        <option value="name_az" {{ request('sort')==='name_az'  ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="price_asc"  {{ request('sort')==='price_asc'  ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort')==='price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </form>
            </div>

            <div class="sp-result-count">{{ $services->count() }} produk</div>

            <div class="sp-view-btns">
                <button class="sp-view-btn active" id="btnGrid" onclick="setView('grid')" title="Grid">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                </button>
                <button class="sp-view-btn" id="btnList" onclick="setView('list')" title="List">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

@php
    $activeCats = [];
    if(request()->filled('category')){
        $raw = request('category');
        $activeCats = is_array($raw) ? $raw : explode(',', $raw);
        $activeCats = array_filter($activeCats);
    }
@endphp

{{-- MAIN LAYOUT --}}
<div class="sp-layout">

    {{-- SIDEBAR --}}
    <aside class="sp-sidebar" id="spSidebar">

        {{-- Search --}}
        <div class="sp-sidebar-card">
            <div class="sp-sidebar-head"><span class="sp-sidebar-head-dot"></span>Pencarian</div>
            <div class="sp-sidebar-body">
                <form method="GET" action="{{ route_locale('products') }}" id="searchForm">
                    @foreach(request()->except('q') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ is_array($v) ? implode(',', $v) : $v }}">
                    @endforeach
                    <div class="sp-search-wrap">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input class="sp-search-input" type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk...">
                    </div>
                </form>
            </div>
        </div>

        {{-- Tipe --}}
        <div class="sp-sidebar-card">
            <div class="sp-sidebar-head"><span class="sp-sidebar-head-dot"></span>Tipe Produk</div>
            <div class="sp-sidebar-body" style="padding:0.75rem 1rem;">
                <div style="display:flex;gap:0.5rem;">
                    @php $baseP = request()->except('type'); @endphp
                    {{-- Semua --}}
                    <a href="{{ route_locale('products') }}?{{ http_build_query($baseP) }}"
                       title="Semua"
                       style="flex:1;display:flex;flex-direction:column;align-items:center;gap:0.3rem;padding:0.625rem 0.5rem;border-radius:10px;border:1.5px solid {{ !request('type') ? 'var(--c-accent)' : 'var(--c-border)' }};background:{{ !request('type') ? 'rgba(14,165,233,0.07)' : 'var(--c-surface)' }};text-decoration:none;transition:all 0.2s;">
                        <svg width="18" height="18" fill="none" stroke="{{ !request('type') ? '#0EA5E9' : '#94A3B8' }}" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        <span style="font-size:0.65rem;font-weight:700;color:{{ !request('type') ? '#0EA5E9' : '#94A3B8' }};font-family:var(--font);">Semua</span>
                    </a>
                    {{-- Produk --}}
                    <a href="{{ route_locale('products') }}?{{ http_build_query(array_merge($baseP, ['type'=>'produk'])) }}"
                       title="Produk"
                       style="flex:1;display:flex;flex-direction:column;align-items:center;gap:0.3rem;padding:0.625rem 0.5rem;border-radius:10px;border:1.5px solid {{ request('type')==='produk' ? 'var(--c-accent)' : 'var(--c-border)' }};background:{{ request('type')==='produk' ? 'rgba(14,165,233,0.07)' : 'var(--c-surface)' }};text-decoration:none;transition:all 0.2s;">
                        <svg width="18" height="18" fill="none" stroke="{{ request('type')==='produk' ? '#0EA5E9' : '#94A3B8' }}" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span style="font-size:0.65rem;font-weight:700;color:{{ request('type')==='produk' ? '#0EA5E9' : '#94A3B8' }};font-family:var(--font);">Produk</span>
                    </a>
                    {{-- Jasa --}}
                    <a href="{{ route_locale('products') }}?{{ http_build_query(array_merge($baseP, ['type'=>'jasa'])) }}"
                       title="Layanan Jasa"
                       style="flex:1;display:flex;flex-direction:column;align-items:center;gap:0.3rem;padding:0.625rem 0.5rem;border-radius:10px;border:1.5px solid {{ request('type')==='jasa' ? '#25D366' : 'var(--c-border)' }};background:{{ request('type')==='jasa' ? 'rgba(37,211,102,0.07)' : 'var(--c-surface)' }};text-decoration:none;transition:all 0.2s;">
                        <svg width="18" height="18" fill="none" stroke="{{ request('type')==='jasa' ? '#25D366' : '#94A3B8' }}" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 9.36l-7.1 7.1a1 1 0 0 1-1.4 0l-2.8-2.8a1 1 0 0 1 0-1.4l7.1-7.1a6 6 0 0 1 9.36-7.94l-3.77 3.77z"/></svg>
                        <span style="font-size:0.65rem;font-weight:700;color:{{ request('type')==='jasa' ? '#25D366' : '#94A3B8' }};font-family:var(--font);">Jasa</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Harga --}}
        <div class="sp-sidebar-card">
            <div class="sp-sidebar-head"><span class="sp-sidebar-head-dot"></span>Harga</div>
            <div class="sp-sidebar-body">
                <form method="GET" action="{{ route_locale('products') }}" id="priceForm">
                    @foreach(request()->except('price_min','price_max') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ is_array($v) ? implode(',', $v) : $v }}">
                    @endforeach
                    <div class="sp-price-inputs">
                        <div>
                            <div class="sp-price-label">Min</div>
                            <input class="sp-price-input" type="number" name="price_min" id="priceMinInput" value="{{ request('price_min', 0) }}" min="0" placeholder="0">
                        </div>
                        <div>
                            <div class="sp-price-label">Maks</div>
                            <input class="sp-price-input" type="number" name="price_max" id="priceMaxInput" value="{{ request('price_max', $maxPrice) }}" max="{{ $maxPrice }}" placeholder="{{ number_format($maxPrice, 0, ',', '.') }}">
                        </div>
                    </div>
                    <button type="submit" class="sp-apply-btn">Terapkan Harga</button>
                </form>
            </div>
        </div>

        {{-- Kategori --}}
        @if($categories->count() > 0)
        <div class="sp-sidebar-card">
            <div class="sp-sidebar-head"><span class="sp-sidebar-head-dot"></span>Kategori</div>
            <div class="sp-sidebar-body" style="padding:0.5rem 1.25rem;">
                <div class="sp-cat-list">
                    @foreach($categories as $cat)
                    @php
                        $isCatA = in_array($cat->slug, $activeCats);
                        $newList = $isCatA ? array_values(array_filter($activeCats, fn($c) => $c !== $cat->slug)) : array_merge($activeCats, [$cat->slug]);
                        $catQ    = array_merge(request()->except('category'), $newList ? ['category' => implode(',', $newList)] : []);
                    @endphp
                    <a href="{{ route_locale('products') }}?{{ http_build_query($catQ) }}" class="sp-cat-item {{ $isCatA ? 'active' : '' }}">
                        <div class="sp-cat-check">
                            <svg width="10" height="10" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="sp-cat-name">{{ $cat->name }}</span>
                        <span class="sp-cat-badge">{{ $cat->services_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if(request()->hasAny(['q','category','type','price_min','price_max','sort']))
        <a href="{{ route_locale('products') }}" class="sp-reset-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg>
            Reset semua filter
        </a>
        @endif
    </aside>

    {{-- CONTENT --}}
    <div class="sp-content">

        {{-- Active Filter Chips --}}
        @php $hasF = request()->hasAny(['q','category','type','price_min','price_max']); @endphp
        @if($hasF)
        <div class="sp-active-filters">
            <span style="font-size:0.8rem;color:var(--c-muted);font-family:var(--font);font-weight:600;">Filter:</span>
            @if(request()->filled('q'))
            <a href="{{ route_locale('products') }}?{{ http_build_query(request()->except('q')) }}" class="sp-filter-chip">
                "{{ request('q') }}" <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
            @endif
            @if(request('type'))
            <a href="{{ route_locale('products') }}?{{ http_build_query(request()->except('type')) }}" class="sp-filter-chip">
                {{ request('type') === 'jasa' ? 'Layanan Jasa' : 'Produk' }} <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
            @endif
            @foreach($activeCats as $cs)
                @php $cObj = $categories->firstWhere('slug', $cs); @endphp
                @if($cObj)
                @php $rem = array_values(array_diff($activeCats, [$cs])); @endphp
                <a href="{{ route_locale('products') }}?{{ http_build_query(array_merge(request()->except('category'), $rem ? ['category' => implode(',', $rem)] : [])) }}" class="sp-filter-chip">
                    {{ $cObj->name }} <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </a>
                @endif
            @endforeach
            @if(request()->filled('price_min') || request()->filled('price_max'))
            <a href="{{ route_locale('products') }}?{{ http_build_query(request()->except('price_min','price_max')) }}" class="sp-filter-chip">
                Rp {{ number_format(request('price_min',0),0,',','.') }} – Rp {{ number_format(request('price_max',$maxPrice),0,',','.') }}
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
            @endif
        </div>
        @endif

        {{-- Grid --}}
        <div class="sp-grid" id="spGrid">
            @forelse($services as $i => $service)
            <div class="sp-card" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 50 }}">

                {{-- Badge --}}
                @if($service->price > 0)
                    @if($service->sale_price > 0 && $service->sale_price < $service->price)
                        <div class="sp-card-badge diskon">% Diskon</div>
                    @else
                        <div class="sp-card-badge produk">Produk</div>
                    @endif
                @else
                    <div class="sp-card-badge jasa">Jasa</div>
                @endif

                {{-- Image --}}
                <a href="{{ route_locale('products.show', $service->slug) }}" class="sp-card-img">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" loading="{{ $i < 4 ? 'eager' : 'lazy' }}">
                    @else
                        <div class="sp-card-img-ph">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                    @endif
                </a>

                {{-- Body --}}
                <div class="sp-card-body">
                    @if($service->category)
                        <div class="sp-card-cat">{{ $service->category->name }}</div>
                    @endif
                    <a href="{{ route_locale('products.show', $service->slug) }}" style="text-decoration:none;">
                        <h2 class="sp-card-name">{{ $service->name }}</h2>
                    </a>
                    <p class="sp-card-desc">{{ Str::limit($service->short_desc ?? '', 75) }}</p>

                    {{-- List view: extra meta info --}}
                    <div class="sp-card-meta-row">
                        @if($service->category)
                        <span class="sp-card-meta-item">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                            {{ $service->category->name }}
                        </span>
                        @endif
                        @if($service->stock)
                        <span class="sp-card-meta-item">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Stok: {{ $service->stock }}
                        </span>
                        @endif
                        <span class="sp-card-meta-item">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                            Terjual: {{ $service->sold_count ?? 0 }}
                        </span>
                    </div>

                    <div class="sp-card-footer">
                        {{-- Price --}}
                        <div class="sp-card-price">
                            @if($service->price > 0)
                                @if($service->sale_price > 0 && $service->sale_price < $service->price)
                                    <div class="sp-card-price-old">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                                    <div class="sp-card-price-main">Rp {{ number_format($service->sale_price, 0, ',', '.') }}</div>
                                @else
                                    <div class="sp-card-price-main">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                                @endif
                            @else
                                <div class="sp-card-price-jasa">Layanan Jasa</div>
                                <div class="sp-card-price-jasa-sub">Konsultasi untuk harga</div>
                            @endif
                        </div>

                        {{-- 2 Buttons: Main action + Detail --}}
                        <div class="sp-card-actions">
                            @if($service->price > 0)
                                <form action="{{ route('cart.add') }}" method="POST" style="display:contents;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $service->id }}">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="sp-btn-main">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                        Keranjang
                                    </button>
                                </form>
                            @else
                                <button type="button" class="sp-btn-main wa" onclick="openOrderModal('Produk: {{ addslashes($service->name) }}')">
                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.487-1.761-1.66-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    Tanya WA
                                </button>
                            @endif
                            {{-- Wishlist icon button --}}
                            @auth
                                <form action="{{ route('account.wishlist.toggle') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $service->id }}">
                                    <button type="submit" class="sp-btn-icon" title="Tambah ke Wishlist">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    </button>
                                </form>
                            @else
                                <button type="button" class="sp-btn-icon" title="Tambah ke Wishlist" onclick="openWishlistModal()">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="sp-empty">
                <div class="sp-empty-icon">
                    <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </div>
                <h3>Tidak ada produk ditemukan</h3>
                <p>Coba ubah filter atau kata kunci pencarian Anda</p>
                <a href="{{ route_locale('products') }}" style="display:inline-flex;align-items:center;gap:0.5rem;background:var(--c-accent);color:#fff;padding:0.75rem 1.75rem;border-radius:50px;font-family:var(--font);font-weight:700;font-size:0.9375rem;text-decoration:none;" onmouseover="this.style.background='var(--c-accent-h)'" onmouseout="this.style.background='var(--c-accent)'">
                    Lihat Semua Produk
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

</div>

{{-- Wishlist Login Modal --}}
<div id="wishlistModal" class="sp-modal-backdrop" onclick="if(event.target === this) closeWishlistModal()">
    <div class="sp-modal" style="text-align:center;">
        <button type="button" onclick="closeWishlistModal()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--c-muted);">&times;</button>
        <div style="width:64px;height:64px;border-radius:50%;background:#FEE2E2;color:#EF4444;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
            <svg width="32" height="32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </div>
        <h3 style="font-size:1.5rem;font-weight:800;color:var(--c-text);font-family:var(--font);margin:0 0 0.75rem;">Yukk Buat Akun Dulu!</h3>
        <p style="font-size:0.95rem;color:var(--c-muted);font-family:var(--font);line-height:1.6;margin:0 0 1.5rem;">
            Agar produk favoritmu bisa tersimpan aman di Wishlist, silakan login atau buat akun baru ya! Gratis dan cepat kok.
        </p>
        <div style="display:flex;gap:1rem;justify-content:center;">
            <a href="{{ route('login') }}" class="sp-btn-main" style="width:140px;">Login</a>
            <a href="{{ route('register') }}" class="sp-btn-main" style="width:140px;background:#F1F5F9;color:#0F172A;">Daftar</a>
        </div>
    </div>
</div>

<script>
function setView(m){var g=document.getElementById('spGrid'),b1=document.getElementById('btnGrid'),b2=document.getElementById('btnList');if(m==='list'){g.classList.add('list-view');b2.classList.add('active');b1.classList.remove('active');localStorage.setItem('sp_view','list');}else{g.classList.remove('list-view');b1.classList.add('active');b2.classList.remove('active');localStorage.setItem('sp_view','grid');}}
(function(){if(localStorage.getItem('sp_view')==='list')setView('list');})();
function toggleMobileFilter(){document.getElementById('spSidebar').classList.toggle('mobile-open');}

function openWishlistModal() {
    var wModal = document.getElementById('wishlistModal');
    wModal.style.display = 'flex';
    void wModal.offsetWidth;
    wModal.style.opacity = '1';
}

function closeWishlistModal() {
    var wModal = document.getElementById('wishlistModal');
    wModal.style.opacity = '0';
    setTimeout(function(){ wModal.style.display = 'none'; }, 300);
}

document.querySelector('.sp-search-input').addEventListener('keydown',function(e){if(e.key==='Enter')document.getElementById('searchForm').submit();});
</script>
@endsection
