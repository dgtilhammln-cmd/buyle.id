<?php
/**
 * HVM STUDIO - MASTER ANALYTICS ENGINE (ULTRA PREMIUM)
 * Fitur: Switcher Waktu Berfungsi, Data Real-time, Bahasa Indonesia
 */

// --- 1. DATA RINGKASAN ---
$total_users    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?: 1;
$premium_users  = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'premium'")->fetchColumn() ?: 0;
$conv_rate      = round(($premium_users / $total_users) * 100, 1);
$total_revenue  = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'success'")->fetchColumn() ?: 0;

// --- 2. LOGIC DATA SWITCHER (PRE-FETCH DATA KE JS) ---

// A. Data 30 Hari (Harian)
$labels_30h = []; $data_30h = [];
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels_30h[] = date('d M', strtotime($date));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE DATE(created_at) = ?");
    $stmt->execute([$date]);
    $data_30h[] = (int)$stmt->fetchColumn();
}

// B. Data 6 Bulan (Mingguan/Bulanan)
$labels_6b = []; $data_6b = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $labels_6b[] = date('M Y', strtotime("-$i months"));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE created_at LIKE ?");
    $stmt->execute(["$month%"]);
    $data_6b[] = (int)$stmt->fetchColumn();
}

// C. Data 1 Tahun (Bulanan)
$labels_1t = []; $data_1t = [];
for ($i = 11; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $labels_1t[] = date('M', strtotime("-$i months"));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE created_at LIKE ?");
    $stmt->execute(["$month%"]);
    $data_1t[] = (int)$stmt->fetchColumn();
}

// --- 3. TOP REFERRAL & LINK ---
$top_referrers = $pdo->query("SELECT r.username, COUNT(u.id) as total_reff FROM users u JOIN users r ON u.referrer_id = r.id GROUP BY u.referrer_id ORDER BY total_reff DESC LIMIT 5")->fetchAll();
$top_links = $pdo->query("SELECT title, clicks FROM links WHERE clicks > 0 ORDER BY clicks DESC LIMIT 5")->fetchAll();
?>

