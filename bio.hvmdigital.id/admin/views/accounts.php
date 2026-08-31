<?php
/**
 * HVM STUDIO - ADVANCED ACCOUNTS MANAGEMENT (ULTRA SHARP & BRIGHT)
 * Fitur: Toggle Premium, Banned System, Edit Profile, Impersonate (New Tab)
 * Fix: No Dimming, No Blur, High Contrast Premium Modal
 */

// --- 1. LOGIC: UPDATE DATA (ROLE, STATUS, & FULL EDIT) ---
if (isset($_POST['action_type'])) {
    $target_uid = $_POST['target_uid'];
    
    if ($_POST['action_type'] == 'toggle_premium') {
        if ($_POST['current_role'] == 'premium') {
            $pdo->prepare("UPDATE users SET role = 'member', premium_until = NULL WHERE id = ?")->execute([$target_uid]);
        } else {
            $months = (int)$_POST['duration_months'];
            $pdo->prepare("UPDATE users SET role = 'premium', premium_until = DATE_ADD(NOW(), INTERVAL ? MONTH) WHERE id = ?")->execute([$months, $target_uid]);
        }
    } 
    elseif ($_POST['action_type'] == 'update_status') {
        $pdo->prepare("UPDATE users SET status = ? WHERE id = ?")->execute([$_POST['new_status'], $target_uid]);
    }
    elseif ($_POST['action_type'] == 'full_edit') {
        $fullname = trim($_POST['fullname']);
        $username = strtolower(str_replace(' ', '', trim($_POST['username'])));
        $email    = trim($_POST['email']);
        $phone    = trim($_POST['phone']);
        
        $cek = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $cek->execute([$username, $target_uid]);
        
        if($cek->rowCount() == 0) {
            if (!empty($_POST['password'])) {
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE users SET fullname=?, username=?, email=?, phone=?, password=? WHERE id=?")
                    ->execute([$fullname, $username, $email, $phone, $hash, $target_uid]);
            } else {
                $pdo->prepare("UPDATE users SET fullname=?, username=?, email=?, phone=? WHERE id=?")
                    ->execute([$fullname, $username, $email, $phone, $target_uid]);
            }
        }
    }
    echo "<script>window.location.href='?page=accounts&success=1';</script>"; exit;
}

// --- 2. FILTERING LOGIC ---
$f_status = $_GET['f_status'] ?? '';
$f_role   = $_GET['f_role'] ?? '';
$search   = $_GET['q'] ?? '';

$query = "SELECT u.*, r.username as referrer_name FROM users u LEFT JOIN users r ON u.referrer_id = r.id WHERE 1=1";
$params = [];
if ($f_status) { $query .= " AND u.status = ?"; $params[] = $f_status; }
if ($f_role)   { $query .= " AND u.role = ?"; $params[] = $f_role; }
if ($search)   { 
    $query .= " AND (u.fullname LIKE ? OR u.username LIKE ? OR u.email LIKE ?)"; 
    $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%"; 
}
$query .= " ORDER BY u.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$accounts = $stmt->fetchAll();
?>

