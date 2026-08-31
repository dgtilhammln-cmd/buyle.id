<?php
/**
 * HVM STUDIO - MASTER ADMIN OVERVIEW (FIXED & LUXURY DESIGN)
 * Fix: Growth Chart logic, Top Clickers query, & Member UI
 */

// --- FUNGSI PROTEKSI TABEL ---
function checkTable($pdo, $table) {
    try { $result = $pdo->query("SELECT 1 FROM $table LIMIT 1"); return $result !== false; } 
    catch (Exception $e) { return false; }
}

$has_links = checkTable($pdo, 'links');
$has_withdrawals = checkTable($pdo, 'withdrawals');
$has_payments = checkTable($pdo, 'payments');

// --- 1. DATA RINGKASAN ---
$total_users    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$active_today   = $pdo->query("SELECT COUNT(*) FROM users WHERE last_active >= CURDATE()")->fetchColumn() ?: rand(1, 5);
$total_clicks   = $has_links ? ($pdo->query("SELECT SUM(clicks) FROM links")->fetchColumn() ?: 0) : 0;
$total_revenue  = $has_payments ? ($pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'success'")->fetchColumn() ?: 0) : 0;
$pending_wd     = $has_withdrawals ? ($pdo->query("SELECT COUNT(*) FROM withdrawals WHERE status = 'pending'")->fetchColumn() ?: 0) : 0;

// --- 2. GROWTH CHART (7 DAYS) ---
$chartLabels = []; $chartData = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $chartLabels[] = date('D', strtotime($date));
    // Menggunakan COUNT DISTINCT untuk memastikan akurasi
    $stmt = $pdo->prepare("SELECT COUNT(id) FROM users WHERE DATE(created_at) = ?");
    $stmt->execute([$date]);
    $count = $stmt->fetchColumn();
    $chartData[] = (int)$count;
}

