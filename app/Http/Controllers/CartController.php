<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $summary = $this->cartService->getSummary();
        $seo = ['title' => 'Keranjang Belanja', 'robots' => 'noindex, nofollow', 'canonical' => route('cart.index')];
        return view('ecommerce.cart', compact('summary', 'seo'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'variant_value_id' => 'nullable|exists:product_variant_values,id',
            'qty'              => 'required|integer|min:1',
        ]);

        try {
            $this->cartService->add(
                $request->product_id,
                $request->variant_value_id,
                $request->qty
            );

            $summary = $this->cartService->getSummary();
            $totalItems = (int)($summary['total_items'] ?? 1);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang.',
                    'total_items' => $totalItems,
                ]);
            }

            if ($request->input('action') === 'cart') {
                return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
            }

            return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'qty'     => 'required|integer|min:0', // 0 = remove
        ]);

        try {
            $this->cartService->update($request->cart_id, $request->qty);
            return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        $this->cartService->remove($request->cart_id);
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}
