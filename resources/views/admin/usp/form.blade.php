@extends('layouts.admin')
@section('title', isset($usp) ? 'Edit USP' : 'Tambah USP')
@section('page-title', isset($usp) ? 'Edit USP' : 'Tambah USP')
@section('content')

<div style="max-width:640px;">
  <div style="background:#fff;border-radius:20px;box-shadow:0 2px 20px rgba(0,0,0,0.06);padding:2rem;">
    <h2 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0 0 1.5rem;">
      {{ isset($usp) ? 'Edit USP' : 'Tambah USP Baru' }}
    </h2>

    <form action="{{ isset($usp) ? route('admin.usp.update', $usp) : route('admin.usp.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @if(isset($usp)) @method('PUT') @endif

      {{-- Icon Type Selector --}}
      <div style="margin-bottom:1.25rem;">
        <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">TIPE ICON</label>
        <div style="display:flex;gap:.75rem;">
          <label style="flex:1;display:flex;align-items:center;gap:.5rem;padding:.75rem 1rem;border:2px solid {{ old('icon_type', $usp->icon_type ?? 'emoji') === 'emoji' ? '#3B82F6' : '#E2E8F0' }};border-radius:12px;cursor:pointer;">
            <input type="radio" name="icon_type" value="emoji" {{ old('icon_type', $usp->icon_type ?? 'emoji') === 'emoji' ? 'checked' : '' }} onchange="document.getElementById('emoji-wrap').style.display='block';document.getElementById('upload-wrap').style.display='none';">
            <span style="font-size:.875rem;font-weight:600;color:#1E293B;">Emoji / Teks</span>
          </label>
          <label style="flex:1;display:flex;align-items:center;gap:.5rem;padding:.75rem 1rem;border:2px solid {{ old('icon_type', $usp->icon_type ?? 'emoji') === 'upload' ? '#3B82F6' : '#E2E8F0' }};border-radius:12px;cursor:pointer;">
            <input type="radio" name="icon_type" value="upload" {{ old('icon_type', $usp->icon_type ?? '') === 'upload' ? 'checked' : '' }} onchange="document.getElementById('emoji-wrap').style.display='none';document.getElementById('upload-wrap').style.display='block';">
            <span style="font-size:.875rem;font-weight:600;color:#1E293B;">Upload Gambar</span>
          </label>
        </div>
      </div>

      <div id="emoji-wrap" style="margin-bottom:1.25rem;{{ old('icon_type', $usp->icon_type ?? 'emoji') === 'upload' ? 'display:none;' : '' }}">
        <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">EMOJI / KARAKTER ICON</label>
        <input type="text" name="icon_emoji" value="{{ old('icon_emoji', $usp->icon_type === 'emoji' ? $usp->icon_value : '') }}" placeholder="Contoh: 🚚 atau ✅" style="width:100%;padding:.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:1.5rem;outline:none;box-sizing:border-box;">
      </div>

      <div id="upload-wrap" style="margin-bottom:1.25rem;{{ old('icon_type', $usp->icon_type ?? 'emoji') !== 'upload' ? 'display:none;' : '' }}">
        <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">UPLOAD ICON (PNG/SVG, max 1MB)</label>
        @if(isset($usp) && $usp->icon_type === 'upload' && $usp->icon_value)
          <img src="{{ asset('storage/'.$usp->icon_value) }}" width="48" style="margin-bottom:.5rem;border-radius:8px;">
        @endif
        <input type="file" name="icon_file" accept="image/*" style="width:100%;padding:.75rem;border:1.5px dashed #CBD5E1;border-radius:12px;cursor:pointer;font-size:.875rem;box-sizing:border-box;">
      </div>

      <div style="margin-bottom:1.25rem;">
        <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">LABEL TEKS <span style="color:#EF4444;">*</span></label>
        <input type="text" name="label" value="{{ old('label', $usp->label ?? '') }}" required placeholder="Contoh: Pengiriman Gratis" style="width:100%;padding:.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.875rem;outline:none;box-sizing:border-box;">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
        <div>
          <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">URUTAN</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', $usp->sort_order ?? 0) }}" style="width:100%;padding:.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.875rem;outline:none;box-sizing:border-box;">
        </div>
        <div>
          <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">STATUS</label>
          <select name="is_active" style="width:100%;padding:.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.875rem;outline:none;box-sizing:border-box;background:#fff;">
            <option value="1" {{ old('is_active', $usp->is_active ?? true) ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ !old('is_active', $usp->is_active ?? true) ? 'selected' : '' }}>Nonaktif</option>
          </select>
        </div>
      </div>

      <div style="display:flex;gap:.75rem;">
        <button type="submit" style="flex:1;padding:.875rem;background:#3B82F6;color:#fff;font-weight:700;font-size:.875rem;border:none;border-radius:12px;cursor:pointer;">
          {{ isset($usp) ? 'Simpan Perubahan' : 'Tambah USP' }}
        </button>
        <a href="{{ route('admin.usp.index') }}" style="padding:.875rem 1.5rem;border:1.5px solid #E2E8F0;border-radius:12px;font-weight:600;font-size:.875rem;text-decoration:none;color:#64748B;">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
