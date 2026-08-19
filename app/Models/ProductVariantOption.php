<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariantOption extends Model
{
    protected $fillable = [
        'product_id',
        'name',
    ];

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * Produk (service) yang memiliki opsi varian ini.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Semua nilai yang tersedia untuk opsi varian ini.
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductVariantValue::class, 'variant_option_id');
    }
}
