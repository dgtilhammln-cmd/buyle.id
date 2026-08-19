<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreatorProductGroup extends Model
{
    protected $fillable = [
        'seller_id',
        'name',
        'slug',
        'order',
        'is_active',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'creator_group_id');
    }
