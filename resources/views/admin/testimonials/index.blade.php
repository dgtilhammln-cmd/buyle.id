@extends('layouts.admin')
@section('title','Kelola Testimoni')
@section('page-title','Testimoni')
@section('content')

{{-- PAGE HEADER --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Kelola Testimoni</h1>
    <p style="font-size:.875rem;color:#94A3B8;margin:0;">{{ $testimonials->count() }} testimoni terdaftar</p>
  </div>
  <a href="{{ route('admin.testimonials.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.875rem;font-weight:700;padding:.625rem 1.25rem;border-radius:12px;text-decoration:none;transition:all .2s;box-shadow:0 4px 14px rgba(59,130,246,0.35);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(59,130,246,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(59,130,246,0.35)'">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Testimoni
  </a>
</div>

{{-- STATS BAR --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.75rem;">
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(59,130,246,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $testimonials->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Total Testimoni</div>
    </div>
  </div>
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(245,158,11,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="#F59E0B" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    </div>
    <div>
      @php $avgRating = $testimonials->count() ? round($testimonials->avg('rating'),1) : 0; @endphp
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $avgRating }}<span style="font-size:.875rem;color:#94A3B8;">/5</span></div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Rating Rata-rata</div>
    </div>
  </div>
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(16,185,129,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $testimonials->where('is_active',true)->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Tampil di Website</div>
    </div>
  </div>
</div>

{{-- TABLE CARD --}}
<div style="background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow:hidden;">
  <table style="width:100%;border-collapse:collapse;">
    <thead>
      <tr style="background:#F8FAFC;">
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Nama & Jabatan</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Perusahaan</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Kutipan</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Rating</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Status</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($testimonials as $t)
      <tr style="border-bottom:1px solid #F8FAFC;transition:background .15s;" onmouseover="this.style.background='#FAFBFF'" onmouseout="this.style.background='transparent'">
        <td style="padding:1.25rem 1.5rem;">
          <div style="display:flex;align-items:center;gap:.875rem;">
            @if($t->photo)
              <img src="{{ asset('storage/'.$t->photo) }}" alt="{{ $t->name }}" style="width:40px;height:40px;border-radius:12px;object-fit:cover;flex-shrink:0;border:1px solid #E2E8F0;">
            @else
              <div style="width:40px;height:40px;background:linear-gradient(135deg,#3B82F6,#8B5CF6);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:800;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr($t->name,0,1)) }}
              </div>
            @endif
            <div>
              <div style="font-size:.9rem;font-weight:700;color:#1E293B;">{{ $t->name }}</div>
              @if($t->position)
                <div style="font-size:.75rem;color:#94A3B8;margin-top:.1rem;">{{ $t->position }}</div>
              @endif
            </div>
          </div>
        </td>
        <td style="padding:1.25rem 1.5rem;">
          @if($t->company)
            <span style="font-size:.78rem;font-weight:600;padding:.3rem .75rem;border-radius:8px;background:#F1F5F9;color:#475569;">{{ $t->company }}</span>
          @else
            <span style="color:#CBD5E1;font-size:.8rem;">—</span>
          @endif
        </td>
        <td style="padding:1.25rem 1.5rem;max-width:280px;">
          <div style="font-size:.8rem;color:#475569;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-style:italic;">"{{ $t->content }}"</div>
        </td>
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          <div style="display:flex;align-items:center;justify-content:center;gap:2px;">
            @for($i=0;$i<5;$i++)
              <svg width="14" height="14" fill="{{ $i<$t->rating ? '#F59E0B' : '#E2E8F0' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            @endfor
          </div>
          <div style="font-size:.7rem;color:#94A3B8;margin-top:.25rem;font-weight:700;">{{ $t->rating }}/5</div>
        </td>
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          @if($t->is_active)
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
            <a href="{{ route('admin.testimonials.edit',$t) }}" title="Edit"
               style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(59,130,246,0.08);border-radius:8px;color:#3B82F6;text-decoration:none;transition:all .2s;"
               onmouseover="this.style.background='rgba(59,130,246,0.18)'" onmouseout="this.style.background='rgba(59,130,246,0.08)'">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
            <form method="POST" action="{{ route('admin.testimonials.destroy',$t) }}" onsubmit="return confirm('Hapus testimoni ini?')">
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
        <td colspan="6" style="padding:5rem;text-align:center;">
          <div style="width:64px;height:64px;background:#F1F5F9;border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
            <svg width="28" height="28" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
          </div>
          <div style="font-size:.95rem;font-weight:700;color:#334155;">Belum ada testimoni</div>
          <div style="font-size:.82rem;color:#94A3B8;margin:.35rem 0 1.25rem;">Klik tombol "Tambah Testimoni" untuk mulai.</div>
          <a href="{{ route('admin.testimonials.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.85rem;font-weight:700;padding:.625rem 1.25rem;border-radius:10px;text-decoration:none;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Testimoni Pertama
          </a>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
