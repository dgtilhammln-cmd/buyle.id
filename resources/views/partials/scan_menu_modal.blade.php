{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- MODAL SCAN MENU AI TO CATALOG                                  --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div id="scan-menu-modal" class="modal-backdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.65); backdrop-filter:blur(6px); z-index:99999; justify-content:center; align-items:center; padding:1.5rem; box-sizing:border-box;">
  <div style="background:#FFFFFF; border-radius:24px; max-width:850px; width:100%; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); overflow:hidden; border:1px solid #E2E8F0; animation:modalPop 0.25s ease-out;">
    
    {{-- Modal Header --}}
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid #F1F5F9; display:flex; justify-content:space-between; align-items:center; background:#FAFAFA;">
      <div style="display:flex; align-items:center; gap:0.6rem;">
        <div style="width:36px; height:36px; background:linear-gradient(135deg, #0f172a, #1e293b); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff;">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-4a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
        </div>
        <div>
          <h3 style="font-size:1.05rem; font-weight:800; color:#0F172A; margin:0; line-height:1.2;">Scan Menu Buku AI</h3>
          <p style="font-size:0.75rem; color:#64748B; margin:0;">Upload foto daftar menu resto/kafe untuk mengekstrak nama, harga, & deskripsi jualan otomatis ke Produk Fisik Link Bio.</p>
        </div>
      </div>
      <button type="button" onclick="closeScanMenuModal()" style="background:none; border:none; color:#94A3B8; cursor:pointer; padding:0.25rem; border-radius:8px;" onmouseover="this.style.color='#0F172A'">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    {{-- Modal Body --}}
    <div style="padding:1.5rem 1.75rem; overflow-y:auto; flex:1;">
      
      {{-- Step 1: Upload Form --}}
      <div id="scan-step-upload">
        <div id="scan-dropzone" onclick="document.getElementById('menu_file_input').click()" 
             style="border:2px dashed #CBD5E1; border-radius:18px; padding:2.5rem 1.5rem; text-align:center; background:#F8FAFC; cursor:pointer; transition:all 0.2s;"
             onmouseover="this.style.borderColor='#1eb349'; this.style.background='#F0FDF4';"
             onmouseout="this.style.borderColor='#CBD5E1'; this.style.background='#F8FAFC';">
          <input type="file" id="menu_file_input" accept="image/*" style="display:none;" onchange="handleMenuFileSelected(this.files[0])">
          <div style="width:54px; height:54px; background:rgba(30,179,73,0.1); color:#1eb349; border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
          <div style="font-size:0.95rem; font-weight:700; color:#1E293B; margin-bottom:0.35rem;">Pilih atau Foto Buku Menu</div>
          <p style="font-size:0.78rem; color:#64748B; margin:0 0 0.75rem;">Format JPG, PNG, atau WebP (Maksimal 10MB)</p>
          <span style="display:inline-block; font-size:0.75rem; font-weight:700; color:#1eb349; background:#E8F5E9; padding:0.4rem 1rem; border-radius:8px;">Pilih File Foto Menu</span>
        </div>

        <div style="margin-top:1.25rem; background:#FFFBEB; border:1px solid #FCD34D; border-radius:12px; padding:0.85rem 1.1rem; display:flex; align-items:center; gap:0.75rem; font-size:0.78rem; color:#92400E;">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; color:#D97706;"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          <div>
            <strong>Tips Hasil Terbaik:</strong> Pastikan pencahayaan terang, foto tegak, dan teks tulisan/harga menu terlihat jelas.
          </div>
        </div>
      </div>

      {{-- Step 2: Loading Indicator --}}
      <div id="scan-step-loading" style="display:none; text-align:center; padding:3rem 1.5rem;">
        <div class="ai-pulse-loader" style="width:70px; height:70px; border-radius:50%; background:linear-gradient(135deg, #1eb349, #a5cf37); display:flex; align-items:center; justify-content:center; color:#fff; margin:0 auto 1.5rem; box-shadow:0 0 25px rgba(30,179,73,0.4);">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="spin-anim"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-4a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
        </div>
        <h4 style="font-size:1.1rem; font-weight:800; color:#0F172A; margin:0 0 0.5rem;">AI Sedang Membaca Menu Anda...</h4>
        <p style="font-size:0.82rem; color:#64748B; margin:0;">Mengekstrak nama makanan/minuman, menguraikan harga, dan menyusun deskripsi lezat otomatis.</p>
      </div>

      {{-- Step 3: Result Preview & Selection Table --}}
      <div id="scan-step-result" style="display:none;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
          <div style="font-size:0.88rem; font-weight:700; color:#0F172A;" id="scan-result-count">Daftar Menu Terdeteksi:</div>
          <div style="display:flex; gap:0.5rem;">
            <button type="button" onclick="selectAllScanItems(true)" style="font-size:0.75rem; font-weight:600; color:#1eb349; background:#E8F5E9; border:none; padding:0.3rem 0.75rem; border-radius:6px; cursor:pointer;">Pilih Semua</button>
            <button type="button" onclick="selectAllScanItems(false)" style="font-size:0.75rem; font-weight:600; color:#64748B; background:#F1F5F9; border:none; padding:0.3rem 0.75rem; border-radius:6px; cursor:pointer;">Batal Semua</button>
          </div>
        </div>

        <div style="overflow-x:auto; border:1px solid #E2E8F0; border-radius:14px; max-height:380px;">
          <table style="width:100%; border-collapse:collapse; text-align:left; font-size:0.82rem;">
            <thead style="background:#F8FAFC; border-bottom:1px solid #E2E8F0; position:sticky; top:0; z-index:5;">
              <tr>
                <th style="padding:0.75rem; width:35px; text-align:center;">#</th>
                <th style="padding:0.75rem; min-width:140px;">Nama Menu</th>
                <th style="padding:0.75rem; width:110px;">Harga (Rp)</th>
                <th style="padding:0.75rem; min-width:220px;">Deskripsi Jualan AI</th>
              </tr>
            </thead>
            <tbody id="scan-items-tbody">
              {{-- Dynamic Rows --}}
            </tbody>
          </table>
        </div>
      </div>

    </div>

    {{-- Modal Footer --}}
    <div style="padding:1rem 1.75rem; border-top:1px solid #F1F5F9; background:#FAFAFA; display:flex; justify-content:space-between; align-items:center;">
      <button type="button" onclick="closeScanMenuModal()" style="padding:0.6rem 1.25rem; font-size:0.82rem; font-weight:600; color:#64748B; background:#F1F5F9; border:none; border-radius:10px; cursor:pointer;">Tutup</button>

      <button type="button" id="btn-import-scanned" onclick="submitBulkImportScanned()" style="display:none; align-items:center; gap:0.5rem; padding:0.65rem 1.5rem; font-size:0.875rem; font-weight:700; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#FFFFFF; border:none; border-radius:10px; cursor:pointer; box-shadow:0 4px 14px rgba(30,179,73,0.3);">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        <span id="btn-import-text">Impor ke Produk Fisik</span>
      </button>
    </div>

  </div>
</div>

<style>
@keyframes modalPop { from { opacity: 0; transform: scale(0.96); } to { opacity: 1; transform: scale(1); } }
.ai-pulse-loader { animation: pulseGlow 1.8s ease-in-out infinite; }
@keyframes pulseGlow { 0%, 100% { transform: scale(1); box-shadow: 0 0 20px rgba(30,179,73,0.3); } 50% { transform: scale(1.06); box-shadow: 0 0 35px rgba(30,179,73,0.6); } }
.spin-anim { animation: spin 1s linear infinite; }
@keyframes spin { 100% { transform: rotate(360deg); } }
</style>

<script>
let detectedMenuItems = [];

function openScanMenuModal() {
  document.getElementById('scan-menu-modal').style.display = 'flex';
  resetScanModalState();
}

function closeScanMenuModal() {
  document.getElementById('scan-menu-modal').style.display = 'none';
}

function resetScanModalState() {
  document.getElementById('scan-step-upload').style.display = 'block';
  document.getElementById('scan-step-loading').style.display = 'none';
  document.getElementById('scan-step-result').style.display = 'none';
  document.getElementById('btn-import-scanned').style.display = 'none';
  document.getElementById('menu_file_input').value = '';
  detectedMenuItems = [];
}

function handleMenuFileSelected(file) {
  if (!file) return;

  const formData = new FormData();
  formData.append('menu_image', file);
  formData.append('_token', "{{ csrf_token() }}");

  document.getElementById('scan-step-upload').style.display = 'none';
  document.getElementById('scan-step-loading').style.display = 'block';

  fetch("{{ route('creator.products.scan-menu') }}", {
    method: 'POST',
    body: formData,
    headers: {
      'Accept': 'application/json'
    }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success && data.items && data.items.length > 0) {
      detectedMenuItems = data.items;
      renderScanItemsTable();
      document.getElementById('scan-step-loading').style.display = 'none';
      document.getElementById('scan-step-result').style.display = 'block';
      document.getElementById('btn-import-scanned').style.display = 'inline-flex';
    } else {
      alert(data.message || 'Gagal mengekstrak menu. Coba gunakan foto yang lebih jelas.');
      resetScanModalState();
    }
  })
  .catch(err => {
    alert('Terjadi kesalahan saat memproses gambar menu: ' + err.message);
    resetScanModalState();
  });
}

