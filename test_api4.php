<?php
$key  = 'f1Hirqhga122b8bcfa5daa1ekEib4jrW';
$base = 'https://rajaongkir.komerce.id';

function hit($label, $url, $key, $method = 'GET') {
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
    }
    curl_setopt_array($ch, $opts);
    $res  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "[$code] $label\n   " . substr($res, 0, 200) . "\n\n";
}

hit('api/v1/calculate', "$base/api/v1/calculate", $key);
hit('order/api/v1/orders/history-airway-bill', "$base/order/api/v1/orders/history-airway-bill", $key);
hit('api/v1/orders/history-airway-bill', "$base/api/v1/orders/history-airway-bill", $key);
hit('api/v1/history-airway-bill', "$base/api/v1/history-airway-bill", $key);
