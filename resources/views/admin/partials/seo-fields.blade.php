{{-- Shared SEO Fields Partial --}}
{{-- Usage: @include('admin.partials.seo-fields', ['item' => $item ?? null]) --}}
@php $s = $item ?? null; @endphp

<div style="background:#fff;border-radius:20px;padding:1.75rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
  <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1.5rem;">
    <div style="width:32px;height:32px;background:rgba(16,185,129,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
      <svg width="16" height="16" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </div>
    <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">SEO Settings</h3>
  </div>

  <div style="display:flex;flex-direction:column;gap:1.125rem;">
    {{-- Meta Title --}}
    <div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem;">
        <label style="font-size:.8rem;font-weight:700;color:#374151;">Meta Title <span style="color:#EF4444;">*</span></label>
        <span style="font-size:.72rem;color:#94A3B8;"><span id="seo-mt-cnt">{{ strlen(old('meta_title', $s?->getRawOriginal('meta_title') ?? '')) }}</span>/70</span>
      </div>
      <input type="text" name="meta_title" value="{{ old('meta_title', $s?->getRawOriginal('meta_title')) }}" maxlength="70" required
        oninput="document.getElementById('seo-mt-cnt').textContent=this.value.length;seoTitleBar(this)"
        style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
        onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'"
        placeholder="Judul SEO (idealnya 50–60 karakter)">
      {{-- Progress bar --}}
      <div style="margin-top:.375rem;height:3px;background:#F1F5F9;border-radius:100px;overflow:hidden;">
        <div id="seo-mt-bar" style="height:100%;border-radius:100px;transition:width .3s,background .3s;background:#10B981;width:{{ min(100, round(strlen(old('meta_title', $s?->getRawOriginal('meta_title') ?? ''))/70*100)) }}%;"></div>
      </div>
    </div>

    {{-- Meta Description --}}
    <div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem;">
        <label style="font-size:.8rem;font-weight:700;color:#374151;">Meta Description <span style="color:#EF4444;">*</span></label>
        <span style="font-size:.72rem;color:#94A3B8;"><span id="seo-md-cnt">{{ strlen(old('meta_desc', $s?->getRawOriginal('meta_desc') ?? '')) }}</span>/160</span>
      </div>
      <textarea name="meta_desc" rows="3" maxlength="160" required
        oninput="document.getElementById('seo-md-cnt').textContent=this.value.length;seoDescBar(this)"
        style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;resize:vertical;box-sizing:border-box;transition:border-color .2s;"
        onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'"
        placeholder="Deskripsi singkat halaman untuk mesin pencari (idealnya 120–160 karakter)...">{{ old('meta_desc', $s?->getRawOriginal('meta_desc')) }}</textarea>
      <div style="margin-top:.375rem;height:3px;background:#F1F5F9;border-radius:100px;overflow:hidden;">
        <div id="seo-md-bar" style="height:100%;border-radius:100px;transition:width .3s,background .3s;background:#10B981;width:{{ min(100, round(strlen(old('meta_desc', $s?->getRawOriginal('meta_desc') ?? ''))/160*100)) }}%;"></div>
      </div>
    </div>

    {{-- Meta Keywords --}}
    <div>
      <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">Meta Keywords <span style="color:#EF4444;">*</span> <span style="font-weight:500;color:#94A3B8;font-size:.75rem;">— pisah dengan koma</span></label>
      <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $s?->meta_keywords) }}" required
        style="width:100%;padding:.75rem 1rem;background:#F8FAFC;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.875rem;color:#1E293B;font-family:inherit;outline:none;box-sizing:border-box;transition:border-color .2s;"
        onfocus="this.style.borderColor='#3B82F6';this.style.background='#fff'" onblur="this.style.borderColor='#E4E7F0';this.style.background='#F8FAFC'"
        placeholder="crane surabaya, overhead crane, lift industri">
    </div>
  </div>
</div>

<script>
function seoTitleBar(el) {
  var pct = Math.min(100, Math.round(el.value.length / 70 * 100));
  var bar = document.getElementById('seo-mt-bar');
  bar.style.width = pct + '%';
  bar.style.background = pct > 90 ? '#EF4444' : pct > 70 ? '#F59E0B' : '#10B981';
}
function seoDescBar(el) {
  var pct = Math.min(100, Math.round(el.value.length / 160 * 100));
  var bar = document.getElementById('seo-md-bar');
  bar.style.width = pct + '%';
  bar.style.background = pct > 90 ? '#EF4444' : pct > 70 ? '#F59E0B' : '#10B981';
}
</script>
