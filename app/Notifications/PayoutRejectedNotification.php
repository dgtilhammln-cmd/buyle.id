<?php

namespace App\Notifications;

use App\Models\PayoutRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutRejectedNotification extends Notification
{
    public function __construct(
        protected PayoutRequest $payout
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amountFormatted = 'Rp ' . number_format($this->payout->amount, 0, ',', '.');
        $creatorName = $notifiable->name ?? 'Creator';
        $reason = $this->payout->admin_notes ?: 'Informasi rekening tidak sesuai atau tidak valid.';

        return (new MailMessage)
            ->subject("Pengajuan Pencairan Saldo {$amountFormatted} Ditolak | buyle.id")
            ->view('emails.layout', [
                'subject'   => "Pengajuan Pencairan Saldo {$amountFormatted} Ditolak | buyle.id",
                'badgeText' => 'STATUS PENCAIRAN',
                'title'     => 'Pengajuan Pencairan Saldo Ditolak',
                'subtitle'  => 'Saldo kamu telah dikembalikan secara utuh 100% ke akun creator.',
                'content'   => "
                    <p>Halo <strong>{$creatorName}</strong>,</p>
                    <p>Pengajuan pencairan saldo kamu sebesar <strong>{$amountFormatted}</strong> belum dapat disetujui oleh tim kami dengan alasan sebagai berikut:</p>
                    
                    <div style=\"background-color: #FEF2F2; border: 1.5px solid #FECACA; border-radius: 14px; padding: 16px; margin: 20px 0; color: #991B1B;\">
                        <div style=\"font-weight: 700; margin-bottom: 6px; font-size: 13px; text-transform: uppercase;\">Alasan Penolakan:</div>
                        <div style=\"font-size: 14px; line-height: 1.6; color: #B91C1C;\">{$reason}</div>
                    </div>

                    <div style=\"background-color: #F0FDF4; border: 1.5px solid #BBF7D0; border-radius: 14px; padding: 14px 16px; margin-bottom: 20px;\">
                        <p style=\"margin: 0; font-size: 14px; color: #166534; font-weight: 600;\">
                            Saldo sebesar {$amountFormatted} telah dikembalikan secara otomatis 100% ke saldo siap ditarik kamu.
                        </p>
                    </div>

                    <p>Silakan periksa kembali informasi rekening atau e-wallet kamu di halaman pengaturan sebelum mengajukan kembali.</p>
                ",
                'ctaUrl'    => route('creator.payout.settings'),
                'ctaText'   => 'Perbarui Rekening & Ajukan Ulang',
                'footerNote' => 'Jika kamu membutuhkan bantuan lebih lanjut, silakan hubungi tim bantuan buyle.id.',
            ]);
    }
}
