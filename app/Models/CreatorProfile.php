<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreatorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'store_name',
        'store_slug',
        'store_description',
        'creator_type',
        'social_links',
        'address',
        'province_id',
        'city_id',
        'subdistrict_id',
        'province_name',
        'city_name',
        'meta_title',
        'meta_desc',
        'meta_keywords',
        'store_banner_1',
        'store_banner_2',
        // Bio Link fields
        'bio_role',
        'bio_theme',
        'bio_config',
    ];

    protected $casts = [
        'social_links' => 'array',
        'bio_config'   => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bioBlocks()
    {
        return $this->hasMany(CreatorBioBlock::class, 'creator_id')->orderBy('order');
    }
}
