@extends('layouts.app')

@section('title', 'Cek Resi & Lacak Paket — buyle.id')
@section('meta_description', 'Cek status pengiriman paket Anda secara real-time dari semua kurir populer: JNE, J&T, SiCepat, AnterAja, dan lainnya.')

@section('content')
    <style>
        :root {
            --tr-blue: #0EA5E9;
            --tr-blue-d: #0369A1;
            --tr-blue-l: #E0F2FE;
            --tr-slate: #64748B;
            --tr-text: #0F172A;
            --tr-bg: #F8FAFC;
            --tr-border: #E2E8F0;
            --tr-white: #FFFFFF;
            --tr-green: #16A34A;
            --tr-green-l: #DCFCE7;
            --tr-amber: #D97706;
            --tr-amber-l: #FEF9C3;
            --tr-red: #DC2626;
            --tr-red-l: #FEE2E2;
            --tr-font: 'Montserrat', sans-serif;
            --tr-r: 16px;
            --tr-shadow: 0 8px 32px rgba(14, 165, 233, .06);
        }

        .tr-page {
            min-height: 100vh;
            background: var(--tr-bg);
            font-family: var(--tr-font);
            padding: 100px 0 3rem;
        }

        .tr-wrap {
            max-width: 1050px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        .tr-page-title {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 2rem;
        }

        .tr-page-title-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--tr-blue-l), #fff);
            border: 1px solid var(--tr-blue-l);
            color: var(--tr-blue-d);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(14, 165, 233, .08);
        }

        .tr-page-title h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--tr-text);
            margin: 0;
            letter-spacing: -.02em;
        }

        .tr-page-title p {
            font-size: .85rem;
            color: var(--tr-slate);
            margin: .15rem 0 0;
        }

        .tr-grid-layout {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 1.75rem;
            align-items: start;
        }

        @media(max-width:860px) {
            .tr-grid-layout {
                grid-template-columns: 1fr;
            }

            .tr-page {
                padding-top: 80px;
            }
        }

        .tr-card {
            background: var(--tr-white);
            border: 1px solid var(--tr-border);
            border-radius: var(--tr-r);
            padding: 1.75rem;
            box-shadow: var(--tr-shadow);
            margin-bottom: 1rem;
        }

        .tr-form-row {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .tr-field label {
            display: block;
            font-size: .75rem;
            font-weight: 700;
            color: var(--tr-slate);
            margin-bottom: .4rem;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .tr-field input,
        .tr-field select {
            width: 100%;
            padding: .85rem 1rem;
            border: 1.5px solid var(--tr-border);
            border-radius: 12px;
            font-size: .95rem;
            font-family: var(--tr-font);
            font-weight: 500;
            color: var(--tr-text);
            background: var(--tr-bg);
            outline: none;
            transition: all .2s;
            box-sizing: border-box;
        }

        .tr-field input:focus,
        .tr-field select:focus {
            border-color: var(--tr-blue);
            background: var(--tr-white);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, .12);
        }

        .tr-field-err {
            color: var(--tr-red);
            font-size: .75rem;
            margin-top: .4rem;
            font-weight: 500;
        }

        .tr-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            background: linear-gradient(135deg, var(--tr-blue), var(--tr-blue-d));
            color: #fff;
            border: none;
            padding: .9rem 1.5rem;
            border-radius: 12px;
            font-size: .95rem;
            font-weight: 700;
            font-family: var(--tr-font);
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(14, 165, 233, .25);
            transition: transform .2s, box-shadow .2s;
            width: 100%;
        }

        .tr-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, .35);
        }

        .tr-btn:disabled {
            opacity: .65;
            transform: none;
        }

        .tr-note {
            font-size: .72rem;
            color: var(--tr-slate);
            display: flex;
            align-items: center;
            gap: .35rem;
            margin-top: 1.25rem;
            flex-wrap: wrap;
            background: var(--tr-blue-l);
            padding: .6rem .8rem;
            border-radius: 10px;
        }

        .tr-chips {
            display: flex;
            flex-wrap: wrap;
            gap: .35rem;
            margin-top: .75rem;
        }

        .tr-chip {
            font-size: .65rem;
            font-weight: 600;
            background: var(--tr-bg);
            border: 1px solid var(--tr-border);
            color: var(--tr-slate);
            border-radius: 999px;
            padding: .2rem .6rem;
        }

        .tr-empty-state {
            background: var(--tr-white);
            border: 2px dashed var(--tr-border);
            border-radius: var(--tr-r);
            padding: 5rem 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--tr-slate);
        }

        .tr-empty-state svg {
            width: 72px;
            height: 72px;
            margin-bottom: 1.25rem;
            opacity: 0.3;
            color: var(--tr-slate);
        }

        .tr-empty-state h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--tr-text);
            margin: 0 0 .5rem;
        }

        .tr-empty-state p {
            font-size: .9rem;
            max-width: 320px;
            margin: 0;
            line-height: 1.6;
        }

        .tr-error {
            background: var(--tr-red-l);
            border: 1px solid #FECACA;
            border-radius: var(--tr-r);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            color: #991B1B;
            margin-bottom: 1rem;
            font-size: .85rem;
        }

        .tr-error strong {
            display: block;
            font-weight: 700;
            margin-bottom: .15rem;
        }

        .tr-result {
            border: 1px solid var(--tr-border);
            border-radius: var(--tr-r);
            overflow: hidden;
            background: var(--tr-white);
            box-shadow: var(--tr-shadow);
            animation: trUp .4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes trUp {
            from {
                opacity: 0;
                transform: translateY(16px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .tr-result-head {
            border-bottom: 1px solid var(--tr-border);
            padding: 1.5rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            background: linear-gradient(to right, var(--tr-white), var(--tr-bg));
        }

        .tr-awb-label {
            font-size: .68rem;
            font-weight: 700;
            color: var(--tr-slate);
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: .25rem;
        }

        .tr-awb-num {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--tr-text);
            letter-spacing: .02em;
        }

        .tr-courier-tag {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: var(--tr-blue-l);
            color: var(--tr-blue-d);
            border-radius: 999px;
            padding: .25rem .85rem;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-top: .4rem;
        }

        .tr-status {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .5rem 1.1rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .03em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .tr-status.delivered {
            background: var(--tr-green-l);
            color: var(--tr-green);
        }

        .tr-status.transit {
            background: var(--tr-amber-l);
            color: var(--tr-amber);
        }

        .tr-status.process {
            background: var(--tr-blue-l);
            color: var(--tr-blue-d);
        }

        .tr-status.other {
            background: var(--tr-bg);
            color: var(--tr-slate);
        }

        .tr-info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--tr-border);
            gap: 1px;
            border-bottom: 1px solid var(--tr-border);
        }

        @media(max-width:560px) {
            .tr-info-row {
                grid-template-columns: 1fr;
            }
        }

        .tr-info-box {
            background: var(--tr-white);
            padding: 1.25rem 1.75rem;
        }

        .tr-info-lbl {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--tr-slate);
            margin-bottom: .4rem;
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        .tr-info-name {
            font-size: .95rem;
            font-weight: 800;
            color: var(--tr-text);
            margin-bottom: .15rem;
        }

        .tr-info-loc {
            font-size: .8rem;
            color: var(--tr-slate);
            line-height: 1.4;
        }

        .tr-weight-strip {
            padding: .75rem 1.75rem;
            background: var(--tr-bg);
            border-bottom: 1px solid var(--tr-border);
            font-size: .78rem;
            color: var(--tr-slate);
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .tr-timeline-wrap {
            padding: 1.5rem 1.75rem;
        }

        .tr-timeline-hd {
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--tr-slate);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .tr-timeline-hd::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--tr-border);
        }

        .tr-tl {
            position: relative;
            padding-left: 2.25rem;
        }

        .tr-tl-line {
            position: absolute;
            top: 8px;
            bottom: 8px;
            left: 7px;
            width: 2px;
            background: linear-gradient(to bottom, var(--tr-blue) 0%, var(--tr-border) 100%);
        }

        .tr-tl-item {
            position: relative;
            padding-bottom: 1.75rem;
            animation: trUp .4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .tr-tl-item:last-child {
            padding-bottom: 0;
        }

        .tr-tl-dot {
            position: absolute;
            left: -2.25rem;
            top: 4px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--tr-border);
            background: var(--tr-border);
        }

        .tr-tl-dot.active {
            background: var(--tr-blue);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, .2);
            width: 16px;
            height: 16px;
            left: calc(-2.25rem - 1px);
            top: 3px;
        }

        .tr-tl-date {
            font-size: .72rem;
            color: var(--tr-slate);
            font-weight: 600;
            margin-bottom: .25rem;
        }

        .tr-tl-desc {
            font-size: .88rem;
            font-weight: 500;
            color: #334155;
            line-height: 1.5;
        }

        .tr-tl-item.active .tr-tl-desc {
            font-weight: 700;
            color: var(--tr-text);
        }

        .tr-tl-loc {
            display: inline-flex;
            align-items: flex-start;
            gap: .3rem;
            font-size: .75rem;
            color: var(--tr-slate);
            margin-top: .35rem;
            line-height: 1.4;
        }

        .tr-tl-loc svg {
            flex-shrink: 0;
            margin-top: 2px;
        }
    </style>

    <div class="tr-page">
        <div class="tr-wrap">

            <div class="tr-page-title">
                <div class="tr-page-title-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13" rx="2" />
                        <path d="M16 8h4l3 3v5h-7V8z" />
                        <circle cx="5.5" cy="18.5" r="2.5" />
                        <circle cx="18.5" cy="18.5" r="2.5" />
                    </svg>
                </div>
                <div>
                    <h1>Lacak Paket</h1>
                    <p>Pantau perjalanan paket Anda secara real-time</p>
                </div>
            </div>

            <div class="tr-grid-layout">
                <!-- Kolom Kiri: Form -->
                <div class="tr-col-left">
                    <div class="tr-card">
                        <form action="{{ route('cek-resi.track') }}" method="POST" id="trackForm">
                            @csrf
                            <div class="tr-form-row">
                                <div class="tr-field">
                                    <label for="awb_input">Nomor Resi / AWB</label>
                                    <input type="text" id="awb_input" name="awb" value="{{ old('awb', $awb ?? '') }}"
                                        required placeholder="Contoh: JX2024XXXXXX" autocomplete="off" spellcheck="false">
                                    @error('awb')
                                    <div class="tr-field-err">{{ $message }}</div>@enderror
                                </div>
                                <div class="tr-field">
                                    <label for="courier_select">Pilih Kurir Ekspedisi</label>
                                    <select id="courier_select" name="courier" required>
                                        <option value="" disabled {{ empty($courier ?? '') ? 'selected' : '' }}>Pilih
                                            Kurir...</option>
                                        <option value="jne" {{ ($courier ?? '') == 'jne' ? 'selected' : '' }}>JNE</option>
                                        <option value="jnt" {{ ($courier ?? '') == 'jnt' ? 'selected' : '' }}>J&amp;T Express
                                        </option>
                                        <option value="sicepat" {{ ($courier ?? '') == 'sicepat' ? 'selected' : '' }}>SiCepat
                                        </option>
                                        <option value="anteraja" {{ ($courier ?? '') == 'anteraja' ? 'selected' : '' }}>
                                            AnterAja</option>
                                        <option value="pos" {{ ($courier ?? '') == 'pos' ? 'selected' : '' }}>POS Indonesia
                                        </option>
                                        <option value="ninja" {{ ($courier ?? '') == 'ninja' ? 'selected' : '' }}>Ninja Xpress
                                        </option>
                                        <option value="lion" {{ ($courier ?? '') == 'lion' ? 'selected' : '' }}>Lion Parcel
                                        </option>
                                        <option value="idx" {{ ($courier ?? '') == 'idx' ? 'selected' : '' }}>ID Express
                                        </option>
                                    </select>
                                    @error('courier')
                                    <div class="tr-field-err">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <button type="submit" class="tr-btn" id="trackBtn">
                                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="M21 21l-4.35-4.35" />
                                </svg>
                                <span id="trackBtnText">Lacak Sekarang</span>
                            </button>

                            <div class="tr-chips">
                                @foreach(['JNE', 'J&T', 'SiCepat', 'AnterAja', 'POS', 'Ninja', 'Lion', 'ID Express'] as $chip)
                                    <span class="tr-chip">{{ $chip }}</span>
                                @endforeach
                            </div>
                            <div class="tr-note">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 8v4m0 4h.01" />
                                </svg>
                                <strong>Update Otomatis</strong> — Terhubung langsung ke server ekspedisi.
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kolom Kanan: Result / Empty State -->
                <div class="tr-col-right">
                    @if(!empty($error))
                        <div class="tr-error">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                style="flex-shrink:0;margin-top:2px;">
                                <path
                                    d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                            <div><strong>Gagal Melacak Paket</strong>{{ $error }}</div>
                        </div>
                    @endif

                    @if(!empty($tracking))
                        @php
                            $st = strtolower($tracking['summary']['status'] ?? '');
                            $isDelivered = str_contains($st, 'terkirim') || str_contains($st, 'delivered');
                            $isTransit = str_contains($st, 'perjalanan') || str_contains($st, 'transit');
                            $isProcess = str_contains($st, 'proses') || str_contains($st, 'process') || str_contains($st, 'pickup');
                            $statusClass = $isDelivered ? 'delivered' : ($isTransit ? 'transit' : ($isProcess ? 'process' : 'other'));
                        @endphp

                        <div class="tr-result">
                            <div class="tr-result-head">
                                <div>
                                    <div class="tr-awb-label">Nomor Resi / AWB</div>
                                    <div class="tr-awb-num">{{ $tracking['summary']['awb'] }}</div>
                                    <span class="tr-courier-tag">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <rect x="1" y="3" width="15" height="13" rx="2" />
                                            <path d="M16 8h4l3 3v5h-7V8z" />
                                            <circle cx="5.5" cy="18.5" r="2.5" />
                                            <circle cx="18.5" cy="18.5" r="2.5" />
                                        </svg>
                                        {{ $tracking['summary']['courier'] }}@if($tracking['summary']['service'] && $tracking['summary']['service'] != '-')
                                        &nbsp;·&nbsp;{{ $tracking['summary']['service'] }}@endif
                                    </span>
                                </div>
                                <div class="tr-status {{ $statusClass }}">
                                    @if($isDelivered)
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>Terkirim
                                    @elseif($isTransit)
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>Dalam Perjalanan
                                    @elseif($isProcess)
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 8v4m0 4h.01" />
                                        </svg>Diproses
                                    @else
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 8v4m0 4h.01" />
                                        </svg>{{ $tracking['summary']['status'] }}
                                    @endif
                                </div>
                            </div>

                            <div class="tr-info-row">
                                <div class="tr-info-box">
                                    <div class="tr-info-lbl">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path d="M5 10l7-7 7 7" />
                                            <path d="M12 3v18" />
                                        </svg>Pengirim
                                    </div>
                                    <div class="tr-info-name">{{ $tracking['detail']['shipper'] ?? '-' }}</div>
                                    <div class="tr-info-loc">{{ $tracking['detail']['origin'] ?? '' }}</div>
                                </div>
                                <div class="tr-info-box" style="border-left:1px solid var(--tr-border);">
                                    <div class="tr-info-lbl">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path d="M19 14l-7 7-7-7" />
                                            <path d="M12 3v18" />
                                        </svg>Penerima
                                    </div>
                                    <div class="tr-info-name">{{ $tracking['detail']['receiver'] ?? '-' }}</div>
                                    <div class="tr-info-loc">{{ $tracking['detail']['destination'] ?? '' }}</div>
                                </div>
                            </div>

                            @if(isset($tracking['detail']['weight']) && $tracking['detail']['weight'] !== '-')
                                <div class="tr-weight-strip">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                        <line x1="3" y1="6" x2="21" y2="6" />
                                    </svg>
                                    Berat paket: <strong
                                        style="color:var(--tr-text); margin-left:.2rem;">{{ $tracking['detail']['weight'] }}</strong>
                                </div>
                            @endif

                            <div class="tr-timeline-wrap">
                                <div class="tr-timeline-hd">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 8v4l2 2" />
                                    </svg>
                                    Riwayat Pengiriman
                                </div>
                                <div class="tr-tl">
                                    <div class="tr-tl-line"></div>
                                    @php
                                        $historyReversed = array_reverse($tracking['history']);
                                    @endphp
                                    @foreach($historyReversed as $i => $hist)
                                        <div class="tr-tl-item {{ $i === 0 ? 'active' : '' }}"
                                            style="animation-delay:{{ $i * 0.05 }}s">
                                            <div class="tr-tl-dot {{ $i === 0 ? 'active' : '' }}"></div>
                                            <div class="tr-tl-date">
                                                @php try {
                                                        echo date('d M Y, H:i', strtotime($hist['date']));
                                                    } catch (\Exception $e) {
                                                        echo $hist['date'];
                                                } @endphp
                                            </div>
                                            <div class="tr-tl-desc">{{ $hist['desc'] }}</div>
                                            @if(!empty($hist['location']))
                                                <div class="tr-tl-loc">
                                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                                                        <circle cx="12" cy="9" r="2.5" />
                                                    </svg>
                                                    {{ $hist['location'] }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @elseif(empty($error))
                        <div class="tr-empty-state">
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13" rx="2" />
                                <path d="M16 8h4l3 3v5h-7V8z" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                            <h4>Belum Ada Pencarian</h4>
                            <p>Masukkan nomor resi dan pilih kurir di panel sebelah kiri untuk melacak status paket Anda.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('trackForm').addEventListener('submit', function () {
            var btn = document.getElementById('trackBtn');
            var txt = document.getElementById('trackBtnText');
            btn.disabled = true; btn.style.opacity = '.7';
            txt.textContent = 'Melacak...';
        });
    </script>
@endsection