<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @php
        $firstTicketItem = $order->items->first(function ($i) {
            $pType = $i->product?->product_type ?? '';
            return in_array($pType, ['ticket', 'tiket', 'tiket event', 'ticket_event']) || !empty($i->product?->event_date);
        }) ?? $order->items->first();

        $product = $firstTicketItem?->product;

        // Foto Landscape (16:9) sesuai Image 2
        $landscapePoster = null;
        if (!empty($product?->og_image)) {
            $landscapePoster = \Illuminate\Support\Str::startsWith($product->og_image, 'http') ? $product->og_image : asset('storage/' . $product->og_image);
        } elseif (!empty($product?->image)) {
            $landscapePoster = \Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image);
        } else {
            $landscapePoster = asset('assets/images/default-ticket.webp');
        }

        // Tanggal Event
        $eventDateStr = 'Sep 5, 2024';
        if (!empty($product?->event_date)) {
            try {
                $eventDateStr = \Carbon\Carbon::parse($product->event_date)->locale('id')->translatedFormat('d M Y');
            } catch (\Exception $e) {
                $eventDateStr = (string)$product->event_date;
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

        // Seat / Kategori
        $seatOrCategory = $firstTicketItem?->variantValue?->name
            ?: ($firstTicketItem?->product?->short_desc ?: 'No seat');

        // Ticket Passes
        $passes = $order->ticketPasses;
        if ($passes->isEmpty()) {
            $passes = collect([(object)[
                'id'          => 1,
                'ticket_code' => $order->order_number,
                'holder_name' => $order->user?->name ?? ($order->customer_name ?: 'Pemegang Tiket'),
                'holder_nik'  => null,
                'status'      => 'valid',
            ]]);
        }

        $activePass = $passes->first();

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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

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
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .tix-page-viewport {
            min-height: 100vh;
            width: 100%;
            background: var(--tix-bg-gradient);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 1.25rem 1rem 3rem;
            box-sizing: border-box;
            position: relative;
        }

        /* Ambient glow & bokeh */
        .tix-page-viewport::before {
            content: '';
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.24) 0%, rgba(255,255,255,0) 70%);
            pointer-events: none;
            z-index: 1;
        }

        .tix-phone-frame {
            width: 100%;
            max-width: 390px;
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
            margin-bottom: 1.25rem;
            padding: 0 0.25rem;
        }

        .tix-nav-btn {
            width: 42px;
            height: 42px;
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
        }
        .tix-nav-btn:hover {
            background: rgba(0, 0, 0, 0.35);
            transform: scale(1.05);
            color: #ffffff;
        }

        .tix-nav-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.3px;
            margin: 0;
        }

        /* ── Ticket Tabs (Multiple Attendees) ── */
        .tix-tabs-wrap {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            overflow-x: auto;
            padding-bottom: 0.65rem;
            margin-bottom: 0.5rem;
            scrollbar-width: none;
        }
        .tix-tabs-wrap::-webkit-scrollbar { display: none; }

        .tix-tab-pill {
            padding: 0.38rem 0.85rem;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(0, 0, 0, 0.16);
            color: #ffffff;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
            font-family: inherit;
        }
        .tix-tab-pill.active {
            background: #ffffff;
            color: #15803d;
            border-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* ── The Ticket Card ── */
        .tix-card-container {
            background: var(--tix-card-bg);
            border-radius: 28px;
            padding: 1.15rem 1.25rem 1.4rem;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.25), 0 4px 16px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        /* ── Cutout Ticket Notches on Left & Right ── */
        .tix-notch-left,
        .tix-notch-right {
            position: absolute;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--tix-bg-gradient);
            z-index: 5;
            top: 55%;
            margin-top: -14px;
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.18);
        }
        .tix-notch-left {
            left: -14px;
        }
        .tix-notch-right {
            right: -14px;
        }

        /* ── 16:9 Landscape Poster ── */
        .tix-poster-box {
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 20px;
            overflow: hidden;
            background: #0f172a;
            position: relative;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        .tix-poster-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* ── Event Title ── */
        .tix-event-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            text-align: center;
            margin: 1.1rem 0 1rem;
            line-height: 1.35;
            letter-spacing: -0.2px;
        }

        /* ── Dashed Divider Line ── */
        .tix-dashed-divider {
            border: none;
            border-top: 2px dashed #cbd5e1;
            margin: 0.25rem 0 1.25rem;
            width: 100%;
        }

        /* ── Details Grid (2x2) ── */
        .tix-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            row-gap: 1.1rem;
            column-gap: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .tix-grid-col.right {
            text-align: right;
        }

        .tix-detail-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--tix-text-muted);
            margin-bottom: 0.25rem;
            letter-spacing: 0.3px;
        }

        .tix-detail-value {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
            word-break: break-word;
        }

        /* ── Attendee Badge (Clean & Subtle) ── */
        .tix-attendee-row {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.5rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            color: #475569;
            margin-bottom: 1.15rem;
        }
        .tix-attendee-name {
            font-weight: 700;
            color: #0f172a;
        }

        /* ── Barcode Section ── */
        .tix-barcode-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-top: 0.25rem;
        }

        .tix-code-text {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
            font-size: 0.76rem;
            color: #64748b;
            letter-spacing: 1px;
            margin-bottom: 0.4rem;
            font-weight: 600;
            text-align: center;
        }

        .tix-barcode-svg-box {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .tix-barcode-svg-box svg {
            max-width: 100%;
            height: 56px;
            display: block;
        }

        /* ── Bottom Floating Controls ── */
        .tix-controls-bar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1.35rem;
            width: 100%;
        }

        /* Save / Download Circle Button */
        .tix-save-btn {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
        }
        .tix-save-btn:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.35);
            color: #ffffff;
        }

        /* Show QR Code Pill Button */
        .tix-qr-pill-btn {
            flex: 1;
            height: 52px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1.5px solid rgba(255, 255, 255, 0.38);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.25rem 0 0.45rem;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: inherit;
        }
        .tix-qr-pill-btn:hover {
            background: rgba(255, 255, 255, 0.32);
            border-color: rgba(255, 255, 255, 0.6);
            transform: translateY(-2px);
            color: #ffffff;
        }

        .tix-qr-icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #d9f99d;
            color: #166534;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }

        .tix-qr-arrow {
            font-size: 0.95rem;
            font-weight: 800;
            opacity: 0.85;
            letter-spacing: -1px;
        }

        /* ── Modal QR Code (Gate Pass) ── */
        .tix-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }
        .tix-modal-backdrop.open {
            opacity: 1;
            pointer-events: auto;
        }

        .tix-modal-card {
            background: #ffffff;
            border-radius: 28px;
            width: 100%;
            max-width: 360px;
            padding: 1.75rem 1.5rem 1.5rem;
            text-align: center;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.35);
            transform: translateY(20px) scale(0.96);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .tix-modal-backdrop.open .tix-modal-card {
            transform: translateY(0) scale(1);
        }

        .tix-modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f1f5f9;
            border: none;
            color: #64748b;
            font-size: 1.2rem;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .tix-modal-qr-box {
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 20px;
            padding: 1rem;
            margin: 1rem auto 1.25rem;
            display: inline-block;
        }
        .tix-modal-qr-box img {
            width: 200px;
            height: 200px;
            display: block;
        }

        /* Toast */
        .tix-toast {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%) translateY(40px);
            background: #0f172a;
            color: #ffffff;
            padding: 0.75rem 1.4rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            z-index: 999999;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .tix-toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    </style>
