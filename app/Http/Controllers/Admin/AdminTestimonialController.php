<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTestimonialController extends Controller
{
    use HandlesImageUpload;

    public function index()
    {
        $testimonials = Testimonial::ordered()->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|max:100',
            'company'   => 'nullable|max:200',
            'position'  => 'nullable|max:100',
            'content'   => 'required',
            'rating'    => 'integer|min:1|max:5',
            'order'     => 'integer|min:0',
            'is_active' => 'boolean',
            'photo'     => 'nullable|image|max:2048',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->storeWebPSquare($request->file('photo'), 'testimonials', 200);
        }

        Testimonial::create($validated);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name'      => 'required|max:100',
            'company'   => 'nullable|max:200',
            'position'  => 'nullable|max:100',
            'content'   => 'required',
            'rating'    => 'integer|min:1|max:5',
            'order'     => 'integer|min:0',
            'is_active' => 'boolean',
            'photo'     => 'nullable|image|max:2048',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $this->deleteStorageFile($testimonial->photo);
            $validated['photo'] = $this->storeWebPSquare($request->file('photo'), 'testimonials', 200);
        }

        $testimonial->update($validated);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $this->deleteStorageFile($testimonial->photo);
        $testimonial->delete();
        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
