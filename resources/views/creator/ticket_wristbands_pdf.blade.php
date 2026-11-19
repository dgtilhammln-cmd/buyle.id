<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Lembar Cetak Gelang Tiket Fisik - {{ $event ? $event->name : 'Semua Event' }}</title>
    <style>
        @page {
            margin: 10mm 10mm 12mm 10mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
            font-size: 11px;
            background: #ffffff;
        }

        /* ── HEADER DOKUMEN CETAK ── */
        .doc-header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .doc-header table {
            width: 100%;
            border-collapse: collapse;
        }
        .doc-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 3px;
        }
        .doc-subtitle {
            font-size: 11px;
            color: #475569;
            margin: 0;
        }
        .doc-meta {
            text-align: right;
            font-size: 10px;
            color: #64748b;
            line-height: 1.4;
        }
        .doc-meta strong {
            color: #0f172a;
        }

        .vendor-note-box {
            background: #f8fafc;
            border: 1px dashed #94a3b8;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 14px;
            font-size: 9.5px;
            color: #334155;
            line-height: 1.35;
        }

        /* ── GRID WRISTBAND (2 PER BARIS UNTUK A4 PORTRAIT) ── */
        .wristband-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 10px;
        }
        .wristband-col {
            width: 50%;
            vertical-align: top;
            padding: 0;
        }

        /* ── KOTAK GELANG (WRISTBAND STRIP CARD) ── */
        .wristband-card {
            border: 1.5px dashed #64748b;
            border-radius: 6px;
            background: #ffffff;
            padding: 8px 10px;
            box-sizing: border-box;
            position: relative;
        }
        .card-top-row table {
            width: 100%;
            border-collapse: collapse;
        }
        .wristband-event {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }
        .wristband-type-badge {
            display: inline-block;
            background: #0f172a;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .wristband-seq {
            text-align: right;
            font-size: 10px;
            font-weight: 800;
            color: #16a34a;
            font-family: 'Courier New', Courier, monospace;
        }

        .wristband-code-row {
            margin: 6px 0 4px;
            text-align: center;
        }
        .ticket-code-display {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 1.2px;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 3px;
            display: inline-block;
            border: 1px solid #cbd5e1;
        }

        /* BARCODE & QR TABLE */
        .code-render-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0 4px;
        }
        .barcode-cell {
            vertical-align: middle;
            text-align: center;
            width: 65%;
            padding-right: 6px;
        }
        .barcode-img {
            max-width: 100%;
            height: 42px;
            display: block;
            margin: 0 auto;
        }
        .qr-cell {
            vertical-align: middle;
            text-align: center;
            width: 35%;
            border-left: 1px dashed #cbd5e1;
            padding-left: 6px;
        }
        .qr-img {
            width: 48px;
            height: 48px;
            display: block;
            margin: 0 auto;
        }

        .wristband-bottom-row table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px solid #f1f5f9;
        }
        .holder-name {
            font-size: 9.5px;
            font-weight: 700;
            color: #1e293b;
        }
        .event-date {
            text-align: right;
            font-size: 8.5px;
            color: #64748b;
        }

        /* Cut line helper mark */
        .cut-mark {
            font-size: 7.5px;
            color: #94a3b8;
            text-align: center;
            margin-top: 2px;
            letter-spacing: 1px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    {{-- HEADER DOKUMEN CETAK --}}
    <div class="doc-header">
        <table>
            <tr>
                <td style="vertical-align: bottom;">
                    <div class="doc-title">LEMBAR CETAK GELANG TIKET FISIK (WRISTBANDS)</div>
                    <div class="doc-subtitle">
                        {{ $event ? $event->name : 'Kompilasi Seluruh Event' }}
                        @if($event && $event->event_date)
                            &bull; Tanggal Pelaksanaan: <strong>{{ $event->event_date->format('d M Y') }}</strong>
                        @endif
                    </div>
                </td>
                <td class="doc-meta" style="vertical-align: bottom;">
                    Total Tiket: <strong>{{ count($tickets) }} Gelang</strong><br>
                    Tgl Cetak: <strong>{{ date('d/m/Y H:i') }} WIB</strong><br>
                    Penyelenggara: <strong>{{ auth()->user()->name }}</strong>
                </td>
            </tr>
        </table>
    </div>

    {{-- INSTRUKSI RESMI UNTUK VENDOR PERCETAKAN --}}
    <div class="vendor-note-box">
        <strong>PANDUAN VENDOR PERCETAKAN GELANG FISIK:</strong><br>
        1. Format cetak ini siap digunakan untuk media gelang fisik (Thermal Wristband / Tyvek / PVC / Synthetic).<br>
        2. Cetak pada skala 100% (No Scale / Do not fit to page) agar rasio garis barcode dan QR code tetap presisi.<br>
        3. Setiap tiket memiliki Kode Tiket unik, Barcode 1D (Code 128) serta QR Code 2D resolusi tinggi yang dapat langsung dipindai scanner optik/laser pada pintu masuk (gate scanner).
    </div>

    {{-- GRID TICKETS --}}
    @php
        $chunks = $tickets->chunk(2);
        $totalItems = count($tickets);
        $counter = 1;
        $itemsPerPage = 10; // 5 baris x 2 kolom = 10 gelang per lembar A4
        $pageItemCount = 0;
    @endphp

    <table class="wristband-grid">
        @foreach($chunks as $row)
            @php
                $pageItemCount += count($row);
            @endphp
            <tr>
                @foreach($row as $ticket)
                    <td class="wristband-col">
                        <div class="wristband-card">
                            {{-- Top Row: Event Title, Type & Seq --}}
                            <div class="card-top-row">
                                <table>
                                    <tr>
                                        <td>
                                            <span class="wristband-event">{{ $ticket->product?->name ?? 'EVENT' }}</span>
                                            <span class="wristband-type-badge">{{ $ticket->product?->product_type === 'ticket' ? 'TIKET' : ($ticket->product?->product_type ?? 'TIKET') }}</span>
                                        </td>
                                        <td class="wristband-seq">
                                            #{{ str_pad($counter, 4, '0', STR_PAD_LEFT) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            {{-- Nomor / Kode Tiket (Monospace Besar) --}}
                            <div class="wristband-code-row">
                                <span class="ticket-code-display">{{ $ticket->ticket_code }}</span>
                            </div>

                            {{-- Barcode 1D & QR Code 2D --}}
                            <table class="code-render-table">
                                <tr>
                                    <td class="barcode-cell">
                                        <img src="{{ \App\Services\BarcodeGenerator::generateBase64Png($ticket->ticket_code, 40, 2) }}" class="barcode-img" alt="Barcode">
                                    </td>
                                    <td class="qr-cell">
                                        <img src="{{ \App\Http\Controllers\QrCodeController::generateBase64($ticket->ticket_code) }}" class="qr-img" alt="QR Code">
                                    </td>
                                </tr>
                            </table>

                            {{-- Bottom Row: Nama Pemegang & Tanggal --}}
                            <div class="wristband-bottom-row">
                                <table>
                                    <tr>
                                        <td class="holder-name">
                                            {{ $ticket->holder_name ?: 'Pengunjung' }}
                                        </td>
                                        <td class="event-date">
                                            {{ $ticket->product?->event_date ? $ticket->product->event_date->format('d/m/Y') : '-' }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="cut-mark">- - - - - - - - - - GARIS POTONG GELANG - - - - - - - - - -</div>
                        </div>
                    </td>
                    @php $counter++; @endphp
                @endforeach

                {{-- Jika ganjil di akhir baris, isi kolom kosong --}}
                @if(count($row) === 1)
                    <td class="wristband-col"></td>
                @endif
            </tr>

            @if($pageItemCount >= $itemsPerPage && $counter <= $totalItems)
                </table>
                <div class="page-break"></div>
                <table class="wristband-grid">
                @php $pageItemCount = 0; @endphp
            @endif
        @endforeach
    </table>

</body>
</html>
