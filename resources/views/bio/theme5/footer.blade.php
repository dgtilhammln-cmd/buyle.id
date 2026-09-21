@php
    $bioName   = $config['name'] ?? $profile->store_name ?? $username;
    $bioText   = $config['bio'] ?? $profile->store_description ?? 'Website resmi & katalog terpercaya.';
    $avatarUrl = !empty($config['avatar']) 
        ? asset('storage/' . $config['avatar']) 
        : (!empty($config['_user_avatar']) ? (Str::startsWith($config['_user_avatar'], ['http://', 'https://']) ? $config['_user_avatar'] : asset('storage/' . $config['_user_avatar'])) : null);
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
                @if($products->count() > 0)
                    <a href="#products-section" class="t5-footer-link">Katalog Produk</a>
                @endif
            </div>

            {{-- Security & Support --}}
            <div class="t5-footer-security">
                <h4 class="t5-footer-heading">Keamanan & Layanan</h4>
                <div class="t5-security-badge">
                    <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <span>Transaksi Terlindungi 100%</span>
                </div>
                <div class="t5-security-badge">
                    <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>Layanan & Produk Resmi Terjamin</span>
                </div>
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
