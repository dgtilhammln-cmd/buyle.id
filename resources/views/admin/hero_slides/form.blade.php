@extends('layouts.admin')
@section('title', isset($slide) ? 'Edit Banner' : 'Tambah Banner')
@section('page-title', isset($slide) ? 'Edit Banner' : 'Tambah Banner Baru')
@section('content')

    <style>
        .banner-form-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            padding: 1.35rem 1.6rem;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.25rem;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        .banner-form-title {
            font-size: 0.8rem;
            font-weight: 800;
            color: #16a34a;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 0 0 1.15rem 0;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .form-label-custom {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.4rem;
        }

        .form-input-custom {
            width: 100%;
            height: 42px;
            padding: 0 0.85rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.84rem;
            color: #0f172a;
            background: #ffffff;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            box-sizing: border-box;
        }

        .form-input-custom:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .form-textarea-custom {
            width: 100%;
            padding: 0.75rem 0.85rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.84rem;
            color: #0f172a;
            background: #ffffff;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            box-sizing: border-box;
            resize: vertical;
        }

        .form-textarea-custom:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .form-hint {
            font-size: 0.72rem;
            color: #64748b;
            margin-top: 0.35rem;
            line-height: 1.4;
        }
    </style>

    <div style="max-width: 860px; margin: 0 auto; padding-bottom: 3rem;">

        <form method="POST" action="{{ isset($slide) ? route('admin.hero_slides.update', $slide) : route('admin.hero_slides.store') }}" enctype="multipart/form-data" id="bannerForm">
            @csrf
            @if(isset($slide)) @method('PUT') @endif

            @if($errors->any())
                <div style="background:#fef2f2; border:1.5px solid #fca5a5; color:#991b1b; padding:0.9rem 1.25rem; border-radius:12px; margin-bottom:1.25rem; font-size:0.84rem; font-weight:700;">
                    <ul style="margin:0; padding-left:1.2rem;">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 1. POSISI & KONTEN BANNER --}}
            <div class="banner-form-card">
                <h3 class="banner-form-title">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><polyline points="21 15 16 10 5 21"/></svg>
                    Posisi & Konten Banner
                </h3>

                <div style="display:flex; flex-direction:column; gap:1rem;">
                    <div>
                        <label class="form-label-custom">
                            Posisi Penempatan Banner <span style="color:#dc2626;">*</span>
                        </label>
                        <select name="position" id="positionSelect" class="form-input-custom" required onchange="handlePositionChange(this.value)">
                            @php $defaultPos = old('position', $slide->position ?? request()->get('position', 'hero')); @endphp
                            <option value="hero" {{ $defaultPos == 'hero' ? 'selected' : '' }}>
                                🖼️ Hero Slider (Halaman Utama / Homepage)
                            </option>
                            <option value="tix" {{ $defaultPos == 'tix' ? 'selected' : '' }}>
                                🎟️ Buyle TIX Banner (Landscape 1920 x 600 di /tix)
                            </option>
                            <option value="partner" {{ $defaultPos == 'partner' ? 'selected' : '' }}>
                                🤝 Media Partner Buyle TIX (Logo Sponsor/Mitra)
                            </option>
                            <option value="utama" {{ $defaultPos == 'utama' ? 'selected' : '' }}>
                                ↗️ Banner Samping Kanan Atas (Home)
                            </option>
                            <option value="samping" {{ $defaultPos == 'samping' ? 'selected' : '' }}>
                                ↘️ Banner Samping Kanan Bawah (Home)
                            </option>
                        </select>
                        <p class="form-hint" id="positionHint">
                            Banner Buyle TIX akan otomatis tampil di slider 1920 x 600 di https://buyle.id/tix.
                        </p>
                    </div>

                    <div>
                        <label class="form-label-custom">Judul Banner <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $slide->title ?? '') }}" class="form-input-custom" required placeholder="Contoh: KONSER NASYID NOSTALGIA 2026">
                    </div>

                    <div>
                        <label class="form-label-custom">Deskripsi / Subtitle</label>
                        <textarea name="description" class="form-textarea-custom" rows="3" placeholder="Contoh: SNADA ★ IZZATUL ISLAM ★ EDCOUSTIC ★ THE RAYHAN">{{ old('description', $slide->description ?? '') }}</textarea>
                        <p class="form-hint">Maksimal 500 karakter. Untuk Buyle TIX, deskripsi akan menjadi subtitle bintang tamu / info konser.</p>
                    </div>

                    <div>
                        <label class="form-label-custom">Organizer / Lokasi & Tanggal (Atau Ikon)</label>
                        <input type="text" name="icon" value="{{ old('icon', $slide->icon ?? '') }}" class="form-input-custom" placeholder="Contoh: TRUST ENTERTAINMENT atau JAKARTA • 15 NOV 2026">
                        <p class="form-hint">Pada Buyle TIX, teks ini akan tampil sebagai nama penyelenggara resmi di atas judul banner.</p>
                    </div>
                </div>
            </div>

            {{-- 2. GAMBAR BANNER --}}
            <div class="banner-form-card">
                <h3 class="banner-form-title">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Gambar Banner
                </h3>

                @if(isset($slide) && $slide->image)
                    <div id="currentImageContainer" style="margin-bottom:1rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem;">
                        <p style="font-size:0.75rem; font-weight:700; color:#475569; margin:0 0 0.5rem 0;">Gambar Saat Ini:</p>
                        <img src="{{ asset('storage/'.$slide->image) }}" style="max-height:140px; border-radius:8px; object-fit:contain; background:#0f172a; display:block;">
                    </div>
                @endif

                <div>
                    <label class="form-label-custom">Upload Gambar Banner Baru</label>
                    <input type="file" id="imageInput" name="image" accept="image/*" class="form-input-custom" style="padding-top:0.45rem;">
                    <input type="hidden" name="image_base64" id="imageBase64">

                    <div id="previewContainer" style="display:none; margin-top:1rem; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:0.85rem;">
                        <p style="font-size:0.78rem; font-weight:800; color:#166534; margin:0 0 0.5rem 0;">✓ Preview Gambar (Siap Disimpan):</p>
                        <img id="imagePreview" style="max-height:180px; border-radius:8px; object-fit:cover; display:block;">
                        <button type="button" id="btnRemoveImage" style="margin-top:0.5rem; background:none; border:none; color:#dc2626; font-size:0.75rem; font-weight:700; cursor:pointer; text-decoration:underline;">
                            ✕ Hapus & Batalkan Gambar Baru
                        </button>
                    </div>

                    <p class="form-hint" id="imageRatioHint">
                        💡 <strong>Rekomendasi Ukuran:</strong><br>
                        • <strong>Buyle TIX:</strong> 1920 x 600 px (Landscape rasio 3.2:1)<br>
                        • <strong>Hero Slider Home:</strong> 1200 x 525 px (Rasio 16:7)<br>
                        • <strong>Banner Kanan:</strong> 600 x 450 px (Rasio 4:3)
                    </p>
                </div>
            </div>

            {{-- 3. LINK / TOMBOL CTA & PENGATURAN --}}
            <div class="banner-form-card">
                <h3 class="banner-form-title">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Aksi Tombol & Pengaturan Tampil
                </h3>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label class="form-label-custom">Teks Tombol CTA</label>
                        <input type="text" name="button_text" value="{{ old('button_text', $slide->button_text ?? 'Beli Tiket') }}" class="form-input-custom" placeholder="Contoh: Beli Tiket / Lihat Detail">
                    </div>
                    <div>
                        <label class="form-label-custom">URL / Link Tujuan</label>
                        <input type="text" name="button_url" value="{{ old('button_url', $slide->button_url ?? '') }}" class="form-input-custom" placeholder="Contoh: /produk/nama-tiket atau /tix">
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap; border-top:1px dashed #f1f5f9; padding-top:1rem;">
                    <div style="width:120px;">
                        <label class="form-label-custom">Urutan Tampil</label>
                        <input type="number" name="order" value="{{ old('order', $slide->order ?? 0) }}" class="form-input-custom" min="0">
                    </div>

                    <div style="display:flex; align-items:center; gap:0.6rem; padding-top:1.25rem;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $slide->is_active ?? true) ? 'checked' : '' }} style="accent-color:#16a34a; width:18px; height:18px; cursor:pointer;">
                        <label for="is_active" style="font-size:0.85rem; font-weight:700; color:#0f172a; cursor:pointer;">
                            Aktifkan & Tampilkan Banner
                        </label>
                    </div>
                </div>
            </div>

            {{-- 4. SUBMIT BUTTONS --}}
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <button type="submit" style="height:44px; padding:0 1.75rem; border-radius:999px; font-weight:800; font-size:0.85rem; background:linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color:#ffffff; border:none; cursor:pointer; display:flex; align-items:center; gap:0.4rem; box-shadow:0 4px 14px rgba(30,179,73,0.35);">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Simpan Banner</span>
                </button>
                <a href="{{ route('admin.hero_slides.index') }}" style="height:44px; padding:0 1.25rem; border-radius:999px; font-weight:700; font-size:0.85rem; background:#f1f5f9; color:#475569; text-decoration:none; display:inline-flex; align-items:center; border:1px solid #e2e8f0;">
                    Batal
                </a>
            </div>

        </form>

    </div>

    <script>
        const imageInput = document.getElementById('imageInput');
        const imageBase64 = document.getElementById('imageBase64');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const btnRemoveImage = document.getElementById('btnRemoveImage');
        const currentImageContainer = document.getElementById('currentImageContainer');

        if (imageInput) {
            imageInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        imageBase64.value = event.target.result;
                        imagePreview.src = event.target.result;
                        previewContainer.style.display = 'block';
                        if (currentImageContainer) currentImageContainer.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (btnRemoveImage) {
            btnRemoveImage.addEventListener('click', function() {
                imageBase64.value = '';
                imageInput.value = '';
                previewContainer.style.display = 'none';
                if (currentImageContainer) currentImageContainer.style.display = 'block';
            });
        }

        function handlePositionChange(val) {
            const hint = document.getElementById('positionHint');
            if (val === 'tix') {
                hint.innerHTML = '🌟 <strong>Buyle TIX:</strong> Banner akan tampil di carousel 1920 x 600 di https://buyle.id/tix.';
            } else if (val === 'hero') {
                hint.innerHTML = '🖼️ <strong>Hero Slider:</strong> Banner utama di slider tengah homepage.';
            } else if (val === 'partner') {
                hint.innerHTML = '🤝 <strong>Media Partner TIX:</strong> Logo sponsor/mitra akan tampil di baris bawah hero banner /tix. Upload logo transparan (SVG/PNG). Judul/title = Nama Brand.';
            } else {
                hint.innerHTML = '📌 <strong>Banner Samping:</strong> Banner tetap di samping kanan hero slider.';
            }
        }
    </script>

@endsection
