<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Testimonial extends Model
{
    protected $fillable = [
        'name', 'company', 'position', 'content',
        'rating', 'photo', 'is_active', 'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating'    => 'integer',
        'order'     => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }

    /**
     * Get photo URL or generated initial avatar
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        // Return UI Avatars fallback with initials
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=FFD700&color=000000&size=200&bold=true&format=svg";
    }
}
