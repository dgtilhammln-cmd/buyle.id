<?php
/**
 * HVM STUDIO - PREMIUM FEATURE TOUR v2.2 (ULTIMATE)
 * Trigger: Hanya muncul di menu Overview & Login pertama kali
 */

// Pastikan hanya muncul jika view adalah overview atau kosong
$current_view = $_GET['view'] ?? 'overview';
if ($current_view !== 'overview') return;

$user_display_name = explode(' ', $me['fullname'])[0]; // Ambil nama depan
?>

<div id="tutorialOverlay" class="tour-overlay" style="display: none;">
    <div class="tour-card">
        <!-- Skip Button -->
        <button class="tour-skip" onclick="closeTour()">LEWATI</button>
        
        <!-- Close Button (X) -->
        <button class="tour-close" onclick="closeTour()"><i class="fas fa-times"></i></button>

        <!-- Slide Container -->
        <div class="tour-slides">
            <!-- Slide 1: Welcome User -->
            <div class="tour-slide active" data-step="1">
                <div class="logo-tour-wrapper pulsing">
                    <img src="/assets/images/logobio.png" alt="Logo" class="logo-tour-img">
                </div>
                <h2>Hi, <?= $user_display_name ?>!</h2>
                <p>Selamat datang kembali di <b>BIO by HVM Digital ID</b>. Kami telah merombak total sistem backend dan visual untuk pengalaman branding yang lebih maksimal & 2x lebih cepat.</p>
            </div>

            <!-- Slide 2: Premium Features -->
            <div class="tour-slide" data-step="2">
                <div class="tour-icon"><i class="fas fa-layer-group"></i></div>
                <h2>Pro Features</h2>
                <div class="feat-pro-grid">
                    <div class="f-pro-item"><i class="fas fa-palette"></i> 10 Tema Premium</div>
                    <div class="f-pro-item"><i class="fas fa-briefcase"></i> Work Experience</div>
                    <div class="f-pro-item"><i class="fas fa-images"></i> Project Gallery</div>
                    <div class="f-pro-item"><i class="fas fa-check-double"></i> 10 Custom Buttons</div>
                </div>
                <p>Ekspresikan dirimu lebih profesional dengan berbagai fitur eksklusif yang kini tersedia.</p>
            </div>

            <!-- Slide 3: Affiliate & Commission -->
            <div class="tour-slide" data-step="3">
                <div class="tour-icon"><i class="fas fa-coins"></i></div>
                <h2>Cuan Affiliate</h2>
                <p>Dapatkan penghasilan pasif! Setiap teman yang upgrade melalui link bio-mu akan memberimu komisi <b>15%</b>. Cairkan saldo komisi langsung ke rekeningmu kapan saja.</p>
            </div>
        </div>

        <!-- Footer Navigation -->
        <div class="tour-footer">
            <div class="tour-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
            <div class="tour-btns">
                <button id="prevBtn" class="btn-tour-secondary" onclick="moveTour(-1)" style="visibility: hidden;">KEMBALI</button>
                <button id="nextBtn" class="btn-tour-primary" onclick="moveTour(1)">LANJUT</button>
            </div>
        </div>
    </div>
</div>

<style>
/* --- TOUR SYSTEM STYLES --- */
.tour-overlay {
    position: fixed; inset: 0;
    background: rgba(2, 11, 9, 0.9);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    z-index: 999999;
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
    animation: tourFadeIn 0.8s cubic-bezier(0.23, 1, 0.32, 1);
}

.tour-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.12);
    width: 100%; max-width: 440px;
    border-radius: 50px;
    padding: 60px 40px 40px;
    text-align: center;
    position: relative;
    box-shadow: 0 50px 100px rgba(0,0,0,0.8);
    color: #fff;
}

/* Logo Shimmer Style */
.logo-tour-wrapper {
    width: 140px; height: 70px;
    background: rgba(255,255,255,0.03);
    border-radius: 20px; display: flex; align-items: center; justify-content: center;
    margin: 0 auto 30px; position: relative; overflow: hidden;
}
.logo-tour-img { max-width: 80%; max-height: 80%; object-fit: contain; }

.feat-pro-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 20px 0;
}
.f-pro-item {
    background: rgba(161, 255, 90, 0.1); color: #a1ff5a;
    padding: 10px; border-radius: 12px; font-size: 10px; font-weight: 800;
    text-align: center; border: 1px solid rgba(161,255,90,0.1);
}

.tour-icon {
    width: 80px; height: 80px; background: #a1ff5a;
    border-radius: 25px; display: flex; align-items: center; justify-content: center;
    margin: 0 auto 25px; font-size: 32px; color: #000;
}

.tour-card h2 { font-size: 32px; font-weight: 900; margin-bottom: 10px; letter-spacing: -1.5px; }
.tour-card p { font-size: 14px; color: rgba(255,255,255,0.5); line-height: 1.6; min-height: 80px; }

/* Buttons */
.btn-tour-primary { flex: 2; padding: 18px; border-radius: 20px; border: none; background: #a1ff5a; color: #000; font-weight: 900; font-size: 13px; cursor: pointer; transition: 0.3s; }
.btn-tour-secondary { flex: 1; padding: 18px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); background: none; color: #fff; font-weight: 700; font-size: 13px; cursor: pointer; }
.btn-tour-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(161, 255, 90, 0.3); }

/* Navigation */
.tour-skip { position: absolute; top: 30px; left: 35px; background: none; border: none; color: rgba(255,255,255,0.3); font-size: 11px; font-weight: 800; cursor: pointer; letter-spacing: 1px; }
.tour-close { position: absolute; top: 25px; right: 30px; background: none; border: none; color: rgba(255,255,255,0.2); font-size: 20px; cursor: pointer; }

.tour-dots { display: flex; justify-content: center; gap: 8px; margin-bottom: 30px; }
.dot { width: 8px; height: 8px; background: rgba(255,255,255,0.2); border-radius: 50%; transition: 0.4s; }
.dot.active { width: 25px; background: #a1ff5a; border-radius: 10px; }

/* Animations */
.pulsing { animation: tourPulse 3s infinite; }
@keyframes tourPulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
@keyframes tourFadeIn { from { opacity: 0; } to { opacity: 1; } }

.tour-slide { display: none; animation: slideUp 0.5s ease; }
.tour-slide.active { display: block; }
@keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

@media (max-width: 480px) {
    .tour-card { padding: 45px 30px 30px; }
    .tour-card h2 { font-size: 26px; }
}
</style>

<script>
let currentStep = 1;
const totalSteps = 3;

function moveTour(dir) {
    currentStep += dir;
    if (currentStep > totalSteps) { closeTour(); return; }

    document.querySelectorAll('.tour-slide').forEach(s => s.classList.remove('active'));
    document.querySelector(`.tour-slide[data-step="${currentStep}"]`).classList.add('active');

    document.querySelectorAll('.dot').forEach((d, idx) => {
        d.classList.toggle('active', idx === currentStep - 1);
    });

    document.getElementById('prevBtn').style.visibility = currentStep > 1 ? 'visible' : 'hidden';
    document.getElementById('nextBtn').innerText = currentStep === totalSteps ? 'MULAI SEKARANG' : 'LANJUT';
}

function closeTour() {
    const overlay = document.getElementById('tutorialOverlay');
    overlay.style.transition = '0.4s ease';
    overlay.style.opacity = '0';
    setTimeout(() => { overlay.style.display = 'none'; }, 400);
}

// Muncul otomatis saat load
window.addEventListener('load', () => {
    setTimeout(() => {
        document.getElementById('tutorialOverlay').style.display = 'flex';
    }, 1000);
});
</script>