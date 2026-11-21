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
            $initialRequirements[] = ['platform' => 'instagram', 'url' => $product->follow_instagram_url, 'label' => 'Follow Akun Instagram'];
        } elseif (!empty($product->social_follow_url)) {
            $initialRequirements[] = ['platform' => 'website', 'url' => $product->social_follow_url, 'label' => 'Follow Akun Sosial Media'];
        }
        if (!empty($product->follow_tiktok_url)) {
            $initialRequirements[] = ['platform' => 'tiktok', 'url' => $product->follow_tiktok_url, 'label' => 'Follow Akun TikTok'];
        }
        if (!empty($product->follow_x_url)) {
            $initialRequirements[] = ['platform' => 'x', 'url' => $product->follow_x_url, 'label' => 'Follow Akun X (Twitter)'];
        }
        if (!empty($product->follow_youtube_url)) {
            $initialRequirements[] = ['platform' => 'youtube', 'url' => $product->follow_youtube_url, 'label' => 'Subscribe Channel YouTube'];
        }
        if (!empty($product->join_whatsapp_url)) {
            $initialRequirements[] = ['platform' => 'whatsapp_channel', 'url' => $product->join_whatsapp_url, 'label' => 'Ikuti Saluran WhatsApp'];
        } elseif (!empty($product->community_join_url)) {
            $initialRequirements[] = ['platform' => 'whatsapp_group', 'url' => $product->community_join_url, 'label' => 'Join Komunitas WhatsApp'];
        }
        if (!empty($product->join_telegram_url)) {
            $initialRequirements[] = ['platform' => 'telegram_channel', 'url' => $product->join_telegram_url, 'label' => 'Join Channel Telegram'];
        }
        if (!empty($product->join_discord_url)) {
            $initialRequirements[] = ['platform' => 'discord', 'url' => $product->join_discord_url, 'label' => 'Join Server Discord'];
        }
    }
@endphp

