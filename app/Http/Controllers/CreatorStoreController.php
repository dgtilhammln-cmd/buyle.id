<?php

namespace App\Http\Controllers;

use App\Models\CreatorProfile;
use App\Models\Product;
use Illuminate\Http\Request;

class CreatorStoreController extends Controller
{
    public function show(Request $request, $slug)
    {
        // 1. Cari profil creator berdasarkan slug
        $profile = CreatorProfile::where('store_slug', $slug)
            ->with(['user'])
            ->firstOrFail();

        $seller = $profile->user;
        if (!$seller || $seller->role !== 'seller') {
            abort(404);
        }

        // 2. Ambil grup produk yang aktif untuk filter kategori
        $groups = \App\Models\CreatorProductGroup::where('seller_id', $seller->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // 3. Ambil produk berdasarkan grup (atau semua)
        $query = Product::where('seller_id', $seller->id)
            ->where('is_active', true);

        if ($request->filled('group')) {
            $groupSlug = $request->group;
            $group = $groups->firstWhere('slug', $groupSlug);
            if ($group) {
                $query->where('creator_group_id', $group->id);
            }
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $seo = [
            'title'       => $profile->meta_title ?: ($profile->store_name . ' | buyle.id'),
            'description' => $profile->meta_desc ?: $profile->store_description,
            'keywords'    => $profile->meta_keywords,
            'og_image'    => $seller->avatar ? asset('storage/'.$seller->avatar) : asset('images/og-default.jpg'),
            'canonical'   => route('store.show', ['slug' => $slug]),
        ];

        return view('storefront.show', compact('profile', 'seller', 'groups', 'products', 'seo'));
    }
}
