<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorBioBlock;
use App\Models\CreatorProfile;
use App\Services\AiVisionService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuScanController extends Controller
{
    public function scan(Request $request, AiVisionService $aiService)
    {
        $user = auth()->user();
        $profile = CreatorProfile::getOrCreateForUser($user);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profil Toko belum dikonfigurasi.'
            ], 403);
        }

        if (!$aiService->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Fitur Scan Menu AI sedang tidak diaktifkan oleh admin.'
            ], 403);
        }

        // Check Cooldown
        $cooldownDays = $aiService->getCooldownDays();
        if ($cooldownDays > 0 && $profile->last_menu_scan_at) {
            $nextAllowed = $profile->last_menu_scan_at->addDays($cooldownDays);
            if (now()->lt($nextAllowed)) {
                $daysLeft = ceil(now()->diffInHours($nextAllowed) / 24);
                return response()->json([
                    'success' => false,
                    'message' => "Kuota scan menu minggu/periode ini sudah terpakai. Mohon tunggu {$daysLeft} hari lagi atau hubungi admin."
                ], 429);
            }
        }

        // Check Monthly Limit
        $monthlyLimit = $aiService->getMonthlyLimit();
        if ($profile->scan_count_reset_at === null || now()->greaterThanOrEqualTo($profile->scan_count_reset_at)) {
            // Reset count for new month
            $profile->monthly_scan_count = 0;
            $profile->scan_count_reset_at = now()->addMonth();
            $profile->save();
        }

        if ($profile->monthly_scan_count >= $monthlyLimit) {
            return response()->json([
                'success' => false,
                'message' => "Batas maksimal scan menu bulan ini ({$monthlyLimit}x) sudah tercapai."
            ], 429);
        }

        $request->validate([
            'menu_image' => 'required|image|max:10240',
        ]);

        $file = $request->file('menu_image');
        $tempPath = $file->getRealPath();

        try {
            $items = $aiService->scanMenuImage($tempPath);

            // Update user's scan usage
            $profile->last_menu_scan_at = now();
            $profile->monthly_scan_count += 1;
            $profile->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendeteksi ' . count($items) . ' menu dari foto!',
                'items'   => $items,
                'quota_info' => [
                    'used_monthly' => $profile->monthly_scan_count,
                    'monthly_limit' => $monthlyLimit,
                    'cooldown_days' => $cooldownDays
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk import scanned menu items as Produk Fisik (custom_product) bio blocks.
     * All items auto-set to checkout via Buyle (payment_method = 'web').
     */
    public function bulkImport(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.price' => 'nullable|numeric|min:0',
            'items.*.description' => 'nullable|string',
            'items.*.category' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();
        $profile = CreatorProfile::getOrCreateForUser($user);
        $importedCount = 0;

        // Get the current max order for proper ordering (oldest = lowest order = first)
        $maxOrder = CreatorBioBlock::where('creator_id', $profile->id)->max('order');
        $lastOrder = ($maxOrder !== null && $maxOrder !== false) ? (int)$maxOrder : 0;

        foreach ($request->input('items') as $item) {
            $name = trim($item['name']);
            if (empty($name)) continue;

            $price = (int) ($item['price'] ?? 0);
            $desc  = trim($item['description'] ?? '');
            $catRaw = trim($item['category'] ?? 'Makanan');
            $category = match (strtolower($catRaw)) {
                'barang' => 'Barang',
                'jasa'   => 'Jasa',
                'lainnya' => 'Lainnya',
                default  => 'Makanan',
            };

            $stockVal = $item['stock'] ?? null;
            $stock = ($stockVal === '' || $stockVal === null || $stockVal === 'unlimited') ? null : (int)$stockVal;

            // Smart unique slug logic to prevent 1062 Duplicate entry SQL errors
            $cleanTitle = Str::limit($name, 40, '');
            $baseSlug   = rtrim(Str::slug($cleanTitle), '-') ?: 'produk';
            $slug       = $baseSlug;
            $attempt    = 1;
            while (\App\Models\Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . Str::random(4);
                $attempt++;
                if ($attempt > 10) {
                    $slug = $baseSlug . '-' . time() . '-' . rand(100, 999);
                    break;
                }
            }

            $lastOrder++;

            // Create Product entry for Buyle Payment Gateway checkout
            $product = \App\Models\Product::create([
                'seller_id'    => $user->id,
                'name'         => $name,
                'slug'         => $slug,
                'price'        => $price,
                'stock'        => $stock,
                'description'  => $desc,
                'is_active'    => true,
                'product_type' => 'physical',
            ]);

            CreatorBioBlock::create([
                'creator_id' => $profile->id,
                'type'       => 'custom_product',
                'title'      => $name,
                'url'        => null,
                'data_json'  => [
                    'price'          => $price,
                    'category'       => $category,
                    'stock'          => $stock,
                    'payment_method' => 'web', // Otomatis checkout via Buyle
                    'description'    => $desc,
                    'slug'           => $slug,
                    'product_id'     => $product->id,
                ],
                'order'      => $lastOrder,
                'is_active'  => true,
            ]);

            $importedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil mengimpor {$importedCount} produk fisik ke Link Bio Anda! (Checkout via Buyle)",
            'imported_count' => $importedCount
        ]);
    }

    /**
     * Scan & Scrape product details from URL (Website, TikTok Shop, Tokopedia, Shopee).
     */
    /**
     * Scan & Scrape product details from URL (Website, TikTok Shop, Tokopedia, Shopee).
     */
    public function scanUrl(Request $request)
    {
        $url = trim($request->input('url', ''));
        $source = $request->input('source', 'url');

        if (empty($url)) {
            return response()->json([
                'success' => false,
                'message' => 'URL produk wajib diisi.'
            ], 422);
        }

        $title = '';
        $desc  = '';
        $image = null;
        $price = 0;

        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 6,
                'verify' => false,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                    'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                ]
            ]);

            $res = $client->get($url);
            $html = (string) $res->getBody();

            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                $title = trim(html_entity_decode(strip_tags($m[1])));
            }
            if (preg_match('/<meta[^>]*property=["\']og:title["\'][^>]*content=["\'](.*?)["\']/is', $html, $m)) {
                $title = trim(html_entity_decode($m[1]));
            }

            if (preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\'](.*?)["\']/is', $html, $m)) {
                $desc = trim(html_entity_decode($m[1]));
            } elseif (preg_match('/<meta[^>]*property=["\']og:description["\'][^>]*content=["\'](.*?)["\']/is', $html, $m)) {
                $desc = trim(html_entity_decode($m[1]));
            }

            if (preg_match('/<meta[^>]*property=["\']og:image["\'][^>]*content=["\'](.*?)["\']/is', $html, $m)) {
                $image = trim($m[1]);
            }

            // Extract price if found in text or JSON-LD
            if (preg_match('/"price":\s*"?([\d\.]+)"?/i', $html, $m)) {
                $price = (float) $m[1];
            } elseif (preg_match('/(?:Rp|IDR)\s*([\d\.\,]+)/i', $html, $m)) {
                $rawP = preg_replace('/[^\d]/', '', $m[1]);
                if (is_numeric($rawP) && (float)$rawP > 100) {
                    $price = (float)$rawP;
                }
            }
        } catch (\Exception $e) {
            // Ignore HTTP fetch error and rely on URL parsing below
        }

        // Clean up title
        $cleanTitle = preg_replace('/(\||-|–|Buy|TikTok|Tokopedia|Shopee|Jual|Beli|Online|Murah|Terlengkap).*$/i', '', $title);
        $cleanTitle = trim($cleanTitle);

        // Fallback: Smart URL slug extraction
        if (empty($cleanTitle) || strlen($cleanTitle) < 3 || in_array(strtolower($cleanTitle), ['create', 'product', 'item', 'index'])) {
            $parsedUrl = parse_url($url);
            $path = $parsedUrl['path'] ?? '';

            // Clean Tokopedia/Shopee/TikTok slug formats like /toko/nama-produk-i.12345 or /product/nama-produk-12345
            $pathSegments = array_values(array_filter(explode('/', $path)));
            $lastSegment = end($pathSegments) ?: '';

            if (in_array(strtolower($lastSegment), ['create', 'products', 'item', 'product', 'detail']) && count($pathSegments) > 1) {
                $lastSegment = $pathSegments[count($pathSegments) - 2];
            }

            // Strip IDs like i.123.456, p123456, or trailing numeric IDs
            $lastSegment = preg_replace('/-i\.\d+\.\d+$/i', '', $lastSegment);
            $lastSegment = preg_replace('/-p\d+$/i', '', $lastSegment);
            $lastSegment = preg_replace('/-\d{5,}$/i', '', $lastSegment);

            $extractedName = ucwords(str_replace(['-', '_'], ' ', $lastSegment));
            $extractedName = trim(preg_replace('/\b(Product|Item|Detail|Create|Index|Shop|Toko)\b/i', '', $extractedName));

            if (!empty($extractedName) && strlen($extractedName) > 2) {
                $cleanTitle = $extractedName;
            } else {
                $cleanTitle = 'Produk Impor Baru';
            }
        }

        $item = [
            'name'        => $cleanTitle,
            'price'       => $price > 0 ? $price : null,
            'description' => $desc ?: ('Produk diimpor dari URL: ' . $url),
            'image'       => $image,
            'source_url'  => $url,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendeteksi data produk!',
            'items'   => [$item]
        ]);
    }
}
