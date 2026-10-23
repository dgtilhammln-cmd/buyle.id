{{-- Shared Modern Rich Text Editor Partial with Sticky Toolbar, Ctrl+K Hyperlink Modal, & Image Resizing --}}
{{-- Usage: @include('creator.partials.rich-editor', ['name'=>'content', 'value'=>$item->content??'', 'height'=>'350px']) --}}
@php $edId = 'editor_'.Str::random(6); $haId = 'ha_'.Str::random(6); @endphp

<div class="modern-rich-editor-box" style="position:relative; border:1.5px solid #E2E8F0; border-radius:14px; overflow:hidden; background:#ffffff; box-shadow:0 1px 3px rgba(0,0,0,0.02); transition:border-color .2s, box-shadow .2s;" onfocusin="this.style.borderColor='#1eb349';this.style.boxShadow='0 0 0 3px rgba(30,179,73,0.12)'" onfocusout="this.style.borderColor='#E2E8F0';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.02)'">
  {{-- Sticky Toolbar --}}
  <div class="mre-toolbar" style="position:sticky; top:0; z-index:15; display:flex; flex-wrap:wrap; align-items:center; gap:4px; padding:0.5rem 0.75rem; background:#F8FAFC; border-bottom:1.5px solid #E2E8F0; box-shadow:0 1px 3px rgba(0,0,0,0.02);" id="tb_{{ $edId }}">
    
    {{-- Format Teks Dasar --}}
    <button type="button" class="mre-tool-btn" onclick="edFmt('{{$edId}}','bold')" title="Tebal (Ctrl+B)">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.7"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg>
    </button>
    <button type="button" class="mre-tool-btn" onclick="edFmt('{{$edId}}','italic')" title="Miring (Ctrl+I)">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
    </button>
    <button type="button" class="mre-tool-btn" onclick="edFmt('{{$edId}}','underline')" title="Garis Bawah (Ctrl+U)">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M6 3v7a6 6 0 0 0 12 0V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg>
    </button>

    <div class="mre-tool-sep"></div>

    {{-- Headings & Paragraf --}}
    <button type="button" class="mre-tool-btn mre-btn-tag" onclick="edBlock('{{$edId}}','h2')" title="Judul Besar (H2)">H2</button>
    <button type="button" class="mre-tool-btn mre-btn-tag" onclick="edBlock('{{$edId}}','h3')" title="Sub-Judul (H3)">H3</button>
    <button type="button" class="mre-tool-btn mre-btn-tag" onclick="edBlock('{{$edId}}','p')" title="Paragraf Normal (¶)">¶</button>

    <div class="mre-tool-sep"></div>

    {{-- List --}}
    <button type="button" class="mre-tool-btn" onclick="edFmt('{{$edId}}','insertUnorderedList')" title="Bullet List">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><rect x="2" y="5" width="3.5" height="3.5" rx="1"/><rect x="8.5" y="5.5" width="13.5" height="2.5" rx="1"/><rect x="2" y="11" width="3.5" height="3.5" rx="1"/><rect x="8.5" y="11.5" width="13.5" height="2.5" rx="1"/><rect x="2" y="17" width="3.5" height="3.5" rx="1"/><rect x="8.5" y="17.5" width="13.5" height="2.5" rx="1"/></svg>
    </button>
    <button type="button" class="mre-tool-btn" onclick="edFmt('{{$edId}}','insertOrderedList')" title="Numbered List">
      <span style="font-size:0.75rem; font-weight:800;">1.</span>
    </button>

    <div class="mre-tool-sep"></div>

    {{-- Hyperlink (Ctrl+K) --}}
    <button type="button" class="mre-tool-btn mre-link-btn" onclick="openLinkModal('{{$edId}}')" title="Sisipkan Link (Shortcut: Ctrl + K)">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
      <span style="font-size:0.72rem; font-weight:700; margin-left:3px;">Link</span>
    </button>

    {{-- Upload Gambar --}}
    <button type="button" class="mre-tool-btn mre-img-btn" onclick="edImage('{{$edId}}')" title="Unggah & Sisipkan Foto">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
      <span style="font-size:0.72rem; font-weight:700; margin-left:3px;">Foto</span>
    </button>

    {{-- Kutipan & Code --}}
    <button type="button" class="mre-tool-btn" onclick="edQuote('{{$edId}}')" title="Kutipan (Blockquote)">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
    </button>
    <button type="button" class="mre-tool-btn" onclick="edCode('{{$edId}}')" title="Kode (Inline Code)">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
    </button>

    <div class="mre-tool-sep"></div>

    {{-- Mode HTML --}}
    <button type="button" class="mre-tool-btn mre-html-btn" id="htmlbtn_{{ $edId }}" onclick="edToggleHtml('{{$edId}}','{{$haId}}')" title="Lihat Source HTML">
      HTML
    </button>
  </div>

  {{-- Visual contenteditable editor with internal scroll (Toolbar stays pinned!) --}}
  <div id="{{ $edId }}" class="mre-content" contenteditable="true"
       style="min-height:{{ $height ?? '320px' }}; max-height:480px; overflow-y:auto; padding:1.25rem 1.5rem; color:#0f172a; font-size:.92rem; line-height:1.8; outline:none; font-family:'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif; background:#ffffff;"
       oninput="document.getElementById('{{ $haId }}').value=this.innerHTML;"
  >{!! $value ?? '' !!}</div>

  {{-- Hidden HTML textarea --}}
  <textarea id="{{ $haId }}" name="{{ $name }}" style="display:none; width:100%; min-height:{{ $height ?? '320px' }}; max-height:480px; padding:1.25rem; color:#1E293B; font-size:.82rem; line-height:1.6; font-family:'Fira Code', monospace; background:#F8FAFC; border:none; outline:none; resize:vertical; box-sizing:border-box;">{{ $value ?? '' }}</textarea>

  {{-- Floating Image Tools Popup --}}
  <div id="imgToolbar_{{ $edId }}" style="display:none; position:absolute; z-index:99; background:#0F172A; color:#fff; border-radius:10px; padding:6px 10px; box-shadow:0 12px 28px rgba(0,0,0,0.28); font-size:0.75rem; align-items:center; gap:6px; transform:translateX(-50%);">
    <span style="color:#94A3B8; font-size:0.7rem; font-weight:600;">Ukuran:</span>
    <button type="button" class="img-btn-sz" data-sz="25%" style="background:#1E293B; color:#fff; border:1px solid #334155; border-radius:6px; padding:3px 7px; cursor:pointer; font-size:0.7rem;">25%</button>
    <button type="button" class="img-btn-sz" data-sz="50%" style="background:#1E293B; color:#fff; border:1px solid #334155; border-radius:6px; padding:3px 7px; cursor:pointer; font-size:0.7rem;">50%</button>
    <button type="button" class="img-btn-sz" data-sz="75%" style="background:#1E293B; color:#fff; border:1px solid #334155; border-radius:6px; padding:3px 7px; cursor:pointer; font-size:0.7rem;">75%</button>
    <button type="button" class="img-btn-sz" data-sz="100%" style="background:#1E293B; color:#fff; border:1px solid #334155; border-radius:6px; padding:3px 7px; cursor:pointer; font-size:0.7rem;">100%</button>
    <div style="width:1px; height:14px; background:#334155; margin:0 2px;"></div>
    <span style="color:#94A3B8; font-size:0.7rem; font-weight:600;">Posisi:</span>
    <button type="button" class="img-btn-align" data-align="left" style="background:#1E293B; color:#fff; border:1px solid #334155; border-radius:6px; padding:3px 7px; cursor:pointer; font-size:0.7rem;">Kiri</button>
    <button type="button" class="img-btn-align" data-align="center" style="background:#1E293B; color:#fff; border:1px solid #334155; border-radius:6px; padding:3px 7px; cursor:pointer; font-size:0.7rem;">Tengah</button>
    <button type="button" class="img-btn-align" data-align="right" style="background:#1E293B; color:#fff; border:1px solid #334155; border-radius:6px; padding:3px 7px; cursor:pointer; font-size:0.7rem;">Kanan</button>
    <div style="width:1px; height:14px; background:#334155; margin:0 2px;"></div>
    <button type="button" id="imgBtnDelete_{{ $edId }}" style="background:#EF4444; color:#fff; border:none; border-radius:6px; padding:3px 8px; cursor:pointer; font-size:0.7rem; font-weight:700; display:inline-flex; align-items:center; gap:3px;">
      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      Hapus
    </button>
  </div>
