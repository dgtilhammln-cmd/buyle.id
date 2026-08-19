@extends('layouts.admin')
@section('title', isset($gallery) ? 'Edit Foto Proyek' : 'Tambah Foto Proyek')
@section('page-title', isset($gallery) ? 'Edit Foto Proyek' : 'Tambah Foto Proyek')
@section('content')
@php $g = $gallery ?? null; @endphp

{{-- PAGE HEADER --}}
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
  <a href="{{ route('admin.gallery.index') }}" style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;background:#fff;border:1.5px solid #E4E7F0;border-radius:10px;color:#64748B;text-decoration:none;flex-shrink:0;transition:all .2s;" onmouseover="this.style.borderColor='#3B82F6';this.style.color='#3B82F6'" onmouseout="this.style.borderColor='#E4E7F0';this.style.color='#64748B'">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
  </a>
  <div>
    <h1 style="font-size:1.375rem;font-weight:800;color:#1E293B;margin:0 0 .1rem;letter-spacing:-.02em;">{{ $g ? 'Edit Foto Proyek' : 'Tambah Foto Proyek' }}</h1>
    <p style="font-size:.8rem;color:#94A3B8;margin:0;">{{ $g ? 'Perbarui informasi dan konten proyek galeri.' : 'Tambahkan portofolio atau proyek baru ke galeri.' }}</p>
  </div>
</div>

<form method="POST" action="{{ $g ? route('admin.gallery.update',$g) : route('admin.gallery.store') }}" enctype="multipart/form-data" id="gal-form">
@csrf @if($g) @method('PUT') @endif

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.75rem;align-items:start;">

{{-- LEFT COLUMN --}}
<div style="display:flex;flex-direction:column;gap:1.5rem;">

  {{-- Info Proyek --}}
  <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.5rem;">
      <div style="width:32px;height:32px;background:rgba(59,130,246,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
      </div>
      <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Info Proyek</h3>
    </div>
    <div style="display:flex;flex-direction:column;gap:1.125rem;">
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Judul Proyek <span style="color:#EF4444;">*</span></label>
        <input type="text" name="title" id="gal-title" value="{{ old('title',$g?->title) }}" required oninput="galAutoSlug()"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'"
          placeholder="Contoh: Pemasangan buyle.id PT. ABC">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">
          Slug (URL) <span style="font-weight:500;color:#94A3B8;font-size:.75rem;">— Kosongkan untuk otomatis</span>
        </label>
        <div style="display:flex;align-items:center;gap:0;border:1.5px solid #E4E7F0;border-radius:10px;overflow:hidden;background:#F8FAFC;transition:border-color .2s;" id="slug-wrapper">
          <span style="padding:.75rem .875rem;font-size:.8rem;color:#94A3B8;background:#F1F5F9;border-right:1px solid #E4E7F0;white-space:nowrap;">/gallery/</span>
          <input type="text" name="slug" id="gal-slug" value="{{ old('slug',$g?->slug) }}" pattern="[a-z0-9\-]*"
            style="flex:1;padding:.75rem .875rem;background:transparent;border:none;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;"
            onfocus="document.getElementById('slug-wrapper').style.borderColor='#3B82F6'" onblur="document.getElementById('slug-wrapper').style.borderColor='#E4E7F0'"
            placeholder="contoh-slug">
        </div>
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Deskripsi Singkat</label>
        <textarea name="description" rows="2"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;resize:vertical;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'"
          placeholder="Ringkasan singkat proyek...">{{ old('description',$g?->description) }}</textarea>
      </div>
    </div>
  </div>

  {{-- Detail Konten --}}
  <div style="background:#fff;border-radius:20px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow:hidden;">
    <div style="padding:1.5rem 1.75rem;border-bottom:1px solid #E4E7F0;">
      <div style="display:flex;align-items:center;gap:.625rem;">
        <div style="width:32px;height:32px;background:rgba(59,130,246,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="16" height="16" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Detail Konten Proyek</h3>
      </div>
    </div>
    <div style="padding:1.5rem 1.75rem;">
      @include('admin.partials.rich-editor', ['name'=>'content','value'=>old('content',$g?->content??''),'height'=>'300px'])
    </div>
  </div>

  {{-- Spesifikasi Proyek --}}
  <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.5rem;">
      <div style="width:32px;height:32px;background:rgba(59,130,246,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
      </div>
      <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Spesifikasi Proyek</h3>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.125rem;">
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Klien / Perusahaan</label>
        <input type="text" name="client" value="{{ old('client',$g?->client) }}"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Lokasi</label>
        <input type="text" name="location" value="{{ old('location',$g?->location) }}"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Tahun</label>
        <input type="number" name="year" value="{{ old('year',$g?->year ?? date('Y')) }}" min="2000" max="2099"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Kategori</label>
        <input type="text" name="category" list="cat-suggestions" value="{{ old('category', $g?->category) }}"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'"
          placeholder="Ketik kategori...">
        <datalist id="cat-suggestions">
          <option value="Pabrik"><option value="Gudang"><option value="Ruko">
          <option value="Perumahan"><option value="Gedung Komersial"><option value="Sekolah">
          <option value="Rumah Sakit"><option value="Hotel"><option value="Sport Center">
          <option value="Masjid"><option value="Kantor"><option value="Mal">
        </datalist>
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Alt Text (SEO)</label>
        <input type="text" name="alt_text" value="{{ old('alt_text',$g?->alt_text) }}"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Tags (pisah koma)</label>
        <input type="text" name="tags" value="{{ old('tags',$g?->tags) }}"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
      </div>
    </div>
  </div>

  @include('admin.partials.seo-fields', ['item'=>$g])
