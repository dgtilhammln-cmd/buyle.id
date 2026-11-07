@extends('creator.layout')

@section('title', isset($group) ? 'Edit Kelompok' : 'Buat Kelompok Baru')
@section('page_title', isset($group) ? 'Edit Kelompok' : 'Buat Kelompok Baru')
@section('breadcrumb', 'Katalog › Kelompok › ' . (isset($group) ? 'Edit' : 'Baru'))

@section('styles')
<style>
    .form-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e7f0e7;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        overflow: hidden;
        max-width: 680px;
        width: 100%;
        box-sizing: border-box;
    }
    .form-body {
        padding: 1.5rem;
        box-sizing: border-box;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 1.25rem;
    }
    .form-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #374151;
    }
    .form-input {
        height: 44px;
        padding: 0 1rem;
        border: 1.5px solid #e7f0e7;
        border-radius: 10px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.875rem;
        color: #1a1a1a;
        background: #f9fefb;
        outline: none;
        transition: all 0.2s;
        box-sizing: border-box;
        width: 100%;
    }
    .form-input:focus {
        border-color: #1eb349;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30,179,73,0.1);
    }
    .form-hint {
        font-size: 0.72rem;
        color: #94A3B8;
        line-height: 1.4;
    }
    .form-error {
        font-size: 0.72rem;
        color: #ef4444;
    }

    /* Product Selection Card & List */
    .group-prod-wrapper {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        padding: 0.65rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        max-height: 320px;
        overflow-y: auto;
        scrollbar-width: thin;
        box-sizing: border-box;
    }
    .group-prod-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.6rem 0.85rem;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }
    .group-prod-item:hover {
        background: #fcfdfc;
        border-color: #cbd5e1;
    }
    .group-prod-item.is-checked {
        background: #f0fdf4;
        border-color: #1eb349;
    }
    .group-prod-checkbox {
        width: 18px;
        height: 18px;
        accent-color: #1eb349;
        cursor: pointer;
        flex-shrink: 0;
    }

    /* Submit bar */
    .form-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f3f7f3;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
        box-sizing: border-box;
    }
    .btn-cancel {
        height: 42px;
        padding: 0 1.25rem;
        border-radius: 10px;
        border: 1.5px solid #e7f0e7;
        background: #fff;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: #64748B;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s;
        cursor: pointer;
    }
    .btn-cancel:hover {
        border-color: #94A3B8;
        color: #374151;
    }
    .btn-submit {
        height: 42px;
        padding: 0 1.5rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #1eb349, #15803d);
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(30,179,73,0.3);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30,179,73,0.4);
    }

    .toggle-switch {
        position: relative;
        width: 40px;
        height: 22px;
        flex-shrink: 0;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider-s {
        position: absolute;
        inset: 0;
        background: #cbd5e1;
        border-radius: 22px;
        cursor: pointer;
        transition: 0.3s;
    }
    .toggle-slider-s::before {
        content: '';
        position: absolute;
        left: 3px;
        top: 3px;
        width: 16px;
        height: 16px;
        background: #fff;
        border-radius: 50%;
        transition: 0.3s;
    }
    .toggle-switch input:checked + .toggle-slider-s {
        background: #1eb349;
    }
    .toggle-switch input:checked + .toggle-slider-s::before {
        transform: translateX(18px);
    }

    @media (max-width: 640px) {
        .form-body {
            padding: 1.1rem;
        }
        .form-footer {
            padding: 0.85rem 1.1rem;
            flex-direction: column-reverse;
            gap: 0.5rem;
        }
        .btn-cancel,
        .btn-submit {
            width: 100%;
            justify-content: center;
        }
        .group-prod-wrapper {
            max-height: 260px;
        }
    }
</style>
@endsection

@section('content')
@php $isEdit = isset($group); @endphp
<div class="form-card">
    <form action="{{ $isEdit ? route('creator.groups.update', $group) : route('creator.groups.store') }}" method="POST">
        @csrf
        @if($isEdit) @method('PUT') @endif
        
        <div class="form-body">
            {{-- Nama Kelompok --}}
            <div class="form-group">
                <label class="form-label">Nama Kelompok Produk <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" value="{{ old('name', $group->name ?? '') }}" class="form-input" placeholder="Misal: Template Premium" required>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label class="form-label">Deskripsi Singkat (Opsional)</label>
                <textarea name="description" class="form-input" style="height:auto; padding-top:0.75rem; padding-bottom:0.75rem;" rows="3" placeholder="Deskripsi singkat kelompok produk untuk keperluan pencarian..." maxlength="2000">{{ old('description', $group->description ?? '') }}</textarea>
                <span class="form-hint">Digunakan untuk informasi dan deskripsi halaman katalog kelompok ini.</span>
                @error('description')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            {{-- Urutan Tampil --}}
            <div class="form-group">
                <label class="form-label">Urutan Tampil (Opsional)</label>
                <input type="number" name="order" value="{{ old('order', $group->order ?? 0) }}" class="form-input" placeholder="0">
                <span class="form-hint">Angka lebih kecil akan tampil lebih dulu di katalog toko.</span>
                @error('order')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            {{-- Status Aktif --}}
            <div class="form-group" style="flex-direction:row; align-items:center; gap:0.75rem; margin-top:0.5rem; margin-bottom:1.5rem;">
                <label class="toggle-switch">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $group->is_active ?? true) ? 'checked' : '' }}>
                    <span class="toggle-slider-s"></span>
                </label>
                <div>
                    <div class="form-label">Kelompok Aktif</div>
                    <div class="form-hint">Tampilkan kelompok ini di halaman toko Anda</div>
                </div>
            </div>

            {{-- Divider --}}
            <div style="height:1px; background:#e7f0e7; margin:0.5rem 0 1.25rem;"></div>

            {{-- Pilihan Produk Langsung --}}
            <div class="form-group">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.35rem; flex-wrap:wrap; gap:0.5rem;">
                    <div>
                        <label class="form-label" style="margin-bottom:0;">Pilih Produk dalam Kelompok</label>
                        <div class="form-hint">Centang produk yang ingin dimasukkan ke dalam kelompok ini.</div>
                    </div>
                    <span style="font-size:0.72rem; font-weight:700; color:#166534; background:#f0fdf4; border:1px solid #bbf7d0; padding:2px 8px; border-radius:999px;">
                        <span id="selectedProductCount">0</span> produk terpilih
                    </span>
                </div>

                {{-- Filter Pencarian & Tombol Pilih Semua --}}
                <div style="display:flex; gap:0.5rem; margin-bottom:0.65rem; align-items:center;">
                    <div style="position:relative; flex:1;">
                        <svg width="14" height="14" fill="none" stroke="#94a3b8" stroke-width="2.2" viewBox="0 0 24 24" style="position:absolute; left:11px; top:50%; transform:translateY(-50%); pointer-events:none;">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="productSearchFilter" placeholder="Cari nama produk..." oninput="filterGroupProducts(this.value)"
                            style="width:100%; height:38px; padding-left:32px; padding-right:10px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:0.78rem; background:#ffffff; color:#0f172a; outline:none; box-sizing:border-box; font-family:inherit;">
                    </div>
                    <button type="button" onclick="toggleSelectAllProducts()" id="btnToggleSelectAll"
                        style="height:38px; padding:0 0.85rem; border-radius:9px; border:1.5px solid #e2e8f0; background:#ffffff; font-size:0.75rem; font-weight:700; color:#475569; cursor:pointer; white-space:nowrap; transition:all 0.15s; font-family:inherit;">
                        Pilih Semua
                    </button>
                </div>

                {{-- Daftar Produk --}}
                <div class="group-prod-wrapper" id="groupProductListContainer">
                    @forelse($products as $prod)
                        @php
                            $isChecked = in_array($prod->id, old('product_ids', $selectedProductIds ?? []));
                            $currentGroup = $prod->creatorGroup;
                            $inOtherGroup = $currentGroup && (!isset($group) || $currentGroup->id !== $group->id);
                        @endphp
                        <label class="group-prod-item {{ $isChecked ? 'is-checked' : '' }}" data-name="{{ strtolower($prod->name) }}" onclick="handleItemClick(event, this)">
                            <div style="display:flex; align-items:center; gap:0.65rem; min-width:0; flex:1;">
                                <input type="checkbox" name="product_ids[]" value="{{ $prod->id }}" class="group-prod-checkbox" {{ $isChecked ? 'checked' : '' }} onchange="updateCheckedState(this)">
                                
                                @if($prod->image)
                                    <img src="{{ asset('storage/' . $prod->image) }}" alt="" style="width:34px; height:34px; border-radius:7px; object-fit:cover; border:1px solid #e2e8f0; flex-shrink:0;">
                                @else
                                    <div style="width:34px; height:34px; border-radius:7px; background:#f1f5f9; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; color:#94a3b8; flex-shrink:0;">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    </div>
                                @endif

                                <div style="min-width:0; flex:1;">
                                    <div style="font-size:0.8rem; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $prod->name }}
                                    </div>
                                    <div style="display:flex; align-items:center; gap:0.4rem; font-size:0.72rem; color:#64748b; margin-top:1px; flex-wrap:wrap;">
                                        <span style="font-weight:700; color:#166534;">Rp {{ number_format($prod->sale_price ?: $prod->price, 0, ',', '.') }}</span>
                                        @if($inOtherGroup)
                                            <span style="font-size:0.65rem; font-weight:600; color:#b45309; background:#fef3c7; border:1px solid #fde68a; padding:1px 5px; border-radius:4px;">
                                                Kelompok: {{ $currentGroup->name }}
                                            </span>
                                        @elseif($currentGroup && isset($group) && $currentGroup->id === $group->id)
                                            <span style="font-size:0.65rem; font-weight:600; color:#15803d; background:#dcfce7; border:1px solid #bbf7d0; padding:1px 5px; border-radius:4px;">
                                                Di kelompok ini
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </label>
                    @empty
                        <div style="padding:1.75rem 1rem; text-align:center; color:#94a3b8; font-size:0.78rem;">
                            Belum ada produk aktif yang tersedia. Buat produk terlebih dahulu di menu Produk Saya.
                        </div>
                    @endforelse
                </div>
                @error('product_ids')<span class="form-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('creator.groups.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                {{ $isEdit ? 'Simpan Perubahan' : 'Buat Kelompok' }}
            </button>
        </div>
    </form>
