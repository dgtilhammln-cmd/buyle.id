@extends('creator.layout')

@section('title', isset($group) ? 'Edit Kelompok' : 'Buat Kelompok Baru')
@section('page_title', isset($group) ? 'Edit Kelompok' : 'Buat Kelompok Baru')
@section('breadcrumb', 'Katalog › Kelompok › ' . (isset($group) ? 'Edit' : 'Baru'))

@section('styles')
<style>
    .form-card {
        background:#fff; border-radius:16px; border:1px solid #e7f0e7;
        box-shadow:0 2px 8px rgba(0,0,0,0.04); overflow:hidden; max-width:600px;
    }
    .form-body { padding:1.5rem; }
    .form-group { display:flex; flex-direction:column; gap:0.4rem; margin-bottom:1.25rem; }
    .form-label { font-size:0.8rem; font-weight:700; color:#374151; }
    .form-input {
        height:44px; padding:0 1rem;
        border:1.5px solid #e7f0e7; border-radius:10px;
        font-family:'Montserrat',sans-serif; font-size:0.875rem; color:#1a1a1a;
        background:#f9fefb; outline:none; transition:all 0.2s;
    }
    .form-input:focus { border-color:#1eb349; background:#fff; box-shadow:0 0 0 3px rgba(30,179,73,0.1); }
    .form-hint { font-size:0.72rem; color:#94A3B8; }
    .form-error { font-size:0.72rem; color:#ef4444; }

    /* Submit bar */
    .form-footer {
        padding:1rem 1.5rem; border-top:1px solid #f3f7f3;
        display:flex; align-items:center; justify-content:flex-end; gap:0.75rem;
    }
    .btn-cancel {
        height:42px; padding:0 1.25rem; border-radius:10px;
        border:1.5px solid #e7f0e7; background:#fff;
        font-family:'Montserrat',sans-serif; font-size:0.82rem; font-weight:700;
        color:#64748B; text-decoration:none; display:inline-flex; align-items:center;
        transition:all 0.2s; cursor:pointer;
    }
    .btn-cancel:hover { border-color:#94A3B8; color:#374151; }
    .btn-submit {
        height:42px; padding:0 1.5rem; border-radius:10px;
        background:linear-gradient(135deg,#1eb349,#a5cf37); border:none;
        font-family:'Montserrat',sans-serif; font-size:0.82rem; font-weight:700; color:#fff;
        cursor:pointer; box-shadow:0 2px 8px rgba(30,179,73,0.3);
        transition:all 0.2s; display:inline-flex; align-items:center; gap:0.4rem;
    }
    .btn-submit:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(30,179,73,0.4); }

    .toggle-switch { position:relative; width:40px; height:22px; flex-shrink:0; }
    .toggle-switch input { opacity:0; width:0; height:0; }
    .toggle-slider-s { position:absolute; inset:0; background:#cbd5e1; border-radius:22px; cursor:pointer; transition:0.3s; }
    .toggle-slider-s::before { content:''; position:absolute; left:3px; top:3px; width:16px; height:16px; background:#fff; border-radius:50%; transition:0.3s; }
    .toggle-switch input:checked + .toggle-slider-s { background:#1eb349; }
    .toggle-switch input:checked + .toggle-slider-s::before { transform:translateX(18px); }
</style>
@endsection

@section('content')
@php $isEdit = isset($group); @endphp
<div class="form-card">
    <form action="{{ $isEdit ? route('creator.groups.update', $group) : route('creator.groups.store') }}" method="POST">
        @csrf
        @if($isEdit) @method('PUT') @endif
        
        <div class="form-body">
            <div class="form-group">
                <label class="form-label">Nama Kelompok Produk <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" value="{{ old('name', $group->name ?? '') }}" class="form-input" placeholder="Misal: Template Premium" required>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Singkat (Maks 300 kata)</label>
                <textarea name="description" class="form-input" style="height:auto; padding-top:0.75rem; padding-bottom:0.75rem;" rows="3" placeholder="Deskripsi singkat kelompok produk untuk keperluan SEO..." maxlength="2000">{{ old('description', $group->description ?? '') }}</textarea>
                <span class="form-hint">Digunakan untuk meta deskripsi pada halaman kelompok ini.</span>
                @error('description')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Urutan (Opsional)</label>
                <input type="number" name="order" value="{{ old('order', $group->order ?? 0) }}" class="form-input" placeholder="0">
                <span class="form-hint">Angka lebih kecil akan tampil lebih dulu.</span>
                @error('order')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group" style="flex-direction:row; align-items:center; gap:0.75rem; margin-top:1rem;">
                <label class="toggle-switch">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $group->is_active ?? true) ? 'checked' : '' }}>
                    <span class="toggle-slider-s"></span>
                </label>
                <div>
                    <div class="form-label">Aktif</div>
                    <div class="form-hint">Tampilkan kelompok ini di toko Anda</div>
                </div>
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
@endsection
