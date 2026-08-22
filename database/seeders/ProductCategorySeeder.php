<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Hapus kategori lama (turbine ventilator / suku cadang)
        $slugsLama = [
            'residensial', 'komersial', 'industri-pergudangan',
            'fasilitas-umum', 'aksesori-suku-cadang',
        ];
        ProductCategory::whereIn('slug', $slugsLama)->delete();

        // Delegasikan ke seeder baru yang lengkap
        $this->call(MarketplaceCategorySeeder::class);
    }
}
