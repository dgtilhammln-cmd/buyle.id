@if(isset($services) && $services->count() > 0)
    @foreach($services as $sv)
        @php
            $svImg        = $sv->image ? asset('storage/'.$sv->image) : asset('images/buyle-og.png');
            $svIsFree     = $sv->sale_price !== null && (float)$sv->sale_price <= 0;
            $svPrice      = $svIsFree ? 0 : (($sv->sale_price > 0 && $sv->sale_price < $sv->price) ? (float)$sv->sale_price : (float)$sv->price);
            $svOrigPrice  = $svIsFree ? $sv->price : (($sv->sale_price > 0 && $sv->sale_price < $sv->price) ? $sv->price : null);
            $svHasDiscount = !empty($svOrigPrice) && $svOrigPrice > $svPrice;
            $svDiscountPct = $svIsFree ? 100 : ($svHasDiscount ? round((($svOrigPrice - $svPrice) / $svOrigPrice) * 100) : 0);
            $svSellerCp   = $sv->seller?->creatorProfile;
            $svIsVerified = $svSellerCp ? (bool)$svSellerCp->is_verified : false;
            $svRatingVal  = $sv->getEffectiveRating();
        @endphp
        <a href="{{ route_locale('products.show', $sv->slug) }}"
            style="text-decoration: none; color: inherit; display: flex; flex-direction: column;"
            class="sp-card article-card-swipe-item cv-card-enter">
            <div style="background: #ffffff; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.75rem; display: flex; flex-direction: column; height: 100%; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.02);"
                class="article-card-box sp-card-inner">

                {{-- Image Wrap 1:1 Square --}}
                <div style="position: relative; width: 100%; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; background: #F1F5F9;" class="sp-card-img-wrap">
                    <img src="{{ $svImg }}" alt="{{ $sv->name }}"
                        style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease;"
                        class="article-banner-img" loading="lazy">

                    {{-- Discount / Free Badge Top-Left --}}
                    @if($svIsFree)
                        <div style="position: absolute; top: 8px; left: 8px; background: #16a34a; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 8px; font-family: 'Montserrat', sans-serif; z-index: 2; box-shadow: 0 2px 6px rgba(22,163,74,0.3);">
                            GRATIS
                        </div>
                    @elseif($svHasDiscount)
                        <div style="position: absolute; top: 8px; left: 8px; background: #EF4444; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 8px; font-family: 'Montserrat', sans-serif; z-index: 2; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);">
                            -{{ $svDiscountPct }}%
                        </div>
                    @endif
                </div>

                {{-- Card Body --}}
                <div style="padding: 0.85rem 0.25rem 0.25rem; display: flex; flex-direction: column; flex: 1; justify-content: space-between;" class="sp-card-body-wrap">
                    <div>
                        {{-- Rating & Verified Badge --}}
                        <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; font-weight: 700; color: #1E293B; margin-bottom: 0.35rem;">
                            @if($svRatingVal !== null && $svRatingVal > 0)
                                <div style="display: flex; align-items: center; gap: 0.3rem;">
                                    <span style="color: #F59E0B; font-size: 0.95rem;">★</span>
                                    <span style="font-family: 'Montserrat', sans-serif;">{{ number_format($svRatingVal, 1) }}</span>
                                </div>
                            @else
                                <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #94A3B8; font-weight: 600;">
                                    <span style="color: #CBD5E1; font-size: 0.85rem;">★</span>
                                    <span>0.0</span>
                                </div>
                            @endif
                            @if($svIsVerified)
                                <div style="display: inline-flex; align-items: center; gap: 0.25rem; color: #0D9488; font-size: 0.78rem; font-weight: 700;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>
                                    Verified
                                </div>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h3 style="font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 800; color: #0F172A; margin: 0 0 0.4rem; line-height: 1.35; height: 2.7em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            {{ $sv->name }}
                        </h3>
                    </div>

                    {{-- Footer Separator & Price CTA --}}
                    <div>
                        <div style="border-top: 1px solid #F1F5F9; margin: 0.65rem 0 0.75rem;"></div>
                        <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 0.35rem;">
                            <div style="white-space: nowrap; min-width: 0; flex: 1;">
                                @if($svHasDiscount || $svIsFree)
                                    <span style="font-size: 0.68rem; color: #94A3B8; text-decoration: line-through; display: block; font-weight: 500; white-space: nowrap;">
                                        Rp{{ number_format($svOrigPrice, 0, ',', '.') }}
                                    </span>
                                @endif
                                <span style="font-family: 'Montserrat', sans-serif; font-size: 0.92rem; font-weight: 800; color: #16a34a; white-space: nowrap; display: block;">
                                    @if($svIsFree)
                                        GRATIS
                                    @elseif($svPrice > 0)
                                        Rp{{ number_format($svPrice, 0, ',', '.') }}
                                    @else
                                        GRATIS
                                    @endif
                                </span>
                            </div>
                            <span style="background: linear-gradient(135deg, #1eb349, #7db928); color: #ffffff; padding: 0.35rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem; box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3); flex-shrink: 0; white-space: nowrap;" class="retarget-btn-cta">
                                Lihat <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7l7 7-7 7" /></svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
@endif
