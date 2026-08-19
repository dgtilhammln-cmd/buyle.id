<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryProject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class AdminGalleryController extends Controller
{
    use HandlesImageUpload;

    public function index()
    {
        $items = GalleryProject::ordered()->get();
        return view('admin.gallery.index', compact('items'));
    }

    public function create() { return view('admin.gallery.create'); }

    public function store(Request $request)
    {
        $v = $request->validate([
            'title'         => 'required|max:200',
            'slug'          => 'nullable|max:200|regex:/^[a-z0-9\-]*$/',
            'description'   => 'nullable|max:1000',
            'content'       => 'nullable',
            'category'      => 'nullable|max:100',
            'client'        => 'nullable|max:200',
            'location'      => 'nullable|max:200',
            'year'          => 'nullable|integer|min:2000|max:2099',
            'alt_text'      => 'nullable|max:200',
            'tags'          => 'nullable|max:500',
            'order'         => 'nullable|integer|min:0',
            'is_active'     => 'boolean',
            'is_published'  => 'boolean',
            'is_featured'   => 'boolean',
            'meta_title'    => 'nullable|max:70',
            'meta_desc'     => 'nullable|max:165',
            'meta_keywords' => 'nullable|max:500',
            'image'         => 'required|image|max:8192',
            'og_image'      => 'nullable|image|max:5120',
        ]);

        $v['is_active']    = $request->boolean('is_active', true);
        $v['is_published'] = $request->boolean('is_published', true);
        $v['is_featured']  = $request->boolean('is_featured', false);
        $v['order']        = $v['order'] ?? 0;

        if (empty($v['alt_text'])) $v['alt_text'] = $v['title'].' - buyle.id';

        // Slug
        $slug = Str::slug(!empty($v['slug']) ? $v['slug'] : $v['title']);
        $base = $slug; $i = 1;
        while (GalleryProject::where('slug', $slug)->exists()) { $slug = $base.'-'.$i++; }
        $v['slug'] = $slug;

        $v['image']    = $this->storeWebP($request->file('image'), 'gallery', 1920, 1440);
        $v['og_image'] = $request->hasFile('og_image')
            ? $this->storeOgWebP($request->file('og_image'), 'gallery/og')
            : $this->storeOgWebP($request->file('image'), 'gallery/og');

        GalleryProject::create($v);
        Cache::forget('home_gallery');
        return redirect()->route('admin.gallery.index')->with('success', 'Foto proyek berhasil ditambahkan.');
    }

    public function edit(GalleryProject $gallery) { return view('admin.gallery.edit', compact('gallery')); }

    public function update(Request $request, GalleryProject $gallery)
    {
        $v = $request->validate([
            'title'         => 'required|max:200',
            'slug'          => 'nullable|max:200|regex:/^[a-z0-9\-]*$/',
            'description'   => 'nullable|max:1000',
            'content'       => 'nullable',
            'category'      => 'nullable|max:100',
            'client'        => 'nullable|max:200',
            'location'      => 'nullable|max:200',
            'year'          => 'nullable|integer|min:2000|max:2099',
            'alt_text'      => 'nullable|max:200',
            'tags'          => 'nullable|max:500',
            'order'         => 'nullable|integer|min:0',
            'is_active'     => 'boolean',
            'is_published'  => 'boolean',
            'is_featured'   => 'boolean',
            'meta_title'    => 'nullable|max:70',
            'meta_desc'     => 'nullable|max:165',
            'meta_keywords' => 'nullable|max:500',
            'image'         => 'nullable|image|max:8192',
            'og_image'      => 'nullable|image|max:5120',
        ]);

        $v['is_active']    = $request->boolean('is_active', true);
        $v['is_published'] = $request->boolean('is_published', true);
        $v['is_featured']  = $request->boolean('is_featured', false);

        if (!empty($v['slug'])) {
            $slug = Str::slug($v['slug']);
            $base = $slug; $i = 1;
            while (GalleryProject::where('slug', $slug)->where('id','!=',$gallery->id)->exists()) { $slug = $base.'-'.$i++; }
            $v['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            $this->deleteStorageFile($gallery->image);
            $v['image'] = $this->storeWebP($request->file('image'), 'gallery', 1920, 1440);
            if (!$request->hasFile('og_image') && !$gallery->og_image) {
                $v['og_image'] = $this->storeOgWebP($request->file('image'), 'gallery/og');
            }
        }
        if ($request->hasFile('og_image')) {
            $this->deleteStorageFile($gallery->og_image);
            $v['og_image'] = $this->storeOgWebP($request->file('og_image'), 'gallery/og');
        }

        $gallery->update($v);
        Cache::forget('home_gallery');
        return redirect()->route('admin.gallery.index')->with('success', 'Foto proyek berhasil diperbarui.');
    }

    public function destroy(GalleryProject $gallery)
    {
        $this->deleteStorageFile($gallery->image);
        $this->deleteStorageFile($gallery->og_image);
        $gallery->delete();
        Cache::forget('home_gallery');
        return back()->with('success', 'Foto proyek berhasil dihapus.');
    }
}
