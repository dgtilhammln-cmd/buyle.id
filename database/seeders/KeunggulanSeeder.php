<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\UspItem;

class KeunggulanSeeder extends Seeder
{
    /**
     * Run the database seeds for buyle.id core values & advantages.
     */
    public function run(): void
    {
        $keunggulanData = [
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

        // 1. Simpan ke tabel Settings sebagai JSON untuk About Us & Landing Pages
        Setting::updateOrCreate(
            ['key' => 'about_keunggulan'],
            [
                'value' => json_encode($keunggulanData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                'type'  => 'json',
                'group' => 'about',
                'label' => 'Daftar Keunggulan Platform',
            ]
        );

        // 2. Perbarui juga USP Items (Home USP Bar) agar senada dengan value buyle.id
        UspItem::truncate();

        $uspList = [
            ['label' => 'E-Ticketing & Event Pass',       'sort_order' => 1],
            ['label' => 'Web & Bio Link Builder',        'sort_order' => 2],
            ['label' => 'Whitelabel & Komisi Afiliasi',   'sort_order' => 3],
            ['label' => 'Produk Digital Download Instan', 'sort_order' => 4],
            ['label' => 'Transaksi Aman Escrow 100%',    'sort_order' => 5],
        ];

        foreach ($uspList as $usp) {
            UspItem::create([
                'icon_type'  => 'font',
                'icon_value' => 'check-circle',
                'label'      => $usp['label'],
                'sort_order' => $usp['sort_order'],
                'is_active'  => true,
            ]);
        }

        Setting::clearCache();

        $this->command->info('KeunggulanSeeder: Value Keunggulan buyle.id berhasil diperbarui ke database!');
    }
}
