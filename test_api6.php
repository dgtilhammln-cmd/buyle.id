<?php
$key  = 'f1Hirqhga122b8bcfa5daa1ekEib4jrW';
$base = 'https://rajaongkir.komerce.id/api/v1';

function hit($label, $url, $key, $method = 'GET', $body = null) {
    $ch = curl_init();
    $opts = [
        CURLOPT_URL            => $url,
        CURLOPT_HTTPHEADER     => ["Authorization: Bearer $key", "key: $key", "Accept: application/json"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 10,
    ];
    if ($method === 'POST') {
        $opts[CURLOPT_POST] = true;
        if ($body) $opts[CURLOPT_POSTFIELDS] = http_build_query($body);
    }
    curl_setopt_array($ch, $opts);
    $res  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $icon = ($code == 200) ? '✅' : '❌';
    echo "$icon [$code] $label\n   " . substr($res, 0, 300) . "\n\n";
}

hit('province', "$base/destination/province", $key);
hit('city', "$base/destination/city/11", $key);
hit('cost', "$base/calculate/domestic-cost", $key, 'POST', ['origin'=>304, 'destination'=>305, 'weight'=>1000, 'courier'=>'jne']);
hit('waybill', "$base/waybill", $key, 'GET', ['waybill'=>'test', 'courier'=>'jne']); // guessing
