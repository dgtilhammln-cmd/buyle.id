@extends('layouts.app')

@section('content')
<style>
:root { --accent: #1eb349; --accent-dark: #a5cf37; --text: #0F172A; --muted: #64748B; --border: #E2E8F0; --bg: #F8FAFC; }
body { background-color: #F8FAFC !important; }

.fin-wrap {
    max-width: 960px;
    margin: 80px auto 4rem;
    padding: 0 1.5rem;
    font-family: 'Montserrat', sans-serif;
}

.fin-single-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(30,179,73,0.08), 0 4px 20px rgba(0,0,0,0.04);
    overflow: hidden;
}

.fin-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    align-items: stretch;
}

.fin-left {
    padding: 3.5rem 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
}

.fin-right {
    background: linear-gradient(145deg, #F8FAFC, #F1F5F9);
    padding: 3.5rem 3rem;
    border-left: 1px solid var(--border);
    display: flex;
    flex-direction: column;
}

.fin-icon-wrap {
    width: 80px; height: 80px;
    background: linear-gradient(135deg, #dcfce7, #bbf7d0); /* Blue primary gradient */
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    animation: popIn 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.fin-icon-wrap.success {
    background: linear-gradient(135deg, #ECFDF5, #D1FAE5); /* Green for success only */
}

@keyframes popIn {
    0% { transform: scale(0.5); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.fin-title {
    font-size: 1.75rem; font-weight: 800;
    color: var(--text); margin-bottom: 0.75rem;
    line-height: 1.2;
}
.fin-sub {
    font-size: 0.95rem; color: var(--muted);
    line-height: 1.6; margin-bottom: 2.5rem;
}

.fin-order-box {
    margin-top: auto;
    margin-bottom: auto;
}
.fin-order-box-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px dashed var(--border);
}

.fin-order-row {
    display: flex; justify-content: space-between;
    align-items: center; font-size: 0.95rem;
    padding: 0.6rem 0;
}
.fin-order-row:not(:last-child) { border-bottom: 1px solid #E2E8F0; }
.fin-order-row .label { color: var(--muted); font-weight: 500; }
.fin-order-row .value { font-weight: 700; color: var(--text); text-align: right; }
.fin-order-row .value.total { color: #1eb349 !important; font-size: 1.25rem; font-weight: 800; }

.btn-pay {
    display: inline-flex; width: auto; min-width: 220px;
    align-items: center; justify-content: center; gap: 8px;
    background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
    color: #fff !important; border: none; border-radius: 999px;
    padding: 1rem 2rem; font-size: 1rem; font-weight: 700;
    cursor: pointer; transition: all 0.2s;
    font-family: 'Montserrat', sans-serif;
    text-decoration: none; text-align: center;
    box-shadow: 0 8px 24px rgba(30,179,73,0.35) !important;
    margin: 0 auto;
}
.btn-pay:hover { 
    background: linear-gradient(135deg, #17a03d, #8fbf2e) !important;
    transform: translateY(-2px); 
    box-shadow: 0 14px 32px rgba(30,179,73,0.45) !important; 
    color: #fff !important; 
}
.btn-pay:active { transform: translateY(0); }

.btn-secondary-link {
    display: inline-block; text-align: center;
    margin-top: 1.25rem; color: var(--muted);
    font-size: 0.9rem; text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}
.btn-secondary-link:hover { color: var(--accent); }

.fin-status-badge {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.4rem 1rem; border-radius: 999px;
    font-size: 0.85rem; font-weight: 700;
    margin-bottom: 1.5rem;
}
.badge-success { background: #D1FAE5; color: #065F46; }
.badge-pending { background: #dcfce7; color: #15803d; } /* Blue badge instead of yellow */

.security-note {
    display: flex; align-items: center; justify-content: center;
    gap: 0.5rem; margin-top: 2.5rem;
    color: var(--muted); font-size: 0.8rem;
    background: #F1F5F9;
    padding: 0.75rem;
    border-radius: 8px;
}

@media(max-width: 768px) {
    .fin-grid { grid-template-columns: 1fr; }
    .fin-wrap { margin-top: 60px; }
    .fin-left { padding: 2.5rem 1.5rem; }
    .fin-right { border-left: none; border-top: 1px solid var(--border); padding: 2.5rem 1.5rem; }
}
</style>

<div class="fin-wrap">
    
    @php
        $isPaid = $order->payment && $order->payment->status?->value === 'success';
        $isPending = $order->payment && $order->payment->status?->value === 'pending';
    @endphp

    <div class="fin-single-card">
        <div class="fin-grid">
            
            {{-- LEFT COLUMN: Status & Action --}}
            <div class="fin-left">
                @if($isPaid)
                    {{-- PAID STATE --}}
                    <div class="fin-icon-wrap success">
                        <svg width="40" height="40" fill="none" stroke="#059669" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <span class="fin-status-badge badge-success">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="10"/></svg>
                            Pembayaran Berhasil
                        </span>
                    </div>
                    <div class="fin-title" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                        Pesanan Berhasil!
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#1eb349;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                    </div>
                    <p class="fin-sub">
                        Terima kasih sudah berbelanja di buyle.id.<br>
                        Akses produk digital atau jasa Anda melalui link di bawah ini.
                    </p>

                    <div style="background: linear-gradient(135deg, #1eb349, #a5cf37); border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem; color: #fff; text-align: left; box-shadow: 0 10px 30px rgba(30,179,73,0.3);">
                        <h4 style="margin-bottom: 1rem; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.3); padding-bottom: 0.5rem;">Akses Produk / Jasa</h4>
                        @foreach($order->items as $item)
                            @if($item->product && $item->product->digital_resource)
                                <div style="margin-bottom: 0.75rem;">
                                    <strong style="display:block; font-size: 0.9rem; margin-bottom: 0.25rem;">{{ $item->product_name }}</strong>
                                    <a href="{{ $item->product->digital_resource }}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #fff; color: #1eb349; padding: 0.5rem 1rem; border-radius: 999px; text-decoration: none; font-weight: 700; font-size: 0.85rem;">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                        Buka Link / WhatsApp
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div>
                        <a href="{{ route('account.orders') }}" class="btn-pay" style="background: #0f1f0f;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                            Riwayat Pesanan
                        </a>
                    </div>
                    <div style="margin-top: 1rem;">
                        <a href="{{ route('products') }}" class="btn-secondary-link">
                            Lanjut Belanja &rarr;
                        </a>
                    </div>

                @else
                    {{-- PENDING PAYMENT STATE --}}
                    <div style="margin: 0 auto 1.5rem; animation: popIn 0.6s cubic-bezier(0.22,1,0.36,1) both; display:flex; justify-content:center;">
                        @php $logo = \App\Models\Setting::get('logo'); @endphp
                        @if($logo)
                            <img src="{{ asset('storage/'.$logo) }}" alt="buyle.id" style="height:56px;width:auto;object-fit:contain;">
                        @else
                            <span style="font-weight:800;color:#1eb349;font-size:1.75rem;font-family:'Montserrat',sans-serif;">buyle.id</span>
                        @endif
                    </div>
                    <div>
                        <span class="fin-status-badge badge-pending">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="10"/></svg>
                            Menunggu Pembayaran
                        </span>
                    </div>
                    <div class="fin-title">Selesaikan Pembayaran</div>
                    <p class="fin-sub">
                        Klik tombol di bawah untuk melanjutkan ke halaman pembayaran.<br>
                        Pesanan akan otomatis dibatalkan jika belum dibayar dalam <strong>24 jam</strong>.
                    </p>

                    @if($isPending && $order->payment && $order->payment->midtrans_token)
                        @php
                            $midtransIsProd  = \App\Models\Setting::get('midtrans_is_production', config('midtrans.is_production'));
                            $midtransClientKey = \App\Models\Setting::get('midtrans_client_key') ?: config('midtrans.client_key');
                            $snapUrl = $midtransIsProd ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
                        @endphp

                        {{-- Tombol dengan data-snap-token agar tidak perlu inline script (CSP safe) --}}
                        <div>
                            <button
                                id="pay-button"
                                class="btn-pay"
                                data-snap-token="{{ $order->payment->midtrans_token }}">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                Bayar Sekarang
                            </button>
                        </div>

                        {{-- Midtrans Snap JS (external, CSP-safe) --}}
                        <script src="{{ $snapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
                        {{-- Payment handler (external JS, no inline script) --}}
                        <script src="{{ asset('js/snap-pay.js') }}" defer></script>

                        <div>
                            <a href="{{ route('account.orders') }}" class="btn-secondary-link">
                                Bayar nanti &rarr; Lihat Pesanan
                            </a>
                        </div>
                    @else
                        <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;font-size:0.9rem;color:#B91C1C;">
                            Status Pesanan: <strong style="text-transform:uppercase;">{{ $order->status->label() }}</strong>
                        </div>
                        <div>
                            <a href="{{ route('home') }}" class="btn-pay">
                                Kembali ke Beranda
                            </a>
                        </div>
                    @endif
                @endif

                <div class="security-note">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Pembayaran 100% Aman & Terenkripsi
                </div>
            </div>

            {{-- RIGHT COLUMN: Order Summary --}}
            <div class="fin-right">
                <div class="fin-order-box">
                    <div class="fin-order-box-title">Ringkasan Pesanan</div>
                    
                    <div class="fin-order-row">
                        <span class="label">No. Pesanan</span>
                        <span class="value">#{{ $order->order_number }}</span>
                    </div>
                    <div class="fin-order-row">
                        <span class="label">Tanggal</span>
                        <span class="value">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="fin-order-row">
                        <span class="label">Jumlah Item</span>
                        <span class="value">{{ $order->items->count() }} produk</span>
                    </div>
                    
                    @if($order->shipping_cost > 0)
                    <div class="fin-order-row">
                        <span class="label">Ongkos Kirim</span>
                        <span class="value">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    @if($order->discount > 0)
                    <div class="fin-order-row">
                        <span class="label">Diskon</span>
                        <span class="value" style="color:#10B981;">-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="fin-order-row" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px dashed #CBD5E1; border-bottom: none;">
                        <span class="label" style="font-weight: 600; color: var(--text); font-size: 1.05rem;">Total Tagihan</span>
                        <span class="value total">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
