@extends('account.layout')
@section('title', 'Wishlist – Akun')

@section('acc_page')
<div class="acc-card">
    <div class="acc-card-header">
        <h2>Wishlist Saya</h2>
        <p>Produk yang Anda simpan untuk dibeli nanti</p>
    </div>
    <div class="acc-card-body">
        @if($wishlists->count())
            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:1rem;">
                @foreach($wishlists as $wl)
                @if($wl->product)
                <a href="{{ route('products.show', $wl->product->slug) }}"
                   style="display:block; background:#F8FAFC; border:1.5px solid #E2E8F0; border-radius:14px; overflow:hidden; text-decoration:none; transition:all 0.2s;"
                   onmouseover="this.style.borderColor='#0EA5E9'; this.style.boxShadow='0 6px 20px rgba(14,165,233,0.1)'"
                   onmouseout="this.style.borderColor='#E2E8F0'; this.style.boxShadow='none'">
                    @if($wl->product->image)
                    <img src="{{ asset('storage/' . $wl->product->image) }}" alt="{{ $wl->product->name }}"
                         style="width:100%; height:160px; object-fit:cover;">
                    @else
                    <div style="width:100%; height:160px; background:#E2E8F0; display:flex; align-items:center; justify-content:center; color:#94A3B8;">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                    @endif
                    <div style="padding:0.875rem;">
                        <div style="font-size:0.825rem; font-weight:700; color:#0f172a; margin-bottom:0.3rem; line-height:1.4;">{{ $wl->product->name }}</div>
                        @if($wl->product->price)
                        <div style="font-size:0.875rem; font-weight:800; color:#0EA5E9;">Rp {{ number_format($wl->product->price, 0, ',', '.') }}</div>
                        @endif
                    </div>
                </a>
                @endif
                @endforeach
            </div>
            <div style="margin-top:1.5rem;">{{ $wishlists->links() }}</div>
        @else
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                <h3>Wishlist Kosong</h3>
                <p>Simpan produk favorit Anda agar mudah ditemukan kembali.</p>
                <a href="{{ route('products') }}" style="display:inline-flex;align-items:center;gap:0.5rem;margin-top:1rem;background:#0EA5E9;color:#fff;padding:0.6rem 1.25rem;border-radius:10px;font-size:0.85rem;font-weight:700;text-decoration:none;">
                    Jelajahi Produk
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
