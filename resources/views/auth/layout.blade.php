<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk') – buyle.id</title>
    <meta name="description" content="@yield('meta_desc', 'Masuk atau daftar akun buyle.id untuk platform digital creator dan marketplace.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    @php $layoutSettings = \App\Models\Setting::getAllAsArray(); @endphp
    @if(file_exists(public_path('build/assets')) && count(glob(public_path('build/assets/*.css'))) > 0)
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #F0F9FF;
            color: #0F172A;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 1.5rem 1rem;
            box-sizing: border-box;
            position: relative;
            overflow-x: hidden;
            background-image: 
                radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(30, 179, 73, 0.15) 0px, transparent 50%);
        }
        
        .blob-1 {
            position: absolute;
            top: -100px; left: -100px;
            width: 400px; height: 400px;
            background: rgba(30, 179, 73, 0.15);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }
        .blob-2 {
            position: absolute;
            bottom: -150px; right: -50px;
            width: 500px; height: 500px;
            background: rgba(56, 189, 248, 0.15);
            filter: blur(100px);
            border-radius: 50%;
            z-index: -1;
        }

        .login-wrapper {
            width: 100%;
            max-width: 960px;
            margin: auto;
            position: relative;
            z-index: 10;
        }

        .login-glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 0.75rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            box-shadow: 0 20px 45px rgba(30, 179, 73, 0.08), 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid rgba(255, 255, 255, 0.8);
            min-height: 520px;
        }

        .login-left {
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .login-right {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-desc {
            font-size: 0.9rem;
            color: #64748B;
            line-height: 1.6;
            margin-top: 1rem;
            margin-bottom: 1.75rem;
            font-weight: 500;
        }

        .feature-badge-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .feature-badge-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.8125rem;
            color: #334155;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.8);
            padding: 0.5rem 0.85rem;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            width: fit-content;
        }

        .back-link {
            font-size: 0.8125rem;
            color: #64748B;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .back-link:hover {
            color: #1eb349;
        }

        .auth-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #0F172A;
            margin: 0 0 0.5rem;
            letter-spacing: -0.01em;
        }
        .auth-subtitle {
            font-size: 0.8125rem;
            color: #94A3B8;
            margin: 0 0 1.5rem;
            font-weight: 500;
        }
        .auth-subtitle a { color: #1eb349; text-decoration: none; font-weight: 600; }
        .auth-subtitle a:hover { text-decoration: underline; }

        .form-group {
            margin-bottom: 1.15rem;
        }

        .form-label {
            color: #475569;
            margin-bottom: 0.4rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: block;
        }
        .form-label span { color: #EF4444; margin-left: 2px; }

        .form-input {
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            width: 100%;
            color: #0F172A;
            font-size: 0.875rem;
            font-weight: 500;
            font-family: 'Montserrat', sans-serif;
            border-radius: 10px;
            outline: none;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }
        .form-input::placeholder {
            color: #CBD5E1;
            font-weight: 400;
        }
        .form-input:focus {
            border-color: #1eb349;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
        }
        .form-input.is-invalid { border-color: #EF4444; }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }
        .input-icon:hover { color: #1eb349; }

        .btn-primary {
            background: #1eb349;
            color: #fff;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            border: none;
            cursor: pointer;
            width: 100%;
            padding: 0.85rem;
            font-size: 0.9rem;
            border-radius: 10px;
            transition: all 0.25s;
            margin-top: 0.75rem;
            box-shadow: 0 6px 14px rgba(30, 179, 73, 0.2);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary:hover {
            background: #16a34a;
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(30, 179, 73, 0.28);
        }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FCA5A5;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            color: #EF4444;
            font-size: 0.8125rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.25rem 0;
            color: #94A3B8;
            font-size: 0.78rem;
            font-weight: 500;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #E2E8F0;
        }

        .btn-google {
            width: 100%;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background: #fff;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-google:hover {
            border-color: #CBD5E1;
            background: #F8FAFC;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* Mobile Responsive Breakpoint */
        @media (max-width: 768px) {
            .login-glass-panel {
                grid-template-columns: 1fr;
                padding: 0;
                min-height: auto;
            }
            .login-left {
                padding: 1.75rem 1.5rem 1.25rem;
                text-align: center;
                align-items: center;
            }
            .feature-badge-list {
                display: none;
            }
            .hero-desc {
                margin-top: 0.5rem;
                margin-bottom: 0.75rem;
                font-size: 0.8125rem;
            }
            .back-link {
                margin-top: 0.5rem;
            }
            .login-right {
                border-radius: 0 0 24px 24px;
                padding: 1.75rem 1.5rem 2rem;
                box-shadow: none;
                border-top: 1px solid #E2E8F0;
            }
        }
    </style>
</head>
<body>

<div class="blob-1"></div>
<div class="blob-2"></div>

<div class="login-wrapper">
    <div class="login-glass-panel">
        {{-- Left Side: Branding & Info --}}
        <div class="login-left">
            <div>
                @php $logo = $layoutSettings['logo'] ?? null; @endphp
                @if($logo)
                    <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;text-decoration:none;">
                        <div style="height:50px;padding:0.35rem 0.9rem;border-radius:14px;overflow:hidden;background:rgba(255,255,255,0.95);box-shadow:0 4px 16px rgba(0,0,0,0.06),0 1px 3px rgba(0,0,0,0.04);display:flex;align-items:center;justify-content:center;">
                            <img src="{{ asset('storage/'.$logo) }}" alt="buyle.id" style="height:36px;width:auto;object-fit:contain;display:block;">
                        </div>
                    </a>
                @else
                    <a href="{{ url('/') }}" style="text-decoration:none;font-size:2rem;font-weight:700;color:#0F172A;letter-spacing:-0.02em;">
                        buyle<span style="color:#1eb349;">.id</span>
                    </a>
                @endif

                <p class="hero-desc">
                    Platform Bio Link, Produk Digital & Marketplace Terlengkap untuk Bisnis dan Kreator Indonesia.
                </p>

                <div class="feature-badge-list">
                    <div class="feature-badge-item">
                        <svg width="15" height="15" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        <span>Bio Link & Katalog Produk</span>
                    </div>
                    <div class="feature-badge-item">
                        <svg width="15" height="15" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        <span>Produk Digital & E-Course</span>
                    </div>
                    <div class="feature-badge-item">
                        <svg width="15" height="15" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        <span>Pembayaran Instant (QRIS & E-Wallet)</span>
                    </div>
                </div>
            </div>

            <a href="{{ url('/') }}" class="back-link">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali ke Beranda
            </a>
        </div>

        {{-- Right Side: Login / Register Form --}}
        <div class="login-right">
            @if(session('success'))
                <div class="alert-error" style="background:#F0FDF4; border-color:#BBF7D0; color:#15803D;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error" style="background:#FEF2F2; border-color:#FCA5A5; color:#DC2626;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

</body>
</html>
