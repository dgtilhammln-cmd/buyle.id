{{-- SYARAT KLAIM PRODUK GRATIS (HANYA MUNCUL JIKA HARGA = 0) --}}
<div id="freeProductRequirementsWrap" class="form-group full" style="display:none; background:#f0fdf4; border:1.5px solid #86efac; border-radius:16px; padding:1.25rem 1.5rem; margin-top:0.75rem; box-sizing:border-box;">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:40px; height:40px; border-radius:12px; background:#dcfce7; display:flex; align-items:center; justify-content:center; color:#166534; flex-shrink:0; border:1px solid #bbf7d0;">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
            </div>
            <div>
                <strong style="display:block; font-size:0.92rem; color:#0f172a; font-weight:800; letter-spacing:-0.2px;">Syarat Klaim Produk Gratis (Lead Magnet)</strong>
                <span style="font-size:0.75rem; color:#166534; font-weight:500;">Kumpulkan followers & anggota komunitas Anda sebelum pembeli mengunduh produk gratis ini.</span>
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

    <div id="freeRequirementsFields" style="display:none; border-top:1px dashed #bbf7d0; padding-top:1.25rem; margin-top:1.15rem;">
        {{-- Banner Edukasi / Info --}}
        <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:0.85rem 1rem; margin-bottom:1.25rem; display:flex; align-items:flex-start; gap:0.6rem;">
            <svg width="18" height="18" fill="none" stroke="#d97706" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div style="font-size:0.77rem; color:#92400e; line-height:1.5;">
                <strong>Cukup isi minimal salah 1 platform</strong> (tidak perlu semua). Calon pembeli hanya akan diwajibkan menyelesaikan syarat pada platform yang Anda cantumkan di bawah ini.
            </div>
        </div>

        {{-- Error jika toggle ON tapi semua kosong --}}
        <div id="err_free_requirements" style="display:none; background:#fef2f2; border:1px solid #fca5a5; border-radius:12px; padding:0.8rem 1rem; margin-bottom:1.25rem; color:#dc2626; font-size:0.76rem; font-weight:700; align-items:center; gap:0.5rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <span>Mohon isi minimal salah satu tautan media sosial atau grup komunitas di bawah ini.</span>
        </div>

        @if($errors->has('require_follow_join'))
        <div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:12px; padding:0.8rem 1rem; margin-bottom:1.25rem; color:#dc2626; font-size:0.76rem; font-weight:700;">
            {{ $errors->first('require_follow_join') }}
        </div>
        @endif

        {{-- SEKSI 1: SOSIAL MEDIA (FOLLOW) --}}
        <div style="margin-bottom:1.5rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.85rem; border-bottom:1px solid #dcfce7; padding-bottom:0.4rem;">
                <div style="display:flex; align-items:center; gap:0.45rem;">
                    <span style="font-size:0.82rem; font-weight:800; color:#14532d; text-transform:uppercase; letter-spacing:0.5px;">1. Akun Sosial Media (Follow)</span>
                </div>
                <span style="font-size:0.7rem; color:#15803d; font-weight:600; background:#dcfce7; padding:2px 8px; border-radius:6px;">Pilih yang Anda miliki</span>
            </div>

            <div class="form-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1rem;">
                {{-- Instagram --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="display:flex; align-items:center; gap:0.45rem; font-size:0.78rem;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#E1306C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                        Instagram
                    </label>
                    <input type="url" name="follow_instagram_url" id="follow_instagram_url"
                        value="{{ old('follow_instagram_url', $product->follow_instagram_url ?? ($product->social_follow_url ?? '')) }}"
                        class="form-input platform-req-input"
                        placeholder="https://instagram.com/username">
                    <span class="form-hint" style="font-size:0.69rem;">Link profil Instagram Anda.</span>
                    @error('follow_instagram_url')
                        <span class="form-error" style="color:#dc2626; font-size:0.72rem; font-weight:700;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- TikTok --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="display:flex; align-items:center; gap:0.45rem; font-size:0.78rem;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="#000000">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.29 0 .57.04.84.11V9.41a6.33 6.33 0 0 0-.84-.06 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V9.05a8.27 8.27 0 0 0 4.77 1.52V7.12a4.85 4.85 0 0 1-1-.43z"/>
                        </svg>
                        TikTok
                    </label>
                    <input type="url" name="follow_tiktok_url" id="follow_tiktok_url"
                        value="{{ old('follow_tiktok_url', $product->follow_tiktok_url ?? '') }}"
                        class="form-input platform-req-input"
                        placeholder="https://tiktok.com/@username">
                    <span class="form-hint" style="font-size:0.69rem;">Link profil TikTok Anda.</span>
                    @error('follow_tiktok_url')
                        <span class="form-error" style="color:#dc2626; font-size:0.72rem; font-weight:700;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- X / Twitter --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="display:flex; align-items:center; gap:0.45rem; font-size:0.78rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#000000">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                        X (Twitter)
                    </label>
                    <input type="url" name="follow_x_url" id="follow_x_url"
                        value="{{ old('follow_x_url', $product->follow_x_url ?? '') }}"
                        class="form-input platform-req-input"
                        placeholder="https://x.com/username">
                    <span class="form-hint" style="font-size:0.69rem;">Link akun X Anda.</span>
                    @error('follow_x_url')
                        <span class="form-error" style="color:#dc2626; font-size:0.72rem; font-weight:700;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- YouTube --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="display:flex; align-items:center; gap:0.45rem; font-size:0.78rem;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="#FF0000">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        YouTube
                    </label>
                    <input type="url" name="follow_youtube_url" id="follow_youtube_url"
                        value="{{ old('follow_youtube_url', $product->follow_youtube_url ?? '') }}"
                        class="form-input platform-req-input"
                        placeholder="https://youtube.com/@channel">
                    <span class="form-hint" style="font-size:0.69rem;">Link channel YouTube Anda.</span>
                    @error('follow_youtube_url')
                        <span class="form-error" style="color:#dc2626; font-size:0.72rem; font-weight:700;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- SEKSI 2: GRUP KOMUNITAS (JOIN) --}}
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.85rem; border-bottom:1px solid #dcfce7; padding-bottom:0.4rem;">
                <div style="display:flex; align-items:center; gap:0.45rem;">
                    <span style="font-size:0.82rem; font-weight:800; color:#14532d; text-transform:uppercase; letter-spacing:0.5px;">2. Grup / Saluran Komunitas (Join)</span>
                </div>
                <span style="font-size:0.7rem; color:#15803d; font-weight:600; background:#dcfce7; padding:2px 8px; border-radius:6px;">Pilih yang Anda miliki</span>
            </div>

            <div class="form-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1rem;">
                {{-- WhatsApp --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="display:flex; align-items:center; gap:0.45rem; font-size:0.78rem;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="#25D366">
                            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z"/>
                        </svg>
                        WhatsApp (Grup / Channel)
                    </label>
                    <input type="url" name="join_whatsapp_url" id="join_whatsapp_url"
                        value="{{ old('join_whatsapp_url', $product->join_whatsapp_url ?? ($product->community_join_url ?? '')) }}"
                        class="form-input platform-req-input"
                        placeholder="https://chat.whatsapp.com/... atau saluran">
                    <span class="form-hint" style="font-size:0.69rem;">Tautan undangan grup atau saluran WhatsApp.</span>
                    @error('join_whatsapp_url')
                        <span class="form-error" style="color:#dc2626; font-size:0.72rem; font-weight:700;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Telegram --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="display:flex; align-items:center; gap:0.45rem; font-size:0.78rem;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="#229ED9">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 0 0-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                        </svg>
                        Telegram (Channel / Grup)
                    </label>
                    <input type="url" name="join_telegram_url" id="join_telegram_url"
                        value="{{ old('join_telegram_url', $product->join_telegram_url ?? '') }}"
                        class="form-input platform-req-input"
                        placeholder="https://t.me/nama_channel">
                    <span class="form-hint" style="font-size:0.69rem;">Tautan channel atau grup Telegram.</span>
                    @error('join_telegram_url')
                        <span class="form-error" style="color:#dc2626; font-size:0.72rem; font-weight:700;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Discord --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" style="display:flex; align-items:center; gap:0.45rem; font-size:0.78rem;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="#5865F2">
                            <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994.021-.041.001-.09-.041-.106a13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.061 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.893.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.028zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                        </svg>
                        Discord (Server)
                    </label>
                    <input type="url" name="join_discord_url" id="join_discord_url"
                        value="{{ old('join_discord_url', $product->join_discord_url ?? '') }}"
                        class="form-input platform-req-input"
                        placeholder="https://discord.gg/invite_code">
                    <span class="form-hint" style="font-size:0.69rem;">Tautan undangan server Discord.</span>
                    @error('join_discord_url')
                        <span class="form-error" style="color:#dc2626; font-size:0.72rem; font-weight:700;">{{ $message }}</span>
                    @enderror
                </div>
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
        const errBox = document.getElementById('err_free_requirements');
        if (errBox) errBox.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('input_price');
    const salePriceInput = document.getElementById('input_sale_price');

    if (priceInput) priceInput.addEventListener('input', checkFreeProductPrice);
    if (salePriceInput) salePriceInput.addEventListener('input', checkFreeProductPrice);

    // Initial check on page load
    checkFreeProductPrice();

    // Hide error banner when creator types into any platform input
    const platformInputs = document.querySelectorAll('.platform-req-input');
    platformInputs.forEach(input => {
        input.addEventListener('input', function() {
            const errBox = document.getElementById('err_free_requirements');
            if (errBox) errBox.style.display = 'none';
        });
    });

    // Validasi form submit: CUKUP SALAH 1 AJA DIISI
    const form = document.querySelector('form.product-form') || document.querySelector('form[action*="products"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const wrap = document.getElementById('freeProductRequirementsWrap');
            const toggle = document.getElementById('require_follow_join_toggle');
            if (wrap && wrap.style.display !== 'none' && toggle && toggle.checked) {
                let hasFilledAtLeastOne = false;
                platformInputs.forEach(input => {
                    if ((input.value || '').trim().length > 0) {
                        hasFilledAtLeastOne = true;
                    }
                });

                const errBox = document.getElementById('err_free_requirements');
                if (!hasFilledAtLeastOne) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (errBox) errBox.style.display = 'flex';
                    document.getElementById('freeProductRequirementsWrap').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Focus first input
                    const firstInput = document.getElementById('follow_instagram_url');
                    if (firstInput) firstInput.focus();
                    return false;
                } else {
                    if (errBox) errBox.style.display = 'none';
                }
            }
        });
    }
});
</script>
