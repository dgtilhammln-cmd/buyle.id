<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariantOption;
use App\Models\ProductVariantValue;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Cari user seller hvmdigital
        $seller = User::where('username', 'hvmdigital')
            ->orWhere('email', 'hvm@buyle.id')
            ->orWhereHas('creatorProfile', function ($q) {
                $q->where('store_slug', 'hvmdigital');
            })
            ->first() ?? User::find(4) ?? User::first();

        if (!$seller) {
            return;
        }

        // 2. Kategori Konser & Musik
        $category = ProductCategory::where('slug', 'tiket-konser-musik')->first()
            ?? ProductCategory::find(13)
            ?? ProductCategory::first();

        $catId = $category ? $category->id : null;

        // 3. Salin gambar banner/poster jika ada
        $storageDir = storage_path('app/public/products');
        if (!File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        $sourceImg1 = public_path('images/ticketing/concert1.jpg');
        $sourceImg2 = public_path('images/ticketing/concert3.jpg');

        $posterPath = 'products/buylefest-poster.jpg';
        $bannerPath = 'products/buylefest-banner.jpg';

        if (File::exists($sourceImg1)) {
            File::copy($sourceImg1, storage_path('app/public/' . $posterPath));
        }
        if (File::exists($sourceImg2)) {
            File::copy($sourceImg2, storage_path('app/public/' . $bannerPath));
        }

        // 4. Copywriting Deskripsi & T&C
        $shortDesc = "[SIMULASI / DUMMY EVENT] Festival musik & seni kreatif terbesar penutup tahun 2026 di Surabaya! Menampilkan line-up musisi ternama, tata panggung spektakuler, creative market, dan food bazaar.";

        $description = <<<HTML
<div style="background: #FEF2F2; border-left: 4px solid #EF4444; padding: 1.1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem;">
    <h4 style="color: #991B1B; margin: 0 0 0.5rem 0; font-size: 1.05rem; font-weight: 700; display: flex; align-items: center; gap: 6px;">
        <span>⚠️</span> PEMBERITAHUAN: DUMMY / SIMULASI EVENT TIKET
    </h4>
    <p style="color: #B91C1C; margin: 0; font-size: 0.9rem; line-height: 1.6;">
        Produk ini adalah <strong>PRODUK SIMULASI / CONTOH TIKETING RESMI</strong> yang dibuat khusus untuk demonstrasi ekosistem <strong>Buyle TIX</strong>, flow pemesanan tiket online, integrasi barcode E-Tiket, dan validasi scanner gate <strong>Buyle Gate Scanner</strong>. Pemesanan tidak mewakili tiket konser fisik komersil.
    </p>
</div>

<h3>✨ Tentang BuyleFest 2026</h3>
<p>
    Sambut perayaan musik dan industri kreatif termegah di penghujung tahun: <strong>BuyleFest 2026</strong>! Mempertemukan panggung konser multi-genre, instalasi seni interaktif modern, komunitas kreator digital, serta festival kuliner legendaris dalam satu arena spektakuler di <strong>Jatim Expo Surabaya</strong>.
</p>

<h3>🎸 Line-up Bintang Tamu (Dummy Simulation)</h3>
<ul>
    <li><strong>Sheila on 7</strong> (Special Guest Star Headliner)</li>
    <li><strong>Hindia</strong> (Indie Pop / Rock)</li>
    <li><strong>Nadin Amizah</strong> (Folk / Acoustic)</li>
    <li><strong>NDX A.K.A</strong> (Dangdut Hip-Hop Party)</li>
    <li><strong>Diskoria ft. Special Friends</strong> (Retro Disco Party)</li>
    <li><strong>Opening Act:</strong> Top 3 Juara Buyle Music Creator Competition 2026</li>
</ul>

<h3>🎪 Festival Experience & Area Acara</h3>
<ul>
    <li><strong>Main Stage Arena:</strong> Panggung konser megah berstandar internasional dengan tata suara L-Acoustics dan visual kinetic lighting 360°.</li>
    <li><strong>Buyle Creator Corner:</strong> Booth live podcast, meet & greet eksklusif creator, dan instalasi interactive photobox.</li>
    <li><strong>Taste of Archipelago Food Bazaar:</strong> 40+ tenant kuliner viral pilihan Jawa Timur & nasional.</li>
    <li><strong>Official Merchandise Hub:</strong> Kaos festival, tote bag, lanyard, dan collectibles resmi BuyleFest 2026.</li>
</ul>

<h3>📅 Jadwal & Rundown Acara</h3>
<ul>
    <li><strong>15:00 WIB:</strong> Open Gate & Penukaran Wristband / Validasi QR Code Gate Scanner</li>
    <li><strong>15:30 WIB:</strong> Opening DJ Set & Community Activation</li>
    <li><strong>16:30 WIB:</strong> Opening Act Performances</li>
    <li><strong>18:00 WIB:</strong> Break Maghrib & Rest Time</li>
    <li><strong>18:45 WIB:</strong> Session 1: Nadin Amizah & Hindia</li>
    <li><strong>20:30 WIB:</strong> Session 2: NDX A.K.A</li>
    <li><strong>21:45 WIB:</strong> Grand Finale: Sheila on 7 & Diskoria Party</li>
    <li><strong>23:15 WIB:</strong> Event Closed</li>
</ul>

<div style="background: #F0FDF4; border-left: 4px solid #16A34A; padding: 1.1rem 1.25rem; border-radius: 10px; margin-top: 1.5rem;">
    <h4 style="color: #166534; margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700;">🎟️ Keunggulan Pemesanan via Buyle TIX</h4>
    <p style="color: #15803D; margin: 0; font-size: 0.88rem; line-height: 1.6;">
        • <strong>E-Tiket Instan:</strong> QR code resmi langsung terbit di dashboard dan email setelah pembayaran lunas.<br>
        • <strong>Check-in Cepat:</strong> Scan gate &lt; 1 detik dengan Buyle Gate Scanner anti-duplikasi.<br>
        • <strong>Metode Pembayaran Fleksibel:</strong> QRIS instan seluruh bank & e-wallet, Virtual Account otomatis, dan Transfer Bank aman.
    </p>
</div>
HTML;

        $tnc = <<<TXT
⚠️ PEMBERITAHUAN SIMULASI (DUMMY):
Produk tiket ini adalah CONTOH / SIMULASI untuk keperluan demonstrasi sistem penjualan tiket online Buyle TIX dan Gate Scanner. Pemesanan tidak mewakili acara konser fisik komersil.

SYARAT & KETENTUAN UMUM (T&C) BUYLEFEST 2026:
1. KEPEMILIKAN TIKET:
   - 1 (satu) e-tiket ber-QR Code hanya berlaku untuk 1 (satu) orang pengunjung sesuai kategori tiket yang dibeli.
   - Maksimal pembelian per transaksi adalah 5 (lima) tiket.
   - E-tiket resmi akan dikirimkan otomatis ke email pembeli dan dapat diakses kapan saja pada menu "Tiket Saya" di akun Buyle Anda.

2. AKSES MASUK & VALIDASI CHECK-IN:
   - Pintu masuk (gate) dibuka mulai pukul 15:00 WIB dan ditutup pukul 21:00 WIB.
   - Pengunjung wajib menunjukkan Barcode / QR Code E-Tiket asli dari layar smartphone atau cetakan fisik kepada petugas Gate Scanner di pintu masuk.
   - Pengunjung wajib membawa kartu identitas diri asli yang sah dan masih berlaku (KTP / SIM / Paspor / Kartu Pelajar / Mahasiswa) yang sesuai dengan nama pada pesanan tiket.

3. KETENTUAN USIA:
   - Acara ini terbuka untuk umum (usia 12 tahun ke atas).
   - Pengunjung di bawah usia 15 tahun wajib didampingi oleh orang tua atau wali dewasa yang juga memiliki tiket resmi.

4. BARANG-BARANG YANG DILARANG DIBAWA KE VENUE:
   - Senjata tajam, senjata api, bahan peledak, atau benda berbahaya lainnya.
   - Narkotika, psikotropika, minuman beralkohol, dan obat-obatan terlarang.
   - Makanan dan minuman dari luar area venue (tersedia puluhan tenant kuliner resmi di dalam venue).
   - Kamera profesional (DSLR, Mirrorless, lensa tele), drone, tripod, dan tongsis tanpa tanda pengenal pers resmi.
   - Laser pointer, flare, smoke bomb, petasan, kembang api, atau benda yang menimbulkan percikan api.

5. KEBIJAKAN PENGEMBALIAN DANA (REFUND):
   - Tiket yang sudah dibeli bersifat NON-REFUNDABLE (tidak dapat dibatalkan, ditukar, atau diuangkan kembali) untuk alasan pribadi pembeli.
   - Jika acara mengalami penundaan (rescheduled) atau pembatalan sepihak oleh penyelenggara karena keadaan darurat (force majeure), maka mekanisme pengembalian dana akan diatur sepenuhnya sesuai regulasi penyelenggara.

6. PROTOKOL & KEAMANAN:
   - Penyelenggara berhak menolak masuk atau mengeluarkan pengunjung yang melanggar norma kesopanan, mengganggu ketertiban umum, atau berada di bawah pengaruh zat terlarang tanpa kewajiban kompensasi apapun.
   - Jagalah barang bawaan pribadi Anda. Pihak penyelenggara tidak bertanggung jawab atas kehilangan atau kerusakan barang bawaan pribadi di seluruh area acara.
TXT;

        // 5. Update or Create Product
        $product = Product::updateOrCreate(
            ['slug' => 'buylefest'],
            [
                'seller_id'                  => $seller->id,
                'product_category_id'        => $catId,
                'name'                       => 'BuyleFest 2026 — Music & Creative Festival (Simulasi Event)',
                'slug'                       => 'buylefest',
                'short_desc'                 => $shortDesc,
                'description'                => $description,
                'terms_conditions'           => $tnc,
                'product_type'               => 'ticket',
                'type'                       => 'product',
                'event_type'                 => 'offline',
                'event_date'                 => '2026-12-20',
                'event_end_date'             => '2026-12-20',
                'event_time'                 => '15:00 - 23:00 WIB',
                'event_location'             => 'Jatim Expo Convention Hall, Jl. Ahmad Yani No. 99, Surabaya, Jawa Timur',
                'price'                      => 150000.00,
                'sale_price'                 => 125000.00,
                'stock'                      => 1600,
                'is_active'                  => true,
                'is_available'               => true,
                'is_featured'                => true,
                'max_tickets_per_order'      => 5,
                'is_buyle_checkout'          => true,
                'gate_scanner_enabled'       => true,
                'gate_scanner_key'           => 'BF26-GATE-DUMMY789',
                'gate_scanner_pin'           => '123456',
                'gate_open_time'             => '15:00',
                'gate_close_time'            => '21:00',
                'image'                      => $posterPath,
                'og_image'                   => $bannerPath,
                'meta_title'                 => 'Tiket BuyleFest 2026 — Music & Creative Festival (Simulasi) | BuyleTIX',
                'meta_desc'                  => 'Dapatkan tiket BuyleFest 2026 (Simulasi Event) di Jatim Expo Surabaya. Line-up Sheila on 7, Hindia, Nadin Amizah, NDX AKA. Pesan e-tiket instan via BuyleTIX!',
                'meta_keywords'              => 'buylefest, tiket buylefest, konser surabaya, buyle tix, tiket konser, festival musik surabaya, simulasi tiket',
                'affiliate_commission_rate'  => 10.00,
            ]
        );

        // 6. Setup Variant Option & Values
        $option = ProductVariantOption::firstOrCreate(
            [
                'product_id' => $product->id,
                'name'       => 'Kategori Tiket',
            ]
        );

        $variantsData = [
            [
                'value'            => 'Early Bird - Festival Regular',
                'price'            => 99000.00,
                'price_adjustment' => 0,
                'stock'            => 200,
                'sku'              => 'TIX-BF26-EB',
            ],
            [
                'value'            => 'Presale - Festival Regular',
                'price'            => 125000.00,
                'price_adjustment' => 0,
                'stock'            => 800,
                'sku'              => 'TIX-BF26-PRE',
            ],
            [
                'value'            => 'VIP Lounge Experience (Seated & Merchandise)',
                'price'            => 250000.00,
                'price_adjustment' => 0,
                'stock'            => 400,
                'sku'              => 'TIX-BF26-VIP',
            ],
            [
                'value'            => 'Super VIP All-Access + Meet & Greet',
                'price'            => 450000.00,
                'price_adjustment' => 0,
                'stock'            => 200,
                'sku'              => 'TIX-BF26-SVIP',
            ],
        ];

        ProductVariantValue::where('variant_option_id', $option->id)->delete();

        foreach ($variantsData as $v) {
            ProductVariantValue::create([
                'variant_option_id' => $option->id,
                'value'             => $v['value'],
                'price'             => $v['price'],
                'price_adjustment'  => $v['price_adjustment'],
                'stock'             => $v['stock'],
                'sku'               => $v['sku'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $product = Product::where('slug', 'buylefest')->first();
        if ($product) {
            $options = ProductVariantOption::where('product_id', $product->id)->get();
            foreach ($options as $opt) {
                ProductVariantValue::where('variant_option_id', $opt->id)->delete();
                $opt->delete();
            }
            $product->delete();
        }
    }
};
