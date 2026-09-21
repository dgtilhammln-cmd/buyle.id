@php
    $bioName  = $config['name'] ?? $profile->store_name ?? $username;
    $roleTitle = $profile->bio_role ? ucfirst(str_replace('_', ' ', $profile->bio_role)) : 'Professional Creator';
    $avatarUrl = !empty($config['avatar']) 
        ? asset('storage/' . $config['avatar']) 
        : (!empty($config['_user_avatar']) ? (Str::startsWith($config['_user_avatar'], ['http://', 'https://']) ? $config['_user_avatar'] : asset('storage/' . $config['_user_avatar'])) : null);
    
    $storeSlug = $profile->store_slug ?? $username;
@endphp

<header class="t5-header">
    <div class="t5-header-container">
        {{-- Brand / Logo --}}
        <a href="{{ url('/' . $username) }}" class="t5-brand">
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
            <a href="#hero-section" class="t5-nav-link active">Beranda</a>
            @if($products->count() > 0)
                <a href="#products-section" class="t5-nav-link">Katalog Produk</a>
            @endif
            @if($blocks->count() > 0)
                @foreach($blocks as $b)
                    @php $bData = $b->data_json ?? []; @endphp
                    @if(in_array($b->type, ['link', 'url', 'custom_link']) || (!empty($bData['url']) && empty($bData['product_id'])))
                        @php
                            $bUrl = $bData['url'] ?? '#';
                            $bTitle = $bData['title'] ?? ($bData['label'] ?? ($b->title ?? 'Link'));
                        @endphp
                        <a href="{{ $bUrl }}" target="_blank" rel="noopener noreferrer" class="t5-nav-link t5-custom-block-link">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                            </svg>
                            <span>{{ $bTitle }}</span>
                        </a>
                    @endif
                @endforeach
            @endif
            <a href="#about-section" class="t5-nav-link">Tentang</a>
        </nav>

        {{-- Action Buttons --}}
        <div class="t5-header-actions">
            @if(!empty($config['wa']))
                @php $waNum = preg_replace('/^(62|0)/', '', $config['wa']); @endphp
                <a href="https://wa.me/62{{ $waNum }}" target="_blank" class="t5-action-btn t5-btn-wa" title="Hubungi via WhatsApp">
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

    {{-- Mobile Nav Drawer --}}
    <div class="t5-mobile-drawer" id="t5MobileDrawer">
        <div class="t5-drawer-overlay" onclick="toggleT5Drawer()"></div>
        <div class="t5-drawer-content">
            <div class="t5-drawer-header">
                <span class="t5-drawer-title">Menu Navigasi</span>
                <button type="button" class="t5-drawer-close" onclick="toggleT5Drawer()">&times;</button>
            </div>
            <nav class="t5-drawer-nav">
                <a href="#hero-section" onclick="toggleT5Drawer()" class="t5-drawer-link">Beranda</a>
                @if($products->count() > 0)
                    <a href="#products-section" onclick="toggleT5Drawer()" class="t5-drawer-link">Katalog Produk</a>
                @endif
                @if($blocks->count() > 0)
                    @foreach($blocks as $b)
                        @php $bData = $b->data_json ?? []; @endphp
                        @if(in_array($b->type, ['link', 'url', 'custom_link']) || (!empty($bData['url']) && empty($bData['product_id'])))
                            @php
                                $bUrl = $bData['url'] ?? '#';
                                $bTitle = $bData['title'] ?? ($bData['label'] ?? ($b->title ?? 'Link'));
                            @endphp
                            <a href="{{ $bUrl }}" target="_blank" rel="noopener noreferrer" onclick="toggleT5Drawer()" class="t5-drawer-link t5-drawer-custom-link">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                    <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                                </svg>
                                <span>{{ $bTitle }}</span>
                            </a>
                        @endif
                    @endforeach
                @endif
                <a href="#about-section" onclick="toggleT5Drawer()" class="t5-drawer-link">Tentang Creator</a>
            </nav>
        </div>
    </div>
</header>
