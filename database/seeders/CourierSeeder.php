<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Courier;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        $couriersData = [
            ['code' => 'jne', 'name' => 'JNE Reguler', 'type' => 'expedition', 'order' => 1, 'is_active' => true],
            ['code' => 'jnt', 'name' => 'J&T Express', 'type' => 'expedition', 'order' => 2, 'is_active' => true],
            ['code' => 'sicepat', 'name' => 'SiCepat Express', 'type' => 'expedition', 'order' => 3, 'is_active' => true],
            ['code' => 'pos', 'name' => 'POS Indonesia', 'type' => 'expedition', 'order' => 4, 'is_active' => true],
            ['code' => 'tiki', 'name' => 'TIKI', 'type' => 'expedition', 'order' => 5, 'is_active' => true],
            ['code' => 'custom', 'name' => 'Kurir Toko (Manual)', 'type' => 'custom', 'order' => 6, 'is_active' => true],
        ];

        foreach ($couriersData as $item) {
            Courier::updateOrCreate(['code' => $item['code']], $item);
        }
    }
}
