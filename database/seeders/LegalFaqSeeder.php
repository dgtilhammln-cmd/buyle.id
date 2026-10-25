<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class LegalFaqSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. SYARAT & KETENTUAN ──
        $syaratContent = '
            <p>Selamat datang di <strong>buyle.id</strong>. Syarat dan Ketentuan berikut mengatur penggunaan Anda atas platform, situs web, layanan transaksi, dan ekosistem digital kami (selanjutnya disebut "Layanan").</p>
            <p>Dengan mendaftar, mengakses, atau menggunakan Layanan buyle.id, Anda menyatakan bahwa Anda telah membaca, memahami, dan menyetujui untuk terikat secara hukum oleh Syarat dan Ketentuan ini.</p>

            <h2>1. Ketentuan Umum & Pendaftaran Akun</h2>
            <ul>
                <li><strong>Kelayakan:</strong> Pengguna harus berusia minimal 17 tahun atau telah memiliki kartu identitas hukum yang sah untuk melakukan transaksi komersial di platform kami.</li>
                <li><strong>Keamanan Akun:</strong> Anda bertanggung jawab penuh untuk menjaga kerahasiaan kata sandi dan informasi autentikasi akun Anda. Segala aktivitas yang terjadi melalui akun Anda adalah tanggung jawab Anda sepenuhnya.</li>
                <li><strong>Informasi yang Akurat:</strong> Anda wajib memberikan data pendaftaran yang benar, akurat, terkini, dan lengkap, termasuk saat proses verifikasi identitas (e-KYC) bagi Creator/Seller.</li>
            </ul>

            <h2>2. Peran Platform & Transaksi Digital</h2>
            <p>buyle.id berfungsi sebagai platform marketplace dan web builder digital yang mempertemukan Kreator Konten (Seller) dengan Pembeli (Buyer):</p>
            <ul>
                <li><strong>Produk Digital & Tiket Event:</strong> Pembelian produk digital (e-book, template, software, file unduhan) dan tiket event/konser diproses secara langsung melalui gateway pembayaran resmi buyle.id.</li>
                <li><strong>Akses Instant:</strong> Setelah pembayaran terkonfirmasi, file unduhan atau tiket digital (e-voucher / QR Code) akan segera tersedia di dasbor pembeli dan dikirimkan salinannya melalui email terdaftar.</li>
                <li><strong>Biaya Layanan & Pembayaran:</strong> Semua transaksi diproses dalam mata uang Rupiah (IDR) menggunakan metode pembayaran yang sah (QRIS, Virtual Account, E-Wallet, Kartu Kredit).</li>
            </ul>

            <h2>3. Hak Cipta & Kekayaan Intelektual</h2>
            <ul>
                <li><strong>Hak Kreator:</strong> Seluruh hak kekayaan intelektual atas karya, produk digital, dan materi yang diunggah oleh kreator tetap menjadi milik sah dari kreator bersangkutan.</li>
                <li><strong>Lisensi Penggunaan:</strong> Pembeli memperoleh lisensi non-eksklusif, tidak dapat dipindahtangankan, dan hanya untuk penggunaan pribadi (kecuali produk mencantumkan lisensi komersial secara tertulis). Dilarang keras mendistribusikan ulang, menjual kembali, atau membajak karya tanpa izin tertulis.</li>
            </ul>

            <h2>4. Kebijakan Pembatalan & Pengembalian Dana (Refund)</h2>
            <p>Mengingat sifat produk digital yang langsung dapat diakses atau diunduh setelah pembayaran berhasil, <em>pengembalian dana (refund)</em> secara umum tidak dapat dilakukan, kecuali:</p>
            <ul>
                <li>File produk terbukti rusak permanen dan kreator tidak memberikan perbaikan dalam waktu 3x24 jam.</li>
                <li>Acara/Event dibatalkan secara resmi oleh penyelenggara (kebijakan refund tiket mengikuti ketentuan resmi promotor event).</li>
                <li>Terjadi kesalahan teknis pada sistem pembayaran ganda (double charge).</li>
            </ul>

            <h2>5. Larangan Penggunaan</h2>
            <p>Pengguna dilarang keras mengunggah atau mendistribusikan konten yang mengandung pornografi, ujaran kebencian, penipuan, pelanggaran hak cipta, materi berbahaya/malware, atau melanggar hukum perundang-undangan Republik Indonesia (termasuk UU ITE).</p>

            <h2>6. Perubahan Ketentuan</h2>
            <p>buyle.id berhak memperbarui atau memodifikasi Syarat dan Ketentuan ini sewaktu-waktu. Perubahan akan berlaku efektif setelah dipublikasikan pada halaman ini.</p>
        ';

        Faq::updateOrCreate(
            ['slug' => 'syarat-ketentuan'],
            [
                'title'        => 'Syarat & Ketentuan Layanan Buyle.id',
                'excerpt'      => 'Ketentuan umum, aturan transaksi digital, hak cipta, dan pedoman penggunaan platform buyle.id bagi pembeli maupun kreator.',
                'content'      => trim($syaratContent),
                'category'     => 'Syarat & Kebijakan',
                'tags'         => ['syarat', 'ketentuan', 'terms', 'legal', 'kebijakan'],
                'is_published' => true,
                'published_at' => now(),
                'meta_title'   => 'Syarat & Ketentuan Layanan | buyle.id',
                'meta_desc'    => 'Pelajari syarat dan ketentuan resmi penggunaan platform buyle.id untuk transaksi produk digital, tiket event, dan ekosistem creator.',
            ]
        );

        // ── 2. KEBIJAKAN PRIVASI ──
        $privasiContent = '
            <p>Di <strong>buyle.id</strong>, kami sangat menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi yang Anda percayakan kepada kami sesuai dengan Undang-Undang Perlindungan Data Pribadi (UU PDP) yang berlaku di Republik Indonesia.</p>

            <h2>1. Informasi yang Kami Kumpulkan</h2>
            <p>Kami mengumpulkan informasi yang Anda berikan secara langsung saat mendaftar atau bertransaksi, antara lain:</p>
            <ul>
                <li><strong>Informasi Identitas:</strong> Nama lengkap, alamat email, nomor telepon/WhatsApp, dan kata sandi yang telah dienkripsi secara aman.</li>
                <li><strong>Informasi Profil & Sosial:</strong> Apabila Anda mendaftar atau menghubungkan akun melalui Google atau media sosial lain, kami menerima nama, alamat email, dan foto profil publik Anda.</li>
                <li><strong>Data Transaksi:</strong> Riwayat pemesanan, produk yang dibeli, nomor resi/tiket, serta status pembayaran (kami <em>tidak pernah</em> menyimpan informasi kartu kredit Anda; semua diproses melalui payment gateway berlisensi BI).</li>
                <li><strong>Data Verifikasi Creator (e-KYC):</strong> Foto KTP/identitas dan rekening bank tujuan pencairan dana (hanya bagi pengguna yang mendaftar sebagai Seller/Creator).</li>
            </ul>

            <h2>2. Bagaimana Kami Menggunakan Informasi Anda</h2>
            <p>Informasi yang kami kumpulkan digunakan untuk:</p>
            <ul>
                <li>Memproses pendaftaran akun, autentikasi login, dan pengiriman kode verifikasi OTP.</li>
                <li>Memfasilitasi transaksi jual-beli dan pengiriman link akses produk digital atau e-ticket ke email Anda.</li>
                <li>Menghubungi Anda terkait status pesanan, notifikasi transaksi, dan bantuan pelanggan.</li>
                <li>Mencegah aktivitas penipuan, penyalahgunaan, dan menjamin keamanan platform buyle.id.</li>
            </ul>

            <h2>3. Keamanan Data & Enkripsi</h2>
            <p>Kami menerapkan standar keamanan industri modern, termasuk:</p>
            <ul>
                <li>Enkripsi HTTPS/TLS 256-bit di seluruh jalur komunikasi website.</li>
                <li>Penyimpanan kata sandi dengan algoritma <em>Bcrypt Hashing</em> bertingkat tinggi yang tidak dapat dibaca oleh staf kami sekalipun.</li>
                <li>Penyimpanan database di cloud server yang terlindungi firewall dan backup berkala.</li>
            </ul>

            <h2>4. Pembagian Data kepada Pihak Ketiga</h2>
            <p>Kami <strong>tidak pernah menjual atau menyewakan</strong> data pribadi Anda kepada pihak mana pun untuk keperluan periklanan tanpa persetujuan Anda. Data hanya dapat dibagikan kepada mitra resmi yang diperlukan untuk menjalankan layanan, seperti:</p>
            <ul>
                <li>Mitra Payment Gateway resmi (untuk verifikasi pembayaran).</li>
                <li>Kreator terkait (hanya berupa nama pembeli dan email untuk keperluan pemenuhan akses pesanan).</li>
                <li>Pihak penegak hukum apabila diwajibkan oleh penetapan pengadilan yang sah sesuai hukum Republik Indonesia.</li>
            </ul>

            <h2>5. Hak-Hak Pengguna atas Data Pribadi</h2>
            <p>Anda memiliki hak untuk:</p>
            <ul>
                <li>Mengakses, memeriksa, dan memperbarui informasi profil akun Anda kapan saja melalui menu Pengaturan Profil.</li>
                <li>Meminta penghapusan akun dan data pribadi Anda dengan menghubungi tim bantuan kami.</li>
            </ul>

            <h2>6. Hubungi Kami</h2>
            <p>Jika Anda memiliki pertanyaan mengenai Kebijakan Privasi ini atau pengelolaan data Anda, silakan hubungi tim kami melalui email resmi: <a href="mailto:support@buyle.id" style="color:#1eb349; font-weight:600;">support@buyle.id</a> atau WhatsApp Customer Support buyle.id.</p>
        ';

        Faq::updateOrCreate(
            ['slug' => 'kebijakan-privasi'],
            [
                'title'        => 'Kebijakan Privasi Buyle.id',
                'excerpt'      => 'Panduan transparansi pengumpulan, pemrosesan, perlindungan, dan pengelolaan data pribadi pengguna platform buyle.id.',
                'content'      => trim($privasiContent),
                'category'     => 'Syarat & Kebijakan',
                'tags'         => ['privasi', 'kebijakan', 'data', 'keamanan', 'legal'],
                'is_published' => true,
                'published_at' => now(),
                'meta_title'   => 'Kebijakan Privasi | buyle.id',
                'meta_desc'    => 'Kebijakan privasi resmi buyle.id. Ketahui bagaimana kami melindungi dan mengamankan data pribadi serta transaksi Anda.',
            ]
        );
    }
}
