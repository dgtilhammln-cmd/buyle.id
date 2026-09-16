<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

$headers = [
    'User-Agent'                => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
    'Accept'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
    'Accept-Language'           => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
    'Accept-Encoding'           => 'gzip, deflate',
    'Sec-Ch-Ua'                 => '"Google Chrome";v="125", "Chromium";v="125", "Not.A/Brand";v="99"',
    'Sec-Ch-Ua-Mobile'          => '?0',
    'Sec-Ch-Ua-Platform'        => '"Windows"',
    'Sec-Fetch-Dest'            => 'document',
    'Sec-Fetch-Mode'            => 'navigate',
    'Sec-Fetch-Site'            => 'none',
    'Sec-Fetch-User'            => '?1',
    'Upgrade-Insecure-Requests' => '1',
];

echo "=== Test 1: Full Chrome Headers (gzip/deflate) ===\n";
try {
    $res = \Illuminate\Support\Facades\Http::withHeaders($headers)
        ->timeout(12)
        ->withOptions([
            'allow_redirects' => true,
            'curl' => [
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
                CURLOPT_ENCODING     => 'gzip,deflate',
            ]
        ])->get($url);

    echo "Status: " . $res->status() . " Length: " . strlen($res->body()) . "\n";
    $body = $res->body();
    if (str_contains($body, 'Cloudflare') || str_contains($body, 'Attention Required!')) {
        echo "Cloudflare blocked\n";
    } else {
        echo "SUCCESS! Sample: " . substr(strip_tags($body), 0, 400) . "\n";
        if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $body, $m)) {
            echo "TITLE: " . $m[1] . "\n";
        }
        if (preg_match_all('/https?:\/\/cdn\.lynkid\.my\.id\/[^\s"\']+/i', $body, $m)) {
            echo "IMAGES: " . implode(', ', array_unique($m[0])) . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}

// Test 3: Try Google Web Cache / Bing Cache / Google Amp / DuckDuckGo / Google Search JSON
echo "\n=== Test 3: Search Engines / Cache ===\n";
$searches = [
    'google_html' => 'https://www.google.com/search?q=' . urlencode('site:lynk.id/mindiw/PZbVe7P'),
    'bing_html' => 'https://www.bing.com/search?q=' . urlencode('site:lynk.id/mindiw/PZbVe7P'),
    'duckduckgo' => 'https://html.duckduckgo.com/html/?q=' . urlencode('site:lynk.id/mindiw/PZbVe7P'),
];

foreach ($searches as $name => $sUrl) {
    try {
        $res = \Illuminate\Support\Facades\Http::withHeaders($headers)->timeout(8)->get($sUrl);
        echo "Search ($name) -> Status: " . $res->status() . " Len: " . strlen($res->body()) . "\n";
        $html = $res->body();
        if ($name === 'duckduckgo' && preg_match_all('/<a[^>]+class=["\']result__snippet["\'][^>]*>(.*?)<\/a>/is', $html, $m)) {
            echo "DDG Snippet: " . implode(' | ', array_map('strip_tags', $m[1])) . "\n";
        }
        if ($name === 'bing_html' && preg_match('/<li[^>]*class=["\']b_algo["\'][^>]*>(.*?)<\/li>/is', $html, $m)) {
            echo "Bing Result: " . strip_tags($m[1]) . "\n";
        }
    } catch (\Throwable $e) {
        echo "Search ERR: " . $e->getMessage() . "\n";
    }
}
