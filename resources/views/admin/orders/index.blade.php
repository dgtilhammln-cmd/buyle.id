@extends('layouts.admin')
@section('title', 'Pesanan Saya')
@section('page-title', 'Manajemen Pesanan')
@section('content')

    <style>
        /* ── Premium Orders Page ── */
        .opage {
            font-family: 'Montserrat', sans-serif;
        }

        /* Page Header */
        .opage-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
        }

        .opage-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -.03em;
            margin: 0 0 .2rem;
        }

        .opage-sub {
            font-size: .8rem;
            color: #94A3B8;
            margin: 0;
            font-weight: 500;
        }

        .opage-sub strong {
            color: #EF4444;
        }

        /* Tabs Header */
        .o-tabs-wrap {
            display: flex;
            gap: .75rem;
            overflow-x: auto;
            margin-bottom: 1.75rem;
            padding: .25rem 0 .5rem 0;
            /* Fixed cut-off */
        }

        .o-tab {
            padding: .6rem 1.25rem;
            font-size: .8rem;
            font-weight: 700;
            color: #64748B;
            background: #fff;
            border: 1.5px solid #E2E8F0;
            border-radius: 99px;
            text-decoration: none;
            white-space: nowrap;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: .5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .o-tab:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
            color: #0F172A;
            transform: translateY(-1px);
        }

        .o-tab.active {
            background: #EFF6FF;
            border-color: #93C5FD;
            color: #1D4ED8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .o-tab-count {
            background: #F1F5F9;
            color: #64748B;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: .7rem;
            transition: all .2s;
        }

        .o-tab.active .o-tab-count {
            background: #3B82F6;
            color: #fff;
        }

        /* Search & Filter Bar */
        .o-filter-bar {
            display: flex;
            gap: .75rem;
            align-items: center;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
            background: #fff;
            padding: 1rem;
            border-radius: 16px;
            border: 1.5px solid #F1F5F9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .o-search-input {
            flex: 1;
            min-width: 250px;
            padding: .6rem 1rem;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            font-size: .8rem;
            outline: none;
            font-family: 'Montserrat', sans-serif;
            background: #F8FAFC;
            transition: all .2s;
            color: #0F172A;
        }

        .o-search-input:focus {
            border-color: #FCA5A5;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
        }

        .o-btn {
            background: #0F172A;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: .6rem 1.25rem;
            font-size: .8rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: all .2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .o-btn:hover {
            background: #1E293B;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .o-btn-outline {
            background: #fff;
            color: #475569;
            border: 1.5px solid #E2E8F0;
        }

        .o-btn-outline:hover {
            background: #F8FAFC;
            box-shadow: none;
            transform: none;
        }

        /* Order Cards (Premium Style) */
        .o-card {
            background: #fff;
            border-radius: 20px;
            border: 1.5px solid #F1F5F9;
            margin-bottom: 1.25rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            transition: all .25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .o-card:hover {
            border-color: #FECACA;
            box-shadow: 0 12px 30px rgba(239, 68, 68, 0.08);
            transform: translateY(-4px);
        }

        .o-card-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.75rem;
            border-bottom: 1.5px dashed #E2E8F0;
            background: #FAFAF9;
        }

        .o-card-user {
            display: flex;
            align-items: center;
            gap: .85rem;
            font-weight: 800;
            font-size: .95rem;
            color: #0F172A;
        }

        .o-card-user svg {
            color: #94A3B8;
        }

        .o-card-ordernum {
            font-size: .75rem;
            font-weight: 700;
            padding: .35rem .85rem;
            background: #F1F5F9;
            color: #475569;
            border-radius: 8px;
            letter-spacing: .02em;
        }

        .o-card-body {
            display: grid;
            grid-template-columns: 2.5fr 1fr 1fr 1fr;
            padding: 1.5rem 1.75rem;
            gap: 1.5rem;
            align-items: center;
        }

        @media(max-width: 992px) {
            .o-card-body {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
        }

        /* Item Column */
        .o-item-col {
            display: flex;
            gap: 1.25rem;
            align-items: center;
        }

        .o-item-img {
            width: 65px;
            height: 65px;
            border-radius: 12px;
            object-fit: cover;
            border: 1.5px solid #E2E8F0;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .o-item-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .o-item-title {
            font-weight: 800;
            font-size: .9rem;
            color: #1E293B;
            line-height: 1.4;
            margin-bottom: .35rem;
        }

        .o-item-var {
            font-size: .75rem;
            color: #64748B;
            margin-bottom: .35rem;
            font-weight: 600;
        }

        .o-item-qty {
            font-weight: 700;
            font-size: .7rem;
            color: #0F172A;
            background: #F1F5F9;
            padding: .15rem .5rem;
            border-radius: 6px;
            display: inline-block;
            width: max-content;
        }

        /* Total Column */
        .o-col-title {
            font-size: .7rem;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            margin-bottom: .5rem;
            letter-spacing: .03em;
        }

        .o-total-price {
            font-weight: 800;
            color: #EF4444;
            font-size: 1.1rem;
        }

        .o-pay-method {
            font-size: .75rem;
            color: #64748B;
            margin-top: .35rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        /* Status Column */
        .o-status-text {
            font-weight: 800;
            font-size: .85rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .o-status-text::before {
            content: "";
            display: block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .o-ship-name {
            font-weight: 700;
            font-size: .85rem;
            color: #1E293B;
        }

        .o-ship-type {
            font-size: .75rem;
            color: #64748B;
            margin-top: .35rem;
            font-weight: 500;
        }

        /* Action Column */
        .o-action-col {
            text-align: right;
            display: flex;
            flex-direction: column;
            gap: .5rem;
            align-items: flex-end;
        }

        .o-action-btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .8rem;
            font-weight: 700;
            color: #fff;
            background: #EF4444;
            text-decoration: none;
            padding: .65rem 1.25rem;
            transition: all .2s;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
        }

        .o-action-btn:hover {
            background: #DC2626;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(239, 68, 68, 0.3);
        }

        /* Empty state */
        .o-empty {
            text-align: center;
            padding: 5rem 2rem;
            background: #fff;
            border-radius: 20px;
            border: 1.5px dashed #CBD5E1;
        }

        .o-empty-icon {
            width: 80px;
            height: 80px;
            opacity: 0.2;
            margin-bottom: 1.25rem;
            color: #94A3B8;
            display: inline-block;
        }

        .o-empty-text {
            color: #475569;
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: .5rem;
        }

        .o-empty-sub {
            color: #94A3B8;
            font-size: .85rem;
            font-weight: 500;
        }

        /* Modal Download Laporan */
        .rm-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease;
            backdrop-filter: blur(4px);
        }

        .rm-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .rm-modal {
            background: #fff;
            width: 90%;
            max-width: 450px;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transform: translateY(20px);
            transition: all .3s ease;
        }

        .rm-overlay.show .rm-modal {
            transform: translateY(0);
        }

        .rm-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .rm-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0F172A;
        }

        .rm-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: #94A3B8;
            cursor: pointer;
        }

        .rm-close:hover {
            color: #EF4444;
        }

        .rm-group {
            margin-bottom: 1.25rem;
        }

        .rm-label {
            display: block;
            font-size: .8rem;
            font-weight: 800;
            color: #475569;
            margin-bottom: .5rem;
        }

        .rm-presets {
            display: flex;
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .rm-preset-btn {
            flex: 1;
            padding: .5rem;
            font-size: .75rem;
            font-weight: 700;
            color: #3B82F6;
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 8px;
            cursor: pointer;
            transition: all .2s;
        }

        .rm-preset-btn:hover {
            background: #DBEAFE;
        }

        .rm-preset-btn.active {
            background: #3B82F6;
            color: #fff;
            border-color: #2563EB;
        }

        .rm-date-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .rm-input,
        .rm-select {
            width: 100%;
            padding: .75rem 1rem;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: .85rem;
            outline: none;
            background: #F8FAFC;
            color: #0F172A;
        }

        .rm-input:focus,
        .rm-select:focus {
            border-color: #3B82F6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Grid View */
        .o-grid-view {
            display: none;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.25rem;
        }

        .o-grid-card {
            background: #fff;
            border-radius: 20px;
            border: 1.5px solid #F1F5F9;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            transition: all .25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .o-grid-card:hover {
            border-color: #FECACA;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.08);
            transform: translateY(-4px);
        }
    </style>

    <div class="opage">

        {{-- Page Header --}}
        <div class="opage-head">
            <div>
                <h1 class="opage-title">Data Pesanan</h1>
                <p class="opage-sub">Manajemen seluruh pesanan. <strong>{{ $orders->total() }}</strong> pesanan ditemukan.
                </p>
            </div>
            <div style="display:flex;align-items:center;gap:.75rem;">
                {{-- VIEW TOGGLE --}}
                <div
                    style="display:flex;background:#fff;border:1.5px solid #E2E8F0;border-radius:12px;padding:4px;box-shadow:0 2px 10px rgba(0,0,0,0.02);">
                    <button onclick="switchView('list')" id="btn-view-list"
                        style="border:none;background:transparent;border-radius:8px;padding:8px 10px;cursor:pointer;color:#94A3B8;display:flex;align-items:center;transition:all .2s;"
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
                        style="border:none;background:transparent;border-radius:8px;padding:8px 10px;cursor:pointer;color:#94A3B8;display:flex;align-items:center;transition:all .2s;"
                        title="Grid View">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </button>
                </div>
                <button type="button" class="o-btn" onclick="openReportModal()"
                    style="background:#3B82F6; box-shadow:0 4px 12px rgba(59,130,246,0.2); display:flex; align-items:center; gap:.5rem;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Unduh Laporan
                </button>
            </div>
        </div>

        {{-- Premium Tabs --}}
        <div class="o-tabs-wrap">
            @foreach($tabs as $k => $t)
                <a href="{{ route('admin.orders.index', ['tab' => $k]) }}" class="o-tab {{ $tab == $k ? 'active' : '' }}">
                    {{ $t['label'] }} <span class="o-tab-count">{{ $counts[$k] ?? 0 }}</span>
                </a>
            @endforeach
        </div>

        {{-- Search Bar --}}
        <form class="o-filter-bar" method="GET" action="{{ route('admin.orders.index') }}">
            <input type="hidden" name="tab" value="{{ $tab }}">

            <svg width="20" height="20" fill="none" stroke="#94A3B8" stroke-width="2.5" viewBox="0 0 24 24"
                style="margin-left:.5rem;">
                <circle cx="11" cy="11" r="8" />
                <path d="M21 21l-4.35-4.35" />
            </svg>
            <input type="text" name="q" class="o-search-input" placeholder="Cari nomor pesanan atau nama pembeli..."
                value="{{ $q }}">

            <button type="submit" class="o-btn">Terapkan Pencarian</button>
            @if($q)
                <a href="{{ route('admin.orders.index', ['tab' => $tab]) }}" class="o-btn o-btn-outline">Reset</a>
            @endif
        </form>

        {{-- Abandoned Carts Section (Khusus tab Belum Bayar & Semua) --}}
        @if(in_array($tab, ['pending', 'all']) && $abandonedCarts->count() > 0)
            <div style="margin-bottom: 2rem;">
                <div
                    style="display:flex; align-items:center; gap:.5rem; margin-bottom:1rem; padding-bottom:.5rem; border-bottom:1px solid #E2E8F0;">
                    <svg width="18" height="18" fill="none" stroke="#F59E0B" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    <h2 style="font-size:.95rem; font-weight:700; color:#1E293B; margin:0;">Keranjang Belum Checkout (Follow Up)
                    </h2>
                </div>

                <div id="abandoned-view-list">
                    @foreach($abandonedCarts as $u)
                        <div
                            style="background:#fff; padding:1rem 1.25rem; border-radius:16px; border:1.5px solid #F1F5F9; box-shadow:0 2px 10px rgba(0,0,0,0.02); margin-bottom:1rem; transition:all .25s;">
                            <div
                                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; padding-bottom:1rem; border-bottom:1.5px dashed #F1F5F9;">
                                <div style="display:flex; align-items:center; gap:.75rem;">
                                    <img src="{{ $u->avatar ? asset('storage/' . $u->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=E2E8F0&color=475569' }}"
                                        style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                                    <div>
                                        <div style="font-weight:700; color:#0F172A; font-size:.9rem;">{{ $u->name }}</div>
                                        @if($u->username)
                                        <div style="font-size:.75rem; color:#64748B;">{{ '@' . $u->username }}</div> @endif
                                    </div>
                                </div>
                                <div
                                    style="color:#F59E0B; font-weight:700; font-size:.75rem; background:#FFFBEB; padding:.3rem .75rem; border-radius:20px;">
                                    Abandoned Cart</div>
                            </div>
                            <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                                <div style="flex:1; min-width:250px;">
                                    @foreach($u->carts->take(2) as $cart)
                                        <div
                                            style="display:flex; align-items:center; gap:.75rem; {{ !$loop->last ? 'margin-bottom:.75rem;' : '' }}">
                                            <img src="{{ $cart->product && $cart->product->image ? asset('storage/' . $cart->product->image) : asset('img/no-image.jpg') }}"
                                                style="width:48px;height:48px;border-radius:4px;object-fit:cover;border:1px solid #E2E8F0;"
                                                onerror="this.src='https://via.placeholder.com/50?text=Img'">
                                            <div>
                                                <div style="font-size:.85rem; font-weight:600; color:#1E293B; margin-bottom:.15rem;">
                                                    {{ Str::limit($cart->product->name ?? 'Produk Dihapus', 50) }}</div>
                                                <div style="font-size:.75rem; color:#64748B;">Qty: {{ $cart->qty }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($u->carts->count() > 2)
                                        <div
                                            style="font-size:.75rem; color:#64748B; margin-top:.5rem; font-weight:700; background:#F8FAFC; padding:.3rem .75rem; border-radius:6px; display:inline-block;">
                                            + {{ $u->carts->count() - 2 }} produk lainnya</div>
                                    @endif
                                </div>
                                <div style="text-align:right; min-width:120px;">
                                    <div
                                        style="font-size:.75rem; color:#64748B; margin-bottom:.25rem; font-weight:700; text-transform:uppercase;">
                                        Estimasi Total</div>
                                    <div style="font-size:1.1rem; font-weight:800; color:#EF4444;">Rp
                                        {{ number_format($u->carts->sum('subtotal'), 0, ',', '.') }}</div>
                                </div>
                                <div style="min-width:140px; text-align:right;">
                                    @php
                                        $waText = "Halo Kak {$u->name}, kami melihat ada produk di keranjang Anda yang belum dicheckout nih. Apakah ada kendala saat pemesanan? 😊";
                                        $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $u->addresses->first()->phone ?? '') . "?text=" . urlencode($waText);
                                    @endphp
                                    <a href="{{ $waUrl }}" target="_blank"
                                        style="display:inline-flex; align-items:center; justify-content:center; gap:.4rem; padding:.6rem 1.25rem; font-size:.8rem; font-weight:700; color:#fff; background:#10B981; text-decoration:none; border-radius:10px; transition:all .2s; box-shadow:0 4px 12px rgba(16,185,129,0.2);"
                                        onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10B981'">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                        </svg>
                                        Chat Follow Up
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="abandoned-view-grid" class="o-grid-view">
                    @foreach($abandonedCarts as $u)
                        <div class="o-grid-card">
                            <div
                                style="padding:1rem 1.25rem;background:#FFFBEB;border-bottom:1.5px dashed #FDE68A;display:flex;justify-content:space-between;align-items:center;">
                                <div style="display:flex;align-items:center;gap:.75rem;">
                                    <img src="{{ $u->avatar ? asset('storage/' . $u->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=E2E8F0&color=475569' }}"
                                        style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
                                    <div>
                                        <div style="font-weight:800;font-size:.88rem;color:#0F172A;">{{ $u->name }}</div>
                                        @if($u->username)
                                        <div style="font-size:.7rem;color:#64748B;">{{ '@' . $u->username }}</div> @endif
                                    </div>
                                </div>
                                <div
                                    style="font-size:.7rem;font-weight:700;padding:.3rem .75rem;background:#F59E0B;color:#fff;border-radius:8px;">
                                    Cart</div>
                            </div>
                            <div style="padding:1rem 1.25rem;flex:1;">
                                @foreach($u->carts->take(2) as $cart)
                                    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem;">
                                        <img src="{{ $cart->product && $cart->product->image ? asset('storage/' . $cart->product->image) : asset('img/no-image.jpg') }}"
                                            style="width:52px;height:52px;border-radius:10px;object-fit:cover;border:1.5px solid #E2E8F0;flex-shrink:0;"
                                            onerror="this.src='https://via.placeholder.com/52?text=Img'">
                                        <div>
                                            <div style="font-weight:700;font-size:.85rem;color:#1E293B;line-height:1.3;">
                                                {{ Str::limit($cart->product->name ?? 'Produk Dihapus', 40) }}</div>
                                            <div style="font-size:.72rem;color:#94A3B8;margin-top:.2rem;">Qty: {{ $cart->qty }}</div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($u->carts->count() > 2)
                                    <div
                                        style="font-size:.72rem;color:#64748B;background:#F8FAFC;padding:.25rem .5rem;border-radius:6px;display:inline-block;margin-bottom:.75rem;font-weight:700;">
                                        + {{ $u->carts->count() - 2 }} produk lainnya</div>
                                @endif
                                <div
                                    style="display:flex;justify-content:space-between;align-items:center;padding-top:.75rem;border-top:1px solid #F1F5F9;">
                                    <div>
                                        <div
                                            style="font-size:.68rem;color:#94A3B8;font-weight:700;text-transform:uppercase;margin-bottom:.2rem;">
                                            Estimasi Total</div>
                                        <div style="font-weight:800;color:#EF4444;font-size:1rem;">Rp
                                            {{ number_format($u->carts->sum('subtotal'), 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div style="padding:.875rem 1.25rem;border-top:1.5px solid #F1F5F9;">
                                @php
                                    $waText = "Halo Kak {$u->name}, kami melihat ada produk di keranjang Anda yang belum dicheckout nih. Apakah ada kendala saat pemesanan? 😊";
                                    $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $u->addresses->first()->phone ?? '') . "?text=" . urlencode($waText);
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank"
                                    style="display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.6rem;font-size:.8rem;font-weight:700;color:#fff;background:#10B981;text-decoration:none;border-radius:10px;transition:all .2s;box-shadow:0 4px 10px rgba(16,185,129,0.2);"
                                    onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10B981'">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                    </svg>
                                    Chat Follow Up
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div
                style="display:flex; align-items:center; gap:.5rem; margin-bottom:1rem; margin-top: 1rem; padding-bottom:.5rem; border-bottom:1px solid #E2E8F0;">
                <svg width="18" height="18" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <h2 style="font-size:.95rem; font-weight:700; color:#1E293B; margin:0;">Pesanan Menunggu Pembayaran</h2>
            </div>
        @endif

        {{-- Order List (LIST VIEW) --}}
        <div id="view-list">
            @forelse($orders as $o)
                <div class="o-card">
                    {{-- Header --}}
                    <div class="o-card-head">
                        <div class="o-card-user">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            {{ $o->user->name ?? 'User Terhapus' }}
                        </div>
                        <div class="o-card-ordernum">ORD #{{ $o->order_number }}</div>
                    </div>

                    {{-- Body --}}
                    <div class="o-card-body">

                        {{-- Product(s) Info --}}
                        <div>
                            @foreach($o->items->take(2) as $item)
                                <div class="o-item-col" style="{{ !$loop->last ? 'margin-bottom:1.25rem;' : '' }}">
                                    <img src="{{ $item->product && $item->product->image ? asset('storage/' . $item->product->image) : asset('img/no-image.jpg') }}"
                                        class="o-item-img" onerror="this.src='https://via.placeholder.com/70?text=No+Img'">
                                    <div class="o-item-info">
                                        <div class="o-item-title">{{ Str::limit($item->product_name, 50) }}</div>
                                        @if($item->variant_name)
                                            <div class="o-item-var">Varian: {{ $item->variant_name }}</div>
                                        @endif
                                        <div class="o-item-qty">Qty: {{ $item->qty }}</div>
                                    </div>
                                </div>
                            @endforeach
                            @if($o->items->count() > 2)
                                <div
                                    style="font-size:.75rem;color:#64748B;margin-top:.75rem;font-weight:700;background:#F8FAFC;padding:.3rem .75rem;border-radius:6px;display:inline-block;">
                                    + {{ $o->items->count() - 2 }} produk lainnya
                                </div>
                            @endif
                        </div>

                        {{-- Status & Ekspedisi --}}
                        <div>
                            <div class="o-col-title">Status Pesanan</div>
                            <div class="o-status-text"
                                style="color: {{ $o->status->color() === 'red' ? '#EF4444' : ($o->status->color() === 'green' ? '#10B981' : ($o->status->color() === 'yellow' ? '#F59E0B' : '#3B82F6')) }};">
                                {{ $o->status->label() }}
                            </div>

                            <div class="o-col-title" style="margin-top:1.25rem;">Kurir Kirim</div>
                            <div class="o-ship-name">{{ $o->shipment->courier_name ?? 'Reguler' }}</div>
                            <div class="o-ship-type">Resi: {{ $o->shipment->tracking_number ?? 'Belum ada resi' }}</div>
                        </div>

                        {{-- Total Pembayaran --}}
                        <div>
                            <div class="o-col-title">Total Belanja</div>
                            <div class="o-total-price">Rp {{ number_format($o->total, 0, ',', '.') }}</div>
                            <div class="o-pay-method">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="2" y="5" width="20" height="14" rx="2" />
                                    <line x1="2" y1="10" x2="22" y2="10" />
                                </svg>
                                {{ $o->payment->method ?? 'Transfer Bank' }}
                            </div>
                        </div>

                        {{-- Action --}}
                        <div class="o-action-col">
                            <a href="{{ route('admin.orders.show', $o) }}" class="o-action-btn">
                                Lihat Rincian
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>
            @empty
                {{-- Empty state --}}
                @if(empty($abandonedCarts) || $abandonedCarts->count() === 0)
                    <div class="o-empty">
                        <svg class="o-empty-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                            <line x1="12" y1="22.08" x2="12" y2="12" />
                        </svg>
                        <div class="o-empty-text">Pesanan Tidak Ditemukan</div>
                        <div class="o-empty-sub">Belum ada transaksi di tab ini atau kata kunci tidak cocok.</div>
                    </div>
                @endif
            @endforelse
        </div>{{-- /view-list --}}

        {{-- GRID VIEW --}}
        <div id="view-grid" class="o-grid-view">
            @forelse($orders as $o)
                <div class="o-grid-card">
                    <div
                        style="padding:1rem 1.25rem;background:#FAFAF9;border-bottom:1.5px dashed #E2E8F0;display:flex;justify-content:space-between;align-items:center;">
                        <div style="font-weight:800;font-size:.88rem;color:#0F172A;display:flex;align-items:center;gap:.5rem;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            {{ $o->user->name ?? 'User Terhapus' }}
                        </div>
                        <div
                            style="font-size:.7rem;font-weight:700;padding:.3rem .75rem;background:#F1F5F9;color:#475569;border-radius:8px;">
                            #{{ $o->order_number }}</div>
                    </div>
                    <div style="padding:1rem 1.25rem;flex:1;">
                        @foreach($o->items->take(1) as $item)
                            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem;">
                                <img src="{{ $item->product && $item->product->image ? asset('storage/' . $item->product->image) : asset('img/no-image.jpg') }}"
                                    style="width:52px;height:52px;border-radius:10px;object-fit:cover;border:1.5px solid #E2E8F0;flex-shrink:0;"
                                    onerror="this.src='https://via.placeholder.com/52?text=Img'">
                                <div>
                                    <div style="font-weight:700;font-size:.85rem;color:#1E293B;line-height:1.3;">
                                        {{ Str::limit($item->product_name, 40) }}</div>
                                    <div style="font-size:.72rem;color:#94A3B8;margin-top:.2rem;">Qty: {{ $item->qty }}</div>
                                </div>
                            </div>
                        @endforeach
                        @if($o->items->count() > 1)
                            <div
                                style="font-size:.72rem;color:#64748B;background:#F8FAFC;padding:.25rem .5rem;border-radius:6px;display:inline-block;margin-bottom:.75rem;">
                                + {{ $o->items->count() - 1 }} produk lainnya</div>
                        @endif
                        <div
                            style="display:flex;justify-content:space-between;align-items:center;padding-top:.75rem;border-top:1px solid #F1F5F9;">
                            <div>
                                <div
                                    style="font-size:.68rem;color:#94A3B8;font-weight:700;text-transform:uppercase;margin-bottom:.2rem;">
                                    Total</div>
                                <div style="font-weight:800;color:#EF4444;font-size:1rem;">Rp
                                    {{ number_format($o->total, 0, ',', '.') }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div
                                    style="font-size:.68rem;color:#94A3B8;font-weight:700;text-transform:uppercase;margin-bottom:.2rem;">
                                    Status</div>
                                <div
                                    style="font-weight:800;font-size:.8rem;color:{{ $o->status->color() === 'red' ? '#EF4444' : ($o->status->color() === 'green' ? '#10B981' : ($o->status->color() === 'yellow' ? '#F59E0B' : '#3B82F6')) }};">
                                    {{ $o->status->label() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="padding:.875rem 1.25rem;border-top:1.5px solid #F1F5F9;">
                        <a href="{{ route('admin.orders.show', $o) }}"
                            style="display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.6rem;font-size:.8rem;font-weight:700;color:#fff;background:#EF4444;text-decoration:none;border-radius:10px;transition:all .2s;box-shadow:0 4px 10px rgba(239,68,68,0.2);"
                            onmouseover="this.style.background='#DC2626'" onmouseout="this.style.background='#EF4444'">
                            Lihat Rincian <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div
                    style="grid-column:1/-1;text-align:center;padding:4rem;background:#fff;border-radius:20px;border:1.5px dashed #CBD5E1;">
                    <div style="font-size:1rem;font-weight:800;color:#334155;">Pesanan Tidak Ditemukan</div>
                </div>
            @endforelse
        </div>{{-- /view-grid --}}

        @if($orders->hasPages())
            <div style="display:flex;justify-content:flex-end;margin-top:2rem;">
                {{ $orders->links() }}
            </div>
        @endif

    </div>

    {{-- Report Modal --}}
    <div class="rm-overlay" id="reportModal">
        <div class="rm-modal">
            <div class="rm-head">
                <div class="rm-title">Unduh Laporan</div>
                <button class="rm-close" onclick="closeReportModal()">×</button>
            </div>
            <form action="{{ route('admin.orders.export') }}" method="GET">
                <div class="rm-group">
                    <label class="rm-label">Pilih Periode Cepat</label>
                    <div class="rm-presets">
                        <button type="button" class="rm-preset-btn" onclick="setPreset(7, this)">7 Hari</button>
                        <button type="button" class="rm-preset-btn active" onclick="setPreset(30, this)">30 Hari</button>
                        <button type="button" class="rm-preset-btn" onclick="setPreset(365, this)">1 Tahun</button>
                    </div>
                </div>
                <div class="rm-group rm-date-inputs">
                    <div>
                        <label class="rm-label">Dari Tanggal</label>
                        <input type="date" name="start_date" id="start_date" class="rm-input" required>
                    </div>
                    <div>
                        <label class="rm-label">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="end_date" class="rm-input" required>
                    </div>
                </div>
                <div class="rm-group">
                    <label class="rm-label">Format File</label>
                    <select name="format" class="rm-select">
                        <option value="xlsx">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                        <option value="pdf">PDF (.pdf)</option>
                    </select>
                </div>
                <button type="submit" class="o-btn"
                    style="width:100%; background:#10B981; margin-top:1rem; box-shadow:0 4px 12px rgba(16,185,129,0.2);">Download
                    Sekarang</button>
            </form>
        </div>
    </div>

    <script>
        function openReportModal() {
            document.getElementById('reportModal').classList.add('show');
            // Default 30 days
            document.querySelector('.rm-preset-btn.active')?.click();
        }
        function closeReportModal() {
            document.getElementById('reportModal').classList.remove('show');
        }
        function setPreset(days, btn) {
            // Update active class
            document.querySelectorAll('.rm-preset-btn').forEach(el => el.classList.remove('active'));
            btn.classList.add('active');

            // Calculate dates
            const end = new Date();
            const start = new Date();
            start.setDate(end.getDate() - days);

            document.getElementById('end_date').value = end.toISOString().split('T')[0];
            document.getElementById('start_date').value = start.toISOString().split('T')[0];
        }

        // Ensure clicking outside modal closes it
        document.getElementById('reportModal').addEventListener('click', function (e) {
            if (e.target === this) closeReportModal();
        });

        // View Toggle
        function switchView(type) {
            localStorage.setItem('admin_orders_view', type);
            document.getElementById('view-list').style.display = type === 'list' ? 'block' : 'none';
            document.getElementById('view-grid').style.display = type === 'grid' ? 'grid' : 'none';

            let abl = document.getElementById('abandoned-view-list');
            let abg = document.getElementById('abandoned-view-grid');
            if (abl) abl.style.display = type === 'list' ? 'block' : 'none';
            if (abg) abg.style.display = type === 'grid' ? 'grid' : 'none';

            document.getElementById('btn-view-list').style.background = type === 'list' ? '#3B82F6' : 'transparent';
            document.getElementById('btn-view-list').style.color = type === 'list' ? '#fff' : '#94A3B8';
            document.getElementById('btn-view-grid').style.background = type === 'grid' ? '#3B82F6' : 'transparent';
            document.getElementById('btn-view-grid').style.color = type === 'grid' ? '#fff' : '#94A3B8';
        }
        const savedOrderView = localStorage.getItem('admin_orders_view') || 'list';
        switchView(savedOrderView);
    </script>

@endsection