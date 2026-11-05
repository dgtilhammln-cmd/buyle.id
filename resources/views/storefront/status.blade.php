<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Status Pesanan #{{ $order->order_number }} – {{ $storeName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    @if(!empty($snapUrl) && !empty($midtransClientKey))
        <script src="{{ $snapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    @endif
    <style>
        :root {
            --store-accent: {{ $posAccentColor }};
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
            0% { transform: scale(1); }
            50% { transform: scale(1.04); }
            100% { transform: scale(1); }
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
    </style>
</head>
<body>
    @php
        $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
        $orderType = $addr['order_type'] ?? 'dine_in';
        $tableNo = $addr['table_number'] ?? ($orderType === 'takeaway' ? 'Takeaway' : 'Umum');
        $paymentMethod = $addr['payment_method'] ?? 'cash';
        $statusVal = strtolower(is_object($order->status) ? $order->status->value : (string) $order->status);
        $payStat = strtolower($order->payment?->status?->value ?? (string)($order->payment?->status ?? ''));
        $isPaid = in_array($statusVal, ['confirmed', 'delivered', 'processing', 'completed', 'paid']) || in_array($payStat, ['success', 'paid']);
        $isCompleted = in_array($statusVal, ['delivered', 'completed']);
        $feesBreakdown = $addr['fees_breakdown'] ?? [];
        $discountDetails = $addr['discount_details'] ?? null;
    @endphp

    <div class="status-canvas">
        {{-- Arahan Jangan di Close --}}
        <div class="stay-notice-box">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div>
                <strong>Jangan tutup halaman ini!</strong>
                <div>Status pesanan &amp; proses makanan Anda akan terupdate otomatis secara real-time.</div>
            </div>
        </div>

        {{-- Cashier Notice jika Cash & belum bayar --}}
        @if(!$isPaid && $paymentMethod === 'cash')
            <div class="cashier-warning-box" id="cashierWarningBox">
                <div class="cw-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="2" y="6" width="20" height="12" rx="2"/>
                        <circle cx="12" cy="12" r="2.5"/>
                        <path d="M6 12h.01M18 12h.01"/>
                    </svg>
                </div>
                <div>
                    <div class="cw-title">Wajib Sekarang ke Kasir</div>
                    <div class="cw-desc">
                        Silakan segera ke kasir dan sebutkan <strong>#{{ $order->order_number }}</strong> ({{ $tableNo }}). Pesanan baru akan diproses dan dimasak setelah pembayaran diterima kasir.
                    </div>
                </div>
            </div>
        @endif

        {{-- Status Circle Badge --}}
        <div class="status-badge-circle {{ $isCompleted ? 'circle-completed' : ($isPaid ? 'circle-confirmed' : 'circle-pending') }}" id="statusBadgeCircle">
            @if($isCompleted)
                {{-- Centang 2 --}}
                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                    <polyline points="20 10 9 21 4 16" style="opacity:0.6;"/>
                </svg>
            @elseif($isPaid)
                {{-- Ceklis 1 --}}
                <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            @else
                {{-- Jam pasir / pending --}}
                <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            @endif
        </div>

        <h1 class="status-title" id="statusTitle">
            @if($isCompleted)
                Pesanan Sudah Selesai!
            @elseif($isPaid)
                Pesanan Terkonfirmasi
            @else
                Pesanan Diterima
            @endif
        </h1>

        <p class="status-sub" id="statusSub">
            @if($isCompleted)
                Pesanan Anda telah selesai dipersiapkan dan siap dinikmati / disajikan ke meja Anda!
            @elseif($isPaid)
                Pembayaran telah diterima. Dapur sedang menyiapkan pesanan makanan &amp; minuman Anda.
            @elseif($paymentMethod === 'cash')
                Pesanan masuk ke kasir. Silakan bayar tunai di meja kasir sekarang agar segera dimasak.
            @else
                Pesanan Anda menunggu konfirmasi pembayaran digital.
            @endif
        </p>

        {{-- Tombol Ganti Metode Pembayaran (Hanya jika belum lunas) --}}
        @if(!$isPaid)
            <div class="switch-pay-wrap" id="switchPayWrap">
                @if($paymentMethod === 'cash')
                    <button type="button" class="btn-switch-pay btn-switch-to-digital" onclick="switchPayment('midtrans')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span>Ganti ke Pembayaran Digital (QRIS / GoPay / VA)</span>
                    </button>
                @else
                    <button type="button" class="btn-switch-pay btn-switch-to-cash" onclick="switchPayment('cash')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
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
                <span class="order-val">{{ $orderType === 'takeaway' ? 'Takeaway (Bawa Pulang)' : 'Dine In (Makan di Tempat)' }}</span>
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
                <span class="order-val" id="dispPayMethod">{{ $paymentMethod === 'cash' ? 'Bayar di Kasir (Cash)' : 'Digital (Snap Midtrans)' }}</span>
            </div>
            <div class="order-card-row">
                <span class="order-label">Status</span>
                <span class="order-val" id="dispStatus" style="color: {{ $isCompleted ? '#16a34a' : ($isPaid ? '#16a34a' : '#d97706') }}; font-weight:800;">
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
                <div style="font-size:0.75rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.5rem;">
                    Rincian Menu &amp; Pajak
                </div>

                @foreach($order->items as $it)
                    <div class="breakdown-row">
                        <span>{{ $it->qty }}&times; {{ $it->product_name }}</span>
                        <span style="font-weight:600;">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach

                <div class="breakdown-row" style="margin-top:6px; padding-top:6px; border-top:1px dashed #e2e8f0; color:#64748b;">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>

                @if($order->discount > 0)
                    <div class="breakdown-row" style="color:#16a34a; font-weight:600;">
                        <span>Diskon {{ !empty($discountDetails['name']) ? '(' . $discountDetails['name'] . ')' : '' }}</span>
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
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <span>Unduh Struk Resmi (PNG)</span>
            </button>

            <a href="{{ route('storefront.show', ['slug' => $slug]) }}" class="btn-action-pill btn-primary">
                Pesan Menu Lainnya
            </a>

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
        const itemsList = {!! json_encode($order->items->map(fn($it) => ['name' => $it->product_name, 'qty' => $it->qty, 'subtotal' => (float)$it->subtotal])) !!};
        const feesList = {!! json_encode($feesBreakdown) !!};
        const discountVal = {{ (float) $order->discount }};
        const subtotalVal = {{ (float) $order->subtotal }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        let isCompletedState = {{ $isCompleted ? 'true' : 'false' }};
        let isPaidState = {{ $isPaid ? 'true' : 'false' }};

        // Audio chime generator (Web Audio API)
        function playChime(isDone = false) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.type = 'sine';

                if (isDone) {
                    // Nada perayaan pesanan selesai
                    osc.frequency.setValueAtTime(523.25, ctx.currentTime); // C5
                    osc.frequency.setValueAtTime(659.25, ctx.currentTime + 0.15); // E5
                    osc.frequency.setValueAtTime(783.99, ctx.currentTime + 0.3); // G5
                    osc.frequency.setValueAtTime(1046.50, ctx.currentTime + 0.45); // C6
                    gain.gain.setValueAtTime(0.35, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 1.1);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 1.1);
                } else {
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    osc.frequency.setValueAtTime(1100, ctx.currentTime + 0.12);
                    gain.gain.setValueAtTime(0.25, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.5);
                }
            } catch(e) {}
        }

        // Getar HP (Vibration API)
        function triggerPhoneVibration() {
            if ('vibrate' in navigator) {
                try {
                    navigator.vibrate([300, 150, 300, 150, 600]);
                } catch(e) {}
            }
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
                    playChime(false);
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
                    triggerPhoneVibration();
                    playChime(true);

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
            .catch(() => {})
            .finally(() => {
                if (!isCompletedState) {
                    setTimeout(pollCustomerOrderStatus, 2800);
                }
            });
        }

        setTimeout(pollCustomerOrderStatus, 2800);

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
                                onSuccess: function(result) {
                                    alert('Pembayaran digital berhasil!');
                                    location.reload();
                                },
                                onPending: function(result) {
                                    alert('Menunggu pembayaran diselesaikan.');
                                    location.reload();
                                },
                                onError: function(result) {
                                    alert('Pembayaran gagal atau dibatalkan.');
                                },
                                onClose: function() {
                                    location.reload();
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
</body>
</html>
