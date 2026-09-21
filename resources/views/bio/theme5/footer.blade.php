@php
    $bioName      = $config['name'] ?? $profile->store_name ?? $username;
    $bioText      = $config['bio'] ?? $profile->store_description ?? 'Website resmi & katalog terpercaya.';
    $avatarUrl    = !empty($config['avatar']) 
        ? asset('storage/' . $config['avatar']) 
        : (!empty($config['_user_avatar']) ? (Str::startsWith($config['_user_avatar'], ['http://', 'https://']) ? $config['_user_avatar'] : asset('storage/' . $config['_user_avatar'])) : null);
    
    $locationText = !empty($config['location']) ? $config['location'] : ($profile->address ?? null);
    $embedMaps    = $config['embed_location'] ?? null;
@endphp

<style>
    /* ── SELF-CONTAINED THEME 5 FOOTER STYLES ── */
    .t5-footer {
        background: #0d0d0d !important;
        color: #ffffff !important;
        margin-top: 3rem;
        padding: 3rem 1.5rem 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.07);
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    .t5-footer-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .t5-footer-main {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 2.5rem;
        margin-bottom: 2.5rem;
        padding-bottom: 2.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    @media (max-width: 768px) {
        .t5-footer-main {
            grid-template-columns: 1fr;
            gap: 1.75rem;
        }
    }
    .t5-footer-brand-head {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.85rem;
    }
    .t5-footer-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #1eb349;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.25);
        flex-shrink: 0;
    }
    .t5-footer-avatar-fallback {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1eb349, #15803d);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .t5-footer-brand-meta {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    .t5-footer-title {
        display: block;
        font-size: 1.3rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 0.2rem;
    }
    .t5-footer-verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.72rem;
        font-weight: 500;
        color: #4ade80;
        background: rgba(30, 179, 73, 0.15);
        border: 1px solid rgba(30, 179, 73, 0.35);
        padding: 0.15rem 0.55rem;
        border-radius: 6px;
    }
    .t5-footer-desc {
        font-size: 0.82rem;
        color: #94a3b8;
        line-height: 1.6;
        max-width: 400px;
    }
    .t5-footer-heading {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #ffffff;
        margin-bottom: 1rem;
    }
    .t5-footer-links {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .t5-footer-link {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.85rem;
        transition: color 0.2s ease;
    }
    .t5-footer-link:hover {
        color: #1eb349;
    }
    .t5-footer-location-text {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        font-size: 0.82rem;
        color: #cbd5e1;
        margin-bottom: 0.85rem;
        line-height: 1.5;
    }
    .t5-footer-location-icon {
        color: #1eb349;
        flex-shrink: 0;
        margin-top: 0.15rem;
    }
    .t5-footer-map-wrap {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.05);
        max-height: 160px;
    }
    .t5-footer-map-wrap iframe {
        width: 100% !important;
        height: 150px !important;
        border: 0 !important;
        display: block;
    }
    .t5-footer-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.8rem;
        color: #94a3b8;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .t5-footer-bottom a {
        color: #1eb349;
        text-decoration: none;
        font-weight: 500;
    }
    .t5-back-to-top {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(255,255,255,0.1);
        color: #ffffff;
        border: 1px solid rgba(255,255,255,0.12);
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .t5-back-to-top:hover {
        background: #1eb349;
        transform: translateY(-2px);
    }
    .t5-footer-social {
        margin-top: 1rem;
    }
    .t5-footer-social .social-row {
        justify-content: flex-start !important;
        margin: 0 !important;
    }
    .t5-footer-social .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        transition: background 0.2s, border-color 0.2s;
        flex-shrink: 0;
    }
    .t5-footer-social .social-icon:hover {
        background: rgba(255,255,255,0.18);
        border-color: rgba(255,255,255,0.3);
    }
    .t5-footer-social .social-icon svg {
        display: block;
        filter: brightness(0) invert(1);
    }
    .t5-footer-social .social-icon img {
        display: block;
        filter: none !important;
    }
</style>

<footer class="t5-footer" id="footer-section">
    <div class="t5-footer-container">
        <div class="t5-footer-main">
            {{-- Brand & About Creator Info in Footer --}}
            <div class="t5-footer-brand">
                <div class="t5-footer-brand-head">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $bioName }}" class="t5-footer-avatar">
                    @else
                        <div class="t5-footer-avatar-fallback">{{ strtoupper(substr($bioName, 0, 2)) }}</div>
                    @endif
                    <div class="t5-footer-brand-meta">
                        <span class="t5-footer-title">{{ $bioName }}</span>
                        <span class="t5-footer-verified-badge">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            Terverifikasi buyle.id
                        </span>
                    </div>
                </div>

                <p class="t5-footer-desc">{{ $bioText }}</p>

                {{-- Social Media Icon Row --}}
                <div class="t5-footer-social">
                    @include('bio._social_icons', ['profile' => $profile, 'config' => $config])
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="t5-footer-links">
                <h4 class="t5-footer-heading">Navigasi Cepat</h4>
                <a href="{{ url('/' . $username) }}" class="t5-footer-link">Beranda</a>
                <a href="{{ url('/' . $username . '/produk') }}" class="t5-footer-link">Produk</a>
                <a href="{{ url('/' . $username) }}#about-section" class="t5-footer-link">Tentang</a>
            </div>

            {{-- Location & Embed Maps --}}
            <div class="t5-footer-location">
                <h4 class="t5-footer-heading">Lokasi Kami</h4>
                
                @if(!empty($locationText))
                    <div class="t5-footer-location-text">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="t5-footer-location-icon">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span>{{ $locationText }}</span>
                    </div>
                @endif

                @if(!empty($embedMaps))
                    <div class="t5-footer-map-wrap">
                        {!! $embedMaps !!}
                    </div>
                @elseif(!empty($locationText))
                    <a href="https://maps.google.com/?q={{ urlencode($locationText) }}" target="_blank" rel="noopener" class="t5-footer-map-link" style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.78rem; color:#1eb349; text-decoration:none; margin-top:0.3rem;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polygon points="1 6 1 22 8 18 15 22 22 18 22 2 15 6 8 2 1 6"/>
                            <line x1="8" y1="2" x2="8" y2="18"/>
                            <line x1="15" y1="6" x2="15" y2="22"/>
                        </svg>
                        Buka di Google Maps
                    </a>
                @else
                    <div class="t5-footer-location-text" style="opacity:0.6;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="t5-footer-location-icon">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span>Indonesia</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="t5-footer-bottom">
            <p>&copy; {{ date('Y') }} <strong>{{ $bioName }}</strong> &bull; Powered by <a href="https://buyle.id" target="_blank" rel="noopener">buyle.id</a></p>
            
            <button type="button" class="t5-back-to-top" onclick="window.scrollTo({top:0, behavior:'smooth'})" title="Kembali ke Atas">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="18 15 12 9 6 15"/>
                </svg>
                <span>Atas</span>
            </button>
        </div>
    </div>
</footer>
