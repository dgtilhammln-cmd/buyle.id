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
use App\Models\PosDeposit;
use App\Models\PosBalanceMutation;
use App\Models\Setting;
use App\Services\MidtransService;
use Carbon\Carbon;
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
        $authUser = auth()->user();
        $isStaff = $authUser->isPosCashier() && $authUser->parent_seller_id;
        $seller = $isStaff ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser) : $authUser;
        $currentCashier = $isStaff ? $authUser : null;

        // 1. Ambil profil toko creator
        $profile = CreatorProfile::where('user_id', $seller->id)->first();

        // 2. Ambil produk untuk POS
        $products = $this->getPosProducts($seller->id);

        // 3. Transaksi POS hari ini
        $todayOrders = Order::where('source', 'pos')
            ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
            ->whereDate('created_at', now()->today())
            ->whereIn('status', [
                OrderStatus::Confirmed,
                OrderStatus::Processing,
                OrderStatus::Shipped,
                OrderStatus::Delivered,
            ])
            ->with(['items', 'payment'])
            ->orderByDesc('created_at')
            ->get();

        // 4. Setting fee (Mendukung tarif custom creator jika disetting di admin)
        $posFeeRates = $profile ? $profile->calculatePosFees(100000) : null;
        $platformFeeRate = $posFeeRates ? $posFeeRates['platform_fee_rate'] : (float) Setting::get('platform_fee_rate', 5);
        $adminFeeRate    = $posFeeRates ? $posFeeRates['admin_fee_rate'] : (float) Setting::get('admin_fee_rate', 5);

        // 5. Saldo Deposit Kasir POS
        $posBalance = (float) ($profile->pos_balance ?? 0);

        // 6. Rekap Keuangan Shift Hari Ini (Cash in Drawer, Digital, Fee Deducted)
        $todayGrossSales = (float) $todayOrders->sum('total');
        $todayCashInDrawer = (float) $todayOrders->filter(function($o) {
            $method = strtolower($o->shipping_address['payment_method'] ?? ($o->payment->method ?? ''));
            return $method === 'cash';
        })->sum('total');

        $todayDigitalSales = (float) $todayOrders->filter(function($o) {
            $method = strtolower($o->shipping_address['payment_method'] ?? ($o->payment->method ?? ''));
            return $method !== 'cash';
        })->sum('total');

        $todayFeeDeducted = (float) $todayOrders->filter(function($o) {
            $method = strtolower($o->shipping_address['payment_method'] ?? ($o->payment->method ?? ''));
            return $method === 'cash';
        })->sum(function($o) {
            return (float) ($o->platform_fee ?? 0) + (float) ($o->admin_fee ?? 0);
        });

        // 7. Riwayat Mutasi Saldo Deposit Hari Ini
        $todayMutations = $profile
            ? PosBalanceMutation::where('creator_id', $profile->id)
                ->whereDate('created_at', now()->today())
                ->orderByDesc('created_at')
                ->get()
            : collect();

        // 8. Saldo Toko Siap Ditarik (untuk fitur alihkan saldo langsung ke deposit)
        $balanceData = \App\Services\SellerBalanceService::calculateBalance($seller);
        $availableStoreBalance = (float) ($balanceData['available_balance'] ?? 0);

        // 9. Logo Struk POS Toko
        $posLogoUrl = $profile?->pos_logo ? asset('storage/' . $profile->pos_logo) : null;

        // 10. Pengaturan Alamat & Telepon Toko untuk Struk
        $posStoreAddress = $profile?->pos_store_address ?? $profile?->address ?? '';
        $posStorePhone   = $profile?->pos_store_phone ?? $seller?->phone ?? '';

        // 11. Daftar Staf Kasir Terdaftar
        $cashiers = \App\Models\User::where('parent_seller_id', $seller->id)
            ->orderBy('name')
            ->get();

        // 12. Daftar Pajak & Biaya Tambahan (PPN, Service Fee, dll)
        $posFees = $profile ? $profile->getPosFees() : [];

        // 13. Daftar Diskon / Promo Produk
        $posDiscounts = $profile ? $profile->getPosDiscounts() : [];

        return view('creator.pos.index', compact(
            'products',
            'todayOrders',
            'profile',
            'posLogoUrl',
            'posStoreAddress',
            'posStorePhone',
            'cashiers',
            'currentCashier',
            'isStaff',
            'seller',
            'platformFeeRate',
            'adminFeeRate',
            'posBalance',
            'availableStoreBalance',
            'todayGrossSales',
            'todayCashInDrawer',
            'todayDigitalSales',
            'todayFeeDeducted',
            'todayMutations',
            'posFees',
            'posDiscounts'
        ));
    }

    /**
     * Dipanggil AJAX: Mengambil produk ter-sync dari Link in Bio & Katalog.
     */
    public function products(Request $request)
    {
        $authUser = auth()->user();
        $seller = ($authUser->isPosCashier() && $authUser->parent_seller_id)
            ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser)
            : $authUser;
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

        $authUser = auth()->user();
        $isStaff = $authUser->isPosCashier() && $authUser->parent_seller_id;
        $seller = $isStaff ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser) : $authUser;

        // Tentukan kasir yang bertugas pada transaksi ini
        $cashierId = $authUser->id;
        $cashierName = $authUser->name;
        $cashierShift = $authUser->shift_hours ?? 'Shift Kasir';

        if (!$isStaff && $request->filled('active_cashier_id')) {
            $selectedCashier = \App\Models\User::where('parent_seller_id', $seller->id)->find($request->active_cashier_id);
            if ($selectedCashier) {
                $cashierId = $selectedCashier->id;
                $cashierName = $selectedCashier->name;
                $cashierShift = $selectedCashier->shift_hours ?? 'Shift Kasir';
            }
        }

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
            if ($request->filled('discount_amount')) {
                $discountAmount = min($subtotal, max(0, (float) $request->discount_amount));
            } elseif ($request->discount_type === 'percent') {
                $pct = min(100, max(0, (float) ($request->discount_val ?? 0)));
                $discountAmount = ($subtotal * $pct) / 100;
            } elseif ($request->discount_type === 'nominal') {
                $discountAmount = min($subtotal, max(0, (float) ($request->discount_val ?? 0)));
            }

            // Biaya Layanan Tambahan & Pajak (PPN, Service Fee, dll)
            $serviceFee = max(0, (float) ($request->service_fee ?? 0));
            $feesBreakdown = $request->fees_breakdown ?? [];
            if (is_string($feesBreakdown)) {
                $feesBreakdown = json_decode($feesBreakdown, true) ?: [];
            }
            $discountDetails = $request->discount_details ?? [];
            if (is_string($discountDetails)) {
                $discountDetails = json_decode($discountDetails, true) ?: [];
            }

            // Lock Creator Profile
            $profile = CreatorProfile::where('user_id', $seller->id)->lockForUpdate()->first();

            // System Fee Calculation (Platform Fee & Admin Fee — mendukung special fee creator)
            $feeCalc = $profile ? $profile->calculatePosFees($subtotal) : [
                'platform_fee' => ($subtotal * (float) Setting::get('platform_fee_rate', 5)) / 100,
                'admin_fee'    => ($subtotal * (float) Setting::get('admin_fee_rate', 5)) / 100,
            ];
            $platformFee = $feeCalc['platform_fee'];
            $adminFee    = $feeCalc['admin_fee'];
            $totalFee    = $platformFee + $adminFee;

            $total = max(0, $subtotal - $discountAmount + $serviceFee + $platformFee + $adminFee);

            $paymentMethod = $request->payment_method;
            $isPaid = in_array($paymentMethod, ['cash', 'transfer']);
            $customerName = trim($request->customer_name ?? '') ?: 'Pelanggan Umum';

            // Validasi Saldo Deposit Kasir POS jika metode pembayaran CASH
            if ($paymentMethod === 'cash' && $totalFee > 0) {
                $currentBalance = (float) ($profile->pos_balance ?? 0);
                if ($currentBalance < $totalFee) {
                    DB::rollBack();
                    return response()->json([
                        'success'              => false,
                        'insufficient_deposit' => true,
                        'required_fee'         => $totalFee,
                        'current_balance'      => $currentBalance,
                        'shortage'             => $totalFee - $currentBalance,
                        'message'              => 'Saldo deposit POS Anda tidak mencukupi untuk biaya transaksi (Dibutuhkan: Rp ' . number_format($totalFee, 0, ',', '.') . ', Saldo Anda: Rp ' . number_format($currentBalance, 0, ',', '.') . '). Silakan isi saldo deposit terlebih dahulu.',
                    ], 422);
                }
            }

            // Create Order
            $order = Order::create([
                'order_number'    => Order::generateOrderNumber(),
                'user_id'         => $seller->id,
                'cashier_id'      => $cashierId,
                'cashier_name'    => $cashierName,
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
                    'name'             => $customerName,
                    'receiver_name'    => $customerName,
                    'email'            => $request->customer_email,
                    'phone'            => $request->customer_phone,
                    'is_pos'           => true,
                    'payment_method'   => $paymentMethod,
                    'cash_paid'        => (float) ($request->cash_paid ?? 0),
                    'cash_change'      => (float) ($request->cash_change ?? 0),
                    'service_fee'      => $serviceFee,
                    'fees_breakdown'   => $feesBreakdown,
                    'discount_details' => $discountDetails,
                    'cashier_id'       => $cashierId,
                    'cashier_name'     => $cashierName,
                    'cashier_shift'    => $cashierShift,
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

            // Potong Saldo Deposit Kasir POS jika pembayaran CASH & catat mutasi ledger
            if ($paymentMethod === 'cash' && $totalFee > 0 && $profile) {
                $balanceBefore = (float) $profile->pos_balance;
                $balanceAfter  = max(0, $balanceBefore - $totalFee);
                $profile->update(['pos_balance' => $balanceAfter]);

                PosBalanceMutation::create([
                    'creator_id'       => $profile->id,
                    'type'             => 'fee_deduction',
                    'amount'           => -$totalFee,
                    'balance_before'   => $balanceBefore,
                    'balance_after'    => $balanceAfter,
                    'order_id'         => $order->id,
                    'reference_number' => $order->order_number,
                    'notes'            => 'Potongan Fee Kasir Transaksi Tunai ' . $order->order_number,
                ]);
            }

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

            // Kirim E-Receipt otomatis HANYA untuk transaksi tunai/transfer yang langsung lunas
            // Untuk QRIS, email akan dikirim otomatis melalui webhook Midtrans setelah pembayaran berhasil
            if ($isPaid && !empty($request->customer_email)) {
                $this->dispatchReceiptEmail($order, $request->customer_email);
            }

            $order->load(['items', 'payment']);

            return response()->json([
                'success'     => true,
                'message'     => 'Transaksi POS berhasil diproses',
                'order'       => $order,
                'snap_token'  => $snapToken,
                'pos_balance' => $profile ? (float) $profile->fresh()->pos_balance : 0,
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
     * Batalkan Order POS yang masih pending (QRIS modal ditutup tanpa bayar).
     * Mengembalikan stok produk dan membatalkan order.
     */
    public function cancelPending(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $seller = auth()->user();

        try {
            DB::beginTransaction();

            $order = Order::where('id', $request->order_id)
                ->where('source', 'pos')
                ->where('user_id', $seller->id)
                ->where('status', OrderStatus::Pending)
                ->with(['items.product'])
                ->lockForUpdate()
                ->first();

            if (!$order) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak ditemukan atau sudah diproses.',
                ], 404);
            }

            // Kembalikan stok produk
            foreach ($order->items as $item) {
                if ($item->product && $item->product->stock !== null) {
                    $item->product->increment('stock', $item->qty);
                    $item->product->decrement('sold_count', $item->qty);
                }
            }

            // Batalkan order dan payment
            $order->update(['status' => OrderStatus::Cancelled]);
            if ($order->payment) {
                $order->payment->update(['status' => \App\Enums\PaymentStatus::Failed]);
            }

            DB::commit();

            Log::info('POS Order dibatalkan (Midtrans ditutup)', [
                'order_id'     => $order->id,
                'order_number' => $order->order_number,
                'seller_id'    => $seller->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order POS berhasil dibatalkan.',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('POS Cancel Pending Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper privat kirim HTML email E-Receipt.
     */
    private function dispatchReceiptEmail(Order $order, string $email): void
    {
        try {
            $order->loadMissing(['items', 'payment']);
            $profile = CreatorProfile::where('user_id', $order->user_id)->first();
            $storeName = $profile->store_name ?? $order->user->name ?? 'buyle.id Store';

            Mail::send('emails.pos_receipt', [
                'order'     => $order,
                'profile'   => $profile,
                'storeName' => $storeName,
                'subject'   => 'Struk Pembayaran - ' . $storeName . ' (' . $order->order_number . ')',
            ], function ($message) use ($email, $order, $storeName) {
                $message->to($email)
                    ->subject('Struk Pembayaran - ' . $storeName . ' (' . $order->order_number . ')');
            });
            Log::info("POS E-Receipt sent to {$email} for order {$order->order_number}");
        } catch (\Throwable $e) {
            Log::error("Failed to send POS E-Receipt to {$email}: " . $e->getMessage());
        }
    }

    /**
     * Top Up Saldo Deposit Kasir POS via Midtrans QRIS/Snap.
     */
    public function topupDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:50000000',
        ]);

        $seller  = auth()->user();
        $profile = CreatorProfile::where('user_id', $seller->id)->firstOrFail();
        $amount  = (float) $request->amount;

        $deposit = PosDeposit::create([
            'creator_id'     => $profile->id,
            'deposit_number' => PosDeposit::generateDepositNumber(),
            'amount'         => $amount,
            'payment_method' => 'qris',
            'status'         => 'pending',
        ]);

        $midtransService = app(MidtransService::class);
        $snapToken       = $midtransService->createPosDepositSnapToken($deposit, $seller);

        if (!$snapToken) {
            $deposit->update(['status' => 'failed']);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat tagihan pembayaran Midtrans. Silakan coba lagi.',
            ], 500);
        }

        $deposit->update(['snap_token' => $snapToken]);

        return response()->json([
            'success'        => true,
            'snap_token'     => $snapToken,
            'deposit_number' => $deposit->deposit_number,
            'amount'         => $amount,
            'message'        => 'Tagihan deposit berhasil dibuat.',
        ]);
    }

    /**
     * Cek status pembayaran deposit POS secara realtime.
     */
    public function checkDeposit(Request $request)
    {
        $request->validate([
            'deposit_number' => 'required|string',
        ]);

        $seller  = auth()->user();
        $profile = CreatorProfile::where('user_id', $seller->id)->firstOrFail();
        $deposit = PosDeposit::where('creator_id', $profile->id)
            ->where('deposit_number', $request->deposit_number)
            ->first();

        if (!$deposit) {
            return response()->json([
                'success' => false,
                'message' => 'Data deposit tidak ditemukan.',
            ], 404);
        }

        if ($deposit->status === 'success') {
            return response()->json([
                'success'     => true,
                'is_paid'     => true,
                'pos_balance' => (float) $profile->pos_balance,
                'message'     => 'Deposit berhasil terbayar.',
            ]);
        }

        $midtransService = app(MidtransService::class);
        $status          = $midtransService->getTransactionStatus($deposit->deposit_number);

        $isPaid = false;
        if ($status) {
            $txStatus = $status->transaction_status ?? '';
            $fraud    = $status->fraud_status ?? '';
            if (($txStatus === 'capture' && $fraud === 'accept') || $txStatus === 'settlement') {
                $isPaid = true;
            }
        }

        if ($isPaid) {
            DB::transaction(function () use ($deposit, $profile, $status) {
                $lockedDeposit = PosDeposit::where('id', $deposit->id)->lockForUpdate()->first();
                if (!$lockedDeposit || $lockedDeposit->status === 'success') {
                    return;
                }

                $lockedProfile = CreatorProfile::where('id', $profile->id)->lockForUpdate()->first();
                $balanceBefore = (float) $lockedProfile->pos_balance;
                $amount        = (float) $lockedDeposit->amount;
                $balanceAfter  = $balanceBefore + $amount;

                $lockedProfile->update(['pos_balance' => $balanceAfter]);

                $lockedDeposit->update([
                    'status'         => 'success',
                    'paid_at'        => now(),
                    'payment_method' => $status->payment_type ?? 'qris',
                ]);

                PosBalanceMutation::create([
                    'creator_id'       => $lockedProfile->id,
                    'type'             => 'topup',
                    'amount'           => $amount,
                    'balance_before'   => $balanceBefore,
                    'balance_after'    => $balanceAfter,
                    'reference_number' => $lockedDeposit->deposit_number,
                    'notes'            => 'Top Up Saldo Deposit POS (' . ($status->payment_type ?? 'QRIS') . ')',
                ]);
            });

            return response()->json([
                'success'     => true,
                'is_paid'     => true,
                'pos_balance' => (float) $profile->fresh()->pos_balance,
                'message'     => 'Pembayaran deposit berhasil diverifikasi.',
            ]);
        }

        return response()->json([
            'success'     => true,
            'is_paid'     => false,
            'pos_balance' => (float) $profile->pos_balance,
            'message'     => 'Menunggu verifikasi pembayaran.',
        ]);
    }

    /**
     * Rekap Laporan Closing Shift Kasir POS (Struk Closing & Neraca Harian).
     */
    public function closingReport(Request $request)
    {
        $seller  = auth()->user();
        $profile = CreatorProfile::where('user_id', $seller->id)->firstOrFail();
        $dateStr = $request->query('date', now()->toDateString());
        $date    = Carbon::parse($dateStr);

        $orders = Order::where('source', 'pos')
            ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
            ->whereDate('created_at', $date)
            ->with(['items', 'payment'])
            ->orderBy('created_at', 'asc')
            ->get();

        $totalTransactions = $orders->count();
        $totalGrossSales   = (float) $orders->sum('total');
        $totalDiscount     = (float) $orders->sum('discount');

        $cashOrders = $orders->filter(function ($o) {
            $m = strtolower($o->shipping_address['payment_method'] ?? ($o->payment->method ?? ''));
            return $m === 'cash';
        });
        $totalCash = (float) $cashOrders->sum('total');

        $qrisOrders = $orders->filter(function ($o) {
            $m = strtolower($o->shipping_address['payment_method'] ?? ($o->payment->method ?? ''));
            return $m === 'qris';
        });
        $totalQris = (float) $qrisOrders->sum('total');

        $transferOrders = $orders->filter(function ($o) {
            $m = strtolower($o->shipping_address['payment_method'] ?? ($o->payment->method ?? ''));
            return in_array($m, ['transfer', 'bank_transfer']);
        });
        $totalTransfer = (float) $transferOrders->sum('total');

        $totalDigital = $totalQris + $totalTransfer;

        $totalFeeDeducted = (float) $cashOrders->sum(function ($o) {
            return (float) ($o->platform_fee ?? 0) + (float) ($o->admin_fee ?? 0);
        });

        // Mutasi saldo deposit pada tanggal ini
        $mutations = PosBalanceMutation::where('creator_id', $profile->id)
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'asc')
            ->get();

        $totalTopupOnDate = (float) $mutations->where('type', 'topup')->sum('amount');
        $totalFeeOnDate   = (float) abs($mutations->where('type', 'fee_deduction')->sum('amount'));

        return response()->json([
            'success'            => true,
            'date'               => $date->format('d/m/Y'),
            'date_full'          => $date->translatedFormat('d F Y'),
            'store_name'         => $profile->store_name ?? $seller->name,
            'cashier_name'       => $seller->name,
            'pos_balance'        => (float) $profile->pos_balance,
            'total_transactions' => $totalTransactions,
            'total_gross_sales'  => $totalGrossSales,
            'total_cash'         => $totalCash,
            'total_qris'         => $totalQris,
            'total_transfer'     => $totalTransfer,
            'total_digital'      => $totalDigital,
            'total_discount'     => $totalDiscount,
            'total_fee_deducted' => $totalFeeDeducted,
            'total_topup'        => $totalTopupOnDate,
            'mutations'          => $mutations,
            'orders'             => $orders->map(function ($o) {
                return [
                    'order_number' => $o->order_number,
                    'time'         => $o->created_at->format('H:i'),
                    'customer'     => $o->shipping_address['name'] ?? 'Pelanggan Umum',
                    'method'       => strtoupper($o->shipping_address['payment_method'] ?? ($o->payment->method ?? 'CASH')),
                    'total'        => (float) $o->total,
                    'fee'          => (float) (($o->platform_fee ?? 0) + ($o->admin_fee ?? 0)),
                ];
            }),
        ]);
    }

    /**
     * Polling AJAX Real-time: Cek status pembayaran order POS (misal QRIS / Non-tunai).
     * Jika Midtrans sudah settlement tapi webhook delay, otomatis sync status lunas & kirim E-Receipt.
     */
    public function orderStatus(int $id)
    {
        $seller = auth()->user();
        $order = Order::where('id', $id)
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

        $statusStr = is_object($order->status) ? $order->status->value : (string) $order->status;
        $isPaid = in_array($statusStr, ['confirmed', 'processing', 'completed', 'delivered']);

        // Jika masih pending dan ada payment Midtrans/QRIS, cek langsung ke Midtrans API
        if (!$isPaid && $statusStr === 'pending' && !empty($order->order_number)) {
            try {
                $midtransService = app(MidtransService::class);
                $mtStatus = $midtransService->getTransactionStatus($order->order_number);
                if ($mtStatus && isset($mtStatus->transaction_status)) {
                    $tStatus = $mtStatus->transaction_status;
                    if (in_array($tStatus, ['settlement', 'capture'])) {
                        $order->update(['status' => OrderStatus::Confirmed]);
                        if ($order->payment) {
                            $order->payment->update([
                                'status'  => PaymentStatus::Success,
                                'paid_at' => now(),
                            ]);
                        }
                        $isPaid = true;
                        $statusStr = 'confirmed';

                        // Kirim E-Receipt jika email pelanggan diisi
                        $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
                        if (!empty($addr['email'])) {
                            $this->dispatchReceiptEmail($order, $addr['email']);
                        }
                    } elseif (in_array($tStatus, ['cancel', 'deny', 'expire'])) {
                        $order->update(['status' => OrderStatus::Cancelled]);
                        if ($order->payment) {
                            $order->payment->update(['status' => PaymentStatus::Failed]);
                        }
                        $statusStr = 'cancelled';
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Midtrans real-time check error: ' . $e->getMessage());
            }
        }

        $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);

        return response()->json([
            'success'        => true,
            'status'         => $statusStr,
            'is_paid'        => $isPaid,
            'payment_status' => $order->payment?->status ? (is_object($order->payment->status) ? $order->payment->status->value : (string) $order->payment->status) : null,
            'customer_email' => $addr['email'] ?? null,
            'order'          => $order->fresh(['items', 'payment']),
        ]);
    }

    /**
     * Upload Logo Toko Struk POS.
     * Jika diupload, nama toko teks pada struk akan digantikan logo ini.
     */
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg,svg,webp|max:3072',
        ]);

        $authUser = auth()->user();
        $seller = ($authUser->isPosCashier() && $authUser->parent_seller_id)
            ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser)
            : $authUser;
        $profile = CreatorProfile::where('user_id', $seller->id)->firstOrFail();

        if ($request->hasFile('logo')) {
            if ($profile->pos_logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($profile->pos_logo);
            }

            $path = $request->file('logo')->store('pos_logos', 'public');
            $profile->update(['pos_logo' => $path]);

            return response()->json([
                'success'  => true,
                'message'  => 'Logo struk kasir berhasil disimpan!',
                'logo_url' => asset('storage/' . $path),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File logo tidak valid.',
        ], 422);
    }

    /**
     * Hapus Logo Toko Struk POS (kembali ke nama teks toko).
     */
    public function removeLogo()
    {
        $authUser = auth()->user();
        $seller = ($authUser->isPosCashier() && $authUser->parent_seller_id)
            ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser)
            : $authUser;
        $profile = CreatorProfile::where('user_id', $seller->id)->firstOrFail();

        if ($profile->pos_logo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($profile->pos_logo);
            $profile->update(['pos_logo' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logo struk dihapus. Struk kini kembali menggunakan nama toko.',
        ]);
    }

    /**
     * Simpan Pengaturan Profil Toko untuk Struk POS (Alamat, No Telp, Nama Toko).
     */
    public function updateSettings(Request $request)
    {
        $authUser = auth()->user();
        $seller = ($authUser->isPosCashier() && $authUser->parent_seller_id)
            ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser)
            : $authUser;
        $profile = CreatorProfile::where('user_id', $seller->id)->firstOrFail();

        $request->validate([
            'store_name'        => 'nullable|string|max:100',
            'pos_store_address' => 'nullable|string|max:500',
            'pos_store_phone'   => 'nullable|string|max:50',
            'pos_fees'          => 'nullable',
            'pos_discounts'     => 'nullable',
        ]);

        $updateData = [
            'pos_store_address' => $request->pos_store_address,
            'pos_store_phone'   => $request->pos_store_phone,
        ];
        if ($request->filled('store_name')) {
            $updateData['store_name'] = trim($request->store_name);
        }

        if ($request->has('pos_fees')) {
            $fees = $request->input('pos_fees');
            if (is_string($fees)) {
                $fees = json_decode($fees, true) ?: [];
            }
            $updateData['pos_fees'] = $fees;
            $bioConfig = $profile->bio_config ?? [];
            $bioConfig['pos_fees'] = $fees;
            $updateData['bio_config'] = $bioConfig;
        }

        if ($request->has('pos_discounts')) {
            $discounts = $request->input('pos_discounts');
            if (is_string($discounts)) {
                $discounts = json_decode($discounts, true) ?: [];
            }
            $updateData['pos_discounts'] = $discounts;
            $bioConfig = $updateData['bio_config'] ?? ($profile->bio_config ?? []);
            $bioConfig['pos_discounts'] = $discounts;
            $updateData['bio_config'] = $bioConfig;
        }

        $profile->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan POS berhasil disimpan!',
            'data'    => [
                'store_name'        => $profile->store_name,
                'pos_store_address' => $profile->pos_store_address,
                'pos_store_phone'   => $profile->pos_store_phone,
                'pos_fees'          => $profile->getPosFees(),
                'pos_discounts'     => $profile->getPosDiscounts(),
            ]
        ]);
    }

    /**
     * Ambil data staf kasir untuk toko ini.
     */
    public function getCashiers()
    {
        $authUser = auth()->user();
        $seller = ($authUser->isPosCashier() && $authUser->parent_seller_id)
            ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser)
            : $authUser;

        $cashiers = \App\Models\User::where('parent_seller_id', $seller->id)
            ->orderBy('name')
            ->get(['id', 'name', 'username', 'email', 'shift_hours', 'cashier_role_title', 'is_active', 'created_at']);

        return response()->json([
            'success'  => true,
            'cashiers' => $cashiers
        ]);
    }

    /**
     * Tambah atau edit data staf kasir (menggunakan Email untuk login).
     */
    public function saveCashier(Request $request)
    {
        $authUser = auth()->user();
        $seller = ($authUser->isPosCashier() && $authUser->parent_seller_id)
            ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser)
            : $authUser;

        $cashierId = $request->input('id');

        $request->validate([
            'name'               => 'required|string|max:100',
            'email'              => 'required|email|max:100|unique:users,email,' . ($cashierId ?: 'NULL') . ',id',
            'password'           => $cashierId ? 'nullable|string|min:6' : 'required|string|min:6',
            'shift_hours'        => 'nullable|string|max:100',
            'cashier_role_title' => 'nullable|string|max:100',
            'is_active'          => 'nullable',
        ], [
            'name.required'      => 'Nama staf kasir wajib diisi.',
            'email.required'     => 'Email login staf kasir wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'password.required'  => 'Password wajib diisi untuk staf kasir baru.',
            'password.min'       => 'Password minimal 6 karakter.',
        ]);

        $email = strtolower(trim($request->email));
        $usernameBase = explode('@', $email)[0];
        $username = \Illuminate\Support\Str::slug($usernameBase);

        if ($cashierId) {
            $cashier = \App\Models\User::where('parent_seller_id', $seller->id)->findOrFail($cashierId);
            $cashier->name = $request->name;
            $cashier->email = $email;
            if (empty($cashier->username)) {
                $cashier->username = $username;
            }
            $cashier->shift_hours = $request->shift_hours;
            $cashier->cashier_role_title = $request->cashier_role_title ?: 'Kasir';
            if ($request->has('is_active')) {
                $cashier->is_active = $request->boolean('is_active', true);
            }
            if ($request->filled('password')) {
                $cashier->password = \Illuminate\Support\Facades\Hash::make($request->password);
            }
            $cashier->save();
            $msg = 'Data staf kasir ' . $cashier->name . ' berhasil diperbarui!';
        } else {
            $u = $username;
            $counter = 1;
            while (\App\Models\User::where('username', $u)->exists()) {
                $u = $username . $counter++;
            }

            $cashier = \App\Models\User::create([
                'name'               => $request->name,
                'email'              => $email,
                'username'           => $u,
                'password'           => \Illuminate\Support\Facades\Hash::make($request->password),
                'role'               => 'pos_cashier',
                'parent_seller_id'   => $seller->id,
                'shift_hours'        => $request->shift_hours,
                'cashier_role_title' => $request->cashier_role_title ?: 'Kasir',
                'is_active'          => $request->boolean('is_active', true),
            ]);
            $msg = 'Staf kasir baru ' . $cashier->name . ' berhasil didaftarkan!';
        }

        $allCashiers = \App\Models\User::where('parent_seller_id', $seller->id)
            ->orderBy('name')
            ->get(['id', 'name', 'username', 'email', 'shift_hours', 'cashier_role_title', 'is_active', 'created_at']);

        return response()->json([
            'success'  => true,
            'message'  => $msg,
            'cashier'  => $cashier,
            'cashiers' => $allCashiers
        ]);
    }

    /**
     * Hapus staf kasir.
     */
    public function deleteCashier($id)
    {
        $authUser = auth()->user();
        $seller = ($authUser->isPosCashier() && $authUser->parent_seller_id)
            ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser)
            : $authUser;

        $cashier = \App\Models\User::where('parent_seller_id', $seller->id)->findOrFail($id);
        $name = $cashier->name;
        $cashier->delete();

        $allCashiers = \App\Models\User::where('parent_seller_id', $seller->id)
            ->orderBy('name')
            ->get(['id', 'name', 'username', 'email', 'shift_hours', 'cashier_role_title', 'is_active', 'created_at']);

        return response()->json([
            'success'  => true,
            'message'  => 'Akun staf kasir ' . $name . ' berhasil dihapus.',
            'cashiers' => $allCashiers
        ]);
    }

    /**
     * Toggle status aktif/nonaktif staf kasir.
     */
    public function toggleCashier($id)
    {
        $authUser = auth()->user();
        $seller = ($authUser->isPosCashier() && $authUser->parent_seller_id)
            ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser)
            : $authUser;

        $cashier = \App\Models\User::where('parent_seller_id', $seller->id)->findOrFail($id);
        $cashier->is_active = !$cashier->is_active;
        $cashier->save();

        $allCashiers = \App\Models\User::where('parent_seller_id', $seller->id)
            ->orderBy('name')
            ->get(['id', 'name', 'username', 'email', 'shift_hours', 'cashier_role_title', 'is_active', 'created_at']);

        return response()->json([
            'success'   => true,
            'is_active' => $cashier->is_active,
            'message'   => 'Status akun kasir ' . $cashier->name . ' diubah menjadi ' . ($cashier->is_active ? 'AKTIF' : 'NONAKTIF') . '.',
            'cashiers'  => $allCashiers
        ]);
    }
}
