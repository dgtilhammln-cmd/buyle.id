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
            overflow: hidden;
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
            grid-template-columns: repeat(auto-fill, minmax(135px, 1fr));
            gap: 0.75rem;
        }

        @media (max-width: 576px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.65rem;
            }
        }

        .product-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.75rem;
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
            background: #f8fafc;
            margin-bottom: 0.6rem;
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
            font-size: 0.88rem;
            font-weight: 800;
            color: #0f172a;
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

        .summary-row > span:last-child,
        .summary-row > strong:last-child {
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
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.6);
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
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.75; transform: scale(0.98); }
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
            max-height: 88vh;
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
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
            box-shadow: 0 8px 28px rgba(15,23,42,0.10), 0 2px 8px rgba(15,23,42,0.06);
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
    </style>
@endsection

@section('topbar_actions')
    <div class="pos-topbar-bar">
        <!-- Compact Indikator Kasir -->
        <div class="pos-topbar-chip" title="Kasir yang sedang bertugas">
            <span class="pos-status-pulse"></span>
            <span style="color:#64748b; font-weight:600; font-size:0.7rem;">Kasir:</span>
            <span id="topbarCashierNameDisplay" style="color:#0f172a; font-weight:800;">{{ $currentCashier ? $currentCashier->name : auth()->user()->name }}</span>
            @if($currentCashier && $currentCashier->shift_hours)
                <span style="font-size:0.65rem; background:#dcfce7; color:#15803d; font-weight:800; padding:1px 5px; border-radius:999px;">{{ $currentCashier->shift_hours }}</span>
            @endif
        </div>

        <!-- Compact Saldo Deposit Kasir -->
        <div class="pos-topbar-chip pos-deposit-chip" title="Saldo Deposit POS">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                <rect x="2" y="5" width="20" height="14" rx="2" />
                <line x1="2" y1="10" x2="22" y2="10" />
            </svg>
            <span id="posBalanceDisplay" style="font-size:0.8rem; font-weight:800; color:{{ $posBalance < 10000 ? '#dc2626' : '#166534' }};">Rp {{ number_format($posBalance, 0, ',', '.') }}</span>
            <button type="button" onclick="openDepositModal()" class="pos-deposit-add-btn" title="Isi Saldo POS">+</button>
        </div>

        <!-- Tombol Pengaturan POS + Hover Dropdown Menu -->
        <div class="pos-settings-dropdown-wrap">
            <button type="button" class="pos-topbar-btn" onclick="openPosSettingsModal('struk')" title="Pengaturan POS &amp; Kasir">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                </svg>
                <span class="pos-btn-text">Pengaturan</span>
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-left:2px; opacity:0.7;">
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
        <button type="button" class="pos-topbar-btn" onclick="openModal('posHistoryModal')" title="Rekap Shift &amp; Riwayat">
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
            <button type="button" onclick="document.getElementById('logout-form').submit();" class="pos-topbar-btn pos-btn-logout" title="Logout Kasir">
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
        $storeName = $profile->store_name ?? auth()->user()->name ?? 'Toko Saya';
    @endphp

    {{-- Midtrans Snap JS --}}
    @if($midtransClientKey)
        <script src="{{ $snapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    @endif

    <!-- SKELETON LOADING PLACEHOLDER (Smooth & Lightweight) -->
    <div id="pos-skeleton" class="pos-wrapper" style="margin-bottom: 2rem;">
        <!-- LEFT COLUMN: SKELETON KATALOG -->
        <div>
            <div class="prof-card">
                <div class="prof-card-head">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <div class="pos-skel-bone" style="width:16px; height:16px; border-radius:4px;"></div>
                        <div class="pos-skel-bone" style="width:140px; height:16px;"></div>
                    </div>
                    <div class="pos-skel-bone" style="width:110px; height:24px; border-radius:20px;"></div>
                </div>

                <div class="prof-card-body">
                    <!-- Search Bar Skeleton -->
                    <div style="height:44px; margin-bottom:1rem; border-radius:12px;" class="pos-skel-bone"></div>

                    <!-- Category Pills Skeleton -->
                    <div class="pos-category-pills-wrap" style="margin-bottom:1.25rem;">
                        <div class="pos-skel-bone" style="width:125px; height:34px; border-radius:999px;"></div>
                        <div class="pos-skel-bone" style="width:165px; height:34px; border-radius:999px;"></div>
                        <div class="pos-skel-bone" style="width:160px; height:34px; border-radius:999px;"></div>
                    </div>

                    <!-- Product Grid Skeleton (8 items) -->
                    <div class="product-grid">
                        @for($i = 0; $i < 8; $i++)
                            <div class="pos-skel-card">
                                <div class="pos-skel-bone"
                                    style="width:100%; aspect-ratio:1/1; border-radius:12px; margin-bottom:0.6rem;"></div>
                                <div class="pos-skel-bone" style="width:80%; height:13px; margin-bottom:0.35rem;"></div>
                                <div class="pos-skel-bone" style="width:50%; height:11px; margin-bottom:0.6rem;"></div>
                                <div class="pos-skel-bone" style="width:65%; height:16px; border-radius:4px; margin-top:auto;">
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: SKELETON CART -->
        <div>
            <div class="prof-card">
                <div class="prof-card-head">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <div class="pos-skel-bone" style="width:16px; height:16px; border-radius:4px;"></div>
                        <div class="pos-skel-bone" style="width:160px; height:16px;"></div>
                    </div>
                    <div class="pos-skel-bone" style="width:70px; height:16px;"></div>
                </div>

                <div class="prof-card-body">
                    <!-- Form Customer Fields Skeleton -->
                    <div style="margin-bottom:0.85rem;">
                        <div class="pos-skel-bone" style="width:130px; height:12px; margin-bottom:0.4rem;"></div>
                        <div class="pos-skel-bone" style="width:100%; height:42px; border-radius:12px;"></div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.85rem; margin-bottom:0.85rem;">
                        <div>
                            <div class="pos-skel-bone" style="width:100px; height:12px; margin-bottom:0.4rem;"></div>
                            <div class="pos-skel-bone" style="width:100%; height:42px; border-radius:12px;"></div>
                        </div>
                        <div>
                            <div class="pos-skel-bone" style="width:110px; height:12px; margin-bottom:0.4rem;"></div>
                            <div class="pos-skel-bone" style="width:100%; height:42px; border-radius:12px;"></div>
                        </div>
                    </div>

                    <!-- Cart Item Rows Skeleton (3 items) -->
                    <div style="margin: 1.25rem 0; display:flex; flex-direction:column; gap:0.75rem;">
                        @for($i = 0; $i < 3; $i++)
                            <div
                                style="display:flex; align-items:center; justify-content:space-between; padding:0.6rem; border-radius:12px; background:#f8fafc; border:1px solid #f1f5f9;">
                                <div style="display:flex; flex-direction:column; gap:4px; flex:1;">
                                    <div class="pos-skel-bone" style="width:65%; height:13px;"></div>
                                    <div class="pos-skel-bone" style="width:40%; height:11px;"></div>
                                </div>
                                <div class="pos-skel-bone" style="width:80px; height:30px; border-radius:999px;"></div>
                            </div>
                        @endfor
                    </div>

                    <!-- Summary Box Skeleton -->
                    <div
                        style="background:#f8fafc; border-radius:16px; padding:1rem; border:1.5px solid #e2e8f0; margin-bottom:1.25rem; display:flex; flex-direction:column; gap:0.6rem;">
                        <div style="display:flex; justify-content:space-between;">
                            <div class="pos-skel-bone" style="width:70px; height:12px;"></div>
                            <div class="pos-skel-bone" style="width:90px; height:12px;"></div>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <div class="pos-skel-bone" style="width:80px; height:12px;"></div>
                            <div class="pos-skel-bone" style="width:60px; height:12px;"></div>
                        </div>
                        <div
                            style="border-top:1px dashed #cbd5e1; padding-top:0.6rem; margin-top:0.2rem; display:flex; justify-content:space-between; align-items:center;">
                            <div class="pos-skel-bone" style="width:90px; height:16px;"></div>
                            <div class="pos-skel-bone" style="width:120px; height:20px;"></div>
                        </div>
                    </div>

                    <!-- Payment Button Skeleton -->
                    <div class="pos-skel-bone" style="width:100%; height:48px; border-radius:999px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- POS Real Content Wrapper -->
    <div id="pos-real-content" style="opacity:0; transition:opacity 0.25s ease;">
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
                        <!-- Search Bar Input -->
                        <div class="search-input-wrap">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            <input type="text" id="posProductSearch" class="form-input"
                                placeholder="Cari nama produk / item..." oninput="filterProducts()">
                        </div>

                        <!-- Filter Kategori Premium (Instant 0ms Client-Side Filtering) -->
                        <div class="pos-category-pills-wrap">
                            <button type="button" class="pos-cat-pill active" data-cat="all"
                                onclick="setPosCategoryFilter('all', this)">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="7" height="7" />
                                    <rect x="14" y="3" width="7" height="7" />
                                    <rect x="14" y="14" width="7" height="7" />
                                    <rect x="3" y="14" width="7" height="7" />
                                </svg>
                                Semua Produk
                                <span class="pos-cat-count" id="catCountAll">0</span>
                            </button>
                            <button type="button" class="pos-cat-pill" data-cat="physical"
                                onclick="setPosCategoryFilter('physical', this)">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                    <line x1="12" y1="22.08" x2="12" y2="12" />
                                </svg>
                                Produk Fisik / Barang
                                <span class="pos-cat-count" id="catCountPhysical">0</span>
                            </button>
                            <button type="button" class="pos-cat-pill" data-cat="fnb"
                                onclick="setPosCategoryFilter('fnb', this)">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M18 8h1a4 4 0 0 1 0 8h-1" />
                                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" />
                                    <line x1="6" y1="1" x2="6" y2="4" />
                                    <line x1="10" y1="1" x2="10" y2="4" />
                                    <line x1="14" y1="1" x2="14" y2="4" />
                                </svg>
                                Makanan &amp; Minuman
                                <span class="pos-cat-count" id="catCountFnb">0</span>
                            </button>
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
                                    data-price="{{ $prodPrice }}" data-category="{{ $catSlug }}"
                                    data-image="{{ $imgUrl ?? '' }}"
                                    onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prodPrice }}, '{{ $imgUrl ? addslashes($imgUrl) : '' }}')">
                                    <div class="product-qty-badge" id="badge-qty-{{ $prod->id }}">0</div>
                                    <div class="product-img-wrapper"
                                        style="@if(!$imgUrl) background: {{ $themeBg }}; border: 1px solid {{ $themeBorder }}; @endif">
                                        @if($imgUrl)
                                            <img src="{{ $imgUrl }}" alt="{{ $prod->name }}" class="product-img" loading="lazy"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="product-placeholder-wrap"
                                                style="display:none; background: {{ $themeBg }}; width:100%; height:100%; align-items:center; justify-content:center; flex-direction:column; gap:4px;">
                                                @if($iconType === 'drink')
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                        stroke="{{ $themeColor }}" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M17 8h1a4 4 0 1 1 0 8h-1" />
                                                        <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z" />
                                                        <line x1="6" y1="2" x2="6" y2="4" />
                                                        <line x1="10" y1="2" x2="10" y2="4" />
                                                        <line x1="14" y1="2" x2="14" y2="4" />
                                                    </svg>
                                                @elseif($iconType === 'snack')
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                        stroke="{{ $themeColor }}" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                        <circle cx="12" cy="7" r="4" />
                                                    </svg>
                                                @elseif($iconType === 'dish')
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                        stroke="{{ $themeColor }}" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M3 11h18a1 1 0 0 1 1 1v1a8 8 0 0 1-8 8H10a8 8 0 0 1-8-8v-1a1 1 0 0 1 1-1z" />
                                                        <path d="M12 2a5 5 0 0 0-5 5h10a5 5 0 0 0-5-5z" />
                                                        <line x1="12" y1="18" x2="12" y2="21" />
                                                    </svg>
                                                @elseif($iconType === 'digital')
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                        stroke="{{ $themeColor }}" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                                                    </svg>
                                                @else
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                        stroke="{{ $themeColor }}" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                                        <line x1="12" y1="22.08" x2="12" y2="12" />
                                                    </svg>
                                                @endif
                                                <span
                                                    style="font-size:0.62rem; font-weight:800; color:{{ $themeColor }}; letter-spacing:0.05em; text-transform:uppercase;">{{ $badgeText }}</span>
                                            </div>
                                        @else
                                            <div class="product-placeholder-wrap"
                                                style="display:flex; background: {{ $themeBg }}; width:100%; height:100%; align-items:center; justify-content:center; flex-direction:column; gap:4px;">
                                                @if($iconType === 'drink')
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                        stroke="{{ $themeColor }}" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M17 8h1a4 4 0 1 1 0 8h-1" />
                                                        <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z" />
                                                        <line x1="6" y1="2" x2="6" y2="4" />
                                                        <line x1="10" y1="2" x2="10" y2="4" />
                                                        <line x1="14" y1="2" x2="14" y2="4" />
                                                    </svg>
                                                @elseif($iconType === 'snack')
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                        stroke="{{ $themeColor }}" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                        <circle cx="12" cy="7" r="4" />
                                                    </svg>
                                                @elseif($iconType === 'dish')
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                        stroke="{{ $themeColor }}" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M3 11h18a1 1 0 0 1 1 1v1a8 8 0 0 1-8 8H10a8 8 0 0 1-8-8v-1a1 1 0 0 1 1-1z" />
                                                        <path d="M12 2a5 5 0 0 0-5 5h10a5 5 0 0 0-5-5z" />
                                                        <line x1="12" y1="18" x2="12" y2="21" />
                                                    </svg>
                                                @elseif($iconType === 'digital')
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                        stroke="{{ $themeColor }}" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                                                    </svg>
                                                @else
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                        stroke="{{ $themeColor }}" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                                        <line x1="12" y1="22.08" x2="12" y2="12" />
                                                    </svg>
                                                @endif
                                                <span
                                                    style="font-size:0.62rem; font-weight:800; color:{{ $themeColor }}; letter-spacing:0.05em; text-transform:uppercase;">{{ $badgeText }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-name">{{ $prod->name }}</div>
                                    <div class="product-price">Rp {{ number_format($prodPrice, 0, ',', '.') }}</div>
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
                                <div class="d-flex align-items-center gap-1" style="flex:1; flex-wrap:wrap; gap:6px !important;">
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
                                    <strong id="displayDiscountAmount" style="color:#16a34a; font-size:0.85rem;">- Rp 0</strong>
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
                                <span style="line-height:1.35; flex:1; min-width:0;">Platform Fee ({{ $platformFeeRate }}%) &amp; Admin Fee ({{ $adminFeeRate }}%)</span>
                                <span id="displaySystemFees" style="white-space:nowrap; flex-shrink:0; text-align:right; font-weight:600; margin-left:8px;">Rp 0</span>
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
    </div> <!-- Close #pos-real-content -->

    <!-- Mobile Sticky Bottom Bar -->
    <div class="mobile-cart-bar" id="mobileCartBar">
        <div>
            <div style="font-size:0.75rem; color:#94a3b8;" id="mobileCartCount">0 Items</div>
            <div style="font-size:1.1rem; font-weight:800; color:#ffffff;" id="mobileCartTotal">Rp 0</div>
        </div>
        <button type="button" class="btn-submit-green" style="width:auto; padding:0.6rem 1.2rem; font-size:0.82rem;"
            onclick="scrollToCartPanel()">
            Lihat Keranjang
        </button>
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
        <div class="pos-modal-card">
            <div class="pos-modal-head">
                <h5>Metode Pembayaran</h5>
                <button type="button" class="pos-modal-close" onclick="closeModal('posPaymentModal')">&times;</button>
            </div>
            <div class="pos-modal-body">
                <!-- Total Banner Hero -->
                <div
                    style="background: linear-gradient(135deg, #1eb349, #a5cf37); color:#ffffff; padding:0.85rem 1.25rem; border-radius:14px; text-align:center; margin-bottom:1.25rem; box-shadow:0 4px 14px rgba(30,179,73,0.35);">
                    <div
                        style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; opacity:0.9;">
                        Total Tagihan</div>
                    <div style="font-size:1.75rem; font-weight:900; line-height:1.1; margin-top:3px; letter-spacing:-0.02em;"
                        id="modalPayTotal">Rp 0</div>
                </div>

                <!-- Informasi Pelanggan -->
                <div
                    style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:0.85rem 1rem; margin-bottom:1rem;">
                    <div
                        style="font-size:0.7rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                        Info Pelanggan (Opsional)</div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                        <div>
                            <label
                                style="font-size:0.7rem; font-weight:700; color:#475569; display:block; margin-bottom:3px;">Nama</label>
                            <input type="text" id="posCustomerName" class="form-input"
                                style="height:34px; font-size:0.78rem;" placeholder="Pelanggan Umum">
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:3px;">
                                <label style="font-size:0.7rem; font-weight:700; color:#475569; margin:0;">
                                    Meja / Ref
                                </label>
                                <a href="javascript:void(0)" onclick="openPosSettingsModal('tables')" style="font-size:0.67rem; color:#1eb349; text-decoration:none; font-weight:700;">
                                    + Kelola Meja
                                </a>
                            </div>
                            <div style="display:flex; gap:4px;">
                                <select id="posTableQuickSelect" class="form-input" style="height:34px; font-size:0.75rem; flex:1; padding:0 6px;" onchange="onTableQuickSelectChanged(this.value)">
                                    <option value="">Pilih Meja...</option>
                                </select>
                                <input type="text" id="posTableNumber" class="form-input"
                                    style="height:34px; font-size:0.75rem; flex:1; padding:0 8px;" placeholder="No. Meja / Ref">
                            </div>
                        </div>
                        <div>
                            <label
                                style="font-size:0.7rem; font-weight:700; color:#475569; display:block; margin-bottom:3px;">No.
                                HP / WA</label>
                            <input type="text" id="posCustomerPhone" class="form-input"
                                style="height:34px; font-size:0.78rem;" placeholder="08xxx">
                        </div>
                        <div>
                            <label
                                style="font-size:0.7rem; font-weight:700; color:#166534; display:block; margin-bottom:3px;">Email
                                (E-Receipt otomatis)</label>
                            <input type="email" id="posCartCustomerEmail" class="form-input"
                                style="height:34px; font-size:0.78rem;" placeholder="contoh@gmail.com"
                                oninput="syncCustomerEmail(this.value)">
                        </div>
                    </div>
                </div>

                @php
                    $isDepositEmpty = ($posBalance <= 0);
                @endphp

                <!-- Opsi 1: Tunai (Cash) -->
                <div class="pay-option-card {{ $isDepositEmpty ? 'disabled-cash' : 'selected' }}" id="optCash"
                    onclick="{{ $isDepositEmpty ? '' : 'selectPaymentMethod(\'cash\')' }}"
                    style="{{ $isDepositEmpty ? 'background:#f8fafc; border-color:#cbd5e1; opacity:0.6; cursor:not-allowed; pointer-events:none;' : '' }}">
                    <input type="radio" name="pay_method" value="cash" {{ $isDepositEmpty ? 'disabled' : 'checked' }}
                        style="accent-color:#1eb349;">
                    <div
                        style="width:38px; height:38px; border-radius:50%; background:{{ $isDepositEmpty ? '#f1f5f9' : '#f0fdf4' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="{{ $isDepositEmpty ? '#94a3b8' : '#1eb349' }}"
                            stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <circle cx="12" cy="12" r="2" />
                        </svg>
                    </div>
                    <div style="flex:1;">
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:6px;">
                            <div
                                style="font-size:0.88rem; font-weight:700; color:{{ $isDepositEmpty ? '#64748b' : '#0f172a' }};">
                                Tunai (Cash)</div>
                            <span id="cashDepositBadge"
                                style="display:{{ $isDepositEmpty ? 'inline-block' : 'none' }}; font-size:0.68rem; font-weight:800; background:#fee2e2; color:#dc2626; border:1px solid #fecaca; padding:2px 8px; border-radius:999px;">
                                Saldo Deposit Kurang
                            </span>
                        </div>
                        <div
                            style="font-size:0.72rem; color:{{ $isDepositEmpty ? '#ef4444' : '#64748b' }}; margin-top:1px;">
                            {{ $isDepositEmpty ? 'Tidak dapat dipilih karena saldo deposit kosong.' : 'Bayar & hitung kembalian langsung' }}
                        </div>
                    </div>
                </div>

                <!-- Cash Calculator Panel -->
                <div id="cashCalcPanel"
                    style="display:{{ $isDepositEmpty ? 'none' : 'block' }}; background:#f8fafc; border-radius:12px; border:1.5px solid #cbd5e1; padding:0.85rem; margin-bottom:0.75rem;">
                    <label
                        style="font-size:0.75rem; font-weight:700; color:#166534; display:block; margin-bottom:6px;">Nominal
                        Uang Diterima</label>
                    <div style="position:relative; margin-bottom:0.5rem;">
                        <span
                            style="position:absolute; left:12px; top:50%; transform:translateY(-50%); font-weight:800; font-size:0.9rem; color:#334155;">Rp.</span>
                        <input type="text" id="cashPaidDisplayInput" class="form-input"
                            style="padding-left:40px; height:44px; font-size:1.05rem; font-weight:800; color:#0f172a; border-radius:10px;"
                            placeholder="0" oninput="handleCashInput(this)">
                        <input type="hidden" id="cashPaidInput" value="0">
                    </div>
                    <div class="d-flex flex-wrap gap-1 mb-2">
                        <button type="button" class="btn-quick-cash" onclick="setQuickCash('exact')">Uang Pas</button>
                        <button type="button" class="btn-quick-cash" onclick="setQuickCash(20000)">Rp. 20.000</button>
                        <button type="button" class="btn-quick-cash" onclick="setQuickCash(50000)">Rp. 50.000</button>
                        <button type="button" class="btn-quick-cash" onclick="setQuickCash(100000)">Rp. 100.000</button>
                    </div>
                    <div
                        style="background:#ffffff; border:1px dashed #bbf7d0; border-radius:8px; padding:7px 12px; display:flex; align-items:center; justify-content:space-between;">
                        <span style="font-size:0.78rem; font-weight:700; color:#475569;">Kembalian Kasir:</span>
                        <strong style="font-size:1.05rem; font-weight:900; color:#1eb349;" id="cashChangeDisplay">Rp.
                            0</strong>
                    </div>

                    <!-- Saldo Deposit POS Status Box -->
                    <div id="cashDepositStatusBox"
                        style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:10px; padding:9px 12px; margin-top:8px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.75rem;">
                            <span style="color:#64748b; font-weight:600;">Potongan Fee Tunai (Platform + Admin):</span>
                            <strong style="color:#0f172a;" id="cashFeeRequiredDisplay">Rp 0</strong>
                        </div>
                        <div
                            style="display:flex; justify-content:space-between; align-items:center; font-size:0.75rem; margin-top:4px;">
                            <span style="color:#64748b; font-weight:600;">Saldo Deposit Kasir Anda:</span>
                            <strong style="color:{{ $posBalance < 10000 ? '#dc2626' : '#166534' }};"
                                id="cashUserBalanceDisplay">Rp {{ number_format($posBalance, 0, ',', '.') }}</strong>
                        </div>

                        <!-- Warning Alert when Deposit is Insufficient -->
                        <div id="cashDepositWarning"
                            style="display:none; margin-top:8px; background:#fef2f2; border:1.5px solid #fecaca; border-radius:8px; padding:8px 10px; color:#991b1b; font-size:0.74rem; line-height:1.45;">
                            <div style="display:flex; align-items:flex-start; gap:6px;">
                                <svg width="15" height="15" fill="none" stroke="#dc2626" stroke-width="2.2"
                                    viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="8" x2="12" y2="12" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                                <div>
                                    <strong style="display:block; margin-bottom:2px;">Saldo Deposit POS Tidak
                                        Mencukupi!</strong>
                                    <span id="cashDepositWarningText">Biaya transaksi tunai melebihi saldo deposit. Lakukan
                                        top up agar dapat menerima pembayaran cash.</span>
                                    <div style="margin-top:6px;">
                                        <button type="button" onclick="closeModal('posPaymentModal'); openDepositModal();"
                                            style="background:#dc2626; color:#ffffff; border:none; border-radius:999px; font-size:0.72rem; font-weight:800; padding:4px 12px; cursor:pointer; display:inline-flex; align-items:center; gap:4px; box-shadow:0 2px 6px rgba(220,38,38,0.3);">
                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <line x1="12" y1="5" x2="12" y2="19" />
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                            </svg>
                                            Isi Saldo Deposit Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opsi 2: Non-Tunai / Cashless -->
                <div class="pay-option-card {{ $isDepositEmpty ? 'selected' : '' }}" id="optCashless"
                    onclick="selectPaymentMethod('cashless')">
                    <input type="radio" name="pay_method" value="cashless" {{ $isDepositEmpty ? 'checked' : '' }}
                        style="accent-color:#7c3aed;">
                    <div
                        style="width:38px; height:38px; border-radius:50%; background:#f5f3ff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:0.88rem; font-weight:700; color:#0f172a;">Non-Tunai / Cashless</div>
                        <div style="font-size:0.72rem; color:#64748b; margin-top:1px;">QRIS, Transfer Bank, E-Wallet (GoPay,
                            ShopeePay)</div>
                    </div>
                </div>

                <input type="hidden" id="posCustomerEmail" value="">
            </div>

            <div class="pos-modal-foot">
                <button type="button" class="btn-outline-bio"
                    style="border-color:#e2e8f0; color:#64748b; padding:0.6rem 1rem; font-size:0.8rem; flex-shrink:0; border-radius:999px !important;"
                    onclick="closeModal('posPaymentModal')">Batal</button>
                <button type="button" class="btn-submit-green" id="btnSubmitOrder" onclick="processOrderCheckout()"
                    style="flex:1; margin:0; padding:0.6rem 1rem; font-size:0.82rem; white-space:nowrap; border-radius:999px !important;">
                    Konfirmasi &amp; Bayar
                </button>
            </div>
        </div>
    </div>

    <!-- CUSTOM OVERLAY MODAL 2: Receipt Preview & Modern Thermal Print -->
    <div class="pos-modal-overlay" id="posReceiptModal">
        <div class="pos-modal-card" style="max-width: 440px;">
            <div class="pos-modal-head no-print">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:30px; height:30px; border-radius:50%; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#166534;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                    </div>
                    <h5 style="margin:0; font-size:0.95rem; font-weight:800; color:#0f172a;">Struk Transaksi POS</h5>
                </div>
                <button type="button" class="pos-modal-close"
                    onclick="closeModal('posReceiptModal'); resetPOS();">&times;</button>
            </div>

            <div class="pos-modal-body" id="posReceiptModalContent" style="padding: 1.25rem;">
                <!-- Thermal Struk Printer Container with Slot & Ejection Animation -->
                <div class="receipt-printer-box">
                    <div class="receipt-printer-slot"></div>
                    <div style="padding: 1rem; background: #ffffff;">
                        <div style="font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 11.5px; color: #000000; line-height: 1.35; position: relative; overflow: hidden; background: #ffffff; padding: 4px;"
                            id="printablePosReceiptArea">

                            <!-- Watermark Logo buyle.id di Tengah Struk (Transparansi Minim tapi Tetap Kelihatan) -->
                            <div class="pos-receipt-watermark" style="position: absolute; top: 48%; left: 50%; transform: translate(-50%, -50%) rotate(-25deg); opacity: 0.055; pointer-events: none; z-index: 0; width: 190px; text-align: center;">
                                @if(!empty($siteLogoUrl))
                                    <img src="{{ $siteLogoUrl }}" alt="buyle watermark" style="max-width: 140px; max-height: 55px; object-fit: contain; filter: grayscale(100%); margin:0 auto; display:block;">
                                @endif
                                <div style="font-size: 24px; font-weight: 900; color: #000; letter-spacing: 2px;">buyle.id</div>
                                <div style="font-size: 8.5px; font-weight: 800; color: #000; letter-spacing: 1px;">DIGITAL CREATOR CENTER</div>
                            </div>

                            <!-- Konten Struk Transaksi Modern (Z-Index 1) -->
                            <div style="position: relative; z-index: 1;">
                                <!-- 1. Header (Logo/Icon Toko, Nama Toko, Alamat, No Telp, No Struk) -->
                                <div style="text-align: center; margin-bottom: 6px;">
                                    <div id="recHeaderLogoWrap" style="margin-bottom: 4px;">
                                        @if(!empty($posLogoUrl))
                                            <img src="{{ $posLogoUrl }}" alt="Logo Toko" id="recHeaderLogoImg" style="max-height: 48px; max-width: 140px; object-fit: contain; margin: 0 auto 3px auto; display: block;">
                                        @else
                                            <svg id="recHeaderLogoFallbackSvg" width="38" height="38" viewBox="0 0 24 24" fill="currentColor" style="display:inline-block; margin:0 auto 2px auto; color:#000000;">
                                                <path d="M4 4h16a1 1 0 0 1 1 1v2a3 3 0 0 1-3 3 3 3 0 0 1-3-3 3 3 0 0 1-3 3 3 3 0 0 1-3-3 3 3 0 0 1-3 3 3 3 0 0 1-3-3V5a1 1 0 0 1 1-1zm0 8v9a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-9h-2v7H6v-7H4zm5 1h6v6H9v-6z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div id="recHeaderStoreName" style="font-weight: 800; font-size: 15px; color: #000; letter-spacing: -0.2px;">{{ $storeName }}</div>
                                    <div id="recHeaderStoreAddress" style="font-size: 11px; color: #111; line-height: 1.35; max-width: 250px; margin: 2px auto 0 auto;">{{ $posStoreAddress ?: ($profile->address ?? '') }}</div>
                                    <div id="recHeaderStorePhone" style="font-size: 11px; color: #111; margin-top: 1px;">{{ $posStorePhone ? ('No. Telp ' . $posStorePhone) : '' }}</div>
                                    <div id="recOrderCode" style="font-size: 11.5px; font-weight: 600; color: #000; margin-top: 2px; letter-spacing: 0.3px;">-</div>
                                </div>

                                <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

                                <!-- 2. Meta Transaksi (Date, Time, Queue Tag di Kiri; Alias, Kasir, Cabang di Kanan) -->
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; font-size: 11.5px; color: #000; line-height: 1.35;">
                                    <div>
                                        <div id="recDateOnly">-</div>
                                        <div id="recTimeOnly">-</div>
                                        <div style="margin-top: 5px;">
                                            <span id="recQueueTag" style="border: 1.5px solid #16a34a; color: #16a34a; font-weight: 800; font-size: 11px; padding: 2px 7px; border-radius: 4px; display: inline-block;">No. 0-1</span>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div id="recStoreShortSlug" style="font-weight: 600;">{{ $profile->store_slug ?? 'toko' }}</div>
                                        <div id="recCashierName" style="font-weight: 700;">{{ $currentCashier ? $currentCashier->name : auth()->user()->name }}</div>
                                        <div id="recStoreCity" style="font-size: 10.5px; color: #222;">{{ $profile->city_name ?? ($profile->address ? Str::limit($profile->address, 20) : 'Indonesia') }}</div>
                                    </div>
                                </div>

                                <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

                                <!-- 3. Daftar Item Bernomor -->
                                <div id="recNumberedItemsWrap" style="font-size: 11.5px; color: #000;">
                                    <!-- Dynamic items list -->
                                </div>

                                <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

                                <!-- 4. Rincian & Total Pembayaran -->
                                <div style="font-size: 11.5px; color: #000; line-height: 1.45;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-weight: 700;">
                                        <span>Total QTY : <span id="recTotalQty">0</span></span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                                        <span>Sub Total</span>
                                        <span id="recSubtotal" style="font-weight: 600;">Rp 0</span>
                                    </div>
                                    <div id="recDiscountRow" style="display: none; justify-content: space-between; color: #dc2626; margin-bottom: 3px;">
                                        <span id="recDiscountLabel">Diskon</span>
                                        <span id="recDiscount">-Rp 0</span>
                                    </div>
                                    <!-- Dynamic Custom Fees from POS Settings -->
                                    <div id="recCustomFeesWrap" style="display:none;">
                                        <!-- Rendered dynamically -->
                                    </div>
                                    <div style="display: flex; justify-content: space-between; font-weight: 900; font-size: 14.5px; margin: 6px 0 6px 0; border-top: 1px dashed #ccc; padding-top: 5px;">
                                        <span>Total</span>
                                        <span id="recTotal">Rp 0</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                                        <span id="recPayMethodLabel">Bayar (Cash)</span>
                                        <span id="recCashPaidVal" style="font-weight: 600;">Rp 0</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                        <span>Kembali</span>
                                        <span id="recCashChangeVal" style="font-weight: 600;">Rp 0</span>
                                    </div>
                                </div>

                                <div style="border-bottom: 1px dashed #000; margin: 10px 0 8px 0;"></div>

                                <!-- 5. Ucapan & Link Kritik Saran -->
                                <div style="text-align: center; font-size: 11px; color: #111; line-height: 1.4;">
                                    <div style="font-weight: 600; margin-bottom: 3px;">Terimakasih Telah Berbelanja</div>
                                    <div style="font-size: 10px; color: #555;">Link Kritik dan Saran:</div>
                                    <div id="recLinkKritik" style="font-size: 9.5px; color: #222; word-break: break-all; margin-top: 1px; font-weight: 500;">
                                        {{ url('/c/' . ($profile->store_slug ?? 'shop')) }}
                                    </div>
                                </div>

                                <!-- 6. Powered by buyle.id di paling bawah -->
                                <div style="text-align: center; border-top: 1px dashed #ccc; margin-top: 10px; padding-top: 6px; font-size: 9.5px; font-weight: 700; color: #555; letter-spacing: 0.4px;">
                                    powered buyle.id | digital creator center
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- E-Receipt auto-sent notice & manual resend input -->
                <div class="no-print mt-3 pt-3" style="border-top:1.5px dashed #cbd5e1;">
                    <div id="receiptEmailAutoNotice"
                        style="display:none; align-items:center; gap:8px; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:10px; padding:9px 12px; margin-bottom:8px;">
                        <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"
                            style="flex-shrink:0;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <div style="font-size:0.75rem; color:#166534; font-weight:600; line-height:1.4;">
                            E-Receipt terkirim otomatis ke <span id="receiptAutoEmail" style="font-weight:800;"></span>
                        </div>
                    </div>
                    <div id="receiptEmailNoneNotice"
                        style="display:none; font-size:0.72rem; color:#94a3b8; text-align:center; margin-bottom:8px;">
                        Tidak ada email pelanggan saat transaksi.
                    </div>
                    <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:10px 12px;">
                        <div style="font-size:0.72rem; font-weight:700; color:#475569; margin-bottom:6px; display:flex; align-items:center; gap:4px;">
                            <svg width="13" height="13" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            Kirim / Kirim Ulang E-Receipt ke Email
                        </div>
                        <div style="display:flex; gap:6px;">
                            <input type="email" id="recSendEmailInput" class="form-input" style="height:34px; font-size:0.78rem; flex:1;" placeholder="Email pelanggan...">
                            <button type="button" class="btn-submit-green" id="btnSendRecEmail" onclick="sendEReceiptEmail()" style="width:auto; height:34px; padding:0 14px; font-size:0.75rem; flex-shrink:0;">Kirim</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pos-modal-foot no-print" style="gap:0.5rem; padding: 0.85rem 1.25rem;">
                <button type="button" class="btn-outline-bio"
                    style="flex-shrink:0; padding:0.55rem 0.9rem; font-size:0.75rem; border-radius:999px !important; white-space:nowrap;"
                    onclick="printPosReceipt()">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="6 9 6 2 18 2 18 9" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <rect x="6" y="14" width="12" height="8" />
                    </svg>
                    Cetak Struk
                </button>
                <button type="button" class="btn-submit-green"
                    style="flex:1; margin:0; padding:0.55rem 0.9rem; font-size:0.78rem; border-radius:999px !important; white-space:nowrap;"
                    onclick="closeModal('posReceiptModal'); resetPOS();">
                    Selesai &amp; Baru
                </button>
            </div>
        </div>
    </div>

    <!-- CUSTOM OVERLAY MODAL: Pengaturan Modul POS (Logo, Alamat, No Telp, Staf Kasir) -->
    <div class="pos-modal-overlay" id="posSettingsModal" data-static-backdrop="true">
        <div class="pos-modal-card" style="max-width: 780px; width: 95vw; max-height: 92vh;">
            <!-- Modal Header -->
            <div class="pos-modal-head" style="border-bottom: 1.5px solid #f1f5f9; padding: 1.1rem 1.4rem;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; border-radius:10px; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#166534; flex-shrink:0;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3" />
                            <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                        </svg>
                    </div>
                    <div>
                        <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a; line-height:1.2;">Pengaturan Kasir POS</h5>
                        <div style="font-size:0.73rem; color:#64748b; margin-top:2px;">Format struk, pajak/biaya tambahan, diskon produk &amp; akun kasir</div>
                    </div>
                </div>
                <!-- Tombol X Close (Hanya bisa keluar lewat tombol ini atau Simpan) -->
                <button type="button" class="pos-modal-close" onclick="closeModal('posSettingsModal')" title="Tutup Pengaturan">&times;</button>
            </div>

            <!-- Tab Buttons Bar (4 Tab Lengkap: Struk & Toko, Pajak & Biaya, Diskon & Promo, Staf Kasir) -->
            <div style="display:flex; align-items:center; border-bottom:1.5px solid #e2e8f0; background:#f8fafc; padding:0 1.25rem; gap:6px; overflow-x:auto; scrollbar-width:none;">
                <button type="button" class="history-tab-btn active" id="tabBtnSettingStruk" onclick="switchSettingTab('struk')">
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
                <button type="button" class="history-tab-btn" id="tabBtnSettingDiscounts" onclick="switchSettingTab('discounts')">
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
                    <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.1rem; display:flex; flex-direction:column; gap:12px;">
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
                            <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.1rem;">
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                                    <div>
                                        <div style="font-size:0.85rem; font-weight:800; color:#0f172a;">Logo Struk Transaksi</div>
                                        <div style="font-size:0.72rem; color:#64748b;">Logo toko akan dicetak di bagian paling atas struk thermal</div>
                                    </div>
                                    <div id="posLogoDeleteWrap" style="{{ empty($posLogoUrl) ? 'display:none;' : '' }}">
                                        <button type="button" onclick="handlePosLogoDelete()" style="background:#fee2e2; border:1px solid #fecaca; color:#dc2626; font-size:0.72rem; font-weight:700; padding:4px 9px; border-radius:6px; cursor:pointer;">
                                            Hapus Logo
                                        </button>
                                    </div>
                                </div>

                                <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                                    <div id="posLogoPreviewContainer" style="width: 130px; height: 65px; border: 2px dashed #cbd5e1; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: #ffffff; overflow: hidden; padding: 4px; flex-shrink:0;">
                                        @if(!empty($posLogoUrl))
                                            <img id="posLogoPreviewImg" src="{{ $posLogoUrl }}" alt="Logo POS" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        @else
                                            <span id="posLogoPreviewEmpty" style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-align:center;">Belum ada logo</span>
                                        @endif
                                    </div>

                                    <div style="flex:1; min-width: 180px;">
                                        <form id="formUploadPosLogo" onsubmit="handlePosLogoUpload(event)">
                                            <input type="file" id="posLogoFileInput" accept="image/*" style="display:none;" onchange="previewPosLogoSelected(this)">
                                            <div style="display:flex; gap:8px; align-items:center;">
                                                <button type="button" class="btn-outline-bio" onclick="document.getElementById('posLogoFileInput').click()" style="padding:0.45rem 0.9rem; font-size:0.75rem; border-radius:8px;">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                                    Pilih Logo Baru
                                                </button>
                                                <button type="submit" class="btn-submit-green" id="btnSavePosLogo" style="width:auto; padding:0.45rem 1rem; font-size:0.75rem; border-radius:8px; display:none;">
                                                    Upload &amp; Pasang
                                                </button>
                                            </div>
                                        </form>
                                        <div style="font-size:0.69rem; color:#94a3b8; margin-top:6px; line-height:1.35;">
                                            Format JPG, PNG, WEBP atau SVG (Maks. 3MB). Saran: format horizontal / transparan.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Toko di Struk -->
                            <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.1rem;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a; margin-bottom:0.25rem;">Identitas Toko di Cetakan Struk</div>
                                <div style="font-size:0.72rem; color:#64748b; margin-bottom:1rem;">Informasi nama, alamat dan kontak yang tercetak di kertas thermal struk</div>

                                <div style="display:flex; flex-direction:column; gap:12px;">
                                    <div>
                                        <label style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:4px;">Nama Toko Struk</label>
                                        <input type="text" id="setPosStoreName" class="form-input" style="height:38px; font-size:0.82rem;" value="{{ $storeName }}" placeholder="Contoh: Toko Kopi Senja" oninput="liveUpdateReceiptPreview()">
                                    </div>

                                    <div>
                                        <label style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:4px;">Alamat Toko</label>
                                        <textarea id="setPosStoreAddress" class="form-input" rows="2" style="font-size:0.82rem; padding:8px 10px; resize:none;" placeholder="Contoh: Jl. Sudirman No. 12, Jakarta Pusat" oninput="liveUpdateReceiptPreview()">{{ $posStoreAddress ?: ($profile->address ?? '') }}</textarea>
                                    </div>

                                    <div>
                                        <label style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:4px;">No. Telepon / WhatsApp Toko</label>
                                        <input type="text" id="setPosStorePhone" class="form-input" style="height:38px; font-size:0.82rem;" value="{{ $posStorePhone ?: ($profile->phone ?? '') }}" placeholder="Contoh: 081234567890" oninput="liveUpdateReceiptPreview()">
                                    </div>
                                </div>
                            </div>

                            <!-- Mini Live Preview Box -->
                            <div style="border:1.5px dashed #cbd5e1; border-radius:12px; padding:12px 16px; background:#ffffff; text-align:center;">
                                <div style="font-size:0.68rem; font-weight:800; text-transform:uppercase; letter-spacing:1px; color:#64748b; margin-bottom:8px;">
                                    — Live Preview Header Struk —
                                </div>
                                <div id="prevReceiptHeader" style="font-family:'Montserrat',sans-serif; color:#000; line-height:1.35; max-width:280px; margin:0 auto;">
                                    <div id="prevHeaderLogo" style="margin-bottom:4px;">
                                        @if(!empty($posLogoUrl))
                                            <img src="{{ $posLogoUrl }}" alt="Logo" style="max-height:36px; max-width:120px; object-fit:contain; margin:0 auto; display:block;">
                                        @endif
                                    </div>
                                    <div id="prevHeaderName" style="font-weight:800; font-size:13.5px;">{{ $storeName }}</div>
                                    <div id="prevHeaderAddress" style="font-size:10px; color:#333; margin-top:2px;">{{ $posStoreAddress ?: ($profile->address ?? 'Alamat toko belum diatur') }}</div>
                                    <div id="prevHeaderPhone" style="font-size:10px; color:#333; margin-top:1px;">{{ $posStorePhone ? ('No. Telp ' . $posStorePhone) : 'No. Telp: -' }}</div>
                                </div>
                            </div>

                        </div>

                        <!-- Footer Tab 1: Simpan Pengaturan -->
                        <div style="margin-top:1.25rem; display:flex; justify-content:flex-end; gap:8px;">
                            <button type="button" class="btn-submit-green" id="btnSaveStoreSettings" onclick="savePosStoreSettings()" style="width:auto; padding:0.6rem 1.6rem; font-size:0.82rem; border-radius:999px;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- TAB 2: PAJAK & BIAYA TAMBAHAN                       -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <div id="paneSettingFees" style="display: none;">
                        <!-- Guidance Alert Box -->
                        <div style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#166534" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#166534; line-height:1.45;">
                                <strong>Pajak &amp; Biaya Tambahan Fleksibel:</strong> Anda dapat menambahkan beberapa jenis biaya atau pajak (misal <strong>PPN 11%</strong>, <strong>Service Charge Resto 5%</strong>, <strong>PB1</strong>, atau <strong>Biaya Kemasan/Takeaway Rp 2.000</strong>). Biaya yang berstatus aktif akan otomatis dihitung dan dirinci di struk thermal kasir.
                            </div>
                        </div>

                        <!-- Top Action Bar -->
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:8px;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Daftar Biaya &amp; Pajak POS</div>
                                <div style="font-size:0.72rem; color:#64748b;">Atur persentase (%) atau nominal rupiah (Rp) untuk tiap komponen</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="addFeeRow()" style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                + Tambah Biaya / Pajak
                            </button>
                        </div>

                        <!-- Container Daftar Biaya -->
                        <div id="feeRowsListContainer" style="display:flex; flex-direction:column; gap:10px;">
                            <!-- Rendered dynamically by renderFeeSettingsList() -->
                        </div>

                        <!-- Live Simulation Box -->
                        <div style="margin-top:1.25rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px; padding:12px 14px;">
                            <div style="font-size:0.74rem; font-weight:700; color:#475569; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Simulasi Perhitungan Transaksi (Contoh Subtotal Rp 100.000):
                            </div>
                            <div id="feeLiveSimulationText" style="font-size:0.78rem; color:#0f172a; font-weight:600; line-height:1.5;">
                                Memuat simulasi...
                            </div>
                        </div>

                        <!-- Footer Tab 2: Simpan Pengaturan Biaya -->
                        <div style="margin-top:1.25rem; display:flex; justify-content:flex-end; gap:8px;">
                            <button type="button" class="btn-submit-green" id="btnSaveFeesSettings" onclick="savePosFeesSettings()" style="width:auto; padding:0.6rem 1.6rem; font-size:0.82rem; border-radius:999px;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Simpan Pengaturan Biaya &amp; Pajak
                            </button>
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- TAB 3: DISKON & PROMO PRODUK                        -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <div id="paneSettingDiscounts" style="display: none;">
                        <!-- Guidance Alert Box -->
                        <div style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#1e40af; line-height:1.45;">
                                <strong>Promo Fleksibel Berbasis Produk:</strong> Anda dapat membuat promo potongan harga (%) atau nominal (Rp). Diskon dapat diterapkan untuk <strong>Semua Produk</strong> atau <strong>Produk Tertentu (bisa pilih lebih dari 1 produk)</strong>. Saat transaksi di kasir POS, sistem akan otomatis menghitung potongan hanya untuk produk yang valid!
                            </div>
                        </div>

                        <!-- Top Action Bar -->
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:8px;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Katalog Diskon &amp; Promo POS</div>
                                <div style="font-size:0.72rem; color:#64748b;">Kelola promo aktif, potongan harga, dan target produk</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="openAddDiscountDrawer()" style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                + Buat Diskon Baru
                            </button>
                        </div>

                        <!-- Collapsible Drawer / Form Buat & Edit Diskon -->
                        <div id="discountDrawerForm" style="display:none; background:#f8fafc; border:1.5px solid #cbd5e1; border-radius:14px; padding:1.1rem; margin-bottom:1.25rem;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.85rem; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a;" id="discountDrawerTitle">
                                    Tambah Diskon / Promo Baru
                                </div>
                                <button type="button" onclick="closeDiscountDrawer()" style="background:none; border:none; font-size:1.1rem; color:#64748b; cursor:pointer; line-height:1;">&times;</button>
                            </div>

                            <form id="formDiscountData" onsubmit="handleSaveDiscount(event)">
                                <input type="hidden" id="discountEditId" value="">

                                <div style="display:grid; grid-template-columns:1fr; gap:10px; margin-bottom:10px;">
                                    <div>
                                        <label style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Nama Promo / Diskon *</label>
                                        <input type="text" id="discountInputName" class="form-input" style="height:36px; font-size:0.8rem;" placeholder="Contoh: Diskon Kopi 15% / Promo Gajian Rp 10.000" required>
                                    </div>
                                </div>

                                <div class="cashier-form-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
                                    <div>
                                        <label style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Tipe Diskon *</label>
                                        <select id="discountInputType" class="form-input" style="height:36px; font-size:0.8rem;" onchange="onDiscountTypeChanged()">
                                            <option value="percent">Persentase (%)</option>
                                            <option value="nominal">Nominal Rupiah (Rp)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Besaran Potongan *</label>
                                        <div style="position:relative;">
                                            <input type="number" id="discountInputValue" class="form-input" style="height:36px; font-size:0.8rem; padding-right:32px;" placeholder="Contoh: 15" min="0" step="any" required>
                                            <span id="discountValueSuffix" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); font-size:0.75rem; font-weight:700; color:#64748b;">%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Scope Pilihan Produk -->
                                <div style="margin-bottom:12px; background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px;">
                                    <label style="display:block; font-size:0.73rem; font-weight:800; color:#1e293b; margin-bottom:6px;">Berlaku Untuk Produk:</label>
                                    <div style="display:flex; gap:16px; flex-wrap:wrap;">
                                        <label style="display:flex; align-items:center; gap:6px; font-size:0.78rem; font-weight:600; color:#334155; cursor:pointer;">
                                            <input type="radio" name="discountScopeRadio" value="all" checked onchange="toggleDiscountScopeUI('all')" style="accent-color:#1eb349; cursor:pointer;">
                                            <span>Semua Produk (Global)</span>
                                        </label>
                                        <label style="display:flex; align-items:center; gap:6px; font-size:0.78rem; font-weight:600; color:#334155; cursor:pointer;">
                                            <input type="radio" name="discountScopeRadio" value="specific" onchange="toggleDiscountScopeUI('specific')" style="accent-color:#1eb349; cursor:pointer;">
                                            <span>Pilih Produk Tertentu (Multi-select)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Multi-product Picker Checklist (Visible only when scope === 'specific') -->
                                <div id="discountProductPickerWrap" style="display:none; margin-bottom:12px; background:#ffffff; border:1.5px solid #cbd5e1; border-radius:10px; padding:10px;">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; gap:8px; flex-wrap:wrap;">
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <span style="font-size:0.75rem; font-weight:700; color:#0f172a;">Pilih Produk:</span>
                                            <span id="discountSelectedCountBadge" style="font-size:0.68rem; background:#dcfce7; color:#15803d; font-weight:700; padding:1px 7px; border-radius:999px;">0 produk dipilih</span>
                                        </div>
                                        <div style="display:flex; gap:6px;">
                                            <button type="button" onclick="selectAllDiscountProducts()" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-size:0.68rem; font-weight:700; padding:3px 8px; border-radius:6px; cursor:pointer;">
                                                Pilih Semua
                                            </button>
                                            <button type="button" onclick="deselectAllDiscountProducts()" style="background:#fee2e2; border:1px solid #fecaca; color:#dc2626; font-size:0.68rem; font-weight:700; padding:3px 8px; border-radius:6px; cursor:pointer;">
                                                Hapus Pilihan
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Search input within picker -->
                                    <div style="margin-bottom:8px;">
                                        <input type="text" id="discountProductPickerSearch" class="form-input" style="height:32px; font-size:0.74rem;" placeholder="Cari nama produk..." oninput="filterDiscountProductPicker()">
                                    </div>

                                    <!-- Scrollable Checklist -->
                                    <div id="discountProductPickerList" style="max-height:220px; overflow-y:auto; display:flex; flex-direction:column; gap:4px; padding-right:4px;">
                                        <!-- Rendered dynamically from window.posAllProducts -->
                                    </div>
                                </div>

                                <!-- Status Toggle -->
                                <div style="margin-bottom:12px;">
                                    <label style="display:flex; align-items:center; gap:8px; font-size:0.8rem; font-weight:600; color:#1e293b; cursor:pointer;">
                                        <input type="checkbox" id="discountInputActive" checked style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer;">
                                        <span>Promo Aktif &amp; Siap Digunakan di Kasir</span>
                                    </label>
                                </div>

                                <div style="display:flex; justify-content:flex-end; gap:8px; border-top:1px solid #e2e8f0; padding-top:10px;">
                                    <button type="button" class="btn-outline-bio" onclick="closeDiscountDrawer()" style="padding:0.45rem 1rem; font-size:0.76rem; border-radius:8px;">Batal</button>
                                    <button type="submit" class="btn-submit-green" id="btnSaveDiscountSubmit" style="width:auto; padding:0.45rem 1.25rem; font-size:0.76rem; border-radius:8px;">
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
                        <div style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#1e40af; line-height:1.45;">
                                <strong>Akses Terbatas Staf Kasir:</strong> Akun kasir hanya dapat membuka halaman <strong>Kasir POS</strong>. Seluruh menu lain (Overview, Produk, Saldo, Laporan) otomatis terkunci demi keamanan data Anda. Setiap transaksi dan closing shift akan tercatat atas nama staf yang sedang bertugas.
                            </div>
                        </div>

                        <!-- Top Action Bar -->
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Daftar Akun Kasir</div>
                                <div style="font-size:0.72rem; color:#64748b;">Kelola akun login (email), jam kerja / shift, dan status aktif staf</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="openAddCashierDrawer()" style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                + Tambah Staf Kasir
                            </button>
                        </div>

                        <!-- Collapsible Drawer / Form Tambah & Edit Staf -->
                        <div id="cashierDrawerForm" style="display:none; background:#f8fafc; border:1.5px solid #cbd5e1; border-radius:14px; padding:1.1rem; margin-bottom:1.25rem;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.85rem; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a;" id="cashierDrawerTitle">
                                    Tambah Staf Kasir Baru
                                </div>
                                <button type="button" onclick="closeCashierDrawer()" style="background:none; border:none; font-size:1.1rem; color:#64748b; cursor:pointer; line-height:1;">&times;</button>
                            </div>

                            <form id="formCashierData" onsubmit="handleSaveCashier(event)">
                                <input type="hidden" id="cashierEditId" value="">
                                
                                <div class="cashier-form-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                                    <div>
                                        <label style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Nama Lengkap Staf *</label>
                                        <input type="text" id="cashierInputName" class="form-input" style="height:36px; font-size:0.8rem;" placeholder="Contoh: Siti Rahmawati" required>
                                    </div>
                                    <div>
                                        <label style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Jabatan / Role Title</label>
                                        <input type="text" id="cashierInputRole" class="form-input" style="height:36px; font-size:0.8rem;" placeholder="Contoh: Kasir Pagi / Kasir Utama" value="Kasir">
                                    </div>
                                </div>

                                <div class="cashier-form-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                                    <div>
                                        <label style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Email Login *</label>
                                        <input type="email" id="cashierInputEmail" class="form-input" style="height:36px; font-size:0.8rem;" placeholder="Contoh: kasir@tokosaya.com" required autocomplete="off">
                                        <div style="font-size:0.67rem; color:#94a3b8; margin-top:2px;">Email untuk login staf kasir ke sistem POS</div>
                                    </div>
                                    <div>
                                        <label style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Password Login <span id="cashierPasswordNotice" style="font-weight:normal; color:#94a3b8;">*</span></label>
                                        <input type="password" id="cashierInputPassword" class="form-input" style="height:36px; font-size:0.8rem;" placeholder="Minimal 6 karakter" autocomplete="new-password">
                                        <div style="font-size:0.67rem; color:#94a3b8; margin-top:2px;" id="cashierPassHelper">Wajib diisi untuk akun baru</div>
                                    </div>
                                </div>

                                <div class="cashier-form-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                                    <div>
                                        <label style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:4px;">Jam Kerja / Shift Kasir</label>
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <input type="time" id="cashierShiftStart" class="form-input" style="height:36px; font-size:0.82rem; padding:0 8px; flex:1;" value="08:00" onchange="syncCashierShiftTime()">
                                            <span style="font-size:0.73rem; font-weight:700; color:#64748b;">s/d</span>
                                            <input type="time" id="cashierShiftEnd" class="form-input" style="height:36px; font-size:0.82rem; padding:0 8px; flex:1;" value="16:00" onchange="syncCashierShiftTime()">
                                        </div>
                                        <input type="hidden" id="cashierInputShift" value="08:00 - 16:00">
                                        <div style="display:flex; gap:4px; flex-wrap:wrap; margin-top:6px;">
                                            <button type="button" onclick="setCashierShiftPreset('08:00', '16:00')" style="font-size:0.65rem; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:999px; padding:2px 7px; color:#475569; cursor:pointer; font-weight:600;">Pagi (08:00 - 16:00)</button>
                                            <button type="button" onclick="setCashierShiftPreset('12:00', '20:00')" style="font-size:0.65rem; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:999px; padding:2px 7px; color:#475569; cursor:pointer; font-weight:600;">Siang (12:00 - 20:00)</button>
                                            <button type="button" onclick="setCashierShiftPreset('16:00', '00:00')" style="font-size:0.65rem; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:999px; padding:2px 7px; color:#475569; cursor:pointer; font-weight:600;">Malam (16:00 - 00:00)</button>
                                        </div>
                                    </div>
                                    <div style="display:flex; flex-direction:column; justify-content:center;">
                                        <label style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:4px;">Status Akun</label>
                                        <label style="display:flex; align-items:center; gap:8px; font-size:0.8rem; font-weight:600; color:#1e293b; cursor:pointer; padding:8px 0;">
                                            <input type="checkbox" id="cashierInputActive" checked style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer;">
                                            <span>Aktif &amp; Boleh Login</span>
                                        </label>
                                    </div>
                                </div>

                                <div style="display:flex; justify-content:flex-end; gap:8px; border-top:1px solid #e2e8f0; padding-top:10px;">
                                    <button type="button" class="btn-outline-bio" onclick="closeCashierDrawer()" style="padding:0.45rem 1rem; font-size:0.76rem; border-radius:8px;">Batal</button>
                                    <button type="submit" class="btn-submit-green" id="btnSaveCashierSubmit" style="width:auto; padding:0.45rem 1.25rem; font-size:0.76rem; border-radius:8px;">
                                        Simpan Akun Staf
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Cashiers List Cards / Grid -->
                        <div id="cashierListWrap" style="display:flex; flex-direction:column; gap:8px;">
                            @forelse($cashiers as $c)
                                <div class="cashier-card-item" id="cashierRow-{{ $c->id }}" style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between; gap:12px; transition:all 0.2s ease;">
                                    <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                                        <div style="width:38px; height:38px; border-radius:50%; background:#f1f5f9; border:1.5px solid #cbd5e1; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:800; color:#334155; flex-shrink:0;">
                                            {{ strtoupper(substr($c->name, 0, 2)) }}
                                        </div>
                                        <div style="min-width:0;">
                                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                <span style="font-size:0.88rem; font-weight:800; color:#0f172a;">{{ $c->name }}</span>
                                                <span style="font-size:0.7rem; color:#0369a1; background:#f0f9ff; border:1px solid #bae6fd; padding:1px 6px; border-radius:4px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                                    {{ $c->email }}
                                                </span>
                                                @if($c->is_active)
                                                    <span class="badge-status-active" id="badgeActive-{{ $c->id }}" style="font-size:0.68rem; background:#dcfce7; color:#15803d; font-weight:700; padding:2px 7px; border-radius:999px;">Aktif</span>
                                                @else
                                                    <span class="badge-status-inactive" id="badgeActive-{{ $c->id }}" style="font-size:0.68rem; background:#f1f5f9; color:#64748b; font-weight:700; padding:2px 7px; border-radius:999px;">Nonaktif</span>
                                                @endif
                                            </div>
                                            <div style="display:flex; align-items:center; gap:12px; font-size:0.72rem; color:#64748b; margin-top:3px; flex-wrap:wrap;">
                                                <span style="display:inline-flex; align-items:center; gap:4px; color:#475569; font-weight:600;">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                    {{ $c->cashier_role_title ?: 'Kasir' }}
                                                </span>
                                                @if(!empty($c->shift_hours))
                                                    <span style="display:inline-flex; align-items:center; gap:4px; color:#0369a1; background:#f0f9ff; padding:1px 6px; border-radius:4px; font-weight:600;">
                                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                        {{ $c->shift_hours }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                        <button type="button" class="btn-outline-bio" style="padding:4px 9px; font-size:0.72rem; border-radius:6px;" onclick='editCashierData(@json($c))' title="Edit Kasir">
                                            Edit
                                        </button>
                                        <button type="button" style="padding:4px 9px; font-size:0.72rem; border-radius:6px; border:1px solid #e2e8f0; background:#f8fafc; color:#334155; cursor:pointer; font-weight:600;" onclick="toggleCashierActive({{ $c->id }})" title="Aktifkan / Nonaktifkan">
                                            {{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                        <button type="button" style="padding:4px 8px; font-size:0.72rem; border-radius:6px; border:1px solid #fee2e2; background:#fff1f2; color:#e11d48; cursor:pointer; font-weight:700;" onclick="deleteCashier({{ $c->id }}, '{{ addslashes($c->name) }}')" title="Hapus Staf">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div id="cashierEmptyNotice" style="text-align:center; padding:2rem 1rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px;">
                                    <div style="width:40px; height:40px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 8px auto; color:#94a3b8;">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    </div>
                                    <div style="font-size:0.85rem; font-weight:700; color:#334155;">Belum Ada Akun Staf Kasir</div>
                                    <div style="font-size:0.72rem; color:#64748b; margin-top:2px;">Klik tombol "+ Tambah Staf Kasir" di atas untuk menambahkan staf yang bertugas.</div>
                                </div>
                            @endforelse
                        </div>

                    </div>

                    <!-- TAB 5: KELOLA MEJA & BARCODE QR DINE-IN -->
                    <div id="paneSettingTables" style="display:none;">
                        <!-- Header & Info Card -->
                        <div style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.1rem; margin-bottom:1.2rem;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px; flex-wrap:wrap;">
                                <div>
                                    <div style="font-size:0.9rem; font-weight:800; color:#0f172a; display:flex; align-items:center; gap:6px;">
                                        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <path d="M3 9h18M9 21V9" />
                                        </svg>
                                        Kelola Meja &amp; Barcode QR Dine-In
                                    </div>
                                    <div style="font-size:0.75rem; color:#64748b; margin-top:3px; line-height:1.45;">
                                        Setiap meja memiliki Barcode QR khusus yang terhubung ke Link in Bio (Tema 1-4). Pelanggan tinggal scan untuk memesan, dan nomor meja langsung terisi otomatis saat checkout!
                                    </div>
                                </div>
                                <button type="button" class="btn-primary-bio" onclick="toggleAddTableForm()"
                                    style="padding:0.4rem 0.95rem; font-size:0.75rem; border-radius:8px; display:inline-flex; align-items:center; gap:5px; flex-shrink:0;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    + Tambah Meja
                                </button>
                            </div>

                            <!-- Add Table Form (Collapsible) -->
                            <div id="formAddTableBox" style="display:none; margin-top:1rem; padding-top:1rem; border-top:1.5px dashed #cbd5e1;">
                                <div style="display:grid; grid-template-columns: 2fr 1fr 2fr auto; gap:8px; align-items:flex-end;">
                                    <div>
                                        <label style="font-size:0.7rem; font-weight:700; color:#475569; display:block; margin-bottom:3px;">Nama Meja / Nomor <span style="color:#ef4444;">*</span></label>
                                        <input type="text" id="newTableName" class="form-input" style="height:34px; font-size:0.78rem;" placeholder="Contoh: Meja 01, VIP 1">
                                    </div>
                                    <div>
                                        <label style="font-size:0.7rem; font-weight:700; color:#475569; display:block; margin-bottom:3px;">Kursi</label>
                                        <input type="number" id="newTableSeats" class="form-input" style="height:34px; font-size:0.78rem;" placeholder="4" min="1" value="4">
                                    </div>
                                    <div>
                                        <label style="font-size:0.7rem; font-weight:700; color:#475569; display:block; margin-bottom:3px;">Area / Catatan</label>
                                        <input type="text" id="newTableArea" class="form-input" style="height:34px; font-size:0.78rem;" placeholder="Contoh: Indoor Lt 1 / Outdoor">
                                    </div>
                                    <div style="display:flex; gap:6px;">
                                        <button type="button" class="btn-primary-bio" onclick="confirmAddTable()" style="height:34px; padding:0 12px; font-size:0.75rem; border-radius:6px; font-weight:700;">
                                            Simpan
                                        </button>
                                        <button type="button" onclick="toggleAddTableForm(false)" style="height:34px; padding:0 10px; font-size:0.75rem; border-radius:6px; border:1px solid #cbd5e1; background:#fff; color:#64748b; cursor:pointer;">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table List Container -->
                        <div id="tableRowsListContainer" style="display:flex; flex-direction:column; gap:10px;">
                            <!-- Rendered by renderTablesSettingsList() -->
                        </div>

                        <!-- Bottom Save Notice & Button -->
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.2rem; padding-top:1rem; border-top:1.5px solid #f1f5f9;">
                            <div style="font-size:0.72rem; color:#64748b;">
                                Perubahan meja otomatis tersinkronisasi dengan kasir POS dan Link in Bio.
                            </div>
                            <button type="button" class="btn-primary-bio" id="btnSaveTablesSettings" onclick="saveTablesSettings()"
                                style="padding:0.48rem 1.25rem; font-size:0.78rem; border-radius:8px; font-weight:800; display:inline-flex; align-items:center; gap:6px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                Simpan Perubahan Meja
                            </button>
                        </div>
                    </div>

                </div> <!-- end posSettingsRealContent -->

            </div>

            <!-- Footer: Penutup Modal -->
            <div class="pos-modal-foot" style="display:flex; justify-content:space-between; align-items:center; padding:0.8rem 1.4rem; background:#f8fafc; border-top:1.5px solid #f1f5f9;">
                <div style="font-size:0.7rem; color:#94a3b8;">
                    Kustomisasi tersimpan &amp; terhubung langsung ke modul POS.
                </div>
                <button type="button" class="btn-outline-bio" onclick="closeModal('posSettingsModal')" style="padding:0.45rem 1.2rem; font-size:0.78rem; border-radius:999px;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- CUSTOM OVERLAY MODAL 3: History & Shift Closing Modal -->
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

                        <div style="background:#fff7ed; border:1.5px solid #fed7aa; border-radius:14px; padding:12px 14px;">
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
                                <strong style="color:#0f172a;">{{ $currentCashier ? $currentCashier->name : auth()->user()->name }} @if(!empty($currentCashier?->shift_hours))<span style="font-size:0.75rem; color:#0369a1; font-weight:600;">({{ $currentCashier->shift_hours }})</span>@endif</strong>
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
                                                    • <span style="color:#0369a1; font-weight:700;">Kasir: {{ $tCashierName }}</span>
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
                                        <td colspan="6" style="text-align:center; padding:3rem 1rem; color:#94a3b8; font-size:0.85rem;">Belum ada transaksi POS hari ini.
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
                                            {{ $mut->amount >= 0 ? '+' : '' }}Rp {{ number_format($mut->amount, 0, ',', '.') }}
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
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($todayGrossSales, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Total Diskon:</td>
                    <td style="text-align: right;">-Rp {{ number_format($todayOrders->sum('discount'), 0, ',', '.') }}</td>
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
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($todayFeeDeducted, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Sisa Saldo Deposit Aktif:</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($posBalance, 0, ',', '.') }}</td>
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

        // Fees, Discounts & Tables Data (from POS Settings)
        let posFeesData = {!! json_encode($profile ? $profile->getPosFees() : []) !!};
        let posDiscountsData = {!! json_encode($profile ? $profile->getPosDiscounts() : []) !!};
        let posTablesData = {!! json_encode($profile ? $profile->getPosTables() : []) !!};
        const posBioBaseUrl = {!! json_encode(!empty($profile->custom_domain) ? ('https://' . rtrim($profile->custom_domain, '/')) : url('/' . ($profile->store_slug ?: ($seller->username ?? '')))) !!};
        const posStoreDisplayName = {!! json_encode($profile->store_name ?? $storeName ?? 'Toko') !!};

        // All products list for discount product-picker
        window.posAllProducts = [
            @foreach($products as $prod)
            {
                id: {{ $prod->id }},
                name: {!! json_encode($prod->name) !!},
            },
            @endforeach
        ];

        // Custom Modal Helpers (Vanilla JS - Independent from Bootstrap)
        function openModal(modalId) {
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
            document.querySelectorAll('.pos-cat-pill').forEach(p => p.classList.remove('active'));
            if (btn) btn.classList.add('active');
            filterProducts();
        }

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

                // Match text query
                const textMatch = !query || name.includes(query);
                if (textMatch) {
                    countAll++;
                    if (cat === 'physical') countPhysical++;
                    else if (cat === 'fnb') countFnb++;
                    else if (cat === 'digital') countDigital++;
                }

                const catMatch = (activePosCategory === 'all') || (cat === activePosCategory);
                const matches = textMatch && catMatch;

                card.style.display = matches ? '' : 'none';
                if (matches) visibleCount++;
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

        // Add product to cart
        function addToCart(id, name, price, image = '') {
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

            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.qty += 1;
                if (!existing.image && image) existing.image = image;
            } else {
                cart.push({ id, name, price, image, qty: 1 });
            }
            renderCart();
            showPosToast(`+ ${name} ditambahkan`, 'success');
        }

        // Update Item Qty
        function updateQty(id, delta) {
            const item = cart.find(i => i.id === id);
            if (!item) return;

            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
            renderCart();
        }

        // Remove Item
        function removeItem(id) {
            cart = cart.filter(i => i.id !== id);
            renderCart();
        }

        // Clear Cart
        function clearCart() {
            cart = [];
            renderCart();
        }

        // Render Cart HTML & Badges
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

                const badge = document.getElementById(`badge-qty-${item.id}`);
                if (badge) {
                    if (item.qty > 0) {
                        badge.innerText = `x${item.qty}`;
                        badge.classList.add('has-qty');
                    } else {
                        badge.innerText = '0';
                        badge.classList.remove('has-qty');
                    }
                }

                html += `
                                    <div class="cart-item-row">
                                        <div class="cart-item-thumb">
                                            ${item.image ? `
                                                <img src="${item.image}" alt="${escapeHtml(item.name)}" class="cart-item-img" onerror="this.parentElement.innerHTML='<div class=\\'cart-item-img-fallback\\'><svg width=\\'16\\' height=\\'16\\' fill=\\'none\\' stroke=\\'currentColor\\' stroke-width=\\'2\\' viewBox=\\'0 0 24 24\\'><rect x=\\'3\\' y=\\'3\\' width=\\'18\\' height=\\'18\\' rx=\\'2\\'/><circle cx=\\'8.5\\' cy=\\'8.5\\' r=\\'1.5\\'/><polyline points=\\'21 15 16 10 5 21\\'/></svg></div>';">
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
                                            <div class="cart-item-unit-price">Rp ${formatRupiah(itemSub)}</div>
                                        </div>
                                        <div class="cart-qty-ctrl">
                                            <button type="button" class="btn-qty" onclick="updateQty(${item.id}, -1)">-</button>
                                            <span class="qty-num">${item.qty}</span>
                                            <button type="button" class="btn-qty btn-qty-plus" onclick="updateQty(${item.id}, 1)">+</button>
                                            <button type="button" class="btn text-danger ms-1 p-0" onclick="removeItem(${item.id})" style="border:none; background:none;">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                `;
            });

            container.innerHTML = html;
            calculateTotals();
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

            const grandTotal = Math.max(0, subtotal - discountAmount + customFeesTotal + systemFees);

            document.getElementById('displaySubtotal').innerText = 'Rp ' + formatRupiah(subtotal);
            const dispDisc = document.getElementById('displayDiscountAmount');
            if (dispDisc) {
                dispDisc.innerText = discountAmount > 0 ? ('- Rp ' + formatRupiah(discountAmount)) : '- Rp 0';
            }
            document.getElementById('displaySystemFees').innerText = 'Rp ' + formatRupiah(systemFees);
            document.getElementById('displayGrandTotal').innerText = 'Rp ' + formatRupiah(grandTotal);

            renderCartFees();

            const totalItemsCount = cart.reduce((sum, item) => sum + item.qty, 0);
            document.getElementById('mobileCartCount').innerText = `${totalItemsCount} Items`;
            document.getElementById('mobileCartTotal').innerText = 'Rp ' + formatRupiah(grandTotal);
        }

        function syncCustomerEmail(val) {
            const target = document.getElementById('posCustomerEmail');
            if (target) target.value = val;
        }

        // Open Payment Modal
        function openPaymentModal() {
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

            const isDepositInsufficient = (currentPosBalance <= 0 || currentPosBalance < totalFeeRequired);

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
                        cashBadge.innerText = currentPosBalance <= 0 ? 'Saldo Deposit Kurang (Rp 0)' : 'Saldo Deposit Kurang';
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
            return Math.max(0, subtotal - discountAmount + customFeesTotal + systemFees);
        }

        // Select Payment Method (cash | cashless)
        function selectPaymentMethod(method) {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const totalFeeRequired = (subtotal * (platformFeeRate + adminFeeRate)) / 100;

            if (method === 'cash' && (currentPosBalance <= 0 || currentPosBalance < totalFeeRequired)) {
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
                items: cart.map(i => ({ id: i.id, qty: i.qty })),
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

                        if (payMethodRaw === 'cashless') {
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

            // Cashier Name
            const elCashierName = document.getElementById('recCashierName');
            if (elCashierName) {
                elCashierName.innerText = order.cashier_name || (typeof currentCashierStaffName !== 'undefined' && currentCashierStaffName ? currentCashierStaffName : '{{ $currentCashier ? $currentCashier->name : auth()->user()->name }}');
            }

            // Customer Name & Notes (Legacy fallback)
            const elCustomerName = document.getElementById('recCustomerName');
            if (elCustomerName) elCustomerName.innerText = addr.name || 'Pelanggan Umum';

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
                            </div>
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
        let storeAddressText = {!! json_encode($posStoreAddress ?: ($profile->address ?? '')) !!};
        let storePhoneText = {!! json_encode($posStorePhone ?: ($profile->phone ?? '')) !!};

        // Buka Pop-up Pengaturan POS (Dengan efek Shimmer Skeleton Premium)
        function openPosSettingsModal(initialTab = 'struk') {
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
        function switchSettingTab(tabId) {
            const tabs = ['struk', 'fees', 'discounts', 'staff', 'tables'];
            const btnMap = {
                struk: 'tabBtnSettingStruk',
                fees: 'tabBtnSettingFees',
                discounts: 'tabBtnSettingDiscounts',
                staff: 'tabBtnSettingStaff',
                tables: 'tabBtnSettingTables',
            };
            const paneMap = {
                struk: 'paneSettingStruk',
                fees: 'paneSettingFees',
                discounts: 'paneSettingDiscounts',
                staff: 'paneSettingStaff',
                tables: 'paneSettingTables',
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
                        nameEl.onblur = function() {
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
                reader.onload = function(e) {
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
                    <div style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:12px 16px; display:flex; align-items:center; justify-content:space-between; gap:12px; transition:all 0.2s ease;">
                        <div style="display:flex; align-items:center; gap:14px; min-width:0; flex:1;">
                            <div id="tableQrThumb_${idx}" style="width:48px; height:48px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden; padding:2px;"></div>
                            <div style="min-width:0;">
                                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                    <span style="font-size:0.92rem; font-weight:800; color:#0f172a;">${escapeHtml(table.name)}</span>
                                    ${table.seats ? `<span style="font-size:0.68rem; font-weight:700; background:#f1f5f9; color:#475569; padding:2px 7px; border-radius:4px;">${table.seats} Kursi</span>` : ''}
                                    ${table.area ? `<span style="font-size:0.68rem; font-weight:700; background:#f0fdf4; color:#166534; padding:2px 7px; border-radius:4px; border:1px solid #bbf7d0;">${escapeHtml(table.area)}</span>` : ''}
                                </div>
                                <div style="font-size:0.7rem; color:#94a3b8; margin-top:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:400px;">
                                    ${escapeHtml(tableUrl)}
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                            <button type="button" class="btn-primary-bio" onclick="downloadTableQr(${idx})"
                                style="padding:6px 12px; font-size:0.74rem; border-radius:7px; font-weight:700; display:inline-flex; align-items:center; gap:5px;"
                                title="Download Barcode QR Meja ini">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Download Barcode
                            </button>
                            <a href="${tableUrl}" target="_blank"
                                style="padding:6px 10px; font-size:0.74rem; border-radius:7px; border:1px solid #cbd5e1; background:#fff; color:#475569; text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-weight:600;"
                                title="Buka Link di Tab Baru">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                Tes Link
                            </a>
                            <button type="button" onclick="deleteTable(${idx})"
                                style="padding:6px 8px; font-size:0.74rem; border-radius:7px; border:1px solid #fee2e2; background:#fff1f2; color:#e11d48; cursor:pointer;"
                                title="Hapus Meja">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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
                const skel = document.getElementById('pos-skeleton');
                const real = document.getElementById('pos-real-content');
                if (skel) skel.style.display = 'none';
                if (real) real.style.opacity = '1';

                // Initial render for fees, discounts, and tables
                try {
                    if (typeof renderCartFees === 'function') renderCartFees();
                    if (typeof renderDiscountPresetDropdown === 'function') renderDiscountPresetDropdown();
                    if (typeof renderTablesQuickSelect === 'function') renderTablesQuickSelect();
                    if (typeof updateTablesBadgeCount === 'function') updateTablesBadgeCount();
                } catch(e) {}
            }
            if (document.readyState === 'complete') {
                setTimeout(revealPos, 120);
            } else {
                window.addEventListener('load', () => setTimeout(revealPos, 120));
            }
        })();

    </script>
@endsection