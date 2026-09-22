@php
    $bioName  = $config['name'] ?? $profile->store_name ?? $username;
    $roleTitle = $profile->bio_role ? ucfirst(str_replace('_', ' ', $profile->bio_role)) : 'Professional Creator';
    $avatarUrl = !empty($config['avatar']) 
        ? asset('storage/' . $config['avatar']) 
        : (!empty($config['_user_avatar']) ? (Str::startsWith($config['_user_avatar'], ['http://', 'https://']) ? $config['_user_avatar'] : asset('storage/' . $config['_user_avatar'])) : null);
    
    $storeSlug = $profile->store_slug ?? $username;
    $products  = $products ?? collect();
    $blocks    = $blocks ?? collect();
@endphp

<style>
    /* ── SELF-CONTAINED THEME 5 HEADER STYLES ── */
    .t5-header {
        position: sticky;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 999;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid #e2e8f0;
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    .t5-header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .t5-brand {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        text-decoration: none;
        flex-shrink: 0;
    }
    .t5-brand-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #bbf7d0;
    }
    .t5-brand-avatar-fallback {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1eb349, #15803d);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .t5-brand-info {
        display: flex;
        flex-direction: column;
    }
    .t5-brand-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
        letter-spacing: -0.02em;
    }
    .t5-nav-desktop {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .t5-nav-link {
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        color: #475569;
        transition: color 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .t5-nav-link:hover, .t5-nav-link.active {
        color: #15803d;
        font-weight: 600;
    }
    .t5-header-actions {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .t5-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.42rem 0.85rem;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: 'Montserrat', sans-serif;
    }
    .t5-action-btn:hover {
        border-color: #1eb349;
        color: #15803d;
    }
    .t5-btn-wa {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
        font-weight: 600;
    }
    .t5-btn-wa:hover {
        background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 6px 18px rgba(30, 179, 73, 0.4);
    }
    .t5-btn-cart {
        background: #ffffff;
        color: #334155;
        border-color: #e2e8f0;
        position: relative;
        font-weight: 500;
    }
    .t5-btn-cart:hover {
        background: #f0fdf4 !important;
        color: #15803d !important;
        border-color: #1eb349 !important;
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.18);
    }
    .t5-cart-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #ef4444;
        color: #ffffff;
        font-size: 0.65rem;
        font-weight: 800;
        min-width: 18px;
        height: 18px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        line-height: 1;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
        border: 2px solid #ffffff;
    }
    .t5-drawer-cart-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .t5-drawer-cart-badge {
        background: #ef4444;
        color: #ffffff;
        font-size: 0.68rem;
        font-weight: 800;
        min-width: 20px;
        height: 20px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        line-height: 1;
        box-shadow: 0 2px 5px rgba(239, 68, 68, 0.35);
    }
    .t5-mobile-toggle {
        display: none;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        color: #0f172a;
        padding: 0.4rem;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .t5-mobile-toggle:hover {
        background: #e2e8f0;
    }
    @media (max-width: 991px) {
        .t5-nav-desktop { display: none !important; }
        .t5-mobile-toggle { display: inline-flex !important; }
    }
    @media (max-width: 640px) {
        .t5-header-container {
            padding: 0.55rem 0.85rem;
        }
        .t5-brand-avatar, .t5-brand-avatar-fallback {
            width: 34px;
            height: 34px;
            font-size: 0.85rem;
        }
        .t5-brand-title {
            font-size: 0.88rem;
        }
        .t5-btn-text {
            display: none !important;
        }
        .t5-action-btn {
            padding: 0.4rem 0.55rem;
        }
    }

    /* ── MOBILE DRAWER STANDALONE STYLES ── */
    .t5-mobile-drawer {
        position: fixed;
        inset: 0;
        z-index: 99999;
        pointer-events: none;
        font-family: 'Montserrat', sans-serif;
    }
    .t5-mobile-drawer.active {
        pointer-events: auto;
    }
    .t5-drawer-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .t5-mobile-drawer.active .t5-drawer-overlay {
        opacity: 1;
    }
    .t5-drawer-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 280px;
        background: #ffffff;
        box-shadow: -4px 0 20px rgba(0,0,0,0.15);
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
    }
    .t5-mobile-drawer.active .t5-drawer-content {
        transform: translateX(0);
    }
    .t5-drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 1.25rem;
    }
    .t5-drawer-title {
        font-weight: 600;
        font-size: 1rem;
        color: #0f172a;
    }
    .t5-drawer-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #64748b;
        cursor: pointer;
    }
    .t5-drawer-nav {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .t5-drawer-link {
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        color: #334155;
        padding: 0.65rem 0.85rem;
        border-radius: 6px;
        transition: background 0.2s, color 0.2s;
    }
    .t5-drawer-link:hover {
        background: #f0fdf4;
        color: #1eb349;
    }
</style>

@php
    $homeUrl = !empty($profile->custom_domain) ? 'https://' . rtrim($profile->custom_domain, '/') : url('/' . $username);
    $productsUrl = !empty($profile->custom_domain) ? 'https://' . rtrim($profile->custom_domain, '/') . '/produk' : url('/' . $username . '/produk');
@endphp

<header class="t5-header">
    <div class="t5-header-container">
        {{-- Brand / Logo --}}
        <a href="{{ $homeUrl }}" class="t5-brand">
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ $bioName }}" class="t5-brand-avatar">
            @else
                <div class="t5-brand-avatar-fallback">{{ strtoupper(substr($bioName, 0, 2)) }}</div>
            @endif
            <div class="t5-brand-info">
                <span class="t5-brand-title">{{ $bioName }}</span>
            </div>
        </a>

        {{-- Desktop Navigation Links --}}
        <nav class="t5-nav-desktop">
            <a href="{{ $homeUrl }}" class="t5-nav-link {{ request()->is($username) || request()->path() === '/' ? 'active' : '' }}">Beranda</a>
            <a href="{{ $homeUrl }}#about-section" class="t5-nav-link">Profil</a>
            <a href="{{ $productsUrl }}" class="t5-nav-link {{ request()->is('*/produk*') || request()->is('produk*') ? 'active' : '' }}">Produk / Layanan</a>
            
            @if(isset($blocks) && $blocks->count() > 0)
                @foreach($blocks as $b)
                    @php $bData = $b->data_json ?? []; @endphp
                    @if(in_array($b->type, ['link', 'url', 'custom_link', 'shopee', 'affiliate', 'external']) || (!empty($bData['url']) && empty($bData['product_id'])))
                        @php
                            $bUrl = $bData['url'] ?? ($bData['link'] ?? ($b->url ?? '#'));
                            if ($bUrl !== '#' && !\Illuminate\Support\Str::startsWith($bUrl, ['http://', 'https://', '/', '#'])) {
                                $bUrl = 'https://' . $bUrl;
                            }
                            $bTitle = $b->title ?? ($bData['title'] ?? ($bData['label'] ?? 'Link'));
                        @endphp
                        <a href="{{ $bUrl }}" target="_blank" rel="noopener noreferrer" class="t5-nav-link t5-custom-block-link">{{ $bTitle }}</a>
                    @endif
                @endforeach
            @endif

            <a href="{{ $homeUrl }}#contact-section" class="t5-nav-link">Kontak</a>
        </nav>

        {{-- Action Buttons --}}
        <div class="t5-header-actions">
            {{-- Cart Button --}}
            @php
                $cartBadgeVal = 0;
                try {
                    if (class_exists(\App\Services\CartService::class)) {
                        $cartBadgeVal = (int) app(\App\Services\CartService::class)->getItems()->sum('qty');
                    }
                } catch (\Throwable $e) {}
            @endphp
            <a href="https://buyle.id/keranjang" class="t5-action-btn t5-btn-cart" title="Keranjang Belanja">
                <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <span class="t5-cart-badge" id="t5HeaderCartBadge" style="{{ $cartBadgeVal > 0 ? '' : 'display:none;' }}">{{ $cartBadgeVal }}</span>
            </a>

            @if(!empty($config['wa']))
                <a href="javascript:void(0)" onclick="openT5LeadModal('Halo, saya ingin berkonsultasi via WhatsApp.')" class="t5-action-btn t5-btn-wa" title="Hubungi via WhatsApp">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.556 4.117 1.528 5.849L0 24l6.335-1.508A11.948 11.948 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.65-.52-5.154-1.422l-.37-.218-3.764.896.924-3.667-.243-.381A9.953 9.953 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z"/>
                    </svg>
                    <span class="t5-btn-text">WhatsApp</span>
                </a>
            @endif

            <button type="button" class="t5-action-btn t5-btn-share" onclick="openShareSheet()" title="Bagikan Profil">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
            </button>

            {{-- Mobile Drawer Trigger Button --}}
            <button type="button" class="t5-mobile-toggle" onclick="toggleT5Drawer()" aria-label="Menu Mobile">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

