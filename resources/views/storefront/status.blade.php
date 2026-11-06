<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Status Pesanan #{{ $order->order_number }} – {{ $storeName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap"
        rel="stylesheet">
    @if(!empty($snapUrl) && !empty($midtransClientKey))
        <script src="{{ $snapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    @endif
    <style>
        :root {
            --store-accent:
                {{ $posAccentColor }}
            ;
            --safe-area-top: env(safe-area-inset-top, 0px);
            --safe-area-bottom: env(safe-area-inset-bottom, 0px);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            justify-content: center;
        }

        .status-canvas {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            min-height: 100vh;
            padding: max(1.25rem, calc(var(--safe-area-top) + 0.75rem)) 1.25rem max(1.5rem, calc(var(--safe-area-bottom) + 1rem)) 1.25rem;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 35px rgba(0, 0, 0, 0.05);
        }

        /* ── Jangan Close Banner ── */
        .stay-notice-box {
            background: #eff6ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 14px;
            padding: 0.75rem 0.9rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            color: #1e40af;
            font-size: 0.75rem;
            line-height: 1.45;
        }

        .stay-notice-box svg {
            flex-shrink: 0;
            margin-top: 1px;
            color: #2563eb;
        }

        /* ── Cashier Warning Box (Wajib Sekarang ke Kasir) ── */
        .cashier-warning-box {
            background: #fff7ed;
            border: 1.5px solid #fed7aa;
            border-radius: 14px;
            padding: 0.85rem 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.06);
        }

        .cw-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffedd5;
            color: #c2410c;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .cw-title {
            font-size: 0.85rem;
            font-weight: 800;
            color: #9a3412;
            margin-bottom: 3px;
        }

        .cw-desc {
            font-size: 0.74rem;
            color: #c2410c;
            line-height: 1.45;
        }

        /* ── Status Header ── */
        .status-badge-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem auto;
            transition: all 0.3s ease;
        }

        .circle-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .circle-confirmed {
            background: #dcfce7;
            color: #16a34a;
        }

        .circle-completed {
            background: #16a34a;
            color: #ffffff;
            box-shadow: 0 0 0 8px rgba(22, 163, 74, 0.15);
            animation: pulseSuccess 2s infinite;
        }

        @keyframes pulseSuccess {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.04);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        .status-title {
            font-size: 1.25rem;
            font-weight: 800;
            text-align: center;
            color: #0f172a;
            margin-bottom: 0.35rem;
        }

        .status-sub {
            font-size: 0.8rem;
            color: #64748b;
            text-align: center;
            line-height: 1.5;
            margin-bottom: 1.25rem;
        }

        /* ── Order Card ── */
        .order-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.1rem;
            margin-bottom: 1rem;
        }

        .order-card-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .order-label {
            color: #64748b;
            font-weight: 500;
        }

        .order-val {
            color: #0f172a;
            font-weight: 700;
            text-align: right;
        }

        /* ── Struk / Items Breakdown ── */
        .items-box {
            border-top: 1px dashed #cbd5e1;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
        }

        .breakdown-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.78rem;
            margin-bottom: 5px;
            color: #475569;
        }

        .breakdown-row.total-row {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1.5px dashed #cbd5e1;
        }

        /* ── Switch Payment Button ── */
        .switch-pay-wrap {
            margin-bottom: 1rem;
        }

        .btn-switch-pay {
            width: 100%;
            padding: 0.75rem 0.9rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.15s;
            font-family: inherit;
        }

        .btn-switch-to-digital {
            background: #ffffff;
            color: #0284c7;
            border: 1.5px solid #bae6fd;
        }

        .btn-switch-to-digital:hover {
            background: #f0f9ff;
        }

        .btn-switch-to-cash {
            background: #ffffff;
            color: #b45309;
            border: 1.5px solid #fde68a;
        }

        .btn-switch-to-cash:hover {
            background: #fefce8;
        }

        /* ── Action Buttons ── */
        .btn-action-pill {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 0.85rem;
            border-radius: 999px;
            font-size: 0.86rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-family: inherit;
            margin-bottom: 0.5rem;
            transition: all 0.15s;
        }

        .btn-download-receipt {
            background: #0f172a;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.2);
        }

        .btn-primary {
            background: #16a34a;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(22, 163, 74, 0.25);
        }

        .btn-outline {
            background: #ffffff;
            color: #475569;
            border: 1.5px solid #cbd5e1;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.9) translateY(12px);
                opacity: 0;
            }

            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        @keyframes shake {
            0% {
                transform: rotate(0deg);
            }

            20% {
                transform: rotate(-14deg);
            }

            40% {
                transform: rotate(14deg);
            }

            60% {
                transform: rotate(-8deg);
            }

            80% {
                transform: rotate(8deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }
    </style>
</head>

<body>
    @php
        $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
        $orderType = $addr['order_type'] ?? 'dine_in';
        $tableNo = $addr['table_number'] ?? ($orderType === 'takeaway' ? 'Takeaway' : 'Umum');
        $paymentMethod = $addr['payment_method'] ?? 'cash';
        $statusVal = strtolower(is_object($order->status) ? $order->status->value : (string) $order->status);
        $payStat = strtolower($order->payment?->status?->value ?? (string) ($order->payment?->status ?? ''));
        $isPaid = in_array($statusVal, ['confirmed', 'delivered', 'processing', 'completed', 'paid']) || in_array($payStat, ['success', 'paid']);
        $isCompleted = in_array($statusVal, ['delivered', 'completed']);
        $feesBreakdown = $addr['fees_breakdown'] ?? [];
        $discountDetails = $addr['discount_details'] ?? null;
    @endphp

    <div class="status-canvas">
        {{-- Store Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
            <div style="display:flex; align-items:center; gap:0.6rem; min-width:0;">
                @if($posLogoUrl)
                    <img src="{{ $posLogoUrl }}" alt="{{ $storeName }}"
                        style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:1px solid #e2e8f0; flex-shrink:0;">
                @endif
                <div
                    style="font-size:0.88rem; font-weight:800; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $storeName }}</div>
            </div>
            <div
                style="font-size:0.65rem; color:#94a3b8; white-space:nowrap; flex-shrink:0; display:flex; align-items:center; gap:4px;">
                <span
                    style="width:6px; height:6px; border-radius:50%; background:#22c55e; display:inline-block; animation:pulse-dot 1.5s infinite;"></span>
                Live Update
            </div>
        </div>

        {{-- Status Circle Badge --}}
        <div class="status-badge-circle {{ $isCompleted ? 'circle-completed' : ($isPaid ? 'circle-confirmed' : ($paymentMethod !== 'cash' ? 'circle-pending' : 'circle-pending')) }}"
            id="statusBadgeCircle"
            style="{{ !$isPaid && $paymentMethod !== 'cash' ? 'background:#fef3c7; color:#d97706; border-color:#fde68a;' : '' }}">
            @if($isCompleted)
                {{-- Centang 2 --}}
                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12" />
                    <polyline points="20 10 9 21 4 16" style="opacity:0.6;" />
                </svg>
            @elseif($isPaid)
                {{-- Ceklis 1 --}}
                <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            @elseif($paymentMethod !== 'cash')
                {{-- Dompet / Waiting Digital --}}
                <svg width="30" height="30" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="2" y="6" width="20" height="14" rx="2" />
                    <path d="M16 11h.01" />
                    <circle cx="12" cy="12" r="2" />
                    <path d="M6 11h.01M18 11h.01" />
                </svg>
            @else
                {{-- Jam pasir / cash pending --}}
                <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
            @endif
        </div>

        <h1 class="status-title" id="statusTitle">
            @if($isCompleted)
                Pesanan Sudah Selesai!
            @elseif($isPaid)
                Pesanan Terkonfirmasi
            @elseif($paymentMethod !== 'cash')
                Menunggu Pembayaran Digital
            @else
                Pesanan Diterima
            @endif
        </h1>

        <p class="status-sub" id="statusSub">
            @if($isCompleted)
                Pesanan Anda telah selesai dipersiapkan dan siap dinikmati / disajikan ke meja Anda!
            @elseif($isPaid)
                Pembayaran telah diterima. Dapur sedang menyiapkan pesanan makanan &amp; minuman Anda.
            @elseif($paymentMethod !== 'cash')
                Pesanan sudah tercatat. Silakan selesaikan pembayaran QRIS / E-Wallet / VA untuk mulai diproses.
            @else
                Pesanan masuk ke kasir. Silakan bayar tunai di meja kasir sekarang agar segera dimasak.
            @endif
        </p>

        {{-- Tombol Bayar Sekarang khusus digital belum lunas --}}
        @if(!$isPaid && $paymentMethod !== 'cash')
            <div id="digitalPayNowWrap" style="margin-bottom:1rem;">
                <button type="button" onclick="triggerSnapPay()" id="btnPayNowSnap"
                    style="display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:0.9rem; border-radius:999px; background:linear-gradient(135deg,#1db349,#a5cf36); color:#fff; font-size:0.88rem; font-weight:800; border:none; cursor:pointer; font-family:inherit; box-shadow:0 4px 16px rgba(29,179,73,0.3); margin-bottom:0.5rem;">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    Bayar Sekarang (Buka Pembayaran)
                </button>
            </div>
        @endif

        {{-- Cashier Notice jika Cash & belum bayar --}}
        @if(!$isPaid && $paymentMethod === 'cash')
            <div class="cashier-warning-box" id="cashierWarningBox">
                <div class="cw-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="12" cy="12" r="2.5" />
                        <path d="M6 12h.01M18 12h.01" />
                    </svg>
                </div>
                <div>
                    <div class="cw-title">Wajib Sekarang ke Kasir</div>
                    <div class="cw-desc">
                        Silakan segera ke kasir dan sebutkan <strong>#{{ $order->order_number }}</strong>
                        @if($orderType !== 'takeaway') ({{ $tableNo }})@endif.
                        Pesanan baru akan diproses setelah pembayaran diterima kasir.
                    </div>
                </div>
            </div>
        @endif

        {{-- Tombol Ganti Metode Pembayaran (Hanya jika belum lunas) --}}
        @if(!$isPaid)
            <div class="switch-pay-wrap" id="switchPayWrap">
                @if($paymentMethod === 'cash')
                    <button type="button" class="btn-switch-pay btn-switch-to-digital" onclick="switchPayment('midtrans')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="5" width="18" height="14" rx="2" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span>Ganti ke Pembayaran Digital (QRIS / GoPay / VA)</span>
                    </button>
                @else
                    <button type="button" class="btn-switch-pay btn-switch-to-cash" onclick="switchPayment('cash')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <circle cx="12" cy="12" r="2" />
                        </svg>
                        <span>Ganti ke Bayar di Kasir (Cash)</span>
                    </button>
                @endif
            </div>
        @endif

        {{-- Kartu Rincian Struk Resmi --}}
        <div class="order-card" id="receiptPrintArea">
            <div class="order-card-row">
                <span class="order-label">Nomor Pesanan</span>
                <span class="order-val">#{{ $order->order_number }}</span>
            </div>
            <div class="order-card-row">
                <span class="order-label">Tipe Pesanan</span>
                <span
                    class="order-val">{{ $orderType === 'takeaway' ? 'Takeaway (Bawa Pulang)' : 'Dine In (Makan di Tempat)' }}</span>
            </div>
            @if($orderType !== 'takeaway')
                <div class="order-card-row">
                    <span class="order-label">Nomor Meja</span>
                    <span class="order-val">{{ $tableNo }}</span>
                </div>
            @endif
            <div class="order-card-row">
                <span class="order-label">Pemesan</span>
                <span class="order-val">{{ $addr['name'] ?? 'Pelanggan' }}</span>
            </div>
            <div class="order-card-row">
                <span class="order-label">Metode Bayar</span>
                <span class="order-val"
                    id="dispPayMethod">{{ $paymentMethod === 'cash' ? 'Bayar di Kasir (Cash)' : 'Digital (Snap Midtrans)' }}</span>
            </div>
            <div class="order-card-row">
                <span class="order-label">Status</span>
                <span class="order-val" id="dispStatus"
                    style="color: {{ $isCompleted ? '#16a34a' : ($isPaid ? '#16a34a' : '#d97706') }}; font-weight:800;">
                    @if($isCompleted)
                        Selesai (Completed)
                    @elseif($isPaid)
                        Lunas / Sedang Diproses
                    @else
                        Menunggu Pembayaran Kasir
                    @endif
                </span>
            </div>

            {{-- Breakdown Item & Pajak --}}
            <div class="items-box">
                <div
                    style="font-size:0.75rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.5rem;">
                    Rincian Menu &amp; Pajak
                </div>

                @foreach($order->items as $it)
                    <div class="breakdown-row">
                        <span>{{ $it->qty }}&times; {{ $it->product_name }}</span>
                        <span style="font-weight:600;">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach

                <div class="breakdown-row"
                    style="margin-top:6px; padding-top:6px; border-top:1px dashed #e2e8f0; color:#64748b;">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>

                @if($order->discount > 0)
                    <div class="breakdown-row" style="color:#16a34a; font-weight:600;">
                        <span>Diskon
                            {{ !empty($discountDetails['name']) ? '(' . $discountDetails['name'] . ')' : '' }}</span>
                        <span>-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                @endif

                {{-- Pajak & Biaya POS Toko Lengkap --}}
                @if(!empty($feesBreakdown) && is_array($feesBreakdown))
                    @foreach($feesBreakdown as $fb)
                        <div class="breakdown-row" style="color:#64748b;">
                            <span>{{ $fb['name'] ?? 'Pajak & Biaya' }}</span>
                            <span>Rp {{ number_format($fb['amount'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                @elseif(!empty($addr['service_fee']) && $addr['service_fee'] > 0)
                    <div class="breakdown-row" style="color:#64748b;">
                        <span>Pajak &amp; Biaya Layanan</span>
                        <span>Rp {{ number_format($addr['service_fee'], 0, ',', '.') }}</span>
                    </div>
                @endif

                @if($order->platform_fee > 0 || $order->admin_fee > 0)
                    <div class="breakdown-row" style="color:#64748b;">
                        <span>Biaya Transaksi Digital</span>
                        <span>Rp {{ number_format($order->platform_fee + $order->admin_fee, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="breakdown-row total-row">
                    <span>Total Pembayaran</span>
                    <span style="color:#16a34a;">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="margin-top:auto; padding-top:1rem;">
            {{-- Tombol Unduh Struk Digital --}}
            <button type="button" onclick="downloadCustomerReceipt()" class="btn-action-pill btn-download-receipt">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                <span>Unduh Struk</span>
            </button>

            <a href="{{ route('storefront.show', ['slug' => $slug]) }}" class="btn-action-pill btn-primary">
                Pesan Menu Lainnya
            </a>

            @if($isCompleted)
                <button type="button" onclick="start30sAlarmAndVibration()" class="btn-action-pill btn-outline"
                    style="border-color:#bbf7d0; background:#f0fdf4; color:#166534; font-weight:700;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"
                        style="margin-right:6px;">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    <span>Bunyikan Alarm &amp; Getar (30d)</span>
                </button>
            @endif

            <button type="button" onclick="location.reload()" class="btn-action-pill btn-outline">
                Perbarui Status
            </button>
        </div>
    </div>

    <script>
        const storeSlug = '{{ $slug }}';
        const orderId = {{ $order->id }};
        const orderNumber = '{{ $order->order_number }}';
        const storeName = {!! json_encode($storeName) !!};
        const orderTotal = {{ (float) $order->total }};
        const tableNumber = {!! json_encode($tableNo) !!};
        const orderTypeLabel = {!! json_encode($orderType === 'takeaway' ? 'Takeaway' : 'Dine In') !!};
        const itemsList = {!! json_encode($order->items->map(fn($it) => ['name' => $it->product_name, 'qty' => $it->qty, 'subtotal' => (float) $it->subtotal])) !!};
        const feesList = {!! json_encode($feesBreakdown) !!};
        const discountVal = {{ (float) $order->discount }};
        const subtotalVal = {{ (float) $order->subtotal }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        let isCompletedState = {{ $isCompleted ? 'true' : 'false' }};
        let isPaidState = {{ $isPaid ? 'true' : 'false' }};

        // Trigger Snap langsung (untuk tombol Bayar Sekarang di halaman digital unpaid)
        function triggerSnapPay() {
            const btn = document.getElementById('btnPayNowSnap');
            if (btn) { btn.disabled = true; btn.innerText = 'Membuka Pembayaran...'; }
            switchPayment('midtrans');
        }

        // Audio & Vibration Alarm Manager (Melodic Chime & Looping Haptic)
        let alarmInterval = null;
        let alarmTimeout = null;
        let audioCtx = null;

        function getAudioContext() {
            if (!audioCtx) {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (AudioCtx) audioCtx = new AudioCtx();
            }
            if (audioCtx && audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            return audioCtx;
        }

        // Pre-unlock audio on user gesture
        ['click', 'touchstart', 'scroll', 'keydown'].forEach(evt => {
            window.addEventListener(evt, () => { getAudioContext(); }, { once: true, passive: true });
        });

        function playBeepChime(isDone = false) {
            try {
                const ctx = getAudioContext();
                if (!ctx) return;
                const now = ctx.currentTime;

                if (isDone) {
                    // Melodic chime ring (C5 - E5 - G5 - C6)
                    const notes = [523.25, 659.25, 783.99, 1046.50];
                    notes.forEach((freq, idx) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(freq, now + idx * 0.12);
                        gain.gain.setValueAtTime(0.4, now + idx * 0.12);
                        gain.gain.exponentialRampToValueAtTime(0.001, now + idx * 0.12 + 0.35);
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.start(now + idx * 0.12);
                        osc.stop(now + idx * 0.12 + 0.38);
                    });
                } else {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, now);
                    osc.frequency.setValueAtTime(1100, now + 0.12);
                    gain.gain.setValueAtTime(0.3, now);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now);
                    osc.stop(now + 0.5);
                }
            } catch (e) { }
        }

        // Getar HP (Vibration API)
        function triggerPhoneVibration() {
            if ('vibrate' in navigator) {
                try {
                    navigator.vibrate([400, 200, 400, 200, 600]);
                } catch (e) { }
            }
        }

        // Alarm 30 Detik (Audio Chime + Getar Berulang)
        function start30sAlarmAndVibration() {
            stop30sAlarm();

            const modal = document.getElementById('orderReadyModal');
            if (modal) modal.style.display = 'flex';

            playBeepChime(true);
            triggerPhoneVibration();

            // Ulangi nada & getaran setiap 2.5 detik selama 30 detik
            alarmInterval = setInterval(() => {
                playBeepChime(true);
                triggerPhoneVibration();
            }, 2500);

            // Berhenti otomatis setelah 30 detik
            alarmTimeout = setTimeout(() => {
                stop30sAlarm();
            }, 30000);
        }

        function stop30sAlarm() {
            if (alarmInterval) {
                clearInterval(alarmInterval);
                alarmInterval = null;
            }
            if (alarmTimeout) {
                clearTimeout(alarmTimeout);
                alarmTimeout = null;
            }
            if ('vibrate' in navigator) {
                try { navigator.vibrate(0); } catch (e) { }
            }
            const modal = document.getElementById('orderReadyModal');
            if (modal) modal.style.display = 'none';

            localStorage.setItem('order_ready_alarm_done_' + orderId, '1');
        }

        // Realtime Status Poller
        function pollCustomerOrderStatus() {
            fetch(`/s/${storeSlug}/order/${orderId}/check-status`, {
                headers: { 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;

                    // 1. Cek jika baru saja Lunas
                    if (data.is_paid && !isPaidState) {
                        isPaidState = true;
                        playBeepChime(false);
                        document.getElementById('cashierWarningBox')?.remove();
                        document.getElementById('switchPayWrap')?.remove();
                        const badge = document.getElementById('statusBadgeCircle');
                        if (badge) {
                            badge.className = 'status-badge-circle circle-confirmed';
                            badge.innerHTML = '<svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>';
                        }
                        document.getElementById('statusTitle').innerText = 'Pesanan Terkonfirmasi';
                        document.getElementById('statusSub').innerText = 'Pembayaran telah diterima kasir. Dapur sedang menyiapkan pesanan Anda.';
                        const dispStat = document.getElementById('dispStatus');
                        if (dispStat) {
                            dispStat.innerText = 'Lunas / Sedang Diproses';
                            dispStat.style.color = '#16a34a';
                        }
                    }

                    // 2. Cek jika baru saja Selesai (Completed)
                    if (data.is_completed && !isCompletedState) {
                        isCompletedState = true;
                        isPaidState = true;
                        start30sAlarmAndVibration();

                        const badge = document.getElementById('statusBadgeCircle');
                        if (badge) {
                            badge.className = 'status-badge-circle circle-completed';
                            badge.innerHTML = '<svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/><polyline points="20 10 9 21 4 16" style="opacity:0.6;"/></svg>';
                        }
                        document.getElementById('statusTitle').innerText = 'Pesanan Sudah Selesai!';
                        document.getElementById('statusSub').innerText = 'Pesanan Anda telah selesai dipersiapkan dan siap dinikmati / disajikan ke meja Anda!';
                        const dispStat = document.getElementById('dispStatus');
                        if (dispStat) {
                            dispStat.innerText = 'Selesai (Completed)';
                            dispStat.style.color = '#16a34a';
                        }
                    }
                })
                .catch(() => { })
                .finally(() => {
                    if (!isCompletedState) {
                        setTimeout(pollCustomerOrderStatus, 2800);
                    }
                });
        }

        setTimeout(pollCustomerOrderStatus, 2800);

        document.addEventListener('DOMContentLoaded', function () {
            if (isCompletedState && !localStorage.getItem('order_ready_alarm_done_' + orderId)) {
                setTimeout(start30sAlarmAndVibration, 600);
            }
        });

        // Ganti Metode Pembayaran (Cash <-> Midtrans Snap)
        function switchPayment(targetMethod) {
            const btn = event.currentTarget;
            if (btn) btn.disabled = true;

            fetch(`/s/${storeSlug}/order/${orderId}/switch-payment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ target_method: targetMethod })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (targetMethod === 'midtrans' && data.snap_token) {
                            if (typeof snap !== 'undefined') {
                                snap.pay(data.snap_token, {
                                    onSuccess: function (result) {
                                        alert('Pembayaran digital berhasil!');
                                        location.reload();
                                    },
                                    onPending: function (result) {
                                        alert('Menunggu pembayaran diselesaikan.');
                                        location.reload();
                                    },
                                    onError: function (result) {
                                        // Jangan reload — cukup tampilkan pesan, status tetap Menunggu Pembayaran Digital
                                        if (btn) btn.disabled = false;
                                    },
                                    onClose: function () {
                                        // Snap ditutup tanpa bayar — reload tidak akan salah tampilkan "Pesanan Diterima"
                                        // karena status.blade.php sekarang membedakan digital unpaid dengan benar
                                        if (btn) btn.disabled = false;
                                    }
                                });
                            } else {
                                alert(data.message);
                                location.reload();
                            }
                        } else {
                            alert(data.message);
                            location.reload();
                        }
                    } else {
                        alert(data.message || 'Gagal mengubah metode pembayaran.');
                        if (btn) btn.disabled = false;
                    }
                })
                .catch(() => {
                    alert('Terjadi kesalahan koneksi.');
                    if (btn) btn.disabled = false;
                });
        }

        // Unduh Struk Digital (HTML5 Canvas PNG)
        function downloadCustomerReceipt() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            canvas.width = 650;
            const rowHeight = 30;
            const baseHeight = 560;
            canvas.height = baseHeight + (itemsList.length * rowHeight) + (feesList.length * 28);

            // Background
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Accent Top Bar
            ctx.fillStyle = '#16a34a';
            ctx.fillRect(0, 0, canvas.width, 16);

            // Store Name Header
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 28px Montserrat, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(storeName.toUpperCase(), canvas.width / 2, 70);

            ctx.fillStyle = '#64748b';
            ctx.font = '500 14px Montserrat, sans-serif';
            ctx.fillText('STRUK PESANAN DIGITAL RESMI', canvas.width / 2, 100);

            // Divider Dashed
            ctx.strokeStyle = '#cbd5e1';
            ctx.lineWidth = 2;
            ctx.setLineDash([6, 6]);
            ctx.beginPath();
            ctx.moveTo(35, 125);
            ctx.lineTo(canvas.width - 35, 125);
            ctx.stroke();
            ctx.setLineDash([]);

            // Meta Details
            ctx.textAlign = 'left';
            ctx.font = '600 14px Montserrat, sans-serif';
            ctx.fillStyle = '#475569';
            ctx.fillText('No. Pesanan  : #' + orderNumber, 40, 160);
            ctx.fillText('Waktu        : ' + new Date().toLocaleString('id-ID'), 40, 188);
            ctx.fillText('Tipe Pesanan : ' + orderTypeLabel + ' (' + tableNumber + ')', 40, 216);

            // Divider Solid
            ctx.strokeStyle = '#e2e8f0';
            ctx.lineWidth = 1.5;
            ctx.beginPath();
            ctx.moveTo(35, 235);
            ctx.lineTo(canvas.width - 35, 235);
            ctx.stroke();

            // Menu Items List
            let curY = 270;
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 15px Montserrat, sans-serif';
            ctx.fillText('MENU', 40, curY);
            ctx.textAlign = 'right';
            ctx.fillText('HARGA', canvas.width - 40, curY);

            curY += 24;
            itemsList.forEach(it => {
                ctx.textAlign = 'left';
                ctx.font = '500 14px Montserrat, sans-serif';
                ctx.fillStyle = '#334155';
                ctx.fillText(it.qty + 'x ' + it.name, 40, curY);

                ctx.textAlign = 'right';
                ctx.font = '600 14px Montserrat, sans-serif';
                ctx.fillText('Rp ' + it.subtotal.toLocaleString('id-ID'), canvas.width - 40, curY);
                curY += 28;
            });

            // Divider Dashed
            curY += 10;
            ctx.strokeStyle = '#cbd5e1';
            ctx.setLineDash([5, 5]);
            ctx.beginPath();
            ctx.moveTo(35, curY);
            ctx.lineTo(canvas.width - 35, curY);
            ctx.stroke();
            ctx.setLineDash([]);

            // Subtotal
            curY += 30;
            ctx.textAlign = 'left';
            ctx.fillStyle = '#64748b';
            ctx.font = '500 14px Montserrat, sans-serif';
            ctx.fillText('Subtotal', 40, curY);
            ctx.textAlign = 'right';
            ctx.font = '600 14px Montserrat, sans-serif';
            ctx.fillText('Rp ' + subtotalVal.toLocaleString('id-ID'), canvas.width - 40, curY);

            // Discount
            if (discountVal > 0) {
                curY += 26;
                ctx.textAlign = 'left';
                ctx.fillStyle = '#16a34a';
                ctx.fillText('Diskon', 40, curY);
                ctx.textAlign = 'right';
                ctx.fillText('-Rp ' + discountVal.toLocaleString('id-ID'), canvas.width - 40, curY);
            }

            // Taxes & Fees
            feesList.forEach(f => {
                curY += 26;
                ctx.textAlign = 'left';
                ctx.fillStyle = '#64748b';
                ctx.fillText(f.name || 'Pajak & Biaya', 40, curY);
                ctx.textAlign = 'right';
                ctx.fillText('Rp ' + (parseFloat(f.amount) || 0).toLocaleString('id-ID'), canvas.width - 40, curY);
            });

            // Grand Total
            curY += 34;
            ctx.fillStyle = '#f8fafc';
            ctx.fillRect(35, curY - 24, canvas.width - 70, 44);
            ctx.strokeStyle = '#16a34a';
            ctx.strokeRect(35, curY - 24, canvas.width - 70, 44);

            ctx.textAlign = 'left';
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 16px Montserrat, sans-serif';
            ctx.fillText('TOTAL PEMBAYARAN', 50, curY + 4);

            ctx.textAlign = 'right';
            ctx.fillStyle = '#16a34a';
            ctx.font = 'bold 18px Montserrat, sans-serif';
            ctx.fillText('Rp ' + orderTotal.toLocaleString('id-ID'), canvas.width - 50, curY + 4);

            // Footer Note
            curY += 60;
            ctx.textAlign = 'center';
            ctx.fillStyle = '#94a3b8';
            ctx.font = '500 12px Montserrat, sans-serif';
            ctx.fillText('Terima kasih telah berkunjung & berbelanja!', canvas.width / 2, curY);
            ctx.fillText('Simpan struk ini sebagai bukti pesanan yang sah.', canvas.width / 2, curY + 20);

            // Trigger Download
            const link = document.createElement('a');
            link.download = 'Struk_' + orderNumber + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }
    </script>
    <!-- ── POP-UP NOTIFIKASI PESANAN SELESAI / ALARM ── -->
    <div id="orderReadyModal"
        style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15,23,42,0.75); backdrop-filter:blur(4px); z-index:99999; align-items:center; justify-content:center; padding:1.25rem; box-sizing:border-box;">
        <div
            style="background:#ffffff; border-radius:28px; padding:1.75rem 1.5rem; max-width:380px; width:100%; text-align:center; box-shadow:0 25px 60px rgba(0,0,0,0.35); position:relative; animation:scaleIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);">
            <div
                style="width:72px; height:72px; border-radius:50%; background:#dcfce7; color:#16a34a; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; position:relative; box-shadow:0 0 0 10px rgba(34,197,94,0.15);">
                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"
                    style="animation:shake 1s infinite alternate;">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
            </div>
            <h3 style="font-size:1.3rem; font-weight:800; color:#0f172a; margin:0 0 0.5rem; line-height:1.2;">Pesanan
                Anda Sudah Selesai!</h3>
            <p style="font-size:0.85rem; color:#475569; margin:0 0 1.4rem; line-height:1.45;">
                Makanan &amp; minuman pesanan Anda telah selesai dipersiapkan kasir/dapur dan siap dinikmati!
            </p>
            <button type="button" onclick="stop30sAlarm()"
                style="width:100%; padding:0.9rem; border-radius:999px; background:linear-gradient(135deg,#1db349,#a5cf36); color:#ffffff; font-size:0.92rem; font-weight:800; border:none; cursor:pointer; font-family:inherit; box-shadow:0 6px 20px rgba(29,179,73,0.35);">
                Saya Mengerti (Matikan Alarm)
            </button>
        </div>
    </div>
</body>

</html>