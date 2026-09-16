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
    /**
     * Scan & Scrape product details from Marketplace URLs (Tokopedia, Shopee, TikTok Shop, etc.).
     */
    public function scanUrl(Request $request)
    {
        try {
            $url = trim($request->input('url', ''));

            if (empty($url)) {
                return response()->json(['success' => false, 'message' => 'URL produk wajib diisi.'], 200);
            }

            if (!preg_match('#^https?://#i', $url)) {
                $url = 'https://' . $url;
            }

            $url  = preg_replace('/[\x00-\x1F\x7F]/', '', $url);
            $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

            $isShopee    = str_contains($host, 'shopee') || str_contains($host, 'shp.ee');
            $isTokopedia = str_contains($host, 'tokopedia') || str_contains($host, 'tokope.dia');
            $isTiktok    = str_contains($host, 'tiktok') || str_contains($host, 'vt.tiktok') || str_contains($host, 'vm.tiktok');

            $title     = '';
            $desc      = '';
            $images    = [];
            $price     = 0;
            $origPrice = 0;
            $html      = '';

            // 1. Primary HTTP Fetch using Facebook Bot / Browser UA
            $ua = ($isShopee || $isTiktok)
                ? 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)'
                : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36';

            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'User-Agent'      => $ua,
                    'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                ])->timeout(15)->withOptions(['allow_redirects' => true])->get($url);

                $html = $response->body();
            } catch (\Throwable $e) {
                $html = '';
            }

            // Fallback Stage 2 for Shopee / TikTok if empty: try WhatsApp UA
            if (($isShopee || $isTiktok) && (!$html || strlen($html) < 500)) {
                try {
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'User-Agent' => 'WhatsApp/2.23.20.0 i',
                    ])->timeout(15)->withOptions(['allow_redirects' => true])->get($url);
                    $html = $response->body();
                } catch (\Throwable $e) {}
            }

            // Helper: Extract Meta Tags (robust attribute order & property vs name)
            $getMeta = function (string $prop) use (&$html): string {
                foreach (['property="' . $prop . '"', 'property=\'' . $prop . '\'', 'name="' . $prop . '"', 'name=\'' . $prop . '\''] as $attr) {
                    if (preg_match('/<meta[^>]+' . preg_quote($attr, '/') . '[^>]+content=["\']([^"\']+)["\'][^>]*>/i', $html, $m) ||
                        preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+' . preg_quote($attr, '/') . '[^>]*>/i', $html, $m)) {
                        return trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                    }
                }
                return '';
            };

            $addImg = function (string $u) use (&$images) {
                $u = trim($u);
                if ($u && filter_var($u, FILTER_VALIDATE_URL) && !in_array($u, $images) && count($images) < 6) {
                    $images[] = $u;
                }
            };

            if (!empty($html)) {
                $title = $getMeta('og:title') ?: $getMeta('title');
                if (!$title && preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                    $title = trim(html_entity_decode(strip_tags($m[1])));
                }
                $desc = $getMeta('og:description') ?: $getMeta('description');

                $priceRaw = $getMeta('product:price:amount');
                if ($priceRaw) {
                    $price = (float) preg_replace('/[^0-9]/', '', $priceRaw);
                }

                $rawOrig = $getMeta('product:price:standart_amount')
                        ?: $getMeta('og:price_before_discount')
                        ?: $getMeta('og:original-price')
                        ?: $getMeta('product:original_price:amount');
                if ($rawOrig) {
                    $origPrice = (float) preg_replace('/[^0-9]/', '', $rawOrig);
                }

                // Collect OpenGraph & Twitter Images
                $ogImg = $getMeta('og:image') ?: $getMeta('og:image:secure_url') ?: $getMeta('twitter:image');
                if ($ogImg) $addImg($ogImg);

                for ($i = 1; $i <= 6 && count($images) < 6; $i++) {
                    $alt = $getMeta("og:image:alt:$i") ?: $getMeta("og:image:$i") ?: $getMeta("product:image:$i");
                    if ($alt) $addImg($alt);
                }

                // JSON-LD structured data for products
                if (preg_match_all('/<script[^>]+type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is', $html, $jsonMatches)) {
                    foreach ($jsonMatches[1] as $jsonStr) {
                        $ld = @json_decode(trim($jsonStr), true);
                        if (!is_array($ld)) continue;
                        $candidates = isset($ld['@graph']) ? $ld['@graph'] : [$ld];
                        foreach ($candidates as $obj) {
                            $type = $obj['@type'] ?? '';
                            if (!in_array($type, ['Product', 'ItemPage', 'WebPage', 'Offer', 'BreadcrumbList'])) continue;

                            if ($type === 'BreadcrumbList' && !empty($obj['itemListElement'])) {
                                $lastItem = end($obj['itemListElement']);
                                if (!empty($lastItem['item']['name']) && strlen($lastItem['item']['name']) > 5) {
                                    $title = $title ?: $lastItem['item']['name'];
                                }
                            }

                            if ($type === 'Product' || $type === 'ItemPage') {
                                if (!empty($obj['name']) && empty($title)) {
                                    $title = trim($obj['name']);
                                }
                                if (!empty($obj['description']) && empty($desc)) {
                                    $desc = trim(strip_tags($obj['description']));
                                }
                                if (!empty($obj['image'])) {
                                    $ldImgs = is_array($obj['image']) ? $obj['image'] : [$obj['image']];
                                    foreach ($ldImgs as $ldImg) {
                                        $src = is_array($ldImg) ? ($ldImg['url'] ?? '') : $ldImg;
                                        $addImg((string)$src);
                                    }
                                }

                                $offers = $obj['offers'] ?? ($type === 'Offer' ? $obj : null);
                                if ($offers) {
                                    $offerList = isset($offers[0]) ? $offers : [$offers];
                                    foreach ($offerList as $off) {
                                        $p = floatval(preg_replace('/[^0-9.]/', '', (string)($off['price'] ?? 0)));
                                        if ($p > 0 && $price <= 0) {
                                            $price = $p;
                                        }
                                        if (isset($off['highPrice'])) {
                                            $hp = floatval(preg_replace('/[^0-9.]/', '', (string)$off['highPrice']));
                                            if ($hp > 0 && $origPrice <= 0) $origPrice = $hp;
                                        }
                                        if (isset($off['originalPrice'])) {
                                            $op = floatval(preg_replace('/[^0-9.]/', '', (string)$off['originalPrice']));
                                            if ($op > 0 && $origPrice <= 0) $origPrice = $op;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // Struck price / del tag regex fallback
                if (!$origPrice) {
                    if (preg_match('/(?:original.?price|harga.?normal|harga.?coret|strike)[^>]*>(?:[^<]*Rp\s*)?([0-9][0-9.,]{2,})/i', $html, $m)) {
                        $candidate = (float) preg_replace('/[^0-9]/', '', $m[1]);
                        if ($candidate > $price) $origPrice = $candidate;
                    }
                    if (!$origPrice && preg_match_all('/<(?:del|s)[^>]*>(?:[^<]*?Rp\s*)?([0-9][0-9.,]{2,})<\/(?:del|s)>/i', $html, $m2)) {
                        foreach ($m2[1] as $rawP) {
                            $candidate = (float) preg_replace('/[^0-9]/', '', $rawP);
                            if ($candidate > $price) { $origPrice = $candidate; break; }
                        }
                    }
                }

                // In-page <img> tags regex fallback for extra product gallery images
                if (count($images) < 6) {
                    $imgPatterns = [
                        '/data-src=["\']((https?:\/\/[^"\']+\.(?:jpg|jpeg|png|webp))[^"\']*)["\']/',
                        '/src=["\']((https?:\/\/[^"\']+\.(?:jpg|jpeg|png|webp))[^"\']*)["\']/',
                    ];
                    foreach ($imgPatterns as $pat) {
                        if (preg_match_all($pat, $html, $imgMatches)) {
                            foreach ($imgMatches[1] as $src) {
                                if (preg_match('/[?&]w=[1-9][0-9]?(?:&|$)/', $src)) continue;
                                if (str_contains($src, 'icon') || str_contains($src, 'logo')) continue;
                                $addImg($src);
                                if (count($images) >= 6) break;
                            }
                        }
                        if (count($images) >= 6) break;
                    }
                }
            }

            // 2. Microlink Fallback if title OR images are missing
            if (empty($title) || empty($images)) {
                try {
                    $ml = \Illuminate\Support\Facades\Http::timeout(12)->get('https://api.microlink.io', [
                        'url'  => $url,
                        'meta' => 'true',
                    ]);
                    if ($ml->successful()) {
                        $d = $ml->json('data', []);
                        $title = $title ?: ($d['title'] ?? '');
                        $desc  = $desc  ?: ($d['description'] ?? '');
                        $mlImg = $d['image']['url'] ?? $d['logo']['url'] ?? '';
                        if ($mlImg) $addImg($mlImg);
                    }
                } catch (\Throwable $e) {}
            }

            // 3. Platform-specific cleaning & Title Fallbacks
            if ($isShopee) {
                $title = preg_replace('/^Jual\s+/i', '', $title);
                $title = preg_replace('/\s*[-|]\s*(Shopee|Shopee Indonesia).*$/i', '', $title);
                if (str_contains($desc, 'Beli ') && str_contains($desc, 'di Shopee')) {
                    $desc = preg_replace('/^Beli\s+.*?\s+Terbaru Harga Murah di Shopee\.\s*/i', '', $desc);
                }
            }
            if ($isTokopedia) {
                $title = preg_replace('/\s*[-|]\s*(Tokopedia).*$/i', '', $title);
            }
            if ($isTiktok) {
                $title = preg_replace('/\s*[-|]\s*(TikTok|TikTok Shop).*$/i', '', $title);
            }

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
            $images = array_slice($images, 0, 6);

            // 4. Download and compress images to local storage, formatting as full HTTP URLs
            $downloadedImages = [];
            foreach ($images as $imgUrl) {
                if (str_starts_with($imgUrl, 'http://') || str_starts_with($imgUrl, 'https://')) {
                    $dl = \App\Services\ImageDownloader::downloadAndCompress($imgUrl, 'products/gallery');
                    if (str_starts_with($dl, 'http://') || str_starts_with($dl, 'https://')) {
                        $downloadedImages[] = $dl;
                    } else {
                        $downloadedImages[] = asset('storage/' . $dl);
                    }
                } else {
                    $downloadedImages[] = str_starts_with($imgUrl, '/') ? $imgUrl : asset('storage/' . $imgUrl);
                }
            }

            $primaryImg = $downloadedImages[0] ?? null;
            if (empty($primaryImg)) {
                $primaryImg = \App\Models\Product::getPlaceholderUrl();
            }

            if (empty($desc)) {
                $desc = $cleanTitle . ' — Produk berkualitas tinggi. Dapatkan harga terbaik di buyle.id.';
            }

            // Normal Price vs Promo Price logic
            if ($origPrice > 0 && $origPrice > $price) {
                $normalPrice = (int)$origPrice;
                $promoPrice  = (int)$price;
            } else {
                $normalPrice = (int)$price;
                $promoPrice  = 0;
            }

            $productType = $request->input('product_type', 'physical');

            $item = [
                'name'         => $cleanTitle,
                'slug'         => $slug,
                'price'        => $normalPrice > 0 ? $normalPrice : null,
                'sale_price'   => $promoPrice > 0 ? $promoPrice : null,
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
    /**
     * Dedicated Scan & Scrape for Lynk.id (URL & Source Code HTML) - Overpowered Multi-Strategy.
     */
    public function scanLynk(Request $request)
    {
        try {
            $url = trim($request->input('url', ''));
            if (empty($url)) {
                $url = 'https://lynk.id/imported-product';
            }

            if (!preg_match('#^https?://#i', $url)) {
                $url = 'https://' . $url;
            }

            $rawHtml = '';
            if ($request->filled('html_b64')) {
                $rawHtml = base64_decode($request->input('html_b64'));
            } elseif ($request->filled('html')) {
                $r = $request->input('html');
                $d = base64_decode($r, true);
                $rawHtml = ($d !== false && base64_encode($d) === $r) ? $d : $r;
            }

            // Strategy 1: Multi User-Agent HTTP fetch if HTML is empty or Cloudflare blocked
            if ((empty($rawHtml) || strlen($rawHtml) < 500 || str_contains($rawHtml, 'Cloudflare')) && filter_var($url, FILTER_VALIDATE_URL)) {
                $uas = [
                    'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
                    'WhatsApp/2.23.20.0 i',
                    'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                    'Twitterbot/1.0',
                    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                ];

                foreach ($uas as $ua) {
                    try {
                        $response = \Illuminate\Support\Facades\Http::withHeaders([
                            'User-Agent'      => $ua,
                            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                            'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                        ])->timeout(8)->withOptions(['allow_redirects' => true])->get($url);

                        $body = $response->body();
                        if (!empty($body) && strlen($body) > 500 && !str_contains($body, 'Attention Required!') && !str_contains($body, 'Just a moment...')) {
                            $rawHtml = $body;
                            break;
                        }
                    } catch (\Throwable $e) {}
                }
            }

            // Strategy 2: Microlink API Fallback
            if ((empty($rawHtml) || strlen($rawHtml) < 500 || str_contains($rawHtml, 'Cloudflare')) && filter_var($url, FILTER_VALIDATE_URL)) {
                try {
                    $ml = \Illuminate\Support\Facades\Http::timeout(8)->get('https://api.microlink.io', [
                        'url'  => $url,
                        'meta' => 'true',
                    ]);
                    if ($ml->successful()) {
                        $d = $ml->json('data', []);
                        if (!empty($d)) {
                            $mlTitle = $d['title'] ?? '';
                            $mlDesc  = $d['description'] ?? '';
                            $mlImg   = $d['image']['url'] ?? $d['logo']['url'] ?? '';

                            if ($mlTitle) {
                                $rawHtml = '<html><head><title>' . htmlspecialchars($mlTitle) . '</title>' .
                                    '<meta property="og:title" content="' . htmlspecialchars($mlTitle) . '">' .
                                    '<meta property="og:description" content="' . htmlspecialchars($mlDesc) . '">' .
                                    '<meta property="og:image" content="' . htmlspecialchars($mlImg) . '">' .
                                    '</head><body></body></html>';
                            }
                        }
                    }
                } catch (\Throwable $e) {}
            }

            // Strategy 3: Parse Lynk.id HTML & JS Data
            $title     = '';
            $desc      = '';
            $price     = 0;
            $salePrice = 0;
            $images    = [];

            if (!empty($rawHtml) && !str_contains($rawHtml, 'Attention Required!')) {
                // Title Extraction
                if (preg_match('/<h2[^>]*id=["\']title_product["\'][^>]*>(.*?)<\/h2>/is', $rawHtml, $m)) {
                    $title = trim(html_entity_decode(strip_tags($m[1])));
                } elseif (preg_match('/shareMessage\s*=\s*["\']Check out (.*?) from \w+ @/is', $rawHtml, $m)) {
                    $title = trim(html_entity_decode(strip_tags($m[1])));
                } elseif (preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\']View [^\']*\'s (.*?) Product details/is', $rawHtml, $m)) {
                    $title = trim(html_entity_decode(strip_tags($m[1])));
                } elseif (preg_match('/<meta[^>]+(?:property|name)=["\']og:title["\'][^>]+content=["\']([^"\']+)["\']/i', $rawHtml, $m) ||
                          preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+(?:property|name)=["\']og:title["\']/i', $rawHtml, $m)) {
                    $title = trim(html_entity_decode(strip_tags($m[1])));
                } elseif (preg_match('/<title[^>]*>(.*?)<\/title>/is', $rawHtml, $m)) {
                    $title = trim(html_entity_decode(strip_tags($m[1])));
                    $title = preg_replace('/^LYNK\s*\|\s*/i', '', $title);
                    $title = preg_replace('/\s*[-|]\s*Lynk\.id.*$/i', '', $title);
                }

                // Price Extraction: var p = _g('750000.0') & var sPrice = _g('500000.0')
                if (preg_match('/var p\s*=\s*_g\([\'"]([\d.]+)[\'"]\)/i', $rawHtml, $m)) {
                    $price = floatval($m[1]);
                }
                if (preg_match('/var sPrice\s*=\s*_g\([\'"]([\d.]+)[\'"]\)/i', $rawHtml, $m)) {
                    $salePrice = floatval($m[1]);
                }
                if (!$price && preg_match('/(?:Rp|IDR)\s*([0-9][0-9.,]{2,})/i', $rawHtml, $m)) {
                    $price = floatval(preg_replace('/[^0-9]/', '', $m[1]));
                }

                // Description Extraction
                if (preg_match('/<div[^>]*class=["\'][^"\']*rich-content[^"\']*["\'][^>]*>(.*?)<\/div>\s*<\/div>/is', $rawHtml, $m) ||
                    preg_match('/<div[^>]*class=["\'][^"\']*rich-content[^"\'][^>]*>(.*?)<\/div>/is', $rawHtml, $m)) {
                    $desc = trim(html_entity_decode($m[1]));
                } elseif (preg_match('/<meta[^>]+(?:property|name)=["\']og:description["\'][^>]+content=["\']([^"\']+)["\']/i', $rawHtml, $m) ||
                          preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+(?:property|name)=["\']og:description["\']/i', $rawHtml, $m)) {
                    $desc = trim(html_entity_decode(strip_tags($m[1])));
                }

                // Image Extraction
                if (preg_match_all('/https?:\/\/cdn\.lynkid\.my\.id\/products\/[^\s"\']+/i', $rawHtml, $imgMatches)) {
                    foreach ($imgMatches[0] as $img) {
                        $cleanImg = strtok($img, '?');
                        if (filter_var($cleanImg, FILTER_VALIDATE_URL) && !in_array($cleanImg, $images)) {
                            $images[] = $cleanImg;
                        }
                    }
                }
                if (empty($images)) {
                    if (preg_match_all('/<meta[^>]+(?:property|name)=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i', $rawHtml, $ms) ||
                        preg_match_all('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+(?:property|name)=["\']og:image["\']/i', $rawHtml, $ms)) {
                        foreach ($ms[1] as $img) {
                            $img = trim($img);
                            if ($img && filter_var($img, FILTER_VALIDATE_URL) && !in_array($img, $images)) {
                                $images[] = $img;
                            }
                        }
                    }
                }
            }

            // Strategy 4: FAIL-SAFE URL & Creator Path Parsing (Guarantees zero-failure!)
            $parsedUrl = parse_url($url);
            $pathSegments = array_values(array_filter(explode('/', $parsedUrl['path'] ?? '')));
            $username = $pathSegments[0] ?? '';
            $lastSeg = end($pathSegments) ?: '';

            if (empty($title) || strlen($title) < 3 || $title === 'Just a moment...') {
                if ($lastSeg && $lastSeg !== $username) {
                    $cleanSeg = preg_replace('/-i\.\d+$/i', '', urldecode($lastSeg));
                    $cleanSeg = ucwords(str_replace(['-', '_'], ' ', $cleanSeg));
                    $title = (strlen($cleanSeg) > 2) ? $cleanSeg : ('Produk Digital Lynk.id' . ($username ? ' (@' . $username . ')' : ''));
                } elseif ($username) {
                    $title = 'Produk Digital Lynk.id (@' . $username . ')';
                } else {
                    $title = 'Produk Digital Lynk.id';
                }
            }

            if (empty($desc)) {
                $desc = 'Produk Digital ' . $title . ' dari Lynk.id' . ($username ? ' (@' . $username . ')' : '') . '. Silakan periksa detail & atur harga sebelum menyimpan.';
            }

            if ($salePrice > 0 && $price > 0 && $salePrice > $price) {
                [$price, $salePrice] = [$salePrice, $price];
            }
            if ($salePrice > 0 && $salePrice === $price) {
                $salePrice = 0;
            }

            $images = array_values(array_unique($images));
            $images = array_slice($images, 0, 6);

            $downloadedImages = [];
            foreach ($images as $imgUrl) {
                if (str_starts_with($imgUrl, 'http://') || str_starts_with($imgUrl, 'https://')) {
                    $dl = \App\Services\ImageDownloader::downloadAndCompress($imgUrl, 'products/gallery');
                    if (str_starts_with($dl, 'http://') || str_starts_with($dl, 'https://')) {
                        $downloadedImages[] = $dl;
                    } else {
                        $downloadedImages[] = asset('storage/' . $dl);
                    }
                } else {
                    $downloadedImages[] = str_starts_with($imgUrl, '/') ? $imgUrl : asset('storage/' . $imgUrl);
                }
            }

            $primaryImg = $downloadedImages[0] ?? null;
            if (empty($primaryImg)) {
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
