@extends('layouts.admin')
@section('title','Pengaturan Situs')
@section('page-title','Pengaturan Situs')
@section('content')

<style>
/* ===== PREMIUM ADMIN THEME OVERRIDES FOR SETTINGS PAGE ===== */

/* 1. Card Styles */
.tab-section > div[style*="background:#FFFFFF"],
.tab-section > div > div[style*="background:#FFFFFF"],
.tab-section > div > div > div[style*="background:#FFFFFF"] {
    background: #ffffff !important;
    border-radius: 20px !important;
    padding: 1.75rem !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03) !important;
    border: 1px solid #F8FAFC !important;
}

/* 2. Card Headers (Icon Box + Title) */
.tab-section [style*="background:#FFFFFF"] > div:first-child[style*="display:flex"] {
    gap: .75rem !important;
    margin-bottom: 1.75rem !important;
    align-items: center !important;
}
.tab-section [style*="background:#FFFFFF"] > div:first-child[style*="display:flex"] > svg {
    width: 34px !important;
    height: 34px !important;
    padding: 8px !important;
    background: rgba(59,130,246,0.1) !important;
    border-radius: 10px !important;
    stroke: #3B82F6 !important;
    color: #3B82F6 !important;
    box-sizing: border-box !important;
}
.tab-section [style*="background:#FFFFFF"] > div:first-child[style*="display:flex"] > div {
    font-size: .95rem !important;
    font-weight: 800 !important;
    color: #1E293B !important;
    letter-spacing: normal !important;
    text-transform: none !important;
}

/* 3. Form Inputs */
.form-input {
    width: 100% !important;
    padding: .875rem 1rem !important;
    background: #F8FAFC !important;
    border: 1.5px solid #E4E7F0 !important;
    border-radius: 12px !important;
    font-size: .9rem !important;
    color: #1E293B !important;
    font-family: 'Montserrat', inherit !important;
    font-weight: 500 !important;
    outline: none !important;
    box-sizing: border-box !important;
    transition: all .2s !important;
}
.form-input:focus {
    border-color: #3B82F6 !important;
    background: #ffffff !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
}

/* 4. Form Labels */
.form-label, 
.tab-section label[style*="font-size:.7rem"] {
    display: block !important;
    font-size: .85rem !important;
    font-weight: 700 !important;
    color: #334155 !important;
    margin-bottom: .5rem !important;
    text-transform: none !important;
    letter-spacing: normal !important;
}
.form-label span, 
.tab-section label span {
    font-weight: 500 !important;
    color: #94A3B8 !important;
    font-size: .75rem !important;
    margin-left: 0.25rem !important;
}

/* 5. File Inputs (Uploaders) */
input[type="file"].form-input {
    padding: 1.25rem 1rem !important;
    background: transparent !important;
    border: 2px dashed #CBD5E1 !important;
    border-radius: 12px !important;
    color: #64748B !important;
    cursor: pointer !important;
}
input[type="file"].form-input:hover {
    border-color: #3B82F6 !important;
    background: #F8FAFF !important;
}
input[type="file"]::file-selector-button {
    background: #F1F5F9 !important;
    border: 1px solid #E2E8F0 !important;
    padding: 0.5rem 1.25rem !important;
    border-radius: 8px !important;
    color: #475569 !important;
    font-weight: 600 !important;
    font-size: .8rem !important;
    cursor: pointer !important;
    transition: all 0.2s !important;
    margin-right: 1rem !important;
}
input[type="file"]::file-selector-button:hover {
    background: #E2E8F0 !important;
    color: #1E293B !important;
}

/* 6. Helpers / Descriptions */
.tab-section p[style*="font-size:.7rem"],
.tab-section p[style*="font-size:.75rem"] {
    font-size: .75rem !important;
    color: #94A3B8 !important;
    margin-top: .5rem !important;
    line-height: 1.5 !important;
    font-weight: 500 !important;
}

/* 7. Image Previews */
.tab-section div[style*="background:#F8FAFC"] {
    background: #F8FAFC !important;
    border: 1.5px solid #E4E7F0 !important;
    border-radius: 12px !important;
    padding: 1rem !important;
}

/* 8. Tab Buttons Wrapper */
div[style*="border-bottom:1px solid #E2E8F0"] {
    border-bottom: 2px solid #F1F5F9 !important;
    padding-bottom: 0.75rem !important;
    gap: 0.5rem !important;
}
button[id^="tab-btn-"] {
    padding: .75rem 1.25rem !important;
    border-radius: 12px !important;
    font-size: .85rem !important;
}
button.tab-btn-active {
    background: #E0F2FE !important;
    color: #0284C7 !important;
    font-weight: 700 !important;
    box-shadow: 0 2px 10px rgba(14, 165, 233, 0.1) !important;
}
/* 9. Premium Buttons */
button[type="submit"][style*="background:#0EA5E9"] {
    background: #3B82F6 !important;
    color: #fff !important;
    font-size: .875rem !important;
    font-weight: 700 !important;
    padding: .625rem 1.25rem !important;
    border-radius: 10px !important;
    border: none !important;
    box-shadow: 0 4px 14px rgba(59,130,246,0.3) !important;
    transition: all .2s !important;
}
button[type="submit"][style*="background:#0EA5E9"]:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 20px rgba(59,130,246,0.4) !important;
}
a[target="_blank"][style*="border:1px solid"] {
    background: #fff !important;
    border: 1.5px solid #E4E7F0 !important;
    color: #475569 !important;
    font-size: .85rem !important;
    font-weight: 600 !important;
    padding: .625rem 1.25rem !important;
    border-radius: 10px !important;
    transition: all .2s !important;
}
a[target="_blank"][style*="border:1px solid"]:hover {
    border-color: #3B82F6 !important;
    color: #3B82F6 !important;
    background: #F8FAFC !important;
}
button[style*="background:#25D366"] {
    background: #10B981 !important;
    color: #fff !important;
    border-radius: 10px !important;
    padding: .625rem 1.25rem !important;
    font-size: .85rem !important;
    font-weight: 700 !important;
    box-shadow: 0 4px 14px rgba(16,185,129,0.2) !important;
    transition: all .2s !important;
    border: none !important;
}
button[style*="background:#25D366"]:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 20px rgba(16,185,129,0.3) !important;
}
button[style*="background:rgba(37,211,102,.15)"] {
    background: rgba(16,185,129,0.1) !important;
    color: #10B981 !important;
    border: 1px solid rgba(16,185,129,0.2) !important;
    border-radius: 10px !important;
    padding: .625rem 1.25rem !important;
    font-size: .85rem !important;
    font-weight: 700 !important;
    transition: all .2s !important;
}
button[style*="background:rgba(37,211,102,.15)"]:hover {
    background: rgba(16,185,129,0.15) !important;
}
</style><form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settings-form">
@csrf @method('POST')

