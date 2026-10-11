<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * ImageOptimizer Service
 * 
 * Digunakan oleh semua controller saat upload gambar.
 * Otomatis: resize → WebP → compress → simpan
 * 
 * Target: gambar hero max 800x600px @80% = ~80-150KB
 *         gambar produk max 800x800px @82% = ~60-120KB
 *         gambar avatar max 400x400px @85% = ~30-60KB
 */
class ImageOptimizer
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Process & store an uploaded image.
     *
     * @param UploadedFile $file    The uploaded file from request
     * @param string       $path    Storage path relative to storage/app/public (e.g. 'hero_slides')
     * @param string       $preset  Preset: 'hero' | 'banner' | 'product' | 'avatar' | 'article' | 'thumbnail' | 'original'
     * @return string               The stored path relative to storage/app/public (for DB storing)
     */
    public function store(UploadedFile $file, string $path, string $preset = 'product'): string
    {
        [$maxW, $maxH, $quality] = $this->getPreset($preset);

        $filename  = Str::uuid() . '.webp';
        $storePath = rtrim($path, '/') . '/' . $filename;
        $fullPath  = storage_path('app/public/' . $storePath);

        // Ensure directory exists
        @mkdir(dirname($fullPath), 0755, true);

        $img = $this->manager->read($file->getRealPath());

        // Only scale DOWN, never scale up
        if ($img->width() > $maxW || $img->height() > $maxH) {
            $img->scaleDown($maxW, $maxH);
        }

        $img->toWebp($quality)->save($fullPath);

        return $storePath;
    }

    /**
     * Simpan gambar review dari UploadedFile atau binary/base64 data.
     * Otomatis resize max 600x600px, convert ke WebP quality 50 (sangat ringan ~15-25KB).
     */
    public function storeReviewImage(UploadedFile|string $input, string $productId): ?string
    {
        $filename  = 'reviews/' . $productId . '_' . time() . '_' . Str::random(6) . '.webp';
        $fullPath  = storage_path('app/public/' . $filename);

        @mkdir(dirname($fullPath), 0755, true);

        try {
            if ($input instanceof UploadedFile) {
                $img = $this->manager->read($input->getRealPath());
            } elseif (is_string($input)) {
                if (preg_match('/^data:image\/\w+;base64,/', $input)) {
                    $binary = base64_decode(substr($input, strpos($input, ',') + 1));
                } else {
                    $binary = $input;
                }
                $img = $this->manager->read($binary);
            } else {
                return null;
            }

            if ($img->width() > 600 || $img->height() > 600) {
                $img->scaleDown(600, 600);
            }

            $img->toWebp(50)->save($fullPath);

            return $filename;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('ImageOptimizer::storeReviewImage failed: ' . $e->getMessage());

            // GD fallback
            try {
                $raw = $input instanceof UploadedFile ? file_get_contents($input->getRealPath()) : (
                    preg_match('/^data:image\/\w+;base64,/', $input) 
                        ? base64_decode(substr($input, strpos($input, ',') + 1)) 
                        : $input
                );
                $gdImg = @imagecreatefromstring($raw);
                if ($gdImg) {
                    $origW = imagesx($gdImg);
                    $origH = imagesy($gdImg);
                    $maxDim = 600;
                    if ($origW > $maxDim || $origH > $maxDim) {
                        if ($origW > $origH) {
                            $newH = (int) round(($origH * $maxDim) / $origW);
                            $newW = $maxDim;
                        } else {
                            $newW = (int) round(($origW * $maxDim) / $origH);
                            $newH = $maxDim;
                        }
                        $resized = imagecreatetruecolor($newW, $newH);
                        imagecopyresampled($resized, $gdImg, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
                        imagedestroy($gdImg);
                        $gdImg = $resized;
                    }
                    imagewebp($gdImg, $fullPath, 50);
                    imagedestroy($gdImg);
                    return $filename;
                }
            } catch (\Throwable $ex) {}

            return null;
        }
    }

    /**
     * Compress & re-encode an existing file path (absolute).
     * Returns true on success.
     */
    public function compressExisting(string $absolutePath, string $preset = 'product'): bool
    {
        if (!file_exists($absolutePath)) return false;

        [$maxW, $maxH, $quality] = $this->getPreset($preset);

        try {
            $img = $this->manager->read($absolutePath);

            if ($img->width() > $maxW || $img->height() > $maxH) {
                $img->scaleDown($maxW, $maxH);
            }

            $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
            if ($ext === 'webp') {
                $img->toWebp($quality)->save($absolutePath);
            } elseif ($ext === 'png') {
                $img->toPng()->save($absolutePath);
            } else {
                $img->toWebp($quality)->save($absolutePath);
            }

            return true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('ImageOptimizer::compressExisting failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Presets: [maxWidth, maxHeight, quality]
     * 
     * Hero     : 1280x640  @78% — banner besar halaman depan
     * Banner   : 900x400   @80% — banner samping / promo
     * Product  : 800x800   @82% — thumbnail produk
     * Article  : 1000x600  @80% — gambar artikel/blog
     * Avatar   : 400x400   @85% — foto profil / avatar
     * Thumbnail: 400x400   @75% — gambar kecil / icon
     * Original : 1920x1920 @85% — preserve quality (untuk download)
     */
    protected function getPreset(string $preset): array
    {
        return match ($preset) {
            'hero'      => [1280, 640,  78],
            'banner'    => [900,  400,  80],
            'product'   => [800,  800,  82],
            'article'   => [1000, 600,  80],
            'avatar'    => [400,  400,  85],
            'thumbnail' => [400,  400,  75],
            'original'  => [1920, 1920, 85],
            default     => [800,  800,  80],
        };
    }
}
