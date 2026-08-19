{{-- Shared Rich Text Editor Partial --}}
{{-- Usage: @include('admin.partials.rich-editor', ['name'=>'content', 'value'=>$item->content??'', 'height'=>'350px']) --}}
@php $edId = 'editor_'.Str::random(6); $haId = 'ha_'.Str::random(6); @endphp

<div style="border:1.5px solid #E4E7F0;border-radius:12px;overflow:hidden;transition:border-color .2s;" onfocusin="this.style.borderColor='#3B82F6'" onfocusout="this.style.borderColor='#E4E7F0'">
  {{-- Toolbar --}}
  <div style="display:flex;flex-wrap:wrap;align-items:center;gap:3px;padding:.5rem .75rem;background:#F8FAFC;border-bottom:1px solid #F1F5F9;" id="tb_{{ $edId }}">
    @foreach([['bold','<strong>B</strong>','font-weight:800'],['italic','<em>I</em>','font-style:italic'],['underline','<span style=\"text-decoration:underline\">U</span>','']] as $b)
    <button type="button" onclick="edFmt('{{$edId}}','{{$b[0]}}')"
      style="padding:.3rem .5rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;font-size:.8rem;min-width:30px;font-family:inherit;transition:all .15s;"
      onmouseover="this.style.borderColor='#3B82F6';this.style.color='#3B82F6'" onmouseout="this.style.borderColor='#E4E7F0';this.style.color='#374151'">{!! $b[1] !!}</button>
    @endforeach
    <div style="width:1px;height:20px;background:#E4E7F0;margin:0 .25rem;"></div>
    @foreach([['h2','H2'],['h3','H3'],['p','¶']] as $b)
    <button type="button" onclick="edBlock('{{$edId}}','{{$b[0]}}')"
      style="padding:.3rem .5rem;background:#fff;border:1.5px solid #E4E7F0;color:#3B82F6;border-radius:6px;cursor:pointer;font-size:.78rem;font-weight:700;min-width:30px;transition:all .15s;"
      onmouseover="this.style.background='#EFF6FF'" onmouseout="this.style.background='#fff'">{{$b[1]}}</button>
    @endforeach
    <div style="width:1px;height:20px;background:#E4E7F0;margin:0 .25rem;"></div>
    <button type="button" onclick="edFmt('{{$edId}}','insertUnorderedList')"
      style="padding:.3rem .5rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;transition:all .15s;" title="Bullet List"
      onmouseover="this.style.borderColor='#3B82F6'" onmouseout="this.style.borderColor='#E4E7F0'">
      <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><rect x="2" y="5" width="3" height="3" rx="1"/><rect x="8" y="5" width="14" height="3" rx="1"/><rect x="2" y="11" width="3" height="3" rx="1"/><rect x="8" y="11" width="14" height="3" rx="1"/><rect x="2" y="17" width="3" height="3" rx="1"/><rect x="8" y="17" width="14" height="3" rx="1"/></svg>
    </button>
    <button type="button" onclick="edFmt('{{$edId}}','insertOrderedList')"
      style="padding:.3rem .5rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;font-size:.75rem;font-weight:700;transition:all .15s;" title="Numbered List"
      onmouseover="this.style.borderColor='#3B82F6'" onmouseout="this.style.borderColor='#E4E7F0'">1.</button>
    <button type="button" onclick="edLink('{{$edId}}')"
      style="padding:.3rem .5rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;transition:all .15s;" title="Insert Link"
      onmouseover="this.style.borderColor='#3B82F6'" onmouseout="this.style.borderColor='#E4E7F0'">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
    </button>
    <button type="button" onclick="edImage('{{$edId}}')"
      style="padding:.3rem .5rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;transition:all .15s;" title="Insert Image"
      onmouseover="this.style.borderColor='#3B82F6'" onmouseout="this.style.borderColor='#E4E7F0'">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
    </button>
    <button type="button" onclick="edQuote('{{$edId}}')"
      style="padding:.3rem .5rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;font-size:.85rem;transition:all .15s;" title="Blockquote"
      onmouseover="this.style.borderColor='#3B82F6'" onmouseout="this.style.borderColor='#E4E7F0'">&ldquo;</button>
    <button type="button" onclick="edCode('{{$edId}}')"
      style="padding:.3rem .5rem;background:#fff;border:1.5px solid #E4E7F0;color:#374151;border-radius:6px;cursor:pointer;font-size:.72rem;font-family:monospace;transition:all .15s;" title="Inline Code"
      onmouseover="this.style.borderColor='#3B82F6'" onmouseout="this.style.borderColor='#E4E7F0'">&lt;/&gt;</button>
    <div style="width:1px;height:20px;background:#E4E7F0;margin:0 .25rem;"></div>
    <button type="button" id="htmlbtn_{{ $edId }}" onclick="edToggleHtml('{{$edId}}','{{$haId}}')"
      style="padding:.3rem .5rem;background:#fff;border:1.5px solid #E4E7F0;color:#94A3B8;border-radius:6px;cursor:pointer;font-size:.72rem;font-family:monospace;transition:all .15s;">HTML</button>
  </div>

  {{-- Visual editor --}}
  <div id="{{ $edId }}" contenteditable="true"
       style="min-height:{{ $height ?? '320px' }};padding:1.25rem 1.5rem;color:#1E293B;font-size:.9375rem;line-height:1.85;outline:none;font-family:'Inter','Segoe UI',sans-serif;background:#fff;"
       oninput="document.getElementById('{{ $haId }}').value=this.innerHTML;"
  >{!! $value ?? '' !!}</div>

  {{-- Hidden HTML textarea --}}
  <textarea id="{{ $haId }}" name="{{ $name }}" style="display:none;width:100%;min-height:{{ $height ?? '320px' }};padding:1.25rem;color:#1E293B;font-size:.8rem;line-height:1.6;font-family:'Fira Code',monospace;background:#F8FAFC;border:none;outline:none;resize:vertical;box-sizing:border-box;">{{ $value ?? '' }}</textarea>
