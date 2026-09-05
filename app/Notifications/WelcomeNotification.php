<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 Selamat Datang di buyle.id!')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Selamat datang dan terima kasih telah mendaftar di **buyle.id**!')
            ->line('Akun Anda telah aktif dan siap digunakan untuk berbelanja produk digital, karya kreatif, dan layanan menarik.')
            ->action('🚀 Jelajahi buyle.id', url('/'))
            ->line('Jika Anda membutuhkan bantuan, jangan ragu untuk menghubungi tim dukungan kami.')
            ->salutation('Salam hangat, Tim buyle.id 🙌');
    }
}
