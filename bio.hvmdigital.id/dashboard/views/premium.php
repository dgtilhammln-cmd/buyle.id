<?php
/**
 * ==============================================================================
 * HVM STUDIO - PREMIUM & AFFILIATE ENGINE v13.0 (FINAL FIX)
 * ==============================================================================
 * Database Fix : Sinkronisasi Kolom withdrawals (bank_name, account_number, account_holder)
 * Logic        : Post-Redirect-Get (Anti Blank), Debugging Enabled
 * ==============================================================================
 */

// 1. SYSTEM INIT (ANTI BLANK)
ob_start(); 
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// Error Reporting (Hanya aktifkan jika masih blank, untuk debug)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Load Config (Pastikan path ini benar di server Anda)
// require_once __DIR__ . '/../../config.php'; 

// Cek Session
$uid = $_SESSION['user_id'] ?? null;
if (!$uid) { header("Location: /login"); exit; }

// --- 2. CONTROLLER: PAYMENT & WITHDRAWAL ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // A. HANDLE WITHDRAWAL (FIXED COLUMN NAMES)
    if (isset($_POST['submit_withdraw'])) {
        try {
            $balance = (int)$me['balance'];
            
            // Tangkap Input
            $bank_name = htmlspecialchars($_POST['wd_bank']);
            $acc_num   = htmlspecialchars($_POST['wd_number']);
            $acc_name  = htmlspecialchars($_POST['wd_name']);

            // Validasi Server
            if ($balance < 100000) throw new Exception("Saldo minimal Rp 100.000");

            // SQL INSERT (DISESUAIKAN DENGAN SCREENSHOT ANDA)
            $sql = "INSERT INTO withdrawals (user_id, amount, bank_name, account_number, account_holder, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
            
            $stmt = $pdo->prepare($sql);
            
            // Eksekusi
            if ($stmt->execute([$uid, $balance, $bank_name, $acc_num, $acc_name])) {
                // Potong Saldo
                $pdo->prepare("UPDATE users SET balance = 0 WHERE id = ?")->execute([$uid]);
                
                header("Location: ?page=premium&notif=wd_success");
                exit;
            } else {
                throw new Exception("Gagal menyimpan ke database.");
            }

        } catch (Exception $e) {
            header("Location: ?page=premium&notif=error&msg=" . urlencode($e->getMessage()));
            exit;
        }
    }

    // B. HANDLE PAYMENT UPGRADE
    if (isset($_POST['submit_payment'])) {
        try {
            $duration = $_POST['plan_duration']; 
            $prices   = ['1_month' => 59000, '6_month' => 299000, '1_year' => 499000];
            $amount   = $prices[$duration] ?? 0;

            if (!empty($_FILES['proof_file']['name'])) {
                $ext = strtolower(pathinfo($_FILES['proof_file']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    
                    // Gunakan ROOT path agar tidak salah folder
                    $upload_root = $_SERVER['DOCUMENT_ROOT'] . "/assets/uploads/proofs/";
                    if (!is_dir($upload_root)) { mkdir($upload_root, 0777, true); }

                    $filename = "PAY_" . $uid . "_" . date('YmdHis') . "." . $ext;
                    
                    if (move_uploaded_file($_FILES['proof_file']['tmp_name'], $upload_root . $filename)) {
                        $sql = "INSERT INTO payments (user_id, amount, plan_name, status, created_at, proof_img, plan_duration) 
                                VALUES (?, ?, 'Pro Elite Member', 'pending', NOW(), ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$uid, $amount, $filename, $duration]);
                        
                        header("Location: ?page=premium&notif=payment_success");
                        exit;
                    }
                }
            }
            throw new Exception("Gagal upload bukti transfer.");
        } catch (Exception $e) {
            header("Location: ?page=premium&notif=error&msg=" . urlencode($e->getMessage()));
            exit;
        }
    }
}

