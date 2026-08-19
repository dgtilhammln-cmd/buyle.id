<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class BuyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Truncate Data buyle.id
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // for mysql
        // But since we are using SQLite, we can just delete:
        DB::table('clients')->delete();
        DB::table('testimonials')->delete();
        DB::table('gallery_projects')->delete();
        DB::table('articles')->delete();
        DB::table('hero_slides')->delete();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Set Data Baru untuk buyle.id
        $settings = [
            // Identitas Utama
            'site_name'    => 'buyle.id',
            'domain'       => 'buyle.id',
            'tagline'      => 'Semua Kebutuhan Rumah, Satu Tempat',
            'short_desc'   => 'Toko online buyle.id tangga terlengkap. Menjual produk berkualitas dan menyediakan jasa pemasangan serta servis untuk kebutuhan hunian Anda.',
            'established'  => '',
            'business_type'=> 'Toko online & Jasa',

            // Kontak & Lokasi
            'contact.phone'    => '',
            'contact.whatsapp' => '',
            'contact.email'    => '',
            'contact.address'  => '',
            'contact.city'     => 'Jakarta',
            'contact.province' => 'DKI Jakarta',

            // SEO Default
            'meta_title_home' => 'Beranda | buyle.id - Toko buyle.id Tangga Online',
            'meta_desc_home'  => 'Belanja buyle.id tangga berkualitas di buyle.id. Produk lengkap, harga terjangkau, pengiriman ke seluruh Indonesia.',
            'meta_keywords_home' => 'buyle.id tangga, peralatan rumah, toko buyle.id online, jasa pemasangan',

            'meta_title_about' => 'Tentang Kami | buyle.id',
            'meta_desc_about'  => 'Profil buyle.id, toko online buyle.id tangga terlengkap. Melayani seluruh Indonesia.',
            'meta_keywords_about' => 'tentang buyle.id, profil toko, toko buyle.id',

            'meta_title_products' => 'Produk & Layanan | buyle.id',
            'meta_desc_products'  => 'Temukan berbagai produk buyle.id tangga berkualitas dan layanan jasa profesional di buyle.id.',
            'meta_keywords_products' => 'produk rumah tangga, jual buyle.id, layanan servis, jasa pasang',

            'meta_title_articles' => 'Artikel & Tips | buyle.id',
            'meta_desc_articles'  => 'Kumpulan artikel, tips, dan panduan merawat buyle.id tangga.',
            'meta_keywords_articles' => 'tips rumah tangga, artikel rumah, panduan produk',

            'meta_title_contact' => 'Hubungi Kami | buyle.id',
            'meta_desc_contact'  => 'Hubungi buyle.id untuk konsultasi produk, pemesanan, dan layanan jasa.',
            'meta_keywords_contact' => 'kontak buyle.id, hubungi toko',

            'meta_title_gallery' => 'Galeri | buyle.id',
            'meta_desc_gallery'  => 'Dokumentasi produk dan instalasi buyle.id tangga dari buyle.id.',
            'meta_keywords_gallery' => 'galeri buyle.id, instalasi',
        ];

        // Kosongkan tabel settings lama
        DB::table('settings')->delete();

        // Insert new settings
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->command->info('Rebrand ke buyle.id berhasil diselesaikan!');
    }
}
