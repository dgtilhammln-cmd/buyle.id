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
                'badgeText' => 'WELCOME ABOARD',
                'title'     => 'Selamat Datang di buyle.id!',
                'subtitle'  => 'Akun kamu sudah siap 100% buat belanja dan berkreasi',
                'content'   => "
                    <p>Halo <strong>{$name}</strong>,</p>
                    <p>Senang banget kamu resmi jadi bagian dari <strong>buyle.id</strong>!</p>
                    <p>Sekarang akun kamu sudah aktif sepenuhnya. Kamu bisa langsung nyari produk digital favorit, layanan keren, atau mulai buka toko creator kamu sendiri dalam hitungan menit.</p>
                    <p style='margin-top: 16px;'>Yuk klik tombol di bawah buat mulai petualangan kamu:</p>
                ",
                'ctaUrl'    => url('/'),
                'ctaText'   => 'Mulai Eksplor buyle.id Now',
                'footerNote' => 'Ada pertanyaan atau kendala? Balas aja email ini, tim buyle.id siap bantu kamu kapan aja!',
            ]);
    }
}
