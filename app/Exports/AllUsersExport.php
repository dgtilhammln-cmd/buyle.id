<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AllUsersExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $search;
    protected $rowCount = 0;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'Username',
            'Email',
            'No. WhatsApp',
            'NIK / Identitas',
            'Status Akun',
            'Role',
            'Tipe Bisnis (Persona)',
            'Sumber Tahu Buyle',
            'Platform / Detail Sumber',
            'Media Partner',
            'Tujuan Utama (Goal)',
            'Kode Referral',
            'Direferensikan Oleh',
            'Status Onboarding',
            'Waktu Onboarding Selesai',
            'Total Order',
            'Total Transaksi (Rp)',
            'Total Tiket Dibeli',
            'Tanggal Terdaftar',
            'Login Google'
        ];
    }

    public function array(): array
    {
        $q = $this->search;
        $users = User::where('role', '!=', 'admin')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%");
                });
            })
            ->with(['referrer', 'ticketPasses', 'orders'])
            ->withCount(['orders', 'ticketPasses'])
            ->withSum('orders', 'total')
            ->latest()
            ->get();

        $personaLabels = [
            'digital_product'    => 'Produk Digital',
            'service_freelance'  => 'Jasa & Freelance',
            'fnb_resto'          => 'FnB & Resto / Kafe',
            'event_ticket'       => 'Event & Tiket',
            'wedding_invitation' => 'Undangan Digital & Wedding',
            'content_creator'    => 'Content Creator',
            'media_partner'      => 'Media Partner',
            'business_retail'    => 'Bisnis & Ritel',
        ];

        $goalLabels = [
            'sell_digital'    => 'Jual Produk Digital & Karya Kreatif',
            'web_builder'     => 'Bangun Website & Landing Page Profesional',
            'pos_cashier'     => 'Kasir POS & Menu Resto Digital',
            'event_ticketing' => 'Penjualan Tiket Konser & Manajemen Event',
            'digital_wedding' => 'Undangan Digital & Buku Tamu QR',
        ];

        $data = [];
        foreach ($users as $user) {
            $this->rowCount++;

            // Resolusi NIK
            $nik = $user->resolved_nik ?? $user->nik ?? '-';

            // Persona
            $persona = $personaLabels[$user->business_persona] ?? ($user->business_persona ?: '-');

            // Sumber Tahu
            $sourceType = match($user->referral_source_type) {
                'media_partner' => 'Media Partner',
                'social_media'  => 'Media Sosial',
                'word_of_mouth' => 'Teman / Rekomendasi',
                'ads'           => 'Iklan Digital',
                'search_engine' => 'Google / Pencarian',
                default         => $user->referral_source_type ?: '-'
            };

            $sourceName = $user->referral_source_name ?: '-';
            $mediaPartner = $user->media_partner_name ?: ($user->referral_source_type === 'media_partner' ? $sourceName : '-');

            // Goal
            $goal = $goalLabels[$user->primary_goal] ?? ($user->primary_goal ?: '-');

            // Referrer
            $referrerText = '-';
            if ($user->referrer) {
                $referrerText = $user->referrer->name . ' (' . $user->referrer->email . ')';
            } elseif ($user->referral_code) {
                $referrerText = 'Kode: ' . $user->referral_code;
            }

            // Status Onboarding
            $onboardingStatus = $user->onboarding_completed_at ? 'Selesai' : 'Belum Selesai';
            $onboardingTime = $user->onboarding_completed_at ? \Carbon\Carbon::parse($user->onboarding_completed_at)->format('d/m/Y H:i') : '-';

            // Transaksi
            $totalSpending = (float) ($user->orders_sum_total ?? 0);

            $data[] = [
                $user->id,
                $user->name,
                $user->username ? '@' . $user->username : '-',
                $user->email,
                $user->phone ? "'" . $user->phone : '-',
                $nik !== '-' ? "'" . $nik : '-',
                ($user->is_active ?? true) ? 'Aktif' : 'Nonaktif',
                ucfirst($user->role ?? 'Buyer'),
                $persona,
                $sourceType,
                $sourceName,
                $mediaPartner,
                $goal,
                $user->referral_code ?: '-',
                $referrerText,
                $onboardingStatus,
                $onboardingTime,
                $user->orders_count ?? 0,
                $totalSpending,
                $user->ticket_passes_count ?? 0,
                $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-',
                $user->google_id ? 'Ya' : 'Tidak'
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1EB349'], // Buyle primary green
                ],
            ],
        ];
    }
}
