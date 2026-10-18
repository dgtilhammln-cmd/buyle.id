<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosBalanceMutation extends Model
{
    protected $fillable = [
        'creator_id',
        'type', // 'topup', 'fee_deduction', 'adjustment'
        'amount',
        'balance_before',
        'balance_after',
        'order_id',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'amount'         => 'float',
        'balance_before' => 'float',
        'balance_after'  => 'float',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(CreatorProfile::class, 'creator_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
