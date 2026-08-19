@extends('layouts.admin')
@section('title', 'Promo Section')
@section('page-title', 'Promo & Deals Section')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Promo & Deals Section</h1>
    <p style="font-size:.875rem;color:#94A3B8;margin:0;">Kelola section promo yang tampil di halaman beranda (di bawah kategori).</p>
  </div>
  <a href="{{ route('admin.promo-sections.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#1E293B;color:#fff;font-weight:700;font-size:.875rem;padding:.65rem 1.25rem;border-radius:12px;text-decoration:none;transition:opacity .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Section Baru
  </a>
</div>

@if(session('success'))
  <div style="background:#DCFCE7;color:#166534;padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;font-size:.875rem;font-weight:600;border:1px solid #BBF7D0;">{{ session('success') }}</div>
@endif

@if($promos->isEmpty())
  <div style="background:#fff;border-radius:20px;padding:4rem;text-align:center;box-shadow:0 2px 20px rgba(0,0,0,.04);">
    <div style="width:64px;height:64px;background:#F1F5F9;border-radius:16px;margin:0 auto 1.25rem;display:flex;align-items:center;justify-content:center;">
      <svg width="28" height="28" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
    </div>
    <h3 style="font-size:1rem;font-weight:700;color:#1E293B;margin:0 0 .5rem;">Belum ada Section Promo</h3>
    <p style="color:#94A3B8;font-size:.875rem;margin:0 0 1.5rem;">Buat section pertama dengan klik tombol di atas.</p>
  </div>
@else
  <div style="display:flex;flex-direction:column;gap:1rem;">
    @foreach($promos as $promo)
    <div style="background:#fff;border-radius:20px;padding:1.25rem 1.5rem;box-shadow:0 2px 20px rgba(0,0,0,.04);display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;">
      {{-- Banner Preview --}}
      <div style="width:80px;height:80px;border-radius:12px;overflow:hidden;flex-shrink:0;background:#F1F5F9;display:flex;align-items:center;justify-content:center;">
        @if($promo->banner)
          <img src="{{ asset('storage/'.$promo->banner) }}" alt="{{ $promo->title }}" style="width:100%;height:100%;object-fit:cover;">
        @else
          <svg width="28" height="28" fill="none" stroke="#CBD5E1" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        @endif
      </div>

      {{-- Info --}}
      <div style="flex:1;min-width:160px;">
        <div style="font-size:.9375rem;font-weight:700;color:#1E293B;margin-bottom:.25rem;">{{ $promo->title }}</div>
        @if($promo->subtitle)<div style="font-size:.8rem;color:#64748B;margin-bottom:.375rem;">{{ $promo->subtitle }}</div>@endif
        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
          <span style="font-size:.75rem;color:#94A3B8;">Urutan: <strong style="color:#1E293B;">{{ $promo->sort_order }}</strong></span>
          <span style="font-size:.75rem;color:#94A3B8;">Produk: <strong style="color:#1E293B;">{{ $promo->services()->count() }}</strong></span>
          <span style="display:inline-flex;align-items:center;gap:.25rem;font-size:.72rem;font-weight:700;padding:.2rem .6rem;border-radius:6px;background:{{ $promo->is_active ? '#DCFCE7' : '#FEE2E2' }};color:{{ $promo->is_active ? '#166534' : '#991B1B' }};">
            {{ $promo->is_active ? 'Aktif' : 'Non-Aktif' }}
          </span>
        </div>
      </div>

      {{-- Actions --}}
      <div style="display:flex;align-items:center;gap:.625rem;flex-shrink:0;">
        <a href="{{ route('admin.promo-sections.edit', $promo) }}" style="display:inline-flex;align-items:center;gap:.375rem;padding:.55rem 1rem;background:#EFF6FF;color:#1D4ED8;border-radius:10px;font-size:.8rem;font-weight:700;text-decoration:none;">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit
        </a>
        <form action="{{ route('admin.promo-sections.destroy', $promo) }}" method="POST" onsubmit="return confirm('Hapus section ini?')" style="margin:0;">
          @csrf @method('DELETE')
          <button type="submit" style="display:inline-flex;align-items:center;gap:.375rem;padding:.55rem 1rem;background:#FEF2F2;color:#DC2626;border:none;border-radius:10px;font-size:.8rem;font-weight:700;cursor:pointer;font-family:inherit;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
            Hapus
          </button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
@endif
@endsection
