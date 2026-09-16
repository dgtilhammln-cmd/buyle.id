<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariantValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Dapatkan semua item cart milik user (atau session jika guest).
     */
    public function getItems(): Collection
    {
        $query = Cart::with(['product.category', 'variantValue.variantOption']);

        if (auth()->check()) {
            $query->forUser(auth()->id());
        } else {
            $query->forSession(Session::getId());
        }

        return $query->get();
    }

    /**
     * Tambah item ke cart.
     */
    public function add(int $productId, ?int $variantValueId = null, int $qty = 1): Cart
    {
        $product = Product::findOrFail($productId);

        if (auth()->check() && $product->seller_id && (int)$product->seller_id === (int)auth()->id()) {
            throw new \Exception('Anda tidak dapat membeli produk milik Anda sendiri.');
        }

        if (! $product->is_available) {
            throw new \Exception('Produk tidak tersedia atau stok habis.');
        }

        if ($product && ($product->type !== 'service' || isset($product->stock))) {
            if ($product->stock !== null && $product->stock < $qty) {
                throw new \Exception('Stok tidak mencukupi.');
            }
        }

        if (isset($product->min_order) && $qty < $product->min_order) {
            throw new \Exception("Minimum pembelian untuk produk ini adalah {$product->min_order}.");
        }

        // Cek existing
        $query = Cart::where('product_id', $productId)
                     ->where('variant_value_id', $variantValueId);

        if (auth()->check()) {
            $query->forUser(auth()->id());
        } else {
            $query->forSession(Session::getId());
        }

        $cart = $query->first();

        if ($cart) {
            $newQty = $cart->qty + $qty;
            if ($product->max_order && $newQty > $product->max_order) {
                throw new \Exception('Melebihi batas maksimal pembelian: ' . $product->max_order);
            }
            $cart->update(['qty' => $newQty]);
            return $cart;
        }

        if ($product->min_order && $qty < $product->min_order) {
            throw new \Exception('Minimal pembelian adalah: ' . $product->min_order);
        }

        return Cart::create([
            'user_id'          => auth()->id(),
            'session_id'       => auth()->check() ? null : Session::getId(),
            'product_id'       => $productId,
            'variant_value_id' => $variantValueId,
            'qty'              => $qty,
        ]);
    }

    /**
     * Update qty item di cart.
     */
    public function update(int $cartId, int $qty): void
    {
        $cart = Cart::with('product')->findOrFail($cartId);

        if ($qty <= 0) {
            $cart->delete();
            return;
        }

        $product = $cart->product;

        if ($product->min_order && $qty < $product->min_order) {
            throw new \Exception('Minimal pembelian adalah: ' . $product->min_order);
        }

        if ($product->max_order && $qty > $product->max_order) {
            throw new \Exception('Batas maksimal pembelian adalah: ' . $product->max_order);
        }

        if ($product && $product->type !== 'service' && $product->stock !== null && $product->stock < $qty) {
            throw new \Exception('Stok tidak mencukupi. Tersisa: ' . $product->stock);
        }

        $cart->update(['qty' => $qty]);
    }

    /**
     * Hapus item dari cart.
     */
    public function remove(int $cartId): void
    {
        Cart::where('id', $cartId)->delete();
    }

    /**
     * Kosongkan seluruh cart.
     */
    public function emptyCart(): void
    {
        $query = Cart::query();
        if (auth()->check()) {
            $query->forUser(auth()->id());
        } else {
            $query->forSession(Session::getId());
        }
        $query->delete();
    }

    /**
     * Merge guest cart ke user cart (dipanggil saat login).
     */
    public function mergeGuestCart(int $userId, string $sessionId): void
    {
        $guestItems = Cart::forSession($sessionId)->get();
        if ($guestItems->isEmpty()) {
            return;
        }

        foreach ($guestItems as $guestItem) {
            $existingUserCart = Cart::forUser($userId)
                                    ->where('product_id', $guestItem->product_id)
                                    ->where('variant_value_id', $guestItem->variant_value_id)
                                    ->first();

            if ($existingUserCart) {
                // Update qty, dengan asumsi tidak melebihi stok
                $newQty = $existingUserCart->qty + $guestItem->qty;
                $existingUserCart->update(['qty' => $newQty]);
                $guestItem->delete();
            } else {
                // Pindahkan ke user
                $guestItem->update([
                    'user_id'    => $userId,
                    'session_id' => null,
                ]);
            }
        }
    }

    /**
     * Dapatkan ringkasan cart.
     */
    public function getSummary(): array
    {
        $items = $this->getItems();
        $subtotal = 0;
        $totalWeight = 0;
        
        $hasGoodsShipping = false;
        $hasGoodsShipping = false;
        $hasFood          = false;
        $hasDigitalOnly   = true;

        foreach ($items as $item) {
            $subtotal += $item->subtotal;
            $itemWeight = $item->product ? $item->product->effective_weight : 1000;
            $totalWeight += $itemWeight * $item->qty;

            if ($item->product) {
                $p = $item->product;
                $pName   = strtolower($p->name ?? '');
                $pType   = strtolower($p->product_type ?? $p->type ?? '');
                $catName = strtolower($p->category->name ?? '');

                // Check block category if product belongs to bio block
                $blockCat = '';
                if (isset($p->bioBlock) && is_array($p->bioBlock->data_json)) {
                    $blockCat = strtolower($p->bioBlock->data_json['category'] ?? '');
                }

                // Food / FnB keywords
                $foodKeywords = ['nasi', 'mie', 'ayam', 'bebek', 'daging', 'ikan', 'es', 'kopi', 'makanan', 'minuman', 'kuliner', 'food', 'drink', 'resto', 'cafe', 'menu', 'paket', 'porsi', 'jus', 'teh', 'bakso', 'soto', 'gudeg', 'sate', 'bento', 'snack', 'kue', 'roti', 'donut', 'pizza', 'burger', 'seafood', 'dimsum', 'coffe', 'tea', 'boba'];
                $isFoodName = false;
                foreach ($foodKeywords as $kw) {
                    if (str_contains($pName, $kw)) {
                        $isFoodName = true;
                        break;
                    }
                }

                // 1. Food / FnB classification
                $isFood = ($isFoodName 
                           || $blockCat === 'makanan' 
                           || $pType === 'makanan' 
                           || $pType === 'fnb'
                           || in_array($catName, ['makanan', 'food', 'culinary', 'kuliner', 'resto', 'fnb', 'makanan & minuman']));

                // 2. Goods / Barang Physical classification
                $isGoods = ($blockCat === 'barang' 
                            || $pType === 'physical' 
                            || $pType === 'barang' 
                            || in_array($catName, ['barang', 'produk fisik', 'umkm', 'peralatan dapur', 'kebersihan', 'kamar tidur', 'kamar mandi', 'elektronik', 'taman & outdoor', 'perkakas', 'laundry', 'penyimpanan', 'pengiriman kilat']));

                // 3. Digital, Ticket, Service / Jasa classification
                $isDigitalOrService = in_array($pType, ['digital', 'service', 'jasa', 'ticket', 'course', 'ebook', 'download', 'virtual', 'file', 'external_link']) 
                                       || $blockCat === 'jasa'
                                       || in_array($catName, ['jasa', 'service', 'layanan', 'booking & jasa layanan online', 'booking', 'tiket & event', 'ticket']);

                if ($isGoods) {
                    $hasGoodsShipping = true;
                    $hasDigitalOnly   = false;
                } elseif ($isFood) {
                    $hasFood        = true;
                    $hasDigitalOnly = false;
                }
            }
        }

        $checkoutType = 'digital';
        if ($hasGoodsShipping) {
            $checkoutType = 'goods';
        } elseif ($hasFood) {
            $checkoutType = 'food';
        }

        return [
            'items'                => $items,
            'count'                => $items->sum('qty'),
            'subtotal'             => $subtotal,
            'total_weight'         => $totalWeight,
            'checkout_type'        => $checkoutType,
            'has_goods_shipping'   => ($checkoutType === 'goods'),
            'has_food'             => ($checkoutType === 'food'),
            'has_food_or_service'  => ($checkoutType === 'food'),
            'has_digital_only'     => ($checkoutType === 'digital'),
            'has_physical_product' => ($checkoutType === 'goods'),
            'has_service'          => ($checkoutType === 'digital'),
        ];
    }
}
