@php
    $adsenseEnabled = \App\Models\Setting::get('adsense_status', 'enabled') === 'enabled';
    $pubId = \App\Models\Setting::get('adsense_publisher_id', 'ca-pub-8031682064726767');
    $scriptCode = \App\Models\Setting::get('adsense_script_code', '');
    $customImage = \App\Models\Setting::get('adsense_custom_image', '');
    $customUrl = \App\Models\Setting::get('adsense_custom_url', '');
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
    {{-- Flexible Interstitial Pop-up Modal --}}
    <div id="adsenseInterstitialModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.7); z-index:999999; backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:1rem;">
        <div style="background:#ffffff; max-width:440px; width:92%; max-height:90vh; overflow-y:auto; border-radius:20px; padding:1.25rem; text-align:center; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); position:relative; border:1px solid #E2E8F0; font-family:'Montserrat', sans-serif;">
            
            {{-- Minimal Header Badge --}}
            <div style="font-size:0.7rem; font-weight:700; color:#94A3B8; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:0.75rem; display:flex; align-items:center; justify-content:center; gap:0.35rem;">
                <span style="display:inline-block; width:6px; height:6px; background:#1eb349; border-radius:50%;"></span> Sponsor / Iklan
            </div>

            {{-- Flexible Ad Box Container (Adapts naturally to image aspect ratio) --}}
            <div style="width:100%; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:14px; margin-bottom:1rem; display:flex; align-items:center; justify-content:center; overflow:hidden; position:relative; padding:0;">
                @if(!empty($customImage))
                    <a href="{{ $customUrl ?: '#' }}" target="_blank" style="display:block; width:100%; text-decoration:none;">
                        <img src="{{ asset('storage/' . $customImage) }}" alt="Sponsor" style="width:100%; height:auto; display:block; border-radius:12px; margin:0 auto;">
                    </a>
                @elseif(!empty($pubId))
                    <div style="width:100%; padding:0.5rem; min-height:180px; display:flex; align-items:center; justify-content:center;">
                        <ins class="adsbygoogle"
                             style="display:block; width:100%; height:180px;"
                             data-ad-client="{{ $pubId }}"
                             data-ad-slot="auto"
                             data-ad-format="auto"
                             data-full-width-responsive="true"></ins>
                        <script>
                             (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                @else
                    <div style="font-size:0.8rem; color:#94A3B8; font-weight:600; padding:2rem 1rem;">
                        Iklan Google AdSense
                    </div>
                @endif
            </div>

            {{-- Minimal Timer Text --}}
            <p style="font-size:0.82rem; color:#475569; font-weight:600; margin-bottom:1rem;" id="adsenseTimerText">
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
