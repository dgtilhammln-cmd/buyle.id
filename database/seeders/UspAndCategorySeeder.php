<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UspItem;
use App\Models\CategoryItem;
use Illuminate\Support\Facades\Storage;

class UspAndCategorySeeder extends Seeder
{
    private function saveSvg(string $disk, string $folder, string $filename, string $svg): string
    {
        $path = "{$folder}/{$filename}";
        Storage::disk($disk)->put($path, $svg);
        return $path;
    }

    public function run(): void
    {
        // ── Ensure dirs exist ──
        Storage::disk('public')->makeDirectory('usp-icons');
        Storage::disk('public')->makeDirectory('category-icons');

        UspItem::truncate();
        CategoryItem::truncate();

        // ════════════════════════════════════════════════
        // USP ICONS  (24x24 outlined, brand blue #0EA5E9)
        // ════════════════════════════════════════════════
        $uspSvgs = [
            'usp-ori' => <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48">
  <rect width="48" height="48" rx="12" fill="#EFF6FF"/>
  <path d="M24 8l4.47 8.44L38 18.27l-7 6.73 1.65 9.5L24 30.2l-8.65 4.3L17 25 10 18.27l9.53-1.83z"
    fill="none" stroke="#0EA5E9" stroke-width="2" stroke-linejoin="round"/>
</svg>
SVG,
            'usp-retur' => <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48">
  <rect width="48" height="48" rx="12" fill="#EFF6FF"/>
  <path d="M14 20H30a6 6 0 0 1 0 12H16" fill="none" stroke="#0EA5E9" stroke-width="2" stroke-linecap="round"/>
  <polyline points="18,15 13,20 18,25" fill="none" stroke="#0EA5E9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
SVG,
            'usp-garansi' => <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48">
  <rect width="48" height="48" rx="12" fill="#EFF6FF"/>
  <path d="M24 10l10 4v8c0 6-4.5 11-10 13C18.5 33 14 28 14 22v-8z" fill="none" stroke="#0EA5E9" stroke-width="2" stroke-linejoin="round"/>
  <polyline points="20,24 23,27 29,21" fill="none" stroke="#0EA5E9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
SVG,
        ];

        foreach ($uspSvgs as $filename => $svg) {
            $this->saveSvg('public', 'usp-icons', "{$filename}.svg", $svg);
        }

        $uspData = [
            ['icon' => 'usp-icons/usp-ori.svg',    'label' => 'Pasti Ori',          'sort_order' => 1],
            ['icon' => 'usp-icons/usp-retur.svg',   'label' => 'Retur Alasan Apapun','sort_order' => 2],
            ['icon' => 'usp-icons/usp-garansi.svg', 'label' => 'Jaminan Tepat Waktu','sort_order' => 3],
        ];

        foreach ($uspData as $d) {
            UspItem::create([
                'icon_type'  => 'upload',
                'icon_value' => $d['icon'],
                'label'      => $d['label'],
                'sort_order' => $d['sort_order'],
                'is_active'  => true,
            ]);
        }

        // ════════════════════════════════════════════════
        // CATEGORY ICONS  (CLEAN UNIFORM BLUE STYLE)
        // ════════════════════════════════════════════════
        $catSvgs = [
            'cat-all' => <<<PATH
<path d="M12 12h8v8h-8zM24 12h8v8h-8zM12 24h8v8h-8zM24 24h8v8h-8z" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linejoin="round"/>
PATH,
            'cat-promo' => <<<PATH
<path d="M14 30L30 14" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
<circle cx="17" cy="17" r="3.5" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
<circle cx="27" cy="27" r="3.5" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
PATH,
            'cat-dapur' => <<<PATH
<path d="M16 12v11M20 12v11" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
<path d="M13 21c0 3.5 2.5 5.5 5 5.5s5-2 5-5.5" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
<path d="M28 12c2 3 2 9 0 14.5" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
<path d="M28 20h-4" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
PATH,
            'cat-kebersihan' => <<<PATH
<path d="M22 13l-7 7c-1.5 1.5-1.5 4 0 5.5l7 7c1.5 1.5 4 1.5 5.5 0l7-7c1.5-1.5 1.5-4 0-5.5l-7-7c-1.5-1.5-4-1.5-5.5 0z" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
<path d="M14 30l-3 3" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
<path d="M22 13l5-5c1-1 3-1 4 0l1 1c1 1 1 3 0 4l-5 5" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
PATH,
            'cat-kamar' => <<<PATH
<rect x="10" y="20" width="24" height="12" rx="2" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
<path d="M10 25h24" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
<path d="M15 20v-5a4 4 0 0 1 8 0v5" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
<path d="M10 32v2M34 32v2" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
PATH,
            'cat-kamar-mandi' => <<<PATH
<path d="M14 28c0 2 1.5 4 4 4s4-2 4-4v-4h-8v4z" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linejoin="round"/>
<path d="M14 24V14a4 4 0 0 1 8 0" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
<path d="M22 24h10" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
<circle cx="27" cy="21" r="2" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
PATH,
            'cat-elektronik' => <<<PATH
<rect x="10" y="12" width="24" height="16" rx="3" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
<path d="M19 32h6M22 28v4" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
<path d="M16 20h2M21 18v4M24 20h4" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
PATH,
            'cat-taman' => <<<PATH
<path d="M22 32V20M22 20c0-4-6-9-6-9s0 6 6 9zM22 20c0-4 6-9 6-9s0 6-6 9z" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M15 32h14" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
PATH,
            'cat-perkakas' => <<<PATH
<path d="M28 14l2 2-10 10-2-2 10-10z" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M14 28l2 2 4-4-2-2-4 4z" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M31 14c0-1.5-1-3-3-3a3 3 0 0 0-3 3c0 .8.3 1.5.8 2.2L22 20l2 2 4.2-4.2c.7.5 1.4.8 2.2.8a3 3 0 0 0 2.6-3.6z" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linejoin="round"/>
PATH,
            'cat-laundry' => <<<PATH
<rect x="12" y="10" width="20" height="24" rx="3" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
<circle cx="22" cy="23" r="5" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
<circle cx="22" cy="23" r="2" fill="#0EA5E9"/>
<rect x="15" y="13" width="5" height="3" rx="1" fill="#0EA5E9"/>
PATH,
            'cat-penyimpanan' => <<<PATH
<rect x="10" y="20" width="24" height="12" rx="2" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
<rect x="10" y="12" width="24" height="8" rx="2" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
<path d="M19 16h6M19 26h6" stroke="#0EA5E9" stroke-width="2.5" stroke-linecap="round"/>
PATH,
            'cat-pengiriman' => <<<PATH
<rect x="8" y="16" width="20" height="14" rx="2" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
<path d="M28 20h5l4 5v5h-9v-10z" fill="none" stroke="#0EA5E9" stroke-width="2.5" stroke-linejoin="round"/>
<circle cx="14" cy="30" r="3" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
<circle cx="31" cy="30" r="3" fill="none" stroke="#0EA5E9" stroke-width="2.5"/>
PATH,
        ];

        foreach ($catSvgs as $filename => $svgData) {
            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="44" height="44">
  {$svgData}
</svg>
SVG;
            $this->saveSvg('public', 'category-icons', "{$filename}.svg", $svg);
        }

        $categoryData = [
            ['icon' => 'category-icons/cat-all.svg',         'name' => 'Lihat Semua',      'url' => '/produk',                           'badge' => null,   'badge_color' => null,      'sort_order' => 1],
            ['icon' => 'category-icons/cat-promo.svg',       'name' => 'Semua Promo',      'url' => '/produk?promo=1',                   'badge' => 'Hot',  'badge_color' => '#ef4444', 'sort_order' => 2],
            ['icon' => 'category-icons/cat-dapur.svg',       'name' => 'Peralatan Dapur',  'url' => '/produk?kategori=dapur',            'badge' => null,   'badge_color' => null,      'sort_order' => 3],
            ['icon' => 'category-icons/cat-kebersihan.svg',  'name' => 'Kebersihan',       'url' => '/produk?kategori=kebersihan',       'badge' => null,   'badge_color' => null,      'sort_order' => 4],
            ['icon' => 'category-icons/cat-kamar.svg',       'name' => 'Kamar Tidur',      'url' => '/produk?kategori=kamar-tidur',      'badge' => null,   'badge_color' => null,      'sort_order' => 5],
            ['icon' => 'category-icons/cat-kamar-mandi.svg', 'name' => 'Kamar Mandi',      'url' => '/produk?kategori=kamar-mandi',      'badge' => null,   'badge_color' => null,      'sort_order' => 6],
            ['icon' => 'category-icons/cat-elektronik.svg',  'name' => 'Elektronik',       'url' => '/produk?kategori=elektronik',       'badge' => 'Baru', 'badge_color' => '#0EA5E9', 'sort_order' => 7],
            ['icon' => 'category-icons/cat-taman.svg',       'name' => 'Taman & Outdoor',  'url' => '/produk?kategori=taman',            'badge' => null,   'badge_color' => null,      'sort_order' => 8],
            ['icon' => 'category-icons/cat-perkakas.svg',    'name' => 'Perkakas',         'url' => '/produk?kategori=perkakas',         'badge' => null,   'badge_color' => null,      'sort_order' => 9],
            ['icon' => 'category-icons/cat-laundry.svg',     'name' => 'Laundry',          'url' => '/produk?kategori=laundry',          'badge' => null,   'badge_color' => null,      'sort_order' => 10],
            ['icon' => 'category-icons/cat-penyimpanan.svg', 'name' => 'Penyimpanan',      'url' => '/produk?kategori=penyimpanan',      'badge' => null,   'badge_color' => null,      'sort_order' => 11],
            ['icon' => 'category-icons/cat-pengiriman.svg',  'name' => 'Pengiriman Kilat', 'url' => '/produk?pengiriman=kilat',          'badge' => 'Cepat','badge_color' => '#f97316', 'sort_order' => 12],
        ];

        foreach ($categoryData as $d) {
            CategoryItem::create([
                'icon_type'   => 'upload',
                'icon_value'  => $d['icon'],
                'name'        => $d['name'],
                'url'         => $d['url'],
                'badge'       => $d['badge'],
                'badge_color' => $d['badge_color'],
                'sort_order'  => $d['sort_order'],
                'is_active'   => true,
            ]);
        }

        $this->command->info('✅ ' . UspItem::count() . ' USP items and ' . CategoryItem::count() . ' Category items seeded!');
    }
}
