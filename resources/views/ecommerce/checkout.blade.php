@extends('layouts.app')

@section('content')
    <style>
        :root {
            --c-bg: #ffffff;
            --c-surface: #F8FAFC;
            --c-card: #ffffff;
            --c-border: #E2E8F0;
            --c-text: #0F172A;
            --c-muted: #64748B;
            --c-accent: #1eb349;
            --font: 'Montserrat', sans-serif;
        }

        body {
            background: var(--c-bg);
        }

        .co-wrap {
            max-width: 1200px;
            margin: 100px auto 4rem;
            padding: 0 1.5rem;
            font-family: var(--font);
        }

        /* Interactive Fee Popover Tooltips */
        .fee-info-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .fee-info-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: #E2E8F0;
            color: #475569;
            font-size: 0.65rem;
            font-weight: 700;
            font-style: normal;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
            line-height: 1;
        }

        .fee-info-wrapper:hover .fee-info-icon,
        .fee-info-icon.active {
            background: #1eb349;
            color: #ffffff;
        }

        .fee-popover {
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%) translateY(4px);
            width: 220px;
            background: #ffffff;
            color: #334155;
            border: 1px solid #E2E8F0;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 0.73rem;
            line-height: 1.4;
            font-weight: 500;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.2s;
            z-index: 9999;
            pointer-events: none;
            text-align: left;
            white-space: normal;
        }

        .fee-popover::before {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 7px;
            border-style: solid;
            border-color: #E2E8F0 transparent transparent transparent;
        }

        .fee-popover::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: #ffffff transparent transparent transparent;
        }

        .fee-info-wrapper:hover .fee-popover,
        .fee-popover.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
            pointer-events: auto;
        }

        .fee-popover-title {
            font-weight: 800;
            color: #1eb349;
            margin-bottom: 3px;
            display: block;
            font-size: 0.75rem;
        }

        .co-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            align-items: start;
        }

        .co-section {
            background: var(--c-card);
            border: 1px solid var(--c-border);
            border-radius: 16px;
            padding: 1.75rem;
        }

        .co-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--c-text);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--c-text);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            color: #0F172A;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #1eb349;
            background: #fff;
        }

        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        /* ══ PREMIUM TICKET IDENTITY INPUT & SELECT ══ */
        .ticket-identity-grid {
            display: grid;
            grid-template-columns: 155px 1fr;
            gap: 8px;
            align-items: stretch;
            width: 100%;
            box-sizing: border-box;
        }

        .ticket-id-type-select {
            width: 100%;
            min-height: 42px;
            background-color: #F8FAFC;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231eb349' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 0.9em;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 0.6rem 2.2rem 0.6rem 0.85rem;
            font-size: 0.84rem;
            font-weight: 600;
            color: #1E293B;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            outline: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .ticket-id-type-select:hover {
            border-color: #94A3B8;
            background-color: #fff;
        }

        .ticket-id-type-select:focus {
            border-color: #1eb349;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
        }

        .ticket-id-number-input {
            width: 100%;
            min-height: 42px;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 0.6rem 0.85rem;
            font-size: 0.86rem;
            font-weight: 500;
            color: #0F172A;
            outline: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .ticket-id-number-input:hover {
            border-color: #94A3B8;
            background-color: #fff;
        }

        .ticket-id-number-input:focus {
            border-color: #1eb349;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
        }

        @media (max-width: 540px) {
            .ticket-identity-grid {
                grid-template-columns: 1fr !important;
                gap: 6px !important;
            }

            .ticket-id-type-select,
            .ticket-id-number-input {
                min-height: 42px !important;
                font-size: 0.86rem !important;
                padding: 0.6rem 0.85rem !important;
            }

            .ticket-id-type-select {
                padding-right: 2.1rem !important;
            }
        }

        .summary-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px dashed #E2E8F0;
        }

        .summary-img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            background: #F1F5F9;
            flex-shrink: 0;
        }

        .summary-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--c-text);
            line-height: 1.3;
        }

        .summary-meta {
            font-size: 0.8rem;
            color: var(--c-muted);
            margin-top: 0.25rem;
        }

        .summary-price {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--c-accent);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: var(--c-muted);
            margin-bottom: 0.5rem;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--c-text);
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--c-border);
        }

        .btn-pay {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-align: center;
            margin-top: 1.5rem;
            box-shadow: 0 6px 20px rgba(30, 179, 73, .35);
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 179, 73, .35);
        }

        /* Scrollbar biru — hapus kuning */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }

        ::-webkit-scrollbar-thumb {
            background: #1eb349;
            border-radius: 4px;
        }

        /* Hapus semua outline/highlight kuning browser */
        *:focus {
            outline: none !important;
        }

        *:focus-visible {
            outline: 2px solid #1eb349 !important;
            outline-offset: 2px !important;
        }

        input[type="radio"]:focus,
        input[type="radio"]:focus-visible {
            outline: none !important;
            box-shadow: none !important;
        }

        label:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .service-card {
            outline: none !important;
            box-shadow: none !important;
        }

        .guest-benefit-desktop {
            display: block !important;
        }

        .guest-benefit-mobile {
            display: none !important;
        }

        @media(max-width:991px) {
            .guest-benefit-desktop {
                display: none !important;
            }

            .guest-benefit-mobile {
                display: flex !important;
            }

            .co-grid {
                grid-template-columns: 1fr;
            }

            .co-wrap {
                margin-top: 80px;
            }
        }

        /* Mobile super friendly */
        @media(max-width:768px) {
            .co-wrap {
                margin-top: 0.75rem;
                padding: 0 0.75rem;
                margin-bottom: 5rem;
            }

            .co-section {
                padding: 1rem !important;
                border-radius: 12px;
                margin-bottom: 0.75rem !important;
            }

            .co-section-title {
                font-size: 0.95rem;
                margin-bottom: 0.85rem;
            }

            .form-group {
                margin-bottom: 0.85rem;
            }

            .form-label {
                font-size: 0.8rem;
            }

            .form-input {
                font-size: 1rem;
                padding: 0.85rem 1rem;
                border-radius: 10px;
                min-height: 48px;
            }

            select.form-input {
                font-size: 1rem;
            }

            textarea.form-input {
                min-height: 80px;
            }

            .summary-item {
                gap: 0.75rem;
            }

            .summary-img {
                width: 48px;
                height: 48px;
            }

            .summary-title {
                font-size: 0.85rem;
            }

            /* Collapse 2-col grids to 1-col on mobile */
            [style*="grid-template-columns:1fr 1fr"],
            div[style*="grid-template-columns:1fr 1fr"] {
                display: flex !important;
                flex-direction: column !important;
                gap: 0 !important;
            }

            /* Hide floating chat widget on checkout page */
            .fc-widget {
                display: none !important;
            }

            /* Hide site-wide footer on mobile - checkout has its own sticky footer */
            .cv-footer-v2 {
                display: none !important;
            }

            .summary-meta,
            .summary-price {
                font-size: 0.78rem;
            }

            .summary-row,
            .summary-total {
                font-size: 0.85rem;
            }

            .btn-pay {
                padding: 1rem;
                font-size: 1rem;
                border-radius: 12px;
                margin-top: 0.75rem;
            }

            /* SUPER APP STICKY FOOTER */
            .co-wrap {
                padding-bottom: 100px;
            }

            .checkout-sticky-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                background: #fff;
                padding: 0.85rem 1rem;
                box-shadow: 0 -4px 25px rgba(0, 0, 0, 0.08);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                z-index: 1000;
                border-top: 1px solid var(--c-border);
                margin: 0;
            }

            .checkout-sticky-footer .summary-total {
                margin: 0;
                padding: 0;
                border: none;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            .checkout-sticky-footer .summary-total span:first-child {
                font-size: 0.75rem;
                font-weight: 500;
                color: var(--c-muted);
                margin-bottom: 0.15rem;
                font-family: var(--font);
            }

            .checkout-sticky-footer .summary-total span:last-child {
                font-size: 1.15rem;
                font-weight: 800;
                color: var(--c-accent);
                font-family: var(--font);
            }

            .checkout-sticky-footer .btn-pay {
                margin: 0;
                width: auto;
                padding: 0.8rem 1.5rem;
                font-size: 0.95rem;
                flex-shrink: 0;
                flex: 1;
                max-width: 200px;
            }

            .secure-badge {
                display: none !important;
            }

            #courier_service_container label {
                padding: 0.85rem !important;
                border-radius: 8px !important;
                gap: 0.75rem !important;
            }

            /* Guest benefit card: stack on mobile */
            .co-left>div[style*="max-width:180px"] {
                max-width: 100% !important;
            }
        }

        /* SweetAlert2 custom theme */
        .swal2-border-radius {
            border-radius: 20px !important;
            font-family: 'Montserrat', sans-serif !important;
        }

        .swal2-title {
            font-family: 'Montserrat', sans-serif !important;
        }

        .swal2-html-container {
            font-family: 'Montserrat', sans-serif !important;
        }

        .swal2-confirm {
            border-radius: 999px !important;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 700 !important;
            padding: 0.75rem 2rem !important;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        /* Service card: hilangkan outline/garis kuning bawaan browser */
        .service-card {
            outline: none !important;
        }

        .service-card:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .service-card input[type="radio"]:focus {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>

    <div class="co-wrap">
        @if(session('error'))
            <div
                style="background:#FEF2F2;border:1px solid #FCA5A5;color:#B91C1C;padding:1rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.9rem;">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div
                style="background:#FEF2F2;border:1px solid #FCA5A5;color:#B91C1C;padding:1rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.9rem;">
                <ul style="margin:0;padding-left:1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            // Cek apakah ada produk gratis dengan syarat follow/join di keranjang
            $hasFreeRequirements = false;
            $requiresScreenshot = false;
            $claimSteps = [];
            foreach ($summary['items'] as $itemIdx => $item) {
                if ($item->product && $item->product->require_follow_join) {
                    if (!empty($item->product->require_screenshot_proof)) {
                        $requiresScreenshot = true;
                    }
                    $activeReqs = method_exists($item->product, 'getActiveFreeRequirements')
                        ? $item->product->getActiveFreeRequirements()
                        : [];
                    if (!empty($activeReqs)) {
                        $hasFreeRequirements = true;
                        foreach ($activeReqs as $rIdx => $r) {
                            $claimSteps[] = array_merge($r, [
                                'step_id' => $r['key'] . '_' . $itemIdx . '_' . $rIdx,
                                'product_name' => $item->product->name,
                            ]);
                        }
                    }
                }
            }
        @endphp

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf
            {{-- Mode Beli Sekarang (Direct Single Product Checkout) --}}
            @if(request('buy_now') || session('buy_now_cart_id'))
                <input type="hidden" name="buy_now" value="{{ request('buy_now') ?: session('buy_now_cart_id') }}">
            @endif
            {{-- Idempotency Key: mencegah double order akibat double-click atau koneksi lambat --}}
            <input type="hidden" name="idempotency_key" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
            {{-- Admission Token untuk Tiket War (Single-Use Server Token) --}}
            @php
                $checkoutWarToken = '';
                foreach ($summary['items'] as $itemWarCheck) {
                    if ($itemWarCheck->product && $itemWarCheck->product->isTicketProduct() && !empty($itemWarCheck->product->ticket_war_start)) {
                        $checkoutWarToken = session('war_admission_token_' . $itemWarCheck->product_id, '');
                        break;
                    }
                }
            @endphp
            <input type="hidden" name="admission_token" id="checkout_admission_token_input" value="{{ $checkoutWarToken }}">
            <div class="co-grid">
                <div class="co-left">

                    @guest
                        {{-- 1. TAMPILAN MOBILE (MINIMALIS 1 BARIS) --}}
                        <div class="guest-benefit-mobile"
                            style="background: #F0FDF4; border: 1.5px solid #86EFAC; border-radius: 12px; padding: 0.55rem 0.85rem; margin-bottom: 0.85rem; align-items: center; justify-content: space-between; gap: 0.5rem; box-shadow: 0 2px 6px rgba(30,179,73,0.06); height: 46px; box-sizing: border-box;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 0; flex: 1;">
                                <div
                                    style="width: 26px; height: 26px; border-radius: 7px; background: #DCFCE7; color: #15803D; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"
                                        viewBox="0 0 24 24">
                                        <path d="M20 12v10H4V12" />
                                        <path d="M22 7H2v5h20V7z" />
                                        <path d="M12 22V7" />
                                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" />
                                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" />
                                    </svg>
                                </div>
                                <div
                                    style="font-size: 0.76rem; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.2;">
                                    <strong>Punya akun?</strong> <span style="color: #64748B;">Masuk agar file tersimpan</span>
                                </div>
                            </div>
                            <a href="{{ route('checkout.login', ['redirect' => request()->fullUrl()]) }}"
                                style="padding: 0.38rem 0.75rem; font-size: 0.72rem; font-weight: 700; background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; border-radius: 7px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; flex-shrink: 0; box-shadow: 0 2px 6px rgba(30,179,73,0.2); white-space: nowrap;">
                                <span>Masuk</span>
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        {{-- 2. TAMPILAN DESKTOP (LENGKAP DENGAN BENEFIT CARD) --}}
                        <div class="guest-benefit-desktop"
                            style="background: linear-gradient(135deg, #EFF6FF, #F0FDF4); border: 1.5px solid #6EE7B7; border-radius: 16px; padding: 1.25rem; margin-bottom: 1.5rem; position: relative; overflow: hidden; box-shadow: 0 4px 15px rgba(30,179,73,0.08);">
                            <div
                                style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                                <div style="flex:1; min-width:240px;">
                                    <div
                                        style="display:inline-flex; align-items:center; gap:6px; background:#DCFCE7; color:#15803D; font-size:0.75rem; font-weight:800; padding:0.25rem 0.65rem; border-radius:99px; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.6rem;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                                        </svg>
                                        Opsional (Tanpa Akun)
                                    </div>
                                    <h3
                                        style="font-size:1.05rem; font-weight:800; color:#0F172A; margin:0 0 0.35rem; font-family:var(--font); display:flex; align-items:center; gap:0.4rem;">
                                        Punya Akun = Makin Untung!
                                        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path d="M20 12v10H4V12" />
                                            <path d="M22 7H2v5h20V7z" />
                                            <path d="M12 22V7" />
                                            <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" />
                                            <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" />
                                        </svg>
                                    </h3>
                                    <p style="font-size:0.83rem; color:#475569; line-height:1.5; margin:0 0 0.75rem;">
                                        Login agar <strong>tersimpan selamanya di
                                            Dashboard</strong> & banyak bonusnya!
                                    </p>
                                    <div
                                        style="display:flex; align-items:center; gap:1rem; font-size:0.78rem; font-weight:600; color:#166534; flex-wrap:wrap;">
                                        <span style="display:inline-flex; align-items:center; gap:4px;"><svg width="14"
                                                height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg> Simpan Akses 24/7</span>
                                        <span style="display:inline-flex; align-items:center; gap:4px;"><svg width="14"
                                                height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg> Bebas Ketik Ulang</span>
                                        <span style="display:inline-flex; align-items:center; gap:4px;"><svg width="14"
                                                height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg> Promo Eksklusif</span>
                                    </div>
                                </div>
                                <div
                                    style="display:flex; flex-direction:column; gap:0.4rem; width:100%; max-width:180px; align-self:center;">
                                    <a href="{{ route('checkout.login', ['redirect' => request()->fullUrl()]) }}"
                                        style="display:inline-flex; align-items:center; justify-content:center; gap:0.4rem; background: linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; font-weight:800; font-size:0.85rem; padding:0.7rem 1rem; border-radius:10px; text-decoration:none; text-align:center; box-shadow:0 4px 12px rgba(30,179,73,0.3); transition:all 0.2s;"
                                        onmouseover="this.style.transform='translateY(-2px)'"
                                        onmouseout="this.style.transform='none'">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                            <polyline points="10 17 15 12 10 7" />
                                            <line x1="15" y1="12" x2="3" y2="12" />
                                        </svg>
                                        Masuk / Daftar
                                    </a>
                                    <span
                                        style="font-size:0.72rem; color:#64748B; text-align:center; font-weight:500; display:inline-flex; align-items:center; justify-content:center; gap:3px;">
                                        atau isi form di bawah
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path d="M12 5v14M19 12l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- GUEST CHECKOUT / FORM SIMPEL DIRECT --}}
                        <div class="co-section" style="margin-bottom:1.5rem;">
                            <div class="co-section-title">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                Data Pembeli (Langsung Checkout)
                            </div>

                            {{-- Panduan & Double Check Alert --}}
                            <div
                                style="background:#FFFBEB; border:1px dashed #F59E0B; border-radius:12px; padding:0.85rem 1rem; margin-bottom:1.25rem; display:flex; align-items:flex-start; gap:0.75rem;">
                                <div
                                    style="background:#F59E0B; color:#fff; border-radius:50%; width:24px; height:24px; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                        <line x1="12" y1="9" x2="12" y2="13" />
                                        <line x1="12" y1="17" x2="12.01" y2="17" />
                                    </svg>
                                </div>
                                <div style="font-size:0.82rem; color:#78350F; line-height:1.45;">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nama (Opsional)</label>
                                    <input type="text" name="guest_name" class="form-input" value="{{ old('guest_name') }}"
                                        placeholder="Nama Anda (boleh dikosongkan)">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email (Wajib) <span style="color:#EF4444;">*</span></label>
                                    <input type="email" name="guest_email" class="form-input" value="{{ old('guest_email') }}"
                                        required placeholder="emailaktif@gmail.com">
                                    <div style="font-size:0.75rem;color:#64748B;margin-top:0.35rem;">📩 File/akses digital akan
                                        dikirim ke email ini setelah pembayaran berhasil.</div>
                                </div>
                            </div>
                    @else
                            {{-- LOGGED IN BUYER INFO --}}
                            <div class="co-section" style="margin-bottom:1.5rem;">
                                <div class="co-section-title">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                        <polyline points="22 4 12 14.01 9 11.01" />
                                    </svg>
                                    Data Pembeli (Sudah Login)
                                </div>
                                <div style="font-size:0.95rem; color:#0F172A; font-weight:800;">
                                    {{ auth()->user()->name }} <span
                                        style="font-weight:500; color:#64748B; font-size:0.85rem;">({{ auth()->user()->email }})</span>
                                </div>
                                <div
                                    style="font-size:0.8rem; color:#64748B; margin-top:0.35rem; display:flex; align-items:center; gap:0.4rem;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                    Pesanan Anda akan diproses & tersimpan otomatis di Dashboard Akun Anda.
                                </div>
                            </div>
                        @endguest

                        {{-- CONDITIONAL CHECKOUT FORM BY PRODUCT CATEGORY / TYPE --}}

                        @if($summary['checkout_type'] === 'goods')
                            {{-- A. FORM PENGIRIMAN LENGKAP (PRODUK KATEGORI BARANG) --}}
                            <div class="co-section" style="margin-bottom:1.5rem;">
                                <div class="co-section-title">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                    Alamat Pengiriman (Produk Barang)
                                </div>

                                @if(auth()->check() && $addresses->count() > 0)
                                    <div class="form-group">
                                        <label class="form-label">Pilih Alamat Tersimpan</label>
                                        <select name="address_id" id="address_id_select" class="form-input"
                                            onchange="toggleNewAddress()">
                                            @foreach($addresses as $addr)
                                                <option value="{{ $addr->id }}" data-city="{{ $addr->city }}"
                                                    data-province="{{ $addr->province }}"
                                                    data-full-address="{{ $addr->address }}, {{ $addr->district }}, {{ $addr->city }}, {{ $addr->province }}">
                                                    {{ $addr->label }} - {{ $addr->receiver_name }} ({{ $addr->city }},
                                                    {{ $addr->province }})
                                                </option>
                                            @endforeach
                                            <option value="new">+ Tambah Alamat Baru</option>
                                        </select>
                                    </div>

                                    {{-- City selector for ongkir (shown only when using saved address) --}}
                                    <div id="saved_addr_city_wrap" style="margin-top:0;">
                                        <div
                                            style="background:#FFF9EC;border:1px dashed #F59E0B;border-radius:12px;padding:1rem 1.25rem;">
                                            <p
                                                style="font-size:0.82rem;font-weight:700;color:#92400E;margin:0 0 0.75rem; display:flex; align-items:center; gap:6px;">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path d="M5 18H3a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2" />
                                                    <path d="M19 8h-4v6h4l3 3v-5l-3-3z" />
                                                    <circle cx="7" cy="18" r="2" />
                                                    <circle cx="17" cy="18" r="2" />
                                                </svg>
                                                Pilih Kota Tujuan untuk Kalkulasi Ongkir
                                            </p>
                                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                                                <div>
                                                    <label class="form-label" style="font-size:0.78rem;">Provinsi</label>
                                                    <select id="saved_province_select" class="form-input"
                                                        style="font-size:0.85rem;">
                                                        <option value="">Loading...</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="form-label" style="font-size:0.78rem;">Kota / Kabupaten</label>
                                                    <select id="saved_city_select" class="form-input" style="font-size:0.85rem;">
                                                        <option value="">Pilih Provinsi Dulu</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="hidden" id="saved_city_id" value="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="address_id" id="address_id_select" value="new">
                                @endif

                                <div id="new_address_form"
                                    style="{{ (auth()->check() && $addresses->count() > 0) ? 'display:none;margin-top:1.5rem;padding-top:1.5rem;border-top:1px dashed #E2E8F0;' : '' }}">

                                    <div
                                        style="margin-bottom:1.5rem;background:#F0F9FF;border:1px dashed #bbf7d0;padding:1.25rem;border-radius:12px;display:flex;flex-direction:column;gap:0.75rem;align-items:flex-start;">
                                        <div style="font-size:0.9rem;font-weight:600;color:#15803d;">Opsi Otomatis (Rekomendasi)
                                        </div>
                                        <div style="font-size:0.8rem;color:#0C4A6E;margin-top:-0.5rem;">Izinkan akses GPS untuk
                                            mengisi alamat lengkap Anda secara otomatis (Akurat & Realtime).</div>
                                        <button type="button" id="btn_get_location" onclick="getLocation()"
                                            style="background:#1eb349;color:#fff;border:none;border-radius:8px;padding:0.6rem 1rem;font-size:0.85rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;transition:background 0.2s;">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                                                <circle cx="12" cy="9" r="2.5" />
                                            </svg>
                                            Gunakan Lokasi Saat Ini (GPS)
                                        </button>
                                        <div id="loc_status"
                                            style="font-size:0.75rem;font-weight:600;color:var(--c-muted);display:none;"></div>
                                    </div>

                                    <!-- HIDDEN COORDS -->
                                    <input type="hidden" name="new_address_lat" id="new_addr_lat">
                                    <input type="hidden" name="new_address_lng" id="new_addr_lng">

                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                                        <div class="form-group">
                                            <label class="form-label">Nama Penerima</label>
                                            <input type="text" name="new_address_receiver" id="new_addr_receiver"
                                                class="form-input" placeholder="Nama penerima paket">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">No. Telepon Penerima</label>
                                            <input type="text" name="new_address_phone" id="new_addr_phone" class="form-input"
                                                placeholder="08xxxxxxxxxx">
                                        </div>
                                    </div>

                                    {{-- Indo Regions & RajaOngkir API --}}
                                    <input type="hidden" name="new_address_province" id="province_name">
                                    <input type="hidden" name="new_address_city" id="city_name">
                                    <input type="hidden" name="new_address_district" id="district_name">

                                    <div class="form-group">
                                        <label class="form-label">Provinsi <span style="color:#EF4444;">*</span></label>
                                        <select id="province_select" class="form-input">
                                            <option value="">Loading Provinsi...</option>
                                        </select>
                                    </div>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                                        <div class="form-group">
                                            <label class="form-label">Kota / Kabupaten <span
                                                    style="color:#EF4444;">*</span></label>
                                            <select id="city_select" class="form-input">
                                                <option value="">Pilih Provinsi Dulu</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Kecamatan <span style="color:#EF4444;">*</span></label>
                                            <select id="district_select" class="form-input">
                                                <option value="">Pilih Kota Dulu</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                                        <div class="form-group">
                                            <label class="form-label">Kelurahan / Desa <span
                                                    style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                                            <select id="subdistrict_select" class="form-input">
                                                <option value="">Pilih Kecamatan Dulu</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Kode Pos <span style="color:#EF4444;">*</span></label>
                                            <input type="text" name="new_address_postal" id="new_addr_postal" class="form-input"
                                                placeholder="Kode Pos (contoh: 60111)">
                                        </div>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label class="form-label">Kategori Alamat (Rumah/Kantor)</label>
                                        <select name="new_address_label" id="new_addr_label" class="form-input">
                                            <option value="Rumah">Rumah</option>
                                            <option value="Kantor">Kantor</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-0" style="margin-top:1rem;">
                                        <label class="form-label">Alamat Lengkap (Nama Jalan, Gedung, RT/RW)</label>
                                        <textarea name="new_address_full" id="new_addr_full" class="form-input" rows="3"
                                            placeholder="Masukkan detail alamat lengkap..."></textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- METODE PENGIRIMAN EKSPEDISI BARANG --}}
                            <div class="co-section" style="margin-bottom:1.5rem;">
                                <div class="co-section-title">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <rect x="1" y="3" width="15" height="13" />
                                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                        <circle cx="5.5" cy="18.5" r="2.5" />
                                        <circle cx="18.5" cy="18.5" r="2.5" />
                                    </svg>
                                    Pilih Metode Pengiriman (Kurir)
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label">Pilih Kurir Ekspedisi</label>
                                    <select name="courier_name" id="courier_name_select" class="form-input" required>
                                        <option value="">Pilih kurir pengiriman...</option>
                                        @forelse($couriers ?? [] as $courier)
                                            <option value="{{ $courier->code }}">{{ $courier->name }}</option>
                                        @empty
                                            <option value="jne">JNE Express</option>
                                            <option value="jnt">J&T Express</option>
                                        @endforelse
                                    </select>

                                    <div id="ongkir_loading"
                                        style="display:none; align-items:center; gap:0.5rem; font-size:0.85rem; color:#1eb349; margin-top:1rem; font-weight:600;">
                                        <svg style="animation: spin 1s linear infinite;" width="18" height="18" fill="none"
                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83">
                                            </path>
                                        </svg>
                                        Menghitung ongkir...
                                    </div>

                                    <div id="courier_service_container"
                                        style="margin-top: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                        <!-- Default empty state -->
                                        <div
                                            style="padding: 1rem; border: 1px dashed #CBD5E1; border-radius: 10px; background: #F8FAFC; color: #64748B; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"
                                                viewBox="0 0 24 24">
                                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Pilih kota dan kurir terlebih dahulu untuk melihat opsi pengiriman.
                                        </div>
                                    </div>

                                    <!-- Hidden input required for form submission -->
                                    <input type="hidden" name="courier_service" id="courier_service_hidden" required>
                                </div>
                            </div>

                        @elseif($summary['checkout_type'] === 'food' || $summary['checkout_type'] === 'food_service')
                            {{-- B. FORM KULINER / F&B (RESTO, CAFE, MAKANAN & MINUMAN) --}}
                            <div class="co-section" style="margin-bottom:1.5rem;">
                                <div class="co-section-title">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                    </svg>
                                    Pilihan Layanan & Pengantaran (Kuliner / FnB)
                                </div>
                                <div style="font-size:0.82rem; color:#64748B; margin-bottom:1.25rem; line-height:1.45;">
                                    Pilih jenis layanan pesanan makanan/minuman Anda:
                                </div>

                                <div class="form-group">
                                    <label class="form-label" style="font-weight:700; color:#0F172A;">Opsi Pesanan / Pengantaran
                                        <span style="color:#EF4444;">*</span></label>
                                    <select name="fnb_service_type" id="fnb_service_type_select" class="form-input"
                                        onchange="toggleFnbFields(this.value)" required>
                                        <option value="dine_in">Makan di Tempat (Dine-In / Antar ke Meja)</option>
                                        <option value="delivery" selected>Delivery / Antar ke Alamat</option>
                                        <option value="takeaway">Takeaway / Ambil Sendiri di Toko</option>
                                    </select>
                                </div>

                                <div id="fnb_field_dine_in" style="display:none;" class="form-group mb-0">
                                    <label class="form-label" style="font-weight:700;">Nomor Meja / Area Duduk <span
                                            style="color:#EF4444;">*</span></label>
                                    <input type="text" name="fnb_table_number" id="fnb_table_number_input" class="form-input"
                                        placeholder="Contoh: Meja No. 05 / Area Outdoor lantai 2">
                                </div>

                                <div id="fnb_field_delivery" class="form-group mb-0">
                                    <label class="form-label" style="font-weight:700;">Alamat Pengantaran Lengkap & Patokan
                                        <span style="color:#EF4444;">*</span></label>
                                    <textarea name="new_address_full" id="fnb_delivery_address_input" class="form-input"
                                        rows="3"
                                        placeholder="Contoh: Jl. Raya Kebon Jeruk No. 12 (Seberang Indomaret, pagar hitam, rumah cat hijau)..."></textarea>
                                </div>

                                <div id="fnb_field_takeaway" style="display:none;" class="form-group mb-0">
                                    <label class="form-label" style="font-weight:700;">Estimasi Jam Pengambilan /
                                        Pick-Up</label>
                                    <input type="text" name="fnb_pickup_time" id="fnb_pickup_time_input" class="form-input"
                                        placeholder="Contoh: Diambil jam 13:30 WIB / Ditinggal di meja kasir">
                                </div>
                            </div>
                        @else
                            {{-- C. FORM DIGITAL / TICKETING / JASA --}}

                            {{-- ============================================================ --}}
                            {{-- Card: Atas Nama Tiket — Support Multi-Qty, Anti-Konflik --}}
                            {{-- Nama field: ticket_holders[{product_id}][] (array per produk) --}}
                            {{-- Tampil HANYA jika ada produk bertipe ticket / tiket / event --}}
                            {{-- ============================================================ --}}
                            @php
                                $ticketItems = collect($summary['items'])->filter(function ($item) {
                                    $pType = strtolower($item->product->product_type ?? $item->product->type ?? '');
                                    return in_array($pType, ['ticket', 'tiket', 'event']);
                                });
                            @endphp

                            @if($ticketItems->count() > 0)
                                @php
                                    $authUserName = auth()->check() ? auth()->user()->name : '';
                                    $authUserEmail = auth()->check() ? auth()->user()->email : '';
                                    $ticketSlotCounter = 0; /* global slot index across all items */
                                @endphp

                                <div class="co-section" style="margin-bottom:1.5rem;">
                                    <div class="co-section-title">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <rect x="2" y="7" width="20" height="14" rx="2" />
                                            <path d="M16 3l-4 4-4-4" />
                                            <path d="M12 11v6" />
                                        </svg>
                                        Atas Nama Pemegang Tiket
                                    </div>

                                    {{-- Info bar --}}
                                    <div
                                        style="background:#EFF6FF;border:1px dashed #93C5FD;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1.25rem;display:flex;align-items:flex-start;gap:0.6rem;">
                                        <svg width="15" height="15" fill="none" stroke="#2563EB" stroke-width="2.5"
                                            viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" y1="8" x2="12" y2="12" />
                                            <line x1="12" y1="16" x2="12.01" y2="16" />
                                        </svg>
                                        <span style="font-size:0.78rem;color:#1D4ED8;line-height:1.5;">
                                            Isi nama lengkap &amp; identitas resmi (KTP, SIM, Paspor, atau Kartu Pelajar)
                                            <strong>setiap
                                                pemegang tiket</strong> untuk validasi di pintu masuk.
                                            @if(auth()->check()) Tiket pertama diisi otomatis dari profil akun Anda. @endif
                                        </span>
                                    </div>

                                    {{-- Loop setiap item tiket × qty --}}
                                    @foreach($ticketItems as $item)
                                        @php
                                            $pid = $item->product_id ?? ($item->product->id ?? 0);
                                            $pname = $item->product->name ?? 'Produk';
                                            $qty = (int) ($item->qty ?? 1);
                                            $oldVals = old("ticket_holders.{$pid}", []);
                                            $oldIdTypes = old("ticket_id_types.{$pid}", []);
                                            $oldNiks = old("ticket_niks.{$pid}", []);
                                        @endphp

                                        @for($ticketIdx = 0; $ticketIdx < $qty; $ticketIdx++)
                                            @php
                                                $isFirstSlot = ($ticketSlotCounter === 0);
                                                $slotLabel = ($qty > 1 || $ticketItems->count() > 1)
                                                    ? "{$pname} — Tiket #" . ($ticketIdx + 1)
                                                    : "Pemegang Tiket";
                                                $defaultVal = $isFirstSlot && auth()->check() ? $authUserName : ($oldVals[$ticketIdx] ?? '');
                                                $defaultIdType = $oldIdTypes[$ticketIdx] ?? 'KTP';
                                                $defaultNik = $oldNiks[$ticketIdx] ?? '';

                                                // Otomatis isi NIK jika pengguna sudah login dan punya NIK tersimpan / pernah beli tiket
                                                if ($isFirstSlot && auth()->check() && empty($defaultNik)) {
                                                    $userAutoNik = auth()->user()->resolved_nik ?? auth()->user()->nik;
                                                    if (!empty($userAutoNik)) {
                                                        $defaultNik = $userAutoNik;
                                                        $defaultIdType = 'KTP';
                                                    }
                                                }
                                                $inputId = "ticket_holder_{$pid}_{$ticketIdx}";
                                                $typeId = "ticket_id_type_{$pid}_{$ticketIdx}";
                                                $nikId = "ticket_nik_{$pid}_{$ticketIdx}";
                                                $ticketSlotCounter++;
                                            @endphp

                                            <div class="ticket-holder-row"
                                                style="margin-bottom:{{ $ticketIdx < $qty - 1 ? '1rem' : '0' }};
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            {{ !$loop->last || $ticketIdx < $qty - 1 ? 'padding-bottom:1rem;border-bottom:1px dashed #E2E8F0;' : '' }}">

                                                {{-- Slot label dengan nomor tiket --}}
                                                <div
                                                    style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
                                                    <label for="{{ $inputId }}"
                                                        style="font-size:0.82rem;font-weight:700;color:#334155;display:flex;align-items:center;gap:0.4rem;">
                                                        <span
                                                            style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:{{ $isFirstSlot ? 'linear-gradient(135deg,#1eb349,#a5cf37)' : '#E2E8F0' }};color:{{ $isFirstSlot ? '#fff' : '#64748B' }};font-size:0.72rem;font-weight:800;flex-shrink:0;">
                                                            {{ $ticketSlotCounter }}
                                                        </span>
                                                        {{ $slotLabel }}
                                                        <span style="color:#EF4444;">*</span>
                                                    </label>

                                                    {{-- Toggle hanya untuk slot pertama jika login --}}
                                                    @if($isFirstSlot && auth()->check())
                                                        <button type="button" id="toggle-slot-{{ $inputId }}"
                                                            onclick="toggleTicketSlot('{{ $inputId }}', '{{ addslashes($authUserName) }}')"
                                                            style="font-size:0.72rem;font-weight:700;color:#1eb349;background:none;border:none;cursor:pointer;padding:0.2rem 0.5rem;border-radius:6px;border:1px solid #BBF7D0;background:#F0FDF4;display:flex;align-items:center;gap:0.3rem;white-space:nowrap;">
                                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"
                                                                viewBox="0 0 24 24">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                            </svg>
                                                            <span id="toggle-label-{{ $inputId }}">Ganti Nama</span>
                                                        </button>
                                                    @endif
                                                </div>

                                                {{-- Input field --}}
                                                {{-- Input Nama --}}
                                                <input type="text" id="{{ $inputId }}" name="ticket_holders[{{ $pid }}][]"
                                                    class="form-input" value="{{ $defaultVal }}" required
                                                    placeholder="{{ $isFirstSlot && auth()->check() ? 'Nama dari akun Anda (bisa diganti)' : 'Nama lengkap pemegang tiket #' . ($ticketIdx + 1) . '...' }}"
                                                    {{ $isFirstSlot && auth()->check() ? 'data-auth-name="' . e($authUserName) . '"' : '' }}
                                                    style="{{ $isFirstSlot && auth()->check() ? 'background:#F8FAFC;' : '' }}">

                                                {{-- Field Identitas Resmi (Dropdown Premium + Input Nomor) --}}
                                                <div style="margin-top:0.65rem;">
                                                    <label
                                                        style="font-size:0.78rem;font-weight:700;color:#334155;display:flex;align-items:center;gap:0.35rem;margin-bottom:0.35rem;">
                                                        <svg width="13" height="13" fill="none" stroke="#1eb349" stroke-width="2.5"
                                                            viewBox="0 0 24 24">
                                                            <rect x="3" y="4" width="18" height="16" rx="2" />
                                                            <path d="M7 8h10M7 12h6M7 16h4" />
                                                        </svg>
                                                        Identitas Resmi Pemegang Tiket
                                                        <span style="color:#EF4444;">*</span>
                                                    </label>

                                                    <div class="ticket-identity-grid">
                                                        {{-- Dropdown Jenis Identitas --}}
                                                        <div>
                                                            <select id="{{ $typeId }}" name="ticket_id_types[{{ $pid }}][]"
                                                                class="ticket-id-type-select"
                                                                onchange="updateIdentitySlot('{{ $pid }}_{{ $ticketIdx }}')">
                                                                <option value="KTP" {{ $defaultIdType === 'KTP' ? 'selected' : '' }}>KTP (NIK)
                                                                </option>
                                                                <option value="SIM" {{ $defaultIdType === 'SIM' ? 'selected' : '' }}>SIM
                                                                </option>
                                                                <option value="Paspor" {{ $defaultIdType === 'Paspor' ? 'selected' : '' }}>
                                                                    Paspor
                                                                </option>
                                                                <option value="Kartu Pelajar" {{ $defaultIdType === 'Kartu Pelajar' ? 'selected' : '' }}>Kartu Pelajar / KTM</option>
                                                                <option value="Lainnya" {{ $defaultIdType === 'Lainnya' ? 'selected' : '' }}>
                                                                    Lainnya</option>
                                                            </select>
                                                        </div>

                                                        {{-- Input Nomor Identitas --}}
                                                        <div>
                                                            <input type="text" id="{{ $nikId }}" name="ticket_niks[{{ $pid }}][]"
                                                                class="ticket-id-number-input" value="{{ $defaultNik }}" required
                                                                placeholder="{{ $defaultIdType === 'KTP' ? 'Nomor KTP (16 digit angka)...' : ($defaultIdType === 'SIM' ? 'Nomor SIM...' : ($defaultIdType === 'Paspor' ? 'Nomor Paspor (Passport No)...' : ($defaultIdType === 'Kartu Pelajar' ? 'Nomor Kartu Pelajar / KTM...' : 'Nomor identitas resmi...'))) }}"
                                                                {{ $defaultIdType === 'KTP' ? 'inputmode="numeric"' : '' }} autocomplete="off"
                                                                oninput="if(document.getElementById('{{ $typeId }}')?.value === 'KTP'){ this.value=this.value.replace(/\D/g,'').slice(0,16); }">
                                                        </div>
                                                    </div>

                                                    <div
                                                        style="font-size:0.71rem;color:#94A3B8;margin-top:0.35rem;display:flex;align-items:center;gap:0.35rem;line-height:1.35;">
                                                        <svg width="11" height="11" fill="none" stroke="#1eb349" stroke-width="2"
                                                            viewBox="0 0 24 24" style="flex-shrink:0;">
                                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                                        </svg>
                                                        <span>Data identitas dienkripsi &amp; disimpan aman untuk verifikasi e-tiket saat
                                                            masuk
                                                            ke venue.</span>
                                                    </div>
                                                </div>

                                                {{-- ═══ DYNAMIC CUSTOM FIELDS PER EVENT ═══ --}}
                                                @php
                                                    $customFields = $item->product->custom_fields ?? [];
                                                    $extraOld = old("ticket_extra.{$pid}.{$ticketIdx}", []);
                                                @endphp
                                                @if(!empty($customFields) && is_array($customFields))
                                                    <div
                                                        style="margin-top:0.75rem;padding:0.75rem 0.85rem;background:#F0FDF4;border-radius:10px;border:1px solid #BBF7D0;">
                                                        <div
                                                            style="font-size:0.75rem;font-weight:700;color:#166534;margin-bottom:0.55rem;display:flex;align-items:center;gap:0.35rem;">
                                                            <svg width="13" height="13" fill="none" stroke="#166534" stroke-width="2.2"
                                                                viewBox="0 0 24 24">
                                                                <path d="M9 11l3 3L22 4" />
                                                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                                                            </svg>
                                                            Data Tambahan Peserta
                                                        </div>
                                                        <div
                                                            style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:0.6rem;">
                                                            @foreach($customFields as $cf)
                                                                @php
                                                                    $fLabel = trim($cf['label'] ?? '');
                                                                    $fType = $cf['type'] ?? 'text';
                                                                    $fOptions = $cf['options'] ?? '';
                                                                    $fReq = !empty($cf['required']);
                                                                    $valOld = $extraOld[$fLabel] ?? '';
                                                                    $isFull = ($fType === 'textarea' || strlen($fLabel) > 28);
                                                                @endphp
                                                                @if(!empty($fLabel))
                                                                    <div style="{{ $isFull ? 'grid-column: 1 / -1;' : '' }}">
                                                                        <label
                                                                            style="font-size:0.73rem;font-weight:700;color:#334155;display:block;margin-bottom:0.25rem;">
                                                                            {{ $fLabel }} @if($fReq)<span style="color:#EF4444;">*</span>@else<span
                                                                            style="font-weight:400;color:#94A3B8;">(opsional)</span>@endif
                                                                        </label>

                                                                        @if($fType === 'select')
                                                                            @php
                                                                                $optList = array_filter(array_map('trim', explode(',', $fOptions)));
                                                                            @endphp
                                                                            <select name="ticket_extra[{{ $pid }}][{{ $ticketIdx }}][{{ $fLabel }}]"
                                                                                class="form-input" style="font-size:0.82rem;padding:0.45rem 0.6rem;" {{ $fReq ? 'required' : '' }}>
                                                                                <option value="">— Pilih —</option>
                                                                                @foreach($optList as $opt)
                                                                                    <option value="{{ $opt }}" {{ $valOld === $opt ? 'selected' : '' }}>{{ $opt }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        @elseif($fType === 'date')
                                                                            <input type="date"
                                                                                name="ticket_extra[{{ $pid }}][{{ $ticketIdx }}][{{ $fLabel }}]"
                                                                                class="form-input" style="font-size:0.82rem;padding:0.45rem 0.6rem;"
                                                                                value="{{ $valOld }}" {{ $fReq ? 'required' : '' }}>
                                                                        @elseif($fType === 'number')
                                                                            <input type="number"
                                                                                name="ticket_extra[{{ $pid }}][{{ $ticketIdx }}][{{ $fLabel }}]"
                                                                                class="form-input" style="font-size:0.82rem;padding:0.45rem 0.6rem;"
                                                                                placeholder="0" value="{{ $valOld }}" {{ $fReq ? 'required' : '' }}>
                                                                        @elseif($fType === 'textarea')
                                                                            <textarea name="ticket_extra[{{ $pid }}][{{ $ticketIdx }}][{{ $fLabel }}]"
                                                                                class="form-input" style="font-size:0.82rem;padding:0.45rem 0.6rem;"
                                                                                rows="2" placeholder="Isi {{ strtolower($fLabel) }}" {{ $fReq ? 'required' : '' }}>{{ $valOld }}</textarea>
                                                                        @else
                                                                            <input type="text"
                                                                                name="ticket_extra[{{ $pid }}][{{ $ticketIdx }}][{{ $fLabel }}]"
                                                                                class="form-input" style="font-size:0.82rem;padding:0.45rem 0.6rem;"
                                                                                placeholder="Isi {{ strtolower($fLabel) }}" value="{{ $valOld }}" {{ $fReq ? 'required' : '' }}>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                {{-- ═══ END DYNAMIC CUSTOM FIELDS ═══ --}}

                                                @if(!$isFirstSlot)
                                                    <div style="font-size:0.72rem;color:#94A3B8;margin-top:0.3rem;">Nama sesuai identitas resmi
                                                        pemegang tiket ini.</div>
                                                @endif
                                            </div>
                                        @endfor
                                    @endforeach

                                </div>
                            @endif
                        @endif
                        {{-- END: Conditional Checkout Form by Product Type --}}

                        @if($hasFreeRequirements)
                            {{-- SYARAT FOLLOW / KLAIM PRODUK & TIKET --}}
                            <div class="co-section" id="freeClaimRequirementsSection"
                                style="margin-bottom:1.5rem; border: 1.5px solid #86EFAC; background: #FAFCF8;">
                                <div class="co-section-title" style="color: #15803D; margin-bottom: 0.5rem;">
                                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2"
                                        viewBox="0 0 24 24">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                        <polyline points="9 12 11 14 15 10" />
                                    </svg>
                                    {{ $summary['subtotal'] > 0 ? 'Syarat Follow & Join Penyelenggara' : 'Syarat Klaim Gratis' }}
                                    ({{ count($claimSteps) }})
                                    @if($requiresScreenshot)
                                        <span
                                            style="margin-left:0.5rem;background:#DCFCE7;color:#15803D;font-size:0.65rem;font-weight:800;padding:2px 8px;border-radius:999px;border:1px solid #86EFAC;letter-spacing:0.3px;">Wajib</span>
                                    @endif
                                </div>
                                <p style="font-size: 0.82rem; color: #475569; margin: 0 0 1rem; line-height: 1.45;">
                                    Silakan selesaikan syarat di bawah ini untuk membuka akses:
                                </p>

                                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                                    @foreach($claimSteps as $step)
                                        <div class="claim-step" id="step_{{ $step['step_id'] }}" data-type="timer"
                                            data-step-key="{{ $step['step_id'] }}"
                                            style="background:#fff; border:1.5px solid #E2E8F0; border-radius:12px; padding:0.85rem 1rem; transition:all 0.25s; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                                            <div
                                                style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; flex-wrap:wrap;">
                                                <div
                                                    style="display:flex; align-items:center; gap:0.75rem; min-width:200px; flex:1;">
                                                    <div id="ico_{{ $step['step_id'] }}"
                                                        style="width:38px; height:38px; border-radius:10px; background:#F1F5F9; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all 0.3s;">
                                                        @if($step['icon'] === 'instagram')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#E1306C"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                                            </svg>
                                                        @elseif($step['icon'] === 'tiktok')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#000000">
                                                                <path
                                                                    d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.29 0 .57.04.84.11V9.41a6.33 6.33 0 0 0-.84-.06 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V9.05a8.27 8.27 0 0 0 4.77 1.52V7.12a4.85 4.85 0 0 1-1-.43z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'x')
                                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#000000">
                                                                <path
                                                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'youtube')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#FF0000">
                                                                <path
                                                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'whatsapp')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#25D366">
                                                                <path
                                                                    d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'telegram')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#0088cc">
                                                                <path
                                                                    d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.96 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'discord')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#5865F2">
                                                                <path
                                                                    d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994.021-.041.001-.09-.041-.106a13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.929 1.793 8.18 1.793 12.061 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.894.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.028zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'threads')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#000000">
                                                                <path
                                                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm3.87 14.54c-.66.86-1.57 1.31-2.7 1.31-2.06 0-3.47-1.42-3.47-3.51 0-2.28 1.62-3.76 3.8-3.76 1.83 0 3.03 1.05 3.28 2.76h-1.52c-.22-.97-.93-1.46-1.84-1.46-1.28 0-2.15.93-2.15 2.42 0 1.39.81 2.3 2.05 2.3.83 0 1.43-.37 1.82-.95l.73.89z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'facebook')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877F2">
                                                                <path
                                                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'linkedin')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#0A66C2">
                                                                <path
                                                                    d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'spotify')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1DB954">
                                                                <path
                                                                    d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'github')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#24292e">
                                                                <path
                                                                    d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z" />
                                                            </svg>
                                                        @elseif($step['icon'] === 'pinterest')
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#BD081C">
                                                                <path
                                                                    d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345-.09.375-.291 1.199-.334 1.357-.057.24-.19.29-.44.175-1.649-.767-2.678-3.175-2.678-5.11 0-4.157 3.023-7.978 8.718-7.978 4.577 0 8.134 3.262 8.134 7.623 0 4.549-2.868 8.211-6.849 8.211-1.337 0-2.594-.696-3.025-1.516l-.824 3.143c-.298 1.146-1.104 2.582-1.644 3.452A11.966 11.966 0 0 0 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" />
                                                            </svg>
                                                        @else
                                                            <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2"
                                                                viewBox="0 0 24 24">
                                                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                                                <path
                                                                    d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div style="font-size:0.875rem; font-weight:700; color:#0F172A;">
                                                            {{ $step['label'] }}
                                                        </div>
                                                        <div style="font-size:0.75rem; color:#64748B;">{{ $step['sublabel'] }}</div>
                                                    </div>
                                                </div>

                                                <div style="display:flex; align-items:center; gap:0.5rem; flex-shrink:0;">
                                                    <span id="timer_{{ $step['step_id'] }}"
                                                        style="display:none; font-size:0.75rem; font-weight:700; color:#B45309; background:#FEF3C7; padding:0.35rem 0.65rem; border-radius:8px;">
                                                        <span id="timer_{{ $step['step_id'] }}_txt">Verifikasi dalam 5
                                                            detik...</span>
                                                    </span>

                                                    @if($requiresScreenshot)
                                                        {{-- UPLOAD BUKTI SCREENSHOT PER SOSMED (di sebelah kiri button action) --}}
                                                        <div class="step-proof-slot" id="slot_{{ $step['step_id'] }}"
                                                            style="display:inline-flex; align-items:center;">
                                                            <input type="file" id="file_{{ $step['step_id'] }}" accept="image/*"
                                                                capture="environment" style="display:none;"
                                                                onchange="handleStepFileChange(this, '{{ $step['step_id'] }}')">

                                                            <button type="button" onclick="triggerStepUpload('{{ $step['step_id'] }}')"
                                                                id="btn_upload_{{ $step['step_id'] }}"
                                                                title="Upload bukti screenshot follow / join untuk {{ $step['label'] }}"
                                                                style="padding:0.42rem 0.65rem; border-radius:8px; font-size:0.75rem; font-weight:600; background:#F0FDF4; border:1.5px dashed #86EFAC; color:#15803D; cursor:pointer; display:inline-flex; align-items:center; gap:4px; white-space:nowrap; transition:all 0.2s;">
                                                                <svg width="13" height="13" fill="none" stroke="currentColor"
                                                                    stroke-width="2" viewBox="0 0 24 24">
                                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                                    <polyline points="17 8 12 3 7 8" />
                                                                    <line x1="12" y1="3" x2="12" y2="15" />
                                                                </svg>
                                                                <span>Upload Bukti</span>
                                                            </button>

                                                            <div id="preview_{{ $step['step_id'] }}"
                                                                style="display:none; align-items:center; gap:5px;">
                                                                <div style="position:relative; width:34px; height:34px; flex-shrink:0;">
                                                                    <img id="thumb_{{ $step['step_id'] }}" src="" alt="Bukti"
                                                                        style="width:34px; height:34px; object-fit:cover; border-radius:7px; border:1.5px solid #16A34A; cursor:pointer; display:block;"
                                                                        onclick="triggerStepUpload('{{ $step['step_id'] }}')"
                                                                        title="Klik untuk ganti bukti">
                                                                    <span
                                                                        style="position:absolute; top:-4px; right:-4px; width:14px; height:14px; background:#16A34A; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:900; box-shadow:0 1px 3px rgba(0,0,0,0.2);">✓</span>
                                                                </div>
                                                                <button type="button" onclick="clearStepProof('{{ $step['step_id'] }}')"
                                                                    title="Hapus bukti"
                                                                    style="border:none; background:transparent; color:#EF4444; font-size:16px; cursor:pointer; padding:0 2px; line-height:1;">&times;</button>
                                                            </div>

                                                            <input type="hidden" name="claim_proof_screenshots[{{ $step['step_id'] }}]"
                                                                id="proof_data_{{ $step['step_id'] }}" class="step-proof-data"
                                                                data-step-id="{{ $step['step_id'] }}"
                                                                data-platform="{{ $step['platform'] }}"
                                                                data-label="{{ $step['label'] }}" value="">
                                                        </div>
                                                    @endif

                                                    <a href="{{ $step['url'] }}" target="_blank" rel="noopener noreferrer"
                                                        onclick="startTimer('{{ $step['step_id'] }}')"
                                                        id="btn_{{ $step['step_id'] }}"
                                                        style="padding:0.45rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:700; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; box-shadow:0 2px 8px rgba(30,179,73,0.25); white-space:nowrap;">
                                                        <span>{{ $step['action'] }}</span>
                                                        <svg width="12" height="12" fill="none" stroke="currentColor"
                                                            stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                                            <polyline points="15 3 21 3 21 9" />
                                                            <line x1="10" y1="14" x2="21" y2="3" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if($requiresScreenshot)
                                    <div
                                        style="margin-top:0.85rem; padding:0.6rem 0.85rem; background:#F0FDF4; border-radius:10px; border:1px solid #BBF7D0; display:flex; align-items:center; gap:8px;">
                                        <svg width="16" height="16" fill="none" stroke="#15803D" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" y1="8" x2="12" y2="12" />
                                            <line x1="12" y1="16" x2="12.01" y2="16" />
                                        </svg>
                                        <span style="font-size:0.75rem; color:#15803D; font-weight:600;">Wajib upload 1 screenshot
                                            bukti follow/join untuk tiap platform di atas (tombol Upload Bukti di kiri tombol
                                            aksi).</span>
                                    </div>
                                @endif

                            </div>
                        @endif

                        {{-- Card: Info Pengiriman Digital --}}
                        @if($summary['checkout_type'] === 'digital')
                            <div class="co-section" style="margin-bottom:1.5rem;background:#F0FDF4;border:1px solid #BBF7D0;">
                                <div class="co-section-title" style="color:#15803D;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                        <polyline points="13 2 13 9 20 9"></polyline>
                                    </svg>
                                    Terkirim Otomatis
                                </div>
                                <div style="font-size:0.85rem;color:#166534;line-height:1.5;">
                                    Akses file digital dan e-tiket, Anda akan otomatis dikirimkan langsung ke
                                    <strong>Email</strong>
                                    Anda setelah pembayaran berhasil.
                                </div>
                            </div>
                        @endif

                        @if($summary['checkout_type'] !== 'digital')
                            {{-- CATATAN PESANAN (Hanya untuk produk barang fisik / makanan, di-hide untuk digital) --}}
                            <div class="co-section">
                                <div class="co-section-title">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                    Catatan Pesanan (Opsional)
                                </div>
                                <div class="form-group mb-0">
                                    <textarea name="notes" id="notes_input" class="form-input" rows="2"
                                        placeholder="Tuliskan pesan atau instruksi tambahan jika ada..."></textarea>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="notes" id="notes_input" value="">
                        @endif
                    </div>

                    <div class="co-right">

                        {{-- ══ COUNTDOWN TIMER CARD ══ --}}
                        <div id="checkout-countdown-card" style="
                                                                        background: linear-gradient(135deg, #1eb349 0%, #17963d 60%, #0f7a30 100%);
                                                                        border-radius: 14px;
                                                                        padding: 0;
                                                                        margin-bottom: 14px;
                                                                        overflow: hidden;
                                                                        box-shadow: 0 4px 18px rgba(30,179,73,0.22);
                                                                    ">
                            <div style="display:flex;align-items:center;gap:0;height:52px;">
                                {{-- Time block --}}
                                <div style="
                                                                                display:flex;
                                                                                align-items:center;
                                                                                justify-content:center;
                                                                                padding: 0 20px;
                                                                                height:100%;
                                                                                border-right: 1px solid rgba(255,255,255,0.22);
                                                                                min-width: 90px;
                                                                            ">
                                    <span id="checkout-timer-display" style="
                                                                                    font-size: 1.35rem;
                                                                                    font-weight: 700;
                                                                                    color: #fff;
                                                                                    letter-spacing: 2px;
                                                                                    font-variant-numeric: tabular-nums;
                                                                                    font-family: 'Courier New', monospace;
                                                                                ">10:00</span>
                                </div>
                                {{-- Label block --}}
                                <div style="
                                                                                display:flex;
                                                                                align-items:center;
                                                                                gap: 8px;
                                                                                padding: 0 18px;
                                                                                flex:1;
                                                                            ">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="rgba(255,255,255,0.85)" stroke-width="2.2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span style="font-size:0.875rem;color:rgba(255,255,255,0.92);font-weight:500;">Batas
                                        Waktu
                                        Tersisa</span>
                                </div>
                            </div>
                            {{-- Progress bar --}}
                            <div style="height:3px;background:rgba(255,255,255,0.18);position:relative;">
                                <div id="checkout-timer-bar" style="
                                                                                height:100%;
                                                                                width:100%;
                                                                                background:rgba(255,255,255,0.65);
                                                                                transition: width 1s linear;
                                                                                border-radius:0 0 14px 14px;
                                                                            "></div>
                            </div>
                        </div>
                        <script>
                            (function () {
                                var TOTAL = 10 * 60; // 10 minutes in seconds
                                var storageKey = 'buyle_checkout_timer_end';
                                var now = Math.floor(Date.now() / 1000);
                                var endTime;

                                // If timer exists and hasn't expired, resume it
                                var stored = sessionStorage.getItem(storageKey);
                                if (stored && parseInt(stored) > now) {
                                    endTime = parseInt(stored);
                                } else {
                                    endTime = now + TOTAL;
                                    sessionStorage.setItem(storageKey, endTime);
                                }

                                var display = document.getElementById('checkout-timer-display');
                                var bar = document.getElementById('checkout-timer-bar');
                                var card = document.getElementById('checkout-countdown-card');

                                function update() {
                                    var remaining = Math.max(0, endTime - Math.floor(Date.now() / 1000));
                                    var pct = (remaining / TOTAL) * 100;
                                    var m = Math.floor(remaining / 60);
                                    var s = remaining % 60;
                                    display.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                                    bar.style.width = pct + '%';

                                    // Turn warning style when < 2 min
                                    if (remaining <= 120) {
                                        card.style.background = 'linear-gradient(135deg, #e53e3e 0%, #c53030 100%)';
                                        card.style.boxShadow = '0 4px 18px rgba(229,62,62,0.28)';
                                    }

                                    if (remaining <= 0) {
                                        display.textContent = '00:00';
                                        bar.style.width = '0%';
                                        sessionStorage.removeItem(storageKey);
                                        clearInterval(timerInterval);
                                        // Redirect to cart after short delay
                                        setTimeout(function () {
                                            window.location.href = '{{ route("cart.index") }}';
                                        }, 1500);
                                    }
                                }

                                update();
                                var timerInterval = setInterval(update, 1000);
                            })();
                        </script>
                        {{-- ══ END COUNTDOWN ══ --}}

                        <div class="co-section" style="position:sticky;top:100px;">
                            <div class="co-section-title">Ringkasan Pesanan</div>

                            <div style="margin-bottom:1.5rem;">
                                @foreach($summary['items'] as $item)
                                    <div class="summary-item">
                                        @if($item->product && !empty($item->product->image))
                                            @php
                                                $img = $item->product->image;
                                                $imgUrl = \Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])
                                                    ? $img
                                                    : (\Illuminate\Support\Str::startsWith($img, ['storage/', '/storage/'])
                                                        ? asset(ltrim($img, '/'))
                                                        : asset('storage/' . ltrim($img, '/')));
                                            @endphp
                                            <img src="{{ $imgUrl }}" class="summary-img" alt="{{ $item->product->name ?? '' }}">
                                        @else
                                            <div class="summary-img"
                                                style="display:flex;align-items:center;justify-content:center;color:#94A3B8;">
                                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"
                                                    viewBox="0 0 24 24">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                                    <polyline points="21 15 16 10 5 21" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div style="flex:1;">
                                            <div class="summary-title">{{ $item->product->name ?? 'Produk Telah Dihapus' }}
                                            </div>
                                            @if($item->product && $item->product->isTraktir())
                                                <div
                                                    style="font-size:0.75rem; color:#d97706; font-weight:700; margin-bottom:0.2rem; display:inline-flex; align-items:center; gap:4px; background:#fef3c7; padding:2px 8px; border-radius:6px;">
                                                    <span>{{ $item->product->traktir_unit_icon ?? '☕' }}</span>
                                                    <span>Traktir {{ $item->product->traktir_unit_name ?? 'Kopi' }}</span>
                                                </div>
                                            @elseif($item->product && $item->product->isPayWhatYouWant())
                                                <div
                                                    style="font-size:0.75rem; color:#0284c7; font-weight:700; margin-bottom:0.2rem; display:inline-flex; align-items:center; gap:4px; background:#e0f2fe; padding:2px 8px; border-radius:6px;">
                                                    <span>💛</span>
                                                    <span>Dukungan Seikhlasnya</span>
                                                </div>
                                            @endif
                                            @if($item->variantValue)
                                                <div
                                                    style="font-size:0.75rem; color:#1eb349; font-weight:600; margin-bottom:0.2rem; display:inline-flex; align-items:center; gap:3px;">
                                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5"
                                                        viewBox="0 0 24 24">
                                                        <polyline points="20 6 9 17 4 12" />
                                                    </svg>
                                                    {{ $item->variantValue->variantOption->name ?? 'Varian' }}:
                                                    {{ $item->variantValue->value }}
                                                </div>
                                            @endif
                                            <div class="summary-meta">{{ $item->qty }} x Rp
                                                {{ number_format($item->unit_price, 0, ',', '.') }}
                                            </div>
                                            <div class="summary-price mt-1">Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <input type="hidden" name="shipping_cost" value="0" id="shipping_cost_input">

                            <div class="summary-row">
                                <span>Total Harga Produk</span>
                                <span>Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</span>
                            </div>

                            @php
                                // Fee tiket: baca setting global (default 0 jika sudah diset 0 di admin)
                                $ticketAdminFeeFlat = (float) (\App\Models\Setting::get('ticket_admin_fee_per_qty', 0));
                                $ticketPlatformFeeRate = (float) (\App\Models\Setting::get('ticket_platform_fee_rate', 0));
                                $pfRate = (float) (\App\Models\Setting::get('platform_fee_rate', 5));
                                $afRate = (float) (\App\Models\Setting::get('admin_fee_rate', 5));
                                $pfAmt = 0;
                                $afAmt = 0;
                                $ticketTaxTotal = 0;
                                $ticketTaxesBreakdown = [];
                                $hasTicketItem = false;
                                $hasNonTicketItem = false;
                                foreach ($summary['items'] as $ci) {
                                    $pt = $ci->product->product_type ?? $ci->product->type ?? '';
                                    $isTicket = (method_exists($ci->product, 'isTicketProduct') && $ci->product->isTicketProduct()) || $pt === 'ticket';
                                    $sellerProfile = $ci->product->seller?->creatorProfile
                                        ?? \App\Models\CreatorProfile::where('user_id', $ci->product->seller_id)->first();
                                    if ($isTicket) {
                                        $hasTicketItem = true;
                                        if (!empty($ci->product->tax_enabled)) {
                                            $taxesList = $ci->product->ticket_taxes_list ?? [];
                                            if (!empty($taxesList) && is_array($taxesList)) {
                                                foreach ($taxesList as $taxItem) {
                                                    $rate = (float) ($taxItem['rate'] ?? 0);
                                                    $name = trim($taxItem['name'] ?? 'Pajak Daerah / Hiburan');
                                                    if ($rate > 0) {
                                                        $itemTax = round(($ci->subtotal * $rate) / 100);
                                                        $ticketTaxTotal += $itemTax;
                                                        if (!isset($ticketTaxesBreakdown[$name])) {
                                                            $ticketTaxesBreakdown[$name] = ['rate' => $rate, 'amount' => 0];
                                                        }
                                                        $ticketTaxesBreakdown[$name]['amount'] += $itemTax;
                                                    }
                                                }
                                            } else {
                                                $tRate = (float) ($ci->product->ticket_tax_rate ?? 0);
                                                if ($tRate > 0) {
                                                    $itemTax = round(($ci->subtotal * $tRate) / 100);
                                                    $ticketTaxTotal += $itemTax;
                                                    $name = $ci->product->ticket_tax_name ?: 'Pajak Daerah / Hiburan';
                                                    if (!isset($ticketTaxesBreakdown[$name])) {
                                                        $ticketTaxesBreakdown[$name] = ['rate' => $tRate, 'amount' => 0];
                                                    }
                                                    $ticketTaxesBreakdown[$name]['amount'] += $itemTax;
                                                }
                                            }
                                        }
                                        if ($sellerProfile && $sellerProfile->has_custom_fee) {
                                            $tFee = $sellerProfile->calculateTicketFees($ci->subtotal, $ci->qty);
                                            $pfAmt += $tFee['platform_fee'];
                                            $afAmt += $tFee['admin_fee'];
                                        } else {
                                            $pfAmt += round($ci->subtotal * $ticketPlatformFeeRate / 100);
                                            $afAmt += $ticketAdminFeeFlat * $ci->qty;
                                        }
                                    } else {
                                        $hasNonTicketItem = true;
                                        $pfAmt += round($ci->subtotal * $pfRate / 100);
                                        $afAmt += round($ci->subtotal * $afRate / 100);
                                    }
                                }
                                // Label tampil
                                $pfLabel = 'Platform Fee';
                                $afLabel = 'Admin Fee';
                            @endphp

                            <div class="summary-row" id="platform-fee-row">
                                <span style="display:inline-flex;align-items:center;gap:4px;">
                                    {{ $pfLabel }}
                                    <span class="fee-info-wrapper">
                                        <span class="fee-info-icon" onclick="toggleFeePopover(event, this)">ⓘ</span>
                                        <div class="fee-popover">
                                            <span class="fee-popover-title">Platform Fee</span>
                                            Kontribusi untuk pengembangan platform buyle.id agar terus berinovasi dan
                                            memberikan
                                            pengalaman terbaik.
                                        </div>
                                    </span>
                                </span>
                                <span id="platform-fee-val" style="color:#F59E0B;font-weight:600;">+Rp
                                    {{ number_format($pfAmt, 0, ',', '.') }}</span>
                            </div>

                            <div class="summary-row" id="admin-fee-row">
                                <span style="display:inline-flex;align-items:center;gap:4px;">
                                    {{ $afLabel }}
                                    <span class="fee-info-wrapper">
                                        <span class="fee-info-icon" onclick="toggleFeePopover(event, this)">ⓘ</span>
                                        <div class="fee-popover">
                                            <span class="fee-popover-title">Admin Fee</span>
                                            Dukungan layanan transaksi kamu diproses dengan aman, cepat, dan terlindungi.
                                        </div>
                                    </span>
                                </span>
                                <span id="admin-fee-val" style="color:#F59E0B;font-weight:600;">+Rp
                                    {{ number_format($afAmt, 0, ',', '.') }}</span>
                            </div>

                            @if(!empty($ticketTaxesBreakdown))
                                @foreach($ticketTaxesBreakdown as $tName => $tData)
                                    <div class="summary-row ticket-tax-item-row">
                                        <span style="display:inline-flex;align-items:center;gap:4px;">
                                            {{ $tName }} ({{ $tData['rate'] }}%)
                                            <span class="fee-info-wrapper">
                                                <span class="fee-info-icon" onclick="toggleFeePopover(event, this)">ⓘ</span>
                                                <div class="fee-popover">
                                                    <span class="fee-popover-title">{{ $tName }}</span>
                                                    Pajak resmi daerah / hiburan sesuai ketentuan peraturan daerah terkait tiket
                                                    acara ({{ $tData['rate'] }}%).
                                                </div>
                                            </span>
                                        </span>
                                        <span style="color:#64748B;font-weight:600;">+Rp
                                            {{ number_format($tData['amount'], 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            @elseif($ticketTaxTotal > 0)
                                <div class="summary-row" id="ticket-tax-row">
                                    <span style="display:inline-flex;align-items:center;gap:4px;">
                                        Pajak Tiket
                                        <span class="fee-info-wrapper">
                                            <span class="fee-info-icon" onclick="toggleFeePopover(event, this)">ⓘ</span>
                                            <div class="fee-popover">
                                                <span class="fee-popover-title">Pajak Tiket</span>
                                                Pajak resmi daerah / hiburan sesuai ketentuan peraturan daerah terkait tiket
                                                acara.
                                            </div>
                                        </span>
                                    </span>
                                    <span id="ticket-tax-val" style="color:#64748B;font-weight:600;">+Rp
                                        {{ number_format($ticketTaxTotal, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            @if($summary['has_physical_product'])
                                <div class="summary-row" id="ongkir-row">
                                    <span>Ongkos Kirim</span>
                                    <span id="ongkir-row-val" style="color:#1eb349; font-weight:600; font-size:0.8rem;">Pilih
                                        Lokasi
                                        & Kurir</span>
                                </div>
                            @endif

                            {{-- ====== VOUCHER PICKER ====== --}}
                            <div style="margin-top:1.5rem;">
                                <input type="hidden" name="coupon_code" id="coupon_code_input" value="">

                                {{-- Applied State --}}
                                <div id="voucher-applied-state"
                                    style="display:none; background:linear-gradient(135deg,#D1FAE5,#A7F3D0); border:1.5px solid #10B981; border-radius:12px; padding:0.875rem 1.25rem; margin-bottom:0.5rem; align-items:center; justify-content:space-between; gap:0.5rem;">
                                    <div style="display:flex;align-items:center;gap:0.5rem;">
                                        <svg width="18" height="18" fill="none" stroke="#059669" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <div id="voucher-applied-code"
                                                style="font-size:0.85rem;font-weight:800;color:#065F46;font-family:monospace;letter-spacing:1px;">
                                            </div>
                                            <div id="voucher-applied-save"
                                                style="font-size:0.8rem;color:#059669;font-weight:600;"></div>
                                        </div>
                                    </div>
                                    <button type="button" onclick="clearVoucher()"
                                        style="background:none;border:none;color:#B91C1C;font-size:0.8rem;font-weight:700;cursor:pointer;padding:0.25rem 0.5rem;border-radius:6px;background:#FEE2E2;">✕
                                        Hapus</button>
                                </div>

                                {{-- Picker Button --}}
                                <button type="button" id="voucher-picker-btn" onclick="openVoucherModal()"
                                    style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:0.875rem 1.25rem;background:#fff;border:1.5px dashed #CBD5E1;border-radius:12px;cursor:pointer;font-family:var(--font);transition:all 0.2s;"
                                    onmouseover="this.style.borderColor='#1eb349'"
                                    onmouseout="this.style.borderColor='#CBD5E1'">
                                    <div style="display:flex;align-items:center;gap:0.75rem;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1eb349"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                            <line x1="7" y1="7" x2="7.01" y2="7" />
                                        </svg>
                                        <span style="font-size:0.9rem;font-weight:600;color:#334155;">Pilih atau Masukkan
                                            Voucher</span>
                                    </div>
                                    <svg width="16" height="16" fill="none" stroke="#94A3B8" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M9 18l6-6-6-6" />
                                    </svg>
                                </button>
                            </div>

                            <div class="checkout-sticky-footer" style="margin-top: 1.5rem;">
                                <div class="summary-total" style="margin-top:0; padding-top:1rem;">
                                    <span>Total</span>
                                    @if($summary['subtotal'] <= 0)
                                        <span id="total-row-val"
                                            style="color:#16a34a;font-size:1.3rem;font-weight:900;letter-spacing:-0.5px;">GRATIS</span>
                                    @else
                                        <span id="total-row-val">Rp
                                            {{ number_format($summary['subtotal'] + $pfAmt + $afAmt + $ticketTaxTotal, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                @if($summary['subtotal'] <= 0)
                                    @if($hasFreeRequirements && auth()->guest())
                                        <a href="{{ route('checkout.login', ['redirect' => request()->fullUrl()]) }}"
                                            id="btn-claim-free"
                                            style="display:flex;align-items:center;justify-content:center;gap:0.5rem;width:100%;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:12px;padding:0.9rem;font-size:0.95rem;font-weight:800;text-decoration:none;text-align:center;box-shadow:0 4px 15px rgba(245,158,11,0.25);">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                                <polyline points="10 17 15 12 10 7" />
                                                <line x1="15" y1="12" x2="3" y2="12" />
                                            </svg>
                                            <span>Masuk untuk Klaim Gratis</span>
                                        </a>
                                    @elseif($hasFreeRequirements)
                                        <button type="submit" id="btn-claim-free" class="btn-pay" onclick="prepareSubmit(event)"
                                            style="background:#94a3b8;cursor:not-allowed;box-shadow:none;" disabled>
                                            <span id="btn-claim-text">Klaim Gratis</span>
                                        </button>
                                    @else
                                        <button type="submit" class="btn-pay" onclick="prepareSubmit(event)"
                                            style="background:linear-gradient(135deg,#16a34a,#22c55e);">Dapatkan Sekarang
                                            (Gratis)</button>
                                    @endif
                                @else
                                    <button type="submit" class="btn-pay" onclick="prepareSubmit(event)">Pilih
                                        Pembayaran</button>
                                @endif
                            </div>

                            <div class="secure-badge"
                                style="display:flex;align-items:center;justify-content:center;gap:0.5rem;margin-top:1.5rem;color:var(--c-muted);font-size:0.75rem;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                100% Secure & Encrypted Payment
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>

    {{-- ====== VOUCHER MODAL ====== --}}
    <div id="voucher-modal-overlay" onclick="closeVoucherModal()"
        style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);z-index:99998;backdrop-filter:blur(4px);opacity:0;transition:opacity 0.3s ease;">
    </div>
    <div id="voucher-modal"
        style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%, -50%);width:92%;max-width:420px;z-index:99999;background:#fff;border-radius:20px;max-height:75vh;overflow:hidden;flex-direction:column;opacity:0;transition:all 0.3s cubic-bezier(0.34,1.56,0.64,1);box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);">
        {{-- Header --}}
        <div
            style="padding:1.25rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #F1F5F9;background:#F8FAFC;">
            <div style="display:flex;align-items:center;gap:0.5rem;font-size:1.1rem;font-weight:800;color:#0F172A;">
                <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Pilih Voucher
            </div>
            <button type="button" onclick="closeVoucherModal()"
                style="background:#E2E8F0;border:none;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1rem;color:#475569;transition:background 0.2s;"
                onmouseover="this.style.background='#CBD5E1'" onmouseout="this.style.background='#E2E8F0'">✕</button>
        </div>
        {{-- Manual Input --}}
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #F1F5F9;">
            <div style="display:flex;gap:0.5rem;">
                <input type="text" id="voucher-manual-input" placeholder="Masukkan kode voucher..."
                    style="flex:1;border:1.5px solid #E2E8F0;border-radius:10px;padding:0.65rem 1rem;font-size:0.9rem;font-family:var(--font);outline:none;text-transform:uppercase;"
                    oninput="this.value=this.value.toUpperCase()">
                <button type="button" onclick="applyManualVoucher()"
                    style="background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;border:none;border-radius:10px;padding:0.65rem 1.25rem;font-weight:700;font-size:0.875rem;cursor:pointer;white-space:nowrap;">Pakai</button>
            </div>
            <div id="voucher-manual-msg" style="font-size:0.78rem;margin-top:0.4rem;display:none;">
            </div>
        </div>
        {{-- List --}}
        <div style="overflow-y:auto;flex:1;padding:0.75rem 1.25rem 2rem;" id="voucher-list-container">
            <div
                style="font-size:0.75rem;color:#94A3B8;font-weight:600;margin-bottom:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">
                Voucher Tersedia</div>
            <div id="voucher-list">
                <div style="text-align:center;padding:2rem;color:#94A3B8;font-size:0.875rem;">Memuat
                    voucher...</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const subtotal = {{ $summary['subtotal'] }};
        const totalWeight = {{ $summary['total_weight'] > 0 ? $summary['total_weight'] : 100 }};
        // Fee sudah dihitung di PHP per item (ticket vs non-ticket) — pakai nilai yang sudah benar
        const platformFee = {{ (int) $pfAmt }};
        const adminFee = {{ (int) $afAmt }};
        const ticketTax = {{ (int) $ticketTaxTotal }};
        const totalFees = platformFee + adminFee + ticketTax;

        let selectedCost = 0;
        let allProvinces = [];

        // ────────────────────────────────────────────
        // INIT
        // ────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            // Sinkronisasi admission_token dari sessionStorage jika ada produk war tiket
            const admInput = document.getElementById('checkout_admission_token_input');
            if (admInput && !admInput.value) {
                for (let i = 0; i < sessionStorage.length; i++) {
                    const key = sessionStorage.key(i);
                    if (key && key.startsWith('buyle_war_token_')) {
                        const tokenVal = sessionStorage.getItem(key);
                        if (tokenVal) {
                            admInput.value = tokenVal;
                            break;
                        }
                    }
                }
            }
            toggleNewAddress();
            loadProvincesAll();
        });

        // ────────────────────────────────────────────
        // LOAD PROVINCES — shared for both flows
        // ────────────────────────────────────────────
        function loadProvincesAll() {
            fetch(`{{ route('api.rajaongkir.provinces') }}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) throw new Error(data.error);
                    allProvinces = data;

                    const provSelect = document.getElementById('province_select');
                    const savedProvSelect = document.getElementById('saved_province_select');

                    if (provSelect) {
                        provSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                        data.forEach(prov => provSelect.add(new Option(prov.province, prov.province_id)));
                    }

                    if (savedProvSelect) {
                        savedProvSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                        data.forEach(prov => savedProvSelect.add(new Option(prov.province, prov.province_id)));
                    }

                    // Now that provinces are loaded, try to auto-match saved address
                    toggleNewAddress();
                })
                .catch(err => {
                    console.error('Provinces API Error:', err);
                    toggleNewAddress(); // still run to handle display logic
                });
        }

        // ────────────────────────────────────────────
        // NEW-ADDRESS FLOW: Province → City
        // ────────────────────────────────────────────
        document.getElementById('province_select')?.addEventListener('change', function () {
            const text = this.options[this.selectedIndex].text;
            document.getElementById('province_name').value = this.value ? text : '';
            loadCitiesInto('city_select', this.value, null);
            resetShipping();
        });

        document.getElementById('city_select')?.addEventListener('change', function () {
            const text = this.options[this.selectedIndex]?.text || '';
            document.getElementById('city_name').value = this.value ? text : '';
            loadDistrictsInto('district_select', this.value);
            resetShipping();
            if (this.value && document.getElementById('courier_name_select')?.value) {
                checkCost();
            }
        });

        document.getElementById('district_select')?.addEventListener('change', function () {
            const text = this.options[this.selectedIndex]?.text || '';
            const selectedOpt = this.options[this.selectedIndex];
            document.getElementById('district_name').value = this.value ? text : '';

            // Auto fill postal code if returned from district API
            const postal = selectedOpt?.getAttribute('data-postal') || '';
            if (postal) {
                document.getElementById('new_addr_postal').value = postal;
            }

            loadSubdistrictsInto('subdistrict_select', this.value);
        });

        document.getElementById('subdistrict_select')?.addEventListener('change', function () {
            const text = this.options[this.selectedIndex]?.text || '';
            const postalInput = document.getElementById('new_addr_postal');
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt?.getAttribute('data-postal')) {
                postalInput.value = selectedOpt.getAttribute('data-postal');
            }
        });

        function loadDistrictsInto(selectId, cityId) {
            const distSelect = document.getElementById(selectId);
            if (!distSelect) return;
            if (!cityId) {
                distSelect.innerHTML = '<option value="">Pilih Kota Dulu</option>';
                return;
            }

            const citySelect = document.getElementById('city_select');
            const cityName = citySelect?.options[citySelect.selectedIndex]?.text || '';
            const provId = document.getElementById('province_select')?.value || '';

            distSelect.innerHTML = '<option value="">Memuat kecamatan...</option>';
            fetch(`{{ url('/api/rajaongkir/districts') }}/${cityId}?city_name=${encodeURIComponent(cityName)}&province_id=${provId}`)
                .then(res => res.json())
                .then(data => {
                    distSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(dist => {
                            const opt = new Option(dist.district_name, dist.district_id);
                            if (dist.postal_code) opt.setAttribute('data-postal', dist.postal_code);
                            distSelect.add(opt);
                        });
                    } else {
                        distSelect.innerHTML = '<option value="">Pilih Kecamatan (Kosong / Manual)</option>';
                    }
                })
                .catch(err => {
                    console.error('Districts API Error:', err);
                    distSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                });
        }

        function loadSubdistrictsInto(selectId, districtId) {
            const subSelect = document.getElementById(selectId);
            if (!subSelect) return;
            if (!districtId) {
                subSelect.innerHTML = '<option value="">Pilih Kecamatan Dulu</option>';
                return;
            }
            subSelect.innerHTML = '<option value="">Memuat kelurahan...</option>';
            fetch(`{{ url('/api/rajaongkir/subdistricts') }}/${districtId}`)
                .then(res => res.json())
                .then(data => {
                    subSelect.innerHTML = '<option value="">Pilih Kelurahan / Desa</option>';
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(sub => {
                            const opt = new Option(sub.village_name, sub.village_id);
                            subSelect.add(opt);
                        });
                    }
                })
                .catch(err => {
                    subSelect.innerHTML = '<option value="">Pilih Kelurahan / Desa (Opsional)</option>';
                });
        }

        // ────────────────────────────────────────────
        // SAVED-ADDRESS FLOW: Province → City
        // ────────────────────────────────────────────
        document.getElementById('saved_province_select')?.addEventListener('change', function () {
            loadCitiesInto('saved_city_select', this.value, null);
            document.getElementById('saved_city_id').value = '';
            resetShipping();
        });

        document.getElementById('saved_city_select')?.addEventListener('change', function () {
            document.getElementById('saved_city_id').value = this.value;
            resetShipping();
            if (this.value && document.getElementById('courier_name_select')?.value) {
                checkCost();
            }
        });

        // ────────────────────────────────────────────
        // SHARED: Load cities into any select
        // ────────────────────────────────────────────
        function loadCitiesInto(selectId, provId, preselectedCityId, matchCityName = null) {
            const citySelect = document.getElementById(selectId);
            if (!citySelect) return;
            if (!provId) {
                citySelect.innerHTML = '<option value="">Pilih Provinsi Dulu</option>';
                return;
            }
            citySelect.innerHTML = '<option value="">Memuat kota...</option>';
            return fetch(`{{ url('/api/rajaongkir/cities') }}/${provId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) throw new Error(data.error);
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    data.forEach(city => {
                        const type = city.type === 'Kabupaten' ? 'Kab.' : 'Kota';
                        const opt = new Option(`${type} ${city.city_name}`, city.city_id);
                        citySelect.add(opt);
                    });

                    // Match by text (for saved addresses)
                    if (matchCityName) {
                        const target = matchCityName.toLowerCase().replace(/^(kota|kabupaten|kab\.)\s+/i, '').trim();
                        for (let i = 0; i < citySelect.options.length; i++) {
                            const optText = citySelect.options[i].text.toLowerCase().replace(/^(kota|kabupaten|kab\.)\s+/i, '').trim();
                            if (optText === target || optText.includes(target)) {
                                citySelect.value = citySelect.options[i].value;
                                citySelect.dispatchEvent(new Event('change'));
                                return true; // Match found
                            }
                        }
                    }

                    // Auto-select exact ID
                    if (preselectedCityId) {
                        citySelect.value = preselectedCityId;
                        citySelect.dispatchEvent(new Event('change'));
                    }
                    return false;
                })
                .then(undefined, err => {
                    citySelect.innerHTML = '<option value="">Gagal memuat kota — coba lagi</option>';
                    return false;
                });
        }

        // ────────────────────────────────────────────
        // KURIR: Same trigger for both flows
        // ────────────────────────────────────────────
        // F&B MULTIFUNGSI SERVICE TYPE TOGGLE
        // ────────────────────────────────────────────
        function toggleFnbFields(type) {
            const dineInBox = document.getElementById('fnb_field_dine_in');
            const deliveryBox = document.getElementById('fnb_field_delivery');
            const takeawayBox = document.getElementById('fnb_field_takeaway');

            if (dineInBox) dineInBox.style.display = (type === 'dine_in') ? 'block' : 'none';
            if (deliveryBox) deliveryBox.style.display = (type === 'delivery') ? 'block' : 'none';
            if (takeawayBox) takeawayBox.style.display = (type === 'takeaway') ? 'block' : 'none';
        }

        document.getElementById('courier_name_select')?.addEventListener('change', function () {
            const cityId = getActiveCityId();
            if (cityId && this.value) {
                checkCost();
            } else {
                resetShipping();
            }
        });

        function getActiveCityId() {
            const addrSelect = document.getElementById('address_id_select');
            if (addrSelect && addrSelect.value && addrSelect.value !== 'new') {
                // Using saved address → use saved_city_id
                return document.getElementById('saved_city_id')?.value || '';
            } else {
                // Using new address → use city_select
                return document.getElementById('city_select')?.value || '';
            }
        }

        // ────────────────────────────────────────────
        // SHIPPING COST CALCULATION
        // ────────────────────────────────────────────
        function resetShipping() {
            const serviceContainer = document.getElementById('courier_service_container');
            if (serviceContainer) {
                serviceContainer.innerHTML = `
                                                                                    <div style="padding: 1rem; border: 1px dashed #CBD5E1; border-radius: 10px; background: #F8FAFC; color: #64748B; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                                                                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                                        Pilih kota dan kurir terlebih dahulu untuk melihat opsi pengiriman.
                                                                                    </div>
                                                                                `;
            }
            const hiddenInput = document.getElementById('courier_service_hidden');
            if (hiddenInput) hiddenInput.value = '';
            selectedCost = 0;
            updateTotal();
        }

        function checkCost() {
            const cityId = getActiveCityId();
            const courier = document.getElementById('courier_name_select')?.value;
            const serviceContainer = document.getElementById('courier_service_container');
            const hiddenInput = document.getElementById('courier_service_hidden');
            const loadingEl = document.getElementById('ongkir_loading');

            if (!cityId || !courier) return;

            if (loadingEl) loadingEl.style.display = 'flex';
            serviceContainer.innerHTML = `
                                                                                <div style="padding: 1rem; border: 1px dashed #CBD5E1; border-radius: 10px; background: #F8FAFC; color: #1eb349; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; font-weight:600;">
                                                                                    <svg style="animation: spin 1s linear infinite;" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path></svg>
                                                                                    Menghitung ongkir...
                                                                                </div>
                                                                            `;
            hiddenInput.value = '';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || '{{ csrf_token() }}';

            fetch(`{{ route('api.rajaongkir.cost') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    destination: parseInt(cityId),
                    weight: totalWeight,
                    courier: courier
                })
            })
                .then(res => {
                    console.info('[ONGKIR] HTTP Status:', res.status);
                    return res.json().then(data => ({ httpStatus: res.status, data }));
                })
                .then(({ httpStatus, data }) => {
                    if (loadingEl) loadingEl.style.display = 'none';

                    console.info('[ONGKIR] Response:', data);

                    // Manual fallback: HANYA jika timeout/connection refused (bukan error API biasa)
                    if (data.manual) {
                        console.warn('[ONGKIR] Fallback manual. Debug:', data.debug_error);
                        serviceContainer.innerHTML = `
                                                                                        <label class="service-card selected" style="cursor:pointer; display:flex; align-items:center; gap:1rem; padding:1rem; border:1px solid #1eb349; border-radius:10px; background:#F0F9FF;">
                                                                                            <input type="radio" name="courier_service_radio" value="manual" data-cost="0" checked style="accent-color:#1eb349; width:1.2rem; height:1.2rem;">
                                                                                            <div style="flex:1;">
                                                                                                <div style="font-weight:700; color:#0F172A; font-size:0.95rem;">Manual</div>
                                                                                                <div style="font-size:0.8rem; color:#64748B; margin-top:0.2rem;">Ongkir dikonfirmasi Admin setelah pesan</div>
                                                                                            </div>
                                                                                            <div style="font-weight:700; color:#1eb349; font-size:1rem;">Rp 0</div>
                                                                                        </label>
                                                                                    `;
                        hiddenInput.value = 'manual';
                        hiddenInput.removeAttribute('required');
                        selectedCost = 0;
                        updateTotal('manual');
                        attachRadioListeners();
                        return;
                    }

                    // Error dari API (origin salah, kurir tidak tersedia, dll) — tampilkan, jangan silent fallback
                    if (data.error) {
                        console.warn('[ONGKIR] API error:', data.error);
                        serviceContainer.innerHTML = `
                                                                                        <div style="padding: 1rem; border: 1px dashed #FCA5A5; border-radius: 10px; background: #FEF2F2; color: #B91C1C; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                                                                                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4m0 4h.01"></path></svg>
                                                                                            ${data.error}
                                                                                        </div>
                                                                                    `;
                        hiddenInput.value = '';
                        hiddenInput.removeAttribute('required');
                        return;
                    }

                    // Validasi: harus array dan tidak kosong
                    if (!Array.isArray(data) || data.length === 0) {
                        console.warn('[ONGKIR] Data kosong atau bukan array:', data);
                        serviceContainer.innerHTML = `
                                                                                        <label class="service-card selected" style="cursor:pointer; display:flex; align-items:center; gap:1rem; padding:1rem; border:1px solid #1eb349; border-radius:10px; background:#F0F9FF;">
                                                                                            <input type="radio" name="courier_service_radio" value="manual" data-cost="0" checked style="accent-color:#1eb349; width:1.2rem; height:1.2rem;">
                                                                                            <div style="flex:1;">
                                                                                                <div style="font-weight:700; color:#0F172A; font-size:0.95rem;">Manual</div>
                                                                                                <div style="font-size:0.8rem; color:#64748B; margin-top:0.2rem;">Ongkir dikonfirmasi Admin setelah pesan</div>
                                                                                            </div>
                                                                                            <div style="font-weight:700; color:#1eb349; font-size:1rem;">Rp 0</div>
                                                                                        </label>
                                                                                    `;
                        hiddenInput.value = 'manual';
                        hiddenInput.removeAttribute('required');
                        selectedCost = 0;
                        updateTotal('manual');
                        attachRadioListeners();
                        return;
                    }

                    // SUCCESS — render semua pilihan layanan
                    hiddenInput.setAttribute('required', 'required');
                    let html = '';
                    data.forEach((service, idx) => {
                        const cost = service.cost[0]?.value ?? 0;
                        let etdString = service.cost[0]?.etd ? service.cost[0].etd.toString().replace(/hari/gi, '').trim() : '';
                        etdString = etdString.replace(/-/g, ' - '); // Fix optical illusion (1-2 looking like 8)
                        const etd = etdString ? `Estimasi ${etdString} Hari` : 'Estimasi tidak tersedia';
                        const fmt = new Intl.NumberFormat('id-ID').format(cost);

                        // Tambahkan deskripsi agar pembeli gaptek mengerti
                        let desc = service.description || '';
                        if (service.service.toUpperCase() === 'JTR') desc = 'Layanan Kargo/Barang Berat (Lebih hemat)';
                        if (service.service.toUpperCase() === 'REG' || service.service.toUpperCase() === 'EZ') desc = 'Layanan Standar Reguler';
                        if (service.service.toUpperCase() === 'YES') desc = 'Layanan Cepat (Yakin Esok Sampai)';

                        const isChecked = (idx === 0) ? 'checked' : '';
                        const cardStyle = (idx === 0) ? 'border-color:#1eb349; background:#F0F9FF;' : 'border-color:#E2E8F0; background:#fff;';

                        html += `
                                                                                        <label class="service-card ${idx === 0 ? 'selected' : ''}" style="cursor:pointer; display:flex; align-items:center; gap:1rem; padding:1rem; border:1px solid; ${cardStyle} border-radius:10px; transition:all 0.2s;">
                                                                                            <input type="radio" name="courier_service_radio" value="${service.service}" data-cost="${cost}" ${isChecked} style="accent-color:#1eb349; width:1.2rem; height:1.2rem;">
                                                                                            <div style="flex:1;">
                                                                                                <div style="font-weight:700; color:#0F172A; font-size:0.95rem;">${service.service}</div>
                                                                                                <div style="font-size:0.8rem; color:#64748B; margin-top:0.2rem; display:flex; align-items:center; gap:0.3rem;">
                                                                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                                                                                    ${desc}
                                                                                                </div>
                                                                                                <div style="font-size:0.8rem; color:#15803d; font-weight:600; margin-top:0.3rem; display:flex; align-items:center; gap:0.3rem;">
                                                                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                                                                                                    ${etd}
                                                                                                </div>
                                                                                            </div>
                                                                                            <div style="font-weight:800; color:#1eb349; font-size:1.1rem;">Rp ${fmt}</div>
                                                                                        </label>
                                                                                    `;
                    });
                    serviceContainer.innerHTML = html;
                    attachRadioListeners();

                    // Auto-select first option and update summary total immediately
                    const firstRadio = serviceContainer.querySelector('input[name="courier_service_radio"]:checked');
                    if (firstRadio) {
                        hiddenInput.value = firstRadio.value;
                        selectedCost = parseInt(firstRadio.dataset.cost) || 0;
                        updateTotal();
                    }
                    console.info('[ONGKIR] Sukses:', data.length, 'layanan tampil.');
                })
                .catch(err => {
                    // Hanya terjadi jika ada error JARINGAN di sisi browser (bukan response error)
                    if (loadingEl) loadingEl.style.display = 'none';
                    console.error('[ONGKIR] Fetch/jaringan error:', err);
                    serviceContainer.innerHTML = `
                                                                                    <label class="service-card selected" style="cursor:pointer; display:flex; align-items:center; gap:1rem; padding:1rem; border:1px solid #1eb349; border-radius:10px; background:#F0F9FF;">
                                                                                        <input type="radio" name="courier_service_radio" value="manual" data-cost="0" checked style="accent-color:#1eb349; width:1.2rem; height:1.2rem;">
                                                                                        <div style="flex:1;">
                                                                                            <div style="font-weight:700; color:#0F172A; font-size:0.95rem;">Manual</div>
                                                                                            <div style="font-size:0.8rem; color:#64748B; margin-top:0.2rem;">Ongkir dikonfirmasi Admin setelah pesan</div>
                                                                                        </div>
                                                                                        <div style="font-weight:700; color:#1eb349; font-size:1rem;">Rp 0</div>
                                                                                    </label>
                                                                                `;
                    hiddenInput.value = 'manual';
                    hiddenInput.removeAttribute('required');
                    selectedCost = 0;
                    updateTotal('manual');
                    attachRadioListeners();
                });
        }

        function attachRadioListeners() {
            const radios = document.querySelectorAll('input[name="courier_service_radio"]');
            const hiddenInput = document.getElementById('courier_service_hidden');

            radios.forEach(radio => {
                const selectOption = function () {
                    // Reset semua background card
                    document.querySelectorAll('.service-card').forEach(card => {
                        card.style.borderColor = '#E2E8F0';
                        card.style.background = '#fff';
                        card.classList.remove('selected');
                    });

                    radio.checked = true;
                    const card = radio.closest('.service-card');
                    if (card) {
                        card.style.borderColor = '#1eb349';
                        card.style.background = '#F0F9FF';
                        card.classList.add('selected');
                    }

                    hiddenInput.value = radio.value;

                    if (radio.value === 'manual') {
                        selectedCost = 0;
                        updateTotal('manual');
                    } else {
                        selectedCost = parseInt(radio.dataset.cost) || 0;
                        updateTotal();
                    }
                };

                radio.addEventListener('change', selectOption);

                const card = radio.closest('.service-card');
                if (card) {
                    card.onclick = function (e) {
                        if (e.target !== radio) {
                            selectOption();
                        }
                    };
                }
            });
        }

        function updateTotal(mode) {
            const input = document.getElementById('shipping_cost_input');
            if (input) input.value = selectedCost;

            const ongkirRowVal = document.getElementById('ongkir-row-val');
            if (ongkirRowVal) {
                if (mode === 'manual') {
                    ongkirRowVal.innerText = 'Dikonfirmasi Admin';
                    ongkirRowVal.style.color = '#F59E0B';
                } else {
                    ongkirRowVal.innerText = selectedCost > 0
                        ? `Rp ${new Intl.NumberFormat('id-ID').format(selectedCost)}`
                        : 'Pilih kurir & layanan';
                    ongkirRowVal.style.color = selectedCost > 0 ? '#1E293B' : '#1eb349';
                }
            }

            const totalRowVal = document.getElementById('total-row-val');
            if (totalRowVal) {
                // Grand total = subtotal + platformFee (5%) + adminFee (5%) + ongkir - diskon
                const discount = (typeof appliedVoucherDiscount !== 'undefined') ? appliedVoucherDiscount : 0;
                const finalTotal = subtotal + totalFees + selectedCost - discount;
                totalRowVal.innerText = `Rp ${new Intl.NumberFormat('id-ID').format(finalTotal)}`;
            }
        }

        // Geolocation logic
        function getLocation() {
            const status = document.getElementById('loc_status');
            status.style.display = 'block';
            status.style.color = 'var(--c-muted)';
            status.innerText = 'Meminta izin lokasi GPS dari browser...';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError, { enableHighAccuracy: true });
            } else {
                status.style.color = '#EF4444';
                status.innerText = "GPS / Geolocation tidak didukung oleh browser Anda.";
            }
        }

        function showPosition(position) {
            const status = document.getElementById('loc_status');
            status.innerText = 'Mengambil alamat dari koordinat GPS...';

            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            document.getElementById('new_addr_lat').value = lat;
            document.getElementById('new_addr_lng').value = lon;

            // Reverse geocoding via Nominatim
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1&accept-language=id`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.address) {
                        status.style.color = '#16A34A';
                        status.innerText = '✓ Lokasi ditemukan! Provinsi, Kota & Kecamatan diisi otomatis. Harap isi Alamat Lengkap secara manual.';

                        const addr = data.address;

                        // Isi Kode Pos & Kecamatan otomatis dari GPS
                        if (addr.postcode) document.getElementById('new_addr_postal').value = addr.postcode;
                        if (addr.village || addr.suburb || addr.town)
                            document.getElementById('district_name').value = addr.village || addr.suburb || addr.town || '';

                        // TIDAK isi alamat lengkap — user harus isi sendiri
                        const fullAddrField = document.getElementById('new_addr_full');
                        if (fullAddrField) {
                            fullAddrField.value = '';
                            fullAddrField.placeholder = '⚠ Wajib isi manual: Nama jalan, nomor rumah, RT/RW, dll.';
                            fullAddrField.style.borderColor = '#F59E0B';
                            fullAddrField.style.boxShadow = '0 0 0 3px rgba(245,158,11,0.2)';
                            setTimeout(() => fullAddrField.focus(), 400);
                        }

                        // Auto-select Province from dropdown
                        const rawProv = addr.state || '';
                        const provSelect = document.getElementById('province_select');
                        let matchedProvId = null;
                        if (rawProv && provSelect) {
                            const targetP = rawProv.toLowerCase().replace(/^(provinsi|daerah istimewa|dki|daerah khusus)\s*/i, '').trim();
                            for (let i = 0; i < provSelect.options.length; i++) {
                                const optP = provSelect.options[i].text.toLowerCase().replace(/^(dki|daerah istimewa|daerah khusus)\s*/i, '').trim();
                                if (optP === targetP || optP.includes(targetP) || targetP.includes(optP)) {
                                    matchedProvId = provSelect.options[i].value;
                                    provSelect.value = matchedProvId;
                                    provSelect.dispatchEvent(new Event('change'));
                                    break;
                                }
                            }
                        }

                        // Auto-select City after cities load
                        if (matchedProvId) {
                            const rawCity = addr.city || addr.county || addr.town || '';
                            loadCitiesInto('city_select', matchedProvId, null, rawCity);
                        }
                    } else {
                        status.style.color = '#EF4444';
                        status.innerText = 'Gagal mendapatkan detail alamat.';
                    }
                })
                .catch(err => {
                    status.style.color = '#EF4444';
                    status.innerText = 'Gagal menterjemahkan koordinat GPS.';
                });
        }

        function updateMapAndAddress(lat, lon, showSwal = false) {
            // Legacy stub — no longer used (map removed)
        }

        function showError(error) {
            const status = document.getElementById('loc_status');
            status.style.color = '#EF4444';
            let msg = "";
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    msg = "Akses GPS ditolak/diblokir oleh perangkat Anda.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    msg = "Informasi lokasi GPS tidak tersedia saat ini.";
                    break;
                case error.TIMEOUT:
                    msg = "Waktu tunggu pencarian GPS habis (Timeout).";
                    break;
                default:
                    msg = "Terjadi kesalahan tidak diketahui pada GPS.";
                    break;
            }
            status.innerHTML = `<b>${msg}</b><br><span style="color:#64748B;font-size:0.8rem;">Silakan isi Provinsi & Kota/Kecamatan secara manual di bawah.</span>`;
        }



        function prepareSubmit(e) {
            const checkoutType = @json($summary['checkout_type']);
            const hasGoodsShipping = (checkoutType === 'goods');
            const hasPhysicalProduct = (checkoutType === 'goods');
            const isGuest = @json(auth()->guest());

            // Validate screenshot proof jika validasi ketat aktif (1 platform 1 screenshot bukti)
            if (SCREENSHOT_REQUIRED) {
                const stepProofInputs = document.querySelectorAll('.step-proof-data');
                let missingLabels = [];
                let firstMissingBtn = null;

                stepProofInputs.forEach(input => {
                    if (!input.value) {
                        const label = input.getAttribute('data-label') || 'Syarat';
                        missingLabels.push(label);
                        const stepId = input.getAttribute('data-step-id');
                        const btn = document.getElementById('btn_upload_' + stepId);
                        if (btn) {
                            btn.style.borderColor = '#DC2626';
                            btn.style.backgroundColor = '#FEF2F2';
                            btn.style.color = '#DC2626';
                            if (!firstMissingBtn) firstMissingBtn = btn;
                        }
                    }
                });

                if (missingLabels.length > 0) {
                    e.preventDefault();
                    if (firstMissingBtn) {
                        firstMissingBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    const missingListHtml = missingLabels.map(l => `• ${l}`).join('<br>');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Upload Bukti Screenshot',
                            html: `Kreator mewajibkan upload bukti screenshot follow/join untuk tiap platform:<br><div style="text-align:left; background:#FEF2F2; color:#DC2626; padding:8px 12px; border-radius:8px; margin-top:8px; font-size:0.85rem; font-weight:600;">${missingListHtml}</div>`,
                            confirmButtonColor: '#1eb349',
                            confirmButtonText: 'Upload Sekarang'
                        });
                    } else {
                        alert('Mohon upload screenshot bukti follow/gabung terlebih dahulu untuk:\n' + missingLabels.join('\n'));
                    }
                    return;
                }
            }

            // Validate guest contact info
            if (isGuest) {
                const guestEmail = document.querySelector('input[name="guest_email"]')?.value?.trim();

                if (!guestEmail) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Email Belum Diisi',
                        text: 'Mohon isi Email aktif Anda untuk menerima file/akses digital.',
                        confirmButtonColor: '#1eb349',
                        confirmButtonText: 'Isi Sekarang'
                    });
                    e.preventDefault();
                    return;
                }
            }

            // Validate physical shipping address & courier ONLY IF checkoutType is goods
            if (hasGoodsShipping) {
                const select = document.getElementById('address_id_select');
                const isNewAddress = (!select || select.value === 'new');

                if (isNewAddress) {
                    const provName = document.getElementById('province_name');
                    const cityName = document.getElementById('city_name');
                    const distName = document.getElementById('district_name');
                    const newAddrFull = document.getElementById('new_addr_full');
                    const newAddrReceiver = document.getElementById('new_addr_receiver');
                    const newAddrPhone = document.getElementById('new_addr_phone');

                    if (!provName?.value || !cityName?.value || !distName?.value || !newAddrFull?.value || !newAddrReceiver?.value || !newAddrPhone?.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Alamat Belum Lengkap',
                            text: 'Mohon isi semua kolom penerima, nomor telepon, dan detail alamat lengkap.',
                            confirmButtonColor: '#1eb349',
                            confirmButtonText: 'Lengkapi Data'
                        });
                        e.preventDefault();
                        return;
                    }
                } else if (select && select.value !== 'new') {
                    const savedCityId = document.getElementById('saved_city_id')?.value;
                    if (!savedCityId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Kota Tujuan',
                            text: 'Mohon pilih Provinsi dan Kota di bagian ongkir agar biaya pengiriman dapat dihitung.',
                            confirmButtonColor: '#1eb349'
                        });
                        e.preventDefault();
                        return;
                    }
                }

                if (document.getElementById('courier_name_select')) {
                    const service = document.getElementById('courier_service_hidden')?.value;
                    if (!service) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Layanan Pengiriman',
                            text: 'Anda belum memilih layanan pengiriman (contoh: REG / YES).',
                            confirmButtonColor: '#1eb349'
                        });
                        e.preventDefault();
                        return;
                    }
                }
            }

            // PREVENT DEFAULT TO SHOW CONFIRMATION MODAL FIRST
            e.preventDefault();

            // Gather summary data for double check
            let receiverName = '';
            let receiverEmail = '';
            let receiverPhone = '';
            let addressText = '';

            if (isGuest) {
                receiverName = document.querySelector('input[name="guest_name"]')?.value || '';
                receiverEmail = document.querySelector('input[name="guest_email"]')?.value || '';
                receiverPhone = document.querySelector('input[name="guest_phone"]')?.value || '';
            } else {
                receiverName = @json(auth()->check() ? auth()->user()->name : '');
                receiverEmail = @json(auth()->check() ? auth()->user()->email : '');
                receiverPhone = @json(auth()->check() ? (auth()->user()->phone ?? '') : '');
            }

            if (hasPhysicalProduct) {
                const select = document.getElementById('address_id_select');
                const isNewAddress = (!select || select.value === 'new');
                if (isNewAddress) {
                    const prov = document.getElementById('province_name')?.value || '';
                    const city = document.getElementById('city_name')?.value || '';
                    const dist = document.getElementById('district_name')?.value || '';
                    const full = document.getElementById('new_addr_full')?.value || '';
                    addressText = `${full}, ${dist}, ${city}, ${prov}`;
                } else if (select) {
                    const opt = select.options[select.selectedIndex];
                    addressText = opt.getAttribute('data-full-address') || opt.text;
                }
            }

            let courierText = '';
            if (hasPhysicalProduct && document.getElementById('courier_name_select')) {
                const cSel = document.getElementById('courier_name_select');
                const cName = cSel.options[cSel.selectedIndex]?.text || '';
                const cSvc = document.getElementById('courier_service_hidden')?.value || '';
                courierText = `${cName} - ${cSvc}`;
            } else {
                courierText = 'Produk Digital / Akses Langsung (Tanpa Pengiriman Fisik)';
            }

            const notesText = document.getElementById('notes_input')?.value || '-';
            const totalCost = document.getElementById('total-row-val')?.innerText || '';

            const htmlSummary = `
                                                                                <div style="text-align:left; font-size:0.9rem; line-height:1.5; color:#334155; margin-top:1rem;">
                                                                                    <div style="margin-bottom:0.75rem;">
                                                                                        <strong style="color:#0F172A; display:block; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">Pembeli & Kontak Akses</strong>
                                                                                        <div><strong>${receiverName}</strong></div>
                                                                                        <div style="color:#166534; font-weight:600;">Email: ${receiverEmail}</div>
                                                                                        ${receiverPhone ? `<div>WhatsApp: ${receiverPhone}</div>` : ''}
                                                                                    </div>
                                                                                    ${hasPhysicalProduct ? `
                                                                                    <div style="margin-bottom:0.75rem;">
                                                                                        <strong style="color:#0F172A; display:block; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">Alamat Pengiriman</strong>
                                                                                        <div>${addressText}</div>
                                                                                    </div>
                                                                                    <div style="margin-bottom:0.75rem;">
                                                                                        <strong style="color:#0F172A; display:block; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">Kurir</strong>
                                                                                        <div>${courierText}</div>
                                                                                    </div>
                                                                                    ` : `
                                                                                    <div style="margin-bottom:0.75rem;">
                                                                                        <strong style="color:#0F172A; display:block; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">Pengiriman</strong>
                                                                                        <div style="color:#166534; font-weight:600;">Instan / Otomatis dikirim ke Email & WhatsApp</div>
                                                                                    </div>
                                                                                    `}
                                                                                    ${notesText && notesText !== '-' ? `
                                                                                    <div style="margin-bottom:0.75rem;">
                                                                                        <strong style="color:#0F172A; display:block; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">Catatan</strong>
                                                                                        <div style="font-style:italic;">"${notesText}"</div>
                                                                                    </div>
                                                                                    ` : ''}
                                                                                    <div style="margin-top:1rem; padding-top:1rem; border-top:1px dashed #CBD5E1; color:#1eb349; font-weight:800; font-size:1.15rem; text-align:right;">
                                                                                        ${totalCost}
                                                                                    </div>
                                                                                </div>
                                                                            `;

            Swal.fire({
                title: 'Konfirmasi Pesanan',
                html: htmlSummary,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#1eb349',
                cancelButtonColor: '#94A3B8',
                confirmButtonText: 'Semua Benar, Lanjut!',
                cancelButtonText: 'Cek Lagi',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-xl',
                    title: 'text-lg font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Disable semua tombol bayar/submit untuk proteksi anti double-click di browser
                    document.querySelectorAll('.btn-pay, #btn-claim-free').forEach(b => {
                        b.disabled = true;
                        b.style.opacity = '0.6';
                        b.style.pointerEvents = 'none';
                    });

                    // Show loading state
                    Swal.fire({
                        title: 'Memproses Pesanan...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    document.getElementById('checkoutForm').submit();
                }
            });
        }

        function toggleNewAddress() {
            const select = document.getElementById('address_id_select');
            const form = document.getElementById('new_address_form');
            const savedWrap = document.getElementById('saved_addr_city_wrap');
            if (!select) return;

            const isNew = select.value === 'new';

            if (form) form.style.display = isNew ? 'block' : 'none';

            // Reset shipping when switching address
            resetShipping();

            if (isNew) {
                if (savedWrap) savedWrap.style.display = 'none';
            } else {
                // Auto-match province & city from data attributes
                const option = select.options[select.selectedIndex];
                const targetProv = option.dataset.province;
                const targetCity = option.dataset.city;

                let matchedProvId = null;
                const provSelect = document.getElementById('saved_province_select');

                if (targetProv && provSelect) {
                    const targetP = targetProv.toLowerCase().trim();
                    for (let i = 0; i < provSelect.options.length; i++) {
                        if (provSelect.options[i].text.toLowerCase().trim() === targetP) {
                            matchedProvId = provSelect.options[i].value;
                            provSelect.value = matchedProvId;
                            break;
                        }
                    }
                }

                if (matchedProvId && targetCity) {
                    // We matched province, now load cities and match city
                    if (savedWrap) savedWrap.style.display = 'none'; // hide while loading

                    loadCitiesInto('saved_city_select', matchedProvId, null, targetCity)
                        .then(matched => {
                            // If we couldn't match the city automatically, show the manual fallback
                            if (savedWrap && !matched) {
                                savedWrap.style.display = 'block';
                            }
                        });
                } else {
                    // Fallback: show manual select if auto-match fails
                    if (savedWrap) savedWrap.style.display = 'block';
                }
            }

            // Toggle required on new-address fields
            const reqEls = ['new_addr_receiver', 'new_addr_phone', 'new_addr_postal', 'new_addr_full'];
            const selectEls = ['province_select', 'city_select', 'district_name'];
            [reqEls, selectEls].flat().forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    isNew ? el.setAttribute('required', 'required') : el.removeAttribute('required');
                }
            });
        }

        // Instead of triggering immediately, we now trigger after provinces are loaded
        // toggleNewAddress();

        // ────────────────────────────────────────────────────
        // VOUCHER PICKER
        // ────────────────────────────────────────────────────
        let appliedVoucherDiscount = 0;
        const COUPON_API_URL = '{{ route("api.coupons.index") }}';
        const COUPON_VALIDATE_URL = '{{ route("api.coupons.validate") }}';
        const CSRF = '{{ csrf_token() }}';

        function openVoucherModal() {
            const overlay = document.getElementById('voucher-modal-overlay');
            const modal = document.getElementById('voucher-modal');
            const navbar = document.getElementById('mainNavbar');
            overlay.style.display = 'block';
            modal.style.display = 'flex';
            // Push navbar below overlay (stacking context fix: backdrop-filter creates new ctx)
            if (navbar) navbar.style.zIndex = '1';
            document.body.style.overflow = 'hidden';
            // Trigger reflow for transition
            void overlay.offsetWidth;
            overlay.style.opacity = '1';
            modal.style.opacity = '1';
            modal.style.transform = 'translate(-50%, -50%) scale(1)';
            loadVouchers();
        }

        function closeVoucherModal() {
            const overlay = document.getElementById('voucher-modal-overlay');
            const modal = document.getElementById('voucher-modal');
            const navbar = document.getElementById('mainNavbar');
            overlay.style.opacity = '0';
            modal.style.opacity = '0';
            modal.style.transform = 'translate(-50%, -56%) scale(0.96)';
            setTimeout(() => {
                overlay.style.display = 'none';
                modal.style.display = 'none';
                // Restore navbar z-index & scroll
                if (navbar) navbar.style.zIndex = '';
                document.body.style.overflow = '';
            }, 300);
        }

        function loadVouchers() {
            const totalNow = subtotal + selectedCost;
            fetch(`${COUPON_API_URL}?subtotal=${totalNow}`)
                .then(r => r.json())
                .then(vouchers => renderVoucherList(vouchers))
                .catch(() => {
                    document.getElementById('voucher-list').innerHTML =
                        '<div style="text-align:center;padding:2rem;color:#EF4444;">Gagal memuat voucher.</div>';
                });
        }

        function renderVoucherList(vouchers) {
            const container = document.getElementById('voucher-list');
            if (!vouchers.length) {
                container.innerHTML = '<div style="text-align:center;padding:2rem;color:#94A3B8;font-size:0.875rem;">Belum ada voucher tersedia saat ini.</div>';
                return;
            }

            const categoryLabel = {
                product: '🛒 Diskon Produk', shipping: '🚚 Gratis Ongkir',
                event: '🎉 Event Spesial', member: '💎 Member', referral: '👥 Referral'
            };

            let html = '';
            vouchers.forEach(v => {
                const opacity = v.eligible ? '1' : '0.5';
                const cursor = v.eligible ? 'pointer' : 'default';
                const bgCard = v.eligible ? '#fff' : '#F8FAFC';
                const hint = v.already_used ? '⚠️ Sudah digunakan' : (!v.eligible ? '⚠️ Belum memenuhi syarat' : '');

                html += `
                                                                                <div onclick="${v.eligible ? `selectVoucher('${v.code}', '${v.estimated_discount_fmt || ''}', ${v.estimated_discount})` : ''}"
                                                                                     style="position:relative;border:1.5px solid ${v.eligible ? '#E2E8F0' : '#F1F5F9'};border-radius:12px;padding:0.875rem 1rem;margin-bottom:0.75rem;cursor:${cursor};background:${bgCard};opacity:${opacity};transition:all 0.2s;"
                                                                                     ${v.eligible ? 'onmouseover="this.style.borderColor=\'#1eb349\';this.style.boxShadow=\'0 4px 12px rgba(30,179,73,0.1)\'"' : ''}
                                                                                     ${v.eligible ? 'onmouseout="this.style.borderColor=\'#E2E8F0\';this.style.boxShadow=\'none\'"' : ''}>

                                                                                    <!-- Ticket cutouts left & right -->
                                                                                    <div style="position:absolute;left:-7px;top:50%;transform:translateY(-50%);width:14px;height:14px;background:#fff;border-radius:50%;border-right:1.5px solid ${v.eligible ? '#E2E8F0' : '#F1F5F9'};z-index:2;"></div>
                                                                                    <div style="position:absolute;right:-7px;top:50%;transform:translateY(-50%);width:14px;height:14px;background:#fff;border-radius:50%;border-left:1.5px solid ${v.eligible ? '#E2E8F0' : '#F1F5F9'};z-index:2;"></div>

                                                                                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.5rem;margin-bottom:0.25rem;">
                                                                                        <div>
                                                                                            <span style="display:inline-block;background:#F0F9FF;color:#1eb349;font-size:0.65rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:99px;margin-bottom:0.15rem;">${v.badge || categoryLabel[v.category] || v.category}</span>
                                                                                            <div style="font-size:0.9rem;font-weight:800;color:#0F172A;font-family:monospace;letter-spacing:0.5px;">${v.code}</div>
                                                                                        </div>
                                                                                        <div style="text-align:right;flex-shrink:0;">
                                                                                            <div style="font-size:1rem;font-weight:900;color:#1eb349;">${v.value}</div>
                                                                                            ${v.estimated_discount_fmt ? `<div style="font-size:0.65rem;color:#059669;font-weight:700;">${v.estimated_discount_fmt}</div>` : ''}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div style="font-size:0.75rem;color:#64748B;margin-bottom:0.35rem;line-height:1.2;">${v.description || ''}</div>
                                                                                    <div style="display:flex;flex-wrap:wrap;gap:0.3rem;font-size:0.65rem;">
                                                                                        <span style="background:#F1F5F9;color:#64748B;padding:0.1rem 0.4rem;border-radius:99px;">${v.min_purchase}</span>
                                                                                        ${v.max_discount ? `<span style="background:#F1F5F9;color:#64748B;padding:0.1rem 0.4rem;border-radius:99px;">${v.max_discount}</span>` : ''}
                                                                                        ${v.expired_label ? `<span style="background:#F1F5F9;color:#64748B;padding:0.1rem 0.4rem;border-radius:99px;">⏰ ${v.expired_label}</span>` : ''}
                                                                                        ${v.remaining !== null ? `<span style="background:#F1F5F9;color:#64748B;padding:0.1rem 0.4rem;border-radius:99px;">Sisa ${v.remaining}x</span>` : ''}
                                                                                        ${hint ? `<span style="background:#FEF2F2;color:#DC2626;padding:0.1rem 0.4rem;border-radius:99px;">${hint}</span>` : ''}
                                                                                    </div>
                                                                                </div>`;
            });
            container.innerHTML = html;
        }

        function selectVoucher(code, savingFmt, discountAmount) {
            document.getElementById('coupon_code_input').value = code;
            appliedVoucherDiscount = discountAmount;

            // Show applied state
            document.getElementById('voucher-applied-code').textContent = code;
            document.getElementById('voucher-applied-save').textContent = savingFmt || `Hemat Rp ${discountAmount.toLocaleString('id-ID')}`;
            document.getElementById('voucher-applied-state').style.display = 'flex';
            document.getElementById('voucher-picker-btn').style.display = 'none';

            updateTotal();
            closeVoucherModal();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `Voucher ${code} diterapkan!`,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
            });
        }

        function clearVoucher() {
            document.getElementById('coupon_code_input').value = '';
            appliedVoucherDiscount = 0;
            document.getElementById('voucher-applied-state').style.display = 'none';
            document.getElementById('voucher-picker-btn').style.display = 'flex';
            updateTotal();
        }

        function applyManualVoucher() {
            const code = document.getElementById('voucher-manual-input').value.trim();
            const msg = document.getElementById('voucher-manual-msg');
            if (!code) return;

            const totalNow = subtotal + selectedCost;
            msg.style.display = 'block';
            msg.style.color = '#64748B';
            msg.textContent = 'Memvalidasi voucher...';

            fetch(COUPON_VALIDATE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ code, subtotal: totalNow }),
            })
                .then(r => r.json())
                .then(data => {
                    if (data.valid) {
                        msg.style.color = '#059669';
                        msg.textContent = '✓ ' + data.message;
                        setTimeout(() => {
                            selectVoucher(data.code, data.discount_fmt ? 'Hemat ' + data.discount_fmt : '', data.discount);
                        }, 600);
                    } else {
                        msg.style.color = '#EF4444';
                        msg.textContent = '✗ ' + (data.message || 'Voucher tidak valid.');
                    }
                })
                .catch(() => {
                    msg.style.color = '#EF4444';
                    msg.textContent = '✗ Gagal memvalidasi. Coba lagi.';
                });
        }

        // Override updateTotal to include voucher discount
        function updateTotal() {
            const shippingCost = selectedCost || 0;
            const discount = appliedVoucherDiscount || 0;
            const total = Math.max(0, subtotal + totalFees + shippingCost - discount);
            document.getElementById('total-row-val').textContent = 'Rp ' + total.toLocaleString('id-ID');

            // Show/update discount row
            let discRow = document.getElementById('voucher-discount-row');
            if (discount > 0) {
                if (!discRow) {
                    discRow = document.createElement('div');
                    discRow.id = 'voucher-discount-row';
                    discRow.className = 'summary-row';
                    discRow.style.color = '#059669';
                    const ongkirRow = document.getElementById('ongkir-row');
                    if (ongkirRow) ongkirRow.after(discRow);
                    else document.getElementById('total-row-val').parentElement.before(discRow);
                }
                discRow.innerHTML = `<span>Diskon Voucher</span><span style="font-weight:700;color:#059669;">- Rp ${discount.toLocaleString('id-ID')}</span>`;
            } else if (discRow) {
                discRow.remove();
            }

            // Update hidden shipping input as well
            document.getElementById('shipping_cost_input').value = shippingCost;
        }

        // Auto-load voucher with best discount on page load
        document.addEventListener('DOMContentLoaded', function () {
            fetch(`${COUPON_API_URL}?subtotal=${subtotal}`)
                .then(r => r.json())
                .then(vouchers => {
                    const best = vouchers.find(v => v.eligible && v.estimated_discount > 0);
                    if (best) {
                        // Show subtle suggestion
                        const btn = document.getElementById('voucher-picker-btn');
                        if (btn) {
                            const hint = document.createElement('div');
                            hint.style = 'font-size:0.72rem;color:#059669;margin-top:0.35rem;text-align:center;font-weight:600;';
                            hint.textContent = `✓ Ada voucher tersedia: hemat hingga ${best.estimated_discount_fmt || 'Rp ' + best.estimated_discount.toLocaleString('id-ID')}`;
                            btn.insertAdjacentElement('afterend', hint);
                        }
                    }
                })
                .catch(() => { });
        });

        function toggleFeePopover(event, el) {
            event.stopPropagation();
            const wrapper = el.closest('.fee-info-wrapper');
            const popover = wrapper.querySelector('.fee-popover');
            const isOpen = popover.classList.contains('show');

            document.querySelectorAll('.fee-popover.show').forEach(p => p.classList.remove('show'));
            document.querySelectorAll('.fee-info-icon.active').forEach(i => i.classList.remove('active'));

            if (!isOpen) {
                popover.classList.add('show');
                el.classList.add('active');
            }
        }

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.fee-info-wrapper')) {
                document.querySelectorAll('.fee-popover.show').forEach(p => p.classList.remove('show'));
                document.querySelectorAll('.fee-info-icon.active').forEach(i => i.classList.remove('active'));
            }
        });

        // ── Atas Nama Tiket: Toggle antara nama akun vs manual ─────────────
        // State per-input disimpan di dataset.mode (default: 'akun')
        function toggleTicketSlot(inputId, authName) {
            const input = document.getElementById(inputId);
            const toggleBtn = document.getElementById('toggle-slot-' + inputId);
            const labelSpan = document.getElementById('toggle-label-' + inputId);
            if (!input || !toggleBtn) return;

            const currentMode = input.dataset.mode || 'akun';

            if (currentMode === 'akun') {
                // Switch ke manual: kosongkan, aktifkan edit
                input.dataset.mode = 'manual';
                input.value = '';
                input.readOnly = false;
                input.style.background = '#fff';
                input.style.borderColor = '#93C5FD';
                input.placeholder = 'Masukkan nama lengkap pemegang tiket ini...';
                input.focus();

                // Update tombol: tampilkan opsi "Pakai Nama Akun"
                labelSpan.textContent = 'Pakai Nama Akun';
                toggleBtn.style.color = '#64748B';
                toggleBtn.style.borderColor = '#E2E8F0';
                toggleBtn.style.backgroundColor = '#F8FAFC';

                // Ganti icon ke user-icon
                toggleBtn.querySelector('svg').innerHTML =
                    '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>';

            } else {
                // Switch kembali ke akun: isi ulang nama akun, read-only-feel
                input.dataset.mode = 'akun';
                input.value = authName;
                input.readOnly = false;   // tetap editable tapi prefilled
                input.style.background = '#F8FAFC';
                input.style.borderColor = '';
                input.placeholder = 'Nama dari akun Anda (bisa diganti)';

                // Kembalikan tombol
                labelSpan.textContent = 'Ganti Nama';
                toggleBtn.style.color = '#1eb349';
                toggleBtn.style.borderColor = '#BBF7D0';
                toggleBtn.style.backgroundColor = '#F0FDF4';

                // Kembalikan icon ke edit-icon
                toggleBtn.querySelector('svg').innerHTML =
                    '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>';
            }
        }

        // Inisialisasi: set mode 'akun' pada semua slot dengan data-auth-name
        document.querySelectorAll('input[data-auth-name]').forEach(function (input) {
            input.dataset.mode = 'akun';
        });

        // ── Identitas Resmi Tiket: Dropdown jenis identitas & placeholder dinamis
        window.updateIdentitySlot = function (key) {
            const sel = document.getElementById('ticket_id_type_' + key);
            const inp = document.getElementById('ticket_nik_' + key);
            if (!sel || !inp) return;

            const val = sel.value;
            if (val === 'KTP') {
                inp.placeholder = 'Nomor KTP (16 digit angka)...';
                inp.inputMode = 'numeric';
                inp.value = inp.value.replace(/\D/g, '').slice(0, 16);
            } else if (val === 'SIM') {
                inp.placeholder = 'Nomor SIM...';
                inp.inputMode = 'text';
            } else if (val === 'Paspor') {
                inp.placeholder = 'Nomor Paspor (Passport No)...';
                inp.inputMode = 'text';
            } else if (val === 'Kartu Pelajar') {
                inp.placeholder = 'Nomor Kartu Pelajar / KTM...';
                inp.inputMode = 'text';
            } else {
                inp.placeholder = 'Nomor identitas resmi...';
                inp.inputMode = 'text';
            }
        };

        // ──────────────────────────────────────────────────────────
        // FREE CLAIM REQUIREMENTS — Verification Timer (5 detik)
        // ──────────────────────────────────────────────────────────
        const FREE_TIMER_MS = 5000;
        const freeTimerState = {};

        // ── Per-Step Screenshot Proof Upload + Canvas WebP Compression ──────────────
        const SCREENSHOT_REQUIRED = @json($requiresScreenshot ?? false);

        window.triggerStepUpload = function (stepId) {
            const fi = document.getElementById('file_' + stepId);
            if (fi) fi.click();
        };

        window.clearStepProof = function (stepId) {
            const input = document.getElementById('proof_data_' + stepId);
            if (input) input.value = '';
            const fi = document.getElementById('file_' + stepId);
            if (fi) fi.value = '';
            const thumb = document.getElementById('thumb_' + stepId);
            if (thumb) thumb.src = '';
            const preview = document.getElementById('preview_' + stepId);
            if (preview) preview.style.display = 'none';
            const btn = document.getElementById('btn_upload_' + stepId);
            if (btn) {
                btn.style.display = 'inline-flex';
                btn.style.borderColor = '#86EFAC';
                btn.style.backgroundColor = '#F0FDF4';
                btn.style.color = '#15803D';
            }
        };

        window.handleStepFileChange = function (fileInput, stepId) {
            if (!fileInput.files || !fileInput.files[0]) return;
            const file = fileInput.files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = new Image();
                img.onload = function () {
                    const MAX_W = 1080;
                    let w = img.width, h = img.height;
                    if (w > MAX_W) {
                        h = Math.round(h * MAX_W / w);
                        w = MAX_W;
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = w;
                    canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, w, h);
                    const dataUrl = canvas.toDataURL('image/webp', 0.72);

                    // Set input & thumbnail
                    const dataInput = document.getElementById('proof_data_' + stepId);
                    if (dataInput) dataInput.value = dataUrl;

                    const thumb = document.getElementById('thumb_' + stepId);
                    if (thumb) thumb.src = dataUrl;

                    const preview = document.getElementById('preview_' + stepId);
                    if (preview) preview.style.display = 'inline-flex';

                    const btn = document.getElementById('btn_upload_' + stepId);
                    if (btn) btn.style.display = 'none';
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        };

        function startTimer(stepId) {
            if (freeTimerState[stepId]) return;

            const timerEl = document.getElementById('timer_' + stepId);
            const timerTxt = document.getElementById('timer_' + stepId + '_txt');
            const icoEl = document.getElementById('ico_' + stepId);

            if (timerEl) {
                timerEl.style.display = 'inline-flex';
            }

            let remaining = Math.ceil(FREE_TIMER_MS / 1000);
            if (timerTxt) timerTxt.textContent = 'Verifikasi dalam ' + remaining + ' detik...';

            const interval = setInterval(function () {
                remaining--;
                if (remaining > 0) {
                    if (timerTxt) timerTxt.textContent = 'Verifikasi dalam ' + remaining + ' detik...';
                } else {
                    clearInterval(interval);
                    freeTimerState[stepId] = true;

                    if (icoEl) {
                        icoEl.style.background = '#DCFCE7';
                        icoEl.innerHTML = '<svg width="20" height="20" fill="none" stroke="#16A34A" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>';
                    }

                    const stepEl = document.getElementById('step_' + stepId);
                    if (stepEl) {
                        stepEl.style.background = '#F0FDF4';
                        stepEl.style.borderColor = '#86EFAC';
                    }

                    if (timerTxt) timerTxt.textContent = '✓ Terverifikasi';
                    if (timerEl) {
                        timerEl.style.background = '#DCFCE7';
                        timerEl.style.color = '#15803D';
                    }

                    checkAllFreeRequirements();
                }
            }, 1000);
        }

        function checkAllFreeRequirements() {
            const btn = document.getElementById('btn-claim-free');
            if (!btn || btn.tagName.toLowerCase() === 'a') return;

            const allSteps = document.querySelectorAll('.claim-step[data-type="timer"]');
            let allDone = true;

            allSteps.forEach(function (step) {
                const key = step.getAttribute('data-step-key');
                if (!freeTimerState[key]) allDone = false;
            });

            if (allDone && allSteps.length > 0) {
                btn.disabled = false;
                btn.style.background = 'linear-gradient(135deg,#1eb349,#a5cf37)';
                btn.style.cursor = 'pointer';
                btn.style.boxShadow = '0 6px 20px rgba(30,179,73,.35)';
                const txt = document.getElementById('btn-claim-text');
                if (txt) txt.textContent = 'Klaim Gratis';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            checkAllFreeRequirements();

            // ── Auto-fill Nomor Meja dari Link in Bio QR Code (HANYA KHUSUS PRODUK MAKANAN / F&B) ──
            (function () {
                // Bersihkan cache localStorage lama agar tidak menempel di semua produk
                try { localStorage.removeItem('buyle_table_number'); } catch (e) { }

                @if($summary['checkout_type'] === 'food' || $summary['checkout_type'] === 'food_service')
                    const urlParams = new URLSearchParams(window.location.search);
                    const meja = urlParams.get('meja') || sessionStorage.getItem('buyle_table_number');
                    if (meja) {
                        const decodedMeja = decodeURIComponent(meja).trim();
                        try { sessionStorage.setItem('buyle_table_number', decodedMeja); } catch (e) { }

                        // 1. Opsi F&B: Ubah ke Dine-In & Isi Nomor Meja
                        const fnbSelect = document.getElementById('fnb_service_type_select');
                        const fnbTableInput = document.getElementById('fnb_table_number_input');
                        if (fnbSelect) {
                            fnbSelect.value = 'dine_in';
                            if (typeof toggleFnbFields === 'function') {
                                toggleFnbFields('dine_in');
                            } else {
                                const dineInField = document.getElementById('fnb_field_dine_in');
                                const deliveryField = document.getElementById('fnb_field_delivery');
                                const takeawayField = document.getElementById('fnb_field_takeaway');
                                if (dineInField) dineInField.style.display = 'block';
                                if (deliveryField) deliveryField.style.display = 'none';
                                if (takeawayField) takeawayField.style.display = 'none';
                            }
                        }
                        if (fnbTableInput) {
                            fnbTableInput.value = decodedMeja;
                        }

                        // 2. Tambahkan hidden input table_number ke form checkout
                        const form = document.getElementById('checkoutForm');
                        if (form) {
                            let hiddenInput = form.querySelector('input[name="table_number"]');
                            if (!hiddenInput) {
                                hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = 'table_number';
                                form.appendChild(hiddenInput);
                            }
                            hiddenInput.value = decodedMeja;
                        }

                        // 3. Banner visual notifikasi meja di bagian atas form checkout
                        const coLeft = document.querySelector('.co-left') || form;
                        if (coLeft && !document.getElementById('checkoutTableNotice')) {
                            const notice = document.createElement('div');
                            notice.id = 'checkoutTableNotice';
                            notice.style.cssText = 'background:#ffffff; border:1px solid #86efac; border-radius:12px; padding:0.85rem 1.15rem; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; gap:12px; box-shadow:0 1px 4px rgba(30,179,73,0.06);';
                            notice.innerHTML = `
                                                                                                                                                        <div style="display:flex; align-items:center; gap:10px; min-width:0;">
                                                                                                                                                            <div style="width:34px; height:34px; border-radius:8px; background:#1eb349; color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                                                                                                                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                                                                                                                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                                                                                                                                                    <path d="M3 9h18M9 21V9" />
                                                                                                                                                                </svg>
                                                                                                                                                            </div>
                                                                                                                                                            <div style="min-width:0;">
                                                                                                                                                                <div style="font-size:0.7rem; color:#166534; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Pesanan</div>
                                                                                                                                                                <div style="font-size:0.92rem; font-weight:800; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${decodedMeja}</div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                        <div style="font-size:0.7rem; color:#15803d; font-weight:700; background:#dcfce7; padding:4px 10px; border-radius:99px; flex-shrink:0; border:1px solid #bbf7d0;">
                                                                                                                                                            Auto Diantar ke Meja
                                                                                                                                                        </div>
                                                                                                                                                    `;
                            coLeft.insertBefore(notice, coLeft.firstChild);
                        }
                    }
                @else
                    // Bukan produk makanan: Bersihkan sesi meja agar tidak terbawa
                    try { sessionStorage.removeItem('buyle_table_number'); } catch (e) { }
                @endif
                                                                            })();
        });
    </script>
@endsection