function renderScanItemsTable() {
  const tbody = document.getElementById('scan-items-tbody');
  tbody.innerHTML = '';

  document.getElementById('scan-result-count').innerHTML = `Daftar Menu Terdeteksi (<strong>${detectedMenuItems.length} item</strong>):`;

  detectedMenuItems.forEach((item, idx) => {
    const tr = document.createElement('tr');
    tr.style.borderBottom = '1px solid #F1F5F9';
    tr.innerHTML = `
      <td style="padding:0.75rem; text-align:center;">
        <input type="checkbox" class="scan-item-chk" data-index="${idx}" checked onchange="updateImportBtnCount()">
      </td>
      <td style="padding:0.5rem 0.75rem;">
        <input type="text" value="${escapeHtml(item.name)}" class="scan-input-name" data-index="${idx}"
               style="width:100%; padding:0.4rem 0.6rem; border:1px solid #E2E8F0; border-radius:6px; font-weight:600; font-size:0.8rem;">
      </td>
      <td style="padding:0.5rem 0.75rem;">
        <input type="number" value="${item.price}" class="scan-input-price" data-index="${idx}"
               style="width:100%; padding:0.4rem 0.6rem; border:1px solid #E2E8F0; border-radius:6px; font-size:0.8rem;">
      </td>
      <td style="padding:0.5rem 0.75rem;">
        <textarea class="scan-input-desc" data-index="${idx}" rows="2"
                  style="width:100%; padding:0.4rem 0.6rem; border:1px solid #E2E8F0; border-radius:6px; font-size:0.78rem; font-family:sans-serif;">${escapeHtml(item.description)}</textarea>
      </td>
    `;
    tbody.appendChild(tr);
  });

  updateImportBtnCount();
}

