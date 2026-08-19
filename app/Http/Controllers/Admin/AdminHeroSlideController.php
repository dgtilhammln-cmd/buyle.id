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
            'position'    => 'required|in:hero,utama,samping',
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
            $base64 = $request->input('image_base64');
            $image_parts = explode(";base64,", $base64);
            if (count($image_parts) == 2) {
                $image_data = base64_decode($image_parts[1]);
                $filename = uniqid('banner_') . '.webp';
                \Illuminate\Support\Facades\Storage::disk('public')->put('hero_slides/' . $filename, $image_data);
                $validated['image'] = 'hero_slides/' . $filename;
            }
        } elseif ($request->hasFile('image')) {
            $validated['image'] = $this->storeWebP($request->file('image'), 'hero_slides', 1200);
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
            'position'    => 'required|in:hero,utama,samping',
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
            $base64 = $request->input('image_base64');
            $image_parts = explode(";base64,", $base64);
            if (count($image_parts) == 2) {
                $image_data = base64_decode($image_parts[1]);
                $filename = uniqid('banner_') . '.webp';
                \Illuminate\Support\Facades\Storage::disk('public')->put('hero_slides/' . $filename, $image_data);
                $validated['image'] = 'hero_slides/' . $filename;
            }
        } elseif ($request->hasFile('image')) {
            $this->deleteStorageFile($heroSlide->image);
            $validated['image'] = $this->storeWebP($request->file('image'), 'hero_slides', 1200);
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
