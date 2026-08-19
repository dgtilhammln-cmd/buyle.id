<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\AuthorTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminAuthorController extends Controller
{
    use HandlesImageUpload;

    protected $locales = ['id', 'en', 'ar', 'ko'];

    public function index()
    {
        $authors = Author::orderBy('name', 'asc')->get();
        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        $locales = $this->locales;
        return view('admin.authors.create', compact('locales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|max:100',
            'slug'           => 'nullable|max:120|unique:authors,slug',
            'photo'          => 'nullable|image|max:2048',
            'social_links'   => 'nullable|array',
            'translations'   => 'nullable|array',
            'translations.*.bio' => 'nullable|max:1000',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->storeWebP($request->file('photo'), 'authors', 400, 400);
        }

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $base = $slug; $i = 1;
        while (Author::where('slug', $slug)->exists()) { $slug = $base . '-' . $i++; }

        $author = Author::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'photo' => $validated['photo'] ?? null,
            'social_links' => $validated['social_links'] ?? null,
        ]);

        $this->saveTranslations($author, $request->input('translations', []));

        return redirect()->route('admin.authors.index')->with('success', 'Author berhasil ditambahkan.');
    }

    public function edit(Author $author)
    {
        $locales = $this->locales;
        $translations = $author->translations->keyBy('locale');
        return view('admin.authors.edit', compact('author', 'locales', 'translations'));
    }

    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name'           => 'required|max:100',
            'slug'           => 'nullable|max:120|unique:authors,slug,' . $author->id,
            'photo'          => 'nullable|image|max:2048',
            'social_links'   => 'nullable|array',
            'translations'   => 'nullable|array',
            'translations.*.bio' => 'nullable|max:1000',
        ]);

        $author->name = $validated['name'];
        if (!empty($validated['slug'])) {
            $slug = Str::slug($validated['slug']);
            $base = $slug; $i = 1;
            while (Author::where('slug', $slug)->where('id', '!=', $author->id)->exists()) { $slug = $base . '-' . $i++; }
            $author->slug = $slug;
        }

        if (isset($validated['social_links'])) {
            $author->social_links = $validated['social_links'];
        }

        if ($request->hasFile('photo')) {
            $this->deleteStorageFile($author->getRawOriginal('photo'));
            $author->photo = $this->storeWebP($request->file('photo'), 'authors', 400, 400);
        }

        $author->save();
        $this->saveTranslations($author, $request->input('translations', []));

        return redirect()->route('admin.authors.index')->with('success', 'Author berhasil diperbarui.');
    }

    public function destroy(Author $author)
    {
        $this->deleteStorageFile($author->getRawOriginal('photo'));
        $author->delete();
        return back()->with('success', 'Author berhasil dihapus.');
    }

    protected function saveTranslations(Author $author, array $translationsInput)
    {
        foreach ($this->locales as $locale) {
            $data = $translationsInput[$locale] ?? [];
            if (empty($data['bio'])) continue;

            AuthorTranslation::updateOrCreate(
                ['author_id' => $author->id, 'locale' => $locale],
                ['bio' => $data['bio']]
            );
        }
    }
}
