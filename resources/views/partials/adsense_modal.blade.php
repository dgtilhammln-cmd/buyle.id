@php
    $adsenseEnabled = \App\Models\Setting::get('adsense_status', 'enabled') === 'enabled';
    $pubId = \App\Models\Setting::get('adsense_publisher_id', 'ca-pub-8031682064726767');
    $scriptCode = \App\Models\Setting::get('adsense_script_code', '');
    $interstitialEnabled = \App\Models\Setting::get('adsense_bio_interstitial_status', 'enabled') === 'enabled';
    $interstitialDelay = (int) \App\Models\Setting::get('adsense_interstitial_delay', 3);
@endphp

@if($adsenseEnabled)
    {{-- Google AdSense Code --}}
    @if(!empty($scriptCode))
        {!! $scriptCode !!}
    @elseif(!empty($pubId))
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $pubId }}" crossorigin="anonymous"></script>
    @endif

    @if($interstitialEnabled)
    {{-- Interstitial Pop-up Modal --}}
    <div id="adsenseInterstitialModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.85); z-index:999999; backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); align-items:center; justify-content:center; padding:1.25rem;">
        <div style="background:#ffffff; max-width:420px; width:100%; border-radius:24px; padding:1.75rem 1.5rem; text-align:center; box-shadow:0 25px 50px -12px rgba(0,0,0,0.5); position:relative; overflow:hidden; border:1px solid #E2E8F0; font-family:'Montserrat',sans-serif;">
            <div style="font-size:0.75rem; font-weight:800; color:#64748B; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:1rem; display:flex; align-items:center; justify-content:center; gap:0.4rem;">
                <span style="display:inline-block; width:8px; height:8px; background:#10B981; border-radius:50%; box-shadow:0 0 8px #10B981;"></span> Sponsor / Iklan
            </div>

            {{-- Ad Container --}}
            <div style="min-height:220px; background:#F8FAFC; border:1.5px dashed #CBD5E1; border-radius:16px; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:center; overflow:hidden; position:relative; padding:0.5rem;">
                @if(!empty($pubId))
                    <ins class="adsbygoogle"
                         style="display:block; width:100%; height:200px;"
                         data-ad-client="{{ $pubId }}"
                         data-ad-slot="auto"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                    <script>
                         (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                @else
                    <div style="font-size:0.85rem; color:#94A3B8; font-weight:600;">
                        <i class="fas fa-ad" style="font-size:2rem; margin-bottom:0.5rem; display:block; color:#CBD5E1;"></i>
                        Iklan Google AdSense
                    </div>
                @endif
            </div>

            <p style="font-size:0.85rem; color:#334155; font-weight:700; margin-bottom:1.25rem;" id="adsenseTimerText">
                Mengarahkan ke link dalam <strong style="color:#10B981; font-size:1.15rem;" id="adsenseCountdown">{{ $interstitialDelay }}</strong> detik...
            </p>

            <a href="#" id="adsenseContinueBtn" style="display:inline-flex; align-items:center; justify-content:center; gap:0.5rem; width:100%; padding:0.85rem; background:linear-gradient(135deg, #10B981, #059669); color:#ffffff; font-weight:800; font-size:0.9rem; border-radius:14px; text-decoration:none; transition:all 0.2s; box-shadow:0 4px 14px rgba(16,185,129,0.35);">
                <span>Lanjutkan ke Link</span>
                <i class="fas fa-arrow-right" style="font-size:0.85rem;"></i>
            </a>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('adsenseInterstitialModal');
        var countdownEl = document.getElementById('adsenseCountdown');
        var continueBtn = document.getElementById('adsenseContinueBtn');
        var pendingUrl = null;
        var pendingTarget = '_self';
        var timer = null;

        document.querySelectorAll('a.bio-track-link, a.btn-buy, .product-grid a, .cat-panel a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                var href = this.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;

                e.preventDefault();
                pendingUrl = href;
                pendingTarget = this.getAttribute('target') || '_self';

                modal.style.display = 'flex';
                var left = {{ $interstitialDelay }};
                countdownEl.textContent = left;

                if (timer) clearInterval(timer);
                timer = setInterval(function() {
                    left--;
                    if (left <= 0) {
                        clearInterval(timer);
                        navigateNow();
                    } else {
                        countdownEl.textContent = left;
                    }
                }, 1000);
            });
        });

        function navigateNow() {
            if (!pendingUrl) return;
            var targetUrl = pendingUrl;
            var targetWin = pendingTarget;
            pendingUrl = null;
            modal.style.display = 'none';

            if (targetWin === '_blank') {
                window.open(targetUrl, '_blank');
            } else {
                window.location.href = targetUrl;
            }
        }

        if (continueBtn) {
            continueBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (timer) clearInterval(timer);
                navigateNow();
            });
        }
    });
    </script>
    @endif
@endif
