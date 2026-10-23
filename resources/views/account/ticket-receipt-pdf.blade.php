<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>E-Ticket Resmi #{{ $order->order_number }}</title>
    @php
        $findFont = function($filename) {
            $paths = [
                public_path('fonts/' . $filename),
                base_path('public/fonts/' . $filename),
                base_path('public_html/fonts/' . $filename),
            ];
            foreach ($paths as $p) {
                if (file_exists($p) && filesize($p) > 1000) {
                    return $p;
                }
            }
            return null;
        };

        $lightFont    = $findFont('Montserrat-Light.ttf');
        $regularFont  = $findFont('Montserrat-Regular.ttf');
        $mediumFont   = $findFont('Montserrat-Medium.ttf');
        $semiboldFont = $findFont('Montserrat-SemiBold.ttf');

        $b64Light    = $lightFont ? base64_encode(file_get_contents($lightFont)) : null;
        $b64Regular  = $regularFont ? base64_encode(file_get_contents($regularFont)) : null;
        $b64Medium   = $mediumFont ? base64_encode(file_get_contents($mediumFont)) : null;
        $b64SemiBold = $semiboldFont ? base64_encode(file_get_contents($semiboldFont)) : null;

        // Logo buyle.id
        $logoSetting = \App\Models\Setting::get('logo');
        $logoPath = storage_path('app/public/' . $logoSetting);
        $logoBase64 = null;

        if ($logoSetting && file_exists($logoPath)) {
            $info = @getimagesize($logoPath);
            $mime = $info['mime'] ?? 'image/webp';
            if ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
                $img = @imagecreatefromwebp($logoPath);
                if ($img) {
                    ob_start();
                    imagepng($img);
                    $pngData = ob_get_clean();
                    imagedestroy($img);
                    $logoBase64 = 'data:image/png;base64,' . base64_encode($pngData);
                }
            } else {
                $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
            }
        }

        if (!$logoBase64) {
            $defaultLogo = file_exists(public_path('buyle-logo.png')) 
                ? public_path('buyle-logo.png') 
                : (file_exists(base_path('public_html/buyle-logo.png')) ? base_path('public_html/buyle-logo.png') : null);
            if ($defaultLogo && file_exists($defaultLogo)) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($defaultLogo));
            }
        }
    @endphp

    <style>
        @if($b64Light)
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 300;
            src: url('data:font/truetype;charset=utf-8;base64,{{ $b64Light }}') format('truetype');
        }
        @endif

        @if($b64Regular)
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 400;
            src: url('data:font/truetype;charset=utf-8;base64,{{ $b64Regular }}') format('truetype');
        }
        @endif

        @if($b64Medium)
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 500;
            src: url('data:font/truetype;charset=utf-8;base64,{{ $b64Medium }}') format('truetype');
        }
        @endif

        @if($b64SemiBold)
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 600;
            src: url('data:font/truetype;charset=utf-8;base64,{{ $b64SemiBold }}') format('truetype');
        }
        @endif

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', 'DejaVu Sans', Arial, sans-serif;
            font-weight: 300;
            background: #ffffff;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.45;
            padding: 24px 28px;
        }

        /* ── Header ── */
        .top-header {
            display: table;
            width: 100%;
            padding-bottom: 14px;
            border-bottom: 1.5px solid #E2E8F0;
            margin-bottom: 16px;
        }
        .top-left {
            display: table-cell;
            vertical-align: middle;
            width: 55%;
        }
        .top-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 45%;
        }
        .logo-img {
            height: 32px;
            width: auto;
            display: block;
        }
        .brand-text {
            font-size: 22px;
            font-weight: 400;
            color: #0F172A;
            letter-spacing: -0.5px;
        }
        .brand-text span {
            color: #1eb349;
            font-weight: 600;
        }
        .brand-sub {
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748B;
            margin-top: 3px;
            font-weight: 400;
        }
        .ticket-badge {
            display: inline-block;
            background: #DCFCE7;
            color: #15803D;
            border: 1px solid #86EFAC;
            border-radius: 99px;
            padding: 4px 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .order-num {
            font-size: 13px;
            font-weight: 400;
            color: #0F172A;
            margin-top: 4px;
            letter-spacing: 0.3px;
        }

        /* ── Meta Strip ── */
        .meta-strip {
            display: table;
            width: 100%;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 18px;
        }
        .meta-cell {
            display: table-cell;
            vertical-align: top;
            width: 25%;
        }
        .meta-label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748B;
            font-weight: 400;
            margin-bottom: 2px;
        }
        .meta-val {
            font-size: 11px;
            font-weight: 400;
            color: #0F172A;
        }
        .meta-val-badge {
            display: inline-block;
            background: #DCFCE7;
            color: #166534;
            font-size: 9px;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 4px;
        }

        /* ── Section Title ── */
        .section-heading {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #166534;
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* ── Ticket Card ── */
        .ticket-card {
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 16px;
            page-break-inside: avoid;
            background: #ffffff;
        }
        .ticket-header {
            display: table;
            width: 100%;
            background: #1eb349;
            color: #ffffff;
            padding: 10px 16px;
        }
        .ticket-header-left {
            display: table-cell;
            vertical-align: middle;
            width: 75%;
        }
        .ticket-header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 25%;
        }
        .event-name {
            font-size: 13px;
            font-weight: 500;
            color: #ffffff;
            letter-spacing: 0.2px;
        }
        .ticket-slot {
            font-size: 9px;
            color: #e2f5e7;
            margin-top: 2px;
            font-weight: 300;
        }
        .status-pill {
            display: inline-block;
            background: #ffffff;
            color: #166534;
            font-size: 8.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 3px 9px;
            border-radius: 99px;
        }
        .status-pill-used {
            display: inline-block;
            background: #FEF3C7;
            color: #92400E;
            font-size: 8.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 3px 9px;
            border-radius: 99px;
        }

        .ticket-body-table {
            display: table;
            width: 100%;
        }
        .ticket-info-col {
            display: table-cell;
            vertical-align: top;
            width: 68%;
            padding: 14px 16px;
        }
        .ticket-qr-col {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 32%;
            padding: 14px 12px;
            border-left: 1.5px dashed #CBD5E1;
            background: #FAFDF9;
        }

        .field-grid {
            display: table;
            width: 100%;
        }
        .field-row {
            display: table-row;
        }
        .field-cell {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding-bottom: 10px;
            padding-right: 10px;
        }
        .field-label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748B;
            font-weight: 400;
            margin-bottom: 2px;
        }
        .field-value {
            font-size: 11px;
            font-weight: 400;
            color: #0F172A;
            line-height: 1.35;
        }
        .field-value-strong {
            font-size: 12px;
            font-weight: 500;
            color: #0F172A;
        }
        .field-value-nik {
            font-size: 10.5px;
            font-weight: 400;
            color: #475569;
            letter-spacing: 0.5px;
        }

        .qr-image {
            width: 108px;
            height: 108px;
            display: block;
            margin: 0 auto 5px;
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 4px;
        }
        .ticket-code-str {
            font-size: 9px;
            font-weight: 500;
            color: #1e293b;
            letter-spacing: 0.6px;
        }
        .qr-helper {
            font-size: 7.5px;
            color: #64748B;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 300;
        }

        /* ── Instructions Box ── */
        .instruction-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }
        .instruction-title {
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            color: #166534;
            margin-bottom: 6px;
        }
        .instruction-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .instruction-list li {
            font-size: 9px;
            color: #475569;
            margin-bottom: 3px;
            line-height: 1.4;
            font-weight: 300;
        }

        /* ── Payment Summary Table ── */
        .summary-card {
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 5px 0;
            font-size: 10px;
            color: #475569;
            border-bottom: 1px solid #F1F5F9;
            font-weight: 300;
        }
        .summary-table td.tar {
            text-align: right;
            color: #0F172A;
            font-weight: 400;
        }
        .summary-table tr.total-row td {
            border-top: 1.5px solid #CBD5E1;
            border-bottom: none;
            padding-top: 8px;
            font-size: 11.5px;
            font-weight: 500;
            color: #0F172A;
        }
        .summary-table tr.total-row td.tar {
            color: #166534;
            font-weight: 600;
            font-size: 12px;
        }

        /* ── Footer ── */
        .page-footer {
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px solid #E2E8F0;
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            vertical-align: middle;
            font-size: 8px;
            color: #94A3B8;
            font-weight: 300;
        }
        .footer-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 8px;
            color: #94A3B8;
            font-weight: 300;
        }
    </style>
