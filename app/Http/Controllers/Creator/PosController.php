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
     * Helper privat mengambil seluruh produk untuk Kasir Digital (POS) dari Link in Bio & Katalog Toko.
     */
    private function getPosProducts(int $sellerId)
    {
        $profile = CreatorProfile::where('user_id', $sellerId)->first();

        $parsePrice = function ($val) {
            if (is_null($val) || $val === '' || $val === false) return 0.0;
            if (is_numeric($val)) return (float) $val;
            if (is_string($val)) {
                $clean = preg_replace('/[^0-9]/', '', $val);
                return (float) $clean;
            }
            return 0.0;
        };

        $extractImage = function ($data) {
            if (empty($data) || !is_array($data)) return null;

            $candidates = [];

            if (!empty($data['images'])) {
                if (is_array($data['images'])) {
                    foreach ($data['images'] as $img) {
                        if (is_string($img) && strlen(trim($img)) > 1) {
                            $candidates[] = trim($img);
                        }
                    }
                } elseif (is_string($data['images'])) {
                    $decoded = json_decode($data['images'], true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $img) {
                            if (is_string($img) && strlen(trim($img)) > 1) {
                                $candidates[] = trim($img);
                            }
                        }
                    } elseif (strlen(trim($data['images'])) > 1 && !str_starts_with(trim($data['images']), '[')) {
                        $candidates[] = trim($data['images']);
                    }
                }
            }

            foreach (['image', 'scraped_image', 'thumbnail', 'thumb', 'photo', 'cover', 'block_image'] as $key) {
                if (!empty($data[$key]) && is_string($data[$key]) && strlen(trim($data[$key])) > 1) {
                    $candidates[] = trim($data[$key]);
                }
            }

            return !empty($candidates) ? $candidates[0] : null;
        };

        $posProducts = collect();

        if ($profile) {
            $bioBlocks = \App\Models\CreatorBioBlock::where('creator_id', $profile->id)
                ->whereIn('type', ['custom_product', 'buyle_product'])
                ->get();

            foreach ($bioBlocks as $block) {
                $isActive = ($block->is_active === true || $block->is_active == 1 || $block->is_active === '1');
                if (!$isActive) {
                    continue;
                }

                $data  = $block->data_json ?? [];
                $pId   = $data['product_id'] ?? null;
                $product = null;

                if ($pId) {
                    $product = Product::find($pId);
                }

                if (!$product) {
                    $product = Product::where('seller_id', $sellerId)
                        ->where('name', $block->title)
                        ->first();
                }

                // Exclude digital products / tickets from POS
                if ($product && in_array(strtolower($product->product_type ?? ''), ['external_link', 'digital', 'ticket'])) {
                    continue;
                }

                $cat = strtolower(trim($data['category'] ?? ''));
                if (in_array($cat, ['digital', 'ebook', 'link', 'tiket', 'event'])) {
                    continue;
                }

                $titleLower = strtolower($block->title ?? '');
                if (preg_match('/(ebook|e-book|pdf|modul|panduan|link|akses|webinar|tiket|course|kursus)/i', $titleLower)) {
                    continue;
                }

                $extractedPrice = $parsePrice($data['price'] ?? 0);
                if ($extractedPrice <= 0) {
                    $extractedPrice = $parsePrice($data['original_price'] ?? 0);
                }
                if ($extractedPrice <= 0) {
                    $extractedPrice = $parsePrice($data['harga'] ?? 0);
                }

                $extractedImg = $extractImage($data);

                if (!$product) {
                    $baseSlug = ($data['slug'] ?? \Illuminate\Support\Str::slug($block->title)) ?: 'produk';
                    $slug     = $baseSlug;
                    while (Product::where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . \Illuminate\Support\Str::random(4);
                    }
                    $stock   = isset($data['stock']) && $data['stock'] !== '' && $data['stock'] !== null ? (int)$data['stock'] : null;

                    $product = Product::create([
                        'seller_id'    => $sellerId,
                        'name'         => $block->title,
                        'slug'         => $slug,
                        'price'        => $extractedPrice,
                        'stock'        => $stock,
                        'description'  => $data['description'] ?? '',
                        'image'        => $extractedImg,
                        'is_active'    => true,
                        'product_type' => 'physical',
                    ]);

                    $data['product_id'] = $product->id;
                    $block->data_json   = $data;
                    $block->save();
                } else {
                    $product->name = $block->title;

                    if ($extractedPrice > 0) {
                        $product->price = $extractedPrice;
                    } elseif ((float)$product->price <= 0 && (float)$product->sale_price > 0) {
                        $product->price = (float)$product->sale_price;
                    }

                    if (!empty($extractedImg)) {
                        if (empty($product->image) || strlen($product->image) <= 1) {
                            $product->image = $extractedImg;
                        }
                    }

                    if ($product->isDirty(['price', 'name', 'image'])) {
                        $product->save();
                    }
                }

                $posProducts->push($product);
            }
        }

        // Tambahkan juga produk katalog umum milik seller yang aktif (hanya produk fisik/makanan/service)
        $catalogProducts = Product::where('seller_id', $sellerId)
            ->where('is_active', true)
            ->whereNotIn('product_type', ['digital', 'ticket', 'external_link'])
            ->orderBy('name', 'asc')
            ->get();

        foreach ($catalogProducts as $cp) {
            $posProducts->push($cp);
        }

        return $posProducts->unique('id')->values();
    }

    /**
     * Tampilkan antarmuka Kasir Digital (POS).
     */
    public function index(Request $request)
    {
        $seller = auth()->user();

        // 1. Ambil profil toko creator
        $profile = CreatorProfile::where('user_id', $seller->id)->first();

        // 2. Ambil produk untuk POS
        $products = $this->getPosProducts($seller->id);

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
     * Dipanggil AJAX: Mengambil produk ter-sync dari Link in Bio & Katalog.
     */
    public function products(Request $request)
    {
        $seller = auth()->user();
        $products = $this->getPosProducts($seller->id);

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
            'customer_name'  => 'nullable|string|max:100',
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
                    'qty'          => $qty,
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
            $customerName = trim($request->customer_name ?? '') ?: 'Pelanggan Umum';

            // Create Order
            $order = Order::create([
                'order_number'    => Order::generateOrderNumber(),
                'user_id'         => $seller->id,
                'status'          => $isPaid ? OrderStatus::Confirmed : OrderStatus::Pending,
                'subtotal'        => $subtotal,
                'shipping_cost'   => 0,
                'platform_fee'    => $platformFee,
                'admin_fee'       => $adminFee,
                'discount'        => $discountAmount,
                'total'           => $total,
                'notes'           => $request->table_number ? ('No. Ref/Meja: ' . $request->table_number) : null,
                'source'          => 'pos',
                'shipping_address' => [
                    'name'           => $customerName,
                    'receiver_name'  => $customerName,
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
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $itemRow['product_id'],
                    'product_name' => $itemRow['product_name'],
                    'price'        => $itemRow['price'],
                    'qty'          => $itemRow['qty'],
                    'subtotal'     => $itemRow['subtotal'],
                ]);

                // Kurangi stok jika produk memiliki stok terhitung
                $p = Product::find($itemRow['product_id']);
                if ($p && $p->stock !== null) {
                    $p->decrement('stock', $itemRow['qty']);
                    $p->increment('sold_count', $itemRow['qty']);
                }
            }

            // Create Payment record
            $payment = Payment::create([
                'payment_number' => Payment::generatePaymentNumber(),
                'order_id'       => $order->id,
                'method'         => $paymentMethod,
                'gateway'        => $paymentMethod === 'qris' ? 'midtrans' : 'direct',
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
            ->where('user_id', $seller->id)
            ->with(['items', 'payment'])
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi POS tidak ditemukan.',
            ], 404);
        }

        try {
            $this->dispatchReceiptEmail($order, $request->email);
            return response()->json([
                'success' => true,
                'message' => 'E-Receipt berhasil dikirim ke ' . $request->email,
            ]);
        } catch (Exception $e) {
            Log::error('POS Dispatch Receipt Email Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email E-Receipt: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper privat kirim HTML email E-Receipt.
     */
    private function dispatchReceiptEmail(Order $order, string $email): void
    {
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
    }
}
