@extends('creator.layout')
@section('title', 'Inventori Bahan Baku & Resep Menu')
@section('page_title', 'Inventori Bahan')
@section('page_subtitle', $totalCount . ' bahan baku terdaftar (F&B / Cafe)')

@section('topbar_actions')
    <div class="prod-actions-row">
        <form action="{{ route('creator.inventories.index') }}" method="GET" class="prod-search-form">
            <input type="hidden" name="tab" value="{{ $tab }}">
            @if(request('from')) <input type="hidden" name="from" value="{{ request('from') }}"> @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bahan..."
                class="prod-search-input">
            <button type="submit" class="prod-search-btn" title="Cari">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </button>
        </form>

        <div class="prod-view-toggle">
            <button type="button" onclick="switchMaterialView('list')" id="mat-btn-view-list" class="prod-toggle-btn active"
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
            <button type="button" onclick="switchMaterialView('grid')" id="mat-btn-view-grid" class="prod-toggle-btn"
                title="Grid View">
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
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
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

        .btn-add-text-full {
            display: inline;
        }

        .btn-add-text-short {
            display: none;
        }

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
            background: rgba(0, 0, 0, 0.06);
        }

        .mat-tab-btn.active .mat-tab-badge {
            background: rgba(255, 255, 255, 0.28);
            color: #ffffff;
        }

        /* ── Metric Summary Pills ── */
        .mat-stat-pills {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin-bottom: 1.15rem;
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 2px;
        }

        .mat-stat-pills::-webkit-scrollbar {
            display: none;
        }

        .mat-stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.28rem 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            line-height: 1.2;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .mat-stat-pill strong {
            font-weight: 600;
        }

        .mat-stat-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ── Grid Cards & Badges (Matching Products Page) ── */
        .grid-status-btn {
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.22rem 0.55rem;
            border-radius: 100px;
            color: #fff;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            line-height: 1;
            letter-spacing: 0.02em;
        }

        .grid-status-btn.active {
            background: rgba(16, 185, 129, 0.95);
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);
        }

        .grid-status-btn.archived {
            background: rgba(245, 158, 11, 0.95);
            box-shadow: 0 2px 6px rgba(245, 158, 11, 0.35);
        }

        .grid-card-item {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            border: 1px solid #F1F5F9;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }

        .grid-card-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        #bomViewGrid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 0.85rem;
        }

        @media (max-width: 991px) {
            #bomViewGrid {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 0.75rem;
            }
        }

        @media (max-width: 600px) {
            #bomViewGrid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.55rem;
            }
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .mat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.06);
        }

        /* ── Table Styling ── */
        .mat-table-wrap {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
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
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.18);
            animation: matModalPop 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes matModalPop {
            from {
                transform: scale(0.95) translateY(12px);
                opacity: 0;
            }

            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
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

            .btn-restock-head,
            .prod-add-btn {
                flex-shrink: 0 !important;
                padding: 6px 10px !important;
                font-size: 0.76rem !important;
                border-radius: 10px !important;
            }

            .btn-add-text-full {
                display: none !important;
            }

            .btn-add-text-short {
                display: inline !important;
            }

            .mat-table th,
            .mat-table td {
                padding: 0.65rem 0.75rem !important;
                font-size: 0.76rem !important;
            }

            .mat-grid {
                grid-template-columns: 1fr !important;
            }

            /* Khusus Mobile pada Tab Resep List View: Sembunyikan kolom sekunder agar tabel tetap ringkas & proporsional */
            .col-recipe-cat,
            .col-recipe-price,
            .col-recipe-hpp,
            .col-recipe-margin,
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

        .prod-mobile-header {
            display: none;
        }

        /* Mobile Quick Action Bar (Hanya tampil di mobile, disembunyikan total di desktop) */
        .mat-mobile-quick-actions {
            display: none !important;
        }

        @media (max-width: 991px) {
            .prod-mobile-header {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                padding: 0.25rem 0 0.35rem;
                margin-bottom: 0;
            }

            .prod-mobile-btn {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #ffffff;
                border: 1.5px solid #f1ece4;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #0f172a;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
                cursor: pointer;
                text-decoration: none;
                transition: all 0.2s ease;
                flex-shrink: 0;
            }

            .prod-mobile-btn:active {
                transform: scale(0.92);
            }

            .prod-mobile-title {
                margin: 0;
                font-size: 1.1rem;
                font-weight: 800;
                color: #0f172a;
                letter-spacing: -0.01em;
                text-align: center;
                flex: 1;
            }

            .mat-mobile-quick-actions {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
                margin-bottom: 1.15rem;
            }

            .mat-quick-btn-primary {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                background: var(--pos-grad);
                color: #ffffff;
                border: none;
                padding: 0.62rem 0.85rem;
                border-radius: 12px;
                font-size: 0.8rem;
                font-weight: 700;
                font-family: inherit;
                box-shadow: 0 4px 14px rgba(30, 179, 73, 0.28);
                cursor: pointer;
                transition: all 0.2s;
            }

            .mat-quick-btn-primary:active {
                transform: scale(0.96);
            }

            .mat-quick-btn-secondary {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                background: #f0fdf4;
                color: #166534;
                border: 1.5px solid #bbf7d0;
                padding: 0.62rem 0.85rem;
                border-radius: 12px;
                font-size: 0.8rem;
                font-weight: 700;
                font-family: inherit;
                cursor: pointer;
                transition: all 0.2s;
            }

            .mat-quick-btn-secondary:active {
                transform: scale(0.96);
            }

            /* Floating Action Buttons on Mobile (Image 3) */
            .mat-mobile-fab-wrap {
                display: flex !important;
                align-items: center;
                gap: 12px;
                position: fixed;
                right: 18px;
                bottom: max(22px, calc(18px + env(safe-area-inset-bottom, 0px)));
                z-index: 9999;
            }

            .mat-mobile-fab-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                cursor: pointer;
                font-family: inherit;
                border: none;
                transition: transform 0.2s, box-shadow 0.2s;
                -webkit-tap-highlight-color: transparent;
            }

            .mat-mobile-fab-btn:active {
                transform: scale(0.92);
            }

            .mat-mobile-fab-primary {
                width: 52px;
                height: 52px;
                background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
                color: #ffffff;
                box-shadow: 0 8px 24px rgba(30, 179, 73, 0.42), 0 3px 8px rgba(0, 0, 0, 0.12);
            }

            .mat-mobile-fab-secondary {
                width: 46px;
                height: 46px;
                background: #f0fdf4;
                color: #166534;
                border: 1.5px solid #bbf7d0;
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            }
        }

        .mat-mobile-fab-wrap {
            display: none;
        }
    </style>
@endsection