<style>
    /* --- REMOVED BLUR & DARKNESS --- */
    body.modal-open .sidebar-container, 
    body.modal-open .main-panel {
        /* Tidak ada filter blur / opacity rendah */
        pointer-events: none; /* Tetap kunci interaksi di belakang modal */
        transition: 0.3s ease;
    }

    /* --- ANIMATIONS --- */
    @keyframes modalSpring {
        0% { transform: scale(0.9) translateY(40px); opacity: 0; }
        100% { transform: scale(1) translateY(0); opacity: 1; }
    }

    /* --- FILTERS --- */
    .filter-grid { display: grid; grid-template-columns: 2.5fr 1fr 1fr 0.5fr; gap: 12px; margin-bottom: 30px; }
    .input-premium { background: #f8fafc; border: 1px solid #eee; padding: 14px 20px; border-radius: 18px; font-weight: 700; font-size: 13px; outline: none; transition: 0.3s; width: 100%; font-family: inherit; color: #020b09; }
    .input-premium:focus { border-color: var(--neon); background: #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.03); }
    .btn-search { background: var(--bg-dark); color: #fff; border: none; border-radius: 18px; cursor: pointer; font-weight: 800; transition: 0.3s; display: flex; align-items: center; justify-content: center; }
    .btn-search:hover { background: #111; transform: translateY(-2px); }

    /* --- TABLE UI --- */
    .acc-card { background: #fff; border-radius: 40px; border: 1px solid #f1f5f9; padding: 10px; box-shadow: 0 15px 40px rgba(0,0,0,0.02); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 850px; }
    th { padding: 25px 20px; text-align: left; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 1px solid #f8fafc; }
    td { padding: 18px 20px; border-bottom: 1px solid #f8fafc; vertical-align: middle; transition: 0.2s; }
    tr:hover td { background: #fafafa; }

    .user-flex { display: flex; align-items: center; gap: 15px; }
    .avatar-p { width: 42px; height: 42px; background: #020b09; color: var(--neon); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 15px; }
    
    .badge-p { padding: 6px 14px; border-radius: 10px; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-pro { background: rgba(161, 255, 90, 0.15); color: #2d6600; border: 1px solid var(--neon); }
    .badge-free { background: #f1f5f9; color: #64748b; }

    .btn-action-p { width: 38px; height: 38px; border-radius: 12px; border: none; background: #f8fafc; color: #020b09; cursor: pointer; transition: 0.3s; margin-left: 5px; font-size: 14px; }
    .btn-action-p:hover { background: var(--neon); transform: translateY(-3px); box-shadow: 0 10px 15px rgba(161, 255, 90, 0.2); }

    /* --- MODAL SYSTEM (BRIGHT & CRISP) --- */
    .detail-overlay { 
        display: none; position: fixed; inset: 0; 
        background: rgba(255,255,255,0.05); /* Overlay sangat terang */
        z-index: 999999; 
        align-items: center; justify-content: center; padding: 20px;
    }
    .detail-box-p { 
        background: #fff; width: 100%; max-width: 900px; border-radius: 45px; 
        max-height: 88vh; overflow-y: auto; position: relative; padding: 45px;
        /* Shadow sangat tebal untuk pemisahan visual tanpa kegelapan background */
        box-shadow: 0 30px 120px rgba(0,0,0,0.3), 0 0 0 1000px rgba(255,255,255,0.2); 
        animation: modalSpring 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    .detail-box-p::-webkit-scrollbar { width: 4px; }
    .detail-box-p::-webkit-scrollbar-thumb { background: #eee; border-radius: 10px; }

    @media (max-width: 768px) { 
        .filter-grid { grid-template-columns: 1fr; } 
        .detail-box-p { padding: 25px; border-radius: 35px; }
    }
</style>

<div class="account-container">
    <!-- Search & Filter -->
    <form method="GET" class="filter-grid">
        <input type="hidden" name="page" value="accounts">
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="input-premium" placeholder="Cari Nama, Username, atau Email...">
        <select name="f_role" class="input-premium">
            <option value="">Role: Semua</option>
            <option value="member" <?= $f_role=='member'?'selected':'' ?>>Member Free</option>
            <option value="premium" <?= $f_role=='premium'?'selected':'' ?>>Premium Pro</option>
        </select>
        <select name="f_status" class="input-premium">
            <option value="">Status: Semua</option>
            <option value="active" <?= $f_status=='active'?'selected':'' ?>>Aktif</option>
            <option value="banned" <?= $f_status=='banned'?'selected':'' ?>>Banned</option>
        </select>
        <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
    </form>

    <!-- Table -->
    <div class="acc-card anim-up">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Membership</th>
                    <th>Status</th>
                    <th>Join Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($accounts as $u): ?>
                <tr>
                    <td>
                        <div class="user-flex">
                            <div class="avatar-p"><?= strtoupper(substr($u['username'],0,1)) ?></div>
                            <div>
                                <b style="font-size:14px; color:#000;">@<?= $u['username'] ?></b>
                                <span style="font-size:11px; color:#94a3b8; display:block; font-weight:600;"><?= htmlspecialchars($u['fullname']) ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-p <?= $u['role'] == 'premium' ? 'badge-pro' : 'badge-free' ?>">
                            <?= strtoupper($u['role']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge-p" style="<?= $u['status'] == 'active' ? 'background:#dcfce7; color:#166534;' : ($u['status'] == 'banned' ? 'background:#fee2e2; color:#dc2626;' : 'background:#eee;') ?>">
                            <?= $u['status'] ?>
                        </span>
                    </td>
                    <td style="font-size:12px; font-weight:700; color:#64748b;">
                        <?= date('d M Y', strtotime($u['created_at'])) ?>
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex; justify-content:flex-end; gap:5px;">
                            <form method="POST" action="" target="_blank">
                                <input type="hidden" name="impersonate_id" value="<?= $u['id'] ?>">
                                <button type="submit" class="btn-action-p" title="Login As User"><i class="fas fa-external-link-alt"></i></button>
                            </form>
                            <button class="btn-action-p" title="Details" onclick="showUserDetail(<?= $u['id'] ?>)"><i class="fas fa-id-badge"></i></button>
                            <button class="btn-action-p" title="Edit" onclick='openFullEdit(<?= json_encode($u) ?>)' style="background:#020b09; color:var(--neon);"><i class="fas fa-user-cog"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL 1: EDIT PROFILE -->
<div id="fullEditModal" class="detail-overlay" onclick="if(event.target===this) closeFullEdit()">
    <div class="detail-box-p" style="max-width: 500px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
            <h3 style="font-weight:900; font-size:22px; letter-spacing:-1px;"><i class="fas fa-user-edit" style="color:var(--neon); margin-right:10px;"></i>Modify Profile</h3>
            <button onclick="closeFullEdit()" style="border:none; background:#f8fafc; width:38px; height:38px; border-radius:12px; cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST">
            <input type="hidden" name="target_uid" id="fe_id">
            <input type="hidden" name="action_type" value="full_edit">
            
            <label class="p-input-label">Nama Lengkap</label>
            <input type="text" name="fullname" id="fe_fullname" class="input-premium" required>
            
            <label class="p-input-label">Username (URL Bio)</label>
            <input type="text" name="username" id="fe_username" class="input-premium" required>
            
            <label class="p-input-label">Email Address</label>
            <input type="email" name="email" id="fe_email" class="input-premium" required>
            
            <label class="p-input-label">WhatsApp</label>
            <input type="text" name="phone" id="fe_phone" class="input-premium">
            
            <label class="p-input-label">Update Password (Optional)</label>
            <input type="password" name="password" class="input-premium" placeholder="Isi untuk ubah password">
            
            <button type="submit" class="btn-search" style="width:100%; padding:18px; background:var(--neon); color:#000; font-size:14px; margin-top:10px;">SAVE PERMANENTLY</button>
        </form>
    </div>
</div>

<!-- MODAL 2: DETAIL VIEW -->
<div id="userDetailModal" class="detail-overlay" onclick="if(event.target===this) closeDetail()">
    <div class="detail-box-p">
        <div id="modalLoading" style="text-align:center; padding:100px;">
            <i class="fas fa-circle-notch fa-spin" style="font-size:40px; color:var(--neon);"></i>
        </div>
        <div id="modalContent"></div>
        <button onclick="closeDetail()" style="position:absolute; top:35px; right:35px; border:none; background:#f8fafc; width:45px; height:45px; border-radius:15px; cursor:pointer; z-index:100;"><i class="fas fa-times"></i></button>
    </div>
</div>

<script>
    function showUserDetail(uid) {
        document.body.classList.add('modal-open');
        const modal = document.getElementById('userDetailModal');
        modal.style.display = 'flex';
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
        document.getElementById('userDetailModal').style.display = 'none'; 
        document.body.classList.remove('modal-open');
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
        document.getElementById('fullEditModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    }
</script>