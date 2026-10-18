@php
    $count = $count ?? 4;
@endphp
@for($i = 0; $i < $count; $i++)
    <div class="cv-skel-card-wrapper" style="display: flex; flex-direction: column;">
        <div style="background: #ffffff; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.75rem; display: flex; flex-direction: column; height: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.02); box-sizing: border-box;">
            
            {{-- Shimmer Image 1:1 Square matching article-banner-img --}}
            <div class="cv-skel-shimmer" style="position: relative; width: 100%; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; background: #e2e8f0;"></div>

            {{-- Body --}}
            <div style="padding: 0.85rem 0.25rem 0.25rem; display: flex; flex-direction: column; flex: 1; justify-content: space-between;">
                <div>
                    {{-- Rating & Verified Meta Row --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                        <div class="cv-skel-shimmer" style="width: 48px; height: 13px; border-radius: 4px;"></div>
                        <div class="cv-skel-shimmer" style="width: 56px; height: 13px; border-radius: 4px;"></div>
                    </div>
                    {{-- Title 2 Lines --}}
                    <div class="cv-skel-shimmer" style="width: 90%; height: 14px; border-radius: 4px; margin-bottom: 0.45rem;"></div>
                    <div class="cv-skel-shimmer" style="width: 62%; height: 14px; border-radius: 4px;"></div>
                </div>

                {{-- Footer Separator & Price CTA --}}
                <div style="margin-top: 0.85rem;">
                    <div style="border-top: 1px solid #F1F5F9; margin: 0.65rem 0 0.75rem;"></div>
                    <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 0.35rem;">
                        <div class="cv-skel-shimmer" style="width: 72px; height: 18px; border-radius: 4px;"></div>
                        <div class="cv-skel-shimmer" style="width: 58px; height: 26px; border-radius: 99px;"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endfor
