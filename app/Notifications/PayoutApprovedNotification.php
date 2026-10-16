<?php

namespace App\Notifications;

use App\Models\PayoutRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutApprovedNotification extends Notification
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
        $netAmountFormatted = 'Rp ' . number_format($this->payout->net_amount, 0, ',', '.');
        $creatorName = $notifiable->name ?? 'Creator';
        $refText = $this->payout->reference_number ? "No. Referensi: {$this->payout->reference_number}" : "Pencairan Berhasil";

        return (new MailMessage)
            ->subject("Pencairan Saldo {$netAmountFormatted} Berhasil Ditransfer | buyle.id")
            ->view('emails.layout', [
                'subject'   => "Pencairan Saldo {$netAmountFormatted} Berhasil Ditransfer | buyle.id",
                'badgeText' => 'TRANSFER BERHASIL',
                'title'     => 'Dana Pencairan Telah Ditransfer',
                'subtitle'  => 'Pencairan saldo kamu sebesar 100% utuh telah berhasil dikirimkan.',
                'content'   => "
                    <p>Halo <strong>{$creatorName}</strong>,</p>
                    <p>Kabar gembira! Permintaan pencairan dana kamu sebesar <strong>{$netAmountFormatted}</strong> telah berhasil ditransfer oleh tim buyle.id langsung ke rekening tujuan kamu.</p>
                    
                    <div style=\"background-color: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 16px; margin: 20px 0;\">
                        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"font-size: 14px; color: #334155;\">
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Total Ditransfer:</td>
                                <td style=\"padding: 6px 0; font-weight: 800; text-align: right; color: #16A34A; font-size: 16px;\">{$netAmountFormatted}</td>
                            </tr>
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Potongan Biaya:</td>
                                <td style=\"padding: 6px 0; font-weight: 700; text-align: right; color: #16A34A;\">Rp 0 (100% Utuh)</td>
                            </tr>
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Rekening Tujuan:</td>
                                <td style=\"padding: 6px 0; font-weight: 600; text-align: right; color: #0F172A;\">{$this->payout->bank_name}</td>
                            </tr>
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Nomor Rekening:</td>
                                <td style=\"padding: 6px 0; font-weight: 600; text-align: right; color: #0F172A;\">{$this->payout->bank_account_number}</td>
                            </tr>
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">Atas Nama:</td>
                                <td style=\"padding: 6px 0; font-weight: 600; text-align: right; color: #0F172A;\">{$this->payout->bank_account_name}</td>
                            </tr>
                            " . ($this->payout->reference_number ? "
                            <tr>
                                <td style=\"padding: 6px 0; color: #64748B;\">No. Referensi:</td>
                                <td style=\"padding: 6px 0; font-weight: 600; text-align: right; color: #0F172A;\">{$this->payout->reference_number}</td>
                            </tr>" : "") . "
                        </table>
                    </div>

                    <p>Silakan periksa mutasi rekening bank atau saldo e-wallet kamu. Terima kasih atas karya dan kontribusi kamu di buyle.id!</p>
                ",
                'ctaUrl'    => route('creator.payout.settings'),
                'ctaText'   => 'Cek Riwayat Pencairan',
                'footerNote' => 'Jika dana belum masuk dalam 1x24 jam perbankan, hubungi tim support buyle.id dengan menyertakan detail penarikan ini.',
            ]);
    }
}
