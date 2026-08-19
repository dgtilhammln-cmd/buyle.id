<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ── Show Login Form ──
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('account.overview');
        }
        return view('auth.login');
    }

    // ── Process Login ──
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $role = Auth::user()->role;
            $redirectRoute = route('account.overview'); // default (buyer)
            
            if ($role === 'super_admin' || $role === 'admin') {
                // Set legacy session for admin panel backward compatibility
                session([
                    'admin_logged_in' => true,
                    'admin_id'        => Auth::id(),
                    'admin_name'      => Auth::user()->name,
                    'admin_email'     => Auth::user()->email,
                ]);
                $redirectRoute = route('admin.dashboard');
            } elseif ($role === 'seller') {
                $redirectRoute = route('creator.dashboard');
            }
            
            return redirect()->intended($redirectRoute)
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withInput()->withErrors([
            'email' => 'Email atau kata sandi tidak sesuai.',
        ]);
    }

    // ── Show Register Form ──
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('account.overview');
        }
        return view('auth.register');
    }

    // ── Process Register ──
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Generate unique username from name
        $base     = Str::slug($request->name, '.');
        $username = $base;
        $i        = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'username' => $username,
            'password' => Hash::make($request->password),
            'role'     => 'buyer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('account.overview')
            ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }

    // ── Logout ──
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Berhasil keluar.');
    }

    // ── Google OAuth Redirect ──
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // ── Google OAuth Callback ──
    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Login Google gagal. Silakan coba lagi.']);
        }

        // Find or create user
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id if not yet saved
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        } else {
            // Generate unique username
            $base     = Str::slug($googleUser->getName(), '.');
            $username = $base;
            $i        = 1;
            while (User::where('username', $username)->exists()) {
                $username = $base . $i++;
            }

            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'avatar'    => $googleUser->getAvatar(),
                'google_id' => $googleUser->getId(),
                'username'  => $username,
                'password'  => Hash::make(Str::random(24)),
                'role'      => 'buyer',
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->route('account.overview')
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    // ── Magic Login (1-click dari email notifikasi) ──
    public function magicLogin(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
        ]);

        $record = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Tautan login tidak valid atau sudah kedaluwarsa.']);
        }

        // Token expired setelah 24 jam
        if (\Carbon\Carbon::parse($record->created_at)->addHours(24)->isPast()) {
            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('login')
                ->withErrors(['email' => 'Tautan login sudah kedaluwarsa. Silakan login manual.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        // Hapus token setelah dipakai (one-time use)
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('buyer.dashboard')
            ->with('success', '✅ Selamat datang di buyle.id! Akses produk Anda ada di sini.');
    }
}
