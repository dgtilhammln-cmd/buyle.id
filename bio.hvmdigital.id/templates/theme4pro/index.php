<!DOCTYPE html>
<html lang="id" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    
    <!-- 1. ELITE SEO & AI ENGINE -->
    <title><?= htmlspecialchars($user['fullname']) ?> | Velvet Pro</title>
    <meta name="description" content="Official portfolio of <?= htmlspecialchars($user['fullname']) ?>. <?= htmlspecialchars($user['role_display']) ?> in <?= htmlspecialchars($user['location']) ?>. Explore exclusive links and media.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://bio.hvmdigital.id/<?= $user['username'] ?>">

    <!-- JSON-LD Schema (AI Friendly) -->
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Person",
      "name": "<?= htmlspecialchars($user['fullname']) ?>",
      "jobTitle": "<?= htmlspecialchars($user['role_display']) ?>",
      "image": "https://bio.hvmdigital.id/uploads/<?= $user['profile_pic'] ?: 'default.png' ?>",
      "address": { "@type": "PostalAddress", "addressLocality": "<?= htmlspecialchars($user['location']) ?>" }
    }
    </script>

    <!-- Assets -->
    <link rel="icon" type="image/png" href="/assets/images/logobio.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;700;900&display=swap" rel="stylesheet">
    
    <style>
        /* --- CYBER VELVET v6.0 DESIGN SYSTEM --- */
        :root {
            --primary: #8b5cf6;           /* Velvet Purple */
            --secondary: #3b82f6;         /* Azure Blue */
            --dark: #020617;              /* Deep Obsidian */
            --glass: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
            --side-margin: 20px;
            --lux-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.2);
            --transition: all 0.6s cubic-bezier(0.19, 1, 0.22, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        /* DESKTOP BACKGROUND OUTSIDE MOCKUP IS WHITE */
        body { 
            background-color: #f8fafc; 
            color: var(--dark); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            overflow-x: hidden;
        }

        /* --- DESKTOP VIEWPORT (IPHONE MOCKUP) --- */
        @media (min-width: 992px) {
            body { 
                display: flex; align-items: center; justify-content: center; 
                min-height: 100vh; padding: 30px 0; 
                background: radial-gradient(circle at 10% 10%, #f1f5f9 0%, #ffffff 100%);
            }
            .iphone-frame {
                width: 400px; height: 830px; background: #000; border-radius: 65px;
                border: 14px solid #1a1a1a; position: relative; overflow: hidden;
                box-shadow: 0 80px 150px -30px rgba(0,0,0,0.4);
            }
            .iphone-notch {
                position: absolute; top: 0; left: 50%; transform: translateX(-50%);
                width: 150px; height: 32px; background: #1a1a1a; border-bottom-left-radius: 24px;
                border-bottom-right-radius: 24px; z-index: 100;
            }
            .main-content { height: 100%; overflow-y: auto; scrollbar-width: none; background: #fff; }
            .main-content::-webkit-scrollbar { display: none; }
        }

        /* --- MOBILE CONTENT WRAPPER --- */
        .main-content { width: 100%; position: relative; padding-bottom: 80px; background: #fff; }

        /* 1. COVER & PROFILE UNIT (REDESIGNED) */
        .hero-banner { position: relative; height: 260px; overflow: hidden; }
        .cover-asset { width: 100%; height: 100%; object-fit: cover; }
        .cover-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 20%, #fff 100%); }
        
        .profile-unit { margin-top: -110px; text-align: center; padding: 0 var(--side-margin); position: relative; z-index: 10; }
        .avatar-frame { 
            width: 130px; height: 130px; margin: 0 auto 15px; border-radius: 50px; padding: 5px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 15px 35px rgba(139, 92, 246, 0.3);
        }
        .avatar-frame img { width: 100%; height: 100%; border-radius: 45px; object-fit: cover; border: 4px solid #fff; }
        
        .name-label { font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 900; color: var(--dark); letter-spacing: -1px; }
        .role-pill { 
            display: inline-block; padding: 6px 16px; border-radius: 100px;
            background: #f1f5f9; color: var(--primary); font-size: 11px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 2px; margin-top: 5px;
        }

        /* 2. SOCIAL ORBIT (FLOATING STYLE) */
        .social-orbit { display: flex; justify-content: center; gap: 12px; margin: 25px 0; }
        .orbit-btn { 
            width: 55px; height: 55px; background: #fff; border: 1px solid #f1f5f9; border-radius: 18px;
            display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--dark);
            box-shadow: 0 10px 20px rgba(0,0,0,0.04); transition: var(--transition);
        }
        .orbit-btn:hover { background: var(--dark); color: #fff; transform: translateY(-5px); }

        /* 3. EXPERIENCE CARDS (ASYMMETRIC) */
        .section-header { font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 3px; margin: 40px var(--side-margin) 15px; }
        
        .scroller-h { display: flex; gap: 15px; overflow-x: auto; padding: 0 var(--side-margin) 10px; scrollbar-width: none; }
        .scroller-h::-webkit-scrollbar { display: none; }

        .exp-cyber-card { 
            flex: 0 0 260px; background: #fff; border: 1px solid #f1f5f9; border-radius: 30px; 
            overflow: hidden; box-shadow: var(--lux-shadow); transition: var(--transition);
        }
        .exp-img-h { width: 100%; height: 140px; object-fit: cover; }
        .exp-body-h { padding: 20px; }
        .exp-body-h h3 { font-size: 15px; font-weight: 800; color: var(--dark); margin-bottom: 5px; }
        .exp-body-h p { font-size: 11px; color: #64748b; line-height: 1.6; }

        /* 4. BUTTONS GRID (NEW BENTO LAYOUT) */
        .bento-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; /* 2 KOLOM DIFERENSIAL */
            gap: 12px; 
            padding: 0 var(--side-margin); 
        }
        .bento-item { 
            background: #fff; border: 1px solid #f1f5f9; border-radius: 25px; padding: 20px;
            text-decoration: none; color: var(--dark); transition: var(--transition);
            box-shadow: 0 10px 20px rgba(0,0,0,0.02);
            display: flex; flex-direction: column; align-items: flex-start; gap: 10px;
        }
        /* Item pertama dibuat lebar penuh (High Priority) */
        .bento-item:nth-child(1) { grid-column: span 2; flex-direction: row; align-items: center; }
        
        .bento-item:hover { border-color: var(--primary); transform: translateY(-5px); box-shadow: 0 15px 30px rgba(139, 92, 246, 0.1); }
        .bento-icon { width: 45px; height: 45px; border-radius: 12px; background: #f8fafc; object-fit: cover; }
        .bento-text h4 { font-size: 13px; font-weight: 800; }
        .bento-text p { font-size: 9px; color: #94a3b8; font-weight: 600; margin-top: 2px; }

        /* 5. TIKTOK VELVET (PORTRAIT) */
        .tt-velvet-card {
            flex: 0 0 160px; height: 280px; border-radius: 28px; background: #000;
            position: relative; overflow: hidden; text-decoration: none; border: 1px solid #f1f5f9;
        }
        .tt-velvet-img { width: 100%; height: 100%; object-fit: cover; opacity: 0.8; }
        .tt-velvet-overlay { position: absolute; inset: 0; background: linear-gradient(to top, var(--primary) 0%, transparent 60%); opacity: 0.6; }
        .tt-velvet-icon { position: absolute; top: 15px; left: 15px; color: #fff; font-size: 18px; }
        .tt-velvet-label { position: absolute; bottom: 15px; left: 15px; color: #fff; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

        /* 6. DRIVE HERO PILL */
        .drive-lux-pill {
            background: var(--dark); border-radius: 30px; padding: 20px;
            margin: 0 var(--side-margin) 30px; display: flex; align-items: center; gap: 15px;
            text-decoration: none; color: #fff; transition: var(--transition);
        }
        .drive-lux-pill:hover { background: var(--primary); transform: scale(1.02); }
        .drive-lux-icon { width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--accent); }

        /* Footer */
        .footer-velvet { text-align: center; margin-top: 80px; padding-bottom: 50px; }
        .footer-velvet a { text-decoration: none; color: #cbd5e1; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 3px; display: inline-flex; align-items: center; gap: 10px; }
        .f-logo-v { height: 16px; filter: grayscale(1); opacity: 0.3; }

        /* ANIMATIONS */
        .reveal { opacity: 0; transform: translateY(30px); transition: var(--transition); }
        .reveal.active { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

<div class="iphone-frame">
    <div class="iphone-notch"></div>
    
    <main class="main-content">
        
        <!-- --- 1. HEADER & COVER --- -->
        <div class="hero-banner">
            <img src="/uploads/<?= $user['cover_pic'] ?: 'defaultsampul.png' ?>" class="cover-asset" alt="Cover Branding">
            <div class="cover-overlay"></div>
        </div>

        <div class="profile-unit">
            <div class="avatar-frame reveal">
                <img src="/uploads/<?= $user['profile_pic'] ?: 'defaultprofile.png' ?>" alt="Avatar">
            </div>
            <h1 class="name-label reveal"><?= htmlspecialchars($user['fullname']) ?></h1>
            <div class="role-pill reveal"><?= htmlspecialchars($user['role_display'] ?: $user['role']) ?></div>
            
            <?php if($user['location']): ?>
                <div style="margin-top:10px; font-size:11px; color:#94a3b8; font-weight:700;" class="reveal">
                    <i class="fas fa-map-marker-alt" style="color:var(--secondary);"></i> <?= htmlspecialchars($user['location']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- --- 2. SOCIAL FLOATING --- -->
        <div class="social-orbit reveal">
            <?php if($user['ig_user']): ?><a href="https://instagram.com/<?= $user['ig_user'] ?>" target="_blank" class="orbit-btn"><i class="fab fa-instagram"></i></a><?php endif; ?>
            <?php if($user['tt_user']): ?><a href="https://tiktok.com/@<?= $user['tt_user'] ?>" target="_blank" class="orbit-btn"><i class="fab fa-tiktok"></i></a><?php endif; ?>
            <?php if($user['wa_number']): ?><a href="https://wa.me/<?= $user['wa_number'] ?>" target="_blank" class="orbit-btn"><i class="fab fa-whatsapp"></i></a><?php endif; ?>
        </div>

        <!-- --- 3. PREMIUM ACTION (DRIVE) --- -->
        <?php if($user['drive_url']): ?>
        <a href="<?= $user['drive_url'] ?>" target="_blank" class="drive-lux-pill reveal">
            <div class="drive-lux-icon"><i class="fab fa-google-drive"></i></div>
            <div style="flex:1">
                <div style="font-weight:800; font-size:15px;"><?= htmlspecialchars($user['drive_title'] ?: 'Cloud Repository') ?></div>
                <div style="font-size:10px; opacity:0.6; font-weight:600;">TAP TO BROWSE ASSETS</div>
            </div>
            <i class="fas fa-chevron-right" style="opacity:0.3"></i>
        </a>
        <?php endif; ?>

        <!-- --- 4. EXPERIENCE MOSAIC (5 SLOT) --- -->
        <?php 
        $has_exp = false;
        for($e=1;$e<=5;$e++) { if(!empty($user['exp'.$e.'_title'])) { $has_exp = true; break; } }
        if($has_exp): ?>
        <div class="section-header reveal">Experience Journeys</div>
        <div class="scroller-h reveal">
            <?php for($e=1; $e<=5; $e++): if($user['exp'.$e.'_title']): ?>
                <div class="exp-cyber-card">
                    <img src="/uploads/<?= $user['exp'.$e.'_img'] ?>" class="exp-img-h" onerror="this.src='https://placehold.co/600x400/f1f5f9/94a3b8?text=Project'">
                    <div class="exp-body-h">
                        <h3><?= htmlspecialchars($user['exp'.$e.'_title']) ?></h3>
                        <p><?= htmlspecialchars($user['exp'.$e.'_desc']) ?></p>
                    </div>
                </div>
            <?php endif; endfor; ?>
        </div>
        <?php endif; ?>

        <!-- --- 5. BUTTON BENTO GRID (10 SLOT) --- -->
        <div class="section-header reveal"> </div>
        <div class="bento-grid reveal">
            <?php for($i=1; $i<=10; $i++): if($user['btn'.$i.'_url']): ?>
                <a href="<?= $user['btn'.$i.'_url'] ?>" target="_blank" class="bento-item">
                    <?php if($user['btn'.$i.'_img']): ?>
                        <img src="/uploads/<?= $user['btn'.$i.'_img'] ?>" class="bento-icon">
                    <?php else: ?>
                        <div class="bento-icon" style="display:flex; align-items:center; justify-content:center; color:#cbd5e1;"><i class="fas fa-link"></i></div>
                    <?php endif; ?>
                    <div class="bento-text">
                        <h4><?= htmlspecialchars($user['btn'.$i.'_text']) ?></h4>
                        <?php if($user['btn'.$i.'_desc']): ?>
                            <p><?= htmlspecialchars($user['btn'.$i.'_desc']) ?></p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endif; endfor; ?>
        </div>

        <!-- --- 6. TIKTOK PORTRAIT UNIT (10 SLOT) --- -->
        <?php 
        $has_tt = false;
        for($t=1;$t<=10;$t++) { if(!empty($user['tiktok_vid'.$t])) { $has_tt = true; break; } }
        if($has_tt): ?>
        <div class="section-header reveal">Visual Highlights</div>
        <div class="scroller-h reveal">
            <?php for($t=1; $t<=10; $t++): if($user['tiktok_vid'.$t]): ?>
                <a href="<?= $user['tiktok_vid'.$t] ?>" target="_blank" class="tt-velvet-card tt-fetch-v6">
                    <img src="" class="tt-velvet-img">
                    <div class="tt-velvet-overlay"></div>
                    <i class="fab fa-tiktok tt-velvet-icon"></i>
                    <div class="tt-velvet-label">Play Clip</div>
                </a>
            <?php endif; endfor; ?>
        </div>
        <?php endif; ?>

        <!-- --- 7. FOOTER --- -->
        <footer class="footer-velvet reveal">
            <a href="https://bio.hvmdigital.id" target="_blank">
                By <img src="/assets/images/logobio.png" class="f-logo-v" alt="HVM Studio">
            </a>
        </footer>

    </main>
</div>

<!-- --- ANALYTICS & SMART SCRIPT --- -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // --- A. REVEAL OBSERVER ---
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // --- B. TIKTOK SMART FETCH ---
        document.querySelectorAll('.tt-fetch-v6').forEach(card => {
            const url = card.getAttribute('href');
            const img = card.querySelector('.tt-velvet-img');
            if (url && url.includes('tiktok.com')) {
                fetch(`https://www.tiktok.com/oembed?url=${encodeURIComponent(url)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.thumbnail_url) {
                            img.src = data.thumbnail_url;
                            img.style.opacity = '1';
                        }
                    })
                    .catch(() => {
                        img.src = "https://placehold.co/400x700/020617/ffffff?text=Video";
                        img.style.opacity = '0.5';
                    });
            }
        });

        // --- C. ANALYTICS ---
        if(typeof track === "function") track('page_view');
    });

    function track(type) {
        fetch('/track.php', {
            method: 'POST',
            body: JSON.stringify({ username: '<?= $user['username'] ?>', type: type }),
            headers: {'Content-Type': 'application/json'}
        });
    }
</script>

<?php 
$popup_path = __DIR__ . '/../../templates/popup.php';
if(file_exists($popup_path)) include $popup_path; 
?>

</body>
</html>