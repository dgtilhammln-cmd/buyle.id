@extends('creator.layout')
@section('title', 'Inventori Bahan Baku & Resep Menu')
@section('page_title', 'Inventori Bahan')
@section('page_subtitle', $totalCount . ' bahan baku terdaftar (F&B / Cafe)')

@section('topbar_actions')
    <div class="prod-actions-row">
        <form action="{{ route('creator.inventories.index') }}" method="GET" class="prod-search-form">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bahan..." class="prod-search-input">
            <button type="submit" class="prod-search-btn" title="Cari">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </button>
        </form>

        <div class="prod-view-toggle">
            <button type="button" onclick="switchMaterialView('list')" id="mat-btn-view-list" class="prod-toggle-btn active" title="List View">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
            </button>
            <button type="button" onclick="switchMaterialView('grid')" id="mat-btn-view-grid" class="prod-toggle-btn" title="Grid View">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
            </button>
        </div>

        <button type="button" onclick="openRestockModal()" class="btn-restock-head" title="Restock Masuk">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
            <span class="btn-add-text-full">Restock Masuk</span>
            <span class="btn-add-text-short">Restock</span>
        </button>

        <button type="button" onclick="openAddMaterialModal()" class="btn-primary prod-add-btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            <span class="btn-add-text-full">Tambah Bahan</span>
            <span class="btn-add-text-short">Bahan</span>
        </button>
    </div>
@endsection

