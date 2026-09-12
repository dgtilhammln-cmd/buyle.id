import os, re

# -------------------------------------------------------------
# 1. Update resources/views/admin/settings/index.blade.php
# -------------------------------------------------------------
idx_path = 'resources/views/admin/settings/index.blade.php'
with open(idx_path, 'r', encoding='utf-8') as f:
    idx_content = f.read()

adsense_section_old = """    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
      <div>
        <label class="form-label">Status Google AdSense <span>(Aktifkan Iklan)</span></label>"""

adsense_section_new = """    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
      <div>
        <label class="form-label">Status Google AdSense <span>(Aktifkan Iklan)</span></label>"""

custom_banner_fields = """
      <div style="grid-column:1 / -1; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:1.25rem; margin-top:0.5rem;">
        <div style="font-size:0.8rem; font-weight:700; color:#1E293B; margin-bottom:0.75rem;">Upload Gambar Banner Custom (Opsional / Pengganti Iklan Google)</div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; align-items:center;">
          <div>
            @if(!empty($settings['adsense_custom_image']))
              <div style="margin-bottom:0.5rem;">
                <img src="{{ asset('storage/'.$settings['adsense_custom_image']) }}" alt="Custom Banner" style="max-height:90px; border-radius:8px; object-fit:contain; border:1px solid #E2E8F0;">
              </div>
            @endif
            <label class="form-label">Upload Gambar Banner Custom</label>
            <input type="file" name="adsense_custom_image" class="form-input" accept="image/*">
            <p style="font-size:0.75rem; color:#94A3B8; margin-top:0.25rem;">Upload gambar banner/promosi manual (PNG/JPG/WebP). Jika diisi, banner ini akan tampil di pop-up modal.</p>
          </div>
          <div>
            <label class="form-label">Link Target Banner Custom</label>
            <input type="url" name="adsense_custom_url" class="form-input" value="{{ old('adsense_custom_url', $settings['adsense_custom_url'] ?? '') }}" placeholder="https://domain.com/promo">
            <p style="font-size:0.75rem; color:#94A3B8; margin-top:0.25rem;">URL yang akan dibuka saat pengunjung mengeklik gambar banner custom.</p>
          </div>
        </div>
      </div>
"""

if "adsense_custom_image" not in idx_content:
    idx_content = idx_content.replace(
        "    <div style=\"margin-top:1.25rem;\">\n      <label class=\"form-label\">Script Custom AdSense",
        custom_banner_fields + "\n    <div style=\"margin-top:1.25rem;\">\n      <label class=\"form-label\">Script Custom AdSense"
    )
    with open(idx_path, 'w', encoding='utf-8') as f:
        f.write(idx_content)
    print("1. Updated admin settings index with custom banner fields.")

# -------------------------------------------------------------
# 2. Update resources/views/partials/adsense_modal.blade.php
# -------------------------------------------------------------
modal_path = 'resources/views/partials/adsense_modal.blade.php'
modal_code = """@php
    $adsenseEnabled = \\App\\Models\\Setting::get('adsense_status', 'enabled') === 'enabled';
    $pubId = \\App\\Models\\Setting::get('adsense_publisher_id', 'ca-pub-8031682064726767');
    $scriptCode = \\App\\Models\\Setting::get('adsense_script_code', '');
    $customImage = \\App\\Models\\Setting::get('adsense_custom_image', '');
    $customUrl = \\App\\Models\\Setting::get('adsense_custom_url', '');
    $interstitialEnabled = \\App\\Models\\Setting::get('adsense_bio_interstitial_status', 'enabled') === 'enabled';
    $interstitialDelay = (int) \\App\\Models\\Setting::get('adsense_interstitial_delay', 3);
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
                @if(!empty($customImage))
                    <a href="{{ $customUrl ?: '#' }}" target="_blank" style="display:block; width:100%; text-decoration:none;">
                        <img src="{{ asset('storage/' . $customImage) }}" alt="Sponsor" style="width:100%; max-height:220px; object-fit:contain; border-radius:10px; display:block;">
                    </a>
                @elseif(!empty($pubId))
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
"""

with open(modal_path, 'w', encoding='utf-8') as f:
    f.write(modal_code)
print("2. Updated adsense_modal.blade.php")

# -------------------------------------------------------------
# 3 & 4. Update resources/views/bio/product_show.blade.php
# -------------------------------------------------------------
ps_path = 'resources/views/bio/product_show.blade.php'
with open(ps_path, 'r', encoding='utf-8') as f:
    ps_content = f.read()

# Fix HTML description display & font styling
# 1. Update font-weights in <style>
ps_content = ps_content.replace(
    "font-weight: 800;",
    "font-weight: 600;"
).replace(
    "font-weight: 900;",
    "font-weight: 600;"
)

# 2. Fix description rendering from {!! nl2br(e($rawDesc)) !!} to {!! $rawDesc !!}
ps_content = ps_content.replace(
    "{!! nl2br(e($rawDesc)) !!}",
    "{!! $rawDesc !!}"
)

# 3. Add CSS for description html tags
if ".prod-desc span {" not in ps_content:
    ps_content = ps_content.replace(
        ".prod-desc {",
        ".prod-desc {\n            font-size: 0.85rem;\n            font-weight: 400;\n            line-height: 1.65;\n            color: {{ $text }};\n            opacity: 0.85;\n            margin-top: 0.85rem;\n            padding-top: 0.85rem;\n            border-top: 1px solid {{ $border }};\n        }\n        .prod-desc p, .prod-desc span, .prod-desc div {\n            font-size: inherit !important;\n            line-height: inherit !important;\n        }\n        .prod-desc-old {"
    )

