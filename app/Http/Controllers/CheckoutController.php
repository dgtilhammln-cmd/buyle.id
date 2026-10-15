<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Requests\CheckoutRequest;
use App\Models\Courier;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected CheckoutService $checkoutService;

    public function __construct(CartService $cartService, CheckoutService $checkoutService)
    {
        $this->cartService = $cartService;
        $this->checkoutService = $checkoutService;
    }

    /**
     * Tampilkan halaman checkout.
     */
    public function index()
    {
        $summary = $this->cartService->getSummary();
        
        if ($summary['items']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $user = auth()->user();
        $addresses = $user ? $user->addresses : collect();
        $couriers = Courier::where('is_active', true)->orderBy('order')->get();
        
        return view('ecommerce.checkout', compact('summary', 'addresses', 'couriers'));
    }

    /**
     * Proses pengiriman form checkout.
     */
    public function store(CheckoutRequest $request)
    {
        $data = $request->validated();

        try {
            if (auth()->guest()) {
                // Guest checkout: JANGAN buat akun, cukup simpan email di order
                $user = null;
            } else {
                $user = auth()->user();
            }

            $order = $this->checkoutService->processCheckout($data, $user);

            // Simpan order_number di session agar guest bisa akses halaman finish
            session(['guest_order_number' => $order->order_number]);

            // Redirect ke halaman finish
            return redirect()->route('checkout.finish', $order->order_number);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Halaman setelah checkout (menampilkan tombol bayar Midtrans).
     */
    public function finish(string $orderNumber)
    {
        // Temukan order
        $orderQuery = Order::with(['payment', 'items.product.seller', 'items.variantValue.option', 'user'])
                          ->where('order_number', $orderNumber);

        if (auth()->check()) {
            // User login: cari by user_id ATAU customer_email (jaga-jaga)
            $orderQuery->where(function($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('customer_email', auth()->user()->email);
            });
        } else {
            // Guest: izinkan jika order_number tersimpan di session
            $guestOrderNumber = session('guest_order_number');
            if ($guestOrderNumber !== $orderNumber) {
                return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
            }
        }

        $order = $orderQuery->firstOrFail();

        // Jika sudah gratis / confirmed, langsung tampilkan tanpa cek Midtrans
        $isFree = ($order->total <= 0 || ($order->payment && $order->payment->gateway === 'free'));

        // Auto-sync status dari Midtrans jika di database belum tercatat Lunas/Success
        $isPaidInDb = ($order->payment && $order->payment->status === PaymentStatus::Success)
            || in_array($order->status, [OrderStatus::Confirmed, OrderStatus::Delivered, OrderStatus::Processing]);

        if (!$isFree && !$isPaidInDb) {
            try {
                $midtrans = app(\App\Services\MidtransService::class);
                $midtransOrderId = 'BUYLE-' . $order->id;
                $status = $midtrans->getTransactionStatus($midtransOrderId);

                if (!$status) {
                    $status = $midtrans->getTransactionStatus($order->order_number);
                }

                if ($status) {
                    $txStatus = is_object($status) ? ($status->transaction_status ?? '') : ($status['transaction_status'] ?? '');
                    $fraudStatus = is_object($status) ? ($status->fraud_status ?? '') : ($status['fraud_status'] ?? '');

                    $isPaid = (
                        ($txStatus === 'capture' && $fraudStatus === 'accept') ||
                        $txStatus === 'settlement'
                    );

                    if ($isPaid) {
                        \DB::transaction(function () use ($order, $status) {
                            if ($order->payment) {
                                $order->payment->update([
                                    'status'                  => PaymentStatus::Success,
                                    'paid_at'                 => now(),
                                    'midtrans_transaction_id' => is_object($status) ? ($status->transaction_id ?? null) : ($status['transaction_id'] ?? null),
                                    'method'                  => is_object($status) ? ($status->payment_type ?? null) : ($status['payment_type'] ?? null),
                                ]);
                            }
                            $order->update([
                                'status' => OrderStatus::Confirmed,
                            ]);
                        });

                        try {
                            $buyerEmail = $order->user?->email ?? $order->customer_email ?? '';
                            $buyerName  = $order->user?->name ?? ($order->shipping_address['receiver_name'] ?? 'Pembeli');
                            $buyerPhone = $order->user?->phone ?? '';
                            \App\Jobs\ProcessSuccessfulOrderJob::dispatchSync(
                                orderId:    $order->id,
                                buyerEmail: $buyerEmail,
                                buyerName:  $buyerName,
                                buyerPhone: $buyerPhone,
                            );
                        } catch (\Throwable $e) {
                            \Log::error("ProcessSuccessfulOrderJob dispatchSync error: " . $e->getMessage());
                        }

                        // Refresh model
                        $order = Order::with(['payment', 'items.product.seller', 'user'])->find($order->id);
                    }
                }
            } catch (\Exception $e) {
                \Log::warning("Auto-sync Midtrans status failed for order {$orderNumber}: " . $e->getMessage());
            }
        }

        return view('ecommerce.finish', compact('order'));
    }

    /**
     * Webhook / Callback dari Midtrans.
     * Route ini tidak menggunakan CSRF middleware.
     */
    public function callback(Request $request)
    {
        $payload = $request->all();

        $handled = $this->checkoutService->handleCallback($payload);

        if ($handled) {
            return response()->json(['status' => 'success', 'message' => 'Callback handled']);
        }

        return response()->json(['status' => 'error', 'message' => 'Callback failed or invalid signature'], 400);
    }
}
