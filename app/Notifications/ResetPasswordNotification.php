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

        $name = $notifiable->name ?? 'Pengguna';

        return (new MailMessage)
            ->subject('Reset Kata Sandi Anda - buyle.id')
            ->view('emails.layout', [
                'subject'     => 'Reset Kata Sandi Anda - buyle.id',
                'badgeText'   => 'KEAMANAN AKUN',
                'title'       => 'Reset Kata Sandi',
                'subtitle'    => 'Permintaan perubahan kata sandi akun buyle.id',
                'content'     => "
                    <p>Halo <strong>{$name}</strong>,</p>
                    <p>Kami menerima permintaan untuk mengatur ulang kata sandi akun buyle.id yang terhubung dengan email ini.</p>
                    <p>Silakan klik tombol hijau di bawah ini untuk melanjutkan pembuatan kata sandi baru:</p>
                ",
                'ctaUrl'      => $resetUrl,
                'ctaText'     => 'Reset Kata Sandi Sekarang',
                'footerNote'  => 'Tautan reset ini berlaku selama <strong>60 menit</strong>. Jika Anda tidak merasa meminta ini, Anda dapat mengabaikan email ini dengan aman.',
            ]);
    }
}