function escapeHtml(text) {
  if (!text) return '';
  return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
}

function selectAllScanItems(checked) {
  document.querySelectorAll('.scan-item-chk').forEach(chk => chk.checked = checked);
  updateImportBtnCount();
}

function updateImportBtnCount() {
  const checkedCount = document.querySelectorAll('.scan-item-chk:checked').length;
  document.getElementById('btn-import-text').textContent = `Impor ${checkedCount} Menu ke Produk Fisik`;
}

function submitBulkImportScanned() {
  const selectedItems = [];
  const chks = document.querySelectorAll('.scan-item-chk:checked');

  if (chks.length === 0) {
    alert('Pilih minimal 1 menu untuk diimpor ke katalog.');
    return;
  }

  chks.forEach(chk => {
    const idx = chk.getAttribute('data-index');
    const name = document.querySelector(`.scan-input-name[data-index="${idx}"]`).value;
    const price = document.querySelector(`.scan-input-price[data-index="${idx}"]`).value;
    const desc = document.querySelector(`.scan-input-desc[data-index="${idx}"]`).value;

    selectedItems.push({
      name: name,
      price: price,
      description: desc,
      category: 'Makanan'
    });
  });

  const btn = document.getElementById('btn-import-scanned');
  btn.disabled = true;
  btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="spin-anim"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg> Mengimpor...`;

  fetch("{{ route('creator.products.bulk-import') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': "{{ csrf_token() }}",
      'Accept': 'application/json'
    },
    body: JSON.stringify({ items: selectedItems })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert(data.message);
      window.location.reload();
    } else {
      alert(data.message || 'Gagal mengimpor produk.');
      btn.disabled = false;
      updateImportBtnCount();
    }
  })
  .catch(err => {
    alert('Error saat impor produk: ' + err.message);
    btn.disabled = false;
    updateImportBtnCount();
  });
}
</script>
