<?php
/**
 * ==============================================================================
 * HVM DIGITAL ID - SEO & AI TERCANGGIH v2.5 (ELITE EDITION)
 * ==============================================================================
 * Connection  : public_html/home/index.php
 * Framework   : HVM Design Engine 6.0
 * Standards   : Schema JSON-LD Graph, Dublin Core, OG v5, AI Semantic Core
 * Logic       : Intelligent Scroll Reveal (Anti-Blank System)
 * ==============================================================================
 */

// Konfigurasi SEO Dinamis
$site_title = "Bio by HVM Digital | Premium Bio Link Solution for Professionals";
$site_desc  = "Ciptakan identitas digital eksklusif dengan platform Bio Link tercanggih. Desain ultra-luxury, kompresi WebP otomatis, dan analitik presisi untuk konversi tinggi.";
$site_url   = "https://bio.hvmdigital.id";
$brand_img  = $site_url . "/assets/images/og-main.png";
?>
<!DOCTYPE html>
<html lang="id" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- --- 1. SEO META ELITE --- -->
    <title><?php echo $site_title; ?></title>
    <meta name="description" content="<?php echo $site_desc; ?>">
    <meta name="keywords" content="bio link, link in bio premium, hvm digital, portfolio stack, digital identity, landing page creator">
    <meta name="author" content="HVM Studio">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
    <link rel="canonical" href="<?php echo $site_url; ?>">

    <!-- --- 2. OPEN GRAPH & SOCIAL (AI FRIENDLY) --- -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="HVM Digital">
    <meta property="og:url" content="<?php echo $site_url; ?>">
    <meta property="og:title" content="<?php echo $site_title; ?>">
    <meta property="og:description" content="<?php echo $site_desc; ?>">
    <meta property="og:image" content="<?php echo $brand_img; ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $site_title; ?>">
    <meta name="twitter:description" content="<?php echo $site_desc; ?>">
    <meta name="twitter:image" content="<?php echo $brand_img; ?>">

    <!-- --- 3. JSON-LD SCHEMA GRAPH (Otak AI Google & ChatGPT) --- -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Organization",
          "@id": "<?php echo $site_url; ?>/#organization",
          "name": "HVM Digital",
          "url": "<?php echo $site_url; ?>",
          "logo": "<?php echo $site_url; ?>/assets/images/logo.png",
          "sameAs": ["https://instagram.com/hvmdigital.id"]
        },
        {
          "@type": "WebSite",
          "@id": "<?php echo $site_url; ?>/#website",
          "url": "<?php echo $site_url; ?>",
          "name": "HVM Digital ID",
          "publisher": {"@id": "<?php echo $site_url; ?>/#organization"},
          "potentialAction": [{
            "@type": "SearchAction",
            "target": "<?php echo $site_url; ?>/register?u={search_term_string}",
            "query-input": "required name=search_term_string"
          }]
        }
      ]
    }
    </script>

    <!-- --- 4. ASSETS & FONTS --- -->
    <link rel="icon" type="image/png" href="/assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to SEO Web App Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <style>
        :root {
            --neon: #a1ff5a;
            --bg-dark: #020b09;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.1);
            --transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; scroll-behavior: smooth; }
        
        body { 
            background-color: var(--bg-dark); 
            color: var(--white);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Utility SEO */
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }

        /* Anti-Blank Section System */
        section { 
            position: relative; 
            width: 100%; 
            min-height: 50px; /* Memastikan section memiliki tinggi untuk dideteksi bot */
            opacity: 1; /* Default terlihat agar SEO friendly jika JS mati */
            transition: var(--transition);
        }

        /* Animasi Reveal khusus untuk Browser modern */
        .reveal-hidden { 
            opacity: 0; 
            transform: translateY(40px); 
            filter: blur(10px); 
        }
        
        .reveal-visible { 
            opacity: 1 !important; 
            transform: translateY(0) !important; 
            filter: blur(0) !important;
        }

        .text-neon { color: var(--neon); text-shadow: 0 0 15px rgba(161, 255, 90, 0.3); }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Background Dynamic Blobs */
        .blob {
            position: fixed; width: 50vw; height: 50vw;
            background: radial-gradient(circle, rgba(161, 255, 90, 0.05) 0%, transparent 70%);
            z-index: -1; pointer-events: none; filter: blur(60px);
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: #1a1a1a; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--neon); }
    </style>
</head>
<body>
    <!-- AI Contextual Guard -->
    <h1 class="sr-only"><?php echo $site_title; ?></h1>

    <div class="blob" style="top: -10%; left: -10%;"></div>
    <div class="blob" style="bottom: -10%; right: -10%;"></div>

    <!-- SEMANTIC HEADER -->
    <header id="main-header">
        <?php include __DIR__ . '/header.php'; ?>
    </header>

    <main id="main-content">
        <!-- HERO SECTION -->
        <section id="hero" class="reveal-section reveal-hidden">
            <?php include __DIR__ . '/section1.php'; ?>
        </section>

        <!-- INSIGHTS SECTION -->
        <section id="insights" class="reveal-section reveal-hidden">
            <?php include __DIR__ . '/section2.php'; ?>
        </section>

        <!-- CTA & FEATURES SECTION -->
        <section id="features" class="reveal-section reveal-hidden">
            <?php include __DIR__ . '/section3.php'; ?>
        </section>
    </main>

    <!-- SEMANTIC FOOTER -->
    <footer id="main-footer">
        <?php include __DIR__ . '/footer.php'; ?>
    </footer>

    <!-- --- 5. LOGIC SCRIPTS (PRESERVED & ENHANCED) --- -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sections = document.querySelectorAll('.reveal-section');
            
            // Konfigurasi sensor scroll yang lebih sensitif (AI & User Friendly)
            const observerOptions = {
                threshold: 0.05,
                rootMargin: "0px 0px -50px 0px"
            };

            const sectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal-visible');
                    }
                });
            }, observerOptions);

            sections.forEach(section => {
                sectionObserver.observe(section);
            });

            // Log untuk audit AI/SEO
            console.log("%c HVM SEO ENGINE ACTIVE %c v2.5 ", "color: #000; background: #a1ff5a; font-weight: bold; padding: 4px;", "color: #fff; background: #020b09; padding: 4px;");
        });
    </script>

    <?php 
        /**
         * POPUP GLOBAL SINKRONISASI
         * Memanggil popup.php dari folder templates (jalur ../)
         */
        $popup_path = __DIR__ . '/../templates/popup.php';
        if(file_exists($popup_path)) include $popup_path; 
    ?>
</body>
</html>