<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductCategoryController extends Controller
{
    // ─── KATEGORI ────────────────────────────────────

    public function index()
    {
        $produkCats = ProductCategory::withCount('products')
            ->with('subCategories')
            ->where('tab', 'produk')
            ->orderBy('order')
            ->get();

        $jasaCats = ProductCategory::withCount('products')
            ->with('subCategories')
            ->where('tab', 'jasa')
            ->orderBy('order')
            ->get();

        return view('admin.product-categories.index', compact('produkCats', 'jasaCats'));
    }

    public function create()
    {
        return view('admin.product-categories.form', ['category' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'slug'        => 'nullable|string|max:120|unique:product_categories,slug',
            'tab'         => 'required|in:produk,jasa',
            'badge'       => 'nullable|in:terpopuler,naik-daun',
            'description' => 'nullable|string|max:255',
            'order'       => 'integer|min:0',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);

        ProductCategory::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'tab'         => $request->tab,
            'badge'       => $request->badge ?: null,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
            'order'       => $request->input('order', 0),
        ]);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(ProductCategory $productCategory)
    {
        $productCategory->load('subCategories');
        return view('admin.product-categories.form', ['category' => $productCategory]);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'slug'        => 'nullable|string|max:120|unique:product_categories,slug,' . $productCategory->id,
            'tab'         => 'required|in:produk,jasa',
            'badge'       => 'nullable|in:terpopuler,naik-daun',
            'description' => 'nullable|string|max:255',
            'order'       => 'integer|min:0',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);

        $productCategory->update([
            'name'        => $request->name,
            'slug'        => $slug,
            'tab'         => $request->tab,
            'badge'       => $request->badge ?: null,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
            'order'       => $request->input('order', 0),
        ]);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate(['id' => 'required|integer', 'order' => 'required|integer|min:0']);
        ProductCategory::where('id', $request->id)->update(['order' => $request->order]);
        return response()->json(['success' => true]);
    }

    // ─── SUB-KATEGORI ────────────────────────────────

    public function storeSub(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'slug'          => 'nullable|string|max:120|unique:product_sub_categories,slug',
            'description'   => 'nullable|string|max:255',
            'contoh_produk' => 'nullable|string|max:255',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);

        // auto-order: last + 1
        $lastOrder = $productCategory->subCategories()->max('order') ?? 0;

        $productCategory->subCategories()->create([
            'name'          => $request->name,
            'slug'          => $slug,
            'description'   => $request->description,
            'contoh_produk' => $request->contoh_produk,
            'order'         => $lastOrder + 1,
            'is_active'     => true,
        ]);

        return back()->with('success', 'Sub-kategori berhasil ditambahkan.');
    }

    public function updateSub(Request $request, ProductSubCategory $sub)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'slug'          => 'nullable|string|max:120|unique:product_sub_categories,slug,' . $sub->id,
            'description'   => 'nullable|string|max:255',
            'contoh_produk' => 'nullable|string|max:255',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);

        $sub->update([
            'name'          => $request->name,
            'slug'          => $slug,
            'description'   => $request->description,
            'contoh_produk' => $request->contoh_produk,
        ]);

        return back()->with('success', 'Sub-kategori berhasil diperbarui.');
    }

    public function destroySub(ProductSubCategory $sub)
    {
        $sub->delete();
        return back()->with('success', 'Sub-kategori berhasil dihapus.');
    }
}
