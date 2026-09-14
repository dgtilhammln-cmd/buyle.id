<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "--- Testing Complete District Resolver ---\n";

function resolveEmsifaDistricts($cityId, $cityName = null, $provinceId = null) {
    // 1. Direct try with $cityId
    $resDirect = \Illuminate\Support\Facades\Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$cityId}.json");
    if ($resDirect->successful() && is_array($resDirect->json()) && count($resDirect->json()) > 0) {
        return $resDirect->json();
    }

    // 2. Map RajaOngkir province IDs to Emsifa province IDs
    $provMap = [
        '1'  => '51', // Bali
        '2'  => '19', // Bangka Belitung
        '3'  => '36', // Banten
        '4'  => '17', // Bengkulu
        '5'  => '34', // DI Yogyakarta
        '6'  => '31', // DKI Jakarta
        '7'  => '75', // Gorontalo
        '8'  => '15', // Jambi
        '9'  => '32', // Jawa Barat
        '10' => '33', // Jawa Tengah
        '11' => '35', // Jawa Timur
        '12' => '61', // Kalbar
        '13' => '63', // Kalsel
        '14' => '62', // Kalteng
        '15' => '64', // Kaltim
        '16' => '65', // Kaltara
        '17' => '21', // Kepulauan Riau
        '18' => '18', // Lampung
        '19' => '81', // Maluku
        '20' => '82', // Maluku Utara
        '21' => '52', // NTB
        '22' => '53', // NTT
        '23' => '91', // Papua
        '24' => '92', // Papua Barat
        '25' => '14', // Riau
        '26' => '76', // Sulbar
        '27' => '73', // Sulsel
        '28' => '72', // Sulteng
        '29' => '74', // Sultra
        '30' => '71', // Sulut
        '31' => '13', // Sumbar
        '32' => '16', // Sumsel
        '33' => '12', // Sumut
        '34' => '11', // Aceh
    ];

    $emsifaProvId = $provMap[(string)$provinceId] ?? null;

    // If provinceId not provided or mapped, search all Emsifa provinces if needed
    $searchProvinces = $emsifaProvId ? [$emsifaProvId] : array_values($provMap);

    $cleanCityName = trim(preg_replace('/^(kota|kabupaten|kab\.)\s+/i', '', $cityName ?? ''));

    foreach ($searchProvinces as $emsProv) {
        $resReg = \Illuminate\Support\Facades\Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$emsProv}.json");
        if ($resReg->successful() && is_array($resReg->json())) {
            foreach ($resReg->json() as $reg) {
                $cleanRegName = trim(preg_replace('/^(kota|kabupaten|kab\.)\s+/i', '', $reg['name']));
                if ($cleanCityName && (strcasecmp($cleanRegName, $cleanCityName) === 0 || stristr($cleanRegName, $cleanCityName) || stristr($cleanCityName, $cleanRegName))) {
                    $resDist = \Illuminate\Support\Facades\Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$reg['id']}.json");
                    if ($resDist->successful() && is_array($resDist->json())) {
                        return $resDist->json();
                    }
                }
            }
        }
    }

    return [];
}

// Test cityId 304 (Surabaya, Province 11)
$districts = resolveEmsifaDistricts(304, 'Kota Surabaya', 11);
echo "City 304 (Surabaya) resolved districts count: " . count($districts) . "\n";
if (count($districts) > 0) {
    echo "First 3: " . json_encode(array_slice($districts, 0, 3)) . "\n";
}

// Test cityId 419 (Sidoarjo, Province 11)
$districts2 = resolveEmsifaDistricts(419, 'Kabupaten Sidoarjo', 11);
echo "City 419 (Sidoarjo) resolved districts count: " . count($districts2) . "\n";
if (count($districts2) > 0) {
    echo "First 3: " . json_encode(array_slice($districts2, 0, 3)) . "\n";
}
