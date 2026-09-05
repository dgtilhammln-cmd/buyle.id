<?php

namespace App\Http\Controllers;

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

        $cacheKey = 'qr_code_with_buyle_logo_' . md5($data);

        $pngData = Cache::remember($cacheKey, 86400, function () use ($data) {
            // 1. Ambil QR Code PNG dari API QR Code (ecc=H untuk error correction tinggi)
            $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&ecc=H&data=' . urlencode($data);

            $context = stream_context_create([
                'http' => ['timeout' => 5],
                'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
            ]);

            $qrContent = @file_get_contents($qrApiUrl, false, $context);
            if (!$qrContent) {
                return @file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($data), false, $context);
            }

            if (!extension_loaded('gd')) {
                return $qrContent;
            }

            // 2. Muat gambar QR Code ke dalam GD
            $qrImg = @imagecreatefromstring($qrContent);
            if (!$qrImg) {
                return $qrContent;
            }

            $qrWidth  = imagesx($qrImg);
            $qrHeight = imagesy($qrImg);

            // 3. Cari file logo Favicon buyle.id
            $favPath = public_path('favicon.png');
            if (!file_exists($favPath)) {
                $favPath = storage_path('app/public/settings/favicon.png');
            }
            if (!file_exists($favPath)) {
                $favPath = public_path('favicon.ico');
            }

            if (file_exists($favPath) && is_readable($favPath)) {
                $logoContent = @file_get_contents($favPath);
                $logoImg     = $logoContent ? @imagecreatefromstring($logoContent) : null;

                if ($logoImg) {
                    $logoWidth  = imagesx($logoImg);
                    $logoHeight = imagesy($logoImg);

                    // Ukuran background badge & logo di tengah QR
                    $badgeSize      = (int) ($qrWidth * 0.22);
                    $logoTargetSize = (int) ($badgeSize * 0.76);

                    // Posisi tengah (center)
                    $centerX = (int) (($qrWidth - $badgeSize) / 2);
                    $centerY = (int) (($qrHeight - $badgeSize) / 2);

                    // Buat kotak putih dengan border halus di tengah QR
                    $white       = imagecolorallocate($qrImg, 255, 255, 255);
                    $borderColor = imagecolorallocate($qrImg, 203, 213, 225); // #CBD5E1

                    imagefilledrectangle($qrImg, $centerX, $centerY, $centerX + $badgeSize, $centerY + $badgeSize, $white);
                    imagerectangle($qrImg, $centerX, $centerY, $centerX + $badgeSize, $centerY + $badgeSize, $borderColor);

                    // Overlay logo di tengah badge
                    $logoX = $centerX + (int) (($badgeSize - $logoTargetSize) / 2);
                    $logoY = $centerY + (int) (($badgeSize - $logoTargetSize) / 2);

                    imagecopyresampled(
                        $qrImg, $logoImg,
                        $logoX, $logoY, 0, 0,
                        $logoTargetSize, $logoTargetSize,
                        $logoWidth, $logoHeight
                    );

                    imagedestroy($logoImg);
                }
            }

            // Output GD image ke PNG
            ob_start();
            imagepng($qrImg, null, 9);
            $output = ob_get_clean();
            imagedestroy($qrImg);

            return $output;
        });

        return response($pngData, 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
