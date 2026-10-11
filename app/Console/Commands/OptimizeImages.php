<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OptimizeImages extends Command
{
    protected $signature   = 'images:optimize 
                                {--folder= : Sub-folder in storage/app/public (e.g. hero_slides). Default: all}
                                {--max-width=1280 : Maximum width in pixels}
                                {--max-height=1280 : Maximum height in pixels}
                                {--quality=80 : WebP/JPEG quality (1-100)}
                                {--dry-run : Show what would be done without actually doing it}';

    protected $description = 'Compress & resize oversized images in storage to boost Lighthouse score';

    // Only process these extensions
    private array $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    // Skip files already smaller than this (bytes)
    private int $skipBelowBytes = 80_000; // 80KB — already good

    public function handle(): int
    {
        $manager  = new ImageManager(new Driver());
        $folder   = $this->option('folder');
        $maxW     = (int) $this->option('max-width');
        $maxH     = (int) $this->option('max-height');
        $quality  = (int) $this->option('quality');
        $dryRun   = $this->option('dry-run');

        $basePath = storage_path('app/public');
        $scanPath = $folder ? $basePath . DIRECTORY_SEPARATOR . $folder : $basePath;

        if (!is_dir($scanPath)) {
            $this->error("Directory not found: {$scanPath}");
            return self::FAILURE;
        }

        $this->info("🔍 Scanning: {$scanPath}");
        $this->info("📐 Max: {$maxW}x{$maxH}px | Quality: {$quality}% | Dry-run: " . ($dryRun ? 'YES' : 'NO'));
        $this->newLine();

        $files = $this->collectImages($scanPath);
        $this->info("📁 Found " . count($files) . " image files");
        $this->newLine();

        $totalSaved   = 0;
        $processCount = 0;
        $skipCount    = 0;

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $filePath) {
            $bar->advance();

            $originalSize = filesize($filePath);
            $ext          = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            // Skip small files — they're already good
            if ($originalSize < $this->skipBelowBytes) {
                $skipCount++;
                continue;
            }

            try {
                $img = $manager->read($filePath);

                $w = $img->width();
                $h = $img->height();

                // Only resize if bigger than max
                $needsResize = ($w > $maxW || $h > $maxH);
                $needsCompress = $originalSize > $this->skipBelowBytes;

                if (!$needsResize && !$needsCompress) {
                    $skipCount++;
                    continue;
                }

                if ($needsResize) {
                    $img->scaleDown($maxW, $maxH);
                }

                if ($dryRun) {
                    $relPath = Str::after($filePath, $basePath . DIRECTORY_SEPARATOR);
                    $savingEstimate = round($originalSize * 0.5 / 1024);
                    $this->line("\n  [DRY] {$relPath} ({$w}x{$h}, " . round($originalSize/1024) . "KB → ~{$savingEstimate}KB)");
                    $processCount++;
                    continue;
                }

                // Save as WebP for maximum compression (except GIFs)
                $outputPath = $filePath;
                if (in_array($ext, ['jpg', 'jpeg', 'png']) && function_exists('imagewebp')) {
                    // Convert JPG/PNG to WebP
                    $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $filePath);
                    $img->toWebp($quality)->save($webpPath);

                    // Remove old file if webp is smaller
                    if (file_exists($webpPath) && filesize($webpPath) < $originalSize) {
                        if ($filePath !== $webpPath) {
                            @unlink($filePath);
                        }
                        $outputPath = $webpPath;
                    } else {
                        @unlink($webpPath); // revert, webp wasn't better
                        $img->toWebp($quality)->save($filePath);
                        $outputPath = $filePath;
                    }
                } elseif ($ext === 'webp') {
                    $img->toWebp($quality)->save($filePath);
                } elseif ($ext === 'png') {
                    $img->toPng()->save($filePath);
                } else {
                    $img->toJpeg($quality)->save($filePath);
                }

                $newSize   = file_exists($outputPath) ? filesize($outputPath) : $originalSize;
                $saved     = $originalSize - $newSize;
                $totalSaved += $saved;
                $processCount++;

            } catch (\Throwable $e) {
                $this->newLine();
                $this->warn("  ⚠ Skipped (error): " . basename($filePath) . " — " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine(2);

        $savedMB = round($totalSaved / 1024 / 1024, 2);
        $this->info("✅ Done!");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Files processed',    $processCount],
                ['Files skipped',      $skipCount],
                ['Total space saved',  "{$savedMB} MB (" . round($totalSaved/1024) . " KB)"],
            ]
        );

        if (!$dryRun) {
            $this->newLine();
            $this->info("🚀 Run 'php artisan cache:clear' and redeploy for changes to take effect.");
        }

        return self::SUCCESS;
    }

    private function collectImages(string $dir): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, $this->allowed)) {
                    $files[] = $file->getRealPath();
                }
            }
        }

        return $files;
    }
}
