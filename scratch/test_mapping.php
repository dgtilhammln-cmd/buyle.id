<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "--- Testing Name Matching Mapping ---\n";

// Example: city_id 304 (Surabaya), province 11 (Jawa Timur)
// Emsifa province for Jawa Timur is 35
$resReg = \Illuminate\Support\Facades\Http::timeout(5)->get('https://www.emsifa.com/api-wilayah-indonesia/api/regencies/35.json');
$emsifaRegencies = $resReg->json();

$cityName = "Kota Surabaya";
$cleanCityName = trim(preg_replace('/^(kota|kabupaten|kab\.)\s+/i', '', $cityName));
echo "Clean City Name: {$cleanCityName}\n";

$matchedRegencyId = null;
foreach ($emsifaRegencies as $reg) {
    $cleanRegName = trim(preg_replace('/^(kota|kabupaten|kab\.)\s+/i', '', $reg['name']));
    if (strcasecmp($cleanRegName, $cleanCityName) === 0 || stristr($cleanRegName, $cleanCityName) || stristr($cleanCityName, $cleanRegName)) {
        $matchedRegencyId = $reg['id'];
        echo "MATCHED: {$reg['name']} -> ID {$reg['id']}\n";
        break;
    }
}

if ($matchedRegencyId) {
    $resDist = \Illuminate\Support\Facades\Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$matchedRegencyId}.json");
    echo "Districts count for {$matchedRegencyId}: " . count($resDist->json() ?? []) . "\n";
    print_r(array_slice($resDist->json() ?? [], 0, 5));
}
