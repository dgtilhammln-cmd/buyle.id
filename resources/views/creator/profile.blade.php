@extends('creator.layout')

@section('title', 'Profil & Pengaturan Toko')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* { font-family: 'Montserrat', sans-serif; }

/* ── Page Header ─────────────────────────────────────────── */
.pg-header { margin-bottom: 1.75rem; }
.pg-title   { font-size: 1.6rem; font-weight: 700; color: #0f172a; margin: 0 0 0.2rem; }
.pg-sub     { font-size: 0.82rem; color: #64748b; margin: 0; font-weight: 400; }

/* ── Alert ───────────────────────────────────────────────── */
.alert-success {
    background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;
    border-radius: 12px; padding: 0.85rem 1.25rem;
    font-size: 0.82rem; font-weight: 600; margin-bottom: 1.25rem;
    display: flex; align-items: center; gap: 0.5rem;
}

/* ── Tab System ──────────────────────────────────────────── */
.tab-nav {
    display: flex;
    gap: 0.25rem;
    background: #fff;
    padding: 0.35rem;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    overflow-x: auto;
}
.tab-btn {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.1rem;
    border: none;
    border-radius: 10px;
    background: transparent;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.82rem;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.tab-btn:hover { background: #f8fafc; color: #0f172a; }
.tab-btn.active {
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #fff;
    box-shadow: 0 2px 10px rgba(30,179,73,0.25);
}
.tab-pane { display: none; }
.tab-pane.active { display: block; }

/* ── Card ────────────────────────────────────────────────── */
.prof-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.prof-card-head {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.9rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbfa;
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}
.prof-card-head svg { color: #1eb349; }
.prof-card-body { padding: 1.5rem; }

/* ── Form Elements ───────────────────────────────────────── */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }
.form-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
.form-group { display: flex; flex-direction: column; gap: 0.35rem; }
.form-group.full { grid-column: 1 / -1; }

.form-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.form-input {
    height: 42px;
    padding: 0 0.9rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.82rem;
    font-weight: 400;
    color: #1a1a1a;
    background: #f8fafc;
    outline: none;
    transition: all 0.2s;
    width: 100%;
    box-sizing: border-box;
}
.form-input:focus {
    border-color: #1eb349;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(30,179,73,0.1);
}
textarea.form-input { height: auto; padding: 0.75rem 0.9rem; resize: vertical; min-height: 90px; }
select.form-input { cursor: pointer; }

.form-hint  { font-size: 0.7rem; color: #94a3b8; }
.form-error { font-size: 0.7rem; color: #ef4444; }

/* ── Social Input ────────────────────────────────────────── */
.social-wrap {
    display: flex;
    align-items: center;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    overflow: hidden;
    transition: all 0.2s;
}
.social-wrap:focus-within {
    border-color: #1eb349;
    box-shadow: 0 0 0 3px rgba(30,179,73,0.1);
    background: #fff;
}
.social-prefix {
    padding: 0 0.7rem;
    font-size: 0.75rem;
    color: #94a3b8;
    background: #f1f5f9;
    border-right: 1px solid #e2e8f0;
    height: 42px;
    display: flex;
    align-items: center;
    white-space: nowrap;
}
.social-field {
    border: none !important;
    background: transparent !important;
    height: 42px !important;
    padding: 0 0.75rem !important;
    flex: 1;
    outline: none;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.82rem;
    box-shadow: none !important;
    min-width: 0;
}

/* ── Slug Input ──────────────────────────────────────────── */
.slug-wrap {
    display: flex;
    align-items: center;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    overflow: hidden;
    transition: all 0.2s;
}
.slug-wrap:focus-within { border-color: #1eb349; box-shadow: 0 0 0 3px rgba(30,179,73,0.1); }
.slug-prefix {
    padding: 0 0.75rem;
    font-size: 0.78rem;
    color: #94a3b8;
    background: #f1f5f9;
    border-right: 1px solid #e2e8f0;
    height: 42px;
    display: flex;
    align-items: center;
    white-space: nowrap;
}
.slug-input {
    border: none !important;
    background: transparent !important;
    height: 42px !important;
    padding: 0 0.75rem !important;
    flex: 1;
    outline: none;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.82rem;
}

/* ── Avatar & Banner ─────────────────────────────────────── */
.avatar-section {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.25rem;
    background: #f8fafc;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    margin-bottom: 1.25rem;
}
.avatar-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e2e8f0;
    flex-shrink: 0;
}
.avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    flex-shrink: 0;
}
.avatar-info { flex: 1; }
.avatar-info h4 { font-size: 0.9rem; font-weight: 700; color: #0f172a; margin: 0 0 0.2rem; }
.avatar-info p  { font-size: 0.75rem; color: #64748b; margin: 0 0 0.75rem; }

.banner-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.banner-slot {
    border: 1.5px dashed #cbd5e1;
    padding: 1rem;
    border-radius: 12px;
    background: #f8fafc;
    transition: border-color 0.2s;
}
.banner-slot:hover { border-color: #1eb349; }
.banner-label { font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 0.5rem; }
.banner-preview { width: 100%; height: 80px; object-fit: cover; border-radius: 8px; margin-bottom: 0.5rem; }

/* ── SEO Preview ─────────────────────────────────────────── */
.seo-preview {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-top: 1rem;
    font-family: sans-serif;
}
.seo-preview-url  { font-size: 0.8rem; color: #006621; margin-bottom: 0.2rem; }
.seo-preview-title { font-size: 1rem; color: #1a0dab; font-weight: 600; margin-bottom: 0.25rem; }
.seo-preview-desc { font-size: 0.82rem; color: #545454; line-height: 1.4; }

/* ── Submit Bar ──────────────────────────────────────────── */
.submit-bar {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    margin-top: 0.5rem;
}
.submit-bar-info { font-size: 0.78rem; color: #94a3b8; }
.btn-save {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.65rem 1.5rem;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(30,179,73,0.3);
    transition: all 0.2s;
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(30,179,73,0.4); }
.btn-save:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

@keyframes spin { to { transform: rotate(360deg); } }
.spinner {
    display: inline-block;
    width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,0.4);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-grid.cols-3 { grid-template-columns: 1fr 1fr; }
    .banner-grid { grid-template-columns: 1fr; }
    .avatar-section { flex-direction: column; text-align: center; }
    .tab-btn span.tab-label { display: none; }
    .submit-bar { flex-direction: column; gap: 0.75rem; }
}
@media (max-width: 480px) {
    .form-grid.cols-3 { grid-template-columns: 1fr; }
    .pg-title { font-size: 1.3rem; }
}
</style>
@endsection

@section('content')

<div class="pg-header">
    <h1 class="pg-title">Profil & Pengaturan Toko</h1>
    <p class="pg-sub">Kelola identitas, media sosial, lokasi, dan metadata SEO toko Anda.</p>
</div>

@if(session('success'))
    <div class="alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('creator.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
    @csrf
    @method('PUT')

    {{-- ── Tab Navigation ───────────────────────────────────────────── --}}
    <div class="tab-nav">
        <button type="button" class="tab-btn active" data-tab="tab-identity">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span class="tab-label">Identitas Toko</span>
        </button>
        <button type="button" class="tab-btn" data-tab="tab-media">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span class="tab-label">Foto & Banner</span>
        </button>
        <button type="button" class="tab-btn" data-tab="tab-social">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            <span class="tab-label">Sosial Media</span>
        </button>
        <button type="button" class="tab-btn" data-tab="tab-location">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span class="tab-label">Lokasi</span>
        </button>
        <button type="button" class="tab-btn" data-tab="tab-seo">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <span class="tab-label">SEO</span>
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- TAB 1: IDENTITAS TOKO                                         --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane active" id="tab-identity">
        <div class="prof-card">
            <div class="prof-card-head">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Informasi Dasar Toko
            </div>
            <div class="prof-card-body">
                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label">Nama Creator / Toko</label>
                        <input type="text" name="store_name" value="{{ old('store_name', $profile->store_name) }}" class="form-input" placeholder="Misal: HVM Digital Studio">
                        @error('store_name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Slug URL Toko</label>
                        <div class="slug-wrap">
                            <span class="slug-prefix">buyle.id/c/</span>
                            <input type="text" name="store_slug" class="slug-input" value="{{ old('store_slug', $profile->store_slug) }}" placeholder="nama-toko-anda">
                        </div>
                        <span class="form-hint">Hanya huruf kecil, angka, dan strip (-). Kosongkan untuk auto-generate dari nama toko.</span>
                        @error('store_slug')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Tipe / Peran Creator</label>
                        @php
                            $creatorTypes = [
                                'Content Creator'         => 'Content Creator (Video, Edukasi & Lifestyle)',
                                'Affiliate Marketer'      => 'Affiliate Marketer / Affiliator Digital',
                                'Graphic & UI/UX Designer'=> 'Graphic, UI/UX & Visual Designer',
                                'Video Editor & Motion'   => 'Video Editor, Animator & 3D Artist',
                                'Software Developer'      => 'Software Developer / Programmer & Web Creator',
                                'Course Creator'          => 'Course Creator & Instruktur / Mentor Online',
                                'Copywriter & Writer'     => 'Copywriter, Penulis & Prompt Engineer',
                                'Digital Marketer'        => 'Digital Marketer & Agency Kreatif',
                                'Konsultan & Coach'       => 'Konsultan Bisnis / Digital Coach',
                                'Kreator Template & Aset' => 'Kreator Template (Notion, Canva, Figma, Spreadsheet, dll)',
                                'Fotografer & Videografer'=> 'Fotografer & Kreator Media Visual',
                                'Audio & Music Producer'  => 'Musisi, Sound Engineer & Audio Creator',
                                'Supplier / Vendor Digital'=> 'Supplier / Vendor Produk Digital',
                                'Lainnya'                 => 'Lainnya / General Digital Creator',
                            ];
                            $selectedType = old('creator_type', $profile->creator_type ?? '');
                        @endphp
                        <select name="creator_type" class="form-input">
                            <option value="">— Pilih Kategori Creator —</option>
                            @foreach($creatorTypes as $key => $label)
                                <option value="{{ $key }}" {{ $selectedType === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('creator_type')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Deskripsi Toko</label>
                        <textarea name="store_description" class="form-input" rows="4" placeholder="Ceritakan tentang toko Anda, spesialisasi, dan produk unggulan...">{{ old('store_description', $profile->store_description) }}</textarea>
                        <span class="form-hint">Maksimal 500 karakter. Ditampilkan di halaman profil publik Anda.</span>
                        @error('store_description')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- TAB 2: FOTO & BANNER                                          --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane" id="tab-media">
        <div class="prof-card">
            <div class="prof-card-head">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Foto Profil
            </div>
            <div class="prof-card-body">
                <div class="avatar-section">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="avatar-img">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="avatar-info">
                        <h4>{{ auth()->user()->name }}</h4>
                        <p>Foto digunakan sebagai identitas di seluruh platform buyle.id</p>
                        <input type="file" name="avatar" class="form-input" style="height:auto;padding:0.5rem;" accept="image/*">
                    </div>
                </div>
                <span class="form-hint">Rekomendasi: 400×400 px. Maks 10MB. Dikonversi otomatis ke WebP.</span>
                @error('avatar')<div class="form-error" style="margin-top:0.35rem;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="prof-card">
            <div class="prof-card-head">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                Banner Toko (Maks 2 Slide)
            </div>
            <div class="prof-card-body">
                <div class="banner-grid">
                    <div class="banner-slot">
                        <div class="banner-label">🖼 Slide 1 <span style="font-weight:400;color:#94a3b8;">(Utama)</span></div>
                        @if($profile->store_banner_1)
                            <img src="{{ asset('storage/' . $profile->store_banner_1) }}" class="banner-preview">
                        @endif
                        <input type="file" name="store_banner_1" class="form-input" style="height:auto;padding:0.5rem;font-size:0.75rem;" accept="image/*">
                    </div>
                    <div class="banner-slot">
                        <div class="banner-label">🖼 Slide 2 <span style="font-weight:400;color:#94a3b8;">(Opsional)</span></div>
                        @if($profile->store_banner_2)
                            <img src="{{ asset('storage/' . $profile->store_banner_2) }}" class="banner-preview">
                        @endif
                        <input type="file" name="store_banner_2" class="form-input" style="height:auto;padding:0.5rem;font-size:0.75rem;" accept="image/*">
                    </div>
                </div>
                <span class="form-hint" style="display:block;margin-top:0.75rem;">Rekomendasi: 1200×600 px (rasio 2:1). Maks 10MB per slide. Dikonversi otomatis ke WebP.</span>
                @error('store_banner_1')<div class="form-error">{{ $message }}</div>@enderror
                @error('store_banner_2')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- TAB 3: SOSIAL MEDIA                                           --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane" id="tab-social">
        @php $socials = old('social_links', $profile->social_links ?? []); @endphp

        <div class="prof-card">
            <div class="prof-card-head">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                Akun Sosial Media
            </div>
            <div class="prof-card-body">
                <div class="form-grid">
                    {{-- Instagram --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#E1306C" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                            Instagram
                        </label>
                        <div class="social-wrap">
                            <span class="social-prefix">instagram.com/</span>
                            <input type="text" name="social_links[instagram]" value="{{ $socials['instagram'] ?? '' }}" class="social-field" placeholder="username_anda">
                        </div>
                    </div>

                    {{-- TikTok --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.86 4.43 6.2 6.2 0 0 0 1.91-4.42V8.92a8.28 8.28 0 0 0 4.82 1.55v-3.47a4.91 4.91 0 0 1-1-.31z"/></svg>
                            TikTok
                        </label>
                        <div class="social-wrap">
                            <span class="social-prefix">tiktok.com/@</span>
                            <input type="text" name="social_links[tiktok]" value="{{ $socials['tiktok'] ?? '' }}" class="social-field" placeholder="username_anda">
                        </div>
                    </div>

                    {{-- YouTube --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#FF0000" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
                            YouTube
                        </label>
                        <input type="text" name="social_links[youtube]" value="{{ $socials['youtube'] ?? '' }}" class="form-input" placeholder="https://youtube.com/@channel">
                    </div>

                    {{-- X / Twitter --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            X / Twitter
                        </label>
                        <input type="text" name="social_links[x]" value="{{ $socials['x'] ?? '' }}" class="form-input" placeholder="https://x.com/username">
                    </div>

                    {{-- LinkedIn --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0A66C2" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                            LinkedIn
                        </label>
                        <input type="text" name="social_links[linkedin]" value="{{ $socials['linkedin'] ?? '' }}" class="form-input" placeholder="https://linkedin.com/in/username">
                    </div>

                    {{-- Facebook --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1877F2" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            Facebook
                        </label>
                        <input type="text" name="social_links[facebook]" value="{{ $socials['facebook'] ?? '' }}" class="form-input" placeholder="https://facebook.com/username">
                    </div>

                    {{-- Website --}}
                    <div class="form-group full">
                        <label class="form-label">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1eb349" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            Website / Portofolio / Linktree
                        </label>
                        <input type="url" name="social_links[website]" value="{{ $socials['website'] ?? '' }}" class="form-input" placeholder="https://portofolio-anda.com">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- TAB 4: LOKASI                                                 --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane" id="tab-location">
        <div class="prof-card">
            <div class="prof-card-head">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Lokasi & Alamat Toko
            </div>
            <div class="prof-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Provinsi</label>
                        <select name="province_id" id="province" class="form-input">
                            <option value="">— Pilih Provinsi —</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kabupaten / Kota</label>
                        <select name="city_id" id="city" class="form-input" disabled>
                            <option value="">— Pilih Kabupaten/Kota —</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Kecamatan</label>
                        <select name="subdistrict_id" id="subdistrict" class="form-input" disabled>
                            <option value="">— Pilih Kecamatan —</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-input" rows="3" placeholder="Nama jalan, gedung, nomor rumah/ruko...">{{ old('address', $profile->address) }}</textarea>
                    </div>

                    <input type="hidden" id="provId_val" value="{{ old('province_id', $profile->province_id) }}">
                    <input type="hidden" id="cityId_val" value="{{ old('city_id', $profile->city_id) }}">
                    <input type="hidden" id="distId_val" value="{{ old('subdistrict_id', $profile->subdistrict_id) }}">
                    <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $profile->province_name) }}">
                    <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name', $profile->city_name) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- TAB 5: SEO                                                    --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane" id="tab-seo">
        <div class="prof-card">
            <div class="prof-card-head">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Metadata SEO Toko
            </div>
            <div class="prof-card-body">
                <div class="form-group" style="margin-bottom:1.1rem;">
                    <label class="form-label">Meta Title <span style="font-weight:400;color:#94a3b8;">(maks 60 karakter)</span></label>
                    <input type="text" name="meta_title" id="metaTitle" value="{{ old('meta_title', $profile->meta_title) }}" class="form-input" placeholder="Nama Toko | Buyle.id" maxlength="70">
                    <span class="form-hint">Kosongkan untuk fallback ke nama Creator.</span>
                </div>
                <div class="form-group" style="margin-bottom:1.1rem;">
                    <label class="form-label">Meta Description <span style="font-weight:400;color:#94a3b8;">(maks 160 karakter)</span></label>
                    <textarea name="meta_desc" id="metaDesc" class="form-input" rows="3" placeholder="Deskripsi singkat toko Anda yang muncul di hasil pencarian Google..." maxlength="160">{{ old('meta_desc', $profile->meta_desc) }}</textarea>
                    <span class="form-hint">Kosongkan untuk fallback ke deskripsi toko.</span>
                </div>
                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $profile->meta_keywords) }}" class="form-input" placeholder="template canva, desain grafis, jasa cv, ...">
                    <span class="form-hint">Pisahkan dengan koma.</span>
                </div>

                {{-- Live SEO Preview --}}
                <div class="prof-card-head" style="border-radius:10px 10px 0 0; margin: 0 -1.5rem; padding: 0.75rem 1.5rem; border-top: 1px solid #f1f5f9;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Preview di Google
                </div>
                <div class="seo-preview" style="border-radius:0 0 12px 12px; border-top:none; margin: 0 -1.5rem -1.5rem; padding: 1rem 1.5rem;">
                    <div class="seo-preview-url">buyle.id/c/{{ $profile->store_slug ?? 'nama-toko-anda' }}</div>
                    <div class="seo-preview-title" id="seoPreviewTitle">{{ $profile->meta_title ?? $profile->store_name ?? 'Nama Toko Anda' }}</div>
                    <div class="seo-preview-desc" id="seoPreviewDesc">{{ $profile->meta_desc ?? $profile->store_description ?? 'Deskripsi toko Anda akan muncul di sini.' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Submit Bar ────────────────────────────────────────────────── --}}
    <div class="submit-bar">
        <span class="submit-bar-info">Semua perubahan disimpan secara bersamaan.</span>
        <button type="submit" class="btn-save" id="btnSave">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
            Simpan Perubahan
        </button>
    </div>

</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Tab switching ──────────────────────────────────────────────────
    const tabBtns  = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    // Restore active tab from hash or localStorage
    const savedTab = localStorage.getItem('prof_active_tab') || 'tab-identity';
    activateTab(savedTab);

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            activateTab(this.dataset.tab);
            localStorage.setItem('prof_active_tab', this.dataset.tab);
        });
    });

    function activateTab(id) {
        tabBtns.forEach(b => b.classList.toggle('active', b.dataset.tab === id));
        tabPanes.forEach(p => p.classList.toggle('active', p.id === id));
    }

    // ── SEO Live Preview ───────────────────────────────────────────────
    const titleInput = document.getElementById('metaTitle');
    const descInput  = document.getElementById('metaDesc');
    const prevTitle  = document.getElementById('seoPreviewTitle');
    const prevDesc   = document.getElementById('seoPreviewDesc');

    function updateSeoPreview() {
        if (titleInput && prevTitle) {
            prevTitle.textContent = titleInput.value || 'Nama Toko Anda';
        }
        if (descInput && prevDesc) {
            prevDesc.textContent = descInput.value || 'Deskripsi toko Anda akan muncul di sini.';
        }
    }
    titleInput?.addEventListener('input', updateSeoPreview);
    descInput?.addEventListener('input', updateSeoPreview);

    // ── Wilayah API ────────────────────────────────────────────────────
    const apiBase  = 'https://www.emsifa.com/api-wilayah-indonesia/api';
    const provSel  = document.getElementById('province');
    const citySel  = document.getElementById('city');
    const distSel  = document.getElementById('subdistrict');
    const selProv  = document.getElementById('provId_val').value;
    const selCity  = document.getElementById('cityId_val').value;
    const selDist  = document.getElementById('distId_val').value;

    fetch(`${apiBase}/provinces.json`)
        .then(r => r.json())
        .then(data => {
            data.forEach(p => {
                const opt = new Option(p.name, p.id);
                if (p.id == selProv) { opt.selected = true; }
                provSel.add(opt);
            });
            if (selProv) loadCities(selProv, selCity);
        });

    provSel.addEventListener('change', function () {
        citySel.innerHTML = '<option value="">— Pilih Kabupaten/Kota —</option>';
        distSel.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
        citySel.disabled = distSel.disabled = true;
        document.getElementById('province_name').value = this.selectedIndex > 0 ? this.options[this.selectedIndex].text : '';
        document.getElementById('city_name').value = '';
        if (this.value) loadCities(this.value);
    });

    citySel.addEventListener('change', function () {
        distSel.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
        distSel.disabled = true;
        document.getElementById('city_name').value = this.selectedIndex > 0 ? this.options[this.selectedIndex].text : '';
        if (this.value) loadDistricts(this.value);
    });

    function loadCities(provId, selectedId = null) {
        fetch(`${apiBase}/regencies/${provId}.json`).then(r => r.json()).then(data => {
            citySel.innerHTML = '<option value="">— Pilih Kabupaten/Kota —</option>';
            data.forEach(c => {
                const opt = new Option(c.name, c.id);
                if (c.id == selectedId) {
                    opt.selected = true;
                    if (!document.getElementById('city_name').value) document.getElementById('city_name').value = c.name;
                }
                citySel.add(opt);
            });
            citySel.disabled = false;
            if (selectedId && selDist) loadDistricts(selectedId, selDist);
        });
    }

    function loadDistricts(cityId, selectedId = null) {
        fetch(`${apiBase}/districts/${cityId}.json`).then(r => r.json()).then(data => {
            distSel.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
            data.forEach(d => {
                const opt = new Option(d.name, d.id);
                if (d.id == selectedId) opt.selected = true;
                distSel.add(opt);
            });
            distSel.disabled = false;
        });
    }

    // ── Image Compression on Submit ────────────────────────────────────
    const form    = document.getElementById('profileForm');
    const saveBtn = document.getElementById('btnSave');

    async function compressImage(file, maxW = 1200, maxH = 1200) {
        if (!file.type.match(/image.*/) || file.type === 'image/webp') return file;
        return new Promise(resolve => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = new Image();
                img.onload = () => {
                    let w = img.width, h = img.height;
                    const ratio = Math.min(maxW / w, maxH / h);
                    if (ratio < 1) { w *= ratio; h *= ratio; }
                    const canvas = document.createElement('canvas');
                    canvas.width = w; canvas.height = h;
                    canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                    canvas.toBlob(blob => {
                        resolve(new File([blob], file.name.replace(/\.[^/.]+$/, '.webp'), { type: 'image/webp', lastModified: Date.now() }));
                    }, 'image/webp', 0.85);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner"></span> Menyimpan...';

        try {
            const slots = [
                { input: 'avatar',        maxW: 800,  maxH: 800  },
                { input: 'store_banner_1', maxW: 1200, maxH: 600  },
                { input: 'store_banner_2', maxW: 1200, maxH: 600  },
            ];
            for (const slot of slots) {
                const el = form.querySelector(`input[name="${slot.input}"]`);
                if (el?.files[0]) {
                    const compressed = await compressImage(el.files[0], slot.maxW, slot.maxH);
                    const dt = new DataTransfer();
                    dt.items.add(compressed);
                    el.files = dt.files;
                }
            }
            form.submit();
        } catch (err) {
            console.error(err);
            form.submit();
        }
    });

});
</script>
@endsection