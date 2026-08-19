<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'courier_name',
        'courier_service',
        'tracking_number',
        'status',
        'estimated_delivery',
        'shipped_at',
        'delivered_at',
        'raw_tracking',
    ];

    protected function casts(): array
    {
        return [
            'status'             => ShipmentStatus::class,
            'estimated_delivery' => 'date',
            'shipped_at'         => 'datetime',
            'delivered_at'       => 'datetime',
            'raw_tracking'       => 'array',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * Order yang terkait dengan pengiriman ini.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Pengiriman yang sedang dalam perjalanan.
     */
    public function scopeInTransit(Builder $query): Builder
    {
        return $query->whereIn('status', [
            ShipmentStatus::PickedUp->value,
            ShipmentStatus::InTransit->value,
        ]);
    }

    /**
     * Pengiriman yang sudah sampai.
     */
    public function scopeDelivered(Builder $query): Builder
    {
        return $query->where('status', ShipmentStatus::Delivered->value);
    }

    // =========================================================================
    // Accessors / Helpers
    // =========================================================================

    /**
     * Label kurir + layanan (misal: "JNE - REG").
     */
    public function getCourierLabelAttribute(): string
    {
        if ($this->courier_service) {
            return strtoupper($this->courier_name) . ' ' . strtoupper($this->courier_service);
        }

        return strtoupper($this->courier_name);
    }

    /**
     * Apakah pengiriman sudah selesai.
     */
    public function isDelivered(): bool
    {
        return $this->status === ShipmentStatus::Delivered;
    }

    /**
     * URL tracking kurir (jika didukung).
     * Contoh integrasi dengan binderbyte.com API.
     */
    public function getTrackingUrlAttribute(): ?string
    {
        if (! $this->tracking_number) {
            return null;
        }

        return "https://www.cekresi.com/?noresi={$this->tracking_number}";
    }
}
