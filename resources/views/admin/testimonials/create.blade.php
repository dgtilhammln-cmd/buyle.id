@extends('layouts.admin')
@section('title', isset($testimonial) ? 'Edit Testimoni' : 'Tambah Testimoni')
@section('page-title', isset($testimonial) ? 'Edit Testimoni' : 'Tambah Testimoni')
@section('content')
<div style="max-width:640px;">
<form method="POST" action="{{ isset($testimonial) ? route('admin.testimonials.update',$testimonial) : route('admin.testimonials.store') }}" enctype="multipart/form-data">
    @csrf @if(isset($testimonial)) @method('PUT') @endif

    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Foto Avatar --}}
        <div class="admin-card">
            <h3 style="font-size:0.875rem;font-weight:700;color:#F5A623;text-transform:uppercase;margin:0 0 1.25rem;">Foto / Avatar Klien</h3>
            <div style="display:flex;align-items:center;gap:1.5rem;">
                <div style="position:relative;flex-shrink:0;">
                    <img id="photo-preview"
                         src="{{ isset($testimonial) && $testimonial->photo ? asset('storage/'.$testimonial->photo) : (isset($testimonial) ? $testimonial->photo_url : 'https://ui-avatars.com/api/?name=buyle.id&background=F5A623&color=000000&size=80&bold=true&format=svg') }}"
                         alt="Preview"
                         style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #F5A623;">
                </div>
                <div style="flex:1;">
                    <label class="form-label">Upload Foto (opsional)</label>
                    <input type="file" name="photo" accept="image/*" class="form-input" id="photo-input">
                    <p style="font-size:0.75rem;color:#3F3F46;margin:0.375rem 0 0;">Auto-crop persegi & convert ke WebP 200×200px. Max 2MB.<br>Jika kosong, akan ditampilkan inisial nama secara otomatis.</p>
                </div>
            </div>
        </div>

        {{-- Data Klien --}}
        <div class="admin-card">
            <h3 style="font-size:0.875rem;font-weight:700;color:#F5A623;text-transform:uppercase;margin:0 0 1.25rem;">Data Klien</h3>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label class="form-label">Nama <span style="color:#F5A623;">*</span></label>
                        <input type="text" name="name" id="name-input" value="{{ old('name',$testimonial->name??'') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="position" value="{{ old('position',$testimonial->position??'') }}" class="form-input" placeholder="Manager, Direktur, dll.">
                    </div>
                </div>
                <div>
                    <label class="form-label">Perusahaan</label>
                    <input type="text" name="company" value="{{ old('company',$testimonial->company??'') }}" class="form-input" placeholder="PT. Contoh Perusahaan">
                </div>
                <div>
                    <label class="form-label">Isi Testimoni <span style="color:#F5A623;">*</span></label>
                    <textarea name="content" class="form-input" rows="5" required placeholder="Pengalaman positif klien menggunakan produk/layanan kami...">{{ old('content',$testimonial->content??'') }}</textarea>
                </div>
                <div style="display:flex;gap:1.5rem;align-items:flex-end;">
                    <div>
                        <label class="form-label">Rating</label>
                        <div style="display:flex;gap:0.5rem;align-items:center;margin-top:0.5rem;" id="star-selector">
                            @for($i=1;$i<=5;$i++)
                            <button type="button" onclick="setRating({{ $i }})" id="star-{{ $i }}"
                                    style="background:none;border:none;cursor:pointer;padding:0.25rem;font-size:1.5rem;line-height:1;transition:transform 0.1s;"
                                    onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform=''">
                                <svg width="24" height="24" fill="{{ $i <= (old('rating',$testimonial->rating??5)) ? '#F5A623' : '#27272A' }}" id="star-icon-{{ $i }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input" value="{{ old('rating',$testimonial->rating??5) }}">
                    </div>
                    <div>
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order" value="{{ old('order',$testimonial->order??0) }}" class="form-input" min="0" style="width:80px;">
                    </div>
                    <div style="display:flex;align-items:center;gap:0.5rem;padding-bottom:0.375rem;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $testimonial->is_active??true) ? 'checked' : '' }}
                               style="width:16px;height:16px;accent-color:#F5A623;">
                        <label for="is_active" style="font-size:0.875rem;color:#D4D4D8;cursor:pointer;">Tampilkan</label>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:0.75rem;">
            <button type="submit" class="btn-primary">Simpan Testimoni</button>
            <a href="{{ route('admin.testimonials.index') }}" class="btn-outline">Batal</a>
        </div>
    </div>
</form>
</div>

@push('scripts')
<script>
// Star rating
function setRating(val) {
    document.getElementById('rating-input').value = val;
    for (let i = 1; i <= 5; i++) {
        document.getElementById('star-icon-' + i).setAttribute('fill', i <= val ? '#F5A623' : '#27272A');
    }
}

// Photo preview
document.getElementById('photo-input').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photo-preview').src = e.target.result;
    };
    reader.readAsDataURL(file);
});

// Auto-update avatar from name if no photo uploaded
document.getElementById('name-input').addEventListener('input', function () {
    const preview = document.getElementById('photo-preview');
    const photoInput = document.getElementById('photo-input');
    if (!photoInput.files.length && !preview.src.includes('/storage/')) {
        const name = encodeURIComponent(this.value || 'buyle.id');
        preview.src = `https://ui-avatars.com/api/?name=${name}&background=F5A623&color=000000&size=80&bold=true&format=svg`;
    }
});
</script>
@endpush
@endsection
