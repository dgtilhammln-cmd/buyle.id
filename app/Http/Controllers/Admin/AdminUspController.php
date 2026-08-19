<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UspItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUspController extends Controller
{
    public function index()
    {
        $items = UspItem::orderBy('sort_order')->get();
        return view('admin.usp.index', compact('items'));
    }

    public function create()
    {
        return view('admin.usp.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'icon_type'  => 'required|in:emoji,upload',
            'icon_emoji' => 'nullable|string|max:10',
            'icon_file'  => 'nullable|image|max:1024',
            'label'      => 'required|string|max:100',
            'sort_order' => 'integer',
            'is_active'  => 'boolean',
        ]);

        $iconValue = null;
        if ($data['icon_type'] === 'upload' && $request->hasFile('icon_file')) {
            $iconValue = $request->file('icon_file')->store('usp-icons', 'public');
        } elseif ($data['icon_type'] === 'emoji') {
            $iconValue = $request->input('icon_emoji');
        }

        UspItem::create([
            'icon_type'  => $data['icon_type'],
            'icon_value' => $iconValue,
            'label'      => $data['label'],
            'sort_order' => $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.usp.index')->with('success', 'USP berhasil ditambahkan.');
    }

    public function edit(UspItem $usp)
    {
        return view('admin.usp.form', compact('usp'));
    }

    public function update(Request $request, UspItem $usp)
    {
        $data = $request->validate([
            'icon_type'  => 'required|in:emoji,upload',
            'icon_emoji' => 'nullable|string|max:10',
            'icon_file'  => 'nullable|image|max:1024',
            'label'      => 'required|string|max:100',
            'sort_order' => 'integer',
            'is_active'  => 'boolean',
        ]);

        $iconValue = $usp->icon_value;
        if ($data['icon_type'] === 'upload' && $request->hasFile('icon_file')) {
            if ($usp->icon_type === 'upload' && $usp->icon_value) {
                Storage::disk('public')->delete($usp->icon_value);
            }
            $iconValue = $request->file('icon_file')->store('usp-icons', 'public');
        } elseif ($data['icon_type'] === 'emoji') {
            $iconValue = $request->input('icon_emoji');
        }

        $usp->update([
            'icon_type'  => $data['icon_type'],
            'icon_value' => $iconValue,
            'label'      => $data['label'],
            'sort_order' => $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.usp.index')->with('success', 'USP berhasil diperbarui.');
    }

    public function destroy(UspItem $usp)
    {
        if ($usp->icon_type === 'upload' && $usp->icon_value) {
            Storage::disk('public')->delete($usp->icon_value);
        }
        $usp->delete();
        return back()->with('success', 'USP dihapus.');
    }
}
