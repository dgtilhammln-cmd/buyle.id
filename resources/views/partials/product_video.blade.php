@php
    $rawVideo = $videoUrl ?? (isset($product) && method_exists($product, 'getVideoUrlsAttribute') ? $product->video_urls : ($product->tiktok_video_url ?? $product->youtube_video_url ?? ($service->tiktok_video_url ?? null)));

    $allUrls = [];
    if (!empty($rawVideo)) {
        if (is_array($rawVideo)) {
            $allUrls = array_values(array_filter($rawVideo));
        } elseif (is_string($rawVideo)) {
            $trimmed = trim($rawVideo);
            if (str_starts_with($trimmed, '[') || str_starts_with($trimmed, '{')) {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded)) {
                    $allUrls = array_values(array_filter($decoded));
                } else {
                    $allUrls = [$trimmed];
                }
            } else {
                $allUrls = [$trimmed];
            }
        }
    }

    $ttItems = [];
    $ytItem = null;
    $otherItems = [];

    foreach ($allUrls as $u) {
        $uStr = trim((string)$u);
        if (empty($uStr)) continue;

        // Check YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts|live)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $uStr, $ytMatches)) {
            if (!$ytItem) $ytItem = ['url' => $uStr, 'id' => $ytMatches[1]];
        }
        // Check TikTok ID
        elseif (preg_match('/(?:tiktok\.com\/.*\/video\/|tiktok\.com\/v\/)(\d+)/i', $uStr, $ttMatches)) {
            $ttItems[] = ['url' => $uStr, 'id' => $ttMatches[1]];
        }
        // TikTok fallback
        elseif (str_contains(strtolower($uStr), 'tiktok.com')) {
            $ttItems[] = ['url' => $uStr, 'id' => null];
        }
        // Direct mp4/webm or other
        else {
            $otherItems[] = $uStr;
        }
    }
@endphp

