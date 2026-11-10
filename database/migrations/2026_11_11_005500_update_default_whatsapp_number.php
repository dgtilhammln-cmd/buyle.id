<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;
use App\Models\WaSetting;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update atau pastikan WaSetting utama menggunakan nomor aktif resmi
        try {
            $wa = WaSetting::first();
            if ($wa) {
                $wa->update([
                    'nomor_wa'   => '085179982373',
                    'is_active'  => true,
                    'is_primary' => true,
                ]);
            } else {
                WaSetting::create([
                    'label'          => 'Admin Support Buyle',
                    'nomor_wa'       => '085179982373',
                    'template_pesan' => 'Halo CS Buyle, saya butuh bantuan.',
                    'is_active'      => true,
                    'is_primary'     => true,
                    'order'          => 1,
                ]);
            }

            // Ganti jika ada nomor dummy 081234567890 di record lain
            WaSetting::where('nomor_wa', 'like', '%81234567890%')
                ->orWhereNull('nomor_wa')
                ->orWhere('nomor_wa', '')
                ->update(['nomor_wa' => '085179982373']);
        } catch (\Throwable $e) {
            // Ignore jika tabel belum siap
        }

        // 2. Update Global Settings untuk contact & whatsapp
        try {
            Setting::set('contact.whatsapp', '6285179982373');
            Setting::set('contact.phone', '085179982373');
            Setting::set('whatsapp', '6285179982373');
            Setting::set('phone', '085179982373');
        } catch (\Throwable $e) {
            // Ignore
        }
    }

    public function down(): void
    {
        // No rollback needed
    }
};

