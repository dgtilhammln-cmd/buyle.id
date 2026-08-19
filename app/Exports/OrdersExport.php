<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function query()
    {
        return Order::query()
            ->with(['user', 'items', 'payment', 'shipment'])
            ->whereBetween('created_at', [$this->start . ' 00:00:00', $this->end . ' 23:59:59'])
            ->orderBy('created_at', 'desc');
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

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->created_at->format('d/m/Y H:i'),
            $order->user->name ?? '-',
            $order->user->email ?? '-',
            $order->shipping_address['phone'] ?? '-',
            $order->status->label(),
            $order->shipment->courier_name ?? '-',
            $order->shipment->tracking_number ?? '-',
            $order->subtotal,
            $order->shipping_cost,
            $order->discount,
            $order->total,
            $order->payment->method ?? '-',
            $order->notes ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
