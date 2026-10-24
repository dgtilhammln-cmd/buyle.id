<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wedding extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'event_date'               => 'datetime',
        'ceremony_date'            => 'datetime',
        'digital_envelope_enabled' => 'boolean',
        'is_active'                => 'boolean',
        'data_json'                => 'array',
        'event_latitude'           => 'float',
        'event_longitude'          => 'float',
        'guest_limit'              => 'integer',
        'paid_amount'              => 'float',
        'paid_at'                  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(WeddingGuest::class)->orderBy('name', 'asc');
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(WeddingWish::class)->latest();
    }

    public function gifts(): HasMany
    {
        return $this->hasMany(WeddingGift::class)->latest();
    }

    public function totalGiftsAmount(): float
    {
        return (float) $this->gifts()->where('payment_status', 'settlement')->sum('amount');
    }

    public function checkedInCount(): int
    {
        return $this->guests()->where('is_checked_in', true)->count();
    }

    public function totalGuestsCount(): int
    {
        return $this->guests()->count();
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid' || $this->payment_status === 'free';
    }

    public function remainingGuestSlots(): int
    {
        $limit = max(0, (int) ($this->guest_limit ?: 150));
        return max(0, $limit - $this->totalGuestsCount());
    }

    public function canAddGuests(int $count = 1): bool
    {
        $limit = max(0, (int) ($this->guest_limit ?: 150));
        return ($this->totalGuestsCount() + $count) <= $limit;
    }

    public function getPublicUrlAttribute(): string
    {
        return url('/wedding/' . $this->slug);
    }

    public function getReceptionUrlAttribute(): string
    {
        return url('/wedding/' . $this->slug . '/buku-tamu');
    }
}
