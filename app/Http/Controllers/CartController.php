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
            'affiliate_id'     => 'nullable|exists:users,id',
            'custom_price'     => 'nullable|integer|min:0', // Untuk traktir/PWYW
        ]);

        try {
            // Resolve custom_price untuk traktir / PWYW
            $customPrice = null;
            $product = \App\Models\Product::findOrFail($request->product_id);
            if ($product->isTraktir()) {
                // Hitung: jumlah kopi × harga per kopi
                $unitPrice = (float)($product->traktir_unit_price ?? 10000);
                $qty = (int)$request->qty;
                $customPrice = $unitPrice * $qty; // total custom price = unit × qty
            } elseif ($product->isPayWhatYouWant()) {
                // Pembeli bebas isi nominal (custom_price dikirim dari form)
                $rawCustom = (int) preg_replace('/[^\d]/', '', (string) ($request->custom_price ?? 0));
                $minPrice  = (float)($product->pwyw_min_price ?? 0);
                if ($rawCustom < $minPrice) {
                    throw new \Exception('Nominal minimum donasi adalah Rp ' . number_format($minPrice, 0, ',', '.') . '.');
                }
                $customPrice = $rawCustom > 0 ? $rawCustom : null;
            }

            $this->cartService->add(
                (int) $request->product_id,
                $request->variant_value_id ? (int) $request->variant_value_id : null,
                $product->isTraktir() ? 1 : (int) $request->qty, // Traktir qty selalu 1 (custom_price yg berubah)
                $request->affiliate_id ? (int) $request->affiliate_id : null,
                $customPrice
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

            // action === 'buy' — skip keranjang, langsung ke checkout
            return redirect()->route('checkout.index')->with('success', 'Produk siap untuk checkout.');
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
