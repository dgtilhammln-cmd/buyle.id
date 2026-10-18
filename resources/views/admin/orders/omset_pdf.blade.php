<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan &amp; Omset</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 10px;
        }
        .header {
            margin-bottom: 15px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }
        .subtitle {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            text-align: left;
            font-size: 9px;
        }
        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.02em;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row td {
            background-color: #e2e8f0;
            font-weight: bold;
            color: #0f172a;
            font-size: 9.5px;
            border-top: 2px solid #0f172a;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="border:none; margin:0; width:100%;">
            <tr style="border:none;">
                <td style="border:none; padding:0;">
                    <h1 class="title">LAPORAN KEUANGAN &amp; OMSET PLATFORM</h1>
                    <div class="subtitle">Buyle.id &bull; Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</div>
                </td>
                <td style="border:none; padding:0; text-align:right; vertical-align:bottom;">
                    <div style="font-size:9px; color:#64748b;">Dicetak: {{ now()->format('d/m/Y H:i') }} WIB</div>
                    <div style="font-size:9px; color:#64748b;">Filter: {{ strtoupper($txType) }} ({{ count($rows) }} Transaksi)</div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:11%;">Tanggal</th>
                <th style="width:11%;">ID Ref</th>
                <th style="width:14%;">Pihak / User</th>
                <th style="width:13%;">Kategori</th>
                <th style="width:8%;">Channel</th>
                <th style="width:8%;" class="text-right">Modal Dasar</th>
                <th style="width:6%;" class="text-right">Admin Fee</th>
                <th style="width:7%;" class="text-right">Platform Fee</th>
                <th style="width:6%;" class="text-right">PPN 11%</th>
                <th style="width:8%;" class="text-right">Total Transaksi</th>
                <th style="width:8%;" class="text-right">Profit</th>
                <th style="width:7%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $r)
                <tr>
                    <td>{{ $r->created_at ? $r->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td><strong>{{ $r->ref_number }}</strong></td>
                    <td>
                        <div>{{ $r->party_name }}</div>
                        <div style="font-size:7.5px; color:#64748b;">{{ $r->party_email }}</div>
                    </td>
                    <td>{{ $r->category }}</td>
                    <td>{{ $r->channel }}</td>
                    <td class="text-right">Rp {{ number_format($r->base_amount, 0, ',', '.') }}</td>
                    <td class="text-right">+ Rp {{ number_format($r->admin_fee, 0, ',', '.') }}</td>
                    <td class="text-right">+ Rp {{ number_format($r->platform_fee, 0, ',', '.') }}</td>
                    <td class="text-right">+ Rp {{ number_format($r->tax_amount, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($r->gross, 0, ',', '.') }}</strong></td>
                    <td class="text-right" style="color:#15803d;"><strong>+ Rp {{ number_format($r->profit, 0, ',', '.') }}</strong></td>
                    <td class="text-center">{{ $r->status_label }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center" style="padding:15px; color:#94a3b8;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL KESELURUHAN ({{ count($rows) }} TRANSAKSI):</td>
                <td class="text-right">Rp {{ number_format($totals['base_amount'] ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">+ Rp {{ number_format($totals['admin_fee'] ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">+ Rp {{ number_format($totals['platform_fee'] ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">+ Rp {{ number_format($totals['tax_amount'] ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totals['gross'] ?? 0, 0, ',', '.') }}</td>
                <td class="text-right" style="color:#15803d;">+ Rp {{ number_format($totals['profit'] ?? 0, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
