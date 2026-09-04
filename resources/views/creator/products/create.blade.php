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
    
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; width:100%; box-sizing:border-box; }
    .form-group { display:flex; flex-direction:column; gap:0.4rem; margin-bottom:1.25rem; width:100%; box-sizing:border-box; }
    .form-group.full { grid-column:1/-1; width:100%; box-sizing:border-box; }
    .form-label { font-size:0.8rem; font-weight:700; color:#374151; display:block; }
    .form-label span { color:#ef4444; }
    .form-input {
        width: 100%;
        box-sizing: border-box;
        height: 44px;
        padding: 0 1rem;
        border: 1.5px solid #e7f0e7;
        border-radius: 10px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.875rem;
        color: #1a1a1a;
        background: #f9fefb;
        outline: none;
        transition: all 0.2s;
        display: block;
    }
    .form-input:focus { border-color:#1eb349; background:#fff; box-shadow:0 0 0 3px rgba(30,179,73,0.1); }
    textarea.form-input { height:auto; padding:0.75rem 1rem; resize:vertical; min-height:90px; width:100%; box-sizing:border-box; }
    
    .form-hint { font-size:0.72rem; color:#94A3B8; display:block; margin-top:0.25rem; }
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
        width:100%; box-sizing:border-box;
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
        .prof-layout { flex-direction:column; width:100%; }
        .prof-sidebar { width:100%; position:static; box-sizing:border-box; }
        .prof-content { width:100%; max-width:100%; box-sizing:border-box; }
        .form-grid { grid-template-columns:1fr; gap:1rem; }
        .form-body { padding:1.25rem 1rem; }
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
                                <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Contoh: Tiket Konser Musik Fest 2026" required>
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

                            <div class="form-group">
                                <label class="form-label">Tipe Produk <span>*</span></label>
                                <select name="product_type" id="productTypeSelect" class="form-input" onchange="toggleProductTypeFields(this.value)" required>
                                    <option value="external_link" {{ old('product_type') == 'external_link' ? 'selected' : '' }}>Produk Digital / Link Access</option>
                                    <option value="ticket" {{ old('product_type') == 'ticket' ? 'selected' : '' }}>Tiket Event / Wisata / Webinar</option>
                                </select>
                            </div>

                            <div class="form-group full" id="ticketFieldsWrap" style="display:none; background:#F0FDF4; border:1.5px solid #BBF7D0; border-radius:16px; padding:1.5rem; width:100%; box-sizing:border-box;">
                                <h5 style="font-size:0.95rem; font-weight:800; color:#166534; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem; border-bottom:1px dashed #bbf7d0; padding-bottom:0.75rem;">
                                    <svg width="20" height="20" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/><path d="M13 5v14"/><path d="M13 9h.01"/><path d="M13 15h.01"/></svg>
                                    Detail Informasi Event / Tiket
                                </h5>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Pelaksanaan Event <span>*</span></label>
                                        <input type="date" name="event_date" value="{{ old('event_date') }}" class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Waktu Event (Jam) <span>*</span></label>
                                        <input type="text" name="event_time" value="{{ old('event_time') }}" class="form-input" placeholder="Contoh: 19:00 - 22:00 WIB">
                                    </div>
                                    <div class="form-group full">
                                        <label class="form-label">Jenis Pelaksanaan Event <span>*</span></label>
                                        <select name="event_type" id="eventTypeSelect" class="form-input" onchange="toggleEventTypeFields(this.value)">
                                            <option value="offline" {{ old('event_type') == 'offline' ? 'selected' : '' }}>Offline (Tatap Muka di Venue / Gedung / Tempat Acara)</option>
                                            <option value="online" {{ old('event_type') == 'online' ? 'selected' : '' }}>Online (Webinar / Zoom Meeting / Live Streaming)</option>
                                        </select>
                                    </div>

                                    <div class="form-group full" id="offlineLocationWrap">
                                        <label class="form-label" style="display:flex; align-items:center; gap:0.4rem;">
                                            <svg width="15" height="15" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            Alamat / Lokasi Venue Event (Offline) <span>*</span>
                                        </label>
                                        <input type="text" name="event_location_offline" id="eventLocationOffline" value="{{ old('event_location_offline') }}" class="form-input" placeholder="Contoh: Gedung Senayan Hall A, Jl. Asia Afrika, Jakarta Pusat">
                                        <span class="form-hint">Tuliskan nama tempat / alamat fisik tempat acara berlangsung.</span>
                                    </div>

                                    <div class="form-group full" id="onlineLocationWrap" style="display:none;">
                                        <label class="form-label" style="display:flex; align-items:center; gap:0.4rem;">
                                            <svg width="15" height="15" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                            Link Access Online / Room Zoom / Streaming (Online) <span>*</span>
                                        </label>
                                        <input type="text" name="event_location_online" id="eventLocationOnline" value="{{ old('event_location_online') }}" class="form-input" placeholder="Contoh: https://zoom.us/j/123456789 atau Link Youtube Live">
                                        <span class="form-hint">Tautan ini hanya akan dikirimkan / dapat diakses oleh pembeli tiket online.</span>
                                    </div>
                                </div>
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
                                @include('admin.partials.rich-editor', ['name' => 'description', 'value' => old('description'), 'height' => '280px'])
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
                                <div style="position:relative; display:inline-block; border-radius:14px; overflow:hidden; border:2px solid #1eb349; box-shadow:0 4px 14px rgba(0,0,0,0.08);">
                                    <img id="thumbPreviewImg" src="" style="width:130px; height:130px; object-fit:cover; display:block;">
                                    <div style="position:absolute; top:6px; right:6px; display:flex; gap:4px; background:rgba(11,18,12,0.65); padding:3px 5px; border-radius:20px; backdrop-filter:blur(4px);">
                                        <button type="button" onclick="document.getElementById('thumb-input').click()" style="background:#3b82f6; color:#fff; border:none; border-radius:50%; width:24px; height:24px; cursor:pointer; font-size:11px; font-weight:bold; display:flex; align-items:center; justify-content:center;" title="Ganti Foto">✎</button>
                                        <button type="button" onclick="removeThumbnail()" style="background:#ef4444; color:#fff; border:none; border-radius:50%; width:24px; height:24px; cursor:pointer; font-size:11px; font-weight:bold; display:flex; align-items:center; justify-content:center;" title="Hapus Foto">✕</button>
                                    </div>
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

                <div class="prof-card" id="digitalAccessCard">
                    <div class="prof-card-head">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Akses File Digital <span>*</span>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="form-label">Tautan Eksternal (G-Drive / Notion / Website)</label>
                            <input type="url" name="digital_resource" id="externalLink" value="{{ old('digital_resource') }}" class="form-input" placeholder="https://..." required>
                            <span id="linkStatus" style="font-size:0.75rem; font-weight:600; display:none; margin-top:0.5rem; padding:0.5rem; border-radius:6px;"></span>
                        </div>
                    </div>
                </div>

                {{-- WHITE LABEL CARD --}}
                <div class="prof-card" style="border: 2px solid #E2E8F0; transition: border-color 0.2s;" id="whitelabelCard">
                    <div class="prof-card-head" style="background: linear-gradient(135deg, #F8FAFC 0%, #F0FDF4 100%); display:flex; align-items:center; gap:0.5rem;">
                        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        <span style="color:#1E293B; font-weight:800;">Fitur White Label & Lisensi Jual Kembali (Reseller / Makelar)</span>
                    </div>
                    <div class="form-body">
                        <div style="background:#F0FDF4; border:1px solid #BBF7D0; border-radius:12px; padding:1rem; margin-bottom:1.25rem;">
                            <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; font-weight:700; color:#15803D; font-size:0.88rem;">
                                <input type="checkbox" name="is_whitelabel" id="isWhitelabelCheck" value="1" {{ old('is_whitelabel') ? 'checked' : '' }} onchange="toggleWhitelabelFields(this.checked)" style="width:18px; height:18px; accent-color:#1eb349; cursor:pointer;">
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
                                    <li><strong>Approval Tim Buyle:</strong> Setelah produk disimpan, statusnya akan <code>Menunggu Approval Tim Buyle (Pending)</code>. Admin akan memverifikasi file Anda sebelum muncul di katalog White Label Marketplace.</li>
                                </ul>
                            </div>

                            <div class="form-grid">
                                <div class="form-group full">
                                    <label class="form-label">Ketentuan Lisensi / Catatan Reseller (Opsional)</label>
                                    <textarea name="whitelabel_terms" class="form-input" rows="2" placeholder="Contoh: Boleh ubah nama & cover produk, dilarang menjual di bawah harga normal...">{{ old('whitelabel_terms') }}</textarea>
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
        if (!catId) { wrap.style.display = 'none'; return; }
        const found = subData.find(c => c.id == catId);
        if (!found || !found.subs.length) { wrap.style.display = 'none'; return; }
        sel.innerHTML = '<option value="">— Pilih Sub-Kategori —</option>';
        found.subs.forEach(s => { const o = document.createElement('option'); o.value = s.id; o.textContent = s.name; sel.appendChild(o); });
        wrap.style.display = 'block';
    }
    const initCat = document.getElementById('catSelect').value;
    if (initCat) loadSubCat(initCat);
    // Whitelabel toggle
    function toggleWhitelabelFields(checked) {
        const wrap = document.getElementById('whitelabelOptionsWrap');
        const card = document.getElementById('whitelabelCard');
        if (wrap) wrap.style.display = checked ? 'block' : 'none';
        if (card) card.style.borderColor = checked ? '#1eb349' : '#E2E8F0';
    }
    // Init check
    const wlCheck = document.getElementById('isWhitelabelCheck');
    if (wlCheck) toggleWhitelabelFields(wlCheck.checked);

    // Event Type Toggle (Offline vs Online)
    function toggleEventTypeFields(val) {
        const offWrap = document.getElementById('offlineLocationWrap');
        const onWrap = document.getElementById('onlineLocationWrap');
        if (val === 'online') {
            if (offWrap) offWrap.style.display = 'none';
            if (onWrap) onWrap.style.display = 'block';
        } else {
            if (offWrap) offWrap.style.display = 'block';
            if (onWrap) onWrap.style.display = 'none';
        }
    }

    // Product Type Toggle
    function toggleProductTypeFields(val) {
        const wrap = document.getElementById('ticketFieldsWrap');
        const digitalCard = document.getElementById('digitalAccessCard');
        const extInput = document.getElementById('externalLink');
        const evType = document.getElementById('eventTypeSelect');
        
        if (val === 'ticket') {
            if (wrap) wrap.style.display = 'block';
            if (digitalCard) digitalCard.style.display = 'none';
            if (extInput) {
                extInput.removeAttribute('required');
            }
            if (evType) toggleEventTypeFields(evType.value);
        } else {
            if (wrap) wrap.style.display = 'none';
            if (digitalCard) digitalCard.style.display = 'block';
            if (extInput) {
                extInput.setAttribute('required', 'required');
            }
        }
    }
    const initPType = document.getElementById('productTypeSelect');
    if (initPType) toggleProductTypeFields(initPType.value);
</script>
@endsection
