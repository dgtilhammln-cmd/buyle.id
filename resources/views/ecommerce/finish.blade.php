@extends('layouts.app')

@section('content')
<style>
:root { --accent: #0EA5E9; --accent-dark: #0369A1; --text: #0F172A; --muted: #64748B; --border: #E2E8F0; --bg: #F8FAFC; }
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
    box-shadow: 0 20px 60px rgba(14,165,233,0.08), 0 4px 20px rgba(0,0,0,0.04);
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
    background: linear-gradient(135deg, #E0F2FE, #BAE6FD); /* Blue primary gradient */
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
.fin-order-row .value.total { color: var(--accent); font-size: 1.25rem; font-weight: 800; }

.btn-pay {
    display: inline-flex; width: auto; min-width: 220px;
    align-items: center; justify-content: center; gap: 8px;
    background: linear-gradient(135deg, var(--accent), var(--accent-dark));
    color: #fff; border: none; border-radius: 999px;
    padding: 1rem 2rem; font-size: 1rem; font-weight: 700;
    cursor: pointer; transition: all 0.2s;
    font-family: 'Montserrat', sans-serif;
    text-decoration: none; text-align: center;
    box-shadow: 0 8px 24px rgba(14,165,233,0.3);
    margin: 0 auto;
}
.btn-pay:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(14,165,233,0.4); color: #fff; }
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
.badge-pending { background: #E0F2FE; color: #0369A1; } /* Blue badge instead of yellow */

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
                    <div class="fin-title">Pesanan Dikonfirmasi! 🎉</div>
                    <p class="fin-sub">
                        Terima kasih sudah berbelanja di buyle.id.<br>
                        Pesanan Anda sedang kami proses. Notifikasi akan dikirim ke email Anda.
                    </p>

                    <div>
                        <a href="{{ route('account.orders') }}" class="btn-pay">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                            Lacak Pesanan Saya
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('products') }}" class="btn-secondary-link">
                            Lanjut Belanja &rarr;
                        </a>
                    </div>

                @else
                    {{-- PENDING PAYMENT STATE --}}
                    <div class="fin-icon-wrap">
                        <svg width="40" height="40" fill="none" stroke="#0284C7" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
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
                        <div>
                            <button id="pay-button" class="btn-pay" onclick="launchSnapPay()">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                Bayar Sekarang
                            </button>
                        </div>

                        <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
                            data-client-key="{{ config('midtrans.client_key') }}"></script>

                        <script>
                            function launchSnapPay() {
                                const btn = document.getElementById('pay-button');
                                btn.disabled = true;
                                btn.innerHTML = '⏳ &nbsp;Memuat Pembayaran...';

                                snap.pay('{{ $order->payment->midtrans_token }}', {
                                    onSuccess: function(result) {
                                        btn.innerHTML = '✅ &nbsp;Pembayaran Berhasil!';
                                        btn.style.background = 'linear-gradient(135deg, #10B981, #059669)';
                                        setTimeout(() => {
                                            window.location.href = "{{ route('account.orders') }}";
                                        }, 1500);
                                    },
                                    onPending: function(result) {
                                        btn.disabled = false;
                                        btn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:8px;vertical-align:-4px;"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Bayar Sekarang';
                                        alert('Pembayaran menunggu konfirmasi. Cek email Anda untuk instruksi selanjutnya.');
                                    },
                                    onError: function(result) {
                                        btn.disabled = false;
                                        btn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:8px;vertical-align:-4px;"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Coba Lagi';
                                        alert('Pembayaran gagal. Silakan coba metode pembayaran lain.');
                                    },
                                    onClose: function() {
                                        btn.disabled = false;
                                        btn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:8px;vertical-align:-4px;"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Bayar Sekarang';
                                    }
                                });
                            }
                        </script>

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
