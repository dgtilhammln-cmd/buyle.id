{{-- ═══════════════════════════════════
FOOTER COMPONENT — buyle.id
Modern Clean Style (Inspired by Celestix Style with Buyle.id Green Theme)
══════════════════════════════════ --}}
@php
    $s = \App\Models\Setting::getAllAsArray();
    $wa = \App\Models\WaSetting::where('is_active', true)->first();
    $logo = \App\Models\Setting::get('logo');
    
    // File versioning for aggressive browser caching
    $greenWebpPath = public_path('images/logobuylefooter-green.webp');
    $greenPngPath  = public_path('images/logobuylefooter-green.png');
    $whitePngPath  = public_path('images/logobuylefooter.png');
    
    $vGreenWebp = file_exists($greenWebpPath) ? filemtime($greenWebpPath) : '1.0';
    $vGreenPng  = file_exists($greenPngPath) ? filemtime($greenPngPath) : '1.0';
    $vWhitePng  = file_exists($whitePngPath) ? filemtime($whitePngPath) : '1.0';
@endphp

<style>
    /* ═════════════════════════════════════════
       FOOTER — Clean Modern White & Green Theme
    ═════════════════════════════════════════ */
    .cv-footer-v2 {
        position: relative;
        font-family: inherit;
        background: #ffffff;
        color: #0f172a;
        border-top: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .cv-footer-body {
        position: relative;
        max-width: 1240px;
        margin: 0 auto;
        padding: 4rem clamp(1.25rem, 5vw, 3rem) 2.5rem;
    }

    /* Top Grid: Brand & Links */
    .cv-footer-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 3.5rem;
    }

    /* Brand Left Column */
    .cv-footer-brand {
        flex: 0 0 320px;
        max-width: 340px;
    }

    .cv-footer-logo-link {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        margin-bottom: 1.25rem;
    }

    .cv-footer-logo-link img {
        height: 38px;
        width: auto;
        object-fit: contain;
    }

    .cv-footer-logo-fallback {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .cv-footer-logo-fallback span {
        color: #1eb349;
    }

    /* Social Icons under Logo */
    .cv-footer-socials {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }

    .cv-footer-social-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .cv-footer-social-btn:hover {
        background: #1eb349;
        color: #ffffff;
        border-color: #1eb349;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.22);
    }

    /* Right Navigation Columns */
    .cv-footer-nav-cols {
        display: flex;
        gap: clamp(2.5rem, 5vw, 4.5rem);
        flex-wrap: wrap;
    }

    .cv-footer-col {
        min-width: 140px;
    }

    .cv-footer-col-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1.15rem;
        letter-spacing: -0.01em;
    }

    .cv-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .cv-footer-links a {
        font-size: 0.88rem;
        font-weight: 500;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .cv-footer-links a:hover {
        color: #1eb349;
        transform: translateX(3px);
    }

    /* ── Middle Wireframe Wordmark Banner (No Overlap, Responsive) ── */
    .cv-footer-wireframe-wrap {
        margin: 3.75rem auto 1.5rem;
        padding: 0 1rem;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        max-width: 1100px;
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    .cv-footer-wireframe-img {
        width: 100%;
        max-width: 960px;
        height: auto;
        display: block;
        object-fit: contain;
        user-select: none;
        -webkit-user-drag: none;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    /* Bottom Copyright Row */
    .cv-footer-bottom {
        text-align: center;
        padding-top: 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #94a3b8;
    }

    .cv-footer-bottom a {
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .cv-footer-bottom a:hover {
        color: #1eb349;
    }

    /* ── Responsive Styling ── */
    @media (max-width: 960px) {
        .cv-footer-top {
            flex-direction: column;
            gap: 2.5rem;
        }

        .cv-footer-brand {
            flex: none;
            max-width: 100%;
        }

        .cv-footer-nav-cols {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .cv-footer-wireframe-wrap {
            margin: 2.75rem auto 1.25rem;
        }
    }

    @media (max-width: 640px) {
        .cv-footer-body {
            padding-top: 2.75rem;
            padding-bottom: 95px !important; /* Space for mobile sticky bottom navbar */
        }

        .cv-footer-nav-cols {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.75rem;
        }

        .cv-footer-wireframe-wrap {
            margin: 2rem auto 1rem;
            padding: 0;
        }

        .cv-footer-wireframe-img {
            max-width: 100%;
        }

        .cv-footer-bottom {
            font-size: 0.8rem;
        }
    }
</style>

<footer class="cv-footer-v2" role="contentinfo">
    <div class="cv-footer-body">

        {{-- Top Row: Brand & Nav Links --}}
        <div class="cv-footer-top">

            {{-- Brand Column (Logo + Socials) --}}
            <div class="cv-footer-brand">
                <a href="{{ route_locale('home') }}" class="cv-footer-logo-link" title="Buyle.id">
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="buyle.id" loading="lazy" decoding="async">
                    @elseif(file_exists(public_path('images/buyle-logo.png')))
                        <img src="{{ asset('images/buyle-logo.png') }}" alt="buyle.id" loading="lazy" decoding="async">
                    @else
                        <div class="cv-footer-logo-fallback">
                            buyle<span>.id</span>
                        </div>
                    @endif
                </a>

                {{-- Social Media Icons --}}
                <div class="cv-footer-socials">
                    {{-- WhatsApp --}}
                    @if($wa)
                        <a href="javascript:void(0)" onclick="openOrderModal('Footer WA Icon')" class="cv-footer-social-btn"
                            title="WhatsApp" data-track="Footer WA Icon">
                            <svg width="17" height="17" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                        </a>
                    @else
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s['whatsapp'] ?? '6281234567890') }}" target="_blank" rel="noopener" class="cv-footer-social-btn" title="WhatsApp">
                            <svg width="17" height="17" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                        </a>
                    @endif

                    {{-- Instagram --}}
                    <a href="{{ $s['instagram'] ?? 'https://instagram.com/buyle.id' }}" target="_blank" rel="noopener"
                        class="cv-footer-social-btn" title="Instagram">
                        <svg width="17" height="17" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>

                    {{-- TikTok --}}
                    <a href="{{ $s['tiktok'] ?? 'https://tiktok.com/@buyle.id' }}" target="_blank" rel="noopener"
                        class="cv-footer-social-btn" title="TikTok">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.589 6.686a4.793 4.793 0 0 1-3.77-4.245V2h-3.445v13.672a2.896 2.896 0 0 1-2.9 2.802 2.895 2.895 0 0 1-2.893-2.89 2.894 2.894 0 0 1 2.893-2.891c.328 0 .641.055.932.155V9.408a6.3 6.3 0 0 0-.932-.07A6.333 6.333 0 0 0 3.14 15.67a6.33 6.33 0 0 0 6.334 6.33 6.333 6.333 0 0 0 6.333-6.33V9.088a8.216 8.216 0 0 0 5.234 1.86v-3.48a4.8 4.8 0 0 1-1.452-.782z" />
                        </svg>
                    </a>

                    {{-- YouTube --}}
                    <a href="{{ $s['youtube'] ?? 'https://youtube.com' }}" target="_blank" rel="noopener"
                        class="cv-footer-social-btn" title="YouTube">
                        <svg width="17" height="17" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Right Navigation Columns (Like Celestix in Image 1) --}}
            <div class="cv-footer-nav-cols">

                {{-- Column 1: Tentang Buyle.id --}}
                <div class="cv-footer-col">
                    <div class="cv-footer-col-title">Tentang Buyle.id</div>
                    <ul class="cv-footer-links">
                        <li><a href="{{ route_locale('about') }}">Tentang Kami</a></li>
                        <li><a href="{{ route_locale('products') }}">Event & Creator</a></li>
                        <li><a href="{{ route_locale('products') }}">Semua Produk</a></li>
                        <li><a href="{{ route_locale('articles') }}">Artikel & Tips</a></li>
                    </ul>
                </div>

                {{-- Column 2: Tautan --}}
                <div class="cv-footer-col">
                    <div class="cv-footer-col-title">Tautan</div>
                    <ul class="cv-footer-links">
                        <li><a href="{{ route_locale('faqs') }}">FAQ & Bantuan</a></li>
                        <li><a href="{{ route_locale('faqs.show', 'syarat-ketentuan') }}">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route_locale('faqs.show', 'kebijakan-privasi') }}">Kebijakan Privasi</a></li>
                        <li><a href="{{ route_locale('products') }}?category=digital">Produk Digital</a></li>
                    </ul>
                </div>

                {{-- Column 3: Dukungan --}}
                <div class="cv-footer-col">
                    <div class="cv-footer-col-title">Dukungan</div>
                    <ul class="cv-footer-links">
                        <li><a href="{{ route_locale('contact') }}">Hubungi Kami</a></li>
                        <li><a href="{{ route_locale('contact') }}">Kerja Sama</a></li>
                        <li>
                            @if($wa)
                                <a href="javascript:void(0)" onclick="openOrderModal('Footer Help')">Customer Service</a>
                            @else
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s['whatsapp'] ?? '6281234567890') }}" target="_blank" rel="noopener">Customer Service</a>
                            @endif
                        </li>
                        <li><a href="{{ route_locale('contact') }}">Kemitraan</a></li>
                    </ul>
                </div>

            </div>

        </div>

        {{-- ── Middle Artistic Wireframe Wordmark (BUYLE.ID) — Lazy Loaded & Aggressively Cached ── --}}
        <div class="cv-footer-wireframe-wrap">
            <picture>
                <source srcset="{{ asset('images/logobuylefooter-green.webp') }}?v={{ $vGreenWebp }}" type="image/webp">
                <img src="{{ asset('images/logobuylefooter-green.png') }}?v={{ $vGreenPng }}"
                     alt="BUYLE.ID"
                     loading="lazy"
                     decoding="async"
                     width="2170"
                     height="725"
                     class="cv-footer-wireframe-img">
            </picture>
        </div>

        {{-- Bottom Copyright Notice (Centered, like Image 1) --}}
        <div class="cv-footer-bottom">
            <div>
                &copy; {{ date('Y') }} Buyle.id. All rights reserved.
            </div>
        </div>

    </div>
</footer>