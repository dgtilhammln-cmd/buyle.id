<?php
// ... (BAGIAN ATAS PHP SAMA PERSIS DENGAN SEBELUMNYA, JANGAN DIUBAH SAMPAI TAG </head>) ...
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header("Location: /login"); exit; }
require_once __DIR__ . '/../config.php';
$uid = $_SESSION['user_id'];
$view = isset($_GET['view']) ? $_GET['view'] : 'editor'; // Default view via GET

// --- PROSES SIMPAN (COPY PASTE LOGIKA SEBELUMNYA DI SINI) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $view == 'editor') {
    // ... Copy logika simpan dari file sebelumnya ...
    // Agar singkat saya tulis: Logika simpan tetap sama
    function up($f, $n) { if(!empty($f['name'])) { $nm=$n.'_'.time().'.'.pathinfo($f['name'],PATHINFO_EXTENSION); move_uploaded_file($f['tmp_name'], "../uploads/".$nm); return $nm; } return null; }
    function getThumb($u) { if(empty($u))return''; $j=@file_get_contents("https://www.tiktok.com/oembed?url=".urlencode($u)); $d=json_decode($j,true); return $d['thumbnail_url']??''; }
    try {
        $t1=getThumb($_POST['tiktok_vid1']); $t2=getThumb($_POST['tiktok_vid2']); $t3=getThumb($_POST['tiktok_vid3']);
        $th = !empty($_POST['theme']) ? $_POST['theme'] : 'theme1';
        $sql = "UPDATE users SET fullname=?, nickname=?, role=?, location=?, drive_title=?, drive_url=?, ig_user=?, tt_user=?, wa_number=?, btn1_text=?, btn1_desc=?, btn1_url=?, btn2_text=?, btn2_desc=?, btn2_url=?, tiktok_vid1=?, tiktok_vid2=?, tiktok_vid3=?, theme=?";
        $p = [$_POST['fullname'],$_POST['nickname'],$_POST['role'],$_POST['location'],$_POST['drive_title'],$_POST['drive_url'],$_POST['ig_user'],$_POST['tt_user'],$_POST['wa_number'],$_POST['btn1_text'],$_POST['btn1_desc'],$_POST['btn1_url'],$_POST['btn2_text'],$_POST['btn2_desc'],$_POST['btn2_url'],$_POST['tiktok_vid1'],$_POST['tiktok_vid2'],$_POST['tiktok_vid3'],$th];
        if($t1){$sql.=", tiktok_thumb1=?"; $p[]=$t1;} if($t2){$sql.=", tiktok_thumb2=?"; $p[]=$t2;} if($t3){$sql.=", tiktok_thumb3=?"; $p[]=$t3;}
        $sql .= " WHERE id=?"; $p[]=$uid;
        $pdo->prepare($sql)->execute($p);
        if($n=up($_FILES['cover_pic'],'cov')) $pdo->prepare("UPDATE users SET cover_pic=? WHERE id=?")->execute([$n,$uid]);
        if($n=up($_FILES['profile_pic'],'pro')) $pdo->prepare("UPDATE users SET profile_pic=? WHERE id=?")->execute([$n,$uid]);
        if($n=up($_FILES['btn1_img'],'b1')) $pdo->prepare("UPDATE users SET btn1_img=? WHERE id=?")->execute([$n,$uid]);
        if($n=up($_FILES['btn2_img'],'b2')) $pdo->prepare("UPDATE users SET btn2_img=? WHERE id=?")->execute([$n,$uid]);
        header("Location: /dashboard?view=editor&saved=1"); exit;
    } catch (Exception $e) { die($e->getMessage()); }
}

$me = $pdo->prepare("SELECT * FROM users WHERE id=?"); $me->execute([$uid]); $me=$me->fetch();
$current_theme = $me['theme'] ?: 'theme1';

