<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Material extends Model
{
    protected $fillable = [
        'seller_id',
        'name',
        'code',
        'category',
        'unit',
        'stock',
        'min_stock',
        'cost_per_unit',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'stock'         => 'decimal:2',
        'min_stock'     => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(ProductRecipe::class, 'material_id');
    }

    public function mutations(): HasMany
    {
        return $this->hasMany(MaterialMutation::class, 'material_id')->latest();
    }

    /**
     * Status stok: safe, warning, danger
     */
    public function getStockStatusAttribute(): array
    {
        $current = (float) $this->stock;
        $min = (float) $this->min_stock;

        if ($current <= 0) {
            return [
                'status' => 'danger',
                'label'  => 'Habis',
                'bg'     => '#fef2f2',
                'color'  => '#dc2626',
                'border' => '#fca5a5',
            ];
        }

        if ($min > 0 && $current <= $min) {
            return [
                'status' => 'warning',
                'label'  => 'Menipis',
                'bg'     => '#fffbeb',
                'color'  => '#d97706',
                'border' => '#fde68a',
            ];
        }

        return [
            'status' => 'safe',
            'label'  => 'Aman',
            'bg'     => '#f0fdf4',
            'color'  => '#16a34a',
            'border' => '#bbf7d0',
        ];
    }

    /**
     * Tambah stok masuk (Restock)
     */
    public function restock(float $qty, ?float $unitCost = null, ?string $notes = null): MaterialMutation
    {
        return DB::transaction(function () use ($qty, $unitCost, $notes) {
            $material = self::where('id', $this->id)->lockForUpdate()->first();
            $stockBefore = (float) $material->stock;
            $stockAfter  = $stockBefore + $qty;

            $updateData = ['stock' => $stockAfter];
            if ($unitCost !== null && $unitCost > 0) {
                $updateData['cost_per_unit'] = $unitCost;
            }
            $material->update($updateData);

            return MaterialMutation::create([
                'seller_id'      => $material->seller_id,
                'material_id'    => $material->id,
                'type'           => 'in_purchase',
                'amount'         => $qty,
                'stock_before'   => $stockBefore,
                'stock_after'    => $stockAfter,
                'unit_cost'      => $unitCost ?? $material->cost_per_unit,
                'reference_note' => $notes ?: 'Restock Masuk',
            ]);
        });
    }

    /**
     * Kurangi stok bahan secara otomatis berdasarkan resep saat transaksi POS berhasil
     */
    public static function deductForProduct(int $productId, int $soldQty, ?int $orderId = null, ?int $sellerId = null): void
    {
        $recipes = ProductRecipe::where('product_id', $productId)->with('material')->get();
        if ($recipes->isEmpty()) return;

        foreach ($recipes as $recipe) {
            $material = $recipe->material;
            if (!$material) continue;

            $neededQty = (float) $recipe->quantity * $soldQty;
            if ($neededQty <= 0) continue;

            DB::transaction(function () use ($material, $neededQty, $orderId, $sellerId, $recipe, $soldQty) {
                $mat = self::where('id', $material->id)->lockForUpdate()->first();
                if (!$mat) return;

                $stockBefore = (float) $mat->stock;
                $stockAfter  = $stockBefore - $neededQty;
                $mat->update(['stock' => $stockAfter]);

                MaterialMutation::create([
                    'seller_id'      => $sellerId ?: $mat->seller_id,
                    'material_id'    => $mat->id,
                    'type'           => 'out_pos_sale',
                    'amount'         => -$neededQty,
                    'stock_before'   => $stockBefore,
                    'stock_after'    => $stockAfter,
                    'order_id'       => $orderId,
                    'reference_note' => "Penjualan POS ({$soldQty} porsi)",
                ]);
            });
        }
    }
}
