<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    // Constructor removed - middleware handled in routes/web.php

    // ── Overview / Dashboard ──
    public function overview()
    {
        $user           = Auth::user();
        $totalOrders    = $user->orders()->count();
        $activeOrders   = $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count();
        $totalSpent     = $user->orders()->where('status', '!=', 'cancelled')->sum('grand_total');
        $totalAddresses = $user->addresses()->count();
        $recentOrders   = $user->orders()->latest()->limit(5)->get();

        return view('account.overview', compact(
            'user', 'totalOrders', 'activeOrders', 'totalSpent', 'totalAddresses', 'recentOrders'
        ));
    }

    // ── Cart ──
    public function cart()
    {
        $user    = Auth::user();
        $cart    = app(CartService::class);
        $summary = $cart->getSummary();
        return view('account.cart', compact('user', 'summary'));
    }

    // ── Wishlist ──
    public function wishlist()
    {
        $user      = Auth::user();
        $wishlists = $user->wishlists()->with('product')->latest()->paginate(12);
        return view('account.wishlist', compact('user', 'wishlists'));
    }

    public function toggleWishlist(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:services,id']);
        $user = Auth::user();
        
        $wishlist = $user->wishlists()->where('product_id', $request->product_id)->first();
        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Produk dihapus dari wishlist.');
        } else {
            $user->wishlists()->create(['product_id' => $request->product_id]);
            return redirect()->route('account.wishlist')->with('success', 'Produk ditambahkan ke wishlist.');
        }
    }

    // ── Orders ──
    public function orders()
    {
        $user   = Auth::user();
        $orders = $user->orders()->with(['items.product', 'payment', 'shipment'])->latest()->get();
        return view('account.orders', compact('user', 'orders'));
    }

    // ── Order Detail (with tracking) ──
    public function showOrder(\App\Models\Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->load(['items.product', 'payment', 'shipment', 'couponUsage']);

        // If there is a tracking number, fetch live tracking from RajaOngkir/Komerce
        $tracking = null;
        if ($order->shipment && $order->shipment->tracking_number && $order->shipment->courier_name) {
            $mode = \App\Models\Setting::get('komerce_mode', 'sandbox');
            $isLive = ($mode === 'live');
            
            if ($isLive) {
                $apiKey  = \App\Models\Setting::get('shipping_delivery_api_key') ?: \App\Models\Setting::get('rajaongkir_api_key');
            } else {
                $apiKey  = \App\Models\Setting::get('shipping_delivery_api_key_sandbox') ?: \App\Models\Setting::get('rajaongkir_api_key_sandbox');
            }

            if ($apiKey) {
                try {
                    // Map courier name to API code
                    $rawCourier = strtolower(trim($order->shipment->courier_name));
                    $courierCode = match(true) {
                        str_contains($rawCourier, 'j&t') || str_contains($rawCourier, 'jnt') => 'jnt',
                        str_contains($rawCourier, 'jne') => 'jne',
                        str_contains($rawCourier, 'sicepat') => 'sicepat',
                        str_contains($rawCourier, 'anteraja') => 'anteraja',
                        str_contains($rawCourier, 'pos') => 'pos',
                        str_contains($rawCourier, 'tiki') => 'tiki',
                        str_contains($rawCourier, 'ninja') => 'ninjaxpress',
                        str_contains($rawCourier, 'sap') => 'sap',
                        str_contains($rawCourier, 'spx') || str_contains($rawCourier, 'shopee') => 'spx',
                        str_contains($rawCourier, 'lion') => 'lion',
                        str_contains($rawCourier, 'ide') || str_contains($rawCourier, 'idx') => 'ide',
                        default => $rawCourier,
                    };

                    $mode = \App\Models\Setting::get('komerce_mode', 'sandbox');
                    $isLive = ($mode === 'live');

                    if ($isLive) {
                        $baseUrl = 'https://api.collaborator.komerce.id';
                        $trackUrl = $baseUrl . '/order/api/v1/orders/history-airway-bill';
                        $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                            ->timeout(20)
                            ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                            ->withHeaders(['x-api-key' => $apiKey, 'Accept' => 'application/json', 'User-Agent' => 'Mozilla/5.0'])
                            ->get($trackUrl, [
                                'awb' => $order->shipment->tracking_number,
                                'courier' => $courierCode,
                            ]);
                    } else {
                        $baseUrl = 'https://rajaongkir.komerce.id/api/v1';
                        $trackUrl = $baseUrl . '/track/waybill?awb=' . urlencode($order->shipment->tracking_number) . '&courier=' . urlencode($courierCode);
                        $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                            ->timeout(20)
                            ->withOptions(['curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]])
                            ->withHeaders(['key' => $apiKey, 'Accept' => 'application/json', 'Content-Type' => 'application/x-www-form-urlencoded', 'User-Agent' => 'Mozilla/5.0'])
                            ->post($trackUrl);
                    }
                    $json = $response->json();
                    $meta = $json['meta'] ?? [];
                    $data = $json['data'] ?? null;
                    if ($data) {
                        $manifest = $data['manifest'] ?? $data['history'] ?? [];
                        $delivery = $data['delivery_status'] ?? [];
                        $summary  = $data['summary'] ?? $data;
                        $tracking = [
                            'status'   => $delivery['status'] ?? ($summary['status'] ?? ''),
                            'manifest' => array_map(fn($m) => [
                                'date'  => ($m['manifest_date'] ?? $m['date'] ?? '') . ' ' . ($m['manifest_time'] ?? $m['time'] ?? ''),
                                'desc'  => trim($m['manifest_description'] ?? $m['description'] ?? '-'),
                                'city'  => $m['city_name'] ?? $m['location'] ?? '',
                            ], $manifest),
                        ];
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Order tracking fetch failed: ' . $e->getMessage());
                }
            }
        }

        return view('account.order-detail', compact('order', 'tracking'));
    }

    public function completeOrder(\App\Models\Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status->value !== 'shipped') {
            return back()->with('error', 'Hanya pesanan yang sedang dikirim yang dapat diselesaikan.');
        }

        $order->update(['status' => \App\Enums\OrderStatus::Delivered]);

        return back()->with('success', 'Pesanan telah dikonfirmasi selesai. Terima kasih telah berbelanja!');
    }


    // ── Addresses ──
    public function addresses()
    {
        $user      = Auth::user();
        $addresses = $user->addresses()->latest()->get();
        return view('account.addresses', compact('user', 'addresses'));
    }

    // ── Store Address ──
    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'label'         => 'required|string|max:50',
            'receiver_name' => 'required|string|max:100',
            'phone'         => 'required|string|max:20',
            'province'      => 'required|string|max:100',
            'city'          => 'required|string|max:100',
            'district'      => 'required|string|max:100',
            'village'       => 'nullable|string|max:100',
            'postal_code'   => 'required|string|max:10',
            'full_address'  => 'required|string|max:500',
        ]);

        $user = Auth::user();
        $data['user_id'] = $user->id;

        if ($user->addresses()->count() === 0) {
            $data['is_default'] = true;
        }

        Address::create($data);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    // ── Update Address ──
    public function updateAddress(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'label'         => 'required|string|max:50',
            'receiver_name' => 'required|string|max:100',
            'phone'         => 'required|string|max:20',
            'province'      => 'required|string|max:100',
            'city'          => 'required|string|max:100',
            'district'      => 'required|string|max:100',
            'village'       => 'nullable|string|max:100',
            'postal_code'   => 'required|string|max:10',
            'full_address'  => 'required|string|max:500',
        ]);

        $address->update($data);

        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    // ── Delete Address ──
    public function destroyAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        $address->delete();
        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    // ── Set Default Address ──
    public function setDefaultAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return back()->with('success', 'Alamat utama diperbarui.');
    }

    // ── Profile Page ──
    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    // ── Update Profile ──
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        if ($request->boolean('change_password')) {
            $rules['password']              = 'required|min:6|confirmed';
            $rules['password_confirmation'] = 'required';
        }

        $data = $request->validate($rules, [
            'name.required'      => 'Nama wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'password.min'       => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $file = $request->file('avatar');
            $filename = uniqid('avatar_') . '.webp';
            $path = 'avatars/' . $filename;
            $fullPath = storage_path('app/public/' . $path);
            
            // Ensure directory exists
            if (!file_exists(storage_path('app/public/avatars'))) {
                mkdir(storage_path('app/public/avatars'), 0755, true);
            }

            try {
                // Use native PHP GD to resize & convert to WebP
                $tmpPath = $file->getRealPath();
                $mime = $file->getMimeType();

                $src = match(true) {
                    str_contains($mime, 'jpeg') || str_contains($mime, 'jpg') => imagecreatefromjpeg($tmpPath),
                    str_contains($mime, 'png')  => imagecreatefrompng($tmpPath),
                    str_contains($mime, 'webp') => imagecreatefromwebp($tmpPath),
                    str_contains($mime, 'gif')  => imagecreatefromgif($tmpPath),
                    default => imagecreatefromjpeg($tmpPath),
                };

                $origW = imagesx($src);
                $origH = imagesy($src);
                $maxW  = 400;

                if ($origW > $maxW) {
                    $ratio = $maxW / $origW;
                    $newW  = $maxW;
                    $newH  = (int)($origH * $ratio);
                } else {
                    $newW = $origW;
                    $newH = $origH;
                }

                $dst = imagecreatetruecolor($newW, $newH);
                // Preserve transparency for PNG
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
                imagewebp($dst, $fullPath, 75);
                imagedestroy($src);
                imagedestroy($dst);

                $data['avatar'] = $path;
            } catch (\Exception $e) {
                // Final fallback: plain store
                $data['avatar'] = $file->store('avatars', 'public');
            }
        }

        if ($request->boolean('change_password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password'], $data['password_confirmation']);
        }

        unset($data['change_password']);
        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