{{-- Page Header --}}
<div style="margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:1rem;">
  <div>
    <h1 style="font-size:1.375rem;font-weight:800;color:#0F172A;margin:0 0 .25rem;letter-spacing:-.02em;">Pengaturan Situs</h1>
    <p style="font-size:.8125rem;color:var(--text3,#7A7A8A);margin:0;">Kelola konten, SEO, kontak & tampilan website</p>
  </div>
  <div style="display:flex;gap:.5rem;align-items:center;">
    <a href="{{ url('/en/') }}" target="_blank" style="display:inline-flex;align-items:center;gap:.375rem;padding:.5rem 1rem;font-size:.8rem;font-weight:600;background:transparent;border:1px solid #CBD5E1;color:#475569;border-radius:4px;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='rgba(255,255,255,.4)';this.style.color='#fff'" onmouseout="this.style.borderColor='rgba(255,255,255,.15)';this.style.color='rgba(255,255,255,.6)'">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      Preview Website
    </a>
    <button type="submit" style="display:inline-flex;align-items:center;gap:.375rem;padding:.5rem 1.25rem;font-size:.875rem;font-weight:700;background:#0EA5E9;color:#ffffff;border:none;border-radius:4px;cursor:pointer;transition:all .2s;font-family:'Montserrat',sans-serif;" onmouseover="this.style.background='#0284C7'" onmouseout="this.style.background='#0EA5E9'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      Simpan Semua
    </button>
  </div>
</div>

