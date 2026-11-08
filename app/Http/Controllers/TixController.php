<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
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

        // 1. Event Terdekat (Upcoming Events: event_date >= today or null)
        $upcomingEvents = (clone $baseQuery)
            ->where(function ($q) use ($today) {
                $q->whereNull('event_date')
                  ->orWhere('event_date', '>=', $today);
            })
            ->orderByRaw('CASE WHEN event_date IS NULL THEN 1 ELSE 0 END, event_date ASC')
            ->get();

        // 2. Event Berakhir (Past Events: event_date < today)
        $pastEvents = (clone $baseQuery)
            ->whereNotNull('event_date')
            ->where('event_date', '<', $today)
            ->orderBy('event_date', 'desc')
            ->get();

        // 3. Carousel Banners (1920 x 600 style)
        $banners = $this->buildBanners($upcomingEvents);

        $seo = [
            'title'       => 'Buyle TIX – Marketplace Tiket Event, Konser & Webinar Online | ' . $siteName,
            'description' => 'Eksplorasi dan dapatkan tiket konser musik, festival, seminar, workshop, dan event seru di Buyle TIX. E-Tiket instan QR code, pembayaran mudah & aman.',
            'keywords'    => 'buyle tix, tiket konser, beli tiket event, tiket seminar online, tiket webinar, jadwal konser indonesia, tiket murah buyle',
            'og_image'    => $banners[0]['image'] ?? asset('images/buyle-og.png'),
            'canonical'   => route('tix.index'),
        ];

        return view('tix.index', compact(
            'upcomingEvents',
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
     * Combines active upcoming events with rich curated showcase slides.
     *
     * @param  \Illuminate\Support\Collection  $upcomingEvents
     * @return array
     */
    protected function buildBanners($upcomingEvents): array
    {
        $banners = [];

        // 1. Add real upcoming events that have featured images or standard images
        foreach ($upcomingEvents as $evt) {
            $img = $evt->featured_image ? asset('storage/' . $evt->featured_image) : ($evt->image ? asset('storage/' . $evt->image) : null);
            $organizer = $evt->seller ? ($evt->seller->creatorProfile->display_name ?? $evt->seller->name) : 'Official Event Organizer';

            $banners[] = [
                'is_dynamic'  => true,
                'title'       => $evt->name,
                'organizer'   => $organizer,
                'date'        => $evt->event_date ? $evt->event_date->translatedFormat('d M Y') : 'Segera Hadir',
                'location'    => $evt->event_location ?: ($evt->event_type === 'online' ? 'Online Streaming' : 'Indonesia'),
                'image'       => $img,
                'price'       => 'Rp ' . number_format($evt->sale_price ?: $evt->price, 0, ',', '.'),
                'badge'       => strtoupper($evt->event_type ?: 'EVENT'),
                'url'         => route('products.show', $evt->slug),
            ];

            if (count($banners) >= 3) break;
        }

        // 2. Add curated showcase landscape slides (1920 x 600) matching Image 1 aesthetics
        $showcases = [
            [
                'is_dynamic'  => false,
                'title'       => 'KONSER NASYID NOSTALGIA',
                'subtitle'    => 'SNADA ★ IZZATUL ISLAM ★ EDCOUSTIC ★ THE RAYHAN',
                'organizer'   => 'TRUST ENTERTAINMENT',
                'date'        => '15 NOV 2026',
                'location'    => 'JAKARTA',
                'image'       => null, // Will use CSS dark retro-stage synthwave gradient
                'bg_gradient' => 'linear-gradient(135deg, #090a1a 0%, #171b3c 40%, #2a113a 70%, #0d1b2a 100%)',
                'accent_color'=> '#38bdf8',
                'badge'       => 'KONSER MUSIK',
                'url'         => !empty($banners[0]['url']) ? $banners[0]['url'] : '#event-terdekat',
            ],
            [
                'is_dynamic'  => false,
                'title'       => 'INDONESIA DIGITAL SUMMIT 2026',
                'subtitle'    => 'Artificial Intelligence, Future Tech & Creative Economy Expo',
                'organizer'   => 'HVM DIGITAL X BLUEPRYNTS',
                'date'        => '30 NOV 2026',
                'location'    => 'SURABAYA',
                'image'       => null,
                'bg_gradient' => 'linear-gradient(135deg, #031e13 0%, #064e3b 45%, #0f172a 100%)',
                'accent_color'=> '#34d399',
                'badge'       => 'EXPO & CONFERENCE',
                'url'         => !empty($banners[0]['url']) ? $banners[0]['url'] : '#event-terdekat',
            ],
            [
                'is_dynamic'  => false,
                'title'       => 'CREATOR FESTIVAL & TALKSHOW',
                'subtitle'    => 'Monetisasi Karya, Bio Link & Solusi Ticketing Mandiri',
                'organizer'   => 'BUYLE.ID CREATOR NETWORK',
                'date'        => 'DESEMBER 2026',
                'location'    => 'ONLINE & HYBRID',
                'image'       => null,
                'bg_gradient' => 'linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #0f766e 100%)',
                'accent_color'=> '#a7f3d0',
                'badge'       => 'WORKSHOP RESMI',
                'url'         => url('/creator/products?from=ticketing'),
            ],
        ];

        // Merge to guarantee at least 3 slides in the carousel
        foreach ($showcases as $sc) {
            if (count($banners) >= 5) break;
            $banners[] = $sc;
        }

        return $banners;
    }
}
