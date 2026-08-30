<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | buyle.id</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    @if(file_exists(public_path('build/assets')) && count(glob(public_path('build/assets/*.css'))) > 0)
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #F4FBF7;
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
                radial-gradient(at 0% 0%, rgba(165, 207, 55, 0.2) 0px, transparent 50%),
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
            background: rgba(165, 207, 55, 0.15);
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
            margin-top: auto; /* Push to bottom */
        }
        .back-link:hover {
            color: #1eb349;
        }

        /* Right side form styles */
        .form-header {
            margin-bottom: 2rem;
        }
        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0 0 0.5rem;
            letter-spacing: -0.02em;
        }
        .form-subtitle {
            font-size: 0.8125rem;
            color: #94A3B8;
            margin: 0;
            font-weight: 500;
        }

        .form-label {
            color: #475569;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            display: block;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .input-modern {
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
        .input-modern::placeholder {
            color: #CBD5E1;
            font-weight: 400;
        }
        .input-modern:focus {
            border-color: #1eb349;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
        }

        .toggle-password {
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
        .toggle-password:hover { color: #1eb349; }

        .btn-primary {
            background: #1eb349; /* solid blue */
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
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(30, 179, 73, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }

        .error-box {
            background: #FEF2F2;
            border: 1px solid #FCA5A5;
            padding: 0.875rem 1rem;
            margin-bottom: 1.25rem;
            color: #EF4444;
            font-size: 0.8125rem;
            border-radius: 8px;
            font-weight: 500;
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

@php 
    $logo = \App\Models\Setting::get('logo');
@endphp

<div class="login-wrapper">
    <div class="login-glass-panel">
        {{-- Left Side: Text & Welcome --}}
        <div class="login-left">
            @if($logo)
                <img src="{{ asset('storage/'.$logo) }}" alt="Logo" style="height: 50px; width: auto; max-width: 250px; object-fit: contain; align-self: flex-start; margin-bottom: 2rem;">
            @else
                <div class="logo-placeholder">AR</div>
            @endif
            
            <h1 class="hero-title">buyle.id<br>Control Panel</h1>
            <p class="hero-desc">Sistem manajemen konten dan pengaturan website untuk efisiensi bisnis Anda.</p>
            
            <a href="{{ url('/en/') }}" class="back-link">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali ke Beranda
            </a>
        </div>

        {{-- Right Side: Login Form --}}
        <div class="login-right">
            <div class="form-header">
                <h2 class="form-title">Log In</h2>
                <p class="form-subtitle">Welcome back! Please enter your details.</p>
            </div>

            @if(session('error'))
                <div class="error-box">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="input-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-modern" placeholder="admin@domain.com" required autofocus>
                    @error('email')<p style="color:#EF4444;font-size:0.7rem;margin:0.25rem 0 0;">{{ $message }}</p>@enderror
                </div>
                
                <div class="input-group">
                    <label class="form-label">Password</label>
                    <div style="position:relative;">
                        <input type="password" name="password" id="password" class="input-modern" style="padding-right:2.5rem;" placeholder="••••••••" required>
                        <button type="button" class="toggle-password" id="toggle-btn" aria-label="Toggle password visibility">
                            <svg id="eye-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    @error('password')<p style="color:#EF4444;font-size:0.7rem;margin:0.25rem 0 0;">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn-primary">
                    Login
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 1.5rem;">
                <p style="font-size: 0.75rem; color: #94A3B8; margin: 0;">developed by <a href="https://hvmdigital.id" target="_blank" rel="noopener" style="color: #1eb349; text-decoration: none; font-weight: 600;">hvmdigital.id</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    localStorage.removeItem('buyle.id_intro_played');

    // Toggle Password Visibility
    const toggleBtn = document.getElementById('toggle-btn');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    toggleBtn.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        if (type === 'text') {
            eyeIcon.innerHTML = `
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            `;
        } else {
            eyeIcon.innerHTML = `
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            `;
        }
    });
</script>
</body>
</html>
