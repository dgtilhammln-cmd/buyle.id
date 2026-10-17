<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Client;

class AboutController extends Controller
{
    public function index()
    {
        $settings     = Setting::getAllAsArray();
        $testimonials = Testimonial::active()->ordered()->get()->unique('name');
        $clients      = Client::active()->ordered()->get();
        $wa           = \App\Models\WaSetting::primary();

        $siteName = $settings['site_name'] ?? 'buyle.id';

        $seo = [
            'title'       => $settings['meta_title_about'] ?? 'Tentang Kami | ' . $siteName,
            'description' => $settings['meta_desc_about'] ?? 'Profil ' . $siteName . ', ekosistem kreator digital, e-ticketing event, web builder, dan marketplace aset kreatif terpercaya di Indonesia.',
            'keywords'    => $settings['meta_keywords_about'] ?? 'tentang buyle.id, digital creator marketplace, e-ticketing, web builder, reseller affiliate, jasa freelance',
            'og_image'    => !empty($settings['og_image_default']) ? asset('storage/'.$settings['og_image_default']) : (!empty($settings['logo']) ? asset('storage/'.$settings['logo']) : asset('images/og-default.jpg')),
            'canonical'   => route('about'),
        ];

        // Ambil data keunggulan dari Setting (Seeder) atau fallback default buyle.id
        $rawKeunggulan = $settings['about_keunggulan'] ?? Setting::get('about_keunggulan');
        $keunggulan = [];
        if ($rawKeunggulan) {
            $keunggulan = is_array($rawKeunggulan) ? $rawKeunggulan : json_decode($rawKeunggulan, true);
        }

        if (empty($keunggulan)) {
            $keunggulan = [
                [
                    'key'         => 'eticketing',
                    'badge'       => 'E-Tiket',
                    'title'       => 'E-Ticketing & Event Pass',
                    'desc'        => 'Solusi tiket digital lengkap untuk konser, workshop, webinar, dan wahana wisata. Dilengkapi QR Code scan cepat anti-duplikasi serta dashboard check-in gate staff.',
                    'icon'        => 'ticket',
                    'style_class' => 'accent',
                ],
                [
                    'key'         => 'web_builder',
                    'badge'       => 'No-Code',
                    'title'       => 'Web & Bio Link Builder',
                    'desc'        => 'Bangun landing page dan link-in-bio toko kustom instan tanpa koding. Mendukung kustom domain, tema fleksibel, formulir pemesanan, dan analytics pengunjung langsung.',
                    'icon'        => 'layout',
                    'style_class' => '',
                ],
                [
                    'key'         => 'reseller_affiliate',
                    'badge'       => 'Bebas Margin',
                    'title'       => 'Whitelabel & Jual Produk Mitra',
                    'desc'        => 'Mulai bisnis tanpa modal produk sendiri. Jual produk digital kreator lain dengan sistem whitelabel pakai harga Anda sendiri atau bagikan link afiliasi dengan komisi otomatis.',
                    'icon'        => 'repeat',
                    'style_class' => '',
                ],
                [
                    'key'         => 'escrow_safe',
                    'badge'       => '100% Aman',
                    'title'       => 'Transaksi Terlindungi Escrow',
                    'desc'        => 'Keamanan transaksi terjamin penuh. Pembayaran pembeli diamankan sistem escrow sampai produk diterima, dan saldo penjualan kreator dapat dicairkan instan ke rekening bank.',
                    'icon'        => 'shield',
                    'style_class' => 'accent-dark',
                ],
                [
                    'key'         => 'digital_products',
                    'badge'       => 'Instan',
                    'title'       => 'Marketplace Produk Digital',
                    'desc'        => 'Akses instan tanpa jeda untuk template Canva/Notion, source code, desain grafis, e-book, hingga course edukasi. File terkirim otomatis detik itu juga setelah bayar.',
                    'icon'        => 'download',
                    'style_class' => '',
                ],
                [
                    'key'         => 'freelance_service',
                    'badge'       => '1-on-1',
                    'title'       => 'Jasa Kreator & Konsultasi Privat',
                    'desc'        => 'Pesan layanan profesional seperti desain, penulisan, koding, hingga mentoring privat 1-on-1 dengan kalender jadwal tersinkronisasi dan brief terstruktur.',
                    'icon'        => 'calendar',
                    'style_class' => '',
                ],
                [
                    'key'         => 'payment_ecosystem',
                    'badge'       => 'Terpadu',
                    'title'       => 'Ekosistem Pembayaran Lengkap & Payout Cepat',
                    'desc'        => 'Mendukung QRIS semua bank & e-wallet (GoPay, OVO, DANA, ShopeePay), Virtual Account, dan Kartu Kredit. Integrasi all-in-one dari pembuatan website, tiket event, hingga penjualan aset digital.',
                    'icon'        => 'credit-card',
                    'style_class' => 'span-2',
                ],
            ];
        }

        $legalitas = [
            ['label' => 'NIB',  'value' => $settings['nib']  ?? '-'],
            ['label' => 'NPWP', 'value' => $settings['npwp'] ?? '-'],
        ];

        return view('about.index', compact('settings', 'testimonials', 'clients', 'seo', 'keunggulan', 'legalitas', 'wa'));
    }
}
