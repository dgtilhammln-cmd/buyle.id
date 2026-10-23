@extends('layouts.app')

@section('content')

    <style>
        /* ══════════════════════════════════════════════
               BUYLE TICKETING PLATFORM & VENUE SYSTEM
               ══════════════════════════════════════════════ */
        :root {
            --tk-accent: #1eb349;
            --tk-accent-light: #a5cf37;
            --tk-dark: #0F172A;
            --tk-muted: #64748B;
            --tk-border: #E2E8F0;
            --font: 'Montserrat', sans-serif;
        }

        .buyle-ticketing-section {
            padding: 8rem 1.5rem 5.5rem;
            background: #FFFFFF;
            position: relative;
            overflow: hidden;
            font-family: var(--font);
        }

        /* Subtle Ambient Glow */
        .buyle-ticketing-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 1000px;
            height: 520px;
            background: radial-gradient(ellipse at center, rgba(30, 179, 73, 0.06) 0%, rgba(165, 207, 55, 0.03) 40%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .buyle-ticketing-container {
            max-width: 1220px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* ── Breadcrumb (Persis Style Tentang Kami) ── */
        .sv-breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748B;
            margin-bottom: 2.25rem;
            font-family: var(--font);
        }

        .sv-breadcrumb a {
            color: #64748B;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .sv-breadcrumb a:hover {
            color: #1eb349;
        }

        .sv-breadcrumb-sep {
            font-size: 0.6rem;
            color: #94A3B8;
            opacity: 0.6;
        }

        .sv-breadcrumb-current {
            color: #0F172A;
            font-weight: 700;
        }

        /* ── Header Intro ── */
        .ticket-header {
            text-align: center;
            max-width: 860px;
            margin: 0 auto 3.75rem;
        }

        .ticket-title {
            font-size: clamp(2.2rem, 4.5vw, 3.4rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.18;
            letter-spacing: -0.03em;
            margin: 0 0 1.25rem;
        }

        .ticket-title .accent-dot {
            color: #1eb349;
        }

        .ticket-desc {
            font-size: 1.05rem;
            color: #64748B;
            line-height: 1.7;
            margin: 0 auto 2.25rem;
            max-width: 740px;
            font-weight: 400;
        }

        /* ── Action Buttons ── */
        .ticket-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .ticket-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #FFFFFF;
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            padding: 0.85rem 1.75rem;
            border-radius: 999px;
            box-shadow: 0 10px 25px rgba(30, 179, 73, 0.28);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .ticket-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(30, 179, 73, 0.4);
            color: #FFFFFF;
        }

        .ticket-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            background: #F8FAFC;
            color: #334155;
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            padding: 0.85rem 1.75rem;
            border-radius: 999px;
            border: 1.5px solid #E2E8F0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .ticket-btn-secondary:hover {
            background: #FFFFFF;
            border-color: #CBD5E1;
            color: #0F172A;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        /* ── Grid 6 Feature Cards (Style Sesuai Referensi Infographic) ── */
        .ticket-features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem 2.25rem;
            margin-top: 4.5rem;
        }

        .ticket-card-outer {
            position: relative;
            padding: 6px;
        }

        /* Corner L-Shaped Ribbon Frame */
        .ticket-corner-svg {
            position: absolute;
            width: 100px;
            height: 100px;
            pointer-events: none;
            z-index: 1;
            transition: transform 0.35s ease;
        }

        .corner-top-left {
            top: 0;
            left: 0;
        }

        .corner-top-right {
            top: 0;
            right: 0;
        }

        .corner-bottom-left {
            bottom: 0;
            left: 0;
        }

        .corner-bottom-right {
            bottom: 0;
            right: 0;
        }

        .ticket-card-outer:hover .ticket-corner-svg {
            transform: scale(1.04);
        }

        /* Card Inner Box */
        .ticket-card-inner {
            background: #FFFFFF;
            border-radius: 22px;
            box-shadow: 0 10px 32px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.03);
            border: 1.5px solid rgba(226, 232, 240, 0.85);
            padding: 2.25rem 2rem;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 1.75rem;
            min-height: 200px;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .ticket-card-outer:hover .ticket-card-inner {
            transform: translateY(-4px);
            box-shadow: 0 20px 45px rgba(30, 179, 73, 0.12), 0 2px 8px rgba(0, 0, 0, 0.04);
            border-color: rgba(30, 179, 73, 0.35);
        }

        /* Giant Outlined Number */
        .ticket-num-col {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 95px;
        }

        .ticket-big-num {
            font-size: clamp(5.2rem, 6.5vw, 6.8rem);
            font-weight: 800;
            line-height: 0.82;
            color: #FFFFFF;
            -webkit-text-stroke: 2px #1eb349;
            text-shadow: 0 0 1px #1eb349;
            font-family: var(--font);
            user-select: none;
            letter-spacing: -0.04em;
            transition: transform 0.35s ease, -webkit-text-stroke-color 0.35s ease;
        }

        .ticket-card-outer:hover .ticket-big-num {
            -webkit-text-stroke-color: #16a34a;
            transform: scale(1.08);
        }

        .ticket-body-col {
            flex: 1;
            min-width: 0;
        }

        .ticket-module-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 0.55rem;
            letter-spacing: -0.015em;
            line-height: 1.35;
        }

        .ticket-module-desc {
            font-size: 0.9rem;
            color: #64748B;
            line-height: 1.6;
            margin: 0;
            font-weight: 400;
        }

        /* Circular Icon Badge with Gradient Sesuai Button Daftar */
        .ticket-badge-col {
            display: flex;
            align-items: flex-start;
            flex-shrink: 0;
            align-self: flex-start;
        }

        .ticket-circle-badge {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(30, 179, 73, 0.35);
            flex-shrink: 0;
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        .ticket-card-outer:hover .ticket-circle-badge {
            transform: scale(1.12);
            box-shadow: 0 10px 24px rgba(30, 179, 73, 0.45);
        }

        .card-layout-even {
            justify-content: space-between;
        }

        /* ── Highlights Bar ── */
        .ticket-highlights-bar {
            margin-top: 4.5rem;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            align-items: center;
        }

        .ticket-highlight-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .ticket-highlight-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            color: #1eb349;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .ticket-highlight-content h4 {
            font-size: 0.9rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 0.15rem;
        }

        .ticket-highlight-content p {
            font-size: 0.78rem;
            color: #64748B;
            margin: 0;
            line-height: 1.35;
        }

        /* ══════════════════════════════════════════════
               SIMULASI PERHITUNGAN BIAYA TIKET (CALCULATOR)
               ══════════════════════════════════════════════ */
        .ticket-calc-wrap {
            margin-top: 5.5rem;
            background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
            border: 1.5px solid #E2E8F0;
            border-radius: 28px;
            padding: 3rem 2.5rem;
            box-shadow: 0 16px 45px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
        }

        .ticket-calc-wrap::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle at top right, rgba(30, 179, 73, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .ticket-calc-header {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 2.5rem;
        }

        .ticket-calc-title {
            font-size: clamp(1.8rem, 3.2vw, 2.4rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.25;
            margin: 0 0 0.75rem;
        }

        .ticket-calc-subtitle {
            font-size: 0.98rem;
            color: #64748B;
            margin: 0;
            line-height: 1.6;
        }

        .ticket-calc-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 2.25rem;
            align-items: stretch;
        }

        /* Left Input Card */
        .ticket-calc-input-card {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .calc-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .calc-input-box {
            position: relative;
            display: flex;
            align-items: center;
            background: #F8FAFC;
            border: 2px solid #E2E8F0;
            border-radius: 14px;
            padding: 0 1.25rem;
            transition: all 0.25s ease;
        }

        .calc-input-box:focus-within {
            border-color: #1eb349;
            background: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(30, 179, 73, 0.12);
        }

        .calc-currency-prefix {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0F172A;
            margin-right: 0.5rem;
            user-select: none;
        }

        .calc-input {
            width: 100%;
            height: 52px;
            border: none;
            background: transparent;
            font-size: 1.45rem;
            font-weight: 800;
            color: #0F172A;
            font-family: var(--font);
            outline: none;
        }

        /* Preset Pills */
        .calc-presets {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 0.85rem;
        }

        .calc-preset-btn {
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            border-radius: 999px;
            padding: 0.35rem 0.85rem;
            font-size: 0.78rem;
            font-weight: 700;
            color: #475569;
            cursor: pointer;
            font-family: var(--font);
            transition: all 0.2s ease;
        }

        .calc-preset-btn:hover {
            border-color: #1eb349;
            color: #16a34a;
            background: #F0FDF4;
        }

        .calc-preset-btn.active {
            background: #1eb349;
            color: #FFFFFF;
            border-color: #1eb349;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.3);
        }

        /* Quantity Counter */
        .calc-qty-control {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .calc-qty-btn {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            font-size: 1.3rem;
            font-weight: 700;
            color: #0F172A;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .calc-qty-btn:hover {
            background: #1eb349;
            color: #FFFFFF;
            border-color: #1eb349;
        }

        .calc-qty-input {
            width: 70px;
            height: 44px;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            text-align: center;
            font-size: 1.15rem;
            font-weight: 800;
            color: #0F172A;
            font-family: var(--font);
            outline: none;
            background: #F8FAFC;
        }

        .calc-qty-text {
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748B;
        }

        /* Fee Information Note */
        .calc-fee-info-box {
            background: #F8FAFC;
            border: 1px dashed #CBD5E1;
            border-radius: 14px;
            padding: 1rem 1.15rem;
            margin-top: 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .calc-fee-info-row {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.82rem;
            color: #475569;
        }

        .calc-fee-info-row strong {
            color: #0F172A;
        }

        /* Right Result Receipt Card — Clean Buyle Theme with Signature Gradient */
        .ticket-calc-result-card {
            background: #FFFFFF;
            color: #0F172A;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 2.25rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.05);
            overflow: hidden;
        }

        .ticket-calc-result-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        }

        .ticket-calc-result-card::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(30, 179, 73, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .calc-receipt-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #E2E8F0;
        }

        .calc-receipt-title {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: #0F172A;
        }

        .calc-receipt-tag {
            font-size: 0.72rem;
            font-weight: 700;
            background: #F0FDF4;
            color: #16a34a;
            border: 1px solid #BBF7D0;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            text-transform: uppercase;
        }

        .calc-receipt-list {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            position: relative;
            z-index: 2;
        }

        .calc-receipt-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .calc-receipt-label {
            color: #64748B;
            font-weight: 500;
        }

        .calc-receipt-val {
            font-weight: 700;
            color: #0F172A;
        }

        .calc-receipt-divider {
            height: 1px;
            background: #E2E8F0;
            margin: 0.5rem 0;
        }

        .calc-receipt-row.total-row {
            background: linear-gradient(135deg, rgba(30, 179, 73, 0.07) 0%, rgba(165, 207, 55, 0.07) 100%);
            border: 1.5px solid rgba(30, 179, 73, 0.22);
            border-radius: 14px;
            padding: 1rem 1.15rem;
            margin-top: 0.4rem;
        }

        .calc-total-label {
            font-size: 0.98rem;
            font-weight: 800;
            color: #0F172A;
        }

        .calc-total-note {
            font-size: 0.72rem;
            color: #64748B;
            margin-top: 0.15rem;
        }

        .calc-total-val {
            font-size: 1.85rem;
            font-weight: 900;
            color: #1eb349;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: var(--font);
            letter-spacing: -0.02em;
        }

        .calc-creator-takeaway {
            margin-top: 1.5rem;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 0.95rem 1.15rem;
            position: relative;
            z-index: 2;
        }

        .creator-takeaway-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .takeaway-left {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.82rem;
            color: #475569;
            font-weight: 600;
        }

        .takeaway-left svg {
            color: #1eb349;
            flex-shrink: 0;
        }

        .takeaway-val {
            font-size: 1.05rem;
            font-weight: 800;
            color: #15803d;
        }

        /* ══════════════════════════════════════════════
               SECTION BARU: MULTI-GATE SCANNER & VENUE PLAN
               (TEMA UNIFORM BUYLE — CLEAN LIGHT & GRADIENT)
               ══════════════════════════════════════════════ */
        .buyle-venue-section {
            padding: 6.5rem 1.5rem 7.5rem;
            background: #FFFFFF;
            position: relative;
            overflow: hidden;
            font-family: var(--font);
            color: #0F172A;
            border-top: 1px solid #E2E8F0;
        }

        .buyle-venue-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 1100px;
            height: 520px;
            background: radial-gradient(ellipse at center, rgba(30, 179, 73, 0.06) 0%, rgba(165, 207, 55, 0.03) 40%, transparent 70%);
            pointer-events: none;
        }

        .buyle-venue-container {
            max-width: 1220px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .venue-header {
            text-align: center;
            max-width: 820px;
            margin: 0 auto 2.75rem;
        }

        .venue-title {
            font-size: clamp(2.2rem, 4.2vw, 3.2rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.2;
            letter-spacing: -0.03em;
            margin: 0 0 1rem;
        }

        .venue-title .accent-dot {
            color: #1eb349;
        }

        .venue-desc {
            font-size: 1.05rem;
            color: #64748B;
            line-height: 1.7;
            margin: 0 auto;
            max-width: 760px;
        }

        /* Interactive Filter Tabs */
        .venue-tabs-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 2.5rem;
        }

        .venue-tab-btn {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            color: #475569;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 0.45rem 1rem;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.25s ease;
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .venue-tab-btn:hover {
            color: #1eb349;
            border-color: #1eb349;
            background: #F0FDF4;
        }

        .venue-tab-btn.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #FFFFFF;
            border-color: transparent;
            box-shadow: 0 6px 18px rgba(30, 179, 73, 0.35);
        }

        /* ── Denah Arena Map Box ── */
        .venue-map-stage-wrap {
            position: relative;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 28px;
            padding: 1.5rem;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.05);
            overflow: hidden;
        }

        .venue-map-svg {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 18px;
        }

        /* Map Animation Keys */
        @keyframes fohPulse {
            0% {
                r: 35;
                opacity: 0.8;
            }

            50% {
                r: 75;
                opacity: 0.2;
            }

            100% {
                r: 120;
                opacity: 0;
            }
        }

        @keyframes beamFlow {
            0% {
                stroke-dashoffset: 60;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        .venue-ray {
            stroke-dasharray: 6 6;
            animation: beamFlow 1.8s linear infinite;
        }

        .radar-pulse-ring {
            animation: fohPulse 3s cubic-bezier(0.2, 0.8, 0.2, 1) infinite;
        }

        /* Interactive Gate Map Hotspots */
        .gate-hotspot {
            cursor: pointer;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .gate-hotspot:hover,
        .gate-hotspot.active {
            filter: drop-shadow(0 0 12px rgba(30, 179, 73, 0.5));
        }

        /* ── Gate Status Live Inspector Card ── */
        .venue-inspector-card {
            margin-top: 2rem;
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.06);
            transition: all 0.3s ease;
        }

        .inspector-left {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .inspector-icon-badge {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 6px 18px rgba(30, 179, 73, 0.35);
        }

        .inspector-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 0.25rem;
        }

        .inspector-desc {
            font-size: 0.85rem;
            color: #64748B;
            margin: 0;
        }

        .inspector-stats-row {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .stat-block {
            text-align: right;
        }

        .stat-val {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1eb349;
            font-family: var(--font);
        }

        .stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .ticket-calc-grid {
                grid-template-columns: 1fr;
                gap: 1.75rem;
            }

            .venue-inspector-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.25rem;
            }

            .inspector-stats-row {
                width: 100%;
                justify-content: space-between;
            }

            .stat-block {
                text-align: left;
            }
        }

        @media (max-width: 768px) {
            .ticket-features-grid {
                grid-template-columns: 1fr;
                gap: 1.75rem;
            }

            .ticket-card-inner {
                padding: 1.75rem 1.5rem;
            }

            .ticket-highlights-bar {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }

            .ticket-calc-wrap {
                padding: 2rem 1.25rem;
            }

            .buyle-venue-section {
                padding: 5rem 1.25rem 5.5rem;
            }
        }

        @media (max-width: 640px) {
            .buyle-ticketing-section {
                padding: 6.5rem 1.25rem 4rem;
            }

            .ticket-title {
                font-size: 2rem;
            }

            .ticket-desc {
                font-size: 0.95rem;
            }

            .ticket-card-inner {
                padding: 1.5rem 1.25rem;
                gap: 1rem;
            }

            .ticket-num-col {
                width: 70px;
            }

            .ticket-big-num {
                font-size: 4.5rem;
            }

            .ticket-circle-badge {
                width: 38px;
                height: 38px;
            }

            .ticket-circle-badge svg {
                width: 18px;
                height: 18px;
            }

            .ticket-highlights-bar {
                grid-template-columns: 1fr;
                padding: 1.25rem;
                gap: 1rem;
            }

            .inspector-stats-row {
                flex-wrap: wrap;
                gap: 1rem;
            }
        }
    </style>

    {{-- Shared SVG Defs for Button Daftar Gradient & Filters --}}
    <svg width="0" height="0" style="position:absolute; width:0; height:0; overflow:hidden;" aria-hidden="true">
        <defs>
            <linearGradient id="btnDaftarGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#1eb349" />
                <stop offset="100%" stop-color="#a5cf37" />
            </linearGradient>

            <linearGradient id="stageGlowGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stop-color="#1eb349" stop-opacity="0.25" />
                <stop offset="100%" stop-color="#1eb349" stop-opacity="0" />
            </linearGradient>

            <linearGradient id="fohGlowGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#1eb349" />
                <stop offset="100%" stop-color="#a5cf37" />
            </linearGradient>

            <filter id="gateDropShadow" x="-20%" y="-20%" width="140%" height="140%">
                <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#0F172A" flood-opacity="0.08" />
            </filter>
        </defs>
    </svg>

    {{-- SEO Schema JSON-LD --}}
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@graph": [
            {
              "@type": "BreadcrumbList",
              "itemListElement": [
                {
                  "@type": "ListItem",
                  "position": 1,
                  "name": "Beranda",
                  "item": "{{ route_locale('home') }}"
                },
                {
                  "@type": "ListItem",
                  "position": 2,
                  "name": "Ticketing",
                  "item": "{{ route('ticketing') }}"
                }
              ]
            },
            {
              "@type": "SoftwareApplication",
              "name": "Buyle.id E-Ticketing Platform",
              "applicationCategory": "BusinessApplication",
              "operatingSystem": "All Web Browsers",
              "description": "Platform e-ticketing terlengkap di Indonesia untuk konser, webinar, seminar, dan event. Dilengkapi QR code E-Tiket PDF, sistem pembayaran aman escrow, dan scanner gate panitia.",
              "offers": {
                "@type": "Offer",
                "price": "0",
                "priceCurrency": "IDR"
              }
            }
          ]
        }
        </script>

    {{-- ════ 1. TICKETING MASTER OVERVIEW SECTION ════ --}}
    <section class="buyle-ticketing-section" id="ticketing-platform">
        <div class="buyle-ticketing-container">

            {{-- Breadcrumb (Sama dengan Tentang Kami) --}}
            <nav class="sv-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route_locale('home') }}">Beranda</a>
                <span class="sv-breadcrumb-sep">/</span>
                <span class="sv-breadcrumb-current">Ticketing</span>
            </nav>

            {{-- Header Intro (Badge Dihapus Sesuai Permintaan) --}}
            <div class="ticket-header" data-aos="fade-up">
                <h1 class="ticket-title">
                    Solusi Tiket Event &amp; Konser Digital Terlengkap<span class="accent-dot">.</span>
                </h1>
                <p class="ticket-desc">
                    Platform e-ticketing all-in-one untuk kreator, promotor, dan event organizer. Dari pembuatan tiket
                    online, pembayaran instan QRIS &amp; Virtual Account, pengiriman E-Tiket QR PDF otomatis, hingga
                    validasi check-in kru gate secara realtime.
                </p>

                {{-- Action CTA --}}
                <div class="ticket-actions">
                    <a href="{{ route('creator.onboarding') }}" class="ticket-btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z">
                            </path>
                            <line x1="13" y1="5" x2="13" y2="19" stroke-dasharray="2"></line>
                        </svg>
                        <span>Mulai Buat Tiket Event</span>
                    </a>
                    <a href="#simulasi-biaya" class="ticket-btn-secondary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span>Simulasi Biaya Tiket</span>
                    </a>
                    <a href="#venue-scanner-section" class="ticket-btn-secondary"
                        style="border-color:#1eb349; color:#16a34a;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polygon points="12 8 8 12 12 16 12 12 16 12 12 8"></polygon>
                        </svg>
                        <span>Denah Multi-Gate</span>
                    </a>
                </div>
            </div>

            {{-- 6 Feature Cards Grid (Sesuai Referensi Infographic) --}}
            <div class="ticket-features-grid">

                {{-- Card 1: Top-Left Frame | Number Left | Badge Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="50">
                    <svg class="ticket-corner-svg corner-top-left" viewBox="0 0 100 100" fill="none">
                        <path d="M 90 7 H 26 A 19 19 0 0 0 7 26 V 90" stroke="url(#btnDaftarGradient)" stroke-width="12"
                            stroke-linecap="round" />
                    </svg>
                    <div class="ticket-card-inner card-layout-odd">
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">1</span>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">Kustomisasi Event &amp; Kuota</h2>
                            <p class="ticket-module-desc">
                                Atur tiket gratis atau berbayar dengan kuota otomatis, batas waktu penjualan, form peserta
                                kustom, dan multi-tier (Early Bird, VIP, Presale, OTS).
                            </p>
                        </div>
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="Kustomisasi Event">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Top-Right Frame | Badge Left | Number Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="100">
                    <svg class="ticket-corner-svg corner-top-right" viewBox="0 0 100 100" fill="none">
                        <path d="M 10 7 H 74 A 19 19 0 0 1 93 26 V 90" stroke="url(#btnDaftarGradient)" stroke-width="12"
                            stroke-linecap="round" />
                    </svg>
                    <div class="ticket-card-inner card-layout-even">
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="E-Tiket Otomatis">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="12" y1="18" x2="12" y2="12"></line>
                                    <line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                            </div>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">E-Tiket QR PDF Otomatis</h2>
                            <p class="ticket-module-desc">
                                File E-Tiket PDF resmi ber-QR Code unik terkirim otomatis ke Email dan WhatsApp pembeli
                                tepat setelah transaksi pembayaran terverifikasi.
                            </p>
                        </div>
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">2</span>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Top-Left Frame | Number Left | Badge Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="150">
                    <svg class="ticket-corner-svg corner-top-left" viewBox="0 0 100 100" fill="none">
                        <path d="M 90 7 H 26 A 19 19 0 0 0 7 26 V 90" stroke="url(#btnDaftarGradient)" stroke-width="12"
                            stroke-linecap="round" />
                    </svg>
                    <div class="ticket-card-inner card-layout-odd">
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">3</span>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">Gate Scanner Bebas Alat Sewa</h2>
                            <p class="ticket-module-desc">
                                Panitia validasi tiket langsung lewat browser kamera smartphone tanpa perlu sewa barcode
                                scanner mahal. Respon kilat &lt;0.2 detik bebas antrean.
                            </p>
                        </div>
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="Gate Scanner">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                                    <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                                    <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                                    <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                                    <rect x="7" y="7" width="10" height="10" rx="1"></rect>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Top-Right Frame | Badge Left | Number Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="200">
                    <svg class="ticket-corner-svg corner-top-right" viewBox="0 0 100 100" fill="none">
                        <path d="M 10 7 H 74 A 19 19 0 0 1 93 26 V 90" stroke="url(#btnDaftarGradient)" stroke-width="12"
                            stroke-linecap="round" />
                    </svg>
                    <div class="ticket-card-inner card-layout-even">
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="Multi-Gate PIN">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">Multi-Gate &amp; Akses Kru PIN</h2>
                            <p class="ticket-module-desc">
                                Bagi tugas scan ke puluhan kru lapangan di berbagai pintu masuk cukup dengan otorisasi PIN
                                rahasia. Kuota check-in tersinkronisasi realtime antar gate.
                            </p>
                        </div>
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">4</span>
                        </div>
                    </div>
                </div>

                {{-- Card 5: Bottom-Left Frame | Number Left | Badge Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="250">
                    <svg class="ticket-corner-svg corner-bottom-left" viewBox="0 0 100 100" fill="none">
                        <path d="M 90 93 H 26 A 19 19 0 0 1 7 74 V 10" stroke="url(#btnDaftarGradient)" stroke-width="12"
                            stroke-linecap="round" />
                    </svg>
                    <div class="ticket-card-inner card-layout-odd">
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">5</span>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">Multi-Payment &amp; Escrow Aman</h2>
                            <p class="ticket-module-desc">
                                Mendukung pembayaran QRIS dari semua m-banking &amp; e-wallet, Virtual Account (BCA,
                                Mandiri, BRI, BNI), dan kartu debit. Seluruh perputaran dana terlindungi escrow aman.
                            </p>
                        </div>
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="Multi-Payment">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 6: Bottom-Right Frame | Badge Left | Number Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="300">
                    <svg class="ticket-corner-svg corner-bottom-right" viewBox="0 0 100 100" fill="none">
                        <path d="M 10 93 H 74 A 19 19 0 0 0 93 74 V 10" stroke="url(#btnDaftarGradient)" stroke-width="12"
                            stroke-linecap="round" />
                    </svg>
                    <div class="ticket-card-inner card-layout-even">
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="Statistik & Rekap">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">Statistik Penjualan &amp; Rekap Peserta</h2>
                            <p class="ticket-module-desc">
                                Pantau grafik penjualan tiket harian, total omzet, sisa kuota, serta unduh data rekap
                                lengkap dari dashboard.
                            </p>
                        </div>
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">6</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Highlights & Keamanan Bar --}}
            <div class="ticket-highlights-bar" data-aos="fade-up" data-aos-delay="350">
                <div class="ticket-highlight-item">
                    <div class="ticket-highlight-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <div class="ticket-highlight-content">
                        <h4>100% Anti-Curang</h4>
                        <p>Tiket hanya bisa di-scan 1 kali.</p>
                    </div>
                </div>

                <div class="ticket-highlight-item">
                    <div class="ticket-highlight-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="ticket-highlight-content">
                        <h4>Check-in &lt;0.2 Detik</h4>
                        <p>Bebas antrean panjang di pintu gate.</p>
                    </div>
                </div>

                <div class="ticket-highlight-item">
                    <div class="ticket-highlight-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="ticket-highlight-content">
                        <h4>Proteksi Escrow</h4>
                        <p>Pencairan dana langsung ke rekening.</p>
                    </div>
                </div>

                <div class="ticket-highlight-item">
                    <div class="ticket-highlight-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <div class="ticket-highlight-content">
                        <h4>Multi-Payment</h4>
                        <p>QRIS, VA, dan E-Wallet terintegrasi.</p>
                    </div>
                </div>
            </div>

            {{-- ════ 2. SIMULASI PERHITUNGAN BIAYA TIKET (INTERACTIVE CALCULATOR) ════ --}}
            <div class="ticket-calc-wrap" id="simulasi-biaya" data-aos="fade-up">
                <div class="ticket-calc-header">
                    <h2 class="ticket-calc-title">Simulasi Perhitungan Biaya Tiket<span class="accent-dot">.</span></h2>
                    <p class="ticket-calc-subtitle">
                        Hitung rincian biaya tiket secara transparan. Biaya admin payment gateway Rp 5.000 dan platform fee
                        5% dihitung otomatis.
                    </p>
                </div>

                <div class="ticket-calc-grid">
                    {{-- Left Input Card --}}
                    <div class="ticket-calc-input-card">
                        <div>
                            <div class="calc-form-group">
                                <label class="calc-label" for="calcTicketPrice">Harga Normal Tiket (Pokok)</label>
                                <div class="calc-input-box">
                                    <span class="calc-currency-prefix">Rp</span>
                                    <input type="text" id="calcTicketPrice" class="calc-input" value="100.000"
                                        inputmode="numeric" placeholder="100.000">
                                </div>
                                <div class="calc-presets">
                                    <button type="button" class="calc-preset-btn" data-val="50000">Rp 50rb</button>
                                    <button type="button" class="calc-preset-btn active" data-val="100000">Rp 100rb</button>
                                    <button type="button" class="calc-preset-btn" data-val="250000">Rp 250rb</button>
                                    <button type="button" class="calc-preset-btn" data-val="500000">Rp 500rb</button>
                                    <button type="button" class="calc-preset-btn" data-val="1000000">Rp 1 Jt</button>
                                </div>
                            </div>

                            <div class="calc-form-group" style="margin-top: 1.5rem;">
                                <label class="calc-label">Jumlah Tiket</label>
                                <div class="calc-qty-control">
                                    <button type="button" class="calc-qty-btn" id="qtyMinus"
                                        aria-label="Kurangi tiket">−</button>
                                    <input type="number" id="calcTicketQty" class="calc-qty-input" value="1" min="1"
                                        max="100" readonly>
                                    <button type="button" class="calc-qty-btn" id="qtyPlus"
                                        aria-label="Tambah tiket">+</button>
                                    <span class="calc-qty-text">Tiket</span>
                                </div>
                            </div>
                        </div>

                        <div class="calc-fee-info-box">
                            <div class="calc-fee-info-row">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                                    stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                <span>Biaya Admin Payment: <strong>Rp 5.000 / tiket</strong></span>
                            </div>
                            <div class="calc-fee-info-row">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                                    stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                <span>Platform Fee Buyle.id: <strong>5% dari harga tiket</strong></span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Breakdown Receipt Card --}}
                    <div class="ticket-calc-result-card">
                        <div class="calc-receipt-header">
                            <span class="calc-receipt-title">Rincian Pembayaran</span>
                            <span class="calc-receipt-tag">Realtime Estimasi</span>
                        </div>

                        <div class="calc-receipt-list">
                            <div class="calc-receipt-row">
                                <span class="calc-receipt-label">Harga Pokok Tiket (<span id="outQtyLabel">1</span>x)</span>
                                <span class="calc-receipt-val" id="outBasePrice">Rp 100.000</span>
                            </div>
                            <div class="calc-receipt-row">
                                <span class="calc-receipt-label">Biaya Admin (Rp 5.000/tiket)</span>
                                <span class="calc-receipt-val" id="outAdminFee">+ Rp 5.000</span>
                            </div>
                            <div class="calc-receipt-row">
                                <span class="calc-receipt-label">Platform Fee Buyle (5%)</span>
                                <span class="calc-receipt-val" id="outPlatformFee">+ Rp 5.000</span>
                            </div>
                            <div class="calc-receipt-divider"></div>
                            <div class="calc-receipt-row total-row">
                                <div>
                                    <div class="calc-total-label">Total Dibayar Pembeli</div>
                                </div>
                                <div class="calc-total-val" id="outTotalBuyer">Rp 110.000</div>
                            </div>
                        </div>

                        <div class="calc-creator-takeaway">
                            <div class="creator-takeaway-inner">
                                <div class="takeaway-left">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                                        viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                        <polyline points="22 4 12 14.01 9 11.01" />
                                    </svg>
                                    <span>Pendapatan Bersih Promotor:</span>
                                </div>
                                <span class="takeaway-val" id="outCreatorNet">Rp 100.000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ════ 3. SECTION BARU: MULTI GATE SCANNER & VENUE PLAN ════ --}}
    <section class="buyle-venue-section" id="venue-scanner-section">
        <div class="buyle-venue-container">

            {{-- Header Multi Gate Scanner (Tanpa Badge Sesuai Permintaan) --}}
            <div class="venue-header" data-aos="fade-up">
                <h2 class="venue-title">
                    Multi-Gate Scanner &amp; Denah Venue Event<span class="accent-dot">.</span>
                </h2>
                <p class="venue-desc">
                    Arsitektur gerbang terdistribusi dengan kapasitas <strong>Up to 10 Scanner Aktif</strong> bersamaan
                    untuk konser dan festival berkapasitas ribuan penonton. Terhubung langsung dengan <strong>FOH (Front of
                        House)</strong> di pusat arena dan sinkronisasi cloud realtime &lt;0.2 detik tanpa tabrakan data.
                </p>
            </div>

            {{-- Interactive Gate Selection Tabs --}}
            <div class="venue-tabs-bar" data-aos="fade-up" data-aos-delay="100">
                <button type="button" class="venue-tab-btn active" data-gate="all">
                    <span>Semua Gate (10 Scanner)</span>
                </button>
                <button type="button" class="venue-tab-btn" data-gate="gate1">
                    <span>Gate 1 (VIP)</span>
                </button>
                <button type="button" class="venue-tab-btn" data-gate="gate2">
                    <span>Gate 2 (Kru/Sponsor)</span>
                </button>
                <button type="button" class="venue-tab-btn" data-gate="gate3">
                    <span>Gate 3 (Festival Barat)</span>
                </button>
                <button type="button" class="venue-tab-btn" data-gate="gate4">
                    <span>Gate 4 (Festival Timur)</span>
                </button>
                <button type="button" class="venue-tab-btn" data-gate="gate5">
                    <span>Gate 5 (Main Entrance)</span>
                </button>
                <button type="button" class="venue-tab-btn" data-gate="foh">
                    <span>FOH Control Center</span>
                </button>
            </div>

            {{-- Interactive SVG Denah Venue Map — Clean Light Stadium Theme --}}
            <div class="venue-map-stage-wrap" data-aos="fade-up" data-aos-delay="150">
                <svg class="venue-map-svg" viewBox="0 0 1000 700" fill="none" xmlns="http://www.w3.org/2000/svg">
                    {{-- Grid Background Guide --}}
                    <defs>
                        <pattern id="arenaGrid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(30,179,73,0.06)" stroke-width="1" />
                        </pattern>
                    </defs>
                    <rect width="1000" height="700" fill="url(#arenaGrid)" />

                    {{-- ── Outer Arena Boundary ── --}}
                    <path d="M 120 180 C 120 70, 880 70, 880 180 L 920 540 C 920 660, 80 660, 80 540 Z" fill="#FFFFFF"
                        stroke="#CBD5E1" stroke-width="2.5" stroke-dasharray="10 6" />

                    {{-- ── Tribune / Grandstand Seating Area (Barat & Timur) ── --}}
                    <path d="M 100 240 C 90 350, 90 470, 110 560" stroke="#E2E8F0" stroke-width="32"
                        stroke-linecap="round" />
                    <text x="75" y="380" fill="#94A3B8" font-size="11" font-weight="800" letter-spacing="3"
                        transform="rotate(-90 75 380)">TRIBUNE BARAT</text>

                    <path d="M 900 240 C 910 350, 910 470, 890 560" stroke="#E2E8F0" stroke-width="32"
                        stroke-linecap="round" />
                    <text x="925" y="380" fill="#94A3B8" font-size="11" font-weight="800" letter-spacing="3"
                        transform="rotate(90 925 380)">TRIBUNE TIMUR</text>

                    {{-- ── Panggung Utama (Stage) ── --}}
                    <g id="venueStage">
                        {{-- Stage Light Spotlight Cones --}}
                        <polygon points="500 45, 300 240, 700 240" fill="url(#stageGlowGradient)" opacity="0.6" />
                        <polygon points="400 45, 200 230, 480 230" fill="url(#stageGlowGradient)" opacity="0.4" />
                        <polygon points="600 45, 520 230, 800 230" fill="url(#stageGlowGradient)" opacity="0.4" />

                        {{-- Stage Structure --}}
                        <path d="M 330 90 Q 500 50 670 90 L 650 140 Q 500 115 350 140 Z" fill="#F0FDF4"
                            stroke="url(#btnDaftarGradient)" stroke-width="3" />
                        <text x="500" y="105" fill="#15803d" font-size="13" font-weight="900" text-anchor="middle"
                            letter-spacing="2">PANGGUNG UTAMA / STAGE</text>
                        <text x="500" y="125" fill="#64748B" font-size="9" font-weight="600"
                            text-anchor="middle">PERFORMANCE &amp; LED WALL</text>

                        {{-- Barricade Pit --}}
                        <path d="M 280 160 Q 500 135 720 160" stroke="#f59e0b" stroke-width="2.5" stroke-dasharray="8 4" />
                        <text x="500" y="152" fill="#d97706" font-size="8.5" font-weight="700" text-anchor="middle"
                            letter-spacing="1">BARRICADE PIT / SOUND BUFFER</text>
                    </g>

                    {{-- ── VIP / VVIP Area ── --}}
                    <rect x="310" y="180" width="380" height="85" rx="14" fill="rgba(30, 179, 73, 0.08)"
                        stroke="rgba(30, 179, 73, 0.45)" stroke-width="2" />
                    <text x="500" y="228" fill="#15803d" font-size="12" font-weight="800" text-anchor="middle"
                        letter-spacing="3">AREA VVIP &amp; VIP SEATING</text>

                    {{-- ── Festival Standing Area ── --}}
                    <rect x="220" y="285" width="560" height="235" rx="18" fill="#F8FAFC" stroke="#E2E8F0"
                        stroke-width="2" />
                    <text x="320" y="325" fill="#64748B" font-size="11" font-weight="800" letter-spacing="2">FESTIVAL
                        A</text>
                    <text x="680" y="325" fill="#64748B" font-size="11" font-weight="800" letter-spacing="2">FESTIVAL
                        B</text>

                    {{-- ── Connecting Data Stream Rays (From Gates to FOH) ── --}}
                    <g id="dataRays">
                        {{-- Ray Gate 1 -> FOH --}}
                        <line x1="170" y1="210" x2="475" y2="395" stroke="url(#btnDaftarGradient)" stroke-width="2.5"
                            class="venue-ray" opacity="0.85" />
                        {{-- Ray Gate 2 -> FOH --}}
                        <line x1="830" y1="210" x2="525" y2="395" stroke="url(#btnDaftarGradient)" stroke-width="2.5"
                            class="venue-ray" opacity="0.85" />
                        {{-- Ray Gate 3 -> FOH --}}
                        <line x1="150" y1="430" x2="470" y2="415" stroke="url(#btnDaftarGradient)" stroke-width="2.5"
                            class="venue-ray" opacity="0.85" />
                        {{-- Ray Gate 4 -> FOH --}}
                        <line x1="850" y1="430" x2="530" y2="415" stroke="url(#btnDaftarGradient)" stroke-width="2.5"
                            class="venue-ray" opacity="0.85" />
                        {{-- Ray Gate 5 -> FOH --}}
                        <line x1="500" y1="585" x2="500" y2="445" stroke="url(#btnDaftarGradient)" stroke-width="3"
                            class="venue-ray" opacity="0.95" />
                    </g>

                    {{-- ── PUSAT ARENA: FOH (FRONT OF HOUSE) ── --}}
                    <g id="venueFOH" class="gate-hotspot" data-target="foh">
                        {{-- Concentric Radar Rings --}}
                        <circle cx="500" cy="415" r="35" fill="none" stroke="#1eb349" stroke-width="2"
                            class="radar-pulse-ring" />
                        <circle cx="500" cy="415" r="55" fill="none" stroke="rgba(165, 207, 55, 0.45)" stroke-width="1.5"
                            stroke-dasharray="4 4" />

                        {{-- FOH Booth Structure --}}
                        <rect x="440" y="380" width="120" height="70" rx="14" fill="#FFFFFF"
                            stroke="url(#btnDaftarGradient)" stroke-width="3" filter="url(#gateDropShadow)" />
                        <circle cx="465" cy="402" r="4" fill="#38bdf8" />
                        <circle cx="480" cy="402" r="4" fill="#1eb349" />
                        <circle cx="495" cy="402" r="4" fill="#f59e0b" />

                        {{-- FOH Text --}}
                        <text x="500" y="423" fill="#0F172A" font-size="12" font-weight="900" text-anchor="middle"
                            letter-spacing="1">FOH</text>
                        <text x="500" y="438" fill="#1eb349" font-size="8.5" font-weight="800" text-anchor="middle">FRONT OF
                            HOUSE</text>
                    </g>

                    {{-- ── GATE 1: VIP & MEDIA (Barat / Kiri Atas) ── --}}
                    <g id="mapGate1" class="gate-hotspot" data-target="gate1">
                        <circle cx="150" cy="205" r="28" fill="rgba(30, 179, 73, 0.15)" />
                        <rect x="85" y="180" width="130" height="52" rx="12" fill="#FFFFFF" stroke="url(#btnDaftarGradient)"
                            stroke-width="2.5" filter="url(#gateDropShadow)" />
                        <circle cx="106" cy="206" r="8" fill="#1eb349" />
                        <text x="122" y="202" fill="#0F172A" font-size="11" font-weight="900">GATE 1</text>
                        <text x="122" y="218" fill="#15803d" font-size="8.5" font-weight="800">VIP (2 SCANNER)</text>
                    </g>

                    {{-- ── GATE 2: SPONSOR & KRU (Timur / Kanan Atas) ── --}}
                    <g id="mapGate2" class="gate-hotspot" data-target="gate2">
                        <circle cx="850" cy="205" r="28" fill="rgba(30, 179, 73, 0.15)" />
                        <rect x="785" y="180" width="130" height="52" rx="12" fill="#FFFFFF"
                            stroke="url(#btnDaftarGradient)" stroke-width="2.5" filter="url(#gateDropShadow)" />
                        <circle cx="806" cy="206" r="8" fill="#1eb349" />
                        <text x="822" y="202" fill="#0F172A" font-size="11" font-weight="900">GATE 2</text>
                        <text x="822" y="218" fill="#15803d" font-size="8.5" font-weight="800">KRU (2 SCANNER)</text>
                    </g>

                    {{-- ── GATE 3: FESTIVAL WEST GATE (Barat / Kiri Tengah) ── --}}
                    <g id="mapGate3" class="gate-hotspot" data-target="gate3">
                        <circle cx="130" cy="430" r="28" fill="rgba(30, 179, 73, 0.15)" />
                        <rect x="65" y="405" width="135" height="52" rx="12" fill="#FFFFFF" stroke="url(#btnDaftarGradient)"
                            stroke-width="2.5" filter="url(#gateDropShadow)" />
                        <circle cx="86" cy="431" r="8" fill="#1eb349" />
                        <text x="102" y="427" fill="#0F172A" font-size="11" font-weight="900">GATE 3</text>
                        <text x="102" y="443" fill="#15803d" font-size="8.5" font-weight="800">FEST. A (2 SCAN)</text>
                    </g>

                    {{-- ── GATE 4: FESTIVAL EAST GATE (Timur / Kanan Tengah) ── --}}
                    <g id="mapGate4" class="gate-hotspot" data-target="gate4">
                        <circle cx="870" cy="430" r="28" fill="rgba(30, 179, 73, 0.15)" />
                        <rect x="800" y="405" width="135" height="52" rx="12" fill="#FFFFFF"
                            stroke="url(#btnDaftarGradient)" stroke-width="2.5" filter="url(#gateDropShadow)" />
                        <circle cx="821" cy="431" r="8" fill="#1eb349" />
                        <text x="837" y="427" fill="#0F172A" font-size="11" font-weight="900">GATE 4</text>
                        <text x="837" y="443" fill="#15803d" font-size="8.5" font-weight="800">FEST. B (2 SCAN)</text>
                    </g>

                    {{-- ── GATE 5: MAIN ENTRANCE (Selatan / Bawah Utama) ── --}}
                    <g id="mapGate5" class="gate-hotspot" data-target="gate5">
                        <circle cx="500" cy="610" r="32" fill="rgba(30, 179, 73, 0.2)" />
                        <rect x="405" y="585" width="190" height="54" rx="14" fill="#FFFFFF"
                            stroke="url(#btnDaftarGradient)" stroke-width="3" filter="url(#gateDropShadow)" />
                        <circle cx="430" cy="612" r="9" fill="#1eb349" />
                        <text x="448" y="608" fill="#0F172A" font-size="12" font-weight="900">GATE 5 (MAIN GATE)</text>
                        <text x="448" y="626" fill="#15803d" font-size="9" font-weight="800">REGULER • 2 SCANNER</text>
                    </g>
                </svg>
            </div>

            {{-- ── Gate Live Status Inspector Card ── --}}
            <div class="venue-inspector-card" id="gateInspector" data-aos="fade-up" data-aos-delay="200">
                <div class="inspector-left">
                    <div class="inspector-icon-badge" id="inspIconBadge">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="7" width="18" height="13" rx="2"></rect>
                            <circle cx="8" cy="12" r="1.5"></circle>
                            <circle cx="16" cy="12" r="1.5"></circle>
                        </svg>
                    </div>
                    <div>
                        <h3 class="inspector-name" id="inspTitle">Semua Gerbang (Gate 1 - 5 Terkoneksi)</h3>
                        <p class="inspector-desc" id="inspDesc">Seluruh gerbang check-in tersambung ke FOH Hub dan server
                            cloud Buyle.id.</p>
                    </div>
                </div>

                <div class="inspector-stats-row">
                    <div class="stat-block">
                        <div class="stat-val" id="inspScanners">10 Device</div>
                        <div class="stat-label">Scanner Aktif</div>
                    </div>
                    <div class="stat-block">
                        <div class="stat-val" id="inspSpeed">&lt; 0.18 Detik</div>
                        <div class="stat-label">Kecepatan Scan</div>
                    </div>
                    <div class="stat-block">
                        <div class="stat-val" id="inspStatus" style="color:#1eb349;">100% Online</div>
                        <div class="stat-label">Status Jaringan</div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Interactive Javascript Engine for Calculator & Venue Map --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            /* ════ 1. TICKET COST CALCULATOR LOGIC ════ */
            const priceInput = document.getElementById('calcTicketPrice');
            const qtyInput = document.getElementById('calcTicketQty');
            const qtyMinus = document.getElementById('qtyMinus');
            const qtyPlus = document.getElementById('qtyPlus');
            const presetBtns = document.querySelectorAll('.calc-preset-btn');

            const outQtyLabel = document.getElementById('outQtyLabel');
            const outBasePrice = document.getElementById('outBasePrice');
            const outAdminFee = document.getElementById('outAdminFee');
            const outPlatformFee = document.getElementById('outPlatformFee');
            const outTotalBuyer = document.getElementById('outTotalBuyer');
            const outCreatorNet = document.getElementById('outCreatorNet');

            function parseCurrency(str) {
                return parseInt(String(str).replace(/[^0-9]/g, ''), 10) || 0;
            }

            function formatRupiah(num) {
                return 'Rp ' + Math.round(num).toLocaleString('id-ID');
            }

            function recalculate() {
                let basePrice = parseCurrency(priceInput.value);
                let qty = parseInt(qtyInput.value, 10) || 1;
                if (qty < 1) qty = 1;
                if (qty > 100) qty = 100;

                // Admin fee = Rp 5.000 per ticket
                const adminFeePerTicket = 5000;
                // Platform fee = 5% of base ticket price
                const platformFeeRate = 0.05;

                const totalBasePrice = basePrice * qty;
                const totalAdminFee = adminFeePerTicket * qty;
                const totalPlatformFee = Math.round(totalBasePrice * platformFeeRate);
                const totalBuyer = totalBasePrice + totalAdminFee + totalPlatformFee;
                const creatorNet = totalBasePrice;

                if (outQtyLabel) outQtyLabel.textContent = qty;
                if (outBasePrice) outBasePrice.textContent = formatRupiah(totalBasePrice);
                if (outAdminFee) outAdminFee.textContent = '+ ' + formatRupiah(totalAdminFee);
                if (outPlatformFee) outPlatformFee.textContent = '+ ' + formatRupiah(totalPlatformFee);
                if (outTotalBuyer) outTotalBuyer.textContent = formatRupiah(totalBuyer);
                if (outCreatorNet) outCreatorNet.textContent = formatRupiah(creatorNet);
            }

            if (priceInput) {
                priceInput.addEventListener('input', function () {
                    let val = parseCurrency(this.value);
                    this.value = val ? val.toLocaleString('id-ID') : '';
                    presetBtns.forEach(b => b.classList.remove('active'));
                    recalculate();
                });
            }

            presetBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    presetBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const val = parseInt(this.getAttribute('data-val'), 10);
                    if (priceInput) {
                        priceInput.value = val.toLocaleString('id-ID');
                    }
                    recalculate();
                });
            });

            if (qtyMinus && qtyInput) {
                qtyMinus.addEventListener('click', function () {
                    let q = parseInt(qtyInput.value, 10) || 1;
                    if (q > 1) {
                        qtyInput.value = q - 1;
                        recalculate();
                    }
                });
            }

            if (qtyPlus && qtyInput) {
                qtyPlus.addEventListener('click', function () {
                    let q = parseInt(qtyInput.value, 10) || 1;
                    if (q < 100) {
                        qtyInput.value = q + 1;
                        recalculate();
                    }
                });
            }

            recalculate();

            /* ════ 2. MULTI-GATE VENUE INTERACTIVE MAP LOGIC ════ */
            const gateData = {
                all: {
                    title: "Semua Gerbang (Gate 1 - 5 Terkoneksi)",
                    desc: "Seluruh 5 gerbang check-in terhubung realtime ke FOH Hub dan server cloud Buyle.id.",
                    scanners: "10 Device",
                    speed: "< 0.18 Detik",
                    status: "100% Online"
                },
                gate1: {
                    title: "Gate 1: VIP & Media Entrance",
                    desc: "Gerbang khusus pemegang tiket VIP, VVIP, artis, dan media pers dengan jalur karpet merah eksklusif.",
                    scanners: "2 Device",
                    speed: "0.15 Detik",
                    status: "Lancar (VIP Priority)"
                },
                gate2: {
                    title: "Gate 2: Sponsor & Kru Entrance",
                    desc: "Gerbang verifikasi panitia lapangan, kru panggung, teknisi FOH, dan perwakilan sponsor resmi.",
                    scanners: "2 Device",
                    speed: "0.14 Detik",
                    status: "Lancar (Akses Khusus)"
                },
                gate3: {
                    title: "Gate 3: Festival West Gate",
                    desc: "Gerbang masuk penonton Festival A dan Tribun Barat. Dilengkapi 2 jalur scan berkecepatan tinggi.",
                    scanners: "2 Device",
                    speed: "0.18 Detik",
                    status: "Optimal (Anti-Queue)"
                },
                gate4: {
                    title: "Gate 4: Festival East Gate",
                    desc: "Gerbang masuk penonton Festival B dan Tribun Timur dengan sinkronisasi kuota realtime.",
                    scanners: "2 Device",
                    speed: "0.18 Detik",
                    status: "Optimal (Anti-Queue)"
                },
                gate5: {
                    title: "Gate 5: Main Entrance (Reguler)",
                    desc: "Pintu masuk utama penonton reguler di sisi selatan venue. Mampu mengalirkan ribuan penonton tanpa hambatan.",
                    scanners: "2 Device",
                    speed: "0.16 Detik",
                    status: "Kapasitas Penuh Siap"
                },
                foh: {
                    title: "FOH (Front of House) Control Center",
                    desc: "Pusat komando tata suara, pencahayaan, audio console, dan router sinkronisasi cloud seluruh gate.",
                    scanners: "Hub Server",
                    speed: "< 50ms Ping",
                    status: "Master Node Active"
                }
            };

            const tabBtns = document.querySelectorAll('.venue-tab-btn');
            const hotspots = document.querySelectorAll('.gate-hotspot');
            const inspTitle = document.getElementById('inspTitle');
            const inspDesc = document.getElementById('inspDesc');
            const inspScanners = document.getElementById('inspScanners');
            const inspSpeed = document.getElementById('inspSpeed');
            const inspStatus = document.getElementById('inspStatus');

            function updateInspector(key) {
                const data = gateData[key] || gateData.all;
                if (inspTitle) inspTitle.textContent = data.title;
                if (inspDesc) inspDesc.textContent = data.desc;
                if (inspScanners) inspScanners.textContent = data.scanners;
                if (inspSpeed) inspSpeed.textContent = data.speed;
                if (inspStatus) inspStatus.textContent = data.status;

                tabBtns.forEach(t => {
                    t.classList.toggle('active', t.getAttribute('data-gate') === key);
                });

                hotspots.forEach(h => {
                    h.classList.toggle('active', h.getAttribute('data-target') === key);
                });
            }

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const key = this.getAttribute('data-gate');
                    updateInspector(key);
                });
            });

            hotspots.forEach(spot => {
                spot.addEventListener('click', function () {
                    const key = this.getAttribute('data-target');
                    updateInspector(key);
                });
            });
        });
    </script>

@endsection