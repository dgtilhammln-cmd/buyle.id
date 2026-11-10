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
        // Clear buy_now isolation when user visits cart page normally
        session()->forget('buy_now_cart_id');
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
            'admission_token'  => 'nullable|string',
        ]);

        try {
            // Resolve custom_price untuk traktir / PWYW
            $customPrice = null;
            $product = \App\Models\Product::findOrFail($request->product_id);

            // Validasi Server-Side Admission Token jika produk mengaktifkan jadwal War Tiket
            if ($product->isTicketWar() && $product->isTicketWarOpen()) {
                $warService = app(\App\Services\TicketWarQueueService::class);
                $admissionToken = $request->input('admission_token') 
                    ?? session('war_admission_token_' . $product->id);

                if (!$warService->validateToken($admissionToken, $product->id)) {
                    throw new \Exception('Sesi antrean tiket tidak valid atau telah kedaluwarsa. Mohon antre kembali melalui tombol Beli Tiket.');
                }

                // Simpan token di session agar berlanjut ke tahap checkout
                session(['war_admission_token_' . $product->id => $admissionToken]);
            }
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

            // action === 'buy' — simpan cart_id di session agar checkout hanya proses produk ini
            // (produk lain di keranjang TIDAK ikut checkout)
            $cartItem = $this->cartService->getLatestCartItem((int) $request->product_id, $request->variant_value_id ? (int) $request->variant_value_id : null);
            if ($cartItem) {
                session(['buy_now_cart_id' => $cartItem->id]);
            }

            $checkoutParams = [];
            $mejaVal = $request->input('table_number') ?: $request->input('meja');
            $pTypeLower = strtolower($product->product_type ?? '');
            $catLower   = strtolower($product->category?->name ?? '');
            $isDigitalOrService = in_array($pTypeLower, ['digital', 'service', 'jasa', 'ticket', 'course', 'ebook', 'download', 'virtual', 'file', 'external_link'])
                || (method_exists($product, 'isTraktir') && $product->isTraktir())
                || (method_exists($product, 'isPayWhatYouWant') && $product->isPayWhatYouWant())
                || in_array($catLower, ['jasa', 'service', 'layanan', 'booking & jasa layanan online', 'booking', 'tiket & event', 'ticket']);

            $isFoodProduct = !$isDigitalOrService && (
                in_array($pTypeLower, ['makanan', 'fnb', 'food'])
                || in_array($catLower, ['makanan', 'food', 'culinary', 'kuliner', 'resto', 'fnb', 'makanan & minuman'])
                || preg_match('/\b(nasi|mie|bakso|soto|ayam|bebek|kopi|es|minuman|resto|cafe|kuliner)\b/i', $product->name ?? '')
            );
            if ($mejaVal && $isFoodProduct) {
                $checkoutParams['meja'] = $mejaVal;
            }
            return redirect()->route('checkout.index', $checkoutParams)->with('success', 'Produk siap untuk checkout.');
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
