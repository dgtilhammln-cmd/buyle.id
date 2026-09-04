<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $categories = [
            [
                'name'        => 'Tiket Konser & Musik',
                'slug'        => 'tiket-konser-musik',
                'tab'         => 'event',
                'badge'       => 'terpopuler',
                'description' => 'Konser musik, festival, dan pertunjukan live',
                'order'       => 7,
                'sub'         => [
                    ['name' => 'Konser Musik Live',     'slug' => 'konser-musik-live',  'order' => 1, 'description' => 'Tiket konser musik solo / band',       'contoh_produk' => 'Tiket Konser Musik, Festival Musik, Intimate Gig'],
                    ['name' => 'Festival & Expo',        'slug' => 'festival-expo',       'order' => 2, 'description' => 'Tiket festival Budaya & Pameran',     'contoh_produk' => 'Tiket Fair, Festival Kuliner, Pameran Seni'],
                    ['name' => 'Pertunjukan Seni & Show','slug' => 'pertunjukan-seni',   'order' => 3, 'description' => 'Tiket teater, komedi, drama & show', 'contoh_produk' => 'Standup Comedy Show, Teater Seni, Pertunjukan Tari'],
                ],
            ],
            [
                'name'        => 'Event & Workshop Edukasi',
                'slug'        => 'event-workshop-edukasi',
                'tab'         => 'event',
                'badge'       => null,
                'description' => 'Webinar, bootcamp, seminar, dan workshop',
                'order'       => 8,
                'sub'         => [
                    ['name' => 'Webinar & Zoom Live',     'slug' => 'webinar-zoom-live',   'order' => 1, 'description' => 'Tiket webinar & kelas streaming online', 'contoh_produk' => 'Webinar Bisnis, Masterclass AI, Live Zoom Mentoring'],
                    ['name' => 'Workshop & Bootcamp',     'slug' => 'workshop-bootcamp',   'order' => 2, 'description' => 'Pelatihan tatap muka / kelas praktek',   'contoh_produk' => 'Workshop Editing Video, Class Coding Offline, Barista Class'],
                    ['name' => 'Seminar & Konferensi',    'slug' => 'seminar-konferensi',  'order' => 3, 'description' => 'Tiket seminar nasional & konferensi',    'contoh_produk' => 'Seminar Entrepreneurship, Conference Tech, Gathering Bisnis'],
                ],
            ],
            [
                'name'        => 'Tiket Wisata & Rekreasi',
                'slug'        => 'tiket-wisata-rekreasi',
                'tab'         => 'event',
                'badge'       => 'naik-daun',
                'description' => 'Wisata alam, hiburan, wahana, dan aktivitas outdoor',
                'order'       => 9,
                'sub'         => [
                    ['name' => 'Tiket Tempat Wisata & Wahana', 'slug' => 'tempat-wisata-wahana', 'order' => 1, 'description' => 'Voucher masuk tempat wisata & wahana',  'contoh_produk' => 'Tiket Taman Bermain, Waterpark, Kebun Binatang'],
                    ['name' => 'Tur & Open Trip',              'slug' => 'tur-open-trip',        'order' => 2, 'description' => 'Paket tur wisata & trip jalan-jalan',    'contoh_produk' => 'Open Trip Gunung, Tour Wisata Pulau, Private Trip'],
                    ['name' => 'Aktivitas Outdoor & Camping',  'slug' => 'aktivitas-outdoor',    'order' => 3, 'description' => 'Booking wahana outdoor & kemping',      'contoh_produk' => 'Booking Camping Ground, Rafting, Campervan, ATV'],
                ],
            ],
            [
                'name'        => 'Olahraga, Hobi & Komunitas',
                'slug'        => 'olahraga-hobi-komunitas',
                'tab'         => 'event',
                'badge'       => null,
                'description' => 'Tiket turnamen, maraton, dan meetup komunitas',
                'order'       => 10,
                'sub'         => [
                    ['name' => 'Lari & Maraton (Fun Run)',    'slug' => 'lari-maraton-fun-run',  'order' => 1, 'description' => 'Tiket event lari & fun run',            'contoh_produk' => 'Tiket 5K/10K Fun Run, Trail Run, Race Pass'],
                    ['name' => 'Turnamen & E-Sports',         'slug' => 'turnamen-esports',      'order' => 2, 'description' => 'Tiket & pendaftaran kompetisi',         'contoh_produk' => 'Slot Turnamen Futsal, MLBB Tournament, Badminton Cup'],
                    ['name' => 'Meetup & Community Gathering','slug' => 'meetup-gathering',      'order' => 3, 'description' => 'Akses event gathering komunitas',       'contoh_produk' => 'Tiket Gath Komunitas Mobil, Cosplay Event, Fan Meeting'],
                ],
            ],
            [
                'name'        => 'Booking & Jasa Layanan Online',
                'slug'        => 'booking-jasa-layanan-online',
                'tab'         => 'event',
                'badge'       => null,
                'description' => 'Reservasi slot, konsultasi, dan jasa booking',
                'order'       => 11,
                'sub'         => [
                    ['name' => 'Reservasi Sesi & Konsultasi 1-on-1', 'slug' => 'reservasi-sesi-1on1',  'order' => 1, 'description' => 'Booking slot waktu konsultasi privat', 'contoh_produk' => 'Booking 1 Hour Private Coaching, Konsultasi Legal/Kesehatan'],
                    ['name' => 'Sewa Lapangan & Studio',             'slug' => 'sewa-lapangan-studio', 'order' => 2, 'description' => 'Booking slot waktu tempat / studio',    'contoh_produk' => 'Booking Studio Foto, Lapangan Badminton/Futsal, Podcast Room'],
                    ['name' => 'Voucher Belanja & E-Gift Card',       'slug' => 'voucher-egift-card',   'order' => 3, 'description' => 'Voucher promo & hadiah digital',         'contoh_produk' => 'Voucher Diskon Toko, Gift Card Event, Pass Member'],
                ],
            ],
        ];

        $now = now();

        foreach ($categories as $catData) {
            $subs = $catData['sub'];
            unset($catData['sub']);

            $existing = DB::table('product_categories')->where('slug', $catData['slug'])->first();

            if (!$existing) {
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
            } else {
                $catId = $existing->id;
            }

            foreach ($subs as $sub) {
                $subExists = DB::table('product_sub_categories')->where('category_id', $catId)->where('slug', $sub['slug'])->exists();
                if (!$subExists) {
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
        }
    }

    public function down(): void
    {
        //
    }
};
