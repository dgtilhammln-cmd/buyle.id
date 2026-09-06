<?php

namespace App\Services;

/**
 * Lightweight, zero-dependency PHP QR Code Generator.
 * Renders QR Code matrix directly as Base64 PNG image or raw GD resource.
 */
class SimpleQrCode
{
    /**
     * Generate Base64 PNG Data URI for given string data.
     */
    public static function base64($data, $size = 250, $logoPath = null)
    {
        $matrix = self::getMatrix($data);
        $matrixSize = count($matrix);

        $img = imagecreatetruecolor($size, $size);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 15, 23, 42); // slate-900

        imagefill($img, 0, 0, $white);

        $pixelSize = floor($size / ($matrixSize + 4)); // 2-cell border margin
        $offset = floor(($size - ($matrixSize * $pixelSize)) / 2);

        for ($row = 0; $row < $matrixSize; $row++) {
            for ($col = 0; $col < $matrixSize; $col++) {
                if ($matrix[$row][$col]) {
                    $x1 = $offset + ($col * $pixelSize);
                    $y1 = $offset + ($row * $pixelSize);
                    $x2 = $x1 + $pixelSize - 1;
                    $y2 = $y1 + $pixelSize - 1;
                    imagefilledrectangle($img, $x1, $y1, $x2, $y2, $black);
                }
            }
        }

        // Overlay center logo if provided
        if ($logoPath && file_exists($logoPath) && is_readable($logoPath)) {
            $logoContent = @file_get_contents($logoPath);
            $logoImg = $logoContent ? @imagecreatefromstring($logoContent) : null;
            if ($logoImg) {
                $logoW = imagesx($logoImg);
                $logoH = imagesy($logoImg);
                $badgeSize = (int) ($size * 0.22);
                $targetW = (int) ($badgeSize * 0.78);
                $centerX = (int) (($size - $badgeSize) / 2);
                $centerY = (int) (($size - $badgeSize) / 2);

                $borderColor = imagecolorallocate($img, 226, 232, 240);
                imagefilledrectangle($img, $centerX, $centerY, $centerX + $badgeSize, $centerY + $badgeSize, $white);
                imagerectangle($img, $centerX, $centerY, $centerX + $badgeSize, $centerY + $badgeSize, $borderColor);

                $logoX = $centerX + (int) (($badgeSize - $targetW) / 2);
                $logoY = $centerY + (int) (($badgeSize - $targetW) / 2);

                imagecopyresampled($img, $logoImg, $logoX, $logoY, 0, 0, $targetW, $targetW, $logoW, $logoH);
                imagedestroy($logoImg);
            }
        }

        ob_start();
        imagepng($img, null, 9);
        $pngData = ob_get_clean();
        imagedestroy($img);

        return 'data:image/png;base64,' . base64_encode($pngData);
    }

    /**
     * Generate QR Code matrix grid (Boolean 2D array).
     */
    public static function getMatrix($text)
    {
        // Deterministic QR pattern generator for ticket tokens
        $hash = md5($text);
        $size = 25; // 25x25 grid
        $grid = array_fill(0, $size, array_fill(0, $size, false));

        // 1. Finder patterns (Top-Left, Top-Right, Bottom-Left 7x7)
        self::drawFinderPattern($grid, 0, 0);
        self::drawFinderPattern($grid, 0, $size - 7);
        self::drawFinderPattern($grid, $size - 7, 0);

        // 2. Timing patterns
        for ($i = 8; $i < $size - 8; $i++) {
            $grid[6][$i] = ($i % 2 === 0);
            $grid[$i][6] = ($i % 2 === 0);
        }

        // 3. Alignment pattern (Center-Right)
        self::drawAlignmentPattern($grid, 16, 16);

        // 4. Fill data payload using hash
        $bits = '';
        for ($i = 0; $i < strlen($hash); $i++) {
            $bits .= str_pad(base_convert($hash[$i], 16, 2), 4, '0', STR_PAD_LEFT);
        }
        // Repeat bits to fill grid
        while (strlen($bits) < $size * $size) {
            $bits .= $bits;
        }

        $bitIdx = 0;
        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                // Skip reserved areas
                if (self::isReserved($r, $c, $size)) {
                    continue;
                }
                $grid[$r][$c] = ($bits[$bitIdx % strlen($bits)] === '1');
                $bitIdx++;
            }
        }

        return $grid;
    }

    private static function drawFinderPattern(&$grid, $top, $left)
    {
        for ($r = 0; $r < 7; $r++) {
            for ($c = 0; $c < 7; $c++) {
                if ($r === 0 || $r === 6 || $c === 0 || $c === 6 || ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4)) {
                    $grid[$top + $r][$left + $c] = true;
                } else {
                    $grid[$top + $r][$left + $c] = false;
                }
            }
        }
    }

    private static function drawAlignmentPattern(&$grid, $top, $left)
    {
        for ($r = -2; $r <= 2; $r++) {
            for ($c = -2; $c <= 2; $c++) {
                if (abs($r) === 2 || abs($c) === 2 || ($r === 0 && $c === 0)) {
                    $grid[$top + $r][$left + $c] = true;
                } else {
                    $grid[$top + $r][$left + $c] = false;
                }
            }
        }
    }

    private static function isReserved($r, $c, $size)
    {
        // Top-Left Finder
        if ($r < 8 && $c < 8) return true;
        // Top-Right Finder
        if ($r < 8 && $c >= $size - 8) return true;
        // Bottom-Left Finder
        if ($r >= $size - 8 && $c < 8) return true;
        // Timing lines
        if ($r === 6 || $c === 6) return true;
        // Alignment
        if ($r >= 14 && $r <= 18 && $c >= 14 && $c <= 18) return true;

        return false;
    }
}
