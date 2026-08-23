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
            'tagline'      => 'Digital Creator Marketplace',
            'short_desc'   => 'buyle.id adalah digital creator marketplace terlengkap. Menjual produk digital, layanan jasa, dan aset kreatif berkualitas.',
            'established'  => '',
            'business_type'=> 'Marketplace & Digital Service',

            // Kontak & Lokasi
            'contact.phone'    => '',
            'contact.whatsapp' => '',
            'contact.email'    => '',
            'contact.address'  => '',
            'contact.city'     => 'Surabaya',
            'contact.province' => 'Jawa Timur',

            // SEO Default
            'meta_title_home' => 'Beranda | buyle.id - Digital Creator Marketplace',
            'meta_desc_home'  => 'Belanja aset digital berkualitas di buyle.id. Produk lengkap, transaksi aman, akses instan, karya kreator Indonesia.',
            'meta_keywords_home' => 'buyle.id, digital marketplace, produk digital, creator, jual beli digital',

            'meta_title_about' => 'Tentang Kami | buyle.id',
            'meta_desc_about'  => 'Profil buyle.id, marketplace produk digital dan jasa freelancer terpercaya di Indonesia.',
            'meta_keywords_about' => 'tentang buyle.id, profil platform, marketplace digital',

            'meta_title_products' => 'Produk & Layanan | buyle.id',
            'meta_desc_products'  => 'Temukan berbagai produk digital dan layanan jasa profesional dari para kreator di buyle.id.',
            'meta_keywords_products' => 'produk digital, jual aset digital, jasa digital, jasa freelancer',

            'meta_title_articles' => 'Blog & Info | buyle.id',
            'meta_desc_articles'  => 'Kumpulan artikel, tips bisnis digital, dan info terbaru seputar ekosistem kreator.',
            'meta_keywords_articles' => 'artikel bisnis, tips digital, blog creator',

            'meta_title_contact' => 'Hubungi Kami | buyle.id',
            'meta_desc_contact'  => 'Hubungi tim dukungan buyle.id untuk bantuan transaksi dan pertanyaan seputar marketplace.',
            'meta_keywords_contact' => 'kontak buyle.id, hubungi tim support, cs digital',

            'meta_title_gallery' => 'Katalog Kreator | buyle.id',
            'meta_desc_gallery'  => 'Katalog karya terbaik dari para kreator berbakat di platform buyle.id.',
            'meta_keywords_gallery' => 'katalog digital, karya kreator, portofolio',
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
