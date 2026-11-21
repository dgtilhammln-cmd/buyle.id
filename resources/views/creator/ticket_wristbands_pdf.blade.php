<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Tiket - {{ $event ? $event->name : 'Semua Event' }}</title>
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

        $regularFont  = $findFont('Montserrat-Regular.ttf');
        $mediumFont   = $findFont('Montserrat-Medium.ttf');
        $semiboldFont = $findFont('Montserrat-SemiBold.ttf');
        $boldFont     = $findFont('Montserrat-Bold.ttf');

        $b64Regular  = $regularFont ? base64_encode(file_get_contents($regularFont)) : null;
        $b64Medium   = $mediumFont ? base64_encode(file_get_contents($mediumFont)) : null;
        $b64SemiBold = $semiboldFont ? base64_encode(file_get_contents($semiboldFont)) : null;
        $b64Bold     = $boldFont ? base64_encode(file_get_contents($boldFont)) : null;
    @endphp

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');

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

        @if($b64Bold)
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 700;
            src: url('data:font/truetype;charset=utf-8;base64,{{ $b64Bold }}') format('truetype');
        }
        @endif

        @page {
            margin: 10mm 8mm 10mm 8mm;
            size: A4 portrait;
        }

        * {
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif !important;
        }

        body {
            font-family: 'Montserrat', sans-serif !important;
            background: #ffffff;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        .ticket-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px 14px;
            table-layout: fixed;
        }

        .ticket-cell {
            width: 33.333%;
            vertical-align: top;
            padding: 0;
        }

        .empty-cell {
            border: none;
            background: transparent;
        }

        .ticket-card {
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            border-radius: 18px;
            padding: 16px 12px 14px 12px;
            text-align: center;
            box-sizing: border-box;
        }

        .qr-box {
            width: 150px;
            height: 150px;
            margin: 0 auto;
            text-align: center;
        }

        .qr-img {
            width: 150px;
            height: 150px;
            display: block;
            margin: 0 auto;
        }

        .ticket-code {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 1px;
            margin-top: 10px;
            margin-bottom: 4px;
            line-height: 1.2;
            text-align: center;
        }

        .ticket-holder {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 11px;
            font-weight: 600;
            color: #475569;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            line-height: 1.25;
            text-align: center;
            overflow: hidden;
            word-break: break-word;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    @php
        $pages = $tickets->chunk(12);
    @endphp

    @foreach($pages as $pageIndex => $pageTickets)
        <table class="ticket-grid">
            @foreach($pageTickets->chunk(3) as $row)
                <tr>
                    @foreach($row as $ticket)
                        <td class="ticket-cell">
                            <div class="ticket-card">
                                <div class="qr-box">
                                    <img src="{{ \App\Http\Controllers\QrCodeController::generateBase64($ticket->ticket_code) }}" class="qr-img" alt="QR Code">
                                </div>
                                <div class="ticket-code">{{ $ticket->ticket_code }}</div>
                                <div class="ticket-holder">
                                    {{ $ticket->holder_name ?: ($ticket->order?->customer_name ?? 'PENGUNJUNG') }}
                                </div>
                            </div>
                        </td>
                    @endforeach
                    @for($i = count($row); $i < 3; $i++)
                        <td class="ticket-cell empty-cell"></td>
                    @endfor
                </tr>
            @endforeach
        </table>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>
