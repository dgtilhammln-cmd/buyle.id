<!DOCTYPE html>
<html lang="id" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    
    <!-- 1. DYNAMIC SEO & AI META TAGS (UPGRADED) -->
    <title><?= htmlspecialchars($user['fullname']) ?> | Official Profile & Portfolio</title>
    <meta name="description" content="Kunjungi profil resmi <?= htmlspecialchars($user['fullname']) ?>. <?= htmlspecialchars($user['role_display'] ?: 'Professional Creator') ?> berlokasi di <?= htmlspecialchars($user['location'] ?: 'Indonesia') ?>. Hubungi dan temukan portofolio serta konten eksklusif di sini.">
    <meta name="keywords" content="<?= htmlspecialchars($user['fullname']) ?>, <?= htmlspecialchars($user['nickname']) ?>, Bio Link, Azure Sky Theme, HVM Digital, Portfolio, Professional Link">
    <meta name="author" content="<?= htmlspecialchars($user['fullname']) ?>">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="https://bio.hvmdigital.id/<?= $user['username'] ?>">

    <!-- 2. OPEN GRAPH (Social Media Optimization) -->
    <meta property="og:type" content="profile">
    <meta property="og:title" content="<?= htmlspecialchars($user['fullname']) ?> - Link Resmi">
    <meta property="og:description" content="Kunjungi profil digital <?= htmlspecialchars($user['fullname']) ?>. Semua tautan penting dalam satu halaman bersih, modern, dan eksklusif.">
    <meta property="og:image" content="https://bio.hvmdigital.id/uploads/<?= $user['profile_pic'] ?: 'default.png' ?>">
    <meta property="og:url" content="https://bio.hvmdigital.id/<?= $user['username'] ?>">
    <meta property="og:site_name" content="HVM Studio">

    <!-- 3. TWITTER CARD (X Optimization) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($user['fullname']) ?>">
    <meta name="twitter:description" content="Digital Identity & Portfolio Stack of <?= htmlspecialchars($user['fullname']) ?>">
    <meta name="twitter:image" content="https://bio.hvmdigital.id/uploads/<?= $user['cover_pic'] ?: 'default_cover.jpg' ?>">

    <!-- 4. JSON-LD SCHEMA MARKUP (AI & Google Brain Integration) -->
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Person",
      "name": "<?= htmlspecialchars($user['fullname']) ?>",
      "jobTitle": "<?= htmlspecialchars($user['role_display'] ?: 'Professional') ?>",
      "image": "https://bio.hvmdigital.id/uploads/<?= $user['profile_pic'] ?: 'default.png' ?>",
      "url": "https://bio.hvmdigital.id/<?= $user['username'] ?>",
      "sameAs": [
        "https://instagram.com/<?= htmlspecialchars($user['ig_user']) ?>",
        "https://tiktok.com/@<?= htmlspecialchars($user['tt_user']) ?>"
      ],
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "<?= htmlspecialchars($user['location'] ?: 'Indonesia') ?>"
      },
      "description": "<?= htmlspecialchars($user['fullname']) ?> membagikan karya dan kontaknya melalui platform HVM Digital ID."
    }
    </script>

    <!-- Favicon & External Fonts -->
    <link rel="icon" type="image/png" href="/assets/images/logobio.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* --- PREMIUM BLUE SYSTEM v2.0 --- */
        :root {
            --accent: #3b82f6;          /* Azure Blue */
            --accent-soft: rgba(59, 130, 246, 0.1);
            --bg-body: #f8fafc;
            --glass: #ffffff;
            --glass-border: rgba(0, 0, 0, 0.05);
            --text-main: #0f172a;
            --text-sub: #64748b;
            --side-margin: 24px;
            --shadow-lux: 0 15px 40px -10px rgba(15, 23, 42, 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        body { 
            background-color: var(--bg-body); 
            color: var(--text-main); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            margin:0; padding:0; overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .container { 
            max-width: 500px; 
            margin: 0 auto; 
            padding-bottom: 100px; 
            position: relative; 
            background: #ffffff;
            box-shadow: 0 0 60px rgba(0,0,0,0.02);
            min-height: 100vh;
        }

        /* --- STAGGERED REVEAL ANIMATION --- */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal.visible { opacity: 1 !important; transform: translateY(0) !important; }

        /* Typography & Headlines */
        .section-headline { 
            color: var(--text-main); font-weight: 800; font-size: 13px; 
            margin: 40px var(--side-margin) 15px; text-transform: uppercase; 
            letter-spacing: 1.5px; opacity: 0.9; display: block;
        }

        /* --- HEADER & PROFILE --- */
        .header-wrapper { position: relative; margin-bottom: 30px; text-align: center; }
        .cover-area { 
            width: 100%; height: 200px; 
            background-size: cover; background-position: center; 
            border-radius: 0 0 50px 50px;
            box-shadow: inset 0 -30px 60px rgba(0,0,0,0.1);
        }
        .profile-container { margin-top: -75px; padding: 0 var(--side-margin); position: relative; z-index: 10; }
        .profile-box { 
            width: 130px; height: 130px; margin: 0 auto 20px; 
            border-radius: 45px; padding: 6px; background: #fff; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1); 
        }
        .profile-box img { width: 100%; height: 100%; border-radius: 38px; object-fit: cover; }
        
        h1 { font-size: 26px; font-weight: 800; color: var(--text-main); margin-bottom: 6px; letter-spacing: -0.5px; }
        .nick { font-size: 15px; color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 20px; }
        
        .badges { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; }
        .badge { font-size: 11px; font-weight: 600; padding: 8px 18px; border-radius: 100px; background: var(--soft-bg); color: var(--text-sub); display: flex; align-items: center; gap: 8px; border: 1px solid var(--border-color); }
        .badge i { color: var(--accent); font-size: 12px; }

        /* --- DRIVE PREMIUM CARD --- */
        .drive-card {
            margin: 0 var(--side-margin) 35px; background: var(--accent);
            border-radius: 28px; padding: 25px; display: flex; align-items: center; gap: 20px;
            text-decoration: none; color: #fff; box-shadow: 0 15px 35px rgba(59, 130, 246, 0.3);
            transition: var(--trans-lux);
        }
        .drive-card:hover { transform: scale(1.03); filter: brightness(1.1); }
        .drive-icon { font-size: 32px; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.2)); }
        .drive-title { font-size: 17px; font-weight: 800; display: block; margin-bottom: 2px; }
        .drive-sub { font-size: 12px; opacity: 0.8; font-weight: 500; }

        /* --- EXPERIENCE LANDSCAPE SLIDER --- */
/* --- TIKTOK SLIDER REFINED (NO LEAK) --- */
.slider-wrapper {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    /* Padding kiri disamakan dengan margin teks agar sejajar lurus */
    /* Padding kanan dilebihkan agar card terakhir bisa di-scroll sampai ujung */
    padding: 0 0 20px 0; 
    margin: 0 var(--side-margin); 
    scrollbar-width: none;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
}
.slider-wrapper::-webkit-scrollbar { display: none; }

/* Menghapus baris ::after yang lama agar tidak membuat ruang kosong aneh */
.slider-wrapper::after { content: none; }

        .exp-card {
            flex: 0 0 290px; background: var(--white); border: 1px solid var(--glass-border);
            border-radius: 28px; overflow: hidden; scroll-snap-align: start;
            box-shadow: var(--shadow-lux); transition: 0.3s ease;
        }
        .exp-img { width: 100%; height: 155px; overflow: hidden; position: relative; }
        .exp-img img { width: 100%; height: 100%; object-fit: cover; }
        .exp-content { padding: 20px; }
        .exp-content h3 { font-size: 15px; font-weight: 800; margin: 0 0 8px; color: var(--text-main); letter-spacing: -0.2px; }
        .exp-content p { font-size: 12px; color: var(--text-sub); line-height: 1.6; margin: 0; }

        /* --- TIKTOK PORTRAIT SLIDER (10 SLOTS) --- */
.video-card {
    /* Gunakan 150px agar sejajar dan memberikan ruang scroll yang enak */
    flex: 0 0 150px; 
    height: 260px;
    scroll-snap-align: start;
    border-radius: 22px !important;
    position: relative;
    overflow: hidden;
    border: 1px solid var(--glass-border);
    background: #111;
    text-decoration: none;
    transition: 0.3s ease;
}
        .video-card:hover { transform: translateY(-8px); border-color: var(--accent); }
        .tt-thumb { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; z-index: 1; opacity: 0; transition: 0.6s ease; }
        .video-card::after {
            content: ''; position: absolute; inset: 0; z-index: 2;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.9) 0%, transparent 60%);
        }
        .video-card i { position: absolute; top: 18px; left: 18px; z-index: 3; font-size: 18px; color: #fff; }
        .watch-label { position: absolute; bottom: 18px; left: 18px; z-index: 3; font-size: 9px; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: 1px; }

        /* --- SOCIAL TILES --- */
        .social-row { 
    display: grid; 
    grid-template-columns: repeat(3, 1fr); 
    gap: 12px; 
    padding: 0 var(--side-margin); 
    margin-top: 5px; 
}
        .social-tile { 
            height: 75px; background: #fff; border: 1px solid var(--glass-border); border-radius: 22px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 24px; color: var(--text-main); text-decoration: none; transition: 0.3s;
            box-shadow: var(--shadow-lux);
        }
        .social-tile:hover { background: var(--accent); color: #fff; transform: translateY(-5px); border-color: var(--accent); }

        /* --- LINK BUTTONS STACK --- */
        .link-stack { padding: 0 var(--side-margin); display: flex; flex-direction: column; gap: 14px; margin-top: 25px; }
        .glass-btn { 
            background: #fff; border: 1px solid var(--glass-border); border-radius: 24px; 
            padding: 16px; display: flex; align-items: center; gap: 18px;
            text-decoration: none; color: var(--text-main); transition: 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: var(--shadow-lux);
        }
        .glass-btn:hover { border-color: var(--accent); transform: translateX(8px); }
        .btn-img-box { width: 52px; height: 52px; border-radius: 15px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; padding: 2px; }
        .btn-img-box img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
        .btn-icon-fa { width: 52px; height: 52px; border-radius: 15px; background: #f1f5f9; color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
        .btn-info { flex: 1; }
        .btn-title { font-size: 15px; font-weight: 800; margin-bottom: 2px; color: var(--text-main); }
        .btn-desc { font-size: 11px; color: var(--text-sub); line-height: 1.4; font-weight: 500; }
        .btn-arrow { color: #cbd5e1; font-size: 14px; margin-right: 5px; }

        /* --- FOOTER --- */
        .footer-branding { margin-top: 80px; text-align: center; padding-bottom: 60px; }
        .footer-credit { font-size: 11px; color: var(--text-sub); text-decoration: none; font-weight: 700; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 10px; text-transform: uppercase; }
        .footer-logo { height: 16px; filter: grayscale(1); opacity: 0.5; }

        /* Utility */
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }
    </style>
</head>

<body>

<div class="container">
    <!-- AI Semantic Hidden Content -->
    <h1 class="sr-only"><?= htmlspecialchars($user['fullname']) ?> - Bio Link Portofolio Stack</h1>

    <!-- HEADER SECTION -->
    <header class="header-wrapper">
        <div class="cover-area" style="background-image: url('/uploads/<?= $user['cover_pic'] ?: 'default_cover.jpg' ?>');" role="img" aria-label="Latar Belakang Profil"></div>
        <div class="profile-container">
            <div class="profile-box reveal">
                <img src="/uploads/<?= $user['profile_pic'] ?: 'default.png' ?>" alt="Foto Profil Utama <?= htmlspecialchars($user['fullname']) ?>">
            </div>
            <h2 class="reveal"><?= htmlspecialchars($user['fullname']) ?></h2>
            <span class="nick reveal"><?= htmlspecialchars($user['nickname'] ?: $user['username']) ?></span>
            <div class="badges reveal">
                <div class="badge"><i class="fas fa-briefcase"></i> <?= htmlspecialchars($user['role_display'] ?: $user['role']) ?></div>
                <?php if(!empty($user['location'])): ?>
                    <div class="badge"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($user['location']) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- DRIVE PREMIUM ACTION -->
    <?php if(!empty($user['drive_url'])): ?>
    <section class="reveal">
        <a href="<?= $user['drive_url'] ?>" target="_blank" class="drive-card" title="Download atau Lihat File Portofolio">
            <i class="fab fa-google-drive drive-icon"></i>
            <div class="drive-info">
                <span class="drive-title"><?= htmlspecialchars($user['drive_title'] ?: 'Cloud Drive Portofolio') ?></span>
                <span class="drive-sub">Tap to access official assets</span>
            </div>
            <i class="fas fa-chevron-right" style="opacity:0.3; margin-left: auto;"></i>
        </a>
    </section>
    <?php endif; ?>

    <!-- EXPERIENCE JOURNEY (LANDSCAPE) -->
    <?php 
    $has_exp = false;
    for($e=1;$e<=5;$e++) { if(!empty($user['exp'.$e.'_title'])) { $has_exp = true; break; } }
    if($has_exp): ?>
    <section class="reveal">
        <span class="section-headline">My Experience</span>
        <div class="slider-wrapper">
            <?php for($e=1; $e<=5; $e++): if(!empty($user['exp'.$e.'_title'])): ?>
                <div class="exp-card">
                    <div class="exp-img">
                        <img src="/uploads/<?= $user['exp'.$e.'_img'] ?>" alt="Pengalaman <?= $e ?>: <?= htmlspecialchars($user['exp'.$e.'_title']) ?>" loading="lazy" onerror="this.src='https://placehold.co/600x400/f1f5f9/94a3b8?text=Project'">
                    </div>
                    <div class="exp-content">
                        <h3><?= htmlspecialchars($user['exp'.$e.'_title']) ?></h3>
                        <p><?= htmlspecialchars($user['exp'.$e.'_desc']) ?></p>
                    </div>
                </div>
            <?php endif; endfor; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- TIKTOK HIGHLIGHTS (UP TO 10) -->
    <?php 
    $has_tt = false;
    for($t=1;$t<=10;$t++) { if(!empty($user['tiktok_vid'.$t])) { $has_tt = true; break; } }
    if($has_tt): ?>
    <section class="reveal">
        <span class="section-headline">Latest Highlights</span>
        <div class="slider-wrapper">
            <?php for($i=1; $i<=10; $i++): if(!empty($user['tiktok_vid'.$i])): ?>
                <a href="<?= $user['tiktok_vid'.$i] ?>" target="_blank" class="video-card tt-fetch" title="Tonton Video TikTok Terbaru">
                    <img src="" class="tt-thumb" alt="Cuplikan Video TikTok" loading="lazy">
                    <i class="fab fa-tiktok"></i>
                    <div class="watch-label">Watch Now</div>
                </a>
            <?php endif; endfor; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- SOCIAL TILES -->
    <section class="reveal">
        <span class="section-headline" style="margin-top:10px;">Digital Presence</span>
        <div class="social-row">
            <?php if($user['ig_user']): ?><a href="https://instagram.com/<?= $user['ig_user'] ?>" target="_blank" class="social-tile" title="Follow Instagram"><i class="fab fa-instagram"></i></a><?php endif; ?>
            <?php if($user['tt_user']): ?><a href="https://tiktok.com/@<?= $user['tt_user'] ?>" target="_blank" class="social-tile" title="Follow TikTok"><i class="fab fa-tiktok"></i></a><?php endif; ?>
            <?php if($user['wa_number']): ?><a href="https://wa.me/<?= $user['wa_number'] ?>" target="_blank" class="social-tile" title="Chat WhatsApp"><i class="fab fa-whatsapp"></i></a><?php endif; ?>
        </div>
    </section>

    <!-- CUSTOM BUTTONS LOOP (STACK) -->
    <main class="link-stack">
        <span class="section-headline" style="margin-left:0; margin-top:20px;">Contact & Links</span>
        <?php for($i=1; $i<=10; $i++): if(!empty($user['btn'.$i.'_url']) && !empty($user['btn'.$i.'_text'])): ?>
            <a href="<?= $user['btn'.$i.'_url'] ?>" target="_blank" class="glass-btn reveal" title="<?= htmlspecialchars($user['btn'.$i.'_text']) ?>">
                <div class="btn-img-box">
                    <?php if(!empty($user['btn'.$i.'_img'])): ?>
                        <img src="/uploads/<?= $user['btn'.$i.'_img'] ?>" alt="Icon untuk <?= htmlspecialchars($user['btn'.$i.'_text']) ?>" loading="lazy">
                    <?php else: ?>
                        <i class="fas fa-link"></i>
                    <?php endif; ?>
                </div>
                <div class="btn-info">
                    <div class="btn-title"><?= htmlspecialchars($user['btn'.$i.'_text']) ?></div>
                    <?php if(!empty($user['btn'.$i.'_desc'])): ?>
                        <div class="btn-desc"><?= htmlspecialchars($user['btn'.$i.'_desc']) ?></div>
                    <?php endif; ?>
                </div>
                <i class="fas fa-chevron-right btn-arrow"></i>
            </a>
        <?php endif; endfor; ?>
    </main>

    <!-- FOOTER BRANDING -->
    <footer class="footer-branding reveal">
        <a href="https://bio.hvmdigital.id" target="_blank" class="footer-credit">
            Powered <img src="/assets/images/logobio.png" alt="HVM Studio Logo" class="footer-logo">
        </a>
    </footer>

</div>

<!-- SCRIPTS: ANALYTICS & SMART FETCH -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // --- 1. SENSOR REVEAL (LUXURY ANIMATION) ---
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.05 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // --- 2. TIKTOK SMART FETCH ENGINE ---
        document.querySelectorAll('.tt-fetch').forEach(card => {
            const videoUrl = card.getAttribute('href');
            const imgTag = card.querySelector('.tt-thumb');
            if (videoUrl && videoUrl.includes('tiktok.com')) {
                fetch(`https://www.tiktok.com/oembed?url=${encodeURIComponent(videoUrl)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.thumbnail_url) {
                            imgTag.src = data.thumbnail_url;
                            imgTag.style.opacity = '1';
                        }
                    })
                    .catch(e => {
                        imgTag.src = "https://placehold.co/400x700/f1f5f9/94a3b8?text=Watch+on+TikTok";
                        imgTag.style.opacity = '1';
                    });
            }
        });

        // --- 3. TRACKING SYSTEM ---
        if(typeof track === 'function') track('page_view');
        document.querySelectorAll('a').forEach(l => {
            l.addEventListener('click', () => {
                let t = 'other_click', h = l.getAttribute('href');
                if(!h) return;
                if(h.includes('wa.me')) t = 'wa_click';
                else if(h.includes('instagram.com')) t = 'ig_click';
                else if(h.includes('tiktok.com')) t = 'tt_click';
                else if(h.includes('drive.google.com')) t = 'drive_click';
                else if(l.classList.contains('glass-btn')) t = 'btn_click';
                if(typeof track === 'function') track(t);
            });
        });
    });

    function track(type) {
        fetch('/track.php', {
            method: 'POST',
            body: JSON.stringify({ username: '<?= $user['username'] ?>', type: type }),
            headers: { 'Content-Type': 'application/json' }
        });
    }
</script>

<?php 
$popup_path = __DIR__ . '/../../templates/popup.php';
if(file_exists($popup_path)) include $popup_path; 
?>

</body>
</html>