</div>

<script>
(function(){
  var edId = '{{ $edId }}', haId = '{{ $haId }}';
  var htmlMode = false;

  window.edFmt = window.edFmt || function(id, cmd) {
    document.getElementById(id).focus();
    document.execCommand(cmd, false, null);
    document.getElementById('ha_'+id.slice(7)).value = document.getElementById(id).innerHTML;
  };

  // Override for per-instance calls with full edId
  window['edFmt'] = function(id, cmd) {
    var ed = document.getElementById(id); if(!ed) return;
    ed.focus(); document.execCommand(cmd,false,null);
    var ha = document.querySelector('[id^="ha_"]'); // fallback
    // find the paired textarea by scanning for matching hidden textarea
    var allHa = document.querySelectorAll('textarea[style*="display:none"]');
    allHa.forEach(function(t){ if(t.id && document.getElementById(id).parentElement.contains(t)) { t.value = document.getElementById(id).innerHTML; }});
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
        document.execCommand('insertHTML', false, `<span id="${loadingId}" style="color:var(--yellow);font-style:italic;">[Mengupload gambar...]</span>`);
        
        const fd = new FormData();
        fd.append('image', file);
        fd.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route("admin.upload.image") }}', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
            const loadingEl = document.getElementById(loadingId);
            if(data.url) {
                const imgHtml = `<img src="${data.url}" style="max-width:100%; border-radius:8px; margin:1rem 0;">`;
                if(loadingEl) loadingEl.outerHTML = imgHtml;
                else { ed.focus(); document.execCommand('insertHTML', false, imgHtml); }
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
      btn.style.color='var(--yellow)'; btn.style.borderColor='rgba(255,215,0,.4)';
    } else {
      ed.innerHTML = ha.value; ed.style.display='block'; ha.style.display='none';
      btn.style.color='rgba(255,255,255,.4)'; btn.style.borderColor='rgba(255,255,255,.1)';
    }
  };

  // Sync before form submit
  document.addEventListener('submit', function() {
    if(!htmlMode) {
      var ed = document.getElementById(edId);
      var ha = document.getElementById(haId);
      if(ed && ha) ha.value = ed.innerHTML;
    }
    document.getElementById(haId).style.display = 'block';
  });
})();
</script>
