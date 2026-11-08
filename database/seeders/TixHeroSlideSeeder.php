<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;

class TixHeroSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'position'    => 'tix',
                'title'       => 'Banner TIX 1 (1920 x 600)',
                'description' => 'Banner promosi konser & festival musik Buyle TIX',
                'button_text' => 'Beli Tiket',
                'button_url'  => '#event-terdekat',
                'order'       => 1,
                'is_active'   => true,
            ],
            [
                'position'    => 'tix',
                'title'       => 'Banner TIX 2 (1920 x 600)',
                'description' => 'Banner promosi seminar, workshop & expo kreatif Buyle TIX',
                'button_text' => 'Lihat Tiket',
                'button_url'  => '#event-terdekat',
                'order'       => 2,
                'is_active'   => true,
            ],
            [
                'position'    => 'tix',
                'title'       => 'Banner TIX 3 (1920 x 600)',
                'description' => 'Banner promosi e-tiket instan & event online Buyle TIX',
                'button_text' => 'Eksplorasi Event',
                'button_url'  => '#event-terdekat',
                'order'       => 3,
                'is_active'   => true,
            ],
        ];

        foreach ($slides as $data) {
            HeroSlide::firstOrCreate(
                ['position' => 'tix', 'order' => $data['order']],
                $data
            );
        }
    }
}

