<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpVerificationNotification extends Notification
{
    public function __construct(
        protected string $otpCode,
        protected string $name = 'Teman buyle.id'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $this->name;
        $otp  = $this->otpCode;

        $otpBox = '
            <div style="text-align: center; margin: 24px 0;">
                <div style="display: inline-block; background-color: #F0FDF4; border: 2px dashed #10B981; border-radius: 12px; padding: 18px 36px;">
                    <span style="font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #047857; font-family: monospace;">' . $otp . '</span>
                </div>
            </div>';

        return (new MailMessage)
            ->subject('Kode OTP Verifikasi Akun Kamu | buyle.id')
            ->view('emails.layout', [
                'subject'    => 'Kode OTP Verifikasi Akun Kamu | buyle.id',
                'title'      => 'Kode Verifikasi Pendaftaran',
                'subtitle'   => 'Gunakan kode 6 digit di bawah ini untuk menyelesaikan pendaftaran akun kamu.',
                'content'    => "
                    <p>Halo <strong>{$name}</strong>,</p>
                    <p>Terima kasih sudah mendaftar di <strong>buyle.id</strong>. Berikut adalah kode verifikasi OTP kamu:</p>
                    {$otpBox}
                    <p style='text-align: center; color: #DC2626; font-weight: 600; font-size: 13px; display: flex; align-items: center; justify-content: center; gap: 6px;'>
                        <svg width='16' height='16' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><circle cx='12' cy='12' r='10'/><polyline points='12 6 12 12 16 14'/></svg>
                        <span>Kode ini hanya berlaku selama <strong>1 menit</strong> (60 detik).</span>
                    </p>
                    <div style='background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 12px 16px; margin-top: 16px; font-size: 12px; color: #64748B;'>
                        <div style='display: flex; align-items: center; gap: 6px; font-weight: 600; color: #334155; margin-bottom: 4px;'>
                            <svg width='14' height='14' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><rect x='3' y='11' width='18' height='11' rx='2' ry='2'/><path d='M7 11V7a5 5 0 0 1 10 0v4'/></svg>
                            <span>Tips Keamanan</span>
                        </div>
                        Jangan bagikan kode OTP ini kepada siapa pun, termasuk pihak yang mengatasnamakan buyle.id.
                    </div>
                ",
                'footerNote' => 'Jika kamu tidak merasa melakukan pendaftaran ini, silakan abaikan email ini secara aman.',
            ]);
    }
}