</head>
<body>

<div class="tix-page-viewport">
    <div class="tix-phone-frame">
        
        {{-- Top Navigation Bar --}}
        <div class="tix-nav-bar">
            <button type="button" class="tix-nav-btn" onclick="handleGoBack()" title="Kembali">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>
            <h1 class="tix-nav-title">Ticket</h1>
            <button type="button" class="tix-nav-btn" onclick="shareTicket()" title="Bagikan Tiket">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="18" cy="5" r="3"/>
                    <circle cx="6" cy="12" r="3"/>
                    <circle cx="18" cy="19" r="3"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
            </button>
        </div>

        {{-- Multiple Attendees Tab (Jika pemesanan lebih dari 1 tiket) --}}
        @if($passes->count() > 1)
            <div class="tix-tabs-wrap">
                @foreach($passes as $idx => $pass)
                    <button type="button" 
                        class="tix-tab-pill {{ $idx === 0 ? 'active' : '' }}" 
                        onclick="switchTicketPass({{ $idx }})">
                        Tiket {{ $idx + 1 }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- The White Ticket Card --}}
        <div class="tix-card-container">
            {{-- Ticket Notches (Cutouts on Left & Right) --}}
            <div class="tix-notch-left"></div>
            <div class="tix-notch-right"></div>

            {{-- Landscape Poster (16:9) --}}
            <div class="tix-poster-box">
                <img src="{{ $landscapePoster }}" alt="{{ $product->name ?? 'Poster Event' }}" loading="lazy">
            </div>

            {{-- Event Title --}}
            <div class="tix-event-title">
                {{ $product->name ?? 'Jazz & Blues Festival' }}
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

                {{-- Seat / Kategori --}}
                <div class="tix-grid-col right">
                    <div class="tix-detail-label">Seat</div>
                    <div class="tix-detail-value" id="tixSeatDisplay">{{ $seatOrCategory }}</div>
                </div>
            </div>

            {{-- Info Pemegang Tiket --}}
            <div class="tix-attendee-row">
                <span>Pemegang:</span>
                <span class="tix-attendee-name" id="tixHolderDisplay">
                    {{ $activePass->holder_name ?? ($order->user?->name ?? 'Pemegang Tiket') }}
                </span>
            </div>

            {{-- Barcode Section --}}
            <div class="tix-barcode-wrap">
                <div class="tix-code-text" id="tixCodeDisplay">
                    {{ $activePass->ticket_code ?? $order->order_number }}
                </div>
                <div class="tix-barcode-svg-box">
                    <svg id="ticketBarcodeSvg"></svg>
                </div>
            </div>
        </div>

        {{-- Bottom Floating Controls --}}
        <div class="tix-controls-bar">
            {{-- Save / Download PDF Button --}}
            <a href="{{ $ticketPdfUrl }}" class="tix-save-btn" title="Simpan / Unduh E-Tiket PDF" target="_blank" download>
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
            </a>

            {{-- Show QR Code Pill Button (Icon Gate Pass / Scan) --}}
            <button type="button" class="tix-qr-pill-btn" onclick="openQrModal()">
                <div style="display:flex; align-items:center; gap:0.65rem;">
                    <div class="tix-qr-icon-circle">
                        {{-- Icon Modern Scan Frame / Gate Pass (Bukan sekadar QR biasa) --}}
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2"/>
                            <path d="M17 3h2a2 2 0 0 1 2 2v2"/>
                            <path d="M21 17v2a2 2 0 0 1-2 2h-2"/>
                            <path d="M7 21H5a2 2 0 0 1-2-2v-2"/>
                            <rect x="7" y="7" width="10" height="10" rx="2" stroke-width="2"/>
                            <path d="M7 12h10" stroke-width="1.8"/>
                        </svg>
                    </div>
                    <span>Tampilkan QR Masuk</span>
                </div>
                <span class="tix-qr-arrow">&gt;&gt;&gt;</span>
            </button>
        </div>

    </div>
