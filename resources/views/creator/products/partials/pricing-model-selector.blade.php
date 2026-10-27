{{--
  PRICING MODEL SELECTOR
  Ditampilkan di create & edit product form.
  Desain selaras tema Buyle (Montserrat, Emerald #1eb349, Slate & Clean neutral cards)
--}}

@php
  $pt       = old('pricing_type', $product->pricing_type ?? 'fixed');
  $pUnit    = old('traktir_unit_name', $product->traktir_unit_name ?? 'Kopi');
  $pIcon    = old('traktir_unit_icon', $product->traktir_unit_icon ?? 'coffee');
  $pPrice   = old('traktir_unit_price', $product->traktir_unit_price ?? 10000);
  $pwywMin  = old('pwyw_min_price', $product->pwyw_min_price ?? 0);
  $pwywSug  = old('pwyw_suggested_price', $product->pwyw_suggested_price ?? null);
@endphp

{{-- Hidden field pricing_type --}}
<input type="hidden" name="pricing_type" id="pricing_type_input" value="{{ $pt }}">

{{-- Traktir hidden fields --}}
<input type="hidden" name="traktir_unit_name" id="traktir_unit_name_input" value="{{ $pUnit }}">
<input type="hidden" name="traktir_unit_icon" id="traktir_unit_icon_input" value="{{ $pIcon }}">

{{-- PWYW hidden fields --}}
<input type="hidden" name="pwyw_min_price" id="pwyw_min_price_input" value="{{ $pwywMin }}">
<input type="hidden" name="pwyw_suggested_price" id="pwyw_suggested_price_input" value="{{ $pwywSug }}">
<input type="hidden" name="traktir_unit_price" id="traktir_unit_price_input" value="{{ $pPrice }}">

<style>
    .buyle-pricing-model-wrap {
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
        margin: 1.25rem 0 1.5rem 0;
    }
    .buyle-pricing-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    @media (max-width: 640px) {
        .buyle-pricing-grid {
            grid-template-columns: 1fr;
            gap: 0.6rem;
        }
    }
    .buyle-ptab-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.85rem 1rem;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-align: left;
        width: 100%;
        box-sizing: border-box;
        position: relative;
    }
    .buyle-ptab-card:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        transform: translateY(-1px);
    }
    .buyle-ptab-card.active {
        background: #ffffff;
        border-color: #1eb349;
        box-shadow: 0 4px 16px rgba(30, 179, 73, 0.12), 0 0 0 1px #1eb349;
    }
    .buyle-ptab-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s;
        font-size: 1.15rem;
    }
    .buyle-ptab-card.active .buyle-ptab-icon {
        background: rgba(30, 179, 73, 0.12);
        color: #1eb349;
    }
    .buyle-ptab-info {
        flex: 1;
        min-width: 0;
    }
    .buyle-ptab-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .buyle-ptab-desc {
        font-size: 0.73rem;
        font-weight: 500;
        color: #64748b;
        line-height: 1.4;
    }
    .buyle-ptab-radio {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        position: relative;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    .buyle-ptab-card.active .buyle-ptab-radio {
        border-color: #1eb349;
        background: #1eb349;
    }
    .buyle-ptab-card.active .buyle-ptab-radio::after {
        content: '';
        position: absolute;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #fff;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Sub-panels */
    .buyle-pricing-subpanel {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
        animation: buyleFadeIn 0.2s ease-out;
    }
    @keyframes buyleFadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .buyle-subpanel-head {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.88rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .buyle-subpanel-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 640px) {
        .buyle-subpanel-grid {
            grid-template-columns: 1fr;
            gap: 0.85rem;
        }
    }
    .buyle-subpanel-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.4rem;
        display: block;
    }
    .buyle-subpanel-hint {
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 0.35rem;
        display: block;
    }
    .buyle-icon-pill-btn {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.45rem 0.75rem;
        font-size: 1.15rem;
        cursor: pointer;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .buyle-icon-pill-btn:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }
    .buyle-icon-pill-btn.active {
        border-color: #1eb349;
        background: rgba(30, 179, 73, 0.1);
        transform: scale(1.05);
    }
    .buyle-subpanel-preview {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 1rem;
    }
</style>

<div class="form-group full buyle-pricing-model-wrap" id="pricing-model-wrap">
    <label class="form-label" style="font-weight:800; color:#0f172a; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.5rem; font-size:0.92rem;">
        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
            <rect x="2" y="4" width="20" height="16" rx="2"/>
            <line x1="2" y1="10" x2="22" y2="10"/>
        </svg>
        Model Penetapan Harga
    </label>

    {{-- Tabs Grid --}}
    <div class="buyle-pricing-grid" id="pricing-tab-grid">
        {{-- Fixed Price --}}
        <div id="ptab_fixed" onclick="switchPricingTab('fixed')" class="buyle-ptab-card {{ $pt === 'fixed' ? 'active' : '' }}" role="button" tabindex="0">
            <div class="buyle-ptab-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div class="buyle-ptab-info">
                <div class="buyle-ptab-title">
                    <span>Harga Tetap</span>
                    <span class="buyle-ptab-radio"></span>
                </div>
                <div class="buyle-ptab-desc">Pembeli membayar harga pasti yang Anda tentukan</div>
            </div>
        </div>

        {{-- Free --}}
        <div id="ptab_free" onclick="switchPricingTab('free')" class="buyle-ptab-card {{ $pt === 'free' ? 'active' : '' }}" role="button" tabindex="0">
            <div class="buyle-ptab-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 14 14"/>
                </svg>
            </div>
            <div class="buyle-ptab-info">
                <div class="buyle-ptab-title">
                    <span>Gratis (Wajib Follow)</span>
                    <span class="buyle-ptab-radio"></span>
                </div>
                <div class="buyle-ptab-desc">Klaim gratis setelah follow sosial media / komunitas</div>
            </div>
        </div>

        {{-- Traktir --}}
        <div id="ptab_traktir" onclick="switchPricingTab('traktir')" class="buyle-ptab-card {{ $pt === 'traktir' ? 'active' : '' }}" role="button" tabindex="0">
            <div class="buyle-ptab-icon">
                <span>☕</span>
            </div>
            <div class="buyle-ptab-info">
                <div class="buyle-ptab-title">
                    <span>Traktir Kreator</span>
                    <span class="buyle-ptab-radio"></span>
                </div>
                <div class="buyle-ptab-desc">Pembeli mentraktir kelipatan kopi / item pilihan</div>
            </div>
        </div>

        {{-- PWYW --}}
        <div id="ptab_pay_what_you_want" onclick="switchPricingTab('pay_what_you_want')" class="buyle-ptab-card {{ $pt === 'pay_what_you_want' ? 'active' : '' }}" role="button" tabindex="0">
            <div class="buyle-ptab-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </div>
            <div class="buyle-ptab-info">
                <div class="buyle-ptab-title">
                    <span>Bayar Seikhlasnya</span>
                    <span class="buyle-ptab-radio"></span>
                </div>
                <div class="buyle-ptab-desc">Pembeli bebas input nominal donasi / apresiasi</div>
            </div>
        </div>
    </div>

    {{-- Panel: Traktir Settings --}}
    <div id="ptab_panel_traktir" class="buyle-pricing-subpanel" style="display:{{ $pt === 'traktir' ? 'block' : 'none' }};">
        <div class="buyle-subpanel-head">
            <span style="font-size:1.15rem;">☕</span>
            <span>Konfigurasi Traktir Kreator</span>
        </div>
        <div class="buyle-subpanel-grid">
            <div>
                <label class="buyle-subpanel-label">Nama Satuan Traktir</label>
                <input type="text" id="traktir_unit_name_field" value="{{ $pUnit }}"
                    oninput="document.getElementById('traktir_unit_name_input').value=this.value; updateTraktirPreview();"
                    class="form-input" placeholder="Contoh: Kopi, Es Teh, Buku..."
                    style="font-family:'Montserrat',sans-serif; font-size:0.88rem;" maxlength="30">
                <span class="buyle-subpanel-hint">Satuan yang akan ditampilkan ke pembeli</span>
            </div>
            <div>
                <label class="buyle-subpanel-label">Harga per Satuan (Rp)</label>
                <input type="text" id="traktir_unit_price_field" value="{{ number_format((float)$pPrice, 0, ',', '.') }}"
                    oninput="syncTraktirPrice(this.value); updateTraktirPreview();"
                    class="form-input currency-input" placeholder="Contoh: 10.000"
                    style="font-family:'Montserrat',sans-serif; font-size:0.88rem;" autocomplete="off">
                <span class="buyle-subpanel-hint">Harga 1 satuan traktiran (min. Rp 1.000)</span>
            </div>
            <div style="grid-column: 1 / -1;">
                <label class="buyle-subpanel-label">Pilihan Ikon Satuan</label>
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                    @foreach(['coffee' => '☕', 'tea' => '🍵', 'meal' => '🍔', 'pizza' => '🍕', 'gift' => '🎁', 'heart' => '❤️'] as $iconKey => $iconEmoji)
                    <button type="button"
                        onclick="selectTraktirIcon('{{ $iconKey }}', '{{ $iconEmoji }}')"
                        id="traktir_icon_btn_{{ $iconKey }}"
                        class="buyle-icon-pill-btn {{ $pIcon === $iconKey ? 'active' : '' }}">
                        {{ $iconEmoji }}
                    </button>
                    @endforeach
                </div>
                <span class="buyle-subpanel-hint">Pilih ikon yang cocok dengan tema produk atau kreator</span>
            </div>
        </div>

        {{-- Preview --}}
        <div class="buyle-subpanel-preview">
            <span style="font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">Preview:</span>
            <span style="font-size:0.88rem; color:#0f172a; font-weight:800;" id="traktir_preview_text">
                ☕ 1 {{ $pUnit }} = Rp {{ number_format((float)$pPrice, 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- Panel: PWYW Settings --}}
    <div id="ptab_panel_pay_what_you_want" class="buyle-pricing-subpanel" style="display:{{ $pt === 'pay_what_you_want' ? 'block' : 'none' }};">
        <div class="buyle-subpanel-head">
            <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <span>Konfigurasi Bayar Seikhlasnya (PWYW)</span>
        </div>
        <div class="buyle-subpanel-grid">
            <div>
                <label class="buyle-subpanel-label">Nominal Minimum (Rp)</label>
                <input type="text" id="pwyw_min_price_field" value="{{ $pwywMin > 0 ? number_format((float)$pwywMin, 0, ',', '.') : '' }}"
                    oninput="syncPwywMin(this.value);"
                    class="form-input currency-input" placeholder="0 = bebas tanpa minimum"
                    style="font-family:'Montserrat',sans-serif; font-size:0.88rem;" autocomplete="off">
                <span class="buyle-subpanel-hint">Biarkan kosong atau isi 0 jika bebas berapa saja</span>
            </div>
            <div>
                <label class="buyle-subpanel-label">Saran Nominal / Rekomendasi (Rp)</label>
                <input type="text" id="pwyw_suggested_price_field" value="{{ $pwywSug ? number_format((float)$pwywSug, 0, ',', '.') : '' }}"
                    oninput="syncPwywSug(this.value);"
                    class="form-input currency-input" placeholder="Opsional (cth: 25.000)"
                    style="font-family:'Montserrat',sans-serif; font-size:0.88rem;" autocomplete="off">
                <span class="buyle-subpanel-hint">Akan otomatis terpilih sebagai nominal awal di storefront</span>
            </div>
        </div>
        <div style="margin-top:1rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.75rem 1rem; font-size:0.78rem; color:#475569; line-height:1.55;">
            <strong style="color:#0f172a;">Catatan:</strong> Pembeli dapat bebas mengisi nominal dukungan saat checkout (tetap diproses via Payment Gateway Midtrans). File digital atau akses otomatis dikirimkan begitu pembayaran berhasil.
        </div>
    </div>

    {{-- Panel: Free info --}}
    <div id="ptab_panel_free" class="buyle-pricing-subpanel" style="display:{{ $pt === 'free' ? 'block' : 'none' }};">
        <div style="font-size:0.82rem; color:#334155; line-height:1.6; display:flex; align-items:flex-start; gap:0.6rem;">
            <div style="width:24px; height:24px; border-radius:50%; background:rgba(30,179,73,0.12); color:#1eb349; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div>
                Produk ini akan berstatus <strong>Gratis</strong> (Rp 0). Anda dapat mengaktifkan opsi <strong>Wajib Follow Sosial Media / Gabung Grup</strong> pada bagian syarat di bawah untuk menumbuhkan audiens sebelum pembeli dapat mengklaim file.
            </div>
        </div>
    </div>

    {{-- Panel: Fixed info --}}
    <div id="ptab_panel_fixed" class="buyle-pricing-subpanel" style="display:{{ $pt === 'fixed' ? 'block' : 'none' }};">
        <div style="font-size:0.82rem; color:#334155; line-height:1.6; display:flex; align-items:flex-start; gap:0.6rem;">
            <div style="width:24px; height:24px; border-radius:50%; background:rgba(30,179,73,0.12); color:#1eb349; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div>
                Model harga standar. Silakan masukkan <strong>Harga Utama</strong> dan <strong>Harga Coret / Diskon</strong> (opsional) pada kolom input harga di atas.
            </div>
        </div>
    </div>
</div>

<script>
function switchPricingTab(tab) {
    // Update hidden input
    document.getElementById('pricing_type_input').value = tab;

    // Reset all tab cards
    ['fixed', 'free', 'traktir', 'pay_what_you_want'].forEach(t => {
        const btn = document.getElementById('ptab_' + t);
        const panel = document.getElementById('ptab_panel_' + t);
        if (btn) btn.classList.remove('active');
        if (panel) panel.style.display = 'none';
    });

    // Activate selected
    const activeBtn = document.getElementById('ptab_' + tab);
    if (activeBtn) activeBtn.classList.add('active');

    const activePanel = document.getElementById('ptab_panel_' + tab);
    if (activePanel) activePanel.style.display = 'block';

    // Show/hide price fields based on tab
    const priceWrap = document.querySelector('.form-group:has(#input_price)');
    const salePriceWrap = document.querySelector('.form-group:has(#input_sale_price)');
    const freeReqWrap = document.getElementById('freeProductRequirementsWrap');

    if (tab === 'fixed') {
        if (priceWrap) priceWrap.style.display = '';
        if (salePriceWrap) salePriceWrap.style.display = '';
        if (freeReqWrap) freeReqWrap.style.display = 'none';
    } else if (tab === 'free') {
        if (priceWrap) priceWrap.style.display = 'none';
        if (salePriceWrap) salePriceWrap.style.display = 'none';
        if (freeReqWrap) freeReqWrap.style.display = 'block';
    } else if (tab === 'traktir' || tab === 'pay_what_you_want') {
        if (priceWrap) priceWrap.style.display = 'none';
        if (salePriceWrap) salePriceWrap.style.display = 'none';
        if (freeReqWrap) freeReqWrap.style.display = 'none';
    }
}

function syncTraktirPrice(val) {
    const raw = val.replace(/[^\d]/g, '');
    document.getElementById('traktir_unit_price_input').value = raw;
}

function syncPwywMin(val) {
    const raw = val.replace(/[^\d]/g, '');
    document.getElementById('pwyw_min_price_input').value = raw;
}

function syncPwywSug(val) {
    const raw = val.replace(/[^\d]/g, '');
    document.getElementById('pwyw_suggested_price_input').value = raw;
}

function selectTraktirIcon(iconKey, iconEmoji) {
    document.getElementById('traktir_unit_icon_input').value = iconKey;
    document.querySelectorAll('[id^="traktir_icon_btn_"]').forEach(btn => {
        btn.classList.remove('active');
    });
    const active = document.getElementById('traktir_icon_btn_' + iconKey);
    if (active) active.classList.add('active');
    updateTraktirPreview();
}

function updateTraktirPreview() {
    const name = document.getElementById('traktir_unit_name_field')?.value || 'Kopi';
    const rawPrice = document.getElementById('traktir_unit_price_input')?.value || '10000';
    const price = parseInt(rawPrice.replace(/[^\d]/g, ''), 10) || 0;
    const icons = { coffee:'☕', tea:'🍵', meal:'🍔', pizza:'🍕', gift:'🎁', heart:'❤️' };
    const iconKey = document.getElementById('traktir_unit_icon_input')?.value || 'coffee';
    const emoji = icons[iconKey] || '☕';
    const el = document.getElementById('traktir_preview_text');
    if (el) {
        const fmt = price.toLocaleString('id-ID');
        el.textContent = emoji + ' 1 ' + name + ' = Rp ' + fmt;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const initialTab = document.getElementById('pricing_type_input')?.value || 'fixed';
    switchPricingTab(initialTab);
});
</script>
