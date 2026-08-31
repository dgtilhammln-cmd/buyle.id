<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVisit extends Model
{
    protected $fillable = [
        'product_id',
        'seller_id',
        'session_id',
        'ip_address',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'product_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
