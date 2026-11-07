<div class="bank-balance-card">
    <div class="bank-header">
        <div class="bank-title-wrap">
            <span class="bank-live-dot"></span>
            <span class="bank-label">Saldo Siap Ditarik</span>
        </div>
        <button type="button" class="bank-eye-btn" onclick="toggleBalanceVisibility()" title="Sembunyikan / Tampilkan Saldo" aria-label="Toggle Saldo">
            <svg class="eye-open-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <svg class="eye-closed-icon" style="display:none;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            </svg>
        </button>
    </div>

    <div class="bank-main-val-wrap">
        <span class="bank-curr">Rp</span>
        <span class="bank-amount val-real">{{ number_format($availableBalance, 0, ',', '.') }}</span>
        <span class="bank-masked-dots val-masked" style="display:none;">••••••••</span>
    </div>
    <div class="bank-note">Dapat dicairkan kapan saja ke rekening Anda</div>

    @if(($posBalance ?? 0) > 0)
    <div class="bank-divider"></div>

    <div class="bank-sub-box">
        <div class="bank-sub-row">
            <div class="bank-sub-left">
                <span>Deposit POS</span>
                <span class="bank-sub-tag kasir">Kasir</span>
            </div>
            <div class="bank-sub-right">
                <span class="val-real">Rp {{ number_format($posBalance ?? 0, 0, ',', '.') }}</span>
                <span class="val-masked" style="display:none;">••••••</span>
                <button type="button" class="bank-btn-transfer" onclick="openTransferPosModal()" title="Pindah ke saldo utama">+ Pindah</button>
            </div>
        </div>
    </div>
    @endif
</div>
