<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTopup extends Model
{
    protected $table = 'wallet_topups';

    protected $fillable = [
        'invoice_code',
        'user_id',
        'wallet_id',
        'amount',
        'admin_fee',
        'total_amount',
        'payment_method',
        'payment_gateway',
        'payment_ref',
        'status',
        'paid_at',
        'metadata',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'admin_fee'    => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at'      => 'datetime',
        'metadata'     => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
