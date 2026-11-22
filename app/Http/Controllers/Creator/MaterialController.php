<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialMutation;
use App\Models\Product;
use App\Models\ProductRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = auth()->id();
        $search   = trim((string) $request->get('search', ''));
        $category = trim((string) $request->get('category', ''));
        $tab      = trim((string) $request->get('tab', 'materials')); // materials, recipes, mutations

        // ── 1. Query Bahan Baku ──
        $matQuery = Material::where('seller_id', $sellerId)
            ->when($search, function ($q, $s) {
                $q->where(function ($sub) use ($s) {
                    $sub->where('name', 'like', "%{$s}%")
                        ->orWhere('code', 'like', "%{$s}%");
                });
            })
            ->when($category, function ($q, $c) {
                $q->where('category', $c);
            })
            ->orderBy('name', 'asc');

        $materials = $matQuery->paginate(20)->withQueryString();

        // Metrik Ringkasan Bahan
        $allMats      = Material::where('seller_id', $sellerId)->get();
        $totalCount   = $allMats->count();
        $safeCount    = $allMats->filter(fn($m) => (float) $m->stock > (float) $m->min_stock && (float) $m->stock > 0)->count();
        $warningCount = $allMats->filter(fn($m) => (float) $m->min_stock > 0 && (float) $m->stock <= (float) $m->min_stock && (float) $m->stock > 0)->count();
        $dangerCount  = $allMats->filter(fn($m) => (float) $m->stock <= 0)->count();

        // ── 2. Data Produk untuk Tab Resep Menu ──
        $products = Product::where('seller_id', $sellerId)
            ->with(['recipes.material', 'category:id,name'])
            ->orderBy('name', 'asc')
            ->get();

        // ── 3. Data Riwayat Mutasi ──
        $mutations = MaterialMutation::where('seller_id', $sellerId)
            ->with(['material:id,name,unit', 'order:id,order_number'])
            ->latest()
            ->paginate(25, ['*'], 'mutation_page')
            ->withQueryString();

        return view('creator.materials.index', compact(
            'materials',
            'allMats',
            'products',
            'mutations',
            'totalCount',
            'safeCount',
            'warningCount',
            'dangerCount',
            'tab'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:150',
            'code'          => 'nullable|string|max:50',
            'category'      => 'required|string|max:50',
            'unit'          => 'required|string|max:30',
            'stock'         => 'nullable|numeric|min:0',
            'min_stock'     => 'nullable|numeric|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string|max:500',
        ]);

        $initialStock = max(0, (float) ($request->stock ?? 0));
        $material = Material::create([
            'seller_id'     => auth()->id(),
            'name'          => $request->name,
            'code'          => $request->code,
            'category'      => $request->category,
            'unit'          => strtolower($request->unit),
            'stock'         => $initialStock,
            'min_stock'     => max(0, (float) ($request->min_stock ?? 0)),
            'cost_per_unit' => max(0, (float) ($request->cost_per_unit ?? 0)),
            'notes'         => $request->notes,
        ]);

        if ($initialStock > 0) {
            MaterialMutation::create([
                'seller_id'      => auth()->id(),
                'material_id'    => $material->id,
                'type'           => 'in_purchase',
                'amount'         => $initialStock,
                'stock_before'   => 0,
                'stock_after'    => $initialStock,
                'unit_cost'      => $material->cost_per_unit,
                'reference_note' => 'Stok Awal Pendaftaran Bahan',
            ]);
        }

        return redirect()->route('creator.materials.index', ['tab' => 'materials'])
            ->with('success', "Bahan baku {$material->name} berhasil ditambahkan!");
    }

    public function update(Request $request, $id)
    {
        $material = Material::where('seller_id', auth()->id())->findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:150',
            'code'          => 'nullable|string|max:50',
            'category'      => 'required|string|max:50',
            'unit'          => 'required|string|max:30',
            'min_stock'     => 'nullable|numeric|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string|max:500',
        ]);

        $material->update([
            'name'          => $request->name,
            'code'          => $request->code,
            'category'      => $request->category,
            'unit'          => strtolower($request->unit),
            'min_stock'     => max(0, (float) ($request->min_stock ?? 0)),
            'cost_per_unit' => max(0, (float) ($request->cost_per_unit ?? 0)),
            'notes'         => $request->notes,
        ]);

        return redirect()->route('creator.materials.index', ['tab' => 'materials'])
            ->with('success', "Bahan baku {$material->name} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $material = Material::where('seller_id', auth()->id())->findOrFail($id);
        $name = $material->name;
        $material->delete();

        return redirect()->route('creator.materials.index', ['tab' => 'materials'])
            ->with('success', "Bahan baku {$name} telah dihapus.");
    }

    public function restock(Request $request, $id)
    {
        $material = Material::where('seller_id', auth()->id())->findOrFail($id);

        $request->validate([
            'amount'        => 'required|numeric|min:0.01',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string|max:255',
        ]);

        $qty      = (float) $request->amount;
        $unitCost = $request->filled('cost_per_unit') ? (float) $request->cost_per_unit : null;
        $notes    = $request->notes ?: 'Restock Tambah Stok';

        $material->restock($qty, $unitCost, $notes);

        return redirect()->route('creator.materials.index', ['tab' => 'materials'])
            ->with('success', "Berhasil menambahkan {$qty} {$material->unit} ke stok {$material->name}.");
    }

    public function saveRecipe(Request $request, $productId)
    {
        $product = Product::where('seller_id', auth()->id())->findOrFail($productId);

        $recipesInput = $request->input('recipes', []);

        DB::transaction(function () use ($product, $recipesInput) {
            ProductRecipe::where('product_id', $product->id)->delete();

            if (is_array($recipesInput)) {
                foreach ($recipesInput as $row) {
                    $matId = $row['material_id'] ?? null;
                    $qty   = (float) ($row['quantity'] ?? 0);

                    if ($matId && $qty > 0) {
                        ProductRecipe::create([
                            'product_id'  => $product->id,
                            'material_id' => $matId,
                            'quantity'    => $qty,
                            'notes'       => $row['notes'] ?? null,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('creator.materials.index', ['tab' => 'recipes'])
            ->with('success', "Resep untuk {$product->name} berhasil disimpan!");
    }
}
