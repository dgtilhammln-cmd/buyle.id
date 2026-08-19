<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\WaSetting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $settings = Setting::getAllAsArray();
        $wa       = WaSetting::primary();

        $siteName = $settings['site_name'] ?? 'buyle.id';

        $seo = [
            'title'       => $settings['meta_title_contact'] ?? 'Hubungi Kami | ' . $siteName,
            'description' => $settings['meta_desc_contact'] ?? 'Hubungi ' . $siteName . ' untuk konsultasi produk, pemesanan, dan layanan jasa. Respon cepat via WhatsApp.',
            'keywords'    => $settings['meta_keywords_contact'] ?? 'kontak buyle.id, hubungi toko, konsultasi produk',
            'og_image'    => !empty($settings['og_image_default']) ? asset('storage/'.$settings['og_image_default']) : (!empty($settings['logo']) ? asset('storage/'.$settings['logo']) : asset('images/og-default.jpg')),
            'canonical'   => route('contact'),
        ];

        $faq = [
            ['q' => 'Bagaimana cara memesan produk?', 'a' => 'Anda dapat memesan langsung melalui website atau menghubungi tim kami via WhatsApp untuk bantuan pemesanan.'],
            ['q' => 'Apakah tersedia layanan pemasangan?', 'a' => 'Ya, kami menyediakan layanan pemasangan oleh teknisi berpengalaman. Hubungi kami untuk informasi lebih lanjut.'],
            ['q' => 'Berapa lama estimasi pengiriman?', 'a' => 'Estimasi pengiriman bergantung pada lokasi tujuan. Kami bekerja sama dengan berbagai ekspedisi terpercaya untuk memastikan produk tiba tepat waktu.'],
            ['q' => 'Apakah ada garansi produk?', 'a' => 'Ya, setiap produk dilengkapi garansi sesuai ketentuan produsen. Kami siap membantu proses klaim garansi jika diperlukan.'],
        ];

        $schema = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'FAQPage',
            'mainEntity' => collect($faq)->map(fn($f) => [
                '@type'          => 'Question',
                'name'           => $f['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
            ])->toArray(),
        ]);

        return view('contact.index', compact('settings', 'wa', 'seo', 'faq', 'schema'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|min:2|max:100',
            'company'   => 'nullable|max:100',
            'email'     => 'required|email|max:100',
            'phone'     => 'required|min:8|max:20',
            'product'   => 'nullable|max:100',
            'message'   => 'required|min:10|max:2000',
        ]);

        // Log to database (analytics)
        \App\Models\AnalyticsEvent::record('contact_form', route('contact'), [
            'page_title' => 'Contact Form - ' . $validated['name'],
        ]);

        return back()->with('success', 'Pesan Anda berhasil dikirim! Tim kami akan menghubungi Anda segera. Atau langsung chat via WhatsApp untuk respon lebih cepat.');
    }
}
