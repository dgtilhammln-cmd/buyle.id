<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class UpdateSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update DB settings for digital creator marketplace theme';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = [
            'tagline'      => 'Digital Creator Marketplace',
            'short_desc'   => 'buyle.id adalah digital creator marketplace terlengkap. Menjual produk digital, layanan jasa, dan aset kreatif berkualitas.',
            'business_type'=> 'Marketplace & Digital Service',
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

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        $this->info('Settings updated successfully!');
    }
}
