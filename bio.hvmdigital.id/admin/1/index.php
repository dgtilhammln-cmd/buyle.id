<?php
require_once __DIR__ . '/../config.php';
session_start();

// SECURITY CHECK: PASTIKAN ROLE ADMIN
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login"); exit;
}

// --- LOGIC: IMPERSONATE (LOGIN SEBAGAI USER) ---
if (isset($_POST['impersonate_id'])) {
    $targetId = $_POST['impersonate_id'];
    
    // Ambil data user target
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$targetId]);
    $targetUser = $stmt->fetch();

    if ($targetUser) {
        // Timpa session admin dengan session user target
        // (Admin akan logout otomatis dan menjadi user tersebut)
        $_SESSION['user_id'] = $targetUser['id'];
        $_SESSION['username'] = $targetUser['username'];
        $_SESSION['fullname'] = $targetUser['fullname'];
        $_SESSION['role'] = $targetUser['role']; // Biasanya 'member'

        // Redirect ke Dashboard User
        header("Location: /dashboard?welcome=1");
        exit;
    }
}

// --- LOGIC: UPDATE MEMBER ---
if (isset($_POST['edit_user_id'])) {
    $uid = $_POST['edit_user_id'];
    $sql = "UPDATE users SET fullname=?, email=?, phone=?, username=? WHERE id=?";
    $params = [$_POST['fullname'], $_POST['email'], $_POST['phone'], $_POST['username'], $uid];
    if (!empty($_POST['password'])) {
        $sql = "UPDATE users SET fullname=?, email=?, phone=?, username=?, password=? WHERE id=?";
        $params = [$_POST['fullname'], $_POST['email'], $_POST['phone'], $_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT), $uid];
    }
    $pdo->prepare($sql)->execute($params);
    header("Location: /admincenter?success=1"); exit;
}

// --- GET DATA FOR DASHBOARD ---
$totalUser = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$newMembers = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= NOW() - INTERVAL 30 DAY")->fetchColumn();
$members = $pdo->query("SELECT * FROM users ORDER BY id DESC");

