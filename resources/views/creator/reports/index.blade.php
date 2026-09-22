@extends('creator.layout')

@section('title', 'Laporan Penjualan – Creator Studio')
@section('page_title', 'Laporan Penjualan')
@section('page_subtitle', 'Pantau trafik visitor, sumber klik, dan data pembelian produkmu.')

@section('topbar_actions')
    <div class="filter-bar">
        <a href="{{ route('creator.sales.report', ['filter' => '7']) }}"
            class="filter-btn {{ $filter === '7' ? 'active' : '' }}">7 Hari</a>
        <a href="{{ route('creator.sales.report', ['filter' => '30']) }}"
            class="filter-btn {{ $filter === '30' ? 'active' : '' }}">30 Hari</a>
        <a href="{{ route('creator.sales.report', ['filter' => '90']) }}"
            class="filter-btn {{ $filter === '90' ? 'active' : '' }}">90 Hari</a>
        <div class="filter-divider"></div>
        <button type="button" class="filter-custom-btn {{ $filter === 'custom' ? 'active' : '' }}" id="btnOpenCustomDate">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            @if($filter === 'custom')
                {{ \Carbon\Carbon::parse($startDate)->format('d M') }} – {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            @else
                Custom
            @endif
        </button>
    </div>
@endsection

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Montserrat', sans-serif;
        }

        /* ── Header ─────────────────────────────────────────────── */
        .rp-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .rp-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 0.2rem;
        }

        .rp-sub {
            font-size: 0.82rem;
            color: #64748b;
            margin: 0;
            font-weight: 400;
        }

        /* ── Filter Bar ──────────────────────────────────────────── */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            padding: 0.4rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.45rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            background: transparent;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .filter-btn:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
        }

        .filter-divider {
            width: 1px;
            height: 20px;
            background: #e2e8f0;
        }

        .filter-custom-btn {
            padding: 0.45rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: 0.2s;
        }

        .filter-custom-btn:hover {
            border-color: #1eb349;
            color: #1eb349;
        }

        .filter-custom-btn.active {
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            border-color: transparent;
        }

        /* ── Custom Date Modal ───────────────────────────────────── */
        .date-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }

        .date-modal-overlay.show {
            display: flex;
        }

        .date-modal {
            background: #fff;
            border-radius: 20px;
            padding: 1.75rem;
            width: 360px;
            max-width: 95vw;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
        }

        .date-modal h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 1.25rem;
        }

        .date-input-group {
            margin-bottom: 1rem;
        }

        .date-input-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 0.35rem;
        }

        .date-input-group input {
            width: 100%;
            padding: 0.6rem 0.8rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.85rem;
            font-family: 'Montserrat', sans-serif;
            outline: none;
            transition: 0.2s;
            box-sizing: border-box;
        }

        .date-input-group input:focus {
            border-color: #1eb349;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
        }

        .date-modal-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }

        .btn-apply {
            flex: 1;
            padding: 0.65rem;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
        }

        .btn-cancel {
            padding: 0.65rem 1rem;
            background: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
        }

        /* ── Metric Cards & Luxury Banking Card ──────────────────── */
        .rp-hero-section {
            display: grid;
            grid-template-columns: minmax(320px, 440px) 1fr;
            gap: 1.25rem;
            margin-bottom: 1.75rem;
            align-items: stretch;
        }

        @media (max-width: 960px) {
            .rp-hero-section {
                grid-template-columns: 1fr;
            }
        }

        .luxury-card-wrapper {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .luxury-card {
            position: relative;
            border-radius: 20px;
            padding: 1.4rem 1.6rem;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.18);
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.3s ease;
            user-select: none;
            box-sizing: border-box;
        }

        .luxury-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 22px 45px rgba(0, 0, 0, 0.26);
        }

        .luxury-card::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: linear-gradient(
                115deg,
                rgba(255, 255, 255, 0) 30%,
                rgba(255, 255, 255, 0.12) 48%,
                rgba(255, 255, 255, 0.25) 50%,
                rgba(255, 255, 255, 0.12) 52%,
                rgba(255, 255, 255, 0) 70%
            );
            pointer-events: none;
            transform: rotate(25deg);
        }

        /* Tier 1: Perintis (Matte Silver / Brushed Steel) */
        .luxury-card.tier-silver {
            background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 50%, #94a3b8 100%);
            border: 1px solid rgba(255, 255, 255, 0.75);
            color: #0f172a;
            box-shadow: 0 12px 30px rgba(148, 163, 184, 0.3);
        }
        .luxury-card.tier-silver .lc-label,
        .luxury-card.tier-silver .lc-holder-label { color: #475569; }
        .luxury-card.tier-silver .lc-amount { color: #0f172a; text-shadow: 0 1px 2px rgba(255,255,255,0.7); }
        .luxury-card.tier-silver .lc-tier-badge { background: rgba(15, 23, 42, 0.08); color: #0f172a; border: 1px solid rgba(15, 23, 42, 0.15); }

        /* Tier 2: Hustler (Titanium Gray Card) */
        .luxury-card.tier-titanium {
            background: linear-gradient(135deg, #334155 0%, #1e293b 50%, #0f172a 100%);
            background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.03) 0px, rgba(255,255,255,0.03) 2px, transparent 2px, transparent 10px), linear-gradient(135deg, #334155 0%, #1e293b 50%, #0f172a 100%);
            border: 1px solid rgba(203, 213, 225, 0.35);
            color: #ffffff;
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.4);
        }
        .luxury-card.tier-titanium .lc-label,
        .luxury-card.tier-titanium .lc-holder-label { color: #94a3b8; }
        .luxury-card.tier-titanium .lc-amount { color: #ffffff; }
        .luxury-card.tier-titanium .lc-tier-badge { background: rgba(255, 255, 255, 0.12); color: #f1f5f9; border: 1px solid rgba(255, 255, 255, 0.25); }

        /* Tier 3: Pengusaha Muda (Rose Gold / Champagne Gold Minimalist) */
        .luxury-card.tier-rosegold {
            background: linear-gradient(135deg, #f5d0c5 0%, #e6b8a2 40%, #d49b85 80%, #b87355 100%);
            border: 1px solid rgba(255, 255, 255, 0.75);
            color: #3b1609;
            box-shadow: 0 14px 35px rgba(212, 155, 133, 0.4);
        }
        .luxury-card.tier-rosegold .lc-label,
        .luxury-card.tier-rosegold .lc-holder-label { color: #6e331f; }
        .luxury-card.tier-rosegold .lc-amount { color: #2e0f05; }
        .luxury-card.tier-rosegold .lc-tier-badge { background: rgba(61, 22, 9, 0.12); color: #3b1609; border: 1px solid rgba(61, 22, 9, 0.22); }

        /* Tier 4: Eksekutif Muda (Deep Emerald Platinum Card) */
        .luxury-card.tier-emerald {
            background: linear-gradient(135deg, #064e3b 0%, #047857 40%, #022c22 100%);
            border: 1px solid rgba(52, 211, 153, 0.4);
            color: #ffffff;
            box-shadow: 0 14px 38px rgba(4, 120, 87, 0.4);
        }
        .luxury-card.tier-emerald .lc-label,
        .luxury-card.tier-emerald .lc-holder-label { color: #a7f3d0; }
        .luxury-card.tier-emerald .lc-amount { color: #ffffff; text-shadow: 0 0 12px rgba(52, 211, 153, 0.35); }
        .luxury-card.tier-emerald .lc-tier-badge { background: rgba(52, 211, 153, 0.18); color: #6ee7b7; border: 1px solid rgba(52, 211, 153, 0.35); }

        /* Tier 5: Eksekutif Senior (Obsidian Matte Black Silver Engraved) */
        .luxury-card.tier-obsidian {
            background: linear-gradient(135deg, #111827 0%, #030712 100%);
            border: 1px solid rgba(226, 232, 240, 0.3);
            color: #f8fafc;
            box-shadow: 0 16px 42px rgba(0, 0, 0, 0.65);
        }
        .luxury-card.tier-obsidian .lc-label,
        .luxury-card.tier-obsidian .lc-holder-label { color: #94a3b8; }
        .luxury-card.tier-obsidian .lc-amount { color: #f8fafc; text-shadow: 0 2px 6px rgba(0,0,0,0.9); }
        .luxury-card.tier-obsidian .lc-tier-badge { background: rgba(255, 255, 255, 0.1); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.25); }

        /* Tier 6: Financial Freedom (Solid Carbon Fiber Ultra-Card) */
        .luxury-card.tier-carbon {
            background-color: #050505;
            background-image: 
                radial-gradient(rgba(255,255,255,0.09) 1px, transparent 0),
                radial-gradient(rgba(255,255,255,0.09) 1px, #050505 1px);
            background-size: 8px 8px;
            background-position: 0 0, 4px 4px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.85);
        }
        .luxury-card.tier-carbon .lc-label,
        .luxury-card.tier-carbon .lc-holder-label { color: #a1a1aa; letter-spacing: 0.1em; }
        .luxury-card.tier-carbon .lc-amount { color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.95); }
        .luxury-card.tier-carbon .lc-tier-badge { background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.35); font-weight: 800; letter-spacing: 0.08em; }

        /* EMV Chip & NFC elements */
        .lc-chip-group { display: flex; align-items: center; gap: 0.65rem; }
        .emv-chip {
            width: 36px; height: 26px;
            background: linear-gradient(135deg, #ffe066 0%, #d4af37 50%, #aa8c2c 100%);
            border-radius: 5px;
            position: relative;
            box-shadow: inset 0 1px 2px rgba(255,255,255,0.4), 0 2px 4px rgba(0,0,0,0.2);
            overflow: hidden; flex-shrink: 0;
        }
        .luxury-card.tier-titanium .emv-chip,
        .luxury-card.tier-obsidian .emv-chip,
        .luxury-card.tier-carbon .emv-chip {
            background: linear-gradient(135deg, #f1f5f9 0%, #94a3b8 50%, #64748b 100%);
        }
        .chip-line.horizontal { position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: rgba(0,0,0,0.25); }
        .chip-line.vertical { position: absolute; left: 50%; top: 0; bottom: 0; width: 1px; background: rgba(0,0,0,0.25); }

        .nfc-icon { opacity: 0.65; }

        /* Card Rows */
        .lc-top { display: flex; align-items: center; justify-content: space-between; z-index: 2; margin-bottom: 0.5rem; }
        .lc-brand { display: flex; align-items: center; gap: 0.5rem; }
        .lc-brand-title { font-size: 1.15rem; font-weight: 800; letter-spacing: -0.02em; }
        .lc-brand-dot { color: #1eb349; }

        .lc-middle { margin: 0.75rem 0; z-index: 2; }
        .lc-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem; }
        
        .lc-amount-row { display: flex; align-items: center; gap: 0.65rem; }
        .lc-amount { font-size: 1.65rem; font-weight: 800; letter-spacing: -0.02em; line-height: 1.1; word-break: break-word; transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.25s ease; }
        .lc-amount.hidden-mask { opacity: 0.85; letter-spacing: 0.05em; }

        .lc-eye-toggle {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: inherit;
            width: 30px; height: 30px;
            border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
            flex-shrink: 0; backdrop-filter: blur(4px); outline: none;
        }
        .luxury-card.tier-silver .lc-eye-toggle,
        .luxury-card.tier-rosegold .lc-eye-toggle {
            background: rgba(15, 23, 42, 0.08);
            border-color: rgba(15, 23, 42, 0.15);
            color: #0f172a;
        }
        .lc-eye-toggle:hover { transform: scale(1.1); background: rgba(255, 255, 255, 0.25); }

        .lc-bottom { display: flex; align-items: flex-end; justify-content: space-between; z-index: 2; gap: 0.5rem; }
        .lc-tier-label { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; opacity: 0.9; margin-bottom: 0.15rem; }
        .lc-holder-name { font-size: 0.85rem; font-weight: 800; letter-spacing: 0.03em; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .lc-subtitle-badge { font-size: 0.68rem; font-weight: 600; opacity: 0.85; text-align: right; }

        /* Progress bar container below card */
        .lc-progress-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.85rem 1.15rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
        }

        .lc-progress-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.76rem;
            color: #475569;
            font-weight: 500;
            flex-wrap: wrap;
            gap: 0.4rem;
        }

        .lc-progress-track {
            width: 100%;
            height: 9px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        .lc-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #1eb349 0%, #a5cf37 100%);
            border-radius: 999px;
            transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 0 10px rgba(30, 179, 73, 0.35);
        }

        .lc-tier-info-btn {
            background: none; border: none; color: #1eb349; font-size: 0.75rem; font-weight: 700;
            cursor: pointer; padding: 0; text-decoration: underline; font-family: 'Montserrat', sans-serif;
        }
        .lc-tier-info-btn:hover { color: #15803d; }

        /* Mini Stats Grid (2x2 beside Luxury Card) */
        .stats-mini-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }
        @media (max-width: 540px) {
            .stats-mini-grid {
                grid-template-columns: 1fr;
            }
        }
        .stat-mini-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1rem 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-mini-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        .sm-icon {
            width: 42px; height: 42px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sm-icon.green { background: #f0fdf4; color: #16a34a; }
        .sm-icon.blue { background: #eff6ff; color: #2563eb; }
        .sm-icon.purple { background: #faf5ff; color: #9333ea; }
        .sm-icon.orange { background: #fff7ed; color: #ea580c; }

        .sm-label { font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.15rem; }
        .sm-value { font-size: 1.3rem; font-weight: 800; color: #0f172a; line-height: 1.1; }

        /* ── TIER GUIDE MODAL ── */
        .tier-modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(6px);
            z-index: 9999; align-items: center; justify-content: center;
            padding: 1rem;
        }
        .tier-modal-overlay.show { display: flex; animation: tmFadeIn 0.25s ease; }
        @keyframes tmFadeIn { from { opacity: 0; } to { opacity: 1; } }

        .tier-modal-card {
            background: #ffffff; border-radius: 24px; max-width: 680px; width: 100%;
            max-height: 90vh; overflow-y: auto; padding: 1.75rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3); position: relative;
        }
        .tier-modal-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
        .tier-modal-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0; }
        .tier-modal-close {
            background: #f1f5f9; border: none; width: 32px; height: 32px;
            border-radius: 50%; font-size: 18px; cursor: pointer; color: #64748b;
            display: flex; align-items: center; justify-content: center;
        }
        .tier-grid-preview {
            display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;
        }
        @media (max-width: 580px) { .tier-grid-preview { grid-template-columns: 1fr; } }
        .tier-item-box {
            border-radius: 14px; padding: 1rem; border: 1px solid #e2e8f0;
            display: flex; flex-direction: column; gap: 0.4rem; position: relative;
            overflow: hidden;
        }
        .tier-item-name { font-size: 0.85rem; font-weight: 800; }
        .tier-item-target { font-size: 0.78rem; font-weight: 700; opacity: 0.9; }
        .tier-item-desc { font-size: 0.72rem; opacity: 0.8; line-height: 1.4; }

        /* ── Traffic Wave Chart Card ─────────────────────────────── */
        /* ── Charts Grid (2 Cards) ─────────────────────────────────── */
        .charts-wave-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 900px) {
            .charts-wave-grid {
                grid-template-columns: 1fr;
            }
        }

        .wave-card {
            background: #fff;
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            margin-bottom: 1.5rem;
            min-width: 0;
            overflow: hidden;
        }

        .wave-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .wave-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .wave-live-badge {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #1eb349;
            background: #dcfce7;
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
        }

        .wave-live-dot {
            width: 7px;
            height: 7px;
            background: #1eb349;
            border-radius: 50%;
            animation: pulse-dot 1.5s infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.3);
            }
        }

        .chart-wrap {
            position: relative;
            height: 140px;
            min-width: 0;
            width: 100%;
        }

        /* ── Content Grid ────────────────────────────────────────── */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
            align-items: start;
            min-width: 0;
        }

        /* ── Panel Card ──────────────────────────────────────────── */
        .panel-card {
            background: #fff;
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            min-width: 0;
            overflow: hidden;
        }

        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .panel-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .export-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
            background: #0f172a;
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.2s;
            white-space: nowrap;
        }

        .btn-export:hover {
            background: #1eb349;
        }

        .btn-export.red {
            background: #dc2626;
        }

        .btn-export.red:hover {
            background: #b91c1c;
        }

        /* ── Data Table ──────────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .data-table th {
            text-align: left;
            padding: 0.65rem 0.75rem;
            color: #64748b;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .data-table td {
            padding: 0.75rem;
            color: #334155;
            border-bottom: 1px solid #f8fafc;
            font-weight: 500;
            vertical-align: middle;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:hover td {
            background: #fafafa;
        }

        .td-user .name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.82rem;
        }

        .td-user .email {
            font-size: 0.72rem;
            color: #94a3b8;
            margin-top: 1px;
        }

        .td-user .phone {
            font-size: 0.72rem;
            color: #94a3b8;
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.55rem;
            border-radius: 5px;
            font-size: 0.72rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            margin: 1px;
        }

        .badge.green {
            background: #dcfce7;
            color: #166534;
        }

        /* ── Sidebar Lists ───────────────────────────────────────── */
        .side-panel {
            margin-bottom: 1.25rem;
        }

        .list-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.7rem 0;
            border-bottom: 1px dashed #e2e8f0;
            gap: 1rem;
        }

        .list-row:last-child {
            border-bottom: none;
        }

        .list-name {
            font-weight: 600;
            font-size: 0.82rem;
            color: #1e293b;
            flex: 1;
            min-width: 0;
        }

        .list-name small {
            display: block;
            font-weight: 400;
            color: #94a3b8;
            font-size: 0.7rem;
            margin-top: 1px;
        }

        .list-stat {
            font-weight: 700;
            font-size: 0.8rem;
            color: #1eb349;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 1100px) {
            .main-grid {
                grid-template-columns: 1fr;
            }

            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .metrics-grid .dark[style*="span 4"] {
                grid-column: span 2 !important;
            }
        }

        @media (max-width: 600px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }

            .metrics-grid .dark[style*="span 4"] {
                grid-column: span 2 !important;
            }

            .metric-card {
                padding: 0.85rem 1rem;
            }

            .metric-value {
                font-size: 1.05rem;
            }

            .rp-title {
                font-size: 1.3rem;
            }

            .filter-bar {
                gap: 0.25rem;
            }

            .data-table {
                width: 100%;
            }

            .wave-card {
                padding: 1rem;
            }
        }

        @media (max-width: 420px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }

            .metrics-grid .dark[style*="span 4"] {
                grid-column: span 1 !important;
            }
        }

        /* ── Print Receipt Styles ────────────────────────────────────────── */
        #printableReceiptArea {
            display: none;
        }

        @media print {
            body * {
                visibility: hidden !important;
            }

            #printableReceiptArea,
            #printableReceiptArea * {
                visibility: visible !important;
            }

            #printableReceiptArea {
                display: block !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                max-width: 480px !important;
                margin: 0 auto !important;
                padding: 24px !important;
                background: #ffffff !important;
                color: #0f172a !important;
                font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif !important;
                box-sizing: border-box !important;
            }

            .date-modal-overlay,
            .sidebar,
            .topbar,
            header,
            footer,
            .main-grid,
            .metrics-grid,
            .charts-wave-grid {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')

@php
    $salesVal = (float)($totalSales ?? 0);
    $sellerStoreName = $config['name'] ?? $profile->store_name ?? auth()->user()->name ?? 'CREATOR';

    if ($salesVal >= 500000000) {
        $tierName = 'FINANCIAL FREEDOM';
        $tierSubtitle = 'Solid Carbon Fiber Ultra-Card';
        $tierClass = 'tier-carbon';
        $nextTierTarget = null;
        $nextTierName = null;
        $tierProgress = 100;
    } elseif ($salesVal >= 200000000) {
        $tierName = 'EKSEKUTIF SENIOR';
        $tierSubtitle = 'Obsidian Matte Black Priority';
        $tierClass = 'tier-obsidian';
        $nextTierTarget = 500000000;
        $nextTierName = 'Financial Freedom (500 Juta)';
        $tierProgress = round(($salesVal / 500000000) * 100, 1);
    } elseif ($salesVal >= 100000000) {
        $tierName = 'EKSEKUTIF MUDA';
        $tierSubtitle = 'Deep Emerald Platinum Card';
        $tierClass = 'tier-emerald';
        $nextTierTarget = 200000000;
        $nextTierName = 'Eksekutif Senior (200 Juta)';
        $tierProgress = round(($salesVal / 200000000) * 100, 1);
    } elseif ($salesVal >= 50000000) {
        $tierName = 'PENGUSAHA MUDA';
        $tierSubtitle = 'Rose Gold Minimalist Card';
        $tierClass = 'tier-rosegold';
        $nextTierTarget = 100000000;
        $nextTierName = 'Eksekutif Muda (100 Juta)';
        $tierProgress = round(($salesVal / 100000000) * 100, 1);
    } elseif ($salesVal >= 10000000) {
        $tierName = 'PEJUANG / HUSTLER';
        $tierSubtitle = 'Titanium Gray Metallic Card';
        $tierClass = 'tier-titanium';
        $nextTierTarget = 50000000;
        $nextTierName = 'Pengusaha Muda (50 Juta)';
        $tierProgress = round(($salesVal / 50000000) * 100, 1);
    } else {
        $tierName = 'PERINTIS';
        $tierSubtitle = 'Matte Silver Brushed Steel';
        $tierClass = 'tier-silver';
        $nextTierTarget = 10000000;
        $nextTierName = 'Pejuang / Hustler (10 Juta)';
        $tierProgress = round(($salesVal / 10000000) * 100, 1);
    }
@endphp

    {{-- ── Custom Date Modal ──────────────────────────────────────────── --}}
    <div class="date-modal-overlay" id="customDateModal">
        <div class="date-modal">
            <h4>Pilih Periode Custom</h4>
            <form method="GET" action="{{ route('creator.sales.report') }}" id="customDateForm">
                <input type="hidden" name="filter" value="custom">
                <div class="date-input-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="start_date" id="inputStartDate"
                        value="{{ $filter === 'custom' ? \Carbon\Carbon::parse($startDate)->format('Y-m-d') : \Carbon\Carbon::now()->subDays(30)->format('Y-m-d') }}"
                        required>
                </div>
                <div class="date-input-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="end_date" id="inputEndDate"
                        value="{{ $filter === 'custom' ? \Carbon\Carbon::parse($endDate)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}"
                        required>
                </div>
                <div class="date-modal-actions">
                    <button type="button" class="btn-cancel" id="btnCloseModal">Batal</button>
                    <button type="submit" class="btn-apply">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TAB NAVIGATION ────────────────────────────────────────────── --}}
    <div class="rp-tab-nav" style="display:flex; gap:0.5rem; margin-bottom:1.5rem; border-bottom:2px solid #e2e8f0; padding-bottom:0.25rem;">
        <button type="button" class="rp-tab-btn active" onclick="switchReportTab('analytics')" id="tabBtnAnalytics" style="padding:0.65rem 1.25rem; font-weight:700; font-size:0.9rem; border:none; background:none; color:#1eb349; border-bottom:3px solid #1eb349; cursor:pointer; display:flex; align-items:center; gap:0.5rem; transition:all 0.2s;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
            <span>Laporan Penjualan & Analytics</span>
        </button>
        <button type="button" class="rp-tab-btn" onclick="switchReportTab('leads')" id="tabBtnLeads" style="padding:0.65rem 1.25rem; font-weight:700; font-size:0.9rem; border:none; background:none; color:#64748b; border-bottom:3px solid transparent; cursor:pointer; display:flex; align-items:center; gap:0.5rem; transition:all 0.2s;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Leads Tracker</span>
            <span style="background:#1eb349; color:#fff; font-size:0.72rem; font-weight:800; padding:0.15rem 0.55rem; border-radius:999px;">{{ count($leads ?? []) }}</span>
        </button>
    </div>

    {{-- ── TAB 1: ANALYTICS & PENJUALAN ────────────────────────────────── --}}
    <div id="tabContentAnalytics" class="rp-tab-content">
        {{-- ── ACHIEVEMENT CARD & STATS HERO SECTION ─────────────────────── --}}
        <div class="rp-hero-section">
            {{-- Left Column: Luxury Banking Achievement Card --}}
            <div class="luxury-card-wrapper">
                <div class="luxury-card {{ $tierClass }}">
                    <div class="lc-top">
                        <div class="lc-chip-group">
                            <div class="emv-chip">
                                <div class="chip-line horizontal"></div>
                                <div class="chip-line vertical"></div>
                            </div>
                            <svg class="nfc-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M12 2a10 10 0 0 1 10 10" />
                                <path d="M12 6a6 6 0 0 1 6 6" />
                                <path d="M12 10a2 2 0 0 1 2 2" />
                            </svg>
                        </div>
                        <div class="lc-brand">
                            @php $siteLogo = \App\Models\Setting::get('logo'); @endphp
                            @if($siteLogo)
                                <img src="{{ asset('storage/' . $siteLogo) }}" alt="buyle.id" style="height:24px; max-width:120px; object-fit:contain;">
                            @else
                                <span class="lc-brand-title">buyle<span class="lc-brand-dot">.id</span></span>
                            @endif
                        </div>
                    </div>

                    <div class="lc-middle">
                        <div class="lc-label">TOTAL OMZET PENJUALAN</div>
                        <div class="lc-amount-row">
                            <div class="lc-amount" id="lcAmountText" data-amount="Rp {{ number_format($totalSales, 0, ',', '.') }}">
                                Rp {{ number_format($totalSales, 0, ',', '.') }}
                            </div>
                            <button type="button" class="lc-eye-toggle" id="btnToggleSalesMask" onclick="toggleSalesVisibility()" title="Tampilkan / Sembunyikan Nominal">
                                <svg id="eyeIconOpen" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="eyeIconClosed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="lc-bottom">
                        <div>
                            <div class="lc-tier-label">{{ $tierName }}</div>
                            <div class="lc-holder-name">{{ strtoupper($sellerStoreName) }}</div>
                        </div>
                        <div class="lc-subtitle-badge">
                            {{ $tierSubtitle }}
                        </div>
                    </div>

                    @if($nextTierTarget)
                        <div class="lc-progress-bar-wrap" title="Progress ke Tier Berikutnya">
                            <div class="lc-progress-bar" style="width: {{ min(100, $tierProgress) }}%;"></div>
                        </div>
                    @endif
                </div>

                <div class="lc-progress-container">
                    <div class="lc-progress-header">
                        @if($nextTierTarget)
                            <span>Pencapaian: <strong>{{ $tierProgress }}%</strong> menuju {{ $nextTierName }}</span>
                        @else
                            <span>🏆 Selamat! Anda telah mencapai Tier Tertinggi (Financial Freedom)</span>
                        @endif
                        <button type="button" class="lc-tier-info-btn" onclick="openTierModal()">
                            Lihat 6 Tier Card ⓘ
                        </button>
                    </div>
                    @if($nextTierTarget)
                        <div class="lc-progress-track">
                            <div class="lc-progress-fill" style="width: {{ min(100, max(2, $tierProgress)) }}%;"></div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: 4 Stat Cards in 2x2 Grid --}}
            <div class="stats-mini-grid">
                <div class="stat-mini-card">
                    <div class="sm-icon green">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <div class="sm-label">Total Visitor</div>
                        <div class="sm-value">{{ number_format($totalVisitors) }}</div>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="sm-icon blue">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <div class="sm-label">Unique Visitor</div>
                        <div class="sm-value">{{ number_format($uniqueVisitors) }}</div>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="sm-icon purple">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    </div>
                    <div>
                        <div class="sm-label">Total Transaksi</div>
                        <div class="sm-value">{{ number_format($totalOrders) }}</div>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="sm-icon orange">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    </div>
                    <div>
                        <div class="sm-label">Link Klik Bio</div>
                        <div class="sm-value">{{ number_format($totalBioClicks) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 2 Wave Cards Grid: Visitors Wave & Sales Wave ──────────────── --}}
        <div class="charts-wave-grid">
            {{-- Card Kiri: Grafik Wave Visitor --}}
            <div class="wave-card" style="margin-bottom:0;">
                <div class="wave-header">
                    <span class="wave-title">GRAFIK WAVE VISITOR</span>
                    <span class="wave-live-badge"><span class="wave-live-dot"></span> LIVE</span>
                </div>
                <div class="chart-wrap">
                    <canvas id="trafficChart"></canvas>
                </div>
            </div>

            {{-- Card Kanan: Grafik Wave Penjualan --}}
            <div class="wave-card" style="margin-bottom:0;">
                <div class="wave-header">
                    <span class="wave-title">GRAFIK WAVE PENJUALAN</span>
                    <span class="wave-live-badge" style="background:#e0e7ff; color:#3730a3;"><span class="wave-live-dot"
                            style="background:#4338ca;"></span> RP</span>
                </div>
                <div class="chart-wrap">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- ── Main Grid: Buyers + Sidebar ────────────────────────────────── --}}
        <div class="main-grid">

            {{-- Buyers Table --}}
            <div class="panel-card">
                <div class="panel-head">
                    <h3 class="panel-title">Data Pembeli</h3>
                    <div class="export-group">
                        <a href="{{ route('creator.sales.report.export', array_merge(request()->query(), ['format' => 'xls'])) }}"
                            class="btn-export">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            Export XLS
                        </a>
                        <a href="{{ route('creator.sales.report.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
                            class="btn-export red">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            Export PDF
                        </a>
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal & ID</th>
                                <th>Pembeli</th>
                                <th>Produk</th>
                                <th>Total</th>
                                <th>Status Pesanan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buyers as $order)
                                @php
                                    $statusVal = is_object($order->status) ? $order->status->value : (string) $order->status;
                                    $statusLabel = is_object($order->status) && method_exists($order->status, 'label') ? $order->status->label() : ucfirst($statusVal);
                                    $statusBadgeStyle = match ($statusVal) {
                                        'completed' => 'background:#dcfce7;color:#166534;border:1px solid #bbf7d0;',
                                        'shipped' => 'background:#e0e7ff;color:#3730a3;border:1px solid #c7d2fe;',
                                        'processing' => 'background:#fef3c7;color:#92400e;border:1px solid #fde68a;',
                                        'cancelled' => 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                                        default => 'background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;',
                                    };
                                    $shipment = $order->shipment;
                                    $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
                                @endphp
                                <tr>
                                    <td style="white-space:nowrap; color:#64748b; font-size:0.75rem;">
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <span
                                                style="font-weight:700;color:#0f172a;">#{{ $order->order_number ?? ('BYL-' . $order->id) }}</span>
                                            @if(($order->source ?? '') === 'pos')
                                                <span
                                                    style="background:#e0e7ff;color:#3730a3;font-size:0.65rem;padding:1px 6px;border-radius:4px;font-weight:700;">POS</span>
                                            @else
                                                <span
                                                    style="background:#f1f5f9;color:#475569;font-size:0.65rem;padding:1px 6px;border-radius:4px;font-weight:600;">Bio</span>
                                            @endif
                                        </div>
                                        <div>{{ $order->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="td-user">
                                        <div class="name" style="font-weight:700; color:#0f172a;">
                                            {{ $addr['name'] ?? $order->user?->name ?? 'Pembeli' }}</div>
                                        @if(!empty($addr['phone']) || !empty($order->user?->phone))
                                            <div class="phone" style="font-size:0.75rem;color:#1eb349;font-weight:600;">
                                                {{ $addr['phone'] ?? $order->user?->phone }}</div>
                                        @endif
                                        <div class="email" style="font-size:0.72rem;color:#94a3b8;">{{ $order->user?->email ?? '' }}
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($order->items as $item)
                                            <div style="display:flex;align-items:center;gap:4px;margin-bottom:2px;">
                                                <span class="badge"
                                                    style="font-weight:600;">{{ Str::limit($item->product_name, 28) }}</span>
                                                <span style="font-size:0.68rem;color:#64748b;">(x{{ $item->quantity }})</span>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td style="font-weight:800; white-space:nowrap; color:#0f172a;">Rp
                                        {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</td>
                                    <td>
                                        <span id="badge-status-{{ $order->id }}" class="badge"
                                            style="{{ $statusBadgeStyle }} font-weight:700; padding:3px 8px; border-radius:6px; font-size:0.72rem;">
                                            {{ $statusLabel }}
                                        </span>
                                        @if(!empty($shipment?->tracking_number))
                                            <div id="resi-text-{{ $order->id }}"
                                                style="font-size:0.7rem; color:#2563eb; font-weight:700; margin-top:3px;">
                                                <i class="fas fa-truck"></i> {{ $shipment->tracking_number }}
                                            </div>
                                        @else
                                            <div id="resi-text-{{ $order->id }}"
                                                style="font-size:0.68rem; color:#94a3b8; margin-top:2px;">Belum ada resi</div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $orderDataJson = json_encode([
                                                'id' => $order->id,
                                                'order_number' => $order->order_number ?? ('BYL-' . $order->id),
                                                'date' => $order->created_at->format('d M Y H:i'),
                                                'status' => $statusVal,
                                                'status_label' => $statusLabel,
                                                'user_name' => $addr['name'] ?? $order->user?->name ?? 'Pembeli',
                                                'phone' => $addr['phone'] ?? $order->user?->phone ?? '',
                                                'email' => $order->user?->email ?? '',
                                                'shipping_address' => $addr,
                                                'items' => $order->items->map(fn($i) => [
                                                    'name' => $i->product_name,
                                                    'quantity' => $i->quantity,
                                                    'price' => $i->price,
                                                    'subtotal' => $i->subtotal,
                                                    'product_type' => $i->product?->product_type ?? $i->product?->type ?? 'external_link',
                                                    'seller_id' => $i->seller_id,
                                                    'reseller_id' => $i->reseller_id,
                                                    'seller_name' => $i->seller?->name ?? '',
                                                    'reseller_name' => $i->reseller?->name ?? '',
                                                    'base_price' => (float) ($i->base_whitelabel_price ?? 0),
                                                    'reseller_margin' => (float) ($i->reseller_margin ?? 0),
                                                    'creator_earnings' => (float) ($i->creator_earnings ?? $i->subtotal),
                                                ])->values(),
                                                'subtotal' => $order->items->sum('subtotal'),
                                                'shipping_cost' => (float) ($order->shipping_cost ?? 0),
                                                'platform_fee' => (float) ($order->platform_fee ?? 0),
                                                'admin_fee' => (float) ($order->admin_fee ?? 0),
                                                'discount' => (float) ($order->discount ?? 0),
                                                'grand_total' => (float) ($order->total ?? $order->items->sum('subtotal')),
                                                'total' => (float) ($order->total ?? $order->items->sum('subtotal')),
                                                'seller_name' => auth()->user()->name ?? 'Kreator buyle.id',
                                                'courier_name' => $shipment?->courier_name ?? '',
                                                'tracking_number' => $shipment?->tracking_number ?? '',
                                                'update_url' => route('creator.sales.report.update_order', $order->id),
                                            ]);
                                        @endphp
                                        <div style="display:flex; align-items:center; gap:0.4rem;">
                                            <button type="button" onclick="openOrderModal({{ $orderDataJson }})" class="btn-export"
                                                style="background:#0f172a; color:#fff; border:none; padding:0.4rem 0.65rem; font-size:0.75rem; border-radius:8px; cursor:pointer; font-weight:700; white-space:nowrap;">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24" style="vertical-align:middle;margin-right:3px;">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                                Detail & Edit
                                            </button>
                                            <button type="button" onclick="printReceipt({{ $orderDataJson }})" class="btn-export"
                                                style="background:#1eb349; color:#fff; border:none; padding:0.4rem 0.65rem; font-size:0.75rem; border-radius:8px; cursor:pointer; font-weight:700; white-space:nowrap;"
                                                title="Cetak / Download e-Receipt Pesanan">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24" style="vertical-align:middle;margin-right:3px;">
                                                    <polyline points="6 9 6 2 18 2 18 9" />
                                                    <path
                                                        d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                                    <rect x="6" y="14" width="12" height="8" />
                                                </svg>
                                                e-Receipt
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align:center; padding: 2.5rem; color:#94a3b8; font-size:0.85rem;">
                                        Belum ada data pembeli di rentang waktu ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Sidebar --}}
            <div>
                {{-- Top Products --}}
                <div class="panel-card side-panel">
                    <h3 class="panel-title" style="margin-bottom:1rem;">Produk Terpopuler</h3>
                    @forelse($topProducts as $prod)
                        <div class="list-row">
                            <div class="list-name">
                                {{ Str::limit($prod->name, 32) }}
                                <small>Rp {{ number_format($prod->sold_amount ?? 0, 0, ',', '.') }}</small>
                            </div>
                            <div class="list-stat">{{ number_format($prod->visits_count) }} klik</div>
                        </div>
                    @empty
                        <p style="font-size:0.82rem; color:#94a3b8; margin:0;">Belum ada data kunjungan produk.</p>
                    @endforelse
                </div>

                {{-- Top Bio Link Clicks --}}
                <div class="panel-card side-panel">
                    <h3 class="panel-title" style="margin-bottom:1rem;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            style="vertical-align:middle;margin-right:4px;">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                        </svg>
                        Klik Link Bio
                    </h3>
                    @forelse($topBioLinks as $link)
                        <div class="list-row">
                            <div class="list-name">{{ Str::limit($link->page_title ?? 'Block #' . $link->bio_block_id, 32) }}</div>
                            <div class="list-stat">{{ number_format($link->click_count) }} klik</div>
                        </div>
                    @empty
                        <p style="font-size:0.82rem; color:#94a3b8; margin:0;">Belum ada klik link bio tercatat.</p>
                    @endforelse
                </div>

                {{-- Bio UTM Sources --}}
                @if($bioUtmSources->isNotEmpty())
                    <div class="panel-card side-panel">
                        <h3 class="panel-title" style="margin-bottom:1rem;">Sumber Trafik Bio</h3>
                        @foreach($bioUtmSources as $utm)
                            <div class="list-row">
                                <div class="list-name">{{ $utm->utm_source }}</div>
                                <div class="list-stat">{{ number_format($utm->count) }} klik</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- UTM Sources (orders) --}}
                <div class="panel-card">
                    <h3 class="panel-title" style="margin-bottom:1rem;">Sumber Trafik Order</h3>
                    @forelse($utmSources as $utm)
                        <div class="list-row">
                            <div class="list-name">{{ $utm->utm_source }}</div>
                            <div class="list-stat">{{ number_format($utm->count) }} transaksi</div>
                        </div>
                    @empty
                        <p style="font-size:0.82rem; color:#94a3b8; margin:0;">Belum ada data UTM tercatat.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- ── TAB 2: LEADS TRACKER ────────────────────────────────────────── --}}
    <div id="tabContentLeads" class="rp-tab-content" style="display:none;">
        {{-- Leads Metric Cards --}}
        <div class="metrics-grid" style="margin-bottom:1.5rem;">
            <div class="metric-card">
                <div class="metric-label">Total Leads Masuk</div>
                <div class="metric-value">{{ number_format(count($leads ?? [])) }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Leads Hari Ini</div>
                <div class="metric-value">{{ number_format(collect($leads ?? [])->filter(fn($l) => \Carbon\Carbon::parse($l->created_at)->isToday())->count()) }}</div>
            </div>
            <div class="metric-card dark" style="grid-column: span 2;">
                <div class="metric-label">Leads Bulan Ini</div>
                <div class="metric-value">{{ number_format(collect($leads ?? [])->filter(fn($l) => \Carbon\Carbon::parse($l->created_at)->isCurrentMonth())->count()) }}</div>
            </div>
        </div>

        {{-- Leads Table Panel --}}
        <div class="panel-card" style="width:100%; box-sizing:border-box;">
            <div class="panel-head" style="margin-bottom:1.25rem;">
                <div>
                    <h3 class="panel-title" style="margin:0 0 0.2rem;">Daftar Leads & Pesan Masuk</h3>
                    <p style="font-size:0.8rem; color:#64748b; margin:0;">Daftar calon klien & pembeli yang mengisi form Hubungi Kami dari website Anda.</p>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Waktu & Tanggal</th>
                            <th>Nama Lead</th>
                            <th>WhatsApp</th>
                            <th>Kota / Perusahaan</th>
                            <th>Kebutuhan / Pesan</th>
                            <th>Sumber</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads ?? [] as $lead)
                            @php
                                $waClean = preg_replace('/[^0-9]/', '', $lead->phone);
                                if(str_starts_with($waClean, '0')) $waClean = '62' . substr($waClean, 1);
                                $waMsg = "Halo Kak {$lead->name}, terima kasih telah menghubungi kami mengenai kebutuhan: " . ($lead->message ?? $lead->product);
                            @endphp
                            <tr>
                                <td style="white-space:nowrap; color:#64748b; font-size:0.78rem;">
                                    <div style="font-weight:600; color:#0f172a;">{{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}</div>
                                    <div style="font-size:0.72rem; color:#94a3b8;">{{ \Carbon\Carbon::parse($lead->created_at)->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    <div style="font-weight:700; color:#0f172a; font-size:0.88rem;">{{ $lead->name }}</div>
                                </td>
                                <td>
                                    @if($waClean)
                                        <a href="https://wa.me/{{ $waClean }}?text={{ urlencode($waMsg) }}" target="_blank"
                                            style="display:inline-flex; align-items:center; gap:4px; padding:0.25rem 0.65rem; background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; border-radius:6px; font-weight:700; font-size:0.78rem; text-decoration:none;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                            <span>{{ $lead->phone }}</span>
                                        </a>
                                    @else
                                        <span style="font-size:0.8rem; color:#64748b;">{{ $lead->phone }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size:0.83rem; font-weight:600; color:#334155;">{{ $lead->city ?? $lead->company ?? '-' }}</span>
                                </td>
                                <td style="max-width:280px; font-size:0.82rem; color:#475569; line-height:1.4;">
                                    {{ $lead->message ?? $lead->product ?? '-' }}
                                </td>
                                <td>
                                    <span style="display:inline-block; padding:0.2rem 0.55rem; background:#EFF6FF; border:1px solid #BFDBFE; color:#1D4ED8; border-radius:6px; font-size:0.72rem; font-weight:600;">
                                        {{ $lead->source === 'theme5_footer' ? 'Footer5' : ucfirst($lead->source ?? 'Website') }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        @if($waClean)
                                            <a href="https://wa.me/{{ $waClean }}?text={{ urlencode($waMsg) }}" target="_blank"
                                                title="Chat via WhatsApp"
                                                style="padding:0.35rem 0.65rem; background:#22c55e; color:#fff; border-radius:6px; font-size:0.75rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                                                WA
                                            </a>
                                        @endif

                                        <form method="POST" action="{{ route('creator.sales.report.destroy_lead', $lead->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lead ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Lead"
                                                style="padding:0.35rem 0.55rem; background:#FEE2E2; border:1px solid #FCA5A5; color:#991B1B; border-radius:6px; font-size:0.75rem; font-weight:700; cursor:pointer;">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:2.5rem; color:#94a3b8; font-size:0.85rem;">
                                    Belum ada data leads masuk dari formulir footer Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        function switchReportTab(tabName) {
            const analyticsContent = document.getElementById('tabContentAnalytics');
            const leadsContent     = document.getElementById('tabContentLeads');
            const tabBtnAnalytics  = document.getElementById('tabBtnAnalytics');
            const tabBtnLeads      = document.getElementById('tabBtnLeads');

            if (tabName === 'leads') {
                if (analyticsContent) analyticsContent.style.display = 'none';
                if (leadsContent) leadsContent.style.display = 'block';
                if (tabBtnAnalytics) {
                    tabBtnAnalytics.style.color = '#64748b';
                    tabBtnAnalytics.style.borderBottomColor = 'transparent';
                }
                if (tabBtnLeads) {
                    tabBtnLeads.style.color = '#1eb349';
                    tabBtnLeads.style.borderBottomColor = '#1eb349';
                }
            } else {
                if (analyticsContent) analyticsContent.style.display = 'block';
                if (leadsContent) leadsContent.style.display = 'none';
                if (tabBtnAnalytics) {
                    tabBtnAnalytics.style.color = '#1eb349';
                    tabBtnAnalytics.style.borderBottomColor = '#1eb349';
                }
                if (tabBtnLeads) {
                    tabBtnLeads.style.color = '#64748b';
                    tabBtnLeads.style.borderBottomColor = 'transparent';
                }
            }
        }
        // ── Traffic Wave Chart ────────────────────────────────────────────────
        (function () {
            const filter = @json($filter);
            const days = filter === '7' ? 7 : (filter === '90' ? 90 : 30);

            // Generate labels (day names or dates)
            const labels = [];
            const now = new Date();
            for (let i = days - 1; i >= 0; i--) {
                const d = new Date(now);
                d.setDate(d.getDate() - i);
                labels.push(days <= 7
                    ? d.toLocaleDateString('id-ID', { weekday: 'short' })
                    : d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' })
                );
            }

            // Visitor data from PHP — group by date
            const rawVisitors = @json($visitorsByDate ?? []);
            const data = labels.map((_, idx) => {
                const d = new Date(now);
                d.setDate(d.getDate() - (days - 1 - idx));
                const key = d.toISOString().split('T')[0];
                return rawVisitors[key] ?? 0;
            });

            const ctx = document.getElementById('trafficChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        data,
                        borderColor: '#1eb349',
                        backgroundColor: (context) => {
                            const g = context.chart.ctx.createLinearGradient(0, 0, 0, 130);
                            g.addColorStop(0, 'rgba(30,179,73,0.25)');
                            g.addColorStop(1, 'rgba(30,179,73,0)');
                            return g;
                        },
                        borderWidth: 2.5,
                        tension: 0.45,
                        fill: true,
                        pointRadius: days <= 7 ? 5 : 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#1eb349',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }, tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y} kunjungan`
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Montserrat', size: 11 }, color: '#94a3b8' } },
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Montserrat', size: 11 }, color: '#94a3b8', precision: 0 } }
                    }
                }
            });
        })();

        // ── Sales Wave Chart (Card Kanan) ────────────────────────────────────
        (function () {
            const filter = @json($filter);
            const days = filter === '7' ? 7 : (filter === '90' ? 90 : 30);
            const labels = [];
            const now = new Date();
            for (let i = days - 1; i >= 0; i--) {
                const d = new Date(now);
                d.setDate(d.getDate() - i);
                labels.push(days <= 7
                    ? d.toLocaleDateString('id-ID', { weekday: 'short' })
                    : d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' })
                );
            }
            const rawSales = @json($salesByDate ?? []);
            const data = labels.map((_, idx) => {
                const d = new Date(now);
                d.setDate(d.getDate() - (days - 1 - idx));
                const key = d.toISOString().split('T')[0];
                return rawSales[key] ?? 0;
            });

            const ctx = document.getElementById('salesChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        data,
                        borderColor: '#2563eb',
                        backgroundColor: (context) => {
                            const g = context.chart.ctx.createLinearGradient(0, 0, 0, 130);
                            g.addColorStop(0, 'rgba(37,99,235,0.25)');
                            g.addColorStop(1, 'rgba(37,99,235,0)');
                            return g;
                        },
                        borderWidth: 2.5,
                        tension: 0.45,
                        fill: true,
                        pointRadius: days <= 7 ? 5 : 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` Rp ${Number(ctx.parsed.y).toLocaleString('id-ID')}`
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Montserrat', size: 11 }, color: '#94a3b8' } },
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Montserrat', size: 11 }, color: '#94a3b8', precision: 0, callback: v => 'Rp ' + Number(v).toLocaleString('id-ID') } }
                    }
                }
            });
        })();

        // ── Custom Date Modal ─────────────────────────────────────────────────
        const modal = document.getElementById('customDateModal');
        const btnOpen = document.getElementById('btnOpenCustomDate');
        const btnClose = document.getElementById('btnCloseModal');

        if (btnOpen && modal) {
            btnOpen.addEventListener('click', () => modal.classList.add('show'));
            btnClose.addEventListener('click', () => modal.classList.remove('show'));
            modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('show'); });
        }

        // ── Order Detail & Edit Management Modal ────────────────────────────────
        let currentOrderData = null;

        function openOrderModal(data) {
            currentOrderData = data;
            document.getElementById('od_title').innerText = 'Detail Pesanan #' + data.order_number;
            document.getElementById('od_date').innerText = 'Tanggal Transaksi: ' + data.date;
            document.getElementById('od_user_name').innerText = data.user_name;

            let contactInfo = [];
            if (data.phone) contactInfo.push(data.phone);
            if (data.email) contactInfo.push(data.email);
            document.getElementById('od_user_contact').innerText = contactInfo.join('  •  ');

            // WA Button link
            const waBtn = document.getElementById('od_wa_btn');
            if (data.phone) {
                let cleanPhone = data.phone.replace(/[^0-9]/g, '');
                if (cleanPhone.startsWith('0')) cleanPhone = '62' + cleanPhone.substring(1);
                const waText = encodeURIComponent(`Halo ${data.user_name}, mengenai pesanan #${data.order_number} di buyle.id...`);
                waBtn.href = `https://wa.me/${cleanPhone}?text=${waText}`;
                waBtn.style.display = 'inline-flex';
            } else {
                waBtn.style.display = 'none';
            }

            // Check if order contains physical items vs digital/service/ticket
            const isPhysical = (data.items || []).some(item => ['physical', 'physical_product'].includes(item.product_type));

            // Shipping Address Box
            const addrBox = document.getElementById('od_address_box');
            const addrContent = document.getElementById('od_address_content');
            const sa = data.shipping_address || {};

            if (isPhysical && (sa.address || sa.city || sa.province || sa.district)) {
                let lines = [];
                if (sa.name) lines.push(`<strong>Penerima:</strong> ${sa.name} (${sa.phone || ''})`);
                if (sa.address) lines.push(sa.address);
                let locParts = [sa.district, sa.city, sa.province, sa.postal_code].filter(Boolean);
                if (locParts.length) lines.push(locParts.join(', '));
                if (sa.notes) lines.push(`<em>Catatan: ${sa.notes}</em>`);

                addrContent.innerHTML = lines.join('<br>');
                addrBox.style.display = 'block';
            } else {
                addrBox.style.display = 'none';
            }

            // Toggle status & shipping form for physical products only (digital/service/ticket auto-completed)
            const odForm = document.getElementById('od_form');
            if (isPhysical) {
                if (odForm) odForm.style.display = 'block';
            } else {
                if (odForm) odForm.style.display = 'none';
            }

            // Render Ordered Items
            const itemsList = document.getElementById('od_items_list');
            let itemsHtml = '';
            (data.items || []).forEach(item => {
                let wlBadges = '';
                if (item.seller_name) {
                    wlBadges += `<span style="background:#F1F5F9;color:#475569;padding:2px 7px;border-radius:4px;font-size:0.7rem;font-weight:600;display:inline-flex;align-items:center;gap:3px;margin-top:4px;">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Pemilik Produk: ${item.seller_name}</span>`;
                }
                if (item.reseller_name) {
                    wlBadges += `<span style="background:#EFF6FF;color:#2563EB;padding:2px 7px;border-radius:4px;font-size:0.7rem;font-weight:600;display:inline-flex;align-items:center;gap:3px;margin-top:4px;margin-left:4px;">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Reseller Whitelabel: ${item.reseller_name}</span>`;
                }
                let wlBreakdown = '';
                if (item.reseller_margin > 0) {
                    wlBreakdown = `<div style="margin-top:5px;background:#F0FDF4;border:1px dashed #86EFAC;color:#166534;padding:4px 8px;border-radius:6px;font-size:0.7rem;line-height:1.8;">
                        <div style="display:flex;align-items:center;gap:3px;"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> Harga Dasar Whitelabel: Rp ${Number(item.base_price).toLocaleString('id-ID')}</div>
                        <div style="display:flex;align-items:center;gap:3px;"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg> Margin Reseller Whitelabel: <strong>+ Rp ${Number(item.reseller_margin).toLocaleString('id-ID')}</strong></div>
                        <div style="display:flex;align-items:center;gap:3px;"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Hak Pemilik Produk: <strong>Rp ${Number(item.creator_earnings).toLocaleString('id-ID')}</strong></div>
                    </div>`;
                }
                itemsHtml += `
                <div style="padding:0.6rem 0.85rem; border-bottom:1px solid #f1f5f9; font-size:0.82rem;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <div>
                            <strong style="color:#0f172a;">${item.name}</strong>
                            <div style="font-size:0.72rem; color:#64748b;">Rp ${Number(item.price).toLocaleString('id-ID')} x ${item.quantity}</div>
                            <div style="display:flex;flex-wrap:wrap;gap:3px;">${wlBadges}</div>
                            ${wlBreakdown}
                        </div>
                        <div style="font-weight:700; color:#0f172a; white-space:nowrap; margin-left:1rem;">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</div>
                    </div>
                </div>
            `;
            });
            itemsList.innerHTML = itemsHtml;
            document.getElementById('od_total_price').innerText = 'Rp ' + Number(data.total).toLocaleString('id-ID');

            // Set Form values
            document.getElementById('od_order_id').value = data.id;
            document.getElementById('od_update_url').value = data.update_url;
            document.getElementById('od_input_status').value = data.status || 'processing';
            document.getElementById('od_input_courier').value = data.courier_name || '';
            document.getElementById('od_input_resi').value = data.tracking_number || '';

            document.getElementById('orderDetailModal').classList.add('show');
        }

        function closeOrderModal() {
            document.getElementById('orderDetailModal').classList.remove('show');
        }

        function saveOrderData(e) {
            e.preventDefault();
            const btn = document.getElementById('od_btn_save');
            const orderId = document.getElementById('od_order_id').value;
            const url = document.getElementById('od_update_url').value;
            const statusVal = document.getElementById('od_input_status').value;
            const courierVal = document.getElementById('od_input_courier').value;
            const resiVal = document.getElementById('od_input_resi').value;

            btn.disabled = true;
            btn.innerText = 'Menyimpan...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_status: statusVal,
                    courier_name: courierVal,
                    tracking_number: resiVal
                })
            })
                .then(res => res.json())
                .then(res => {
                    btn.disabled = false;
                    btn.innerText = 'Simpan Perubahan';
                    if (res.success) {
                        // Update table row badges live
                        const badge = document.getElementById('badge-status-' + orderId);
                        const resiText = document.getElementById('resi-text-' + orderId);

                        if (badge) {
                            badge.innerText = res.status_label;
                            const bgStyles = {
                                'completed': 'background:#dcfce7;color:#166534;border:1px solid #bbf7d0;',
                                'shipped': 'background:#e0e7ff;color:#3730a3;border:1px solid #c7d2fe;',
                                'processing': 'background:#fef3c7;color:#92400e;border:1px solid #fde68a;',
                                'cancelled': 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                            };
                            badge.style.cssText = (bgStyles[res.status] || 'background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;') + ' font-weight:700; padding:3px 8px; border-radius:6px; font-size:0.72rem;';
                        }

                        if (resiText) {
                            if (res.tracking_number) {
                                resiText.innerHTML = `<i class="fas fa-truck"></i> ${res.tracking_number}`;
                                resiText.style.color = '#2563eb';
                                resiText.style.fontWeight = '700';
                            } else {
                                resiText.innerText = 'Belum ada resi';
                                resiText.style.color = '#94a3b8';
                            }
                        }

                        closeOrderModal();
                        alert(res.message);
                    } else {
                        alert('Gagal memperbarui: ' + (res.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerText = 'Simpan Perubahan';
                    alert('Terjadi kesalahan jaringan!');
                });
        }

        function printReceipt(data) {
            if (!data) return;

            document.getElementById('pr_seller_name').innerText = data.seller_name || 'Kreator buyle.id';
            document.getElementById('pr_order_number').innerText = '#' + (data.order_number || ('BYL-' + data.id));
            document.getElementById('pr_order_date').innerText = data.date || '';

            document.getElementById('pr_customer_name').innerText = data.user_name || 'Pembeli';

            const phoneRow = document.getElementById('pr_phone_row');
            if (data.phone) {
                document.getElementById('pr_customer_phone').innerText = data.phone;
                phoneRow.style.display = 'flex';
            } else {
                phoneRow.style.display = 'none';
            }

            const emailRow = document.getElementById('pr_email_row');
            if (data.email) {
                document.getElementById('pr_customer_email').innerText = data.email;
                emailRow.style.display = 'flex';
            } else {
                emailRow.style.display = 'none';
            }

            // Fulfillment & Address Details
            const fulBox = document.getElementById('pr_fulfillment_box');
            const fulTitle = document.getElementById('pr_fulfillment_title');
            const fulDetail = document.getElementById('pr_fulfillment_detail');
            const sa = data.shipping_address || {};

            if (sa.fnb_service_type || sa.table_number || sa.pickup_time || sa.delivery_address || sa.address) {
                fulBox.style.display = 'block';
                if (sa.fnb_service_type === 'dine_in' || sa.table_number) {
                    fulTitle.innerHTML = '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:-1px;margin-right:4px;"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg> Layanan FnB / Resto: DINE-IN (Makan di Tempat)';
                    fulDetail.innerHTML = `<strong>Nomor Meja:</strong> Meja #${sa.table_number || '-'} ${sa.notes ? '<br><em>Catatan: ' + sa.notes + '</em>' : ''}`;
                } else if (sa.fnb_service_type === 'takeaway' || sa.pickup_time) {
                    fulTitle.innerHTML = '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:-1px;margin-right:4px;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg> Layanan FnB / Resto: TAKEAWAY (Ambil Sendiri)';
                    fulDetail.innerHTML = `<strong>Waktu Pengambilan:</strong> ${sa.pickup_time || '-'} ${sa.notes ? '<br><em>Catatan: ' + sa.notes + '</em>' : ''}`;
                } else if (sa.fnb_service_type === 'delivery') {
                    fulTitle.innerHTML = '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:-1px;margin-right:4px;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg> Layanan FnB / Resto: DELIVERY (Antar ke Rumah)';
                    fulDetail.innerHTML = `<strong>Alamat Pengantaran:</strong> ${sa.delivery_address || sa.address || '-'} ${sa.notes ? '<br><em>Catatan: ' + sa.notes + '</em>' : ''}`;
                } else {
                    fulTitle.innerHTML = '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:-1px;margin-right:4px;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg> Pengiriman:';
                    let addrStr = [sa.name ? 'Penerima: ' + sa.name : null, sa.address, sa.district, sa.city, sa.province, sa.postal_code].filter(Boolean).join(', ');
                    if (data.courier_name || data.tracking_number) {
                        addrStr += `<br><strong>Kurir:</strong> ${data.courier_name || '-'} (Resi: ${data.tracking_number || '-'})`;
                    }
                    fulDetail.innerHTML = addrStr;
                }
            } else {
                fulBox.style.display = 'block';
                fulTitle.innerHTML = '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:-1px;margin-right:4px;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Akses / Produk Digital:';
                fulDetail.innerText = 'Produk digital / tiket dikirimkan otomatis secara sistem.';
            }

            // Items list
            const itemsBody = document.getElementById('pr_items_body');
            let itemsHtml = '';
            (data.items || []).forEach(item => {
                itemsHtml += `
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:8px 0; color:#0f172a; font-weight:600;">${item.name}</td>
                    <td style="padding:8px 0; text-align:center; color:#475569;">${item.quantity}</td>
                    <td style="padding:8px 0; text-align:right; color:#64748b;">Rp ${Number(item.price).toLocaleString('id-ID')}</td>
                    <td style="padding:8px 0; text-align:right; color:#0f172a; font-weight:700;">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                </tr>
            `;
            });
            itemsBody.innerHTML = itemsHtml;

            // Financials
            document.getElementById('pr_subtotal').innerText = 'Rp ' + Number(data.subtotal || 0).toLocaleString('id-ID');

            const shipRow = document.getElementById('pr_shipping_row');
            if (data.shipping_cost > 0) {
                document.getElementById('pr_shipping_cost').innerText = 'Rp ' + Number(data.shipping_cost).toLocaleString('id-ID');
                shipRow.style.display = 'flex';
            } else {
                shipRow.style.display = 'none';
            }

            const adminRow = document.getElementById('pr_admin_fee_row');
            const feeSum = (data.platform_fee || 0) + (data.admin_fee || 0);
            if (feeSum > 0) {
                document.getElementById('pr_admin_fee').innerText = 'Rp ' + Number(feeSum).toLocaleString('id-ID');
                adminRow.style.display = 'flex';
            } else {
                adminRow.style.display = 'none';
            }

            const discRow = document.getElementById('pr_discount_row');
            if (data.discount > 0) {
                document.getElementById('pr_discount').innerText = '- Rp ' + Number(data.discount).toLocaleString('id-ID');
                discRow.style.display = 'flex';
            } else {
                discRow.style.display = 'none';
            }

            document.getElementById('pr_grand_total').innerText = 'Rp ' + Number(data.grand_total || data.total || 0).toLocaleString('id-ID');

            // Status badge
            const statusBadge = document.getElementById('pr_status_badge');
            const statusVal = data.status || 'processing';
            statusBadge.innerText = (data.status_label || statusVal).toUpperCase();
            if (statusVal === 'completed') {
                statusBadge.style.cssText = 'display:inline-block; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:800; background:#dcfce7; color:#166534; border:1px solid #bbf7d0;';
            } else if (statusVal === 'shipped' || statusVal === 'processing') {
                statusBadge.style.cssText = 'display:inline-block; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:800; background:#e0e7ff; color:#3730a3; border:1px solid #c7d2fe;';
            } else {
                statusBadge.style.cssText = 'display:inline-block; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:800; background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;';
            }

            // Trigger Print
            window.print();
        }
    </script>

    <!-- Modal Order Detail & Manajemen Pesanan -->
    <div class="date-modal-overlay" id="orderDetailModal">
        <div class="date-modal" style="width:580px; max-width:95vw; border-radius:24px; padding:1.75rem;">
            <div
                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; border-bottom:1px solid #f1f5f9; padding-bottom:0.85rem;">
                <div>
                    <h4 style="font-size:1.1rem; font-weight:800; color:#0f172a; margin:0;" id="od_title">Detail Pesanan
                    </h4>
                    <div style="font-size:0.75rem; color:#64748b; margin-top:2px;" id="od_date"></div>
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <button type="button" onclick="printReceipt(currentOrderData)"
                        style="background:#1eb349; color:#fff; border:none; padding:0.4rem 0.75rem; border-radius:8px; font-size:0.78rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;"
                        title="Cetak / Download e-Receipt">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Cetak e-Receipt
                    </button>
                    <button type="button" class="btn-cancel" onclick="closeOrderModal()"
                        style="padding:0.35rem 0.75rem; border-radius:8px;">✕</button>
                </div>
            </div>

            <div style="max-height:75vh; overflow-y:auto; padding-right:4px;">
                <!-- Customer Info & WA Chat Button -->
                <div
                    style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:1rem; margin-bottom:1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <div>
                            <div
                                style="font-size:0.72rem; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">
                                Informasi Pembeli</div>
                            <div style="font-size:0.95rem; font-weight:800; color:#0f172a; margin-top:2px;"
                                id="od_user_name"></div>
                            <div style="font-size:0.8rem; color:#475569; margin-top:1px;" id="od_user_contact"></div>
                        </div>
                        <a id="od_wa_btn" href="#" target="_blank"
                            style="display:inline-flex; align-items:center; gap:6px; background:#25d366; color:#fff; padding:0.45rem 0.85rem; border-radius:10px; text-decoration:none; font-size:0.78rem; font-weight:700; box-shadow:0 2px 6px rgba(37,211,102,0.3);">
                            <i class="fab fa-whatsapp" style="font-size:14px;"></i> Hubungi WA
                        </a>
                    </div>
                </div>

                <!-- Shipping Address (if available) -->
                <div id="od_address_box"
                    style="display:none; background:#fff; border:1px dashed #cbd5e1; border-radius:14px; padding:1rem; margin-bottom:1rem;">
                    <div
                        style="font-size:0.72rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.4rem; display:flex; align-items:center; gap:4px;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Alamat Pengiriman
                    </div>
                    <div id="od_address_content" style="font-size:0.82rem; color:#1e293b; line-height:1.5;"></div>
                </div>

                <!-- Products List -->
                <div style="margin-bottom:1.25rem;">
                    <div
                        style="font-size:0.72rem; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.5rem;">
                        Produk Dipesan</div>
                    <div id="od_items_list" style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;"></div>
                    <div
                        style="display:flex; justify-content:space-between; align-items:center; padding:0.75rem 1rem; background:#f8fafc; border-top:1px solid #e2e8f0; font-weight:800; font-size:0.9rem; color:#0f172a;">
                        <span>Total Pembayaran</span>
                        <span id="od_total_price" style="color:#1eb349;"></span>
                    </div>
                </div>

                <!-- Form Edit Status & Resi -->
                <form id="od_form" onsubmit="saveOrderData(event)">
                    <input type="hidden" id="od_order_id">
                    <input type="hidden" id="od_update_url">

                    <div
                        style="background:#fff; border:1.5px solid #e2e8f0; border-radius:16px; padding:1.1rem; box-shadow:0 4px 14px rgba(0,0,0,0.03);">
                        <div id="od_management_title"
                            style="font-size:0.85rem; font-weight:800; color:#0f172a; margin-bottom:0.85rem; display:flex; align-items:center; gap:6px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Manajemen Status & Pengiriman
                        </div>

                        <div id="od_status_grid" style="display:grid; grid-template-columns:1fr 1fr; gap:0.85rem; margin-bottom:0.85rem;">
                            <div>
                                <label
                                    style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:0.3rem;">Status
                                    Pesanan</label>
                                <select id="od_input_status"
                                    style="width:100%; padding:0.55rem 0.75rem; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-weight:600; outline:none; font-family:'Montserrat',sans-serif;">
                                    <option value="pending">Menunggu Pembayaran</option>
                                    <option value="processing">Diproses</option>
                                    <option value="shipped">Dikirim (Dalam Pengiriman)</option>
                                    <option value="completed">Selesai</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>
                            </div>
                            <div id="od_shipping_fields_wrap">
                                <label
                                    style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:0.3rem;">Ekspedisi
                                    / Kurir</label>
                                <input type="text" id="od_input_courier" placeholder="Misal: J&T, JNE, SiCepat, Express"
                                    style="width:100%; padding:0.55rem 0.75rem; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; outline:none; font-family:'Montserrat',sans-serif; margin-bottom:0.85rem;">
                                <label
                                    style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:0.3rem;">Nomor
                                    Resi Pengiriman (AWB)</label>
                                <input type="text" id="od_input_resi" placeholder="Masukkan No Resi... (contoh: JY1241520391)"
                                    style="width:100%; padding:0.6rem 0.8rem; border:1.5px solid #94a3b8; border-radius:10px; font-size:0.85rem; font-weight:700; color:#0f172a; outline:none; font-family:monospace;">
                            </div>
                        </div>

                        <button type="submit" id="od_btn_save"
                            style="width:100%; padding:0.75rem; background:linear-gradient(135deg,#0f172a,#1e293b); color:#fff; border:none; border-radius:12px; font-size:0.85rem; font-weight:800; cursor:pointer; font-family:'Montserrat',sans-serif; transition:all 0.2s; box-shadow:0 4px 12px rgba(15,23,42,0.2); display:flex; align-items:center; justify-content:center; gap:6px;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
        $logoSetting = \App\Models\Setting::get('logo');
        $siteLogoUrl = $logoSetting ? Storage::url($logoSetting) : null;
    @endphp

    <!-- Printable Receipt Template Container (Clean White Dominant Header & Footer buyle.id) -->
    <div id="printableReceiptArea"
        style="background:#ffffff; color:#0f172a; border:1px solid #e2e8f0; border-radius:16px; padding:24px; font-family:'Montserrat', sans-serif;">
        <!-- Receipt Header with buyle.id Logo -->
        <div style="text-align:center; padding-bottom:16px; border-bottom:2px dashed #e2e8f0; margin-bottom:16px;">
            @if($siteLogoUrl)
                <img src="{{ asset($siteLogoUrl) }}" alt="buyle.id"
                    style="max-height:48px; width:auto; margin-bottom:6px; display:inline-block;">
            @else
                <div style="font-weight:900; font-size:1.6rem; color:#0f172a; letter-spacing:-0.5px; margin-bottom:4px;">
                    buyle<span style="color:#1eb349;">.id</span>
                </div>
            @endif
            <div style="font-size:0.75rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:1px;"
                id="pr_seller_name">
                {{ auth()->user()->name ?? 'Kreator buyle.id' }}
            </div>
            <div style="font-size:1.1rem; font-weight:800; color:#0f172a; margin-top:8px;">E-RECEIPT PENJUALAN</div>
            <div style="font-size:0.8rem; font-weight:700; color:#1eb349; margin-top:2px;" id="pr_order_number">#BYL-0000
            </div>
            <div style="font-size:0.72rem; color:#94a3b8; margin-top:2px;" id="pr_order_date">15 Sep 2026 00:00</div>
        </div>

        <!-- Order Metadata / Buyer Info -->
        <div
            style="margin-bottom:16px; font-size:0.8rem; line-height:1.6; border-bottom:1px solid #f1f5f9; padding-bottom:14px;">
            <div style="display:flex; justify-content:space-between;">
                <span style="color:#64748b;">Pembeli:</span>
                <strong style="color:#0f172a;" id="pr_customer_name">-</strong>
            </div>
            <div style="display:flex; justify-content:space-between;" id="pr_phone_row">
                <span style="color:#64748b;">No. HP / WA:</span>
                <span style="color:#0f172a; font-weight:600;" id="pr_customer_phone">-</span>
            </div>
            <div style="display:flex; justify-content:space-between;" id="pr_email_row">
                <span style="color:#64748b;">Email:</span>
                <span style="color:#0f172a;" id="pr_customer_email">-</span>
            </div>
            <div style="margin-top:8px; background:#f8fafc; padding:8px 10px; border-radius:8px; border:1px solid #e2e8f0; font-size:0.75rem;"
                id="pr_fulfillment_box">
                <div style="font-weight:700; color:#475569; margin-bottom:2px;" id="pr_fulfillment_title">Tipe Layanan /
                    Pengiriman:</div>
                <div style="color:#0f172a;" id="pr_fulfillment_detail">-</div>
            </div>
        </div>

        <!-- Itemized List Table -->
        <div style="margin-bottom:16px;">
            <table style="width:100%; border-collapse:collapse; font-size:0.8rem;">
                <thead>
                    <tr style="border-bottom:1.5px solid #0f172a; text-align:left; color:#475569;">
                        <th style="padding:6px 0; font-weight:700;">Item / Produk</th>
                        <th style="padding:6px 0; text-align:center; font-weight:700;">Qty</th>
                        <th style="padding:6px 0; text-align:right; font-weight:700;">Harga</th>
                        <th style="padding:6px 0; text-align:right; font-weight:700;">Total</th>
                    </tr>
                </thead>
                <tbody id="pr_items_body">
                    <!-- Dynamically populated -->
                </tbody>
            </table>
        </div>

        <!-- Financial Summary Breakdown -->
        <div
            style="border-top:1.5px solid #0f172a; padding-top:12px; margin-bottom:20px; font-size:0.82rem; line-height:1.8;">
            <div style="display:flex; justify-content:space-between;">
                <span style="color:#64748b;">Subtotal Produk:</span>
                <span style="font-weight:600; color:#0f172a;" id="pr_subtotal">Rp 0</span>
            </div>
            <div style="display:flex; justify-content:space-between;" id="pr_shipping_row">
                <span style="color:#64748b;">Ongkos Kirim:</span>
                <span style="font-weight:600; color:#0f172a;" id="pr_shipping_cost">Rp 0</span>
            </div>
            <div style="display:flex; justify-content:space-between;" id="pr_admin_fee_row">
                <span style="color:#64748b;">Biaya Layanan / Penanganan:</span>
                <span style="font-weight:600; color:#0f172a;" id="pr_admin_fee">Rp 0</span>
            </div>
            <div style="display:flex; justify-content:space-between; color:#dc2626;" id="pr_discount_row">
                <span>Diskon:</span>
                <span style="font-weight:600;" id="pr_discount">- Rp 0</span>
            </div>
            <div
                style="display:flex; justify-content:space-between; border-top:2px solid #0f172a; padding-top:8px; margin-top:6px; font-size:1rem; font-weight:800; color:#0f172a;">
                <span>TOTAL BAYAR:</span>
                <span style="color:#1eb349;" id="pr_grand_total">Rp 0</span>
            </div>
            <div style="margin-top:10px; text-align:center;">
                <span id="pr_status_badge"
                    style="display:inline-block; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:800; background:#dcfce7; color:#166534; border:1px solid #bbf7d0;">
                    LUNAS
                </span>
            </div>
        </div>

        <!-- Footer buyle.id Branding -->
        <div
            style="text-align:center; border-top:2px dashed #e2e8f0; padding-top:16px; font-size:0.72rem; color:#64748b; line-height:1.5;">
            <div style="font-weight:700; color:#0f172a; margin-bottom:2px;">Terima kasih atas pesanan Anda!</div>
            <div>Bukti transaksi resmi yang diterbitkan via <strong style="color:#1eb349;">buyle.id</strong></div>
            <div style="font-size:0.68rem; color:#94a3b8; margin-top:4px;">https://buyle.id • Digital Creator Center</div>
        </div>
    </div>

    {{-- TIER PREVIEW MODAL --}}
    <div id="tierModalOverlay" class="tier-modal-overlay" onclick="closeTierModal(event)">
        <div class="tier-modal-card" onclick="event.stopPropagation()">
            <div class="tier-modal-head">
                <h3 class="tier-modal-title">🏆 6 Tingkat Kasta Achievement Card</h3>
                <button type="button" class="tier-modal-close" onclick="closeTierModal()">&times;</button>
            </div>
            <p style="font-size:0.8rem; color:#64748b; margin-bottom:1.25rem; line-height:1.5;">
                Tingkatkan omzet penjualan buyle.id Anda untuk membuka tampilan kartu digital bernilai tinggi dengan finishing material eksklusif ala luxury priority banking.
            </p>

            <div class="tier-grid-preview">
                {{-- Tier 1 --}}
                <div class="tier-item-box" style="background: linear-gradient(135deg, #f1f5f9, #cbd5e1); color:#0f172a; border-color:#cbd5e1;">
                    <div class="tier-item-name">1. PERINTIS (Rp 1 Juta+)</div>
                    <div class="tier-item-target">Matte Silver / Brushed Steel Card</div>
                    <div class="tier-item-desc">Tampilan bersih industrial steel, menandakan langkah awal fondasi bisnis yang kokoh.</div>
                </div>

                {{-- Tier 2 --}}
                <div class="tier-item-box" style="background: linear-gradient(135deg, #334155, #0f172a); color:#ffffff; border-color:#475569;">
                    <div class="tier-item-name">2. PEJUANG / HUSTLER (Rp 10 Juta+)</div>
                    <div class="tier-item-target">Titanium Gray Metallic Card</div>
                    <div class="tier-item-desc">Titanium gray dengan garis metalik, menyimbolkan tempaan awal bisnis yang berputar kencang.</div>
                </div>

                {{-- Tier 3 --}}
                <div class="tier-item-box" style="background: linear-gradient(135deg, #f5d0c5, #d49b85); color:#3b1609; border-color:#e6b8a2;">
                    <div class="tier-item-name">3. PENGUSAHA MUDA (Rp 50 Juta+)</div>
                    <div class="tier-item-target">Rose Gold / Champagne Gold Card</div>
                    <div class="tier-item-desc">Estetika hangat champagne gold minimalist yang elegan dan berkelas.</div>
                </div>

                {{-- Tier 4 --}}
                <div class="tier-item-box" style="background: linear-gradient(135deg, #064e3b, #022c22); color:#ffffff; border-color:#047857;">
                    <div class="tier-item-name">4. EKSEKUTIF MUDA (Rp 100 Juta+)</div>
                    <div class="tier-item-target">Deep Emerald Platinum Card</div>
                    <div class="tier-item-desc">Emerald green & platinum dengan chip hologram, menggambarkan pertumbuhan korporat matang.</div>
                </div>

                {{-- Tier 5 --}}
                <div class="tier-item-box" style="background: linear-gradient(135deg, #111827, #030712); color:#f8fafc; border-color:#334155;">
                    <div class="tier-item-name">5. EKSEKUTIF SENIOR (Rp 200 Juta+)</div>
                    <div class="tier-item-target">Obsidian Matte Black Silver Engraved</div>
                    <div class="tier-item-desc">Warna gelap obsidian misterius ala kartu prioritas perbankan papan atas dengan ukiran perak.</div>
                </div>

                {{-- Tier 6 --}}
                <div class="tier-item-box" style="background:#080808; color:#ffffff; border-color:#3f3f46;">
                    <div class="tier-item-name">6. FINANCIAL FREEDOM (Rp 500 Juta+)</div>
                    <div class="tier-item-target">Solid Carbon Fiber Ultra-Card</div>
                    <div class="tier-item-desc">Pure Carbon Fiber & laser platinum matte, memancarkan prestise mutlak ala Amex Centurion.</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTierModal() {
            const m = document.getElementById('tierModalOverlay');
            if (m) m.classList.add('show');
        }
        function closeTierModal(e) {
            if (!e || e.target.id === 'tierModalOverlay' || e.target.classList.contains('tier-modal-close')) {
                const m = document.getElementById('tierModalOverlay');
                if (m) m.classList.remove('show');
            }
        }

        // ── Smooth Eye Toggle for Sales Nominal ──
        function toggleSalesVisibility() {
            const amtEl = document.getElementById('lcAmountText');
            const eyeOpen = document.getElementById('eyeIconOpen');
            const eyeClosed = document.getElementById('eyeIconClosed');
            if (!amtEl) return;

            const isHidden = amtEl.classList.contains('hidden-mask');
            const fullAmount = amtEl.getAttribute('data-amount');

            amtEl.style.opacity = '0';
            amtEl.style.transform = 'translateY(-2px)';

            setTimeout(function() {
                if (isHidden) {
                    amtEl.innerText = fullAmount;
                    amtEl.classList.remove('hidden-mask');
                    if (eyeOpen) eyeOpen.style.display = 'block';
                    if (eyeClosed) eyeClosed.style.display = 'none';
                    localStorage.setItem('buyle_sales_hidden', 'false');
                } else {
                    amtEl.innerText = 'Rp ••••••••';
                    amtEl.classList.add('hidden-mask');
                    if (eyeOpen) eyeOpen.style.display = 'none';
                    if (eyeClosed) eyeClosed.style.display = 'block';
                    localStorage.setItem('buyle_sales_hidden', 'true');
                }
                amtEl.style.opacity = '1';
                amtEl.style.transform = 'translateY(0)';
            }, 150);
        }

        // ── Safe & Non-blocking Anti-Tamper Text Guard ──
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('buyle_sales_hidden') === 'true') {
                const amtEl = document.getElementById('lcAmountText');
                const eyeOpen = document.getElementById('eyeIconOpen');
                const eyeClosed = document.getElementById('eyeIconClosed');
                if (amtEl) {
                    amtEl.innerText = 'Rp ••••••••';
                    amtEl.classList.add('hidden-mask');
                    if (eyeOpen) eyeOpen.style.display = 'none';
                    if (eyeClosed) eyeClosed.style.display = 'block';
                }
            }

            // Verify and restore text if modified via Inspect Element
            setInterval(function() {
                const amtEl = document.getElementById('lcAmountText');
                if (!amtEl) return;
                const isHidden = amtEl.classList.contains('hidden-mask');
                const validAmount = amtEl.getAttribute('data-amount');
                const expected = isHidden ? 'Rp ••••••••' : validAmount;

                if (amtEl.innerText.trim() !== expected) {
                    amtEl.innerText = expected;
                }
            }, 1000);
        });
    </script>
@endsection