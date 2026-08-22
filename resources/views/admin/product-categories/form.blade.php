@extends('layouts.admin')
@section('title', $category ? 'Edit Kategori' : 'Tambah Kategori')
@section('page-title', 'Kategori Marketplace')
@section('content')

<div style="max-width:640px;">
  <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:2rem;">
    <a href="{{ route('admin.product-categories.index') }}" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1.5px solid #E2E8F0;background:#fff;color:#64748B;text-decoration:none;">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    </a>
    <div>
      <h1 style="font-size:1.25rem;font-weight:800;color:#1E293B;margin:0;">{{ $category ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h1>
      <p style="font-size:.8rem;color:#94A3B8;margin:0;">{{ $category ? $category->name : 'Isi form berikut untuk menambah kategori baru' }}</p>
    </div>
  </div>

  <div style="background:#fff;border-radius:20px;box-shadow:0 2px 20px rgba(0,0,0,0.04);padding:2rem;">
    <form action="{{ $category ? route('admin.product-categories.update', $category) : route('admin.product-categories.store') }}" method="POST">
      @csrf
      @if($category) @method('PUT') @endif

      @if($errors->any())
      <div style="background:#FEF2F2;border:1px solid #FCA5A5;color:#DC2626;padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;font-size:.875rem;">
        @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
      </div>
      @endif

      {{-- Nama --}}
      <div style="margin-bottom:1.25rem;">
        <label style="font-size:.85rem;font-weight:600;color:#374151;display:block;margin-bottom:.4rem;">Nama Kategori <span style="color:#EF4444;">*</span></label>
        <input type="text" name="name" value="{{ old('name', $category?->name) }}" required
          placeholder="mis: AI & Otomatisasi"
          style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.95rem;outline:none;font-family:inherit;"
          onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E2E8F0'">
      </div>

      {{-- Slug --}}
      <div style="margin-bottom:1.25rem;">
        <label style="font-size:.85rem;font-weight:600;color:#374151;display:block;margin-bottom:.4rem;">Slug <span style="font-size:.75rem;color:#94A3B8;">(otomatis jika kosong)</span></label>
        <input type="text" name="slug" value="{{ old('slug', $category?->slug) }}"
          placeholder="mis: ai-otomatisasi"
          style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.9rem;font-family:monospace;outline:none;"
          onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E2E8F0'">
      </div>

      {{-- Tab --}}
      <div style="margin-bottom:1.25rem;">
        <label style="font-size:.85rem;font-weight:600;color:#374151;display:block;margin-bottom:.4rem;">Tab <span style="color:#EF4444;">*</span></label>
        <div style="display:flex;gap:.75rem;">
          @foreach(['produk' => 'Produk Digital', 'jasa' => 'Jasa Digital'] as $val => $label)
          <label style="flex:1;display:flex;align-items:center;gap:.5rem;padding:.75rem 1rem;border:1.5px solid {{ old('tab', $category?->tab) === $val ? '#1eb349' : '#E2E8F0' }};border-radius:12px;cursor:pointer;background:{{ old('tab', $category?->tab) === $val ? '#F0FDF4' : '#fff' }};">
            <input type="radio" name="tab" value="{{ $val }}" {{ old('tab', $category?->tab ?? 'produk') === $val ? 'checked' : '' }} style="accent-color:#1eb349;">
            <span style="font-size:.9rem;font-weight:600;color:#1E293B;">{{ $label }}</span>
          </label>
          @endforeach
        </div>
      </div>

      {{-- Badge --}}
      <div style="margin-bottom:1.25rem;">
        <label style="font-size:.85rem;font-weight:600;color:#374151;display:block;margin-bottom:.4rem;">Badge Label <span style="font-size:.75rem;color:#94A3B8;">(opsional)</span></label>
        <select name="badge" style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.9rem;outline:none;font-family:inherit;background:#fff;"
          onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E2E8F0'">
          <option value="">— Tanpa badge —</option>
          <option value="terpopuler" {{ old('badge', $category?->badge) === 'terpopuler' ? 'selected' : '' }}>🔥 Terpopuler</option>
          <option value="naik-daun"  {{ old('badge', $category?->badge) === 'naik-daun'  ? 'selected' : '' }}>📈 Naik Daun</option>
        </select>
      </div>

      {{-- Deskripsi --}}
      <div style="margin-bottom:1.25rem;">
        <label style="font-size:.85rem;font-weight:600;color:#374151;display:block;margin-bottom:.4rem;">Deskripsi <span style="font-size:.75rem;color:#94A3B8;">(tampil sebagai subtitle)</span></label>
        <input type="text" name="description" value="{{ old('description', $category?->description) }}"
          placeholder="mis: Alat berbasis kecerdasan buatan"
          style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.9rem;outline:none;font-family:inherit;"
          onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E2E8F0'">
      </div>

      {{-- Urutan & Status --}}
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
        <div>
          <label style="font-size:.85rem;font-weight:600;color:#374151;display:block;margin-bottom:.4rem;">Urutan Tampil</label>
          <input type="number" name="order" value="{{ old('order', $category?->order ?? 0) }}" min="0"
            style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;font-size:.9rem;outline:none;"
            onfocus="this.style.borderColor='#1eb349'" onblur="this.style.borderColor='#E2E8F0'">
        </div>
        <div>
          <label style="font-size:.85rem;font-weight:600;color:#374151;display:block;margin-bottom:.4rem;">Status</label>
          <label style="display:flex;align-items:center;gap:.5rem;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:12px;cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }} style="accent-color:#1eb349;width:16px;height:16px;">
            <span style="font-size:.9rem;color:#1E293B;">Aktif (tampil di frontend)</span>
          </label>
        </div>
      </div>

      <div style="display:flex;gap:.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid #F1F5F9;">
        <a href="{{ route('admin.product-categories.index') }}"
          style="padding:.7rem 1.5rem;border-radius:12px;border:1.5px solid #E2E8F0;background:#fff;color:#64748B;font-size:.9rem;font-weight:600;text-decoration:none;">Batal</a>
        <button type="submit"
          style="padding:.7rem 1.75rem;border-radius:12px;border:none;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;font-size:.9rem;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(30,179,73,0.25);">
          {{ $category ? 'Simpan Perubahan' : 'Tambah Kategori' }}
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
