<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function convertFile()
    {
        $settings = Setting::getAllAsArray();
        $siteName = $settings['site_name'] ?? 'buyle.id';

        $seo = [
            'title'       => 'Convert File & Foto Online Gratis - ' . $siteName,
            'description' => 'Ubah format gambar dan foto online gratis, cepat, dan aman. Dukungan format JPG, PNG, WEBP, AVIF, GIF, dan BMP dengan opsi pengaturan kualitas kompresi fleksibel.',
            'keywords'    => 'convert file, convert foto online, ubah format gambar, jpg ke png, png ke webp, kompres foto, resize foto, image converter gratis, buyle tools',
            'og_image'    => !empty($settings['og_image_default']) ? asset('storage/' . $settings['og_image_default']) : (!empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('images/og-default.jpg')),
            'canonical'   => route('tools.convert_file'),
        ];

        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'Tools', 'url' => 'javascript:void(0)'],
            ['name' => 'Convert File', 'url' => route('tools.convert_file')],
        ];

        return view('tools.convert_file', compact('settings', 'seo', 'breadcrumbs'));
    }

    /**
     * Endpoint Cron Web untuk trigger pembersihan file gambar sementara via URL (misal dari Hostinger / cPanel Cron Job).
     */
    public function cronCleanTempImages(Request $request)
    {
        // Validasi token keamanan opsional jika token disediakan di request
        $expectedKey = substr(md5(config('app.key') . '_buyle_clean'), 0, 16);
        $providedKey = $request->query('key');

        if ($providedKey && !hash_equals($expectedKey, (string) $providedKey)) {
            return response()->json(['success' => false, 'message' => 'Kunci akses cron tidak valid.'], 403);
        }

        $exitCode = \Illuminate\Support\Facades\Artisan::call('tools:clean-temp-images', ['--days' => 14]);
        $output = \Illuminate\Support\Facades\Artisan::output();

        return response()->json([
            'success' => $exitCode === 0,
            'message' => trim($output),
            'cron_key_hint' => $expectedKey,
        ]);
    }
}

