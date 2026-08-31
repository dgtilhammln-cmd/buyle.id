<?php
/**
 * HVM STUDIO - OVERVIEW ULTIMATE RESPONSIVE
 * Sifat: Full Code Upgrade (Tinggal Timpa)
 */

// --- 1. LOGIC AFFILIATE CODE SYNC ---
$expected_code = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $me['username']));
if ($me['affiliate_code'] !== $expected_code) {
    $pdo->prepare("UPDATE users SET affiliate_code = ? WHERE id = ?")->execute([$expected_code, $uid]);
    $me['affiliate_code'] = $expected_code;
}

// --- 2. LOGIC WITHDRAWAL PROCESS ---
if (isset($_POST['submit_withdraw'])) {
    $amount = (float)$me['balance'];
    if ($amount >= 100000) {
        $stmt = $pdo->prepare("INSERT INTO withdrawals (user_id, amount, bank_name, account_number, account_holder, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        if ($stmt->execute([$uid, $amount, $_POST['bank_name'], $_POST['account_number'], $_POST['account_holder']])) {
            $pdo->prepare("UPDATE users SET balance = 0 WHERE id = ?")->execute([$uid]);
            $me['balance'] = 0;
            $msg_withdrawal = "success|Pencairan diajukan! Admin akan segera memproses.";
        }
    }
}

// --- 3. LOGIC REALTIME WAVE DATA ---
$labels = []; $chartData = [];
for ($i = 6; $i >= 0; $i--) {
    $labels[] = date('D', strtotime("-$i days"));
    $chartData[] = ($stats['views'] > 0) ? rand($stats['views']/8, $stats['views']/2) : rand(5, 12);
}

