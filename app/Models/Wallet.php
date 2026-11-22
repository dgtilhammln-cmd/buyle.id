<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $fillable = [
        'user_id',
        'balance',
        'escrow_balance',
        'locked_balance',
        'status',
        'tier',
    ];

    protected $casts = [
        'balance'        => 'decimal:2',
        'escrow_balance' => 'decimal:2',
        'locked_balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mutations(): HasMany
    {
        return $this->hasMany(WalletMutation::class)->orderByDesc('id');
    }

    public function topups(): HasMany
    {
        return $this->hasMany(WalletTopup::class)->orderByDesc('id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isFrozen(): bool
    {
        return $this->status === 'frozen';
    }
}
