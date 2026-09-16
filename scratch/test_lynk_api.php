<?php

require __DIR__ . '/../vendor/autoload.php';

// Test Lynk API or Next.js data endpoint
$urls = [
    'https://lynk.id/api/v1/link/Pv23p2E',
    'https://lynk.id/api/link/Pv23p2E',
    'https://lynk.id/api/p/Pv23p2E',
    'https://lynk.id/api/product/Pv23p2E',
];

foreach ($urls as $u) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $u);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        'Accept: application/json'
    ]);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "$u => Status: $code, Response: " . substr($res, 0, 200) . "\n";
}
