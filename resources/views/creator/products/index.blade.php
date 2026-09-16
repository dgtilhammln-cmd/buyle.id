@extends('creator.layout')
@section('title', 'Produk Saya')
@section('page_title', 'Produk Saya')
@section('page_subtitle', $products->count() . ' produk/layanan terdaftar')

@section('topbar_actions')
  <div class="prod-actions-row">
    <form action="{{ route('creator.products.index') }}" method="GET" class="prod-search-form">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="prod-search-input">
      <button type="submit" class="prod-search-btn">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8" />
          <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
      </button>
    </form>

    <div class="prod-view-toggle">
      <button onclick="switchView('list', true)" id="btn-view-list" class="prod-toggle-btn" title="List View">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <line x1="8" y1="6" x2="21" y2="6"></line>
          <line x1="8" y1="12" x2="21" y2="12"></line>
          <line x1="8" y1="18" x2="21" y2="18"></line>
          <line x1="3" y1="6" x2="3.01" y2="6"></line>
          <line x1="3" y1="12" x2="3.01" y2="12"></line>
          <line x1="3" y1="18" x2="3.01" y2="18"></line>
        </svg>
      </button>
      <button onclick="switchView('grid', true)" id="btn-view-grid" class="prod-toggle-btn" title="Grid View">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="3" width="7" height="7"></rect>
          <rect x="14" y="3" width="7" height="7"></rect>
          <rect x="14" y="14" width="7" height="7"></rect>
          <rect x="3" y="14" width="7" height="7"></rect>
        </svg>
      </button>
    </div>

    @if($products->count() > 0)
      <form action="{{ route('creator.products.destroy-all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA {{ $products->count() }} produk digital Anda? Data yang dihapus tidak dapat dikembalikan.');" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger-sm" style="background:#fef2f2; color:#dc2626; border:1px solid #fca5a5; font-weight:700; padding:0.5rem 0.85rem; border-radius:8px; font-size:0.8rem; cursor:pointer; display:inline-flex; align-items:center; gap:0.35rem;">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6M14 11v6"/></svg>
          Hapus Semua Produk
        </button>
      </form>
    @endif

    <button onclick="openSmartImport()" class="btn-smart-import" title="Smart Import Produk">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
      <span class="btn-add-text-full">Smart Import</span>
      <span class="btn-add-text-short">✨</span>
    </button>

    <a href="{{ route('creator.products.create') }}" class="btn-primary prod-add-btn">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19" />
        <line x1="5" y1="12" x2="19" y2="12" />
      </svg>
      <span class="btn-add-text-full">Tambah Produk</span>
      <span class="btn-add-text-short">Tambah</span>
    </a>
  </div>
@endsection

