{{--
    Share Modal Partial — Bio Link Themes (1-4)
    Usage: @include('partials.share_modal')
    Requires: $canonical (page URL), $config['name'] or $username variable
--}}
@php
    $shareUrl  = $canonical ?? url()->current();
    $shareName = $config['name'] ?? ($profile->store_name ?? ($username ?? 'Profile'));
    $shareText = urlencode('Lihat profile ' . $shareName . ' di buyle.id');
    $shareUrlEncoded = urlencode($shareUrl);
@endphp

{{-- Share Button styles --}}
<style>
#bio-share-btn {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 900;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1.5px solid rgba(255,255,255,0.28);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}
/* Desktop: letakkan tombol share sejajar tepi kanan konten bio */
@media (min-width: 640px) {
    #bio-share-btn {
        right: calc(50% - 260px - 50px);
    }
}
</style>

{{-- Share Button (fixed) --}}
<button id="bio-share-btn" onclick="openShareSheet()" aria-label="Bagikan profil">
    <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24">
        <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
    </svg>
</button>

{{-- Overlay --}}
<div id="share-overlay" onclick="closeShareSheet()"
    style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,0.45);
           backdrop-filter:blur(3px);transition:opacity 0.3s;opacity:0;"></div>

{{-- Bottom Sheet --}}
<div id="share-sheet"
    style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:1001;background:#fff;
           border-radius:24px 24px 0 0;padding:0 1.25rem 2rem;max-width:520px;margin:0 auto;
           box-shadow:0 -8px 40px rgba(0,0,0,0.18);transform:translateY(100%);
           transition:transform 0.35s cubic-bezier(0.32,0.72,0,1);">

    {{-- Handle bar --}}
    <div style="display:flex;justify-content:center;padding:0.85rem 0 0.5rem;">
        <div style="width:40px;height:4px;border-radius:99px;background:#e2e8f0;"></div>
    </div>

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <h3 style="font-family:'Montserrat',sans-serif;font-size:1rem;font-weight:700;color:#0f172a;margin:0;">
            Bagikan Profil
        </h3>
        <button onclick="closeShareSheet()"
            style="background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;padding:4px;">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>

    {{-- QR Code --}}
    <div style="text-align:center;margin-bottom:1.25rem;">
        <div style="display:inline-block;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:16px;padding:1rem;">
            <div id="share-qr-box"></div>
            <p style="font-family:'Montserrat',sans-serif;font-size:0.68rem;color:#94a3b8;margin:0.5rem 0 0;font-weight:600;">
                Scan QR untuk buka profil
            </p>
        </div>
    </div>

    {{-- Copy Link --}}
    <div style="display:flex;gap:0.6rem;align-items:center;margin-bottom:1.25rem;">
        <div style="flex:1;background:#f1f5f9;border-radius:10px;padding:0.65rem 0.9rem;
                    font-family:'Montserrat',sans-serif;font-size:0.75rem;color:#475569;
                    overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:500;">
            {{ $shareUrl }}
        </div>
        <button id="share-copy-btn" onclick="copyShareLink()"
            style="flex-shrink:0;background:#1eb349;color:#fff;border:none;border-radius:10px;
                   padding:0.65rem 1rem;font-family:'Montserrat',sans-serif;font-size:0.78rem;
                   font-weight:700;cursor:pointer;display:flex;align-items:center;gap:0.35rem;
                   transition:background 0.2s;white-space:nowrap;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <rect x="9" y="9" width="13" height="13" rx="2"/>
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
            </svg>
            Salin
        </button>
    </div>

    {{-- Social Share --}}
    <p style="font-family:'Montserrat',sans-serif;font-size:0.72rem;font-weight:700;color:#94a3b8;
              text-transform:uppercase;letter-spacing:0.07em;margin:0 0 0.85rem;">Bagikan ke</p>
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:0.65rem;">

        {{-- WhatsApp --}}
        <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrlEncoded }}" target="_blank" rel="noopener"
           style="text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:0.4rem;">
            <div style="width:48px;height:48px;border-radius:14px;background:#25d366;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="#fff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
            </div>
            <span style="font-family:'Montserrat',sans-serif;font-size:0.62rem;font-weight:600;color:#475569;">WhatsApp</span>
        </a>

        {{-- Telegram --}}
        <a href="https://t.me/share/url?url={{ $shareUrlEncoded }}&text={{ $shareText }}" target="_blank" rel="noopener"
           style="text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:0.4rem;">
            <div style="width:48px;height:48px;border-radius:14px;background:#0088cc;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
            </div>
            <span style="font-family:'Montserrat',sans-serif;font-size:0.62rem;font-weight:600;color:#475569;">Telegram</span>
        </a>

        {{-- Twitter/X --}}
        <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrlEncoded }}" target="_blank" rel="noopener"
           style="text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:0.4rem;">
            <div style="width:48px;height:48px;border-radius:14px;background:#000;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="#fff"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.26 5.632L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z"/></svg>
            </div>
            <span style="font-family:'Montserrat',sans-serif;font-size:0.62rem;font-weight:600;color:#475569;">X (Twitter)</span>
        </a>

        {{-- Facebook --}}
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrlEncoded }}" target="_blank" rel="noopener"
           style="text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:0.4rem;">
            <div style="width:48px;height:48px;border-radius:14px;background:#1877f2;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </div>
            <span style="font-family:'Montserrat',sans-serif;font-size:0.62rem;font-weight:600;color:#475569;">Facebook</span>
        </a>

        {{-- LINE --}}
        <a href="https://social-plugins.line.me/lineit/share?url={{ $shareUrlEncoded }}" target="_blank" rel="noopener"
           style="text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:0.4rem;">
            <div style="width:48px;height:48px;border-radius:14px;background:#00b900;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff"><path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.281.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/></svg>
            </div>
            <span style="font-family:'Montserrat',sans-serif;font-size:0.62rem;font-weight:600;color:#475569;">LINE</span>
        </a>

    </div>
