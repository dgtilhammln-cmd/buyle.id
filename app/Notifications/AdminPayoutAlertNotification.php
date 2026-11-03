<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPayoutAlertNotification extends Notification
{
    public function __construct(
        protected User $seller,
        protected float|int $amount,
        protected string $bankName,
        protected string $bankAccountNumber,
        protected string $bankAccountName
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amountFormatted = 'Rp ' . number_format($this->amount, 0, ',', '.');
        $creatorName     = $this->seller->name ?? 'Creator';

        return (new MailMessage)
            ->subject("[PERMINTAAN PENCAIRAN] {$creatorName} menarik {$amountFormatted} | buyle.id")
            ->view('emails.layout', [
                'subject'   => "[PERMINTAAN PENCAIRAN] {$creatorName} menarik {$amountFormatted} | buyle.id",
                'badgeText' => 'PENCAIRAN DANA CREATOR',
                'title'     => 'Permintaan Pencairan Saldo Baru',
                'subtitle'  => 'Ada creator yang mengajukan penarikan dana penjualan dan membutuhkan proses transfer.',
                'content'   => "
                    <p>Halo <strong>Tim Keuangan & Admin buyle.id</strong>,</p>
                    <p>Creator <strong>{$creatorName}</strong> ({$this->seller->email}) baru saja mengajukan penarikan dana sebesar <strong>{$amountFormatted}</strong>.</p>
                    
                    <div style=\"background-color: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 16px; margin: 20px 0;\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"font-size: 14px; color: #334155;\">
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Nama Creator:</td>
                                <td style=\"padding: 6px 0; font-weight: 700; text-align: right; color: #0F172A;\">{$creatorName}</td>
                            </tr>
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Nominal Dicairkan:</td>
                                <td style=\"padding: 6px 0; font-weight: 800; text-align: right; color: #16A34A; font-size: 16px;\">{$amountFormatted}</td>
                            </tr>
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Bank / E-Wallet:</td>
                                <td style=\"padding: 6px 0; font-weight: 700; text-align: right; color: #0F172A;\">{$this->bankName}</td>
                            </tr>
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">No. Rekening:</td>
                                <td style=\"padding: 6px 0; font-weight: 700; text-align: right; color: #0F172A;\">{$this->bankAccountNumber}</td>
                            </tr>
                            <tr style=\"border-top: 1.5px solid #E2E8F0;\">
                                <td style=\"padding: 10px 0 6px; color: #64748B;\">Atas Nama (A/N):</td>
                                <td style=\"padding: 10px 0 6px; font-weight: 700; text-align: right; color: #0F172A;\">{$this->bankAccountName}</td>
                            </tr>
                        </table>
                    </div>

                    <p>Silakan verifikasi saldo dan proses transfer dana ke rekening tujuan melalui menu Payout Admin buyle.id.</p>
                ",
                'ctaUrl'    => route('admin.payouts.index'),
                'ctaText'   => 'Buka Manajemen Payout',
                'footerNote' => 'Notifikasi otomatis sistem buyle.id ketika creator mengajukan pencairan dana.',
            ]);
    }
}
