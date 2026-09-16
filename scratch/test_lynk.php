<?php

require __DIR__ . '/../vendor/autoload.php';

$url = 'https://lynk.id/mindiw/Pv23p2E';

// Try cURL with realistic browser headers
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
    'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
    'Cache-Control: max-age=0',
    'Sec-Ch-Ua: "Not-A.Brand";v="99", "Chromium";v="124", "Google Chrome";v="124"',
    'Sec-Ch-Ua-Mobile: ?0',
    'Sec-Ch-Ua-Platform: "Windows"',
    'Sec-Fetch-Dest: document',
    'Sec-Fetch-Mode: navigate',
    'Sec-Fetch-Site: none',
    'Sec-Fetch-User: ?1',
    'Upgrade-Insecure-Requests: 1'
]);

$html = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "CURL HTTP Status: " . $httpCode . "\n";
echo "HTML Length: " . strlen($html) . "\n\n";

if ($html && $httpCode === 200) {
    preg_match_all('/<meta[^>]*property=["\'](og:[^"\']+)["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $m1);
    for ($i = 0; $i < count($m1[1]); $i++) {
        echo $m1[1][$i] . " => " . $m1[2][$i] . "\n";
    }

    if (preg_match('/<script id="__NEXT_DATA__"[^>]*>(.*?)<\/script>/is', $html, $nMatches)) {
        echo "\nFound __NEXT_DATA__!\n";
        file_put_contents(__DIR__ . '/lynk_next_data.json', $nMatches[1]);
        echo "Saved __NEXT_DATA__ to lynk_next_data.json!\n";
    }
}
