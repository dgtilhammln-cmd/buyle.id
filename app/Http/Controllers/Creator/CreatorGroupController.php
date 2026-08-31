<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreatorGroupController extends Controller
{
    public function index()
    {
        $groups = CreatorProductGroup::where('seller_id', auth()->id())
            ->orderBy('order')
            ->get();
        
        return view('creator.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('creator.groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:2000',
        ]);

        $slug = Str::slug($request->name);
        // Ensure slug is unique per seller
        $baseSlug = $slug;
        $i = 1;
        while(CreatorProductGroup::where('seller_id', auth()->id())->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        CreatorProductGroup::create([
            'seller_id' => auth()->id(),
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('creator.groups.index')->with('success', 'Kelompok produk berhasil ditambahkan!');
    }

    public function edit(CreatorProductGroup $group)
    {
        if ($group->seller_id !== auth()->id()) abort(403);
        
        return view('creator.groups.edit', compact('group'));
    }

    public function update(Request $request, CreatorProductGroup $group)
    {
        if ($group->seller_id !== auth()->id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:100',
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:2000',
        ]);

        $group->name = $request->name;
        $group->description = $request->description;
        $group->order = $request->order ?? 0;
        $group->is_active = $request->boolean('is_active', true);
        
        // Update slug only if name changes
        if ($group->isDirty('name')) {
            $slug = Str::slug($request->name);
            $baseSlug = $slug;
            $i = 1;
            while(CreatorProductGroup::where('seller_id', auth()->id())->where('slug', $slug)->where('id', '!=', $group->id)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            $group->slug = $slug;
        }

        $group->save();

        return redirect()->route('creator.groups.index')->with('success', 'Kelompok produk berhasil diperbarui!');
    }

    public function destroy(CreatorProductGroup $group)
    {
        if ($group->seller_id !== auth()->id()) abort(403);
        
        // Prevent deleting if it has products? We used nullOnDelete in DB, so it's safe to delete.
        $group->delete();

        return redirect()->route('creator.groups.index')->with('success', 'Kelompok produk berhasil dihapus!');
    }
}
