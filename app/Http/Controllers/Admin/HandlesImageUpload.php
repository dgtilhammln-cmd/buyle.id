<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

/**
 * Native GD-based image processing trait.
 * No Intervention Image dependency — works reliably on all environments.
 */
trait HandlesImageUpload
{
    /**
     * Load GD image resource from UploadedFile regardless of mime type
     */
    private function gdLoad(UploadedFile $file)
    {
        $path = $file->getRealPath();
        $mime = $file->getMimeType() ?: mime_content_type($path);

        return match (true) {
            str_contains($mime, 'jpeg'), str_contains($mime, 'jpg') => imagecreatefromjpeg($path),
            str_contains($mime, 'png')  => imagecreatefrompng($path),
            str_contains($mime, 'webp') => imagecreatefromwebp($path),
            str_contains($mime, 'gif')  => imagecreatefromgif($path),
            str_contains($mime, 'bmp')  => imagecreatefrombmp($path),
            default                     => @imagecreatefromjpeg($path) ?: @imagecreatefrompng($path),
        };
    }

    /**
     * Scale image down to max dimensions (keep aspect ratio)
     */
    private function gdScaleDown($src, int $maxW, int $maxH)
    {
        $origW = imagesx($src);
        $origH = imagesy($src);

        if ($origW <= $maxW && $origH <= $maxH) {
            return $src;
        }

        $ratio = min($maxW / $origW, $maxH / $origH);
        $newW  = (int) round($origW * $ratio);
        $newH  = (int) round($origH * $ratio);

        $dst = imagecreatetruecolor($newW, $newH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);
        return $dst;
    }

    /**
     * Crop to square from center, then resize
     */
    private function gdSquareCrop($src, int $size)
    {
        $w = imagesx($src);
        $h = imagesy($src);
        $side = min($w, $h);
        $x = (int)(($w - $side) / 2);
        $y = (int)(($h - $side) / 2);

        $sq = imagecreatetruecolor($size, $size);
        imagealphablending($sq, false);
        imagesavealpha($sq, true);
        imagecopyresampled($sq, $src, 0, 0, $x, $y, $size, $size, $side, $side);
        imagedestroy($src);
        return $sq;
    }

    /**
     * Crop image to exact target dimensions from center (cover mode)
     */
    private function gdCenterCrop($src, int $targetW, int $targetH)
    {
        $origW = imagesx($src);
        $origH = imagesy($src);

        // Scale so the image fills the target (cover mode)
        $ratio = max($targetW / $origW, $targetH / $origH);
        $scaledW = (int) round($origW * $ratio);
        $scaledH = (int) round($origH * $ratio);

        // Crop from center
        $cropX = (int) round(($scaledW - $targetW) / 2);
        $cropY = (int) round(($scaledH - $targetH) / 2);

        $dst = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0,
            (int)round($cropX / $ratio), (int)round($cropY / $ratio),
            $targetW, $targetH,
            (int)round($targetW / $ratio), (int)round($targetH / $ratio));
        imagedestroy($src);
        return $dst;
    }

    private function gdEncodeWebP($img, int $quality): string
    {
        ob_start();
        imagewebp($img, null, $quality);
        $data = ob_get_clean();
        imagedestroy($img);
        return $data;
    }

    /**
     * Store uploaded image as WebP (scaled down, or exact crop if both maxW & maxH given strictly)
     */
    protected function storeWebP(UploadedFile $file, string $folder, int $maxW = 1200, int $maxH = 800, int $quality = 88): string
    {
        $img      = $this->gdLoad($file);
        $img      = $this->gdCenterCrop($img, $maxW, $maxH);
        $webp     = $this->gdEncodeWebP($img, $quality);
        $filename = $folder . '/' . Str::random(16) . '.webp';
        Storage::disk('public')->put($filename, $webp);
        return $filename;
    }

    /**
     * Store uploaded image as WebP preserving original aspect ratio (NO cropping - for logos, icons, banners)
     */
    protected function storeWebPNoCrop(UploadedFile $file, string $folder, int $maxW = 1600, int $maxH = 800, int $quality = 95): string
    {
        $img      = $this->gdLoad($file);
        $img      = $this->gdScaleDown($img, $maxW, $maxH);
        $webp     = $this->gdEncodeWebP($img, $quality);
        $filename = $folder . '/' . Str::random(16) . '.webp';
        Storage::disk('public')->put($filename, $webp);
        return $filename;
    }

    /**
     * Store uploaded image as square WebP (for avatars)
     */
    protected function storeWebPSquare(UploadedFile $file, string $folder, int $size = 200, int $quality = 88): string
    {
        $img      = $this->gdLoad($file);
        $img      = $this->gdSquareCrop($img, $size);
        $webp     = $this->gdEncodeWebP($img, $quality);
        $filename = $folder . '/' . Str::random(12) . '.webp';
        Storage::disk('public')->put($filename, $webp);
        return $filename;
    }

    /**
     * Store uploaded image as WebP sized for OG (1200x630)
     */
    protected function storeOgWebP(UploadedFile $file, string $folder, int $quality = 85): string
    {
        $img      = $this->gdLoad($file);
        $img      = $this->gdScaleDown($img, 1200, 630);
        $webp     = $this->gdEncodeWebP($img, $quality);
        $filename = $folder . '/og_' . Str::random(12) . '.webp';
        Storage::disk('public')->put($filename, $webp);
        return $filename;
    }

    /**
     * Delete file from public storage safely
     */
    protected function deleteStorageFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
