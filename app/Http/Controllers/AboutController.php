<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Client;

class AboutController extends Controller
{
    public function index()
    {
        $settings     = Setting::getAllAsArray();
        $testimonials = Testimonial::active()->ordered()->get()->unique('name');
        $clients      = Client::active()->ordered()->get();
        $wa           = \App\Models\WaSetting::primary();

        $siteName = $settings['site_name'] ?? 'buyle.id';

        $seo = [
            'title'       => $settings['meta_title_about'] ?? 'Tentang Kami | ' . $siteName,
            'description' => $settings['meta_desc_about'] ?? 'Profil ' . $siteName . ', toko online buyle.id tangga terlengkap. Melayani seluruh Indonesia.',
            'keywords'    => $settings['meta_keywords_about'] ?? 'tentang buyle.id, profil toko, toko online buyle.id tangga',
            'og_image'    => !empty($settings['og_image_default']) ? asset('storage/'.$settings['og_image_default']) : (!empty($settings['logo']) ? asset('storage/'.$settings['logo']) : asset('images/og-default.jpg')),
            'canonical'   => route('about'),
        ];

        $keunggulan = [
            ['icon' => 'star',     'title' => 'Produk Berkualitas',  'desc' => 'Semua produk dipilih dari merek terpercaya dengan kualitas terjamin'],
            ['icon' => 'shield',   'title' => 'Bergaransi',          'desc' => 'Layanan purna jual dan garansi resmi untuk setiap produk'],
            ['icon' => 'tag',      'title' => 'Harga Terjangkau',    'desc' => 'Harga kompetitif tanpa mengorbankan kualitas produk'],
            ['icon' => 'map',      'title' => 'Jangkauan Nasional',  'desc' => 'Melayani pengiriman ke seluruh wilayah Indonesia'],
            ['icon' => 'clock',    'title' => 'Pengiriman Tepat Waktu', 'desc' => 'Komitmen pengiriman sesuai estimasi waktu yang diberikan'],
            ['icon' => 'hard-hat', 'title' => 'Jasa Profesional',    'desc' => 'Tim teknisi berpengalaman untuk layanan pemasangan dan servis'],
        ];

        $legalitas = [
            ['label' => 'NIB',  'value' => $settings['nib']  ?? '-'],
            ['label' => 'NPWP', 'value' => $settings['npwp'] ?? '-'],
        ];

        return view('about.index', compact('settings', 'testimonials', 'clients', 'seo', 'keunggulan', 'legalitas', 'wa'));
    }
}
