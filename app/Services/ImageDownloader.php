<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageDownloader
{
    /**
     * Download an external image, compress it with GD to WebP/JPEG, and save to storage.
     * Returns relative path (e.g. 'products/abc123xyz.webp') or original URL if download fails.
     */
    public static function downloadAndCompress(string $url, string $subDir = 'products', int $maxWidth = 1200, int $quality = 80): string
    {
        if (empty($url) || (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://'))) {
            return $url;
        }

        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT        => 15,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            ]);
            $contents = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || empty($contents)) {
                Log::warning("ImageDownloader HTTP {$httpCode} for URL: {$url}");
                return $url;
            }

            // Load GD image from string binary
            $srcImg = @imagecreatefromstring($contents);
            if (!$srcImg) {
                Log::warning("ImageDownloader failed to create GD image from URL: {$url}");
                return $url;
            }

            $width  = imagesx($srcImg);
            $height = imagesy($srcImg);

            // Calculate new dimensions (max width 1200)
            if ($width > $maxWidth) {
                $newWidth  = $maxWidth;
                $newHeight = (int) round(($height / $width) * $maxWidth);
            } else {
                $newWidth  = $width;
                $newHeight = $height;
            }

            $dstImg = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparency for PNG / WEBP
            imagealphablending($dstImg, false);
            imagesavealpha($dstImg, true);
            $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
            imagefilledrectangle($dstImg, 0, 0, $newWidth, $newHeight, $transparent);

            imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            $useWebp = function_exists('imagewebp');
            $extension = $useWebp ? 'webp' : 'jpg';
            $filename = trim($subDir, '/') . '/' . Str::random(24) . '.' . $extension;
            $fullPath = storage_path('app/public/' . $filename);

            // Ensure target directory exists
            $dir = dirname($fullPath);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }

            // Save compressed WebP or JPG
            if ($useWebp) {
                imagewebp($dstImg, $fullPath, $quality);
            } else {
                imagejpeg($dstImg, $fullPath, $quality);
            }

            imagedestroy($srcImg);
            imagedestroy($dstImg);

            Log::info("ImageDownloader successfully compressed {$url} -> {$filename}");
            return $filename;
        } catch (\Throwable $e) {
            Log::warning("ImageDownloader exception for {$url}: " . $e->getMessage());
            return $url;
        }
    }

    /**
     * Download and compress an array of gallery image URLs.
     */
    public static function downloadAndCompressMany(array $urls, string $subDir = 'products/gallery', int $maxCount = 6): array
    {
        $result = [];
        $count = 0;
        foreach ($urls as $u) {
            if ($count >= $maxCount) break;
            if (is_string($u) && !empty($u)) {
                $result[] = static::downloadAndCompress($u, $subDir);
                $count++;
            }
        }
        return $result;
    }
}
