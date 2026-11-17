@extends('layouts.admin')

@section('title', 'Detail Media Partner: ' . $mediaPartner->name . ' - Admin')
@section('page-title', 'Detail Media Partner')

@section('content')
<style>
    .mp-container {
        font-family: 'Montserrat', sans-serif;
    }
    .mp-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .mp-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0F172A;
        letter-spacing: -.03em;
        margin: 0;
    }
    .mp-sub {
        font-size: 0.8rem;
        color: #94A3B8;
        margin-top: 0.25rem;
    }
    .mp-profile-card {
        background: #ffffff;
        border: 1.5px solid #F1F5F9;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    }
    .mp-p-label {
        font-size: 0.68rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }
    .mp-p-val {
        font-size: 0.95rem;
        font-weight: 800;
        color: #0F172A;
    }
    .mp-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #E2E8F0;
        padding-bottom: 0.5rem;
    }
    .mp-tab-btn {
        background: transparent;
        border: none;
        font-size: 0.82rem;
        font-weight: 700;
        color: #64748B;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
    }
    .mp-tab-btn.active {
        background: #F0FDF4;
        color: #16A34A;
    }
    .mp-table-card {
        background: #ffffff;
        border: 1.5px solid #F1F5F9;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    }
    .mp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.78rem;
        text-align: left;
    }
    .mp-table th {
        background: #F8FAFC;
        padding: 0.85rem 1rem;
        font-weight: 700;
        color: #475569;
        border-bottom: 1.5px solid #E2E8F0;
        text-transform: uppercase;
        font-size: 0.68rem;
        letter-spacing: 0.04em;
    }
    .mp-table td {
        padding: 0.95rem 1rem;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        color: #1E293B;
    }
    .mp-action-btn {
        padding: .4rem .85rem;
        border-radius: 10px;
        font-size: .75rem;
        font-weight: 700;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        border: none;
        display: inline-block;
        font-family: 'Montserrat', sans-serif;
        transition: all .18s;
    }
    .mp-btn-main {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .58rem 1.1rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #1eb349, #15803D);
        color: #fff;
        font-size: .8rem;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(30,179,73,0.25);
        transition: all .2s;
        font-family: 'Montserrat', sans-serif;
    }
</style>

