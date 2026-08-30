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
                        <label class="form-label">Tipe / Peran Creator <span style="color:#ef4444;">*</span></label>
                        <select name="creator_type" class="form-input" required>
                            <option value="">— Pilih Peran / Kategori Creator —</option>
                            @php
                                $creatorTypes = [
                                    'Content Creator' => 'Content Creator (Video, Edukasi & Lifestyle)',
                                    'Affiliate Marketer' => 'Affiliate Marketer / Affiliator Digital',
                                    'Graphic & UI/UX Designer' => 'Graphic, UI/UX & Visual Designer',
                                    'Video Editor & Motion' => 'Video Editor, Animator & 3D Artist',
                                    'Software Developer' => 'Software Developer / Programmer & Web Creator',
                                    'Course Creator' => 'Course Creator & Instruktur / Mentor Online',
                                    'Copywriter & Writer' => 'Copywriter, Penulis & Prompt Engineer',
                                    'Digital Marketer' => 'Digital Marketer & Agency Kreatif',
                                    'Konsultan & Coach' => 'Konsultan Bisnis / Digital Coach',
                                    'Kreator Template & Aset' => 'Kreator Template (Notion, Canva, Figma, Spreadsheet, dll)',
                                    'Fotografer & Videografer' => 'Fotografer & Kreator Media Visual',
                                    'Audio & Music Producer' => 'Musisi, Sound Engineer & Audio Creator',
                                    'Supplier / Vendor Digital' => 'Supplier / Vendor Produk Digital',
                                    'Lainnya' => 'Lainnya / General Digital Creator',
                                ];
                                $selectedType = old('creator_type', $profile->creator_type ?? '');
                            @endphp
                            @foreach($creatorTypes as $key => $label)
                                <option value="{{ $key }}" {{ $selectedType === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <span class="form-hint">Pilih peran utama yang paling merepresentasikan karya dan produk digital Anda.</span>
                        @error('creator_type')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Deskripsi Singkat Toko <span style="color:#ef4444;">*</span></label>
                        <textarea name="store_description" class="form-input" rows="3"
                            placeholder="Ceritakan tentang keahlian Anda, spesialisasi produk digital, dll." maxlength="60"
                            required>{{ old('store_description', $profile->store_description) }}</textarea>
                        <span class="form-hint">Maksimal 60 karakter.</span>
                        @error('store_description')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Sosial Media Toko --}}
            <div class="form-section-title">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                </svg>
                Sosial Media & Saluran Promosi (Wajib Min. 2: Instagram & TikTok)
            </div>
            <div class="form-body">
                <div class="form-grid">
                    @php
                        $socials = old('social_links', $profile->social_links ?? []);
                    @endphp

                    {{-- Instagram (Wajib) --}}
                    <div class="form-group">
                        <label class="form-label" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#E1306C" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                            Instagram <span style="color:#ef4444;">*</span>
                        </label>
                        <div style="display:flex;align-items:center;border:1.5px solid #e7f0e7;border-radius:10px;background:#f9fefb;overflow:hidden;">
                            <span style="padding:0 0.75rem;font-size:0.8rem;color:#94A3B8;background:#f1f5f9;border-right:1px solid #e7f0e7;height:44px;display:flex;align-items:center;">instagram.com/</span>
                            <input type="text" name="social_links[instagram]" value="{{ $socials['instagram'] ?? '' }}" class="form-input" style="border:none;background:transparent;height:100%;padding:0 0.75rem;flex:1;" placeholder="username_anda" required>
                        </div>
                        @error('social_links.instagram')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    {{-- TikTok (Wajib) --}}
                    <div class="form-group">
                        <label class="form-label" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.86 4.43 6.2 6.2 0 0 0 1.91-4.42V8.92a8.28 8.28 0 0 0 4.82 1.55v-3.47a4.91 4.91 0 0 1-1-.31z"/></svg>
                            TikTok <span style="color:#ef4444;">*</span>
                        </label>
                        <div style="display:flex;align-items:center;border:1.5px solid #e7f0e7;border-radius:10px;background:#f9fefb;overflow:hidden;">
                            <span style="padding:0 0.75rem;font-size:0.8rem;color:#94A3B8;background:#f1f5f9;border-right:1px solid #e7f0e7;height:44px;display:flex;align-items:center;">tiktok.com/@</span>
                            <input type="text" name="social_links[tiktok]" value="{{ $socials['tiktok'] ?? '' }}" class="form-input" style="border:none;background:transparent;height:100%;padding:0 0.75rem;flex:1;" placeholder="username_anda" required>
                        </div>
                        @error('social_links.tiktok')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    {{-- YouTube (Opsional) --}}
                    <div class="form-group">
                        <label class="form-label" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FF0000" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
                            YouTube <span style="font-size:0.7rem;color:#94A3B8;font-weight:400;">(Opsional)</span>
                        </label>
                        <input type="text" name="social_links[youtube]" value="{{ $socials['youtube'] ?? '' }}" class="form-input" placeholder="https://youtube.com/@channel">
                    </div>

                    {{-- X / Twitter (Opsional) --}}
                    <div class="form-group">
                        <label class="form-label" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            X / Twitter <span style="font-size:0.7rem;color:#94A3B8;font-weight:400;">(Opsional)</span>
                        </label>
                        <input type="text" name="social_links[x]" value="{{ $socials['x'] ?? '' }}" class="form-input" placeholder="https://x.com/username">
                    </div>

                    {{-- LinkedIn (Opsional) --}}
                    <div class="form-group">
                        <label class="form-label" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0A66C2" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                            LinkedIn <span style="font-size:0.7rem;color:#94A3B8;font-weight:400;">(Opsional)</span>
                        </label>
                        <input type="text" name="social_links[linkedin]" value="{{ $socials['linkedin'] ?? '' }}" class="form-input" placeholder="https://linkedin.com/in/username">
                    </div>

                    {{-- Facebook (Opsional) --}}
                    <div class="form-group">
                        <label class="form-label" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1877F2" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            Facebook <span style="font-size:0.7rem;color:#94A3B8;font-weight:400;">(Opsional)</span>
                        </label>
                        <input type="text" name="social_links[facebook]" value="{{ $socials['facebook'] ?? '' }}" class="form-input" placeholder="https://facebook.com/username">
                    </div>

                    {{-- Website / Portfolio (Opsional) --}}
                    <div class="form-group full">
                        <label class="form-label" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1eb349" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            Website / Portofolio / Linktree <span style="font-size:0.7rem;color:#94A3B8;font-weight:400;">(Opsional)</span>
                        </label>
                        <input type="url" name="social_links[website]" value="{{ $socials['website'] ?? '' }}" class="form-input" placeholder="https://portofolio-anda.com">
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