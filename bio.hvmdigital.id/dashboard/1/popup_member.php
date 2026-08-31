<!-- HVM MEMBER POPUP COMPONENT -->
<style>
    /* Overlay Gelap */
    .member-popup-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);
        z-index: 10000; /* Di atas segalanya */
        display: flex; justify-content: center; align-items: center;
        opacity: 0; visibility: hidden;
        transition: all 0.4s ease;
    }

    /* Active State */
    .member-popup-overlay.active {
        opacity: 1; visibility: visible;
    }

    /* Kartu Popup Mewah */
    .member-popup-card {
        width: 85%; max-width: 360px; /* Ukuran Compact/Tidak Kebesaran */
        background: linear-gradient(145deg, #0a0a0a, #000000);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-top: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        padding: 30px 20px;
        text-align: center;
        position: relative;
        transform: scale(0.9);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 20px 60px rgba(0,0,0,0.9), inset 0 0 20px rgba(255,255,255,0.02);
    }

    /* Animasi Masuk */
    .member-popup-overlay.active .member-popup-card {
        transform: scale(1);
    }

    /* Efek Kilau Silver */
    .popup-icon-wrapper {
        width: 60px; height: 60px; margin: 0 auto 15px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        display: flex; justify-content: center; align-items: center;
        border: 1px solid rgba(255,255,255,0.1);
        box-shadow: 0 0 15px rgba(255,255,255,0.05);
    }
    
    .popup-icon-wrapper i {
        font-size: 28px;
        background: linear-gradient(135deg, #fff 0%, #999 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 0 5px rgba(255,255,255,0.5));
    }

    /* Typography */
    .mp-title {
        font-family: 'Outfit', sans-serif;
        font-size: 18px; font-weight: 700; color: #fff;
        margin-bottom: 8px; letter-spacing: 0.5px;
    }

    .mp-desc {
        font-family: 'Outfit', sans-serif;
        font-size: 12px; color: #888; line-height: 1.5;
        margin-bottom: 25px; padding: 0 10px;
    }

    /* Tombol Close (Pojok Kiri Atas) */
    .mp-close {
        position: absolute; top: 12px; left: 12px;
        width: 28px; height: 28px;
        background: rgba(255,255,255,0.05);
        border: none; border-radius: 50%;
        color: #fff; cursor: pointer;
        display: flex; justify-content: center; align-items: center;
        transition: 0.3s; font-size: 12px;
    }
    .mp-close:hover { background: rgba(255,255,255,0.2); transform: rotate(90deg); }

    /* Tombol Sosmed (Grid) */
    .mp-actions {
        display: flex; gap: 10px; justify-content: center;
    }

    .btn-mp-social {
        flex: 1;
        padding: 10px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 12px; font-weight: 600;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: 0.3s;
    }

    /* Style Tombol Silver & Dark */
    .btn-ig {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1);
        color: #e0e0e0;
    }
    .btn-ig:hover { background: rgba(255,255,255,0.2); border-color: #fff; color: #fff; }

    .btn-tt {
        background: linear-gradient(135deg, #e0e0e0, #999);
        color: #000;
        border: none;
        box-shadow: 0 0 15px rgba(255,255,255,0.1);
    }
    .btn-tt:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(255,255,255,0.3); }

</style>

<div class="member-popup-overlay" id="memberPopup">
    <div class="member-popup-card">
        <button class="mp-close" onclick="closeMemberPopup()"><i class="fas fa-times"></i></button>
        
        <div class="popup-icon-wrapper">
            <i class="fas fa-laptop-code"></i>
        </div>

        <div class="mp-title">Selamat Mendesain!</div>
        <div class="mp-desc">
            Buat portofolio terbaikmu sekarang. Yuk follow akun resmi kami untuk update fitur terbaru.
        </div>

        <div class="mp-actions">
            <a href="https://instagram.com/hvmdigital.id" target="_blank" class="btn-mp-social btn-ig">
                <i class="fab fa-instagram"></i> Instagram
            </a>
            <a href="https://tiktok.com/@hvmdigital.id" target="_blank" class="btn-mp-social btn-tt">
                <i class="fab fa-tiktok"></i> TikTok
            </a>
        </div>
    </div>
</div>

<script>
    // Fungsi Tampilkan Popup
    function showMemberPopup() {
        document.getElementById('memberPopup').classList.add('active');
    }

    // Fungsi Tutup & Set Timer 30 Detik
    function closeMemberPopup() {
        document.getElementById('memberPopup').classList.remove('active');
        
        // Muncul lagi dalam 30 detik (30.000 ms)
        setTimeout(() => {
            showMemberPopup();
        }, 50000);
    }

    // Trigger saat halaman dashboard dimuat (Delay 1 detik agar smooth)
    window.addEventListener('load', () => {
        setTimeout(() => {
            showMemberPopup();
        }, 2000);
    });
</script>