<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config.php';

// --- 1. REDIRECT JIKA SUDAH LOGIN ---
if (isset($_SESSION['user_id']) && !isset($_GET['route'])) {
    // Jika dia admin (punya admin_id), arahkan ke admin center
    if (isset($_SESSION['admin_id'])) {
        header("Location: /admincenter");
    } else {
        header("Location: /dashboard");
    }
    exit;
}

$swal_icon = ''; $swal_title = ''; $swal_text = '';
$reff_val = isset($_GET['reff']) ? htmlspecialchars($_GET['reff']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // --- 2. PROSES REGISTER ---
    if (isset($_POST['register'])) {
        $fullname = trim($_POST['fullname']);
        $email    = trim($_POST['email']);
        $phone    = trim($_POST['phone']);
        $username = strtolower(str_replace(' ', '', trim($_POST['username'])));
        $password = $_POST['password'];
        $reff_code = trim($_POST['referral_code']);

        $cek = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $cek->execute([$username, $email]);

        if ($cek->rowCount() > 0) {
            $swal_icon = 'error'; $swal_title = 'Opss!'; $swal_text = 'Username atau Email sudah terdaftar.';
        } else {
            $referrer_id = null;
            if (!empty($reff_code)) {
                $get_reff = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                $get_reff->execute([$reff_code]);
                $reff_data = $get_reff->fetch();
                $referrer_id = $reff_data ? $reff_data['id'] : null;
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            // Default role saat daftar adalah 'member'
            $stmt = $pdo->prepare("INSERT INTO users (fullname, username, email, phone, password, role, referrer_id, affiliate_code) VALUES (?, ?, ?, ?, ?, 'member', ?, ?)");
            if ($stmt->execute([$fullname, $username, $email, $phone, $hash, $referrer_id, $username])) {
                header("Location: /login?reg_success=1"); 
                exit;
            }
        }
    }

    // --- 3. PROSES LOGIN (Bisa Username / Email) ---
    if (isset($_POST['login'])) {
        $identity = trim($_POST['email']); // Input bisa berisi email atau username
        $password = $_POST['password'];

        // Cek email ATAU username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$identity, $identity]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // SET SESSION UTAMA
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Jika dia Admin, berikan identitas Admin terpisah agar tidak logout
            if ($user['role'] === 'admin') {
                $_SESSION['admin_id'] = $user['id'];
                header("Location: /admincenter");
            } else {
                // User biasa, pastikan admin_id kosong
                unset($_SESSION['admin_id']);
                header("Location: /dashboard?welcome=1");
            }
            exit;
        } else {
            $swal_icon = 'error'; $swal_title = 'Gagal'; $swal_text = 'Username/Email atau Password salah.';
        }
    }
}

