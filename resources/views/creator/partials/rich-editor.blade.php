{{-- Shared Rich Text Editor Partial with Interactive Image Resizing & Formatting --}}
{{-- Usage: @include('creator.partials.rich-editor', ['name'=>'content', 'value'=>$item->content??'', 'height'=>'350px']) --}}
@php $edId = 'editor_'.Str::random(6); $haId = 'ha_'.Str::random(6); @endphp

<div class="rich-editor-wrapper" style="position:relative;border:1.5px solid #E4E7F0;border-radius:12px;overflow:visible;transition:border-color .2s;background:#fff;" onfocusin="this.style.borderColor='#1eb349'" onfocusout="this.style.borderColor='#E4E7F0'">
  {{-- Toolbar --}}
  <div style="display:flex;flex-wrap:wrap;align-items:center;gap:4px;padding:.5rem .75rem;background:#F8FAFC;border-bottom:1px solid #F1F5F9;border-radius:11px 11px 0 0;" id="tb_{{ $edId }}">
    @foreach([['bold','<strong>B</strong>','font-weight:800'],['italic','<em>I</em>','font-style:italic'],['underline','<span style="text-decoration:underline">U</span>','']] as $b)
    <button type="button" onclick="edFmt('{{$edId}}','{{$b[0]}}')"
      style="padding:.3rem .55rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;font-size:.8rem;min-width:30px;font-family:inherit;transition:all .15s;"
      onmouseover="this.style.borderColor='#1eb349';this.style.color='#1eb349'" onmouseout="this.style.borderColor='#E4E7F0';this.style.color='#374151'">{!! $b[1] !!}</button>
    @endforeach
    <div style="width:1px;height:20px;background:#E4E7F0;margin:0 .25rem;"></div>
    @foreach([['h2','H2'],['h3','H3'],['p','¶']] as $b)
    <button type="button" onclick="edBlock('{{$edId}}','{{$b[0]}}')"
      style="padding:.3rem .55rem;background:#fff;border:1.5px solid #E4E7F0;color:#1eb349;border-radius:6px;cursor:pointer;font-size:.78rem;font-weight:700;min-width:30px;transition:all .15s;"
      onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='#fff'">{{$b[1]}}</button>
    @endforeach
    <div style="width:1px;height:20px;background:#E4E7F0;margin:0 .25rem;"></div>
    <button type="button" onclick="edFmt('{{$edId}}','insertUnorderedList')"
      style="padding:.3rem .55rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;transition:all .15s;" title="Bullet List"
      onmouseover="this.style.borderColor='#1eb349'" onmouseout="this.style.borderColor='#E4E7F0'">
      <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><rect x="2" y="5" width="3" height="3" rx="1"/><rect x="8" y="5" width="14" height="3" rx="1"/><rect x="2" y="11" width="3" height="3" rx="1"/><rect x="8" y="11" width="14" height="3" rx="1"/><rect x="2" y="17" width="3" height="3" rx="1"/><rect x="8" y="17" width="14" height="3" rx="1"/></svg>
    </button>
    <button type="button" onclick="edFmt('{{$edId}}','insertOrderedList')"
      style="padding:.3rem .55rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;font-size:.75rem;font-weight:700;transition:all .15s;" title="Numbered List"
      onmouseover="this.style.borderColor='#1eb349'" onmouseout="this.style.borderColor='#E4E7F0'">1.</button>
    <button type="button" onclick="edLink('{{$edId}}')"
      style="padding:.3rem .55rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;transition:all .15s;" title="Insert Link"
      onmouseover="this.style.borderColor='#1eb349'" onmouseout="this.style.borderColor='#E4E7F0'">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
    </button>
    <button type="button" onclick="edImage('{{$edId}}')"
      style="padding:.3rem .55rem;background:#fff;border:1.5px solid #1eb349;color:#1eb349;border-radius:6px;cursor:pointer;transition:all .15s;font-weight:600;display:inline-flex;align-items:center;gap:4px;" title="Insert Image"
      onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='#fff'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
      <span style="font-size:0.75rem;">Foto</span>
    </button>
    <button type="button" onclick="edQuote('{{$edId}}')"
      style="padding:.3rem .55rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;font-size:.85rem;transition:all .15s;" title="Blockquote"
      onmouseover="this.style.borderColor='#1eb349'" onmouseout="this.style.borderColor='#E4E7F0'">&ldquo;</button>
    <button type="button" onclick="edCode('{{$edId}}')"
      style="padding:.3rem .55rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;font-size:.72rem;font-family:monospace;transition:all .15s;" title="Inline Code"
      onmouseover="this.style.borderColor='#1eb349'" onmouseout="this.style.borderColor='#E4E7F0'">&lt;/&gt;</button>
    <div style="width:1px;height:20px;background:#E4E7F0;margin:0 .25rem;"></div>
    <button type="button" id="htmlbtn_{{ $edId }}" onclick="edToggleHtml('{{$edId}}','{{$haId}}')"
      style="padding:.3rem .55rem;background:#fff;border:1.5px solid #E4E7F0;color:#94A3B8;border-radius:6px;cursor:pointer;font-size:.72rem;font-family:monospace;transition:all .15s;">HTML</button>
  </div>

  {{-- Floating Image Tools Popup --}}
  <div id="imgToolbar_{{ $edId }}" style="display:none;position:absolute;z-index:99;background:#1E293B;color:#fff;border-radius:8px;padding:4px 8px;box-shadow:0 10px 25px rgba(0,0,0,0.25);font-size:0.75rem;align-items:center;gap:6px;transform:translateX(-50%);">
    <span style="color:#94A3B8;font-size:0.7rem;font-weight:600;margin-right:2px;">Ukuran:</span>
    <button type="button" class="img-btn-sz" data-sz="25%" style="background:#334155;color:#fff;border:none;border-radius:4px;padding:3px 6px;cursor:pointer;font-size:0.7rem;">25%</button>
    <button type="button" class="img-btn-sz" data-sz="50%" style="background:#334155;color:#fff;border:none;border-radius:4px;padding:3px 6px;cursor:pointer;font-size:0.7rem;">50%</button>
    <button type="button" class="img-btn-sz" data-sz="75%" style="background:#334155;color:#fff;border:none;border-radius:4px;padding:3px 6px;cursor:pointer;font-size:0.7rem;">75%</button>
    <button type="button" class="img-btn-sz" data-sz="100%" style="background:#334155;color:#fff;border:none;border-radius:4px;padding:3px 6px;cursor:pointer;font-size:0.7rem;">100%</button>
    <div style="width:1px;height:14px;background:#475569;margin:0 2px;"></div>
    <span style="color:#94A3B8;font-size:0.7rem;font-weight:600;margin-right:2px;">Posisi:</span>
    <button type="button" class="img-btn-align" data-align="left" style="background:#334155;color:#fff;border:none;border-radius:4px;padding:3px 6px;cursor:pointer;font-size:0.7rem;">Kiri</button>
    <button type="button" class="img-btn-align" data-align="center" style="background:#334155;color:#fff;border:none;border-radius:4px;padding:3px 6px;cursor:pointer;font-size:0.7rem;">Tengah</button>
    <button type="button" class="img-btn-align" data-align="right" style="background:#334155;color:#fff;border:none;border-radius:4px;padding:3px 6px;cursor:pointer;font-size:0.7rem;">Kanan</button>
    <div style="width:1px;height:14px;background:#475569;margin:0 2px;"></div>
    <button type="button" id="imgBtnDelete_{{ $edId }}" style="background:#EF4444;color:#fff;border:none;border-radius:4px;padding:3px 8px;cursor:pointer;font-size:0.7rem;font-weight:700;display:inline-flex;align-items:center;gap:3px;">
      <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      Hapus
    </button>
  </div>

  {{-- Visual editor --}}
  <div id="{{ $edId }}" contenteditable="true"
       style="min-height:{{ $height ?? '320px' }};padding:1.25rem 1.5rem;color:#1E293B;font-size:.9375rem;line-height:1.85;outline:none;font-family:'Montserrat',sans-serif;background:#fff;"
       oninput="document.getElementById('{{ $haId }}').value=this.innerHTML;"
  >{!! $value ?? '' !!}</div>

  {{-- Hidden HTML textarea --}}
  <textarea id="{{ $haId }}" name="{{ $name }}" style="display:none;width:100%;min-height:{{ $height ?? '320px' }};padding:1.25rem;color:#1E293B;font-size:.8rem;line-height:1.6;font-family:'Fira Code',monospace;background:#F8FAFC;border:none;outline:none;resize:vertical;box-sizing:border-box;">{{ $value ?? '' }}</textarea>
