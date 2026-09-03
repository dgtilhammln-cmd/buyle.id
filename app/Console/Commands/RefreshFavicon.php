<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class RefreshFavicon extends Command
{
    protected $signature = 'favicon:refresh';
    protected $description = 'Copy current favicon from storage to public_html root (favicon.ico & favicon.png)';

    public function handle(): int
    {
        $favPath = Setting::get('favicon');

        if (empty($favPath)) {
            $this->error('No favicon setting found in DB.');
            return self::FAILURE;
        }

        $storedPath = storage_path('app/public/' . ltrim($favPath, '/'));

        if (!file_exists($storedPath)) {
            $this->error("Favicon file not found at: $storedPath");
            return self::FAILURE;
        }

        $targets = [
            public_path('favicon.ico'),
            public_path('favicon.png'),
            base_path('public_html/favicon.ico'),
            base_path('public_html/favicon.png'),
        ];

        foreach ($targets as $target) {
            try {
                $dir = dirname($target);
                if (!is_dir($dir)) @mkdir($dir, 0755, true);
                copy($storedPath, $target);
                $this->info("Copied to: $target");
            } catch (\Exception $e) {
                $this->warn("Failed to copy to $target: " . $e->getMessage());
            }
        }

        $this->info('Favicon refreshed successfully!');
        return self::SUCCESS;
    }
}
