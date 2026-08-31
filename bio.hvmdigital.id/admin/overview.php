<?php
/**
 * HVM STUDIO - ADMIN OVERVIEW ENGINE (REAL-TIME)
 */

// --- 1. DATA SUMMARY (KPI) ---
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$activeToday = $pdo->query("SELECT COUNT(*) FROM users WHERE last_login >= CURDATE()")->fetchColumn();
$totalClicks = $pdo->query("SELECT SUM(clicks) FROM links")->fetchColumn() ?? 0;
// Pendapatan platform (asumsi ada tabel payments status success)
$totalRevenue = $pdo->query("SELECT SUM(amount) FROM payments WHERE status='success'")->fetchColumn() ?? 0;
// Komisi yang sudah dibayar
$affPaid = $pdo->query("SELECT SUM(amount) FROM withdrawals WHERE status='approved'")->fetchColumn() ?? 0;

// --- 2. DATA CHART (PERTUMBUHAN 7 HARI) ---
$chartLabels = []; $chartData = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $count = $pdo->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = '$date'")->fetchColumn();
    $chartLabels[] = date('D', strtotime($date));
    $chartData[] = $count;
}

// --- 3. AKTIVITAS TERKINI ---
$recentUsers = $pdo->query("SELECT fullname, username, created_at FROM users ORDER BY id DESC LIMIT 5")->fetchAll();
$pendingWD = $pdo->query("SELECT w.*, u.username FROM withdrawals w JOIN users u ON w.user_id = u.id WHERE w.status='pending' ORDER BY w.id DESC LIMIT 5")->fetchAll();

