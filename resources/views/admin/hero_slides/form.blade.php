@extends('layouts.admin')
@section('title', isset($slide) ? 'Edit Hero Slide' : 'Tambah Hero Slide')
@section('page-title', isset($slide) ? 'Edit Hero Slide' : 'Tambah Hero Slide Baru')
@section('content')
<form method="POST" action="{{ isset($slide) ? route('admin.hero_slides.update', $slide) : route('admin.hero_slides.store') }}" enctype="multipart/form-data" id="bannerForm">
    @csrf @if(isset($slide)) @method('PUT') @endif

    @if($errors->any())
    <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#fca5a5;padding:.875rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:.875rem;">
        <ul style="margin:0;padding-left:1rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Main Info --}}
        <div class="admin-card">
            <h3 style="font-size:.7rem;font-weight:700;color:#38BDF8;text-transform:uppercase;letter-spacing:.1em;margin:0 0 1.25rem;">Konten Banner</h3>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div>
                    <label class="form-label">Posisi Banner <span style="color:#f87171;">*</span></label>
                    <select name="position" id="positionSelect" class="form-input" required>
                        <option value="hero" {{ old('position', $slide->position ?? 'hero') == 'hero' ? 'selected' : '' }}>Hero Slider (Utama)</option>
                        <option value="utama" {{ old('position', $slide->position ?? '') == 'utama' ? 'selected' : '' }}>Banner Utama (Samping Kanan Atas)</option>
                        <option value="samping" {{ old('position', $slide->position ?? '') == 'samping' ? 'selected' : '' }}>Banner Samping (Samping Kanan Bawah)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Judul Slide <span style="color:#f87171;">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $slide->title ?? '') }}" class="form-input" required placeholder="Pabrik & Manufaktur">
                </div>
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-input" rows="3" placeholder="Sirkulasi maksimal untuk membuang hawa panas...">{{ old('description', $slide->description ?? '') }}</textarea>
                    <p style="font-size:.7rem;color:rgba(255,255,255,.25);margin:.375rem 0 0;">Maks. 500 karakter.</p>
                </div>
                <div>
                    <label class="form-label">Ikon (Nama SVG / Kode)</label>
                    <input type="text" name="icon" value="{{ old('icon', $slide->icon ?? '') }}" class="form-input" placeholder="building | home | truck | coffee">
                    <p style="font-size:.7rem;color:rgba(255,255,255,.25);margin:.375rem 0 0;">Pilih: building, home, truck, coffee, activity, shield, clock, star</p>
                </div>
            </div>
        </div>

        {{-- Image --}}
        <div class="admin-card">
            <h3 style="font-size:.7rem;font-weight:700;color:#38BDF8;text-transform:uppercase;letter-spacing:.1em;margin:0 0 1.25rem;">Gambar Banner</h3>
            
            @if(isset($slide) && $slide->image)
                <div id="currentImageContainer">
                    <p style="font-size:0.75rem; color:#94A3B8; margin-bottom:0.5rem;">Gambar Saat Ini:</p>
                    <img src="{{ asset('storage/'.$slide->image) }}" style="height:120px;border-radius:8px;object-fit:cover;margin-bottom:1rem;display:block;">
                </div>
            @endif
            
            <input type="file" id="imageInput" name="image_file" accept="image/*" class="form-input" style="padding:.5rem;">
            <input type="hidden" name="image_base64" id="imageBase64">
            
            <div id="previewContainer" style="display:none; margin-top:1rem;">
                <p style="font-size:0.8rem; color:#38BDF8; margin-bottom:0.5rem; display:flex; align-items:center; gap:0.4rem;"><svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Preview Gambar (Siap Diupload):</p>
                <img id="imagePreview" style="max-height:200px; border-radius:10px; object-fit:cover; display:block;">
                <button type="button" id="btnRemoveImage" style="margin-top:0.5rem; display:inline-flex; align-items:center; gap:0.3rem; background:none; border:none; color:#f87171; font-size:0.75rem; cursor:pointer; text-decoration:underline;"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14H6L5 6"></path></svg>Hapus & Ganti Gambar</button>
            </div>
            
            <p style="font-size:.7rem;color:rgba(255,255,255,.4);margin:.5rem 0 0;">Upload gambar dengan rasio sesuai posisi (Hero: 16:7 | Banner Kanan: 4:3). Gambar akan disimpan sesuai aslinya.</p>
        </div>

        {{-- Button --}}
        <div class="admin-card">
            <h3 style="font-size:.7rem;font-weight:700;color:#38BDF8;text-transform:uppercase;letter-spacing:.1em;margin:0 0 1.25rem;">Tombol (Opsional)</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="button_text" value="{{ old('button_text', $slide->button_text ?? '') }}" class="form-input" placeholder="Lihat Detail">
                </div>
                <div>
                    <label class="form-label">URL / Link</label>
                    <input type="text" name="button_url" value="{{ old('button_url', $slide->button_url ?? '') }}" class="form-input" placeholder="#produk atau /products">
                </div>
            </div>
        </div>

        {{-- Meta --}}
        <div class="admin-card">
            <h3 style="font-size:.7rem;font-weight:700;color:#38BDF8;text-transform:uppercase;letter-spacing:.1em;margin:0 0 1.25rem;">Pengaturan</h3>
            <div style="display:flex;gap:1.5rem;align-items:center;">
                <div>
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="order" value="{{ old('order', $slide->order ?? 0) }}" class="form-input" min="0" style="width:80px;">
                </div>
                <div style="display:flex;align-items:flex-end;gap:.5rem;padding-bottom:.5rem;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $slide->is_active ?? true) ? 'checked' : '' }} style="accent-color:#38BDF8;width:16px;height:16px;">
                    <label for="is_active" style="font-size:.875rem;color:#D4D4D8;">Tampilkan di homepage</label>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:.75rem;">
            <button type="submit" class="btn-primary">Simpan Slide</button>
            <a href="{{ route('admin.hero_slides.index') }}" class="btn-outline">Batal</a>
        </div>
    </div>
</form>

<script>
    const imageInput = document.getElementById('imageInput');
    const imageBase64 = document.getElementById('imageBase64');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const btnRemoveImage = document.getElementById('btnRemoveImage');
    const currentImageContainer = document.getElementById('currentImageContainer');

    imageInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                // Set base64 value for submission
                imageBase64.value = event.target.result;
                // Show preview
                imagePreview.src = event.target.result;
                previewContainer.style.display = 'block';
                // Hide old current image if exists
                if (currentImageContainer) currentImageContainer.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    if (btnRemoveImage) {
        btnRemoveImage.addEventListener('click', function() {
            imageBase64.value = '';
            imageInput.value = '';
            previewContainer.style.display = 'none';
            if (currentImageContainer) currentImageContainer.style.display = 'block';
        });
    }
</script>

@endsection
