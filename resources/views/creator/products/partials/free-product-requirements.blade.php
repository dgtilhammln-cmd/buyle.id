{{-- SYARAT KLAIM PRODUK GRATIS (DYNAMIC MULTI-SOSMED REPEATER) --}}
@php
    $platformsMaster = \App\Models\Product::getAvailablePlatforms();

    // Siapkan data awal untuk edit mode / old() input
    $initialRequirements = [];
    $rawOld = old('custom_free_requirements', $product->custom_free_requirements ?? null);

    if (!empty($rawOld) && is_array($rawOld)) {
        $initialRequirements = $rawOld;
    } elseif (!empty($rawOld) && is_string($rawOld)) {
        $decoded = json_decode($rawOld, true);
        if (is_array($decoded)) {
            $initialRequirements = $decoded;
        }
    }

    // Fallback: Jika belum ada custom_free_requirements tapi ada data legacy
    if (empty($initialRequirements) && isset($product) && $product->require_follow_join) {
        if (!empty($product->follow_instagram_url)) {
            $initialRequirements[] = ['platform' => 'instagram', 'url' => $product->follow_instagram_url, 'label' => ''];
        } elseif (!empty($product->social_follow_url)) {
            $initialRequirements[] = ['platform' => 'website', 'url' => $product->social_follow_url, 'label' => ''];
        }
        if (!empty($product->follow_tiktok_url)) {
            $initialRequirements[] = ['platform' => 'tiktok', 'url' => $product->follow_tiktok_url, 'label' => ''];
        }
        if (!empty($product->follow_x_url)) {
            $initialRequirements[] = ['platform' => 'x', 'url' => $product->follow_x_url, 'label' => ''];
        }
        if (!empty($product->follow_youtube_url)) {
            $initialRequirements[] = ['platform' => 'youtube', 'url' => $product->follow_youtube_url, 'label' => ''];
        }
        if (!empty($product->join_whatsapp_url)) {
            $initialRequirements[] = ['platform' => 'whatsapp_channel', 'url' => $product->join_whatsapp_url, 'label' => ''];
        } elseif (!empty($product->community_join_url)) {
            $initialRequirements[] = ['platform' => 'whatsapp_group', 'url' => $product->community_join_url, 'label' => ''];
        }
        if (!empty($product->join_telegram_url)) {
            $initialRequirements[] = ['platform' => 'telegram_channel', 'url' => $product->join_telegram_url, 'label' => ''];
        }
        if (!empty($product->join_discord_url)) {
            $initialRequirements[] = ['platform' => 'discord', 'url' => $product->join_discord_url, 'label' => ''];
        }
    }
@endphp

