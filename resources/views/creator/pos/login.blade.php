<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Login Kasir Digital - Buyle POS</title>

    <!-- PWA Settings (Dedicated Buyle POS PWA) -->
    <link rel="manifest" href="/pos-manifest.json">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Buyle POS">
    <link rel="apple-touch-icon" href="/buyle-logo.png">
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --pg: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            --pg-shadow: rgba(30, 179, 73, 0.35);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* ── Splash Screen (Putih + Logo Header saat PWA Dibuka) ── */
        #posSplash {
            position: fixed;
            inset: 0;
            background: #ffffff;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        #posSplash.hide {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .splash-logo {
            width: 84px;
            height: 84px;
            border-radius: 24px;
            background: #ffffff;
            padding: 10px;
            box-shadow: 0 14px 35px rgba(30, 179, 73, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulseLogo 1.6s infinite ease-in-out;
        }

        .splash-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .splash-title {
            margin-top: 1.25rem;
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .splash-subtitle {
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 600;
            margin-top: 4px;
        }

        .splash-spinner {
            margin-top: 2rem;
            width: 24px;
            height: 24px;
            border: 2.5px solid #f1f5f9;
            border-top-color: #1eb349;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes pulseLogo {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ── Container Kartu Login PWA POS (Super App Mobile-First) ── */
        .login-card {
            width: 100%;
            max-width: 410px;
            background: #ffffff;
            border-radius: 28px;
            padding: 2rem 1.65rem 2.2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04), 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1.5px solid #f1f5f9;
            position: relative;
            z-index: 10;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 1.85rem;
        }

        .brand-icon-wrap {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 8px 24px rgba(30, 179, 73, 0.12);
            border: 1px solid #f1f5f9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
            padding: 8px;
        }

        .brand-icon-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        /* ── Google Login Button ── */
        .pos-login-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.15rem 0 0.95rem;
            color: #94a3b8;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .pos-login-divider::before,
        .pos-login-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .pos-login-divider span {
            padding: 0 10px;
        }

        .btn-google-pos {
            width: 100%;
            height: 48px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            box-sizing: border-box;
        }

        .btn-google-pos:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .btn-google-pos:active {
            transform: translateY(0);
        }

        .brand-desc {
            font-size: 0.82rem;
            color: #64748b;
            margin-top: 8px;
            font-weight: 500;
        }

        /* ── Form Inputs ── */
        .form-group {
            margin-bottom: 1.15rem;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 48px;
            padding: 0 14px 0 42px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.88rem;
            color: #0f172a;
            font-weight: 600;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: #1eb349;
            box-shadow: 0 0 0 4px rgba(30, 179, 73, 0.12);
        }

        .toggle-pwd {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.35rem;
            font-size: 0.75rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #475569;
            cursor: pointer;
            font-weight: 600;
        }

        .remember-label input {
            accent-color: #1eb349;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        /* ── Submit Button (Gradient Modern) ── */
        .btn-submit {
            width: 100%;
            height: 48px;
            background: var(--pg);
            border: none;
            border-radius: 999px;
            color: #ffffff;
            font-size: 0.92rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 18px var(--pg-shadow);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        /* ── Alert Error ── */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.65rem 0.85rem;
            border-radius: 12px;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 1.15rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-note {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.72rem;
            color: #94a3b8;
            line-height: 1.4;
        }

        .footer-note a {
            color: #1eb349;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <!-- ── 1. Splash Screen Putih + Logo Header di Awal Buka PWA ── -->
    <div id="posSplash">
        <div class="splash-logo">
            <img src="/buyle-logo.png" alt="Buyle POS Logo">
        </div>
        <h2 class="splash-title">Buyle POS</h2>
        <p class="splash-subtitle">Kasir Digital & Super App</p>
        <div class="splash-spinner"></div>
    </div>

    <!-- ── 2. Card Login Khusus POS ── -->
    <div class="login-card">
        <div class="brand-header">
            <div class="brand-icon-wrap">
                <img src="/buyle-logo.png" alt="Buyle Logo">
            </div>
            <h1 class="brand-title">Kasir Digital POS</h1>
            <p class="brand-desc">Masuk sebagai Pemilik Toko atau Staf Kasir untuk mulai transaksi.</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('pos.login.submit') }}" method="POST">
            @csrf

            <!-- Email atau Username Kasir -->
            <div class="form-group">
                <label class="form-label" for="posEmail">Email atau Username</label>
                <div class="input-wrap">
                    <div class="input-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <input type="text" name="email" id="posEmail" class="form-input" placeholder="contoh@toko.com / kasir1" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
            </div>

            <!-- Password atau PIN Kasir -->
            <div class="form-group">
                <label class="form-label" for="posPassword">Kata Sandi / PIN</label>
                <div class="input-wrap">
                    <div class="input-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <input type="password" name="password" id="posPassword" class="form-input" placeholder="Masukkan kata sandi" required autocomplete="current-password">
                    <button type="button" class="toggle-pwd" onclick="togglePasswordVisibility()" title="Lihat Kata Sandi">
                        <svg id="eyeIcon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <!-- Ingat Saya Permanen (Anti-Logout) -->
            <div class="options-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" value="1" checked>
                    <span>Ingat saya di perangkat ini</span>
                </label>
                <a href="{{ route('password.request') }}" style="color:#64748b; text-decoration:none; font-weight:600;">Lupa sandi?</a>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-submit">
                <span>Buka Aplikasi Kasir</span>
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>
        </form>

        <!-- Divider & Login Google -->
        <div class="pos-login-divider">
            <span>atau</span>
        </div>

        <a href="{{ route('auth.google', ['redirect' => route('creator.pos.index')]) }}" class="btn-google-pos">
            <svg width="20" height="20" viewBox="0 0 48 48">
                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
            </svg>
            <span>Masuk dengan Google</span>
        </a>

        <div class="footer-note">
            Aplikasi Kasir Digital Terintegrasi Toko Buyle.<br>
            Bukan kasir toko ini? <a href="{{ route('home') }}">Kembali ke Beranda</a>
        </div>
    </div>

    <script>
        // Auto Dismiss Splash Screen smoothly after initial load
        window.addEventListener('load', function() {
            setTimeout(function() {
                const splash = document.getElementById('posSplash');
                if (splash) {
                    splash.classList.add('hide');
                    setTimeout(() => splash.remove(), 400);
                }
            }, 600);
        });

        // Toggle Password visibility
        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('posPassword');
            const eyeIcon = document.getElementById('eyeIcon');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                pwdInput.type = 'password';
                eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }

        // Register Dedicated POS Service Worker for PWA Builder
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw-pos.js', { scope: '/' })
                    .then(reg => console.log('Buyle POS SW registered:', reg.scope))
                    .catch(err => console.warn('Buyle POS SW failed:', err));
            });
        }
    </script>
</body>
</html>

