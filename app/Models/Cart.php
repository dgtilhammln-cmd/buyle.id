<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'variant_value_id',
        'reseller_id',
        'custom_price',
        'qty',
    ];

    protected function casts(): array
    {
        return [
            'custom_price' => 'decimal:2',
            'qty'          => 'integer',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * User pemilik cart (null jika guest).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Produk (service) di dalam cart ini.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Reseller / Creator pemilik Halaman Bio yang menawarkan produk ini.
     */
    public function reseller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    /**
     * Varian yang dipilih (opsional).
     */
    public function variantValue(): BelongsTo
    {
        return $this->belongsTo(ProductVariantValue::class, 'variant_value_id');
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Cart milik user yang sedang login.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Cart milik session (guest).
     */
    public function scopeForSession(Builder $query, string $sessionId): Builder
    {
        return $query->whereNull('user_id')->where('session_id', $sessionId);
    }

    // =========================================================================
    // Accessors / Helpers
    // =========================================================================

    /**
     * Harga satuan item (mempertimbangkan harga markup reseller & varian).
     */
    public function getUnitPriceAttribute(): float
    {
        if ($this->custom_price && (float)$this->custom_price > 0) {
            return (float) $this->custom_price;
        }

        if ($this->variantValue) {
            return $this->variantValue->finalPrice();
        }

        return (float) ($this->product?->sale_price ?? $this->product?->price ?? 0);
    }

    /**
     * Total harga item (unit price × qty).
     */
    public function getSubtotalAttribute(): float
    {
        return $this->unit_price * $this->qty;
    }
}
