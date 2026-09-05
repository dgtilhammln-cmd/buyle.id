<?php

namespace App\Http\Controllers;

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
            
            if (!auth()->check()) {
                $oldSessionId = $request->session()->getId();

                $user = User::where('email', $data['guest_email'])->first();

                if ($user) {
                    if (!Hash::check($data['guest_password'], $user->password)) {
                        return back()->withInput()->withErrors([
                            'guest_email' => 'Email ini sudah terdaftar. Silakan masukkan kata sandi yang benar atau login terlebih dahulu.'
                        ]);
                    }
                    Auth::login($user, true);
                } else {
                    $base     = Str::slug($data['guest_name'], '.');
                    $username = $base;
                    $i        = 1;
                    while (User::where('username', $username)->exists()) {
                        $username = $base . $i++;
                    }

                    $user = User::create([
                        'name'     => $data['guest_name'],
                        'email'    => $data['guest_email'],
                        'phone'    => $data['guest_phone'],
                        'username' => $username,
                        'password' => Hash::make($data['guest_password']),
                        'role'     => 'buyer',
                    ]);

                    try {
                        $user->notify(new \App\Notifications\WelcomeNotification());
                    } catch (\Throwable $e) {
                        \Log::warning('WelcomeNotification failed on guest checkout: ' . $e->getMessage());
                    }

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
