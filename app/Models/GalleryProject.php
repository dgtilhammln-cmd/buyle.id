<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class GalleryProject extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'content', 'image', 'og_image', 'alt_text',
        'category', 'client', 'location', 'year', 'order',
        'is_active', 'is_published', 'is_featured',
        'meta_title', 'meta_desc', 'meta_keywords', 'tags',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
        'order'        => 'integer',
        'year'         => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $base = Str::slug($model->title);
                $slug = $base; $i = 1;
                while (static::where('slug', $slug)->exists()) { $slug = $base.'-'.$i++; }
                $model->slug = $slug;
            }
        });
    }

    public function scopeActive(Builder $q): Builder   { return $q->where('is_active', true); }
    public function scopePublished(Builder $q): Builder { return $q->where('is_published', true); }
    public function scopeOrdered(Builder $q): Builder   { return $q->orderBy('order')->orderBy('id'); }
    public function scopeByCategory(Builder $q, ?string $cat): Builder
    {
        return ($cat && $cat !== 'all') ? $q->where('category', $cat) : $q;
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/'.$this->image) : (\App\Models\Setting::get('logo') ? asset('storage/'.\App\Models\Setting::get('logo')) : asset('images/og-default.jpg'));
    }
    public function getOgImageUrlAttribute(): string
    {
        return $this->og_image ? asset('storage/'.$this->og_image) : $this->image_url;
    }
    public function getAltTextAttribute($v): string
    {
        return $v ?: $this->title.' - buyle.id';
    }
    public function getMetaTitleAttribute($v): string
    {
        return $v ?: $this->title.' | Galeri buyle.id';
    }
    public function getMetaDescAttribute($v): string
    {
        return $v ?: 'Proyek '.$this->title.' oleh buyle.id di '.($this->location ?: 'Indonesia').'.';
    }
    public function getUrlAttribute(): string
    {
        return $this->slug ? route('gallery.show', $this->slug) : route('gallery');
    }
    public function getTagsArrayAttribute(): array
    {
        return $this->tags ? array_map('trim', explode(',', $this->tags)) : [];
    }
}
