<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CleanTempImagesCommand extends Command
{
    /**
     * Nama dan signature perintah console.
     *
     * @var string
     */
    protected $signature = 'tools:clean-temp-images {--days=14 : Batas usia file dalam hitungan hari} {--force : Jalankan pembersihan tanpa konfirmasi}';

    /**
     * Deskripsi perintah console.
     *
     * @var string
     */
    protected $description = 'Hapus otomatis file gambar asal dan hasil konversi yang berusia lebih dari 2 minggu (14 hari) agar hosting tetap lega';

    /**
     * Eksekusi perintah console.
     */
    public function handle()
    {
        $days = (int) $this->option('days') ?: 14;
        $expiryTimestamp = now()->subDays($days)->timestamp;

        $targetDirectories = [
            storage_path('app/temp'),
            storage_path('app/conversions'),
            storage_path('app/public/tools'),
            storage_path('app/public/temp'),
            storage_path('app/tools/conversions'),
            public_path('uploads/temp'),
            public_path('storage/tools'),
            public_path('storage/temp'),
        ];

        $deletedCount = 0;
        $totalBytesFreed = 0;

        $this->info("Memulai pembersihan file gambar sementara yang berusia lebih dari {$days} hari (2 minggu)...");

        foreach ($targetDirectories as $dir) {
            if (!File::exists($dir) || !File::isDirectory($dir)) {
                continue;
            }

            try {
                $files = File::allFiles($dir);

                foreach ($files as $file) {
                    $mtime = $file->getMTime();

                    if ($mtime < $expiryTimestamp) {
                        $size = $file->getSize();
                        $path = $file->getRealPath();

                        if (File::delete($path)) {
                            $deletedCount++;
                            $totalBytesFreed += $size;
                        }
                    }
                }

                // Bersihkan sub-direktori kosong
                $subDirs = File::directories($dir);
                foreach ($subDirs as $subDir) {
                    if (count(File::allFiles($subDir)) === 0) {
                        @rmdir($subDir);
                    }
                }
            } catch (\Throwable $e) {
                $this->warn("Gagal memproses folder [{$dir}]: " . $e->getMessage());
                Log::warning("tools:clean-temp-images error pada folder {$dir}: " . $e->getMessage());
            }
        }

        $mbFreed = round($totalBytesFreed / (1024 * 1024), 2);
        $message = "Selesai: {$deletedCount} file berhasil dihapus. Ruang penyimpanan berhasil dibersihkan sebesar {$mbFreed} MB.";

        $this->info($message);
        Log::info("Pembersihan Auto Delete Gambar: {$message}");

        return Command::SUCCESS;
    }
}

