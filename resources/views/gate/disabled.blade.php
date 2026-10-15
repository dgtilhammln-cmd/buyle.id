<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Scanner Ditutup · {{ $event->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            padding: 2.5rem 2rem;
            max-width: 440px;
            width: 100%;
            text-align: center;
        }
        .icon-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #fef2f2;
            color: #ef4444;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }
        h1 { font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
        .event-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            margin-bottom: 1rem;
        }
        p { font-size: 0.92rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem; }
        .btn-reload {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #10b981;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 0.85rem 1.75rem;
            border-radius: 9999px;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            width: 100%;
        }
        .btn-reload:hover { background: #059669; transform: translateY(-1px); }
        .brand { margin-top: 2rem; font-size: 0.78rem; font-weight: 700; color: #94a3b8; letter-spacing: 0.05em; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-circle">
            <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <h1>Akses Gate Ditutup</h1>
        <div class="event-badge">{{ $event->name }}</div>
        <p>Akses pemindai tiket untuk gate panitia saat ini sedang dinonaktifkan oleh penyelenggara event. Silakan hubungi koordinator creator event Anda.</p>
        <button class="btn-reload" onclick="location.reload()">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M23 4v6h-6"></path>
                <path d="M1 20v-6h6"></path>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
            Muat Ulang Halaman
        </button>
        <div class="brand">buyle.id · Gate Pass System</div>
    </div>
</body>
</html>
