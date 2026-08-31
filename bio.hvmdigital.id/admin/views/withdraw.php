<?php
/**
 * ==============================================================================
 * HVM STUDIO - WITHDRAWAL MANAGEMENT ENGINE v11.1 (FINAL FIX)
 * ==============================================================================
 * Connection  : Table `withdrawals`, `users`, `settings`
 * Logic       : Bulk Audit, Auto-Refund on Reject, Threshold Logic, Bento Stats
 * Style       : Obsidian-Neon Compact Luxury
 * ==============================================================================
 */

// --- 1. CONTROLLER: LOGIKA PENCAIRAN (SINGLE / BULK) ---
$msg_wd = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['execute_withdraw_action'])) {
    try {
        $ids = isset($_POST['wd_ids']) ? $_POST['wd_ids'] : [$_POST['target_wd_id']];
        $target_status = $_POST['status_to_set']; // 'approved' atau 'rejected'
        $admin_notes = htmlspecialchars($_POST['admin_notes'] ?? '');

        foreach ($ids as $id) {
            // Ambil data WD lama untuk pengecekan dan pengembalian saldo
            $stmt_check = $pdo->prepare("SELECT user_id, amount, status FROM withdrawals WHERE id = ?");
            $stmt_check->execute([$id]);
            $wd_row = $stmt_check->fetch();

            if ($wd_row && $wd_row['status'] == 'pending') {
                
                // A. UPDATE STATUS PENARIKAN
                $upd = $pdo->prepare("UPDATE withdrawals SET status = ?, admin_notes = ?, processed_at = NOW() WHERE id = ?");
                $upd->execute([$target_status, $admin_notes, $id]);

                // B. LOGIKA AUTO-REFUND (PENGEMBALIAN SALDO)
                if ($target_status == 'rejected') {
                    $refund = (int)$wd_row['amount'];
                    $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")
                        ->execute([$refund, $wd_row['user_id']]);
                }
            }
        }
        $msg_wd = "success|Permintaan berhasil diproses! Data telah berpindah tab.";
    } catch (Exception $e) {
        $msg_wd = "error|Kegagalan Sistem: " . $e->getMessage();
    }
}

// --- 2. CONTROLLER: UPDATE AMBANG BATAS MINIMAL ---
if (isset($_POST['save_threshold'])) {
    $min_val = (int)$_POST['min_val'];
    $pdo->prepare("REPLACE INTO settings (setting_key, setting_value) VALUES ('min_withdrawal', ?)")
        ->execute([$min_val]);
    $msg_wd = "success|Kebijakan Ambang Batas Penarikan Berhasil Diperbarui.";
}

// --- 3. FETCH DATA ENGINE ---
$current_filter = $_GET['f_status'] ?? 'pending';

// Statistik Realtime (Bento Box)
$total_pending = $pdo->query("SELECT SUM(amount) FROM withdrawals WHERE status = 'pending'")->fetchColumn() ?: 0;
$total_paid = $pdo->query("SELECT SUM(amount) FROM withdrawals WHERE status = 'approved'")->fetchColumn() ?: 0;
$min_threshold = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'min_withdrawal'")->fetchColumn() ?: 100000;

// Query Data Berdasarkan Tab Filter (Fix Column Names)
// Pastikan kolom bank_name, account_number, account_holder ada di tabel withdrawals
$sql_main = "SELECT w.*, u.fullname, u.username, u.email 
             FROM withdrawals w 
             JOIN users u ON w.user_id = u.id 
             WHERE w.status = ? 
             ORDER BY w.id DESC";
$stmt_main = $pdo->prepare($sql_main);
$stmt_main->execute([$current_filter]);
$withdrawal_list = $stmt_main->fetchAll();
?>

<!-- --- ENGINE STYLES (ULTRA LUXURY) --- -->
<style>
    /* Global View Framework */
    .wd-elite-page { animation: wdEnter 0.7s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes wdEnter { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    /* Bento Grid Stats */
    .bento-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
    .bento-item { 
        background: #fff; border-radius: 40px; padding: 30px; border: 1px solid #f1f5f9; 
        display: flex; align-items: center; gap: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: 0.4s;
    }
    .bento-item:hover { transform: translateY(-8px); border-color: var(--neon); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
    .bento-icon { width: 60px; height: 60px; background: var(--bg-dark); color: var(--neon); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 10px 20px rgba(161, 255, 90, 0.2); }
    .bento-val b { font-size: 24px; font-weight: 950; color: var(--bg-dark); display: block; letter-spacing: -1px; line-height: 1.2; }
    .bento-val span { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; }

    /* Tab Controller */
    .nav-control-wrapper { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .nav-pills-lux { display: flex; gap: 8px; background: #f1f5f9; padding: 6px; border-radius: 25px; }
    .nav-pill-btn { padding: 12px 30px; border-radius: 20px; text-decoration: none; font-size: 11px; font-weight: 900; color: #94a3b8; transition: 0.3s; }
    .nav-pill-btn.active { background: #fff; color: var(--bg-dark); box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    .nav-pill-btn.active.tab-pending { color: #f59e0b; }
    .nav-pill-btn.active.tab-approved { color: #22c55e; }
    .nav-pill-btn.active.tab-rejected { color: #ef4444; }

    /* Table System LUX */
    .table-lux-card { background: #fff; border-radius: 50px; padding: 15px; border: 1px solid #f1f5f9; box-shadow: 0 35px 70px rgba(0,0,0,0.03); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 1050px; }
    th { padding: 30px 25px; text-align: left; font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2.5px; border-bottom: 2px solid #f8fafc; }
    td { padding: 25px; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
    
    .u-meta { display: flex; align-items: center; gap: 18px; }
    .u-avatar { width: 45px; height: 45px; background: #f8fafc; border: 1px solid #eee; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-weight: 950; color: var(--bg-dark); font-size: 16px; }
    
    .method-badge { background: #f1f5f9; padding: 6px 15px; border-radius: 10px; font-size: 11px; font-weight: 900; color: #475569; border: 1px solid #e2e8f0; text-transform: uppercase; display: inline-block; margin-bottom: 5px; }
    .acc-number { font-size: 15px; font-weight: 800; color: #000; letter-spacing: 1px; display: block; }
    .acc-holder { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-top: 5px; }

    .amount-lux { background: var(--bg-dark); color: var(--neon); padding: 8px 18px; border-radius: 12px; font-size: 16px; font-weight: 950; display: inline-block; letter-spacing: -0.5px; }

    .note-bubble { background: #f8fafc; border: 1px solid #eee; padding: 12px 18px; border-radius: 18px; font-size: 11px; color: #64748b; line-height: 1.5; font-style: italic; max-width: 250px; }

    /* Bulk Action Floating Bar */
    #bulkBar { 
        display: none; position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); 
        background: var(--bg-dark); padding: 15px 40px; border-radius: 100px; 
        z-index: 10005; box-shadow: 0 40px 80px rgba(0,0,0,0.6); align-items: center; gap: 40px; 
        border: 1px solid rgba(255,255,255,0.15); animation: springUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes springUp { from { transform: translate(-50%, 100px); opacity: 0; } to { transform: translate(-50%, 0); opacity: 1; } }
    
    .btn-bulk-exec { padding: 12px 28px; border-radius: 100px; border: none; font-weight: 950; font-size: 12px; cursor: pointer; transition: 0.3s; text-transform: uppercase; }

    /* Luxury Modals */
    .modal-lux-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.95); z-index: 20000; align-items: center; justify-content: center; padding: 25px; backdrop-filter: blur(20px); }
    .modal-lux-card { 
        background: #ffffff; border-radius: 60px; padding: 60px; width: 100%; max-width: 650px; 
        position: relative; animation: modalEliteSlide 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; 
    }
    @keyframes modalEliteSlide { from { opacity: 0; transform: scale(0.9) translateY(40px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    
    .modal-lux-close { position: absolute; top: 40px; right: 40px; font-size: 30px; cursor: pointer; color: #cbd5e1; transition: 0.3s; }
    .modal-lux-close:hover { color: #000; transform: rotate(90deg); }

    .audit-grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 20px; margin-bottom: 35px; }
    .audit-box { background: #f8fafc; padding: 30px; border-radius: 35px; border: 1.5px solid #eee; }
    .audit-box small { color: #94a3b8; font-weight: 900; font-size: 9px; display: block; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px; }
    .audit-box b { font-size: 22px; color: #000; display: block; letter-spacing: -0.5px; }
    
    .input-lux-area { width: 100%; height: 120px; padding: 25px; border-radius: 25px; background: #f8fafc; border: 2px solid #eee; font-weight: 700; font-family: inherit; resize: none; margin-bottom: 30px; transition: 0.3s; }
    .input-lux-area:focus { border-color: var(--neon); background: #fff; outline: none; }

    .btn-lux-exec { width: 100%; padding: 22px; border-radius: 30px; border: none; font-weight: 900; font-size: 14px; cursor: pointer; transition: 0.4s; text-transform: uppercase; letter-spacing: 2px; }
    .btn-lux-approve { background: var(--neon); color: #020b09; box-shadow: 0 15px 35px rgba(161, 255, 90, 0.4); }
    .btn-lux-reject { background: #fef2f2; color: #ef4444; margin-top: 15px; }

    @media (max-width: 992px) {
        .wd-stats-grid { grid-template-columns: 1fr; }
        .tab-nav-container { flex-direction: column; align-items: flex-start; gap: 15px; }
    }
</style>

<div class="wd-elite-page">
    
    <!-- SECTION 1: BENTO STATISTICS -->
    <div class="bento-grid">
        <div class="bento-item">
            <div class="bento-icon"><i class="fas fa-hourglass-start"></i></div>
            <div class="bento-val">
                <span>Antrean Cair</span>
                <b>Rp <?= number_format($total_pending) ?></b>
            </div>
        </div>
        <div class="bento-item" style="border-color: var(--neon);">
            <div class="bento-icon" style="background:var(--neon); color:#000;"><i class="fas fa-check-double"></i></div>
            <div class="bento-val">
                <span>Total Sukses</span>
                <b>Rp <?= number_format($total_paid) ?></b>
            </div>
        </div>
        <div class="bento-item" onclick="toggleModal('modalThreshold', true)" style="cursor:pointer;">
            <div class="bento-icon"><i class="fas fa-sliders-h"></i></div>
            <div class="bento-val">
                <span>Min. Withdrawal</span>
                <b>Rp <?= number_format($min_threshold) ?></b>
            </div>
        </div>
    </div>

    <!-- SECTION 2: NAVIGATION TABS -->
    <div class="nav-control-wrapper">
        <div class="nav-pills-lux">
            <a href="?page=withdraw&f_status=pending" class="nav-pill-btn tab-pending <?= $current_filter=='pending'?'active':'' ?>">WAITING QUEUE</a>
            <a href="?page=withdraw&f_status=approved" class="nav-pill-btn tab-approved <?= $current_filter=='approved'?'active':'' ?>">PAID LOGS</a>
            <a href="?page=withdraw&f_status=rejected" class="nav-pill-btn tab-rejected <?= $current_filter=='rejected'?'active':'' ?>">REJECTED HISTORY</a>
        </div>
        <div style="font-size: 11px; font-weight: 900; color: #94a3b8; letter-spacing: 1px;">
            <i class="fas fa-database"></i> REGISTRY: <?= count($withdrawal_list) ?> ENTRIES
        </div>
    </div>

    <!-- SECTION 3: DATA TABLE -->
    <form id="bulkForm" method="POST">
        <div class="table-lux-card">
            <table>
                <thead>
                    <tr>
                        <th width="40"><input type="checkbox" style="width:18px; height:18px; accent-color:var(--neon);" onclick="toggleSelectAll(this)"></th>
                        <th>User Affiliator</th>
                        <th>Rincian Rekening</th>
                        <th>Nominal</th>
                        <?php if($current_filter != 'pending'): ?><th>Audit Notes</th><?php endif; ?>
                        <th style="text-align:right;">Manajemen Kontrol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($withdrawal_list as $row): ?>
                    <tr>
                        <td><input type="checkbox" name="wd_ids[]" value="<?= $row['id'] ?>" class="wd-check" onchange="updateBulkUI()" style="width:18px; height:18px; accent-color:var(--neon);"></td>
                        <td>
                            <div class="u-meta">
                                <div class="u-avatar"><?= strtoupper(substr($row['username'],0,1)) ?></div>
                                <div>
                                    <b style="font-size:16px; display:block;"><?= htmlspecialchars($row['fullname']) ?></b>
                                    <span style="font-size:11px; color:#94a3b8; font-weight:700;">@<?= $row['username'] ?> • <?= date('d/m/y H:i', strtotime($row['created_at'])) ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="method-badge"><?= $row['bank_name'] ?></span>
                            <span class="acc-number"><?= $row['account_number'] ?></span>
                            <div class="acc-holder">A/N <?= htmlspecialchars($row['account_holder']) ?></div>
                        </td>
                        <td>
                            <div class="amount-lux">Rp <?= number_format($row['amount']) ?></div>
                        </td>
                        <?php if($current_filter != 'pending'): ?>
                        <td>
                            <div class="note-bubble"><?= $row['admin_notes'] ?: '<i style="opacity:0.5">No audit notes</i>' ?></div>
                        </td>
                        <?php endif; ?>
                        <td style="text-align:right;">
                            <?php if($row['status'] == 'pending'): ?>
                                <button type="button" class="nav-pill-btn active" style="padding: 10px 20px; cursor: pointer;" onclick='openAudit(<?= json_encode($row) ?>)'>
                                    REVIEW <i class="fas fa-chevron-right" style="margin-left:5px;"></i>
                                </button>
                            <?php else: ?>
                                <div style="font-weight: 900; font-size: 11px; color: <?= $row['status']=='approved'?'#22c55e':'#ef4444' ?>; letter-spacing: 2px;">
                                    <i class="fas <?= $row['status']=='approved'?'fa-check-circle':'fa-undo-alt' ?>"></i> <?= strtoupper($row['status']) ?>
                                    <?php if($row['processed_at']): ?>
                                        <div style="font-size: 9px; color: #94a3b8; margin-top: 5px;"><?= date('d/m/y H:i', strtotime($row['processed_at'])) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; if(empty($withdrawal_list)) echo "<tr><td colspan='6' style='text-align:center; padding:150px; color:var(--gray-text); font-weight:900; font-size:16px;'>TIDAK ADA DATA DALAM KATEGORI INI.</td></tr>"; ?>
                </tbody>
            </table>
        </div>

        <!-- FLOATING BULK BAR -->
        <div id="bulkBar">
            <span class="bulk-label" style="color:#fff; font-weight:700;"><i class="fas fa-shield-halved text-neon"></i> <span id="countUI">0</span> Request Terpilih</span>
            <input type="hidden" name="status_to_set" id="bulkStatus">
            <input type="hidden" name="execute_withdraw_action" value="1">
            <div style="display:flex; gap:12px;">
                <button type="button" onclick="launchBulk('approved')" class="btn-bulk-exec" style="background:var(--neon); color:#000;">PAID ALL</button>
                <button type="button" onclick="launchBulk('rejected')" class="btn-bulk-exec" style="background:rgba(255,255,255,0.1); color:#fff;">REJECT ALL</button>
            </div>
        </div>
    </form>
</div>

<!-- MODAL: AUDIT PENCAIRAN -->
<div id="modalAuditWD" class="modal-lux-overlay" onclick="if(event.target===this) toggleModal('modalAuditWD', false)">
    <div class="modal-lux-card">
        <i class="fas fa-times modal-lux-close" onclick="toggleModal('modalAuditWD', false)"></i>
        <h2 style="font-weight:950; font-size:35px; margin-bottom:10px; letter-spacing:-2px;"><i class="fas fa-fingerprint text-neon"></i> WD Audit</h2>
        <p style="color:#64748b; font-size:16px; font-weight:500; margin-bottom:45px;">Pastikan Anda sudah mentransfer dana ke rekening tujuan.</p>

        <div class="audit-grid">
            <div class="audit-box">
                <small>Informasi Rekening</small>
                <b id="m_bank" style="font-size:24px; display:block; margin-bottom:8px;"></b>
                <b id="m_acc" style="color:var(--neon); background:#000; padding:5px 15px; border-radius:12px; display:inline-block; font-size:18px; margin-top:10px;"></b>
                <div id="m_holder" style="font-weight:800; font-size:13px; text-transform:uppercase; margin-top:10px;"></div>
            </div>
            <div class="audit-box" style="background:var(--bg-dark); color:#fff; text-align:center; display:flex; flex-direction:column; justify-content:center;">
                <small style="opacity:0.5; font-weight:800;">TOTAL CAIR</small>
                <div id="m_amt" style="font-size:30px; font-weight:950; color:var(--neon);"></div>
            </div>
        </div>

        <form method="POST">
            <input type="hidden" name="target_wd_id" id="m_id">
            <input type="hidden" name="execute_withdraw_action" value="1">
            <label style="font-size:12px; font-weight:900; color:var(--gray-text); text-transform:uppercase; margin-bottom:12px; display:block; letter-spacing:1px;">Internal Notes / Transfer ID</label>
            <textarea name="admin_notes" class="input-lux-area" placeholder="Masukkan nomor referensi mutasi atau alasan reject..."></textarea>
            
            <button type="submit" name="status_to_set" value="approved" class="btn-lux-exec btn-lux-approve">KONFIRMASI TELAH TERBAYAR <i class="fas fa-check-circle"></i></button>
            <button type="submit" name="status_to_set" value="rejected" class="btn-lux-exec btn-lux-reject">TOLAK PERMINTAAN (REFUND SALDO)</button>
        </form>
    </div>
</div>

<!-- MODAL: SETTINGS THRESHOLD -->
<div id="modalThreshold" class="modal-lux-overlay" onclick="if(event.target===this) toggleModal('modalThreshold', false)">
    <div class="modal-lux-card" style="max-width: 450px;">
        <h3 style="font-weight:950; margin-bottom:25px; letter-spacing:-1px;">Withdrawal Policy</h3>
        <form method="POST">
            <label style="font-size:11px; font-weight:900; color:var(--gray-text); text-transform:uppercase; margin-bottom:12px; display:block;">Saldo Minimal Penarikan (Rp)</label>
            <input type="number" name="min_val" class="input-lux-area" style="height:70px;" value="<?= $min_threshold ?>" required>
            <p style="font-size:11px; color:var(--gray-text); line-height:1.6; font-weight:600;">Member hanya bisa mengajukan penarikan jika saldo komisi melebihi angka di atas.</p>
            <button type="submit" name="save_threshold" class="btn-lux-exec btn-lux-approve" style="margin-top:20px;">UPDATE SETTINGS</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleModal(id, show) {
        document.getElementById(id).style.display = show ? 'flex' : 'none';
        if(show) document.body.style.overflow = 'hidden'; else document.body.style.overflow = 'auto';
    }

    function openAudit(data) {
        document.getElementById('m_id').value = data.id;
        document.getElementById('m_bank').innerText = data.bank_name; // Sesuai DB baru
        document.getElementById('m_acc').innerText = data.account_number; // Sesuai DB baru
        document.getElementById('m_holder').innerText = "A/N " + data.account_holder; // Sesuai DB baru
        document.getElementById('m_amt').innerText = "Rp " + parseInt(data.amount).toLocaleString();
        toggleModal('modalAuditWD', true);
    }

    function toggleSelectAll(source) {
        document.querySelectorAll('.wd-check').forEach(c => c.checked = source.checked);
        updateBulkUI();
    }

    function updateBulkUI() {
        let count = document.querySelectorAll('.wd-check:checked').length;
        document.getElementById('countUI').innerText = count;
        document.getElementById('bulkBar').style.display = count > 0 ? 'flex' : 'none';
    }

    function launchBulk(status) {
        Swal.fire({
            title: 'Audit Massal',
            text: "Proses " + document.querySelectorAll('.wd-check:checked').length + " data sebagai " + status.toUpperCase() + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: status === 'approved' ? '#a1ff5a' : '#ef4444',
            confirmButtonText: 'Ya, Lanjutkan'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulkStatus').value = status;
                document.getElementById('bulkForm').submit();
            }
        });
    }

    <?php if(isset($msg_wd)): $m = explode('|', $msg_wd); ?>
        Swal.fire({ icon: '<?= $m[0] ?>', title: 'Audit Result', text: '<?= $m[1] ?>', confirmButtonColor: '#020b09' });
    <?php endif; ?>
</script>