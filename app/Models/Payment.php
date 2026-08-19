<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_number',
        'method',
        'gateway',
        'status',
        'amount',
        'midtrans_transaction_id',
        'midtrans_token',
        'midtrans_redirect_url',
        'paid_at',
        'expired_at',
        'raw_response',
    ];

    protected function casts(): array
    {
        return [
            'status'       => PaymentStatus::class,
            'amount'       => 'decimal:2',
            'paid_at'      => 'datetime',
            'expired_at'   => 'datetime',
            'raw_response' => 'array',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * Order yang terkait dengan pembayaran ini.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Hanya pembayaran yang berhasil.
     */
    public function scopeSuccess(Builder $query): Builder
    {
        return $query->where('status', PaymentStatus::Success->value);
    }

    /**
     * Hanya pembayaran yang masih pending.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', PaymentStatus::Pending->value);
    }

    /**
     * Pembayaran yang sudah kedaluwarsa.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', PaymentStatus::Expired->value)
            ->orWhere(function (Builder $q) {
                $q->where('status', PaymentStatus::Pending->value)
                    ->where('expired_at', '<', now());
            });
    }

    // =========================================================================
    // Accessors / Helpers
    // =========================================================================

    /**
     * Apakah pembayaran sudah lunas.
     */
    public function isPaid(): bool
    {
        return $this->status->isPaid();
    }

    /**
     * Generate nomor pembayaran unik.
     * Format: PAY-YYYYMMDD-XXXX
     */
    public static function generatePaymentNumber(): string
    {
        $date   = now()->format('Ymd');
        $prefix = "PAY-{$date}-";

        $last = static::where('payment_number', 'like', $prefix . '%')
            ->orderByDesc('payment_number')
            ->first();

        $sequence = $last ? (int) substr($last->payment_number, -4) + 1 : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
