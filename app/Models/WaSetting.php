<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WaSetting extends Model
{
    protected $fillable = ['label', 'nomor_wa', 'template_pesan', 'is_active', 'is_primary', 'order'];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_primary' => 'boolean',
        'order'      => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }

    /**
     * Get the primary WA setting
     */
    public static function primary(): ?static
    {
        return static::where('is_active', true)
            ->where('is_primary', true)
            ->first()
            ?? static::where('is_active', true)->orderBy('order')->first();
    }

    /**
     * Get formatted WA URL with encoded message
     */
    public function getWaUrlAttribute(): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $this->nomor_wa);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }
        return 'https://wa.me/' . $nomor . '?text=' . urlencode($this->template_pesan);
    }

    /**
     * Build WA URL with custom context
     */
    public function buildUrl(?string $product = null, ?string $customMessage = null): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $this->nomor_wa);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }
        $message = $customMessage ?? str_replace(
            '[produk]',
            $product ?? 'produk Anda',
            $this->template_pesan
        );
        return 'https://wa.me/' . $nomor . '?text=' . urlencode($message);
    }
}
