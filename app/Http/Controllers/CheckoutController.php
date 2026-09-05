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
        $oldSessionId = session()->getId();

        try {
            if (auth()->guest()) {
                $email = $data['guest_email'];
                $user = User::where('email', $email)->first();

                if (!$user) {
                    $password = !empty($data['guest_password']) ? $data['guest_password'] : Str::random(12);
                    $user = User::create([
                        'name'     => $data['guest_name'],
                        'email'    => $email,
                        'phone'    => $data['guest_phone'] ?? null,
                        'password' => Hash::make($password),
                        'role'     => 'buyer',
                    ]);

                    Auth::login($user, true);
                    $user->notify(new \App\Notifications\WelcomeNotification($password));
                } else {
                    Auth::login($user, true);
                }

                $request->session()->regenerate();
                $this->cartService->mergeGuestCart($user->id, $oldSessionId);
            } else {
                $user = auth()->user();
            }

            $order = $this->checkoutService->processCheckout($data, $user);
            
            // Redirect ke halaman finish yang akan menampilkan popup Midtrans
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
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat detail pesanan Anda.');
        }

        $order = Order::with(['payment', 'items.product.seller', 'user'])->where('order_number', $orderNumber)
                      ->where('user_id', auth()->id())
                      ->firstOrFail();

        // Auto-sync status dari Midtrans jika di database belum tercatat Lunas/Success
        $isPaidInDb = ($order->payment && $order->payment->status === PaymentStatus::Success)
            || in_array($order->status, [OrderStatus::Confirmed, OrderStatus::Completed, OrderStatus::Processing]);

        if (!$isPaidInDb) {
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
                            \App\Jobs\ProcessSuccessfulOrderJob::dispatchSync(
                                orderId:    $order->id,
                                buyerEmail: $order->user->email,
                                buyerName:  $order->user->name,
                                buyerPhone: $order->user->phone ?? '',
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
