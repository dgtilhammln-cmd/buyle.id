<?php
$members = $pdo->query("SELECT * FROM users ORDER BY id DESC");
?>
<style>
    .table-responsive { overflow-x: auto; margin-top: 20px; }
    table { width: 100%; border-collapse: collapse; color: #fff; }
    th { text-align: left; padding: 20px; font-size: 11px; text-transform: uppercase; opacity: 0.4; letter-spacing: 1px; border-bottom: 1px solid var(--border); }
    td { padding: 20px; border-bottom: 1px solid var(--border); font-size: 14px; }
    .user-pill { display: flex; align-items: center; gap: 12px; }
    .avatar-sm { width: 35px; height: 35px; background: var(--neon); border-radius: 10px; color: #000; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; }
    
    .btn-action { background: var(--card); border: 1px solid var(--border); color: #fff; padding: 8px 12px; border-radius: 10px; cursor: pointer; transition: 0.3s; margin-left: 5px; }
    .btn-action:hover { border-color: var(--neon); color: var(--neon); }
    .role-badge { font-size: 9px; font-weight: 800; padding: 4px 8px; border-radius: 5px; background: rgba(161,255,90,0.1); color: var(--neon); border: 1px solid var(--neon); }
</style>

<div class="glass-card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Member Profile</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($members as $m): ?>
                <tr>
                    <td>
                        <div class="user-pill">
                            <div class="avatar-sm"><?= strtoupper(substr($m['username'],0,1)) ?></div>
                            <div>
                                <div style="font-weight:700;"><?= htmlspecialchars($m['fullname']) ?></div>
                                <div style="font-size:11px; opacity:0.5;">@<?= $m['username'] ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:13px;"><?= $m['email'] ?></div>
                        <div style="font-size:11px; opacity:0.4;"><?= $m['phone'] ?></div>
                    </td>
                    <td><span class="role-badge"><?= strtoupper($m['role']) ?></span></td>
                    <td style="text-align:right;">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="impersonate_id" value="<?= $m['id'] ?>">
                            <button type="submit" class="btn-action" title="Impersonate"><i class="fas fa-sign-in-alt"></i></button>
                        </form>
                        <button class="btn-action" onclick='openEdit(<?= json_encode($m) ?>)'><i class="fas fa-edit"></i></button>
                        <a href="/<?= $m['username'] ?>" target="_blank" class="btn-action"><i class="fas fa-external-link-alt"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit (Desain Glass) -->
<div id="editModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:2000; align-items:center; justify-content:center; padding:20px;">
    <div class="glass-card" style="width:100%; max-width:500px; background:#020b09; border: 1px solid var(--neon);">
        <h3 style="margin-bottom:20px;">Edit Member</h3>
        <form method="POST">
            <input type="hidden" name="edit_user_id" id="e_id">
            <div style="margin-bottom:15px;">
                <label style="font-size:10px; opacity:0.5;">FULLNAME</label>
                <input type="text" name="fullname" id="e_fullname" style="width:100%; padding:12px; background:rgba(255,255,255,0.05); border:1px solid var(--border); color:#fff; border-radius:10px;" required>
            </div>
            <div style="margin-bottom:15px;">
                <label style="font-size:10px; opacity:0.5;">USERNAME</label>
                <input type="text" name="username" id="e_username" style="width:100%; padding:12px; background:rgba(255,255,255,0.05); border:1px solid var(--border); color:#fff; border-radius:10px;" required>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
                 <div>
                    <label style="font-size:10px; opacity:0.5;">EMAIL</label>
                    <input type="email" name="email" id="e_email" style="width:100%; padding:12px; background:rgba(255,255,255,0.05); border:1px solid var(--border); color:#fff; border-radius:10px;" required>
                </div>
                <div>
                    <label style="font-size:10px; opacity:0.5;">PHONE</label>
                    <input type="text" name="phone" id="e_phone" style="width:100%; padding:12px; background:rgba(255,255,255,0.05); border:1px solid var(--border); color:#fff; border-radius:10px;">
                </div>
            </div>
            <div style="margin-bottom:25px;">
                <label style="font-size:10px; opacity:0.5;">PASSWORD (KEEP EMPTY TO UNCHANGE)</label>
                <input type="text" name="password" style="width:100%; padding:12px; background:rgba(255,255,255,0.05); border:1px solid var(--border); color:#fff; border-radius:10px;">
            </div>
            <div style="display:flex; gap:10px;">
                <button type="submit" style="flex:2; padding:15px; background:var(--neon); color:#000; border:none; border-radius:15px; font-weight:800; cursor:pointer;">SAVE CHANGES</button>
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" style="flex:1; padding:15px; background:#222; color:#fff; border:none; border-radius:15px; font-weight:800; cursor:pointer;">CANCEL</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(data) {
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('e_id').value = data.id;
    document.getElementById('e_fullname').value = data.fullname;
    document.getElementById('e_username').value = data.username;
    document.getElementById('e_email').value = data.email;
    document.getElementById('e_phone').value = data.phone;
}
</script>