</div>

{{-- ── Modal Gate Pass QR Code ── --}}
<div class="tix-modal-backdrop" id="qrModalBackdrop" onclick="closeQrModal(event)">
    <div class="tix-modal-card" onclick="event.stopPropagation()">
        <button type="button" class="tix-modal-close" onclick="closeQrModal()">&times;</button>
        <div style="font-size:1.1rem; font-weight:800; color:#0f172a; margin-bottom:0.25rem;">
            Gate Pass QR Code
        </div>
        <div style="font-size:0.78rem; color:#64748b;">
            Tunjukkan kode ini kepada petugas di gate masuk
        </div>

        <div class="tix-modal-qr-box">
            @php
                $qrBase64 = \App\Services\SimpleQrCode::base64($activePass->ticket_code ?? $order->order_number);
            @endphp
            <img id="tixModalQrImg" src="{{ $qrBase64 }}" alt="Gate QR Code">
        </div>

        <div style="font-family:monospace; font-size:0.88rem; font-weight:800; color:#0f172a; letter-spacing:1px; margin-bottom:0.4rem;" id="tixModalCode">
            {{ $activePass->ticket_code ?? $order->order_number }}
        </div>

        <div style="font-size:0.82rem; color:#475569; margin-bottom:1rem;" id="tixModalHolder">
            {{ $activePass->holder_name ?? ($order->user?->name ?? 'Pemegang Tiket') }}
        </div>

        <div style="display:inline-flex; align-items:center; gap:6px; background:#dcfce7; color:#15803d; font-size:0.75rem; font-weight:700; padding:0.4rem 0.85rem; border-radius:999px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            Siap Dipindai di Gate Event
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div class="tix-toast" id="tixToast">Tautan tiket berhasil disalin!</div>