</div>

<script>
(function() {
    var _qrReady = false, _qrLoading = false, _qrDone = false;

    window.openShareSheet = function() {
        var overlay = document.getElementById('share-overlay');
        var sheet   = document.getElementById('share-sheet');
        if (!overlay || !sheet) return;
        overlay.style.display = 'block';
        sheet.style.display   = 'block';
        requestAnimationFrame(function() {
            overlay.style.opacity = '1';
            sheet.style.transform = 'translateY(0)';
        });
        loadQR();
    };

    window.closeShareSheet = function() {
        var overlay = document.getElementById('share-overlay');
        var sheet   = document.getElementById('share-sheet');
        if (!overlay || !sheet) return;
        overlay.style.opacity = '0';
        sheet.style.transform = 'translateY(100%)';
        setTimeout(function() {
            overlay.style.display = 'none';
            sheet.style.display   = 'none';
        }, 350);
    };

    function loadQR() {
        if (_qrDone) return;
        if (_qrReady) { genQR(); return; }
        if (_qrLoading) return;
        _qrLoading = true;
        var s = document.createElement('script');
        s.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
        s.onload = function() { _qrReady = true; genQR(); };
        s.onerror = function() { showQRFallback(); };
        document.head.appendChild(s);
    }

    function genQR() {
        if (_qrDone) return;
        _qrDone = true;
        var box = document.getElementById('share-qr-box');
        if (!box) return;
        box.innerHTML = '';
        try {
            new QRCode(box, {
                text: "{{ addslashes($shareUrl) }}",
                width: 160, height: 160,
                colorDark: '#0f172a',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
        } catch(e) { showQRFallback(); }
    }

    function showQRFallback() {
        var box = document.getElementById('share-qr-box');
        if (box) box.innerHTML = '<div style="width:160px;height:160px;display:flex;align-items:center;justify-content:center;font-size:0.7rem;color:#94a3b8;text-align:center;padding:1rem;">QR tidak tersedia</div>';
    }

    window.copyShareLink = function() {
        var url = "{{ addslashes($shareUrl) }}";
        var btn = document.getElementById('share-copy-btn');
        var done = function() {
            if (!btn) return;
            var orig = btn.innerHTML;
            btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Tersalin!';
            btn.style.background = '#16a34a';
            setTimeout(function() { btn.innerHTML = orig; btn.style.background = '#1eb349'; }, 2000);
        };
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(done).catch(function() { legacyCopy(url); done(); });
        } else { legacyCopy(url); done(); }
    };

    function legacyCopy(text) {
        var ta = document.createElement('textarea');
        ta.value = text; ta.style.cssText = 'position:fixed;top:-9999px;opacity:0;';
        document.body.appendChild(ta); ta.select();
        try { document.execCommand('copy'); } catch(e) {}
        document.body.removeChild(ta);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') window.closeShareSheet();
    });

    // Swipe down to close
    (function() {
        var startY = 0;
        var el = document.getElementById('share-sheet');
        if (!el) return;
        el.addEventListener('touchstart', function(e) { startY = e.touches[0].clientY; }, { passive: true });
        el.addEventListener('touchend', function(e) {
            if (e.changedTouches[0].clientY - startY > 80) window.closeShareSheet();
        }, { passive: true });
    })();
})();
</script>

