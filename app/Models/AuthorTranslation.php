<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorTranslation extends Model
{
    protected $fillable = ['author_id', 'locale', 'bio'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
