<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Creator\StoreProductRequest;
use App\Http\Requests\Creator\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\DigitalLinkValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerProductController extends Controller
{
    use \App\Http\Controllers\Admin\HandlesImageUpload;

    public function __construct(private readonly DigitalLinkValidator $linkValidator) {}

    /**
     * Daftar produk milik seller ini.
     */
    public function index(Request $request)
    {
        $seller = auth()->user();

        $products = Product::where('seller_id', $seller->id)
            ->with('category:id,name')
            ->when($request->q, fn($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->when($request->status, fn($q, $s) => $q->where('is_active', $s === 'active'))
            ->latest()
            ->simplePaginate(15)
            ->withQueryString();

        return view('creator.products.index', compact('products'));
    }

    /**
     * Form tambah produk baru.
     */
    public function create()
    {
        $groups         = \App\Models\CreatorProductGroup::where('seller_id', auth()->id())->where('is_active', true)->orderBy('order')->get(['id', 'name']);
        $categories     = ProductCategory::orderBy('name')->get(['id', 'name']);
        $allowedDomains = DigitalLinkValidator::getAllowedDomains();

        return view('creator.products.create', compact('groups', 'categories', 'allowedDomains'));
    }

    /**
     * Simpan produk baru. URL divalidasi otomatis via StoreProductRequest.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // Handle thumbnail upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $data['image'] = $this->storeWebP($file, 'products', 1200, 1200, 85);
            }
        }

        // Produk digital buyle.id selalu external_link
        $data['seller_id']    = auth()->id();
        $data['product_type'] = 'external_link';
        $data['slug']         = Str::slug($data['name']);

        $product = Product::create($data);

        // Invalidate cache katalog
        Cache::forget('catalog_main');
        Cache::forget("seller_products_{$product->seller_id}");

        return redirect()
            ->route('creator.products.index')
            ->with('success', "Produk \"{$product->name}\" berhasil ditambahkan! Link telah diverifikasi ✅");
    }

    /**
     * Detail produk (untuk preview seller).
     */
    public function show(Product $product)
    {
        $this->authorizeProduct($product);
        return view('creator.products.show', compact('product'));
    }

    /**
     * Form edit produk.
     */
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);
        $groups         = \App\Models\CreatorProductGroup::where('seller_id', auth()->id())->where('is_active', true)->orderBy('order')->get(['id', 'name']);
        $categories     = ProductCategory::orderBy('name')->get(['id', 'name']);
        $allowedDomains = DigitalLinkValidator::getAllowedDomains();

        return view('creator.products.edit', compact('product', 'groups', 'categories', 'allowedDomains'));
    }

    /**
     * Update produk. URL divalidasi ulang jika berubah.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $file = $request->file('image');
            if ($file->isValid()) {
                $data['image'] = $this->storeWebP($file, 'products', 1200, 1200, 85);
            }
        }

        $data['product_type'] = 'external_link'; // Selalu link

        $product->update($data);

        // Invalidate cache
        Cache::forget('catalog_main');
        Cache::forget("seller_products_{$product->seller_id}");
        Cache::forget("product_{$product->id}");

        return redirect()
            ->route('creator.products.edit', $product)
            ->with('success', 'Produk berhasil diperbarui. Link telah diverifikasi ulang ✅');
    }

    /**
     * Hapus produk.
     */
    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        $sellerId = $product->seller_id;

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        Cache::forget('catalog_main');
        Cache::forget("seller_products_{$sellerId}");

        return redirect()
            ->route('creator.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * AJAX endpoint: validasi URL secara real-time sebelum submit form.
     */
    public function validateLink(Request $request)
    {
        $request->validate(['url' => 'required|string|max:2000']);

        $result = $this->linkValidator->validate($request->url);

        return response()->json([
            'valid'   => $result['valid'],
            'message' => $result['valid']
                ? "✅ Link dari domain '{$result['domain']}' aman dan diizinkan."
                : "❌ {$result['reason']}",
            'domain'  => $result['domain'],
        ]);
    }

    /**
     * Pastikan produk milik seller yang sedang login.
     */
    private function authorizeProduct(Product $product): void
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }
    }
}
