<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductSubCategory extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'contoh_produk',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_sub_category_id');
    }
}
