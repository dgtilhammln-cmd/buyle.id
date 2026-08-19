@extends('layouts.admin')
@section('title','Kelola USP')
@section('page-title','Unique Selling Points')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Kelola USP Bar</h1>
    <p style="font-size:.875rem;color:#94A3B8;margin:0;">Tampil di bawah Hero sebagai keunggulan layanan. Max 5 item.</p>
  </div>
  <a href="{{ route('admin.usp.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.875rem;font-weight:700;padding:.625rem 1.25rem;border-radius:12px;text-decoration:none;box-shadow:0 4px 14px rgba(59,130,246,0.35);">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah USP
  </a>
</div>

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #86EFAC;color:#166534;padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;font-size:.875rem;font-weight:600;">
    {{ session('success') }}
  </div>
@endif

<div style="background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow:hidden;">
  <table style="width:100%;border-collapse:collapse;">
    <thead>
      <tr style="background:#F8FAFC;border-bottom:1px solid #E2E8F0;">
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;">Icon</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;">Label</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;">Urutan</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;">Status</th>
        <th style="padding:1rem 1.5rem;text-align:right;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $item)
      <tr style="border-bottom:1px solid #F1F5F9;">
        <td style="padding:1rem 1.5rem;">
          @if($item->icon_type === 'upload' && $item->icon_value)
            <img src="{{ asset('storage/'.$item->icon_value) }}" width="32" height="32" style="object-fit:contain;border-radius:8px;">
          @else
            <span style="font-size:1.75rem;">{{ $item->icon_value }}</span>
          @endif
        </td>
        <td style="padding:1rem 1.5rem;font-weight:600;color:#1E293B;">{{ $item->label }}</td>
        <td style="padding:1rem 1.5rem;text-align:center;color:#64748B;">{{ $item->sort_order }}</td>
        <td style="padding:1rem 1.5rem;text-align:center;">
          <span style="font-size:.7rem;font-weight:700;padding:.25rem .625rem;border-radius:999px;background:{{ $item->is_active ? '#DCFCE7' : '#FEE2E2' }};color:{{ $item->is_active ? '#166534' : '#991B1B' }};">
            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
          </span>
        </td>
        <td style="padding:1rem 1.5rem;text-align:right;">
          <a href="{{ route('admin.usp.edit', $item) }}" style="display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .875rem;border-radius:8px;background:#EFF6FF;color:#2563EB;font-size:.8rem;font-weight:600;text-decoration:none;margin-right:.5rem;">Edit</a>
          <form action="{{ route('admin.usp.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus USP ini?')">
            @csrf @method('DELETE')
            <button type="submit" style="display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .875rem;border-radius:8px;background:#FEF2F2;color:#DC2626;font-size:.8rem;font-weight:600;border:none;cursor:pointer;">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" style="padding:3rem;text-align:center;color:#94A3B8;">Belum ada USP. Tambahkan yang pertama!</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
