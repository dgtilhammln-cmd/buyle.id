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
     * Tampilkan daftar audit penggunaan resource & storage creator.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $sortBy = $request->input('sort_by', 'storage_desc');

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
                  ->orWhereHas('creatorProfile', function ($cp) use ($search) {
                      $cp->where('store_name', 'like', "%{$search}%")
                         ->where('store_slug', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->get();

        // Hitung rincian resource & revenue untuk setiap creator
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

            // Format Avatar URL
            $avatarUrl = self::getStorageUrl($user->avatar);

            return [
                'user'               => $user,
                'creator_profile'    => $creatorProfile,
                'avatar_url'         => $avatarUrl,
                'product_count'      => $productCount,
                'bio_blocks_count'   => $bioBlocksCount,
                'asset_file_count'   => $existingFileCount,
                'total_size_bytes'   => $totalSizeBytes,
                'total_size_mb'      => $totalSizeMb,
                'est_monthly_cost'   => $estMonthlyCost,
                'total_revenue'      => $totalRevenue,
            ];
        });

        // Sorting
        if ($sortBy === 'storage_desc') {
            $creatorResources = $creatorResources->sortByDesc('total_size_bytes');
        } elseif ($sortBy === 'storage_asc') {
            $creatorResources = $creatorResources->sortBy('total_size_bytes');
        } elseif ($sortBy === 'revenue_desc') {
            $creatorResources = $creatorResources->sortByDesc('total_revenue');
        } elseif ($sortBy === 'products_desc') {
            $creatorResources = $creatorResources->sortByDesc('product_count');
        } elseif ($sortBy === 'blocks_desc') {
            $creatorResources = $creatorResources->sortByDesc('bio_blocks_count');
        } elseif ($sortBy === 'assets_desc') {
            $creatorResources = $creatorResources->sortByDesc('asset_file_count');
        }

        // Statistik Keseluruhan
        $totalStorageBytesOverall = $creatorResources->sum('total_size_bytes');
        $totalStorageMbOverall    = round($totalStorageBytesOverall / (1024 * 1024), 2);
        $totalRevenueOverall      = $creatorResources->sum('total_revenue');
        $totalProductsOverall      = $creatorResources->sum('product_count');
        $totalBlocksOverall        = $creatorResources->sum('bio_blocks_count');
        $totalAssetsOverall        = $creatorResources->sum('asset_file_count');

        return view('admin.creator-resources.index', [
            'creatorResources'        => $creatorResources,
            'search'                  => $search,
            'sortBy'                  => $sortBy,
            'totalStorageMbOverall'   => $totalStorageMbOverall,
            'totalRevenueOverall'     => $totalRevenueOverall,
            'totalProductsOverall'     => $totalProductsOverall,
            'totalBlocksOverall'       => $totalBlocksOverall,
            'totalAssetsOverall'       => $totalAssetsOverall,
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
}
