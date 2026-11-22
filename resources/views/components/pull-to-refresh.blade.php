{{-- ── High-Performance Native-Like Pull-to-Refresh (iOS & Android Compatible) ── --}}
<div id="buyle-ptr" class="buyle-ptr" aria-hidden="true">
    <div class="buyle-ptr-pill" id="buyle-ptr-pill">
        <svg class="buyle-ptr-arrow" id="buyle-ptr-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <polyline points="19 12 12 19 5 12"></polyline>
        </svg>
        <svg class="buyle-ptr-spinner" id="buyle-ptr-spinner" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
            <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
        </svg>
    </div>
</div>

<style>
    .buyle-ptr {
        position: fixed;
        top: max(8px, env(safe-area-inset-top, 8px));
        left: 50%;
        transform: translate3d(-50%, -90px, 0);
        z-index: 999999;
        pointer-events: none;
        will-change: transform, opacity;
        opacity: 0;
    }

    .buyle-ptr-pill {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #ffffff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.12), 0 2px 6px rgba(30, 179, 73, 0.16);
        border: 1px solid rgba(226, 232, 240, 0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1eb349;
        transition: background 0.2s cubic-bezier(0.16, 1, 0.3, 1), color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .buyle-ptr.ready .buyle-ptr-pill {
        background: linear-gradient(135deg, #1eb349 0%, #16a34a 100%);
        color: #ffffff;
        border-color: #1eb349;
        box-shadow: 0 6px 22px rgba(30, 179, 73, 0.42);
        transform: scale(1.08);
    }

    .buyle-ptr-arrow {
        display: block;
        transition: transform 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .buyle-ptr-spinner {
        display: none;
        animation: buylePtrSpin 0.75s linear infinite;
    }

    .buyle-ptr.refreshing .buyle-ptr-arrow {
        display: none;
    }

    .buyle-ptr.refreshing .buyle-ptr-spinner {
        display: block;
    }

    @keyframes buylePtrSpin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
(function () {
    // Only run on touch-capable devices
    if (!('ontouchstart' in window) && navigator.maxTouchPoints <= 0) return;

    var ptr = document.getElementById('buyle-ptr');
    var arrow = document.getElementById('buyle-ptr-arrow');
    if (!ptr || !arrow) return;

    var startY = 0;
    var startX = 0;
    var pullDist = 0;
    var isPulling = false;
    var isReady = false;
    var isRefreshing = false;
    var hasHaptic = false;

    var THRESHOLD = 65; // Distance to trigger refresh
    var MAX_DIST = 95;  // Cap on pull distance

    function getScrollTop() {
        return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || (document.scrollingElement ? document.scrollingElement.scrollTop : 0) || 0;
    }

    function isInsideScrolledElement(target) {
        var el = target;
        while (el && el !== document.body && el !== document.documentElement) {
            var overflowY = window.getComputedStyle(el).overflowY;
            if ((overflowY === 'auto' || overflowY === 'scroll') && el.scrollTop > 0) {
                return true;
            }
            el = el.parentElement;
        }
        return false;
    }

    document.addEventListener('touchstart', function (e) {
        if (isRefreshing) return;
        if (getScrollTop() > 2) return;
        if (isInsideScrolledElement(e.target)) return;

        startY = e.touches[0].clientY;
        startX = e.touches[0].clientX;
        pullDist = 0;
        isPulling = false;
        isReady = false;
        hasHaptic = false;
    }, { passive: true });

    document.addEventListener('touchmove', function (e) {
        if (isRefreshing) return;
        if (getScrollTop() > 2 && !isPulling) return;

        var currentY = e.touches[0].clientY;
        var currentX = e.touches[0].clientX;
        var diffY = currentY - startY;
        var diffX = currentX - startX;

        // If user is swiping horizontally, cancel PTR immediately
        if (!isPulling && Math.abs(diffX) > Math.abs(diffY)) {
            return;
        }

        if (diffY > 0 && getScrollTop() <= 2) {
            // Cancel native scroll/bounce on iOS
            if (e.cancelable) {
                e.preventDefault();
            }

            isPulling = true;
            ptr.style.transition = 'none';

            // Damping calculation for natural elasticity
            pullDist = Math.min(diffY * 0.44, MAX_DIST);

            ptr.style.transform = 'translate3d(-50%, ' + pullDist + 'px, 0)';
            ptr.style.opacity = Math.min(pullDist / 24, 1);

            var progress = Math.min(pullDist / THRESHOLD, 1);
            // Rotate arrow based on pull depth (0 to 180deg)
            arrow.style.transform = 'rotate(' + (progress * 180) + 'deg)';

            if (pullDist >= THRESHOLD) {
                if (!isReady) {
                    isReady = true;
                    ptr.classList.add('ready');
                    if (!hasHaptic && navigator.vibrate) {
                        try { navigator.vibrate(18); } catch (err) {}
                        hasHaptic = true;
                    }
                }
            } else {
                if (isReady) {
                    isReady = false;
                    ptr.classList.remove('ready');
                    hasHaptic = false;
                }
            }
        } else if (isPulling && diffY <= 0) {
            reset();
        }
    }, { passive: false });

    function onEnd() {
        if (!isPulling) return;
        isPulling = false;

        if (isReady && !isRefreshing) {
            isRefreshing = true;
            ptr.classList.add('refreshing');
            ptr.style.transition = 'transform 0.22s cubic-bezier(0.16, 1, 0.3, 1)';
            ptr.style.transform = 'translate3d(-50%, 46px, 0)';

            // Smooth delay to appreciate the refresh feedback before reload
            setTimeout(function () {
                window.location.reload();
            }, 550);
        } else {
            reset();
        }
    }

    document.addEventListener('touchend', onEnd, { passive: true });
    document.addEventListener('touchcancel', reset, { passive: true });

    function reset() {
        isPulling = false;
        isReady = false;
        hasHaptic = false;
        ptr.classList.remove('ready', 'refreshing');
        ptr.style.transition = 'transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.22s ease';
        ptr.style.transform = 'translate3d(-50%, -90px, 0)';
        ptr.style.opacity = '0';
        arrow.style.transform = 'rotate(0deg)';
    }
})();
</script>
