<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PromoSection extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'banner', 'view_all_url', 'sort_order', 'is_active', 'selection_type', 'category_id',
        'start_time', 'end_time', 'bg_color_1', 'bg_color_2', 'logo'
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    /**
     * Produk yang dipilih secara manual (melalui pivot table).
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promo_section_service')
                    ->withPivot('sort_order')
                    ->orderByPivot('sort_order');
    }

    /**
     * Dapatkan produk berdasarkan tipe seleksi.
     */
    public function getDynamicServicesAttribute()
    {
        $limit = 10; // Batas wajar untuk slider

        switch ($this->selection_type) {
            case 'category':
                return Service::active()->where('product_category_id', $this->category_id)->orderBy('order')->limit($limit)->get();
            case 'discount':
                return Service::active()->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price')->where('sale_price', '>', 0)->orderBy('order')->limit($limit)->get();
            case 'all':
                return Service::active()->orderBy('order')->limit($limit)->get();
            case 'manual':
            default:
                return $this->services()->limit($limit)->get();
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
