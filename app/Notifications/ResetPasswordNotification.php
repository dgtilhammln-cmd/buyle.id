<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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

        return (new MailMessage)
            ->subject('🔐 Reset Password Anda — buyle.id')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Kami menerima permintaan untuk mereset kata sandi akun buyle.id Anda.')
            ->action('🔑 Reset Kata Sandi Sekarang', $resetUrl)
            ->line('Tautan reset password ini hanya berlaku selama **60 menit**.')
            ->line('Jika Anda tidak merasa meminta reset password, abaikan pesan email ini.')
            ->salutation('Salam hangat, Tim buyle.id 🙌');
    }
}
