<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header("Location: /login"); exit; }
require_once __DIR__ . '/../config.php';

$uid = $_SESSION['user_id'];
$view = isset($_GET['view']) ? $_GET['view'] : 'overview';

$me = $pdo->prepare("SELECT * FROM users WHERE id=?");
$me->execute([$uid]);
$me = $me->fetch();

if (!$me) { header("Location: /logout"); exit; }

// Statistik & URL
$stats = $stats ?? ['views' => 24, 'clicks' => 0];
$user_url = "https://bio.hvmdigital.id/" . $me['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>HVM STUDIO | Premium Dashboard</title>
    <link rel="icon" type="image/png" href="/assets/images/logobio.png">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #a1ff5a 0%, #4efdc4 100%);
            --bg-dark: #020b09;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.03);
            --neon: #a1ff5a;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }
        
        body { 
            background-color: var(--bg-dark); 
            background-image: radial-gradient(circle at 10% 10%, rgba(161, 255, 90, 0.05) 0%, transparent 40%);
            min-height: 100vh; 
            display: flex; 
            overflow-x: hidden;
        }

        /* --- SIDEBAR DESKTOP --- */
        .sidebar-container {
            width: 280px; height: 100vh; position: fixed; left: 0; top: 0;
            padding: 25px 0 25px 20px; z-index: 1000;
            transition: all 0.5s cubic-bezier(0.77, 0, 0.175, 1);
        }

        .sidebar-glass {
            width: 100%; height: 100%; background: var(--glass);
            backdrop-filter: blur(25px); border: 1px solid rgba(255,255,255,0.1);
            border-right: none; border-radius: 40px 0 0 40px;
            display: flex; flex-direction: column;
        }

        .sidebar-header { padding: 40px 20px; text-align: center; }
        .logo-box {
            width: 100%; height: 80px; background: rgba(255,255,255,0.03);
            border-radius: 20px; display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .logo-img { max-width: 90%; max-height: 55px; object-fit: contain; z-index: 2; }
        .logo-box::after {
            content: ''; position: absolute; top: -50%; left: -150%; width: 50%; height: 200%;
            background: linear-gradient(to right, transparent, rgba(161, 255, 90, 0.4), transparent);
            transform: rotate(25deg); animation: shimmer 4s infinite;
        }
        @keyframes shimmer { 0% { left: -150%; } 20% { left: 150%; } 100% { left: 150%; } }

        .menu-list { flex-grow: 1; padding-left: 15px; list-style: none; margin-top: 20px; }
        .nav-item {
            position: relative; display: flex; align-items: center; gap: 15px;
            padding: 18px 25px; color: rgba(255,255,255,0.4); text-decoration: none;
            font-weight: 500; font-size: 14px; transition: all 0.3s ease; margin-bottom: 5px;
        }
        .nav-item i { font-size: 18px; width: 20px; text-align: center; }

        /* ACTIVE MENU DESKTOP ONLY */
        @media (min-width: 993px) {
            .nav-item.active {
                background: var(--white); color: #000; border-radius: 40px 0 0 40px;
                font-weight: 700; margin-right: -1px;
            }
            .nav-item.active::before, .nav-item.active::after {
                content: ""; position: absolute; right: 0; width: 40px; height: 40px; background: transparent;
            }
            .nav-item.active::before { top: -40px; border-bottom-right-radius: 25px; box-shadow: 15px 15px 0 15px var(--white); }
            .nav-item.active::after { bottom: -40px; border-top-right-radius: 25px; box-shadow: 15px -15px 0 15px var(--white); }
            .nav-item:hover:not(.active) { color: var(--neon); transform: translateX(5px); }
        }

        /* --- CONTENT PANEL --- */
        .main-panel {
            margin-left: 280px; margin-top: 25px; margin-bottom: 25px; margin-right: 25px;
            flex-grow: 1; background: var(--white); border-radius: 40px;
            min-height: calc(100vh - 50px); padding: 50px; color: #000;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3); transition: all 0.5s ease;
        }

        /* --- MOBILE OPTIMIZATION --- */
        .mobile-header {
            display: none; position: fixed; top: 0; left: 0; width: 100%;
            height: 70px; background: rgba(2, 11, 9, 0.95); backdrop-filter: blur(10px);
            z-index: 1100; padding: 0 20px; align-items: center; justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .mobile-bottom-nav {
            display: none; position: fixed; bottom: 20px; left: 50%;
            transform: translateX(-50%); width: 92%;
            height: 70px; background: rgba(2, 11, 9, 0.95); backdrop-filter: blur(25px);
            border: 1px solid rgba(255,255,255,0.1); border-radius: 35px;
            z-index: 1100; align-items: center; justify-content: space-around;
            padding: 0 5px; box-shadow: 0 15px 35px rgba(0,0,0,0.6);
        }

        .bottom-nav-item {
            color: rgba(255,255,255,0.4); text-decoration: none;
            display: flex; flex-direction: column; align-items: center; gap: 4px; transition: 0.3s;
            flex: 1;
        }
        .bottom-nav-item i { font-size: 20px; }
        .bottom-nav-item span { font-size: 9px; font-weight: 700; text-transform: uppercase; }
        .bottom-nav-item.active { color: var(--neon); }

        @media (max-width: 992px) {
            .sidebar-container { 
                width: 100%; height: 100%; left: 0; top: 0; padding: 0;
                transform: translateY(100%); opacity: 0; visibility: hidden;
            }
            .sidebar-container.open { transform: translateY(0); opacity: 1; visibility: visible; }
            .sidebar-glass { border-radius: 0; border: none; background: rgba(2, 11, 9, 0.98); }
            
            /* Sembunyikan Logo Sidebar di Mobile agar tidak Double */
            .sidebar-container.open .sidebar-header { display: none; }

            .main-panel { margin-left: 10px; margin-right: 10px; margin-top: 85px; margin-bottom: 110px; padding: 25px; border-radius: 35px; }
            .mobile-header, .mobile-bottom-nav { display: flex; }
            
            /* Reset Active Style for Mobile Sidebar Overlay */
            .nav-item.active {
                background: rgba(161, 255, 90, 0.1);
                color: var(--neon);
                border-radius: 20px;
                margin: 5px 20px;
                border: 1px solid rgba(161, 255, 90, 0.2);
            }
            .nav-item { border-radius: 20px; margin: 5px 20px; background: rgba(255,255,255,0.03); }
        }

        .btn-toggle { background: var(--glass); border: 1px solid rgba(255,255,255,0.1); color: var(--neon); padding: 10px; border-radius: 12px; cursor: pointer; border:none; }
    </style>
</head>
<body>

    <!-- Mobile Header -->
    <header class="mobile-header">
        <button class="btn-toggle" onclick="toggleSidebar()"><i class="fas fa-grid-2"></i></button>
        <img src="/assets/images/logobio.png" style="height: 30px;">
        <a href="/logout-process" class="btn-toggle" style="color:#ff4e4e; text-decoration:none;"><i class="fas fa-power-off"></i></a>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar-container" id="sidebar">
        <div class="sidebar-glass">
            <div class="sidebar-header">
                <div class="logo-box">
                    <img src="/assets/images/logobio.png" class="logo-img">
                </div>
            </div>
            <ul class="menu-list">
                <li><a href="?view=overview" class="nav-item <?= $view=='overview'?'active':'' ?>"><i class="fas fa-home-alt"></i> Overview</a></li>
                <li><a href="?view=settings" class="nav-item <?= $view=='settings'?'active':'' ?>"><i class="fas fa-sliders-h"></i> Link Settings</a></li>
                <li><a href="?view=design" class="nav-item <?= $view=='design'?'active':'' ?>"><i class="fas fa-paint-brush"></i> Tema & Visual</a></li>
                <li><a href="?view=analytics" class="nav-item <?= $view=='analytics'?'active':'' ?>"><i class="fas fa-chart-line"></i> Analytics</a></li>
                <li><a href="?view=premium" class="nav-item <?= $view=='premium'?'active':'' ?>"><i class="fas fa-crown"></i> Premium</a></li>
                <hr style="opacity: 0.1; margin: 15px 25px;">
                <li><a href="<?= $user_url ?>" target="_blank" class="nav-item" style="color: var(--neon);"><i class="fas fa-external-link-alt"></i> Lihat Web</a></li>
                <li><a href="/logout-process" class="nav-item" style="color:#ff4e4e;"><i class="fas fa-power-off"></i> Logout</a></li>
                
                <li style="margin-top: 20px; text-align: center; display: block;" class="mobile-only-btn">
                    <button onclick="toggleSidebar()" class="btn-toggle" style="width: 80%; background:rgba(255,255,255,0.05); color:#fff;">TUTUP MENU</button>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="?view=overview" class="bottom-nav-item <?= $view=='overview'?'active':'' ?>">
            <i class="fas fa-home"></i><span>Home</span>
        </a>
        <a href="?view=settings" class="bottom-nav-item <?= $view=='settings'?'active':'' ?>">
            <i class="fas fa-link"></i><span>Links</span>
        </a>
        <a href="?view=analytics" class="bottom-nav-item <?= $view=='analytics'?'active':'' ?>">
            <i class="fas fa-chart-bar"></i><span>Stats</span>
        </a>
        <a href="?view=design" class="bottom-nav-item <?= $view=='design'?'active':'' ?>">
            <i class="fas fa-palette"></i><span>Tema</span>
        </a>
        <a href="?view=premium" class="bottom-nav-item <?= $view=='premium'?'active':'' ?>">
            <i class="fas fa-crown"></i><span>Pro</span>
        </a>
    </nav>

    <!-- Main Panel -->
    <main class="main-panel">
        <div class="panel-header">
            <h1 style="font-weight: 800; font-size: 28px; letter-spacing: -1px; text-transform: capitalize;"><?= str_replace('_', ' ', $view) ?></h1>
            <p style="color: #94a3b8; font-size: 14px; margin-bottom: 30px; font-weight: 500;">Manage your premium branding assets.</p>
        </div>

        <?php 
        $path = __DIR__ . "/views/$view.php";
        if (file_exists($path)) {
            include $path;
        } else {
            include __DIR__ . "/views/overview.php";
        }
        ?>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Haptic Feedback
        document.querySelectorAll('.nav-item, .bottom-nav-item').forEach(item => {
            item.addEventListener('click', function() {
                this.style.transform = 'scale(0.92)';
                setTimeout(() => this.style.transform = 'scale(1)', 100);
            });
        });

        // Auto hide close button on desktop
        if(window.innerWidth > 992) {
            document.querySelector('.mobile-only-btn').style.display = 'none';
        }
    </script>
    
    <?php if(file_exists(__DIR__ . '/views/parts/tutorial.php')) include __DIR__ . '/views/parts/tutorial.php'; ?>
</body>
</html>