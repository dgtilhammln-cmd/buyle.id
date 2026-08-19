<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleTranslation;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminArticleController extends Controller
{
    use HandlesImageUpload;

    protected $locales = ['id'];

    public function index(Request $request)
    {
        $query = Article::with('authorRel')->orderBy('created_at', 'desc');
        
        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('translations', function($t) use ($search) {
                      $t->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        $articles = $query->get();
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $locales = $this->locales;
        $authors = Author::all();
        return view('admin.articles.create', compact('locales', 'authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug'           => 'nullable|max:200|regex:/^[a-z0-9\-]*$/',
            'category'       => 'nullable|max:100',
            'tags'           => 'nullable|max:500',
            'author_id'      => 'nullable|exists:authors,id',
            'published_at'   => 'nullable|date',
            'is_published'   => 'boolean',
            'show_toc'       => 'boolean',
            'image'          => 'nullable|image|max:5120',
            'og_image'       => 'nullable|image|max:5120',
            'translations'   => 'nullable|array',
            'translations.*.title'         => 'nullable|max:200',
            'translations.*.excerpt'       => 'nullable|max:500',
            'translations.*.content'       => 'nullable',
            'translations.*.meta_title'    => 'nullable|max:70',
            'translations.*.meta_desc'     => 'nullable|max:165',
            'translations.*.meta_keywords' => 'nullable|max:500',
            'translations.*.cta_text'      => 'nullable|max:100',
            'translations.*.cta_url'       => 'nullable|max:500',
            'translations.*.cta_type'      => 'nullable|in:wa,url',
            'translations.*.faqs'          => 'nullable|array',
            'translations.*.featured_snippet'=> 'nullable|max:1000',
            'translations.*.thumbnail_alt'   => 'nullable|max:200',
        ]);

        $request->validate([
            'translations.id.title' => 'required|max:200',
            'translations.id.content' => 'required',
        ]);

        $idTitle = $request->input('translations.id.title');

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($idTitle);
        $base = $slug; $i = 1;
        while (Article::where('slug', $slug)->exists()) { $slug = $base . '-' . $i++; }

        $validated['is_published'] = $request->boolean('is_published');
        $validated['show_toc']     = $request->boolean('show_toc');
        $validated['published_at'] = $validated['published_at'] ?? ($validated['is_published'] ? now() : null);
        $validated['tags']         = $validated['tags'] ? array_filter(array_map('trim', explode(',', $validated['tags']))) : null;

        if ($request->hasFile('image')) {
            $validated['image']    = $this->storeWebP($request->file('image'), 'articles', 1280, 720);
            $validated['alt_text'] = $idTitle;
            if (!$request->hasFile('og_image')) {
                $validated['og_image'] = $this->storeWebP($request->file('image'), 'articles/og', 1280, 720);
            }
        }
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $this->storeWebP($request->file('og_image'), 'articles/og', 1280, 720);
        }

        unset($validated['translations'], $validated['slug']);
        $article = Article::create(array_merge($validated, ['slug' => $slug]));

        $this->saveTranslations($article, $request->input('translations', []));
        $this->clearArticleCache($article);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article)
    {
        $locales = $this->locales;
        $authors = Author::all();
        $translations = $article->translations->keyBy('locale');
        return view('admin.articles.edit', compact('article', 'locales', 'translations', 'authors'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'slug'           => 'nullable|max:200|regex:/^[a-z0-9\-]*$/',
            'category'       => 'nullable|max:100',
            'tags'           => 'nullable|max:500',
            'author_id'      => 'nullable|exists:authors,id',
            'published_at'   => 'nullable|date',
            'is_published'   => 'boolean',
            'show_toc'       => 'boolean',
            'image'          => 'nullable|image|max:5120',
            'og_image'       => 'nullable|image|max:5120',
            'translations'   => 'nullable|array',
            'translations.*.title'         => 'nullable|max:200',
            'translations.*.excerpt'       => 'nullable|max:500',
            'translations.*.content'       => 'nullable',
            'translations.*.meta_title'    => 'nullable|max:70',
            'translations.*.meta_desc'     => 'nullable|max:165',
            'translations.*.meta_keywords' => 'nullable|max:500',
            'translations.*.cta_text'      => 'nullable|max:100',
            'translations.*.cta_url'       => 'nullable|max:500',
            'translations.*.cta_type'      => 'nullable|in:wa,url',
            'translations.*.faqs'          => 'nullable|array',
            'translations.*.featured_snippet'=> 'nullable|max:1000',
            'translations.*.thumbnail_alt'   => 'nullable|max:200',
        ]);

        $request->validate([
            'translations.id.title'   => 'required|max:200',
            'translations.id.content' => 'required',
        ]);

        if (!empty($validated['slug'])) {
            $slug = Str::slug($validated['slug']);
            $base = $slug; $i = 1;
            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) { $slug = $base . '-' . $i++; }
            $article->slug = $slug;
        }

        $article->is_published = $request->boolean('is_published');
        $article->show_toc     = $request->boolean('show_toc');
        $article->published_at = $validated['published_at'] ?? ($article->is_published && !$article->published_at ? now() : $article->published_at);
        $article->tags         = $validated['tags'] ? array_filter(array_map('trim', explode(',', $validated['tags']))) : null;
        $article->category     = $validated['category'] ?? null;
        $article->author_id    = $validated['author_id'] ?? null;

        if ($request->hasFile('image')) {
            $this->deleteStorageFile($article->getRawOriginal('image'));
            $article->image = $this->storeWebP($request->file('image'), 'articles', 1280, 720);
            if (!$request->hasFile('og_image') && !$article->getRawOriginal('og_image')) {
                $article->og_image = $this->storeWebP($request->file('image'), 'articles/og', 1280, 720);
            }
        }
        if ($request->hasFile('og_image')) {
            $this->deleteStorageFile($article->getRawOriginal('og_image'));
            $article->og_image = $this->storeWebP($request->file('og_image'), 'articles/og', 1280, 720);
        }

        $article->save();
        $this->saveTranslations($article, $request->input('translations', []));
        $this->clearArticleCache($article);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        $this->deleteStorageFile($article->getRawOriginal('image'));
        $this->deleteStorageFile($article->getRawOriginal('og_image'));
        $this->clearArticleCache($article);
        $article->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    protected function saveTranslations(Article $article, array $translationsInput)
    {
        foreach ($this->locales as $locale) {
            $data = $translationsInput[$locale] ?? [];
            if (empty($data)) continue;

            $title   = $data['title'] ?? null;
            $content = $data['content'] ?? null;

            if ($locale !== 'id' && empty($title) && empty($content)) continue;

            $faqs = [];
            if (!empty($data['faqs'])) {
                foreach ($data['faqs'] as $faq) {
                    if (!empty($faq['q']) || !empty($faq['a'])) {
                        $faqs[] = ['q' => $faq['q'] ?? '', 'a' => $faq['a'] ?? ''];
                    }
                }
            }

            $ctaButton = null;
            if (!empty($data['cta_text'])) {
                $ctaButton = [
                    'text' => $data['cta_text'],
                    'url'  => $data['cta_url'] ?? '',
                    'type' => $data['cta_type'] ?? 'url',
                ];
            }

            $excerpt = $data['excerpt'] ?? null;
            if (empty($excerpt) && !empty($content)) {
                $excerpt = Str::limit(strip_tags($content), 160);
            }

            $metaTitle = $data['meta_title'] ?? null;
            if (empty($metaTitle) && !empty($title)) {
                $metaTitle = Str::limit($title, 55) . ' | buyle.id';
            }
            $metaDesc = $data['meta_desc'] ?? null;
            if (empty($metaDesc)) {
                $metaDesc = Str::limit(strip_tags($excerpt ?? $content ?? ''), 155);
            }

            $isComplete = !empty($title) && !empty($content);

            ArticleTranslation::updateOrCreate(
                ['article_id' => $article->id, 'locale' => $locale],
                [
                    'title'         => $title,
                    'excerpt'       => $excerpt,
                    'content'       => $content,
                    'faqs'          => !empty($faqs) ? $faqs : null,
                    'cta_button'    => $ctaButton,
                    'meta_title'    => $metaTitle,
                    'meta_desc'     => $metaDesc,
                    'meta_keywords' => $data['meta_keywords'] ?? null,
                    'featured_snippet'=> $data['featured_snippet'] ?? null,
                    'thumbnail_alt'   => $data['thumbnail_alt'] ?? null,
                    'is_complete'   => $isComplete,
                ]
            );
        }
    }

    protected function clearArticleCache(Article $article)
    {
        foreach ($this->locales as $locale) {
            Cache::forget("article.{$article->slug}.{$locale}");
            Cache::forget("article.related.{$article->id}.{$locale}");
            Cache::forget("articles.categories.{$locale}");
            Cache::forget("articles.popular.{$locale}");
        }
    }
}