</head>
<body>

    {{-- Top Header --}}
    <div class="top-header">
        <div class="top-left">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" class="logo-img" alt="buyle.id">
            @else
                <div class="brand-text">buyle<span>.id</span></div>
            @endif
            <div class="brand-sub">Platform E-Ticket Event</div>
        </div>
        <div class="top-right">
            <div class="ticket-badge">E-Ticket Resmi</div>
            <div class="order-num">#{{ $order->order_number }}</div>
        </div>
    </div>

    {{-- Order Meta Strip --}}
    <div class="meta-strip">
        <div class="meta-cell">
            <div class="meta-label">Nama Pemesan</div>
            <div class="meta-val">{{ $order->customer_name ?? ($order->user?->name ?? '-') }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">Tanggal Transaksi</div>
            <div class="meta-val">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">Status Pembayaran</div>
            <div class="meta-val">
                <span class="meta-val-badge">LUNAS</span>
            </div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">Total Tiket</div>
            <div class="meta-val">{{ isset($order->ticketPasses) ? $order->ticketPasses->count() : 1 }} Tiket Pass</div>
        </div>
    </div>

    {{-- Ticket Passes List --}}
    @if(isset($order->ticketPasses) && $order->ticketPasses->count() > 0)
        <div class="section-heading">Daftar Tiket Masuk ({{ $order->ticketPasses->count() }} Tiket)</div>

        @foreach($order->ticketPasses as $index => $pass)
            @php
                $itemForPass = $order->items->first(fn($i) => $i->product_id == $pass->product_id);
            @endphp
            <div class="ticket-card">
                <div class="ticket-header">
                    <div class="ticket-header-left">
                        <div class="event-name">{{ $pass->product?->name ?? ($itemForPass?->product_name ?? 'Event Ticket') }}</div>
                        <div class="ticket-slot">Tiket {{ $index + 1 }} dari {{ $order->ticketPasses->count() }} • Kategori: {{ $itemForPass?->product_name ?? 'General Admission' }}</div>
                    </div>
                    <div class="ticket-header-right">
                        @if($pass->status === 'valid')
                            <span class="status-pill">Valid Entry</span>
                        @else
                            <span class="status-pill-used">Sudah Check-In</span>
                        @endif
                    </div>
                </div>

                <div class="ticket-body-table">
                    {{-- Info Kolom Kiri --}}
                    <div class="ticket-info-col">
                        <div class="field-grid">
                            <div class="field-row">
                                <div class="field-cell">
                                    <div class="field-label">Pemegang Tiket</div>
                                    <div class="field-value-strong">{{ $pass->holder_name }}</div>
                                </div>
                                <div class="field-cell">
                                    <div class="field-label">Nomor Identitas (NIK)</div>
                                    <div class="field-value-nik">{{ \App\Models\TicketPass::maskNik($pass->holder_nik) }}</div>
                                </div>
                            </div>

                            <div class="field-row">
                                <div class="field-cell">
                                    <div class="field-label">Tanggal Pelaksanaan</div>
                                    <div class="field-value">{{ $pass->product?->event_date?->translatedFormat('l, d F Y') ?? ($pass->product?->event_date?->format('d M Y') ?? 'Sesuai Jadwal') }}</div>
                                </div>
                                <div class="field-cell">
                                    <div class="field-label">Waktu &amp; Open Gate</div>
                                    <div class="field-value">
                                        {{ $pass->product?->event_time ?? '-' }}
                                        @if($pass->product?->gate_open_time)
                                            <span style="color:#64748B;">(Gate: {{ $pass->product->gate_open_time }})</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="field-row">
                                <div class="field-cell" style="padding-bottom: 0;">
                                    <div class="field-label">Lokasi / Venue Acara</div>
                                    <div class="field-value">{{ $pass->product?->event_location ?? 'Lokasi Ditentukan Panitia' }}</div>
                                </div>
                                <div class="field-cell" style="padding-bottom: 0;">
                                    <div class="field-label">Penyelenggara / Organizer</div>
                                    <div class="field-value">{{ $pass->product?->seller?->shop_name ?? ($pass->product?->seller?->name ?? 'Official Organizer') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- QR Code Kolom Kanan --}}
                    <div class="ticket-qr-col">
                        <img src="{{ \App\Http\Controllers\QrCodeController::generateBase64($pass->ticket_code ?: $pass->qr_token) }}" class="qr-image" alt="QR Code Tiket">
                        <div class="ticket-code-str">{{ $pass->ticket_code }}</div>
                        <div class="qr-helper">Tunjukkan Saat Check-In</div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- Panduan Check-In --}}
    <div class="instruction-box">
        <div class="instruction-title">Panduan Check-In &amp; Ketentuan Acara</div>
        <ul class="instruction-list">
            <li>1. Tunjukkan QR Code pada e-ticket ini langsung dari layar ponsel Anda atau versi cetak ke petugas scanner di gerbang masuk.</li>
            <li>2. Setiap QR Code hanya berlaku untuk 1 (satu) kali akses masuk sesuai nama pemegang tiket yang terdaftar.</li>
            <li>3. Petugas berhak meminta tanda pengenal sah (KTP / SIM / Paspor) yang sesuai dengan NIK untuk verifikasi bila diperlukan.</li>
            <li>4. Jagalah kerahasiaan tiket ini. Dilarang membagikan foto atau barcode tiket ke media sosial untuk menghindari penyalahgunaan pihak lain.</li>
        </ul>
    </div>

    {{-- Ringkasan Pembayaran --}}
    <div class="section-heading">Ringkasan Pembayaran</div>
    <div class="summary-card">
        <table class="summary-table">
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }} &times; {{ $item->qty }}</td>
                <td class="tar">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            @if($order->platform_fee > 0)
            <tr>
                <td>Biaya Layanan / Platform Fee</td>
                <td class="tar">Rp {{ number_format($order->platform_fee, 0, ',', '.') }}</td>
            </tr>
            @endif

            @if(isset($order->admin_fee) && $order->admin_fee > 0)
            <tr>
                <td>Biaya Transaksi / Admin Fee</td>
                <td class="tar">Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</td>
            </tr>
            @endif

            @if($order->discount > 0)
            <tr>
                <td>Diskon Promo</td>
                <td class="tar" style="color:#166534;">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
            </tr>
            @endif

            <tr class="total-row">
                <td>Total Pembayaran</td>
                <td class="tar">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- Footer --}}
    <div class="page-footer">
        <div class="footer-left">
            E-Ticket ini sah dan diterbitkan secara resmi oleh platform buyle.id &bull; Dicetak: {{ now()->format('d M Y, H:i') }} WIB
        </div>
        <div class="footer-right">
            Dokumen Elektronik &bull; buyle.id
        </div>
    </div>

</body>
</html>
