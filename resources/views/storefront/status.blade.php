<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Status Pesanan #{{ $order->order_number }} – {{ $storeName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
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
            padding: calc(2rem + var(--safe-area-top)) 1.25rem calc(2rem + var(--safe-area-bottom)) 1.25rem;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 35px rgba(0, 0, 0, 0.05);
        }

        .status-badge-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
        }

        .circle-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .circle-confirmed {
            background: #dcfce7;
            color: #16a34a;
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
            margin-bottom: 1.5rem;
        }

        .order-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.25rem;
        }

        .order-card-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .order-card-row:last-child {
            margin-bottom: 0;
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

        .items-box {
            border-top: 1px dashed #cbd5e1;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
        }

        .btn-action-pill {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 0.85rem;
            border-radius: 999px;
            font-size: 0.88rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-family: inherit;
        }

        .btn-primary {
            background: #16a34a;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(22, 163, 74, 0.3);
            margin-bottom: 0.5rem;
        }

        .btn-outline {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
    </style>
</head>
<body>
    @php
        $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
        $tableNo = $addr['table_number'] ?? 'Umum';
        $paymentMethod = $addr['payment_method'] ?? 'cash';
        $statusVal = is_object($order->status) ? $order->status->value : (string) $order->status;
        $isConfirmed = in_array($statusVal, ['confirmed', 'processing', 'completed', 'paid']);
    @endphp

    <div class="status-canvas">
        <div class="status-badge-circle {{ $isConfirmed ? 'circle-confirmed' : 'circle-pending' }}">
            @if($isConfirmed)
                <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            @else
                <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            @endif
        </div>

        <h1 class="status-title">
            {{ $isConfirmed ? 'Pesanan Terkonfirmasi' : 'Pesanan Diterima' }}
        </h1>

        <p class="status-sub">
            @if($isConfirmed)
                Pembayaran telah diverifikasi. Pesanan Anda sedang dipersiapkan oleh pihak toko.
            @elseif($paymentMethod === 'cash')
                Pesanan Anda telah masuk ke sistem kasir. <strong>Silakan sebutkan nomor pesanan atau nomor meja Anda ke kasir</strong> untuk menyelesaikan pembayaran tunai.
            @else
                Pesanan Anda telah dibuat dan menunggu konfirmasi pembayaran digital.
            @endif
        </p>

        <div class="order-card">
            <div class="order-card-row">
                <span class="order-label">Nomor Pesanan</span>
                <span class="order-val">#{{ $order->order_number }}</span>
            </div>
            <div class="order-card-row">
                <span class="order-label">Meja</span>
                <span class="order-val">{{ $tableNo }}</span>
            </div>
            <div class="order-card-row">
                <span class="order-label">Metode Bayar</span>
                <span class="order-val">{{ $paymentMethod === 'cash' ? 'Bayar di Kasir (Cash)' : 'Digital (Snap Midtrans)' }}</span>
            </div>
            <div class="order-card-row">
                <span class="order-label">Status</span>
                <span class="order-val" style="color: {{ $isConfirmed ? '#16a34a' : '#d97706' }};">
                    {{ $isConfirmed ? 'Lunas / Diproses' : 'Menunggu Kasir' }}
                </span>
            </div>

            <div class="items-box">
                <div style="font-size:0.75rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.5rem;">
                    Menu Dipesan
                </div>
                @foreach($order->items as $it)
                    <div style="display:flex; justify-content:space-between; font-size:0.78rem; margin-bottom:4px;">
                        <span>{{ $it->qty }}&times; {{ $it->product_name }}</span>
                        <span style="font-weight:600;">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div style="display:flex; justify-content:space-between; font-size:0.88rem; font-weight:800; margin-top:8px; padding-top:6px; border-top:1px dashed #cbd5e1; color:#0f172a;">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div style="margin-top:auto;">
            <a href="{{ route('storefront.show', ['slug' => $slug]) }}" class="btn-action-pill btn-primary">
                Pesan Menu Lainnya
            </a>
            <button type="button" onclick="location.reload()" class="btn-action-pill btn-outline">
                Perbarui Status
            </button>
        </div>
    </div>
</body>
</html>