<div id="freeProductRequirementsWrap" class="form-group full" style="display:none; background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; margin-top:0.75rem; margin-bottom:0.75rem; overflow:hidden; box-sizing:border-box;">
    {{-- Header Toggle --}}
    <div style="display:flex; align-items:center; justify-content:space-between; padding:0.85rem 1rem; user-select:none; background:#f8fafc; gap:0.75rem;">
        <label for="require_follow_join_toggle" style="flex:1; cursor:pointer; margin:0;">
            <div style="font-size:0.86rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:6px;">
                <span>Syarat Klaim Gratis</span>
                <span style="font-size:0.65rem; font-weight:600; background:#dcfce7; color:#15803d; padding:2px 7px; border-radius:6px;">Sosmed / Komunitas</span>
            </div>
            <div style="font-size:0.72rem; color:#64748b; line-height:1.4; margin-top:2px;">Wajibkan pembeli follow sosmed atau join grup sebelum klaim tiket/file.</div>
        </label>
        <div style="position:relative; width:44px; height:24px; flex-shrink:0;">
            <input type="checkbox" name="require_follow_join" id="require_follow_join_toggle" value="1"
                {{ old('require_follow_join', $product->require_follow_join ?? false) ? 'checked' : '' }}
                onchange="toggleFreeRequirementsFields(this.checked)"
                style="position:absolute; inset:0; width:100%; height:100%; opacity:0; z-index:5; cursor:pointer; margin:0;">
            <span class="toggle-slider-s" style="pointer-events:none;"></span>
        </div>
    </div>

    <div id="freeRequirementsFields" style="display:none; border-top:1px solid #e2e8f0; padding:1.15rem 1.25rem; background:#ffffff;">
        {{-- Banner Info Singkat --}}
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.75rem 0.9rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
            <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div style="font-size:0.75rem; color:#166534; line-height:1.4;">
                Isi minimal 1 platform. Anda dapat menambahkan platform yang sama berulang kali (misal: 2 akun Instagram).
            </div>
        </div>

        {{-- Error Banner jika kosong saat submit --}}
        <div id="err_free_requirements" style="display:none; background:#fef2f2; border:1px solid #fca5a5; border-radius:10px; padding:0.75rem 0.9rem; margin-bottom:1rem; color:#dc2626; font-size:0.75rem; font-weight:600; align-items:center; gap:0.5rem;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <span>Mohon isi minimal 1 tautan syarat sosial media atau komunitas yang valid.</span>
        </div>

        @if($errors->has('require_follow_join'))
        <div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:10px; padding:0.75rem 0.9rem; margin-bottom:1rem; color:#dc2626; font-size:0.75rem; font-weight:600;">
            {{ $errors->first('require_follow_join') }}
        </div>
        @endif

        {{-- REPEATER CONTAINER: DAFTAR KARTU SYARAT --}}
        <div id="repeaterRequirementsList" style="display:flex; flex-direction:column; gap:0.75rem; margin-bottom:1rem;">
            {{-- Kartu akan di-render secara dinamis oleh JavaScript --}}
        </div>

        {{-- Tombol Tambah Syarat (Bersih & Mobile Friendly) --}}
        <div style="margin-bottom:1.25rem;">
            <button type="button" onclick="addRequirementRow('instagram')"
                style="width:100%; min-height:42px; background:#f0fdf4; border:1px dashed #86efac; color:#15803d; border-radius:10px; font-size:0.8rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:6px; transition:background 0.2s; padding:0.6rem 1rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>Tambah Syarat Sosmed / Komunitas</span>
            </button>
        </div>

        {{-- SEKSI VALIDASI KETAT (OPSI UPLOAD BUKTI SCREENSHOT) --}}
        <div style="padding-top:1rem; border-top:1px solid #f1f5f9;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                <label for="require_screenshot_proof_toggle" style="flex:1; cursor:pointer; margin:0;">
                    <div style="font-size:0.82rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                        <svg width="15" height="15" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <span>Wajibkan Screenshot Bukti</span>
                        <span style="font-size:0.65rem; font-weight:600; color:#166534; background:#dcfce7; padding:2px 6px; border-radius:6px;">Validasi Ketat</span>
                    </div>
                    <div style="font-size:0.71rem; color:#64748b; line-height:1.4; margin-top:2px;">
                        Pembeli wajib melampirkan screenshot bukti follow/join sebelum klaim berhasil. Bukti dapat Anda review di Laporan Pesanan.
                    </div>
                </label>
                <div style="position:relative; width:44px; height:24px; flex-shrink:0;">
                    <input type="checkbox" name="require_screenshot_proof" id="require_screenshot_proof_toggle" value="1"
                        {{ old('require_screenshot_proof', $product->require_screenshot_proof ?? false) ? 'checked' : '' }}
                        style="position:absolute; inset:0; width:100%; height:100%; opacity:0; z-index:5; cursor:pointer; margin:0;">
                    <span class="toggle-slider-s" style="pointer-events:none;"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Data Master Platform dari Backend (Nama Bersih tanpa Emoji)
