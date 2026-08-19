<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoSection;
use App\Models\Service;
use App\Models\CategoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPromoSectionController extends Controller
{
    public function index()
    {
        $promos = PromoSection::orderBy('sort_order')->get();
        return view('admin.promo-sections.index', compact('promos'));
    }

    public function create()
    {
        $services    = Service::active()->ordered()->get();
        $categories  = CategoryItem::active()->get();
        $selectedIds = [];
        return view('admin.promo-sections.form', compact('services', 'categories', 'selectedIds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:100',
            'subtitle'       => 'nullable|string|max:200',
            'banner'         => 'nullable|image|max:3072',
            'view_all_url'   => 'nullable|string|max:300',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
            'selection_type' => 'required|in:manual,category,discount,all',
            'category_id'    => 'nullable|integer|exists:product_categories,id',
            'service_ids'    => 'nullable|array',
            'service_ids.*'  => 'integer|exists:services,id',
            'start_time'     => 'nullable|date',
            'end_time'       => 'nullable|date',
            'bg_color_1'     => 'nullable|string|max:20',
            'bg_color_2'     => 'nullable|string|max:20',
            'logo'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('promo-banners', 'public');
        }
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('promo-logos', 'public');
        }
        $data['is_active']   = $request->boolean('is_active', true);
        $data['sort_order']  = $data['sort_order'] ?? 0;

        // Reset category_id if not category type
        if ($data['selection_type'] !== 'category') {
            $data['category_id'] = null;
        }

        $promo = PromoSection::create($data);

        // Sync products with sort order if manual
        if ($data['selection_type'] === 'manual') {
            $sync = [];
            foreach (($request->input('service_ids', [])) as $i => $sid) {
                $sync[$sid] = ['sort_order' => $i];
            }
            $promo->services()->sync($sync);
        }

        return redirect()->route('admin.promo-sections.index')
                         ->with('success', 'Section promo berhasil ditambahkan!');
    }

    public function show(PromoSection $promoSection)
    {
        return redirect()->route('admin.promo-sections.edit', $promoSection);
    }

    public function edit(PromoSection $promoSection)
    {
        $services     = Service::active()->ordered()->get();
        $categories   = CategoryItem::active()->get();
        $selectedIds  = $promoSection->services()->pluck('services.id')->toArray();
        return view('admin.promo-sections.form', compact('promoSection', 'services', 'categories', 'selectedIds'));
    }

    public function update(Request $request, PromoSection $promoSection)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:100',
            'subtitle'       => 'nullable|string|max:200',
            'banner'         => 'nullable|image|max:3072',
            'view_all_url'   => 'nullable|string|max:300',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
            'selection_type' => 'required|in:manual,category,discount,all',
            'category_id'    => 'nullable|integer|exists:product_categories,id',
            'service_ids'    => 'nullable|array',
            'service_ids.*'  => 'integer|exists:services,id',
            'start_time'     => 'nullable|date',
            'end_time'       => 'nullable|date',
            'bg_color_1'     => 'nullable|string|max:20',
            'bg_color_2'     => 'nullable|string|max:20',
            'logo'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('banner')) {
            if ($promoSection->banner) {
                Storage::disk('public')->delete($promoSection->banner);
            }
            $data['banner'] = $request->file('banner')->store('promo-banners', 'public');
        }
        if ($request->hasFile('logo')) {
            if ($promoSection->logo) {
                Storage::disk('public')->delete($promoSection->logo);
            }
            $data['logo'] = $request->file('logo')->store('promo-logos', 'public');
        }
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($data['selection_type'] !== 'category') {
            $data['category_id'] = null;
        }

        $promoSection->update($data);

        if ($data['selection_type'] === 'manual') {
            $sync = [];
            foreach (($request->input('service_ids', [])) as $i => $sid) {
                $sync[$sid] = ['sort_order' => $i];
            }
            $promoSection->services()->sync($sync);
        } else {
            $promoSection->services()->sync([]);
        }

        return redirect()->route('admin.promo-sections.index')
                         ->with('success', 'Section promo berhasil diperbarui!');
    }

    public function destroy(PromoSection $promoSection)
    {
        if ($promoSection->banner) {
            Storage::disk('public')->delete($promoSection->banner);
        }
        if ($promoSection->logo) {
            Storage::disk('public')->delete($promoSection->logo);
        }
        $promoSection->delete();

        return back()->with('success', 'Section promo berhasil dihapus.');
    }
}
