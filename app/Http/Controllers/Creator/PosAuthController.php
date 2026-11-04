<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PosAuthController extends Controller
{
    /**
     * Tampilkan halaman Login Khusus PWA POS Buyle.
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Jika sudah login sebagai seller atau staf kasir, langsung arahkan ke POS
            if ($user->role === 'seller' || $user->role === 'pos_cashier' || $user->isPosCashier()) {
                return redirect()->route('creator.pos.index');
            }
        }

        return view('creator.pos.login');
    }

    /**
     * Proses Login Khusus PWA POS.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|min:4',
        ], [
            'email.required'    => 'Email atau Username kasir wajib diisi.',
            'password.required' => 'Password / PIN wajib diisi.',
            'password.min'      => 'Password minimal 4 karakter.',
        ]);

        $loginInput  = trim($request->input('email'));
        $fieldType   = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$fieldType => $loginInput, 'password' => $request->password];

        // Sesi selalu diingat (remember = true) agar kasir tidak ter-logout saat PWA ditutup
        $remember = $request->boolean('remember', true);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->is_active === false || $user->is_active === 0) {
                Auth::logout();
                return back()->withInput()->withErrors([
                    'email' => 'Akun kasir Anda saat ini dinonaktifkan oleh pemilik toko.',
                ]);
            }

            // Izinkan seller, admin, atau pos_cashier
            if ($user->role === 'seller' || $user->role === 'pos_cashier' || $user->isPosCashier() || $user->role === 'super_admin' || $user->role === 'admin') {
                return redirect()->intended(route('creator.pos.index'));
            }

            // Jika role buyer biasa, tetap izinkan masuk ke POS jika toko aktif
            return redirect()->route('creator.pos.index');
        }

        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Email/Username atau Kata Sandi kasir tidak sesuai.',
        ]);
    }
}

