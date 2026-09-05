@extends('layouts.admin')
@section('title','Kategori Marketplace')
@section('page-title','Kategori Marketplace')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Kategori Marketplace</h1>
    <p style="font-size:.875rem;color:#94A3B8;margin:0;">Kelola semua kategori & sub-kategori produk, jasa & tiket event buyle.id</p>
  </div>
  <a href="{{ route('admin.product-categories.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;font-size:.875rem;font-weight:700;padding:.625rem 1.25rem;border-radius:12px;text-decoration:none;box-shadow:0 4px 14px rgba(30,179,73,0.3);">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Kategori
  </a>
</div>

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #86EFAC;color:#166534;padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;font-size:.875rem;font-weight:600;">✓ {{ session('success') }}</div>
@endif

{{-- TAB NAV --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;flex-wrap:wrap;">
  <button onclick="showTab('produk')" id="tab-produk-btn"
    style="padding:.5rem 1.25rem;border-radius:99px;font-size:.875rem;font-weight:700;cursor:pointer;border:none;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;">
    Produk Digital ({{ $produkCats->count() }})
  </button>
  <button onclick="showTab('jasa')" id="tab-jasa-btn"
    style="padding:.5rem 1.25rem;border-radius:99px;font-size:.875rem;font-weight:700;cursor:pointer;border:1.5px solid #E2E8F0;background:#fff;color:#64748B;">
    Jasa Digital ({{ $jasaCats->count() }})
  </button>
  <button onclick="showTab('event')" id="tab-event-btn"
    style="padding:.5rem 1.25rem;border-radius:99px;font-size:.875rem;font-weight:700;cursor:pointer;border:1.5px solid #E2E8F0;background:#fff;color:#64748B;">
    🎟️ Tiket & Event ({{ $eventCats->count() }})
  </button>
</div>

{{-- TAB: PRODUK --}}
<div id="tab-produk">
  @include('admin.product-categories._table', ['cats' => $produkCats, 'tabLabel' => 'Produk Digital'])
</div>

{{-- TAB: JASA --}}
<div id="tab-jasa" style="display:none;">
  @include('admin.product-categories._table', ['cats' => $jasaCats, 'tabLabel' => 'Jasa Digital'])
</div>

{{-- TAB: EVENT --}}
<div id="tab-event" style="display:none;">
  @include('admin.product-categories._table', ['cats' => $eventCats, 'tabLabel' => 'Tiket & Event'])
</div>

<script>
function showTab(tab) {
    document.getElementById('tab-produk').style.display = tab === 'produk' ? 'block' : 'none';
    document.getElementById('tab-jasa').style.display   = tab === 'jasa'   ? 'block' : 'none';
    document.getElementById('tab-event').style.display  = tab === 'event'  ? 'block' : 'none';

    const activeStyle = 'padding:.5rem 1.25rem;border-radius:99px;font-size:.875rem;font-weight:700;cursor:pointer;border:none;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;';
    const inactiveStyle = 'padding:.5rem 1.25rem;border-radius:99px;font-size:.875rem;font-weight:700;cursor:pointer;border:1.5px solid #E2E8F0;background:#fff;color:#64748B;';

    document.getElementById('tab-produk-btn').style.cssText = tab === 'produk' ? activeStyle : inactiveStyle;
    document.getElementById('tab-jasa-btn').style.cssText   = tab === 'jasa'   ? activeStyle : inactiveStyle;
    document.getElementById('tab-event-btn').style.cssText  = tab === 'event'  ? activeStyle : inactiveStyle;
}
</script>
@endsection
