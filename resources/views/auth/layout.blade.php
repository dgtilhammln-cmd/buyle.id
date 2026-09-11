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
            max-width: 460px;
            margin: auto;
            position: relative;
            z-index: 10;
        }

        .login-glass-panel {
            background: #ffffff;
            border-radius: 24px;
            padding: 2.25rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 20px 45px rgba(30, 179, 73, 0.08), 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #E2E8F0;
        }

        .logo-center-wrap {
            margin-bottom: 1.25rem;
            text-align: center;
        }
        .logo-center-wrap img {
            height: 48px;
            width: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            background: none !important;
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
        }

        .auth-content-wrap {
            width: 100%;
        }

        .back-link {
            font-size: 0.8125rem;
            color: #64748B;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            width: 100%;
        }
        .back-link:hover {
            color: #1eb349;
        }

        .auth-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0 0 0.4rem;
            letter-spacing: -0.01em;
            text-align: center;
        }
        .auth-subtitle {
            font-size: 0.8125rem;
            color: #94A3B8;
            margin: 0 0 1.5rem;
            font-weight: 500;
            text-align: center;
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

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 0.8rem;
        }
        .remember-label {
            color: #64748B;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            cursor: pointer;
        }
        .forgot-link {
            color: #1eb349;
            text-decoration: none;
            font-weight: 600;
        }

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

        @media (max-width: 480px) {
            .login-glass-panel {
                padding: 1.75rem 1.25rem;
                border-radius: 20px;
            }
        }
    </style>
</head>
<body>

<div class="blob-1"></div>
<div class="blob-2"></div>

<div class="login-wrapper">
    <div class="login-glass-panel">
        {{-- Logo Only (Plain PNG, No card/shape wrapper) --}}
        <div class="logo-center-wrap">
            @php $logo = $layoutSettings['logo'] ?? null; @endphp
            @if($logo)
                <a href="{{ url('/') }}" style="text-decoration:none;">
                    <img src="{{ asset('storage/'.$logo) }}" alt="buyle.id">
                </a>
            @else
                <a href="{{ url('/') }}" style="text-decoration:none;font-size:2.2rem;font-weight:800;color:#0F172A;letter-spacing:-0.02em;">
                    buyle<span style="color:#1eb349;">.id</span>
                </a>
            @endif
        </div>

        {{-- Form Content Area --}}
        <div class="auth-content-wrap">
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

        {{-- Back to home link --}}
        <a href="{{ url('/') }}" class="back-link">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Beranda
        </a>
    </div>
</div>

</body>
</html>
