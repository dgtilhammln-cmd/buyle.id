<?php
// Insert color picker + hero sections into bio index.blade.php

$file = __DIR__ . '/resources/views/creator/bio/index.blade.php';
$content = file_get_contents($file);

// Find the insertion point: after closing </div> of tab-theme but before Tab 2 comment
$search = "        </div>\r\n\r\n        {{-- ══ TAB 2: PROFIL ══ --}}";
if (strpos($content, $search) === false) {
    // Try LF version
    $search = "        </div>\n\n        {{-- ══ TAB 2: PROFIL ══ --}}";
}

if (strpos($content, $search) === false) {
    echo "ERROR: Could not find insertion point!\n";
    // Show surrounding content
    $pos = strpos($content, 'TAB 2: PROFIL');
    echo "Context: " . substr($content, $pos - 100, 200) . "\n";
    exit(1);
}

$insertion = <<<'BLADE'

        {{-- Custom Color Customizer --}}
        <div class="prof-card" style="margin-top:0;">
            <div class="prof-card-head">
                <span>Kustomisasi Warna</span>
                <span style="font-size:0.72rem; font-weight:600; color:#64748b;">Override warna tema</span>
            </div>
            <div class="card-body">
                <form action="{{ route('creator.bio.save-profile') }}" method="POST" id="colorForm">
                    @csrf
                    <input type="hidden" name="bio_name" value="{{ $cfg['name'] ?? '' }}">
                    <input type="hidden" name="bio_bio" value="{{ $cfg['bio'] ?? '' }}">
                    <input type="hidden" name="bio_location" value="{{ $cfg['location'] ?? '' }}">
                    <input type="hidden" name="bio_wa" value="{{ $cfg['wa'] ?? '' }}">
                    <input type="hidden" name="bio_ig" value="{{ $cfg['ig'] ?? '' }}">
                    <input type="hidden" name="bio_tiktok" value="{{ $cfg['tiktok'] ?? '' }}">
                    <input type="hidden" name="bio_youtube" value="{{ $cfg['youtube'] ?? '' }}">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label class="form-label">Warna Background</label>
                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                <input type="color" name="color_bg" id="color_bg_picker" value="{{ $cfg['color_bg'] ?? '#0b120c' }}" style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;" oninput="syncColor('color_bg', this.value)">
                                <input type="text" id="color_bg_text" value="{{ $cfg['color_bg'] ?? '#0b120c' }}" class="form-input" style="flex:1;" oninput="syncColor('color_bg', this.value, true)">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label class="form-label">Warna Teks</label>
                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                <input type="color" name="color_text" id="color_text_picker" value="{{ $cfg['color_text'] ?? '#ffffff' }}" style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;" oninput="syncColor('color_text', this.value)">
                                <input type="text" id="color_text_text" value="{{ $cfg['color_text'] ?? '#ffffff' }}" class="form-input" style="flex:1;" oninput="syncColor('color_text', this.value, true)">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label class="form-label">Warna Tombol</label>
                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                <input type="color" name="color_btn" id="color_btn_picker" value="{{ $cfg['color_btn'] ?? '#1eb349' }}" style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;" oninput="syncColor('color_btn', this.value)">
                                <input type="text" id="color_btn_text" value="{{ $cfg['color_btn'] ?? '#1eb349' }}" class="form-input" style="flex:1;" oninput="syncColor('color_btn', this.value, true)">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label class="form-label">Teks Tombol</label>
                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                <input type="color" name="color_btn_text" id="color_btn_text_picker" value="{{ $cfg['color_btn_text'] ?? '#ffffff' }}" style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;" oninput="syncColor('color_btn_text', this.value)">
                                <input type="text" id="color_btn_text_text" value="{{ $cfg['color_btn_text'] ?? '#ffffff' }}" class="form-input" style="flex:1;" oninput="syncColor('color_btn_text', this.value, true)">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label class="form-label">Warna Aksen</label>
                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                <input type="color" name="color_accent" id="color_accent_picker" value="{{ $cfg['color_accent'] ?? '#1eb349' }}" style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;" oninput="syncColor('color_accent', this.value)">
                                <input type="text" id="color_accent_text" value="{{ $cfg['color_accent'] ?? '#1eb349' }}" class="form-input" style="flex:1;" oninput="syncColor('color_accent', this.value, true)">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label class="form-label">Warna Card</label>
                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                <input type="color" name="color_card" id="color_card_picker" value="{{ $cfg['color_card'] ?? '#1a231b' }}" style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;" oninput="syncColor('color_card', this.value)">
                                <input type="text" id="color_card_text" value="{{ $cfg['color_card'] ?? '#1a231b' }}" class="form-input" style="flex:1;" oninput="syncColor('color_card', this.value, true)">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:0.75rem; border-radius:14px; overflow:hidden; border:1px solid #e2e8f0;">
                        <div id="cpBg" style="padding:1.25rem; display:flex; flex-direction:column; align-items:center; gap:0.6rem; background:{{ $cfg['color_bg'] ?? '#0b120c' }};">
                            <div id="cpCard" style="width:46px; height:46px; border-radius:50%; background:{{ $cfg['color_card'] ?? '#1a231b' }}; border:2px solid {{ $cfg['color_accent'] ?? '#1eb349' }};"></div>
                            <div id="cpText" style="font-weight:700; font-size:0.88rem; color:{{ $cfg['color_text'] ?? '#ffffff' }};">Preview Nama Anda</div>
                            <div id="cpBtn" style="padding:0.45rem 1.25rem; border-radius:999px; font-size:0.78rem; font-weight:700; background:{{ $cfg['color_btn'] ?? '#1eb349' }}; color:{{ $cfg['color_btn_text'] ?? '#ffffff' }};">Tombol Contoh</div>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem;">
                        <button type="button" onclick="resetColors()" style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Reset Default</button>
                        <button type="submit" class="btn-submit-sm">Simpan Warna</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Hero/Banner Image --}}
        <div class="prof-card" style="margin-top:0;">
            <div class="prof-card-head"><span>Hero / Banner Halaman Bio</span></div>
            <div class="card-body">
                <form action="{{ route('creator.bio.save-profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="bio_name" value="{{ $cfg['name'] ?? '' }}">
                    <input type="hidden" name="bio_bio" value="{{ $cfg['bio'] ?? '' }}">
                    <input type="hidden" name="bio_location" value="{{ $cfg['location'] ?? '' }}">
                    <input type="hidden" name="bio_wa" value="{{ $cfg['wa'] ?? '' }}">
                    <input type="hidden" name="bio_ig" value="{{ $cfg['ig'] ?? '' }}">
                    <input type="hidden" name="bio_tiktok" value="{{ $cfg['tiktok'] ?? '' }}">
                    <input type="hidden" name="bio_youtube" value="{{ $cfg['youtube'] ?? '' }}">
                    <input type="hidden" name="color_bg" value="{{ $cfg['color_bg'] ?? '' }}">
                    <input type="hidden" name="color_text" value="{{ $cfg['color_text'] ?? '' }}">
                    <input type="hidden" name="color_btn" value="{{ $cfg['color_btn'] ?? '' }}">
                    <input type="hidden" name="color_btn_text" value="{{ $cfg['color_btn_text'] ?? '' }}">
                    <input type="hidden" name="color_accent" value="{{ $cfg['color_accent'] ?? '' }}">
                    <input type="hidden" name="color_card" value="{{ $cfg['color_card'] ?? '' }}">
                    <input type="hidden" name="hero_size" id="heroSizeHidden" value="{{ $cfg['hero_size'] ?? '200' }}">
                    @if(!empty($cfg['hero']))
                    <div id="heroPreviewWrap" style="margin-bottom:1rem;">
                        <img id="heroPreview" src="{{ asset('storage/'.$cfg['hero']) }}" style="width:100%; height:{{ $cfg['hero_size'] ?? 200 }}px; object-fit:cover; border-radius:12px; display:block;">
                    </div>
                    @else
                    <div id="heroPreviewWrap" style="display:none; margin-bottom:1rem;">
                        <img id="heroPreview" src="" style="width:100%; height:200px; object-fit:cover; border-radius:12px; display:block;">
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Upload Gambar Hero / Banner</label>
                        <input type="file" name="bio_hero" accept="image/*" class="form-input" style="height:auto; padding:0.5rem;"
                            onchange="var r=new FileReader(); r.onload=function(e){document.getElementById('heroPreview').src=e.target.result; document.getElementById('heroPreviewWrap').style.display='block';}; r.readAsDataURL(this.files[0]);">
                        <span class="form-hint">Minimal 1080x400px. Tampil di atas halaman bio Anda.</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tinggi Gambar: <span id="heroSizeLabel">{{ $cfg['hero_size'] ?? 200 }}px</span></label>
                        <input type="range" min="80" max="500" step="10" value="{{ $cfg['hero_size'] ?? 200 }}" style="width:100%; accent-color:#1eb349;"
                            oninput="document.getElementById('heroSizeLabel').textContent=this.value+'px'; document.getElementById('heroSizeHidden').value=this.value; var p=document.getElementById('heroPreview'); if(p) p.style.height=this.value+'px';">
                    </div>
                    <div style="display:flex; justify-content:flex-end;">
                        <button type="submit" class="btn-submit-sm">Simpan Hero</button>
                    </div>
                </form>
            </div>
        </div>

BLADE;

$content = str_replace($search, $insertion . "\n        {{-- ══ TAB 2: PROFIL ══ --}}", $content);
file_put_contents($file, $content);
echo "Done! Sections inserted.\n";
