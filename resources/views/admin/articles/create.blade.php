@extends('layouts.admin')
@section('title', isset($article) ? 'Edit Artikel' : 'Tulis Artikel')
@section('page-title', isset($article) ? 'Edit Artikel' : 'Tulis Artikel Baru')
@section('content')
@php
    $a = $article ?? null;
    $t = $a ? ($translations['id'] ?? null) : null;
@endphp

<style>
.premium-card {
    background: #fff; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    border: 1px solid #E2E8F0; padding: 1.5rem; margin-bottom: 1.5rem;
}
.premium-card-header {
    font-size: 0.85rem; font-weight: 700; color: #1E293B; text-transform: uppercase;
    letter-spacing: 0.05em; margin: 0 0 1.25rem; padding-bottom: 0.75rem;
    border-bottom: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center;
}
.form-group { margin-bottom: 1.25rem; }
.form-label { display: block; font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 0.5rem; }
.form-label span.req { color: #EF4444; }
.form-label span.hint { font-weight: 400; color: #94A3B8; font-size: 0.75rem; margin-left: 0.25rem; }
.form-input, .form-select, .form-textarea {
    width: 100%; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 0.75rem 1rem;
    font-size: 0.9rem; color: #1E293B; background: #F8FAFC; transition: all 0.2s;
    outline: none; font-family: inherit; box-sizing: border-box;
}
.form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: #1eb349; background: #fff; box-shadow: 0 0 0 4px rgba(30,179,73,0.1);
}
.form-textarea { resize: vertical; min-height: 85px; }
.char-count { font-size: 0.75rem; color: #94A3B8; margin-top: 0.35rem; text-align: right; }

/* CMS TABS STYLING */
.cms-tabs-nav {
    display: flex; gap: 1.5rem; border-bottom: 2px solid #E2E8F0;
    margin-bottom: 1.5rem; padding-bottom: 0.25rem;
}
.cms-tab-btn {
    background: none; border: none; padding: 0.65rem 0.25rem; font-size: 0.95rem;
    font-weight: 700; color: #64748B; cursor: pointer; position: relative;
    transition: all 0.2s; outline: none; font-family: inherit;
}
.cms-tab-btn:hover { color: #1E293B; }
.cms-tab-btn.active { color: #1eb349; }
.cms-tab-btn.active::after {
    content: ''; position: absolute; bottom: -0.35rem; left: 0;
    width: 100%; height: 3px; background: #1eb349; border-radius: 3px 3px 0 0;
}
.cms-tab-pane { display: none; }
.cms-tab-pane.active { display: block; }

/* PROFESSIONAL TEXT EDITOR STYLING */
.editor-wrapper {
    border: 1.5px solid #CBD5E1; border-radius: 14px; overflow: hidden; background: #fff;
    box-shadow: 0 4px 16px rgba(0,0,0,0.03); transition: border-color 0.2s;
}
.editor-wrapper:focus-within { border-color: #1eb349; box-shadow: 0 0 0 4px rgba(30,179,73,0.1); }
.editor-toolbar {
    background: #FAFAFA; border-bottom: 1.5px solid #E2E8F0; padding: 0.65rem 0.85rem;
    display: flex; flex-wrap: wrap; align-items: center; gap: 0.4rem;
}
.editor-toolbar-sep { width: 1px; height: 22px; background: #CBD5E1; margin: 0 0.25rem; }
.editor-btn {
    padding: 0.4rem 0.65rem; background: #fff; border: 1px solid #E2E8F0; color: #334155;
    border-radius: 8px; cursor: pointer; font-size: 0.825rem; font-weight: 700; transition: all 0.15s;
    display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px;
    box-sizing: border-box;
}
.editor-btn:hover { background: #F1F5F9; color: #0F172A; border-color: #94A3B8; }
.editor-btn.active { background: #E2E8F0; color: #0F172A; border-color: #64748B; }

.editor-select {
    padding: 0.35rem 0.5rem; background: #fff; border: 1px solid #E2E8F0; color: #334155;
    border-radius: 8px; font-size: 0.825rem; font-weight: 600; outline: none; cursor: pointer; height: 32px;
}
.editor-select:hover { border-color: #94A3B8; }

.editor-mode-toggle {
    display: flex; background: #E2E8F0; padding: 3px; border-radius: 10px; gap: 2px;
}
.editor-mode-btn {
    border: none; background: transparent; padding: 0.35rem 0.75rem; border-radius: 8px;
    font-size: 0.785rem; font-weight: 700; color: #64748B; cursor: pointer; transition: all 0.2s;
    display: inline-flex; align-items: center; gap: 0.35rem; font-family: inherit;
}
.editor-mode-btn.active { background: #fff; color: #0F172A; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }

/* VISUAL EDITOR SPACING FIXES */
.editor-area {
    min-height: 380px; padding: 1.5rem; font-size: 0.975rem; line-height: 1.85; color: #1E293B;
    background: #fff; outline: none; overflow-y: auto; max-height: 600px;
}
.editor-area p { margin-top: 0; margin-bottom: 1.25rem; line-height: 1.85; }
.editor-area h2 { font-size: 1.5rem; font-weight: 800; color: #0F172A; margin-top: 1.75rem; margin-bottom: 0.85rem; line-height: 1.35; }
.editor-area h3 { font-size: 1.25rem; font-weight: 700; color: #0F172A; margin-top: 1.5rem; margin-bottom: 0.75rem; line-height: 1.4; }
.editor-area h4 { font-size: 1.1rem; font-weight: 700; color: #0F172A; margin-top: 1.25rem; margin-bottom: 0.65rem; line-height: 1.45; }
.editor-area ul, .editor-area ol { margin-top: 0; margin-bottom: 1.25rem; padding-left: 1.5rem; }
.editor-area li { margin-bottom: 0.5rem; line-height: 1.75; }
.editor-area blockquote {
    margin: 1.25rem 0; padding: 0.85rem 1.25rem; border-left: 4px solid #1eb349;
    background: #F8FAFC; color: #475569; font-style: italic; border-radius: 0 8px 8px 0;
}

.editor-area[contenteditable="true"]:empty:before {
    content: attr(placeholder); color: #94A3B8; font-style: italic; pointer-events: none;
}

/* LIGHT THEME FOR HTML/CODE EDITOR */
.editor-code-area {
    display: none; width: 100%; min-height: 380px; padding: 1.25rem; color: #0F172A;
    font-size: 0.9rem; line-height: 1.7; font-family: 'Fira Code', Consolas, Monaco, monospace;
    background: #F8FAFC; border: 1.5px solid #E2E8F0; border-top: none;
    outline: none; resize: vertical; box-sizing: border-box; border-radius: 0 0 14px 14px;
}

.img-preview { width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: 10px; margin-bottom: 0.75rem; border: 1px solid #E2E8F0; }
.btn-primary-new {
    background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; border: none; padding: 0.65rem 1.5rem; border-radius: 10px;
    font-weight: 700; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center;
    justify-content: center; gap: 0.5rem; transition: all 0.2s; box-shadow: 0 3px 10px rgba(30,179,73,0.25); text-decoration: none;
}
.btn-primary-new:hover { background: #1eb349; transform: translateY(-1px); }
.btn-outline-new {
    background: #fff; color: #64748B; border: 1.5px solid #E2E8F0; padding: 0.65rem 1.25rem;
    border-radius: 10px; font-weight: 600; font-size: 0.875rem; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
    transition: all 0.2s; text-decoration: none;
}
.btn-outline-new:hover { background: #F8FAFC; color: #1E293B; }
.switch-label { display: flex; align-items: center; gap: 0.75rem; cursor: pointer; }
.switch-input { width: 20px; height: 20px; accent-color: #1eb349; cursor: pointer; }
.switch-text { font-size: 0.9rem; font-weight: 600; color: #334155; }
</style>

<div style="max-width:1080px; margin:0 auto;">

    <form method="POST" action="{{ $a ? route('admin.articles.update',$a) : route('admin.articles.store') }}" enctype="multipart/form-data" id="article-form">
    @csrf @if($a) @method('PUT') @endif

    {{-- TOP HEADER WITH TITLE AND UNIFIED ACTION BUTTONS --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">{{ $a ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</h1>
            <p style="font-size:.875rem;color:#94A3B8;margin:0;">Kelola konten artikel, media, tags, dan optimasi SEO.</p>
        </div>
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <a href="{{ route('admin.articles.index') }}" class="btn-outline-new">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <button type="submit" class="btn-primary-new">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                {{ $a ? 'Simpan Perubahan' : 'Publish Artikel' }}
            </button>
        </div>
    </div>

    {{-- CMS TABS NAVIGATION --}}
    <div class="cms-tabs-nav">
        <button type="button" class="cms-tab-btn active" data-tab="tab-content">Content</button>
        <button type="button" class="cms-tab-btn" data-tab="tab-media">Media</button>
        <button type="button" class="cms-tab-btn" data-tab="tab-tags">Tags & Status</button>
        <button type="button" class="cms-tab-btn" data-tab="tab-seo">SEO</button>
    </div>

    <div id="validation-alert" style="display:none;background:#FEF2F2;border:1.5px solid #FCA5A5;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#991B1B;font-size:.875rem;line-height:1.6;"></div>

    {{-- FULL WIDTH CONTENT CONTAINER --}}
    <div>
        {{-- TAB 1: CONTENT --}}
        <div id="tab-content" class="cms-tab-pane active">
            <div class="premium-card">
                <h3 class="premium-card-header">Detail Artikel <span style="color:#EF4444;font-size:.75rem;font-weight:600;text-transform:none;">Wajib diisi</span></h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Judul Artikel <span class="req">*</span></label>
                        <input type="text" name="translations[id][title]" id="art-title-id"
                            value="{{ old('translations.id.title', $t?->title) }}"
                            class="form-input" required oninput="autoSlug()" placeholder="Judul artikel yang menarik...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Slug (URL) <span class="hint">Otomatis dari judul jika kosong.</span></label>
                        <div style="display:flex;align-items:center;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:10px;padding:0 1rem;overflow:hidden;">
                            <span style="font-size:.85rem;color:#94A3B8;white-space:nowrap;">/artikel/</span>
                            <input type="text" name="slug" id="art-slug" value="{{ old('slug',$a?->slug) }}"
                                style="border:none;background:transparent;padding:0.75rem 0;width:100%;font-size:.9rem;color:#1E293B;outline:none;" pattern="[a-z0-9\-]*" placeholder="auto-dari-judul">
                        </div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="category" value="{{ old('category',$a?->category) }}" class="form-input" placeholder="Tips & Panduan">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Penulis / Author</label>
                        <select name="author_id" class="form-select">
                            <option value="">— Pilih Penulis —</option>
                            @foreach($authors as $auth)
                                <option value="{{ $auth->id }}" {{ old('author_id', $a?->author_id) == $auth->id ? 'selected' : '' }}>{{ $auth->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Excerpt / Ringkasan <span class="hint">(max 500 karakter)</span></label>
                    <textarea name="translations[id][excerpt]" class="form-textarea" rows="2" maxlength="500"
                        oninput="document.getElementById('exc-cnt').textContent=this.value.length"
                        placeholder="Ringkasan singkat artikel...">{{ old('translations.id.excerpt', $t?->excerpt) }}</textarea>
                    <div class="char-count"><span id="exc-cnt">{{ strlen(old('translations.id.excerpt', $t?->excerpt ?? '')) }}</span>/500</div>
                </div>
            </div>

            {{-- REVAMPED PROFESSIONAL EDITOR CARD --}}
            <div class="premium-card" style="padding:1.25rem 1.5rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                    <label class="form-label" style="margin:0;font-size:.9rem;">Konten Artikel <span class="req">*</span></label>
                    <div class="editor-mode-toggle">
                        <button type="button" class="editor-mode-btn active" id="btn-mode-visual" onclick="setEditorMode('visual')">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Visual Editor
                        </button>
                        <button type="button" class="editor-mode-btn" id="btn-mode-code" onclick="setEditorMode('code')">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                            HTML / Code
                        </button>
                    </div>
                </div>

                <div class="editor-wrapper">
                    {{-- Editor Toolbar --}}
                    <div class="editor-toolbar" id="editor-toolbar-bar">
                        <select class="editor-select" onchange="fmtBlock(this.value); this.value='';" title="Format Paragraf">
                            <option value="">Normal</option>
                            <option value="h2">Heading 2 (H2)</option>
                            <option value="h3">Heading 3 (H3)</option>
                            <option value="h4">Heading 4 (H4)</option>
                            <option value="blockquote">Kutipan (Blockquote)</option>
                        </select>

                        <div class="editor-toolbar-sep"></div>

                        <button type="button" class="editor-btn" onclick="fmt('bold')" title="Bold (Tebal)"><b>B</b></button>
                        <button type="button" class="editor-btn" onclick="fmt('italic')" title="Italic (Miring)"><i>I</i></button>
                        <button type="button" class="editor-btn" onclick="fmt('underline')" title="Underline (Garis Bawah)"><u>U</u></button>
                        <button type="button" class="editor-btn" onclick="fmt('strikeThrough')" title="Strikethrough (Coret)"><s>S</s></button>

                        <div class="editor-toolbar-sep"></div>

                        <button type="button" class="editor-btn" onclick="fmt('insertUnorderedList')" title="Bullet List">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        </button>
                        <button type="button" class="editor-btn" onclick="fmt('insertOrderedList')" title="Numbered List">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/></svg>
                        </button>
                        <button type="button" class="editor-btn" onclick="fmt('justifyLeft')" title="Rata Kiri">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                        </button>
                        <button type="button" class="editor-btn" onclick="fmt('justifyCenter')" title="Rata Tengah">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="10" x2="6" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="18" y1="18" x2="6" y2="18"/></svg>
                        </button>

                        <div class="editor-toolbar-sep"></div>

                        <button type="button" class="editor-btn" onclick="insertLink()" title="Sisipkan Link">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                        </button>
                        <button type="button" class="editor-btn" onclick="insertImgUrl()" title="Sisipkan Gambar">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </button>
                        <button type="button" class="editor-btn" onclick="insertTable()" title="Sisipkan Tabel">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/></svg>
                        </button>
                        <button type="button" class="editor-btn" onclick="fmt('removeFormat')" title="Hapus Format (Clear)">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 3L7 13l4 4 10-10-4-4z"/><path d="M3 21h18"/></svg>
                        </button>
                    </div>

                    {{-- Visual ContentEditable Area --}}
                    <div id="editor-id" class="editor-area" contenteditable="true" oninput="syncContent()" placeholder="Ketik isi artikel dengan profesional di sini...">{!! old('translations.id.content', $t?->content) !!}</div>

                    {{-- Code / HTML Textarea (LIGHT THEME) --}}
                    <textarea id="html-editor-id" name="translations[id][content]" class="editor-code-area" required>{{ old('translations.id.content', $t?->content) }}</textarea>
                </div>
            </div>
        </div>

        {{-- TAB 2: MEDIA --}}
        <div id="tab-media" class="cms-tab-pane">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                <div class="premium-card">
                    <h3 class="premium-card-header">Gambar Utama (Thumbnail) <span style="font-size:.72rem;font-weight:400;color:#94A3B8;text-transform:none;">1280x720 WebP</span></h3>
                    @if($a?->getRawOriginal('image'))
                        <img src="{{ asset('storage/'.$a->getRawOriginal('image')) }}" id="img-prev" class="img-preview">
                    @else
                        <img id="img-prev" class="img-preview" style="display:none;">
                    @endif
                    <input type="file" name="image" accept="image/*" class="form-input" style="padding:0.6rem;background:#fff;" onchange="previewImg(this,'img-prev')">
                    <p style="font-size:.75rem;color:#94A3B8;margin:.75rem 0 1.25rem;line-height:1.5;">Otomatis dikonversi ke format WebP 1280x720 landscape.</p>

                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Alt Text Gambar Utama <span class="hint">(Aksesibilitas & SEO Gambar)</span></label>
                        <input type="text" name="translations[id][thumbnail_alt]" value="{{ old('translations.id.thumbnail_alt', $t?->thumbnail_alt) }}" class="form-input" placeholder="Deskripsi teks gambar untuk Google Image & Screen Reader">
                    </div>
                </div>

                <div class="premium-card">
                    <h3 class="premium-card-header">OG Image (Social Media Preview) <span style="font-size:.72rem;font-weight:400;color:#94A3B8;text-transform:none;">1280x720 WebP</span></h3>
                    @if($a?->getRawOriginal('og_image'))
                        <img src="{{ asset('storage/'.$a->getRawOriginal('og_image')) }}" id="og-prev" class="img-preview">
                    @else
                        <img id="og-prev" class="img-preview" style="display:none;">
                    @endif
                    <input type="file" name="og_image" accept="image/*" class="form-input" style="padding:0.6rem;background:#fff;" onchange="previewImg(this,'og-prev')">
                    <p style="font-size:.75rem;color:#94A3B8;margin:.75rem 0 0;line-height:1.5;">Opsional. Jika kosong, gambar utama akan otomatis digunakan saat dibagikan ke WhatsApp / Facebook / X.</p>
                </div>
            </div>
        </div>

        {{-- TAB 3: TAGS & STATUS --}}
        <div id="tab-tags" class="cms-tab-pane">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                <div class="premium-card">
                    <h3 class="premium-card-header">Status Publikasi & Jadwal</h3>
                    <div class="form-group">
                        <label class="switch-label">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published',$a?->is_published ?? true) ? 'checked' : '' }} class="switch-input">
                            <span class="switch-text">Publish Sekarang</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Publish <span class="hint">(Otomatis sekarang jika dikosongkan)</span></label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', $a?->published_at?->format('Y-m-d\TH:i')) }}" class="form-input">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="switch-label">
                            <input type="hidden" name="show_toc" value="0">
                            <input type="checkbox" name="show_toc" value="1" {{ old('show_toc',$a?->show_toc ?? true) ? 'checked' : '' }} class="switch-input">
                            <span class="switch-text" style="font-size:.85rem;">Tampilkan Daftar Isi (Table of Contents)</span>
                        </label>
                    </div>
                </div>

                <div class="premium-card">
                    <h3 class="premium-card-header">Pengelompokan & Label (Tags)</h3>
                    <div class="form-group">
                        <label class="form-label">Tags <span class="hint">(Pisah dengan koma)</span></label>
                        <input type="text" name="tags" value="{{ old('tags', $a && $a->tags ? implode(', ',$a->tags) : '') }}" class="form-input" placeholder="dapur, tips, hemat, buyle">
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 4: SEO (CONTAINS FAQ SECTION NOW) --}}
        <div id="tab-seo" class="cms-tab-pane">
            <div class="premium-card">
                <h3 class="premium-card-header">Optimasi Mesin Pencari (SEO)</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Meta Title <span class="hint">(max 65 karakter)</span></label>
                        <input type="text" name="translations[id][meta_title]" value="{{ old('translations.id.meta_title', $t?->meta_title) }}" class="form-input" maxlength="65" oninput="document.getElementById('mt-cnt').textContent=this.value.length" placeholder="Otomatis dari judul jika dikosongkan">
                        <div class="char-count"><span id="mt-cnt">{{ strlen(old('translations.id.meta_title', $t?->meta_title ?? '')) }}</span>/65</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Keywords <span class="hint">(Pisah dengan koma)</span></label>
                        <input type="text" name="translations[id][meta_keywords]" value="{{ old('translations.id.meta_keywords', $t?->meta_keywords) }}" class="form-input" placeholder="buyle.id, peralatan rumah, tips hemat">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Description <span class="hint">(max 160 karakter)</span></label>
                    <textarea name="translations[id][meta_desc]" class="form-textarea" rows="2" maxlength="160" oninput="document.getElementById('md-cnt').textContent=this.value.length" placeholder="Deskripsi menarik yang akan tampil pada hasil pencarian Google...">{{ old('translations.id.meta_desc', $t?->meta_desc) }}</textarea>
                    <div class="char-count"><span id="md-cnt">{{ strlen(old('translations.id.meta_desc', $t?->meta_desc ?? '')) }}</span>/160</div>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Featured Snippet / Ringkasan Utama SEO <span class="hint">(Jawaban langsung untuk posisi #1 Google)</span></label>
                    <textarea name="translations[id][featured_snippet]" class="form-textarea" rows="2" placeholder="Tulis jawaban singkat & padat untuk target snippet Google...">{{ old('translations.id.featured_snippet', $t?->featured_snippet) }}</textarea>
                </div>
            </div>

            {{-- FAQ MOVED TO SEO TAB --}}
            <div class="premium-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                    <h3 style="font-size:.85rem;font-weight:700;color:#1E293B;text-transform:uppercase;letter-spacing:.05em;margin:0;">FAQ Artikel (Tanya Jawab Schema)</h3>
                    <button type="button" onclick="addFaq()" class="editor-btn" style="color:#1eb349;padding:0.4rem 0.75rem;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah FAQ
                    </button>
                </div>
                <p style="font-size:.8rem;color:#64748B;margin-bottom:1rem;line-height:1.5;">Pertanyaan & jawaban otomatis generate Schema FAQPage untuk Rich Results SEO Google.</p>
                <div id="faq-list-id" style="display:flex;flex-direction:column;gap:1rem;">
                    @php $faqsData = old('translations.id.faqs', $t?->faqs ?? []); @endphp
                    @foreach($faqsData as $fi => $faq)
                    <div class="faq-item" style="background:#F8FAFC;border:1px solid #E2E8F0;padding:1.25rem;border-radius:12px;position:relative;">
                        <button type="button" onclick="this.closest('.faq-item').remove()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;color:#94A3B8;cursor:pointer;padding:0;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                        <input type="text" name="translations[id][faqs][{{ $fi }}][q]" class="form-input" value="{{ $faq['q'] ?? '' }}" placeholder="Pertanyaan?" style="margin-bottom:.75rem;background:#fff;">
                        <textarea name="translations[id][faqs][{{ $fi }}][a]" class="form-textarea" rows="2" placeholder="Jawaban..." style="background:#fff;">{{ $faq['a'] ?? '' }}</textarea>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    </form>
</div>

@push('scripts')
<script>
// TABS SWITCHING LOGIC
document.querySelectorAll('.cms-tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.cms-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.cms-tab-pane').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
});

let slugManual = {{ $a ? 'true' : 'false' }};
document.getElementById('art-slug')?.addEventListener('input', () => slugManual = true);
function autoSlug() {
    if (slugManual) return;
    const titleVal = document.getElementById('art-title-id')?.value || '';
    document.getElementById('art-slug').value = titleVal
        .toLowerCase().replace(/[^a-z0-9\s\-]/g,'').trim().replace(/\s+/g,'-');
}

// REVAMPED RICH TEXT EDITOR LOGIC
let currentEditorMode = 'visual';

function setEditorMode(mode) {
    const visualBtn = document.getElementById('btn-mode-visual');
    const codeBtn = document.getElementById('btn-mode-code');
    const toolbar = document.getElementById('editor-toolbar-bar');
    const visualArea = document.getElementById('editor-id');
    const codeArea = document.getElementById('html-editor-id');

    currentEditorMode = mode;

    if (mode === 'code') {
        codeArea.value = visualArea.innerHTML;
        visualArea.style.display = 'none';
        codeArea.style.display = 'block';
        toolbar.style.opacity = '0.4';
        toolbar.style.pointerEvents = 'none';
        visualBtn.classList.remove('active');
        codeBtn.classList.add('active');
    } else {
        visualArea.innerHTML = codeArea.value;
        codeArea.style.display = 'none';
        visualArea.style.display = 'block';
        toolbar.style.opacity = '1';
        toolbar.style.pointerEvents = 'auto';
        codeBtn.classList.remove('active');
        visualBtn.classList.add('active');
    }
}

function fmt(cmd, val = null) {
    if (currentEditorMode === 'code') return;
    document.getElementById('editor-id').focus();
    document.execCommand(cmd, false, val);
    syncContent();
}

function fmtBlock(tag) {
    if (currentEditorMode === 'code' || !tag) return;
    document.getElementById('editor-id').focus();
    document.execCommand('formatBlock', false, tag);
    syncContent();
}

function insertLink() {
    if (currentEditorMode === 'code') return;
    const url = prompt('Masukkan URL Link:');
    if (url) {
        document.getElementById('editor-id').focus();
        document.execCommand('createLink', false, url);
        syncContent();
    }
}

function insertImgUrl() {
    if (currentEditorMode === 'code') return;
    const url = prompt('Masukkan URL Gambar:');
    if (url) {
        document.getElementById('editor-id').focus();
        document.execCommand('insertImage', false, url);
        syncContent();
    }
}

function insertTable() {
    if (currentEditorMode === 'code') return;
    const html = `<table style="width:100%;border-collapse:collapse;margin:1rem 0;border:1px solid #E2E8F0;">
        <thead><tr style="background:#F8FAFC;"><th style="border:1px solid #E2E8F0;padding:8px 12px;">Kolom 1</th><th style="border:1px solid #E2E8F0;padding:8px 12px;">Kolom 2</th></tr></thead>
        <tbody><tr><td style="border:1px solid #E2E8F0;padding:8px 12px;">Data 1</td><td style="border:1px solid #E2E8F0;padding:8px 12px;">Data 2</td></tr></tbody>
    </table><p></p>`;
    document.getElementById('editor-id').focus();
    document.execCommand('insertHTML', false, html);
    syncContent();
}

function syncContent() {
    if (currentEditorMode === 'visual') {
        document.getElementById('html-editor-id').value = document.getElementById('editor-id').innerHTML;
    }
}

let faqIdx = {{ count($t?->faqs ?? []) }};
function addFaq() {
    const idx = faqIdx++;
    document.getElementById('faq-list-id').insertAdjacentHTML('beforeend', `
        <div class="faq-item" style="background:#F8FAFC;border:1px solid #E2E8F0;padding:1.25rem;border-radius:12px;position:relative;">
            <button type="button" onclick="this.closest('.faq-item').remove()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;color:#94A3B8;cursor:pointer;padding:0;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <input type="text" name="translations[id][faqs][${idx}][q]" class="form-input" placeholder="Pertanyaan?" style="margin-bottom:.75rem;background:#fff;">
            <textarea name="translations[id][faqs][${idx}][a]" class="form-textarea" rows="2" placeholder="Jawaban..." style="background:#fff;"></textarea>
        </div>`);
}

function previewImg(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(previewId);
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('article-form').addEventListener('submit', function(e) {
    if (currentEditorMode === 'visual') {
        document.getElementById('html-editor-id').value = document.getElementById('editor-id').innerHTML;
    } else {
        document.getElementById('editor-id').innerHTML = document.getElementById('html-editor-id').value;
    }
    
    const errors = [];
    const title = document.getElementById('art-title-id')?.value.trim();
    if (!title) errors.push('Judul Artikel wajib diisi.');
    const content = document.getElementById('html-editor-id')?.value.trim() ||
                    document.getElementById('editor-id')?.innerHTML.replace(/<[^>]+>/g,'').trim();
    if (!content || content.length < 10) errors.push('Isi Artikel wajib diisi.');
    
    if (errors.length > 0) {
        e.preventDefault();
        const box = document.getElementById('validation-alert');
        box.innerHTML = '<strong>Mohon perbaiki sebelum menyimpan:</strong><ul style="margin:.5rem 0 0;padding-left:1.25rem;">' +
            errors.map(err => `<li style="margin-bottom:.25rem;">❌ ${err}</li>`).join('') + '</ul>';
        box.style.display = 'block';
        box.scrollIntoView({ behavior: 'smooth', block: 'start' });
        return false;
    }
});
</script>
@endpush
@endsection
