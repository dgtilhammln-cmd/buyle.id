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
        $totalFormatted = 'Rp ' . number_format($this->order->total, 0, ',', '.');
        $buyerName = htmlspecialchars($notifiable->name ?? 'Teman buyle.id');

        // Pastikan TicketPass telah ter-generate jika ada produk tiket
        try {
            \App\Models\TicketPass::generateForOrder($this->order);
        } catch (\Throwable $e) {
            // Silence exception in mail generator
        }

        $ticketPasses = \App\Models\TicketPass::where('order_id', $this->order->id)->with('product')->get();
        // Deteksi jenis transaksi (Ticketing vs Produk Digital)
        $hasTickets = $ticketPasses->isNotEmpty() || $this->order->items->contains(fn($i) => $i->product?->product_type === 'ticket');

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

        // Render E-Ticket Pass HTML Card jika ada tiket
        $ticketsHtml = '';
        if ($hasTickets) {
            $ticketsHtml = '
            <div style="margin: 24px 0;">
                <div style="font-size: 16px; font-weight: 800; color: #0F172A; margin-bottom: 14px;">
                    E-Ticket Digital Pass (QR Code Check-In Masuk Acara)
                </div>';
            
            foreach ($ticketPasses as $pass) {
                $qrUrl = route('qr.code', ['data' => $pass->qr_token]);
                $eventName = htmlspecialchars($pass->product?->name ?? 'Tiket Event');
                $eventDate = $pass->product?->event_date?->format('d M Y') ?? 'Sesuai Jadwal';
                $eventTime = htmlspecialchars($pass->product?->event_time ?? '-');
                $eventLocation = htmlspecialchars($pass->product?->event_location ?? 'Venue / Online');

                $ticketsHtml .= '
                <div style="background-color: #FFFFFF; border: 2px solid #1eb349; border-radius: 16px; padding: 18px; margin-bottom: 16px; box-shadow: 0 4px 14px rgba(30,179,73,0.1);">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="140" align="center" valign="top" style="padding-right: 14px;">
                                <img src="' . $qrUrl . '" alt="QR Code Tiket" width="130" height="130" style="display: block; margin: 0 auto; border-radius: 10px; border: 1.5px solid #E2E8F0; background: #fff; padding: 4px;">
                                <div style="font-family: monospace; font-size: 11px; font-weight: 800; color: #0F172A; margin-top: 8px; text-align: center; background: #F1F5F9; padding: 3px 6px; border-radius: 6px;">
                                    ' . htmlspecialchars($pass->ticket_code) . '
                                </div>
                            </td>
                            <td valign="top" style="font-size: 13px; color: #334155; line-height: 1.6;">
                                <div style="font-size: 16px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">' . $eventName . '</div>
                                <div style="margin-bottom: 4px;"><strong>Pemegang Tiket:</strong> ' . htmlspecialchars($pass->holder_name) . '</div>
                                <div style="margin-bottom: 4px;"><strong>Tanggal & Waktu:</strong> ' . $eventDate . ' (' . $eventTime . ')</div>
                                <div style="margin-bottom: 6px;"><strong>Lokasi / Venue:</strong> ' . $eventLocation . '</div>
                                <div style="display: inline-block; margin-top: 4px; background-color: #DCFCE7; color: #166534; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 99px; text-transform: uppercase;">
                                    Status: TIKET VALID / BISA SCANNED
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>';
            }

            $ticketsHtml .= '</div>';
        }

        $accountNotice = '';
        if ($this->isNewAccount) {
            $accountNotice = '
                <div style="background-color: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; padding: 14px 16px; margin-top: 16px; font-size: 13px; color: #065F46;">
                    <strong>Akun buyle.id Kamu Sudah Siap:</strong><br>
                    Kami sudah buatkan akun otomatis menggunakan email ini. Kamu bisa klik tombol sekunder di bawah untuk masuk ke dashboard tanpa perlu ngetik password!
                </div>';
        }

        if ($hasTickets) {
            $subject          = "Pembayaran Berhasil! E-Ticket Event #{$orderNumber} Siap Digunakan | buyle.id";
            $badgeText        = 'E-TICKET EVENT ACTIVE';
            $title            = 'E-Ticket Kamu Sudah Aktif!';
            $subtitle         = "Pembayaran terverifikasi. Tunjukkan QR Code ini saat masuk lokasi acara #{$orderNumber}";
            $introText        = "Halo <strong>{$buyerName}</strong>, pembayaran tiket event kamu sudah terverifikasi. Berikut adalah akses E-Ticket resmi beserta QR Code unik untuk masuk ke tempat acara:";
            $ctaText          = 'Buka & Simpan E-Ticket Saya';
            $promptMsg        = "Silakan simpan email ini atau tunjukkan QR Code di atas saat proses check-in di lokasi acara:";
            $footerNote       = 'Ada kendala terkait lokasi, jadwal event, atau tiket? Balas email ini aja, tim kami siap bantu!';
        } else {
            $subject          = "Pembayaran Berhasil! File Akses Produk Digital #{$orderNumber} Ready | buyle.id";
            $badgeText        = 'DIGITAL PRODUCT READY';
            $title            = 'Pembayaran Produk Digital Berhasil!';
            $subtitle         = "Produk digital kamu sudah siap diunduh dan dipelajari #{$orderNumber}";
            $introText        = "Halo <strong>{$buyerName}</strong>, terima kasih banyak telah berbelanja di <strong>buyle.id</strong>. Pembayaran kamu untuk produk digital <strong>#{$orderNumber}</strong> sudah terverifikasi:";
            $ctaText          = 'Unduh & Akses Produk Digital';
            $promptMsg        = "Klik tombol hijau di bawah untuk langsung mengunduh file atau membuka tautan akses produk digital kamu:";
            $footerNote       = 'Ada kendala mengunduh file atau membuka link? Balas email ini aja, kami siap bantu sampai tuntas!';
        }

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.layout', [
                'subject'          => $subject,
                'badgeText'        => $badgeText,
                'title'            => $title,
                'subtitle'         => $subtitle,
                'content'          => "
                    <p>{$introText}</p>
                    {$itemsHtml}
                    {$ticketsHtml}
                    {$accountNotice}
                    <p style='margin-top: 20px;'>{$promptMsg}</p>
                ",
                'ctaUrl'           => route('account.orders.show', $this->order->id),
                'ctaText'          => $ctaText,
                'secondaryCtaUrl'  => $this->isNewAccount ? $this->magicLoginUrl : route('account.orders'),
                'secondaryCtaText' => $this->isNewAccount ? 'Masuk Instan ke Dashboard Pembeli' : 'Lihat Riwayat Pesanan Saya',
                'footerNote'       => $footerNote,
            ]);
    }
}
