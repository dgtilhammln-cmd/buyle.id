<style>
    /* --- HVM ELITE DESIGN SYSTEM --- */
    :root {
        --neon: #a1ff5a;
        --dark-bg: #020b09;
        --glass-bg: rgba(2, 11, 9, 0.7);
        --border-glass: rgba(255, 255, 255, 0.05);
        --transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        --navbar-height: 85px;
        --navbar-height-mobile: 64px;
    }

    /* --- NAVBAR --- */
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: var(--navbar-height);
        z-index: 9999;
        display: flex;
        align-items: center;
        background: var(--glass-bg);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border-bottom: 1px solid var(--border-glass);
        transition: var(--transition);
        box-sizing: border-box;
    }

    .navbar.scrolled {
        height: 64px;
        background: rgba(2, 11, 9, 0.95);
    }

    .nav-container {
        width: 100%;
        max-width: 1300px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 40px;
        box-sizing: border-box;
    }

    /* --- LOGO --- */
    .logo-elite {
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: var(--transition);
        flex-shrink: 0;
    }

    .logo-elite img {
        height: 42px;
        width: auto;
        filter: drop-shadow(0 0 15px rgba(161, 255, 90, 0.2));
        transition: var(--transition);
        display: block;
    }

    .logo-elite:hover img {
        transform: scale(1.05);
        filter: drop-shadow(0 0 20px rgba(161, 255, 90, 0.5));
    }

    /* --- NAV ACTIONS --- */
    .nav-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    /* Sign In — outline pill default */
    .link-login {
        color: rgba(255, 255, 255, 0.65);
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 9px 18px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 100px;
        transition: var(--transition);
        white-space: nowrap;
        line-height: 1;
    }

    .link-login:hover {
        color: #fff;
        border-color: rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.05);
    }

    /* --- CTA BUTTON --- */
    .btn-cta-elite {
        position: relative;
        background: var(--neon);
        color: #000;
        text-decoration: none;
        font-weight: 900;
        font-size: 12px;
        padding: 10px 24px;
        border-radius: 100px;
        text-transform: uppercase;
        letter-spacing: 1px;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 8px 20px rgba(161, 255, 90, 0.25);
        border: 1px solid transparent;
        white-space: nowrap;
        line-height: 1;
    }

    .btn-cta-elite::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -60%;
        width: 20%;
        height: 200%;
        background: rgba(255, 255, 255, 0.4);
        transform: rotate(30deg);
        transition: 0.6s;
    }

    .btn-cta-elite:hover::after { left: 120%; }

    .btn-cta-elite:hover {
        background: #fff;
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(161, 255, 90, 0.4);
    }

    /* --- BODY OFFSET — wajib agar konten tidak tertindih navbar --- */
    body {
        padding-top: var(--navbar-height);
    }

    /* --- TABLET --- */
    @media (max-width: 992px) {
        :root {
            --navbar-height: var(--navbar-height-mobile);
        }

        .navbar { height: var(--navbar-height-mobile); }
        .navbar.scrolled { height: 58px; }

        .nav-container { padding: 0 20px; }

        .logo-elite img { height: 32px; }

        .nav-actions { gap: 8px; }

        .link-login {
            font-size: 11px;
            padding: 8px 14px;
            letter-spacing: 0.8px;
        }

        .btn-cta-elite {
            font-size: 11px;
            padding: 9px 16px;
            letter-spacing: 0.6px;
        }
    }

    /* --- MOBILE KECIL --- */
    @media (max-width: 480px) {
        .nav-container { padding: 0 14px; }

        .logo-elite img { height: 28px; }

        .nav-actions { gap: 6px; }

        .link-login {
            font-size: 10px;
            padding: 7px 11px;
            letter-spacing: 0.5px;
        }

        .btn-cta-elite {
            font-size: 10px;
            padding: 8px 12px;
            letter-spacing: 0.3px;
        }
    }

    /* --- SANGAT SEMPIT (< 360px) --- */
    @media (max-width: 360px) {
        .link-login { padding: 6px 9px; font-size: 9px; }
        .btn-cta-elite { padding: 7px 10px; font-size: 9px; }
    }
</style>

<nav class="navbar" id="hvmNavbar">
    <div class="nav-container">
        <a href="/" class="logo-elite">
            <img src="/assets/images/logo.png" alt="HVM STUDIO">
        </a>
        <div class="nav-actions">
            <a href="/login" class="link-login">Sign In</a>
            <a href="/register" class="btn-cta-elite">Get Started Free</a>
        </div>
    </div>
</nav>

<script>
    (function() {
        var nav = document.getElementById('hvmNavbar');
        var lastScroll = 0;

        window.addEventListener('scroll', function() {
            var y = window.pageYOffset;
            if (y > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
            lastScroll = y;
        }, { passive: true });
    })();
</script>