const PLATFORMS_MASTER = {
    'instagram': { name: 'Instagram', action: 'Follow', placeholder: 'https://instagram.com/username', default_label: 'Follow Akun Instagram' },
    'tiktok': { name: 'TikTok', action: 'Follow', placeholder: 'https://tiktok.com/@username', default_label: 'Follow Akun TikTok' },
    'youtube': { name: 'YouTube', action: 'Subscribe', placeholder: 'https://youtube.com/@channel', default_label: 'Subscribe Channel YouTube' },
    'whatsapp_channel': { name: 'WhatsApp Saluran', action: 'Ikuti', placeholder: 'https://whatsapp.com/channel/...', default_label: 'Ikuti Saluran WhatsApp' },
    'whatsapp_group': { name: 'WhatsApp Grup', action: 'Join', placeholder: 'https://chat.whatsapp.com/...', default_label: 'Gabung Grup WhatsApp' },
    'telegram_channel': { name: 'Telegram Channel', action: 'Join', placeholder: 'https://t.me/nama_channel', default_label: 'Join Channel Telegram' },
    'telegram_group': { name: 'Telegram Grup', action: 'Join', placeholder: 'https://t.me/nama_grup', default_label: 'Gabung Grup Telegram' },
    'x': { name: 'X (Twitter)', action: 'Follow', placeholder: 'https://x.com/username', default_label: 'Follow Akun X (Twitter)' },
    'threads': { name: 'Threads', action: 'Follow', placeholder: 'https://threads.net/@username', default_label: 'Follow Akun Threads' },
    'facebook': { name: 'Facebook', action: 'Follow', placeholder: 'https://facebook.com/halaman', default_label: 'Follow Halaman Facebook' },
    'linkedin': { name: 'LinkedIn', action: 'Follow', placeholder: 'https://linkedin.com/in/username', default_label: 'Follow Profil LinkedIn' },
    'discord': { name: 'Discord', action: 'Join', placeholder: 'https://discord.gg/invite_code', default_label: 'Join Server Discord' },
    'spotify': { name: 'Spotify', action: 'Follow', placeholder: 'https://open.spotify.com/artist/...', default_label: 'Follow Akun Spotify' },
    'github': { name: 'GitHub', action: 'Follow', placeholder: 'https://github.com/username', default_label: 'Follow Profil GitHub' },
    'pinterest': { name: 'Pinterest', action: 'Follow', placeholder: 'https://pinterest.com/username', default_label: 'Follow Akun Pinterest' },
    'website': { name: 'Website / Link Kustom', action: 'Kunjungi', placeholder: 'https://website-anda.com', default_label: 'Kunjungi Tautan Resmi' }
};

// Data Inisial dari Database / Old Input
let requirementsData = @json($initialRequirements);
let requirementRowCounter = 0;

function checkFreeProductPrice() {
    const priceInput = document.getElementById('input_price');
    const salePriceInput = document.getElementById('input_sale_price');
    const pricingTypeInput = document.getElementById('pricing_type_input');
    const wrap = document.getElementById('freeProductRequirementsWrap');
    if (!wrap) return;

    const pricingType = pricingTypeInput ? pricingTypeInput.value : null;

    const rawPrice = (priceInput?.value || '').replace(/\D/g, '');
    const rawSalePrice = (salePriceInput?.value || '').replace(/\D/g, '');

    const price = rawPrice !== '' ? parseInt(rawPrice, 10) : null;
    const salePrice = rawSalePrice !== '' ? parseInt(rawSalePrice, 10) : null;

    const isFree = pricingType === 'free' || (pricingType === null && ((salePrice !== null && salePrice === 0) || (salePrice === null && price === 0)));

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
    } else {
        const list = document.getElementById('repeaterRequirementsList');
        if (list && list.children.length === 0) {
            addRequirementRow('instagram');
        }
    }
}

