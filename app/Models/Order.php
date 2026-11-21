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
        'customer_email',
        'status',
        'subtotal',
        'shipping_cost',
        'platform_fee',
        'admin_fee',
        'payment_gateway_fee',
        'discount',
        'total',
        'shipping_address',
        'cashier_id',
        'cashier_name',
        'notes',
        'claim_proof',
        'source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
    ];

    protected function casts(): array
    {
        return [
            'status'              => OrderStatus::class,
            'subtotal'            => 'decimal:2',
            'shipping_cost'       => 'decimal:2',
            'platform_fee'        => 'decimal:2',
            'admin_fee'           => 'decimal:2',
            'payment_gateway_fee' => 'decimal:2',
            'discount'            => 'decimal:2',
            'total'               => 'decimal:2',
            'shipping_address'    => 'array',
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

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
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

    /**
     * E-Ticket pass untuk produk event di order ini.
     */
    public function ticketPasses(): HasMany
    {
        return $this->hasMany(TicketPass::class);
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
     * Generate nomor order unik berstandar 4 pilar bisnis:
     * - MKT : Marketplace & Produk Fisik/Digital
     * - TIX : Ticketing & Event
     * - POS : Kasir POS & Dine-in
     * - WED : Paket Sewa Undangan Digital
     * - AMP : Amplop Digital Kado Pernikahan
     * - DOM : Domain Creator
     *
     * Format: TYPE-YYYYMMDD-XXXX (contoh: MKT-20261117-0001, TIX-20261117-0001)
     */
    public static function generateOrderNumber(string $type = 'MKT'): string
    {
        $type = strtoupper(trim($type));
        if (!in_array($type, ['MKT', 'TIX', 'POS', 'WED', 'AMP', 'DOM'])) {
            $type = 'MKT';
        }

        $date   = now()->format('Ymd');
        $prefix = "{$type}-{$date}-";

        $lastOrder = static::where(function($q) use ($prefix, $type, $date) {
                $q->where('order_number', 'like', $prefix . '%')
                  ->orWhere('order_number', 'like', "BYL-{$date}-%")
                  ->orWhere('order_number', 'like', "ORD-{$date}-%");
            })
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        $sequence = 1;
        if ($lastOrder && !empty($lastOrder->order_number)) {
            $parts = explode('-', $lastOrder->order_number);
            $lastSeq = (int) end($parts);
            if ($lastSeq > 0) {
                $sequence = $lastSeq + 1;
            }
        }

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

    /**
     * Daftar bukti screenshot klaim per platform (mendukung JSON array maupun string legacy single URL).
     */
    public function getClaimProofsListAttribute(): array
    {
        if (empty($this->claim_proof)) {
            return [];
        }

        // Coba decode JSON
        $decoded = json_decode($this->claim_proof, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_map(function ($item) {
                if (is_array($item)) {
                    $proof = $item['proof'] ?? ($item['url'] ?? '');
                    if ($proof && !str_starts_with($proof, 'http')) {
                        $item['url'] = asset('storage/' . $proof);
                    } else {
                        $item['url'] = $proof;
                    }
                }
                return $item;
            }, $decoded);
        }

        // Fallback: single file path legacy
        $url = str_starts_with($this->claim_proof, 'http')
            ? $this->claim_proof
            : asset('storage/' . $this->claim_proof);

        return [
            [
                'step_id'  => 'legacy',
                'platform' => 'Syarat Klaim',
                'label'    => 'Bukti Follow / Join',
                'proof'    => $this->claim_proof,
                'url'      => $url,
            ]
        ];
    }
}