</div>

{{-- RIGHT COLUMN --}}
<div style="display:flex;flex-direction:column;gap:1.5rem;">
  
  {{-- Status --}}
  <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.25rem;">
      <div style="width:28px;height:28px;background:rgba(59,130,246,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="14" height="14" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
      <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Status & Urutan</h3>
    </div>
    
    <div style="display:flex;flex-direction:column;gap:1rem;">
      <label style="display:flex;align-items:center;gap:.75rem;cursor:pointer;">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" name="is_published" value="1" {{ old('is_published',$g?->is_published??true)?'checked':'' }} style="width:18px;height:18px;accent-color:#3B82F6;cursor:pointer;">
        <span style="font-size:.875rem;color:#475569;font-weight:500;">Published (Tampil)</span>
      </label>
      <label style="display:flex;align-items:center;gap:.75rem;cursor:pointer;">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active',$g?->is_active??true)?'checked':'' }} style="width:18px;height:18px;accent-color:#3B82F6;cursor:pointer;">
        <span style="font-size:.875rem;color:#475569;font-weight:500;">Aktif</span>
      </label>
      <label style="display:flex;align-items:center;gap:.75rem;cursor:pointer;">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured',$g?->is_featured??false)?'checked':'' }} style="width:18px;height:18px;accent-color:#3B82F6;cursor:pointer;">
        <span style="font-size:.875rem;color:#475569;font-weight:500;">Featured (Homepage)</span>
      </label>

      <div style="margin-top:.5rem;padding-top:1rem;border-top:1px solid #E4E7F0;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Urutan Tampil</label>
        <input type="number" name="order" value="{{ old('order',$g?->order??0) }}" min="0"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
      </div>
    </div>
  </div>

  {{-- Gambar --}}
  <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.25rem;">
      <div style="width:28px;height:28px;background:rgba(59,130,246,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="14" height="14" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
      </div>
      <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Media Visual</h3>
    </div>
    
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
      @include('admin.partials.image-upload', ['item'=>$g, 'field'=>'image', 'label'=>'Foto Utama (wajib)', 'required'=>true])
      <div style="border-top:1px dashed #E4E7F0;padding-top:1.5rem;">
        @include('admin.partials.image-upload', ['item'=>$g, 'field'=>'og_image', 'label'=>'OG Image (opsional)'])
      </div>
    </div>
  </div>

  {{-- Actions --}}
  <div style="display:flex;flex-direction:column;gap:.75rem;">
    <button type="submit" style="display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:1rem;background:#3B82F6;color:#fff;border:none;border-radius:12px;font-weight:700;font-size:.9rem;cursor:pointer;transition:all .2s;box-shadow:0 4px 14px rgba(59,130,246,0.3);" onmouseover="this.style.background='#2563EB';this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#3B82F6';this.style.transform='';">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
      {{ $g ? 'Update Proyek' : 'Simpan Proyek' }}
    </button>
    <a href="{{ route('admin.gallery.index') }}" style="display:flex;align-items:center;justify-content:center;width:100%;padding:1rem;background:#F1F5F9;color:#64748B;text-decoration:none;border-radius:12px;font-weight:700;font-size:.9rem;transition:all .2s;" onmouseover="this.style.background='#E2E8F0';this.style.color='#1E293B';" onmouseout="this.style.background='#F1F5F9';this.style.color='#64748B';">
      Batal
    </a>
  </div>

</div>
</div>
</form>

<script>
var galSlugManual = {{ $g ? 'true' : 'false' }};
document.getElementById('gal-slug').addEventListener('input',()=>galSlugManual=true);
function galAutoSlug() {
  if(galSlugManual) return;
  document.getElementById('gal-slug').value = document.getElementById('gal-title').value.toLowerCase().replace(/[^a-z0-9\s\-]/g,'').trim().replace(/\s+/g,'-');
}
document.getElementById('gal-form').addEventListener('submit',function(){
  document.querySelectorAll('textarea[style*="display:none"]').forEach(t=>t.style.display='block');
});
</script>
@endsection
