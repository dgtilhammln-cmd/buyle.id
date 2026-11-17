@extends('layouts.admin')
@section('title', 'All Users — Manajemen Pengguna')
@section('page-title', 'All User')
@section('content')

@php
  $personaLabels = [
      'digital_product'    => ['label' => 'Produk Digital', 'bg' => '#E0F2FE', 'color' => '#0369A1'],
      'service_freelance'  => ['label' => 'Jasa & Freelance', 'bg' => '#F3E8FF', 'color' => '#7E22CE'],
      'fnb_resto'          => ['label' => 'FnB & Resto', 'bg' => '#FFEDD5', 'color' => '#C2410C'],
      'event_ticket'       => ['label' => 'Event & Tiket', 'bg' => '#FEF3C7', 'color' => '#B45309'],
      'wedding_invitation' => ['label' => 'Undangan Digital', 'bg' => '#FCE7F3', 'color' => '#BE185D'],
      'content_creator'    => ['label' => 'Creator', 'bg' => '#DCFCE7', 'color' => '#15803D'],
      'media_partner'      => ['label' => 'Media Partner', 'bg' => '#CCFBF1', 'color' => '#0F766E'],
      'business_retail'    => ['label' => 'Bisnis & Ritel', 'bg' => '#F1F5F9', 'color' => '#334155'],
  ];
@endphp

<style>
/* ── All Users Premium Page ── */
.upage { font-family: 'Montserrat', sans-serif; }