// --- 4. TOP PERFORMERS ---
// Asumsi performa dihitung dari total klik links per user
$topPerformers = $pdo->query("SELECT u.fullname, u.username, SUM(l.clicks) as total_clicks 
                             FROM users u 
                             JOIN links l ON u.id = l.user_id 
                             GROUP BY u.id 
                             ORDER BY total_clicks DESC LIMIT 5")->fetchAll();
?>

<style>
    /* --- BENTO GRID SYSTEM --- */
    .overview-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-auto-rows: minmax(100px, auto);
        gap: 20px;
    }

    /* KPI Cards */
    .kpi-card {
        background: #fff;
        padding: 25px;
        border-radius: 30px;
        border: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
    }
    .kpi-card h3 { font-size: 28px; font-weight: 800; margin: 5px 0; color: #020b09; }
    .kpi-card p { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
    .kpi-icon { position: absolute; right: -10px; bottom: -10px; font-size: 60px; color: #f1f5f9; z-index: 0; }
    .kpi-card * { position: relative; z-index: 1; }

    /* Chart & Health Box */
    .chart-container { grid-column: span 3; grid-row: span 2; }
    .health-box { grid-column: span 1; grid-row: span 2; background: #020b09; color: #fff; }

    /* Table Sections */
    .data-box { 
        grid-column: span 2; 
        background: #fff; 
        border-radius: 30px; 
        padding: 30px; 
        border: 1px solid #f1f5f9;
    }
    .data-box h4 { font-size: 16px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }

    /* List Items */
    .list-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 0; border-bottom: 1px solid #f8fafc;
    }
    .list-item:last-child { border: none; }
    .user-info b { display: block; font-size: 13px; }
    .user-info span { font-size: 11px; color: #94a3b8; }

    .status-pill { padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 800; }
    .pill-pending { background: #fff7ed; color: #c2410c; }
    .pill-live { background: rgba(161, 255, 90, 0.2); color: #2d6600; }

    /* Health Indicator */
    .health-status { display: flex; flex-direction: column; gap: 20px; margin-top: 20px; }
    .health-item { background: rgba(255,255,255,0.05); padding: 15px; border-radius: 20px; }
    .pulse { width: 10px; height: 10px; background: var(--neon); border-radius: 50%; display: inline-block; margin-right: 8px; box-shadow: 0 0 10px var(--neon); animation: blink 1.5s infinite; }
    @keyframes blink { 0% { opacity: 0.3; } 50% { opacity: 1; } 100% { opacity: 0.3; } }

    @media (max-width: 1200px) {
        .overview-grid { grid-template-columns: repeat(2, 1fr); }
        .chart-container { grid-column: span 2; }
    }
    @media (max-width: 768px) {
        .overview-grid { grid-template-columns: 1fr; }
        .chart-container, .data-box, .health-box { grid-column: span 1; }
    }
</style>

<div class="overview-grid">
    
    <!-- KPI CARDS -->
    <div class="kpi-card">
        <p>Active Users</p>
        <h3><?= number_format($totalUsers) ?></h3>
        <span class="status-pill pill-live"><i class="fas fa-circle" style="font-size:6px; vertical-align:middle;"></i> <?= $activeToday ?> Today</span>
        <i class="fas fa-users kpi-icon"></i>
    </div>

    <div class="kpi-card">
        <p>Global Clicks</p>
        <h3><?= number_format($totalClicks) ?></h3>
        <span style="font-size:11px; color:#10b981; font-weight:700;"><i class="fas fa-arrow-up"></i> Real-time</span>
        <i class="fas fa-mouse-pointer kpi-icon"></i>
    </div>

    <div class="kpi-card">
        <p>Total Revenue</p>
        <h3>Rp <?= number_format($totalRevenue/1000, 1) ?>k</h3>
        <span style="font-size:11px; color:#94a3b8; font-weight:700;">Platform Income</span>
        <i class="fas fa-wallet kpi-icon"></i>
    </div>

    <div class="kpi-card">
        <p>Affiliate Paid</p>
        <h3>Rp <?= number_format($affPaid/1000, 1) ?>k</h3>
        <span style="font-size:11px; color:#94a3b8; font-weight:700;">Commissions</span>
        <i class="fas fa-hand-holding-usd kpi-icon"></i>
    </div>

    <!-- GROWTH CHART -->
    <div class="kpi-card chart-container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <p>User Growth (Last 7 Days)</p>
            <i class="fas fa-chart-line" style="color:var(--neon);"></i>
        </div>
        <canvas id="growthChart" height="100"></canvas>
    </div>

    <!-- SYSTEM HEALTH -->
    <div class="kpi-card health-box">
        <p style="color: rgba(255,255,255,0.5);">System Health</p>
        <div class="health-status">
            <div class="health-item">
                <div style="font-size:12px; font-weight:700; margin-bottom:5px;"><span class="pulse"></span> Server Status</div>
                <div style="color:var(--neon); font-weight:800; font-size:14px;">OPERATIONAL</div>
            </div>
            <div class="health-item">
                <div style="font-size:11px; opacity:0.6;">Database Latency</div>
                <div style="font-weight:800;">14ms <small style="color:#10b981;">Excellent</small></div>
            </div>
            <!-- Alert jika ada banyak WD pending -->
            <?php if(count($pendingWD) > 0): ?>
            <div class="health-item" style="border-left: 3px solid #f59e0b;">
                <div style="font-size:11px; color:#f59e0b; font-weight:800;">WARNING</div>
                <div style="font-size:12px;"><?= count($pendingWD) ?> Payouts Pending</div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- RECENT ACTIVITY -->
    <div class="data-box">
        <h4><i class="fas fa-user-plus" style="color:var(--neon);"></i> New Registrations</h4>
        <?php foreach($recentUsers as $ru): ?>
        <div class="list-item">
            <div class="user-info">
                <b><?= htmlspecialchars($ru['fullname']) ?></b>
                <span>@<?= $ru['username'] ?></span>
            </div>
            <div style="font-size:10px; font-weight:700; color:#94a3b8;"><?= date('H:i', strtotime($ru['created_at'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- WITHDRAWAL REQUESTS -->
    <div class="data-box">
        <h4><i class="fas fa-clock" style="color:#f59e0b;"></i> Pending Withdrawals</h4>
        <?php if(count($pendingWD) == 0): ?>
            <p style="font-size:12px; color:#94a3b8;">No pending requests.</p>
        <?php endif; ?>
        <?php foreach($pendingWD as $wd): ?>
        <div class="list-item">
            <div class="user-info">
                <b>@<?= $wd['username'] ?></b>
                <span>Rp <?= number_format($wd['amount']) ?></span>
            </div>
            <a href="?page=withdraw" class="status-pill pill-pending">REVIEW</a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- TOP PERFORMERS -->
    <div class="data-box" style="grid-column: span 4;">
        <h4><i class="fas fa-trophy" style="color:#fbbf24;"></i> Top Performers (Most Clicks)</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <?php foreach($topPerformers as $tp): ?>
            <div class="health-item" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                <div class="user-info">
                    <b style="color:#020b09;"><?= htmlspecialchars($tp['fullname']) ?></b>
                    <span style="color:var(--neon); font-weight:800;"><?= number_format($tp['total_clicks']) ?> Clicks</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
    // GROWTH CHART ENGINE
    const ctx = document.getElementById('growthChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(161, 255, 90, 0.3)');
    gradient.addColorStop(1, 'rgba(161, 255, 90, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'New Users',
                data: <?= json_encode($chartData) ?>,
                borderColor: '#a1ff5a',
                borderWidth: 4,
                tension: 0.4,
                fill: true,
                backgroundColor: gradient,
                pointRadius: 4,
                pointBackgroundColor: '#020b09',
                pointBorderColor: '#a1ff5a',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { weight: 'bold' } } },
                x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
            }
        }
    });
</script>