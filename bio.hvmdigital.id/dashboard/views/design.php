<?php
/**
 * ==============================================================================
 * HVM STUDIO - DESIGN ENGINE v7.0 (ULTRA ELITE & COMPACT LUXURY)
 * ==============================================================================
 * Path Connection : public_html/uploads/
 * Logic           : Compact UI, WebP Processor, Pro Role Gating
 * Mobile          : Version 7.0 Optimized (Floating Pill)
 * Status          : 100% Production Ready
 * ==============================================================================
 */

// --- 1. CORE DATA INITIALIZATION ---
$current_theme = $me['theme'] ?? 'theme1';
$user_role     = $me['role']  ?? 'member'; 
$username      = $me['username'] ?? 'default';
$user_url      = "https://bio.hvmdigital.id/" . $username;

// Jalur Gambar Aset (Sinkronisasi Root /uploads/)
$profile_pic_path = !empty($me['profile_pic']) ? "/uploads/" . $me['profile_pic'] : "/uploads/defaultprofile.png";
$cover_pic_path   = !empty($me['cover_pic'])   ? "/uploads/" . $me['cover_pic']   : "/uploads/defaultsampul.png";

/**
 * --- 2. INTELLIGENT THEME REGISTRY ---
 */
$themes = [
    ['id' => 'theme1', 'name' => 'Onyx Dark', 'type' => 'free', 'color' => '#020b09', 'desc' => 'Elegan & Profesional', 'status' => 'published', 'icon' => 'fa-moon'],
    ['id' => 'theme2', 'name' => 'Azure Sky', 'type' => 'free', 'color' => '#3b82f6', 'desc' => 'Modern & Terpercaya', 'status' => 'published', 'icon' => 'fa-cloud-sun'],
    ['id' => 'theme3pro', 'name' => 'Neon Elite', 'type' => 'pro', 'color' => '#a1ff5a', 'desc' => 'Vibrant & Eksklusif', 'status' => 'published', 'icon' => 'fa-bolt'],
    ['id' => 'theme4pro', 'name' => 'Blue glasses', 'type' => 'pro', 'color' => '#8b5cf6', 'desc' => 'Blue Obsidian', 'status' => 'published', 'icon' => 'fa-gem'],
    ['id' => 'soon2', 'name' => 'Gold Obsidian', 'type' => 'pro', 'color' => '#eab308', 'desc' => 'Golden Premium', 'status' => 'draft', 'icon' => 'fa-crown'],
    ['id' => 'soon3', 'name' => 'Cyber V', 'type' => 'pro', 'color' => '#8b5cf6', 'desc' => 'Futuristic Purple', 'status' => 'draft', 'icon' => 'fa-robot'],
];
?>

