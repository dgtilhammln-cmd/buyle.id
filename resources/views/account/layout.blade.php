@extends('layouts.app')

@section('title', 'Akun – buyle.id')

@section('content')
<style>

        /* ─── WRAPPER ─── */
        .acc-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem 3rem;
        }

        /* ─── PAGE TITLE ─── */
        .acc-page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.03em;
        }
        .acc-page-welcome {
            font-size: 0.875rem;
            color: #64748B;
            margin-top: 0.25rem;
            font-weight: 500;
        }
        .acc-title-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* ─── TABS NAV ─── */
        .acc-tabs {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            border-bottom: 2px solid #E2E8F0;
            margin-bottom: 2rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .acc-tabs::-webkit-scrollbar { display: none; }
        .acc-tab {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.625rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748B;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .acc-tab:hover { color: #0EA5E9; }
        .acc-tab.active {
            color: #0EA5E9;
            border-bottom-color: #0EA5E9;
        }
        .acc-tab svg { opacity: 0.7; }
        .acc-tab.active svg { opacity: 1; }

        /* ─── CARDS ─── */
        .acc-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .acc-stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            border: 1px solid #E2E8F0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: box-shadow 0.2s;
        }
        .acc-stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.07); }
        .acc-stat-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #94A3B8;
            letter-spacing: 0.03em;
            margin-bottom: 0.5rem;
        }
        .acc-stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.03em;
        }
        .acc-stat-value.blue { color: #0EA5E9; }

        /* ─── CONTENT CARD ─── */
        .acc-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .acc-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #F1F5F9;
        }
        .acc-card-header h2 {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
        }
        .acc-card-header p {
            font-size: 0.8rem;
            color: #64748B;
            margin-top: 0.2rem;
        }
        .acc-card-body { padding: 1.5rem; }

        /* ─── FORM ─── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .form-group.full { grid-column: 1 / -1; }
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
        }
        .form-label span { color: #EF4444; }
        .form-input {
            height: 44px;
            padding: 0 1rem;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
            color: #0f172a;
            background: #F8FAFC;
            transition: all 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: #0EA5E9;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(14,165,233,0.1);
        }
        textarea.form-input {
            height: auto;
            padding: 0.75rem 1rem;
            resize: vertical;
            min-height: 90px;
        }
        select.form-input { cursor: pointer; }
        .form-error {
            font-size: 0.75rem;
            color: #EF4444;
        }
        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #0f172a;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.625rem 1.25rem;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-save:hover { background: #1e293b; box-shadow: 0 4px 12px rgba(15,23,42,0.2); }
        .btn-save-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        /* ─── TOGGLE SWITCH ─── */
        .toggle-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1rem 0;
        }
        .toggle-switch {
            position: relative;
            width: 44px; height: 24px;
        }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute;
            inset: 0;
            background: #CBD5E1;
            border-radius: 24px;
            cursor: pointer;
            transition: 0.3s;
        }
        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 18px; height: 18px;
            left: 3px; top: 3px;
            background: #fff;
            border-radius: 50%;
            transition: 0.3s;
        }
        .toggle-switch input:checked + .toggle-slider { background: #0EA5E9; }
        .toggle-switch input:checked + .toggle-slider::before { transform: translateX(20px); }
        .toggle-label { font-size: 0.875rem; font-weight: 600; color: #374151; }

        /* ─── AVATAR ─── */
        .avatar-row {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.75rem;
            padding-bottom: 1.75rem;
            border-bottom: 1px solid #F1F5F9;
        }
        .avatar-img {
            width: 80px; height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #E2E8F0;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            font-weight: 800;
            color: #0EA5E9;
            flex-shrink: 0;
        }
        .btn-upload {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #F1F5F9;
            color: #374151;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-upload:hover { border-color: #0EA5E9; color: #0EA5E9; background: #EFF6FF; }

        /* ─── ADDRESSES ─── */
        .addr-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }
        .addr-card {
            border: 1.5px solid #E2E8F0;
            border-radius: 14px;
            padding: 1.25rem;
            background: #fff;
            transition: all 0.2s;
            position: relative;
        }
        .addr-card.is-default { border-color: #0EA5E9; background: #EFF6FF; }
        .addr-default-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: #0EA5E9;
            color: #fff;
            border-radius: 999px;
            padding: 0.2rem 0.6rem;
            font-size: 0.7rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .addr-label {
            font-size: 0.875rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.3rem;
        }
        .addr-name { font-size: 0.8rem; color: #374151; font-weight: 600; }
        .addr-detail { font-size: 0.8rem; color: #64748B; margin-top: 0.3rem; line-height: 1.5; }
        .addr-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .btn-addr-del {
            flex: 1;
            padding: 0.4rem;
            border-radius: 8px;
            border: 1.5px solid #FECACA;
            background: #FFF;
            color: #EF4444;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-addr-del:hover { background: #FEF2F2; }
        .btn-addr-default {
            flex: 2;
            padding: 0.4rem;
            border-radius: 8px;
            border: 1.5px solid #E2E8F0;
            background: #FFF;
            color: #0EA5E9;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-addr-default:hover { background: #EFF6FF; }
        .btn-add-addr {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #0EA5E9;
            color: #fff;
            border-radius: 10px;
            padding: 0.6rem 1.25rem;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-add-addr:hover { background: #0284C7; box-shadow: 0 4px 12px rgba(14,165,233,0.3); }

        /* ─── MODAL ─── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 24px 60px rgba(0,0,0,0.15);
        }
        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        .modal-title { font-size: 1.1rem; font-weight: 800; color: #0f172a; }
        .modal-subtitle { font-size: 0.8rem; color: #64748B; margin-top: 0.2rem; }
        .modal-close {
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px;
            background: #F1F5F9;
            border: none;
            cursor: pointer;
            color: #64748B;
            flex-shrink: 0;
        }
        .modal-close:hover { background: #E2E8F0; }

        /* ─── EMPTY STATE ─── */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #94A3B8;
        }
        .empty-state svg { margin-bottom: 1rem; opacity: 0.5; }
        .empty-state h3 { font-size: 1rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem; }
        .empty-state p { font-size: 0.85rem; }

        /* ─── ALERT ─── */
        .alert-success {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 10px;
            padding: 0.875rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.8rem;
            color: #15803D;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ─── ORDER TABLE ─── */
        .order-table { width: 100%; border-collapse: collapse; }
        .order-table th {
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            color: #94A3B8;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            border-bottom: 1.5px solid #E2E8F0;
        }
        .order-table td {
            padding: 0.875rem 1rem;
            font-size: 0.85rem;
            color: #374151;
            border-bottom: 1px solid #F1F5F9;
        }
        .order-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .badge-pending { background: #FEF9C3; color: #854D0E; }
        .badge-processing { background: #DBEAFE; color: #1D4ED8; }
        .badge-shipped { background: #F0FDF4; color: #15803D; }
        .badge-completed { background: #ECFDF5; color: #059669; }
        .badge-cancelled { background: #FEF2F2; color: #B91C1C; }

        /* ─── MODAL ─── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.45);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-overlay.open {
            display: flex;
        }
        .modal-box {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 560px;
            max-height: 92vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(15,23,42,0.2);
            animation: modalIn 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }
        @keyframes modalIn {
            from { opacity:0; transform: scale(0.93) translateY(20px); }
            to   { opacity:1; transform: scale(1) translateY(0); }
        }
        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }
        .modal-title { font-size: 1.1rem; font-weight: 800; color: #0f172a; }
        .modal-subtitle { font-size: 0.8rem; color: #64748B; margin-top: 0.2rem; }
        .modal-close {
            background: #F1F5F9;
            border: none;
            border-radius: 8px;
            padding: 0.5rem;
            cursor: pointer;
            color: #64748B;
            flex-shrink: 0;
        }
        .modal-close:hover { background: #E2E8F0; color: #0f172a; }

        /* ─── MODAL VISIBILITY ENFORCEMENT ─── */
        .modal-overlay.open {
            display: flex !important;
        }
        .form-input:focus { outline: none; border-color: #0EA5E9; box-shadow: 0 0 0 3px rgba(14,165,233,0.1); background: #fff; }
        .btn-save-row { display: flex; align-items: center; justify-content: flex-end; padding-top: 0.5rem; }
        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #0EA5E9;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.65rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.2s;
        }
        .btn-save:hover { background: #0284C7; }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 768px) {
            .acc-wrap { padding: 1.25rem 1rem 2rem; }
            .acc-stat-grid { grid-template-columns: repeat(2, 1fr); }
            .form-grid { grid-template-columns: 1fr; }
            .acc-title-row { flex-direction: column; }
            .modal-box { padding: 1.5rem 1.25rem; }
        }
        @media (max-width: 480px) {
            .acc-stat-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
            .acc-stat-value { font-size: 1.375rem; }
            .acc-header-inner { padding: 0 1rem; }
            .acc-user-name { display: none; }
        }
    </style>

    
    <div style="background: #F8FAFC; min-height: 100vh; padding-top: 80px; padding-bottom: 3rem;">
    <main class="acc-wrap">
        {{-- Title Row --}}
        <div class="acc-title-row">
            <div>
                <h1 class="acc-page-title">Akun</h1>
                <p class="acc-page-welcome">Selamat datang kembali, {{ Auth::user()->name }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- TAB NAV --}}
        <nav class="acc-tabs">
            <a href="{{ route('account.overview') }}"
               class="acc-tab {{ request()->routeIs('account.overview') ? 'active' : '' }}">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Overview
            </a>
            <a href="{{ route('account.wishlist') }}"
               class="acc-tab {{ request()->routeIs('account.wishlist') ? 'active' : '' }}">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                Wishlist
            </a>
            <a href="{{ route('account.orders') }}"
               class="acc-tab {{ request()->routeIs('account.orders') ? 'active' : '' }}">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Pesanan
            </a>
            <a href="{{ route('account.addresses') }}"
               class="acc-tab {{ request()->routeIs('account.addresses') ? 'active' : '' }}">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Alamat
            </a>
            <a href="{{ route('account.profile') }}"
               class="acc-tab {{ request()->routeIs('account.profile') ? 'active' : '' }}">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93A10 10 0 0 1 21 12a10 10 0 0 1-1.93 7.07m-2.14 2.14A10 10 0 0 1 12 23a10 10 0 0 1-7.07-2.93m-2.14-2.14A10 10 0 0 1 1 12a10 10 0 0 1 2.93-7.07M4.93 4.93A10 10 0 0 1 12 1a10 10 0 0 1 7.07 2.93"/></svg>
                Profile
            </a>
            <a href="{{ route('account.cart') }}"
               class="acc-tab {{ request()->routeIs('account.cart') ? 'active' : '' }}">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Keranjang
            </a>
        </nav>

        {{-- PAGE CONTENT --}}
        @yield('acc_page')
    </main>
    </div>
@endsection
