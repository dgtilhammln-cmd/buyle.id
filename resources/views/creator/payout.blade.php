@extends('creator.layout')
@section('title', 'Pencairan Dana & Pengaturan Payout')
@section('page_title', 'Pencairan Dana & Pengaturan Payout')
@section('breadcrumb', 'Keuangan › Payout Settings')

@section('styles')
<style>
    .payout-card { background:#fff; border-radius:20px; border:1px solid #e7f0e7; box-shadow:0 4px 20px rgba(0,0,0,0.04); overflow:hidden; max-width:900px; margin-bottom: 2rem; }
    .payout-header { background:linear-gradient(135deg,#1eb349,#a5cf37); padding:1.75rem 2rem; color:#fff; }
    .payout-balance { font-size:2.25rem; font-weight:800; letter-spacing:-0.04em; }
    .payout-body { padding:2rem; }
    
    /* Tabs Navigation */
    .payout-tabs { display:flex; gap:0.5rem; padding:0.5rem; background:#f8fafc; border-radius:14px; border:1px solid #e2e8f0; margin-bottom:1.5rem; }
    .payout-tab { flex:1; text-align:center; padding:0.65rem 1rem; font-size:0.85rem; font-weight:700; color:#64748b; border-radius:10px; cursor:pointer; transition:all 0.2s; text-decoration:none; border:none; background:transparent; }
    .payout-tab.active { background:#fff; color:#1eb349; box-shadow:0 2px 8px rgba(0,0,0,0.05); }

    .info-row { display:flex; justify-content:space-between; align-items:center; padding:0.75rem 0; border-bottom:1px solid #f3f7f3; font-size:0.85rem; }
    .info-row:last-child { border-bottom:none; }
    .info-label { color:#64748B; font-weight:600; }
    .info-val { font-weight:700; color:#0f1f0f; }
    
    .form-group { display:flex; flex-direction:column; gap:0.4rem; margin-bottom:1.25rem; }
    .form-label { font-size:0.8rem; font-weight:700; color:#374151; }
    .form-input { height:44px; padding:0 1rem; border:1.5px solid #e7f0e7; border-radius:10px; font-family:'Montserrat',sans-serif; font-size:0.875rem; color:#1a1a1a; background:#f9fefb; outline:none; transition:all 0.2s; }
    .form-input:focus { border-color:#1eb349; background:#fff; box-shadow:0 0 0 3px rgba(30,179,73,0.1); }
    .btn-submit { height:46px; padding:0 1.5rem; border-radius:12px; background:linear-gradient(135deg,#1eb349,#a5cf37); border:none; font-family:'Montserrat',sans-serif; font-size:0.875rem; font-weight:700; color:#fff; cursor:pointer; box-shadow:0 4px 14px rgba(30,179,73,0.3); transition:all 0.2s; width:100%; margin-top:0.5rem; }
    .btn-submit:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(30,179,73,0.4); }
    
    .alert-info { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:1rem; font-size:0.82rem; color:#15803d; margin-bottom:1.5rem; line-height:1.5; }
    .badge-status { font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:99px; text-transform:uppercase; }
</style>
@endsection

@section('content')
<div class="payout-card">
    <div class="payout-header">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <div style="font-size:0.75rem; font-weight:700; opacity:0.85; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.25rem;">💰 Saldo Tersedia</div>
                <div class="payout-balance">Rp {{ number_format($availableBalance, 0, ',', '.') }}</div>
            </div>
            <div style="background:rgba(255,255,255,0.2); padding:0.5rem 1rem; border-radius:12px; backdrop-filter:blur(4px); font-size:0.78rem; font-weight:600;">
                Platform Fee: 5% (Pembeli)
            </div>
        </div>
    </div>
    
    <div class="payout-body">
        {{-- Navigation Tabs --}}
        <div class="payout-tabs">
            <button type="button" class="payout-tab active" onclick="switchTab('withdrawTab', this)">
                💸 Penarikan Saldo
            </button>
            <button type="button" class="payout-tab" onclick="switchTab('settingsTab', this)">
                🏦 Pengaturan Rekening
            </button>
            <button type="button" class="payout-tab" onclick="switchTab('historyTab', this)">
                📜 Riwayat Pencairan
            </button>
        </div>

        {{-- TAB 1: PENARIKAN SALDO --}}
        <div id="withdrawTab" class="tab-content">
            <div class="alert-info d-flex align-items-start gap-2">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <div>
                    <strong>Informasi Penarikan Saldo:</strong><br>
                    • Pencairan diproses dalam 1–3 hari kerja setelah disetujui Admin.<br>
                    • Dikenakan **Biaya Penarikan (Withdrawal Fee)** sebesar **Rp 5.000** per transaksi penarikan.<br>
                    • Minimal penarikan saldo adalah **Rp 50.000**.
                </div>
            </div>

            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:1.25rem; margin-bottom:1.5rem;">
                <div class="info-row"><span class="info-label">Total Pendapatan (GMV)</span><span class="info-val">Rp {{ number_format($gmv, 0, ',', '.') }}</span></div>
                <div class="info-row"><span class="info-label">Sudah Dicairkan</span><span class="info-val" style="color:#64748B;">- Rp {{ number_format($totalPayout, 0, ',', '.') }}</span></div>
                <div class="info-row" style="font-size:1rem;"><span class="info-label" style="color:#0f1f0f; font-weight:800;">Saldo Siap Ditarik</span><span class="info-val" style="color:#1eb349; font-size:1.15rem;">Rp {{ number_format($availableBalance, 0, ',', '.') }}</span></div>
            </div>

            @if($availableBalance >= 50000)
            <form action="{{ route('creator.payout.request') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tujuan Bank / E-Wallet</label>
                    <select name="bank_name" class="form-input" required>
                        <option value="">— Pilih Bank —</option>
                        @foreach(['BCA','BNI','BRI','Mandiri','BSI','CIMB Niaga','Jenius/SMBC','Dana','GoPay','OVO','ShopeePay'] as $bank)
                            <option value="{{ $bank }}" {{ ($seller->bank_name ?? '') === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Rekening / Akun</label>
                    <input type="text" name="account_number" value="{{ $seller->bank_account_number ?? '' }}" class="form-input" placeholder="1234567890" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" value="{{ $seller->bank_account_name ?? $seller->name }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Penarikan (Rp)</label>
                    <input type="number" name="amount" id="withdrawAmount" class="form-input" min="50000" max="{{ $availableBalance }}" placeholder="Minimum Rp 50.000" oninput="calcNetPayout()" required>
                    <span style="font-size:0.75rem; color:#64748B; margin-top:0.25rem;" id="netPayoutCalc">Biaya Penarikan: Rp 5.000 | Dana Bersih Diterima: Rp 0</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <input type="text" name="notes" class="form-input" placeholder="Misal: Penarikan saldo event">
                </div>
                <button type="submit" class="btn-submit" onclick="return confirm('Ajukan penarikan saldo?')">
                    💸 Ajukan Penarikan Dana
                </button>
            </form>
            @else
            <div style="text-align:center; padding:2rem 1rem; color:#94A3B8;">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem; opacity:0.4;"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <h3 style="font-size:0.95rem; font-weight:700; color:#374151; margin-bottom:0.5rem;">Saldo Minimal Rp 50.000</h3>
                <p style="font-size:0.8rem;">Saldo akan dapat ditarik setelah mencapai batas minimal penarikan.</p>
            </div>
            @endif
        </div>

        {{-- TAB 2: PENGATURAN REKENING --}}
        <div id="settingsTab" class="tab-content" style="display:none;">
            <h4 style="font-weight:700; color:#0F172A; margin-bottom:1rem; font-size:1rem;">Detail Rekening Bank Penerima</h4>
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:1.25rem;">
                <div class="info-row"><span class="info-label">Bank</span><span class="info-val">{{ $seller->bank_name ?? 'Belum diatur' }}</span></div>
                <div class="info-row"><span class="info-label">No. Rekening</span><span class="info-val">{{ $seller->bank_account_number ?? 'Belum diatur' }}</span></div>
                <div class="info-row"><span class="info-label">Atas Nama</span><span class="info-val">{{ $seller->bank_account_name ?? 'Belum diatur' }}</span></div>
            </div>
            <p style="font-size:0.8rem; color:#64748B; margin-top:1rem;">Detail rekening bank akan terisi otomatis saat Anda melakukan pengajuan penarikan dana.</p>
        </div>

        {{-- TAB 3: RIWAYAT PENCAIRAN --}}
        <div id="historyTab" class="tab-content" style="display:none;">
            <h4 style="font-weight:700; color:#0F172A; margin-bottom:1rem; font-size:1rem;">Riwayat Pengajuan Penarikan</h4>
            @if(isset($requests) && $requests->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle" style="font-size:0.85rem;">
                        <thead>
                            <tr style="background:#f8fafc; color:#475569;">
                                <th>Tanggal</th>
                                <th>Bank</th>
                                <th>Jumlah</th>
                                <th>Admin Fee</th>
                                <th>Diterima Bersih</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                            <tr>
                                <td>{{ $req->created_at->format('d M Y, H:i') }}</td>
                                <td>{{ $req->bank_name }} ({{ $req->bank_account_number }})</td>
                                <td>Rp {{ number_format($req->amount, 0, ',', '.') }}</td>
                                <td style="color:#ef4444;">- Rp {{ number_format($req->admin_fee ?? 5000, 0, ',', '.') }}</td>
                                <td style="font-weight:700; color:#1eb349;">Rp {{ number_format(($req->amount - ($req->admin_fee ?? 5000)), 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge-status" style="background:{{ $req->status === 'approved' || $req->status === 'processed' ? '#dcfce7' : ($req->status === 'pending' ? '#fef3c7' : '#fef2f2') }}; color:{{ $req->status === 'approved' || $req->status === 'processed' ? '#166534' : ($req->status === 'pending' ? '#92400E' : '#991B1B') }};">
                                        {{ $req->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align:center; padding:2rem 1rem; color:#94A3B8;">
                    <p style="font-size:0.85rem;">Belum ada riwayat penarikan saldo.</p>
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.payout-tab').forEach(el => el.classList.remove('active'));
        document.getElementById(tabId).style.display = 'block';
        btn.classList.add('active');
    }

    function calcNetPayout() {
        const val = parseFloat(document.getElementById('withdrawAmount').value) || 0;
        const adminFee = 5000;
        const net = Math.max(0, val - adminFee);
        document.getElementById('netPayoutCalc').textContent = `Biaya Penarikan: Rp 5.000 | Dana Bersih Diterima: Rp ${net.toLocaleString('id-ID')}`;
    }
</script>
@endsection