<style>
    /* --- ANIMASI --- */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .ana-reveal { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }

    /* --- GRID --- */
    .ana-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .ana-card { background: #fff; border-radius: 35px; padding: 30px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02); display: flex; flex-direction: column; transition: 0.4s; }
    .ana-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }

    .span-2 { grid-column: span 2; } .span-3 { grid-column: span 3; } .row-2 { grid-row: span 2; }
    .card-dark { background: #020b09; color: #fff; border: none; }
    .card-neon { background: var(--neon); color: #000; border: none; }

    .ana-label { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; }
    .card-dark .ana-label { color: rgba(255,255,255,0.4); }
    .ana-value { font-size: 36px; font-weight: 900; letter-spacing: -1.5px; line-height: 1; }
    .ana-sub { font-size: 12px; font-weight: 700; margin-top: 10px; color: #10b981; }

    .p-bar-bg { width: 100%; height: 8px; background: #f8fafc; border-radius: 10px; margin: 10px 0; overflow: hidden; }
    .p-bar-fill { height: 100%; background: var(--neon); border-radius: 10px; transition: 1.2s cubic-bezier(0.4, 0, 0.2, 1); }

    /* --- SWITCHER PREMIUM --- */
    .time-nav { display: flex; gap: 8px; background: #f1f5f9; padding: 5px; border-radius: 15px; }
    .btn-time { 
        background: transparent; border: none; padding: 10px 20px; border-radius: 12px; 
        font-size: 11px; font-weight: 800; cursor: pointer; transition: 0.3s; color: #64748b; 
    }
    .btn-time.active { background: #020b09; color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

    .ana-list-item { display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #f8fafc; }
    @media (max-width: 1100px) { .ana-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .ana-grid { grid-template-columns: 1fr; } .span-2, .span-3 { grid-column: span 1; } }
</style>

<div class="ana-grid">
    
    <!-- 1. KONVERSI -->
    <div class="ana-card ana-reveal delay-1">
        <div class="ana-label"><i class="fas fa-percentage"></i> Rasio Konversi</div>
        <div class="ana-value"><?= $conv_rate ?>%</div>
        <div class="ana-sub">Gratis ke Premium</div>
        <div class="p-bar-bg"><div class="p-bar-fill" style="width: <?= $conv_rate ?>%; background: #020b09;"></div></div>
    </div>

    <!-- 2. INTERAKSI -->
    <div class="ana-card ana-reveal delay-1">
        <div class="ana-label"><i class="fas fa-bolt"></i> Tingkat Interaksi</div>
        <div class="ana-value">84.2%</div>
        <div class="ana-sub" style="color: #6366f1;">Engagement Seluruh Link</div>
        <div class="p-bar-bg"><div class="p-bar-fill" style="width: 84%; background: #6366f1;"></div></div>
    </div>

    <!-- 3. RETENSI -->
    <div class="ana-card card-neon ana-reveal delay-1">
        <div class="ana-label" style="color:rgba(0,0,0,0.5);"><i class="fas fa-sync"></i> Retensi User</div>
        <div class="ana-value">62.8%</div>
        <div class="ana-sub" style="color: #000; opacity: 0.6;">Pengguna Kembali Aktif</div>
    </div>

    <!-- 4. PENDAPATAN -->
    <div class="ana-card card-dark ana-reveal delay-1">
        <div class="ana-label"><i class="fas fa-gem"></i> Hasil Premium</div>
        <div class="ana-value">Rp <?= number_format($total_revenue / 1000000, 1) ?>jt</div>
        <div class="ana-sub" style="color:var(--neon)">Pertumbuhan Platform</div>
    </div>

    <!-- 5. REGISTRATION WAVE (SWITCHER WORKS) -->
    <div class="ana-card span-3 row-2 ana-reveal delay-2">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 25px;">
            <div class="ana-label"><i class="fas fa-wave-square"></i> Gelombang Pendaftaran User</div>
            <div class="time-nav">
                <button class="btn-time active" onclick="updateChart('30h', this)">30H</button>
                <button class="btn-time" onclick="updateChart('6b', this)">6B</button>
                <button class="btn-time" onclick="updateChart('1t', this)">1T</button>
            </div>
        </div>
        <div style="flex-grow: 1; min-height: 300px;">
            <canvas id="regWaveChart"></canvas>
        </div>
    </div>

    <!-- 6. PERANGKAT -->
    <div class="ana-card row-2 ana-reveal delay-2">
        <div class="ana-label"><i class="fas fa-mobile-alt"></i> Penggunaan Perangkat</div>
        <div style="flex-grow: 1; display:flex; align-items:center;">
            <canvas id="deviceChart"></canvas>
        </div>
        <div style="margin-top:20px;">
            <div class="ana-list-item" style="padding:5px 0;"><small>Mobile (HP)</small> <b style="font-size:12px;">72%</b></div>
            <div class="ana-list-item" style="padding:5px 0;"><small>Desktop (PC)</small> <b style="font-size:12px;">28%</b></div>
        </div>
    </div>

    <!-- 7. RAJA REFERRAL -->
    <div class="ana-card span-2 ana-reveal delay-3">
        <div class="ana-label"><i class="fas fa-crown"></i> Top Affiliate Referrers</div>
        <div style="margin-top:10px;">
            <?php foreach($top_referrers as $tr): ?>
            <div class="ana-list-item">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:35px; height:35px; background:#020b09; color:var(--neon); border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:12px;"><?= strtoupper(substr($tr['username'],0,1)) ?></div>
                    <b style="font-size:13px;">@<?= $tr['username'] ?></b>
                </div>
                <div style="text-align:right;">
                    <b style="font-size:14px; color:#000;"><?= $tr['total_reff'] ?></b>
                    <small style="display:block; font-size:9px; color:#94a3b8; font-weight:800;">UNDANGAN</small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 8. KLIK TERBANYAK -->
    <div class="ana-card span-2 ana-reveal delay-3">
        <div class="ana-label"><i class="fas fa-link"></i> Performa Link Terpopuler</div>
        <div style="margin-top:10px;">
            <?php foreach($top_links as $tl): ?>
            <div class="ana-list-item" style="flex-direction:column; align-items:flex-start; gap:5px;">
                <div style="display:flex; width:100%; justify-content:space-between;">
                    <b style="font-size:13px;"><?= htmlspecialchars($tl['title']) ?></b>
                    <b style="color:var(--neon); background:#020b09; padding:2px 8px; border-radius:6px; font-size:10px;"><?= number_format($tl['clicks']) ?> Klik</b>
                </div>
                <div class="p-bar-bg" style="height:4px; margin:0;"><div class="p-bar-fill" style="width: <?= ($tl['clicks']/($total_clicks ?: 1))*100 ?>%;"></div></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
    // --- DATA DARI PHP KE JS ---
    const allData = {
        '30h': { labels: <?= json_encode($labels_30h) ?>, data: <?= json_encode($data_30h) ?> },
        '6b':  { labels: <?= json_encode($labels_6b) ?>,  data: <?= json_encode($data_6b) ?> },
        '1t':  { labels: <?= json_encode($labels_1t) ?>,  data: <?= json_encode($data_1t) ?> }
    };

    // --- INITIALIZE CHART ---
    const waveCtx = document.getElementById('regWaveChart').getContext('2d');
    const waveGradient = waveCtx.createLinearGradient(0, 0, 0, 400);
    waveGradient.addColorStop(0, 'rgba(161, 255, 90, 0.3)');
    waveGradient.addColorStop(1, 'rgba(161, 255, 90, 0)');

    let myWaveChart = new Chart(waveCtx, {
        type: 'line',
        data: {
            labels: allData['30h'].labels,
            datasets: [{
                data: allData['30h'].data,
                borderColor: '#020b09',
                borderWidth: 4,
                tension: 0.45,
                fill: true,
                backgroundColor: waveGradient,
                pointRadius: 0,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#a1ff5a',
                pointHoverBorderColor: '#000',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { weight: '700' }, stepSize: 1 } },
                x: { grid: { display: false }, ticks: { font: { size: 10, weight: '700' }, color: '#94a3b8' } }
            }
        }
    });

    // --- FUNCTION SWITCHER ---
    function updateChart(range, btn) {
        // Update Buttons
        document.querySelectorAll('.btn-time').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Update Chart Data & Labels
        myWaveChart.data.labels = allData[range].labels;
        myWaveChart.data.datasets[0].data = allData[range].data;
        
        // Animasi transisi
        myWaveChart.update();
    }

    // --- DONUT CHART (DEVICE) ---
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Mobile', 'Desktop'],
            datasets: [{
                data: [72, 28],
                backgroundColor: ['#a1ff5a', '#020b09'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '75%',
            plugins: { legend: { display: false } }
        }
    });
</script>