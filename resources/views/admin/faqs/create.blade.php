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
    border: 1px solid #E2E8F0; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;
}
.premium-card-header {
    font-size: 0.85rem; font-weight: 700; color: #1E293B; text-transform: uppercase;
    letter-spacing: 0.05em; margin: 0 0 1.25rem; padding-bottom: 0.75rem;
    border-bottom: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center;
}
.form-group { margin-bottom: 1.25rem; }
.form-label { display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem; }
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
.form-textarea { resize: vertical; min-height: 80px; }
.char-count { font-size: 0.75rem; color: #94A3B8; margin-top: 0.35rem; text-align: right; }
.editor-toolbar {
    background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 12px 12px 0 0;
    border-bottom: none; padding: 0.75rem; display: flex; flex-wrap: wrap; gap: 0.35rem;
}
.editor-btn {
    padding: 0.35rem 0.6rem; background: #fff; border: 1px solid #E2E8F0; color: #475569;
    border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: 600; transition: all 0.2s;
    display: inline-flex; align-items: center; gap: 0.35rem;
}
.editor-btn:hover { background: #F1F5F9; color: #1E293B; border-color: #CBD5E1; }
.editor-area {
    border: 1.5px solid #E2E8F0; border-radius: 0 0 12px 12px; min-height: 380px;
    padding: 1.5rem; font-size: 1rem; line-height: 1.8; color: #1E293B; background: #fff; outline: none;
}
.editor-area:focus { border-color: #1eb349; }
.img-preview { width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: 8px; margin-bottom: 0.75rem; border: 1px solid #E2E8F0; }
.btn-primary-new {
    background: #1eb349; color: #fff; border: none; padding: 0.875rem 1.5rem; border-radius: 12px;
    font-weight: 700; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center;
    justify-content: center; gap: 0.5rem; transition: all 0.2s; box-shadow: 0 4px 14px rgba(30,179,73,0.3); width: 100%;
}
.btn-primary-new:hover { background: #1eb349; transform: translateY(-2px); }
.btn-outline-new {
    background: #fff; color: #64748B; border: 1.5px solid #E2E8F0; padding: 0.875rem 1.5rem;
    border-radius: 12px; font-weight: 600; font-size: 0.9rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    transition: all 0.2s; width: 100%; text-decoration: none;
}
.btn-outline-new:hover { background: #F8FAFC; color: #1E293B; }
.switch-label { display: flex; align-items: center; gap: 0.75rem; cursor: pointer; }
.switch-input { width: 20px; height: 20px; accent-color: #1eb349; cursor: pointer; }
.switch-text { font-size: 0.9rem; font-weight: 600; color: #334155; }
</style>

<div style="max-width:1080px; margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">{{ $a ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</h1>
            <p style="font-size:.875rem;color:#94A3B8;margin:0;">Isi konten artikel dalam Bahasa Indonesia.</p>
        </div>
        <a href="{{ route('admin.faqs.index') }}" class="btn-outline-new" style="width:auto;padding:.5rem 1rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ $a ? route('admin.faqs.update',$a) : route('admin.faqs.store') }}" enctype="multipart/form-data" id="article-form">
    @csrf @if($a) @method('PUT') @endif

    <div id="validation-alert" style="display:none;background:#FEF2F2;border:1.5px solid #FCA5A5;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#991B1B;font-size:.875rem;line-height:1.6;"></div>

    <div style="display:grid;grid-template-columns:minmax(0, 1fr) 340px;gap:1.5rem;">

        <div>
            <div class="premium-card">
                <h3 class="premium-card-header">Konten Artikel <span style="color:#EF4444;font-size:.75rem;font-weight:600;text-transform:none;">Wajib diisi</span></h3>
                <div class="form-group">
                    <label class="form-label">Judul Artikel <span class="req">*</span></label>
                    <input type="text" name="translations[id][title]" id="art-title-id"
                        value="{{ old('translations.id.title', $t?->title) }}"
                        class="form-input" required oninput="autoSlug()" placeholder="Judul artikel yang menarik...">
                </div>
                <div class="form-group">
                    <label class="form-label">Slug (URL) <span class="hint">Otomatis dari judul jika dikosongkan.</span></label>
                    <div style="display:flex;align-items:center;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:10px;padding:0 1rem;overflow:hidden;">
                        <span style="font-size:.85rem;color:#94A3B8;white-space:nowrap;">/faq/</span>
                        <input type="text" name="slug" id="art-slug" value="{{ old('slug',$a?->slug) }}"
                            style="border:none;background:transparent;padding:0.75rem 0;width:100%;font-size:.9rem;color:#1E293B;outline:none;" pattern="[a-z0-9\-]*" placeholder="auto-dari-judul">
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

            <div class="premium-card" style="padding:0;overflow:hidden;border:none;">
                <h3 class="premium-card-header" style="padding:1.25rem 1.5rem;margin:0;border:1px solid #E2E8F0;border-bottom:none;border-radius:12px 12px 0 0;">
                    Isi Artikel <span class="req" style="margin-left:4px;">*</span>
                </h3>
                <div class="editor-toolbar">
                    <button type="button" class="editor-btn" onclick="fmt('bold')" style="font-weight:800;">B</button>
                    <button type="button" class="editor-btn" onclick="fmt('italic')" style="font-style:italic;">I</button>
                    <button type="button" class="editor-btn" onclick="fmt('underline')" style="text-decoration:underline;">U</button>
                    <div style="width:1px;background:#E2E8F0;margin:0 .25rem;"></div>
                    <button type="button" class="editor-btn" onclick="fmtBlock('h2')">H2</button>
                    <button type="button" class="editor-btn" onclick="fmtBlock('h3')">H3</button>
                    <button type="button" class="editor-btn" onclick="fmtBlock('p')">P</button>
                    <div style="width:1px;background:#E2E8F0;margin:0 .25rem;"></div>
                    <button type="button" class="editor-btn" onclick="fmt('insertUnorderedList')">UL</button>
                    <button type="button" class="editor-btn" onclick="fmt('insertOrderedList')">OL</button>
                    <div style="width:1px;background:#E2E8F0;margin:0 .25rem;"></div>
                    <button type="button" class="editor-btn" onclick="insertLink()">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                    </button>
                    <div style="flex-grow:1;"></div>
                    <button type="button" class="editor-btn" id="html-btn" onclick="toggleHtml()">HTML</button>
                </div>
                <div id="editor-id" class="editor-area" contenteditable="true" oninput="syncContent()">{!! old('translations.id.content', $t?->content) !!}</div>
                <textarea id="html-editor-id" name="translations[id][content]"
                    style="display:none;width:100%;min-height:380px;padding:1.5rem;color:#1E293B;font-size:.85rem;line-height:1.6;font-family:monospace;background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:0 0 12px 12px;outline:none;resize:vertical;box-sizing:border-box;"
                    required>{{ old('translations.id.content', $t?->content) }}</textarea>
            </div>

            <div class="premium-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                    <h3 style="font-size:.85rem;font-weight:700;color:#1E293B;text-transform:uppercase;letter-spacing:.05em;margin:0;">FAQ (Tanya Jawab)</h3>
                    <button type="button" onclick="addFaq()" class="editor-btn" style="color:#1eb349;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah FAQ
                    </button>
                </div>
                <p style="font-size:.8rem;color:#64748B;margin-bottom:1rem;line-height:1.5;">Pertanyaan & jawaban otomatis generate Schema FAQPage untuk SEO.</p>
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

            <div class="premium-card">
                <h3 class="premium-card-header">SEO & Meta</h3>
                <div class="form-group">
                    <label class="form-label">Meta Title <span class="hint">(max 65 karakter)</span></label>
                    <input type="text" name="translations[id][meta_title]" value="{{ old('translations.id.meta_title', $t?->meta_title) }}" class="form-input" maxlength="65" oninput="document.getElementById('mt-cnt').textContent=this.value.length" placeholder="Otomatis dari judul jika kosong">
                    <div class="char-count"><span id="mt-cnt">{{ strlen(old('translations.id.meta_title', $t?->meta_title ?? '')) }}</span>/65</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Description <span class="hint">(max 160 karakter)</span></label>
                    <textarea name="translations[id][meta_desc]" class="form-textarea" rows="2" maxlength="160" oninput="document.getElementById('md-cnt').textContent=this.value.length" placeholder="Deskripsi untuk Google (otomatis jika kosong)">{{ old('translations.id.meta_desc', $t?->meta_desc) }}</textarea>
                    <div class="char-count"><span id="md-cnt">{{ strlen(old('translations.id.meta_desc', $t?->meta_desc ?? '')) }}</span>/160</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Keywords <span class="hint">(pisah dengan koma)</span></label>
                    <input type="text" name="translations[id][meta_keywords]" value="{{ old('translations.id.meta_keywords', $t?->meta_keywords) }}" class="form-input" placeholder="buyle.id, peralatan, tips">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Alt Text Gambar <span class="hint">(aksesibilitas & SEO gambar)</span></label>
                    <input type="text" name="translations[id][thumbnail_alt]" value="{{ old('translations.id.thumbnail_alt', $t?->thumbnail_alt) }}" class="form-input" placeholder="Deskripsi gambar untuk screen reader">
                </div>
            </div>
        </div>

        <div>
            <div class="premium-card">
                <h3 class="premium-card-header">Status Publikasi</h3>
                <div class="form-group">
                    <label class="switch-label">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published',$a?->is_published) ? 'checked' : '' }} class="switch-input">
                        <span class="switch-text">Publish Sekarang</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Publish</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', $a?->published_at?->format('Y-m-d\TH:i')) }}" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" value="{{ old('category',$a?->category) }}" class="form-input" placeholder="Tips & Panduan">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Tags <span class="hint">(pisah dengan koma)</span></label>
                    <input type="text" name="tags" value="{{ old('tags', $a && $a->tags ? implode(', ',$a->tags) : '') }}" class="form-input" placeholder="dapur, tips, hemat">
                </div>
            </div>

            <div class="premium-card">
                <h3 class="premium-card-header">Gambar Utama <span style="font-size:.72rem;font-weight:400;color:#94A3B8;text-transform:none;">1280x720 otomatis</span></h3>
                @if($a?->getRawOriginal('image'))
                    <img src="{{ asset('storage/'.$a->getRawOriginal('image')) }}" id="img-prev" class="img-preview">
                @else
                    <img id="img-prev" class="img-preview" style="display:none;">
                @endif
                <input type="file" name="image" accept="image/*" class="form-input" style="padding:0.6rem;background:#fff;" onchange="previewImg(this,'img-prev')">
                <p style="font-size:.75rem;color:#94A3B8;margin:.75rem 0 0;line-height:1.5;">Otomatis dikonversi ke WebP 1280x720 landscape.</p>
            </div>

            <div class="premium-card">
                <h3 class="premium-card-header">OG Image <span style="font-size:.72rem;font-weight:400;color:#94A3B8;text-transform:none;">1280x720 otomatis</span></h3>
                @if($a?->getRawOriginal('og_image'))
                    <img src="{{ asset('storage/'.$a->getRawOriginal('og_image')) }}" id="og-prev" class="img-preview">
                @else
                    <img id="og-prev" class="img-preview" style="display:none;">
                @endif
                <input type="file" name="og_image" accept="image/*" class="form-input" style="padding:0.6rem;background:#fff;" onchange="previewImg(this,'og-prev')">
                <p style="font-size:.75rem;color:#94A3B8;margin:.75rem 0 0;line-height:1.5;">Opsional. Jika kosong, gambar utama akan dipakai.</p>
            </div>

            <div class="premium-card">
                <h3 class="premium-card-header">Opsi Lainnya</h3>
                <label class="switch-label">
                    <input type="hidden" name="show_toc" value="0">
                    <input type="checkbox" name="show_toc" value="1" {{ old('show_toc',$a?->show_toc) ? 'checked' : '' }} class="switch-input">
                    <span class="switch-text" style="font-size:.85rem;">Tampilkan Daftar Isi (TOC)</span>
                </label>
            </div>

            <div style="display:flex;flex-direction:column;gap:0.75rem;position:sticky;top:6rem;">
                <button type="submit" class="btn-primary-new">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    {{ $a ? 'Simpan Perubahan' : 'Publish Artikel' }}
                </button>
                <a href="{{ route('admin.faqs.index') }}" class="btn-outline-new">Batalkan</a>
            </div>
        </div>

    </div>
    </form>
</div>

@push('scripts')
<script>
let slugManual = {{ $a ? 'true' : 'false' }};
document.getElementById('art-slug').addEventListener('input', () => slugManual = true);
function autoSlug() {
    if (slugManual) return;
    document.getElementById('art-slug').value = document.getElementById('art-title-id').value
        .toLowerCase().replace(/[^a-z0-9\s\-]/g,'').trim().replace(/\s+/g,'-');
}

let htmlMode = false;
function fmt(cmd) {
    if (htmlMode) return;
    document.getElementById('editor-id').focus();
    document.execCommand(cmd, false, null);
    syncContent();
}
function fmtBlock(tag) {
    if (htmlMode) return;
    document.getElementById('editor-id').focus();
    document.execCommand('formatBlock', false, tag);
    syncContent();
}
function insertLink() {
    if (htmlMode) return;
    const url = prompt('Masukkan URL:');
    if (url) { document.getElementById('editor-id').focus(); document.execCommand('createLink', false, url); syncContent(); }
}
function toggleHtml() {
    const editor = document.getElementById('editor-id');
    const textarea = document.getElementById('html-editor-id');
    const btn = document.getElementById('html-btn');
    htmlMode = !htmlMode;
    if (htmlMode) {
        textarea.value = editor.innerHTML;
        editor.style.display = 'none'; textarea.style.display = 'block';
        btn.style.background = '#1eb349'; btn.style.color = '#fff';
    } else {
        editor.innerHTML = textarea.value;
        textarea.style.display = 'none'; editor.style.display = 'block';
        btn.style.background = ''; btn.style.color = '#64748B';
    }
}
function syncContent() {
    if (!htmlMode) document.getElementById('html-editor-id').value = document.getElementById('editor-id').innerHTML;
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
    syncContent();
    const errors = [];
    const title = document.getElementById('art-title-id')?.value.trim();
    if (!title) errors.push('Judul Artikel wajib diisi.');
    const content = document.getElementById('html-editor-id')?.value.trim() ||
                    document.getElementById('editor-id')?.innerHTML.replace(/<[^>]+>/g,'').trim();
    if (!content || content.length < 30) errors.push('Isi Artikel wajib diisi minimal 30 karakter.');
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

