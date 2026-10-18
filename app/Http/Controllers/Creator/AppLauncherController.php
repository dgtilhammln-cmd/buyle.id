<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AppLauncherController extends Controller
{
    /**
     * Tampilkan halaman pusat aplikasi Buyle (App Launcher).
     */
    public function index()
    {
        $apps = [
            [
                'id' => 'webbuilder',
                'name' => 'Web Builder',
                'badge' => 'Landing Page & Bio Link',
                'description' => 'Bangun landing page, bio link interaktif, dan etalase digital untuk memperkuat branding serta penjualan toko Anda.',
                'route' => route('creator.bio.index'),
                'logo_key' => 'app_logo_webbuilder',
                'logo' => Setting::get('app_logo_webbuilder'),
                'theme_color' => '#1eb349',
                'bg_gradient' => 'linear-gradient(135deg, rgba(30,179,73,0.12) 0%, rgba(165,207,55,0.12) 100%)',
                'border_color' => 'rgba(30,179,73,0.3)',
                'icon_type' => 'globe',
            ],
            [
                'id' => 'pos',
                'name' => 'Buyle POS',
                'badge' => 'Kasir Digital Kilat',
                'description' => 'Aplikasi kasir point of sale kilat, kalkulator pesanan otomatis, cetak struk thermal 80mm, serta rekap closing shift kasir.',
                'route' => route('creator.pos.index'),
                'logo_key' => 'app_logo_pos',
                'logo' => Setting::get('app_logo_pos'),
                'theme_color' => '#0284c7',
                'bg_gradient' => 'linear-gradient(135deg, rgba(2,132,199,0.12) 0%, rgba(56,189,248,0.12) 100%)',
                'border_color' => 'rgba(2,132,199,0.3)',
                'icon_type' => 'pos',
            ],
            [
                'id' => 'eticket',
                'name' => 'Buyle e-Ticket',
                'badge' => 'Sistem Tiket & Scan Kehadiran',
                'description' => 'Validasi barcode & tiket event, kontrol akses gerbang masuk, manajemen gate staff, dan rekap kehadiran pengunjung realtime.',
                'route' => route('creator.ticket.scanner'),
                'logo_key' => 'app_logo_eticket',
                'logo' => Setting::get('app_logo_eticket'),
                'theme_color' => '#7c3aed',
                'bg_gradient' => 'linear-gradient(135deg, rgba(124,58,237,0.12) 0%, rgba(167,139,250,0.12) 100%)',
                'border_color' => 'rgba(124,58,237,0.3)',
                'icon_type' => 'ticket',
            ],
        ];

        return view('creator.app.index', compact('apps'));
    }
}
