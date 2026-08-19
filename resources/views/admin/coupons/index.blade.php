@extends('layouts.admin')
@section('title', 'Kelola Voucher')
@section('page-title', 'Voucher')
@section('content')

  {{-- PAGE HEADER --}}
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
    <div>
      <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Voucher / Kupon Diskon</h1>
      <p style="font-size:.875rem;color:#94A3B8;margin:0;">{{ $coupons->total() }} voucher terdaftar</p>
    </div>
    <div style="display:flex;align-items:center;gap:1rem;">
      <a href="{{ route('admin.coupons.create') }}"
        style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.875rem;font-weight:700;padding:.625rem 1.25rem;border-radius:12px;text-decoration:none;transition:all .2s;box-shadow:0 4px 14px rgba(59,130,246,0.35);"
        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(59,130,246,0.4)'"
        onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(59,130,246,0.35)'">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <line x1="12" y1="5" x2="12" y2="19" />
          <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Tambah Voucher
      </a>
    </div>
  </div>

  {{-- TABLE CARD --}}
  <div class="view-container" style="background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow:hidden;display:block;">
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr style="background:#F8FAFC;">
          <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Kode & Kategori</th>
          <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Detail Promo</th>
          <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Status Pemakaian</th>
          <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Status & Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($coupons as $c)
          <tr style="border-bottom:1px solid #F8FAFC;transition:background .15s;" onmouseover="this.style.background='#FAFBFF'" onmouseout="this.style.background='transparent'">
            <td style="padding:1.25rem 1.5rem;">
              <div style="font-size:1rem;font-weight:800;color:#1E293B;font-family:monospace;letter-spacing:1px;margin-bottom:0.25rem;">{{ $c->code }}</div>
              <div style="display:flex;align-items:center;gap:0.35rem;font-size:0.75rem;">
                @if($c->badge)
                <span style="background:#E0F2FE;color:#0284C7;padding:2px 6px;border-radius:4px;font-weight:600;">{{ $c->badge }}</span>
                @endif
                <span style="color:#64748B;">{{ ucfirst($c->category) }}</span>
              </div>
            </td>
            
            <td style="padding:1.25rem 1.5rem;">
              <div style="font-size:0.9rem;font-weight:700;color:#059669;margin-bottom:0.15rem;">
                @if($c->type === 'percentage') Diskon {{ (int)$c->value }}% @else Potongan Rp {{ number_format($c->value,0,',','.') }} @endif
              </div>
              <div style="font-size:0.75rem;color:#64748B;">
                Min: Rp {{ number_format($c->min_purchase,0,',','.') }} 
                @if($c->max_discount) | Maks: Rp {{ number_format($c->max_discount,0,',','.') }} @endif
              </div>
            </td>

            <td style="padding:1.25rem 1.5rem;text-align:center;">
              <div style="font-size:0.9rem;font-weight:700;color:#1E293B;">{{ $c->used_count }} / {{ $c->usage_limit ?: '∞' }}</div>
              <div style="font-size:0.7rem;color:#94A3B8;margin-top:0.15rem;">
                @if($c->expired_at) Exp: {{ $c->expired_at->format('d/m/y H:i') }} @else Berlaku Selamanya @endif
              </div>
            </td>

            <td style="padding:1.25rem 1.5rem;text-align:center;">
              <div style="display:flex;align-items:center;justify-content:center;gap:1rem;">
                @if($c->is_active)
                  <span style="background:#DCFCE7;color:#166534;font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;white-space:nowrap;">AKTIF</span>
                @else
                  <span style="background:#FEE2E2;color:#991B1B;font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;white-space:nowrap;">NONAKTIF</span>
                @endif
                
                <div style="display:flex;gap:.5rem;">
                  <a href="{{ route('admin.coupons.edit', $c->id) }}" style="color:#3B82F6;background:#EFF6FF;border-radius:8px;padding:6px 10px;text-decoration:none;transition:background .2s;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.background='#DBEAFE'" onmouseout="this.style.background='#EFF6FF'" title="Edit">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                  </a>
                  <form action="{{ route('admin.coupons.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" style="color:#EF4444;background:#FEF2F2;border:none;border-radius:8px;padding:6px 10px;cursor:pointer;transition:background .2s;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='#FEF2F2'" title="Hapus">
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" style="padding:3rem 1.5rem;text-align:center;color:#94A3B8;font-size:.875rem;">
              <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem;color:#CBD5E1;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
              <div>Belum ada voucher.</div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
    @if($coupons->hasPages())
    <div style="padding:1.25rem 1.5rem;border-top:1px solid #F1F5F9;">
      {{ $coupons->links() }}
    </div>
    @endif
  </div>

@endsection
