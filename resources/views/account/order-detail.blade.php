@extends('account.layout')
@section('title', 'Detail Pesanan #' . $order->order_number . ' – Akun')

@section('acc_page')
<style>
:root {
    --c-border: #E2E8F0;
    --c-surface: #F8FAFC;
    --c-text: #0F172A;
    --c-muted: #64748B;
    --c-accent: #1eb349;
}
.od-card { background: #fff; border: 1px solid var(--c-border); border-radius: 12px; margin-bottom: 1.5rem; overflow: hidden; }
.od-header { background: var(--c-surface); padding: 1rem 1.5rem; border-bottom: 1px solid var(--c-border); display: flex; justify-content: space-between; align-items: center; }
.od-body { padding: 1.5rem; }
.od-title { font-size: 1rem; font-weight: 700; color: var(--c-text); margin: 0; }
.od-meta { font-size: 0.85rem; color: var(--c-muted); }
.od-status { font-size: 0.75rem; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 999px; white-space: nowrap; flex-shrink: 0; display: inline-block; }
@media (max-width: 640px) {
    .od-status { font-size: 0.7rem; padding: 0.25rem 0.55rem; }
}

.od-item { display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px dashed var(--c-border); }
.od-item:last-child { border-bottom: none; padding-bottom: 0; }
.od-item-img { width: 70px; height: 70px; border-radius: 8px; object-fit: cover; background: #F1F5F9; }
.od-item-info { flex: 1; }
.od-item-title { font-size: 0.95rem; font-weight: 600; color: var(--c-text); margin-bottom: 0.25rem; }
.od-item-meta { font-size: 0.85rem; color: var(--c-muted); }
.od-item-price { font-size: 0.95rem; font-weight: 700; color: var(--c-text); }

.summary-row { display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--c-muted); margin-bottom: 0.5rem; }
.summary-row.total { font-size: 1.1rem; font-weight: 800; color: var(--c-accent); margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--c-border); }

