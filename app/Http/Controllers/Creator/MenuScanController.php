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
     * Scan & Scrape product details from URL (Website, TikTok Shop, Tokopedia, Shopee).
     * Returns: name, slug, price (normal), sale_price (promo), description, image, images[], source_url.
     */
    public function scanUrl(Request $request)
    {
        $url    = trim($request->input('url', ''));
        $source = $request->input('source', 'url');

        if (empty($url)) {
            return response()->json(['success' => false, 'message' => 'URL produk wajib diisi.'], 422);
        }

        $title     = '';
        $desc      = '';
        $image     = null;
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

            // ── Title ──────────────────────────────────────────────────
            if (preg_match('/<meta[^>]*property=["\']og:title["\'][^>]*content=["\'](.*?)["\']/is', $html, $m)) {
                $title = trim(html_entity_decode(strip_tags($m[1])));
            } elseif (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                $title = trim(html_entity_decode(strip_tags($m[1])));
            }

            // ── Description ────────────────────────────────────────────
            if (preg_match('/<meta[^>]*property=["\']og:description["\'][^>]*content=["\'](.*?)["\']/is', $html, $m)) {
                $desc = trim(html_entity_decode($m[1]));
            } elseif (preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\'](.*?)["\']/is', $html, $m)) {
                $desc = trim(html_entity_decode($m[1]));
            }

            // ── Primary Image (og:image) ────────────────────────────────
            if (preg_match_all('/<meta[^>]*property=["\']og:image["\'][^>]*content=["\'](.*?)["\']/is', $html, $ms)) {
                foreach ($ms[1] as $img) {
                    $img = trim($img);
                    if ($img && filter_var($img, FILTER_VALIDATE_URL)) {
                        $images[] = $img;
                    }
                }
            }

            // ── JSON-LD Structured Data (best source for prices & images) ──
            if (preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is', $html, $jsonMatches)) {
                foreach ($jsonMatches[1] as $jsonRaw) {
                    $jsonData = json_decode(trim($jsonRaw), true);
                    if (!$jsonData) continue;

                    // Handle @graph array
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

                        // Images from JSON-LD
                        $imgField = $obj['image'] ?? null;
                        if (is_string($imgField) && $imgField) {
                            if (filter_var($imgField, FILTER_VALIDATE_URL)) $images[] = $imgField;
                        } elseif (is_array($imgField)) {
                            foreach ($imgField as $imgItem) {
                                $imgUrl = is_array($imgItem) ? ($imgItem['url'] ?? '') : $imgItem;
                                if ($imgUrl && filter_var($imgUrl, FILTER_VALIDATE_URL)) $images[] = $imgUrl;
                            }
                        }

                        // Offers price
                        $offers = $obj['offers'] ?? null;
                        if ($offers) {
                            $offerList = isset($offers['@type']) ? [$offers] : $offers;
                            foreach ((array) $offerList as $offer) {
                                $offerPrice = (float) ($offer['price'] ?? 0);
                                if ($offerPrice > 100) {
                                    if ($price <= 0) $price = $offerPrice;
                                    else $salePrice = min($price, $offerPrice);
                                }
                                // highPrice = original, lowPrice = sale
                                if (!empty($offer['highPrice'])) {
                                    $price     = (float) $offer['highPrice'];
                                    $salePrice = (float) ($offer['lowPrice'] ?? 0);
                                }
                            }
                        }
                    }
                }
            }

            // ── Regex price fallback (Tokopedia, Shopee, TikTok patterns) ──
            if ($price <= 0) {
                // Meta tag prices
                if (preg_match('/<meta[^>]*property=["\'](?:product|og):price:amount["\'][^>]*content=["\']([\d.]+)\b/i', $html, $m)) {
                    $price = (float) $m[1];
                } elseif (preg_match('/<meta[^>]*name=["\'](?:twitter:data1|price)["\'][^>]*content=["\']([\d.]+)\b/i', $html, $m)) {
                    $price = (float) $m[1];
                }
            }
            if ($price <= 0) {
                // Shopee micro-currency / JSON prices ("price":4900000000 or "price_min":49000)
                if (preg_match('/"(?:price_min|price)"\s*:\s*(\d{5,})/i', $html, $m)) {
                    $rawP = (float) $m[1];
                    $price = ($rawP > 10000000) ? ($rawP / 100000) : $rawP;
                }
            }
            if ($price <= 0) {
                if (preg_match('/["\'](?:price|harga)["\']\s*:\s*["\']?(\d{4,})["\']?/i', $html, $m)) {
                    $price = (float) $m[1];
                }
            }
            if ($price <= 0 || $salePrice <= 0) {
                // Rp pattern – grab candidates
                preg_match_all('/(?:Rp|IDR)[\s.]*([\d]{2,}(?:[.,][\d]{3})*)/u', $html, $pm);
                $pricesCandidates = [];
                foreach ($pm[1] as $rawP) {
                    $cleaned = (float) preg_replace('/[^\d]/', '', $rawP);
                    if ($cleaned >= 500 && $cleaned < 1000000000) $pricesCandidates[] = $cleaned;
                }
                $pricesCandidates = array_unique($pricesCandidates);
                if (count($pricesCandidates) >= 2) {
                    rsort($pricesCandidates); // descending: biggest = original
                    if ($price <= 0)     $price     = $pricesCandidates[0];
                    if ($salePrice <= 0) $salePrice = $pricesCandidates[1];
                } elseif (count($pricesCandidates) === 1 && $price <= 0) {
                    $price = $pricesCandidates[0];
                }
            }
        } catch (\Exception $e) {
            // Ignore HTTP fetch errors; rely on URL slug parsing below
        }

        // ── Deduplicate images ──────────────────────────────────────────
        $images = array_values(array_unique($images));
        $image  = $images[0] ?? null;

        // ── Clean up title ──────────────────────────────────────────────
        $cleanTitle = preg_replace('/\s*(\||-|–|—)\s*(TikTok|Tokopedia|Shopee|Bukalapak|Lazada|Blibli|Jual|Beli|Online|Murah|Terlengkap|Buy).*$/i', '', $title);
        $cleanTitle = trim($cleanTitle);

        // ── Fallback: smart URL slug extraction ─────────────────────────
        if (empty($cleanTitle) || mb_strlen($cleanTitle) < 3) {
            $parsedUrl    = parse_url($url);
            $path         = $parsedUrl['path'] ?? '';
            $pathSegments = array_values(array_filter(explode('/', $path)));
            $lastSegment  = end($pathSegments) ?: '';

            // Skip generic slugs
            if (in_array(strtolower($lastSegment), ['create', 'products', 'item', 'product', 'detail', 'p', 'i']) && count($pathSegments) > 1) {
                $lastSegment = $pathSegments[count($pathSegments) - 2];
            }

            // Strip Tokopedia/Shopee IDs: produk-nama-i.12345.67890 or produk-nama-12345678
            $lastSegment = preg_replace('/-i\.\d+\.\d+$/i', '', $lastSegment);
            $lastSegment = preg_replace('/-p\d+$/i', '', $lastSegment);
            $lastSegment = preg_replace('/-\d{5,}$/i', '', $lastSegment);

            $extractedName = ucwords(str_replace(['-', '_'], ' ', urldecode($lastSegment)));
            $extractedName = trim(preg_replace('/\b(Product|Item|Detail|Create|Index|Shop|Toko|Id)\b/i', '', $extractedName));
            $extractedName = trim(preg_replace('/\s+/', ' ', $extractedName));

            $cleanTitle = (mb_strlen($extractedName) > 2) ? $extractedName : 'Produk Impor';
        }

        // ── Build slug ──────────────────────────────────────────────────
        $slug = Str::slug($cleanTitle);

        // ── Clean & Format Description (No raw URL parameters) ──────────
        if (!empty($desc)) {
            $desc = preg_replace('/Produk diimpor dari:\s*https?:\/\/[^\s]+/i', '', $desc);
            $desc = trim(strip_tags(html_entity_decode($desc)));
        }
        if (empty($desc) || str_starts_with(strtolower($desc), 'http') || mb_strlen($desc) < 5) {
            $desc = $cleanTitle . ' — Produk jualan berkualitas tinggi. Dapatkan penawaran terbaik dan layanan pengiriman cepat.';
        }

        // ── If sale price > normal price, swap ──────────────────────────
        if ($salePrice > 0 && $price > 0 && $salePrice > $price) {
            [$price, $salePrice] = [$salePrice, $price];
        }
        // If sale price equals price, clear it
        if ($salePrice > 0 && $salePrice === $price) {
            $salePrice = 0;
        }

        $item = [
            'name'        => $cleanTitle,
            'slug'        => $slug,
            'price'       => $price > 0 ? (int) $price : null,
            'sale_price'  => $salePrice > 0 ? (int) $salePrice : null,
            'description' => $desc,
            'image'       => $image ?: \App\Models\Product::getPlaceholderUrl(),
            'images'      => $images,
            'source_url'  => $url,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendeteksi data produk!',
            'items'   => [$item]
        ]);
    }
}
