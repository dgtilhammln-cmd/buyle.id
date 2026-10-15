<?php

namespace App\Http\Controllers;

use App\Services\SimpleQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class QrCodeController extends Controller
{
    /**
     * Generate QR Code PNG dengan logo Favicon buyle.id di tengahnya.
     */
    public function generate(Request $request)
    {
        $data = $request->query('data', 'buyle-id-ticket');
        if (empty($data)) {
            $data = 'buyle-id-ticket';
        }

        $base64 = self::generateBase64($data);
        $rawPng = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));

        return response($rawPng, 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, private')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Generate QR Code as Base64 Data URI for PDF rendering without HTTP loopback issues.
     */
    public static function generateBase64($data = 'buyle-id-ticket')
    {
        if (empty($data)) {
            $data = 'buyle-id-ticket';
        }

        // Generate crisp pixel-perfect QR with Level H (30% recovery) without any center logo
        $cacheKey = 'qr_crisp_v4_high_' . md5($data);

        return Cache::remember($cacheKey, 86400, function () use ($data) {
            return SimpleQrCode::base64($data, 320);
        });
    }
}

