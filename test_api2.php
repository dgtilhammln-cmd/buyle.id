<?php
$key  = 'f1Hirqhga122b8bcfa5daa1ekEib4jrW';
$base = 'https://rajaongkir.komerce.id';

function hit($label, $url, $key, $method = 'GET', $body = null) {
    $ch = curl_init();
    $opts = [
        CURLOPT_URL            => $url,
        CURLOPT_HTTPHEADER     => ["key: $key", "x-api-key: $key", "Accept: application/json"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 10,
    ];
    if ($method === 'POST') {
        $opts[CURLOPT_POST] = true;
        $opts[CURLOPT_POSTFIELDS] = $body ? http_build_query($body) : '';
    }
    curl_setopt_array($ch, $opts);
    $res  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "[$code] $label\n   " . substr($res, 0, 200) . "\n\n";
}

hit('Cost', "$base/api/cost", $key, 'POST', ['origin'=>304,'originType'=>'city','destination'=>305,'destinationType'=>'city','weight'=>1000,'courier'=>'jne']);
hit('Waybill', "$base/api/waybill", $key, 'POST', ['waybill'=>'test','courier'=>'jne']);
hit('Province', "$base/api/province", $key);
hit('City', "$base/api/city?province=11", $key);
