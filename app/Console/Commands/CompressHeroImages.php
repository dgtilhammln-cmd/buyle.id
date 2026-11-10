<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HeroSlide;
use Imagick;

class CompressHeroImages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'hero:compress {--quality=76 : Compression quality (0-100)} {--max-width=1280 : Max width}';

    /**
     * The console command description.
     */
    protected $description = 'Compress hero slider images to WebP format for 90+ PageSpeed performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(180);

        $quality = (int) ($this->option('quality') ?: 76);
        $maxW    = (int) ($this->option('max-width') ?: 1280);

        $this->info("Starting Hero Slide Image Compression (Quality: {$quality}%, Max Width: {$maxW}px)...");

        // 1. Collect all files in storage/app/public/hero_slides
        $dir = storage_path('app/public/hero_slides');
        $files = [];
        if (is_dir($dir)) {
            foreach (glob($dir . '/*.*') as $f) {
                if (is_file($f)) $files[] = $f;
            }
        }

        // Also collect from HeroSlide model paths
        $slides = HeroSlide::all();
        foreach ($slides as $slide) {
            if ($slide->image) {
                $p = storage_path('app/public/' . $slide->image);
                if (is_file($p) && !in_array($p, $files)) {
                    $files[] = $p;
                }
            }
        }

        $processed = 0;
        $totalSavedBytes = 0;

        foreach ($files as $originalPath) {
            $origSize = filesize($originalPath);
            // Skip already small images (< 85 KB)
            if ($origSize < 85 * 1024) {
                continue;
            }

            try {
                $raw = @file_get_contents($originalPath);
                if (!$raw) continue;

                $src = @imagecreatefromstring($raw);
                if (!$src) {
                    $this->warn("Cannot parse image: " . basename($originalPath));
                    continue;
                }

                $origW = imagesx($src);
                $origH = imagesy($src);

                if ($origW > $maxW) {
                    $ratio = $maxW / $origW;
                    $newW  = $maxW;
                    $newH  = (int) round($origH * $ratio);
                } else {
                    $newW = $origW;
                    $newH = $origH;
                }

                $dst = imagecreatetruecolor($newW, $newH);
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

                // Write to temp file first
                $tempPath = $originalPath . '.tmp.webp';
                imagewebp($dst, $tempPath, $quality);

                imagedestroy($src);
                imagedestroy($dst);

                if (file_exists($tempPath)) {
                    $newSize = filesize($tempPath);
                    if ($newSize < $origSize) {
                        rename($tempPath, $originalPath);
                        $saved = $origSize - $newSize;
                        $totalSavedBytes += $saved;
                        $this->info("✓ Compressed " . basename($originalPath) . ": " . round($origSize / 1024) . "KB → " . round($newSize / 1024) . "KB (saved " . round($saved / 1024) . "KB)");
                        $processed++;
                    } else {
                        @unlink($tempPath);
                    }
                }
            } catch (\Throwable $e) {
                $this->error("Error compressing " . basename($originalPath) . ": " . $e->getMessage());
            }
        }

        $savedMb = round($totalSavedBytes / 1024 / 1024, 2);
        $this->info("Hero image compression finished: {$processed} files compressed, {$savedMb} MB saved.");
        return 0;
    }
}
?>
