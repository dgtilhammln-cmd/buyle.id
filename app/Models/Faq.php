<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Faq extends Model
{
    protected $fillable = [
        'slug', 'image', 'alt_text', 'category', 'tags', 'is_published', 
        'published_at', 'views', 'og_image', 'author_id', 'show_toc',
    ];

    protected $casts = [
        'tags'         => 'array',
        'is_published' => 'boolean',
        'show_toc'     => 'boolean',
        'published_at' => 'datetime',
        'views'        => 'integer',
    ];

    protected $with = [];

    /** Cache per-request translation resolve */
    private array $_translationCache = [];

    public function authorRel()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function translations()
    {
        return $this->hasMany(FaqTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations->firstWhere('locale', $locale) 
            ?: $this->translations->firstWhere('locale', 'id');
    }

    public function currentTranslation()
    {
        $locale = app()->getLocale();
        if (!array_key_exists($locale, $this->_translationCache)) {
            $this->_translationCache[$locale] = $this->translate($locale);
        }
        return $this->_translationCache[$locale];
    }

    // Accessors for translated fields
    public function getTitleAttribute() { return $this->currentTranslation()?->title; }
    public function getExcerptAttribute() { return $this->currentTranslation()?->excerpt; }
    public function getContentAttribute() { return $this->currentTranslation()?->content; }
    public function getFaqsAttribute() { return $this->currentTranslation()?->faqs; }
    public function getCtaButtonAttribute() { return $this->currentTranslation()?->cta_button; }
    public function getMetaKeywordsAttribute() { return $this->currentTranslation()?->meta_keywords; }
    public function getFeaturedSnippetAttribute() { return $this->currentTranslation()?->featured_snippet; }
    public function getThumbnailAltAttribute() { return $this->currentTranslation()?->thumbnail_alt; }


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug) && !empty($model->title)) {
                $model->slug = Str::slug($model->title);
            }
        });
        static::updating(function ($model) {
            // Ensure slug uniqueness on update if explicitly changed
            if ($model->isDirty('slug') && !empty($model->slug)) {
                $base = Str::slug($model->slug);
                $slug = $base;
                $i    = 1;
                while (static::where('slug', $slug)->where('id', '!=', $model->id)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $model->slug = $slug;
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    public function scopeByCategory(Builder $query, ?string $cat): Builder
    {
        return $cat ? $query->where('category', $cat) : $query;
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/article-default.jpg');
    }

    public function getOgImageUrlAttribute(): string
    {
        return $this->og_image
            ? asset('storage/' . $this->og_image)
            : $this->image_url;
    }

    public function getMetaTitleAttribute(): string
    {
        $val = $this->currentTranslation()?->meta_title;
        return $val ?: Str::limit($this->title ?? '', 55) . ' | buyle.id';
    }

    public function getMetaDescAttribute(): string
    {
        $val = $this->currentTranslation()?->meta_desc;
        return $val ?: Str::limit(strip_tags($this->excerpt ?? $this->content ?? ''), 155);
    }

    public function getUrlAttribute(): string
    {
        return route('faqs.show', $this->slug);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function getReadTimeAttribute(): int
    {
        $content = $this->currentTranslation()?->content ?? '';
        return max(1, (int) ceil(str_word_count(strip_tags($content)) / 200));
    }

    public function getFormattedDateAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;
        $locale = app()->getLocale();
        \Carbon\Carbon::setLocale($locale);

        return match ($locale) {
            'en' => $date->translatedFormat('F j, Y'),
            'ko' => $date->translatedFormat('Y년 n월 j일'),
            'ar' => $date->translatedFormat('j F Y'), // Arabic translation handled by Carbon
            default => $date->translatedFormat('j F Y'),
        };
    }

    /**
     * Extract headings from content for Table of Contents
     */
    public function getTocAttribute(): array
    {
        if (!$this->show_toc || !$this->content) return [];
        $contentWithIds = $this->content_with_toc_ids;
        preg_match_all('/<h([23])[^>]*id=["\']?([^"\'>\s]+)["\']?[^>]*>(.*?)<\/h[23]>/i', $contentWithIds, $matches, PREG_SET_ORDER);
        $toc = [];
        foreach ($matches as $m) {
            $toc[] = [
                'level' => (int)$m[1],
                'id'    => $m[2],
                'text'  => strip_tags($m[3]),
            ];
        }
        return $toc;
    }

    /**
     * Auto-inject IDs into headings for TOC anchors
     */
    public function getContentWithTocIdsAttribute(): string
    {
        return preg_replace_callback('/<h([23])([^>]*)>(.*?)<\/h[23]>/i', function($m) {
            $text = strip_tags($m[3]);
            $id   = \Illuminate\Support\Str::slug($text);
            // Don't duplicate id if already present
            if (str_contains($m[2], 'id=')) return $m[0];
            return "<h{$m[1]} id=\"{$id}\"{$m[2]}>{$m[3]}</h{$m[1]}>";
        }, $this->content ?? '');
    }
}

