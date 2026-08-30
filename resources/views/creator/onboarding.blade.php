@extends('creator.layout')

@section('title', 'Profil & Pengaturan Toko')
@section('page_title', 'Profil & Pengaturan Toko')
@section('breadcrumb', 'Pengaturan › Profil Toko')

@section('styles')
    <style>
        .form-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e7f0e7;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            max-width: 860px;
        }

        .form-section-title {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 1rem 1.5rem 0.5rem;
            border-top: 1px solid #f3f7f3;
            margin-top: 0.5rem;
        }

        .form-section-title:first-child {
            border-top: none;
            margin-top: 0;
        }

        .form-body {
            padding: 0 1.5rem 1.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .form-group.full {
            grid-column: 1/-1;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #374151;
        }

        .form-input {
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
        }

        .form-input:focus {
            border-color: #1eb349;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
        }

        textarea.form-input {
            height: auto;
            padding: 0.75rem 1rem;
            resize: vertical;
            min-height: 90px;
        }

        select.form-input {
            cursor: pointer;
        }

        .form-hint {
            font-size: 0.72rem;
            color: #94A3B8;
        }

        .form-error {
            font-size: 0.72rem;
            color: #ef4444;
        }

        /* Submit bar */
        .form-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #f3f7f3;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .btn-submit {
            height: 42px;
            padding: 0 1.5rem;
            border-radius: 10px;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            border: none;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.82rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.3);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.4);
        }

        @media(max-width:640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="creator-content">
        <form action="{{ route('creator.onboarding.store') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf


            {{-- Identitas Toko --}}
            <div class="form-section-title">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Identitas Toko
            </div>
            <div class="form-body">
                <div class="form-grid">
                    <div class="form-group full" style="display:flex; align-items:center; gap:1rem;">
                        <div class="form-group-content" style="flex:1;">
                            @if(auth()->user()->avatar)
                                <div style="margin-bottom:0.75rem;">
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar"
                                        style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #e7f0e7;">
                                </div>
                            @endif
                            <label class="form-label">Foto Profil Toko</label>
                            <input type="file" name="avatar" class="form-input" style="padding:10px;" accept="image/*">
                            <span class="form-hint">Maksimal 10MB. Akan dikonversi otomatis ke WebP.</span>
                            @error('avatar')
                            <div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Nama Toko / Creator</label>
                        <input type="text" name="store_name" value="{{ old('store_name', $profile->store_name) }}"
                            class="form-input" placeholder="Misal: HVM Digital Studio" maxlength="30" required>
                        <span class="form-hint">Maksimal 30 karakter.</span>
                        @error('store_name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Slug URL Toko</label>
                        <div style="display:flex; align-items:center; border:1.5px solid #e7f0e7; border-radius:10px; background:#f9fefb; overflow:hidden;"
                            class="slug-wrapper">
                            <span
                                style="padding:0 0.75rem; font-size:0.8rem; color:#94A3B8; background:#f1f5f9; border-right:1px solid #e7f0e7; height:44px; display:flex; align-items:center;">buyle.id/c/</span>
                            <input type="text" name="store_slug" value="{{ old('store_slug', $profile->store_slug) }}"
                                style="border:none; background:transparent; height:100%; padding:0 0.75rem; flex:1; outline:none; font-family:'Montserrat',sans-serif; font-size:0.875rem;"
                                placeholder="hvm-digital-studio" maxlength="30" required>
                        </div>
                        <span class="form-hint">Hanya huruf kecil, angka, dan strip (-). Kosongkan untuk nama
                            otomatis.</span>
                        @error('store_slug')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Deskripsi Toko</label>
                        <textarea name="store_description" class="form-input" rows="4"
                            placeholder="Ceritakan tentang toko Anda, spesialisasi Anda, dll." maxlength="60"
                            required>{{ old('store_description', $profile->store_description) }}</textarea>
                        <span class="form-hint">Maksimal 60 karakter.</span>
                        @error('store_description')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Alamat Toko --}}
            <div class="form-section-title">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Lokasi & Alamat (Untuk Validasi & Skema SEO)
            </div>
            <div class="form-body">
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
                        <textarea name="address" class="form-input" rows="2"
                            placeholder="Nama Jalan, Gedung, No. Rumah">{{ old('address', $profile->address) }}</textarea>
                    </div>

                    {{-- Hidden inputs to store names if needed for SEO fallback without querying EMSIFA again on frontend
                    --}}
                    <input type="hidden" id="provId_val" value="{{ old('province_id', $profile->province_id) }}">
                    <input type="hidden" id="cityId_val" value="{{ old('city_id', $profile->city_id) }}">
                    <input type="hidden" id="distId_val" value="{{ old('subdistrict_id', $profile->subdistrict_id) }}">
                </div>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn-submit">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                    </svg>
                    Jadilah Creator Sekarang
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Focus effect for slug wrapper
            const slugInput = document.querySelector('input[name="store_slug"]');
            const slugWrapper = document.querySelector('.slug-wrapper');
            slugInput.addEventListener('focus', () => { slugWrapper.style.borderColor = '#1eb349'; slugWrapper.style.boxShadow = '0 0 0 3px rgba(30,179,73,0.1)'; });
            slugInput.addEventListener('blur', () => { slugWrapper.style.borderColor = '#e7f0e7'; slugWrapper.style.boxShadow = 'none'; });

            // EMSIFA API WILAYAH INDONESIA
            const apiBase = "https://www.emsifa.com/api-wilayah-indonesia/api";

            const provSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');
            const distSelect = document.getElementById('subdistrict');

            const selProv = document.getElementById('provId_val').value;
            const selCity = document.getElementById('cityId_val').value;
            const selDist = document.getElementById('distId_val').value;

            // Load Provinces
            fetch(`${apiBase}/provinces.json`)
                .then(response => response.json())
                .then(provinces => {
                    provinces.forEach(p => {
                        let option = new Option(p.name, p.id);
                        if (p.id == selProv) option.selected = true;
                        provSelect.add(option);
                    });
                    if (selProv) loadCities(selProv, selCity);
                });

            // On Province Change
            provSelect.addEventListener('change', function () {
                citySelect.innerHTML = '<option value="">— Pilih Kabupaten/Kota —</option>';
                distSelect.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
                citySelect.disabled = true;
                distSelect.disabled = true;
                if (this.value) loadCities(this.value);
            });

            // On City Change
            citySelect.addEventListener('change', function () {
                distSelect.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
                distSelect.disabled = true;
                if (this.value) loadDistricts(this.value);
            });

            function loadCities(provId, selectedId = null) {
                fetch(`${apiBase}/regencies/${provId}.json`)
                    .then(res => res.json())
                    .then(cities => {
                        citySelect.innerHTML = '<option value="">— Pilih Kabupaten/Kota —</option>';
                        cities.forEach(c => {
                            let option = new Option(c.name, c.id);
                            if (c.id == selectedId) option.selected = true;
                            citySelect.add(option);
                        });
                        citySelect.disabled = false;
                        if (selectedId && selDist) loadDistricts(selectedId, selDist);
                    });
            }

            function loadDistricts(cityId, selectedId = null) {
                fetch(`${apiBase}/districts/${cityId}.json`)
                    .then(res => res.json())
                    .then(districts => {
                        distSelect.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
                        districts.forEach(d => {
                            let option = new Option(d.name, d.id);
                            if (d.id == selectedId) option.selected = true;
                            distSelect.add(option);
                        });
                    });
            }

            // ── Client-Side Image Compression (Fix 500 Error) ──
            const form = document.getElementById('profileForm');
            const submitBtn = document.querySelector('.btn-submit');
            const originalBtnText = submitBtn.innerHTML;

            async function compressImage(file, maxWidth = 1200, maxHeight = 1200) {
                if (!file.type.match(/image.*/) || file.type === 'image/webp') return file;
                return new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = new Image();
                        img.onload = () => {
                            let w = img.width, h = img.height;
                            const ratio = Math.min(maxWidth / w, maxHeight / h);
                            if (ratio < 1) { w *= ratio; h *= ratio; }

                            const canvas = document.createElement('canvas');
                            canvas.width = w; canvas.height = h;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, w, h);

                            canvas.toBlob((blob) => {
                                const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".webp", {
                                    type: 'image/webp', lastModified: Date.now()
                                });
                                resolve(newFile);
                            }, 'image/webp', 0.85);
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner" style="display:inline-block;width:14px;height:14px;border:2px solid #fff;border-top-color:transparent;border-radius:50%;animation:spin 1s linear infinite;margin-right:0.5rem;"></span> Mengompresi & Menyimpan...';

                try {
                    const dtAvatar = new DataTransfer();
                    const avatarInput = document.querySelector('input[name="avatar"]');
                    if (avatarInput && avatarInput.files[0]) {
                        const compressed = await compressImage(avatarInput.files[0], 800, 800);
                        dtAvatar.items.add(compressed);
                        avatarInput.files = dtAvatar.files;
                    }

                    form.submit();
                } catch (err) {
                    console.error('Compression error:', err);
                    form.submit(); // fallback submit directly
                }
            });
        });
    </script>
    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection