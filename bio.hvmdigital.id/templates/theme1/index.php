<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <!-- 1. SEO & AI META TAGS (UPGRADED) -->
    <title><?= htmlspecialchars($user['fullname']) ?> | Official Profile & Portfolio</title>
    <meta name="description" content="Kunjungi profil resmi <?= htmlspecialchars($user['fullname']) ?>. <?= htmlspecialchars($user['role_display'] ?: 'Professional Content Creator') ?> yang berlokasi di <?= htmlspecialchars($user['location'] ?: 'Indonesia') ?>. Temukan portofolio, kontak, dan karya terbaru di sini.">
    <meta name="keywords" content="<?= htmlspecialchars($user['fullname']) ?>, <?= htmlspecialchars($user['nickname']) ?>, Bio Link, HVM Digital, Portfolio, Contact, Content Creator, Professional">
    <meta name="author" content="<?= htmlspecialchars($user['fullname']) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://bio.hvmdigital.id/<?= $user['username'] ?>">

    <!-- 2. OPEN GRAPH (Media Sosial Optimization) -->
    <meta property="og:type" content="profile">
    <meta property="og:title" content="<?= htmlspecialchars($user['fullname']) ?> - Official Link">
    <meta property="og:description" content="Lihat profil lengkap, pengalaman, dan hubungi <?= htmlspecialchars($user['fullname']) ?> secara langsung.">
    <meta property="og:image" content="https://bio.hvmdigital.id/assets/uploads/<?= $user['profile_pic'] ?: 'defaultprofile.png' ?>">
    <meta property="og:url" content="https://bio.hvmdigital.id/<?= $user['username'] ?>">
    <meta property="og:site_name" content="HVM Studio">

    <!-- 3. TWITTER CARD (X Optimization) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($user['fullname']) ?>">
    <meta name="twitter:description" content="Profesional Profile of <?= htmlspecialchars($user['fullname']) ?> - <?= htmlspecialchars($user['role_display']) ?>">
    <meta name="twitter:image" content="https://bio.hvmdigital.id/assets/uploads/<?= $user['cover_pic'] ?: 'defaultsampul.png' ?>">

    <!-- 4. SCHEMA MARKUP (JSON-LD untuk AI Friendly) -->
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Person",
      "name": "<?= htmlspecialchars($user['fullname']) ?>",
      "jobTitle": "<?= htmlspecialchars($user['role_display']) ?>",
      "image": "https://bio.hvmdigital.id/assets/uploads/<?= $user['profile_pic'] ?: 'defaultprofile.png' ?>",
      "url": "https://bio.hvmdigital.id/<?= $user['username'] ?>",
      "sameAs": [
        "https://instagram.com/<?= htmlspecialchars($user['ig_user']) ?>",
        "https://tiktok.com/@<?= htmlspecialchars($user['tt_user']) ?>"
      ],
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "<?= htmlspecialchars($user['location']) ?>",
        "addressCountry": "Indonesia"
      },
      "description": "<?= htmlspecialchars($user['fullname']) ?> adalah seorang <?= htmlspecialchars($user['role_display']) ?>. Lihat pengalaman dan portofolio lengkapnya di HVM Digital."
    }
    </script>

    <!-- Favicon & Styles -->
    <link rel="icon" type="image/png" href="/assets/images/logobio.png">
    <link rel="stylesheet" href="/templates/theme1/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- PREMIUM REFINED SYSTEM --- */
        :root {
            --accent: #a1ff5a;
            --glass: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.1);
            --side-margin: 24px; /* Margin konsisten kiri-kanan */
        }

        /* Reset & Container */
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; background-color: #000; color: #fff; font-family: 'Montserrat', sans-serif; }
        
        .container { 
            max-width: 500px; 
            margin: 0 auto; 
            padding: 0 0 50px 0; /* Padding bawah untuk space footer */
            overflow-x: hidden;
        }

        /* Typography */
        .section-headline { 
            color: #ffffff !important; /* Headline Putih sesuai request */
            font-weight: 800; 
            font-size: 13px; 
            margin: 30px var(--side-margin) 15px var(--side-margin); 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            display: block;
        }

        /* Experience Card */
        .experience-card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 20px;
            margin: 0 var(--side-margin) 25px var(--side-margin);
            backdrop-filter: blur(15px);
        }
        .exp-desc { font-size: 13px; line-height: 1.6; color: rgba(255,255,255,0.7); font-weight: 500; }

