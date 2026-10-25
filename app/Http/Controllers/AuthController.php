<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\OtpVerificationNotification;
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
            $role = Auth::user()->role;
            if ($role === 'super_admin' || $role === 'admin') return redirect()->route('admin.dashboard');
            if ($role === 'seller') return redirect()->route('creator.dashboard');
            return redirect()->route('account.overview');
        }

        $prev = url()->previous();
        if ($prev && !session()->has('url.intended')) {
            if (!Str::contains($prev, ['/login', '/register', '/lupa-password', '/reset-password', '/keluar'])) {
                session()->put('url.intended', $prev);
            }
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

        $oldSessionId = $request->session()->getId();
        $credentials  = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            try {
                app(\App\Services\CartService::class)->mergeGuestCart(Auth::id(), $oldSessionId);
            } catch (\Throwable $e) {
                \Log::warning('Merge guest cart error: ' . $e->getMessage());
            }

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
            $role = Auth::user()->role;
            if ($role === 'super_admin' || $role === 'admin') return redirect()->route('admin.dashboard');
            if ($role === 'seller') return redirect()->route('creator.dashboard');
            return redirect()->route('account.overview');
        }

        $prev = url()->previous();
        if ($prev && !session()->has('url.intended')) {
            if (!Str::contains($prev, ['/login', '/register', '/lupa-password', '/reset-password', '/keluar'])) {
                session()->put('url.intended', $prev);
            }
        }

        return view('auth.register');
    }

    // ── Show Register via Email Form ──
    public function showRegisterEmail()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'super_admin' || $role === 'admin') return redirect()->route('admin.dashboard');
            if ($role === 'seller') return redirect()->route('creator.dashboard');
            return redirect()->route('account.overview');
        }

        $prev = url()->previous();
        if ($prev && !session()->has('url.intended')) {
            if (!Str::contains($prev, ['/login', '/register', '/lupa-password', '/reset-password', '/keluar'])) {
                session()->put('url.intended', $prev);
            }
        }

        return view('auth.register_email');
    }

    // ── Process Register (Step 1: Save Draft & Send OTP) ──
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',        // min 1 huruf kecil
                'regex:/[A-Z]/',        // min 1 huruf besar
                'regex:/[0-9]/',        // min 1 angka
                'regex:/[^A-Za-z0-9]/', // min 1 simbol khusus
            ],
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar. Silakan gunakan email lain atau masuk.',
            'phone.required'     => 'No. WhatsApp wajib diisi.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.regex'     => 'Kata sandi wajib memenuhi standar keamanan: kombinasi huruf besar & kecil, angka, dan simbol.',
        ]);

        $otpCode = (string) rand(100000, 999999);
        $expiresAt = time() + 60; // berlaku 1 menit (60 detik)

        session()->put('pending_register', [
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'password'       => $request->password,
            'old_session_id' => $request->session()->getId(),
            'otp_code'       => $otpCode,
            'otp_expires_at' => $expiresAt,
        ]);

        session()->put('otp_expires_at', $expiresAt);
        session()->put('otp_email', $request->email);

        try {
            \Illuminate\Support\Facades\Notification::route('mail', $request->email)
                ->notify(new OtpVerificationNotification($otpCode, $request->name));
        } catch (\Throwable $e) {
            \Log::error('Kirim OTP Email Gagal: ' . $e->getMessage());
        }

        return redirect()->route('otp.verify')
            ->with('success', 'Kode OTP verifikasi telah dikirimkan ke email ' . $request->email . '. Silakan periksa kotak masuk/spam Anda.');
    }

    // ── Show Verify OTP Form ──
    public function showVerifyOtp()
    {
        if (!session()->has('pending_register')) {
            return redirect()->route('register')->with('error', 'Sesi pendaftaran telah berakhir. Silakan isi kembali formulir pendaftaran.');
        }

        return view('auth.verify-otp');
    }

    // ── Submit OTP Verification & Create User ──
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size'     => 'Kode OTP harus berjumlah 6 digit.',
        ]);

        $pending = session('pending_register');
        if (!$pending) {
            return redirect()->route('register')->with('error', 'Sesi pendaftaran tidak ditemukan. Silakan daftar kembali.');
        }

        // Cek kadaluarsa OTP (1 menit)
        if (time() > $pending['otp_expires_at']) {
            return redirect()->route('otp.verify')->with('error', 'Kode OTP telah kadaluarsa. Silakan klik "Kirim Ulang Kode OTP".');
        }

        // Cek keakuratan OTP
        if (trim($request->otp) !== (string) $pending['otp_code']) {
            return redirect()->route('otp.verify')->with('error', 'Kode OTP yang Anda masukkan salah. Mohon periksa kembali.');
        }

        // OTP Valid -> Buat User di Database
        $base     = Str::slug($pending['name'], '.');
        $username = $base;
        $i        = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }

        $user = User::create([
            'name'     => $pending['name'],
            'email'    => $pending['email'],
            'phone'    => $pending['phone'],
            'username' => $username,
            'password' => Hash::make($pending['password']),
            'role'     => 'buyer',
            'email_verified_at' => now(),
        ]);

        try {
            $user->notify(new \App\Notifications\WelcomeNotification());
        } catch (\Throwable $e) {
            \Log::warning('WelcomeNotification failed: ' . $e->getMessage());
        }

        // Link order guest (yang checkout tanpa akun) ke akun baru ini
        try {
            \App\Models\Order::where('customer_email', $user->email)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
        } catch (\Throwable $e) {
            \Log::warning('Link guest orders on register failed: ' . $e->getMessage());
        }

        $oldSessionId = $pending['old_session_id'] ?? null;
        session()->forget(['pending_register', 'otp_expires_at', 'otp_email']);

        Auth::login($user);
        $request->session()->regenerate();

        if ($oldSessionId) {
            try {
                app(\App\Services\CartService::class)->mergeGuestCart($user->id, $oldSessionId);
            } catch (\Throwable $e) {
                \Log::warning('Merge guest cart on register failed: ' . $e->getMessage());
            }
        }


        $redirectRoute = route('account.profile');
        if ($user->role === 'super_admin' || $user->role === 'admin') {
            $redirectRoute = route('admin.dashboard');
        } elseif ($user->role === 'seller') {
            $redirectRoute = route('creator.dashboard');
        }

        return redirect()->to($redirectRoute)
            ->with('success', 'Verifikasi berhasil! Akun Anda telah aktif. Selamat datang di buyle.id, ' . $user->name . '!');
    }

    // ── AJAX: Send OTP for inline email registration ──
    public function sendOtpAjax(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name'  => 'required|string|max:100',
        ]);

        // Check if email is already taken
        if (\App\Models\User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk.',
            ], 422);
        }

        $otpCode   = (string) rand(100000, 999999);
        $expiresAt = time() + 120; // 2 menit

        // Store in session
        session()->put('ajax_otp', [
            'code'       => $otpCode,
            'email'      => $request->email,
            'expires_at' => $expiresAt,
        ]);

        // Create HMAC token so client can reference this OTP attempt
        $token = hash_hmac('sha256', $request->email . '|' . $otpCode . '|' . $expiresAt, config('app.key'));

        try {
            \Illuminate\Support\Facades\Notification::route('mail', $request->email)
                ->notify(new OtpVerificationNotification($otpCode, $request->name));
        } catch (\Throwable $e) {
            \Log::error('sendOtpAjax email failed: ' . $e->getMessage());
            // Still return success so UX is not blocked by mail issues
        }

        return response()->json([
            'success' => true,
            'token'   => $token,
            'message' => 'OTP telah dikirim.',
        ]);
    }

    // ── AJAX: Verify OTP token (does NOT create user) ──
    public function verifyOtpAjax(Request $request)
    {
        $request->validate([
            'otp'   => 'required|string|size:6',
            'token' => 'required|string',
        ]);

        $stored = session('ajax_otp');
        if (!$stored) {
            return response()->json(['success' => false, 'message' => 'Sesi OTP tidak ditemukan. Kirim ulang OTP.'], 422);
        }

        if (time() > $stored['expires_at']) {
            return response()->json(['success' => false, 'message' => 'Kode OTP kadaluarsa.'], 422);
        }

        // Re-derive the token to verify it matches
        $expected = hash_hmac('sha256', $stored['email'] . '|' . $stored['code'] . '|' . $stored['expires_at'], config('app.key'));
        if (!hash_equals($expected, $request->token)) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid.'], 422);
        }

        if (trim($request->otp) !== $stored['code']) {
            return response()->json(['success' => false, 'message' => 'Kode OTP salah.']);
        }

        // Mark OTP as verified in session
        session()->put('ajax_otp_verified', [
            'email'     => $stored['email'],
            'token'     => $request->token,
            'verified'  => true,
        ]);

        return response()->json(['success' => true]);
    }

    // ── Resend OTP ──
    public function resendOtp(Request $request)
    {
        $pending = session('pending_register');
        if (!$pending) {
            return redirect()->route('register')->with('error', 'Sesi pendaftaran tidak ditemukan. Silakan daftar kembali.');
        }

        $newOtpCode = (string) rand(100000, 999999);
        $expiresAt  = time() + 60; // 1 menit baru

        $pending['otp_code']       = $newOtpCode;
        $pending['otp_expires_at'] = $expiresAt;
        session()->put('pending_register', $pending);
        session()->put('otp_expires_at', $expiresAt);

        try {
            \Illuminate\Support\Facades\Notification::route('mail', $pending['email'])
                ->notify(new OtpVerificationNotification($newOtpCode, $pending['name']));
        } catch (\Throwable $e) {
            \Log::error('Resend OTP Email Gagal: ' . $e->getMessage());
        }

        return redirect()->route('otp.verify')
            ->with('success', 'Kode OTP baru berhasil dikirim ke ' . $pending['email'] . '. Silakan cek email Anda.');
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

        $oldSessionId = request()->session()->getId();
        $isNewUser = false;

        // Find or create user
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id & avatar if not yet saved
            $updates = [];
            if (!$user->google_id) {
                $updates['google_id'] = $googleUser->getId();
            }
            if (!$user->avatar && $googleUser->getAvatar()) {
                $updates['avatar'] = $googleUser->getAvatar();
            }
            if (!empty($updates)) {
                $user->update($updates);
            }
        } else {
            $isNewUser = true;
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

            try {
                $user->notify(new \App\Notifications\WelcomeNotification());
            } catch (\Throwable $e) {
                \Log::warning('WelcomeNotification failed: ' . $e->getMessage());
            }
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        try {
            app(\App\Services\CartService::class)->mergeGuestCart($user->id, $oldSessionId);
        } catch (\Throwable $e) {
            \Log::warning('Merge guest cart on Google callback failed: ' . $e->getMessage());
        }

        if ($isNewUser && $user->role === 'buyer') {
            return redirect()->route('account.profile')
                ->with('success', 'Selamat datang, ' . $user->name . '! Harap lengkapi profil Anda.');
        }

        $redirectRoute = route('account.overview');
        if ($user->role === 'super_admin' || $user->role === 'admin') {
            $redirectRoute = route('admin.dashboard');
        } elseif ($user->role === 'seller') {
            $redirectRoute = route('creator.dashboard');
        }

        return redirect()->intended($redirectRoute)
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

    // ── Show Forgot Password Form ──
    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    // ── Send Reset Link Email ──
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput()->withErrors(['email' => 'Email tidak terdaftar, cek ulang sisst...']);
        }

        $token = Str::random(64);

        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        try {
            \App\Services\MailConfigService::apply();
            $user->notify(new \App\Notifications\ResetPasswordNotification($token, $user->email));
        } catch (\Throwable $e) {
            \Log::error('ResetPasswordNotification error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal mengirim email reset password: ' . $e->getMessage());
        }

        return back()->with('success', 'Tautan reset kata sandi telah dikirim ke email Anda (' . $user->email . '). Silakan periksa inbox/spam Anda.');
    }

    // ── Show Reset Password Form ──
    public function showResetPassword(Request $request, $token)
    {
        $email = $request->query('email', '');
        return view('auth.reset_password', compact('token', 'email'));
    }

    // ── Process Reset Password ──
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:6|confirmed',
        ], [
            'email.required'        => 'Email wajib diisi.',
            'password.required'     => 'Kata sandi baru wajib diisi.',
            'password.min'          => 'Kata sandi minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $record = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withInput()->withErrors(['email' => 'Tautan reset kata sandi tidak valid atau sudah kadaluwarsa.']);
        }

        if (\Carbon\Carbon::parse($record->created_at)->addHours(2)->isPast()) {
            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withInput()->withErrors(['email' => 'Tautan reset kata sandi sudah kedaluwarsa. Silakan minta tautan baru.']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withInput()->withErrors(['email' => 'Pengguna tidak ditemukan.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', '✅ Kata sandi Anda berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
    }
}
