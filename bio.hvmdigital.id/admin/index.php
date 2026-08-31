<?php
require_once __DIR__ . '/../config.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// PROTEKSI: Cek apakah yang mengakses benar-benar admin melalui session admin_id
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login"); 
    exit;
}

$view = isset($_GET['page']) ? $_GET['page'] : 'overview';

// Ambil data admin yang sedang login
$me = $pdo->prepare("SELECT * FROM users WHERE id=?");
$me->execute([$_SESSION['admin_id']]);
$me = $me->fetch();

// --- LOGIC IMPERSONATE (Login as User) ---
if (isset($_POST['impersonate_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_POST['impersonate_id']]);
    $target = $stmt->fetch();
    
    if ($target) {
        // Ganti user_id yang aktif dengan ID target
        $_SESSION['user_id'] = $target['id'];
        $_SESSION['username'] = $target['username'];
        // PENTING: Set role ke target (member/premium) agar dashboard tidak menolak
        $_SESSION['role'] = $target['role']; 
        
        // Tetap simpan $_SESSION['admin_id'] agar Anda tidak logout dari Admin Center
        header("Location: /dashboard?impersonate=true"); 
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Center | Bio by HVM Digital</title>
    <link rel="icon" type="image/png" href="/assets/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-dark: #020b09;
            --white: #ffffff;
            --neon: #a1ff5a;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }
        body { background-color: var(--bg-dark); min-height: 100vh; display: flex; overflow-x: hidden; }

        /* --- MODAL SYSTEM FIX (SUPER TERANG & BISA DIKLIK) --- */
        body.modal-open { overflow: hidden; }
        
        .detail-overlay {
            display: none; 
            position: fixed; 
            inset: 0; 
            background: rgba(0, 0, 0, 0.7); /* Background gelap standar untuk kontras */
            z-index: 10000; 
            align-items: center; 
            justify-content: center; 
            padding: 20px;
            pointer-events: auto; /* Memastikan area modal bisa diklik */
        }

        .detail-box-p {
            background: #ffffff !important; /* Putih Solid */
            width: 100%;
            max-width: 950px;
            max-height: 90vh;
            border-radius: 45px;
            position: relative;
            padding: 45px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.5);
            overflow-y: auto;
            color: #000;
            opacity: 1 !important; /* Pastikan tidak ikut transparan */
            animation: springPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes springPop {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* --- MAIN PANEL --- */
        .main-panel {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            background: var(--white);
            min-height: 100vh;
            padding: 50px;
            color: #000;
            border-radius: 45px 0 0 45px;
            position: relative;
            z-index: 5;
        }

        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .admin-title h1 { font-size: 32px; font-weight: 800; letter-spacing: -1.5px; }

        @media (max-width: 992px) {
            .main-panel { margin-left: 0; border-radius: 0; padding: 100px 20px 40px 20px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar Include -->
    <?php include 'sidebar.php'; ?>

    <main class="main-panel">
        <header class="admin-header">
            <div class="admin-title">
                <small style="color: #94a3b8; font-weight: 800; text-transform: uppercase; font-size: 10px; letter-spacing: 2px;">Admin Center</small>
                <h1><?= ucfirst($view) ?></h1>
            </div>
            <div class="admin-profile" style="text-align: right;">
                <b style="font-size: 14px;"><?= htmlspecialchars($me['fullname']) ?></b><br>
                <span style="font-size: 11px; color: #94a3b8; font-weight: 600;">System Administrator</span>
            </div>
        </header>

        <div class="content-wrapper">
            <?php 
            $path = __DIR__ . "/views/$view.php";
            include(file_exists($path) ? $path : __DIR__ . "/views/overview.php"); 
            ?>
        </div>
    </main>

    <!-- MODAL DETAIL USER (DILUAR MAIN PANEL AGAR TIDAK GELAP) -->
    <div id="userDetailModal" class="detail-overlay" onclick="if(event.target === this) closeDetail()">
        <div class="detail-box-p">
            <div id="modalLoading" style="text-align:center; padding:100px; display:none;">
                <i class="fas fa-circle-notch fa-spin" style="font-size:40px; color:var(--neon);"></i>
            </div>
            <div id="modalContent"></div>
            <button onclick="closeDetail()" style="position:absolute; top:30px; right:30px; border:none; background:#f1f5f9; width:45px; height:45px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.3s; z-index:100;">
                <i class="fas fa-times" style="font-size: 18px; color: #000;"></i>
            </button>
        </div>
    </div>

    <!-- MODAL EDIT PROFILE (DILUAR MAIN PANEL) -->
    <div id="fullEditModal" class="detail-overlay" onclick="if(event.target === this) closeFullEdit()">
        <div class="detail-box-p" style="max-width: 500px;">
            <h3 style="font-weight:900; font-size:24px; margin-bottom:30px;">Edit Account</h3>
            <form method="POST" action="?page=accounts">
                <input type="hidden" name="target_uid" id="fe_id">
                <input type="hidden" name="action_type" value="full_edit">
                <input type="text" name="fullname" id="fe_fullname" style="width:100%; padding:15px; border-radius:15px; border:1px solid #eee; margin-bottom:15px; font-weight:700;" placeholder="Full Name" required>
                <input type="text" name="username" id="fe_username" style="width:100%; padding:15px; border-radius:15px; border:1px solid #eee; margin-bottom:15px; font-weight:700;" placeholder="Username" required>
                <input type="email" name="email" id="fe_email" style="width:100%; padding:15px; border-radius:15px; border:1px solid #eee; margin-bottom:15px; font-weight:700;" placeholder="Email" required>
                <input type="text" name="phone" id="fe_phone" style="width:100%; padding:15px; border-radius:15px; border:1px solid #eee; margin-bottom:15px; font-weight:700;" placeholder="WhatsApp">
                <input type="password" name="password" style="width:100%; padding:15px; border-radius:15px; border:1px solid #eee; margin-bottom:20px; font-weight:700;" placeholder="New Password (Optional)">
                <button type="submit" style="width:100%; padding:18px; background:var(--bg-dark); color:#fff; border:none; border-radius:18px; font-weight:900; cursor:pointer;">SAVE CHANGES</button>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('open'); }

        function showUserDetail(uid) {
            document.body.classList.add('modal-open');
            document.getElementById('userDetailModal').style.display = 'flex';
            document.getElementById('modalLoading').style.display = 'block';
            document.getElementById('modalContent').innerHTML = '';

            fetch(`admin/helpers/get_user_detail.php?id=${uid}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modalLoading').style.display = 'none';
                    document.getElementById('modalContent').innerHTML = data;
                });
        }

        function closeDetail() {
            document.body.classList.remove('modal-open');
            document.getElementById('userDetailModal').style.display = 'none';
        }

        function openFullEdit(u) {
            document.body.classList.add('modal-open');
            document.getElementById('fullEditModal').style.display = 'flex';
            document.getElementById('fe_id').value = u.id;
            document.getElementById('fe_fullname').value = u.fullname;
            document.getElementById('fe_username').value = u.username;
            document.getElementById('fe_email').value = u.email;
            document.getElementById('fe_phone').value = u.phone;
        }

        function closeFullEdit() {
            document.body.classList.remove('modal-open');
            document.getElementById('fullEditModal').style.display = 'none';
        }
    </script>
</body>
</html>