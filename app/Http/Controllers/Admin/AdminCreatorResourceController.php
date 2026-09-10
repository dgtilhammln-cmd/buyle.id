<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\CreatorProfile;
use App\Models\CreatorBioBlock;
use App\Models\OrderItem;
use App\Models\AnalyticsEvent;
use App\Models\ProductVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCreatorResourceController extends Controller
{
    /**
     * Helper untuk mendapatkan URL storage yang valid tanpa broken image
     */
    public static function getStorageUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $cleanPath = preg_replace('#^/?(storage/)?#i', '', $path);
        return asset('storage/' . $cleanPath);
    }

    /**
     * Tampilkan daftar audit penggunaan resource, revenue & aktivitas online creator.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $sortBy = $request->input('sort_by', 'storage_desc');
        $onlineFilter = $request->input('online_status', 'all');

        // Ambil semua pengguna bertipe creator (Kecuali Admin / Super Admin)
        $query = User::query()
            ->with(['creatorProfile', 'products'])
            ->whereNotIn('role', ['admin', 'super_admin', 'admin_super'])
            ->where(function ($q) {
                $q->where('role', 'creator')
                  ->orWhereHas('creatorProfile')
                  ->orWhereHas('products');
            });

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('creatorProfile', function ($cp) use ($search) {
                      $cp->where('store_name', 'like', "%{$search}%")
                         ->where('store_slug', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->get();

        // Hitung rincian resource, online history, & revenue untuk setiap creator
        $creatorResources = $users->map(function ($user) {
            $productCount = $user->products->count();
            $creatorProfile = $user->creatorProfile;
            $bioBlocksCount = 0;
            $bioBlocks = collect();

            if ($creatorProfile) {
                $bioBlocks = CreatorBioBlock::where('creator_id', $creatorProfile->id)->get();
                $bioBlocksCount = $bioBlocks->count();
            }

            // Kumpulkan berkas milik creator
            $filePaths = [];

            if (!empty($user->avatar)) {
                $filePaths[] = $user->avatar;
            }

            if ($creatorProfile) {
                if (!empty($creatorProfile->store_banner_1)) {
                    $filePaths[] = $creatorProfile->store_banner_1;
                }
                if (!empty($creatorProfile->store_banner_2)) {
                    $filePaths[] = $creatorProfile->store_banner_2;
                }
            }

            foreach ($user->products as $product) {
                if (!empty($product->image)) {
                    $filePaths[] = $product->image;
                }
                if (!empty($product->brochure)) {
                    $filePaths[] = $product->brochure;
                }
                if (!empty($product->og_image)) {
                    $filePaths[] = $product->og_image;
                }
                if (!empty($product->digital_resource)) {
                    $filePaths[] = $product->digital_resource;
                }
                if (is_array($product->gallery)) {
                    foreach ($product->gallery as $galImg) {
                        if (!empty($galImg)) {
                            $filePaths[] = $galImg;
                        }
                    }
                }
            }

            foreach ($bioBlocks as $block) {
                $json = $block->data_json ?? [];
                if (is_array($json)) {
                    foreach (['image', 'thumb', 'pdf_file', 'file', 'file_path', 'avatar'] as $key) {
                        if (!empty($json[$key]) && is_string($json[$key])) {
                            $filePaths[] = $json[$key];
                        }
                    }
                }
            }

            $filePaths = array_unique(array_filter($filePaths));

            $totalSizeBytes = 0;
            $existingFileCount = 0;

            foreach ($filePaths as $path) {
                if (Str::startsWith($path, ['http://', 'https://'])) {
                    continue;
                }
                $cleanPath = preg_replace('#^/?(storage/)?#i', '', $path);

                if (Storage::disk('public')->exists($cleanPath)) {
                    $totalSizeBytes += Storage::disk('public')->size($cleanPath);
                    $existingFileCount++;
                } elseif (file_exists(public_path($path))) {
                    $totalSizeBytes += filesize(public_path($path));
                    $existingFileCount++;
                }
            }

            $totalSizeMb = round($totalSizeBytes / (1024 * 1024), 2);
            $totalSizeGb = round($totalSizeBytes / (1024 * 1024 * 1024), 3);

            // Estimasi Biaya Storage Server (Asumsi Rp 2.500 / GB per bulan)
            $estMonthlyCost = ceil($totalSizeGb * 2500);
            if ($totalSizeBytes > 0 && $estMonthlyCost < 500) {
                $estMonthlyCost = 500; // Minimal Rp 500 jika ada file
            }

            // Total Omset Revenue Penjualan Creator
            $totalRevenue = OrderItem::whereHas('product', function ($q) use ($user) {
                $q->where('seller_id', $user->id);
            })->whereHas('order', function ($q) {
                $q->whereNotIn('status', ['pending', 'cancelled', 'refunded', 'failed', 'expired']);
            })->sum('subtotal');

            // Potensi Abandoned Cart (Keranjang Belanja Tertunda Pembeli)
            $abandonedCartCount = 0;
            $abandonedCartValue = 0;
            try {
                $cartItems = \App\Models\Cart::whereHas('product', function ($q) use ($user) {
                    $q->where('seller_id', $user->id);
                })->get();
                $abandonedCartCount = $cartItems->sum('qty');
                $abandonedCartValue = $cartItems->sum(function ($item) {
                    return $item->subtotal;
                });
            } catch (\Exception $e) {
                // Ignore if cart query fails
            }

            // Riwayat Online Status Breakdown
            $lastSeen = $user->last_seen_at;
            $isOnlineNow = $lastSeen && $lastSeen->gt(now()->subMinutes(15));
            $isActiveToday = $lastSeen && $lastSeen->gt(now()->subHours(24));
            $isActiveWeek = $lastSeen && $lastSeen->gt(now()->subDays(7));

            if ($isOnlineNow) {
                $onlineStatusCode = 'online_now';
                $onlineStatusLabel = 'Online Sekarang';
                $onlineBadgeClass = 'online-badge-now';
            } elseif ($isActiveToday) {
                $onlineStatusCode = 'active_today';
                $onlineStatusLabel = 'Aktif Hari Ini';
                $onlineBadgeClass = 'online-badge-today';
            } elseif ($isActiveWeek) {
                $onlineStatusCode = 'active_week';
                $onlineStatusLabel = 'Aktif Minggu Ini';
                $onlineBadgeClass = 'online-badge-week';
            } else {
                $onlineStatusCode = 'inactive';
                $onlineStatusLabel = 'Inaktif (> 7 hr)';
                $onlineBadgeClass = 'online-badge-inactive';
            }

            $lastSeenText = $lastSeen ? $lastSeen->diffForHumans() : 'Belum Aktif';
            $lastSeenFull = $lastSeen ? $lastSeen->translatedFormat('d M Y H:i:s') : 'Belum Pernah Login';

            // Direct WhatsApp Link Generator
            $waNumber = null;
            $waLink = null;
            $rawPhone = $user->phone ?? ($creatorProfile->phone ?? null);
            if (!empty($rawPhone)) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                if (Str::startsWith($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
                if (!Str::startsWith($cleanPhone, '62') && strlen($cleanPhone) >= 9) {
                    $cleanPhone = '62' . $cleanPhone;
                }
                $waNumber = $cleanPhone;
                $storeTitle = $creatorProfile->store_name ?? $user->name;
                $msg = urlencode("Halo kak {$user->name} ({$storeTitle}), kami dari tim Buyle.id ingin menyapa & membantu optimasi toko online kamu agar omset penjualan makin meningkat! 🚀");
                $waLink = "https://wa.me/{$cleanPhone}?text={$msg}";
            }

            // Format Avatar URL
            $avatarUrl = self::getStorageUrl($user->avatar);

            return [
                'user'                 => $user,
                'creator_profile'      => $creatorProfile,
                'avatar_url'           => $avatarUrl,
                'product_count'        => $productCount,
                'bio_blocks_count'     => $bioBlocksCount,
                'asset_file_count'     => $existingFileCount,
                'total_size_bytes'     => $totalSizeBytes,
                'total_size_mb'        => $totalSizeMb,
                'est_monthly_cost'     => $estMonthlyCost,
                'total_revenue'        => $totalRevenue,
                'abandoned_cart_count' => $abandonedCartCount,
                'abandoned_cart_value' => $abandonedCartValue,
                'is_online_now'        => $isOnlineNow,
                'is_active_today'      => $isActiveToday,
                'is_active_week'       => $isActiveWeek,
                'online_status_code'   => $onlineStatusCode,
                'online_status_label'  => $onlineStatusLabel,
                'online_badge_class'   => $onlineBadgeClass,
                'last_seen_text'       => $lastSeenText,
                'last_seen_full'       => $lastSeenFull,
                'wa_number'            => $waNumber,
                'wa_link'              => $waLink,
            ];
        });

        // Filter berdasarkan Status Online
        if ($onlineFilter !== 'all') {
            $creatorResources = $creatorResources->filter(function ($item) use ($onlineFilter) {
                if ($onlineFilter === 'online_now') return $item['is_online_now'];
                if ($onlineFilter === 'active_today') return $item['is_active_today'];
                if ($onlineFilter === 'active_week') return $item['is_active_week'];
                if ($onlineFilter === 'inactive') return !$item['is_active_week'];
                return true;
            });
        }

        // Sorting
        if ($sortBy === 'storage_desc') {
            $creatorResources = $creatorResources->sortByDesc('total_size_bytes');
        } elseif ($sortBy === 'storage_asc') {
            $creatorResources = $creatorResources->sortBy('total_size_bytes');
        } elseif ($sortBy === 'revenue_desc') {
            $creatorResources = $creatorResources->sortByDesc('total_revenue');
        } elseif ($sortBy === 'abandoned_desc') {
            $creatorResources = $creatorResources->sortByDesc('abandoned_cart_value');
        } elseif ($sortBy === 'products_desc') {
            $creatorResources = $creatorResources->sortByDesc('product_count');
        } elseif ($sortBy === 'blocks_desc') {
            $creatorResources = $creatorResources->sortByDesc('bio_blocks_count');
        } elseif ($sortBy === 'assets_desc') {
            $creatorResources = $creatorResources->sortByDesc('asset_file_count');
        } elseif ($sortBy === 'online_recent') {
            $creatorResources = $creatorResources->sortByDesc(function ($item) {
                return $item['user']->last_seen_at ? $item['user']->last_seen_at->timestamp : 0;
            });
        }

        // 10 Statistik Keseluruhan (Grid 1 Baris 10 Card)
        $totalCreatorsCount       = $users->count();
        $onlineCreatorsCount      = $users->filter(fn($u) => $u->last_seen_at && $u->last_seen_at->gt(now()->subMinutes(15)))->count();
        $totalStorageBytesOverall = $creatorResources->sum('total_size_bytes');
        $totalStorageMbOverall    = round($totalStorageBytesOverall / (1024 * 1024), 2);
        $estServerCostOverall     = $creatorResources->sum('est_monthly_cost');
        $totalRevenueOverall      = $creatorResources->sum('total_revenue');
        $totalAbandonedOverall    = $creatorResources->sum('abandoned_cart_value');
        $avgLtvPerMb              = $totalStorageMbOverall > 0 ? round($totalRevenueOverall / $totalStorageMbOverall, 0) : 0;
        $totalProductsOverall     = $creatorResources->sum('product_count');
        $totalBlocksOverall       = $creatorResources->sum('bio_blocks_count');
        $totalAssetsOverall       = $creatorResources->sum('asset_file_count');

        // Scan Orphan / Ghost Files (File Sampah Terbuang)
        $orphanData = self::getOrphanFiles();

        return view('admin.creator-resources.index', [
            'creatorResources'        => $creatorResources,
            'search'                  => $search,
            'sortBy'                  => $sortBy,
            'onlineFilter'            => $onlineFilter,
            'totalCreatorsCount'      => $totalCreatorsCount,
            'onlineCreatorsCount'     => $onlineCreatorsCount,
            'totalStorageMbOverall'   => $totalStorageMbOverall,
            'estServerCostOverall'    => $estServerCostOverall,
            'totalRevenueOverall'     => $totalRevenueOverall,
            'totalAbandonedOverall'  => $totalAbandonedOverall,
            'avgLtvPerMb'             => $avgLtvPerMb,
            'totalProductsOverall'    => $totalProductsOverall,
            'totalBlocksOverall'      => $totalBlocksOverall,
            'totalAssetsOverall'      => $totalAssetsOverall,
            'orphanData'              => $orphanData,
        ]);
    }

    /**
     * Tampilkan detail rincian aset, dependency graph, traffic hits, dan omset per creator.
     */
    public function show($id)
    {
        $user = User::with(['creatorProfile', 'products'])->findOrFail($id);

        if (in_array($user->role, ['admin', 'super_admin', 'admin_super'])) {
            return redirect()->route('admin.creator-resources.index')
                ->with('error', 'Akun admin tidak memiliki data resource creator.');
        }

        $creatorProfile = $user->creatorProfile;
        $bioBlocks = $creatorProfile ? CreatorBioBlock::where('creator_id', $creatorProfile->id)->get() : collect();

        // Rincian Aset Berkas & Dependency Graph
        $assets = [];

        // 1. Avatar User
        if (!empty($user->avatar)) {
            $assets[] = [
                'type'           => 'Avatar Profil User',
                'raw_path'       => $user->avatar,
                'used_in'        => [
                    ['label' => 'Profil Pengguna', 'sub' => 'Avatar Akun & Bio Header', 'url' => null]
                ],
                'product_id'     => null,
                'block_id'       => null,
            ];
        }

        // 2. Banner Creator Profile
        if ($creatorProfile) {
            if (!empty($creatorProfile->store_banner_1)) {
                $assets[] = [
                    'type'       => 'Banner Toko (Utama)',
                    'raw_path'   => $creatorProfile->store_banner_1,
                    'used_in'    => [
                        ['label' => 'Halaman Bio Toko', 'sub' => 'Banner Header 1', 'url' => url('/' . $creatorProfile->store_slug)]
                    ],
                    'product_id' => null,
                    'block_id'   => null,
                ];
            }
            if (!empty($creatorProfile->store_banner_2)) {
                $assets[] = [
                    'type'       => 'Banner Toko (Sekunder)',
                    'raw_path'   => $creatorProfile->store_banner_2,
                    'used_in'    => [
                        ['label' => 'Halaman Bio Toko', 'sub' => 'Banner Header 2', 'url' => url('/' . $creatorProfile->store_slug)]
                    ],
                    'product_id' => null,
                    'block_id'   => null,
                ];
            }
        }

        // 3. Berkas Produk
        foreach ($user->products as $product) {
            $pName = $product->name;
            $pUrl  = route('admin.services.show', $product->id);

            if (!empty($product->image)) {
                $assets[] = [
                    'type'       => 'Gambar Utama Produk',
                    'raw_path'   => $product->image,
                    'used_in'    => [
                        ['label' => "Produk: {$pName}", 'sub' => 'Katalog Marketplace & Bio Link', 'url' => $pUrl]
                    ],
                    'product_id' => $product->id,
                    'block_id'   => null,
                ];
            }
            if (!empty($product->brochure)) {
                $assets[] = [
                    'type'       => 'Brosur / PDF Produk',
                    'raw_path'   => $product->brochure,
                    'used_in'    => [
                        ['label' => "Produk: {$pName}", 'sub' => 'Download Brosur Spesifikasi', 'url' => $pUrl]
                    ],
                    'product_id' => $product->id,
                    'block_id'   => null,
                ];
            }
            if (!empty($product->og_image)) {
                $assets[] = [
                    'type'       => 'Social Share Image (OG)',
                    'raw_path'   => $product->og_image,
                    'used_in'    => [
                        ['label' => "Produk: {$pName}", 'sub' => 'Preview WhatsApp / Meta Tag', 'url' => $pUrl]
                    ],
                    'product_id' => $product->id,
                    'block_id'   => null,
                ];
            }
            if (!empty($product->digital_resource)) {
                $assets[] = [
                    'type'       => 'File Digital Download',
                    'raw_path'   => $product->digital_resource,
                    'used_in'    => [
                        ['label' => "Produk Digital: {$pName}", 'sub' => 'File Akses Pembeli', 'url' => $pUrl]
                    ],
                    'product_id' => $product->id,
                    'block_id'   => null,
                ];
            }
            if (is_array($product->gallery)) {
                foreach ($product->gallery as $gIdx => $galImg) {
                    if (!empty($galImg)) {
                        $assets[] = [
                            'type'       => "Galeri Produk #" . ($gIdx + 1),
                            'raw_path'   => $galImg,
                            'used_in'    => [
                                ['label' => "Produk: {$pName}", 'sub' => 'Galeri Foto Produk', 'url' => $pUrl]
                            ],
                            'product_id' => $product->id,
                            'block_id'   => null,
                        ];
                    }
                }
            }
        }

        // 4. Berkas Bio Block
        foreach ($bioBlocks as $block) {
            $bTitle = $block->title ?: 'Block #' . $block->id;
            $json = $block->data_json ?? [];
            if (is_array($json)) {
                foreach (['image', 'thumb', 'pdf_file', 'file', 'file_path', 'avatar'] as $key) {
                    if (!empty($json[$key]) && is_string($json[$key])) {
                        $assets[] = [
                            'type'       => "Aset Bio Block (" . ucfirst($key) . ")",
                            'raw_path'   => $json[$key],
                            'used_in'    => [
                                ['label' => "Bio Block: {$bTitle}", 'sub' => 'Tipe: ' . strtoupper($block->type), 'url' => $creatorProfile ? url('/' . $creatorProfile->store_slug) : null]
                            ],
                            'product_id' => null,
                            'block_id'   => $block->id,
                        ];
                    }
                }
            }
        }

        // Olah Detail Aset (Hitung Size, URL, Traffic/Hits)
        $detailedAssets = [];
        $totalBytes = 0;

        foreach ($assets as $idx => $asset) {
            $path = $asset['raw_path'];
            $url  = self::getStorageUrl($path);
            $sizeBytes = 0;
            $exists = false;

            if (!Str::startsWith($path, ['http://', 'https://'])) {
                $cleanPath = preg_replace('#^/?(storage/)?#i', '', $path);
                if (Storage::disk('public')->exists($cleanPath)) {
                    $sizeBytes = Storage::disk('public')->size($cleanPath);
                    $exists = true;
                } elseif (file_exists(public_path($path))) {
                    $sizeBytes = filesize(public_path($path));
                    $exists = true;
                }
            } else {
                $exists = true;
            }

            $totalBytes += $sizeBytes;

            // Traffic / Hits Detector
            $hitsCount = 0;
            if ($asset['block_id']) {
                $hitsCount = AnalyticsEvent::where('bio_block_id', $asset['block_id'])->count();
            } elseif ($asset['product_id']) {
                $hitsCount = ProductVisit::where('product_id', $asset['product_id'])->count();
            }

            $sizeFormatted = $sizeBytes > (1024 * 1024)
                ? round($sizeBytes / (1024 * 1024), 2) . ' MB'
                : round($sizeBytes / 1024, 1) . ' KB';

            $detailedAssets[] = [
                'id'             => $idx + 1,
                'type'           => $asset['type'],
                'raw_path'       => $path,
                'url'            => $url,
                'size_bytes'     => $sizeBytes,
                'size_formatted' => $sizeFormatted,
                'exists'         => $exists,
                'used_in'        => $asset['used_in'],
                'hits_count'     => $hitsCount,
            ];
        }

        $totalSizeMb = round($totalBytes / (1024 * 1024), 2);
        $totalSizeGb = round($totalBytes / (1024 * 1024 * 1024), 3);
        $estMonthlyCost = ceil($totalSizeGb * 2500);
        if ($totalBytes > 0 && $estMonthlyCost < 500) {
            $estMonthlyCost = 500;
        }

        // Total Revenue Creator
        $totalRevenue = OrderItem::whereHas('product', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })->whereHas('order', function ($q) {
            $q->whereNotIn('status', ['pending', 'cancelled', 'refunded', 'failed', 'expired']);
        })->sum('subtotal');

        return view('admin.creator-resources.show', [
            'user'            => $user,
            'creatorProfile'  => $creatorProfile,
            'avatarUrl'       => self::getStorageUrl($user->avatar),
            'detailedAssets'  => $detailedAssets,
            'totalAssets'     => count($detailedAssets),
            'totalSizeMb'     => $totalSizeMb,
            'estMonthlyCost'  => $estMonthlyCost,
            'totalRevenue'    => $totalRevenue,
            'productCount'    => $user->products->count(),
            'bioBlocksCount'  => $bioBlocks->count(),
        ]);
    }

    /**
     * Impersonation / Masuk ke Dashboard Creator tanpa password (Admin Instant Switch)
     */
    public function impersonate($id)
    {
        $user = User::findOrFail($id);

        if (in_array($user->role, ['admin', 'super_admin', 'admin_super'])) {
            return redirect()->back()->with('error', 'Tidak dapat impersonate akun admin.');
        }

        // Autentikasi instant sebagai creator
        auth()->login($user);

        return redirect()->route('creator.dashboard')->with('success', "Berhasil masuk ke dashboard creator as {$user->name}.");
    }

    /**
     * Helper untuk mengompres berkas gambar lokal (JPG, PNG, WEBP)
     */
    public static function compressImageFile(string $filePath, int $quality = 70): array
    {
        if (empty($filePath) || Str::startsWith($filePath, ['http://', 'https://'])) {
            return ['success' => false, 'message' => 'Bukan berkas lokal.'];
        }

        $cleanPath = preg_replace('#^/?(storage/)?#i', '', $filePath);
        $fullPath = null;

        if (Storage::disk('public')->exists($cleanPath)) {
            $fullPath = Storage::disk('public')->path($cleanPath);
        } elseif (file_exists(public_path($filePath))) {
            $fullPath = public_path($filePath);
        } elseif (file_exists(public_path('storage/' . $cleanPath))) {
            $fullPath = public_path('storage/' . $cleanPath);
        }

        if (!$fullPath || !file_exists($fullPath)) {
            return ['success' => false, 'message' => 'Berkas tidak ditemukan di storage server.'];
        }

        $origSize = filesize($fullPath);
        if ($origSize === 0) {
            return ['success' => false, 'message' => 'Ukuran berkas 0 byte.'];
        }

        $info = @getimagesize($fullPath);
        if (!$info) {
            return ['success' => false, 'message' => 'Format berkas bukan gambar (misal PDF atau Zip).'];
        }

        $mime = $info['mime'];
        $image = null;

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($fullPath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($fullPath);
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($fullPath);
                break;
            default:
                return ['success' => false, 'message' => "Format {$mime} tidak didukung untuk kompresi."];
        }

        if (!$image) {
            return ['success' => false, 'message' => 'Gagal membaca berkas gambar.'];
        }

        // Simpan versi terkompresi dengan optimal alpha handling
        imagealphablending($image, false);
        imagesavealpha($image, true);

        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            imagejpeg($image, $fullPath, $quality);
        } elseif ($mime === 'image/png') {
            imagepng($image, $fullPath, 9);
        } elseif ($mime === 'image/webp') {
            imagewebp($image, $fullPath, $quality);
        }

        imagedestroy($image);
        clearstatcache(true, $fullPath);

        $newSize = filesize($fullPath);
        $savedBytes = max(0, $origSize - $newSize);
        $savedKb = round($savedBytes / 1024, 1);

        return [
            'success'     => true,
            'orig_size'   => $origSize,
            'new_size'    => $newSize,
            'saved_bytes' => $savedBytes,
            'saved_kb'    => $savedKb,
        ];
    }

    /**
     * Kompresi 1 Aset Berkas
     */
    public function compressAsset(Request $request, $id)
    {
        $rawPath = $request->input('raw_path');
        if (empty($rawPath)) {
            return redirect()->back()->with('error', 'Path berkas tidak valid.');
        }

        $res = self::compressImageFile($rawPath, 70);

        if (!$res['success']) {
            return redirect()->back()->with('error', $res['message']);
        }

        if ($res['saved_bytes'] > 0) {
            $origMb = round($res['orig_size'] / 1024, 1) . ' KB';
            $newMb  = round($res['new_size'] / 1024, 1) . ' KB';
            return redirect()->back()->with('success', "Berkas berhasil dikompresi maksimal dari {$origMb} menjadi {$newMb} (Hemat {$res['saved_kb']} KB)!");
        }

        return redirect()->back()->with('success', 'Berkas sudah dalam tingkat kompresi paling optimal.');
    }

    /**
     * Kompresi Semua Aset Milik Creator Ini
     */
    public function compressAll(Request $request, $id)
    {
        $user = User::with(['creatorProfile', 'products'])->findOrFail($id);

        $creatorProfile = $user->creatorProfile;
        $bioBlocks = $creatorProfile ? CreatorBioBlock::where('creator_id', $creatorProfile->id)->get() : collect();

        $filePaths = [];
        if (!empty($user->avatar)) {
            $filePaths[] = $user->avatar;
        }
        if ($creatorProfile) {
            if (!empty($creatorProfile->store_banner_1)) $filePaths[] = $creatorProfile->store_banner_1;
            if (!empty($creatorProfile->store_banner_2)) $filePaths[] = $creatorProfile->store_banner_2;
        }
        foreach ($user->products as $product) {
            if (!empty($product->image)) $filePaths[] = $product->image;
            if (!empty($product->brochure)) $filePaths[] = $product->brochure;
            if (!empty($product->og_image)) $filePaths[] = $product->og_image;
            if (!empty($product->digital_resource)) $filePaths[] = $product->digital_resource;
            if (is_array($product->gallery)) {
                foreach ($product->gallery as $g) {
                    if (!empty($g)) $filePaths[] = $g;
                }
            }
        }
        foreach ($bioBlocks as $block) {
            $json = $block->data_json ?? [];
            if (is_array($json)) {
                foreach (['image', 'thumb', 'pdf_file', 'file', 'file_path', 'avatar'] as $key) {
                    if (!empty($json[$key]) && is_string($json[$key])) {
                        $filePaths[] = $json[$key];
                    }
                }
            }
        }

        $filePaths = array_unique(array_filter($filePaths));
        $totalSavedBytes = 0;
        $compressedCount = 0;

        foreach ($filePaths as $path) {
            $res = self::compressImageFile($path, 70);
            if ($res['success']) {
                $compressedCount++;
                $totalSavedBytes += $res['saved_bytes'];
            }
        }

        $savedMb = round($totalSavedBytes / (1024 * 1024), 2);
        $savedKb = round($totalSavedBytes / 1024, 1);
        $savedText = $savedMb > 0 ? "{$savedMb} MB" : "{$savedKb} KB";

        return redirect()->back()->with('success', "Proses kompresi maksimal selesai! Berhasil memproses {$compressedCount} berkas media. Total ruang terhemat: {$savedText}.");
    }

    /**
     * Pindai & Deteksi Berkas Yatim / Sampah (Orphan Files) di storage
     */
    public static function getOrphanFiles(): array
    {
        $usedPaths = [];

        // Users avatar
        foreach (User::whereNotNull('avatar')->pluck('avatar') as $av) {
            if ($av) $usedPaths[] = preg_replace('#^/?(storage/)?#i', '', $av);
        }

        // Banners
        foreach (CreatorProfile::all() as $cp) {
            if ($cp->store_banner_1) $usedPaths[] = preg_replace('#^/?(storage/)?#i', '', $cp->store_banner_1);
            if ($cp->store_banner_2) $usedPaths[] = preg_replace('#^/?(storage/)?#i', '', $cp->store_banner_2);
        }

        // Products
        foreach (Product::all() as $p) {
            if ($p->image) $usedPaths[] = preg_replace('#^/?(storage/)?#i', '', $p->image);
            if ($p->brochure) $usedPaths[] = preg_replace('#^/?(storage/)?#i', '', $p->brochure);
            if ($p->og_image) $usedPaths[] = preg_replace('#^/?(storage/)?#i', '', $p->og_image);
            if ($p->digital_resource) $usedPaths[] = preg_replace('#^/?(storage/)?#i', '', $p->digital_resource);
            if (is_array($p->gallery)) {
                foreach ($p->gallery as $g) {
                    if ($g) $usedPaths[] = preg_replace('#^/?(storage/)?#i', '', $g);
                }
            }
        }

        // Bio Blocks
        foreach (CreatorBioBlock::all() as $b) {
            $json = $b->data_json ?? [];
            if (is_array($json)) {
                foreach (['image', 'thumb', 'pdf_file', 'file', 'file_path', 'avatar'] as $key) {
                    if (!empty($json[$key]) && is_string($json[$key])) {
                        $usedPaths[] = preg_replace('#^/?(storage/)?#i', '', $json[$key]);
                    }
                }
            }
        }

        $usedPathsSet = array_flip(array_unique(array_filter($usedPaths)));

        $allPhysicalFiles = Storage::disk('public')->allFiles();
        $orphanFiles = [];
        $totalOrphanBytes = 0;

        foreach ($allPhysicalFiles as $file) {
            if (Str::startsWith($file, ['settings/', '.git', 'system/']) || basename($file) === '.gitignore') {
                continue;
            }

            if (!isset($usedPathsSet[$file])) {
                $size = Storage::disk('public')->size($file);
                $orphanFiles[] = [
                    'path' => $file,
                    'size' => $size,
                ];
                $totalOrphanBytes += $size;
            }
        }

        return [
            'files'       => $orphanFiles,
            'count'       => count($orphanFiles),
            'total_bytes' => $totalOrphanBytes,
            'total_mb'    => round($totalOrphanBytes / (1024 * 1024), 2),
        ];
    }

    /**
     * Hapus Seluruh Berkas Sampah Terbuang (Orphan / Ghost Files)
     */
    public function cleanOrphans(Request $request)
    {
        $scan = self::getOrphanFiles();
        $deletedCount = 0;
        $deletedBytes = 0;

        foreach ($scan['files'] as $orphan) {
            if (Storage::disk('public')->exists($orphan['path'])) {
                Storage::disk('public')->delete($orphan['path']);
                $deletedCount++;
                $deletedBytes += $orphan['size'];
            }
        }

        $freedMb = round($deletedBytes / (1024 * 1024), 2);
        $freedText = $freedMb > 0 ? "{$freedMb} MB" : round($deletedBytes / 1024, 1) . " KB";

        return redirect()->back()->with('success', "Pembersihan berkas sampah berhasil! Berhasil menghapus {$deletedCount} berkas tak terpakai dan membebaskan {$freedText} disk server.");
    }
}


