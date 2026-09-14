<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Courier;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        // Deactivate all others
        Courier::query()->update(['is_active' => false]);

        $couriersData = [
            ['code' => 'jne', 'name' => 'JNE Express', 'type' => 'expedition', 'order' => 1, 'is_active' => true],
            ['code' => 'jnt', 'name' => 'J&T Express', 'type' => 'expedition', 'order' => 2, 'is_active' => true],
        ];

        foreach ($couriersData as $item) {
            Courier::updateOrCreate(['code' => $item['code']], $item);
        }
    }
}
