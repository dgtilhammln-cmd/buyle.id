<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'receiver_name',
        'phone',
        'province',
        'city',
        'district',
        'village',
        'postal_code',
        'full_address',
        'latitude',
        'longitude',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * User pemilik alamat ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Hanya alamat default.
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    /**
     * Filter berdasarkan user tertentu.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // =========================================================================
    // Accessors / Helpers
    // =========================================================================

    /**
     * Format alamat lengkap satu baris.
     */
    public function getFullAddressOneLinerAttribute(): string
    {
        return "{$this->full_address}, {$this->district}, {$this->city}, {$this->province} {$this->postal_code}";
    }

    /**
     * Konversi ke array yang siap disimpan sebagai JSON di tabel orders.
     *
     * @return array<string, mixed>
     */
    public function toShippingSnapshot(): array
    {
        return [
            'receiver_name' => $this->receiver_name,
            'phone'         => $this->phone,
            'province'      => $this->province,
            'city'          => $this->city,
            'district'      => $this->district,
            'postal_code'   => $this->postal_code,
            'full_address'  => $this->full_address,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'label'         => $this->label,
        ];
    }
}
