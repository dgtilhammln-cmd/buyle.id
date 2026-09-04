@extends('creator.layout')
@section('title', 'Produk Saya')
@section('page_title', 'Produk Saya')
@section('page_subtitle', $products->count() . ' produk/layanan terdaftar')

@section('topbar_actions')
  <form action="{{ route('creator.products.index') }}" method="GET"
    style="display:flex;align-items:center;background:#fff;border:1px solid #E2E8F0;border-radius:12px;padding:4px;box-shadow:0 2px 10px rgba(0,0,0,0.02);">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk/layanan..."
      style="border:none;outline:none;padding:8px 12px;font-size:0.875rem;width:200px;font-family:'Montserrat',sans-serif;">
    <button type="submit"
      style="background:#F1F5F9;border:none;border-radius:8px;padding:8px;cursor:pointer;color:#64748B;display:flex;align-items:center;justify-content:center;">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
    </button>
  </form>

  <div
    style="display:flex;background:#fff;border:1px solid #E2E8F0;border-radius:12px;padding:4px;box-shadow:0 2px 10px rgba(0,0,0,0.02);">
    <button onclick="switchView('list')" id="btn-view-list"
      style="border:none;background:transparent;border-radius:8px;padding:8px;cursor:pointer;color:#94A3B8;display:flex;align-items:center;justify-content:center;transition:all .2s;"
      title="List View">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="8" y1="6" x2="21" y2="6"></line>
        <line x1="8" y1="12" x2="21" y2="12"></line>
        <line x1="8" y1="18" x2="21" y2="18"></line>
        <line x1="3" y1="6" x2="3.01" y2="6"></line>
        <line x1="3" y1="12" x2="3.01" y2="12"></line>
        <line x1="3" y1="18" x2="3.01" y2="18"></line>
      </svg>
    </button>
    <button onclick="switchView('grid')" id="btn-view-grid"
      style="border:none;background:transparent;border-radius:8px;padding:8px;cursor:pointer;color:#94A3B8;display:flex;align-items:center;justify-content:center;transition:all .2s;"
      title="Grid View">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <rect x="3" y="3" width="7" height="7"></rect>
        <rect x="14" y="3" width="7" height="7"></rect>
        <rect x="14" y="14" width="7" height="7"></rect>
        <rect x="3" y="14" width="7" height="7"></rect>
      </svg>
    </button>
  </div>

  <a href="{{ route('creator.products.create') }}" class="btn-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
      <line x1="12" y1="5" x2="12" y2="19" />
      <line x1="5" y1="12" x2="19" y2="12" />
    </svg>
    Tambah Produk
  </a>
@endsection

@section('styles')
  <style>
    #view-grid {
      grid-template-columns: repeat(7, 1fr) !important;
    }

    @media (max-width: 1400px) {
      #view-grid {
        grid-template-columns: repeat(5, 1fr) !important;
      }
    }

    @media (max-width: 1024px) {
      #view-grid {
        grid-template-columns: repeat(4, 1fr) !important;
      }
    }

    @media (max-width: 768px) {
      #view-grid {
        grid-template-columns: repeat(3, 1fr) !important;
      }
    }

    @media (max-width: 500px) {
      #view-grid {
        grid-template-columns: repeat(2, 1fr) !important;
      }
    }

    #view-list {
      overflow-x: auto;
    }

    #view-list table {
      min-width: 700px;
    }
  </style>
@endsection

