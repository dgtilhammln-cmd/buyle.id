<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCategoryItemController extends Controller
{
    public function index()
    {
        $items = CategoryItem::orderBy('sort_order')->get();
        return view('admin.category-items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.category-items.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon_type'  => 'required|in:icon,upload',
            'icon_value' => 'nullable|string|max:50',
            'icon_file'  => 'nullable|image|max:1024',
            'name'       => 'required|string|max:100|unique:category_items,name',
            'url'        => 'nullable|string|max:255',
            'badge'      => 'nullable|string|max:20',
            'badge_color'=> 'nullable|string|max:7',
            'is_active'  => 'boolean',
            'sort_order' => 'integer',
        ]);

        $iconValue = $request->icon_value;
        if ($request->icon_type === 'upload' && $request->hasFile('icon_file')) {
            $iconValue = $request->file('icon_file')->store('category-icons', 'public');
        }

        CategoryItem::create([
            'icon_type'   => $request->icon_type,
            'icon_value'  => $iconValue,
            'name'        => $request->name,
            'url'         => $request->url,
            'badge'       => $request->badge,
            'badge_color' => $request->badge_color,
            'sort_order'  => $request->input('sort_order', 0),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.category-items.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(CategoryItem $categoryItem)
    {
        return view('admin.category-items.form', ['item' => $categoryItem]);
    }

    public function update(Request $request, CategoryItem $categoryItem)
    {
        $request->validate([
            'icon_type'  => 'required|in:icon,upload',
            'icon_value' => 'nullable|string|max:50',
            'icon_file'  => 'nullable|image|max:1024',
            'name'       => 'required|string|max:100|unique:category_items,name,' . $categoryItem->id,
        ]);

        $iconValue = $categoryItem->icon_value;
        if ($request->icon_type === 'upload' && $request->hasFile('icon_file')) {
            if ($categoryItem->icon_type === 'upload' && $categoryItem->icon_value) {
                Storage::disk('public')->delete($categoryItem->icon_value);
            }
            $iconValue = $request->file('icon_file')->store('category-icons', 'public');
        } elseif ($request->icon_type === 'icon') {
            $iconValue = $request->icon_value;
        }

        $categoryItem->update([
            'icon_type'   => $request->icon_type,
            'icon_value'  => $iconValue,
            'name'        => $request->name,
            'url'         => $request->url,
            'badge'       => $request->badge,
            'badge_color' => $request->badge_color,
            'sort_order'  => $request->input('sort_order', 0),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.category-items.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(CategoryItem $categoryItem)
    {
        if ($categoryItem->icon_type === 'upload' && $categoryItem->icon_value) {
            Storage::disk('public')->delete($categoryItem->icon_value);
        }
        $categoryItem->delete();
        return back()->with('success', 'Kategori dihapus.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate(['id' => 'required|integer', 'sort_order' => 'required|integer|min:0']);
        CategoryItem::where('id', $request->id)->update(['sort_order' => $request->sort_order]);
        return response()->json(['success' => true]);
    }
}
