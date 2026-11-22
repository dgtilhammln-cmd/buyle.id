<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\ProductVisit;
use App\Models\OrderItem;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        // Kolom company profile (existing)
        'name', 'slug', 'short_desc', 'description', 'image', 'brochure', 'og_image', 'gallery',
        'specifications', 'faqs',
        'icon', 'order', 'is_active',
        'meta_title', 'meta_desc', 'meta_keywords',
        // Kolom e-commerce (new)
        'price', 'sale_price', 'stock', 'weight', 'sku', 'length', 'width', 'height', 'volume',
        'product_category_id', 'product_sub_category_id', 'is_featured', 'is_buyle_checkout',
        'product_type', 'file_type', 'digital_resource', 'seller_id', 'creator_group_id', 'sold_count', 'views_count', 'unit', 'min_order', 'max_order', 'rating', 'tiktok_video_url',
        // Event / Ticket fields
        'event_date', 'event_end_date', 'event_time', 'event_location', 'event_type', 'event_platform',
        'terms_conditions', 'instagram_username', 'tiktok_username',
        'gate_scanner_key', 'gate_scanner_enabled', 'gate_scanner_pin',
        'gate_open_time', 'gate_close_time', 'gate_bypass_time',
        'ticket_war_start', 'max_tickets_per_order',
        // Free Product Follow & Join Requirement
        'require_follow_join',
        'require_screenshot_proof',
        'custom_free_requirements',
        'social_follow_url', 'community_join_url', // Legacy fallback
        'follow_instagram_url', 'follow_tiktok_url', 'follow_x_url', 'follow_youtube_url',
        'join_whatsapp_url', 'join_telegram_url', 'join_discord_url',
        // Flexible Pricing Model (Fixed, Free, PWYW, Traktir)
        'pricing_type', 'pwyw_min_price', 'pwyw_suggested_price',
        'traktir_unit_name', 'traktir_unit_icon', 'traktir_unit_price',
        // Kolom White Label & Reseller
        'is_whitelabel', 'whitelabel_price', 'whitelabel_terms', 'whitelabel_approval_status', 'whitelabel_rejection_reason',
        // Affiliate Commission
        'affiliate_commission_rate'
    ];

    protected $casts = [
        // Existing casts
        'is_active'          => 'boolean',
        'is_buyle_checkout'  => 'boolean',
        'order'          => 'integer',
        'gallery'        => 'array',
        'specifications' => 'array',
        'faqs'           => 'array',
        // E-commerce casts
        'price'          => 'decimal:2',
        'sale_price'     => 'decimal:2',
        'stock'          => 'integer',
        'weight'         => 'integer',
        'is_featured'    => 'boolean',
        'sold_count'     => 'integer',
        'views_count'    => 'integer',
        'min_order'      => 'integer',
        'max_order'      => 'integer',
        'rating'         => 'decimal:1',
        'event_date'     => 'date',
        'event_end_date' => 'date',
        'ticket_war_start' => 'datetime',
        'max_tickets_per_order' => 'integer',
        'gate_scanner_enabled' => 'boolean',
        'gate_bypass_time'     => 'boolean',
        'require_follow_join'  => 'boolean',
        'require_screenshot_proof' => 'boolean',
        'custom_free_requirements' => 'array',
        // Pricing models casts
        'pwyw_min_price'       => 'decimal:2',
        'pwyw_suggested_price' => 'decimal:2',
        'traktir_unit_price'   => 'decimal:2',
        // White Label casts
        'is_whitelabel'             => 'boolean',
        'whitelabel_price'          => 'decimal:2',
        'affiliate_commission_rate' => 'decimal:2',
    ];

    /**
     * Periksa apakah Gate saat ini berstatus Buka (Open) atau Tutup (Closed).
     */
    public function getGateStatus(): array
    {
        if (!$this->gate_scanner_enabled) {
            return [
                'is_open' => false,
                'status'  => 'disabled',
                'reason'  => 'Akses Gate dinonaktifkan oleh penyelenggara event.'
            ];
        }

        if ($this->gate_bypass_time) {
            return [
                'is_open' => true,
                'status'  => 'bypassed',
                'reason'  => 'Gate dibuka manual (Bypass Waktu aktif).'
            ];
        }

        if (empty($this->gate_open_time) && empty($this->gate_close_time)) {
            return [
                'is_open' => true,
                'status'  => 'open',
                'reason'  => 'Gate aktif (Tanpa batasan jam).'
            ];
        }

        $now = now();
        $currentTime = $now->format('H:i');

        if ($this->event_date) {
            $startDateStr = $this->event_date->format('Y-m-d');
            $endDateStr   = $this->event_end_date ? $this->event_end_date->format('Y-m-d') : $startDateStr;
            $todayStr     = $now->format('Y-m-d');
            
            if ($todayStr < $startDateStr) {
                return [
                    'is_open' => false,
                    'status'  => 'before_date',
                    'reason'  => 'Event dijadwalkan pada ' . $this->formatted_event_date . '. Gate belum dibuka.'
                ];
            }

            if ($todayStr > $endDateStr) {
                return [
                    'is_open' => false,
                    'status'  => 'after_date',
                    'reason'  => 'Rangkaian event telah berakhir pada ' . ($this->event_end_date ? $this->event_end_date->format('d M Y') : $this->event_date->format('d M Y')) . '.'
                ];
            }
        }

        if (!empty($this->gate_open_time) && $currentTime < $this->gate_open_time) {
            return [
                'is_open' => false,
                'status'  => 'before_open',
                'reason'  => 'Gate belum dibuka. Jadwal buka gate pukul ' . $this->gate_open_time . ' WIB.'
            ];
        }

        if (!empty($this->gate_close_time) && $currentTime > $this->gate_close_time) {
            return [
                'is_open' => false,
                'status'  => 'after_close',
                'reason'  => 'Gate telah ditutup pada pukul ' . $this->gate_close_time . ' WIB.'
            ];
        }

        return [
            'is_open' => true,
            'status'  => 'open',
            'reason'  => 'Gate dibuka.'
        ];
    }

    /**
     * Format tanggal event ringkas (misal: "11 Des 2026" atau "11 - 13 Des 2026").
     */
    public function getFormattedEventDateAttribute(): string
    {
        if (!$this->event_date) {
            return '';
        }

        try {
            $start = \Carbon\Carbon::parse($this->event_date)->locale('id');
            $end = $this->event_end_date ? \Carbon\Carbon::parse($this->event_end_date)->locale('id') : null;

            if ($end && $end->gt($start)) {
                if ($start->format('Y-m') === $end->format('Y-m')) {
                    // Contoh: 11 - 13 Des 2026
                    return $start->translatedFormat('d') . ' - ' . $end->translatedFormat('d M Y');
                } elseif ($start->format('Y') === $end->format('Y')) {
                    // Contoh: 30 Okt - 2 Nov 2026
                    return $start->translatedFormat('d M') . ' - ' . $end->translatedFormat('d M Y');
                } else {
                    // Contoh: 31 Des 2026 - 2 Jan 2027
                    return $start->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y');
                }
            }

            return $start->translatedFormat('d M Y');
        } catch (\Throwable $e) {
            return (string)$this->event_date;
        }
    }

    /**
     * Format tanggal event lengkap (misal: "Jumat, 11 Desember 2026" atau "11 - 13 Desember 2026").
     */
    public function getFormattedEventDateFullAttribute(): string
    {
        if (!$this->event_date) {
            return '';
        }

        try {
            $start = \Carbon\Carbon::parse($this->event_date)->locale('id');
            $end = $this->event_end_date ? \Carbon\Carbon::parse($this->event_end_date)->locale('id') : null;

            if ($end && $end->gt($start)) {
                if ($start->format('Y-m') === $end->format('Y-m')) {
                    // Contoh: 11 - 13 Desember 2026
                    return $start->translatedFormat('d') . ' - ' . $end->translatedFormat('d F Y');
                } elseif ($start->format('Y') === $end->format('Y')) {
                    // Contoh: 30 Oktober - 2 November 2026
                    return $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y');
                } else {
                    // Contoh: 31 Desember 2026 - 2 Januari 2027
                    return $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
                }
            }

            return $start->translatedFormat('l, d F Y');
        } catch (\Throwable $e) {
            return (string)$this->event_date;
        }
    }

    /**
     * Cek apakah event berlangsung lebih dari 1 hari.
     */
    public function getIsMultiDayEventAttribute(): bool
    {
        if (!$this->event_date || !$this->event_end_date) {
            return false;
        }
        return \Carbon\Carbon::parse($this->event_end_date)->gt(\Carbon\Carbon::parse($this->event_date));
    }

    /**
     * Mengambil daftar URL video (bisa 1 link YouTube atau 1-3 link TikTok).
     *
     * @return array
     */
    public function getVideoUrlsAttribute(): array
    {
        $raw = $this->tiktok_video_url ?? $this->youtube_video_url ?? null;
        if (empty($raw)) {
            return [];
        }

        if (is_array($raw)) {
            return array_values(array_filter($raw));
        }

        $trimmed = trim((string)$raw);
        if (str_starts_with($trimmed, '[') || str_starts_with($trimmed, '{')) {
            $decoded = json_decode($trimmed, true);
            if (is_array($decoded)) {
                return array_values(array_filter($decoded));
            }
        }

        return [$trimmed];
    }

    /**
     * Mendapatkan platform video yang dipakai ('youtube', 'tiktok', atau 'none').
     *
     * @return string
     */
    public function getVideoPlatformAttribute(): string
    {
        $urls = $this->video_urls;
        if (empty($urls)) {
            return 'none';
        }
        foreach ($urls as $u) {
            if (str_contains(strtolower($u), 'tiktok.com')) {
                return 'tiktok';
            }
        }
        return 'youtube';
    }

    /**
     * Dapatkan atau generate kunci akses Gate Scanner untuk panitia event ini.
     */
    public function getOrGenerateGateKey(): string
    {
        if (empty($this->gate_scanner_key)) {
            $this->gate_scanner_key = Str::random(32);
            $this->saveQuietly();
        }
        return $this->gate_scanner_key;
    }

    /**
     * Hitung berat efektif produk (menggunakan max antara berat fisik dan berat volume dimensional).
     * Rumus Volumetric Weight = (Panjang * Lebar * Tinggi) / 6 dalam gram (atau volume / 6).
     * Agar seller/creator tidak nombok ongkir karena barang besar tapi ringan.
     */
    public function getEffectiveWeightAttribute(): int
    {
        $actualWeight = (int) ($this->weight ?? 0);
        
        $vol = 0;
        if (!empty($this->length) && !empty($this->width) && !empty($this->height)) {
            $vol = (float)$this->length * (float)$this->width * (float)$this->height;
        } elseif (!empty($this->volume)) {
            $vol = (float)$this->volume;
        }
        
        $volumetricWeight = $vol > 0 ? (int) ceil($vol / 6) : 0;
        
        $effective = max($actualWeight, $volumetricWeight);
        return $effective > 0 ? $effective : 1000;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::deleting(function ($product) {
            // Bersihkan semua bio block yang menautkan produk ini (apapun tipenya: buyle_product, custom_product, buyle_affiliate)
            \App\Models\CreatorBioBlock::where(function ($q) use ($product) {
                $q->where('data_json->product_id', $product->id)
                  ->orWhere('data_json->product_id', (string)$product->id);
            })->delete();

            // Bersihkan variant options
            if (method_exists($product, 'variantOptions')) {
                $product->variantOptions()->delete();
            }

            // Bersihkan cart & wishlist
            if (method_exists($product, 'carts')) {
                $product->carts()->delete();
            }
            if (method_exists($product, 'wishlists')) {
                $product->wishlists()->delete();
            }

            // Hapus cache produk
            \Illuminate\Support\Facades\Cache::forget('catalog_main');
            \Illuminate\Support\Facades\Cache::forget('home_page_data_v3');
            \Illuminate\Support\Facades\Cache::forget("product_{$product->id}");
            if ($product->seller_id) {
                \Illuminate\Support\Facades\Cache::forget("seller_products_{$product->seller_id}");
            }
        });
    }

    // =========================================================================
    // Relationships (E-commerce)
    // =========================================================================

    /**
     * Penjual / Creator dari produk ini.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Kategori produk yang menaungi produk ini.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Sub-Kategori produk yang menaungi produk ini.
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(ProductSubCategory::class, 'product_sub_category_id');
    }

    /**
     * Grup produk (custom creator).
     */
    public function creatorGroup(): BelongsTo
    {
        return $this->belongsTo(CreatorProductGroup::class, 'creator_group_id');
    }

    /**
     * Semua opsi varian produk ini (misal: "Ukuran", "Warna").
     */
    public function variantOptions(): HasMany
    {
        return $this->hasMany(ProductVariantOption::class, 'product_id');
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(ProductRecipe::class, 'product_id');
    }

    /**
     * Semua item cart yang mengandung produk ini.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    /**
     * Semua wishlist yang mengandung produk ini.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

    /**
     * Semua kunjungan (views) ke produk ini.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(ProductVisit::class, 'product_id');
    }

    /**
     * Semua item pesanan yang berisi produk ini.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    public function scopeActive(Builder $query): Builder { return $query->where('is_active', true); }

    /**
     * Scope khusus untuk produk yang layak tampil di Marketplace.
     * HANYA 2 Tipe Produk yang boleh tampil di marketplace buyle.id:
     * 1. Produk Digital / Link Access (external_link, digital, ebook, dsb)
     * 2. Tiket Event / Wisata / Webinar (ticket, event, webinar, dsb)
     */
    public function scopeMarketplace(Builder $query): Builder
    {
        return $query->where('is_active', true)
                     ->whereIn('product_type', [
                         'external_link', 'digital', 'link', 'ebook', 'course', 'license', 'file', 'download',
                         'ticket', 'event', 'webinar', 'workshop', 'wisata'
                     ]);
    }
    public function scopeOrdered(Builder $query): Builder { return $query->orderBy('created_at', 'desc'); }

    /**
     * Scope produk White Label yang disetujui Admin.
     */
    public function scopeWhiteLabelApproved(Builder $query): Builder
    {
        return $query->where('is_whitelabel', true)->where('whitelabel_approval_status', 'approved');
    }

    /**
     * Scope produk White Label yang menunggu approval Admin.
     */
    public function scopeWhiteLabelPending(Builder $query): Builder
    {
        return $query->where('is_whitelabel', true)->where('whitelabel_approval_status', 'pending');
    }

    /**
     * Hanya produk yang ditandai sebagai unggulan.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Produk yang masih ada stoknya (stok > 0 atau stok NULL = unlimited).
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('stock')->orWhere('stock', '>', 0);
        });
    }

    public function getIsUnlimitedStockAttribute(): bool
    {
        return is_null($this->stock);
    }

    public function getInStockAttribute(): bool
    {
        return is_null($this->stock) || $this->stock > 0;
    }

    /**
     * Filter berdasarkan kategori.
     */
    public function scopeInCategory(Builder $query, int|string $categoryId): Builder
    {
        return $query->where('product_category_id', $categoryId);
    }

    /**
     * Filter pencarian.
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where('name', 'like', "%{$keyword}%")
                     ->orWhere('short_desc', 'like', "%{$keyword}%")
                     ->orWhere('description', 'like', "%{$keyword}%");
    }

    /**
     * Filter berdasarkan tipe (product/service).
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Pengurutan (sort).
     */
    public function scopeSort(Builder $query, string $sort): Builder
    {
        return match($sort) {
            'latest'  => $query->latest(),
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('sold_count', 'desc')->orderBy('views_count', 'desc'),
            default   => $query->orderBy('order')->orderBy('id'),
        };
    }

    // =========================================================================
    // Actions
    // =========================================================================

    /**
     * Kurangi stok produk.
     */
    public function reduceStock(int $qty): void
    {
        if ($this->type === 'product' && $this->stock >= $qty) {
            $this->decrement('stock', $qty);
        }
    }

    /**
     * Tambah jumlah terjual.
     */
    public function addSoldCount(int $qty): void
    {
        $this->increment('sold_count', $qty);
    }

    public static function getPlaceholderUrl(): string
    {
        return asset('images/buyle-placeholder.svg');
    }

    public function getImageUrlAttribute(): string
    {
        if (!empty($this->image)) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            return asset('storage/' . ltrim($this->image, '/'));
        }

        if (!empty($this->gallery) && is_array($this->gallery) && count($this->gallery) > 0) {
            $first = $this->gallery[0];
            if (!empty($first)) {
                if (str_starts_with($first, 'http://') || str_starts_with($first, 'https://')) {
                    return $first;
                }
                return asset('storage/' . ltrim($first, '/'));
            }
        }

        return static::getPlaceholderUrl();
    }
    public function getOgImageUrlAttribute(): string
    {
        return $this->og_image ? (str_starts_with($this->og_image, 'http') ? $this->og_image : asset('storage/'.ltrim($this->og_image, '/'))) : $this->image_url;
    }
    public function getGalleryUrlsAttribute(): array
    {
        $urls = [];
        if (is_array($this->gallery)) {
            foreach ($this->gallery as $img) {
                if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                    $urls[] = $img;
                } else {
                    $urls[] = asset('storage/' . ltrim($img, '/'));
                }
            }
        }
        return $urls;
    }
    public function getMetaTitleAttribute($v): string
    {
        return $v ?: $this->name . ' | buyle.id';
    }
    public function getMetaDescAttribute($v): string
    {
        return $v ?: Str::limit(strip_tags($this->short_desc ?? $this->description ?? ''), 155);
    }

    /**
     * Dapatkan URL detail produk (otomatis rute /tix/{slug} jika produk tiket, atau /produk/{slug} jika produk umum).
     */
    public function getUrlAttribute(): string
    {
        if ($this->isTicketProduct()) {
            return route('tix.show', ['slug' => $this->slug]);
        }
        return route('products.show', ['slug' => $this->slug]);
    }

    // =========================================================================
    // E-commerce Accessors
    // =========================================================================

    /**
     * Harga yang ditampilkan (sale_price jika set, termasuk 0 untuk gratis; jika null pakai price).
     */
    public function getEffectivePriceAttribute(): float
    {
        // sale_price === null berarti tidak ada promo, pakai price
        // sale_price = 0 berarti GRATIS
        if ($this->sale_price !== null) {
            return (float) $this->sale_price;
        }
        return (float) ($this->price ?? 0);
    }

    /**
     * Apakah produk gratis (harga efektif = 0).
     */
    public function getIsFreeAttribute(): bool
    {
        return $this->effective_price <= 0;
    }

    /**
     * Apakah produk sedang dalam kondisi diskon.
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && (float) $this->sale_price < (float) $this->price && (float) $this->sale_price > 0;
    }

    /**
     * Persentase diskon (0 jika tidak ada diskon).
     */
    public function getDiscountPercentageAttribute(): int
    {
        if ((float) $this->price <= 0) {
            return 0;
        }
        if ($this->sale_price !== null && (float) $this->sale_price <= 0) {
            return 100; // gratis = 100% off
        }
        if (! $this->is_on_sale) {
            return 0;
        }

        return (int) round((1 - (float) $this->sale_price / (float) $this->price) * 100);
    }


    /**
     * Apakah produk masih tersedia (stok > 0) atau berupa jasa.
     */
    public function getIsAvailableAttribute(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        if ($this->type === 'service') {
            return true;
        }
        if ($this->stock === null) {
            return true;
        }
        return (int)$this->stock > 0;
    }

    /**
     * Gambar utama produk.
     */
    public function getMainImageAttribute(): string
    {
        return $this->image_url;
    }

    /**
     * Apakah ini produk fisik.
     */
    public function getIsPhysicalProductAttribute(): bool
    {
        return $this->type === 'product';
    }

    /**
     * Apakah ini jasa.
     */
    public function getIsServiceAttribute(): bool
    {
        return $this->type === 'service';
    }

    /**
     * Apakah produk ini bertipe Tiket Event
     */
    public function isTicketProduct(): bool
    {
        return $this->product_type === 'ticket' 
            || in_array($this->product_type, ['ticket', 'event', 'webinar', 'workshop', 'wisata'])
            || !empty($this->event_date);
    }

    /**
     * Apakah produk ini memiliki jadwal War Tiket
     */
    public function isTicketWar(): bool
    {
        return $this->isTicketProduct() && !empty($this->ticket_war_start);
    }

    /**
     * Apakah sesi war tiket sudah dibuka
     */
    public function isTicketWarOpen(): bool
    {
        return $this->isTicketWar() && now()->gte($this->ticket_war_start);
    }

    /**
     * Apakah saat ini sedang dalam PERIODE WAR TIKET (antrean aktif).
     * Dibatasi maksimal 3 jam setelah ticket_war_start (kebijakan platform).
     * Setelah 3 jam lewat, antrean dinonaktifkan & tiket bisa langsung dibeli normal.
     */
    public function isTicketWarActive(): bool
    {
        if (!$this->isTicketWarOpen()) {
            return false;
        }

        $warEnd = $this->ticket_war_start->copy()->addHours(3);
        return now()->lt($warEnd);
    }

    /**
     * Rating dan ulasan produk.
     */
    public function ratings()
    {
        return $this->hasMany(\App\Models\ProductRating::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\ProductRating::class, 'product_id')->where('is_approved', true);
    }

    /**
     * Hitung rating riil produk (dari ulasan DB yang disetujui atau kolom rating manual jika diatur kreator eksklusif).
     * Mengembalikan null jika belum ada ulasan/rating sama sekali.
     */
    public function getEffectiveRating(): ?float
    {
        // 1. Jika sudah dimuat withAvg('reviews', 'rating')
        if (isset($this->attributes['reviews_avg_rating']) && $this->attributes['reviews_avg_rating'] !== null) {
            $val = (float) $this->attributes['reviews_avg_rating'];
            if ($val > 0) {
                return round($val, 1);
            }
        }

        // 2. Jika ada rating manual diatur kreator (> 0)
        if ($this->rating !== null && (float) $this->rating > 0) {
            return round((float) $this->rating, 1);
        }

        // 3. Cek relasi jika sudah di-load
        if ($this->relationLoaded('reviews')) {
            $avg = $this->reviews->where('is_approved', true)->avg('rating');
            if ($avg && $avg > 0) {
                return round((float) $avg, 1);
            }
        }

        return null;
    }

    /**
     * Dapatkan jumlah ulasan valid produk.
     */
    public function getEffectiveReviewsCount(): int
    {
        if (isset($this->attributes['reviews_count']) && $this->attributes['reviews_count'] !== null) {
            return (int) $this->attributes['reviews_count'];
        }

        if ($this->relationLoaded('reviews')) {
            return (int) $this->reviews->where('is_approved', true)->count();
        }

        return (int) $this->reviews()->count();
    }

    /**
     * Metadata master daftar belasan platform sosial media & komunitas yang didukung.
     */
    public static function getAvailablePlatforms(): array
    {
        return [
            'instagram' => [
                'name'             => 'Instagram',
                'category'         => 'sosmed',
                'action'           => 'Follow',
                'icon'             => 'instagram',
                'default_label'    => 'Follow Akun Instagram',
                'default_sublabel' => 'Kunjungi & follow profil Instagram kreator',
                'placeholder'      => 'https://instagram.com/username',
                'color'            => '#E1306C',
            ],
            'tiktok' => [
                'name'             => 'TikTok',
                'category'         => 'sosmed',
                'action'           => 'Follow',
                'icon'             => 'tiktok',
                'default_label'    => 'Follow Akun TikTok',
                'default_sublabel' => 'Kunjungi & follow profil TikTok kreator',
                'placeholder'      => 'https://tiktok.com/@username',
                'color'            => '#000000',
            ],
            'youtube' => [
                'name'             => 'YouTube',
                'category'         => 'sosmed',
                'action'           => 'Subscribe',
                'icon'             => 'youtube',
                'default_label'    => 'Subscribe Channel YouTube',
                'default_sublabel' => 'Kunjungi & subscribe channel YouTube kreator',
                'placeholder'      => 'https://youtube.com/@channel',
                'color'            => '#FF0000',
            ],
            'x' => [
                'name'             => 'X (Twitter)',
                'category'         => 'sosmed',
                'action'           => 'Follow',
                'icon'             => 'x',
                'default_label'    => 'Follow Akun X (Twitter)',
                'default_sublabel' => 'Kunjungi & ikuti akun X kreator',
                'placeholder'      => 'https://x.com/username',
                'color'            => '#000000',
            ],
            'threads' => [
                'name'             => 'Threads',
                'category'         => 'sosmed',
                'action'           => 'Follow',
                'icon'             => 'threads',
                'default_label'    => 'Follow Akun Threads',
                'default_sublabel' => 'Kunjungi & ikuti akun Threads kreator',
                'placeholder'      => 'https://threads.net/@username',
                'color'            => '#000000',
            ],
            'facebook' => [
                'name'             => 'Facebook',
                'category'         => 'sosmed',
                'action'           => 'Follow',
                'icon'             => 'facebook',
                'default_label'    => 'Follow / Like Facebook Page',
                'default_sublabel' => 'Kunjungi & ikuti halaman Facebook kreator',
                'placeholder'      => 'https://facebook.com/halaman',
                'color'            => '#1877F2',
            ],
            'linkedin' => [
                'name'             => 'LinkedIn',
                'category'         => 'sosmed',
                'action'           => 'Follow',
                'icon'             => 'linkedin',
                'default_label'    => 'Follow Profil / Page LinkedIn',
                'default_sublabel' => 'Kunjungi & follow akun LinkedIn kreator',
                'placeholder'      => 'https://linkedin.com/in/username',
                'color'            => '#0A66C2',
            ],
            'whatsapp_channel' => [
                'name'             => 'WhatsApp Saluran',
                'category'         => 'community',
                'action'           => 'Ikuti',
                'icon'             => 'whatsapp',
                'default_label'    => 'Ikuti Saluran WhatsApp',
                'default_sublabel' => 'Dapatkan info update di Saluran WhatsApp',
                'placeholder'      => 'https://whatsapp.com/channel/...',
                'color'            => '#25D366',
            ],
            'whatsapp_group' => [
                'name'             => 'WhatsApp Grup',
                'category'         => 'community',
                'action'           => 'Join',
                'icon'             => 'whatsapp',
                'default_label'    => 'Gabung Grup WhatsApp',
                'default_sublabel' => 'Bergabung ke komunitas grup WhatsApp kreator',
                'placeholder'      => 'https://chat.whatsapp.com/...',
                'color'            => '#25D366',
            ],
            'telegram_channel' => [
                'name'             => 'Telegram Saluran',
                'category'         => 'community',
                'action'           => 'Join',
                'icon'             => 'telegram',
                'default_label'    => 'Join Channel Telegram',
                'default_sublabel' => 'Bergabung ke channel Telegram resmi',
                'placeholder'      => 'https://t.me/nama_channel',
                'color'            => '#229ED9',
            ],
            'telegram_group' => [
                'name'             => 'Telegram Grup',
                'category'         => 'community',
                'action'           => 'Join',
                'icon'             => 'telegram',
                'default_label'    => 'Gabung Grup Telegram',
                'default_sublabel' => 'Bergabung ke ruang obrolan Telegram',
                'placeholder'      => 'https://t.me/nama_grup',
                'color'            => '#229ED9',
            ],
            'discord' => [
                'name'             => 'Discord',
                'category'         => 'community',
                'action'           => 'Join',
                'icon'             => 'discord',
                'default_label'    => 'Join Server Discord',
                'default_sublabel' => 'Bergabung ke server komunitas Discord',
                'placeholder'      => 'https://discord.gg/invite_code',
                'color'            => '#5865F2',
            ],
            'spotify' => [
                'name'             => 'Spotify',
                'category'         => 'sosmed',
                'action'           => 'Follow',
                'icon'             => 'spotify',
                'default_label'    => 'Follow Akun / Podcast Spotify',
                'default_sublabel' => 'Dengarkan & follow Spotify kreator',
                'placeholder'      => 'https://open.spotify.com/artist/...',
                'color'            => '#1DB954',
            ],
            'github' => [
                'name'             => 'GitHub',
                'category'         => 'sosmed',
                'action'           => 'Follow',
                'icon'             => 'github',
                'default_label'    => 'Follow Profil GitHub',
                'default_sublabel' => 'Kunjungi & follow profil GitHub kreator',
                'placeholder'      => 'https://github.com/username',
                'color'            => '#24292e',
            ],
            'pinterest' => [
                'name'             => 'Pinterest',
                'category'         => 'sosmed',
                'action'           => 'Follow',
                'icon'             => 'pinterest',
                'default_label'    => 'Follow Akun Pinterest',
                'default_sublabel' => 'Kunjungi & follow board Pinterest kreator',
                'placeholder'      => 'https://pinterest.com/username',
                'color'            => '#BD081C',
            ],
            'website' => [
                'name'             => 'Website / Link Kustom',
                'category'         => 'other',
                'action'           => 'Kunjungi',
                'icon'             => 'website',
                'default_label'    => 'Kunjungi Tautan Resmi',
                'default_sublabel' => 'Buka dan jelajahi tautan website kreator',
                'placeholder'      => 'https://website-anda.com',
                'color'            => '#16A34A',
            ],
        ];
    }

    /**
     * Dapatkan metadata untuk satu platform tertentu.
     */
    public static function getPlatformRequirementMeta(string $platform): array
    {
        $all = self::getAvailablePlatforms();
        return $all[$platform] ?? [
            'name'             => ucfirst($platform),
            'category'         => 'other',
            'action'           => 'Buka',
            'icon'             => 'website',
            'default_label'    => 'Kunjungi Tautan ' . ucfirst($platform),
            'default_sublabel' => 'Buka tautan yang diberikan kreator',
            'placeholder'      => 'https://...',
            'color'            => '#16A34A',
        ];
    }

    /**
     * Dapatkan daftar syarat klaim gratis yang aktif (berdasarkan platform yang diisi kreator).
     */
    public function getActiveFreeRequirements(): array
    {
        if (!$this->require_follow_join) {
            return [];
        }

        $items = [];

        // 1. Cek dynamic custom_free_requirements (Repeater baru)
        if (!empty($this->custom_free_requirements) && is_array($this->custom_free_requirements)) {
            foreach ($this->custom_free_requirements as $idx => $req) {
                $platform = $req['platform'] ?? 'website';
                $url = trim($req['url'] ?? '');
                if (empty($url)) continue;

                $meta = self::getPlatformRequirementMeta($platform);
                $label = !empty($req['label']) ? trim($req['label']) : $meta['default_label'];
                $action = !empty($req['action']) ? trim($req['action']) : $meta['action'];

                $items[] = [
                    'key'      => 'cr_' . $platform . '_' . $idx,
                    'category' => $meta['category'],
                    'platform' => $meta['name'],
                    'action'   => $action,
                    'label'    => $label,
                    'sublabel' => $meta['default_sublabel'],
                    'url'      => $url,
                    'icon'     => $meta['icon'],
                    'color'    => $meta['color'],
                ];
            }

            if (!empty($items)) {
                return $items;
            }
        }

        // 2. Fallback Backward-Compatibility ke kolom legacy jika custom belum diisi
        // 1. Instagram
        if (!empty($this->follow_instagram_url)) {
            $items[] = [
                'key'      => 'ig',
                'category' => 'sosmed',
                'platform' => 'Instagram',
                'action'   => 'Follow',
                'label'    => 'Follow Akun Instagram',
                'sublabel' => 'Kunjungi & follow profil Instagram kreator',
                'url'      => $this->follow_instagram_url,
                'icon'     => 'instagram',
                'color'    => '#E1306C',
            ];
        } elseif (!empty($this->social_follow_url)) {
            $items[] = [
                'key'      => 'legacy_social',
                'category' => 'sosmed',
                'platform' => 'Sosial Media',
                'action'   => 'Follow',
                'label'    => 'Follow Akun Sosial Media',
                'sublabel' => 'Kunjungi & follow profil kreator',
                'url'      => $this->social_follow_url,
                'icon'     => 'website',
                'color'    => '#16A34A',
            ];
        }

        // 2. TikTok
        if (!empty($this->follow_tiktok_url)) {
            $items[] = [
                'key'      => 'tiktok',
                'category' => 'sosmed',
                'platform' => 'TikTok',
                'action'   => 'Follow',
                'label'    => 'Follow Akun TikTok',
                'sublabel' => 'Kunjungi & follow profil TikTok kreator',
                'url'      => $this->follow_tiktok_url,
                'icon'     => 'tiktok',
                'color'    => '#000000',
            ];
        }

        // 3. X (Twitter)
        if (!empty($this->follow_x_url)) {
            $items[] = [
                'key'      => 'x',
                'category' => 'sosmed',
                'platform' => 'X (Twitter)',
                'action'   => 'Follow',
                'label'    => 'Follow Akun X (Twitter)',
                'sublabel' => 'Kunjungi & ikuti akun X kreator',
                'url'      => $this->follow_x_url,
                'icon'     => 'x',
                'color'    => '#000000',
            ];
        }

        // 4. YouTube
        if (!empty($this->follow_youtube_url)) {
            $items[] = [
                'key'      => 'youtube',
                'category' => 'sosmed',
                'platform' => 'YouTube',
                'action'   => 'Subscribe',
                'label'    => 'Subscribe Channel YouTube',
                'sublabel' => 'Kunjungi & subscribe channel YouTube kreator',
                'url'      => $this->follow_youtube_url,
                'icon'     => 'youtube',
                'color'    => '#FF0000',
            ];
        }

        // 5. WhatsApp
        if (!empty($this->join_whatsapp_url)) {
            $items[] = [
                'key'      => 'wa',
                'category' => 'community',
                'platform' => 'WhatsApp',
                'action'   => 'Join',
                'label'    => 'Join Grup / Channel WhatsApp',
                'sublabel' => 'Bergabung ke komunitas WhatsApp kreator',
                'url'      => $this->join_whatsapp_url,
                'icon'     => 'whatsapp',
                'color'    => '#25D366',
            ];
        } elseif (!empty($this->community_join_url)) {
            $items[] = [
                'key'      => 'legacy_community',
                'category' => 'community',
                'platform' => 'Komunitas',
                'action'   => 'Join',
                'label'    => 'Join Komunitas Kreator',
                'sublabel' => 'Bergabung ke grup komunitas kreator',
                'url'      => $this->community_join_url,
                'icon'     => 'whatsapp',
                'color'    => '#25D366',
            ];
        }

        // 6. Telegram
        if (!empty($this->join_telegram_url)) {
            $items[] = [
                'key'      => 'tele',
                'category' => 'community',
                'platform' => 'Telegram',
                'action'   => 'Join',
                'label'    => 'Join Channel Telegram',
                'sublabel' => 'Bergabung ke channel Telegram kreator',
                'url'      => $this->join_telegram_url,
                'icon'     => 'telegram',
                'color'    => '#229ED9',
            ];
        }

        // 7. Discord
        if (!empty($this->join_discord_url)) {
            $items[] = [
                'key'      => 'discord',
                'category' => 'community',
                'platform' => 'Discord',
                'action'   => 'Join',
                'label'    => 'Join Server Discord',
                'sublabel' => 'Bergabung ke server Discord kreator',
                'url'      => $this->join_discord_url,
                'icon'     => 'discord',
                'color'    => '#5865F2',
            ];
        }

        return $items;
    }

    // =========================================================================
    // Pricing Type Helpers (Fixed, Free, Pay What You Want, Traktir)
    // =========================================================================

    public function isFixedPrice(): bool
    {
        return ($this->pricing_type ?? 'fixed') === 'fixed';
    }

    public function isFree(): bool
    {
        if (($this->pricing_type ?? '') === 'free') {
            return true;
        }
        if ($this->isTraktir() || $this->isPayWhatYouWant()) {
            return false;
        }
        $salePrice = $this->sale_price !== null ? (float) $this->sale_price : null;
        $price = (float) ($this->price ?? 0);
        return ($salePrice !== null && $salePrice <= 0) || ($salePrice === null && $price <= 0);
    }

    public function isPayWhatYouWant(): bool
    {
        return ($this->pricing_type ?? '') === 'pay_what_you_want';
    }

    public function isTraktir(): bool
    {
        return ($this->pricing_type ?? '') === 'traktir';
    }

    public function getEffectivePricingTypeAttribute(): string
    {
        if ($this->isTraktir()) {
            return 'traktir';
        }
        if ($this->isPayWhatYouWant()) {
            return 'pay_what_you_want';
        }
        if ($this->isFree()) {
            return 'free';
        }
        return 'fixed';
    }

    public function getTraktirUnitIconAttribute($value): string
    {
        $map = [
            'coffee' => '☕', 'kopi'   => '☕',
            'tea'    => '🍵', 'teh'    => '🍵',
            'meal'   => '🍔', 'burger' => '🍔',
            'pizza'  => '🍕',
            'gift'   => '🎁', 'hadiah' => '🎁',
            'heart'  => '❤️', 'hati'   => '❤️',
        ];

        $key = strtolower(trim((string) $value));
        return $map[$key] ?? ($value ?: '☕');
    }

    public function getTraktirUnitIconKeyAttribute(): string
    {
        $raw = strtolower(trim((string) ($this->attributes['traktir_unit_icon'] ?? 'coffee')));
        $emojiToKey = [
            '☕' => 'coffee', '🍵' => 'tea', '🍔' => 'meal',
            '🍕' => 'pizza',  '🎁' => 'gift', '❤️' => 'heart',
        ];
        return $emojiToKey[$raw] ?? ($raw ?: 'coffee');
    }
}
