<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BioLink Builder | HVM Digital ID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- CSS Home -->
    <link rel="stylesheet" href="/home/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Background Animation -->
    <div class="bg-noise"></div>
    <div class="bg-glow glow-1"></div>
    <div class="bg-glow glow-2"></div>

    <!-- NAVBAR (Sticky) -->
    <nav class="navbar">
        <div class="logo">
            <!-- Ganti dengan path logo Anda -->
            <img src="/uploads/logohvm.png" alt="HVM Digital ID">
        </div>
        <div class="nav-auth">
            <a href="/login" class="nav-link">Login</a>
            <a href="/register" class="nav-btn">Register</a>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="hero-text">
            <div class="badge-new"><i class="fas fa-star"></i> v2.0 Luxury Edition</div>
            <h1>The Only Link <br><span class="text-silver">You'll Ever Need.</span></h1>
            <p class="subtitle">Hubungkan seluruh audiens ke semua konten Anda hanya dengan satu tautan eksklusif. Platform bio link paling elegan, 100% Gratis.</p>
            
            <form onsubmit="event.preventDefault(); window.location.href='/register?u='+document.getElementById('u').value" class="hero-form">
                <div class="input-glass">
                    <span class="domain-prefix">bio.hvmdigital.id/</span>
                    <input type="text" id="u" placeholder="username" autocomplete="off" required>
                    <button type="submit" class="btn-claim">Buat Sekarang <i class="fas fa-arrow-right"></i></button>
                </div>
            </form>

            <div class="hero-stats">
                <div class="stat-item"><i class="fas fa-check-circle"></i> <span>Free Forever</span></div>
                <div class="stat-item"><i class="fas fa-shield-alt"></i> <span>Secure Data</span></div>
                <div class="stat-item"><i class="fas fa-bolt"></i> <span>Fast Load</span></div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="glass-phone float-anim">
                <div class="notch"></div>
                <div class="screen-content">
                    <div class="dummy-profile">
                        <div class="dummy-img"></div>
                        <div class="dummy-name"></div>
                        <div class="dummy-role"></div>
                    </div>
                    <div class="dummy-btn"></div>
                    <div class="dummy-btn"></div>
                    <div class="dummy-btn"></div>
                    <div class="dummy-grid"><div></div><div></div></div>
                </div>
                <div class="reflection"></div>
            </div>
            <div class="float-card c1"><i class="fab fa-instagram"></i></div>
            <div class="float-card c2"><i class="fab fa-tiktok"></i></div>
            <div class="float-card c3"><i class="fab fa-whatsapp"></i></div>
        </div>
    </section>

    <!-- INSIGHTS SECTION (REVISI) -->
    <section class="insights-section">
        
        <!-- KOLOM KIRI: TEKS (Item Location Dihapus) -->
        <div class="insights-text">
            <h2>Get Real <span class="text-silver">Insights</span></h2>
            <div class="divider-glow"></div>
            <p class="subtitle">
                Pantau performa tautan Anda secara real-time. Ketahui berapa banyak orang yang melihat dan mengklik konten Anda dengan data analitik yang presisi.
            </p>
            <ul class="feature-list">
                <li><i class="fas fa-chart-line"></i> Traffic Monitoring</li>
                <!-- Location dihapus sesuai request -->
                <li><i class="fas fa-mouse-pointer"></i> Click Rate Tracker</li>
            </ul>
        </div>

        <!-- KOLOM KANAN: MOCKUP GRAFIK (SILVER THEME) -->
        <div class="insights-visual">
            
            <!-- Mockup 1: Line Chart -->
            <div class="glass-chart-card main-chart float-anim">
                <div class="chart-header">
                    <span>VIEWS</span>
                    <div class="badge-silver">Monthly</div>
                </div>
                <div class="chart-number">
                    12.4K <i class="fas fa-caret-up" style="color:#e0e0e0; font-size:16px;"></i>
                </div>
                <!-- SVG Line Graph (Warna diganti Silver #e0e0e0) -->
                <svg viewBox="0 0 300 100" class="svg-graph">
                    <defs>
                        <linearGradient id="gradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#e0e0e0" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#e0e0e0" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path fill="url(#gradient)" stroke="none" d="M0,80 Q50,70 100,40 T200,50 T300,10 V100 H0 Z" />
                    <path fill="none" stroke="#e0e0e0" stroke-width="3" d="M0,80 Q50,70 100,40 T200,50 T300,10" />
                    <circle cx="100" cy="40" r="4" fill="#fff" />
                    <circle cx="200" cy="50" r="4" fill="#fff" />
                    <circle cx="300" cy="10" r="4" fill="#fff" />
                </svg>
            </div>

            <!-- Mockup 2: Donut Chart (Revisi Label & Warna) -->
            <div class="glass-chart-card sub-chart float-anim-delayed">
                <div class="chart-header">
                    <span>TOTAL</span> <!-- Ubah judul jadi TOTAL -->
                </div>
                <div class="donut-wrapper">
                    <div class="donut-chart"></div>
                    <div class="donut-hole"></div>
                </div>
                <div class="chart-legend">
                    <div><span class="dot d1"></span> CLICK SOSMED</div> <!-- Ubah Label -->
                    <div><span class="dot d2"></span> LINK LAINNYA</div> <!-- Ubah Label -->
                </div>
            </div>

        </div>
    <!-- ... (Kode Insights Section Sebelumnya) ... -->
    </section>

    <!-- NEW SECTION: CTA (Call To Action) -->
    <section class="cta-section">
        <div class="glow-bg"></div>
        <div class="cta-content scroll-anim">
            <h2>Ready to Control <br> Your <span class="text-silver">Digital Identity?</span></h2>
            <p>Dapatkan kendali penuh atas audiens Anda. Kelola bio link, personal branding, dan analitik dalam satu platform eksklusif tanpa biaya.</p>
            
            <div class="cta-buttons">
                <a href="/register" class="btn-primary-glass">
                    Start Now <div class="shine"></div>
                </a>
                <a href="https://instagram.com/hvmdigital.id/" class="btn-secondary-glass">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- NEW FOOTER (Layout Referensi) -->
    <footer class="luxury-footer-v2">
        <div class="footer-card scroll-anim">
            
            <div class="footer-top">
                <!-- Kolom Kiri: Brand & Newsletter -->
                <div class="footer-brand-col">
                    <img src="/uploads/logohvm.png" alt="HVM Logo" class="footer-logo">
                    <p class="newsletter-text">Dapatkan tips branding eksklusif.</p>
                    
                    <form class="newsletter-form" onsubmit="event.preventDefault();">
                        <input type="email" placeholder="Enter your email">
                        <button type="submit">Submit</button>
                    </form>
                    
                    <p class="privacy-note">
                        Dengan berlangganan, Anda menyetujui Kebijakan Privasi kami untuk menerima pembaruan eksklusif.
                    </p>
                </div>

                <!-- Kolom Kanan: Links Grid -->
                <div class="footer-links-grid">
                    <div class="link-col">
                        <h4>Platform</h4>
                        <a href="#">Bio Link</a>
                        <a href="#">Analytics</a>
                        <a href="#">Themes</a>
                        <a href="#">Digital Store</a>
                    </div>
                    <div class="link-col">
                        <h4>Learn</h4>
                        <a href="#">Blog</a>
                        <a href="#">Tutorials</a>
                        <a href="#">Community</a>
                        <a href="#">Showcase</a>
                    </div>
                    <div class="link-col">
                        <h4>Company</h4>
                        <a href="#">About Us</a>
                        <a href="#">Careers</a>
                        <a href="#">Partners</a>
                        <a href="#">Contact</a>
                    </div>
                    <div class="link-col">
                        <h4>Legal</h4>
                        <a href="#">Terms & Conditions</a>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Cookies</a>
                        <a href="#">Disclaimer</a>
                    </div>
                </div>
            </div>

            <div class="footer-divider"></div>

            <div class="footer-bottom">
                &copy; <?= date('Y') ?> HVM Digital ID. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Script Animasi Scroll Sederhana -->
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        });
        document.querySelectorAll('.scroll-anim').forEach((el) => observer.observe(el));
    </script>

</body>
</html>