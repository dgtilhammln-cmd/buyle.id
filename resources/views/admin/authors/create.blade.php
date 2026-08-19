@extends('layouts.admin')
@section('title', isset($author) ? 'Edit Penulis' : 'Tambah Penulis')
@section('page-title', isset($author) ? 'Edit Penulis' : 'Tambah Penulis Baru')
@section('content')
@php
    $isEdit = isset($author);
    $actionUrl = $isEdit ? route('admin.authors.update', $author->id) : route('admin.authors.store');
    
    $locales = ['id', 'en', 'ar', 'ko'];
    $localeLabels = [
        'id' => 'Indonesia',
        'en' => 'English',
        'ar' => 'العربية',
        'ko' => '한국어',
    ];
    $activeLang = old('_active_lang', 'id');
@endphp

<style>
/* ===== Premium Form Styles ===== */
.premium-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    border: 1px solid #E2E8F0;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
}
.premium-card-header {
    font-size: 0.85rem;
    font-weight: 700;
    color: #1E293B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0 0 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #F1F5F9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.form-group { margin-bottom: 1.25rem; }
.form-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.5rem;
}
.form-label span.req { color: #EF4444; }
.form-label span.hint { font-weight: 400; color: #94A3B8; font-size: 0.75rem; margin-left: 0.25rem; }
.form-input, .form-select, .form-textarea {
    width: 100%;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    color: #1E293B;
    background: #F8FAFC;
    transition: all 0.2s;
    outline: none;
    font-family: inherit;
    box-sizing: border-box;
}
.form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: #3B82F6;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
}
.form-textarea { resize: vertical; min-height: 80px; }

/* ===== Language Tab Switcher ===== */
.lang-tabs {
    display: flex;
    gap: 0.35rem;
    background: #F1F5F9;
    border-radius: 12px;
    padding: 0.35rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.lang-tab {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: none;
    background: transparent;
    font-size: 0.82rem;
    font-weight: 600;
    color: #64748B;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    white-space: nowrap;
}
.lang-tab:hover { background: #fff; color: #1E293B; }
.lang-tab.active {
    background: #fff;
    color: #3B82F6;
    box-shadow: 0 2px 8px rgba(59,130,246,0.12);
}
.lang-tab .lang-badge {
    font-size: 0.7rem;
    padding: 0.1rem 0.4rem;
    border-radius: 4px;
    background: #E2E8F0;
    color: #64748B;
    font-weight: 700;
}
.lang-tab.active .lang-badge { background: #DBEAFE; color: #3B82F6; }

/* Language panel */
.lang-panel { display: none; }
.lang-panel.active { display: block; }

/* Buttons */
.btn-primary-new {
    background: #3B82F6; color: #fff; border: none; padding: 0.875rem 1.5rem;
    border-radius: 12px; font-weight: 700; font-size: 0.9rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    transition: all 0.2s; box-shadow: 0 4px 14px rgba(59,130,246,0.3); width: 100%;
}
.btn-primary-new:hover { background: #2563EB; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(59,130,246,0.4); }
.btn-outline-new {
    background: #fff; color: #64748B; border: 1.5px solid #E2E8F0; padding: 0.875rem 1.5rem;
    border-radius: 12px; font-weight: 600; font-size: 0.9rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    transition: all 0.2s; width: 100%; text-decoration: none;
}
.btn-outline-new:hover { background: #F8FAFC; color: #1E293B; border-color: #CBD5E1; }
</style>

<div style="max-width:1080px; margin:0 auto;">
    {{-- PAGE HEADER --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">{{ $isEdit ? 'Edit Penulis' : 'Tambah Penulis Baru' }}</h1>
            <p style="font-size:.875rem;color:#94A3B8;margin:0;">Isi bio penulis dalam berbagai bahasa menggunakan tab di bawah.</p>
        </div>
        <a href="{{ route('admin.authors.index') }}" class="btn-outline-new" style="width:auto;padding:.5rem 1rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
    <div style="background:#FEF2F2;border:1px solid #FCA5A5;border-radius:12px;padding:1rem 1.5rem;margin-bottom:1.5rem;">
        <div style="display:flex;align-items:center;gap:.5rem;color:#DC2626;font-weight:700;font-size:.9rem;margin-bottom:.5rem;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Ada kesalahan pada input:
        </div>
        <ul style="margin:0;padding-left:1.5rem;color:#DC2626;font-size:.85rem;">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ $actionUrl }}" enctype="multipart/form-data" id="author-form">
    @csrf @if($isEdit) @method('PUT') @endif
    <input type="hidden" name="_active_lang" id="active-lang-input" value="{{ $activeLang }}">

    <div style="display:grid;grid-template-columns:minmax(0,2fr) minmax(0,1fr);gap:1.5rem;align-items:start;">
        
        {{-- LEFT COLUMN --}}
        <div>
            <div class="premium-card">
                <div class="premium-card-header">Info Dasar</div>
                
                <div class="form-group">
                    <label class="form-label">Nama Penulis <span class="req">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $author->name ?? '') }}" required placeholder="Contoh: Budi Santoso">
                </div>

                <div class="form-group">
                    <label class="form-label">URL Slug <span class="hint">(Opsional, auto-generate dari nama)</span></label>
                    <div style="display:flex;align-items:center;">
                        <span style="background:#F1F5F9;border:1.5px solid #E2E8F0;border-right:none;border-radius:10px 0 0 10px;padding:0.75rem 1rem;color:#94A3B8;font-size:0.9rem;">/author/</span>
                        <input type="text" name="slug" class="form-input" style="border-radius:0 10px 10px 0;" value="{{ old('slug', $author->slug ?? '') }}" placeholder="budi-santoso">
                    </div>
                </div>
            </div>

            <div class="premium-card">
                <div class="premium-card-header">Bio Multi-Bahasa</div>
                
                <div class="lang-tabs" id="lang-tabs">
                    @foreach($locales as $lc)
                    <button type="button" class="lang-tab {{ $activeLang === $lc ? 'active' : '' }}" onclick="switchLangTab('{{ $lc }}')">
                        <span class="lang-badge">{{ strtoupper($lc) }}</span>
                        {{ $localeLabels[$lc] }}
                    </button>
                    @endforeach
                </div>

                @foreach($locales as $lc)
                @php
                    $t = $isEdit ? ($translations[$lc] ?? null) : null;
                @endphp
                <div class="lang-panel {{ $activeLang === $lc ? 'active' : '' }}" id="panel-{{ $lc }}">
                    <div class="form-group">
                        <label class="form-label">Biografi Pendek ({{ strtoupper($lc) }})</label>
                        <textarea name="translations[{{ $lc }}][bio]" class="form-textarea" placeholder="Tulis biografi singkat penulis ini dalam bahasa {{ $localeLabels[$lc] }}...">{{ old("translations.{$lc}.bio", $t?->bio) }}</textarea>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="premium-card">
                <div class="premium-card-header">Social Links <span style="font-weight:400;color:#94A3B8;text-transform:none;">(Opsional)</span></div>
                <div class="form-group">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="url" name="social_links[linkedin]" class="form-input" value="{{ old('social_links.linkedin', $author->social_links['linkedin'] ?? '') }}" placeholder="https://linkedin.com/in/username">
                </div>
                <div class="form-group">
                    <label class="form-label">Twitter/X URL</label>
                    <input type="url" name="social_links[twitter]" class="form-input" value="{{ old('social_links.twitter', $author->social_links['twitter'] ?? '') }}" placeholder="https://twitter.com/username">
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div>
            <div class="premium-card" style="position:sticky;top:90px;">
                <div class="premium-card-header">Foto Profil</div>
                
                <div style="text-align:center;margin-bottom:1.25rem;">
                    @if($isEdit && $author->getRawOriginal('photo'))
                        <img src="{{ asset('storage/'.$author->getRawOriginal('photo')) }}" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #E2E8F0;box-shadow:0 4px 12px rgba(0,0,0,0.05);margin-bottom:1rem;">
                    @else
                        <div style="width:120px;height:120px;border-radius:50%;background:#F1F5F9;border:3px dashed #CBD5E1;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:#94A3B8;">
                            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*" class="form-input" style="font-size:.8rem;padding:.5rem;">
                    <div style="font-size:.75rem;color:#94A3B8;margin-top:.5rem;">Disarankan format square 400x400px. Gambar akan otomatis dikonversi ke WebP.</div>
                </div>

                <div style="margin-top:1.5rem;">
                    <button type="submit" class="btn-primary-new">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Penulis
                    </button>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<script>
function switchLangTab(lc) {
    document.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.lang-panel').forEach(p => p.classList.remove('active'));
    
    document.querySelector(`.lang-tab[onclick="switchLangTab('${lc}')"]`).classList.add('active');
    document.getElementById(`panel-${lc}`).classList.add('active');
    
    document.getElementById('active-lang-input').value = lc;
}
</script>
@endsection
