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

        $search = $request->input('search', $request->input('q'));
        $status = $request->input('status');
        $type   = $request->input('type');

        $baseQuery = Product::where('seller_id', $seller->id);
        $totalCount    = (clone $baseQuery)->count();
        $activeCount   = (clone $baseQuery)->where('is_active', true)->count();
        $archivedCount = (clone $baseQuery)->where('is_active', false)->count();

        $products = Product::where('seller_id', $seller->id)
            ->with('category:id,name', 'variantOptions.values')
            ->when($search, function($q, $s) {
                $q->where(function($sub) use ($s) {
                    $sub->where('name', 'like', "%{$s}%")
                        ->orWhere('slug', 'like', "%{$s}%")
                        ->orWhere('short_desc', 'like', "%{$s}%");
                });
            })
            ->when($status === 'active', fn($q) => $q->where('is_active', true))
            ->when($status === 'archived', fn($q) => $q->where('is_active', false))
            ->when($type, fn($q, $t) => $q->where('product_type', $t))
            ->orderBy('order', 'asc')
            ->latest('id')
            ->paginate(50)
            ->withQueryString();

        return view('creator.products.index', compact('products', 'totalCount', 'activeCount', 'archivedCount'));
    }

    /**
     * Form tambah produk baru.
     */
    public function create()
    {
        $groups         = \App\Models\CreatorProductGroup::where('seller_id', auth()->id())->where('is_active', true)->orderBy('order')->get(['id', 'name']);
        $categories     = ProductCategory::where('is_active', true)->with('subCategories:id,category_id,name')->orderBy('order', 'asc')->orderBy('name', 'asc')->get(['id', 'name', 'tab']);
        $allowedDomains = DigitalLinkValidator::getAllowedDomains();

        return view('creator.products.create', compact('groups', 'categories', 'allowedDomains'));
    }

    /**
     * Simpan produk baru. URL divalidasi otomatis via StoreProductRequest.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // Handle thumbnail
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } elseif (!empty($request->input('scraped_image_url'))) {
            $scrapedUrl = $request->input('scraped_image_url');
            // Auto-download external image to local storage
            if (str_starts_with($scrapedUrl, 'http')) {
                $data['image'] = \App\Services\ImageDownloader::downloadAndCompress($scrapedUrl, 'products');
            } else {
                $data['image'] = $scrapedUrl;
            }
        }

        // Handle poster landscape (og_image)
        if ($request->hasFile('og_image') && $request->file('og_image')->isValid()) {
            $data['og_image'] = $request->file('og_image')->store('products/posters', 'public');
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
        } elseif (!empty($request->input('scraped_gallery_urls'))) {
            $scrapedGalleries = json_decode($request->input('scraped_gallery_urls'), true);
            if (is_array($scrapedGalleries)) {
                // Auto-download each gallery image to local storage
                $downloadedGallery = [];
                foreach (array_slice($scrapedGalleries, 0, 6) as $gUrl) {
                    if (str_starts_with((string)$gUrl, 'http')) {
                        $downloadedGallery[] = \App\Services\ImageDownloader::downloadAndCompress($gUrl, 'products/gallery');
                    } else {
                        $downloadedGallery[] = $gUrl;
                    }
                }
                $data['gallery'] = $downloadedGallery;
            } else {
                $data['gallery'] = [];
            }
        } else {
            $data['gallery'] = [];
        }

        // Produk type: ticket, physical, makanan, service, atau external_link
        $data['seller_id']    = auth()->id();
        $pt = $request->input('product_type');
        $validTypes = ['digital', 'physical', 'makanan', 'service', 'ticket', 'external_link'];
        $data['product_type'] = in_array($pt, $validTypes) ? $pt : 'physical';

        if ($data['product_type'] === 'ticket' && !auth()->user()->isKycVerified()) {
            return redirect()->to(route('ticket.scanner', ['tab' => 'settings']))
                ->with('error', 'Untuk membuat produk tipe Tiket Event, akun Creator Anda wajib terverifikasi e-KYC terlebih dahulu.');
        }

        $data['stock']        = ($request->has('stock') && $request->input('stock') !== null && $request->input('stock') !== '') ? (int) $request->input('stock') : null;
        $data['weight']       = (int) ($data['weight'] ?? 0);
        $data['length']       = (float) ($data['length'] ?? 0);
        $data['width']        = (float) ($data['width'] ?? 0);
        $data['height']       = (float) ($data['height'] ?? 0);
        $data['volume']       = (float) ($data['volume'] ?? 0);

        if (empty($data['short_desc'])) {
            $data['short_desc'] = Str::limit(strip_tags($data['description'] ?? $data['name']), 150);
        }

        // Handle Exclusive Creator custom rating & sold_count
        if (auth()->user()->creatorProfile?->is_exclusive) {
            if ($request->has('rating') && $request->input('rating') !== null && $request->input('rating') !== '') {
                $data['rating'] = (float) $request->input('rating');
            }
            if ($request->has('sold_count') && $request->input('sold_count') !== null && $request->input('sold_count') !== '') {
                $data['sold_count'] = (int) $request->input('sold_count');
            }
        }

        // Handle White Label status (hanya untuk produk non-tiket)
        if ($data['product_type'] === 'ticket') {
            $data['is_whitelabel'] = false;
            $data['whitelabel_approval_status'] = 'none';
        } else {
            $data['is_whitelabel'] = $request->boolean('is_whitelabel');
            $data['whitelabel_approval_status'] = $data['is_whitelabel'] ? 'pending' : 'none';
        }

        // Handle Pricing Type & Prices
        $data['pricing_type'] = $data['pricing_type'] ?? 'fixed';
        if ($data['pricing_type'] === 'free') {
            $data['price'] = 0;
            $data['sale_price'] = null;
        } elseif ($data['pricing_type'] === 'traktir') {
            $data['price'] = (float)($data['traktir_unit_price'] ?? 10000);
            $data['sale_price'] = null;
        } elseif ($data['pricing_type'] === 'pay_what_you_want') {
            $data['price'] = (float)($data['pwyw_min_price'] ?? 0);
            $data['sale_price'] = null;
        }

        // Handle Syarat Klaim Produk Gratis (hanya aktif jika harga = 0 atau tipe gratis)
        $effectivePrice = isset($data['sale_price']) && $data['sale_price'] !== null && $data['sale_price'] !== ''
            ? (float) $data['sale_price']
            : (float) ($data['price'] ?? 0);

        if ($data['pricing_type'] === 'free' || $effectivePrice <= 0) {
            $data['require_follow_join'] = $request->boolean('require_follow_join');
            $data['social_follow_url']   = $data['require_follow_join'] ? $request->input('social_follow_url') : null;
            $data['community_join_url']  = $data['require_follow_join'] ? $request->input('community_join_url') : null;
        } else {
            $data['require_follow_join'] = false;
            $data['social_follow_url']   = null;
            $data['community_join_url']  = null;
        }

        // Generate unique slug
        $slug = Str::slug($data['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (\App\Models\Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $data['slug'] = $slug;

        // Safety: Truncate fields yang mungkin melebihi batas kolom DB
        // (migration fix mengubah ke TEXT, ini sebagai defense-in-depth)
        if (!empty($data['meta_keywords']) && strlen($data['meta_keywords']) > 5000) {
            $data['meta_keywords'] = substr($data['meta_keywords'], 0, 5000);
        }
        if (!empty($data['tags']) && strlen($data['tags']) > 5000) {
            $data['tags'] = substr($data['tags'], 0, 5000);
        }
        if (!empty($data['meta_title']) && strlen($data['meta_title']) > 500) {
            $data['meta_title'] = substr($data['meta_title'], 0, 500);
        }
        if (!empty($data['short_desc']) && strlen($data['short_desc']) > 500) {
            $data['short_desc'] = substr($data['short_desc'], 0, 500);
        }

        $product = Product::create($data);

        // Simpan variasi produk (jika ada)
        $this->saveProductVariants($product, $request);

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
        $product->load(['variantOptions.values']);
        $groups         = \App\Models\CreatorProductGroup::where('seller_id', auth()->id())->where('is_active', true)->orderBy('order')->get(['id', 'name']);
        $categories     = ProductCategory::where('is_active', true)->with('subCategories:id,category_id,name')->orderBy('order', 'asc')->orderBy('name', 'asc')->get(['id', 'name', 'tab']);
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

        // Handle poster landscape (og_image)
        if ($request->hasFile('og_image') && $request->file('og_image')->isValid()) {
            if ($product->og_image) {
                Storage::disk('public')->delete($product->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('products/posters', 'public');
        }

        // Handle gallery upload & granular existing gallery deletion
        unset($data['gallery']);
        $currentGallery = is_array($product->gallery) ? $product->gallery : [];

        if ($request->has('existing_gallery_submitted')) {
            $keptGallery = $request->input('existing_gallery', []);
            $keptGallery = is_array($keptGallery) ? $keptGallery : [];
            // Delete removed images from storage
            foreach ($currentGallery as $oldImg) {
                if (!in_array($oldImg, $keptGallery)) {
                    Storage::disk('public')->delete($oldImg);
                }
            }
            $finalGallery = array_values($keptGallery);
        } else {
            $finalGallery = $currentGallery;
        }

        $galleryFiles = collect($request->file('gallery', []))->filter(fn($f) => $f && $f->isValid());
        if ($galleryFiles->isNotEmpty()) {
            foreach ($galleryFiles as $gFile) {
                if (count($finalGallery) < 6) {
                    $finalGallery[] = $gFile->store('products/gallery', 'public');
                }
            }
        }
        $data['gallery'] = $finalGallery;

        $pt = $request->input('product_type');
        $validTypes = ['digital', 'physical', 'makanan', 'service', 'ticket', 'external_link'];
        $data['product_type'] = in_array($pt, $validTypes) ? $pt : 'physical';

        if ($data['product_type'] === 'ticket' && !auth()->user()->isKycVerified()) {
            return redirect()->to(route('ticket.scanner', ['tab' => 'settings']))
                ->with('error', 'Untuk memilih produk tipe Tiket Event, akun Creator Anda wajib terverifikasi e-KYC terlebih dahulu.');
        }
        $data['stock']        = ($request->has('stock') && $request->input('stock') !== null && $request->input('stock') !== '') ? (int) $request->input('stock') : null;
        $data['weight']       = (int) ($data['weight'] ?? 0);
        $data['length']       = (float) ($data['length'] ?? 0);
        $data['width']        = (float) ($data['width'] ?? 0);
        $data['height']       = (float) ($data['height'] ?? 0);
        $data['volume']       = (float) ($data['volume'] ?? 0);

        if (empty($data['short_desc'])) {
            $data['short_desc'] = Str::limit(strip_tags($data['description'] ?? $data['name']), 150);
        }

        $isWhitelabel         = false; // Initialize to avoid undefined variable error

        if ($data['product_type'] === 'ticket') {
            $data['is_whitelabel'] = false;
            $data['whitelabel_approval_status'] = 'none';
        } else {
            $isWhitelabel = $request->boolean('is_whitelabel');
            $data['is_whitelabel'] = $isWhitelabel;
            if ($isWhitelabel) {
                if ($product->whitelabel_approval_status === 'none' || $product->whitelabel_approval_status === 'rejected' || !$product->is_whitelabel) {
                    $data['whitelabel_approval_status'] = 'pending';
                }
            } else {
                $data['whitelabel_approval_status'] = 'none';
            }
        }

        // Handle Exclusive Creator custom rating & sold_count
        if (auth()->user()->creatorProfile?->is_exclusive || ($product->seller && $product->seller->creatorProfile?->is_exclusive)) {
            if ($request->has('rating') && $request->input('rating') !== null && $request->input('rating') !== '') {
                $data['rating'] = (float) $request->input('rating');
            }
            if ($request->has('sold_count') && $request->input('sold_count') !== null && $request->input('sold_count') !== '') {
                $data['sold_count'] = (int) $request->input('sold_count');
            }
        }

        // Handle Pricing Type & Prices
        $data['pricing_type'] = $data['pricing_type'] ?? ($product->pricing_type ?? 'fixed');
        if ($data['pricing_type'] === 'free') {
            $data['price'] = 0;
            $data['sale_price'] = null;
        } elseif ($data['pricing_type'] === 'traktir') {
            $data['price'] = (float)($data['traktir_unit_price'] ?? $product->traktir_unit_price ?? 10000);
            $data['sale_price'] = null;
        } elseif ($data['pricing_type'] === 'pay_what_you_want') {
            $data['price'] = (float)($data['pwyw_min_price'] ?? $product->pwyw_min_price ?? 0);
            $data['sale_price'] = null;
        }

        // Handle Syarat Klaim Produk Gratis (hanya aktif jika harga = 0 atau tipe gratis)
        $effectivePrice = isset($data['sale_price']) && $data['sale_price'] !== null && $data['sale_price'] !== ''
            ? (float) $data['sale_price']
            : (float) ($data['price'] ?? $product->price);

        if ($data['pricing_type'] === 'free' || $effectivePrice <= 0) {
            $data['require_follow_join'] = $request->boolean('require_follow_join');
            $data['social_follow_url']   = $data['require_follow_join'] ? $request->input('social_follow_url') : null;
            $data['community_join_url']  = $data['require_follow_join'] ? $request->input('community_join_url') : null;
        } else {
            $data['require_follow_join'] = false;
            $data['social_follow_url']   = null;
            $data['community_join_url']  = null;
        }

        // Safety: Truncate fields yang mungkin melebihi batas kolom DB
        if (!empty($data['meta_keywords']) && strlen($data['meta_keywords']) > 5000) {
            $data['meta_keywords'] = substr($data['meta_keywords'], 0, 5000);
        }
        if (!empty($data['tags']) && strlen($data['tags']) > 5000) {
            $data['tags'] = substr($data['tags'], 0, 5000);
        }
        if (!empty($data['meta_title']) && strlen($data['meta_title']) > 500) {
            $data['meta_title'] = substr($data['meta_title'], 0, 500);
        }
        if (!empty($data['short_desc']) && strlen($data['short_desc']) > 500) {
            $data['short_desc'] = substr($data['short_desc'], 0, 500);
        }

        $product->update($data);

        // Simpan variasi produk (jika ada)
        $this->saveProductVariants($product, $request);

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
     * Toggle status aktif / arsip produk.
     */
    public function toggleStatus(Product $product, Request $request)
    {
        $this->authorizeProduct($product);

        $product->is_active = !$product->is_active;
        $product->save();

        // Sync active state ke bio block jika ada
        \App\Models\CreatorBioBlock::where(function ($q) use ($product) {
            $q->where('data_json->product_id', $product->id)
              ->orWhere('data_json->product_id', (string)$product->id);
        })->update(['is_active' => $product->is_active]);

        Cache::forget('catalog_main');
        Cache::forget('home_page_data_v3');
        Cache::forget("seller_products_{$product->seller_id}");
        Cache::forget("product_{$product->id}");

        $statusText = $product->is_active ? 'diaktifkan' : 'diarsipkan';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $product->is_active,
                'status'    => $product->is_active ? 'active' : 'archived',
                'label'     => $product->is_active ? 'Aktif' : 'Diarsipkan',
                'message'   => "Produk \"{$product->name}\" berhasil {$statusText}.",
            ]);
        }

        return back()->with('success', "Produk \"{$product->name}\" berhasil {$statusText}.");
    }

    /**
     * Simpan variasi produk (berlaku untuk semua tipe produk: digital, tiket, fisik, makanan, jasa).
     */
    protected function saveProductVariants(Product $product, Request $request): void
    {
        $variantsData = $request->input('variants', []);
        if (!is_array($variantsData)) {
            return;
        }

        // Filter variasi yang punya nama dan tidak kosong, maks 20
        $validVariants = [];
        foreach ($variantsData as $index => $v) {
            if (is_array($v) && !empty(trim($v['name'] ?? ''))) {
                $validVariants[$index] = $v;
            }
            if (count($validVariants) >= 20) {
                break;
            }
        }

        if (empty($validVariants)) {
            // Hapus opsi varian jika tidak ada variasi yang dikirim
            $product->variantOptions()->delete();
            return;
        }

        $option = $product->variantOptions()->firstOrCreate(['name' => 'Pilihan Variasi']);

        $existingValueIds = $option->values()->pluck('id')->toArray();
        $keptIds = [];

        foreach ($validVariants as $index => $vData) {
            $valueId = $vData['id'] ?? null;
            $valName = trim($vData['name']);

            // Parse price cleanly (numeric or from Indonesian currency format)
            $rawPrice = $vData['price'] ?? null;
            if ($rawPrice !== null && $rawPrice !== '') {
                $cleanPrice = preg_replace('/[^\d]/', '', (string)$rawPrice);
                $valPrice = $cleanPrice !== '' ? (float)$cleanPrice : (float)$product->price;
            } else {
                $valPrice = (float) $product->price;
            }

            // Parse stock cleanly
            $rawStock = $vData['stock'] ?? null;
            $valStock = ($rawStock !== null && $rawStock !== '') ? (int) $rawStock : null;

            // Optional SKU
            $valSku = !empty($vData['sku']) ? trim($vData['sku']) : null;

            $imagePath = null;
            if ($request->hasFile("variants.{$index}.image") && $request->file("variants.{$index}.image")->isValid()) {
                $imagePath = $request->file("variants.{$index}.image")->store('products/variants', 'public');
            } elseif (!empty($vData['existing_image'])) {
                $imagePath = $vData['existing_image'];
            }

            if ($valueId && in_array($valueId, $existingValueIds)) {
                $valModel = \App\Models\ProductVariantValue::find($valueId);
                if ($valModel) {
                    $updatePayload = [
                        'value' => $valName,
                        'price' => $valPrice,
                        'stock' => $valStock,
                        'sku'   => $valSku,
                    ];
                    if ($imagePath !== null) {
                        $updatePayload['image'] = $imagePath;
                    }
                    $valModel->update($updatePayload);
                    $keptIds[] = $valModel->id;
                }
            } else {
                $newVal = $option->values()->create([
                    'value' => $valName,
                    'price' => $valPrice,
                    'stock' => $valStock,
                    'sku'   => $valSku,
                    'image' => $imagePath,
                ]);
                $keptIds[] = $newVal->id;
            }
        }

        $toDelete = array_diff($existingValueIds, $keptIds);
        if (!empty($toDelete)) {
            \App\Models\ProductVariantValue::whereIn('id', $toDelete)->delete();
        }
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

        // Clean up linked bio blocks
        \App\Models\CreatorBioBlock::where('type', 'buyle_product')
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data_json, '$.product_id')) = ?", [(string)$product->id])
            ->delete();

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
            'stock' => 'nullable|integer|min:0',
        ]);

        $stock = ($request->has('stock') && $request->input('stock') !== null && $request->input('stock') !== '')
            ? (int) $request->input('stock')
            : null;

        $product->update(['stock' => $stock]);

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
     * Hapus semua produk digital milik seller yang sedang login.
     */
    public function destroyAll(Request $request)
    {
        $sellerId = auth()->id();
        $query = Product::where('seller_id', $sellerId);

        if ($request->filled('type')) {
            $type = $request->type;
            if ($type === 'makanan') {
                $query->where(function($q) {
                    $q->where('product_type', 'makanan')
                      ->orWhere('category', 'makanan');
                });
            } else {
                $query->where(function($q) use ($type) {
                    $q->where('product_type', $type)
                      ->orWhere('category', $type);
                });
            }
        }

        $products = $query->get();

        foreach ($products as $product) {
            if ($product->image && !Str::startsWith($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            // Clean up linked bio blocks
            \App\Models\CreatorBioBlock::where('type', 'buyle_product')
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data_json, '$.product_id')) = ?", [(string)$product->id])
                ->delete();

            $product->delete();
        }

        Cache::forget('catalog_main');
        Cache::forget("seller_products_{$sellerId}");

        $msg = $request->filled('type')
            ? 'Berhasil menghapus semua produk tipe ' . $request->type . '!'
            : 'Berhasil menghapus semua produk!';

        return back()->with('success', $msg);
    }

    /**
     * Bulk action untuk produk yang dipilih: hapus, arsip, atau aktifkan.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,archive,activate',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer',
        ]);

        $sellerId = auth()->id();
        $action   = $request->input('action');
        $ids      = $request->input('ids');

        $products = Product::where('seller_id', $sellerId)
            ->whereIn('id', $ids)
            ->get();

        if ($products->isEmpty()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada produk valid yang dipilih.'], 422);
            }
            return back()->with('error', 'Tidak ada produk valid yang dipilih.');
        }

        $count = $products->count();

        if ($action === 'delete') {
            foreach ($products as $product) {
                if ($product->image && !Str::startsWith($product->image, 'http')) {
                    Storage::disk('public')->delete($product->image);
                }
                if ($product->og_image && !Str::startsWith($product->og_image, 'http')) {
                    Storage::disk('public')->delete($product->og_image);
                }
                // Clean up linked bio blocks
                \App\Models\CreatorBioBlock::where('type', 'buyle_product')
                    ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data_json, '$.product_id')) = ?", [(string)$product->id])
                    ->delete();

                $product->delete();
                Cache::forget("product_{$product->id}");
            }
            $msg = "Berhasil menghapus {$count} produk terpilih.";
        } elseif ($action === 'archive') {
            foreach ($products as $product) {
                $product->update(['is_active' => false]);
                \App\Models\CreatorBioBlock::where(function ($q) use ($product) {
                    $q->where('data_json->product_id', $product->id)
                      ->orWhere('data_json->product_id', (string)$product->id);
                })->update(['is_active' => false]);
                Cache::forget("product_{$product->id}");
            }
            $msg = "Berhasil mengarsipkan {$count} produk terpilih.";
        } elseif ($action === 'activate') {
            foreach ($products as $product) {
                $product->update(['is_active' => true]);
                \App\Models\CreatorBioBlock::where(function ($q) use ($product) {
                    $q->where('data_json->product_id', $product->id)
                      ->orWhere('data_json->product_id', (string)$product->id);
                })->update(['is_active' => true]);
                Cache::forget("product_{$product->id}");
            }
            $msg = "Berhasil mengaktifkan {$count} produk terpilih.";
        }

        Cache::forget('catalog_main');
        Cache::forget('home_page_data_v3');
        Cache::forget("seller_products_{$sellerId}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'count'   => $count,
                'action'  => $action,
            ]);
        }

        return back()->with('success', $msg);
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
