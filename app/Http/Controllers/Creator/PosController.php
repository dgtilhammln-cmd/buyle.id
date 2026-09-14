<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\CreatorProfile;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Services\MidtransService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PosController extends Controller
{
    /**
     * Helper privat mengambil produk Makanan dari Link in Bio.
     */
    private function getPosFoodProducts(int $sellerId)
    {
        $profile = CreatorProfile::where('user_id', $sellerId)->first();
        
        $foodKeywords = [
            'nasi', 'mie', 'ayam', 'bebek', 'daging', 'ikan', 'es', 'kopi', 
            'makanan', 'minuman', 'kuliner', 'food', 'drink', 'resto', 'cafe', 
            'menu', 'paket', 'porsi', 'jus', 'teh', 'bakso', 'soto', 'gudeg', 
            'sate', 'bento', 'snack', 'kue', 'roti', 'donut', 'pizza', 'burger', 
            'seafood', 'dimsum', 'coffe', 'tea', 'boba', 'manja'
        ];

        $productIdsToInclude = collect();
        $excludedProductIds  = collect();
        $blockDataMap        = [];

        if ($profile) {
            $bioBlocks = \App\Models\CreatorBioBlock::where('creator_id', $profile->id)
                ->whereIn('type', ['custom_product', 'buyle_product'])
                ->where('is_active', true)
                ->get();

            foreach ($bioBlocks as $block) {
                $data = $block->data_json ?? [];
                $pId  = $data['product_id'] ?? null;
                $cat  = strtolower(trim($data['category'] ?? ''));
                $title = strtolower(trim($block->title ?? ''));

                if ($pId) {
                    $blockDataMap[$pId] = [
                        'name'  => $block->title,
                        'price' => $data['price'] ?? null,
                        'image' => !empty($data['images'][0]) ? $data['images'][0] : ($data['image'] ?? null),
                        'cat'   => $cat,
                    ];
                }

                // Jika dikategorikan tegas sebagai Barang atau Jasa, KECUALIKAN dari POS
                if ($cat === 'barang' || $cat === 'jasa') {
                    if ($pId) $excludedProductIds->push($pId);
                    continue;
                }

                // Jika dikategorikan Makanan / FnB atau judul memiliki kata kunci makanan
                $isFoodCat = in_array($cat, ['makanan', 'food', 'kuliner', 'fnb', 'resto', 'minuman', 'drink', 'snack', 'kue', 'cafe']);
                $isFoodKeyword = false;
                foreach ($foodKeywords as $kw) {
                    if (str_contains($title, strtolower($kw))) {
                        $isFoodKeyword = true;
                        break;
                    }
                }

                if ($isFoodCat || $isFoodKeyword || empty($cat)) {
                    if ($pId) $productIdsToInclude->push($pId);
                }
            }
        }

        // Query tabel products
        $query = Product::where('seller_id', $sellerId)
            ->where('is_active', true);

        // Jangan sertakan produk digital, tiket, external_link, service, atau yang dikategorikan Barang
        $query->whereNotIn('product_type', ['digital', 'ticket', 'external_link', 'service']);

        if ($excludedProductIds->isNotEmpty()) {
            $query->whereNotIn('id', $excludedProductIds);
        }

        if ($productIdsToInclude->isNotEmpty()) {
            $query->where(function ($q) use ($productIdsToInclude, $foodKeywords) {
                $q->whereIn('id', $productIdsToInclude)
                  ->orWhere('product_type', 'makanan')
                  ->orWhere(function ($q2) use ($foodKeywords) {
                      foreach ($foodKeywords as $kw) {
                          $q2->orWhere(DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($kw) . '%');
                      }
                  });
            });
        } else {
            $query->where(function ($q) use ($foodKeywords) {
                $q->where('product_type', 'makanan')
                  ->orWhere(function ($q2) use ($foodKeywords) {
                      foreach ($foodKeywords as $kw) {
                          $q2->orWhere(DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($kw) . '%');
                      }
                  });
            });
        }

        $products = $query->with('category')->orderBy('name', 'asc')->get();

        // Overwrite name, price & image dari data bio block jika ada
        foreach ($products as $prod) {
            if (isset($blockDataMap[$prod->id])) {
                $bMeta = $blockDataMap[$prod->id];
                if (!empty($bMeta['name']))  $prod->name  = $bMeta['name'];
                if ($bMeta['price'] !== null) $prod->price = (float)$bMeta['price'];
                if (!empty($bMeta['image'])) $prod->image = $bMeta['image'];
            }
        }

        return $products;
    }

    /**
     * Tampilkan antarmuka Kasir Digital (POS).
     */
    public function index(Request $request)
    {
        $seller = auth()->user();

        // 1. Ambil profil toko creator
        $profile = CreatorProfile::where('user_id', $seller->id)->first();

        // 2. Ambil produk makanan untuk POS
        $products = $this->getPosFoodProducts($seller->id);

        // 3. Transaksi POS hari ini
        $todayOrders = Order::where('source', 'pos')
            ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
            ->whereDate('created_at', now()->today())
            ->with(['items', 'payment'])
            ->orderByDesc('created_at')
            ->get();

        // 4. Setting fee
        $platformFeeRate = (float) Setting::get('platform_fee_rate', 5);
        $adminFeeRate    = (float) Setting::get('admin_fee_rate', 5);

        return view('creator.pos.index', compact(
            'products',
            'todayOrders',
            'profile',
            'platformFeeRate',
            'adminFeeRate'
        ));
    }

    /**
     * Dipanggil AJAX: Mengambil produk ter-sync dari Link in Bio.
     */
    public function products(Request $request)
    {
        $seller = auth()->user();
        $products = $this->getPosFoodProducts($seller->id);

        return response()->json([
            'success'  => true,
            'products' => $products
        ]);
    }

    /**
     * Proses Checkout Transaksi POS.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:100',
            'customer_email' => 'nullable|email|max:100',
            'customer_phone' => 'nullable|string|max:30',
            'table_number'   => 'nullable|string|max:50',
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,transfer,qris',
        ]);

        $seller = auth()->user();

        DB::beginTransaction();
        try {
            // Hitung subtotal & susun items
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($request->items as $itemData) {
                $product = Product::where('id', $itemData['id'])
                    ->where('seller_id', $seller->id)
                    ->firstOrFail();

                $price    = (float) ($product->sale_price ?: $product->price);
                $qty      = (int) $itemData['qty'];
                $itemSub  = $price * $qty;
                $subtotal += $itemSub;

                $orderItemsData[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $price,
                    'quantity'     => $qty,
                    'subtotal'     => $itemSub,
                ];
            }

            // Hitung Diskon
            $discountAmount = 0;
            if ($request->discount_type === 'percent') {
                $pct = min(100, max(0, (float) ($request->discount_val ?? 0)));
                $discountAmount = ($subtotal * $pct) / 100;
            } elseif ($request->discount_type === 'nominal') {
                $discountAmount = min($subtotal, max(0, (float) ($request->discount_val ?? 0)));
            }

            // Biaya Layanan Tambahan (opsional oleh toko)
            $serviceFee = max(0, (float) ($request->service_fee ?? 0));

            // System Fee Calculation (Platform Fee & Admin Fee)
            $platformFeeRate = (float) Setting::get('platform_fee_rate', 5);
            $adminFeeRate    = (float) Setting::get('admin_fee_rate', 5);

            $platformFee = ($subtotal * $platformFeeRate) / 100;
            $adminFee    = ($subtotal * $adminFeeRate) / 100;

            $total = max(0, $subtotal - $discountAmount + $serviceFee + $platformFee + $adminFee);

            $paymentMethod = $request->payment_method;
            $isPaid = in_array($paymentMethod, ['cash', 'transfer']);

            // Create Order
            $order = Order::create([
                'order_number'    => Order::generateOrderNumber(),
                'user_id'         => $seller->id,
                'status'          => $isPaid ? OrderStatus::Completed : OrderStatus::Pending,
                'subtotal'        => $subtotal,
                'shipping_cost'   => 0,
                'platform_fee'    => $platformFee,
                'admin_fee'       => $adminFee,
                'discount'        => $discountAmount,
                'total'           => $total,
                'notes'           => $request->table_number ? ('Meja/Antrean: ' . $request->table_number) : null,
                'source'          => 'pos',
                'shipping_address' => [
                    'name'           => $request->customer_name,
                    'receiver_name'  => $request->customer_name,
                    'email'          => $request->customer_email,
                    'phone'          => $request->customer_phone,
                    'is_pos'         => true,
                    'payment_method' => $paymentMethod,
                    'cash_paid'      => (float) ($request->cash_paid ?? 0),
                    'cash_change'    => (float) ($request->cash_change ?? 0),
                    'service_fee'    => $serviceFee,
                ],
            ]);

            // Save Order Items
            foreach ($orderItemsData as $itemRow) {
                $itemRow['order_id'] = $order->id;
                OrderItem::create($itemRow);

                // Kurangi stok jika produk memiliki stok terhitung
                $p = Product::find($itemRow['product_id']);
                if ($p && $p->stock !== null) {
                    $p->decrement('stock', $itemRow['quantity']);
                    $p->increment('sold_count', $itemRow['quantity']);
                }
            }

            // Create Payment record
            $payment = Payment::create([
                'order_id'       => $order->id,
                'payment_method' => $paymentMethod,
                'amount'         => $total,
                'status'         => $isPaid ? PaymentStatus::Success : PaymentStatus::Pending,
                'paid_at'        => $isPaid ? now() : null,
            ]);

            DB::commit();

            // Handle Midtrans Snap Token for QRIS
            $snapToken = null;
            if ($paymentMethod === 'qris') {
                try {
                    $midtransService = app(MidtransService::class);
                    $snapToken = $midtransService->createSnapToken($order);
                } catch (Exception $e) {
                    Log::error('POS Midtrans QRIS Error: ' . $e->getMessage());
                }
            }

            // Kirim E-Receipt otomatis jika email diisi
            if (!empty($request->customer_email)) {
                $this->dispatchReceiptEmail($order, $request->customer_email);
            }

            $order->load(['items', 'payment']);

            return response()->json([
                'success'    => true,
                'message'    => 'Transaksi POS berhasil diproses',
                'order'      => $order,
                'snap_token' => $snapToken,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('POS Checkout Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses transaksi POS: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dipanggil AJAX: Kirim E-Receipt via email manual / ulang.
     */
    public function sendReceipt(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'email'    => 'required|email',
        ]);

        $seller = auth()->user();
        $order  = Order::where('id', $request->order_id)
            ->where('source', 'pos')
            ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
            ->with(['items', 'payment'])
            ->firstOrFail();

        $success = $this->dispatchReceiptEmail($order, $request->email);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'E-Receipt berhasil dikirim ke ' . $request->email,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengirim email E-Receipt. Periksa konfigurasi email SMTP server.',
        ], 500);
    }

    /**
     * Helper privat kirim HTML email E-Receipt.
     */
    private function dispatchReceiptEmail(Order $order, string $email): bool
    {
        try {
            $profile = CreatorProfile::where('user_id', auth()->id())->first();
            $storeName = $profile->store_name ?? auth()->user()->name ?? 'buyle.id Store';

            Mail::send('emails.pos_receipt', [
                'order'     => $order,
                'profile'   => $profile,
                'storeName' => $storeName,
                'subject'   => 'Struk Pembayaran - ' . $storeName . ' (' . $order->order_number . ')',
            ], function ($message) use ($email, $order, $storeName) {
                $message->to($email)
                    ->subject('Struk Pembayaran - ' . $storeName . ' (' . $order->order_number . ')');
            });

            return true;
        } catch (Exception $e) {
            Log::error('POS Dispatch Receipt Email Error: ' . $e->getMessage());
            return false;
        }
    }
}