</div>

<script>
    function updateCheckedCount() {
        const checkboxes = document.querySelectorAll('.group-prod-checkbox:checked');
        const countEl = document.getElementById('selectedProductCount');
        if (countEl) countEl.textContent = checkboxes.length;

        const allCheckboxes = document.querySelectorAll('.group-prod-checkbox');
        const btnToggle = document.getElementById('btnToggleSelectAll');
        if (btnToggle && allCheckboxes.length > 0) {
            btnToggle.textContent = (checkboxes.length === allCheckboxes.length) ? 'Batal Semua' : 'Pilih Semua';
        }
    }

    function updateCheckedState(checkbox) {
        const parent = checkbox.closest('.group-prod-item');
        if (parent) {
            if (checkbox.checked) {
                parent.classList.add('is-checked');
            } else {
                parent.classList.remove('is-checked');
            }
        }
        updateCheckedCount();
    }

    function handleItemClick(e, item) {
        if (e.target.tagName.toLowerCase() === 'input') return;
        const cb = item.querySelector('.group-prod-checkbox');
        if (cb) {
            cb.checked = !cb.checked;
            updateCheckedState(cb);
        }
    }

    function filterGroupProducts(query) {
        const q = (query || '').toLowerCase().trim();
        const items = document.querySelectorAll('.group-prod-item');
        items.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            item.style.display = name.includes(q) ? 'flex' : 'none';
        });
    }

    function toggleSelectAllProducts() {
        const allVisibleCheckboxes = Array.from(document.querySelectorAll('.group-prod-item'))
            .filter(item => item.style.display !== 'none')
            .map(item => item.querySelector('.group-prod-checkbox'))
            .filter(Boolean);

        const allChecked = allVisibleCheckboxes.every(cb => cb.checked);
        allVisibleCheckboxes.forEach(cb => {
            cb.checked = !allChecked;
            updateCheckedState(cb);
        });
    }

    // Init count on page load
    document.addEventListener('DOMContentLoaded', () => {
        updateCheckedCount();
    });
</script>
@endsection
