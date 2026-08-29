<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'tab',
        'badge',
        'badge_color',
        'description',
        'image',
        'icon_type',
        'icon_value',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order'     => 'integer',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * Semua produk (services) yang masuk kategori ini.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }

    /**
     * Semua sub-kategori dari kategori ini.
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany(ProductSubCategory::class, 'category_id')->orderBy('order');
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Hanya kategori yang aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Urutkan berdasarkan kolom order ascending.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc');
    }

    // =========================================================================
    // Accessors / Helpers
    // =========================================================================

    /**
     * URL gambar kategori (dengan fallback ke placeholder).
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return asset('images/placeholder-category.webp');
    }

    /**
     * Route model binding menggunakan slug.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
