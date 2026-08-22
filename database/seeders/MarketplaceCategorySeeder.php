<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketplaceCategorySeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // DATA KATEGORI & SUB-KATEGORI BUYLE.ID
        // Total: 11 kategori, 41 sub-kategori
        // Sumber: kategori.md
        // =============================================

        $categories = [

            // ──────────────────────────────────────
            // TAB: PRODUK DIGITAL (6 kategori)
            // ──────────────────────────────────────
            [
                'name'        => 'AI & Otomatisasi',
                'slug'        => 'ai-otomatisasi',
                'tab'         => 'produk',
                'badge'       => 'terpopuler',
                'description' => 'Alat berbasis kecerdasan buatan',
                'order'       => 1,
                'sub'         => [
                    ['name' => 'Prompt Siap Pakai',        'slug' => 'prompt-siap-pakai',    'order' => 1, 'description' => 'Prompt siap pakai untuk berbagai AI',           'contoh_produk' => 'Prompt ChatGPT, Midjourney, Claude untuk berbagai kebutuhan'],
                    ['name' => 'Bot & Agen AI Kustom',     'slug' => 'bot-agen-ai',          'order' => 2, 'description' => 'Custom GPT dan bot bisnis otomatis',             'contoh_produk' => 'Custom GPT, bot layanan pelanggan, asisten bisnis otomatis'],
                    ['name' => 'Blueprint Otomatisasi',    'slug' => 'blueprint-otomatisasi','order' => 3, 'description' => 'Alur kerja otomatisasi siap impor',               'contoh_produk' => 'Alur kerja Make.com, n8n, Zapier siap impor'],
                    ['name' => 'Suara & Audio AI',         'slug' => 'suara-audio-ai',       'order' => 4, 'description' => 'Preset dan sampel audio berbasis AI',            'contoh_produk' => 'Preset ElevenLabs, sampel musik AI, efek suara AI'],
                ],
            ],

            [
                'name'        => 'Templat & Produktivitas',
                'slug'        => 'templat-produktivitas',
                'tab'         => 'produk',
                'badge'       => null,
                'description' => 'Siap pakai, hemat waktu',
                'order'       => 2,
                'sub'         => [
                    ['name' => 'Templat Canva',       'slug' => 'templat-canva',       'order' => 1, 'description' => 'Templat desain Canva siap pakai',         'contoh_produk' => 'Feed Instagram, pitch deck, undangan, banner iklan'],
                    ['name' => 'Sistem Notion',       'slug' => 'sistem-notion',       'order' => 2, 'description' => 'Sistem dan workspace Notion terstruktur', 'contoh_produk' => 'Second Brain, CRM, manajemen proyek, keuangan pribadi'],
                    ['name' => 'Templat Spreadsheet', 'slug' => 'templat-spreadsheet', 'order' => 3, 'description' => 'Template spreadsheet otomatis',           'contoh_produk' => 'Invoice otomatis, kalkulator harga, laporan keuangan'],
                    ['name' => 'Templat Website',     'slug' => 'templat-website',     'order' => 4, 'description' => 'Template website berbagai platform',      'contoh_produk' => 'Landing page Framer/Webflow/WordPress, portofolio interaktif'],
                ],
            ],

            [
                'name'        => 'Aset Desain & Visual',
                'slug'        => 'aset-desain-visual',
                'tab'         => 'produk',
                'badge'       => null,
                'description' => 'Untuk konten, video, dan desain',
                'order'       => 3,
                'sub'         => [
                    ['name' => 'Preset & Filter Foto/Video', 'slug' => 'preset-filter',        'order' => 1, 'description' => 'Preset dan filter untuk foto/video',       'contoh_produk' => 'Preset Lightroom, LUT DaVinci/Premiere, filter CapCut'],
                    ['name' => 'Aset Desain Grafis',         'slug' => 'aset-desain-grafis',   'order' => 2, 'description' => 'Aset visual untuk desainer grafis',        'contoh_produk' => 'Font, mockup 3D, ikon, UI kit Figma'],
                    ['name' => 'Aset Video & Motion',        'slug' => 'aset-video-motion',    'order' => 3, 'description' => 'Aset video dan animasi gerak',              'contoh_produk' => 'Transisi After Effects, animasi teks, stok footage 4K'],
                    ['name' => 'Aset Audio & Musik',         'slug' => 'aset-audio-musik',     'order' => 4, 'description' => 'Beat, SFX, dan musik digital',             'contoh_produk' => 'Beat & sampel instrumen, SFX, musik intro/outro podcast'],
                ],
            ],

            [
                'name'        => 'Kode & Alat Developer',
                'slug'        => 'kode-developer',
                'tab'         => 'produk',
                'badge'       => null,
                'description' => 'Boilerplate, skrip, dan tools',
                'order'       => 4,
                'sub'         => [
                    ['name' => 'Boilerplate & Starter Kit', 'slug' => 'boilerplate-starter',   'order' => 1, 'description' => 'Template kode siap pakai untuk developer', 'contoh_produk' => 'Next.js, Laravel + auth & payment gateway terintegrasi'],
                    ['name' => 'Skrip Otomatisasi',         'slug' => 'skrip-otomatisasi',     'order' => 2, 'description' => 'Skrip otomasi berbagai platform',          'contoh_produk' => 'Python scraper, Google Apps Script, Tampermonkey'],
                    ['name' => 'Plugin & Ekstensi',         'slug' => 'plugin-ekstensi',       'order' => 3, 'description' => 'Plugin dan ekstensi untuk berbagai tools',  'contoh_produk' => 'Plugin Figma, ekstensi browser, addon Google Workspace'],
                ],
            ],

            [
                'name'        => 'Ilmu & Konten Digital',
                'slug'        => 'ilmu-konten-digital',
                'tab'         => 'produk',
                'badge'       => null,
                'description' => 'Belajar dan bertumbuh',
                'order'       => 5,
                'sub'         => [
                    ['name' => 'E-Book & Panduan',          'slug' => 'ebook-panduan',              'order' => 1, 'description' => 'E-book dan panduan digital',                    'contoh_produk' => 'Strategi bisnis, keuangan, resep, parenting, self-help'],
                    ['name' => 'Kursus & Video Tutorial',   'slug' => 'kursus-video',               'order' => 2, 'description' => 'Kursus dan tutorial video online',             'contoh_produk' => 'Desain, marketing digital, coding, editing video'],
                    ['name' => 'Database & List Riset',     'slug' => 'database-riset',             'order' => 3, 'description' => 'Database dan data riset siap pakai',           'contoh_produk' => 'List supplier, database prospek, riset pasar industri'],
                    ['name' => 'Keanggotaan & Komunitas',   'slug' => 'keanggotaan-komunitas',      'order' => 4, 'description' => 'Akses eksklusif komunitas digital',            'contoh_produk' => 'Akses grup VIP Telegram/Discord, newsletter eksklusif'],
                ],
            ],

            [
                'name'        => 'Edukasi Anak',
                'slug'        => 'edukasi-anak',
                'tab'         => 'produk',
                'badge'       => 'naik-daun',
                'description' => 'Untuk orang tua dan guru',
                'order'       => 6,
                'sub'         => [
                    ['name' => 'Worksheet & Lembar Kerja', 'slug' => 'worksheet',        'order' => 1, 'description' => 'Lembar kerja cetak untuk anak-anak',     'contoh_produk' => 'Latihan menulis, berhitung, bahasa, islami'],
                    ['name' => 'Buku Mewarnai Digital',    'slug' => 'buku-mewarnai',    'order' => 2, 'description' => 'Coloring book digital bertemakan anak',  'contoh_produk' => 'Coloring book bertema hewan, buah, alam, karakter'],
                    ['name' => 'Modul & RPP Guru',         'slug' => 'modul-rpp-guru',   'order' => 3, 'description' => 'Modul pengajaran dan RPP untuk guru',    'contoh_produk' => 'Materi ajar, soal ujian, RPP Kurikulum Merdeka'],
                ],
            ],

            // ──────────────────────────────────────
            // TAB: JASA DIGITAL (5 kategori)
            // ──────────────────────────────────────
            [
                'name'        => 'Desain & Identitas Visual',
                'slug'        => 'desain-identitas-visual',
                'tab'         => 'jasa',
                'badge'       => null,
                'description' => 'Tampilan brand yang kuat',
                'order'       => 1,
                'sub'         => [
                    ['name' => 'Pembuatan Brand Identity',   'slug' => 'brand-identity',         'order' => 1, 'description' => 'Identitas visual merek yang komprehensif',    'contoh_produk' => 'Logo, panduan warna, tipografi, aset visual siap pakai'],
                    ['name' => 'Desain Thumbnail & Cover',   'slug' => 'thumbnail-cover',        'order' => 2, 'description' => 'Thumbnail dan cover konten digital',          'contoh_produk' => 'Thumbnail YouTube/TikTok, cover e-book, cover podcast'],
                    ['name' => 'Audit UI/UX',                'slug' => 'audit-ui-ux',            'order' => 3, 'description' => 'Review tampilan dan pengalaman pengguna',     'contoh_produk' => 'Review video + laporan perbaikan tampilan & konversi'],
                    ['name' => 'Desain Konten Medsos',       'slug' => 'desain-konten-medsos',   'order' => 4, 'description' => 'Desain konten media sosial profesional',      'contoh_produk' => 'Carousel, infografis, story template, highlight cover'],
                ],
            ],

            [
                'name'        => 'Penulisan & Copywriting',
                'slug'        => 'penulisan-copywriting',
                'tab'         => 'jasa',
                'badge'       => null,
                'description' => 'Kata-kata yang menggerakkan',
                'order'       => 2,
                'sub'         => [
                    ['name' => 'Penulisan Sales Page & Landing Page', 'slug' => 'sales-landing-page-copy',    'order' => 1, 'description' => 'Teks penawaran berkonversi tinggi',            'contoh_produk' => 'Teks penawaran berkonversi tinggi untuk produk/jasa'],
                    ['name' => 'Skrip Video & Konten',                'slug' => 'skrip-video-konten',         'order' => 2, 'description' => 'Paket skrip konten video kreatif',              'contoh_produk' => 'Paket skrip Reels/TikTok/Shorts sesuai niche klien'],
                    ['name' => 'Strategi & Kalender Konten',          'slug' => 'strategi-kalender-konten',   'order' => 3, 'description' => 'Rencana konten bulanan terstruktur',            'contoh_produk' => 'Ide konten + riset tren + jadwal publikasi bulanan'],
                    ['name' => 'Penulisan E-Book & Artikel',          'slug' => 'penulisan-ebook-artikel',    'order' => 4, 'description' => 'Ghostwriting dan penulisan konten panjang',     'contoh_produk' => 'Ghostwriting e-book, artikel blog SEO, newsletter'],
                ],
            ],

            [
                'name'        => 'Produksi Video & Audio',
                'slug'        => 'produksi-video-audio',
                'tab'         => 'jasa',
                'badge'       => null,
                'description' => 'Konten yang enak ditonton & didengar',
                'order'       => 3,
                'sub'         => [
                    ['name' => 'Editing Video Konten',          'slug' => 'editing-video',          'order' => 1, 'description' => 'Editing video konten siap upload',              'contoh_produk' => 'Potong video long-form jadi klip short-form siap upload'],
                    ['name' => 'Color Grading & Mixing Audio',  'slug' => 'color-grading-audio',    'order' => 2, 'description' => 'Pewarnaan sinematik dan mixing audio',          'contoh_produk' => 'Pewarnaan sinematik + pembersihan suara podcast'],
                    ['name' => 'Voice Over & Dubbing',          'slug' => 'voiceover-dubbing',      'order' => 3, 'description' => 'Pengisi suara profesional untuk berbagai media', 'contoh_produk' => 'Pengisi suara iklan, narasi video, IVR telepon'],
                ],
            ],

            [
                'name'        => 'Teknis & Otomatisasi',
                'slug'        => 'teknis-otomatisasi',
                'tab'         => 'jasa',
                'badge'       => null,
                'description' => 'Sistem yang bekerja sendiri',
                'order'       => 4,
                'sub'         => [
                    ['name' => 'Setup Otomatisasi Bisnis', 'slug' => 'setup-otomatisasi',  'order' => 1, 'description' => 'Integrasi dan setup alur otomasi bisnis',         'contoh_produk' => 'Integrasi Make.com/n8n untuk email, CRM, medsos klien'],
                    ['name' => 'Pembuatan Website',        'slug' => 'pembuatan-website',  'order' => 2, 'description' => 'Jasa pembuatan website profesional',              'contoh_produk' => 'Figma ke Framer/Webflow, landing page WordPress'],
                    ['name' => 'Pembuatan Bot & AI Kustom','slug' => 'pembuatan-bot-ai',   'order' => 3, 'description' => 'Jasa pembuatan bot dan AI dari data perusahaan',  'contoh_produk' => 'Bot AI dari data internal perusahaan, chatbot WhatsApp'],
                ],
            ],

            [
                'name'        => 'Konsultasi & Coaching',
                'slug'        => 'konsultasi-coaching',
                'tab'         => 'jasa',
                'badge'       => null,
                'description' => 'Bimbingan dari para ahli',
                'order'       => 5,
                'sub'         => [
                    ['name' => 'Review Portofolio & Karier',    'slug' => 'review-portofolio',          'order' => 1, 'description' => 'Tinjauan portofolio dan bimbingan karier kreatif',   'contoh_produk' => 'Sesi tinjauan portofolio + masukan karier kreatif'],
                    ['name' => 'Strategi Produk Digital',       'slug' => 'strategi-produk-digital',    'order' => 2, 'description' => 'Bimbingan merancang produk digital dari nol',        'contoh_produk' => 'Bimbingan merancang dan meluncurkan produk digital dari nol'],
                    ['name' => 'Konsultasi Bisnis & Marketing', 'slug' => 'konsultasi-bisnis',          'order' => 3, 'description' => 'Strategi pertumbuhan bisnis digital',               'contoh_produk' => 'Strategi pertumbuhan, branding, pemasaran digital UMKM'],
                ],
            ],
        ];

        // =============================================
        // TRUNCATE & SEED
        // =============================================
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_sub_categories')->truncate();
        DB::table('product_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = now();

        foreach ($categories as $catData) {
            $subs = $catData['sub'];
            unset($catData['sub']);

            $catId = DB::table('product_categories')->insertGetId([
                'name'        => $catData['name'],
                'slug'        => $catData['slug'],
                'tab'         => $catData['tab'],
                'badge'       => $catData['badge'],
                'description' => $catData['description'],
                'is_active'   => true,
                'order'       => $catData['order'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            foreach ($subs as $sub) {
                DB::table('product_sub_categories')->insert([
                    'category_id'   => $catId,
                    'name'          => $sub['name'],
                    'slug'          => $sub['slug'],
                    'description'   => $sub['description'],
                    'contoh_produk' => $sub['contoh_produk'],
                    'order'         => $sub['order'],
                    'is_active'     => true,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        }

        $this->command->info('✅ MarketplaceCategorySeeder: 11 kategori dan 41 sub-kategori berhasil diseed!');
    }
}