<div class="mp-container">

    {{-- Breadcrumb & Header --}}
    <div style="margin-bottom:0.75rem;">
        <a href="{{ route('admin.media-partners.index') }}" style="font-size:0.75rem; color:#64748B; text-decoration:none; font-weight:700;">
            &larr; Kembali ke Daftar Media Partner
        </a>
    </div>

    <div class="mp-header">
        <div style="display:flex; align-items:center; gap:0.85rem;">
            @if($mediaPartner->avatar_url)
                <img src="{{ $mediaPartner->avatar_url }}" alt="{{ $mediaPartner->name }}" style="width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #BBF7D0;">
            @else
                <div style="width:48px; height:48px; border-radius:50%; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; font-weight:800; font-size:1.1rem; display:flex; align-items:center; justify-content:center;">
                    {{ strtoupper(substr($mediaPartner->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h1 class="mp-title">{{ $mediaPartner->name }}</h1>
                <p class="mp-sub">Kode Referral: <strong>{{ $mediaPartner->code }}</strong> &bull; Link: <code>{{ $mediaPartner->referral_url }}</code></p>
            </div>
        </div>
        <div style="display:flex; gap:0.5rem;">
            <button type="button" onclick="navigator.clipboard.writeText('{{ $mediaPartner->referral_url }}'); alert('Link referral disalin:\n{{ $mediaPartner->referral_url }}');" class="mp-btn-main">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                Salin Link Referral
            </button>
        </div>
    </div>

    {{-- Profile & Stats Card --}}
    <div class="mp-profile-card">
        <div>
            <div class="mp-p-label">Komisi Aktif</div>
            <div class="mp-p-val" style="color:#1D4ED8;">{{ $mediaPartner->commission_rate }}% per Tiket</div>
            <div style="font-size:0.68rem; color:#64748B; margin-top:2px;">Sistem Lifetime Revenue Sharing</div>
        </div>
        <div>
            <div class="mp-p-label">Total EO / Creator Dibawa</div>
            <div class="mp-p-val">{{ $mediaPartner->creators_count }} Event Organizer</div>
        </div>
        <div>
            <div class="mp-p-label">Akumulasi Komisi</div>
            <div class="mp-p-val" style="color:#16A34A;">Rp {{ number_format($mediaPartner->total_commission_earned, 0, ',', '.') }}</div>
        </div>
        <div>
            <div class="mp-p-label">Saldo Belum Dicairkan</div>
            <div class="mp-p-val" style="color:#D97706;">Rp {{ number_format($mediaPartner->balance, 0, ',', '.') }}</div>
        </div>
        <div>
            <div class="mp-p-label">Rekening Pencairan</div>
            <div style="font-size:0.85rem; font-weight:700; color:#0F172A;">
                {{ $mediaPartner->bank_name ?: '-' }} {{ $mediaPartner->bank_account_number }}
            </div>
            <div style="font-size:0.68rem; color:#64748B;">a/n {{ $mediaPartner->bank_account_name ?: '-' }}</div>
        </div>
        <div>
            <div class="mp-p-label">PIC &amp; Kontak</div>
            <div style="font-size:0.85rem; font-weight:700; color:#0F172A;">{{ $mediaPartner->pic_name ?: '-' }}</div>
            <div style="font-size:0.68rem; color:#64748B;">{{ $mediaPartner->pic_phone }} {{ $mediaPartner->pic_email }}</div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mp-tabs">
        <button type="button" class="mp-tab-btn active" id="tabBtnCreators" onclick="switchTab('creators')">
            Daftar Creator / EO Terkait ({{ $creators->total() }})
        </button>
        <button type="button" class="mp-tab-btn" id="tabBtnLogs" onclick="switchTab('logs')">
            Riwayat Komisi Transaksi Tiket ({{ $commissionLogs->total() }})
        </button>
    </div>

    {{-- TAB 1: CREATORS / EO LIST --}}
    <div id="tabContentCreators" class="mp-table-card">
        <div style="overflow-x:auto;">
            <table class="mp-table">
                <thead>
                    <tr>
                        <th>Creator / Toko</th>
                        <th>Email &amp; Kontak</th>
                        <th>Tanggal Terdaftar</th>
                        <th>Event Tiket</th>
                        <th>Total Transaksi</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($creators as $c)
                        @php
                            $cp = $c->creatorProfile;
                            $storeTitle = $cp?->store_name ?: ($c->name);
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight:800; color:#0F172A;">{{ $storeTitle }}</div>
                                @if($cp?->store_slug)
                                    <a href="{{ url('/b/'.$cp->store_slug) }}" target="_blank" style="font-size:0.7rem; color:#1eb349; text-decoration:none;">
                                        {{ '@' . $cp->store_slug }} &rsaquo;
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600; color:#334155;">{{ $c->email }}</div>
                                @if($c->phone)
                                    <div style="font-size:0.68rem; color:#64748B;">{{ $c->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:0.75rem; color:#64748B;">
                                    {{ $c->created_at ? $c->created_at->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td>
                                <span style="font-weight:700; color:#1D4ED8;">{{ $c->products->count() }} Event</span>
                            </td>
                            <td>
                                <div style="font-weight:700; color:#16A34A;">{{ $c->orders_count }} Pesanan</div>
                            </td>
                            <td style="text-align:right;">
                                <a href="{{ route('admin.all-creator.show', $c) }}" class="mp-action-btn" style="background:#F8FAFC; border:1px solid #E2E8F0; color:#475569;">
                                    Buka Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:3rem; color:#94A3B8;">
                                Belum ada Event Creator yang terafiliasi dengan Media Partner ini. Bagikan link referral di atas untuk mengajak EO bergabung.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($creators->hasPages())
            <div style="padding:1rem; border-top:1px solid #F1F5F9; display:flex; justify-content:flex-end;">
                {{ $creators->links() }}
            </div>
        @endif
    </div>

    {{-- TAB 2: COMMISSION TRANSACTION LOGS --}}
    <div id="tabContentLogs" class="mp-table-card" style="display:none;">
        <div style="overflow-x:auto;">
            <table class="mp-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Order</th>
                        <th>Event &amp; Creator</th>
                        <th>Harga Tiket</th>
                        <th>Qty</th>
                        <th>Gross Omset</th>
                        <th>% Komisi</th>
                        <th>Nominal Komisi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commissionLogs as $log)
                        <tr>
                            <td style="white-space:nowrap; color:#64748B; font-size:0.72rem;">
                                {{ $log->created_at->format('d M Y H:i') }}
                            </td>
                            <td>
                                <strong style="font-family:monospace; color:#0F172A;">#{{ $log->order_id }}</strong>
                            </td>
                            <td>
                                <div style="font-weight:700; color:#0F172A;">{{ $log->event_name }}</div>
                                <div style="font-size:0.68rem; color:#64748B;">EO: {{ $log->creator?->name }}</div>
                            </td>
                            <td>
                                Rp {{ number_format($log->ticket_price, 0, ',', '.') }}
                            </td>
                            <td>
                                <strong>{{ $log->quantity }}x</strong>
                            </td>
                            <td>
                                Rp {{ number_format($log->gross_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                <span style="font-weight:700; color:#1D4ED8;">{{ $log->commission_rate }}%</span>
                            </td>
                            <td>
                                <strong style="color:#16A34A; font-size:0.85rem;">
                                    Rp {{ number_format($log->commission_amount, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <span style="font-size:0.65rem; font-weight:800; padding:2px 6px; border-radius:4px; background:{{ $log->status === 'paid' ? '#DCFCE7' : '#FEF3C7' }}; color:{{ $log->status === 'paid' ? '#15803D' : '#B45309' }};">
                                    {{ strtoupper($log->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center; padding:3rem; color:#94A3B8;">
                                Belum ada transaksi penjualan tiket dari EO yang terafiliasi dengan Media Partner ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($commissionLogs->hasPages())
            <div style="padding:1rem; border-top:1px solid #F1F5F9; display:flex; justify-content:flex-end;">
                {{ $commissionLogs->links() }}
            </div>
        @endif
    </div>

</div>

<script>
function switchTab(tab) {
    if (tab === 'creators') {
        document.getElementById('tabContentCreators').style.display = 'block';
        document.getElementById('tabContentLogs').style.display = 'none';
        document.getElementById('tabBtnCreators').classList.add('active');
        document.getElementById('tabBtnLogs').classList.remove('active');
    } else {
        document.getElementById('tabContentCreators').style.display = 'none';
        document.getElementById('tabContentLogs').style.display = 'block';
        document.getElementById('tabBtnLogs').classList.add('active');
        document.getElementById('tabBtnCreators').classList.remove('active');
    }
}
</script>
@endsection