<div id="freeProductRequirementsWrap" class="form-group full" style="display:none; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; margin-top:0.75rem; margin-bottom:0.75rem; overflow:hidden; box-sizing:border-box;">
    {{-- Header Toggle --}}
    <label style="display:flex; align-items:center; gap:0.75rem; padding:0.85rem 1rem; cursor:pointer; user-select:none; margin:0; background:#f8fafc;">
        <div style="position:relative; width:44px; height:24px; flex-shrink:0;">
            <input type="checkbox" name="require_follow_join" id="require_follow_join_toggle" value="1"
                {{ old('require_follow_join', $product->require_follow_join ?? false) ? 'checked' : '' }}
                onchange="toggleFreeRequirementsFields(this.checked)"
                style="opacity:0; width:0; height:0;">
            <span class="toggle-slider-s"></span>
        </div>
        <div style="flex:1;">
            <div style="font-size:0.88rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:6px;">
                <span>Syarat Klaim Produk Gratis</span>
                <span style="font-size:0.65rem; font-weight:800; background:#dcfce7; color:#15803d; padding:2px 8px; border-radius:999px;">Multi-Platform</span>
            </div>
            <div style="font-size:0.73rem; color:#64748b; line-height:1.4;">Kumpulkan followers & anggota komunitas Anda sebelum pembeli mengunduh produk gratis ini.</div>
        </div>
    </label>

    <div id="freeRequirementsFields" style="display:none; border-top:1px solid #e2e8f0; padding:1.15rem 1.25rem; background:#ffffff;">
        {{-- Banner Edukasi / Info --}}
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:0.85rem 1rem; margin-bottom:1.25rem; display:flex; align-items:flex-start; gap:0.6rem;">
            <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div style="font-size:0.77rem; color:#166534; line-height:1.5;">
                <strong>Pilih dari belasan platform</strong> sosial media atau grup komunitas. Anda dapat menambahkan platform yang sama berulang kali (misal: Follow akun pribadi & akun brand). <strong>Isi minimal 1 platform</strong>.
            </div>
        </div>

        {{-- Error Banner jika kosong saat submit --}}
        <div id="err_free_requirements" style="display:none; background:#fef2f2; border:1px solid #fca5a5; border-radius:12px; padding:0.8rem 1rem; margin-bottom:1.25rem; color:#dc2626; font-size:0.76rem; font-weight:700; align-items:center; gap:0.5rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0;">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <span>Mohon tambahkan dan isi minimal 1 tautan syarat sosial media / komunitas yang valid.</span>
        </div>

        @if($errors->has('require_follow_join'))
        <div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:12px; padding:0.8rem 1rem; margin-bottom:1.25rem; color:#dc2626; font-size:0.76rem; font-weight:700;">
            {{ $errors->first('require_follow_join') }}
        </div>
        @endif

        {{-- Quick Add Chips (Shortcut Cepat Belasan Platform) --}}
        <div style="margin-bottom:1rem;">
            <div style="font-size:0.72rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.5rem; display:flex; align-items:center; gap:4px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                Tambah Cepat Platform:
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:0.4rem;">
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('instagram')" style="border:1px solid #fbcfe8; background:#fdf2f8; color:#db2777; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 📸 Instagram
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('tiktok')" style="border:1px solid #e2e8f0; background:#f8fafc; color:#0f172a; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 🎵 TikTok
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('youtube')" style="border:1px solid #fecaca; background:#fef2f2; color:#dc2626; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + ▶️ YouTube
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('whatsapp_channel')" style="border:1px solid #bbf7d0; background:#f0fdf4; color:#16a34a; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 💬 WA Saluran
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('whatsapp_group')" style="border:1px solid #bbf7d0; background:#f0fdf4; color:#16a34a; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 👥 WA Grup
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('telegram_channel')" style="border:1px solid #bae6fd; background:#f0f9ff; color:#0284c7; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 📢 Telegram
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('x')" style="border:1px solid #e2e8f0; background:#f8fafc; color:#0f172a; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 𝕏 X (Twitter)
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('threads')" style="border:1px solid #e2e8f0; background:#f8fafc; color:#0f172a; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 🧵 Threads
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('discord')" style="border:1px solid #c7d2fe; background:#eef2ff; color:#4f46e5; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 🎮 Discord
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('spotify')" style="border:1px solid #bbf7d0; background:#f0fdf4; color:#15803d; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 🎧 Spotify
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('facebook')" style="border:1px solid #bfdbfe; background:#eff6ff; color:#1d4ed8; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 📘 Facebook
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('linkedin')" style="border:1px solid #bfdbfe; background:#eff6ff; color:#0284c7; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 💼 LinkedIn
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('github')" style="border:1px solid #e2e8f0; background:#f8fafc; color:#1e293b; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 🐙 GitHub
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('pinterest')" style="border:1px solid #fecaca; background:#fef2f2; color:#b91c1c; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 📌 Pinterest
                </button>
                <button type="button" class="quick-add-btn" onclick="addRequirementRow('website')" style="border:1px solid #bbf7d0; background:#f0fdf4; color:#16a34a; padding:4px 9px; border-radius:8px; font-size:0.74rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;">
                    + 🌐 Website / Link
                </button>
            </div>
        </div>

        {{-- REPEATER CONTAINER: DAFTAR KARTU SYARAT --}}
        <div id="repeaterRequirementsList" style="display:flex; flex-direction:column; gap:0.85rem; margin-bottom:1rem;">
            {{-- Kartu akan di-render secara dinamis oleh JavaScript --}}
        </div>

        {{-- Tombol Tambah Syarat Utama (Besar & Mobile-Friendly) --}}
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.5rem;">
            <button type="button" onclick="addRequirementRow('instagram')"
                style="flex:1; min-height:44px; background:#f0fdf4; border:1.5px dashed #86efac; color:#15803d; border-radius:12px; font-size:0.82rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:6px; transition:all 0.2s; padding:0.65rem 1rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>+ Tambah Syarat Sosmed / Komunitas</span>
            </button>
        </div>

        {{-- SEKSI 3: METODE VALIDASI SYARAT (OPSI UPLOAD BUKTI SCREENSHOT) --}}
        <div style="padding-top:1.25rem; border-top:1px solid #f1f5f9;">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem;">
                <div style="flex:1;">
                    <div style="font-size:0.84rem; font-weight:800; color:#0f172a; margin-bottom:0.25rem; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                        <svg width="17" height="17" fill="none" stroke="#16a34a" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <span>Wajibkan Upload Screenshot Bukti</span>
                        <span style="font-size:0.65rem; font-weight:800; color:#166534; background:#dcfce7; padding:2px 7px; border-radius:999px;">Validasi Ketat</span>
                    </div>
                    <div style="font-size:0.73rem; color:#64748b; line-height:1.45;">
                        Jika aktif, pembeli / peserta <strong>wajib melampirkan screenshot</strong> bukti follow/join sebelum tiket/file dapat diklaim. Bukti otomatis terkompresi dan dapat Anda review langsung di Dashboard Ticketing & Laporan Pesanan.
                    </div>
                </div>
                <div style="position:relative; width:44px; height:24px; flex-shrink:0; margin-top:2px;">
                    <input type="checkbox" name="require_screenshot_proof" id="require_screenshot_proof_toggle" value="1"
                        {{ old('require_screenshot_proof', $product->require_screenshot_proof ?? false) ? 'checked' : '' }}
                        style="opacity:0; width:0; height:0;">
                    <span class="toggle-slider-s"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Data Master Platform dari Backend
