<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\CreatorProfile;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Services\MidtransService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StorefrontController extends Controller
{
    /**
     * Tampilkan halaman publik Storefront (Self-Order Menu) untuk pelanggan.
     */
    public function show(Request $request, $slug)
    {
        $profile = CreatorProfile::where('store_slug', $slug)
            ->orWhereHas('user', function ($q) use ($slug) {
                $q->where('username', $slug);
            })
            ->with(['user'])
            ->first();

        if (!$profile && str_starts_with($slug, 'toko-')) {
            $uid = (int) str_replace('toko-', '', $slug);
            $profile = CreatorProfile::where('user_id', $uid)->with('user')->first();
        }

        if (!$profile || !$profile->user) {
            abort(404, 'Toko tidak ditemukan.');
        }

        $seller = $profile->user;

        // Staff shift fallback for hours
        $cashiers = \App\Models\User::where('parent_seller_id', $seller->id)
            ->where('is_active', true)
            ->get();

        $staffEarlyStart = null;
        $staffLateEnd = null;
        if ($cashiers->isNotEmpty()) {
            $times = [];
            foreach ($cashiers as $c) {
                if (!empty($c->shift_hours) && preg_match_all('/\b(\d{1,2}[:.]\d{2})\b/', $c->shift_hours, $matches)) {
                    foreach ($matches[1] as $t) {
                        $norm = str_replace('.', ':', $t);
                        if (strlen($norm) === 4) $norm = '0' . $norm;
                        $times[] = $norm;
                    }
                }
            }
            if (!empty($times)) {
                sort($times);
                $staffEarlyStart = $times[0];
                $staffLateEnd = end($times);
            }
        }

        $storeOpen = $profile->pos_store_open ?: ($staffEarlyStart ?: '08:00');
        $storeClose = $profile->pos_store_close ?: ($staffLateEnd ?: '22:00');

        // Check if currently open in Asia/Jakarta
        $nowWib = Carbon::now('Asia/Jakarta');
        $currentTime = $nowWib->format('H:i');
        $isOpen = ($currentTime >= $storeOpen && $currentTime <= $storeClose);

        $storeName = $profile->store_name ?: ($seller->name ?: 'Toko Kami');
        $posLogoUrl = $profile->pos_logo
            ? asset('storage/' . $profile->pos_logo)
            : ($seller->avatar ? (str_starts_with($seller->avatar, 'http') ? $seller->avatar : asset('storage/' . $seller->avatar)) : null);
        $posBannerUrl = $profile->pos_banner ? asset('storage/' . $profile->pos_banner) : null;
        $posAccentColor = $profile->pos_accent_color ?: 'linear-gradient(135deg, #1db349 0%, #a5cf36 100%)';
        $posStoreAddress = $profile->pos_store_address ?? $profile->address ?? '';
        $posStorePhone = $profile->pos_store_phone ?? $seller->phone ?? '';
        $posMapsLink = $profile->pos_maps_link;

        // Products & Kelompok Produk (HANYA tipe makanan/minuman dan kelompok produk kreator)
        $products = Product::where('seller_id', $seller->id)
            ->where('is_active', true)
            ->whereIn('product_type', ['makanan', 'food', 'beverage', 'minuman'])
            ->with(['creatorGroup', 'variantOptions.values'])
            ->orderBy('name')
            ->get();

        $groups = \App\Models\CreatorProductGroup::where('seller_id', $seller->id)
            ->where('is_active', true)
            ->whereHas('products', function ($pq) use ($seller) {
                $pq->where('seller_id', $seller->id)
                    ->where('is_active', true)
                    ->whereIn('product_type', ['makanan', 'food', 'beverage', 'minuman']);
            })
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $posTables = $profile->getPosTables();
        $posFees = $profile->getPosFees();
        $posDiscounts = $profile->getPosDiscounts();

        $selectedTable = trim($request->query('meja') ?: $request->query('table', ''));

        return view('storefront.index', compact(
            'slug',
            'seller',
            'profile',
            'storeName',
            'posLogoUrl',
            'posBannerUrl',
            'posAccentColor',
            'posStoreAddress',
            'posStorePhone',
            'posMapsLink',
            'storeOpen',
            'storeClose',
            'isOpen',
            'products',
            'groups',
            'posTables',
            'posFees',
            'posDiscounts',
            'selectedTable'
        ));
    }

    /**
     * Submit Self-Order dari Storefront oleh pelanggan.
     */
    public function placeOrder(Request $request, $slug)
    {
        $profile = CreatorProfile::where('store_slug', $slug)
            ->orWhereHas('user', function ($q) use ($slug) {
                $q->where('username', $slug);
            })
            ->with(['user'])
            ->first();

        if (!$profile && str_starts_with($slug, 'toko-')) {
            $uid = (int) str_replace('toko-', '', $slug);
            $profile = CreatorProfile::where('user_id', $uid)->with('user')->first();
        }

        if (!$profile || !$profile->user) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.'], 404);
        }

        $seller = $profile->user;

        $orderType = $request->input('order_type', 'dine_in'); // 'dine_in' or 'takeaway'
        $tableNumber = ($orderType === 'takeaway') ? 'Takeaway' : trim($request->input('table_number', ''));

        $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.id'       => 'required|exists:products,id',
            'items.*.qty'      => 'required|integer|min:1',
            'order_type'       => 'nullable|in:dine_in,takeaway',
            'table_number'     => $orderType === 'takeaway' ? 'nullable|string|max:50' : 'required|string|max:50',
            'customer_name'    => 'required|string|min:2|max:100',
            'customer_phone'   => 'required|string|min:8|max:30',
            'payment_method'   => 'required|in:cash,midtrans',
            'notes'            => 'nullable|string|max:300',
        ], [
            'customer_name.required'  => 'Nama pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'table_number.required'   => 'Nomor meja wajib dipilih untuk Dine In.',
            'items.required'          => 'Keranjang pesanan masih kosong.',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($request->items as $itemData) {
                $product = Product::where('id', $itemData['id'])
                    ->where('seller_id', $seller->id)
                    ->whereIn('product_type', ['makanan', 'food', 'beverage', 'minuman'])
                    ->firstOrFail();

                $price = (float) ($product->sale_price ?: $product->price);
                $qty = (int) $itemData['qty'];
                $itemSub = $price * $qty;
                $subtotal += $itemSub;

                $orderItemsData[] = [
                    'product_id'       => $product->id,
                    'product_name'     => $product->name,
                    'variant_value_id' => $itemData['variant_value_id'] ?? null,
                    'variant_name'     => $itemData['variant_name'] ?? null,
                    'notes'            => $itemData['notes'] ?? null,
                    'price'            => $price,
                    'qty'              => $qty,
                    'subtotal'         => $itemSub,
                ];
            }

            // Hitung Biaya & Pajak POS Toko
            $posFees = $profile->getPosFees();
            $feesBreakdown = [];
            $serviceFee = 0;
            foreach ($posFees as $f) {
                if (isset($f['is_active']) && !$f['is_active']) continue;
                $val = (float) ($f['value'] ?? 0);
                $feeAmt = ($f['type'] === 'percent') ? ($subtotal * $val / 100) : $val;
                $serviceFee += $feeAmt;
                $feesBreakdown[] = [
                    'name'   => $f['name'] ?? 'Biaya',
                    'type'   => $f['type'] ?? 'percent',
                    'value'  => $val,
                    'amount' => $feeAmt,
                ];
            }

            // Hitung Diskon Otomatis (jika ada preset yang match)
            $discountAmount = 0;
            $discountDetails = [];
            $posDiscounts = $profile->getPosDiscounts();
            $totalQty = array_sum(array_column($orderItemsData, 'qty'));
            foreach ($posDiscounts as $d) {
                if (isset($d['is_active']) && !$d['is_active']) continue;
                $minQty = (int) ($d['min_qty'] ?? 1);
                if ($totalQty >= $minQty) {
                    $val = (float) ($d['value'] ?? 0);
                    $amt = ($d['type'] === 'percent') ? ($subtotal * $val / 100) : min($subtotal, $val);
                    if ($amt > $discountAmount) {
                        $discountAmount = $amt;
                        $discountDetails = [
                            'name'    => $d['name'] ?? 'Diskon',
                            'type'    => $d['type'] ?? 'percent',
                            'value'   => $val,
                            'min_qty' => $minQty,
                            'amount'  => $amt,
                        ];
                    }
                }
            }

            // Fee Platform Sistem
            $feeCalc = $profile->calculatePosFees($subtotal);
            $platformFee = $feeCalc['platform_fee'] ?? 0;
            $adminFee    = $feeCalc['admin_fee'] ?? 0;
            $totalFee    = $platformFee + $adminFee;

            $posFeePayer = $profile->pos_fee_payer ?? 'seller';
            if ($posFeePayer === 'buyer') {
                $total = max(0, $subtotal - $discountAmount + $serviceFee + $totalFee);
            } else {
                $total = max(0, $subtotal - $discountAmount + $serviceFee);
            }

            $customerName = trim($request->customer_name ?? '') ?: ($orderType === 'takeaway' ? 'Pelanggan Takeaway' : ('Pelanggan ' . $tableNumber));
            $paymentMethod = $request->payment_method; // 'cash' or 'midtrans'
            $orderLabel = ($orderType === 'takeaway') ? 'Takeaway (Bawa Pulang)' : ('Meja: ' . $tableNumber);

            // Create Order
            $order = Order::create([
                'order_number'    => Order::generateOrderNumber(),
                'user_id'         => $seller->id,
                'status'          => OrderStatus::Pending,
                'subtotal'        => $subtotal,
                'shipping_cost'   => 0,
                'platform_fee'    => $platformFee,
                'admin_fee'       => $adminFee,
                'discount'        => $discountAmount,
                'total'           => $total,
                'notes'           => 'Self-Order ' . $orderLabel . ($request->filled('notes') ? ' - ' . $request->notes : ''),
                'source'          => 'storefront',
                'shipping_address' => [
                    'name'             => $customerName,
                    'receiver_name'    => $customerName,
                    'phone'            => $request->customer_phone,
                    'is_pos'           => true,
                    'is_self_order'    => true,
                    'order_type'       => $orderType,
                    'table_number'     => $tableNumber,
                    'payment_method'   => $paymentMethod === 'cash' ? 'cash' : 'midtrans',
                    'service_fee'      => $serviceFee,
                    'fees_breakdown'   => $feesBreakdown,
                    'discount_details' => $discountDetails,
                    'items_meta'       => $orderItemsData,
                ],
            ]);

            // Save Items
            foreach ($orderItemsData as $itemRow) {
                OrderItem::create([
                    'order_id'         => $order->id,
                    'product_id'       => $itemRow['product_id'],
                    'variant_value_id' => $itemRow['variant_value_id'] ?? null,
                    'variant_name'     => $itemRow['variant_name'] ?? null,
                    'product_name'     => $itemRow['product_name'],
                    'price'            => $itemRow['price'],
                    'qty'              => $itemRow['qty'],
                    'subtotal'         => $itemRow['subtotal'],
                ]);

                // Reduce stock
                $p = Product::find($itemRow['product_id']);
                if ($p && $p->stock !== null) {
                    $p->decrement('stock', $itemRow['qty']);
                    $p->increment('sold_count', $itemRow['qty']);
                }
            }

            // Create Payment record
            Payment::create([
                'payment_number' => Payment::generatePaymentNumber(),
                'order_id'       => $order->id,
                'method'         => $paymentMethod === 'cash' ? 'cash' : 'snap_midtrans',
                'gateway'        => $paymentMethod === 'cash' ? 'cashier' : 'midtrans',
                'amount'         => $total,
                'status'         => PaymentStatus::Pending,
                'paid_at'        => null,
            ]);

            DB::commit();

            // Snap Midtrans Token if digital payment
            $snapToken = null;
            if ($paymentMethod === 'midtrans') {
                try {
                    $midtransService = app(MidtransService::class);
                    $snapToken = $midtransService->createSnapToken($order);
                } catch (Exception $e) {
                    Log::error('Storefront Snap Token Error: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success'        => true,
                'order_id'       => $order->id,
                'order_number'   => $order->order_number,
                'total'          => $total,
                'total_fmt'      => 'Rp ' . number_format($total, 0, ',', '.'),
                'payment_method' => $paymentMethod,
                'snap_token'     => $snapToken,
                'table_number'   => $request->table_number,
                'status_url'     => route('storefront.status', ['slug' => $slug, 'id' => $order->id]),
                'message'        => $paymentMethod === 'cash'
                    ? 'Pesanan berhasil dibuat! Silakan bayar di kasir.'
                    : 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Storefront Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Halaman status pesanan untuk pelanggan self-order.
     */
    public function status(Request $request, $slug, $id)
    {
        $profile = CreatorProfile::where('store_slug', $slug)
            ->orWhereHas('user', function ($q) use ($slug) {
                $q->where('username', $slug);
            })
            ->with(['user'])
            ->first();

        if (!$profile && str_starts_with($slug, 'toko-')) {
            $uid = (int) str_replace('toko-', '', $slug);
            $profile = CreatorProfile::where('user_id', $uid)->with('user')->first();
        }

        if (!$profile || !$profile->user) {
            abort(404);
        }

        $seller = $profile->user;
        $order = Order::where('id', $id)
            ->where('source', 'storefront')
            ->where('user_id', $seller->id)
            ->with(['items.product', 'payment'])
            ->firstOrFail();

        $storeName = $profile->store_name ?: ($seller->name ?: 'Toko');
        $posLogoUrl = $profile->pos_logo ? asset('storage/' . $profile->pos_logo) : null;
        $posAccentColor = $profile->pos_accent_color ?: 'linear-gradient(135deg, #1db349 0%, #a5cf36 100%)';

        $isProduction = config('midtrans.is_production', false);
        $snapUrl = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $midtransClientKey = config('midtrans.client_key');

        return view('storefront.status', compact('order', 'profile', 'seller', 'storeName', 'posLogoUrl', 'posAccentColor', 'slug', 'snapUrl', 'midtransClientKey'));
    }

    /**
     * API Realtime status checking dari halaman status pelanggan.
     */
    public function checkOrderStatus(Request $request, $slug, $id)
    {
        $order = Order::where('id', $id)
            ->where('source', 'storefront')
            ->with(['payment'])
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        $statusStr = is_string($order->status) ? $order->status : ($order->status?->value ?? $order->status?->name ?? 'pending');
        $statusStr = strtolower($statusStr);

        $payStat = strtolower($order->payment?->status?->value ?? $order->payment?->status?->name ?? (string)($order->payment?->status ?? ''));
        $isPaid = in_array($statusStr, ['confirmed', 'delivered', 'processing', 'completed', 'paid']) || in_array($payStat, ['success', 'paid']);
        $isCompleted = in_array($statusStr, ['delivered', 'completed']);

        $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
        $payMethod = $addr['payment_method'] ?? ($order->payment?->method ?? 'cash');

        return response()->json([
            'success'        => true,
            'status'         => $statusStr,
            'is_paid'        => $isPaid,
            'is_completed'   => $isCompleted,
            'payment_method' => $payMethod,
            'total'          => (float) $order->total,
        ]);
    }

    /**
     * Ganti metode pembayaran dari halaman status (Cash <-> Midtrans Snap).
     */
    public function switchPaymentMethod(Request $request, $slug, $id)
    {
        $order = Order::where('id', $id)
            ->where('source', 'storefront')
            ->with(['payment'])
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        $statusStr = strtolower(is_string($order->status) ? $order->status : ($order->status?->value ?? 'pending'));
        if (in_array($statusStr, ['confirmed', 'delivered', 'completed', 'paid'])) {
            return response()->json(['success' => false, 'message' => 'Pesanan sudah lunas, tidak dapat mengubah metode pembayaran.'], 400);
        }

        $target = $request->input('target_method'); // 'cash' or 'midtrans'
        if (!in_array($target, ['cash', 'midtrans'])) {
            return response()->json(['success' => false, 'message' => 'Metode pembayaran tidak valid.'], 422);
        }

        $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
        $addr['payment_method'] = $target;
        $order->shipping_address = $addr;
        $order->save();

        if ($order->payment) {
            $order->payment->method = ($target === 'cash') ? 'cash' : 'snap_midtrans';
            $order->payment->gateway = ($target === 'cash') ? 'cashier' : 'midtrans';
            $order->payment->save();
        }

        $snapToken = null;
        if ($target === 'midtrans') {
            try {
                $midtransService = app(MidtransService::class);
                $snapToken = $midtransService->createSnapToken($order);
            } catch (Exception $e) {
                Log::error('Switch Payment Snap Token Error: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success'        => true,
            'target_method'  => $target,
            'snap_token'     => $snapToken,
            'message'        => ($target === 'cash')
                ? 'Metode pembayaran berhasil diubah ke Bayar di Kasir (Cash).'
                : 'Metode pembayaran berhasil diubah ke Pembayaran Digital (Snap Midtrans).'
        ]);
    }
}