{{-- Tab Nav --}}
<div style="display:flex;gap:.125rem;margin-bottom:1.75rem;border-bottom:1px solid #E2E8F0; padding-bottom: 0.5rem;flex-wrap:wrap;">
  @php
    $tabs = [
      'general' => ['Umum', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
      'seo'     => ['SEO', 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
      'hero'    => ['Hero & Konten', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
      'contact' => ['Kontak', 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
      'api'     => ['Integrasi API', 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
      
    ];
  @endphp
  @foreach($tabs as $tabKey => [$tabLabel, $tabIcon])
  <button type="button" onclick="switchTab('{{ $tabKey }}')" id="tab-btn-{{ $tabKey }}"
          style="display:flex;align-items:center;gap:.375rem;padding:.625rem 1rem;font-size:.8rem;font-weight:600;border:none;background:transparent;cursor:pointer;color:#64748B;border-radius:99px;transition:all .2s;font-family:'Montserrat',sans-serif;margin-bottom:0;">
    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="{{ $tabIcon }}"/></svg>
    {{ $tabLabel }}
  </button>
  @endforeach
</div>

{{-- ======== TAB: UMUM ======== --}}
<div id="tab-general" class="tab-section">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
    {{-- Logo --}}
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Logo Perusahaan</div>
      </div>
      @if(!empty($settings['logo']))
      <div style="margin-bottom:1rem;padding:1rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:6px;display:flex;align-items:center;gap:1rem;">
        <img src="{{ asset('storage/'.$settings['logo']) }}" alt="Logo" style="height:48px;object-fit:contain;">
        <span style="font-size:.75rem;color:#94A3B8;">Logo saat ini</span>
      </div>
      @endif
      <label style="font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text3);display:block;margin-bottom:.5rem;">Upload Logo Baru</label>
      <input type="file" name="logo" class="form-input" accept="image/*" style="padding:.5rem;">
      <p style="font-size:.7rem;color:#94A3B8;margin:.5rem 0 0;">PNG/SVG transparan, min 400px lebar. Otomatis dikonversi ke WebP.</p>
    </div>
    {{-- Favicon --}}
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Favicon Browser</div>
      </div>
      @if(!empty($settings['favicon']))
      <div style="margin-bottom:1rem;padding:1rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:6px;display:flex;align-items:center;gap:1rem;">
        <img src="{{ asset('storage/'.$settings['favicon']) }}" alt="Favicon" style="width:32px;height:32px;object-fit:contain;">
        <span style="font-size:.75rem;color:#94A3B8;">Favicon saat ini</span>
      </div>
      @endif
      <label style="font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text3);display:block;margin-bottom:.5rem;">Upload Favicon (ICO/PNG)</label>
      <input type="file" name="favicon" class="form-input" accept=".ico,.png,.svg" style="padding:.5rem;">
      <p style="font-size:.7rem;color:#94A3B8;margin:.5rem 0 0;">Rekomendasi: 32×32 atau 64×64 px format ICO/PNG.</p>
    </div>

    {{-- Company Profile --}}
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;grid-column:1/-1;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Company Profile (PDF)</div>
      </div>
      @if(!empty($settings['compro']))
      <div style="margin-bottom:1rem;padding:1rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:6px;display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:.5rem;">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          <span style="font-size:.8rem;color:#0F172A;">{{ basename($settings['compro']) }}</span>
        </div>
        <a href="{{ asset('storage/'.$settings['compro']) }}" target="_blank" style="font-size:.7rem;color:#0EA5E9;text-decoration:none;">Buka File</a>
      </div>
      @endif
      <label style="font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text3);display:block;margin-bottom:.5rem;">Upload Compro Baru</label>
      <input type="file" name="compro" class="form-input" accept=".pdf,.doc,.docx" style="padding:.5rem;">
      <p style="font-size:.7rem;color:#94A3B8;margin:.5rem 0 0;">Upload file PDF Company Profile. Akan tampil di menu header.</p>
    </div>

    {{-- Coverage Map --}}
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;grid-column:1/-1;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Peta Jangkauan (Coverage Map)</div>
      </div>
      @if(!empty($settings['coverage_map']))
      <div style="margin-bottom:1rem;padding:1rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:6px;">
        <img src="{{ asset('storage/'.$settings['coverage_map']) }}" alt="Coverage Map" style="width:100%;max-height:300px;object-fit:contain;border-radius:4px;margin-bottom:.75rem;">
        <span style="font-size:.75rem;color:#94A3B8;">Peta jangkauan saat ini</span>
      </div>
      @endif
      <label style="font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text3);display:block;margin-bottom:.5rem;">Upload Gambar Peta</label>
      <input type="file" name="coverage_map" class="form-input" accept="image/*" style="padding:.5rem;">
      <p style="font-size:.7rem;color:#94A3B8;margin:.5rem 0 0;">Upload gambar peta Indonesia dengan titik-titik jangkauan. Otomatis dikonversi ke WebP. Tidak ada batas ukuran — gambar tampil penuh.</p>
    </div>
  </div>

  <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
      <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Teks Umum</div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
      <div>
        <label class="form-label" for="s-footer_desc">Deskripsi Footer</label>
        <input type="text" name="footer_desc" id="s-footer_desc" class="form-input" value="{{ $settings['footer_desc'] ?? '' }}" placeholder="Produsen dan Spesialis buyle.id...">
        <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Tampil di footer website sebagai deskripsi singkat perusahaan.</p>
      </div>
      <div>
        <label class="form-label" for="s-copyright">Copyright Text</label>
        <input type="text" name="copyright" id="s-copyright" class="form-input" value="{{ $settings['copyright'] ?? '' }}" placeholder="© 2026 buyle.id. All rights reserved.">
        <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Tampil di bagian bawah footer.</p>
      </div>
      <div>
        <label class="form-label" for="s-founding_year">📅 Tahun Berdiri</label>
        <input type="number" name="founding_year" id="s-founding_year" class="form-input" value="{{ $settings['founding_year'] ?? '2013' }}" placeholder="2013" min="1900" max="{{ date('Y') }}" style="max-width:180px;">
        <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Otomatis tersinkron ke semua halaman: hero, statistik, footer, dan badge "Sejak xxxx".</p>
      </div>
    </div>
  </div>
</div>

{{-- ======== TAB: SEO ======== --}}
<div id="tab-seo" class="tab-section" style="display:none;">
  <div style="display:flex;flex-direction:column;gap:1.25rem;">
    
    {{-- Google Search Console --}}
    <div style="background:#FFFFFF;border:1px solid rgba(16,185,129,.15);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#10b981;">Google Search Console Verification</div>
      </div>
      <div>
        <label class="form-label" for="s-google_search_console">Meta Tag Verifikasi</label>
        <input type="text" name="google_search_console" id="s-google_search_console" class="form-input" value="{{ $settings['google_search_console'] ?? '' }}" placeholder="<meta name=&quot;google-site-verification&quot; content=&quot;...&quot; />">
        <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Paste meta tag verifikasi HTML dari Google Search Console di sini. Kode ini akan otomatis dipasang di bagian <code>&lt;head&gt;</code> website agar terbaca oleh Google.</p>
      </div>
    </div>
    @foreach([
      ['key'=>'home','label'=>'Halaman Home','route'=>'/'],
      ['key'=>'about','label'=>'Halaman About','route'=>'/about'],
      ['key'=>'services','label'=>'Halaman Layanan','route'=>'/services'],
      ['key'=>'gallery','label'=>'Halaman Galeri','route'=>'/gallery'],
      ['key'=>'articles','label'=>'Halaman Artikel','route'=>'/articles'],
      ['key'=>'contact','label'=>'Halaman Kontak','route'=>'/contact'],
    ] as $page)
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <div style="display:flex;align-items:center;gap:.5rem;">
          <svg width="13" height="13" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">{{ $page['label'] }}</div>
        </div>
        <a href="{{ url($page['route']) }}" target="_blank" style="display:flex;align-items:center;gap:.25rem;font-size:.7rem;color:#94A3B8;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#0EA5E9'" onmouseout="this.style.color='rgba(255,255,255,.3)'">
          {{ $page['route'] }}
          <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
      </div>
      <div style="display:grid;grid-template-columns:1fr;gap:.875rem;">
        <div>
          <label class="form-label">Meta Title <span style="color:#94A3B8;font-weight:400;">(max 65 karakter)</span></label>
          <input type="text" name="meta_title_{{ $page['key'] }}" class="form-input" maxlength="65"
                 value="{{ $settings['meta_title_'.$page['key']] ?? '' }}"
                 oninput="updateCounter(this,'cnt-title-{{ $page['key'] }}')"
                 placeholder="{{ $page['label'] }} | buyle.id">
          <div style="font-size:.7rem;color:#94A3B8;margin-top:.25rem;">
            <span id="cnt-title-{{ $page['key'] }}">{{ strlen($settings['meta_title_'.$page['key']] ?? '') }}</span>/65 karakter
          </div>
        </div>
        <div>
          <label class="form-label">Meta Description <span style="color:#94A3B8;font-weight:400;">(max 160 karakter)</span></label>
          <textarea name="meta_desc_{{ $page['key'] }}" class="form-input" rows="3" maxlength="160"
                    oninput="updateCounter(this,'cnt-desc-{{ $page['key'] }}')"
                    placeholder="Deskripsi halaman untuk mesin pencari...">{{ $settings['meta_desc_'.$page['key']] ?? '' }}</textarea>
          <div style="font-size:.7rem;color:#94A3B8;margin-top:.25rem;">
            <span id="cnt-desc-{{ $page['key'] }}">{{ strlen($settings['meta_desc_'.$page['key']] ?? '') }}</span>/160 karakter
          </div>
        </div>
        <div>
          <label class="form-label">Meta Keywords <span style="color:#94A3B8;font-weight:400;">(pisahkan koma)</span></label>
          <input type="text" name="meta_keywords_{{ $page['key'] }}" class="form-input"
                 value="{{ $settings['meta_keywords_'.$page['key']] ?? '' }}"
                 placeholder="overhead crane, hoist, crane surabaya, ...">
        </div>
        @if($page['key'] === 'home')
        <div>
          <label class="form-label">Default OG Image <span style="color:#94A3B8;font-weight:400;">(1200×630px, untuk share media sosial)</span></label>
          @if(!empty($settings['og_image_default']))
          <div style="margin-bottom:.75rem;border-radius:6px;overflow:hidden;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <img src="{{ asset('storage/'.$settings['og_image_default']) }}" style="height:100px;width:100%;object-fit:cover;" alt="OG Image">
          </div>
          @endif
          <input type="file" name="og_image_default" class="form-input" accept="image/*" style="padding:.5rem;">
        </div>
        @endif
      </div>
    </div>
    @endforeach

    {{-- Custom Scripts --}}
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
      <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
      <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Custom Scripts / Tags</div>
    </div>
    <p style="font-size:.75rem;color:#94A3B8;margin-bottom:1.25rem;line-height:1.6;">Gunakan area ini untuk memasukkan kode pelacakan seperti Google Analytics, Meta Pixel, atau custom CSS/JS. Pastikan Anda memasukkan tag lengkap (contoh: <code>&lt;script&gt;...&lt;/script&gt;</code>).</p>
    
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
      <div>
        <label class="form-label" for="s-head_scripts">Script di dalam <code>&lt;head&gt;</code></label>
        <textarea name="head_scripts" id="s-head_scripts" class="form-input" rows="8" placeholder="<!-- Google Tag Manager -->\n<script>...</script>" style="font-family:monospace; font-size:.8rem;">{{ $settings['head_scripts'] ?? '' }}</textarea>
        <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Cocok untuk meta tag verifikasi, Google Analytics (gtag), Meta Pixel base code, atau custom CSS.</p>
      </div>
      <div>
        <label class="form-label" for="s-body_scripts">Script di akhir <code>&lt;body&gt;</code></label>
        <textarea name="body_scripts" id="s-body_scripts" class="form-input" rows="8" placeholder="<!-- Live Chat Widget -->\n<script>...</script>" style="font-family:monospace; font-size:.8rem;">{{ $settings['body_scripts'] ?? '' }}</textarea>
        <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Cocok untuk widget live chat, event tracking pixel, atau custom Javascript yang membutuhkan DOM load selesai.</p>
  </div>

  </div>
</div>

  </div>
</div>



{{-- ======== TAB: HERO & KONTEN ======== --}}
<div id="tab-hero" class="tab-section" style="display:none;">
  <div style="display:flex;flex-direction:column;gap:1.25rem;">

    {{-- Hero Text --}}
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Teks Hero Section</div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        @foreach(['hero_headline'=>'Headline Utama (Judul Besar)','hero_subheadline'=>'Sub-headline (Kalimat Pendukung)','hero_cta_primary'=>'Teks Tombol Utama','hero_cta_secondary'=>'Teks Tombol Sekunder'] as $key=>$label)
        <div>
          <label class="form-label" for="s-{{ $key }}">{{ $label }}</label>
          <input type="text" name="{{ $key }}" id="s-{{ $key }}" class="form-input" value="{{ $settings[$key] ?? '' }}">
        </div>
        @endforeach
      </div>
    </div>

    {{-- Images --}}
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Gambar Background</div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.25rem;">
        @foreach([
          'hero_bg_image' => 'Background Hero (Gambar paling belakang, default putih)',
          'hero_main_image' => 'Hero Main Image (Tengah)',
          'hero_secondary_image' => 'Hero Secondary Image (Kanan)',
          'breadcrumb_bg' => 'Header Sub-halaman (Layanan, Artikel, dll)',
          'about_image'   => 'Foto About / Profile (Lama)',
        ] as $imgKey => $imgLabel)
        <div>
          <label class="form-label">{{ $imgLabel }}</label>
          @if(!empty($settings[$imgKey]))
          <div style="margin-bottom:.75rem;border-radius:6px;overflow:hidden;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <img src="{{ asset('storage/'.$settings[$imgKey]) }}" style="height:90px;width:100%;object-fit:cover;" alt="{{ $imgLabel }}">
          </div>
          @endif
          <input type="file" name="{{ $imgKey }}" class="form-input" accept="image/*" style="padding:.5rem;">
        </div>
        @endforeach
      </div>
      <p style="font-size:.7rem;color:#94A3B8;margin:.75rem 0 0;">Semua gambar akan otomatis dikompresi ke format WebP.</p>
    </div>

    {{-- About / Visi Misi --}}
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Visi & Misi</div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        @foreach(['visi'=>'Visi Perusahaan','misi'=>'Misi Perusahaan'] as $key=>$label)
        <div>
          <label class="form-label" for="s-{{ $key }}">{{ $label }}</label>
          <textarea name="{{ $key }}" id="s-{{ $key }}" class="form-input" rows="4">{{ $settings[$key] ?? '' }}</textarea>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Stats --}}
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Angka Statistik (Hero Section)</div>
      </div>
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;">
        @foreach(['stat_years'=>'Tahun Pengalaman','stat_clients'=>'Jumlah Klien','stat_products'=>'Jenis Produk','stat_coverage'=>'Jangkauan/Kota'] as $key=>$label)
        <div>
          <label class="form-label" for="s-{{ $key }}">{{ $label }}</label>
          <input type="text" name="{{ $key }}" id="s-{{ $key }}" class="form-input" value="{{ $settings[$key] ?? '' }}" placeholder="contoh: 10+">
        </div>
        @endforeach
      </div>
      <p style="font-size:.7rem;color:#94A3B8;margin:.75rem 0 0;">Angka-angka ini muncul di bagian statistik halaman Home.</p>
    </div>

    {{-- ── Aplikasi Produk (4 Layanan) ── --}}
    <div style="background:#FFFFFF;border:1px solid rgba(245,158,11,.15);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#f59e0b;">Gambar Ilustrasi Aplikasi Produk</div>
      </div>
      <p style="font-size:.75rem;color:#94A3B8;margin-bottom:1.25rem;line-height:1.6;">Upload gambar ilustrasi untuk 4 bagian di halaman produk (Restaurant, Pabrik & Gudang, Gedung Olahraga, Dapur).</p>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        @foreach([
          'app_img_restoran' => '1. Restaurant',
          'app_img_pabrik'   => '2. Pabrik & Gudang',
          'app_img_gor'      => '3. Gedung Olahraga',
          'app_img_dapur'    => '4. Dapur',
        ] as $imgKey => $imgLabel)
        <div style="background:#F1F5F9;padding:1rem;border-radius:8px;border:1px solid #E2E8F0;">
          <div style="font-size:.75rem;color:#f59e0b;margin-bottom:.75rem;font-weight:600;">{{ $imgLabel }}</div>
          
          @if(!empty($settings[$imgKey]))
          <div style="margin-bottom:.5rem;border-radius:4px;overflow:hidden;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <img src="{{ asset('storage/'.$settings[$imgKey]) }}" style="height:100px;width:100%;object-fit:cover;" alt="{{ $imgLabel }}">
          </div>
          @endif
          
          <label class="form-label" style="font-size:.65rem;">Upload Gambar (Otomatis kompres WebP)</label>
          <input type="file" name="{{ $imgKey }}" class="form-input" accept="image/*" style="padding:.25rem;font-size:.7rem;">
        </div>
        @endforeach
      </div>
    </div>

    {{-- ── About Section (Tentang Kami) ── --}}
    <div style="background:#FFFFFF;border:1px solid rgba(16,185,129,.15);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#10b981;">About Section (4 Cards Layout)</div>
      </div>
      
      <div style="margin-bottom:1.25rem;">
        <label class="form-label" for="s-about_heading">Heading Utama (Gunakan tag <code>&lt;span&gt;...&lt;/span&gt;</code> untuk styling biru)</label>
        <textarea name="about_heading" id="s-about_heading" class="form-input" rows="3" placeholder="A global consulting partner...">{{ $settings['about_heading'] ?? '' }}</textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        {{-- Card 1 --}}
        <div style="background:#F1F5F9;padding:1rem;border-radius:8px;border:1px solid #E2E8F0;">
          <div style="font-size:.75rem;color:#94a3b8;margin-bottom:.75rem;font-weight:600;">Card 1 (Abu-abu, Kiri Atas)</div>
          <div style="margin-bottom:.5rem;">
            <label class="form-label" style="font-size:.65rem;">Title / Label</label>
            <input type="text" name="about_c1_title" class="form-input" value="{{ $settings['about_c1_title'] ?? '' }}" placeholder="Pengalaman">
          </div>
          <div>
            <label class="form-label" style="font-size:.65rem;">Value / Angka</label>
            <input type="text" name="about_c1_val" class="form-input" value="{{ $settings['about_c1_val'] ?? '' }}" placeholder="10+ Tahun">
          </div>
        </div>
        
        {{-- Card 2 --}}
        <div style="background:rgba(56,189,248,.05);padding:1rem;border-radius:8px;border:1px solid rgba(56,189,248,.15);">
          <div style="font-size:.75rem;color:#38bdf8;margin-bottom:.75rem;font-weight:600;">Card 2 (Biru Accent, Kiri Bawah)</div>
          <div style="margin-bottom:.5rem;">
            <label class="form-label" style="font-size:.65rem;">Title / Label</label>
            <input type="text" name="about_c2_title" class="form-input" value="{{ $settings['about_c2_title'] ?? '' }}" placeholder="Komitmen Kualitas">
          </div>
          <div style="margin-bottom:.5rem;">
            <label class="form-label" style="font-size:.65rem;">Value / Angka</label>
            <input type="text" name="about_c2_val" class="form-input" value="{{ $settings['about_c2_val'] ?? '' }}" placeholder="100%">
          </div>
          <div>
            <label class="form-label" style="font-size:.65rem;">Deskripsi Singkat</label>
            <textarea name="about_c2_desc" class="form-input" rows="2" placeholder="Memberikan solusi sirkulasi udara terbaik untuk industri.">{{ $settings['about_c2_desc'] ?? '' }}</textarea>
          </div>
        </div>

        {{-- Card 3 --}}
        <div style="background:#F1F5F9;padding:1rem;border-radius:8px;border:1px solid #E2E8F0;">
          <div style="font-size:.75rem;color:#94a3b8;margin-bottom:.75rem;font-weight:600;">Card 3 (Gambar Background, Kanan Atas)</div>
          
          <div style="margin-bottom:.75rem;">
            <label class="form-label" style="font-size:.65rem;">Upload Gambar Background Card 3</label>
            @if(!empty($settings['about_c3_image']))
            <div style="margin-bottom:.5rem;border-radius:4px;overflow:hidden;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
              <img src="{{ asset('storage/'.$settings['about_c3_image']) }}" style="height:60px;width:100%;object-fit:cover;" alt="Card 3 Image">
            </div>
            @endif
            <input type="file" name="about_c3_image" class="form-input" accept="image/*" style="padding:.25rem;font-size:.7rem;">
          </div>

          <div style="margin-bottom:.5rem;">
            <label class="form-label" style="font-size:.65rem;">Value / Angka</label>
            <input type="text" name="about_c3_val" class="form-input" value="{{ $settings['about_c3_val'] ?? '' }}" placeholder="120+">
          </div>
          <div>
            <label class="form-label" style="font-size:.65rem;">Deskripsi Singkat</label>
            <textarea name="about_c3_desc" class="form-input" rows="2" placeholder="Proyek instalasi diselesaikan di seluruh Indonesia.">{{ $settings['about_c3_desc'] ?? '' }}</textarea>
          </div>
        </div>

        {{-- Card 4 --}}
        <div style="background:#F1F5F9;padding:1rem;border-radius:8px;border:1px solid #E2E8F0;">
          <div style="font-size:.75rem;color:#94a3b8;margin-bottom:.75rem;font-weight:600;">Card 4 (Abu-abu, Kanan Bawah)</div>
          <div style="margin-bottom:.5rem;">
            <label class="form-label" style="font-size:.65rem;">Title / Label</label>
            <input type="text" name="about_c4_title" class="form-input" value="{{ $settings['about_c4_title'] ?? '' }}" placeholder="Produk Terjual">
          </div>
          <div style="margin-bottom:.5rem;">
            <label class="form-label" style="font-size:.65rem;">Value / Angka</label>
            <input type="text" name="about_c4_val" class="form-input" value="{{ $settings['about_c4_val'] ?? '' }}" placeholder="10.000+">
          </div>
          <div>
            <label class="form-label" style="font-size:.65rem;">Deskripsi Singkat</label>
            <textarea name="about_c4_desc" class="form-input" rows="2" placeholder="Unit buyle.id terpasang di berbagai sektor industri.">{{ $settings['about_c4_desc'] ?? '' }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Hero Slides Manager ── --}}
    <div style="background:#FFFFFF;border:1px solid rgba(56,189,248,.15);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <div style="display:flex;align-items:center;gap:.5rem;">
          <svg width="14" height="14" fill="none" stroke="#38BDF8" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
          <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#38BDF8;">Hero Slides (Maks. 5 Slide)</div>
        </div>
        @php $slideCount = \App\Models\HeroSlide::count(); @endphp
        <span style="font-size:.7rem;color:#38BDF8;background:rgba(56,189,248,.1);padding:.25rem .75rem;border-radius:20px;">{{ $slideCount }} / 5 slide</span>
      </div>

      @php $allSlides = \App\Models\HeroSlide::orderBy('order')->get(); @endphp
      @if($allSlides->count())
      <div style="display:flex;flex-direction:column;gap:.625rem;margin-bottom:1.25rem;">
        @foreach($allSlides as $hs)
        <div style="display:flex;align-items:center;gap:1rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;padding:.75rem 1rem;">
          @if($hs->image)
          <img src="{{ asset('storage/'.$hs->image) }}" style="width:64px;height:44px;object-fit:cover;border-radius:6px;flex-shrink:0;">
          @else
          <div style="width:64px;height:44px;background:#F1F5F9;border-radius:6px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
            <svg width="18" height="18" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          </div>
          @endif
          <div style="flex:1;min-width:0;">
            <div style="font-size:.8rem;font-weight:600;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $hs->title }}</div>
            <div style="font-size:.7rem;color:#64748B;">{{ Str::limit($hs->description, 70) }}</div>
          </div>
          <span style="font-size:.7rem;padding:.2rem .6rem;border-radius:4px;flex-shrink:0;{{ $hs->is_active ? 'background:rgba(16,185,129,.12);color:#34d399;' : 'background:#F1F5F9;color:#94A3B8;' }}">
            {{ $hs->is_active ? 'Aktif' : 'Off' }}
          </span>
          <div style="display:flex;gap:.375rem;flex-shrink:0;">
            <a href="{{ route('admin.hero_slides.edit', $hs) }}" style="font-size:.72rem;background:rgba(56,189,248,.1);color:#38BDF8;padding:.35rem .7rem;border-radius:5px;text-decoration:none;transition:background .2s;">Edit</a>
            <button type="button" onclick="if(confirm('Hapus slide ini?')) document.getElementById('del-slide-{{ $hs->id }}').submit();" style="font-size:.72rem;background:rgba(239,68,68,.1);color:#f87171;padding:.35rem .7rem;border-radius:5px;border:none;cursor:pointer;">Hapus</button>
          </div>
        </div>
        @endforeach
      </div>
      @else
      <p style="font-size:.78rem;color:#94A3B8;margin-bottom:1.25rem;">Belum ada slide. Klik tombol di bawah untuk menambahkan.</p>
      @endif

      @if($slideCount < 5)
      <a href="{{ route('admin.hero_slides.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(56,189,248,.1);color:#38BDF8;border:1px solid rgba(56,189,248,.2);padding:.6rem 1.25rem;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Slide Baru ({{ 5 - $slideCount }} slot tersisa)
      </a>
      @else
      <p style="font-size:.75rem;color:rgba(255,165,0,.7);background:rgba(255,165,0,.08);border:1px solid rgba(255,165,0,.15);padding:.6rem 1rem;border-radius:6px;display:inline-block;margin:0;">Batas 5 slide tercapai. Hapus salah satu dulu.</p>
      @endif
    </div>

  </div>
</div>

{{-- ======== TAB: KONTAK ======== --}}
<div id="tab-contact" class="tab-section" style="display:none;">
  <div style="display:flex;flex-direction:column;gap:1.25rem;">
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Informasi Kontak</div>
      </div>
      <p style="font-size:.75rem;color:#94A3B8;margin-bottom:1.25rem;line-height:1.6;">Data di bawah ini akan tampil di <strong style="color:#475569;">Footer</strong>, halaman <strong style="color:#475569;">Kontak</strong>, dan <strong style="color:#475569;">Navbar</strong> website.</p>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div>
          <label class="form-label" for="s-phone">Nomor Telepon Kantor</label>
          <input type="text" name="phone" id="s-phone" class="form-input" value="{{ $settings['phone'] ?? '' }}" placeholder="031-XXXXXXXX">
          <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Tampil di footer & halaman kontak</p>
        </div>
        <div>
          <label class="form-label" for="s-email">Email Perusahaan</label>
          <input type="email" name="email" id="s-email" class="form-input" value="{{ $settings['email'] ?? '' }}" placeholder="karyaperdanateknik@gmail.com">
          <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Tampil di footer & halaman kontak</p>
        </div>
        <div style="grid-column:span 2; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div>
            <label class="form-label">Provinsi</label>
            <select name="province_id" id="province-select" class="form-input" data-selected="{{ $settings['province_id'] ?? '' }}" required>
                <option value="">Pilih Provinsi</option>
            </select>
            <input type="hidden" name="province_name" id="province-name" value="{{ $settings['province_name'] ?? '' }}">
          </div>
          <div>
            <label class="form-label">Kota/Kabupaten</label>
            <select name="city_id" id="city-select" class="form-input" data-selected="{{ $settings['city_id'] ?? '' }}" required disabled>
                <option value="">Pilih Kota/Kabupaten</option>
            </select>
            <input type="hidden" name="city_name" id="city-name" value="{{ $settings['city_name'] ?? '' }}">
          </div>
          <div>
            <label class="form-label">Kecamatan</label>
            <select name="district_id" id="district-select" class="form-input" data-selected="{{ $settings['district_id'] ?? '' }}" required disabled>
                <option value="">Pilih Kecamatan</option>
            </select>
            <input type="hidden" name="district_name" id="district-name" value="{{ $settings['district_name'] ?? '' }}">
          </div>
          <div>
            <label class="form-label">Kelurahan / Desa</label>
            <select name="village_id" id="village-select" class="form-input" data-selected="{{ $settings['village_id'] ?? '' }}" required disabled>
                <option value="">Pilih Kelurahan</option>
            </select>
            <input type="hidden" name="village_name" id="village-name" value="{{ $settings['village_name'] ?? '' }}">
          </div>
          <div>
            <label class="form-label" for="s-postal_code">Kode Pos</label>
            <input type="text" name="postal_code" id="s-postal_code" class="form-input" value="{{ $settings['postal_code'] ?? '' }}" placeholder="Kode Pos">
          </div>
        </div>
        <div style="grid-column:span 2;">
          <label class="form-label" for="s-address">Alamat Jalan / Gedung (Detail)</label>
          <textarea name="address" id="s-address" class="form-input" rows="3" placeholder="Jl. ... No. ...">{{ $settings['address'] ?? '' }}</textarea>
          <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Tampil di footer & halaman kontak</p>
        </div>
        <div style="grid-column:span 2;">
          <label class="form-label" for="s-hours">Jam Operasional</label>
          <input type="text" name="hours" id="s-hours" class="form-input" value="{{ $settings['hours'] ?? '' }}" placeholder="Senin – Sabtu, 08.00 – 18.00 WIB">
          <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Tampil di footer</p>
        </div>
        <div style="grid-column:span 2;">
          <label class="form-label" for="s-maps_embed">URL Google Maps Embed</label>
          <input type="text" name="maps_embed" id="s-maps_embed" class="form-input" value="{{ $settings['maps_embed'] ?? '' }}" placeholder="https://maps.google.com/maps?q=...&output=embed">
          <p style="font-size:.7rem;color:#94A3B8;margin:.375rem 0 0;">Buka Google Maps → Share → Embed a map → salin URL dari atribut src iframe-nya</p>
        </div>
      </div>
    </div>

    {{-- Footer Logos (Payment & Expedition) --}}
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <svg width="14" height="14" fill="none" stroke="#0EA5E9" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#0EA5E9;">Logo Pembayaran & Ekspedisi (Footer)</div>
      </div>
      <p style="font-size:.75rem;color:#94A3B8;margin-bottom:1.25rem;line-height:1.6;">Upload gambar berlatar transparan (PNG) untuk ditampilkan di bagian footer bawah.</p>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        {{-- Payment Logo --}}
        <div>
          <label class="form-label" style="display:block;margin-bottom:.5rem;">Logo Metode Pembayaran</label>
          @if(!empty($settings['payment_logos']))
            <div style="margin-bottom:1rem;padding:.75rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;">
              <img src="{{ asset('storage/'.$settings['payment_logos']) }}" alt="Payment Logos" style="max-height:40px;object-fit:contain;">
              <div style="font-size:.7rem;color:#94A3B8;margin-top:.25rem;">Logo saat ini</div>
            </div>
          @endif
          <input type="file" name="payment_logos" class="form-input" accept="image/*" style="padding:.5rem;">
        </div>

        {{-- Expedition Logo --}}
        <div>
          <label class="form-label" style="display:block;margin-bottom:.5rem;">Logo Jasa Pengiriman</label>
          @if(!empty($settings['expedition_logos']))
            <div style="margin-bottom:1rem;padding:.75rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;">
              <img src="{{ asset('storage/'.$settings['expedition_logos']) }}" alt="Expedition Logos" style="max-height:40px;object-fit:contain;">
              <div style="font-size:.7rem;color:#94A3B8;margin-top:.25rem;">Logo saat ini</div>
            </div>
          @endif
          <input type="file" name="expedition_logos" class="form-input" accept="image/*" style="padding:.5rem;">
        </div>
      </div>
    </div>

    {{-- WhatsApp Numbers Management --}}
    @php $waSettings = \App\Models\WaSetting::ordered()->get(); @endphp
    <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <div style="display:flex;align-items:center;gap:.5rem;">
          <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" style="color:#25D366;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
          <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#25D366;">Nomor WhatsApp (Floating Button & Order Modal)</div>
        </div>
      </div>
      <p style="font-size:.75rem;color:#94A3B8;margin-bottom:1.25rem;line-height:1.6;">Nomor di bawah ini digunakan pada <strong style="color:#475569;">tombol WA mengambang</strong> dan <strong style="color:#475569;">form order modal</strong> di website. Centang <em>Utama</em> untuk nomor yang aktif dipakai.</p>

      </form>{{-- tutup form settings sementara --}}

      {{-- WA Update Form (terpisah agar tidak konflik submit) --}}
      <form method="POST" action="{{ route('admin.wa.update') }}">
        @csrf
        <input type="hidden" name="ids[]" value="">
        @forelse($waSettings as $wa)
        <input type="hidden" name="ids[]" value="{{ $wa->id }}">
        <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;padding:1rem;margin-bottom:.75rem;">
          <div style="display:grid;grid-template-columns:1fr 1fr auto auto;gap:.75rem;align-items:end;">
            <div>
              <label class="form-label">Label</label>
              <input type="text" name="label[{{ $wa->id }}]" class="form-input" value="{{ $wa->label }}" placeholder="Contoh: CS Utama">
            </div>
            <div>
              <label class="form-label">Nomor WA <span style="color:#94A3B8;font-weight:400;">(format: 628xxx)</span></label>
              <input type="text" name="nomor_wa[{{ $wa->id }}]" class="form-input" value="{{ $wa->nomor_wa }}" placeholder="628xxxxxxxxxx">
            </div>
            <div style="display:flex;flex-direction:column;gap:.375rem;align-items:center;">
              <label class="form-label" style="text-align:center;">Utama</label>
              <input type="radio" name="primary" value="{{ $wa->id }}" {{ $wa->is_primary ? 'checked' : '' }} style="width:18px;height:18px;accent-color:#0EA5E9;cursor:pointer;">
            </div>
            <div>
              <label class="form-label" style="display:block;margin-bottom:.375rem;">Hapus</label>
              <a href="#" onclick="if(confirm('Hapus nomor ini?')) { document.getElementById('del-wa-{{ $wa->id }}').submit(); } return false;"
                style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:6px;color:#f87171;text-decoration:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </a>
            </div>
          </div>
          <div style="margin-top:.75rem;">
            <label class="form-label">Template Pesan WA</label>
            <input type="text" name="template_pesan[{{ $wa->id }}]" class="form-input" value="{{ $wa->template_pesan }}" placeholder="Halo, saya ingin menanyakan produk [produk]">
            <p style="font-size:.7rem;color:#94A3B8;margin:.25rem 0 0;">Gunakan [produk] untuk diganti nama produk secara otomatis.</p>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:1.5rem;color:#94A3B8;font-size:.8rem;">Belum ada nomor WhatsApp. Tambahkan di bawah.</div>
        @endforelse
        @if($waSettings->count() > 0)
        <button type="submit" style="display:inline-flex;align-items:center;gap:.375rem;padding:.5rem 1.25rem;font-size:.8rem;font-weight:700;background:#25D366;color:#0F172A;border:none;border-radius:6px;cursor:pointer;margin-bottom:1rem;">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          Simpan Nomor WA
        </button>
        @endif
      </form>

      {{-- Add New WA Form --}}
      <form method="POST" action="{{ route('admin.wa.store') }}" style="border-top:1px solid rgba(255,255,255,.07);padding-top:1rem;margin-top:.5rem;">
        @csrf
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text3);margin-bottom:.75rem;">+ Tambah Nomor Baru</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
          <div>
            <label class="form-label">Label</label>
            <input type="text" name="label" class="form-input" placeholder="Contoh: CS Backup" required>
          </div>
          <div>
            <label class="form-label">Nomor WA <span style="color:#94A3B8;font-weight:400;">(628xxx)</span></label>
            <input type="text" name="nomor_wa" class="form-input" placeholder="628xxxxxxxxxx" required>
          </div>
          <div style="grid-column:span 2;">
            <label class="form-label">Template Pesan</label>
            <input type="text" name="template_pesan" class="form-input" placeholder="Halo, saya ingin menanyakan produk [produk]" required>
          </div>
        </div>
        <button type="submit" style="margin-top:.75rem;display:inline-flex;align-items:center;gap:.375rem;padding:.5rem 1rem;font-size:.8rem;font-weight:700;background:rgba(37,211,102,.15);color:#25D366;border:1px solid rgba(37,211,102,.3);border-radius:6px;cursor:pointer;">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Tambah Nomor
        </button>
      </form>

      {{-- Reopen settings form --}}
      <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" style="display:none;"></form>
    </div>
  </div>
</div>

{{-- ======== TAB: API ======== --}}
<div id="tab-api" class="tab-section" style="display:none;">
  <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf @method('POST')
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
      <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;">
        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
          <svg width="14" height="14" fill="none" stroke="#F59E0B" stroke-width="2" viewBox="0 0 24 24"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
          <div style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#F59E0B;">API Pengiriman & Pembayaran</div>
        </div>
        <p style="font-size:.75rem;color:#94A3B8;margin-bottom:1.25rem;line-height:1.6;">Konfigurasi kunci API untuk layanan pihak ketiga (Ongkir & Payment Gateway).</p>
        
        <div style="display:grid;grid-template-columns:1fr;gap:1.25rem;">
          {{-- API RajaOngkir --}}
          <div style="border-bottom:1px dashed #E2E8F0;padding-bottom:1.25rem;">
            <h4 style="font-size:0.9rem;font-weight:700;margin-bottom:0.75rem;color:#334155;">API Cek Resi / Ongkir (RajaOngkir)</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
              <div>
                <label class="form-label">API Key</label>
                <input type="text" name="rajaongkir_api_key" class="form-input" value="{{ $settings['rajaongkir_api_key'] ?? '' }}" placeholder="Kunci API RajaOngkir">
              </div>
              <div>
                <label class="form-label">Tipe Akun</label>
                <select name="rajaongkir_type" class="form-input">
                  <option value="starter" {{ ($settings['rajaongkir_type'] ?? '') == 'starter' ? 'selected' : '' }}>Starter</option>
                  <option value="basic" {{ ($settings['rajaongkir_type'] ?? '') == 'basic' ? 'selected' : '' }}>Basic</option>
                  <option value="pro" {{ ($settings['rajaongkir_type'] ?? '') == 'pro' ? 'selected' : '' }}>Pro</option>
                </select>
              </div>
            </div>
          </div>
          
          {{-- API Midtrans --}}
          <div>
            <h4 style="font-size:0.9rem;font-weight:700;margin-bottom:0.75rem;color:#334155;">API Payment Gateway (Midtrans)</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
              <div style="grid-column:span 2;">
                <label class="form-label">Environment</label>
                <select name="midtrans_is_production" class="form-input">
                  <option value="0" {{ ($settings['midtrans_is_production'] ?? '0') == '0' ? 'selected' : '' }}>Sandbox (Testing)</option>
                  <option value="1" {{ ($settings['midtrans_is_production'] ?? '0') == '1' ? 'selected' : '' }}>Production (Live)</option>
                </select>
              </div>
              <div>
                <label class="form-label">Client Key</label>
                <input type="text" name="midtrans_client_key" class="form-input" value="{{ $settings['midtrans_client_key'] ?? '' }}" placeholder="Midtrans Client Key">
              </div>
              <div>
                <label class="form-label">Server Key</label>
                <input type="text" name="midtrans_server_key" class="form-input" value="{{ $settings['midtrans_server_key'] ?? '' }}" placeholder="Midtrans Server Key">
              </div>
            </div>
          </div>
        </div>

        <div style="margin-top:1.5rem;text-align:right;">
          <button type="submit" style="display:inline-flex;align-items:center;gap:.375rem;padding:.5rem 1.25rem;font-size:.875rem;font-weight:700;background:#0EA5E9;color:#ffffff;border:none;border-radius:4px;cursor:pointer;transition:all .2s;font-family:'Montserrat',sans-serif;">Simpan API</button>
        </div>
      </div>
    </div>
  </form>
</div>

</form>

{{-- Hidden Delete Forms for WA --}}
@php $waAll = \App\Models\WaSetting::all(); @endphp
@foreach($waAll as $waItem)
<form id="del-wa-{{ $waItem->id }}" method="POST" action="{{ route('admin.wa.destroy', $waItem->id) }}" style="display:none;">
  @csrf @method('DELETE')
</form>
@endforeach

{{-- Hidden Delete Forms for Hero Slides --}}
@php $allSlidesHidden = \App\Models\HeroSlide::all(); @endphp
@foreach($allSlidesHidden as $hs)
<form id="del-slide-{{ $hs->id }}" method="POST" action="{{ route('admin.hero_slides.destroy', $hs->id) }}" style="display:none;">
  @csrf @method('DELETE')
</form>
@endforeach

<style>
.tab-btn-active { border-bottom-color:#0EA5E9 !important; color:#0EA5E9 !important; }
</style>
<script>
function switchTab(name) {
    document.querySelectorAll('.tab-section').forEach(s => s.style.display='none');
    document.querySelectorAll('[id^="tab-btn-"]').forEach(b => b.classList.remove('tab-btn-active'));
    document.getElementById('tab-'+name).style.display='block';
    document.getElementById('tab-btn-'+name).classList.add('tab-btn-active');
}
function updateCounter(el, cntId) {
    document.getElementById(cntId).textContent = el.value.length;
}
switchTab('general');
</script>
<style>
@keyframes fadeIn { from { opacity:0; transform:translateY(-5px); } to { opacity:1; transform:translateY(0); } }
</style>

<script>
// EMSIFA API WILAYAH INDONESIA
document.addEventListener("DOMContentLoaded", function() {
    const apiBase = "https://www.emsifa.com/api-wilayah-indonesia/api";
    
    const provSelect = document.getElementById("province-select");
    const citySelect = document.getElementById("city-select");
    const distSelect = document.getElementById("district-select");
    const villSelect = document.getElementById("village-select");
    
    const provName = document.getElementById("province-name");
    const cityName = document.getElementById("city-name");
    const distName = document.getElementById("district-name");
    const villName = document.getElementById("village-name");

    const selProv = provSelect.getAttribute('data-selected');
    const selCity = citySelect.getAttribute('data-selected');
    const selDist = distSelect.getAttribute('data-selected');
    const selVill = villSelect.getAttribute('data-selected');

    // Fetch Provinces
    fetch(`${apiBase}/provinces.json`)
        .then(response => response.json())
        .then(provinces => {
            provinces.forEach(prov => {
                let option = new Option(prov.name, prov.id);
                if (prov.id == selProv) option.selected = true;
                provSelect.add(option);
            });
            if (selProv) {
                loadCities(selProv, selCity);
            }
        });

    provSelect.addEventListener('change', function() {
        provName.value = this.options[this.selectedIndex].text;
        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        distSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        villSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
        citySelect.disabled = distSelect.disabled = villSelect.disabled = true;
        cityName.value = distName.value = villName.value = '';
        
        if (this.value) loadCities(this.value);
    });

    citySelect.addEventListener('change', function() {
        cityName.value = this.options[this.selectedIndex].text;
        distSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        villSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
        distSelect.disabled = villSelect.disabled = true;
        distName.value = villName.value = '';

        if (this.value) loadDistricts(this.value);
    });

    distSelect.addEventListener('change', function() {
        distName.value = this.options[this.selectedIndex].text;
        villSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
        villSelect.disabled = true;
        villName.value = '';

        if (this.value) loadVillages(this.value);
    });

    villSelect.addEventListener('change', function() {
        villName.value = this.options[this.selectedIndex].text;
    });

    function loadCities(provId, selectedId = null) {
        fetch(`${apiBase}/regencies/${provId}.json`)
            .then(res => res.json())
            .then(cities => {
                cities.forEach(city => {
                    let option = new Option(city.name, city.id);
                    if (city.id == selectedId) option.selected = true;
                    citySelect.add(option);
                });
                citySelect.disabled = false;
                if (selectedId) loadDistricts(selectedId, selDist);
            });
    }

    function loadDistricts(cityId, selectedId = null) {
        fetch(`${apiBase}/districts/${cityId}.json`)
            .then(res => res.json())
            .then(districts => {
                districts.forEach(dist => {
                    let option = new Option(dist.name, dist.id);
                    if (dist.id == selectedId) option.selected = true;
                    distSelect.add(option);
                });
                distSelect.disabled = false;
                if (selectedId) loadVillages(selectedId, selVill);
            });
    }

    function loadVillages(distId, selectedId = null) {
        fetch(`${apiBase}/villages/${distId}.json`)
            .then(res => res.json())
            .then(villages => {
                villages.forEach(vill => {
                    let option = new Option(vill.name, vill.id);
                    if (vill.id == selectedId) option.selected = true;
                    villSelect.add(option);
                });
                villSelect.disabled = false;
            });
    }
});
</script>
@endsection