</div>

{{-- MODAL HYPERLINK (Shortcut Ctrl + K) --}}
<div id="linkModal_{{ $edId }}" class="mre-link-modal" style="display:none; position:fixed; inset:0; z-index:999999; background:rgba(15,23,42,0.65); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; box-sizing:border-box;">
  <div style="width:100%; max-width:440px; background:#ffffff; border-radius:18px; box-shadow:0 24px 60px rgba(0,0,0,0.3); overflow:hidden; border:1px solid #e2e8f0; animation:mrePop 0.18s ease-out;">
    <div style="display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.35rem; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
      <div style="display:flex; align-items:center; gap:0.6rem; font-weight:800; font-size:0.95rem; color:#0f172a;">
        <span style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:8px; background:rgba(30,179,73,0.12); color:#1eb349;">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </span>
        Sisipkan Hyperlink
      </div>
      <span style="font-size:0.7rem; font-weight:700; color:#475569; background:#e2e8f0; padding:2px 8px; border-radius:6px; letter-spacing:0.02em;">Ctrl + K</span>
    </div>
    
    <div style="padding:1.25rem 1.35rem;">
      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:0.4rem;">Teks Tautan</label>
        <input type="text" id="linkText_{{ $edId }}" class="form-input" style="width:100%; height:38px; font-size:0.85rem;" placeholder="Teks yang akan diklik pembaca...">
      </div>
      
      <div style="margin-bottom:1.15rem;">
        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:0.4rem;">URL Target <span>*</span></label>
        <input type="url" id="linkUrl_{{ $edId }}" class="form-input" style="width:100%; height:38px; font-size:0.85rem;" placeholder="https://contoh.com/halaman" onkeydown="if(event.key==='Enter') applyLinkModal('{{ $edId }}');">
        <span style="font-size:0.7rem; color:#94a3b8; margin-top:3px; display:block;">Awali dengan https:// atau http://</span>
      </div>

      <div style="margin-bottom:1.35rem; padding:0.8rem 0.95rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; margin:0; user-select:none;">
          <input type="checkbox" id="linkTarget_{{ $edId }}" checked style="width:17px; height:17px; accent-color:#1eb349; cursor:pointer;">
          <span style="font-size:0.82rem; font-weight:700; color:#0f172a;">Buka tautan di tab baru (target="_blank")</span>
        </label>
        <div style="font-size:0.71rem; color:#64748b; margin-top:4px; margin-left:1.7rem; line-height:1.4;">
          Direkomendasikan agar pembeli tidak keluar dari halaman produk / marketplace Anda.
        </div>
      </div>

      <div style="display:flex; align-items:center; justify-content:flex-end; gap:0.65rem;">
        <button type="button" onclick="closeLinkModal('{{ $edId }}')" style="padding:0.55rem 1rem; border-radius:9px; border:1.5px solid #e2e8f0; background:#ffffff; color:#475569; font-size:0.82rem; font-weight:700; cursor:pointer; transition:all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">Batal</button>
        <button type="button" onclick="applyLinkModal('{{ $edId }}')" style="padding:0.55rem 1.35rem; border-radius:9px; border:none; background:linear-gradient(135deg, #1eb349, #16a34a); color:#ffffff; font-size:0.82rem; font-weight:700; cursor:pointer; box-shadow:0 3px 10px rgba(30,179,73,0.3); transition:all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">Terapkan Link</button>
      </div>
    </div>
  </div>
