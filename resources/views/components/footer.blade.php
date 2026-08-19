{{-- ═══════════════════════════════════
FOOTER COMPONENT — buyle.id (Jangkauan-Style)
PT. Hiranatha Makmur Sukses
www.buyle.id
══════════════════════════════════ --}}
@php
    $s = \App\Models\Setting::getAllAsArray();
    $svc = \App\Models\Product::where('is_active', true)->take(5)->get();
    $wa = \App\Models\WaSetting::where('is_active', true)->first();
@endphp

<style>
    /* ═══════════════════════════════════
   FOOTER — Jangkauan Section Style (#EAEBED)
═══════════════════════════════════ */
    .cv-footer-v2 {
        background: #F8FAFC;
        border-top: 1px solid #E2E8F0;
        color: #0F172A;
        font-family: 'Montserrat', sans-serif;
        position: relative;
    }

    /* ── MAIN GRID ─────────────────── */
    .cv-footer-v2-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 5rem clamp(1.25rem, 5vw, 2.5rem) 4rem;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.5fr;
        gap: 3.5rem;
    }

    /* ── BRAND COL ─────────────────── */
    .cv-footer-v2-logo-wrap {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border-radius: 999px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07), 0 1px 3px rgba(0, 0, 0, 0.04);
        padding: 0.25rem 0.5rem;
        text-decoration: none;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .cv-footer-v2-logo-icon {
        height: 38px;
        width: auto;
        border-radius: 999px;
        overflow: hidden;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .cv-footer-v2-logo-icon img {
        width: auto;
        height: 100%;
        object-fit: contain;
    }

    .cv-footer-v2-tagline {
        font-size: 0.9rem;
        font-weight: 400;
        color: #64748B;
        line-height: 1.75;
        margin-bottom: 2rem;
        max-width: 320px;
    }

    /* Badges — white cards like stat cards */
    .cv-footer-v2-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }

    .cv-footer-v2-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #ffffff;
        color: #1E293B;
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.4rem 0.875rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .cv-footer-v2-badge svg {
        color: #64748B;
        transition: color 0.3s;
    }

    .cv-footer-v2-badge:hover svg {
        color: #0EA5E9;
    }

    /* Social */
    .cv-footer-v2-socials {
        display: flex;
        gap: 0.6rem;
    }

    .cv-footer-v2-social-btn {
        width: 36px;
        height: 36px;
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748B;
        text-decoration: none;
        transition: all 0.25s;
    }

    .cv-footer-v2-social-btn:hover {
        background: #0EA5E9;
        border-color: #0EA5E9;
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.25);
    }

    /* ── COLUMN HEADINGS ───────────── */
    .cv-footer-v2-col-title {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #64748B;
        margin-bottom: 1.5rem;
    }

    /* ── LINKS ─────────────────────── */
    .cv-footer-v2-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .cv-footer-v2-links li {
        margin-bottom: 0.75rem;
    }

    .cv-footer-v2-links a {
        font-size: 0.9rem;
        font-weight: 400;
        color: #1E293B;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0;
        transition: all 0.25s;
    }

    .cv-footer-v2-links a:hover {
        color: #0F172A;
        font-weight: 600;
    }

    /* ── CONTACT ───────────────────── */
    .cv-footer-v2-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 0.875rem;
        margin-bottom: 1.25rem;
    }

    .cv-footer-v2-contact-icon {
        width: 36px;
        height: 36px;
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #64748B;
        transition: all 0.25s;
    }

    .cv-footer-v2-contact-item:hover .cv-footer-v2-contact-icon {
        background: #0EA5E9;
        border-color: #0EA5E9;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);
        transform: scale(1.05);
    }

    .cv-footer-v2-contact-label {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #64748B;
        display: block;
        margin-bottom: 3px;
    }

    .cv-footer-v2-contact-text {
        font-size: 0.8125rem;
        font-weight: 400;
        color: #1E293B;
        line-height: 1.6;
    }

    .cv-footer-v2-contact-text a {
        color: #0F172A;
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.2s;
    }

    .cv-footer-v2-contact-text a:hover {
        opacity: 0.7;
    }

    /* ── DIVIDER ───────────────────── */
    .cv-footer-v2-divider {
        border: none;
        border-top: 1px solid rgba(15, 23, 42, 0.08);
        margin: 0;
    }

    /* ── BOTTOM BAR ────────────────── */
    .cv-footer-v2-bottom-wrap {
        background: rgba(15, 23, 42, 0.04);
    }

    .cv-footer-v2-bottom {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem clamp(1.25rem, 5vw, 2.5rem);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .cv-footer-v2-copy {
        font-size: 0.8rem;
        color: #64748B;
        font-weight: 400;
    }

    .cv-footer-v2-copy strong {
        color: #0F172A;
        font-weight: 600;
    }

    .cv-footer-v2-bottom-links {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .cv-footer-v2-bottom-links a {
        font-size: 0.8rem;
        color: #64748B;
        text-decoration: none;
        font-weight: 400;
        transition: color 0.2s;
    }

    .cv-footer-v2-bottom-links a:hover {
        color: #0F172A;
    }

    /* ── PAYMENT & EXPEDITION ─────── */
    .cv-footer-v2-pay-wrap {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem clamp(1.25rem, 5vw, 2.5rem);
        max-width: 1200px;
        margin: 0 auto;
        border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        justify-content: space-between;
    }

    .cv-footer-v2-pay-section {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .cv-footer-v2-pay-title {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #64748B;
    }

    .cv-footer-v2-pay-logos {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }

    .cv-footer-v2-pay-logos img {
        height: 28px;
        width: auto;
        object-fit: contain;
        border-radius: 4px;
    }

    .cv-footer-v2-dev {
        font-size: 0.7rem;
        color: #94A3B8;
    }

    .cv-footer-v2-dev a {
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .cv-footer-v2-dev a:hover {
        color: #0F172A;
    }

    /* ── RESPONSIVE ────────────────── */
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
            padding: 3rem 1.25rem 2.5rem;
        }

        .cv-footer-v2-bottom {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .cv-footer-v2-bottom-links {
            flex-wrap: wrap;
            gap: 0.875rem;
        }

        .cv-footer-v2-tagline {
            max-width: 100%;
        }
    }
</style>

{{-- ════ MAIN FOOTER ════ --}}
<footer class="cv-footer-v2" role="contentinfo">

    <div class="cv-footer-v2-main">

        {{-- Brand Column --}}
        <div>
            <a href="{{ route_locale('home') }}" class="cv-footer-v2-logo-wrap">
                <div class="cv-footer-v2-logo-icon">
                    @php $logo = \App\Models\Setting::get('logo'); @endphp
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="Logo">
                    @else
                        <span
                            style="font-weight:900;color:#0EA5E9;font-size:1rem;font-family:'Montserrat',sans-serif;">buyle.id</span>
                    @endif
                </div>
            </a>

            <p class="cv-footer-v2-tagline">
                {{ $s['footer_desc'] ?? 'Toko serba ada yang menyediakan berbagai kebutuhan rumah tangga, produk elektronik, furnitur, hingga jasa profesional terpercaya.' }}
            </p>

            <div class="cv-footer-v2-badges">
                <span class="cv-footer-v2-badge">
                    <svg width="9" height="9" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Produk Berkualitas
                </span>
                <span class="cv-footer-v2-badge">
                    <svg width="9" height="9" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    Harga Terbaik
                </span>
                <span class="cv-footer-v2-badge">
                    <svg width="9" height="9" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengiriman Cepat
                </span>
                <span class="cv-footer-v2-badge">
                    <svg width="9" height="9" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    CS Siap Bantu
                </span>
            </div>

            <div class="cv-footer-v2-socials">
                @if($wa)
                    <a href="javascript:void(0)" onclick="openOrderModal('Footer WA Icon')" class="cv-footer-v2-social-btn"
                        title="WhatsApp" data-track="Footer WA Icon">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                    </a>
                @endif
                <a href="mailto:info@buyle.id" class="cv-footer-v2-social-btn" title="Email">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <a href="tel:{{ $footerPhoneClean }}" class="cv-footer-v2-social-btn" title="Telepon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.5 12.05a19.79 19.79 0 01-3.07-8.67A2 2 0 012.41 1.5h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 9.4a16 16 0 006.69 6.69l1.27-.76a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Produk Column --}}
        <div>
            <div class="cv-footer-v2-col-title">Produk</div>
            <ul class="cv-footer-v2-links">
                @if($svc->count())
                    @foreach($svc as $item)
                        <li><a href="{{ route_locale('products.show', $item->slug) }}">{{ $item->name }}</a></li>
                    @endforeach
                @else
                    <li><a href="{{ route_locale('products') }}">Smart TV LED 4K 55 Inch</a></li>
                    <li><a href="{{ route_locale('products') }}">AC Split Inverter 1 PK</a></li>
                    <li><a href="{{ route_locale('products') }}">Sofa Bed Minimalis Modern</a></li>
                    <li><a href="{{ route_locale('products') }}">Jasa Pasang AC Profesional</a></li>
                    <li><a href="{{ route_locale('products') }}">Jasa Pembersihan & Servis AC</a></li>
                @endif
            </ul>
        </div>

        {{-- Navigasi Column --}}
        <div>
            <div class="cv-footer-v2-col-title">Navigasi</div>
            <ul class="cv-footer-v2-links">
                <li><a href="{{ route_locale('home') }}">Beranda</a></li>
                <li><a href="{{ route_locale('about') }}">Tentang Kami</a></li>
                <li><a href="{{ route_locale('gallery') }}">Galeri Instalasi</a></li>
                <li><a href="{{ route_locale('articles') }}">Artikel & Tips</a></li>
                <li><a href="{{ route_locale('contact') }}">Hubungi Kami</a></li>
            </ul>
        </div>

        {{-- Kontak Column --}}
        <div>
            <div class="cv-footer-v2-col-title">Kontak</div>

            <div class="cv-footer-v2-contact-item">
                <div class="cv-footer-v2-contact-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="cv-footer-v2-contact-text">
                    <span class="cv-footer-v2-contact-label">Alamat</span>
                    @php
                        $addrDetail = $s['address'] ?? '';
                        $village    = $s['village_name'] ?? '';
                        $district   = $s['district_name'] ?? '';
                        $city       = $s['city_name'] ?? 'Surabaya';
                        $province   = $s['province_name'] ?? 'Jawa Timur';
                        $postal     = $s['postal_code'] ?? '';
                        
                        $fullAddressParts = array_filter([$addrDetail, $village, $district, $city, $province, $postal]);
                        $fullAddress = !empty($fullAddressParts) ? implode(', ', $fullAddressParts) : 'Surabaya, Jawa Timur';
                    @endphp
                    {!! nl2br(e($fullAddress)) !!}
                </div>
            </div>

            <div class="cv-footer-v2-contact-item">
                <div class="cv-footer-v2-contact-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.5 12.05a19.79 19.79 0 01-3.07-8.67A2 2 0 012.41 1.5h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 9.4a16 16 0 006.69 6.69l1.27-.76a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" />
                    </svg>
                </div>
                <div class="cv-footer-v2-contact-text">
                    <span class="cv-footer-v2-contact-label">Telepon</span>
                    @php
                        $phoneVal = $s['phone'] ?? '';
                        $phoneDisplay = $phoneVal ?: ($wa ? $wa->nomor_wa : '0812-9656-5757');
                        $phoneClean = preg_replace('/[^0-9+]/', '', $phoneDisplay);
                    @endphp
                    <a href="tel:{{ $phoneClean }}">{{ $phoneDisplay }}</a>
                </div>
            </div>

            <div class="cv-footer-v2-contact-item">
                <div class="cv-footer-v2-contact-icon">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                </div>
                <div class="cv-footer-v2-contact-text">
                    <span class="cv-footer-v2-contact-label">WhatsApp</span>
                    @if($wa)
                        @php
                            $waNum = $wa->nomor_wa;
                            // Convert international format 628xx → 08xx for display
                            $waDisplay = $waNum;
                            if (str_starts_with($waNum, '62')) {
                                $waDisplay = '0' . substr($waNum, 2);
                            }
                            // Format as 0812-9656-5757 style (4-4-4)
                            $waClean = preg_replace('/[^0-9]/', '', $waDisplay);
                            if (strlen($waClean) >= 10) {
                                $waDisplay = substr($waClean,0,4).'-'.substr($waClean,4,4).'-'.substr($waClean,8);
                            }
                        @endphp
                        <a href="javascript:void(0)" onclick="openOrderModal('Footer WA')"
                            data-track="Footer WA">{{ $waDisplay }}</a>
                    @else
                        <a href="javascript:void(0)" onclick="openOrderModal('Footer WA')">0812-9656-5757</a>
                    @endif
                </div>
            </div>

            <div class="cv-footer-v2-contact-item">
                <div class="cv-footer-v2-contact-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,12 2,6" />
                    </svg>
                </div>
                <div class="cv-footer-v2-contact-text">
                    <span class="cv-footer-v2-contact-label">Email</span>
                    <a href="mailto:{{ $s['email'] ?? 'info@buyle.id' }}">{{ $s['email'] ?? 'info@buyle.id'
                        }}</a>
                </div>
            </div>

            <div class="cv-footer-v2-contact-item">
                <div class="cv-footer-v2-contact-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div class="cv-footer-v2-contact-text">
                    <span class="cv-footer-v2-contact-label">Jam Operasional</span>
                    {{ $s['hours'] ?? '' ?: 'Senin - Sabtu, 08.00 - 17.00 WIB' }}
                </div>
            </div>
        </div>

    </div>{{-- /cv-footer-v2-main --}}

    <hr class="cv-footer-v2-divider">

    <div class="cv-footer-v2-bottom-wrap">
        {{-- Payment & Expedition Section --}}
        <div class="cv-footer-v2-pay-wrap">
            <div class="cv-footer-v2-pay-section">
                <div class="cv-footer-v2-pay-title">Metode Pembayaran</div>
                <div class="cv-footer-v2-pay-logos">
                    @if(!empty($s['payment_logos']))
                        <img src="{{ asset('storage/' . $s['payment_logos']) }}" alt="Metode Pembayaran" loading="lazy">
                    @else
                        {{-- Fallback default text or empty --}}
                        <span style="font-size: 0.8rem; color: #94A3B8; font-weight: 500;">BCA • Mandiri • BNI • QRIS</span>
                    @endif
                </div>
            </div>

            <div class="cv-footer-v2-pay-section">
                <div class="cv-footer-v2-pay-title">Jasa Pengiriman</div>
                <div class="cv-footer-v2-pay-logos">
                    @if(!empty($s['expedition_logos']))
                        <img src="{{ asset('storage/' . $s['expedition_logos']) }}" alt="Jasa Pengiriman" loading="lazy">
                    @else
                        {{-- Fallback default text or empty --}}
                        <span style="font-size: 0.8rem; color: #94A3B8; font-weight: 500;">JNE • J&T • Sicepat • GoSend •
                            GrabExpress</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="cv-footer-v2-bottom">
            <div class="cv-footer-v2-copy">
                <strong>{{ $s['copyright'] ?? '© ' . date('Y') . ' buyle.id' }}</strong>. All rights reserved.
            </div>
            <div class="cv-footer-v2-dev">
                Built by <a href="https://hvmdigital.id/" target="_blank" rel="noopener">HVM Digital</a>
            </div>
        </div>
    </div>

</footer>