@extends('creator.layout')
@section('title', 'Saldo & Pencairan · Creator Dashboard')
@section('page_title', 'Saldo & Pencairan')

@section('styles')
<style>
    .bio-layout {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
    }

    .bio-sidebar {
        width: 300px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 20px;
        padding: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border: 1px solid #f0fdf4;
        position: sticky;
        top: 1.5rem;
    }

    .bio-content {
        flex: 1;
        min-width: 0;
    }

    .tab-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 0.85rem 1rem;
        border: none;
        background: transparent;
        color: #64748b;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 12px;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s;
        margin-bottom: 0.25rem;
    }

    .tab-btn:hover {
        background: #f8fafc;
        color: #1eb349;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.2);
    }

    .prof-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e7f0e7;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .prof-card-head {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f7f3;
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #fafdfb;
    }

    .form-body {
        padding: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #334155;
    }

    .form-input {
        height: 44px;
        padding: 0 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.875rem;
        color: #1a1a1a;
        background: #f8fafc;
        outline: none;
        transition: all 0.2s;
    }

    .form-input:focus {
        border-color: #1eb349;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
    }

    .btn-submit-payout {
        height: 46px;
        padding: 0 1.5rem;
        border-radius: 999px;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.875rem;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        margin-top: 0.5rem;
    }

    .btn-submit-payout:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #64748b;
        font-weight: 600;
    }

    .info-val {
        font-weight: 700;
        color: #0f172a;
    }

    .alert-notice {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        font-size: 0.82rem;
        color: #15803d;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    /* Premium Custom Select Component */
    .custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    .custom-select-trigger {
        height: 46px;
        padding: 0 1.15rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        background: #f8fafc;
        outline: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s;
        box-sizing: border-box;
        user-select: none;
    }

    .custom-select-trigger:hover,
    .custom-select-trigger.active {
        border-color: #1eb349;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
    }

    .custom-select-trigger svg {
        transition: transform 0.2s;
        color: #64748b;
        flex-shrink: 0;
    }

    .custom-select-trigger.active svg {
        transform: rotate(180deg);
        color: #1eb349;
    }

    .custom-select-options {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12), 0 4px 12px rgba(30, 179, 73, 0.08);
        z-index: 999;
        max-height: 240px;
        overflow-y: auto;
        padding: 6px;
    }

    .custom-select-options.open {
        display: block;
        animation: selectFadeIn 0.15s ease-out;
    }

    @keyframes selectFadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .custom-select-option {
        padding: 10px 14px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        border-radius: 99px;
        cursor: pointer;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2px;
    }

    .custom-select-option:hover {
        background: #f0fdf4;
        color: #1eb349;
        font-weight: 700;
    }

    .custom-select-option.selected {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #ffffff;
        font-weight: 700;
    }

    .custom-select-option.custom-other {
        border-top: 1px dashed #e2e8f0;
        border-radius: 12px;
        margin-top: 4px;
        color: #1eb349;
        font-weight: 700;
        background: #f0fdf4;
    }

    @media (max-width: 991px) {
        .bio-layout {
            flex-direction: column;
        }

        .bio-sidebar {
            width: 100%;
            position: static;
        }
    }
</style>
@endsection

@section('content')
@php
    $presetBanks = ['BCA','BNI','BRI','Mandiri','BSI','CIMB Niaga','Jenius/SMBC','Permata Bank','Bank DKI','Bank BJB','Bank Jatim','Dana','GoPay','OVO','ShopeePay'];
