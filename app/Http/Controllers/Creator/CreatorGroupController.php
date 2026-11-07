<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorProductGroup;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreatorGroupController extends Controller
{
    public function index()
    {
        $groups = CreatorProductGroup::where('seller_id', auth()->id())
            ->orderBy('order')
            ->get();
        
        return view('creator.groups.index', compact('groups'));
    }

    public function create()
    {
        $sellerId = auth()->id();
        $products = Product::where(function($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
                if (Product::where('seller_id', $sellerId)->count() === 0) {
                    $q->orWhereNull('seller_id');
                }
            })
            ->where('is_active', true)
            ->with('creatorGroup:id,name')
            ->orderBy('name')
            ->get();

        $selectedProductIds = [];
        return view('creator.groups.create', compact('products', 'selectedProductIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:2000',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $slug = Str::slug($request->name);
        // Ensure slug is unique per seller
        $baseSlug = $slug;
        $i = 1;
        while(CreatorProductGroup::where('seller_id', auth()->id())->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        $group = CreatorProductGroup::create([
            'seller_id' => auth()->id(),
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $selectedIds = $request->input('product_ids', []);
        if (!empty($selectedIds)) {
            $sellerId = auth()->id();
            Product::where(function($q) use ($sellerId) {
                    $q->where('seller_id', $sellerId);
                    if (Product::where('seller_id', $sellerId)->count() === 0) {
                        $q->orWhereNull('seller_id');
                    }
                })
                ->whereIn('id', $selectedIds)
                ->update(['creator_group_id' => $group->id]);
        }

        return redirect()->route('creator.groups.index')->with('success', 'Kelompok produk berhasil ditambahkan!');
    }

    public function edit(CreatorProductGroup $group)
    {
        if ($group->seller_id !== auth()->id()) abort(403);
        
        $sellerId = auth()->id();
        $products = Product::where(function($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
                if (Product::where('seller_id', $sellerId)->count() === 0) {
                    $q->orWhereNull('seller_id');
                }
            })
            ->where('is_active', true)
            ->with('creatorGroup:id,name')
            ->orderBy('name')
            ->get();

        $selectedProductIds = $group->products()->pluck('id')->toArray();

        return view('creator.groups.edit', compact('group', 'products', 'selectedProductIds'));
    }

    public function update(Request $request, CreatorProductGroup $group)
    {
        if ($group->seller_id !== auth()->id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:100',
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:2000',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $group->name = $request->name;
        $group->description = $request->description;
        $group->order = $request->order ?? 0;
        $group->is_active = $request->boolean('is_active', true);
        
        // Update slug only if name changes
        if ($group->isDirty('name')) {
            $slug = Str::slug($request->name);
            $baseSlug = $slug;
            $i = 1;
            while(CreatorProductGroup::where('seller_id', auth()->id())->where('slug', $slug)->where('id', '!=', $group->id)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            $group->slug = $slug;
        }

        $group->save();

        $selectedIds = $request->input('product_ids', []);
        $sellerId = auth()->id();

        // 1. Lepas produk yang sebelumnya ada di kelompok ini tapi tidak lagi dicentang
        Product::where(function($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
                if (Product::where('seller_id', $sellerId)->count() === 0) {
                    $q->orWhereNull('seller_id');
                }
            })
            ->where('creator_group_id', $group->id)
            ->whereNotIn('id', $selectedIds)
            ->update(['creator_group_id' => null]);

        // 2. Terapkan kelompok ini pada produk yang baru dicentang
        if (!empty($selectedIds)) {
            Product::where(function($q) use ($sellerId) {
                    $q->where('seller_id', $sellerId);
                    if (Product::where('seller_id', $sellerId)->count() === 0) {
                        $q->orWhereNull('seller_id');
                    }
                })
                ->whereIn('id', $selectedIds)
                ->update(['creator_group_id' => $group->id]);
        }

        return redirect()->route('creator.groups.index')->with('success', 'Kelompok produk berhasil diperbarui!');
    }

    public function destroy(CreatorProductGroup $group)
    {
        if ($group->seller_id !== auth()->id()) abort(403);
        
        // Prevent deleting if it has products? We used nullOnDelete in DB, so it's safe to delete.
        $group->delete();

        return redirect()->route('creator.groups.index')->with('success', 'Kelompok produk berhasil dihapus!');
    }
}
