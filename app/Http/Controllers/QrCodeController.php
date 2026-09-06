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
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Generate QR Code as Base64 Data URI for PDF rendering without HTTP loopback issues.
     */
    public static function generateBase64($data = 'buyle-id-ticket')
    {
        if (empty($data)) {
            $data = 'buyle-id-ticket';
        }

        $cacheKey = 'qr_code_base64_v3_' . md5($data);

        return Cache::remember($cacheKey, 86400, function () use ($data) {
            $favPath = public_path('favicon.png');
            if (!file_exists($favPath)) {
                $favPath = storage_path('app/public/settings/favicon.png');
            }
            if (!file_exists($favPath)) {
                $favPath = public_path('favicon.ico');
            }

            return SimpleQrCode::base64($data, 300, $favPath);
        });
    }
}
