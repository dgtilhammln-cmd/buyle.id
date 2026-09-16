<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

echo "=== DDG HTML Search Dump ===\n";
try {
    $res = \Illuminate\Support\Facades\Http::timeout(10)->withOptions(['verify' => false])->withHeaders([
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
    ])->asForm()->post('https://html.duckduckgo.com/html/', [
        'q' => 'lynk.id/mindiw/PZbVe7P'
    ]);

    $html = $res->body();
    echo "Status: " . $res->status() . " Length: " . strlen($html) . "\n";
    file_put_contents(__DIR__ . '/ddg.html', $html);

    // Find all links & titles in DDG html
    if (preg_match_all('/<a[^>]+class=["\']result__title["\'][^>]*>(.*?)<\/a>/is', $html, $m)) {
        echo "DDG Titles:\n";
        foreach ($m[1] as $t) {
            echo " - " . trim(strip_tags($t)) . "\n";
        }
    } else {
        echo "No result__title matches found.\n";
    }

    if (preg_match_all('/<a[^>]+class=["\']result__snippet["\'][^>]*>(.*?)<\/a>/is', $html, $m2)) {
        echo "DDG Snippets:\n";
        foreach ($m2[1] as $s) {
            echo " - " . trim(strip_tags($s)) . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
