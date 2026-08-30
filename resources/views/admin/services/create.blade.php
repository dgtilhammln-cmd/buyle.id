@extends('layouts.admin')
@section('title', isset($service) ? 'Edit Layanan' : 'Tambah Layanan')
@section('page-title', isset($service) ? 'Edit Layanan' : 'Tambah Layanan')
@section('content')
@php $s = $service ?? null; @endphp

{{-- PAGE HEADER --}}
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
  <a href="{{ route('admin.services.index') }}" style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;background:#fff;border:1.5px solid #E4E7F0;border-radius:10px;color:#64748B;text-decoration:none;flex-shrink:0;transition:all .2s;" onmouseover="this.style.borderColor='#1eb349';this.style.color='#1eb349'" onmouseout="this.style.borderColor='#E4E7F0';this.style.color='#64748B'">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
  </a>
  <div>
    <h1 style="font-size:1.375rem;font-weight:800;color:#1E293B;margin:0 0 .1rem;letter-spacing:-.02em;">{{ $s ? 'Edit Layanan' : 'Tambah Layanan Baru' }}</h1>
    <p style="font-size:.8rem;color:#94A3B8;margin:0;">{{ $s ? 'Perbarui informasi dan konten layanan' : 'Isi detail lengkap layanan/produk baru' }}</p>
  </div>
</div>

<form method="POST" action="{{ $s ? route('admin.services.update',$s) : route('admin.services.store') }}" enctype="multipart/form-data" id="svc-form">
@csrf @if($s) @method('PUT') @endif

