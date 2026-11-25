{{-- Footer Buyle Badge with Anti-Lepas Referral Tracker & Official Media Partner Attribution --}}
@php
    $referralParam = strtolower($username ?? ($profile->store_slug ?? ''));
    $footerReferralUrl = url('/?ref=' . urlencode($referralParam) . '&utm_source=bio_badge&utm_medium=' . urlencode($referralParam));
    $footerLogo = \App\Models\Setting::get('logo');

    // Resolusi Media Partner:
    // 1. Cek apakah user ini adalah binaan Media Partner (media_partner_id pada user)
    // 2. ATAU user ini sendiri terdaftar sebagai entitas Media Partner (user_id pada media_partners)
    // 3. ATAU cari via kode/slug/username
    $mediaPartner = $profile->user?->mediaPartner;
    if (!$mediaPartner && $profile->user_id) {
        $mediaPartner = \App\Models\MediaPartner::where('user_id', $profile->user_id)->first();
    }
    if (!$mediaPartner && !empty($profile->store_slug)) {
        $mediaPartner = \App\Models\MediaPartner::where('code', $profile->store_slug)->first();
    }

    $hasMediaPartnerBadge = $mediaPartner 
        && $mediaPartner->is_active 
        && ($mediaPartner->show_on_bio ?? true) 
        && !empty($mediaPartner->badge_image);

    $mediaPartnerUrl = null;
    if ($hasMediaPartnerBadge) {
        $baseUrl = !empty($mediaPartner->target_url) 
            ? $mediaPartner->target_url 
            : url('/?ref=' . urlencode($mediaPartner->code));
            
        // Sisipkan parameter UTM tracking per Media Partner & Creator
        $separator = str_contains($baseUrl, '?') ? '&' : '?';
        $utmParams = http_build_query([
            'utm_source'   => 'buyle',
            'utm_medium'   => 'media_partner_bio',
            'utm_campaign' => $mediaPartner->code ?: 'mediapartner',
            'utm_content'  => $referralParam ?: 'creator',
        ]);
        $mediaPartnerUrl = $baseUrl . $separator . $utmParams;
    }
@endphp

@if($hasMediaPartnerBadge)
{{-- Official Media Partner Banner/Badge Container (Full Responsive) --}}
<div class="media-partner-footer-badge" style="text-align:center; margin-top:2rem; margin-bottom:1.25rem; width:100%; box-sizing:border-box; position:relative; z-index:10;">
    <div style="font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:rgba(255,255,255,0.45); margin-bottom:0.5rem; font-family:'Montserrat', sans-serif;">
        Official Media Partner
    </div>
    <a href="{{ $mediaPartnerUrl }}" target="_blank" rel="noopener" style="display:block; width:100%; text-decoration:none; transition:transform 0.2s, opacity 0.2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.opacity='0.95'" onmouseout="this.style.transform='none';this.style.opacity='1'" title="{{ $mediaPartner->name }}">
        <img src="{{ asset('storage/' . $mediaPartner->badge_image) }}" alt="{{ $mediaPartner->name }}" style="width:100%; max-width:100%; height:auto; display:block; margin:0 auto; border-radius:14px; box-shadow:0 6px 20px rgba(0,0,0,0.15); object-fit:cover;">
    </a>
</div>
@endif

<div class="footer-bio" style="{{ $hasMediaPartnerBadge ? 'margin-top: 0.5rem;' : '' }}">
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
