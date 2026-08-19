<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\Service;
use App\Models\GalleryProject;
use App\Models\Article;
use App\Models\Client;
use App\Models\Testimonial;
use App\Models\WaSetting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user (Akses 2026)
        User::updateOrCreate(['email' => 'admin@buyle.id.com'], [
            'name'     => 'Admin buyle.id',
            'password' => Hash::make('Admin@buyle.id2026'),
            'role'     => 'admin',
            'is_active'=> true,
        ]);

        // WA Settings
        WaSetting::insert([
            ['label'=>'WA Utama - Konsultasi','nomor_wa'=>'081296565757','template_pesan'=>'Halo buyle.id, saya ingin konsultasi mengenai [produk]. Mohon informasinya. Terima kasih.','is_active'=>1,'is_primary'=>1,'order'=>1,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // Settings
        $settings = [
            // Hero
            ['key'=>'hero_headline','value'=>'Ventilasi Udara Tanpa Listrik','type'=>'text','group'=>'hero','label'=>'Hero Headline'],
            ['key'=>'hero_subheadline','value'=>'buyle.id turbine ventilator non-electric — menghisap udara panas, lembab & berbau dari pabrik, gudang, hingga rumah tinggal. Beroperasi 24 jam dengan tenaga angin.','type'=>'text','group'=>'hero','label'=>'Hero Sub-headline'],
            ['key'=>'hero_cta_primary','value'=>'Konsultasi Gratis','type'=>'text','group'=>'hero','label'=>'CTA Primary Text'],
            ['key'=>'hero_cta_secondary','value'=>'Lihat Produk Kami','type'=>'text','group'=>'hero','label'=>'CTA Secondary Text'],
            ['key'=>'hero_bg_image','value'=>'','type'=>'image','group'=>'hero','label'=>'Hero Background Image'],
            // About
            ['key'=>'about_text','value'=>'PT. Hiranatha Makmur Sukses adalah perusahaan produsen turbine ventilator profesional dengan merek dagang "buyle.id". Berdiri sejak 2007, berpengalaman lebih dari 18 tahun melayani pelanggan di seluruh Indonesia — mulai dari perusahaan multinasional, konglomerasi, perusahaan domestik, hingga BUMN.','type'=>'text','group'=>'about','label'=>'About Text'],
            ['key'=>'about_image','value'=>'','type'=>'image','group'=>'about','label'=>'About Image'],
            ['key'=>'visi','value'=>'Menjadi perusahaan nasional kelas dunia yang berorientasi pada kepentingan pelanggan.','type'=>'text','group'=>'about','label'=>'Visi'],
            ['key'=>'misi','value'=>'Menjalankan bisnis secara profesional dengan penguasaan teknis tinggi dari hulu sampai hilir untuk memberikan produk Turbine Ventilator terbaik bagi pelanggan.','type'=>'text','group'=>'about','label'=>'Misi'],
            // Stats
            ['key'=>'stat_years','value'=>'18+','type'=>'text','group'=>'stats','label'=>'Tahun Pengalaman'],
            ['key'=>'stat_clients','value'=>'500+','type'=>'text','group'=>'stats','label'=>'Klien / Proyek'],
            ['key'=>'stat_products','value'=>'5','type'=>'text','group'=>'stats','label'=>'Tipe Produk'],
            ['key'=>'stat_coverage','value'=>'Seluruh Indonesia','type'=>'text','group'=>'stats','label'=>'Jangkauan'],
            // Contact
            ['key'=>'phone','value'=>'021-22523334','type'=>'text','group'=>'contact','label'=>'Telepon'],
            ['key'=>'wa1','value'=>'081296565757','type'=>'text','group'=>'contact','label'=>'WhatsApp Utama'],
            ['key'=>'email','value'=>'buyle.id.ventilator58@gmail.com','type'=>'text','group'=>'contact','label'=>'Email'],
            ['key'=>'address','value'=>'Jl. Kerukunan IX, Komp. Citra Garden 2 Blok G1 No.6, Kalideres, Jakarta Barat 11830','type'=>'text','group'=>'contact','label'=>'Alamat'],
            ['key'=>'maps_embed','value'=>'https://maps.google.com/maps?q=-6.1364,106.7028&output=embed','type'=>'text','group'=>'contact','label'=>'Maps Embed URL'],
            // Social
            ['key'=>'instagram','value'=>'','type'=>'text','group'=>'social','label'=>'Instagram URL'],
            ['key'=>'facebook','value'=>'','type'=>'text','group'=>'social','label'=>'Facebook URL'],
            ['key'=>'youtube','value'=>'','type'=>'text','group'=>'social','label'=>'YouTube URL'],
            // Footer
            ['key'=>'footer_desc','value'=>'Produsen Turbine Ventilator Non-Electric #1 di Indonesia. Berdiri sejak 2007, melayani ribuan pelanggan dari Sabang sampai Merauke.','type'=>'text','group'=>'footer','label'=>'Footer Description'],
            ['key'=>'copyright','value'=>'© 2007–2026 PT. Hiranatha Makmur Sukses (buyle.id). All rights reserved.','type'=>'text','group'=>'footer','label'=>'Copyright'],
            // SEO
            ['key'=>'meta_title_home','value'=>'buyle.id — Turbine Ventilator Non-Electric #1 Indonesia','type'=>'text','group'=>'seo','label'=>'Meta Title Home'],
            ['key'=>'meta_desc_home','value'=>'Produsen turbine ventilator non-electric terpercaya sejak 2007. Garansi 15 tahun tidak berkarat. Gratis konsultasi: 0812-9656-5757.','type'=>'text','group'=>'seo','label'=>'Meta Desc Home'],
            ['key'=>'og_image_default','value'=>'','type'=>'image','group'=>'seo','label'=>'Default OG Image (1200x630)'],
            // Theme Colors (Light Blue Palette)
            ['key'=>'color_accent','value'=>'#38BDF8','type'=>'text','group'=>'theme','label'=>'Accent Color'],
            ['key'=>'color_main','value'=>'#FFFFFF','type'=>'text','group'=>'theme','label'=>'Main Background Color'],
            ['key'=>'color_text','value'=>'#0C1A3A','type'=>'text','group'=>'theme','label'=>'Main Text Color'],
        ];
        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], array_merge($s, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // Products (stored in `services` table for backward compatibility with admin)
        $products = [
            ['name'=>'CV-45 (18")', 'slug'=>'cv-45-18', 'short_desc'=>'Kapasitas hisap 52,47 m³/menit. Cocok untuk ruangan sedang dan aplikasi residensial/komersial ringan.', 'order'=>1],
            ['name'=>'CV-60 (24")', 'slug'=>'cv-60-24', 'short_desc'=>'Kapasitas hisap 98,79 m³/menit. Standar industri ringan, bengkel, dan pergudangan kecil.', 'order'=>2],
            ['name'=>'CV-75 (30")', 'slug'=>'cv-75-30', 'short_desc'=>'Kapasitas hisap 147,95 m³/menit. Ideal untuk pabrik, aula, dan gudang skala menengah.', 'order'=>3],
            ['name'=>'CV-90 (36")', 'slug'=>'cv-90-36', 'short_desc'=>'Kapasitas hisap 215,79 m³/menit. Desain masif untuk area industri berat yang butuh sirkulasi besar.', 'order'=>4],
            ['name'=>'CV-105 (42")', 'slug'=>'cv-105-42', 'short_desc'=>'Kapasitas hisap 257,87 m³/menit. Ukuran terbesar kami, performa maksimal untuk pabrik skala masif.', 'order'=>5],
        ];
        foreach ($products as $p) {
            Service::updateOrCreate(['slug'=>$p['slug']], array_merge($p, [
                'description' => '<p>'.$p['short_desc'].'</p><ul><li>Material: Aluminium & Stainless Steel 430/304</li><li>Bearing: SKF Full Stainless Steel (Made in Japan)</li><li>Operasi: 24 Jam Non-stop, 0 Watt</li></ul><p>buyle.id menggunakan konstruksi standar USA dengan powder coating pada rangka dan topi bola, menjamin ketahanan terhadap karat dan cuaca ekstrim hingga 15 tahun.</p>',
                'is_active' => true, 'created_at'=>now(),'updated_at'=>now()
            ]));
        }

        // Gallery Projects (Dummy)
        $galleries = [
            ['title'=>'Instalasi Pabrik Cikarang','category'=>'industri','client'=>'PT. Manufaktur Sukses','location'=>'Cikarang','year'=>2024,'order'=>1],
            ['title'=>'Gudang Logistik Nasional','category'=>'pergudangan','client'=>'PT. Logistik Cepat','location'=>'Bekasi','year'=>2023,'order'=>2],
            ['title'=>'Area Dapur Restoran','category'=>'komersial','client'=>'Restoran Rasa Nusantara','location'=>'Jakarta Selatan','year'=>2023,'order'=>3],
            ['title'=>'Ventilasi Rumah Tinggal','category'=>'residensial','client'=>'Bp. Anton','location'=>'Tangerang','year'=>2024,'order'=>4],
            ['title'=>'GOR Bulutangkis','category'=>'fasilitas-umum','client'=>'GOR Sehat Raya','location'=>'Depok','year'=>2022,'order'=>5],
            ['title'=>'Pabrik Garment Balaraja','category'=>'industri','client'=>'PT. Busana Indah','location'=>'Tangerang','year'=>2022,'order'=>6],
            ['title'=>'Gudang Bahan Kimia','category'=>'industri','client'=>'PT. Kimia Raya','location'=>'Gresik','year'=>2023,'order'=>7],
            ['title'=>'Pabrik Makanan & Minuman','category'=>'industri','client'=>'PT. Pangan Sejahtera','location'=>'Semarang','year'=>2024,'order'=>8],
        ];
        foreach ($galleries as $g) {
            GalleryProject::updateOrCreate(['title'=>$g['title']], array_merge($g, [
                'description' => 'Proyek pemasangan Turbine Ventilator buyle.id untuk mengatasi masalah suhu panas dan kelembapan di dalam ruangan.',
                'image'       => '',
                'alt_text'    => $g['title'].' - buyle.id',
                'is_active'   => true, 'created_at'=>now(),'updated_at'=>now()
            ]));
        }

        // Articles
        $articles = [
            [
                'title'        => 'Mengapa Pabrik Anda Butuh Turbine Ventilator? Ini Alasannya',
                'slug'         => 'mengapa-pabrik-butuh-turbine-ventilator',
                'excerpt'      => 'Suhu panas dan sirkulasi udara yang buruk di dalam pabrik dapat menurunkan produktivitas pekerja. Pelajari solusi tanpa listrik dengan turbine ventilator.',
                'category'     => 'Tips Industri',
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'author'       => 'Tim buyle.id',
                'meta_title'   => 'Mengapa Pabrik Anda Butuh Turbine Ventilator? | buyle.id',
                'meta_desc'    => 'Suhu panas pabrik menurunkan produktivitas. Temukan solusi ventilasi udara tanpa biaya listrik dari buyle.id.',
            ],
            [
                'title'        => 'Cara Kerja Turbine Ventilator Non-Electric buyle.id',
                'slug'         => 'cara-kerja-turbine-ventilator',
                'excerpt'      => 'Banyak yang bertanya bagaimana kipas atap bisa berputar tanpa listrik. Rahasianya ada pada desain aerodinamis dan efek sentrifugal.',
                'category'     => 'Edukasi',
                'is_published' => true,
                'published_at' => now()->subDays(12),
                'author'       => 'Tim buyle.id',
                'meta_title'   => 'Cara Kerja Turbine Ventilator buyle.id Tanpa Listrik',
                'meta_desc'    => 'Penjelasan lengkap cara kerja turbine ventilator menghisap udara panas tanpa menggunakan energi listrik.',
            ],
            [
                'title'        => 'Tips Memilih Ukuran Turbine Ventilator untuk Gudang',
                'slug'         => 'tips-memilih-ukuran-ventilator-gudang',
                'excerpt'      => 'CV-60, CV-75, atau CV-90? Jangan salah pilih! Ikuti panduan kami untuk menghitung volume ruangan dan menentukan kapasitas hisap yang tepat.',
                'category'     => 'Panduan',
                'is_published' => true,
                'published_at' => now()->subDays(20),
                'author'       => 'Tim buyle.id',
                'meta_title'   => 'Cara Menghitung Ukuran Turbine Ventilator Gudang | buyle.id',
                'meta_desc'    => 'Jangan salah beli! Panduan lengkap memilih ukuran (CV-45 s/d CV-105) turbine ventilator sesuai luas gudang Anda.',
            ],
        ];
        $contentTemplate = '<h2>Pendahuluan</h2><p>Sirkulasi udara yang baik sangat vital untuk menjaga kenyamanan dan kesehatan pekerja, serta melindungi barang-barang di dalam ruangan dari kelembapan berlebih.</p><h2>Detail Pembahasan</h2><p>Turbine ventilator dari buyle.id menggunakan desain berstandar USA dan bearing SKF asli Jepang untuk menjamin perputaran maksimal meski dengan hembusan angin yang sangat minim. Bahan aluminium dan stainless steel juga memastikannya bebas dari karat hingga belasan tahun.</p><h2>Kesimpulan</h2><p>Hubungi buyle.id di <strong>0812-9656-5757</strong> untuk konsultasi gratis penentuan ukuran dan jumlah ventilator yang ideal untuk properti Anda.</p>';
        foreach ($articles as $a) {
            Article::updateOrCreate(['slug'=>$a['slug']], array_merge($a, [
                'content'=>$contentTemplate, 'views'=>rand(100,500),
                'created_at'=>now(),'updated_at'=>now()
            ]));
        }

        // Clients
        $clients = [
            ['name'=>'PT. Indofood CBP','city'=>'Jakarta','order'=>1],
            ['name'=>'PT. Astra Honda Motor','city'=>'Cikarang','order'=>2],
            ['name'=>'PT. Mayora Indah','city'=>'Tangerang','order'=>3],
            ['name'=>'PT. Gudang Garam','city'=>'Kediri','order'=>4],
            ['name'=>'PT. Unilever Indonesia','city'=>'Bekasi','order'=>5],
            ['name'=>'PT. Kalbe Farma','city'=>'Cikarang','order'=>6],
            ['name'=>'PT. Wings Group','city'=>'Gresik','order'=>7],
            ['name'=>'PT. Nestle Indonesia','city'=>'Karawang','order'=>8],
        ];
        foreach ($clients as $c) {
            Client::updateOrCreate(['name'=>$c['name']], array_merge($c, [
                'is_active'=>true,'created_at'=>now(),'updated_at'=>now()
            ]));
        }

        // Testimonials
        Testimonial::insert([
            ['name'=>'Bpk. Budi Santoso','company'=>'PT. Manufaktur Sukses','position'=>'Plant Manager','content'=>'Suhu di dalam pabrik kami turun signifikan setelah memasang buyle.id CV-90. Pekerja jadi lebih nyaman dan produktivitas meningkat tajam. Sangat direkomendasikan!','rating'=>5,'is_active'=>1,'order'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Ibu Linda','company'=>'PT. Logistik Cepat','position'=>'Warehouse Head','content'=>'Awalnya ragu karena tanpa listrik, tapi ternyata perputarannya sangat stabil dan kuat. Masalah udara pengap di gudang langsung teratasi.','rating'=>5,'is_active'=>1,'order'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'H. Ahmad','company'=>'Owner','position'=>'Pabrik Tekstil Balaraja','content'=>'Pelayanan dari buyle.id sangat memuaskan. Mulai dari survei, rekomendasi ukuran, hingga instalasi berjalan dengan rapi. Garansi 15 tahun bikin tenang.','rating'=>5,'is_active'=>1,'order'=>3,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // =====================================================================
        // E-commerce Seeders (Fase 1 - UMKM Lite)
        // =====================================================================
        $this->call([
            ProductCategorySeeder::class,
            CouponSeeder::class,
        ]);
    }
}