@section('content')



  {{-- TABLE CARD (LIST VIEW) --}}
  <div id="view-list" class="view-container"
    style="background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow-x:auto;display:block;">
    <table style="width:100%;border-collapse:collapse;min-width:1000px;">
      <thead>
        <tr style="background:#F8FAFC;">
          <th
            style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">
            No</th>
          <th
            style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">
            Gambar</th>
          <th
            style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">
            Nama Layanan</th>
          <th
            style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">
            Slug</th>
          <th
            style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">
            Stok</th>
          <th
            style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">
            Urutan</th>
          <th
            style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">
            Status</th>
          <th
            style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">
            Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $s)
          <tr style="border-bottom:1px solid #F8FAFC;transition:background .15s;"
            onmouseover="this.style.background='#FAFBFF'" onmouseout="this.style.background='transparent'">
            <td style="padding:1.25rem 1.5rem;">
              <span
                style="font-size:.8rem;font-weight:700;color:#CBD5E1;">{{ str_pad($s->order, 2, '0', STR_PAD_LEFT) }}</span>
            </td>
            <td style="padding:1.25rem 1.5rem;">
              <img src="{{ $s->image_url }}" alt="{{ $s->name }}"
                style="width:60px;height:44px;object-fit:cover;border-radius:10px;border:1px solid #E4E7F0;">
            </td>
            <td style="padding:1.25rem 1.5rem;">
              <div style="font-size:.9rem;font-weight:700;color:#1E293B;">{{ $s->name }}</div>
              @if($s->short_desc)
                <div
                  style="font-size:.75rem;color:#94A3B8;margin-top:.2rem;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                  {{ $s->short_desc }}
                </div>
              @endif
              @if($s->is_whitelabel)
                <div style="margin-top:.35rem;">
                  @if($s->whitelabel_approval_status === 'pending')
                    <span style="font-size:.68rem;font-weight:700;background:#FEF3C7;color:#D97706;padding:.15rem .55rem;border-radius:10px;display:inline-flex;align-items:center;gap:.3rem;">
                      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                      WL Approval Pending
                    </span>
                  @elseif($s->whitelabel_approval_status === 'approved')
                    <span style="font-size:.68rem;font-weight:700;background:#DCFCE7;color:#16A34A;padding:.15rem .55rem;border-radius:10px;display:inline-flex;align-items:center;gap:.3rem;">
                      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                      WL Approved (Disetujui)
                    </span>
                  @elseif($s->whitelabel_approval_status === 'rejected')
                    <span style="font-size:.68rem;font-weight:700;background:#FEE2E2;color:#DC2626;padding:.15rem .55rem;border-radius:10px;display:inline-flex;align-items:center;gap:.3rem;" title="{{ $s->whitelabel_rejection_reason }}">
                      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                      WL Ditolak
                    </span>
                  @endif
                </div>
              @endif
            </td>
            <td style="padding:1.25rem 1.5rem;">
              <code
                style="font-size:.75rem;background:#F1F5F9;color:#3B82F6;padding:.25rem .625rem;border-radius:6px;font-family:'Courier New',monospace;">/{{ $s->slug }}</code>
            </td>
            <td style="padding:1.25rem 1.5rem;text-align:center;">
              <form action="{{ route('admin.services.stock', $s->id) }}" method="POST" style="margin:0;">
                @csrf
                @method('PATCH')
                <input type="number" name="stock" value="{{ $s->stock }}" min="0" onchange="this.form.submit()"
                  style="width:70px;padding:.375rem;border:1px solid #E2E8F0;border-radius:6px;text-align:center;font-family:'Montserrat',sans-serif;font-size:.875rem;font-weight:600;color:{{ $s->stock > 0 ? '#10B981' : '#EF4444' }};outline:none;background:transparent;">
              </form>
            </td>
            <td style="padding:1.25rem 1.5rem;text-align:center;">
              <form action="{{ route('admin.services.order', $s->id) }}" method="POST" style="margin:0;">
                @csrf
                @method('PATCH')
                <input type="number" name="order" value="{{ $s->order }}" min="0" onchange="this.form.submit()"
                  style="width:60px;padding:.375rem;border:1px solid #E2E8F0;border-radius:6px;text-align:center;font-family:'Montserrat',sans-serif;font-size:.875rem;font-weight:700;color:#334155;outline:none;background:transparent;">
              </form>
            </td>
            <td style="padding:1.25rem 1.5rem;text-align:center;">
              @if($s->is_active)
                <span
                  style="display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:700;padding:.3rem .875rem;border-radius:100px;background:rgba(16,185,129,0.1);color:#10B981;">
                  <span style="width:6px;height:6px;background:#10B981;border-radius:50%;"></span>Aktif
                </span>
              @else
                <span
                  style="display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:700;padding:.3rem .875rem;border-radius:100px;background:rgba(239,68,68,0.1);color:#EF4444;">
                  <span style="width:6px;height:6px;background:#EF4444;border-radius:50%;"></span>Nonaktif
                </span>
              @endif
            </td>
            <td style="padding:1.25rem 1.5rem;text-align:center;">
              <div style="display:inline-flex;gap:.5rem;align-items:center;">
                <a href="{{ route('creator.products.edit', $s) }}" title="Edit"
                  style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(59,130,246,0.08);border-radius:8px;color:#3B82F6;text-decoration:none;transition:all .2s;"
                  onmouseover="this.style.background='rgba(59,130,246,0.16)'"
                  onmouseout="this.style.background='rgba(59,130,246,0.08)'">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                  </svg>
                </a>
                <a href="{{ route('products.show', $s->slug) }}" target="_blank" title="Lihat"
                  style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(139,92,246,0.08);border-radius:8px;color:#8B5CF6;text-decoration:none;transition:all .2s;"
                  onmouseover="this.style.background='rgba(139,92,246,0.16)'"
                  onmouseout="this.style.background='rgba(139,92,246,0.08)'">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6" />
                    <polyline points="15 3 21 3 21 9" />
                    <line x1="10" y1="14" x2="21" y2="3" />
                  </svg>
                </a>
                <form method="POST" action="{{ route('creator.products.destroy', $s) }}"
                  onsubmit="return confirm('Hapus layanan ini? Tindakan tidak dapat dibatalkan.')">
                  @csrf @method('DELETE')
                  <button type="submit" title="Hapus"
                    style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(239,68,68,0.08);border-radius:8px;color:#EF4444;border:none;cursor:pointer;transition:all .2s;"
                    onmouseover="this.style.background='rgba(239,68,68,0.16)'"
                    onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <polyline points="3 6 5 6 21 6" />
                      <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                    </svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
        @if($products->count() === 0)
          <tr>
            <td colspan="7" style="padding:4rem;text-align:center;">
              <div
                style="width:56px;height:56px;background:#F1F5F9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <svg width="24" height="24" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24">
                  <path
                    d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" />
                </svg>
              </div>
              <div style="font-size:.9rem;font-weight:700;color:#334155;">Belum ada produk</div>
              <div style="font-size:.8rem;color:#94A3B8;margin-top:.25rem;">Klik tombol "Tambah produk" untuk upload.</div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  {{-- GRID VIEW: 7 per row --}}
  <div id="view-grid" class="view-container" style="display:none; grid-template-columns: repeat(7, 1fr); gap: 0.75rem;">
    @foreach($products as $s)
      <div
        style="background:#fff;border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,0.06);overflow:hidden;border:1px solid #F1F5F9;display:flex;flex-direction:column;position:relative;transition:transform 0.2s,box-shadow 0.2s;"
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,0.1)'"
        onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 10px rgba(0,0,0,0.06)'">
        <div
          style="position:absolute;top:6px;right:6px;font-size:.6rem;font-weight:600;padding:.2rem .5rem;border-radius:100px;background:{{ $s->is_active ? 'rgba(16,185,129,0.9)' : 'rgba(239,68,68,0.9)' }};color:#fff;z-index:10;backdrop-filter:blur(4px);">
          {{ $s->is_active ? 'Aktif' : 'Nonaktif' }}
        </div>
        <div style="position:relative;width:100%;aspect-ratio:1/1;overflow:hidden;border-bottom:1px solid #F1F5F9;">
          <img src="{{ $s->image_url }}" alt="{{ $s->name }}"
            style="width:100%;height:100%;object-fit:cover;display:block;">
        </div>
        <div style="padding:0.65rem;flex:1;display:flex;flex-direction:column;">
          <div
            style="font-size:0.72rem;font-weight:500;color:#1E293B;margin-bottom:.25rem;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
            {{ $s->name }}
          </div>
          <code
            style="font-size:.6rem;background:#F8FAFC;color:#1eb349;padding:.15rem .4rem;border-radius:4px;align-self:flex-start;margin-bottom:.5rem;border:1px solid #E2E8F0;display:block;max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">/{{ $s->slug }}</code>

          <div
            style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:0.5rem;border-top:1px solid #F1F5F9;">
            <div style="display:flex;align-items:center;gap:.3rem;flex-wrap:wrap;">
              <span style="font-size:.62rem;color:#94A3B8;font-weight:500;">STOK:</span>
              <form action="{{ route('admin.services.stock', $s->id) }}" method="POST" style="margin:0;">
                @csrf @method('PATCH')
                <input type="number" name="stock" value="{{ $s->stock }}" min="0" onchange="this.form.submit()"
                  style="width:38px;padding:.15rem;border:1px solid #E2E8F0;border-radius:4px;text-align:center;font-family:'Montserrat',sans-serif;font-size:.72rem;font-weight:500;color:{{ $s->stock > 0 ? '#10B981' : '#EF4444' }};outline:none;background:#F8FAFC;">
              </form>
              <span style="font-size:.62rem;color:#94A3B8;font-weight:500;">URT:</span>
              <form action="{{ route('admin.services.order', $s->id) }}" method="POST" style="margin:0;">
                @csrf @method('PATCH')
                <input type="number" name="order" value="{{ $s->order }}" min="0" onchange="this.form.submit()"
                  style="width:38px;padding:.15rem;border:1px solid #E2E8F0;border-radius:4px;text-align:center;font-family:'Montserrat',sans-serif;font-size:.72rem;font-weight:500;color:#334155;outline:none;background:#F8FAFC;">
              </form>
            </div>
            <div style="display:flex;gap:.25rem;">
              <a href="{{ route('creator.products.edit', $s) }}" title="Edit"
                style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;background:rgba(59,130,246,0.1);border-radius:8px;color:#3B82F6;transition:all .2s;"
                onmouseover="this.style.background='rgba(59,130,246,0.2)'"
                onmouseout="this.style.background='rgba(59,130,246,0.1)'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                  <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
              </a>
              <form method="POST" action="{{ route('creator.products.destroy', $s) }}" onsubmit="return confirm('Hapus?')">
                @csrf @method('DELETE')
                <button type="submit" title="Hapus"
                  style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;background:rgba(239,68,68,0.1);border-radius:8px;color:#EF4444;border:none;cursor:pointer;transition:all .2s;"
                  onmouseover="this.style.background='rgba(239,68,68,0.2)'"
                  onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                  </svg>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach

    @if($products->count() === 0)
      <div
        style="grid-column: 1 / -1; padding:4rem;text-align:center;background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
        <div
          style="width:56px;height:56px;background:#F1F5F9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
          <svg width="24" height="24" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24">
            <path
              d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" />
          </svg>
        </div>
        <div style="font-size:.9rem;font-weight:700;color:#334155;">Belum ada produk</div>
        <div style="font-size:.8rem;color:#94A3B8;margin-top:.25rem;">Klik tombol "Tambah" untuk mulai.</div>
      </div>
    @endif
  </div>

  <script>
    function switchView(type) {
      localStorage.setItem('admin_services_view', type);
      document.getElementById('view-list').style.display = type === 'list' ? 'block' : 'none';
      document.getElementById('view-grid').style.display = type === 'grid' ? 'grid' : 'none';

      document.getElementById('btn-view-list').style.background = type === 'list' ? 'linear-gradient(135deg,#1eb349,#a5cf37)' : 'transparent';
      document.getElementById('btn-view-list').style.color = type === 'list' ? '#fff' : '#94A3B8';

      document.getElementById('btn-view-grid').style.background = type === 'grid' ? 'linear-gradient(135deg,#1eb349,#a5cf37)' : 'transparent';
      document.getElementById('btn-view-grid').style.color = type === 'grid' ? '#fff' : '#94A3B8';
    }

    // Initialize view
    const savedView = localStorage.getItem('admin_services_view') || 'list';
    switchView(savedView);
  </script>

@endsection