<?php

require __DIR__ . '/../vendor/autoload.php';

$url = 'https://lynk.id/mindiw/Pv23p2E';

$client = new \GuzzleHttp\Client([
    'timeout'         => 10,
    'verify'          => false,
    'allow_redirects' => ['max' => 5],
    'headers'         => [
        'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
        'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8',
        'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    ]
]);

try {
    $res = $client->get($url);
    $html = (string) $res->getBody();
    echo "HTTP Status: " . $res->getStatusCode() . "\n";
    echo "HTML Length: " . strlen($html) . "\n\n";

    // Extract OpenGraph tags
    preg_match_all('/<meta[^>]*property=["\'](og:[^"\']+)["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $m1);
    for ($i = 0; $i < count($m1[1]); $i++) {
        echo $m1[1][$i] . " => " . $m1[2][$i] . "\n";
    }

    // Extract twitter tags
    preg_match_all('/<meta[^>]*name=["\']([^"\']+)["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $m2);
    for ($i = 0; $i < count($m2[1]); $i++) {
        echo $m2[1][$i] . " => " . $m2[2][$i] . "\n";
    }

    // Extract JSON-LD / __NEXT_DATA__
    if (preg_match('/<script id="__NEXT_DATA__"[^>]*>(.*?)<\/script>/is', $html, $nMatches)) {
        echo "\nFound __NEXT_DATA__!\n";
        $nextJson = json_decode($nMatches[1], true);
        print_r(array_keys($nextJson['props']['pageProps'] ?? []));
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
