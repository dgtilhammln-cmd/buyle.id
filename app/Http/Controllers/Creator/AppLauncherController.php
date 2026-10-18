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
                'id'          => 'webbuilder',
                'name'        => 'Web Builder',
                'badge'       => 'Halaman Digital & Bio Link',
                'description' => 'Bangun semua aset digital bisnis Anda — dari Link in Bio interaktif, Website Company Profile, hingga toko E-Commerce — lengkap dengan sistem Affiliate & Whitelabel.',
                'features'    => [
                    ['icon' => 'link',    'label' => 'Link in Bio'],
                    ['icon' => 'globe',   'label' => 'Company Profile'],
                    ['icon' => 'shop',    'label' => 'E-Commerce'],
                    ['icon' => 'percent', 'label' => 'Affiliate'],
                    ['icon' => 'tag',     'label' => 'Whitelabel'],
                ],
                'route'        => route('creator.bio.index'),
                'logo_key'     => 'app_logo_webbuilder',
                'logo'         => Setting::get('app_logo_webbuilder'),
                'theme_color'  => '#1eb349',
                'bg_gradient'  => 'linear-gradient(135deg, rgba(30,179,73,0.12) 0%, rgba(165,207,55,0.12) 100%)',
                'border_color' => 'rgba(30,179,73,0.3)',
                'icon_type'    => 'globe',
            ],
            [
                'id'          => 'pos',
                'name'        => 'Buyle POS',
                'badge'       => 'Kasir Digital All-in-One',
                'description' => 'Kasir digital lengkap untuk semua jenis usaha — dari warung, kafe, hingga event. Proses transaksi kilat, cetak struk, dan closing laporan dalam satu layar.',
                'features'    => [
                    ['icon' => 'zap',     'label' => 'Kasir Kilat'],
                    ['icon' => 'calc',    'label' => 'Kalkulator Otomatis'],
                    ['icon' => 'print',   'label' => 'Cetak Struk 80mm'],
                    ['icon' => 'qris',    'label' => 'QRIS & Tunai'],
                    ['icon' => 'mail',    'label' => 'E-Receipt Otomatis'],
                    ['icon' => 'report',  'label' => 'Closing & Rekap Shift'],
                    ['icon' => 'wallet',  'label' => 'Deposit Kasir'],
                ],
                'route'        => route('creator.pos.index'),
                'logo_key'     => 'app_logo_pos',
                'logo'         => Setting::get('app_logo_pos'),
                'theme_color'  => '#0284c7',
                'bg_gradient'  => 'linear-gradient(135deg, rgba(2,132,199,0.12) 0%, rgba(56,189,248,0.12) 100%)',
                'border_color' => 'rgba(2,132,199,0.3)',
                'icon_type'    => 'pos',
            ],
            [
                'id'          => 'eticket',
                'name'        => 'Buyle e-Ticket',
                'badge'       => 'Tiket & Scan Kehadiran',
                'description' => 'Validasi barcode & tiket event, kontrol akses gerbang masuk, manajemen gate staff, dan rekap kehadiran pengunjung realtime.',
                'features'    => [
                    ['icon' => 'scan',    'label' => 'Scan Barcode'],
                    ['icon' => 'gate',    'label' => 'Gate Access'],
                    ['icon' => 'users',   'label' => 'Gate Staff'],
                    ['icon' => 'chart',   'label' => 'Rekap Realtime'],
                    ['icon' => 'pin',     'label' => 'PIN & Bypass'],
                ],
                'route'        => route('creator.ticket.scanner'),
                'logo_key'     => 'app_logo_eticket',
                'logo'         => Setting::get('app_logo_eticket'),
                'theme_color'  => '#7c3aed',
                'bg_gradient'  => 'linear-gradient(135deg, rgba(124,58,237,0.12) 0%, rgba(167,139,250,0.12) 100%)',
                'border_color' => 'rgba(124,58,237,0.3)',
                'icon_type'    => 'ticket',
            ],
        ];

        return view('creator.app.index', compact('apps'));
    }
}
