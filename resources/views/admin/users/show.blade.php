@extends('layouts.admin')
@section('title','Detail Pengguna')
@section('page-title','Detail Pengguna')
@section('content')

{{-- HEADER --}}
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
  <a href="{{ route('admin.users.index') }}" style="display:flex;align-items:center;gap:.375rem;color:#94A3B8;text-decoration:none;font-size:.875rem;font-weight:600;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    Kembali
  </a>
  <span style="color:#E2E8F0;">/</span>
  <span style="color:#1E293B;font-weight:700;">{{ $user->name }}</span>
</div>

@if(session('success'))
  <div style="background:#F0FDF4;color:#166534;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;border:1px solid #BBF7D0;font-size:.875rem;">{{ session('success') }}</div>
@endif
@if($errors->any())
  <div style="background:#FEF2F2;color:#991B1B;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;border:1px solid #FCA5A5;font-size:.875rem;">{{ $errors->first() }}</div>
@endif

<div style="display:grid;grid-template-columns:340px 1fr;gap:1.5rem;align-items:start;">

  {{-- SIDEBAR INFO --}}
  <div style="display:flex;flex-direction:column;gap:1.5rem;">
    {{-- Profile Card --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);text-align:center;">
      @php
        $avatarUrl = null;
        if ($user->avatar) {
            $avatarUrl = str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar);
        } elseif ($user->creatorProfile && $user->creatorProfile->avatar) {
            $cAv = $user->creatorProfile->avatar;
            $avatarUrl = str_starts_with($cAv, 'http') ? $cAv : asset('storage/' . $cAv);
        }
      @endphp

      @if($avatarUrl)
        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;margin:0 auto 1rem;display:block;box-shadow:0 4px 12px rgba(0,0,0,0.06);">
      @else
        <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg, #1eb349, #a5cf37);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.75rem;margin:0 auto 1rem;">
          {{ strtoupper(substr($user->name,0,1)) }}
        </div>
      @endif
      <h2 style="font-size:1.1rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;">{{ $user->name }}</h2>
      <p style="font-size:.8rem;color:#94A3B8;margin:0 0 1rem;">{{ $user->email }}</p>
      <div style="display:flex;align-items:center;justify-content:center;gap:.375rem;font-size:.75rem;font-weight:700;color:{{ ($user->is_active ?? true) ? '#16A34A' : '#DC2626' }};">
        <span style="width:6px;height:6px;border-radius:50%;background:currentColor;"></span>
        {{ ($user->is_active ?? true) ? 'Akun Aktif' : 'Akun Nonaktif' }}
      </div>
    </div>

    {{-- Info --}}
    <div style="background:#fff;border-radius:20px;padding:1.5rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
      <h3 style="font-size:.875rem;font-weight:700;color:#1E293B;margin:0 0 1rem;">Informasi Akun</h3>
      @php
        $waNum = $user->phone ?? '';
        $waDisplay = $waNum ? $waNum : '—';
      @endphp
      @foreach([['Nama', $user->name],['Email', $user->email],['No. WhatsApp', $waDisplay],['Username', $user->username ? '@'.$user->username : '—'],['Login Google', $user->google_id ? 'Ya' : 'Tidak'],['Terdaftar', $user->created_at->format('d M Y')],['Total Order', $orders->count().' order']] as [$label,$val])
      <div style="display:flex;justify-content:space-between;align-items:center;padding:.6rem 0;border-bottom:1px solid #F8FAFC;font-size:.8rem;">
        <span style="color:#94A3B8;font-weight:600;">{{ $label }}</span>
        <span style="color:#1E293B;font-weight:700;text-align:right;">
          @if($label === 'No. WhatsApp' && $user->phone)
            @php
              $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
              if (str_starts_with($cleanPhone, '0')) {
                  $cleanPhone = '62' . substr($cleanPhone, 1);
              }
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" style="color:#1eb349;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
              {{ $val }}
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
            </a>
          @else
            {{ $val }}
          @endif
        </span>
      </div>
      @endforeach
    </div>

    {{-- Actions --}}
    <div style="background:#fff;border-radius:20px;padding:1.5rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
      <h3 style="font-size:.875rem;font-weight:700;color:#1E293B;margin:0 0 1rem;">Aksi Cepat</h3>
      <form action="{{ route('admin.users.toggle', $user) }}" method="POST" style="margin-bottom:.5rem;" onsubmit="return confirm('Apakah Anda yakin ingin {{ ($user->is_active ?? true) ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ $user->name }}?')">
        @csrf
        <button type="submit" style="width:100%;padding:.45rem .75rem;background:{{ ($user->is_active ?? true) ? '#FEE2E2' : '#DCFCE7' }};color:{{ ($user->is_active ?? true) ? '#DC2626' : '#16A34A' }};border:none;border-radius:8px;font-weight:700;cursor:pointer;font-family:inherit;font-size:.785rem;display:flex;align-items:center;justify-content:center;gap:.375rem;">
          @if($user->is_active ?? true)
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            Nonaktifkan Akun
          @else
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            Aktifkan Akun
          @endif
        </button>
      </form>
      <button onclick="document.getElementById('pw-modal').style.display='flex'" style="width:100%;padding:.45rem .75rem;background:#f0fdf4;color:#16a34a;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-family:inherit;font-size:.785rem;display:flex;align-items:center;justify-content:center;gap:.375rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
        Ganti Password
      </button>
    </div>
  </div>

  {{-- MAIN CONTENT --}}
  <div style="display:flex;flex-direction:column;gap:1.5rem;">

    {{-- Orders --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
      <h3 style="font-size:1rem;font-weight:800;color:#1E293B;margin:0 0 1.25rem;">Riwayat Order ({{ $orders->count() }})</h3>
      @forelse($orders as $order)
      @php
        $firstItem = $order->items->first();
        $product = $firstItem?->product;
        $prodImage = null;
        if ($product && $product->image) {
            $prodImage = str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image);
        } elseif ($product && is_array($product->gallery) && count($product->gallery) > 0) {
            $firstGal = $product->gallery[0];
            $prodImage = str_starts_with($firstGal, 'http') ? $firstGal : asset('storage/' . $firstGal);
        }
        
        $itemCount = $order->items->count();
        $prodName = $firstItem ? $firstItem->full_name : 'Order #' . ($order->order_number ?? $order->id);
        if ($itemCount > 1) {
            $prodName .= ' (+ ' . ($itemCount - 1) . ' item lainnya)';
        }

        $statusObj = $order->status instanceof \App\Enums\OrderStatus ? $order->status : \App\Enums\OrderStatus::tryFrom((string)$order->status);
        $statusLabel = $statusObj ? $statusObj->label() : ucfirst((string)($order->status ?? 'Pending'));
        $statusValue = $statusObj ? $statusObj->value : (string)($order->status ?? 'pending');

        $badgeBg = match($statusValue) {
            'pending' => '#FEF3C7',
            'confirmed', 'processing', 'shipped', 'delivered' => '#DCFCE7',
            'cancelled', 'refunded' => '#FEE2E2',
            default => '#F1F5F9'
        };
        $badgeColor = match($statusValue) {
            'pending' => '#92400E',
            'confirmed', 'processing', 'shipped', 'delivered' => '#166534',
            'cancelled', 'refunded' => '#991B1B',
            default => '#475569'
        };
      @endphp
      <div style="display:flex;align-items:center;gap:1rem;padding:.875rem 0;border-bottom:1px solid #F1F5F9;">
        {{-- Foto Produk --}}
        <div style="width:48px;height:48px;border-radius:10px;overflow:hidden;background:#F8FAFC;flex-shrink:0;display:flex;align-items:center;justify-content:center;border:1px solid #E2E8F0;">
          @if($prodImage)
            <img src="{{ $prodImage }}" alt="{{ $prodName }}" style="width:100%;height:100%;object-fit:cover;">
          @else
            <svg width="20" height="20" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
          @endif
        </div>

        {{-- Product Info & ID/Timestamp --}}
        <div style="flex:1;min-width:0;">
          <div style="font-weight:700;color:#1E293B;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $prodName }}">
            {{ $prodName }}
          </div>
          <div style="display:flex;align-items:center;gap:.5rem;margin-top:.2rem;font-size:.75rem;color:#64748B;">
            <span style="font-weight:700;color:#0F172A;">#{{ $order->order_number ?? $order->id }}</span>
            <span>•</span>
            <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
          </div>
        </div>

        {{-- Price & Status --}}
        <div style="text-align:right;flex-shrink:0;">
          <div style="font-weight:800;color:#1E293B;font-size:.875rem;">
            Rp {{ number_format($order->total ?? $order->total_amount ?? 0, 0, ',', '.') }}
          </div>
          <div style="margin-top:.2rem;">
            <span style="display:inline-block;padding:.15rem .55rem;border-radius:50px;font-size:.7rem;font-weight:700;background:{{ $badgeBg }};color:{{ $badgeColor }};">
              {{ $statusLabel }}
            </span>
          </div>
        </div>
      </div>
      @empty
      <p style="color:#94A3B8;font-size:.875rem;text-align:center;padding:1.5rem 0;">Belum ada riwayat order.</p>
      @endforelse
    </div>

    {{-- Addresses --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
      <h3 style="font-size:1rem;font-weight:800;color:#1E293B;margin:0 0 1.25rem;">Alamat Pengiriman ({{ $addresses->count() }})</h3>
      @forelse($addresses as $addr)
      <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:.875rem;border-radius:12px;background:#F8FAFC;margin-bottom:.625rem;gap:.5rem;">
        <div>
          <div style="font-weight:700;font-size:.875rem;color:#1E293B;">{{ $addr->receiver_name }} <span style="font-weight:400;color:#94A3B8;">— {{ $addr->label ?? 'Alamat' }}</span></div>
          <div style="font-size:.8rem;color:#64748B;margin-top:.25rem;">{{ $addr->full_address }}</div>
          <div style="font-size:.75rem;color:#94A3B8;">{{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}</div>
          
          <div style="display:flex; gap:1rem; align-items:center; margin-top:.5rem;">
              @if($addr->is_default)<span style="font-size:.7rem;color:#16a34a;font-weight:700;display:inline-flex;align-items:center;gap:0.25rem;"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>Utama</span>@endif
              @if($addr->latitude && $addr->longitude)
                  <a href="https://maps.google.com/?q={{ $addr->latitude }},{{ $addr->longitude }}" target="_blank" style="font-size:.75rem;color:#16A34A;text-decoration:none;font-weight:700;display:inline-flex;align-items:center;gap:0.25rem;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>Buka di Google Maps</a>
              @endif
          </div>
        </div>
        <form action="{{ route('admin.users.addresses.destroy', [$user, $addr]) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')" style="flex-shrink:0;">
          @csrf @method('DELETE')
          <button type="submit" style="background:#FEE2E2;color:#DC2626;border:none;border-radius:8px;padding:.35rem .75rem;font-size:.75rem;font-weight:600;cursor:pointer;">Hapus</button>
        </form>
      </div>
      @empty
      <p style="color:#94A3B8;font-size:.875rem;text-align:center;padding:1rem 0;">Belum ada alamat tersimpan.</p>
      @endforelse
    </div>

  </div>
</div>

{{-- PASSWORD MODAL --}}
<div id="pw-modal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
  <div style="background:#fff;border-radius:20px;padding:2rem;width:100%;max-width:420px;box-shadow:0 24px 64px rgba(0,0,0,0.2);margin:1rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <h3 style="font-size:1.1rem;font-weight:800;color:#1E293B;margin:0;">Ganti Password</h3>
      <button onclick="document.getElementById('pw-modal').style.display='none'" style="background:#F1F5F9;border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem;color:#64748B;">✕</button>
    </div>
    <form action="{{ route('admin.users.password', $user) }}" method="POST">
      @csrf
      <div style="margin-bottom:1rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#475569;margin-bottom:.4rem;">Password Baru</label>
        <input type="password" name="password" required placeholder="Minimal 6 karakter" style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:inherit;">
      </div>
      <div style="margin-bottom:1.5rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#475569;margin-bottom:.4rem;">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required placeholder="Ulangi password baru" style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:inherit;">
      </div>
      <div style="display:flex;gap:.75rem;">
        <button type="button" onclick="document.getElementById('pw-modal').style.display='none'" style="flex:1;padding:.75rem;background:#F1F5F9;color:#475569;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Batal</button>
        <button type="submit" style="flex:2;padding:.75rem;background:#1E293B;color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Simpan</button>
      </div>
    </form>
  </div>
</div>
<script>
document.getElementById('pw-modal').addEventListener('click', function(e){ if(e.target===this) this.style.display='none'; });
@if($errors->any()) document.getElementById('pw-modal').style.display='flex'; @endif
</script>
@endsection