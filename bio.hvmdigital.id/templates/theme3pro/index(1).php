<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <!-- 1. DYNAMIC SEO META TAGS -->
    <title><?= htmlspecialchars($user['fullname']) ?> | Official Profile & Portfolio</title>
    <meta name="description" content="Kunjungi profil resmi <?= htmlspecialchars($user['fullname']) ?>. <?= htmlspecialchars($user['role_display']) ?> yang berlokasi di <?= htmlspecialchars($user['location']) ?>. Temukan portofolio, kontak, dan karya terbaru di sini.">
    <meta name="keywords" content="<?= htmlspecialchars($user['fullname']) ?>, <?= htmlspecialchars($user['role_display']) ?>, Bio Link, HVM Digital, Portfolio, Contact">
    <meta name="author" content="<?= htmlspecialchars($user['fullname']) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://bio.hvmdigital.id/<?= $user['username'] ?>">

    <!-- 2. OPEN GRAPH (Agar saat di-share di WA/IG/FB gambarnya muncul & mewah) -->
    <meta property="og:type" content="profile">
    <meta property="og:title" content="<?= htmlspecialchars($user['fullname']) ?> - Official Link">
    <meta property="og:description" content="Lihat profil lengkap, pengalaman, dan hubungi <?= htmlspecialchars($user['fullname']) ?> secara langsung.">
    <meta property="og:image" content="https://bio.hvmdigital.id/uploads/<?= $user['profile_pic'] ?: 'defaultprofile.png' ?>">
    <meta property="og:url" content="https://bio.hvmdigital.id/<?= $user['username'] ?>">
    <meta property="og:site_name" content="HVM Studio">

    <!-- 3. TWITTER CARD (X) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($user['fullname']) ?>">
    <meta name="twitter:description" content="Profesional Profile of <?= htmlspecialchars($user['fullname']) ?> - <?= htmlspecialchars($user['role_display']) ?>">
    <meta name="twitter:image" content="https://bio.hvmdigital.id/uploads/<?= $user['cover_pic'] ?: 'defaultsampul.png' ?>">

    <!-- 4. SCHEMA MARKUP (KUNCI AGAR AI FRIENDLY / RICH SNIPPET) -->
    <!-- Ini membuat Google paham ini adalah "Orang" bukan sekedar halaman web kosong -->
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Person",
      "name": "<?= $user['fullname'] ?>",
      "jobTitle": "<?= $user['role_display'] ?>",
      "image": "https://bio.hvmdigital.id/uploads/<?= $user['profile_pic'] ?: 'defaultprofile.png' ?>",
      "url": "https://bio.hvmdigital.id/<?= $user['username'] ?>",
      "sameAs": [
        "https://instagram.com/<?= $user['ig_user'] ?>",
        "https://tiktok.com/@<?= $user['tt_user'] ?>"
      ],
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "<?= $user['location'] ?>"
      },
      "description": "<?= $user['fullname'] ?> adalah seorang <?= $user['role_display'] ?>. Lihat pengalaman dan portofolio lengkapnya di HVM Digital."
    }
    </script>

    <!-- Favicon & Styles -->
    <link rel="icon" type="image/png" href="/assets/images/logobio.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- (CSS ANDA DI BAWAH INI TETAP SAMA SEPERTI YANG SAYA BUAT SEBELUMNYA) -->
    <style>
        /* --- ONYX NOIR PREMIUM SYSTEM v7.0 --- */
        :root {
            --accent: #a1ff5a;           /* Neon Green */
            --dark: #020b09;             /* Obsidian Dark */
            --dark-card: #111111;        /* Secondary Dark */
            --white: #ffffff;
            --soft-gray: #f1f5f9;
            --border: rgba(0, 0, 0, 0.05);
            --side-margin: 24px;
            --shadow-lux: 0 20px 40px rgba(0,0,0,0.06);
            --trans: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        body { 
            margin: 0; padding: 0; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--soft-gray);
            overflow-x: hidden;
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
        }

        /* --- DESKTOP VIEW LIMITER (IPHONE MOCKUP) --- */
        @media (min-width: 992px) {
            body {
                background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
                display: flex; align-items: center; justify-content: center; min-height: 100vh;
            }
            .iphone-frame {
                width: 385px; height: 800px; background: #000; border-radius: 60px;
                border: 12px solid #1a1a1a; position: relative; overflow: hidden;
                box-shadow: 0 50px 100px rgba(0,0,0,0.3); zoom: 0.9;
            }
            .iphone-notch {
                position: absolute; top: 0; left: 50%; transform: translateX(-50%);
                width: 150px; height: 30px; background: #1a1a1a; border-radius: 0 0 22px 22px; z-index: 100;
            }
            .main-content { height: 100%; overflow-y: auto; background: var(--white); scrollbar-width: none; }
            .main-content::-webkit-scrollbar { display: none; }
        }

        /* --- CONTENT WRAPPER --- */
        .main-content { width: 100%; position: relative; padding-bottom: 80px; background: var(--white); }

        /* Luxury Header Construction */
        .header-v4 { position: relative; background: var(--dark); padding-bottom: 40px; border-radius: 0 0 50px 50px; }
        .cover-img { height: 220px; width: 100%; object-fit: cover; opacity: 0.5; }
        .profile-overlap { margin-top: -80px; text-align: center; padding: 0 var(--side-margin); }
        .avatar { 
            width: 130px; height: 130px; border-radius: 45px; border: 7px solid var(--dark); 
            object-fit: cover; background: #111; box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .name { font-size: 26px; font-weight: 800; color: var(--white); margin: 20px 0 5px; letter-spacing: -0.8px; }
        .name i { color: var(--accent); font-size: 18px; margin-left: 6px; filter: drop-shadow(0 0 10px rgba(161,255,90,0.3)); }
        .role-text { color: var(--accent); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 3px; }

        .location-tag { 
            display: inline-flex; align-items: center; gap: 8px; 
            background: rgba(255,255,255,0.06); padding: 8px 20px; 
            border-radius: 100px; color: #999; font-size: 11px; font-weight: 600; margin-top: 15px;
            border: 1px solid rgba(255,255,255,0.08);
        }

        /* Headlines */
        .label-premium { 
            font-size: 12px; font-weight: 800; letter-spacing: 2px; color: var(--dark); 
            margin: 45px var(--side-margin) 20px; text-transform: uppercase; 
            display: flex; align-items: center; gap: 12px;
        }
        .label-premium::after { content: ''; flex: 1; height: 1px; background: var(--soft-gray); }

        /* --- HITAM DOMINAN COMPONENT (DRIVE) --- */
        .drive-black-pill {
            background: var(--dark); color: var(--white);
            margin: 30px var(--side-margin) 0; padding: 25px; border-radius: 30px;
            display: flex; align-items: center; justify-content: space-between;
            text-decoration: none; transition: var(--trans);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05);
        }
        .drive-black-pill:hover { transform: scale(1.02); border-color: var(--accent); }
        .drive-icon-lux { width: 55px; height: 55px; background: rgba(161,255,90,0.1); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 24px; }

        /* --- EXPERIENCE LANDSCAPE SYSTEM --- */
        .slider-wrapper {
            display: flex; gap: 15px; overflow-x: auto; padding: 0 var(--side-margin) 15px;
            scrollbar-width: none; scroll-snap-type: x mandatory;
            margin-left: calc(var(--side-margin) * -1);
            margin-right: calc(var(--side-margin) * -1);
            padding-left: var(--side-margin);
            width: calc(100% + (var(--side-margin) * 2));
        }
        .slider-wrapper::-webkit-scrollbar { display: none; }
        .slider-wrapper::after { content: ''; flex: 0 0 var(--side-margin); }

        .exp-noir-card {
            flex: 0 0 290px; background: var(--white); border: 1px solid #eee;
            border-radius: 32px; overflow: hidden; scroll-snap-align: start;
            box-shadow: var(--shadow-lux); transition: 0.3s;
        }
        .exp-noir-img { width: 100%; height: 160px; object-fit: cover; }
        .exp-noir-body { padding: 22px; }
        .exp-noir-body h3 { font-size: 16px; font-weight: 800; margin: 0 0 8px; color: var(--dark); letter-spacing: -0.5px; }
        .exp-noir-body p { font-size: 12px; line-height: 1.7; color: #64748b; margin: 0; }

        /* --- TIKTOK PORTRAIT SYSTEM --- */
        .video-noir-card {
            flex: 0 0 165px; height: 285px; border-radius: 28px; 
            position: relative; overflow: hidden; background: #000;
            border: 1px solid var(--border); scroll-snap-align: start;
            text-decoration: none; transition: var(--trans);
        }
        .video-noir-card:hover { transform: translateY(-10px); border-color: var(--accent); }
        .tt-fetch-img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; z-index: 1; opacity: 0; transition: 0.6s; }
        .video-noir-card::after {
            content: ''; position: absolute; inset: 0; z-index: 2;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, transparent 65%);
        }
        .video-noir-card i { position: absolute; top: 18px; left: 18px; z-index: 3; font-size: 20px; color: var(--white); opacity: 0.9; }
        .noir-vid-label { position: absolute; bottom: 20px; left: 20px; z-index: 3; font-size: 9px; font-weight: 900; color: var(--white); text-transform: uppercase; letter-spacing: 1.5px; }

        /* --- BLACK DOMINANT BUTTONS --- */
        .social-noir-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; padding: 0 var(--side-margin); }
        .social-noir-tile { 
            height: 70px; background: var(--dark); border-radius: 25px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 22px; color: var(--white); text-decoration: none; transition: var(--trans);
            border: 1px solid rgba(255,255,255,0.05);
        }
        .social-noir-tile:hover { background: var(--accent); color: var(--dark); transform: translateY(-5px); box-shadow: 0 15px 30px rgba(161,255,90,0.2); }

        .btn-noir-stack { padding: 0 var(--side-margin); margin-top: 20px; display: flex; flex-direction: column; gap: 15px; }
        .btn-noir-item { 
            background: var(--dark); border: 1px solid rgba(255,255,255,0.08); 
            border-radius: 28px; padding: 18px;
            display: flex; align-items: center; gap: 18px; text-decoration: none; color: var(--white);
            transition: var(--trans);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .btn-noir-item:hover { transform: translateX(8px); border-color: var(--accent); }
        
        .btn-noir-media { 
            width: 52px; height: 52px; border-radius: 18px; object-fit: cover; 
            background: #222; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.1);
        }
        .btn-noir-info { flex: 1; }
        .btn-noir-info h4 { margin: 0; font-size: 15px; font-weight: 700; color: var(--white); }
        .btn-noir-info p { margin: 4px 0 0; font-size: 11px; color: #777; font-weight: 500; }
        .btn-noir-arrow { color: #444; font-size: 14px; margin-right: 5px; }

        /* Luxury Footer */
        .footer-noir { text-align: center; margin-top: 70px; padding-bottom: 60px; }
        .footer-noir a { 
            text-decoration: none; color: #bbb; font-size: 11px; font-weight: 800; 
            display: inline-flex; align-items: center; gap: 12px; text-transform: uppercase; letter-spacing: 2px;
        }
        .footer-logo-lux { height: 18px; opacity: 0.5; filter: grayscale(1) brightness(0.4); transition: 0.3s; }
        .footer-noir a:hover .footer-logo-lux { opacity: 1; filter: none; }

        /* Animations */
        @keyframes noirFadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .noir-anim { animation: noirFadeUp 0.8s cubic-bezier(0.19, 1, 0.22, 1) forwards; opacity: 0; }
    </style>
</head>
<body>

<!-- MOCKUP START -->
<div class="iphone-frame">
    <div class="iphone-notch"></div>
    
    <main class="main-content">
        
        <!-- 1. HEADER SECTION -->
        <div class="header-v4">
            <img src="/uploads/<?= $user['cover_pic'] ?: 'defaultsampul.png' ?>" class="cover-img">
            <div class="profile-overlap">
                <img src="/uploads/<?= $user['profile_pic'] ?: 'defaultprofile.png' ?>" class="avatar noir-anim">
                <h1 class="name noir-anim" style="animation-delay: 0.1s"><?= htmlspecialchars($user['fullname']) ?> <i class="fas fa-check-circle"></i></h1>
                <div class="role-text noir-anim" style="animation-delay: 0.2s"><?= htmlspecialchars($user['role_display'] ?: $user['role']) ?></div>
                
                <?php if($user['location']): ?>
                    <div class="location-tag noir-anim" style="animation-delay: 0.3s">
                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($user['location']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2. HERO COMPONENT (DRIVE) -->
        <?php if(!empty($user['drive_url'])): ?>
        <div class="noir-anim" style="animation-delay: 0.4s;">
            <a href="<?= $user['drive_url'] ?>" target="_blank" class="drive-black-pill">
                <div style="display:flex; align-items:center; gap:20px;">
                    <div class="drive-icon-lux"><i class="fab fa-google-drive"></i></div>
                    <div>
                        <div style="font-weight:900; font-size:15px; color:#fff;"><?= htmlspecialchars($user['drive_title'] ?: 'Cloud Access') ?></div>
                        <div style="font-size:10px; opacity:0.5; font-weight:700; letter-spacing:1.5px; margin-top:4px;">VISIT MY LINK</div>
                    </div>
                </div>
                <i class="fas fa-arrow-right-long" style="opacity:0.3; font-size:16px; margin-right:10px;"></i>
            </a>
        </div>
        <?php endif; ?>

        <!-- 3. EXPERIENCE LANDSCAPE SLIDER (5 SLOTS) -->
        <?php 
        $has_exp = false;
        for($e=1;$e<=5;$e++) { if(!empty($user['exp'.$e.'_title'])) { $has_exp = true; break; } }
        if($has_exp): ?>
        <div class="noir-anim" style="animation-delay: 0.5s;">
            <div class="label-premium">Experience Showcase</div>
            <div class="slider-wrapper">
                <?php for($e=1; $e<=5; $e++): if($user['exp'.$e.'_title']): ?>
                    <div class="exp-noir-card">
                        <img src="/uploads/<?= $user['exp'.$e.'_img'] ?>" class="exp-noir-img" onerror="this.src='https://placehold.co/600x400/020b09/111?text=Gallery'">
                        <div class="exp-noir-body">
                            <h3><?= htmlspecialchars($user['exp'.$e.'_title']) ?></h3>
                            <p><?= htmlspecialchars($user['exp'.$e.'_desc']) ?></p>
                        </div>
                    </div>
                <?php endif; endfor; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 4. TIKTOK PORTRAIT SLIDER (10 SLOTS) -->
        <?php 
        $has_tt = false;
        for($t=1;$t<=10;$t++) { if(!empty($user['tiktok_vid'.$t])) { $has_tt = true; break; } }
        if($has_tt): ?>
        <div class="noir-anim" style="animation-delay: 0.6s;">
            <div class="label-premium">Latest Highlights</div>
            <div class="slider-wrapper">
                <?php for($t=1; $t<=10; $t++): if($user['tiktok_vid'.$t]): ?>
                    <a href="<?= $user['tiktok_vid'.$t] ?>" target="_blank" class="video-noir-card tt-noir-fetch">
                        <img src="" class="tt-fetch-img">
                        <i class="fab fa-tiktok"></i>
                        <div class="noir-vid-label">Watch Now</div>
                    </a>
                <?php endif; endfor; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 5. SOCIAL CONNECT TILES -->
        <div class="label-premium noir-anim" style="animation-delay: 0.7s;">Digital Presence</div>
        <div class="social-noir-row noir-anim" style="animation-delay: 0.7s;">
            <?php if($user['ig_user']): ?><a href="https://instagram.com/<?= $user['ig_user'] ?>" target="_blank" class="social-noir-tile"><i class="fab fa-instagram"></i></a><?php endif; ?>
            <?php if($user['tt_user']): ?><a href="https://tiktok.com/@<?= $user['tt_user'] ?>" target="_blank" class="social-noir-tile"><i class="fab fa-tiktok"></i></a><?php endif; ?>
            <?php if($user['wa_number']): ?><a href="https://wa.me/<?= $user['wa_number'] ?>" target="_blank" class="social-noir-tile"><i class="fab fa-whatsapp"></i></a><?php endif; ?>
        </div>

        <!-- 6. BLACK DOMINANT STACKED BUTTONS -->
        <div class="btn-noir-stack">
            <?php for($i=1; $i<=10; $i++): if($user['btn'.$i.'_url']): ?>
                <a href="<?= $user['btn'.$i.'_url'] ?>" target="_blank" class="btn-noir-item noir-anim" style="animation-delay: <?= 0.8 + ($i * 0.08) ?>s;">
                    <?php if($user['btn'.$i.'_img']): ?>
                        <img src="/uploads/<?= $user['btn'.$i.'_img'] ?>" class="btn-noir-media">
                    <?php else: ?>
                        <div class="btn-noir-media" style="display:flex; align-items:center; justify-content:center; color:#555;"><i class="fas fa-link"></i></div>
                    <?php endif; ?>
                    <div class="btn-noir-info">
                        <h4><?= htmlspecialchars($user['btn'.$i.'_text']) ?></h4>
                        <p><?= htmlspecialchars($user['btn'.$i.'_desc']) ?></p>
                    </div>
                    <i class="fas fa-chevron-right btn-noir-arrow"></i>
                </a>
            <?php endif; endfor; ?>
        </div>

        <!-- 7. ELITE FOOTER -->
        <footer class="footer-noir noir-anim" style="animation-delay: 1.5s;">
            <a href="https://bio.hvmdigital.id" target="_blank">
                Powered by <img src="/assets/images/logobio.png" class="footer-logo-lux">
            </a>
        </footer>

    </main>
</div>

<!-- --- ANALYTICS & SMART FETCH SCRIPTS --- -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // --- A. ANALYTICS INIT ---
        if(typeof track === "function") track('page_view');

        // --- B. TIKTOK SMART FETCH (REALTIME) ---
        document.querySelectorAll('.tt-noir-fetch').forEach(card => {
            const videoUrl = card.getAttribute('href');
            const imgTag = card.querySelector('.tt-fetch-img');
            
            if (videoUrl && videoUrl.includes('tiktok.com')) {
                fetch(`https://www.tiktok.com/oembed?url=${encodeURIComponent(videoUrl)}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.thumbnail_url) {
                            imgTag.src = data.thumbnail_url;
                            imgTag.style.opacity = '1';
                        }
                    })
                    .catch(err => {
                        // Fallback jika API limit
                        imgTag.src = "https://placehold.co/400x700/020b09/ffffff?text=Video+Ready";
                        imgTag.style.opacity = '1';
                    });
            }
        });

        // --- C. DYNAMIC CLICK TRACKING ---
        document.querySelectorAll('a').forEach(l => {
            l.addEventListener('click', () => {
                let t = 'other_click', h = l.getAttribute('href');
                if(!h) return;
                
                if(h.includes('wa.me')) t='wa_click';
                else if(h.includes('instagram.com')) t='ig_click';
                else if(h.includes('tiktok.com')) t='tt_click';
                else if(h.includes('drive.google.com')) t='drive_click';
                else if(l.classList.contains('btn-noir-item')) t='btn_click';
                
                track(t);
            });
        });
    });

    // Tracking Bridge
    function track(type) {
        fetch('/track.php', {
            method: 'POST',
            body: JSON.stringify({ username: '<?= $user['username'] ?>', type: type }),
            headers: {'Content-Type': 'application/json'}
        });
    }
</script>

<?php 
// Include Popup Component if exists
$popup_path = __DIR__ . '/../../templates/popup.php';
if(file_exists($popup_path)) include $popup_path; 
?>

</body>
</html>