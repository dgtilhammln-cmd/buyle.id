@extends('layouts.admin')
@section('title', isset($promoSection) ? 'Edit Section Promo' : 'Tambah Section Promo')
@section('page-title', isset($promoSection) ? 'Edit Section Promo' : 'Tambah Section Promo')

@section('content')
<style>
.pf-grid { display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; align-items: start; }
.pf-left { min-width: 0; }
.pf-right { position: sticky; top: 1.5rem; display: flex; flex-direction: column; gap: 1rem; }
.pf-card { background: #fff; border-radius: 20px; padding: 1.5rem; box-shadow: 0 2px 20px rgba(0,0,0,.05); }
.pf-section-card { background: #fff; border-radius: 20px; padding: 1.75rem; box-shadow: 0 2px 20px rgba(0,0,0,.04); margin-bottom: 1.5rem; }
.pf-section-title { font-size:.875rem;font-weight:800;color:#1E293B;margin:0 0 1.25rem;display:flex;align-items:center;gap:.5rem; }
.pf-icon { width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
.form-label { display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem; }
.form-input { width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;font-family:inherit; }
.form-input:focus { border-color:#3B82F6; }
.form-hint { font-size:.7rem;color:#94A3B8;margin:.3rem 0 0; }
.pf-preview-section {
    border-radius: 14px;
    padding: 1rem;
    background: linear-gradient(90deg, #EF4444 0%, #F97316 100%);
    min-height: 80px;
    display: flex;
    align-items: center;
    gap: .75rem;
    margin-bottom: 1rem;
    transition: background .3s;
}
.pf-timer-preview { display:flex; gap:.25rem; align-items:center; }
.pf-timer-box { background:rgba(0,0,0,.5);color:#fff;font-weight:800;font-size:.95rem;padding:.15rem .4rem;border-radius:5px;min-width:28px;text-align:center; }
.pf-timer-sep { color:#fff;font-weight:800; }
@media (max-width: 1024px) { .pf-grid { grid-template-columns: 1fr; } .pf-right { position:static; } }
</style>

<div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1.75rem;">
    <a href="{{ route('admin.promo-sections.index') }}" style="display:inline-flex;align-items:center;gap:.375rem;padding:.5rem .875rem;background:#F1F5F9;color:#64748B;border-radius:10px;font-size:.8rem;font-weight:700;text-decoration:none;">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      Kembali
    </a>
    <h1 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0;letter-spacing:-.02em;">
      {{ isset($promoSection) ? 'Edit Section Promo' : 'Tambah Section Promo Baru' }}
    </h1>
</div>

<div class="pf-grid">
<div class="pf-left">
  <form action="{{ isset($promoSection) ? route('admin.promo-sections.update', $promoSection) : route('admin.promo-sections.store') }}"
        method="POST" enctype="multipart/form-data" id="promoForm">
    @csrf
    @if(isset($promoSection)) @method('PUT') @endif

    {{-- Main Info Card --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,.04);margin-bottom:1.5rem;">
      <h2 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0 0 1.25rem;display:flex;align-items:center;gap:.5rem;">
        <div style="width:28px;height:28px;background:rgba(59,130,246,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="14" height="14" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        Informasi Section
      </h2>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div style="grid-column:1/-1;">
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Judul Section <span style="color:#EF4444">*</span></label>
          <input type="text" name="title" value="{{ old('title', $promoSection->title ?? '') }}" required
            placeholder="cth: Penawaran Spesial Hari Ini"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;"
            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#E4E7F0'">
          @error('title')<p style="color:#EF4444;font-size:.75rem;margin:.35rem 0 0;">{{ $message }}</p>@enderror
        </div>

        <div style="grid-column:1/-1;">
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Subjudul (Opsional)</label>
          <input type="text" name="subtitle" value="{{ old('subtitle', $promoSection->subtitle ?? '') }}"
            placeholder="cth: Diskon hingga 60% untuk produk pilihan"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;"
            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#E4E7F0'">
        </div>

        <div style="grid-column:1/-1;">
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Urutan Tampil</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', $promoSection->sort_order ?? 0) }}" min="0"
            style="width:200px;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;"
            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#E4E7F0'">
        </div>
      </div>

      <div style="margin-top:1rem;">
        <label style="display:flex;align-items:center;gap:.625rem;cursor:pointer;user-select:none;">
          <input type="hidden" name="is_active" value="0">
          <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promoSection->is_active ?? true) ? 'checked' : '' }}
            style="width:18px;height:18px;accent-color:#3B82F6;">
          <span style="font-size:.875rem;font-weight:600;color:#374151;">Aktifkan Section ini (tampil di beranda)</span>
        </label>
      </div>
    </div>

    {{-- Banner Upload --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,.04);margin-bottom:1.5rem;">
      <h2 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0 0 1.25rem;display:flex;align-items:center;gap:.5rem;">
        <div style="width:28px;height:28px;background:rgba(245,158,11,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="14" height="14" fill="none" stroke="#F59E0B" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        </div>
        Banner Sisi Kiri
      </h2>

      <p style="font-size:.8rem;color:#64748B;margin:0 0 1rem;">Unggah banner dengan rasio <strong>potret (3:4)</strong>, misalnya 600×800 px. Banner akan ditampilkan di sisi kiri bersebelahan dengan produk.</p>

      @if(isset($promoSection) && $promoSection->banner)
        <div style="margin-bottom:1rem;">
          <img src="{{ asset('storage/'.$promoSection->banner) }}" alt="Banner" style="max-height:160px;border-radius:12px;border:1.5px solid #E4E7F0;">
          <p style="font-size:.75rem;color:#94A3B8;margin:.375rem 0 0;">Banner saat ini. Unggah file baru untuk menggantinya.</p>
        </div>
      @endif

      <input type="file" name="banner" accept="image/*" id="bannerInput"
        style="display:block;width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px dashed #CBD5E1;border-radius:10px;font-size:.875rem;color:#64748B;cursor:pointer;box-sizing:border-box;"
        onchange="previewBanner(this)">
      <img id="bannerPreview" style="display:none;margin-top:.75rem;max-height:160px;border-radius:12px;border:1.5px solid #E4E7F0;">
      @error('banner')<p style="color:#EF4444;font-size:.75rem;margin:.35rem 0 0;">{{ $message }}</p>@enderror
    </div>

    {{-- Flash Sale / Campaign Settings --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,.04);margin-bottom:1.5rem;">
      <h2 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0 0 .5rem;display:flex;align-items:center;gap:.5rem;">
        <div style="width:28px;height:28px;background:rgba(239,68,68,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="14" height="14" fill="none" stroke="#EF4444" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        </div>
        Pengaturan Flash Sale & Campaign (Opsional)
      </h2>
      <p style="font-size:.8rem;color:#64748B;margin:0 0 1.25rem;">Isi bagian ini HANYA jika Anda ingin membuat tampilan Flash Sale/Event dengan Timer dan warna khusus.</p>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
        <div>
          <label class="form-label" for="start_time">Waktu Mulai Promo</label>
          <input type="datetime-local" name="start_time" id="start_time" class="form-input" value="{{ old('start_time', isset($promoSection->start_time) ? $promoSection->start_time->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div>
          <label class="form-label" for="end_time">Waktu Berakhir Promo (Timer)</label>
          <input type="datetime-local" name="end_time" id="end_time" class="form-input" value="{{ old('end_time', isset($promoSection->end_time) ? $promoSection->end_time->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div>
          <label class="form-label" for="bg_color_1">Warna Background Kiri (Gradient)</label>
          <div style="display:flex; gap:0.5rem;">
            <input type="color" name="bg_color_1" id="bg_color_1" style="height:42px; width:50px; border-radius:8px; border:1px solid #E2E8F0; padding:0; cursor:pointer;" value="{{ old('bg_color_1', $promoSection->bg_color_1 ?? '#EF4444') }}">
            <input type="text" class="form-input" style="flex-grow:1;" value="{{ old('bg_color_1', $promoSection->bg_color_1 ?? '') }}" placeholder="#EF4444" onchange="document.getElementById('bg_color_1').value=this.value">
          </div>
          <p style="font-size:.7rem;color:#94A3B8;margin-top:.3rem;">Kosongkan / reset untuk desain standar.</p>
        </div>

        <div>
          <label class="form-label" for="bg_color_2">Warna Background Kanan (Opsional)</label>
          <div style="display:flex; gap:0.5rem;">
            <input type="color" name="bg_color_2" id="bg_color_2" style="height:42px; width:50px; border-radius:8px; border:1px solid #E2E8F0; padding:0; cursor:pointer;" value="{{ old('bg_color_2', $promoSection->bg_color_2 ?? '#F97316') }}">
            <input type="text" class="form-input" style="flex-grow:1;" value="{{ old('bg_color_2', $promoSection->bg_color_2 ?? '') }}" placeholder="#F97316" onchange="document.getElementById('bg_color_2').value=this.value">
          </div>
        </div>

        <div style="grid-column:span 2;">
          <label class="form-label">Upload Logo Flash Sale</label>
          @if(isset($promoSection) && $promoSection->logo)
            <div style="margin-bottom:.75rem;padding:.75rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;display:inline-block;">
              <img src="{{ asset('storage/'.$promoSection->logo) }}" alt="Logo" style="max-height:40px;object-fit:contain;">
            </div>
          @endif
          <input type="file" name="logo" accept="image/*" class="form-input" style="padding:.5rem;">
          <p style="font-size:.7rem;color:#94A3B8;margin-top:.3rem;">PNG transparan direkomendasikan. Lebar max 300px.</p>
        </div>
      </div>
    </div>

    {{-- Product Selection Type --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,.04);margin-bottom:1.5rem;">
      <h2 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0 0 1.25rem;display:flex;align-items:center;gap:.5rem;">
        <div style="width:28px;height:28px;background:rgba(16,185,129,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="14" height="14" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        </div>
        Pengaturan Produk
      </h2>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
          <div>
              <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Tipe Pemilihan Produk <span style="color:#EF4444">*</span></label>
              <select name="selection_type" id="selectionType" style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;" onchange="toggleSelectionOptions()">
                  <option value="manual" {{ old('selection_type', $promoSection->selection_type ?? 'manual') === 'manual' ? 'selected' : '' }}>Pilih Produk Manual</option>
                  <option value="category" {{ old('selection_type', $promoSection->selection_type ?? '') === 'category' ? 'selected' : '' }}>Berdasarkan Kategori</option>
                  <option value="discount" {{ old('selection_type', $promoSection->selection_type ?? '') === 'discount' ? 'selected' : '' }}>Produk Sedang Diskon (Harga Coret)</option>
                  <option value="all" {{ old('selection_type', $promoSection->selection_type ?? '') === 'all' ? 'selected' : '' }}>Semua Produk</option>
              </select>
          </div>
          
          <div id="categorySelectWrapper" style="display:none;">
              <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Pilih Kategori</label>
              <select name="category_id" style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;">
                  <option value="">-- Pilih Kategori --</option>
                  @foreach($categories as $cat)
                      <option value="{{ $cat->id }}" {{ old('category_id', $promoSection->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                  @endforeach
              </select>
          </div>
      </div>
    </div>

    {{-- Product Selection --}}
    <div id="manualProductWrapper" style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,.04);margin-bottom:1.5rem;">
      <h2 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0 0 .5rem;display:flex;align-items:center;gap:.5rem;">
        <div style="width:28px;height:28px;background:rgba(16,185,129,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="14" height="14" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        </div>
        Pilih Produk / Jasa yang Ditampilkan
      </h2>
      <p style="font-size:.8rem;color:#64748B;margin:0 0 1rem;">Centang produk/jasa yang ingin tampil di section ini. Disarankan memilih <strong>5–8 produk</strong>. Urutan tampil sesuai urutan di bawah.</p>

      {{-- Search / Filter --}}
      <input type="text" id="productSearch" placeholder="Cari nama produk..."
        oninput="filterProducts(this.value)"
        style="width:100%;padding:.65rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.875rem;color:#1E293B;outline:none;box-sizing:border-box;margin-bottom:1rem;"
        onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#E4E7F0'">

      <div id="productList" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.625rem;max-height:380px;overflow-y:auto;padding:.25rem;">
        @foreach($services as $svc)
          @php $checked = in_array($svc->id, $selectedIds ?? []); @endphp
          <label class="product-item" data-name="{{ strtolower($svc->name) }}"
            style="display:flex;align-items:center;gap:.625rem;padding:.5rem;border:1.5px solid {{ $checked ? '#3B82F6' : '#E4E7F0' }};border-radius:12px;cursor:pointer;transition:all .2s;background:{{ $checked ? '#EFF6FF' : '#fff' }};"
            onmouseover="this.style.borderColor='#3B82F6'" onmouseout="syncLabel(this)">
            <div style="padding-left:.35rem; display:flex; align-items:center;">
              <input type="checkbox" name="service_ids[]" value="{{ $svc->id }}" {{ $checked ? 'checked' : '' }}
                onchange="syncLabel(this.closest('label'))"
                style="width:16px;height:16px;accent-color:#3B82F6;flex-shrink:0;">
            </div>
            <img src="{{ $svc->image ? asset('storage/'.$svc->image) : asset('img/no-image.jpg') }}" style="width:42px;height:42px;border-radius:8px;object-fit:cover;border:1px solid #E2E8F0;" onerror="this.src='https://via.placeholder.com/42?text=Img'">
            <div style="font-size:.8rem;font-weight:600;color:#1E293B;line-height:1.3;padding-right:.35rem;">
              {{ Str::limit($svc->name, 45) }}
              <span style="display:block;font-size:.7rem;font-weight:400;color:#94A3B8;margin-top:2px;">{{ $svc->type === 'service' ? 'Jasa' : 'Produk' }} · {{ $svc->price > 0 ? 'Rp'.number_format($svc->price,0,',','.') : '-' }}</span>
            </div>
          </label>
        @endforeach
      </div>
    </div>

    {{-- Submit --}}
    <div style="display:flex;gap:.875rem;padding:.5rem 0;">
      <button type="submit" style="display:inline-flex;align-items:center;gap:.5rem;background:#1E293B;color:#fff;font-weight:700;font-size:.9rem;padding:.75rem 1.75rem;border-radius:12px;border:none;cursor:pointer;font-family:inherit;flex:1;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        {{ isset($promoSection) ? 'Simpan Perubahan' : 'Buat Section Promo' }}
      </button>
      <a href="{{ route('admin.promo-sections.index') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#F1F5F9;color:#64748B;font-weight:700;font-size:.9rem;padding:.75rem 1.5rem;border-radius:12px;text-decoration:none;">
        Batal
      </a>
    </div>
  </form>
</div>{{-- end .pf-left --}}

{{-- ======= RIGHT PANEL ======= --}}
<div class="pf-right">

  {{-- Live Gradient Preview --}}
  <div class="pf-card">
    <div style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;margin-bottom:.875rem;">Preview Tampilan</div>
    <div class="pf-preview-section" id="previewSection">
      <div style="flex:1;">
        <div style="font-size:.95rem;font-weight:800;color:#fff;" id="previewTitle">{{ old('title', $promoSection->title ?? 'Nama Section') }}</div>
        <div style="font-size:.75rem;color:rgba(255,255,255,.8);margin-top:.2rem;" id="previewSubtitle">{{ old('subtitle', $promoSection->subtitle ?? 'Subjudul section...') }}</div>
      </div>
      <div class="pf-timer-preview">
        <div class="pf-timer-box">71</div>
        <span class="pf-timer-sep">:</span>
        <div class="pf-timer-box">59</div>
        <span class="pf-timer-sep">:</span>
        <div class="pf-timer-box">00</div>
      </div>
    </div>
    <div style="font-size:.72rem;color:#94A3B8;text-align:center;">Preview berubah sesuai warna & judul yang Anda isi.</div>
  </div>

  {{-- Section Status --}}
  @if(isset($promoSection))
  <div class="pf-card">
    <div style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;margin-bottom:.875rem;">Status Section</div>
    <div style="display:flex;flex-direction:column;gap:.625rem;">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.8rem;color:#64748B;">Status</span>
        <span style="padding:.2rem .65rem;border-radius:20px;font-size:.7rem;font-weight:700;background:{{ $promoSection->is_active ? '#DCFCE7' : '#FEE2E2' }};color:{{ $promoSection->is_active ? '#16A34A' : '#DC2626' }};">{{ $promoSection->is_active ? 'Aktif' : 'Non-Aktif' }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.8rem;color:#64748B;">Produk</span>
        <span style="font-size:.8rem;font-weight:700;color:#1E293B;">{{ $promoSection->services->count() }} item</span>
      </div>
      @if($promoSection->end_time)
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.8rem;color:#64748B;">Berakhir</span>
        <span style="font-size:.75rem;font-weight:700;color:{{ $promoSection->end_time > now() ? '#F97316' : '#EF4444' }};">
          {{ $promoSection->end_time > now() ? $promoSection->end_time->diffForHumans() : '⚠ Sudah berakhir' }}
        </span>
      </div>
      @endif
      @if($promoSection->bg_color_1)
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.8rem;color:#64748B;">Mode</span>
        <span style="padding:.2rem .65rem;border-radius:20px;font-size:.7rem;font-weight:700;background:#FEF3C7;color:#D97706;">⚡ Flash Sale</span>
      </div>
      @else
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.8rem;color:#64748B;">Mode</span>
        <span style="padding:.2rem .65rem;border-radius:20px;font-size:.7rem;font-weight:700;background:#F1F5F9;color:#475569;">📋 Standar</span>
      </div>
      @endif
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.8rem;color:#64748B;">Urutan</span>
        <span style="font-size:.8rem;font-weight:700;color:#1E293B;">#{{ $promoSection->sort_order }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.8rem;color:#64748B;">Dibuat</span>
        <span style="font-size:.75rem;color:#64748B;">{{ $promoSection->created_at->format('d M Y') }}</span>
      </div>
    </div>
  </div>
  @endif

  {{-- Tips --}}
  <div class="pf-card" style="background:linear-gradient(135deg,#EFF6FF,#F0FDF4);border:1.5px solid #BFDBFE;">
    <div style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#2563EB;margin-bottom:.875rem;">💡 Tips Penggunaan</div>
    <ul style="margin:0;padding-left:1.1rem;display:flex;flex-direction:column;gap:.5rem;">
      <li style="font-size:.775rem;color:#374151;line-height:1.5;">Pilih <strong>5–8 produk</strong> agar swiper terlihat proporsional.</li>
      <li style="font-size:.775rem;color:#374151;line-height:1.5;">Isi <strong>Warna Background</strong> untuk mode Flash Sale. Kosongkan untuk tampilan standar.</li>
      <li style="font-size:.775rem;color:#374151;line-height:1.5;">Gradient butuh <strong>Warna 1 &amp; Warna 2</strong>. Isi salah satu saja = warna solid.</li>
      <li style="font-size:.775rem;color:#374151;line-height:1.5;">Timer otomatis muncul jika <strong>Waktu Berakhir</strong> diisi dan belum kadaluarsa.</li>
      <li style="font-size:.775rem;color:#374151;line-height:1.5;">Upload <strong>Banner Sisi Kiri</strong> min 600×800 px untuk tampilan terbaik.</li>
    </ul>
  </div>

  {{-- Quick Submit --}}
  <button type="submit" form="promoForm" style="display:flex;align-items:center;justify-content:center;gap:.5rem;background:linear-gradient(135deg,#3B82F6,#2563EB);color:#fff;font-weight:800;font-size:.9rem;padding:.875rem 1.5rem;border-radius:14px;border:none;cursor:pointer;font-family:inherit;width:100%;box-shadow:0 4px 14px rgba(59,130,246,.35);transition:all .2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='none'">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
    {{ isset($promoSection) ? 'Simpan Perubahan' : 'Buat Section Promo' }}
  </button>

</div>{{-- end .pf-right --}}
</div>{{-- end .pf-grid --}}

<script>
function previewBanner(input) {
  const preview = document.getElementById('bannerPreview');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
    reader.readAsDataURL(input.files[0]);
  }
}

function syncLabel(label) {
  const cb = label.querySelector('input[type=checkbox]');
  if (!cb) return;
  label.style.borderColor = cb.checked ? '#3B82F6' : '#E4E7F0';
  label.style.background   = cb.checked ? '#EFF6FF' : '#fff';
}

function filterProducts(q) {
  document.querySelectorAll('.product-item').forEach(el => {
    el.style.display = el.dataset.name.includes(q.toLowerCase()) ? '' : 'none';
  });
}

function toggleSelectionOptions() {
  const type = document.getElementById('selectionType').value;
  const manualWrapper = document.getElementById('manualProductWrapper');
  const catWrapper = document.getElementById('categorySelectWrapper');

  manualWrapper.style.display = type === 'manual' ? 'block' : 'none';
  catWrapper.style.display = type === 'category' ? 'block' : 'none';
}

// Initial call
toggleSelectionOptions();

// Live Preview: update title, subtitle & gradient in right panel
function liveUpdate() {
    const title     = document.querySelector('[name="title"]');
    const subtitle  = document.querySelector('[name="subtitle"]');
    const c1Input   = document.getElementById('bg_color_1');
    const c2Input   = document.getElementById('bg_color_2');
    const prev      = document.getElementById('previewSection');
    const prevTitle = document.getElementById('previewTitle');
    const prevSub   = document.getElementById('previewSubtitle');

    if (title && prevTitle)   prevTitle.textContent   = title.value   || 'Nama Section';
    if (subtitle && prevSub)  prevSub.textContent     = subtitle.value || 'Subjudul section...';
    
    if (prev && c1Input) {
        const c1 = c1Input.value || '#EF4444';
        const c2 = c2Input ? (c2Input.value || c1) : c1;
        prev.style.background = `linear-gradient(90deg, ${c1} 0%, ${c2} 100%)`;
    }
}

// Wire up live updates
document.querySelectorAll('[name="title"],[name="subtitle"]').forEach(el => el.addEventListener('input', liveUpdate));
document.getElementById('bg_color_1')?.addEventListener('input', e => {
    e.target.nextElementSibling.value = e.target.value;
    liveUpdate();
});
document.getElementById('bg_color_2')?.addEventListener('input', e => {
    e.target.nextElementSibling.value = e.target.value;
    liveUpdate();
});
// Sync text inputs with color pickers
document.querySelectorAll('[placeholder="#EF4444"],[placeholder="#F97316"]').forEach(el => {
    el.addEventListener('input', e => {
        const colorInput = e.target.previousElementSibling;
        if (colorInput && colorInput.type === 'color') colorInput.value = e.target.value;
        liveUpdate();
    });
});
liveUpdate();

</script>
@endsection
