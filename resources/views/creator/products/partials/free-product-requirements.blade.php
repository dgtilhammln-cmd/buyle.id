{{-- SYARAT KLAIM PRODUK GRATIS (HANYA MUNCUL JIKA HARGA = 0) --}}
<div id="freeProductRequirementsWrap" class="form-group full" style="display:none; background:#f0fdf4; border:1.5px solid #86efac; border-radius:14px; padding:1.25rem; margin-top:0.75rem; box-sizing:border-box;">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:0.6rem;">
            <div style="width:36px; height:36px; border-radius:10px; background:#dcfce7; display:flex; align-items:center; justify-content:center; color:#166534; flex-shrink:0; border:1px solid #bbf7d0;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
            </div>
            <div>
                <strong style="display:block; font-size:0.875rem; color:#0f172a; font-weight:800;">Syarat Klaim Produk Gratis (Lead Magnet)</strong>
                <span style="font-size:0.75rem; color:#166534; font-weight:500;">Wajibkan calon pembeli follow akun sosmed & join grup komunitas Anda sebelum bisa download gratis di checkout.</span>
            </div>
        </div>
        <label style="position:relative; display:inline-block; width:44px; height:24px; margin:0; cursor:pointer;">
            <input type="checkbox" name="require_follow_join" id="require_follow_join_toggle" value="1"
                {{ old('require_follow_join', $product->require_follow_join ?? false) ? 'checked' : '' }}
                onchange="toggleFreeRequirementsFields(this.checked)"
                style="opacity:0; width:0; height:0;">
            <span class="toggle-slider-s"></span>
        </label>
    </div>

    <div id="freeRequirementsFields" style="display:none; border-top:1px dashed #bbf7d0; padding-top:1.1rem; margin-top:1rem;">
        <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:0.75rem 0.9rem; margin-bottom:1rem; display:flex; align-items:flex-start; gap:0.5rem;">
            <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div style="font-size:0.75rem; color:#92400e; line-height:1.45;">
                <strong>Perhatian:</strong> Karena syarat diaktifkan, kedua URL di bawah ini <strong>wajib diisi</strong>. Jika belum diisi, produk gratis tidak dapat disimpan.
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group" style="margin-bottom:0.75rem;">
                <label class="form-label" style="display:flex; align-items:center; gap:0.4rem;">
                    <svg width="15" height="15" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                    </svg>
                    URL Akun Sosmed Wajib Follow (Instagram / TikTok / X) <span style="color:#dc2626;">*</span>
                </label>
                <input type="url" name="social_follow_url" id="social_follow_url"
                    value="{{ old('social_follow_url', $product->social_follow_url ?? '') }}"
                    class="form-input"
                    placeholder="https://instagram.com/username atau https://tiktok.com/@username">
                <span class="form-hint">Tautan profil akun sosial media Anda yang wajib dikunjungi & diikuti pembeli.</span>
                <span id="err_social_follow_url" class="form-error" style="display:none; color:#dc2626; font-size:0.72rem; margin-top:3px; font-weight:700;">URL Sosmed wajib diisi ketika syarat diaktifkan.</span>
            </div>

            <div class="form-group" style="margin-bottom:0.75rem;">
                <label class="form-label" style="display:flex; align-items:center; gap:0.4rem;">
                    <svg width="15" height="15" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    </svg>
                    URL Komunitas Wajib Join (WhatsApp / Telegram / Discord) <span style="color:#dc2626;">*</span>
                </label>
                <input type="url" name="community_join_url" id="community_join_url"
                    value="{{ old('community_join_url', $product->community_join_url ?? '') }}"
                    class="form-input"
                    placeholder="https://chat.whatsapp.com/... atau https://t.me/...">
                <span class="form-hint">Tautan grup WhatsApp, channel Telegram, atau server Discord yang wajib dimasuki pembeli.</span>
                <span id="err_community_join_url" class="form-error" style="display:none; color:#dc2626; font-size:0.72rem; margin-top:3px; font-weight:700;">URL Komunitas wajib diisi ketika syarat diaktifkan.</span>
            </div>
        </div>
    </div>
</div>

<script>
function checkFreeProductPrice() {
    const priceInput = document.getElementById('input_price');
    const salePriceInput = document.getElementById('input_sale_price');
    const wrap = document.getElementById('freeProductRequirementsWrap');
    if (!wrap) return;

    const rawPrice = (priceInput?.value || '').replace(/\D/g, '');
    const rawSalePrice = (salePriceInput?.value || '').replace(/\D/g, '');

    const price = rawPrice !== '' ? parseInt(rawPrice, 10) : null;
    const salePrice = rawSalePrice !== '' ? parseInt(rawSalePrice, 10) : null;

    // Produk gratis jika salePrice === 0 ATAU (salePrice kosong dan price === 0)
    const isFree = (salePrice !== null && salePrice === 0) || (salePrice === null && price === 0);

    if (isFree) {
        wrap.style.display = 'block';
        const toggle = document.getElementById('require_follow_join_toggle');
        toggleFreeRequirementsFields(toggle ? toggle.checked : false);
    } else {
        wrap.style.display = 'none';
        const toggle = document.getElementById('require_follow_join_toggle');
        if (toggle) toggle.checked = false;
        toggleFreeRequirementsFields(false);
    }
}

function toggleFreeRequirementsFields(checked) {
    const fields = document.getElementById('freeRequirementsFields');
    if (fields) fields.style.display = checked ? 'block' : 'none';

    if (!checked) {
        const errSoc = document.getElementById('err_social_follow_url');
        const errCom = document.getElementById('err_community_join_url');
        if (errSoc) errSoc.style.display = 'none';
        if (errCom) errCom.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('input_price');
    const salePriceInput = document.getElementById('input_sale_price');

    if (priceInput) priceInput.addEventListener('input', checkFreeProductPrice);
    if (salePriceInput) salePriceInput.addEventListener('input', checkFreeProductPrice);

    // Initial check on page load
    checkFreeProductPrice();

    // Validasi form submit
    const form = document.querySelector('form.product-form') || document.querySelector('form[action*="products"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const wrap = document.getElementById('freeProductRequirementsWrap');
            const toggle = document.getElementById('require_follow_join_toggle');
            if (wrap && wrap.style.display !== 'none' && toggle && toggle.checked) {
                const soc = (document.getElementById('social_follow_url')?.value || '').trim();
                const com = (document.getElementById('community_join_url')?.value || '').trim();
                let hasErr = false;

                if (!soc) {
                    document.getElementById('err_social_follow_url').style.display = 'block';
                    hasErr = true;
                } else {
                    document.getElementById('err_social_follow_url').style.display = 'none';
                }

                if (!com) {
                    document.getElementById('err_community_join_url').style.display = 'block';
                    hasErr = true;
                } else {
                    document.getElementById('err_community_join_url').style.display = 'none';
                }

                if (hasErr) {
                    e.preventDefault();
                    e.stopPropagation();
                    document.getElementById('freeProductRequirementsWrap').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
            }
        });
    }
});
</script>
