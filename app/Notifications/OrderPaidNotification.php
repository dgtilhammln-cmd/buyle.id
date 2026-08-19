<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

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

        $mail = (new MailMessage)
            ->subject("✅ Pembayaran Berhasil — {$orderNumber} | buyle.id")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Pembayaran untuk pesanan **{$orderNumber}** telah kami terima. Terima kasih sudah berbelanja di buyle.id!");

        // Tampilkan daftar produk yang dibeli
        foreach ($this->order->items as $item) {
            $mail->line("• **{$item->product_name}** × {$item->qty}");
        }

        $mail->line('---');

        // Tombol akses produk digital
        $mail->action('🚀 Akses Produk Sekarang', route('buyer.orders.show', $this->order->id));

        if ($this->isNewAccount) {
            $mail->line('---')
                 ->line('💡 **Akun buyle.id Anda telah dibuat secara otomatis** menggunakan email ini.')
                 ->line('Gunakan tautan di bawah untuk masuk ke Buyer Dashboard Anda — tanpa perlu password!')
                 ->action('🔑 Masuk ke Dashboard Saya', $this->magicLoginUrl)
                 ->line('Tautan login ini hanya berlaku selama **24 jam**.');
        } else {
            $mail->line('Anda dapat mengakses riwayat pembelian di Buyer Dashboard.');
            $mail->action('📦 Lihat Riwayat Pesanan', route('buyer.orders.index'));
        }

        $mail->line('---')
             ->line('Jika ada pertanyaan, hubungi kami via WhatsApp atau balas email ini.')
             ->salutation('Salam hangat, Tim buyle.id 🙌');

        return $mail;
    }
}
