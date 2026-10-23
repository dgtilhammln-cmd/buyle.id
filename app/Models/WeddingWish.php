<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingWish extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_approved' => 'boolean',
        'latitude'    => 'float',
        'longitude'   => 'float',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(WeddingGuest::class, 'guest_id');
    }
}
