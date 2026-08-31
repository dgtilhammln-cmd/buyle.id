<!-- Load Font Premium -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --neon: #a1ff5a;
        --dark-bg: #020b09;
        --glass: rgba(255, 255, 255, 0.03);
        --border: rgba(255, 255, 255, 0.08);
    }

    /* --- HERO WRAPPER --- */
    .hero-v2 {
        position: relative;
        padding: 160px 20px 120px;
        background-color: var(--dark-bg);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        overflow: hidden;
        min-height: 100vh;
        justify-content: center;
    }

    /* --- SIDE MESH FLARES (Better Stack Style) --- */
    .hero-v2::before, .hero-v2::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 40vw;
        height: 80vh;
        transform: translateY(-50%);
        pointer-events: none;
        z-index: 1;
        opacity: 0.5;
    }

    .hero-v2::before {
        left: -10vw;
        background: radial-gradient(circle at center, rgba(161, 255, 90, 0.15) 0%, transparent 70%);
        clip-path: polygon(0% 0%, 100% 50%, 0% 100%);
        animation: flareLeft 8s ease-in-out infinite alternate;
    }

    .hero-v2::after {
        right: -10vw;
        background: radial-gradient(circle at center, rgba(161, 255, 90, 0.15) 0%, transparent 70%);
        clip-path: polygon(100% 0%, 0% 50%, 100% 100%);
        animation: flareRight 8s ease-in-out infinite alternate;
    }

    @keyframes flareLeft { from { transform: translateY(-50%) translateX(-20px); } to { transform: translateY(-50%) translateX(20px); } }
    @keyframes flareRight { from { transform: translateY(-50%) translateX(20px); } to { transform: translateY(-50%) translateX(-20px); } }

    /* --- CENTER CONTENT --- */
    .hero-container {
        position: relative;
        z-index: 10;
        max-width: 900px;
        width: 100%;
    }

    .brand-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--glass);
        padding: 8px 20px;
        border-radius: 100px;
        border: 1px solid var(--border);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
        animation: fadeInUp 0.8s ease backwards;
    }

    .hero-v2 h1 {
        font-size: clamp(48px, 8vw, 92px);
        font-weight: 800;
        color: #fff;
        line-height: 1.05;
        letter-spacing: -4px;
        margin-bottom: 30px;
        animation: fadeInUp 1s ease 0.2s backwards;
    }

    .hero-v2 h1 span {
        background: linear-gradient(to bottom, #fff 30%, rgba(255,255,255,0.4) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-v2 p {
        font-size: clamp(16px, 2vw, 20px);
        color: #94a3b8;
        max-width: 600px;
        margin: 0 auto 50px;
        line-height: 1.6;
        animation: fadeInUp 1s ease 0.4s backwards;
    }

    /* --- CLAIM BOX UPGRADE --- */
    .claim-form {
        display: flex;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 8px;
        border-radius: 24px;
        max-width: 550px;
        margin: 0 auto;
        backdrop-filter: blur(20px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
        animation: fadeInUp 1s ease 0.6s backwards;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .claim-form:focus-within {
        border-color: var(--neon);
        box-shadow: 0 0 40px rgba(161, 255, 90, 0.15);
        transform: scale(1.02);
    }

    .input-group {
        display: flex;
        align-items: center;
        flex-grow: 1;
        padding-left: 15px;
    }

    .prefix {
        color: #4b5563;
        font-weight: 700;
        font-size: 16px;
        user-select: none;
    }

    .claim-form input {
        background: transparent;
        border: none;
        color: #fff;
        padding: 15px 10px;
        font-size: 18px;
        font-weight: 700;
        outline: none;
        width: 100%;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

/* --- CSS TOMBOL YANG SUDAH DIUBAH KE HIJAU --- */
    .btn-claim-v2 {
        background: var(--neon); /* Menggunakan variabel warna hijau neon */
        color: var(--dark-bg);   /* Teks diubah ke hitam agar kontras */
        border: none;
        padding: 0 35px;
        border-radius: 18px;
        font-weight: 800;
        font-size: 15px;
        cursor: pointer;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-claim-v2:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(161, 255, 90, 0.4); /* Glow hijau saat dihover */
        background: #fff; /* Berubah jadi putih saat dihover agar luxury */
    }

    .hero-footer {
        margin-top: 30px;
        font-size: 14px;
        color: #4b5563;
        animation: fadeInUp 1s ease 0.8s backwards;
    }

    .hero-footer a {
        color: #94a3b8;
        text-decoration: underline;
        transition: 0.3s;
    }

    .hero-footer a:hover { color: var(--neon); }

    /* --- ANIMATION ENGINE --- */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); filter: blur(10px); }
        to { opacity: 1; transform: translateY(0); filter: blur(0); }
    }

    /* --- MOBILE RESPONSIVE --- */
    @media (max-width: 768px) {
        .hero-v2 h1 { letter-spacing: -2px; }
        .hero-v2::before, .hero-v2::after { display: none; }
        .claim-form { 
            flex-direction: column; 
            background: transparent; 
            border: none; 
            box-shadow: none;
            gap: 15px;
            padding: 0;
        }
        .input-group {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 10px 15px;
        }
        .btn-claim-v2 {
            padding: 20px;
            width: 100%;
        }
    }
</style>

<section class="hero-v2">
    <!-- Center Glow -->
    <div style="position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(161, 255, 90, 0.05) 0%, transparent 70%); top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;"></div>

    <div class="hero-container">
        <div class="brand-pill">
            <i class="fas fa-bolt" style="color: var(--neon);"></i>
            <span>Powered by HVM Digital ID</span>
        </div>

        <h1><span>Link Bio Premium</span> <br> #1 Indonesia.</h1>
        <p>Hubungkan audiens ke seluruh konten Anda dalam satu tautan eksklusif. Bio Link tercanggih dengan estetika premium.</p>
        
        <form action="/register" method="GET" class="claim-form">
            <div class="input-group">
                <span class="prefix">bio/</span>
                <input type="text" name="u" placeholder="yourname" autocomplete="off" required>
            </div>
            <button type="submit" class="btn-claim-v2">Claim Free</button>
        </form>

        <div class="hero-footer">
            Start building for free or <a href="#">view pro features</a>
        </div>
    </div>
</section>