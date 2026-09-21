@php
    $bioName = $config['name'] ?? $profile->store_name ?? $username;
    $roleTitle = $profile->bio_role ? ucfirst(str_replace('_', ' ', $profile->bio_role)) : 'Digital Creator';
@endphp

<footer class="t5-footer">
    <div class="t5-footer-container">
        <div class="t5-footer-main">
            <div class="t5-footer-brand">
                <span class="t5-footer-title">{{ $bioName }}</span>
                <span class="t5-footer-subtitle">{{ $roleTitle }} &bull; Portofolio Digital Resmi</span>
                <p class="t5-footer-desc">Platform etalase digital terverifikasi. Transaksi aman & terlindungi melalui ekosistem buyle.id.</p>
            </div>

            <div class="t5-footer-links">
                <h4 class="t5-footer-heading">Navigasi Cepat</h4>
                <a href="#hero-section" class="t5-footer-link">Beranda</a>
                @if($products->count() > 0)
                    <a href="#products-section" class="t5-footer-link">Katalog Produk</a>
                @endif
                @if($blocks->count() > 0)
                    <a href="#links-section" class="t5-footer-link">Rekomendasi & Link</a>
                @endif
                <a href="#about-section" class="t5-footer-link">Tentang Creator</a>
            </div>

            <div class="t5-footer-security">
                <h4 class="t5-footer-heading">Keamanan & Layanan</h4>
                <div class="t5-security-badge">
                    <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <span>Terverifikasi buyle.id</span>
                </div>
                <div class="t5-security-badge">
                    <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>Akses File & Lisensi Instan</span>
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
