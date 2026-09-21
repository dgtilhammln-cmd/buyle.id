@php
    $bioName      = $config['name'] ?? $profile->store_name ?? $username;
    $bioText      = $config['bio'] ?? $profile->store_description ?? 'Website resmi & katalog terpercaya.';
    $avatarUrl    = !empty($config['avatar']) 
        ? asset('storage/' . $config['avatar']) 
        : (!empty($config['_user_avatar']) ? (Str::startsWith($config['_user_avatar'], ['http://', 'https://']) ? $config['_user_avatar'] : asset('storage/' . $config['_user_avatar'])) : null);
    
    $locationText = !empty($config['location']) ? $config['location'] : ($profile->address ?? null);
    $embedMaps    = $config['embed_location'] ?? null;
@endphp

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
                <a href="#hero-section" class="t5-footer-link">Beranda</a>
                @if(isset($products) && $products->count() > 0)
                    <a href="#products-section" class="t5-footer-link">Katalog Produk</a>
                @endif
                <a href="{{ url('/' . $username . '/produk') }}" class="t5-footer-link">Semua Produk</a>
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
                    <a href="https://maps.google.com/?q={{ urlencode($locationText) }}" target="_blank" rel="noopener" class="t5-footer-map-link" style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.78rem; color:var(--t5-emerald); text-decoration:none; margin-top:0.3rem;">
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
