<?php

namespace App\Notifications;

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
        $name      = $notifiable->name ?? 'Teman buyle.id';
        $isGoogle  = !empty($notifiable->google_id);

        if ($isGoogle) {
            // Registrasi via Google - tidak perlu info password
            $loginNote = '
                <div style="background-color: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; padding: 14px 16px; margin-top: 16px; font-size: 13px; color: #065F46;">
                    <strong>Masuk kapan aja pakai Google kamu</strong><br>
                    Karena kamu daftar lewat Google, cukup klik tombol "Lanjutkan dengan Google" saat login - tidak perlu password sama sekali.
                </div>';
        } else {
            // Registrasi manual dengan email + password
            $loginNote = '
                <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 16px; margin-top: 16px; font-size: 13px; color: #475569;">
                    <strong>Lupa kata sandi nanti?</strong><br>
                    Tenang, kamu bisa reset kapan aja lewat halaman <a href="' . url('/lupa-password') . '" style="color: #10B981; font-weight: 600; text-decoration: none;">Lupa Kata Sandi</a>. Tautan reset akan dikirim langsung ke email ini.
                </div>';
        }

        return (new MailMessage)
            ->subject('Selamat Datang di buyle.id! Yuk Mulai Eksplor')
            ->view('emails.layout', [
                'subject'    => 'Selamat Datang di buyle.id! Yuk Mulai Eksplor',
                'title'      => 'Selamat Datang di buyle.id!',
                'subtitle'   => 'Akun kamu sudah aktif di Digital Creator Platform-nya Indonesia',
                'content'    => "
                    <p>Halo <strong>{$name}</strong>,</p>
                    <p>Senang banget kamu resmi jadi bagian dari <strong>buyle.id</strong>!</p>
                    <p>Kamu sekarang punya akses penuh ke platform kreator digital Indonesia - dari beli produk creator favorit, bikin Link in Bio, sampai support kreator lewat fitur Traktir.</p>
                    {$loginNote}
                    <p style='margin-top: 16px;'>Yuk klik tombol di bawah buat mulai eksplor:</p>
                ",
                'ctaUrl'     => url('/'),
                'ctaText'    => 'Mulai Eksplor buyle.id Now',
                'footerNote' => 'Ada pertanyaan atau kendala? Balas aja email ini, tim buyle.id siap bantu kamu kapan aja!',
            ]);
    }
}
