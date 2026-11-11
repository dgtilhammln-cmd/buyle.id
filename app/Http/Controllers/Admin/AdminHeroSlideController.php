<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;

class AdminHeroSlideController extends Controller
{
    use HandlesImageUpload;

    public function index()
    {
        $slides = HeroSlide::ordered()->get();
        return view('admin.hero_slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.hero_slides.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position'    => 'required|in:hero,utama,samping,tix,partner',
            'title'       => 'required|max:200',
            'description' => 'nullable|max:500',
            'icon'        => 'nullable|max:50',
            'image'       => 'nullable|image|max:3072',
            'bg_color'    => 'nullable|string|max:50',
            'button_text' => 'nullable|max:100',
            'button_url'  => 'nullable|max:300',
            'order'       => 'integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->filled('image_base64')) {
            $stored = $this->storeBase64WebP($request->input('image_base64'), 'hero_slides', 1280, 640, 78);
            if ($stored) {
                $validated['image'] = $stored;
            }
        } elseif ($request->hasFile('image')) {
            $validated['image'] = $this->storeWebP($request->file('image'), 'hero_slides', 1280, 640, 78);
        }

        HeroSlide::create($validated);
        return redirect()->route('admin.hero_slides.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero_slides.form', ['slide' => $heroSlide]);
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $validated = $request->validate([
            'position'    => 'required|in:hero,utama,samping,tix,partner',
            'title'       => 'required|max:200',
            'description' => 'nullable|max:500',
            'icon'        => 'nullable|max:50',
            'image'       => 'nullable|image|max:3072',
            'bg_color'    => 'nullable|string|max:50',
            'button_text' => 'nullable|max:100',
            'button_url'  => 'nullable|max:300',
            'order'       => 'integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->filled('image_base64')) {
            if ($heroSlide->image) {
                $this->deleteStorageFile($heroSlide->image);
            }
            $stored = $this->storeBase64WebP($request->input('image_base64'), 'hero_slides', 1280, 640, 78);
            if ($stored) {
                $validated['image'] = $stored;
            }
        } elseif ($request->hasFile('image')) {
            $this->deleteStorageFile($heroSlide->image);
            $validated['image'] = $this->storeWebP($request->file('image'), 'hero_slides', 1280, 640, 78);
        }

        $heroSlide->update($validated);
        return redirect()->route('admin.hero_slides.index')->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        $this->deleteStorageFile($heroSlide->image ?? null);
        $heroSlide->delete();
        return back()->with('success', 'Slide berhasil dihapus.');
    }
}
