@extends('layouts.admin')
@section('title', isset($item) ? 'Edit Kategori' : 'Tambah Kategori')
@section('page-title', isset($item) ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')

<div style="max-width:800px;">
  <div style="background:#fff;border-radius:20px;box-shadow:0 2px 20px rgba(0,0,0,0.06);padding:2rem;">
    <h2 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0 0 1.5rem;">
      {{ isset($item) ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
    </h2>

    <form action="{{ isset($item) ? route('admin.category-items.update', $item) : route('admin.category-items.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @if(isset($item)) @method('PUT') @endif

      <div style="margin-bottom:1.5rem;">
        <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.75rem;">JENIS ICON <span style="color:#EF4444;">*</span></label>
        <div style="display:flex;gap:1.5rem;align-items:center;">
          <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
            <input type="radio" name="icon_type" value="icon" {{ old('icon_type', $item->icon_type ?? 'icon') === 'icon' ? 'checked' : '' }} onchange="toggleIconType()">
            <span style="font-size:.875rem;font-weight:600;color:#1E293B;">Pilih SVG Icon</span>
          </label>
          <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
            <input type="radio" name="icon_type" value="upload" {{ old('icon_type', $item->icon_type ?? 'icon') === 'upload' ? 'checked' : '' }} onchange="toggleIconType()">
            <span style="font-size:.875rem;font-weight:600;color:#1E293B;">Upload Gambar Sendiri</span>
          </label>
        </div>
      </div>

      {{-- SVG Icon Picker --}}
      <input type="hidden" name="icon_value" id="icon_value" value="{{ old('icon_value', $item->icon_value ?? '') }}">
      <div id="icon_picker_section" style="margin-bottom:1.5rem; {{ old('icon_type', $item->icon_type ?? 'icon') === 'icon' ? 'display:block;' : 'display:none;' }}">
        <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.75rem;">PILIH ICON <span style="color:#EF4444;">*</span></label>
        <div class="icon-grid" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(48px, 1fr));gap:.5rem;max-height:200px;overflow-y:auto;padding-right:.5rem;">
          @php
            $icons = [
              'home' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
              'box' => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
              'tool' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
              'truck' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
              'shopping-cart' => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
              'shopping-bag' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>',
              'zap' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
              'star' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
              'monitor' => '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
              'smartphone' => '<rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
              'camera' => '<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>',
              'gift' => '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>',
              'heart' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
              'tag' => '<path d="m15 5 6 6-6 6-6-6 6-6Z"/><path d="M9 5 3 11v6l6 6-6-6V11L9 5Z"/><circle cx="9" cy="15" r="2"/>',
              'briefcase' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
              'award' => '<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>',
              'cpu' => '<rect x="4" y="4" width="16" height="16" rx="2" ry="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>',
              'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
              'clock' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
              'map-pin' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
              'coffee' => '<path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>',
              'trash-2' => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>',
              'moon' => '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>',
              'droplet' => '<path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>',
              'sun' => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
              'wind' => '<path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"/>',
              'scissors' => '<circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/>',
              'key' => '<path d="m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4"/><path d="m21 2-9.6 9.6"/><circle cx="7.5" cy="15.5" r="5.5"/>',
              'bell' => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>',
              'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
              'book' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
              'headphones' => '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>',
              'music' => '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
              'mic' => '<path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/>',
              'video' => '<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>',
              'user' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
              'users' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
              'smile' => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>',
              'globe' => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
              'compass' => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',
              'navigation' => '<polygon points="3 11 22 2 13 21 11 13 3 11"/>',
              'map' => '<polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/>',
              'anchor' => '<circle cx="12" cy="5" r="3"/><line x1="12" y1="22" x2="12" y2="8"/><path d="M5 12H2a10 10 0 0 0 20 0h-3"/>',
              'airplane' => '<path d="M22 16.92v3l-8.09-3.15L8.74 22 7 22l1.63-7.53L4 12 1 13 1 11l3-1 4.63-2.53L7 2 8.74 2l5.17 5.23L22 10.38v6.54z"/>',
              'activity' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
              'archive' => '<polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/>',
              'server' => '<rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>',
              'database' => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>'
            ];
          @endphp
          @foreach($icons as $iconKey => $iconSvg)
            <div class="icon-option {{ old('icon_value', $item->icon_value ?? '') == $iconKey ? 'selected' : '' }}" data-key="{{ $iconKey }}" onclick="selectIcon('{{ $iconKey }}', this)" style="width:48px;height:48px;border-radius:12px;border:2px solid {{ old('icon_value', $item->icon_value ?? '') == $iconKey ? '#3B82F6' : '#E2E8F0' }};display:flex;align-items:center;justify-content:center;cursor:pointer;color:{{ old('icon_value', $item->icon_value ?? '') == $iconKey ? '#3B82F6' : '#64748B' }};background:{{ old('icon_value', $item->icon_value ?? '') == $iconKey ? '#EFF6FF' : '#fff' }};transition:all .2s;" title="{{ ucfirst(str_replace('-',' ',$iconKey)) }}">
              <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $iconSvg !!}</svg>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Image Upload Section --}}
      <div id="upload_picker_section" style="margin-bottom:1.5rem; {{ old('icon_type', $item->icon_type ?? 'icon') === 'upload' ? 'display:block;' : 'display:none;' }}">
        <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">UPLOAD GAMBAR ICON</label>
        @if(isset($item) && $item->icon_type === 'upload' && $item->icon_value)
            <div style="margin-bottom:1rem;">
                <img src="{{ asset('storage/' . $item->icon_value) }}" alt="Current Icon" style="width:64px;height:64px;object-fit:cover;border-radius:12px;border:1.5px solid #E2E8F0;">
            </div>
        @endif
        <input type="file" name="icon_file" accept="image/png, image/jpeg, image/jpg, image/svg+xml, image/webp" style="width:100%;padding:.75rem 1rem;border:1.5px dashed #CBD5E1;border-radius:12px;font-size:.875rem;outline:none;background:#F8FAFC;">
        <p style="font-size:0.75rem;color:#94A3B8;margin-top:0.5rem;">Format: PNG, JPG, WEBP, SVG. Maks 1MB. (Gunakan rasio 1:1, misal 100x100px).</p>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">NAMA KATEGORI <span style="color:#EF4444;">*</span></label>
        <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" required placeholder="Contoh: Peralatan Dapur" style="width:100%;padding:.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.875rem;outline:none;box-sizing:border-box;">
      </div>

      <div style="margin-bottom:1.25rem;">
        <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">URL LINK (opsional)</label>
        <input type="text" name="url" value="{{ old('url', $item->url ?? '') }}" placeholder="/produk?kategori=dapur" style="width:100%;padding:.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.875rem;outline:none;box-sizing:border-box;">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
        <div>
          <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">BADGE (opsional, misal: "Baru")</label>
          <input type="text" name="badge" value="{{ old('badge', $item->badge ?? '') }}" placeholder="Baru / Hot / Promo" style="width:100%;padding:.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.875rem;outline:none;box-sizing:border-box;">
        </div>
        <div>
          <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">WARNA BADGE</label>
          <input type="color" name="badge_color" value="{{ old('badge_color', $item->badge_color ?? '#ef4444') }}" style="width:100%;height:44px;border:1.5px solid #E2E8F0;border-radius:12px;cursor:pointer;padding:.25rem;">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
        <div>
          <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">URUTAN</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" style="width:100%;padding:.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.875rem;outline:none;box-sizing:border-box;">
        </div>
        <div>
          <label style="font-size:.8rem;font-weight:700;color:#64748B;display:block;margin-bottom:.5rem;">STATUS</label>
          <select name="is_active" style="width:100%;padding:.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.875rem;outline:none;box-sizing:border-box;background:#fff;">
            <option value="1" {{ old('is_active', $item->is_active ?? true) ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ !old('is_active', $item->is_active ?? true) ? 'selected' : '' }}>Nonaktif</option>
          </select>
        </div>
      </div>

      <div style="display:flex;gap:.75rem;">
        <button type="submit" style="flex:1;padding:.875rem;background:#3B82F6;color:#fff;font-weight:700;font-size:.875rem;border:none;border-radius:12px;cursor:pointer;">
          {{ isset($item) ? 'Simpan Perubahan' : 'Tambah Kategori' }}
        </button>
        <a href="{{ route('admin.category-items.index') }}" style="padding:.875rem 1.5rem;border:1.5px solid #E2E8F0;border-radius:12px;font-weight:600;font-size:.875rem;text-decoration:none;color:#64748B;">Batal</a>
      </div>
    </form>
  </div>
</div>

<script>
function toggleIconType() {
  const isIcon = document.querySelector('input[name="icon_type"]:checked').value === 'icon';
  document.getElementById('icon_picker_section').style.display = isIcon ? 'block' : 'none';
  document.getElementById('upload_picker_section').style.display = isIcon ? 'none' : 'block';
}

function selectIcon(key, el) {
  document.getElementById('icon_value').value = key;
  const options = document.querySelectorAll('.icon-option');
  options.forEach(opt => {
    opt.style.borderColor = '#E2E8F0';
    opt.style.color = '#64748B';
    opt.style.background = '#fff';
  });
  el.style.borderColor = '#3B82F6';
  el.style.color = '#3B82F6';
  el.style.background = '#EFF6FF';
}
</script>
@endsection