const PLATFORMS_MASTER = @json($platformsMaster);

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
        // Jika belum ada row sama sekali saat toggle di-ON-kan, tambahkan default 1 baris Instagram
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
    card.style.cssText = 'background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:0.85rem 1rem; box-shadow:0 1px 3px rgba(0,0,0,0.03); transition:border-color 0.2s;';

    // Buat options untuk select
    let optionsHtml = '';
    for (const [key, p] of Object.entries(PLATFORMS_MASTER)) {
        const isSelected = (key === platform) ? 'selected' : '';
        optionsHtml += `<option value="${key}" ${isSelected}>${p.name} (${p.action})</option>`;
    }

    card.innerHTML = `
        <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; margin-bottom:0.65rem; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:0.5rem; flex:1; min-width:200px;">
                <span class="row-num-badge" style="width:22px; height:22px; border-radius:6px; background:#f1f5f9; color:#475569; font-size:0.7rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0;">1</span>
                <select name="custom_free_requirements[${requirementRowCounter}][platform]"
                    class="form-input platform-select-input"
                    onchange="onPlatformChanged('${rowId}', this.value)"
                    style="font-size:0.82rem; font-weight:700; color:#0f172a; padding:0.35rem 0.65rem; border-radius:8px; height:auto; width:auto; max-width:100%; cursor:pointer;">
                    ${optionsHtml}
                </select>
            </div>
            <button type="button" onclick="removeRequirementRow('${rowId}')"
                style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; border-radius:8px; padding:0.35rem 0.65rem; font-size:0.75rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px; transition:all 0.15s;"
                title="Hapus Syarat Ini">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                <span>Hapus</span>
            </button>
        </div>

        <div style="display:flex; flex-direction:column; gap:0.45rem;">
            <div>
                <label style="font-size:0.72rem; font-weight:700; color:#475569; margin-bottom:2px; display:block;">Tautan / Link Tujuan <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <input type="url" name="custom_free_requirements[${requirementRowCounter}][url]"
                        value="${url}"
                        placeholder="${meta.placeholder}"
                        class="form-input req-url-input"
                        required
                        oninput="hideRequirementsError()"
                        style="padding-left:2.2rem; font-size:0.82rem;">
                    <div style="position:absolute; left:0.7rem; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    </div>
                </div>
            </div>

            <div>
                <label style="font-size:0.7rem; color:#64748b; margin-bottom:2px; display:flex; justify-content:space-between;">
                    <span>Label Tombol (Opsional):</span>
                    <span style="color:#94a3b8; font-size:0.67rem;">Contoh: Follow IG @brand_saya</span>
                </label>
                <input type="text" name="custom_free_requirements[${requirementRowCounter}][label]"
                    value="${label}"
                    placeholder="${meta.default_label}"
                    class="form-input"
                    style="font-size:0.78rem; padding:0.35rem 0.65rem;">
            </div>
        </div>
    `;

    list.appendChild(card);
    updateRowNumberBadges();
    hideRequirementsError();

    // Auto focus ke URL jika user klik tombol tambah
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
        labelInput.placeholder = meta.default_label;
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
        b.textContent = '#' + (idx + 1);
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
