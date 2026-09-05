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
        <div style="background-color: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 18px; margin: 20px 0;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; color: #334155;">
                <thead>
                    <tr style="border-bottom: 1.5px solid #CBD5E1; text-align: left;">
                        <th style="padding-bottom: 10px; font-weight: 700; color: #0F172A;">Item Produk</th>
                        <th style="padding-bottom: 10px; font-weight: 700; color: #0F172A; text-align: center;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($this->order->items as $item) {
            $itemsHtml .= '
                    <tr>
                        <td style="padding: 10px 0; border-top: 1px solid #F1F5F9; font-weight: 600; color: #0F172A;">' . htmlspecialchars($item->product_name) . '</td>
                        <td style="padding: 10px 0; border-top: 1px solid #F1F5F9; text-align: center; color: #64748B; font-weight: 600;">' . $item->qty . 'x</td>
                    </tr>';
        }

        $itemsHtml .= '
                </tbody>
            </table>
            <div style="border-top: 2px solid #E2E8F0; margin-top: 12px; padding-top: 12px; font-size: 15px; font-weight: 800; color: #0F172A; text-align: right;">
                Total Transaksi: <span style="color: #1eb349;">' . $totalFormatted . '</span>
            </div>
        </div>';

        return (new MailMessage)
            ->subject("Hore! Ada Pesanan Baru #{$orderNumber} | buyle.id")
            ->view('emails.layout', [
                'subject'   => "Hore! Ada Pesanan Baru #{$orderNumber} | buyle.id",
                'badgeText' => 'PESANAN MASUK',
                'title'     => 'Hore! Ada Cuan Baru Masuk',
                'subtitle'  => "Pembeli baru saja menyelesaikan pembayaran #{$orderNumber}",
                'content'   => "
                    <p>Halo <strong>{$sellerName}</strong>,</p>
                    <p>Kabar gembira! Pembeli baru saja melunasi pembayaran untuk produk toko kamu.</p>
                    {$itemsHtml}
                    <p>Yuk langsung cek dan kelola detail pesanan ini di dashboard creator kamu!</p>
                ",
                'ctaUrl'    => route('creator.orders.index'),
                'ctaText'   => 'Cek Pesanan di Dashboard Creator',
                'footerNote' => 'Notifikasi ini otomatis dikirim begitu pembayaran pembeli berhasil diverifikasi oleh sistem.',
            ]);
    }
}
