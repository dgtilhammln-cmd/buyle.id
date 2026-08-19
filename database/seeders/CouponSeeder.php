<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('coupons')->upsert([
            // ── PRODUCT / DISKON ──────────────────────────────────
            [
                'code'         => 'SELAMAT10',
                'category'     => 'product',
                'description'  => 'Diskon 10% untuk semua produk. Maks. Rp 50.000',
                'badge'        => 'Terbaru',
                'type'         => 'percentage',
                'value'        => 10.00,
                'min_purchase' => 100000,
                'max_discount' => 50000,
                'usage_limit'  => 100,
                'used_count'   => 0,
                'is_active'    => true,
                'started_at'   => null,
                'expired_at'   => '2026-12-31 23:59:59',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'code'         => 'HEMAT50K',
                'category'     => 'product',
                'description'  => 'Potongan langsung Rp 50.000 untuk pembelian di atas Rp 300.000',
                'badge'        => 'Populer',
                'type'         => 'fixed',
                'value'        => 50000,
                'min_purchase' => 300000,
                'max_discount' => null,
                'usage_limit'  => 50,
                'used_count'   => 0,
                'is_active'    => true,
                'started_at'   => null,
                'expired_at'   => '2026-12-31 23:59:59',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'code'         => 'FLASH25',
                'category'     => 'product',
                'description'  => 'Flash Sale! Diskon 25% maks. Rp 100.000. Hari ini saja!',
                'badge'        => 'Flash Sale',
                'type'         => 'percentage',
                'value'        => 25.00,
                'min_purchase' => 200000,
                'max_discount' => 100000,
                'usage_limit'  => 30,
                'used_count'   => 0,
                'is_active'    => true,
                'started_at'   => null,
                'expired_at'   => now()->addDays(1)->toDateTimeString(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            // ── MEMBER / LOYALTY ──────────────────────────────────
            [
                'code'         => 'MEMBER15',
                'category'     => 'member',
                'description'  => 'Khusus pelanggan setia! Diskon 15% tanpa batas pembelian minimum.',
                'badge'        => 'Member',
                'type'         => 'percentage',
                'value'        => 15.00,
                'min_purchase' => 0,
                'max_discount' => 75000,
                'usage_limit'  => null,
                'used_count'   => 0,
                'is_active'    => true,
                'started_at'   => null,
                'expired_at'   => '2026-12-31 23:59:59',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            // ── REFERRAL ──────────────────────────────────────────
            [
                'code'         => 'REFER20K',
                'category'     => 'referral',
                'description'  => 'Voucher referral dari teman. Dapatkan potongan Rp 20.000!',
                'badge'        => 'Referral',
                'type'         => 'fixed',
                'value'        => 20000,
                'min_purchase' => 100000,
                'max_discount' => null,
                'usage_limit'  => 1,
                'used_count'   => 0,
                'is_active'    => true,
                'started_at'   => null,
                'expired_at'   => '2026-12-31 23:59:59',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            // ── GRATIS ONGKIR (shipping) ──────────────────────────
            [
                'code'         => 'GRATISONGKIR',
                'category'     => 'shipping',
                'description'  => 'Gratis ongkos kirim! Potongan ongkir hingga Rp 30.000.',
                'badge'        => 'Gratis Ongkir',
                'type'         => 'fixed',
                'value'        => 30000,
                'min_purchase' => 150000,
                'max_discount' => null,
                'usage_limit'  => 200,
                'used_count'   => 0,
                'is_active'    => true,
                'started_at'   => null,
                'expired_at'   => '2026-12-31 23:59:59',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            // ── EVENT / SPECIAL ───────────────────────────────────
            [
                'code'         => 'HUT17AGUSTUS',
                'category'     => 'event',
                'description'  => 'Peringatan HUT RI ke-81! Diskon 17% untuk semua produk.',
                'badge'        => '17 Agustus',
                'type'         => 'percentage',
                'value'        => 17.00,
                'min_purchase' => 170000,
                'max_discount' => 170000,
                'usage_limit'  => 81,
                'used_count'   => 0,
                'is_active'    => true,
                'started_at'   => '2026-08-17 00:00:00',
                'expired_at'   => '2026-08-17 23:59:59',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ], ['code'], ['description', 'badge', 'category', 'value', 'min_purchase', 'max_discount', 'updated_at']);
    }
}
