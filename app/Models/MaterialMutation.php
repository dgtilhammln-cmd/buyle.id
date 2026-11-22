<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialMutation extends Model
{
    protected $fillable = [
        'seller_id',
        'material_id',
        'type',
        'amount',
        'stock_before',
        'stock_after',
        'unit_cost',
        'order_id',
        'reference_note',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'stock_before' => 'decimal:2',
        'stock_after'  => 'decimal:2',
        'unit_cost'    => 'decimal:2',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'in_purchase'        => 'Restock Masuk',
            'out_pos_sale'       => 'Penjualan POS',
            'adjustment_waste'   => 'Bahan Rusak / Waste',
            'adjustment_opname'  => 'Penyesuaian Opname',
            default              => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }
}