function addRequirementRow(defaultPlatform = 'instagram', data = null) {
    const list = document.getElementById('repeaterRequirementsList');
    if (!list) return;

    const rowId = 'req_row_' + (++requirementRowCounter);
    const platform = (data && data.platform) ? data.platform : defaultPlatform;
    const url = (data && data.url) ? data.url : '';
    const label = (data && data.label) ? data.label : '';
    const meta = PLATFORMS_MASTER[platform] || PLATFORMS_MASTER['website'];

    const card = document.createElement('div');
    card.id = rowId;
    card.className = 'requirement-repeater-card';
    card.style.cssText = 'background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:0.75rem 0.9rem; box-shadow:0 1px 2px rgba(0,0,0,0.02);';

    let optionsHtml = '';
    for (const [key, p] of Object.entries(PLATFORMS_MASTER)) {
        const isSelected = (key === platform) ? 'selected' : '';
        optionsHtml += `<option value="${key}" ${isSelected}>${p.name} (${p.action})</option>`;
    }

    card.innerHTML = `
        <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; margin-bottom:0.5rem; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:0.45rem; flex:1; min-width:180px;">
                <span class="row-num-badge" style="width:20px; height:20px; border-radius:5px; background:#f1f5f9; color:#64748b; font-size:0.68rem; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0;">1</span>
                <select name="custom_free_requirements[${requirementRowCounter}][platform]"
                    class="form-input platform-select-input"
                    onchange="onPlatformChanged('${rowId}', this.value)"
                    style="font-size:0.8rem; font-weight:600; color:#0f172a; padding:0.3rem 0.55rem; border-radius:7px; height:auto; width:auto; max-width:100%; cursor:pointer;">
                    ${optionsHtml}
                </select>
            </div>
            <button type="button" onclick="removeRequirementRow('${rowId}')"
                style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; border-radius:7px; padding:0.3rem 0.55rem; font-size:0.72rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:3px;"
                title="Hapus Syarat">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                <span>Hapus</span>
            </button>
        </div>

        <div style="display:flex; flex-direction:column; gap:0.4rem;">
            <div>
                <div style="position:relative;">
                    <input type="url" name="custom_free_requirements[${requirementRowCounter}][url]"
                        value="${url}"
                        placeholder="${meta.placeholder}"
                        class="form-input req-url-input"
                        required
                        oninput="hideRequirementsError()"
                        style="padding-left:2rem; font-size:0.8rem; height:38px;">
                    <div style="position:absolute; left:0.65rem; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    </div>
                </div>
            </div>

            <div>
                <input type="text" name="custom_free_requirements[${requirementRowCounter}][label]"
                    value="${label}"
                    placeholder="${meta.default_label} (opsional ubah label)"
                    class="form-input"
                    style="font-size:0.75rem; padding:0.3rem 0.6rem; height:32px; background:#f8fafc; border-color:#e2e8f0;">
            </div>
        </div>
    `;

    list.appendChild(card);
    updateRowNumberBadges();
    hideRequirementsError();

    if (!data) {
        const urlInp = card.querySelector('.req-url-input');
        if (urlInp) urlInp.focus();
    }
}

function onPlatformChanged(rowId, newPlatform) {
    const card = document.getElementById(rowId);
    if (!card) return;

    const meta = PLATFORMS_MASTER[newPlatform] || PLATFORMS_MASTER['website'];
    const urlInput = card.querySelector('.req-url-input');
    const labelInput = card.querySelector('input[name*="[label]"]');

    if (urlInput) {
        urlInput.placeholder = meta.placeholder;
    }
    if (labelInput) {
        labelInput.placeholder = meta.default_label + ' (opsional ubah label)';
    }
}

function removeRequirementRow(rowId) {
    const card = document.getElementById(rowId);
    if (card) {
        card.remove();
        updateRowNumberBadges();
    }
}

function updateRowNumberBadges() {
    const badges = document.querySelectorAll('#repeaterRequirementsList .row-num-badge');
    badges.forEach((b, idx) => {
        b.textContent = (idx + 1);
    });
}

function hideRequirementsError() {
    const errBox = document.getElementById('err_free_requirements');
    if (errBox) errBox.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('input_price');
    const salePriceInput = document.getElementById('input_sale_price');

    if (priceInput) priceInput.addEventListener('input', checkFreeProductPrice);
    if (salePriceInput) salePriceInput.addEventListener('input', checkFreeProductPrice);

    // Initial populate dari data yang ada
    if (Array.isArray(requirementsData) && requirementsData.length > 0) {
        requirementsData.forEach(item => {
            if (item && item.url) {
                addRequirementRow(item.platform || 'instagram', item);
            }
        });
    }

    // Initial check on page load
    checkFreeProductPrice();

    // Validasi form submit: CUKUP MINIMAL 1 SYARAT DENGAN URL VALID
    const form = document.querySelector('form.product-form') || document.querySelector('form[action*="products"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const wrap = document.getElementById('freeProductRequirementsWrap');
            const toggle = document.getElementById('require_follow_join_toggle');

            if (wrap && wrap.style.display !== 'none' && toggle && toggle.checked) {
                const urlInputs = document.querySelectorAll('#repeaterRequirementsList .req-url-input');
                let hasFilledAtLeastOne = false;

                urlInputs.forEach(input => {
                    const val = (input.value || '').trim();
                    if (val.length > 0) {
                        hasFilledAtLeastOne = true;
                    }
                });

                const errBox = document.getElementById('err_free_requirements');
                if (!hasFilledAtLeastOne) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (errBox) errBox.style.display = 'flex';
                    wrap.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                } else {
                    if (errBox) errBox.style.display = 'none';
                }
            }
        });
    }
});
</script>
