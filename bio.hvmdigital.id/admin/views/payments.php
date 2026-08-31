<?php
/**
 * ==============================================================================
 * HVM STUDIO - PREMIUM PAYMENT ENGINE v11.0 (ULTRA ELITE)
 * ==============================================================================
 * Logic       : Auto-Commission 10%, Role Upgrade, Balance Injector, Bento Stats
 * Database    : Table `payments`, `users`
 * Aesthetics  : Obsidian-Neon Luxury Dashboard
 * ==============================================================================
 */

// --- 1. CONTROLLER: LOGIKA VERIFIKASI ---
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['execute_payment'])) {
    try {
        $pay_id = $_POST['target_pay_id'];
        $action_status = $_POST['status_action']; // 'approved' atau 'rejected'
        $admin_note = htmlspecialchars($_POST['admin_note'] ?? '');

        // Ambil data transaksi & buyer secara mendalam
        $stmt = $pdo->prepare("
            SELECT p.*, u.id as buyer_id, u.referrer_id 
            FROM payments p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$pay_id]);
        $tx = $stmt->fetch();

        if ($tx && $tx['status'] == 'pending') {
            if ($action_status == 'approved') {
                // A. UPDATE STATUS TRANSAKSI & NOTE
                $pdo->prepare("UPDATE payments SET status = 'approved', verified_at = NOW(), admin_note = ? WHERE id = ?")
                    ->execute([$admin_note, $pay_id]);

                // B. KALKULASI DURASI & UPGRADE ROLE
                $duration = $tx['plan_duration']; 
                $days = ($duration == '1_year') ? 365 : (($duration == '6_month') ? 180 : 30);

                // Update Role & Masa Aktif (Stacking Logic)
                $pdo->prepare("
                    UPDATE users SET 
                    role = 'premium', 
                    premium_until = IF(premium_until > NOW(), DATE_ADD(premium_until, INTERVAL $days DAY), DATE_ADD(NOW(), INTERVAL $days DAY)) 
                    WHERE id = ?
                ")->execute([$tx['buyer_id']]);

                // C. SISTEM AFILIASI 10% (INJEKSI SALDO OTOMATIS)
                if (!empty($tx['referrer_id']) && $tx['referrer_id'] > 0) {
                    $commission = (int)($tx['amount'] * 0.10);
                    // Update Saldo pengajak di tabel users
                    $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")
                        ->execute([$commission, $tx['referrer_id']]);
                }
                
                $msg = "success|Verifikasi Berhasil! Akun User Aktif & Saldo Referral Terupdate.";
            } else {
                // LOGIKA REJECT (TOLAK)
                $pdo->prepare("UPDATE payments SET status = 'rejected', verified_at = NOW(), admin_note = ? WHERE id = ?")
                    ->execute([$admin_note, $pay_id]);
                $msg = "warning|Pembayaran telah ditolak. Riwayat tercatat di log Rejected.";
            }
        }
    } catch (Exception $e) {
        $msg = "error|Kegagalan Sistem: " . $e->getMessage();
    }
}

// --- 2. DATA ENGINE (REALTIME STATISTICS) ---
// Perhatikan: Penjumlahan hanya pada status 'approved' agar omzet akurat
$stat_revenue = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'approved'")->fetchColumn() ?: 0;
$stat_pending = $pdo->query("SELECT COUNT(*) FROM payments WHERE status = 'pending'")->fetchColumn() ?: 0;
$stat_rejected = $pdo->query("SELECT COUNT(*) FROM payments WHERE status = 'rejected'")->fetchColumn() ?: 0;

// Filter Tab
$current_filter = $_GET['f_status'] ?? 'pending';

// Query Utama untuk Tabel
$query = "SELECT p.*, u.fullname, u.username, u.email 
          FROM payments p 
          JOIN users u ON p.user_id = u.id 
          WHERE p.status = ? 
          ORDER BY p.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([$current_filter]);
$transactions = $stmt->fetchAll();
?>