</div>

<style>
  @keyframes mrePop {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
  }
  .mre-tool-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 31px;
    min-width: 31px;
    padding: 0 7px;
    background: transparent;
    border: 1px solid transparent;
    border-radius: 7px;
    color: #475569;
    cursor: pointer;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.15s ease;
    user-select: none;
  }
  .mre-tool-btn:hover {
    background: #edf2f7;
    color: #0f172a;
    border-color: #e2e8f0;
  }
  .mre-tool-btn:active {
    background: #dcfce7;
    color: #15803d;
    border-color: #86efac;
  }
  .mre-btn-tag {
    font-size: 0.76rem;
    font-weight: 800;
    color: #16a34a;
  }
  .mre-link-btn {
    color: #0284c7;
  }
  .mre-link-btn:hover {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #bae6fd;
  }
  .mre-img-btn {
    color: #15803d;
  }
  .mre-img-btn:hover {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
  }
  .mre-html-btn {
    font-size: 0.72rem;
    font-family: monospace;
    font-weight: 700;
    color: #94a3b8;
  }
  .mre-tool-sep {
    width: 1px;
    height: 18px;
    background: #e2e8f0;
    margin: 0 3px;
    flex-shrink: 0;
  }
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
  var savedEditorRange = null;

  window.edFmt = function(id, cmd) {
    var ed = document.getElementById(id); if(!ed) return;
    ed.focus();
    document.execCommand(cmd, false, null);
    syncContent(id);
  };

  window.edBlock = function(id, tag) {
    var ed = document.getElementById(id); if(!ed) return;
    ed.focus();
    document.execCommand('formatBlock', false, tag);
    syncContent(id);
  };

  function syncContent(id) {
    var ed = document.getElementById(id); if(!ed) return;
    var allHa = ed.parentElement.querySelectorAll('textarea');
    allHa.forEach(function(t) { t.value = ed.innerHTML; });
  }

  // Modern Link Modal & Shortcut (Ctrl + K)
  window.openLinkModal = function(id) {
    var ed = document.getElementById(id); if(!ed) return;
    ed.focus();
    
    var sel = window.getSelection();
    if (sel.rangeCount > 0) {
      savedEditorRange = sel.getRangeAt(0).cloneRange();
    } else {
      savedEditorRange = null;
    }

    var textInput = document.getElementById('linkText_' + id);
    var urlInput = document.getElementById('linkUrl_' + id);
    var targetInput = document.getElementById('linkTarget_' + id);

    var selectedText = sel.toString().trim();
    if (textInput) textInput.value = selectedText;

    // Check if cursor is inside an existing <a> tag
    var parentNode = sel.anchorNode ? (sel.anchorNode.nodeType === 3 ? sel.anchorNode.parentNode : sel.anchorNode) : null;
    var existingA = parentNode ? parentNode.closest('a') : null;

    if (existingA) {
      if (textInput) textInput.value = existingA.textContent || selectedText;
      if (urlInput) urlInput.value = existingA.getAttribute('href') || '';
      if (targetInput) targetInput.checked = (existingA.getAttribute('target') === '_blank');
    } else {
      if (urlInput) urlInput.value = '';
      if (targetInput) targetInput.checked = true;
    }

    var modal = document.getElementById('linkModal_' + id);
    if (modal) {
      modal.style.display = 'flex';
      setTimeout(function() {
        if (selectedText.length > 0 && urlInput) {
          urlInput.focus();
        } else if (textInput) {
          textInput.focus();
        }
      }, 50);
    }
  };

  window.closeLinkModal = function(id) {
    var modal = document.getElementById('linkModal_' + id);
    if (modal) modal.style.display = 'none';
    var ed = document.getElementById(id);
    if (ed) ed.focus();
  };

  window.applyLinkModal = function(id) {
    var textInput = document.getElementById('linkText_' + id);
    var urlInput = document.getElementById('linkUrl_' + id);
    var targetInput = document.getElementById('linkTarget_' + id);

    var url = (urlInput.value || '').trim();
    if (!url) {
      alert('Silakan masukkan URL tujuan.');
      urlInput.focus();
      return;
    }

    // Auto-prefix http/https if missing and looks like domain
    if (!/^https?:\/\//i.test(url) && !/^mailto:/i.test(url) && !/^tel:/i.test(url) && !url.startsWith('#')) {
      url = 'https://' + url;
    }

    var text = (textInput ? textInput.value : '').trim() || url;
    var isBlank = targetInput ? targetInput.checked : true;

    var ed = document.getElementById(id);
    ed.focus();

    if (savedEditorRange) {
      var sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(savedEditorRange);
    }

    var targetAttr = isBlank ? ' target="_blank" rel="noopener noreferrer"' : ' target="_self"';
    var linkHtml = '<a href="' + url + '"' + targetAttr + ' style="color:#1eb349; font-weight:600; text-decoration:underline;">' + escapeHtml(text) + '</a>';

    document.execCommand('insertHTML', false, linkHtml);
    syncContent(id);

    var modal = document.getElementById('linkModal_' + id);
    if (modal) modal.style.display = 'none';
  };

  function escapeHtml(t) {
    return String(t).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  // Attach keydown listener for Ctrl + K inside editor
  var edEl = document.getElementById(edId);
  if (edEl) {
    edEl.addEventListener('keydown', function(e) {
      if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
        e.preventDefault();
        openLinkModal(edId);
      }
    });
  }

  // Close link modal on Esc key or click on backdrop
  var lModal = document.getElementById('linkModal_' + edId);
  if (lModal) {
    lModal.addEventListener('click', function(e) {
      if (e.target === lModal) closeLinkModal(edId);
    });
  }
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && lModal && lModal.style.display === 'flex') {
      closeLinkModal(edId);
    }
  });

  // Client-side image compression
  function compressImage(file, maxWidth = 1200, maxHeight = 1200, quality = 0.82) {
    return new Promise((resolve) => {
      if (!file || !file.type.startsWith('image/')) { resolve(file); return; }
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function(e) {
        const img = new Image();
        img.src = e.target.result;
        img.onload = function() {
          let width = img.width, height = img.height;
          if (width > maxWidth || height > maxHeight) {
            if (width / height > maxWidth / maxHeight) {
              height = Math.round((height * maxWidth) / width);
              width = maxWidth;
            } else {
              width = Math.round((width * maxHeight) / height);
              height = maxHeight;
            }
          }
          const canvas = document.createElement('canvas');
          canvas.width = width;
          canvas.height = height;
          const ctx = canvas.getContext('2d');
          ctx.drawImage(img, 0, 0, width, height);
          canvas.toBlob(function(blob) {
            if (!blob) { resolve(file); return; }
            resolve(new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".webp", { type:'image/webp', lastModified:Date.now() }));
          }, 'image/webp', quality);
        };
        img.onerror = function() { resolve(file); };
      };
      reader.onerror = function() { resolve(file); };
    });
  }

  window.edImage = function(id) {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function(e) {
      const file = e.target.files[0];
      if(!file) return;
      const ed = document.getElementById(id);
      ed.focus();
      const loadingId = 'img-loading-' + Date.now();
      document.execCommand('insertHTML', false, `<span id="${loadingId}" style="color:#1eb349;font-style:italic;">[Mengompresi & mengunggah gambar...]</span>`);
      
      compressImage(file).then(compressedFile => {
        const fd = new FormData();
        fd.append('image', compressedFile);
        fd.append('_token', '{{ csrf_token() }}');
        
        @php $uploadRoute = (auth()->check() && auth()->user()->role === 'seller') ? route('creator.upload.image') : route('admin.upload.image'); @endphp
        fetch('{{ $uploadRoute }}', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
          const loadingEl = document.getElementById(loadingId);
          if(data.url) {
            const imgHtml = `<img src="${data.url}" style="max-width:100%; height:auto; border-radius:10px; margin:1rem 0; display:block;">`;
            if(loadingEl) loadingEl.outerHTML = imgHtml;
            else { ed.focus(); document.execCommand('insertHTML', false, imgHtml); }
          } else {
            if(loadingEl) loadingEl.outerHTML = `<span style="color:#ef4444;">[Gagal upload gambar]</span>`;
          }
          syncContent(id);
        })
        .catch(err => {
          const loadingEl = document.getElementById(loadingId);
          if(loadingEl) loadingEl.outerHTML = `<span style="color:#ef4444;">[Error upload gambar]</span>`;
        });
      });
    };
    input.click();
  };

  window.edQuote = function(id) {
    var sel = window.getSelection().toString() || 'Kutipan inspiratif di sini...';
    var ed = document.getElementById(id); ed.focus();
    document.execCommand('insertHTML', false, '<blockquote style="border-left:4px solid #1eb349; padding-left:1rem; margin:1rem 0; color:#475569; font-style:italic;">' + sel + '</blockquote>');
    syncContent(id);
  };

  window.edCode = function(id) {
    var sel = window.getSelection().toString() || 'code';
    var ed = document.getElementById(id); ed.focus();
    document.execCommand('insertHTML', false, '<code style="background:#f1f5f9; padding:2px 6px; border-radius:5px; font-family:monospace; color:#0f172a;">' + sel + '</code>');
    syncContent(id);
  };

  window.edToggleHtml = function(edId, haId) {
    htmlMode = !htmlMode;
    var ed = document.getElementById(edId);
    var ha = document.getElementById(haId);
    var btn = document.getElementById('htmlbtn_' + edId);
    if(htmlMode) {
      ha.value = ed.innerHTML;
      ha.style.display = 'block';
      ed.style.display = 'none';
      if(btn) { btn.style.color = '#1eb349'; btn.style.borderColor = '#86efac'; btn.style.background = '#f0fdf4'; }
    } else {
      ed.innerHTML = ha.value;
      ed.style.display = 'block';
      ha.style.display = 'none';
      if(btn) { btn.style.color = '#94A3B8'; btn.style.borderColor = 'transparent'; btn.style.background = 'transparent'; }
    }
  };

  // Image interaction tools
  if (edEl && imgToolbar) {
    edEl.addEventListener('click', function(e) {
      if (e.target.tagName === 'IMG') {
        selectImage(e.target);
      } else {
        deselectImage();
      }
    });

    function selectImage(img) {
      deselectImage();
      currentSelectedImg = img;
      img.classList.add('img-selected');
      
      var editorBox = edEl.closest('.modern-rich-editor-box');
      var boxRect = editorBox.getBoundingClientRect();
      var imgRect = img.getBoundingClientRect();
      
      imgToolbar.style.display = 'flex';
      var topPos = (imgRect.top - boxRect.top) - 44;
      if (topPos < 46) topPos = (imgRect.top - boxRect.top) + imgRect.height + 10;
      imgToolbar.style.top = Math.max(10, topPos) + 'px';
      imgToolbar.style.left = ((imgRect.left - boxRect.left) + (imgRect.width / 2)) + 'px';
    }

    function deselectImage() {
      if (currentSelectedImg) currentSelectedImg.classList.remove('img-selected');
      currentSelectedImg = null;
      if (imgToolbar) imgToolbar.style.display = 'none';
    }

    document.addEventListener('click', function(e) {
      if (!e.target.closest('.modern-rich-editor-box') && !e.target.closest('.mre-link-modal')) {
        deselectImage();
      }
    });

    imgToolbar.querySelectorAll('.img-btn-sz').forEach(function(btn) {
      btn.addEventListener('click', function() {
        if (!currentSelectedImg) return;
        currentSelectedImg.style.width = this.getAttribute('data-sz');
        currentSelectedImg.style.maxWidth = '100%';
        syncContent(edId);
        selectImage(currentSelectedImg);
      });
    });

    imgToolbar.querySelectorAll('.img-btn-align').forEach(function(btn) {
      btn.addEventListener('click', function() {
        if (!currentSelectedImg) return;
        var align = this.getAttribute('data-align');
        if (align === 'center') {
          currentSelectedImg.style.display = 'block';
          currentSelectedImg.style.marginLeft = 'auto';
          currentSelectedImg.style.marginRight = 'auto';
        } else if (align === 'right') {
          currentSelectedImg.style.display = 'block';
          currentSelectedImg.style.marginLeft = 'auto';
          currentSelectedImg.style.marginRight = '0';
        } else {
          currentSelectedImg.style.display = 'block';
          currentSelectedImg.style.marginLeft = '0';
          currentSelectedImg.style.marginRight = 'auto';
        }
        syncContent(edId);
        selectImage(currentSelectedImg);
      });
    });

    var delBtn = document.getElementById('imgBtnDelete_' + edId);
    if (delBtn) {
      delBtn.addEventListener('click', function() {
        if (currentSelectedImg) {
          currentSelectedImg.remove();
          deselectImage();
          syncContent(edId);
        }
      });
    }
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
