<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreatorBioBlock extends Model
{
    protected $table = 'creator_bio_blocks';

    protected $fillable = [
        'creator_id',
        'type',       // link, pdf, tiktok, affiliate, shopee, buyle_product
        'title',
        'url',
        'data_json',  // JSON: image, thumb, description, product_id, price, etc.
        'order',
        'is_active',
    ];

    protected $casts = [
        'data_json' => 'array',
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(CreatorProfile::class, 'creator_id');
    }

    public function getImageAttribute(): ?string
    {
        return $this->data_json['image'] ?? null;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->data_json['description'] ?? null;
    }
}
