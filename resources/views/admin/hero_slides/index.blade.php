@extends('layouts.admin')
@section('title', 'Manajemen Banner')
@section('page-title', 'Manajemen Banner & Slider')
@section('content')

    <style>
        .banner-admin-container {
            font-family: 'Montserrat', sans-serif;
            color: #1e293b;
        }

        .banner-admin-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.25rem;
            box-sizing: border-box;
        }

        .banner-card-head-title {
            font-size: 0.78rem;
            font-weight: 800;
            color: #16a34a;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .banner-card-desc {
            font-size: 0.78rem;
            color: #64748b;
            line-height: 1.5;
            margin: 0 0 1rem 0;
        }

        .banner-filter-tabs {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }

        .banner-tab-btn {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #475569;
            padding: 0.45rem 1rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.15s ease;
        }

        .banner-tab-btn:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
        }

        .banner-tab-btn.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.28);
        }

        .banner-item-row {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            margin-bottom: 0.85rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        }

        .banner-item-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        @media (max-width: 860px) {
            .banner-item-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .banner-item-actions {
                width: 100%;
                justify-content: flex-end;
                border-top: 1px dashed #f1f5f9;
                padding-top: 0.75rem;
            }
        }

        .banner-thumb-wrap {
            width: 120px;
            height: 65px;
            border-radius: 10px;
            overflow: hidden;
            background: #0f172a;
            flex-shrink: 0;
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .banner-thumb-wrap.tix-thumb {
            width: 150px;
            height: 50px; /* 1920 x 600 aspect ratio ~3:1 */
        }

        .banner-thumb-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .banner-thumb-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            color: #94a3b8;
            background: #f1f5f9;
        }

        .banner-item-info {
            flex: 1;
            min-width: 0;
        }

        .banner-item-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.25rem 0;
            line-height: 1.3;
        }

        .banner-item-desc {
            font-size: 0.76rem;
            color: #64748b;
            line-height: 1.45;
            margin: 0 0 0.45rem 0;
        }

        .banner-item-tags {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .pos-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .pos-badge.hero {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .pos-badge.utama {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .pos-badge.samping {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .pos-badge.tix {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            color: #047857;
            border: 1.5px solid #a7f3d0;
            font-weight: 800;
        }

        .btn-action-edit {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            color: #334155;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            font-size: 0.76rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.15s ease;
        }

        .btn-action-edit:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .btn-action-delete {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            color: #dc2626;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            font-size: 0.76rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.15s ease;
        }

        .btn-action-delete:hover {
            background: #fee2e2;
            border-color: #fca5a5;
        }

        .btn-add-banner {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 800;
            padding: 0.65rem 1.45rem;
            border-radius: 999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-add-banner:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(30, 179, 73, 0.45);
            color: #ffffff;
        }
    </style>

    <div class="banner-admin-container">

        {{-- Session Success & Error Banners --}}
        @if(session('success'))
            <div style="background:#f0fdf4; border:1.5px solid #bbf7d0; color:#166534; padding:0.9rem 1.25rem; border-radius:12px; margin-bottom:1.25rem; font-size:0.85rem; font-weight:700; display:flex; align-items:center; gap:0.5rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div style="background:#fef2f2; border:1.5px solid #fca5a5; color:#991b1b; padding:0.9rem 1.25rem; border-radius:12px; margin-bottom:1.25rem; font-size:0.85rem; font-weight:700;">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- 1. WARNA BACKGROUND OVAL (HERO SLIDER) --}}
        <div class="banner-admin-card">
            <h3 class="banner-card-head-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4l3 3"/>
                </svg>
                Warna Background Oval (Hero Slider Home)
            </h3>
            <p class="banner-card-desc">
                Warna ini digunakan sebagai latar belakang oval di belakang hero slider halaman depan. Anda bisa memilih warna gradasi solid atau gradient CSS.
            </p>
            <form action="{{ route('admin.settings.update') }}" method="POST" style="display:flex; gap:0.85rem; align-items:flex-end; flex-wrap:wrap;">
                @csrf
                @php
                    $currentGradient = \App\Models\Setting::get('hero_oval_gradient', 'linear-gradient(180deg, #1db349 10%, #a4ff01 100%)');
                @endphp
                <div style="flex:1; min-width:240px;">
                    <label style="font-size:0.75rem; font-weight:700; color:#334155; display:block; margin-bottom:0.4rem;">
                        Warna / Gradient CSS
                    </label>
                    <div style="display:flex; gap:0.6rem; align-items:center;">
                        <input type="color" id="colorPicker" value="#1eb349" style="width:42px; height:42px; border-radius:10px; border:1.5px solid #e2e8f0; cursor:pointer; padding:2px; background:#fff;" title="Pilih warna solid" onchange="document.getElementById('gradientInput').value = this.value; updateGradientPreview(this.value);">
                        <input type="text" name="hero_oval_gradient" id="gradientInput" class="form-input" value="{{ $currentGradient }}" placeholder="linear-gradient(180deg, #1db349 10%, #a4ff01 100%)" style="flex:1; height:42px; padding:0 0.85rem; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.82rem; font-family:monospace; color:#0f172a; outline:none;" oninput="updateGradientPreview(this.value)">
                        <div id="gradientPreviewBadge" style="width:42px; height:42px; border-radius:10px; border:1.5px solid #e2e8f0; background: {{ $currentGradient }}; flex-shrink:0;" title="Preview Latar"></div>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="height:42px; padding:0 1.35rem; border-radius:10px; font-weight:800; font-size:0.82rem; background:linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color:#ffffff; border:none; cursor:pointer; display:flex; align-items:center; gap:0.4rem; box-shadow:0 3px 10px rgba(30,179,73,0.3);">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Simpan Warna</span>
                </button>
            </form>
        </div>

        {{-- 1.5 KELOLA MEDIA PARTNER BUYLE TIX (DINAMIS - UNLIMITED) --}}
        <div class="banner-admin-card" id="mediaPartnerCard">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem; margin-bottom:0.75rem;">
                <h3 class="banner-card-head-title" style="margin:0;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Media Partner Buyle TIX
                    <span style="font-size:0.65rem; background:#dcfce7; color:#166534; padding:2px 8px; border-radius:99px; font-weight:700; margin-left:0.25rem;">
                        {{ $slides->where('position','partner')->count() }} Logo
                    </span>
                </h3>
                <a href="{{ route('admin.hero_slides.create') }}?position=partner" class="btn-add-banner" style="font-size:0.78rem; padding:0.5rem 1rem;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    <span>+ Tambah Logo Partner</span>
                </a>
            </div>
            <p class="banner-card-desc">
                Kelola logo media partner / sponsor resmi yang tampil di bawah hero banner Buyle TIX. Tambahkan sebanyak yang diperlukan. Format transparan SVG atau PNG direkomendasikan.
            </p>

            @php $partnerSlides = $slides->where('position', 'partner'); @endphp
            @if($partnerSlides->isEmpty())
                <div style="text-align:center; padding:1.5rem; background:#f8fafc; border-radius:12px; border:1.5px dashed #e2e8f0;">
                    <p style="margin:0; font-size:0.82rem; color:#94a3b8; font-weight:600;">Belum ada logo partner. Klik "+ Tambah Logo Partner" untuk menambahkan.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">
                    @foreach($partnerSlides->sortBy('order') as $partner)
                        <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 1rem; position:relative;">
                            {{-- Badge order & status --}}
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                                <span style="font-size:0.75rem; font-weight:800; color:#0f172a;">{{ $partner->title }}</span>
                                <div style="display:flex; gap:0.35rem; align-items:center;">
                                    <span style="font-size:0.62rem; background:#e2e8f0; color:#475569; padding:2px 6px; border-radius:99px;">#{{ $partner->order }}</span>
                                    @if($partner->is_active)
                                        <span style="font-size:0.62rem; background:#dcfce7; color:#166534; padding:2px 6px; border-radius:99px; font-weight:700;">Aktif</span>
                                    @else
                                        <span style="font-size:0.62rem; background:#f1f5f9; color:#94a3b8; padding:2px 6px; border-radius:99px;">Nonaktif</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Logo Preview --}}
                            @if($partner->image)
                                <div style="background:#0f172a; border-radius:8px; padding:0.5rem 0.75rem; display:flex; align-items:center; justify-content:center; min-height:44px; margin-bottom:0.6rem;">
                                    <img src="{{ asset('storage/'.$partner->image) }}" alt="{{ $partner->title }}" style="max-height:30px; max-width:140px; object-fit:contain; filter:brightness(0) invert(1);">
                                </div>
                            @else
                                <div style="background:#f1f5f9; border-radius:8px; padding:0.5rem; display:flex; align-items:center; justify-content:center; min-height:44px; margin-bottom:0.6rem;">
                                    <span style="font-size:0.72rem; color:#94a3b8; font-weight:700;">Belum Ada Logo</span>
                                </div>
                            @endif

                            {{-- Link --}}
                            @if($partner->button_url)
                                <p style="font-size:0.65rem; color:#64748b; margin:0 0 0.6rem 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    🔗 {{ $partner->button_url }}
                                </p>
                            @endif

                            {{-- Action Buttons --}}
                            <div style="display:flex; gap:0.5rem;">
                                <a href="{{ route('admin.hero_slides.edit', $partner) }}" class="btn-action-edit" style="flex:1; justify-content:center; font-size:0.72rem; padding:0.35rem 0.5rem;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.hero_slides.destroy', $partner) }}" method="POST" onsubmit="return confirm('Hapus logo partner ini?');" style="margin:0; flex:1;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete" style="width:100%; justify-content:center; font-size:0.72rem; padding:0.35rem 0.5rem;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 2. HEADER TOP ROW: TOTAL & TAMBAH BANNER --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:0.75rem;">
            <div>
                <h3 style="font-size:1.1rem; font-weight:800; color:#0f172a; margin:0 0 2px 0;">Daftar Banner Aktif</h3>
                <p style="font-size:0.78rem; color:#64748b; margin:0;">Total Banner Terdaftar: <strong>{{ $slides->count() }}</strong></p>
            </div>
            <a href="{{ route('admin.hero_slides.create') }}" class="btn-add-banner">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                <span>+ Tambah Banner</span>
            </a>
        </div>

        {{-- 3. FILTER TABS (Semua, Hero Home, Kanan, Buyle TIX, Partner) --}}
        @php
            $countTix = $slides->where('position', 'tix')->count();
            $countHero = $slides->where('position', 'hero')->count();
            $countKanan = $slides->whereIn('position', ['utama', 'samping'])->count();
            $countPartner = $slides->where('position', 'partner')->count();
        @endphp
        <div class="banner-filter-tabs">
            <button type="button" class="banner-tab-btn active" onclick="filterBanners('all', this)">
                <span>Semua Banner</span>
                <span style="background:rgba(0,0,0,0.08); padding:1px 6px; border-radius:999px; font-size:0.7rem;">{{ $slides->count() }}</span>
            </button>
            <button type="button" class="banner-tab-btn" onclick="filterBanners('tix', this)">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/></svg>
                <span>Buyle TIX (1920x600)</span>
                <span style="background:rgba(0,0,0,0.08); padding:1px 6px; border-radius:999px; font-size:0.7rem;">{{ $countTix }}</span>
            </button>
            <button type="button" class="banner-tab-btn" onclick="filterBanners('hero', this)">
                <span>Hero Slider (Home)</span>
                <span style="background:rgba(0,0,0,0.08); padding:1px 6px; border-radius:999px; font-size:0.7rem;">{{ $countHero }}</span>
            </button>
            <button type="button" class="banner-tab-btn" onclick="filterBanners('kanan', this)">
                <span>Banner Kanan (Atas & Bawah)</span>
                <span style="background:rgba(0,0,0,0.08); padding:1px 6px; border-radius:999px; font-size:0.7rem;">{{ $countKanan }}</span>
            </button>
            <button type="button" class="banner-tab-btn" onclick="filterBanners('partner', this)">
                <span>🤝 Media Partner TIX</span>
                <span style="background:rgba(0,0,0,0.08); padding:1px 6px; border-radius:999px; font-size:0.7rem;">{{ $countPartner }}</span>
            </button>
        </div>

        {{-- 4. BANNER LIST --}}
        @if($slides->isEmpty())
            <div class="banner-admin-card" style="text-align:center; padding:3rem 1.5rem;">
                <div style="width:52px; height:52px; border-radius:50%; background:#f1f5f9; display:inline-flex; align-items:center; justify-content:center; color:#94a3b8; margin-bottom:0.75rem;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <h4 style="margin:0 0 0.25rem 0; font-size:0.95rem; font-weight:700; color:#0f172a;">Belum Ada Banner</h4>
                <p style="margin:0; font-size:0.78rem; color:#64748b;">Klik tombol "+ Tambah Banner" di atas untuk menambahkan slide hero atau banner tiket baru.</p>
            </div>
        @else
            <div id="bannerListContainer">
                @foreach($slides as $slide)
                    @php
                        $pos = $slide->position ?? 'hero';
                        $isTix = ($pos === 'tix');
                        $isPartner = ($pos === 'partner');
                        $posGroup = $isTix ? 'tix' : ($pos === 'hero' ? 'hero' : ($isPartner ? 'partner' : 'kanan'));
                        $badgeClass = match($pos) {
                            'tix'     => 'tix',
                            'hero'    => 'hero',
                            'utama'   => 'utama',
                            'samping' => 'samping',
                            'partner' => 'tix',
                            default   => 'hero'
                        };
                        $posLabel = match($pos) {
                            'tix'     => '🎟️ Buyle TIX (1920 x 600)',
                            'hero'    => '🖼️ Hero Slider',
                            'utama'   => '↗️ Kanan Atas',
                            'samping' => '↘️ Kanan Bawah',
                            'partner' => '🤝 Media Partner TIX',
                            default   => ucfirst($pos)
                        };
                    @endphp
                    <div class="banner-item-row" data-pos-group="{{ $posGroup }}">
                        {{-- Left: Thumbnail --}}
                        <div class="banner-thumb-wrap {{ $isTix ? 'tix-thumb' : '' }}" {{ $isPartner ? 'style=background:#0f172a;' : '' }}>
                            @if($slide->image)
                                <img src="{{ asset('storage/'.$slide->image) }}" alt="{{ $slide->title }}" {{ $isPartner ? 'style=filter:brightness(0) invert(1);object-fit:contain;padding:4px;' : '' }}>
                            @else
                                <div class="banner-thumb-placeholder">NO IMAGE</div>
                            @endif
                        </div>

                        {{-- Center: Info & Tags --}}
                        <div class="banner-item-info">
                            <h4 class="banner-item-title">{{ $slide->title }}</h4>
                            @if($slide->description)
                                <p class="banner-item-desc">{{ Str::limit($slide->description, 95) }}</p>
                            @endif
                            <div class="banner-item-tags">
                                <span class="pos-badge {{ $badgeClass }}">
                                    {{ $posLabel }}
                                </span>
                                @if($slide->button_text)
                                    <span style="font-size:0.7rem; background:#f1f5f9; color:#475569; padding:2px 8px; border-radius:4px; font-weight:600;">
                                        Btn: {{ $slide->button_text }}
                                    </span>
                                @endif
                                @if($slide->button_url)
                                    <span style="font-size:0.7rem; color:#94a3b8; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        → {{ $slide->button_url }}
                                    </span>
                                @endif
                                <span style="font-size:0.7rem; background:#f8fafc; border:1px solid #e2e8f0; color:#64748b; padding:2px 8px; border-radius:4px; font-weight:700;">
                                    Urutan: #{{ $slide->order }}
                                </span>
                            </div>
                        </div>

                        {{-- Right: Status & Action Buttons --}}
                        <div class="banner-item-actions" style="display:flex; align-items:center; gap:0.6rem; flex-shrink:0;">
                            @if($slide->is_active)
                                <span style="font-size:0.72rem; font-weight:800; background:#dcfce7; color:#166534; padding:3px 10px; border-radius:999px;">
                                    Aktif
                                </span>
                            @else
                                <span style="font-size:0.72rem; font-weight:700; background:#f1f5f9; color:#94a3b8; padding:3px 10px; border-radius:999px;">
                                    Nonaktif
                                </span>
                            @endif

                            <a href="{{ route('admin.hero_slides.edit', $slide) }}" class="btn-action-edit" title="Edit Banner">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                <span>Edit</span>
                            </a>

                            <form action="{{ route('admin.hero_slides.destroy', $slide) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete" title="Hapus Banner">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <script>
        function updateGradientPreview(val) {
            const badge = document.getElementById('gradientPreviewBadge');
            if (badge) badge.style.background = val;
        }

        function filterBanners(group, btn) {
            document.querySelectorAll('.banner-tab-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');

            const rows = document.querySelectorAll('.banner-item-row');
            rows.forEach(row => {
                if (group === 'all' || row.dataset.posGroup === group) {
                    row.style.display = 'flex';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

@endsection
