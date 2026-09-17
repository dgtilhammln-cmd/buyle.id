<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PromoSection;

class PromoSectionSeeder extends Seeder
{
    /**
     * Jalankan: php artisan db:seed --class=PromoSectionSeeder
     *
     * Membuat promo sections default secara otomatis.
     * Produk diambil dinamis berdasarkan tipe — tidak perlu input manual satu per satu.
     */
    public function run(): void
    {
        $sections = [
            // 1. E-Book & Produk Digital
            [
                'title'               => 'E-Book & Produk Digital',
                'subtitle'            => 'Akses instan, download langsung setelah bayar',
                'view_all_url'        => '/products?type=digital',
                'sort_order'          => 1,
                'is_active'           => true,
                'selection_type'      => 'product_type',
                'product_type_filter' => 'digital',
                'category_id'         => null,
                'sub_category_id'     => null,
                'bg_color_1'          => '#1D4ED8',
                'bg_color_2'          => '#7C3AED',
                'banner'              => null,
                'logo'                => null,
            ],
            // 2. Flash Sale (produk dengan harga diskon)
            [
                'title'               => 'Flash Sale Hari Ini',
                'subtitle'            => 'Harga spesial, stok terbatas — jangan sampai ketinggalan!',
                'view_all_url'        => '/products?promo=1',
                'sort_order'          => 2,
                'is_active'           => true,
                'selection_type'      => 'discount',
                'product_type_filter' => null,
                'category_id'         => null,
                'sub_category_id'     => null,
                'bg_color_1'          => '#EF4444',
                'bg_color_2'          => '#F97316',
                'banner'              => null,
                'logo'                => null,
            ],
            // 3. Jasa & Layanan
            [
                'title'               => 'Jasa & Layanan Profesional',
                'subtitle'            => 'Temukan jasa terpercaya dari kreator terbaik',
                'view_all_url'        => '/products?type=service',
                'sort_order'          => 3,
                'is_active'           => true,
                'selection_type'      => 'product_type',
                'product_type_filter' => 'service',
                'category_id'         => null,
                'sub_category_id'     => null,
                'bg_color_1'          => null,
                'bg_color_2'          => null,
                'banner'              => null,
                'logo'                => null,
            ],
            // 4. Tiket & Event
            [
                'title'               => 'Tiket & Event',
                'subtitle'            => 'Konser, workshop, dan event seru di sekitar kamu',
                'view_all_url'        => '/products?type=ticket',
                'sort_order'          => 4,
                'is_active'           => true,
                'selection_type'      => 'product_type',
                'product_type_filter' => 'ticket',
                'category_id'         => null,
                'sub_category_id'     => null,
                'bg_color_1'          => '#7C3AED',
                'bg_color_2'          => '#4F46E5',
                'banner'              => null,
                'logo'                => null,
            ],
            // 5. Semua Produk Pilihan
            [
                'title'               => 'Produk Pilihan Buyle',
                'subtitle'            => 'Kurasi terbaik dari seluruh penjual di platform kami',
                'view_all_url'        => '/products',
                'sort_order'          => 5,
                'is_active'           => true,
                'selection_type'      => 'all',
                'product_type_filter' => null,
                'category_id'         => null,
                'sub_category_id'     => null,
                'bg_color_1'          => null,
                'bg_color_2'          => null,
                'banner'              => null,
                'logo'                => null,
            ],
        ];

        foreach ($sections as $data) {
            // firstOrCreate: tidak menimpa jika judul sudah ada
            PromoSection::firstOrCreate(['title' => $data['title']], $data);
        }

        $this->command->info('PromoSectionSeeder done! ' . count($sections) . ' sections seeded.');
    }
}
