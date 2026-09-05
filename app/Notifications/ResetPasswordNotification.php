<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public function __construct(
        protected string $token,
        protected string $email
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $this->email,
        ]);

        $name = $notifiable->name ?? 'Teman buyle.id';

        return (new MailMessage)
            ->subject('Yuk Reset Kata Sandi Kamu | buyle.id')
            ->view('emails.layout', [
                'subject'     => 'Yuk Reset Kata Sandi Kamu | buyle.id',
                'badgeText'   => 'KEAMANAN AKUN',
                'title'       => 'Yuk Atur Kata Sandi Baru Kamu',
                'subtitle'    => 'Minta reset kata sandi? Tenang, kamu bisa buat yang baru di sini',
                'content'     => "
                    <p>Halo <strong>{$name}</strong>,</p>
                    <p>Kami menerima permintaan untuk mereset kata sandi akun buyle.id kamu. Kalau ini beneran kamu, langsung klik tombol hijau di bawah ya buat bikin kata sandi yang baru!</p>
                    <p>Prosesnya instan dan dijamin aman kok.</p>
                ",
                'ctaUrl'      => $resetUrl,
                'ctaText'     => 'Buat Kata Sandi Baru Sekarang',
                'footerNote'  => 'Tautan reset ini berlaku selama <strong>60 menit</strong> ya. Kalau kamu tidak merasa minta reset, abaikan aja email ini dengan santai.',
            ]);
    }
}