<!-- --- ELITE DESIGN SYSTEM (COMPACT & LUXURY) --- -->
<style>
    :root { 
        --neon: #a1ff5a; 
        --dark-bg: #020b09; 
        --soft-bg: #f8fafc;
        --border-color: #f1f5f9;
        --gray-text: #94a3b8; 
        --white: #ffffff;
        --shadow-lux: 0 20px 40px rgba(0,0,0,0.05);
        --trans-lux: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    }

    /* Layout Optimization for 100% Laptop View */
    .design-wrapper { 
        display: grid; 
        grid-template-columns: 1fr 340px; 
        gap: 25px; 
        align-items: start; 
        padding-bottom: 150px; 
        max-width: 1400px;
        margin: 0 auto;
    }

    /* --- 1. COMPACT SHORTCUT CARD --- */
    .shortcut-elite-card { 
        background: var(--dark-bg); 
        color: var(--white); 
        border-radius: 30px; 
        padding: 30px; 
        margin-bottom: 25px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        gap: 20px;
        border: 1px solid rgba(161,255,90,0.2); 
        position: relative; 
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }
    .shortcut-elite-card::after {
        content: ''; position: absolute; top: -50%; right: -5%; width: 180px; height: 180px;
        background: var(--neon); filter: blur(80px); opacity: 0.12; pointer-events: none;
    }
    .shortcut-text-box h4 { font-weight: 900; color: var(--neon); margin-bottom: 5px; font-size: 18px; letter-spacing: -0.5px; }
    .shortcut-text-box p { font-size: 12px; opacity: 0.7; line-height: 1.6; max-width: 400px; }
    .shortcut-icon-lux { width: 55px; height: 55px; background: rgba(255,255,255,0.05); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--neon); flex-shrink: 0; border: 1px solid rgba(255,255,255,0.08); }

    /* --- 2. CONTROL CARDS (SCALED DOWN) --- */
    .st-card-elite { 
        background: var(--white); 
        border-radius: 35px; 
        padding: 35px; 
        margin-bottom: 25px; 
        box-shadow: var(--shadow-lux); 
        border: 1px solid var(--border-color); 
    }
    .st-label-elite { 
        font-size: 10px; font-weight: 900; color: var(--neon); letter-spacing: 3px; 
        text-transform: uppercase; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;
        background: var(--dark-bg); width: fit-content; padding: 10px 20px; border-radius: 100px;
    }

    /* --- 3. PREMIUM THEME GRID --- */
    .theme-engine-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(145px, 1fr)); gap: 15px; }
    
    .theme-item-lux {
        background: var(--white); border-radius: 25px; border: 2px solid var(--border-color);
        cursor: pointer; transition: var(--trans-lux);
        position: relative; overflow: hidden; padding: 25px 15px; text-align: center;
    }
    .theme-item-lux:hover { transform: translateY(-8px); border-color: var(--neon); box-shadow: 0 15px 30px rgba(161, 255, 90, 0.15); }
    .theme-item-lux.active { border-color: var(--neon); background: #f0fdf4; box-shadow: inset 0 0 0 1px var(--neon); }
    
    .theme-swatch-elite { width: 45px; height: 45px; border-radius: 15px; margin: 0 auto 15px; border: 4px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: 0.4s; }
    .theme-item-lux.active .theme-swatch-elite { transform: scale(1.1) rotate(10deg); }
    
    .theme-item-lux b { font-size: 11px; font-weight: 900; color: var(--dark-bg); display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
    .theme-item-lux span { font-size: 9px; color: var(--gray-text); font-weight: 700; display: block; opacity: 0.8; }
    .theme-item-lux i.theme-icon-status { position: absolute; top: 12px; left: 12px; font-size: 10px; color: var(--gray-text); opacity: 0.3; }

    .pro-lock-pill { position: absolute; top: 10px; right: 10px; background: rgba(2, 11, 9, 0.05); color: var(--dark-bg); padding: 4px 8px; border-radius: 8px; font-size: 8px; font-weight: 900; }

    /* Theme Draft State */
    .theme-item-lux.soon { opacity: 0.4; cursor: not-allowed; border-style: dashed; filter: grayscale(1); }
    .soon-label { position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%); font-size: 7px; font-weight: 900; background: var(--dark-bg); color: #fff; padding: 3px 8px; border-radius: 100px; }

    /* --- 4. MEDIA ENGINE (COMPACT) --- */
    .media-engine-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .upload-elite-box {
        background: var(--white); border: 2px dashed #e2e8f0; border-radius: 30px; padding: 35px 20px; 
        text-align: center; transition: var(--trans-lux); cursor: pointer; position: relative; overflow: hidden;
    }
    .upload-elite-box:hover { border-color: var(--neon); background: var(--soft-bg); transform: scale(1.02); }
    
    .visual-elite-prev { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.1; z-index: 1; transition: 0.5s; }
    .upload-elite-box:hover .visual-elite-prev { opacity: 0.3; transform: scale(1.1); }

    .upload-elite-content { position: relative; z-index: 5; }
    .upload-elite-content i { font-size: 26px; color: var(--dark-bg); margin-bottom: 12px; display: block; opacity: 0.7; }
    .upload-elite-content b { display: block; font-size: 11px; font-weight: 900; color: var(--dark-bg); letter-spacing: 1.5px; }
    .upload-elite-content span { font-size: 8px; color: var(--gray-text); font-weight: 800; text-transform: uppercase; margin-top: 5px; display: inline-block; background: rgba(0,0,0,0.03); padding: 2px 8px; border-radius: 100px; }

    /* --- 5. STICKY MOCKUP (RESIZED) --- */
    .mockup-compact-zone { position: sticky; top: 30px; text-align: center; }
    .iphone-v7-frame {
        width: 290px; height: 580px; background: #000; border-radius: 55px; border: 12px solid #1a1a1a; 
        box-shadow: 0 40px 80px rgba(0,0,0,0.25); position: relative; overflow: hidden; margin: 0 auto;
    }
    .iphone-v7-frame::before { 
        content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%);
        width: 120px; height: 26px; background: #1a1a1a; border-bottom-left-radius: 18px;
        border-bottom-right-radius: 18px; z-index: 100;
    }
    .iphone-v7-frame iframe { width: 100%; height: 100%; border: none; background: #fff; }

    .mockup-status-elite {
        margin-top: 20px; display: inline-flex; align-items: center; gap: 8px;
        background: var(--dark-bg); color: #fff; padding: 8px 20px; border-radius: 50px;
        font-size: 10px; font-weight: 900; letter-spacing: 1.5px;
        border: 1px solid rgba(161,255,90,0.25);
    }

    /* --- 6. ACTION BAR (MOBILE V7.0 READY) --- */
    .floating-action-pill {
        position: fixed; bottom: 35px; left: 50%; transform: translateX(-50%);
        background: rgba(2, 11, 9, 0.92); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
        padding: 10px 10px 10px 35px; border-radius: 100px;
        display: flex; align-items: center; gap: 60px; z-index: 10005;
        box-shadow: 0 35px 70px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1);
    }
    .publish-label-lux { color: #fff; font-size: 11px; font-weight: 900; letter-spacing: 2px; }
    .btn-lux-save {
        background: var(--neon); color: #020b09; padding: 18px 60px; border-radius: 100px; 
        font-weight: 900; font-size: 13px; border: none; cursor: pointer; text-transform: uppercase; 
        transition: var(--trans-lux); letter-spacing: 1px; box-shadow: 0 10px 25px rgba(161, 255, 90, 0.3);
    }
    .btn-lux-save:hover { transform: scale(1.05) translateY(-3px); background: var(--white); }

    /* --- 7. MOBILE RESPONSIVE --- */
    @media (max-width: 992px) {
        .design-wrapper { grid-template-columns: 1fr; padding-bottom: 250px; }
        .mockup-compact-zone { display: none; }
        .theme-engine-grid { grid-template-columns: repeat(2, 1fr); }
        .media-engine-row { grid-template-columns: 1fr; }
        .floating-action-pill { bottom: 110px; width: 90%; padding: 8px; justify-content: center; }
        .publish-label-lux { display: none; }
        .btn-lux-save { width: 100%; padding: 18px 0; font-size: 12px; }
    }

    /* Premium Syncing Overlay */
    #hvm-sync-engine { display: none; position: fixed; inset: 0; background: rgba(2, 11, 9, 0.98); z-index: 100010; align-items: center; justify-content: center; flex-direction: column; color: #fff; }
</style>

<!-- SYNCING HUB -->
<div id="hvm-sync-engine">
    <i class="fas fa-snowflake fa-spin fa-4x" style="color:var(--neon); margin-bottom: 25px;"></i>
    <h2 style="font-weight: 900; letter-spacing: 6px; text-transform: uppercase;">Syncing Hub</h2>
    <p style="opacity: 0.5; font-weight: 700; font-size: 12px; margin-top: 10px;">Purging cache & optimizing branding assets...</p>
</div>

<!-- DESIGN ENGINE FORM -->
<form id="formDesignHub" action="actions/save_settings.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="redirect" value="design">
    <input type="hidden" name="theme" id="val_theme" value="<?= $current_theme ?>">

    <div class="design-wrapper">
        <div class="content-left">
            
            <!-- SECTION 1: COMPACT HUB INFO -->
            <div class="shortcut-elite-card">
                <div class="shortcut-text-box">
                    <h4><i class="fas fa-wand-magic-sparkles"></i> Visual Customizer</h4>
                    <p>Konfigurasikan identitas visual profil Anda. Gunakan aset beresolusi tinggi; sistem kami akan mengoptimasi ukurannya secara otomatis untuk performa terbaik.</p>
                </div>
                <div class="shortcut-icon-lux"><i class="fas fa-palette"></i></div>
            </div>

            <!-- SECTION 2: COMPACT THEME SELECTOR -->
            <div class="st-card-elite">
                <div class="st-label-elite">
                    <i class="fas fa-layer-group"></i> Elite Collection
                </div>
                <div class="theme-engine-grid">
                    <?php foreach($themes as $t): 
                        $is_locked = ($t['type'] == 'pro' && $user_role == 'member');
                        $is_draft  = ($t['status'] == 'draft');
                    ?>
                    <div class="theme-item-lux <?= ($current_theme == $t['id']) ? 'active' : '' ?> <?= $is_draft ? 'soon' : '' ?>" 
                         onclick="<?= $is_draft ? '' : "handleVisualEngine('".$t['id']."', '".$t['type']."', this)" ?>">
                        
                        <i class="fas <?= $t['icon'] ?> theme-icon-status"></i>

                        <?php if($t['type'] == 'pro' && !$is_draft): ?>
                            <div class="pro-lock-pill"><i class="fas fa-crown"></i> PRO</div>
                        <?php endif; ?>

                        <?php if($is_draft): ?>
                            <div class="soon-label">UPCOMING</div>
                        <?php endif; ?>

                        <div class="theme-swatch-elite" style="background: <?= $t['color'] ?>;"></div>
                        <b><?= $t['name'] ?></b>
                        <span><?= $t['desc'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- SECTION 3: COMPACT MEDIA ENGINE -->
            <div class="media-engine-row">
                <!-- Avatar Engine -->
                <div class="upload-elite-box" onclick="document.getElementById('input_profile').click()">
                    <img src="<?= $profile_pic_path ?>" id="ui_prev_profile" class="visual-elite-prev">
                    <div class="upload-elite-content">
                        <i class="fas fa-user-shield"></i>
                        <b>PROFILE PHOTO</b>
                        <span>Auto WebP Active</span>
                    </div>
                    <input type="file" id="input_profile" name="profile_pic" hidden onchange="runSmartPreview(this, 'ui_prev_profile')">
                </div>
                
                <!-- Cover Engine -->
                <div class="upload-elite-box" onclick="document.getElementById('input_cover').click()">
                    <img src="<?= $cover_pic_path ?>" id="ui_prev_cover" class="visual-elite-prev">
                    <div class="upload-elite-content">
                        <i class="fas fa-camera-retro"></i>
                        <b>BRAND COVER</b>
                        <span>Optimal 16:9</span>
                    </div>
                    <input type="file" id="input_cover" name="cover_pic" hidden onchange="runSmartPreview(this, 'ui_prev_cover')">
                </div>
            </div>

            <div style="margin-top: 40px; text-align: center; opacity: 0.3;">
                <p style="font-size: 10px; font-weight: 900; letter-spacing: 3px; color: var(--gray-text); text-transform: uppercase;">
                    <i class="fas fa-fingerprint"></i> SECURED BY HVM DIGITAL V7.0
                </p>
            </div>
        </div>

        <!-- SECTION 4: STICKY ELITE MOCKUP -->
        <div class="content-right">
            <div class="mockup-compact-zone">
                <div class="iphone-v7-frame">
                    <iframe id="simulationIframe" src="<?= $user_url ?>?preview=<?= $current_theme ?>"></iframe>
                </div>
                
                <div class="mockup-status-elite">
                    <i class="fas fa-bolt-lightning fa-beat-fade" style="color:var(--neon);"></i> 
                    SIMULATION READY
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 5: ACTION PILL (MOBILE OPTIMIZED) -->
    <div class="floating-action-pill">
        <div class="publish-label-lux">APPLY CHANGES NOW?</div>
        <button type="button" onclick="launchSyncSequence()" class="btn-lux-save">
            Publish design <i class="fas fa-check-circle" style="margin-left:10px;"></i>
        </button>
    </div>
</form>

<!-- SCRIPTS INTELLIGENT ENGINE -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/**
 * Smart Preview with WebP Optimization Simulation
 */
function runSmartPreview(input, targetId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Luxury Processing Toast
        Swal.fire({
            toast: true, position: 'top-end', icon: 'info',
            title: 'Optimizing branding asset...', showConfirmButton: false, timer: 1200,
            background: '#020b09', color: '#fff', iconColor: '#a1ff5a'
        });

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById(targetId);
            previewImg.src = e.target.result;
            previewImg.style.opacity = "0.7"; 
        }
        reader.readAsDataURL(file);
    }
}

/**
 * Visual Engine Simulator
 */
function handleVisualEngine(id, type, element) {
    const frame = document.getElementById('simulationIframe');
    
    // Smooth transition simulation
    frame.style.opacity = '0.2'; 
    setTimeout(() => {
        frame.src = "<?= $user_url ?>?preview=" + id;
        frame.onload = () => frame.style.opacity = '1';
    }, 300);

    // Proteksi Member (Pro Gating)
    if (type === 'pro' && "<?= $user_role ?>" === 'member') {
        Swal.fire({
            title: '<span style="font-weight:900">PRO ELITE ACCESS</span>',
            html: '<p style="color:#64748b; font-size:14px; line-height:1.6;">Identitas premium berhasil disimulasikan! Namun untuk mempublikasikannya, Anda memerlukan akses Pro Elite.</p>',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Upgrade Now',
            confirmButtonColor: '#020b09',
            cancelButtonColor: '#f1f5f9',
            background: '#ffffff',
            borderRadius: '40px',
            backdrop: `rgba(2, 11, 9, 0.85) blur(15px)`
        }).then((res) => { 
            if(res.isConfirmed) window.location.href='?view=premium'; 
        });
        
        document.querySelectorAll('.theme-item-lux').forEach(c => c.classList.remove('active'));
        return; 
    }

    // Update State UI
    document.querySelectorAll('.theme-item-lux').forEach(c => c.classList.remove('active'));
    element.classList.add('active');
    
    // Push ID to hidden field
    document.getElementById('val_theme').value = id;
}

/**
 * Launch Global Sync Sequence
 */
function launchSyncSequence() {
    Swal.fire({
        title: '<span style="font-weight:900">PUBLISH DESIGN?</span>',
        text: 'Aset lama akan dipurge dan diganti dengan konfigurasi visual baru Anda secara global.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Publikasikan!',
        confirmButtonColor: '#a1ff5a',
        cancelButtonColor: '#020b09',
        background: '#ffffff',
        backdrop: `rgba(2, 11, 9, 0.85) blur(15px)`,
        borderRadius: '50px'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('hvm-sync-engine').style.display = 'flex';
            document.getElementById('formDesignHub').submit();
        }
    });
}

// Handler Success State
if (new URLSearchParams(window.location.search).has('success')) {
    Swal.fire({ 
        icon: 'success', 
        title: '<span style="font-weight:900">SYNC SUCCESSFUL</span>', 
        text: 'Visual identitas Anda telah berhasil dioptimasi & dipublikasikan.', 
        confirmButtonColor: '#a1ff5a',
        borderRadius: '40px'
    });
    window.history.replaceState(null, null, window.location.pathname + "?view=design");
}
</script>