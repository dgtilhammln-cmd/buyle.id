<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk') – buyle.id</title>
    <meta name="description" content="@yield('meta_desc', 'Masuk atau daftar akun buyle.id untuk belanja lebih mudah.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            min-height: 100vh;
            margin: 0;
            padding: 2rem 1rem;
            box-sizing: border-box;
            position: relative;
            overflow-x: hidden;
            background-image: 
                radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.2) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(30, 179, 73, 0.15) 0px, transparent 50%);
        }
        
        /* Ornamental Blobs */
        .blob-1 {
            position: absolute;
            top: -100px; left: -100px;
            width: 400px; height: 400px;
            background: rgba(30, 179, 73, 0.2);
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
            max-width: 1000px;
            margin: auto;
            position: relative;
            z-index: 10;
        }

        .login-glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            box-shadow: 0 24px 50px rgba(30, 179, 73, 0.08), 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid rgba(255, 255, 255, 0.5);
            min-height: 500px;
        }

        .login-left {
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right {
            background: #ffffff;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-placeholder {
            width: 50px; height: 50px;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; color: #fff; font-size: 1.125rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(30, 179, 73, 0.25);
            margin-bottom: 2rem;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.2;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
        }
        
        .hero-desc {
            font-size: 0.9375rem;
            color: #64748B;
            line-height: 1.6;
            margin-bottom: 3rem;
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
            margin-top: auto;
        }
        .back-link:hover {
            color: #1eb349;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0 0 0.5rem;
            letter-spacing: -0.02em;
        }
        .auth-subtitle {
            font-size: 0.8125rem;
            color: #94A3B8;
            margin: 0 0 2rem;
            font-weight: 500;
        }
        .auth-subtitle a { color: #1eb349; text-decoration: none; font-weight: 600; }
        .auth-subtitle a:hover { text-decoration: underline; }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            color: #475569;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
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

        .form-error {
            font-size: 0.75rem;
            color: #EF4444;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

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
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            border: none;
            cursor: pointer;
            width: 100%;
            padding: 0.875rem;
            font-size: 0.9rem;
            border-radius: 10px;
            transition: all 0.3s;
            margin-top: 1rem;
            box-shadow: 0 8px 16px rgba(30, 179, 73, 0.2);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary:hover {
            background: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(30, 179, 73, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FCA5A5;
            padding: 0.875rem 1rem;
            margin-bottom: 1.25rem;
            color: #EF4444;
            font-size: 0.8125rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #374151;
            cursor: pointer;
            font-weight: 500;
        }
        .remember-label input[type="checkbox"] { width: 16px; height: 16px; accent-color: #1eb349; }
        .forgot-link { font-size: 0.8rem; color: #1eb349; text-decoration: none; font-weight: 600; }
        .forgot-link:hover { text-decoration: underline; }

        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0;
            color: #94A3B8;
            font-size: 0.8rem;
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
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background: #fff;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-google:hover {
            border-color: #CBD5E1;
            background: #F8FAFC;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transform: translateY(-1px);
        }

        /* Mobile Responsive */
        @media (max-width: 800px) {
            .login-glass-panel {
                grid-template-columns: 1fr;
                padding: 0;
                overflow: hidden;
            }
            .login-left {
                padding: 2.5rem 2rem 2rem;
                text-align: center;
            }
            .logo-placeholder {
                margin: 0 auto 1.5rem;
            }
            .back-link {
                margin-top: 1.5rem;
                justify-content: center;
            }
            .login-right {
                border-radius: 0 0 24px 24px;
                padding: 2rem;
                box-shadow: none;
                border-top: 1px solid rgba(30, 179, 73, 0.1);
            }
        }
    </style>
</head>
<body>

<div class="blob-1"></div>
<div class="blob-2"></div>

<div class="login-wrapper">
    <div class="login-glass-panel">
        {{-- Left Side: Text & Welcome --}}
        <div class="login-left">
            @php $logo = $layoutSettings['logo'] ?? null; @endphp
            @if($logo)
                <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;text-decoration:none;margin-bottom:2rem;align-self:flex-start;">
                    <div style="height:44px;padding:0.25rem 0.75rem;border-radius:999px;overflow:hidden;background:rgba(255,255,255,0.95);box-shadow:0 4px 20px rgba(0,0,0,0.08),0 1px 4px rgba(0,0,0,0.05);display:flex;align-items:center;justify-content:center;">
                        <img src="{{ asset('storage/'.$logo) }}" alt="Logo" style="height:32px;width:auto;object-fit:contain;border-radius:0;display:block;">
                    </div>
                </a>
            @else
                <div class="logo-placeholder">AR</div>
            @endif
            
            <h1 class="hero-title">Belanja<br>buyle.id</h1>
            <p class="hero-desc">Temukan segala kebutuhan rumah tangga Anda dalam satu tempat dengan harga terbaik.</p>
            
            <a href="{{ url('/') }}" class="back-link">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali ke Beranda
            </a>
        </div>

        {{-- Right Side: Login Form --}}
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
