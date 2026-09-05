<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerNewOrderNotification extends Notification
{
    public function __construct(
        protected Order $order
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $orderNumber = $this->order->order_number;
        $totalFormatted = 'Rp ' . number_format($this->order->total_price, 0, ',', '.');
        $sellerName = $notifiable->name ?? 'Creator';

        // Render item list table
        $itemsHtml = '
        <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin: 20px 0;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; color: #334155;">
                <thead>
                    <tr style="border-bottom: 1px solid #CBD5E1; text-align: left;">
                        <th style="padding-bottom: 8px; font-weight: 700; color: #0F172A;">Produk</th>
                        <th style="padding-bottom: 8px; font-weight: 700; color: #0F172A; text-align: center;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($this->order->items as $item) {
            $itemsHtml .= '
                    <tr>
                        <td style="padding: 8px 0; border-top: 1px solid #F1F5F9; font-weight: 600; color: #1E293B;">' . htmlspecialchars($item->product_name) . '</td>
                        <td style="padding: 8px 0; border-top: 1px solid #F1F5F9; text-align: center; color: #64748B;">' . $item->qty . 'x</td>
                    </tr>';
        }

        $itemsHtml .= '
                </tbody>
            </table>
            <div style="border-top: 2px solid #E2E8F0; margin-top: 12px; padding-top: 12px; font-size: 15px; font-weight: 800; color: #0F172A; text-align: right;">
                Total Pembayaran: <span style="color: #10B981;">' . $totalFormatted . '</span>
            </div>
        </div>';

        return (new MailMessage)
            ->subject("Pesanan Baru Masuk #{$orderNumber} | buyle.id")
            ->view('emails.layout', [
                'subject'   => "Pesanan Baru Masuk #{$orderNumber} | buyle.id",
                'badgeText' => 'PESANAN MASUK',
                'title'     => 'Pesanan Baru Berhasil Diterima',
                'subtitle'  => "Nomor Pesanan: #{$orderNumber}",
                'content'   => "
                    <p>Halo <strong>{$sellerName}</strong>,</p>
                    <p>Selamat! Pembeli telah menyelesaikan pembayaran untuk pesanan <strong>#{$orderNumber}</strong>.</p>
                    {$itemsHtml}
                    <p>Silakan periksa dan kelola detail transaksi ini melalui dashboard creator Anda.</p>
                ",
                'ctaUrl'    => route('creator.orders.index'),
                'ctaText'   => 'Lihat Pesanan di Dashboard Creator',
                'footerNote' => 'Notifikasi ini dikirimkan secara otomatis saat pembayaran transaksi berhasil dikonfirmasi oleh sistem.',
            ]);
    }
}
