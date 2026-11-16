<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Undangan Belum Aktif &bull; Buyle Wedding</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #06080c;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }
        .bg-glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(30, 179, 73, 0.18) 0%, rgba(6, 8, 12, 0) 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
        }
        .card {
            position: relative;
            z-index: 1;
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            max-width: 440px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
        }
        .lock-badge {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.18), rgba(217, 119, 6, 0.08));
            border: 1.5px solid rgba(245, 158, 11, 0.4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: #fbbf24;
        }
        .eyebrow {
            font-family: 'Cinzel', serif;
            font-size: 0.72rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: #f59e0b;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        h1 {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.75rem;
            letter-spacing: 0.05em;
        }
        .couple-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 1rem;
        }
        p {
            font-size: 0.85rem;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 1.8rem;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 0.85rem 1.25rem;
            border-radius: 14px;
            font-size: 0.88rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Montserrat', sans-serif;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 6px 20px rgba(30, 179, 73, 0.35);
            margin-bottom: 0.6rem;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(30, 179, 73, 0.45);
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="card">
        <div class="lock-badge">
            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>
        <div class="eyebrow">Aktivasi Diperlukan</div>
        <h1>Undangan Belum Aktif</h1>
        
        @if(!empty($wedding->groom_name) || !empty($wedding->bride_name))
            <div class="couple-name">
                {{ $wedding->groom_nickname ?? $wedding->groom_name }} &amp; {{ $wedding->bride_nickname ?? $wedding->bride_name }}
            </div>
        @endif

        <p>
            Undangan digital ini saat ini belum dapat diakses publik karena masih menunggu penyelesaian aktivasi pembayaran oleh pembuat acara.
        </p>

        @auth
            @if(auth()->id() === $wedding->user_id)
                <a href="{{ route('creator.wedding.index', ['w' => $wedding->id]) }}" class="btn-action btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    Selesaikan Pembayaran di Dashboard
                </a>
            @else
                <a href="{{ url('/') }}" class="btn-action btn-secondary">
                    Kembali ke Beranda
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn-action btn-primary">
                Masuk Pemilik Undangan
            </a>
            <a href="{{ url('/') }}" class="btn-action btn-secondary">
                Kembali ke Beranda
            </a>
        @endauth
    </div>
</body>
</html>
