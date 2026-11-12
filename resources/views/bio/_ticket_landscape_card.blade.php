{{-- ============================================================
     _ticket_landscape_card.blade.php
     Landscape ticket card for Bio Theme 1-4
     Expects variables: $block, $prod, $prodUrl, $price, $origPrice, $displayTitle, $cardNum
     Theme color hints via CSS variables set by parent theme.
     ============================================================ --}}
@php
    // ── Image: prefer og_image (landscape poster 16:9), then featured_image, then image
    $tixPoster = null;
    if (!empty($prod->og_image)) {
        $tixPoster = Str::startsWith($prod->og_image, 'http') ? $prod->og_image : asset('storage/' . $prod->og_image);
    } elseif (!empty($prod->image)) {
        $tixPoster = Str::startsWith($prod->image, 'http') ? $prod->image : asset('storage/' . $prod->image);
    } elseif (!empty($block->data_json['images'][0])) {
        $rawImg = $block->data_json['images'][0];
        $tixPoster = Str::startsWith($rawImg, 'http') ? $rawImg : asset('storage/' . $rawImg);
    } elseif (!empty($block->data_json['image'])) {
        $rawImg = $block->data_json['image'];
        $tixPoster = Str::startsWith($rawImg, 'http') ? $rawImg : asset('storage/' . $rawImg);
    } else {
        $tixPoster = asset('assets/images/default-ticket.webp');
    }

    // ── Event Date
    $tixRawDate = null;
    if (!empty($prod->event_date)) $tixRawDate = $prod->event_date;
    elseif (!empty($block->data_json['event_date'])) $tixRawDate = $block->data_json['event_date'];
    if ($tixRawDate) {
        try {
            $tixDateCarbon = \Carbon\Carbon::parse($tixRawDate)->locale('id');
            $tixDate = $tixDateCarbon->isoFormat('ddd, D MMM Y');
            $tixTime = !empty($prod->event_time) ? substr($prod->event_time, 0, 5) . ' WIB' : null;
            $tixIsExpired = $tixDateCarbon->endOfDay()->isPast();
        } catch (\Exception $e) {
            $tixDate = null; $tixTime = null; $tixIsExpired = false;
        }
    } else {
        $tixDate = null; $tixTime = null; $tixIsExpired = false;
    }

    // ── Location
    $tixLocation = $prod->event_location
        ?? ($block->data_json['event_location'] ?? null)
        ?? ($prod->event_type === 'online' ? 'Online / Webinar' : null);

    // ── Discount
    $tixHasDiscount = !empty($origPrice) && $origPrice > $price;
    $tixDiscountPct = $tixHasDiscount ? round((($origPrice - $price) / $origPrice) * 100) : 0;
@endphp

<a href="{{ $prodUrl }}"
   class="ticket-lsc search-item bio-track-link"
   data-title="{{ $displayTitle }}"
   data-bio-block="{{ $block->id }}"
   data-bio-creator="{{ $profile->id }}">

    {{-- ── Image Section (16:9) with info overlay ── --}}
    <div class="ticket-lsc-img-wrap">

        <img src="{{ $tixPoster }}"
             alt="{{ $displayTitle }}"
             class="ticket-lsc-img"
             loading="lazy"
             onerror="this.src='https://placehold.co/640x360/111/444?text=Event'">

        {{-- Dark gradient overlay so text on image is readable --}}
        <div class="ticket-lsc-overlay"></div>

        {{-- Card number badge --}}
        <div class="ticket-lsc-num">{{ sprintf('%02d', $cardNum) }}</div>

        {{-- Discount / status badge --}}
        @if($tixIsExpired)
            <div class="ticket-lsc-badge ticket-lsc-badge--expired">EVENT SELESAI</div>
        @elseif($tixHasDiscount)
            <div class="ticket-lsc-badge ticket-lsc-badge--discount">-{{ $tixDiscountPct }}%</div>
        @else
            <div class="ticket-lsc-badge ticket-lsc-badge--event">EVENT</div>
        @endif

        {{-- Event type pill (online / offline) --}}
        @if(!empty($prod->event_type))
            <div class="ticket-lsc-type-pill">
                @if($prod->event_type === 'online')
                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10"/></svg>
                    Online
                @else
                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Offline
                @endif
            </div>
        @endif
    </div>

    {{-- ── Info Section ── --}}
    <div class="ticket-lsc-info">

        {{-- Title --}}
        <h3 class="ticket-lsc-title">{{ $displayTitle }}</h3>

        {{-- Date + Time --}}
        @if($tixDate)
            <div class="ticket-lsc-meta">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span>{{ $tixDate }}@if($tixTime) &bull; {{ $tixTime }}@endif</span>
            </div>
        @endif

        {{-- Location --}}
        @if($tixLocation)
            <div class="ticket-lsc-meta">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span class="ticket-lsc-loc">{{ Str::limit($tixLocation, 32) }}</span>
            </div>
        @endif

        {{-- Price row --}}
        <div class="ticket-lsc-price-row">
            <div class="ticket-lsc-price-wrap">
                @if($tixHasDiscount && !$tixIsExpired)
                    <span class="ticket-lsc-orig-price">Rp{{ number_format($origPrice, 0, ',', '.') }}</span>
                @endif
                <span class="ticket-lsc-price {{ $tixIsExpired ? 'ticket-lsc-price--expired' : '' }}">
                    @if($tixIsExpired)
                        Selesai
                    @elseif($price > 0)
                        Rp{{ number_format($price, 0, ',', '.') }}
                    @else
                        GRATIS
                    @endif
                </span>
            </div>
            <span class="ticket-lsc-cta {{ $tixIsExpired ? 'ticket-lsc-cta--expired' : '' }}">
                @if($tixIsExpired)
                    Selesai
                @else
                    Lihat
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-7-7l7 7-7 7"/></svg>
                @endif
            </span>
        </div>
    </div>
</a>

