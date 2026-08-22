{{-- Reusable table partial for a given tab's categories --}}
<div style="display:flex;flex-direction:column;gap:1.5rem;">
  @forelse($cats as $cat)
  <div style="background:#fff;border-radius:20px;box-shadow:0 2px 16px rgba(0,0,0,0.04);overflow:hidden;border:1.5px solid #F1F5F9;">

    {{-- Kategori Header Row --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.5rem;background:linear-gradient(135deg,rgba(30,179,73,0.04),rgba(165,207,55,0.04));border-bottom:1px solid #F1F5F9;gap:1rem;flex-wrap:wrap;">
      <div style="display:flex;align-items:center;gap:1rem;">
        <div style="width:44px;height:44px;background:linear-gradient(135deg,#1eb349,#a5cf37);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem;flex-shrink:0;">
          {{ strtoupper(substr($cat->name, 0, 1)) }}
        </div>
        <div>
          <div style="display:flex;align-items:center;gap:.5rem;">
            <span style="font-size:1rem;font-weight:700;color:#1E293B;">{{ $cat->name }}</span>
            @if($cat->badge)
              <span style="font-size:.65rem;font-weight:700;padding:.2rem .5rem;border-radius:99px;background:{{ $cat->badge === 'terpopuler' ? '#1eb349' : '#F59E0B' }};color:#fff;">{{ ucfirst($cat->badge) }}</span>
            @endif
            @if(!$cat->is_active)
              <span style="font-size:.65rem;font-weight:700;padding:.2rem .5rem;border-radius:99px;background:#FEE2E2;color:#DC2626;">Nonaktif</span>
            @endif
          </div>
          <div style="font-size:.8rem;color:#94A3B8;margin-top:.15rem;">
            <code style="background:#F1F5F9;padding:.1rem .4rem;border-radius:4px;font-size:.75rem;">{{ $cat->slug }}</code>
            &nbsp;·&nbsp; {{ $cat->products_count }} produk &nbsp;·&nbsp; {{ $cat->subCategories->count() }} sub-kategori &nbsp;·&nbsp; Urutan #{{ $cat->order }}
          </div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:.5rem;">
        <a href="{{ route('admin.product-categories.edit', $cat) }}"
          style="display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .875rem;border-radius:8px;background:#EFF6FF;color:#2563EB;font-size:.8rem;font-weight:600;text-decoration:none;">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit
        </a>
        <form action="{{ route('admin.product-categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini beserta semua sub-kategorinya?')">
          @csrf @method('DELETE')
          <button type="submit" style="display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .875rem;border-radius:8px;background:#FEF2F2;color:#DC2626;font-size:.8rem;font-weight:600;border:none;cursor:pointer;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
            Hapus
          </button>
        </form>
      </div>
    </div>

    {{-- Sub-Kategori Table --}}
    <div style="padding:0 1.5rem 1rem;">
      @if($cat->subCategories->isNotEmpty())
      <table style="width:100%;border-collapse:collapse;margin-top:.75rem;">
        <thead>
          <tr style="border-bottom:1px solid #F1F5F9;">
            <th style="padding:.5rem .75rem;text-align:left;font-size:.7rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">#</th>
            <th style="padding:.5rem .75rem;text-align:left;font-size:.7rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">Nama Sub-Kategori</th>
            <th style="padding:.5rem .75rem;text-align:left;font-size:.7rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">Slug</th>
            <th style="padding:.5rem .75rem;text-align:left;font-size:.7rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">Deskripsi</th>
            <th style="padding:.5rem .75rem;text-align:right;font-size:.7rem;font-weight:700;color:#94A3B8;text-transform:uppercase;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cat->subCategories as $sub)
          <tr style="border-bottom:1px solid #F8FAFC;" id="sub-row-{{ $sub->id }}">
            <td style="padding:.5rem .75rem;font-size:.8rem;color:#CBD5E1;">{{ $sub->order }}</td>
            <td style="padding:.5rem .75rem;font-size:.875rem;font-weight:600;color:#1E293B;">{{ $sub->name }}</td>
            <td style="padding:.5rem .75rem;"><code style="font-size:.72rem;background:#F1F5F9;padding:.1rem .4rem;border-radius:4px;">{{ $sub->slug }}</code></td>
            <td style="padding:.5rem .75rem;font-size:.8rem;color:#64748B;max-width:260px;">{{ Str::limit($sub->description, 60) }}</td>
            <td style="padding:.5rem .75rem;text-align:right;white-space:nowrap;">
              <button onclick="openEditSub({{ $sub->id }}, '{{ addslashes($sub->name) }}', '{{ $sub->slug }}', '{{ addslashes($sub->description) }}', '{{ addslashes($sub->contoh_produk) }}')"
                style="padding:.3rem .7rem;border-radius:6px;background:#EFF6FF;color:#2563EB;font-size:.75rem;font-weight:600;border:none;cursor:pointer;margin-right:.3rem;">Edit</button>
              <form action="{{ route('admin.product-categories.sub.destroy', $sub) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus sub-kategori ini?')">
                @csrf @method('DELETE')
                <button type="submit" style="padding:.3rem .7rem;border-radius:6px;background:#FEF2F2;color:#DC2626;font-size:.75rem;font-weight:600;border:none;cursor:pointer;">Hapus</button>
              </form>
              {{-- Hidden edit form --}}
              <div id="edit-form-{{ $sub->id }}" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:9999;display:none;align-items:center;justify-content:center;">
                <div style="background:#fff;border-radius:20px;padding:2rem;width:100%;max-width:500px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
                  <div style="font-size:1.1rem;font-weight:700;color:#1E293B;margin-bottom:1.5rem;">Edit Sub-Kategori</div>
                  <form action="{{ route('admin.product-categories.sub.update', $sub) }}" method="POST">
                    @csrf @method('PUT')
                    <div style="margin-bottom:1rem;">
                      <label style="font-size:.8rem;font-weight:600;color:#64748B;display:block;margin-bottom:.35rem;">Nama *</label>
                      <input type="text" name="name" value="{{ $sub->name }}" required style="width:100%;padding:.6rem .875rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;">
                    </div>
                    <div style="margin-bottom:1rem;">
                      <label style="font-size:.8rem;font-weight:600;color:#64748B;display:block;margin-bottom:.35rem;">Slug</label>
                      <input type="text" name="slug" value="{{ $sub->slug }}" style="width:100%;padding:.6rem .875rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;font-family:monospace;outline:none;">
                    </div>
                    <div style="margin-bottom:1rem;">
                      <label style="font-size:.8rem;font-weight:600;color:#64748B;display:block;margin-bottom:.35rem;">Deskripsi</label>
                      <input type="text" name="description" value="{{ $sub->description }}" style="width:100%;padding:.6rem .875rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;">
                    </div>
                    <div style="margin-bottom:1.5rem;">
                      <label style="font-size:.8rem;font-weight:600;color:#64748B;display:block;margin-bottom:.35rem;">Contoh Produk</label>
                      <input type="text" name="contoh_produk" value="{{ $sub->contoh_produk }}" style="width:100%;padding:.6rem .875rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;">
                    </div>
                    <div style="display:flex;gap:.75rem;justify-content:flex-end;">
                      <button type="button" onclick="document.getElementById('edit-form-{{ $sub->id }}').style.display='none'"
                        style="padding:.6rem 1.25rem;border-radius:10px;border:1.5px solid #E2E8F0;background:#fff;color:#64748B;font-size:.875rem;font-weight:600;cursor:pointer;">Batal</button>
                      <button type="submit" style="padding:.6rem 1.25rem;border-radius:10px;border:none;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;font-size:.875rem;font-weight:600;cursor:pointer;">Simpan</button>
                    </div>
                  </form>
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @endif

      {{-- Tambah Sub-Kategori --}}
      <div style="margin-top:1rem;">
        <button onclick="toggleAddSub({{ $cat->id }})"
          style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem .875rem;border-radius:8px;background:#F0FDF4;color:#16a34a;font-size:.8rem;font-weight:600;border:1px dashed #86EFAC;cursor:pointer;">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Tambah Sub-Kategori
        </button>
        <div id="add-sub-{{ $cat->id }}" style="display:none;margin-top:.75rem;padding:1rem;background:#F8FAFC;border-radius:12px;border:1px solid #E2E8F0;">
          <form action="{{ route('admin.product-categories.sub.store', $cat) }}" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem;">
              <div>
                <label style="font-size:.75rem;font-weight:600;color:#64748B;display:block;margin-bottom:.3rem;">Nama Sub-Kategori *</label>
                <input type="text" name="name" required placeholder="mis: Prompt Siap Pakai" style="width:100%;padding:.5rem .75rem;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;outline:none;background:#fff;">
              </div>
              <div>
                <label style="font-size:.75rem;font-weight:600;color:#64748B;display:block;margin-bottom:.3rem;">Slug (otomatis jika kosong)</label>
                <input type="text" name="slug" placeholder="mis: prompt-siap-pakai" style="width:100%;padding:.5rem .75rem;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;font-family:monospace;outline:none;background:#fff;">
              </div>
              <div>
                <label style="font-size:.75rem;font-weight:600;color:#64748B;display:block;margin-bottom:.3rem;">Deskripsi</label>
                <input type="text" name="description" placeholder="Deskripsi singkat" style="width:100%;padding:.5rem .75rem;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;outline:none;background:#fff;">
              </div>
              <div>
                <label style="font-size:.75rem;font-weight:600;color:#64748B;display:block;margin-bottom:.3rem;">Contoh Produk</label>
                <input type="text" name="contoh_produk" placeholder="mis: ChatGPT prompt pack" style="width:100%;padding:.5rem .75rem;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;outline:none;background:#fff;">
              </div>
            </div>
            <div style="display:flex;gap:.5rem;justify-content:flex-end;">
              <button type="button" onclick="toggleAddSub({{ $cat->id }})"
                style="padding:.45rem 1rem;border-radius:8px;border:1.5px solid #E2E8F0;background:#fff;color:#64748B;font-size:.8rem;font-weight:600;cursor:pointer;">Batal</button>
              <button type="submit"
                style="padding:.45rem 1rem;border-radius:8px;border:none;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;font-size:.8rem;font-weight:600;cursor:pointer;">+ Tambah</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
  @empty
  <div style="background:#fff;border-radius:20px;padding:3rem;text-align:center;color:#94A3B8;border:1.5px dashed #E2E8F0;">
    Belum ada kategori untuk tab <strong>{{ $tabLabel }}</strong>.
    <a href="{{ route('admin.product-categories.create') }}" style="color:#1eb349;font-weight:600;text-decoration:none;">+ Tambah sekarang</a>
  </div>
  @endforelse
</div>

<script>
function toggleAddSub(id) {
    const el = document.getElementById('add-sub-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
function openEditSub(id) {
    document.getElementById('edit-form-' + id).style.display = 'flex';
}
</script>
