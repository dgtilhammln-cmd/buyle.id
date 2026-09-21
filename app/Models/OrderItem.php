<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_value_id',
        'seller_id',
        'reseller_id',
        'product_name',
        'variant_name',
        'price',
        'base_whitelabel_price',
        'reseller_margin',
        'creator_earnings',
        'qty',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'price'                 => 'decimal:2',
            'base_whitelabel_price' => 'decimal:2',
            'reseller_margin'       => 'decimal:2',
            'creator_earnings'      => 'decimal:2',
            'qty'                   => 'integer',
            'subtotal'              => 'decimal:2',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * Order yang mengandung item ini.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Produk asli (bisa null jika produk sudah dihapus).
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Creator asli pemilik produk.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Reseller / Whitelabel yang menjual produk ini.
     */
    public function reseller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    /**
     * Nilai varian yang dipilih (bisa null).
     */
    public function variantValue(): BelongsTo
    {
        return $this->belongsTo(ProductVariantValue::class, 'variant_value_id');
    }

    // =========================================================================
    // Accessors / Helpers
    // =========================================================================

    /**
     * Label produk lengkap termasuk nama varian (untuk tampilan).
     */
    public function getFullNameAttribute(): string
    {
        if ($this->variant_name) {
            return "{$this->product_name} ({$this->variant_name})";
        }

        return $this->product_name;
    }

    /**
     * Alias accessor quantity untuk qty.
     */
    public function getQuantityAttribute(): int
    {
        return (int) $this->qty;
    }
}
