<?php

namespace App\Http\Controllers;

use App\Models\GateStaff;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TicketingAuthController extends Controller
{
    /**
     * Tampilkan Portal Super App Login & Registrasi Khusus Ticketing.
     */
    public function show(Request $request)
    {
        // Jika sudah login sebagai creator / seller / admin, langsung arahkan ke Dashboard Tiket
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'seller' || $user->role === 'super_admin' || $user->role === 'admin') {
                return redirect()->route('creator.ticket.scanner');
            }
        }

        $activeStaffSession = session('gate_staff_active');
        $initialTab = $request->query('mode', 'menu'); // 'menu', 'register', 'organizer', 'staff'

        $seo = [
            'title'       => 'Portal Tiket & Gatekeeper · Buyle.id',
            'description' => 'Akses resmi penyelenggara tiket event dan panitia gate scanner tiket Buyle.',
            'robots'      => 'noindex, nofollow',
        ];

        return view('auth.login-ticketing', compact('seo', 'activeStaffSession', 'initialTab'));
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

        // Cari panitia aktif dengan nama ini (case-insensitive)
        $candidates = GateStaff::where('is_active', true)
            ->where(function ($q) use ($name) {
                $q->where('name', $name)
                  ->orWhereRaw('LOWER(name) = ?', [strtolower($name)]);
            })
            ->with('product')
            ->get();

        if ($candidates->isEmpty()) {
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
            'email'    => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email atau No. WhatsApp wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput  = trim($request->input('email'));
        $fieldType   = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : (is_numeric($loginInput) ? 'phone' : 'username');
        $credentials = [$fieldType => $loginInput, 'password' => $request->password];
        $remember    = $request->boolean('remember', true);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->is_active === false || $user->is_active === 0) {
                Auth::logout();
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Akun penyelenggara Anda dinonaktifkan.'], 403);
                }
                return back()->withInput()->withErrors(['organizer_email' => 'Akun penyelenggara Anda saat ini dinonaktifkan.']);
            }

            // Pastikan role seller agar bisa akses studio tiket
            if ($user->role === 'buyer') {
                $user->role = 'seller';
                $user->save();
            }

            $redirectUrl = route('creator.ticket.scanner');

            if ($request->expectsJson()) {
                return response()->json([
                    'success'      => true,
                    'message'      => 'Login berhasil.',
                    'redirect_url' => $redirectUrl,
                ]);
            }

            return redirect($redirectUrl);
        }

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
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:255|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar. Silakan gunakan tombol Login Penyelenggara.',
            'phone.required'     => 'No. WhatsApp wajib diisi.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $baseUsername = Str::slug($request->name);
        if (empty($baseUsername)) {
            $baseUsername = 'organizer';
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
            'role'              => 'seller', // Langsung siap kelola event & tiket
            'email_verified_at' => now(),
            'is_active'         => true,
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        $redirectUrl = route('creator.ticket.scanner');

        if ($request->expectsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Pendaftaran berhasil. Mengalihkan ke Creator Studio...',
                'redirect_url' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl);
    }
}

