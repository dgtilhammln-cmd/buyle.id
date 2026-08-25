@extends('creator.layout')

@section('title', 'Edit Produk Digital')
@section('page_title', 'Edit Produk')
@section('breadcrumb', 'Katalog › Produk › Edit')

@section('styles')
<style>
    .form-card {
        background:#fff; border-radius:16px; border:1px solid #e7f0e7;
        box-shadow:0 2px 8px rgba(0,0,0,0.04); overflow:hidden; max-width:860px;
    }
    .form-section-title {
        display:flex; align-items:center; gap:0.4rem;
        font-size:0.72rem; font-weight:700; color:#94A3B8;
        text-transform:uppercase; letter-spacing:0.1em;
        padding:1rem 1.5rem 0.5rem;
        border-top:1px solid #f3f7f3; margin-top:0.5rem;
    }
    .form-section-title:first-child { border-top:none; margin-top:0; }
    .form-body { padding:0 1.5rem 1.5rem; }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
    .form-group { display:flex; flex-direction:column; gap:0.4rem; }
    .form-group.full { grid-column:1/-1; }
    .form-label { font-size:0.8rem; font-weight:700; color:#374151; }
    .form-label span { color:#ef4444; }
    .form-input {
        height:44px; padding:0 1rem;
        border:1.5px solid #e7f0e7; border-radius:10px;
        font-family:'Montserrat',sans-serif; font-size:0.875rem; color:#1a1a1a;
        background:#f9fefb; outline:none; transition:all 0.2s;
    }
    .form-input:focus { border-color:#1eb349; background:#fff; box-shadow:0 0 0 3px rgba(30,179,73,0.1); }
    textarea.form-input { height:auto; padding:0.75rem 1rem; resize:vertical; min-height:90px; }
    select.form-input { cursor:pointer; }
    .form-hint { font-size:0.72rem; color:#94A3B8; }
    .form-error { font-size:0.72rem; color:#ef4444; }

    /* Link validator */
    .link-status {
        margin-top:0.5rem; padding:0.5rem 0.75rem; border-radius:8px;
        font-size:0.75rem; font-weight:600; display:none;
    }
    .link-status.valid { background:#dcfce7; color:#15803d; }
    .link-status.invalid { background:#fee2e2; color:#b91c1c; }

    /* Image Upload */
    .img-upload-area {
        border:2px dashed #bbf7d0; border-radius:12px;
        padding:1.5rem; text-align:center; cursor:pointer;
        transition:all 0.2s; background:#f9fefb;
    }
    .img-upload-area:hover { border-color:#1eb349; background:#f0fdf4; }
    .img-preview { width:80px; height:80px; object-fit:cover; border-radius:10px; }
    #img-input { display:none; }

    /* Submit bar */
    .form-footer {
        padding:1rem 1.5rem; border-top:1px solid #f3f7f3;
        display:flex; align-items:center; justify-content:flex-end; gap:0.75rem;
    }
    .btn-cancel {
        height:42px; padding:0 1.25rem; border-radius:10px;
        border:1.5px solid #e7f0e7; background:#fff;
        font-family:'Montserrat',sans-serif; font-size:0.82rem; font-weight:700;
        color:#64748B; text-decoration:none; display:inline-flex; align-items:center;
        transition:all 0.2s; cursor:pointer;
    }
    .btn-cancel:hover { border-color:#94A3B8; color:#374151; }
    .btn-submit {
        height:42px; padding:0 1.5rem; border-radius:10px;
        background:linear-gradient(135deg,#1eb349,#a5cf37); border:none;
        font-family:'Montserrat',sans-serif; font-size:0.82rem; font-weight:700; color:#fff;
        cursor:pointer; box-shadow:0 2px 8px rgba(30,179,73,0.3);
        transition:all 0.2s;
    }
    .btn-submit:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(30,179,73,0.4); }

    @media(max-width:640px) { .form-grid { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('creator.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        @method('PUT')

        @if($errors->any())
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 1rem 1.5rem; border-bottom: 1px solid #fca5a5; font-size: 0.85rem; font-weight: 600;">
            Terdapat beberapa kesalahan. Mohon periksa kembali isian Anda:
            <ul style="margin-top: 0.5rem; margin-bottom: 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Info Dasar --}}
        <div class="form-section-title">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            Informasi Produk
        </div>
        <div class="form-body">
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">Nama Produk <span>*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input" placeholder="Contoh: Template Canva Premium Social Media Pack" required>
                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori Platform</label>
                    <select name="product_category_id" id="catSelect" class="form-input" onchange="loadSubCat(this.value)">
                        <option value="">— Pilih Kategori —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('product_category_id', $product->product_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('product_category_id')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group" id="subCatWrap" style="display:none;">
                    <label class="form-label">Sub-Kategori</label>
                    <select name="product_sub_category_id" id="subCatSelect" class="form-input">
                        <option value="">— Pilih Sub-Kategori —</option>
                    </select>
                    @error('product_sub_category_id')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kelompok Produk (Toko Saya)</label>
                    <select name="creator_group_id" class="form-input">
                        <option value="">— Tidak Masuk Kelompok —</option>
                        @foreach($groups as $grp)
                            <option value="{{ $grp->id }}" {{ old('creator_group_id', $product->creator_group_id) == $grp->id ? 'selected' : '' }}>{{ $grp->name }}</option>
                        @endforeach
                    </select>
                    <span class="form-hint"><a href="{{ route('creator.groups.index') }}" style="color:#1eb349;text-decoration:none;">Kelola kelompok produk di sini</a></span>
                    @error('creator_group_id')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Harga (Rp) <span>*</span></label>
                    <input type="number" name="price" value="{{ old('price', (int)$product->price) }}" class="form-input" placeholder="50000" min="0" required>
                    @error('price')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Harga Coret (Rp)</label>
                    <input type="number" name="sale_price" value="{{ old('sale_price', (int)$product->sale_price) }}" class="form-input" placeholder="Opsional" min="0">
                    <span class="form-hint">Kosongkan jika tidak ada diskon</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Stok / Kuota</label>
                    <input type="number" name="stock" value="{{ old('stock', (int)$product->stock) }}" class="form-input" placeholder="Opsional" min="0">
                    <span class="form-hint">Kosongkan jika stok unlimited (digital)</span>
                </div>

                <div class="form-group full">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="short_desc" class="form-input" rows="2" placeholder="Deskripsi singkat produk (maks 160 karakter)" maxlength="160">{{ old('short_desc', $product->short_desc) }}</textarea>
                    @error('short_desc')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">Deskripsi Lengkap <span>*</span></label>
                    @include('creator.partials.rich-editor', ['name'=>'description', 'value'=>old('description', $product->description), 'height'=>'300px'])
                </div>
            </div>
        </div>

        {{-- Link Digital --}}
        <div class="form-section-title">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
            Link Produk Digital
        </div>
        <div class="form-body">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Jenis File <span>*</span></label>
                    <select name="file_type" class="form-input" required>
                        <option value="">— Pilih Jenis File —</option>
                        @foreach(['PDF','ZIP','RAR','Video (MP4)','Audio (MP3)','Ebook','Template (Canva/Notion)','Source Code','Spreadsheet (Excel/GSheets)','Document (Word)','Lainnya'] as $ft)
                            <option value="{{ $ft }}" {{ old('file_type', $product->file_type) == $ft ? 'selected' : '' }}>{{ $ft }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <label class="form-label">URL Produk Digital <span>*</span></label>
                    <input type="url" name="digital_resource" id="externalLink" value="{{ old('digital_resource', $product->digital_resource) }}"
                        class="form-input" placeholder="https://drive.google.com/... atau https://www.canva.com/..."
                        required autocomplete="off">
                    <div class="link-status" id="linkStatus"></div>
                    <span class="form-hint">
                        Domain yang diizinkan: {{ implode(', ', array_slice($allowedDomains, 0, 6)) }}{{ count($allowedDomains) > 6 ? ', dan lainnya' : '' }}
                    </span>
                    @error('digital_resource')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group full">
                    <label class="form-label">Link Video TikTok (Opsional)</label>
                    <input type="url" name="tiktok_video_url" value="{{ old('tiktok_video_url', $product->tiktok_video_url) }}"
                        class="form-input" placeholder="https://www.tiktok.com/@username/video/123456789" autocomplete="off">
                    <span class="form-hint">Tautan langsung ke video TikTok yang relevan dengan produk.</span>
                    @error('tiktok_video_url')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- Gambar --}}
        <div class="form-section-title">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            Gambar Thumbnail
        </div>
        <div class="form-body">
            <div class="form-group">
                <label class="form-label">Thumbnail Utama & Galeri (Maks 7 Gambar)</label>
                <div class="img-upload-area" onclick="document.getElementById('img-input').click()" style="margin-bottom:0.75rem;">
                    <div id="imgPlaceholder">
                        <svg width="32" height="32" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <p style="font-size:0.8rem; color:#94A3B8; margin-top:0.5rem; font-weight:600;">Klik untuk ganti gambar (pilih hingga 7)</p>
                        <p style="font-size:0.7rem; color:#cbd5e1; margin-top:0.2rem;">JPG, PNG, WEBP — maks 10MB/file</p>
                    </div>
                </div>
                <div id="galleryPreview" style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1rem;">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" style="width:80px;height:80px;object-fit:cover;border-radius:10px;">
                    @endif
                    @if(is_array($product->gallery))
                        @foreach($product->gallery as $galImg)
                            <img src="{{ Storage::url($galImg) }}" style="width:80px;height:80px;object-fit:cover;border-radius:10px;">
                        @endforeach
                    @endif
                </div>
                <input type="file" name="gallery[]" id="img-input" accept="image/*" multiple max="7" onchange="previewGallery(event)" style="display:none;">
                @error('gallery')<span class="form-error">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Pengaturan --}}
        <div class="form-section-title">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            Pengaturan
        </div>
        <div class="form-body">
            <div class="form-grid">
                <div class="form-group" style="flex-direction:row; align-items:center; gap:0.75rem;">
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <span class="toggle-slider-s"></span>
                    </label>
                    <div>
                        <div class="form-label">Aktifkan Produk</div>
                        <div class="form-hint">Produk langsung tampil di katalog</div>
                    </div>
                </div>
                <div class="form-group" style="flex-direction:row; align-items:center; gap:0.75rem;">
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <span class="toggle-slider-s"></span>
                    </label>
                    <div>
                        <div class="form-label">Produk Unggulan</div>
                        <div class="form-hint">Tampil di halaman utama toko Anda</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Meta Data (SEO) --}}
        <div class="form-section-title">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            Meta Data (SEO)
        </div>
        <div class="form-body">
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="form-input" placeholder="Otomatis menggunakan nama produk jika kosong">
                    <span class="form-hint">Maksimal 70 karakter</span>
                    @error('meta_title')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group full">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_desc" class="form-input" rows="2" placeholder="Otomatis menggunakan deskripsi singkat jika kosong">{{ old('meta_desc', $product->meta_desc) }}</textarea>
                    <span class="form-hint">Maksimal 160 karakter</span>
                    @error('meta_desc')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group full">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" class="form-input" placeholder="e.g. preset lightroom, ebook bisnis">
                    <span class="form-hint">Pisahkan dengan koma</span>
                    @error('meta_keywords')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('creator.products.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit" id="submitBtn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-right:0.4rem;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                Perbarui Produk
            </button>
        </div>
    </form>
</div>

<style>
    .toggle-switch { position:relative; width:40px; height:22px; flex-shrink:0; }
    .toggle-switch input { opacity:0; width:0; height:0; }
    .toggle-slider-s { position:absolute; inset:0; background:#cbd5e1; border-radius:22px; cursor:pointer; transition:0.3s; }
    .toggle-slider-s::before { content:''; position:absolute; left:3px; top:3px; width:16px; height:16px; background:#fff; border-radius:50%; transition:0.3s; }
    .toggle-switch input:checked + .toggle-slider-s { background:#1eb349; }
    .toggle-switch input:checked + .toggle-slider-s::before { transform:translateX(18px); }
</style>
@endsection

@section('scripts')
<script>
let galleryFiles = new DataTransfer();

async function previewGallery(event) {
    const input   = event.target;
    const preview = document.getElementById('galleryPreview');

    for (const file of Array.from(input.files)) {
        if (galleryFiles.items.length >= 7) break;
        galleryFiles.items.add(file); // upload file asli tanpa kompresi
    }
    input.files = galleryFiles.files;
    renderGalleryPreview();
}

function removeGalleryImage(index) {
    const newFiles = new DataTransfer();
    Array.from(galleryFiles.files).forEach((f, i) => { if (i !== index) newFiles.items.add(f); });
    galleryFiles = newFiles;
    document.getElementById('img-input').files = galleryFiles.files;
    renderGalleryPreview();
}

function renderGalleryPreview() {
    const preview     = document.getElementById('galleryPreview');
    const placeholder = document.getElementById('imgPlaceholder');
    preview.innerHTML = '';

    if (galleryFiles.files.length > 0) {
        if (placeholder) placeholder.style.display = 'none';
        Array.from(galleryFiles.files).forEach((file, index) => {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;display:inline-block;';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.cssText = 'width:80px;height:80px;object-fit:cover;border-radius:10px;display:block;';

            const badge = document.createElement('div');
            badge.textContent = index === 0 ? '🖼 Thumbnail' : `#${index+1}`;
            badge.style.cssText = 'position:absolute;bottom:2px;left:2px;right:2px;background:rgba(0,0,0,0.55);color:#fff;font-size:9px;border-radius:0 0 8px 8px;text-align:center;padding:2px 0;';

            const btn = document.createElement('button');
            btn.innerHTML = '&times;';
            btn.type = 'button';
            btn.style.cssText = 'position:absolute;top:-6px;right:-6px;background:#ef4444;color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:14px;line-height:1;display:flex;align-items:center;justify-content:center;';
            btn.onclick = () => removeGalleryImage(index);

            wrap.appendChild(img);
            wrap.appendChild(badge);
            wrap.appendChild(btn);
            preview.appendChild(wrap);
        });
    } else {
        if (placeholder) placeholder.style.display = 'block';
    }
}

// Real-time link validator
let linkTimer;
const linkInput  = document.getElementById('externalLink');
const linkStatus = document.getElementById('linkStatus');
const csrfToken  = document.querySelector('meta[name="csrf-token"]').content;

linkInput.addEventListener('input', function() {
    clearTimeout(linkTimer);
    linkStatus.style.display = 'none';
    if (!this.value.trim()) return;
    linkTimer = setTimeout(() => validateLink(this.value), 800);
});

async function validateLink(url) {
    try {
        const res  = await fetch('{{ route("creator.products.validate-link") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ url })
        });
        const data = await res.json();
        linkStatus.textContent  = data.message;
        linkStatus.className    = 'link-status ' + (data.valid ? 'valid' : 'invalid');
        linkStatus.style.display = 'block';
    } catch(e) {}
}
// Sub-kategori dinamis
const subData = @json($categories->map(fn($c) => ['id'=>$c->id,'subs'=>$c->subCategories->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])]));
const oldSubCat = "{{ old('product_sub_category_id', $product->product_sub_category_id) }}";

function loadSubCat(catId) {
    const wrap = document.getElementById('subCatWrap');
    const sel  = document.getElementById('subCatSelect');
    if (!catId) { wrap.style.display='none'; return; }
    const found = subData.find(c => c.id == catId);
    if (!found || !found.subs.length) { wrap.style.display='none'; return; }
    sel.innerHTML = '<option value="">— Pilih Sub-Kategori —</option>';
    found.subs.forEach(s => { 
        const o = document.createElement('option'); 
        o.value = s.id; 
        o.textContent = s.name; 
        if (oldSubCat == s.id) o.selected = true;
        sel.appendChild(o); 
    });
    wrap.style.display = 'block';
}

// Trigger on load if old value exists
const initCat = document.getElementById('catSelect').value;
if (initCat) loadSubCat(initCat);
</script>
@endsection
