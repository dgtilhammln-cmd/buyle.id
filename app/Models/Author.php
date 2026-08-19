<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name', 'photo', 'social_links'];

    protected $casts = [
        'social_links' => 'array',
    ];

    private $_translationCache = [];

    public function translations()
    {
        return $this->hasMany(AuthorTranslation::class);
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

    // Accessor for Bio
    public function getBioAttribute()
    {
        return $this->currentTranslation()?->bio;
    }
}
