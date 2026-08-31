@extends('creator.layout')

@section('title', 'Tambah Produk Digital')
@section('page_title', 'Tambah Produk')
@section('breadcrumb', 'Katalog › Produk › Tambah Baru')

@section('styles')
<style>
    /* Reusing some layout from profile */
    .prof-layout { display:flex; gap:2rem; align-items:flex-start; }
    .prof-sidebar {
        width: 250px; flex-shrink:0; background:#fff; border-radius:20px;
        padding:1.25rem; box-shadow:0 4px 12px rgba(0,0,0,0.03); border:1px solid #f0fdf4;
        position:sticky; top:1.5rem;
    }
    .prof-content { flex:1; min-width:0; }
    
    .tab-btn {
        width:100%; display:flex; align-items:center; gap:0.85rem;
        padding:0.85rem 1rem; border:none; background:transparent;
        color:#64748B; font-family:'Montserrat',sans-serif; font-size:0.85rem; font-weight:600;
        border-radius:12px; cursor:pointer; text-align:left; transition:all 0.2s;
        margin-bottom:0.25rem;
    }
    .tab-btn:hover { background:#f8fafc; color:#1eb349; }
    .tab-btn.active {
        background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; font-weight:700;
        box-shadow:0 4px 12px rgba(30,179,73,0.2);
    }
    .tab-pane { display:none; animation:fadeIn 0.3s; }
    .tab-pane.active { display:block; }
    @keyframes fadeIn { from{opacity:0; transform:translateY(5px);} to{opacity:1; transform:translateY(0);} }

    .prof-card {
        background:#fff; border-radius:20px; border:1px solid #f0fdf4;
        box-shadow:0 4px 20px rgba(0,0,0,0.03); margin-bottom:1.5rem; overflow:hidden;
    }
    .prof-card-head {
        padding:1.25rem 1.75rem; border-bottom:1px solid #f8fafc;
        display:flex; align-items:center; gap:0.6rem;
        font-size:0.95rem; font-weight:800; color:#0b120c;
    }
    .form-body { padding:1.5rem 1.75rem; }
    
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
    .form-group { display:flex; flex-direction:column; gap:0.4rem; margin-bottom:1.25rem; }
    .form-group.full { grid-column:1/-1; }
    .form-label { font-size:0.8rem; font-weight:700; color:#374151; }
    .form-label span { color:#ef4444; }
    .form-input {
        height:44px; padding:0 1rem; border:1.5px solid #e7f0e7; border-radius:10px;
        font-family:'Montserrat',sans-serif; font-size:0.875rem; color:#1a1a1a;
        background:#f9fefb; outline:none; transition:all 0.2s;
    }
    .form-input:focus { border-color:#1eb349; background:#fff; box-shadow:0 0 0 3px rgba(30,179,73,0.1); }
    textarea.form-input { height:auto; padding:0.75rem 1rem; resize:vertical; min-height:90px; }
    
    .form-hint { font-size:0.72rem; color:#94A3B8; }
    .form-error { font-size:0.72rem; color:#ef4444; }

    /* Toggle Switch */
    .toggle-slider-s {
        position: absolute; inset:0; background:#cbd5e1; border-radius:24px; transition:0.3s;
    }
    .toggle-slider-s::before {
        content: ''; position:absolute; left:3px; top:3px; width:18px; height:18px;
        background:#fff; border-radius:50%; transition:0.3s;
    }
    input:checked + .toggle-slider-s { background:#1eb349; }
    input:checked + .toggle-slider-s::before { transform:translateX(20px); }

    /* File Upload */
    .img-upload-area {
        border:2px dashed #bbf7d0; border-radius:12px; padding:1.5rem;
        text-align:center; cursor:pointer; transition:all 0.2s; background:#f9fefb;
    }
    .img-upload-area:hover { border-color:#1eb349; background:#f0fdf4; }

    /* Submit Footer */
    .form-footer {
        padding:1rem 1.75rem; border-top:1px solid #f3f7f3;
        display:flex; justify-content:flex-end; gap:0.75rem; background:#fff;
        border-radius:0 0 20px 20px;
    }
    .btn-cancel {
        height:44px; padding:0 1.5rem; border-radius:999px;
        border:1.5px solid #e7f0e7; background:#fff; color:#64748B;
        font-weight:700; display:inline-flex; align-items:center; text-decoration:none;
    }
    .btn-submit {
        height:44px; padding:0 1.5rem; border-radius:999px;
        background:linear-gradient(135deg, #1eb349, #a5cf37); border:none; color:#fff;
        font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem;
    }
    
    @media(max-width:768px) {
        .prof-layout { flex-direction:column; }
        .prof-sidebar { width:100%; position:static; }
        .form-grid { grid-template-columns:1fr; }
    }
</style>
@endsection

@section('content')
<div class="prof-layout">

    <div class="prof-sidebar">
        <button type="button" class="tab-btn active" data-tab="tab-content">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Informasi Dasar
        </button>
        <button type="button" class="tab-btn" data-tab="tab-media">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M20.4 14.5L16 10 4 20"/></svg>
            Media & File
        </button>
        <button type="button" class="tab-btn" data-tab="tab-seo" id="tab-seo-btn" style="display:none;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            SEO & FAQ
        </button>
        
        <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #e7f0e7;">
            <div style="font-size:0.75rem; font-weight:700; color:#64748b; margin-bottom:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">Pengaturan Form</div>
            
            <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer;">
                <div style="position:relative; width:44px; height:24px; flex-shrink:0;">
                    <input type="checkbox" name="is_advanced" id="seoModeToggle" onchange="toggleSeoMode()" style="opacity:0; width:0; height:0;" {{ old('is_advanced') ? 'checked' : '' }}>
                    <span class="toggle-slider-s"></span>
                </div>
                <div>
                    <div style="font-size:0.85rem; font-weight:700; color:#0b120c;">Mode Advanced SEO</div>
                    <div style="font-size:0.7rem; color:#64748b; line-height:1.4; margin-top:0.2rem;">Tampilkan tab SEO & Schema FAQ</div>
                </div>
            </label>
        </div>
    </div>

    <div class="prof-content">
        <form action="{{ route('creator.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            @if($errors->any())
                <div style="background:#fee2e2; color:#b91c1c; padding:1rem 1.5rem; border-radius:12px; margin-bottom:1.5rem; font-size:0.85rem; font-weight:600;">
                    Terdapat kesalahan isian:
                    <ul style="margin-top:0.5rem; margin-bottom:0; padding-left:1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- TAB 1: KONTEN --}}
            <div class="tab-pane active" id="tab-content">
                <div class="prof-card">
                    <div class="prof-card-head">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        Informasi Utama Produk
                    </div>
                    <div class="form-body">
                        <div class="form-grid">
                            <div class="form-group full">
                                <label class="form-label">Nama Produk <span>*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Contoh: Template Premium Social Media Pack" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kategori Utama <span>*</span></label>
                                <select name="product_category_id" id="catSelect" class="form-input" required onchange="loadSubCat(this.value)">
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('product_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="subCatWrap" style="display:none;">
                                <label class="form-label">Sub-Kategori</label>
                                <select name="product_sub_category_id" id="subCatSelect" class="form-input">
                                    <option value="">— Pilih Sub-Kategori —</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kelompok Produk</label>
                                <select name="creator_group_id" class="form-input">
                                    <option value="">— Tidak Masuk Kelompok —</option>
                                    @foreach($groups as $grp)
                                        <option value="{{ $grp->id }}" {{ old('creator_group_id') == $grp->id ? 'selected' : '' }}>{{ $grp->name }}</option>
                                    @endforeach
                                </select>
                                <span class="form-hint"><a href="{{ route('creator.groups.index') }}" style="color:#1eb349;text-decoration:none;">Buat Kelompok Baru?</a></span>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Harga Normal (Rp) <span>*</span></label>
                                <input type="text" name="price" id="input_price" value="{{ old('price') ? number_format((float)preg_replace('/[^\d]/', '', old('price')), 0, ',', '.') : '' }}" class="form-input currency-input" placeholder="Misal: 50.000" required autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Harga Coret / Diskon (Rp)</label>
                                <input type="text" name="sale_price" id="input_sale_price" value="{{ old('sale_price') ? number_format((float)preg_replace('/[^\d]/', '', old('sale_price')), 0, ',', '.') : '' }}" class="form-input currency-input" placeholder="Opsional, misal: 99.000" autocomplete="off">
                            </div>

                            <div class="form-group full">
                                <label class="form-label">Deskripsi Singkat (Exerp) <span>*</span></label>
                                <textarea name="short_desc" class="form-input" rows="2" placeholder="Deskripsi singkat maksimal 160 karakter" maxlength="160" required>{{ old('short_desc') }}</textarea>
                                <span class="form-hint">Digunakan sebagai meta description SEO jika mode advanced dimatikan.</span>
                            </div>

                            <div class="form-group full" style="margin-bottom:0;">
                                <label class="form-label">Deskripsi Lengkap <span>*</span></label>
                                <!-- Trix Editor could be loaded here -->
                                <textarea name="description" id="description" class="form-input" rows="6" placeholder="Tuliskan deskripsi lengkap produk..." required>{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: MEDIA --}}
            <div class="tab-pane" id="tab-media">
                <div class="prof-card">
                    <div class="prof-card-head">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                        Upload Visual Produk
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="form-label">Upload Thumbnail Utama <span>*</span></label>
                            <div class="img-upload-area" id="thumbDropzone" onclick="document.getElementById('thumb-input').click()" style="margin-bottom:0.75rem;">
                                <div>
                                    <svg width="32" height="32" fill="none" stroke="#1eb349" stroke-width="1.6" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><polyline points="21 15 16 10 5 21" /></svg>
                                    <p style="font-size:0.82rem; color:#1E293B; margin-top:0.4rem; font-weight:700;">Pilih Thumbnail Utama (1 Foto)</p>
                                </div>
                            </div>
                            <div id="thumbPreviewWrap" style="display:none; margin-bottom:1rem;">
                                <div style="position:relative; display:inline-block; border-radius:12px; overflow:hidden; border:2px solid #1eb349;">
                                    <img id="thumbPreviewImg" src="" style="width:110px; height:110px; object-fit:cover;">
                                    <button type="button" onclick="removeThumbnail()" style="position:absolute; top:4px; right:4px; background:#ef4444; color:#fff; border:none; border-radius:50%; width:24px; height:24px; cursor:pointer;" title="Hapus">&times;</button>
                                </div>
                            </div>
                            <input type="file" name="image" id="thumb-input" accept="image/*" onchange="previewSingleThumb(event)" style="display:none;" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Upload Foto Galeri / Screenshot (Opsional)</label>
                            <div class="img-upload-area" id="galleryDropzone" onclick="document.getElementById('gallery-input').click()" style="margin-bottom:0.75rem;">
                                <div>
                                    <p style="font-size:0.8rem; color:#64748B; font-weight:600;">Klik untuk pilih hingga 6 foto galeri pendukung</p>
                                </div>
                            </div>
                            <div id="galleryPreview" style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:1rem;"></div>
                            <input type="file" name="gallery[]" id="gallery-input" accept="image/*" multiple max="6" onchange="previewGalleryList(event)" style="display:none;">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Link Video (Opsional)</label>
                            <input type="url" name="youtube_video_url" value="{{ old('youtube_video_url') }}" class="form-input" placeholder="YouTube, TikTok, dll">
                        </div>
                    </div>
                </div>

                <div class="prof-card">
                    <div class="prof-card-head">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Akses File Digital <span>*</span>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="form-label">Tautan Eksternal (G-Drive / Notion / Website)</label>
                            <input type="url" name="external_link" id="externalLink" value="{{ old('external_link') }}" class="form-input" placeholder="https://..." required>
                            <span id="linkStatus" style="font-size:0.75rem; font-weight:600; display:none; margin-top:0.5rem; padding:0.5rem; border-radius:6px;"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 3: SEO & FAQ --}}
            <div class="tab-pane" id="tab-seo">
                <div class="prof-card">
                    <div class="prof-card-head">
                        Meta Data Custom
                    </div>
                    <div class="form-body">
                        <div class="form-hint" style="margin-bottom:1.25rem;">Jika dikosongkan, sistem akan otomatis menggunakan Informasi Utama Produk untuk SEO.</div>
                        <div class="form-group">
                            <label class="form-label">Meta Title SEO</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Meta Description SEO</label>
                            <textarea name="meta_description" class="form-input" rows="2">{{ old('meta_description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="form-input">
                        </div>
                    </div>
                </div>

                <div class="prof-card">
                    <div class="prof-card-head" style="justify-content:space-between;">
                        FAQ Schema
                        <button type="button" onclick="addFaqRow()" style="background:#f0fdf4; color:#1eb349; border:none; padding:0.4rem 0.8rem; border-radius:6px; font-weight:700; cursor:pointer; font-size:0.8rem;">+ Tambah FAQ</button>
                    </div>
                    <div class="form-body" id="faqContainer">
                        @php $oldFaqs = old('faqs', [['question'=>'', 'answer'=>'']]); @endphp
                        @foreach($oldFaqs as $idx => $faq)
                        <div class="faq-row" style="background:#f8fafc; border:1px solid #e2e8f0; padding:1rem; border-radius:12px; margin-bottom:1rem; position:relative;">
                            <div class="form-group">
                                <label class="form-label">Pertanyaan</label>
                                <input type="text" name="faqs[{{$idx}}][question]" value="{{ $faq['question'] ?? '' }}" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jawaban</label>
                                <textarea name="faqs[{{$idx}}][answer]" class="form-input" rows="2">{{ $faq['answer'] ?? '' }}</textarea>
                            </div>
                            @if($idx > 0)
                            <button type="button" onclick="this.closest('.faq-row').remove()" style="position:absolute; top:1rem; right:1rem; background:#fee2e2; color:#ef4444; border:none; border-radius:6px; padding:0.25rem 0.5rem; cursor:pointer; font-size:0.75rem; font-weight:bold;">Hapus</button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('creator.products.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan & Publikasikan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Tab switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');
        });
    });

    // SEO Mode Toggle
    function toggleSeoMode() {
        const isAdvanced = document.getElementById('seoModeToggle').checked;
        const seoBtn = document.getElementById('tab-seo-btn');
        if(isAdvanced) {
            seoBtn.style.display = 'flex';
        } else {
            seoBtn.style.display = 'none';
            // Switch tab if seo is active
            if(seoBtn.classList.contains('active')) {
                document.querySelector('[data-tab="tab-content"]').click();
            }
        }
    }
    // Initialize
    toggleSeoMode();

    // FAQ Add Row
    let faqIdx = {{ count($oldFaqs) }};
    function addFaqRow() {
        const container = document.getElementById('faqContainer');
        const html = `
        <div class="faq-row" style="background:#f8fafc; border:1px solid #e2e8f0; padding:1rem; border-radius:12px; margin-bottom:1rem; position:relative;">
            <div class="form-group">
                <label class="form-label">Pertanyaan</label>
                <input type="text" name="faqs[${faqIdx}][question]" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Jawaban</label>
                <textarea name="faqs[${faqIdx}][answer]" class="form-input" rows="2"></textarea>
            </div>
            <button type="button" onclick="this.closest('.faq-row').remove()" style="position:absolute; top:1rem; right:1rem; background:#fee2e2; color:#ef4444; border:none; border-radius:6px; padding:0.25rem 0.5rem; cursor:pointer; font-size:0.75rem; font-weight:bold;">Hapus</button>
        </div>`;
        container.insertAdjacentHTML('beforeend', html);
        faqIdx++;
    }

    // Money Formatter
    function formatRupiah(val) {
        let numberString = val.replace(/[^,\d]/g, '').toString();
        let split = numberString.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    }
    document.querySelectorAll('.currency-input').forEach(input => {
        input.addEventListener('input', function() { this.value = formatRupiah(this.value); });
    });

    // Single Thumb
    function previewSingleThumb(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('thumbPreviewImg').src = URL.createObjectURL(file);
            document.getElementById('thumbPreviewWrap').style.display = 'block';
            document.getElementById('thumbDropzone').style.display = 'none';
        }
    }
    function removeThumbnail() {
        document.getElementById('thumb-input').value = '';
        document.getElementById('thumbPreviewWrap').style.display = 'none';
        document.getElementById('thumbDropzone').style.display = 'block';
    }

    // Sub Kategori
    const subData = @json($categories->map(fn($c) => ['id' => $c->id, 'subs' => $c->subCategories->map(fn($s) => ['id' => $s->id, 'name' => $s->name])]));
    function loadSubCat(catId) {
        const wrap = document.getElementById('subCatWrap');
        const sel = document.getElementById('subCatSelect');
        if (!catId) { wrap.style.display = 'none'; return; }
        const found = subData.find(c => c.id == catId);
        if (!found || !found.subs.length) { wrap.style.display = 'none'; return; }
        sel.innerHTML = '<option value="">— Pilih Sub-Kategori —</option>';
        found.subs.forEach(s => { const o = document.createElement('option'); o.value = s.id; o.textContent = s.name; sel.appendChild(o); });
        wrap.style.display = 'block';
    }
    const initCat = document.getElementById('catSelect').value;
    if (initCat) loadSubCat(initCat);
</script>
@endsection
