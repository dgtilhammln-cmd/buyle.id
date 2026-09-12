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
    {{-- Minimalist Interstitial Pop-up Modal --}}
    <div id="adsenseInterstitialModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.65); z-index:999999; backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:1.25rem;">
        <div style="background:#ffffff; max-width:380px; width:100%; border-radius:20px; padding:1.5rem; text-align:center; box-shadow:0 20px 40px -10px rgba(0,0,0,0.15); position:relative; overflow:hidden; border:1px solid #E2E8F0; font-family:'Montserrat', sans-serif;">
            
            {{-- Minimal Header Badge --}}
            <div style="font-size:0.7rem; font-weight:700; color:#94A3B8; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:1rem; display:flex; align-items:center; justify-content:center; gap:0.35rem;">
                <span style="display:inline-block; width:6px; height:6px; background:#1eb349; border-radius:50%;"></span> Sponsor / Iklan
            </div>

            {{-- Clean Minimal Ad Box Container --}}
            <div style="min-height:180px; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:14px; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:center; overflow:hidden; position:relative; padding:0.5rem;">
                @if(!empty($pubId))
                    <ins class="adsbygoogle"
                         style="display:block; width:100%; height:180px;"
                         data-ad-client="{{ $pubId }}"
                         data-ad-slot="auto"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                    <script>
                         (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                @else
                    <div style="font-size:0.8rem; color:#94A3B8; font-weight:600;">
                        Iklan Google AdSense
                    </div>
                @endif
            </div>

            {{-- Minimal Timer Text --}}
            <p style="font-size:0.82rem; color:#475569; font-weight:600; margin-bottom:1.15rem;" id="adsenseTimerText">
                Mengarahkan ke link dalam <strong style="color:#1eb349; font-weight:800; font-size:0.95rem;" id="adsenseCountdown">{{ $interstitialDelay }}</strong> detik...
            </p>

            {{-- Button Header Style ("Daftar" Button Style) --}}
            <a href="#" id="adsenseContinueBtn" style="display:inline-flex; align-items:center; justify-content:center; gap:0.5rem; width:100%; padding:0.65rem 1.5rem; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#ffffff; font-family:'Montserrat', sans-serif; font-weight:700; font-size:0.85rem; border-radius:999px; text-decoration:none; transition:transform 0.2s, box-shadow 0.2s; box-shadow:0 4px 14px rgba(30,179,73,0.35); border:none; cursor:pointer;" onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(30,179,73,0.45)'" onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 14px rgba(30,179,73,0.35)'">
                <span>Lanjutkan ke Link</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
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
