@extends('creator.layout')
@section('title', 'Pencairan Dana')
@section('page_title', 'Pencairan Dana')
@section('breadcrumb', 'Keuangan › Pencairan Dana')

@section('styles')
<style>
    .payout-card { background:#fff; border-radius:16px; border:1px solid #e7f0e7; box-shadow:0 2px 8px rgba(0,0,0,0.04); overflow:hidden; max-width:720px; }
    .payout-header { background:linear-gradient(135deg,#1eb349,#a5cf37); padding:1.5rem; color:#fff; }
    .payout-balance { font-size:2rem; font-weight:800; letter-spacing:-0.04em; }
    .payout-body { padding:1.5rem; }
    .info-row { display:flex; justify-content:space-between; align-items:center; padding:0.75rem 0; border-bottom:1px solid #f3f7f3; font-size:0.82rem; }
    .info-row:last-child { border-bottom:none; }
    .info-label { color:#64748B; font-weight:600; }
    .info-val { font-weight:700; color:#0f1f0f; }
    .form-group { display:flex; flex-direction:column; gap:0.4rem; margin-bottom:1rem; }
    .form-label { font-size:0.8rem; font-weight:700; color:#374151; }
    .form-input { height:44px; padding:0 1rem; border:1.5px solid #e7f0e7; border-radius:10px; font-family:'Montserrat',sans-serif; font-size:0.875rem; color:#1a1a1a; background:#f9fefb; outline:none; transition:all 0.2s; }
    .form-input:focus { border-color:#1eb349; background:#fff; box-shadow:0 0 0 3px rgba(30,179,73,0.1); }
    .btn-submit { height:44px; padding:0 1.5rem; border-radius:10px; background:linear-gradient(135deg,#1eb349,#a5cf37); border:none; font-family:'Montserrat',sans-serif; font-size:0.85rem; font-weight:700; color:#fff; cursor:pointer; box-shadow:0 2px 8px rgba(30,179,73,0.3); transition:all 0.2s; width:100%; margin-top:0.5rem; }
    .btn-submit:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(30,179,73,0.4); }
    .alert-info { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.875rem 1rem; font-size:0.8rem; color:#15803d; margin-bottom:1.5rem; }
</style>
@endsection

@section('content')
<div class="payout-card">
    <div class="payout-header">
        <div style="font-size:0.75rem; font-weight:700; opacity:0.8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.25rem;">💰 Saldo Tersedia</div>
        <div class="payout-balance">Rp {{ number_format($availableBalance, 0, ',', '.') }}</div>
        <div style="font-size:0.78rem; opacity:0.8; margin-top:0.5rem;">Platform fee {{ $platformFeeRate }}% sudah dipotong otomatis</div>
    </div>
    <div class="payout-body">
        <div class="alert-info">
            ℹ️ Pencairan diproses dalam 1–3 hari kerja setelah disetujui Admin.
        </div>

        <div style="margin-bottom:1.25rem;">
            <div class="info-row"><span class="info-label">Total GMV</span><span class="info-val">Rp {{ number_format($gmv, 0, ',', '.') }}</span></div>
            <div class="info-row"><span class="info-label">Platform Fee ({{ $platformFeeRate }}%)</span><span class="info-val" style="color:#ef4444;">- Rp {{ number_format($platformFee, 0, ',', '.') }}</span></div>
            <div class="info-row"><span class="info-label">Sudah Dicairkan</span><span class="info-val" style="color:#64748B;">- Rp {{ number_format($totalPayout, 0, ',', '.') }}</span></div>
            <div class="info-row" style="font-size:1rem;"><span class="info-label" style="color:#0f1f0f; font-weight:800;">Saldo Tersedia</span><span class="info-val" style="color:#1eb349; font-size:1.1rem;">Rp {{ number_format($availableBalance, 0, ',', '.') }}</span></div>
        </div>

        @if($availableBalance > 0)
        <form action="{{ route('creator.payout.request') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Bank</label>
                <select name="bank_name" class="form-input" required>
                    <option value="">— Pilih Bank —</option>
                    @foreach(['BCA','BNI','BRI','Mandiri','BSI','CIMB Niaga','Jenius/SMBC','Dana','GoPay','OVO','ShopeePay'] as $bank)
                        <option value="{{ $bank }}">{{ $bank }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Rekening / Akun</label>
                <input type="text" name="account_number" class="form-input" placeholder="1234567890" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Pemilik Rekening</label>
                <input type="text" name="account_name" value="{{ auth()->user()->name }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Penarikan (Rp)</label>
                <input type="number" name="amount" class="form-input" min="50000" max="{{ $availableBalance }}" placeholder="Minimum Rp 50.000" required>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan (opsional)</label>
                <input type="text" name="notes" class="form-input" placeholder="Misal: Pencairan bulan Agustus">
            </div>
            <button type="submit" class="btn-submit" onclick="return confirm('Ajukan pencairan dana?')">
                ✅ Ajukan Pencairan
            </button>
        </form>
        @else
        <div style="text-align:center; padding:2rem 1rem; color:#94A3B8;">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem; opacity:0.4;"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            <h3 style="font-size:0.95rem; font-weight:700; color:#374151; margin-bottom:0.5rem;">Saldo Belum Tersedia</h3>
            <p style="font-size:0.8rem;">Saldo akan tersedia setelah ada transaksi yang berhasil dibayar pembeli.</p>
        </div>
        @endif
    </div>
</div>
@endsection
