<?php
require_once __DIR__ . '/../../config.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') die('Unauthorized');

$uid = $_GET['id'];
$user = $pdo->prepare("SELECT u.*, r.username as reff_by FROM users u LEFT JOIN users r ON u.referrer_id = r.id WHERE u.id = ?");
$user->execute([$uid]);
$u = $user->fetch();

// Logika Get Foto Profil
$avatar_path = "../../uploads/p_" . $u['username'] . ".jpg"; 
$avatar_url = file_exists($avatar_path) ? "uploads/p_" . $u['username'] . ".jpg" : "";
$initial = strtoupper(substr($u['username'], 0, 1));

// Data Riwayat
$p_list = $pdo->prepare("SELECT * FROM payments WHERE user_id = ? ORDER BY id DESC LIMIT 5");
$p_list->execute([$uid]);
$w_list = $pdo->prepare("SELECT * FROM withdrawals WHERE user_id = ? ORDER BY id DESC LIMIT 5");
$w_list->execute([$uid]);
?>

<div style="display:grid; grid-template-columns: 320px 1fr; gap:30px;">
    
    <!-- LEFT COLUMN: Profile Card -->
    <div style="background: #fcfcfc; border-radius: 35px; padding: 30px; border: 1px solid #f1f5f9; text-align: center;">
        <div style="position: relative; width: 120px; height: 120px; margin: 0 auto 20px;">
            <?php if($avatar_url): ?>
                <img src="<?= $avatar_url ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 40px; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
            <?php else: ?>
                <div style="width: 100%; height: 100%; background: #020b09; color: #a1ff5a; border-radius: 40px; display: flex; align-items: center; justify-content: center; font-size: 50px; font-weight: 800;"><?= $initial ?></div>
            <?php endif; ?>
            <div style="position: absolute; bottom: -5px; right: -5px; width: 35px; height: 35px; background: <?= $u['role'] == 'premium' ? '#a1ff5a' : '#eee' ?>; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 4px solid #fff;">
                <i class="fas <?= $u['role'] == 'premium' ? 'fa-crown' : 'fa-user' ?>" style="font-size: 12px; color: #000;"></i>
            </div>
        </div>

        <h2 style="font-weight: 800; font-size: 22px; margin-bottom: 5px;"><?= $u['fullname'] ?></h2>
        <a href="https://bio.hvmdigital.id/<?= $u['username'] ?>" target="_blank" style="color: #94a3b8; font-weight: 700; text-decoration: none; font-size: 14px;">@<?= $u['username'] ?> <i class="fas fa-external-link-alt" style="font-size: 10px;"></i></a>

        <div style="margin-top: 25px; text-align: left; background: #fff; padding: 20px; border-radius: 25px; border: 1px solid #f1f5f9;">
            <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-envelope" style="color: #94a3b8;"></i>
                <a href="mailto:<?= $u['email'] ?>" style="font-size: 12px; color: #020b09; font-weight: 700; text-decoration: none;"><?= $u['email'] ?></a>
            </div>
            <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 12px;">
                <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $u['phone']) ?>" target="_blank" style="font-size: 12px; color: #020b09; font-weight: 700; text-decoration: none;"><?= $u['phone'] ?></a>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-wallet" style="color: #a1ff5a;"></i>
                <span style="font-size: 12px; color: #020b09; font-weight: 700;">Rp <?= number_format($u['balance']) ?></span>
            </div>
        </div>

        <!-- QUICK ACTIONS CARD -->
        <div style="margin-top: 20px; text-align: left; background: #020b09; padding: 20px; border-radius: 25px; color: #fff;">
            <small style="font-weight: 800; opacity: 0.5; font-size: 9px; letter-spacing: 1px; display: block; margin-bottom: 10px;">CONTROL PANEL</small>
            
            <form method="POST" action="?page=accounts">
                <input type="hidden" name="target_uid" value="<?= $u['id'] ?>">
                <input type="hidden" name="action_type" value="toggle_premium">
                <input type="hidden" name="current_role" value="<?= $u['role'] ?>">
                
                <?php if($u['role'] != 'premium'): ?>
                    <select name="duration_months" style="width: 100%; padding: 10px; border-radius: 10px; margin-bottom: 10px; font-weight: 700; font-family: inherit;">
                        <option value="1">Aktif 1 Bulan</option>
                        <option value="6">Aktif 6 Bulan</option>
                        <option value="12">Aktif 12 Bulan</option>
                        <option value="120">LIFETIME</option>
                    </select>
                    <button type="submit" style="width: 100%; padding: 12px; border-radius: 12px; border: none; background: #a1ff5a; color: #000; font-weight: 800; cursor: pointer;">AKTIFKAN PRO</button>
                <?php else: ?>
                    <div style="background: rgba(161, 255, 90, 0.1); padding: 10px; border-radius: 10px; margin-bottom: 10px; border: 1px solid rgba(161, 255, 90, 0.3);">
                        <small style="display: block; font-size: 9px;">PREMIUM EXPIRES:</small>
                        <b style="font-size: 12px; color: #a1ff5a;"><?= date('d M Y', strtotime($u['premium_until'])) ?></b>
                    </div>
                    <button type="submit" style="width: 100%; padding: 12px; border-radius: 12px; border: none; background: #dc2626; color: #fff; font-weight: 800; cursor: pointer;">HENTIKAN PRO</button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- RIGHT COLUMN: History Cards -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        
        <!-- Upgrade Card -->
        <div style="background: #fff; border-radius: 35px; padding: 30px; border: 1px solid #f1f5f9;">
            <h4 style="margin: 0 0 20px 0; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-history" style="color: #94a3b8;"></i> Riwayat Transaksi Upgrade
            </h4>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <?php $p_data = $p_list->fetchAll(); if($p_data): foreach($p_data as $p): ?>
                    <div style="background: #f8fafc; padding: 15px 20px; border-radius: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <b style="font-size: 13px; display: block;">Upgrade Pro Elite</b>
                            <small style="color: #94a3b8; font-weight: 600;"><?= date('d M Y', strtotime($p['created_at'])) ?></small>
                        </div>
                        <span style="font-weight: 800; color: #10b981;">Rp <?= number_format($p['amount']) ?></span>
                    </div>
                <?php endforeach; else: ?>
                    <div style="padding: 20px; text-align: center; color: #cbd5e1; font-weight: 600;">Belum ada transaksi pendaftaran.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Withdraw Card -->
        <div style="background: #fff; border-radius: 35px; padding: 30px; border: 1px solid #f1f5f9;">
            <h4 style="margin: 0 0 20px 0; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-wallet" style="color: #94a3b8;"></i> Riwayat Pencairan Komisi
            </h4>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <?php $w_data = $w_list->fetchAll(); if($w_data): foreach($w_data as $w): ?>
                    <div style="background: #f8fafc; padding: 15px 20px; border-radius: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <b style="font-size: 13px; display: block;">Pencairan Komisi</b>
                            <small style="color: #94a3b8; font-weight: 600;"><?= date('d M Y', strtotime($w['created_at'])) ?></small>
                        </div>
                        <div style="text-align: right;">
                            <b style="font-size: 14px; display: block;">Rp <?= number_format($w['amount']) ?></b>
                            <span style="font-size: 9px; font-weight: 800; text-transform: uppercase; color: <?= $w['status'] == 'approved' ? '#10b981' : '#f59e0b' ?>"><?= $w['status'] ?></span>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <div style="padding: 20px; text-align: center; color: #cbd5e1; font-weight: 600;">Belum ada riwayat penarikan.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>