<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TixController extends Controller
{
    /**
     * Display the dedicated Ticketing Marketplace (Buyle TIX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $settings = Setting::getAllAsArray();
        $siteName = $settings['site_name'] ?? 'buyle.id';

        $search = trim($request->query('q', ''));
        $type = $request->query('type'); // 'offline', 'online', or null (all)
        $today = Carbon::now()->startOfDay();

        // Base Query for Ticketing Products
        $baseQuery = Product::where(function ($q) {
                $q->where('product_type', 'ticket')
                  ->orWhereNotNull('event_date');
            })
            ->where('is_active', true)
            ->with(['seller.creatorProfile', 'category']);

        // Search Filter
        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('event_location', 'like', "%{$search}%")
                  ->orWhere('short_desc', 'like', "%{$search}%")
                  ->orWhereHas('seller', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Type Filter (Offline / Online)
        if ($type && in_array($type, ['offline', 'online'])) {
            $baseQuery->where('event_type', $type);
        }

        // AJAX Infinite Scroll Request for Upcoming Events
        if ($request->ajax()) {
            $page = (int) $request->input('page', 1);
            $perPage = 8;
            $paginated = (clone $baseQuery)
                ->where(function ($q) use ($today) {
                    $q->whereNull('event_date')
                      ->orWhere('event_date', '>=', $today);
                })
                ->orderByRaw('CASE WHEN event_date IS NULL THEN 1 ELSE 0 END, event_date ASC')
                ->paginate($perPage, ['*'], 'page', $page);

            $html = view('tix.partials.event_cards', ['events' => $paginated->items()])->render();

            return response()->json([
                'html'         => $html,
                'has_more'     => $paginated->hasMorePages(),
                'current_page' => $paginated->currentPage(),
                'last_page'    => $paginated->lastPage(),
                'total'        => $paginated->total(),
            ]);
        }

        // Cache halaman utama TIX (tanpa filter/search) selama 60 detik.
        // Saat event besar dibuka bersamaan ribuan orang, DB tidak dihantam tiap request.
        $cacheKey = 'tix_index_' . ($search ?: 'all') . '_' . ($type ?: 'all') . '_' . $today->format('Ymd');
        $cacheSeconds = ($search || $type) ? 10 : 60; // filter/search: 10s; default: 60s

        [$upcomingEvents, $hasMoreUpcoming, $pastEvents] = Cache::remember($cacheKey, $cacheSeconds, function () use ($baseQuery, $today) {
            // 1. Event Terdekat
            $upcomingPaginated = (clone $baseQuery)
                ->where(function ($q) use ($today) {
                    $q->whereNull('event_date')
                      ->orWhere('event_date', '>=', $today);
                })
                ->orderByRaw('CASE WHEN event_date IS NULL THEN 1 ELSE 0 END, event_date ASC')
                ->paginate(8);

            // 2. Event Berakhir
            $pastEvents = (clone $baseQuery)
                ->whereNotNull('event_date')
                ->where('event_date', '<', $today)
                ->orderBy('event_date', 'desc')
                ->get();

            return [
                $upcomingPaginated->items(),
                $upcomingPaginated->hasMorePages(),
                $pastEvents,
            ];
        });

        // 3. Carousel Banners (1920 x 600 clean image slides from Admin / Seeder)
        $banners = $this->buildBanners();

        $seo = [
            'title'       => 'Buyle TIX - Platform Tiket Event, Konser & Webinar Online | ' . $siteName,
            'description' => 'Eksplorasi dan dapatkan tiket konser musik, festival, seminar, workshop, dan event seru di Buyle TIX. E-Tiket instan QR code, pembayaran mudah & aman.',
            'keywords'    => 'buyle tix, tiket konser, beli tiket event, tiket seminar online, tiket webinar, jadwal konser indonesia, tiket murah buyle',
            'og_image'    => $banners[0]['image'] ?? asset('images/buyle-og.png'),
            'canonical'   => route('tix.index'),
        ];

        return view('tix.index', compact(
            'upcomingEvents',
            'hasMoreUpcoming',
            'pastEvents',
            'banners',
            'settings',
            'seo',
            'search',
            'type'
        ));
    }

    /**
     * Build landscape banners (1920 x 600) for the top carousel.
     * Dedicated pure image banners seeded for Buyle TIX (position = 'tix').
     * Displays clean uploaded image or a modern placeholder (no text crop).
     *
     * @return array
     */
    protected function buildBanners(): array
    {
        $banners = [];

        // Fetch custom banners configured by Admin specifically for Buyle TIX (position = 'tix')
        $adminSlides = HeroSlide::where('position', 'tix')
            ->where('is_active', true)
            ->ordered()
            ->get();

        // If none exist yet, automatically trigger seeder
        if ($adminSlides->isEmpty()) {
            try {
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'TixHeroSlideSeeder', '--force' => true]);
                $adminSlides = HeroSlide::where('position', 'tix')
                    ->where('is_active', true)
                    ->ordered()
                    ->get();
            } catch (\Exception $e) {
                // Ignore seeder exception if any
            }
        }

        foreach ($adminSlides as $slide) {
            $banners[] = [
                'id'          => $slide->id,
                'title'       => $slide->title ?: 'Banner Buyle TIX',
                'description' => $slide->description,
                'image'       => $slide->image ? asset('storage/' . $slide->image) : null,
                'url'         => $slide->button_url ?: '#event-terdekat',
            ];
        }

        return $banners;
    }
}