</header>

{{-- Mobile Nav Drawer (Positioned outside <header> to avoid backdrop-filter containing block trap) --}}
<div class="t5-mobile-drawer" id="t5MobileDrawer">
    <div class="t5-drawer-overlay" onclick="toggleT5Drawer()"></div>
    <div class="t5-drawer-content">
        <div class="t5-drawer-header">
            <span class="t5-drawer-title">Menu Navigasi</span>
            <button type="button" class="t5-drawer-close" onclick="toggleT5Drawer()">&times;</button>
        </div>
        <nav class="t5-drawer-nav">
            <a href="{{ $homeUrl }}" onclick="toggleT5Drawer()" class="t5-drawer-link">Beranda</a>
            <a href="{{ $homeUrl }}#about-section" onclick="toggleT5Drawer()" class="t5-drawer-link">Profil</a>
            <a href="{{ $productsUrl }}" onclick="toggleT5Drawer()" class="t5-drawer-link">Produk / Layanan</a>
            <a href="https://buyle.id/keranjang" class="t5-drawer-link t5-drawer-cart-link">
                <span style="display:inline-flex; align-items:center; gap:0.45rem;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#1eb349;">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    Keranjang
                </span>
                <span class="t5-drawer-cart-badge" id="t5DrawerCartBadge" style="{{ $cartBadgeVal > 0 ? '' : 'display:none;' }}">{{ $cartBadgeVal }}</span>
            </a>
            @if(isset($blocks) && $blocks->count() > 0)
                @foreach($blocks as $b)
                    @php $bData = $b->data_json ?? []; @endphp
                    @if(in_array($b->type, ['link', 'url', 'custom_link', 'shopee', 'affiliate', 'external']) || (!empty($bData['url']) && empty($bData['product_id'])))
                        @php
                            $bUrl = $bData['url'] ?? ($bData['link'] ?? ($b->url ?? '#'));
                            if ($bUrl !== '#' && !\Illuminate\Support\Str::startsWith($bUrl, ['http://', 'https://', '/', '#'])) {
                                $bUrl = 'https://' . $bUrl;
                            }
                            $bTitle = $b->title ?? ($bData['title'] ?? ($bData['label'] ?? 'Link'));
                        @endphp
                        <a href="{{ $bUrl }}" target="_blank" rel="noopener noreferrer" onclick="toggleT5Drawer()" class="t5-drawer-link t5-drawer-custom-link">{{ $bTitle }}</a>
                    @endif
                @endforeach
            @endif
            <a href="{{ $homeUrl }}#contact-section" onclick="toggleT5Drawer()" class="t5-drawer-link">Kontak</a>
        </nav>
    </div>