<style>
    /* --- ELITE DESIGN SYSTEM --- */
    :root {
        --neon: #a1ff5a;
        --dark: #020b09;
        --white: #ffffff;
        --soft-gray: #f8fafc;
        --border: #f1f5f9;
        --gray-text: #94a3b8;
    }

    .p-wrapper { animation: pSlideUp 0.7s cubic-bezier(0.165, 0.84, 0.44, 1) forwards; }
    @keyframes pSlideUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }

    /* Bento Stats (Grid 3 Col) */
    .bento-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; margin-bottom: 45px; }
    .bento-card { 
        background: var(--white); border-radius: 40px; padding: 35px; border: 1px solid var(--border); 
        display: flex; align-items: center; gap: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.02); transition: 0.4s;
    }
    .bento-card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.06); border-color: var(--neon); }
    .bento-icon { width: 65px; height: 65px; background: var(--dark); color: var(--neon); border-radius: 22px; display: flex; align-items: center; justify-content: center; font-size: 26px; box-shadow: 0 10px 20px rgba(161, 255, 90, 0.2); }
    .bento-info span { font-size: 10px; font-weight: 800; color: var(--gray-text); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 5px; }
    .bento-info b { font-size: 28px; font-weight: 900; color: var(--dark); letter-spacing: -1.5px; line-height: 1; }

    /* Navigation Pills */
    .p-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .p-pills { display: flex; gap: 10px; background: #f1f5f9; padding: 8px; border-radius: 30px; }
    .p-link { padding: 12px 30px; border-radius: 22px; text-decoration: none; font-size: 11px; font-weight: 900; color: var(--gray-text); transition: 0.3s; }
    .p-link.active { background: #fff; color: var(--dark); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    .p-link.active.p-pending { color: #f59e0b; }
    .p-link.active.p-approved { color: #22c55e; }
    .p-link.active.p-rejected { color: #ef4444; }

    /* Elite Premium Table */
    .p-card-table { background: var(--white); border-radius: 50px; padding: 15px; border: 1px solid var(--border); box-shadow: 0 40px 80px rgba(0,0,0,0.04); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 1000px; }
    th { padding: 30px 25px; text-align: left; font-size: 11px; font-weight: 900; color: var(--gray-text); text-transform: uppercase; letter-spacing: 2.5px; border-bottom: 2px solid var(--soft-gray); }
    td { padding: 25px; border-bottom: 1px solid var(--soft-gray); vertical-align: middle; }
    
    .u-meta { display: flex; align-items: center; gap: 18px; }
    .u-initial { width: 50px; height: 50px; background: var(--soft-gray); border: 1px solid #eee; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: var(--dark); font-size: 20px; }
    
    .proof-img-lux { width: 65px; height: 90px; object-fit: cover; border-radius: 15px; cursor: zoom-in; border: 4px solid #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: 0.4s; }
    .proof-img-lux:hover { transform: scale(1.15) rotate(3deg); border-color: var(--neon); }

    .plan-pill { background: var(--dark); color: var(--neon); padding: 7px 18px; border-radius: 12px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px; }
    .amount-bold { font-size: 18px; font-weight: 950; color: #000; letter-spacing: -0.5px; }
    .audit-note { background: var(--soft-gray); padding: 10px 15px; border-radius: 15px; font-size: 11px; color: #64748b; font-weight: 600; line-height: 1.5; max-width: 250px; border: 1px solid #eee; }

    /* Ultra Luxury Modal */
    .hvm-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.96); z-index: 20000; align-items: center; justify-content: center; padding: 25px; backdrop-filter: blur(25px); }
    .hvm-modal-card { 
        background: #ffffff; border-radius: 65px; padding: 60px; width: 100%; max-width: 700px; 
        position: relative; animation: modalEliteSpring 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; 
        box-shadow: 0 60px 120px rgba(0,0,0,0.5);
    }
    @keyframes modalEliteSpring { from { opacity: 0; transform: scale(0.9) translateY(50px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    
    .modal-close-icon { position: absolute; top: 40px; right: 40px; font-size: 35px; cursor: pointer; color: #e2e8f0; transition: 0.3s; }
    .modal-close-icon:hover { color: #000; transform: rotate(90deg); }

    .audit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 40px; }
    .audit-block { background: #f8fafc; padding: 30px; border-radius: 40px; border: 1.5px solid #eee; }
    .audit-block small { color: var(--gray-text); font-weight: 900; font-size: 10px; display: block; text-transform: uppercase; letter-spacing: 2.5px; margin-bottom: 12px; }
    .audit-block b { font-size: 24px; color: #000; display: block; letter-spacing: -1px; }
    
    .note-area { width: 100%; height: 140px; padding: 25px; border-radius: 30px; background: #f8fafc; border: 2px solid #eee; font-weight: 700; font-family: inherit; resize: none; margin-bottom: 35px; transition: 0.3s; font-size: 14px; }
    .note-area:focus { border-color: var(--neon); background: #fff; outline: none; }

    .btn-submit-exec { width: 100%; padding: 25px; border-radius: 35px; border: none; font-weight: 900; font-size: 15px; cursor: pointer; transition: 0.4s; text-transform: uppercase; letter-spacing: 2px; }
    .btn-approve-elite { background: var(--neon); color: #020b09; box-shadow: 0 15px 35px rgba(161, 255, 90, 0.4); }
    .btn-approve-elite:hover { transform: scale(1.03); background: #000; color: #fff; }
    .btn-reject-elite { background: #fef2f2; color: #ef4444; }

    .img-zoom-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.98); z-index: 30000; align-items: center; justify-content: center; cursor: zoom-out; }
</style>

<div class="p-wrapper">
    
    <!-- HEADER STATS -->
    <div class="bento-grid">
        <div class="bento-card">
            <div class="bento-icon"><i class="fas fa-chart-line"></i></div>
            <div class="bento-info">
                <span>Revenue Bersih</span>
                <b>Rp <?= number_format($stat_revenue) ?></b>
            </div>
        </div>
        <div class="bento-card" style="border-color: #f59e0b;">
            <div class="bento-icon" style="background:#f59e0b; color:#fff;"><i class="fas fa-hourglass-half"></i></div>
            <div class="bento-info">
                <span>Konfirmasi Antrean</span>
                <b><?= $stat_pending ?> Transaksi</b>
            </div>
        </div>
        <div class="bento-card">
            <div class="bento-icon"><i class="fas fa-times-circle"></i></div>
            <div class="bento-info">
                <span>Reject / Gagal</span>
                <b><?= $stat_rejected ?> Record</b>
            </div>
        </div>
    </div>

    <!-- TAB FILTERS -->
    <div class="p-nav">
        <div class="p-pills">
            <a href="?page=payments&f_status=pending" class="p-link p-pending <?= $current_filter=='pending'?'active':'' ?>">WAITING LIST</a>
            <a href="?page=payments&f_status=approved" class="p-link p-approved <?= $current_filter=='approved'?'active':'' ?>">COMPLETED</a>
            <a href="?page=payments&f_status=rejected" class="p-link p-rejected <?= $current_filter=='rejected'?'active':'' ?>">REJECTED</a>
        </div>
        <div style="font-size: 11px; font-weight: 900; color: var(--gray-text); letter-spacing: 1px;">
            <i class="fas fa-database"></i> TOTAL: <?= count($transactions) ?> ENTRIES
        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="p-card-table">
        <table>
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Detail Paket</th>
                    <th>Nominal</th>
                    <?php if($current_filter != 'pending'): ?><th>Audit Notes</th><?php endif; ?>
                    <th>Bukti Transfer</th>
                    <th style="text-align:right;">Aksi Kontrol</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transactions as $row): ?>
                <tr>
                    <td>
                        <div class="u-meta">
                            <div class="u-initial"><?= strtoupper(substr($row['username'],0,1)) ?></div>
                            <div>
                                <b style="font-size:16px; display:block;"><?= htmlspecialchars($row['fullname']) ?></b>
                                <span style="font-size:11px; color:var(--gray-text); font-weight:700;">@<?= $row['username'] ?> • <?= date('d/m/y H:i', strtotime($row['created_at'])) ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="plan-pill"><?= str_replace('_', ' ', $row['plan_duration']) ?></span>
                    </td>
                    <td>
                        <div class="amount-bold">Rp <?= number_format($row['amount']) ?></div>
                    </td>
                    <?php if($current_filter != 'pending'): ?>
                    <td>
                        <div class="audit-note"><?= $row['admin_note'] ?: '<i style="opacity:0.5">No notes recorded</i>' ?></div>
                    </td>
                    <?php endif; ?>
                    <td>
                        <img src="/assets/uploads/proofs/<?= $row['proof_img'] ?>" class="proof-img-lux" onclick="zoomIn(this.src)">
                    </td>
                    <td style="text-align:right;">
                        <?php if($row['status'] == 'pending'): ?>
                            <button class="btn-submit-exec btn-approve-elite" style="width:auto; padding:15px 30px; font-size:11px;" onclick='triggerAudit(<?= json_encode($row) ?>)'>
                                AUDIT SYSTEM <i class="fas fa-fingerprint" style="margin-left:8px;"></i>
                            </button>
                        <?php else: ?>
                            <div style="font-weight: 900; font-size: 11px; color: <?= $row['status']=='approved'?'#22c55e':'#ef4444' ?>; letter-spacing: 2px;">
                                <i class="fas <?= $row['status']=='approved'?'fa-check-double':'fa-ban' ?>"></i> <?= strtoupper($row['status']) ?>
                                <?php if($row['verified_at']): ?>
                                    <div style="font-size: 9px; color: var(--gray-text); margin-top: 5px; font-weight:700;"><?= date('d/m/y H:i', strtotime($row['verified_at'])) ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; if(empty($transactions)) echo "<tr><td colspan='6' style='text-align:center; padding:150px; color:var(--gray-text); font-weight:900; font-size:16px;'>KATEGORI INI KOSONG.</td></tr>"; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- AUDIT MODAL -->
<div id="modalAudit" class="hvm-modal" onclick="if(event.target===this) closeAudit()">
    <div class="hvm-modal-card">
        <i class="fas fa-times modal-close-icon" onclick="closeAudit()"></i>
        <h2 style="font-weight:950; font-size:35px; margin-bottom:12px; letter-spacing:-2px;"><i class="fas fa-shield-check text-neon"></i> Payment Audit</h2>
        <p style="color:#64748b; font-size:16px; font-weight:500; margin-bottom:45px;">Verifikasi transaksi masuk di mutasi bank Anda sebelum aktivasi.</p>

        <div class="audit-grid">
            <div class="audit-block">
                <span>Validasi Nominal</span>
                <b id="m_amt" style="font-size: 28px;"></b>
                <div id="m_dur" style="margin-top:12px; font-weight:900; color:var(--neon); background:#000; padding:8px 18px; border-radius:12px; display:inline-block; font-size:11px;"></div>
            </div>
            <div class="audit-block">
                <span>Pemohon / User</span>
                <b id="m_user" style="font-size: 20px;"></b>
                <span id="m_email" style="font-size:13px; opacity:0.6; font-weight:700;"></span>
            </div>
        </div>

        <form method="POST">
            <input type="hidden" name="target_pay_id" id="m_id">
            <input type="hidden" name="execute_payment" value="1">
            
            <label style="font-size:12px; font-weight:900; color:var(--gray-text); text-transform:uppercase; margin-bottom:12px; display:block; letter-spacing:1px;">Admin Verification Notes</label>
            <textarea name="admin_note" class="note-area" placeholder="Contoh: Ref BCA-99210 atau Alasan penolakan..."></textarea>
            
            <div style="display:grid; grid-template-columns: 1.8fr 1fr; gap:18px;">
                <button type="submit" name="status_action" value="approved" class="btn-submit-exec btn-approve-elite">APPROVE & ACTIVATE PRO <i class="fas fa-crown" style="margin-left:12px;"></i></button>
                <button type="submit" name="status_action" value="rejected" class="btn-submit-exec btn-reject-elite">REJECT TX</button>
            </div>
        </form>
    </div>
</div>

<!-- ZOOM VIEWER -->
<div id="zoomBox" class="img-zoom-overlay" onclick="this.style.display='none'">
    <img src="" id="zoomImg" style="max-height: 92vh; border-radius:20px; box-shadow: 0 0 60px rgba(161,255,90,0.3);">
</div>

<script>
    function triggerAudit(data) {
        document.body.style.overflow = 'hidden';
        document.getElementById('modalAudit').style.display = 'flex';
        document.getElementById('m_id').value = data.id;
        document.getElementById('m_amt').innerText = "Rp " + parseInt(data.amount).toLocaleString();
        document.getElementById('m_dur').innerText = data.plan_duration.replace('_', ' ').toUpperCase();
        document.getElementById('m_user').innerText = data.fullname;
        document.getElementById('m_email').innerText = data.email;
    }
    function closeAudit() { document.getElementById('modalAudit').style.display = 'none'; document.body.style.overflow = 'auto'; }
    function zoomIn(src) { document.getElementById('zoomImg').src = src; document.getElementById('zoomBox').style.display = 'flex'; }

    <?php if(!empty($msg)): 
        $m = explode('|', $msg); ?>
        Swal.fire({ icon: '<?= $m[0] ?>', title: 'Audit Result', text: '<?= $m[1] ?>', confirmButtonColor: '#020b09', borderRadius: '35px' });
    <?php endif; ?>
</script>