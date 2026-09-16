<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';
$cleanUrl = preg_replace('#^https?://#i', '', $url);

$queries = [
    'site:' . $cleanUrl,
    '"' . $cleanUrl . '"',
    'lynk.id mindiw PZbVe7P',
    'mindiw PZbVe7P',
];

foreach ($queries as $q) {
    echo "=== Searching Google for: $q ===\n";
    try {
        $res = \Illuminate\Support\Facades\Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        ])->get('https://www.google.com/search?q=' . urlencode($q));

        $html = $res->body();
        echo "Status: " . $res->status() . " Length: " . strlen($html) . "\n";

        if (preg_match_all('/<h3[^>]*>(.*?)<\/h3>/is', $html, $titles)) {
            foreach ($titles[1] as $t) {
                $cleanT = strip_tags($t);
                if (!empty($cleanT) && strlen($cleanT) > 3) {
                    echo " - Title: " . $cleanT . "\n";
                }
            }
        }
    } catch (\Throwable $e) {
        echo "ERR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
