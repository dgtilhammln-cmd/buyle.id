<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    /**
     * Seed kategori produk untuk Turbine Ventilator buyle.id.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Residensial',
                'slug'        => 'residensial',
                'description' => 'Turbine ventilator untuk rumah tinggal, townhouse, dan perumahan. '
                               . 'Ukuran kompak dengan performa optimal untuk ruangan hunian.',
                'image'       => null,
                'is_active'   => true,
                'order'       => 1,
            ],
            [
                'name'        => 'Komersial',
                'slug'        => 'komersial',
                'description' => 'Solusi ventilasi untuk ruko, restoran, hotel, dan bangunan komersial. '
                               . 'Dirancang untuk kebutuhan sirkulasi udara pada area semi-publik.',
                'image'       => null,
                'is_active'   => true,
                'order'       => 2,
            ],
            [
                'name'        => 'Industri & Pergudangan',
                'slug'        => 'industri-pergudangan',
                'description' => 'Turbine ventilator skala besar untuk pabrik, gudang logistik, '
                               . 'dan fasilitas industri berat. Kapasitas hisap maksimal.',
                'image'       => null,
                'is_active'   => true,
                'order'       => 3,
            ],
            [
                'name'        => 'Fasilitas Umum',
                'slug'        => 'fasilitas-umum',
                'description' => 'Untuk GOR, sekolah, puskesmas, pasar, dan gedung pemerintah. '
                               . 'Solusi ventilasi hemat energi untuk area publik.',
                'image'       => null,
                'is_active'   => true,
                'order'       => 4,
            ],
            [
                'name'        => 'Aksesori & Suku Cadang',
                'slug'        => 'aksesori-suku-cadang',
                'description' => 'Spare part resmi buyle.id: bearing pengganti, topi bola, '
                               . 'flashing kit, dan aksesori instalasi lainnya.',
                'image'       => null,
                'is_active'   => true,
                'order'       => 5,
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::updateOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ ProductCategorySeeder: ' . count($categories) . ' kategori berhasil dibuat.');
    }
}
