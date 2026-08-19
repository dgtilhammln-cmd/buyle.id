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
    protected $signature = 'hero:compress {--quality=80 : Compression quality (0-100)}';

    /**
     * The console command description.
     */
    protected $description = 'Compress hero slider images to WebP format for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $quality = (int) $this->option('quality');
        if ($quality < 0 || $quality > 100) {
            $this->error('Quality must be between 0 and 100');
            return 1;
        }

        $slides = HeroSlide::all();
        if ($slides->isEmpty()) {
            $this->info('No hero slides found.');
            return 0;
        }

        foreach ($slides as $slide) {
            $originalPath = storage_path('app/public/' . $slide->image);
            if (!file_exists($originalPath)) {
                $this->warn("File not found: {$slide->image}");
                continue;
            }

            $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $originalPath);

            try {
                $origSize = filesize($originalPath);
                
                $mime = mime_content_type($originalPath);
                
                $src = match(true) {
                    str_contains($mime, 'jpeg') || str_contains($mime, 'jpg') => imagecreatefromjpeg($originalPath),
                    str_contains($mime, 'png')  => imagecreatefrompng($originalPath),
                    str_contains($mime, 'webp') => imagecreatefromwebp($originalPath),
                    str_contains($mime, 'gif')  => imagecreatefromgif($originalPath),
                    default => imagecreatefromjpeg($originalPath),
                };
                
                if (!$src) {
                    $this->error("Failed to load image: {$slide->image}");
                    continue;
                }

                $origW = imagesx($src);
                $origH = imagesy($src);
                $maxW  = 1920; // Max width for hero images

                if ($origW > $maxW) {
                    $ratio = $maxW / $origW;
                    $newW  = $maxW;
                    $newH  = (int)($origH * $ratio);
                } else {
                    $newW = $origW;
                    $newH = $origH;
                }

                $dst = imagecreatetruecolor($newW, $newH);
                // Preserve transparency for PNG
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
                
                imagewebp($dst, $webpPath, $quality);
                
                imagedestroy($src);
                imagedestroy($dst);
                
                $newSize = filesize($webpPath);

                // Optional: store webp path in model for later use
                $relativeWebp = str_replace(storage_path('app/public/'), '', $webpPath);
                
                // Update the image path in the database to point to the new webp file
                $slide->image = $relativeWebp;
                $slide->save();
                
                // If it was not webp originally, we can delete the original to save space
                if ($originalPath !== $webpPath && file_exists($originalPath)) {
                    unlink($originalPath);
                }
                
                $origSizeMb = round($origSize / 1024 / 1024, 2);
                $newSizeMb = round($newSize / 1024 / 1024, 2);

                $this->info("Compressed {$slide->image} ({$origSizeMb}MB) → {$relativeWebp} ({$newSizeMb}MB)");
            } catch (\Exception $e) {
                $this->error("Failed to compress {$slide->image}: " . $e->getMessage());
            }
        }

        $this->info('Hero image compression completed.');
        return 0;
    }
}
?>
