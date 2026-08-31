<?php
/**
 * HVM STUDIO - DESIGN ENGINE v4.0 (ULTRA LUXURY)
 */
$current_theme = $me['theme'] ?? 'theme1';
$user_role = $me['role'] ?? 'member';
$user_url = "https://bio.hvmdigital.id/" . $me['username'];

$themes = [
    ['id' => 'theme1', 'name' => 'Tema 1 (Starter)', 'type' => 'free', 'color' => '#020b09'],
    ['id' => 'theme2', 'name' => 'Tema 2 (Starter)', 'type' => 'free', 'color' => '#3b82f6'],
    ['id' => 'theme3pro', 'name' => 'Tema 3 Pro', 'type' => 'pro', 'color' => '#a1ff5a'],
    ['id' => 'theme4pro', 'name' => 'Tema 4 Pro', 'type' => 'pro', 'color' => '#ff00ff'],
    ['id' => 'theme5pro', 'name' => 'Tema 5 Pro', 'type' => 'pro', 'color' => '#fda4af'],
    ['id' => 'theme6pro', 'name' => 'Tema 6 Pro', 'type' => 'pro', 'color' => '#111827'],
    ['id' => 'theme7pro', 'name' => 'Tema 7 Pro', 'type' => 'pro', 'color' => '#00f2ff'],
    ['id' => 'theme8pro', 'name' => 'Tema 8 Pro', 'type' => 'pro', 'color' => '#8b5cf6'],
    ['id' => 'theme9pro', 'name' => 'Tema 9 Pro', 'type' => 'pro', 'color' => '#000000'],
    ['id' => 'theme10pro', 'name' => 'Tema 10 Pro', 'type' => 'pro', 'color' => '#a1ff5a'],
];
?>

