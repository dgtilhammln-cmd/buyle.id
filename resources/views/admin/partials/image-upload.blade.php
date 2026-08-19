{{-- Shared Image Upload Partial --}}
{{-- Usage: @include('admin.partials.image-upload', ['item'=>$item??null,'field'=>'image','label'=>'Foto Utama','required'=>true]) --}}
@php
  $imgField      = $field       ?? 'image';
  $imgLabel      = $label       ?? 'Gambar';
  $imgRequired   = $required    ?? false;
  $imgItem       = $item        ?? null;
  $imgPreviewId  = 'prev_'.Str::random(6);
  $imgInputId    = 'inp_'.Str::random(6);
  $currentSrc    = $imgItem && $imgItem->{$imgField} ? asset('storage/'.$imgItem->{$imgField}) : null;
  // aspectRatio: '1:1' | '16:9' | '4:3' | 'free'  (default: 'free')
  $imgAspectRatio = $aspectRatio ?? 'free';
@endphp

<div style="background:#fff;border-radius:20px;padding:1.5rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
  {{-- Header --}}
  <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1rem;">
    <div style="width:28px;height:28px;background:rgba(59,130,246,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
      <svg width="14" height="14" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
    </div>
    <h3 style="font-size:.8rem;font-weight:800;color:#1E293B;margin:0;">{{ $imgLabel }}</h3>
  </div>

  {{-- Current saved image --}}
  @if($currentSrc)
  <div style="position:relative;border-radius:10px;overflow:hidden;border:1.5px solid #E4E7F0;margin-bottom:.75rem;cursor:pointer;" onclick="document.getElementById('{{ $imgInputId }}').click()">
    <img src="{{ $currentSrc }}" style="width:100%;height:140px;object-fit:cover;display:block;">
    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.4);display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .2s;"
         onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
      <div style="color:#fff;font-size:.78rem;font-weight:700;display:flex;flex-direction:column;align-items:center;gap:.3rem;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Ganti Foto
      </div>
    </div>
  </div>
  @endif

  {{-- New file preview (shown after picking new file) --}}
  <div id="{{ $imgPreviewId }}_newwrap" style="display:none;position:relative;border-radius:10px;overflow:hidden;border:2px solid #3B82F6;margin-bottom:.75rem;">
    <img id="{{ $imgPreviewId }}_new" src="" style="width:100%;height:140px;object-fit:cover;display:block;">
    <button type="button" onclick="removeNewImg('{{ $imgInputId }}','{{ $imgPreviewId }}')"
      style="position:absolute;top:.375rem;right:.375rem;width:28px;height:28px;background:rgba(239,68,68,0.95);border:none;border-radius:6px;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,0.3);">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
    </button>
    <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(59,130,246,0.85);padding:.3rem .625rem;font-size:.68rem;color:#fff;font-weight:700;">
      ✓ Gambar baru dipilih — belum tersimpan
    </div>
  </div>

  {{-- Upload trigger --}}
  <div id="{{ $imgPreviewId }}_trigger"
       onclick="document.getElementById('{{ $imgInputId }}').click()"
       style="display:{{ $currentSrc ? 'none' : 'flex' }};flex-direction:column;align-items:center;gap:.5rem;padding:1.25rem;border:2px dashed #E4E7F0;border-radius:12px;cursor:pointer;transition:all .2s;text-align:center;"
       onmouseover="this.style.borderColor='#3B82F6';this.style.background='#F0F6FF'"
       onmouseout="this.style.borderColor='#E4E7F0';this.style.background='transparent'">
    <div style="width:40px;height:40px;background:#EFF6FF;border-radius:10px;display:flex;align-items:center;justify-content:center;">
      <svg width="20" height="20" fill="none" stroke="#3B82F6" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
    </div>
    <div style="font-size:.8rem;font-weight:700;color:#1E293B;">Klik untuk upload</div>
    <div style="font-size:.72rem;color:#94A3B8;">JPG, PNG, WebP — Maks 8MB</div>
  </div>

  @if($currentSrc)
  <div style="text-align:center;margin-top:.5rem;">
    <button type="button" onclick="document.getElementById('{{ $imgInputId }}').click()"
      style="font-size:.72rem;font-weight:600;color:#3B82F6;background:none;border:none;cursor:pointer;text-decoration:underline;">
      Ganti foto
    </button>
  </div>
  @endif

  <input type="file" id="{{ $imgInputId }}" name="{{ $imgField }}" accept="image/*"
         {{ $imgRequired && !$imgItem ? 'required' : '' }}
         data-aspect-ratio="{{ $imgAspectRatio }}"
         onchange="previewImgPremium(this,'{{ $imgPreviewId }}')"
         style="display:none;">

  <div style="display:flex;align-items:center;gap:.375rem;margin-top:.625rem;font-size:.68rem;color:#94A3B8;">
    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
    Auto convert ke WebP + kompres otomatis.
  </div>
</div>

