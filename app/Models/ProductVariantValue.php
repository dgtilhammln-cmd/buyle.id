<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariantValue extends Model
{
    protected $fillable = [
        'variant_option_id',
        'value',
        'price_adjustment',
        'stock',
        'sku',
    ];

    protected function casts(): array
    {
        return [
            'price_adjustment' => 'decimal:2',
            'stock'            => 'integer',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * Opsi varian yang memiliki nilai ini.
     */
    public function variantOption(): BelongsTo
    {
        return $this->belongsTo(ProductVariantOption::class, 'variant_option_id');
    }

    /**
     * Semua item cart yang memilih varian ini.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'variant_value_id');
    }

    /**
     * Semua item order yang memilih varian ini.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'variant_value_id');
    }

    // =========================================================================
    // Accessors / Helpers
    // =========================================================================

    /**
     * Hitung harga final varian berdasarkan harga dasar produk + price_adjustment.
     */
    public function finalPrice(): float
    {
        $basePrice = $this->variantOption?->product?->price ?? 0;
        return max(0, $basePrice + $this->price_adjustment);
    }

    /**
     * Apakah varian ini masih tersedia (stock > 0).
     */
    public function isAvailable(): bool
    {
        return $this->stock === null || $this->stock > 0;
    }
}
