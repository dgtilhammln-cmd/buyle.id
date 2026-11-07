@extends('creator.layout')

@section('title', 'Kasir Digital (POS)')
@section('page_title', 'Kasir Digital (POS)')
@section('page_subtitle', 'Kasir digital cepat & praktis. Auto-sync produk dari Web Builder & katalog toko Anda.')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Montserrat', sans-serif;
            box-sizing: border-box;
        }

        /* ── Card Styling (Sama dengan Profil & Web Builder) ──────────────── */
        .prof-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: visible;
            margin-bottom: 1.25rem;
        }

        .prof-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-bottom: 1.5px solid #f1f5f9;
            background: #fafbfa;
            font-size: 0.78rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .prof-card-head svg {
            color: #1eb349;
        }

        .prof-card-body {
            padding: 1.5rem;
        }

        /* ── Form Inputs & Controls ───────────────────────────────────────── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-bottom: 0.85rem;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .form-input {
            height: 44px;
            padding: 0 1rem;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.84rem;
            font-weight: 500;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s ease;
            width: 100%;
        }

        .form-input:focus {
            border-color: #1eb349;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(30, 179, 73, 0.12);
        }

        /* ── Web Builder Signature Green Buttons ──────────────────────────── */
        .btn-submit-green {
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            border-radius: 999px !important;
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            color: #ffffff;
            font-size: 0.9rem;
            padding: 0.8rem 1.5rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            text-decoration: none;
        }

        .btn-submit-green:hover {
            background: linear-gradient(135deg, #16963c, #8cb82b);
            box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
            transform: translateY(-2px);
            color: #ffffff;
        }

        .btn-submit-green:active {
            transform: scale(0.98);
        }

        .btn-submit-green:disabled {
            background: #cbd5e1 !important;
            box-shadow: none !important;
            cursor: not-allowed !important;
            transform: none !important;
        }

        .btn-outline-bio {
            background: #ffffff;
            border: 1.5px solid #1eb349;
            color: #1eb349;
            font-weight: 700;
            font-size: 0.8rem;
            padding: 0.5rem 1.25rem;
            border-radius: 999px !important;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-outline-bio:hover {
            background: #f0fdf4;
            border-color: #16963c;
            color: #16963c;
            transform: translateY(-1px);
        }

        /* ── POS Layout Grid ──────────────────────────────────────────────── */
        .pos-wrapper {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(300px, 350px);
            gap: 1rem;
            align-items: start;
        }

        @media (max-width: 1100px) {
            .pos-wrapper {
                grid-template-columns: minmax(0, 1fr) 310px;
            }
        }


        /* Screen 2 Display Toggles */
        @media (min-width: 992px) {
            .pos-mobile-transactions-list {
                display: none !important;
            }
        }

        @media (max-width: 991px) {
            .pos-desktop-only-table {
                display: none !important;
            }
        }

        @media (max-width: 991px) {
            .pos-wrapper {
                grid-template-columns: 1fr;
            }
        }

        /* ── Search Input ─────────────────────────────────────────────────── */
        .search-input-wrap {
            position: relative;
            margin-bottom: 0.85rem;
        }

        .search-input-wrap svg {
            position: absolute;
            left: 14px;
            top: 14px;
            color: #94a3b8;
        }

        .search-input-wrap input {
            padding-left: 42px;
        }

        /* ── Product Grid ────────────────────────────────────────────────── */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.65rem;
        }

        @media (max-width: 576px) {
            .product-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.65rem;
        }
        }

        .product-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 0;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            user-select: none;
        }

        .product-card:hover {
            border-color: #1eb349;
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(30, 179, 73, 0.15);
        }

        .product-card:active {
            transform: scale(0.96);
        }

        .product-img-wrapper {
            width: 100%;
            height: 110px;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-placeholder-wrap svg {
            transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .pos-category-pills-wrap {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            margin: 6px 0 12px 0;
            padding: 2px;
        }

        .pos-category-pills-wrap::-webkit-scrollbar {
            display: none;
        }

        .pos-cat-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            font-size: 0.73rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
            user-select: none;
            font-family: 'Montserrat', sans-serif;
            box-shadow: none;
            flex-shrink: 0;
        }

        .pos-cat-pill:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
        }

        .pos-cat-pill.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff !important;
            border-color: transparent;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.25);
        }

        .pos-cat-pill .pos-cat-count {
            background: rgba(0, 0, 0, 0.06);
            color: inherit;
            padding: 1px 6px;
            border-radius: 20px;
            font-size: 0.68rem;
            font-weight: 700;
            margin-left: 2px;
            line-height: 1.2;
        }

        .pos-cat-pill.active .pos-cat-count {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        .product-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 0.35rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 0.8rem;
            font-weight: 700;
            color: #1eb349;
            margin-top: auto;
        }

        .product-qty-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #0f172a;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 4px 11px;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.2);
            transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: popBadge 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .product-qty-badge.has-qty {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.4) !important;
        }

        @keyframes popBadge {
            0% {
                transform: scale(0.5);
            }

            100% {
                transform: scale(1);
            }
        }

        /* ── Cart Items List ─────────────────────────────────────────────── */
        .cart-items-container {
            max-height: 260px;
            overflow-y: auto;
            padding-right: 4px;
            margin-bottom: 1rem;
        }

        .cart-item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .cart-item-thumb {
            width: 42px;
            height: 42px;
            border-radius: 9px;
            overflow: hidden;
            flex-shrink: 0;
            margin-right: 0.65rem;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
        }

        .cart-item-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .cart-item-img-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            background: #f8fafc;
        }

        .cart-item-info {
            flex: 1;
            padding-right: 0.5rem;
            min-width: 0;
        }

        .cart-item-title {
            font-weight: 700;
            font-size: 0.84rem;
            color: #0f172a;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cart-item-unit-price {
            font-size: 0.78rem;
            font-weight: 700;
            color: #166534;
            margin-top: 2px;
            white-space: nowrap;
        }

        .cart-qty-ctrl {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .btn-qty {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-qty:hover {
            background: #1eb349;
            color: #ffffff;
            border-color: #1eb349;
        }

        .btn-qty-plus {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.35) !important;
        }

        .btn-qty-plus:hover {
            background: linear-gradient(135deg, #16963c 0%, #8cb82b 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.5) !important;
            transform: scale(1.05);
        }

        .qty-num {
            font-weight: 800;
            font-size: 0.88rem;
            min-width: 22px;
            text-align: center;
        }

        /* ── Summary & Calculator Box ────────────────────────────────────── */
        .summary-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
            font-size: 0.83rem;
            color: #475569;
        }

        .summary-row>span:last-child,
        .summary-row>strong:last-child {
            white-space: nowrap;
            flex-shrink: 0;
            text-align: right;
        }

        .summary-total {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            border-top: 2px dashed #cbd5e1;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
        }

        /* ── Thermal Receipt Printer Animation ───────────────────────────── */
        .receipt-printer-box {
            position: relative;
            background: #ffffff;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .receipt-printer-slot {
            height: 10px;
            background: #0f172a;
            border-radius: 10px 10px 0 0;
            position: relative;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.6);
        }

        @keyframes receiptEjectAnim {
            0% {
                transform: translateY(-35px);
                opacity: 0.5;
            }

            45% {
                transform: translateY(8px);
                opacity: 0.95;
            }

            75% {
                transform: translateY(-2px);
                opacity: 1;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .receipt-ejecting {
            animation: receiptEjectAnim 0.65s cubic-bezier(0.25, 1, 0.5, 1) forwards !important;
        }

        @keyframes pulsePendingAnim {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.75;
                transform: scale(0.98);
            }
        }

        .status-badge-pending {
            background: #fef3c7 !important;
            color: #92400e !important;
            border: 1.5px dashed #f59e0b !important;
            animation: pulsePendingAnim 1.8s infinite ease-in-out;
        }

        .status-badge-paid {
            background: #ecfdf5 !important;
            color: #065f46 !important;
            border: 1.5px solid #10b981 !important;
        }

        /* ── Discount Type Toggle ────────────────────────────────────────── */
        .disc-toggle {
            display: flex;
            border: 1.5px solid #cbd5e1;
            border-radius: 999px;
            overflow: hidden;
            background: #fff;
            padding: 2px;
        }

        .disc-btn {
            padding: 3px 12px;
            font-size: 0.72rem;
            font-weight: 800;
            border: none;
            border-radius: 999px;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }

        .disc-btn.active {
            background: #1eb349;
            color: #fff;
        }

        /* ── CUSTOM MODAL OVERLAY SYSTEM (Ultra Compact & Minimalist) ──── */
        .pos-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            z-index: 99999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pos-modal-overlay.show {
            display: flex;
            opacity: 1;
        }

        .pos-modal-card {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 440px;
            max-height: calc(100vh - 100px); /* clear top + bottom nav */
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.25);
            transform: scale(0.96);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pos-modal-overlay.show .pos-modal-card {
            transform: scale(1);
        }

        .pos-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            background: #ffffff;
            flex-shrink: 0;
        }

        .pos-modal-head h5 {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.01em;
        }

        .pos-modal-body {
            padding: 1.25rem;
            overflow-y: auto;
            flex: 1;
        }

        .pos-modal-foot {
            padding: 0.85rem 1.25rem 1.25rem;
            display: flex;
            gap: 0.6rem;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            flex-shrink: 0;
        }

        /* ── Custom Table Styles (Independent from Bootstrap) ───────────── */
        .pos-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .pos-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Montserrat', sans-serif;
            text-align: left;
        }

        .pos-table th {
            background: #fafbfa;
            color: #475569;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 12px 16px;
            border-bottom: 1.5px solid #e2e8f0;
            white-space: nowrap;
        }

        .pos-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.83rem;
            vertical-align: middle;
        }

        .pos-table tr:hover td {
            background: #f8fafc;
        }

        /* ── Close Button ────────────────────────────────────────────────── */
        .pos-modal-close {
            background: #f1f5f9;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .pos-modal-close:hover {
            background: #fee2e2;
            color: #dc2626;
            transform: rotate(90deg);
        }

        /* ── Payment Options Cards (Minimalist Compact) ──────────────────── */
        .pay-option-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            margin-bottom: 0.5rem;
            background: #ffffff;
        }

        .pay-option-card:hover {
            border-color: #1eb349;
            background: #f8fafc;
        }

        .pay-option-card.selected {
            border-color: #1eb349;
            background: #f0fdf4;
            box-shadow: 0 0 0 1px #1eb349;
        }

        /* ── Quick Money Pill Buttons ────────────────────────────────────── */
        .btn-quick-cash {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #1e293b;
            font-weight: 700;
            font-size: 0.72rem;
            padding: 5px 14px;
            border-radius: 999px !important;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-quick-cash:hover {
            background: #1eb349;
            color: #ffffff;
            border-color: #1eb349;
        }

        /* ── Deposit Preset Buttons ──────────────────────────────────────── */
        .btn-deposit-preset {
            padding: 8px 10px;
            font-size: 0.8rem;
            font-weight: 700;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            background: #ffffff;
            color: #0f172a;
            cursor: pointer;
            transition: all 0.15s ease;
            text-align: center;
        }

        .btn-deposit-preset:hover {
            border-color: #1eb349;
            color: #166534;
            background: #f0fdf4;
        }

        .btn-deposit-preset.active {
            border-color: #1eb349;
            background: #1eb349;
            color: #ffffff;
        }

        /* ── History & Closing Shift Tabs ────────────────────────────────── */
        .history-tab-btn {
            background: transparent;
            border: none;
            border-bottom: 2.5px solid transparent;
            padding: 10px 16px;
            font-size: 0.82rem;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .history-tab-btn:hover {
            color: #1eb349;
        }

        .history-tab-btn.active {
            color: #166534;
            border-bottom-color: #1eb349;
            background: #f0fdf4;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        /* ── Mobile Fixed Bottom Drawer Bar ──────────────────────────────── */
        .mobile-cart-bar {
            display: none;
            position: fixed;
            bottom: 60px;
            left: 0;
            right: 0;
            background: #0f172a;
            color: #ffffff;
            padding: 0.9rem 1.25rem;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.25);
            z-index: 999;
            align-items: center;
            justify-content: space-between;
            border-radius: 16px 16px 0 0;
        }

        @media (max-width: 991px) {
            .mobile-cart-bar {
                display: flex;
            }
        }

        /* ── Thermal Receipt Styles ──────────────────────────────────────── */
        @media print {
            body * {
                visibility: hidden !important;
            }

            #printablePosReceiptArea,
            #printablePosReceiptArea * {
                visibility: visible !important;
            }

            #printablePosReceiptArea {
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
                width: 80mm !important;
                max-width: 80mm !important;
                padding: 4mm !important;
                margin: 0 !important;
                overflow: visible !important;
            }

            .no-print {
                display: none !important;
            }

            * {
                overflow: visible !important;
            }
        }

        /* ── Privacy Blur Overlay ───────────────────────────────── */
        #midtransBlurOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            z-index: 9990;
        }

        #midtransBlurOverlay.active {
            display: block;
        }

        /* Force Payment Gateway Snap iframe / modal on top of backdrop blur */
        #snap-container,
        iframe[src*="midtrans"],
        iframe[id*="snap"],
        .snap-modal,
        #snap-midtrans {
            z-index: 999999 !important;
        }

        /* ── POS Lightweight CSS Shimmer Skeletons ──────────────── */
        @keyframes posSkeletonShimmer {
            0% {
                background-position: -600px 0;
            }

            100% {
                background-position: 600px 0;
            }
        }

        .pos-skel-bone {
            background: linear-gradient(90deg, #f1f5f9 0%, #e2e8f0 50%, #f1f5f9 100%);
            background-size: 1200px 100%;
            animation: posSkeletonShimmer 1.5s infinite linear;
            border-radius: 6px;
        }

        .pos-skel-card {
            background: #ffffff;
            border: 1.5px solid #f1f5f9;
            border-radius: 16px;
            padding: 0.65rem;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        /* ── Minimalist Topbar Controls ──────────────── */
        .pos-topbar-bar {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .pos-topbar-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.76rem;
            font-weight: 700;
            color: #1e293b;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .pos-status-pulse {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #16a34a;
            display: inline-block;
            box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.25);
            flex-shrink: 0;
        }

        .pos-deposit-chip {
            border-color: #bbf7d0;
            background: #f0fdf4;
            padding: 3px 6px 3px 9px;
        }

        .pos-deposit-chip svg {
            color: #166534;
            flex-shrink: 0;
        }

        .pos-deposit-add-btn {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(30, 179, 73, 0.25);
            transition: transform 0.15s ease;
            margin-left: 2px;
            line-height: 1;
        }

        .pos-deposit-add-btn:hover {
            transform: scale(1.1);
        }

        .pos-topbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 11px;
            font-size: 0.76rem;
            font-weight: 700;
            color: #334155;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 999px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            white-space: nowrap;
            font-family: 'Montserrat', sans-serif;
        }

        .pos-topbar-btn:hover {
            border-color: #1eb349;
            color: #166534;
            background: #f0fdf4;
        }

        .pos-topbar-btn.pos-btn-back:hover {
            border-color: #64748b;
            color: #0f172a;
            background: #f8fafc;
        }

        .pos-topbar-btn.pos-btn-logout {
            border-color: #fecaca;
            background: #fff1f2;
            color: #ef4444;
        }

        .pos-topbar-btn.pos-btn-logout:hover {
            background: #fee2e2;
            border-color: #f87171;
            color: #b91c1c;
        }

        /* Mobile responsiveness for topbar & cashier drawer */
        @media (max-width: 768px) {
            .pos-topbar-bar {
                gap: 0.35rem;
            }

            .pos-topbar-chip {
                padding: 4px 8px;
                font-size: 0.72rem;
            }

            .pos-topbar-btn {
                padding: 5px 8px;
                font-size: 0.72rem;
            }

            .pos-btn-text {
                display: none;
            }

            .cashier-form-grid {
                grid-template-columns: 1fr !important;
            }
        }

        /* ── Fee & Discount Setting Components ──────────────── */
        .fee-card-row {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .fee-card-row:hover {
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .discount-card-row {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .discount-card-row:hover {
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .product-picker-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.15s ease;
            user-select: none;
        }

        .product-picker-item:hover {
            border-color: #bbf7d0;
            background: #f0fdf4;
        }

        .product-picker-item.selected {
            border-color: #1eb349;
            background: #f0fdf4;
            box-shadow: 0 0 0 1px #1eb349;
        }

        .product-picker-thumb {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            object-fit: cover;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            overflow: hidden;
        }

        .badge-scope-all {
            background: #e0e7ff;
            color: #3730a3;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .badge-scope-specific {
            background: #fef3c7;
            color: #92400e;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            white-space: nowrap;
        }

        /* Cart Promo and Fee Rows */
        .cart-fee-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: #334155;
            padding: 2px 0;
        }

        .cart-promo-active-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
        }

        /* ── POS Settings Hover Dropdown ──────────────────── */
        .pos-settings-dropdown-wrap {
            position: relative;
            display: inline-flex;
        }

        .pos-settings-dropdown-wrap .pos-settings-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 220px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 8px 28px rgba(15, 23, 42, 0.10), 0 2px 8px rgba(15, 23, 42, 0.06);
            padding: 6px;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px) scale(0.97);
            transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s;
            pointer-events: none;
        }

        /* Arrow caret pointing up */
        /* Invisible hover bridge */
        .pos-settings-dropdown-wrap .pos-settings-dropdown::after {
            content: '';
            position: absolute;
            top: -12px;
            left: 0;
            right: 0;
            height: 12px;
        }

        .pos-settings-dropdown-wrap .pos-settings-dropdown::before {
            content: '';
            position: absolute;
            top: -7px;
            right: 18px;
            width: 12px;
            height: 12px;
            background: #ffffff;
            border-left: 1.5px solid #e2e8f0;
            border-top: 1.5px solid #e2e8f0;
            transform: rotate(45deg);
        }

        .pos-settings-dropdown-wrap:hover .pos-settings-dropdown,
        .pos-settings-dropdown-wrap .pos-settings-dropdown:hover {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .pos-settings-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 12px;
            border: none;
            background: transparent;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            color: #334155;
            border-radius: 9px;
            cursor: pointer;
            text-align: left;
            text-decoration: none;
            transition: background 0.14s ease, color 0.14s ease;
        }

        .pos-settings-dropdown-item:hover {
            background: #f0fdf4;
            color: #15803d;
        }

        .pos-settings-dropdown-item .psd-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.14s;
        }

        .pos-settings-dropdown-item:hover .psd-icon {
            background: #dcfce7;
        }

        .pos-settings-dropdown-item .psd-label {
            flex: 1;
            line-height: 1.25;
        }

        .pos-settings-dropdown-item .psd-label small {
            display: block;
            font-size: 0.65rem;
            font-weight: 500;
            color: #94a3b8;
            margin-top: 1px;
        }

        .pos-settings-dropdown-item:hover .psd-label small {
            color: #6ee7a0;
        }

        .pos-settings-dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 4px 0;
        }

        /* Keep dropdown open when moving mouse from button to dropdown */
        .pos-settings-dropdown-wrap .pos-topbar-btn {
            position: relative;
            z-index: 1;
        }

        /* ═══════════════════════════════════════════════════════════════
                       MOBILE-FIRST POS OVERHAUL (100% Sesuai Mockup Gambar 3-Layar)
                       Pure Light Theme - Zero Dark Blocks - Shimmer Skeleton Effect
                       ═══════════════════════════════════════════════════════════════ */

        /* Shimmer Skeleton Animation */
        @keyframes shimmerSkel {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .pos-skel-bone {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%) !important;
            background-size: 200% 100% !important;
            animation: shimmerSkel 1.5s infinite ease-in-out !important;
            border-radius: 8px !important;
        }

        /* Mobile Top Header (Screen 1) */
        .pos-mobile-top-header {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0.25rem 1rem 0.25rem;
            margin-bottom: 0.25rem;
        }

        .pos-mobile-menu-btn {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: all 0.15s ease;
        }

        .pos-mobile-menu-btn:active {
            transform: scale(0.92);
            background: #f8fafc;
        }

        .pos-mobile-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .pos-mobile-subtitle {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 600;
            margin-top: 1px;
        }

        .pos-mobile-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 999px;
            padding: 7px 14px;
            font-size: 0.76rem;
            font-weight: 700;
            color: #0f172a;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: all 0.15s ease;
        }

        .pos-mobile-action-btn:active {
            transform: scale(0.95);
            background: #f8fafc;
        }

        /* Pill Search Wrap with Barcode Scanner Icon */
        .pos-pill-search {
            position: relative;
            margin-bottom: 1rem;
        }

        .pos-pill-search svg.search-ico {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .pos-pill-input {
            width: 100%;
            height: 48px !important;
            padding-left: 46px !important;
            padding-right: 48px !important;
            border-radius: 999px !important;
            border: 1.5px solid #e2e8f0 !important;
            background: #ffffff !important;
            font-size: 0.84rem !important;
            color: #0f172a !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02) !important;
            transition: all 0.2s ease !important;
        }

        .pos-pill-input:focus {
            border-color: #1eb349 !important;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15) !important;
            outline: none !important;
        }

        .pos-search-barcode-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .pos-search-barcode-btn:active {
            transform: translateY(-50%) scale(0.92);
            background: #f1f5f9;
        }

        /* Category Scrollable Track */
        .pos-category-pills-wrap {
            display: flex !important;
            gap: 8px !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            padding: 2px 2px 12px 2px !important;
            margin: 0 0 1rem 0 !important;
            scrollbar-width: none !important;
            flex-wrap: nowrap !important;
        }

        .pos-category-pills-wrap::-webkit-scrollbar {
            display: none !important;
        }

        .pos-cat-pill {
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            padding: 7px 16px !important;
            border-radius: 999px !important;
            border: 1.5px solid #e2e8f0 !important;
            background: #ffffff !important;
            color: #64748b !important;
            font-size: 0.78rem !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1) !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02) !important;
        }

        .pos-cat-pill:hover {
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        .pos-cat-pill.active {
            background: #f0fdf4 !important;
            border-color: #1eb349 !important;
            color: #1eb349 !important;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.18) !important;
        }

        /* Product Card (2 Columns - Screen 1 Mockup) */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 0.85rem;
        }

        .product-card {
            background: #ffffff;
            border: 1.5px solid #f1f5f9;
            border-radius: 14px;
            padding: 0;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            user-select: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
        }

        .product-card:hover {
            border-color: #bbf7d0;
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(30, 179, 73, 0.1);
        }

        .product-card.in-cart {
            border-color: #1eb349 !important;
            background: #ffffff !important;
            box-shadow: 0 6px 20px rgba(30, 179, 73, 0.15) !important;
        }

        .product-img-wrapper {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 0;
            overflow: hidden;
            background: #f1f5f9;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0;
        }

        /* Checkmark Badge on Top Right of Image when in Cart */
        .product-check-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(30, 179, 73, 0.4);
            z-index: 6;
            animation: popCheck 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes popCheck {
            0% {
                transform: scale(0.4);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .product-name {
            font-size: 0.84rem;
            font-weight: 700;
            color: #1a2e1a;
            margin: 0 0 0.25rem 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.35;
            min-height: 2.3em;
        }

        /* Bottom Row inside Product Card */
        .product-card-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 0.35rem;
            gap: 6px;
        }

        .product-price {
            font-size: 0.8rem;
            font-weight: 700;
            color: #1eb349;
            letter-spacing: -0.01em;
        }

        /* Circular Add Button */
        .card-btn-add {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #ffffff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.35);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            flex-shrink: 0;
        }

        .card-btn-add:active {
            transform: scale(0.9);
        }

        /* Card Mini Stepper */
        .card-stepper {
            display: flex;
            align-items: center;
            gap: 4px;
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 999px;
            padding: 3px 5px;
            box-shadow: 0 2px 6px rgba(30, 179, 73, 0.1);
        }

        .card-stepper-btn {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: none;
            background: #ffffff;
            color: #1eb349;
            font-weight: 800;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            transition: transform 0.1s ease;
        }

        .card-stepper-btn:active {
            transform: scale(0.88);
        }

        .card-stepper-btn.plus {
            background: #1eb349;
            color: #ffffff;
        }

        .card-stepper-num {
            font-size: 0.8rem;
            font-weight: 800;
            color: #0f172a;
            min-width: 16px;
            text-align: center;
        }

        /* ── Floating Cart Bar (Screen 1 Mockup) ── */
        .pos-mobile-floating-bar {
            display: none;
            position: fixed;
            bottom: 78px;
            left: 14px;
            right: 14px;
            max-width: 520px;
            margin: 0 auto;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 26px;
            padding: 0.75rem 1.15rem;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 14px 40px rgba(0, 0, 0, 0.12);
            z-index: 9990;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pos-mobile-floating-bar:hover {
            border-color: #bbf7d0;
        }

        .pos-mf-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            flex: 1;
        }

        .pos-mf-chevron {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .pos-mf-info {
            display: flex;
            flex-direction: column;
        }

        .pos-mf-count {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .pos-mf-total {
            font-size: 1.12rem;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.15;
            letter-spacing: -0.01em;
        }

        .pos-mf-btn-next {
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #ffffff;
            border: none;
            border-radius: 999px;
            padding: 0.75rem 1.6rem;
            font-size: 0.88rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(30, 179, 73, 0.38);
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .pos-mf-btn-next:active {
            transform: scale(0.95);
        }

        /* ── Slide-up Cart Drawer Sheet on Mobile ── */
        .pos-cart-drawer-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 99990;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .pos-cart-drawer-overlay.open {
            display: block;
            opacity: 1;
        }

        .pos-cart-drawer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            max-width: 520px;
            margin: 0 auto;
            max-height: 82vh;
            background: #ffffff;
            border-radius: 28px 28px 0 0;
            box-shadow: 0 -12px 40px rgba(0, 0, 0, 0.18);
            z-index: 99995;
            display: flex;
            flex-direction: column;
            transform: translateY(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .pos-cart-drawer.open {
            transform: translateY(0);
        }

        .pos-drawer-handle {
            width: 44px;
            height: 5px;
            border-radius: 999px;
            background: #cbd5e1;
            margin: 10px auto 4px auto;
        }

        .pos-drawer-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .pos-drawer-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .pos-drawer-body {
            padding: 1rem 1.25rem;
            overflow-y: auto;
            flex: 1;
        }

        /* ── Confirm Sale & Screen 3 Polish (Modal Customization) ── */
        .confirm-sale-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 0.85rem;
        }

        .confirm-info-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.65rem 0.85rem;
        }

        .confirm-info-label {
            font-size: 0.68rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 3px;
        }

        .confirm-info-value {
            font-size: 0.88rem;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .confirm-cust-box {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .confirm-pill-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .confirm-action-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #166534;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .confirm-action-pill:active {
            transform: scale(0.95);
            background: #dcfce7;
        }

        /* ── Screen 2 Transactions (Mobile List UI) ── */
        .pos-trx-card {
            background: #ffffff;
            border: 1.5px solid #f1f5f9;
            border-radius: 18px;
            padding: 0.85rem 1rem;
            margin-bottom: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .pos-trx-card:hover {
            border-color: #bbf7d0;
            transform: translateY(-2px);
        }

        .pos-trx-card:active {
            transform: scale(0.98);
        }

        .pos-trx-left {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .pos-trx-num {
            font-size: 0.88rem;
            font-weight: 800;
            color: #0f172a;
        }

        .pos-trx-meta {
            font-size: 0.72rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pos-trx-right {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 3px;
        }

        .pos-trx-amount {
            font-size: 0.95rem;
            font-weight: 900;
            color: #0f172a;
        }

        .pos-badge-paid {
            font-size: 0.68rem;
            font-weight: 800;
            padding: 2px 9px;
            border-radius: 999px;
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            display: inline-block;
        }

        /* ── 3 Tombol Aksi Bulat di Kartu Transaksi ── */
        .pos-trans-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .pos-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.14s, box-shadow 0.14s;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .pos-action-btn:active { transform: scale(0.9); }
        .pos-action-btn:disabled { opacity: 0.4; cursor: not-allowed; }

        /* Icon uang – cash pending */
        .pos-action-btn.btn-cash-pending {
            background: #fff7ed;
            color: #ea580c;
            border: 1.5px solid #fed7aa;
        }

        /* Icon ceklis 1 – sudah lunas */
        .pos-action-btn.btn-paid {
            background: #f0fdf4;
            color: #16a34a;
            border: 1.5px solid #bbf7d0;
        }

        /* Icon centang 2 – selesai */
        .pos-action-btn.btn-complete {
            background: #eff6ff;
            color: #2563eb;
            border: 1.5px solid #bfdbfe;
        }

        /* Kartu transaksi baru – highlight animasi */
        .pos-trans-card.new-order-flash {
            animation: newOrderPulse 2.5s ease;
        }

        @keyframes newOrderPulse {
            0%   { background: #f0fdf4; border-color: #22c55e; }
            40%  { background: #dcfce7; border-color: #16a34a; box-shadow: 0 0 0 4px rgba(34,197,94,0.15); }
            100% { background: #ffffff; border-color: #f1f5f9; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
        }

        /* ── Responsive Mobile Overrides (< 991px) ── */
        @media (max-width: 991px) {
            .pos-mobile-top-header {
                display: flex !important;
            }

            .pos-wrapper {
                grid-template-columns: 1fr !important;
                gap: 0 !important;
            }

            #cartPanel {
                display: none !important;
                /* On mobile, cart is in floating bar & drawer */
            }

            .pos-mobile-floating-bar {
                display: flex !important;
            }

            .mobile-cart-bar {
                display: none !important;
                /* replaced by floating bar */
            }

            .product-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.75rem !important;
                padding-bottom: 120px !important;
                /* prevent floating bar overlap */
            }

            .prof-card {
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
                padding: 0 !important;
            }

            .prof-card-head {
                display: none !important;
                /* replaced by sleek mobile top header */
            }

            .prof-card-body {
                padding: 0 !important;
            }

            .pos-topbar-bar {
                display: none !important;
                /* Hide desktop topbar on mobile */
            }

            /* Curved, Compact, Mobile-Friendly Modal Card for Payment */
            #posPaymentModal {
                align-items: center !important;
                justify-content: center !important;
                padding: 12px !important;
            }
            #posPaymentModal .pos-modal-card {
                width: 100% !important;
                max-width: 440px !important;
                height: auto !important;
                max-height: 88vh !important;
                border-radius: 28px !important;
                margin: auto !important;
                display: flex !important;
                flex-direction: column !important;
                border: 1.5px solid #e2e8f0 !important;
                padding: 0 !important;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25) !important;
                overflow: hidden !important;
                background: #ffffff !important;
            }

            #posPaymentModal .pos-modal-head {
                border-radius: 28px 28px 0 0 !important;
                padding: 0.85rem 1.15rem !important;
                border-bottom: 1.5px solid #f1f5f9 !important;
                background: #ffffff !important;
            }

            #posPaymentModal .pos-modal-body {
                flex: 1 1 auto !important;
                overflow-y: auto !important;
                max-height: calc(88vh - 125px) !important;
                padding: 0.9rem 1.15rem !important;
                background: #ffffff !important;
            }

            #posPaymentModal .pos-modal-foot {
                border-radius: 0 0 28px 28px !important;
                padding: 0.85rem 1.15rem !important;
                background: #ffffff !important;
                border-top: 1.5px solid #f1f5f9 !important;
                box-shadow: 0 -4px 14px rgba(0, 0, 0, 0.03) !important;
            }

        }
    
        /* Hide creator superapp bottom nav specifically inside Buyle POS */
        .superapp-bottom-nav {
            display: none !important;
        }

        
        /* ═══════════════════════════════════════════════════════════════════════
           PIXEL-PERFECT BUYLE POS (100% MATCHING REFERENCE MOCKUP SCREENSHOT)
           ═══════════════════════════════════════════════════════════════════════ */
        
        /* Background & Container */
        @media (max-width: 991px) {
            body {
                background: #ffffff !important;
            }
            .cr-main-wrapper {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .pos-wrapper {
                display: block !important;
                padding: max(0.75rem, calc(env(safe-area-inset-top, 0px) + 0.65rem)) 1rem 180px 1rem !important;
                max-width: 480px !important;
                margin: 0 auto !important;
            }
            .prof-card {
                border: none !important;
                background: transparent !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .prof-card-head {
                display: none !important;
            }
            .prof-card-body {
                padding: 0 !important;
            }
        }

        /* 1. Header (Screen 1 Reference) */
        .pos-ref-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            width: 100%;
        }
        .pos-ref-menu-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #2d2013;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }
        .pos-ref-menu-btn:active {
            transform: scale(0.92);
            background: #f8fafc;
        }
        .pos-ref-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #2d2013;
            margin: 0;
            text-align: center;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }
        .pos-ref-add-btn {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 999px;
            padding: 8px 18px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #2d2013;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
            white-space: nowrap;
        }
        .pos-ref-add-btn:active {
            transform: scale(0.94);
            background: #ffffff;
        }

        /* Period Filter Quick Buttons (Buyle Style) */
        .pos-period-btn {
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 999px;
            padding: 4px 10px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
            font-family: inherit;
        }
        .pos-period-btn:hover {
            color: #166534;
            border-color: #bbf7d0;
            background: #f0fdf4;
        }
        .pos-period-btn.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            border-color: transparent !important;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.3) !important;
        }
        .pos-modal-preset-btn {
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #334155;
            padding: 8px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
            text-align: center;
            font-family: inherit;
        }
        .pos-modal-preset-btn:hover {
            border-color: #bbf7d0;
            background: #f0fdf4;
            color: #166534;
        }
        .pos-modal-preset-btn.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            border-color: transparent !important;
        }

        /* 2. Search Bar Capsule (Screen 1 Reference) */
        .pos-ref-search-wrap {
            position: relative;
            margin-bottom: 1rem;
            width: 100%;
        }
        .pos-ref-search-input {
            width: 100%;
            height: 52px !important;
            padding-left: 48px !important;
            padding-right: 52px !important;
            border-radius: 999px !important;
            border: 1.5px solid #e2e8f0 !important;
            background: #ffffff !important;
            font-size: 0.85rem !important;
            color: #2d2013 !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02) !important;
            outline: none !important;
            transition: all 0.2s ease !important;
        }
        .pos-ref-search-input:focus {
            border-color: #1eb349 !important;
            box-shadow: 0 0 0 3px rgba(30,179,73,0.12) !important;
        }
        .pos-ref-search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #b5a89b;
            pointer-events: none;
        }
        .pos-ref-barcode-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1eb349;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .pos-ref-barcode-btn:active {
            transform: translateY(-50%) scale(0.92);
        }

        /* 3. Category Filter Horizontal Pills (Screen 1 Reference) */
        .pos-ref-cat-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            overflow-x: auto;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 12px;
            margin-bottom: 0.5rem;
            width: 100%;
        }
        .pos-ref-cat-bar::-webkit-scrollbar {
            display: none;
        }
        .pos-ref-cat-pill {
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            padding: 8px 18px;
            border-radius: 999px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .pos-ref-cat-pill:hover {
            color: #166534;
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .pos-ref-cat-pill.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35) !important;
            border: 1.5px solid transparent !important;
        }

        /* 4. Product Card & 2-Col Grid (Screen 1 Reference) */
        .product-grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        @media (min-width: 640px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }
        @media (min-width: 992px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }

        .product-card {
            background: #ffffff !important;
            border-radius: 20px !important;
            padding: 10px !important;
            border: 1.5px solid #e2e8f0 !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02) !important;
            display: flex !important;
            flex-direction: column !important;
            position: relative !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            user-select: none !important;
            overflow: visible !important;
            box-sizing: border-box !important;
        }
        .product-card:active {
            transform: scale(0.97);
        }
        .product-card.in-cart {
            border-color: #1eb349 !important;
            box-shadow: 0 6px 20px rgba(30,179,73,0.15) !important;
        }
        /* Search filter hides - overrides in-cart display */
        .product-card.pos-filtered-hidden {
            display: none !important;
        }

        .product-card .product-img-wrapper {
            width: 100% !important;
            height: 115px !important;
            border-radius: 14px !important;
            overflow: hidden !important;
            background: #f8fafc !important;
            position: relative !important;
            margin-bottom: 8px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .product-card .product-img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 14px !important;
            display: block !important;
        }

        /* Checkmark Badge on Top Right of Image - HANYA MUNCUL KETIKA PRODUK DIPILIH (IN-CART) */
        .product-card .product-check-badge {
            position: absolute !important;
            top: 6px !important;
            right: 6px !important;
            width: 22px !important;
            height: 22px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
            display: none !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 10 !important;
            box-shadow: 0 2px 6px rgba(30,179,73,0.35) !important;
        }
        .product-card.in-cart .product-check-badge {
            display: flex !important;
        }

        .product-card .product-name {
            font-size: 0.82rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            margin: 0 0 4px 0 !important;
            line-height: 1.3 !important;
            min-height: 2.6em !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }

        .product-card .product-card-bottom {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            margin-top: auto !important;
            padding-top: 2px !important;
        }

        .product-card .product-price {
            font-size: 0.85rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            letter-spacing: -0.01em !important;
        }

        /* Round + Button on Product Card */
        .product-card .card-btn-add {
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
            color: #ffffff !important;
            border: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            box-shadow: 0 3px 8px rgba(30,179,73,0.3) !important;
            transition: all 0.15s ease !important;
            font-size: 16px !important;
            line-height: 1 !important;
        }
        .product-card .card-btn-add:active {
            transform: scale(0.9);
        }

        /* Stepper in Card (- qty +) */
        .product-card .card-stepper {
            display: flex !important;
            align-items: center !important;
            gap: 3px !important;
            background: #ffffff !important;
            border-radius: 999px !important;
            padding: 2px 4px !important;
        }
        .product-card .card-stepper-btn {
            width: 22px !important;
            height: 22px !important;
            border-radius: 50% !important;
            border: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            font-weight: 800 !important;
            font-size: 13px !important;
            line-height: 1 !important;
        }
        .product-card .card-stepper-btn.minus {
            background: #ffffff !important;
            color: #166534 !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
        }
        .product-card .card-stepper-btn.plus {
            background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(30,179,73,0.3) !important;
        }
        .product-card .card-stepper-num {
            display: none !important;
        }

        /* 5. Floating Cart Bar (Screen 1 Reference) */
        .pos-mobile-floating-bar {
            display: none;
            position: fixed !important;
            bottom: 84px !important;
            left: 16px !important;
            right: 16px !important;
            max-width: 440px !important;
            margin: 0 auto !important;
            height: 62px !important;
            background: #ffffff !important;
            border-radius: 999px !important;
            padding: 6px 8px 6px 14px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
            border: 1.5px solid #e2e8f0 !important;
            align-items: center !important;
            justify-content: space-between !important;
            z-index: 9980 !important;
        }
        @media (max-width: 991px) {
            .pos-mobile-floating-bar {
                display: flex !important;
            }
        }
        .pos-mf-left {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            cursor: pointer !important;
        }
        .pos-mf-chevron {
            width: 38px !important;
            height: 38px !important;
            border-radius: 50% !important;
            background: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #1eb349 !important;
        }
        .pos-mf-info {
            display: flex !important;
            flex-direction: column !important;
        }
        .pos-mf-count {
            font-size: 0.7rem !important;
            color: #64748b !important;
            font-weight: 600 !important;
        }
        .pos-mf-total {
            font-size: 0.95rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            line-height: 1.1 !important;
        }
        .pos-mf-btn-next {
            background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
            color: #ffffff !important;
            border-radius: 999px !important;
            padding: 10px 24px !important;
            font-size: 0.84rem !important;
            font-weight: 700 !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(30,179,73,0.35) !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
            transition: all 0.15s ease !important;
        }
        .pos-mf-btn-next:active {
            transform: scale(0.95);
        }

        /* 6. Buyle POS Bottom Nav CSS moved below */

        .pos-bottom-nav-wrap {
            display: none;
            position: fixed !important;
            bottom: 16px !important;
            left: 14px !important;
            right: 14px !important;
            max-width: 440px !important;
            margin: 0 auto !important;
            z-index: 10000 !important;
            pointer-events: none !important;
            justify-content: center !important;
            background: transparent !important;
            padding: 0 !important;
        }
        @media (max-width: 991px) {
            .pos-bottom-nav-wrap {
                display: flex !important;
            }
        }
        .pos-bottom-nav-bar {
            pointer-events: auto !important;
            width: 100% !important;
            height: 62px !important;
            background: #ffffff !important;
            border-radius: 999px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 0.35rem 0.65rem !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.04) !important;
            border: 1.5px solid #f1f5f9 !important;
            box-sizing: border-box !important;
        }
        .pos-nav-item {
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            color: #64748b;
            padding: 0;
            outline: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            text-decoration: none;
            -webkit-tap-highlight-color: transparent;
        }
        .pos-nav-item.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            padding: 0.5rem 1.1rem !important;
            border-radius: 999px !important;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35) !important;
            gap: 6px !important;
            width: auto !important;
        }
        .pos-nav-item.active .pos-nav-label {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.01em;
        }
        .pos-nav-item:not(.active) {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: transparent;
            color: #64748b;
        }
        .pos-nav-item:not(.active):active {
            transform: scale(0.92);
            background: #f1f5f9;
        }
        .pos-nav-label {
            display: none;
        }

        /* Adjust Floating Cart Bar to sit neatly above Buyle POS Bottom Nav */
        @media (max-width: 991px) {
            .pos-mobile-floating-bar {
                bottom: 86px !important;
                z-index: 9990 !important;
            }
            .product-grid {
                padding-bottom: 190px !important;
            }
        }

        /* ── BUYLE POS TOP HEADER (Buyle POS + Settings + Add Item) ── */
        .pos-header-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.4rem 0 0.85rem;
            margin-bottom: 0.25rem;
        }
        .pos-header-brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .pos-header-brand-badge {
            font-size: 0.65rem;
            font-weight: 800;
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 2px 8px;
            border-radius: 999px;
            text-transform: uppercase;
        }
        .pos-header-icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #f1f5f9;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            transition: all 0.15s ease;
        }
        .pos-header-icon-btn:active {
            transform: scale(0.92);
            background: #f8fafc;
        }
        .pos-header-add-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            border: none;
            border-radius: 999px;
            padding: 8px 15px;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 3px 10px rgba(30, 179, 73, 0.3);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .pos-header-add-btn:active {
            transform: scale(0.94);
        }

        /* ── POPUP OPTIONS MODAL (+ Add Item) ── */
        .pos-add-option-card {
            background: #ffffff;
            border: 1.5px solid #f1f5f9;
            border-radius: 16px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
            width: 100%;
        }
        .pos-add-option-card:hover, .pos-add-option-card:active {
            border-color: #1eb349;
            background: #f0fdf4;
            transform: translateY(-2px);
        }
        .pos-add-option-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── SCREEN 2: TRANSACTIONS VIEW (Mockup Image 4) ── */
        #posScreenTransactions {
            display: none;
            padding-bottom: 160px;
        }
        .pos-trans-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0 0.85rem;
        }
        .pos-trans-card {
            background: #ffffff;
            border: 1.5px solid #f1f5f9;
            border-radius: 16px;
            padding: 0.85rem 1rem;
            margin-bottom: 0.65rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: all 0.18s ease;
        }
        .pos-trans-card:active {
            transform: scale(0.99);
            border-color: #bbf7d0;
        }
        .pos-trans-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.35rem;
        }
        .pos-trans-sale-num {
            font-size: 0.76rem;
            font-weight: 700;
            color: #1eb349;
        }
        .pos-trans-amount {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
        }
        .pos-trans-card-mid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }
        .pos-trans-type {
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
        }
        .pos-trans-badge-paid {
            font-size: 0.68rem;
            font-weight: 700;
            background: #dcfce7;
            color: #15803d;
            padding: 3px 10px;
            border-radius: 999px;
            border: 1px solid #bbf7d0;
        }
        .pos-trans-time {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
        }
        .pos-trans-action-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 1rem;
            margin-bottom: 1.25rem;
        }
        .pos-trans-btn-act {
            flex: 1;
            height: 46px;
            border-radius: 999px;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            border: none;
            font-size: 0.84rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3);
            cursor: pointer;
        }
        .pos-trans-btn-act:active {
            transform: scale(0.96);
        }
        .pos-trans-btn-plus {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #1eb349;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            flex-shrink: 0;
        }
        .pos-trans-btn-plus:active {
            transform: scale(0.92);
        }

        /* -- ELEVATED FLOATING BOTTOM BARS (No Collision, Clean Margin) -- */
        /* NOTE: .pos-bottom-nav-wrap & .pos-bottom-nav-bar are defined above (standardized) */

        .pos-mobile-floating-bar {
            display: none;
            position: fixed !important;
            bottom: 92px !important;
            left: 14px !important;
            right: 14px !important;
            max-width: 440px !important;
            margin: 0 auto !important;
            height: 60px !important;
            background: #ffffff !important;
            border-radius: 999px !important;
            padding: 6px 8px 6px 14px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1), 0 2px 8px rgba(0, 0, 0, 0.04) !important;
            border: 1.5px solid #e2e8f0 !important;
            align-items: center !important;
            justify-content: space-between !important;
            z-index: 9990 !important;
        }
        @media (max-width: 991px) {
            .pos-mobile-floating-bar {
                display: flex !important;
            }
            .product-grid {
                padding-bottom: 210px !important;
            }
        }

        /* 1-Line Clean Price & Hide Card Stepper */
        .product-card .product-price {
            font-size: 0.92rem !important;
            font-weight: 800 !important;
            color: #166534 !important;
            white-space: nowrap !important;
            line-height: 1.2 !important;
            margin: 0 !important;
        }
        .product-card-bottom {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
            margin-top: auto !important;
            gap: 6px !important;
        }
        .card-btn-add {
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
            color: #ffffff !important;
            border: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            flex-shrink: 0 !important;
            box-shadow: 0 3px 8px rgba(30, 179, 73, 0.3) !important;
        }
        .card-stepper, .product-card .card-stepper {
            display: none !important;
        }

    
        /* Compact Minimalist Popups (Never stretched or fullscreen) */
        #posAddOptionsModal .pos-modal-card,
        #posQuickProductModal .pos-modal-card,
        #posQuickCategoryModal .pos-modal-card {
            height: auto !important;
            max-height: fit-content !important;
            min-height: unset !important;
            margin: auto !important;
            align-self: center !important;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3) !important;
        }
        #posAddOptionsModal {
            align-items: center !important;
            justify-content: center !important;
        }


        /* Unified Mobile-First Super App Layout (HP, Tab, iPad, Desktop 100% Identical) */
        .cr-topbar-header-row {
            display: none !important;
        }
        .cr-main-canvas {
            padding: 0.5rem 0.75rem 150px 0.75rem !important;
            min-height: auto !important;
            background: #ffffff !important;
            display: block !important;
            max-width: 480px !important;
            margin: 0 auto !important;
            overflow: visible !important;
        }
        .cr-main-wrapper {
            min-height: 100vh !important;
            background: #f8fafc !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            overflow-x: clip !important;
        }
        #pos-real-content {
            margin-top: 0 !important;
            padding-top: max(0px, env(safe-area-inset-top, 0px)) !important;
            max-width: 480px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            overflow: visible !important;
        }
        .pos-wrapper {
            display: block !important;
            padding-top: 0 !important;
            max-width: 480px !important;
            margin: 0 auto !important;
            grid-template-columns: 1fr !important;
            overflow: visible !important;
        }
        .prof-card-body {
            overflow: visible !important;
        }

        /* ── STICKY SEARCH & CATEGORY BAR (Capture Tetap di Posisi Saat Scroll) ── */
        .pos-sticky-catalog-header {
            position: -webkit-sticky !important;
            position: sticky !important;
            top: 0 !important;
            top: max(0px, env(safe-area-inset-top, 0px)) !important;
            z-index: 50 !important;
            background: #ffffff !important;
            padding-top: 6px !important;
            padding-bottom: 2px !important;
            margin-left: -12px !important;
            margin-right: -12px !important;
            padding-left: 12px !important;
            padding-right: 12px !important;
            transition: box-shadow 0.2s ease;
        }
        #cartPanel {
            display: none !important;
        }
        .pos-mobile-floating-bar {
            display: flex !important;
        }
        .pos-bottom-nav-wrap {
            display: flex !important;
        }
        .product-grid {
            padding-bottom: 210px !important;
        }
        /* Eliminate Image 1 Modal completely */
        #posHistoryModal {
            display: none !important;
            pointer-events: none !important;
        }
        /* Screen 2 Transactions full width wrapper */
        #posScreenTransactions {
            max-width: 480px !important;
            margin: 0 auto !important;
            padding: 0 0 160px 0 !important;
        }
    </style>
@endsection

@section('topbar_actions')
    <div class="pos-topbar-bar">
        <!-- Compact Status Jaringan (Online / Offline) -->
        <div class="pos-topbar-chip" id="posNetworkStatusChip" title="Status Jaringan Kasir">
            <span id="posNetworkDot" style="width:7px; height:7px; border-radius:50%; background:#16a34a; display:inline-block;"></span>
            <span id="posNetworkLabel" style="font-weight:700; font-size:0.7rem; color:#15803d;">Online</span>
        </div>

        <!-- Compact Indikator Kasir -->
        <div class="pos-topbar-chip" title="Kasir yang sedang bertugas">
            <span class="pos-status-pulse"></span>
            <span style="color:#64748b; font-weight:600; font-size:0.7rem;">Kasir:</span>
            <span id="topbarCashierNameDisplay"
                style="color:#0f172a; font-weight:800;">{{ $currentCashier ? $currentCashier->name : auth()->user()->name }}</span>
            @if($currentCashier && $currentCashier->shift_hours)
                <span
                    style="font-size:0.65rem; background:#dcfce7; color:#15803d; font-weight:800; padding:1px 5px; border-radius:999px;">{{ $currentCashier->shift_hours }}</span>
            @endif
        </div>

        <!-- Compact Saldo Deposit Kasir -->
        <div class="pos-topbar-chip pos-deposit-chip" title="Saldo Deposit POS">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                <rect x="2" y="5" width="20" height="14" rx="2" />
                <line x1="2" y1="10" x2="22" y2="10" />
            </svg>
            <span id="posBalanceDisplay"
                style="font-size:0.8rem; font-weight:800; color:{{ $posBalance < 10000 ? '#dc2626' : '#166534' }};">Rp
                {{ number_format($posBalance, 0, ',', '.') }}</span>
            <button type="button" onclick="openDepositModal()" class="pos-deposit-add-btn" title="Isi Saldo POS">+</button>
        </div>

        <!-- Tombol Pengaturan POS + Hover Dropdown Menu -->
        <div class="pos-settings-dropdown-wrap">
            <button type="button" class="pos-topbar-btn" onclick="openPosSettingsModal('struk')"
                title="Pengaturan POS &amp; Kasir">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3" />
                    <path
                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                </svg>
                <span class="pos-btn-text">Pengaturan</span>
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"
                    style="margin-left:2px; opacity:0.7;">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </button>

            <!-- Dropdown Menu saat Hover -->
            <div class="pos-settings-dropdown">
                <button type="button" class="pos-settings-dropdown-item" onclick="openPosSettingsModal('struk')">
                    <span class="psd-icon">
                        <svg width="15" height="15" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                    </span>
                    <span class="psd-label">
                        Struk &amp; Toko
                        <small>Profil, logo &amp; setting cetak</small>
                    </span>
                </button>

                <button type="button" class="pos-settings-dropdown-item" onclick="openPosSettingsModal('fees')">
                    <span class="psd-icon">
                        <svg width="15" height="15" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </span>
                    <span class="psd-label">
                        Pajak &amp; Biaya
                        <small>PB1 restoran &amp; service charge</small>
                    </span>
                </button>

                <button type="button" class="pos-settings-dropdown-item" onclick="openPosSettingsModal('discounts')">
                    <span class="psd-icon">
                        <svg width="15" height="15" fill="none" stroke="#eab308" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="19" y1="5" x2="5" y2="19" />
                            <circle cx="6.5" cy="6.5" r="2.5" />
                            <circle cx="17.5" cy="17.5" r="2.5" />
                        </svg>
                    </span>
                    <span class="psd-label">
                        Diskon &amp; Promo
                        <small>Voucher &amp; diskon otomatis</small>
                    </span>
                </button>

                <button type="button" class="pos-settings-dropdown-item" onclick="openPosSettingsModal('staff')">
                    <span class="psd-icon">
                        <svg width="15" height="15" fill="none" stroke="#8b5cf6" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </span>
                    <span class="psd-label">
                        Staf Kasir
                        <small>Kelola akun kasir &amp; PIN</small>
                    </span>
                </button>

                <div class="pos-settings-dropdown-divider"></div>

                <button type="button" class="pos-settings-dropdown-item" onclick="openPosSettingsModal('tables')">
                    <span class="psd-icon">
                        <svg width="15" height="15" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <path d="M3 9h18M9 21V9" />
                        </svg>
                    </span>
                    <span class="psd-label">
                        Kelola Meja
                        <small>Tata letak &amp; nomor meja dine-in</small>
                    </span>
                </button>
            </div>
        </div>

        <!-- Tombol Rekap Closing & Riwayat Shift -->
        <button type="button" class="pos-topbar-btn" onclick="openModal('posHistoryModal')"
            title="Rekap Shift &amp; Riwayat">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
            </svg>
            <span class="pos-btn-text">Rekap Shift</span>
        </button>

        @if(!$isStaff)
            <!-- Tombol Kembali ke Aplikasi (Hanya untuk Owner) -->
            <a href="{{ route('creator.app.index') }}" class="pos-topbar-btn pos-btn-back" title="Kembali ke Dashboard">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
                <span class="pos-btn-text">Kembali</span>
            </a>
        @else
            <!-- Tombol Logout Kasir Staf -->
            <button type="button" onclick="document.getElementById('logout-form').submit();"
                class="pos-topbar-btn pos-btn-logout" title="Logout Kasir">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
                <span class="pos-btn-text">Logout</span>
            </button>
        @endif
    </div>
@endsection

@section('content')
    @php
        $midtransClientKey = \App\Models\Setting::get('midtrans_client_key', config('services.midtrans.client_key'));
        $midtransIsProd = (bool) \App\Models\Setting::get('midtrans_is_production', config('services.midtrans.is_production', false));
        $snapUrl = $midtransIsProd ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $siteLogo = \App\Models\Setting::get('logo');
        $siteLogoUrl = $siteLogo ? url('storage/' . $siteLogo) : null;
        $storeName = $profile?->store_name ?? auth()->user()->name ?? 'Toko Saya';
    @endphp

    {{-- Midtrans Snap JS --}}
    @if($midtransClientKey)
        <script src="{{ $snapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    @endif

    <!-- POS Real Content Wrapper -->
    <div id="pos-real-content" style="opacity: 1; position: relative; z-index: 1;">
        <!-- SCREEN 1: Kasir & Katalog Produk -->
        <div id="posScreenCatalog">
            <!-- POS Main Grid Wrapper -->
            <div class="pos-wrapper">
            <!-- LEFT COLUMN: Product Search & Food Menu Grid -->
            <div>
                <div class="prof-card">
                    <div class="prof-card-head">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <rect x="4" y="3" width="16" height="18" rx="2" />
                                <line x1="8" y1="7" x2="16" y2="7" />
                                <line x1="8" y1="11" x2="10" y2="11" />
                            </svg>
                            KATALOG PRODUK
                        </div>
                        <span class="badge" id="posMenuCountBadge"
                            style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-weight:700; padding:5px 12px; border-radius:20px; font-size:0.75rem;">
                            {{ count($products) }} Produk Tersedia
                        </span>
                    </div>

                    <div class="prof-card-body">
                        <!-- 1. Header (Screen 1 Reference 100% Match) -->
                        <div class="pos-ref-header">
                            <button type="button" class="pos-ref-menu-btn" onclick="openPosSettingsModal('struk')" title="Pengaturan POS">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="3" />
                                    <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                                </svg>
                            </button>
                            <h1 class="pos-ref-title">Buyle POS</h1>
                            <a href="{{ route('creator.products.index', ['from' => 'pos']) }}" class="pos-ref-add-btn" id="posBtnHeaderAdd" title="Kelola Produk di Buyle" style="width:40px; height:40px; border-radius:50%; padding:0; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; border:1.5px solid #e2e8f0; background:#fff; cursor:pointer; color:#166534; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </a>
                        </div>

                        <!-- Mini Stats Cards (Saldo Deposit & Revenue Hari Ini) -->
                        <div class="pos-stats-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 0.85rem;">
                            <!-- Card Kiri: Saldo Deposit -->
                            <div class="pos-stat-card" onclick="openDepositModal()" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 18px; padding: 0.75rem 0.95rem; display: flex; align-items: center; gap: 10px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                                <div style="width: 36px; height: 36px; border-radius: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; display: flex; align-items: center; justify-content: center; color: #16a34a; flex-shrink: 0;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <rect x="2" y="5" width="20" height="14" rx="2"/>
                                        <line x1="2" y1="10" x2="22" y2="10"/>
                                    </svg>
                                </div>
                                <div style="min-width: 0; flex: 1;">
                                    <div style="font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.02em;">Saldo Deposit</div>
                                    <div style="font-size: 0.92rem; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px;" id="posStatDepositDisplay">
                                        Rp {{ number_format($posBalance, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Card Kanan: Revenue (Hari Ini) -->
                            <div class="pos-stat-card" onclick="switchPosScreen('transactions')" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 18px; padding: 0.75rem 0.95rem; display: flex; align-items: center; gap: 10px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                                <div style="width: 36px; height: 36px; border-radius: 12px; background: #eff6ff; border: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: center; color: #2563eb; flex-shrink: 0;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <line x1="12" y1="1" x2="12" y2="23"/>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                    </svg>
                                </div>
                                <div style="min-width: 0; flex: 1;">
                                    <div style="font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.02em;">Revenue <br>(Hari Ini)</div>
                                    <div style="font-size: 0.92rem; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px;" id="posStatRevenueDisplay">
                                        Rp {{ number_format($todayGrossSales, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sticky Search & Category Bar (Capture yang tetap di posisi saat scroll) -->
                        <div class="pos-sticky-catalog-header" id="posStickyCatalogHeader">
                            <!-- 2. Search Bar Capsule (Screen 1 Reference 100% Match) -->
                            <div class="pos-ref-search-wrap">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" class="pos-ref-search-icon">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                                <input type="text" id="posProductSearch" class="pos-ref-search-input"
                                    placeholder="Search dishes by name..." oninput="handlePosProductSearchInput(this)">
                                <button type="button" class="pos-ref-barcode-btn" id="posProductSearchClearBtn" onclick="clearPosProductSearch()" title="Hapus pencarian / Scan Barcode">
                                    <svg id="posSearchBarcodeIcon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/>
                                        <line x1="7" y1="8" x2="7" y2="16" stroke-width="2.5"/><line x1="12" y1="8" x2="12" y2="16" stroke-width="2"/><line x1="17" y1="8" x2="17" y2="16" stroke-width="2.5"/>
                                    </svg>
                                    <span id="posSearchClearXIcon" style="display:none; font-size:1.15rem; font-weight:700; color:#94a3b8; line-height:1;">✕</span>
                                </button>
                            </div>

                            <!-- 3. Category Filter - Kelompok Produk Creator -->
                            <div class="pos-ref-cat-bar" id="posCategoryPillsContainer">
                                <button type="button" class="pos-ref-cat-pill active" data-cat="all" onclick="setPosCategoryFilter('all', this)">
                                    Semua
                                </button>
                                @if(isset($categories) && count($categories) > 0)
                                    @foreach($categories as $cItem)
                                        @if(trim(strtolower($cItem->name)) !== 'kategori 1')
                                            <button type="button" class="pos-ref-cat-pill" data-cat="cat-{{ $cItem->id }}" onclick="setPosCategoryFilter('cat-{{ $cItem->id }}', this)">
                                                {{ $cItem->name }}
                                            </button>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="product-grid" id="productGridContainer">
                            @forelse($products as $prod)
                                @php
                                    $prodPrice = (float) ($prod->sale_price ?: $prod->price);
                                    $nameLower = strtolower($prod->name);
                                    $imgUrl = null;
                                    if (isset($prod->image_url) && !empty($prod->image_url) && !str_contains($prod->image_url, 'service-default')) {
                                        $imgUrl = $prod->image_url;
                                    } elseif (!empty($prod->image) && strlen(trim($prod->image)) > 1) {
                                        $rawImg = trim($prod->image);
                                        if (\Illuminate\Support\Str::startsWith($rawImg, ['http://', 'https://'])) {
                                            $imgUrl = $rawImg;
                                        } elseif (\Illuminate\Support\Str::startsWith($rawImg, ['storage/', '/storage/'])) {
                                            $imgUrl = asset(ltrim($rawImg, '/'));
                                        } else {
                                            $imgUrl = asset('storage/' . ltrim($rawImg, '/'));
                                        }
                                    }

                                    // Category Classification Slug
                                    $pType = strtolower($prod->product_type ?? $prod->type ?? 'physical');
                                    $catName = strtolower($prod->category?->name ?? '');

                                    if (in_array($pType, ['digital', 'service', 'ticket', 'external_link', 'digital_download', 'virtual']) || in_array($catName, ['digital', 'jasa', 'tiket', 'virtual'])) {
                                        $catSlug = 'digital';
                                    } elseif ($pType === 'makanan' || $pType === 'fnb' || in_array($catName, ['makanan', 'minuman', 'kuliner', 'fnb', 'food', 'resto', 'dapur']) || \Illuminate\Support\Str::contains($nameLower, ['es', 'kopi', 'teh', 'jus', 'air', 'boba', 'drink', 'minuman', 'nasi', 'mie', 'ayam', 'bebek', 'daging', 'ikan', 'sate', 'bakso', 'soto', 'roti', 'kue', 'donut', 'snack', 'pisang', 'toast', 'burger', 'pizza', 'alpukat', 'susu'])) {
                                        $catSlug = 'fnb';
                                    } else {
                                        $catSlug = 'physical';
                                    }

                                    // Clean Light Neutral Theme for Placeholder Cards
                                    $themeBg = '#f8fafc';
                                    $themeColor = '#475569';
                                    $themeBorder = '#e2e8f0';
                                    $badgeText = 'PRODUK';
                                    $iconType = 'item';

                                    if ($catSlug === 'digital') {
                                        $badgeText = 'DIGITAL';
                                        $iconType = 'digital';
                                    } elseif (\Illuminate\Support\Str::contains($nameLower, ['es', 'kopi', 'teh', 'jus', 'air', 'boba', 'drink', 'minuman', 'jeruk', 'lemon', 'syrup', 'coffee', 'tea', 'milk', 'susu', 'soda', 'alpukat'])) {
                                        $badgeText = 'MINUMAN';
                                        $iconType = 'drink';
                                    } elseif (\Illuminate\Support\Str::contains($nameLower, ['roti', 'kue', 'donut', 'snack', 'pisang', 'toast', 'cake', 'waffle', 'pancake', 'keju', 'cokelat', 'crepes', 'martabak'])) {
                                        $badgeText = 'SNACK';
                                        $iconType = 'snack';
                                    } elseif (\Illuminate\Support\Str::contains($nameLower, ['nasi', 'mie', 'ayam', 'bebek', 'daging', 'ikan', 'sate', 'bakso', 'soto', 'gudeg', 'bento', 'dimsum', 'burger', 'pizza', 'seafood', 'makanan'])) {
                                        $badgeText = 'MAKANAN';
                                        $iconType = 'dish';
                                    }
                                @endphp
                                <div class="product-card" data-id="{{ $prod->id }}" data-name="{{ strtolower($prod->name) }}"
                                    data-price="{{ $prodPrice }}" data-category="{{ $catSlug }}" data-category-id="{{ $prod->creator_group_id ?? '' }}"
                                    data-image="{{ $imgUrl ?? '' }}"
                                    onclick="handleProductClick({{ $prod->id }})">

                                    {{-- Product Image 100% Matching Reference --}}
                                    <div class="product-img-wrapper">
                                        @if($imgUrl)
                                            <img src="{{ $imgUrl }}" alt="{{ $prod->name }}" class="product-img" loading="lazy"
                                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                            <div style="display:none;width:100%;height:100%;align-items:center;justify-content:center;background:#ffffff;">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                            </div>
                                        @else
                                            <div style="display:flex;width:100%;height:100%;align-items:center;justify-content:center;background:#ffffff;">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                            </div>
                                        @endif
                                        <div class="product-check-badge" id="card-check-{{ $prod->id }}" style="display:none;">
                                            <svg width="11" height="11" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
                                    </div>

                                    <div class="product-name">{{ $prod->name }}</div>
                                    <div class="product-card-bottom">
                                        <div class="product-price">Rp {{ number_format($prodPrice, 0, ',', '.') }}</div>
                                        <button type="button" class="card-btn-add" id="card-add-btn-{{ $prod->id }}"
                                            onclick="event.stopPropagation(); handleProductClick({{ $prod->id }});"
                                            aria-label="Tambah Produk">
                                            +
                                        </button>
                                    </div>
                                </div>
                            @empty
                            @endforelse

                            <!-- Empty State Container for zero results -->
                            <div id="posEmptyStateContainer"
                                style="display: none; grid-column: 1 / -1; width: 100%; flex-direction: column; align-items: center; justify-content: center; padding: 3.5rem 1.5rem; text-align: center; background: #ffffff; border: 2px dashed #cbd5e1; border-radius: 24px; margin: 1rem 0; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                                <style>
                                    @keyframes floatPulseIcon {
                                        0% {
                                            transform: translateY(0px) scale(1);
                                            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.15);
                                        }

                                        50% {
                                            transform: translateY(-8px) scale(1.04);
                                            box-shadow: 0 12px 24px rgba(30, 179, 73, 0.3);
                                        }

                                        100% {
                                            transform: translateY(0px) scale(1);
                                            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.15);
                                        }
                                    }
                                </style>
                                <div
                                    style="width: 76px; height: 76px; border-radius: 24px; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1.5px solid #bbf7d0; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; animation: floatPulseIcon 3.2s ease-in-out infinite;">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#1eb349"
                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                        <line x1="12" y1="22.08" x2="12" y2="12" />
                                    </svg>
                                </div>
                                <h4
                                    style="font-weight: 800; color: #0f172a; font-size: 1.05rem; margin: 0 0 0.4rem 0; font-family: 'Montserrat', sans-serif;">
                                    Belum Ada Produk Ditemukan</h4>
                                <p
                                    style="font-size: 0.85rem; color: #64748b; max-width: 380px; line-height: 1.55; margin: 0 0 1.25rem 0;">
                                    Tidak ada produk yang sesuai dengan filter atau pencarian Anda.
                                </p>
                                <a href="{{ route('creator.products.create') }}"
                                    style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #1eb349, #a5cf37); color: #ffffff; padding: 0.65rem 1.35rem; border-radius: 50px; font-weight: 700; font-size: 0.82rem; text-decoration: none; box-shadow: 0 4px 14px rgba(30,179,73,0.35); transition: all 0.2s;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>
                                    Tambah Produk Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Order Cart & Calculator -->
            <div id="cartPanel">
                <div class="prof-card">
                    <div class="prof-card-head">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="9" cy="21" r="1" />
                                <circle cx="20" cy="21" r="1" />
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                            </svg>
                            KERANJANG TRANSAKSI
                        </div>
                        <button type="button" onclick="clearCart()"
                            style="font-size:0.75rem; font-weight:700; color:#dc2626; border:none; background:none; cursor:pointer;">
                            Kosongkan
                        </button>
                    </div>

                    <div class="prof-card-body">

                        <!-- Cart Items Container -->
                        <div class="cart-items-container" id="cartItemsContainer">
                            <div class="text-center py-4 text-muted" id="cartEmptyNotice" style="font-size: 0.84rem;">
                                Keranjang masih kosong.<br>Pilih produk di sebelah kiri untuk menambah.
                            </div>
                        </div>

                        <!-- Summary Box & Fees Calculator -->
                        <div class="summary-box">
                            <!-- Subtotal Row -->
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <strong style="color:#0f172a;" id="displaySubtotal">Rp 0</strong>
                            </div>

                            <!-- Diskon Field (Preset dari Pengaturan POS) -->
                            <div class="summary-row" id="cartDiscountSection">
                                <div class="d-flex align-items-center gap-1"
                                    style="flex:1; flex-wrap:wrap; gap:6px !important;">
                                    <span>Diskon</span>
                                    <!-- Preset Promo Dropdown (dari Pengaturan POS) -->
                                    <div id="cartDiscountPresetWrap" style="display:none; margin-left:4px;">
                                        <select id="posDiscountPresetSelect" class="form-input"
                                            style="height:28px; font-size:0.75rem; padding:0 8px; border-radius:6px; min-width:115px; max-width:170px;"
                                            onchange="onDiscountPresetChanged()">
                                            <option value="">Tanpa Diskon</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="display:flex; align-items:center;">
                                    <strong id="displayDiscountAmount" style="color:#16a34a; font-size:0.85rem;">- Rp
                                        0</strong>
                                    <!-- Hidden input for calculateTotals() & POS store request compatibility -->
                                    <input type="hidden" id="posDiscountInput" value="0">
                                </div>
                            </div>

                            <!-- Dynamic Fees Container (dari Pengaturan POS) -->
                            <div id="cartFeesContainer" style="display:none;">
                                <!-- Rendered dynamically by renderCartFees() -->
                            </div>

                            <!-- Platform & Admin Fees Breakdown -->
                            <div class="summary-row" style="font-size:0.75rem; color:#94a3b8; align-items:flex-start;">
                                <span style="line-height:1.35; flex:1; min-width:0;">Platform Fee ({{ $platformFeeRate }}%)
                                    &amp; Admin Fee ({{ $adminFeeRate }}%)</span>
                                <span id="displaySystemFees"
                                    style="white-space:nowrap; flex-shrink:0; text-align:right; font-weight:600; margin-left:8px;">Rp
                                    0</span>
                            </div>

                            <!-- Grand Total -->
                            <div class="summary-total summary-row">
                                <span>Total Bayar</span>
                                <span style="color:#1eb349;" id="displayGrandTotal">Rp 0</span>
                            </div>
                        </div>



                        <!-- Checkout Button -->
                        <button type="button" class="btn-submit-green" id="btnCheckout" onclick="openPaymentModal()"
                            disabled>
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path d="M5 12h14" />
                                <path d="M12 5l7 7-7 7" />
                            </svg>
                            Proses Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div> <!-- Close #posScreenCatalog -->

        <!-- SCREEN 2: TRANSACTIONS VIEW (100% Sesuai Mockup Image 4) -->
        <div id="posScreenTransactions">
            <div class="pos-trans-header">
                <button type="button" class="pos-header-icon-btn" onclick="switchPosScreen('catalog')" title="Kembali ke Kasir">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                </button>
                <h2 style="font-size:1.15rem; font-weight:800; color:#0f172a; margin:0;">Transactions</h2>
                <button type="button" class="pos-header-icon-btn" onclick="document.getElementById('posTransSearch').focus();" title="Scan">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 7V5a2 2 0 0 1 2-2h2"/>
                        <path d="M17 3h2a2 2 0 0 1 2 2v2"/>
                        <path d="M21 17v2a2 2 0 0 1-2 2h-2"/>
                        <path d="M7 21H5a2 2 0 0 1-2-2v-2"/>
                    </svg>
                </button>
            </div>

            <!-- Search in Transactions -->
            <div class="pos-pill-search" style="margin-bottom:0.75rem; position:relative;">
                <svg width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2.2" viewBox="0 0 24 24" class="search-ico">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="posTransSearch" class="pos-pill-input" placeholder="Search transactions..."
                    oninput="filterTransactionsList(this.value); document.getElementById('posTransSearchClear').style.display = this.value ? 'flex' : 'none';"
                    style="padding-right:36px;">
                <button type="button" id="posTransSearchClear"
                    onclick="document.getElementById('posTransSearch').value=''; filterTransactionsList(''); this.style.display='none'; document.getElementById('posTransSearch').focus();"
                    style="display:none; position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#94a3b8; padding:4px; border-radius:50%; width:24px; height:24px; align-items:center; justify-content:center; font-size:1rem; line-height:1; z-index:2;"
                    title="Hapus pencarian">&#x2715;</button>
            </div>

            <!-- 3 Summary Cards: Belum Bayar, Sedang Diproses, & Selesai -->
            <div class="pos-trans-summary-cards" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:8px; margin-bottom:0.85rem;">
                <!-- Card 1: Belum Bayar -->
                <div class="pos-summary-card" id="cardSummaryUnpaid" onclick="filterTransactionsByTab('unpaid')" style="background:#fffbeb; border:1.5px solid #fde68a; border-radius:14px; padding:8px 10px; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:space-between; box-shadow:0 2px 6px rgba(245,158,11,0.06);">
                    <div style="min-width:0;">
                        <div style="font-size:0.62rem; font-weight:800; color:#b45309; text-transform:uppercase; letter-spacing:0.3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Belum Bayar</div>
                        <div style="font-size:1.2rem; font-weight:800; color:#92400e; line-height:1.2; margin-top:2px;" id="countSummaryUnpaid">0</div>
                    </div>
                    <div style="width:28px; height:28px; border-radius:8px; background:#fef3c7; display:flex; align-items:center; justify-content:center; color:#d97706; flex-shrink:0;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <!-- Card 2: Sedang Diproses -->
                <div class="pos-summary-card" id="cardSummaryProcessing" onclick="filterTransactionsByTab('processing')" style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:14px; padding:8px 10px; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:space-between; box-shadow:0 2px 6px rgba(34,197,94,0.06);">
                    <div style="min-width:0;">
                        <div style="font-size:0.62rem; font-weight:800; color:#15803d; text-transform:uppercase; letter-spacing:0.3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Diproses</div>
                        <div style="font-size:1.2rem; font-weight:800; color:#166534; line-height:1.2; margin-top:2px;" id="countSummaryProcessing">0</div>
                    </div>
                    <div style="width:28px; height:28px; border-radius:8px; background:#dcfce7; display:flex; align-items:center; justify-content:center; color:#16a34a; flex-shrink:0;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                        </svg>
                    </div>
                </div>
                <!-- Card 3: Selesai -->
                <div class="pos-summary-card" id="cardSummaryCompleted" onclick="filterTransactionsByTab('completed')" style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:14px; padding:8px 10px; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:space-between; box-shadow:0 2px 6px rgba(59,130,246,0.06);">
                    <div style="min-width:0;">
                        <div style="font-size:0.62rem; font-weight:800; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Selesai</div>
                        <div style="font-size:1.2rem; font-weight:800; color:#1e40af; line-height:1.2; margin-top:2px;" id="countSummaryCompleted">0</div>
                    </div>
                    <div style="width:28px; height:28px; border-radius:8px; background:#dbeafe; display:flex; align-items:center; justify-content:center; color:#2563eb; flex-shrink:0;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Simplified Period Filter: Hari Ini & Kemarin -->
            <div class="pos-period-bar-wrap" style="display:flex; align-items:center; justify-content:space-between; background:#ffffff; border:1.5px solid #e2e8f0; border-radius:999px; padding:5px 8px 5px 14px; margin-bottom:1rem; box-shadow:0 2px 8px rgba(0,0,0,0.02); gap:8px;">
                <!-- Left: Calendar Icon + Label -->
                <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                    <div style="width:28px; height:28px; border-radius:50%; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#16a34a; flex-shrink:0;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <span style="font-size:0.82rem; font-weight:800; color:#0f172a; line-height:1.2;" id="posTransFilterLabel">Hari Ini</span>
                </div>

                <!-- Right: Quick Options (Hari Ini & Kemarin Saja) + Sort Toggle -->
                <div class="pos-period-quick-opts" style="display:flex; align-items:center; gap:4px;">
                    <button type="button" class="pos-period-btn active" id="btnPeriodToday" data-period="today" onclick="selectPeriodPreset('today', this)">Hari Ini</button>
                    <button type="button" class="pos-period-btn" id="btnPeriodYesterday" data-period="yesterday" onclick="selectPeriodPreset('yesterday', this)">Kemarin</button>
                    <button type="button" id="btnTransSortToggle" onclick="toggleTransactionSort()" title="Urut transaksi"
                        style="display:flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:50%; background:#f0fdf4; border:1.5px solid #bbf7d0; color:#16a34a; cursor:pointer; flex-shrink:0; transition:background 0.2s;">
                        <svg id="sortToggleIcon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="20" x2="12" y2="4"/><polyline points="6 10 12 4 18 10"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Transactions List Container -->
            <div id="posTransactionsListContainer">
                @forelse($recentPosOrders ?? $todayOrders as $ord)
                    @php
                        $payMethodRaw = $ord->payment_method ?? ($ord->payment?->method ?? ($ord->payment?->gateway ?? 'cash'));
                        $payMethod = strtolower(is_object($payMethodRaw) ? ($payMethodRaw->value ?? $payMethodRaw->name ?? 'cash') : ($payMethodRaw ?? 'cash'));
                        $totalVal = (float) ($ord->total ?? $ord->total_amount ?? 0);
                        $statusStr = is_string($ord->status) ? $ord->status : ($ord->status?->value ?? $ord->status?->name ?? '');
                        $payStat = strtolower($ord->payment?->status?->value ?? $ord->payment?->status?->name ?? (string)($ord->payment?->status ?? ''));
                        $isPaidOrd = in_array(strtolower($statusStr), ['confirmed', 'delivered', 'processing', 'shipped', 'paid', 'completed'])
                                     || in_array($payStat, ['success', 'paid']);
                        $isCompletedOrd = in_array(strtolower($statusStr), ['delivered', 'completed']);
                        $addr = is_array($ord->shipping_address) ? $ord->shipping_address : (json_decode($ord->shipping_address ?? '', true) ?? []);
                        $custName = $addr['name'] ?? $ord->user?->name ?? 'Pelanggan Umum';
                        $tableNo = $addr['table_number'] ?? ($addr['table'] ?? null);
                        $isSelfOrd = ($ord->source === 'storefront') || !empty($addr['is_self_order']);
                        $itemCount = $ord->items ? $ord->items->sum('qty') : 0;
                        $itemsSummary = [];
                        if (!empty($addr['items_meta']) && is_array($addr['items_meta'])) {
                            foreach($addr['items_meta'] as $im) {
                                $itemsSummary[] = ($im['qty'] ?? 1) . 'x ' . ($im['product_name'] ?? 'Item');
                            }
                        } elseif ($ord->items && $ord->items->count() > 0) {
                            foreach($ord->items as $it) {
                                $pName = $it->product ? $it->product->name : ($it->product_name ?? 'Item');
                                $itemsSummary[] = $it->qty . 'x ' . $pName;
                            }
                        }
                        $itemsText = !empty($itemsSummary) ? implode(', ', array_slice($itemsSummary, 0, 3)) . (count($itemsSummary) > 3 ? ' +' . (count($itemsSummary) - 3) . ' lainnya' : '') : ($itemCount . ' item');
                        $pm = strtolower($payMethod);
                        $badgeColor = $pm === 'cash' ? '#059669' : ($pm === 'qris' ? '#2563eb' : '#7c3aed');
                        $badgeLabel = $pm === 'cash' ? 'Tunai' : ($pm === 'qris' ? 'QRIS' : strtoupper($payMethod));
                    @endphp
                    <div class="pos-trans-card" id="pos-trans-card-{{ $ord->id }}"
                        data-id="{{ $ord->id }}"
                        data-paid="{{ $isPaidOrd ? '1' : '0' }}"
                        data-completed="{{ $isCompletedOrd ? '1' : '0' }}"
                        data-pay-method="{{ $pm }}"
                        data-search="{{ strtolower($ord->order_number ?? $ord->id) }} {{ strtolower($custName) }}"
                        data-date="{{ \Carbon\Carbon::parse($ord->created_at)->format('Y-m-d') }}"
                        data-timestamp="{{ \Carbon\Carbon::parse($ord->created_at)->timestamp }}">
                        <div style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;" onclick="openPosOrderReceipt({{ $ord->id }})">
                            <div class="pos-trans-card-top" style="flex:1;">
                                <span class="pos-trans-sale-num">
                                    @if($isSelfOrd)
                                        <span style="font-size:0.6rem;background:#fef3c7;color:#d97706;border:1px solid #fde68a;padding:1px 6px;border-radius:999px;font-weight:800;margin-right:4px;">Self-Order</span>
                                    @endif
                                    #{{ $ord->order_number ?? ('BYL-' . $ord->id) }}
                                    @if($tableNo)<span style="font-size:0.68rem;color:#64748b;font-weight:600;margin-left:4px;">· Meja {{ $tableNo }}</span>@endif
                                </span>
                                <span class="pos-trans-amount">Rp {{ number_format($totalVal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="pos-trans-card-mid" style="cursor:pointer;" onclick="openPosOrderReceipt({{ $ord->id }})">
                            <span style="font-size:0.75rem;color:#334155;font-weight:600;">{{ $custName }}</span>
                            <span style="font-size:0.7rem;color:#64748b;">{{ $itemCount }} item</span>
                        </div>
                        <div style="font-size:0.75rem;color:#475569;margin:3px 0 5px;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;cursor:pointer;" onclick="openPosOrderReceipt({{ $ord->id }})">
                            {{ $itemsText }}
                        </div>
                        {{-- Row bawah: badge + waktu + tombol aksi --}}
                        <div class="pos-trans-time" style="display:flex;align-items:center;justify-content:space-between;gap:6px;">
                            <div style="display:flex;align-items:center;gap:5px;flex:1;min-width:0;cursor:pointer;" onclick="openPosOrderReceipt({{ $ord->id }})">
                                <span style="display:inline-block;padding:2px 8px;border-radius:999px;font-size:0.65rem;font-weight:700;color:#fff;background:{{ $badgeColor }};white-space:nowrap;">{{ $badgeLabel }}</span>
                                <span style="font-size:0.68rem;color:#94a3b8;white-space:nowrap;">{{ \Carbon\Carbon::parse($ord->created_at)->format('d M, H:i') }}</span>
                            </div>
                            {{-- 3 Tombol Bulat Aksi --}}
                            <div class="pos-trans-actions" style="margin-top:0;">
                                @if(!$isPaidOrd && $pm === 'cash')
                                    {{-- Tombol 1: Cash – tunggu bayar (icon uang) --}}
                                    <button type="button"
                                        class="pos-action-btn btn-cash-pending"
                                        onclick="event.stopPropagation(); posMarkPaid({{ $ord->id }}, this)"
                                        title="Tandai Pembayaran Diterima (Cash)">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <rect x="2" y="6" width="20" height="14" rx="2"/>
                                            <path d="M12 10v4m-2-2h4"/>
                                            <path d="M6 10h.01M18 10h.01"/>
                                        </svg>
                                    </button>
                                @elseif($isPaidOrd && !$isCompletedOrd)
                                    {{-- Tombol 2: Lunas – ceklis 1 (non-aktif, tampil info status) --}}
                                    <button type="button" class="pos-action-btn btn-paid" disabled title="Sudah Lunas">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    </button>
                                @elseif($isCompletedOrd)
                                    {{-- Sudah Selesai – centang 2 (double check, non-aktif) --}}
                                    <button type="button" class="pos-action-btn btn-complete" disabled title="Pesanan Selesai">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"/>
                                            <polyline points="20 10 9 21 4 16" style="opacity:0.5;"/>
                                        </svg>
                                    </button>
                                @endif

                                @if($isPaidOrd && !$isCompletedOrd)
                                    {{-- Tombol 3: Tandai Selesai (double check) --}}
                                    <button type="button"
                                        class="pos-action-btn btn-complete"
                                        onclick="event.stopPropagation(); posMarkCompleted({{ $ord->id }}, this)"
                                        title="Tandai Pesanan Selesai / Sudah Disajikan">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"/>
                                            <polyline points="20 10 9 21 4 16" style="opacity:0.5;"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div id="posTransEmptyState" style="text-align:center; padding:3rem 1rem; color:#94a3b8; font-size:0.84rem;">
                        <svg width="40" height="40" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24" style="display:block;margin:0 auto 10px;"><rect x="2" y="4" width="20" height="16" rx="2"/><circle cx="12" cy="12" r="3"/></svg>
                        Belum ada riwayat transaksi kasir
                    </div>
                @endforelse
            </div>

<!-- Bottom action buttons removed per user request -->
        </div>
    </div> <!-- Close #pos-real-content -->

    <!-- ── Floating Cart Bar (100% Sesuai Screen 1 Mockup) ── -->
    <div class="pos-mobile-floating-bar" id="posMobileFloatingBar">
        <div class="pos-mf-left" onclick="toggleCartDrawer()">
            <div class="pos-mf-chevron" id="posMfChevron">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="18 15 12 9 6 15" />
                </svg>
            </div>
            <div class="pos-mf-info">
                <div class="pos-mf-count" id="mobileCartCount">0 Items</div>
                <div class="pos-mf-total" id="mobileCartTotal">Rp 0</div>
            </div>
        </div>
        <button type="button" class="pos-mf-btn-next" onclick="openPaymentModal()" title="Lanjut Pembayaran">
            <span>Next</span>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="5" y1="12" x2="19" y2="12" />
                <polyline points="12 5 19 12 12 19" />
            </svg>
        </button>
    </div>

    <!-- ── Slide-up Cart Drawer Sheet on Mobile ── -->
    <div class="pos-cart-drawer-overlay" id="posCartDrawerOverlay" onclick="closeCartDrawer()"></div>
    <div class="pos-cart-drawer" id="posCartDrawer">
        <div class="pos-drawer-handle"></div>
        <div class="pos-drawer-head">
            <div style="display:flex; align-items:center; gap:8px;">
                <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                </svg>
                <h3 class="pos-drawer-title">Keranjang Belanja</h3>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <button type="button" onclick="clearCart()"
                    style="font-size:0.75rem; font-weight:700; color:#dc2626; border:none; background:none; cursor:pointer;">
                    Kosongkan
                </button>
                <button type="button" onclick="closeCartDrawer()"
                    style="width:28px; height:28px; border-radius:50%; background:#f1f5f9; border:none; font-size:1rem; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b;">
                    &times;
                </button>
            </div>
        </div>
        <div class="pos-drawer-body">
            <div id="drawerCartItemsContainer">
                <div class="text-center py-4 text-muted" style="font-size: 0.84rem;">
                    Keranjang masih kosong.
                </div>
            </div>
        </div>
        <div style="padding:1rem 1.25rem; background:#ffffff; border-top:1.5px solid #f1f5f9;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                <span style="font-size:0.85rem; color:#64748b; font-weight:600;">Total Pembayaran</span>
                <span style="font-size:1.2rem; font-weight:900; color:#0f172a;" id="drawerGrandTotal">Rp 0</span>
            </div>
            <button type="button" class="btn-submit-green" style="width:100%; border-radius:999px; padding:0.75rem;"
                onclick="closeCartDrawer(); openPaymentModal();">
                Lanjut ke Pembayaran &rarr;
            </button>
        </div>
    </div>

    <!-- Floating Toast Notification Container -->
    <div id="posToastContainer"
        style="position: fixed; top: 24px; right: 24px; z-index: 100001; display: flex; flex-direction: column; gap: 8px; pointer-events: none;">
    </div>

    <!-- Privacy Blur Overlay for Midtrans -->
    <div id="midtransBlurOverlay"></div>

    <!-- CUSTOM OVERLAY MODAL: Alert Modal -->
    <div class="pos-modal-overlay" id="posAlertModal" style="z-index: 100000;">
        <div class="pos-modal-card" style="max-width: 360px; padding: 1.5rem 1.25rem; text-align: center;">
            <div id="posAlertIconWrap"
                style="display:flex !important; justify-content:center !important; align-items:center !important; width:100% !important; margin:0 auto 0.75rem auto !important;">
            </div>
            <h5 id="posAlertTitle"
                style="font-size:1.05rem; font-weight:800; color:#0f172a; margin-bottom:0.4rem; text-align:center;">
                Perhatian</h5>
            <div id="posAlertMessage"
                style="font-size:0.83rem; color:#475569; line-height:1.45; margin-bottom:1.25rem; text-align:center;">-
            </div>
            <button type="button" class="btn-submit-green"
                style="width:100%; margin:0; padding:0.65rem 1rem; font-size:0.85rem; border-radius:999px !important;"
                onclick="closeModal('posAlertModal')">Oke, Saya Mengerti</button>
        </div>
    </div>

    <!-- CUSTOM OVERLAY MODAL: Top Up Saldo Deposit POS -->
    <div class="pos-modal-overlay" id="posDepositModal" style="z-index: 100000;">
        <div class="pos-modal-card" style="max-width: 480px; width: 95vw;">
            <div class="pos-modal-head">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div
                        style="width:30px; height:30px; border-radius:50%; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#166534;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <rect x="2" y="5" width="20" height="14" rx="2" />
                            <line x1="2" y1="10" x2="22" y2="10" />
                        </svg>
                    </div>
                    <h5 style="margin:0; font-size:1rem; font-weight:800; color:#0f172a;">Isi Saldo Deposit Kasir POS</h5>
                </div>
                <button type="button" class="pos-modal-close" onclick="closeModal('posDepositModal')">&times;</button>
            </div>
            <div class="pos-modal-body">
                <!-- Status Saldo Sekarang -->
                <div
                    style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                    <div>
                        <div style="font-size:0.68rem; color:#64748b; font-weight:700; text-transform:uppercase;">Saldo
                            Deposit Saat Ini</div>
                        <div style="font-size:1.15rem; font-weight:900; color:#166534; margin-top:2px;"
                            id="depositModalCurrentBalance">Rp {{ number_format($posBalance, 0, ',', '.') }}</div>
                    </div>
                    <span class="badge"
                        style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-size:0.7rem; font-weight:700; padding:4px 8px; border-radius:6px;">Aktif</span>
                </div>

                <!-- Segmented Tabs: Saldo Toko vs QRIS -->
                <div
                    style="display:flex; background:#f1f5f9; padding:4px; border-radius:12px; margin-bottom:14px; gap:4px;">
                    <button type="button" id="tabDepositStore" onclick="switchDepositMethod('store')"
                        style="flex:1; border:none; padding:7px 10px; border-radius:8px; font-size:0.75rem; font-weight:700; background:#ffffff; color:#0f172a; box-shadow:0 1px 3px rgba(0,0,0,0.08); cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; transition:all 0.15s ease;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <polyline points="17 1 21 5 17 9" />
                            <path d="M3 11V9a4 4 0 0 1 4-4h14" />
                            <polyline points="7 23 3 19 7 15" />
                            <path d="M21 13v2a4 4 0 0 1-4 4H3" />
                        </svg>
                        Pindah dari Saldo Utama
                    </button>
                    <button type="button" id="tabDepositQris" onclick="switchDepositMethod('qris')"
                        style="flex:1; border:none; padding:7px 10px; border-radius:8px; font-size:0.75rem; font-weight:700; background:transparent; color:#64748b; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; transition:all 0.15s ease;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                        Top-Up via QRIS
                    </button>
                </div>

                <!-- PANEL 1: PINDAH DARI SALDO TOKO -->
                <div id="panelDepositStore">
                    <!-- Info Box Saldo Toko -->
                    <div
                        style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:10px 14px; margin-bottom:12px; display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <div style="font-size:0.68rem; color:#166534; font-weight:700; text-transform:uppercase;">Saldo
                                Siap Ditarik</div>
                            <div style="font-size:1.15rem; font-weight:900; color:#14532d; margin-top:1px;"
                                id="availableStoreBalanceDisplay">Rp
                                {{ number_format($availableStoreBalance, 0, ',', '.') }}
                            </div>
                        </div>
                        <span
                            style="font-size:0.68rem; background:#dcfce7; color:#15803d; border:1px solid #86efac; padding:3px 8px; border-radius:999px; font-weight:700;">Instan
                            &amp; Bebas Biaya</span>
                    </div>

                    <div
                        style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:9px 12px; margin-bottom:12px; font-size:0.73rem; color:#1e40af; line-height:1.4;">
                        ⚡ <strong>Pindah Saldo Toko:</strong> Memindahkan dana penjualan Anda langsung ke deposit POS tanpa
                        perlu bayar QRIS. Saldo siap ditarik otomatis terpotong saat ini juga.
                    </div>

                    @if($availableStoreBalance >= 5000)
                        <!-- Pilihan Nominal Cepat -->
                        <label
                            style="font-size:0.75rem; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Pilih
                            Nominal Pemindahan</label>
                        <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:10px;">
                            @foreach([10000, 20000, 50000, 100000] as $preset)
                                @if($preset <= $availableStoreBalance)
                                    <button type="button" class="btn-deposit-preset btn-store-preset"
                                        onclick="setStoreTransferPreset({{ $preset }})">
                                        +Rp {{ number_format($preset, 0, ',', '.') }}
                                    </button>
                                @endif
                            @endforeach
                            <button type="button" class="btn-deposit-preset btn-store-preset"
                                onclick="setStoreTransferPreset({{ (int) $availableStoreBalance }})"
                                style="background:#e0f2fe; border-color:#7dd3fc; color:#0369a1;">
                                Semua (Rp {{ number_format($availableStoreBalance, 0, ',', '.') }})
                            </button>
                        </div>

                        <!-- Input Nominal Manual -->
                        <div class="form-group mb-0">
                            <label style="font-size:0.72rem; font-weight:700; color:#334155;">Atau Masukkan Nominal Sendiri
                                (Min. Rp 5.000)</label>
                            <div style="position:relative; margin-top:4px;">
                                <span
                                    style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:800; font-size:0.88rem; color:#475569;">Rp</span>
                                <input type="text" id="storeTransferDisplay" class="form-input"
                                    style="padding-left:42px; height:42px; font-size:1rem; font-weight:800; color:#0f172a;"
                                    value="{{ min(50000, (int) $availableStoreBalance) > 0 ? number_format(min(50000, (int) $availableStoreBalance), 0, ',', '.') : '' }}"
                                    placeholder="0" oninput="handleStoreTransferInput(this)">
                                <input type="hidden" id="storeTransferVal"
                                    value="{{ min(50000, (int) $availableStoreBalance) }}">
                            </div>
                        </div>
                    @else
                        <div
                            style="background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:10px 12px; font-size:0.75rem; color:#92400e; line-height:1.45;">
                            Saldo siap ditarik Anda saat ini belum mencapai batas minimal transfer <strong>Rp 5.000</strong>.
                            Silakan pilih tab <strong>Top-Up via QRIS</strong> untuk menambah deposit kasir.
                        </div>
                    @endif
                </div>

                <!-- PANEL 2: TOP UP VIA QRIS -->
                <div id="panelDepositQris" style="display:none;">
                    <!-- Info Box QRIS -->
                    <div
                        style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:9px 12px; margin-bottom:12px; font-size:0.73rem; color:#1e40af; line-height:1.4;">
                        Pembayaran top up menggunakan QRIS / Virtual Account Midtrans dan deposit bertambah otomatis.
                    </div>

                    <!-- Pilihan Nominal Cepat QRIS -->
                    <label
                        style="font-size:0.75rem; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Pilih
                        Nominal Top Up</label>
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:6px; margin-bottom:10px;">
                        <button type="button" class="btn-deposit-preset" onclick="setDepositPreset(20000)">Rp
                            20.000</button>
                        <button type="button" class="btn-deposit-preset active" onclick="setDepositPreset(50000)">Rp
                            50.000</button>
                        <button type="button" class="btn-deposit-preset" onclick="setDepositPreset(100000)">Rp
                            100.000</button>
                        <button type="button" class="btn-deposit-preset" onclick="setDepositPreset(200000)">Rp
                            200.000</button>
                        <button type="button" class="btn-deposit-preset" onclick="setDepositPreset(300000)">Rp
                            300.000</button>
                        <button type="button" class="btn-deposit-preset" onclick="setDepositPreset(500000)">Rp
                            500.000</button>
                    </div>

                    <!-- Input Nominal Manual QRIS -->
                    <div class="form-group mb-0">
                        <label style="font-size:0.72rem; font-weight:700; color:#334155;">Atau Masukkan Nominal Lain (Min.
                            Rp 10.000)</label>
                        <div style="position:relative; margin-top:4px;">
                            <span
                                style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:800; font-size:0.88rem; color:#475569;">Rp</span>
                            <input type="text" id="depositAmountDisplay" class="form-input"
                                style="padding-left:42px; height:42px; font-size:1rem; font-weight:800; color:#0f172a;"
                                value="50.000" placeholder="50.000" oninput="handleDepositAmountInput(this)">
                            <input type="hidden" id="depositAmountVal" value="50000">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pos-modal-foot" style="gap:8px;">
                <button type="button" class="btn-outline-bio"
                    style="border-color:#e2e8f0; color:#64748b; padding:0.6rem 1rem; font-size:0.8rem; border-radius:999px !important;"
                    onclick="closeModal('posDepositModal')">Batal</button>

                <!-- Tombol aksi dinamis sesuai panel aktif -->
                <button type="button" class="btn-submit-green" id="btnSubmitStoreTransfer"
                    onclick="submitStoreToPosTransfer()"
                    style="{{ $availableStoreBalance < 5000 ? 'display:none;' : '' }} flex:1; margin:0; padding:0.65rem 1.25rem; font-size:0.84rem; border-radius:999px !important; display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <polyline points="17 1 21 5 17 9" />
                        <path d="M3 11V9a4 4 0 0 1 4-4h14" />
                        <polyline points="7 23 3 19 7 15" />
                        <path d="M21 13v2a4 4 0 0 1-4 4H3" />
                    </svg>
                    Pindahkan ke Deposit Sekarang
                </button>

                <button type="button" class="btn-submit-green" id="btnSubmitDeposit" onclick="submitPosDeposit()"
                    style="display:none; flex:1; margin:0; padding:0.65rem 1.25rem; font-size:0.84rem; border-radius:999px !important; align-items:center; justify-content:center; gap:6px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path d="M5 12h14" />
                        <path d="M12 5l7 7-7 7" />
                    </svg>
                    Bayar Instan via QRIS
                </button>
            </div>
        </div>
    </div>

    <!-- CUSTOM OVERLAY MODAL 1: Payment Method Selection -->
        <div class="pos-modal-overlay" id="posPaymentModal">
        <div class="pos-modal-card" style="max-width: 440px; border-radius: 20px; overflow: hidden;">
            <!-- Modal Header -->
            <div class="pos-modal-head" style="padding: 0.9rem 1.25rem; border-bottom: 1.5px solid #f1f5f9;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <button type="button" class="pos-modal-close"
                        style="width:30px; height:30px; border-radius:50%; background:#f8fafc; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; color:#0f172a; cursor:pointer;"
                        onclick="closeModal('posPaymentModal')" title="Kembali">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                    </button>
                    <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Konfirmasi Pembayaran</h5>
                </div>
                <button type="button" class="pos-modal-close" onclick="closeModal('posPaymentModal')">&times;</button>
            </div>

            <div class="pos-modal-body" style="padding: 1rem 1.25rem;">
                <!-- 1. Detail Order Ringkas (Minimalis) -->
                <div style="background:#f8fafc; border:1.5px solid #f1f5f9; border-radius:14px; padding:0.75rem 0.9rem; margin-bottom:0.85rem;">
                    <div style="font-size:0.72rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:6px;">
                        Detail Pesanan
                    </div>
                    <div id="paymentModalOrderItems" style="max-height: 140px; overflow-y: auto; padding-right: 4px; font-size: 0.78rem;">
                        <!-- Rendered via JS -->
                    </div>
                </div>

                <!-- 2. Info Pelanggan (Cukup Nama, Email, dan Pilih Meja) -->
                <div style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:14px; padding:0.85rem; margin-bottom:0.85rem;">
                    <div style="font-size:0.72rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:8px;">
                        Info Pelanggan
                    </div>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <!-- Nama Pelanggan -->
                        <div>
                            <label style="font-size:0.68rem; font-weight:700; color:#475569; display:block; margin-bottom:2px;">Nama</label>
                            <input type="text" id="posCustomerName" class="form-input"
                                style="height:36px; font-size:0.8rem; border-radius:10px; width:100%; border:1.5px solid #e2e8f0;"
                                placeholder="Pelanggan Umum">
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                            <!-- WhatsApp / No. HP Pelanggan -->
                            <div>
                                <label style="font-size:0.68rem; font-weight:700; color:#475569; display:block; margin-bottom:2px;">WhatsApp / No. HP</label>
                                <input type="tel" id="posCustomerPhone" class="form-input"
                                    style="height:36px; font-size:0.8rem; border-radius:10px; width:100%; border:1.5px solid #e2e8f0;"
                                    placeholder="08xxxxxxxxxx">
                            </div>

                            <!-- Pilih Meja -->
                            <div>
                                <label style="font-size:0.68rem; font-weight:700; color:#475569; display:block; margin-bottom:2px;">Pilih Meja</label>
                                <select id="posTableQuickSelect" class="form-input"
                                    style="height:36px; font-size:0.8rem; border-radius:10px; width:100%; border:1.5px solid #e2e8f0; padding:0 8px;"
                                    onchange="onTableQuickSelectChanged(this.value)">
                                    <option value="">Tanpa Meja (Takeaway)</option>
                                    @if(isset($posTables))
                                        @foreach($posTables as $tbl)
                                            <option value="{{ $tbl['name'] ?? $tbl }}">{{ $tbl['name'] ?? $tbl }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <input type="hidden" id="posTableNumber" value="">
                            </div>
                        </div>

                        <!-- Email E-Receipt (Opsional) -->
                        <div>
                            <label style="font-size:0.68rem; font-weight:700; color:#475569; display:block; margin-bottom:2px;">Email (Opsional)</label>
                            <input type="email" id="posCartCustomerEmail" class="form-input"
                                style="height:36px; font-size:0.8rem; border-radius:10px; width:100%; border:1.5px solid #e2e8f0;"
                                placeholder="contoh@gmail.com" oninput="syncCustomerEmail(this.value)">
                            <input type="hidden" id="posCustomerEmail" value="">
                        </div>
                    </div>
                </div>

                <!-- 3. Total Tagihan Minimalis (Bukan Banner Norak Tebal) -->
                <div style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:14px; padding:0.75rem 1rem; display:flex; align-items:center; justify-content:space-between; margin-bottom:0.85rem;">
                    <div>
                        <div style="font-size:0.7rem; font-weight:700; color:#166534; text-transform:uppercase; letter-spacing:0.04em;">Total Pembayaran</div>
                        <div style="font-size:1.35rem; font-weight:900; color:#15803d; line-height:1.2; margin-top:1px;" id="modalPayTotal">Rp 0</div>
                    </div>
                    <div style="font-size:0.72rem; color:#64748b; font-weight:600;" id="modalPayItemsCount">0 Items</div>
                </div>

                @php
                    // Jika fee sistem adalah 0%, penjual TIDAK memerlukan saldo deposit untuk memproses tunai
                    $systemFeeRate = (float) ($platformFeeRate ?? 0) + (float) ($adminFeeRate ?? 0);
                    $isDepositEmpty = ($posFeePayer === 'seller' && $systemFeeRate > 0 && $posBalance <= 0);
                @endphp

                <!-- 4. Metode Pembayaran -->
                <div style="display:flex; flex-direction:column; gap:6px; margin-bottom:0.85rem;">
                    <!-- Opsi Tunai -->
                    <div class="pay-option-card {{ $isDepositEmpty ? 'disabled-cash' : 'selected' }}" id="optCash"
                        onclick="{{ $isDepositEmpty ? '' : 'selectPaymentMethod(\'cash\')' }}"
                        style="{{ $isDepositEmpty ? 'background:#f8fafc; border-color:#cbd5e1; opacity:0.6; cursor:not-allowed;' : 'padding:0.65rem 0.85rem; border-radius:12px;' }}">
                        <input type="radio" name="pay_method" value="cash" {{ $isDepositEmpty ? 'disabled' : 'checked' }} style="accent-color:#1eb349;">
                        <div style="flex:1; margin-left:6px;">
                            <div style="font-size:0.84rem; font-weight:700; color:#0f172a;">Tunai (Cash)</div>
                            <div style="font-size:0.7rem; color:#64748b;">Bayar tunai & hitung kembalian</div>
                        </div>
                    </div>

                    <!-- Cash Calculator Panel -->
                    <div id="cashCalcPanel" style="display:{{ $isDepositEmpty ? 'none' : 'block' }}; background:#f8fafc; border-radius:12px; border:1.5px solid #e2e8f0; padding:0.75rem; margin-top:2px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                            <label style="font-size:0.72rem; font-weight:700; color:#475569; margin:0;">Uang Diterima:</label>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn-quick-cash" onclick="setQuickCash('exact')" style="font-size:0.68rem; padding:2px 8px;">Uang Pas</button>
                                <button type="button" class="btn-quick-cash" onclick="setQuickCash(50000)" style="font-size:0.68rem; padding:2px 8px;">50rb</button>
                                <button type="button" class="btn-quick-cash" onclick="setQuickCash(100000)" style="font-size:0.68rem; padding:2px 8px;">100rb</button>
                            </div>
                        </div>
                        <input type="text" id="cashPaidDisplayInput" class="form-input"
                            style="height:38px; font-size:0.95rem; font-weight:800; color:#0f172a; border-radius:10px; width:100%; margin-bottom:6px;"
                            placeholder="0" oninput="handleCashInput(this)">
                        <input type="hidden" id="cashPaidInput" value="0">
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.78rem;">
                            <span style="color:#64748b; font-weight:600;">Kembalian:</span>
                            <strong style="color:#1eb349; font-size:0.92rem;" id="cashChangeDisplay">Rp 0</strong>
                        </div>
                    </div>

                    <!-- Opsi Non-Tunai / Cashless -->
                    <div class="pay-option-card {{ $isDepositEmpty ? 'selected' : '' }}" id="optCashless"
                        onclick="selectPaymentMethod('cashless')" style="padding:0.65rem 0.85rem; border-radius:12px;">
                        <input type="radio" name="pay_method" value="cashless" {{ $isDepositEmpty ? 'checked' : '' }} style="accent-color:#1eb349;">
                        <div style="flex:1; margin-left:6px;">
                            <div style="font-size:0.84rem; font-weight:700; color:#0f172a;">Non-Tunai / QRIS</div>
                            <div style="font-size:0.7rem; color:#64748b;">QRIS & Pembayaran Digital</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pos-modal-foot" style="padding: 0.85rem 1.25rem;">
                <button type="button" class="btn-outline-bio"
                    style="border-color:#e2e8f0; color:#64748b; padding:0.55rem 1rem; font-size:0.78rem; border-radius:999px !important;"
                    onclick="closeModal('posPaymentModal')">Batal</button>
                <button type="button" class="btn-submit-green" id="btnSubmitOrder" onclick="processOrderCheckout()"
                    style="flex:1; margin:0; padding:0.6rem 1rem; font-size:0.82rem; border-radius:999px !important;">
                    Konfirmasi &amp; Bayar
                </button>
            </div>
        </div>
    </div>
        
    <!-- ── POP-UP MODAL: ATUR PERIODE KALENDER POS (100% STYLE BUYLE) ── -->
    <div class="pos-modal-overlay" id="posPeriodCalendarModal" style="z-index: 100020;">
        <div class="pos-modal-card" style="max-width: 420px; width: 92vw; border-radius: 24px; padding: 1.4rem; background: #ffffff; box-shadow: 0 20px 50px rgba(0,0,0,0.25);">
            <!-- Modal Header -->
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 1.2rem; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 0.85rem;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:38px; height:38px; border-radius:12px; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#166534; flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin:0; font-size:1.02rem; font-weight:800; color:#0f172a;">Atur Periode Kalender</h4>
                        <p style="margin:0; font-size:0.72rem; color:#64748b;">Filter riwayat transaksi kasir Buyle</p>
                    </div>
                </div>
                <button type="button" class="pos-modal-close" onclick="closeModal('posPeriodCalendarModal')" style="width:32px; height:32px; border-radius:50%; border:none; background:#f1f5f9; color:#64748b; font-size:1.2rem; display:flex; align-items:center; justify-content:center; cursor:pointer;">&times;</button>
            </div>

            <!-- Modal Body -->
            <div style="display:flex; flex-direction:column; gap:1.1rem;">
                <!-- Pilihan Cepat -->
                <div>
                    <label style="font-size:0.72rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.03em; margin-bottom:0.5rem; display:block;">Pilihan Cepat Periode</label>
                    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:8px;">
                        <button type="button" class="pos-modal-preset-btn active" id="modalPresetAll" onclick="applyModalPreset('all')">Semua</button>
                        <button type="button" class="pos-modal-preset-btn" id="modalPresetToday" onclick="applyModalPreset('today')">Hari Ini</button>
                        <button type="button" class="pos-modal-preset-btn" id="modalPreset7d" onclick="applyModalPreset('7d')">7 Hari</button>
                        <button type="button" class="pos-modal-preset-btn" id="modalPreset30d" onclick="applyModalPreset('30d')">30 Hari</button>
                        <button type="button" class="pos-modal-preset-btn" id="modalPreset1y" onclick="applyModalPreset('1y')">1 Tahun</button>
                        <button type="button" class="pos-modal-preset-btn" id="modalPresetMonth" onclick="applyModalPreset('this_month')">Bulan Ini</button>
                    </div>
                </div>

                <!-- Rentang Tanggal Kalender (Custom Range) -->
                <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:16px; padding:12px 14px;">
                    <div style="font-size:0.75rem; font-weight:800; color:#0f172a; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                        <svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span>Atur Rentang Tanggal Kalender</span>
                    </div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                        <div>
                            <label style="font-size:0.68rem; font-weight:700; color:#64748b; margin-bottom:4px; display:block;">Dari Tanggal</label>
                            <input type="date" id="posCalStartDate" style="width:100%; height:40px; border-radius:12px; border:1.5px solid #e2e8f0; background:#ffffff; padding:0 10px; font-size:0.78rem; font-weight:600; color:#0f172a; outline:none; font-family:inherit;">
                        </div>
                        <div>
                            <label style="font-size:0.68rem; font-weight:700; color:#64748b; margin-bottom:4px; display:block;">Sampai Tanggal</label>
                            <input type="date" id="posCalEndDate" style="width:100%; height:40px; border-radius:12px; border:1.5px solid #e2e8f0; background:#ffffff; padding:0 10px; font-size:0.78rem; font-weight:600; color:#0f172a; outline:none; font-family:inherit;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Foot -->
            <div style="display:flex; gap:10px; margin-top:1.25rem; border-top:1.5px solid #f1f5f9; padding-top:1rem;">
                <button type="button" onclick="resetCalendarFilter()" style="flex:1; height:42px; border-radius:999px; background:#f1f5f9; border:none; color:#475569; font-size:0.8rem; font-weight:700; cursor:pointer;">
                    Reset
                </button>
                <button type="button" onclick="applyCustomDateFilter()" class="btn-submit-green" style="flex:1.5; height:42px; border-radius:999px !important; margin:0; font-size:0.82rem; font-weight:800; cursor:pointer;">
                    Terapkan Periode
                </button>
            </div>
        </div>
    </div>

    <div class="pos-modal-overlay" id="posReceiptModal">
        <div class="pos-modal-card" style="max-width: 440px; border-radius: 24px; overflow: hidden; background: #f8fafc;">
            <!-- Modal Head -->
            <div class="pos-modal-head no-print" style="padding: 1rem 1.25rem; border-bottom: 1.5px solid #f1f5f9; background: #ffffff;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:28px; height:28px; border-radius:50%; background:#f0fdf4; border:1.5px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#166534;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div>
                        <h5 style="margin:0; font-size:1rem; font-weight:800; color:#0f172a;">Transaksi Berhasil</h5>
                        <div style="font-size:0.68rem; color:#64748b;">Tagihan telah lunas dan tercatat</div>
                    </div>
                </div>
                <button type="button" class="pos-modal-close" onclick="closeModal('posReceiptModal'); if(window._posReceiptFromTrans){window._posReceiptFromTrans=false;}else{resetPOS();}">&times;</button>
            </div>

            <div class="pos-modal-body" id="posReceiptModalContent" style="padding: 1.1rem 1.25rem;">
                <!-- ── MODERN TICKET BILL CARD (Sesuai Mockup Image 4) ── -->
                <div id="printablePosReceiptArea" style="background:#ffffff; border-radius:20px; border:1.5px solid #e2e8f0; padding:1.25rem 1.25rem 1rem; position:relative; box-shadow:0 4px 16px rgba(0,0,0,0.03);">
                    <!-- Header Bill: Logo + Nama Brand + Alamat + No Telepon + Nomor Invoice -->
                    <div style="text-align:center; padding-bottom:10px; border-bottom:1.5px dashed #e2e8f0;">
                        <!-- Logo Toko (jika ada) -->
                        <div id="recBillLogoWrap" style="margin-bottom:6px; {{ empty($posLogoUrl) ? 'display:none;' : '' }}">
                            <img id="recBillLogoImg" src="{{ $posLogoUrl ?? '' }}" alt="Logo Toko"
                                style="max-height:56px; max-width:160px; object-fit:contain; margin:0 auto; display:block; border-radius:6px;">
                        </div>
                        <div style="font-size:1.1rem; font-weight:800; color:#0f172a; letter-spacing:-0.01em;">
                            {{ $storeName }}
                        </div>
                        @if($posStoreAddress ?: ($profile?->address ?? ''))
                        <div style="font-size:0.7rem; color:#64748b; margin-top:2px;">
                            {{ $posStoreAddress ?: ($profile?->address ?? '') }}
                        </div>
                        @endif
                        @if($posStorePhone ?? '')
                        <div style="font-size:0.7rem; color:#64748b; margin-top:1px;">
                            {{ $posStorePhone }}
                        </div>
                        @endif
                        <div style="display:inline-flex; align-items:center; gap:6px; background:#f0fdf4; border:1px solid #bbf7d0; padding:2px 10px; border-radius:999px; margin-top:6px;">
                            <span style="width:6px; height:6px; border-radius:50%; background:#16a34a;"></span>
                            <span style="font-size:0.68rem; font-weight:800; color:#15803d;" id="recOrderCode">#INV-POS</span>
                        </div>
                    </div>

                    <!-- Meta info (Tanggal, Kasir & Customer) -->
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; font-size:0.72rem; color:#64748b; padding:8px 0; border-bottom:1.5px dashed #e2e8f0;">
                        <div>
                            <div><span id="recDateOnly">{{ now()->translatedFormat('d M Y') }}</span> &middot; <span id="recTimeOnly">{{ now()->format('H:i') }}</span></div>
                            <div style="margin-top:2px; color:#0f172a; font-weight:700;" id="recCustomerInfoBill">Pelanggan Umum</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-weight:700; color:#0f172a;" id="recCashierNameBill">{{ $currentCashier ? $currentCashier->name : auth()->user()->name }}</div>
                            <div style="color:#94a3b8; font-size:0.65rem;">Kasir</div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div id="recNumberedItemsWrap" style="padding:10px 0 6px; font-size:0.78rem;">
                        <!-- Dynamic items -->
                    </div>

                    <!-- Summary & Total -->
                    <div style="border-top:1.5px dashed #e2e8f0; padding-top:8px;">
                        <div style="display:flex; justify-content:space-between; font-size:0.75rem; color:#64748b; margin-bottom:4px;">
                            <span>Subtotal (<span id="recTotalQty">0</span> item)</span>
                            <span id="recSubtotal" style="font-weight:700; color:#0f172a;">Rp 0</span>
                        </div>
                        <div id="recDiscountRow" style="display:none; justify-content:space-between; font-size:0.75rem; color:#dc2626; margin-bottom:4px;">
                            <span id="recDiscountLabel">Diskon</span>
                            <span id="recDiscount" style="font-weight:700;">-Rp 0</span>
                        </div>
                        <!-- Rincian Biaya Tambahan & Pajak POS -->
                        <div id="recCustomFeesWrap" style="display:none; margin-bottom:4px; font-size:0.75rem;"></div>

                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px; padding-top:6px; border-top:1px solid #f1f5f9;">
                            <div>
                                <div style="font-size:0.68rem; font-weight:700; color:#64748b; text-transform:uppercase;">Total Tagihan</div>
                                <div style="font-size:1.25rem; font-weight:900; color:#15803d;" id="recTotal">Rp 0</div>
                            </div>
                            <div style="text-align:right;">
                                <span style="font-size:0.7rem; font-weight:800; background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; padding:3px 10px; border-radius:999px;">
                                    LUNAS
                                </span>
                                <div style="font-size:0.68rem; color:#64748b; margin-top:2px;" id="recPayMethodLabel">Tunai</div>
                            </div>
                        </div>

                        <!-- Rincian Tunai Dibayar & Kembalian -->
                        <div id="recPaymentCashDetails" style="display:none; margin-top:6px; padding-top:6px; border-top:1px dashed #f1f5f9; font-size:0.75rem; color:#64748b;">
                            <div id="recCashPaidRow" style="display:flex; justify-content:space-between; margin-bottom:3px;">
                                <span>Tunai Dibayar</span>
                                <span id="recCashPaidVal" style="font-weight:700; color:#0f172a;">Rp 0</span>
                            </div>
                            <div id="recCashChangeRow" style="display:flex; justify-content:space-between;">
                                <span>Kembalian</span>
                                <span id="recCashChangeVal" style="font-weight:700; color:#15803d;">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div style="text-align:center; margin-top:12px; padding-top:8px; border-top:1px dashed #e2e8f0; font-size:0.68rem; color:#94a3b8;">
                        Terima kasih atas kunjungan Anda &middot; Powered by <strong>buyle.id</strong>
                    </div>
                </div>

                <!-- ── SHARE & ACTIONS (Direct WA + Auto Generate Gambar Bill) ── -->
                <div class="no-print" style="margin-top: 1rem; display:flex; flex-direction:column; gap:8px;">
                    <!-- 1. Tombol Kirim Struk ke WhatsApp (Direct WA) -->
                    <button type="button" onclick="shareBillToWhatsApp()"
                        style="width:100%; height:46px; border-radius:999px; background:#25D366; color:#ffffff; border:none; font-size:0.84rem; font-weight:700; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 4px 14px rgba(37,211,102,0.3); cursor:pointer;">
                        <svg width="19" height="19" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.164.576 4.195 1.583 5.952l-1.683 6.147 6.304-1.654c1.716.945 3.69 1.485 5.796 1.485 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
                        </svg>
                        <span>Share Bill to WhatsApp</span>
                    </button>

                    <!-- 2. Tombol Download Gambar Struk (Auto Generate Image via html2canvas) -->
                    <button type="button" id="btnDownloadBillImg" onclick="generateBillImage()"
                        style="width:100%; height:42px; border-radius:999px; background:#ffffff; border:1.5px solid #e2e8f0; color:#0f172a; font-size:0.8rem; font-weight:700; display:flex; align-items:center; justify-content:center; gap:6px; cursor:pointer;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        <span>Download Gambar Bill</span>
                    </button>

                    <div style="display:flex; gap:8px; margin-top:2px;">
                        <!-- Cetak Thermal -->
                        <button type="button" class="btn-outline-bio" onclick="printPosReceipt()"
                            style="flex:1; height:40px; border-radius:999px !important; font-size:0.78rem;">
                            Cetak Struk
                        </button>
                        <!-- Transaksi Baru -->
                        <button type="button" class="btn-submit-green" onclick="closeModal('posReceiptModal'); resetPOS();"
                            style="flex:1; height:40px; border-radius:999px !important; font-size:0.78rem; margin:0;">
                            Transaksi Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CUSTOM OVERLAY MODAL: Pengaturan Modul POS (Logo, Alamat, No Telp, Staf Kasir) -->
    <div class="pos-modal-overlay" id="posSettingsModal" data-static-backdrop="true">
        <div class="pos-modal-card" style="max-width: 520px; width: 92vw; max-height: 85vh; border-radius: 20px;">
            <!-- Modal Header -->
            <div class="pos-modal-head" style="border-bottom: 1.5px solid #f1f5f9; padding: 1.1rem 1.4rem;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div
                        style="width:36px; height:36px; border-radius:10px; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#166534; flex-shrink:0;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                        </svg>
                    </div>
                    <div>
                        <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a; line-height:1.2;">Pengaturan
                            Kasir POS</h5>
                        <div style="font-size:0.73rem; color:#64748b; margin-top:2px;">Format struk, pajak/biaya tambahan,
                            diskon produk &amp; akun kasir</div>
                    </div>
                </div>
                <!-- Tombol X Close (Hanya bisa keluar lewat tombol ini atau Simpan) -->
                <button type="button" class="pos-modal-close" onclick="closeModal('posSettingsModal')"
                    title="Tutup Pengaturan">&times;</button>
            </div>

            <!-- Tab Buttons Bar (4 Tab Lengkap: Struk & Toko, Pajak & Biaya, Diskon & Promo, Staf Kasir) -->
            <div
                style="display:flex; align-items:center; border-bottom:1.5px solid #e2e8f0; background:#f8fafc; padding:0 1.25rem; gap:6px; overflow-x:auto; scrollbar-width:none;">
                <button type="button" class="history-tab-btn active" id="tabBtnSettingStruk"
                    onclick="switchSettingTab('struk')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                    Struk &amp; Toko
                </button>
                <button type="button" class="history-tab-btn" id="tabBtnSettingFees" onclick="switchSettingTab('fees')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    Pajak &amp; Biaya (<span id="settingFeesBadgeCount">0</span>)
                </button>
                <button type="button" class="history-tab-btn" id="tabBtnSettingDiscounts"
                    onclick="switchSettingTab('discounts')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="5" x2="5" y2="19" />
                        <circle cx="6.5" cy="6.5" r="2.5" />
                        <circle cx="17.5" cy="17.5" r="2.5" />
                    </svg>
                    Diskon &amp; Promo (<span id="settingDiscountsBadgeCount">0</span>)
                </button>
                <button type="button" class="history-tab-btn" id="tabBtnSettingStaff" onclick="switchSettingTab('staff')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    Staf Kasir (<span id="settingStaffBadgeCount">{{ count($cashiers) }}</span>)
                </button>
                <button type="button" class="history-tab-btn" id="tabBtnSettingTables" onclick="switchSettingTab('tables')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path d="M3 9h18M9 21V9" />
                    </svg>
                    Kelola Meja (<span id="settingTablesBadgeCount">{{ count($posTables ?? []) }}</span>)
                </button>
                <button type="button" class="history-tab-btn" id="tabBtnSettingPayment"
                    onclick="switchSettingTab('payment')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <line x1="2" y1="10" x2="22" y2="10" />
                    </svg>
                    Pembayaran &amp; Fee
                </button>
            </div>

            <!-- Modal Body with Scroll -->
            <div class="pos-modal-body" style="padding: 1.25rem; background: #ffffff;">

                <!-- Skeleton Loader for Settings Modal (Smooth Premium Feel) -->
                <div id="posSettingsSkeleton" style="display:none; flex-direction:column; gap:1.2rem;">
                    <!-- Shimmer Card 1 -->
                    <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.1rem;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.75rem;">
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                <div class="pos-skel-bone" style="width:140px; height:16px;"></div>
                                <div class="pos-skel-bone" style="width:230px; height:12px;"></div>
                            </div>
                            <div class="pos-skel-bone" style="width:80px; height:24px; border-radius:6px;"></div>
                        </div>
                        <div style="display:flex; gap:16px; align-items:center; margin-top:10px;">
                            <div class="pos-skel-bone" style="width:120px; height:60px; border-radius:10px;"></div>
                            <div class="pos-skel-bone" style="width:140px; height:34px; border-radius:8px;"></div>
                        </div>
                    </div>
                    <!-- Shimmer Card 2 -->
                    <div
                        style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.1rem; display:flex; flex-direction:column; gap:12px;">
                        <div class="pos-skel-bone" style="width:180px; height:16px;"></div>
                        <div class="pos-skel-bone" style="width:100%; height:38px; border-radius:8px;"></div>
                        <div class="pos-skel-bone" style="width:100%; height:60px; border-radius:8px;"></div>
                        <div class="pos-skel-bone" style="width:100%; height:38px; border-radius:8px;"></div>
                    </div>
                </div>

                <!-- Real Settings Content Wrapper -->
                <div id="posSettingsRealContent" style="transition: opacity 0.2s ease;">

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- TAB 1: STRUK & PROFIL TOKO                         -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <div id="paneSettingStruk" style="display: block;">
                        <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;">

                            <!-- Upload Logo Struk -->
                            <div
                                style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.1rem;">
                                <div
                                    style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                                    <div>
                                        <div style="font-size:0.85rem; font-weight:800; color:#0f172a;">Logo Struk Transaksi
                                        </div>
                                        <div style="font-size:0.72rem; color:#64748b;">Logo toko akan dicetak di bagian
                                            paling atas struk thermal</div>
                                    </div>
                                    <div id="posLogoDeleteWrap" style="{{ empty($posLogoUrl) ? 'display:none;' : '' }}">
                                        <button type="button" onclick="handlePosLogoDelete()"
                                            style="background:#fee2e2; border:1px solid #fecaca; color:#dc2626; font-size:0.72rem; font-weight:700; padding:4px 9px; border-radius:6px; cursor:pointer;">
                                            Hapus Logo
                                        </button>
                                    </div>
                                </div>

                                <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                                    <div id="posLogoPreviewContainer"
                                        style="width: 130px; height: 65px; border: 2px dashed #cbd5e1; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: #ffffff; overflow: hidden; padding: 4px; flex-shrink:0;">
                                        @if(!empty($posLogoUrl))
                                            <img id="posLogoPreviewImg" src="{{ $posLogoUrl }}" alt="Logo POS"
                                                style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        @else
                                            <span id="posLogoPreviewEmpty"
                                                style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-align:center;">Belum
                                                ada logo</span>
                                        @endif
                                    </div>

                                    <div style="flex:1; min-width: 180px;">
                                        <form id="formUploadPosLogo" onsubmit="handlePosLogoUpload(event)">
                                            <input type="file" id="posLogoFileInput" accept="image/*" style="display:none;"
                                                onchange="previewPosLogoSelected(this)">
                                            <div style="display:flex; gap:8px; align-items:center;">
                                                <button type="button" class="btn-outline-bio"
                                                    onclick="document.getElementById('posLogoFileInput').click()"
                                                    style="padding:0.45rem 0.9rem; font-size:0.75rem; border-radius:8px;">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg>
                                                    Pilih Logo Baru
                                                </button>
                                                <button type="submit" class="btn-submit-green" id="btnSavePosLogo"
                                                    style="width:auto; padding:0.45rem 1rem; font-size:0.75rem; border-radius:8px; display:none;">
                                                    Upload &amp; Pasang
                                                </button>
                                            </div>
                                        </form>
                                        <div style="font-size:0.69rem; color:#94a3b8; margin-top:6px; line-height:1.35;">
                                            Format JPG, PNG, WEBP atau SVG (Maks. 3MB). Saran: format horizontal /
                                            transparan.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Toko di Struk -->
                            <div
                                style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.1rem;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a; margin-bottom:0.25rem;">
                                    Identitas Toko di Cetakan Struk</div>
                                <div style="font-size:0.72rem; color:#64748b; margin-bottom:1rem;">Informasi nama, alamat
                                    dan kontak yang tercetak di kertas thermal struk</div>

                                <div style="display:flex; flex-direction:column; gap:12px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:4px;">Nama
                                            Toko Struk</label>
                                        <input type="text" id="setPosStoreName" class="form-input"
                                            style="height:38px; font-size:0.82rem;" value="{{ $storeName }}"
                                            placeholder="Contoh: Toko Kopi Senja" oninput="liveUpdateReceiptPreview()">
                                    </div>

                                    <div>
                                        <label
                                            style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:4px;">Alamat
                                            Toko</label>
                                        <textarea id="setPosStoreAddress" class="form-input" rows="2"
                                            style="font-size:0.82rem; padding:8px 10px; resize:none;"
                                            placeholder="Contoh: Jl. Sudirman No. 12, Jakarta Pusat"
                                            oninput="liveUpdateReceiptPreview()">{{ $posStoreAddress ?: ($profile?->address ?? '') }}</textarea>
                                    </div>

                                    <div>
                                        <label
                                            style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:4px;">No.
                                            Telepon / WhatsApp Toko</label>
                                        <input type="text" id="setPosStorePhone" class="form-input"
                                            style="height:38px; font-size:0.82rem;"
                                            value="{{ $posStorePhone ?: ($profile?->phone ?? '') }}"
                                            placeholder="Contoh: 081234567890" oninput="liveUpdateReceiptPreview()">
                                    </div>
                                </div>
                            </div>

                            <!-- Mini Live Preview Box -->
                            <div
                                style="border:1.5px dashed #cbd5e1; border-radius:12px; padding:12px 16px; background:#ffffff; text-align:center;">
                                <div
                                    style="font-size:0.68rem; font-weight:800; text-transform:uppercase; letter-spacing:1px; color:#64748b; margin-bottom:8px;">
                                    — Live Preview Header Struk —
                                </div>
                                <div id="prevReceiptHeader"
                                    style="font-family:'Montserrat',sans-serif; color:#000; line-height:1.35; max-width:280px; margin:0 auto;">
                                    <div id="prevHeaderLogo" style="margin-bottom:4px;">
                                        @if(!empty($posLogoUrl))
                                            <img src="{{ $posLogoUrl }}" alt="Logo"
                                                style="max-height:36px; max-width:120px; object-fit:contain; margin:0 auto; display:block;">
                                        @endif
                                    </div>
                                    <div id="prevHeaderName" style="font-weight:800; font-size:13.5px;">{{ $storeName }}
                                    </div>
                                    <div id="prevHeaderAddress" style="font-size:10px; color:#333; margin-top:2px;">
                                        {{ $posStoreAddress ?: ($profile?->address ?? 'Alamat toko belum diatur') }}
                                    </div>
                                    <div id="prevHeaderPhone" style="font-size:10px; color:#333; margin-top:1px;">
                                        {{ $posStorePhone ? ('No. Telp ' . $posStorePhone) : 'No. Telp: -' }}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Footer Tab 1: Simpan Pengaturan -->
                        <div style="margin-top:1.25rem; display:flex; justify-content:flex-end; gap:8px;">
                            <button type="button" class="btn-submit-green" id="btnSaveStoreSettings"
                                onclick="savePosStoreSettings()"
                                style="width:auto; padding:0.6rem 1.6rem; font-size:0.82rem; border-radius:999px;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- TAB 2: PAJAK & BIAYA TAMBAHAN                       -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <div id="paneSettingFees" style="display: none;">
                        <!-- Guidance Alert Box -->
                        <div
                            style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#166534" stroke-width="2.2" viewBox="0 0 24 24"
                                style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#166534; line-height:1.45;">
                                <strong>Pajak &amp; Biaya Tambahan Fleksibel:</strong> Anda dapat menambahkan beberapa jenis
                                biaya atau pajak (misal <strong>PPN 11%</strong>, <strong>Service Charge Resto 5%</strong>,
                                <strong>PB1</strong>, atau <strong>Biaya Kemasan/Takeaway Rp 2.000</strong>). Biaya yang
                                berstatus aktif akan otomatis dihitung dan dirinci di struk thermal kasir.
                            </div>
                        </div>

                        <!-- Top Action Bar -->
                        <div
                            style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:8px;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Daftar Biaya &amp; Pajak POS
                                </div>
                                <div style="font-size:0.72rem; color:#64748b;">Atur persentase (%) atau nominal rupiah (Rp)
                                    untuk tiap komponen</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="addFeeRow()"
                                style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                + Tambah Biaya / Pajak
                            </button>
                        </div>

                        <!-- Container Daftar Biaya -->
                        <div id="feeRowsListContainer" style="display:flex; flex-direction:column; gap:10px;">
                            <!-- Rendered dynamically by renderFeeSettingsList() -->
                        </div>

                        <!-- Live Simulation Box -->
                        <div
                            style="margin-top:1.25rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px; padding:12px 14px;">
                            <div
                                style="font-size:0.74rem; font-weight:700; color:#475569; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                Simulasi Perhitungan Transaksi (Contoh Subtotal Rp 100.000):
                            </div>
                            <div id="feeLiveSimulationText"
                                style="font-size:0.78rem; color:#0f172a; font-weight:600; line-height:1.5;">
                                Memuat simulasi...
                            </div>
                        </div>

                        <!-- Footer Tab 2: Simpan Pengaturan Biaya -->
                        <div style="margin-top:1.25rem; display:flex; justify-content:flex-end; gap:8px;">
                            <button type="button" class="btn-submit-green" id="btnSaveFeesSettings"
                                onclick="savePosFeesSettings()"
                                style="width:auto; padding:0.6rem 1.6rem; font-size:0.82rem; border-radius:999px;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                Simpan Pengaturan Biaya &amp; Pajak
                            </button>
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- TAB 3: DISKON & PROMO PRODUK                        -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <div id="paneSettingDiscounts" style="display: none;">
                        <!-- Guidance Alert Box -->
                        <div
                            style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24"
                                style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#1e40af; line-height:1.45;">
                                <strong>Promo Fleksibel Berbasis Produk:</strong> Anda dapat membuat promo potongan harga
                                (%) atau nominal (Rp). Diskon dapat diterapkan untuk <strong>Semua Produk</strong> atau
                                <strong>Produk Tertentu (bisa pilih lebih dari 1 produk)</strong>. Saat transaksi di kasir
                                POS, sistem akan otomatis menghitung potongan hanya untuk produk yang valid!
                            </div>
                        </div>

                        <!-- Top Action Bar -->
                        <div
                            style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:8px;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Katalog Diskon &amp; Promo
                                    POS</div>
                                <div style="font-size:0.72rem; color:#64748b;">Kelola promo aktif, potongan harga, dan
                                    target produk</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="openAddDiscountDrawer()"
                                style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                + Buat Diskon Baru
                            </button>
                        </div>

                        <!-- Collapsible Drawer / Form Buat & Edit Diskon -->
                        <div id="discountDrawerForm"
                            style="display:none; background:#f8fafc; border:1.5px solid #cbd5e1; border-radius:14px; padding:1.1rem; margin-bottom:1.25rem;">
                            <div
                                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.85rem; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a;" id="discountDrawerTitle">
                                    Tambah Diskon / Promo Baru
                                </div>
                                <button type="button" onclick="closeDiscountDrawer()"
                                    style="background:none; border:none; font-size:1.1rem; color:#64748b; cursor:pointer; line-height:1;">&times;</button>
                            </div>

                            <form id="formDiscountData" onsubmit="handleSaveDiscount(event)">
                                <input type="hidden" id="discountEditId" value="">

                                <div style="display:grid; grid-template-columns:1fr; gap:10px; margin-bottom:10px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Nama
                                            Promo / Diskon *</label>
                                        <input type="text" id="discountInputName" class="form-input"
                                            style="height:36px; font-size:0.8rem;"
                                            placeholder="Contoh: Diskon Kopi 15% / Promo Gajian Rp 10.000" required>
                                    </div>
                                </div>

                                <div class="cashier-form-grid"
                                    style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Tipe
                                            Diskon *</label>
                                        <select id="discountInputType" class="form-input"
                                            style="height:36px; font-size:0.8rem;" onchange="onDiscountTypeChanged()">
                                            <option value="percent">Persentase (%)</option>
                                            <option value="nominal">Nominal Rupiah (Rp)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Besaran
                                            Potongan *</label>
                                        <div style="position:relative;">
                                            <input type="number" id="discountInputValue" class="form-input"
                                                style="height:36px; font-size:0.8rem; padding-right:32px;"
                                                placeholder="Contoh: 15" min="0" step="any" required>
                                            <span id="discountValueSuffix"
                                                style="position:absolute; right:10px; top:50%; transform:translateY(-50%); font-size:0.75rem; font-weight:700; color:#64748b;">%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Scope Pilihan Produk -->
                                <div
                                    style="margin-bottom:12px; background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px;">
                                    <label
                                        style="display:block; font-size:0.73rem; font-weight:800; color:#1e293b; margin-bottom:6px;">Berlaku
                                        Untuk Produk:</label>
                                    <div style="display:flex; gap:16px; flex-wrap:wrap;">
                                        <label
                                            style="display:flex; align-items:center; gap:6px; font-size:0.78rem; font-weight:600; color:#334155; cursor:pointer;">
                                            <input type="radio" name="discountScopeRadio" value="all" checked
                                                onchange="toggleDiscountScopeUI('all')"
                                                style="accent-color:#1eb349; cursor:pointer;">
                                            <span>Semua Produk (Global)</span>
                                        </label>
                                        <label
                                            style="display:flex; align-items:center; gap:6px; font-size:0.78rem; font-weight:600; color:#334155; cursor:pointer;">
                                            <input type="radio" name="discountScopeRadio" value="specific"
                                                onchange="toggleDiscountScopeUI('specific')"
                                                style="accent-color:#1eb349; cursor:pointer;">
                                            <span>Pilih Produk Tertentu (Multi-select)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Multi-product Picker Checklist (Visible only when scope === 'specific') -->
                                <div id="discountProductPickerWrap"
                                    style="display:none; margin-bottom:12px; background:#ffffff; border:1.5px solid #cbd5e1; border-radius:10px; padding:10px;">
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; gap:8px; flex-wrap:wrap;">
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <span style="font-size:0.75rem; font-weight:700; color:#0f172a;">Pilih
                                                Produk:</span>
                                            <span id="discountSelectedCountBadge"
                                                style="font-size:0.68rem; background:#dcfce7; color:#15803d; font-weight:700; padding:1px 7px; border-radius:999px;">0
                                                produk dipilih</span>
                                        </div>
                                        <div style="display:flex; gap:6px;">
                                            <button type="button" onclick="selectAllDiscountProducts()"
                                                style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-size:0.68rem; font-weight:700; padding:3px 8px; border-radius:6px; cursor:pointer;">
                                                Pilih Semua
                                            </button>
                                            <button type="button" onclick="deselectAllDiscountProducts()"
                                                style="background:#fee2e2; border:1px solid #fecaca; color:#dc2626; font-size:0.68rem; font-weight:700; padding:3px 8px; border-radius:6px; cursor:pointer;">
                                                Hapus Pilihan
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Search input within picker -->
                                    <div style="margin-bottom:8px;">
                                        <input type="text" id="discountProductPickerSearch" class="form-input"
                                            style="height:32px; font-size:0.74rem;" placeholder="Cari nama produk..."
                                            oninput="filterDiscountProductPicker()">
                                    </div>

                                    <!-- Scrollable Checklist -->
                                    <div id="discountProductPickerList"
                                        style="max-height:220px; overflow-y:auto; display:flex; flex-direction:column; gap:4px; padding-right:4px;">
                                        <!-- Rendered dynamically from window.posAllProducts -->
                                    </div>
                                </div>

                                <!-- Status Toggle -->
                                <div style="margin-bottom:12px;">
                                    <label
                                        style="display:flex; align-items:center; gap:8px; font-size:0.8rem; font-weight:600; color:#1e293b; cursor:pointer;">
                                        <input type="checkbox" id="discountInputActive" checked
                                            style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer;">
                                        <span>Promo Aktif &amp; Siap Digunakan di Kasir</span>
                                    </label>
                                </div>

                                <div
                                    style="display:flex; justify-content:flex-end; gap:8px; border-top:1px solid #e2e8f0; padding-top:10px;">
                                    <button type="button" class="btn-outline-bio" onclick="closeDiscountDrawer()"
                                        style="padding:0.45rem 1rem; font-size:0.76rem; border-radius:8px;">Batal</button>
                                    <button type="submit" class="btn-submit-green" id="btnSaveDiscountSubmit"
                                        style="width:auto; padding:0.45rem 1.25rem; font-size:0.76rem; border-radius:8px;">
                                        Simpan Promo Diskon
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Discounts List Cards -->
                        <div id="discountListContainer" style="display:flex; flex-direction:column; gap:8px;">
                            <!-- Rendered dynamically by renderDiscountSettingsList() -->
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- TAB 4: STAF KASIR & SHIFT                          -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <div id="paneSettingStaff" style="display: none;">

                        <!-- Guidance Alert Box -->
                        <div
                            style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24"
                                style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#1e40af; line-height:1.45;">
                                <strong>Akses Terbatas Staf Kasir:</strong> Akun kasir hanya dapat membuka halaman
                                <strong>Kasir POS</strong>. Seluruh menu lain (Overview, Produk, Saldo, Laporan) otomatis
                                terkunci demi keamanan data Anda. Setiap transaksi dan closing shift akan tercatat atas nama
                                staf yang sedang bertugas.
                            </div>
                        </div>

                        <!-- Top Action Bar -->
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Daftar Akun Kasir</div>
                                <div style="font-size:0.72rem; color:#64748b;">Kelola akun login (email), jam kerja / shift,
                                    dan status aktif staf</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="openAddCashierDrawer()"
                                style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                + Tambah Staf Kasir
                            </button>
                        </div>

                        <!-- Collapsible Drawer / Form Tambah & Edit Staf -->
                        <div id="cashierDrawerForm"
                            style="display:none; background:#f8fafc; border:1.5px solid #cbd5e1; border-radius:14px; padding:1.1rem; margin-bottom:1.25rem;">
                            <div
                                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.85rem; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a;" id="cashierDrawerTitle">
                                    Tambah Staf Kasir Baru
                                </div>
                                <button type="button" onclick="closeCashierDrawer()"
                                    style="background:none; border:none; font-size:1.1rem; color:#64748b; cursor:pointer; line-height:1;">&times;</button>
                            </div>

                            <form id="formCashierData" onsubmit="handleSaveCashier(event)">
                                <input type="hidden" id="cashierEditId" value="">

                                <div class="cashier-form-grid"
                                    style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Nama
                                            Lengkap Staf *</label>
                                        <input type="text" id="cashierInputName" class="form-input"
                                            style="height:36px; font-size:0.8rem;" placeholder="Contoh: Siti Rahmawati"
                                            required>
                                    </div>
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Jabatan
                                            / Role Title</label>
                                        <input type="text" id="cashierInputRole" class="form-input"
                                            style="height:36px; font-size:0.8rem;"
                                            placeholder="Contoh: Kasir Pagi / Kasir Utama" value="Kasir">
                                    </div>
                                </div>

                                <div class="cashier-form-grid"
                                    style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Email
                                            Login *</label>
                                        <input type="email" id="cashierInputEmail" class="form-input"
                                            style="height:36px; font-size:0.8rem;" placeholder="Contoh: kasir@tokosaya.com"
                                            required autocomplete="off">
                                        <div style="font-size:0.67rem; color:#94a3b8; margin-top:2px;">Email untuk login
                                            staf kasir ke sistem POS</div>
                                    </div>
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Password
                                            Login <span id="cashierPasswordNotice"
                                                style="font-weight:normal; color:#94a3b8;">*</span></label>
                                        <input type="password" id="cashierInputPassword" class="form-input"
                                            style="height:36px; font-size:0.8rem;" placeholder="Minimal 6 karakter"
                                            autocomplete="new-password">
                                        <div style="font-size:0.67rem; color:#94a3b8; margin-top:2px;"
                                            id="cashierPassHelper">Wajib diisi untuk akun baru</div>
                                    </div>
                                </div>

                                <div class="cashier-form-grid"
                                    style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:4px;">Jam
                                            Kerja / Shift Kasir</label>
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <input type="time" id="cashierShiftStart" class="form-input"
                                                style="height:36px; font-size:0.82rem; padding:0 8px; flex:1;" value="08:00"
                                                onchange="syncCashierShiftTime()">
                                            <span style="font-size:0.73rem; font-weight:700; color:#64748b;">s/d</span>
                                            <input type="time" id="cashierShiftEnd" class="form-input"
                                                style="height:36px; font-size:0.82rem; padding:0 8px; flex:1;" value="16:00"
                                                onchange="syncCashierShiftTime()">
                                        </div>
                                        <input type="hidden" id="cashierInputShift" value="08:00 - 16:00">
                                        <div style="display:flex; gap:4px; flex-wrap:wrap; margin-top:6px;">
                                            <button type="button" onclick="setCashierShiftPreset('08:00', '16:00')"
                                                style="font-size:0.65rem; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:999px; padding:2px 7px; color:#475569; cursor:pointer; font-weight:600;">Pagi
                                                (08:00 - 16:00)</button>
                                            <button type="button" onclick="setCashierShiftPreset('12:00', '20:00')"
                                                style="font-size:0.65rem; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:999px; padding:2px 7px; color:#475569; cursor:pointer; font-weight:600;">Siang
                                                (12:00 - 20:00)</button>
                                            <button type="button" onclick="setCashierShiftPreset('16:00', '00:00')"
                                                style="font-size:0.65rem; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:999px; padding:2px 7px; color:#475569; cursor:pointer; font-weight:600;">Malam
                                                (16:00 - 00:00)</button>
                                        </div>
                                    </div>
                                    <div style="display:flex; flex-direction:column; justify-content:center;">
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:4px;">Status
                                            Akun</label>
                                        <label
                                            style="display:flex; align-items:center; gap:8px; font-size:0.8rem; font-weight:600; color:#1e293b; cursor:pointer; padding:8px 0;">
                                            <input type="checkbox" id="cashierInputActive" checked
                                                style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer;">
                                            <span>Aktif &amp; Boleh Login</span>
                                        </label>
                                    </div>
                                </div>

                                <div
                                    style="display:flex; justify-content:flex-end; gap:8px; border-top:1px solid #e2e8f0; padding-top:10px;">
                                    <button type="button" class="btn-outline-bio" onclick="closeCashierDrawer()"
                                        style="padding:0.45rem 1rem; font-size:0.76rem; border-radius:8px;">Batal</button>
                                    <button type="submit" class="btn-submit-green" id="btnSaveCashierSubmit"
                                        style="width:auto; padding:0.45rem 1.25rem; font-size:0.76rem; border-radius:8px;">
                                        Simpan Akun Staf
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Cashiers List Cards / Grid -->
                        <div id="cashierListWrap" style="display:flex; flex-direction:column; gap:8px;">
                            @forelse($cashiers as $c)
                                <div class="cashier-card-item" id="cashierRow-{{ $c->id }}"
                                    style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between; gap:12px; transition:all 0.2s ease;">
                                    <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                                        <div
                                            style="width:38px; height:38px; border-radius:50%; background:#f1f5f9; border:1.5px solid #cbd5e1; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:800; color:#334155; flex-shrink:0;">
                                            {{ strtoupper(substr($c->name, 0, 2)) }}
                                        </div>
                                        <div style="min-width:0;">
                                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                <span
                                                    style="font-size:0.88rem; font-weight:800; color:#0f172a;">{{ $c->name }}</span>
                                                <span
                                                    style="font-size:0.7rem; color:#0369a1; background:#f0f9ff; border:1px solid #bae6fd; padding:1px 6px; border-radius:4px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                                                    <svg width="11" height="11" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path
                                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                                        <polyline points="22,6 12,13 2,6" />
                                                    </svg>
                                                    {{ $c->email }}
                                                </span>
                                                @if($c->is_active)
                                                    <span class="badge-status-active" id="badgeActive-{{ $c->id }}"
                                                        style="font-size:0.68rem; background:#dcfce7; color:#15803d; font-weight:700; padding:2px 7px; border-radius:999px;">Aktif</span>
                                                @else
                                                    <span class="badge-status-inactive" id="badgeActive-{{ $c->id }}"
                                                        style="font-size:0.68rem; background:#f1f5f9; color:#64748b; font-weight:700; padding:2px 7px; border-radius:999px;">Nonaktif</span>
                                                @endif
                                            </div>
                                            <div
                                                style="display:flex; align-items:center; gap:12px; font-size:0.72rem; color:#64748b; margin-top:3px; flex-wrap:wrap;">
                                                <span
                                                    style="display:inline-flex; align-items:center; gap:4px; color:#475569; font-weight:600;">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                        <circle cx="12" cy="7" r="4" />
                                                    </svg>
                                                    {{ $c->cashier_role_title ?: 'Kasir' }}
                                                </span>
                                                @if(!empty($c->shift_hours))
                                                    <span
                                                        style="display:inline-flex; align-items:center; gap:4px; color:#0369a1; background:#f0f9ff; padding:1px 6px; border-radius:4px; font-weight:600;">
                                                        <svg width="11" height="11" fill="none" stroke="currentColor"
                                                            stroke-width="2" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="12" r="10" />
                                                            <polyline points="12 6 12 12 16 14" />
                                                        </svg>
                                                        {{ $c->shift_hours }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                        <button type="button" class="btn-outline-bio"
                                            style="padding:4px 9px; font-size:0.72rem; border-radius:6px;"
                                            onclick='editCashierData(@json($c))' title="Edit Kasir">
                                            Edit
                                        </button>
                                        <button type="button"
                                            style="padding:4px 9px; font-size:0.72rem; border-radius:6px; border:1px solid #e2e8f0; background:#f8fafc; color:#334155; cursor:pointer; font-weight:600;"
                                            onclick="toggleCashierActive({{ $c->id }})" title="Aktifkan / Nonaktifkan">
                                            {{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                        <button type="button"
                                            style="padding:4px 8px; font-size:0.72rem; border-radius:6px; border:1px solid #fee2e2; background:#fff1f2; color:#e11d48; cursor:pointer; font-weight:700;"
                                            onclick="deleteCashier({{ $c->id }}, '{{ addslashes($c->name) }}')"
                                            title="Hapus Staf">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div id="cashierEmptyNotice"
                                    style="text-align:center; padding:2rem 1rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px;">
                                    <div
                                        style="width:40px; height:40px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 8px auto; color:#94a3b8;">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                        </svg>
                                    </div>
                                    <div style="font-size:0.85rem; font-weight:700; color:#334155;">Belum Ada Akun Staf Kasir
                                    </div>
                                    <div style="font-size:0.72rem; color:#64748b; margin-top:2px;">Klik tombol "+ Tambah Staf
                                        Kasir" di atas untuk menambahkan staf yang bertugas.</div>
                                </div>
                            @endforelse
                        </div>

                    </div>

                    <!-- TAB 5: KELOLA MEJA & BARCODE QR DINE-IN -->
                    <div id="paneSettingTables" style="display:none;">

                        <!-- Guidance Alert Box (Identik dengan Staf Kasir) -->
                        <div
                            style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24"
                                style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#1e40af; line-height:1.45;">
                                <strong>Dine-In Barcode QR Meja:</strong> Setiap meja memiliki Barcode QR khusus yang
                                terhubung ke Link in Bio (Tema 1-4). Pelanggan tinggal scan untuk memesan, dan nomor meja
                                langsung terisi otomatis saat checkout!
                            </div>
                        </div>

                        <!-- Top Action Bar (Identik dengan Staf Kasir) -->
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Daftar Meja &amp; Barcode QR
                                </div>
                                <div style="font-size:0.72rem; color:#64748b;">Kelola nomor meja, kapasitas kursi, dan
                                    generate barcode QR untuk dine-in</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="toggleAddTableForm()"
                                style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                + Tambah Meja
                            </button>
                        </div>

                        <!-- Collapsible Form Tambah Meja (Identik dengan Drawer Staf Kasir) -->
                        <div id="formAddTableBox"
                            style="display:none; background:#f8fafc; border:1.5px solid #cbd5e1; border-radius:14px; padding:1.1rem; margin-bottom:1.25rem;">
                            <div
                                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.85rem; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a;">
                                    Tambah Meja Baru
                                </div>
                                <button type="button" onclick="toggleAddTableForm(false)"
                                    style="background:none; border:none; font-size:1.1rem; color:#64748b; cursor:pointer; line-height:1;">&times;</button>
                            </div>

                            <div style="display:grid; grid-template-columns: 2fr 1fr 2fr; gap:10px; margin-bottom:12px;">
                                <div>
                                    <label
                                        style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Nama
                                        Meja / Nomor <span style="color:#ef4444;">*</span></label>
                                    <input type="text" id="newTableName" class="form-input"
                                        style="height:36px; font-size:0.8rem;" placeholder="Contoh: Meja 01, VIP 1">
                                </div>
                                <div>
                                    <label
                                        style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Kapasitas
                                        Kursi</label>
                                    <input type="number" id="newTableSeats" class="form-input"
                                        style="height:36px; font-size:0.8rem;" placeholder="4" min="1" value="4">
                                </div>
                                <div>
                                    <label
                                        style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Area
                                        / Catatan</label>
                                    <input type="text" id="newTableArea" class="form-input"
                                        style="height:36px; font-size:0.8rem;" placeholder="Contoh: Indoor Lt 1 / Outdoor">
                                </div>
                            </div>

                            <div
                                style="display:flex; justify-content:flex-end; gap:8px; border-top:1px solid #e2e8f0; padding-top:10px;">
                                <button type="button" class="btn-outline-bio" onclick="toggleAddTableForm(false)"
                                    style="padding:0.45rem 1rem; font-size:0.76rem; border-radius:8px;">Batal</button>
                                <button type="button" class="btn-submit-green" onclick="confirmAddTable()"
                                    style="width:auto; padding:0.45rem 1.25rem; font-size:0.76rem; border-radius:8px;">
                                    Simpan Meja
                                </button>
                            </div>
                        </div>

                        <!-- Table List Container -->
                        <div id="tableRowsListContainer" style="display:flex; flex-direction:column; gap:8px;">
                            <!-- Rendered by renderTablesSettingsList() -->
                        </div>

                        <!-- Bottom Save Notice & Button (Identik tombol hijau POS) -->
                        <div
                            style="display:flex; justify-content:space-between; align-items:center; margin-top:1.2rem; padding-top:1rem; border-top:1.5px solid #f1f5f9;">
                            <div style="font-size:0.72rem; color:#64748b;">
                                Perubahan meja otomatis tersinkronisasi dengan kasir POS dan Link in Bio.
                            </div>
                            <button type="button" class="btn-submit-green" id="btnSaveTablesSettings"
                                onclick="saveTablesSettings()"
                                style="width:auto; padding:0.48rem 1.4rem; font-size:0.78rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Simpan Perubahan Meja
                            </button>
                        </div>
                    </div>

                    <!-- ══ TAB 6: PEMBAYARAN CASHLESS & BEBAN FEE POS ══ -->
                    <div id="paneSettingPayment" style="display:none;">
                        <!-- Info Alert -->
                        <div
                            style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.2" viewBox="0 0 24 24"
                                style="flex-shrink:0; margin-top:2px;">
                                <rect x="2" y="5" width="20" height="14" rx="2" />
                                <line x1="2" y1="10" x2="22" y2="10" />
                            </svg>
                            <div style="font-size:0.77rem; color:#166534; line-height:1.45;">
                                <strong>Konfigurasi Cashless & Penanggung Fee Platform/Admin</strong><br>
                                Atur metode cashless (Midtrans Snap dinamis otomatis atau QRIS Statis upload toko sendiri)
                                dan tentukan beban biaya fee ditanggung penjual (potong saldo deposit) atau pembeli
                                (ditambahkan ke total tagihan).
                            </div>
                        </div>

                        <!-- Opsi 1: Mode Cashless (QRIS) -->
                        <div
                            style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.2rem; margin-bottom:1.2rem;">
                            <div style="font-weight:800; font-size:0.86rem; color:#0f172a; margin-bottom:0.25rem;">
                                1. Metode Cashless POS (QRIS)
                            </div>
                            <div style="font-size:0.74rem; color:#64748b; margin-bottom:1rem;">
                                Pilih alur penerimaan non-tunai saat kasir memilih pembayaran QRIS / Cashless.
                            </div>

                            <div
                                style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:12px; margin-bottom:1rem;">
                                <!-- Midtrans Snap -->
                                <label
                                    style="border:1.5px solid {{ $posCashlessMode === 'snap_midtrans' ? '#16a34a' : '#e2e8f0' }}; background: {{ $posCashlessMode === 'snap_midtrans' ? '#f0fdf4' : '#ffffff' }}; border-radius:12px; padding:1rem; cursor:pointer; display:flex; gap:10px; align-items:flex-start; transition:all 0.15s ease;"
                                    class="cashless-mode-card" id="cardModeSnap">
                                    <input type="radio" name="set_pos_cashless_mode" value="snap_midtrans" {{ $posCashlessMode === 'snap_midtrans' ? 'checked' : '' }}
                                        onchange="onCashlessModeChange('snap_midtrans')"
                                        style="accent-color:#16a34a; margin-top:3px;">
                                    <div>
                                        <div
                                            style="font-weight:700; font-size:0.82rem; color:#0f172a; display:flex; align-items:center; gap:6px;">
                                            Buyle
                                            QRIS
                                            <span
                                                style="font-size:0.65rem; background:#dcfce7; color:#15803d; padding:2px 6px; border-radius:999px; font-weight:700;">Dinamis</span>
                                        </div>
                                        <div style="font-size:0.72rem; color:#64748b; margin-top:4px; line-height:1.4;">
                                            QRIS terbit otomatis &amp; nominal unik per transaksi. Pembayaran terverifikasi
                                            otomatis via sistem gateway.
                                        </div>
                                    </div>
                                </label>

                                <!-- QRIS Statis Toko -->
                                <label
                                    style="border:1.5px solid {{ $posCashlessMode === 'qris_static' ? '#16a34a' : '#e2e8f0' }}; background: {{ $posCashlessMode === 'qris_static' ? '#f0fdf4' : '#ffffff' }}; border-radius:12px; padding:1rem; cursor:pointer; display:flex; gap:10px; align-items:flex-start; transition:all 0.15s ease;"
                                    class="cashless-mode-card" id="cardModeStatic">
                                    <input type="radio" name="set_pos_cashless_mode" value="qris_static" {{ $posCashlessMode === 'qris_static' ? 'checked' : '' }}
                                        onchange="onCashlessModeChange('qris_static')"
                                        style="accent-color:#16a34a; margin-top:3px;">
                                    <div>
                                        <div
                                            style="font-weight:700; font-size:0.82rem; color:#0f172a; display:flex; align-items:center; gap:6px;">
                                            QRIS Statis Toko
                                            <span
                                                style="font-size:0.65rem; background:#fef3c7; color:#b45309; padding:2px 6px; border-radius:999px; font-weight:700;">Upload
                                                Gambar</span>
                                        </div>
                                        <div style="font-size:0.72rem; color:#64748b; margin-top:4px; line-height:1.4;">
                                            Menampilkan gambar QRIS toko Anda langsung di layar kasir/pelanggan. Uang
                                            langsung masuk rekening Anda.
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Panel Upload QRIS Statis -->
                            <div id="staticQrisUploadPanel"
                                style="display: {{ $posCashlessMode === 'qris_static' ? 'block' : 'none' }}; border-top:1px dashed #e2e8f0; padding-top:1rem; margin-top:0.5rem;">
                                <div style="font-weight:700; font-size:0.78rem; color:#334155; margin-bottom:0.6rem;">
                                    File Gambar QRIS Statis Toko (Auto WebP)
                                </div>

                                <div style="display:flex; flex-wrap:wrap; gap:16px; align-items:center;">
                                    <!-- Preview Box -->
                                    <div id="qrisPreviewContainer"
                                        style="width:130px; height:130px; border:2px dashed #cbd5e1; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#f8fafc; overflow:hidden; flex-shrink:0;">
                                        @if($posQrisUrl)
                                            <img src="{{ $posQrisUrl }}" id="qrisPreviewImg"
                                                style="width:100%; height:100%; object-fit:contain; background:#ffffff;">
                                        @else
                                            <div id="qrisPreviewPlaceholder"
                                                style="text-align:center; padding:8px; color:#94a3b8; font-size:0.68rem;">
                                                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8"
                                                    viewBox="0 0 24 24" style="margin:0 auto 4px auto; display:block;">
                                                    <rect x="3" y="3" width="7" height="7"></rect>
                                                    <rect x="14" y="3" width="7" height="7"></rect>
                                                    <rect x="14" y="14" width="7" height="7"></rect>
                                                    <rect x="3" y="14" width="7" height="7"></rect>
                                                </svg>
                                                Belum ada QRIS
                                            </div>
                                        @endif
                                    </div>

                                    <div style="flex:1; min-width:200px;">
                                        <input type="file" id="posQrisFileInput" accept="image/*" style="display:none;"
                                            onchange="handleQrisFileSelect(event)">
                                        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:8px;">
                                            <button type="button" class="btn-submit-green"
                                                onclick="document.getElementById('posQrisFileInput').click()"
                                                style="width:auto; padding:0.45rem 1rem; font-size:0.75rem; border-radius:8px;">
                                                <svg width="14" height="14" fill="none" stroke="currentColor"
                                                    stroke-width="2.2" viewBox="0 0 24 24">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="17 8 12 3 7 8" />
                                                    <line x1="12" y1="3" x2="12" y2="15" />
                                                </svg>
                                                Unggah Gambar QRIS
                                            </button>
                                            <button type="button" class="btn-outline-bio" id="btnRemoveQris"
                                                onclick="removeQrisImage()"
                                                style="padding:0.45rem 0.9rem; font-size:0.75rem; border-radius:8px; color:#ef4444; border-color:#fca5a5; display: {{ $posQrisUrl ? 'inline-flex' : 'none' }};">
                                                Hapus QRIS
                                            </button>
                                        </div>
                                        <div style="font-size:0.7rem; color:#64748b; line-height:1.45;">
                                            Format didukung: JPG, PNG, WEBP (Maks 5MB). File yang diunggah <strong>otomatis
                                                dikonversi ke WebP</strong> dan dikompresi agar ringan, jernih, dan cepat
                                            tampil saat scan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Opsi 2: Penanggung Biaya Admin & Platform Fee -->
                        <div
                            style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.2rem; margin-bottom:1.2rem;">
                            <div style="font-weight:800; font-size:0.86rem; color:#0f172a; margin-bottom:0.25rem;">
                                2. Penanggung Biaya Platform &amp; Admin POS
                            </div>
                            <div style="font-size:0.74rem; color:#64748b; margin-bottom:1rem;">
                                Tarif saat ini: Platform Fee {{ $platformFeeRate }}% &amp; Admin Fee {{ $adminFeeRate }}%.
                                Tentukan pihak yang membayar biaya transaksi ini.
                            </div>

                            <div
                                style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:12px; margin-bottom:0.5rem;">
                                <!-- Ditanggung Penjual -->
                                <label
                                    style="border:1.5px solid {{ $posFeePayer === 'seller' ? '#16a34a' : '#e2e8f0' }}; background: {{ $posFeePayer === 'seller' ? '#f0fdf4' : '#ffffff' }}; border-radius:12px; padding:1rem; cursor:pointer; display:flex; gap:10px; align-items:flex-start; transition:all 0.15s ease;"
                                    class="fee-payer-card" id="cardFeePayerSeller">
                                    <input type="radio" name="set_pos_fee_payer" value="seller" {{ $posFeePayer === 'seller' ? 'checked' : '' }} onchange="onFeePayerChange('seller')"
                                        style="accent-color:#16a34a; margin-top:3px;">
                                    <div>
                                        <div
                                            style="font-weight:700; font-size:0.82rem; color:#0f172a; display:flex; align-items:center; gap:6px;">
                                            Ditanggung Penjual
                                            <span
                                                style="font-size:0.65rem; background:#e0f2fe; color:#0369a1; padding:2px 6px; border-radius:999px; font-weight:700;">Potong
                                                Saldo Deposit</span>
                                        </div>
                                        <div style="font-size:0.72rem; color:#64748b; margin-top:4px; line-height:1.4;">
                                            Pembeli hanya membayar murni total belanja (+ pajak/layanan toko). Fee platform
                                            dipotong otomatis dari <strong>Saldo Deposit POS</strong> penjual.
                                        </div>
                                        <div
                                            style="font-size:0.68rem; color:#b45309; margin-top:6px; background:#fffbeb; padding:4px 8px; border-radius:6px; border:1px solid #fef3c7; line-height:1.35;">
                                            ⚠️ Jika deposit tidak mencukupi, transaksi POS tidak dapat diproses.
                                        </div>
                                    </div>
                                </label>

                                <!-- Ditanggung Pembeli -->
                                <label
                                    style="border:1.5px solid {{ $posFeePayer === 'buyer' ? '#16a34a' : '#e2e8f0' }}; background: {{ $posFeePayer === 'buyer' ? '#f0fdf4' : '#ffffff' }}; border-radius:12px; padding:1rem; cursor:pointer; display:flex; gap:10px; align-items:flex-start; transition:all 0.15s ease;"
                                    class="fee-payer-card" id="cardFeePayerBuyer">
                                    <input type="radio" name="set_pos_fee_payer" value="buyer" {{ $posFeePayer === 'buyer' ? 'checked' : '' }} onchange="onFeePayerChange('buyer')"
                                        style="accent-color:#16a34a; margin-top:3px;">
                                    <div>
                                        <div
                                            style="font-weight:700; font-size:0.82rem; color:#0f172a; display:flex; align-items:center; gap:6px;">
                                            Ditanggung Pembeli
                                            <span
                                                style="font-size:0.65rem; background:#dcfce7; color:#15803d; padding:2px 6px; border-radius:999px; font-weight:700;">Tambah
                                                ke Tagihan</span>
                                        </div>
                                        <div style="font-size:0.72rem; color:#64748b; margin-top:4px; line-height:1.4;">
                                            Sistem kasir secara otomatis menambahkan nominal fee platform &amp; admin ke
                                            total tagihan yang dibayarkan oleh pembeli. Saldo deposit penjual tidak
                                            dipotong.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Bottom Save Action -->
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-top:0.5rem;">
                            <div style="font-size:0.72rem; color:#64748b;">
                                Pengaturan akan langsung aktif di layar kasir POS.
                            </div>
                            <button type="button" class="btn-submit-green" id="btnSavePaymentSettings"
                                onclick="savePaymentSettings()"
                                style="width:auto; padding:0.48rem 1.4rem; font-size:0.78rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Simpan Pengaturan Pembayaran
                            </button>
                        </div>
                    </div>

                </div> <!-- end posSettingsRealContent -->

            </div>

            <!-- Footer: Penutup Modal -->
            <div class="pos-modal-foot"
                style="display:flex; justify-content:space-between; align-items:center; padding:0.8rem 1.4rem; background:#f8fafc; border-top:1.5px solid #f1f5f9;">
                <div style="font-size:0.7rem; color:#94a3b8;">
                    Kustomisasi tersimpan &amp; terhubung langsung ke modul POS.
                </div>
                <button type="button" class="btn-outline-bio" onclick="closeModal('posSettingsModal')"
                    style="padding:0.45rem 1.2rem; font-size:0.78rem; border-radius:999px;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- CUSTOM OVERLAY MODAL 3: History & Shift Closing Modal -->
    <!-- CUSTOM OVERLAY MODAL: Product Variant & Notes Modal -->
    <div class="pos-modal-overlay" id="posVariantModal" style="z-index: 100010;">
        <div class="pos-modal-card"
            style="max-width: 480px; width: 95vw; border-radius: 20px; overflow: hidden; background: #ffffff; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="pos-modal-head"
                style="padding: 1.1rem 1.25rem; border-bottom: 1.5px solid #f1f5f9; background: #ffffff;">
                <div style="display: flex; align-items: center; gap: 12px; width: calc(100% - 32px);">
                    <div id="variantModalImgWrap"
                        style="width: 48px; height: 48px; border-radius: 12px; overflow: hidden; background: #f8fafc; border: 1px solid #e2e8f0; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                        <img id="variantModalImg" src=""
                            style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        <svg id="variantModalImgFallback" width="22" height="22" fill="none" stroke="#94a3b8"
                            stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                    </div>
                    <div style="min-width: 0;">
                        <div id="variantModalTitle"
                            style="font-weight: 800; font-size: 0.95rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        </div>
                        <div id="variantModalBasePrice" style="font-size: 0.78rem; color: #16a34a; font-weight: 700;"></div>
                    </div>
                </div>
                <button type="button" class="pos-modal-close" onclick="closeModal('posVariantModal')">&times;</button>
            </div>

            <div class="pos-modal-body" style="padding: 1.25rem; max-height: 60vh; overflow-y: auto;">
                <div id="variantModalOptionsList" style="display: flex; flex-direction: column; gap: 1.1rem;"></div>

                <div style="margin-top: 1.2rem; border-top: 1px dashed #e2e8f0; padding-top: 1rem;">
                    <label
                        style="font-weight: 700; font-size: 0.78rem; color: #334155; display: flex; align-items: center; gap: 6px; margin-bottom: 0.4rem;">
                        <svg width="14" height="14" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                        </svg>
                        Catatan Khusus Produk
                        <span style="font-weight: 400; color: #94a3b8; font-size: 0.7rem;">(Opsional)</span>
                    </label>
                    <textarea id="variantModalNotes" rows="2"
                        placeholder="Cth: es sedikit, pisah sambal, tidak pedas, kuah banyak..."
                        style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 8px 12px; font-size: 0.78rem; outline: none; transition: border-color 0.15s; resize: none; background: #f8fafc; color: #0f172a; box-sizing: border-box;"></textarea>
                </div>

                <div
                    style="margin-top: 1.1rem; display: flex; align-items: center; justify-content: space-between; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 10px 14px;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #334155;">Jumlah</div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button type="button" onclick="adjustVariantModalQty(-1)"
                            style="width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; font-size: 1rem; font-weight: 800; color: #0f172a; cursor: pointer; display: flex; align-items: center; justify-content: center;">-</button>
                        <span id="variantModalQty"
                            style="font-weight: 800; font-size: 0.92rem; color: #0f172a; min-width: 24px; text-align: center;">1</span>
                        <button type="button" onclick="adjustVariantModalQty(1)"
                            style="width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; font-size: 1rem; font-weight: 800; color: #0f172a; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
                    </div>
                </div>
            </div>

            <div class="pos-modal-foot"
                style="padding: 1rem 1.25rem; border-top: 1.5px solid #f1f5f9; background: #ffffff; display: flex; gap: 10px;">
                <button type="button" class="btn-outline-bio" onclick="closeModal('posVariantModal')"
                    style="flex: 1; padding: 0.65rem; border-radius: 999px; font-size: 0.8rem;">Batal</button>
                <button type="button" class="btn-submit-green" id="btnVariantModalAdd" onclick="confirmVariantModalAdd()"
                    style="flex: 2; padding: 0.65rem; border-radius: 999px; font-size: 0.82rem; font-weight: 700;">Tambah ke
                    Pesanan</button>
            </div>
        </div>
    </div>

    <!-- CUSTOM OVERLAY MODAL: Static QRIS Display Modal -->
    <div class="pos-modal-overlay" id="posStaticQrisModal" style="z-index: 100020;">
        <div class="pos-modal-card"
            style="max-width: 440px; width: 95vw; border-radius: 20px; overflow: hidden; background: #ffffff; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="pos-modal-head"
                style="padding: 1.1rem 1.4rem; border-bottom: 1.5px solid #f1f5f9; background: #ffffff; display: flex; justify-content: space-between; align-items: center;">
                <div
                    style="font-weight: 800; font-size: 0.95rem; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Scan QRIS Toko
                </div>
                <button type="button" class="pos-modal-close" onclick="closeModal('posStaticQrisModal')">&times;</button>
            </div>

            <div class="pos-modal-body" style="padding: 1.5rem 1.25rem; background: #f8fafc;">
                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600;">Total Tagihan Pembeli</div>
                <div id="staticQrisAmountText"
                    style="font-size: 1.7rem; font-weight: 900; color: #0f172a; letter-spacing: -0.02em; margin: 4px 0 1rem 0;">
                    Rp 0</div>

                <div
                    style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 16px; padding: 14px; max-width: 280px; margin: 0 auto 1.2rem auto; box-shadow: 0 4px 14px rgba(0,0,0,0.05);">
                    <img id="staticQrisDisplayImg" src="" alt="QRIS Toko"
                        style="width: 100%; height: auto; border-radius: 8px; display: block; margin: 0 auto;">
                    <div id="staticQrisNoImageWarning"
                        style="display: none; padding: 2rem 1rem; color: #ef4444; font-size: 0.8rem; font-weight: 700;">
                        Gambar QRIS Toko belum diunggah.<br><span
                            style="font-weight: 400; color: #64748b; font-size: 0.72rem;">Silakan upload gambar QRIS toko di
                            Pengaturan POS.</span>
                    </div>
                    <div style="margin-top: 8px; font-size: 0.72rem; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em;"
                        id="staticQrisStoreName"></div>
                </div>

                <div
                    style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 10px 12px; font-size: 0.72rem; color: #1e40af; line-height: 1.4; text-align: left; display: flex; gap: 8px; align-items: flex-start;">
                    <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24"
                        style="flex-shrink:0; margin-top:1px;">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="16" x2="12" y2="12" />
                        <line x1="12" y1="8" x2="12.01" y2="8" />
                    </svg>
                    <div>Minta pelanggan memindai QRIS di atas dengan BCA, OVO, GoPay, ShopeePay, Dana, dll. Pastikan dana
                        sudah masuk sebelum menekan tombol selesai.</div>
                </div>
            </div>

            <div class="pos-modal-foot"
                style="padding: 1rem 1.25rem; border-top: 1.5px solid #f1f5f9; background: #ffffff; display: flex; gap: 10px;">
                <button type="button" class="btn-outline-bio" onclick="closeModal('posStaticQrisModal')"
                    style="flex: 1; padding: 0.65rem; border-radius: 999px; font-size: 0.78rem;">Tutup</button>
                <button type="button" class="btn-submit-green" id="btnStaticQrisConfirmDone"
                    onclick="confirmStaticQrisDone()"
                    style="flex: 2; padding: 0.65rem; border-radius: 999px; font-size: 0.8rem; font-weight: 700;">
                    ✓ Pembayaran Diterima (Selesai)
                </button>
            </div>
        </div>
    </div>

    <div class="pos-modal-overlay" id="posHistoryModal">
        <div class="pos-modal-card" style="max-width: 860px; width: 95vw;">
            <div class="pos-modal-head">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div
                        style="width:30px; height:30px; border-radius:50%; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#166534;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div>
                        <h5 style="margin:0; font-size:1rem; font-weight:800; color:#0f172a;">Rekap Shift &amp; Riwayat
                            Kasir POS</h5>
                        <div style="font-size:0.72rem; color:#64748b; margin-top:1px;">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                </div>
                <button type="button" class="pos-modal-close" onclick="closeModal('posHistoryModal')">&times;</button>
            </div>

            <!-- Tab Navigation Bar -->
            <div
                style="display:flex; align-items:center; border-bottom:1.5px solid #e2e8f0; background:#f8fafc; padding:0 1.25rem; overflow-x:auto;">
                <button type="button" class="history-tab-btn active" id="tabBtnShift" onclick="switchHistoryTab('shift')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <line x1="3" y1="9" x2="21" y2="9" />
                        <line x1="9" y1="21" x2="9" y2="9" />
                    </svg>
                    Rekap Shift &amp; Closing
                </button>
                <button type="button" class="history-tab-btn" id="tabBtnOrders" onclick="switchHistoryTab('orders')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                    </svg>
                    Riwayat Transaksi ({{ count($todayOrders) }})
                </button>
                <button type="button" class="history-tab-btn" id="tabBtnMutations" onclick="switchHistoryTab('mutations')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                    Mutasi Deposit ({{ count($todayMutations) }})
                </button>
            </div>

            <div class="pos-modal-body p-0">
                <!-- TAB 1: REKAP SHIFT & CLOSING KASIR -->
                <div id="paneHistoryShift" style="padding:1.25rem;">
                    <!-- 4 Summary Metric Cards -->
                    <div
                        style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-bottom:1.25rem;">
                        <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:12px 14px;">
                            <div
                                style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.04em;">
                                Total Omset Kotor</div>
                            <div style="font-size:1.25rem; font-weight:900; color:#0f172a; margin-top:2px;">Rp
                                {{ number_format($todayGrossSales, 0, ',', '.') }}
                            </div>
                            <div style="font-size:0.72rem; color:#64748b; margin-top:2px;">{{ count($todayOrders) }} pesanan
                                selesai</div>
                        </div>

                        <div style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:14px; padding:12px 14px;">
                            <div
                                style="font-size:0.7rem; font-weight:700; color:#166534; text-transform:uppercase; letter-spacing:0.04em;">
                                Uang Tunai di Laci (Cash)</div>
                            <div style="font-size:1.25rem; font-weight:900; color:#166534; margin-top:2px;">Rp
                                {{ number_format($todayCashInDrawer, 0, ',', '.') }}
                            </div>
                            <div style="font-size:0.72rem; color:#166534; margin-top:2px;">Fisik wajib di laci kasir</div>
                        </div>

                        <div style="background:#f5f3ff; border:1.5px solid #ddd6fe; border-radius:14px; padding:12px 14px;">
                            <div
                                style="font-size:0.7rem; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.04em;">
                                Pembayaran Digital</div>
                            <div style="font-size:1.25rem; font-weight:900; color:#6d28d9; margin-top:2px;">Rp
                                {{ number_format($todayDigitalSales, 0, ',', '.') }}
                            </div>
                            <div style="font-size:0.72rem; color:#6d28d9; margin-top:2px;">QRIS &amp; Transfer Bank</div>
                        </div>

                        <div style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:14px; padding:12px 14px;">
                            <div
                                style="font-size:0.7rem; font-weight:700; color:#c2410c; text-transform:uppercase; letter-spacing:0.04em;">
                                Fee Terpotong Deposit</div>
                            <div style="font-size:1.25rem; font-weight:900; color:#c2410c; margin-top:2px;">Rp
                                {{ number_format($todayFeeDeducted, 0, ',', '.') }}
                            </div>
                            <div style="font-size:0.72rem; color:#c2410c; margin-top:2px;">Platform fee &amp; admin fee
                            </div>
                        </div>
                    </div>

                    <!-- Neraca Ringkasan Shift -->
                    <div
                        style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:14px; padding:14px 16px; margin-bottom:1.25rem;">
                        <div
                            style="font-size:0.8rem; font-weight:800; color:#0f172a; margin-bottom:10px; display:flex; align-items:center; justify-content:space-between;">
                            <span>Neraca Keuangan Shift Hari Ini</span>
                            <span class="badge"
                                style="background:#f1f5f9; color:#475569; font-size:0.72rem; border-radius:6px; padding:3px 8px;">Audit
                                POS</span>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; font-size:0.82rem;">
                            <div
                                style="display:flex; justify-content:space-between; padding-bottom:6px; border-bottom:1px dashed #f1f5f9;">
                                <span style="color:#64748b;">Kasir Bertugas:</span>
                                <strong
                                    style="color:#0f172a;">{{ $currentCashier ? $currentCashier->name : auth()->user()->name }}
                                    @if(!empty($currentCashier?->shift_hours))<span
                                    style="font-size:0.75rem; color:#0369a1; font-weight:600;">({{ $currentCashier->shift_hours }})</span>@endif</strong>
                            </div>
                            <div
                                style="display:flex; justify-content:space-between; padding-bottom:6px; border-bottom:1px dashed #f1f5f9;">
                                <span style="color:#64748b;">Sisa Saldo Deposit:</span>
                                <strong style="color:#166534;">Rp {{ number_format($posBalance, 0, ',', '.') }}</strong>
                            </div>
                            <div
                                style="display:flex; justify-content:space-between; padding-bottom:6px; border-bottom:1px dashed #f1f5f9;">
                                <span style="color:#64748b;">Total Diskon Diberikan:</span>
                                <strong style="color:#dc2626;">-Rp
                                    {{ number_format($todayOrders->sum('discount'), 0, ',', '.') }}</strong>
                            </div>
                            <div
                                style="display:flex; justify-content:space-between; padding-bottom:6px; border-bottom:1px dashed #f1f5f9;">
                                <span style="color:#64748b;">Status Rekonsiliasi:</span>
                                <strong style="color:#166534;">Seimbang (Cocok)</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Shift Action Buttons -->
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <button type="button" class="btn-submit-green"
                            style="flex:1; padding:0.75rem 1.25rem; font-size:0.85rem; border-radius:999px !important; display:inline-flex; align-items:center; justify-content:center; gap:6px;"
                            onclick="printClosingReceipt()">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                                viewBox="0 0 24 24">
                                <polyline points="6 9 6 2 18 2 18 9" />
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                <rect x="6" y="14" width="12" height="8" />
                            </svg>
                            Cetak Struk Rekap Closing Kasir
                        </button>
                        <a href="{{ route('creator.sales.report') }}?tab=pos" class="btn-outline-bio"
                            style="padding:0.75rem 1.25rem; font-size:0.85rem; border-radius:999px !important; display:inline-flex; align-items:center; gap:6px; text-decoration:none;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                                viewBox="0 0 24 24">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                <polyline points="15 3 21 3 21 9" />
                                <line x1="10" y1="14" x2="21" y2="3" />
                            </svg>
                            Buka Laporan POS Lengkap
                        </a>
                    </div>
                </div>

                <!-- TAB 2: RIWAYAT TRANSAKSI HARI INI -->
                <div id="paneHistoryOrders" style="display:none;">

                    <!-- Screen 2 Mobile Transactions Cards List (100% Sesuai Screen 2 Mockup) -->
                    <div class="pos-mobile-transactions-list" style="padding:1rem;">
                        <!-- Date Filter Pill (Screen 2) -->
                        <div
                            style="display:flex; align-items:center; justify-content:space-between; background:#ffffff; border:1.5px solid #e2e8f0; border-radius:18px; padding:0.75rem 1rem; margin-bottom:1rem; box-shadow:0 2px 8px rgba(0,0,0,0.02);">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div
                                    style="width:34px; height:34px; border-radius:50%; background:#f8fafc; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; color:#0f172a;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size:0.86rem; font-weight:800; color:#0f172a;">Hari Ini (All Time)
                                    </div>
                                    <div style="font-size:0.7rem; color:#64748b;">{{ now()->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                            </div>
                            <span
                                style="font-size:0.75rem; font-weight:700; color:#1eb349; background:#f0fdf4; border:1px solid #bbf7d0; padding:4px 12px; border-radius:999px;">
                                Aktif
                            </span>
                        </div>

                        <!-- Transactions List Items -->
                        @forelse($todayOrders as $idx => $tOrd)
                            @php
                                $tAddr = is_array($tOrd->shipping_address) ? $tOrd->shipping_address : (json_decode($tOrd->shipping_address ?? '', true) ?? []);
                                $tPayMethodRaw = $tAddr['payment_method'] ?? ($tOrd->payment?->method ?? ($tOrd->payment_method ?? 'cash'));
                                $tPayMethod = is_object($tPayMethodRaw) ? ($tPayMethodRaw->value ?? $tPayMethodRaw->name ?? 'cash') : ($tPayMethodRaw ?? 'cash');
                                $isCash = strtolower($tPayMethod) === 'cash';
                            @endphp
                            <div class="pos-trx-card" onclick="reprintPastReceipt({{ $tOrd->id }})">
                                <div class="pos-trx-left">
                                    <div style="font-size:0.68rem; color:#ea580c; font-weight:800; text-transform:uppercase;">
                                        Sale #{{ $loop->iteration }}</div>
                                    <div class="pos-trx-num">{{ $isCash ? 'Cash Sale' : 'QRIS / Digital' }}</div>
                                    <div class="pos-trx-meta">
                                        <span>{{ $tOrd->created_at->format('d M Y • H:i') }}</span>
                                    </div>
                                </div>
                                <div class="pos-trx-right">
                                    <div class="pos-trx-amount">Rp {{ number_format($tOrd->total, 0, ',', '.') }}</div>
                                    <span class="pos-badge-paid">Paid</span>
                                </div>
                            </div>
                        @empty
                            <div
                                style="text-align:center; padding:3rem 1.5rem; background:#ffffff; border-radius:20px; border:1.5px dashed #cbd5e1; margin-top:0.5rem;">
                                <svg width="36" height="36" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24"
                                    style="margin:0 auto 0.5rem;">
                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                    <line x1="2" y1="10" x2="22" y2="10" />
                                </svg>
                                <div style="font-size:0.86rem; font-weight:700; color:#0f172a;">Belum Ada Transaksi</div>
                                <div style="font-size:0.74rem; color:#64748b; margin-top:2px;">Transaksi penjualan hari ini akan
                                    tampil di sini.</div>
                            </div>
                        @endforelse


                    </div>

                    <div class="pos-desktop-only-table">
                        <div class="pos-table-wrap">
                            <table class="pos-table">
                                <thead>
                                    <tr>
                                        <th style="padding-left:1.5rem;">No. Order / Waktu</th>
                                        <th>Pelanggan</th>
                                        <th>Metode Bayar</th>
                                        <th>Total Bayar</th>
                                        <th>Status</th>
                                        <th style="text-align:right; padding-right:1.5rem;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todayOrders as $tOrd)
                                        @php
                                            $tAddr = is_array($tOrd->shipping_address) ? $tOrd->shipping_address : (json_decode($tOrd->shipping_address ?? '', true) ?? []);
                                            $tPayMethod = $tAddr['payment_method'] ?? ($tOrd->payment?->method ?? 'cash');
                                            $tFee = (float) ($tOrd->platform_fee ?? 0) + (float) ($tOrd->admin_fee ?? 0);
                                            $tCashierName = $tOrd->cashier_name ?? ($tAddr['cashier_name'] ?? null);
                                        @endphp
                                        <tr>
                                            <td style="padding-left:1.5rem;">
                                                <strong style="color:#0f172a;">#{{ $tOrd->order_number }}</strong>
                                                <div style="font-size:0.73rem; color:#64748b;">
                                                    {{ $tOrd->created_at->format('H:i') }} WIB
                                                    @if(!empty($tCashierName))
                                                        • <span style="color:#0369a1; font-weight:700;">Kasir:
                                                            {{ $tCashierName }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div style="font-weight:700; color:#0f172a;">{{ $tAddr['name'] ?? 'Pelanggan' }}
                                                </div>
                                                @if(!empty($tOrd->notes))
                                                    <div style="font-size:0.73rem; color:#64748b;">{{ $tOrd->notes }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge"
                                                    style="background:{{ strtolower($tPayMethod) === 'cash' ? '#f0fdf4' : '#f5f3ff' }}; color:{{ strtolower($tPayMethod) === 'cash' ? '#166534' : '#6d28d9' }}; border:1px solid {{ strtolower($tPayMethod) === 'cash' ? '#bbf7d0' : '#ddd6fe' }}; text-transform:uppercase; font-weight:700; padding:4px 8px; border-radius:6px; font-size:0.7rem;">
                                                    {{ $tPayMethod }}
                                                </span>
                                            </td>
                                            <td style="font-weight:800; color:#0f172a;">
                                                Rp {{ number_format($tOrd->total, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span class="badge"
                                                    style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-weight:700; padding:4px 8px; border-radius:6px; font-size:0.7rem;">Berhasil</span>
                                            </td>
                                            <td style="text-align:right; padding-right:1.5rem;">
                                                <button type="button" class="btn-outline-bio"
                                                    style="padding:4px 10px; font-size:0.75rem;"
                                                    onclick="reprintPastReceipt({{ json_encode($tOrd) }})">
                                                    Cetak Struk
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                style="text-align:center; padding:3rem 1rem; color:#94a3b8; font-size:0.85rem;">
                                                Belum ada transaksi POS hari ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 3: MUTASI SALDO DEPOSIT HARI INI -->
                    <div id="paneHistoryMutations" style="display:none;">
                        <div class="pos-table-wrap">
                            <table class="pos-table">
                                <thead>
                                    <tr>
                                        <th style="padding-left:1.5rem;">Waktu</th>
                                        <th>Aktivitas Mutasi</th>
                                        <th>No. Referensi</th>
                                        <th>Nominal</th>
                                        <th>Saldo Sebelum</th>
                                        <th style="text-align:right; padding-right:1.5rem;">Saldo Sesudah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todayMutations as $mut)
                                        <tr>
                                            <td style="padding-left:1.5rem; font-size:0.75rem; color:#64748b;">
                                                {{ $mut->created_at->format('H:i:s') }} WIB
                                            </td>
                                            <td>
                                                @if($mut->type === 'topup')
                                                    <span class="badge"
                                                        style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-weight:700; padding:4px 8px; border-radius:6px; font-size:0.7rem;">Top
                                                        Up Saldo</span>
                                                @else
                                                    <span class="badge"
                                                        style="background:#fff1f2; color:#be123c; border:1px solid #fecdd3; font-weight:700; padding:4px 8px; border-radius:6px; font-size:0.7rem;">Fee
                                                        Transaksi Cash</span>
                                                @endif
                                                <div style="font-size:0.72rem; color:#64748b; margin-top:2px;">{{ $mut->notes }}
                                                </div>
                                            </td>
                                            <td style="font-size:0.75rem; font-weight:600; color:#0f172a;">
                                                {{ $mut->reference_number ?: '-' }}
                                            </td>
                                            <td style="font-weight:800; color:{{ $mut->amount >= 0 ? '#166534' : '#dc2626' }};">
                                                {{ $mut->amount >= 0 ? '+' : '' }}Rp
                                                {{ number_format($mut->amount, 0, ',', '.') }}
                                            </td>
                                            <td style="font-size:0.75rem; color:#64748b;">
                                                Rp {{ number_format($mut->balance_before, 0, ',', '.') }}
                                            </td>
                                            <td style="text-align:right; padding-right:1.5rem; font-weight:800; color:#0f172a;">
                                                Rp {{ number_format($mut->balance_after, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                style="text-align:center; padding:3rem 1rem; color:#94a3b8; font-size:0.85rem;">
                                                Belum ada mutasi saldo deposit POS hari ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Template Area for Printing Struk Closing Shift Kasir -->
        <div id="printableClosingReceiptArea" style="display:none;">
            <div
                style="font-family: 'Courier New', Courier, monospace; font-size: 11px; color: #000000; line-height: 1.35; padding: 10px; width: 72mm; margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 8px;">
                    <div style="font-size: 14px; font-weight: bold; text-transform: uppercase;">{{ $storeName }}</div>
                    <div style="font-size: 10px; color: #333;">BUYLE.ID DIGITAL POS</div>
                    <div style="border-bottom: 1px dashed #000; margin: 6px 0;"></div>
                    <div style="font-size: 12px; font-weight: bold;">REKAP CLOSING SHIFT KASIR</div>
                    <div style="font-size: 10px;">{{ now()->format('d/m/Y H:i:s') }}</div>
                </div>

                <table style="width: 100%; font-size: 10px; margin-bottom: 6px;">
                    <tr>
                        <td>Kasir:</td>
                        <td style="text-align: right; font-weight: bold;">{{ auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal:</td>
                        <td style="text-align: right;">{{ now()->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Total Transaksi:</td>
                        <td style="text-align: right; font-weight: bold;">{{ count($todayOrders) }} Trx</td>
                    </tr>
                </table>

                <div style="border-bottom: 1px dashed #000; margin: 6px 0;"></div>

                <table style="width: 100%; font-size: 10px; margin-bottom: 6px;">
                    <tr>
                        <td>Omset Penjualan Kotor:</td>
                        <td style="text-align: right; font-weight: bold;">Rp
                            {{ number_format($todayGrossSales, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td>Total Diskon:</td>
                        <td style="text-align: right;">-Rp {{ number_format($todayOrders->sum('discount'), 0, ',', '.') }}
                        </td>
                    </tr>
                </table>

                <div style="border-bottom: 1px dashed #000; margin: 6px 0;"></div>
                <div style="font-weight: bold; font-size: 10px; margin-bottom: 4px;">RINCIAN PENERIMAAN:</div>

                <table style="width: 100%; font-size: 10px; margin-bottom: 6px;">
                    <tr>
                        <td>TUNAI FISIK (Di Laci):</td>
                        <td style="text-align: right; font-weight: bold;">Rp
                            {{ number_format($todayCashInDrawer, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td>DIGITAL (QRIS/Transfer):</td>
                        <td style="text-align: right; font-weight: bold;">Rp
                            {{ number_format($todayDigitalSales, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>

                <div style="border-bottom: 1px dashed #000; margin: 6px 0;"></div>
                <div style="font-weight: bold; font-size: 10px; margin-bottom: 4px;">AUDIT DEPOSIT KASIR:</div>

                <table style="width: 100%; font-size: 10px; margin-bottom: 6px;">
                    <tr>
                        <td>Fee Platform/Admin Terpotong:</td>
                        <td style="text-align: right; font-weight: bold;">Rp
                            {{ number_format($todayFeeDeducted, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td>Sisa Saldo Deposit Aktif:</td>
                        <td style="text-align: right; font-weight: bold;">Rp {{ number_format($posBalance, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>

                <div style="border-bottom: 1px dashed #000; margin: 10px 0;"></div>

                <div
                    style="display: flex; justify-content: space-between; font-size: 9px; margin-top: 15px; text-align: center;">
                    <div style="width: 45%;">
                        Kasir
                        <div style="height: 35px;"></div>
                        ({{ auth()->user()->name }})
                    </div>
                    <div style="width: 45%;">
                        Supervisor / Owner
                        <div style="height: 35px;"></div>
                        (....................)
                    </div>
                </div>

                <div style="text-align: center; font-size: 9px; color: #444; margin-top: 15px;">
                    Dicetak otomatis oleh buyle.id POS
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
            // State POS
            let cart = [];
            let discountType = 'nominal';
            let platformFeeRate = {{ $platformFeeRate }};
            let adminFeeRate = {{ $adminFeeRate }};
            let currentPosBalance = {{ (float) $posBalance }};
            let currentCompletedOrder = null;
            window._posTodayOrdersData = {!! json_encode($todayOrders) !!};

            // Fees, Discounts & Tables Data (from POS Settings)
            let posFeesData = {!! json_encode($profile ? $profile?->getPosFees() : []) !!};
            let posDiscountsData = {!! json_encode($profile ? $profile?->getPosDiscounts() : []) !!};
            let posTablesData = {!! json_encode($profile ? $profile?->getPosTables() : []) !!};
            const posBioBaseUrl = {!! json_encode(!empty($profile?->custom_domain) ? ('https://' . rtrim($profile?->custom_domain, '/')) : url('/' . ($profile?->store_slug ?: ($seller->username ?? '')))) !!};
            const posStoreDisplayName = {!! json_encode($profile?->store_name ?? $storeName ?? 'Toko') !!};

            // Konfigurasi Cashless, Fee Payer, & Saldo Deposit POS
            let posCashlessMode = {!! json_encode($posCashlessMode ?? 'snap_midtrans') !!};
            let posQrisUrl = {!! json_encode($posQrisUrl ?? null) !!};
            let posFeePayer = {!! json_encode($posFeePayer ?? 'seller') !!};
            let posBalance = {{ (float) ($posBalance ?? 0) }};

            // Master Katalog Produk Lengkap dengan Variasi
            window.posCatalog = {
                @foreach($products as $prod)
                                        @php
                                            $pPrice = (float) ($prod->sale_price ?: $prod->price);
                                            $pImg = $prod->image ? (str_starts_with($prod->image, 'http') ? $prod->image : asset('storage/' . $prod->image)) : '';
                                            $pHasVariants = $prod->variantOptions && $prod->variantOptions->count() > 0;
                                            $pVariants = [];
                                            if ($pHasVariants) {
                                                foreach ($prod->variantOptions as $opt) {
                                                    $vals = [];
                                                    foreach ($opt->values as $val) {
                                                        $vals[] = [
                                                            'id' => $val->id,
                                                            'value' => $val->value,
                                                            'price_adjustment' => (float) ($val->price_adjustment ?? 0),
                                                            'final_price' => (float) $val->finalPrice(),
                                                            'stock' => $val->stock,
                                                        ];
                                                    }
                                                    $pVariants[] = [
                                                        'id' => $opt->id,
                                                        'name' => $opt->name,
                                                        'values' => $vals,
                                                    ];
                                                }
                                            }
                                        @endphp
                                        {{ $prod->id }}: {
                        id: {{ $prod->id }},
                        name: {!! json_encode($prod->name) !!},
                        price: {{ $pPrice }},
                        image: {!! json_encode($pImg) !!},
                        hasVariants: {{ $pHasVariants ? 'true' : 'false' }},
                        variants: {!! json_encode($pVariants) !!}
                    },
                @endforeach
                        };

            window.posAllProducts = Object.values(window.posCatalog);

            // Custom Modal Helpers (Vanilla JS - Independent from Bootstrap)
            function openModal(modalId) {
                if (modalId === 'posHistoryModal' && window.innerWidth <= 991) {
                    switchPosScreen('transactions');
                    return;
                }
                if (modalId === 'posLogoModal') {
                    openPosSettingsModal('struk');
                    return;
                }
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('show');
                }
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('show');
                }
                if (modalId === 'posReceiptModal') {
                    stopOrderStatusPolling();
                }
            }

            // Custom Interactive Alert & Toast Helpers
            function showPosAlert(message, title = 'Perhatian', type = 'warning') {
                const iconWrap = document.getElementById('posAlertIconWrap');
                const titleEl = document.getElementById('posAlertTitle');
                const msgEl = document.getElementById('posAlertMessage');

                if (titleEl) titleEl.innerText = title;
                if (msgEl) msgEl.innerText = message;

                let iconSvg = '';
                if (type === 'error') {
                    iconSvg = `<div style="width:52px; height:52px; border-radius:50%; background:#fef2f2; display:flex; align-items:center; justify-content:center; margin:0 auto; color:#ef4444;"><svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>`;
                } else if (type === 'success') {
                    iconSvg = `<div style="width:52px; height:52px; border-radius:50%; background:#f0fdf4; display:flex; align-items:center; justify-content:center; margin:0 auto; color:#1eb349;"><svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>`;
                } else {
                    iconSvg = `<div style="width:52px; height:52px; border-radius:50%; background:#fffbe6; display:flex; align-items:center; justify-content:center; margin:0 auto; color:#d97706;"><svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>`;
                }

                if (iconWrap) iconWrap.innerHTML = iconSvg;
                openModal('posAlertModal');
            }

            function showPosToast(message, type = 'info') {
                const container = document.getElementById('posToastContainer');
                if (!container) return;

                const toast = document.createElement('div');
                toast.style.cssText = `
                                                        background: #0f172a;
                                                        color: #ffffff;
                                                        padding: 10px 16px;
                                                        border-radius: 12px;
                                                        font-size: 0.82rem;
                                                        font-weight: 600;
                                                        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
                                                        display: flex;
                                                        align-items: center;
                                                        gap: 8px;
                                                        opacity: 0;
                                                        transform: translateY(-10px);
                                                        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                                                        pointer-events: auto;
                                                    `;

                let iconColor = type === 'success' ? '#1eb349' : (type === 'error' ? '#ef4444' : '#3b82f6');
                toast.innerHTML = `<span style="color:${iconColor}; font-weight:800;">●</span> <span>${escapeHtml(message)}</span>`;

                container.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateY(0)';
                }, 10);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';
                    setTimeout(() => toast.remove(), 250);
                }, 3000);
            }

            // Close Modal when clicking backdrop overlay (except posSettingsModal / static backdrop)
            document.querySelectorAll('.pos-modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function (e) {
                    if (e.target === this) {
                        if (this.id === 'posSettingsModal' || this.getAttribute('data-static-backdrop') === 'true') {
                            return; // Hanya bisa di-X kalo mau keluar atau klik Simpan
                        }
                        this.classList.remove('show');
                    }
                });
            });

            let activePosCategory = 'all';

            function setPosCategoryFilter(cat, btn) {
                activePosCategory = cat;
                document.querySelectorAll('.pos-ref-cat-pill').forEach(p => p.classList.remove('active'));
                if (btn) btn.classList.add('active');
                filterProducts();
            }

            // Input & Clear Search Box Produk Katalog
            window.handlePosProductSearchInput = function(input) {
                const clearX = document.getElementById('posSearchClearXIcon');
                const barcodeIcon = document.getElementById('posSearchBarcodeIcon');
                if (input && input.value && input.value.trim().length > 0) {
                    if (clearX) clearX.style.display = 'block';
                    if (barcodeIcon) barcodeIcon.style.display = 'none';
                } else {
                    if (clearX) clearX.style.display = 'none';
                    if (barcodeIcon) barcodeIcon.style.display = 'block';
                }
                filterProducts();
            };

            window.clearPosProductSearch = function() {
                const input = document.getElementById('posProductSearch');
                if (input) {
                    input.value = '';
                    input.focus();
                }
                const clearX = document.getElementById('posSearchClearXIcon');
                const barcodeIcon = document.getElementById('posSearchBarcodeIcon');
                if (clearX) clearX.style.display = 'none';
                if (barcodeIcon) barcodeIcon.style.display = 'block';
                filterProducts();
            };

            // Sticky Header Box-Shadow & Border saat di-scroll
            window.addEventListener('scroll', function() {
                const stickyHeader = document.getElementById('posStickyCatalogHeader');
                if (!stickyHeader) return;
                if (window.scrollY > 80) {
                    stickyHeader.style.boxShadow = '0 6px 18px rgba(0,0,0,0.06)';
                } else {
                    stickyHeader.style.boxShadow = 'none';
                }
            }, { passive: true });

            // Filter produk live (instant 0ms client-side filtering, tanpa debounce)
            function filterProducts() {
                const searchInput = document.getElementById('posProductSearch');
                const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
                const cards = document.querySelectorAll('.product-card');
                let visibleCount = 0;

                let countAll = 0;
                let countPhysical = 0;
                let countFnb = 0;
                let countDigital = 0;

                cards.forEach(card => {
                    const name = (card.getAttribute('data-name') || '').toLowerCase();
                    const cat = card.getAttribute('data-category') || 'physical';
                    const cardCatId = card.getAttribute('data-category-id') || '';

                    // Match text query
                    const textMatch = !query || name.includes(query);
                    if (textMatch) {
                        countAll++;
                        if (cat === 'physical') countPhysical++;
                        else if (cat === 'fnb') countFnb++;
                        else if (cat === 'digital') countDigital++;
                    }

                    // Match category (All, specific type, or Creator's Category ID)
                    let catMatch = false;
                    if (activePosCategory === 'all') {
                        catMatch = true;
                    } else if (activePosCategory === ('cat-' + cardCatId) || activePosCategory === String(cardCatId)) {
                        catMatch = true;
                    } else if (activePosCategory === cat) {
                        catMatch = true;
                    }
                    const matches = textMatch && catMatch;

                    // Use both style and class to ensure hiding works even with in-cart styles
                    if (matches) {
                        card.style.display = '';
                        card.classList.remove('pos-filtered-hidden');
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                        card.classList.add('pos-filtered-hidden');
                    }
                });

                // Update badge hitungan pill kategori
                const elAll = document.getElementById('catCountAll');
                const elPhys = document.getElementById('catCountPhysical');
                const elFnb = document.getElementById('catCountFnb');
                const elDigi = document.getElementById('catCountDigital');

                if (elAll) elAll.textContent = countAll;
                if (elPhys) elPhys.textContent = countPhysical;
                if (elFnb) elFnb.textContent = countFnb;
                if (elDigi) elDigi.textContent = countDigital;

                // Update badge jumlah produk tersedia
                const badge = document.getElementById('posMenuCountBadge');
                if (badge) badge.textContent = visibleCount + ' Produk Tersedia';

                // Toggle empty state jika 0 hasil
                const emptyState = document.getElementById('posEmptyStateContainer');
                if (emptyState) {
                    emptyState.style.display = visibleCount === 0 ? 'flex' : 'none';
                }
            }

            // Pastikan event listener terpasang setelah DOM siap & init hitungan awal
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('posProductSearch');
                if (searchInput) {
                    searchInput.addEventListener('input', filterProducts);
                    searchInput.addEventListener('keyup', filterProducts);
                }
                filterProducts();

                // Initialize POS dynamic components
                if (typeof renderCartFees === 'function') renderCartFees();
                if (typeof renderDiscountPresetDropdown === 'function') renderDiscountPresetDropdown();
                if (typeof updateFeesBadgeCount === 'function') updateFeesBadgeCount();
                if (typeof updateDiscountsBadgeCount === 'function') updateDiscountsBadgeCount();
            });

            // Product Click Handler (Cek apakah memiliki variasi)
            function handleProductClick(id) {
                const p = window.posCatalog ? window.posCatalog[id] : null;
                if (!p) return;

                if (p.hasVariants && p.variants && p.variants.length > 0) {
                    openVariantModal(id);
                } else {
                    addToCart(p.id, p.name, p.price, p.image);
                }
            }

            // Stepper di Kartu Produk
            function handleCardStepper(id, delta) {
                const p = window.posCatalog ? window.posCatalog[id] : null;
                if (!p) return;

                if (delta > 0 && p.hasVariants && p.variants && p.variants.length > 0) {
                    openVariantModal(id);
                    return;
                }

                // Cari item terakhir di cart dengan id ini
                const matching = cart.filter(i => i.id == id);
                if (matching.length === 0) return;

                const lastItem = matching[matching.length - 1];
                updateQty(lastItem.cartKey || lastItem.id, delta);
            }

            // Add product to cart (Mendukung Varian & Catatan)
            function addToCart(id, name, price, image = '', variantValueId = null, variantName = '', notes = '') {
                if (!image) {
                    const card = document.querySelector(`.product-card[data-id="${id}"]`);
                    if (card) {
                        image = card.getAttribute('data-image') || '';
                        if (!image) {
                            const imgTag = card.querySelector('img.product-img');
                            if (imgTag && imgTag.src) image = imgTag.src;
                        }
                    }
                }

                const cleanNotes = (notes || '').trim();
                const cleanVariant = (variantName || '').trim();
                const cartKey = `${id}__${variantValueId || 'std'}__${cleanVariant}__${cleanNotes}`;

                const existing = cart.find(item => item.cartKey === cartKey);
                if (existing) {
                    existing.qty += 1;
                    if (!existing.image && image) existing.image = image;
                } else {
                    cart.push({
                        cartKey: cartKey,
                        id: id,
                        name: name,
                        price: parseFloat(price) || 0,
                        image: image,
                        qty: 1,
                        variant_value_id: variantValueId,
                        variant_name: cleanVariant,
                        notes: cleanNotes
                    });
                }
                renderCart();
                showPosToast(`+ ${name} ditambahkan`, 'success');
            }

            // Update Item Qty by cartKey or id
            function updateQty(keyOrId, delta) {
                const item = cart.find(i => i.cartKey === keyOrId || i.id === keyOrId);
                if (!item) return;

                item.qty += delta;
                if (item.qty <= 0) {
                    cart = cart.filter(i => (i.cartKey ? i.cartKey !== item.cartKey : i.id !== item.id));
                }
                renderCart();
            }

            // Remove Item
            function removeItem(keyOrId) {
                cart = cart.filter(i => (i.cartKey ? i.cartKey !== keyOrId : i.id !== keyOrId));
                renderCart();
            }

            // Update Item Note
            window.updateItemNote = function(keyOrId, noteVal) {
                const item = cart.find(i => (i.cartKey ? i.cartKey === keyOrId : i.id === keyOrId));
                if (item) {
                    item.notes = noteVal;
                }
            };

            // Clear Cart
            function clearCart() {
                cart = [];
                renderCart();
            }

            // ══ Variant & Notes Modal Logic ══
            let currentVariantProduct = null;
            let currentVariantSelections = {};
            let currentVariantModalQty = 1;

            function openVariantModal(productId) {
                const p = window.posCatalog ? window.posCatalog[productId] : null;
                if (!p) return;
                currentVariantProduct = p;
                currentVariantModalQty = 1;
                currentVariantSelections = {};

                document.getElementById('variantModalTitle').innerText = p.name;
                document.getElementById('variantModalBasePrice').innerText = 'Harga Dasar: Rp ' + formatRupiah(p.price);

                const imgEl = document.getElementById('variantModalImg');
                const fallbackEl = document.getElementById('variantModalImgFallback');
                if (p.image) {
                    imgEl.src = p.image;
                    imgEl.style.display = 'block';
                    fallbackEl.style.display = 'none';
                } else {
                    imgEl.style.display = 'none';
                    fallbackEl.style.display = 'block';
                }

                document.getElementById('variantModalNotes').value = '';
                document.getElementById('variantModalQty').innerText = '1';

                const container = document.getElementById('variantModalOptionsList');
                container.innerHTML = '';

                (p.variants || []).forEach(opt => {
                    const optWrap = document.createElement('div');
                    optWrap.innerHTML = `
                                    <div style="font-weight:700; font-size:0.78rem; color:#334155; margin-bottom:0.45rem; display:flex; justify-content:space-between;">
                                        <span>${escapeHtml(opt.name)}</span>
                                        <span style="font-size:0.7rem; color:#94a3b8; font-weight:400;">Pilih salah satu</span>
                                    </div>
                                    <div class="variant-pill-grid" style="display:flex; flex-wrap:wrap; gap:8px;" id="opt-pills-${opt.id}">
                                    </div>
                                `;
                    container.appendChild(optWrap);

                    const pillsWrap = optWrap.querySelector(`#opt-pills-${opt.id}`);
                    const firstVal = opt.values && opt.values.length > 0 ? opt.values[0] : null;
                    if (firstVal) {
                        currentVariantSelections[opt.id] = firstVal;
                    }

                    (opt.values || []).forEach(val => {
                        const pill = document.createElement('button');
                        pill.type = 'button';
                        pill.className = 'variant-pill-btn';
                        const isSelected = firstVal && firstVal.id === val.id;
                        const adjText = val.price_adjustment > 0 ? ` (+Rp ${formatRupiah(val.price_adjustment)})` : (val.price_adjustment < 0 ? ` (-Rp ${formatRupiah(Math.abs(val.price_adjustment))})` : '');

                        pill.innerHTML = `<span>${escapeHtml(val.value)}</span><span style="font-size:0.7rem; font-weight:700; color:#16a34a;">${adjText}</span>`;
                        pill.style.cssText = `padding:6px 13px; border-radius:999px; font-size:0.75rem; font-weight:600; cursor:pointer; transition:all 0.15s ease; border:1.5px solid ${isSelected ? '#16a34a' : '#e2e8f0'}; background:${isSelected ? '#f0fdf4' : '#ffffff'}; color:${isSelected ? '#15803d' : '#334155'}; display:inline-flex; align-items:center; gap:5px;`;

                        pill.onclick = () => {
                            currentVariantSelections[opt.id] = val;
                            pillsWrap.querySelectorAll('button').forEach(b => {
                                b.style.border = '1.5px solid #e2e8f0';
                                b.style.background = '#ffffff';
                                b.style.color = '#334155';
                            });
                            pill.style.border = '1.5px solid #16a34a';
                            pill.style.background = '#f0fdf4';
                            pill.style.color = '#15803d';
                            updateVariantModalTotal();
                        };

                        pillsWrap.appendChild(pill);
                    });
                });

                updateVariantModalTotal();
                openModal('posVariantModal');
            }

            function adjustVariantModalQty(delta) {
                currentVariantModalQty = Math.max(1, currentVariantModalQty + delta);
                document.getElementById('variantModalQty').innerText = currentVariantModalQty;
                updateVariantModalTotal();
            }

            function updateVariantModalTotal() {
                if (!currentVariantProduct) return;
                let unitPrice = currentVariantProduct.price;
                Object.values(currentVariantSelections).forEach(v => {
                    if (v && v.price_adjustment) {
                        unitPrice += v.price_adjustment;
                    }
                });
                unitPrice = Math.max(0, unitPrice);
                const totalPrice = unitPrice * currentVariantModalQty;
                document.getElementById('btnVariantModalAdd').innerText = `Tambah ke Pesanan — Rp ${formatRupiah(totalPrice)}`;
            }

            function confirmVariantModalAdd() {
                if (!currentVariantProduct) return;
                let unitPrice = currentVariantProduct.price;
                const variantNames = [];
                let primaryVariantValueId = null;

                Object.entries(currentVariantSelections).forEach(([optId, val]) => {
                    if (val) {
                        if (!primaryVariantValueId) primaryVariantValueId = val.id;
                        unitPrice += (val.price_adjustment || 0);
                        variantNames.push(val.value);
                    }
                });

                unitPrice = Math.max(0, unitPrice);
                const notes = document.getElementById('variantModalNotes').value.trim();
                const variantNameStr = variantNames.join(', ');

                for (let i = 0; i < currentVariantModalQty; i++) {
                    addToCart(
                        currentVariantProduct.id,
                        currentVariantProduct.name,
                        unitPrice,
                        currentVariantProduct.image,
                        primaryVariantValueId,
                        variantNameStr,
                        notes
                    );
                }

                closeModal('posVariantModal');
            }

            // Render Cart HTML & Badges (Mendukung Tampilan Varian & Catatan)
            function renderCart() {
                const container = document.getElementById('cartItemsContainer');

                document.querySelectorAll('.product-qty-badge').forEach(b => {
                    b.classList.remove('has-qty');
                    b.innerText = '0';
                });

                if (cart.length === 0) {
                    container.innerHTML = `
                                    <div class="text-center py-4 text-muted" id="cartEmptyNotice" style="font-size: 0.84rem;">
                                        Keranjang masih kosong.<br>Pilih produk di sebelah kiri untuk menambah.
                                    </div>
                                `;
                    document.getElementById('btnCheckout').disabled = true;
                    calculateTotals();
                    return;
                }

                document.getElementById('btnCheckout').disabled = false;
                let html = '';

                cart.forEach(item => {
                    const itemSub = item.price * item.qty;
                    const escapedKey = escapeHtml(item.cartKey || String(item.id));

                    html += `
                                    <div class="cart-item-row" style="padding: 10px 0; border-bottom: 1px dashed #f1f5f9;">
                                        <div class="cart-item-thumb">
                                            ${item.image ? `
                                                <img src="${item.image}" alt="${escapeHtml(item.name)}" class="cart-item-img" onerror="this.parentElement.innerHTML='<div class=\'cart-item-img-fallback\'><svg width=\'16\' height=\'16\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' viewBox=\'0 0 24 24\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><polyline points=\'21 15 16 10 5 21\'/></svg></div>';">
                                            ` : `
                                                <div class="cart-item-img-fallback">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                                        <polyline points="21 15 16 10 5 21"/>
                                                    </svg>
                                                </div>
                                            `}
                                        </div>
                                        <div class="cart-item-info">
                                            <div class="cart-item-title">${escapeHtml(item.name)}</div>
                                            ${item.variant_name ? `
                                                <div style="font-size:0.71rem; color:#15803d; font-weight:700; margin-top:2px; display:inline-flex; align-items:center; gap:3px; background:#f0fdf4; padding:1px 6px; border-radius:4px;">
                                                    ${escapeHtml(item.variant_name)}
                                                </div>
                                            ` : ''}
                                            <div style="margin-top:4px;">
                                                <input type="text" placeholder="Catatan produk..." value="${escapeHtml(item.notes || '')}" oninput="updateItemNote('${escapedKey}', this.value)" style="width:100%; font-size:0.72rem; padding:3px 6px; border:1px solid #e2e8f0; border-radius:6px; outline:none; background:#f8fafc; color:#334155;">
                                            </div>
                                            <div class="cart-item-unit-price" style="margin-top:2px;">Rp ${formatRupiah(itemSub)}</div>
                                        </div>
                                        <div class="cart-qty-ctrl">
                                            <button type="button" class="btn-qty" onclick="updateQty('${escapedKey}', -1)">-</button>
                                            <span class="qty-num">${item.qty}</span>
                                            <button type="button" class="btn-qty btn-qty-plus" onclick="updateQty('${escapedKey}', 1)">+</button>
                                            <button type="button" class="btn text-danger ms-1 p-0" onclick="removeItem('${escapedKey}')" style="border:none; background:none; cursor:pointer;" title="Hapus">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                `;
                });

                container.innerHTML = html;

                // Sync Drawer Cart on Mobile & Confirm Sale Modal (Screen 3)
                const drawerContainer = document.getElementById('drawerCartItemsContainer');
                const confirmContainer = document.getElementById('confirmBillingItemsContainer');
                if (confirmContainer) confirmContainer.innerHTML = html;
                if (drawerContainer) drawerContainer.innerHTML = html;

                const confirmBillingCount = document.getElementById('confirmBillingCount');
                if (confirmBillingCount) {
                    const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
                    confirmBillingCount.innerText = `Billing Items (${totalItems})`;
                }

                // Update Card State & Badges
                document.querySelectorAll('.product-card').forEach(card => {
                    const id = card.getAttribute('data-id');
                    card.classList.remove('in-cart');
                    const checkBadge = document.getElementById(`card-check-${id}`);
                    const stepper = document.getElementById(`card-stepper-${id}`);
                    const addBtn = document.getElementById(`card-add-btn-${id}`);
                    const badgeQty = document.getElementById(`badge-qty-${id}`);
                    if (checkBadge) checkBadge.style.display = 'none';
                    if (stepper) stepper.style.display = 'none';
                    if (addBtn) addBtn.style.display = 'flex';
                    if (badgeQty) {
                        badgeQty.innerText = '0';
                        badgeQty.classList.remove('has-qty');
                    }
                });

                // Hitung total qty per product id
                const productTotals = {};
                cart.forEach(item => {
                    productTotals[item.id] = (productTotals[item.id] || 0) + item.qty;
                });

                Object.entries(productTotals).forEach(([pId, totalQty]) => {
                    const card = document.querySelector(`.product-card[data-id="${pId}"]`);
                    const checkBadge = document.getElementById(`card-check-${pId}`);
                    const stepper = document.getElementById(`card-stepper-${pId}`);
                    const addBtn = document.getElementById(`card-add-btn-${pId}`);
                    const qtyNum = document.getElementById(`card-qty-num-${pId}`);
                    const badgeQty = document.getElementById(`badge-qty-${pId}`);

                    if (card && totalQty > 0) {
                        card.classList.add('in-cart');
                        if (checkBadge) checkBadge.style.display = 'flex';
                        if (stepper) stepper.style.display = 'none';
                        if (addBtn) addBtn.style.display = 'flex';
                        if (qtyNum) qtyNum.innerText = totalQty;
                        if (badgeQty) {
                            badgeQty.innerText = 'x' + totalQty;
                            badgeQty.classList.add('has-qty');
                        }
                    }
                });

                calculateTotals();
            }

            // Mobile Cart Drawer Helpers
            function toggleCartDrawer() {
                const drawer = document.getElementById('posCartDrawer');
                const overlay = document.getElementById('posCartDrawerOverlay');
                if (drawer && overlay) {
                    const isOpen = drawer.classList.contains('open');
                    if (isOpen) {
                        drawer.classList.remove('open');
                        overlay.classList.remove('open');
                    } else {
                        drawer.classList.add('open');
                        overlay.classList.add('open');
                    }
                }
            }

            function closeCartDrawer() {
                const drawer = document.getElementById('posCartDrawer');
                const overlay = document.getElementById('posCartDrawerOverlay');
                if (drawer) drawer.classList.remove('open');
                if (overlay) overlay.classList.remove('open');
            }

            // Toggle Discount Type (nominal / percent)
            function setDiscountType(type) {
                discountType = type;
                const bNom = document.getElementById('btnDiscNominal');
                const bPct = document.getElementById('btnDiscPercent');
                if (bNom) bNom.classList.toggle('active', type === 'nominal');
                if (bPct) bPct.classList.toggle('active', type === 'percent');
                calculateTotals();
            }

            // Calculate Subtotal, Discounts, Fees & Total
            function calculateTotals() {
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

                // ── Discount Calculation (scoped) ──
                let discountVal = parseFloat(document.getElementById('posDiscountInput').value) || 0;
                let discountAmount = 0;
                const preset = window._selectedDiscountPreset;

                if (preset && preset.scope === 'specific' && Array.isArray(preset.product_ids) && preset.product_ids.length > 0) {
                    // Only apply discount to matching products
                    const eligibleIds = new Set(preset.product_ids.map(Number));
                    const eligibleSubtotal = cart
                        .filter(item => eligibleIds.has(Number(item.id)))
                        .reduce((s, item) => s + (item.price * item.qty), 0);
                    if (preset.type === 'percent') {
                        discountAmount = (eligibleSubtotal * Math.min(100, Math.max(0, preset.value))) / 100;
                    } else {
                        discountAmount = Math.min(eligibleSubtotal, Math.max(0, preset.value));
                    }
                    discountVal = discountAmount; // Keep input synced
                } else {
                    if (discountType === 'percent') {
                        discountVal = Math.min(100, Math.max(0, discountVal));
                        discountAmount = (subtotal * discountVal) / 100;
                    } else {
                        discountAmount = Math.min(subtotal, Math.max(0, discountVal));
                    }
                }

                // ── Custom Fees ──
                let customFeesTotal = 0;
                (posFeesData || []).forEach(fee => {
                    if (!fee.is_active) return;
                    const amt = fee.type === 'percent'
                        ? (subtotal * (parseFloat(fee.value) || 0)) / 100
                        : (parseFloat(fee.value) || 0);
                    customFeesTotal += amt;
                });

                // ── System Fees ──
                const platformFee = (subtotal * platformFeeRate) / 100;
                const adminFee = (subtotal * adminFeeRate) / 100;
                const systemFees = platformFee + adminFee;

                let grandTotal = 0;
                if (posFeePayer === 'buyer') {
                    grandTotal = Math.max(0, subtotal - discountAmount + customFeesTotal + systemFees);
                    document.getElementById('displaySystemFees').innerText = 'Rp ' + formatRupiah(systemFees);
                } else {
                    grandTotal = Math.max(0, subtotal - discountAmount + customFeesTotal);
                    document.getElementById('displaySystemFees').innerHTML = '<span style="color:#16a34a; font-weight:700;">Rp 0</span> <span style="font-size:0.7rem; color:#64748b;">(Ditanggung Penjual)</span>';
                }

                document.getElementById('displaySubtotal').innerText = 'Rp ' + formatRupiah(subtotal);
                const dispDisc = document.getElementById('displayDiscountAmount');
                if (dispDisc) {
                    dispDisc.innerText = discountAmount > 0 ? ('- Rp ' + formatRupiah(discountAmount)) : '- Rp 0';
                }
                document.getElementById('displayGrandTotal').innerText = 'Rp ' + formatRupiah(grandTotal);

                renderCartFees();

                const totalItemsCount = cart.reduce((sum, item) => sum + item.qty, 0);
                document.getElementById('mobileCartCount').innerText = `${totalItemsCount} Items`;
                document.getElementById('mobileCartTotal').innerText = 'Rp ' + formatRupiah(grandTotal);
                const drTotal = document.getElementById('drawerGrandTotal');
                if (drTotal) drTotal.innerText = 'Rp ' + formatRupiah(grandTotal);
            }

            function syncCustomerEmail(val) {
                const target = document.getElementById('posCustomerEmail');
                if (target) target.value = val;
            }

            // Open Payment Modal
            function openPaymentModal() {
                renderPaymentModalOrderItems();
                if (cart.length === 0) {
                    showPosAlert('Keranjang POS Anda masih kosong. Silakan pilih produk terlebih dahulu.', 'Keranjang Kosong', 'warning');
                    return;
                }

                // Sync email from cart input if set
                const cartEmail = (document.getElementById('posCartCustomerEmail')?.value || '').trim();
                if (cartEmail) {
                    document.getElementById('posCustomerEmail').value = cartEmail;
                }

                const grandTotalText = document.getElementById('displayGrandTotal').innerText;
                document.getElementById('modalPayTotal').innerText = grandTotalText;

                const grandTotal = getGrandTotalVal();
                document.getElementById('cashPaidInput').value = grandTotal;
                const displayInput = document.getElementById('cashPaidDisplayInput');
                if (displayInput) displayInput.value = grandTotal > 0 ? formatRupiah(grandTotal) : '';
                calculateCashChange();

                // Check deposit vs required fee
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                const totalFeeRequired = (subtotal * (platformFeeRate + adminFeeRate)) / 100;
                const cashFeeDisplay = document.getElementById('cashFeeRequiredDisplay');
                if (cashFeeDisplay) cashFeeDisplay.innerText = 'Rp ' + formatRupiah(totalFeeRequired);

                const optCash = document.getElementById('optCash');
                const cashDepositWarning = document.getElementById('cashDepositWarning');
                const cashWarningText = document.getElementById('cashDepositWarningText');
                const cashBadge = document.getElementById('cashDepositBadge');

                const isDepositInsufficient = (posFeePayer === 'seller' && totalFeeRequired > 0 && currentPosBalance < totalFeeRequired);

                if (optCash) {
                    if (isDepositInsufficient) {
                        optCash.classList.add('disabled-cash');
                        optCash.style.background = '#f8fafc';
                        optCash.style.borderColor = '#cbd5e1';
                        optCash.style.opacity = '0.6';
                        optCash.style.cursor = 'not-allowed';
                        optCash.style.pointerEvents = 'none';

                        const cashRadio = optCash.querySelector('input[type="radio"]');
                        if (cashRadio) cashRadio.disabled = true;

                        if (cashBadge) {
                            cashBadge.style.display = 'inline-block';
                            cashBadge.innerText = currentPosBalance <= 0 ? '(Rp 0)' : 'Saldo Deposit Kurang';
                        }

                        if (cashDepositWarning) {
                            cashDepositWarning.style.display = 'block';
                            if (cashWarningText) {
                                if (currentPosBalance <= 0) {
                                    cashWarningText.innerText = 'Saldo deposit kasir Anda Rp 0. Silakan isi saldo deposit terlebih dahulu untuk memproses pembayaran tunai.';
                                } else {
                                    cashWarningText.innerText = `Potongan fee transaksi ini (Rp ${formatRupiah(totalFeeRequired)}) melebihi sisa deposit Anda (Rp ${formatRupiah(currentPosBalance)}).`;
                                }
                            }
                        }

                        selectPaymentMethod('cashless');
                    } else {
                        optCash.classList.remove('disabled-cash');
                        optCash.style.background = '';
                        optCash.style.borderColor = '';
                        optCash.style.opacity = '1';
                        optCash.style.cursor = 'pointer';
                        optCash.style.pointerEvents = 'auto';

                        const cashRadio = optCash.querySelector('input[type="radio"]');
                        if (cashRadio) cashRadio.disabled = false;

                        if (cashBadge) cashBadge.style.display = 'none';
                        if (cashDepositWarning) cashDepositWarning.style.display = 'none';

                        selectPaymentMethod('cash');
                    }
                }

                openModal('posPaymentModal');
            }

            function getGrandTotalVal() {
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                let discountVal = parseFloat(document.getElementById('posDiscountInput').value) || 0;
                let discountAmount = 0;
                const preset = window._selectedDiscountPreset;
                if (preset && preset.scope === 'specific' && Array.isArray(preset.product_ids) && preset.product_ids.length > 0) {
                    const eligibleIds = new Set(preset.product_ids.map(Number));
                    const eligibleSubtotal = cart.filter(item => eligibleIds.has(Number(item.id))).reduce((s, i) => s + i.price * i.qty, 0);
                    discountAmount = preset.type === 'percent' ? (eligibleSubtotal * Math.min(100, Math.max(0, preset.value))) / 100 : Math.min(eligibleSubtotal, Math.max(0, preset.value));
                } else {
                    discountAmount = discountType === 'percent' ? (subtotal * Math.min(100, discountVal)) / 100 : Math.min(subtotal, discountVal);
                }
                let customFeesTotal = 0;
                (posFeesData || []).forEach(fee => {
                    if (!fee.is_active) return;
                    customFeesTotal += fee.type === 'percent' ? (subtotal * (parseFloat(fee.value) || 0)) / 100 : (parseFloat(fee.value) || 0);
                });
                const systemFees = (subtotal * (platformFeeRate + adminFeeRate)) / 100;
                if (posFeePayer === 'buyer') {
                    return Math.max(0, subtotal - discountAmount + customFeesTotal + systemFees);
                }
                return Math.max(0, subtotal - discountAmount + customFeesTotal);
            }

            // Select Payment Method (cash | cashless)
            function selectPaymentMethod(method) {
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                const totalFeeRequired = (subtotal * (platformFeeRate + adminFeeRate)) / 100;

                if (method === 'cash' && posFeePayer === 'seller' && totalFeeRequired > 0 && currentPosBalance < totalFeeRequired) {
                    showPosAlert('Saldo deposit tidak mencukupi untuk memproses transaksi tunai ini. Silakan pilih Non-Tunai atau isi saldo deposit.', 'Saldo Deposit Kurang', 'warning');
                    return;
                }

                document.querySelectorAll('.pay-option-card').forEach(c => c.classList.remove('selected'));
                const rad = document.querySelector(`input[name="pay_method"][value="${method}"]`);
                if (rad) rad.checked = true;

                const card = document.getElementById('opt' + method.charAt(0).toUpperCase() + method.slice(1));
                if (card) card.classList.add('selected');

                const cashPanel = document.getElementById('cashCalcPanel');
                cashPanel.style.display = method === 'cash' ? 'block' : 'none';
            }

            // Live Cash Input Formatting with Dots (Rp X.XXX)
            function handleCashInput(el) {
                let raw = el.value.replace(/[^0-9]/g, '');
                let val = parseFloat(raw) || 0;
                document.getElementById('cashPaidInput').value = val;

                if (val > 0) {
                    el.value = formatRupiah(val);
                } else {
                    el.value = '';
                }
                calculateCashChange();
            }

            // Quick Cash Buttons
            function setQuickCash(val) {
                let numVal = 0;
                if (val === 'exact') {
                    numVal = getGrandTotalVal();
                } else {
                    numVal = parseFloat(val) || 0;
                }
                document.getElementById('cashPaidInput').value = numVal;
                const displayInput = document.getElementById('cashPaidDisplayInput');
                if (displayInput) displayInput.value = numVal > 0 ? formatRupiah(numVal) : '';
                calculateCashChange();
            }

            // Calculate Cash Change
            function calculateCashChange() {
                const grandTotal = getGrandTotalVal();
                const cashPaid = parseFloat(document.getElementById('cashPaidInput').value) || 0;
                const change = Math.max(0, cashPaid - grandTotal);
                document.getElementById('cashChangeDisplay').innerText = 'Rp ' + formatRupiah(change);
            }

            // Submit Order Checkout
            function processOrderCheckout() {
                const custName = document.getElementById('posCustomerName').value.trim();
                const tableNum = document.getElementById('posTableNumber').value.trim();
                const custPhone = document.getElementById('posCustomerPhone').value.trim();
                const custEmail = (document.getElementById('posCartCustomerEmail')?.value || document.getElementById('posCustomerEmail')?.value || '').trim();

                const payMethodRaw = document.querySelector('input[name="pay_method"]:checked').value;
                // Map UI choice: 'cashless' => 'qris' for backend
                const payMethodBackend = payMethodRaw === 'cashless' ? 'qris' : payMethodRaw;
                const cashPaid = parseFloat(document.getElementById('cashPaidInput').value) || 0;
                const grandTotal = getGrandTotalVal();

                if (payMethodRaw === 'cash' && cashPaid < grandTotal) {
                    showPosAlert('Jumlah uang tunai yang diterima kurang dari total tagihan!', 'Nominal Uang Kurang', 'warning');
                    return;
                }

                const btnSubmit = document.getElementById('btnSubmitOrder');
                btnSubmit.disabled = true;
                btnSubmit.innerText = 'Memproses...';

                // Hitung breakdown custom fees & total biaya tambahan POS
                const cartSubtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                let customFeesTotal = 0;
                const feesBreakdown = [];
                (posFeesData || []).forEach(fee => {
                    if (!fee.is_active) return;
                    const amt = fee.type === 'percent'
                        ? (cartSubtotal * (parseFloat(fee.value) || 0)) / 100
                        : (parseFloat(fee.value) || 0);
                    customFeesTotal += amt;
                    feesBreakdown.push({
                        name: fee.name,
                        type: fee.type,
                        value: fee.value,
                        amount: amt
                    });
                });

                // Hitung kalkulasi diskon akurat (mendukung diskon selektif per produk)
                let discountVal = parseFloat(document.getElementById('posDiscountInput').value) || 0;
                let discountAmount = 0;
                const preset = window._selectedDiscountPreset;
                if (preset && preset.scope === 'specific' && Array.isArray(preset.product_ids) && preset.product_ids.length > 0) {
                    const eligibleIds = new Set(preset.product_ids.map(Number));
                    const eligibleSubtotal = cart.filter(item => eligibleIds.has(Number(item.id))).reduce((s, i) => s + i.price * i.qty, 0);
                    discountAmount = preset.type === 'percent' ? (eligibleSubtotal * Math.min(100, Math.max(0, preset.value))) / 100 : Math.min(eligibleSubtotal, Math.max(0, preset.value));
                } else {
                    discountAmount = discountType === 'percent' ? (cartSubtotal * Math.min(100, discountVal)) / 100 : Math.min(cartSubtotal, discountVal);
                }

                const payload = {
                    _token: '{{ csrf_token() }}',
                    customer_name: custName,
                    table_number: tableNum,
                    customer_phone: custPhone,
                    customer_email: custEmail,
                    items: cart.map(i => ({
                        id: i.id,
                        qty: i.qty,
                        price: i.price,
                        variant_value_id: i.variant_value_id || null,
                        variant_name: i.variant_name || null,
                        notes: i.notes || null
                    })),
                    discount_type: discountType,
                    discount_val: discountVal,
                    discount_amount: discountAmount,
                    discount_details: preset ? {
                        name: preset.name,
                        type: preset.type,
                        value: preset.value,
                        scope: preset.scope,
                        product_ids: preset.product_ids || []
                    } : null,
                    service_fee: customFeesTotal,
                    fees_breakdown: feesBreakdown,
                    payment_method: payMethodBackend,
                    cash_paid: cashPaid,
                    cash_change: Math.max(0, cashPaid - grandTotal)
                };

                fetch('{{ route("creator.pos.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                    .then(res => res.json())
                    .then(data => {
                        btnSubmit.disabled = false;
                        btnSubmit.innerText = 'Konfirmasi & Bayar';

                        if (data.success) {
                            closeModal('posPaymentModal');

                            if (data.pos_balance !== undefined) {
                                currentPosBalance = parseFloat(data.pos_balance);
                                posBalance = currentPosBalance;
                                const balDisplay = document.getElementById('topbarPosBalanceDisplay');
                                if (balDisplay) balDisplay.innerText = 'Rp ' + formatRupiah(posBalance);
                            }

                            if (payMethodRaw === 'cashless') {
                                if (data.is_static_qris) {
                                    window.currentStaticQrisOrder = data.order;
                                    document.getElementById('staticQrisAmountText').innerText = 'Rp ' + formatRupiah(data.order.total);
                                    document.getElementById('staticQrisStoreName').innerText = posStoreDisplayName || 'Toko';

                                    const qrisImg = document.getElementById('staticQrisDisplayImg');
                                    const noImgWarn = document.getElementById('staticQrisNoImageWarning');
                                    if (data.qris_image_url) {
                                        qrisImg.src = data.qris_image_url;
                                        qrisImg.style.display = 'block';
                                        noImgWarn.style.display = 'none';
                                    } else {
                                        qrisImg.style.display = 'none';
                                        noImgWarn.style.display = 'block';
                                    }

                                    openModal('posStaticQrisModal');
                                    return;
                                }
                                if (data.snap_token && typeof window.snap !== 'undefined') {
                                    document.getElementById('midtransBlurOverlay').classList.add('active');
                                    try {
                                        window.snap.pay(data.snap_token, {
                                            onSuccess: function (result) {
                                                document.getElementById('midtransBlurOverlay').classList.remove('active');
                                                showPosToast('Pembayaran berhasil dikonfirmasi!', 'success');
                                                showReceiptModal(data.order);
                                            },
                                            onPending: function (result) {
                                                document.getElementById('midtransBlurOverlay').classList.remove('active');
                                                showPosToast('Menunggu pembayaran dari pelanggan. Cek di Riwayat Transaksi.', 'info');
                                                showReceiptModal(data.order);
                                            },
                                            onError: function (result) {
                                                document.getElementById('midtransBlurOverlay').classList.remove('active');
                                                // Batalkan order pending jika pembayaran gagal
                                                if (data.order && data.order.id) {
                                                    fetch('{{ route("creator.pos.cancel-pending") }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                        },
                                                        body: JSON.stringify({ order_id: data.order.id })
                                                    }).catch(() => { });
                                                }
                                                showPosAlert('Pembayaran gagal. Transaksi dibatalkan dan stok dikembalikan.', 'Gagal Pembayaran', 'error');
                                            },
                                            onClose: function () {
                                                document.getElementById('midtransBlurOverlay').classList.remove('active');
                                                // Batalkan order pending jika modal ditutup tanpa bayar
                                                if (data.order && data.order.id) {
                                                    fetch('{{ route("creator.pos.cancel-pending") }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                        },
                                                        body: JSON.stringify({ order_id: data.order.id })
                                                    }).catch(() => { });
                                                }
                                                showPosAlert('Pop-up pembayaran ditutup. Transaksi dibatalkan dan stok dikembalikan.', 'Pembayaran Dibatalkan', 'warning');
                                            }
                                        });
                                    } catch (e) {
                                        document.getElementById('midtransBlurOverlay').classList.remove('active');
                                        showReceiptModal(data.order);
                                    }
                                } else {
                                    document.getElementById('midtransBlurOverlay').classList.remove('active');
                                    showReceiptModal(data.order);
                                }
                            } else {
                                showReceiptModal(data.order);
                            }
                        } else {
                            showPosAlert(data.message || 'Gagal memproses transaksi.', 'Gagal Transaksi', 'error');
                        }
                    })
                    .catch(err => {
                        btnSubmit.disabled = false;
                        btnSubmit.innerText = 'Konfirmasi & Bayar';
                        showPosAlert('Terjadi kesalahan jaringan/server: ' + err.message, 'Kesalahan Koneksi', 'error');
                    });
            }

            // ── Order Status Real-Time Polling & Status Badge ─────────────────────
            let orderStatusPollTimer = null;
            let currentPollingOrderId = null;

            function updateReceiptStatusBadge(status) {
                const badge = document.getElementById('recStatusBadge');
                if (!badge) return;

                const st = String(status || '').toLowerCase();
                badge.className = '';
                badge.style.background = '';
                badge.style.color = '';
                badge.style.border = '';

                if (st === 'pending') {
                    badge.className = 'status-badge-pending';
                    badge.innerText = 'MENUNGGU PEMBAYARAN';
                } else if (['paid', 'confirmed', 'settlement', 'capture', 'success'].includes(st)) {
                    badge.className = 'status-badge-paid';
                    badge.innerText = 'LUNAS / BERHASIL';
                } else if (['cancelled', 'cancel', 'deny', 'expire', 'failed'].includes(st)) {
                    badge.style.background = '#fee2e2';
                    badge.style.color = '#991b1b';
                    badge.style.border = '1.5px solid #ef4444';
                    badge.innerText = 'DIBATALKAN';
                } else {
                    badge.className = 'status-badge-pending';
                    badge.innerText = (status || 'MENUNGGU PEMBAYARAN').toUpperCase();
                }
            }

            function startOrderStatusPolling(orderId) {
                stopOrderStatusPolling();
                currentPollingOrderId = orderId;

                // Polling interval 3 detik
                orderStatusPollTimer = setInterval(() => {
                    if (!currentPollingOrderId) return;
                    fetch(`{{ url('/creator/pos/order-status') }}/${currentPollingOrderId}`, {
                        headers: { 'Accept': 'application/json' }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.success) {
                                const isPaid = data.is_paid || ['confirmed', 'paid', 'settlement'].includes(String(data.status).toLowerCase());
                                if (isPaid) {
                                    updateReceiptStatusBadge('paid');
                                    if (currentCompletedOrder) {
                                        currentCompletedOrder.status = 'paid';
                                    }
                                    if (data.customer_email) {
                                        const autoNotice = document.getElementById('receiptEmailAutoNotice');
                                        const noneNotice = document.getElementById('receiptEmailNoneNotice');
                                        const autoEmailSpan = document.getElementById('receiptAutoEmail');
                                        if (autoNotice) autoNotice.style.display = 'flex';
                                        if (noneNotice) noneNotice.style.display = 'none';
                                        if (autoEmailSpan) autoEmailSpan.innerText = data.customer_email;
                                    }
                                    showPosToast('Pembayaran berhasil diterima &amp; terverifikasi realtime!', 'success');
                                    stopOrderStatusPolling();
                                }
                            }
                        })
                        .catch(err => {
                            console.warn('Status poll warning:', err);
                        });
                }, 3000);
            }

            function stopOrderStatusPolling() {
                if (orderStatusPollTimer) {
                    clearInterval(orderStatusPollTimer);
                    orderStatusPollTimer = null;
                }
                currentPollingOrderId = null;
            }

            // Show Receipt Modal
            function showReceiptModal(order) {
                currentCompletedOrder = order;
                const addr = order.shipping_address || {};
                window._currentReceiptPhone = order.customer_phone || (addr ? (addr.phone || addr.customer_phone) : '') || '';

                // 1. Order Number & Date / Time
                const orderCode = order.order_number || ('BYL-' + order.id);
                const orderDate = new Date(order.created_at || new Date().toISOString());

                const elOrderNum = document.getElementById('recOrderNum');
                if (elOrderNum) elOrderNum.innerText = '#' + orderCode;

                const elOrderCode = document.getElementById('recOrderCode');
                if (elOrderCode) elOrderCode.innerText = '#' + orderCode;

                const elDateOnly = document.getElementById('recDateOnly');
                if (elDateOnly) {
                    const day = String(orderDate.getDate()).padStart(2, '0');
                    const month = String(orderDate.getMonth() + 1).padStart(2, '0');
                    const year = orderDate.getFullYear();
                    elDateOnly.innerText = `${day}-${month}-${year}`;
                }

                const elTimeOnly = document.getElementById('recTimeOnly');
                if (elTimeOnly) {
                    const hours = String(orderDate.getHours()).padStart(2, '0');
                    const minutes = String(orderDate.getMinutes()).padStart(2, '0');
                    const seconds = String(orderDate.getSeconds()).padStart(2, '0');
                    elTimeOnly.innerText = `${hours}:${minutes}:${seconds}`;
                }

                // Queue Tag (e.g. [ No. 0-1 ] or daily order count)
                const elQueueTag = document.getElementById('recQueueTag');
                if (elQueueTag) {
                    const qNum = (order.id % 100) || 1;
                    elQueueTag.innerText = `No. 0-${qNum}`;
                }

                // Cashier Name (both legacy & new bill)
                const cashierDisplayName = order.cashier_name || (typeof currentCashierStaffName !== 'undefined' && currentCashierStaffName ? currentCashierStaffName : '{{ $currentCashier ? $currentCashier->name : auth()->user()->name }}');
                const elCashierName = document.getElementById('recCashierName');
                if (elCashierName) elCashierName.innerText = cashierDisplayName;
                const elCashierNameBill = document.getElementById('recCashierNameBill');
                if (elCashierNameBill) elCashierNameBill.innerText = cashierDisplayName;

                // Customer Name on bill
                const customerDisplayName = addr.name || order.customer_name || 'Pelanggan Umum';
                const elCustomerInfoBill = document.getElementById('recCustomerInfoBill');
                if (elCustomerInfoBill) elCustomerInfoBill.innerText = customerDisplayName;

                // Logo on bill card (show/hide based on if logo exists)
                const billLogoWrap = document.getElementById('recBillLogoWrap');
                const billLogoImg = document.getElementById('recBillLogoImg');
                if (billLogoWrap && billLogoImg) {
                    if (currentPosLogoUrl) {
                        billLogoImg.src = currentPosLogoUrl;
                        billLogoWrap.style.display = 'block';
                    } else {
                        billLogoWrap.style.display = 'none';
                    }
                }

                const elNotes = document.getElementById('recNotes');
                const elNotesRow = document.getElementById('recNotesRow');
                if (elNotes && elNotesRow) {
                    if (order.notes) {
                        elNotes.innerText = order.notes;
                        elNotesRow.style.display = 'table-row';
                    } else {
                        elNotesRow.style.display = 'none';
                    }
                }

                // 2. Modern Numbered Items List (media_1792971364160.png style)
                const numberedItemsWrap = document.getElementById('recNumberedItemsWrap');
                let totalItemQty = 0;
                if (numberedItemsWrap) {
                    let itemsHtml = '';
                    (order.items || []).forEach((item, idx) => {
                        const itemQty = parseInt(item.qty ?? item.quantity ?? 1) || 1;
                        totalItemQty += itemQty;
                        const itemPrice = parseInt(item.price) || 0;
                        const itemSubtotal = parseInt(item.subtotal) || (itemPrice * itemQty);

                        itemsHtml += `
                                                <div style="margin-bottom: 7px; line-height: 1.35;">
                                                    <div style="font-weight: 700; font-size: 11.5px; color: #000;">
                                                        ${idx + 1}. ${escapeHtml(item.product_name)}
                                                        ${item.variant_name ? `<span style="font-weight: normal; font-size: 10px; color: #16a34a;"> (${escapeHtml(item.variant_name)})</span>` : ''}
                                                    </div>
                                                    ${item.notes ? `<div style="font-size: 9.5px; color: #555; font-style: italic; margin-left: 12px;">* ${escapeHtml(item.notes)}</div>` : ''}
                                                    <div style="display: flex; justify-content: space-between; font-size: 11px; color: #222; margin-top: 1px;">
                                                        <span style="letter-spacing: 0.2px;">${itemQty} x ${formatRupiah(itemPrice)}</span>
                                                        <span style="font-weight: 700; color: #000;">Rp ${formatRupiah(itemSubtotal)}</span>
                                                    </div>
                                                </div>
                                            `;
                    });
                    numberedItemsWrap.innerHTML = itemsHtml || '<div style="text-align:center; color:#666; font-size:11px;">(Tidak ada item)</div>';
                }

                // Legacy items table fallback if present
                const legacyTableBody = document.querySelector('#recItemsTable tbody');
                if (legacyTableBody) {
                    let legacyRows = '';
                    (order.items || []).forEach(item => {
                        const itemQty = item.qty ?? item.quantity ?? 1;
                        legacyRows += `
                                                <tr>
                                                    <td class="text-start" style="font-weight:700;">
                                                        ${escapeHtml(item.product_name)}
                                                        <div style="font-size:10px; font-weight:normal; color:#444;">@ Rp ${formatRupiah(item.price)}</div>
                                                    </td>
                                                    <td class="text-center" style="vertical-align:top;">${itemQty}</td>
                                                    <td class="text-end" style="font-weight:700; vertical-align:top;">Rp ${formatRupiah(item.subtotal)}</td>
                                                </tr>
                                            `;
                    });
                    legacyTableBody.innerHTML = legacyRows;
                }

                // 3. Totals & Payment Details
                const elTotalQty = document.getElementById('recTotalQty');
                if (elTotalQty) elTotalQty.innerText = totalItemQty;

                const elSubtotal = document.getElementById('recSubtotal');
                if (elSubtotal) elSubtotal.innerText = 'Rp ' + formatRupiah(order.subtotal || 0);

                const discountVal = parseInt(order.discount) || 0;
                const elDiscount = document.getElementById('recDiscount');
                const elDiscountRow = document.getElementById('recDiscountRow');
                const elDiscountLabel = document.getElementById('recDiscountLabel');
                if (elDiscount && elDiscountRow) {
                    if (discountVal > 0) {
                        elDiscount.innerText = '-Rp ' + formatRupiah(discountVal);
                        if (elDiscountLabel) {
                            const discName = addr.discount_details?.name;
                            elDiscountLabel.innerText = discName ? `Diskon (${escapeHtml(discName)})` : 'Diskon';
                        }
                        elDiscountRow.style.display = 'flex';
                    } else {
                        elDiscountRow.style.display = 'none';
                    }
                }

                const serviceFeeVal = parseInt(addr.service_fee) || 0;
                const elCustomFeesWrap = document.getElementById('recCustomFeesWrap');
                if (elCustomFeesWrap) {
                    const breakdown = Array.isArray(addr.fees_breakdown) ? addr.fees_breakdown : [];
                    if (breakdown.length > 0) {
                        let feeHtml = '';
                        breakdown.forEach(f => {
                            const amt = parseFloat(f.amount) || 0;
                            if (amt > 0) {
                                const rateSuffix = f.type === 'percent' ? ` (${f.value}%)` : '';
                                feeHtml += `
                                                        <div style="display: flex; justify-content: space-between; margin-bottom: 3px; color:#475569;">
                                                            <span>${escapeHtml(f.name)}${rateSuffix}</span>
                                                            <span style="font-weight:600;">Rp ${formatRupiah(amt)}</span>
                                                        </div>
                                                    `;
                            }
                        });
                        elCustomFeesWrap.innerHTML = feeHtml;
                        elCustomFeesWrap.style.display = feeHtml ? 'block' : 'none';
                    } else if (serviceFeeVal > 0) {
                        elCustomFeesWrap.innerHTML = `
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 3px; color:#475569;">
                                                    <span>Biaya Layanan</span>
                                                    <span style="font-weight:600;">Rp ${formatRupiah(serviceFeeVal)}</span>
                                                </div>
                                            `;
                        elCustomFeesWrap.style.display = 'block';
                    } else {
                        elCustomFeesWrap.innerHTML = '';
                        elCustomFeesWrap.style.display = 'none';
                    }
                }

                const totalVal = parseInt(order.total) || 0;
                const elTotal = document.getElementById('recTotal');
                if (elTotal) elTotal.innerText = 'Rp ' + formatRupiah(totalVal);

                // Payment method & Cash change
                const isCash = (addr.payment_method || 'cash') === 'cash';
                const payMethodLabel = {
                    'cash': 'Bayar (Cash)',
                    'cashless': 'Bayar (Non-Tunai)',
                    'transfer': 'Bayar (Non-Tunai)',
                    'qris': 'Bayar (Non-Tunai)'
                }[addr.payment_method || 'cash'] || 'Bayar (Cash)';

                const elPayMethodLabel = document.getElementById('recPayMethodLabel');
                if (elPayMethodLabel) elPayMethodLabel.innerText = payMethodLabel;

                const cashPaidVal = parseInt(addr.cash_paid) || totalVal;
                const cashChangeVal = parseInt(addr.cash_change) || 0;

                const elCashPaidVal = document.getElementById('recCashPaidVal');
                if (elCashPaidVal) elCashPaidVal.innerText = 'Rp ' + formatRupiah(cashPaidVal);

                const elCashChangeVal = document.getElementById('recCashChangeVal');
                if (elCashChangeVal) elCashChangeVal.innerText = 'Rp ' + formatRupiah(cashChangeVal);

                const elPaymentCashDetails = document.getElementById('recPaymentCashDetails');
                if (elPaymentCashDetails) {
                    if (isCash && cashPaidVal > 0) {
                        elPaymentCashDetails.style.display = 'block';
                    } else {
                        elPaymentCashDetails.style.display = 'none';
                    }
                }

                // Legacy rows fallback
                const elCashPaid = document.getElementById('recCashPaid');
                const elCashChange = document.getElementById('recCashChange');
                const elCashPaidRow = document.getElementById('recCashPaidRow');
                const elCashChangeRow = document.getElementById('recCashChangeRow');
                if (elCashPaid && elCashPaidRow) {
                    if (isCash && cashPaidVal > 0) {
                        elCashPaid.innerText = 'Rp ' + formatRupiah(cashPaidVal);
                        if (elCashChange) elCashChange.innerText = 'Rp ' + formatRupiah(cashChangeVal);
                        elCashPaidRow.style.display = 'table-row';
                        if (elCashChangeRow) elCashChangeRow.style.display = 'table-row';
                    } else {
                        elCashPaidRow.style.display = 'none';
                        if (elCashChangeRow) elCashChangeRow.style.display = 'none';
                    }
                }

                // Status Badge & Realtime Polling
                const orderStatus = String(order.status || (order.payment && order.payment.status) || 'pending').toLowerCase();
                updateReceiptStatusBadge(orderStatus);
                if (orderStatus === 'pending') {
                    startOrderStatusPolling(order.id);
                } else {
                    stopOrderStatusPolling();
                }

                // Auto-email notice & pre-fill email input
                const autoNotice = document.getElementById('receiptEmailAutoNotice');
                const noneNotice = document.getElementById('receiptEmailNoneNotice');
                const autoEmailSpan = document.getElementById('receiptAutoEmail');
                const recSendEmailInput = document.getElementById('recSendEmailInput');
                const customerEmail = addr.email || '';

                if (recSendEmailInput) {
                    recSendEmailInput.value = customerEmail;
                }

                if (customerEmail) {
                    if (autoNotice) autoNotice.style.display = 'flex';
                    if (noneNotice) noneNotice.style.display = 'none';
                    if (autoEmailSpan) autoEmailSpan.innerText = customerEmail;
                } else {
                    if (autoNotice) autoNotice.style.display = 'none';
                    if (noneNotice) noneNotice.style.display = 'block';
                }

                openModal('posReceiptModal');
            }

            // Send E-Receipt Email
            function sendEReceiptEmail() {
                if (!currentCompletedOrder) return;
                const emailInput = document.getElementById('recSendEmailInput');
                const btnSend = document.getElementById('btnSendRecEmail');
                const email = (emailInput ? emailInput.value : '').trim();
                if (!email) {
                    showPosAlert('Silakan masukkan alamat email pelanggan tujuan!', 'Email Diperlukan', 'warning');
                    return;
                }

                if (btnSend) {
                    btnSend.disabled = true;
                    btnSend.innerText = 'Mengirim...';
                }

                fetch('{{ route("creator.pos.send-receipt") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_id: currentCompletedOrder.id,
                        email: email
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (btnSend) {
                            btnSend.disabled = false;
                            btnSend.innerText = 'Kirim';
                        }
                        if (data.success) {
                            const autoNotice = document.getElementById('receiptEmailAutoNotice');
                            const noneNotice = document.getElementById('receiptEmailNoneNotice');
                            const autoEmailSpan = document.getElementById('receiptAutoEmail');
                            if (autoNotice) autoNotice.style.display = 'flex';
                            if (noneNotice) noneNotice.style.display = 'none';
                            if (autoEmailSpan) autoEmailSpan.innerText = email;
                            showPosToast(data.message || 'E-Receipt berhasil dikirim!', 'success');
                        } else {
                            showPosAlert(data.message || 'Gagal mengirim E-Receipt.', 'Gagal Pengiriman', 'error');
                        }
                    })
                    .catch(err => {
                        if (btnSend) {
                            btnSend.disabled = false;
                            btnSend.innerText = 'Kirim';
                        }
                        showPosAlert('Gagal mengirim E-Receipt: ' + err.message, 'Gagal Pengiriman', 'error');
                    });
            }

            // Reprint Past Order Receipt
            function reprintPastReceipt(order) {
                if (typeof order === 'number' || typeof order === 'string') {
                    const found = (window._posTodayOrdersData || []).find(o => o.id == order);
                    if (found) order = found;
                }
                closeModal('posHistoryModal');
                showReceiptModal(order);
            }

            // Reset POS State
            function resetPOS() {
                cart = [];
                stopOrderStatusPolling();
                document.getElementById('posCustomerName').value = '';
                document.getElementById('posTableNumber').value = '';
                document.getElementById('posCustomerPhone').value = '';
                document.getElementById('posCustomerEmail').value = '';
                document.getElementById('posDiscountInput').value = '';
                const presetSelect = document.getElementById('posDiscountPresetSelect');
                if (presetSelect) presetSelect.value = '';
                window._selectedDiscountPreset = null;
                const cartEmail = document.getElementById('posCartCustomerEmail');
                if (cartEmail) cartEmail.value = '';
                renderCart();
            }

            function scrollToCartPanel() {
                document.getElementById('cartPanel').scrollIntoView({ behavior: 'smooth' });
            }

            function formatRupiah(num) {
                return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function formatDate(dateStr) {
                const d = new Date(dateStr);
                return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            }

            // ── Thermal Printing Mechanical Sound Effect ──────────────────────────
            function playThermalPrintSound() {
                try {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    if (!AudioCtx) return;
                    const audioCtx = new AudioCtx();

                    // High-pitch thermal head chime
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(987.77, audioCtx.currentTime); // B5
                    osc.frequency.exponentialRampToValueAtTime(1567.98, audioCtx.currentTime + 0.12);
                    gain.gain.setValueAtTime(0.08, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.22);
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.22);

                    // Thermal motor whirr simulation
                    const bufferSize = Math.floor(audioCtx.sampleRate * 0.45);
                    const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
                    const data = buffer.getChannelData(0);
                    for (let i = 0; i < bufferSize; i++) {
                        data[i] = (Math.random() * 2 - 1) * 0.04;
                    }
                    const noise = audioCtx.createBufferSource();
                    noise.buffer = buffer;
                    const filter = audioCtx.createBiquadFilter();
                    filter.type = 'bandpass';
                    filter.frequency.value = 850;
                    filter.Q.value = 3;
                    const noiseGain = audioCtx.createGain();
                    noiseGain.gain.setValueAtTime(0.04, audioCtx.currentTime);
                    noiseGain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.45);
                    noise.connect(filter);
                    filter.connect(noiseGain);
                    noiseGain.connect(audioCtx.destination);
                    noise.start(audioCtx.currentTime + 0.05);
                } catch (e) {
                    // Ignore audio restriction gracefully
                }
            }

            // ── Cetak Struk POS dengan Animasi Eject Kertas & Hidden Iframe ────────
            function printPosReceipt() {
                const receiptArea = document.getElementById('printablePosReceiptArea');
                if (!receiptArea) return;

                // Trigger eject paper animation from the slot
                receiptArea.classList.remove('receipt-ejecting');
                void receiptArea.offsetWidth; // force DOM reflow
                receiptArea.classList.add('receipt-ejecting');

                // Play acoustic thermal printer audio
                playThermalPrintSound();

                const content = receiptArea.innerHTML;

                // Reliable print using hidden iframe (prevents browser popup blocker)
                let iframe = document.getElementById('posHiddenPrintIframe');
                if (!iframe) {
                    iframe = document.createElement('iframe');
                    iframe.id = 'posHiddenPrintIframe';
                    iframe.style.position = 'fixed';
                    iframe.style.right = '-9999px';
                    iframe.style.bottom = '-9999px';
                    iframe.style.width = '1px';
                    iframe.style.height = '1px';
                    iframe.style.border = 'none';
                    document.body.appendChild(iframe);
                }

                const doc = iframe.contentWindow || iframe.contentDocument;
                const iframeDoc = doc.document || doc;
                iframeDoc.open();
                iframeDoc.write(`<!DOCTYPE html>
                                        <html>
                                        <head>
                                            <title>Struk POS</title>
                                            <meta charset="utf-8">
                                            <style>
                                                @page { size: 80mm auto; margin: 0; }
                                                body { 
                                                    font-family: 'Courier New', Courier, monospace; 
                                                    font-size: 11px; 
                                                    color: #000000; 
                                                    margin: 0; 
                                                    padding: 4mm 3mm; 
                                                    width: 72mm; 
                                                    background: #ffffff; 
                                                    line-height: 1.3;
                                                }
                                                table { width: 100%; border-collapse: collapse; }
                                                td, th { padding: 1.5px 0; }
                                                .text-end { text-align: right; }
                                                .text-center { text-align: center; }
                                                .text-start { text-align: left; }
                                                .no-print { display: none !important; }
                                                img { 
                                                    max-width: 150px; 
                                                    max-height: 48px; 
                                                    object-fit: contain; 
                                                    display: block; 
                                                    margin: 0 auto 4px auto; 
                                                    filter: grayscale(100%) contrast(150%);
                                                }
                                                #recStatusBadge {
                                                    display: inline-block;
                                                    padding: 2px 6px;
                                                    border: 1px solid #000;
                                                    font-size: 10px;
                                                    font-weight: 800;
                                                }
                                            </style>
                                            </head>
                                        <body>
                                            ${content}
                                        </body>
                                        </html>`);
                iframeDoc.close();

                setTimeout(() => {
                    try {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                    } catch (e) {
                        // Fallback to window.open if iframe printing restricted
                        const printWin = window.open('', '_blank', 'width=400,height=600');
                        if (printWin) {
                            printWin.document.write(iframeDoc.documentElement.outerHTML);
                            printWin.document.close();
                            printWin.focus();
                            setTimeout(() => { printWin.print(); printWin.close(); }, 300);
                        }
                    }
                }, 300);
            }

            // ═══════════════════════════════════════════════
            // PENGATURAN MODUL POS & STAF KASIR
            // ═══════════════════════════════════════════════
            let currentPosLogoUrl = {!! !empty($posLogoUrl) ? json_encode($posLogoUrl) : 'null' !!};
            let storeNameText = {!! json_encode($storeName) !!};
            let storeAddressText = {!! json_encode($posStoreAddress ?: ($profile?->address ?? '')) !!};
            let storePhoneText = {!! json_encode($posStorePhone ?: ($profile?->phone ?? '')) !!};

            // Buka Pop-up Pengaturan POS (Dengan efek Shimmer Skeleton Premium)
            function openPosSettingsModal(initialTab = 'struk') {
                window.location.href = "{{ route('creator.pos.settings') }}" + (initialTab ? '?tab=' + initialTab : '');
                return;
                switchSettingTab(initialTab);
                openModal('posSettingsModal');

                const skel = document.getElementById('posSettingsSkeleton');
                const real = document.getElementById('posSettingsRealContent');
                if (skel && real) {
                    skel.style.display = 'flex';
                    real.style.opacity = '0';
                    real.style.display = 'none';
                    setTimeout(() => {
                        skel.style.display = 'none';
                        real.style.display = 'block';
                        setTimeout(() => {
                            real.style.opacity = '1';
                        }, 30);
                    }, 220);
                }
            }

            // Tab Switching Pengaturan (5 Tab: Struk, Fees, Discounts, Staff, Tables)
            // Konfirmasi Pembayaran QRIS Statis Selesai
            function confirmStaticQrisDone() {
                closeModal('posStaticQrisModal');
                if (window.currentStaticQrisOrder) {
                    showPosToast('Pembayaran QRIS berhasil diterima!', 'success');
                    showReceiptModal(window.currentStaticQrisOrder);
                    window.currentStaticQrisOrder = null;
                }
                clearCart();
            }

            // Handler Pengaturan Mode Cashless & Beban Fee
            function onCashlessModeChange(mode) {
                posCashlessMode = mode;
                const cardSnap = document.getElementById('cardModeSnap');
                const cardStatic = document.getElementById('cardModeStatic');
                const uploadPanel = document.getElementById('staticQrisUploadPanel');

                if (cardSnap && cardStatic) {
                    cardSnap.style.borderColor = mode === 'snap_midtrans' ? '#16a34a' : '#e2e8f0';
                    cardSnap.style.background = mode === 'snap_midtrans' ? '#f0fdf4' : '#ffffff';
                    cardStatic.style.borderColor = mode === 'qris_static' ? '#16a34a' : '#e2e8f0';
                    cardStatic.style.background = mode === 'qris_static' ? '#f0fdf4' : '#ffffff';
                }
                if (uploadPanel) {
                    uploadPanel.style.display = mode === 'qris_static' ? 'block' : 'none';
                }
            }

            function onFeePayerChange(payer) {
                posFeePayer = payer;
                const cardSeller = document.getElementById('cardFeePayerSeller');
                const cardBuyer = document.getElementById('cardFeePayerBuyer');
                if (cardSeller && cardBuyer) {
                    cardSeller.style.borderColor = payer === 'seller' ? '#16a34a' : '#e2e8f0';
                    cardSeller.style.background = payer === 'seller' ? '#f0fdf4' : '#ffffff';
                    cardBuyer.style.borderColor = payer === 'buyer' ? '#16a34a' : '#e2e8f0';
                    cardBuyer.style.background = payer === 'buyer' ? '#f0fdf4' : '#ffffff';
                }
                calculateTotals();
            }

            function handleQrisFileSelect(event) {
                const file = event.target.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('qris_image', file);
                formData.append('_token', '{{ csrf_token() }}');

                showPosToast('Mengunggah & mengonversi QRIS ke WebP...', 'info');

                fetch('{{ route("creator.pos.upload-qris") }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            posQrisUrl = data.qris_url;
                            const prevWrap = document.getElementById('qrisPreviewContainer');
                            if (prevWrap) {
                                prevWrap.innerHTML = `<img src="${data.qris_url}" id="qrisPreviewImg" style="width:100%; height:100%; object-fit:contain; background:#ffffff;">`;
                            }
                            const btnRemove = document.getElementById('btnRemoveQris');
                            if (btnRemove) btnRemove.style.display = 'inline-flex';
                            showPosToast(data.message, 'success');
                        } else {
                            showPosAlert(data.message || 'Gagal mengunggah QRIS.', 'Error', 'error');
                        }
                    })
                    .catch(() => showPosAlert('Terjadi kesalahan jaringan saat mengunggah.', 'Error', 'error'));
            }

            function removeQrisImage() {
                if (!confirm('Apakah Anda yakin ingin menghapus gambar QRIS toko?')) return;

                fetch('{{ route("creator.pos.remove-qris") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            posQrisUrl = null;
                            const prevWrap = document.getElementById('qrisPreviewContainer');
                            if (prevWrap) {
                                prevWrap.innerHTML = `
                                            <div id="qrisPreviewPlaceholder" style="text-align:center; padding:8px; color:#94a3b8; font-size:0.68rem;">
                                                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="margin:0 auto 4px auto; display:block;">
                                                    <rect x="3" y="3" width="7" height="7"></rect>
                                                    <rect x="14" y="3" width="7" height="7"></rect>
                                                    <rect x="14" y="14" width="7" height="7"></rect>
                                                    <rect x="3" y="14" width="7" height="7"></rect>
                                                </svg>
                                                Belum ada QRIS
                                            </div>
                                        `;
                            }
                            const btnRemove = document.getElementById('btnRemoveQris');
                            if (btnRemove) btnRemove.style.display = 'none';
                            showPosToast(data.message, 'success');
                        } else {
                            showPosAlert(data.message || 'Gagal menghapus QRIS.', 'Error', 'error');
                        }
                    })
                    .catch(() => showPosAlert('Terjadi kesalahan jaringan saat menghapus.', 'Error', 'error'));
            }

            function savePaymentSettings() {
                const btn = document.getElementById('btnSavePaymentSettings');
                if (btn) {
                    btn.disabled = true;
                    btn.innerText = 'Menyimpan...';
                }

                const payload = {
                    pos_cashless_mode: posCashlessMode,
                    pos_fee_payer: posFeePayer,
                };

                fetch('{{ route("creator.pos.update-settings") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                })
                    .then(res => res.json())
                    .then(data => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = `<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12" /></svg> Simpan Pengaturan Pembayaran`;
                        }
                        if (data.success) {
                            showPosToast('Pengaturan Pembayaran & Fee berhasil disimpan!', 'success');
                            calculateTotals();
                            closeModal('posSettingsModal');
                        } else {
                            showPosAlert(data.message || 'Gagal menyimpan pengaturan.', 'Error', 'error');
                        }
                    })
                    .catch(() => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = `<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12" /></svg> Simpan Pengaturan Pembayaran`;
                        }
                        showPosAlert('Terjadi kesalahan jaringan.', 'Error', 'error');
                    });
            }

            function switchSettingTab(tabId) {
                const tabs = ['struk', 'fees', 'discounts', 'staff', 'tables', 'payment'];
                const btnMap = {
                    struk: 'tabBtnSettingStruk',
                    fees: 'tabBtnSettingFees',
                    discounts: 'tabBtnSettingDiscounts',
                    staff: 'tabBtnSettingStaff',
                    tables: 'tabBtnSettingTables',
                    payment: 'tabBtnSettingPayment',
                };
                const paneMap = {
                    struk: 'paneSettingStruk',
                    fees: 'paneSettingFees',
                    discounts: 'paneSettingDiscounts',
                    staff: 'paneSettingStaff',
                    tables: 'paneSettingTables',
                    payment: 'paneSettingPayment',
                };
                tabs.forEach(t => {
                    const btn = document.getElementById(btnMap[t]);
                    const pane = document.getElementById(paneMap[t]);
                    if (btn) btn.classList.toggle('active', t === tabId);
                    if (pane) pane.style.display = t === tabId ? 'block' : 'none';
                });

                // Render the appropriate pane when switching
                if (tabId === 'fees') renderFeeSettingsList();
                if (tabId === 'discounts') {
                    renderDiscountSettingsList();
                    renderDiscountProductPicker();
                }
                if (tabId === 'tables') {
                    renderTablesSettingsList();
                }
            }

            // ═══════════════════════════════════════════════
            // FEES MANAGEMENT (Tab Pajak & Biaya)
            // ═══════════════════════════════════════════════

            function renderFeeSettingsList() {
                const container = document.getElementById('feeRowsListContainer');
                if (!container) return;

                if (!posFeesData || posFeesData.length === 0) {
                    container.innerHTML = `
                                            <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.82rem;">
                                                Belum ada biaya / pajak ditambahkan.<br>Klik <strong>+ Tambah Biaya / Pajak</strong> untuk menambah.
                                            </div>`;
                    renderFeeSimulation();
                    updateFeesBadgeCount();
                    return;
                }

                container.innerHTML = posFeesData.map((fee, idx) => `
                                        <div style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; gap:10px; transition:all 0.2s ease;">
                                            <div style="display:flex; align-items:center; gap:10px; flex:1; min-width:0;">
                                                <label style="display:flex; align-items:center; gap:6px; cursor:pointer; margin:0;">
                                                    <input type="checkbox" ${fee.is_active ? 'checked' : ''}
                                                        onchange="toggleFeeActive(${idx}, this.checked)"
                                                        style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer; flex-shrink:0;">
                                                </label>
                                                <div style="min-width:0;">
                                                    <div style="font-size:0.82rem; font-weight:800; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                        ${escapeHtml(fee.name)}
                                                    </div>
                                                    <div style="font-size:0.7rem; color:#64748b; margin-top:1px;">
                                                        ${fee.type === 'percent' ? fee.value + '%' : 'Rp ' + formatRupiah(fee.value)} (${fee.type === 'percent' ? 'Persentase' : 'Nominal Tetap'})
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                                <input type="number" value="${fee.value}" min="0" step="any"
                                                    style="width:70px; height:30px; border:1px solid #e2e8f0; border-radius:6px; padding:0 6px; font-size:0.78rem; font-weight:600; text-align:right;"
                                                    onchange="updateFeeValue(${idx}, this.value)">
                                                <select onchange="updateFeeType(${idx}, this.value)"
                                                    style="height:30px; border:1px solid #e2e8f0; border-radius:6px; padding:0 4px; font-size:0.72rem; font-weight:600; color:#334155;">
                                                    <option value="percent" ${fee.type === 'percent' ? 'selected' : ''}>%</option>
                                                    <option value="nominal" ${fee.type === 'nominal' ? 'selected' : ''}>Rp</option>
                                                </select>
                                                <button onclick="removeFeeRow(${idx})"
                                                    style="background:#fee2e2; border:1px solid #fecaca; color:#dc2626; border-radius:6px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; font-size:0.9rem;">×</button>
                                            </div>
                                        </div>
                                    `).join('');

                renderFeeSimulation();
                updateFeesBadgeCount();
            }

            function addFeeRow() {
                posFeesData.push({ name: 'Biaya Baru', type: 'percent', value: 0, is_active: true });
                renderFeeSettingsList();
                const container = document.getElementById('feeRowsListContainer');
                if (container) {
                    const cards = container.querySelectorAll('[style*="border-radius:12px"]');
                    if (cards.length > 0) {
                        const lastCard = cards[cards.length - 1];
                        lastCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                        // Focus name input via inline edit on click
                        const nameEl = lastCard.querySelector('div[style*="font-weight:800"]');
                        if (nameEl) {
                            nameEl.contentEditable = 'true';
                            nameEl.focus();
                            nameEl.onblur = function () {
                                const newName = this.innerText.trim() || 'Biaya Baru';
                                posFeesData[posFeesData.length - 1].name = newName;
                                this.contentEditable = 'false';
                                renderFeeSettingsList();
                            };
                            // Select all text
                            const range = document.createRange();
                            range.selectNodeContents(nameEl);
                            window.getSelection().removeAllRanges();
                            window.getSelection().addRange(range);
                        }
                    }
                }
            }

            function toggleFeeActive(idx, active) {
                if (posFeesData[idx]) {
                    posFeesData[idx].is_active = active;
                    renderFeeSimulation();
                    updateFeesBadgeCount();
                    renderCartFees();
                    calculateTotals();
                }
            }

            function updateFeeValue(idx, val) {
                if (posFeesData[idx]) {
                    posFeesData[idx].value = parseFloat(val) || 0;
                    renderFeeSimulation();
                    renderCartFees();
                    calculateTotals();
                }
            }

            function updateFeeType(idx, type) {
                if (posFeesData[idx]) {
                    posFeesData[idx].type = type;
                    renderFeeSimulation();
                    renderCartFees();
                    calculateTotals();
                }
            }

            function removeFeeRow(idx) {
                posFeesData.splice(idx, 1);
                renderFeeSettingsList();
                renderCartFees();
                calculateTotals();
            }

            function renderFeeSimulation() {
                const simEl = document.getElementById('feeLiveSimulationText');
                if (!simEl) return;
                const exampleSubtotal = 100000;
                let lines = [];
                let totalCustomFee = 0;
                (posFeesData || []).forEach(fee => {
                    if (!fee.is_active) return;
                    let amount = fee.type === 'percent'
                        ? (exampleSubtotal * (parseFloat(fee.value) || 0)) / 100
                        : (parseFloat(fee.value) || 0);
                    totalCustomFee += amount;
                    lines.push(`${escapeHtml(fee.name)}: +Rp ${formatRupiah(amount)}`);
                });
                const systemFee = (exampleSubtotal * (platformFeeRate + adminFeeRate)) / 100;
                const grandTotal = exampleSubtotal + totalCustomFee + systemFee;
                if (lines.length === 0) {
                    simEl.innerHTML = `<span style="color:#94a3b8;">Tidak ada biaya tambahan aktif.</span>`;
                } else {
                    simEl.innerHTML = `
                                            <div>Subtotal: Rp ${formatRupiah(exampleSubtotal)}</div>
                                            ${lines.map(l => `<div style="color:#0369a1;">+ ${l}</div>`).join('')}
                                            <div style="color:#64748b; font-size:0.72rem;">+ Platform/Admin Fee: Rp ${formatRupiah(systemFee)}</div>
                                            <div style="margin-top:4px; padding-top:4px; border-top:1px dashed #cbd5e1; font-weight:900; color:#1eb349;">
                                                Grand Total: Rp ${formatRupiah(grandTotal)}
                                            </div>`;
                }
            }

            function updateFeesBadgeCount() {
                const active = (posFeesData || []).filter(f => f.is_active).length;
                const badge = document.getElementById('settingFeesBadgeCount');
                if (badge) badge.innerText = active;
            }

            function savePosFeesSettings() {
                const btn = document.getElementById('btnSaveFeesSettings');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" style="animation:spin 0.8s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Menyimpan...`;
                }
                fetch('{{ route("creator.pos.update-settings") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ pos_fees: posFeesData })
                })
                    .then(r => r.json())
                    .then(data => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Simpan Pengaturan Biaya & Pajak`;
                        }
                        if (data.success) {
                            showPosToast('Pengaturan biaya & pajak berhasil disimpan!', 'success');
                            renderCartFees();
                            calculateTotals();
                        } else {
                            showPosAlert(data.message || 'Gagal menyimpan.', 'Error', 'error');
                        }
                    })
                    .catch(() => {
                        if (btn) { btn.disabled = false; btn.innerText = 'Simpan Pengaturan Biaya & Pajak'; }
                        showPosAlert('Terjadi kesalahan jaringan.', 'Error', 'error');
                    });
            }

            // Render dynamic fee rows inside cart summary box
            function renderCartFees() {
                const container = document.getElementById('cartFeesContainer');
                if (!container) return;
                const activeFees = (posFeesData || []).filter(f => f.is_active);
                if (activeFees.length === 0) {
                    container.style.display = 'none';
                    container.innerHTML = '';
                    return;
                }
                container.style.display = 'block';
                const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
                container.innerHTML = activeFees.map(fee => {
                    const amount = fee.type === 'percent'
                        ? (subtotal * (parseFloat(fee.value) || 0)) / 100
                        : (parseFloat(fee.value) || 0);
                    return `<div class="summary-row" style="font-size:0.78rem; color:#475569;">
                                            <span>${escapeHtml(fee.name)}${fee.type === 'percent' ? ' (' + fee.value + '%)' : ''}</span>
                                            <span style="font-weight:600;">+Rp ${formatRupiah(amount)}</span>
                                        </div>`;
                }).join('');
            }

            // ═══════════════════════════════════════════════
            // DISCOUNTS MANAGEMENT (Tab Diskon & Promo)
            // ═══════════════════════════════════════════════

            function renderDiscountSettingsList() {
                const container = document.getElementById('discountListContainer');
                if (!container) return;

                if (!posDiscountsData || posDiscountsData.length === 0) {
                    container.innerHTML = `
                                            <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.82rem;">
                                                Belum ada promo / diskon dibuat.<br>Klik <strong>+ Buat Diskon Baru</strong> untuk membuat promo pertama.
                                            </div>`;
                    updateDiscountsBadgeCount();
                    return;
                }

                container.innerHTML = posDiscountsData.map((disc, idx) => {
                    const scopeLabel = disc.scope === 'all'
                        ? 'Semua Produk'
                        : `${(disc.product_ids || []).length} Produk Dipilih`;
                    const valueLabel = disc.type === 'percent'
                        ? disc.value + '%'
                        : 'Rp ' + formatRupiah(disc.value);
                    return `
                                        <div id="discountCard-${idx}" style="background:#ffffff; border:1.5px solid ${disc.is_active ? '#bbf7d0' : '#e2e8f0'}; border-radius:12px; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; gap:10px; transition:all 0.2s ease;">
                                            <div style="display:flex; align-items:center; gap:10px; flex:1; min-width:0;">
                                                <label style="display:flex; align-items:center; gap:6px; cursor:pointer; margin:0;">
                                                    <input type="checkbox" ${disc.is_active ? 'checked' : ''}
                                                        onchange="toggleDiscountActive(${idx}, this.checked)"
                                                        style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer; flex-shrink:0;">
                                                </label>
                                                <div style="min-width:0;">
                                                    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                                        <span style="font-size:0.82rem; font-weight:800; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${escapeHtml(disc.name)}</span>
                                                        <span style="font-size:0.68rem; background:${disc.is_active ? '#dcfce7' : '#f1f5f9'}; color:${disc.is_active ? '#15803d' : '#64748b'}; font-weight:700; padding:1px 7px; border-radius:999px; flex-shrink:0;">${disc.is_active ? 'Aktif' : 'Nonaktif'}</span>
                                                    </div>
                                                    <div style="font-size:0.7rem; color:#64748b; margin-top:2px;">
                                                        Potongan <strong>${valueLabel}</strong> &nbsp;·&nbsp; ${scopeLabel}
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                                <button onclick="openEditDiscountDrawer(${idx})"
                                                    style="background:#f0f9ff; border:1px solid #bae6fd; color:#0369a1; border-radius:6px; padding:4px 9px; font-size:0.7rem; font-weight:700; cursor:pointer;">Edit</button>
                                                <button onclick="removeDiscount(${idx})"
                                                    style="background:#fee2e2; border:1px solid #fecaca; color:#dc2626; border-radius:6px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; font-size:0.9rem;">×</button>
                                            </div>
                                        </div>`;
                }).join('');
                updateDiscountsBadgeCount();
                // Also update the cart preset dropdown
                renderDiscountPresetDropdown();
            }

            function updateDiscountsBadgeCount() {
                const active = (posDiscountsData || []).filter(d => d.is_active).length;
                const badge = document.getElementById('settingDiscountsBadgeCount');
                if (badge) badge.innerText = active;
            }

            function toggleDiscountActive(idx, active) {
                if (posDiscountsData[idx]) {
                    posDiscountsData[idx].is_active = active;
                    renderDiscountSettingsList();
                    renderDiscountPresetDropdown();
                    calculateTotals();
                }
            }

            function removeDiscount(idx) {
                posDiscountsData.splice(idx, 1);
                renderDiscountSettingsList();
                renderDiscountPresetDropdown();
                calculateTotals();
            }

            // ── Discount Drawer ──
            let discountEditIndex = -1;

            function openAddDiscountDrawer() {
                discountEditIndex = -1;
                const title = document.getElementById('discountDrawerTitle');
                if (title) title.innerText = 'Tambah Diskon / Promo Baru';
                document.getElementById('discountEditId').value = '';
                document.getElementById('discountInputName').value = '';
                document.getElementById('discountInputType').value = 'percent';
                document.getElementById('discountInputValue').value = '';
                document.getElementById('discountValueSuffix').innerText = '%';
                document.getElementById('discountInputActive').checked = true;
                document.querySelector('input[name="discountScopeRadio"][value="all"]').checked = true;
                toggleDiscountScopeUI('all');
                deselectAllDiscountProducts();
                const drawer = document.getElementById('discountDrawerForm');
                if (drawer) { drawer.style.display = 'block'; drawer.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
            }

            function openEditDiscountDrawer(idx) {
                const disc = posDiscountsData[idx];
                if (!disc) return;
                discountEditIndex = idx;
                const title = document.getElementById('discountDrawerTitle');
                if (title) title.innerText = 'Edit Diskon: ' + disc.name;
                document.getElementById('discountEditId').value = idx;
                document.getElementById('discountInputName').value = disc.name;
                document.getElementById('discountInputType').value = disc.type || 'percent';
                document.getElementById('discountInputValue').value = disc.value;
                document.getElementById('discountValueSuffix').innerText = disc.type === 'percent' ? '%' : 'Rp';
                document.getElementById('discountInputActive').checked = !!disc.is_active;
                const scope = disc.scope || 'all';
                document.querySelector(`input[name="discountScopeRadio"][value="${scope}"]`).checked = true;
                toggleDiscountScopeUI(scope);
                // Re-check selected products
                const selectedIds = new Set((disc.product_ids || []).map(Number));
                document.querySelectorAll('#discountProductPickerList input[type="checkbox"]').forEach(cb => {
                    cb.checked = selectedIds.has(Number(cb.value));
                });
                updateDiscountSelectedCount();
                const drawer = document.getElementById('discountDrawerForm');
                if (drawer) { drawer.style.display = 'block'; drawer.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
            }

            function closeDiscountDrawer() {
                const drawer = document.getElementById('discountDrawerForm');
                if (drawer) drawer.style.display = 'none';
                discountEditIndex = -1;
            }

            function onDiscountTypeChanged() {
                const type = document.getElementById('discountInputType').value;
                const suffix = document.getElementById('discountValueSuffix');
                if (suffix) suffix.innerText = type === 'percent' ? '%' : 'Rp';
            }

            function toggleDiscountScopeUI(scope) {
                const wrap = document.getElementById('discountProductPickerWrap');
                if (wrap) wrap.style.display = scope === 'specific' ? 'block' : 'none';
            }

            function handleSaveDiscount(e) {
                e.preventDefault();
                const name = document.getElementById('discountInputName').value.trim();
                const type = document.getElementById('discountInputType').value;
                const value = parseFloat(document.getElementById('discountInputValue').value) || 0;
                const isActive = document.getElementById('discountInputActive').checked;
                const scope = document.querySelector('input[name="discountScopeRadio"]:checked')?.value || 'all';
                const productIds = scope === 'specific'
                    ? Array.from(document.querySelectorAll('#discountProductPickerList input[type="checkbox"]:checked')).map(cb => Number(cb.value))
                    : [];

                if (!name) { showPosAlert('Nama promo tidak boleh kosong.', 'Nama Wajib', 'warning'); return; }
                if (type === 'percent' && value > 100) { showPosAlert('Diskon persen tidak boleh melebihi 100%.', 'Nilai Tidak Valid', 'warning'); return; }
                if (scope === 'specific' && productIds.length === 0) { showPosAlert('Pilih minimal 1 produk untuk diskon spesifik.', 'Produk Wajib Dipilih', 'warning'); return; }

                const discObj = { name, type, value, scope, product_ids: productIds, is_active: isActive };

                if (discountEditIndex >= 0) {
                    posDiscountsData[discountEditIndex] = discObj;
                } else {
                    posDiscountsData.push(discObj);
                }
                // Save to server
                fetch('{{ route("creator.pos.update-settings") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ pos_discounts: posDiscountsData })
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showPosToast('Promo diskon berhasil disimpan!', 'success');
                            closeDiscountDrawer();
                            renderDiscountSettingsList();
                            renderDiscountPresetDropdown();
                            calculateTotals();
                        } else {
                            showPosAlert(data.message || 'Gagal menyimpan.', 'Error', 'error');
                        }
                    })
                    .catch(() => showPosAlert('Terjadi kesalahan jaringan.', 'Error', 'error'));
            }

            // ── Product Picker for Discount ──
            function renderDiscountProductPicker() {
                const list = document.getElementById('discountProductPickerList');
                if (!list) return;
                const products = window.posAllProducts || [];
                if (products.length === 0) {
                    list.innerHTML = `<div style="font-size:0.78rem; color:#94a3b8; text-align:center; padding:1rem;">Tidak ada produk tersedia.</div>`;
                    return;
                }
                list.innerHTML = products.map(p => `
                                        <label style="display:flex; align-items:center; gap:8px; font-size:0.78rem; font-weight:600; color:#1e293b; cursor:pointer; padding:5px 4px; border-radius:6px; transition:background 0.15s;">
                                            <input type="checkbox" value="${p.id}"
                                                style="width:15px; height:15px; accent-color:#1eb349; cursor:pointer; flex-shrink:0;"
                                                onchange="updateDiscountSelectedCount()">
                                            <span>${escapeHtml(p.name)}</span>
                                        </label>
                                    `).join('');
            }

            function filterDiscountProductPicker() {
                const q = (document.getElementById('discountProductPickerSearch')?.value || '').toLowerCase().trim();
                document.querySelectorAll('#discountProductPickerList label').forEach(label => {
                    const name = label.querySelector('span')?.innerText?.toLowerCase() || '';
                    label.style.display = !q || name.includes(q) ? '' : 'none';
                });
            }

            function selectAllDiscountProducts() {
                document.querySelectorAll('#discountProductPickerList input[type="checkbox"]').forEach(cb => { cb.checked = true; });
                updateDiscountSelectedCount();
            }

            function deselectAllDiscountProducts() {
                document.querySelectorAll('#discountProductPickerList input[type="checkbox"]').forEach(cb => { cb.checked = false; });
                updateDiscountSelectedCount();
            }

            function updateDiscountSelectedCount() {
                const count = document.querySelectorAll('#discountProductPickerList input[type="checkbox"]:checked').length;
                const badge = document.getElementById('discountSelectedCountBadge');
                if (badge) badge.innerText = count + ' produk dipilih';
            }

            // ── Cart Preset Dropdown ──
            function renderDiscountPresetDropdown() {
                const sel = document.getElementById('posDiscountPresetSelect');
                const wrap = document.getElementById('cartDiscountPresetWrap');
                if (!sel || !wrap) return;
                const activeDiscounts = (posDiscountsData || []).filter(d => d.is_active);
                if (activeDiscounts.length === 0) {
                    wrap.style.display = 'none';
                    sel.innerHTML = '<option value="">Tanpa Diskon</option>';
                    return;
                }
                wrap.style.display = '';
                sel.innerHTML = '<option value="">Tanpa Diskon</option>' +
                    activeDiscounts.map((d, i) => {
                        const label = `${d.name} (${d.type === 'percent' ? d.value + '%' : 'Rp ' + formatRupiah(d.value)})`;
                        return `<option value="${i}">${label}</option>`;
                    }).join('');
            }

            function onDiscountPresetChanged() {
                const sel = document.getElementById('posDiscountPresetSelect');
                const idx = sel ? parseInt(sel.value) : NaN;
                const activeDiscounts = (posDiscountsData || []).filter(d => d.is_active);
                const discInput = document.getElementById('posDiscountInput');
                if (!isNaN(idx) && activeDiscounts[idx]) {
                    const disc = activeDiscounts[idx];
                    setDiscountType(disc.type === 'percent' ? 'percent' : 'nominal');
                    if (discInput) discInput.value = disc.value;
                    window._selectedDiscountPreset = disc;
                } else {
                    window._selectedDiscountPreset = null;
                    if (discInput) discInput.value = 0;
                }
                calculateTotals();
            }

            // Live Preview Struk di Tab 1
            function liveUpdateReceiptPreview() {
                const name = (document.getElementById('setPosStoreName')?.value || '').trim() || storeNameText;
                const addr = (document.getElementById('setPosStoreAddress')?.value || '').trim();
                const phone = (document.getElementById('setPosStorePhone')?.value || '').trim();

                const prevName = document.getElementById('prevHeaderName');
                const prevAddr = document.getElementById('prevHeaderAddress');
                const prevPhone = document.getElementById('prevHeaderPhone');

                if (prevName) prevName.innerText = name;
                if (prevAddr) prevAddr.innerText = addr || 'Alamat toko belum diatur';
                if (prevPhone) prevPhone.innerText = phone ? ('No. Telp ' + phone) : 'No. Telp: -';
            }

            // Simpan Pengaturan Struk Toko (Nama, Alamat, No Telp)
            function savePosStoreSettings() {
                const name = (document.getElementById('setPosStoreName')?.value || '').trim();
                const address = (document.getElementById('setPosStoreAddress')?.value || '').trim();
                const phone = (document.getElementById('setPosStorePhone')?.value || '').trim();

                if (!name) {
                    showPosAlert('Nama toko struk tidak boleh kosong.', 'Nama Toko Wajib', 'warning');
                    return;
                }

                const btn = document.getElementById('btnSaveStoreSettings');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" style="animation:spin 0.8s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Menyimpan...`;
                }

                fetch('{{ route("creator.pos.update-settings") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        store_name: name,
                        pos_store_address: address,
                        pos_store_phone: phone
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Simpan Pengaturan`;
                        }
                        if (data.success) {
                            storeNameText = name;
                            storeAddressText = address;
                            storePhoneText = phone;

                            // Update main thermal receipt display
                            const recName = document.getElementById('recHeaderStoreName');
                            const recAddr = document.getElementById('recHeaderStoreAddress');
                            const recPhone = document.getElementById('recHeaderStorePhone');
                            if (recName) recName.innerText = name;
                            if (recAddr) recAddr.innerText = address;
                            if (recPhone) recPhone.innerText = phone ? ('No. Telp ' + phone) : '';

                            showPosToast('Pengaturan struk & profil toko berhasil disimpan!', 'success');
                            closeModal('posSettingsModal'); // Menutup pop-up saat tombol Simpan ditekan
                        } else {
                            showPosAlert(data.message || 'Gagal menyimpan pengaturan.', 'Gagal Simpan', 'error');
                        }
                    })
                    .catch(err => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Simpan Pengaturan`;
                        }
                        showPosAlert('Terjadi kesalahan jaringan: ' + err.message, 'Gagal Simpan', 'error');
                    });
            }

            // Preview Logo yang dipilih
            function previewPosLogoSelected(input) {
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    if (file.size > 3 * 1024 * 1024) {
                        showPosAlert('Ukuran file maksimal 3MB.', 'File Terlalu Besar', 'warning');
                        input.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const container = document.getElementById('posLogoPreviewContainer');
                        if (container) {
                            container.innerHTML = `<img id="posLogoPreviewImg" src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                        }
                        const prevLogo = document.getElementById('prevHeaderLogo');
                        if (prevLogo) {
                            prevLogo.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-height:36px; max-width:120px; object-fit:contain; margin:0 auto; display:block;">`;
                        }
                        const btnSave = document.getElementById('btnSavePosLogo');
                        if (btnSave) btnSave.style.display = 'inline-flex';
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Handle Upload Logo Struk
            function handlePosLogoUpload(event) {
                event.preventDefault();
                const fileInput = document.getElementById('posLogoFileInput');
                if (!fileInput.files || !fileInput.files[0]) {
                    showPosAlert('Silakan pilih file logo terlebih dahulu.', 'Pilih File', 'warning');
                    return;
                }

                const btnSave = document.getElementById('btnSavePosLogo');
                if (btnSave) {
                    btnSave.disabled = true;
                    btnSave.innerText = 'Mengupload...';
                }

                const formData = new FormData();
                formData.append('logo', fileInput.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("creator.pos.upload-logo") }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (btnSave) {
                            btnSave.disabled = false;
                            btnSave.innerText = 'Upload & Pasang';
                            btnSave.style.display = 'none';
                        }
                        if (data.success && data.logo_url) {
                            currentPosLogoUrl = data.logo_url;
                            updateReceiptHeaderLogo(data.logo_url);
                            const delWrap = document.getElementById('posLogoDeleteWrap');
                            if (delWrap) delWrap.style.display = 'block';
                            showPosToast('Logo struk kasir berhasil dipasang!', 'success');
                        } else {
                            showPosAlert(data.message || 'Gagal mengupload logo struk.', 'Gagal Upload', 'error');
                        }
                    })
                    .catch(err => {
                        if (btnSave) {
                            btnSave.disabled = false;
                            btnSave.innerText = 'Upload & Pasang';
                        }
                        showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal Upload', 'error');
                    });
            }

            // Handle Hapus Logo Struk
            function handlePosLogoDelete() {
                if (!confirm('Yakin ingin menghapus logo struk dan kembali menggunakan icon standar?')) return;

                fetch('{{ route("creator.pos.remove-logo") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            currentPosLogoUrl = null;
                            updateReceiptHeaderLogo(null);
                            const container = document.getElementById('posLogoPreviewContainer');
                            if (container) {
                                container.innerHTML = `<span id="posLogoPreviewEmpty" style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-align:center;">Belum ada logo</span>`;
                            }
                            const prevLogo = document.getElementById('prevHeaderLogo');
                            if (prevLogo) prevLogo.innerHTML = '';
                            const delWrap = document.getElementById('posLogoDeleteWrap');
                            if (delWrap) delWrap.style.display = 'none';
                            const fileInput = document.getElementById('posLogoFileInput');
                            if (fileInput) fileInput.value = '';
                            const btnSave = document.getElementById('btnSavePosLogo');
                            if (btnSave) btnSave.style.display = 'none';
                            showPosToast(data.message || 'Logo struk berhasil dihapus.', 'success');
                        } else {
                            showPosAlert(data.message || 'Gagal menghapus logo.', 'Gagal', 'error');
                        }
                    })
                    .catch(err => {
                        showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal', 'error');
                    });
            }

            function updateReceiptHeaderLogo(logoUrl) {
                const wrap = document.getElementById('recHeaderLogoWrap');
                if (!wrap) return;
                if (logoUrl) {
                    wrap.innerHTML = `<img src="${logoUrl}" alt="Logo Toko" id="recHeaderLogoImg" style="max-height: 48px; max-width: 140px; object-fit: contain; margin: 0 auto 3px auto; display: block;">`;
                } else {
                    wrap.innerHTML = `<svg id="recHeaderLogoFallbackSvg" width="38" height="38" viewBox="0 0 24 24" fill="currentColor" style="display:inline-block; margin:0 auto 2px auto; color:#000000;"><path d="M4 4h16a1 1 0 0 1 1 1v2a3 3 0 0 1-3 3 3 3 0 0 1-3-3 3 3 0 0 1-3 3 3 3 0 0 1-3-3 3 3 0 0 1-3-3V5a1 1 0 0 1 1-1zm0 8v9a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-9h-2v7H6v-7H4zm5 1h6v6H9v-6z"/></svg>`;
                }
            }

            // ═══════════════════════════════════════════════
            // KELOLA STAF KASIR & SHIFT (AJAX CRUD)
            // ═══════════════════════════════════════════════

            function syncCashierShiftTime() {
                const start = document.getElementById('cashierShiftStart')?.value || '08:00';
                const end = document.getElementById('cashierShiftEnd')?.value || '16:00';
                const hidden = document.getElementById('cashierInputShift');
                if (hidden) hidden.value = start + ' - ' + end;
            }

            function setCashierShiftPreset(start, end) {
                const sInput = document.getElementById('cashierShiftStart');
                const eInput = document.getElementById('cashierShiftEnd');
                if (sInput) sInput.value = start;
                if (eInput) eInput.value = end;
                syncCashierShiftTime();
            }

            function openAddCashierDrawer() {
                document.getElementById('cashierDrawerTitle').innerText = 'Tambah Staf Kasir Baru';
                document.getElementById('cashierEditId').value = '';
                document.getElementById('cashierInputName').value = '';
                document.getElementById('cashierInputRole').value = 'Kasir';
                document.getElementById('cashierInputEmail').value = '';
                document.getElementById('cashierInputPassword').value = '';
                document.getElementById('cashierShiftStart').value = '08:00';
                document.getElementById('cashierShiftEnd').value = '16:00';
                document.getElementById('cashierInputShift').value = '08:00 - 16:00';
                document.getElementById('cashierInputActive').checked = true;

                const notice = document.getElementById('cashierPasswordNotice');
                if (notice) notice.innerText = '*';
                const helper = document.getElementById('cashierPassHelper');
                if (helper) helper.innerText = 'Wajib diisi minimal 6 karakter';

                const drawer = document.getElementById('cashierDrawerForm');
                if (drawer) {
                    drawer.style.display = 'block';
                    drawer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }

            function closeCashierDrawer() {
                const drawer = document.getElementById('cashierDrawerForm');
                if (drawer) drawer.style.display = 'none';
            }

            function editCashierData(c) {
                document.getElementById('cashierDrawerTitle').innerText = 'Edit Staf Kasir: ' + c.name;
                document.getElementById('cashierEditId').value = c.id;
                document.getElementById('cashierInputName').value = c.name || '';
                document.getElementById('cashierInputRole').value = c.cashier_role_title || 'Kasir';
                document.getElementById('cashierInputEmail').value = c.email || c.username || '';
                document.getElementById('cashierInputPassword').value = '';
                const rawShift = c.shift_hours || '08:00 - 16:00';
                document.getElementById('cashierInputShift').value = rawShift;
                if (rawShift.includes('-')) {
                    const parts = rawShift.split('-');
                    document.getElementById('cashierShiftStart').value = parts[0].trim().substring(0, 5) || '08:00';
                    document.getElementById('cashierShiftEnd').value = parts[1].trim().substring(0, 5) || '16:00';
                } else {
                    document.getElementById('cashierShiftStart').value = '08:00';
                    document.getElementById('cashierShiftEnd').value = '16:00';
                }
                document.getElementById('cashierInputActive').checked = !!c.is_active;

                const notice = document.getElementById('cashierPasswordNotice');
                if (notice) notice.innerText = '(opsional)';
                const helper = document.getElementById('cashierPassHelper');
                if (helper) helper.innerText = 'Kosongkan jika password tidak diubah';

                const drawer = document.getElementById('cashierDrawerForm');
                if (drawer) {
                    drawer.style.display = 'block';
                    drawer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }

            function handleSaveCashier(event) {
                event.preventDefault();
                const id = document.getElementById('cashierEditId').value;
                const name = (document.getElementById('cashierInputName').value || '').trim();
                const role = (document.getElementById('cashierInputRole').value || '').trim();
                const email = (document.getElementById('cashierInputEmail').value || '').trim();
                const password = document.getElementById('cashierInputPassword').value;
                const shift = (document.getElementById('cashierInputShift').value || '').trim();
                const isActive = document.getElementById('cashierInputActive').checked ? 1 : 0;

                if (!name || !email) {
                    showPosAlert('Nama lengkap dan email login kasir wajib diisi!', 'Data Belum Lengkap', 'warning');
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showPosAlert('Format email tidak valid. Masukkan format email yang benar (contoh: kasir@tokosaya.com).', 'Format Email Salah', 'warning');
                    return;
                }

                if (!id && (!password || password.length < 6)) {
                    showPosAlert('Password wajib diisi minimal 6 karakter untuk akun kasir baru.', 'Password Terlalu Pendek', 'warning');
                    return;
                }

                const btn = document.getElementById('btnSaveCashierSubmit');
                if (btn) {
                    btn.disabled = true;
                    btn.innerText = 'Menyimpan...';
                }

                fetch('{{ route("creator.pos.cashiers.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: id || null,
                        name: name,
                        email: email,
                        password: password,
                        shift_hours: shift,
                        cashier_role_title: role,
                        is_active: isActive
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerText = 'Simpan Akun Staf';
                        }
                        if (data.success) {
                            closeCashierDrawer();
                            refreshCashierListUI(data.cashiers || []);
                            showPosToast(data.message || 'Akun staf kasir berhasil disimpan!', 'success');
                        } else {
                            showPosAlert(data.message || 'Gagal menyimpan akun kasir.', 'Gagal Simpan', 'error');
                        }
                    })
                    .catch(err => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerText = 'Simpan Akun Staf';
                        }
                        showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal Simpan', 'error');
                    });
            }

            function toggleCashierActive(cashierId) {
                const url = '{{ route("creator.pos.cashiers.toggle", ":id") }}'.replace(':id', cashierId);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showPosToast(data.message || 'Status kasir diperbarui.', 'success');
                            if (data.cashiers) refreshCashierListUI(data.cashiers);
                        } else {
                            showPosAlert(data.message || 'Gagal mengubah status kasir.', 'Gagal', 'error');
                        }
                    })
                    .catch(err => showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal', 'error'));
            }

            function deleteCashier(cashierId, cashierName) {
                if (!confirm(`Yakin ingin menghapus staf kasir "${cashierName}"? Akun ini tidak akan dapat login lagi ke modul POS.`)) return;

                const url = '{{ route("creator.pos.cashiers.delete", ":id") }}'.replace(':id', cashierId);
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showPosToast(data.message || 'Akun staf kasir berhasil dihapus.', 'success');
                            if (data.cashiers) refreshCashierListUI(data.cashiers);
                        } else {
                            showPosAlert(data.message || 'Gagal menghapus akun kasir.', 'Gagal', 'error');
                        }
                    })
                    .catch(err => showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal', 'error'));
            }

            // Render Ulang Daftar Kasir Secara Dinamis
            function refreshCashierListUI(cashiers) {
                const countBadge = document.getElementById('settingStaffBadgeCount');
                if (countBadge) countBadge.innerText = cashiers.length;

                const wrap = document.getElementById('cashierListWrap');
                if (!wrap) return;

                if (cashiers.length === 0) {
                    wrap.innerHTML = `
                                            <div id="cashierEmptyNotice" style="text-align:center; padding:2rem 1rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px;">
                                                <div style="width:40px; height:40px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 8px auto; color:#94a3b8;">
                                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                                </div>
                                                <div style="font-size:0.85rem; font-weight:700; color:#334155;">Belum Ada Akun Staf Kasir</div>
                                                <div style="font-size:0.72rem; color:#64748b; margin-top:2px;">Klik tombol "+ Tambah Staf Kasir" di atas untuk menambahkan staf yang bertugas.</div>
                                            </div>
                                        `;
                    return;
                }

                let html = '';
                cashiers.forEach(c => {
                    const initials = (c.name || 'KS').substring(0, 2).toUpperCase();
                    const isActive = !!c.is_active;
                    const statusBadge = isActive
                        ? `<span style="font-size:0.68rem; background:#dcfce7; color:#15803d; font-weight:700; padding:2px 7px; border-radius:999px;">Aktif</span>`
                        : `<span style="font-size:0.68rem; background:#f1f5f9; color:#64748b; font-weight:700; padding:2px 7px; border-radius:999px;">Nonaktif</span>`;
                    const toggleLabel = isActive ? 'Nonaktifkan' : 'Aktifkan';
                    const roleTitle = c.cashier_role_title || 'Kasir';
                    const shiftHtml = c.shift_hours
                        ? `<span style="display:inline-flex; align-items:center; gap:4px; color:#0369a1; background:#f0f9ff; padding:1px 6px; border-radius:4px; font-weight:600;"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>${escapeHtml(c.shift_hours)}</span>`
                        : '';

                    const cJson = JSON.stringify(c).replace(/"/g, '&quot;');

                    html += `
                                            <div class="cashier-card-item" id="cashierRow-${c.id}" style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between; gap:12px; transition:all 0.2s ease;">
                                                <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                                                    <div style="width:38px; height:38px; border-radius:50%; background:#f1f5f9; border:1.5px solid #cbd5e1; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:800; color:#334155; flex-shrink:0;">
                                                        ${initials}
                                                    </div>
                                                    <div style="min-width:0;">
                                                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                            <span style="font-size:0.88rem; font-weight:800; color:#0f172a;">${escapeHtml(c.name)}</span>
                                                            <span style="font-size:0.7rem; color:#0369a1; background:#f0f9ff; border:1px solid #bae6fd; padding:1px 6px; border-radius:4px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                                                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                                                ${escapeHtml(c.email || c.username || '')}
                                                            </span>
                                                            ${statusBadge}
                                                        </div>
                                                        <div style="display:flex; align-items:center; gap:12px; font-size:0.72rem; color:#64748b; margin-top:3px; flex-wrap:wrap;">
                                                            <span style="display:inline-flex; align-items:center; gap:4px; color:#475569; font-weight:600;">
                                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                                ${escapeHtml(roleTitle)}
                                                            </span>
                                                            ${shiftHtml}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                                    <button type="button" class="btn-outline-bio" style="padding:4px 9px; font-size:0.72rem; border-radius:6px;" onclick="editCashierData(${cJson})" title="Edit Kasir">
                                                        Edit
                                                    </button>
                                                    <button type="button" style="padding:4px 9px; font-size:0.72rem; border-radius:6px; border:1px solid #e2e8f0; background:#f8fafc; color:#334155; cursor:pointer; font-weight:600;" onclick="toggleCashierActive(${c.id})" title="Aktifkan / Nonaktifkan">
                                                        ${toggleLabel}
                                                    </button>
                                                    <button type="button" style="padding:4px 8px; font-size:0.72rem; border-radius:6px; border:1px solid #fee2e2; background:#fff1f2; color:#e11d48; cursor:pointer; font-weight:700;" onclick="deleteCashier(${c.id}, '${escapeHtml(c.name)}')" title="Hapus Staf">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        `;
                });
                wrap.innerHTML = html;
            }

            function escapeHtml(str) {
                return (str || '').replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
            }

            // ═══════════════════════════════════════════════
            // TABLE MANAGEMENT (Kelola Meja & Barcode QR)
            // ═══════════════════════════════════════════════

            function toggleAddTableForm(show) {
                const box = document.getElementById('formAddTableBox');
                if (!box) return;
                if (show === undefined) {
                    box.style.display = box.style.display === 'none' ? 'block' : 'none';
                } else {
                    box.style.display = show ? 'block' : 'none';
                }
                if (box.style.display === 'block') {
                    document.getElementById('newTableName')?.focus();
                }
            }

            function confirmAddTable() {
                const nameInput = document.getElementById('newTableName');
                const seatsInput = document.getElementById('newTableSeats');
                const areaInput = document.getElementById('newTableArea');

                const name = (nameInput?.value || '').trim();
                if (!name) {
                    showPosAlert('Silakan isi Nama atau Nomor Meja.', 'Nama Meja Kosong', 'warning');
                    nameInput?.focus();
                    return;
                }

                const seats = parseInt(seatsInput?.value) || 4;
                const area = (areaInput?.value || '').trim();

                if (!Array.isArray(posTablesData)) posTablesData = [];

                // Cek duplikasi nama meja
                const exists = posTablesData.some(t => (t.name || '').toLowerCase() === name.toLowerCase());
                if (exists) {
                    showPosAlert('Nama meja "' + name + '" sudah ada. Gunakan nama yang berbeda.', 'Meja Sudah Ada', 'warning');
                    return;
                }

                posTablesData.push({
                    id: 'table_' + Date.now(),
                    name: name,
                    seats: seats,
                    area: area,
                    created_at: new Date().toISOString()
                });

                // Reset form
                if (nameInput) nameInput.value = '';
                if (seatsInput) seatsInput.value = '4';
                if (areaInput) areaInput.value = '';
                toggleAddTableForm(false);

                renderTablesSettingsList();
                renderTablesQuickSelect();
                updateTablesBadgeCount();
                saveTablesSettings(false);
                showPosToast('Meja "' + name + '" berhasil ditambahkan!', 'success');
            }

            function deleteTable(idx) {
                if (!posTablesData || !posTablesData[idx]) return;
                const table = posTablesData[idx];
                if (confirm('Hapus ' + (table.name || 'meja ini') + '? Barcode QR meja ini tidak akan bisa digunakan lagi.')) {
                    posTablesData.splice(idx, 1);
                    renderTablesSettingsList();
                    renderTablesQuickSelect();
                    updateTablesBadgeCount();
                    saveTablesSettings(false);
                    showPosToast('Meja berhasil dihapus.', 'info');
                }
            }

            function updateTablesBadgeCount() {
                const count = (posTablesData || []).length;
                const badge = document.getElementById('settingTablesBadgeCount');
                if (badge) badge.innerText = count;
            }

            function renderTablesSettingsList() {
                const container = document.getElementById('tableRowsListContainer');
                if (!container) return;

                if (!posTablesData || posTablesData.length === 0) {
                    container.innerHTML = `
                                            <div style="text-align:center; padding:2.5rem 1rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px;">
                                                <div style="width:44px; height:44px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 10px auto; color:#94a3b8;">
                                                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                                        <path d="M3 9h18M9 21V9" />
                                                    </svg>
                                                </div>
                                                <div style="font-size:0.88rem; font-weight:700; color:#334155;">Belum Ada Meja Ditambahkan</div>
                                                <div style="font-size:0.75rem; color:#64748b; margin-top:3px; max-width:380px; margin-left:auto; margin-right:auto;">
                                                    Klik tombol <strong>+ Tambah Meja</strong> di atas untuk membuat meja dan men-download barcode QR tiap meja.
                                                </div>
                                            </div>
                                        `;
                    updateTablesBadgeCount();
                    return;
                }

                container.innerHTML = posTablesData.map((table, idx) => {
                    const tableUrl = posBioBaseUrl + (posBioBaseUrl.includes('?') ? '&' : '?') + 'meja=' + encodeURIComponent(table.name);
                    return `
                                            <div class="table-card-item" style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between; gap:12px; transition:all 0.2s ease;">
                                                <div style="display:flex; align-items:center; gap:12px; min-width:0; flex:1;">
                                                    <div id="tableQrThumb_${idx}" style="width:40px; height:40px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden; padding:2px;"></div>
                                                    <div style="min-width:0;">
                                                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                            <span style="font-size:0.88rem; font-weight:800; color:#0f172a;">${escapeHtml(table.name)}</span>
                                                            ${table.seats ? `<span style="font-size:0.7rem; color:#0369a1; background:#f0f9ff; border:1px solid #bae6fd; padding:1px 6px; border-radius:4px; font-weight:600;">${table.seats} Kursi</span>` : ''}
                                                            ${table.area ? `<span style="font-size:0.7rem; color:#15803d; background:#dcfce7; border:1px solid #bbf7d0; padding:1px 6px; border-radius:4px; font-weight:600;">${escapeHtml(table.area)}</span>` : ''}
                                                        </div>
                                                        <div style="font-size:0.7rem; color:#94a3b8; margin-top:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:380px;">
                                                            ${escapeHtml(tableUrl)}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                                    <button type="button" class="btn-outline-bio" onclick="downloadTableQr(${idx})"
                                                        style="padding:4px 10px; font-size:0.72rem; border-radius:6px; text-decoration:none;"
                                                        title="Download Barcode QR Meja ini">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                                        Download Barcode
                                                    </button>
                                                    <a href="${tableUrl}" target="_blank"
                                                        style="padding:4px 9px; font-size:0.72rem; border-radius:6px; border:1px solid #e2e8f0; background:#f8fafc; color:#334155; text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-weight:600; cursor:pointer;"
                                                        title="Buka Link di Tab Baru">
                                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                                        Tes Link
                                                    </a>
                                                    <button type="button" onclick="deleteTable(${idx})"
                                                        style="padding:4px 8px; font-size:0.72rem; border-radius:6px; border:1px solid #fee2e2; background:#fff1f2; color:#e11d48; cursor:pointer; font-weight:700;"
                                                        title="Hapus Meja">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        `;
                }).join('');

                // Generate thumbnails for each table
                posTablesData.forEach((table, idx) => {
                    const el = document.getElementById('tableQrThumb_' + idx);
                    if (el && typeof QRCode !== 'undefined') {
                        el.innerHTML = '';
                        const tableUrl = posBioBaseUrl + (posBioBaseUrl.includes('?') ? '&' : '?') + 'meja=' + encodeURIComponent(table.name);
                        new QRCode(el, {
                            text: tableUrl,
                            width: 44,
                            height: 44,
                            colorDark: "#0f172a",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.M
                        });
                    }
                });

                updateTablesBadgeCount();
            }

            // Quick Select Dropdown in POS Cart
            function renderTablesQuickSelect() {
                const select = document.getElementById('posTableQuickSelect');
                if (!select) return;
                const currentVal = document.getElementById('posTableNumber')?.value || '';
                let html = '<option value="">Pilih Meja...</option>';
                (posTablesData || []).forEach(table => {
                    const isSelected = table.name === currentVal ? 'selected' : '';
                    const label = table.name + (table.seats ? ` (${table.seats} Kursi)` : '') + (table.area ? ` - ${table.area}` : '');
                    html += `<option value="${escapeHtml(table.name)}" ${isSelected}>${escapeHtml(label)}</option>`;
                });
                select.innerHTML = html;
            }

            function onTableQuickSelectChanged(val) {
                const input = document.getElementById('posTableNumber');
                if (input) {
                    input.value = val;
                }
            }

            // Download Styled Barcode QR PNG
            function downloadTableQr(idx) {
                if (!posTablesData || !posTablesData[idx]) return;
                const table = posTablesData[idx];
                const tableUrl = posBioBaseUrl + (posBioBaseUrl.includes('?') ? '&' : '?') + 'meja=' + encodeURIComponent(table.name);

                // Generate a clean QR code offscreen
                const tempDiv = document.createElement('div');
                tempDiv.style.position = 'absolute';
                tempDiv.style.left = '-9999px';
                document.body.appendChild(tempDiv);

                new QRCode(tempDiv, {
                    text: tableUrl,
                    width: 320,
                    height: 320,
                    colorDark: '#0f172a',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });

                setTimeout(() => {
                    const qrImg = tempDiv.querySelector('img') || tempDiv.querySelector('canvas');
                    if (!qrImg) {
                        showPosAlert('Gagal memproses QR code. Coba lagi dalam beberapa detik.', 'Gagal Generate QR', 'error');
                        document.body.removeChild(tempDiv);
                        return;
                    }

                    // Render into high-res print-ready canvas
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = 600;
                    canvas.height = 760;

                    // 1. Background clean white
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);

                    // 2. Decorative Top Header
                    ctx.fillStyle = '#1eb349';
                    ctx.fillRect(0, 0, canvas.width, 14);

                    // 3. Store Name Header
                    ctx.fillStyle = '#0f172a';
                    ctx.font = 'bold 28px Montserrat, sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(posStoreDisplayName.toUpperCase(), 300, 75);

                    // Subtitle
                    ctx.fillStyle = '#64748b';
                    ctx.font = '600 16px Montserrat, sans-serif';
                    ctx.fillText('SCAN DI SINI UNTUK PESAN & MENU', 300, 110);

                    // 4. White Card for QR code with subtle border
                    ctx.strokeStyle = '#e2e8f0';
                    ctx.lineWidth = 3;
                    ctx.strokeRect(100, 140, 400, 400);

                    // Draw QR Code
                    ctx.drawImage(qrImg, 140, 180, 320, 320);

                    // 5. Table Name Badge
                    ctx.fillStyle = '#0f172a';
                    ctx.font = 'bold 36px Montserrat, sans-serif';
                    ctx.fillText(table.name.toUpperCase(), 300, 600);

                    if (table.area || table.seats) {
                        ctx.fillStyle = '#16a34a';
                        ctx.font = '600 18px Montserrat, sans-serif';
                        const subInfo = [table.area, table.seats ? (table.seats + ' Kursi') : ''].filter(Boolean).join(' • ');
                        ctx.fillText(subInfo, 300, 635);
                    }

                    // 6. Footer Note
                    ctx.fillStyle = '#94a3b8';
                    ctx.font = '13px Montserrat, sans-serif';
                    ctx.fillText('Pesanan Anda akan diantar langsung ke meja ini', 300, 690);
                    ctx.fillStyle = '#cbd5e1';
                    ctx.fillText('Powered by buyle.id', 300, 725);

                    // 7. Trigger PNG Download
                    const link = document.createElement('a');
                    link.download = 'QR-' + table.name.replace(/[^a-zA-Z0-9_-]/g, '_') + '.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();

                    document.body.removeChild(tempDiv);
                    showPosToast('Barcode QR ' + table.name + ' berhasil di-download!', 'success');
                }, 250);
            }

            // Save Tables to Server via POS updateSettings
            function saveTablesSettings(showNotification = true) {
                const btn = document.getElementById('btnSaveTablesSettings');
                if (btn && showNotification) {
                    btn.disabled = true;
                    btn.innerHTML = '<span style="display:inline-block; animation:spin 1s linear infinite;">↻</span> Menyimpan...';
                }

                fetch('{{ route("creator.pos.update-settings") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        pos_tables: posTablesData
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (btn && showNotification) {
                            btn.disabled = false;
                            btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Simpan Perubahan Meja';
                        }
                        if (data.success) {
                            if (data.data && data.data.pos_tables) {
                                posTablesData = data.data.pos_tables;
                            }
                            renderTablesQuickSelect();
                            updateTablesBadgeCount();
                            if (showNotification) {
                                showPosToast('Pengaturan meja berhasil disimpan!', 'success');
                            }
                        } else {
                            if (showNotification) {
                                showPosAlert(data.message || 'Gagal menyimpan meja.', 'Gagal Simpan', 'error');
                            }
                        }
                    })
                    .catch(err => {
                        if (btn && showNotification) {
                            btn.disabled = false;
                            btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Simpan Perubahan Meja';
                        }
                        if (showNotification) {
                            showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal Simpan', 'error');
                        }
                    });
            }

            // ═══════════════════════════════════════════════
            // DEPOSIT POS FUNCTIONS (STORE TRANSFER & QRIS)
            // ═══════════════════════════════════════════════

            let currentStoreBalance = {{ (int) $availableStoreBalance }};

            function openDepositModal() {
                // Default to store transfer tab if balance available, otherwise QRIS
                if (currentStoreBalance >= 5000) {
                    switchDepositMethod('store');
                } else {
                    switchDepositMethod('qris');
                }
                openModal('posDepositModal');
            }

            function switchDepositMethod(method) {
                const tabStore = document.getElementById('tabDepositStore');
                const tabQris = document.getElementById('tabDepositQris');
                const panelStore = document.getElementById('panelDepositStore');
                const panelQris = document.getElementById('panelDepositQris');
                const btnSubmitStore = document.getElementById('btnSubmitStoreTransfer');
                const btnSubmitDeposit = document.getElementById('btnSubmitDeposit');

                if (method === 'store') {
                    if (tabStore) {
                        tabStore.style.background = '#ffffff';
                        tabStore.style.color = '#0f172a';
                        tabStore.style.boxShadow = '0 1px 3px rgba(0,0,0,0.08)';
                    }
                    if (tabQris) {
                        tabQris.style.background = 'transparent';
                        tabQris.style.color = '#64748b';
                        tabQris.style.boxShadow = 'none';
                    }
                    if (panelStore) panelStore.style.display = 'block';
                    if (panelQris) panelQris.style.display = 'none';
                    if (btnSubmitStore) btnSubmitStore.style.display = currentStoreBalance >= 5000 ? 'inline-flex' : 'none';
                    if (btnSubmitDeposit) btnSubmitDeposit.style.display = 'none';
                } else {
                    if (tabQris) {
                        tabQris.style.background = '#ffffff';
                        tabQris.style.color = '#0f172a';
                        tabQris.style.boxShadow = '0 1px 3px rgba(0,0,0,0.08)';
                    }
                    if (tabStore) {
                        tabStore.style.background = 'transparent';
                        tabStore.style.color = '#64748b';
                        tabStore.style.boxShadow = 'none';
                    }
                    if (panelStore) panelStore.style.display = 'none';
                    if (panelQris) panelQris.style.display = 'block';
                    if (btnSubmitStore) btnSubmitStore.style.display = 'none';
                    if (btnSubmitDeposit) btnSubmitDeposit.style.display = 'inline-flex';
                }
            }

            function setStoreTransferPreset(val) {
                if (val > currentStoreBalance) val = currentStoreBalance;
                document.querySelectorAll('.btn-store-preset').forEach(b => b.classList.remove('active'));
                const elVal = document.getElementById('storeTransferVal');
                const elDisp = document.getElementById('storeTransferDisplay');
                if (elVal) elVal.value = val;
                if (elDisp) elDisp.value = formatRupiah(val);
            }

            function handleStoreTransferInput(el) {
                const raw = el.value.replace(/[^0-9]/g, '');
                let val = parseInt(raw) || 0;
                if (val > currentStoreBalance) {
                    val = currentStoreBalance;
                }
                const elVal = document.getElementById('storeTransferVal');
                if (elVal) elVal.value = val;
                el.value = val > 0 ? formatRupiah(val) : '';
                document.querySelectorAll('.btn-store-preset').forEach(b => b.classList.remove('active'));
            }

            function submitStoreToPosTransfer() {
                const amount = parseInt(document.getElementById('storeTransferVal').value) || 0;
                if (amount < 5000) {
                    showPosAlert('Minimal pemindahan saldo ke deposit POS adalah Rp 5.000.', 'Nominal Terlalu Kecil', 'warning');
                    return;
                }
                if (amount > currentStoreBalance) {
                    showPosAlert('Nominal melebihi Saldo Toko yang siap ditarik (Rp ' + formatRupiah(currentStoreBalance) + ').', 'Saldo Tidak Mencukupi', 'warning');
                    return;
                }

                const btn = document.getElementById('btnSubmitStoreTransfer');
                btn.disabled = true;
                btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" style="animation:spin 0.8s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memindahkan...`;

                fetch('{{ route("creator.payout.to_pos_deposit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ amount: amount })
                })
                    .then(res => res.json())
                    .then(data => {
                        btn.disabled = false;
                        btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9" /><path d="M3 11V9a4 4 0 0 1 4-4h14" /><polyline points="7 23 3 19 7 15" /><path d="M21 13v2a4 4 0 0 1-4 4H3" /></svg> Pindahkan ke Deposit Sekarang`;

                        if (data.success) {
                            closeModal('posDepositModal');
                            updateBalanceDisplay(data.new_pos_balance);
                            currentStoreBalance = data.available_balance !== undefined ? data.available_balance : Math.max(0, currentStoreBalance - amount);
                            const storeDisp = document.getElementById('availableStoreBalanceDisplay');
                            if (storeDisp) storeDisp.innerText = 'Rp ' + formatRupiah(currentStoreBalance);

                            showPosAlert(data.message || `Berhasil memindahkan Rp ${formatRupiah(amount)} ke Deposit POS!`, 'Transfer Berhasil', 'success');
                        } else {
                            showPosAlert(data.message || 'Gagal memindahkan saldo.', 'Gagal', 'error');
                        }
                    })
                    .catch(err => {
                        btn.disabled = false;
                        btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9" /><path d="M3 11V9a4 4 0 0 1 4-4h14" /><polyline points="7 23 3 19 7 15" /><path d="M21 13v2a4 4 0 0 1-4 4H3" /></svg> Pindahkan ke Deposit Sekarang`;
                        showPosAlert('Terjadi kesalahan jaringan: ' + err.message, 'Kesalahan Jaringan', 'error');
                    });
            }

            function setDepositPreset(val) {
                // Deactivate all preset buttons
                document.querySelectorAll('.btn-deposit-preset').forEach(b => b.classList.remove('active'));

                // Activate the clicked one
                const btns = document.querySelectorAll('.btn-deposit-preset');
                btns.forEach(b => {
                    const onclick = b.getAttribute('onclick');
                    if (onclick && onclick.includes('(' + val + ')')) {
                        b.classList.add('active');
                    }
                });

                // Set the display and hidden value
                document.getElementById('depositAmountVal').value = val;
                document.getElementById('depositAmountDisplay').value = formatRupiah(val);
            }

            function handleDepositAmountInput(el) {
                const raw = el.value.replace(/[^0-9]/g, '');
                const val = parseInt(raw) || 0;
                document.getElementById('depositAmountVal').value = val;

                // Remove active from all presets (user typed custom amount)
                document.querySelectorAll('.btn-deposit-preset').forEach(b => b.classList.remove('active'));

                // Format display with dots
                if (val > 0) {
                    el.value = formatRupiah(val);
                } else {
                    el.value = '';
                }
            }

            function submitPosDeposit() {
                const amount = parseInt(document.getElementById('depositAmountVal').value) || 0;
                if (amount < 10000) {
                    showPosAlert('Minimal top up saldo deposit adalah Rp 10.000', 'Nominal Terlalu Kecil', 'warning');
                    return;
                }

                const btn = document.getElementById('btnSubmitDeposit');
                btn.disabled = true;
                btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" style="animation:spin 0.8s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memproses...`;

                fetch('{{ route("creator.pos.deposit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ amount: amount })
                })
                    .then(res => res.json())
                    .then(data => {
                        btn.disabled = false;
                        btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg> Bayar Instan via QRIS`;

                        if (data.snap_token && typeof window.snap !== 'undefined') {
                            const depositNumber = data.deposit_number;
                            closeModal('posDepositModal');
                            document.getElementById('midtransBlurOverlay').classList.add('active');

                            try {
                                window.snap.pay(data.snap_token, {
                                    onSuccess: function (result) {
                                        document.getElementById('midtransBlurOverlay').classList.remove('active');
                                        pollDepositStatus(depositNumber);
                                    },
                                    onPending: function (result) {
                                        document.getElementById('midtransBlurOverlay').classList.remove('active');
                                        showPosToast('Menunggu konfirmasi pembayaran deposit. Saldo akan bertambah otomatis.', 'info');
                                    },
                                    onError: function (result) {
                                        document.getElementById('midtransBlurOverlay').classList.remove('active');
                                        showPosAlert('Pembayaran deposit gagal. Silakan coba lagi.', 'Gagal', 'error');
                                    },
                                    onClose: function () {
                                        document.getElementById('midtransBlurOverlay').classList.remove('active');
                                        showPosAlert('Pop-up pembayaran ditutup. Deposit belum diselesaikan.', 'Ditunda', 'warning');
                                    }
                                });
                            } catch (e) {
                                document.getElementById('midtransBlurOverlay').classList.remove('active');
                                showPosAlert('Terjadi kesalahan saat membuka halaman pembayaran.', 'Kesalahan', 'error');
                            }
                        } else if (data.success) {
                            closeModal('posDepositModal');
                            showPosToast('Deposit berhasil diproses!', 'success');
                            if (data.pos_balance !== undefined) updateBalanceDisplay(data.pos_balance);
                        } else {
                            showPosAlert(data.message || 'Gagal membuat sesi pembayaran deposit.', 'Gagal', 'error');
                        }
                    })
                    .catch(err => {
                        btn.disabled = false;
                        btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg> Bayar Instan via QRIS`;
                        showPosAlert('Terjadi kesalahan jaringan: ' + err.message, 'Kesalahan Koneksi', 'error');
                    });
            }

            function pollDepositStatus(depositNumber) {
                if (!depositNumber) {
                    showPosToast('Deposit berhasil! Halaman akan dimuat ulang untuk memperbarui saldo.', 'success');
                    setTimeout(() => window.location.reload(), 2500);
                    return;
                }

                fetch('{{ route("creator.pos.deposit.check") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ deposit_number: depositNumber })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.pos_balance !== undefined) {
                            updateBalanceDisplay(data.pos_balance);
                            if (data.is_paid) {
                                showPosToast('Saldo deposit berhasil ditambahkan!', 'success');
                            } else {
                                showPosToast('Deposit berhasil! Saldo akan diperbarui otomatis.', 'info');
                            }
                        } else {
                            showPosToast('Deposit diproses! Saldo diperbarui otomatis.', 'success');
                            setTimeout(() => window.location.reload(), 2000);
                        }
                    })
                    .catch(() => {
                        showPosToast('Deposit berhasil! Halaman akan dimuat ulang.', 'success');
                        setTimeout(() => window.location.reload(), 2000);
                    });
            }

            function updateBalanceDisplay(newBalance) {
                currentPosBalance = newBalance;
                const formatted = 'Rp ' + formatRupiah(newBalance);
                const displayEl = document.getElementById('posBalanceDisplay');
                const modalEl = document.getElementById('depositModalCurrentBalance');
                const cashUserBalanceDisplay = document.getElementById('cashUserBalanceDisplay');

                if (displayEl) {
                    displayEl.innerText = formatted;
                    displayEl.style.color = newBalance < 10000 ? '#dc2626' : '#166534';
                }
                if (modalEl) {
                    modalEl.innerText = formatted;
                }
                if (cashUserBalanceDisplay) {
                    cashUserBalanceDisplay.innerText = formatted;
                    cashUserBalanceDisplay.style.color = newBalance < 10000 ? '#dc2626' : '#166534';
                }
                const statDepositEl = document.getElementById('posStatDepositDisplay');
                if (statDepositEl) {
                    statDepositEl.innerText = formatted;
                }

                // Re-evaluate cash payment option if cart has items
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                const totalFeeRequired = (subtotal * (platformFeeRate + adminFeeRate)) / 100;
                const optCash = document.getElementById('optCash');
                const cashDepositWarning = document.getElementById('cashDepositWarning');
                const cashBadge = document.getElementById('cashDepositBadge');

                if (optCash) {
                    const isDepositInsufficient = (currentPosBalance <= 0 || currentPosBalance < totalFeeRequired);
                    if (!isDepositInsufficient) {
                        optCash.classList.remove('disabled-cash');
                        optCash.style.background = '';
                        optCash.style.borderColor = '';
                        optCash.style.opacity = '1';
                        optCash.style.cursor = 'pointer';
                        optCash.style.pointerEvents = 'auto';

                        const cashRadio = optCash.querySelector('input[type="radio"]');
                        if (cashRadio) cashRadio.disabled = false;
                        if (cashBadge) cashBadge.style.display = 'none';
                        if (cashDepositWarning) cashDepositWarning.style.display = 'none';
                    }
                }
            }

            // ═══════════════════════════════════════════════
            // REKAP SHIFT / HISTORY TAB SWITCHING
            // ═══════════════════════════════════════════════

            function switchHistoryTab(tabId, clickedBtn) {
                // Map short key to actual pane element ID
                const paneMap = {
                    'shift': 'paneHistoryShift',
                    'orders': 'paneHistoryOrders',
                    'mutations': 'paneHistoryMutations'
                };

                // Deactivate all tab buttons
                document.querySelectorAll('.history-tab-btn').forEach(btn => btn.classList.remove('active'));

                // Activate the button that called this (or find it by the matching tabId in onclick)
                if (clickedBtn) {
                    clickedBtn.classList.add('active');
                } else {
                    document.querySelectorAll('.history-tab-btn').forEach(btn => {
                        const oc = btn.getAttribute('onclick') || '';
                        if (oc.includes("'" + tabId + "'")) {
                            btn.classList.add('active');
                        }
                    });
                }

                // Hide all panes
                Object.values(paneMap).forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.style.display = 'none';
                });

                // Show selected pane
                const targetId = paneMap[tabId] || tabId;
                const target = document.getElementById(targetId);
                if (target) target.style.display = tabId === 'shift' ? 'block' : 'block';
            }

            // ═══════════════════════════════════════════════
            // CETAK STRUK CLOSING SHIFT
            // ═══════════════════════════════════════════════

            function printClosingReceipt() {
                const receiptEl = document.getElementById('printableClosingReceiptArea');
                if (!receiptEl) return;

                const content = receiptEl.innerHTML;
                const printWin = window.open('', '_blank', 'width=420,height=650');
                if (!printWin) {
                    showPosAlert('Pop-up terblokir oleh browser. Izinkan pop-up untuk mencetak rekap shift.', 'Pop-up Terblokir', 'warning');
                    return;
                }

                printWin.document.write(`<!DOCTYPE html>
                                        <html>
                                        <head>
                                            <title>Rekap Closing Shift - {{ $storeName }}</title>
                                            <style>
                                                @page { size: 80mm auto; margin: 0; }
                                                body { font-family: 'Courier New', monospace; font-size: 11px; color: #000; margin: 0; padding: 5mm; width: 70mm; background: #fff; }
                                                table { width: 100%; border-collapse: collapse; }
                                                td { padding: 1px 0; }
                                                .text-end, [style*="text-align: right"] { text-align: right; }
                                                .text-center, [style*="text-align: center"] { text-align: center; }
                                                .no-print { display: none !important; }
                                                img { display: none !important; }
                                            </style>
                                            </head>
                                        <body>
                                            ${content}
                                        </body>
                                        </html>`);
                printWin.document.close();
                printWin.focus();
                setTimeout(() => {
                    printWin.print();
                    printWin.close();
                }, 350);
            }

            // CSS spin animation for loader
            const styleEl = document.createElement('style');
            styleEl.textContent = `@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }`;
            document.head.appendChild(styleEl);

                        // Smooth POS Skeleton Reveal Transition
            (function () {
                function revealPos() {
                    var skel = document.getElementById('pos-skeleton');
                    var real = document.getElementById('pos-real-content');
                    if (real) {
                        real.style.opacity = '1';
                        real.style.zIndex = '2';
                    }
                    if (skel) {
                        skel.style.opacity = '0';
                        skel.style.pointerEvents = 'none';
                        setTimeout(function() { if (skel && skel.parentNode) skel.parentNode.removeChild(skel); }, 350);
                    }
                    try {
                        if (typeof renderCartFees === 'function') renderCartFees();
                        if (typeof renderDiscountPresetDropdown === 'function') renderDiscountPresetDropdown();
                        if (typeof renderTablesQuickSelect === 'function') renderTablesQuickSelect();
                        if (typeof updateTablesBadgeCount === 'function') updateTablesBadgeCount();
                    } catch (e) { }
                }
                if (document.readyState !== 'loading') {
                    revealPos();
                } else {
                    document.addEventListener('DOMContentLoaded', revealPos);
                }
                window.addEventListener('load', revealPos);
            })();

            
            // -- EXPLICIT CONSOLE DEBUGGING & ERROR TRACKING --
            window.onerror = function(message, source, lineno, colno, error) {
                console.error("%c[POS RUNTIME ERROR]", "background: #ef4444; color: white; padding: 2px 6px; border-radius: 4px; font-weight: bold;", message);
                console.error("File:", source, "Line:", lineno, "Column:", colno);
                if (error && error.stack) console.error("Stack trace:", error.stack);
                return false;
            };
            window.onunhandledrejection = function(event) {
                console.error("%c[POS UNHANDLED PROMISE REJECTION]", "background: #f97316; color: white; padding: 2px 6px; border-radius: 4px; font-weight: bold;", event.reason);
            };
            console.log("%c[Buyle POS Ready] Debug console active. Any error will be logged in full detail.", "color: #16a34a; font-weight: bold;");

            // Direct redirect to Products page
            function openPosAddOptionsModal(e) {
                if (e) { e.preventDefault(); e.stopPropagation(); }
                window.location.href = "{{ route('creator.products.index', ['from' => 'pos']) }}";
            }

            // Move bottom navbar to <body> to escape overflow:hidden stacking context
            (function moveNavbarToBody() {
                function doMove() {
                    var nav = document.getElementById('posBottomNavWrap');
                    var floatBar = document.getElementById('posMobileFloatingBar');
                    if (nav && nav.parentNode !== document.body) {
                        document.body.appendChild(nav);
                    }
                    if (floatBar && floatBar.parentNode !== document.body) {
                        document.body.appendChild(floatBar);
                    }
                    var addModal = document.getElementById('posAddOptionsModal');
                    if (addModal && addModal.parentNode !== document.body) {
                        document.body.appendChild(addModal);
                    }
                    var prodModal = document.getElementById('posQuickProductModal');
                    if (prodModal && prodModal.parentNode !== document.body) {
                        document.body.appendChild(prodModal);
                    }
                    var catModal = document.getElementById('posQuickCategoryModal');
                    if (catModal && catModal.parentNode !== document.body) {
                        document.body.appendChild(catModal);
                    }
                }
                if (document.readyState !== 'loading') {
                    doMove();
                } else {
                    document.addEventListener('DOMContentLoaded', doMove);
                }
            })();


            // Screen 3 Helper: Toggle Billing Items Collapse
            function toggleBillingItemsCollapse() {
                const el = document.getElementById('billingItemsCollapse');
                const ico = document.getElementById('billingItemsToggleIcon');
                if (el) {
                    const isHidden = el.style.display === 'none';
                    el.style.display = isHidden ? 'block' : 'none';
                    if (ico) ico.innerText = isHidden ? '−' : '+';
                }
            }

            // Screen 3 Helper: Toggle Customer Edit Form
            function toggleCustEditForm() {
                const el = document.getElementById('custEditFormCollapse');
                if (el) {
                    el.style.display = el.style.display === 'none' ? 'block' : 'none';
                }
            }

        
            // ── BUYLE POS SCREEN SWITCHER (Screen 1: Catalog vs Screen 2: Transactions) ──
            let currentPosActiveScreen = 'catalog';
            // Check if opened with ?screen=transactions
            (function() {
                try {
                    const params = new URLSearchParams(window.location.search);
                    if (params.get('screen') === 'transactions') {
                        document.addEventListener('DOMContentLoaded', function() {
                            switchPosScreen('transactions');
                        });
                    }
                } catch(e) {}
            })();

            let transFilterTimeMode = 'all';

            function switchPosScreen(screen) {
                currentPosActiveScreen = screen;
                const screenCat = document.getElementById('posScreenCatalog');
                const screenTrans = document.getElementById('posScreenTransactions');
                const navHome = document.getElementById('posNavBtnHome');
                const navTrans = document.getElementById('posNavBtnTrans');
                const floatingBar = document.getElementById('posMobileFloatingBar');

                if (navHome) navHome.classList.remove('active');
                if (navTrans) navTrans.classList.remove('active');

                if (screen === 'transactions') {
                    if (navTrans) navTrans.classList.add('active');
                    if (screenCat) screenCat.style.display = 'none';
                    if (floatingBar && window.innerWidth <= 991) {
                        floatingBar.style.display = 'flex';
                    }

                    if (screenTrans) {
                        screenTrans.style.display = 'block';
                        screenTrans.style.opacity = '0';
                        screenTrans.style.transform = 'translateY(10px)';
                        screenTrans.style.transition = 'all 0.2s cubic-bezier(0.16, 1, 0.3, 1)';
                        setTimeout(() => {
                            screenTrans.style.opacity = '1';
                            screenTrans.style.transform = 'translateY(0)';
                        }, 20);
                    }
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    if (navHome) navHome.classList.add('active');
                    if (screenTrans) screenTrans.style.display = 'none';

                    if (screenCat) {
                        screenCat.style.display = 'block';
                        screenCat.style.opacity = '0';
                        screenCat.style.transform = 'translateY(10px)';
                        screenCat.style.transition = 'all 0.2s cubic-bezier(0.16, 1, 0.3, 1)';
                        setTimeout(() => {
                            screenCat.style.opacity = '1';
                            screenCat.style.transform = 'translateY(0)';
                        }, 20);
                    }
                    // Restore floating bar on mobile if items in cart
                    if (floatingBar && window.innerWidth <= 991) {
                        floatingBar.style.display = 'flex';
                    }
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }

            // Quick Product & Category Modal Handlers
            function openQuickProductModal() {
                closeModal('posAddOptionsModal');
                openModal('posQuickProductModal');
            }

            function openQuickCategoryModal() {
                closeModal('posAddOptionsModal');
                openModal('posQuickCategoryModal');
            }

            // Submit Quick Product via AJAX
            function submitQuickProduct(e) {
                e.preventDefault();
                const btn = document.getElementById('btnSubmitQuickProduct');
                const origText = btn ? btn.innerHTML : 'Simpan';
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
                }

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('name', document.getElementById('qpName').value.trim());
                formData.append('price', document.getElementById('qpPrice').value.trim());
                const stockVal = document.getElementById('qpStock').value.trim();
                if (stockVal) formData.append('stock', stockVal);
                const catVal = document.getElementById('qpCategory').value;
                if (catVal) formData.append('product_category_id', catVal);
                const imgInput = document.getElementById('qpImage');
                if (imgInput && imgInput.files[0]) {
                    formData.append('image', imgInput.files[0]);
                }

                fetch('{{ route("creator.pos.quick-product") }}', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                })
                .then(r => r.json())
                .then(res => {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = origText;
                    }
                    if (res.success && res.product) {
                        const p = res.product;
                        // Add to window.posCatalog
                        if (!window.posCatalog) window.posCatalog = {};
                        window.posCatalog[p.id] = {
                            id: p.id,
                            name: p.name,
                            price: p.price,
                            image: p.image_url || '',
                            hasVariants: false,
                            variants: []
                        };
                        window.posAllProducts = Object.values(window.posCatalog);

                        // Append HTML card to product grid
                        const grid = document.getElementById('productGridContainer');
                        if (grid) {
                            const formattedPrice = new Intl.NumberFormat('id-ID').format(p.price);
                            const imgHtml = p.image_url
                                ? `<img src="${p.image_url}" alt="${p.name}" class="product-img" loading="lazy">`
                                : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f8fafc;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>`;

                            const cardHtml = `
                                <div class="product-card" data-id="${p.id}" data-name="${p.name.toLowerCase()}"
                                    data-price="${p.price}" data-category="all" data-image="${p.image_url || ''}"
                                    onclick="handleProductClick(${p.id})">
                                    <div class="product-img-wrapper">
                                        ${imgHtml}
                                        <div class="product-qty-badge" id="badge-qty-${p.id}" style="display:none;position:absolute;top:6px;left:6px;background:linear-gradient(135deg,#1eb349,#a5cf37);color:#fff;font-size:0.65rem;font-weight:700;min-width:20px;height:20px;border-radius:10px;align-items:center;justify-content:center;padding:0 5px;z-index:5;">0</div>
                                        <div class="product-check-badge" id="card-check-${p.id}" style="display:none;position:absolute;top:6px;right:6px;width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,#1eb349,#a5cf37);align-items:center;justify-content:center;z-index:5;">
                                            <svg width="11" height="11" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
                                    </div>
                                    <div style="padding:0.5rem 0.6rem 0.45rem;flex:1;display:flex;flex-direction:column;justify-content:space-between;">
                                        <div class="product-name">${p.name}</div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">Rp ${formattedPrice}</div>
                                            <button type="button" class="card-btn-add" id="card-add-btn-${p.id}" onclick="event.stopPropagation(); handleProductClick(${p.id});">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                            </button>
                                            <div class="card-stepper" id="card-stepper-${p.id}" style="display:none;">
                                                <button type="button" class="card-stepper-btn minus" onclick="event.stopPropagation(); handleCardStepper(${p.id}, -1);"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
                                                <span class="card-stepper-num" id="card-qty-num-${p.id}">1</span>
                                                <button type="button" class="card-stepper-btn plus" onclick="event.stopPropagation(); handleCardStepper(${p.id}, 1);"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            grid.insertAdjacentHTML('afterbegin', cardHtml);
                        }

                        // Reset form & close modal
                        document.getElementById('formQuickProduct').reset();
                        closeModal('posQuickProductModal');
                        if (typeof showPosAlert === 'function') {
                            showPosAlert('Berhasil', res.message || 'Produk berhasil ditambahkan!', 'success');
                        } else {
                            alert(res.message);
                        }
                    } else {
                        alert(res.message || 'Gagal menambahkan produk');
                    }
                })
                .catch(err => {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = origText;
                    }
                    console.error(err);
                    alert('Terjadi kesalahan saat menyimpan produk.');
                });
            }

            // Submit Quick Category via AJAX
            function submitQuickCategory(e) {
                e.preventDefault();
                const btn = document.getElementById('btnSubmitQuickCategory');
                const origText = btn ? btn.innerHTML : 'Simpan';
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
                }

                const catName = document.getElementById('qcName').value.trim();
                fetch('{{ route("creator.pos.quick-category") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ name: catName })
                })
                .then(r => r.json())
                .then(res => {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = origText;
                    }
                    if (res.success && res.category) {
                        const cat = res.category;
                        // Add to pills container
                        const pillsContainer = document.getElementById('posCategoryPillsContainer');
                        if (pillsContainer) {
                            const newPill = document.createElement('button');
                            newPill.type = 'button';
                            newPill.className = 'pos-ref-cat-pill';
                            newPill.setAttribute('data-cat', 'cat-' + cat.id);
                            newPill.innerText = cat.name;
                            newPill.onclick = function() { setPosCategoryFilter('cat-' + cat.id, this); };
                            pillsContainer.appendChild(newPill);
                        }
                        // Add to select in product modal
                        const qpSelect = document.getElementById('qpCategory');
                        if (qpSelect) {
                            const opt = document.createElement('option');
                            opt.value = cat.id;
                            opt.innerText = cat.name;
                            qpSelect.appendChild(opt);
                        }

                        document.getElementById('formQuickCategory').reset();
                        closeModal('posQuickCategoryModal');
                        if (typeof showPosAlert === 'function') {
                            showPosAlert('Berhasil', res.message, 'success');
                        } else {
                            alert(res.message);
                        }
                    } else {
                        alert(res.message || 'Gagal menambahkan kelompok produk');
                    }
                })
                .catch(err => {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = origText;
                    }
                    console.error(err);
                    alert('Terjadi kesalahan saat menyimpan kategori.');
                });
            }

            // Filter Transactions in Screen 2
            function filterTransactionsList(query) {
                applyPeriodToTransactions(currentPeriodFilter, customPeriodStart, customPeriodEnd);
            }

            // ── Interactive Period & Calendar Filter (Buyle Style) ──
            let currentPeriodFilter = 'all';
            let customPeriodStart = null;
            let customPeriodEnd = null;

            function openPeriodCalendarModal() {
                const today = new Date().toISOString().split('T')[0];
                const startInput = document.getElementById('posCalStartDate');
                const endInput = document.getElementById('posCalEndDate');
                if (startInput && !startInput.value) {
                    const d = new Date();
                    d.setDate(d.getDate() - 30);
                    startInput.value = d.toISOString().split('T')[0];
                }
                if (endInput && !endInput.value) {
                    endInput.value = today;
                }
                document.querySelectorAll('.pos-modal-preset-btn').forEach(b => b.classList.remove('active'));
                const modalPresetMap = {
                    'all': 'modalPresetAll',
                    'today': 'modalPresetToday',
                    '7d': 'modalPreset7d',
                    '30d': 'modalPreset30d',
                    '1y': 'modalPreset1y',
                    'this_month': 'modalPresetMonth'
                };
                const activeId = modalPresetMap[currentPeriodFilter];
                if (activeId && document.getElementById(activeId)) {
                    document.getElementById(activeId).classList.add('active');
                }
                openModal('posPeriodCalendarModal');
            }

            function selectPeriodPreset(preset, btnEl) {
                currentPeriodFilter = preset;
                customPeriodStart = null;
                customPeriodEnd = null;

                document.querySelectorAll('.pos-period-btn').forEach(b => b.classList.remove('active'));
                if (btnEl) {
                    btnEl.classList.add('active');
                } else {
                    const matchingBtn = document.querySelector(`.pos-period-btn[data-period="${preset}"]`);
                    if (matchingBtn) matchingBtn.classList.add('active');
                }

                applyPeriodToTransactions(preset);
            }

            function applyModalPreset(preset) {
                selectPeriodPreset(preset);
                closeModal('posPeriodCalendarModal');
            }

            function applyCustomDateFilter() {
                const startVal = document.getElementById('posCalStartDate')?.value;
                const endVal = document.getElementById('posCalEndDate')?.value;
                if (!startVal || !endVal) {
                    if (typeof showPosAlert === 'function') {
                        showPosAlert('Silakan pilih tanggal awal dan akhir.', 'Perhatian', 'warning');
                    } else {
                        alert('Silakan pilih tanggal awal dan akhir.');
                    }
                    return;
                }
                if (startVal > endVal) {
                    if (typeof showPosAlert === 'function') {
                        showPosAlert('Tanggal awal tidak boleh lebih besar dari tanggal akhir.', 'Perhatian', 'warning');
                    } else {
                        alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
                    }
                    return;
                }

                currentPeriodFilter = 'custom';
                customPeriodStart = startVal;
                customPeriodEnd = endVal;

                document.querySelectorAll('.pos-period-btn').forEach(b => b.classList.remove('active'));

                applyPeriodToTransactions('custom', startVal, endVal);
                closeModal('posPeriodCalendarModal');
            }

            function resetCalendarFilter() {
                selectPeriodPreset('all');
                closeModal('posPeriodCalendarModal');
            }

            let activeSummaryFilter = null;
            window.filterTransactionsByTab = function(tab) {
                const unpaidCard = document.getElementById('cardSummaryUnpaid');
                const procCard = document.getElementById('cardSummaryProcessing');
                const compCard = document.getElementById('cardSummaryCompleted');

                if (activeSummaryFilter === tab) {
                    activeSummaryFilter = null;
                    if (unpaidCard) unpaidCard.style.outline = 'none';
                    if (procCard) procCard.style.outline = 'none';
                    if (compCard) compCard.style.outline = 'none';
                } else {
                    activeSummaryFilter = tab;
                    if (unpaidCard) unpaidCard.style.outline = (tab === 'unpaid') ? '2.5px solid #d97706' : 'none';
                    if (procCard) procCard.style.outline = (tab === 'processing') ? '2.5px solid #16a34a' : 'none';
                    if (compCard) compCard.style.outline = (tab === 'completed') ? '2.5px solid #2563eb' : 'none';
                }
                applyPeriodToTransactions(currentPeriodFilter);
            };

            window.updatePosTransactionsMetrics = function() {
                const allCards = document.querySelectorAll('.pos-trans-card');
                let unpaidCount = 0;
                let processingCount = 0;
                let completedCount = 0;

                allCards.forEach(c => {
                    const isPaid = c.getAttribute('data-paid') === '1';
                    const isComp = c.getAttribute('data-completed') === '1';
                    if (isComp) {
                        completedCount++;
                    } else if (!isPaid) {
                        unpaidCount++;
                    } else {
                        processingCount++;
                    }
                });

                const unpaidEl = document.getElementById('countSummaryUnpaid');
                if (unpaidEl) unpaidEl.innerText = unpaidCount;

                const procEl = document.getElementById('countSummaryProcessing');
                if (procEl) procEl.innerText = processingCount;

                const compEl = document.getElementById('countSummaryCompleted');
                if (compEl) compEl.innerText = completedCount;

                // Update bottom nav badge on Transaksi
                const activeTotal = unpaidCount + processingCount;
                const navBadge = document.getElementById('posTransNavBadge');
                if (navBadge) {
                    if (activeTotal > 0) {
                        navBadge.innerText = activeTotal;
                        navBadge.style.display = 'inline-flex';
                    } else {
                        navBadge.style.display = 'none';
                    }
                }
            };

            function applyPeriodToTransactions(mode, startCustom, endCustom) {
                const label = document.getElementById('posTransFilterLabel');
                const today = new Date();
                today.setHours(23, 59, 59, 999);

                let start = null;
                let end = today;

                if (mode === 'yesterday') {
                    start = new Date();
                    start.setDate(start.getDate() - 1);
                    start.setHours(0, 0, 0, 0);
                    end = new Date();
                    end.setDate(end.getDate() - 1);
                    end.setHours(23, 59, 59, 999);
                    if (label) label.innerText = 'Kemarin';
                } else {
                    // Default: Hari Ini (today)
                    start = new Date();
                    start.setHours(0, 0, 0, 0);
                    if (label) label.innerText = 'Hari Ini';
                }

                const cards = document.querySelectorAll('.pos-trans-card');
                let visibleCount = 0;
                const query = (document.getElementById('posTransSearch')?.value || '').toLowerCase().trim();

                cards.forEach(card => {
                    const cardDateStr = card.getAttribute('data-date');
                    const searchData = (card.getAttribute('data-search') || '').toLowerCase();
                    const matchesQuery = !query || searchData.includes(query);

                    let matchesDate = true;
                    if (cardDateStr && start && end) {
                        const cardDate = new Date(cardDateStr + 'T12:00:00');
                        matchesDate = (cardDate >= start && cardDate <= end);
                    }

                    let matchesSummary = true;
                    const isPaid = card.getAttribute('data-paid') === '1';
                    const isComp = card.getAttribute('data-completed') === '1';
                    if (activeSummaryFilter === 'unpaid') {
                        matchesSummary = (!isPaid && !isComp);
                    } else if (activeSummaryFilter === 'processing') {
                        matchesSummary = (isPaid && !isComp);
                    } else if (activeSummaryFilter === 'completed') {
                        matchesSummary = isComp;
                    }

                    if (matchesQuery && matchesDate && matchesSummary) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                let emptyEl = document.getElementById('posTransEmptyNotice');
                if (!emptyEl) {
                    emptyEl = document.createElement('div');
                    emptyEl.id = 'posTransEmptyNotice';
                    emptyEl.style.textAlign = 'center';
                    emptyEl.style.padding = '3rem 1rem';
                    emptyEl.style.color = '#94a3b8';
                    emptyEl.style.fontSize = '0.84rem';
                    document.getElementById('posTransactionsListContainer')?.appendChild(emptyEl);
                }
                if (visibleCount === 0) {
                    emptyEl.innerText = 'Tidak ada transaksi pada periode yang dipilih.';
                    emptyEl.style.display = 'block';
                } else {
                    emptyEl.style.display = 'none';
                }
            }

            // ── Sort Toggle (Terbaru / Terlama) ──
            window.posTransSortOrder = 'newest'; // default: terbaru di atas

            function toggleTransactionSort() {
                const container = document.getElementById('posTransactionsListContainer');
                if (!container) return;

                // flip
                window.posTransSortOrder = (window.posTransSortOrder === 'newest') ? 'oldest' : 'newest';
                const isNewest = window.posTransSortOrder === 'newest';

                // update icon: arrow-up = terbaru di atas, arrow-down = terlama di atas
                const icon = document.getElementById('sortToggleIcon');
                if (icon) {
                    icon.innerHTML = isNewest
                        ? '<line x1="12" y1="20" x2="12" y2="4"/><polyline points="6 10 12 4 18 10"/>'  // up arrow
                        : '<line x1="12" y1="4" x2="12" y2="20"/><polyline points="6 14 12 20 18 14"/>'; // down arrow
                }

                // re-sort all cards
                const cards = Array.from(container.querySelectorAll('.pos-trans-card'));
                const emptyNotice = document.getElementById('posTransEmptyNotice');

                cards.sort((a, b) => {
                    const tsA = parseInt(a.getAttribute('data-timestamp') || '0', 10);
                    const tsB = parseInt(b.getAttribute('data-timestamp') || '0', 10);
                    return isNewest ? (tsB - tsA) : (tsA - tsB);
                });

                // re-append in sorted order (empty notice stays at end)
                cards.forEach(c => container.appendChild(c));
                if (emptyNotice) container.appendChild(emptyNotice);
            }

            function toggleTransTimeFilter() {
                openPeriodCalendarModal();
            }

            // Open Receipt from Transaction Card Click
            function openPosOrderReceipt(orderId) {
                // Set header to "Detail Transaksi" mode
                window._posReceiptFromTrans = true;
                const recModalHead = document.querySelector('#posReceiptModal .pos-modal-head h5');
                if (recModalHead) recModalHead.textContent = 'Detail Transaksi';
                const recModalSub = document.querySelector('#posReceiptModal .pos-modal-head div:last-child > div:last-child');
                if (recModalSub) recModalSub.textContent = 'Rincian Struk & Pesanan';

                // Coba ambil dari data live dulu
                const cached = (window._posLiveOrdersCache || []).find(o => o.id == orderId)
                            || (window._posTodayOrdersData || []).find(o => o.id == orderId);

                if (cached && cached.items && cached.items.length > 0) {
                    // Data lengkap tersedia
                    if (typeof showReceiptModal === 'function') {
                        showReceiptModal(cached);
                    }
                    return;
                }

                // Fetch dari API
                fetch('/creator/pos/order/' + orderId + '/detail', {
                    headers: { 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (typeof showReceiptModal === 'function') {
                            showReceiptModal(data);
                        } else {
                            // Fallback: buka modal laporan
                            if (typeof openReceiptModal === 'function') openReceiptModal(data);
                        }
                    } else {
                        alert('Gagal memuat detail pesanan.');
                    }
                })
                .catch(() => alert('Gagal memuat detail pesanan. Periksa koneksi.'));
            }

            // ── REALTIME ORDER ACTIONS ──

            function posMarkPaid(orderId, btn) {
                if (btn) { btn.disabled = true; }
                fetch('/creator/pos/order/' + orderId + '/mark-paid', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || ''
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        // Update kartu langsung tanpa reload
                        const card = document.getElementById('pos-trans-card-' + orderId);
                        if (card) {
                            card.setAttribute('data-paid', '1');
                            const actionsDiv = card.querySelector('.pos-trans-actions');
                            if (actionsDiv) {
                                actionsDiv.innerHTML = `
                                    <button type="button" class="pos-action-btn btn-paid" disabled title="Sudah Lunas">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    </button>
                                    <button type="button" class="pos-action-btn btn-complete" onclick="event.stopPropagation(); posMarkCompleted(${orderId}, this)" title="Tandai Pesanan Selesai / Sudah Disajikan">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"/>
                                            <polyline points="20 10 9 21 4 16" style="opacity:0.5;"/>
                                        </svg>
                                    </button>`;
                            }
                            card.classList.add('new-order-flash');
                            updatePosTransactionsMetrics();
                        }
                    } else {
                        if (btn) btn.disabled = false;
                        alert(data.message || 'Gagal menandai pembayaran.');
                    }
                })
                .catch(() => {
                    if (btn) btn.disabled = false;
                    alert('Koneksi error. Coba lagi.');
                });
            }

            function posMarkCompleted(orderId, btn) {
                if (btn) { btn.disabled = true; }
                fetch('/creator/pos/order/' + orderId + '/mark-completed', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || ''
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const card = document.getElementById('pos-trans-card-' + orderId);
                        if (card) {
                            card.setAttribute('data-completed', '1');
                            const actionsDiv = card.querySelector('.pos-trans-actions');
                            if (actionsDiv) {
                                actionsDiv.innerHTML = `
                                    <button type="button" class="pos-action-btn btn-complete" disabled title="Pesanan Selesai">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"/>
                                            <polyline points="20 10 9 21 4 16" style="opacity:0.5;"/>
                                        </svg>
                                    </button>`;
                            }
                            // Animasi halus & otomatis meluncur ke posisi teratas dari kelompok pesanan selesai
                            card.style.transition = 'transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.45s ease';
                            card.style.transform = 'translateY(18px) scale(0.96)';
                            card.style.opacity = '0.4';
                            setTimeout(() => {
                                const container = document.getElementById('posTransactionsListContainer');
                                if (container) {
                                    // Cari pesanan selesai yang sudah ada sebelumnya
                                    const firstCompleted = Array.from(container.querySelectorAll('.pos-trans-card[data-completed="1"]'))
                                        .find(c => c.id !== ('pos-trans-card-' + orderId));
                                    if (firstCompleted) {
                                        container.insertBefore(card, firstCompleted);
                                    } else {
                                        container.appendChild(card);
                                    }
                                }
                                card.style.transition = 'transform 0.35s ease, opacity 0.35s ease';
                                card.style.transform = 'translateY(0) scale(1)';
                                card.style.opacity = '0.65';
                                updatePosTransactionsMetrics();
                            }, 450);
                        }
                    } else {
                        if (btn) btn.disabled = false;
                        alert(data.message || 'Gagal menandai pesanan selesai.');
                    }
                })
                .catch(() => {
                    if (btn) btn.disabled = false;
                    alert('Koneksi error. Coba lagi.');
                });
            }

            // ── REALTIME LIVE POLLING (3 detik, zero refresh) ──

            let liveOrderLatestId = {{ ($recentPosOrders ?? $todayOrders)?->max('id') ?? 0 }};
            let liveOrderPollerActive = false;

            // Audio chime untuk notifikasi pesanan baru (inlined base64 Web Audio)
            function playOrderChime() {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const o = ctx.createOscillator();
                    const g = ctx.createGain();
                    o.connect(g); g.connect(ctx.destination);
                    o.type = 'sine';
                    o.frequency.setValueAtTime(880, ctx.currentTime);
                    o.frequency.setValueAtTime(1100, ctx.currentTime + 0.1);
                    o.frequency.setValueAtTime(1320, ctx.currentTime + 0.2);
                    g.gain.setValueAtTime(0.5, ctx.currentTime);
                    g.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.6);
                    o.start(ctx.currentTime);
                    o.stop(ctx.currentTime + 0.6);
                } catch(e) {}
            }

            function buildTransactionCardHtml(ord) {
                const isCash    = (ord.payment_method || '').includes('cash');
                const isSelf    = ord.is_self_order;
                const isPaid    = ord.is_paid;
                const isComp    = ord.is_completed;
                const total     = 'Rp ' + Number(ord.total).toLocaleString('id-ID');
                const badge     = isCash ? '#059669' : '#7c3aed';
                const badgeLbl  = isCash ? 'Tunai' : 'Digital';
                const tableStr  = ord.table_number ? ' · Meja ' + ord.table_number : '';
                const selfBadge = isSelf ? `<span style="font-size:0.6rem;background:#fef3c7;color:#d97706;border:1px solid #fde68a;padding:1px 6px;border-radius:999px;font-weight:800;margin-right:4px;">Self-Order</span>` : '';

                // Tombol Aksi
                let actionsBtns = '';
                if (!isPaid && isCash) {
                    actionsBtns = `<button type="button" class="pos-action-btn btn-cash-pending" onclick="event.stopPropagation(); posMarkPaid(${ord.id}, this)" title="Tandai Pembayaran Diterima">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 10v4m-2-2h4"/><path d="M6 10h.01M18 10h.01"/></svg>
                    </button>`;
                } else if (isPaid && !isComp) {
                    actionsBtns = `
                        <button type="button" class="pos-action-btn btn-paid" disabled title="Sudah Lunas">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </button>
                        <button type="button" class="pos-action-btn btn-complete" onclick="event.stopPropagation(); posMarkCompleted(${ord.id}, this)" title="Tandai Selesai">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"/>
                                <polyline points="20 10 9 21 4 16" style="opacity:0.5;"/>
                            </svg>
                        </button>`;
                } else if (isComp) {
                    actionsBtns = `<button type="button" class="pos-action-btn btn-complete" disabled title="Selesai">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                            <polyline points="20 10 9 21 4 16" style="opacity:0.5;"/>
                        </svg>
                    </button>`;
                }

                return `<div class="pos-trans-card new-order-flash" id="pos-trans-card-${ord.id}"
                            data-id="${ord.id}" data-paid="${isPaid?'1':'0'}" data-completed="${isComp?'1':'0'}"
                            data-pay-method="${ord.payment_method||'cash'}"
                            data-search="${(ord.order_number||ord.id)} ${ord.customer_name||''}"
                            data-date="${ord.created_date}" data-timestamp="${ord.timestamp}">
                    <div style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;" onclick="openPosOrderReceipt(${ord.id})">
                        <div class="pos-trans-card-top" style="flex:1;">
                            <span class="pos-trans-sale-num">
                                ${selfBadge}#${ord.order_number}
                                ${ord.table_number ? `<span style="font-size:0.68rem;color:#64748b;font-weight:600;margin-left:4px;">· Meja ${ord.table_number}</span>` : ''}
                            </span>
                            <span class="pos-trans-amount">${total}</span>
                        </div>
                    </div>
                    <div class="pos-trans-card-mid" style="cursor:pointer;" onclick="openPosOrderReceipt(${ord.id})">
                        <span style="font-size:0.75rem;color:#334155;font-weight:600;">${ord.customer_name||'Pelanggan Umum'}${tableStr}</span>
                        <span style="font-size:0.7rem;color:#64748b;">${ord.items_count} item</span>
                    </div>
                    <div style="font-size:0.75rem;color:#475569;margin:3px 0 5px;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;cursor:pointer;" onclick="openPosOrderReceipt(${ord.id})">${ord.items_text}</div>
                    <div class="pos-trans-time" style="display:flex;align-items:center;justify-content:space-between;gap:6px;">
                        <div style="display:flex;align-items:center;gap:5px;flex:1;min-width:0;cursor:pointer;" onclick="openPosOrderReceipt(${ord.id})">
                            <span style="display:inline-block;padding:2px 8px;border-radius:999px;font-size:0.65rem;font-weight:700;color:#fff;background:${badge};white-space:nowrap;">${badgeLbl}</span>
                            <span style="font-size:0.68rem;color:#94a3b8;white-space:nowrap;">${ord.created_at}</span>
                        </div>
                        <div class="pos-trans-actions" style="margin-top:0;">${actionsBtns}</div>
                    </div>
                </div>`;
            }

            async function pollLiveOrders() {
                if (!liveOrderPollerActive) return;
                try {
                    const r = await fetch('/creator/pos/live-orders?last_id=' + liveOrderLatestId, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!r.ok) return;
                    const data = await r.json();
                    if (data.success && data.orders && data.orders.length > 0) {
                        const container = document.getElementById('posTransactionsListContainer');
                        const emptyState = document.getElementById('posTransEmptyState') || document.getElementById('posTransEmptyNotice');

                        let newCount = 0;
                        data.orders.forEach(ord => {
                            const existing = document.getElementById('pos-trans-card-' + ord.id);
                            if (!existing) {
                                // Kartu baru – inject paling atas
                                if (emptyState) emptyState.style.display = 'none';
                                container.insertAdjacentHTML('afterbegin', buildTransactionCardHtml(ord));
                                newCount++;
                                liveOrderLatestId = Math.max(liveOrderLatestId, ord.id);
                            }
                        });

                        if (newCount > 0) {
                            playOrderChime();
                            updatePosTransactionsMetrics();
                            applyPeriodToTransactions(currentPeriodFilter);
                            // Tampilkan popup pesanan masuk
                            const firstNew = data.orders[0];
                            const popupBody = firstNew
                                ? ((firstNew.customer_name || 'Pelanggan') + (firstNew.table_number ? ' · Meja ' + firstNew.table_number : '') + ' — ' + (firstNew.items_text || (firstNew.items_count + ' item')))
                                : (newCount + ' pesanan baru masuk');
                            if (typeof window.showPosNewOrderPopup === 'function') {
                                window.showPosNewOrderPopup('Pesanan Masuk! (' + newCount + ')', popupBody);
                            }
                        }
                    }
                } catch(e) { /* silent – jaringan buruk, coba lagi 3 detik */ }
            }

            function startLiveOrderPoller() {
                if (liveOrderPollerActive) return;
                liveOrderPollerActive = true;
                (function loop() {
                    pollLiveOrders().finally(() => {
                        if (liveOrderPollerActive) setTimeout(loop, 3000);
                    });
                })();
            }

            function stopLiveOrderPoller() {
                liveOrderPollerActive = false;
            }

            // Mulai poller secara global agar badge notifikasi navbar selalu live terupdate
            document.addEventListener('DOMContentLoaded', function() {
                // Hitung metrics awal & set periode default Hari Ini
                updatePosTransactionsMetrics();
                selectPeriodPreset('today');
                // Apply default sort: terbaru (newest first) – karena PHP sudah order DESC, icon hanya di-sync
                window.posTransSortOrder = 'newest';
                startLiveOrderPoller();
            });


            // ── Render Payment Modal Order Items (Minimalis) ──
            function renderPaymentModalOrderItems() {
                const container = document.getElementById('paymentModalOrderItems');
                const itemsCountEl = document.getElementById('modalPayItemsCount');
                if (!container) return;

                let totalQty = 0;
                let html = '';
                cart.forEach(item => {
                    totalQty += item.qty;
                    const sub = item.price * item.qty;
                    html += `
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:5px 0; border-bottom:1px dashed #e2e8f0;">
                            <div style="flex:1; padding-right:8px;">
                                <div style="font-weight:700; color:#0f172a;">${escapeHtml(item.name)}</div>
                                ${item.variantName ? `<span style="font-size:0.68rem; color:#166534; background:#f0fdf4; border:1px solid #bbf7d0; padding:1px 6px; border-radius:4px; display:inline-block; margin-top:2px;">${escapeHtml(item.variantName)}</span>` : ''}
                                ${item.notes ? `<div style="font-size:0.68rem; color:#64748b; font-style:italic;">* ${escapeHtml(item.notes)}</div>` : ''}
                                <div style="font-size:0.7rem; color:#64748b; margin-top:1px;">${item.qty} x Rp ${formatRupiah(item.price)}</div>
                            </div>
                            <div style="font-weight:800; color:#0f172a; white-space:nowrap;">Rp ${formatRupiah(sub)}</div>
                        </div>
                    `;
                });
                container.innerHTML = html || '<div style="color:#94a3b8; text-align:center; padding:8px;">(Keranjang Kosong)</div>';
                if (itemsCountEl) itemsCountEl.innerText = `${totalQty} Items`;
            }

            // Helper: Format nomor telepon menjadi standar internasional WhatsApp Indonesia (62xxx)
            function formatWaPhone(raw) {
                if (!raw) return '';
                let p = String(raw).replace(/[^0-9]/g, '');
                if (p.startsWith('0')) {
                    p = '62' + p.substring(1);
                } else if (p.startsWith('8')) {
                    p = '62' + p;
                } else if (!p.startsWith('62') && p.length >= 9) {
                    p = '62' + p;
                }
                return p;
            }

            // ── Share Bill to WhatsApp (Langsung Target ke Nomor Pelanggan + Auto Unduh Struk PNG) ──
            async function shareBillToWhatsApp() {
                if (!currentCompletedOrder) {
                    showPosAlert('Data transaksi tidak ditemukan.', 'Perhatian', 'warning');
                    return;
                }
                const order = currentCompletedOrder;
                const addr = order.shipping_address || {};
                const storeTitle = '{{ $storeName }}';
                const invNum = order.order_number || ('INV-' + order.id);
                const totalFormatted = formatRupiah(order.total || order.subtotal || 0);

                let itemsText = '';
                (order.items || []).forEach((item, idx) => {
                    const itemQty = item.qty || item.quantity || 1;
                    const itemPrice = formatRupiah(item.price || 0);
                    const itemSub = formatRupiah((item.price || 0) * itemQty);
                    itemsText += `${idx + 1}. *${item.product_name}*${item.variant_name ? ' (' + item.variant_name + ')' : ''}\n    ${itemQty} x Rp ${itemPrice} = Rp ${itemSub}\n`;
                });

                const custName = (addr.name || order.customer_name || document.getElementById('posCustomerName')?.value || '').trim();
                const tableVal = addr.table || document.getElementById('posTableQuickSelect')?.value || '';
                const tableInfo = tableVal ? `\nMeja: ${tableVal}` : '';
                const cashierName = document.getElementById('recCashierNameBill')?.innerText || '{{ $currentCashier ? $currentCashier->name : auth()->user()->name }}';

                // Greeting — pakai nama jika ada
                const greeting = custName ? `Halo *${custName}*, terima kasih sudah berbelanja! 😊` : `Halo, terima kasih sudah berbelanja! 😊`;

                const payMethod = addr.payment_method === 'cash' ? 'Tunai (Cash)' : (addr.payment_method === 'cashless' || addr.payment_method === 'qris' ? 'Non-Tunai / QRIS' : (order.payment_method ? order.payment_method.toUpperCase() : 'LUNAS'));

                let subtotalText = `Subtotal: Rp ${formatRupiah(order.subtotal || 0)}\n`;
                let discText = '';
                if (order.discount && parseFloat(order.discount) > 0) {
                    const discName = addr.discount_details?.name ? ` (${addr.discount_details.name})` : '';
                    discText = `Diskon${discName}: -Rp ${formatRupiah(order.discount)}\n`;
                }
                let feeText = '';
                const breakdown = Array.isArray(addr.fees_breakdown) ? addr.fees_breakdown : [];
                if (breakdown.length > 0) {
                    breakdown.forEach(f => {
                        const amt = parseFloat(f.amount) || 0;
                        if (amt > 0) {
                            const rateSuffix = f.type === 'percent' ? ` (${f.value}%)` : '';
                            feeText += `${f.name}${rateSuffix}: Rp ${formatRupiah(amt)}\n`;
                        }
                    });
                } else if (addr.service_fee && parseFloat(addr.service_fee) > 0) {
                    feeText += `Biaya Layanan & Pajak: Rp ${formatRupiah(addr.service_fee)}\n`;
                }
                let cashDetailsText = '';
                if (addr.payment_method === 'cash' && addr.cash_paid) {
                    cashDetailsText = `\nTunai Dibayar: Rp ${formatRupiah(addr.cash_paid)}\nKembalian: Rp ${formatRupiah(addr.cash_change || 0)}`;
                }

                const msg = `${greeting}\n\n🧾 *STRUK BELANJA RESMI*\n*${storeTitle}*\n--------------------------------\nNo. Invoice: *#${invNum}*${custName ? '\nPelanggan: ' + custName : ''}${tableInfo}\nKasir: ${cashierName}\nTanggal: ${new Date().toLocaleDateString('id-ID', {day:'2-digit',month:'long',year:'numeric'})}\n--------------------------------\n*DETAIL PESANAN:*\n${itemsText}--------------------------------\n*RINGKASAN:* \n${subtotalText}${discText}${feeText}*TOTAL: Rp ${totalFormatted}*\nMetode: ${payMethod}${cashDetailsText}\nStatus: *LUNAS (BERHASIL)* ✅\n--------------------------------\n_Terima kasih telah berbelanja di ${storeTitle}!_\nPowered by buyle.id`;

                // Targetkan langsung nomor pelanggan
                let phone = formatWaPhone(window._currentReceiptPhone || addr.phone || addr.customer_phone || order.customer_phone || order.phone || document.getElementById('posCustomerPhone')?.value || '');
                if (!phone) {
                    const manual = prompt('Nomor WhatsApp pelanggan belum terisi pada transaksi ini.\nMasukkan nomor WhatsApp tujuan (contoh: 081234567890):', '');
                    if (manual) {
                        phone = formatWaPhone(manual);
                        window._currentReceiptPhone = phone;
                    }
                }

                const encodedMsg = encodeURIComponent(msg);
                // Langsung arahkan ke chat nomor pelanggan agar tidak perlu pilih kontak di WA
                const waUrl = phone ? `https://api.whatsapp.com/send?phone=${phone}&text=${encodedMsg}` : `https://api.whatsapp.com/send?text=${encodedMsg}`;

                // 1. Generate dan Unduh Struk PNG via html2canvas
                const billArea = document.getElementById('printablePosReceiptArea');
                const invCode = document.getElementById('recOrderCode')?.innerText || invNum || 'POS-RECEIPT';
                const filename = `Struk-${String(invCode).replace(/[^a-zA-Z0-9_-]/g, '')}.png`;

                const executeOpenWA = () => {
                    setTimeout(() => {
                        window.open(waUrl, '_blank');
                    }, 500);
                };

                if (billArea && typeof html2canvas !== 'undefined') {
                    try {
                        const canvas = await html2canvas(billArea, { scale: 2.5, useCORS: true, backgroundColor: '#ffffff' });
                        // Download gambar Struk PNG
                        const link = document.createElement('a');
                        link.download = filename;
                        link.href = canvas.toDataURL('image/png');
                        link.click();

                        // Salin gambar ke clipboard jika didukung browser
                        if (navigator.clipboard && window.ClipboardItem) {
                            canvas.toBlob(blob => {
                                if (blob) {
                                    navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]).catch(() => {});
                                }
                            }, 'image/png');
                        }
                    } catch (e) {
                        console.warn('html2canvas error:', e);
                    }
                    executeOpenWA();
                } else if (billArea && typeof html2canvas === 'undefined') {
                    const s = document.createElement('script');
                    s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
                    s.onload = async () => {
                        try {
                            const canvas = await html2canvas(billArea, { scale: 2.5, useCORS: true, backgroundColor: '#ffffff' });
                            const link = document.createElement('a');
                            link.download = filename;
                            link.href = canvas.toDataURL('image/png');
                            link.click();
                        } catch (e) {}
                        executeOpenWA();
                    };
                    s.onerror = () => executeOpenWA();
                    document.head.appendChild(s);
                } else {
                    executeOpenWA();
                }
            }

            // ── Auto Generate Bill Image via html2canvas ──
            function generateBillImage() {
                const billArea = document.getElementById('printablePosReceiptArea');
                const btn = document.getElementById('btnDownloadBillImg');
                if (!billArea) return;

                const origHtml = btn ? btn.innerHTML : '';
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menggenerate Gambar...';
                }

                if (typeof html2canvas === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
                    script.onload = () => doCapture();
                    document.head.appendChild(script);
                } else {
                    doCapture();
                }

                function doCapture() {
                    html2canvas(billArea, {
                        scale: 2.5,
                        useCORS: true,
                        backgroundColor: '#ffffff'
                    }).then(canvas => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = origHtml;
                        }
                        const imgData = canvas.toDataURL('image/png');
                        const link = document.createElement('a');
                        const orderCode = document.getElementById('recOrderCode')?.innerText || 'POS-RECEIPT';
                        link.download = `Struk-${orderCode.replace(/[^a-zA-Z0-9_-]/g, '')}.png`;
                        link.href = imgData;
                        link.click();

                        if (typeof showPosAlert === 'function') {
                            showPosAlert('Gambar bill struk berhasil diunduh dan siap dikirim ke WhatsApp!', 'Gambar Siap', 'success');
                        }
                    }).catch(err => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = origHtml;
                        }
                        console.error('html2canvas error:', err);
                        alert('Gagal membuat gambar struk: ' + err.message);
                    });
                }
            }

</script>

    <!-- ── PESANAN MASUK POPUP (Self-Order Live Notification) ── -->

    <!-- ── POPUP OFFLINE MODE ── -->
    <div id="posOfflineModal" style="display:none; position:fixed; inset:0; z-index:999997; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1.25rem;">
        <div style="background:#ffffff; border-radius:20px; max-width:380px; width:100%; padding:1.5rem; box-shadow:0 24px 48px rgba(15,23,42,0.25); text-align:center;">
            <div style="width:56px; height:56px; border-radius:50%; background:#fef2f2; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; font-size:1.75rem;">📡</div>
            <div style="font-size:1rem; font-weight:800; color:#0f172a; margin-bottom:6px;">Mode Offline</div>
            <div style="font-size:0.8rem; color:#64748b; line-height:1.5; margin-bottom:1.25rem;">
                Koneksi internet terputus. <strong>Pencatatan penjualan kasir (pembayaran tunai)</strong> masih bisa dilakukan dan akan otomatis tersinkron saat kembali online.<br><br>
                <span style="color:#dc2626; font-weight:700;">Self-Order tidak tersedia saat offline.</span>
            </div>
            <button type="button" onclick="closePosOfflineModal()" style="width:100%; height:44px; border-radius:12px; background:#0f172a; color:#ffffff; font-weight:700; font-size:0.85rem; border:none; cursor:pointer;">
                Mengerti, Lanjutkan
            </button>
        </div>
    </div>
    <div id="posNewOrderPopup" style="display:none; position:fixed; top:72px; left:50%; transform:translateX(-50%); z-index:999990; width:calc(100% - 32px); max-width:380px;">
        <div style="background:#ffffff; border-radius:20px; box-shadow:0 8px 32px rgba(0,0,0,0.18); border:1.5px solid #bbf7d0; overflow:hidden; animation:slideDownPop 0.3s cubic-bezier(0.16,1,0.3,1);">
            <div style="background:linear-gradient(135deg,#1eb349,#a5cf37); padding:12px 16px; display:flex; align-items:center; gap:10px;">
                <div style="width:34px; height:34px; border-radius:50%; background:rgba(255,255,255,0.25); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.2rem;">&#x1F514;</div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:0.9rem; font-weight:800; color:#ffffff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" id="posPopupTitle">Pesanan Masuk!</div>
                    <div style="font-size:0.72rem; color:rgba(255,255,255,0.85); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" id="posPopupBody">Ada pesanan baru masuk</div>
                </div>
                <button onclick="closePosNewOrderPopup()" style="background:rgba(255,255,255,0.2); border:none; color:#fff; cursor:pointer; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0;">&#x2715;</button>
            </div>
            <div style="padding:12px 16px; display:flex; gap:8px;">
                <button onclick="closePosNewOrderPopup(); switchPosScreen('transactions');"
                    style="flex:1; height:38px; border-radius:999px; background:#1eb349; color:#fff; border:none; font-size:0.82rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><circle cx="12" cy="12" r="3"/></svg>
                    Lihat Transaksi
                </button>
                <button onclick="closePosNewOrderPopup();"
                    style="height:38px; padding:0 16px; border-radius:999px; background:#f1f5f9; color:#64748b; border:none; font-size:0.82rem; font-weight:700; cursor:pointer;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ── BUYLE POS BOTTOM NAVIGATION BAR (Home, Transaksi, Laporan, Profil) ── -->
    <div class="pos-bottom-nav-wrap" id="posBottomNavWrap">
        <div class="pos-bottom-nav-bar">
            <!-- 1. Home / Kasir -->
            <button type="button" class="pos-nav-item active" id="posNavBtnHome" onclick="switchPosScreen('catalog')" title="Kasir Home">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span class="pos-nav-label">Home</span>
            </button>

            <!-- 2. Transactions -->
            <button type="button" class="pos-nav-item" id="posNavBtnTrans" onclick="switchPosScreen('transactions')" title="Transaksi" style="position:relative;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                    <circle cx="12" cy="12" r="3"/>
                    <line x1="2" y1="9" x2="6" y2="9"/>
                    <line x1="18" y1="15" x2="22" y2="15"/>
                </svg>
                <span class="pos-nav-label">Transaksi</span>
                <span id="posTransNavBadge" style="display:none; position:absolute; top:2px; right:8px; background:#ef4444; color:#ffffff; font-size:10px; font-weight:800; border-radius:999px; min-width:18px; height:18px; padding:0 5px; align-items:center; justify-content:center; border:2px solid #ffffff; box-shadow:0 2px 6px rgba(239,68,68,0.45); line-height:1; z-index:5;">0</span>
            </button>

            <!-- 3. Laporan (Halaman Baru /pos/reports) -->
            <a href="{{ route('creator.pos.reports') }}" class="pos-nav-item" id="posNavBtnReports" title="Laporan POS">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
                <span class="pos-nav-label">Laporan</span>
            </a>

            <!-- 4. Profile (Halaman Baru /pos/profile) -->
            <a href="{{ route('creator.pos.profile') }}" class="pos-nav-item" id="posNavBtnProfile" title="Profil Kasir & Toko">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="pos-nav-label">Profil</span>
            </a>
        </div>
    </div>

    <!-- ── POP-UP MODAL: PILIHAN TAMBAH (+ Add Item) ── -->
    <div class="pos-modal-overlay" id="posAddOptionsModal" style="z-index: 100025;">
        <div class="pos-modal-card" style="max-width: 380px !important; width: 90vw !important; height: auto !important; max-height: fit-content !important; min-height: unset !important; border-radius: 24px !important; padding: 1.25rem !important; margin: auto !important; align-self: center !important; box-shadow: 0 20px 50px rgba(0,0,0,0.3) !important; background: #ffffff !important;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 1.2rem;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:34px; height:34px; border-radius:10px; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#166534;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Tambah Baru</h4>
                        <div style="font-size:0.72rem; color:#64748b; margin-top:1px;">Pilih jenis item yang ingin ditambahkan</div>
                    </div>
                </div>
                <button type="button" class="pos-modal-close" onclick="closeModal('posAddOptionsModal')">&times;</button>
            </div>

            <div style="display:flex; flex-direction:column; gap:10px;">
                <!-- Opsi 1: Tambah Produk Baru -->
                <button type="button" class="pos-add-option-card" onclick="openQuickProductModal()">
                    <div class="pos-add-option-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:0.92rem; font-weight:800; color:#0f172a;">Tambah Produk Baru</div>
                        <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">Input nama, harga, dan masukkan langsung ke kasir</div>
                    </div>
                    <svg width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>

                <!-- Opsi 2: Tambah Kelompok Produk -->
                <button type="button" class="pos-add-option-card" onclick="openQuickCategoryModal()">
                    <div class="pos-add-option-icon" style="background:#eff6ff; border-color:#bfdbfe; color:#1d4ed8;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:0.92rem; font-weight:800; color:#0f172a;">Tambah Kelompok Produk</div>
                        <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">Buat kategori baru untuk mengelompokkan menu</div>
                    </div>
                    <svg width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- ── POP-UP MODAL: FORM TAMBAH PRODUK CEPAT ── -->
    <div class="pos-modal-overlay" id="posQuickProductModal" style="z-index: 100030;">
        <div class="pos-modal-card" style="max-width: 460px; width: 94vw; border-radius: 24px; padding: 1.4rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 1.2rem;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:34px; height:34px; border-radius:10px; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#166534;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Produk Baru</h4>
                        <div style="font-size:0.72rem; color:#64748b; margin-top:1px;">Simpan langsung ke kasir POS</div>
                    </div>
                </div>
                <button type="button" class="pos-modal-close" onclick="closeModal('posQuickProductModal')">&times;</button>
            </div>

            <form id="formQuickProduct" onsubmit="submitQuickProduct(event)">
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <div>
                        <label style="font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px; display:block;">Nama Produk <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="qpName" class="form-control" placeholder="Contoh: Es Teh Manis" required style="border-radius:12px; height:44px; font-size:0.85rem;">
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                        <div>
                            <label style="font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px; display:block;">Harga (Rp) <span style="color:#ef4444;">*</span></label>
                            <input type="number" id="qpPrice" class="form-control" placeholder="0" min="0" required style="border-radius:12px; height:44px; font-size:0.85rem;">
                        </div>
                        <div>
                            <label style="font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px; display:block;">Stok (Opsional)</label>
                            <input type="number" id="qpStock" class="form-control" placeholder="Unlimited" min="0" style="border-radius:12px; height:44px; font-size:0.85rem;">
                        </div>
                    </div>

                    <div>
                        <label style="font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px; display:block;">Kelompok Produk</label>
                        <select id="qpCategory" class="form-control" style="border-radius:12px; height:44px; font-size:0.85rem;">
                            <option value="">Umum</option>
                            @if(isset($categories))
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label style="font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px; display:block;">Foto Produk (Opsional)</label>
                        <input type="file" id="qpImage" accept="image/*" class="form-control" style="border-radius:12px; font-size:0.8rem; padding:8px;">
                    </div>

                    <div style="display:flex; align-items:center; gap:8px; margin-top:8px;">
                        <button type="button" class="btn-outline-bio" onclick="closeModal('posQuickProductModal')" style="flex:1; height:44px; border-radius:999px;">Batal</button>
                        <button type="submit" class="btn-submit-green" id="btnSubmitQuickProduct" style="flex:1; height:44px; border-radius:999px; margin-top:0;">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ── POP-UP MODAL: FORM TAMBAH KELOMPOK PRODUK CEPAT ── -->
    <div class="pos-modal-overlay" id="posQuickCategoryModal" style="z-index: 100030;">
        <div class="pos-modal-card" style="max-width: 400px; width: 92vw; border-radius: 24px; padding: 1.4rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 1.2rem;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:34px; height:34px; border-radius:10px; background:#eff6ff; border:1px solid #bfdbfe; display:flex; align-items:center; justify-content:center; color:#1d4ed8;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Kelompok Produk</h4>
                        <div style="font-size:0.72rem; color:#64748b; margin-top:1px;">Kategori baru untuk menu kasir</div>
                    </div>
                </div>
                <button type="button" class="pos-modal-close" onclick="closeModal('posQuickCategoryModal')">&times;</button>
            </div>

            <form id="formQuickCategory" onsubmit="submitQuickCategory(event)">
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <div>
                        <label style="font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px; display:block;">Nama Kelompok <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="qcName" class="form-control" placeholder="Contoh: Cemilan, Kopi, Paket Hemat" required style="border-radius:12px; height:44px; font-size:0.85rem;">
                    </div>

                    <div style="display:flex; align-items:center; gap:8px; margin-top:8px;">
                        <button type="button" class="btn-outline-bio" onclick="closeModal('posQuickCategoryModal')" style="flex:1; height:44px; border-radius:999px;">Batal</button>
                        <button type="submit" class="btn-submit-green" id="btnSubmitQuickCategory" style="flex:1; height:44px; border-radius:999px; margin-top:0;">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ── POP-UP MINIMALIS: INSTALL PWA BUYLE POS ── -->
    <div id="posInstallBanner" style="display:none; position:fixed; bottom:88px; left:16px; right:16px; max-width:440px; margin:0 auto; background:#ffffff; border-radius:20px; padding:0.85rem 1.1rem; box-shadow:0 12px 36px rgba(15,23,42,0.18), 0 2px 8px rgba(0,0,0,0.06); border:1.5px solid #e2e8f0; z-index:100050; align-items:center; justify-content:space-between; gap:0.75rem; animation: slideUpPos 0.3s cubic-bezier(0.16,1,0.3,1);">
        <div style="display:flex; align-items:center; gap:0.75rem; min-width:0;">
            <div style="width:42px; height:42px; border-radius:12px; background:linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 10px rgba(30,179,73,0.3);">
                <img src="/buyle-logo.png" alt="Buyle POS" style="width:26px; height:26px; object-fit:contain; filter:brightness(0) invert(1);">
            </div>
            <div style="min-width:0;">
                <div style="font-size:0.85rem; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Install Aplikasi Kasir</div>
                <div style="font-size:0.7rem; color:#64748b; line-height:1.2; margin-top:2px;">Akses super cepat & layar penuh</div>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
            <button type="button" onclick="triggerPwaInstall()" style="background:linear-gradient(135deg, #1eb349 0%, #16933b 100%); color:#ffffff; font-size:0.75rem; font-weight:700; padding:0.5rem 1rem; border-radius:999px; border:none; cursor:pointer; box-shadow:0 2px 8px rgba(30,179,73,0.35);">
                Install
            </button>
            <button type="button" onclick="dismissPosInstallBanner()" style="background:none; border:none; color:#94a3b8; font-size:1.25rem; line-height:1; cursor:pointer; padding:4px 6px;">
                &times;
            </button>
        </div>
    </div>
    <style>
        @keyframes slideUpPos {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes slideDownPop {
            from { transform: translateY(-16px) scale(0.97); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }
    </style>

    <script>
    /* ── PWA INSTALL BANNER CONTROLLER (PWABUILDER COMPLIANT) ── */
    var deferredPosPrompt = null;
    window.addEventListener('beforeinstallprompt', function(e) {
        e.preventDefault();
        deferredPosPrompt = e;
        var isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        var dismissed = sessionStorage.getItem('pos_install_dismissed') === '1';
        if (!isStandalone && !dismissed) {
            var banner = document.getElementById('posInstallBanner');
            if (banner) banner.style.display = 'flex';
        }
    });

    function dismissPosInstallBanner() {
        var banner = document.getElementById('posInstallBanner');
        if (banner) banner.style.display = 'none';
        sessionStorage.setItem('pos_install_dismissed', '1');
    }

    function triggerPwaInstall() {
        if (deferredPosPrompt) {
            deferredPosPrompt.prompt();
            deferredPosPrompt.userChoice.then(function(choiceResult) {
                if (choiceResult.outcome === 'accepted') {
                    dismissPosInstallBanner();
                }
                deferredPosPrompt = null;
            });
        } else {
            var isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            if (isIOS) {
                alert('Untuk memasang Buyle POS di iPhone/iPad:\n1. Tap tombol Bagikan (Share) ⎋ di bawah browser Safari\n2. Pilih "Tambah ke Layar Utama" (Add to Home Screen)');
            } else {
                alert('Untuk memasang Buyle POS:\n1. Klik ikon titik tiga di kanan atas browser\n2. Pilih "Install Buyle POS" atau "Tambahkan ke Layar Utama"');
            }
        }
    }

    // Auto-detect jika belum standalone di perangkat mobile
    document.addEventListener('DOMContentLoaded', function() {
        var isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        var dismissed = sessionStorage.getItem('pos_install_dismissed') === '1';
        if (!isStandalone && !dismissed) {
            setTimeout(function() {
                var banner = document.getElementById('posInstallBanner');
                if (banner && banner.style.display !== 'flex') {
                    banner.style.display = 'flex';
                }
            }, 2500);
        }
    });

    /* ── REAL-TIME PUSH NOTIFICATION & SOUND EFFECT CONTROLLER ── */
    function playPosNotificationChime() {
        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
            osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.12); // A5
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.5);
        } catch(e) {}
    }

    // Polling background notifikasi pesanan & deposit baru setiap 25 detik
    (function() {
        var lastNotifId = 0;
        var popupDismissTimer = null;

        window.closePosNewOrderPopup = function() {
            var popup = document.getElementById('posNewOrderPopup');
            if (popup) popup.style.display = 'none';
            if (popupDismissTimer) { clearTimeout(popupDismissTimer); popupDismissTimer = null; }
        };

        window.showPosNewOrderPopup = function(title, body) {
            var popup = document.getElementById('posNewOrderPopup');
            if (!popup) return;
            var titleEl = document.getElementById('posPopupTitle');
            var bodyEl = document.getElementById('posPopupBody');
            if (titleEl) titleEl.textContent = title || 'Pesanan Masuk!';
            if (bodyEl) bodyEl.textContent = body || 'Ada pesanan baru masuk';
            popup.style.display = 'block';
            if (popupDismissTimer) clearTimeout(popupDismissTimer);
            popupDismissTimer = setTimeout(function() { window.closePosNewOrderPopup(); }, 8000);
        };

        function checkPosLiveUpdates() {
            fetch("{{ route('creator.pos.notifications.check') }}", { headers: { "Accept": "application/json", "X-Requested-With": "XMLHttpRequest" } })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data && data.success && data.notifications && data.notifications.length > 0) {
                        data.notifications.forEach(function(item) {
                            if (item.id > lastNotifId) {
                                lastNotifId = item.id;
                                playPosNotificationChime();

                                // Popup pesanan masuk (visible in-app)
                                window.showPosNewOrderPopup(item.title || 'Pesanan Masuk!', item.body || '');

                                // Tampilkan notifikasi sistem browser/OS
                                if ("Notification" in window && Notification.permission === "granted") {
                                    if ("serviceWorker" in navigator && navigator.serviceWorker.controller) {
                                        navigator.serviceWorker.controller.postMessage({
                                            type: "SHOW_NOTIFICATION",
                                            title: item.title,
                                            options: {
                                                body: item.body,
                                                icon: "/buyle-logo.png",
                                                badge: "/buyle-logo.png"
                                            }
                                        });
                                    } else {
                                        try {
                                            new Notification(item.title, { body: item.body, icon: "/buyle-logo.png" });
                                        } catch(e) {}
                                    }
                                }
                            }
                        });
                    }
                })
                .catch(function(e) {});
        }
        setInterval(checkPosLiveUpdates, 25000);

        // ── OFFLINE MODE DETECTION ──────────────────────────────────
        var _posIsOnline = navigator.onLine;

        function updatePosOnlineStatus(online) {
            _posIsOnline = online;
            var dot = document.getElementById('posNetworkDot');
            var label = document.getElementById('posNetworkLabel');
            var chip = document.getElementById('posNetworkStatusChip');
            if (dot) dot.style.background = online ? '#16a34a' : '#dc2626';
            if (label) { label.textContent = online ? 'Online' : 'Offline'; label.style.color = online ? '#15803d' : '#dc2626'; }
            if (chip) { chip.style.background = online ? '' : '#fef2f2'; chip.style.borderColor = online ? '' : '#fca5a5'; }

            // Sembunyikan opsi pembayaran digital saat offline
            var qrisWrap = document.getElementById('posPayOptCashlessWrap') || document.querySelector('[id*="cashless"]');
            var digitalWrap = document.getElementById('payOptMidtrans') || document.getElementById('posDigitalPayWrap');
            if (digitalWrap) digitalWrap.style.display = online ? '' : 'none';

            if (!online) {
                // Coba sync dulu sebelum tampil modal
                if (typeof syncOfflineOrdersToServer === 'function') syncOfflineOrdersToServer();
                // Tampilkan popup offline
                var modal = document.getElementById('posOfflineModal');
                if (modal) modal.style.display = 'flex';
            }
        }

        function closePosOfflineModal() {
            var modal = document.getElementById('posOfflineModal');
            if (modal) modal.style.display = 'none';
        }

        window.addEventListener('online', function() {
            updatePosOnlineStatus(true);
            if (typeof showPosToast === 'function') showPosToast('Koneksi internet kembali online.', 'success');
            if (typeof syncOfflineOrdersToServer === 'function') syncOfflineOrdersToServer();
        });
        window.addEventListener('offline', function() {
            updatePosOnlineStatus(false);
        });

        // Jalankan status awal
        updatePosOnlineStatus(navigator.onLine);

        // ── OFFLINE ORDER SAVE & SYNC ────────────────────────────────
        var _posOfflineOrdersKey = 'buyle_pos_offline_orders_{{ auth()->id() }}';

        function saveOrderOffline(orderPayload) {
            try {
                var existing = JSON.parse(localStorage.getItem(_posOfflineOrdersKey) || '[]');
                orderPayload._offline_id = Date.now();
                existing.push(orderPayload);
                localStorage.setItem(_posOfflineOrdersKey, JSON.stringify(existing));
                if (typeof showPosToast === 'function') showPosToast('Pesanan disimpan offline. Akan tersinkron saat online.', 'warning');
            } catch(e) {}
        }

        function syncOfflineOrdersToServer() {
            try {
                var pending = JSON.parse(localStorage.getItem(_posOfflineOrdersKey) || '[]');
                if (!pending || pending.length === 0) return;
                fetch('{{ route("creator.pos.sync-offline") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' },
                    body: JSON.stringify({ orders: pending })
                }).then(function(r) { return r.json(); }).then(function(data) {
                    if (data.success) {
                        localStorage.removeItem(_posOfflineOrdersKey);
                        if (data.synced > 0 && typeof showPosToast === 'function') showPosToast(data.synced + ' pesanan offline berhasil tersinkron!', 'success');
                    }
                }).catch(function() {});
            } catch(e) {}
        }

        // ── HEARTBEAT KASIR POS setiap 25 detik ─────────────────────
        setInterval(function() {
            if (!navigator.onLine) return;
            fetch('{{ route("creator.pos.heartbeat") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' },
                body: '{}'
            }).catch(function() {});
        }, 25000);

        // ── CATALOG CACHE ke localStorage untuk akses offline ───────
        try {
            @php
                $posCatalogJson = json_encode($products->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => (float) $p->price,
                    'sale_price' => $p->sale_price ? (float) $p->sale_price : null,
                    'stock' => $p->stock,
                    'group_id' => $p->creator_group_id ?? null,
                ])->values());
            @endphp
            var _catalogData = {!! $posCatalogJson ?? '[]' !!};
            localStorage.setItem('buyle_pos_catalog_{{ auth()->id() }}', JSON.stringify(_catalogData));
        } catch(e) {}
        // ────────────────────────────────────────────────────────────
    })();
    </script>
@endsection




