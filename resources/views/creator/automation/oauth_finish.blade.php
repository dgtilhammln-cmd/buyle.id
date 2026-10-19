<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Otentikasi Instagram Selesai</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            padding: 3rem 1.5rem;
            background: #f8fafc;
            color: #0f172a;
        }
        .card {
            max-width: 420px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            padding: 2.25rem;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .success { background: #f0fdf4; color: #16a34a; }
        .error { background: #fef2f2; color: #dc2626; }
        h2 { font-size: 1.25rem; font-weight: 800; margin: 0 0 0.5rem; }
        p { font-size: 0.88rem; color: #64748b; margin: 0 0 1.5rem; }
    </style>
</head>
<body>
    <div class="card">
        @if($success)
            <div class="icon success">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h2>Berhasil Terhubung!</h2>
            <p>{{ $message }}</p>
        @else
            <div class="icon error">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <h2>Gagal Menghubungkan</h2>
            <p>{{ $message }}</p>
        @endif
        <div style="font-size:0.75rem; color:#94a3b8;">Menutup jendela dan memperbarui dashboard...</div>
    </div>

    <script>
        setTimeout(function() {
            if (window.opener && !window.opener.closed) {
                window.opener.location.href = "{{ $redirect }}";
                window.close();
            } else {
                window.location.href = "{{ $redirect }}";
            }
        }, 1200);
    </script>
</body>
</html>
