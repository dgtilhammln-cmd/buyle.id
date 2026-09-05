<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification
{
    public function __construct(
        protected Order $order,
        protected string $magicLoginUrl,
        protected bool $isNewAccount,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $orderNumber = $this->order->order_number;
        $totalFormatted = 'Rp ' . number_format($this->order->total_price, 0, ',', '.');
        $buyerName = $notifiable->name ?? 'Pembeli';

        // Render item list table
        $itemsHtml = '
        <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin: 20px 0;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; color: #334155;">
                <thead>
                    <tr style="border-bottom: 1px solid #CBD5E1; text-align: left;">
                        <th style="padding-bottom: 8px; font-weight: 700; color: #0F172A;">Rincian Produk</th>
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
                Total Dibayar: <span style="color: #10B981;">' . $totalFormatted . '</span>
            </div>
        </div>';

        $accountNotice = '';
        if ($this->isNewAccount) {
            $accountNotice = '
                <div style="background-color: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; padding: 14px 16px; margin-top: 16px; font-size: 13px; color: #166534;">
                    <strong>Akun buyle.id Otomatis Dibuat:</strong><br>
                    Akun Anda telah dikonfigurasi menggunakan alamat email ini. Anda dapat masuk langsung ke Dashboard Pembeli tanpa memerlukan kata sandi.
                </div>';
        }

        $bodyHtml = view('emails.layout', [
            'subject'          => "Pembayaran Berhasil #{$orderNumber} | buyle.id",
            'badgeText'        => 'PEMBAYARAN SUCCESS',
            'title'            => 'Pembayaran Anda Berhasil Diterima',
            'subtitle'         => "Nomor Transaksi: #{$orderNumber}",
            'content'          => "
                <p>Halo <strong>{$buyerName}</strong>,</p>
                <p>Terima kasih atas pembelian Anda di <strong>buyle.id</strong>. Pembayaran untuk transaksi <strong>#{$orderNumber}</strong> telah dikonfirmasi.</p>
                {$itemsHtml}
                {$accountNotice}
                <p style='margin-top: 20px;'>Klik tombol di bawah ini untuk mengakses atau mengunduh produk digital Anda:</p>
            ",
            'ctaUrl'           => route('buyer.orders.show', $this->order->id),
            'ctaText'          => 'Akses Produk Digital Sekarang',
            'secondaryCtaUrl'  => $this->isNewAccount ? $this->magicLoginUrl : route('buyer.orders.index'),
            'secondaryCtaText' => $this->isNewAccount ? 'Masuk ke Dashboard Pembeli (Instan)' : 'Lihat Riwayat Pesanan',
            'footerNote'       => 'Jika Anda memiliki pertanyaan seputar produk atau akses lisensi, Anda dapat membalas email ini secara langsung.',
        ])->render();

        return (new MailMessage)
            ->subject("Pembayaran Berhasil #{$orderNumber} | buyle.id")
            ->html($bodyHtml);
    }
}
