<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('awb') && $request->filled('courier')) {
            return $this->track($request);
        }
        $settings = Setting::getAllAsArray();
        return view('home.cek-resi', compact('settings'));
    }

    public function track(Request $request)
    {
        $request->validate(
            ['awb'=>'required|string|max:100','courier'=>'required|string|max:50'],
            ['awb.required'=>'Nomor resi wajib diisi.','courier.required'=>'Kurir wajib dipilih.']
        );
        $settings = Setting::getAllAsArray();
        $awb      = trim($request->awb);
        $courier  = strtolower(trim($request->courier));

        // Map common codes to Komerce codes
        $courierMap = [
            'idx'   => 'ide',
            'ninja' => 'ninjaxpress',
        ];
        $mappedCourier = $courierMap[$courier] ?? $courier;

        // Gunakan mode dari settings (sandbox vs live)
        $mode   = $settings['komerce_mode'] ?? 'sandbox';
        $isLive = ($mode === 'live');

        if ($isLive) {
            $apiKey  = $settings['shipping_delivery_api_key']
                    ?? $settings['rajaongkir_api_key']
                    ?? null;
            $baseUrl = 'https://api.collaborator.komerce.id';
        } else {
            // Sandbox: rajaongkir.komerce.id (arahan CS Komerce 15/08/2026)
            $apiKey  = $settings['shipping_delivery_api_key_sandbox']
                    ?? $settings['rajaongkir_api_key_sandbox']
                    ?? null;
            $baseUrl = 'https://rajaongkir.komerce.id';
        }
        if (empty($apiKey)) {
            return view('home.cek-resi', [
                'settings' => $settings,
                'awb'      => $awb,
                'courier'  => $courier,
                'error'    => 'API key belum dikonfigurasi. Hubungi administrator.',
            ]);
        }

        try {
            Log::info('[RESI] Tracking request', [
                'mode'    => $mode,
                'baseUrl' => $baseUrl,
                'awb'     => $awb,
                'courier' => $mappedCourier,
            ]);

            if ($isLive) {
                // Live: Collaborator API
                $url = $baseUrl . '/order/api/v1/orders/history-airway-bill';
                $response = Http::withoutVerifying()
                    ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                    ->timeout(20)
                    ->withHeaders([
                        'x-api-key'  => $apiKey,
                        'Accept'     => 'application/json',
                        'User-Agent' => 'Mozilla/5.0',
                    ])
                    ->get($url, [
                        'awb'     => $awb,
                        'courier' => $mappedCourier,
                    ]);
            } else {
                // Sandbox: Legacy RajaOngkir Komerce Proxy
                $url = $baseUrl . '/api/v1/track/waybill' 
                     . '?awb=' . urlencode($awb) 
                     . '&courier=' . urlencode($mappedCourier);
                
                $response = Http::withoutVerifying()
                    ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                    ->timeout(20)
                    ->withHeaders([
                        'key'           => $apiKey,
                        'Content-Type'  => 'application/x-www-form-urlencoded',
                        'User-Agent'    => 'Mozilla/5.0',
                    ])
                    ->post($url);
            }

            $json = $response->json();
            Log::info('[RESI] Raw API response', [
                'status'  => $response->status(),
                'body'    => $json,
                'awb'     => $awb,
                'courier' => $mappedCourier,
            ]);

            // -------------------------------------------------------
            // Parse response - Komerce Delivery API structure
            // -------------------------------------------------------
            $meta = $json['meta'] ?? [];
            $data = $json['data'] ?? null;

            if (!$response->successful() || !$data) {
                $msg = $meta['message'] ?? $json['message'] ?? 'Resi tidak ditemukan atau kurir tidak mendukung pelacakan.';
                Log::warning('[RESI] API error', ['status' => $response->status(), 'meta' => $meta, 'awb' => $awb]);
                return view('home.cek-resi', compact('settings','awb','courier') + ['error' => $msg]);
            }

            // Support both flat and nested response formats
            $summary  = $data['summary']        ?? $data;
            $details  = $data['details']         ?? $data;
            $manifest = $data['manifest']        ?? $data['history'] ?? [];
            $delivery = $data['delivery_status'] ?? [];

            // Normalize status
            $st    = strtolower($delivery['status'] ?? $summary['status'] ?? '');
            $label = match(true) {
                str_contains($st, 'delivered')  => 'TERKIRIM',
                str_contains($st, 'transit')    => 'DALAM PERJALANAN',
                str_contains($st, 'pickup')     => 'PICKUP',
                str_contains($st, 'on process') => 'DIPROSES',
                str_contains($st, 'return')     => 'DIKEMBALIKAN',
                str_contains($st, 'out for')    => 'DALAM PENGIRIMAN',
                default                         => strtoupper($delivery['status'] ?? $summary['status'] ?? 'TIDAK DIKETAHUI'),
            };

            $tracking = [
                'summary' => [
                    'awb'     => $awb,
                    'courier' => strtoupper($summary['courier_name'] ?? $courier),
                    'service' => $summary['service_code'] ?? $summary['service'] ?? '-',
                    'status'  => $label,
                ],
                'detail' => [
                    'shipper'     => $details['shipper_name']  ?? $summary['shipper_name']  ?? '-',
                    'origin'      => $details['origin']        ?? $summary['origin']         ?? '-',
                    'receiver'    => $details['receiver_name'] ?? $summary['receiver_name']  ?? '-',
                    'destination' => $details['destination']   ?? $summary['destination']    ?? '-',
                    'weight'      => isset($details['weight']) ? $details['weight'] . ' gr' : '-',
                ],
                'history' => array_map(function ($m) {
                    $rawDesc = trim($m['manifest_description'] ?? $m['description'] ?? '');
                    $title   = trim($m['title'] ?? '');

                    $titleMap = [
                        'Pickup'         => 'Paket Diambil oleh Kurir',
                        'Delivered'      => 'Paket Diterima di Titik Pengumpulan',
                        'Transit Center' => 'Paket di Transit Center',
                        'On Delivery'    => 'Paket Dalam Pengiriman ke Penerima',
                        'Received'       => 'Paket Diterima oleh Penerima',
                        'Return'         => 'Paket Dikembalikan',
                    ];

                    $isGeneric = strlen($rawDesc) <= 10 ||
                                 in_array(strtolower($rawDesc), ['manifes', 'manifest', 'pickup', 'transit', '-', '']);

                    if ($isGeneric && $title) {
                        $desc = $titleMap[$title] ?? $title;
                    } else {
                        $desc = $rawDesc ?: ($titleMap[$title] ?? $title ?: '-');
                    }

                    return [
                        'date'     => ($m['manifest_date'] ?? $m['date'] ?? '') . ' ' . ($m['manifest_time'] ?? $m['time'] ?? ''),
                        'desc'     => $desc,
                        'location' => $m['city_name'] ?? $m['location'] ?? '',
                        'title'    => $title,
                    ];
                }, $manifest),
            ];

            Log::info('[RESI] Tracking sukses', ['awb' => $awb, 'courier' => $courier, 'status' => $label]);
            return view('home.cek-resi', compact('settings','tracking','awb','courier'));

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[RESI] Connection error: ' . $e->getMessage());
            return view('home.cek-resi', compact('settings', 'awb', 'courier') + [
                'error' => 'Gagal terhubung ke server ekspedisi. Pastikan koneksi internet aktif dan coba lagi.',
            ]);
        } catch (\Throwable $e) {
            Log::error('[RESI] Error: ' . $e->getMessage());
            return view('home.cek-resi', compact('settings','awb','courier') + ['error' => 'Terjadi kesalahan. Coba lagi nanti.']);
        }
    }
}