// --- DATA ANALYTICS (LOGIKA SAMA) ---
$stats = ['views'=>0, 'clicks'=>0]; $labels = []; $chartData = [];
if ($view == 'analytics') {
    $stats['views'] = $pdo->query("SELECT COUNT(*) FROM analytics WHERE user_id=$uid AND event_type='page_view'")->fetchColumn();
    $stats['clicks'] = $pdo->query("SELECT COUNT(*) FROM analytics WHERE user_id=$uid AND event_type!='page_view'")->fetchColumn();
    $stmt = $pdo->prepare("SELECT DATE(created_at) as tgl, event_type, COUNT(*) as total FROM analytics WHERE user_id=? AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY tgl, event_type ORDER BY tgl ASC");
    $stmt->execute([$uid]); $rows = $stmt->fetchAll();
    $tempData = [];
    for($i=6; $i>=0; $i--) { $d = date('Y-m-d', strtotime("-$i days")); $labels[] = date('d M', strtotime($d)); $tempData['page_view'][$d] = 0; $tempData['click'][$d] = 0; }
    foreach($rows as $r) { $k = ($r['event_type'] == 'page_view') ? 'page_view' : 'click'; if(isset($tempData[$k][$r['tgl']])) $tempData[$k][$r['tgl']] += $r['total']; }
    $chartData['page_view'] = array_values($tempData['page_view']); $chartData['click'] = array_values($tempData['click']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>HVM Dashboard Pro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="/dashboard/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if($view == 'editor'): ?>
    <link id="preview-css" rel="stylesheet" href="/templates/<?= $current_theme ?>/style.css?v=<?= time() ?>">
    <style> #mockup-screen { background-color: <?= $current_theme == 'theme2' ? '#F8FAFC' : '#000000' ?>; } </style>
    <?php endif; ?>
</head>
<body>

<div class="app-layout">
    
    <!-- DESKTOP SIDEBAR (Collapsible) -->
    <nav class="sidebar" id="sidebar">
        <div class="brand">
            <i class="fas fa-bolt"></i> <span>HVM STUDIO</span>
        </div>
        <div class="menu">
            <a href="?view=editor" class="nav-item <?= $view=='editor'?'active':'' ?>">
                <i class="fas fa-pen-nib"></i> <span class="nav-text">Editor</span>
            </a>
            <a href="?view=analytics" class="nav-item <?= $view=='analytics'?'active':'' ?>">
                <i class="fas fa-chart-line"></i> <span class="nav-text">Analytics</span>
            </a>
            <a href="/<?= $me['username'] ?>" target="_blank" class="nav-item">
                <i class="fas fa-external-link-alt"></i> <span class="nav-text">Live Page</span>
            </a>
        </div>
        <div class="sidebar-footer">
            <button class="btn-toggle" onclick="toggleSidebar()"><i class="fas fa-chevron-left"></i></button>
            <a href="/logout" class="nav-item" style="color:#ff4444; margin-top:5px;">
                <i class="fas fa-power-off"></i> <span class="nav-text">Logout</span>
            </a>
        </div>
    </nav>

    <main class="content-area">
        
        <!-- ====== VIEW: EDITOR ====== -->
        <?php if ($view == 'editor'): ?>
        <div class="editor-grid">
            <div class="form-container">
                <div class="page-header">
                    <h2>Profile Editor</h2>
                    <p>Customize your digital identity.</p>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    
                    <!-- TEMA SELECTOR -->
                    <div class="card-glass theme-select">
                        <div class="card-title"><i class="fas fa-palette"></i> TAMPILAN</div>
                        <select name="theme" onchange="changeTheme(this.value)">
                            <option value="theme1" <?= $current_theme=='theme1'?'selected':'' ?>>⚫ Dark Luxury (Default)</option>
                            <option value="theme2" <?= $current_theme=='theme2'?'selected':'' ?>>🔵 Clean Blue Future</option>
                        </select>
                    </div>

                    <!-- IDENTITY FORM -->
                    <div class="card-glass">
                        <div class="card-title"><i class="fas fa-fingerprint"></i> IDENTITAS</div>
                        <div class="row">
                            <div class="col">
                                <label>Cover Image</label>
                                <div class="file-upload-btn">
                                    <i class="fas fa-cloud-upload-alt"></i> Upload Cover
                                    <input type="file" name="cover_pic" onchange="prev(this,'prev_cover',1)">
                                </div>
                            </div>
                            <div class="col">
                                <label>Profile Photo</label>
                                <div class="file-upload-btn">
                                    <i class="fas fa-user-circle"></i> Upload Profile
                                    <input type="file" name="profile_pic" onchange="prev(this,'prev_prof',0)">
                                </div>
                            </div>
                        </div>
                        <label>Nama Lengkap</label>
                        <input type="text" name="fullname" value="<?= htmlspecialchars($me['fullname']) ?>" oninput="upTxt('live_fullname',this.value)">
                        <div style="height:10px;"></div>
                        <label>Nickname / Bio Singkat</label>
                        <input type="text" name="nickname" value="<?= htmlspecialchars($me['nickname']) ?>" oninput="upTxt('live_nickname',this.value)">
                        <div class="row" style="margin-top:10px;">
                            <div class="col"><input type="text" name="role" placeholder="Role (e.g Creator)" value="<?= htmlspecialchars($me['role']) ?>" oninput="upTxt('live_role',this.value)"></div>
                            <div class="col"><input type="text" name="location" placeholder="Lokasi (e.g Jakarta)" value="<?= htmlspecialchars($me['location']) ?>" oninput="upTxt('live_location',this.value)"></div>
                        </div>
                    </div>

                    <!-- CONTENT FORM -->
                    <div class="card-glass">
                        <div class="card-title"><i class="fas fa-link"></i> LINK & KONTEN</div>
                        <label>Google Drive / Produk Utama</label>
                        <input type="text" name="drive_title" placeholder="Nama Produk" value="<?= htmlspecialchars($me['drive_title']) ?>" oninput="upTxt('live_drive_title',this.value)">
                        <div style="height:8px;"></div>
                        <input type="text" name="drive_url" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($me['drive_url']) ?>">
                        
                        <div style="border-top:1px solid rgba(255,255,255,0.1); margin:20px 0;"></div>
                        
                        <label>Custom Button 1</label>
                        <div class="row">
                            <div class="col"><input type="text" name="btn1_text" placeholder="Judul" value="<?= htmlspecialchars($me['btn1_text']) ?>" oninput="upTxt('live_btn1_text',this.value)"></div>
                            <div class="col" style="flex:0 0 60px;">
                                <div class="file-upload-btn" style="height:48px;"><i class="fas fa-image"></i><input type="file" name="btn1_img"></div>
                            </div>
                        </div>
                        <input type="text" name="btn1_desc" placeholder="Deskripsi singkat" value="<?= htmlspecialchars($me['btn1_desc']) ?>" oninput="upTxt('live_btn1_desc',this.value)">
                        <input type="text" name="btn1_url" placeholder="https://..." value="<?= htmlspecialchars($me['btn1_url']) ?>" style="margin-top:8px;">

                        <div style="border-top:1px dashed rgba(255,255,255,0.1); margin:15px 0;"></div>

                        <label>Custom Button 2</label>
                        <div class="row">
                            <div class="col"><input type="text" name="btn2_text" placeholder="Judul" value="<?= htmlspecialchars($me['btn2_text']) ?>" oninput="upTxt('live_btn2_text',this.value)"></div>
                            <div class="col" style="flex:0 0 60px;">
                                <div class="file-upload-btn" style="height:48px;"><i class="fas fa-image"></i><input type="file" name="btn2_img"></div>
                            </div>
                        </div>
                        <input type="text" name="btn2_desc" placeholder="Deskripsi singkat" value="<?= htmlspecialchars($me['btn2_desc']) ?>" oninput="upTxt('live_btn2_desc',this.value)">
                        <input type="text" name="btn2_url" placeholder="https://..." value="<?= htmlspecialchars($me['btn2_url']) ?>" style="margin-top:8px;">
                    </div>

                    <!-- SOCIAL FORM -->
                    <div class="card-glass">
                        <div class="card-title"><i class="fas fa-share-alt"></i> SOSIAL MEDIA</div>
                        <div class="row">
                            <div class="col"><input type="text" name="ig_user" placeholder="Instagram Username" value="<?= htmlspecialchars($me['ig_user']) ?>"></div>
                            <div class="col"><input type="text" name="tt_user" placeholder="TikTok Username" value="<?= htmlspecialchars($me['tt_user']) ?>"></div>
                        </div>
                        <input type="text" name="wa_number" placeholder="WhatsApp (62812xxx)" value="<?= htmlspecialchars($me['wa_number']) ?>">
                        
                        <div style="margin-top:20px;"><label>TikTok Video URLs (Untuk Slider)</label></div>
                        <input type="text" name="tiktok_vid1" placeholder="Video URL 1" value="<?= htmlspecialchars($me['tiktok_vid1']) ?>" style="margin-bottom:8px;">
                        <input type="text" name="tiktok_vid2" placeholder="Video URL 2" value="<?= htmlspecialchars($me['tiktok_vid2']) ?>" style="margin-bottom:8px;">
                        <input type="text" name="tiktok_vid3" placeholder="Video URL 3" value="<?= htmlspecialchars($me['tiktok_vid3']) ?>">
                    </div>

                    <div class="sticky-dock">
                        <div class="dock-content">
                            <span style="color:#aaa; font-size:12px;"><i class="fas fa-check-circle" style="color:#2bff88;"></i> Live Preview</span>
                            <button type="submit" class="btn-save">SIMPAN PERUBAHAN</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- PREVIEW PANEL (KANAN) -->
            <div class="preview-area">
                <div class="iphone-mockup">
                    <div class="notch"></div>
                    <div id="mockup-screen" class="preview-wrapper" style="height:100%; overflow-y:auto; overflow-x:hidden;">
                        <!-- INCLUDE FILE TEMPLATE UNTUK PREVIEW -->
                        <div class="container">
                            <div class="header-wrapper">
                                <div class="cover-area" id="prev_cover" style="background-image: url('/uploads/<?= $me['cover_pic']?:'default_cover.jpg' ?>');"></div>
                                <div class="profile-container">
                                    <div class="profile-box"><img src="/uploads/<?= $me['profile_pic']?:'default.png' ?>" id="prev_prof"></div>
                                    <h1 id="live_fullname"><?= $me['fullname']?:'Nama Anda' ?></h1>
                                    <span class="nick" id="live_nickname"><?= $me['nickname']?:'Nickname' ?></span>
                                    <div class="badges"><div class="badge"><span id="live_role"><?= $me['role']?:'Role' ?></span></div><div class="badge"><span id="live_location"><?= $me['location']?:'Lokasi' ?></span></div></div>
                                </div>
                            </div>
                            
                            <div class="drive-card">
                                <i class="fab fa-google-drive drive-icon"></i>
                                <div class="drive-info">
                                    <div class="drive-title" id="live_drive_title"><?= $me['drive_title']?:'Nama Produk' ?></div>
                                    <div class="drive-sub">Tap to Access</div>
                                </div>
                            </div>

                            <div class="video-section-title" style="padding:0 20px; margin-bottom:10px;">HIGHLIGHTS</div>
                            <div class="video-slider">
                                <div class="video-card"><div class="vid-bg-effect"></div><i class="fab fa-tiktok vid-icon" style="font-size:30px; color:white; z-index:2;"></i></div>
                                <div class="video-card"><div class="vid-bg-effect"></div></div>
                            </div>

                            <div class="link-stack" style="margin-top:20px;">
                                <div class="glass-btn"><div class="btn-icon-fa ig-color"><i class="fab fa-instagram"></i></div><div class="btn-info"><div class="btn-title">Instagram</div><div class="btn-desc">Follow Updates</div></div></div>
                                <div class="glass-btn">
                                    <div class="btn-img-box"><img src="/uploads/<?= $me['btn1_img'] ?>" id="prev_btn1"></div>
                                    <div class="btn-info"><div class="btn-title" id="live_btn1_text"><?= $me['btn1_text']?:'Button 1' ?></div><div class="btn-desc" id="live_btn1_desc"><?= $me['btn1_desc']?:'Deskripsi' ?></div></div>
                                </div>
                                <div class="glass-btn">
                                    <div class="btn-img-box"><img src="/uploads/<?= $me['btn2_img'] ?>" id="prev_btn2"></div>
                                    <div class="btn-info"><div class="btn-title" id="live_btn2_text"><?= $me['btn2_text']?:'Button 2' ?></div><div class="btn-desc" id="live_btn2_desc"><?= $me['btn2_desc']?:'Deskripsi' ?></div></div>
                                </div>
                            </div>
                            <div style="height:50px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function toggleSidebar() { document.getElementById('sidebar').classList.toggle('collapsed'); }
            function changeTheme(n) { 
                document.getElementById('preview-css').href = '/templates/'+n+'/style.css?v='+Date.now();
                document.getElementById('mockup-screen').style.backgroundColor = (n==='theme2') ? '#F8FAFC' : '#000000';
            }
            function upTxt(id, v) { let el = document.getElementById(id); if(el) el.innerText = v ? v : '...'; }
            function prev(i, id, bg) {
                if(i.files[0]){ let r=new FileReader(); r.onload=function(e){ 
                    let t=document.getElementById(id); if(bg)t.style.backgroundImage='url('+e.target.result+')'; else t.src=e.target.result;
                }; r.readAsDataURL(i.files[0]); }
            }
            <?php if(isset($_GET['saved'])): ?>
            Swal.fire({icon:'success',title:'Berhasil Disimpan',toast:true,position:'top-end',showConfirmButton:false,timer:2000,background:'#111',color:'#fff'});
            window.history.replaceState(null,null,window.location.pathname + window.location.search.replace('&saved=1',''));
            <?php endif; ?>
        </script>
        <?php endif; ?>

        <!-- ====== VIEW: ANALYTICS ====== -->
        <?php if ($view == 'analytics'): ?>
        <div class="analytics-container">
            <div class="page-header">
                <h2>Performance Analytics</h2>
                <p>Data aktivitas pengunjung 7 hari terakhir.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-eye"></i></div>
                    <div class="stat-info"><h3><?= number_format($stats['views']) ?></h3><p>Total Views</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color:#3b82f6; border-color:rgba(59,130,246,0.3);"><i class="fas fa-mouse-pointer"></i></div>
                    <div class="stat-info"><h3><?= number_format($stats['clicks']) ?></h3><p>Total Clicks</p></div>
                </div>
            </div>

            <div class="chart-box">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>
        <script>
            const ctx = document.getElementById('trafficChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [
                        { label: 'Page Views', data: <?= json_encode($chartData['page_view']) ?>, borderColor: '#fff', backgroundColor: 'rgba(255,255,255,0.1)', tension: 0.4, fill:true },
                        { label: 'Total Clicks', data: <?= json_encode($chartData['click']) ?>, borderColor: '#3b82f6', tension: 0.4, borderDash:[5,5] }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { labels: { color: '#ccc' } } },
                    scales: { 
                        y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#888' } },
                        x: { grid: { display: false }, ticks: { color: '#888' } }
                    }
                }
            });
        </script>
        <?php endif; ?>

    </main>

    <!-- MOBILE BOTTOM NAV (GLASSMOPRHISM) -->
    <div class="mobile-nav">
        <a href="?view=editor" class="<?= $view=='editor'?'active':'' ?>"><i class="fas fa-pen-nib"></i></a>
        <a href="?view=analytics" class="<?= $view=='analytics'?'active':'' ?>"><i class="fas fa-chart-line"></i></a>
        <a href="/<?= $me['username'] ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
        <a href="/logout" style="color:#ff4444;"><i class="fas fa-power-off"></i></a>
    </div>

</div>
<!-- ... kode dashboard sebelumnya (script chart, toggle sidebar dll) ... -->

    <!-- INCLUDE MEMBER POPUP -->
    <?php include __DIR__ . '/popup_member.php'; ?>

</body>
</html>