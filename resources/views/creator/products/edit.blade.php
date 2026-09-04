@extends('creator.layout')

@section('title', 'Edit Produk Digital')
@section('page_title', 'Edit Produk')
@section('breadcrumb', 'Katalog › Produk › Edit')

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
                    <input type="checkbox" name="is_advanced" id="seoModeToggle" onchange="toggleSeoMode()" style="opacity:0; width:0; height:0;" {{ old('is_advanced', ($product->meta_title || $product->meta_desc || !empty($product->faqs)) ? '1' : '') ? 'checked' : '' }}>
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
        <form action="{{ route('creator.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')

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
                                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input" placeholder="Contoh: Template Premium Social Media Pack" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kategori Utama <span>*</span></label>
                                <select name="product_category_id" id="catSelect" class="form-input" required onchange="loadSubCat(this.value)">
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('product_category_id', $product->product_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                                        <option value="{{ $grp->id }}" {{ old('creator_group_id', $product->creator_group_id) == $grp->id ? 'selected' : '' }}>{{ $grp->name }}</option>
                                    @endforeach
                                </select>
                                <span class="form-hint"><a href="{{ route('creator.groups.index') }}" style="color:#1eb349;text-decoration:none;">Buat Kelompok Baru?</a></span>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Harga Normal (Rp) <span>*</span></label>
                                <input type="text" name="price" id="input_price" value="{{ old('price', number_format($product->price, 0, ',', '.')) }}" class="form-input currency-input" placeholder="Misal: 50.000" required autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Harga Coret / Diskon (Rp)</label>
                                <input type="text" name="sale_price" id="input_sale_price" value="{{ old('sale_price', $product->sale_price ? number_format($product->sale_price, 0, ',', '.') : '') }}" class="form-input currency-input" placeholder="Opsional, misal: 99.000" autocomplete="off">
                            </div>

                            <div class="form-group full">
                                <label class="form-label">Deskripsi Singkat (Exerp) <span>*</span></label>
                                <textarea name="short_desc" class="form-input" rows="2" placeholder="Deskripsi singkat maksimal 160 karakter" maxlength="160" required>{{ old('short_desc', $product->short_desc) }}</textarea>
                                <span class="form-hint">Digunakan sebagai meta description SEO jika mode advanced dimatikan.</span>
                            </div>

                            <div class="form-group full" style="margin-bottom:0;">
                                <label class="form-label">Deskripsi Lengkap <span>*</span></label>
                                @include('admin.partials.rich-editor', ['name' => 'description', 'value' => old('description', $product->description), 'height' => '280px'])
                            </div>
                        </div>
                        
                        <div class="form-group" style="flex-direction:row; align-items:center; gap:0.75rem; margin-top:2rem; padding-top:1rem; border-top:1px solid #f3f7f3;">
                            <label class="toggle-switch" style="position:relative; width:44px; height:24px; flex-shrink:0;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} style="opacity:0; width:0; height:0;">
                                <span class="toggle-slider-s"></span>
                            </label>
                            <div>
                                <div class="form-label" style="font-size:0.9rem;">Status Aktif</div>
                                <div class="form-hint">Tampilkan produk ini di etalase toko.</div>
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
                            <label class="form-label">Upload Thumbnail Utama</label>
                            <div class="img-upload-area" id="thumbDropzone" onclick="document.getElementById('thumb-input').click()" style="margin-bottom:0.75rem; {{ $product->image ? 'display:none;' : '' }}">
                                <div>
                                    <svg width="32" height="32" fill="none" stroke="#1eb349" stroke-width="1.6" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><polyline points="21 15 16 10 5 21" /></svg>
                                    <p style="font-size:0.82rem; color:#1E293B; margin-top:0.4rem; font-weight:700;">Ganti Thumbnail Utama (1 Foto)</p>
                                </div>
                            </div>
                            <div id="thumbPreviewWrap" style="{{ $product->image ? 'display:block;' : 'display:none;' }} margin-bottom:1rem;">
                                <div style="position:relative; display:inline-block; border-radius:12px; overflow:hidden; border:2px solid #1eb349;">
                                    <img id="thumbPreviewImg" src="{{ $product->image ? asset('storage/'.$product->image) : '' }}" style="width:110px; height:110px; object-fit:cover;">
                                    <button type="button" onclick="removeThumbnail()" style="position:absolute; top:4px; right:4px; background:#ef4444; color:#fff; border:none; border-radius:50%; width:24px; height:24px; cursor:pointer;" title="Hapus">&times;</button>
                                </div>
                            </div>
                            <input type="file" name="image" id="thumb-input" accept="image/*" onchange="previewSingleThumb(event)" style="display:none;">
                            <span class="form-hint">Biarkan kosong jika tidak ingin mengubah thumbnail saat ini.</span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Upload Foto Galeri / Screenshot (Opsional)</label>
                            <div class="img-upload-area" id="galleryDropzone" onclick="document.getElementById('gallery-input').click()" style="margin-bottom:0.75rem;">
                                <div>
                                    <p style="font-size:0.8rem; color:#64748B; font-weight:600;">Klik untuk ganti galeri pendukung (Maks 6 foto)</p>
                                </div>
                            </div>
                            <div id="galleryPreview" style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:1rem;">
                                @if(!empty($product->gallery) && is_array($product->gallery))
                                    @foreach($product->gallery as $idx => $gal)
                                        <div style="position:relative; display:inline-block; border-radius:10px; overflow:hidden; border:1px solid #cbd5e1; box-shadow:0 2px 6px rgba(0,0,0,0.06);">
                                            <img src="{{ asset('storage/'.$gal) }}" style="width:90px; height:90px; object-fit:cover; display:block;">
                                            <div style="position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,0.65); color:#fff; font-size:9px; font-weight:600; text-align:center; padding:2px 0;">Lama #{{$idx+1}}</div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <input type="file" name="gallery[]" id="gallery-input" accept="image/*" multiple max="6" onchange="previewGalleryList(event)" style="display:none;">
                            <span class="form-hint">Jika Anda memilih file galeri baru, galeri lama akan digantikan sepenuhnya.</span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Link Video (Opsional)</label>
                            <input type="url" name="youtube_video_url" value="{{ old('youtube_video_url', $product->youtube_video_url ?? $product->tiktok_video_url) }}" class="form-input" placeholder="YouTube, TikTok, dll">
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
                            <input type="url" name="digital_resource" id="externalLink" value="{{ old('digital_resource', $product->digital_resource) }}" class="form-input" placeholder="https://..." required>
                            <span id="linkStatus" style="font-size:0.75rem; font-weight:600; display:none; margin-top:0.5rem; padding:0.5rem; border-radius:6px;"></span>
                        </div>
                    </div>
                </div>

                {{-- WHITE LABEL CARD --}}
                <div class="prof-card" style="border: 2px solid #E2E8F0; transition: border-color 0.2s;" id="whitelabelCard">
                    <div class="prof-card-head" style="background: linear-gradient(135deg, #F8FAFC 0%, #F0FDF4 100%); display:flex; justify-content:space-between; align-items:center;">
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                            <span style="color:#1E293B; font-weight:800;">Fitur White Label & Lisensi Jual Kembali (Reseller / Makelar)</span>
                        </div>

                        @if($product->is_whitelabel)
                            @if($product->whitelabel_approval_status === 'pending')
                                <span style="background:#FEF3C7; color:#D97706; font-size:.73rem; font-weight:700; padding:.25rem .65rem; border-radius:20px; display:inline-flex; align-items:center; gap:.3rem;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Menunggu Review Tim Buyle
                                </span>
                            @elseif($product->whitelabel_approval_status === 'approved')
                                <span style="background:#DCFCE7; color:#16A34A; font-size:.73rem; font-weight:700; padding:.25rem .65rem; border-radius:20px; display:inline-flex; align-items:center; gap:.3rem;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    Approved (Disetujui)
                                </span>
                            @elseif($product->whitelabel_approval_status === 'rejected')
                                <span style="background:#FEE2E2; color:#DC2626; font-size:.73rem; font-weight:700; padding:.25rem .65rem; border-radius:20px; display:inline-flex; align-items:center; gap:.3rem;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Ditolak Admin
                                </span>
                            @endif
                        @endif
                    </div>
                    <div class="form-body">
                        @if($product->is_whitelabel && $product->whitelabel_approval_status === 'rejected' && $product->whitelabel_rejection_reason)
                            <div style="background:#FEE2E2; border:1px solid #FCA5A5; border-radius:12px; padding:0.9rem 1rem; margin-bottom:1.25rem; font-size:0.83rem; color:#991B1B;">
                                <strong style="display:flex; align-items:center; gap:0.4rem; margin-bottom:0.2rem;">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    Catatan Penolakan dari Admin Tim Buyle:
                                </strong>
                                <div>{{ $product->whitelabel_rejection_reason }}</div>
                                <div style="font-size:0.75rem; margin-top:0.3rem; color:#7F1D1D;">Silakan perbaiki aset/file Anda agar netral (tanpa watermark) dan simpan ulang untuk mengajukan review baru.</div>
                            </div>
                        @endif

                        <div style="background:#F0FDF4; border:1px solid #BBF7D0; border-radius:12px; padding:1rem; margin-bottom:1.25rem;">
                            <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; font-weight:700; color:#15803D; font-size:0.88rem;">
                                <input type="checkbox" name="is_whitelabel" id="isWhitelabelCheck" value="1" {{ old('is_whitelabel', $product->is_whitelabel) ? 'checked' : '' }} onchange="toggleWhitelabelFields(this.checked)" style="width:18px; height:18px; accent-color:#1eb349; cursor:pointer;">
                                <span>Tawarkan Produk Ini Sebagai White Label (Dapat Dijual Kembali Oleh Reseller / Creator Lain)</span>
                            </label>
                        </div>

                        <div id="whitelabelOptionsWrap" style="display:none;">
                            <div style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:12px; padding:1rem; margin-bottom:1.25rem; font-size:0.83rem; color:#92400E; line-height:1.5;">
                                <strong style="display:flex; align-items:center; gap:0.4rem; margin-bottom:0.4rem; font-size:0.88rem; color:#B45309;">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                    Ketentuan & Panduan White Label:
                                </strong>
                                <ul style="margin:0; padding-left:1.2rem; display:flex; flex-direction:column; gap:0.25rem;">
                                    <li><strong>Aset Netral:</strong> File / link produk <u>TIDAK BOLEH</u> mengandung watermark, logo personal, atau nomor kontak yang mengikat.</li>
                                    <li><strong>Bebas Markup:</strong> Creator / reseller lain dapat menyalin link produk ini ke Bio Link / Store mereka dan menaikkan harga jual sesuai keinginan.</li>
                                    <li><strong>Harga Dasar:</strong> Menggunakan harga produk utama yang diinput pada Informasi Utama Produk.</li>
                                    <li><strong>Approval Tim Buyle:</strong> Setelah disimpan/diubah, produk akan ditinjau kembali oleh Admin Tim Buyle jika ada perubahan status.</li>
                                </ul>
                            </div>

                            <div class="form-grid">
                                <div class="form-group full">
                                    <label class="form-label">Ketentuan Lisensi / Catatan Reseller (Opsional)</label>
                                    <textarea name="whitelabel_terms" class="form-input" rows="2" placeholder="Contoh: Boleh ubah nama & cover produk, dilarang menjual di bawah harga normal...">{{ old('whitelabel_terms', $product->whitelabel_terms) }}</textarea>
                                </div>
                            </div>
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
                            <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Meta Description SEO</label>
                            <textarea name="meta_description" class="form-input" rows="2">{{ old('meta_description', $product->meta_desc) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" class="form-input">
                        </div>
                    </div>
                </div>

                <div class="prof-card">
                    <div class="prof-card-head" style="justify-content:space-between;">
                        FAQ Schema
                        <button type="button" onclick="addFaqRow()" style="background:#f0fdf4; color:#1eb349; border:none; padding:0.4rem 0.8rem; border-radius:6px; font-weight:700; cursor:pointer; font-size:0.8rem;">+ Tambah FAQ</button>
                    </div>
                    <div class="form-body" id="faqContainer">
                        @php 
                            $oldFaqs = old('faqs', !empty($product->faqs) ? $product->faqs : [['question'=>'', 'answer'=>'']]); 
                        @endphp
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
                            <button type="button" onclick="this.closest('.faq-row').remove()" style="position:absolute; top:1rem; right:1rem; background:#fee2e2; color:#ef4444; border:none; border-radius:6px; padding:0.25rem 0.5rem; cursor:pointer; font-size:0.75rem; font-weight:bold;">Hapus</button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('creator.products.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
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
    let faqIdx = {{ max(count($oldFaqs), 10) }};
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
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('thumbPreviewImg');
                const wrap = document.getElementById('thumbPreviewWrap');
                const drop = document.getElementById('thumbDropzone');
                if (img) img.src = e.target.result;
                if (wrap) wrap.style.display = 'block';
                if (drop) drop.style.display = 'none';

                // Launch Cropper for fine-tuning
                initImageCropper(input, {
                    aspectRatio: 1,
                    width: 800,
                    height: 800,
                    title: 'Crop Foto Produk Utama (1:1 Square)',
                    onCropSuccess: function(file, dataUrl) {
                        if (img) img.src = dataUrl;
                    }
                });
            };
            reader.readAsDataURL(input.files[0]);
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
        const oldSub = '{{ old('product_sub_category_id', $product->product_sub_category_id) }}';
        if (!catId) { wrap.style.display = 'none'; return; }
        const found = subData.find(c => c.id == catId);
        if (!found || !found.subs.length) { wrap.style.display = 'none'; return; }
        sel.innerHTML = '<option value="">— Pilih Sub-Kategori —</option>';
        found.subs.forEach(s => { 
            const o = document.createElement('option'); 
            o.value = s.id; 
            o.textContent = s.name; 
            if(s.id == oldSub) o.selected = true;
            sel.appendChild(o); 
        });
        wrap.style.display = 'block';
    }
    const initCat = document.getElementById('catSelect').value;
    if (initCat) loadSubCat(initCat);

    // Whitelabel toggle
    function toggleWhitelabelFields(checked) {
        const wrap = document.getElementById('whitelabelOptionsWrap');
        const card = document.getElementById('whitelabelCard');
        if (wrap) wrap.style.display = checked ? 'block' : 'none';
        if (card) card.style.borderColor = checked ? '#0284C7' : '#E2E8F0';
    }
    // Init check
    const wlCheck = document.getElementById('isWhitelabelCheck');
    if (wlCheck) toggleWhitelabelFields(wlCheck.checked);
</script>
@endsection
