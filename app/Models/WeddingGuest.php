<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class WeddingGuest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
        'pax'           => 'integer',
        'actual_pax'    => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guest) {
            if (empty($guest->qr_code)) {
                $guest->qr_code = 'W-' . strtoupper(Str::random(8));
            }
        });
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(WeddingWish::class, 'guest_id');
    }

    public function gifts(): HasMany
    {
        return $this->hasMany(WeddingGift::class, 'guest_id');
    }

    public function getInvitationUrlAttribute(): string
    {
        return url('/w/' . $this->wedding->slug . '?to=' . urlencode($this->name) . '&c=' . $this->qr_code);
    }
}