// Data Chart (Pendaftaran 6 Bulan Terakhir)
$chartData = []; $chartLabels = [];
for ($i = 5; $i >= 0; $i--) {
    $monthName = date('M', strtotime("-$i month"));
    $monthNum = date('m', strtotime("-$i month"));
    $yearNum = date('Y', strtotime("-$i month"));
    $count = $pdo->query("SELECT COUNT(*) FROM users WHERE MONTH(created_at) = '$monthNum' AND YEAR(created_at) = '$yearNum'")->fetchColumn();
    $chartLabels[] = $monthName;
    $chartData[] = $count;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Luxury</title>
    <link rel="stylesheet" href="/admin/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="brand"><i class="fas fa-crown"></i> ADMIN PANEL</div>
        <a href="javascript:void(0)" onclick="switchTab('overlay')" class="menu-item active" id="btn-overlay"><i class="fas fa-home"></i> <span>Dashboard</span></a>
        <a href="javascript:void(0)" onclick="switchTab('analytic')" class="menu-item" id="btn-analytic"><i class="fas fa-chart-pie"></i> <span>Analytics</span></a>
        <a href="javascript:void(0)" onclick="switchTab('members')" class="menu-item" id="btn-members"><i class="fas fa-users"></i> <span>Members</span></a>
        <a href="/logout" class="menu-item" style="margin-top:auto; color:#ff4d4d; border-color:rgba(255,77,77,0.2);"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>

    <!-- CONTENT -->
    <div class="main">
        <div class="top-bar">
            <h2 id="pageTitle">Overview</h2>
            <div class="breadcrumb">Control Panel / <span id="breadCrumbText">Dashboard</span></div>
        </div>

        <!-- TAB: OVERLAY -->
        <div id="tab-overlay" class="tab-content">
            <div class="grid-stats">
                <div class="stat-card">
                    <h3><?= $totalUser ?></h3><p>Total User</p><i class="fas fa-users stat-icon"></i>
                </div>
                <div class="stat-card">
                    <h3><?= $newMembers ?></h3><p>New This Month</p><i class="fas fa-user-plus stat-icon"></i>
                </div>
                <div class="stat-card" style="background: linear-gradient(145deg, #eee, #aaa);">
                    <h3 style="color:#000;">ACTIVE</h3><p style="color:#333;">System Status</p><i class="fas fa-server stat-icon" style="color:#000;"></i>
                </div>
            </div>
        </div>

        <!-- TAB: ANALYTIC -->
        <div id="tab-analytic" class="tab-content" style="display:none;">
            <div class="table-container">
                <h3 style="color:#fff; margin-bottom:20px;">Growth Statistics</h3>
                <canvas id="growthChart" height="100"></canvas>
            </div>
        </div>

        <!-- TAB: MEMBERS (FITUR UTAMA) -->
        <div id="tab-members" class="tab-content" style="display:none;">
            <div class="table-container">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h3 style="color:#fff;">Member Management</h3>
                    <small style="color:#666;"><?= $totalUser ?> registered users</small>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>User Info</th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($members as $m): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <b><?= htmlspecialchars($m['fullname']) ?></b>
                                    <span>@<?= htmlspecialchars($m['username']) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="user-info">
                                    <span><?= htmlspecialchars($m['email']) ?></span>
                                    <span><?= htmlspecialchars($m['phone']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span style="padding:5px 10px; border-radius:30px; font-size:10px; background:<?= $m['role']=='admin'?'#fff':'#222' ?>; color:<?= $m['role']=='admin'?'#000':'#ccc' ?>; font-weight:700;">
                                    <?= strtoupper($m['role']) ?>
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <div class="action-group" style="justify-content: flex-end;">
                                    
                                    <!-- TOMBOL LOGIN AS USER (IMPERSONATE) -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="impersonate_id" value="<?= $m['id'] ?>">
                                        <button type="submit" class="btn-icon btn-impersonate" title="Login as User" onclick="return confirm('Login sebagai user ini?')">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </button>
                                    </form>

                                    <!-- Tombol View Page -->
                                    <a href="/<?= $m['username'] ?>" target="_blank" class="btn-icon btn-view" title="Visit Page"><i class="fas fa-eye"></i></a>
                                    
                                    <!-- Tombol Edit Data -->
                                    <a href="javascript:void(0)" class="btn-icon btn-edit" title="Edit Data" onclick='openEdit(<?= json_encode($m) ?>)'><i class="fas fa-pen"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT DATA -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-box">
            <div style="display:flex; justify-content:space-between; margin-bottom:30px;">
                <h3 style="color:#fff; margin:0;">Edit Data</h3>
                <i class="fas fa-times close-modal" onclick="document.getElementById('editModal').style.display='none'"></i>
            </div>
            <form method="POST">
                <input type="hidden" name="edit_user_id" id="e_id">
                <label>Fullname</label><input type="text" name="fullname" id="e_fullname" required>
                <label>Username</label><input type="text" name="username" id="e_username" required>
                <label>Email</label><input type="email" name="email" id="e_email" required>
                <label>Phone</label><input type="text" name="phone" id="e_phone">
                <label>New Password (Optional)</label><input type="text" name="password" placeholder="Leave empty to keep current">
                <button type="submit" class="save-btn">UPDATE DATA</button>
            </form>
        </div>
    </div>

    <script>
        function switchTab(id) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.getElementById('tab-' + id).style.display = 'block';
            document.getElementById('pageTitle').innerText = id.charAt(0).toUpperCase() + id.slice(1);
            document.getElementById('breadCrumbText').innerText = id.charAt(0).toUpperCase() + id.slice(1);
            
            document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('active'));
            document.getElementById('btn-' + id).classList.add('active');
        }

        function openEdit(data) {
            document.getElementById('editModal').style.display = 'flex';
            document.getElementById('e_id').value = data.id;
            document.getElementById('e_fullname').value = data.fullname;
            document.getElementById('e_username').value = data.username;
            document.getElementById('e_email').value = data.email;
            document.getElementById('e_phone').value = data.phone;
        }

        // Chart Config (Silver Theme)
        new Chart(document.getElementById('growthChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($chartLabels) ?>,
                datasets: [{
                    label: 'New Members',
                    data: <?= json_encode($chartData) ?>,
                    borderColor: '#fff',
                    backgroundColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 2, tension: 0.4, fill: true, pointBackgroundColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#222' }, ticks: { color: '#666' } },
                    x: { grid: { display: false }, ticks: { color: '#666' } }
                }
            }
        });

        // Default Tab
        switchTab('overlay');
    </script>
</body>
</html>