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
        $buyerName = $notifiable->name ?? 'Teman buyle.id';

        // Render item list table
        $itemsHtml = '
        <div style="background-color: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 18px; margin: 20px 0;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; color: #334155;">
                <thead>
                    <tr style="border-bottom: 1.5px solid #CBD5E1; text-align: left;">
                        <th style="padding-bottom: 10px; font-weight: 700; color: #0F172A;">Rincian Produk</th>
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
                Total Dibayar: <span style="color: #1eb349;">' . $totalFormatted . '</span>
            </div>
        </div>';

        $accountNotice = '';
        if ($this->isNewAccount) {
            $accountNotice = '
                <div style="background-color: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; padding: 14px 16px; margin-top: 16px; font-size: 13px; color: #065F46;">
                    <strong>Akun buyle.id Kamu Sudah Siap:</strong><br>
                    Kami sudah buatkan akun otomatis menggunakan email ini. Kamu bisa klik tombol sekunder di bawah untuk masuk ke dashboard tanpa perlu ngetik password!
                </div>';
        }

        return (new MailMessage)
            ->subject("Pembayaran Berhasil! Pesanan #{$orderNumber} Siap Diunduh | buyle.id")
            ->view('emails.layout', [
                'subject'          => "Pembayaran Berhasil! Pesanan #{$orderNumber} Siap Diunduh | buyle.id",
                'badgeText'        => 'PEMBAYARAN SUCCESS',
                'title'            => 'Pembayaran Berhasil Diterima!',
                'subtitle'         => "Terima kasih ya! Produk digital kamu siap diakses #{$orderNumber}",
                'content'          => "
                    <p>Halo <strong>{$buyerName}</strong>,</p>
                    <p>Makasih banyak sudah berbelanja di <strong>buyle.id</strong>. Pembayaran kamu untuk transaksi <strong>#{$orderNumber}</strong> sudah terverifikasi.</p>
                    {$itemsHtml}
                    {$accountNotice}
                    <p style='margin-top: 20px;'>Klik tombol hijau di bawah untuk langsung mengunduh atau mengakses produk digital kamu:</p>
                ",
                'ctaUrl'           => route('buyer.orders.show', $this->order->id),
                'ctaText'          => 'Akses Produk Digital Sekarang',
                'secondaryCtaUrl'  => $this->isNewAccount ? $this->magicLoginUrl : route('buyer.orders.index'),
                'secondaryCtaText' => $this->isNewAccount ? 'Masuk Instan ke Dashboard Pembeli' : 'Lihat Riwayat Pesanan Saya',
                'footerNote'       => 'Ada kendala dalam mengunduh atau butuh bantuan lisensi? Balas email ini aja, kami siap bantu sampai tuntas!',
            ]);
    }
}
