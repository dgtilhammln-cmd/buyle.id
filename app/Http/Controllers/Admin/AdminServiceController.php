<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\CategoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class AdminServiceController extends Controller
{
    use HandlesImageUpload;

    public function index(Request $request)
    {
        $query = Service::ordered();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_desc', 'like', "%{$search}%");
            });
        }
        $services = $query->get();
        return view('admin.services.index', compact('services'));
    }

    public function create() { 
        $categories = CategoryItem::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.services.create', compact('categories')); 
    }

    public function store(Request $request)
    {
        if ($request->has('rating')) {
            $request->merge(['rating' => str_replace(',', '.', $request->rating)]);
        }

        $rules = [
            'name'          => 'required|max:200',
            'slug'          => 'nullable|max:200|regex:/^[a-z0-9\-]*$/',
            'short_desc'    => 'nullable|max:500',
            'description'   => 'nullable',
            'icon'          => 'nullable|max:50',
            'order'         => 'nullable|integer|min:0',
            'is_active'     => 'boolean',
            'meta_title'    => 'required|max:70',
            'meta_desc'     => 'required|max:165',
            'meta_keywords' => 'required|max:500',
            'image'         => 'nullable|image|max:5120',
            'brochure'      => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240',
            'og_image'      => 'nullable|image|max:5120',
            'gallery_images.*' => 'nullable|image|max:5120',
            'spec_keys'     => 'required|array|min:1',
            'spec_keys.0'   => 'required|string|max:255',
            'spec_values'   => 'required|array|min:1',
            'spec_values.0' => 'required|string|max:1000',
            'faq_qs'        => 'required|array|min:1',
            'faq_qs.0'      => 'required|string|max:500',
            'faq_as'        => 'required|array|min:1',
            'faq_as.0'      => 'required|string|max:2000',
            // E-commerce fields
            'price'         => 'nullable|numeric|min:0',
            'sale_price'    => 'nullable|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'min_order'     => 'nullable|integer|min:1',
            'weight'        => 'nullable|integer|min:0',
            'sku'           => 'nullable|string|max:100',
            'rating'        => 'required|numeric|min:0|max:5',
            'sold_count'    => 'required|integer|min:0',
            'product_category_id' => 'required|integer|exists:category_items,id',
        ];
        
        $messages = [
            'product_category_id.required' => '⚠️ DOUBLE CEK: Kategori produk WAJIB dipilih!',
            'stock.required' => '⚠️ DOUBLE CEK: Stok WAJIB diisi!',
            'rating.required' => '⚠️ DOUBLE CEK: Rating Bintang WAJIB diisi!',
            'sold_count.required' => '⚠️ DOUBLE CEK: Jumlah Terjual WAJIB diisi!',
            'spec_keys.required' => '⚠️ DOUBLE CEK: Spesifikasi Produk WAJIB diisi (Minimal 1)!',
            'spec_keys.0.required' => '⚠️ DOUBLE CEK: Baris pertama Spesifikasi Produk tidak boleh kosong!',
            'spec_values.required' => '⚠️ DOUBLE CEK: Nilai Spesifikasi Produk WAJIB diisi!',
            'spec_values.0.required' => '⚠️ DOUBLE CEK: Baris pertama Nilai Spesifikasi tidak boleh kosong!',
            'faq_qs.required' => '⚠️ DOUBLE CEK: Pertanyaan FAQ WAJIB diisi (Minimal 1)!',
            'faq_qs.0.required' => '⚠️ DOUBLE CEK: Baris pertama Pertanyaan FAQ tidak boleh kosong!',
            'faq_as.required' => '⚠️ DOUBLE CEK: Jawaban FAQ WAJIB diisi!',
            'faq_as.0.required' => '⚠️ DOUBLE CEK: Baris pertama Jawaban FAQ tidak boleh kosong!',
            'meta_title.required' => '⚠️ DOUBLE CEK: Meta Title (SEO Settings) WAJIB diisi!',
            'meta_desc.required' => '⚠️ DOUBLE CEK: Meta Description (SEO Settings) WAJIB diisi!',
            'meta_keywords.required' => '⚠️ DOUBLE CEK: Meta Keywords (SEO Settings) WAJIB diisi!',
        ];

        $v = $request->validate($rules, $messages);

        // Slug
        $slug = Str::slug(!empty($v['slug']) ? $v['slug'] : $v['name']);
        $base = $slug; $i = 1;
        while (Service::where('slug', $slug)->exists()) { $slug = $base.'-'.$i++; }
        $v['slug'] = $slug;

        $v['is_active'] = $request->boolean('is_active', true);
        // Order otomatis: produk baru otomatis ke urutan paling terakhir (max + 1)
        if (!isset($v['order']) || $v['order'] === '') {
            $maxOrder = \App\Models\Service::max('order');
            $v['order'] = $maxOrder !== null ? $maxOrder + 1 : 0;
        } else {
            $v['order'] = (int)$v['order'];
        }

        if (empty($v['meta_title'])) $v['meta_title'] = $v['name'].' | buyle.id';
        if (empty($v['meta_desc'])) {
            if (isset($v['price']) && $v['price'] > 0) {
                $v['meta_desc'] = "Jual {$v['name']} di Indonesia. Distributor, Supplier, Agen, {$v['name']}. Kami Menjual {$v['name']} terlengkap dengan harga termurah di Surabaya, Jawa Timur, Indonesia.";
            } else {
                $v['meta_desc'] = "Layanan {$v['name']} profesional dan terpercaya di Surabaya, Jawa Timur. Hubungi kami untuk konsultasi gratis dan dapatkan penawaran terbaik.";
            }
            $v['meta_desc'] = Str::limit($v['meta_desc'], 155);
        }

        if ($request->hasFile('image')) {
            $v['image'] = $this->storeWebP($request->file('image'), 'services', 1000, 1000);
            if (!$request->hasFile('og_image')) {
                $v['og_image'] = $this->storeOgWebP($request->file('image'), 'services/og');
            }
        }
        if ($request->hasFile('og_image')) {
            $v['og_image'] = $this->storeOgWebP($request->file('og_image'), 'services/og');
        }

        if ($request->hasFile('brochure')) {
            $v['brochure'] = $request->file('brochure')->store('brochures', 'public');
        }

        $gallery = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $this->storeWebP($file, 'services/gallery', 1200, 800);
            }
        }
        $v['gallery'] = $gallery;

        // Process Specs
        $specs = [];
        $specKeys = $request->input('spec_keys', []);
        $specValues = $request->input('spec_values', []);
        foreach ($specKeys as $idx => $key) {
            if (!empty($key) && isset($specValues[$idx])) {
                $specs[] = ['key' => $key, 'value' => $specValues[$idx]];
            }
        }
        $v['specifications'] = $specs;

        // Process FAQs
        $faqs = [];
        $faqQs = $request->input('faq_qs', []);
        $faqAs = $request->input('faq_as', []);
        foreach ($faqQs as $idx => $q) {
            if (!empty($q) && isset($faqAs[$idx])) {
                $faqs[] = ['q' => $q, 'a' => $faqAs[$idx]];
            }
        }
        $v['faqs'] = $faqs;

        // E-commerce fields defaults
        $v['price']         = $v['price'] ?? 0;
        $v['sale_price']    = $v['sale_price'] ?? 0;
        $v['stock']         = $v['stock'] ?? 0;
        $v['min_order']     = $v['min_order'] ?? 1;
        $v['weight']        = $v['weight'] ?? 0;
        $v['sold_count']    = $v['sold_count'] ?? 0;
        $v['rating']        = $v['rating'] ?? 0;

        Service::create($v);
        Cache::forget('home_page_data');
        Cache::forget('services_page_data');
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service) { 
        $categories = CategoryItem::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.services.edit', compact('service', 'categories')); 
    }

    public function update(Request $request, Service $service)
    {
        if ($request->has('rating')) {
            $request->merge(['rating' => str_replace(',', '.', $request->rating)]);
        }
        
        $rules = [
            'name'          => 'required|max:200',
            'slug'          => 'nullable|max:200|regex:/^[a-z0-9\-]*$/',
            'short_desc'    => 'nullable|max:500',
            'description'   => 'nullable',
            'icon'          => 'nullable|max:50',
            'order'         => 'nullable|integer|min:0',
            'is_active'     => 'boolean',
            'meta_title'    => 'required|max:70',
            'meta_desc'     => 'required|max:165',
            'meta_keywords' => 'required|max:500',
            'image'         => 'nullable|image|max:5120',
            'brochure'      => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240',
            'og_image'      => 'nullable|image|max:5120',
            'gallery_images.*' => 'nullable|image|max:5120',
            'spec_keys'     => 'required|array|min:1',
            'spec_keys.0'   => 'required|string|max:255',
            'spec_values'   => 'required|array|min:1',
            'spec_values.0' => 'required|string|max:1000',
            'faq_qs'        => 'required|array|min:1',
            'faq_qs.0'      => 'required|string|max:500',
            'faq_as'        => 'required|array|min:1',
            'faq_as.0'      => 'required|string|max:2000',
            // E-commerce fields
            'price'         => 'nullable|numeric|min:0',
            'sale_price'    => 'nullable|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'min_order'     => 'nullable|integer|min:1',
            'weight'        => 'nullable|integer|min:0',
            'sku'           => 'nullable|string|max:100',
            'rating'        => 'required|numeric|min:0|max:5',
            'sold_count'    => 'required|integer|min:0',
            'product_category_id' => 'required|integer|exists:category_items,id',
        ];
        
        $messages = [
            'product_category_id.required' => '⚠️ DOUBLE CEK: Kategori produk WAJIB dipilih!',
            'stock.required' => '⚠️ DOUBLE CEK: Stok WAJIB diisi!',
            'rating.required' => '⚠️ DOUBLE CEK: Rating Bintang WAJIB diisi!',
            'sold_count.required' => '⚠️ DOUBLE CEK: Jumlah Terjual WAJIB diisi!',
            'spec_keys.required' => '⚠️ DOUBLE CEK: Spesifikasi Produk WAJIB diisi (Minimal 1)!',
            'spec_keys.0.required' => '⚠️ DOUBLE CEK: Baris pertama Spesifikasi Produk tidak boleh kosong!',
            'spec_values.required' => '⚠️ DOUBLE CEK: Nilai Spesifikasi Produk WAJIB diisi!',
            'spec_values.0.required' => '⚠️ DOUBLE CEK: Baris pertama Nilai Spesifikasi tidak boleh kosong!',
            'faq_qs.required' => '⚠️ DOUBLE CEK: Pertanyaan FAQ WAJIB diisi (Minimal 1)!',
            'faq_qs.0.required' => '⚠️ DOUBLE CEK: Baris pertama Pertanyaan FAQ tidak boleh kosong!',
            'faq_as.required' => '⚠️ DOUBLE CEK: Jawaban FAQ WAJIB diisi!',
            'faq_as.0.required' => '⚠️ DOUBLE CEK: Baris pertama Jawaban FAQ tidak boleh kosong!',
            'meta_title.required' => '⚠️ DOUBLE CEK: Meta Title (SEO Settings) WAJIB diisi!',
            'meta_desc.required' => '⚠️ DOUBLE CEK: Meta Description (SEO Settings) WAJIB diisi!',
            'meta_keywords.required' => '⚠️ DOUBLE CEK: Meta Keywords (SEO Settings) WAJIB diisi!',
        ];

        $v = $request->validate($rules, $messages);

        // Slug update
        if (!empty($v['slug'])) {
            $slug = Str::slug($v['slug']);
            $base = $slug; $i = 1;
            while (Service::where('slug', $slug)->where('id','!=',$service->id)->exists()) { $slug = $base.'-'.$i++; }
            $v['slug'] = $slug;
        }

        $v['is_active'] = $request->boolean('is_active', true);
        $v['order']     = $v['order'] ?? $service->order;

        if (empty($v['meta_title'])) $v['meta_title'] = $v['name'].' | buyle.id';
        if (empty($v['meta_desc'])) {
            if (isset($v['price']) && $v['price'] > 0) {
                $v['meta_desc'] = "Jual {$v['name']} di Indonesia. Distributor, Supplier, Agen, {$v['name']}. Kami Menjual {$v['name']} terlengkap dengan harga termurah di Surabaya, Jawa Timur, Indonesia.";
            } else {
                $v['meta_desc'] = "Layanan {$v['name']} profesional dan terpercaya di Surabaya, Jawa Timur. Hubungi kami untuk konsultasi gratis dan dapatkan penawaran terbaik.";
            }
            $v['meta_desc'] = Str::limit($v['meta_desc'], 155);
        }

        if ($request->hasFile('image')) {
            $this->deleteStorageFile($service->image);
            $v['image'] = $this->storeWebP($request->file('image'), 'services', 1000, 1000);
            if (!$request->hasFile('og_image') && !$service->og_image) {
                $v['og_image'] = $this->storeOgWebP($request->file('image'), 'services/og');
            }
        }
        if ($request->hasFile('og_image')) {
            $this->deleteStorageFile($service->og_image);
            $v['og_image'] = $this->storeOgWebP($request->file('og_image'), 'services/og');
        }

        if ($request->hasFile('brochure')) {
            $this->deleteStorageFile($service->brochure);
            $v['brochure'] = $request->file('brochure')->store('brochures', 'public');
        }

        $gallery = is_array($service->gallery) ? $service->gallery : [];
        if ($request->has('delete_gallery')) {
            foreach ($request->input('delete_gallery') as $delImg) {
                $this->deleteStorageFile($delImg);
                if (($key = array_search($delImg, $gallery)) !== false) {
                    unset($gallery[$key]);
                }
            }
            $gallery = array_values($gallery);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $this->storeWebP($file, 'services/gallery', 1200, 800);
            }
        }
        $v['gallery'] = $gallery;

        // Process Specs
        $specs = [];
        $specKeys = $request->input('spec_keys', []);
        $specValues = $request->input('spec_values', []);
        foreach ($specKeys as $idx => $key) {
            if (!empty($key) && isset($specValues[$idx])) {
                $specs[] = ['key' => $key, 'value' => $specValues[$idx]];
            }
        }
        $v['specifications'] = $specs;

        // Process FAQs
        $faqs = [];
        $faqQs = $request->input('faq_qs', []);
        $faqAs = $request->input('faq_as', []);
        foreach ($faqQs as $idx => $q) {
            if (!empty($q) && isset($faqAs[$idx])) {
                $faqs[] = ['q' => $q, 'a' => $faqAs[$idx]];
            }
        }
        $v['faqs'] = $faqs;

        // E-commerce fields defaults
        $v['price']         = $v['price'] ?? 0;
        $v['sale_price']    = $v['sale_price'] ?? 0;
        $v['stock']         = $v['stock'] ?? 0;
        $v['min_order']     = $v['min_order'] ?? 1;
        $v['weight']        = $v['weight'] ?? 0;
        $v['sold_count']    = $v['sold_count'] ?? 0;
        $v['rating']        = $v['rating'] ?? 0;

        $service->update($v);
        Cache::forget('home_page_data');
        Cache::forget('services_page_data');
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        $this->deleteStorageFile($service->image);
        $this->deleteStorageFile($service->brochure);
        $this->deleteStorageFile($service->og_image);
        if (is_array($service->gallery)) {
            foreach ($service->gallery as $img) {
                $this->deleteStorageFile($img);
            }
        }
        $service->delete();
        Cache::forget('home_page_data');
        Cache::forget('services_page_data');
        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    public function updateStock(Request $request, Service $service)
    {
        $request->validate(['stock' => 'required|integer|min:0']);
        $service->update(['stock' => $request->stock]);
        Cache::forget('home_page_data');
        Cache::forget('services_page_data');
        return back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function updateOrder(Request $request, Service $service)
    {
        $request->validate(['order' => 'required|integer|min:0']);
        $service->update(['order' => $request->order]);
        Cache::forget('home_page_data');
        Cache::forget('services_page_data');
        return back()->with('success', 'Urutan berhasil diperbarui.');
    }

    /**
     * AJAX: Hapus satu foto dari gallery produk secara instan.
     * DELETE /admin/services/{service}/gallery-image
     * Body JSON: { "path": "services/gallery/xxx.webp" }
     */
    public function deleteGalleryImage(Request $request, Service $service)
    {
        $request->validate(['path' => 'required|string']);
        $path = $request->input('path');

        $gallery = is_array($service->gallery) ? $service->gallery : [];
        $key = array_search($path, $gallery);

        if ($key === false) {
            return response()->json(['ok' => false, 'message' => 'Foto tidak ditemukan.'], 404);
        }

        // Hapus file dari storage
        $this->deleteStorageFile($path);

        // Hapus dari array dan simpan
        unset($gallery[$key]);
        $service->update(['gallery' => array_values($gallery)]);

        Cache::forget('home_page_data');
        Cache::forget('services_page_data');

        return response()->json(['ok' => true, 'message' => 'Foto berhasil dihapus.']);
    }
}
