<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PromoSection extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'banner', 'view_all_url', 'sort_order', 'is_active', 'selection_type',
        'category_id', 'sub_category_id',
        'start_time', 'end_time', 'bg_color_1', 'bg_color_2', 'logo', 'product_type_filter'
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'sort_order'      => 'integer',
        'start_time'      => 'datetime',
        'end_time'        => 'datetime',
        'sub_category_id' => 'integer',
        'category_id'     => 'integer',
    ];

    /** Relasi ke kategori induk */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /** Relasi ke sub-kategori (opsional) */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(ProductSubCategory::class, 'sub_category_id');
    }

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
     * Untuk tipe 'category': cek sub_category_id dulu, kalau ada filter by sub-kategori,
     * kalau tidak ada (null/kosong) fallback ke category_id (induk).
     */
    public function getDynamicServicesAttribute()
    {
        $limit = 10; // Batas wajar untuk slider

        switch ($this->selection_type) {
            case 'category':
                $query = Product::active();
                if ($this->sub_category_id) {
                    // Filter berdasarkan sub-kategori spesifik
                    $query->where('product_sub_category_id', $this->sub_category_id);
                } else {
                    // Fallback ke kategori induk
                    $query->where('product_category_id', $this->category_id);
                }
                return $query->limit($limit)->get();

            case 'product_type':
                return Product::active()->where('product_type', $this->product_type_filter)->limit($limit)->get();

            case 'discount':
                return Product::active()
                    ->whereNotNull('sale_price')
                    ->whereColumn('sale_price', '<', 'price')
                    ->where('sale_price', '>', 0)
                    ->limit($limit)->get();

            case 'all':
                return Product::active()->limit($limit)->get();

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
