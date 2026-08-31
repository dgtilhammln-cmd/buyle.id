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
        'price', 'sale_price', 'stock', 'weight', 'sku',
        'product_category_id', 'product_sub_category_id', 'is_featured',
        'product_type', 'file_type', 'digital_resource', 'seller_id', 'creator_group_id', 'sold_count', 'views_count', 'unit', 'min_order', 'max_order', 'rating', 'tiktok_video_url'
    ];

    protected $casts = [
        // Existing casts
        'is_active'      => 'boolean',
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
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
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
    public function scopeOrdered(Builder $query): Builder { return $query->orderBy('created_at', 'desc'); }

    /**
     * Hanya produk yang ditandai sebagai unggulan.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Produk yang masih ada stoknya.
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
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

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/'.$this->image) : asset('images/service-default.jpg');
    }
    public function getOgImageUrlAttribute(): string
    {
        return $this->og_image ? asset('storage/'.$this->og_image) : $this->image_url;
    }
    public function getGalleryUrlsAttribute(): array
    {
        $urls = [];
        if (is_array($this->gallery)) {
            foreach ($this->gallery as $img) {
                $urls[] = asset('storage/' . $img);
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
    public function getUrlAttribute(): string
    {
        return route('services.show', $this->slug);
    }

    // =========================================================================
    // E-commerce Accessors
    // =========================================================================

    /**
     * Harga yang ditampilkan (sale_price jika ada, jika tidak price).
     */
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price ?? 0);
    }

    /**
     * Apakah produk sedang dalam kondisi diskon.
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && (float) $this->sale_price < (float) $this->price;
    }

    /**
     * Persentase diskon (0 jika tidak ada diskon).
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (! $this->is_on_sale || (float) $this->price === 0.0) {
            return 0;
        }

        return (int) round((1 - (float) $this->sale_price / (float) $this->price) * 100);
    }

    /**
     * Apakah produk masih tersedia (stok > 0) atau berupa jasa.
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->type === 'service' || $this->stock > 0;
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
}
