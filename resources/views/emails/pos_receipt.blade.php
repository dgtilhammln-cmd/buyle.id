@extends('emails.layout')

@section('content')
@php
    $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
    $paymentMethodLabel = match($addr['payment_method'] ?? 'cash') {
        'cash' => 'Tunai',
        'transfer' => 'Transfer Bank',
        'qris' => 'QRIS Midtrans',
        default => 'Tunai'
    };
@endphp

<div style="text-align: center; margin-bottom: 24px;">
    <div style="display: inline-block; padding: 8px 16px; background-color: #ecfdf5; border-radius: 9999px; font-size: 13px; font-weight: 700; color: #059669; margin-bottom: 12px;">
        Pembayaran Berhasil
    </div>
    <h2 style="margin: 0 0 4px 0; font-size: 18px; font-weight: 700; color: #0f172a;">
        {{ $storeName }}
    </h2>
    <p style="margin: 0; font-size: 13px; color: #64748b;">
        {{ $order->order_number }} &bull; {{ $order->created_at->format('d M Y H:i') }} WIB
    </p>
</div>

<div style="background-color: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 24px; border: 1px solid #e2e8f0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; color: #475569;">
        <tr>
            <td style="padding: 4px 0; color: #64748b;">Pelanggan:</td>
            <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #0f172a;">{{ $addr['name'] ?? 'Pelanggan' }}</td>
        </tr>
        @if(!empty($order->notes))
        <tr>
            <td style="padding: 4px 0; color: #64748b;">Catatan / Meja:</td>
            <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #0f172a;">{{ $order->notes }}</td>
        </tr>
        @endif
        <tr>
            <td style="padding: 4px 0; color: #64748b;">Metode Bayar:</td>
            <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #0f172a;">{{ $paymentMethodLabel }}</td>
        </tr>
    </table>
</div>

<div style="margin-bottom: 24px;">
    <h4 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 700; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
        Rincian Pesanan
    </h4>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; color: #334155; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <th align="left" style="padding: 8px 0; color: #64748b; font-weight: 600;">Item</th>
                <th align="center" style="padding: 8px 0; color: #64748b; font-weight: 600;">Qty</th>
                <th align="right" style="padding: 8px 0; color: #64748b; font-weight: 600;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr style="border-bottom: 1px dashed #f1f5f9;">
                <td style="padding: 10px 0; font-weight: 600; color: #0f172a;">
                    {{ $item->product_name }}
                    <div style="font-size: 11px; color: #64748b; font-weight: normal;">
                        @ Rp {{ number_format($item->price, 0, ',', '.') }}
                    </div>
                </td>
                <td align="center" style="padding: 10px 0;">{{ $item->quantity }}</td>
                <td align="right" style="padding: 10px 0; font-weight: 600; color: #0f172a;">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div style="background-color: #ffffff; border-top: 2px dashed #e2e8f0; padding-top: 16px; margin-bottom: 24px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; color: #475569;">
        <tr>
            <td style="padding: 4px 0; color: #64748b;">Subtotal:</td>
            <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #0f172a;">
                Rp {{ number_format($order->subtotal, 0, ',', '.') }}
            </td>
        </tr>
        @if(($order->discount ?? 0) > 0)
        <tr>
            <td style="padding: 4px 0; color: #dc2626;">Diskon:</td>
            <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #dc2626;">
                -Rp {{ number_format($order->discount, 0, ',', '.') }}
            </td>
        </tr>
        @endif
        @if(($addr['service_fee'] ?? 0) > 0)
        <tr>
            <td style="padding: 4px 0; color: #64748b;">Biaya Layanan:</td>
            <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #0f172a;">
                Rp {{ number_format($addr['service_fee'], 0, ',', '.') }}
            </td>
        </tr>
        @endif
        @if(($order->platform_fee ?? 0) > 0)
        <tr>
            <td style="padding: 4px 0; color: #64748b;">Biaya Platform:</td>
            <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #0f172a;">
                Rp {{ number_format($order->platform_fee, 0, ',', '.') }}
            </td>
        </tr>
        @endif
        @if(($order->admin_fee ?? 0) > 0)
        <tr>
            <td style="padding: 4px 0; color: #64748b;">Biaya Admin:</td>
            <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #0f172a;">
                Rp {{ number_format($order->admin_fee, 0, ',', '.') }}
            </td>
        </tr>
        @endif
        <tr style="border-top: 1px solid #e2e8f0; font-size: 15px;">
            <td style="padding: 12px 0 4px 0; font-weight: 700; color: #0f172a;">Total Pembayaran:</td>
            <td style="padding: 12px 0 4px 0; text-align: right; font-weight: 800; color: #1eb349;">
                Rp {{ number_format($order->total, 0, ',', '.') }}
            </td>
        </tr>
        @if(($addr['payment_method'] ?? '') === 'cash' && ($addr['cash_paid'] ?? 0) > 0)
        <tr>
            <td style="padding: 4px 0; color: #64748b;">Tunai Diterima:</td>
            <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #0f172a;">
                Rp {{ number_format($addr['cash_paid'], 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b;">Kembalian:</td>
            <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #0f172a;">
                Rp {{ number_format($addr['cash_change'], 0, ',', '.') }}
            </td>
        </tr>
        @endif
    </table>
</div>

<div style="text-align: center; color: #94a3b8; font-size: 12px; margin-top: 24px;">
    <p style="margin: 0 0 4px 0;">Terima kasih telah berbelanja di <strong>{{ $storeName }}</strong>!</p>
    <p style="margin: 0; font-size: 11px;">Powered by buyle.id</p>
</div>
@endsection
