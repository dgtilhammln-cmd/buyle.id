@extends('creator.layout')

@section('title', 'Laporan Kasir POS – Creator Studio')

@section('styles')
    <style>
        :root {
            --pg: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        }

        body {
            background: #f8fafc !important;
        }

        :root {
            --pg: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            --pg-dark: linear-gradient(135deg, #15803d 0%, #166534 100%);
        }

        /* ── Reset Global Layout Wrapper (Full Centered, Anti-Mepet, No Overflow) ── */
        body {
            background: #f8fafc !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .cr-main-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
            background: #f8fafc !important;
        }

        .cr-main-canvas {
            margin: 0 auto !important;
            padding: 0.5rem 0.75rem 120px 0.75rem !important;
            padding-top: max(0.5rem, calc(0.5rem + env(safe-area-inset-top, 0px))) !important;
            width: 100% !important;
            max-width: 480px !important;
            /* Super App width, perfectly centered */
            box-sizing: border-box !important;
            background: transparent !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        .cr-topbar-header-row {
            display: none !important;
        }

        .rp-wrap {
            max-width: 520px;
            margin: 0 auto;
            padding: 0.65rem 0.75rem 6rem;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }

        /* ── Header ── */
        .rp-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-top: 0 !important;
            gap: 0.5rem;
        }

        .rp-hdr-left {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .rp-hdr-left {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            min-width: 0;
        }

        .rp-back {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            text-decoration: none;
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            text-decoration: none;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
            transition: transform .15s;
        }

        .rp-hdr-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.2;
        }

        .rp-hdr-sub {
            font-size: 0.67rem;
            color: #64748b;
            margin: 2px 0 0;
        }

        .rp-export {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.38rem 0.75rem;
            border-radius: 999px;
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #166534;
            font-size: 0.72rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .02);
        }

        .rp-back:hover {
                transform: scale(1.05);
            }

            .rp-hdr-title {
                font-size: 1.05rem;
                font-weight: 700;
                color: #0f172a;
                margin: 0;
                line-height: 1.2;
            }

            .rp-hdr-sub {
                font-size: 0.68rem;
                color: #64748b;
                margin: 2px 0 0;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .rp-export-btn {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 0.42rem 0.85rem;
                border-radius: 999px;
                background: #ffffff;
                border: 1.5px solid #bbf7d0;
                color: #166534;
                font-size: 0.72rem;
                font-weight: 700;
                text-decoration: none;
                box-shadow: 0 1px 4px rgba(30, 179, 73, .12);
                cursor: pointer;
                flex-shrink: 0;
                transition: all .15s;
            }

            .rp-export-btn:hover {
                background: #f0fdf4;
                border-color: #86efac;
                transform: translateY(-1px);
            }

            /* ── Period Filter: 3-col grid ── */
            /* ── Period Filter 3×2 Grid ── */
            .rp-period-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 0.35rem;
                margin-bottom: 0.75rem;
                gap: 0.4rem;
                margin-bottom: 0.85rem;
            }

            .rp-period-pill {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0.4rem 0.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0.42rem 0.2rem;
                border-radius: 10px;
                background: #fff;
                border: 1.5px solid #e2e8f0;
                color: #475569;
                font-size: 0.72rem;
                font-weight: 600;
                text-decoration: none;
                text-align: center;
                background: #fff;
                border: 1.5px solid #e2e8f0;
                color: #475569;
                font-size: 0.72rem;
                font-weight: 600;
                text-decoration: none;
                text-align: center;
                transition: all .15s;
            }

            .rp-period-pill.active {
                background: var(--pg);
                border-color: transparent;
                color: #fff;
                box-shadow: 0 3px 10px rgba(30, 179, 73, .28);
                background: var(--pg);
                border-color: transparent;
                color: #fff;
                box-shadow: 0 3px 10px rgba(30, 179, 73, .28);
            }

            /* ── KPI Cards 2-col ── */
            .rp-kpi-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 0.55rem;
                margin-bottom: 0.75rem;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 0.55rem;
                margin-bottom: 0.85rem;
            }

            .rp-kpi {
                background: #fff;
                border-radius: 16px;
                border: 1px solid #e2e8f0;
                padding: 0.75rem 0.85rem;
                background: #fff;
                border-radius: 16px;
                border: 1px solid #e2e8f0;
                padding: 0.75rem 0.85rem;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .02);
                box-sizing: border-box;
            }

            .rp-kpi.dark {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                border-color: #334155;
                box-shadow: 0 4px 14px rgba(15, 23, 42, .15);
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                border-color: #334155;
                box-shadow: 0 4px 14px rgba(15, 23, 42, .15);
            }

            .rp-kpi-label {
                font-size: 0.67rem;
                font-weight: 600;
                color: #64748b;
                display: flex;
                align-items: center;
                gap: 4px;
                margin-bottom: 0.3rem;
                font-size: 0.67rem;
                font-weight: 600;
                color: #64748b;
                display: flex;
                align-items: center;
                gap: 5px;
                margin-bottom: 0.35rem;
            }

            .rp-kpi.dark .rp-kpi-label {
                color: #94a3b8;
            }

            .rp-kpi-val {
                font-size: 1.05rem;
                font-weight: 800;
                color: #0f172a;
                letter-spacing: -.02em;
            }

            .rp-kpi-val {
                font-size: 1.05rem;
                font-weight: 800;
                color: #0f172a;
                letter-spacing: -.02em;
                line-height: 1.2;
            }

            .rp-kpi.dark .rp-kpi-val {
                color: #fff;
            }

            .rp-kpi-sub {
                font-size: 0.64rem;
                color: #94a3b8;
                margin-top: 2px;
            }

            .rp-kpi-sub {
                font-size: 0.64rem;
                color: #94a3b8;
                margin-top: 3px;
            }

            /* ── Method Filter Row (small pills, single row) ── */
            /* ── Method Filter Row (small pills) ── */
            .rp-method-row {
                display: flex;
                align-items: center;
                gap: 0.3rem;
                overflow-x: auto;
                padding-bottom: 0.4rem;
                margin-bottom: 0.75rem;
                scrollbar-width: none;
                display: flex;
                align-items: center;
                gap: 0.35rem;
                overflow-x: auto;
                padding-bottom: 0.35rem;
                margin-bottom: 0.85rem;
                scrollbar-width: none;
            }

            .rp-method-row::-webkit-scrollbar {
                display: none;
            }

            .rp-method-pill {
                padding: 0.3rem 0.65rem;
                border-radius: 999px;
                font-size: 0.68rem;
                font-weight: 600;
                background: #fff;
                border: 1px solid #e2e8f0;
                color: #64748b;
                white-space: nowrap;
                text-decoration: none;
                padding: 0.32rem 0.7rem;
                border-radius: 999px;
                font-size: 0.68rem;
                font-weight: 600;
                background: #fff;
                border: 1px solid #e2e8f0;
                color: #64748b;
                white-space: nowrap;
                text-decoration: none;
                transition: all .15s;
            }

            .rp-method-pill.active {
                background: #0f172a;
                color: #fff;
                border-color: #0f172a;
            }

            .rp-method-pill.active {
                background: #0f172a;
                color: #fff;
                border-color: #0f172a;
            }

            /* ── Section Tabs ── */
            .rp-sec-tabs {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                background: #f1f5f9;
                border-radius: 12px;
                padding: 3px;
                gap: 3px;
                margin-bottom: 0.75rem;
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                background: #f1f5f9;
                border-radius: 12px;
                padding: 3px;
                gap: 3px;
                margin-bottom: 0.85rem;
            }

            .rp-sec-btn {
                text-align: center;
                padding: 0.4rem 0.15rem;
                border-radius: 9px;
                font-size: 0.7rem;
                font-weight: 600;
                color: #64748b;
                border: none;
                background: transparent;
                cursor: pointer;
                transition: all .15s;
                text-align: center;
                padding: 0.42rem 0.2rem;
                border-radius: 9px;
                font-size: 0.7rem;
                font-weight: 600;
                color: #64748b;
                border: none;
                background: transparent;
                cursor: pointer;
                transition: all .15s;
            }

            .rp-sec-btn.active {
                background: #fff;
                color: #0f172a;
                font-weight: 700;
                background: #fff;
                color: #0f172a;
                font-weight: 700;
                box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
            }

            /* ── Search ── */
            .rp-search {
                position: relative;
                margin-bottom: 0.65rem;
                position: relative;
                margin-bottom: 0.65rem;
            }

            .rp-search input {
                width: 100%;
                box-sizing: border-box;
                background: #fff;
                border: 1.5px solid #e2e8f0;
                border-radius: 999px;
                padding: 0.5rem 0.9rem 0.5rem 2.1rem;
                font-size: 0.78rem;
                color: #0f172a;
                outline: none;
                width: 100%;
                box-sizing: border-box;
                background: #fff;
                border: 1.5px solid #e2e8f0;
                border-radius: 999px;
                padding: 0.52rem 0.9rem 0.52rem 2.2rem;
                font-size: 0.78rem;
                color: #0f172a;
                outline: none;
                transition: border-color .15s;
            }

            .rp-search input:focus {
                border-color: #1eb349;
            }

            .rp-search svg {
                position: absolute;
                left: 0.7rem;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
            }

            .rp-search input:focus {
                border-color: #1eb349;
            }

            .rp-search svg {
                position: absolute;
                left: 0.8rem;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
            }

            /* ── Order Card ── */
            .rp-order-card {
                background: #fff;
                border-radius: 14px;
                border: 1px solid #e2e8f0;
                padding: 0.7rem 0.85rem;
                background: #fff;
                border-radius: 14px;
                border: 1px solid #e2e8f0;
                padding: 0.75rem 0.9rem;
                margin-bottom: 0.55rem;
                box-shadow: 0 1px 4px rgba(0, 0, 0, .02);
                box-sizing: border-box;
            }

            .rp-order-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 0.25rem;
            }

            .rp-order-no {
                font-size: 0.78rem;
                font-weight: 700;
                color: #0f172a;
            }

            .rp-order-time {
                font-size: 0.65rem;
                color: #94a3b8;
            }

            .rp-order-mid {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 0.3rem;
            }

            .rp-order-customer {
                font-size: 0.75rem;
                color: #334155;
                font-weight: 500;
            }

            .rp-order-amt {
                font-size: 0.92rem;
                font-weight: 800;
                color: #166534;
            }

            .rp-order-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 0.3rem;
            }

            .rp-order-no {
                font-size: 0.78rem;
                font-weight: 700;
                color: #0f172a;
                font-family: monospace;
            }

            .rp-order-time {
                font-size: 0.65rem;
                color: #94a3b8;
            }

            .rp-order-mid {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 0.35rem;
            }

            .rp-order-customer {
                font-size: 0.75rem;
                color: #334155;
                font-weight: 600;
            }

            .rp-order-amt {
                font-size: 0.92rem;
                font-weight: 800;
                color: #166534;
            }

            .rp-order-foot {
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-top: 1px dashed #f1f5f9;
                padding-top: 0.35rem;
                margin-top: 0.25rem;
                font-size: 0.66rem;
                color: #64748b;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-top: 1px dashed #f1f5f9;
                padding-top: 0.4rem;
                margin-top: 0.25rem;
                font-size: 0.66rem;
                color: #64748b;
            }

            .badge-cash {
                background: #ecfdf5;
                color: #059669;
                padding: 2px 8px;
                border-radius: 999px;
                font-weight: 600;
                font-size: 0.65rem;
                border: 1px solid #a7f3d0;
            }

            .badge-qris {
                background: #eff6ff;
                color: #2563eb;
                padding: 2px 8px;
                border-radius: 999px;
                font-weight: 600;
                font-size: 0.65rem;
                border: 1px solid #bfdbfe;
            }

            .badge-tf {
                background: #f5f3ff;
                color: #7c3aed;
                padding: 2px 8px;
                border-radius: 999px;
                font-weight: 600;
                font-size: 0.65rem;
                border: 1px solid #ddd6fe;
            }

            .badge-cash {
                background: #ecfdf5;
                color: #059669;
                padding: 2px 8px;
                border-radius: 999px;
                font-weight: 700;
                font-size: 0.65rem;
                border: 1px solid #a7f3d0;
            }

            .badge-qris {
                background: #eff6ff;
                color: #2563eb;
                padding: 2px 8px;
                border-radius: 999px;
                font-weight: 700;
                font-size: 0.65rem;
                border: 1px solid #bfdbfe;
            }

            .badge-tf {
                background: #f5f3ff;
                color: #7c3aed;
                padding: 2px 8px;
                border-radius: 999px;
                font-weight: 700;
                font-size: 0.65rem;
                border: 1px solid #ddd6fe;
            }

            /* ── Top Menu Rank ── */
            .rp-rank {
                background: #fff;
                border-radius: 14px;
                border: 1px solid #e2e8f0;
                padding: 0.65rem 0.85rem;
                margin-bottom: 0.45rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #fff;
                border-radius: 14px;
                border: 1px solid #e2e8f0;
                padding: 0.7rem 0.9rem;
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-sizing: border-box;
            }

            .rp-rank-left {
                display: flex;
                align-items: center;
                gap: 0.55rem;
            }

            .rp-rank-left {
                display: flex;
                align-items: center;
                gap: 0.6rem;
            }

            .rp-rank-badge {
                width: 26px;
                height: 26px;
                border-radius: 7px;
                background: #f1f5f9;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.7rem;
                font-weight: 700;
                color: #334155;
                width: 28px;
                height: 28px;
                border-radius: 8px;
                background: #f1f5f9;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.72rem;
                font-weight: 800;
                color: #334155;
            }

            .rp-rank-badge.r1 {
                background: #fef3c7;
                color: #b45309;
            }

            .rp-rank-badge.r2 {
                background: #e2e8f0;
                color: #475569;
            }

            .rp-rank-badge.r3 {
                background: #ffedd5;
                color: #c2410c;
            }

            .rp-rank-name {
                font-size: 0.8rem;
                font-weight: 700;
                color: #0f172a;
            }

            .rp-rank-qty {
                font-size: 0.66rem;
                color: #64748b;
                margin-top: 1px;
            }

            .rp-rank-rev {
                font-size: 0.82rem;
                font-weight: 700;
                color: #0f172a;
            }

            /* ── Mutasi Card ── */
            .rp-mut-card {
                background: #fff;
                border-radius: 14px;
                border: 1px solid #e2e8f0;
                padding: 0.65rem 0.85rem;
                margin-bottom: 0.45rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.5rem;
                background: #fff;
                border-radius: 14px;
                border: 1px solid #e2e8f0;
                padding: 0.7rem 0.9rem;
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.5rem;
                box-sizing: border-box;
            }

            .rp-mut-type {
                font-size: 0.75rem;
                font-weight: 700;
            }

            .rp-mut-desc {
                font-size: 0.65rem;
                color: #64748b;
                margin-top: 1px;
            }

            .rp-mut-date {
                font-size: 0.63rem;
                color: #94a3b8;
                margin-top: 2px;
            }

            .rp-mut-amt {
                font-size: 0.88rem;
                font-weight: 800;
                flex-shrink: 0;
            }

            /* ── Empty state ── */
            .rp-empty {
                text-align: center;
                padding: 2rem 1rem;
                background: #fff;
                border-radius: 14px;
                border: 1px dashed #cbd5e1;
                text-align: center;
                padding: 2.2rem 1rem;
                background: #fff;
                border-radius: 14px;
                border: 1px dashed #cbd5e1;
            }

            .rp-empty p {
                margin: 0.35rem 0 0;
                font-size: 0.75rem;
                color: #64748b;
            }

            .rp-empty p {
                margin: 0.4rem 0 0;
                font-size: 0.78rem;
                font-weight: 600;
                color: #64748b;
            }

            .rp-empty small {
                font-size: 0.67rem;
                color: #94a3b8;
            }

            /* ── POS Bottom Nav ── */
            /* ── Bottom Nav ── */
    /* POS Standard Bottom Navigation Bar (100% Identical Across All Screens) */
    .pos-bottom-nav-wrap {
        display: flex !important;
        position: fixed !important;
        bottom: 16px !important;
        left: 14px !important;
        right: 14px !important;
        max-width: 440px !important;
        margin: 0 auto !important;
        z-index: 10000 !important;
        justify-content: center !important;
        pointer-events: none !important;
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
    .pos-nav-label {
        display: none;
    }
    .pos-nav-item.active .pos-nav-label {
        display: inline-block;
        font-size: 0.8rem;
        font-weight: 700;
        color: #ffffff !important;
        letter-spacing: -0.01em;
    }

    /* ── Export Modal (Clean, Compact & Interactive) ── */
            .rp-modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(4px);
                z-index: 11000;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
                box-sizing: border-box;
            }

            .rp-modal-box {
                background: #ffffff;
                border-radius: 20px;
                width: 100%;
                max-width: 420px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, .2);
                overflow: hidden;
                animation: rpModalPop .2s ease-out;
                box-sizing: border-box;
            }

            @keyframes rpModalPop {
                from {
                    transform: scale(0.95);
                    opacity: 0;
                }

                to {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            .rp-modal-hdr {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 1.15rem;
                border-bottom: 1px solid #f1f5f9;
            }

            .rp-modal-icon {
                width: 34px;
                height: 34px;
                border-radius: 10px;
                background: #f0fdf4;
                color: #16a34a;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .rp-modal-title {
                font-size: 0.95rem;
                font-weight: 700;
                color: #0f172a;
                margin: 0;
                line-height: 1.2;
            }

            .rp-modal-sub {
                font-size: 0.67rem;
                color: #64748b;
                margin: 2px 0 0;
            }

            .rp-modal-close {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                border: none;
                background: #f1f5f9;
                color: #64748b;
                font-size: 1.2rem;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                line-height: 1;
            }

            .rp-modal-body {
                padding: 1rem 1.15rem;
                max-height: 72vh;
                overflow-y: auto;
            }

            .rp-form-group {
                margin-bottom: 1rem;
            }

            .rp-form-label {
                display: block;
                font-size: 0.72rem;
                font-weight: 700;
                color: #334155;
                margin-bottom: 0.45rem;
            }

            .rp-form-req {
                color: #ef4444;
            }

            /* Format Selection Cards (PDF vs CSV) */
            .rp-format-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.55rem;
            }

            .rp-format-card {
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                padding: 0.75rem 0.65rem;
                cursor: pointer;
                transition: all .18s;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                position: relative;
                background: #fff;
            }

            .rp-format-card.active {
                border-color: #1eb349;
                background: #f0fdf4;
                box-shadow: 0 3px 10px rgba(30, 179, 73, .15);
            }

            .rp-format-icon {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 0.45rem;
            }

            .rp-format-icon.pdf {
                background: #fee2e2;
                color: #dc2626;
            }

            .rp-format-icon.csv {
                background: #dcfce7;
                color: #16a34a;
            }

            .rp-format-title {
                font-size: 0.78rem;
                font-weight: 700;
                color: #0f172a;
            }

            .rp-format-desc {
                font-size: 0.62rem;
                color: #64748b;
                margin-top: 2px;
                line-height: 1.3;
            }

            .rp-format-check {
                position: absolute;
                top: 8px;
                right: 8px;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                background: #1eb349;
                color: #fff;
                display: none;
                align-items: center;
                justify-content: center;
            }

            .rp-format-card.active .rp-format-check {
                display: flex;
            }

            /* Modal Period Pills */
            .rp-exp-periods {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 0.35rem;
            }

            .rp-exp-pill {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0.42rem 0.2rem;
                border-radius: 8px;
                background: #fff;
                border: 1.5px solid #e2e8f0;
                color: #475569;
                font-size: 0.68rem;
                font-weight: 600;
                cursor: pointer;
                text-align: center;
                transition: all .15s;
            }

            .rp-exp-pill.active {
                background: var(--pg);
                color: #fff;
                border-color: transparent;
                font-weight: 700;
            }

            .rp-date-input,
            .rp-select-input {
                width: 100%;
                box-sizing: border-box;
                border: 1.5px solid #e2e8f0;
                border-radius: 9px;
                padding: 0.45rem 0.65rem;
                font-size: 0.75rem;
                color: #0f172a;
                background: #fff;
                outline: none;
            }

            .rp-date-input:focus,
            .rp-select-input:focus {
                border-color: #1eb349;
            }

            .rp-modal-ftr {
                display: flex;
                align-items: center;
                gap: 0.6rem;
                padding: 0.85rem 1.15rem;
                border-top: 1px solid #f1f5f9;
                background: #fafafa;
            }

            .rp-btn-cancel {
                flex: 1;
                padding: 0.58rem;
                border-radius: 999px;
                background: #fff;
                border: 1.5px solid #e2e8f0;
                color: #64748b;
                font-size: 0.75rem;
                font-weight: 600;
                cursor: pointer;
            }

            .rp-btn-submit {
                flex: 2;
                padding: 0.58rem 1rem;
                border-radius: 999px;
                background: var(--pg);
                border: none;
                color: #fff;
                font-size: 0.78rem;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                cursor: pointer;
                box-shadow: 0 4px 14px rgba(30, 179, 73, .35);
            }
    </style>
@endsection

@section('content')
    <div class="rp-wrap">

        {{-- Header --}}
        <div class="rp-hdr">
            <div class="rp-hdr-left">

                    <a href="{{ route('creator.pos.index') }}" class="rp-back" title="Kembali ke POS">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3"
                            viewBox="0 0 24 24">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="rp-hdr-title">Laporan POS</h1>
                        <p class="rp-hdr-sub">Rekap penjualan kasir digital</p>

                    </div>
            </div>
            <button type="button" class="rp-export-btn" onclick="openExportModal()" title="Ekspor Laporan" style="width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;padding:0;border:1.5px solid #bbf7d0;background:#fff;color:#166534;cursor:pointer;box-shadow:0 2px 8px rgba(30,179,73,.12);">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                </button>
        </div>

        {{-- Period Filter 3×2 Grid --}}
        <div class="rp-period-grid">
            @php $periods = [
                'today' => 'Hari Ini',
                'yesterday' => 'Kemarin',
                '7' => '7 Hari',
                '30' => '30 Hari',
                'this_month' => 'Bulan Ini',
                'all' => 'Semua'
            ]; @endphp
            @foreach($periods as $key => $label)
                <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['filter' => $key])) }}"
                    class="rp-period-pill {{ $filter === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>

        {{-- KPI Cards --}}
        <div class="rp-kpi-grid">
            <div class="rp-kpi dark">
                <div class="rp-kpi-label">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                    Total Reveneu
                </div>
                <div class="rp-kpi-val">Rp {{ number_format($posTotalSales, 0, ',', '.') }}</div>
                <div class="rp-kpi-sub">{{ number_format($posTotalTransactions) }} struk selesai</div>
            </div>

            <div class="rp-kpi">
                <div class="rp-kpi-label">
                    <svg width="12" height="12" fill="none" stroke="#059669" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="12" cy="12" r="2" />
                    </svg>
                    Tunai (Cash)
                </div>
                <div class="rp-kpi-val" style="color:#059669;">Rp {{ number_format($posCashTotal, 0, ',', '.') }}</div>
                <div class="rp-kpi-sub">Fisik di laci kasir</div>
            </div>

            <div class="rp-kpi">
                <div class="rp-kpi-label">
                    <svg width="12" height="12" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    QRIS / Digital
                </div>
                <div class="rp-kpi-val" style="color:#2563eb;">Rp {{ number_format($posDigitalTotal, 0, ',', '.') }}</div>
                <div class="rp-kpi-sub">Non-tunai / cashless</div>
            </div>

            <div class="rp-kpi">
                <div class="rp-kpi-label">
                    <svg width="12" height="12" fill="none" stroke="#d97706" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4" />
                        <path d="M4 6v12a2 2 0 0 0 2 2h14v-4" />
                        <circle cx="16" cy="14" r="1" />
                    </svg>
                    Saldo Deposit
                </div>
                <div class="rp-kpi-val" style="color:#d97706;">Rp {{ number_format($posBalance, 0, ',', '.') }}</div>
                <div class="rp-kpi-sub">Saldo biaya transaksi</div>
            </div>
        </div>

        {{-- Method Filter (small, scrollable) --}}
        <div class="rp-method-row">
            @foreach(['all' => 'Semua (' . count($posOrders) . ')', 'cash' => 'Tunai (Cash)', 'qris' => 'QRIS / Digital', 'transfer' => 'Transfer Bank'] as $k => $v)
                <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['pos_method' => $k])) }}"
                    class="rp-method-pill {{ ($posPaymentMethod ?? 'all') === $k ? 'active' : '' }}">{{ $v }}</a>
            @endforeach
        </div>

        {{-- Section Tabs --}}
        <div class="rp-sec-tabs">
            <button type="button" class="rp-sec-btn active" onclick="switchSec('struk',this)">Struk
                ({{ count($posOrders) }})</button>
            <button type="button" class="rp-sec-btn" onclick="switchSec('menu',this)">Top Menu</button>
            <button type="button" class="rp-sec-btn" onclick="switchSec('mutasi',this)">Mutasi Deposit</button>
        </div>

        {{-- SECTION 1: STRUK --}}
        <div id="sec-struk">
            <div class="rp-search">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" id="orderSearch" placeholder="Cari no. struk atau nama pelanggan…"
                    oninput="filterOrders()">

            </div>

            <div id="ordersContainer">
                @forelse($posOrders as $order)
                    @php
                        $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
                        $cName = $addr['name'] ?? $order->user?->name ?? 'Walk-in';
                        $cName = $addr['name'] ?? $order->user?->name ?? 'Pelanggan Umum';
                        $method = strtolower($addr['payment_method'] ?? ($order->payment->method ?? 'cash'));
                        $tableNo = $addr['table_number'] ?? ($addr['table'] ?? null);
                    @endphp
                    <div class="rp-order-card" style="cursor:pointer;" onclick="openReceiptModal({{ json_encode($order) }})" data-search="{{ strtolower($order->order_number . ' ' . $cName) }}">
                        <div class="rp-order-top">
                            <span class="rp-order-no">#{{ $order->order_number }}</span>
                            <span class="rp-order-time">{{ $order->created_at->format('d M y H:i') }}</span>
                        </div>
                        <div class="rp-order-mid">
                            <span class="rp-order-customer">{{ $cName }}{{ $tableNo ? ' · Meja ' . $tableNo : '' }}</span>
                            <span class="rp-order-amt">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="rp-order-foot">
                            @if($method === 'cash')
                                <span class="badge-cash">Tunai</span>
                            @elseif($method === 'qris')
                                <span class="badge-qris">QRIS</span>
                            @else
                                <span class="badge-tf">{{ strtoupper($method) }}</span>
                            @endif
                            <span>{{ $order->items->sum('qty') }} item</span>
                        </div>
                    </div>
                @empty
                    <div class="rp-empty">
                        <svg width="36" height="36" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24"
                            style="margin:0 auto;display:block;">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="8" y1="12" x2="16" y2="12" />
                        </svg>
                        <p>Belum ada transaksi POS</p>
                        <small>Pada rentang waktu yang dipilih.</small>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- SECTION 2: TOP MENU --}}
        <div id="sec-menu" style="display:none;">
            @forelse($topProducts as $i => $p)
                <div class="rp-rank">
                    <div class="rp-rank-left">
                        <div class="rp-rank-badge {{ $i === 0 ? 'r1' : ($i === 1 ? 'r2' : ($i === 2 ? 'r3' : '')) }}">#{{ $i + 1 }}</div>
                        <div>
                            <div class="rp-rank-name">{{ $p->product_name ?? 'Menu POS' }}</div>
                            <div class="rp-rank-qty">Terjual <strong style="color:#166534;">{{ $p->total_qty }}</strong> porsi
                            </div>
                        </div>
                    </div>
                    <div class="rp-rank-rev">Rp {{ number_format($p->total_revenue, 0, ',', '.') }}</div>
                </div>
            @empty
                <div class="rp-empty">
                    <p>Belum ada data menu terlaris</p>
                    <small>Data muncul otomatis setelah ada transaksi.</small>
                </div>
            @endforelse
        </div>

        {{-- SECTION 3: MUTASI --}}
        <div id="sec-mutasi" style="display:none;">
            @forelse($posMutations as $m)
                <div class="rp-mut-card">
                    <div style="min-width:0;">
                        <div class="rp-mut-type" style="color: {{ $m->type === 'topup' ? '#16a34a' : '#ef4444' }};">
                            {{ $m->type === 'topup' ? '+ Top Up Deposit' : '- Potongan Biaya' }}
                        </div>
                        <div class="rp-mut-desc">{{ $m->description ?? '-' }}</div>
                        <div class="rp-mut-date">{{ $m->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="rp-mut-amt" style="color: {{ $m->type === 'topup' ? '#16a34a' : '#ef4444' }};">
                        {{ $m->type === 'topup' ? '+' : '-' }} Rp {{ number_format(abs($m->amount), 0, ',', '.') }}
                    </div>
                </div>
            @empty
                <div class="rp-empty">
                    <p>Belum ada riwayat mutasi deposit</p>
                    <small>Pada rentang waktu yang dipilih.</small>
                </div>
            @endforelse
        </div>

    </div>{{-- end .rp-wrap --}}

    {{-- Bottom Nav --}}
    {{-- Bottom Nav (4 Items) --}}
    <div class="pos-bottom-nav-wrap">
        <div class="pos-bottom-nav-bar">
            <a href="{{ route('creator.pos.index') }}" class="pos-nav-item" title="Home">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                <span class="pos-nav-label">Home</span>
            </a>
            <a href="{{ route('creator.pos.index', ['screen' => 'transactions']) }}" class="pos-nav-item" title="Transaksi">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="2" y="4" width="20" height="16" rx="2" />
                    <circle cx="12" cy="12" r="3" />
                    <line x1="2" y1="9" x2="6" y2="9" />
                    <line x1="18" y1="15" x2="22" y2="15" />
                </svg>
                <span class="pos-nav-label">Transaksi</span>
            </a>
            <a href="{{ route('creator.pos.reports') }}" class="pos-nav-item active" title="Laporan">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="18" y1="20" x2="18" y2="10" />
                    <line x1="12" y1="20" x2="12" y2="4" />
                    <line x1="6" y1="20" x2="6" y2="14" />
                </svg>
                <span class="pos-nav-label">Laporan</span>
            </a>
            <a href="{{ route('creator.pos.profile') }}" class="pos-nav-item" title="Profil">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="pos-nav-label">Profil</span>
            </a>
        </div>
    </div>

    {{-- ── MODAL EKSPOR LAPORAN POS (Wajib Atur Periode + Opsi PDF/CSV) ── --}}
    <div id="exportModal" class="rp-modal-overlay" style="display:none;"
        onclick="if(event.target===this) closeExportModal()">
        <div class="rp-modal-box">
            <div class="rp-modal-hdr">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div class="rp-modal-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="rp-modal-title">Ekspor Laporan POS</h3>
                        <p class="rp-modal-sub">Atur periode & pilih format unduhan</p>
                    </div>
                </div>
                <button type="button" class="rp-modal-close" onclick="closeExportModal()">&times;</button>
            </div>

            <form id="exportForm" method="GET" action="{{ route('creator.sales.report.export_pos') }}" target="_blank">
                <div class="rp-modal-body">

                    {{-- 1. PILIH FORMAT (Wajib: PDF atau CSV) --}}
                    <div class="rp-form-group">
                        <label class="rp-form-label">
                            <span class="rp-form-req">*</span> 1. Format File Unduhan
                        </label>
                        <div class="rp-format-grid">
                            <label class="rp-format-card active" id="cardPdf" onclick="selectFormat('pdf')">
                                <input type="radio" name="format" value="pdf" checked style="display:none;">
                                <div class="rp-format-icon pdf">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2"
                                        viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                        <polyline points="10 9 9 9 8 9" />
                                    </svg>
                                </div>
                                <div class="rp-format-title">Dokumen PDF</div>
                                <div class="rp-format-desc">Siap print / share WA, ada rekap omset</div>
                                <div class="rp-format-check">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3"
                                        viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                            </label>

                            <label class="rp-format-card" id="cardCsv" onclick="selectFormat('csv')">
                                <input type="radio" name="format" value="csv" style="display:none;">
                                <div class="rp-format-icon csv">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2"
                                        viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <path d="M8 13h2" />
                                        <path d="M8 17h2" />
                                        <path d="M14 13h2" />
                                        <path d="M14 17h2" />
                                    </svg>
                                </div>
                                <div class="rp-format-title">Excel / CSV</div>
                                <div class="rp-format-desc">Data tabel spreadsheet untuk pembukuan</div>
                                <div class="rp-format-check">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3"
                                        viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 2. PILIH PERIODE (Wajib) --}}
                    <div class="rp-form-group">
                        <label class="rp-form-label">
                            <span class="rp-form-req">*</span> 2. Atur Periode Laporan
                        </label>
                        <div class="rp-exp-periods">
                            <label class="rp-exp-pill {{ $filter === 'today' ? 'active' : '' }}"
                                onclick="setExpPeriod('today', this)">
                                <input type="radio" name="filter" value="today" {{ $filter === 'today' ? 'checked' : '' }}
                                    style="display:none;">
                                Hari Ini
                            </label>
                            <label class="rp-exp-pill {{ $filter === 'yesterday' ? 'active' : '' }}"
                                onclick="setExpPeriod('yesterday', this)">
                                <input type="radio" name="filter" value="yesterday" {{ $filter === 'yesterday' ? 'checked' : '' }}
                                    style="display:none;">
                                Kemarin
                            </label>
                            <label class="rp-exp-pill {{ $filter === '7' ? 'active' : '' }}" onclick="setExpPeriod('7', this)">
                                <input type="radio" name="filter" value="7" {{ $filter === '7' ? 'checked' : '' }}
                                    style="display:none;">
                                7 Hari
                            </label>
                            <label class="rp-exp-pill {{ ($filter === '30' || !$filter) ? 'active' : '' }}"
                                onclick="setExpPeriod('30', this)">
                                <input type="radio" name="filter" value="30" {{ ($filter === '30' || !$filter) ? 'checked' : '' }}
                                    style="display:none;">
                                30 Hari
                            </label>
                            <label class="rp-exp-pill {{ $filter === 'this_month' ? 'active' : '' }}"
                                onclick="setExpPeriod('this_month', this)">
                                <input type="radio" name="filter" value="this_month" {{ $filter === 'this_month' ? 'checked' : '' }} style="display:none;">
                                Bulan Ini
                            </label>
                            <label class="rp-exp-pill {{ $filter === 'last_month' ? 'active' : '' }}"
                                onclick="setExpPeriod('last_month', this)">
                                <input type="radio" name="filter" value="last_month" {{ $filter === 'last_month' ? 'checked' : '' }} style="display:none;">
                                Bulan Lalu
                            </label>
                            <label class="rp-exp-pill {{ $filter === 'all' ? 'active' : '' }}"
                                onclick="setExpPeriod('all', this)">
                                <input type="radio" name="filter" value="all" {{ $filter === 'all' ? 'checked' : '' }}
                                    style="display:none;">
                                Semua
                            </label>
                            <label class="rp-exp-pill {{ $filter === 'custom' ? 'active' : '' }}"
                                onclick="setExpPeriod('custom', this)">
                                <input type="radio" name="filter" value="custom" {{ $filter === 'custom' ? 'checked' : '' }}
                                    style="display:none;">
                                Custom
                            </label>
                        </div>

                        {{-- Custom Date Picker Range --}}
                        <div id="customDateWrap"
                            style="display:{{ $filter === 'custom' ? 'block' : 'none' }}; margin-top:0.6rem; padding:0.65rem 0.75rem; background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem;">
                                <div>
                                    <label
                                        style="display:block; font-size:0.68rem; font-weight:600; color:#475569; margin-bottom:3px;">Dari
                                        Tanggal</label>
                                    <input type="date" name="start_date" id="expStartDate"
                                        value="{{ request('start_date', date('Y-m-d', strtotime('-7 days'))) }}"
                                        class="rp-date-input">
                                </div>
                                <div>
                                    <label
                                        style="display:block; font-size:0.68rem; font-weight:600; color:#475569; margin-bottom:3px;">Sampai
                                        Tanggal</label>
                                    <input type="date" name="end_date" id="expEndDate"
                                        value="{{ request('end_date', date('Y-m-d')) }}" class="rp-date-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. FILTER METODE PEMBAYARAN (Opsional) --}}
                    <div class="rp-form-group" style="margin-bottom:0.35rem;">
                        <label class="rp-form-label">
                            3. Metode Pembayaran (Opsional)
                        </label>
                        <select name="pos_method" class="rp-select-input">
                            <option value="all" {{ ($posPaymentMethod ?? 'all') === 'all' ? 'selected' : '' }}>Semua Metode (Tunai,
                                QRIS & Transfer)</option>
                            <option value="cash" {{ ($posPaymentMethod ?? '') === 'cash' ? 'selected' : '' }}>Tunai (Cash) Saja
                            </option>
                            <option value="qris" {{ ($posPaymentMethod ?? '') === 'qris' ? 'selected' : '' }}>QRIS / Digital Saja
                            </option>
                            <option value="transfer" {{ ($posPaymentMethod ?? '') === 'transfer' ? 'selected' : '' }}>Transfer Bank
                                Saja</option>
                        </select>
                    </div>

                </div>

                <div class="rp-modal-ftr">
                    <button type="button" class="rp-btn-cancel" onclick="closeExportModal()">Batal</button>
                    <button type="submit" class="rp-btn-submit" onclick="setTimeout(closeExportModal, 800)">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3"
                            viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                        <span>Unduh Laporan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchSec(sec, btn) {
            document.querySelectorAll('.rp-sec-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('sec-struk').style.display = sec === 'struk' ? '' : 'none';
            document.getElementById('sec-menu').style.display = sec === 'menu' ? '' : 'none';
            document.getElementById('sec-mutasi').style.display = sec === 'mutasi' ? '' : 'none';
            document.getElementById('sec-struk').style.display = sec === 'struk' ? '' : 'none';
            document.getElementById('sec-menu').style.display = sec === 'menu' ? '' : 'none';
            document.getElementById('sec-mutasi').style.display = sec === 'mutasi' ? '' : 'none';
        }

        function filterOrders() {
            const q = (document.getElementById('orderSearch')?.value || '').toLowerCase().trim();
            document.querySelectorAll('#ordersContainer .rp-order-card').forEach(c => {
                c.style.display = (c.dataset.search || '').includes(q) ? '' : 'none';
            });
        }

        function openExportModal() {
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeExportModal() {
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        function selectFormat(fmt) {
            document.querySelectorAll('.rp-format-card').forEach(c => c.classList.remove('active'));
            if (fmt === 'pdf') {
                document.getElementById('cardPdf')?.classList.add('active');
                document.querySelector('input[name="format"][value="pdf"]').checked = true;
            } else {
                document.getElementById('cardCsv')?.classList.add('active');
                document.querySelector('input[name="format"][value="csv"]').checked = true;
            }
        }

        function setExpPeriod(val, el) {
            document.querySelectorAll('.rp-exp-pill').forEach(p => p.classList.remove('active'));
            el.classList.add('active');
            const radio = el.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            const wrap = document.getElementById('customDateWrap');
            if (wrap) {
                wrap.style.display = val === 'custom' ? 'block' : 'none';
            }
        }
    
        /* ── POS Smooth Slide Transition (direction-aware) ── */
        (function(){
            var navItems = document.querySelectorAll('.pos-nav-item');
            var activeIdx = Array.from(navItems).findIndex(function(el){ return el.classList.contains('active'); });
            navItems.forEach(function(item, idx){
                item.addEventListener('click', function(){
                    if(this.classList.contains('active')) return;
                    sessionStorage.setItem('posNavDir', idx > activeIdx ? 'right' : 'left');
                });
            });
            var dir = sessionStorage.getItem('posNavDir');
            sessionStorage.removeItem('posNavDir');
            var wrap = document.querySelector('.rp-wrap');
            if(wrap && dir){
                var from = dir === 'right' ? '28px' : '-28px';
                wrap.style.opacity = '0';
                wrap.style.transform = 'translateX('+from+')';
                wrap.style.transition = 'none';
                requestAnimationFrame(function(){
                    requestAnimationFrame(function(){
                        wrap.style.opacity = '1';
                        wrap.style.transform = 'translateX(0)';
                        wrap.style.transition = 'opacity 0.28s cubic-bezier(0.16,1,0.3,1),transform 0.28s cubic-bezier(0.16,1,0.3,1)';
                    });
                });
            }
        })();
    </script>

    {{-- MODAL DETAIL STRUK & PESANAN LENGKAP --}}
    <div id="receiptDetailModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15,23,42,0.6); backdrop-filter:blur(3px); z-index:9999; align-items:center; justify-content:center; padding:1rem; box-sizing:border-box;">
        <div style="background:#ffffff; border-radius:24px; max-width:440px; width:100%; max-height:90vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.2);">
            {{-- Modal Head --}}
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.25rem; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:34px; height:34px; border-radius:50%; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#16a34a;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    </div>
                    <div>
                        <div style="font-size:0.95rem; font-weight:800; color:#0f172a;" id="mRecStoreName">{{ $storeName }}</div>
                        <div style="font-size:0.72rem; color:#64748b;">Rincian Struk &amp; Pesanan</div>
                    </div>
                </div>
                <button type="button" onclick="closeReceiptModal()" style="background:#f1f5f9; border:none; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.1rem; color:#64748b; cursor:pointer;">&times;</button>
            </div>

            {{-- Modal Body Printable Area --}}
            <div id="printableReportReceiptArea" style="padding:1.25rem; overflow-y:auto; flex:1; font-size:0.8rem; color:#334155; line-height:1.5;">
                <div style="text-align:center; margin-bottom:1rem; border-bottom:1.5px dashed #cbd5e1; padding-bottom:0.75rem;">
                    <div style="font-size:1.1rem; font-weight:800; color:#0f172a;" id="mRecOrderNo">#BYL-0000</div>
                    <div style="font-size:0.74rem; color:#64748b; margin-top:2px;" id="mRecDateTime">-</div>
                </div>

                <div style="margin-bottom:0.85rem; font-size:0.78rem;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:3px;">
                        <span style="color:#64748b;">Pelanggan:</span>
                        <strong style="color:#0f172a;" id="mRecCustomer">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:3px;">
                        <span style="color:#64748b;">Kasir:</span>
                        <span style="color:#0f172a; font-weight:600;" id="mRecCashier">-</span>
                    </div>
                </div>

                {{-- Items --}}
                <div style="font-size:0.75rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:6px; border-top:1px dashed #e2e8f0; padding-top:8px;">
                    Daftar Menu &amp; Catatan
                </div>
                <div id="mRecItemsWrap" style="margin-bottom:0.85rem;">
                    {{-- Generated via JS --}}
                </div>

                {{-- Summary --}}
                <div style="border-top:1.5px dashed #cbd5e1; padding-top:8px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:4px; color:#64748b; font-size:0.78rem;">
                        <span>Total Kuantiti:</span>
                        <strong style="color:#0f172a;" id="mRecTotalQty">0</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:4px; color:#64748b; font-size:0.78rem;">
                        <span>Subtotal:</span>
                        <strong style="color:#0f172a;" id="mRecSubtotal">Rp 0</strong>
                    </div>
                    <div id="mRecDiscountRow" style="display:none; justify-content:space-between; margin-bottom:4px; color:#16a34a; font-size:0.78rem; font-weight:600;">
                        <span id="mRecDiscountLabel">Diskon:</span>
                        <span id="mRecDiscount">-Rp 0</span>
                    </div>
                    <div id="mRecFeesWrap" style="font-size:0.78rem;"></div>
                    
                    <div style="display:flex; justify-content:space-between; margin-top:8px; padding-top:8px; border-top:1.5px dashed #cbd5e1; font-size:0.96rem; font-weight:800; color:#0f172a;">
                        <span>Total:</span>
                        <span style="color:#16a34a;" id="mRecTotal">Rp 0</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-top:4px; font-size:0.78rem;">
                        <span style="color:#64748b;">Metode:</span>
                        <strong style="color:#0f172a;" id="mRecPayMethod">-</strong>
                    </div>
                    <div id="mRecCashDetails" style="display:none; margin-top:4px; font-size:0.74rem; color:#64748b; background:#f8fafc; padding:6px 10px; border-radius:8px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:2px;">
                            <span>Tunai Dibayar:</span>
                            <span id="mRecCashPaid" style="font-weight:700; color:#0f172a;">Rp 0</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span>Kembalian:</span>
                            <span id="mRecCashChange" style="font-weight:700; color:#16a34a;">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Foot --}}
            <div style="padding:0.9rem 1.25rem; border-top:1px solid #f1f5f9; background:#f8fafc; display:flex; gap:8px;">
                <button type="button" onclick="printReportReceipt()" style="flex:1; padding:0.65rem 1rem; border-radius:999px; background:#0f172a; color:#fff; font-size:0.8rem; font-weight:700; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    <span>Cetak Struk</span>
                </button>
                <button type="button" onclick="shareReportReceiptToWA()" style="padding:0.65rem 0.9rem; border-radius:999px; background:#22c55e; color:#fff; font-size:0.8rem; font-weight:700; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                    <span>WA</span>
                </button>
            </div>
        </div>
    </div>

    <script>

        function openReceiptModal(order) {
            currentSelectedReportOrder = order;
            const addr = typeof order.shipping_address === 'string' ? JSON.parse(order.shipping_address || '{}') : (order.shipping_address || {});
            
            document.getElementById('mRecOrderNo').innerText = '#' + (order.order_number || ('BYL-' + order.id));
            document.getElementById('mRecDateTime').innerText = new Date(order.created_at).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'});
            
            const cName = addr.name || order.customer_name || 'Pelanggan Umum';
            const tableVal = addr.table_number || addr.table || '';
            document.getElementById('mRecCustomer').innerText = cName + (tableVal ? ' (Meja ' + tableVal + ')' : '');
            
            document.getElementById('mRecCashier').innerText = addr.cashier_name || order.cashier_name || 'Kasir';
            
            // Items
            let itemsHtml = '';
            let totalQty = 0;
            (order.items || []).forEach((item, idx) => {
                const qty = item.qty || item.quantity || 1;
                totalQty += qty;
                const price = Number(item.price || 0);
                const sub = Number(item.subtotal || (price * qty));
                itemsHtml += `
                    <div style="margin-bottom:6px; line-height:1.35;">
                        <div style="font-weight:700; color:#0f172a;">${idx + 1}. ${escapeHtml(item.product_name)} ${item.variant_name ? '<span style="font-weight:normal; color:#16a34a;">(' + escapeHtml(item.variant_name) + ')</span>' : ''}</div>
                        ${item.notes ? '<div style="font-size:9.5px; color:#64748b; font-style:italic; margin-left:10px;">* ' + escapeHtml(item.notes) + '</div>' : ''}
                        <div style="display:flex; justify-content:space-between; font-size:11px; color:#475569; margin-top:1px;">
                            <span>${qty} x Rp ${price.toLocaleString('id-ID')}</span>
                            <span style="font-weight:700; color:#0f172a;">Rp ${sub.toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                `;
            });
            document.getElementById('mRecItemsWrap').innerHTML = itemsHtml || '<div style="color:#94a3b8; text-align:center;">(Tidak ada item)</div>';
            document.getElementById('mRecTotalQty').innerText = totalQty;
            document.getElementById('mRecSubtotal').innerText = 'Rp ' + Number(order.subtotal || 0).toLocaleString('id-ID');
            
            // Discount
            const disc = Number(order.discount || 0);
            const discRow = document.getElementById('mRecDiscountRow');
            if (disc > 0) {
                discRow.style.display = 'flex';
                const discName = addr.discount_details?.name;
                document.getElementById('mRecDiscountLabel').innerText = discName ? 'Diskon (' + discName + ')' : 'Diskon';
                document.getElementById('mRecDiscount').innerText = '-Rp ' + disc.toLocaleString('id-ID');
            } else {
                discRow.style.display = 'none';
            }
            
            // Custom fees
            const feesWrap = document.getElementById('mRecFeesWrap');
            const breakdown = Array.isArray(addr.fees_breakdown) ? addr.fees_breakdown : [];
            if (breakdown.length > 0) {
                let fHtml = '';
                breakdown.forEach(f => {
                    const amt = Number(f.amount || 0);
                    if (amt > 0) {
                        const sfx = f.type === 'percent' ? ` (${f.value}%)` : '';
                        fHtml += `
                            <div style="display:flex; justify-content:space-between; margin-bottom:3px; color:#475569;">
                                <span>${escapeHtml(f.name)}${sfx}</span>
                                <span style="font-weight:700; color:#0f172a;">Rp ${amt.toLocaleString('id-ID')}</span>
                            </div>
                        `;
                    }
                });
                feesWrap.innerHTML = fHtml;
                feesWrap.style.display = fHtml ? 'block' : 'none';
            } else if (addr.service_fee && Number(addr.service_fee) > 0) {
                feesWrap.innerHTML = `
                    <div style="display:flex; justify-content:space-between; margin-bottom:3px; color:#475569;">
                        <span>Biaya Layanan & Pajak</span>
                        <span style="font-weight:700; color:#0f172a;">Rp ${Number(addr.service_fee).toLocaleString('id-ID')}</span>
                    </div>
                `;
                feesWrap.style.display = 'block';
            } else {
                feesWrap.innerHTML = '';
                feesWrap.style.display = 'none';
            }
            
            document.getElementById('mRecTotal').innerText = 'Rp ' + Number(order.total || 0).toLocaleString('id-ID');
            const pMethod = addr.payment_method === 'cash' ? 'Tunai' : (addr.payment_method === 'qris' ? 'QRIS' : (addr.payment_method ? addr.payment_method.toUpperCase() : 'Non-Tunai'));
            document.getElementById('mRecPayMethod').innerText = pMethod;
            
            // Cash details
            const cashDetails = document.getElementById('mRecCashDetails');
            if (addr.payment_method === 'cash' && addr.cash_paid) {
                cashDetails.style.display = 'block';
                document.getElementById('mRecCashPaid').innerText = 'Rp ' + Number(addr.cash_paid).toLocaleString('id-ID');
                document.getElementById('mRecCashChange').innerText = 'Rp ' + Number(addr.cash_change || 0).toLocaleString('id-ID');
            } else {
                cashDetails.style.display = 'none';
            }
            
            document.getElementById('receiptDetailModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeReceiptModal() {
            document.getElementById('receiptDetailModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function escapeHtml(s) {
            return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function printReportReceipt() {
            const area = document.getElementById('printableReportReceiptArea');
            if (!area) return;
            const w = window.open('', '_blank', 'width=400,height=600');
            w.document.write(`<html><head><title>Struk</title><style>body{font-family:'Courier New',monospace;padding:10px;font-size:12px;}</style></head><body>${area.innerHTML}</body></html>`);
            w.document.close();
            w.focus();
            setTimeout(() => { w.print(); w.close(); }, 300);
        }

        function shareReportReceiptToWA() {
            if (!currentSelectedReportOrder) return;
            const order = currentSelectedReportOrder;
            const addr = typeof order.shipping_address === 'string' ? JSON.parse(order.shipping_address || '{}') : (order.shipping_address || {});
            const storeTitle = document.getElementById('mRecStoreName')?.innerText || 'Buyle POS';
            const invNum = order.order_number || ('BYL-' + order.id);
            const totalFmt = Number(order.total || 0).toLocaleString('id-ID');
            
            let itemsText = '';
            (order.items || []).forEach((it, idx) => {
                const qty = it.qty || it.quantity || 1;
                const p = Number(it.price || 0).toLocaleString('id-ID');
                const s = Number(it.subtotal || (it.price * qty)).toLocaleString('id-ID');
                itemsText += `${idx+1}. *${it.product_name}*${it.variant_name ? ' ('+it.variant_name+')' : ''}\n   ${qty} x Rp ${p} = Rp ${s}\n`;
            });
            
            const custName = addr.name || order.customer_name || '';
            let discText = '';
            if (order.discount && Number(order.discount) > 0) {
                const dn = addr.discount_details?.name ? ` (${addr.discount_details.name})` : '';
                discText = `Diskon${dn}: -Rp ${Number(order.discount).toLocaleString('id-ID')}\n`;
            }
            let feeText = '';
            const breakdown = Array.isArray(addr.fees_breakdown) ? addr.fees_breakdown : [];
            if (breakdown.length > 0) {
                breakdown.forEach(f => {
                    if (Number(f.amount || 0) > 0) {
                        const sfx = f.type === 'percent' ? ` (${f.value}%)` : '';
                        feeText += `${f.name}${sfx}: Rp ${Number(f.amount).toLocaleString('id-ID')}\n`;
                    }
                });
            } else if (addr.service_fee && Number(addr.service_fee) > 0) {
                feeText += `Biaya Layanan & Pajak: Rp ${Number(addr.service_fee).toLocaleString('id-ID')}\n`;
            }
            let cashText = '';
            if (addr.payment_method === 'cash' && addr.cash_paid) {
                cashText = `\nTunai Dibayar: Rp ${Number(addr.cash_paid).toLocaleString('id-ID')}\nKembalian: Rp ${Number(addr.cash_change || 0).toLocaleString('id-ID')}`;
            }
            const pMethod = addr.payment_method === 'cash' ? 'Tunai (Cash)' : 'Non-Tunai';
            
            const msg = `🧾 *STRUK BELANJA RESMI*\n*${storeTitle}*\n--------------------------------\nNo. Invoice: *#${invNum}*${custName ? '\nPelanggan: ' + custName : ''}\nTanggal: ${new Date(order.created_at).toLocaleDateString('id-ID')}\n--------------------------------\n*DETAIL PESANAN:*\n${itemsText}--------------------------------\n*RINGKASAN:*\nSubtotal: Rp ${Number(order.subtotal||0).toLocaleString('id-ID')}\n${discText}${feeText}*TOTAL: Rp ${totalFmt}*\nMetode: ${pMethod}${cashText}\nStatus: *LUNAS* ✅\n--------------------------------\n_Terima kasih telah berbelanja!_`;
            
            let phone = (addr.phone || order.customer_phone || '').replace(/[^0-9]/g, '');
            if (phone.startsWith('0')) phone = '62' + phone.substring(1);
            const enc = encodeURIComponent(msg);
            const waUrl = phone ? `https://api.whatsapp.com/send?phone=${phone}&text=${enc}` : `https://api.whatsapp.com/send?text=${enc}`;
            window.open(waUrl, '_blank');
        }
</script>
@endsection
