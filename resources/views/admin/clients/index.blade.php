@extends('layouts.admin')
@section('title','Kelola Klien')
@section('page-title','Klien & Mitra')
@section('content')

{{-- PAGE HEADER --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Kelola Klien & Mitra</h1>
    <p style="font-size:.875rem;color:#94A3B8;margin:0;">{{ $clients->count() }} klien/mitra terdaftar</p>
  </div>
  <a href="{{ route('admin.clients.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.875rem;font-weight:700;padding:.625rem 1.25rem;border-radius:12px;text-decoration:none;transition:all .2s;box-shadow:0 4px 14px rgba(59,130,246,0.35);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(59,130,246,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(59,130,246,0.35)'">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Klien
  </a>
</div>

{{-- STATS BAR --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.75rem;">
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(59,130,246,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $clients->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Total Klien</div>
    </div>
  </div>
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(16,185,129,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $clients->where('is_active',true)->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Aktif Tampil</div>
    </div>
  </div>
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(239,68,68,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#EF4444" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $clients->where('is_active',false)->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Nonaktif</div>
    </div>
  </div>
</div>

{{-- TABLE CARD --}}
<div style="background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow:hidden;">
  <table style="width:100%;border-collapse:collapse;">
    <thead>
      <tr style="background:#F8FAFC;">
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Logo</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Nama Perusahaan</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Kota</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Industri</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Urutan</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Status</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($clients as $c)
      <tr style="border-bottom:1px solid #F8FAFC;transition:background .15s;" onmouseover="this.style.background='#FAFBFF'" onmouseout="this.style.background='transparent'">
        <td style="padding:1.25rem 1.5rem;">
          @if($c->logo)
            <div style="width:64px;height:44px;background:#F8FAFC;border-radius:10px;border:1px solid #E4E7F0;display:flex;align-items:center;justify-content:center;overflow:hidden;padding:.5rem;">
              <img src="{{ asset('storage/'.$c->logo) }}" alt="{{ $c->name }}" style="max-height:100%;max-width:100%;object-fit:contain;">
            </div>
          @else
            <div style="width:64px;height:44px;background:#F1F5F9;border-radius:10px;border:1px solid #E4E7F0;display:flex;align-items:center;justify-content:center;">
              <svg width="18" height="18" fill="none" stroke="#CBD5E1" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
            </div>
          @endif
        </td>
        <td style="padding:1.25rem 1.5rem;">
          <div style="font-size:.9rem;font-weight:700;color:#1E293B;">{{ $c->name }}</div>
          @if($c->website)
            <a href="{{ $c->website }}" target="_blank" style="font-size:.72rem;color:#3B82F6;text-decoration:none;opacity:.8;">{{ $c->website }}</a>
          @endif
        </td>
        <td style="padding:1.25rem 1.5rem;">
          <span style="font-size:.82rem;color:#475569;">{{ $c->city ?: '—' }}</span>
        </td>
        <td style="padding:1.25rem 1.5rem;">
          @if($c->industry)
            <span style="font-size:.75rem;font-weight:600;padding:.3rem .75rem;border-radius:8px;background:#F1F5F9;color:#475569;">{{ $c->industry }}</span>
          @else
            <span style="color:#CBD5E1;font-size:.8rem;">—</span>
          @endif
        </td>
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          <span style="font-size:.875rem;font-weight:700;color:#334155;">{{ $c->order }}</span>
        </td>
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          @if($c->is_active)
            <span style="display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:700;padding:.3rem .875rem;border-radius:100px;background:rgba(16,185,129,0.1);color:#10B981;">
              <span style="width:6px;height:6px;background:#10B981;border-radius:50%;"></span>Aktif
            </span>
          @else
            <span style="display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:700;padding:.3rem .875rem;border-radius:100px;background:rgba(239,68,68,0.1);color:#EF4444;">
              <span style="width:6px;height:6px;background:#EF4444;border-radius:50%;"></span>Nonaktif
            </span>
          @endif
        </td>
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          <div style="display:inline-flex;gap:.5rem;align-items:center;">
            <a href="{{ route('admin.clients.edit',$c) }}" title="Edit"
               style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(59,130,246,0.08);border-radius:8px;color:#3B82F6;text-decoration:none;transition:all .2s;"
               onmouseover="this.style.background='rgba(59,130,246,0.18)'" onmouseout="this.style.background='rgba(59,130,246,0.08)'">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
            <form method="POST" action="{{ route('admin.clients.destroy',$c) }}" onsubmit="return confirm('Hapus klien ini?')">
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
        <td colspan="7" style="padding:5rem;text-align:center;">
          <div style="width:64px;height:64px;background:#F1F5F9;border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
            <svg width="28" height="28" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          </div>
          <div style="font-size:.95rem;font-weight:700;color:#334155;">Belum ada klien</div>
          <div style="font-size:.82rem;color:#94A3B8;margin:.35rem 0 1.25rem;">Klik tombol "Tambah Klien" untuk mulai.</div>
          <a href="{{ route('admin.clients.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.85rem;font-weight:700;padding:.625rem 1.25rem;border-radius:10px;text-decoration:none;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Klien Pertama
          </a>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
