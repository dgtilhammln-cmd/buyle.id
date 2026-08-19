<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Courier;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
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
        $seo = ['title' => 'Checkout', 'robots' => 'noindex, nofollow', 'canonical' => route('checkout.index')];
        return view('ecommerce.checkout', compact('summary', 'user', 'addresses', 'couriers', 'seo'));
    }

    /**
     * Proses data checkout dan minta token Midtrans.
     */
    public function store(CheckoutRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Auto Register if Guest
            if (!auth()->check()) {
                // Tangkap session ID sebelum login (karena auth()->login bisa mengubah/regenerate session ID)
                $guestSessionId = \Illuminate\Support\Facades\Session::getId();

                $user = \App\Models\User::create([
                    'name'     => $data['guest_name'],
                    'email'    => $data['guest_email'],
                    'phone'    => $data['guest_phone'],
                    'password' => bcrypt($data['guest_password']),
                    'role'     => 'buyer', // Assign buyer role
                ]);
                auth()->login($user);
                
                // Merge guest cart to the new user
                $this->cartService->mergeGuestCart($user->id, $guestSessionId);
            }

            $order = $this->checkoutService->processCheckout($data, auth()->user());
            
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

        $order = Order::with('payment')->where('order_number', $orderNumber)
                      ->where('user_id', auth()->id())
                      ->firstOrFail();

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
