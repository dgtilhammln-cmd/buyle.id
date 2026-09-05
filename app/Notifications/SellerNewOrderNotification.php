<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerNewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
        $total = number_format($this->order->total_price, 0, ',', '.');

        $mail = (new MailMessage)
            ->subject("🛒 Pesanan Baru Masuk #{$orderNumber} | buyle.id")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Ada pesanan baru **#{$orderNumber}** yang telah berhasil dibayar oleh pembeli.")
            ->line("Total Transaksi: **Rp {$total}**");

        foreach ($this->order->items as $item) {
            $mail->line("• **{$item->product_name}** × {$item->qty}");
        }

        $mail->action('📊 Lihat Detail Pesanan di Creator Dashboard', route('creator.orders.index'))
             ->salutation('Salam sukses, Tim buyle.id 🙌');

        return $mail;
    }
}
