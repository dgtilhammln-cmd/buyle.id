@extends('layouts.admin')
@section('title', isset($client) ? 'Edit Klien' : 'Tambah Klien')
@section('page-title', isset($client) ? 'Edit Klien' : 'Tambah Klien Baru')
@section('content')
<div style="max-width:600px;">
<form method="POST" action="{{ isset($client) ? route('admin.clients.update',$client) : route('admin.clients.store') }}" enctype="multipart/form-data">
    @csrf @if(isset($client)) @method('PUT') @endif
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <div class="admin-card">
            <h3 style="font-size:0.875rem;font-weight:700;color:#F5A623;text-transform:uppercase;margin:0 0 1.25rem;">Data Klien</h3>
            <div style="display:flex;flex-direction:column;gap:1rem;">

                {{-- Nama Perusahaan --}}
                <div>
                    <label class="form-label">Nama Perusahaan <span style="color:#F5A623;">*</span></label>
                    <input type="text" name="name" id="client-name"
                           value="{{ old('name', $client->name ?? '') }}"
                           class="form-input" required
                           oninput="autoAlt(this.value)">
                </div>

                {{-- Kota & Industri --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label class="form-label">Kota</label>
                        <input type="text" name="city" value="{{ old('city', $client->city ?? '') }}" class="form-input" placeholder="Surabaya, Gresik...">
                    </div>
                    <div>
                        <label class="form-label">Industri</label>
                        <input type="text" name="industry" value="{{ old('industry', $client->industry ?? '') }}" class="form-input" placeholder="Manufaktur, Logistik...">
                    </div>
                </div>

                {{-- Logo Perusahaan --}}
                <div>
                    <label class="form-label">Logo Perusahaan</label>

                    {{-- Preview Box: no overflow:hidden, padding for breathing room --}}
                    <div id="logo-preview-box" style="
                        width: 100%;
                        min-height: 130px;
                        padding: 1.5rem;
                        box-sizing: border-box;
                        background: rgba(255,255,255,0.04);
                        border: 2px dashed rgba(255,255,255,0.12);
                        border-radius: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-bottom: 0.75rem;
                        transition: border-color 0.2s, background 0.2s;
                    ">
                        @if(isset($client) && $client->logo)
                            <img id="logo-preview"
                                 src="{{ asset('storage/'.$client->logo) }}"
                                 alt="{{ $client->alt_text ?? $client->name }}"
                                 style="max-width:100%; max-height:100px; width:auto; height:auto; object-fit:contain; display:block;">
                        @else
                            <div id="logo-placeholder" style="text-align:center; color:rgba(255,255,255,0.2);">
                                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="display:block;margin:0 auto 0.5rem;">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <span style="font-size:0.75rem;font-weight:600;">Preview Logo</span>
                            </div>
                            <img id="logo-preview" src="" style="max-width:100%; max-height:100px; width:auto; height:auto; object-fit:contain; display:none;">
                        @endif
                    </div>

                    <input type="file" name="logo" id="logo-input" accept="image/*" class="form-input"
                           onchange="previewLogo(this)" style="padding:.5rem;">
                    <p style="font-size:0.7rem;color:rgba(255,255,255,.25);margin:0.5rem 0 0;">
                        PNG/SVG transparan direkomendasikan. Auto WebP compress. Max 2MB.
                    </p>
                </div>

                {{-- Alt Text (SEO) --}}
                <div>
                    <label class="form-label">Alt Text (SEO)</label>
                    <input type="text" name="alt_text" id="alt-text-input"
                           value="{{ old('alt_text', $client->alt_text ?? '') }}"
                           class="form-input"
                           placeholder="Klien buyle.id | PT. Nama Perusahaan">
                    <p style="font-size:0.7rem;color:rgba(255,255,255,.25);margin:0.375rem 0 0;">
                        Auto-generate dari nama. Bisa diedit manual jika diperlukan.
                    </p>
                </div>

                {{-- Urutan & Aktif --}}
                <div style="display:flex;gap:1.5rem;">
                    <div>
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order" value="{{ old('order', $client->order ?? 0) }}" class="form-input" min="0" style="width:80px;">
                    </div>
                    <div style="display:flex;align-items:flex-end;gap:0.5rem;padding-bottom:0.5rem;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $client->is_active ?? true) ? 'checked' : '' }}
                               style="accent-color:#F5A623;">
                        <label for="is_active" style="font-size:0.875rem;color:#D4D4D8;">Tampilkan di website</label>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:0.75rem;">
            <button type="submit" class="btn-primary">Simpan Klien</button>
            <a href="{{ route('admin.clients.index') }}" class="btn-outline">Batal</a>
        </div>
    </div>
</form>
</div>

<script>
function previewLogo(input) {
    const preview     = document.getElementById('logo-preview');
    const placeholder = document.getElementById('logo-placeholder');
    const box         = document.getElementById('logo-preview-box');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src           = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
            box.style.borderColor = 'rgba(245,166,35,0.5)';
            box.style.background  = 'rgba(245,166,35,0.04)';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function autoAlt(name) {
    const altInput = document.getElementById('alt-text-input');
    if (!altInput) return;
    altInput.value = name.trim() ? 'Klien buyle.id | ' + name.trim() : '';
}

document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('client-name');
    const altInput  = document.getElementById('alt-text-input');
    // Only auto-fill if alt is still empty (new form or blank edit)
    if (nameInput && altInput && !altInput.value && nameInput.value) {
        altInput.value = 'Klien buyle.id | ' + nameInput.value;
    }
});
</script>
@endsection
