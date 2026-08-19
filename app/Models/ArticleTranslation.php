<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleTranslation extends Model
{
    protected $fillable = [
        'article_id', 'locale', 'title', 'excerpt', 'content', 'faqs', 'cta_button',
        'meta_title', 'meta_desc', 'meta_keywords', 'is_complete',
        'featured_snippet', 'thumbnail_alt', 'translated_by'
    ];

    protected $casts = [
        'faqs'        => 'array',
        'cta_button'  => 'array',
        'is_complete' => 'boolean',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