<script>
if (typeof window._imgUploadInit === 'undefined') {
    window._imgUploadInit = true;

    window.removeNewImg = function(inputId, previewId) {
        document.getElementById(inputId).value = '';
        var w = document.getElementById(previewId + '_newwrap');
        var n = document.getElementById(previewId + '_new');
        var t = document.getElementById(previewId + '_trigger');
        w.style.display = 'none';
        n.src = '';
        var cur = document.getElementById(previewId + '_current');
        if (!cur && t) t.style.display = 'flex';
    };

    window.initCropperModal = function(input, imgPreviewId) {
        if (!document.getElementById('cropper-css')) {
            var css = document.createElement('link');
            css.id = 'cropper-css'; css.rel = 'stylesheet';
            css.href = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css';
            document.head.appendChild(css);
        }
        if (!window.Cropper) {
            var js = document.createElement('script');
            js.src = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js';
            js.onload = function() { openCropper(input, imgPreviewId); };
            document.head.appendChild(js);
        } else {
            openCropper(input, imgPreviewId);
        }
    };

    window.openCropper = function(input, imgPreviewId) {
        if (!input.files || !input.files[0]) return;
        var modal = document.createElement('div');
        modal.style.cssText = 'position:fixed;inset:0;background:rgba(15,23,42,0.8);z-index:9999;display:flex;align-items:center;justify-content:center;padding:2rem;backdrop-filter:blur(4px);';
        var container = document.createElement('div');
        container.style.cssText = 'background:#fff;border-radius:20px;padding:1.5rem;max-width:800px;width:100%;display:flex;flex-direction:column;gap:1.5rem;box-shadow:0 24px 64px rgba(0,0,0,0.2);';
        var title = document.createElement('h3');
        title.style.cssText = 'margin:0;font-size:1.1rem;font-weight:800;color:#1E293B;';
        title.innerText = 'Crop Gambar';
        var imgWrapper = document.createElement('div');
        imgWrapper.style.cssText = 'width:100%;height:60vh;max-height:500px;background:#F8FAFC;border-radius:12px;overflow:hidden;';
        var imgToCrop = document.createElement('img');
        imgToCrop.style.maxWidth = '100%'; imgToCrop.style.display = 'block';
        imgToCrop.src = URL.createObjectURL(input.files[0]);
        imgWrapper.appendChild(imgToCrop);
        var btnWrapper = document.createElement('div');
        btnWrapper.style.cssText = 'display:flex;justify-content:flex-end;gap:.75rem;';
        var btnCancel = document.createElement('button');
        btnCancel.innerText = 'Batal'; btnCancel.type = 'button';
        btnCancel.style.cssText = 'padding:.75rem 1.5rem;background:#F1F5F9;color:#475569;border:none;border-radius:12px;font-weight:700;cursor:pointer;';
        var btnCrop = document.createElement('button');
        btnCrop.innerText = 'Crop & Simpan'; btnCrop.type = 'button';
        btnCrop.style.cssText = 'padding:.75rem 1.5rem;background:#3B82F6;color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;';
        btnWrapper.appendChild(btnCancel); btnWrapper.appendChild(btnCrop);
        container.appendChild(title); container.appendChild(imgWrapper); container.appendChild(btnWrapper);
        modal.appendChild(container); document.body.appendChild(modal);
        var _ar = input.getAttribute('data-aspect-ratio') || 'free';
        var _cropAR = _ar === '1:1' ? 1 : _ar === '16:9' ? 16/9 : _ar === '4:3' ? 4/3 : NaN;
        var _w = _ar === '1:1' ? 1000 : _ar === '4:3' ? 1200 : 1200;
        var _h = _ar === '1:1' ? 1000 : _ar === '4:3' ? 900  : 675;
        var cropper = new Cropper(imgToCrop, { aspectRatio: _cropAR, viewMode: 2, autoCropArea: 1 });
        btnCancel.onclick = function() { input.value = ''; document.body.removeChild(modal); };
        btnCrop.onclick = function() {
            btnCrop.innerText = 'Menyimpan...';
            cropper.getCroppedCanvas({ width:_w, height:_h, imageSmoothingQuality:'high' }).toBlob(function(blob) {
                var dt = new DataTransfer();
                dt.items.add(new File([blob], input.files[0].name, {type: input.files[0].type}));
                input.files = dt.files;
                var newImg = document.getElementById(imgPreviewId + '_new');
                var newWrap = document.getElementById(imgPreviewId + '_newwrap');
                var trigger = document.getElementById(imgPreviewId + '_trigger');
                newImg.src = URL.createObjectURL(blob);
                newWrap.style.display = 'block';
                if (trigger) trigger.style.display = 'none';
                document.body.removeChild(modal);
            }, input.files[0].type, 0.9);
        };
    };
}

function previewImgPremium(input, id) {
    if (!input.files || !input.files[0]) return;
    var newImg = document.getElementById(id + '_new');
    var newWrap = document.getElementById(id + '_newwrap');
    var trigger = document.getElementById(id + '_trigger');
    newImg.src = URL.createObjectURL(input.files[0]);
    newWrap.style.display = 'block';
    if (trigger) trigger.style.display = 'none';
    if (window.initCropperModal) window.initCropperModal(input, id);
}
</script>
