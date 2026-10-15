<?php

namespace App\Services;

/**
 * Standard ISO/IEC 18004 QR Code Generator.
 * Uses high-res Reed-Solomon Error Correction (Level H - 30% recovery)
 * so that center logos / watermarks do not break scanning reliability.
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

        // Render QR with High Error Correction (QR_ECLEVEL_H = 30% damage/overlay recovery)
        ob_start();
        \QRcode::png($data, null, QR_ECLEVEL_H, 8, 2);
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

        // Overlay center logo if provided
        if ($logoPath && file_exists($logoPath) && is_readable($logoPath)) {
            $logoContent = @file_get_contents($logoPath);
            $logoImg = $logoContent ? @imagecreatefromstring($logoContent) : null;
            if ($logoImg) {
                $origLogoW = imagesx($logoImg);
                $origLogoH = imagesy($logoImg);

                // Badge size max 20% of QR size to stay safely within 30% Level H capacity
                $badgeSize = (int) ($qrW * 0.20);
                $centerX = (int) (($qrW - $badgeSize) / 2);
                $centerY = (int) (($qrH - $badgeSize) / 2);

                $white = imagecolorallocate($qrImg, 255, 255, 255);
                $borderColor = imagecolorallocate($qrImg, 226, 232, 240);

                // Draw clean white container badge
                imagefilledrectangle($qrImg, $centerX, $centerY, $centerX + $badgeSize, $centerY + $badgeSize, $white);
                imagerectangle($qrImg, $centerX, $centerY, $centerX + $badgeSize, $centerY + $badgeSize, $borderColor);

                // Draw logo with subtle padding
                $innerPadding = (int) ($badgeSize * 0.15);
                $targetW = $badgeSize - (2 * $innerPadding);
                $targetH = $targetW;

                $logoX = $centerX + $innerPadding;
                $logoY = $centerY + $innerPadding;

                imagecopyresampled($qrImg, $logoImg, $logoX, $logoY, 0, 0, $targetW, $targetH, $origLogoW, $origLogoH);
                imagedestroy($logoImg);
            }
        }

        // Resize if requested
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
        imagepng($qrImg, null, 8);
        $finalPng = ob_get_clean();
        imagedestroy($qrImg);

        return 'data:image/png;base64,' . base64_encode($finalPng);
    }
}
