<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\DomainOrder;
use App\Models\Setting;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreatorDomainController extends Controller
{
    public static array $PRICES = [
        'com'    => 436666,
        'id'     => 480719,
        'co.id'  => 532889,
        'biz'    => 667546,
        'biz.id' => 177589,
        'store'  => 1065836,
        'my.id'  => 333189,
    ];

    public static function getPrices(): array
    {
        $custom = Setting::get('domain_prices');
        if ($custom) {
            $decoded = is_string($custom) ? json_decode($custom, true) : $custom;
            if (is_array($decoded) && !empty($decoded)) {
                $prices = self::$PRICES;
                foreach ($decoded as $k => $v) {
                    if (is_numeric($v) && $v > 0) {
                        $prices[strtolower($k)] = (float)$v;
                    }
                }
                return $prices;
            }
        }
        return self::$PRICES;
    }

    /**
     * Cek Ketersediaan Domain via WhoisJSON dengan Caching (24 jam)
     */
    public function checkAvailability(Request $request)
    {
        $rawDomain = trim($request->input('domain', ''));

        if (empty($rawDomain)) {
            return response()->json([
                'success' => false,
                'message' => 'Masukkan nama domain yang ingin dicari.'
            ], 422);
        }

        // Clean domain
        $cleanDomain = strtolower($rawDomain);
        $cleanDomain = preg_replace('#^https?://#i', '', $cleanDomain);
        $cleanDomain = preg_replace('/^www\./i', '', $cleanDomain);
        $cleanDomain = rtrim($cleanDomain, '/');
        $cleanDomain = explode('/', $cleanDomain)[0];

        $prices = self::getPrices();

        // Determine extension
        $ext = $this->extractExtension($cleanDomain);
        $keyword = preg_replace('/' . preg_quote('.' . $ext, '/') . '$/i', '', $cleanDomain);
        $keyword = preg_replace('/[^a-z0-9\-]/i', '', $keyword);

        if (empty($ext) || !array_key_exists($ext, $prices)) {
            // Extension unsupported -> Suggest supported extensions
            $recommendations = $this->generateRecommendations($keyword);
            return response()->json([
                'success' => false,
                'unsupported_ext' => true,
                'message' => "Ekstensi '." . ($ext ?: 'domain') . "' tidak didukung. Pilihan ekstensi resmi: ." . implode(', .', array_keys($prices)),
                'recommendations' => $recommendations
            ], 422);
        }

        $fullDomainName = $keyword . '.' . $ext;
        $price = $prices[$ext];

        // CACHING: Simpan hasil Whois selama 24 jam agar 1 request tidak diulang-ulang
        $cacheKey = 'whois_json_v1_' . md5($fullDomainName);
        $result = Cache::remember($cacheKey, 86400, function () use ($fullDomainName, $ext, $price) {
            return $this->queryWhoisJsonApi($fullDomainName, $ext, $price);
        });

        return response()->json([
            'success' => true,
            'domain' => $fullDomainName,
            'extension' => $ext,
            'price' => $price,
            'formatted_price' => 'Rp ' . number_format($price, 0, ',', '.'),
            'available' => $result['available'],
            'registered' => $result['registered'],
            'cached' => $result['cached'] ?? false,
            'message' => $result['available'] 
                ? "Selamat! Domain {$fullDomainName} tersedia untuk dibeli."
                : "Maaf, domain {$fullDomainName} sudah terdaftar / tidak tersedia.",
        ]);
    }

    /**
     * Query WhoisJSON API
     */
    private function queryWhoisJsonApi(string $domain, string $ext, float $price): array
    {
        $apiKey = Setting::get('whoisjson_api_key');

        // Fallback jika API key belum diisi admin -> Cek DNS Host / Gethostbyname fallback
        if (empty($apiKey)) {
            $resolved = @gethostbyname($domain);
            $isAvailable = ($resolved === $domain || empty($resolved));
            return [
                'available' => $isAvailable,
                'registered' => !$isAvailable,
                'cached' => false,
            ];
        }

        try {
            // WhoisJSON API request
            $response = Http::timeout(6)->get('https://whoisjson.com/api/v1/whois', [
                'domain' => $domain,
                'token'  => $apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // WhoisJSON returns 'registered' boolean in JSON response
                // If registered === false or 'name' is empty/not registered -> Available
                $isRegistered = false;
                if (isset($data['registered'])) {
                    $isRegistered = (bool)$data['registered'];
                } elseif (isset($data['status']) && is_array($data['status'])) {
                    $statusStr = strtolower(implode(' ', $data['status']));
                    if (str_contains($statusStr, 'active') || str_contains($statusStr, 'registered') || str_contains($statusStr, 'ok')) {
                        $isRegistered = true;
                    }
                } elseif (isset($data['name']) && !empty($data['name']) && isset($data['registrar'])) {
                    $isRegistered = true;
                }

                return [
                    'available' => !$isRegistered,
                    'registered' => $isRegistered,
                    'cached' => false,
                ];
            }
        } catch (\Throwable $e) {
            Log::error('WhoisJSON API Error: ' . $e->getMessage(), ['domain' => $domain]);
        }

        // Fallback jika API sedang limit/error -> Cek DNS resolver
        $resolved = @gethostbyname($domain);
        $isAvailable = ($resolved === $domain || empty($resolved));
        return [
            'available' => $isAvailable,
            'registered' => !$isAvailable,
            'cached' => false,
        ];
    }

    /**
     * Process Domain Order & Generate Midtrans Snap Token
     */
    public function checkout(Request $request)
    {
        $user = auth()->user();
        $rawDomain = trim($request->input('domain', ''));

        if (empty($rawDomain)) {
            return response()->json(['success' => false, 'message' => 'Domain tidak boleh kosong.'], 422);
        }

        $cleanDomain = strtolower($rawDomain);
        $cleanDomain = preg_replace('#^https?://#i', '', $cleanDomain);
        $cleanDomain = preg_replace('/^www\./i', '', $cleanDomain);
        $cleanDomain = rtrim($cleanDomain, '/');
        $cleanDomain = explode('/', $cleanDomain)[0];

        $prices = self::getPrices();
        $ext = $this->extractExtension($cleanDomain);
        if (empty($ext) || !array_key_exists($ext, $prices)) {
            return response()->json(['success' => false, 'message' => 'Ekstensi domain tidak didukung.'], 422);
        }

        $basePrice = (float) $prices[$ext];

        // Calculating Admin Fee, Platform Fee, & PPN Tax 11% using Admin setting path
        $platformFeeRate = (float) Setting::get('platform_fee_rate', 5);
        $adminFeeRate    = (float) Setting::get('admin_fee_rate', 5);
        $taxRate         = (float) Setting::get('tax_rate', 11);

        $platformFee = round($basePrice * ($platformFeeRate / 100));
        $adminFee    = round($basePrice * ($adminFeeRate / 100));
        $taxAmount   = round($basePrice * ($taxRate / 100));

        $totalAmount = $basePrice + $platformFee + $adminFee + $taxAmount;

        // Buat record DomainOrder pending
        $domainOrder = DomainOrder::create([
            'user_id'      => $user->id,
            'domain_name'  => $cleanDomain,
            'extension'    => $ext,
            'base_amount'  => $basePrice,
            'platform_fee' => $platformFee,
            'admin_fee'    => $adminFee,
            'tax_amount'   => $taxAmount,
            'amount'       => $totalAmount,
            'status'       => 'pending',
        ]);

        // Generate Midtrans Snap Token
        try {
            $midtransService = new MidtransService();
            $snapToken = $midtransService->createDomainSnapToken($domainOrder, $user);

            if (!$snapToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses Token Pembayaran Midtrans. Pastikan Server Key Midtrans di Pengaturan Admin sudah dikonfigurasi dengan benar.'
                ], 422);
            }

            $domainOrder->snap_token = $snapToken;
            $domainOrder->save();

            return response()->json([
                'success' => true,
                'order_id' => $domainOrder->id,
                'snap_token' => $snapToken,
                'domain' => $cleanDomain,
                'base_price' => $basePrice,
                'platform_fee' => $platformFee,
                'admin_fee' => $adminFee,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'formatted_price' => 'Rp ' . number_format($totalAmount, 0, ',', '.')
            ]);
        } catch (\Throwable $e) {
            Log::error('Midtrans Domain Snap Error: ' . $e->getMessage(), ['domain' => $cleanDomain]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungkan ke Gateway Pembayaran Midtrans: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extract extension from domain (support multi-level like co.id, biz.id, my.id)
     */
    private function extractExtension(string $domain): string
    {
        $parts = explode('.', $domain);
        if (count($parts) < 2) return '';

        $prices = self::getPrices();

        $sub = implode('.', array_slice($parts, -2));
        if (array_key_exists($sub, $prices)) {
            return $sub;
        }

        $single = end($parts);
        if (array_key_exists($single, $prices)) {
            return $single;
        }

        return $single;
    }

    /**
     * Generate fallback domain recommendations
     */
    private function generateRecommendations(string $keyword): array
    {
        $recommendations = [];
        $prices = self::getPrices();
        foreach ($prices as $ext => $price) {
            $recommendations[] = [
                'domain' => $keyword . '.' . $ext,
                'extension' => $ext,
                'price' => $price,
                'formatted_price' => 'Rp ' . number_format($price, 0, ',', '.')
            ];
        }
        return $recommendations;
    }
}
