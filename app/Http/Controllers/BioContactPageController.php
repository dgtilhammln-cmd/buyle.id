<?php

namespace App\Http\Controllers;

use App\Models\CreatorProfile;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BioContactPageController extends Controller
{
    /**
     * Show dedicated Contact Page for Theme 5
     */
    public function show(Request $request, string $username)
    {
        $profile = CreatorProfile::with(['user'])->where('store_slug', $username)->firstOrFail();
        $theme   = $profile->bio_theme ?? 'theme5';

        // Contact page is strictly exclusive to Theme 5
        if ($theme !== 'theme5') {
            $homeUrl = !empty($profile->custom_domain)
                ? 'https://' . rtrim($profile->custom_domain, '/')
                : url('/' . $username);
            return redirect($homeUrl);
        }

        $config  = $profile->bio_config ?? [];
        $bioName = $config['name'] ?? $profile->store_name ?? $username;

        // Custom SEO Metas per page for Contact Page
        $seoTitle = !empty($config['theme5_seo_contact_title']) 
            ? $config['theme5_seo_contact_title'] 
            : 'Kontak Kami - ' . $bioName . (!empty($profile->custom_domain) ? '' : ' | buyle.id');

        $seoDesc = !empty($config['theme5_seo_contact_desc'])
            ? $config['theme5_seo_contact_desc']
            : 'Hubungi ' . $bioName . ' untuk konsultasi, informasi produk, alamat kantor, dan layanan pelanggan.';

        $seoKeywords = !empty($config['theme5_seo_contact_keywords'])
            ? $config['theme5_seo_contact_keywords']
            : 'kontak, hubungi kami, alamat kantor, customer service, buyle';

        $canonical = !empty($profile->custom_domain)
            ? 'https://' . rtrim($profile->custom_domain, '/') . '/kontak'
            : url('/' . $username . '/kontak');

        $ogImage = asset('images/buyle-og.png');

        // Address & Embed Maps
        $contactAddress = !empty($config['theme5_contact_address'])
            ? $config['theme5_contact_address']
            : ($config['location'] ?? '');

        $contactMapsEmbed = !empty($config['theme5_contact_maps_embed'])
            ? $config['theme5_contact_maps_embed']
            : ($config['embed_location'] ?? '');

        return view('bio.theme5.contact_page', compact(
            'profile', 'config', 'username', 'bioName',
            'seoTitle', 'seoDesc', 'seoKeywords', 'canonical', 'ogImage',
            'contactAddress', 'contactMapsEmbed'
        ));
    }

    /**
     * Store Lead submitted from Theme 5 Contact Page inline form
     */
    public function storeLead(Request $request, string $username)
    {
        $profile = CreatorProfile::where('store_slug', $username)->firstOrFail();

        $validated = $request->validate([
            'name'    => 'required|string|max:150',
            'phone'   => 'required|string|max:30',
            'email'   => 'nullable|email|max:150',
            'city'    => 'nullable|string|max:150',
            'message' => 'required|string|max:3000',
        ], [
            'name.required'    => 'Nama Lengkap wajib diisi.',
            'phone.required'   => 'Nomor WhatsApp / HP wajib diisi.',
            'message.required' => 'Pesan / Kebutuhan wajib diisi.',
        ]);

        Lead::create([
            'user_id'     => $profile->user_id,
            'seller_id'   => $profile->user_id,
            'name'        => $validated['name'],
            'phone'       => $validated['phone'],
            'email'       => $validated['email'] ?? null,
            'city'        => $validated['city'] ?? null,
            'company'     => $validated['city'] ?? null,
            'message'     => $validated['message'],
            'product'     => 'Form Kontak Page',
            'source'      => 'theme5_contact_page',
            'page_url'    => url()->current(),
            'ip_address'  => $request->ip(),
            'device_type' => $request->header('User-Agent'),
            'status'      => 'new',
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah kami terima. Tim kami akan segera menghubungi Anda.');
    }
}