</div>

<script>
    function toggleT5Drawer() {
        var drawer = document.getElementById('t5MobileDrawer');
        if (!drawer) return;
        if (drawer.parentNode !== document.body) {
            document.body.appendChild(drawer);
        }
        var isActive = drawer.classList.contains('active');
        if (isActive) {
            drawer.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            drawer.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        var drawer = document.getElementById('t5MobileDrawer');
        if (drawer && drawer.parentNode !== document.body) {
            document.body.appendChild(drawer);
        }
    });

    window.updateHeaderCartBadge = function(count) {
        var desktopBadge = document.getElementById('t5HeaderCartBadge');
        var drawerBadge = document.getElementById('t5DrawerCartBadge');
        if (count > 0) {
            if (desktopBadge) {
                desktopBadge.textContent = count;
                desktopBadge.style.display = 'inline-flex';
            }
            if (drawerBadge) {
                drawerBadge.textContent = count;
                drawerBadge.style.display = 'inline-flex';
            }
        }
    };

    window.showCartToast = function(msg) {
        var toast = document.createElement('div');
        toast.style.cssText = 'position:fixed;bottom:28px;left:50%;transform:translateX(-50%);background:#10b981;color:#fff;padding:0.65rem 1.3rem;border-radius:99px;font-size:0.85rem;font-weight:600;box-shadow:0 10px 25px rgba(16,185,129,0.35);z-index:99999;display:flex;align-items:center;gap:0.5rem;transition:all 0.3s ease;opacity:0;';
        toast.innerHTML = '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> ' + (msg || 'Produk dimasukkan ke keranjang');
        document.body.appendChild(toast);
        setTimeout(function(){ toast.style.opacity = '1'; toast.style.transform = 'translateX(-50%) translateY(-6px)'; }, 50);
        setTimeout(function(){ toast.style.opacity = '0'; toast.style.transform = 'translateX(-50%) translateY(0)'; setTimeout(function(){ toast.remove(); }, 300); }, 3000);
    };
</script>
