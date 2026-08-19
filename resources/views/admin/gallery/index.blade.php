@extends('layouts.admin')
@section('title','Galeri Proyek')
@section('page-title','Galeri Proyek')
@section('content')

{{-- PAGE HEADER --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Kelola Galeri Proyek</h1>
    <p style="font-size:.875rem;color:#94A3B8;margin:0;">{{ $items->count() }} foto proyek terdaftar</p>
  </div>
  <a href="{{ route('admin.gallery.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.875rem;font-weight:700;padding:.625rem 1.25rem;border-radius:12px;text-decoration:none;transition:all .2s;box-shadow:0 4px 14px rgba(59,130,246,0.35);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(59,130,246,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(59,130,246,0.35)'">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Foto
  </a>
</div>

{{-- STATS BAR --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.75rem;">
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(59,130,246,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $items->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Total Foto</div>
    </div>
  </div>
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(16,185,129,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $items->where('is_active',true)->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Aktif</div>
    </div>
  </div>
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(239,68,68,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#EF4444" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $items->where('is_active',false)->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Nonaktif</div>
    </div>
  </div>
</div>

{{-- GRID CARDS --}}
@if($items->count())
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.25rem;">
  @foreach($items as $item)
  <div style="background:#fff;border-radius:20px;box-shadow:0 2px 16px rgba(0,0,0,0.05);overflow:hidden;transition:transform .2s,box-shadow .2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 32px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 16px rgba(0,0,0,0.05)'">
    {{-- Image --}}
    <div style="position:relative;aspect-ratio:16/10;overflow:hidden;">
      <img src="{{ $item->image_url }}" alt="{{ $item->title }}" style="width:100%;height:100%;object-fit:cover;transition:transform .3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
      {{-- Status Badge --}}
      <div style="position:absolute;top:.75rem;right:.75rem;">
        @if($item->is_active)
          <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.7rem;font-weight:700;padding:.25rem .625rem;border-radius:100px;background:rgba(16,185,129,0.9);color:#fff;backdrop-filter:blur(8px);">
            <span style="width:5px;height:5px;background:#fff;border-radius:50%;"></span>Aktif
          </span>
        @else
          <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.7rem;font-weight:700;padding:.25rem .625rem;border-radius:100px;background:rgba(239,68,68,0.9);color:#fff;backdrop-filter:blur(8px);">
            <span style="width:5px;height:5px;background:#fff;border-radius:50%;"></span>Off
          </span>
        @endif
      </div>
      @if($item->is_featured)
      <div style="position:absolute;top:.75rem;left:.75rem;">
        <span style="font-size:.65rem;font-weight:700;padding:.2rem .5rem;border-radius:6px;background:rgba(245,158,11,0.9);color:#fff;">★ Featured</span>
      </div>
      @endif
    </div>
    {{-- Content --}}
    <div style="padding:1.125rem 1.25rem;">
      <div style="font-size:.9rem;font-weight:700;color:#1E293B;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:.25rem;">{{ $item->title }}</div>
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">
        @if($item->category)
          <span style="font-size:.7rem;font-weight:600;padding:.2rem .625rem;border-radius:6px;background:#F1F5F9;color:#475569;">{{ $item->category }}</span>
        @endif
        @if($item->year)
          <span style="font-size:.7rem;color:#94A3B8;">{{ $item->year }}</span>
        @endif
      </div>
      {{-- Actions --}}
      <div style="display:flex;gap:.5rem;">
        <a href="{{ route('admin.gallery.edit',$item) }}" title="Edit"
           style="flex:1;display:flex;align-items:center;justify-content:center;gap:.375rem;padding:.5rem;background:rgba(59,130,246,0.08);border-radius:10px;color:#3B82F6;text-decoration:none;font-size:.8rem;font-weight:700;transition:all .2s;"
           onmouseover="this.style.background='rgba(59,130,246,0.16)'" onmouseout="this.style.background='rgba(59,130,246,0.08)'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit
        </a>
        <a href="{{ $item->image_url }}" target="_blank" title="Preview"
           style="display:flex;align-items:center;justify-content:center;width:38px;height:38px;background:rgba(139,92,246,0.08);border-radius:10px;color:#8B5CF6;text-decoration:none;transition:all .2s;"
           onmouseover="this.style.background='rgba(139,92,246,0.18)'" onmouseout="this.style.background='rgba(139,92,246,0.08)'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
        <form method="POST" action="{{ route('admin.gallery.destroy',$item) }}" onsubmit="return confirm('Hapus foto ini? Tidak dapat dibatalkan.')">
          @csrf @method('DELETE')
          <button type="submit" title="Hapus"
            style="display:flex;align-items:center;justify-content:center;width:38px;height:38px;background:rgba(239,68,68,0.08);border-radius:10px;color:#EF4444;border:none;cursor:pointer;transition:all .2s;"
            onmouseover="this.style.background='rgba(239,68,68,0.18)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
          </button>
        </form>
      </div>
    </div>
  </div>
  @endforeach
</div>
@else
<div style="background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);padding:5rem;text-align:center;">
  <div style="width:64px;height:64px;background:#F1F5F9;border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
    <svg width="28" height="28" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
  </div>
  <div style="font-size:.95rem;font-weight:700;color:#334155;">Belum ada foto proyek</div>
  <div style="font-size:.82rem;color:#94A3B8;margin:.35rem 0 1.25rem;">Klik tombol "Tambah Foto" untuk mulai.</div>
  <a href="{{ route('admin.gallery.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.85rem;font-weight:700;padding:.625rem 1.25rem;border-radius:10px;text-decoration:none;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Upload Foto Pertama
  </a>
</div>
@endif

@endsection
