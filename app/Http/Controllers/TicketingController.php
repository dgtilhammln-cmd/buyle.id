<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class TicketingController extends Controller
{
    /**
     * Display the public Ticketing overview page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = Setting::getAllAsArray();
        $siteName = $settings['site_name'] ?? 'buyle.id';

        $seo = [
            'title'       => 'Ticketing Event, Konser & Webinar Online | ' . $siteName,
            'description' => 'Platform e-ticketing terlengkap dari ' . $siteName . ' untuk kreator, promotor, dan event organizer. Buat tiket event gratis atau berbayar, pengiriman E-Tiket QR PDF otomatis, dan gate scanner panitia cepat tanpa aplikasi tambahan.',
            'keywords'    => 'ticketing event, jual tiket online, e-ticketing konser, tiket seminar webinar, qr code scanner tiket, buyle.id ticketing, gate scanner panitia, check-in tiket event, tiket online indonesia',
            'og_image'    => !empty($settings['og_image_default']) ? asset('storage/' . $settings['og_image_default']) : (!empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('images/og-default.jpg')),
            'canonical'   => route('ticketing'),
        ];

        return view('ticketing.index', compact('settings', 'seo'));
    }
}
