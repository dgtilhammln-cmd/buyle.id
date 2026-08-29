<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display the specified category and optionally a subcategory.
     */
    public function show($categorySlug, $subcategorySlug = null)
    {
        $category = ProductCategory::where('slug', $categorySlug)
            ->active()
            ->firstOrFail();

        $subcategory = null;
        if ($subcategorySlug) {
            $subcategory = ProductSubCategory::where('slug', $subcategorySlug)
                ->where('category_id', $category->id)
                ->firstOrFail();
        }

        $query = Product::active()->ordered();
        $query->where('product_category_id', $category->id);

        if ($subcategory) {
            $query->where('product_sub_category_id', $subcategory->id);
        }
        
        // Sorting
        $sort = request('sort', 'terbaru');
        switch ($sort) {
            case 'termurah':
                $query->orderBy('price', 'asc');
                break;
            case 'termahal':
                $query->orderBy('price', 'desc');
                break;
            case 'terbaru':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(24)->withQueryString();
        $categories = ProductCategory::active()->orderBy('order')->get();

        return view('categories.show', compact('category', 'subcategory', 'products', 'categories'));
    }
}
