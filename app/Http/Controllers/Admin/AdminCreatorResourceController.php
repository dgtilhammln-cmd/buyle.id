<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\CreatorProfile;
use App\Models\CreatorBioBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCreatorResourceController extends Controller
{
    /**
     * Tampilkan daftar audit penggunaan resource & storage creator.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $sortBy = $request->input('sort_by', 'storage_desc');

        // Ambil semua pengguna bertipe creator atau yang memiliki profil creator / produk
        $query = User::query()
            ->with(['creatorProfile', 'products'])
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

        // Hitung rincian resource untuk setiap creator
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

            // 1. Avatar User
            if (!empty($user->avatar)) {
                $filePaths[] = $user->avatar;
            }

            // 2. Banner Creator Profile
            if ($creatorProfile) {
                if (!empty($creatorProfile->store_banner_1)) {
                    $filePaths[] = $creatorProfile->store_banner_1;
                }
                if (!empty($creatorProfile->store_banner_2)) {
                    $filePaths[] = $creatorProfile->store_banner_2;
                }
            }

            // 3. Berkas Produk (gambar, brosur, og_image, galeri, digital resource)
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

            // 4. Berkas Bio Block
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

            // Bersihkan duplikat & normalisasi path
            $filePaths = array_unique(array_filter($filePaths));

            $totalSizeBytes = 0;
            $existingFileCount = 0;

            foreach ($filePaths as $path) {
                // Hapus prefix '/storage/' atau 'storage/' jika ada
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

            return [
                'user'               => $user,
                'creator_profile'    => $creatorProfile,
                'product_count'      => $productCount,
                'bio_blocks_count'   => $bioBlocksCount,
                'asset_file_count'   => $existingFileCount,
                'total_size_bytes'   => $totalSizeBytes,
                'total_size_mb'      => $totalSizeMb,
            ];
        });

        // Pengurutan (Sorting)
        if ($sortBy === 'storage_desc') {
            $creatorResources = $creatorResources->sortByDesc('total_size_bytes');
        } elseif ($sortBy === 'storage_asc') {
            $creatorResources = $creatorResources->sortBy('total_size_bytes');
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
        $totalProductsOverall      = $creatorResources->sum('product_count');
        $totalBlocksOverall        = $creatorResources->sum('bio_blocks_count');
        $totalAssetsOverall        = $creatorResources->sum('asset_file_count');

        return view('admin.creator-resources.index', [
            'creatorResources'        => $creatorResources,
            'search'                  => $search,
            'sortBy'                  => $sortBy,
            'totalStorageMbOverall'   => $totalStorageMbOverall,
            'totalProductsOverall'     => $totalProductsOverall,
            'totalBlocksOverall'       => $totalBlocksOverall,
            'totalAssetsOverall'       => $totalAssetsOverall,
        ]);
    }
}
