<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "--- Testing Emsifa API ---\n";
$resProv = \Illuminate\Support\Facades\Http::timeout(5)->get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
echo "Provinces status: " . $resProv->status() . "\n";

$resReg = \Illuminate\Support\Facades\Http::timeout(5)->get('https://www.emsifa.com/api-wilayah-indonesia/api/regencies/35.json');
echo "Jatim Regencies status: " . $resReg->status() . "\n";
echo "First 2 regencies: " . json_encode(array_slice($resReg->json() ?? [], 0, 2)) . "\n";

// Surabaya is 3578 in BPS / Emsifa
$resDist = \Illuminate\Support\Facades\Http::timeout(5)->get('https://www.emsifa.com/api-wilayah-indonesia/api/districts/3578.json');
echo "Surabaya Districts status: " . $resDist->status() . "\n";
echo "Districts count: " . count($resDist->json() ?? []) . "\n";
echo "First 3 districts: " . json_encode(array_slice($resDist->json() ?? [], 0, 3)) . "\n";

if (!empty($resDist->json()[0]['id'])) {
    $distId = $resDist->json()[0]['id'];
    $resVill = \Illuminate\Support\Facades\Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$distId}.json");
    echo "Villages count for {$distId}: " . count($resVill->json() ?? []) . "\n";
    echo "First 3 villages: " . json_encode(array_slice($resVill->json() ?? [], 0, 3)) . "\n";
}
