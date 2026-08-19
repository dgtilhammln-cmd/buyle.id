@extends('layouts.admin')
@section('title','Kelola Artikel')
@section('page-title','Artikel')
@section('content')

{{-- PAGE HEADER --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Kelola Artikel</h1>
    <p style="font-size:.875rem;color:#94A3B8;margin:0;">{{ $articles->count() }} artikel terdaftar</p>
  </div>
  <div style="display:flex;align-items:center;gap:1rem;">
    <form action="{{ route('admin.articles.index') }}" method="GET" style="display:flex;align-items:center;background:#fff;border:1px solid #E2E8F0;border-radius:12px;padding:4px;box-shadow:0 2px 10px rgba(0,0,0,0.02);">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel..." style="border:none;outline:none;padding:8px 12px;font-size:0.875rem;width:200px;font-family:'Montserrat',sans-serif;">
      <button type="submit" style="background:#F1F5F9;border:none;border-radius:8px;padding:8px;cursor:pointer;color:#64748B;display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" /></svg>
      </button>
    </form>
    
    {{-- VIEW TOGGLE --}}
    <div style="display:flex;background:#fff;border:1px solid #E2E8F0;border-radius:12px;padding:4px;box-shadow:0 2px 10px rgba(0,0,0,0.02);">
      <button onclick="switchView('list')" id="btn-view-list" style="border:none;background:transparent;border-radius:8px;padding:8px;cursor:pointer;color:#94A3B8;display:flex;align-items:center;justify-content:center;transition:all .2s;" title="List View">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
      </button>
      <button onclick="switchView('grid')" id="btn-view-grid" style="border:none;background:transparent;border-radius:8px;padding:8px;cursor:pointer;color:#94A3B8;display:flex;align-items:center;justify-content:center;transition:all .2s;" title="Grid View">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
      </button>
    </div>

    <a href="{{ route('admin.articles.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.875rem;font-weight:700;padding:.625rem 1.25rem;border-radius:12px;text-decoration:none;transition:all .2s;box-shadow:0 4px 14px rgba(59,130,246,0.35);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(59,130,246,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(59,130,246,0.35)'">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Tulis Artikel
    </a>
  </div>
</div>

{{-- STATS BAR --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.75rem;">
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(59,130,246,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $articles->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Total Artikel</div>
    </div>
  </div>
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(16,185,129,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $articles->where('is_published',true)->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Published</div>
    </div>
  </div>
  <div style="background:#fff;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.04);display:flex;align-items:center;gap:1rem;">
    <div style="width:44px;height:44px;background:rgba(245,158,11,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="#F59E0B" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:#1E293B;line-height:1;">{{ $articles->where('is_published',false)->count() }}</div>
      <div style="font-size:.75rem;color:#94A3B8;font-weight:600;margin-top:.15rem;">Draft</div>
    </div>
  </div>
</div>

{{-- TABLE CARD (LIST VIEW) --}}
<div id="view-list" class="view-container" style="background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow:hidden;display:block;">
  <table style="width:100%;border-collapse:collapse;">
    <thead>
      <tr style="background:#F8FAFC;">
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Gambar</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Judul & Slug</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Kategori</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Tanggal</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Views</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Status</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($articles as $a)
      <tr style="border-bottom:1px solid #F8FAFC;transition:background .15s;" onmouseover="this.style.background='#FAFBFF'" onmouseout="this.style.background='transparent'">

        {{-- Gambar --}}
        <td style="padding:1.25rem 1.5rem;">
          <img src="{{ $a->image_url }}" alt="{{ $a->title }}" style="width:72px;height:50px;object-fit:cover;border-radius:10px;border:1px solid #E4E7F0;">
        </td>

        {{-- Judul & Slug --}}
        <td style="padding:1.25rem 1.5rem;max-width:300px;">
          <div style="font-size:.9rem;font-weight:700;color:#1E293B;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $a->title }}</div>
          <code style="font-size:.72rem;background:#F1F5F9;color:#3B82F6;padding:.2rem .5rem;border-radius:5px;font-family:'Courier New',monospace;margin-top:.25rem;display:inline-block;">/{{ $a->slug }}</code>
        </td>

        {{-- Kategori --}}
        <td style="padding:1.25rem 1.5rem;">
          @if($a->category)
            <span style="font-size:.75rem;font-weight:600;padding:.3rem .75rem;border-radius:8px;background:#F1F5F9;color:#475569;">{{ $a->category }}</span>
          @else
            <span style="color:#CBD5E1;font-size:.8rem;">—</span>
          @endif
        </td>

        {{-- Tanggal --}}
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          <div style="font-size:.8rem;color:#334155;font-weight:600;">{{ $a->formatted_date }}</div>
        </td>

        {{-- Views --}}
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          <div style="display:inline-flex;align-items:center;gap:.35rem;font-size:.8rem;font-weight:700;color:#64748B;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            {{ number_format($a->views) }}
          </div>
        </td>

        {{-- Status --}}
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          @if($a->is_published)
            <span style="display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:700;padding:.3rem .875rem;border-radius:100px;background:rgba(16,185,129,0.1);color:#10B981;">
              <span style="width:6px;height:6px;background:#10B981;border-radius:50%;"></span>Published
            </span>
          @else
            <span style="display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:700;padding:.3rem .875rem;border-radius:100px;background:rgba(245,158,11,0.1);color:#F59E0B;">
              <span style="width:6px;height:6px;background:#F59E0B;border-radius:50%;"></span>Draft
            </span>
          @endif
        </td>

        {{-- Aksi --}}
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          <div style="display:inline-flex;gap:.5rem;align-items:center;">
            {{-- Edit --}}
            <a href="{{ route('admin.articles.edit',$a) }}" title="Edit"
               style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(59,130,246,0.08);border-radius:8px;color:#3B82F6;text-decoration:none;transition:all .2s;"
               onmouseover="this.style.background='rgba(59,130,246,0.18)'" onmouseout="this.style.background='rgba(59,130,246,0.08)'">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
            {{-- Preview --}}
            <a href="{{ url('/en/articles/' . $a->slug) }}" target="_blank" title="Lihat di website"
               style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(139,92,246,0.08);border-radius:8px;color:#8B5CF6;text-decoration:none;transition:all .2s;"
               onmouseover="this.style.background='rgba(139,92,246,0.18)'" onmouseout="this.style.background='rgba(139,92,246,0.08)'">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
            {{-- Hapus --}}
            <form method="POST" action="{{ route('admin.articles.destroy',$a) }}" onsubmit="return confirm('Hapus artikel ini? Tindakan tidak dapat dibatalkan.')">
              @csrf @method('DELETE')
              <button type="submit" title="Hapus"
                style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(239,68,68,0.08);border-radius:8px;color:#EF4444;border:none;cursor:pointer;transition:all .2s;"
                onmouseover="this.style.background='rgba(239,68,68,0.18)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
              </button>
            </form>
          </div>
        </td>

      </tr>
      @empty
      <tr>
        <td colspan="7" style="padding:5rem;text-align:center;">
          <div style="width:64px;height:64px;background:#F1F5F9;border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
            <svg width="28" height="28" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          </div>
          <div style="font-size:.95rem;font-weight:700;color:#334155;">Belum ada artikel</div>
          <div style="font-size:.82rem;color:#94A3B8;margin-top:.35rem;">Klik tombol "Tulis Artikel" untuk memulai.</div>
          <a href="{{ route('admin.articles.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;margin-top:1.25rem;background:#3B82F6;color:#fff;font-size:.85rem;font-weight:700;padding:.625rem 1.25rem;border-radius:10px;text-decoration:none;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tulis Artikel Pertama
          </a>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
  </table>
</div>

{{-- GRID VIEW --}}
<div id="view-grid" class="view-container" style="display:none; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
  @forelse($articles as $a)
    <div style="background:#fff;border-radius:20px;box-shadow:0 4px 20px rgba(0,0,0,0.04);overflow:hidden;border:1px solid #F1F5F9;display:flex;flex-direction:column;position:relative;transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(0,0,0,0.04)'">
      <div style="position:absolute;top:12px;right:12px;display:inline-flex;align-items:center;gap:.375rem;font-size:.7rem;font-weight:700;padding:.3rem .75rem;border-radius:100px;background:{{ $a->is_published ? 'rgba(16,185,129,0.9)' : 'rgba(245,158,11,0.9)' }};color:#fff;z-index:10;-webkit-backdrop-filter:blur(4px);backdrop-filter:blur(4px);">
          {{ $a->is_published ? 'Published' : 'Draft' }}
      </div>
      <img src="{{ $a->image_url }}" alt="{{ $a->title }}" style="width:100%;height:160px;object-fit:cover;border-bottom:1px solid #F1F5F9;">
      <div style="padding:1.25rem;flex:1;display:flex;flex-direction:column;">
          <div style="font-size:1rem;font-weight:800;color:#1E293B;margin-bottom:.35rem;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $a->title }}</div>
          <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
            @if($a->category)
                <span style="font-size:.7rem;font-weight:600;padding:.2rem .5rem;border-radius:6px;background:#F1F5F9;color:#475569;">{{ $a->category }}</span>
            @endif
            <code style="font-size:.7rem;background:#F8FAFC;color:#3B82F6;padding:.2rem .5rem;border-radius:6px;border:1px solid #E2E8F0;">/{{ $a->slug }}</code>
          </div>
          
          <div style="display:flex;align-items:center;gap:.75rem;font-size:.75rem;color:#94A3B8;margin-bottom:1rem;font-weight:600;">
            <div style="display:flex;align-items:center;gap:.25rem;">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ $a->formatted_date }}
            </div>
            <div style="display:flex;align-items:center;gap:.25rem;">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              {{ number_format($a->views) }}
            </div>
          </div>
          
          <div style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:1rem;border-top:1px solid #F1F5F9;">
              <a href="{{ url('/en/articles/' . $a->slug) }}" target="_blank" style="font-size:.75rem;font-weight:700;color:#3B82F6;text-decoration:none;display:flex;align-items:center;gap:.25rem;">
                 Lihat di website <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
              </a>
              <div style="display:flex;gap:.375rem;">
                 <a href="{{ route('admin.articles.edit', $a) }}" title="Edit" style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;background:rgba(59,130,246,0.1);border-radius:8px;color:#3B82F6;transition:all .2s;" onmouseover="this.style.background='rgba(59,130,246,0.2)'" onmouseout="this.style.background='rgba(59,130,246,0.1)'">
                   <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                 </a>
                 <form method="POST" action="{{ route('admin.articles.destroy', $a) }}" onsubmit="return confirm('Hapus?')">
                   @csrf @method('DELETE')
                   <button type="submit" title="Hapus" style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;background:rgba(239,68,68,0.1);border-radius:8px;color:#EF4444;border:none;cursor:pointer;transition:all .2s;" onmouseover="this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                     <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" /></svg>
                   </button>
                 </form>
              </div>
          </div>
      </div>
    </div>
  @empty
    <div style="grid-column: 1 / -1; padding:4rem;text-align:center;background:#fff;border-radius:24px;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
        <div style="width:56px;height:56px;background:#F1F5F9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
          <svg width="24" height="24" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div style="font-size:.9rem;font-weight:700;color:#334155;">Belum ada artikel</div>
        <div style="font-size:.8rem;color:#94A3B8;margin-top:.25rem;">Klik tombol "Tulis Artikel" untuk mulai.</div>
    </div>
  @endforelse
</div>

<script>
  function switchView(type) {
      localStorage.setItem('admin_articles_view', type);
      document.getElementById('view-list').style.display = type === 'list' ? 'block' : 'none';
      document.getElementById('view-grid').style.display = type === 'grid' ? 'grid' : 'none';
      
      document.getElementById('btn-view-list').style.background = type === 'list' ? '#3B82F6' : 'transparent';
      document.getElementById('btn-view-list').style.color = type === 'list' ? '#fff' : '#94A3B8';
      
      document.getElementById('btn-view-grid').style.background = type === 'grid' ? '#3B82F6' : 'transparent';
      document.getElementById('btn-view-grid').style.color = type === 'grid' ? '#fff' : '#94A3B8';
  }

  const savedView = localStorage.getItem('admin_articles_view') || 'list';
  switchView(savedView);
</script>

@endsection