@if(!empty($ytItem) || !empty($ttItems) || !empty($otherItems))
    <div class="pd-video-block" style="margin-top: 1.5rem; margin-bottom: 1.25rem; width:100%; max-width:100%; box-sizing:border-box; overflow:hidden;">
        
        {{-- YOUTUBE EMBED (16:9) --}}
        @if($ytItem)
            <div style="position:relative; width:100%; aspect-ratio:16/9; border-radius:14px; overflow:hidden; background:#0f172a; border:1px solid #e2e8f0; box-shadow:0 4px 16px rgba(0,0,0,0.06); max-width:100%; box-sizing:border-box;">
                <iframe src="https://www.youtube-nocookie.com/embed/{{ $ytItem['id'] }}?rel=0"
                    style="position:absolute; top:0; left:0; width:100%; height:100%; border:none;"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen loading="lazy"></iframe>
            </div>
        @endif

        {{-- TIKTOK EMBEDS: JEJER HORIZONTAL & AMAN DI DALAM BOX DESKRIPSI --}}
        @if(!empty($ttItems))
            <style>
                .pd-tiktok-horizontal-row {
                    display: flex;
                    gap: 12px;
                    width: 100%;
                    max-width: 100%;
                    box-sizing: border-box;
                    align-items: stretch;
                }
                .pd-tiktok-card-item {
                    aspect-ratio: 9/16;
                    max-height: 520px;
                    border-radius: 14px;
                    overflow: hidden;
                    background: #000;
                    border: 1.5px solid #e2e8f0;
                    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
                    box-sizing: border-box;
                }
                @media (min-width: 641px) {
                    .pd-tiktok-horizontal-row {
                        overflow-x: visible;
                    }
                    .pd-tt-count-3 .pd-tiktok-card-item {
                        flex: 1 1 0 !important;
                        width: calc((100% - 24px) / 3) !important;
                        max-width: calc((100% - 24px) / 3) !important;
                        min-width: 0 !important;
                    }
                    .pd-tt-count-2 .pd-tiktok-card-item {
                        flex: 1 1 0 !important;
                        width: calc((100% - 12px) / 2) !important;
                        max-width: calc((100% - 12px) / 2) !important;
                        min-width: 0 !important;
                    }
                    .pd-tt-count-1 .pd-tiktok-card-item {
                        flex: 0 0 min(320px, 100%) !important;
                        width: min(320px, 100%) !important;
                        max-width: 320px !important;
                    }
                    .pd-tt-scroll-hint {
                        display: none !important;
                    }
                }
                @media (max-width: 640px) {
                    .pd-tiktok-horizontal-row {
                        overflow-x: auto;
                        scroll-snap-type: x mandatory;
                        -webkit-overflow-scrolling: touch;
                        padding-bottom: 8px;
                    }
                    .pd-tiktok-card-item {
                        flex: 0 0 min(240px, 72vw) !important;
                        width: min(240px, 72vw) !important;
                        scroll-snap-align: start;
                        flex-shrink: 0;
                    }
                    .pd-tt-scroll-hint {
                        display: inline-block !important;
                    }
                }
            </style>

            <div style="width:100%; max-width:100%; box-sizing:border-box;">
                @if(count($ttItems) > 1)
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.6rem; font-size:0.8rem; font-weight:700; color:#334155;">
                        <span style="display:flex; align-items:center; gap:0.35rem;">
                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.28 6.28 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/></svg>
                            Video Review TikTok ({{ count($ttItems) }})
                        </span>
                        <span class="pd-tt-scroll-hint" style="font-size:0.72rem; color:#94a3b8; font-weight:500;">Geser untuk melihat semua &rarr;</span>
                    </div>
                @endif

                <div class="pd-tiktok-horizontal-row pd-tt-count-{{ min(count($ttItems), 3) }}">
                    @foreach($ttItems as $idx => $tt)
                        <div class="pd-tiktok-card-item">
                            @if(!empty($tt['id']))
                                <iframe src="https://www.tiktok.com/player/v1/{{ $tt['id'] }}?music_info=1&description=1"
                                    style="width:100%; height:100%; border:none; display:block;"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen loading="lazy"></iframe>
                            @else
                                <blockquote class="tiktok-embed" cite="{{ $tt['url'] }}" style="max-width:100%; margin:0 auto; padding:0;">
                                    <section><a target="_blank" href="{{ $tt['url'] }}">Tonton di TikTok #{{ $idx+1 }}</a></section>
                                </blockquote>
                                <script async src="https://www.tiktok.com/embed.js"></script>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- OTHER VIDEOS / MP4 --}}
        @foreach($otherItems as $oUrl)
            @if(preg_match('/\.(mp4|webm|ogg)($|\?)/i', $oUrl))
                <div style="width:100%; aspect-ratio:16/9; border-radius:14px; overflow:hidden; background:#000; border:1px solid #e2e8f0; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-top:0.75rem; max-width:100%; box-sizing:border-box;">
                    <video controls playsinline preload="metadata" style="width:100%; height:100%; object-fit:contain; display:block;">
                        <source src="{{ $oUrl }}">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            @else
                <a href="{{ $oUrl }}" target="_blank" rel="noopener noreferrer"
                    style="display:flex; align-items:center; gap:0.85rem; padding:0.85rem 1rem; border-radius:12px; background:#f8fafc; border:1.5px solid #e2e8f0; text-decoration:none; color:#0f172a; transition:all 0.2s; margin-top:0.75rem; max-width:100%; box-sizing:border-box;"
                    onmouseover="this.style.borderColor='#1eb349';this.style.background='#f0fdf4';"
                    onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';">
                    <div style="width:36px; height:36px; border-radius:8px; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:0.82rem; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Tonton Video Produk</div>
                        <div style="font-size:0.72rem; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $oUrl }}</div>
                    </div>
                    <svg width="16" height="16" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/></svg>
                </a>
            @endif
        @endforeach

    </div>
@endif

