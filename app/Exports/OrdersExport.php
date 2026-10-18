<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OrdersExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $start;
    protected $end;
    protected $rowCount = 0;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function headings(): array
    {
        return [
            'No. Pesanan',
            'Tanggal',
            'Pembeli',
            'Email',
            'No. HP',
            'Status',
            'Kurir',
            'Resi',
            'Subtotal Produk (Rp)',
            'Ongkir (Rp)',
            'Diskon (Rp)',
            'Total Pembayaran (Rp)',
            'Metode Pembayaran',
            'Catatan'
        ];
    }

    public function array(): array
    {
        $orders = Order::query()
            ->with(['user', 'items', 'payment', 'shipment'])
            ->whereBetween('created_at', [$this->start . ' 00:00:00', $this->end . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [];
        $totalSubtotal = 0;
        $totalShipping = 0;
        $totalDiscount = 0;
        $totalAmount   = 0;

        foreach ($orders as $order) {
            $subtotal = (float)$order->subtotal;
            $shipping = (float)$order->shipping_cost;
            $discount = (float)$order->discount;
            $total    = (float)$order->total;

            $totalSubtotal += $subtotal;
            $totalShipping += $shipping;
            $totalDiscount += $discount;
            $totalAmount   += $total;

            $data[] = [
                $order->order_number,
                $order->created_at->format('d/m/Y H:i'),
                $order->user->name ?? '-',
                $order->user->email ?? '-',
                $order->shipping_address['phone'] ?? '-',
                $order->status->label(),
                $order->shipment->courier_name ?? '-',
                $order->shipment->tracking_number ?? '-',
                $subtotal,
                $shipping,
                $discount,
                $total,
                $order->payment->method ?? '-',
                $order->notes ?? '-'
            ];
        }

        $this->rowCount = count($data);

        // Baris TOTAL Keseluruhan di paling bawah
        $data[] = [
            'TOTAL KESELURUHAN',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            $totalSubtotal,
            $totalShipping,
            $totalDiscount,
            $totalAmount,
            '',
            ''
        ];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->rowCount + 2;

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
