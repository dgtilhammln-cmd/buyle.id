<!-- Load Font & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --neon: #a1ff5a;
        --dark-bg: #020b09;
        --glass: rgba(255, 255, 255, 0.03);
        --border: rgba(255, 255, 255, 0.08);
        --transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
    }

    /* --- FOOTER MAIN WRAPPER --- */
    footer {
        background-color: var(--dark-bg);
        padding: 100px 20px 60px;
        position: relative;
        overflow: hidden;
        border-top: 1px solid var(--border);
    }

    /* Background Mesh Glow (Matching Section 1) */
    footer::before {
        content: '';
        position: absolute;
        bottom: -10%;
        left: 50%;
        width: 600px;
        height: 400px;
        background: radial-gradient(circle, rgba(161, 255, 90, 0.05) 0%, transparent 70%);
        transform: translateX(-50%);
        pointer-events: none;
    }

    .footer-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.2fr 0.6fr 0.6fr 1.2fr;
        gap: 40px;
        position: relative;
        z-index: 10;
    }

    /* --- COLUMN 1: BRANDING --- */
    .f-brand img {
        height: 45px;
        margin-bottom: 25px;
        filter: drop-shadow(0 0 15px rgba(161, 255, 90, 0.2));
    }

    .f-brand p {
        color: #94a3b8;
        line-height: 1.7;
        font-size: 15px;
        margin-bottom: 30px;
        max-width: 300px;
    }

    /* Rating System */
    .f-rating { margin-bottom: 30px; }
    .stars { display: flex; gap: 5px; margin-bottom: 10px; }
    .stars i { 
        background: #111; 
        color: var(--neon); 
        padding: 8px; 
        border-radius: 8px; 
        font-size: 14px; 
        border: 1px solid var(--border);
    }
    .rating-text { color: #fff; font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 10px; }
    .trustpilot i { color: var(--neon); font-size: 18px; }

    /* --- COLUMN 2 & 3: LINKS --- */
    .f-links h5 {
        color: #fff;
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 25px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .f-links ul { list-style: none; padding: 0; }
    .f-links ul li { margin-bottom: 15px; }
    .f-links ul li a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: var(--transition);
    }
    .f-links ul li a:hover { color: var(--neon); padding-left: 8px; }

    /* --- COLUMN 4: PREMIUM CONTACT CARD --- */
    .f-contact-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border);
        border-radius: 35px;
        padding: 35px;
        text-align: center;
        backdrop-filter: blur(20px);
        transition: var(--transition);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .f-contact-card:hover {
        border-color: var(--neon);
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(161, 255, 90, 0.1);
    }

    .cs-icon {
        width: 60px;
        height: 60px;
        background: var(--dark-bg);
        border: 1px solid var(--neon);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: var(--neon);
        font-size: 24px;
        box-shadow: 0 0 20px rgba(161, 255, 90, 0.2);
    }

    .f-contact-card h6 { color: #fff; font-size: 18px; font-weight: 800; margin-bottom: 10px; }
    .f-contact-card p { color: #94a3b8; font-size: 13px; margin-bottom: 20px; line-height: 1.5; }

    .btn-whatsapp {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: #fff;
        color: #000;
        text-decoration: none;
        padding: 16px;
        border-radius: 18px;
        font-weight: 900;
        font-size: 14px;
        transition: var(--transition);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-whatsapp:hover {
        background: var(--neon);
        transform: scale(1.03);
    }

    /* --- SOCIAL MEDIA --- */
    .f-socials { margin-top: 40px; display: flex; gap: 15px; }
    .social-btn {
        width: 45px;
        height: 45px;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        transition: var(--transition);
    }
    .social-btn:hover {
        background: var(--neon);
        color: #000;
        border-color: var(--neon);
        transform: translateY(-5px) rotate(8deg);
    }

    /* --- COPYRIGHT LINE --- */
    .f-bottom {
        max-width: 1200px;
        margin: 80px auto 0;
        padding-top: 30px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #4b5563;
        font-size: 13px;
        font-weight: 600;
    }
    .f-legal-link {
    text-decoration: none; /* Menghilangkan garis bawah */
    color: inherit;        /* Mengikuti warna teks sekitarnya (abu-abu) */
    transition: 0.3s;      /* Efek halus saat dihover */
    font-weight: 700;
}

.f-legal-link:hover {
    color: var(--neon);    /* Berubah menjadi hijau neon saat disentuh */
    opacity: 1;
}

    /* --- MOBILE RESPONSIVE --- */
    @media (max-width: 992px) {
        .footer-grid { grid-template-columns: 1fr 1fr; gap: 50px; }
        .f-contact-card { grid-column: span 2; }
    }

    @media (max-width: 600px) {
        .footer-grid { grid-template-columns: 1fr; }
        .f-contact-card { grid-column: span 1; }
        .f-bottom { flex-direction: column; gap: 20px; text-align: center; }
    }

    /* --- ANIMATION --- */
    .reveal-footer { animation: fadeInUp 1s ease backwards; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<footer class="reveal-footer">
    <div class="footer-grid">
        <!-- Brand & Rating -->
        <div class="f-brand">
            <img src="/assets/images/logo.png" alt="HVM LOGO">
            <p>Platform bio-link tercanggih yang dirancang untuk performa tinggi, analitik presisi, dan estetika premium.</p>
            
            <div class="f-rating">
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <div class="rating-text">
                    4.9 / 5 • 100+ Reviews
                </div>
            </div>

            <div class="f-socials">
                <a href="https://instagram.com/hvmdigital.id" class="social-btn" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://tiktok.com/@hvmdigital.id" class="social-btn" target="_blank">
                    <i class="fab fa-tiktok"></i>
                </a>
            </div>
        </div>

        <!-- Company Links -->
        <div class="f-links">
            <h5>Company</h5>
            <ul>
                <li><a href="#">About HVM</a></li>
                <li><a href="#">Enterprise</a></li>
                <li><a href="#">Security</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
            </ul>
        </div>

        <!-- Product Links -->
        <div class="f-links">
            <h5>Product</h5>
            <ul>
                <li><a href="#">Design Engine</a></li>
                <li><a href="#">Pro Features</a></li>
                <li><a href="#">AI Statistics</a></li>
                <li><a href="#">Templates</a></li>
                <li><a href="#">Integrations</a></li>
            </ul>
        </div>

        <!-- Premium CS Card -->
        <div class="f-contact-card">
            <div class="cs-icon">
                <i class="fas fa-headset"></i>
            </div>
            <h6>CS Support</h6>
            <p>Butuh bantuan? Tim kami siap melayani Anda melalui WhatsApp.</p>
            <a href="https://wa.me/6285179982373" class="btn-whatsapp" target="_blank">
                <i class="fab fa-whatsapp" style="font-size: 20px;"></i>
                Hubungi Kami
            </a>
        </div>
    </div>

    <!-- Copyright Footer -->
    <div class="f-bottom">
<span>© 2026 <a href="https://hvmdigital.id" target="_blank" class="f-legal-link">HVM Digital ID</a>. All rights reserved.</span>
        <div style="display: flex; gap: 20px;">
            <span>PT HVM ORBIT STUDIOS</span>
            <span style="color: var(--neon);">v1.0</span>
        </div>
    </div>
</footer>