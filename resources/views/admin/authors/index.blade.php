@extends('layouts.admin')
@section('title','Kelola Penulis (Author)')
@section('page-title','Penulis')
@section('content')

{{-- PAGE HEADER --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Kelola Penulis</h1>
    <p style="font-size:.875rem;color:#94A3B8;margin:0;">{{ $authors->count() }} penulis terdaftar</p>
  </div>
  <a href="{{ route('admin.authors.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.875rem;font-weight:700;padding:.625rem 1.25rem;border-radius:12px;text-decoration:none;transition:all .2s;box-shadow:0 4px 14px rgba(59,130,246,0.35);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(59,130,246,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(59,130,246,0.35)'">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Penulis
  </a>
</div>

{{-- TABLE CARD --}}
<div style="background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow:hidden;">
  <table style="width:100%;border-collapse:collapse;">
    <thead>
      <tr style="background:#F8FAFC;">
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;width:80px;">Foto</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Nama</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Bio (Indonesia)</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($authors as $author)
      <tr style="border-bottom:1px solid #F8FAFC;transition:background .15s;" onmouseover="this.style.background='#FAFBFF'" onmouseout="this.style.background='transparent'">

        {{-- Foto --}}
        <td style="padding:1.25rem 1.5rem;">
            <div style="width:48px; height:48px; border-radius:50%; background:#f1f5f9; overflow:hidden; border:2px solid #E2E8F0;">
                @if($author->getRawOriginal('photo'))
                    <img src="{{ asset('storage/'.$author->getRawOriginal('photo')) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <svg width="24" height="24" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24" style="margin: 10px auto; display: block;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                @endif
            </div>
        </td>

        {{-- Nama --}}
        <td style="padding:1.25rem 1.5rem;">
          <div style="font-size:.95rem;font-weight:700;color:#1E293B;">{{ $author->name }}</div>
        </td>

        {{-- Bio --}}
        <td style="padding:1.25rem 1.5rem; max-width:400px;">
          @php $idBio = $author->translations->where('locale', 'id')->first(); @endphp
          <div style="font-size:.85rem;color:#64748B;line-height:1.4;">{{ Str::limit($idBio?->bio, 80) ?? '-' }}</div>
        </td>

        {{-- Aksi --}}
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          <div style="display:inline-flex;gap:.5rem;align-items:center;">
            {{-- Edit --}}
            <a href="{{ route('admin.authors.edit',$author) }}" title="Edit"
               style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(59,130,246,0.08);border-radius:8px;color:#3B82F6;text-decoration:none;transition:all .2s;"
               onmouseover="this.style.background='rgba(59,130,246,0.18)'" onmouseout="this.style.background='rgba(59,130,246,0.08)'">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
            {{-- Hapus --}}
            <form method="POST" action="{{ route('admin.authors.destroy',$author) }}" onsubmit="return confirm('Hapus penulis ini? Semua artikel miliknya akan kehilangan data penulis. Tindakan tidak dapat dibatalkan.')">
              @csrf @method('DELETE')
              <button type="submit" title="Hapus"
                style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(239,68,68,0.08);border-radius:8px;color:#EF4444;border:none;cursor:pointer;transition:all .2s;"
                onmouseover="this.style.background='rgba(239,68,68,0.18)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
              </button>
            </form>
          </div>
        </td>

      </tr>
      @empty
      <tr>
        <td colspan="4" style="padding:5rem;text-align:center;">
          <div style="width:64px;height:64px;background:#F1F5F9;border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
            <svg width="28" height="28" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </div>
          <div style="font-size:.95rem;font-weight:700;color:#334155;">Belum ada penulis</div>
          <div style="font-size:.82rem;color:#94A3B8;margin-top:.35rem;">Klik tombol "Tambah Penulis" untuk menambahkan.</div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