@section('content')
    {{-- Mobile Top Header --}}
    <div class="prod-mobile-header">
        <button type="button" class="prod-mobile-btn" onclick="handleInventoryMobileBack()" title="Kembali">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </button>
        <h2 class="prod-mobile-title">Inventori Bahan</h2>
        <div style="width: 40px;"></div>
    </div>

    {{-- Tabs Nav --}}
    <div class="mat-tabs-bar">
        <a href="{{ route('creator.inventories.index', array_merge(['tab' => 'materials'], request('from') ? ['from' => request('from')] : [])) }}"
            class="mat-tab-btn {{ $tab === 'materials' ? 'active' : '' }}">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path
                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                </path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            Bahan Baku
            <span class="mat-tab-badge">{{ $totalCount }}</span>
        </a>
        <a href="{{ route('creator.inventories.index', array_merge(['tab' => 'recipes'], request('from') ? ['from' => request('from')] : [])) }}"
            class="mat-tab-btn {{ $tab === 'recipes' ? 'active' : '' }}">
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
        <a href="{{ route('creator.inventories.index', array_merge(['tab' => 'mutations'], request('from') ? ['from' => request('from')] : [])) }}"
            class="mat-tab-btn {{ $tab === 'mutations' ? 'active' : '' }}">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
            Riwayat Mutasi
        </a>
    </div>

    {{-- Alert Success / Errors --}}
    @if(session('success'))
        <div
            style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:0.85rem 1.15rem; border-radius:12px; margin-bottom:1.25rem; font-size:0.82rem; font-weight:600; display:flex; align-items:center; gap:8px;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div
            style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:0.85rem 1.15rem; border-radius:12px; margin-bottom:1.25rem; font-size:0.82rem; font-weight:600; display:flex; align-items:center; gap:8px;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div
            style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:0.85rem 1.15rem; border-radius:12px; margin-bottom:1.25rem; font-size:0.82rem; font-weight:600;">
            <div style="font-weight:700; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <span>Mohon periksa data input:</span>
            </div>
            <ul style="margin:0; padding-left:1.5rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
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

        {{-- Mobile Quick Action Buttons --}}
        <div class="mat-mobile-quick-actions">
            <button type="button" onclick="openAddMaterialModal()" class="mat-quick-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                <span>+ Tambah Bahan</span>
            </button>
            <button type="button" onclick="openRestockModal()" class="mat-quick-btn-secondary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
                <span>Restock Masuk</span>
            </button>
        </div>

        @if($materials->isEmpty())
            <div
                style="text-align:center; padding:3.5rem 1.5rem; background:#ffffff; border:1px dashed #cbd5e1; border-radius:20px;">
                <div
                    style="width:64px; height:64px; border-radius:50%; background:#f0fdf4; color:#1eb349; display:inline-flex; align-items:center; justify-content:center; margin-bottom:1rem;">
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                        </path>
                    </svg>
                </div>
                <h3 style="font-size:1.05rem; font-weight:700; color:#0f172a; margin-bottom:0.35rem;">Belum Ada Bahan Baku</h3>
                <p style="font-size:0.82rem; color:#64748b; max-width:400px; margin:0 auto 1.25rem;">
                    Daftarkan bahan baku fisik untuk cafe/resto Anda (misal biji kopi, susu, cup, bumbu) agar stok terpantau
                    otomatis saat transaksi POS.
                </p>
                <button type="button" onclick="openAddMaterialModal()" class="prod-add-btn" style="margin:0 auto;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
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
                                    <span
                                        style="background:#f1f5f9; color:#475569; padding:3px 8px; border-radius:6px; font-size:0.72rem; font-weight:600; text-transform:capitalize;">
                                        {{ str_replace('_', ' ', $m->category) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight:800; font-size:0.95rem; color:#0f172a;">
                                        {{ number_format($m->stock, 2, ',', '.') }}
                                        <span style="font-size:0.75rem; font-weight:600; color:#64748b;">{{ $m->unit }}</span>
                                    </div>
                                    @if($m->conversion_qty > 1 && $m->purchase_unit)
                                        <div style="font-size:0.7rem; color:#16a34a; font-weight:600; margin-top:2px;">
                                            ≈ {{ number_format($m->stock / $m->conversion_qty, 1, ',', '.') }} {{ $m->purchase_unit }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span style="color:#64748b; font-size:0.8rem;">
                                        {{ (float) $m->min_stock > 0 ? number_format($m->min_stock, 2, ',', '.') . ' ' . $m->unit : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span style="color:#475569; font-weight:700;">
                                        Rp {{ number_format($m->cost_per_unit, 0, ',', '.') }}/{{ $m->unit }}
                                    </span>
                                    @if($m->conversion_qty > 1 && $m->purchase_cost > 0)
                                        <div style="font-size:0.68rem; color:#94a3b8; margin-top:1px;">
                                            Rp {{ number_format($m->purchase_cost, 0, ',', '.') }}/{{ $m->purchase_unit }} (isi
                                            {{ (float) $m->conversion_qty }})
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        style="background:{{ $st['bg'] }}; color:{{ $st['color'] }}; border:1px solid {{ $st['border'] }}; padding:3px 9px; border-radius:999px; font-size:0.72rem; font-weight:700;">
                                        {{ $st['label'] }}
                                    </span>
                                </td>
                                <td style="text-align:right; white-space:nowrap;">
                                    <button type="button"
                                        onclick="openRestockSingle({{ $m->id }}, '{{ addslashes($m->name) }}', '{{ $m->unit }}', {{ $m->cost_per_unit }})"
                                        style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:5px 9px; border-radius:8px; font-size:0.75rem; font-weight:700; cursor:pointer; margin-right:4px;">
                                        + Restock
                                    </button>
                                    <button type="button" onclick="openEditMaterialModal({{ json_encode($m) }})"
                                        style="background:#f8fafc; border:1px solid #e2e8f0; color:#475569; padding:5px 8px; border-radius:8px; font-size:0.75rem; cursor:pointer; margin-right:4px;"
                                        title="Edit">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2"
                                            viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('creator.inventories.destroy', $m->id) }}" method="POST"
                                        style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus bahan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="background:#fef2f2; border:1px solid #fca5a5; color:#dc2626; padding:5px 8px; border-radius:8px; font-size:0.75rem; cursor:pointer;"
                                            title="Hapus">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2"
                                                viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                <line x1="14" y1="11" x2="14" y2="17" />
                                            </svg>
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
                                    <span
                                        style="background:#f1f5f9; color:#475569; padding:2px 7px; border-radius:5px; font-size:0.7rem; font-weight:600; text-transform:capitalize;">
                                        {{ str_replace('_', ' ', $m->category) }}
                                    </span>
                                </div>
                                <span
                                    style="background:{{ $st['bg'] }}; color:{{ $st['color'] }}; border:1px solid {{ $st['border'] }}; padding:2px 8px; border-radius:999px; font-size:0.7rem; font-weight:700;">
                                    {{ $st['label'] }}
                                </span>
                            </div>

                            <div style="background:#f8fafc; border-radius:12px; padding:0.75rem; margin-bottom:0.85rem;">
                                <div style="font-size:0.72rem; color:#64748b; margin-bottom:2px;">Sisa Stok Fisik</div>
                                <div style="font-size:1.25rem; font-weight:800; color:#0f172a;">
                                    {{ number_format($m->stock, 2, ',', '.') }}
                                    <span style="font-size:0.8rem; font-weight:600; color:#64748b;">{{ $m->unit }}</span>
                                    @if($m->conversion_qty > 1 && $m->purchase_unit)
                                        <span
                                            style="font-size:0.75rem; font-weight:700; color:#16a34a; margin-left:6px; background:#f0fdf4; border:1px solid #bbf7d0; padding:1px 6px; border-radius:6px;">
                                            ≈ {{ number_format($m->stock / $m->conversion_qty, 1, ',', '.') }} {{ $m->purchase_unit }}
                                        </span>
                                    @endif
                                </div>
                                <div style="font-size:0.72rem; color:#64748b; margin-top:4px;">
                                    Estimasi modal: <strong style="color:#0f172a;">Rp
                                        {{ number_format($m->cost_per_unit, 0, ',', '.') }}/{{ $m->unit }}</strong>
                                    @if($m->conversion_qty > 1 && $m->purchase_cost > 0)
                                        <div style="font-size:0.68rem; color:#64748b; margin-top:2px;">
                                            Kulakan: <strong>Rp
                                                {{ number_format($m->purchase_cost, 0, ',', '.') }}</strong>/{{ $m->purchase_unit }} (isi
                                            {{ (float) $m->conversion_qty }} {{ $m->unit }})
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; gap:0.4rem; padding-top:0.5rem; border-top:1px solid #f1f5f9;">
                            <button type="button"
                                onclick="openRestockSingle({{ $m->id }}, '{{ addslashes($m->name) }}', '{{ $m->unit }}', {{ $m->cost_per_unit }})"
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
        <div
            style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:0.85rem 1.15rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:10px;">
            <div
                style="width:34px; height:34px; border-radius:50%; background:#ecfdf5; color:#1eb349; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="16" x2="12" y2="12" />
                    <line x1="12" y1="8" x2="12.01" y2="8" />
                </svg>
            </div>
            <div style="font-size:0.78rem; color:#475569; line-height:1.45;">
                Menampilkan daftar menu makanan &amp; minuman. Saat menu terjual di Kasir POS, stok bahan baku akan
                <strong>otomatis berkurang</strong> sesuai formula resep.
            </div>
        </div>

        @if($products->isEmpty())
            <div style="text-align:center; padding:3rem; background:#fff; border-radius:16px; border:1px dashed #cbd5e1;">
                <p style="color:#64748b; font-size:0.85rem;">Belum ada produk bertipe makanan / minuman yang terdaftar.</p>
                <a href="{{ route('creator.products.create') }}" class="prod-add-btn"
                    style="margin:1rem auto 0; display:inline-flex;">+ Tambah Menu Makanan</a>
            </div>
        @else
            {{-- VIEW GRID (Card Style 100% Mirip /creator/products) --}}
            <div id="bomViewGrid" class="view-container">
                @foreach($products as $p)
                    @php
                        $sellingPrice = (float) ($p->sale_price ?: $p->price);
                        $totalHpp = $p->hpp;
                        $profitMargin = $p->profit_margin;
                        $marginPercent = $p->margin_percentage;
                        $hasRecipe = !$p->recipes->isEmpty();
                    @endphp
                    <div id="bom-card-{{ $p->id }}" class="grid-card-item">
                        {{-- Status Badge Resep Top Right --}}
                        <div style="position:absolute;top:7px;right:7px;z-index:3;">
                            @if(!$hasRecipe)
                                <span class="grid-status-btn archived" title="Formula resep belum diatur">Tanpa Resep</span>
                            @else
                                <span class="grid-status-btn active"
                                    title="{{ $p->recipes->count() }} Bahan Baku Terkait">{{ $p->recipes->count() }} Bahan</span>
                            @endif
                        </div>

                        {{-- Kategori Top Left --}}
                        <div style="position:absolute;top:7px;left:7px;z-index:3;">
                            <span
                                style="background:rgba(255,255,255,0.92);backdrop-filter:blur(4px);color:#475569;font-size:0.62rem;font-weight:600;padding:2px 6px;border-radius:6px;border:1px solid #e2e8f0;">
                                {{ $p->category?->name ?? 'F&B' }}
                            </span>
                        </div>

                        {{-- Square Image --}}
                        <div
                            style="position:relative;width:100%;aspect-ratio:1/1;overflow:hidden;border-bottom:1px solid #F1F5F9;background:#f8fafc;display:flex;align-items:center;justify-content:center;">
                            <img src="{{ $p->image_url }}" alt="{{ $p->name }}"
                                style="width:100%;height:100%;object-fit:cover;display:block;"
                                onerror="this.onerror=null;this.src='{{ \App\Models\Product::getPlaceholderUrl() }}';this.style.objectFit='contain';this.style.padding='1.25rem';">
                        </div>

                        {{-- Card Content --}}
                        <div style="padding:0.65rem;flex:1;display:flex;flex-direction:column;">
                            <div style="font-size:0.75rem;font-weight:600;color:#1E293B;margin-bottom:0.35rem;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2em;"
                                title="{{ $p->name }}">
                                {{ $p->name }}
                            </div>

                            {{-- Financial Box: Harga Jual, Total HPP, Margin --}}
                            <div
                                style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:6px 8px; margin-bottom:0.55rem; font-size:0.72rem;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:3px;">
                                    <span style="color:#64748b; font-size:0.68rem; font-weight:600;">Harga Jual:</span>
                                    <span style="font-weight:700; color:#0f172a;">Rp
                                        {{ number_format($sellingPrice, 0, ',', '.') }}</span>
                                </div>
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:3px;">
                                    <span style="color:#64748b; font-size:0.68rem; font-weight:600;">Total HPP:</span>
                                    @if($hasRecipe)
                                        <span style="font-weight:700; color:#b45309;">Rp {{ number_format($totalHpp, 0, ',', '.') }}</span>
                                    @else
                                        <span style="color:#94a3b8; font-style:italic; font-size:0.65rem;">Rp 0 (Belum ada)</span>
                                    @endif
                                </div>
                                <div
                                    style="display:flex; justify-content:space-between; align-items:center; padding-top:3px; border-top:1px dashed #cbd5e1;">
                                    <span style="color:#475569; font-size:0.68rem; font-weight:700;">Margin Laba:</span>
                                    @if($hasRecipe)
                                        @if($profitMargin > 0)
                                            <span style="font-weight:800; color:#16a34a; display:inline-flex; align-items:center; gap:4px;">
                                                <span>+Rp {{ number_format($profitMargin, 0, ',', '.') }}</span>
                                                <span
                                                    style="background:#dcfce7; color:#166534; font-size:0.62rem; font-weight:700; padding:1px 4px; border-radius:4px;">{{ $marginPercent }}%</span>
                                            </span>
                                        @elseif($profitMargin < 0)
                                            <span style="font-weight:800; color:#dc2626; display:inline-flex; align-items:center; gap:4px;">
                                                <span>-Rp {{ number_format(abs($profitMargin), 0, ',', '.') }}</span>
                                                <span
                                                    style="background:#fee2e2; color:#991b1b; font-size:0.62rem; font-weight:700; padding:1px 4px; border-radius:4px;">Rugi</span>
                                            </span>
                                        @else
                                            <span style="font-weight:700; color:#64748b;">Rp 0 (0%)</span>
                                        @endif
                                    @else
                                        <span style="color:#94a3b8; font-size:0.68rem;">-</span>
                                    @endif
                                </div>
                            </div>

                            <div style="margin-top:auto;padding-top:0.45rem;border-top:1px solid #F1F5F9;">
                                @if(!$hasRecipe)
                                    <div
                                        style="font-size:0.65rem;color:#94a3b8;font-style:italic;margin-bottom:0.45rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        Formula resep belum diatur
                                    </div>
                                @else
                                    <div style="display:flex;flex-wrap:wrap;gap:3px;margin-bottom:0.45rem;max-height:38px;overflow:hidden;">
                                        @foreach($p->recipes->take(2) as $rc)
                                            <span
                                                style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;font-size:0.62rem;font-weight:500;padding:1px 5px;border-radius:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;">
                                                {{ $rc->material?->name ?? 'Bahan' }}: {{ (float) $rc->quantity }} {{ $rc->material?->unit }}
                                            </span>
                                        @endforeach
                                        @if($p->recipes->count() > 2)
                                            <span
                                                style="font-size:0.6rem;color:#64748b;align-self:center;">+{{ $p->recipes->count() - 2 }}</span>
                                        @endif
                                    </div>
                                @endif

                                <button type="button"
                                    onclick="openRecipeModal({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $sellingPrice }}, {{ json_encode($p->recipes) }})"
                                    style="width:100%;background:var(--pos-grad);border:none;color:#ffffff;padding:6px 8px;border-radius:8px;font-size:0.72rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;box-shadow:var(--pos-shadow);font-family:inherit;transition:all 0.15s ease;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2"
                                        viewBox="0 0 24 24">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                    </svg>
                                    <span>Atur Resep</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- VIEW TABLE (List View Alternatif) --}}
            <div id="bomViewList" class="mat-table-wrap" style="display:none;">
                <table class="mat-table" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="col-recipe-product">Menu Jual</th>
                            <th class="col-recipe-cat">Kategori</th>
                            <th class="col-recipe-price">Harga Jual</th>
                            <th class="col-recipe-hpp">Total HPP</th>
                            <th class="col-recipe-margin">Margin Laba</th>
                            <th class="col-recipe-bom">Bahan Dalam Resep (BOM)</th>
                            <th class="col-recipe-action" style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $p)
                            @php
                                $sellingPrice = (float) ($p->sale_price ?: $p->price);
                                $totalHpp = $p->hpp;
                                $profitMargin = $p->profit_margin;
                                $marginPercent = $p->margin_percentage;
                                $hasRecipe = !$p->recipes->isEmpty();
                            @endphp
                            <tr>
                                <td class="col-recipe-product">
                                    <div style="display:flex; align-items:center; gap:9px;">
                                        <img src="{{ $p->image_url }}" alt="{{ $p->name }}"
                                            style="width:40px; height:40px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; flex-shrink:0; background:#f8fafc;"
                                            onerror="this.onerror=null;this.src='{{ \App\Models\Product::getPlaceholderUrl() }}';this.style.objectFit='contain';this.style.padding='4px';">
                                        <div style="min-width:0;">
                                            <div
                                                style="font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px;">
                                                {{ $p->name }}
                                            </div>
                                            <div style="font-size:0.7rem; color:#64748b;">
                                                <span style="color:#16a34a; font-weight:700;">{{ $p->recipes->count() }} Bahan</span>
                                                tertaut
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-recipe-cat">
                                    <span
                                        style="background:#f1f5f9; color:#475569; padding:2px 7px; border-radius:6px; font-size:0.72rem; font-weight:600;">
                                        {{ $p->category?->name ?? 'F&B' }}
                                    </span>
                                </td>
                                <td class="col-recipe-price">
                                    <span style="font-weight:700; color:#0f172a;">
                                        Rp {{ number_format($sellingPrice, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="col-recipe-hpp">
                                    @if($hasRecipe)
                                        <span style="font-weight:700; color:#b45309;">
                                            Rp {{ number_format($totalHpp, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span style="color:#94a3b8; font-style:italic; font-size:0.72rem;">Rp 0</span>
                                    @endif
                                </td>
                                <td class="col-recipe-margin">
                                    @if($hasRecipe)
                                        @if($profitMargin > 0)
                                            <div style="display:flex; align-items:center; gap:5px;">
                                                <span style="font-weight:800; color:#16a34a; font-size:0.8rem;">+Rp
                                                    {{ number_format($profitMargin, 0, ',', '.') }}</span>
                                                <span
                                                    style="background:#dcfce7; color:#166534; font-size:0.65rem; font-weight:700; padding:1px 5px; border-radius:4px;">{{ $marginPercent }}%</span>
                                            </div>
                                        @elseif($profitMargin < 0)
                                            <div style="display:flex; align-items:center; gap:5px;">
                                                <span style="font-weight:800; color:#dc2626; font-size:0.8rem;">-Rp
                                                    {{ number_format(abs($profitMargin), 0, ',', '.') }}</span>
                                                <span
                                                    style="background:#fee2e2; color:#991b1b; font-size:0.65rem; font-weight:700; padding:1px 5px; border-radius:4px;">Rugi</span>
                                            </div>
                                        @else
                                            <span style="font-weight:700; color:#64748b; font-size:0.75rem;">Rp 0 (0%)</span>
                                        @endif
                                    @else
                                        <span style="color:#94a3b8; font-size:0.75rem;">-</span>
                                    @endif
                                </td>
                                <td class="col-recipe-bom">
                                    @if(!$hasRecipe)
                                        <span style="color:#94a3b8; font-style:italic; font-size:0.75rem;">Belum ada resep</span>
                                    @else
                                        <div class="recipe-chips-container" style="display:flex; flex-wrap:wrap; gap:4px;">
                                            @foreach($p->recipes as $rc)
                                                @php $subHpp = (float) ($rc->quantity) * (float) ($rc->material?->cost_per_unit ?? 0); @endphp
                                                <span
                                                    style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; font-size:0.72rem; font-weight:600; padding:2px 6px; border-radius:6px; white-space:nowrap;"
                                                    title="HPP Bahan: Rp {{ number_format($subHpp, 0, ',', '.') }}">
                                                    {{ $rc->material?->name ?? 'Bahan' }}: {{ (float) $rc->quantity }}
                                                    {{ $rc->material?->unit }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="col-recipe-action" style="text-align:right; white-space:nowrap;">
                                    <button type="button"
                                        onclick="openRecipeModal({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $sellingPrice }}, {{ json_encode($p->recipes) }})"
                                        style="background:var(--pos-grad); border:none; color:#ffffff; padding:6px 12px; border-radius:8px; font-size:0.75rem; font-weight:700; cursor:pointer; box-shadow:0 2px 6px rgba(30,179,73,0.25); font-family:inherit; white-space:nowrap;">
                                        Atur Resep
                                    </button>
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
                            $isPlus = (float) $mut->amount > 0;
                        @endphp
                        <tr>
                            <td style="color:#64748b; font-size:0.76rem; white-space:nowrap;">
                                {{ $mut->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <strong style="color:#0f172a;">{{ $mut->material?->name ?? 'Bahan Terhapus' }}</strong>
                            </td>
                            <td>
                                <span
                                    style="background:{{ $isPlus ? '#f0fdf4' : '#fef2f2' }}; color:{{ $isPlus ? '#16a34a' : '#dc2626' }}; padding:3px 8px; border-radius:6px; font-size:0.72rem; font-weight:700;">
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
                <button type="button" onclick="closeModal('modalAddMaterial')"
                    style="background:none; border:none; color:#94a3b8; font-size:1.25rem; cursor:pointer;">&times;</button>
            </div>

            <form action="{{ route('creator.inventories.store') }}" method="POST">
                @csrf
                @if(request('from')) <input type="hidden" name="from" value="{{ request('from') }}"> @endif
                <div style="margin-bottom:0.9rem;">
                    <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Nama
                        Bahan Baku *</label>
                    <input type="text" name="name" required placeholder="Contoh: Biji Kopi Espresso Blend"
                        style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.9rem;">
                    <div>
                        <label
                            style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Kategori
                            *</label>
                        <select name="category" required
                            style="width:100%; padding:9px 10px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                            <optgroup label="Daging & Seafood">
                                <option value="daging_unggas">Daging & Unggas (Ayam, Sapi, Bebek)</option>
                                <option value="ikan_seafood">Ikan & Seafood (Lele, Nila, Udang, Cumi)</option>
                            </optgroup>
                            <optgroup label="Sayur, Bumbu & Bahan Segar">
                                <option value="sayuran_jamur">Sayuran, Cabai, Bawang & Jamur</option>
                                <option value="bumbu_saus_minyak">Bumbu Dapur, Saus, Sambal & Minyak</option>
                                <option value="telur_tahu_tempe">Telur, Tahu & Tempe</option>
                            </optgroup>
                            <optgroup label="Minuman, Kopi & Cafe">
                                <option value="biji_kopi_teh">Biji Kopi, Daun Teh & Cokelat Bubuk</option>
                                <option value="susu_dairy">Susu Segar/UHT, Krimer & Keju</option>
                                <option value="sirup_perisa">Sirup Rasa, Gula Cair & Puree</option>
                                <option value="topping_minuman">Topping Minuman (Boba, Jelly, Cincau)</option>
                            </optgroup>
                            <optgroup label="Bahan Pokok & Tepung">
                                <option value="beras_mie_pasta">Beras, Mie, Kwetiau & Pasta</option>
                                <option value="tepung_gula_kering">Tepung Terigu, Gula Pasir & Bahan Kering</option>
                                <option value="roti_pastry_bakery">Roti, Bun Burger & Kulit Pastry</option>
                            </optgroup>
                            <optgroup label="Frozen Food & Olahan">
                                <option value="frozen_food">Frozen Food (Sosis, Nugget, Bakso, Dimsum)</option>
                            </optgroup>
                            <optgroup label="Kemasan & Operasional Dapur">
                                <option value="kemasan_cup_packaging">Cup Plastik/Paper, Box, Sedotan & Kantong</option>
                                <option value="gas_arang_operasional">Gas LPG, Arang, Kertas Minyak & Tusuk Sate</option>
                            </optgroup>
                            <optgroup label="Lainnya">
                                <option value="kering">Bahan Kering Lainnya</option>
                                <option value="cair_dingin">Bahan Basah/Cair Lainnya</option>
                                <option value="lainnya">Lainnya</option>
                            </optgroup>
                        </select>
                    </div>
                    <div>
                        <label
                            style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Satuan
                            Dasar *</label>
                        <select name="unit" required
                            style="width:100%; padding:9px 10px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                            <option value="gram">gram (gr)</option>
                            <option value="kg">kilogram (kg)</option>
                            <option value="ml">mililiter (ml)</option>
                            <option value="liter">liter (L)</option>
                            <option value="pcs">pcs / buah</option>
                            <option value="ekor">ekor</option>
                            <option value="porsi">porsi</option>
                            <option value="butir">butir</option>
                            <option value="lembar">lembar</option>
                            <option value="cup">cup</option>
                            <option value="sachet">sachet / sdt</option>
                            <option value="botol">botol</option>
                            <option value="kaleng">kaleng</option>
                            <option value="ikat">ikat</option>
                            <option value="box">box / dus</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.9rem;">
                    <div>
                        <label
                            style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Stok
                            Awal</label>
                        <input type="number" step="0.01" name="stock" value="0"
                            style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                    </div>
                    <div>
                        <label
                            style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Batas
                            Peringatan (Min)</label>
                        <input type="number" step="0.01" name="min_stock" value="0" placeholder="0 = tanpa alert"
                            style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                    </div>
                </div>

                {{-- Konversi Satuan Kemasan Beli (Multi-Unit) --}}
                <div
                    style="background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px; padding:10px 12px; margin-bottom:0.9rem;">
                    <label
                        style="display:flex; align-items:center; gap:8px; font-size:0.78rem; font-weight:700; color:#166534; cursor:pointer; margin:0;">
                        <input type="checkbox" id="addHasConversion" name="has_conversion" value="1"
                            onchange="toggleConversionBlock('add', this.checked)">
                        <span>Bahan dibeli dalam kemasan/karton/dus (Konversi Satuan)</span>
                    </label>
                    <div id="addConversionBlock"
                        style="display:none; margin-top:10px; padding-top:10px; border-top:1px dashed #cbd5e1;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.75rem;">
                            <div>
                                <label
                                    style="display:block; font-size:0.73rem; font-weight:700; color:#475569; margin-bottom:3px;">Nama
                                    Satuan Kemasan *</label>
                                <select name="purchase_unit" id="addPurchaseUnit"
                                    onchange="calculateUnitCostFromConversion('add')"
                                    style="width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.8rem; font-family:inherit; box-sizing:border-box;">
                                    <option value="dus">dus / karton</option>
                                    <option value="pack">pack / bungkus</option>
                                    <option value="karung">karung / sak</option>
                                    <option value="bal">bal</option>
                                    <option value="galon">galon</option>
                                    <option value="botol">botol besar</option>
                                    <option value="kaleng">kaleng</option>
                                    <option value="jerigen">jerigen</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    style="display:block; font-size:0.73rem; font-weight:700; color:#475569; margin-bottom:3px;">Isi
                                    per Kemasan *</label>
                                <input type="number" step="0.01" name="conversion_qty" id="addConversionQty" value="1"
                                    min="1" placeholder="Misal: 24" oninput="calculateUnitCostFromConversion('add')"
                                    style="width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.8rem; font-family:inherit; box-sizing:border-box;">
                            </div>
                        </div>
                        <div style="margin-bottom:0.6rem;">
                            <label
                                style="display:block; font-size:0.73rem; font-weight:700; color:#475569; margin-bottom:3px;">Harga
                                Beli per Kemasan (Rp) *</label>
                            <div style="position:relative; display:flex; align-items:center;">
                                <span
                                    style="position:absolute; left:10px; font-size:0.78rem; font-weight:700; color:#64748b; pointer-events:none;">Rp</span>
                                <input type="text" inputmode="numeric" name="purchase_cost" id="addPurchaseCost"
                                    placeholder="0"
                                    oninput="formatRupiahInput(this); calculateUnitCostFromConversion('add');"
                                    style="width:100%; padding:8px 10px 8px 34px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.8rem; font-weight:600; font-family:inherit; box-sizing:border-box;">
                            </div>
                        </div>
                        <div id="addCalculatedCostText"
                            style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:6px 10px; font-size:0.73rem; color:#166534; font-weight:600;">
                            💡 Masukkan isi dan harga beli kemasan untuk hitung otomatis modal per unit resep.
                        </div>
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label
                        style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Estimasi
                        Harga Modal per Satuan Dasar</label>
                    <div style="position:relative; display:flex; align-items:center;">
                        <span
                            style="position:absolute; left:12px; font-size:0.82rem; font-weight:700; color:#64748b; pointer-events:none;">Rp</span>
                        <input type="text" inputmode="numeric" id="addCostPerUnit" name="cost_per_unit" value="0"
                            placeholder="0" oninput="formatRupiahInput(this)"
                            style="width:100%; padding:9px 12px 9px 38px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-weight:600; font-family:inherit; box-sizing:border-box;">
                    </div>
                    <small style="color:#64748b; font-size:0.72rem; margin-top:3px; display:block;">Digunakan untuk
                        menghitung HPP (Harga Pokok Penjualan) per porsi/menu.</small>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                    <button type="button" onclick="closeModal('modalAddMaterial')"
                        style="padding:9px 16px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#475569; font-size:0.82rem; font-weight:600; cursor:pointer;">Batal</button>
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
                <button type="button" onclick="closeModal('modalEditMaterial')"
                    style="background:none; border:none; color:#94a3b8; font-size:1.25rem; cursor:pointer;">&times;</button>
            </div>

            <form id="formEditMaterial" method="POST">
                @csrf
                @method('PUT')
                @if(request('from')) <input type="hidden" name="from" value="{{ request('from') }}"> @endif
                <div style="margin-bottom:0.9rem;">
                    <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Nama
                        Bahan Baku *</label>
                    <input type="text" id="editMatName" name="name" required
                        style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.9rem;">
                    <div>
                        <label
                            style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Kategori
                            *</label>
                        <select id="editMatCategory" name="category" required
                            style="width:100%; padding:9px 10px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                            <optgroup label="Daging & Seafood">
                                <option value="daging_unggas">Daging & Unggas (Ayam, Sapi, Bebek)</option>
                                <option value="ikan_seafood">Ikan & Seafood (Lele, Nila, Udang, Cumi)</option>
                            </optgroup>
                            <optgroup label="Sayur, Bumbu & Bahan Segar">
                                <option value="sayuran_jamur">Sayuran, Cabai, Bawang & Jamur</option>
                                <option value="bumbu_saus_minyak">Bumbu Dapur, Saus, Sambal & Minyak</option>
                                <option value="telur_tahu_tempe">Telur, Tahu & Tempe</option>
                            </optgroup>
                            <optgroup label="Minuman, Kopi & Cafe">
                                <option value="biji_kopi_teh">Biji Kopi, Daun Teh & Cokelat Bubuk</option>
                                <option value="susu_dairy">Susu Segar/UHT, Krimer & Keju</option>
                                <option value="sirup_perisa">Sirup Rasa, Gula Cair & Puree</option>
                                <option value="topping_minuman">Topping Minuman (Boba, Jelly, Cincau)</option>
                            </optgroup>
                            <optgroup label="Bahan Pokok & Tepung">
                                <option value="beras_mie_pasta">Beras, Mie, Kwetiau & Pasta</option>
                                <option value="tepung_gula_kering">Tepung Terigu, Gula Pasir & Bahan Kering</option>
                                <option value="roti_pastry_bakery">Roti, Bun Burger & Kulit Pastry</option>
                            </optgroup>
                            <optgroup label="Frozen Food & Olahan">
                                <option value="frozen_food">Frozen Food (Sosis, Nugget, Bakso, Dimsum)</option>
                            </optgroup>
                            <optgroup label="Kemasan & Operasional Dapur">
                                <option value="kemasan_cup_packaging">Cup Plastik/Paper, Box, Sedotan & Kantong</option>
                                <option value="gas_arang_operasional">Gas LPG, Arang, Kertas Minyak & Tusuk Sate</option>
                            </optgroup>
                            <optgroup label="Lainnya">
                                <option value="kering">Bahan Kering Lainnya</option>
                                <option value="cair_dingin">Bahan Basah/Cair Lainnya</option>
                                <option value="lainnya">Lainnya</option>
                            </optgroup>
                        </select>
                    </div>
                    <div>
                        <label
                            style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Satuan
                            Dasar *</label>
                        <select id="editMatUnit" name="unit" required
                            style="width:100%; padding:9px 10px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                            <option value="gram">gram (gr)</option>
                            <option value="kg">kilogram (kg)</option>
                            <option value="ml">mililiter (ml)</option>
                            <option value="liter">liter (L)</option>
                            <option value="pcs">pcs / buah</option>
                            <option value="ekor">ekor</option>
                            <option value="porsi">porsi</option>
                            <option value="butir">butir</option>
                            <option value="lembar">lembar</option>
                            <option value="cup">cup</option>
                            <option value="sachet">sachet / sdt</option>
                            <option value="botol">botol</option>
                            <option value="kaleng">kaleng</option>
                            <option value="ikat">ikat</option>
                            <option value="box">box / dus</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.9rem;">
                    <div>
                        <label
                            style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Batas
                            Peringatan (Min)</label>
                        <input type="number" step="0.01" id="editMatMinStock" name="min_stock"
                            style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                    </div>
                    <div>
                        <label
                            style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Harga
                            Modal per Satuan Dasar</label>
                        <div style="position:relative; display:flex; align-items:center;">
                            <span
                                style="position:absolute; left:12px; font-size:0.82rem; font-weight:700; color:#64748b; pointer-events:none;">Rp</span>
                            <input type="text" inputmode="numeric" id="editMatCost" name="cost_per_unit"
                                oninput="formatRupiahInput(this)"
                                style="width:100%; padding:9px 12px 9px 38px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-weight:600; font-family:inherit; box-sizing:border-box;">
                        </div>
                    </div>
                </div>

                {{-- Konversi Satuan Kemasan Beli (Multi-Unit Edit) --}}
                <div
                    style="background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px; padding:10px 12px; margin-bottom:1.25rem;">
                    <label
                        style="display:flex; align-items:center; gap:8px; font-size:0.78rem; font-weight:700; color:#166534; cursor:pointer; margin:0;">
                        <input type="checkbox" id="editHasConversion" name="has_conversion" value="1"
                            onchange="toggleConversionBlock('edit', this.checked)">
                        <span>Bahan dibeli dalam kemasan/karton/dus (Konversi Satuan)</span>
                    </label>
                    <div id="editConversionBlock"
                        style="display:none; margin-top:10px; padding-top:10px; border-top:1px dashed #cbd5e1;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.75rem;">
                            <div>
                                <label
                                    style="display:block; font-size:0.73rem; font-weight:700; color:#475569; margin-bottom:3px;">Nama
                                    Satuan Kemasan *</label>
                                <select name="purchase_unit" id="editPurchaseUnit"
                                    onchange="calculateUnitCostFromConversion('edit')"
                                    style="width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.8rem; font-family:inherit; box-sizing:border-box;">
                                    <option value="dus">dus / karton</option>
                                    <option value="pack">pack / bungkus</option>
                                    <option value="karung">karung / sak</option>
                                    <option value="bal">bal</option>
                                    <option value="galon">galon</option>
                                    <option value="botol">botol besar</option>
                                    <option value="kaleng">kaleng</option>
                                    <option value="jerigen">jerigen</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    style="display:block; font-size:0.73rem; font-weight:700; color:#475569; margin-bottom:3px;">Isi
                                    per Kemasan *</label>
                                <input type="number" step="0.01" name="conversion_qty" id="editConversionQty" value="1"
                                    min="1" placeholder="Misal: 24" oninput="calculateUnitCostFromConversion('edit')"
                                    style="width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.8rem; font-family:inherit; box-sizing:border-box;">
                            </div>
                        </div>
                        <div style="margin-bottom:0.6rem;">
                            <label
                                style="display:block; font-size:0.73rem; font-weight:700; color:#475569; margin-bottom:3px;">Harga
                                Beli per Kemasan (Rp) *</label>
                            <div style="position:relative; display:flex; align-items:center;">
                                <span
                                    style="position:absolute; left:10px; font-size:0.78rem; font-weight:700; color:#64748b; pointer-events:none;">Rp</span>
                                <input type="text" inputmode="numeric" name="purchase_cost" id="editPurchaseCost"
                                    placeholder="0"
                                    oninput="formatRupiahInput(this); calculateUnitCostFromConversion('edit');"
                                    style="width:100%; padding:8px 10px 8px 34px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.8rem; font-weight:600; font-family:inherit; box-sizing:border-box;">
                            </div>
                        </div>
                        <div id="editCalculatedCostText"
                            style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:6px 10px; font-size:0.73rem; color:#166534; font-weight:600;">
                            💡 Masukkan isi dan harga beli kemasan untuk hitung otomatis modal per unit resep.
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                    <button type="button" onclick="closeModal('modalEditMaterial')"
                        style="padding:9px 16px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#475569; font-size:0.82rem; font-weight:600; cursor:pointer;">Batal</button>
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
                <button type="button" onclick="closeModal('modalRestock')"
                    style="background:none; border:none; color:#94a3b8; font-size:1.25rem; cursor:pointer;">&times;</button>
            </div>

            <form id="formRestock" method="POST">
                @csrf
                @if(request('from')) <input type="hidden" name="from" value="{{ request('from') }}"> @endif
                <div style="margin-bottom:0.9rem;">
                    <label
                        style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Pilih
                        Bahan Baku *</label>
                    <select id="restockMatSelect" onchange="updateRestockUnit()" required
                        style="width:100%; padding:9px 10px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                        @foreach($allMats as $m)
                            <option value="{{ $m->id }}" data-unit="{{ $m->unit }}" data-cost="{{ $m->cost_per_unit }}"
                                data-purchase-unit="{{ $m->purchase_unit }}" data-conv-qty="{{ (float) $m->conversion_qty }}"
                                data-purchase-cost="{{ $m->purchase_cost }}">
                                {{ $m->name }} (Sisa: {{ (float) $m->stock }}
                                {{ $m->unit }}@if($m->conversion_qty > 1 && $m->purchase_unit) / ≈
                                {{ number_format($m->stock / $m->conversion_qty, 1, ',', '.') }} {{ $m->purchase_unit }}@endif)
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mode Pilihan Satuan Restock (Jika Bahan Memiliki Konversi Satuan) --}}
                <div id="restockModeWrap"
                    style="display:none; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:8px 12px; margin-bottom:0.9rem;">
                    <label
                        style="display:block; font-size:0.73rem; font-weight:700; color:#166534; margin-bottom:4px;">Pilihan
                        Satuan Restock:</label>
                    <div style="display:flex; gap:12px; font-size:0.78rem; font-weight:600; color:#0f172a;">
                        <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                            <input type="radio" name="restock_mode" id="restockModePack" value="pack" checked
                                onchange="onRestockModeChange()">
                            <span id="restockModePackLabel">Kemasan Kulakan</span>
                        </label>
                        <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                            <input type="radio" name="restock_mode" id="restockModeUnit" value="unit"
                                onchange="onRestockModeChange()">
                            <span id="restockModeUnitLabel">Satuan Resep</span>
                        </label>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.9rem;">
                    <div>
                        <label
                            style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Jumlah
                            Masuk *</label>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <input type="number" step="0.01" name="amount" required placeholder="0"
                                style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                            <span id="restockUnitLabel"
                                style="font-size:0.82rem; font-weight:700; color:#64748b; white-space:nowrap;">gram</span>
                        </div>
                    </div>
                    <div>
                        <label
                            style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Harga
                            Beli per Satuan</label>
                        <div style="position:relative; display:flex; align-items:center;">
                            <span
                                style="position:absolute; left:12px; font-size:0.82rem; font-weight:700; color:#64748b; pointer-events:none;">Rp</span>
                            <input type="text" inputmode="numeric" name="cost_per_unit" id="restockCostInput"
                                placeholder="0" oninput="formatRupiahInput(this)"
                                style="width:100%; padding:9px 12px 9px 38px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-weight:600; font-family:inherit; box-sizing:border-box;">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label
                        style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Catatan
                        / No. Nota Supplier</label>
                    <input type="text" name="notes" placeholder="Contoh: Beli dari Toko Grosir Kopi Jaya"
                        style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-family:inherit; box-sizing:border-box;">
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                    <button type="button" onclick="closeModal('modalRestock')"
                        style="padding:9px 16px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#475569; font-size:0.82rem; font-weight:600; cursor:pointer;">Batal</button>
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
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                <div>
                    <h3 style="font-size:0.95rem; font-weight:700; color:#0f172a; margin:0;">Formula Resep Menu (BOM)</h3>
                    <p id="recipeProductName" style="font-size:0.75rem; color:#64748b; margin:2px 0 0;"></p>
                </div>
                <button type="button" onclick="closeModal('modalRecipe')"
                    style="background:none; border:none; color:#94a3b8; font-size:1.25rem; cursor:pointer;">&times;</button>
            </div>

            {{-- Live Recipe Cost & Margin Bar --}}
            <div
                style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.65rem 0.85rem; margin-bottom:0.85rem; display:grid; grid-template-columns:1fr 1fr 1.2fr; gap:0.5rem; text-align:center;">
                <div>
                    <div style="font-size:0.65rem; color:#64748b; font-weight:600;">Harga Jual</div>
                    <div id="recipeSellingPriceLabel" style="font-size:0.82rem; font-weight:700; color:#0f172a;">Rp 0</div>
                </div>
                <div>
                    <div style="font-size:0.65rem; color:#64748b; font-weight:600;">Total HPP Resep</div>
                    <div id="recipeTotalHppLabel" style="font-size:0.82rem; font-weight:800; color:#d97706;">Rp 0</div>
                </div>
                <div>
                    <div style="font-size:0.65rem; color:#64748b; font-weight:600;">Estimasi Margin Laba</div>
                    <div id="recipeMarginLabel" style="font-size:0.82rem; font-weight:800; color:#16a34a;">Rp 0 (0%)</div>
                </div>
            </div>

            <form id="formRecipe" method="POST">
                @csrf
                @if(request('from')) <input type="hidden" name="from" value="{{ request('from') }}"> @endif
                <div id="recipeRowsContainer"
                    style="margin-bottom:0.85rem; max-height:340px; overflow-y:auto; padding-right:4px;">
                    <!-- Rows populated dynamically -->
                </div>

                <div style="margin-bottom:1.15rem;">
                    <button type="button" onclick="addRecipeRow()"
                        style="background:#f1f5f9; border:1px dashed #cbd5e1; color:#0f172a; font-size:0.75rem; font-weight:600; width:100%; padding:8px; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        <span>+ Tambah Baris Bahan Baku</span>
                    </button>
                </div>

                <div
                    style="display:flex; justify-content:flex-end; gap:0.5rem; border-top:1px solid #f1f5f9; padding-top:1rem;">
                    <button type="button" onclick="closeModal('modalRecipe')"
                        style="padding:9px 16px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#475569; font-size:0.82rem; font-weight:600; cursor:pointer;">Tutup</button>
                    <button type="submit" class="prod-add-btn">Simpan Formula Resep</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Floating Action Buttons on Mobile (Image 3: $ Restock Masuk & + Tambah Bahan) --}}
    <div class="mat-mobile-fab-wrap">
        <button type="button" onclick="openRestockModal()" class="mat-mobile-fab-btn mat-mobile-fab-secondary"
            title="Restock Masuk">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
        </button>
        <button type="button" onclick="openAddMaterialModal()" class="mat-mobile-fab-btn mat-mobile-fab-primary"
            title="Tambah Bahan Baku">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
        </button>
    </div>
@endsection

@section('scripts')
    <script>
        const allMaterialsList = @json($allMats);

        // Switch View List / Grid (Dukung Tab Bahan Baku & Tab Resep BOM)
        function switchMaterialView(view) {
            const matList = document.getElementById('matViewList');
            const matGrid = document.getElementById('matViewGrid');
            const bomGrid = document.getElementById('bomViewGrid');
            const bomList = document.getElementById('bomViewList');
            const btnList = document.getElementById('mat-btn-view-list');
            const btnGrid = document.getElementById('mat-btn-view-grid');

            if (view === 'grid') {
                if (matList) matList.style.display = 'none';
                if (matGrid) matGrid.style.display = 'grid';
                if (bomList) bomList.style.display = 'none';
                if (bomGrid) bomGrid.style.display = 'grid';
                if (btnList) btnList.classList.remove('active');
                if (btnGrid) btnGrid.classList.add('active');
            } else {
                if (matList) matList.style.display = 'block';
                if (matGrid) matGrid.style.display = 'none';
                if (bomList) bomList.style.display = 'block';
                if (bomGrid) bomGrid.style.display = 'none';
                if (btnList) btnList.classList.add('active');
                if (btnGrid) btnGrid.classList.remove('active');
            }
            try { localStorage.setItem('buyle_inventories_view', view); } catch (e) { }
        }

        // Default ke Grid View pada Mobile atau Tab Resep
        document.addEventListener('DOMContentLoaded', function () {
            const isMobileInv = window.innerWidth <= 768;
            const currentTab = @json($tab);
            let savedInvView = null;
            try { savedInvView = localStorage.getItem('buyle_inventories_view'); } catch (e) { }
            const activeView = savedInvView || (isMobileInv || currentTab === 'recipes' ? 'grid' : 'list');
            switchMaterialView(activeView);
        });

        // Modal Control
        function openModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('show');
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('show');
        }

        function formatNumberWithDots(val) {
            if (val === undefined || val === null || val === '') return '';
            const numStr = val.toString().replace(/[^0-9]/g, '');
            if (!numStr) return '';
            return parseInt(numStr, 10).toLocaleString('id-ID');
        }

        function formatRupiahInput(el) {
            const cursorPosition = el.selectionStart;
            const originalLength = el.value.length;
            const raw = el.value.replace(/[^0-9]/g, '');
            if (!raw) {
                el.value = '';
                return;
            }
            const formatted = parseInt(raw, 10).toLocaleString('id-ID');
            el.value = formatted;
            const newLength = formatted.length;
            const newPos = Math.max(0, cursorPosition + (newLength - originalLength));
            try {
                el.setSelectionRange(newPos, newPos);
            } catch (e) { }
        }

        function parseRupiahNumber(val) {
            if (!val) return 0;
            const clean = val.toString().replace(/[^0-9]/g, '');
            return parseFloat(clean) || 0;
        }

        // ── Multi-Unit Conversion Helper Functions ──
        function toggleConversionBlock(prefix, isChecked) {
            const block = document.getElementById(prefix + 'ConversionBlock');
            if (block) {
                block.style.display = isChecked ? 'block' : 'none';
            }
            if (isChecked) {
                calculateUnitCostFromConversion(prefix);
            }
        }

        function calculateUnitCostFromConversion(prefix) {
            const costStr = document.getElementById(prefix + 'PurchaseCost')?.value || '0';
            const purchaseCost = parseRupiahNumber(costStr);
            const convQtyInput = document.getElementById(prefix + 'ConversionQty');
            const convQty = parseFloat(convQtyInput?.value) || 1;
            const purchaseUnit = document.getElementById(prefix + 'PurchaseUnit')?.value || 'kemasan';

            let baseUnit = 'pcs';
            if (prefix === 'add') {
                baseUnit = document.querySelector('select[name="unit"]')?.value || 'pcs';
            } else {
                baseUnit = document.getElementById('editMatUnit')?.value || 'pcs';
            }

            const costPerUnit = convQty > 0 ? (purchaseCost / convQty) : 0;

            const costInput = prefix === 'add' ? document.getElementById('addCostPerUnit') : document.getElementById('editMatCost');
            if (costInput && purchaseCost > 0) {
                costInput.value = formatNumberWithDots(Math.round(costPerUnit));
            }

            const infoBox = document.getElementById(prefix + 'CalculatedCostText');
            if (infoBox) {
                if (purchaseCost > 0 && convQty > 0) {
                    infoBox.innerHTML = `✅ 1 ${purchaseUnit} = <strong>${convQty} ${baseUnit}</strong> &bull; Biaya modal: <strong>Rp ${Math.round(costPerUnit).toLocaleString('id-ID')} / ${baseUnit}</strong>`;
                } else {
                    infoBox.innerHTML = `💡 Masukkan isi dan harga beli kemasan untuk hitung otomatis modal per ${baseUnit}.`;
                }
            }
        }

        function openAddMaterialModal() {
            const hasConv = document.getElementById('addHasConversion');
            if (hasConv) {
                hasConv.checked = false;
                toggleConversionBlock('add', false);
            }
            openModal('modalAddMaterial');
        }

        function openEditMaterialModal(mat) {
            document.getElementById('editMatName').value = mat.name;
            document.getElementById('editMatCategory').value = mat.category;
            document.getElementById('editMatUnit').value = mat.unit;
            document.getElementById('editMatMinStock').value = mat.min_stock;
            document.getElementById('editMatCost').value = formatNumberWithDots(mat.cost_per_unit);

            const hasConv = document.getElementById('editHasConversion');
            const convQty = parseFloat(mat.conversion_qty) || 1;
            if (hasConv) {
                if (convQty > 1 && mat.purchase_unit) {
                    hasConv.checked = true;
                    if (document.getElementById('editPurchaseUnit')) document.getElementById('editPurchaseUnit').value = mat.purchase_unit;
                    if (document.getElementById('editConversionQty')) document.getElementById('editConversionQty').value = convQty;
                    if (document.getElementById('editPurchaseCost')) document.getElementById('editPurchaseCost').value = formatNumberWithDots(mat.purchase_cost || 0);
                    toggleConversionBlock('edit', true);
                } else {
                    hasConv.checked = false;
                    toggleConversionBlock('edit', false);
                }
            }

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
            if (!sel || !sel.options.length) return;
            const opt = sel.options[sel.selectedIndex];
            if (!opt) return;

            const form = document.getElementById('formRestock');
            form.action = `/creator/inventories/${sel.value}/restock`;

            const baseUnit = opt.getAttribute('data-unit') || 'pcs';
            const baseCost = opt.getAttribute('data-cost') || '0';
            const purchaseUnit = opt.getAttribute('data-purchase-unit') || '';
            const convQty = parseFloat(opt.getAttribute('data-conv-qty')) || 1;
            const purchaseCost = opt.getAttribute('data-purchase-cost') || '0';

            const modeWrap = document.getElementById('restockModeWrap');
            if (convQty > 1 && purchaseUnit) {
                if (modeWrap) modeWrap.style.display = 'block';
                const packLabel = document.getElementById('restockModePackLabel');
                const unitLabel = document.getElementById('restockModeUnitLabel');
                if (packLabel) packLabel.textContent = `Kemasan Kulakan (${purchaseUnit} @ ${convQty} ${baseUnit})`;
                if (unitLabel) unitLabel.textContent = `Satuan Resep (${baseUnit})`;
                onRestockModeChange();
            } else {
                if (modeWrap) modeWrap.style.display = 'none';
                document.getElementById('restockUnitLabel').textContent = baseUnit;
                document.getElementById('restockCostInput').value = formatNumberWithDots(baseCost);
            }
    }

            function onRestockModeChange() {
                const sel = document.getElementById('restockMatSelect');
                if (!sel || !sel.options.length) return;
                const opt = sel.options[sel.selectedIndex];
                if (!opt) return;

                const baseUnit = opt.getAttribute('data-unit') || 'pcs';
                const baseCost = opt.getAttribute('data-cost') || '0';
                const purchaseUnit = opt.getAttribute('data-purchase-unit') || 'dus';
                const purchaseCost = opt.getAttribute('data-purchase-cost') || '0';

                const isPack = document.getElementById('restockModePack')?.checked;
                if (isPack) {
                    document.getElementById('restockUnitLabel').textContent = purchaseUnit;
                    document.getElementById('restockCostInput').value = formatNumberWithDots(purchaseCost);
                } else {
                    document.getElementById('restockUnitLabel').textContent = baseUnit;
                    document.getElementById('restockCostInput').value = formatNumberWithDots(baseCost);
                }
            }

            // Recipe Modal Builder
            let currentRecipeRowCount = 0;
            let currentModalSellingPrice = 0;

            function openRecipeModal(productId, productName, sellingPrice, existingRecipes) {
                currentModalSellingPrice = parseFloat(sellingPrice) || 0;
                document.getElementById('recipeProductName').textContent = `Menu: ${productName}`;
                document.getElementById('recipeSellingPriceLabel').textContent = 'Rp ' + Math.round(currentModalSellingPrice).toLocaleString('id-ID');
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

                recalculateRecipeCost();
                openModal('modalRecipe');
            }

            function addRecipeRow(selectedMatId = null, qty = '', notes = '') {
                const container = document.getElementById('recipeRowsContainer');
                const idx = currentRecipeRowCount++;

                let matOptions = '<option value="" data-cost="0" data-unit="">-- Pilih Bahan Baku --</option>';
                allMaterialsList.forEach(m => {
                    const sel = (selectedMatId && m.id == selectedMatId) ? 'selected' : '';
                    const cost = parseFloat(m.cost_per_unit || 0);
                    matOptions += `<option value="${m.id}" data-cost="${cost}" data-unit="${m.unit}" ${sel}>${m.name} (Rp ${cost.toLocaleString('id-ID')}/${m.unit})</option>`;
                });

                const rowHtml = `
                                                    <div id="recipe-row-${idx}" style="background:#f8fafc; padding:8px 10px; border-radius:10px; border:1px solid #e2e8f0; margin-bottom:8px;">
                                                        <div style="display:flex; gap:6px; align-items:center;">
                                                            <select name="recipes[${idx}][material_id]" onchange="recalculateRecipeCost()" required style="flex:2.3; min-width:0; padding:7px 9px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.75rem; font-weight:600; color:#0f172a; background:#ffffff; font-family:inherit;">
                                                                ${matOptions}
                                                            </select>
                                                            <div style="flex:1; min-width:0;">
                                                                <input type="number" step="0.01" name="recipes[${idx}][quantity]" value="${qty}" oninput="recalculateRecipeCost()" required placeholder="Takaran" style="width:100%; padding:7px 9px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.75rem; font-weight:600; color:#0f172a; background:#ffffff; font-family:inherit; box-sizing:border-box;">
                                                            </div>
                                                            <button type="button" onclick="removeRecipeRow(${idx})" style="background:#fee2e2; border:none; color:#dc2626; width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1rem; flex-shrink:0;" title="Hapus Baris">
                                                                &times;
                                                            </button>
                                                        </div>
                                                        <div class="recipe-row-subcost" style="font-size:0.68rem; color:#64748b; font-weight:600; margin-top:4px; min-height:1em;"></div>
                                                    </div>
                                                `;

                container.insertAdjacentHTML('beforeend', rowHtml);
                recalculateRecipeCost();
            }

            function removeRecipeRow(idx) {
                const row = document.getElementById(`recipe-row-${idx}`);
                if (row) {
                    row.remove();
                    recalculateRecipeCost();
                }
            }

            function recalculateRecipeCost() {
                let totalHpp = 0;
                const rows = document.querySelectorAll('#recipeRowsContainer > div');
                rows.forEach(row => {
                    const sel = row.querySelector('select');
                    const input = row.querySelector('input[type="number"]');
                    const subcostEl = row.querySelector('.recipe-row-subcost');
                    if (sel && input) {
                        const opt = sel.options[sel.selectedIndex];
                        const cost = opt ? parseFloat(opt.getAttribute('data-cost') || 0) : 0;
                        const unit = opt ? (opt.getAttribute('data-unit') || '') : '';
                        const qty = parseFloat(input.value) || 0;
                        const subtotal = cost * qty;
                        totalHpp += subtotal;
                        if (subcostEl) {
                            if (sel.value && qty > 0) {
                                subcostEl.textContent = `Estimasi biaya: ${qty} ${unit} x Rp ${cost.toLocaleString('id-ID')} = Rp ${Math.round(subtotal).toLocaleString('id-ID')}`;
                            } else {
                                subcostEl.textContent = '';
                            }
                        }
                    }
                });

                const hppLabel = document.getElementById('recipeTotalHppLabel');
                if (hppLabel) {
                    hppLabel.textContent = 'Rp ' + Math.round(totalHpp).toLocaleString('id-ID');
                }

                const marginLabel = document.getElementById('recipeMarginLabel');
                if (marginLabel) {
                    const margin = currentModalSellingPrice - totalHpp;
                    const marginPercent = currentModalSellingPrice > 0 ? ((margin / currentModalSellingPrice) * 100).toFixed(1) : 0;
                    if (totalHpp > 0 || currentModalSellingPrice > 0) {
                        if (margin >= 0) {
                            marginLabel.style.color = '#16a34a';
                            marginLabel.textContent = `+Rp ${Math.round(margin).toLocaleString('id-ID')} (${marginPercent}%)`;
                        } else {
                            marginLabel.style.color = '#dc2626';
                            marginLabel.textContent = `-Rp ${Math.round(Math.abs(margin)).toLocaleString('id-ID')} (Rugi)`;
                        }
                    } else {
                        marginLabel.textContent = 'Rp 0 (0%)';
                    }
                }
            }

            function handleInventoryMobileBack() {
                try {
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.get('from') === 'pos' || document.referrer.includes('/pos')) {
                        window.location.href = "{{ route('creator.pos.index') }}";
                        return;
                    }
                } catch (e) { }
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    window.location.href = "{{ route('creator.pos.index') }}";
                }
            }
    </script>
@endsection