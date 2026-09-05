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
        $name = $notifiable->name ?? 'Teman buyle.id';

        return (new MailMessage)
            ->subject('Selamat Datang di buyle.id! Yuk Mulai Eksplor')
            ->view('emails.layout', [
                'subject'   => 'Selamat Datang di buyle.id! Yuk Mulai Eksplor',
                'title'     => 'Selamat Datang di buyle.id!',
                'subtitle'  => 'Akun kamu sudah aktif di Digital Creator Platform-nya Indonesia',
                'content'   => "
                    <p>Halo <strong>{$name}</strong>,</p>
                    <p>Senang banget kamu resmi jadi bagian dari <strong>buyle.id</strong>!</p>
                    <p>Kamu sekarang punya akses penuh ke platform kreator digital Indonesia - dari beli produk creator favorit, bikin Link in Bio, sampai support kreator lewat fitur Traktir.</p>
                    <p style='margin-top: 16px;'>Yuk klik tombol di bawah buat mulai eksplor:</p>
                ",
                'ctaUrl'    => url('/'),
                'ctaText'   => 'Mulai Eksplor buyle.id Now',
                'footerNote' => 'Ada pertanyaan atau kendala? Balas aja email ini, tim buyle.id siap bantu kamu kapan aja!',
            ]);
    }
}
