<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorBioBlock;
use App\Models\CreatorProfile;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreatorBioController extends Controller
{
    private function getProfile(): CreatorProfile
    {
        return CreatorProfile::getOrCreateForUser(auth()->user());
    }

    /**
     * Main dashboard - shows role picker if not set, else tabs.
     */
    public function index()
    {
        $profile = $this->getProfile();

        if (!$profile->bio_role) {
            return view('creator.bio.role_picker', compact('profile'));
        }

        $blocks = $profile->bioBlocks()->get();
        $myProducts = Product::where('seller_id', auth()->id())->where('is_active', true)->orderBy('name')->get(['id', 'name', 'price', 'image', 'slug']);
        $whitelabelProducts = Product::whiteLabelApproved()
            ->where('is_active', true)
            ->where('seller_id', '!=', auth()->id())
            ->with('seller:id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'whitelabel_price', 'whitelabel_terms', 'image', 'slug', 'seller_id']);
        $affiliateProducts = Product::where('is_active', true)
            ->where('seller_id', '!=', auth()->id())
            ->with('seller:id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'sale_price', 'affiliate_commission_rate', 'image', 'slug', 'seller_id']);

        return view('creator.bio.index', compact('profile', 'blocks', 'myProducts', 'whitelabelProducts', 'affiliateProducts'));
    }

    /**
     * Set role and redirect to main dashboard.
     */
    public function setRole(Request $request)
    {
        $request->validate(['bio_role' => 'required|in:content_creator,affiliator,business']);
        $profile = $this->getProfile();

        $updateData = [
            'bio_role'  => $request->bio_role,
            'bio_theme' => $profile->bio_theme ?? 'theme1',
        ];

        if ($request->bio_role === 'affiliator') {
            if (empty($profile->store_name)) {
                $updateData['store_name'] = auth()->user()->name;
            }
            if (empty($profile->store_slug)) {
                $baseSlug = \Illuminate\Support\Str::slug(auth()->user()->name);
                $slug = $baseSlug ?: 'affiliate-' . auth()->id();
                $i = 1;
                while (\App\Models\CreatorProfile::where('store_slug', $slug)->where('id', '!=', $profile->id)->exists()) {
                    $slug = $baseSlug . '-' . $i++;
                }
                $updateData['store_slug'] = $slug;
            }
        }

        $profile->update($updateData);

        if ($request->bio_role === 'affiliator') {
            return redirect()->route('creator.bio.index')->with('success', 'Selamat! Profil Link in Bio Affiliator Anda siap dibuat. 🎉');
        }

        return redirect()->route('creator.profile.edit')->with('success', 'Selamat datang! Silakan lengkapi profil toko Anda.');
    }

    /**
     * Save theme selection.
     */
    public function saveTheme(Request $request)
    {
        $request->validate(['bio_theme' => 'required|in:theme1,theme2,theme3,theme4']);
        $profile = $this->getProfile();
        
        $config = $profile->bio_config ?? [];
        // Reset custom colors & background so the new theme takes precedence
        unset($config['color_bg'], $config['color_text'], $config['color_btn'], $config['color_btn_text'], $config['color_accent'], $config['color_card'], $config['bg_type'], $config['bg_image']);
        
        $profile->update([
            'bio_theme' => $request->bio_theme,
            'bio_config' => $config
        ]);
        
        return back()->with('success', 'Tema berhasil diperbarui & Kustomisasi Background telah direset!');
    }

    /**
     * Save profile settings (Tab 2).
     */
    public function saveProfile(Request $request)
    {
        $request->validate([
            'bio_name'      => 'nullable|string|max:100',
            'bio_username'  => 'nullable|string|max:50|alpha_dash',
            'bio_bio'       => 'nullable|string|max:300',
            'bio_location'  => 'nullable|string|max:100',
            'bio_avatar'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bio_cover'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bio_bg_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'bg_type'       => 'nullable|in:color,image',
            'bio_wa'        => 'nullable|string|max:30',
            'bio_ig'        => 'nullable|string|max:80',
            'bio_tiktok'    => 'nullable|string|max:80',
            'bio_youtube'   => 'nullable|string|max:200',
            'bio_facebook'  => 'nullable|string|max:200',
            'bio_x'         => 'nullable|string|max:100',
            'bio_linkedin'  => 'nullable|string|max:200',
            'bio_pinterest' => 'nullable|string|max:100',
            'bio_threads'   => 'nullable|string|max:100',
            'bio_telegram'  => 'nullable|string|max:100',
            'bio_discord'   => 'nullable|string|max:200',
            'bio_snapchat'  => 'nullable|string|max:100',
            'bio_website'   => 'nullable|url|max:300',
            'bio_gofood'    => 'nullable|url|max:300',
            'bio_grabfood'  => 'nullable|url|max:300',
            'bio_shopeefood'=> 'nullable|url|max:300',
            'color_bg'      => 'nullable|string|max:20',
            'color_text'    => 'nullable|string|max:20',
            'color_btn'     => 'nullable|string|max:20',
            'color_btn_text'=> 'nullable|string|max:20',
            'color_accent'  => 'nullable|string|max:20',
            'color_card'    => 'nullable|string|max:20',
            'embed_location'=> 'nullable|string|max:1000',
        ]);

        $profile = $this->getProfile();
        $config  = $profile->bio_config ?? [];

        // Handle avatar upload / deletion
        if ($request->has('delete_avatar') && !empty($config['avatar'])) {
            Storage::disk('public')->delete($config['avatar']);
            $config['avatar'] = null;
        } elseif ($request->hasFile('bio_avatar')) {
            if (!empty($config['avatar'])) Storage::disk('public')->delete($config['avatar']);
            $config['avatar'] = $request->file('bio_avatar')->store('bio/avatars', 'public');
        }

        // Handle cover upload / deletion
        if ($request->has('delete_cover') && !empty($config['cover'])) {
            Storage::disk('public')->delete($config['cover']);
            $config['cover'] = null;
        } elseif ($request->hasFile('bio_cover')) {
            if (!empty($config['cover'])) Storage::disk('public')->delete($config['cover']);
            $config['cover'] = $request->file('bio_cover')->store('bio/covers', 'public');
        }

        // Handle custom background image upload / deletion
        if ($request->has('bg_type')) {
            $config['bg_type'] = $request->bg_type;
        }

        if ($request->has('delete_bg_image') && !empty($config['bg_image'])) {
            Storage::disk('public')->delete($config['bg_image']);
            $config['bg_image'] = null;
        } elseif ($request->hasFile('bio_bg_image')) {
            if (!empty($config['bg_image'])) {
                Storage::disk('public')->delete($config['bg_image']);
            }
            $config['bg_image'] = $this->convertToWebp($request->file('bio_bg_image'), 'bio/backgrounds');
            $config['bg_type'] = 'image';
        }

        $config['name']     = $request->has('bio_name') ? ($request->bio_name ?? '') : ($config['name'] ?? '');
        $config['bio']      = $request->has('bio_bio') ? ($request->bio_bio ?? '') : ($config['bio'] ?? '');
        $config['location'] = $request->has('bio_location') ? ($request->bio_location ?? '') : ($config['location'] ?? '');
        $config['wa']        = $request->has('bio_wa') ? ($request->bio_wa ?? '') : ($config['wa'] ?? '');
        $config['ig']        = $request->has('bio_ig') ? ($request->bio_ig ?? '') : ($config['ig'] ?? '');
        $config['tiktok']    = $request->has('bio_tiktok') ? ($request->bio_tiktok ?? '') : ($config['tiktok'] ?? '');
        $config['youtube']   = $request->has('bio_youtube') ? ($request->bio_youtube ?? '') : ($config['youtube'] ?? '');
        $config['facebook']  = $request->has('bio_facebook') ? ($request->bio_facebook ?? '') : ($config['facebook'] ?? '');
        $config['x']         = $request->has('bio_x') ? ($request->bio_x ?? '') : ($config['x'] ?? '');
        $config['linkedin']  = $request->has('bio_linkedin') ? ($request->bio_linkedin ?? '') : ($config['linkedin'] ?? '');
        $config['pinterest'] = $request->has('bio_pinterest') ? ($request->bio_pinterest ?? '') : ($config['pinterest'] ?? '');
        $config['threads']   = $request->has('bio_threads') ? ($request->bio_threads ?? '') : ($config['threads'] ?? '');
        $config['telegram']  = $request->has('bio_telegram') ? ($request->bio_telegram ?? '') : ($config['telegram'] ?? '');
        $config['discord']   = $request->has('bio_discord') ? ($request->bio_discord ?? '') : ($config['discord'] ?? '');
        $config['snapchat']  = $request->has('bio_snapchat') ? ($request->bio_snapchat ?? '') : ($config['snapchat'] ?? '');
        $config['website']   = $request->has('bio_website') ? ($request->bio_website ?? '') : ($config['website'] ?? '');
        $config['gofood']    = $request->has('bio_gofood') ? ($request->bio_gofood ?? '') : ($config['gofood'] ?? '');
        $config['grabfood']  = $request->has('bio_grabfood') ? ($request->bio_grabfood ?? '') : ($config['grabfood'] ?? '');
        $config['shopeefood']= $request->has('bio_shopeefood') ? ($request->bio_shopeefood ?? '') : ($config['shopeefood'] ?? '');

        if ($request->filled('color_bg')) $config['color_bg'] = $request->color_bg;
        if ($request->filled('color_text')) $config['color_text'] = $request->color_text;
        if ($request->filled('color_btn')) $config['color_btn'] = $request->color_btn;
        if ($request->filled('color_btn_text')) $config['color_btn_text'] = $request->color_btn_text;
        if ($request->filled('color_accent')) $config['color_accent'] = $request->color_accent;
        if ($request->filled('color_card')) $config['color_card'] = $request->color_card;
        
        if ($request->has('embed_location')) {
            // allow empty to clear
            $config['embed_location'] = $request->embed_location;
        }

        // Handle username (store_slug used as bio URL slug)
        if ($request->filled('bio_username')) {
            $slug = $request->bio_username;
            $exists = CreatorProfile::where('store_slug', $slug)->where('id', '!=', $profile->id)->exists();
            if ($exists) {
                return back()->withErrors(['bio_username' => 'Username sudah dipakai orang lain.'])->withInput();
            }
            $profile->store_slug = $slug;
        }

        $profile->bio_config = $config;
        $profile->save();

        return back()->with('success', 'Profil berhasil disimpan!');
    }

    /**
     * Store a new block (Tab 3 & 4).
     */
    public function storeBlock(Request $request)
    {
        // Strip rupiah formatting (dots) from price fields before validation
        if ($request->has('price')) {
            $cPrice = preg_replace('/[^0-9]/', '', (string)$request->price);
            $request->merge(['price' => $cPrice !== '' ? (int)$cPrice : null]);
        }
        if ($request->has('original_price')) {
            $cOrig = preg_replace('/[^0-9]/', '', (string)$request->original_price);
            $request->merge(['original_price' => $cOrig !== '' ? (int)$cOrig : null]);
        }
        if ($request->has('title') && strlen((string)$request->title) > 140) {
            $request->merge(['title' => Str::limit(trim($request->title), 140, '...')]);
        }

        $request->validate([
            'type'        => 'required|string|max:50',
            'title'       => 'required|string|max:255',
            'url'         => 'nullable|string|max:2000',
            'block_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'custom_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'icon_class'  => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'product_id'  => 'nullable|exists:products,id',
            'price'          => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:wa,web',
            'wa_text'        => 'nullable|string|max:500',
            'scrape_image_url' => 'nullable|string|max:2000',
            'scraped_image'    => 'nullable|string|max:2000',
        ]);

        $profile = $this->getProfile();
        $data    = [];

        // Handle custom image upload or scraped image
        if ($request->hasFile('block_image')) {
            $data['image'] = $request->file('block_image')->store('bio/blocks', 'public');
        } elseif ($request->filled('scraped_image') || $request->filled('scrape_image_url')) {
            $scrapedUrl = $request->input('scraped_image') ?: $request->input('scrape_image_url');
            $data['image'] = $this->saveRemoteImageLocally($scrapedUrl);
        }

        // If shopee/affiliate: try to scrape OG image if still empty and save locally
        if (in_array($request->type, ['shopee', 'affiliate']) && $request->filled('url') && empty($data['image'])) {
            $scraped = $this->scrapeOgImage($request->url);
            if ($scraped) $data['image'] = $this->saveRemoteImageLocally($scraped);
        }

        if ($request->filled('description')) $data['description'] = $request->description;
        if ($request->filled('product_id'))  $data['product_id']  = $request->product_id;
        if ($request->filled('icon_class'))  $data['icon_class']  = $request->icon_class;
        
        // Handle custom product specific fields
        if (in_array($request->type, ['custom_product', 'buyle_product'])) {
            if ($request->filled('price')) $data['price'] = (int)$request->price;
            if ($request->filled('original_price')) $data['original_price'] = (int)$request->original_price;
            if ($request->filled('payment_method')) $data['payment_method'] = $request->payment_method;
            if ($request->filled('wa_text')) $data['wa_text'] = $request->wa_text;

            $catRaw = trim($request->category ?? 'Makanan');
            $data['category'] = match (strtolower($catRaw)) {
                'barang' => 'Barang',
                'jasa'   => 'Jasa',
                'lainnya' => 'Lainnya',
                default  => 'Makanan',
            };

            $stockVal = $request->stock ?? null;
            $data['stock'] = ($stockVal === '' || $stockVal === null || $stockVal === 'unlimited' || (is_numeric($stockVal) && (int)$stockVal < 0)) ? null : (int)$stockVal;
            
            // Shipping & Volume calculation fields
            $data['sku']    = $request->filled('sku') ? trim($request->sku) : null;
            $data['weight'] = $request->filled('weight') ? max(1, (int)$request->weight) : 1000;
            $data['length'] = $request->filled('length') ? (float)$request->length : null;
            $data['width']  = $request->filled('width')  ? (float)$request->width  : null;
            $data['height'] = $request->filled('height') ? (float)$request->height : null;
            
            if (!empty($data['length']) && !empty($data['width']) && !empty($data['height'])) {
                $data['volume'] = (int) ceil($data['length'] * $data['width'] * $data['height']);
            } else {
                $data['volume'] = $request->filled('volume') ? (int)$request->volume : null;
            }

            // Smart slug logic: limit title length intelligently for clean URLs
            $cleanTitle = Str::limit($request->title, 45, '');
            $baseSlug   = rtrim(Str::slug($cleanTitle), '-');
            $data['slug'] = $baseSlug ?: 'produk-' . time();
            
            // Ensure bio/blocks directory exists on hosting
            $bioBlocksDir = storage_path('app/public/bio/blocks');
            if (!is_dir($bioBlocksDir)) {
                @mkdir($bioBlocksDir, 0755, true);
            }

            // Handle multiple images
            if ($request->hasFile('custom_images')) {
                $images = [];
                $files = array_slice($request->file('custom_images'), 0, 3); // Max 3
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('bio/blocks', 'public');
                        if ($path) $images[] = $path;
                    }
                }
                if (!empty($images)) {
                    $data['images'] = $images;
                    $data['image']  = $images[0];
                }
            }

            // If no uploaded images, try scrape_image_url (from Shopee/Tokopedia scraper)
            if (empty($data['images']) && !empty($data['image'])) {
                $data['images'] = [$data['image']];
            }
            // Auto-create Product entry in products table for Payment Gateway checkout
            if (($data['payment_method'] ?? 'web') === 'web' && empty($data['product_id'])) {
                $product = \App\Models\Product::create([
                    'seller_id'    => auth()->id(),
                    'name'         => $request->title,
                    'slug'         => $data['slug'] . '-' . time(),
                    'price'        => $data['price'] ?? 0,
                    'stock'        => $data['stock'],
                    'sku'          => $data['sku'],
                    'weight'       => $data['weight'],
                    'length'       => $data['length'],
                    'width'        => $data['width'],
                    'height'       => $data['height'],
                    'volume'       => $data['volume'],
                    'description'  => $data['description'] ?? '',
                    'image'        => !empty($data['images'][0]) ? $data['images'][0] : ($data['image'] ?? null),
                    'is_active'    => true,
                    'product_type' => ($data['category'] === 'Barang') ? 'physical' : (($data['category'] === 'Jasa') ? 'service' : 'makanan'),
                ]);
                $data['product_id'] = $product->id;
            }
        }

        $maxOrder = CreatorBioBlock::where('creator_id', $profile->id)->max('order');
        $lastOrder = ($maxOrder !== null && $maxOrder !== false) ? (int)$maxOrder : 0;

        CreatorBioBlock::create([
            'creator_id' => $profile->id,
            'type'       => $request->type,
            'title'      => $request->title,
            'url'        => $request->url,
            'data_json'  => $data ?: null,
            'order'      => $lastOrder + 1,
            'is_active'  => true,
        ]);

        return back()->with('success', 'Block berhasil ditambahkan!');
    }

    /**
     * Delete a block.
     */
    public function destroyBlock(CreatorBioBlock $block)
    {
        $profile = $this->getProfile();
        if ($block->creator_id !== $profile->id) abort(403);

        // Smart File Deletion: Delete single image from hosting disk space
        if (!empty($block->data_json['image']) && !Str::startsWith($block->data_json['image'], 'http')) {
            Storage::disk('public')->delete($block->data_json['image']);
        }
        // Smart File Deletion: Delete multiple product images from hosting disk space
        if (!empty($block->data_json['images']) && is_array($block->data_json['images'])) {
            foreach ($block->data_json['images'] as $imgFile) {
                if (!empty($imgFile) && !Str::startsWith($imgFile, 'http')) {
                    Storage::disk('public')->delete($imgFile);
                }
            }
        }
        $block->delete();
        return back()->with('success', 'Block dihapus.');
    }

    /**
     * Delete all custom products (Produk Fisik UMKM) for creator.
     */
    public function destroyAllCustomProducts()
    {
        $profile = $this->getProfile();
        $blocks = CreatorBioBlock::where('creator_id', $profile->id)
            ->where('type', 'custom_product')
            ->get();

        foreach ($blocks as $block) {
            if (!empty($block->data_json['images']) && is_array($block->data_json['images'])) {
                foreach ($block->data_json['images'] as $imgFile) {
                    if (!empty($imgFile) && !Str::startsWith($imgFile, 'http')) {
                        Storage::disk('public')->delete($imgFile);
                    }
                }
            }
            $block->delete();
        }

        return back()->with('success', 'Semua Produk Fisik / UMKM berhasil dihapus dari Link Bio!');
    }

    /**
     * Update a block.
     */
    public function updateBlock(Request $request, CreatorBioBlock $block)
    {
        $profile = $this->getProfile();
        if ($block->creator_id !== $profile->id) abort(403);

        // Strip rupiah formatting before validation
        if ($request->has('price')) {
            $cPrice = preg_replace('/[^0-9]/', '', (string)$request->price);
            $request->merge(['price' => $cPrice !== '' ? (int)$cPrice : null]);
        }
        if ($request->has('original_price')) {
            $cOrig = preg_replace('/[^0-9]/', '', (string)$request->original_price);
            $request->merge(['original_price' => $cOrig !== '' ? (int)$cOrig : null]);
        }
        if ($request->has('title') && strlen((string)$request->title) > 140) {
            $request->merge(['title' => Str::limit(trim($request->title), 140, '...')]);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'nullable|string|max:2000',
            'block_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'custom_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'scraped_image' => 'nullable|string',
            'scrape_image_url' => 'nullable|string',
            'icon_class'  => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'price'          => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:wa,web',
            'wa_text'        => 'nullable|string|max:500',
        ]);

        $data = $block->data_json ?? [];

        if ($request->hasFile('block_image')) {
            if (!empty($data['image']) && !Str::startsWith($data['image'], 'http')) {
                Storage::disk('public')->delete($data['image']);
            }
            $data['image'] = $request->file('block_image')->store('bio/blocks', 'public');
        } elseif ($request->filled('scraped_image')) {
            if (!empty($data['image']) && !Str::startsWith($data['image'], 'http')) {
                Storage::disk('public')->delete($data['image']);
            }
            $data['image'] = $this->saveRemoteImageLocally($request->scraped_image);
        }
        
        if ($request->has('description')) $data['description'] = $request->description;
        if ($request->has('icon_class'))  $data['icon_class']  = $request->icon_class;
        
        if (in_array($block->type, ['custom_product', 'buyle_product'])) {
            if ($request->has('price')) $data['price'] = (int)$request->price;
            if ($request->has('original_price')) $data['original_price'] = (int)$request->original_price;
            if ($request->has('payment_method')) $data['payment_method'] = $request->payment_method;

            if ($request->has('category')) {
                $catRaw = trim($request->category ?? 'Makanan');
                $data['category'] = match (strtolower($catRaw)) {
                    'barang' => 'Barang',
                    'jasa'   => 'Jasa',
                    'lainnya' => 'Lainnya',
                    default  => 'Makanan',
                };
            }
            if ($request->has('stock')) {
                $sVal = $request->stock;
                $data['stock'] = ($sVal === null || $sVal === '' || $sVal === 'unlimited' || (is_numeric($sVal) && (int)$sVal < 0)) ? null : (int)$sVal;
            }
            
            if ($request->has('sku'))    $data['sku']    = $request->filled('sku') ? trim($request->sku) : null;
            if ($request->has('weight')) $data['weight'] = $request->filled('weight') ? max(1, (int)$request->weight) : 1000;
            if ($request->has('length')) $data['length'] = $request->filled('length') ? (float)$request->length : null;
            if ($request->has('width'))  $data['width']  = $request->filled('width')  ? (float)$request->width  : null;
            if ($request->has('height')) $data['height'] = $request->filled('height') ? (float)$request->height : null;
            
            if (!empty($data['length']) && !empty($data['width']) && !empty($data['height'])) {
                $data['volume'] = (int) ceil($data['length'] * $data['width'] * $data['height']);
            } elseif ($request->has('volume')) {
                $data['volume'] = $request->filled('volume') ? (int)$request->volume : null;
            }
            
            if (empty($data['slug']) || $block->title !== $request->title) {
                $cleanTitle = Str::limit($request->title, 45, '');
                $baseSlug   = rtrim(Str::slug($cleanTitle), '-');
                $data['slug'] = $baseSlug ?: 'produk-' . time();
            }
            
            $currentImages = $data['images'] ?? [];

            // Delete requested existing images from disk & array
            if ($request->filled('delete_existing_images')) {
                foreach ($request->delete_existing_images as $delImg) {
                    $currentImages = array_filter($currentImages, fn($i) => $i !== $delImg);
                    if (!Str::startsWith($delImg, 'http')) {
                        Storage::disk('public')->delete($delImg);
                    }
                }
                $currentImages = array_values($currentImages);
            }

            // Ensure bio/blocks directory exists
            $bioBlocksDir = storage_path('app/public/bio/blocks');
            if (!is_dir($bioBlocksDir)) {
                @mkdir($bioBlocksDir, 0755, true);
            }

            // Append new uploaded images up to max 3
            if ($request->hasFile('custom_images')) {
                $maxAllow = max(0, 3 - count($currentImages));
                $newFiles = array_slice($request->file('custom_images'), 0, $maxAllow);
                foreach ($newFiles as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('bio/blocks', 'public');
                        if ($path) $currentImages[] = $path;
                    }
                }
            }

            $data['images'] = array_slice($currentImages, 0, 3);
            if (!empty($data['images'][0])) {
                $data['image'] = $data['images'][0];
            } elseif (!empty($data['image'])) {
                $data['images'] = [$data['image']];
            }

            // Sync with Product table entry if product_id exists
            if (!empty($data['product_id'])) {
                $p = \App\Models\Product::find($data['product_id']);
                if ($p) {
                    $catName = strtolower(trim($data['category'] ?? 'makanan'));
                    $productType = match ($catName) {
                        'barang' => 'physical',
                        'jasa'   => 'service',
                        'makanan' => 'makanan',
                        default  => 'makanan',
                    };

                    $p->update([
                        'name'         => $request->title,
                        'price'        => $data['price'] ?? 0,
                        'stock'        => $data['stock'] ?? null,
                        'sku'          => $data['sku'] ?? $p->sku,
                        'weight'       => $data['weight'] ?? $p->weight,
                        'length'       => $data['length'] ?? $p->length,
                        'width'        => $data['width'] ?? $p->width,
                        'height'       => $data['height'] ?? $p->height,
                        'volume'       => $data['volume'] ?? $p->volume,
                        'description'  => $data['description'] ?? '',
                        'image'        => !empty($data['images'][0]) ? $data['images'][0] : ($data['image'] ?? $p->image),
                        'product_type' => $productType,
                    ]);
                }
            }
        }

        if ($request->filled('icon_class')) {
            $data['icon_class'] = $request->icon_class;
        } else {
            unset($data['icon_class']); // Remove if cleared
        }

        $block->update([
            'title'     => $request->title,
            'url'       => $request->url,
            'data_json' => $data,
        ]);

        return back()->with('success', 'Block berhasil diupdate!');
    }

    /**
     * Toggle block active status.
     */
    public function toggleBlock(CreatorBioBlock $block)
    {
        $profile = $this->getProfile();
        if ($block->creator_id !== $profile->id) abort(403);
        $block->update(['is_active' => !$block->is_active]);
        return back();
    }

    /**
     * Reorder blocks via AJAX (JSON drag-drop).
     */
    public function reorderBlocks(Request $request)
    {
        $profile = $this->getProfile();
        $ids = $request->input('ids', []);
        foreach ($ids as $i => $id) {
            CreatorBioBlock::where('id', $id)->where('creator_id', $profile->id)->update(['order' => $i + 1]);
        }
        return response()->json(['ok' => true]);
    }

    /**
     * Scrape OG image using multiple strategies.
     * Strategy 1: Direct HTML fetch (works for Tokopedia, most sites)
     * Strategy 2: Microlink.io API (works for Shopee JS-rendered pages)
     */
    private function scrapeOgImage(string $url): ?string
    {
        // Strategy 1: Direct HTML scrape
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent'      => 'Mozilla/5.0 (Linux; Android 11; Pixel 5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Safari/537.36',
                    'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Cache-Control'   => 'no-cache',
                ])
                ->get($url);

            if ($response->successful()) {
                $html = $response->body();
                // og:image
                if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\'](.*?)["\']/i', $html, $m) ||
                    preg_match('/<meta[^>]+content=["\'](.*?)["\'\s][^>]+property=["\']og:image["\']/i', $html, $m)) {
                    if (!empty($m[1]) && filter_var($m[1], FILTER_VALIDATE_URL)) return $m[1];
                }
                // twitter:image
                if (preg_match('/<meta[^>]+name=["\']twitter:image["\'][^>]+content=["\'](.*?)["\']/i', $html, $m)) {
                    if (!empty($m[1]) && filter_var($m[1], FILTER_VALIDATE_URL)) return $m[1];
                }
            }
        } catch (\Throwable $e) {}

        // Strategy 2: Microlink.io (handles JS-rendered pages like Shopee)
        try {
            $mlResponse = Http::timeout(15)
                ->get('https://api.microlink.io', [
                    'url'        => $url,
                    'meta'       => 'true',
                    'screenshot' => 'false',
                ]);
            if ($mlResponse->successful()) {
                $data = $mlResponse->json();
                $img = $data['data']['image']['url']
                    ?? $data['data']['logo']['url']
                    ?? null;
                if ($img && filter_var($img, FILTER_VALIDATE_URL)) return $img;
            }
        } catch (\Throwable $e) {}

        return null;
    }



    /**
     * Smart Image Downloader: Download remote scraped image & save locally to hosting storage
     * so images never expire or disappear over time.
     */
    /**
     * Resolve relative image URL to absolute URL.
     */
    private function resolveUrl(string $url, string $baseUrl): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        $scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?: 'https';
        $host   = parse_url($baseUrl, PHP_URL_HOST);
        if (str_starts_with($url, '//')) {
            return $scheme . ':' . $url;
        }
        if (str_starts_with($url, '/')) {
            return $scheme . '://' . $host . $url;
        }
        return $scheme . '://' . $host . '/' . ltrim($url, '/');
    }

    /**
     * Smart Image Downloader: Download remote scraped image & save locally to hosting storage
     */
    private function saveRemoteImageLocally(?string $imageUrl): ?string
    {
        if (empty($imageUrl)) {
            return null;
        }

        if (!Str::startsWith($imageUrl, 'http')) {
            return $imageUrl;
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
                ])
                ->get($imageUrl);

            if ($response->successful() && !empty($response->body())) {
                $ext = 'jpg';
                $ct = $response->header('Content-Type');
                if ($ct && str_contains($ct, 'png')) $ext = 'png';
                elseif ($ct && str_contains($ct, 'webp')) $ext = 'webp';

                $filename = 'bio/scraped/' . md5($imageUrl) . '.' . $ext;
                Storage::disk('public')->put($filename, $response->body());
                return $filename;
            }
        } catch (\Throwable $e) {}

        return $imageUrl;
    }

    /**
     * Convert uploaded background image to WebP automatically.
     */
    private function convertToWebp($file, string $destinationDirectory, int $quality = 85): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = 'bg_' . md5(uniqid('', true) . time()) . '.webp';
        $relativeDir = trim($destinationDirectory, '/');
        $fullDirPath = storage_path('app/public/' . $relativeDir);

        if (!file_exists($fullDirPath)) {
            @mkdir($fullDirPath, 0755, true);
        }

        $fullPath = $fullDirPath . '/' . $filename;
        $image = null;

        if ($extension === 'jpeg' || $extension === 'jpg') {
            $image = @imagecreatefromjpeg($file->getRealPath());
        } elseif ($extension === 'png') {
            $image = @imagecreatefrompng($file->getRealPath());
            if ($image) {
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
        } elseif ($extension === 'webp') {
            $file->storeAs($relativeDir, $filename, 'public');
            return $relativeDir . '/' . $filename;
        }

        if ($image && function_exists('imagewebp')) {
            @imagewebp($image, $fullPath, $quality);
            @imagedestroy($image);
            if (file_exists($fullPath)) {
                return $relativeDir . '/' . $filename;
            }
        }

        // Fallback if GD fails or WebP not created
        return $file->store($relativeDir, 'public');
    }

    /**
     * Scrape product data from Shopee / Tokopedia / other marketplace URLs.
     * Returns JSON: { title, price, original_price, description, image, images[], partial }
     *
     * Strategy per platform:
     *  - Shopee / shortlinks → Facebook Bot UA (bypasses anti-bot login redirect)
     *  - Tokopedia           → Browser UA + JSON-LD
     *  - Others              → OG meta + Microlink.io fallback
     *
     * Multi-image: tries og:image (1st), og:image:secure_url, JSON-LD image[], og:image:alt-index
     * Original price: tries product:price:standart_amount (Tokopedia), og:price_before_discount (Shopee),
     *                 JSON-LD offers.highPrice, og:original-price, regex in-page
     */
    public function scrapeUrl(Request $request)
    {
        $url = trim($request->input('url', ''));

        if (!$url || !preg_match('#^https?://#i', $url)) {
            return response()->json(['error' => 'URL tidak valid. Pastikan dimulai dengan https://'], 422);
        }

        try {
            $url  = preg_replace('/[\x00-\x1F\x7F]/', '', $url);
            $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

            $isShopee    = str_contains($host, 'shopee') || str_contains($host, 'shp.ee');
            $isTokopedia = str_contains($host, 'tokopedia') || str_contains($host, 'tokope.dia');
            $isTiktok    = str_contains($host, 'tiktok') || str_contains($host, 'vt.tiktok') || str_contains($host, 'vm.tiktok');

            $ua = ($isShopee || $isTiktok)
                ? 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)'
                : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36';

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent'      => $ua,
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
            ])->timeout(15)->withOptions(['allow_redirects' => true])->get($url);

            $html = $response->body();

            // Fallback for Shopee / TikTok if empty: try WhatsApp UA
            if (($isShopee || $isTiktok) && (!$html || strlen($html) < 500)) {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'User-Agent' => 'WhatsApp/2.23.20.0 i',
                ])->timeout(15)->get($url);
                $html = $response->body();
            }

            if (!$response->successful() && !$html) {
                return response()->json(['error' => 'Gagal mengambil halaman produk (status ' . $response->status() . ').'], 422);
            }

            // --- Helper: extract meta tag content ---
            $getMeta = function (string $prop) use ($html): string {
                foreach (['property="' . $prop . '"', 'property=\'' . $prop . '\'', 'name="' . $prop . '"', 'name=\'' . $prop . '\''] as $attr) {
                    if (preg_match('/<meta[^>]+' . preg_quote($attr, '/') . '[^>]+content=["\']([^"\']+)["\'][^>]*>/i', $html, $m) ||
                        preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+' . preg_quote($attr, '/') . '[^>]*>/i', $html, $m)) {
                        return trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                    }
                }
                return '';
            };

            $title       = $getMeta('og:title') ?: $getMeta('title');
            $description = $getMeta('og:description') ?: $getMeta('description');
            $price       = (float) preg_replace('/[^0-9]/', '', $getMeta('product:price:amount') ?: $getMeta('og:price:amount'));
            $origPrice   = 0.0;

            // TikTok Shop Specific Scraper
            if ($isTiktok) {
                if (preg_match('/"original_price"\s*:\s*["\']?([0-9.]+)/i', $html, $tm)) {
                    $pOrig = (float) $tm[1];
                    if ($pOrig > 0 && $origPrice <= 0) $origPrice = $pOrig;
                }
                if (preg_match('/"(?:real_price|sale_price|discount_price|min_price)"\s*:\s*["\']?([0-9.]+)/i', $html, $tm2)) {
                    $pSale = (float) $tm2[1];
                    if ($pSale > 0 && $price <= 0) $price = $pSale;
                }
                if (preg_match('/"format_original_price"\s*:\s*["\']?([^"\',]+)/i', $html, $tm3)) {
                    $pOrigFmt = (float) preg_replace('/[^0-9]/', '', $tm3[1]);
                    if ($pOrigFmt > 0 && $origPrice <= 0) $origPrice = $pOrigFmt;
                }
                if (preg_match('/"format_real_price"\s*:\s*["\']?([^"\',]+)/i', $html, $tm4)) {
                    $pSaleFmt = (float) preg_replace('/[^0-9]/', '', $tm4[1]);
                    if ($pSaleFmt > 0 && $price <= 0) $price = $pSaleFmt;
                }
            }

            // Shopee Specific Scraper
            if ($isShopee) {
                if (preg_match('/"price_before_discount"\s*:\s*([0-9]+)/i', $html, $sm)) {
                    $pOrigShp = (float) $sm[1];
                    if ($pOrigShp > 10000000) $pOrigShp = $pOrigShp / 100000;
                    if ($pOrigShp > 0 && $origPrice <= 0) $origPrice = $pOrigShp;
                }
                if (preg_match('/"price"\s*:\s*([0-9]+)/i', $html, $sm2)) {
                    $pSaleShp = (float) $sm2[1];
                    if ($pSaleShp > 10000000) $pSaleShp = $pSaleShp / 100000;
                    if ($pSaleShp > 0 && $price <= 0) $price = $pSaleShp;
                }
            }

            // Tokopedia Specific Scraper
            if ($isTokopedia) {
                if (preg_match('/"(?:slashPrice|originalPrice)"\s*:\s*["\']?([0-9.]+)/i', $html, $tokm)) {
                    $pOrigTok = (float) preg_replace('/[^0-9]/', '', $tokm[1]);
                    if ($pOrigTok > 0 && $origPrice <= 0) $origPrice = $pOrigTok;
                }
                if (preg_match('/"(?:slashPriceFmt)"\s*:\s*["\']?([^"\',]+)/i', $html, $tokm2)) {
                    $pOrigTokFmt = (float) preg_replace('/[^0-9]/', '', $tokm2[1]);
                    if ($pOrigTokFmt > 0 && $origPrice <= 0) $origPrice = $pOrigTokFmt;
                }
            }

            // ── Multi-image collection (maks 3) ─────────────────────────────
            $images = [];
            $addImg = function (string $u) use (&$images) {
                $u = trim($u);
                if ($u && !in_array($u, $images) && count($images) < 3) {
                    $images[] = $u;
                }
            };

            // 1) og:image (main)
            $ogImg = $getMeta('og:image') ?: $getMeta('og:image:secure_url');
            if ($ogImg) $addImg($ogImg);

            // 2) Tokopedia: product:image / og:image:alt indices
            for ($i = 1; $i <= 4 && count($images) < 3; $i++) {
                $alt = $getMeta("og:image:alt:$i") ?: $getMeta("og:image:$i");
                if ($alt) $addImg($alt);
            }

            // ── Price: harga coret / original price ─────────────────────────
            // Tokopedia: product:price:standart_amount, og:price_before_discount
            $rawOrig = $getMeta('product:price:standart_amount')
                    ?: $getMeta('og:price_before_discount')
                    ?: $getMeta('og:original-price')
                    ?: $getMeta('product:original_price:amount');
            if ($rawOrig) {
                $origPrice = (float) preg_replace('/[^0-9]/', '', $rawOrig);
            }

            // ── JSON-LD Parsing ──────────────────────────────────────────────
            if (preg_match_all('/<script[^>]+type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is', $html, $jsonMatches)) {
                foreach ($jsonMatches[1] as $jsonStr) {
                    $ld = @json_decode($jsonStr, true);
                    if (!is_array($ld)) continue;

                    if (($ld['@type'] ?? '') === 'BreadcrumbList' && !empty($ld['itemListElement'])) {
                        $lastItem = end($ld['itemListElement']);
                        if (!empty($lastItem['item']['name']) && strlen($lastItem['item']['name']) > 5) {
                            $title = $title ?: $lastItem['item']['name'];
                        }
                    }

                    if (($ld['@type'] ?? '') === 'Product') {
                        $title       = $title ?: ($ld['name'] ?? '');
                        $description = $description ?: ($ld['description'] ?? '');

                        // JSON-LD images (could be array of URLs)
                        if (!empty($ld['image'])) {
                            $ldImgs = is_array($ld['image']) ? $ld['image'] : [$ld['image']];
                            foreach ($ldImgs as $ldImg) {
                                $src = is_array($ldImg) ? ($ldImg['url'] ?? '') : $ldImg;
                                $addImg((string)$src);
                            }
                        }

                        // Price from offers
                        $offers = $ld['offers'] ?? [];
                        // Normalize single offer or array
                        if (isset($offers['@type'])) $offers = [$offers];
                        foreach ((array)$offers as $offer) {
                            if (!$price && isset($offer['price'])) {
                                $price = (float) preg_replace('/[^0-9]/', '', (string)$offer['price']);
                            }
                            // highPrice = harga normal sebelum diskon
                            if (!$origPrice && isset($offer['highPrice'])) {
                                $origPrice = (float) preg_replace('/[^0-9]/', '', (string)$offer['highPrice']);
                            }
                            // Tokopedia sometimes puts priceValidUntil with originalPrice key
                            if (!$origPrice && isset($offer['originalPrice'])) {
                                $origPrice = (float) preg_replace('/[^0-9]/', '', (string)$offer['originalPrice']);
                            }
                        }
                    }
                }
            }

            // ── Harga coret: inline regex fallback ──────────────────────────
            if (!$origPrice) {
                // Tokopedia: "Rp51.600" as struck text near price — look for higher price in vicinity
                // Pattern: data-testid="lblOriginalPrice" or class containing "strike" or "original"
                if (preg_match('/(?:original.?price|harga.?normal|harga.?coret|strike)[^>]*>(?:[^<]*Rp\s*)?([0-9][0-9.,]{2,})/i', $html, $m)) {
                    $candidate = (float) preg_replace('/[^0-9]/', '', $m[1]);
                    if ($candidate > $price) $origPrice = $candidate;
                }
                // Shopee & Tokopedia: look for del/s tag with a price higher than current price
                if (!$origPrice && preg_match_all('/<(?:del|s)[^>]*>(?:[^<]*?Rp\s*)?([0-9][0-9.,]{2,})<\/(?:del|s)>/i', $html, $m2)) {
                    foreach ($m2[1] as $rawP) {
                        $candidate = (float) preg_replace('/[^0-9]/', '', $rawP);
                        if ($candidate > $price) { $origPrice = $candidate; break; }
                    }
                }
            }

            // Sanity: origPrice must be >= price (otherwise it's not a "coret" price)
            if ($origPrice && $origPrice <= $price) $origPrice = 0;

            // ── Platform-specific text cleaning ─────────────────────────────
            if ($isShopee) {
                $title = preg_replace('/^Jual\s+/i', '', $title);
                $title = preg_replace('/\s*[-|]\s*(Shopee|Shopee Indonesia).*$/i', '', $title);
                if (str_contains($description, 'Beli ') && str_contains($description, 'di Shopee')) {
                    $description = preg_replace('/^Beli\s+.*?\s+Terbaru Harga Murah di Shopee\.\s*/i', '', $description);
                }
            }
            if ($isTokopedia) {
                $title = preg_replace('/\s*[-|]\s*(Tokopedia).*$/i', '', $title);
            }

            $title = trim($title);
            if (strlen($description) > 500) {
                $description = substr($description, 0, 500) . '…';
            }

            // ── Additional images: scrape product gallery img tags ───────────
            if (count($images) < 3) {
                // Tokopedia product images often in data-src or src with cdn urls
                $imgPatterns = [
                    '/data-src=["\']((https?:\/\/[^"\']+\.(?:jpg|jpeg|png|webp))[^"\']*)["\']/',
                    '/src=["\']((https?:\/\/[^"\']+\.(?:jpg|jpeg|png|webp))[^"\']*)["\']/',
                ];
                foreach ($imgPatterns as $pat) {
                    if (preg_match_all($pat, $html, $imgMatches)) {
                        foreach ($imgMatches[1] as $src) {
                            // Skip tiny/icon images (thumbnails < 50px), usually with w=30 or similar
                            if (preg_match('/[?&]w=[1-9][0-9]?(?:&|$)/', $src)) continue;
                            if (str_contains($src, 'icon') || str_contains($src, 'logo')) continue;
                            $addImg($src);
                            if (count($images) >= 3) break;
                        }
                    }
                    if (count($images) >= 3) break;
                }
            }

            // ── Microlink fallback if title and image are missing ────────────
            if (!$title && empty($images)) {
                try {
                    $ml = \Illuminate\Support\Facades\Http::timeout(12)->get('https://api.microlink.io', [
                        'url'  => $url,
                        'meta' => 'true',
                    ]);
                    if ($ml->successful()) {
                        $d = $ml->json('data', []);
                        $title       = $title ?: ($d['title'] ?? '');
                        $description = $description ?: ($d['description'] ?? '');
                        $mlImg = $d['image']['url'] ?? $d['logo']['url'] ?? '';
                        if ($mlImg) $addImg($mlImg);
                    }
                } catch (\Throwable $e) {}
            }

            $downloadedImages = [];
            foreach ($images as $imgUrl) {
                if (str_starts_with($imgUrl, 'http://') || str_starts_with($imgUrl, 'https://')) {
                    $dl = \App\Services\ImageDownloader::downloadAndCompress($imgUrl, 'products/gallery');
                    $downloadedImages[] = $dl;
                } else {
                    $downloadedImages[] = $imgUrl;
                }
            }

            $primaryImage = $downloadedImages[0] ?? null;

            $normalPrice = 0;
            $promoPrice  = 0;

            if ($origPrice > 0 && $price > 0) {
                if ($origPrice > $price) {
                    $normalPrice = (int)$origPrice;
                    $promoPrice  = (int)$price;
                } else if ($price > $origPrice) {
                    $normalPrice = (int)$price;
                    $promoPrice  = (int)$origPrice;
                } else {
                    $normalPrice = (int)$price;
                    $promoPrice  = 0;
                }
            } else if ($price > 0) {
                $normalPrice = (int)$price;
                $promoPrice  = 0;
            } else if ($origPrice > 0) {
                $normalPrice = (int)$origPrice;
                $promoPrice  = 0;
            }

            return response()->json([
                'title'          => $title ?: null,
                'price'          => $normalPrice,
                'original_price' => $normalPrice,
                'sale_price'     => $promoPrice,
                'description'    => $description ?: null,
                'image'          => $primaryImage,
                'images'         => $downloadedImages,  // array, maks 3
                'partial'        => (!$title || empty($downloadedImages)),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal scrape: ' . $e->getMessage()], 500);
        }
    }
}
