@extends('creator.layout')
@section('title', 'Link in Bio · Dashboard')
@section('page_title', 'Link in Bio')

@section('topbar_actions')
@if($profile->store_slug)
<a href="{{ url('/'.$profile->store_slug) }}" target="_blank" class="btn-primary" style="background:transparent; color:#1eb349; border:1.5px solid #1eb349; box-shadow:none;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
    Lihat Bio
</a>
@endif
@endsection

@section('styles')
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<style>
.bio-layout    { display:flex; gap:2rem; align-items:flex-start; }
.bio-sidebar   { width:320px; flex-shrink:0; background:#fff; border-radius:20px; padding:1.25rem; box-shadow:0 4px 12px rgba(0,0,0,0.03); border:1px solid #f0fdf4; position:sticky; top:1.5rem; max-height: calc(100vh - 3rem); overflow-y: auto; scrollbar-width: none; }
.bio-sidebar::-webkit-scrollbar { display: none; }
.bio-content   { flex:1; min-width:0; }
.tab-btn       { width:100%; display:flex; align-items:center; gap:0.8rem; padding:0.85rem 1rem; border:none; background:transparent; color:#64748b; font-family:'Montserrat',sans-serif; font-size:0.85rem; font-weight:600; border-radius:12px; cursor:pointer; text-align:left; transition:all 0.2s; margin-bottom:0.2rem; }
.tab-btn:hover { background:#f8fafc; color:#1eb349; }
.tab-btn.active{ background:linear-gradient(135deg,#1eb349,#a5cf37); color:#fff; font-weight:700; box-shadow:0 4px 12px rgba(30,179,73,0.2); }
.tab-pane      { display:none; animation:fadeIn 0.3s; }
.tab-pane.active{ display:block; }
@keyframes fadeIn { from{opacity:0;transform:translateY(5px)} to{opacity:1;transform:translateY(0)} }
.prof-card     { background:#fff; border-radius:20px; border:1px solid #f0fdf4; box-shadow:0 4px 20px rgba(0,0,0,0.03); margin-bottom:1.5rem; overflow:hidden; }
.prof-card-head{ padding:1.25rem 1.75rem; border-bottom:1px solid #f8fafc; display:flex; align-items:center; justify-content:space-between; gap:0.6rem; font-size:0.95rem; font-weight:800; color:#0b120c; }
.card-body     { padding:1.5rem 1.75rem; }
.form-group    { display:flex; flex-direction:column; gap:0.4rem; margin-bottom:1.25rem; }
.form-label    { font-size:0.8rem; font-weight:700; color:#374151; }
.form-input    { height:44px; padding:0 1rem; border:1.5px solid #e7f0e7; border-radius:10px; font-family:'Montserrat',sans-serif; font-size:0.875rem; color:#1a1a1a; background:#f9fefb; outline:none; transition:all 0.2s; }
.form-input:focus { border-color:#1eb349; background:#fff; box-shadow:0 0 0 3px rgba(30,179,73,0.1); }
textarea.form-input { height:auto; padding:0.75rem 1rem; resize:vertical; min-height:80px; }
.form-hint     { font-size:0.72rem; color:#94a3b8; }

/* Theme grid */
.theme-grid    { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
.theme-card    { border:2px solid #e7f0e7; border-radius:14px; overflow:hidden; cursor:pointer; transition:all 0.2s; background:#f9fefb; }
.theme-card:hover { border-color:#1eb349; transform:scale(1.02); }
.theme-card.active { border-color:#1eb349; box-shadow:0 0 0 4px rgba(30,179,73,0.15); }
.theme-card img { width:100%; height:160px; object-fit:cover; display:block; }
.theme-label   { padding:0.6rem 0.8rem; font-weight:700; font-size:0.8rem; color:#0b120c; display:flex; align-items:center; gap:0.4rem; }

/* Block list */
.block-item    { background:#f9fefb; border:1px solid #e7f0e7; border-radius:12px; padding:0.85rem 1rem; display:flex; align-items:center; gap:0.75rem; margin-bottom:0.65rem; transition:all 0.2s; }
.block-item:hover { border-color:#1eb349; background:#f0fdf4; }
.block-icon    { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.block-info    { flex:1; min-width:0; }
.block-title   { font-weight:700; font-size:0.85rem; color:#0b120c; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.block-sub     { font-size:0.72rem; color:#64748b; }
.block-actions { display:flex; gap:0.4rem; flex-shrink:0; }
.btn-icon-sm   { width:30px; height:30px; border:none; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.2s; }

/* Add block modal */
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:1000; align-items:center; justify-content:center; }
.modal-overlay.open { display:flex; }
.modal-box     { background:#fff; border-radius:24px; max-width:500px; width:calc(100% - 2rem); padding:2rem; box-shadow:0 24px 60px rgba(0,0,0,0.25); animation:fadeIn 0.25s; }
.btn-submit-sm { height:40px; padding:0 1.25rem; border-radius:999px; background:linear-gradient(135deg,#1eb349,#a5cf37); border:none; color:#fff; font-weight:700; font-size:0.82rem; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem; }

/* Affiliate product card */
.aff-card     { border:1px solid #e7f0e7; border-radius:14px; overflow:hidden; background:#fff; display:flex; gap:0; margin-bottom:1rem; }
.aff-img      { width:80px; height:80px; object-fit:cover; flex-shrink:0; }
.aff-info     { padding:0.75rem; flex:1; }
.aff-title    { font-weight:700; font-size:0.82rem; color:#0b120c; line-height:1.3; margin-bottom:0.3rem; }
.aff-sub      { font-size:0.7rem; color:#64748b; }

/* Icon Picker */
.icon-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(40px, 1fr)); gap: 0.5rem; max-height: 250px; overflow-y: auto; padding-right: 0.5rem; }
.icon-grid::-webkit-scrollbar { width: 6px; }
.icon-grid::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.icon-btn { font-size: 24px; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; padding: 0; }
.icon-btn:hover { background: #e0f2fe; color: #0284c7; border-color: #7dd3fc; transform:scale(1.1); }

/* Custom Confirm Modal */
.confirm-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); backdrop-filter:blur(6px); z-index:2000; align-items:center; justify-content:center; }
.confirm-modal-overlay.open { display:flex; }
.confirm-modal-box { background:#fff; border-radius:24px; max-width:380px; width:calc(100% - 2rem); padding:2rem; box-shadow:0 24px 60px rgba(0,0,0,0.25); animation:fadeIn 0.2s; text-align:center; }
.confirm-modal-icon { width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; }
.confirm-modal-title { font-size:1.05rem; font-weight:800; color:#0b120c; margin-bottom:0.4rem; font-family:'Montserrat',sans-serif; }
.confirm-modal-desc { font-size:0.82rem; color:#64748b; margin-bottom:1.5rem; line-height:1.5; }
.confirm-modal-actions { display:flex; gap:0.75rem; justify-content:center; }
.confirm-btn-cancel { height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; font-size:0.82rem; cursor:pointer; }
.confirm-btn-danger { height:40px; padding:0 1.5rem; border-radius:999px; border:none; background:#ef4444; color:#fff; font-weight:700; font-size:0.82rem; cursor:pointer; }
.confirm-btn-primary { height:40px; padding:0 1.5rem; border-radius:999px; border:none; background:linear-gradient(135deg,#1eb349,#a5cf37); color:#fff; font-weight:700; font-size:0.82rem; cursor:pointer; }

@media(max-width:768px) { .bio-layout{flex-direction:column} .bio-sidebar{width:100%;position:static} .theme-grid{grid-template-columns:1fr} }

@keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }

/* Theme Mockups */
/* Theme Mockups */
.theme-mockup { width: 100%; height: 140px; position: relative; overflow: hidden; display: flex; flex-direction: column; align-items: center; padding-top: 1.5rem; gap: 0.5rem; }
.mockup-avatar { width: 34px; height: 34px; border-radius: 50%; }
.mockup-title { width: 50px; height: 6px; border-radius: 4px; margin-bottom: 0.5rem; }
.mockup-btn { width: 75%; height: 14px; }

/* Theme 1: Gelap Elegan */
.theme-mockup.theme1 { background: #0b120c; }
.theme-mockup.theme1 .mockup-avatar { background: #1a231b; border: 1px solid #1eb349; }
.theme-mockup.theme1 .mockup-title { background: #fff; }
.theme-mockup.theme1 .mockup-btn { background: #1a231b; border: 1px solid #1eb349; border-radius: 6px; }

/* Theme 2: Minimalis Pro */
.theme-mockup.theme2 { background: #f8fafc; }
.theme-mockup.theme2 .mockup-avatar { background: #e2e8f0; border: 1px solid #cbd5e1; }
.theme-mockup.theme2 .mockup-title { background: #0b120c; }
.theme-mockup.theme2 .mockup-btn { background: #fff; border: 1px solid #cbd5e1; border-radius: 999px; }

/* Theme 3: Gradient Neon */
.theme-mockup.theme3 { background: linear-gradient(135deg, #1e293b, #0f172a); }
.theme-mockup.theme3 .mockup-avatar { background: #334155; }
.theme-mockup.theme3 .mockup-title { background: #38bdf8; }
.theme-mockup.theme3 .mockup-btn { background: transparent; border: 1.5px solid #38bdf8; border-radius: 6px; }

/* Theme 4: Clean Light */
.theme-mockup.theme4 { background: #ffffff; }
.theme-mockup.theme4 .mockup-avatar { background: #f1f5f9; border: 1px solid #e2e8f0; }
.theme-mockup.theme4 .mockup-title { background: #334155; }
.theme-mockup.theme4 .mockup-btn { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; }
</style>
@endsection

@section('content')
@php
    $cfg = $profile->bio_config ?? [];
    $currentTheme = $profile->bio_theme ?? 'theme1';
    $roleLabels = ['content_creator'=>'Content Creator','affiliator'=>'Affiliator','business'=>'Business / Brand'];
    $bioUrl = $profile->store_slug ? url('/'.$profile->store_slug) : null;
@endphp

<div class="bio-layout">

    {{-- Sidebar Nav --}}
    <div class="bio-sidebar">
        <button class="tab-btn active" data-tab="tab-theme">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
            Tampilan & Tema
        </button>
        <button class="tab-btn" data-tab="tab-profile">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
            Pengaturan Profil
        </button>
        <button class="tab-btn" data-tab="tab-blocks">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Kelola Block
        </button>
        <button class="tab-btn" data-tab="tab-catalog">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            Katalog & Affiliate
        </button>

        <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #e7f0e7;">
            <div style="font-size:0.7rem; color:#94a3b8; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Tipe Profil</div>
            <div style="font-size:0.82rem; font-weight:700; color:#1eb349;">{{ $roleLabels[$profile->bio_role] ?? '-' }}</div>
            @if($bioUrl)
            <a href="{{ $bioUrl }}" target="_blank" style="display:flex; align-items:center; gap:0.4rem; margin-top:0.75rem; font-size:0.72rem; color:#64748b; text-decoration:none; word-break:break-all;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke-linecap="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke-linecap="round"/></svg>
                {{ $bioUrl }}
            </a>
            @endif
        </div>

        {{-- Mobile Preview Mockup --}}
        @if($bioUrl)
        <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #e7f0e7;">
            <div style="font-size:0.7rem; color:#94a3b8; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:1rem; text-align:center;">Live Preview</div>
            <div style="width: 260px; height: 530px; margin: 0 auto; border: 12px solid #1a1a1a; border-radius: 36px; overflow: hidden; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); background:#fff;">
                {{-- Notch --}}
                <div style="position:absolute; top:0; left:50%; transform:translateX(-50%); width:100px; height:20px; background:#1a1a1a; border-bottom-left-radius:12px; border-bottom-right-radius:12px; z-index:10;"></div>
                <iframe src="{{ $bioUrl }}" style="width:100%; height:100%; border:none; background:#fff;" id="bioPreviewFrame"></iframe>
            </div>
            <p style="text-align:center; font-size:0.7rem; color:#94a3b8; margin-top:0.75rem;">Perubahan profil akan terupdate otomatis saat disimpan.</p>
        </div>
        @endif
    </div>

    {{-- Main Content --}}
    <div class="bio-content">

        {{-- ══ TAB 1: TEMA ══ --}}
        <div class="tab-pane active" id="tab-theme">
            <div class="prof-card">
                <div class="prof-card-head">
                    <span>Pilih Tema Visual</span>
                </div>
                <div class="card-body">
                    <p style="font-size:0.82rem; color:#64748b; margin-bottom:1.5rem;">Tema menentukan tampilan halaman publik Link in Bio Anda. Klik untuk memilih, lalu klik Simpan.</p>
                    <form action="{{ route('creator.bio.save-theme') }}" method="POST">
                        @csrf
                        <div class="theme-grid">
                            @foreach(['theme1'=>'Gelap Elegan','theme2'=>'Minimalis Pro','theme3'=>'Gradient Neon','theme4'=>'Clean Light'] as $key=>$label)
                            <label class="theme-card {{ $currentTheme === $key ? 'active' : '' }}">
                                <input type="radio" name="bio_theme" value="{{ $key }}" {{ $currentTheme === $key ? 'checked' : '' }} style="display:none;" onchange="this.closest('form').submit()">
                                <div class="theme-mockup {{ $key }}">
                                    <div class="mockup-avatar"></div>
                                    <div class="mockup-title"></div>
                                    <div class="mockup-btn"></div>
                                    <div class="mockup-btn"></div>
                                </div>
                                <div class="theme-label">
                                    @if($currentTheme === $key) <svg width="14" height="14" fill="#1eb349" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg> @endif
                                    {{ $label }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ══ TAB 2: PROFIL ══ --}}
        <div class="tab-pane" id="tab-profile">
            <form action="{{ route('creator.bio.save-profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="prof-card">
                    <div class="prof-card-head">Informasi Profil Kreator</div>
                    <div class="card-body">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                            <div class="form-group">
                                <label class="form-label">Nama Tampilan</label>
                                <input type="text" name="bio_name" value="{{ old('bio_name', $cfg['name'] ?? $profile->store_name) }}" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Username / URL Publik</label>
                                <div style="display:flex; align-items:center; border:1.5px solid #e7f0e7; border-radius:10px; background:#f9fefb; overflow:hidden;">
                                    <span style="padding:0 0.75rem; color:#94a3b8; font-size:0.8rem; border-right:1.5px solid #e7f0e7; background:#f1f5f9; height:44px; display:flex; align-items:center;">buyle.id/</span>
                                    <input type="text" name="bio_username" value="{{ old('bio_username', $profile->store_slug) }}" style="height:44px; border:none; background:transparent; padding:0 1rem; font-family:'Montserrat',sans-serif; font-size:0.875rem; color:#1a1a1a; outline:none; flex:1;" placeholder="username">
                                </div>
                                @error('bio_username')<span style="font-size:0.72rem; color:#ef4444;">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group" style="grid-column:1/-1;">
                                <label class="form-label">Bio / Tagline</label>
                                <textarea name="bio_bio" class="form-input" rows="2" maxlength="300" placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio_bio', $cfg['bio'] ?? '') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="bio_location" value="{{ old('bio_location', $cfg['location'] ?? '') }}" class="form-input" placeholder="Jakarta, Indonesia">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="prof-card">
                    <div class="prof-card-head">Foto Profil & Cover</div>
                    <div class="card-body" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Foto Profil (Avatar)</label>
                            @if(!empty($cfg['avatar']))
                                <img src="{{ asset('storage/'.$cfg['avatar']) }}" style="width:80px; height:80px; border-radius:50%; object-fit:cover; margin-bottom:0.5rem; border:3px solid #1eb349;">
                            @endif
                            <input type="file" name="bio_avatar" accept="image/*" class="form-input" style="height:auto; padding:0.5rem;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Foto Cover / Banner</label>
                            @if(!empty($cfg['cover']))
                                <img src="{{ asset('storage/'.$cfg['cover']) }}" style="width:100%; height:60px; border-radius:10px; object-fit:cover; margin-bottom:0.5rem;">
                            @endif
                            <input type="file" name="bio_cover" accept="image/*" class="form-input" style="height:auto; padding:0.5rem;">
                        </div>
                    </div>
                </div>

                <div class="prof-card">
                    <div class="prof-card-head">Social Media</div>
                    <div class="card-body">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                            <div class="form-group">
                                <label class="form-label"><span style="color:#25D366;">●</span> WhatsApp</label>
                                <input type="text" name="bio_wa" value="{{ old('bio_wa', $cfg['wa'] ?? '') }}" class="form-input" placeholder="628xxxxxxxxx">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><span style="color:#E4405F;">●</span> Instagram</label>
                                <input type="text" name="bio_ig" value="{{ old('bio_ig', $cfg['ig'] ?? '') }}" class="form-input" placeholder="@username">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><span style="color:#000;">●</span> TikTok</label>
                                <input type="text" name="bio_tiktok" value="{{ old('bio_tiktok', $cfg['tiktok'] ?? '') }}" class="form-input" placeholder="@username">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><span style="color:#FF0000;">●</span> YouTube</label>
                                <input type="url" name="bio_youtube" value="{{ old('bio_youtube', $cfg['youtube'] ?? '') }}" class="form-input" placeholder="https://youtube.com/...">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn-primary">Simpan Profil</button>
                </div>
            </form>
        </div>

        {{-- ══ TAB 3: BLOCKS ══ --}}
        <div class="tab-pane" id="tab-blocks">
            <div class="prof-card">
                <div class="prof-card-head">
                    <span>Block Aktif</span>
                    <button onclick="document.getElementById('addBlockModal').classList.add('open')" class="btn-submit-sm">+ Tambah Block</button>
                </div>
                <div class="card-body">
                    @php
                        $typeBlocks = $blocks->whereIn('type', ['link','pdf','tiktok']);
                    @endphp
                    @forelse($typeBlocks as $block)
                    <div class="block-item" style="{{ !$block->is_active ? 'opacity:0.5;' : '' }}">
                        <div class="block-icon" style="background:{{ ['link'=>'#f0fdf4','pdf'=>'#fef2f2','tiktok'=>'#1a1a1a'][$block->type] ?? '#f8fafc' }}; color:{{ ['link'=>'#1eb349','pdf'=>'#ef4444','tiktok'=>'#fff'][$block->type] ?? '#64748b' }};">
                            @if($block->type==='link') <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke-linecap="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke-linecap="round"/></svg>
                            @elseif($block->type==='pdf') <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            @else <svg width="18" height="18" fill="white" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.77 1.52V6.76a4.85 4.85 0 0 1-1-.07z"/></svg>
                            @endif
                        </div>
                        <div class="block-info">
                            <div class="block-title">{{ $block->title }}</div>
                            <div class="block-sub">{{ $block->type }} · {{ Str::limit($block->url, 40) }}</div>
                        </div>
                        <div class="block-actions">
                            <form action="{{ route('creator.bio.blocks.toggle', $block) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-icon-sm" style="background:{{ $block->is_active ? '#dcfce7' : '#f1f5f9' }}; color:{{ $block->is_active ? '#15803d' : '#94a3b8' }};" title="{{ $block->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="{{ $block->is_active ? 'M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z' : 'M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19' }}"/><circle cx="12" cy="12" r="3" {{ $block->is_active ? '' : 'style=display:none' }}/></svg>
                                </button>
                            </form>
                            <button type="button" class="btn-icon-sm btn-edit-block"
                                data-id="{{ $block->id }}"
                                data-title="{{ addslashes($block->title) }}"
                                data-url="{{ addslashes($block->url ?? '') }}"
                                data-icon="{{ $block->data_json['icon_class'] ?? '' }}"
                                style="background:#e0f2fe; color:#0284c7;" title="Edit">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form action="{{ route('creator.bio.blocks.destroy', $block) }}" method="POST" class="form-delete-block" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-icon-sm btn-delete-block" style="background:#fef2f2; color:#ef4444;" title="Hapus">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6M14 11v6"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                        Belum ada block. Klik "+ Tambah Block" untuk menambahkan link, PDF, atau TikTok.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ══ TAB 4: KATALOG & AFFILIATE ══ --}}
        <div class="tab-pane" id="tab-catalog">

            {{-- Shopee Affiliate / Produk Eksternal --}}
            <div class="prof-card">
                <div class="prof-card-head">
                    <span style="display:flex; align-items:center; gap:0.5rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        Tambah Produk Affiliate / Shopee
                    </span>
                    <button onclick="document.getElementById('addAffModal').classList.add('open')" class="btn-submit-sm">+ Tambah</button>
                </div>
                <div class="card-body">
                    @forelse($blocks->whereIn('type',['shopee','affiliate']) as $block)
                    <div class="aff-card">
                        @if(!empty($block->data_json['image']))
                            <img src="{{ $block->data_json['image'] }}" class="aff-img" onerror="this.style.display='none'">
                        @endif
                        <div class="aff-info">
                            <div class="aff-title">{{ $block->title }}</div>
                            <div class="aff-sub">{{ Str::limit($block->url, 50) }}</div>
                        </div>
                        <div style="padding:0.75rem; display:flex; align-items:center; gap:0.4rem;">
                            <button type="button" class="btn-icon-sm btn-edit-block"
                                data-id="{{ $block->id }}"
                                data-title="{{ addslashes($block->title) }}"
                                data-url="{{ addslashes($block->url ?? '') }}"
                                data-icon="{{ $block->data_json['icon_class'] ?? '' }}"
                                style="background:#e0f2fe; color:#0284c7;" title="Edit">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form action="{{ route('creator.bio.blocks.destroy', $block) }}" method="POST" class="form-delete-block">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-icon-sm btn-delete-block" style="background:#fef2f2; color:#ef4444;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p style="color:#94a3b8; font-size:0.85rem; text-align:center; padding:2rem 0;">Belum ada produk affiliate. Masukkan link Shopee dan sistem akan mengambil gambar otomatis.</p>
                    @endforelse
                </div>
            </div>

            {{-- Produk Buyle Saya --}}
            <div class="prof-card">
                <div class="prof-card-head">
                    <span style="display:flex; align-items:center; gap:0.5rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        Tampilkan Produk Buyle Saya
                    </span>
                </div>
                <div class="card-body">
                    @forelse($myProducts as $product)
                    @php $alreadyAdded = $blocks->where('type','buyle_product')->contains(fn($b) => ($b->data_json['product_id'] ?? null) == $product->id); @endphp
                    <div style="display:flex; align-items:center; gap:0.75rem; padding:0.65rem 0; border-bottom:1px solid #f3f7f3;">
                        @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" style="width:48px; height:48px; border-radius:8px; object-fit:cover; flex-shrink:0;">
                        @endif
                        <div style="flex:1; min-width:0;">
                            <div style="font-weight:700; font-size:0.82rem; color:#0b120c; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $product->name }}</div>
                            <div style="font-size:0.72rem; color:#64748b;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                        @if($alreadyAdded)
                        <span style="font-size:0.72rem; font-weight:700; color:#1eb349; background:#f0fdf4; padding:0.25rem 0.6rem; border-radius:6px;">Ditampilkan</span>
                        @else
                        <form action="{{ route('creator.bio.blocks.store') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="type" value="buyle_product">
                            <input type="hidden" name="title" value="{{ $product->name }}">
                            <input type="hidden" name="url" value="{{ route('products.show', $product->slug) }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn-submit-sm" style="font-size:0.75rem; height:34px;">+ Tampilkan</button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <p style="color:#94a3b8; font-size:0.85rem; text-align:center; padding:2rem 0;">Belum ada produk. <a href="{{ route('creator.products.create') }}" style="color:#1eb349;">Tambah Produk →</a></p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>{{-- /bio-content --}}
</div>{{-- /bio-layout --}}

{{-- ── Modal: Tambah Block Biasa ── --}}
<div class="modal-overlay" id="addBlockModal" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="modal-box">
        <h3 style="font-size:1.1rem; font-weight:800; margin:0 0 1.25rem; color:#0b120c; font-family:'Montserrat',sans-serif;">Tambah Block Baru</h3>
        <form action="{{ route('creator.bio.blocks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Tipe Block</label>
                <select name="type" class="form-input" id="blockType" onchange="handleTypeChange(this.value)">
                    <option value="link">Custom Link / Button</option>
                    <option value="pdf">File / Dokumen PDF</option>
                    <option value="tiktok">TikTok Video</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Judul / Label Tombol</label>
                <input type="text" name="title" class="form-input" placeholder="Contoh: Download Portfolio" maxlength="150" required>
            </div>
            <div class="form-group">
                <label class="form-label">URL / Link</label>
                <input type="url" name="url" class="form-input" placeholder="https://" required>
            </div>
            <div class="form-group">
                <label class="form-label">Ikon (Pilih dari Galeri ATAU Upload)</label>
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    <button type="button" class="btn-primary" style="background:#f8fafc; color:#1e293b; border:1.5px solid #cbd5e1; height:44px; padding:0 1rem; border-radius:10px; font-weight:600; display:flex; gap:0.5rem; align-items:center;" onclick="openIconPicker('addBlockIconClass', 'addBlockIconPreview')">
                        <i class="ph ph-squares-four" style="font-size:1.2rem;"></i> Pilih Ikon
                    </button>
                    <div id="addBlockIconPreview" style="display:none; font-size:24px; color:#1eb349; width:44px; height:44px; align-items:center; justify-content:center; border:1.5px solid #1eb349; border-radius:10px; background:#f0fdf4;"></div>
                    <div style="flex:1;">
                        <input type="file" name="block_image" accept="image/*" class="form-input" style="height:44px; padding:0.5rem; width:100%;">
                    </div>
                </div>
                <input type="hidden" name="icon_class" id="addBlockIconClass">
            </div>
            <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem;">
                <button type="button" onclick="document.getElementById('addBlockModal').classList.remove('open')" style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Batal</button>
                <button type="submit" class="btn-submit-sm">Tambah Block</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Tambah Affiliate / Shopee ── --}}
<div class="modal-overlay" id="addAffModal" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="modal-box">
        <h3 style="font-size:1.1rem; font-weight:800; margin:0 0 0.5rem; color:#0b120c; font-family:'Montserrat',sans-serif;">Tambah Produk Affiliate</h3>
        <p style="font-size:0.78rem; color:#64748b; margin:0 0 1.25rem;">Tempel link Shopee/Tokopedia — sistem akan ambil gambar otomatis. Atau upload manual.</p>

        <div id="scrapePreview" style="display:none; background:#f0fdf4; border-radius:12px; padding:1rem; margin-bottom:1rem; display:none; align-items:center; gap:0.75rem;">
            <img id="scrapeImg" style="width:60px; height:60px; border-radius:8px; object-fit:cover;" src="" onerror="this.src='https://placehold.co/60x60/fff/cbd5e1?text=Img'">
            <div><div id="scrapeTitle" style="font-weight:700; font-size:0.82rem; color:#0b120c; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;"></div><div style="font-size:0.7rem; color:#1eb349;">✓ Info berhasil ditemukan</div></div>
        </div>

        <form action="{{ route('creator.bio.blocks.store') }}" method="POST" enctype="multipart/form-data" id="affForm">
            @csrf
            <input type="hidden" name="type" value="shopee">
            <input type="hidden" name="scraped_image" id="affScrapedImage">
            <div class="form-group">
                <label class="form-label">Link Produk (Shopee / Tokopedia / dll)</label>
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    <input type="url" name="url" id="affUrl" class="form-input" placeholder="https://shopee.co.id/..." style="flex:1;">
                    <button type="button" id="btnScrape" onclick="scrapeUrl(document.getElementById('affUrl').value, true)" style="height:44px; padding:0 1.1rem; border-radius:10px; background:linear-gradient(135deg,#1eb349,#a5cf37); border:none; color:#fff; font-weight:700; font-size:0.82rem; cursor:pointer; display:flex; align-items:center; gap:0.4rem; white-space:nowrap; flex-shrink:0;">
                        <svg id="scrapeSpinner" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:none;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                        <svg id="scrapeIcon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M1 4v6h6"/><path d="M23 20v-6h-6"/><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                        Scrape
                    </button>
                </div>
                <span class="form-hint" id="scrapeStatus">Klik "Scrape" untuk mengambil gambar & judul otomatis dari link</span>
            </div>
            <div class="form-group">
                <label class="form-label">Judul Produk</label>
                <input type="text" name="title" id="affTitle" class="form-input" placeholder="Nama produk..." maxlength="150" required>
            </div>
            <div class="form-group">
                <label class="form-label">Upload Gambar Manual (jika scrape gagal)</label>
                <input type="file" name="block_image" accept="image/*" class="form-input" style="height:auto; padding:0.5rem;">
            </div>
            <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem;">
                <button type="button" onclick="document.getElementById('addAffModal').classList.remove('open')" style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Batal</button>
                <button type="submit" class="btn-submit-sm">Tambah Produk</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Edit Block ── --}}
<div class="modal-overlay" id="editBlockModal" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="modal-box">
        <h3 style="font-size:1.1rem; font-weight:800; margin:0 0 1.25rem; color:#0b120c; font-family:'Montserrat',sans-serif;">Edit Block</h3>
        <form action="" method="POST" enctype="multipart/form-data" id="editBlockForm">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Judul / Label</label>
                <input type="text" name="title" id="editBlockTitle" class="form-input" maxlength="150" required>
            </div>
            <div class="form-group">
                <label class="form-label">URL / Link</label>
                <input type="url" name="url" id="editBlockUrl" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Ganti Ikon (Pilih dari Galeri ATAU Upload)</label>
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    <button type="button" class="btn-primary" style="background:#f8fafc; color:#1e293b; border:1.5px solid #cbd5e1; height:44px; padding:0 1rem; border-radius:10px; font-weight:600; display:flex; gap:0.5rem; align-items:center;" onclick="openIconPicker('editBlockIconClass', 'editBlockIconPreview')">
                        <i class="ph ph-squares-four" style="font-size:1.2rem;"></i> Pilih Ikon
                    </button>
                    <div id="editBlockIconPreview" style="display:none; font-size:24px; color:#1eb349; width:44px; height:44px; align-items:center; justify-content:center; border:1.5px solid #1eb349; border-radius:10px; background:#f0fdf4;"></div>
                    <div style="flex:1;">
                        <input type="file" name="block_image" accept="image/*" class="form-input" style="height:44px; padding:0.5rem; width:100%;">
                    </div>
                </div>
                <input type="hidden" name="icon_class" id="editBlockIconClass">
            </div>
            <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem;">
                <button type="button" onclick="document.getElementById('editBlockModal').classList.remove('open')" style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Batal</button>
                <button type="submit" class="btn-submit-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Icon Picker ── --}}
<div class="modal-overlay" id="iconPickerModal" onclick="if(event.target===this)this.classList.remove('open')" style="z-index: 1050;">
    <div class="modal-box">
        <h3 style="font-size:1.1rem; font-weight:800; margin:0 0 1rem; color:#0b120c; font-family:'Montserrat',sans-serif;">Pilih Ikon</h3>
        <p style="font-size:0.75rem; color:#64748b; margin-bottom:1rem;">Pilih salah satu ikon di bawah ini untuk ditampilkan di tombol Anda.</p>
        
        <div class="icon-grid" id="iconGrid">
            <!-- Icons injected via JS -->
        </div>

        <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.5rem;">
            <button type="button" onclick="document.getElementById('iconPickerModal').classList.remove('open')" style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Tutup</button>
        </div>
    </div>
</div>

{{-- ── Modal: Custom Confirm / Delete ── --}}
<div class="confirm-modal-overlay" id="confirmModal">
    <div class="confirm-modal-box">
        <div class="confirm-modal-icon" id="confirmIcon" style="background:#fef2f2;">
            <svg width="26" height="26" fill="none" stroke="#ef4444" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
        </div>
        <div class="confirm-modal-title" id="confirmTitle">Hapus Block Ini?</div>
        <div class="confirm-modal-desc" id="confirmDesc">Block yang dihapus tidak bisa dikembalikan. Pastikan Anda sudah yakin.</div>
        <div class="confirm-modal-actions">
            <button class="confirm-btn-cancel" onclick="closeConfirmModal()">Batal</button>
            <button class="confirm-btn-danger" id="confirmOkBtn" onclick="doConfirmAction()">Ya, Hapus</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        document.getElementById(this.dataset.tab).classList.add('active');
        localStorage.setItem('bio_active_tab', this.dataset.tab);
    });
});
// Restore tab
const savedTab = localStorage.getItem('bio_active_tab');
if(savedTab) {
    const btn = document.querySelector(`[data-tab="${savedTab}"]`);
    if(btn) btn.click();
}

// Shopee URL scraper AJAX
let scrapeTimeout;
function scrapeUrl(url, immediate) {
    var btn = document.getElementById('btnScrape');
    var spinner = document.getElementById('scrapeSpinner');
    var icon = document.getElementById('scrapeIcon');
    var status = document.getElementById('scrapeStatus');

    if (!url || !url.startsWith('http')) {
        if (status) { status.textContent = 'Masukkan link produk yang valid terlebih dahulu.'; status.style.color = '#ef4444'; }
        return;
    }

    var doScrape = function() {
        if (btn) btn.disabled = true;
        if (spinner) { spinner.style.display = 'inline'; }
        if (icon) icon.style.display = 'none';
        if (status) { status.textContent = 'Sedang mengambil data produk...'; status.style.color = '#64748b'; }

        fetch('{{ route("creator.bio.scrape-url") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ url: url })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var preview = document.getElementById('scrapePreview');
            if (data.image || data.title) {
                if (data.image) {
                    var img = document.getElementById('scrapeImg');
                    img.src = data.image;
                    img.style.display = 'block';
                    document.getElementById('affScrapedImage').value = data.image;
                }
                if (data.title) {
                    var shortTitle = data.title.length > 145 ? data.title.substring(0, 145) + '...' : data.title;
                    document.getElementById('scrapeTitle').textContent = shortTitle;
                    document.getElementById('affTitle').value = shortTitle;
                }
                preview.style.display = 'flex';
                if (status) { status.textContent = '✓ Data berhasil diambil! Periksa judul & gambar di atas.'; status.style.color = '#1eb349'; }
            } else {
                if (status) { status.textContent = 'Data tidak ditemukan otomatis. Silakan isi judul & gambar manual.'; status.style.color = '#f59e0b'; }
            }
        })
        .catch(function() {
            if (status) { status.textContent = 'Gagal terhubung ke link. Silakan isi manual.'; status.style.color = '#ef4444'; }
        })
        .then(function() {
            if (btn) btn.disabled = false;
            if (spinner) spinner.style.display = 'none';
            if (icon) icon.style.display = 'inline';
        });
    };

    clearTimeout(scrapeTimeout);
    if (immediate) { doScrape(); } else { scrapeTimeout = setTimeout(doScrape, 800); }
}

function editBlock(btn) {
    const id = btn.dataset.id;
    const title = btn.dataset.title;
    const url = btn.dataset.url;
    const iconClass = btn.dataset.icon || '';

    const form = document.getElementById('editBlockForm');
    form.action = '{{ url("creator/bio/blocks") }}/' + id;
    document.getElementById('editBlockTitle').value = title;
    document.getElementById('editBlockUrl').value = url;

    const iconClassInput = document.getElementById('editBlockIconClass');
    const iconPreview = document.getElementById('editBlockIconPreview');
    iconClassInput.value = iconClass;
    if (iconClass) {
        iconPreview.innerHTML = `<i class="${iconClass}" style="font-size:24px;"></i>`;
        iconPreview.style.display = 'flex';
    } else {
        iconPreview.style.display = 'none';
    }
    document.getElementById('editBlockModal').classList.add('open');
}

// Attach edit block listeners
document.querySelectorAll('.btn-edit-block').forEach(btn => {
    btn.addEventListener('click', function() { editBlock(this); });
});

// Custom confirm modal
let _pendingDeleteForm = null;
function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('open');
    _pendingDeleteForm = null;
}
document.querySelectorAll('.btn-delete-block').forEach(btn => {
    btn.addEventListener('click', function() {
        _pendingDeleteForm = this.closest('.form-delete-block');
        document.getElementById('confirmModal').classList.add('open');
    });
});
function doConfirmAction() {
    if (_pendingDeleteForm) {
        _pendingDeleteForm.submit();
    }
    closeConfirmModal();
}

// ── Icon Picker ──────────────────────────────────────────
const ICON_LIST = [
    // Communication
    'ph ph-chat-circle','ph ph-chat-dots','ph ph-phone','ph ph-phone-call','ph ph-envelope',
    'ph ph-envelope-simple','ph ph-telegram-logo','ph ph-whatsapp-logo','ph ph-instagram-logo',
    'ph ph-tiktok-logo','ph ph-youtube-logo','ph ph-twitter-logo','ph ph-facebook-logo',
    'ph ph-linkedin-logo','ph ph-discord-logo','ph ph-snapchat-logo','ph ph-pinterest-logo',
    // Commerce
    'ph ph-shopping-cart','ph ph-shopping-bag','ph ph-storefront','ph ph-tag','ph ph-currency-circle-dollar',
    'ph ph-wallet','ph ph-credit-card','ph ph-bank','ph ph-receipt','ph ph-package',
    'ph ph-gift','ph ph-percent','ph ph-barcode','ph ph-qr-code',
    // Media & Files
    'ph ph-play-circle','ph ph-video','ph ph-microphone','ph ph-music-note','ph ph-headphones',
    'ph ph-film-strip','ph ph-image','ph ph-images','ph ph-camera','ph ph-file-pdf',
    'ph ph-file-text','ph ph-file-zip','ph ph-download','ph ph-upload','ph ph-cloud',
    // Navigation
    'ph ph-house','ph ph-map-pin','ph ph-compass','ph ph-navigation','ph ph-globe',
    'ph ph-globe-hemisphere-east','ph ph-link','ph ph-link-simple','ph ph-arrow-right',
    'ph ph-arrow-circle-right','ph ph-arrow-square-out','ph ph-caret-right',
    // People & Profile
    'ph ph-user','ph ph-users','ph ph-user-circle','ph ph-identification-badge',
    'ph ph-smiley','ph ph-handshake','ph ph-hand-waving',
    // Business
    'ph ph-briefcase','ph ph-chart-bar','ph ph-chart-line','ph ph-presentation-chart',
    'ph ph-clipboard-text','ph ph-calendar','ph ph-clock','ph ph-bell','ph ph-megaphone',
    'ph ph-broadcast','ph ph-projector-screen','ph ph-article','ph ph-newspaper',
    // Creative
    'ph ph-palette','ph ph-pen','ph ph-pen-nib','ph ph-pencil','ph ph-paint-brush',
    'ph ph-magic-wand','ph ph-star','ph ph-sparkle','ph ph-crown','ph ph-trophy',
    'ph ph-medal','ph ph-lightning','ph ph-fire','ph ph-heart','ph ph-diamond',
    // Tech
    'ph ph-code','ph ph-code-block','ph ph-terminal','ph ph-laptop','ph ph-device-mobile',
    'ph ph-robot','ph ph-gear','ph ph-plugin','ph ph-cpu','ph ph-wifi',
    // Misc
    'ph ph-book','ph ph-book-open','ph ph-graduation-cap','ph ph-certificate',
    'ph ph-first-aid-kit','ph ph-leaf','ph ph-planet','ph ph-rocket','ph ph-key',
];

let _activeIconInputId = null;
let _activeIconPreviewId = null;

function openIconPicker(iconInputId, iconPreviewId) {
    _activeIconInputId = iconInputId;
    _activeIconPreviewId = iconPreviewId;

    const grid = document.getElementById('iconGrid');
    if (!grid.hasChildNodes()) {
        ICON_LIST.forEach(iconClass => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'icon-btn';
            btn.title = iconClass.replace('ph ph-', '').replace(/-/g, ' ');
            btn.innerHTML = `<i class="${iconClass}"></i>`;
            btn.onclick = () => selectIcon(iconClass);
            grid.appendChild(btn);
        });
    }
    document.getElementById('iconPickerModal').classList.add('open');
}

function selectIcon(iconClass) {
    if (!_activeIconInputId) return;
    document.getElementById(_activeIconInputId).value = iconClass;
    const preview = document.getElementById(_activeIconPreviewId);
    preview.innerHTML = `<i class="${iconClass}" style="font-size:24px;"></i>`;
    preview.style.display = 'flex';
    document.getElementById('iconPickerModal').classList.remove('open');
}
</script>
@endsection
