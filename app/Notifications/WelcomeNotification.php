<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $notifiable->name ?? 'Pengguna';

        $bodyHtml = view('emails.layout', [
            'subject'   => 'Selamat Datang di buyle.id',
            'badgeText' => 'AKUN RESMI',
            'title'     => 'Selamat Datang di buyle.id',
            'subtitle'  => 'Akun Anda telah berhasil terdaftar dan siap digunakan',
            'content'   => "
                <p>Halo <strong>{$name}</strong>,</p>
                <p>Terima kasih telah bergabung dengan platform <strong>buyle.id</strong>!</p>
                <p>Akun Anda telah aktif secara penuh. Anda sekarang dapat menelusuri katalog produk digital, membeli layanan pilihan, atau mengelola toko creator Anda secara langsung.</p>
                <p style='margin-top: 20px;'>Klik tombol di bawah ini untuk mulai menjelajahi platform:</p>
            ",
            'ctaUrl'    => url('/'),
            'ctaText'   => 'Mulai Jelajahi buyle.id',
            'footerNote' => 'Jika Anda membutuhkan bantuan teknis atau informasi layanan, tim dukungan kami selalu siap membantu Anda.',
        ])->render();

        return (new MailMessage)
            ->subject('Selamat Datang di buyle.id')
            ->html($bodyHtml);
    }
}