// --- 3. VIEW DATA ---
// Status Pembayaran Terakhir
$stmt = $pdo->prepare("SELECT * FROM payments WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$uid]);
$last_pay = $stmt->fetch();

$is_pending  = ($last_pay && $last_pay['status'] == 'pending');
$is_approved = ($me['role'] == 'premium' || $me['role'] == 'admin');

// Data Affiliate
$stmt_reff = $pdo->prepare("SELECT COUNT(*) FROM users WHERE referrer_id = ?");
$stmt_reff->execute([$uid]);
$total_reff = $stmt_reff->fetchColumn();
$aff_link = "https://bio.hvmdigital.id/register?reff=" . ($me['affiliate_code'] ?? 'join');
?>

<style>
    /* --- ELITE CSS SYSTEM (V8.0) --- */
    :root {
        --neon: #a1ff5a;
        --obsidian: #020b09;
        --soft-bg: #f8fafc;
        --border: #f1f5f9;
        --text-dim: #94a3b8;
    }

    .p-wrapper { max-width: 1100px; margin: 0 auto; padding: 20px; animation: fadeIn 0.6s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Header */
    .p-head { text-align: center; margin-bottom: 50px; }
    .p-head h1 { font-size: 36px; font-weight: 900; color: var(--obsidian); letter-spacing: -1.5px; }
    .p-head p { font-size: 15px; color: var(--text-dim); }

    /* Alert Box */
    .status-alert {
        background: var(--obsidian); color: #fff; padding: 20px 30px; border-radius: 25px;
        display: flex; align-items: center; gap: 20px; margin-bottom: 35px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1); border: 1px solid rgba(161, 255, 90, 0.3);
    }
    .status-alert i { font-size: 24px; color: var(--neon); }
    
    /* Grid System */
    .p-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; align-items: stretch; }

    /* Cards */
    .p-card { 
        background: #fff; border-radius: 40px; padding: 45px 35px; border: 1px solid var(--border); 
        display: flex; flex-direction: column; transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); 
        position: relative; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.03);
    }
    .p-card:hover { transform: translateY(-10px); box-shadow: 0 40px 80px rgba(161, 255, 90, 0.15); border-color: var(--neon); }

    .p-card.pro { background: var(--obsidian); color: #fff; border: 1px solid rgba(161,255,90,0.4); }
    .p-card.pro::after { 
        content: 'ELITE'; position: absolute; top: 25px; right: -35px; 
        background: var(--neon); color: #000; padding: 6px 45px; transform: rotate(45deg); font-size: 10px; font-weight: 900; 
    }

    .p-card.affiliate { background: linear-gradient(135deg, var(--neon) 0%, #4efdc4 100%); color: var(--obsidian); border: none; }

    /* Text & Lists */
    .p-label { font-size: 10px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 25px; opacity: 0.6; display: block; }
    .p-card.pro .p-label { color: var(--neon); opacity: 1; }
    .p-price { font-size: 46px; font-weight: 950; letter-spacing: -2px; margin-bottom: 5px; }
    .p-price small { font-size: 16px; opacity: 0.6; font-weight: 700; letter-spacing: 0; }

    .p-list { list-style: none; padding: 0; margin-bottom: 40px; flex-grow: 1; }
    .p-list li { font-size: 14px; font-weight: 600; margin-bottom: 15px; display: flex; align-items: center; gap: 12px; }
    .p-list i { font-size: 14px; color: var(--neon); }
    .p-card:not(.pro) .p-list i { color: var(--obsidian); opacity: 0.2; }

    /* Buttons */
    .btn-lux { width: 100%; padding: 20px; border-radius: 25px; border: none; font-weight: 900; font-size: 13px; cursor: pointer; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px; }
    .btn-neon { background: var(--neon); color: #000; box-shadow: 0 10px 25px rgba(161, 255, 90, 0.3); }
    .btn-neon:hover { transform: scale(1.03); background: #fff; }
    .btn-dark { background: var(--obsidian); color: #fff; }
    .btn-wait { background: #f1f5f9; color: #cbd5e1; cursor: not-allowed; }

    /* Affiliate UI */
    .aff-copy { background: rgba(255,255,255,0.4); border-radius: 20px; padding: 15px; display: flex; align-items: center; justify-content: space-between; border: 1px solid rgba(255,255,255,0.4); margin-bottom: 25px; }
    .aff-copy code { font-size: 13px; font-weight: 800; color: var(--obsidian); }
    .aff-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 30px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.08); }

    /* Modals */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 10000; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(20px); }
    .modal-box { background: #fff; border-radius: 50px; padding: 50px; width: 100%; max-width: 500px; position: relative; animation: modalPop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    @keyframes modalPop { from { opacity: 0; transform: scale(0.9) translateY(40px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .close-btn { position: absolute; top: 35px; right: 35px; font-size: 28px; cursor: pointer; color: #cbd5e1; }

    .input-field { width: 100%; padding: 18px 25px; border-radius: 20px; border: 2px solid #eee; background: #f8fafc; font-weight: 700; margin-bottom: 12px; transition: 0.3s; font-family: inherit; }
    .input-field:focus { border-color: var(--neon); background: #fff; outline: none; }

    .plan-sel { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin: 30px 0; }
    .plan-opt { border: 2px solid #f1f5f9; border-radius: 20px; padding: 15px 5px; text-align: center; cursor: pointer; transition: 0.3s; }
    .plan-opt input { display: none; }
    .plan-opt:has(input:checked) { border-color: var(--neon); background: #f0fdf4; transform: scale(1.05); }

    @media (max-width: 992px) { .p-grid { grid-template-columns: 1fr; } }
</style>

<div class="p-wrapper">
    <div class="p-head">
        <h1>Elite Branding Hub</h1>
        <p>Hanya <b>Rp 2.000/hari</b> untuk fitur tanpa batas.</p>
    </div>

    <!-- PENDING ALERT -->
    <?php if($is_pending): ?>
    <div class="status-alert">
        <i class="fas fa-clock fa-spin"></i>
        <div>
            <b style="color:var(--neon); letter-spacing:1px; display:block; margin-bottom:2px;">MENUNGGU VALIDASI ADMIN</b>
            <span style="font-size:12px; opacity:0.8;">Data Anda telah diterima. Mohon tunggu 1x24 jam untuk aktivasi.</span>
        </div>
    </div>
    <?php endif; ?>

    <div class="p-grid">
        <!-- STARTER -->
        <div class="p-card">
            <span class="p-label">Standard</span>
            <div class="p-price">Rp 0</div>
            <span style="font-size:13px; font-weight:700; opacity:0.5; margin-bottom:30px;">Gratis Selamanya</span>
            <ul class="p-list">
                <li><i class="fas fa-check-circle"></i> 2 Custom Buttons</li>
                <li><i class="fas fa-check-circle"></i> 3 Video TikTok</li>
                <li style="opacity:0.3"><i class="fas fa-lock"></i> Experience Card</li>
            </ul>
            <button class="btn-lux btn-wait">PAKET AKTIF</button>
        </div>

        <!-- PRO -->
        <div class="p-card pro">
            <span class="p-label">Most Popular</span>
            <div class="p-price">Rp 2k<small>/ Hari</small></div>
            <span style="font-size:13px; font-weight:700; color:var(--neon); margin-bottom:30px;">Branding Ultimate</span>
            <ul class="p-list">
                <li><i class="fas fa-bolt"></i> 10 Custom Buttons</li>
                <li><i class="fas fa-bolt"></i> 10 TikTok Portrait</li>
                <li><i class="fas fa-bolt"></i> Experience Landscape</li>
                <li><i class="fas fa-bolt"></i> 3 Theme Premium</li>
                <li><i class="fas fa-bolt"></i> Verified Badge</li>
            </ul>
            <?php if($is_approved): ?>
                <button class="btn-lux btn-neon" style="background:#fff">ELITE MEMBER</button>
            <?php elseif($is_pending): ?>
                <button class="btn-lux btn-wait">PROSES VALIDASI</button>
            <?php else: ?>
                <button class="btn-lux btn-neon" onclick="openModal('payModal')">UPGRADE SEKARANG</button>
            <?php endif; ?>
        </div>

        <!-- AFFILIATE -->
        <div class="p-card affiliate">
            <span class="p-label">Revenue Share</span>
            <div class="p-price">10%</small></div>
            <span style="font-size:13px; font-weight:700; opacity:0.7; margin-bottom:30px;">Komisi Referral</span>
            
            <div class="aff-copy">
                <code id="affCode"><?= $me['affiliate_code'] ?></code>
                <button onclick="copyAff()" style="background:var(--obsidian); color:#fff; border:none; padding:8px 15px; border-radius:12px; font-size:10px; font-weight:900; cursor:pointer;">COPY</button>
            </div>
            
            <div class="aff-stats">
                <div><span style="display:block; font-size:9px; opacity:0.5; font-weight:800; letter-spacing:1px;">SALDO</span><b style="font-size:22px; font-weight:900; color:var(--obsidian);">Rp <?= number_format($me['balance']) ?></b></div>
                <div style="text-align:right;"><span style="display:block; font-size:9px; opacity:0.5; font-weight:800; letter-spacing:1px;">AJAKAN</span><b style="font-size:22px; font-weight:900; color:var(--obsidian);"><?= $total_reff ?></b></div>
            </div>
            
            <button class="btn-lux btn-dark" onclick="validateWithdraw()">CAIRKAN SALDO</button>
        </div>
    </div>
</div>

<!-- MODAL PAYMENT -->
<div class="modal-overlay" id="payModal">
    <div class="modal-box">
        <i class="fas fa-times close-btn" onclick="closeModal('payModal')"></i>
        <h2 style="font-weight:900; font-size:26px; margin-bottom:5px;">Aktivasi Elite</h2>
        <p style="color:#94a3b8; font-size:14px; margin-bottom:30px;">Transfer & lampirkan bukti pembayaran.</p>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="plan-sel">
                <label class="plan-opt"><input type="radio" name="plan_duration" value="1_month" checked><b>1 Bulan</b><br><span style="font-size:10px">Rp 59k</span></label>
                <label class="plan-opt"><input type="radio" name="plan_duration" value="6_month"><b>6 Bulan</b><br><span style="font-size:10px">Rp 299k</span></label>
                <label class="plan-opt"><input type="radio" name="plan_duration" value="1_year"><b>1 Tahun</b><br><span style="font-size:10px">Rp 499k</span></label>
            </div>
            
            <div style="text-align:center; padding:20px; background:#f8fafc; border-radius:20px; margin-bottom:20px; border:1px solid #eee;">
                <span style="font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:1px;">REKENING BCA</span>
                <b style="font-size:24px; display:block; color:#000;">8660307410</b>
                <span style="font-size:12px; font-weight:700;">a.n Ilham Maulana</span>
            </div>
            
            <input type="file" name="proof_file" class="input-field" required accept="image/*" style="border:2px dashed #cbd5e1; cursor:pointer;">
            <button type="submit" name="submit_payment" class="btn-lux btn-neon">KONFIRMASI PEMBAYARAN</button>
        </form>
    </div>
</div>

<!-- MODAL WITHDRAW -->
<div class="modal-overlay" id="wdModal">
    <div class="modal-box">
        <i class="fas fa-times close-btn" onclick="closeModal('wdModal')"></i>
        <h2 style="font-weight:900; font-size:26px; margin-bottom:5px;">Withdrawal</h2>
        <p style="color:#94a3b8; font-size:14px; margin-bottom:30px;">Dana dikirim maks 1x24 jam.</p>
        
        <form method="POST">
            <input type="text" name="wd_bank" class="input-field" placeholder="Nama Bank (BCA/DANA/GOPAY)" required>
            <input type="number" name="wd_number" class="input-field" placeholder="Nomor Rekening" required>
            <input type="text" name="wd_name" class="input-field" placeholder="Atas Nama" required>
            <div style="background:#fff9db; padding:15px; border-radius:15px; margin-bottom:25px;">
                <p style="margin:0; font-size:11px; font-weight:700; color:#856404;">Anda akan menarik seluruh saldo: Rp <?= number_format($me['balance']) ?></p>
            </div>
            <button type="submit" name="submit_withdraw" class="btn-lux btn-dark">AJUKAN PENCAIRAN</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Modal Helpers
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    
    // Copy Affiliate
    function copyAff() {
        navigator.clipboard.writeText("<?= $aff_link ?>");
        Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Link Disalin!', showConfirmButton:false, timer:1500 });
    }

    // Validate Withdraw
    function validateWithdraw() {
        const bal = <?= (int)$me['balance'] ?>;
        if (bal < 100000) {
            Swal.fire({ title:'Saldo Kurang', text:'Minimal pencairan Rp 100.000', icon:'warning', confirmButtonColor:'#020b09' });
        } else { openModal('wdModal'); }
    }

    // Handle Notifications from PHP Redirect
    window.addEventListener('load', () => {
        const p = new URLSearchParams(window.location.search);
        if(p.get('notif') === 'payment_success') {
            Swal.fire({ icon:'success', title:'Berhasil Terkirim!', text:'Data pembayaran telah diterima. Tunggu validasi admin.', confirmButtonColor:'#a1ff5a' });
        }
        if(p.get('notif') === 'wd_success') {
            Swal.fire({ icon:'success', title:'Penarikan Diproses!', text:'Saldo akan segera dikirim ke rekening Anda.', confirmButtonColor:'#020b09' });
        }
        if(p.get('notif') === 'error') {
            Swal.fire({ icon:'error', title:'Gagal!', text: p.get('msg') || 'Terjadi kesalahan sistem.', confirmButtonColor:'#d33' });
        }
    });
</script>