{{-- JsBarcode Library --}}
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
    // Data Tiket untuk Multi-Pass
    const ticketPasses = @json($passes->map(function($p) use ($order) {
        $code = $p->ticket_code ?? $order->order_number;
        return [
            'id'          => $p->id,
            'ticket_code' => $code,
            'holder_name' => $p->holder_name ?? ($order->user?->name ?? 'Pemegang Tiket'),
            'qr_image'    => \App\Services\SimpleQrCode::base64($code),
        ];
    }));

    let currentPassIndex = 0;

    function renderBarcode(code) {
        try {
            if (window.JsBarcode) {
                JsBarcode("#ticketBarcodeSvg", code, {
                    format: "CODE128",
                    width: 2.1,
                    height: 56,
                    displayValue: false,
                    lineColor: "#0f172a",
                    margin: 0,
                    background: "transparent"
                });
            } else {
                renderFallbackBarcode(code);
            }
        } catch (e) {
            renderFallbackBarcode(code);
        }
    }

    // Fallback Barcode SVG jika CDN offline / terhalang
    function renderFallbackBarcode(code) {
        const svg = document.getElementById('ticketBarcodeSvg');
        if (!svg) return;
        let rects = '';
        let x = 4;
        for (let i = 0; i < code.length; i++) {
            const charCode = code.charCodeAt(i);
            const w1 = (charCode % 3) + 1.8;
            const w2 = ((charCode * 2) % 3) + 1.5;
            rects += `<rect x="${x}" y="0" width="${w1}" height="56" fill="#0f172a" />`;
            x += w1 + 2.2;
            rects += `<rect x="${x}" y="0" width="${w2}" height="56" fill="#0f172a" />`;
            x += w2 + 2.5;
        }
        svg.setAttribute('viewBox', `0 0 ${Math.max(x + 4, 220)} 56`);
        svg.innerHTML = rects;
    }

    function switchTicketPass(index) {
        if (!ticketPasses[index]) return;
        currentPassIndex = index;

        // Update pills
        document.querySelectorAll('.tix-tab-pill').forEach((btn, i) => {
            btn.classList.toggle('active', i === index);
        });

        const pass = ticketPasses[index];
        document.getElementById('tixHolderDisplay').textContent = pass.holder_name;
        document.getElementById('tixCodeDisplay').textContent = pass.ticket_code;
        document.getElementById('tixModalCode').textContent = pass.ticket_code;
        document.getElementById('tixModalHolder').textContent = pass.holder_name;
        document.getElementById('tixModalQrImg').src = pass.qr_image;

        renderBarcode(pass.ticket_code);
    }

    function handleGoBack() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = "{{ route('home') }}";
        }
    }

    function shareTicket() {
        const shareData = {
            title: 'Tiket Event: {{ addslashes($product->name ?? "E-Tiket") }}',
            text: 'Akses e-tiket resmi {{ addslashes($product->name ?? "") }} di buyle.id',
            url: window.location.href
        };

        if (navigator.share && navigator.canShare && navigator.canShare(shareData)) {
            navigator.share(shareData).catch(() => {});
        } else {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showToast('Tautan tiket berhasil disalin!');
                });
            } else {
                showToast('Tautan: ' + window.location.href);
            }
        }
    }

    function showToast(msg) {
        const toast = document.getElementById('tixToast');
        if (!toast) return;
        toast.textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2800);
    }

    function openQrModal() {
        const modal = document.getElementById('qrModalBackdrop');
        if (modal) modal.classList.add('open');
    }

    function closeQrModal(e) {
        if (!e || e.target.id === 'qrModalBackdrop' || e.classList?.contains('tix-modal-close')) {
            const modal = document.getElementById('qrModalBackdrop');
            if (modal) modal.classList.remove('open');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const initialCode = ticketPasses[0]?.ticket_code || '{{ $activePass->ticket_code ?? $order->order_number }}';
        renderBarcode(initialCode);
    });
</script>

</body>
</html>
