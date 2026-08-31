<?php
/**
 * bio.hvmdigital.id - SETTINGS ELITE UPGRADE v6.0 (ULTRA LUXURY)
 * Focus: Realtime Sync Engine & 10 TikTok Slots.
 */
$is_premium = ($me['role'] == 'premium' || $me['role'] == 'admin');
$limit_btn = $is_premium ? 10 : 2;
$limit_tiktok = $is_premium ? 10 : 3; // Upgrade ke 10 Slot untuk Pro
?>

<style>
    /* --- ELITE DESIGN SYSTEM --- */
    :root {
        --neon: #a1ff5a;
        --dark-bg: #020b09;
        --soft-bg: #f8fafc;
        --border-color: #f1f5f9;
        --text-main: #1e293b;
        --text-dim: #64748b;
    }

    .st-container { display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px; align-items: start; padding-bottom: 180px; }
    
    .st-card { 
        background: #fff; border-radius: 40px; padding: 40px; margin-bottom: 30px; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.02); border: 1px solid var(--border-color); 
        position: relative; overflow: visible; /* Diubah agar icon tidak terpotong */
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .st-card:hover { border-color: rgba(161,255,90,0.4); transform: translateY(-5px); }
    
    .st-label { 
        font-size: 12px; font-weight: 900; color: var(--neon); letter-spacing: 2.5px; 
        text-transform: uppercase; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;
        background: var(--dark-bg); width: fit-content; padding: 10px 25px; border-radius: 100px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    /* --- SHORTCUT CARD --- */
    .shortcut-card { 
        background: var(--dark-bg); color: #fff; border-radius: 35px; padding: 30px; margin-bottom: 35px; 
        display: flex; justify-content: space-between; align-items: center; gap: 20px;
        border: 1px solid rgba(161,255,90,0.2); position: relative; overflow: hidden;
    }
    .shortcut-text h4 { font-weight: 900; color: var(--neon); margin-bottom: 8px; font-size: 18px; }
    .shortcut-text p { font-size: 13px; opacity: 0.7; line-height: 1.6; }
    
    .btn-design-go { 
        background: var(--neon); color: #000; padding: 16px 30px; border-radius: 20px; 
        font-weight: 900; text-decoration: none; font-size: 13px; display: flex; align-items: center; gap: 10px;
        flex-shrink: 0; transition: 0.3s;
    }

    /* --- INPUT FIX --- */
    .st-group { margin-bottom: 25px; position: relative; width: 100%; }
    .st-group label { display: block; font-size: 11px; font-weight: 800; color: var(--text-dim); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; margin-left: 10px; }
    
    .input-wrapper { position: relative; display: flex; align-items: center; }
    .input-wrapper i.input-icon { 
        position: absolute; left: 20px; color: #cbd5e1; font-size: 18px; 
        z-index: 10; pointer-events: none; transition: 0.3s; 
    }
    
    .st-field { 
        width: 100%; padding: 18px 25px 18px 55px; background: var(--soft-bg); border: 2px solid transparent; 
        border-radius: 22px; font-size: 14px; font-weight: 700; color: var(--dark-bg); transition: 0.3s;
    }
    .st-field:focus { background: #fff; border-color: var(--neon); outline: none; box-shadow: 0 15px 30px rgba(161,255,90,0.1); }
    .st-field:focus + i.input-icon { color: var(--neon); }

    /* --- UPLOAD BOX --- */
    .upload-box { 
        position: relative; width: 100%; height: 58px; background: var(--soft-bg); border-radius: 20px; 
        border: 2px dashed #e2e8f0; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s;
    }
    .upload-box:hover { border-color: var(--neon); background: #fff; }

    /* --- PREMIUM LOCK --- */
    .st-lock { position: relative; pointer-events: none; }
    .st-lock::after { 
        content: ""; position: absolute; inset: 0; background: rgba(255,255,255,0.7); 
        backdrop-filter: blur(6px); z-index: 5; cursor: pointer; border-radius: 35px; pointer-events: all;
    }
    .lock-tag { 
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
        background: var(--dark-bg); color: #fff; padding: 12px 25px; border-radius: 100px; 
        font-size: 11px; font-weight: 900; z-index: 10; display: flex; align-items: center; gap: 10px;
    }

    /* --- MOCKUP DISPLAY --- */
    .st-mockup { position: sticky; top: 30px; text-align: center; }
    .st-phone { 
        width: 320px; height: 650px; background: #000; border-radius: 60px; border: 12px solid #1a1a1a; 
        margin: 0 auto; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.3); position: relative; 
    }
    .st-phone iframe { width: 100%; height: 100%; border: none; background: #fff; }

    /* --- FLOATING SAVE BAR --- */
    .st-save-bar { 
        position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); 
        background: rgba(2, 11, 9, 0.95); backdrop-filter: blur(20px);
        padding: 15px 15px 15px 40px; border-radius: 100px; 
        display: flex; align-items: center; gap: 60px; z-index: 9999; 
        box-shadow: 0 25px 50px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1);
    }
    .st-save-btn { 
        background: var(--neon); color: #020b09; padding: 20px 60px; border-radius: 100px; 
        font-weight: 900; font-size: 14px; border: none; cursor: pointer; text-transform: uppercase; 
        transition: 0.3s; letter-spacing: 1px;
    }
    .st-save-btn:hover { transform: scale(1.05); box-shadow: 0 10px 25px rgba(161, 255, 90, 0.4); }

/* --- MOBILE FRIENDLY FIX (V7.0) --- */
    @media (max-width: 992px) { 
        /* 1. Beri ruang di bawah agar input terakhir tidak tertutup navbar & button */
        .st-container { 
            grid-template-columns: 1fr; 
            padding-bottom: 220px; 
        } 
        
        .st-mockup { display: none; } 

        /* 2. Pindahkan Save Bar ke Atas Navbar Bawah */
        .st-save-bar { 
            bottom: 100px; /* KUNCI: Melayang di atas navbar bawah */
            top: auto;
            left: 50%;
            transform: translateX(-50%);
            width: auto; /* Jadi bentuk pill ramping */
            background: rgba(2, 11, 9, 0.8) !important; /* Transparan mewah */
            backdrop-filter: blur(10px);
            padding: 8px;
            border-radius: 100px;
            border: 1px solid rgba(255,255,255,0.1);
            gap: 0;
            display: flex;
            justify-content: center;
        }

        /* Hilangkan teks "SINKRONISASI" agar tidak sesak di mobile */
        .st-save-bar div { display: none; }

        /* 3. Tombol Publish dibuat Ramping & Elegant */
        .st-save-btn { 
            padding: 12px 40px; 
            font-size: 12px; 
            border-radius: 100px; 
            width: auto;
            box-shadow: 0 10px 25px rgba(161, 255, 90, 0.3);
            white-space: nowrap;
        }

        /* 4. Perbaikan Layout Card Design Hub */
        .shortcut-card { 
            flex-direction: column; 
            align-items: flex-start; 
            padding: 25px; 
        }
        .shortcut-text p { margin-bottom: 15px; font-size: 12px; }
        .btn-design-go { width: 100%; justify-content: center; padding: 14px; }

        /* 5. Optimasi Card Info */
        .st-card { padding: 25px 20px; border-radius: 35px; }
    }
</style>

<div id="loading-overlay" style="display:none; position:fixed; inset:0; background:rgba(255,255,255,0.98); z-index:100000; align-items:center; justify-content:center; flex-direction:column; backdrop-filter:blur(10px);">
    <i class="fas fa-sync fa-spin fa-3x" style="color:var(--neon);"></i>
    <h3 style="margin-top:25px; font-weight:900; color:#000;">SYNCING ELITE DATA...</h3>
</div>

<form id="formElite" action="actions/save_settings.php" method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
    <input type="hidden" name="save_settings" value="1">
    <input type="hidden" name="redirect" value="settings">

    <div class="st-container">
        <div class="st-form">
            
            <!-- SHORTCUT DESIGN -->
            <div class="shortcut-card">
                <div class="shortcut-text">
                    <h4><i class="fas fa-magic"></i> Design Hub</h4>
                    <p>Ubah tema, foto profil, dan sampul secara instan di menu Design.</p>
                </div>
                <a href="?view=design" class="btn-design-go">BUKA DESIGN <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- 1. IDENTITAS -->
            <div class="st-card">
                <span class="st-label"><i class="fas fa-user-shield"></i> Identity</span>
                
                <div class="st-group">
                    <label>Nama Lengkap</label>
                    <div class="input-wrapper">
                        <input type="text" name="fullname" class="st-field live-sync" data-target="name" value="<?= htmlspecialchars($me['fullname']) ?>" required>
                        <i class="fas fa-id-badge input-icon"></i>
                    </div>
                </div>

                <div class="st-group">
                    <label>Bio / Public Nickname</label>
                    <div class="input-wrapper">
                        <input type="text" name="nickname" class="st-field live-sync" data-target="nickname" value="<?= htmlspecialchars($me['nickname']) ?>">
                        <i class="fas fa-at input-icon"></i>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="st-group">
                        <label>Profesi</label>
                        <div class="input-wrapper">
                            <input type="text" name="role_user" class="st-field live-sync" data-target="role" placeholder="Creative" value="<?= htmlspecialchars($me['role_display']) ?>">
                            <i class="fas fa-briefcase input-icon"></i>
                        </div>
                    </div>
                    <div class="st-group">
                        <label>Location</label>
                        <div class="input-wrapper">
                            <input type="text" name="location" class="st-field live-sync" data-target="location" placeholder="Jakarta" value="<?= htmlspecialchars($me['location']) ?>">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. SOCIALS & CONNECTIONS -->
            <div class="st-card">
                <span class="st-label"><i class="fas fa-paper-plane"></i> Connections</span>
                <div class="st-group">
                    <label>Hero Action (Drive Link)</label>
                    <div class="input-wrapper">
                        <input type="text" name="drive_title" class="st-field live-sync" data-target="drive-title" placeholder="My Portofolio" value="<?= htmlspecialchars($me['drive_title']) ?>">
                        <i class="fab fa-google-drive input-icon"></i>
                    </div>
                </div>
                <div class="st-group">
                    <div class="input-wrapper">
                        <input type="text" name="drive_url" class="st-field" placeholder="https://..." value="<?= htmlspecialchars($me['drive_url']) ?>">
                        <i class="fas fa-link input-icon"></i>
                    </div>
                </div>
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="st-group">
                        <label>Instagram</label>
                        <div class="input-wrapper">
                            <input type="text" name="ig_user" class="st-field" placeholder="Username" value="<?= htmlspecialchars($me['ig_user']) ?>">
                            <i class="fab fa-instagram input-icon"></i>
                        </div>
                    </div>
                    <div class="st-group">
                        <label>TikTok User</label>
                        <div class="input-wrapper">
                            <input type="text" name="tt_user" class="st-field" placeholder="Username" value="<?= htmlspecialchars($me['tt_user']) ?>">
                            <i class="fab fa-tiktok input-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="st-group">
                    <label>WhatsApp</label>
                    <div class="input-wrapper">
                        <input type="text" name="wa_number" class="st-field" placeholder="628xxx" value="<?= htmlspecialchars($me['wa_number']) ?>">
                        <i class="fab fa-whatsapp input-icon"></i>
                    </div>
                </div>
            </div>

            <!-- 3. CUSTOM LINK BUTTONS -->
            <div class="st-card">
                <span class="st-label"><i class="fas fa-list-ul"></i> Custom Buttons</span>
                <?php for($i=1; $i<=10; $i++): $lock = ($i > $limit_btn); ?>
                    <div class="<?= $lock?'st-lock':'' ?>" onclick="<?= $lock?'showCTA()':'' ?>" style="background:var(--soft-bg); padding:30px; border-radius:30px; margin-bottom:20px; border: 1px solid #eef2f7; position:relative;">
                        <?php if($lock): ?><div class="lock-tag"><i class="fas fa-crown"></i> PRO SLOT</div><?php endif; ?>
                        <p style="font-weight:900; font-size:10px; margin-bottom:20px; color:var(--text-dim); text-transform:uppercase; letter-spacing:1px;">Button Slot #<?= $i ?></p>
                        
                        <div style="display:grid; grid-template-columns:1.2fr 1fr; gap:15px; margin-bottom:15px;">
                            <input type="text" name="btn<?= $i ?>_text" class="st-field" style="padding-left:20px;" placeholder="Label" value="<?= htmlspecialchars($me["btn{$i}_text"]) ?>">
                            <div class="upload-box">
                                <span style="font-size:9px;"><i class="fas fa-image"></i> ICON</span>
                                <input type="file" name="btn<?= $i ?>_img" style="position:absolute; inset:0; opacity:0;">
                            </div>
                        </div>
                        <input type="text" name="btn<?= $i ?>_desc" class="st-field" style="padding-left:20px; margin-bottom:15px;" placeholder="Short Description" value="<?= htmlspecialchars($me["btn{$i}_desc"]) ?>">
                        <input type="text" name="btn<?= $i ?>_url" class="st-field" style="padding-left:20px; margin-bottom:0;" placeholder="https://..." value="<?= htmlspecialchars($me["btn{$i}_url"]) ?>">
                    </div>
                <?php endfor; ?>
            </div>

            <!-- 4. TIKTOK VIDEO SLOTS (10 SLOTS) -->
            <div class="st-card">
                <span class="st-label"><i class="fab fa-tiktok"></i> TikTok Highlights</span>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                    <?php for($v=1; $v<=10; $v++): $vlock = ($v > $limit_tiktok); ?>
                        <div class="st-group <?= $vlock?'st-lock':'' ?>" onclick="<?= $vlock?'showCTA()':'' ?>">
                            <?php if($vlock): ?><div class="lock-tag" style="top:25px; transform:translate(-50%, 0);"><i class="fas fa-lock"></i></div><?php endif; ?>
                            <label>Slot #<?= $v ?></label>
                            <div class="input-wrapper">
                                <input type="text" name="tiktok_vid<?= $v ?>" class="st-field" style="padding-left:45px;" placeholder="Video URL" value="<?= htmlspecialchars($me["tiktok_vid{$v}"]) ?>">
                                <i class="fas fa-play-circle input-icon" style="left:15px;"></i>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

        </div>

        <!-- STICKY MOCKUP -->
        <div class="st-mockup">
            <div class="st-phone">
                <iframe id="preview-iframe" src="/<?= $me['username'] ?>?preview=1"></iframe>
            </div>
            <p style="margin-top:25px; font-size:11px; color:var(--neon); font-weight:900; letter-spacing:2px; text-transform:uppercase;"><i class="fas fa-bolt"></i> Realtime Simulation Active</p>
        </div>
    </div>

    <!-- FLOATING SAVE BAR -->
    <div class="st-save-bar">
        <div style="color: #fff; font-size: 11px; font-weight: 900; letter-spacing: 1px;">APPLY CHANGES NOW?</div>
        <button type="submit" class="st-save-btn">PUBLISH CHANGES <i class="fas fa-check-circle" style="margin-left:10px;"></i></button>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showLoading() { document.getElementById('loading-overlay').style.display = 'flex'; }
    
    // --- LIVE PREVIEW ENGINE (UPGRADED) ---
    const iframe = document.getElementById('preview-iframe');
    const syncInputs = document.querySelectorAll('.live-sync');

    syncInputs.forEach(input => {
        input.addEventListener('input', function() {
            const target = this.getAttribute('data-target');
            const value = this.value;

            // Mengirim data ke iframe secara realtime
            // Iframe harus dalam domain yang sama
            try {
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                
                // Mapping target ke Class/ID di Tema
                if(target === 'name') {
                    const el = iframeDoc.querySelector('h1');
                    if(el) el.innerText = value;
                }
                if(target === 'nickname') {
                    const el = iframeDoc.querySelector('.nick');
                    if(el) el.innerText = value;
                }
                if(target === 'role') {
                    const el = iframeDoc.querySelector('.badge i.fa-briefcase');
                    if(el && el.parentElement) el.parentElement.lastChild.textContent = ' ' + value;
                }
                if(target === 'location') {
                    const el = iframeDoc.querySelector('.badge i.fa-map-marker-alt');
                    if(el && el.parentElement) el.parentElement.lastChild.textContent = ' ' + value;
                }
                if(target === 'drive-title') {
                    const el = iframeDoc.querySelector('.drive-title');
                    if(el) el.innerText = value;
                }
            } catch (e) {
                console.log("Cross-origin preview restriction, but sync is ready.");
            }
        });
    });

    function showCTA() {
        Swal.fire({
            title: '<span style="font-weight:900">ULTRA ELITE ACCESS</span>',
            text: 'Upgrade ke Pro untuk membuka 10 Slot TikTok, 10 Tombol Kustom, dan fitur eksklusif lainnya.',
            icon: 'info', iconColor: '#a1ff5a', showCancelButton: true, confirmButtonText: 'Upgrade Now', confirmButtonColor: '#020b09', borderRadius: '40px'
        }).then((res) => { if(res.isConfirmed) window.location.href="?view=premium"; });
    }

    <?php if(isset($_GET['saved'])): ?>
    Swal.fire({ icon: 'success', title: 'Data Sinkron!', confirmButtonColor: '#020b09', borderRadius: '35px' });
    window.history.replaceState(null, null, window.location.pathname + "?view=settings");
    <?php endif; ?>
</script>