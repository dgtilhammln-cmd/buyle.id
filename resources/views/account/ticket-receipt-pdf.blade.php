<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Receipt Tiket #{{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #fff;
            color: #0F172A;
            font-size: 13px;
            line-height: 1.5;
        }
        .header {
            background: #0F172A;
            padding: 24px 36px;
            display: table;
            width: 100%;
        }
        .header-left { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }
        .brand-name { font-size: 24px; font-weight: 900; color: #ffffff; letter-spacing: -0.5px; }
        .brand-dot { color: #1eb349; }
        .brand-tagline { font-size: 10px; color: #94A3B8; margin-top: 2px; letter-spacing: 0.5px; text-transform: uppercase; }
        .header-label { font-size: 10px; color: #64748B; text-transform: uppercase; letter-spacing: 1px; }
        .header-title { font-size: 18px; font-weight: 700; color: #fff; margin-top: 2px; }
        .doc-info {
            background: #F8FAFC;
            border-bottom: 2px solid #E2E8F0;
            padding: 14px 36px;
            display: table;
            width: 100%;
        }
        .doc-info-col { display: table-cell; vertical-align: top; width: 25%; }
        .doc-info-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; font-weight: 600; margin-bottom: 3px; }
        .doc-info-value { font-size: 12px; font-weight: 700; color: #0F172A; }
        .doc-info-value-green { font-size: 12px; font-weight: 700; color: #15803D; }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748B;
            padding: 16px 36px 10px;
            border-bottom: 1px solid #E2E8F0;
        }
        .ticket-wrap { padding: 14px 36px; }
        .ticket-card {
            border: 1.5px solid #CBD5E1;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 14px;
            page-break-inside: avoid;
        }
        .ticket-header {
            background: #0F172A;
            padding: 11px 18px;
            display: table;
            width: 100%;
        }
        .ticket-header-left { display: table-cell; vertical-align: middle; }
        .ticket-header-right { display: table-cell; vertical-align: middle; text-align: right; }
        .ticket-event-name { font-size: 13px; font-weight: 700; color: #fff; }
        .ticket-holder { font-size: 10px; color: #94A3B8; margin-top: 2px; }
        .ticket-status-valid { font-size: 9px; font-weight: 700; padding: 4px 10px; border-radius: 99px; text-transform: uppercase; background: #DCFCE7; color: #15803D; }
        .ticket-status-used { font-size: 9px; font-weight: 700; padding: 4px 10px; border-radius: 99px; text-transform: uppercase; background: #FEF3C7; color: #92400E; }
        .ticket-body { display: table; width: 100%; background: #fff; }
        .ticket-qr-col {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 140px;
            padding: 14px;
            border-right: 1.5px dashed #CBD5E1;
        }
        .ticket-qr-img { width: 108px; height: 108px; display: block; margin: 0 auto 6px; }
        .ticket-code { font-family: 'DejaVu Sans Mono', monospace; font-size: 9px; font-weight: 700; color: #475569; word-break: break-all; letter-spacing: 0.5px; }
        .ticket-info-col { display: table-cell; vertical-align: middle; padding: 14px 18px; }
        .ticket-info-grid { display: table; width: 100%; }
        .ticket-info-row { display: table-row; }
        .ticket-info-cell { display: table-cell; padding: 5px 10px 5px 0; width: 50%; vertical-align: top; }
        .info-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.7px; color: #94A3B8; font-weight: 600; margin-bottom: 2px; }
        .info-value { font-size: 12px; font-weight: 700; color: #0F172A; }
        .summary-section { padding: 10px 36px 20px; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { padding: 6px 0; font-size: 12px; color: #475569; border-bottom: 1px solid #F1F5F9; }
        .summary-table td.right { text-align: right; font-weight: 600; color: #0F172A; }
        .summary-table tr.total td { padding-top: 12px; font-size: 14px; font-weight: 800; border-bottom: none; border-top: 2px solid #E2E8F0; }
        .footer {
            margin-top: 20px;
            border-top: 2px solid #E2E8F0;
            padding: 14px 36px;
            display: table;
            width: 100%;
            background: #F8FAFC;
        }
        .footer-left { display: table-cell; vertical-align: middle; }
        .footer-right { display: table-cell; vertical-align: middle; text-align: right; }
        .footer-brand { font-size: 13px; font-weight: 800; color: #0F172A; }
        .footer-brand-dot { color: #1eb349; }
        .footer-url { font-size: 10px; color: #64748B; }
        .footer-text { font-size: 10px; color: #94A3B8; }
        .watermark-notice { text-align: center; padding: 10px 36px; font-size: 10px; color: #CBD5E1; letter-spacing: 0.5px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <div class="brand-name">buyle<span class="brand-dot">.</span>id</div>
            <div class="brand-tagline">Platform Digital &amp; Tiket Event</div>
        </div>
        <div class="header-right">
            <div class="header-label">E-Receipt</div>
            <div class="header-title">#{{ $order->order_number }}</div>
        </div>
    </div>

    <div class="doc-info">
        <div class="doc-info-col">
            <div class="doc-info-label">Tanggal</div>
            <div class="doc-info-value">{{ $order->created_at->format('d M Y') }}</div>
        </div>
        <div class="doc-info-col">
            <div class="doc-info-label">Pukul</div>
            <div class="doc-info-value">{{ $order->created_at->format('H:i') }} WIB</div>
        </div>
        <div class="doc-info-col">
            <div class="doc-info-label">Status</div>
            <div class="doc-info-value-green">LUNAS</div>
        </div>
        <div class="doc-info-col">
            <div class="doc-info-label">Nama Pembeli</div>
            <div class="doc-info-value">{{ $order->customer_name ?? ($order->user?->name ?? '-') }}</div>
        </div>
    </div>

    @if(isset($order->ticketPasses) && $order->ticketPasses->count() > 0)
        <div class="section-title">E-Ticket — {{ $order->ticketPasses->count() }} Tiket</div>
        <div class="ticket-wrap">
            @foreach($order->ticketPasses as $pass)
                <div class="ticket-card">
                    <div class="ticket-header">
                        <div class="ticket-header-left">
                            <div class="ticket-event-name">{{ $pass->product?->name ?? 'Event Ticket' }}</div>
                            <div class="ticket-holder">Pemegang: {{ $pass->holder_name }}</div>
                        </div>
                        <div class="ticket-header-right">
                            @if($pass->status === 'valid')
                                <span class="ticket-status-valid">Valid</span>
                            @else
                                <span class="ticket-status-used">Terpakai</span>
                            @endif
                        </div>
                    </div>
                    <div class="ticket-body">
                        <div class="ticket-qr-col">
                            <img src="{{ route('qr.code', ['data' => $pass->qr_token]) }}" class="ticket-qr-img" alt="QR">
                            <div class="ticket-code">{{ $pass->ticket_code }}</div>
                        </div>
                        <div class="ticket-info-col">
                            <div class="ticket-info-grid">
                                <div class="ticket-info-row">
                                    <div class="ticket-info-cell">
                                        <div class="info-label">Tanggal Event</div>
                                        <div class="info-value">{{ $pass->product?->event_date?->format('d M Y') ?? 'Sesuai Jadwal' }}</div>
                                    </div>
                                    <div class="ticket-info-cell">
                                        <div class="info-label">Waktu</div>
                                        <div class="info-value">{{ $pass->product?->event_time ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="ticket-info-row">
                                    <div class="ticket-info-cell">
                                        <div class="info-label">Venue / Lokasi</div>
                                        <div class="info-value">{{ $pass->product?->event_location ?? 'Online / Venue Event' }}</div>
                                    </div>
                                    <div class="ticket-info-cell">
                                        <div class="info-label">No. Pesanan</div>
                                        <div class="info-value" style="font-size:10px;">#{{ $order->order_number }}</div>
                                    </div>
                                </div>
                                <div class="ticket-info-row">
                                    <div class="ticket-info-cell">
                                        <div class="info-label">Harga Tiket</div>
                                        <div class="info-value">
                                            @php
                                                $ticketItem = $order->items->first(fn($i) => $i->product_id == $pass->product_id);
                                            @endphp
                                            Rp {{ number_format($ticketItem?->price ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="ticket-info-cell">
                                        <div class="info-label">Qty</div>
                                        <div class="info-value">1 tiket</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="section-title">Ringkasan Pembayaran</div>
    <div class="summary-section">
        <table class="summary-table">
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }} x{{ $item->qty }}</td>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @if($order->shipping_cost > 0)
            <tr>
                <td>Ongkos Kirim</td>
                <td class="right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($order->platform_fee > 0)
            <tr>
                <td>Biaya Platform</td>
                <td class="right">Rp {{ number_format($order->platform_fee, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($order->discount > 0)
            <tr>
                <td>Diskon</td>
                <td class="right" style="color:#15803D;">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total">
                <td style="color:#0F172A;">Total Pembayaran</td>
                <td class="right" style="color:#1eb349;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="footer-left">
            <div class="footer-brand">buyle<span class="footer-brand-dot">.</span>id</div>
            <div class="footer-url">buyle.id</div>
        </div>
        <div class="footer-right">
            <div class="footer-text">Tunjukkan QR Code kepada panitia saat check-in.</div>
            <div class="footer-text">Dokumen ini adalah bukti pembelian resmi buyle.id.</div>
            <div class="footer-text" style="margin-top:4px;">Dicetak: {{ now()->format('d M Y, H:i') }} WIB</div>
        </div>
    </div>

    <div class="watermark-notice">
        Dokumen ini dibuat otomatis oleh sistem buyle.id — tidak memerlukan tanda tangan fisik.
    </div>

</body>
</html>

