<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class CheckoutApiController extends Controller
{
    // =========================================================
    // Static province data — always works even if API is blocked
    // Data matches RajaOngkir province IDs exactly
    // =========================================================
    private static array $staticProvinces = [
        ['province_id' => '34', 'province' => 'Aceh'],
        ['province_id' => '1',  'province' => 'Bali'],
        ['province_id' => '2',  'province' => 'Bangka Belitung'],
        ['province_id' => '3',  'province' => 'Banten'],
        ['province_id' => '4',  'province' => 'Bengkulu'],
        ['province_id' => '5',  'province' => 'DI Yogyakarta'],
        ['province_id' => '6',  'province' => 'DKI Jakarta'],
        ['province_id' => '7',  'province' => 'Gorontalo'],
        ['province_id' => '8',  'province' => 'Jambi'],
        ['province_id' => '9',  'province' => 'Jawa Barat'],
        ['province_id' => '10', 'province' => 'Jawa Tengah'],
        ['province_id' => '11', 'province' => 'Jawa Timur'],
        ['province_id' => '12', 'province' => 'Kalimantan Barat'],
        ['province_id' => '13', 'province' => 'Kalimantan Selatan'],
        ['province_id' => '14', 'province' => 'Kalimantan Tengah'],
        ['province_id' => '15', 'province' => 'Kalimantan Timur'],
        ['province_id' => '16', 'province' => 'Kalimantan Utara'],
        ['province_id' => '17', 'province' => 'Kepulauan Riau'],
        ['province_id' => '18', 'province' => 'Lampung'],
        ['province_id' => '19', 'province' => 'Maluku'],
        ['province_id' => '20', 'province' => 'Maluku Utara'],
        ['province_id' => '21', 'province' => 'Nusa Tenggara Barat'],
        ['province_id' => '22', 'province' => 'Nusa Tenggara Timur'],
        ['province_id' => '23', 'province' => 'Papua'],
        ['province_id' => '24', 'province' => 'Papua Barat'],
        ['province_id' => '25', 'province' => 'Riau'],
        ['province_id' => '26', 'province' => 'Sulawesi Barat'],
        ['province_id' => '27', 'province' => 'Sulawesi Selatan'],
        ['province_id' => '28', 'province' => 'Sulawesi Tengah'],
        ['province_id' => '29', 'province' => 'Sulawesi Tenggara'],
        ['province_id' => '30', 'province' => 'Sulawesi Utara'],
        ['province_id' => '31', 'province' => 'Sumatera Barat'],
        ['province_id' => '32', 'province' => 'Sumatera Selatan'],
        ['province_id' => '33', 'province' => 'Sumatera Utara'],
    ];

    // =========================================================
    // Static city data for Jawa Timur (province 11) as fast fallback
    // since seller is in Surabaya. Other provinces load from API.
    // =========================================================
    private static array $staticCitiesJatim = [
        ['city_id'=>'1','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Bangkalan','postal_code'=>'69116'],
        ['city_id'=>'19','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Banyuwangi','postal_code'=>'68411'],
        ['city_id'=>'36','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Blitar','postal_code'=>'66171'],
        ['city_id'=>'37','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kota','city_name'=>'Blitar','postal_code'=>'66111'],
        ['city_id'=>'45','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Bojonegoro','postal_code'=>'62111'],
        ['city_id'=>'54','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Bondowoso','postal_code'=>'68211'],
        ['city_id'=>'80','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kota','city_name'=>'Batu','postal_code'=>'65311'],
        ['city_id'=>'92','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Gresik','postal_code'=>'61111'],
        ['city_id'=>'118','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Jember','postal_code'=>'68111'],
        ['city_id'=>'119','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Jombang','postal_code'=>'61411'],
        ['city_id'=>'155','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Kediri','postal_code'=>'64182'],
        ['city_id'=>'156','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kota','city_name'=>'Kediri','postal_code'=>'64111'],
        ['city_id'=>'172','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Lamongan','postal_code'=>'62211'],
        ['city_id'=>'178','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Lumajang','postal_code'=>'67311'],
        ['city_id'=>'179','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Madiun','postal_code'=>'63153'],
        ['city_id'=>'180','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kota','city_name'=>'Madiun','postal_code'=>'63111'],
        ['city_id'=>'185','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Magetan','postal_code'=>'63311'],
        ['city_id'=>'190','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Malang','postal_code'=>'65156'],
        ['city_id'=>'191','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kota','city_name'=>'Malang','postal_code'=>'65111'],
        ['city_id'=>'204','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Mojokerto','postal_code'=>'61361'],
        ['city_id'=>'205','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kota','city_name'=>'Mojokerto','postal_code'=>'61311'],
        ['city_id'=>'218','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Nganjuk','postal_code'=>'64411'],
        ['city_id'=>'219','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Ngawi','postal_code'=>'63211'],
        ['city_id'=>'232','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Pacitan','postal_code'=>'63511'],
        ['city_id'=>'236','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Pamekasan','postal_code'=>'69311'],
        ['city_id'=>'239','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Pasuruan','postal_code'=>'67154'],
        ['city_id'=>'240','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kota','city_name'=>'Pasuruan','postal_code'=>'67111'],
        ['city_id'=>'243','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Ponorogo','postal_code'=>'63411'],
        ['city_id'=>'254','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Probolinggo','postal_code'=>'67271'],
        ['city_id'=>'255','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kota','city_name'=>'Probolinggo','postal_code'=>'67211'],
        ['city_id'=>'273','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Sampang','postal_code'=>'69211'],
        ['city_id'=>'288','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Sidoarjo','postal_code'=>'61211'],
        ['city_id'=>'290','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Situbondo','postal_code'=>'68311'],
        ['city_id'=>'295','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Sumenep','postal_code'=>'69411'],
        ['city_id'=>'304','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kota','city_name'=>'Surabaya','postal_code'=>'60111'],
        ['city_id'=>'311','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Trenggalek','postal_code'=>'66311'],
        ['city_id'=>'317','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Tuban','postal_code'=>'62311'],
        ['city_id'=>'318','province_id'=>'11','province'=>'Jawa Timur','type'=>'Kabupaten','city_name'=>'Tulungagung','postal_code'=>'66211'],
    ];

    /**
     * Mode aktif: 'sandbox' atau 'live'.
     * Dikontrol dari Admin → API & Integrasi.
     */
    private function isLiveMode(): bool
    {
        return Setting::get('komerce_mode', 'sandbox') === 'live';
    }

    private function getTariffApiKey(): ?string
    {
        if ($this->isLiveMode()) {
            return Setting::get('rajaongkir_api_key') ?: null;
        }
        return Setting::get('rajaongkir_api_key_sandbox') ?: Setting::get('rajaongkir_api_key') ?: null;
    }

    private function getApiKey(): ?string
    {
        if ($this->isLiveMode()) {
            return Setting::get('shipping_delivery_api_key') ?: Setting::get('rajaongkir_api_key') ?: null;
        }
        return Setting::get('shipping_delivery_api_key_sandbox') ?: Setting::get('rajaongkir_api_key_sandbox') ?: null;
    }

    /**
     * Header untuk Tariff/Ongkir endpoints (gunakan Shipping Cost key).
     */
    private function getTariffHeaders(): array
    {
        return [
            'x-api-key'  => $this->getTariffApiKey(),
            'Accept'     => 'application/json',
            'User-Agent' => 'Mozilla/5.0',
        ];
    }

    /**
     * Header untuk Order/AWB/Delivery endpoints (gunakan Shipping Delivery key).
     */
    private function getApiHeaders(): array
    {
        return [
            'x-api-key'  => $this->getApiKey(),
            'Accept'     => 'application/json',
            'User-Agent' => 'Mozilla/5.0',
        ];
    }

    // =========================================================
    // PROVINCES — always returns static data (no API call)
    // =========================================================
    public function provinces()
    {
        // Try API first (with short 5s timeout), cache for 24h
        $cached = Cache::get('rajaongkir_provinces');
        if ($cached) {
            return response()->json($cached);
        }

        $apiKey = $this->getTariffApiKey();
        $isLive = $this->isLiveMode();

        if ($apiKey) {
            try {
                if ($isLive) {
                    // LIVE: Komerce Shipping Cost API
                    $response = Http::withoutVerifying()
                                    ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                                    ->timeout(5)
                                    ->withHeaders([
                                        'x-api-key'  => $apiKey,
                                        'Accept'     => 'application/json',
                                        'User-Agent' => 'Mozilla/5.0',
                                    ])
                                    ->get('https://api.collaborator.komerce.id/tariff/api/v1/destination', [
                                        'keyword' => '',
                                        'type'    => 'province',
                                    ]);
                } else {
                    // SANDBOX: Legacy RajaOngkir Komerce Proxy
                    $response = Http::withoutVerifying()
                                    ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                                    ->timeout(5)
                                    ->withHeaders([
                                        'key'        => $apiKey,
                                        'User-Agent' => 'Mozilla/5.0',
                                    ])
                                    ->get('https://rajaongkir.komerce.id/api/v1/destination/province');
                }

                $json = $response->json();

                if ($response->successful() && isset($json['data']) && count($json['data']) > 0) {
                    $results = array_map(function($item) {
                        return [
                            'province_id' => $item['id'] ?? $item['province_id'] ?? '',
                            'province'    => $item['name'] ?? $item['province'] ?? '',
                        ];
                    }, $json['data']);

                    Cache::put('rajaongkir_provinces', $results, now()->addHours(24));
                    return response()->json($results);
                }

                Log::warning('[ONGKIR] Province API non-success', ['status' => $response->status(), 'body' => $response->body()]);
            } catch (\Exception $e) {
                Log::warning('Komerce Provinces API unreachable, using static data: ' . $e->getMessage());
            }
        }

        // Fallback: always return static data
        return response()->json(self::$staticProvinces);
    }

    // =========================================================
    // CITIES — try API with cache, fallback to static (Jawa Timur)
    // =========================================================
    public function cities($provinceId)
    {
        // Check cache first
        $cacheKey = 'rajaongkir_cities_' . $provinceId;
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return response()->json($cached);
        }

        $apiKey = $this->getTariffApiKey();
        $isLive = $this->isLiveMode();

        if ($apiKey) {
            try {
                if ($isLive) {
                    // LIVE: Komerce Shipping Cost API
                    $response = Http::withoutVerifying()
                                    ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                                    ->timeout(8)
                                    ->withHeaders([
                                        'x-api-key'  => $apiKey,
                                        'Accept'     => 'application/json',
                                        'User-Agent' => 'Mozilla/5.0',
                                    ])
                                    ->get('https://api.collaborator.komerce.id/tariff/api/v1/destination', [
                                        'keyword'     => '',
                                        'province_id' => $provinceId,
                                        'type'        => 'subdistrict',
                                    ]);
                } else {
                    // SANDBOX: Legacy RajaOngkir Komerce Proxy
                    $response = Http::withoutVerifying()
                                    ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                                    ->timeout(8)
                                    ->withHeaders([
                                        'key'        => $apiKey,
                                        'User-Agent' => 'Mozilla/5.0',
                                    ])
                                    ->get('https://rajaongkir.komerce.id/api/v1/destination/city/' . $provinceId);
                }

                $json = $response->json();

                if ($response->successful() && isset($json['data']) && count($json['data']) > 0) {
                    $results = array_map(function($item) {
                        return [
                            'city_id'   => $item['id'] ?? $item['subdistrict_id'] ?? $item['city_id'] ?? '',
                            'city_name' => $item['name'] ?? $item['subdistrict_name'] ?? $item['city_name'] ?? '',
                            'type'      => $item['type'] ?? 'Kota/Kabupaten',
                        ];
                    }, $json['data']);

                    Cache::put($cacheKey, $results, now()->addHours(24));
                    return response()->json($results);
                }

                Log::warning('[ONGKIR] Cities API non-success', ['province' => $provinceId, 'status' => $response->status(), 'body' => substr($response->body(), 0, 500)]);
            } catch (\Exception $e) {
                Log::warning('Komerce Cities API unreachable for province ' . $provinceId . ': ' . $e->getMessage());
            }
        }

        // Fallback: EMSIFA API (https://www.emsifa.com/api-wilayah-indonesia)
        $provMap = [
            '1' => '51', '2' => '19', '3' => '36', '4' => '17', '5' => '34',
            '6' => '31', '7' => '75', '8' => '15', '9' => '32', '10' => '33',
            '11' => '35', '12' => '61', '13' => '63', '14' => '62', '15' => '64',
            '16' => '65', '17' => '21', '18' => '18', '19' => '81', '20' => '82',
            '21' => '52', '22' => '53', '23' => '91', '24' => '92', '25' => '14',
            '26' => '76', '27' => '73', '28' => '72', '29' => '74', '30' => '71',
            '31' => '13', '32' => '16', '33' => '12', '34' => '11'
        ];

        $emsifaProvId = $provMap[(string)$provinceId] ?? ((int)$provinceId > 34 ? (string)$provinceId : '35');

        try {
            $response = Http::timeout(6)->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$emsifaProvId}.json");
            if ($response->successful() && is_array($response->json()) && count($response->json()) > 0) {
                $results = [];
                $staticMapJatim = [
                    'bangkalan' => 1, 'banyuwangi' => 19, 'blitar' => 36, 'bojonegoro' => 45,
                    'bondowoso' => 54, 'batu' => 80, 'gresik' => 92, 'jember' => 118,
                    'jombang' => 119, 'kediri' => 155, 'lamongan' => 172, 'lumajang' => 178,
                    'madiun' => 179, 'magetan' => 185, 'malang' => 190, 'mojokerto' => 204,
                    'nganjuk' => 218, 'ngawi' => 219, 'pacitan' => 232, 'pamekasan' => 236,
                    'pasuruan' => 239, 'ponorogo' => 243, 'probolinggo' => 254, 'sampang' => 273,
                    'sidoarjo' => 288, 'situbondo' => 290, 'sumenep' => 295, 'surabaya' => 304,
                    'trenggalek' => 311, 'tuban' => 317, 'tulungagung' => 318
                ];

                foreach ($response->json() as $item) {
                    $rawName = $item['name'];
                    $type = (str_starts_with(strtoupper($rawName), 'KOTA')) ? 'Kota' : 'Kabupaten';
                    $cleanName = trim(preg_replace('/^(kota|kabupaten|kab\.)\s+/i', '', $rawName));
                    $formattedName = ucwords(strtolower($cleanName));
                    $lowerClean = strtolower($cleanName);

                    $rajaCityId = $staticMapJatim[$lowerClean] ?? $item['id'];

                    $results[] = [
                        'city_id'   => (string)$rajaCityId,
                        'emsifa_id' => $item['id'],
                        'city_name' => $formattedName,
                        'type'      => $type,
                    ];
                }

                Cache::put($cacheKey, $results, now()->addHours(24));
                return response()->json($results);
            }
        } catch (\Exception $e) {
            Log::warning('EMSIFA Cities API error for prov ' . $provinceId . ': ' . $e->getMessage());
        }

        // Final fallback: return static Jawa Timur data if province 11
        if ((string)$provinceId === '11') {
            return response()->json(self::$staticCitiesJatim);
        }

        return response()->json(self::$staticCitiesJatim);
    }

    // =========================================================
    // DISTRICTS (Kecamatan) — try API with EMSIFA fallback
    // =========================================================
    public function districts(Request $request, $cityId)
    {
        $cityName   = $request->query('city_name');
        $provinceId = $request->query('province_id');

        $cacheKey = 'rajaongkir_districts_' . $cityId . '_' . md5($cityName ?? '');
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return response()->json($cached);
        }

        $apiKey = $this->getTariffApiKey();
        $isLive = $this->isLiveMode();

        if ($apiKey) {
            try {
                if ($isLive) {
                    $response = Http::withoutVerifying()
                                    ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                                    ->timeout(6)
                                    ->withHeaders([
                                        'x-api-key'  => $apiKey,
                                        'Accept'     => 'application/json',
                                        'User-Agent' => 'Mozilla/5.0',
                                    ])
                                    ->get('https://api.collaborator.komerce.id/tariff/api/v1/destination', [
                                        'city_id' => $cityId,
                                        'type'    => 'subdistrict',
                                    ]);
                } else {
                    $response = Http::withoutVerifying()
                                    ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                                    ->timeout(6)
                                    ->withHeaders([
                                        'key'        => $apiKey,
                                        'User-Agent' => 'Mozilla/5.0',
                                    ])
                                    ->get('https://rajaongkir.komerce.id/api/v1/destination/subdistrict/' . $cityId);
                }

                $json = $response->json();

                if ($response->successful() && isset($json['data']) && count($json['data']) > 0) {
                    $results = array_map(function($item) {
                        return [
                            'district_id'   => $item['id'] ?? $item['subdistrict_id'] ?? $item['district_id'] ?? '',
                            'district_name' => $item['name'] ?? $item['subdistrict_name'] ?? $item['district_name'] ?? '',
                            'postal_code'   => $item['postal_code'] ?? $item['zip_code'] ?? '',
                        ];
                    }, $json['data']);

                    Cache::put($cacheKey, $results, now()->addHours(24));
                    return response()->json($results);
                }
            } catch (\Exception $e) {
                Log::warning('Komerce Districts API error for city ' . $cityId . ': ' . $e->getMessage());
            }
        }

        // Fallback: EMSIFA API (https://www.emsifa.com/api-wilayah-indonesia)
        $emsifaData = $this->resolveEmsifaDistricts($cityId, $cityName, $provinceId);
        if (!empty($emsifaData)) {
            $results = array_map(function($item) {
                return [
                    'district_id'   => $item['id'],
                    'district_name' => ucwords(strtolower($item['name'])),
                    'postal_code'   => '',
                ];
            }, $emsifaData);

            Cache::put($cacheKey, $results, now()->addHours(24));
            return response()->json($results);
        }

        return response()->json([]);
    }

    private function resolveEmsifaDistricts($cityId, $cityName = null, $provinceId = null): array
    {
        // 1. Direct try with $cityId
        try {
            $resDirect = Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$cityId}.json");
            if ($resDirect->successful() && is_array($resDirect->json()) && count($resDirect->json()) > 0) {
                return $resDirect->json();
            }
        } catch (\Exception $e) {}

        // 2. Map RajaOngkir province IDs to EMSIFA province IDs
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
        $searchProvinces = $emsifaProvId ? [$emsifaProvId] : array_values($provMap);
        $cleanCityName = trim(preg_replace('/^(kota|kabupaten|kab\.)\s+/i', '', $cityName ?? ''));

        foreach ($searchProvinces as $emsProv) {
            try {
                $resReg = Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$emsProv}.json");
                if ($resReg->successful() && is_array($resReg->json())) {
                    foreach ($resReg->json() as $reg) {
                        $cleanRegName = trim(preg_replace('/^(kota|kabupaten|kab\.)\s+/i', '', $reg['name']));
                        if ($cleanCityName && (strcasecmp($cleanRegName, $cleanCityName) === 0 || stristr($cleanRegName, $cleanCityName) || stristr($cleanCityName, $cleanRegName))) {
                            $resDist = Http::timeout(5)->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$reg['id']}.json");
                            if ($resDist->successful() && is_array($resDist->json()) && count($resDist->json()) > 0) {
                                return $resDist->json();
                            }
                        }
                    }
                }
            } catch (\Exception $e) {}
        }

        return [];
    }

    // =========================================================
    // SUBDISTRICTS / VILLAGES (Kelurahan / Desa & Kode Pos)
    // =========================================================
    public function subdistricts($districtId)
    {
        $cacheKey = 'rajaongkir_subdistricts_' . $districtId;
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return response()->json($cached);
        }

        try {
            $response = Http::timeout(6)->get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json");
            if ($response->successful() && is_array($response->json())) {
                $results = array_map(function($item) {
                    return [
                        'village_id'   => $item['id'],
                        'village_name' => ucwords(strtolower($item['name'])),
                        'district_id'  => $item['district_id'] ?? '',
                    ];
                }, $response->json());
                Cache::put($cacheKey, $results, now()->addHours(24));
                return response()->json($results);
            }
        } catch (\Exception $e) {
            Log::warning('Subdistricts API error for district ' . $districtId . ': ' . $e->getMessage());
        }

        return response()->json([]);
    }

    // =========================================================
    // COST — try API, graceful fallback with manual cost option
    // =========================================================
    public function cost(Request $request)
    {
        $request->validate([
            'destination' => 'required|integer',
            'weight'      => 'required|integer|min:1',
            'courier'     => 'required|string',
        ]);

        // Intercept Custom Courier (Kurir Toko)
        if (strtolower($request->courier) === 'custom') {
            Log::info('[ONGKIR] Menggunakan Kurir Toko (Manual)');
            return response()->json([
                'manual'      => true,
                'message'     => 'Menggunakan Kurir Toko. Ongkir dikonfirmasi manual oleh Admin.',
                'debug_error' => 'custom_courier'
            ]);
        }

        $apiKey = $this->getTariffApiKey();
        $isLive = $this->isLiveMode();

        if (!$apiKey) {
            Log::warning('[ONGKIR] Tariff API Key belum dikonfigurasi. Menggunakan estimasi ongkir fallback.');
            return response()->json($this->getFallbackShippingOptions($request->courier));
        }

        $origin = (int) Setting::get('rajaongkir_origin_city', 304); // 304 = Surabaya

        Log::info('[ONGKIR] Request dikirim', [
            'mode'        => $isLive ? 'LIVE' : 'SANDBOX',
            'origin'      => $origin,
            'destination' => $request->destination,
            'weight'      => $request->weight,
            'courier'     => $request->courier,
        ]);

        try {
            if ($isLive) {
                // LIVE: Komerce Shipping Cost API (GET)
                $response = Http::withoutVerifying()
                                ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                                ->timeout(10)
                                ->withHeaders([
                                    'x-api-key'  => $apiKey,
                                    'Accept'     => 'application/json',
                                    'User-Agent' => 'Mozilla/5.0',
                                ])
                                ->get('https://api.collaborator.komerce.id/tariff/api/v1/calculate', [
                                    'origin_id'      => $origin,
                                    'destination_id' => $request->destination,
                                    'weight'         => $request->weight,
                                    'courier_code'   => strtolower($request->courier),
                                ]);
            } else {
                // SANDBOX: Legacy RajaOngkir Komerce Proxy (POST form data)
                $response = Http::withoutVerifying()
                                ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                                ->timeout(10)
                                ->withHeaders([
                                    'key'        => $apiKey,
                                    'User-Agent' => 'Mozilla/5.0'
                                ])
                                ->asForm()
                                ->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                                    'origin'      => $origin,
                                    'destination' => $request->destination,
                                    'weight'      => $request->weight,
                                    'courier'     => strtolower($request->courier),
                                ]);
            }

            $statusCode = $response->status();
            $json       = $response->json();

            Log::info('[ONGKIR] Response diterima', [
                'http_status'   => $statusCode,
                'meta_status'   => $json['meta']['status'] ?? $json['status'] ?? null,
                'has_data'      => isset($json['data']),
                'results_count' => count($json['data'] ?? []),
            ]);

            // Cek HTTP status & meta error — jika ada kesalahan API Key atau API failure, gunakan fallback estimasi ongkir
            if ($statusCode !== 200 || isset($json['error']) || (isset($json['meta']['status']) && strtolower($json['meta']['status']) === 'error') || (isset($json['status']) && $json['status'] === false)) {
                $desc = $json['meta']['message'] ?? $json['message'] ?? $json['error'] ?? "HTTP Error {$statusCode}";
                Log::warning('[ONGKIR] Error dari API Komerce/RajaOngkir (' . $desc . '). Menggunakan estimasi ongkir fallback.');
                return response()->json($this->getFallbackShippingOptions($request->courier));
            }

            // Validasi struktur data
            $results = $json['data'] ?? $json['rajaongkir']['results'] ?? [];
            if (empty($results)) {
                Log::warning('[ONGKIR] Data kosong dari API Komerce/RajaOngkir. Menggunakan estimasi ongkir fallback.');
                return response()->json($this->getFallbackShippingOptions($request->courier));
            }

            // Map Komerce structure to what the frontend expects
            $EXCLUDED = ['JTR', 'JTR<130', 'JTR>130', 'JTR>200', 'JTR250', 'LITER'];
            $allCosts = [];
            foreach ($results as $item) {
                $svc = strtoupper($item['service'] ?? '');
                if (in_array($svc, $EXCLUDED)) continue;

                $etdRaw = trim($item['etd'] ?? '');
                $etdNum = preg_replace('/\s*days?\s*/i', '', $etdRaw);

                $allCosts[] = [
                    'service'     => $item['service'],
                    'description' => $item['description'],
                    'cost'        => [
                        [
                            'value' => $item['cost'],
                            'etd'   => $etdNum,
                        ]
                    ]
                ];
            }

            if (empty($allCosts)) {
                foreach ($results as $item) {
                    $etdRaw = trim($item['etd'] ?? '');
                    $etdNum = preg_replace('/\s*days?\s*/i', '', $etdRaw);
                    $allCosts[] = [
                        'service'     => $item['service'],
                        'description' => $item['description'],
                        'cost'        => [['value' => $item['cost'], 'etd' => $etdNum]]
                    ];
                }
            }

            Log::info('[ONGKIR] Sukses — ' . count($allCosts) . ' layanan ditemukan.');
            return response()->json($allCosts);

        } catch (\Exception $e) {
            Log::error('[ONGKIR] Exception ongkir: ' . $e->getMessage() . '. Menggunakan fallback estimasi.');
            return response()->json($this->getFallbackShippingOptions($request->courier));
        }
    }

    private function getFallbackShippingOptions(string $courier): array
    {
        $c = strtolower(trim($courier));
        if ($c === 'jnt') {
            return [
                ['service' => 'EZ', 'description' => 'J&T Express (EZ)', 'cost' => [['value' => 10000, 'etd' => '2-3']]],
                ['service' => 'J&T Super', 'description' => 'J&T Express Super', 'cost' => [['value' => 18000, 'etd' => '1-2']]],
            ];
        } elseif ($c === 'jne') {
            return [
                ['service' => 'REG', 'description' => 'JNE Reguler', 'cost' => [['value' => 10000, 'etd' => '2-3']]],
                ['service' => 'YES', 'description' => 'JNE Yakin Esok Sampai', 'cost' => [['value' => 19000, 'etd' => '1']]],
            ];
        } elseif ($c === 'sicepat') {
            return [
                ['service' => 'REG', 'description' => 'SiCepat Reguler', 'cost' => [['value' => 10000, 'etd' => '2-3']]],
                ['service' => 'BEST', 'description' => 'SiCepat Besok Sampai Tujuan', 'cost' => [['value' => 18000, 'etd' => '1']]],
            ];
        } elseif ($c === 'pos') {
            return [
                ['service' => 'Pos Reguler', 'description' => 'POS Indonesia Reguler', 'cost' => [['value' => 9000, 'etd' => '2-4']]],
                ['service' => 'Pos Nextday', 'description' => 'POS Indonesia Nextday', 'cost' => [['value' => 17000, 'etd' => '1']]],
            ];
        } elseif ($c === 'tiki') {
            return [
                ['service' => 'REG', 'description' => 'TIKI Reguler', 'cost' => [['value' => 10000, 'etd' => '2-3']]],
                ['service' => 'ONS', 'description' => 'TIKI Over Night Service', 'cost' => [['value' => 18000, 'etd' => '1']]],
            ];
        } else {
            return [
                ['service' => 'REG', 'description' => strtoupper($c) . ' Layanan Reguler', 'cost' => [['value' => 10000, 'etd' => '2-3']]],
                ['service' => 'EXP', 'description' => strtoupper($c) . ' Layanan Ekstra Cepat', 'cost' => [['value' => 18000, 'etd' => '1-2']]],
            ];
        }
    }
}