@endphp
<div class="bio-layout">

    {{-- SUB SIDEBAR --}}
    <div class="bio-sidebar">
        {{-- Balance Card --}}
        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-radius: 16px; padding: 1.25rem; margin-bottom: 1.25rem;">
            <div style="font-size: 0.72rem; font-weight: 800; color: #166534; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.35rem;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                Saldo Siap Ditarik
            </div>
            <div style="font-size: 1.75rem; font-weight: 800; color: #15803d; letter-spacing: -0.03em;">
                Rp {{ number_format($availableBalance, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.72rem; color: #166534; margin-top: 0.4rem; font-weight: 600;">
                Platform Fee: 5% (Dibayar Pembeli)
            </div>
        </div>

        <button type="button" class="tab-btn active" onclick="switchPayoutTab('tab-withdraw', this)">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="16" cy="12" r="2"/><path d="M6 12h.01"/></svg>
            Penarikan Saldo
        </button>
        <button type="button" class="tab-btn" onclick="switchPayoutTab('tab-bank', this)">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Pengaturan Rekening
        </button>
        <button type="button" class="tab-btn" onclick="switchPayoutTab('tab-history', this)">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            Riwayat Pencairan
        </button>
    </div>

    {{-- CONTENT AREA --}}
    <div class="bio-content">

        {{-- TAB 1: PENARIKAN SALDO --}}
        <div id="tab-withdraw" class="payout-tab-pane" style="display: block;">
            <div class="prof-card">
                <div class="prof-card-head">
                    <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="16" cy="12" r="2"/><path d="M6 12h.01"/></svg>
                    Form Pengajuan Penarikan Saldo
                </div>
                <div class="form-body">
                    <div class="alert-notice d-flex align-items-start gap-2">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <div>
                            <strong>Ketentuan Penarikan Saldo:</strong><br>
                            • Pencairan diproses 1–3 hari kerja setelah diajukan.<br>
                            • Dikenakan <strong>Biaya Penarikan (Withdrawal Fee)</strong> sebesar <strong>Rp 5.000</strong> per transaksi.<br>
                            • Minimal jumlah penarikan saldo adalah <strong>Rp 50.000</strong>.
                        </div>
                    </div>

                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:1.25rem; margin-bottom:1.5rem;">
                        <div class="info-row"><span class="info-label">Total Penjualan (GMV)</span><span class="info-val">Rp {{ number_format($gmv, 0, ',', '.') }}</span></div>
                        <div class="info-row"><span class="info-label">Total Sudah Dicairkan</span><span class="info-val" style="color:#64748B;">- Rp {{ number_format($totalPayout, 0, ',', '.') }}</span></div>
                        <div class="info-row" style="font-size:1rem;"><span class="info-label" style="color:#0f172a; font-weight:800;">Saldo Siap Ditarik</span><span class="info-val" style="color:#1eb349; font-size:1.15rem;">Rp {{ number_format($availableBalance, 0, ',', '.') }}</span></div>
                    </div>

                    @if($availableBalance >= 50000)
                    <form action="{{ route('creator.payout.request') }}" method="POST" id="withdrawForm">
                        @csrf
                        @php
                            $currentBank1 = $seller->bank_name ?? '';
                            $isCustomBank1 = $currentBank1 && !in_array($currentBank1, $presetBanks);
                        @endphp
                        <div class="form-group">
                            <label class="form-label">Tujuan Bank / E-Wallet <span>*</span></label>
                            
                            <div class="custom-select-wrapper" id="wrapBankTab1">
                                <div class="custom-select-trigger" onclick="toggleCustomDropdown('wrapBankTab1')">
                                    <span class="custom-select-text" id="textBankTab1">
                                        {{ $currentBank1 ? ($isCustomBank1 ? "Lainnya: {$currentBank1}" : $currentBank1) : '— Pilih Bank / E-Wallet —' }}
                                    </span>
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                                <div class="custom-select-options">
                                    @foreach($presetBanks as $bank)
                                        <div class="custom-select-option {{ $currentBank1 === $bank ? 'selected' : '' }}" onclick="selectBankOption('wrapBankTab1', '{{ $bank }}', '{{ $bank }}')">
                                            <span>{{ $bank }}</span>
                                            @if($currentBank1 === $bank)<span>✓</span>@endif
                                        </div>
                                    @endforeach
                                    <div class="custom-select-option custom-other {{ $isCustomBank1 ? 'selected' : '' }}" onclick="selectBankOption('wrapBankTab1', 'custom', 'Lainnya (Ketik Manual...)')">
                                        <span>Lainnya (Ketik Manual...)</span>
                                    </div>
                                </div>
                                <input type="hidden" name="bank_name" id="hiddenBankTab1" value="{{ $currentBank1 }}" required>
                            </div>

                            <div id="customInputWrapTab1" style="display: {{ $isCustomBank1 ? 'block' : 'none' }}; margin-top: 0.6rem;">
                                <input type="text" id="customBankInputTab1" class="form-input" placeholder="Ketik nama Bank / E-Wallet Anda (misal: SeaBank, Blu, Neobank)..." value="{{ $isCustomBank1 ? $currentBank1 : '' }}" oninput="updateCustomBankVal('wrapBankTab1', this.value)">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor Rekening / Akun <span>*</span></label>
                            <input type="text" name="bank_account_number" value="{{ $seller->bank_account_number ?? '' }}" class="form-input" placeholder="Contoh: 1234567890" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Pemilik Rekening <span>*</span></label>
                            <input type="text" name="bank_account_name" value="{{ $seller->bank_account_name ?? $seller->name }}" class="form-input" placeholder="Nama sesuai di rekening" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Penarikan (Rp) <span>*</span></label>
                            <input type="number" name="amount" id="withdrawAmount" class="form-input" min="50000" max="{{ $availableBalance }}" placeholder="Minimum Rp 50.000" oninput="calcNetPayout()" required>
                            <span style="font-size:0.75rem; color:#64748B; margin-top:0.25rem;" id="netPayoutCalc">Biaya Penarikan: Rp 5.000 | Dana Bersih Diterima: Rp 0</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Catatan (Opsional)</label>
                            <input type="text" name="notes" class="form-input" placeholder="Misal: Penarikan saldo bulan ini">
                        </div>
                        <button type="submit" class="btn-submit-payout" onclick="return confirm('Ajukan penarikan saldo?')">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                            Ajukan Penarikan Dana
                        </button>
                    </form>
                    @else
                    <div style="text-align:center; padding:2.5rem 1rem; color:#94A3B8;">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem; opacity:0.4;"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        <h4 style="font-size:0.95rem; font-weight:700; color:#374151; margin-bottom:0.4rem;">Saldo Belum Mencapai Minimum</h4>
                        <p style="font-size:0.8rem; margin:0;">Batas minimal penarikan saldo adalah Rp 50.000.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB 2: PENGATURAN REKENING --}}
        <div id="tab-bank" class="payout-tab-pane" style="display: none;">
            <div class="prof-card">
                <div class="prof-card-head">
                    <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Informasi & Pengaturan Rekening Bank
                </div>
                <div class="form-body">
                    <form action="{{ route('creator.payout.bank.update') }}" method="POST" id="bankSettingForm">
                        @csrf
                        @php
                            $currentBank2 = $seller->bank_name ?? '';
                            $isCustomBank2 = $currentBank2 && !in_array($currentBank2, $presetBanks);
                        @endphp
                        <div class="form-group">
                            <label class="form-label">Nama Bank / E-Wallet <span>*</span></label>
                            
                            <div class="custom-select-wrapper" id="wrapBankTab2">
                                <div class="custom-select-trigger" onclick="toggleCustomDropdown('wrapBankTab2')">
                                    <span class="custom-select-text" id="textBankTab2">
                                        {{ $currentBank2 ? ($isCustomBank2 ? "Lainnya: {$currentBank2}" : $currentBank2) : '— Pilih Bank / E-Wallet —' }}
                                    </span>
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                                <div class="custom-select-options">
                                    @foreach($presetBanks as $bank)
                                        <div class="custom-select-option {{ $currentBank2 === $bank ? 'selected' : '' }}" onclick="selectBankOption('wrapBankTab2', '{{ $bank }}', '{{ $bank }}')">
                                            <span>{{ $bank }}</span>
                                            @if($currentBank2 === $bank)<span>✓</span>@endif
                                        </div>
                                    @endforeach
                                    <div class="custom-select-option custom-other {{ $isCustomBank2 ? 'selected' : '' }}" onclick="selectBankOption('wrapBankTab2', 'custom', 'Lainnya (Ketik Manual...)')">
                                        <span>Lainnya (Ketik Manual...)</span>
                                    </div>
                                </div>
                                <input type="hidden" name="bank_name" id="hiddenBankTab2" value="{{ $currentBank2 }}" required>
                            </div>

                            <div id="customInputWrapTab2" style="display: {{ $isCustomBank2 ? 'block' : 'none' }}; margin-top: 0.6rem;">
                                <input type="text" id="customBankInputTab2" class="form-input" placeholder="Ketik nama Bank / E-Wallet Anda (misal: SeaBank, Blu, Neobank)..." value="{{ $isCustomBank2 ? $currentBank2 : '' }}" oninput="updateCustomBankVal('wrapBankTab2', this.value)">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor Rekening / Akun E-Wallet <span>*</span></label>
                            <input type="text" name="bank_account_number" value="{{ $seller->bank_account_number ?? '' }}" class="form-input" placeholder="Contoh: 1234567890" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Pemilik Rekening <span>*</span></label>
                            <input type="text" name="bank_account_name" value="{{ $seller->bank_account_name ?? $seller->name }}" class="form-input" placeholder="Nama sesuai di rekening" required>
                        </div>

                        <button type="submit" class="btn-submit-payout" style="margin-top:0.75rem;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Pengaturan Rekening
                        </button>
                    </form>
                    <p style="font-size:0.78rem; color:#64748b; margin-top:1.25rem; line-height:1.5;">
                        Informasi rekening di atas akan otomatis digunakan sebagai tujuan penarikan saldo Anda.
                    </p>
                </div>
            </div>
        </div>

        {{-- TAB 3: RIWAYAT PENCAIRAN --}}
        <div id="tab-history" class="payout-tab-pane" style="display: none;">
            <div class="prof-card">
                <div class="prof-card-head">
                    <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Riwayat Pengajuan Pencairan
                </div>
                <div class="form-body">
                    @if(isset($requests) && $requests->count() > 0)
                        <div style="overflow-x:auto;">
                            <table class="table align-middle" style="font-size:0.85rem; width:100%;">
                                <thead>
                                    <tr style="background:#f8fafc; color:#475569; text-align:left;">
                                        <th style="padding:0.75rem 1rem;">Tanggal</th>
                                        <th style="padding:0.75rem 1rem;">Bank</th>
                                        <th style="padding:0.75rem 1rem;">Jumlah</th>
                                        <th style="padding:0.75rem 1rem;">Admin Fee</th>
                                        <th style="padding:0.75rem 1rem;">Bersih</th>
                                        <th style="padding:0.75rem 1rem;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $req)
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:0.85rem 1rem;">{{ $req->created_at->format('d M Y, H:i') }}</td>
                                        <td style="padding:0.85rem 1rem;">{{ $req->bank_name }} ({{ $req->bank_account_number }})</td>
                                        <td style="padding:0.85rem 1rem;">Rp {{ number_format($req->amount, 0, ',', '.') }}</td>
                                        <td style="padding:0.85rem 1rem; color:#ef4444;">- Rp {{ number_format($req->admin_fee ?? 5000, 0, ',', '.') }}</td>
                                        <td style="padding:0.85rem 1rem; font-weight:700; color:#1eb349;">Rp {{ number_format(($req->amount - ($req->admin_fee ?? 5000)), 0, ',', '.') }}</td>
                                        <td style="padding:0.85rem 1rem;">
                                            <span style="font-size:0.72rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:99px; text-transform:uppercase; background:{{ $req->status === 'approved' || $req->status === 'processed' ? '#dcfce7' : ($req->status === 'pending' ? '#fef3c7' : '#fef2f2') }}; color:{{ $req->status === 'approved' || $req->status === 'processed' ? '#166534' : ($req->status === 'pending' ? '#92400E' : '#991B1B') }};">
                                                {{ $req->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="text-align:center; padding:2.5rem 1rem; color:#94A3B8;">
                            <p style="font-size:0.85rem; margin:0;">Belum ada riwayat penarikan saldo.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function switchPayoutTab(tabId, btn) {
        document.querySelectorAll('.payout-tab-pane').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById(tabId).style.display = 'block';
        btn.classList.add('active');
    }

    function calcNetPayout() {
        const val = parseFloat(document.getElementById('withdrawAmount').value) || 0;
        const adminFee = 5000;
        const net = Math.max(0, val - adminFee);
        document.getElementById('netPayoutCalc').textContent = `Biaya Penarikan: Rp 5.000 | Dana Bersih Diterima: Rp ${net.toLocaleString('id-ID')}`;
    }

    function toggleCustomDropdown(wrapperId) {
        const wrap = document.getElementById(wrapperId);
        if (!wrap) return;
        const trigger = wrap.querySelector('.custom-select-trigger');
        const options = wrap.querySelector('.custom-select-options');
        
        const isOpen = options.classList.contains('open');
        document.querySelectorAll('.custom-select-options').forEach(el => el.classList.remove('open'));
        document.querySelectorAll('.custom-select-trigger').forEach(el => el.classList.remove('active'));

        if (!isOpen) {
            options.classList.add('open');
            trigger.classList.add('active');
        }
    }

    function selectBankOption(wrapperId, val, labelText) {
        const wrap = document.getElementById(wrapperId);
        if (!wrap) return;
        const textSpan = wrap.querySelector('.custom-select-text');
        const hiddenInput = wrap.querySelector('input[type="hidden"]');
        const options = wrap.querySelector('.custom-select-options');
        const trigger = wrap.querySelector('.custom-select-trigger');
        const isTab1 = wrapperId.includes('Tab1');
        const customInputWrap = document.getElementById(isTab1 ? 'customInputWrapTab1' : 'customInputWrapTab2');
        const customBankInput = document.getElementById(isTab1 ? 'customBankInputTab1' : 'customBankInputTab2');

        wrap.querySelectorAll('.custom-select-option').forEach(el => el.classList.remove('selected'));

        if (val === 'custom') {
            textSpan.textContent = '✍️ Lainnya (Ketik Manual...)';
            customInputWrap.style.display = 'block';
            customBankInput.focus();
            hiddenInput.value = customBankInput.value.trim() || 'Lainnya';
        } else {
            textSpan.textContent = labelText;
            customInputWrap.style.display = 'none';
            hiddenInput.value = val;
        }

        options.classList.remove('open');
        trigger.classList.remove('active');
    }

    function updateCustomBankVal(wrapperId, typedVal) {
        const wrap = document.getElementById(wrapperId);
        if (!wrap) return;
        const hiddenInput = wrap.querySelector('input[type="hidden"]');
        hiddenInput.value = typedVal.trim() || 'Lainnya';
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select-wrapper')) {
            document.querySelectorAll('.custom-select-options').forEach(el => el.classList.remove('open'));
            document.querySelectorAll('.custom-select-trigger').forEach(el => el.classList.remove('active'));
        }
    });
</script>
@endsection
