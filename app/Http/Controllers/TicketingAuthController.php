<?php

namespace App\Http\Controllers;

use App\Models\GateStaff;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class TicketingAuthController extends Controller
{
    /**
     * Tampilkan Portal Super App Login & Registrasi Khusus Ticketing & Creator (/tix/auth).
     */
    public function show(Request $request)
    {
        // Jika sudah login, langsung arahkan ke dashboard yang sesuai
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'seller' || $user->role === 'super_admin' || $user->role === 'admin') {
                return redirect('/creator/event-attendance');
            } else {
                return redirect('/akun');
            }
        }

        $activeStaffSession = session('gate_staff_active');
        $initialRole = $request->query('role', 'event_creator'); // 'event_creator', 'buyle_creator', 'buyer'
        $initialMode = $request->query('mode', 'register'); // 'register' (daftar) atau 'login'

        $seo = [
            'title'       => 'Masuk & Daftar · Buyle TIX',
            'description' => 'Akses resmi Event Creator, Buyle Creator, dan Pembeli tiket Buyle TIX.',
            'robots'      => 'noindex, nofollow',
        ];

        return view('auth.login-ticketing', compact('seo', 'activeStaffSession', 'initialRole', 'initialMode'));
    }

    /**
     * Login Panitia Gate Scanner (Input Nama & Password).
     */
    public function loginStaff(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'password' => 'required|string',
        ], [
            'name.required'     => 'Nama panitia wajib diisi.',
            'password.required' => 'Password panitia wajib diisi.',
        ]);

        $name = trim($request->input('name'));
        $password = trim($request->input('password'));

        // Brute-force throttling per IP + nama panitia (maks 5 percobaan gagal per menit)
        $staffThrottleKey = 'staff_login|' . Str::lower($name) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($staffThrottleKey, 5)) {
            $seconds = RateLimiter::availableIn($staffThrottleKey);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Terlalu banyak percobaan masuk scanner. Silakan coba lagi dalam {$seconds} detik."
                ], 429);
            }
            return back()->withInput()->withErrors([
                'staff_password' => "Terlalu banyak percobaan masuk scanner yang gagal. Silakan tunggu {$seconds} detik."
            ]);
        }

        // Cari panitia aktif dengan nama ini (case-insensitive)
        $candidates = GateStaff::where('is_active', true)
            ->where(function ($q) use ($name) {
                $q->where('name', $name)
                  ->orWhereRaw('LOWER(name) = ?', [strtolower($name)]);
            })
            ->with('product')
            ->get();

        if ($candidates->isEmpty()) {
            RateLimiter::hit($staffThrottleKey, 60);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Nama panitia '{$name}' tidak ditemukan atau akses dinonaktifkan oleh penyelenggara."
                ], 404);
            }
            return back()->withInput()->withErrors([
                'staff_name' => "Nama panitia '{$name}' tidak ditemukan atau akses dinonaktifkan."
            ]);
        }

        $matchedStaff = null;
        foreach ($candidates as $cand) {
            if (Hash::check($password, $cand->pin) || $cand->pin === $password) {
                $matchedStaff = $cand;
                break;
            }
        }

        if (!$matchedStaff) {
            RateLimiter::hit($staffThrottleKey, 60);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password panitia salah. Silakan periksa kembali kata sandi yang diberikan penyelenggara.'
                ], 401);
            }
            return back()->withInput()->withErrors([
                'staff_password' => 'Password panitia salah. Periksa kembali kata sandi Anda.'
            ]);
        }

        // Login berhasil -> bersihkan hitungan rate limiter
        RateLimiter::clear($staffThrottleKey);

        $event = $matchedStaff->product;
        if (!$event) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event tiket terkait tidak ditemukan atau telah dihapus.'
                ], 404);
            }
            return back()->withErrors([
                'staff_name' => 'Event tiket terkait tidak ditemukan.'
            ]);
        }

        $gateKey = $event->getOrGenerateGateKey();

        // Simpan sesi panitia gate
        session([
            'gate_staff_' . $event->id => [
                'id'         => $matchedStaff->id,
                'name'       => $matchedStaff->name,
                'gate'       => $matchedStaff->gate_number,
                'event_id'   => $event->id,
                'event_name' => $event->name,
            ],
            'gate_staff_active' => [
                'id'         => $matchedStaff->id,
                'name'       => $matchedStaff->name,
                'gate'       => $matchedStaff->gate_number,
                'event_id'   => $event->id,
                'event_name' => $event->name,
                'gate_key'   => $gateKey,
            ]
        ]);

        $redirectUrl = route('gate.scanner', ['key' => $gateKey]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => "Selamat bertugas, {$matchedStaff->name}!",
                'redirect_url' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl);
    }

    /**
     * Login Penyelenggara Event (Creator).
     */
    public function loginOrganizer(Request $request)
    {
        $request->validate([
            'email'       => 'required|string',
            'password'    => 'required|string',
            'target_role' => 'nullable|string|in:event_creator,buyle_creator,buyer',
        ], [
            'email.required'    => 'Email atau No. WhatsApp wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput  = trim($request->input('email'));
        $fieldType   = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : (is_numeric($loginInput) ? 'phone' : 'username');
        $credentials = [$fieldType => $loginInput, 'password' => $request->password];
        $remember    = $request->boolean('remember', true);

        // Brute-force throttling: maks 5 percobaan per menit per identifier + IP
        $throttleKey = 'organizer_login|' . Str::lower($loginInput) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Terlalu banyak percobaan masuk yang gagal. Silakan tunggu {$seconds} detik."
                ], 429);
            }
            return back()->withInput($request->only('email'))->withErrors([
                'organizer_email' => "Terlalu banyak percobaan masuk yang gagal. Silakan tunggu {$seconds} detik."
            ]);
        }

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->is_active === false || $user->is_active === 0) {
                Auth::logout();
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Akun penyelenggara Anda dinonaktifkan.'], 403);
                }
                return back()->withInput()->withErrors(['organizer_email' => 'Akun penyelenggara Anda saat ini dinonaktifkan.']);
            }

            $targetRole  = $request->input('target_role', 'event_creator');
            $customRedir = $request->input('redirect_url');

            // Role assignment: Event Creator & Buyle Creator otomatis upgrade ke seller
            if ($targetRole === 'event_creator' || $targetRole === 'buyle_creator') {
                if ($user->role === 'buyer') {
                    $user->role = 'seller';
                    $user->save();
                }
            }

            // Sanitasi redirect URL aman (anti open-redirect phishing)
            $redirectUrl = $this->getSafeRedirectUrl($customRedir, $targetRole);

            if ($request->expectsJson()) {
                return response()->json([
                    'success'      => true,
                    'message'      => 'Login berhasil.',
                    'redirect_url' => $redirectUrl,
                ]);
            }

            return redirect($redirectUrl);
        }

        RateLimiter::hit($throttleKey, 60);

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Email/WhatsApp atau Kata Sandi salah.'], 401);
        }

        return back()->withInput($request->only('email'))->withErrors([
            'organizer_email' => 'Email/No. WhatsApp atau Kata Sandi salah.',
        ]);
    }

    /**
     * Daftar Akun Penyelenggara Baru.
     */
    public function registerOrganizer(Request $request)
    {
        // Rate-limit pendaftaran: maks 5 akun per 10 menit per IP
        $regThrottleKey = 'organizer_reg|' . $request->ip();
        if (RateLimiter::tooManyAttempts($regThrottleKey, 5)) {
            $seconds = RateLimiter::availableIn($regThrottleKey);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Terlalu banyak pendaftaran dari perangkat Anda. Silakan coba lagi dalam {$seconds} detik."
                ], 429);
            }
            return back()->withInput()->withErrors([
                'email' => "Terlalu banyak pendaftaran dari IP ini. Silakan tunggu {$seconds} detik."
            ]);
        }

        $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|max:255|unique:users,email',
            'phone'       => 'required|string|max:20',
            'password'    => 'required|string|min:6|confirmed',
            'target_role' => 'nullable|string|in:event_creator,buyle_creator,buyer',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar. Silakan gunakan tab Login.',
            'phone.required'     => 'No. WhatsApp wajib diisi.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        RateLimiter::hit($regThrottleKey, 600);

        $targetRole   = $request->input('target_role', 'event_creator');
        $customRedir  = $request->input('redirect_url');
        $assignedRole = ($targetRole === 'buyer') ? 'buyer' : 'seller';

        $baseUsername = Str::slug($request->name);
        if (empty($baseUsername)) {
            $baseUsername = 'creator';
        }
        $username = $baseUsername . rand(100, 9999);
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . rand(100, 9999);
        }

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'username'          => $username,
            'password'          => Hash::make($request->password),
            'role'              => $assignedRole,
            'email_verified_at' => now(),
            'is_active'         => true,
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        $redirectUrl = $this->getSafeRedirectUrl($customRedir, $targetRole);

        if ($request->expectsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Pendaftaran berhasil. Mengalihkan ke halaman tujuan...',
                'redirect_url' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl);
    }

    /**
     * Sanitasi redirect URL agar tidak bisa diarahkan ke domain luar (Anti Open-Redirect Phishing).
     */
    private function getSafeRedirectUrl(?string $customRedir, string $targetRole): string
    {
        if (!empty($customRedir)) {
            $parsedHost = parse_url($customRedir, PHP_URL_HOST);
            $appHost    = parse_url(config('app.url'), PHP_URL_HOST);

            // Hanya izinkan jika relative path atau host persis sama dengan domain app
            if ((empty($parsedHost) || strcasecmp($parsedHost, $appHost) === 0) && !Str::startsWith($customRedir, ['//', '\\\\'])) {
                return $customRedir;
            }
        }

        if ($targetRole === 'event_creator') {
            return url('/creator/event-attendance');
        } elseif ($targetRole === 'buyle_creator') {
            return url('/creator/dashboard');
        }

        return url('/akun');
    }
}

