<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pesanan</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; margin: 12px; }
        h2 { text-align: center; margin-bottom: 4px; font-size: 15px; color: #0f172a; }
        .subtitle { text-align: center; margin-top: 0; color: #64748b; font-size: 10px; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #cbd5e1; padding: 5px 7px; text-align: left; font-size: 9.5px; }
        th { background-color: #f1f5f9; color: #334155; font-weight: bold; font-size: 8.5px; text-transform: uppercase; letter-spacing: 0.02em; }
        .text-right { text-align: right; }
        .total-row td {
            background-color: #e2e8f0;
            font-weight: bold;
            color: #0f172a;
            font-size: 10px;
            border-top: 2px solid #0f172a;
        }
        .meta { font-size: 9px; color: #64748b; text-align: right; margin-bottom: 6px; }
    </style>
</head>
<body>
    <h2>Laporan Pesanan Produk</h2>
    <p class="subtitle">Buyle.id &bull; Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</p>
    <div class="meta">Dicetak: {{ now()->format('d/m/Y H:i') }} WIB &nbsp;&bull;&nbsp; {{ count($orders) }} Pesanan</div>

    <table>
        <thead>
            <tr>
                <th style="width:10%;">No. Pesanan</th>
                <th style="width:9%;">Tanggal</th>
                <th style="width:18%;">Pembeli</th>
                <th style="width:9%;">Status</th>
                <th style="width:10%;" class="text-right">Subtotal (Rp)</th>
                <th style="width:8%;" class="text-right">Ongkir (Rp)</th>
                <th style="width:8%;" class="text-right">Diskon (Rp)</th>
                <th style="width:10%;" class="text-right">Total (Rp)</th>
                <th style="width:10%;">Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $o)
            <tr>
                <td><strong>{{ $o->order_number }}</strong></td>
                <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <div>{{ $o->user->name ?? '-' }}</div>
                    <div style="font-size:8px;color:#64748b;">{{ $o->user->email ?? '-' }}</div>
                </td>
                <td>{{ $o->status->label() }}</td>
                <td class="text-right">{{ number_format($o->subtotal, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($o->shipping_cost, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($o->discount, 0, ',', '.') }}</td>
                <td class="text-right"><strong>{{ number_format($o->total, 0, ',', '.') }}</strong></td>
                <td>{{ $o->payment->method ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL KESELURUHAN ({{ count($orders) }} PESANAN):</td>
                <td class="text-right">Rp {{ number_format($pdfTotals['subtotal'] ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($pdfTotals['shipping'] ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($pdfTotals['discount'] ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($pdfTotals['total'] ?? 0, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
