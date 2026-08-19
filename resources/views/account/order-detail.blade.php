@extends('account.layout')
@section('title', 'Detail Pesanan #' . $order->order_number . ' – Akun')

@section('acc_page')
<style>
:root {
    --c-border: #E2E8F0;
    --c-surface: #F8FAFC;
    --c-text: #0F172A;
    --c-muted: #64748B;
    --c-accent: #0EA5E9;
}
.od-card { background: #fff; border: 1px solid var(--c-border); border-radius: 12px; margin-bottom: 1.5rem; overflow: hidden; }
.od-header { background: var(--c-surface); padding: 1rem 1.5rem; border-bottom: 1px solid var(--c-border); display: flex; justify-content: space-between; align-items: center; }
.od-body { padding: 1.5rem; }
.od-title { font-size: 1rem; font-weight: 700; color: var(--c-text); margin: 0; }
.od-meta { font-size: 0.85rem; color: var(--c-muted); }
.od-status { font-size: 0.85rem; font-weight: 700; padding: 0.4rem 1rem; border-radius: 999px; }

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

.tr-tl-more { display: inline-block; margin-top: 1rem; color: #0284C7; font-weight: 700; font-size: 0.85rem; cursor: pointer; text-decoration: none; }

</style>

<div class="mb-4">
    <a href="{{ route('account.orders') }}" style="color: var(--c-muted); font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
        &larr; Kembali ke Daftar Pesanan
    </a>
</div>

<div class="od-card">
    <div class="od-header">
        <div>
            <h2 class="od-title">No. Pesanan: #{{ $order->order_number }}</h2>
            <div class="od-meta">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
        </div>
        <div class="od-status" style="background-color: {{ $order->status->color() === 'yellow' ? '#FEF3C7' : ($order->status->color() === 'green' ? '#D1FAE5' : ($order->status->color() === 'blue' ? '#DBEAFE' : '#F1F5F9')) }}; color: {{ $order->status->color() === 'yellow' ? '#D97706' : ($order->status->color() === 'green' ? '#059669' : ($order->status->color() === 'blue' ? '#2563EB' : '#475569')) }}; border: 1px solid currentColor;">
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
                    @if($item->product && !empty($item->product->image))
                        <img src="{{ $item->product->image_url }}" class="od-item-img" onerror="this.src='https://placehold.co/150x150/f1f5f9/94a3b8?text=No+Image'">
                    @else
                        <div class="od-item-img"></div>
                    @endif
                    <div class="od-item-info">
                        <div class="od-item-title">{{ $item->product_name }}</div>
                        <div class="od-item-meta">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="od-item-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
            </div> <!-- END KIRI -->

        {{-- KANAN: RINGKASAN --}}
        <div>
            @if($order->status->value === 'pending' && $order->payment && $order->payment->status->value === 'pending')
                <div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem;">
                    <div style="font-size: 0.9rem; font-weight: 700; color: #92400E; margin-bottom: 0.5rem;">Belum Dibayar</div>
                    <div style="font-size: 0.8rem; color: #B45309; margin-bottom: 1rem;">Selesaikan pembayaran Anda agar pesanan dapat diproses.</div>
                    
                    @if($order->shipping_cost > 0)
                        <a href="{{ route('checkout.finish', $order->order_number) }}" style="display: block; width: 100%; text-align: center; background: #059669; color: #fff; text-decoration: none; padding: 0.75rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem;">Bayar Sekarang</a>
                    @else
                        <button disabled style="display: block; width: 100%; text-align: center; background: #94A3B8; color: #fff; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: not-allowed;">Menunggu Info Ongkir</button>
                    @endif
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
                            <span style="color:#D97706; font-size:0.8rem; font-weight:600;">Menunggu Konfirmasi Admin</span>
                        @endif
                    </span>
                </div>
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

<div class="tr-card">
    <div class="tr-left">
        <h3 class="tr-title">Alamat Pengiriman</h3>
        <div class="tr-addr-name">{{ $order->shipping_address['receiver_name'] ?? '-' }}</div>
        <div class="tr-addr-phone">{{ $order->shipping_address['phone'] ?? '-' }}</div>
        <div class="tr-addr-text">
            {{ $order->shipping_address['full_address'] ?? '-' }}<br>
            {{ $order->shipping_address['district'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}<br>
            {{ $order->shipping_address['province'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}
        </div>
    </div>
    
    <div class="tr-right">
        @if($order->shipment)
        <div class="tr-right-header">
            <div>
                <h3 class="tr-title" style="margin-bottom:0.25rem;">Status Pengiriman</h3>
                <span style="font-size:0.85rem; color:var(--c-muted);">Informasi tracking resi otomatis</span>
            </div>
            <div style="text-align: right;">
                <div class="tr-courier-name">{{ strtoupper($order->shipment->courier_name) }} {{ strtoupper($order->shipment->courier_service ?? '') }}</div>
                <div class="tr-courier-awb">{{ $order->shipment->tracking_number ?? 'Resi belum diinput' }}</div>
            </div>
        </div>

        @if($tracking && !empty($tracking['manifest']))
            <div class="tr-timeline">
                @php
                    $manifests = array_reverse($tracking['manifest']);
                    $limit = 3;
                    $hasMore = count($manifests) > $limit;
                    $shown = array_slice($manifests, 0, $limit);
                    $hidden = array_slice($manifests, $limit);
                @endphp

                @foreach($shown as $index => $tl)
                <div class="tr-tl-item">
                    @if($index === 0)
                        <div class="tr-tl-icon active">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        </div>
                    @elseif($index === 1)
                        <div class="tr-tl-icon truck">
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        </div>
                    @else
                        <div class="tr-tl-icon dot"></div>
                    @endif

                    <div class="tr-tl-content">
                        <div class="tr-tl-time {{ $index === 0 ? 'active' : '' }}">{{ $tl['date'] }}</div>
                        <div class="tr-tl-desc {{ $index === 0 ? 'active' : '' }}">{{ $tl['desc'] }}</div>
                        @if(!empty($tl['city']))
                        <div class="tr-tl-city">{{ $tl['city'] }}</div>
                        @endif
                    </div>
                </div>
                @endforeach

                @if($hasMore)
                <div id="hiddenTimeline" style="display:none;">
                    @foreach($hidden as $index => $tl)
                    <div class="tr-tl-item">
                        <div class="tr-tl-icon dot"></div>
                        <div class="tr-tl-content">
                            <div class="tr-tl-time">{{ $tl['date'] }}</div>
                            <div class="tr-tl-desc">{{ $tl['desc'] }}</div>
                            @if(!empty($tl['city']))
                            <div class="tr-tl-city">{{ $tl['city'] }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                <a class="tr-tl-more" id="showMoreBtn" onclick="document.getElementById('hiddenTimeline').style.display='block'; this.style.display='none';">Lihat Lainnya</a>
                @endif
            </div>
        @elseif($order->shipment->tracking_number)
            <div style="margin-top:2rem; padding: 2rem; background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0; color:var(--c-muted); text-align:center;">
                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:0.5rem; opacity:0.5;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                <div>Tracking belum tersedia atau masih diproses oleh ekspedisi.</div>
                <div style="font-size: 0.8rem; margin-top: 0.25rem;">Biasanya update status membutuhkan waktu 1x24 jam setelah resi diinput.</div>
            </div>
        @endif
        
        @endif
    </div>
</div>

{{-- Custom Confirm Modal --}}
<div id="confirmModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(4px);z-index:9999;align-items:center;justify-content:center;padding:1rem;opacity:0;transition:opacity .2s;">
    <div id="confirmModalBox" style="background:#fff;border-radius:20px;padding:2rem;width:100%;max-width:400px;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,0.1);transform:scale(0.95);transition:transform .2s;">
        <div style="width:64px;height:64px;background:rgba(59,130,246,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
            <svg width="32" height="32" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h3 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0 0 .5rem;">Konfirmasi Pesanan</h3>
        <p style="font-size:.9rem;color:#64748B;margin:0 0 2rem;line-height:1.5;">Apakah Anda yakin pesanan sudah diterima dengan baik?</p>
        <div style="display:flex;gap:1rem;">
            <button type="button" onclick="closeConfirmModal()" style="flex:1;padding:.75rem;background:#F1F5F9;color:#64748B;border:none;border-radius:12px;font-weight:700;font-size:.95rem;cursor:pointer;transition:all .2s;" onmouseover="this.style.background='#E2E8F0';this.style.color='#475569'" onmouseout="this.style.background='#F1F5F9';this.style.color='#64748B'">Batal</button>
            <button type="button" onclick="submitCompleteOrder()" style="flex:1;padding:.75rem;background:#3B82F6;color:#fff;border:none;border-radius:12px;font-weight:700;font-size:.95rem;cursor:pointer;transition:all .2s;" onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">Ya, Diterima</button>
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