<style>
    .design-wrapper { display: grid; grid-template-columns: 1fr 360px; gap: 30px; padding-bottom: 120px; }
    
    /* --- THEME CARDS --- */
    .theme-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 20px; }
    
    .theme-card {
        background: #fff; border-radius: 20px; border: 2px solid #f1f5f9;
        cursor: pointer; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative; overflow: hidden; padding: 15px; text-align: center;
    }
    .theme-card:hover { transform: translateY(-5px); border-color: var(--neon); }
    .theme-card.active { border-color: var(--neon); background: #f0fdf4; box-shadow: 0 10px 20px rgba(161, 255, 90, 0.1); }
    
    .theme-color-tag { width: 40px; height: 40px; border-radius: 12px; margin: 0 auto 10px; box-shadow: 0 5px 10px rgba(0,0,0,0.1); }
    .theme-card b { font-size: 11px; font-weight: 800; color: #020b09; text-transform: uppercase; }

    .lock-icon { position: absolute; top: 10px; right: 10px; font-size: 10px; color: #94a3b8; }
    .theme-card.locked { opacity: 0.6; background: #fafafa; }

    /* --- MEDIA SECTION --- */
    .media-card-luxury {
        background: #fff; border-radius: 30px; padding: 30px; border: 1px solid #f1f5f9;
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;
    }
    .upload-box {
        background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 20px;
        padding: 20px; text-align: center; transition: 0.3s;
    }
    .upload-box:hover { border-color: var(--neon); background: #fff; }
    .upload-box i { font-size: 20px; color: #94a3b8; margin-bottom: 10px; }

    /* --- MOCKUP --- */
    .mockup-sticky { position: sticky; top: 100px; z-index: 10; }
    .iphone-frame {
        width: 300px; height: 610px; background: #000; border-radius: 50px;
        border: 9px solid #1a1a1a; box-shadow: 0 40px 80px rgba(0,0,0,0.2);
        position: relative; overflow: hidden; margin: 0 auto;
    }
    .iphone-frame iframe { width: 100%; height: 100%; border: none; background: #fff; transition: 0.5s; }

    /* --- MINIMALIST SAVE BAR --- */
    .floating-action-bar {
        position: fixed; bottom: 25px; left: 50%; transform: translateX(-50%);
        background: #020b09; padding: 10px 10px 10px 25px; border-radius: 100px;
        display: flex; align-items: center; gap: 40px; z-index: 1000;
        box-shadow: 0 20px 40px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1);
    }
    .btn-update {
        background: var(--neon); color: #000; padding: 14px 35px;
        border-radius: 100px; font-weight: 900; font-size: 12px; border: none;
        cursor: pointer; transition: 0.3s; text-transform: uppercase;
    }

    @media (max-width: 992px) {
        .design-wrapper { grid-template-columns: 1fr; }
        .media-card-luxury { grid-template-columns: 1fr; }
        .floating-action-bar { width: 90%; justify-content: space-between; gap: 10px; }
    }
</style>

<form id="formDesign" action="actions/save_settings.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="redirect" value="design">
    <input type="hidden" name="theme" id="val_theme" value="<?= $current_theme ?>">

    <div class="design-wrapper">
        
        <div class="content-left anim-fade-up">
            <div class="page-header">
                <h2>Visual Customizer</h2>
                <p>Personalisasi estetika halaman Anda dengan satu klik.</p>
            </div>

            <!-- THEME SELECTOR -->
            <div class="card-glass">
                <div style="font-weight: 800; font-size: 13px; letter-spacing: 1px;"><i class="fas fa-palette text-neon"></i> KATALOG TEMA</div>
                <div class="theme-grid">
                    <?php foreach($themes as $t): 
                        $is_locked = ($t['type'] == 'pro' && $user_role == 'member');
                    ?>
                    <div class="theme-card <?= $current_theme == $t['id'] ? 'active' : '' ?> <?= $is_locked ? 'locked' : '' ?>" 
                         onclick="applyPreview('<?= $t['id'] ?>', '<?= $t['type'] ?>', this)">
                        <?php if($is_locked): ?><i class="fas fa-lock lock-icon"></i><?php endif; ?>
                        <div class="theme-color-tag" style="background: <?= $t['color'] ?>;"></div>
                        <b><?= $t['name'] ?></b>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- MEDIA SECTION -->
            <div class="media-card-luxury">
                <div class="upload-box">
                    <i class="fas fa-user-circle"></i>
                    <div style="font-size: 10px; font-weight: 800; color: #64748b; margin-bottom: 10px;">FOTO PROFIL</div>
                    <input type="file" name="profile_pic" style="font-size: 10px; width: 100%;">
                </div>
                <div class="upload-box">
                    <i class="fas fa-image"></i>
                    <div style="font-size: 10px; font-weight: 800; color: #64748b; margin-bottom: 10px;">FOTO SAMPUL</div>
                    <input type="file" name="cover_pic" style="font-size: 10px; width: 100%;">
                </div>
            </div>
        </div>

        <!-- MOCKUP AREA -->
        <div class="content-right">
            <div class="mockup-sticky">
                <div class="iphone-frame">
                    <iframe id="previewFrame" src="<?= $user_url ?>?preview=<?= $current_theme ?>"></iframe>
                </div>
            </div>
        </div>

    </div>

    <!-- FLOATING SAVE -->
    <div class="floating-action-bar">
        <div style="color: #fff; font-size: 11px; font-weight: 700; letter-spacing: 1px;">
            <i class="fas fa-circle-check text-neon"></i> DESAIN SIAP?
        </div>
        <button type="button" onclick="confirmSave()" class="btn-update">
            Terapkan Perubahan <i class="fas fa-chevron-right" style="margin-left: 10px;"></i>
        </button>
    </div>
</form>

<script>
function applyPreview(id, type, el) {
    if (type === 'pro' && "<?= $user_role ?>" === 'member') {
        Swal.fire({
            title: 'Fitur Pro Elite',
            text: 'Tema ini hanya tersedia untuk member Premium Pro.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Upgrade Sekarang',
            confirmButtonColor: '#a1ff5a',
            cancelButtonColor: '#000'
        }).then((res) => { if(res.isConfirmed) window.location.href='?view=premium'; });
        return;
    }

    // Update UI
    document.querySelectorAll('.theme-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('val_theme').value = id;

    // Refresh Iframe
    const frame = document.getElementById('previewFrame');
    frame.style.opacity = '0.4';
    frame.src = "<?= $user_url ?>?preview=" + id;
    frame.onload = () => frame.style.opacity = '1';
}

function confirmSave() {
    Swal.fire({
        title: 'Update Live Web?',
        text: 'Tampilan profil publik Anda akan segera berubah.',
        background: 'rgba(255,255,255,0.95)',
        backdrop: `rgba(2, 11, 9, 0.5) blur(8px)`,
        confirmButtonText: 'Ya, Update Sekarang',
        confirmButtonColor: '#a1ff5a',
        showCancelButton: true,
        cancelButtonColor: '#000'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Menyimpan...', didOpen: () => { Swal.showLoading() } });
            document.getElementById('formDesign').submit();
        }
    });
}

// Notif sukses jika ada ?success=1
if (new URLSearchParams(window.location.search).has('success')) {
    Swal.fire({ icon:'success', title:'Berhasil Terpasang!', background:'rgba(255,255,255,0.95)', backdrop: 'rgba(2, 11, 9, 0.5) blur(8px)' });
}
</script>