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
}

