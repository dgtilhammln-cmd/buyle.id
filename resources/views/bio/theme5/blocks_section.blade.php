@if($blocks->count() > 0)
<section class="t5-blocks-section" id="links-section">
    <div class="t5-section-header">
        <div class="t5-section-title-wrap">
            <span class="t5-section-badge">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                </svg>
                LINK & REKOMENDASI
            </span>
            <h2 class="t5-section-title">Link Resmi & Tautan Pilihan</h2>
            <p class="t5-section-sub">Akses cepat ke portofolio, media sosial, & rekomendasi resmi.</p>
        </div>
    </div>

    <div class="t5-blocks-grid">
        @foreach($blocks as $block)
            @php
                $bData = $block->data_json ?? [];
                $bType = $block->type;
            @endphp

            @if($bType === 'link' || $bType === 'url')
                @php
                    $url = $bData['url'] ?? '#';
                    $title = $bData['title'] ?? ($bData['label'] ?? 'Tautan Resmi');
                    $sub = $bData['subtitle'] ?? ($bData['desc'] ?? null);
                    $icon = $bData['icon'] ?? null;
                @endphp
                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="t5-block-card t5-link-card">
                    <div class="t5-block-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                            <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                        </svg>
                    </div>
                    <div class="t5-block-info">
                        <span class="t5-block-title">{{ $title }}</span>
                        @if($sub)
                            <span class="t5-block-sub">{{ $sub }}</span>
                        @endif
                    </div>
                    <div class="t5-block-arrow">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </a>
            @elseif($bType === 'banner')
                @php
                    $bImg = !empty($bData['image']) ? (Str::startsWith($bData['image'], ['http://', 'https://']) ? $bData['image'] : asset('storage/' . $bData['image'])) : null;
                    $bUrl = $bData['url'] ?? '#';
                @endphp
                @if($bImg)
                    <a href="{{ $bUrl }}" target="_blank" rel="noopener noreferrer" class="t5-block-banner-card">
                        <img src="{{ $bImg }}" alt="Banner Promo" loading="lazy" draggable="false">
                    </a>
                @endif
            @elseif($bType === 'heading' || $bType === 'title')
                <div class="t5-block-heading-wrap">
                    <h3 class="t5-block-heading">{{ $bData['text'] ?? ($bData['title'] ?? 'Kategori') }}</h3>
                </div>
            @endif
        @endforeach
    </div>
</section>
@endif
