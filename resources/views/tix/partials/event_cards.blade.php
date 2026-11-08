@foreach($events as $tProd)
    @php
        $tIsExpired = isset($is_past) && $is_past;
        $tPrice = $tProd->sale_price > 0 && $tProd->sale_price < $tProd->price ? $tProd->sale_price : $tProd->effective_price;
        $tOrigPrice = $tProd->sale_price > 0 && $tProd->sale_price < $tProd->price ? $tProd->price : null;
        $tHasDiscount = !empty($tOrigPrice) && $tOrigPrice > $tPrice;
        $tDiscountPct = $tHasDiscount ? round((($tOrigPrice - $tPrice) / $tOrigPrice) * 100) : 0;

        // Poster Event (Landscape 16:9), fallback ke gambar utama
        $tImage = !empty($tProd->featured_image) ? asset('storage/' . $tProd->featured_image) : (!empty($tProd->og_image) ? asset('storage/' . $tProd->og_image) : ($tProd->image ? asset('storage/' . $tProd->image) : asset('assets/images/default-ticket.webp')));

        // Deteksi event_date
        $tRawEventDate = $tProd->event_date ?: (!empty($tProd->data_json['event_date']) ? $tProd->data_json['event_date'] : null);
        if ($tRawEventDate) {
            $tDateCarbon = \Carbon\Carbon::parse($tRawEventDate)->locale('id');
            $tDate = $tDateCarbon->isoFormat('D MMMM Y');
            if (!isset($is_past)) {
                $tIsExpired = $tDateCarbon->endOfDay()->isPast();
            }
        } else {
            $tDate = $tIsExpired ? 'Event Selesai' : 'Segera Hadir';
        }

        $tLocation = $tProd->event_location
            ?: ($tProd->location
                ?: ($tProd->data_json['event_location'] ?? null)
                ?: ($tProd->seller?->creatorProfile?->city
                    ?: ($tProd->seller?->creatorProfile?->store_location
                        ?: ($tProd->event_type === 'online' ? 'Online / Webinar' : 'Indonesia'))));

        $tSellerCp = $tProd->seller?->creatorProfile;
        $tIsVerified = $tSellerCp ? (bool) $tSellerCp->is_verified : false;
        $tRatingVal = method_exists($tProd, 'getEffectiveRating') ? $tProd->getEffectiveRating() : ($tProd->rating ?? null);
        $tOrganizer = $tProd->seller ? ($tSellerCp->display_name ?? ($tSellerCp->store_name ?? $tProd->seller->name)) : 'Organizer';

        // Resolve Creator / Organizer Logo
        $sellerAvatar = $tProd->seller?->avatar 
            ?: ($tSellerCp?->avatar 
                ?: ($tSellerCp?->pos_logo ?: null));
        $sellerAvatarUrl = $sellerAvatar ? asset('storage/' . $sellerAvatar) : null;
    @endphp

    <a href="{{ route('products.show', $tProd->slug) }}"
        style="text-decoration: none; color: inherit; display: flex; flex-direction: column;"
        class="ticket-card-swipe-item">
        <div style="background: {{ $tIsExpired ? '#F8FAFC' : '#ffffff' }}; border: 1.5px solid {{ $tIsExpired ? '#CBD5E1' : '#E2E8F0' }}; border-radius: 16px; padding: 0.75rem; display: flex; flex-direction: column; height: 100%; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.02); {{ $tIsExpired ? 'opacity:0.72;' : '' }}"
            class="article-card-box">

            {{-- Image Banner 16:9 Landscape (Poster Event) --}}
            <div style="position: relative; width: 100%; aspect-ratio: 16/9; border-radius: 12px; overflow: hidden; background: #F1F5F9;">
                <img src="{{ $tImage }}" alt="{{ $tProd->name }}"
                    style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease; {{ $tIsExpired ? 'filter:grayscale(100%);' : '' }}"
                    class="article-banner-img" loading="lazy">

                {{-- Expired overlay (hanya ticket yang sudah berlalu) --}}
                @if($tIsExpired)
                    <div style="position:absolute;inset:0;background:rgba(15,23,42,0.38);display:flex;align-items:center;justify-content:center;z-index:3;">
                        <span style="background:rgba(20,20,30,0.85);color:#94A3B8;font-size:0.7rem;font-weight:800;padding:0.28rem 0.75rem;border-radius:99px;font-family:'Montserrat',sans-serif;letter-spacing:0.08em;border:1px solid rgba(148,163,184,0.25);">&#127937; EVENT SELESAI</span>
                    </div>
                @else
                    {{-- Discount / Event Badge Top-Left --}}
                    @if($tHasDiscount)
                        <div style="position: absolute; top: 8px; left: 8px; background: #EF4444; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 8px; font-family: 'Montserrat', sans-serif; z-index: 2; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);">
                            -{{ $tDiscountPct }}%
                        </div>
                    @else
                        <div style="position: absolute; top: 8px; left: 8px; background: #1eb349; color: #ffffff; font-size: 0.65rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 6px; font-family: 'Montserrat', sans-serif; z-index: 2;">
                            EVENT
                        </div>
                    @endif
                @endif
            </div>

            {{-- Card Body --}}
            <div style="padding: 0.85rem 0.25rem 0.25rem; display: flex; flex-direction: column; flex: 1; justify-content: space-between;">
                <div>
                    {{-- Rating & Verified Badge --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; font-weight: 700; color: #1E293B; margin-bottom: 0.35rem;">
                        @if($tRatingVal !== null && $tRatingVal > 0)
                            <div style="display: flex; align-items: center; gap: 0.3rem;">
                                <span style="color: {{ $tIsExpired ? '#94A3B8' : '#F59E0B' }}; font-size: 0.95rem;">★</span>
                                <span style="font-family: 'Montserrat', sans-serif; color: {{ $tIsExpired ? '#94A3B8' : '#1E293B' }};">{{ number_format($tRatingVal, 1) }}</span>
                            </div>
                        @else
                            <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #94A3B8; font-weight: 600;">
                                <span style="color: #CBD5E1; font-size: 0.85rem;">★</span>
                                <span>0.</span>
                            </div>
                        @endif

                        @if($tIsVerified && !$tIsExpired)
                            <div style="display: inline-flex; align-items: center; gap: 0.25rem; color: #0D9488; font-size: 0.78rem; font-weight: 700;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" />
                                </svg>
                                Verified
                            </div>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h3 style="font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 800; color: {{ $tIsExpired ? '#94A3B8' : '#0F172A' }}; margin: 0 0 0.35rem; line-height: 1.35; height: 2.7em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{ $tProd->name }}
                    </h3>

                    {{-- Creator / Organizer Logo & Name --}}
                    @if($tOrganizer)
                        <div style="font-size: 0.74rem; font-weight: 600; color: #475569; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.35rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="Penyelenggara: {{ $tOrganizer }}">
                            @if($sellerAvatarUrl)
                                <img src="{{ $sellerAvatarUrl }}" alt="{{ $tOrganizer }}" style="width: 18px; height: 18px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 1px solid #E2E8F0;">
                            @else
                                <span style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; background: #DCFCE7; color: #16A34A; font-size: 0.65rem; font-weight: 800; flex-shrink: 0;">
                                    {{ strtoupper(substr($tOrganizer, 0, 1)) }}
                                </span>
                            @endif
                            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $tOrganizer }}</span>
                        </div>
                    @endif

                    {{-- Tanggal & Lokasi Event --}}
                    <div style="display: flex; flex-direction: column; gap: 0.2rem; margin: 0.35rem 0 0.4rem; font-size: 0.72rem; color: {{ $tIsExpired ? '#94A3B8' : '#64748B' }}; font-weight: 600;">
                        <div style="display: inline-flex; align-items: center; gap: 0.3rem;">
                            <svg width="12" height="12" fill="none" stroke="{{ $tIsExpired ? '#94A3B8' : '#1eb349' }}" stroke-width="2.2" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            <span>{{ $tDate }}</span>
                        </div>
                        <div style="display: inline-flex; align-items: center; gap: 0.3rem;">
                            <svg width="12" height="12" fill="none" stroke="{{ $tIsExpired ? '#94A3B8' : '#EF4444' }}" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($tLocation, 25) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer Separator & Price CTA --}}
                <div>
                    <div style="border-top: 1px solid #F1F5F9; margin: 0.5rem 0 0.65rem;"></div>
                    <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 0.35rem;">
                        <div style="white-space: nowrap; min-width: 0; flex: 1;">
                            @if($tHasDiscount && !$tIsExpired)
                                <span style="font-size: 0.68rem; color: #94A3B8; text-decoration: line-through; display: block; font-weight: 500; white-space: nowrap;">
                                    Rp{{ number_format($tOrigPrice, 0, ',', '.') }}
                                </span>
                            @endif
                            <span style="font-family: 'Montserrat', sans-serif; font-size: 0.92rem; font-weight: 800; color: {{ $tIsExpired ? '#94A3B8' : '#16a34a' }}; white-space: nowrap; display: block;">
                                @if($tIsExpired)
                                    Selesai
                                @elseif($tPrice > 0)
                                    Rp{{ number_format($tPrice, 0, ',', '.') }}
                                @else
                                    <span style="color: #1eb349;">GRATIS</span>
                                @endif
                            </span>
                        </div>

                        @if($tIsExpired)
                            <span style="background:#E2E8F0; color:#94A3B8; padding:0.35rem 0.75rem; border-radius:99px; font-size:0.75rem; font-weight:700; display:inline-flex; align-items:center; flex-shrink:0; white-space:nowrap; pointer-events:none; cursor:not-allowed;">
                                Selesai
                            </span>
                        @else
                            <span style="background: linear-gradient(135deg, #1eb349, #7db928); color: #ffffff; padding: 0.35rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem; box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3); transition: all 0.2s; flex-shrink: 0; white-space: nowrap;">
                                Lihat <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7l7 7-7 7" /></svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </a>
@endforeach
