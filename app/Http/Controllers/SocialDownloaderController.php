<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SocialMediaDownloaderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocialDownloaderController extends Controller
{
    protected SocialMediaDownloaderService $downloaderService;

    public function __construct(SocialMediaDownloaderService $downloaderService)
    {
        $this->downloaderService = $downloaderService;
    }

    /**
     * Tampilan utama halaman Social Media Downloader.
     */
    public function index()
    {
        $settings = Setting::getAllAsArray();
        $siteName = $settings['site_name'] ?? 'buyle.id';

        $seo = [
            'title'       => 'Download Video TikTok Tanpa Watermark & Reels IG HD Gratis - ' . $siteName,
            'description' => 'Unduh video TikTok tanpa watermark, Instagram Reels, postingan foto carousel, dan foto profil (DP) Instagram kualitas HD online gratis, cepat, dan tanpa aplikasi.',
            'keywords'    => 'download video tiktok tanpa watermark, download reels ig hd, download foto profil ig, instagram reels downloader, tiktok downloader gratis, buyle tools',
            'og_image'    => !empty($settings['og_image_default']) ? asset('storage/' . $settings['og_image_default']) : (!empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('images/og-default.jpg')),
            'canonical'   => route('tools.social_downloader'),
        ];

        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'Tools', 'url' => 'javascript:void(0)'],
            ['name' => 'Social Downloader', 'url' => route('tools.social_downloader')],
        ];

        return view('tools.social_downloader', compact('settings', 'seo', 'breadcrumbs'));
    }

    /**
     * AJAX endpoint untuk mendeteksi dan mengambil media dari URL.
     */
    public function fetchMedia(Request $request)
    {
        $request->validate([
            'url' => 'required|string|max:1000',
        ], [
            'url.required' => 'Masukkan link Instagram atau TikTok terlebih dahulu.',
        ]);

        $inputUrl = trim($request->input('url'));
        $result = $this->downloaderService->resolve($inputUrl);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Gagal memproses media. Pastikan tautan bersifat publik.',
            ], 422);
        }

        // Bungkus URL download dengan download stream proxy agar langsung memicu download di browser
        if (!empty($result['downloads'])) {
            foreach ($result['downloads'] as &$dl) {
                $dl['direct_download_url'] = route('tools.social_downloader.stream', [
                    'url'      => base64_encode($dl['url']),
                    'filename' => ($result['platform'] ?? 'media') . '_' . time() . '.' . ($dl['ext'] ?? 'mp4'),
                ]);
            }
        }

        return response()->json($result);
    }

    /**
     * Proxy stream unduhan agar memicu pop-up download asli di browser pengguna.
     */
    public function downloadStream(Request $request)
    {
        $encodedUrl = $request->query('url');
        $filename   = $request->query('filename', 'media_' . time() . '.mp4');

        if (!$encodedUrl) {
            abort(400, 'Parameter unduhan tidak valid.');
        }

        $realUrl = base64_decode($encodedUrl);
        if (!filter_var($realUrl, FILTER_VALIDATE_URL)) {
            abort(400, 'Tautan media tidak valid.');
        }

        // Bersihkan nama file dari karakter berbahaya
        $safeFilename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);

        try {
            $stream = Http::timeout(60)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Referer'    => 'https://www.tiktok.com/',
                ])
                ->get($realUrl);

            if (!$stream->successful()) {
                // Jika stream gagal, arahkan langsung ke URL sumber
                return redirect()->away($realUrl);
            }

            $contentType = $stream->header('Content-Type') ?: 'application/octet-stream';

            return response($stream->body(), 200, [
                'Content-Type'        => $contentType,
                'Content-Disposition' => 'attachment; filename="' . $safeFilename . '"',
                'Cache-Control'       => 'no-cache, private',
            ]);
        } catch (\Throwable $e) {
            Log::warning('Download stream proxy error: ' . $e->getMessage());
            return redirect()->away($realUrl);
        }
    }
}

