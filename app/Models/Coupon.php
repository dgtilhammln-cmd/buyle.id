<?php

namespace App\Models;

use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'category',
        'description',
        'badge',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'used_count',
        'is_active',
        'started_at',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'type'         => CouponType::class,
            'value'        => 'decimal:2',
            'min_purchase' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'is_active'    => 'boolean',
            'started_at'   => 'datetime',
            'expired_at'   => 'datetime',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * Semua catatan pemakaian kupon ini.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Hanya kupon yang aktif dan belum kedaluwarsa.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(fn(Builder $q) => $q->whereNull('started_at')->orWhere('started_at', '<=', now()))
            ->where(fn(Builder $q) => $q->whereNull('expired_at')->orWhere('expired_at', '>=', now()));
    }

    /**
     * Filter berdasarkan kode (case-insensitive).
     */
    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->where('code', strtoupper(trim($code)));
    }

    // =========================================================================
    // Accessors / Helpers
    // =========================================================================

    /**
     * Apakah kupon masih bisa digunakan (belum habis limit pemakaian).
     */
    public function isUsable(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->started_at && $this->started_at->isFuture()) {
            return false;
        }

        if ($this->expired_at && $this->expired_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Hitung jumlah diskon untuk subtotal tertentu.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < (float) $this->min_purchase) {
            return 0;
        }

        return $this->type->calculate(
            value:       (float) $this->value,
            subtotal:    $subtotal,
            maxDiscount: $this->max_discount ? (float) $this->max_discount : null,
        );
    }

    /**
     * Validasi apakah kupon bisa digunakan untuk subtotal tertentu.
     *
     * @throws \InvalidArgumentException
     */
    public function validate(float $subtotal): void
    {
        if (! $this->isUsable()) {
            throw new \InvalidArgumentException('Kupon tidak valid atau sudah kedaluwarsa.');
        }

        if ($subtotal < (float) $this->min_purchase) {
            throw new \InvalidArgumentException(
                'Minimum pembelian untuk kupon ini adalah Rp ' .
                number_format($this->min_purchase, 0, ',', '.')
            );
        }
    }

    /**
     * Tampilkan nilai kupon dalam format yang mudah dibaca.
     */
    public function getFormattedValueAttribute(): string
    {
        return $this->type->formatValue((float) $this->value);
    }
}