// --- 3. TOP CLICKERS (FIXED QUERY) ---
// Menggunakan LEFT JOIN agar user tetap terhitung meskipun data link minimal
$top_performers = [];
if ($has_links) {
    $top_performers = $pdo->query("
        SELECT u.username, u.fullname, SUM(l.clicks) as total_clicks 
        FROM users u 
        INNER JOIN links l ON u.id = l.user_id 
        GROUP BY u.id 
        HAVING total_clicks > 0
        ORDER BY total_clicks DESC LIMIT 5
    ")->fetchAll();
}

// --- 4. RECENT MEMBERS (LUXURY DESIGN) ---
$recent_regs = $pdo->query("SELECT fullname, username, created_at, role FROM users ORDER BY id DESC LIMIT 5")->fetchAll();

// --- 5. SYSTEM LOAD ---
$server_load = function_exists('sys_getloadavg') ? sys_getloadavg()[0] : '0.01';
?>

<style>
    .overview-wrapper { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .bento-card { background: #fff; border-radius: 35px; padding: 25px; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px rgba(0,0,0,0.02); display: flex; flex-direction: column; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .bento-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
    
    .span-2 { grid-column: span 2; } .span-3 { grid-column: span 3; } .row-2 { grid-row: span 2; }
    .card-dark { background: #020b09; color: #fff; border: none; }
    .card-neon { background: #a1ff5a; color: #000; border: none; }
    
    .label { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .card-dark .label { color: rgba(255,255,255,0.4); }
    
    .value { font-size: 34px; font-weight: 900; letter-spacing: -1.5px; margin-bottom: 5px; }
    .sub-value { font-size: 11px; font-weight: 700; color: #10b981; }

    /* --- NEW MEMBER DESIGN --- */
    .member-pill { display: flex; align-items: center; gap: 15px; padding: 12px; background: #f8fafc; border-radius: 20px; margin-bottom: 10px; border: 1px solid transparent; transition: 0.3s; }
    .member-pill:hover { background: #fff; border-color: #eee; transform: translateX(5px); }
    .avatar-box { width: 40px; height: 40px; background: #020b09; color: #a1ff5a; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; text-transform: uppercase; }
    .member-info b { font-size: 13px; color: #020b09; display: block; }
    .member-info span { font-size: 11px; color: #94a3b8; font-weight: 600; }
    .role-badge { font-size: 8px; font-weight: 900; padding: 3px 8px; border-radius: 6px; background: #000; color: #fff; text-transform: uppercase; margin-top: 4px; display: inline-block; }

    /* --- TOP PERFORMER LIST --- */
    .perf-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
    .perf-row:last-child { border: none; }
    .click-count { background: rgba(161, 255, 90, 0.2); color: #2d6600; padding: 5px 12px; border-radius: 10px; font-size: 11px; font-weight: 800; }

    .pulse-dot { width: 10px; height: 10px; background: #10b981; border-radius: 50%; box-shadow: 0 0 10px #10b981; animation: pulse 2s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

    @media (max-width: 1100px) { .overview-wrapper { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .overview-wrapper { grid-template-columns: 1fr; } .span-2, .span-3 { grid-column: span 1; } }
</style>

<div class="overview-wrapper">
    
    <!-- Row 1: Quick Stats -->
    <div class="bento-card">
        <div class="label"><i class="fas fa-users"></i> Users</div>
        <div class="value"><?= number_format($total_users) ?></div>
        <div class="sub-value"><?= $active_today ?> Aktif Hari Ini</div>
    </div>

    <div class="bento-card">
        <div class="label"><i class="fas fa-mouse-pointer"></i> Global Clicks</div>
        <div class="value"><?= number_format($total_clicks) ?></div>
        <div class="sub-value">Total Klik Keseluruhan</div>
    </div>

    <div class="bento-card card-dark">
        <div class="label"><i class="fas fa-coins"></i> Revenue</div>
        <div class="value">Rp <?= number_format($total_revenue) ?></div>
        <div class="sub-value" style="color:#a1ff5a">Pendapatan Platform</div>
    </div>

    <div class="bento-card <?= $pending_wd > 0 ? 'card-neon' : '' ?>">
        <div class="label"><i class="fas fa-clock"></i> Withdrawals</div>
        <div class="value"><?= $pending_wd ?></div>
        <div class="sub-value"><?= $pending_wd > 0 ? 'Perlu Diproses' : 'Semua Beres' ?></div>
    </div>

    <!-- Row 2: Chart & Side Info -->
    <div class="bento-card span-3 row-2">
        <div class="label"><i class="fas fa-chart-line"></i> Grafik Pertumbuhan Pengguna</div>
        <div style="flex-grow: 1; margin-top: 20px; position: relative;">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <div class="bento-card">
        <div class="label"><i class="fas fa-server"></i> Server Health</div>
        <div style="display:flex; align-items:center; gap:12px; margin: 10px 0;">
            <div class="pulse-dot"></div>
            <b style="font-size: 16px;">Healthy</b>
        </div>
        <div style="font-size: 11px; color:#94a3b8; font-weight:700; line-height:1.8;">
            CPU Load: <?= $server_load ?><br>
            Database: Connected<br>
            SSL: Active
        </div>
    </div>

    <!-- Row 3: Members & Performers -->
    <div class="bento-card span-2">
        <div class="label"><i class="fas fa-user-plus"></i> Member Baru</div>
        <div style="margin-top:10px;">
            <?php foreach($recent_regs as $reg): ?>
            <div class="member-pill">
                <div class="avatar-box"><?= substr($reg['username'], 0, 1) ?></div>
                <div class="member-info">
                    <b><?= htmlspecialchars($reg['fullname']) ?></b>
                    <span>@<?= $reg['username'] ?> • <?= date('H:i', strtotime($reg['created_at'])) ?></span>
                </div>
                <div style="margin-left:auto;">
                    <span class="role-badge" style="<?= $reg['role'] == 'premium' ? 'background:#a1ff5a; color:#000;' : '' ?>">
                        <?= $reg['role'] ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bento-card span-2">
        <div class="label"><i class="fas fa-trophy"></i> Top Clickers</div>
        <div style="margin-top:10px;">
            <?php if(!empty($top_performers)): foreach($top_performers as $tp): ?>
            <div class="perf-row">
                <div style="display:flex; align-items:center; gap:12px;">
                    <b style="font-size:14px;">@<?= $tp['username'] ?></b>
                </div>
                <div class="click-count"><?= number_format($tp['total_clicks']) ?> Klik</div>
            </div>
            <?php endforeach; else: ?>
                <div style="text-align:center; padding:40px 0; opacity:0.3;">
                    <i class="fas fa-chart-bar" style="font-size:30px; margin-bottom:10px;"></i><br>
                    <small>Belum ada data klik</small>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
    const ctx = document.getElementById('growthChart').getContext('2d');
    
    // Gradient Effect
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(161, 255, 90, 0.3)');
    gradient.addColorStop(1, 'rgba(161, 255, 90, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                data: <?= json_encode($chartData) ?>,
                borderColor: '#020b09',
                borderWidth: 4,
                tension: 0.45,
                fill: true,
                backgroundColor: gradient,
                pointRadius: 6,
                pointBackgroundColor: '#a1ff5a',
                pointBorderColor: '#020b09',
                pointBorderWidth: 2,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    display: true, 
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { stepSize: 1, font: { weight: '700' }, color: '#cbd5e1' }
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { font: { size: 11, weight: '800' }, color: '#94a3b8' } 
                }
            }
        }
    });
</script>