/* Header row */
.upage-head {
    display: flex; justify-content: space-between; align-items: center;
    gap: 1.25rem; margin-bottom: 1.5rem; flex-wrap: wrap;
}
.upage-title { font-size: 1.5rem; font-weight: 800; color: #0F172A; letter-spacing: -.03em; margin: 0 0 .2rem; }
.upage-sub   { font-size: .8rem; color: #94A3B8; margin: 0; font-weight: 500; }
.upage-sub strong { color: #1eb349; }

/* Action group */
.upage-actions {
    display: flex; gap: .6rem; align-items: center; flex-wrap: wrap;
}

/* Download dropdown */
.udownload-wrap { position: relative; }
.udownload-btn {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .58rem 1.1rem; border-radius: 12px;
    background: linear-gradient(135deg, #1eb349, #15803D);
    color: #fff; font-size: .8rem; font-weight: 700;
    text-decoration: none; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(30,179,73,0.25);
    transition: all .2s; font-family: 'Montserrat', sans-serif;
}
.udownload-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(30,179,73,0.35);
}
.udownload-menu {
    display: none; position: absolute; right: 0; top: calc(100% + 6px);
    background: #fff; border: 1.5px solid #E2E8F0; border-radius: 12px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.12); z-index: 100;
    min-width: 170px; overflow: hidden; padding: .35rem 0;
}
.udownload-wrap:hover .udownload-menu,
.udownload-wrap:focus-within .udownload-menu { display: block; }
.udownload-item {
    display: flex; align-items: center; gap: .5rem;
    padding: .55rem 1rem; font-size: .78rem; font-weight: 600;
    color: #1E293B; text-decoration: none; transition: background .15s;
}
.udownload-item:hover { background: #F8FAFC; color: #1eb349; }

/* Filter & Search Bar */
.ufilter-card {
    background: #fff; border-radius: 16px; border: 1.5px solid #F1F5F9;
    padding: .9rem 1.25rem; margin-bottom: 1.5rem;
    display: flex; justify-content: space-between; align-items: center;
    gap: .75rem; flex-wrap: wrap; box-shadow: 0 1px 4px rgba(0,0,0,0.02);
}
.ufilter-form {
    display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; flex: 1;
}
.usearch-input {
    padding: .55rem .95rem; border: 1.5px solid #E2E8F0; border-radius: 10px;
    font-size: .8rem; outline: none; width: 230px; font-family: 'Montserrat', sans-serif;
    background: #F8FAFC; transition: all .2s; color: #0F172A;
}
.usearch-input:focus { border-color: #bbf7d0; background: #fff; box-shadow: 0 0 0 3px rgba(30,179,73,0.08); }

.ufilter-select {
    padding: .55rem .85rem; border: 1.5px solid #E2E8F0; border-radius: 10px;
    font-size: .78rem; outline: none; background: #F8FAFC; color: #334155;
    font-family: 'Montserrat', sans-serif; font-weight: 600; cursor: pointer;
}
.ufilter-select:focus { border-color: #bbf7d0; background: #fff; }

.usearch-btn {
    background: #0F172A; color: #fff; border: none; border-radius: 10px;
    padding: .55rem 1rem; font-size: .8rem; font-weight: 700;
    cursor: pointer; font-family: 'Montserrat', sans-serif; transition: background .2s;
}
.usearch-btn:hover { background: #1E293B; }

/* View toggle */
.utoggle {
    display: flex; border: 1.5px solid #E2E8F0; border-radius: 10px; overflow: hidden;
}
.utoggle-btn {
    background: #fff; border: none; padding: .45rem .65rem;
    cursor: pointer; color: #94A3B8; transition: all .2s; display: flex; align-items: center;
}
.utoggle-btn + .utoggle-btn { border-left: 1.5px solid #E2E8F0; }
.utoggle-btn.active { background: #f0fdf4; color: #16a34a; }
.utoggle-btn:hover { background: #F8FAFC; color: #475569; }

/* ──────────────── GRID VIEW ──────────────── */
.u-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.25rem;
}

.u-card {
    background: #fff; border-radius: 20px;
    border: 1.5px solid #F1F5F9;
    padding: 1.5rem 1.25rem 1.25rem;
    display: flex; flex-direction: column; align-items: center; text-align: center;
    gap: .4rem; transition: all .22s; position: relative;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}
.u-card:hover {
    border-color: #bbf7d0;
    box-shadow: 0 8px 28px rgba(30,179,73,0.08);
    transform: translateY(-3px);
}

/* Avatar */
.u-av-wrap { position: relative; margin-bottom: .5rem; }
.u-av, .u-av-init {
    width: 68px; height: 68px; border-radius: 50%;
    border: 3px solid #F1F5F9;
}
.u-av { object-fit: cover; display: block; }
.u-av-init {
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.35rem; color: #fff;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    font-family: 'Montserrat', sans-serif;
}
.u-online-dot {
    position: absolute; bottom: 2px; right: 2px;
    width: 14px; height: 14px; border-radius: 50%; border: 2.5px solid #fff;
}

/* Card text */
.u-name  { font-size: .88rem; font-weight: 700; color: #0F172A; }
.u-handle{ font-size: .72rem; color: #94A3B8; font-weight: 500; }
.u-email { font-size: .72rem; color: #64748B; word-break: break-all; margin-top: .1rem; }

/* Status & info pills */
.u-pill-wrap { display: flex; gap: .35rem; flex-wrap: wrap; justify-content: center; margin-top: .4rem; }
.u-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .2rem .6rem; border-radius: 50px;
    font-size: .67rem; font-weight: 700; font-family: 'Montserrat', sans-serif;
}

/* Card actions */
.u-card-actions {
    display: flex; gap: .5rem; margin-top: .85rem; width: 100%;
}
.u-btn {
    flex: 1; padding: .45rem; border-radius: 10px; font-size: .75rem;
    font-weight: 700; cursor: pointer; text-align: center; text-decoration: none;
    border: none; display: inline-block; font-family: 'Montserrat', sans-serif;
    transition: all .18s;
}
.u-btn-danger { background: #FEF2F2; color: #DC2626; }
.u-btn-danger:hover { background: #FEE2E2; }
.u-btn-primary { background: #f0fdf4; color: #16a34a; }
.u-btn-primary:hover { background: #dcfce7; }
.u-btn-success { background: #F0FDF4; color: #16A34A; }
.u-btn-success:hover { background: #DCFCE7; }

/* ──────────────── LIST VIEW ──────────────── */
.u-grid.list-mode {
    grid-template-columns: 1fr; gap: .625rem;
}
.u-grid.list-mode .u-card {
    flex-direction: row; text-align: left; align-items: center;
    padding: 1rem 1.5rem; border-radius: 14px; gap: 1rem;
}
.u-grid.list-mode .u-av-wrap { margin-bottom: 0; flex-shrink: 0; }
.u-grid.list-mode .u-av,
.u-grid.list-mode .u-av-init { width: 50px; height: 50px; font-size: 1rem; }
.u-grid.list-mode .u-card-body { flex: 1; min-width: 0; }
.u-grid.list-mode .u-name { font-size: .875rem; }
.u-grid.list-mode .u-email-row {
    display: flex; gap: 1.25rem; flex-wrap: wrap; margin-top: .35rem; align-items: center;
}
.u-grid.list-mode .u-meta-item {
    display: flex; align-items: center; gap: .3rem;
    font-size: .72rem; color: #64748B; font-family: 'Montserrat', sans-serif;
}
.u-grid.list-mode .u-pill-wrap { justify-content: flex-start; }
.u-grid.list-mode .u-card-actions {
    margin-top: 0; width: auto; flex-shrink: 0; gap: .4rem;
}
.u-grid.list-mode .u-btn { flex: none; padding: .4rem .85rem; }

/* Grid vs List Meta Row */
.u-grid:not(.list-mode) .u-email-row { display: none; }
.u-grid:not(.list-mode) .u-card-body { width: 100%; }

/* Empty state */
.u-empty {
    grid-column: 1/-1; padding: 4rem; text-align: center;
    color: #94A3B8; font-family: 'Montserrat', sans-serif;
}
</style>

<div class="upage">

  {{-- Page Header --}}
  <div class="upage-head">
    <div>
      <h1 class="upage-title">All User</h1>
      <p class="upage-sub">Seluruh pengguna terdaftar di buyle.id. Ditemukan <strong>{{ $users->total() }}</strong> pengguna.</p>
    </div>
    
    <div class="upage-actions">
      {{-- Download All Data Dropdown --}}
      <div class="udownload-wrap">
        <button type="button" class="udownload-btn">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
          Download All Data
          <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="udownload-menu">
          <a href="{{ route('admin.users.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}" class="udownload-item">
            <svg width="14" height="14" fill="none" stroke="#16A34A" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg>
            Format Excel (.xlsx)
          </a>
          <a href="{{ route('admin.users.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="udownload-item">
            <svg width="14" height="14" fill="none" stroke="#0284C7" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><polyline points="10 13 8 15 10 17"/><polyline points="14 13 16 15 14 17"/></svg>
            Format CSV (.csv)
          </a>
        </div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#F0FDF4;color:#15803D;padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;border:1px solid #BBF7D0;font-size:.8rem;font-family:'Montserrat',sans-serif;font-weight:600;">
      ✓ {{ session('success') }}
    </div>
  @endif

  {{-- Filter & Search Card --}}
  <div class="ufilter-card">
    <form method="GET" action="{{ route('admin.users.index') }}" class="ufilter-form">
      <input type="text" name="search" value="{{ $q }}" placeholder="Cari nama, email, HP, NIK, username..." class="usearch-input">
      
      <select name="persona" class="ufilter-select" onchange="this.form.submit()">
        <option value="">Semua Persona</option>
        @foreach($personaLabels as $key => $p)
          <option value="{{ $key }}" {{ ($persona === $key) ? 'selected' : '' }}>{{ $p['label'] }}</option>
        @endforeach
      </select>

      <select name="source" class="ufilter-select" onchange="this.form.submit()">
        <option value="">Semua Sumber Tahu</option>
        <option value="media_partner" {{ ($source === 'media_partner') ? 'selected' : '' }}>Media Partner</option>
        <option value="social_media" {{ ($source === 'social_media') ? 'selected' : '' }}>Media Sosial</option>
        <option value="word_of_mouth" {{ ($source === 'word_of_mouth') ? 'selected' : '' }}>Teman / Komunitas</option>
        <option value="ads" {{ ($source === 'ads') ? 'selected' : '' }}>Iklan Digital</option>
        <option value="search_engine" {{ ($source === 'search_engine') ? 'selected' : '' }}>Google Search</option>
      </select>

      <button type="submit" class="usearch-btn">Filter</button>
      @if($q || $persona || $source)
        <a href="{{ route('admin.users.index') }}" style="font-size:.78rem;color:#94A3B8;text-decoration:none;font-family:'Montserrat',sans-serif;white-space:nowrap;margin-left:.25rem;">✕ Reset</a>
      @endif
    </form>

    {{-- View Toggle --}}
    <div class="utoggle">
      <button id="btn-grid-view" class="utoggle-btn active" onclick="setView('grid')" title="Grid">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      </button>
      <button id="btn-list-view" class="utoggle-btn" onclick="setView('list')" title="List">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="3" cy="6" r="1" fill="currentColor"/><circle cx="3" cy="12" r="1" fill="currentColor"/><circle cx="3" cy="18" r="1" fill="currentColor"/></svg>
      </button>
    </div>
  </div>

  {{-- User Cards / Grid --}}
  <div class="u-grid" id="u-grid">
    @forelse($users as $u)
    @php
      $personaInfo = $personaLabels[$u->business_persona] ?? null;
      $resolvedNik = $u->resolved_nik ?? $u->nik;
    @endphp
    <div class="u-card">

      {{-- Avatar --}}
      <div class="u-av-wrap">
        @if($u->avatar)
          <img
            src="{{ Str::startsWith($u->avatar, 'http') ? $u->avatar : asset('storage/'.$u->avatar) }}"
            alt="{{ $u->name }}"
            class="u-av"
            onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
          <div class="u-av-init" style="display:none;">{{ strtoupper(substr($u->name,0,1)) }}</div>
        @else
          <div class="u-av-init">{{ strtoupper(substr($u->name,0,1)) }}</div>
        @endif
        <div class="u-online-dot" style="background:{{ $u->isOnline() ? '#10B981' : '#CBD5E1' }};"
             title="{{ $u->isOnline() ? 'Online' : 'Offline' }}"></div>
      </div>

      {{-- Card body --}}
      <div class="u-card-body">
        <div class="u-name">{{ $u->name }}</div>
        @if($u->username)
        <div class="u-handle">{{ '@'.$u->username }}</div>
        @endif
        <div class="u-email">{{ $u->email }}</div>

        {{-- Status & Persona Pills --}}
        <div class="u-pill-wrap">
          {{-- Status Akun --}}
          <span class="u-pill" style="background:{{ ($u->is_active ?? true) ? '#DCFCE7' : '#FEE2E2' }};color:{{ ($u->is_active ?? true) ? '#16A34A' : '#DC2626' }};">
            <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
            {{ ($u->is_active ?? true) ? 'Aktif' : 'Nonaktif' }}
          </span>

          {{-- Persona Badge --}}
          @if($personaInfo)
          <span class="u-pill" style="background:{{ $personaInfo['bg'] }};color:{{ $personaInfo['color'] }};">
            {{ $personaInfo['label'] }}
          </span>
          @endif

          {{-- Orders Count --}}
          @if($u->orders_count > 0)
          <span class="u-pill" style="background:#f0fdf4;color:#16a34a;">
            <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/></svg>
            {{ $u->orders_count }}x Order
          </span>
          @endif

          {{-- Tiket Count --}}
          @if($u->ticket_passes_count > 0)
          <span class="u-pill" style="background:#EFF6FF;color:#1D4ED8;">
            <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="10" x2="17" y2="10"/></svg>
            {{ $u->ticket_passes_count }} Tiket
          </span>
          @endif

          {{-- NIK --}}
          @if($resolvedNik)
          <span class="u-pill" style="background:#F1F5F9;color:#475569;" title="NIK: {{ $resolvedNik }}">
            <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/></svg>
            NIK Ada
          </span>
          @endif
        </div>

        {{-- Meta row: only shows in list mode --}}
        <div class="u-email-row">
          <span class="u-meta-item">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            {{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
          </span>
          @if($u->phone)
          <span class="u-meta-item">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.36 10.9 19.79 19.79 0 0 1 1.27 2.3 2 2 0 0 1 3.23 0h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 7.91a16 16 0 0 0 6 6z"/></svg>
            {{ $u->phone }}
          </span>
          @endif
          @if($u->referral_source_name || $u->media_partner_name)
          <span class="u-meta-item" style="color:#0F766E;">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
            {{ $u->media_partner_name ?: $u->referral_source_name }}
          </span>
          @endif
        </div>
      </div>

      {{-- Actions --}}
      <div class="u-card-actions">
        <form action="{{ route('admin.users.toggle', $u) }}" method="POST" style="flex:1;display:flex;">
          @csrf
          <button type="submit" class="u-btn {{ ($u->is_active ?? true) ? 'u-btn-danger' : 'u-btn-success' }}" style="width:100%;">
            {{ ($u->is_active ?? true) ? 'Nonaktifkan' : 'Aktifkan' }}
          </button>
        </form>
        <a href="{{ route('admin.users.show', $u) }}" class="u-btn u-btn-primary">Detail</a>
      </div>

    </div>
    @empty
    <div class="u-empty">
      <svg width="52" height="52" fill="none" stroke="#CBD5E1" stroke-width="1.5" viewBox="0 0 24 24" style="display:block;margin:0 auto 1rem;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      <p style="font-weight:600;margin:0 0 .25rem;color:#94A3B8;">Tidak ada pengguna ditemukan</p>
      <p style="font-size:.78rem;color:#CBD5E1;margin:0;">Coba ubah kata kunci atau filter pencarian</p>
    </div>
    @endforelse
  </div>

  @if($users->hasPages())
  <div style="padding:1.5rem 0;display:flex;justify-content:flex-end;">
    {{ $users->links() }}
  </div>
  @endif

</div>

<script>
function setView(mode) {
  const grid = document.getElementById('u-grid');
  const btnG = document.getElementById('btn-grid-view');
  const btnL = document.getElementById('btn-list-view');
  if (mode === 'list') {
    grid.classList.add('list-mode');
    btnL.classList.add('active');
    btnG.classList.remove('active');
  } else {
    grid.classList.remove('list-mode');
    btnG.classList.add('active');
    btnL.classList.remove('active');
  }
  try { localStorage.setItem('admin_users_view', mode); } catch(e){}
}
(function(){
  try {
    const saved = localStorage.getItem('admin_users_view');
    if (saved === 'list') setView('list');
  } catch(e){}
})();
</script>
@endsection
