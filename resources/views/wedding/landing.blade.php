<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyle Wedding · Platform Undangan Digital &amp; Smart Guest Book</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,800;1,400&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            text-align: center;
            background: radial-gradient(circle at 50% 20%, rgba(30, 179, 73, 0.15) 0%, rgba(15, 23, 42, 1) 75%);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(30, 179, 73, 0.15);
            color: #4ade80;
            border: 1px solid rgba(74, 222, 128, 0.3);
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.25rem;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        p {
            font-size: clamp(0.9rem, 2vw, 1.1rem);
            color: #94a3b8;
            max-width: 620px;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 0.85rem 2rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 4px 20px rgba(30, 179, 73, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 179, 73, 0.5);
        }

        .features-row {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 3.5rem;
            max-width: 800px;
        }

        .feat-item {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 1.25rem;
            flex: 1;
            min-width: 220px;
            text-align: left;
        }

        .feat-title {
            font-weight: 700;
            font-size: 0.9rem;
            color: #f1f5f9;
            margin-bottom: 0.25rem;
        }

        .feat-desc {
            font-size: 0.75rem;
            color: #64748b;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <div class="badge">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        Buyle Wedding Platform
    </div>

    <h1>Undangan Digital Cerdas &amp; Smart Guest Book</h1>
    <p>
        Hadirkan momen pernikahan modern yang tak terlupakan dengan QR check-in berstandar ISO, amplop digital terintegrasi, InstaStory card generator, dan cadangan buku tamu fisik.
    </p>

    <a href="{{ route('creator.wedding.index') }}" class="btn-cta">
        Kelola Undangan Pernikahan
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
    </a>

    <div class="features-row">
        <div class="feat-item">
            <div class="feat-title">Smart QR Check-in</div>
            <div class="feat-desc">Validasi kehadiran tamu otomatis tanpa antrean panjang di meja resepsi.</div>
        </div>
        <div class="feat-item">
            <div class="feat-title">Amplop Digital QRIS</div>
            <div class="feat-desc">Dukungan transfer bank dan QRIS instan bagi tamu yang ingin berbagi tanda kasih.</div>
        </div>
        <div class="feat-item">
            <div class="feat-title">Anti-Nakal Protection</div>
            <div class="feat-desc">Verifikasi nama tamu database, mencegah modifikasi tautan oleh tamu tak terdaftar.</div>
        </div>
    </div>

</body>
</html>
