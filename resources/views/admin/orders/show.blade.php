@extends('layouts.admin')
@section('title', 'Rincian Pesanan')
@section('page-title', 'Rincian Pesanan #'.$order->order_number)
@section('content')

<style>
/* ── Premium Order Detail Page ── */
.od-page { font-family: 'Montserrat', sans-serif; }

/* Top Navigation */
.od-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
.od-back {
    display: inline-flex; align-items: center; gap: .5rem; color: #64748B;
    text-decoration: none; font-size: .85rem; font-weight: 800; transition: all .2s;
    background: #fff; padding: .65rem 1.25rem; border-radius: 99px;
    border: 1.5px solid #E2E8F0; box-shadow: 0 2px 5px rgba(0,0,0,0.02);
}
.od-back:hover { color: #0F172A; border-color: #CBD5E1; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

/* Status Pill Header */
.od-status-pill {
    padding: .5rem 1.25rem; border-radius: 99px; font-size: .85rem; font-weight: 800;
    display: inline-flex; align-items: center; gap: .5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}

/* Grids */
.od-grid { display: grid; grid-template-columns: 2fr 1.2fr; gap: 1.75rem; align-items: start; }
@media(max-width: 992px) { .od-grid { grid-template-columns: 1fr; } }

/* Cards */
.od-card {
    background: #fff; border-radius: 20px; padding: 2rem;
    box-shadow: 0 2px 15px rgba(0,0,0,0.03); border: 1.5px solid #F1F5F9;
    margin-bottom: 1.75rem;
}
.od-card-title {
    font-size: 1.1rem; font-weight: 800; color: #0F172A; margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: .65rem; border-bottom: 2px dashed #F1F5F9;
    padding-bottom: 1rem;
}

/* Product Table */
.od-table { width: 100%; border-collapse: collapse; }
.od-table th {
    text-align: left; padding-bottom: 1rem; border-bottom: 2px solid #F1F5F9;
    color: #94A3B8; font-size: .75rem; text-transform: uppercase; font-weight: 800; letter-spacing: .05em;
}
.od-table td { padding: 1.25rem 0; border-bottom: 1px dashed #F1F5F9; vertical-align: middle; }
.od-table tr:last-child td { border-bottom: none; padding-bottom: 0; }

.od-prod { display: flex; gap: 1.25rem; align-items: center; }
.od-prod-img { width: 64px; height: 64px; border-radius: 12px; object-fit: cover; border: 1.5px solid #E2E8F0; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
.od-prod-title { font-weight: 800; font-size: .9rem; color: #0F172A; line-height: 1.4; }
.od-prod-var { font-size: .75rem; color: #64748B; margin-top: .35rem; font-weight: 600; }

.od-qty { font-weight: 800; font-size: .85rem; color: #475569; }
.od-price { font-weight: 800; font-size: .9rem; color: #0F172A; text-align: right; }

/* Totals Box */
.od-totals {
    margin-top: 2rem; background: #F8FAFC; padding: 1.5rem; border-radius: 16px;
    border: 1.5px dashed #CBD5E1;
}
.od-total-row { display: flex; justify-content: space-between; font-size: .85rem; font-weight: 600; color: #475569; margin-bottom: .75rem; }
.od-total-row:last-child {
    margin-bottom: 0; margin-top: 1rem; padding-top: 1rem;
    border-top: 2px solid #E2E8F0; font-size: 1.2rem; color: #EF4444; font-weight: 800;
}

/* Info Lists */
.od-info-list { display: flex; flex-direction: column; gap: 1.25rem; }
.od-info-item { display: flex; flex-direction: column; gap: .3rem; }
.od-info-lbl { font-size: .75rem; color: #94A3B8; font-weight: 700; text-transform: uppercase; letter-spacing: .02em; }
.od-info-val { font-size: .9rem; font-weight: 700; color: #1E293B; line-height: 1.5; }

/* Forms */
.od-form-group { margin-bottom: 1.25rem; }
.od-label { display: block; font-size: .8rem; font-weight: 800; color: #475569; margin-bottom: .5rem; }
.od-select, .od-input {
    width: 100%; padding: .85rem 1rem; border: 1.5px solid #E2E8F0; border-radius: 12px;
    font-family: 'Montserrat', sans-serif; font-size: .85rem; outline: none;
    background: #F8FAFC; font-weight: 600; transition: all .2s; color: #0F172A;
}
.od-select:focus, .od-input:focus { border-color: #3B82F6; background: #fff; box-shadow: 0 0 0 4px rgba(59,130,246,0.1); }
.od-btn {
    width: 100%; padding: .85rem; background: #0F172A; color: #fff; font-family: 'Montserrat', sans-serif;
    font-weight: 800; font-size: .9rem; border: none; border-radius: 12px; cursor: pointer; transition: all .2s;
}
.od-btn:hover { background: #1E293B; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(15,23,42,0.2); }
</style>

<div class="od-page">

    <div class="od-top">
        <a href="{{ route('admin.orders.index') }}" class="od-back">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Pesanan
        </a>
        <div class="od-status-pill" style="background:{{ $order->status->color() === 'red' ? '#FEF2F2' : ($order->status->color() === 'green' ? '#F0FDF4' : ($order->status->color() === 'yellow' ? '#FFFBEB' : '#EFF6FF')) }};color:{{ $order->status->color() === 'red' ? '#DC2626' : ($order->status->color() === 'green' ? '#16A34A' : ($order->status->color() === 'yellow' ? '#D97706' : '#2563EB')) }}; border: 1px solid currentColor;">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
            Status: {{ $order->status->label() }}
        </div>
    </div>

    @if(session('success'))
        <div style="background:#F0FDF4;color:#15803D;padding:1rem 1.5rem;border-radius:16px;margin-bottom:2rem;border:1.5px solid #BBF7D0;font-size:.85rem;font-weight:700;box-shadow:0 2px 10px rgba(21,128,61,0.05);">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#FEF2F2;color:#DC2626;padding:1rem 1.5rem;border-radius:16px;margin-bottom:2rem;border:1.5px solid #FECACA;font-size:.85rem;font-weight:700;box-shadow:0 2px 10px rgba(220,38,38,0.05);">
            ! {{ session('error') }}
        </div>
    @endif

    <div class="od-grid">
        
        {{-- Left Col: Items & Totals --}}
        <div>
            <div class="od-card">
                <div class="od-card-title">
                    <svg width="24" height="24" fill="none" stroke="#EF4444" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z M3 6h18 M16 10a4 4 0 0 1-8 0"/></svg>
                    Daftar Produk Dipesan
                </div>
                
                <table class="od-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="od-prod">
                                    <img src="{{ $item->product && $item->product->image ? asset('storage/'.$item->product->image) : asset('img/no-image.jpg') }}" class="od-prod-img" onerror="this.src='https://via.placeholder.com/64?text=Img'">
                                    <div>
                                        <div class="od-prod-title">{{ $item->product_name }}</div>
                                        @if($item->variant_name)
                                            <div class="od-prod-var">Variasi: {{ $item->variant_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="od-qty">x{{ $item->qty }}</td>
                            <td class="od-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="od-totals">
                    <div class="od-total-row">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="od-total-row">
                        <span>Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="od-total-row" style="color:#10B981;">
                        <span>Diskon / Voucher</span>
                        <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="od-total-row">
                        <span>Total Pembayaran Akhir</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>

            {{-- Shipping Address --}}
            <div class="od-card">
                <div class="od-card-title">
                    <svg width="24" height="24" fill="none" stroke="#3B82F6" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                    Informasi Pengiriman
                </div>
                @if($order->shipping_address)
                    <div class="od-info-list" style="background:#F8FAFC;padding:1.5rem;border-radius:12px;border:1px solid #E2E8F0;">
                        <div class="od-info-item">
                            <span class="od-info-lbl">Penerima</span>
                            <span class="od-info-val">{{ $order->shipping_address['receiver_name'] ?? '-' }}</span>
                        </div>
                        <div class="od-info-item">
                            <span class="od-info-lbl">Nomor HP / WhatsApp</span>
                            <span class="od-info-val">{{ $order->shipping_address['phone'] ?? '-' }}</span>
                        </div>
                        <div class="od-info-item">
                            <span class="od-info-lbl">Alamat Lengkap</span>
                            <span class="od-info-val" style="color:#334155;">
                                {{ $order->shipping_address['full_address'] ?? '-' }}
                                @if(!empty($order->shipping_address['postal_code']))
                                    <br><span style="color:#64748B;font-size:.8rem;">Kode Pos: {{ $order->shipping_address['postal_code'] }}</span>
                                @endif
                            </span>
                        </div>
                    </div>
                @else
                    <div style="font-size:.85rem;color:#94A3B8;padding:2rem;text-align:center;background:#F8FAFC;border-radius:12px;border:1px dashed #CBD5E1;">Tidak ada informasi alamat pengiriman.</div>
                @endif
            </div>

        </div>

        {{-- Right Col: Status Update & Info --}}
        <div>
            
            {{-- Shipping Cost Update Form --}}
            @if($order->status === \App\Enums\OrderStatus::Pending)
            <div class="od-card">
                <div class="od-card-title">
                    <svg width="24" height="24" fill="none" stroke="#0EA5E9" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Kalkulasi Ongkos Kirim
                </div>
                <div style="background:#EFF6FF; padding:1rem; border-radius:10px; font-size:.8rem; color:#1E3A8A; margin-bottom:1.25rem;">
                    Pesanan ini belum dibayar dan ongkos kirim saat ini adalah Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}. Update ongkir di sini sebelum pembeli menyelesaikan pembayaran.
                </div>
                <form action="{{ route('admin.orders.shipping_cost', $order) }}" method="POST">
                    @csrf
                    <div class="od-form-group">
                        <label class="od-label">Nominal Ongkos Kirim (Rp)</label>
                        <input type="number" name="shipping_cost" class="od-input" placeholder="Contoh: 15000" value="{{ $order->shipping_cost > 0 ? $order->shipping_cost : '' }}" required min="0">
                    </div>
                    <button type="submit" class="od-btn" style="background:#0EA5E9;box-shadow:0 4px 12px rgba(14,165,233,0.2);">Simpan Ongkir Baru</button>
                </form>
            </div>
            @endif

            {{-- Status Update Form --}}
            <div class="od-card">
                <div class="od-card-title">
                    <svg width="24" height="24" fill="none" stroke="#F59E0B" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Ubah Status Pesanan
                </div>
                @if(count($nextStatuses) > 0)
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf
                        <div class="od-form-group">
                            <label class="od-label">Pilih Status Baru</label>
                            <select name="status" class="od-select">
                                @foreach($nextStatuses as $ns)
                                    <option value="{{ $ns->value }}">{{ $ns->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="od-btn">Perbarui Status</button>
                    </form>
                @else
                    <div style="padding:1rem;background:#F8FAFC;border-radius:12px;font-size:.85rem;color:#64748B;font-weight:600;text-align:center;border:1px dashed #CBD5E1;">
                        Pesanan ini sudah selesai atau dibatalkan. Status tidak dapat diubah lagi.
                    </div>
                @endif
            </div>

            {{-- Resi Form --}}
            @if(in_array($order->status, [\App\Enums\OrderStatus::Processing, \App\Enums\OrderStatus::Shipped]))
            <div class="od-card">
                <div class="od-card-title">
                    <svg width="24" height="24" fill="none" stroke="#10B981" stroke-width="2.5" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    Input Nomor Resi
                </div>

                {{-- Info kurir dari checkout buyer (read-only) --}}
                @if($order->shipment && $order->shipment->courier_name)
                <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.6rem;font-size:0.85rem;">
                    <svg width="16" height="16" fill="none" stroke="#16A34A" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    <span style="color:#15803D;font-weight:700;">Kurir dari Buyer:</span>
                    <span style="color:#166534;font-weight:800;">{{ strtoupper($order->shipment->courier_name) }} {{ strtoupper($order->shipment->courier_service ?? '') }}</span>
                </div>
                @endif

                <form action="{{ route('admin.orders.tracking', $order) }}" method="POST">
                    @csrf
                    <div class="od-form-group">
                        <label class="od-label">Nomor Resi / Pelacakan</label>
                        <input type="text" name="tracking_number" class="od-input"
                            placeholder="Masukkan nomor resi..."
                            value="{{ $order->shipment->tracking_number ?? '' }}"
                            style="font-size:1rem;font-weight:700;letter-spacing:0.05em;"
                            autofocus>
                    </div>
                    <button type="submit" class="od-btn" style="background:#10B981;box-shadow:0 4px 12px rgba(16,185,129,0.2);">Simpan Nomor Resi</button>
                </form>
            </div>
            @endif

            {{-- General Info --}}
            <div class="od-card">
                <div class="od-card-title">Informasi Lanjutan</div>
                <div class="od-info-list">
                    <div class="od-info-item">
                        <span class="od-info-lbl">Pembeli Transaksi</span>
                        <span class="od-info-val">{{ $order->user->name ?? '-' }} <br><span style="font-size:.75rem;color:#64748B;">{{ $order->user->email ?? '-' }}</span></span>
                    </div>
                    <div class="od-info-item">
                        <span class="od-info-lbl">Waktu Pemesanan</span>
                        <span class="od-info-val">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div class="od-info-item">
                        <span class="od-info-lbl">Catatan Pembeli</span>
                        <span class="od-info-val" style="color:{{ $order->notes ? '#0F172A' : '#94A3B8' }};font-weight:{{ $order->notes ? '700' : '500' }};background:#F8FAFC;padding:1rem;border-radius:12px;margin-top:.3rem;border:1px dashed #E2E8F0;">
                            {{ $order->notes ?: 'Tidak ada catatan.' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
// Layanan per kurir (kode kurir => daftar service)
const courierServices = {
    'jne':   ['REG', 'YES', 'OKE', 'SPS', 'JTR', 'JTR250'],
    'jnt':   ['EZ', 'Reguler'],
    'sicepat':  ['BEST', 'GOKIL', 'REG', 'HALU'],
    'anteraja': ['Reguler', 'Same Day', 'Next Day'],
    'pos':   ['Paket Kilat Khusus', 'Express Dalam Kota', 'Surat Biasa'],
    'tiki':  ['REG', 'ECO', 'ONS', 'SDS'],
    'wahana':['Reguler'],
    'sap':   ['Reguler'],
    'lion':  ['REG'],
};

function updateServiceOptions() {
    const courierSelect = document.getElementById('resi_courier_name');
    const serviceSelect = document.getElementById('resi_courier_service');
    const currentService = document.getElementById('resi_current_service')?.value || '';

    if (!courierSelect || !serviceSelect) return;

    const selectedOption = courierSelect.options[courierSelect.selectedIndex];
    const code = (selectedOption?.dataset?.code || courierSelect.value || '').toLowerCase().replace(/\s+/g, '').replace('&t','t').replace('j&t','jnt');
    const services = courierServices[code] || courierServices[Object.keys(courierServices).find(k => code.includes(k))] || [];

    serviceSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
    services.forEach(svc => {
        const opt = document.createElement('option');
        opt.value = svc;
        opt.text = svc;
        if (svc.toUpperCase() === currentService.toUpperCase()) opt.selected = true;
        serviceSelect.appendChild(opt);
    });

    // Jika tidak ada match, tambah option dari existing value agar tidak hilang
    if (currentService && !services.find(s => s.toUpperCase() === currentService.toUpperCase())) {
        const opt = document.createElement('option');
        opt.value = currentService;
        opt.text = currentService;
        opt.selected = true;
        serviceSelect.appendChild(opt);
    }
}

// Jalankan saat page load untuk pre-fill layanan
document.addEventListener('DOMContentLoaded', function() {
    updateServiceOptions();
});
</script>

@endsection
