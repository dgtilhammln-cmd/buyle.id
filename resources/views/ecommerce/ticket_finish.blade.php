<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    @php
        $firstTicketItem = $order->items->first(function ($i) {
            $pType = $i->product?->product_type ?? '';
            return in_array($pType, ['ticket', 'tiket', 'tiket event', 'ticket_event']) || !empty($i->product?->event_date);
        }) ?? $order->items->first();

        $product = $firstTicketItem?->product;

        // Foto Landscape (16:9)
        $landscapePoster = null;
        if (!empty($product?->og_image)) {
            $landscapePoster = \Illuminate\Support\Str::startsWith($product->og_image, 'http') ? $product->og_image : asset('storage/' . $product->og_image);
        } elseif (!empty($product?->image)) {
            $landscapePoster = \Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image);
        } else {
            $landscapePoster = asset('assets/images/default-ticket.webp');
        }

        // Tanggal Event (Mendukung 1 Hari maupun Berhari-hari / Rentang Tanggal)
        $eventDateStr = 'Sep 5, 2024';
        if (!empty($product?->event_date)) {
            try {
                $start = \Carbon\Carbon::parse($product->event_date)->locale('id');
                $end = !empty($product->event_end_date) ? \Carbon\Carbon::parse($product->event_end_date)->locale('id') : null;

                if ($end && $end->gt($start)) {
                    if ($start->format('Y-m') === $end->format('Y-m')) {
                        // Contoh: 11 - 13 Des 2026
                        $eventDateStr = $start->translatedFormat('d') . ' - ' . $end->translatedFormat('d M Y');
                    } elseif ($start->format('Y') === $end->format('Y')) {
                        // Contoh: 30 Okt - 2 Nov 2026
                        $eventDateStr = $start->translatedFormat('d M') . ' - ' . $end->translatedFormat('d M Y');
                    } else {
                        // Contoh: 31 Des 2026 - 2 Jan 2027
                        $eventDateStr = $start->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y');
                    }
                } else {
                    $eventDateStr = $start->translatedFormat('d M Y');
                }
            } catch (\Exception $e) {
                $eventDateStr = (string) $product->event_date;
            }
        } elseif ($order->created_at) {
            $eventDateStr = $order->created_at->translatedFormat('d M Y');
        }

        // Waktu Event
        $eventTimeStr = '15:00 WIB';
        if (!empty($product?->event_time)) {
            $eventTimeStr = substr(trim($product->event_time), 0, 5) . ' WIB';
        }

        // Lokasi Event
        $eventLocationStr = $product?->event_location ?: ($product?->event_platform ?: 'Venue Ditentukan Panitia');

        // Kategori / Seat
        $seatOrCategory = $firstTicketItem?->variantValue?->name
            ?: ($firstTicketItem?->product?->short_desc ?: 'Reguler');

        // Multi-Pass Support
        $passes = $order->ticketPasses;
        if ($passes->isEmpty()) {
            try {
                \App\Models\TicketPass::generateForOrder($order);
                $passes = $order->fresh(['ticketPasses.product'])->ticketPasses;
            } catch (\Throwable $e) {
            }
        }

        // Fallback jika tetap kosong / tidak ada di database
        $totalTicketQty = $order->items->filter(function ($i) {
            $pType = $i->product?->product_type ?? '';
            return in_array($pType, ['ticket', 'tiket', 'tiket event', 'ticket_event']) || !empty($i->product?->event_date);
        })->sum('qty') ?: ($order->items->sum('qty') ?: 1);

        if ($passes->isEmpty()) {
            $defaultHolder = $order->user?->name ?? ($order->customer_name ?: 'Pemegang Tiket');
            $defaultNik = $order->user?->nik ?? null;
            $mockPasses = [];
            for ($q = 1; $q <= $totalTicketQty; $q++) {
                $code = $totalTicketQty > 1 ? ($order->order_number . '-' . $q) : $order->order_number;
                $mockPasses[] = (object) [
                    'id' => $q,
                    'ticket_code' => $code,
                    'holder_name' => $defaultHolder,
                    'holder_nik' => $defaultNik,
                    'created_at' => $order->created_at,
                    'status' => 'valid',
                ];
            }
            $passes = collect($mockPasses);
        }

        $activePass = $passes->first();

        // Siapkan array data tiket untuk frontend JS
        $passesData = $passes->map(function ($p, $idx) use ($order, $passes, $seatOrCategory) {
            $code = $p->ticket_code ?? ($order->order_number . (count($passes) > 1 ? '-' . ($idx + 1) : ''));
            $rawNik = $p->holder_nik ?: ($order->user?->nik ?: null);
            $maskedNik = \App\Models\TicketPass::maskNik($rawNik);

            $createdAt = $p->created_at ?? $order->created_at;
            $printedAt = $createdAt
                ? \Carbon\Carbon::parse($createdAt)->locale('id')->translatedFormat('d F Y H.i.s')
                : date('d F Y H.i.s');

            $passCat = $p->orderItem?->variantValue?->name
                ?: ($p->product?->short_desc ?: $seatOrCategory);

            return [
                'id' => $p->id ?? ($idx + 1),
                'index' => $idx + 1,
                'ticket_code' => $code,
                'holder_name' => $p->holder_name ?? ($order->user?->name ?? 'Pemegang Tiket'),
                'holder_nik' => $maskedNik,
                'category' => $passCat,
                'qr_image' => \App\Services\SimpleQrCode::base64($code),
                'printed_at' => $printedAt,
            ];
        })->values()->toArray();

        // PDF Download URL
        $ticketPdfUrl = route('orders.ticket.download.public', [
            'order' => $order->id,
            'token' => hash_hmac('sha256', $order->id . '|' . $order->order_number . '|' . ($order->created_at?->timestamp ?? ''), config('app.key'))
        ]);
    @endphp
    <title>E-Tiket: {{ $product->name ?? 'Tiket Event' }} - {{ $order->order_number }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --tix-bg-gradient: linear-gradient(135deg, #1db349 0%, #84ce00 100%);
            --tix-card-bg: #ffffff;
            --tix-text-dark: #0f172a;
            --tix-text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--tix-bg-gradient) !important;
            font-family: 'Montserrat', sans-serif !important;
            color: var(--tix-text-dark);
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .tix-page-viewport {
            min-height: 100vh;
            min-height: 100dvh;
            width: 100%;
            background: var(--tix-bg-gradient);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: max(1.15rem, calc(env(safe-area-inset-top) + 0.45rem));
            padding-bottom: max(1.75rem, calc(env(safe-area-inset-bottom) + 0.75rem));
            padding-left: max(0.85rem, env(safe-area-inset-left));
            padding-right: max(0.85rem, env(safe-area-inset-right));
            box-sizing: border-box;
            position: relative;
        }

        /* Ambient glow */
        .tix-page-viewport::before {
            content: '';
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 500px;
            height: 350px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
            pointer-events: none;
            z-index: 1;
        }

        .tix-phone-frame {
            width: 100%;
            max-width: 385px;
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
        }

        /* ── Top Navigation Bar ── */
        .tix-nav-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 0.65rem;
            padding: 0 0.15rem;
        }

        .tix-nav-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.22);
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0;
            flex-shrink: 0;
        }

        .tix-nav-btn:hover {
            background: rgba(0, 0, 0, 0.35);
            transform: scale(1.04);
            color: #ffffff;
        }

        /* ── Pager Badge (Image 3: 1/1 atau 1/N) ── */
        .tix-pager-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            margin-bottom: 0.6rem;
        }

        .tix-pager-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.26rem 0.95rem;
            background: rgba(0, 0, 0, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            user-select: none;
        }

        .tix-pager-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.28);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
            padding: 0;
        }

        .tix-pager-btn:hover {
            background: rgba(0, 0, 0, 0.4);
            transform: scale(1.08);
        }

        .tix-pager-btn:active {
            transform: scale(0.95);
        }

        /* ── The White Ticket Card ── */
        .tix-card-container {
            background: var(--tix-card-bg);
            border-radius: 24px;
            padding: 0.95rem 1.15rem 1.1rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.22), 0 4px 16px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            user-select: none;
        }

        /* Cutout Ticket Notches */
        .tix-notch-left,
        .tix-notch-right {
            position: absolute;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--tix-bg-gradient);
            z-index: 5;
            top: 48%;
            margin-top: -12px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.16);
        }

        .tix-notch-left {
            left: -12px;
        }

        .tix-notch-right {
            right: -12px;
        }

        /* 16:9 Landscape Poster */
        .tix-poster-box {
            width: 100%;
            aspect-ratio: 16 / 9;
            max-height: 160px;
            border-radius: 16px;
            overflow: hidden;
            background: #0f172a;
            position: relative;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        .tix-poster-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Event Title */
        .tix-event-title {
            font-size: 1.02rem;
            font-weight: 600;
            color: #0f172a;
            text-align: center;
            margin: 0.65rem 0 0.55rem;
            line-height: 1.32;
            letter-spacing: -0.2px;
        }

        /* Dashed Line Divider */
        .tix-dashed-divider {
            border: none;
            border-top: 2px dashed #cbd5e1;
            margin: 0.2rem 0 0.8rem;
            width: 100%;
        }

        /* Details Grid (2x2) */
        .tix-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            row-gap: 0.65rem;
            column-gap: 1.1rem;
            margin-bottom: 0.8rem;
        }

        .tix-grid-col.right {
            text-align: right;
        }

        .tix-detail-label {
            font-size: 0.68rem;
            font-weight: 500;
            color: var(--tix-text-muted);
            margin-bottom: 0.15rem;
            letter-spacing: 0.2px;
        }

        .tix-detail-value {
            font-size: 0.82rem;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.28;
            word-break: break-word;
        }

        /* Info Pemegang & No Identitas (Sensor) */
        .tix-attendee-row {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.42rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 0.8rem;
        }

        .tix-attendee-col {
            display: flex;
            flex-direction: column;
            text-align: left;
            min-width: 0;
        }

        .tix-attendee-col.right {
            text-align: right;
        }

        .tix-attendee-label {
            font-size: 0.64rem;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 1px;
            letter-spacing: 0.15px;
        }

        .tix-attendee-val {
            font-size: 0.76rem;
            font-weight: 600;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            letter-spacing: 0.2px;
        }

        /* ── Image 2: QR Gate Pass Section ── */
        .tix-qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding-top: 0.1rem;
        }

        .tix-qr-title {
            font-size: 0.78rem;
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.45rem;
            letter-spacing: 0.2px;
        }

        .tix-qr-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.45rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            margin-bottom: 0.45rem;
        }

        .tix-qr-box img {
            width: 155px;
            height: 155px;
            display: block;
            image-rendering: pixelated;
        }

        .tix-qr-code-text {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
            font-size: 0.8rem;
            font-weight: 600;
            color: #1e293b;
            letter-spacing: 0.8px;
            margin-bottom: 0.15rem;
        }

        .tix-qr-printed-text {
            font-size: 0.68rem;
            font-weight: 500;
            color: #64748b;
            letter-spacing: 0.15px;
        }

        /* Swipe Hint (hanya jika tiket > 1) */
        .tix-swipe-hint {
            text-align: center;
            font-size: 0.68rem;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 0.65rem;
            font-weight: 500;
            letter-spacing: 0.2px;
        }
    </style>