/* Shopee-style Tracking & Address Card */
.tr-card { background: #fff; border: 1px solid var(--c-border); border-radius: 12px; display: flex; overflow: hidden; margin-bottom: 1.5rem; }
.tr-left { width: 320px; padding: 1.5rem; border-right: 1px solid var(--c-border); background: #FAFAFA; flex-shrink: 0; }
.tr-right { flex: 1; padding: 1.5rem; }

.tr-title { font-size: 1.1rem; font-weight: 700; color: var(--c-text); margin-bottom: 1.25rem; }
.tr-addr-name { font-weight: 700; color: var(--c-text); margin-bottom: 0.5rem; font-size: 0.95rem; }
.tr-addr-phone { color: var(--c-muted); margin-bottom: 0.5rem; font-size: 0.9rem; }
.tr-addr-text { color: var(--c-text); font-size: 0.9rem; line-height: 1.5; }

.tr-right-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; border-bottom: 1px dashed var(--c-border); padding-bottom: 1rem; }
.tr-courier-name { font-weight: 700; color: var(--c-text); font-size: 0.95rem; text-transform: uppercase; margin-bottom: 0.2rem; }
.tr-courier-awb { font-size: 0.85rem; color: var(--c-muted); letter-spacing: 0.05em; font-family: monospace; }

.tr-timeline { position: relative; padding-left: 1.5rem; }
.tr-timeline::before { content: ''; position: absolute; left: 0.6rem; top: 0.5rem; bottom: 0; width: 1px; background: #E2E8F0; }

.tr-tl-item { position: relative; margin-bottom: 1.25rem; display: flex; gap: 1rem; align-items: flex-start; }
.tr-tl-item:last-child { margin-bottom: 0; }
.tr-tl-icon { position: absolute; left: -1.5rem; top: 0.1rem; width: 1.2rem; height: 1.2rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #fff; }
.tr-tl-icon.active { background: #10B981; color: #fff; left: -1.75rem; width: 1.7rem; height: 1.7rem; top: -0.1rem; border: 2px solid #fff; box-shadow: 0 0 0 1px #10B981; }
.tr-tl-icon.truck { background: #fff; color: #94A3B8; border: 1px solid #CBD5E1; }
.tr-tl-icon.dot { background: #CBD5E1; width: 0.6rem; height: 0.6rem; left: -1.2rem; top: 0.4rem; border: none; }

.tr-tl-content { flex: 1; }
.tr-tl-time { font-size: 0.8rem; color: var(--c-muted); margin-bottom: 0.1rem; font-weight: 500; }
.tr-tl-time.active { color: #10B981; }
.tr-tl-desc { font-size: 0.9rem; color: var(--c-text); line-height: 1.4; }
.tr-tl-desc.active { color: #10B981; font-weight: 600; }
.tr-tl-city { font-size: 0.8rem; color: var(--c-muted); margin-top: 0.2rem; }

.tr-tl-more { display: inline-block; margin-top: 1rem; color: #16a34a; font-weight: 700; font-size: 0.85rem; cursor: pointer; text-decoration: none; }

</style>

<div class="mb-4">
    <a href="{{ route('account.orders') }}" style="background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; border-radius: 10px; font-weight: 700; font-size: 0.85rem; padding: 0.6rem 1.25rem; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; box-shadow: 0 4px 14px rgba(30,179,73,0.25); transition: all 0.2s;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Pesanan
    </a>
</div>

<div class="od-card">
    <div class="od-header">
        <div>
            <h2 class="od-title">No. Pesanan: #{{ $order->order_number }}</h2>
            <div class="od-meta">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
        </div>
        <div class="od-status" style="background-color: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0;">
            {{ $order->status->label() }}
        </div>
    </div>
    
    <div class="od-body" style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
        
        {{-- KIRI: PRODUK & TRACKING --}}
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--c-text);">Daftar Produk</h3>
            <div style="margin-bottom: 2rem;">
                @foreach($order->items as $item)
                <div class="od-item">
                    @php
                        $imgUrl = $item->product ? $item->product->image_url : asset('images/service-default.jpg');
                    @endphp
                    <img src="{{ $imgUrl }}" class="od-item-img" alt="{{ $item->product_name }}" onerror="this.src='https://placehold.co/150x150/f1f5f9/94a3b8?text=No+Image'">
                    <div class="od-item-info">
                        <div class="od-item-title">{{ $item->product_name }}</div>
                        <div class="od-item-meta">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="od-item-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
            </div> <!-- END KIRI -->

            @if(isset($order->ticketPasses) && $order->ticketPasses->count() > 0)
                <div style="margin-top: 2rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--c-text); display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/><path d="M13 5v14"/><path d="M13 9h.01"/><path d="M13 15h.01"/></svg>
                        E-Ticket Digital Pass ({{ $order->ticketPasses->count() }} Tiket)
                    </h3>
                    
                    @foreach($order->ticketPasses as $pass)
                    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1.5px solid #CBD5E1; border-radius: 16px; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: center;">
                        {{-- QR CODE --}}
                        <div style="text-align: center; background: #fff; padding: 0.75rem; border-radius: 14px; border: 1px solid #E2E8F0; width: 160px;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($pass->qr_token) }}" alt="QR Code Tiket" style="width: 130px; height: 130px; display: block; margin: 0 auto 0.5rem;">
                            <div style="font-family: monospace; font-size: 0.75rem; font-weight: 700; color: #475569;">{{ $pass->ticket_code }}</div>
                        </div>

                        {{-- TICKET INFO --}}
                        <div style="flex: 1; min-width: 240px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin: 0;">{{ $pass->product?->name }}</h4>
                                <span style="font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 99px; text-transform: uppercase; background: {{ $pass->status === 'valid' ? '#DCFCE7' : '#FEF3C7' }}; color: {{ $pass->status === 'valid' ? '#166534' : '#92400E' }};">
                                    {{ $pass->status === 'valid' ? 'VALID / TERDAFTAR' : 'SUDAH DIPAKAI' }}
                                </span>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; font-size: 0.82rem; color: #475569; margin-top: 0.75rem;">
                                <div>
                                    <div style="font-size: 0.7rem; color: #94A3B8; font-weight: 600; text-transform: uppercase;">Pemegang Tiket</div>
                                    <div style="font-weight: 700; color: #0F172A;">{{ $pass->holder_name }}</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.7rem; color: #94A3B8; font-weight: 600; text-transform: uppercase;">Tanggal & Waktu</div>
                                    <div style="font-weight: 700; color: #0F172A;">{{ $pass->product?->event_date?->format('d M Y') ?? 'Sesuai Jadwal' }} ({{ $pass->product?->event_time ?? '-' }})</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.7rem; color: #94A3B8; font-weight: 600; text-transform: uppercase;">Lokasi / Venue</div>
                                    <div style="font-weight: 700; color: #0F172A;">{{ $pass->product?->event_location ?? 'Online / Venue Event' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

        {{-- KANAN: RINGKASAN --}}
        <div>
            @if($order->status->value === 'pending')
                <div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem;">
                    <div style="font-size: 0.9rem; font-weight: 700; color: #92400E; margin-bottom: 0.5rem;">Belum Dibayar</div>
                    <div style="font-size: 0.8rem; color: #B45309; margin-bottom: 1rem;">Selesaikan pembayaran Anda agar pesanan dapat diproses.</div>
                    <a href="{{ route('checkout.finish', $order->order_number) }}" style="display: block; width: 100%; text-align: center; background: #059669; color: #fff; text-decoration: none; padding: 0.75rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem;">Bayar Sekarang</a>
                </div>
            @endif
            @if($order->status->value === 'shipped')
                <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem;">
                    <div style="font-size: 0.9rem; font-weight: 700; color: #166534; margin-bottom: 0.5rem;">Pesanan Sedang Dikirim</div>
                    <div style="font-size: 0.8rem; color: #15803D; margin-bottom: 1rem;">Jika pesanan sudah Anda terima dengan baik, silakan konfirmasi pesanan selesai.</div>
                    
                    <form id="complete-order-form" action="{{ route('account.orders.complete', $order->id) }}" method="POST" onsubmit="event.preventDefault(); openConfirmModal();">
                        @csrf
                        <button type="button" onclick="openConfirmModal()" style="display: block; width: 100%; text-align: center; background: #059669; color: #fff; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                            Pesanan Diterima
                        </button>
                    </form>
                </div>
            @endif

            <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem; color: var(--c-text);">Ringkasan Pembayaran</h3>
            <div style="margin-bottom: 2rem;">
                <div class="summary-row">
                    <span>Total Harga Barang</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Ongkos Kirim</span>
                    <span>
                        @if($order->shipping_cost > 0)
                            Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </span>
                </div>
                @if($order->platform_fee > 0)
                <div class="summary-row">
                    <span>Platform Fee (5%)</span>
                    <span>Rp {{ number_format($order->platform_fee, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($order->discount > 0)
                <div class="summary-row" style="color: #10B981;">
                    <span>Diskon</span>
                    <span>-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span>Total Belanja</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div> <!-- END KANAN -->
    </div> <!-- END od-body -->
</div> <!-- END od-card -->

@if(in_array($order->status->value, ['confirmed', 'processing', 'shipped', 'delivered']))
<div class="tr-card" style="background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; border: none; box-shadow: 0 10px 30px rgba(30,179,73,0.3); display: block;">
    <div style="padding: 2rem;">
        <h3 class="tr-title" style="color: #fff; border-bottom: 1px solid rgba(255,255,255,0.3); padding-bottom: 1rem; margin-bottom: 1.5rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: -4px; margin-right: 8px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
            Akses Produk / Layanan Jasa
        </h3>
        
        @foreach($order->items as $item)
            @if($item->product && $item->product->digital_resource)
                <div style="margin-bottom: 1.25rem;">
                    <div style="font-weight: 700; font-size: 1rem; margin-bottom: 0.5rem;">{{ $item->product_name }}</div>
                    <a href="{{ $item->product->digital_resource }}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #fff; color: #1eb349; padding: 0.75rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: transform 0.2s;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                        Buka Link / WhatsApp
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</div>
@else
<div class="tr-card" style="padding: 3rem 2rem; text-align: center; color: var(--c-muted); display: block;">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 1rem; opacity: 0.5;">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
    </svg>
    <div style="font-weight: 600; font-size: 1.15rem; color: var(--c-text);">Menunggu Pembayaran</div>
    <div style="font-size: 0.95rem; margin-top: 0.5rem;">Selesaikan pembayaran Anda untuk mengakses produk atau layanan jasa.</div>
</div>
@endif

{{-- Custom Confirm Modal --}}
<div id="confirmModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(4px);z-index:9999;align-items:center;justify-content:center;padding:1rem;opacity:0;transition:opacity .2s;">
    <div id="confirmModalBox" style="background:#fff;border-radius:20px;padding:2rem;width:100%;max-width:400px;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,0.1);transform:scale(0.95);transition:transform .2s;">
        <div style="width:64px;height:64px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
            <svg width="32" height="32" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h3 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0 0 .5rem;">Konfirmasi Pesanan</h3>
        <p style="font-size:.9rem;color:#64748B;margin:0 0 2rem;line-height:1.5;">Apakah Anda yakin pesanan sudah diterima dengan baik?</p>
        <div style="display:flex;gap:1rem;">
            <button type="button" onclick="closeConfirmModal()" style="flex:1;padding:.75rem;background:#F1F5F9;color:#64748B;border:none;border-radius:12px;font-weight:700;font-size:.95rem;cursor:pointer;transition:all .2s;" onmouseover="this.style.background='#E2E8F0';this.style.color='#475569'" onmouseout="this.style.background='#F1F5F9';this.style.color='#64748B'">Batal</button>
            <button type="button" onclick="submitCompleteOrder()" style="flex:1;padding:.75rem;background:#1eb349;color:#fff;border:none;border-radius:12px;font-weight:700;font-size:.95rem;cursor:pointer;transition:all .2s;" onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#1eb349'">Ya, Diterima</button>
        </div>
    </div>
</div>

<script>
function openConfirmModal() {
    const modal = document.getElementById('confirmModal');
    const box = document.getElementById('confirmModalBox');
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.style.opacity = '1';
        box.style.transform = 'scale(1)';
    }, 10);
}
function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    const box = document.getElementById('confirmModalBox');
    modal.style.opacity = '0';
    box.style.transform = 'scale(0.95)';
    setTimeout(() => { modal.style.display = 'none'; }, 200);
}
function submitCompleteOrder() {
    document.getElementById('complete-order-form').submit();
}
</script>

<style>
@media(max-width: 768px) {
    .od-body { grid-template-columns: 1fr !important; }
    .tr-card { flex-direction: column; }
    .tr-left, .tr-right { width: 100%; border-right: none; }
    .tr-left { border-bottom: 1px solid var(--c-border); }
    .tr-right-header { flex-direction: column; gap: 1rem; }
    .tr-right-header > div:last-child { text-align: left !important; }
}
</style>
@endsection
