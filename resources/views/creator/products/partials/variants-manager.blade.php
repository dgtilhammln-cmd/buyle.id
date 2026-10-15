{{-- 
    VARIASI PRODUK MANAGER
    Berlaku untuk semua jenis produk: Digital, Tiket, Jasa, Fisik, Makanan.
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

<div class="form-group full" style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:18px; padding:1.35rem 1.6rem; margin-top:0.75rem; margin-bottom:1.25rem; box-shadow:0 4px 16px rgba(15,23,42,0.02);">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; border-bottom:1px dashed #cbd5e1; padding-bottom:1rem; flex-wrap:wrap; gap:0.75rem;">
        <div>
            <div style="font-size:1rem; font-weight:800; color:#0f172a; display:flex; align-items:center; gap:0.5rem;">
                <span style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:8px; background:rgba(30,179,73,0.12); color:#1eb349;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </span>
                Variasi Produk (Opsional - Maksimal 20 Variasi)
                <span style="font-size:0.68rem; font-weight:700; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; padding:0.15rem 0.55rem; border-radius:99px;">Semua Tipe Produk</span>
            </div>
            <span class="form-hint" style="font-size:0.78rem; color:#64748b; margin-top:4px; display:block; line-height:1.45;">
                Tambahkan pilihan varian seperti ukuran, warna, paket lisensi, porsi menu, atau tipe tiket dengan harga & stok masing-masing.
            </span>
        </div>
        <button type="button" onclick="addVariantRow()" id="btnAddVariant"
            style="background:linear-gradient(135deg, #1eb349, #16a34a); color:#fff; border:none; padding:0.55rem 1.1rem; border-radius:10px; font-weight:700; font-size:0.82rem; cursor:pointer; display:inline-flex; align-items:center; gap:6px; box-shadow:0 3px 10px rgba(30,179,73,0.25); transition:all 0.2s;"
            onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            + Tambah Variasi
        </button>
    </div>

    {{-- Type Examples Guide --}}
    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.75rem 1rem; margin-bottom:1.1rem; display:flex; flex-direction:column; gap:0.35rem; font-size:0.75rem; color:#64748b;">
        <div style="font-weight:700; color:#334155; display:flex; align-items:center; gap:0.35rem;">
            <svg width="14" height="14" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            Contoh Penggunaan Variasi di Setiap Tipe Produk:
        </div>
        <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-top:2px;">
            <span style="background:#fff; border:1px solid #cbd5e1; padding:0.2rem 0.6rem; border-radius:6px;"><b>Digital:</b> Basic / Pro / VIP / Lifetime</span>
            <span style="background:#fff; border:1px solid #cbd5e1; padding:0.2rem 0.6rem; border-radius:6px;"><b>Tiket:</b> Presale 1 / Presale 2 / VIP Pass / Day 1</span>
            <span style="background:#fff; border:1px solid #cbd5e1; padding:0.2rem 0.6rem; border-radius:6px;"><b>Fisik:</b> Merah - M / Hitam - XL / Bundle 3 Pcs</span>
            <span style="background:#fff; border:1px solid #cbd5e1; padding:0.2rem 0.6rem; border-radius:6px;"><b>Makanan:</b> Regular / Jumbo / Level 1-5 / Extra Keju</span>
            <span style="background:#fff; border:1px solid #cbd5e1; padding:0.2rem 0.6rem; border-radius:6px;"><b>Jasa:</b> 1 Sesi (60 Menit) / Paket Lengkap / Express</span>
        </div>
    </div>

    <div id="variantsContainer" style="display:flex; flex-direction:column; gap:0.85rem;">
        {{-- Rows dimasukkan secara dinamis via JavaScript --}}
    </div>

    <div id="noVariantsPlaceholder" style="display:none; text-align:center; padding:1.5rem 1rem; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:12px; color:#94a3b8; font-size:0.82rem;">
        Belum ada variasi. Klik tombol <b>"+ Tambah Variasi"</b> jika produk memiliki pilihan opsi, ukuran, paket, atau warna.
    </div>
</div>

<style>
    .variant-row-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.9rem 1.1rem;
        display: grid;
        grid-template-columns: 58px 1fr 140px 100px 100px 36px;
        gap: 0.75rem;
        align-items: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .variant-row-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    .variant-img-upload-box {
        position: relative;
        width: 54px;
        height: 54px;
        border-radius: 10px;
        border: 1.5px dashed #cbd5e1;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.2s;
    }
    .variant-img-upload-box:hover {
        border-color: #1eb349;
        background: #f0fdf4;
    }
    .variant-img-upload-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .variant-input {
        width: 100%;
        padding: 0.55rem 0.75rem;
        font-size: 0.82rem;
        font-family: 'Montserrat', sans-serif;
        border: 1.5px solid #e2e8f0;
        border-radius: 9px;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.2s;
        background: #ffffff;
        color: #0f172a;
    }
    .variant-input:focus {
        border-color: #1eb349;
        box-shadow: 0 0 0 3px rgba(30,179,73,0.12);
    }
    .variant-input::placeholder {
        color: #94a3b8;
    }
    .variant-del-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .variant-del-btn:hover {
        background: rgba(239, 68, 68, 0.18);
        transform: scale(1.05);
    }
    @media (max-width: 900px) {
        .variant-row-card {
            grid-template-columns: 54px 1fr 1fr;
            gap: 0.6rem;
            padding: 0.85rem;
        }
        .variant-row-card .full-mobile {
            grid-column: 1 / -1;
        }
    }
</style>

<script>
    const INITIAL_VARIANTS = @json($initialVariants);

    function addVariantRow(data = null) {
        const container = document.getElementById('variantsContainer');
        const placeholder = document.getElementById('noVariantsPlaceholder');
        if (!container) return;

        const currentCount = container.children.length;
        if (currentCount >= 20) {
            alert('Maksimal 20 variasi produk.');
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

        const row = document.createElement('div');
        row.className = 'variant-row-card';
        row.id = rowId;

        row.innerHTML = `
            ${vId ? `<input type="hidden" name="variants[${index}][id]" value="${vId}">` : ''}
            <input type="hidden" name="variants[${index}][existing_image]" value="${vImg}">
            
            {{-- Image Box --}}
            <label class="variant-img-upload-box" title="Upload foto varian (opsional)">
                <input type="file" name="variants[${index}][image]" accept="image/*" style="display:none;" onchange="previewVariantImage(this, '${rowId}_img')">
                <img id="${rowId}_img" src="${vImgUrl}" style="${vImgUrl ? 'display:block;' : 'display:none;'}">
                <div id="${rowId}_ph" style="${vImgUrl ? 'display:none;' : 'display:flex; flex-direction:column; align-items:center; justify-content:center;'}">
                    <svg width="18" height="18" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <span style="font-size:8px; color:#94a3b8; font-weight:700; margin-top:2px;">FOTO</span>
                </div>
            </label>

            {{-- Nama Varian --}}
            <div>
                <input type="text" name="variants[${index}][name]" value="${escapeHtml(vName)}" class="variant-input" placeholder="Nama Varian (cth: Merah - XL / Paket VIP / Jumbo)" required autocomplete="off">
            </div>

            {{-- Harga Khusus --}}
            <div>
                <input type="text" name="variants[${index}][price]" value="${vPrice ? formatNumber(vPrice) : ''}" class="variant-input currency-input" placeholder="Harga (Rp) / Ikut Utama" autocomplete="off" oninput="formatCurrencyInput(this)">
            </div>

            {{-- Stok --}}
            <div>
                <input type="number" name="variants[${index}][stock]" value="${vStock}" class="variant-input" placeholder="Stok (∞)" min="0" step="1">
            </div>

            {{-- SKU --}}
            <div>
                <input type="text" name="variants[${index}][sku]" value="${escapeHtml(vSku)}" class="variant-input" placeholder="SKU (opsional)">
            </div>

            {{-- Hapus --}}
            <div style="text-align:center;">
                <button type="button" onclick="removeVariantRow('${rowId}')" class="variant-del-btn" title="Hapus varian ini">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
            </div>
        `;

        container.appendChild(row);
        if (placeholder) placeholder.style.display = 'none';
    }

    function removeVariantRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
        }
        const container = document.getElementById('variantsContainer');
        const placeholder = document.getElementById('noVariantsPlaceholder');
        if (container && container.children.length === 0 && placeholder) {
            placeholder.style.display = 'block';
        }
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

    // Auto-populate on DOM load
    document.addEventListener('DOMContentLoaded', function() {
        if (Array.isArray(INITIAL_VARIANTS) && INITIAL_VARIANTS.length > 0) {
            INITIAL_VARIANTS.forEach(v => addVariantRow(v));
        } else {
            const placeholder = document.getElementById('noVariantsPlaceholder');
            if (placeholder) placeholder.style.display = 'block';
        }
    });
</script>