@section('styles')
<style>
    /* ── Pos Signature Green Gradient ── */
    :root {
        --pos-grad: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        --pos-grad-hover: linear-gradient(135deg, #18983e 0%, #94bc2f 100%);
        --pos-shadow: 0 4px 14px rgba(30, 179, 73, 0.28);
    }

    .prod-actions-row {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-wrap: wrap;
        flex: 1;
        justify-content: flex-end;
        min-width: 0;
    }

    .prod-search-form {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 4px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        flex: 1;
        min-width: 140px;
        max-width: 260px;
    }

    .prod-search-input {
        border: none;
        outline: none;
        padding: 8px 12px;
        font-size: 0.85rem;
        width: 100%;
        flex: 1;
        min-width: 0;
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
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .prod-toggle-btn {
        border: none;
        background: transparent;
        border-radius: 8px;
        padding: 7px 9px;
        cursor: pointer;
        color: #94A3B8;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .2s;
    }

    .prod-toggle-btn.active {
        background: var(--pos-grad);
        color: #fff;
    }

    .btn-restock-head {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f0fdf4;
        color: #166534;
        border: 1.5px solid #bbf7d0;
        padding: 0.55rem 0.95rem;
        border-radius: 12px;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-restock-head:hover {
        background: #dcfce7;
    }

    .prod-add-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--pos-grad);
        color: #fff;
        border: none;
        padding: 0.58rem 1.05rem;
        border-radius: 12px;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        box-shadow: var(--pos-shadow);
        transition: all 0.2s;
        white-space: nowrap;
    }

    .prod-add-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(30, 179, 73, 0.4);
    }

    .btn-add-text-full { display: inline; }
    .btn-add-text-short { display: none; }

    /* ── Navigation Tabs Capsule ── */
    .mat-tabs-bar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .mat-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.55rem 1rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 700;
        color: #64748b;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .mat-tab-btn:hover {
        color: #0f172a;
        background: #f1f5f9;
    }

    .mat-tab-btn.active {
        background: var(--pos-grad);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(30, 179, 73, 0.25);
    }

    .mat-tab-badge {
        font-size: 0.68rem;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(0,0,0,0.06);
    }

    .mat-tab-btn.active .mat-tab-badge {
        background: rgba(255,255,255,0.28);
        color: #ffffff;
    }

    /* ── Metric Summary Pills ── */
    .mat-stat-pills {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .mat-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
    }

    .mat-stat-pill .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    /* ── Grid Cards ── */
    .mat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1rem;
    }

    .mat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.15rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .mat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0,0,0,0.06);
    }

    /* ── Table Styling ── */
    .mat-table-wrap {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .mat-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
        text-align: left;
    }

    .mat-table th {
        background: #f8fafc;
        padding: 0.85rem 1rem;
        color: #64748b;
        font-weight: 700;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .mat-table td {
        padding: 0.95rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }

    .mat-table tr:last-child td {
        border-bottom: none;
    }

    /* ── Modal Popups ── */
    .mat-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .mat-modal.show {
        display: flex;
    }

    .mat-modal-content {
        background: #ffffff;
        border-radius: 20px;
        max-width: 520px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 1.5rem;
        box-shadow: 0 20px 45px rgba(0,0,0,0.18);
        animation: matModalPop 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes matModalPop {
        from { transform: scale(0.95) translateY(12px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }

    /* ── Mobile Responsive Matching /creator/products ── */
    @media (max-width: 768px) {
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
            padding: 6px 8px !important;
            font-size: 0.78rem !important;
        }

        .prod-search-btn {
            padding: 6px !important;
        }

        .prod-view-toggle {
            flex-shrink: 0 !important;
            padding: 2px !important;
        }

        .prod-toggle-btn {
            padding: 5px 7px !important;
        }

        .btn-restock-head, .prod-add-btn {
            flex-shrink: 0 !important;
            padding: 6px 10px !important;
            font-size: 0.76rem !important;
            border-radius: 10px !important;
        }

        .btn-add-text-full { display: none !important; }
        .btn-add-text-short { display: inline !important; }

        .mat-table th, .mat-table td {
            padding: 0.65rem 0.75rem !important;
            font-size: 0.76rem !important;
        }

        .mat-grid {
            grid-template-columns: 1fr !important;
        }

        /* Khusus Mobile pada Tab Resep: Sembunyikan Kategori, Harga & Kolom BOM agar compact & proporsional */
        .col-recipe-cat,
        .col-recipe-price,
        .col-recipe-bom {
            display: none !important;
        }

        .col-recipe-product {
            width: 58% !important;
            min-width: 0 !important;
        }

        .col-recipe-action {
            width: 42% !important;
            text-align: right !important;
        }

        .recipe-chips-container {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
    {{-- Tabs Nav --}}
    <div class="mat-tabs-bar">
        <a href="{{ route('creator.inventories.index', ['tab' => 'materials']) }}" class="mat-tab-btn {{ $tab === 'materials' ? 'active' : '' }}">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            Bahan Baku
            <span class="mat-tab-badge">{{ $totalCount }}</span>
        </a>
        <a href="{{ route('creator.inventories.index', ['tab' => 'recipes']) }}" class="mat-tab-btn {{ $tab === 'recipes' ? 'active' : '' }}">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            Resep Menu (BOM)
            <span class="mat-tab-badge">{{ $products->count() }}</span>
        </a>
        <a href="{{ route('creator.inventories.index', ['tab' => 'mutations']) }}" class="mat-tab-btn {{ $tab === 'mutations' ? 'active' : '' }}">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
            Riwayat Mutasi
        </a>
    </div>

    {{-- Alert Success / Errors --}}
    @if(session('success'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:0.85rem 1.15rem; border-radius:12px; margin-bottom:1.25rem; font-size:0.82rem; font-weight:600; display:flex; align-items:center; gap:8px;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         TAB 1: DAFTAR BAHAN BAKU
         ════════════════════════════════════════════════════════════════════ --}}
    @if($tab === 'materials')
        {{-- Status Pills --}}
        <div class="mat-stat-pills">
            <span class="mat-stat-pill">
                <span class="dot" style="background:#64748b;"></span>
                Total: <strong>{{ $totalCount }}</strong>
            </span>
            <span class="mat-stat-pill" style="border-color:#bbf7d0; background:#f0fdf4; color:#166534;">
                <span class="dot" style="background:#16a34a;"></span>
                Stok Aman: <strong>{{ $safeCount }}</strong>
            </span>
            <span class="mat-stat-pill" style="border-color:#fde68a; background:#fffbeb; color:#92400e;">
                <span class="dot" style="background:#d97706;"></span>
                Menipis: <strong>{{ $warningCount }}</strong>
            </span>
            <span class="mat-stat-pill" style="border-color:#fca5a5; background:#fef2f2; color:#991b1b;">
                <span class="dot" style="background:#dc2626;"></span>
                Habis: <strong>{{ $dangerCount }}</strong>
            </span>
        </div>

        @if($materials->isEmpty())
            <div style="text-align:center; padding:3.5rem 1.5rem; background:#ffffff; border:1px dashed #cbd5e1; border-radius:20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#f0fdf4; color:#1eb349; display:inline-flex; align-items:center; justify-content:center; margin-bottom:1rem;">
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    </svg>
                </div>
                <h3 style="font-size:1.05rem; font-weight:700; color:#0f172a; margin-bottom:0.35rem;">Belum Ada Bahan Baku</h3>
                <p style="font-size:0.82rem; color:#64748b; max-width:400px; margin:0 auto 1.25rem;">
                    Daftarkan bahan baku fisik untuk cafe/resto Anda (misal biji kopi, susu, cup, bumbu) agar stok terpantau otomatis saat transaksi POS.
                </p>
                <button type="button" onclick="openAddMaterialModal()" class="prod-add-btn" style="margin:0 auto;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>+ Tambah Bahan Baku Pertama</span>
                </button>
            </div>
        @else
            {{-- VIEW LIST (Default) --}}
            <div id="matViewList" class="mat-table-wrap">
                <table class="mat-table">
                    <thead>
                        <tr>
                            <th>Nama Bahan</th>
                            <th>Kategori</th>
                            <th>Sisa Stok</th>
                            <th>Min. Alert</th>
                            <th>Estimasi Biaya/Unit</th>
                            <th>Status</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $m)
                            @php $st = $m->stock_status; @endphp
                            <tr>
                                <td>
                                    <div style="font-weight:700; color:#0f172a;">{{ $m->name }}</div>
                                    @if($m->code)
                                        <div style="font-size:0.72rem; color:#64748b; font-family:monospace;">{{ $m->code }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span style="background:#f1f5f9; color:#475569; padding:3px 8px; border-radius:6px; font-size:0.72rem; font-weight:600; text-transform:capitalize;">
                                        {{ str_replace('_', ' ', $m->category) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight:800; font-size:0.95rem; color:#0f172a;">
                                        {{ number_format($m->stock, 2, ',', '.') }}
                                        <span style="font-size:0.75rem; font-weight:600; color:#64748b;">{{ $m->unit }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="color:#64748b; font-size:0.8rem;">
                                        {{ (float)$m->min_stock > 0 ? number_format($m->min_stock, 2, ',', '.') . ' ' . $m->unit : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span style="color:#475569; font-weight:600;">
                                        Rp {{ number_format($m->cost_per_unit, 0, ',', '.') }}/{{ $m->unit }}
                                    </span>
                                </td>
                                <td>
                                    <span style="background:{{ $st['bg'] }}; color:{{ $st['color'] }}; border:1px solid {{ $st['border'] }}; padding:3px 9px; border-radius:999px; font-size:0.72rem; font-weight:700;">
                                        {{ $st['label'] }}
                                    </span>
                                </td>
                                <td style="text-align:right; white-space:nowrap;">
                                    <button type="button" onclick="openRestockSingle({{ $m->id }}, '{{ addslashes($m->name) }}', '{{ $m->unit }}', {{ $m->cost_per_unit }})"
                                        style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:5px 9px; border-radius:8px; font-size:0.75rem; font-weight:700; cursor:pointer; margin-right:4px;">
                                        + Restock
                                    </button>
                                    <button type="button" onclick="openEditMaterialModal({{ json_encode($m) }})"
                                        style="background:#f8fafc; border:1px solid #e2e8f0; color:#475569; padding:5px 8px; border-radius:8px; font-size:0.75rem; cursor:pointer; margin-right:4px;" title="Edit">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>
                                    <form action="{{ route('creator.inventories.destroy', $m->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus bahan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:#fef2f2; border:1px solid #fca5a5; color:#dc2626; padding:5px 8px; border-radius:8px; font-size:0.75rem; cursor:pointer;" title="Hapus">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- VIEW GRID --}}
            <div id="matViewGrid" class="mat-grid" style="display:none;">
                @foreach($materials as $m)
                    @php $st = $m->stock_status; @endphp
                    <div class="mat-card">
                        <div>
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.75rem;">
                                <div>
                                    <h4 style="font-size:0.95rem; font-weight:700; color:#0f172a; margin:0 0 3px;">{{ $m->name }}</h4>
                                    <span style="background:#f1f5f9; color:#475569; padding:2px 7px; border-radius:5px; font-size:0.7rem; font-weight:600; text-transform:capitalize;">
                                        {{ str_replace('_', ' ', $m->category) }}
                                    </span>
                                </div>
                                <span style="background:{{ $st['bg'] }}; color:{{ $st['color'] }}; border:1px solid {{ $st['border'] }}; padding:2px 8px; border-radius:999px; font-size:0.7rem; font-weight:700;">
                                    {{ $st['label'] }}
                                </span>
                            </div>

                            <div style="background:#f8fafc; border-radius:12px; padding:0.75rem; margin-bottom:0.85rem;">
                                <div style="font-size:0.72rem; color:#64748b; margin-bottom:2px;">Sisa Stok Fisik</div>
                                <div style="font-size:1.25rem; font-weight:800; color:#0f172a;">
                                    {{ number_format($m->stock, 2, ',', '.') }}
                                    <span style="font-size:0.8rem; font-weight:600; color:#64748b;">{{ $m->unit }}</span>
                                </div>
                                <div style="font-size:0.72rem; color:#64748b; margin-top:4px;">
                                    Estimasi modal: <strong style="color:#0f172a;">Rp {{ number_format($m->cost_per_unit, 0, ',', '.') }}/{{ $m->unit }}</strong>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; gap:0.4rem; padding-top:0.5rem; border-top:1px solid #f1f5f9;">
                            <button type="button" onclick="openRestockSingle({{ $m->id }}, '{{ addslashes($m->name) }}', '{{ $m->unit }}', {{ $m->cost_per_unit }})"
                                style="flex:1; background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:6px; border-radius:8px; font-size:0.78rem; font-weight:700; cursor:pointer;">
                                + Restock
                            </button>
                            <button type="button" onclick="openEditMaterialModal({{ json_encode($m) }})"
                                style="background:#f8fafc; border:1px solid #e2e8f0; color:#475569; padding:6px 10px; border-radius:8px; font-size:0.78rem; cursor:pointer;">
                                Edit
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div style="margin-top:1.25rem;">
                {{ $materials->links() }}
            </div>
        @endif
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         TAB 2: RESEP MENU (BILL OF MATERIALS) - KHUSUS F&B / MAKANAN & MINUMAN
         ════════════════════════════════════════════════════════════════════ --}}
    @if($tab === 'recipes')
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:0.85rem 1.15rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:10px;">
            <div style="width:34px; height:34px; border-radius:50%; background:#ecfdf5; color:#1eb349; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            </div>
            <div style="font-size:0.78rem; color:#475569; line-height:1.45;">
                Menampilkan daftar menu makanan &amp; minuman. Saat menu terjual di Kasir POS, stok bahan baku akan <strong>otomatis berkurang</strong> sesuai formula resep.
            </div>
        </div>

        @if($products->isEmpty())
            <div style="text-align:center; padding:3rem; background:#fff; border-radius:16px; border:1px dashed #cbd5e1;">
                <p style="color:#64748b; font-size:0.85rem;">Belum ada produk bertipe makanan / minuman yang terdaftar.</p>
                <a href="{{ route('creator.products.create') }}" class="prod-add-btn" style="margin:1rem auto 0; display:inline-flex;">+ Tambah Menu Makanan</a>
            </div>
        @else
            <div class="mat-table-wrap">
                <table class="mat-table" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="col-recipe-product">Menu Jual</th>
                            <th class="col-recipe-cat">Kategori</th>
                            <th class="col-recipe-price">Harga</th>
                            <th class="col-recipe-bom">Bahan Dalam Resep (BOM)</th>
                            <th class="col-recipe-action" style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $p)
                            <tr>
                                <td class="col-recipe-product">
                                    <div style="display:flex; align-items:center; gap:9px;">
                                        <img src="{{ $p->image_url }}" alt="{{ $p->name }}"
                                            style="width:40px; height:40px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; flex-shrink:0; background:#f8fafc;"
                                            onerror="this.onerror=null;this.src='{{ \App\Models\Product::getPlaceholderUrl() }}';this.style.objectFit='contain';this.style.padding='4px';">
                                        <div style="min-width:0;">
                                            <div style="font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px;">{{ $p->name }}</div>
                                            <div style="font-size:0.7rem; color:#64748b;">
                                                <span style="color:#16a34a; font-weight:700;">{{ $p->recipes->count() }} Bahan</span> tertaut
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-recipe-cat">
                                    <span style="background:#f1f5f9; color:#475569; padding:2px 7px; border-radius:6px; font-size:0.72rem; font-weight:600;">
                                        {{ $p->category?->name ?? 'F&B' }}
                                    </span>
                                </td>
                                <td class="col-recipe-price">
                                    <span style="font-weight:700; color:#0f172a;">
                                        Rp {{ number_format($p->sale_price ?: $p->price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="col-recipe-bom">
                                    @if($p->recipes->isEmpty())
                                        <span style="color:#94a3b8; font-style:italic; font-size:0.75rem;">Belum ada resep</span>
                                    @else
                                        <div class="recipe-chips-container" style="display:flex; flex-wrap:wrap; gap:4px;">
                                            @foreach($p->recipes as $rc)
                                                <span style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; font-size:0.72rem; font-weight:600; padding:2px 6px; border-radius:6px; white-space:nowrap;">
                                                    {{ $rc->material?->name ?? 'Bahan' }}: {{ (float)$rc->quantity }} {{ $rc->material?->unit }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="col-recipe-action" style="text-align:right; white-space:nowrap;">
                                    <div style="display:inline-flex; flex-direction:column; gap:4px; align-items:flex-end;">
                                        <button type="button" onclick="openRecipeModal({{ $p->id }}, '{{ addslashes($p->name) }}', {{ json_encode($p->recipes) }})"
                                            style="background:var(--pos-grad); border:none; color:#ffffff; padding:6px 12px; border-radius:8px; font-size:0.75rem; font-weight:700; cursor:pointer; box-shadow:0 2px 6px rgba(30,179,73,0.25); font-family:inherit; white-space:nowrap;">
                                            Atur Resep
                                        </button>
                                        @if(!$p->recipes->isEmpty())
                                            <button type="button" onclick="openRecipeModal({{ $p->id }}, '{{ addslashes($p->name) }}', {{ json_encode($p->recipes) }})"
                                                style="background:#f1f5f9; border:none; color:#475569; padding:3px 8px; border-radius:6px; font-size:0.68rem; font-weight:600; cursor:pointer; font-family:inherit; white-space:nowrap;">
                                                Lihat Resep ({{ $p->recipes->count() }})
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         TAB 3: RIWAYAT MUTASI STOK
         ════════════════════════════════════════════════════════════════════ --}}
    @if($tab === 'mutations')
        <div class="mat-table-wrap">
            <table class="mat-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nama Bahan</th>
                        <th>Tipe Mutasi</th>
                        <th>Perubahan</th>
                        <th>Stok Sisa</th>
                        <th>Keterangan / Order</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mutations as $mut)
                        @php
                            $isPlus = (float)$mut->amount > 0;
                        @endphp
                        <tr>
                            <td style="color:#64748b; font-size:0.76rem; white-space:nowrap;">
                                {{ $mut->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <strong style="color:#0f172a;">{{ $mut->material?->name ?? 'Bahan Terhapus' }}</strong>
                            </td>
                            <td>
                                <span style="background:{{ $isPlus ? '#f0fdf4' : '#fef2f2' }}; color:{{ $isPlus ? '#16a34a' : '#dc2626' }}; padding:3px 8px; border-radius:6px; font-size:0.72rem; font-weight:700;">
                                    {{ $mut->type_label }}
                                </span>
                            </td>
                            <td>
                                <strong style="color:{{ $isPlus ? '#16a34a' : '#dc2626' }};">
                                    {{ $isPlus ? '+' : '' }}{{ number_format($mut->amount, 2, ',', '.') }}
                                    <span style="font-size:0.75rem; color:#64748b;">{{ $mut->material?->unit }}</span>
                                </strong>
                            </td>
                            <td style="font-weight:700; color:#0f172a;">
                                {{ number_format($mut->stock_after, 2, ',', '.') }} {{ $mut->material?->unit }}
                            </td>
                            <td style="color:#64748b; font-size:0.78rem;">
                                {{ $mut->reference_note ?: ($mut->order ? 'Order #' . $mut->order->order_number : '-') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:2rem; color:#94a3b8;">
                                Belum ada catatan riwayat mutasi stok bahan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.25rem;">
            {{ $mutations->links() }}
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         MODAL: TAMBAH BAHAN BAKU BARU
         ════════════════════════════════════════════════════════════════════ --}}
    <div id="modalAddMaterial" class="mat-modal">
        <div class="mat-modal-content">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                <h3 style="font-size:1.1rem; font-weight:700; color:#0f172a; margin:0;">Tambah Bahan Baku</h3>
                <button type="button" onclick="closeModal('modalAddMaterial')" style="background:none; border:none; color:#94a3b8; font-size:1.25rem; cursor:pointer;">&times;</button>
            </div>

            <form action="{{ route('creator.inventories.store') }}" method="POST">
                @csrf
                <div style="margin-bottom:0.9rem;">
                    <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Nama Bahan Baku *</label>
                    <input type="text" name="name" required placeholder="Contoh: Biji Kopi Espresso Blend" style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.9rem;">
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Kategori *</label>
                        <select name="category" required style="width:100%; padding:9px 10px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                            <option value="kering">Bahan Kering</option>
                            <option value="cair_dingin">Cair / Segar</option>
                            <option value="kemasan">Kemasan / Cup</option>
                            <option value="bumbu">Bumbu & Saus</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Satuan Dasar *</label>
                        <select name="unit" required style="width:100%; padding:9px 10px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                            <option value="gram">gram (gr)</option>
                            <option value="ml">mililiter (ml)</option>
                            <option value="pcs">pcs / buah</option>
                            <option value="lembar">lembar</option>
                            <option value="kg">kilogram (kg)</option>
                            <option value="liter">liter (L)</option>
                            <option value="porsi">porsi</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.9rem;">
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Stok Awal</label>
                        <input type="number" step="0.01" name="stock" value="0" style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Batas Peringatan (Min)</label>
                        <input type="number" step="0.01" name="min_stock" value="0" placeholder="0 = tanpa alert" style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Estimasi Harga Beli per Satuan (Rp)</label>
                    <input type="number" step="1" name="cost_per_unit" value="0" placeholder="Contoh: 150 (Rp 150/gram)" style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                    <small style="color:#64748b; font-size:0.72rem;">Digunakan untuk menghitung HPP (Harga Pokok Penjualan) per cangkir.</small>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                    <button type="button" onclick="closeModal('modalAddMaterial')" style="padding:9px 16px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#475569; font-size:0.82rem; font-weight:600; cursor:pointer;">Batal</button>
                    <button type="submit" class="prod-add-btn">Simpan Bahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         MODAL: EDIT BAHAN BAKU
         ════════════════════════════════════════════════════════════════════ --}}
    <div id="modalEditMaterial" class="mat-modal">
        <div class="mat-modal-content">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                <h3 style="font-size:1.1rem; font-weight:700; color:#0f172a; margin:0;">Edit Bahan Baku</h3>
                <button type="button" onclick="closeModal('modalEditMaterial')" style="background:none; border:none; color:#94a3b8; font-size:1.25rem; cursor:pointer;">&times;</button>
            </div>

            <form id="formEditMaterial" method="POST">
                @csrf
                @method('PUT')
                <div style="margin-bottom:0.9rem;">
                    <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Nama Bahan Baku *</label>
                    <input type="text" id="editMatName" name="name" required style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.9rem;">
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Kategori *</label>
                        <select id="editMatCategory" name="category" required style="width:100%; padding:9px 10px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                            <option value="kering">Bahan Kering</option>
                            <option value="cair_dingin">Cair / Segar</option>
                            <option value="kemasan">Kemasan / Cup</option>
                            <option value="bumbu">Bumbu & Saus</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Satuan Dasar *</label>
                        <select id="editMatUnit" name="unit" required style="width:100%; padding:9px 10px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                            <option value="gram">gram (gr)</option>
                            <option value="ml">mililiter (ml)</option>
                            <option value="pcs">pcs / buah</option>
                            <option value="lembar">lembar</option>
                            <option value="kg">kilogram (kg)</option>
                            <option value="liter">liter (L)</option>
                            <option value="porsi">porsi</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1.25rem;">
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Batas Peringatan (Min)</label>
                        <input type="number" step="0.01" id="editMatMinStock" name="min_stock" style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Harga Modal per Satuan (Rp)</label>
                        <input type="number" step="1" id="editMatCost" name="cost_per_unit" style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                    <button type="button" onclick="closeModal('modalEditMaterial')" style="padding:9px 16px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#475569; font-size:0.82rem; font-weight:600; cursor:pointer;">Batal</button>
                    <button type="submit" class="prod-add-btn">Perbarui Bahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         MODAL: RESTOCK MASUK (TAMBAH STOK CEPAT)
         ════════════════════════════════════════════════════════════════════ --}}
    <div id="modalRestock" class="mat-modal">
        <div class="mat-modal-content">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                <h3 style="font-size:1.1rem; font-weight:700; color:#0f172a; margin:0;">Restock Bahan Masuk</h3>
                <button type="button" onclick="closeModal('modalRestock')" style="background:none; border:none; color:#94a3b8; font-size:1.25rem; cursor:pointer;">&times;</button>
            </div>

            <form id="formRestock" method="POST">
                @csrf
                <div style="margin-bottom:0.9rem;">
                    <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Pilih Bahan Baku *</label>
                    <select id="restockMatSelect" onchange="updateRestockUnit()" required style="width:100%; padding:9px 10px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                        @foreach($allMats as $m)
                            <option value="{{ $m->id }}" data-unit="{{ $m->unit }}" data-cost="{{ $m->cost_per_unit }}">{{ $m->name }} (Sisa: {{ (float)$m->stock }} {{ $m->unit }})</option>
                        @endforeach
                    </select>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.9rem;">
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Jumlah Masuk *</label>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <input type="number" step="0.01" name="amount" required placeholder="0" style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                            <span id="restockUnitLabel" style="font-size:0.82rem; font-weight:700; color:#64748b; white-space:nowrap;">gram</span>
                        </div>
                    </div>
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Harga Beli per Satuan (Rp)</label>
                        <input type="number" step="1" name="cost_per_unit" id="restockCostInput" placeholder="Opsional" style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Catatan / No. Nota Supplier</label>
                    <input type="text" name="notes" placeholder="Contoh: Beli dari Toko Grosir Kopi Jaya" style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                    <button type="button" onclick="closeModal('modalRestock')" style="padding:9px 16px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#475569; font-size:0.82rem; font-weight:600; cursor:pointer;">Batal</button>
                    <button type="submit" class="prod-add-btn">+ Simpan Restock</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         MODAL: ATUR RESEP PRODUK (BOM BUILDER)
         ════════════════════════════════════════════════════════════════════ --}}
    <div id="modalRecipe" class="mat-modal">
        <div class="mat-modal-content" style="max-width:580px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <div>
                    <h3 style="font-size:1.1rem; font-weight:700; color:#0f172a; margin:0;">Formula Resep Menu</h3>
                    <p id="recipeProductName" style="font-size:0.8rem; color:#64748b; margin:2px 0 0;"></p>
                </div>
                <button type="button" onclick="closeModal('modalRecipe')" style="background:none; border:none; color:#94a3b8; font-size:1.25rem; cursor:pointer;">&times;</button>
            </div>

            <form id="formRecipe" method="POST">
                @csrf
                <div id="recipeRowsContainer" style="margin-bottom:1rem; max-height:360px; overflow-y:auto; padding-right:4px;">
                    <!-- Rows populated dynamically -->
                </div>

                <div style="margin-bottom:1.25rem;">
                    <button type="button" onclick="addRecipeRow()" style="background:#f1f5f9; border:1px dashed #cbd5e1; color:#0f172a; font-size:0.78rem; font-weight:700; width:100%; padding:8px; border-radius:10px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>+ Tambah Baris Bahan Baku</span>
                    </button>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.5rem; border-top:1px solid #f1f5f9; padding-top:1rem;">
                    <button type="button" onclick="closeModal('modalRecipe')" style="padding:9px 16px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#475569; font-size:0.82rem; font-weight:600; cursor:pointer;">Tutup</button>
                    <button type="submit" class="prod-add-btn">Simpan Formula Resep</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const allMaterialsList = @json($allMats);

    // Switch View List / Grid
    function switchMaterialView(view) {
        const listEl = document.getElementById('matViewList');
        const gridEl = document.getElementById('matViewGrid');
        const btnList = document.getElementById('mat-btn-view-list');
        const btnGrid = document.getElementById('mat-btn-view-grid');

        if (view === 'grid') {
            if (listEl) listEl.style.display = 'none';
            if (gridEl) gridEl.style.display = 'grid';
            if (btnList) btnList.classList.remove('active');
            if (btnGrid) btnGrid.classList.add('active');
        } else {
            if (listEl) listEl.style.display = 'block';
            if (gridEl) gridEl.style.display = 'none';
            if (btnList) btnList.classList.add('active');
            if (btnGrid) btnGrid.classList.remove('active');
        }
    }

    // Modal Control
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('show');
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('show');
    }

    function openAddMaterialModal() {
        openModal('modalAddMaterial');
    }

    function openEditMaterialModal(mat) {
        document.getElementById('editMatName').value = mat.name;
        document.getElementById('editMatCategory').value = mat.category;
        document.getElementById('editMatUnit').value = mat.unit;
        document.getElementById('editMatMinStock').value = mat.min_stock;
        document.getElementById('editMatCost').value = mat.cost_per_unit;

        const form = document.getElementById('formEditMaterial');
        form.action = `/creator/inventories/${mat.id}`;

        openModal('modalEditMaterial');
    }

    function openRestockModal() {
        const sel = document.getElementById('restockMatSelect');
        if (sel && sel.options.length > 0) {
            updateRestockUnit();
        }
        const form = document.getElementById('formRestock');
        if (sel && sel.value) {
            form.action = `/creator/inventories/${sel.value}/restock`;
        }
        openModal('modalRestock');
    }

    function openRestockSingle(id, name, unit, cost) {
        const sel = document.getElementById('restockMatSelect');
        if (sel) {
            sel.value = id;
            updateRestockUnit();
        }
        const form = document.getElementById('formRestock');
        form.action = `/creator/inventories/${id}/restock`;
        openModal('modalRestock');
    }

    function updateRestockUnit() {
        const sel = document.getElementById('restockMatSelect');
        const opt = sel.options[sel.selectedIndex];
        if (opt) {
            const unit = opt.getAttribute('data-unit') || '';
            const cost = opt.getAttribute('data-cost') || '';
            document.getElementById('restockUnitLabel').textContent = unit;
            document.getElementById('restockCostInput').value = cost || '';
            const form = document.getElementById('formRestock');
            form.action = `/creator/inventories/${sel.value}/restock`;
        }
    }

    // Recipe Modal Builder
    let currentRecipeRowCount = 0;

    function openRecipeModal(productId, productName, existingRecipes) {
        document.getElementById('recipeProductName').textContent = `Menu: ${productName}`;
        const form = document.getElementById('formRecipe');
        form.action = `/creator/inventories/recipe/${productId}`;

        const container = document.getElementById('recipeRowsContainer');
        container.innerHTML = '';
        currentRecipeRowCount = 0;

        if (existingRecipes && existingRecipes.length > 0) {
            existingRecipes.forEach(rc => {
                addRecipeRow(rc.material_id, rc.quantity, rc.notes);
            });
        } else {
            addRecipeRow(); // Add 1 blank row by default
        }

        openModal('modalRecipe');
    }

    function addRecipeRow(selectedMatId = null, qty = '', notes = '') {
        const container = document.getElementById('recipeRowsContainer');
        const idx = currentRecipeRowCount++;

        let matOptions = '<option value="">-- Pilih Bahan Baku --</option>';
        allMaterialsList.forEach(m => {
            const sel = (selectedMatId && m.id == selectedMatId) ? 'selected' : '';
            matOptions += `<option value="${m.id}" data-unit="${m.unit}" ${sel}>${m.name} (${m.unit})</option>`;
        });

        const rowHtml = `
            <div id="recipe-row-${idx}" style="display:flex; gap:8px; align-items:center; margin-bottom:8px; background:#f8fafc; padding:8px 10px; border-radius:10px; border:1px solid #e2e8f0;">
                <select name="recipes[${idx}][material_id]" required style="flex:2; padding:7px 9px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.8rem; font-family:inherit;">
                    ${matOptions}
                </select>
                <div style="flex:1; display:flex; align-items:center; gap:4px;">
                    <input type="number" step="0.01" name="recipes[${idx}][quantity]" value="${qty}" required placeholder="Takaran" style="width:100%; padding:7px 9px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.8rem; font-family:inherit; box-sizing:border-box;">
                </div>
                <button type="button" onclick="removeRecipeRow(${idx})" style="background:#fee2e2; border:none; color:#dc2626; width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center; cursor:pointer;" title="Hapus Baris">
                    &times;
                </button>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', rowHtml);
    }

    function removeRecipeRow(idx) {
        const row = document.getElementById(`recipe-row-${idx}`);
        if (row) row.remove();
    }
</script>
@endsection