@section('styles')
  <style>
    .prod-actions-row {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .prod-search-form {
      display: flex;
      align-items: center;
      background: #fff;
      border: 1px solid #E2E8F0;
      border-radius: 12px;
      padding: 4px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .prod-search-input {
      border: none;
      outline: none;
      padding: 8px 12px;
      font-size: 0.875rem;
      width: 200px;
      font-family: 'Montserrat', sans-serif;
    }

    .prod-search-btn {
      background: #F1F5F9;
      border: none;
      border-radius: 8px;
      padding: 8px;
      cursor: pointer;
      color: #64748B;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .prod-view-toggle {
      display: flex;
      background: #fff;
      border: 1px solid #E2E8F0;
      border-radius: 12px;
      padding: 4px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .prod-toggle-btn {
      border: none;
      background: transparent;
      border-radius: 8px;
      padding: 8px;
      cursor: pointer;
      color: #94A3B8;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all .2s;
    }

    .prod-add-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
    }

    .btn-add-text-full { display: inline; }
    .btn-add-text-short { display: none; }

    #view-grid {
      grid-template-columns: repeat(7, 1fr) !important;
    }

    @media (max-width: 1400px) {
      #view-grid { grid-template-columns: repeat(5, 1fr) !important; }
    }
    @media (max-width: 1024px) {
      #view-grid { grid-template-columns: repeat(4, 1fr) !important; }
    }
    @media (max-width: 768px) {
      #view-grid { grid-template-columns: repeat(3, 1fr) !important; }
    }
    @media (max-width: 500px) {
      #view-grid { grid-template-columns: repeat(2, 1fr) !important; }
    }

    #view-list { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    #view-list table { min-width: 600px; }

    /* Compact 1-line toolbar on mobile */
    @media (max-width: 768px) {
      .cr-main-canvas > div:first-child {
        margin-bottom: 0.75rem !important;
        gap: 0.5rem !important;
      }

      .cr-topbar-actions-wrapper {
        width: 100% !important;
        gap: 0.35rem !important;
      }
      
      .prod-actions-row {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        width: 100% !important;
        gap: 0.35rem !important;
      }

      .prod-search-form {
        flex: 1 !important;
        min-width: 0 !important;
        padding: 2px !important;
      }

      .prod-search-input {
        width: 100% !important;
        min-width: 0 !important;
        padding: 6px 8px !important;
        font-size: 0.78rem !important;
      }

      .prod-search-btn {
        padding: 6px !important;
        flex-shrink: 0 !important;
      }

      .prod-view-toggle {
        flex-shrink: 0 !important;
        padding: 2px !important;
      }

      .prod-toggle-btn {
        padding: 6px !important;
      }

      .prod-add-btn {
        flex-shrink: 0 !important;
        padding: 6px 10px !important;
        font-size: 0.78rem !important;
        border-radius: 10px !important;
      }

      .btn-add-text-full { display: none !important; }
      .btn-add-text-short { display: inline !important; }

      #view-list table { min-width: 520px; font-size: 0.78rem; }
      #view-list td, #view-list th { padding: 0.6rem 0.75rem !important; }
      #view-list td img { width: 40px !important; height: 30px !important; border-radius: 7px !important; }
      #view-list td div[style*="font-size:.9rem"] { font-size: 0.8rem !important; }
      #view-list td div[style*="max-width:280px"] { max-width: 140px !important; }
      #view-list input[type="number"] { width: 52px !important; }
      /* Hide Slug column on mobile */
      #view-list th:nth-child(4),
      #view-list td:nth-child(4) { display: none; }
      /* Hide Urutan column on mobile */
      #view-list th:nth-child(6),
      #view-list td:nth-child(6) { display: none; }
    }
    /* Smart Import Button (Clean Light White Neutral Theme) */
    .btn-smart-import {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
      height: 38px;
      padding: 0 1rem;
      border-radius: 999px;
      border: 1.5px solid #cbd5e1;
      background: #ffffff;
      color: #0f172a;
      font-family: 'Montserrat', sans-serif;
      font-size: 0.82rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
      flex-shrink: 0;
    }
    .btn-smart-import:hover { transform: translateY(-1px); background: #f8fafc; border-color: #94a3b8; color: #0f172a; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

    /* Smart Import Modal (Clean Light White Theme) */
    .si-overlay {
      position: fixed; inset: 0; background: rgba(15,23,42,0.5); backdrop-filter: blur(4px);
      z-index: 9999; display: flex; align-items: center; justify-content: center;
      opacity: 0; pointer-events: none; transition: opacity 0.25s;
    }
    .si-overlay.open { opacity: 1; pointer-events: all; }
    .si-modal {
      background: #fff; border-radius: 20px; width: 100%; max-width: 560px; max-height: 90vh;
      overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.15); margin: 1rem; border: 1px solid #e2e8f0;
      transform: scale(0.95) translateY(16px); transition: transform 0.25s;
    }
    .si-overlay.open .si-modal { transform: scale(1) translateY(0); }
    .si-header {
      padding: 1.25rem 1.75rem 1rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex; align-items: center; justify-content: space-between;
    }
    .si-title { font-size: 1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.5rem; }
    .si-close {
      width: 32px; height: 32px; border-radius: 50%; border: none; background: #f1f5f9;
      cursor: pointer; display: flex; align-items: center; justify-content: center;
      font-size: 1rem; color: #64748b; transition: background 0.2s;
    }
    .si-close:hover { background: #fee2e2; color: #dc2626; }
    .si-tabs { display: flex; gap: 0; border-bottom: 1.5px solid #f1f5f9; padding: 0 1.75rem; overflow-x: auto; }
    .si-tab {
      padding: 0.85rem 1.1rem; font-size: 0.78rem; font-weight: 700; color: #64748b;
      border: none; background: none; cursor: pointer; white-space: nowrap;
      border-bottom: 2.5px solid transparent; margin-bottom: -1.5px; transition: all 0.2s; font-family: 'Montserrat', sans-serif;
    }
    .si-tab.active { color: #0f172a; border-bottom-color: #0f172a; font-weight: 800; }
    .si-tab-pane { display: none; padding: 1.5rem 1.75rem; }
    .si-tab-pane.active { display: block; }
    .si-label { font-size: 0.78rem; font-weight: 700; color: #374151; margin-bottom: 0.4rem; display: block; }
    .si-input {
      width: 100%; box-sizing: border-box; height: 44px; padding: 0 1rem;
      border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.875rem;
      font-family: 'Montserrat', sans-serif; color: #1a1a1a; background: #f8fafc;
      outline: none; transition: all 0.2s;
    }
    .si-input:focus { border-color: #0f172a; background: #fff; box-shadow: 0 0 0 3px rgba(15,23,42,0.08); }
    .si-btn {
      width: 100%; height: 44px; border-radius: 10px; border: 1.5px solid #cbd5e1;
      background: #ffffff; color: #0f172a; font-weight: 800; font-size: 0.85rem;
      font-family: 'Montserrat', sans-serif; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 0.5rem;
      transition: all 0.2s; margin-top: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .si-btn:hover { background: #f8fafc; border-color: #94a3b8; color: #0f172a; transform: translateY(-1px); }
    .si-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .si-result {
      margin-top: 1.25rem; padding: 1rem; background: #f8fafc;
      border-radius: 12px; border: 1px solid #e2e8f0; display: none;
    }
    .si-source-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem; }
    .si-source-card {
      padding: 0.9rem; border: 1.5px solid #e2e8f0; border-radius: 12px;
      text-align: center; cursor: pointer; transition: all 0.2s; background: #fff;
    }
    .si-source-card:hover, .si-source-card.selected {
      border-color: #0f172a; background: #f8fafc;
    }
    .si-source-card.selected { box-shadow: 0 0 0 2px rgba(15,23,42,0.1); font-weight: 800; }
    .si-source-card svg, .si-source-card img { display: block; margin: 0 auto 0.4rem; }
    .si-source-card span { font-size: 0.72rem; font-weight: 700; color: #374151; }
    .si-upload-area {
      border: 2px dashed #cbd5e1; border-radius: 12px; padding: 1.5rem;
      text-align: center; cursor: pointer; background: #f8fafc; transition: all 0.2s;
    }
    .si-upload-area:hover { border-color: #0f172a; background: #f1f5f9; }
    .si-progress { display: none; margin-top: 1rem; }
    .si-progress-bar {
      height: 6px; background: #e2e8f0; border-radius: 99px; overflow: hidden;
    }
    .si-progress-fill {
      height: 100%; background: #0f172a;
      border-radius: 99px; width: 0%; transition: width 0.4s;
    }
    @media (max-width: 768px) {
      .btn-smart-import { padding: 6px 10px !important; font-size: 0.78rem !important; height: 32px !important; border-radius: 10px !important; }
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
                style="width:60px;height:44px;object-fit:{{ $s->image ? 'cover' : 'contain' }};border-radius:10px;border:1px solid #E4E7F0;background:#ffffff;padding:{{ $s->image ? '0' : '4px' }};"
                onerror="this.onerror=null;this.src='{{ \App\Models\Product::getPlaceholderUrl() }}';this.style.objectFit='contain';this.style.padding='4px';">
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
              <form action="{{ route('creator.products.stock', $s->id) }}" method="POST" style="margin:0;">
                @csrf
                @method('PATCH')
                <input type="number" name="stock" value="{{ $s->stock }}" min="0" placeholder="∞" onchange="this.form.submit()" title="Kosongkan untuk Unlimited (Stok tidak terbatas)"
                  style="width:70px;padding:.375rem;border:1px solid #E2E8F0;border-radius:6px;text-align:center;font-family:'Montserrat',sans-serif;font-size:.875rem;font-weight:600;color:{{ is_null($s->stock) ? '#3B82F6' : ($s->stock > 0 ? '#10B981' : '#EF4444') }};outline:none;background:transparent;">
              </form>
            </td>
            <td style="padding:1.25rem 1.5rem;text-align:center;">
              <form action="{{ route('creator.products.order', $s->id) }}" method="POST" style="margin:0;">
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
        <div style="position:relative;width:100%;aspect-ratio:1/1;overflow:hidden;border-bottom:1px solid #F1F5F9;background:#ffffff;display:flex;align-items:center;justify-content:center;">
          <img src="{{ $s->image_url }}" alt="{{ $s->name }}"
            style="width:100%;height:100%;object-fit:{{ $s->image ? 'cover' : 'contain' }};padding:{{ $s->image ? '0' : '1.25rem' }};display:block;"
            onerror="this.onerror=null;this.src='{{ \App\Models\Product::getPlaceholderUrl() }}';this.style.objectFit='contain';this.style.padding='1.25rem';">
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
              <form action="{{ route('creator.products.stock', $s->id) }}" method="POST" style="margin:0;">
                @csrf @method('PATCH')
                <input type="number" name="stock" value="{{ $s->stock }}" min="0" placeholder="∞" onchange="this.form.submit()" title="Kosongkan untuk Unlimited (Stok tidak terbatas)"
                  style="width:38px;padding:.15rem;border:1px solid #E2E8F0;border-radius:4px;text-align:center;font-family:'Montserrat',sans-serif;font-size:.72rem;font-weight:500;color:{{ is_null($s->stock) ? '#3B82F6' : ($s->stock > 0 ? '#10B981' : '#EF4444') }};outline:none;background:#F8FAFC;">
              </form>
              <span style="font-size:.62rem;color:#94A3B8;font-weight:500;">URT:</span>
              <form action="{{ route('creator.products.order', $s->id) }}" method="POST" style="margin:0;">
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
    function switchView(type, isUserAction = false) {
      if (isUserAction) {
        localStorage.setItem('admin_services_view_user_toggled', 'true');
      }
      localStorage.setItem('admin_services_view', type);
      document.getElementById('view-list').style.display = type === 'list' ? 'block' : 'none';
      document.getElementById('view-grid').style.display = type === 'grid' ? 'grid' : 'none';

      document.getElementById('btn-view-list').style.background = type === 'list' ? 'linear-gradient(135deg,#1eb349,#a5cf37)' : 'transparent';
      document.getElementById('btn-view-list').style.color = type === 'list' ? '#fff' : '#94A3B8';

      document.getElementById('btn-view-grid').style.background = type === 'grid' ? 'linear-gradient(135deg,#1eb349,#a5cf37)' : 'transparent';
      document.getElementById('btn-view-grid').style.color = type === 'grid' ? '#fff' : '#94A3B8';
    }

    // Initialize view (default to grid on mobile)
    const isMobile = window.innerWidth <= 768;
    const userToggled = localStorage.getItem('admin_services_view_user_toggled');
    let savedView = localStorage.getItem('admin_services_view');

    if (isMobile && !userToggled) {
      savedView = 'grid';
    } else if (!savedView) {
      savedView = isMobile ? 'grid' : 'list';
    }

    switchView(savedView);
  </script>

@endsection

@include('partials.scan_menu_modal')

{{-- ===== SMART IMPORT MODAL ===== --}}
<div class="si-overlay" id="smartImportOverlay" onclick="closeSmartImport(event)">
  <div class="si-modal" onclick="event.stopPropagation()">
    <div class="si-header">
      <div class="si-title">
        <svg width="20" height="20" fill="none" stroke="#0f172a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Smart Import Produk
      </div>
      <button class="si-close" onclick="closeSmartImport()">&#x2715;</button>
    </div>

    {{-- TABS --}}
    <div class="si-tabs">
      <button class="si-tab active" onclick="switchSiTab('menu-ai', this)" style="display:inline-flex; align-items:center; gap:0.4rem;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"/><circle cx="12" cy="12" r="3"/></svg>
        Scan Menu AI
      </button>
      <button class="si-tab" onclick="switchSiTab('tiktokshop', this)" style="display:inline-flex; align-items:center; gap:0.4rem;">
        <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.28 6.28 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/></svg>
        TikTok Shop
      </button>
      <button class="si-tab" onclick="switchSiTab('marketplace', this)" style="display:inline-flex; align-items:center; gap:0.4rem;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        Tokopedia / Shopee
      </button>
      <button class="si-tab" onclick="switchSiTab('lynk', this)" style="display:inline-flex; align-items:center; gap:0.4rem;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        Lynk.id
      </button>
    </div>

    {{-- TAB 1: SCAN MENU AI --}}
    <div class="si-tab-pane active" id="si-menu-ai">
      <p style="font-size:0.8rem; color:#64748b; margin-bottom:1.25rem; line-height:1.6;">
        Upload foto menu / brosur, atau paste URL website menu. AI akan scan dan menampilkan daftar menu untuk diimpor ke katalog.
      </p>

      <div class="si-source-cards" id="siMenuSourceCards">
        <div class="si-source-card selected" id="srcPhoto" onclick="selectSiSource('photo')">
          <svg width="28" height="28" fill="none" stroke="#0f172a" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          <span>Foto Menu</span>
        </div>
        <div class="si-source-card" id="srcUrl" onclick="selectSiSource('url')">
          <svg width="28" height="28" fill="none" stroke="#0f172a" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          <span>URL Website</span>
        </div>
      </div>

      <div id="siPhotoInput">
        <div class="si-upload-area" onclick="document.getElementById('siMenuPhotoFile').click()" id="siUploadArea">
          <svg width="32" height="32" fill="none" stroke="#0f172a" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          <p style="font-size:0.8rem; font-weight:700; color:#0f172a; margin-top:0.5rem;">Klik untuk upload foto menu</p>
          <p style="font-size:0.72rem; color:#94a3b8; margin-top:0.2rem;">JPG, PNG, WEBP — Max 10MB</p>
          <div id="siPhotoPreviewWrap" style="display:none; margin-top:0.75rem;">
            <img id="siPhotoPreviewImg" src="" style="max-height:120px; border-radius:10px; max-width:100%; object-fit:contain;">
          </div>
        </div>
        <input type="file" id="siMenuPhotoFile" accept="image/*" style="display:none;" onchange="previewSiMenuPhoto(event)">
      </div>

      <div id="siUrlInput" style="display:none;">
        <label class="si-label">URL Website / Menu Online</label>
        <input type="url" id="siMenuUrl" class="si-input" placeholder="https://tokobakso.com/menu atau https://grabfood.com/...">
      </div>

      <div style="margin-top:1rem;">
        <label class="si-label">Tipe Produk yang akan diimport</label>
        <select id="siMenuProductType" class="si-input" style="height:44px;">
          <option value="makanan">Makanan / Minuman / Kuliner</option>
          <option value="physical">Produk Fisik / Barang / UMKM</option>
          <option value="service">Jasa / Layanan</option>
          <option value="external_link">Produk Digital</option>
        </select>
      </div>

      <button class="si-btn" id="siMenuScanBtn" onclick="runSiMenuScan()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        Scan & Tampilkan Daftar Menu
      </button>

      <div class="si-progress" id="siMenuProgress">
        <p style="font-size:0.75rem; color:#0f172a; font-weight:600; margin-bottom:0.5rem;" id="siMenuProgressText">AI sedang menganalisis menu...</p>
        <div class="si-progress-bar"><div class="si-progress-fill" id="siMenuProgressFill"></div></div>
      </div>
      <div class="si-result" id="siMenuResult"></div>
    </div>

    {{-- TAB 2: TIKTOK SHOP --}}
    <div class="si-tab-pane" id="si-tiktokshop">
      <p style="font-size:0.8rem; color:#64748b; margin-bottom:1.25rem; line-height:1.6;">
        Paste URL produk dari TikTok Shop. Sistem akan membaca data nama, harga, deskripsi, dan menyiapkan daftar impor.
      </p>

      <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem 1rem; margin-bottom:1.25rem; font-size:0.75rem; color:#334155; display:flex; gap:0.5rem; align-items:flex-start;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span>Gunakan URL produk milik Anda sendiri untuk impor otomatis data produk.</span>
      </div>

      <label class="si-label">URL Produk TikTok Shop</label>
      <input type="url" id="siTiktokUrl" class="si-input" placeholder="https://www.tiktok.com/t/xxx atau https://shop.tiktok.com/...">
      <span style="font-size:0.72rem; color:#94a3b8; margin-top:0.3rem; display:block;">Contoh: https://www.tiktok.com/@tokoku/product/123456</span>

      <button class="si-btn" id="siTiktokBtn" onclick="runSiTiktokScrape()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.28 6.28 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/></svg>
        Import dari TikTok Shop
      </button>

      <div class="si-progress" id="siTiktokProgress">
        <p style="font-size:0.75rem; color:#0f172a; font-weight:600; margin-bottom:0.5rem;" id="siTiktokProgressText">Sedang mendeteksi produk TikTok Shop...</p>
        <div class="si-progress-bar"><div class="si-progress-fill" id="siTiktokProgressFill"></div></div>
      </div>
      <div class="si-result" id="siTiktokResult"></div>
    </div>

    {{-- TAB 3: TOKOPEDIA / SHOPEE --}}
    <div class="si-tab-pane" id="si-marketplace">
      <p style="font-size:0.8rem; color:#64748b; margin-bottom:1.25rem; line-height:1.6;">
        Paste URL produk dari Tokopedia atau Shopee untuk import data produk Anda.
      </p>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1.25rem;">
        <div class="si-source-card selected" id="srcTokopedia" onclick="selectMarketplace('tokopedia')" style="padding:1rem;">
          <svg width="28" height="28" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto 0.4rem;">
            <circle cx="20" cy="20" r="20" fill="#42b549"/>
            <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="11" font-weight="bold" fill="white">Toped</text>
          </svg>
          <span>Tokopedia</span>
        </div>
        <div class="si-source-card" id="srcShopee" onclick="selectMarketplace('shopee')" style="padding:1rem;">
          <svg width="28" height="28" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto 0.4rem;">
            <circle cx="20" cy="20" r="20" fill="#ee4d2d"/>
            <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-size="9" font-weight="bold" fill="white">Shopee</text>
          </svg>
          <span>Shopee</span>
        </div>
      </div>

      <label class="si-label">URL Produk <span id="mpPlatformLabel">Tokopedia</span></label>
      <input type="url" id="siMarketUrl" class="si-input" placeholder="https://www.tokopedia.com/toko-anda/produk-abc">

      <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem 1rem; margin-top:1rem; font-size:0.75rem; color:#334155; display:flex; gap:0.5rem; align-items:flex-start;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span>Import hanya untuk produk milik Anda sendiri. Data diproses langsung ke daftar impor.</span>
      </div>

      <button class="si-btn" id="siMarketBtn" onclick="runSiMarketScrape()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Import dari Marketplace
      </button>

      <div class="si-progress" id="siMarketProgress">
        <p style="font-size:0.75rem; color:#0f172a; font-weight:600; margin-bottom:0.5rem;" id="siMarketProgressText">Sedang membaca data marketplace...</p>
        <div class="si-progress-bar"><div class="si-progress-fill" id="siMarketProgressFill"></div></div>
      </div>
      <div class="si-result" id="siMarketResult"></div>
    </div>

    {{-- TAB 4: LYNK.ID --}}
    <div class="si-tab-pane" id="si-lynk">
      <p style="font-size:0.8rem; color:#64748b; margin-bottom:1.25rem; line-height:1.6;">
        Impor produk dari Lynk.id secara otomatis menjadi <b>Produk Digital / Link Access</b>. Gunakan <b>Opsi 1 (URL)</b> atau <b>Opsi 2 (Paste Source Code)</b>.
      </p>

      {{-- METHOD TOGGLE CARDS --}}
      <div class="si-source-cards" style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1.25rem;">
        <div class="si-source-card selected" id="srcLynkUrl" onclick="switchLynkMethod('url')">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin:0 auto 0.3rem;"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          <span style="font-size:0.78rem;">Opsi 1: URL Lynk.id</span>
        </div>
        <div class="si-source-card" id="srcLynkHtml" onclick="switchLynkMethod('html')">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin:0 auto 0.3rem;"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          <span style="font-size:0.78rem;">Opsi 2: Source Code</span>
        </div>
      </div>

      {{-- OPSI 1: INPUT URL --}}
      <div id="lynkUrlBox">
        <label class="si-label">URL Produk Lynk.id</label>
        <input type="url" id="siLynkUrl" class="si-input" placeholder="https://lynk.id/mindiw/Pv23p2E">
        <span style="font-size:0.72rem; color:#94a3b8; margin-top:0.3rem; display:block;">Contoh: https://lynk.id/mindiw/Pv23p2E</span>
      </div>

      {{-- OPSI 2: INPUT HTML SOURCE CODE --}}
      <div id="lynkHtmlBox" style="display:none;">
        <label class="si-label">Paste Source Code Halaman (HTML Lynk.id)</label>
        <textarea id="siLynkHtmlCode" class="si-input" style="height:110px; padding:0.75rem; font-family:monospace; font-size:0.74rem;" placeholder="Buka link Lynk.id -> Klik kanan -> Lihat Sumber Halaman (Ctrl+U) -> Paste di sini..."></textarea>
        <span style="font-size:0.72rem; color:#0284c7; font-weight:600; margin-top:0.35rem; display:block;">💡 Tip: Gunakan opsi ini untuk impor data produk secara instan dan lengkap!</span>
      </div>

      <button class="si-btn" id="siLynkBtn" onclick="runSiLynkScrape()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        Import dari Lynk.id (Produk Digital)
      </button>

      <div class="si-progress" id="siLynkProgress">
        <p style="font-size:0.75rem; color:#0f172a; font-weight:600; margin-bottom:0.5rem;" id="siLynkProgressText">Membaca data produk dari Lynk.id...</p>
        <div class="si-progress-bar"><div class="si-progress-fill" id="siLynkProgressFill"></div></div>
      </div>
      <div class="si-result" id="siLynkResult"></div>
    </div>

    <div style="padding:1rem 1.75rem; border-top:1px solid #f1f5f9; background:#f8fafc; border-radius:0 0 20px 20px;">
      <p style="font-size:0.75rem; color:#64748b; text-align:center; font-weight:600; margin:0; display:flex; align-items:center; justify-content:center; gap:0.4rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        Smart Import membaca daftar produk otomatis dan memungkinkan Anda memilih & mengimpor ke katalog sekaligus.
      </p>
    </div>
  </div>
</div>

<script>
  // Smart Import Modal
  function openSmartImport() {
    document.getElementById('smartImportOverlay').classList.add('open');
  }
  function closeSmartImport(e) {
    if (!e || e.target === document.getElementById('smartImportOverlay')) {
      document.getElementById('smartImportOverlay').classList.remove('open');
    }
  }
  function switchSiTab(tab, el) {
    document.querySelectorAll('.si-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.si-tab-pane').forEach(p => p.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('si-' + tab).classList.add('active');
  }

  // Source selector for menu AI
  function selectSiSource(type) {
    document.getElementById('srcPhoto').classList.toggle('selected', type === 'photo');
    document.getElementById('srcUrl').classList.toggle('selected', type === 'url');
    document.getElementById('siPhotoInput').style.display = type === 'photo' ? 'block' : 'none';
    document.getElementById('siUrlInput').style.display = type === 'url' ? 'block' : 'none';
  }

  function previewSiMenuPhoto(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
      document.getElementById('siPhotoPreviewImg').src = ev.target.result;
      document.getElementById('siPhotoPreviewWrap').style.display = 'block';
    };
    reader.readAsDataURL(file);
  }

  // Marketplace selector
  let selectedMarketplace = 'tokopedia';
  function selectMarketplace(mp) {
    selectedMarketplace = mp;
    document.getElementById('srcTokopedia').classList.toggle('selected', mp === 'tokopedia');
    document.getElementById('srcShopee').classList.toggle('selected', mp === 'shopee');
    document.getElementById('mpPlatformLabel').textContent = mp === 'tokopedia' ? 'Tokopedia' : 'Shopee';
    document.getElementById('siMarketUrl').placeholder = mp === 'tokopedia'
      ? 'https://www.tokopedia.com/toko-anda/produk-abc'
      : 'https://shopee.co.id/toko-anda/produk-abc';
  }

  // Open the Scan Menu Result Table Modal (AI SCAN ONLY)
  function showScannedResultModal(items, categoryOverride) {
    closeSmartImport();
    document.getElementById('scan-menu-modal').style.display = 'flex';
    document.getElementById('scan-step-upload').style.display = 'none';
    document.getElementById('scan-step-loading').style.display = 'none';

    detectedMenuItems = items.map(it => {
      if (categoryOverride) it.category = categoryOverride;
      return it;
    });

    renderScanItemsTable();
    document.getElementById('scan-step-result').style.display = 'block';
    document.getElementById('btn-import-scanned').style.display = 'inline-flex';
    restoreImportBtn();
  }

  // Redirect to create form and pre-fill via sessionStorage
  function redirectToCreateWithData(item, productType) {
    item.product_type = productType || item.product_type || 'physical';
    sessionStorage.setItem('smart_imported_item', JSON.stringify(item));
    window.location.href = '{{ route("creator.products.create") }}?smart_import=1';
  }

  // SCAN MENU AI — shows popup selection list
  function runSiMenuScan() {
    const btn = document.getElementById('siMenuScanBtn');
    const urlVal = document.getElementById('siMenuUrl').value;
    const photoFile = document.getElementById('siMenuPhotoFile').files[0];
    const isPhoto = document.getElementById('srcPhoto').classList.contains('selected');

    if (isPhoto && !photoFile) { alert('Silakan upload foto menu terlebih dahulu.'); return; }
    if (!isPhoto && !urlVal) { alert('Silakan masukkan URL menu terlebih dahulu.'); return; }

    btn.disabled = true;
    document.getElementById('siMenuProgress').style.display = 'block';
    const csrfToken = '{{ csrf_token() }}';

    if (isPhoto) {
      const fd = new FormData();
      fd.append('menu_image', photoFile);
      fd.append('_token', csrfToken);

      fetch('{{ route("creator.products.scan-menu") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body: fd
      })
      .then(res => res.json())
      .then(res => {
        btn.disabled = false;
        document.getElementById('siMenuProgress').style.display = 'none';
        if (res.success && res.items && res.items.length > 0) {
          showScannedResultModal(res.items, 'Makanan'); // → popup modal
        } else {
          alert(res.message || 'Gagal scan foto menu.');
        }
      })
      .catch(err => {
        btn.disabled = false;
        document.getElementById('siMenuProgress').style.display = 'none';
        alert('Gagal scan foto: ' + err.message);
      });
    } else {
      const chosenType = document.getElementById('siMenuProductType').value || 'physical';
      fetch('{{ route("creator.products.scan-url") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ url: urlVal, source: 'menu_url', product_type: chosenType })
      })
      .then(res => res.json())
      .then(res => {
        btn.disabled = false;
        document.getElementById('siMenuProgress').style.display = 'none';
        if (res.success && res.items && res.items.length > 0) {
          showScannedResultModal(res.items, chosenType); // → popup modal
        } else {
          alert(res.message || 'Gagal membaca URL menu.');
        }
      })
      .catch(err => {
        btn.disabled = false;
        document.getElementById('siMenuProgress').style.display = 'none';
        alert('Gagal membaca URL: ' + err.message);
      });
    }
  }

  // TIKTOK SHOP — langsung redirect ke form produk
  function runSiTiktokScrape() {
    const url = document.getElementById('siTiktokUrl').value;
    if (!url) { alert('Masukkan URL produk TikTok Shop.'); return; }
    const btn = document.getElementById('siTiktokBtn');
    btn.disabled = true;
    document.getElementById('siTiktokProgress').style.display = 'block';
    document.getElementById('siTiktokProgressText').textContent = 'Membaca data produk dari TikTok...';
    const csrfToken = '{{ csrf_token() }}';

    fetch('{{ route("creator.products.scan-url") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ url: url, source: 'tiktokshop' })
    })
    .then(res => res.json())
    .then(res => {
      btn.disabled = false;
      document.getElementById('siTiktokProgress').style.display = 'none';
      if (res.success && res.items && res.items.length > 0) {
        redirectToCreateWithData(res.items[0], 'physical'); // → form produk langsung
      } else {
        alert(res.message || 'Gagal membaca data produk TikTok Shop.');
      }
    })
    .catch(err => {
      btn.disabled = false;
      document.getElementById('siTiktokProgress').style.display = 'none';
      alert('Terjadi kesalahan: ' + err.message);
    });
  }

  // MARKETPLACE (Tokopedia / Shopee) — langsung redirect ke form produk
  function runSiMarketScrape() {
    const url = document.getElementById('siMarketUrl').value;
    if (!url) { alert('Masukkan URL produk dari marketplace.'); return; }
    const btn = document.getElementById('siMarketBtn');
    btn.disabled = true;
    document.getElementById('siMarketProgress').style.display = 'block';
    document.getElementById('siMarketProgressText').textContent = 'Membaca data dari ' + (selectedMarketplace === 'tokopedia' ? 'Tokopedia' : 'Shopee') + '...';
    const csrfToken = '{{ csrf_token() }}';

    fetch('{{ route("creator.products.scan-url") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ url: url, source: selectedMarketplace })
    })
    .then(res => res.json())
    .then(res => {
      btn.disabled = false;
      document.getElementById('siMarketProgress').style.display = 'none';
      if (res.success && res.items && res.items.length > 0) {
        redirectToCreateWithData(res.items[0], 'physical'); // → form produk langsung
      } else {
        alert(res.message || 'Gagal membaca data produk Marketplace.');
      }
    })
    .catch(err => {
      btn.disabled = false;
      document.getElementById('siMarketProgress').style.display = 'none';
      alert('Terjadi kesalahan: ' + err.message);
    });
  }

  function strToBase64(str) {
    try {
      return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode('0x' + p1);
      }));
    } catch(e) {
      return '';
    }
  }

  let activeLynkMethod = 'url';
  function switchLynkMethod(method) {
    activeLynkMethod = method;
    document.getElementById('srcLynkUrl').classList.toggle('selected', method === 'url');
    document.getElementById('srcLynkHtml').classList.toggle('selected', method === 'html');
    document.getElementById('lynkUrlBox').style.display = method === 'url' ? 'block' : 'none';
    document.getElementById('lynkHtmlBox').style.display = method === 'html' ? 'block' : 'none';
  }

  // LYNK.ID — langsung redirect ke form produk sebagai Produk Digital
  async function runSiLynkScrape() {
    const urlVal = document.getElementById('siLynkUrl').value.trim();
    const htmlVal = document.getElementById('siLynkHtmlCode').value.trim();

    if (activeLynkMethod === 'url' && !urlVal) {
      alert('Silakan masukkan URL produk Lynk.id terlebih dahulu.');
      return;
    }
    if (activeLynkMethod === 'html' && !htmlVal) {
      alert('Silakan paste Source Code / HTML halaman Lynk.id terlebih dahulu.');
      return;
    }

    const btn = document.getElementById('siLynkBtn');
    btn.disabled = true;
    document.getElementById('siLynkProgress').style.display = 'block';
    const csrfToken = '{{ csrf_token() }}';

    let htmlPayload = (activeLynkMethod === 'html') ? htmlVal : '';
    let targetUrl = urlVal || 'https://lynk.id/imported-product';

    if (activeLynkMethod === 'url' && !htmlPayload) {
      const proxies = [
        'https://api.allorigins.win/raw?url=' + encodeURIComponent(urlVal),
        'https://api.codetabs.com/v1/proxy?quest=' + encodeURIComponent(urlVal),
        'https://corsproxy.io/?' + encodeURIComponent(urlVal)
      ];
      for (const pUrl of proxies) {
        try {
          const resp = await fetch(pUrl, { signal: AbortSignal.timeout(5000) });
          if (resp.ok) {
            const txt = await resp.text();
            if (txt && txt.length > 500) {
              htmlPayload = txt;
              break;
            }
          }
        } catch (e) {
          // try next proxy
        }
      }
    }

    const b64Payload = htmlPayload ? strToBase64(htmlPayload) : '';

    fetch('{{ route("creator.products.scan-lynk") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ url: targetUrl, html_b64: b64Payload, source: 'lynk' })
    })
    .then(async res => {
      const text = await res.text();
      let resJson;
      try {
        resJson = JSON.parse(text);
      } catch(e) {
        throw new Error('Gagal membaca data produk. Silakan coba lagi.');
      }
      return resJson;
    })
    .then(res => {
      btn.disabled = false;
      document.getElementById('siLynkProgress').style.display = 'none';
      if (res.success && res.items && res.items.length > 0) {
        redirectToCreateWithData(res.items[0], 'external_link');
      } else {
        alert(res.message || 'Gagal membaca data produk Lynk.id.');
      }
    })
    .catch(err => {
      btn.disabled = false;
      document.getElementById('siLynkProgress').style.display = 'none';
      alert(err.message);
    });
  }
</script>