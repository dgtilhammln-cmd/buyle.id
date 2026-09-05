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
        $categories     = ProductCategory::with('subCategories:id,category_id,name')->orderBy('name')->get(['id', 'name']);
        $allowedDomains = DigitalLinkValidator::getAllowedDomains();

        return view('creator.products.create', compact('groups', 'categories', 'allowedDomains'));
    }

    /**
     * Simpan produk baru. URL divalidasi otomatis via StoreProductRequest.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // Handle thumbnail (wajib saat create)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle gallery (opsional, maks 6)
        unset($data['gallery']);
        $galleryFiles = collect($request->file('gallery', []))->filter(fn($f) => $f && $f->isValid());
        if ($galleryFiles->isNotEmpty()) {
            $galleryPaths = [];
            foreach ($galleryFiles as $gFile) {
                $galleryPaths[] = $gFile->store('products/gallery', 'public');
            }
            $data['gallery'] = $galleryPaths;
        } else {
            $data['gallery'] = [];
        }

        // Handle White Label status
        $data['is_whitelabel'] = $request->boolean('is_whitelabel');
        $data['whitelabel_approval_status'] = $data['is_whitelabel'] ? 'pending' : 'none';

        // Produk type: ticket atau external_link
        $data['seller_id']    = auth()->id();
        $data['product_type'] = $request->input('product_type') === 'ticket' ? 'ticket' : 'external_link';
        $data['stock']        = $data['stock'] ?? 0;
        
        // Generate unique slug
        $slug = Str::slug($data['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (\App\Models\Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $data['slug'] = $slug;

        $product = Product::create($data);

        // Invalidate cache katalog
        Cache::forget('catalog_main');
        Cache::forget("seller_products_{$product->seller_id}");

        $msg = "Produk \"{$product->name}\" berhasil ditambahkan. Link telah diverifikasi.";
        if ($product->is_whitelabel) {
            $msg .= " Produk ini telah dikirim ke Tim Buyle untuk proses persetujuan (approval) White Label.";
        }

        return redirect()
            ->route('creator.products.index')
            ->with('success', $msg);
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
        $categories     = ProductCategory::with('subCategories:id,category_id,name')->orderBy('name')->get(['id', 'name']);
        $allowedDomains = DigitalLinkValidator::getAllowedDomains();

        return view('creator.products.edit', compact('product', 'groups', 'categories', 'allowedDomains'));
    }

    /**
     * Update produk. URL divalidasi ulang jika berubah.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        // Handle thumbnail upload (jika ada file baru)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle gallery upload (jika ada file baru)
        unset($data['gallery']);
        $galleryFiles = collect($request->file('gallery', []))->filter(fn($f) => $f && $f->isValid());
        if ($galleryFiles->isNotEmpty()) {
            if (is_array($product->gallery)) {
                foreach ($product->gallery as $oldImg) {
                    Storage::disk('public')->delete($oldImg);
                }
            }

            $galleryPaths = [];
            foreach ($galleryFiles as $gFile) {
                $galleryPaths[] = $gFile->store('products/gallery', 'public');
            }
            $data['gallery'] = $galleryPaths;
        }

        $data['product_type'] = $request->input('product_type') === 'ticket' ? 'ticket' : ($product->product_type === 'ticket' ? 'ticket' : 'external_link');
        $data['stock']        = $data['stock'] ?? 0;

        $isWhitelabel = $request->boolean('is_whitelabel');
        $data['is_whitelabel'] = $isWhitelabel;
        if ($isWhitelabel) {
            if ($product->whitelabel_approval_status === 'none' || $product->whitelabel_approval_status === 'rejected' || !$product->is_whitelabel) {
                $data['whitelabel_approval_status'] = 'pending';
            }
        } else {
            $data['whitelabel_approval_status'] = 'none';
        }

        $product->update($data);

        // Invalidate cache
        Cache::forget('catalog_main');
        Cache::forget("seller_products_{$product->seller_id}");
        Cache::forget("product_{$product->id}");

        $msg = 'Produk berhasil diperbarui. Link telah diverifikasi ulang.';
        if ($isWhitelabel && $product->whitelabel_approval_status === 'pending') {
            $msg .= ' Status White Label diajukan untuk ditinjau oleh Admin Tim Buyle.';
        }

        return redirect()
            ->route('creator.products.edit', $product)
            ->with('success', $msg);
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
     * Update stok produk (quick form / quick update).
     */
    public function updateStock(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => $request->stock]);

        Cache::forget('catalog_main');
        Cache::forget("seller_products_{$product->seller_id}");
        Cache::forget("product_{$product->id}");

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'stock' => $product->stock]);
        }

        return redirect()->back()->with('success', 'Stok berhasil diperbarui.');
    }

    /**
     * Update urutan produk (quick form / quick update).
     */
    public function updateOrder(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'order' => 'required|integer|min:0',
        ]);

        $product->update(['order' => $request->order]);

        Cache::forget('catalog_main');
        Cache::forget("seller_products_{$product->seller_id}");
        Cache::forget("product_{$product->id}");

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'order' => $product->order]);
        }

        return redirect()->back()->with('success', 'Urutan berhasil diperbarui.');
    }

    /**
     * Pastikan produk milik seller yang sedang login.
     */
    private function authorizeProduct(Product $product): void
    {
        if (auth()->user()->role === 'admin') {
            return;
        }

        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }
    }
}
