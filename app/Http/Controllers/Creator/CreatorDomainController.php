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

        // CACHING: Simpan hasil Whois (Cache version 3 agar cache lama langsung di-reset)
        $cacheKey = 'whois_json_v3_' . md5($fullDomainName);
        $result = Cache::remember($cacheKey, 1800, function () use ($fullDomainName, $ext, $price) {
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
            'cached' => true,
            'message' => $result['available'] 
                ? "Selamat! Domain {$fullDomainName} tersedia untuk dibeli."
                : "Maaf, domain {$fullDomainName} sudah terdaftar / tidak tersedia.",
        ]);
    }

    /**
     * Query WhoisJSON API + Multi-DNS & Socket Fallback
     */
    private function queryWhoisJsonApi(string $domain, string $ext, float $price): array
    {
        $apiKey = Setting::get('whoisjson_api_key');

        // 1. Jika API Key WhoisJSON dikonfigurasi di Admin -> Panggil WhoisJSON API terlebih dahulu
        if (!empty($apiKey)) {
            try {
                $response = Http::timeout(6)
                    ->withHeaders(['Authorization' => 'Bearer ' . trim($apiKey)])
                    ->get('https://whoisjson.com/api/v1/whois', [
                        'domain' => $domain,
                        'token'  => trim($apiKey)
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['registered'])) {
                        $isRegistered = (bool)$data['registered'];
                        return [
                            'available' => !$isRegistered,
                            'registered' => $isRegistered,
                        ];
                    }
                    if (isset($data['status']) && is_array($data['status'])) {
                        $statusStr = strtolower(implode(' ', $data['status']));
                        $isRegistered = (str_contains($statusStr, 'active') || str_contains($statusStr, 'registered') || str_contains($statusStr, 'ok'));
                        return [
                            'available' => !$isRegistered,
                            'registered' => $isRegistered,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                Log::error('WhoisJSON API Error: ' . $e->getMessage(), ['domain' => $domain]);
            }
        }

        // 2. High-Precision Multi-DNS & Host Record Fallback Check
        // Jika terdapat record NS, A, MX, SOA, TXT, AAAA, atau CNAME -> Domain PASTI SUDAH TERDAFTAR (TAKEN)
        $isRegistered = $this->checkDnsAvailability($domain);

        return [
            'available' => !$isRegistered,
            'registered' => $isRegistered,
        ];
    }

    /**
     * Helper Multi-Record DNS Check (A, NS, MX, SOA, TXT, CNAME, AAAA)
     */
    private function checkDnsAvailability(string $domain): bool
    {
        $cleanDomain = strtolower(trim($domain));

        // Multi DNS Record Check
        $recordTypes = ['NS', 'A', 'MX', 'SOA', 'TXT', 'CNAME', 'AAAA', 'ANY'];
        foreach ($recordTypes as $type) {
            if (@checkdnsrr($cleanDomain, $type)) {
                return true; // Taken
            }
        }

        // Host IP check
        $ip = @gethostbyname($cleanDomain);
        if (!empty($ip) && $ip !== $cleanDomain && filter_var($ip, FILTER_VALIDATE_IP)) {
            return true; // Taken
        }

        // DNS Details Check
        $records = @dns_get_record($cleanDomain, DNS_ALL);
        if (!empty($records) && is_array($records) && count($records) > 0) {
            return true; // Taken
        }

        return false; // Available
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