if (isset($_GET['reg_success'])) {
    $swal_icon = 'success'; $swal_title = 'Berhasil!'; $swal_text = 'Akun anda siap digunakan.';
}
$page = (isset($_GET['route']) && $_GET['route'] == 'register') ? 'register' : 'login';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucfirst($page) ?> | HVM STUDIO</title>
    <link rel="icon" type="image/png" href="/assets/images/logo.png">
    <link rel="stylesheet" href="/dashboard/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* --- LOGO ANIMATION (SHIMMER) --- */
        .logo-container {
            position: relative;
            display: inline-block;
            overflow: hidden;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .logo-img {
            display: block;
            max-width: 180px; /* Ukuran bisa disesuaikan sesuai logo asli */
            height: auto;
        }
        .logo-container::after {
            content: "";
            position: absolute;
            top: 0; left: -150%;
            width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.4), transparent);
            transform: skewX(-25deg);
            animation: shimmer 3s infinite;
        }
        @keyframes shimmer {
            100% { left: 150%; }
        }

        /* --- REFERRAL LOCKED STYLE --- */
        .locked-input {
            background: rgba(161, 255, 90, 0.05) !important;
            border-color: #a1ff5a !important;
            color: #a1ff5a !important;
            cursor: not-allowed;
            font-weight: 800;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="glass-card">
        <div class="logo-area">
            <!-- LOGO DENGAN ANIMASI KILAU -->
            <div class="logo-container">
                <img src="assets/images/logo.png" alt="HVM STUDIO" class="logo-img">
            </div>
        </div>

        <form method="POST" autocomplete="off">
            <?php if($page == 'register'): ?>
                <div class="input-group">
                    <label><i class="fas fa-user-tag"></i> Nama Lengkap</label>
                    <input type="text" name="fullname" placeholder="Jokowee" required>
                </div>
                <div class="input-grid">
                    <div class="input-group">
                        <label><i class="fas fa-at"></i> Username</label>
                        <input type="text" name="username" oninput="this.value = this.value.replace(/\s/g, '').toLowerCase()" placeholder="Jokowee" required>
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-phone"></i> WhatsApp</label>
                        <input type="number" name="phone" placeholder="0812..." required>
                    </div>
                </div>
                
                <!-- KOLOM REFERRAL OTOMATIS -->
                <div class="input-group highlight">
                    <label><i class="fas fa-gift"></i> Kode Referral (Opsional)</label>
                    <input type="text" 
                           name="referral_code" 
                           id="reffInput"
                           value="<?= $reff_val ?>" 
                           placeholder="Username pengajak"
                           <?= !empty($reff_val) ? 'readonly class="locked-input"' : '' ?>
                    >
                    <?php if(!empty($reff_val)): ?>
                    <small style="color:#a1ff5a; font-size:10px; font-weight:800; margin-top:5px; display:block;">
                        <i class="fas fa-check-circle"></i> Terhubung dengan pengajak
                    </small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="input-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" placeholder="emailmu@gmail.com" required>
            </div>

            <div class="input-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <div class="pass-wrapper">
                    <input type="password" name="password" id="passInput" placeholder="••••••••" required>
                    <i class="fas fa-eye" onclick="togglePassword()"></i>
                </div>
            </div>

            <?php if($page == 'login'): ?>
            <div class="extra-actions">
                <label class="checkbox-container">Ingat Saya
                    <input type="checkbox" name="remember">
                    <span class="checkmark"></span>
                </label>
                <a href="javascript:void(0)" onclick="forgotPass()" class="forgot-link">Lupa Password?</a>
            </div>
            <?php endif; ?>

            <button type="submit" name="<?= $page ?>" class="btn-primary">
                <?= $page == 'login' ? 'SIGN IN' : 'CREATE ACCOUNT' ?>
                <i class="fas fa-chevron-right"></i>
            </button>
        </form>

        <div class="auth-footer">
            <?= $page == 'login' ? 'Belum punya akun?' : 'Sudah punya akun?' ?>
            <a href="/<?= $page == 'login' ? 'register' : 'login' ?>">
                <?= $page == 'login' ? 'Daftar Sekarang' : 'Masuk Disini' ?>
            </a>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const i = document.getElementById('passInput');
        i.type = i.type === "password" ? "text" : "password";
    }
    
    // Mencegah penghapusan jika readonly lewat JS (Double Protection)
    const reffInput = document.getElementById('reffInput');
    if(reffInput && reffInput.readOnly) {
        reffInput.addEventListener('keydown', function(e) {
            if (e.key === "Backspace" || e.key === "Delete") {
                e.preventDefault();
            }
        });
    }

    function forgotPass() {
        Swal.fire({
            title: 'Lupa Password?',
            text: 'Silahkan hubungi Admin via WhatsApp untuk reset password akun Anda.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Chat Admin',
            confirmButtonColor: '#a1ff5a',
            cancelButtonText: 'Tutup'
        }).then((result) => {
            if (result.isConfirmed) { window.open('https://wa.me/628123456789?text=Halo%20Admin,%20saya%20lupa%20password%20akun%20BioLink'); }
        });
    }

    <?php if(!empty($swal_icon)): ?>
    Swal.fire({ 
        icon: '<?= $swal_icon ?>', 
        title: '<?= $swal_title ?>', 
        text: '<?= $swal_text ?>', 
        background: '#020b09', 
        color: '#fff',
        confirmButtonColor: '#a1ff5a'
    });
    <?php endif; ?>
</script>
</body>
</html>