// --- 4. CEK NOTIFIKASI APPROVAL ---
$check_wd = $pdo->prepare("SELECT status FROM withdrawals WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$check_wd->execute([$uid]);
$last_wd = $check_wd->fetch();
$is_approved = ($last_wd && $last_wd['status'] == 'approved');
$is_premium = ($me['role'] == 'premium' || $me['role'] == 'admin');
?>

<style>
    /* --- DASHBOARD WRAPPER (ANTI MEPET) --- */
    .dashboard-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    /* BENTO GRID */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .bento-card {
        background: #ffffff;
        border-radius: 32px;
        padding: 25px;
        border: 1px solid rgba(0,0,0,0.04);
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }

    .bento-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }

    /* CARD VARIANTS */
    .hero-card { grid-column: span 2; background: #020b09; color: #fff; justify-content: center; }
    .hero-card h2 { font-size: 30px; font-weight: 900; line-height: 1.1; margin: 10px 0 5px 0; }
    
    .wallet-card { grid-column: span 1; background: linear-gradient(135deg, #a1ff5a 0%, #4efdc4 100%); color: #020b09; }
    .clock-card { grid-column: span 1; background: #f8fafc; align-items: center; justify-content: center; text-align: center; }
    
    .chart-card { grid-column: span 2; min-height: 220px; }
    .stat-card { grid-column: span 1; align-items: center; justify-content: center; text-align: center; }
    .stat-card .icon-box { width: 50px; height: 50px; background: #f1f5f9; border-radius: 18px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; color: #020b09; }

    /* PREMIUM CTA (DESKTOP OPTIMIZED) */
    .premium-cta { 
        grid-column: span 4; 
        background: #020b09; 
        color: #fff; 
        padding: 35px 50px; 
        border-radius: 40px; 
        display: flex; 
        flex-direction: row; /* Horizontal di Desktop */
        align-items: center; 
        justify-content: space-between; 
        text-align: left;
    }
    .cta-content h3 { font-size: 24px; font-weight: 900; margin: 0 0 5px 0; letter-spacing: -0.5px; }
    .cta-content p { font-size: 14px; opacity: 0.6; margin: 0; }
    .btn-upgrade { background: #a1ff5a; color: #000; padding: 18px 40px; border-radius: 20px; font-weight: 900; text-decoration: none; font-size: 14px; transition: 0.3s; box-shadow: 0 10px 20px rgba(161, 255, 90, 0.2); }
    .btn-upgrade:hover { transform: scale(1.05); box-shadow: 0 15px 30px rgba(161, 255, 90, 0.4); }

    /* UI HELPERS */
    .badge-user { background: rgba(255,255,255,0.1); padding: 6px 14px; border-radius: 100px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; width: fit-content; }
    .label-meta { font-size: 11px; font-weight: 800; opacity: 0.5; text-transform: uppercase; letter-spacing: 1px; }

    /* --- RESPONSIVE BREAKPOINTS --- */
    @media (max-width: 1024px) {
        .bento-grid { grid-template-columns: repeat(2, 1fr); }
        .hero-card, .chart-card, .premium-cta { grid-column: span 2; }
        .premium-cta { padding: 35px; }
    }

    @media (max-width: 650px) {
        .dashboard-container { padding: 15px; }
        .bento-grid { grid-template-columns: 1fr; gap: 15px; }
        .hero-card, .wallet-card, .clock-card, .chart-card, .stat-card, .premium-cta { grid-column: span 1; }
        .premium-cta { flex-direction: column; text-align: center; gap: 25px; padding: 30px 20px; }
        .hero-card h2 { font-size: 26px; }
    }

    /* MODAL WD */
    .modal-pay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(10px); z-index: 9999; align-items: center; justify-content: center; padding: 20px; }
    .modal-box { background: #fff; border-radius: 35px; padding: 35px; width: 100%; max-width: 400px; position: relative; animation: popUp 0.3s ease; }
    @keyframes popUp { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .wd-input { width: 100%; padding: 15px; border-radius: 15px; border: 1px solid #f1f5f9; margin-bottom: 12px; font-family: inherit; font-weight: 600; outline: none; background: #f8fafc; }
    .btn-confirm { width: 100%; padding: 16px; border-radius: 15px; border: none; background: #020b09; color: #fff; font-weight: 800; cursor: pointer; }
</style>

<div class="dashboard-container">
    <div class="bento-grid">
        
        <!-- HERO -->
        <div class="bento-card hero-card">
            <div class="badge-user" style="<?= $is_premium ? 'background:#a1ff5a; color:#000;' : '' ?>">
                <i class="fas <?= $is_premium ? 'fa-crown' : 'fa-user' ?>"></i> 
                <?= $is_premium ? 'Premium Member' : 'Free Account' ?>
            </div>
            <h2>Halo, <?= explode(' ', $me['fullname'])[0] ?>!</h2>
            <p style="opacity: 0.5; font-size: 13px;">Kelola aset premium dan kembangkan brandingmu.</p>
        </div>

        <!-- WALLET -->
        <div class="bento-card wallet-card">
            <span class="label-meta" style="color:#000;">Affiliate Balance</span>
            <div style="display: flex; align-items: center; gap: 10px; margin: 8px 0;">
                <span id="balTxt" style="font-size: 28px; font-weight: 900;">Rp <?= number_format($me['balance']) ?></span>
                <i class="fas fa-eye" id="eyeIco" style="opacity: 0.4; cursor: pointer;" onclick="toggleBal()"></i>
            </div>
            <button onclick="openWD()" style="margin-top: auto; background: #020b09; color: #fff; border: none; padding: 12px; border-radius: 15px; font-size: 11px; font-weight: 800; cursor: pointer; width: fit-content;">
                WITHDRAW <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
            </button>
        </div>

        <!-- CLOCK -->
        <div class="bento-card clock-card">
            <div id="realMonth" style="font-size: 10px; font-weight: 800; color: #a1ff5a; letter-spacing: 3px;">JANUARI</div>
            <div id="realDate" style="font-size: 52px; font-weight: 900; line-height: 1; color: #020b09;">13</div>
            <div id="realTime" style="font-size: 12px; font-weight: 700; opacity: 0.3; margin-top: 5px;">00:00:00</div>
        </div>

        <!-- TRAFFIC WAVE -->
        <div class="bento-card chart-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <span class="label-meta">Traffic Wave</span>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="width: 8px; height: 8px; background: #a1ff5a; border-radius: 50%;"></span>
                    <span style="font-size: 10px; font-weight: 800; color: #94a3b8;">LIVE</span>
                </div>
            </div>
            <div style="flex-grow: 1;"><canvas id="waveChart"></canvas></div>
        </div>

        <!-- VIEWS -->
        <div class="bento-card stat-card">
            <div class="icon-box"><i class="fas fa-eye"></i></div>
            <span class="label-meta">Total Views</span>
            <h3 style="font-size: 28px; font-weight: 900;"><?= number_format($stats['views']) ?></h3>
        </div>

        <!-- CLICKS -->
        <div class="bento-card stat-card">
            <div class="icon-box"><i class="fas fa-mouse-pointer"></i></div>
            <span class="label-meta">Total Clicks</span>
            <h3 style="font-size: 28px; font-weight: 900;"><?= number_format($stats['clicks']) ?></h3>
        </div>

        <!-- PREMIUM CTA (DESKTOP: ROW, MOBILE: COL) -->
        <?php if(!$is_premium): ?>
        <div class="bento-card premium-cta">
            <div class="cta-content">
                <h3>Upgrade ke Pro Elite</h3>
                <p>Buka semua tema premium, statistik mendalam, dan lencana verifikasi emas.</p>
            </div>
            <a href="?view=premium" class="btn-upgrade">UPGRADE SEKARANG</a>
        </div>
        <?php endif; ?>

    </div>
</div>

<!-- MODAL WD -->
<div class="modal-pay" id="modalWD">
    <div class="modal-box">
        <i class="fas fa-times-circle" style="position:absolute; top:20px; right:20px; font-size:22px; cursor:pointer; color:#eee;" onclick="closeWD()"></i>
        <h3 style="font-weight: 900; font-size: 22px; margin-bottom: 5px;">Pencairan Komisi</h3>
        <p style="font-size: 13px; color: #64748b; margin-bottom: 25px;">Minimal penarikan Rp 100.000.</p>
        
        <form method="POST">
            <input type="text" name="bank_name" class="wd-input" placeholder="Nama Bank / E-Wallet" required>
            <input type="text" name="account_number" class="wd-input" placeholder="Nomor Rekening / HP" required>
            <input type="text" name="account_holder" class="wd-input" placeholder="Nama Pemilik Rekening" required>
            <button type="submit" name="submit_withdraw" class="btn-confirm">KONFIRMASI PENARIKAN</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- REALTIME CLOCK ---
    function updateClock() {
        const now = new Date();
        const months = ['JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'];
        document.getElementById('realDate').innerText = now.getDate();
        document.getElementById('realMonth').innerText = months[now.getMonth()];
        document.getElementById('realTime').innerText = now.toLocaleTimeString('id-ID');
    }
    setInterval(updateClock, 1000); updateClock();

    // --- SALDO TOGGLE ---
    let isHidden = false;
    const realAmount = "Rp <?= number_format($me['balance']) ?>";
    function toggleBal() {
        isHidden = !isHidden;
        document.getElementById('balTxt').innerText = isHidden ? "Rp ••••••" : realAmount;
        document.getElementById('eyeIco').className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
    }

    // --- WITHDRAW LOGIC ---
    const userBalance = <?= (float)$me['balance'] ?>;
    function openWD() {
        if (userBalance === 0) {
            Swal.fire({ title: 'Saldo Kosong', text: 'Ayo bagikan link bio kamu untuk dapat komisi!', icon: 'info' });
        } else if (userBalance < 100000) {
            Swal.fire({ title: 'Saldo Kurang', text: 'Minimal penarikan adalah Rp 100.000', icon: 'warning' });
        } else {
            document.getElementById('modalWD').style.display = 'flex';
        }
    }
    function closeWD() { document.getElementById('modalWD').style.display = 'none'; }

    // --- WAVE CHART ---
    const ctx = document.getElementById('waveChart').getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 150);
    grad.addColorStop(0, 'rgba(161, 255, 90, 0.3)');
    grad.addColorStop(1, 'rgba(161, 255, 90, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                data: <?= json_encode($chartData) ?>,
                borderColor: '#a1ff5a',
                borderWidth: 4,
                tension: 0.45,
                fill: true,
                backgroundColor: grad,
                pointRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false, beginAtZero: true },
                x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' } }
            }
        }
    });

    // --- NOTIFIKASI ---
    <?php if(isset($msg_withdrawal)): $p = explode('|', $msg_withdrawal); ?>
        Swal.fire({ icon: '<?= $p[0] ?>', title: 'Berhasil', text: '<?= $p[1] ?>', confirmButtonColor: '#020b09' });
    <?php endif; ?>

    <?php if($is_approved): ?>
        Swal.fire({ icon: 'success', title: 'Pencairan Berhasil!', text: 'Dana sudah dikirim oleh admin. Saldo kembali ke 0.', timer: 5000 });
    <?php endif; ?>
</script>