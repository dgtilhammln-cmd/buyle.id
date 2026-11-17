{{-- Footer Buyle Badge with Anti-Lepas Referral Tracker --}}
@php
    $referralParam = strtolower($username ?? ($profile->store_slug ?? ''));
    $footerReferralUrl = url('/?ref=' . urlencode($referralParam) . '&utm_source=bio_badge&utm_medium=' . urlencode($referralParam));
    $footerLogo = \App\Models\Setting::get('logo');
@endphp

<div class="footer-bio">
    <a href="{{ $footerReferralUrl }}" target="_blank" rel="noopener" onclick="trackBuyleBadge('{{ $referralParam }}')" title="Bikin Halaman Seperti Ini di buyle.id">
        @if($footerLogo)
            <img src="{{ asset('storage/' . $footerLogo) }}" alt="buyle.id"
                style="height:30px; width:auto; object-fit:contain;">
        @else
            <span style="font-weight:600; color:var(--text);">buyle.id</span>
        @endif
    </a>
</div>

<script>
function trackBuyleBadge(ref) {
    try {
        if (ref) {
            document.cookie = "buyle_referral=" + encodeURIComponent(ref) + "; path=/; max-age=" + (30 * 86400) + "; SameSite=Lax";
            localStorage.setItem('buyle_referral', ref);
            sessionStorage.setItem('buyle_referral', ref);
        }
    } catch (e) {}
}
(function() {
    try {
        var bioRef = "{{ $referralParam }}";
        if (bioRef) {
            document.cookie = "buyle_referral=" + encodeURIComponent(bioRef) + "; path=/; max-age=" + (30 * 86400) + "; SameSite=Lax";
            localStorage.setItem('buyle_referral', bioRef);
        }
    } catch (e) {}
})();
</script>
