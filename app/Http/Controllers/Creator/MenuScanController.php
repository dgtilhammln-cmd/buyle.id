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

            // Smart slug logic
            $cleanTitle = Str::limit($name, 45, '');
            $baseSlug   = rtrim(Str::slug($cleanTitle), '-');
            $slug = $baseSlug ?: 'produk-' . time() . '-' . $importedCount;

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
                'product_type' => 'external_link',
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
}
