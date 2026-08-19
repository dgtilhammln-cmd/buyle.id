<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'shipping_cost',
        'discount',
        'total',
        'shipping_address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status'           => OrderStatus::class,
            'subtotal'         => 'decimal:2',
            'shipping_cost'    => 'decimal:2',
            'discount'         => 'decimal:2',
            'total'            => 'decimal:2',
            'shipping_address' => 'array',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * User yang membuat order ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Semua item produk di dalam order ini.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Data pembayaran untuk order ini.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Data pengiriman untuk order ini.
     */
    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    /**
     * Penggunaan kupon untuk order ini.
     */
    public function couponUsage(): HasOne
    {
        return $this->hasOne(CouponUsage::class);
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Filter berdasarkan status tertentu.
     */
    public function scopeWithStatus(Builder $query, OrderStatus $status): Builder
    {
        return $query->where('status', $status->value);
    }

    /**
     * Hanya order yang aktif (belum batal/refund).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            OrderStatus::Cancelled->value,
            OrderStatus::Refunded->value,
        ]);
    }

    /**
     * Order milik user tertentu.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Urutkan dari yang terbaru.
     */
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    // =========================================================================
    // Static Helpers
    // =========================================================================

    /**
     * Generate nomor order unik.
     * Format: ORD-YYYYMMDD-XXXX (misal: ORD-20260713-0001)
     */
    public static function generateOrderNumber(): string
    {
        $date   = now()->format('Ymd');
        $prefix = "ORD-{$date}-";

        $lastOrder = static::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('order_number')
            ->lockForUpdate()
            ->first();

        $sequence = $lastOrder
            ? (int) substr($lastOrder->order_number, -4) + 1
            : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // =========================================================================
    // Accessors / Helpers
    // =========================================================================

    /**
     * Apakah order bisa dibatalkan oleh user.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            OrderStatus::Pending,
            OrderStatus::Confirmed,
        ]);
    }

    /**
     * Mendapatkan nama penerima dari snapshot shipping address.
     */
    public function getReceiverNameAttribute(): ?string
    {
        return $this->shipping_address['receiver_name'] ?? null;
    }
}
