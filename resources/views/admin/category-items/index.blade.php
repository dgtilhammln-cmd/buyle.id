@extends('layouts.admin')
@section('title','Kelola Kategori')
@section('page-title','Kategori Produk & Jasa')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Kelola Kategori</h1>
    <p style="font-size:.875rem;color:#94A3B8;margin:0;">Tampil sebagai kategori swipeable. Ubah urutan langsung tanpa buka Edit.</p>
  </div>
  <a href="{{ route('admin.category-items.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.875rem;font-weight:700;padding:.625rem 1.25rem;border-radius:12px;text-decoration:none;box-shadow:0 4px 14px rgba(59,130,246,0.35);">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Kategori
  </a>
</div>

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #86EFAC;color:#166534;padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;font-size:.875rem;font-weight:600;">{{ session('success') }}</div>
@endif

<div id="orderSavedMsg" style="display:none;background:#DCFCE7;border:1px solid #86EFAC;color:#166534;padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;font-size:.875rem;font-weight:600;">
  ✓ Urutan berhasil diperbarui!
</div>

<div style="background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow:hidden;">
  <table style="width:100%;border-collapse:collapse;">
    <thead>
      <tr style="background:#F8FAFC;border-bottom:1px solid #E2E8F0;">
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">Icon</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">Nama</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">Badge</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">Urutan <span style="color:#3B82F6;font-weight:600;">(edit langsung)</span></th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">Status</th>
        <th style="padding:1rem 1.5rem;text-align:right;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $cat)
      <tr style="border-bottom:1px solid #F1F5F9;">
        <td style="padding:1rem;">
          @php
            $icons = [
              'home'=>'<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
              'box'=>'<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>',
              'tool'=>'<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
              'truck'=>'<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
              'shopping-cart'=>'<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
              'shopping-bag'=>'<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>',
              'zap'=>'<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
              'star'=>'<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
              'monitor'=>'<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
              'smartphone'=>'<rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
              'gift'=>'<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/>',
              'heart'=>'<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
              'briefcase'=>'<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
              'award'=>'<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>',
              'droplet'=>'<path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>',
              'wind'=>'<path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"/>',
              'camera'=>'<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>',
              'shield'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
            ];
            $svg = $icons[$cat->icon_value] ?? $icons['box'];
          @endphp
          <div style="width:40px;height:40px;background:#F1F5F9;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#3B82F6;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $svg !!}</svg>
          </div>
        </td>
        <td style="padding:1rem 1.5rem;font-weight:600;color:#1E293B;">{{ $cat->name }}</td>
        <td style="padding:1rem 1.5rem;">
          @if($cat->badge)
            <span style="font-size:.7rem;font-weight:700;padding:.25rem .625rem;border-radius:999px;background:{{ $cat->badge_color ?? '#ef4444' }};color:#fff;">{{ $cat->badge }}</span>
          @else <span style="color:#CBD5E1;">—</span> @endif
        </td>
        <td style="padding:1rem 1.5rem;text-align:center;">
          <div style="display:inline-flex;align-items:center;gap:.375rem;">
            <button type="button" onclick="changeOrder({{ $cat->id }}, -1)" title="Naikkan"
              style="width:28px;height:28px;background:#F1F5F9;border:1px solid #E2E8F0;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748B;padding:0;flex-shrink:0;">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
            </button>
            <input type="number" id="order-{{ $cat->id }}" value="{{ $cat->sort_order }}" min="0"
              style="width:52px;text-align:center;padding:.35rem .5rem;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;font-weight:600;color:#1E293B;outline:none;"
              onchange="saveOrder({{ $cat->id }}, this.value)"
              onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#E2E8F0'">
            <button type="button" onclick="changeOrder({{ $cat->id }}, 1)" title="Turunkan"
              style="width:28px;height:28px;background:#F1F5F9;border:1px solid #E2E8F0;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748B;padding:0;flex-shrink:0;">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
          </div>
        </td>
        <td style="padding:1rem 1.5rem;text-align:center;">
          <span style="font-size:.7rem;font-weight:700;padding:.25rem .625rem;border-radius:999px;background:{{ $cat->is_active ? '#DCFCE7' : '#FEE2E2' }};color:{{ $cat->is_active ? '#166534' : '#991B1B' }};">
            {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
          </span>
        </td>
        <td style="padding:1rem 1.5rem;text-align:right;">
          <a href="{{ route('admin.category-items.edit', $cat) }}" style="display:inline-flex;align-items:center;padding:.4rem .875rem;border-radius:8px;background:#f0fdf4;color:#2563EB;font-size:.8rem;font-weight:600;text-decoration:none;margin-right:.5rem;">Edit</a>
          <form action="{{ route('admin.category-items.destroy', $cat) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kategori ini?')">
            @csrf @method('DELETE')
            <button type="submit" style="padding:.4rem .875rem;border-radius:8px;background:#FEF2F2;color:#DC2626;font-size:.8rem;font-weight:600;border:none;cursor:pointer;">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" style="padding:3rem;text-align:center;color:#94A3B8;">Belum ada kategori. Tambahkan kategori pertama!</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';
const ORDER_ENDPOINT = '{{ route("admin.category-items.updateOrder") }}';

function saveOrder(id, newVal) {
    fetch(ORDER_ENDPOINT, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF_TOKEN},
        body: JSON.stringify({ id: id, sort_order: parseInt(newVal) || 0 })
    }).then(r => r.json()).then(d => {
        if (d.success) {
            const msg = document.getElementById('orderSavedMsg');
            msg.style.display = 'block';
            clearTimeout(window._orderTimer);
            window._orderTimer = setTimeout(() => { msg.style.display = 'none'; }, 2500);
        }
    }).catch(console.error);
}

function changeOrder(id, delta) {
    const input = document.getElementById('order-' + id);
    const newVal = Math.max(0, (parseInt(input.value) || 0) + delta);
    input.value = newVal;
    saveOrder(id, newVal);
}
</script>
@endsection
