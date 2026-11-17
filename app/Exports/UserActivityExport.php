<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UserActivityExport implements FromArray, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $user;
    protected $headerRows = [];

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function array(): array
    {
        $user = $this->user->loadMissing([
            'referrer',
            'orders.items.product',
            'orders.payment',
            'ticketPasses.product',
            'addresses'
        ]);

        $personaLabels = [
            'digital_product'    => 'Produk Digital',
            'service_freelance'  => 'Jasa & Freelance',
            'fnb_resto'          => 'FnB & Resto / Kafe',
            'event_ticket'       => 'Event & Tiket',
            'wedding_invitation' => 'Undangan Digital & Wedding',
            'content_creator'    => 'Content Creator',
            'media_partner'      => 'Media Partner',
            'business_retail'    => 'Bisnis & Ritel',
        ];

        $goalLabels = [
            'sell_digital'    => 'Jual Produk Digital & Karya Kreatif',
            'web_builder'     => 'Bangun Website & Landing Page Profesional',
            'pos_cashier'     => 'Kasir POS & Menu Resto Digital',
            'event_ticketing' => 'Penjualan Tiket Konser & Manajemen Event',
            'digital_wedding' => 'Undangan Digital & Buku Tamu QR',
        ];

        $data = [];
        $currentRow = 1;

        // TITLE
        $data[] = ['LAPORAN AKTIVITAS & DATA PENGGUNA - BUYLE.ID'];
        $data[] = ['Diekspor pada: ' . date('d F Y H:i:s')];
        $data[] = [''];
        $currentRow += 3;

        // SECTION 1: PROFIL PENGGUNA
        $this->headerRows[] = $currentRow;
        $data[] = ['=== 1. INFORMASI AKUN ===', '', '', '', '', '', ''];
        $data[] = ['User ID', '#' . $user->id];
        $data[] = ['Nama Lengkap', $user->name];
        $data[] = ['Username', $user->username ? '@' . $user->username : '-'];
        $data[] = ['Email', $user->email];
        $data[] = ['Nomor WhatsApp / HP', $user->phone ? "'" . $user->phone : '-'];
        $data[] = ['NIK / Identitas Resmi', "'" . ($user->resolved_nik ?? $user->nik ?? '-')];
        $data[] = ['Status Akun', ($user->is_active ?? true) ? 'Aktif' : 'Nonaktif'];
        $data[] = ['Role', ucfirst($user->role ?? 'Buyer')];
        $data[] = ['Login Google', $user->google_id ? 'Ya' : 'Tidak'];
        $data[] = ['Terdaftar Sejak', $user->created_at ? $user->created_at->format('d F Y, H:i') : '-'];
        $data[] = [''];
        $currentRow += 12;

        // SECTION 2: DATA ONBOARDING
        $this->headerRows[] = $currentRow;
        $data[] = ['=== 2. DATA ONBOARDING WIZARD ===', '', '', '', '', '', ''];
        $data[] = ['Tipe Bisnis / Persona', $personaLabels[$user->business_persona] ?? ($user->business_persona ?: '-')];
        $data[] = ['Sumber Tahu Buyle', $user->referral_source_type ?: '-'];
        $data[] = ['Platform / Detail Sumber', $user->referral_source_name ?: '-'];
        $data[] = ['Media Partner Penyelenggara', $user->media_partner_name ?: '-'];
        $data[] = ['Tujuan Utama Penggunaan', $goalLabels[$user->primary_goal] ?? ($user->primary_goal ?: '-')];
        $data[] = ['Kode Referral Akun', $user->referral_code ?: '-'];
        $data[] = ['Direferensikan Oleh', $user->referrer ? $user->referrer->name . ' (' . $user->referrer->email . ')' : '-'];
        $data[] = ['Status Onboarding', $user->onboarding_completed_at ? 'Selesai' : 'Belum Selesai'];
        $data[] = ['Waktu Selesai Onboarding', $user->onboarding_completed_at ? $user->onboarding_completed_at->format('d F Y, H:i') : '-'];
        $data[] = [''];
        $currentRow += 11;

        // SECTION 3: RIWAYAT ORDER
        $this->headerRows[] = $currentRow;
        $data[] = ['=== 3. RIWAYAT TRANSAKSI / ORDER (' . $user->orders->count() . ' Pesanan) ===', '', '', '', '', '', ''];
        $data[] = ['No. Pesanan', 'Tanggal', 'Item Produk', 'Qty', 'Total (Rp)', 'Status Order', 'Metode Bayar'];
        $currentRow += 2;

        if ($user->orders->isEmpty()) {
            $data[] = ['Belum ada riwayat pesanan.', '', '', '', '', '', ''];
            $currentRow++;
        } else {
            foreach ($user->orders as $order) {
                $itemNames = $order->items->map(function ($it) {
                    return ($it->product->title ?? $it->product_name ?? 'Produk') . ' (x' . $it->qty . ')';
                })->implode(', ');

                $totalQty = $order->items->sum('qty');
                $statusStr = is_object($order->status) ? ($order->status->value ?? 'pending') : (string)($order->status ?? 'pending');
                $method = $order->payment->method ?? $order->payment_method ?? '-';

                $data[] = [
                    $order->order_number ?? '#' . $order->id,
                    $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-',
                    $itemNames ?: 'Order #' . $order->id,
                    $totalQty,
                    (float) ($order->total ?? 0),
                    ucfirst($statusStr),
                    strtoupper($method),
                ];
                $currentRow++;
            }
        }
        $data[] = [''];
        $currentRow++;

        // SECTION 4: RIWAYAT TIKET / E-TICKET
        $this->headerRows[] = $currentRow;
        $data[] = ['=== 4. RIWAYAT TIKET / E-TICKET PASSES (' . $user->ticketPasses->count() . ' Tiket) ===', '', '', '', '', '', ''];
        $data[] = ['Kode Tiket', 'Nama Event / Produk', 'Pemegang Tiket', 'NIK / Identitas', 'Status Tiket', 'Waktu Scan', 'Order Ref'];
        $currentRow += 2;

        if ($user->ticketPasses->isEmpty()) {
            $data[] = ['Belum ada tiket yang dibeli.', '', '', '', '', '', ''];
            $currentRow++;
        } else {
            foreach ($user->ticketPasses as $ticket) {
                $eventName = $ticket->product->title ?? 'Event Ticket';
                $data[] = [
                    $ticket->ticket_code,
                    $eventName,
                    $ticket->holder_name ?: '-',
                    "'" . ($ticket->holder_nik ?: '-'),
                    strtoupper($ticket->status ?? 'valid'),
                    $ticket->scanned_at ? $ticket->scanned_at->format('d/m/Y H:i') : 'Belum Check-in',
                    '#' . $ticket->order_id,
                ];
                $currentRow++;
            }
        }
        $data[] = [''];
        $currentRow++;

        // SECTION 5: ALAMAT PENGIRIMAN
        $this->headerRows[] = $currentRow;
        $data[] = ['=== 5. DAFTAR ALAMAT PENGIRIMAN (' . $user->addresses->count() . ' Alamat) ===', '', '', '', '', '', ''];
        $data[] = ['Label Alamat', 'Penerima', 'No. HP', 'Alamat Lengkap', 'Kota / Kab', 'Provinsi', 'Kode Pos'];
        $currentRow += 2;

        if ($user->addresses->isEmpty()) {
            $data[] = ['Belum ada alamat tersimpan.', '', '', '', '', '', ''];
            $currentRow++;
        } else {
            foreach ($user->addresses as $addr) {
                $data[] = [
                    $addr->label ?? 'Utama',
                    $addr->receiver_name ?: '-',
                    $addr->phone ? "'" . $addr->phone : '-',
                    $addr->full_address ?: $addr->address,
                    $addr->city ?: '-',
                    $addr->province ?: '-',
                    $addr->postal_code ? "'" . $addr->postal_code : '-',
                ];
                $currentRow++;
            }
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1EB349']],
            ],
            2 => [
                'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '64748B']],
            ],
        ];

        foreach ($this->headerRows as $row) {
            $styles[$row] = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F172A'], // Dark luxury header
                ],
            ];
            // Sub-heading table kolom
            $styles[$row + 1] = [
                'font' => ['bold' => true, 'color' => ['rgb' => '0F172A']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DCFCE7'], // Light green row
                ],
            ];
        }

        return $styles;
    }
}
