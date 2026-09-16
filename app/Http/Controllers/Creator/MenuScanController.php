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
                'product_type' => ($category === 'Makanan') ? 'makanan' : 'physical',
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
     * Scan & Scrape product details from Marketplace URLs (Tokopedia, Shopee, TikTok Shop).
     */
    public function scanUrl(Request $request)
    {
        try {
            $url = trim($request->input('url', ''));

            if (empty($url)) {
                return response()->json(['success' => false, 'message' => 'URL produk wajib diisi.'], 200);
            }

            $title     = '';
            $desc      = '';
            $images    = [];
            $price     = 0;
            $salePrice = 0;
            $html      = '';

            try {
                $client = new \GuzzleHttp\Client([
                    'timeout'         => 8,
                    'verify'          => false,
                    'allow_redirects' => ['max' => 5],
                    'headers'         => [
                        'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                        'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8',
                        'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    ]
                ]);

                $res  = $client->get($url);
                $html = (string) $res->getBody();
            } catch (\Exception $e) {
                // Ignore HTTP fetch errors
            }

            if (!empty($html)) {
                // OpenGraph title & desc
                if (preg_match('/<meta[^>]*property=["\']og:title["\'][^>]*content=["\'](.*?)["\']/is', $html, $m)) {
                    $title = trim(html_entity_decode(strip_tags($m[1])));
                } elseif (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                    $title = trim(html_entity_decode(strip_tags($m[1])));
                }

                if (preg_match('/<meta[^>]*property=["\']og:description["\'][^>]*content=["\'](.*?)["\']/is', $html, $m)) {
                    $desc = trim(html_entity_decode(strip_tags($m[1])));
                }

                if (preg_match_all('/<meta[^>]*property=["\']og:image["\'][^>]*content=["\'](.*?)["\']/is', $html, $ms)) {
                    foreach ($ms[1] as $imgUrl) {
                        $imgUrl = trim($imgUrl);
                        if ($imgUrl && filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                            $images[] = $imgUrl;
                        }
                    }
                }

                // JSON-LD structured data for products
                if (preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is', $html, $jsonMatches)) {
                    foreach ($jsonMatches[1] as $jsonRaw) {
                        $jsonData = json_decode(trim($jsonRaw), true);
                        if (!$jsonData) continue;
                        $candidates = isset($jsonData['@graph']) ? $jsonData['@graph'] : [$jsonData];
                        foreach ($candidates as $obj) {
                            $type = $obj['@type'] ?? '';
                            if (!in_array($type, ['Product', 'ItemPage', 'WebPage', 'Offer'])) continue;

                            if (!empty($obj['name']) && empty($title)) {
                                $title = trim($obj['name']);
                            }
                            if (!empty($obj['description']) && empty($desc)) {
                                $desc = trim(strip_tags($obj['description']));
                            }
                            $offers = $obj['offers'] ?? ($type === 'Offer' ? $obj : null);
                            if ($offers) {
                                $offerList = isset($offers[0]) ? $offers : [$offers];
                                foreach ($offerList as $off) {
                                    $p = floatval($off['price'] ?? 0);
                                    if ($p > 0 && $price <= 0) {
                                        $price = $p;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Cleanup & Fallbacks
            $cleanTitle = preg_replace('/\s*(\||-|–|—)\s*(TikTok|Tokopedia|Shopee|Bukalapak|Lazada|Blibli|Jual|Beli|Online|Murah|Terlengkap|Buy).*$/i', '', $title);
            $cleanTitle = trim($cleanTitle);

            if (empty($cleanTitle) || mb_strlen($cleanTitle) < 3) {
                $parsedUrl    = parse_url($url);
                $path         = $parsedUrl['path'] ?? '';
                $pathSegments = array_values(array_filter(explode('/', $path)));
                $lastSegment  = end($pathSegments) ?: '';
                $lastSegment  = preg_replace('/-i\.\d+\.\d+$/i', '', $lastSegment);
                $lastSegment  = preg_replace('/-p\d+$/i', '', $lastSegment);
                $lastSegment  = preg_replace('/-\d{5,}$/i', '', $lastSegment);

                $extractedName = ucwords(str_replace(['-', '_'], ' ', urldecode($lastSegment)));
                $extractedName = trim(preg_replace('/\b(Product|Item|Detail|Create|Index|Shop|Toko|Id)\b/i', '', $extractedName));
                $cleanTitle    = (mb_strlen($extractedName) > 2) ? $extractedName : 'Produk Marketplace';
            }

            $slug   = Str::slug($cleanTitle);
            $images = array_values(array_unique($images));
            $images = array_slice($images, 0, 5);

            // Auto-download and compress images to local buyle storage
            $downloadedImages = [];
            foreach ($images as $imgUrl) {
                if (str_starts_with($imgUrl, 'http')) {
                    $dl = \App\Services\ImageDownloader::downloadAndCompress($imgUrl, 'products/gallery');
                    $downloadedImages[] = $dl;
                } else {
                    $downloadedImages[] = $imgUrl;
                }
            }

            $primaryImg = $images[0] ?? null;
            if ($primaryImg && str_starts_with($primaryImg, 'http')) {
                $primaryImg = \App\Services\ImageDownloader::downloadAndCompress($primaryImg, 'products');
            } elseif (empty($primaryImg)) {
                $primaryImg = \App\Models\Product::getPlaceholderUrl();
            }

            if (empty($desc)) {
                $desc = $cleanTitle . ' — Produk berkualitas tinggi. Dapatkan harga terbaik di buyle.id.';
            }

            $productType = $request->input('product_type', 'physical');

            $item = [
                'name'         => $cleanTitle,
                'slug'         => $slug,
                'price'        => $price > 0 ? (int)$price : null,
                'sale_price'   => $salePrice > 0 ? (int)$salePrice : null,
                'description'  => $desc,
                'image'        => $primaryImg,
                'images'       => $downloadedImages,
                'source_url'   => $url,
                'product_type' => $productType,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendeteksi data produk!',
                'items'   => [$item]
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca data dari URL. Silakan periksa URL Anda atau masukkan data secara manual.'
            ], 200);
        }
    }

    /**
     * Dedicated Scan & Scrape for Lynk.id (URL & Source Code HTML).
     */
    public function scanLynk(Request $request)
    {
        try {
            $url = trim($request->input('url', ''));
            if (empty($url)) {
                $url = 'https://lynk.id/imported-product';
            }

            $rawHtml = '';
            if ($request->filled('html_b64')) {
                $rawHtml = base64_decode($request->input('html_b64'));
            } elseif ($request->filled('html')) {
                $r = $request->input('html');
                $d = base64_decode($r, true);
                $rawHtml = ($d !== false && base64_encode($d) === $r) ? $d : $r;
            }

            // If HTML empty, try Guzzle HTTP fetch
            if (empty($rawHtml) && !empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                try {
                    $client = new \GuzzleHttp\Client([
                        'timeout'         => 8,
                        'verify'          => false,
                        'allow_redirects' => ['max' => 5],
                        'headers'         => [
                            'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                            'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8',
                            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                        ]
                    ]);
                    $res = $client->get($url);
                    $rawHtml = (string) $res->getBody();
                } catch (\Exception $e) {
                    // Ignore Guzzle error
                }
            }

            if (empty($rawHtml)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Source code / data Lynk.id tidak ditemukan. Silakan paste Source Code halaman Lynk.id.'
                ], 200);
            }

            // 1. Title Extraction
            $title = '';
            if (preg_match('/<h2[^>]*id=["\']title_product["\'][^>]*>(.*?)<\/h2>/is', $rawHtml, $m)) {
                $title = trim(html_entity_decode(strip_tags($m[1])));
            } elseif (preg_match('/shareMessage\s*=\s*["\']Check out (.*?) from \w+ @/is', $rawHtml, $m)) {
                $title = trim(html_entity_decode(strip_tags($m[1])));
            } elseif (preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\']View [^\']*\'s (.*?) Product details/is', $rawHtml, $m)) {
                $title = trim(html_entity_decode(strip_tags($m[1])));
            } elseif (preg_match('/<title[^>]*>(.*?)<\/title>/is', $rawHtml, $m)) {
                $title = trim(html_entity_decode(strip_tags($m[1])));
                $title = preg_replace('/^LYNK\s*\|\s*/i', '', $title);
            }

            $title = trim(strip_tags($title));
            if (empty($title)) {
                $title = 'Produk Digital Lynk.id';
            }

            // 2. Price Extraction: var p = _g('750000.0') & var sPrice = _g('500000.0')
            $price     = 0;
            $salePrice = 0;
            if (preg_match('/var p\s*=\s*_g\([\'"]([\d.]+)[\'"]\)/i', $rawHtml, $m)) {
                $price = floatval($m[1]);
            }
            if (preg_match('/var sPrice\s*=\s*_g\([\'"]([\d.]+)[\'"]\)/i', $rawHtml, $m)) {
                $salePrice = floatval($m[1]);
            }

            // Swap if salePrice > price
            if ($salePrice > 0 && $price > 0 && $salePrice > $price) {
                [$price, $salePrice] = [$salePrice, $price];
            }
            if ($salePrice > 0 && $salePrice === $price) {
                $salePrice = 0;
            }

            // 3. Description Extraction: <div class="... rich-content ...">
            $desc = '';
            if (preg_match('/<div[^>]*class=["\'][^"\']*rich-content[^"\']*["\'][^>]*>(.*?)<\/div>\s*<\/div>/is', $rawHtml, $m)) {
                $desc = trim(html_entity_decode($m[1]));
            } elseif (preg_match('/<div[^>]*class=["\'][^"\']*rich-content[^"\'][^>]*>(.*?)<\/div>/is', $rawHtml, $m)) {
                $desc = trim(html_entity_decode($m[1]));
            }

            if (!empty($desc)) {
                $desc = strip_tags($desc, '<p><br><b><i><strong><em><ul><ol><li><div><span>');
                $desc = preg_replace('/\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $desc);
                $desc = trim($desc);
            }
            if (empty($desc) || mb_strlen(strip_tags($desc)) < 5) {
                $desc = $title . ' — Produk digital berkualitas tinggi dari Lynk.id.';
            }

            // 4. Image Extraction: https://cdn.lynkid.my.id/products/...
            $images = [];
            if (preg_match_all('/https?:\/\/cdn\.lynkid\.my\.id\/products\/[^\s"\']+/i', $rawHtml, $imgMatches)) {
                foreach ($imgMatches[0] as $img) {
                    $cleanImg = strtok($img, '?');
                    if (filter_var($cleanImg, FILTER_VALIDATE_URL)) {
                        $images[] = $cleanImg;
                    }
                }
            }
            if (empty($images)) {
                if (preg_match_all('/<meta[^>]*property=["\']og:image["\'][^>]*content=["\'](.*?)["\']/is', $rawHtml, $ms)) {
                    foreach ($ms[1] as $img) {
                        $img = trim($img);
                        if ($img && filter_var($img, FILTER_VALIDATE_URL)) {
                            $images[] = $img;
                        }
                    }
                }
            }

            $images = array_values(array_unique($images));
            $images = array_slice($images, 0, 5);

            // Auto-download and compress images to local buyle storage
            $downloadedImages = [];
            foreach ($images as $imgUrl) {
                if (str_starts_with($imgUrl, 'http')) {
                    $dl = \App\Services\ImageDownloader::downloadAndCompress($imgUrl, 'products/gallery');
                    $downloadedImages[] = $dl;
                } else {
                    $downloadedImages[] = $imgUrl;
                }
            }

            $primaryImg = $images[0] ?? null;
            if ($primaryImg && str_starts_with($primaryImg, 'http')) {
                $primaryImg = \App\Services\ImageDownloader::downloadAndCompress($primaryImg, 'products');
            } elseif (empty($primaryImg)) {
                $primaryImg = \App\Models\Product::getPlaceholderUrl();
            }

            $slug = Str::slug($title);

            $item = [
                'name'         => $title,
                'slug'         => $slug,
                'price'        => $price > 0 ? (int)$price : null,
                'sale_price'   => $salePrice > 0 ? (int)$salePrice : null,
                'description'  => $desc,
                'image'        => $primaryImg,
                'images'       => $downloadedImages,
                'source_url'   => $url,
                'product_type' => 'external_link',
            ];

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendeteksi data produk Lynk.id!',
                'items'   => [$item]
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membaca data Lynk.id. Silakan periksa kembali data yang dimasukkan.'
            ], 200);
        }
    }
}