with open(ps_path, 'w', encoding='utf-8') as f:
    f.write(ps_content)
print("3 & 4. Updated product_show.blade.php typography & fixed HTML description display.")

# -------------------------------------------------------------
# 5. Update CreatorBioController.php & product_show.blade.php for Payment Gateway Checkout
# -------------------------------------------------------------
creator_ctrl = 'app/Http/Controllers/Creator/CreatorBioController.php'
with open(creator_ctrl, 'r', encoding='utf-8') as f:
    cb_code = f.read()

# Make sure 'buyle_product' and 'custom_product' auto-create/link a Product if payment_method is web/gateway
if "use App\\Models\\Product;" not in cb_code:
    cb_code = cb_code.replace("use App\\Models\\CreatorBioBlock;", "use App\\Models\\CreatorBioBlock;\nuse App\\Models\\Product;")

store_auto_prod = """        if (in_array($request->type, ['custom_product', 'buyle_product'])) {
            if ($request->filled('price')) $data['price'] = $request->price;
            if ($request->filled('original_price')) $data['original_price'] = $request->original_price;
            if ($request->filled('payment_method')) $data['payment_method'] = $request->payment_method;
            if ($request->filled('wa_text')) $data['wa_text'] = $request->wa_text;
            
            $cleanTitle = Str::limit($request->title, 45, '');
            $baseSlug   = rtrim(Str::slug($cleanTitle), '-');
            $data['slug'] = $baseSlug ?: 'produk-' . time();

            // If payment_method is web/gateway and no product_id yet, auto create a product in products table
            if (($request->payment_method === 'web' || $request->type === 'custom_product') && empty($data['product_id'])) {
                try {
                    $newProd = Product::create([
                        'name' => $request->title,
                        'slug' => $data['slug'] . '-' . time(),
                        'description' => $request->description ?? '',
                        'price' => $request->price ?? 0,
                        'seller_id' => $profile->user_id,
                        'product_type' => 'physical',
                        'image' => $data['image'] ?? null,
                        'is_active' => true,
                    ]);
                    $data['product_id'] = $newProd->id;
                } catch (\\Exception $e) {}
            }
"""

if "If payment_method is web/gateway and no product_id yet" not in cb_code:
    cb_code = cb_code.replace(
        "        if (in_array($request->type, ['custom_product', 'buyle_product'])) {\n            if ($request->filled('price')) $data['price'] = $request->price;\n            if ($request->filled('original_price')) $data['original_price'] = $request->original_price;\n            if ($request->filled('payment_method')) $data['payment_method'] = $request->payment_method;\n            if ($request->filled('wa_text')) $data['wa_text'] = $request->wa_text;\n            \n            // Smart slug logic: limit title length intelligently for clean URLs\n            $cleanTitle = Str::limit($request->title, 45, '');\n            $baseSlug   = rtrim(Str::slug($cleanTitle), '-');\n            $data['slug'] = $baseSlug ?: 'produk-' . time();",
        store_auto_prod
    )

with open(creator_ctrl, 'w', encoding='utf-8') as f:
    f.write(cb_code)
print("5. Updated CreatorBioController.php for Payment Gateway auto product creation.")

# Update product_show CTA bar to support Payment Gateway form for custom_product & buyle_product
with open(ps_path, 'r', encoding='utf-8') as f:
    ps_content = f.read()

old_cta_bar = """    <div class="cta-bar">
        @if($paymentMethod === 'wa' && $waNumber)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}?text={{ urlencode($waMessage) }}"
                target="_blank" class="btn-buy">
                <i class="fab fa-whatsapp" style="font-size:1.1rem;"></i> Beli via WhatsApp
            </a>
        @elseif($block->url)
            <a href="{{ $block->url }}" target="_blank" class="btn-buy">
                <i class="fas fa-shopping-cart"></i> Beli Sekarang
            </a>
        @else
            <a href="{{ route('bio.public', $username) }}" class="btn-buy">
                <i class="fas fa-arrow-left"></i> Kembali ke Profil
            </a>
        @endif
    </div>"""

new_cta_bar = """    <div class="cta-bar">
        @if($paymentMethod === 'wa' && $waNumber)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}?text={{ urlencode($waMessage) }}"
                target="_blank" class="btn-buy">
                <i class="fab fa-whatsapp" style="font-size:1.1rem;"></i> Beli via WhatsApp
            </a>
        @elseif($product || !empty($block->data_json['product_id']))
            <form action="{{ route('cart.add') }}" method="POST" style="width:100%;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product ? $product->id : ($block->data_json['product_id'] ?? '') }}">
                <input type="hidden" name="qty" value="1">
                <button type="submit" class="btn-buy" style="border:none; cursor:pointer;">
                    <i class="fas fa-shopping-bag"></i> Beli Sekarang (Payment Gateway)
                </button>
            </form>
        @elseif($block->url)
            <a href="{{ $block->url }}" target="_blank" class="btn-buy">
                <i class="fas fa-shopping-cart"></i> Beli Sekarang
            </a>
        @else
            <a href="{{ route('bio.public', $username) }}" class="btn-buy">
                <i class="fas fa-arrow-left"></i> Kembali ke Profil
            </a>
        @endif
    </div>"""

if old_cta_bar in ps_content:
    ps_content = ps_content.replace(old_cta_bar, new_cta_bar)
    with open(ps_path, 'w', encoding='utf-8') as f:
        f.write(ps_content)
    print("5. Updated product_show.blade.php CTA bar with Payment Gateway form.")
