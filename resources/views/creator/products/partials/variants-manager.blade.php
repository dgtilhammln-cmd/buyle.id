{{-- 
    VARIASI PRODUK MANAGER
    Berlaku untuk semua jenis produk: Digital, Tiket, Jasa, Fisik, Makanan.
    Maksimal 7 variasi per produk, layout compact & proporsional.
--}}
@php
    $initialVariants = [];
    if (old('variants')) {
        $initialVariants = old('variants');
    } elseif (isset($product) && $product && $product->variantOptions && $product->variantOptions->isNotEmpty()) {
        $firstOption = $product->variantOptions->first();
        foreach ($firstOption->values as $val) {
            $initialVariants[] = [
                'id'             => $val->id,
                'name'           => $val->value,
                'price'          => $val->price !== null ? (float)$val->price : null,
                'stock'          => $val->stock,
                'sku'            => $val->sku,
                'existing_image' => $val->image,
            ];
        }
    }
@endphp

<div id="productVariantsWrap" class="form-group full" style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; margin-top:0.75rem; margin-bottom:0.75rem; overflow:hidden; box-sizing:border-box;">
    
    {{-- Toggle Card Header (Style sama persis Affiliate & Lead Magnet) --}}
    <label style="display:flex; align-items:center; gap:0.75rem; padding:0.85rem 1rem; cursor:pointer; user-select:none; margin:0; background:#f8fafc;">
        <div style="position:relative; width:44px; height:24px; flex-shrink:0;">
            <input type="checkbox" id="productVariantsToggle"
                {{ count($initialVariants) > 0 ? 'checked' : '' }}
                style="opacity:0; width:0; height:0;"
                onchange="toggleVariantsSection(this.checked)">
            <span class="toggle-slider-s"></span>
        </div>
        <div style="flex:1;">
            <div style="font-size:0.88rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:0.45rem;">
                Variasi Produk (Opsional)
                <span id="variantCountBadge" style="font-size:0.7rem; font-weight:700; background:#e2e8f0; color:#475569; padding:0.1rem 0.5rem; border-radius:99px;">{{ count($initialVariants) }} / 7</span>
            </div>
            <div style="font-size:0.73rem; color:#64748b; line-height:1.4;">Tambahkan pilihan varian (maksimal 7 opsi). Setiap varian dapat memiliki harga dan stok khusus.</div>
        </div>
    </label>

    {{-- Content Variasi (muncul saat toggle ON) --}}
    <div id="variantsContentWrap" style="{{ count($initialVariants) > 0 ? '' : 'display:none;' }} border-top:1px solid #e2e8f0; padding:1.25rem 1.4rem; background:#ffffff;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.85rem; padding-bottom:0.75rem; border-bottom:1px dashed #e2e8f0; flex-wrap:wrap; gap:0.75rem;">
            <div style="font-size:0.82rem; font-weight:700; color:#334155;">
                Daftar Pilihan Varian Produk
            </div>
            <button type="button" onclick="addVariantRow()" id="btnAddVariant"
                style="background:linear-gradient(135deg, #1eb349, #16a34a); color:#fff; border:none; padding:0.45rem 0.95rem; border-radius:10px; font-weight:700; font-size:0.8rem; cursor:pointer; display:inline-flex; align-items:center; gap:6px; box-shadow:0 3px 10px rgba(30,179,73,0.22); transition:all 0.2s;"
                onmouseover="if(!this.disabled) this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                + Tambah Variasi
            </button>
        </div>

    {{-- Contoh Penggunaan Sesuai Tipe Produk yang Dipilih --}}
    <div id="variantTypeExampleBox" style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.6rem 0.85rem; margin-bottom:0.85rem; display:flex; align-items:center; gap:0.5rem; font-size:0.76rem; color:#166534; line-height:1.45;">
        <span style="display:inline-flex; align-items:center; justify-content:center; width:20px; height:20px; border-radius:6px; background:rgba(22,101,52,0.1); color:#15803d; flex-shrink:0;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        </span>
        <div id="variantTypeExampleContent">
            {{-- Diisi secara dinamis via JS berdasarkan pilihan Tipe Produk --}}
            <b>Contoh Variasi:</b> Paket Basic / Pro / VIP / Lifetime Access
        </div>
    </div>

    {{-- Tabel Variasi Compact & Proporsional --}}
    <div style="width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch;">
        <table style="width:100%; min-width:580px; border-collapse:separate; border-spacing:0 6px;">
            <thead>
                <tr style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.04em;">
                    <th style="width:44px; text-align:center; padding:0 4px 4px;">Foto</th>
                    <th style="padding:0 6px 4px; text-align:left;">Nama Variasi <span style="color:#ef4444;">*</span></th>
                    <th style="width:135px; padding:0 6px 4px; text-align:left;">Harga (Rp)</th>
                    <th style="width:90px; padding:0 6px 4px; text-align:left;">Stok</th>
                    <th style="width:90px; padding:0 6px 4px; text-align:left;">SKU</th>
                    <th style="width:36px; text-align:center; padding:0 4px 4px;"></th>
                </tr>
            </thead>
            <tbody id="variantsContainer">
                {{-- Baris dimasukkan dinamis via JavaScript --}}
            </tbody>
        </table>
    </div>

    <div id="noVariantsPlaceholder" style="display:none; text-align:center; padding:1.25rem 1rem; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:10px; color:#94a3b8; font-size:0.8rem; margin-top:4px;">
        Belum ada variasi. Klik tombol <b>"+ Tambah Variasi"</b> jika produk memiliki pilihan paket, opsi, ukuran, atau warna.
    </div>
    </div> {{-- end #variantsContentWrap --}}
</div> {{-- end #productVariantsWrap --}}

<style>
    .variant-tr-row td {
        background: #ffffff;
        border-top: 1.5px solid #e2e8f0;
        border-bottom: 1.5px solid #e2e8f0;
        padding: 6px;
        vertical-align: middle;
        transition: background 0.15s;
    }
    .variant-tr-row td:first-child {
        border-left: 1.5px solid #e2e8f0;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        text-align: center;
    }
    .variant-tr-row td:last-child {
        border-right: 1.5px solid #e2e8f0;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        text-align: center;
    }
    .variant-tr-row:hover td {
        background: #fcfdfc;
        border-color: #cbd5e1;
    }
    .variant-compact-input {
        width: 100%;
        height: 38px;
        padding: 0.45rem 0.65rem;
        font-size: 0.81rem;
        font-family: 'Montserrat', sans-serif;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #ffffff;
        color: #0f172a;
    }
    .variant-compact-input:focus {
        border-color: #1eb349;
        box-shadow: 0 0 0 2.5px rgba(30,179,73,0.12);
    }
    .variant-compact-input::placeholder {
        color: #94a3b8;
        font-size: 0.76rem;
    }
    .variant-img-upload-box {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        border: 1.5px dashed #cbd5e1;
        background: #f8fafc;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden;
        position: relative;
        margin: 0 auto;
        transition: all 0.2s;
    }
    .variant-img-upload-box:hover {
        border-color: #1eb349;
        background: #f0fdf4;
    }
    .variant-del-btn {
        width: 32px;
        height: 32px;
        border-radius: 7px;
        border: none;
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
    }
    .variant-del-btn:hover {
        background: rgba(239, 68, 68, 0.18);
        transform: scale(1.05);
    }
</style>

<script>
    const INITIAL_VARIANTS = @json($initialVariants);
    const MAX_VARIANTS = 7;

    const VARIANT_EXAMPLES = {
        'service': '<b>Contoh Variasi Jasa:</b> 1 Sesi (60 Menit) &bull; Paket Lengkap &bull; Express (1 Hari) &bull; Revisi Unlimited',
        'external_link': '<b>Contoh Variasi Digital:</b> Lisensi Personal &bull; Lisensi Komersial &bull; Paket Pro &bull; Lifetime Access',
        'digital': '<b>Contoh Variasi Digital:</b> Lisensi Personal &bull; Lisensi Komersial &bull; Paket Pro &bull; Lifetime Access',
        'ticket': '<b>Contoh Variasi Tiket:</b> Presale 1 &bull; Presale 2 &bull; VIP Pass &bull; Day 1 &bull; All Days Access',
        'physical': '<b>Contoh Variasi Fisik:</b> Merah - M &bull; Hitam - XL &bull; Bundle 3 Pcs &bull; Ukuran 500ml',
        'makanan': '<b>Contoh Variasi Makanan:</b> Regular &bull; Jumbo &bull; Level Pedas 1-5 &bull; Extra Topping Keju'
    };

    function updateVariantTypeExample(type) {
        const contentBox = document.getElementById('variantTypeExampleContent');
        if (!contentBox) return;

        const exampleHtml = VARIANT_EXAMPLES[type] || VARIANT_EXAMPLES['external_link'];
        contentBox.innerHTML = exampleHtml;
    }

    function updateVariantCountUI() {
        const container = document.getElementById('variantsContainer');
        const countBadge = document.getElementById('variantCountBadge');
        const btnAdd = document.getElementById('btnAddVariant');
        const placeholder = document.getElementById('noVariantsPlaceholder');

        const count = container ? container.children.length : 0;

        if (countBadge) {
            countBadge.innerText = `${count} / ${MAX_VARIANTS}`;
            if (count >= MAX_VARIANTS) {
                countBadge.style.background = '#fef2f2';
                countBadge.style.color = '#dc2626';
                countBadge.style.borderColor = '#fecaca';
            } else {
                countBadge.style.background = '#f1f5f9';
                countBadge.style.color = '#475569';
                countBadge.style.borderColor = '#e2e8f0';
            }
        }

        if (btnAdd) {
            if (count >= MAX_VARIANTS) {
                btnAdd.disabled = true;
                btnAdd.style.opacity = '0.55';
                btnAdd.style.cursor = 'not-allowed';
                btnAdd.title = 'Maksimal 7 variasi per produk';
            } else {
                btnAdd.disabled = false;
                btnAdd.style.opacity = '1';
                btnAdd.style.cursor = 'pointer';
                btnAdd.title = '';
            }
        }

        if (placeholder) {
            placeholder.style.display = count === 0 ? 'block' : 'none';
        }
    }

    function addVariantRow(data = null) {
        const container = document.getElementById('variantsContainer');
        if (!container) return;

        const currentCount = container.children.length;
        if (currentCount >= MAX_VARIANTS) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal Variasi',
                    text: `Maksimal hanya diperbolehkan ${MAX_VARIANTS} variasi per produk.`,
                    confirmButtonColor: '#1eb349'
                });
            } else {
                alert(`Maksimal ${MAX_VARIANTS} variasi per produk.`);
            }
            return;
        }

        const index = currentCount;
        const rowId = 'variant_row_' + index + '_' + Date.now();

        const vId = data && data.id ? data.id : '';
        const vName = data && data.name ? data.name : '';
        const vPrice = data && data.price !== null && data.price !== undefined ? data.price : '';
        const vStock = data && data.stock !== null && data.stock !== undefined ? data.stock : '';
        const vSku = data && data.sku ? data.sku : '';
        const vImg = data && data.existing_image ? data.existing_image : '';
        const vImgUrl = vImg ? (vImg.startsWith('http') ? vImg : ('{{ asset("storage") }}/' + vImg.replace(/^\/+/, ''))) : '';

        const tr = document.createElement('tr');
        tr.className = 'variant-tr-row';
        tr.id = rowId;

        tr.innerHTML = `
            {{-- Foto Thumbnail Box --}}
            <td>
                ${vId ? `<input type="hidden" name="variants[${index}][id]" value="${vId}">` : ''}
                <input type="hidden" name="variants[${index}][existing_image]" value="${vImg}">
                <label class="variant-img-upload-box" title="Foto varian (opsional)">
                    <input type="file" name="variants[${index}][image]" accept="image/*" style="display:none;" onchange="previewVariantImage(this, '${rowId}_img')">
                    <img id="${rowId}_img" src="${vImgUrl}" style="${vImgUrl ? 'display:block;' : 'display:none;'} width:100%; height:100%; object-fit:cover;">
                    <div id="${rowId}_ph" style="${vImgUrl ? 'display:none;' : 'display:flex; flex-direction:column; align-items:center; justify-content:center;'}">
                        <svg width="15" height="15" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                </label>
            </td>

            {{-- Nama Variasi --}}
            <td>
                <input type="text" name="variants[${index}][name]" value="${escapeHtml(vName)}" class="variant-compact-input" placeholder="Nama variasi (cth: XL / VIP / Sesi 1)" required autocomplete="off">
            </td>

            {{-- Harga Khusus --}}
            <td>
                <input type="text" name="variants[${index}][price]" value="${vPrice ? formatNumber(vPrice) : ''}" class="variant-compact-input currency-input" placeholder="Ikut harga utama" autocomplete="off" oninput="formatCurrencyInput(this)">
            </td>

            {{-- Stok --}}
            <td>
                <input type="number" name="variants[${index}][stock]" value="${vStock}" class="variant-compact-input" placeholder="∞ (Unlimited)" min="0" step="1">
            </td>

            {{-- SKU --}}
            <td>
                <input type="text" name="variants[${index}][sku]" value="${escapeHtml(vSku)}" class="variant-compact-input" placeholder="Opsional">
            </td>

            {{-- Tombol Hapus --}}
            <td>
                <button type="button" onclick="removeVariantRow('${rowId}')" class="variant-del-btn" title="Hapus varian ini">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
            </td>
        `;

        container.appendChild(tr);
        updateVariantCountUI();
    }

    function removeVariantRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
        }
        updateVariantCountUI();
    }

    function previewVariantImage(input, imgId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(imgId);
                const ph = document.getElementById(imgId.replace('_img', '_ph'));
                if (img) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                if (ph) {
                    ph.style.display = 'none';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function formatCurrencyInput(input) {
        let val = input.value.replace(/[^\d]/g, '');
        if (val) {
            input.value = new Intl.NumberFormat('id-ID').format(val);
        } else {
            input.value = '';
        }
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Auto-populate on DOM load and attach type listener
    document.addEventListener('DOMContentLoaded', function() {
        if (Array.isArray(INITIAL_VARIANTS) && INITIAL_VARIANTS.length > 0) {
            INITIAL_VARIANTS.forEach(v => addVariantRow(v));
        }
        updateVariantCountUI();

        // Listen to product type select changes
        const pTypeSelect = document.getElementById('productTypeSelect');
        if (pTypeSelect) {
            updateVariantTypeExample(pTypeSelect.value);
            pTypeSelect.addEventListener('change', function() {
                updateVariantTypeExample(this.value);
            });
        }

        // Handle form submit when variants toggle is OFF
        const pForm = document.querySelector('form.prod-form') || document.getElementById('productForm') || document.querySelector('form[action*="products"]');
        if (pForm) {
            pForm.addEventListener('submit', function() {
                const toggle = document.getElementById('productVariantsToggle');
                if (toggle && !toggle.checked) {
                    const inputs = document.querySelectorAll('#variantsContentWrap input, #variantsContentWrap select');
                    inputs.forEach(inp => inp.disabled = true);
                }
            });
        }
    });

    window.toggleVariantsSection = function(active) {
        const wrap = document.getElementById('variantsContentWrap');
        if (!wrap) return;
        wrap.style.display = active ? 'block' : 'none';
        if (active && variantsCount === 0) {
            addVariantRow();
        }
    };
</script>