/* --- TIKTOK SLIDER REFINED --- */
/* --- TIKTOK SLIDER PREMIUM (ANTI-MEPET) --- */
/* --- TIKTOK SLIDER LUXURY ALIGNMENT (FIXED) --- */
.slider-wrapper {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    /* KUNCI: Gunakan padding yang sama persis dengan side-margin tombol */
    padding: 0 var(--side-margin) 25px var(--side-margin);
    scrollbar-width: none;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    
    /* Hapus margin-left/right negatif agar sejajar lurus dengan button */
    margin-left: 0;
    margin-right: 0;
    width: 100%;
}
.slider-wrapper::-webkit-scrollbar { display: none; }

/* Memastikan Gambar Muncul 100% Works */
.video-card img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Penting: Gambar tidak gepeng */
    position: absolute;
    inset: 0;
    z-index: 1;
    opacity: 1 !important; /* Paksa muncul */
}

.video-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, transparent 60%);
    z-index: 2;
}

.video-card i.fab.fa-tiktok {
    position: absolute; top: 15px; left: 15px;
    font-size: 18px; color: #fff; z-index: 3;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
}

.video-card .watch-label {
    position: absolute; bottom: 15px; left: 15px;
    font-size: 10px; font-weight: 800; color: #fff; z-index: 3;
    text-transform: uppercase; letter-spacing: 1px;
}
        /* Buttons Stack */
        .link-stack { padding: 0 var(--side-margin); margin-top: 10px; }
        .glass-btn { 
            margin-bottom: 12px !important; 
            border-radius: 20px !important; 
            background: var(--glass) !important;
            border: 1px solid var(--glass-border) !important;
            transition: 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
            padding: 12px 16px !important;
        }
        .glass-btn:hover { background: rgba(255,255,255,0.12) !important; transform: scale(1.02); }

        /* Footer Branding */
        .footer-branding {
            margin-top: 60px;
            text-align: center;
            padding-bottom: 40px;
        }
        .footer-credit { 
            font-size: 11px; 
            color: rgba(255,255,255,0.4); 
            text-decoration: none; 
            display: inline-flex; 
            align-items: center; 
            gap: 8px;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .footer-logo {
            height: 14px;
            opacity: 0.7;
            filter: grayscale(1) brightness(2); /* Menyesuaikan dengan gaya credit */
        }

        /* Animation */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-item { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    </style>
</head>
<body>

<div class="container">
    
    <!-- HEADER -->
    <div class="header-wrapper">
        <div class="cover-area" style="background-image: url('/uploads/<?= $user['cover_pic'] ?: 'default_cover.jpg' ?>');"></div>
        <div class="profile-container">
            <div class="profile-box">
                <img src="/uploads/<?= $user['profile_pic'] ?: 'default.png' ?>">
            </div>
            <h1><?= htmlspecialchars($user['fullname']) ?></h1>
            <span class="nick"><?= htmlspecialchars($user['nickname']) ?></span>
            <div class="badges">
                <div class="badge"><i class="fas fa-briefcase"></i> <?= htmlspecialchars($user['role_display'] ?: $user['role']) ?></div>
                <?php if($user['location']): ?>
                    <div class="badge"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($user['location']) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- DRIVE PREMIUM CARD -->
    <?php if($user['drive_url']): ?>
    <div style="padding: 0 var(--side-margin);">
        <a href="<?= $user['drive_url'] ?>" target="_blank" class="drive-card animate-item" style="animation-delay: 0.1s">
            <i class="fab fa-google-drive drive-icon"></i>
            <div class="drive-info">
                <div class="drive-title"><?= htmlspecialchars($user['drive_title']) ?></div>
                <div class="drive-sub">Tap to access official content</div>
            </div>
        </a>
    </div>
    <?php endif; ?>

    <!-- EXPERIENCE SECTION -->
    <?php if($user['exp_title'] || $user['exp_desc']): ?>
    <div class="animate-item" style="animation-delay: 0.2s">
        <span class="section-headline"><?= htmlspecialchars($user['exp_title'] ?: 'My Experience') ?></span>
        <div class="experience-card">
            <div class="exp-desc"><?= nl2br(htmlspecialchars($user['exp_desc'])) ?></div>
        </div>
    </div>
    <?php endif; ?>

<!-- TIKTOK SLIDER (VERTIKAL) -->
<!-- TIKTOK HIGHLIGHTS -->
<!-- TIKTOK HIGHLIGHTS (SUPPORT UP TO 10 VIDEOS) -->
<?php 
// Cek apakah ada setidaknya satu video tiktok yang diisi dari slot 1-10
$has_tiktok = false;
for($i=1; $i<=10; $i++) {
    if(!empty($user['tiktok_vid'.$i])) {
        $has_tiktok = true;
        break;
    }
}

if($has_tiktok): ?>
<div class="animate-item" style="animation-delay: 0.3s">
    <span class="section-headline">Highlights</span>
    <div class="slider-wrapper">
        <?php 
        // Ubah angka 3 menjadi 10 agar sistem membaca semua slot
        for($i=1; $i<=10; $i++): 
        ?>
            <?php if(!empty($user['tiktok_vid'.$i])): ?>
            <a href="<?= $user['tiktok_vid'.$i] ?>" target="_blank" class="video-card tt-fetch">
                <!-- Image akan diisi otomatis oleh JavaScript fetcher Anda -->
                <img src="" class="tt-thumb" alt="Thumbnail">
                
                <i class="fab fa-tiktok"></i>
                <div class="watch-label">Watch Video</div>
            </a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</div>
<?php endif; ?>

    <!-- GALLERY SECTION (Horizontal) -->
    <?php if($user['gal_title'] || $user['gal1_img']): ?>
    <div class="animate-item" style="animation-delay: 0.4s">
        <span class="section-headline"><?= htmlspecialchars($user['gal_title'] ?: 'Featured Projects') ?></span>
        <div class="slider-wrapper">
            <?php for($g=1; $g<=10; $g++): ?>
                <?php if(!empty($user['gal'.$g.'_img'])): ?>
                <div class="gallery-item">
                    <img src="/uploads/<?= $user['gal'.$g.'_img'] ?>" alt="Gallery Image">
                </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- LINKS & SOCIALS -->
    <div class="link-stack">
        <span class="section-headline" style="margin-left:0; margin-right:0;">Social Connect</span>
        
        <?php if($user['ig_user']): ?>
        <a href="https://instagram.com/<?= $user['ig_user'] ?>" target="_blank" class="glass-btn animate-item" style="animation-delay:0.5s">
            <div class="btn-icon-fa"><i class="fab fa-instagram"></i></div>
            <div class="btn-info"><div class="btn-title">Instagram</div><div class="btn-desc">Connect on social</div></div>
            <div class="btn-arrow"><i class="fas fa-chevron-right"></i></div>
        </a>
        <?php endif; ?>

        <?php if($user['tt_user']): ?>
        <a href="https://tiktok.com/@<?= $user['ig_user'] ?>" target="_blank" class="glass-btn animate-item" style="animation-delay:0.5s">
            <div class="btn-icon-fa"><i class="fab fa-tiktok"></i></div>
            <div class="btn-info"><div class="btn-title">Tiktok</div><div class="btn-desc">Connect on social</div></div>
            <div class="btn-arrow"><i class="fas fa-chevron-right"></i></div>
        </a>
        <?php endif; ?>
        
        <?php if($user['wa_number']): ?>
        <a href="https://wa.me/<?= $user['wa_number'] ?>" target="_blank" class="glass-btn animate-item" style="animation-delay:0.6s">
            <div class="btn-icon-fa"><i class="fab fa-whatsapp"></i></div>
            <div class="btn-info"><div class="btn-title">WhatsApp</div><div class="btn-desc">Chat for collaboration</div></div>
            <div class="btn-arrow"><i class="fas fa-chevron-right"></i></div>
        </a>
        <?php endif; ?>

        <!-- CUSTOM BUTTONS 1-10 -->
<!-- CUSTOM BUTTONS 1-10 (FIXED PATH & DESCRIPTION) -->
    <?php for($i=1; $i<=10; $i++): ?>
        <?php if(!empty($user['btn'.$i.'_url']) && !empty($user['btn'.$i.'_text'])): ?>
        <a href="<?= $user['btn'.$i.'_url'] ?>" target="_blank" class="glass-btn animate-item" style="animation-delay:0.7s">
            
            <!-- 1. BAGIAN ICON (Jalur sudah diperbaiki ke /assets/uploads/) -->
            <div class="btn-img-box">
                <?php if(!empty($user['btn'.$i.'_img'])): ?>
                    <img src="/uploads/<?= $user['btn'.$i.'_img'] ?>" alt="Icon">
                <?php else: ?>
                    <!-- Icon Default jika user tidak upload gambar -->
                    <i class="fas fa-link" style="color:#888; font-size: 16px;"></i>
                <?php endif; ?>
            </div>

            <!-- 2. BAGIAN TEKS & DESKRIPSI -->
            <div class="btn-info">
                <div class="btn-title"><?= htmlspecialchars($user['btn'.$i.'_text']) ?></div>
                
                <!-- Cek jika ada deskripsi, baru tampilkan -->
                <?php if(!empty($user['btn'.$i.'_desc'])): ?>
                    <div class="btn-desc"><?= htmlspecialchars($user['btn'.$i.'_desc']) ?></div>
                <?php endif; ?>
            </div>

            <div class="btn-arrow"><i class="fas fa-chevron-right"></i></div>
        </a>
        <?php endif; ?>
    <?php endfor; ?>
    </div>

    <!-- FOOTER WITH LOGO -->
    <div class="footer-branding animate-item" style="animation-delay:0.8s">
        <a href="https://bio.hvmdigital.id" target="_blank" class="footer-credit">
            Powered <img src="/assets/images/logobio.png" alt="HVM STUDIO" class="footer-logo">
        </a>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // --- 1. TRACKING AWAL (LAMA) ---
        track('page_view');

        // --- 2. TIKTOK THUMBNAIL FETCH (BARU & UPGRADE) ---
        // Menembak API TikTok secara otomatis untuk mengambil thumbnail link
        document.querySelectorAll('.tt-fetch').forEach(card => {
            const videoUrl = card.getAttribute('href');
            const imgTag = card.querySelector('.tt-thumb');
            
            if (videoUrl && videoUrl.includes('tiktok.com')) {
                fetch(`https://www.tiktok.com/oembed?url=${encodeURIComponent(videoUrl)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.thumbnail_url) {
                            imgTag.src = data.thumbnail_url;
                            imgTag.style.opacity = '1';
                        }
                    })
                    .catch(err => {
                        console.error("TikTok Fetch Error:", err);
                        // Fallback jika gagal fetch
                        imgTag.src = "https://placehold.co/400x600/111/fff?text=TikTok";
                        imgTag.style.opacity = '1';
                    });
            }
        });

        // --- 3. CLICK TRACKING (LAMA - PRESERVED) ---
        document.querySelectorAll('a').forEach(l => {
            l.addEventListener('click', () => {
                let t = 'other_click', h = l.getAttribute('href');
                if(h && h.includes('wa.me')) t='wa_click';
                else if(h && h.includes('instagram')) t='ig_click';
                else if(h && h.includes('tiktok')) t='tt_click';
                else if(h && h.includes('drive')) t='drive_click';
                else if(l.classList.contains('glass-btn')) t='btn_click';
                track(t);
            });
        });
    });

    // --- 4. TRACKING FUNCTION (LAMA - PRESERVED) ---
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