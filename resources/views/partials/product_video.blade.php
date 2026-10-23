@php
    $videoUrl = $videoUrl ?? $product->tiktok_video_url ?? $product->youtube_video_url ?? $service->tiktok_video_url ?? null;
@endphp

@if(!empty($videoUrl))
    @php
        $ytId = null;
        $ttId = null;
        $isMp4 = false;

        // Check YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts|live)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $videoUrl, $ytMatches)) {
            $ytId = $ytMatches[1];
        }
        // Check TikTok
        elseif (preg_match('/(?:tiktok\.com\/.*\/video\/|tiktok\.com\/v\/)(\d+)/i', $videoUrl, $ttMatches)) {
            $ttId = $ttMatches[1];
        }
        // Check direct mp4/webm
        elseif (preg_match('/\.(mp4|webm|ogg)($|\?)/i', $videoUrl)) {
            $isMp4 = true;
        }
    @endphp

    <div class="pd-video-block" style="margin-top: 1.5rem; margin-bottom: 1.25rem;">
        @if($ytId)
            {{-- Minimalist YouTube 16:9 Embed --}}
            <div style="position:relative; width:100%; aspect-ratio:16/9; border-radius:14px; overflow:hidden; background:#0f172a; border:1px solid #e2e8f0; box-shadow:0 4px 16px rgba(0,0,0,0.06);">
                <iframe src="https://www.youtube-nocookie.com/embed/{{ $ytId }}?rel=0"
                    style="position:absolute; top:0; left:0; width:100%; height:100%; border:none;"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen loading="lazy"></iframe>
            </div>
        @elseif($ttId)
            {{-- Minimalist TikTok Player Embed --}}
            <div style="display:flex; justify-content:center; width:100%;">
                <div style="width:100%; max-width:340px; aspect-ratio:9/16; max-height:560px; border-radius:14px; overflow:hidden; background:#000; border:1px solid #e2e8f0; box-shadow:0 4px 16px rgba(0,0,0,0.06);">
                    <iframe src="https://www.tiktok.com/player/v1/{{ $ttId }}?music_info=1&description=1"
                        style="width:100%; height:100%; border:none; display:block;"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
        @elseif(str_contains($videoUrl, 'tiktok.com'))
            {{-- TikTok Shortlink / Embed Script Fallback --}}
            <div style="display:flex; justify-content:center; width:100%;">
                <div style="width:100%; max-width:340px;">
                    <blockquote class="tiktok-embed" cite="{{ $videoUrl }}" style="max-width:100%; min-width:280px; margin:0 auto; border-radius:14px;">
                        <section><a target="_blank" href="{{ $videoUrl }}">Tonton Video di TikTok</a></section>
                    </blockquote>
                    <script async src="https://www.tiktok.com/embed.js"></script>
                </div>
            </div>
        @elseif($isMp4)
            {{-- Direct HTML5 Video --}}
            <div style="width:100%; aspect-ratio:16/9; border-radius:14px; overflow:hidden; background:#000; border:1px solid #e2e8f0; box-shadow:0 4px 16px rgba(0,0,0,0.06);">
                <video controls playsinline preload="metadata" style="width:100%; height:100%; object-fit:contain; display:block;">
                    <source src="{{ $videoUrl }}">
                    Browser Anda tidak mendukung tag video.
                </video>
            </div>
        @else
            {{-- Clean Card Fallback for other video links --}}
            <a href="{{ $videoUrl }}" target="_blank" rel="noopener noreferrer"
                style="display:flex; align-items:center; gap:0.85rem; padding:1rem 1.25rem; border-radius:14px; background:#f8fafc; border:1.5px solid #e2e8f0; text-decoration:none; color:#0f172a; transition:all 0.2s;"
                onmouseover="this.style.borderColor='#1eb349';this.style.background='#f0fdf4';"
                onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';">
                <div style="width:40px; height:40px; border-radius:10px; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:0.88rem; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Tonton Video Produk</div>
                    <div style="font-size:0.75rem; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $videoUrl }}</div>
                </div>
                <svg width="18" height="18" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/></svg>
            </a>
        @endif
    </div>
@endif
