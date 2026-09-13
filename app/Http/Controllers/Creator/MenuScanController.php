<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorProfile;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\AiVisionService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuScanController extends Controller
{
    public function scan(Request $request, AiVisionService $aiService)
    {
        $user = auth()->user();
        $profile = $user->creatorProfile;

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
        $importedCount = 0;

        // Default category fallback
        $defaultCat = ProductCategory::first();
        $defaultCatId = $defaultCat?->id ?? 1;

        foreach ($request->input('items') as $item) {
            $name = trim($item['name']);
            if (empty($name)) continue;

            $price = (int) ($item['price'] ?? 0);
            $desc  = trim($item['description'] ?? '');

            // Unique slug generator
            $slug = Str::slug($name);
            $origSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $origSlug . '-' . $counter++;
            }

            Product::create([
                'seller_id'   => $user->id,
                'category_id' => $defaultCatId,
                'name'        => $name,
                'slug'        => $slug,
                'price'       => $price,
                'description' => $desc,
                'product_type'=> 'external_link',
                'is_active'   => true,
                'stock'       => 999,
            ]);

            $importedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil mengimpor {$importedCount} produk ke katalog Anda!",
            'imported_count' => $importedCount
        ]);
    }
}
