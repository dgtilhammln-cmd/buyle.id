<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Tamu Cadangan - {{ $wedding->groom_nickname }} & {{ $wedding->bride_nickname }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .badge {
            display: inline-block;
            background: #f1f5f9;
            color: #0f172a;
            font-size: 8pt;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .title {
            font-size: 18pt;
            font-weight: bold;
            margin: 4px 0;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .meta-info {
            font-size: 9pt;
            color: #475569;
            margin-top: 4px;
        }
        .notice-box {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 8px 12px;
            font-size: 8.5pt;
            color: #92400e;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #f8fafc;
            color: #0f172a;
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #cbd5e1;
            padding: 8px 6px;
            text-align: left;
        }
        td {
            border: 1px solid #e2e8f0;
            padding: 8px 6px;
            font-size: 9pt;
            vertical-align: middle;
        }
        tr:nth-child(even) td {
            background-color: #fafafa;
        }
        .text-center { text-align: center; }
        .sig-box {
            width: 110px;
            height: 28px;
            border-bottom: 1px dashed #cbd5e1;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8pt;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <span class="badge">Buku Tamu Cadangan Fisik (Panic Button)</span>
        <div class="title">{{ $wedding->groom_nickname }} & {{ $wedding->bride_nickname }}</div>
        <div class="meta-info">
            <strong>Acara:</strong> {{ $wedding->event_title }} &nbsp;|&nbsp;
            <strong>Tanggal:</strong> {{ $wedding->event_date ? $wedding->event_date->isoFormat('dddd, D MMMM Y') : '-' }} &nbsp;|&nbsp;
            <strong>Lokasi:</strong> {{ $wedding->event_location_name ?: '-' }}
        </div>
    </div>

    <div class="notice-box">
        <strong>PANDUAN PANITIA PENERIMA TAMU:</strong> Lembar ini digunakan sebagai cadangan resmi apabila koneksi internet mengalami kendala atau perangkat scanner offline di lokasi acara. Beri tanda centang/paraf pada kolom paraf saat tamu hadir.
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th style="width: 180px;">Nama Tamu Undangan</th>
                <th style="width: 100px;">Kategori / Grup</th>
                <th style="width: 45px;" class="text-center">Pax</th>
                <th style="width: 70px;" class="text-center">Kode QR</th>
                <th style="width: 110px;" class="text-center">Paraf / Hadir</th>
                <th>Catatan / No Meja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guests as $index => $g)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $g->name }}</strong><br><small style="color:#64748b;">{{ $g->phone ?: '-' }}</small></td>
                    <td>{{ $g->group_name ?: 'Tamu Undangan' }}</td>
                    <td class="text-center">{{ $g->pax ?: 1 }}</td>
                    <td class="text-center" style="font-family: monospace; font-size: 8pt;">{{ $g->qr_code }}</td>
                    <td class="text-center"><div class="sig-box"></div></td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color:#94a3b8;">Belum ada daftar tamu yang diinput.</td>
                </tr>
            @endforelse
            {{-- Tambahan 10 baris kosong untuk tamu on-the-spot --}}
            @for ($i = 1; $i <= 10; $i++)
                <tr style="background:#fff;">
                    <td class="text-center" style="color:#cbd5e1;">+</td>
                    <td style="color:#94a3b8; font-style:italic;">(Tamu Tambahan On-the-Spot)</td>
                    <td></td>
                    <td class="text-center">1</td>
                    <td></td>
                    <td class="text-center"><div class="sig-box"></div></td>
                    <td></td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis melalui Modul Undangan Digital buyle.id &bull; {{ now()->format('d/m/Y H:i') }} WIB &bull; Akses Reception PIN: <strong>{{ $wedding->reception_pin }}</strong>
    </div>

</body>
</html>
