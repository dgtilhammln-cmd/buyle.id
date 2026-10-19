<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomationShortLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'product_id',
        'code',
        'target_url',
        'clicks_count',
        'orders_count',
        'revenue',
    ];

    protected $casts = [
        'clicks_count' => 'integer',
        'orders_count' => 'integer',
        'revenue'      => 'decimal:2',
    ];

    public function campaign()
    {
        return $this->belongsTo(AutomationCampaign::class, 'campaign_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Dapatkan URL penuh short link (contoh: https://buyle.id/c/x81z9)
     */
    public function getShortUrlAttribute(): string
    {
        return url('/c/' . $this->code);
    }
}
