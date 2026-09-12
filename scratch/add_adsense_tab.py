idx_path = 'resources/views/admin/settings/index.blade.php'
with open(idx_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add tab button
old_tabs = "'email'   => ['Pengaturan Email (SMTP)', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],"
new_tabs = "'adsense' => ['Monetisasi AdSense', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],\n      'email'   => ['Pengaturan Email (SMTP)', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],"

content = content.replace(old_tabs, new_tabs)

# 2. Add Tab Section Content
adsense_tab_html = """
{{-- ======== TAB: MONETISASI ADSENSE ======== --}}
<div class="tab-section" id="tab-adsense" style="display:none;">
  <div style="background:#FFFFFF;border:1px solid #E2E8F0;box-shadow:0 4px 15px rgba(0,0,0,0.03);border-radius:10px;padding:1.5rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
      <svg width="18" height="18" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <div style="font-size:.75rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#10B981;">Pengaturan Google AdSense & Pop-up Monetisasi</div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
      <div>
        <label class="form-label">Status Google AdSense <span>(Aktifkan Iklan)</span></label>
        <select name="adsense_status" class="form-input">
          <option value="enabled" {{ (old('adsense_status', $settings['adsense_status'] ?? 'enabled')) === 'enabled' ? 'selected' : '' }}>Aktif (Enabled)</option>
          <option value="disabled" {{ (old('adsense_status', $settings['adsense_status'] ?? 'enabled')) === 'disabled' ? 'selected' : '' }}>Nonaktif (Disabled)</option>
        </select>
        <p style="font-size:0.75rem;color:#94A3B8;margin-top:0.35rem;">Aktifkan untuk menampilkan iklan Google AdSense pada platform buyle.id.</p>
      </div>

      <div>
        <label class="form-label">AdSense Publisher ID <span>(ca-pub-XXXXXXXXXXXX)</span></label>
        <input type="text" name="adsense_publisher_id" class="form-input" value="{{ old('adsense_publisher_id', $settings['adsense_publisher_id'] ?? 'ca-pub-8031682064726767') }}" placeholder="ca-pub-8031682064726767">
        <p style="font-size:0.75rem;color:#94A3B8;margin-top:0.35rem;">ID Akun Pembayaran/AdSense Anda dari Google AdSense Console.</p>
      </div>

      <div>
        <label class="form-label">Pop-up Interstitial Ads pada Link Bio <span>(Sebelum Navigasi)</span></label>
        <select name="adsense_bio_interstitial_status" class="form-input">
          <option value="enabled" {{ (old('adsense_bio_interstitial_status', $settings['adsense_bio_interstitial_status'] ?? 'enabled')) === 'enabled' ? 'selected' : '' }}>Aktif (Tampilkan Pop-up Iklan saat Link/Button Bio diklik)</option>
          <option value="disabled" {{ (old('adsense_bio_interstitial_status', $settings['adsense_bio_interstitial_status'] ?? 'enabled')) === 'disabled' ? 'selected' : '' }}>Nonaktif (Direct Langsung Tanpa Pop-up)</option>
        </select>
        <p style="font-size:0.75rem;color:#94A3B8;margin-top:0.35rem;">Tampilkan pop-up modal iklan + timer hitung mundur sebelum mengarahkan pengklik.</p>
      </div>

      <div>
        <label class="form-label">Durasi Jeda Iklan Pop-up <span>(Detik Hitung Mundur)</span></label>
        <input type="number" name="adsense_interstitial_delay" class="form-input" value="{{ old('adsense_interstitial_delay', $settings['adsense_interstitial_delay'] ?? '3') }}" placeholder="3" min="1" max="30">
        <p style="font-size:0.75rem;color:#94A3B8;margin-top:0.35rem;">Jumlah detik penundaan sebelum tombol 'Lanjutkan ke Link' otomatis terbuka.</p>
      </div>
    </div>

    <div style="margin-top:1.25rem;">
      <label class="form-label">Script Custom AdSense / Auto-Ads Tag <span>(Opsional Tag Script Lengkap)</span></label>
      <textarea name="adsense_script_code" class="form-input" rows="4" style="font-family:monospace;font-size:0.8rem;" placeholder='<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8031682064726767" crossorigin="anonymous"></script>'>{{ old('adsense_script_code', $settings['adsense_script_code'] ?? '') }}</textarea>
      <p style="font-size:0.75rem;color:#94A3B8;margin-top:0.35rem;">Kosongkan jika menggunakan Publisher ID standar di atas. Masukkan kode ini jika memiliki Auto Ads Tag khusus dari AdSense Console.</p>
    </div>

    <div style="margin-top:1.5rem;text-align:right;">
      <button type="submit" style="display:inline-flex;align-items:center;gap:.375rem;padding:.5rem 1.25rem;font-size:.875rem;font-weight:700;background:linear-gradient(135deg, #1eb349, #a5cf37);color:#ffffff;border:none;border-radius:4px;cursor:pointer;transition:all .2s;font-family:'Montserrat',sans-serif;">Simpan Pengaturan AdSense</button>
    </div>
  </div>
</div>
"""

target_marker = "  {{-- Card Tes Kirim Email --}}\n  <div style=\"background:#FFFFFF;border:1px solid #E2E8F0;"
content = content.replace("  {{-- Card Tes Kirim Email --}}", adsense_tab_html + "\n  {{-- Card Tes Kirim Email --}}")

with open(idx_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Added AdSense tab to Admin Settings page.")