</head>

<body>

    <div class="tix-page-viewport">
        <div class="tix-phone-frame">

            {{-- Top Navigation Bar --}}
            <div class="tix-nav-bar">
                {{-- Tombol Kiri Atas: Back jika dari menu Akun Saya / Home jika dari Checkout --}}
                @if(request()->is('akun/*') || request()->is('akun'))
                    <a href="{{ route('account.orders') }}" class="tix-nav-btn" title="Kembali ke Riwayat Pesanan">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('tix.index') }}" class="tix-nav-btn" title="Ke Beranda BuyleTIX">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                    </a>
                @endif

                {{-- Logo Resmi BuyleTIX --}}
                <a href="{{ route('tix.index') }}" title="Buyle TIX"
                    style="display:inline-flex; align-items:center; text-decoration:none;">
                    <picture>
                        <source srcset="{{ asset('images/logobuyletix.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logobuyletix.png') }}" alt="Buyle TIX"
                            style="height:28px; width:auto; max-width:140px; object-fit:contain; display:block;">
                    </picture>
                </a>

                {{-- Tombol Download Pojok Kanan Atas (Only Icon) --}}
                <a href="{{ $ticketPdfUrl }}" class="tix-nav-btn" title="Unduh E-Tiket PDF" target="_blank" download>
                    <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2.2"
                        viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                </a>
            </div>

            {{-- Pager Counter 1/1 atau 1/N (Image 3) --}}
            <div class="tix-pager-bar">
                @if(count($passesData) > 1)
                    <button type="button" class="tix-pager-btn" onclick="prevTicketPass()" title="Tiket Sebelumnya"
                        aria-label="Tiket Sebelumnya">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                    </button>
                @endif

                <div class="tix-pager-pill">
                    <span id="tixCurrentIndexDisplay">1</span> / <span
                        id="tixTotalCountDisplay">{{ count($passesData) }}</span>
                </div>

                @if(count($passesData) > 1)
                    <button type="button" class="tix-pager-btn" onclick="nextTicketPass()" title="Tiket Selanjutnya"
                        aria-label="Tiket Selanjutnya">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </button>
                @endif
            </div>

            {{-- The White Ticket Card --}}
            <div class="tix-card-container" id="tixCardContainer">
                {{-- Ticket Notches (Cutouts) --}}
                <div class="tix-notch-left"></div>
                <div class="tix-notch-right"></div>

                {{-- Landscape Poster (16:9) --}}
                <div class="tix-poster-box">
                    <img src="{{ $landscapePoster }}" alt="{{ $product->name ?? 'Poster Event' }}" loading="eager">
                </div>

                {{-- Event Title --}}
                <div class="tix-event-title">
                    {{ $product->name ?? 'Tiket Event' }}
                </div>

                {{-- Dashed Line Divider --}}
                <hr class="tix-dashed-divider">

                {{-- Details Grid (2x2) --}}
                <div class="tix-details-grid">
                    {{-- Date --}}
                    <div class="tix-grid-col">
                        <div class="tix-detail-label">Date</div>
                        <div class="tix-detail-value">{{ $eventDateStr }}</div>
                    </div>

                    {{-- Time --}}
                    <div class="tix-grid-col right">
                        <div class="tix-detail-label">Time</div>
                        <div class="tix-detail-value">{{ $eventTimeStr }}</div>
                    </div>

                    {{-- Location --}}
                    <div class="tix-grid-col">
                        <div class="tix-detail-label">Location</div>
                        <div class="tix-detail-value">{{ $eventLocationStr }}</div>
                    </div>

                    {{-- Kategori --}}
                    <div class="tix-grid-col right">
                        <div class="tix-detail-label">Kategori</div>
                        <div class="tix-detail-value" id="tixSeatDisplay">
                            {{ $passesData[0]['category'] ?? $seatOrCategory }}</div>
                    </div>
                </div>

                {{-- Info Pemegang Tiket (Kiri) & No Identitas Sensor (Kanan) --}}
                <div class="tix-attendee-row">
                    <div class="tix-attendee-col">
                        <span class="tix-attendee-label">Pemegang:</span>
                        <span class="tix-attendee-val" id="tixHolderDisplay">
                            {{ $passesData[0]['holder_name'] ?? 'Pemegang Tiket' }}
                        </span>
                    </div>
                    <div class="tix-attendee-col right">
                        <span class="tix-attendee-label">No. Identitas:</span>
                        <span class="tix-attendee-val" id="tixNikDisplay">
                            {{ $passesData[0]['holder_nik'] ?? '—' }}
                        </span>
                    </div>
                </div>

                {{-- Image 2: Pindai kode ini di Gerbang + QR Code + Code + Dicetak --}}
                <div class="tix-qr-section">
                    <div class="tix-qr-title">Simpan & Tunjukan saat di Gate</div>
                    <div class="tix-qr-box">
                        <img id="tixQrImageDisplay" src="{{ $passesData[0]['qr_image'] ?? '' }}"
                            alt="QR Code Gate Pass">
                    </div>
                    <div class="tix-qr-code-text" id="tixCodeDisplay">
                        {{ $passesData[0]['ticket_code'] ?? $order->order_number }}
                    </div>
                    <div class="tix-qr-printed-text" id="tixPrintDateDisplay">
                        Dicetak : {{ $passesData[0]['printed_at'] ?? date('d F Y H.i.s') }}
                    </div>
                </div>
            </div>

            @if(count($passesData) > 1)
                <div class="tix-swipe-hint">
                    Geser tiket atau ketuk panah untuk melihat tiket lainnya
                </div>
            @endif

        </div>
    </div>

    <script>
        // Data Tiket untuk Multi-Pass
        const ticketPasses = @json($passesData);
        let currentPassIndex = 0;

        function updateTicketPass(index) {
            if (!ticketPasses || !ticketPasses[index]) return;
            currentPassIndex = index;
            const pass = ticketPasses[index];

            // Update Counter Pager "1 / N"
            const currEl = document.getElementById('tixCurrentIndexDisplay');
            if (currEl) currEl.textContent = (index + 1);

            // Update Holder Name
            const holderEl = document.getElementById('tixHolderDisplay');
            if (holderEl) holderEl.textContent = pass.holder_name;

            // Update Masked NIK
            const nikEl = document.getElementById('tixNikDisplay');
            if (nikEl) nikEl.textContent = pass.holder_nik || '—';

            // Update Kategori
            const catEl = document.getElementById('tixSeatDisplay');
            if (catEl && pass.category) catEl.textContent = pass.category;

            // Update QR Image
            const qrEl = document.getElementById('tixQrImageDisplay');
            if (qrEl && pass.qr_image) qrEl.src = pass.qr_image;

            // Update Ticket Code Text
            const codeEl = document.getElementById('tixCodeDisplay');
            if (codeEl) codeEl.textContent = pass.ticket_code;

            // Update Printed Date
            const printEl = document.getElementById('tixPrintDateDisplay');
            if (printEl && pass.printed_at) printEl.textContent = 'Dicetak : ' + pass.printed_at;
        }

        function prevTicketPass() {
            if (!ticketPasses || ticketPasses.length <= 1) return;
            const newIdx = (currentPassIndex - 1 + ticketPasses.length) % ticketPasses.length;
            updateTicketPass(newIdx);
        }

        function nextTicketPass() {
            if (!ticketPasses || ticketPasses.length <= 1) return;
            const newIdx = (currentPassIndex + 1) % ticketPasses.length;
            updateTicketPass(newIdx);
        }

        // Touch Swipe Gesture pada Ticket Card
        let touchStartX = 0;
        let touchEndX = 0;
        const cardContainer = document.getElementById('tixCardContainer');
        if (cardContainer) {
            cardContainer.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            cardContainer.addEventListener('touchend', function (e) {
                touchEndX = e.changedTouches[0].screenX;
                handleCardSwipe();
            }, { passive: true });
        }

        function handleCardSwipe() {
            if (!ticketPasses || ticketPasses.length <= 1) return;
            const diff = touchEndX - touchStartX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) {
                    nextTicketPass();
                } else {
                    prevTicketPass();
                }
            }
        }
    </script>

</body>

</html>