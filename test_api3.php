<?php
$key  = 'f1Hirqhga122b8bcfa5daa1ekEib4jrW';
$base = 'https://rajaongkir.komerce.id';

function hit($label, $url, $key) {
    $ch = curl_init();
    $opts = [
        CURLOPT_URL            => $url,
        CURLOPT_HTTPHEADER     => ["key: $key", "x-api-key: $key", "Accept: application/json"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 10,
    ];
    curl_setopt_array($ch, $opts);
    $res  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "[$code] $label\n   " . substr($res, 0, 200) . "\n\n";
}

hit('starter/province', "$base/starter/province", $key);
hit('basic/province', "$base/basic/province", $key);
hit('pro/province', "$base/pro/province", $key);
hit('api/province', "$base/api/province", $key);
hit('api/v1/province', "$base/api/v1/province", $key);
hit('api/v1/destination', "$base/api/v1/destination", $key);
hit('tariff/api/v1/destination', "$base/tariff/api/v1/destination", $key);
