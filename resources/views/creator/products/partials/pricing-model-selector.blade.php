{{--
  PRICING MODEL SELECTOR
  Ditampilkan di create & edit product form.
  Variabel yang perlu disediakan: $product (bisa null untuk create)
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

<div class="form-group full" id="pricing-model-wrap" style="margin-top:0.75rem; margin-bottom:0.75rem;">
    <label class="form-label" style="font-weight:700; color:#0f172a; margin-bottom:0.65rem; display:block;">
        Model Harga Produk
    </label>

    {{-- Tabs Pilihan --}}
    <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:0.6rem; margin-bottom:1rem;" id="pricing-tab-grid">
        {{-- Fixed --}}
        <button type="button" id="ptab_fixed"
            onclick="switchPricingTab('fixed')"
            class="ptab-btn {{ $pt === 'fixed' ? 'ptab-active' : '' }}"
            style="{{ $pt === 'fixed' ? 'background:#f0fdf4; border-color:#1eb349; color:#15803d;' : 'background:#f8fafc; border-color:#e2e8f0; color:#475569;' }} border:1.5px solid; border-radius:12px; padding:0.65rem 0.75rem; cursor:pointer; font-size:0.82rem; font-weight:700; display:flex; align-items:center; gap:0.5rem; transition:all 0.18s; text-align:left;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0;">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
            <div>
                <div>Harga Tetap</div>
                <div style="font-size:0.7rem; font-weight:500; opacity:0.75; margin-top:1px;">Pembeli bayar harga yang ditetapkan</div>
            </div>
        </button>

        {{-- Free --}}
        <button type="button" id="ptab_free"
            onclick="switchPricingTab('free')"
            class="ptab-btn {{ $pt === 'free' ? 'ptab-active' : '' }}"
            style="{{ $pt === 'free' ? 'background:#eff6ff; border-color:#3b82f6; color:#1d4ed8;' : 'background:#f8fafc; border-color:#e2e8f0; color:#475569;' }} border:1.5px solid; border-radius:12px; padding:0.65rem 0.75rem; cursor:pointer; font-size:0.82rem; font-weight:700; display:flex; align-items:center; gap:0.5rem; transition:all 0.18s; text-align:left;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0;">
                <circle cx="12" cy="12" r="10"/>
                <path d="M8 12h8M12 8v8"/>
            </svg>
            <div>
                <div>Gratis (Wajib Follow)</div>
                <div style="font-size:0.7rem; font-weight:500; opacity:0.75; margin-top:1px;">Gratis setelah pembeli follow / join</div>
            </div>
        </button>

        {{-- Traktir --}}
        <button type="button" id="ptab_traktir"
            onclick="switchPricingTab('traktir')"
            class="ptab-btn {{ $pt === 'traktir' ? 'ptab-active' : '' }}"
            style="{{ $pt === 'traktir' ? 'background:#fff7ed; border-color:#f97316; color:#c2410c;' : 'background:#f8fafc; border-color:#e2e8f0; color:#475569;' }} border:1.5px solid; border-radius:12px; padding:0.65rem 0.75rem; cursor:pointer; font-size:0.82rem; font-weight:700; display:flex; align-items:center; gap:0.5rem; transition:all 0.18s; text-align:left;">
            <span style="font-size:1.1rem; line-height:1;">☕</span>
            <div>
                <div>Traktir</div>
                <div style="font-size:0.7rem; font-weight:500; opacity:0.75; margin-top:1px;">Pembeli pilih jumlah kopi / item traktiran</div>
            </div>
        </button>

        {{-- PWYW --}}
        <button type="button" id="ptab_pay_what_you_want"
            onclick="switchPricingTab('pay_what_you_want')"
            class="ptab-btn {{ $pt === 'pay_what_you_want' ? 'ptab-active' : '' }}"
            style="{{ $pt === 'pay_what_you_want' ? 'background:#fdf4ff; border-color:#a855f7; color:#7e22ce;' : 'background:#f8fafc; border-color:#e2e8f0; color:#475569;' }} border:1.5px solid; border-radius:12px; padding:0.65rem 0.75rem; cursor:pointer; font-size:0.82rem; font-weight:700; display:flex; align-items:center; gap:0.5rem; transition:all 0.18s; text-align:left;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0;">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <div>
                <div>Bayar Seikhlasnya</div>
                <div style="font-size:0.7rem; font-weight:500; opacity:0.75; margin-top:1px;">Pembeli bebas tentukan nominal donasi</div>
            </div>
        </button>
    </div>

    {{-- Panel: Traktir Settings --}}
    <div id="ptab_panel_traktir" style="display:{{ $pt === 'traktir' ? 'block' : 'none' }}; background:#fff7ed; border:1.5px solid #fed7aa; border-radius:14px; padding:1.1rem 1.2rem; margin-bottom:0.5rem;">
        <div style="font-size:0.8rem; color:#9a3412; font-weight:700; margin-bottom:0.85rem; display:flex; align-items:center; gap:0.45rem;">
            <span>☕</span> Pengaturan Traktir
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.85rem;">
            <div>
                <label style="font-size:0.77rem; font-weight:700; color:#7c2d12; display:block; margin-bottom:0.3rem;">Nama Satuan Traktiran</label>
                <input type="text" id="traktir_unit_name_field" value="{{ $pUnit }}"
                    oninput="document.getElementById('traktir_unit_name_input').value=this.value; updateTraktirPreview();"
                    class="form-input" placeholder="cth: Kopi, Es Teh, Pizza..."
                    style="background:#fff; font-size:0.83rem;" maxlength="30">
                <span style="font-size:0.7rem; color:#9a3412; opacity:0.8;">Nama yang tampil ke pembeli</span>
            </div>
            <div>
                <label style="font-size:0.77rem; font-weight:700; color:#7c2d12; display:block; margin-bottom:0.3rem;">Harga per Satuan (Rp)</label>
                <input type="text" id="traktir_unit_price_field" value="{{ number_format((float)$pPrice, 0, ',', '.') }}"
                    oninput="syncTraktirPrice(this.value); updateTraktirPreview();"
                    class="form-input currency-input" placeholder="cth: 10.000"
                    style="background:#fff; font-size:0.83rem;" autocomplete="off">
                <span style="font-size:0.7rem; color:#9a3412; opacity:0.8;">Harga 1 satuan (min. Rp 1.000)</span>
            </div>
            <div style="grid-column:1/-1;">
                <label style="font-size:0.77rem; font-weight:700; color:#7c2d12; display:block; margin-bottom:0.3rem;">Ikon Satuan</label>
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                    @foreach(['coffee' => '☕', 'tea' => '🍵', 'meal' => '🍔', 'pizza' => '🍕', 'gift' => '🎁', 'heart' => '❤️'] as $iconKey => $iconEmoji)
                    <button type="button"
                        onclick="selectTraktirIcon('{{ $iconKey }}', '{{ $iconEmoji }}')"
                        id="traktir_icon_btn_{{ $iconKey }}"
                        style="{{ $pIcon === $iconKey ? 'background:#f97316; color:#fff; border-color:#f97316;' : 'background:#fff; color:#7c2d12; border-color:#fed7aa;' }} border:1.5px solid; border-radius:8px; padding:0.35rem 0.6rem; font-size:1rem; cursor:pointer; transition:all 0.15s;">
                        {{ $iconEmoji }}
                    </button>
                    @endforeach
                </div>
                <span style="font-size:0.7rem; color:#9a3412; opacity:0.8; margin-top:0.3rem; display:block;">Ikon yang tampil di samping nama traktiran</span>
            </div>
        </div>
        {{-- Preview --}}
        <div id="traktir_preview" style="margin-top:0.85rem; background:#fff; border:1px solid #fed7aa; border-radius:10px; padding:0.7rem 0.9rem; display:flex; align-items:center; gap:0.7rem;">
            <span style="font-size:0.75rem; color:#9a3412; font-weight:700;">Preview:</span>
            <span style="font-size:0.82rem; color:#c2410c; font-weight:700;" id="traktir_preview_text">
                ☕ 1 {{ $pUnit }} = Rp {{ number_format((float)$pPrice, 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- Panel: PWYW Settings --}}
    <div id="ptab_panel_pay_what_you_want" style="display:{{ $pt === 'pay_what_you_want' ? 'block' : 'none' }}; background:#fdf4ff; border:1.5px solid #e9d5ff; border-radius:14px; padding:1.1rem 1.2rem; margin-bottom:0.5rem;">
        <div style="font-size:0.8rem; color:#6b21a8; font-weight:700; margin-bottom:0.85rem; display:flex; align-items:center; gap:0.45rem;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            Pengaturan Bayar Seikhlasnya
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.85rem;">
            <div>
                <label style="font-size:0.77rem; font-weight:700; color:#6b21a8; display:block; margin-bottom:0.3rem;">Nominal Minimum (Rp)</label>
                <input type="text" id="pwyw_min_price_field" value="{{ $pwywMin > 0 ? number_format((float)$pwywMin, 0, ',', '.') : '' }}"
                    oninput="syncPwywMin(this.value);"
                    class="form-input currency-input" placeholder="0 = gratis terserah"
                    style="background:#fff; font-size:0.83rem;" autocomplete="off">
                <span style="font-size:0.7rem; color:#7e22ce; opacity:0.8;">Isi 0 jika bebas tanpa minimum</span>
            </div>
            <div>
                <label style="font-size:0.77rem; font-weight:700; color:#6b21a8; display:block; margin-bottom:0.3rem;">Nominal Saran (Rp)</label>
                <input type="text" id="pwyw_suggested_price_field" value="{{ $pwywSug ? number_format((float)$pwywSug, 0, ',', '.') : '' }}"
                    oninput="syncPwywSug(this.value);"
                    class="form-input currency-input" placeholder="Opsional, saran nominal"
                    style="background:#fff; font-size:0.83rem;" autocomplete="off">
                <span style="font-size:0.7rem; color:#7e22ce; opacity:0.8;">Ditampilkan sebagai saran default ke pembeli</span>
            </div>
        </div>
        <div style="margin-top:0.75rem; background:#fff; border:1px solid #e9d5ff; border-radius:10px; padding:0.65rem 0.85rem; font-size:0.75rem; color:#7e22ce; line-height:1.55;">
            <strong>💡 Cara kerja:</strong> Pembeli bebas isi nominal berapa saja (di atas minimum). Anda tetap mendapatkan pembayaran melalui Midtrans. File/konten dikirim otomatis setelah pembayaran berhasil.
        </div>
    </div>

    {{-- Panel: Free info --}}
    <div id="ptab_panel_free" style="display:{{ $pt === 'free' ? 'block' : 'none' }}; background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:14px; padding:0.85rem 1rem; margin-bottom:0.5rem;">
        <div style="font-size:0.78rem; color:#1d4ed8; line-height:1.55; display:flex; align-items:flex-start; gap:0.5rem;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div>Produk gratis — Anda bisa wajibkan pembeli <strong>follow akun / join komunitas</strong> sebelum dapat produk. Atur opsi follow/join di bawah.</div>
        </div>
    </div>

    {{-- Panel: Fixed info --}}
    <div id="ptab_panel_fixed" style="display:{{ $pt === 'fixed' ? 'block' : 'none' }}; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:14px; padding:0.85rem 1rem; margin-bottom:0.5rem;">
        <div style="font-size:0.78rem; color:#15803d; line-height:1.55; display:flex; align-items:flex-start; gap:0.5rem;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            <div>Pembeli membayar harga yang sudah Anda tentukan. Anda bisa set harga normal dan harga diskon/promo di atas.</div>
        </div>
    </div>
</div>

<script>
function switchPricingTab(tab) {
    // Update hidden input
    document.getElementById('pricing_type_input').value = tab;

    // Reset all tab buttons
    const colors = {
        fixed:            { bg: '#f0fdf4', border: '#1eb349',  color: '#15803d'  },
        free:             { bg: '#eff6ff', border: '#3b82f6',  color: '#1d4ed8'  },
        traktir:          { bg: '#fff7ed', border: '#f97316',  color: '#c2410c'  },
        pay_what_you_want:{ bg: '#fdf4ff', border: '#a855f7',  color: '#7e22ce'  },
    };
    const inactiveStyle = 'background:#f8fafc; border-color:#e2e8f0; color:#475569;';
    ['fixed', 'free', 'traktir', 'pay_what_you_want'].forEach(t => {
        const btn = document.getElementById('ptab_' + t);
        const panel = document.getElementById('ptab_panel_' + t);
        if (btn) btn.style.cssText = btn.style.cssText.replace(/background[^;]+;|border-color[^;]+;|color[^;]+;/g, '') + inactiveStyle;
        if (panel) panel.style.display = 'none';
    });

    // Activate selected
    const c = colors[tab];
    const activeBtn = document.getElementById('ptab_' + tab);
    if (activeBtn) {
        activeBtn.style.background = c.bg;
        activeBtn.style.borderColor = c.border;
        activeBtn.style.color = c.color;
    }
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
    // Reset all icon buttons
    document.querySelectorAll('[id^="traktir_icon_btn_"]').forEach(btn => {
        btn.style.background = '#fff';
        btn.style.color = '#7c2d12';
        btn.style.borderColor = '#fed7aa';
    });
    const active = document.getElementById('traktir_icon_btn_' + iconKey);
    if (active) {
        active.style.background = '#f97316';
        active.style.color = '#fff';
        active.style.borderColor = '#f97316';
    }
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
