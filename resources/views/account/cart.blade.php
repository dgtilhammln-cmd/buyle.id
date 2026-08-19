@extends('account.layout')
@section('title', 'Keranjang Belanja – Akun')

@section('acc_page')
<style>
.cart-empty { text-align:center; padding:3rem 1rem; }
.cart-empty svg { color:#CBD5E1; margin-bottom:1.25rem; }
.cart-empty h3 { font-size:1.25rem; font-weight:700; color:#0f172a; margin-bottom:0.5rem; }
.cart-empty p  { color:#64748B; font-size:0.875rem; margin-bottom:1.5rem; }
.cart-table { width:100%; border-collapse:collapse; }
.cart-table th { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94A3B8; padding:0.75rem 1rem; border-bottom:2px solid #F1F5F9; text-align:left; }
.cart-table td { padding:1rem; border-bottom:1px solid #F8FAFC; vertical-align:middle; font-size:0.875rem; color:#334155; }
.cart-table tbody tr:hover { background:#FAFBFF; }
.cart-product-thumb { width:56px; height:56px; border-radius:10px; object-fit:cover; border:1px solid #E2E8F0; }
.cart-product-thumb-placeholder { width:56px; height:56px; border-radius:10px; background:#F1F5F9; display:flex; align-items:center; justify-content:center; }
.cart-product-name { font-weight:600; color:#0f172a; font-size:0.875rem; }
.cart-product-variant { font-size:0.75rem; color:#94A3B8; margin-top:0.15rem; }
.qty-control { display:flex; align-items:center; gap:0.5rem; }
.qty-btn { width:28px; height:28px; border-radius:6px; border:1px solid #E2E8F0; background:#F8FAFC; color:#334155; font-size:1rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.15s; line-height:1; }
.qty-btn:hover { border-color:#38BDF8; background:#F0F9FF; color:#0EA5E9; }
.qty-input { width:44px; text-align:center; border:1px solid #E2E8F0; border-radius:6px; padding:0.25rem; font-size:0.875rem; font-family:"Montserrat",sans-serif; font-weight:600; outline:none; }
.qty-input:focus { border-color:#38BDF8; }
.btn-remove { background:none; border:none; color:#94A3B8; cursor:pointer; padding:0.35rem; border-radius:6px; transition:all 0.15s; }
.btn-remove:hover { background:#FEF2F2; color:#EF4444; }
.cart-summary-box { background:#F8FAFC; border:1px solid #E2E8F0; border-radius:14px; padding:1.5rem; }
.cart-summary-row { display:flex; justify-content:space-between; align-items:center; padding:0.5rem 0; font-size:0.875rem; color:#64748B; }
.cart-summary-row.total { border-top:2px solid #E2E8F0; margin-top:0.5rem; padding-top:1rem; font-size:1rem; font-weight:800; color:#0f172a; }
.btn-checkout { display:flex; align-items:center; justify-content:center; gap:0.5rem; width:100%; padding:0.875rem; background:linear-gradient(135deg,#0EA5E9,#38BDF8); color:#fff; border:none; border-radius:12px; font-family:"Montserrat",sans-serif; font-size:0.9rem; font-weight:700; cursor:pointer; text-decoration:none; transition:all 0.25s; margin-top:1.25rem; }
.btn-checkout:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(14,165,233,0.35); }
.btn-continue { display:flex; align-items:center; justify-content:center; gap:0.5rem; width:100%; padding:0.625rem; background:#fff; color:#0EA5E9; border:1.5px solid #BAE6FD; border-radius:12px; font-family:"Montserrat",sans-serif; font-size:0.85rem; font-weight:600; cursor:pointer; text-decoration:none; transition:all 0.2s; margin-top:0.625rem; }
.btn-continue:hover { background:#F0F9FF; border-color:#38BDF8; }
.cart-layout { display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start; }
@media(max-width:768px) { .cart-layout { grid-template-columns:1fr; } }
</style>

<div class="acc-card">
    <div class="acc-card-header">
        <h2>Keranjang Belanja</h2>
        <p>{{ $summary["items"]->count() }} produk dalam keranjang</p>
    </div>
</div>

@if(session("success"))
    <div class="alert-success" style="margin-top:1rem;">{{ session("success") }}</div>
@endif

@if($summary["items"]->isEmpty())
    <div class="acc-card" style="margin-top:1.25rem;">
        <div class="cart-empty">
            <svg width="72" height="72" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <h3>Keranjang Masih Kosong</h3>
            <p>Temukan produk pilihan terbaik dan tambahkan ke keranjang.</p>
            <a href="{{ route("products") }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.75rem; background:linear-gradient(135deg,#0EA5E9,#38BDF8); color:#fff; border-radius:12px; font-weight:700; text-decoration:none; font-size:0.875rem;">Mulai Belanja</a>
        </div>
    </div>
@else
    <div class="cart-layout" style="margin-top:1.25rem;">
        <div class="acc-card">
            <div class="acc-card-body" style="padding:0;">
                <div style="overflow-x:auto;">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th colspan="2">Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($summary["items"] as $item)
                            <tr>
                                <td style="width:72px; padding-right:0;">
                                    @if(!empty($item->product->image))
                                        <img src="{{ asset("storage/" . $item->product->image) }}" alt="{{ $item->product->name }}" class="cart-product-thumb">
                                    @else
                                        <div class="cart-product-thumb-placeholder">
                                            <svg width="20" height="20" fill="none" stroke="#CBD5E1" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="cart-product-name">{{ $item->product->name ?? "-" }}</div>
                                    @if($item->variantValue)
                                        <div class="cart-product-variant">{{ $item->variantValue->value }}</div>
                                    @endif
                                </td>
                                <td style="white-space:nowrap; font-weight:600;">Rp {{ number_format($item->unit_price, 0, ",", ".") }}</td>
                                <td>
                                    <form action="{{ route("cart.update") }}" method="POST" class="qty-control">
                                        @csrf
                                        <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                        <button type="button" class="qty-btn" onclick="changeQty(this,-1)">-</button>
                                        <input type="number" name="qty" value="{{ $item->qty }}" min="1" class="qty-input">
                                        <button type="button" class="qty-btn" onclick="changeQty(this,1)">+</button>
                                        <button type="submit" style="display:none;" class="qty-submit"></button>
                                    </form>
                                </td>
                                <td style="white-space:nowrap; font-weight:700; color:#0EA5E9;">Rp {{ number_format($item->subtotal, 0, ",", ".") }}</td>
                                <td>
                                    <form action="{{ route("cart.remove") }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                        <button type="submit" class="btn-remove" title="Hapus">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <div class="cart-summary-box">
                <div style="font-size:0.85rem; font-weight:700; color:#0f172a; margin-bottom:1rem;">Ringkasan Pesanan</div>
                <div class="cart-summary-row">
                    <span>Subtotal ({{ $summary["items"]->count() }} item)</span>
                    <span>Rp {{ number_format($summary["subtotal"], 0, ",", ".") }}</span>
                </div>
                <div class="cart-summary-row">
                    <span>Ongkos Kirim</span>
                    <span style="color:#10b981; font-weight:600;">Dihitung saat checkout</span>
                </div>
                <div class="cart-summary-row total">
                    <span>Total</span>
                    <span style="color:#0F172A; font-weight:700;">Rp {{ number_format($summary["subtotal"], 0, ",", ".") }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="btn-checkout">Lanjut ke Checkout</a>
                <a href="{{ route("products") }}" class="btn-continue">Lanjut Belanja</a>
            </div>
        </div>
    </div>
@endif
<script>
function changeQty(btn,delta){const form=btn.closest("form");const input=form.querySelector(".qty-input");let val=parseInt(input.value)+delta;if(val<1)val=1;input.value=val;clearTimeout(input._t);input._t=setTimeout(()=>form.querySelector(".qty-submit").click(),600);}
</script>
@endsection
