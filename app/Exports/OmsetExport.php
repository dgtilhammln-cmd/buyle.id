<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OmsetExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $rows;
    protected $totals;
    protected $start;
    protected $end;

    public function __construct($rows, array $totals, $start, $end)
    {
        $this->rows   = $rows;
        $this->totals = $totals;
        $this->start  = $start;
        $this->end    = $end;
    }

    public function headings(): array
    {
        return [
            'Tanggal & Waktu',
            'ID Referensi',
            'Pihak / User',
            'Email',
            'Kategori',
            'Channel',
            'Harga / Modal Dasar (Rp)',
            'Admin Fee (Rp)',
            'Platform Fee (Rp)',
            'PPN 11% (Rp)',
            'Total Transaksi (Rp)',
            'Profit Platform (Rp)',
            'Status'
        ];
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->rows as $r) {
            $data[] = [
                $r->created_at ? $r->created_at->format('d/m/Y H:i') : '-',
                $r->ref_number ?? '-',
                $r->party_name ?? '-',
                $r->party_email ?? '-',
                $r->category ?? '-',
                $r->channel ?? '-',
                (float)($r->base_amount ?? 0),
                (float)($r->admin_fee ?? 0),
                (float)($r->platform_fee ?? 0),
                (float)($r->tax_amount ?? 0),
                (float)($r->gross ?? 0),
                (float)($r->profit ?? 0),
                $r->status_label ?? $r->status ?? '-',
            ];
        }

        // Baris TOTAL Keseluruhan di paling bawah
        $data[] = [
            'TOTAL KESELURUHAN',
            '',
            '',
            '',
            '',
            '',
            (float)($this->totals['base_amount'] ?? 0),
            (float)($this->totals['admin_fee'] ?? 0),
            (float)($this->totals['platform_fee'] ?? 0),
            (float)($this->totals['tax_amount'] ?? 0),
            (float)($this->totals['gross'] ?? 0),
            (float)($this->totals['profit'] ?? 0),
            '',
        ];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->rows) + 2; // Row 1 = header, rows 2..N+1 = data, row N+2 = total

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => '0F172A']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F1F5F9'],
                ],
            ],
            $lastRow => [
                'font' => ['bold' => true, 'color' => ['rgb' => '0F172A']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ];
    }
}
