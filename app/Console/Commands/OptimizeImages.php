<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--dry-run : Show what would be optimized without doing it} {--quality=80 : WebP quality (1-100)}';
    protected $description = 'Compress and resize all images in storage to improve Lighthouse performance score';

    // Max dimensions per folder type
    private array $rules = [
        'hero_slides'    => ['w' => 800,  'h' => 450,  'q' => 82],
        'hero_banners'   => ['w' => 800,  'h' => 450,  'q' => 82],
        'banners'        => ['w' => 800,  'h' => 450,  'q' => 82],
        'promo_banners'  => ['w' => 600,  'h' => 400,  'q' => 80],
        'services'       => ['w' => 600,  'h' => 600,  'q' => 80],
        'products'       => ['w' => 600,  'h' => 600,  'q' => 80],
        'categories'     => ['w' => 400,  'h' => 400,  'q' => 78],
        'usp-icons'      => ['w' => 96,   'h' => 96,   'q' => 80],
        'usp_icons'      => ['w' => 96,   'h' => 96,   'q' => 80],
        'logos'          => ['w' => 300,  'h' => 300,  'q' => 85],
        'articles'       => ['w' => 800,  'h' => 500,  'q' => 78],
        'gallery'        => ['w' => 800,  'h' => 600,  'q' => 78],
        'avatars'        => ['w' => 200,  'h' => 200,  'q' => 82],
        'settings'       => ['w' => 400,  'h' => 400,  'q' => 82],
        '_default'       => ['w' => 900,  'h' => 900,  'q' => 80],
    ];

    private int $totalSaved = 0;
    private int $totalProcessed = 0;
    private int $totalSkipped = 0;

    public function handle(): int
    {
        $dryRun  = $this->option('dry-run');
        $quality = (int) $this->option('quality');

        $this->info('🔍 Scanning storage/app/public for images...');
        $this->newLine();

        $storagePath = storage_path('app/public');
        $files = $this->getImageFiles($storagePath);

        $this->info("Found " . count($files) . " image files.");
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $filePath) {
            $this->processImage($filePath, $storagePath, $dryRun, $quality);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $savedKB  = round($this->totalSaved / 1024, 1);
        $savedMB  = round($this->totalSaved / (1024 * 1024), 2);
        $this->info("✅ Processed : {$this->totalProcessed}");
        $this->info("⏭  Skipped   : {$this->totalSkipped}");
        $this->info("💾 Saved     : {$savedKB} KB ({$savedMB} MB)");
        if ($dryRun) {
            $this->warn("Dry-run mode – no files were modified.");
        }

        return 0;
    }

    private function getImageFiles(string $base): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base));
        foreach ($iterator as $file) {
            if (!$file->isFile()) continue;
            $ext = strtolower($file->getExtension());
            if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }

    private function processImage(string $filePath, string $base, bool $dryRun, int $defaultQuality): void
    {
        // Determine rule from folder name
        $rel = str_replace('\\', '/', substr($filePath, strlen($base) + 1));
        $folder = explode('/', $rel)[0] ?? '_default';
        $rule = $this->rules[$folder] ?? $this->rules['_default'];
        $maxW = $rule['w'];
        $maxH = $rule['h'];
        $quality = $rule['q'];

        $originalSize = filesize($filePath);
        if ($originalSize === false || $originalSize === 0) { $this->totalSkipped++; return; }

        // Load image
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $img = match($ext) {
            'jpg','jpeg' => @imagecreatefromjpeg($filePath),
            'png'        => @imagecreatefrompng($filePath),
            'webp'       => @imagecreatefromwebp($filePath),
            'gif'        => @imagecreatefromgif($filePath),
            default      => false,
        };

        if (!$img) { $this->totalSkipped++; return; }

        $origW = imagesx($img);
        $origH = imagesy($img);

        // Calculate new dimensions keeping aspect ratio
        [$newW, $newH] = $this->calcDimensions($origW, $origH, $maxW, $maxH);

        // If image is already small enough AND already WebP, skip
        if ($origW <= $maxW && $origH <= $maxH && $ext === 'webp' && $originalSize < 100 * 1024) {
            imagedestroy($img);
            $this->totalSkipped++;
            return;
        }

        if ($dryRun) {
            $this->line(" [DRY] {$rel}: {$origW}x{$origH} → {$newW}x{$newH} @ q{$quality}");
            imagedestroy($img);
            $this->totalProcessed++;
            return;
        }

        // Resize
        if ($newW !== $origW || $newH !== $origH) {
            $resized = imagecreatetruecolor($newW, $newH);
            // Preserve transparency for PNG/WebP
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefilledrectangle($resized, 0, 0, $newW, $newH, $transparent);
            imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
            imagedestroy($img);
            $img = $resized;
        }

        // Ensure true color for WebP
        if (!imageistruecolor($img)) {
            imagepalettetotruecolor($img);
        }

        // Always save as WebP for best compression
        $outPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $filePath);
        $success = imagewebp($img, $outPath, $quality);
        imagedestroy($img);

        if (!$success) { $this->totalSkipped++; return; }

        $newSize = filesize($outPath);
        $saved = $originalSize - $newSize;

        // Remove original non-webp file if converted
        if (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) !== 'webp' && $outPath !== $filePath) {
            if ($saved > -50000) { // Only remove if not massively larger
                @unlink($filePath);
            }
        }

        $this->totalSaved += max(0, $saved);
        $this->totalProcessed++;
    }

    private function calcDimensions(int $origW, int $origH, int $maxW, int $maxH): array
    {
        if ($origW <= $maxW && $origH <= $maxH) return [$origW, $origH];
        $ratioW = $origW > $maxW ? $maxW / $origW : 1.0;
        $ratioH = $origH > $maxH ? $maxH / $origH : 1.0;
        $ratio  = min($ratioW, $ratioH);
        return [(int) round($origW * $ratio), (int) round($origH * $ratio)];
    }
}
