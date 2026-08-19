<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SeoSeeder extends Seeder
{
    public function run(): void
    {
        $seo = [
            'home' => [
                'title' => 'CV. Karya Perdana Teknik | Hoist Crane & Cargo Lift Specialist',
                'desc' => 'Spesialis manufaktur, instalasi, dan maintenance Overhead Crane, Chain Hoist, Wire Rope Hoist, Gantry Crane & Cargo Lift di Jawa Timur dan seluruh Indonesia.',
                'keywords' => 'hoist crane surabaya, overhead crane gresik, jual crane indonesia, cargo lift sidoarjo, pabrik gantry crane, maintenance hoist crane, spesialis angkat angkut industri'
            ],
            'about' => [
                'title' => 'Tentang Kami | Profil CV. Karya Perdana Teknik Gresik',
                'desc' => 'Berpengalaman sejak 2013, CV. Karya Perdana Teknik adalah perusahaan terpercaya dalam penyediaan mesin Hoist, Crane System & Cargo Lift berstandar internasional.',
                'keywords' => 'profil karya perdana teknik, tentang kpt gresik, spesialis hoist crane berpengalaman, perusahaan crane jawa timur, sejarah kpt crane'
            ],
            'services' => [
                'title' => 'Produk & Layanan Instalasi Crane, Hoist & Lift Industri',
                'desc' => 'Solusi lengkap pengadaan, pemasangan, dan pemeliharaan Overhead Crane, Cargo Lift, Jib Crane & Monorail Hoist. Dilengkapi garansi purna jual yang responsif.',
                'keywords' => 'layanan instalasi crane, jasa pasang cargo lift, produk hoist crane, overhead crane fabrikasi, perbaikan mesin angkat angkut, gantry crane gresik'
            ],
            'gallery' => [
                'title' => 'Galeri Proyek & Portofolio Pemasangan Crane Lift | KPT',
                'desc' => 'Dokumentasi hasil pengerjaan proyek Overhead Crane, Cargo Lift, dan Gantry Crane di berbagai sektor industri dan manufaktur di seluruh wilayah Indonesia.',
                'keywords' => 'galeri proyek crane, portofolio cargo lift, hasil pemasangan hoist, dokumentasi overhead crane, proyek kpt gresik, instalasi alat berat'
            ],
            'articles' => [
                'title' => 'Artikel, Tips Maintenance & Informasi Hoist Crane Terbaru',
                'desc' => 'Panduan lengkap seputar dunia alat angkat angkut industri, tips perawatan crane, serta berita terbaru dari CV. Karya Perdana Teknik.',
                'keywords' => 'artikel hoist crane, tips maintenance crane, berita industri manufaktur, panduan perawatan cargo lift, blog alat berat, edukasi overhead crane'
            ],
            'contact' => [
                'title' => 'Hubungi Kami | Konsultasi Kebutuhan Hoist Crane & Lift',
                'desc' => 'Dapatkan konsultasi gratis untuk kebutuhan mesin angkat angkut industri Anda. Hubungi spesialis kami di CV. Karya Perdana Teknik, Pergudangan Legundi Gresik.',
                'keywords' => 'kontak karya perdana teknik, hubungi teknisi crane, alamat kpt gresik, nomor wa spesialis hoist, konsultasi cargo lift gratis, penawaran harga crane'
            ]
        ];

        foreach($seo as $page => $data) {
            Setting::set('meta_title_'.$page, $data['title'], 'text', 'seo');
            Setting::set('meta_desc_'.$page, $data['desc'], 'textarea', 'seo');
            Setting::set('meta_keywords_'.$page, $data['keywords'], 'text', 'seo');
        }
    }
}