@if($errors->any())
  <div style="background:#FEF2F2;border:1.5px solid #FCA5A5;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#991B1B;font-size:.875rem;line-height:1.6;">
    <strong style="display:block;margin-bottom:.5rem;">⚠️ DOUBLE CEK DIBUTUHKAN:</strong>
    <ul style="margin:0;padding-left:1.25rem;">
      @foreach($errors->all() as $error)
        <li style="margin-bottom:.25rem;">{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
<div id="validation-alert" style="display:none;background:#FEF2F2;border:1.5px solid #FCA5A5;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#991B1B;font-size:.875rem;line-height:1.6;"></div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.75rem;align-items:start;">

{{-- ═══════════════ LEFT COLUMN ═══════════════ --}}
<div style="display:flex;flex-direction:column;gap:1.5rem;">

  {{-- Informasi Layanan --}}
  <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.5rem;">
      <div style="width:32px;height:32px;background:rgba(30,179,73,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
      </div>
      <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Informasi Layanan</h3>
    </div>
    <div style="display:flex;flex-direction:column;gap:1.125rem;">
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Nama Layanan <span style="color:#EF4444;">*</span></label>
        <input type="text" name="name" id="svc-name" value="{{ old('name',$s?->name) }}" required oninput="svcAutoSlug()"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#1eb349';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'"
          placeholder="Contoh: buyle.id CV-60">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">
          Slug (URL) <span style="font-weight:500;color:#94A3B8;font-size:.75rem;">— Kosongkan untuk otomatis</span>
        </label>
        <div style="display:flex;align-items:center;gap:0;border:1.5px solid #E4E7F0;border-radius:10px;overflow:hidden;background:#F8FAFC;transition:border-color .2s;" id="slug-wrapper">
          <span style="padding:.75rem .875rem;font-size:.8rem;color:#94A3B8;background:#F1F5F9;border-right:1px solid #E4E7F0;white-space:nowrap;">/services/</span>
          <input type="text" name="slug" id="svc-slug" value="{{ old('slug',$s?->slug) }}" pattern="[a-z0-9\-]*"
            style="flex:1;padding:.75rem .875rem;background:transparent;border:none;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;"
            onfocus="document.getElementById('slug-wrapper').style.borderColor='#1eb349'" onblur="document.getElementById('slug-wrapper').style.borderColor='#E4E7F0'"
            placeholder="contoh-slug-url">
        </div>
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Deskripsi Singkat <span style="font-weight:500;color:#94A3B8;font-size:.75rem;">max 500 karakter</span></label>
        <textarea name="short_desc" rows="3" maxlength="500"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:inherit;outline:none;resize:vertical;box-sizing:border-box;transition:border-color .2s;"
          onfocus="this.style.borderColor='#1eb349';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'"
          placeholder="Deskripsi singkat tampil di halaman listing...">{{ old('short_desc',$s?->short_desc) }}</textarea>
      </div>
    </div>
  </div>

  {{-- Rich Text Editor --}}
  <div style="background:#fff;border-radius:20px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow:hidden;">
    <div style="padding:1.25rem 1.75rem;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;gap:.625rem;">
      <div style="width:32px;height:32px;background:rgba(30,179,73,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      </div>
      <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Deskripsi Lengkap</h3>
    </div>
    <div style="padding:1.25rem 1.75rem;">
      @include('admin.partials.rich-editor', ['name'=>'description','value'=>old('description',$s?->description??''),'height'=>'320px'])
    </div>
  </div>

  {{-- Spesifikasi Produk --}}
  <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04); margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.5rem;">
      <div style="width:32px;height:32px;background:rgba(30,179,73,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      </div>
      <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Data E-Commerce / Harga</h3>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.125rem;">
      <div style="grid-column: span 2;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Kategori</label>
        <select name="product_category_id" required style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
          <option value="">- Tanpa Kategori -</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('product_category_id', $s?->product_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Harga (Rp)</label>
        <input type="number" name="price" value="{{ old('price',$s?->price) }}" min="0" placeholder="0"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
        <p style="font-size:.7rem;color:#94A3B8;margin-top:.25rem;">Kosongkan/0 jika ini layanan jasa (Tanya via WA)</p>
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Harga Diskon (Rp)</label>
        <input type="number" name="sale_price" value="{{ old('sale_price',$s?->sale_price) }}" min="0" placeholder="Opsional"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Stok <span style="color:#EF4444;">*</span></label>
        <input type="number" name="stock" value="{{ old('stock',$s?->stock ?? 0) }}" min="0" required
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Minimum Order</label>
        <input type="number" name="min_order" value="{{ old('min_order',$s?->min_order ?? 1) }}" min="1"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Berat (Gram)</label>
        <input type="number" name="weight" value="{{ old('weight',$s?->weight ?? 0) }}" min="0"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Rating Bintang <span style="color:#EF4444;">*</span></label>
        <input type="number" step="0.1" name="rating" value="{{ old('rating', $s?->rating ?? 0) }}" min="0" max="5" placeholder="Cth: 4.8" required
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
      </div>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Jumlah Terjual <span style="color:#EF4444;">*</span></label>
        <input type="number" name="sold_count" value="{{ old('sold_count', $s?->sold_count ?? 0) }}" min="0" placeholder="Cth: 1200" required
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
      </div>
      <div style="grid-column:1 / -1;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">SKU (Opsional)</label>
        <input type="text" name="sku" value="{{ old('sku',$s?->sku) }}" placeholder="Contoh: SKU-001"
          style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
      </div>
    </div>
  </div>

  {{-- Spesifikasi Produk --}}
  <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <div style="display:flex;align-items:center;gap:.625rem;">
        <div style="width:32px;height:32px;background:rgba(30,179,73,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        </div>
        <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Spesifikasi Produk</h3>
      </div>
      <button type="button" onclick="addSpec()" style="display:inline-flex;align-items:center;gap:.375rem;font-size:.78rem;font-weight:700;color:#1eb349;background:rgba(30,179,73,0.08);border:none;border-radius:8px;padding:.4rem .875rem;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='rgba(30,179,73,0.15)'" onmouseout="this.style.background='rgba(30,179,73,0.08)'">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Baris
      </button>
    </div>
    <div id="specs-container" style="display:flex;flex-direction:column;gap:.625rem;">
      @php $specs = old('spec_keys', []); $specVals = old('spec_values', []); @endphp
      @foreach($specs as $idx => $k)
      <div class="spec-row" style="display:flex;gap:.625rem;align-items:center;">
        <input type="text" name="spec_keys[]" value="{{ $k }}" placeholder="Label (misal: Dimensi)" style="flex:1;padding:.625rem .875rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:8px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
        <input type="text" name="spec_values[]" value="{{ $specVals[$idx] ?? '' }}" placeholder="Nilai (misal: 24 inch)" style="flex:2;padding:.625rem .875rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:8px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
        <button type="button" onclick="this.parentElement.remove()" style="flex-shrink:0;width:32px;height:32px;background:rgba(239,68,68,0.08);border:none;border-radius:8px;color:#EF4444;cursor:pointer;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.background='rgba(239,68,68,0.16)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      @endforeach
      @if(count($specs) === 0)
      <div id="specs-empty" style="text-align:center;padding:2rem;color:#94A3B8;font-size:.875rem;border:2px dashed #E4E7F0;border-radius:10px;">
        Belum ada spesifikasi. Klik "Tambah Baris" untuk mulai.
      </div>
      @endif
    </div>
  </div>

  {{-- FAQ --}}
  <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <div style="display:flex;align-items:center;gap:.625rem;">
        <div style="width:32px;height:32px;background:rgba(30,179,73,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Tanya Jawab (FAQ)</h3>
      </div>
      <button type="button" onclick="addFaq()" style="display:inline-flex;align-items:center;gap:.375rem;font-size:.78rem;font-weight:700;color:#1eb349;background:rgba(30,179,73,0.08);border:none;border-radius:8px;padding:.4rem .875rem;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='rgba(30,179,73,0.15)'" onmouseout="this.style.background='rgba(30,179,73,0.08)'">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah FAQ
      </button>
    </div>
    <div id="faq-container" style="display:flex;flex-direction:column;gap:.875rem;">
      @php $faqs = old('faq_qs', []); $faqAs = old('faq_as', []); @endphp
      @foreach($faqs as $idx => $q)
      <div class="faq-row" style="display:flex;flex-direction:column;gap:.5rem;padding:1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:12px;position:relative;">
        <button type="button" onclick="this.parentElement.remove()" style="position:absolute;top:.625rem;right:.625rem;width:24px;height:24px;background:rgba(239,68,68,0.08);border:none;border-radius:6px;color:#EF4444;cursor:pointer;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.background='rgba(239,68,68,0.16)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <input type="text" name="faq_qs[]" value="{{ $q }}" placeholder="Pertanyaan?" style="width:100%;padding:.625rem .875rem;background:#fff;border:1.5px solid #E4E7F0;border-radius:8px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;padding-right:2.5rem;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
        <textarea name="faq_as[]" rows="2" placeholder="Jawaban lengkap..." style="width:100%;padding:.625rem .875rem;background:#fff;border:1.5px solid #E4E7F0;border-radius:8px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;resize:vertical;box-sizing:border-box;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">{{ $faqAs[$idx] ?? '' }}</textarea>
      </div>
      @endforeach
    </div>
  </div>

  @include('admin.partials.seo-fields', ['item'=>$s])

</div>

{{-- ═══════════════ RIGHT COLUMN ═══════════════ --}}
<div style="display:flex;flex-direction:column;gap:1.5rem;position:sticky;top:1.5rem;">

  {{-- Tombol Simpan --}}
  <div style="background:#fff;border-radius:20px;padding:1.25rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <button type="submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:.5rem;background:linear-gradient(135deg, #1eb349, #a5cf37);color:#fff;font-size:.9rem;font-weight:700;padding:.875rem 1.5rem;border-radius:12px;border:none;cursor:pointer;transition:all .2s;box-shadow:0 4px 14px rgba(30,179,73,0.3);" onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(30,179,73,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(30,179,73,0.3)'">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
      {{ $s ? 'Update Layanan' : 'Simpan Layanan' }}
    </button>
    <a href="{{ route('admin.services.index') }}" style="display:flex;align-items:center;justify-content:center;margin-top:.625rem;font-size:.85rem;font-weight:600;color:#64748B;text-decoration:none;padding:.625rem;border-radius:10px;transition:background .2s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">Batal</a>
  </div>

  {{-- Status & Urutan --}}
  <div style="background:#fff;border-radius:20px;padding:1.5rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.25rem;">
      <div style="width:32px;height:32px;background:rgba(30,179,73,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Status Publikasi</h3>
    </div>
    <div style="display:flex;flex-direction:column;gap:1rem;">
      {{-- Toggle aktif --}}
      <label style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;padding:.875rem 1rem;background:#F8FAFC;border-radius:12px;border:1.5px solid #E4E7F0;">
        <div>
          <div style="font-size:.875rem;font-weight:700;color:#1E293B;">Aktif</div>
          <div style="font-size:.75rem;color:#94A3B8;margin-top:.1rem;">Tampil di website publik</div>
        </div>
        <div style="position:relative;">
          <input type="hidden" name="is_active" value="0">
          <input type="checkbox" name="is_active" value="1" {{ old('is_active',$s?->is_active??true)?'checked':'' }} id="is_active_toggle" style="sr-only;position:absolute;opacity:0;width:0;height:0;" onchange="updateToggle(this)">
          <div id="toggle-track" onclick="document.getElementById('is_active_toggle').click()" style="width:44px;height:24px;border-radius:100px;cursor:pointer;transition:background .2s;position:relative;background:{{ old('is_active',$s?->is_active??true)?'#1eb349':'#E4E7F0' }};">
            <div id="toggle-thumb" style="position:absolute;top:3px;left:{{ old('is_active',$s?->is_active??true)?'23px':'3px' }};width:18px;height:18px;background:#fff;border-radius:50%;transition:left .2s;box-shadow:0 1px 4px rgba(0,0,0,0.15);"></div>
          </div>
        </div>
      </label>
      <div>
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Icon (opsional)</label>
        <input type="text" name="icon" value="{{ old('icon',$s?->icon) }}" placeholder="crane, hoist, lift..."
          style="width:100%;padding:.625rem .875rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;" onfocus="this.style.borderColor='#1eb349';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
        <p style="font-size:.72rem;color:#94A3B8;margin:.375rem 0 0;">Nama ikon atau SVG path identifier</p>
      </div>
    </div>
  </div>

  {{-- Foto Utama --}}
  @include('admin.partials.image-upload', ['item'=>$s,'field'=>'image','label'=>'Foto Utama Layanan','aspectRatio'=>'1:1'])

  {{-- OG Image --}}
  @include('admin.partials.image-upload', ['item'=>$s,'field'=>'og_image','label'=>'OG Image (Share Preview)'])

  {{-- Brosur --}}
  <div style="background:#fff;border-radius:20px;padding:1.5rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.25rem;">
      <div style="width:32px;height:32px;background:rgba(30,179,73,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">File Brosur</h3>
    </div>
    <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Upload Brosur/Datasheet</label>
    <label style="display:flex;flex-direction:column;align-items:center;gap:.5rem;padding:1.25rem;border:2px dashed #E4E7F0;border-radius:12px;cursor:pointer;transition:all .2s;text-align:center;" onmouseover="this.style.borderColor='#1eb349';this.style.background='#F8FAFF'" onmouseout="this.style.borderColor='#E4E7F0';this.style.background='transparent'">
      <svg width="24" height="24" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
      <span style="font-size:.8rem;color:#64748B;font-weight:600;">Klik untuk upload</span>
      <span style="font-size:.72rem;color:#94A3B8;">PDF, JPG, PNG — Maks 10MB</span>
      <input type="file" name="brochure" accept=".pdf,image/*" style="display:none;">
    </label>
  </div>

  {{-- Gallery --}}
  <div style="background:#fff;border-radius:20px;padding:1.5rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.125rem;">
      <div style="width:28px;height:28px;background:rgba(30,179,73,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="14" height="14" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
      </div>
      <h3 style="font-size:.8rem;font-weight:800;color:#1E293B;margin:0;">Foto Gallery</h3>
    </div>

    {{-- New gallery file previews (before save) --}}
    <div id="gallery-new-previews" style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-bottom:.75rem;"></div>

    {{-- Upload trigger --}}
    <label style="display:flex;flex-direction:column;align-items:center;gap:.5rem;padding:1rem;border:2px dashed #E4E7F0;border-radius:12px;cursor:pointer;transition:all .2s;text-align:center;"
           onmouseover="this.style.borderColor='#1eb349';this.style.background='#F8FAFF'"
           onmouseout="this.style.borderColor='#E4E7F0';this.style.background='transparent'">
      <svg width="22" height="22" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
      <span style="font-size:.78rem;color:#64748B;font-weight:600;">Upload Foto Gallery</span>
      <span style="font-size:.7rem;color:#94A3B8;">Bisa pilih banyak file sekaligus</span>
      <input type="file" name="gallery_images[]" id="galleryInput" multiple accept="image/*"
             style="display:none;" onchange="previewGalleryFiles(this)">
    </label>
  </div>

</div>
</div>
</form>

{{-- CONFIRMATION MODAL --}}
<div id="confirmModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.6);z-index:9999;align-items:center;justify-content:center;opacity:0;transition:opacity 0.2s;">
  <div style="background:#fff;border-radius:24px;width:90%;max-width:400px;padding:2rem;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,0.1);transform:scale(0.95);transition:transform 0.2s;" id="confirmModalBox">
    <div style="width:64px;height:64px;background:rgba(30,179,73,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
      <svg width="32" height="32" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <h3 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0 0 .5rem;">Cek Kembali Data Anda</h3>
    <p style="font-size:.9rem;color:#64748B;margin:0 0 1.5rem;line-height:1.5;">Apakah Anda yakin semua data (kategori, harga, spek produk, FAQ, dan foto) sudah terisi dengan benar sesuai tema?</p>
    <div style="display:flex;gap:.75rem;">
      <button type="button" onclick="closeConfirmModal()" style="flex:1;padding:.75rem;background:#F1F5F9;color:#64748B;font-weight:700;border:none;border-radius:12px;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">Cek Lagi</button>
      <button type="button" onclick="submitRealForm()" style="flex:1;padding:.75rem;background:linear-gradient(135deg, #1eb349, #a5cf37);color:#fff;font-weight:700;border:none;border-radius:12px;cursor:pointer;transition:background .2s;box-shadow:0 4px 12px rgba(30,179,73,0.3);" onmouseover="this.style.background='#1eb349'" onmouseout="this.style.background='#1eb349'">Ya, Simpan</button>
    </div>
  </div>
</div>

<script>
function previewGalleryFiles(input) {
    var container = document.getElementById('gallery-new-previews');
    container.innerHTML = '';
    if (!input.files || input.files.length === 0) return;
    Array.from(input.files).forEach(function(file, idx) {
        var url = URL.createObjectURL(file);
        var div = document.createElement('div');
        div.style.cssText = 'position:relative;border-radius:10px;overflow:hidden;border:2px solid #8B5CF6;';
        div.innerHTML = '<img src="' + url + '" style="width:100%;aspect-ratio:4/3;object-fit:cover;display:block;">'
            + '<button type="button" onclick="removeGalleryFile(\''+input.id+'\', '+idx+')" style="position:absolute;top:.375rem;right:.375rem;width:28px;height:28px;background:rgba(239,68,68,0.95);border:none;border-radius:6px;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,0.3);">'
            + '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>'
            + '</button>'
            + '<div style="position:absolute;bottom:0;left:0;right:0;background:rgba(59,130,246,0.85);padding:.3rem;font-size:.68rem;color:#fff;font-weight:700;text-align:center;">Baru</div>';
        container.appendChild(div);
    });
}
function removeGalleryFile(inputId, indexToRemove) {
    var input = document.getElementById(inputId);
    var dt = new DataTransfer();
    Array.from(input.files).forEach(function(file, idx) {
        if (idx !== indexToRemove) dt.items.add(file);
    });
    input.files = dt.files;
    previewGalleryFiles(input);
}


var svcSlugManual = {{ $s ? 'true' : 'false' }};
document.getElementById('svc-slug').addEventListener('input',()=>svcSlugManual=true);
function svcAutoSlug() {
  if(svcSlugManual) return;
  document.getElementById('svc-slug').value = document.getElementById('svc-name').value.toLowerCase().replace(/[^a-z0-9\s\-]/g,'').trim().replace(/\s+/g,'-');
}

let isConfirmed = false;
document.getElementById('svc-form').addEventListener('submit', function(e) {
  if(isConfirmed) return true;

  // Sync hidden textareas
  document.querySelectorAll('textarea[style*="display:none"]').forEach(t => t.style.display='block');

  // ── Double-check validasi ──
  const errors = [];
  const name = document.getElementById('svc-name').value.trim();
  if (!name) errors.push('❌  Nama Layanan wajib diisi.');

  const category = document.querySelector('select[name="product_category_id"]').value;
  if (!category) errors.push('❌  Kategori wajib dipilih.');

  const desc = document.querySelector('textarea[name="description"]');
  if (desc && desc.value.trim().length < 20) errors.push('❌  Deskripsi Lengkap minimal 20 karakter.');

  const stock = document.querySelector('input[name="stock"]');
  if (!stock || stock.value === '') errors.push('❌  Stok wajib diisi.');

  const rating = document.querySelector('input[name="rating"]');
  if (!rating || rating.value === '') errors.push('❌  Rating Bintang wajib diisi.');

  const soldCount = document.querySelector('input[name="sold_count"]');
  if (!soldCount || soldCount.value === '') errors.push('❌  Jumlah Terjual wajib diisi.');

  const specKeys = document.querySelectorAll('input[name="spec_keys[]"]');
  const specValues = document.querySelectorAll('input[name="spec_values[]"]');
  if (specKeys.length === 0) {
    errors.push('❌  Spesifikasi Produk minimal harus ada 1 baris.');
  } else {
    let specValid = false;
    for (let i = 0; i < specKeys.length; i++) {
      if (specKeys[i].value.trim() !== '' && specValues[i].value.trim() !== '') { specValid = true; break; }
    }
    if (!specValid) errors.push('❌  Spesifikasi Produk wajib diisi (Label & Nilai minimal 1 baris).');
  }

  const faqQs = document.querySelectorAll('input[name="faq_qs[]"]');
  const faqAs = document.querySelectorAll('textarea[name="faq_as[]"]');
  if (faqQs.length === 0) {
    errors.push('❌  Tanya Jawab (FAQ) minimal harus ada 1 baris.');
  } else {
    let faqValid = false;
    for (let i = 0; i < faqQs.length; i++) {
      if (faqQs[i].value.trim() !== '' && faqAs[i].value.trim() !== '') { faqValid = true; break; }
    }
    if (!faqValid) errors.push('❌  Tanya Jawab (FAQ) wajib diisi (Pertanyaan & Jawaban minimal 1 baris).');
  }

  const metaTitle = document.querySelector('input[name="meta_title"]');
  if (!metaTitle || metaTitle.value.trim() === '') errors.push('❌  Meta Title (SEO Settings) wajib diisi.');

  const metaDesc = document.querySelector('textarea[name="meta_desc"]');
  if (!metaDesc || metaDesc.value.trim() === '') errors.push('❌  Meta Description (SEO Settings) wajib diisi.');

  const metaKeywords = document.querySelector('input[name="meta_keywords"]');
  if (!metaKeywords || metaKeywords.value.trim() === '') errors.push('❌  Meta Keywords (SEO Settings) wajib diisi.');

  if (errors.length > 0) {
    e.preventDefault();
    const box = document.getElementById('validation-alert');
    box.innerHTML = '<strong>Mohon perbaiki sebelum menyimpan:</strong><ul style="margin:.5rem 0 0;padding-left:1.25rem;">' +
      errors.map(err => `<li style="margin-bottom:.25rem;">${err}</li>`).join('') + '</ul>';
    box.style.display = 'block';
    box.scrollIntoView({ behavior: 'smooth', block: 'start' });
    return false;
  }

  e.preventDefault();
  const modal = document.getElementById('confirmModal');
  const box = document.getElementById('confirmModalBox');
  modal.style.display = 'flex';
  setTimeout(() => {
    modal.style.opacity = '1';
    box.style.transform = 'scale(1)';
  }, 10);
});

function closeConfirmModal() {
  const modal = document.getElementById('confirmModal');
  const box = document.getElementById('confirmModalBox');
  modal.style.opacity = '0';
  box.style.transform = 'scale(0.95)';
  setTimeout(() => { modal.style.display = 'none'; }, 200);
}

function submitRealForm() {
  isConfirmed = true;
  document.getElementById('svc-form').submit();
}

function updateToggle(chk) {
  const track = document.getElementById('toggle-track');
  const thumb = document.getElementById('toggle-thumb');
  track.style.background = chk.checked ? '#1eb349' : '#E4E7F0';
  thumb.style.left = chk.checked ? '23px' : '3px';
}

function addSpec() {
  const empty = document.getElementById('specs-empty');
  if(empty) empty.remove();
  document.getElementById('specs-container').insertAdjacentHTML('beforeend', `
    <div class="spec-row" style="display:flex;gap:.625rem;align-items:center;">
      <input type="text" name="spec_keys[]" placeholder="Label (misal: Dimensi)" style="flex:1;padding:.625rem .875rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:8px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
      <input type="text" name="spec_values[]" placeholder="Nilai (misal: 24 inch)" style="flex:2;padding:.625rem .875rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:8px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
      <button type="button" onclick="this.parentElement.remove()" style="flex-shrink:0;width:32px;height:32px;background:rgba(239,68,68,0.08);border:none;border-radius:8px;color:#EF4444;cursor:pointer;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.background='rgba(239,68,68,0.16)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>`);
}

function addFaq() {
  document.getElementById('faq-container').insertAdjacentHTML('beforeend', `
    <div class="faq-row" style="display:flex;flex-direction:column;gap:.5rem;padding:1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:12px;position:relative;">
      <button type="button" onclick="this.parentElement.remove()" style="position:absolute;top:.625rem;right:.625rem;width:24px;height:24px;background:rgba(239,68,68,0.08);border:none;border-radius:6px;color:#EF4444;cursor:pointer;display:flex;align-items:center;justify-content:center;">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      <input type="text" name="faq_qs[]" placeholder="Pertanyaan?" style="width:100%;padding:.625rem .875rem;background:#fff;border:1.5px solid #E4E7F0;border-radius:8px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;padding-right:2.5rem;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'">
      <textarea name="faq_as[]" rows="2" placeholder="Jawaban lengkap..." style="width:100%;padding:.625rem .875rem;background:#fff;border:1.5px solid #E4E7F0;border-radius:8px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;resize:vertical;box-sizing:border-box;" onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E4E7F0'"></textarea>
    </div>`);
}
</script>
@endsection
