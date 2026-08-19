@extends('layouts.admin')
@section('title','Manajemen Pengguna')
@section('page-title','Pengguna / Pembeli')
@section('content')

<style>
/* ── Premium User Page ── */
.upage { font-family: 'Montserrat', sans-serif; }

/* Header row */
.upage-head {
    display: flex; justify-content: space-between; align-items: center;
    gap: 1rem; margin-bottom: 1.75rem; flex-wrap: wrap;
}
.upage-title { font-size: 1.5rem; font-weight: 800; color: #0F172A; letter-spacing: -.03em; margin: 0 0 .2rem; }
.upage-sub   { font-size: .8rem; color: #94A3B8; margin: 0; font-weight: 500; }
.upage-sub strong { color: #3B82F6; }

/* Search bar */
.usearch-wrap { display: flex; gap: .5rem; align-items: center; }
.usearch-input {
    padding: .6rem 1rem; border: 1.5px solid #E2E8F0; border-radius: 12px;
    font-size: .8rem; outline: none; width: 220px; font-family: 'Montserrat', sans-serif;
    background: #F8FAFC; transition: all .2s; color: #0F172A;
}
.usearch-input:focus { border-color: #93C5FD; background: #fff; box-shadow: 0 0 0 3px rgba(59,130,246,0.08); }
.usearch-btn {
    background: #0F172A; color: #fff; border: none; border-radius: 12px;
    padding: .6rem 1.1rem; font-size: .8rem; font-weight: 700;
    cursor: pointer; font-family: 'Montserrat', sans-serif; transition: background .2s;
}
.usearch-btn:hover { background: #1E293B; }

/* View toggle */
.utoggle {
    display: flex; border: 1.5px solid #E2E8F0; border-radius: 10px; overflow: hidden;
}
.utoggle-btn {
    background: #fff; border: none; padding: .5rem .7rem;
    cursor: pointer; color: #94A3B8; transition: all .2s; display: flex; align-items: center;
}
.utoggle-btn + .utoggle-btn { border-left: 1.5px solid #E2E8F0; }
.utoggle-btn.active { background: #EFF6FF; color: #1D4ED8; }
.utoggle-btn:hover { background: #F8FAFC; color: #475569; }

/* ──────────────── GRID VIEW ──────────────── */
.u-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 1.25rem;
}

.u-card {
    background: #fff; border-radius: 20px;
    border: 1.5px solid #F1F5F9;
    padding: 1.75rem 1.5rem 1.25rem;
    display: flex; flex-direction: column; align-items: center; text-align: center;
    gap: .4rem; transition: all .22s; position: relative;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}
.u-card:hover {
    border-color: #BFDBFE;
    box-shadow: 0 8px 28px rgba(59,130,246,0.08);
    transform: translateY(-3px);
}

/* Avatar */
.u-av-wrap { position: relative; margin-bottom: .75rem; }
.u-av, .u-av-init {
    width: 76px; height: 76px; border-radius: 50%;
    border: 3px solid #F1F5F9;
}
.u-av { object-fit: cover; display: block; }
.u-av-init {
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.5rem; color: #fff;
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    font-family: 'Montserrat', sans-serif;
}
.u-online-dot {
    position: absolute; bottom: 3px; right: 3px;
    width: 15px; height: 15px; border-radius: 50%; border: 2.5px solid #fff;
}

/* Card text */
.u-name  { font-size: .9rem; font-weight: 700; color: #0F172A; }
.u-handle{ font-size: .72rem; color: #94A3B8; font-weight: 500; }
.u-email { font-size: .72rem; color: #64748B; word-break: break-all; margin-top: .15rem; }

/* Status & order pills */
.u-pill-wrap { display: flex; gap: .35rem; flex-wrap: wrap; justify-content: center; margin-top: .5rem; }
.u-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .2rem .65rem; border-radius: 50px;
    font-size: .68rem; font-weight: 700; font-family: 'Montserrat', sans-serif;
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
.u-btn-primary { background: #EFF6FF; color: #1D4ED8; }
.u-btn-primary:hover { background: #DBEAFE; }
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
    display: flex; gap: 1.5rem; flex-wrap: wrap; margin-top: .25rem; align-items: center;
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

/* ── grid mode: hide meta row ── */
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
      <h1 class="upage-title">Data Pengguna</h1>
      <p class="upage-sub">Seluruh akun buyer yang terdaftar. <strong>{{ $users->total() }}</strong> pengguna ditemukan.</p>
    </div>
    <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
      <form method="GET" action="{{ route('admin.users.index') }}" class="usearch-wrap">
        <input type="text" name="search" value="{{ $q }}" placeholder="Nama, email, HP..." class="usearch-input">
        <button type="submit" class="usearch-btn">Cari</button>
        @if($q)
          <a href="{{ route('admin.users.index') }}" style="font-size:.78rem;color:#94A3B8;text-decoration:none;font-family:'Montserrat',sans-serif;white-space:nowrap;">✕ Reset</a>
        @endif
      </form>
      <div class="utoggle">
        <button id="btn-grid-view" class="utoggle-btn active" onclick="setView('grid')" title="Grid">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        </button>
        <button id="btn-list-view" class="utoggle-btn" onclick="setView('list')" title="List">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="3" cy="6" r="1" fill="currentColor"/><circle cx="3" cy="12" r="1" fill="currentColor"/><circle cx="3" cy="18" r="1" fill="currentColor"/></svg>
        </button>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#F0FDF4;color:#15803D;padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;border:1px solid #BBF7D0;font-size:.8rem;font-family:'Montserrat',sans-serif;font-weight:600;">
      ✓ {{ session('success') }}
    </div>
  @endif

  {{-- User Cards --}}
  <div class="u-grid" id="u-grid">
    @forelse($users as $u)
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

        {{-- Status + order pills --}}
        <div class="u-pill-wrap" style="margin-top:.6rem;">
          <span class="u-pill" style="background:{{ ($u->is_active ?? true) ? '#DCFCE7' : '#FEE2E2' }};color:{{ ($u->is_active ?? true) ? '#16A34A' : '#DC2626' }};">
            <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
            {{ ($u->is_active ?? true) ? 'Aktif' : 'Nonaktif' }}
          </span>
          @if($u->orders_count > 0)
          <span class="u-pill" style="background:#EFF6FF;color:#1D4ED8;">
            <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/></svg>
            {{ $u->orders_count }}x Order
          </span>
          @endif
        </div>

        {{-- Meta row: only shows in list mode --}}
        <div class="u-email-row">
          <span class="u-meta-item">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            {{ $u->created_at->format('d M Y') }}
          </span>
          @if($u->phone)
          <span class="u-meta-item">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.36 10.9 19.79 19.79 0 0 1 1.27 2.3 2 2 0 0 1 3.23 0h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 7.91a16 16 0 0 0 6 6z"/></svg>
            {{ $u->phone }}
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
      <p style="font-size:.78rem;color:#CBD5E1;margin:0;">Coba ubah kata kunci pencarian</p>
    </div>
    @endforelse
  </div>

  @if($users->hasPages())
  <div style="padding:1.5rem 0;display:flex;justify-content:flex-end;">
    {{ $users->appends(['search' => $q])->links() }}
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