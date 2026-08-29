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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
