<?php
// --- LOGIC AKSES ---
$is_premium = ($me['role'] == 'premium' || $me['role'] == 'admin');

// --- SIMULASI DATA TOP BUTTONS (Realtime) ---
// Di produksi, jalankan query: SELECT event_label, COUNT(*) as total FROM analytics WHERE user_id=? AND event_type='click' GROUP BY event_label ORDER BY total DESC
$top_links = [
    ['label' => 'Instagram Profile', 'clicks' => 124, 'color' => '#E1306C'],
    ['label' => 'WhatsApp Order', 'clicks' => 89, 'color' => '#25D366'],
    ['label' => 'Portfolio Drive', 'clicks' => 45, 'color' => '#4285F4'],
    ['label' => 'TikTok Channel', 'clicks' => 32, 'color' => '#000000'],
];
?>

<style>
    /* --- PREVIEW LOCK (FOR FREE USERS) --- */
    .premium-lock-overlay {
        <?php if (!$is_premium): ?>
        filter: blur(12px);
        pointer-events: none;
        user-select: none;
        <?php endif; ?>
        transition: all 0.5s ease;
    }

    /* --- ANALYZE GRID --- */
    .analyze-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    .card-3d {
        background: #ffffff;
        border-radius: 32px;
        padding: 30px;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.04);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .card-3d:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 40px 80px rgba(0,0,0,0.08);
    }

    /* --- STATS CARD --- */
    .stat-main { grid-column: span 1; text-align: center; }
    .stat-main h3 { font-size: 42px; font-weight: 800; color: #020b09; margin: 10px 0; }
    .stat-main p { font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; }

    /* --- CHART CARD --- */
    .chart-wide { grid-column: span 3; }
    .filter-btn-group {
        display: flex;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 15px;
        gap: 5px;
    }
    .filter-btn {
        padding: 8px 16px;
        border: none;
        background: transparent;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        color: #64748b;
        transition: 0.3s;
    }
    .filter-btn.active {
        background: #fff;
        color: #020b09;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* --- TOP LINKS CARD --- */
    .top-links-card { grid-column: span 1; }
    .link-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .link-info { display: flex; align-items: center; gap: 12px; }
    .link-dot { width: 8px; height: 8px; border-radius: 50%; }
    .link-label { font-size: 13px; font-weight: 600; color: #1e293b; }
    .link-val { font-weight: 800; font-size: 14px; color: #020b09; }

    /* --- MOBILE RESPONSIVE --- */
    @media (max-width: 1100px) {
        .analyze-grid { grid-template-columns: repeat(2, 1fr); }
        .chart-wide { grid-column: span 2; order: 1; }
        .stat-main { order: 2; }
        .top-links-card { grid-column: span 2; order: 3; }
    }
    @media (max-width: 768px) {
        .analyze-grid { grid-template-columns: 1fr; }
        .chart-wide, .stat-main, .top-links-card { grid-column: span 1; }
    }
</style>

<div class="analyze-container">
    
    <div class="premium-lock-overlay">
        <div class="analyze-grid">
            <!-- TOTAL VIEWS -->
            <div class="card-3d stat-main">
                <p>Total Views</p>
                <h3><?= number_format($stats['views']) ?></h3>
                <span style="color: #22c55e; font-size: 11px; font-weight: 700;"><i class="fas fa-arrow-up"></i> 14.2%</span>
            </div>

            <!-- TOTAL CLICKS -->
            <div class="card-3d stat-main">
                <p>Total Clicks</p>
                <h3><?= number_format($stats['clicks']) ?></h3>
                <span style="color: #64748b; font-size: 11px; font-weight: 700;">Engagement Rate: 4.2%</span>
            </div>

            <!-- WAVE CHART -->
            <div class="card-3d chart-wide">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <div>
                        <h4 style="font-weight: 800; font-size: 18px;">Traffic Insights</h4>
                        <p style="font-size: 12px; color: #94a3b8;">Data perbandingan Views vs Clicks</p>
                    </div>
                    <div class="filter-btn-group">
                        <button class="filter-btn active" onclick="updateRange(7)">7D</button>
                        <button class="filter-btn" onclick="updateRange(30)">30D</button>
                        <button class="filter-btn" onclick="updateRange(90)">90D</button>
                        <button class="filter-btn"><i class="fas fa-calendar-alt"></i></button>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="analyzeChart"></canvas>
                </div>
            </div>

            <!-- TOP CLICKS LIST -->
            <div class="card-3d top-links-card">
                <h4 style="font-weight: 800; margin-bottom: 20px;">Top Links</h4>
                <?php foreach($top_links as $link): ?>
                <div class="link-item">
                    <div class="link-info">
                        <div class="link-dot" style="background: <?= $link['color'] ?>;"></div>
                        <span class="link-label"><?= $link['label'] ?></span>
                    </div>
                    <span class="link-val"><?= $link['clicks'] ?></span>
                </div>
                <?php endforeach; ?>
                <button style="width: 100%; margin-top: 20px; padding: 12px; border: 1px solid #f1f5f9; background: #fff; border-radius: 12px; font-size: 11px; font-weight: 700; cursor: pointer;">VIEW FULL REPORT</button>
            </div>
        </div>
    </div>
</div>

<script>
    // --- GATE KEEPER LOGIC ---
    <?php if (!$is_premium): ?>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '<span style="font-weight:800; font-size:24px;">Analyze Is Premium!</span>',
            html: '<p style="color:#64748b; line-height:1.6;">Fitur analisis mendalam, statistik realtime, dan pelacakan tombol hanya tersedia untuk member <b>Premium</b>.</p>',
            icon: 'info',
            iconColor: '#a1ff5a',
            showCancelButton: true,
            confirmButtonText: 'Upgrade Now',
            cancelButtonText: 'Later',
            confirmButtonColor: '#020b09',
            background: '#ffffff',
            borderRadius: '32px',
            allowOutsideClick: false,
            backdrop: `rgba(2,11,9,0.4)`
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "?view=premium";
            } else {
                window.location.href = "?view=overview";
            }
        });
    });
    <?php endif; ?>

    // --- CHART LOGIC ---
    const ctx = document.getElementById('analyzeChart').getContext('2d');
    
    // Gradient Views
    const gradViews = ctx.createLinearGradient(0, 0, 0, 400);
    gradViews.addColorStop(0, 'rgba(161, 255, 90, 0.3)');
    gradViews.addColorStop(1, 'rgba(161, 255, 90, 0)');

    // Gradient Clicks
    const gradClicks = ctx.createLinearGradient(0, 0, 0, 400);
    gradClicks.addColorStop(0, 'rgba(2, 11, 9, 0.1)');
    gradClicks.addColorStop(1, 'rgba(2, 11, 9, 0)');

    let analyzeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [
                {
                    label: 'Page Views',
                    data: <?= json_encode($chartData['page_view'] ?? [0,10,5,15,20,12,30]) ?>,
                    borderColor: '#a1ff5a',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: gradViews,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#a1ff5a'
                },
                {
                    label: 'Clicks',
                    data: <?= json_encode($chartData['click'] ?? [0,2,8,3,10,5,15]) ?>,
                    borderColor: '#020b09',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: gradClicks,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#020b09'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#020b09',
                    padding: 15,
                    titleFont: { family: 'Montserrat', size: 12, weight: '800' },
                    bodyFont: { family: 'Montserrat', size: 12 },
                    cornerRadius: 15
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                    ticks: { color: '#94a3b8', font: { family: 'Montserrat', weight: 600 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { family: 'Montserrat', weight: 600 } }
                }
            }
        }
    });

    function updateRange(days) {
        // Logika update chart via AJAX bisa ditaruh di sini
        // Sementara hanya visual button active
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        
        // Contoh alert simulasi loading
        Swal.fire({
            title: 'Fetching Data...',
            timer: 800,
            showConfirmButton: false,
            willOpen: () => { Swal.showLoading() }
        });
    }
</script>