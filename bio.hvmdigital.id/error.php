<?php
/**
 * HVM STUDIO - PREMIUM ERROR ENGINE v2.6
 * Fix: Absolute Centering & Responsive Sizing
 */

$status = $_GET['code'] ?? ($_SERVER['REDIRECT_STATUS'] ?? 404);

$error_config = [
    404 => [
        'title' => 'Destination Unreachable',
        'desc'  => 'Halaman yang Anda tuju tidak tersedia atau telah berpindah alamat. Pastikan URL yang Anda masukkan sudah benar.',
        'icon'  => 'fa-compass',
        'color' => '#a1ff5a'
    ],
    403 => [
        'title' => 'Restricted Access',
        'desc'  => 'Izin akses terbatas. Anda tidak memiliki otoritas yang diperlukan untuk menjangkau direktori atau halaman ini.',
        'icon'  => 'fa-shield-halved',
        'color' => '#ff4e4e'
    ],
    500 => [
        'title' => 'System Disruption',
        'desc'  => 'Terjadi kendala internal pada server saat memproses permintaan Anda. Tim teknis kami sedang melakukan penanganan.',
        'icon'  => 'fa-microchip',
        'color' => '#4efdc4'
    ],
    503 => [
        'title' => 'System Enhancement',
        'desc'  => 'Layanan sedang dalam proses optimalisasi untuk meningkatkan pengalaman Anda. Kami akan segera kembali dalam waktu dekat.',
        'icon'  => 'fa-clock-rotate-left',
        'color' => '#fbbf24'
    ]
];

$data = $error_config[$status] ?? $error_config[404];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $status ?> | <?= $data['title'] ?> | HVM STUDIO</title>
    
    <link rel="icon" type="image/png" href="/assets/images/logobio.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg: #020b09;
            --accent: <?= $data['color'] ?>;
            --white: #ffffff;
        }

        /* Reset Total */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }

        body, html {
            height: 100%;
            width: 100%;
            background-color: var(--bg);
            overflow: hidden;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            /* dvh = dynamic viewport height (mengatasi bar browser mobile) */
            height: 100dvh; 
            position: relative;
        }

        /* --- LUXURY BACKGROUND --- */
        .aura {
            position: absolute; width: 60vw; height: 60vw;
            border-radius: 50%; filter: blur(120px);
            opacity: 0.08; z-index: 1;
            animation: moveAura 15s infinite alternate ease-in-out;
        }
        .aura-1 { background: var(--accent); top: -10%; left: -10%; }
        .aura-2 { background: #4efdc4; bottom: -10%; right: -10%; animation-delay: -5s; }

        @keyframes moveAura {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(10%, 15%) scale(1.2); }
        }

        /* --- CONTENT WRAPPER --- */
        .container {
            position: relative;
            z-index: 10;
            padding: 20px;
            width: 100%;
            max-width: 450px; /* Batasi lebar teks agar tidak melebar */
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            animation: entrance 1s cubic-bezier(0.19, 1, 0.22, 1);
        }

        @keyframes entrance {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Minimalist Status Header */
        .status-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 4px;
            font-weight: 800;
            font-size: 11px;
            opacity: 0.9;
        }

        /* Error Code Typography */
        .error-code {
            font-size: clamp(90px, 20vw, 160px); /* Ukuran adaptif */
            font-weight: 900;
            line-height: 1;
            margin-bottom: 25px;
            letter-spacing: -6px;
            background: linear-gradient(180deg, #fff 40%, rgba(255,255,255,0.1) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 15px 30px rgba(0,0,0,0.5));
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        /* Headlines */
        h1 {
            font-size: clamp(20px, 5vw, 26px);
            font-weight: 800;
            color: var(--white);
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        p {
            font-size: clamp(13px, 3.5vw, 15px);
            color: rgba(255, 255, 255, 0.4);
            line-height: 1.6;
            margin-bottom: 40px;
            padding: 0 15px;
        }

        /* Premium Button */
        .btn-action {
            display: inline-block;
            text-decoration: none;
            background: var(--white);
            color: #000;
            padding: 16px 40px;
            border-radius: 100px;
            font-weight: 800;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }

        .btn-action:hover {
            transform: translateY(-5px);
            background: var(--accent);
            box-shadow: 0 15px 35px rgba(161, 255, 90, 0.3);
        }

        /* Branding Logo */
        .footer-logo {
            position: absolute;
            bottom: 40px;
            height: 25px;
            opacity: 0.3;
        }

        /* Tablet/Mobile Adjustments */
        @media (max-width: 768px) {
            .container { padding: 15px; }
            .error-code { letter-spacing: -4px; }
        }
    </style>
</head>
<body>

    <div class="aura aura-1"></div>
    <div class="aura aura-2"></div>

    <div class="container">
        <!-- Minimalist Header -->
        <div class="status-header">
            <i class="fas <?= $data['icon'] ?>"></i>
            <span>Warning</span>
        </div>

        <!-- Big Code -->
        <div class="error-code"><?= $status ?></div>
        
        <!-- Content -->
        <h1><?= $data['title'] ?></h1>
        <p><?= $data['desc'] ?></p>
        
        <a href="/" class="btn-action">KEMBALI KE BERANDA</a>
    </div>

    <!-- Minimal Logo Footer (Tetap di tengah bawah) -->
    <img src="/assets/images/logobio.png" class="footer-logo" alt="HVM STUDIO">

</body>
</html>