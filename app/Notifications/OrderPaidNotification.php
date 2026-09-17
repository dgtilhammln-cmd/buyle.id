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

        $hasTickets = $ticketPasses->isNotEmpty() || $this->order->items->contains(function ($i) {
            return strtolower($i->product?->product_type ?? '') === 'ticket';
        });

        $hasFood = $this->order->items->contains(function ($i) {
            $type = strtolower($i->product?->product_type ?? $i->product?->type ?? '');
            $cat  = strtolower($i->product?->category?->name ?? '');
            return in_array($type, ['makanan', 'fnb', 'food']) || in_array($cat, ['makanan', 'food', 'culinary', 'kuliner', 'resto', 'fnb', 'makanan & minuman']);
        });

        $hasPhysical = $this->order->items->contains(function ($i) {
            $type = strtolower($i->product?->product_type ?? $i->product?->type ?? '');
            $cat  = strtolower($i->product?->category?->name ?? '');
            return in_array($type, ['physical', 'product', 'fisik', 'barang']) || in_array($cat, ['barang', 'produk fisik', 'umkm']);
        });

        $hasService = $this->order->items->contains(function ($i) {
            $type = strtolower($i->product?->product_type ?? $i->product?->type ?? '');
            $cat  = strtolower($i->product?->category?->name ?? '');
            return in_array($type, ['service', 'jasa']) || in_array($cat, ['jasa', 'service', 'layanan', 'booking & jasa layanan online', 'booking']);
        });

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
        } elseif ($hasFood) {
            $subject          = "Pembayaran Berhasil! Pesanan Kuliner #{$orderNumber} Siap Diproses | buyle.id";
            $badgeText        = 'PESANAN KULINER / F&B';
            $title            = 'Pembayaran Pesanan Makanan Berhasil!';
            $subtitle         = "Pesanan kuliner kamu sudah diteruskan ke Dapur / Resto #{$orderNumber}";
            $introText        = "Halo <strong>{$buyerName}</strong>, pembayaran untuk pesanan makanan/minuman kamu <strong>#{$orderNumber}</strong> telah kami terima dan terverifikasi. Resto sedang memproses pesanan kamu:";
            $ctaText          = 'Lihat Detail Status Pesanan';
            $promptMsg        = "Resto / Dapur sedang menyiapkan pesanan kamu sesuai opsi (Dine-In / Takeaway / Delivery). Pantau statusnya di dashboard:";
            $footerNote       = 'Ada pertanyaan terkait pesanan makanan kamu? Balas email ini aja, tim kami siap bantu!';
        } elseif ($hasPhysical) {
            $subject          = "Pembayaran Berhasil! Pesanan Produk #{$orderNumber} Sedang Diproses | buyle.id";
            $badgeText        = 'PRODUK FISIK DIPROSES';
            $title            = 'Pembayaran Produk Fisik Berhasil!';
            $subtitle         = "Pesanan kamu sudah diterima dan sedang disiapkan oleh penjual #{$orderNumber}";
            $introText        = "Halo <strong>{$buyerName}</strong>, terima kasih banyak telah memesan produk di <strong>buyle.id</strong>. Pembayaran kamu untuk pesanan produk fisik <strong>#{$orderNumber}</strong> telah kami terima dan terverifikasi:";
            $ctaText          = 'Lihat Detail & Status Pesanan';
            $promptMsg        = "Penjual/UMKM sedang menyiapkan pesanan kamu. Kamu dapat memantau status pesanan dan rincian pengiriman melalui tombol di bawah ini:";
            $footerNote       = 'Ada pertanyaan terkait pengiriman atau produk fisik ini? Balas email ini aja, tim kami siap membantu!';
        } elseif ($hasService) {
            $subject          = "Pembayaran Berhasil! Pesanan Layanan Jasa #{$orderNumber} Terverifikasi | buyle.id";
            $badgeText        = 'LAYANAN JASA / KONSULTASI';
            $title            = 'Pembayaran Layanan Jasa Berhasil!';
            $subtitle         = "Pesanan layanan / konsultasi kamu sudah terverifikasi #{$orderNumber}";
            $introText        = "Halo <strong>{$buyerName}</strong>, terima kasih banyak telah menggunakan layanan di <strong>buyle.id</strong>. Pembayaran untuk layanan <strong>#{$orderNumber}</strong> telah kami terima. Penyedia jasa akan segera menghubungi kamu untuk pelaksanaan layanan / konsultasi:";
            $ctaText          = 'Lihat Detail Pesanan Jasa';
            $promptMsg        = "Kamu dapat melihat instruksi layanan dan menghubungi penyedia jasa melalui dashboard pembeli di bawah ini:";
            $footerNote       = 'Ada kendala atau pertanyaan terkait layanan jasa ini? Balas email ini aja, kami siap membantu!';
        } else {
            $subject          = "Pembayaran Berhasil! File Akses Produk Digital #{$orderNumber} Ready | buyle.id";
            $badgeText        = 'DIGITAL PRODUCT READY';
            $title            = 'Pembayaran Produk Digital Berhasil!';
            $subtitle         = "Produk digital kamu sudah siap diunduh dan dipelajari #{$orderNumber}";
            $introText        = "Halo <strong>{$buyerName}</strong>, terima kasih banyak telah berbelanja di <strong>buyle.id</strong>. Pembayaran kamu untuk produk digital <strong>#{$orderNumber}</strong> sudah terverifikasi:";
            $ctaText          = 'Unduh & Akses Produk Digital';
            $promptMsg        = "Klik tombol di bawah untuk langsung mengunduh file atau membuka tautan akses produk digital kamu:";
        }

        // Cari tautan langsung file / produk digital jika ada
        $digitalResourceUrl = null;
        foreach ($this->order->items as $item) {
            if ($item->product && !empty($item->product->digital_resource)) {
                $digitalResourceUrl = trim($item->product->digital_resource);
                break;
            }
        }

        $orderShowUrl = route('account.orders.show', $this->order->id);
        $primaryCtaUrl = ($ctaText === 'Unduh & Akses Produk Digital' && !empty($digitalResourceUrl)) 
            ? $digitalResourceUrl 
            : $orderShowUrl;

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
                'ctaUrl'           => $primaryCtaUrl,
                'ctaText'          => $ctaText,
                'secondaryCtaUrl'  => $orderShowUrl,
                'secondaryCtaText' => 'Lihat Riwayat Pesanan Saya',
                'footerNote'       => $footerNote,
            ]);
    }
}