</div>

<style>
  #{{ $edId }} img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    cursor: pointer;
    transition: outline 0.15s;
  }
  #{{ $edId }} img.img-selected {
    outline: 3px solid #1eb349 !important;
    outline-offset: 2px;
  }
</style>

<script>
(function(){
  var edId = '{{ $edId }}', haId = '{{ $haId }}';
  var htmlMode = false;
  var currentSelectedImg = null;
  var imgToolbar = document.getElementById('imgToolbar_' + edId);

  window.edFmt = window.edFmt || function(id, cmd) {
    document.getElementById(id).focus();
    document.execCommand(cmd, false, null);
    var ha = document.getElementById('ha_'+id.slice(7));
    if(ha) ha.value = document.getElementById(id).innerHTML;
  };

  window['edFmt'] = function(id, cmd) {
    var ed = document.getElementById(id); if(!ed) return;
    ed.focus(); document.execCommand(cmd,false,null);
    var ha = document.getElementById(haId);
    if(ha) ha.value = ed.innerHTML;
  };
  window['edBlock'] = function(id, tag) { document.getElementById(id).focus(); document.execCommand('formatBlock',false,tag); };
  window['edLink'] = function(id) {
    var url = prompt('URL (https://...):'); if(!url) return;
    var text = prompt('Teks link:') || url;
    document.getElementById(id).focus();
    document.execCommand('insertHTML',false,'<a href="'+url+'" target="_blank" rel="noopener">'+text+'</a>');
  };
  window['edImage'] = function(id) {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function(e) {
        const file = e.target.files[0];
        if(!file) return;
        const ed = document.getElementById(id);
        ed.focus();
        const loadingId = 'img-loading-' + Date.now();
        document.execCommand('insertHTML', false, `<span id="${loadingId}" style="color:#1eb349;font-weight:600;font-style:italic;">[Mengunggah gambar...]</span>`);
        
        const fd = new FormData();
        fd.append('image', file);
        fd.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route("creator.upload.image") }}', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
            const loadingEl = document.getElementById(loadingId);
            if(data.url) {
                const imgHtml = `<img src="${data.url}" style="max-width:100%; width:100%; border-radius:8px; margin:1rem auto; display:block;" alt="Gambar Produk">`;
                if(loadingEl) loadingEl.outerHTML = imgHtml;
                else { ed.focus(); document.execCommand('insertHTML', false, imgHtml); }
                document.getElementById(haId).value = ed.innerHTML;
            } else {
                if(loadingEl) loadingEl.outerHTML = `<span style="color:red;">[Gagal upload]</span>`;
            }
        })
        .catch(err => {
            const loadingEl = document.getElementById(loadingId);
            if(loadingEl) loadingEl.outerHTML = `<span style="color:red;">[Error upload]</span>`;
        });
    };
    input.click();
  };
  window['edQuote'] = function(id) {
    var sel = window.getSelection().toString() || 'Kutipan di sini...';
    document.getElementById(id).focus();
    document.execCommand('insertHTML',false,'<blockquote>'+sel+'</blockquote>');
  };
  window['edCode'] = function(id) {
    var sel = window.getSelection().toString() || 'code';
    document.getElementById(id).focus();
    document.execCommand('insertHTML',false,'<code>'+sel+'</code>');
  };
  window['edToggleHtml'] = function(edId, haId) {
    htmlMode = !htmlMode;
    var ed = document.getElementById(edId);
    var ha = document.getElementById(haId);
    var btn = document.getElementById('htmlbtn_'+edId);
    if(htmlMode) {
      ha.value = ed.innerHTML; ha.style.display='block'; ed.style.display='none';
      btn.style.color='#1eb349'; btn.style.borderColor='#1eb349';
      if(imgToolbar) imgToolbar.style.display = 'none';
    } else {
      ed.innerHTML = ha.value; ed.style.display='block'; ha.style.display='none';
      btn.style.color='#94A3B8'; btn.style.borderColor='#E4E7F0';
    }
  };

  // Image Click & Interactive Toolbar Positioning
  var editorEl = document.getElementById(edId);
  var wrapperEl = editorEl.closest('.rich-editor-wrapper');

  editorEl.addEventListener('click', function(e) {
    if(e.target && e.target.tagName === 'IMG') {
      selectImage(e.target);
    } else {
      deselectImage();
    }
  });

  document.addEventListener('click', function(e) {
    if(!wrapperEl.contains(e.target) && e.target !== currentSelectedImg) {
      deselectImage();
    }
  });

  function selectImage(img) {
    if(currentSelectedImg) currentSelectedImg.classList.remove('img-selected');
    currentSelectedImg = img;
    currentSelectedImg.classList.add('img-selected');

    // Position toolbar directly above the image
    var imgRect = img.getBoundingClientRect();
    var wrapRect = wrapperEl.getBoundingClientRect();

    var topPos = (imgRect.top - wrapRect.top) - 42;
    var leftPos = (imgRect.left - wrapRect.left) + (imgRect.width / 2);

    if(topPos < 45) topPos = (imgRect.top - wrapRect.top) + 10; // if close to top

    imgToolbar.style.top = topPos + 'px';
    imgToolbar.style.left = leftPos + 'px';
    imgToolbar.style.display = 'flex';
  }

  function deselectImage() {
    if(currentSelectedImg) {
      currentSelectedImg.classList.remove('img-selected');
      currentSelectedImg = null;
    }
    if(imgToolbar) imgToolbar.style.display = 'none';
  }

  // Size buttons handlers
  imgToolbar.querySelectorAll('.img-btn-sz').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      if(!currentSelectedImg) return;
      var sz = this.getAttribute('data-sz');
      currentSelectedImg.style.width = sz;
      currentSelectedImg.style.maxWidth = '100%';
      document.getElementById(haId).value = editorEl.innerHTML;
      selectImage(currentSelectedImg); // reposition
    });
  });

  // Alignment buttons handlers
  imgToolbar.querySelectorAll('.img-btn-align').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      if(!currentSelectedImg) return;
      var align = this.getAttribute('data-align');
      if(align === 'center') {
        currentSelectedImg.style.display = 'block';
        currentSelectedImg.style.marginLeft = 'auto';
        currentSelectedImg.style.marginRight = 'auto';
        currentSelectedImg.style.float = 'none';
      } else if(align === 'left') {
        currentSelectedImg.style.display = 'inline-block';
        currentSelectedImg.style.marginLeft = '0';
        currentSelectedImg.style.marginRight = '1rem';
        currentSelectedImg.style.float = 'left';
      } else if(align === 'right') {
        currentSelectedImg.style.display = 'inline-block';
        currentSelectedImg.style.marginLeft = '1rem';
        currentSelectedImg.style.marginRight = '0';
        currentSelectedImg.style.float = 'right';
      }
      document.getElementById(haId).value = editorEl.innerHTML;
      selectImage(currentSelectedImg); // reposition
    });
  });

  // Delete image handler
  var deleteBtn = document.getElementById('imgBtnDelete_' + edId);
  if(deleteBtn) {
    deleteBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      if(currentSelectedImg) {
        currentSelectedImg.remove();
        deselectImage();
        document.getElementById(haId).value = editorEl.innerHTML;
      }
    });
  }

  // Sync before form submit
  document.addEventListener('submit', function() {
    if(!htmlMode) {
      var ed = document.getElementById(edId);
      var ha = document.getElementById(haId);
      if(ed && ha) ha.value = ed.innerHTML;
    }
  });
})();
</script>

