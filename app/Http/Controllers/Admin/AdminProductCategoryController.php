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

        $eventCats = ProductCategory::withCount('products')
            ->with('subCategories')
            ->where('tab', 'event')
            ->orderBy('order')
            ->get();

        return view('admin.product-categories.index', compact('produkCats', 'jasaCats', 'eventCats'));
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
            'tab'         => 'required|in:produk,jasa,event',
            'badge'       => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'icon_type'   => 'nullable|in:icon,upload',
            'icon_value'  => 'nullable|string',
            'icon_upload' => 'nullable|image|max:1024',
            'description' => 'nullable|string|max:255',
            'order'       => 'integer|min:0',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);

        $iconValue = $request->icon_value;
        if ($request->icon_type === 'upload' && $request->hasFile('icon_upload')) {
            $iconValue = $request->file('icon_upload')->store('category-icons', 'public');
        }

        ProductCategory::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'tab'         => $request->tab,
            'badge'       => $request->badge ?: null,
            'badge_color' => $request->badge_color ?: null,
            'icon_type'   => $request->icon_type ?: 'icon',
            'icon_value'  => $iconValue,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
            'order'       => $request->input('order', 0),
        ]);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    private function resolveCategory($cat): ProductCategory
    {
        if ($cat instanceof ProductCategory) {
            return $cat;
        }
        return ProductCategory::where('slug', $cat)->orWhere('id', $cat)->firstOrFail();
    }

    public function edit($productCategory)
    {
        $productCategory = $this->resolveCategory($productCategory);
        $productCategory->load('subCategories');
        return view('admin.product-categories.form', ['category' => $productCategory]);
    }

    public function update(Request $request, $productCategory)
    {
        $productCategory = $this->resolveCategory($productCategory);
        $request->validate([
            'name'        => 'required|string|max:100',
            'slug'        => 'nullable|string|max:120|unique:product_categories,slug,' . $productCategory->id,
            'tab'         => 'required|in:produk,jasa,event',
            'badge'       => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'icon_type'   => 'nullable|in:icon,upload',
            'icon_value'  => 'nullable|string',
            'icon_upload' => 'nullable|image|max:2048',
            'description' => 'nullable|string|max:255',
            'order'       => 'integer|min:0',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);

        $iconValue = $request->icon_value;
        if ($request->icon_type === 'upload') {
            if ($request->hasFile('icon_upload')) {
                $iconValue = $request->file('icon_upload')->store('category-icons', 'public');
            } else {
                $iconValue = $productCategory->icon_value;
            }
        }

        $productCategory->update([
            'name'        => $request->name,
            'slug'        => $slug,
            'tab'         => $request->tab,
            'badge'       => $request->badge ?: null,
            'badge_color' => $request->badge_color ?: null,
            'icon_type'   => $request->icon_type ?: 'icon',
            'icon_value'  => $iconValue,
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
