{{-- ═══════════════════════════════════
FOOTER COMPONENT — buyle.id (Wave Green Gradient Style)
PT. Hiranatha Makmur Sukses
www.buyle.id
══════════════════════════════════ --}}
@php
    $s = \App\Models\Setting::getAllAsArray();
    $wa = \App\Models\WaSetting::where('is_active', true)->first();
    $footerCategories = \App\Models\ProductCategory::active()->ordered()->take(6)->get();
@endphp

<style>
    /* ═════════════════════════════════════════
       FOOTER — Modern Wave Green Gradient Theme
    ═════════════════════════════════════════ */
    .cv-footer-v2 {
        position: relative;
        font-family: inherit;
        background: #ffffff;
        color: #ffffff;
        overflow: hidden;
    }

    /* Wave Top Separator */
    .cv-footer-wave-wrap {
        position: relative;
        width: 100%;
        line-height: 0;
        background: transparent;
        overflow: hidden;
        margin-bottom: -1px;
    }

    .cv-footer-wave-svg {
        position: relative;
        display: block;
        width: 100%;
        height: clamp(45px, 7vw, 95px);
    }

    /* Main Green Gradient Section */
    .cv-footer-body {
        background: linear-gradient(135deg, #1eb349 0%, #7db928 50%, #a5cf37 100%);
        position: relative;
        padding-top: 0.5rem;
        padding-bottom: 4rem;
        box-shadow: inset 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    /* Radial glow overlays for premium depth */
    .cv-footer-body::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(circle at 15% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 55%),
                    radial-gradient(circle at 85% 75%, rgba(0, 0, 0, 0.08) 0%, transparent 50%);
        pointer-events: none;
    }

    .cv-footer-v2-main {
        position: relative;
        z-index: 2;
        max-width: 1240px;
        margin: 0 auto;
        padding: 1.5rem clamp(1.25rem, 5vw, 2.5rem) 0;
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr 1fr;
        gap: 3rem;
    }

    /* Brand Logo Pill */
    .cv-footer-logo-wrap {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border-radius: 99px;
        padding: 0.5rem 1.25rem;
        text-decoration: none;
        margin-bottom: 1.25rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .cv-footer-logo-wrap:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .cv-footer-logo-icon {
        height: 36px;
        width: auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cv-footer-logo-icon img {
        width: auto;
        height: 100%;
        object-fit: contain;
    }

    .cv-footer-tagline {
        font-size: 0.92rem;
        font-weight: 400;
        color: rgba(255, 255, 255, 0.92);
        line-height: 1.75;
        margin-bottom: 1.75rem;
        max-width: 320px;
    }

    /* Social Buttons */
    .cv-footer-socials {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
    }

    .cv-footer-social-btn {
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .cv-footer-social-btn:hover {
        background: #ffffff;
        color: #1eb349;
        border-color: #ffffff;
        transform: translateY(-3px) scale(1.08);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    /* Column Titles */
    .cv-footer-col-title {
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        color: #ffffff;
        margin-bottom: 1.5rem;
        position: relative;
        display: inline-block;
    }

    .cv-footer-col-title::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 26px;
        height: 3px;
        background: #ffffff;
        border-radius: 2px;
        opacity: 0.9;
    }

    /* Links */
    .cv-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .cv-footer-links li {
        margin-bottom: 0.85rem;
    }

    .cv-footer-links a {
        font-size: 0.9rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.88);
        text-decoration: none;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .cv-footer-links a:hover {
        color: #ffffff;
        transform: translateX(4px);
        font-weight: 600;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    /* Bottom Bar */
    .cv-footer-bottom-wrap {
        background: #ffffff;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        position: relative;
        z-index: 2;
    }

    .cv-footer-bottom {
        max-width: 1240px;
        margin: 0 auto;
        padding: 1.25rem clamp(1.25rem, 5vw, 2.5rem);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .cv-footer-copy {
        font-size: 0.85rem;
        color: #64748B;
        font-weight: 500;
    }

    .cv-footer-copy strong {
        color: #0F172A;
        font-weight: 700;
    }

    .cv-footer-dev {
        font-size: 0.83rem;
        color: #64748B;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .cv-footer-dev a {
        color: #1eb349;
        text-decoration: none;
        font-weight: 700;
        transition: color 0.2s;
    }

    .cv-footer-dev a:hover {
        color: #a5cf37;
        text-decoration: underline;
    }

    .cv-footer-bottom-socials {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .cv-footer-bottom-socials a {
        color: #64748B;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cv-footer-bottom-socials a:hover {
        color: #1eb349;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .cv-footer-v2-main {
            grid-template-columns: 1fr 1fr;
            gap: 2.5rem;
        }
    }

    @media (max-width: 640px) {
        .cv-footer-v2-main {
            grid-template-columns: 1fr;
            gap: 2rem;
            padding-top: 0.5rem;
            padding-bottom: 1rem;
        }

        .cv-footer-tagline {
            max-width: 100%;
        }

        .cv-footer-bottom {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.85rem;
            padding-bottom: 90px !important; /* Space for mobile sticky bottom navbar */
        }
    }
</style>

<footer class="cv-footer-v2" role="contentinfo">

    {{-- Top Wave Shape Divider --}}
    <div class="cv-footer-wave-wrap">
        <svg class="cv-footer-wave-svg" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <defs>
                <linearGradient id="footerBgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#1eb349" />
                    <stop offset="50%" stop-color="#7db928" />
                    <stop offset="100%" stop-color="#a5cf37" />
                </linearGradient>
            </defs>
            <!-- Lighter Back Wave Layer for extra depth -->
            <path d="M0,60 C320,110 420,10 720,55 C1020,100 1140,20 1440,65 L1440,120 L0,120 Z" fill="#ffffff" opacity="0.25"/>
            <!-- Main Front Wave Layer -->
            <path d="M0,45 C280,105 440,15 720,60 C1000,105 1160,25 1440,55 L1440,125 L0,125 Z" fill="url(#footerBgGrad)"/>
        </svg>
    </div>

    {{-- Main Green Gradient Body --}}
    <div class="cv-footer-body">
        <div class="cv-footer-v2-main">

            {{-- Brand Column --}}
            <div>
                <a href="{{ route_locale('home') }}" class="cv-footer-logo-wrap">
                    <div class="cv-footer-logo-icon">
                        @php $logo = \App\Models\Setting::get('logo'); @endphp
                        @if($logo)
                            <img src="{{ asset('storage/' . $logo) }}" alt="buyle.id">
                        @else
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="32" height="32" rx="8" fill="#1eb349"/>
                                <text x="16" y="22" text-anchor="middle" font-family="inherit" font-size="18" font-weight="900" fill="white">B</text>
                            </svg>
                        @endif
                    </div>
                </a>

                <p class="cv-footer-tagline">
                    {{ $s['footer_desc'] ?? 'Platform e-commerce & layanan terpercaya untuk kebutuhan rumah tangga, elektronik, furnitur, hingga jasa profesional.' }}
                </p>

                <div class="cv-footer-socials">
                    @if($wa)
                        <a href="javascript:void(0)" onclick="openOrderModal('Footer WA Icon')" class="cv-footer-social-btn" title="WhatsApp" data-track="Footer WA Icon">
                            <svg width="17" height="17" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                        </a>
                    @endif
                    <a href="mailto:{{ $s['email'] ?? 'info@buyle.id' }}" class="cv-footer-social-btn" title="Email">
                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,12 2,6" />
                        </svg>
                    </a>
                    @php
                        $footerPhone = $s['phone'] ?? '';
                        $footerPhoneClean = preg_replace('/[^0-9+]/', '', $footerPhone);
                        $footerPhoneDisplay = $footerPhone ?: ($wa ? $wa->nomor_wa : '0812-9656-5757');
                        $footerPhoneClean = $footerPhoneClean ?: preg_replace('/[^0-9+]/', '', $footerPhoneDisplay);
                    @endphp
                    <a href="tel:{{ $footerPhoneClean }}" class="cv-footer-social-btn" title="Telepon">
                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.5 12.05a19.79 19.79 0 01-3.07-8.67A2 2 0 012.41 1.5h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 9.4a16 16 0 006.69 6.69l1.27-.76a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Column 1: Kategori (Replaces Produk) --}}
            <div>
                <div class="cv-footer-col-title">Kategori</div>
                <ul class="cv-footer-links">
                    @if(isset($footerCategories) && $footerCategories->count())
                        @foreach($footerCategories as $cat)
                            <li><a href="{{ route_locale('category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                        @endforeach
                    @else
                        <li><a href="{{ route_locale('products') }}?category=elektronik">Elektronik & Smart Home</a></li>
                        <li><a href="{{ route_locale('products') }}?category=pendingin">AC & Pendingin</a></li>
                        <li><a href="{{ route_locale('products') }}?category=furnitur">Furnitur & Interior</a></li>
                        <li><a href="{{ route_locale('products') }}?category=dapur">Peralatan Dapur</a></li>
                        <li><a href="{{ route_locale('products') }}?category=jasa">Jasa Service & Instalasi</a></li>
                    @endif
                </ul>
            </div>

            {{-- Column 2: Informasi & Layanan (Replaces Navigasi) --}}
            <div>
                <div class="cv-footer-col-title">Informasi & Layanan</div>
                <ul class="cv-footer-links">
                    <li><a href="{{ route_locale('about') }}">Tentang Kami</a></li>
                    <li><a href="{{ route_locale('products') }}">Semua Produk & Jasa</a></li>
                    <li><a href="{{ route_locale('gallery') }}">Galeri Instalasi</a></li>
                    <li><a href="{{ route_locale('articles') }}">Artikel & Tips</a></li>
                    <li><a href="{{ route_locale('contact') }}">Lokasi & Alamat</a></li>
                </ul>
            </div>

            {{-- Column 3: Bantuan & Legal --}}
            <div>
                <div class="cv-footer-col-title">Bantuan & Support</div>
                <ul class="cv-footer-links">
                    <li><a href="{{ route_locale('contact') }}">Hubungi Kami</a></li>
                    <li><a href="{{ route_locale('faqs') }}">Pusat Bantuan & FAQ</a></li>
                    <li><a href="{{ route_locale('contact') }}#syarat">Syarat & Ketentuan</a></li>
                    <li><a href="{{ route_locale('contact') }}#privasi">Kebijakan Privasi</a></li>
                    <li><a href="javascript:void(0)" onclick="openOrderModal('Footer Order Check')">Status Pemesanan</a></li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="cv-footer-bottom-wrap">
        <div class="cv-footer-bottom">
            <div class="cv-footer-copy">
                <strong>{{ $s['copyright'] ?? '© ' . date('Y') . ' buyle.id' }}</strong>. PT Hiranatha Makmur Sukses. All Rights Reserved.
            </div>

            <div class="cv-footer-dev">
                <span>Built by <a href="https://hvmdigital.id/" target="_blank" rel="noopener">HVM Digital</a></span>
                <div class="cv-footer-bottom-socials">
                    <a href="https://facebook.com" target="_blank" rel="noopener" title="Facebook">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener" title="Twitter / X">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener" title="Instagram">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

</footer>