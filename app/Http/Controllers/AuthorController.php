<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Article;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function show($slug)
    {
        $author = Author::where('slug', $slug)->firstOrFail();

        // Get articles written by this author that are published
        // Paginated to handle authors with many articles
        $articles = Article::with(['authorRel'])
            ->where('author_id', $author->id)
            ->where('is_published', true)
            ->latest()
            ->paginate(12);

        return view('author.show', compact('author', 'articles'));
    }
}
