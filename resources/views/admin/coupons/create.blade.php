@extends('layouts.admin')
@section('title', 'Tambah Voucher')
@section('page-title', 'Tambah Voucher')
@section('content')

{{-- PAGE HEADER --}}
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
  <a href="{{ route('admin.coupons.index') }}" style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;background:#fff;border:1.5px solid #E4E7F0;border-radius:10px;color:#64748B;text-decoration:none;flex-shrink:0;transition:all .2s;" onmouseover="this.style.borderColor='#3B82F6';this.style.color='#3B82F6'" onmouseout="this.style.borderColor='#E4E7F0';this.style.color='#64748B'">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
  </a>
  <div>
    <h1 style="font-size:1.375rem;font-weight:800;color:#1E293B;margin:0 0 .1rem;letter-spacing:-.02em;">Tambah Voucher Baru</h1>
    <p style="font-size:.8rem;color:#94A3B8;margin:0;">Buat kode promo, diskon, dan gratis ongkir.</p>
  </div>
</div>

<form action="{{ route('admin.coupons.store') }}" method="POST">
  @csrf

  @if($errors->any())
  <div style="background:#FEF2F2;border:1.5px solid #FCA5A5;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#991B1B;font-size:.875rem;line-height:1.6;">
    <ul style="margin:0;padding-left:1.5rem;">
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <div style="display:grid;grid-template-columns:1fr;gap:1.75rem;align-items:start;max-width:800px;">
    
    {{-- Main Card --}}
    <div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
      
      <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.5rem;">
        <div style="width:32px;height:32px;background:rgba(59,130,246,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="16" height="16" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        </div>
        <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Informasi Utama Voucher</h3>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.125rem;">
        
        <div>
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Kode Voucher <span style="color:#EF4444;">*</span></label>
          <input type="text" name="code" value="{{ old('code') }}" required placeholder="Contoh: MERDEKA20" oninput="this.value=this.value.toUpperCase()"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;font-family:monospace;font-weight:700;letter-spacing:1px;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
          <div style="font-size:0.7rem;color:#94A3B8;margin-top:4px;">Kode unik yang diinput oleh pembeli.</div>
        </div>

        <div>
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Kategori <span style="color:#EF4444;">*</span></label>
          <select name="category" required style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
            <option value="product" {{ old('category') == 'product' ? 'selected' : '' }}>Diskon Produk</option>
            <option value="shipping" {{ old('category') == 'shipping' ? 'selected' : '' }}>Gratis Ongkir</option>
            <option value="event" {{ old('category') == 'event' ? 'selected' : '' }}>Event Spesial</option>
            <option value="member" {{ old('category') == 'member' ? 'selected' : '' }}>Member Khusus</option>
            <option value="referral" {{ old('category') == 'referral' ? 'selected' : '' }}>Referral</option>
          </select>
        </div>

        <div style="grid-column: span 2;">
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Deskripsi Singkat</label>
          <input type="text" name="description" value="{{ old('description') }}" placeholder="Contoh: Spesial diskon hari Kemerdekaan"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
        </div>

        <div>
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Teks Label / Badge</label>
          <input type="text" name="badge" value="{{ old('badge') }}" placeholder="Contoh: 17 Agustus"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
        </div>
      </div>
      
      <hr style="border:0;border-top:1px solid #F1F5F9;margin:1.75rem 0;">

      <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.5rem;">
        <div style="width:32px;height:32px;background:rgba(20,184,166,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="16" height="16" fill="none" stroke="#14B8A6" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Aturan & Nilai Diskon</h3>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.125rem;">
        <div>
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Tipe Diskon <span style="color:#EF4444;">*</span></label>
          <select name="type" id="discount-type" required style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
          </select>
        </div>

        <div>
          <label id="value-label" style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Nilai Diskon <span style="color:#EF4444;">*</span></label>
          <input type="number" step="0.01" name="value" value="{{ old('value') }}" required placeholder="0"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
        </div>

        <div>
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Minimal Pembelian (Rp) <span style="color:#EF4444;">*</span></label>
          <input type="number" name="min_purchase" value="{{ old('min_purchase', 0) }}" required placeholder="0"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
          <div style="font-size:0.7rem;color:#94A3B8;margin-top:4px;">Isi 0 jika tanpa batas minimal.</div>
        </div>

        <div>
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Maksimal Potongan Harga (Rp)</label>
          <input type="number" name="max_discount" value="{{ old('max_discount') }}" placeholder="Opsional (kosong = tanpa batas)"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
          <div style="font-size:0.7rem;color:#94A3B8;margin-top:4px;">Batas nilai potongan tertinggi jika pakai persentase.</div>
        </div>
      </div>

      <hr style="border:0;border-top:1px solid #F1F5F9;margin:1.75rem 0;">
      
      <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.5rem;">
        <div style="width:32px;height:32px;background:rgba(234,88,12,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <svg width="16" height="16" fill="none" stroke="#EA580C" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Limit & Masa Berlaku</h3>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.125rem;">
        <div style="grid-column: span 2;">
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Batas Pemakaian Voucher</label>
          <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="Opsional (kosong = unlimited)"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
        </div>

        <div>
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Tanggal Mulai</label>
          <input type="datetime-local" name="started_at" value="{{ old('started_at') }}"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
        </div>

        <div>
          <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Tanggal Berakhir</label>
          <input type="datetime-local" name="expired_at" value="{{ old('expired_at') }}"
            style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.9rem;color:#1E293B;outline:none;box-sizing:border-box;transition:border-color .2s;"
            onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'">
        </div>
      </div>
      
      <div style="margin-top:2rem;display:flex;align-items:center;gap:.75rem;padding:1.25rem;background:#F8FAFC;border-radius:12px;">
        <div style="position:relative;display:inline-block;width:44px;height:24px;">
          <input type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="opacity:0;width:0;height:0;position:absolute;z-index:-1;">
          <label for="isActive" style="position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#CBD5E1;transition:.4s;border-radius:34px;" id="toggle-bg">
            <span style="position:absolute;content:'';height:18px;width:18px;left:3px;bottom:3px;background-color:white;transition:.4s;border-radius:50%;" id="toggle-slider"></span>
          </label>
        </div>
        <div style="font-size:.9rem;font-weight:700;color:#1E293B;">Voucher Aktif & Bisa Digunakan</div>
      </div>
      
      <style>
        input:checked + label#toggle-bg { background-color: #10B981; }
        input:checked + label#toggle-bg span#toggle-slider { transform: translateX(20px); }
      </style>

    </div>

    {{-- Form Actions --}}
    <div style="display:flex;gap:1rem;justify-content:flex-end;">
      <a href="{{ route('admin.coupons.index') }}"
        style="padding:.875rem 1.5rem;background:#fff;border:1.5px solid #E4E7F0;border-radius:12px;color:#64748B;font-size:.95rem;font-weight:700;text-decoration:none;cursor:pointer;transition:all .2s;"
        onmouseover="this.style.borderColor='#CBD5E1';this.style.color='#475569'" onmouseout="this.style.borderColor='#E4E7F0';this.style.color='#64748B'">
        Batal
      </a>
      <button type="submit"
        style="padding:.875rem 2rem;background:#3B82F6;border:none;border-radius:12px;color:#fff;font-size:.95rem;font-weight:700;cursor:pointer;transition:all .2s;box-shadow:0 4px 14px rgba(59,130,246,0.35);"
        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(59,130,246,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(59,130,246,0.35)'">
        Simpan Voucher
      </button>
    </div>

  </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSel = document.getElementById('discount-type');
    const valLbl = document.getElementById('value-label');
    
    function updateLabel() {
        if (typeSel.value === 'percentage') {
            valLbl.innerHTML = 'Nilai Diskon (%) <span style="color:#EF4444;">*</span>';
        } else {
            valLbl.innerHTML = 'Nominal Potongan (Rp) <span style="color:#EF4444;">*</span>';
        }
    }
    
    typeSel.addEventListener('change', updateLabel);
    updateLabel();
});
</script>
@endsection
