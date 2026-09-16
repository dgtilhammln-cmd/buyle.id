<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

echo "=== Querying Google ===\n";
try {
    $res = \Illuminate\Support\Facades\Http::timeout(10)->withHeaders([
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
    ])->get('https://www.google.com/search', [
        'q' => 'site:lynk.id/mindiw PZbVe7P',
        'hl' => 'id'
    ]);

    $html = $res->body();
    echo "Status: " . $res->status() . " Length: " . strlen($html) . "\n";
    file_put_contents(__DIR__ . '/google_mindiw.html', $html);

    // Regex for Google search results
    preg_match_all('/<h3[^>]*>(.*?)<\/h3>/is', $html, $titles);
    echo "Titles:\n";
    print_r($titles[1] ?? []);

    preg_match_all('/<div[^>]*class=["\'][^"\']*(?:VwiC3b|yXK7lf|MUwGfd)[^"\']*["\'][^>]*>(.*?)<\/div>/is', $html, $snippets);
    echo "Snippets:\n";
    print_r($snippets[1] ?? []);

} catch (\Throwable $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
