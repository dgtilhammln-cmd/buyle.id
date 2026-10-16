<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutRequestedNotification extends Notification
{
    public function __construct(
        protected float|int $amount,
        protected string $bankName,
        protected string $bankAccountNumber
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amountFormatted = 'Rp ' . number_format($this->amount, 0, ',', '.');
        $creatorName = $notifiable->name ?? 'Creator';

        return (new MailMessage)
            ->subject("Pengajuan Pencairan Saldo {$amountFormatted} Sedang Diproses | buyle.id")
            ->view('emails.layout', [
                'subject'   => "Pengajuan Pencairan Saldo {$amountFormatted} Sedang Diproses | buyle.id",
                'badgeText' => 'PENCAIRAN SALDO',
                'title'     => 'Permintaan Pencairan Saldo Diproses',
                'subtitle'  => 'Permintaan penarikan dana kamu telah berhasil diterima oleh sistem buyle.id.',
                'content'   => "
                    <p>Halo <strong>{$creatorName}</strong>,</p>
                    <p>Permintaan pencairan dana hasil penjualan kamu sebesar <strong>{$amountFormatted}</strong> telah diterima dan sedang dalam antrean verifikasi tim keuangan kami.</p>
                    
                    <div style=\"background-color: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 16px; margin: 20px 0;\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"font-size: 14px; color: #334155;\">
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Nominal Pengajuan:</td>
                                <td style=\"padding: 6px 0; font-weight: 700; text-align: right; color: #0F172A;\">{$amountFormatted}</td>
                            </tr>
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Biaya Penarikan:</td>
                                <td style=\"padding: 6px 0; font-weight: 700; text-align: right; color: #16A34A;\">Gratis (Rp 0)</td>
                            </tr>
                            <tr style=\"border-top: 1.5px solid #E2E8F0;\">
                                <td style=\"padding: 10px 0 6px; font-weight: 800; color: #0F172A;\">Dana Diterima (100%):</td>
                                <td style=\"padding: 10px 0 6px; font-weight: 800; text-align: right; color: #16A34A; font-size: 16px;\">{$amountFormatted}</td>
                            </tr>
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Rekening Tujuan:</td>
                                <td style=\"padding: 6px 0; font-weight: 600; text-align: right; color: #0F172A;\">{$this->bankName} ({$this->bankAccountNumber})</td>
                            </tr>
                        </table>
                    </div>

                    <p>Dana kamu akan ditransfer 100% tanpa potongan biaya dalam waktu 1 sampai 3 hari kerja.</p>
                ",
                'ctaUrl'    => route('creator.payout.settings'),
                'ctaText'   => 'Lihat Status Pencairan',
                'footerNote' => 'Pencairan saldo di buyle.id 100% utuh tanpa potongan biaya administrasi.',
            ]);
    }
}
