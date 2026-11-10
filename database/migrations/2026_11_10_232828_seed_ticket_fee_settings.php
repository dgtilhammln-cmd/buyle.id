<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Seed setting fee default untuk produk tiket.
 *
 * Setting ini dibaca oleh CheckoutService saat menghitung fee tiket
 * ketika seller TIDAK memiliki custom fee (has_custom_fee = false).
 *
 * Nilai default:
 *   - ticket_platform_fee_rate  = 0  (% dari subtotal tiket)
 *   - ticket_admin_fee_per_qty  = 0  (Rp flat per tiket)
 *
 * Admin dapat mengubah nilai ini di Settings → Fee & Biaya
 * tanpa perlu deploy ulang.
 */
return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'key'   => 'ticket_platform_fee_rate',
                'value' => '0',
                'type'  => 'number',
                'group' => 'fee',
                'label' => 'Platform Fee Tiket (%)',
            ],
            [
                'key'   => 'ticket_admin_fee_per_qty',
                'value' => '0',
                'type'  => 'number',
                'group' => 'fee',
                'label' => 'Admin Fee Tiket (Rp flat/tiket)',
            ],
        ];

        foreach ($settings as $s) {
            DB::table('settings')->updateOrInsert(
                ['key' => $s['key']],
                [
                    'value'      => $s['value'],
                    'type'       => $s['type'],
                    'group'      => $s['group'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'ticket_platform_fee_rate',
            'ticket_admin_fee_per_qty',
        ])->delete();
    }
};
