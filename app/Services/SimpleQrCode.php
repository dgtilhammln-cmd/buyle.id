<?php

namespace App\Services;

/**
 * Standard ISO/IEC 18004 QR Code Generator.
 * Generates pure, ultra-high-contrast, un-obscured QR codes for 100% instant scanning
 * across all webcams, phone cameras, and low-light environments.
 */
class SimpleQrCode
{
    /**
     * Generate Base64 PNG Data URI for given string data.
     */
    public static function base64($data, $size = 300, $logoPath = null)
    {
        if (empty($data)) {
            $data = 'buyle-ticket';
        }

        // Include pure PHP QR Code engine
        require_once app_path('Services/QrCode/qrlib.php');

        // Render clean, standard QR Code with Level M error correction (optimal balance of density and contrast)
        // Without center obstructions, 100% of the pattern is crystal clear to scanner decoders.
        ob_start();
        \QRcode::png($data, null, QR_ECLEVEL_M, 8, 2);
        $rawPng = ob_get_clean();

        if (empty($rawPng)) {
            return '';
        }

        $qrImg = @imagecreatefromstring($rawPng);
        if (!$qrImg) {
            return 'data:image/png;base64,' . base64_encode($rawPng);
        }

        $qrW = imagesx($qrImg);
        $qrH = imagesy($qrImg);

        // Resize cleanly if target size differs
        if ($size && $size > 50 && $size !== $qrW) {
            $finalImg = imagecreatetruecolor($size, $size);
            imagealphablending($finalImg, false);
            imagesavealpha($finalImg, true);
            $bg = imagecolorallocate($finalImg, 255, 255, 255);
            imagefill($finalImg, 0, 0, $bg);
            imagecopyresampled($finalImg, $qrImg, 0, 0, 0, 0, $size, $size, $qrW, $qrH);
            imagedestroy($qrImg);
            $qrImg = $finalImg;
        }

        ob_start();
        imagepng($qrImg, null, 9);
        $finalPng = ob_get_clean();
        imagedestroy($qrImg);

        return 'data:image/png;base64,' . base64_encode($finalPng);
    }
}
