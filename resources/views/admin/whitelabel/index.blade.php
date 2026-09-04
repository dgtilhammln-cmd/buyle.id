@extends('layouts.admin')
@section('title', 'Approval Produk White Label')
@section('page-title', 'Persetujuan White Label (Buyle Team)')
@section('content')

<style>
.wl-page { font-family: 'Montserrat', sans-serif; }
.wl-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.wl-title { font-size: 1.5rem; font-weight: 800; color: #0F172A; margin: 0 0 .2rem; }
.wl-sub { font-size: .85rem; color: #64748B; margin: 0; }

.wl-tabs { display: flex; gap: .5rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.wl-tab-btn {
    padding: .55rem 1.1rem; border-radius: 12px; font-size: .82rem; font-weight: 700;
    text-decoration: none; border: 1.5px solid #E2E8F0; color: #64748B; background: #fff;
    transition: all .2s; display: inline-flex; align-items: center; gap: .4rem;
}
.wl-tab-btn:hover { border-color: #CBD5E1; color: #1E293B; }
.wl-tab-btn.active { background: #0F172A; color: #fff; border-color: #0F172A; }
.wl-count-badge {
    background: rgba(255,255,255,0.2); padding: .15rem .45rem; border-radius: 20px; font-size: .75rem;
}
.wl-tab-btn:not(.active) .wl-count-badge { background: #F1F5F9; color: #475569; }

.wl-table-card { background: #fff; border-radius: 18px; border: 1.5px solid #E2E8F0; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
.wl-table { width: 100%; border-collapse: collapse; text-align: left; font-size: .83rem; }
.wl-table th { background: #F8FAFC; color: #475569; font-weight: 700; padding: .85rem 1.2rem; border-bottom: 1.5px solid #E2E8F0; text-transform: uppercase; font-size: .72rem; letter-spacing: 0.05em; }
.wl-table td { padding: 1rem 1.2rem; border-bottom: 1px solid #F1F5F9; color: #334155; vertical-align: middle; }
.wl-table tr:hover td { background: #F8FAFC; }

.badge-status { padding: .3rem .75rem; border-radius: 20px; font-size: .73rem; font-weight: 700; display: inline-flex; align-items: center; gap: .35rem; }
.badge-pending { background: #FEF3C7; color: #D97706; }
.badge-approved { background: #DCFCE7; color: #16A34A; }
.badge-rejected { background: #FEE2E2; color: #DC2626; }

.btn-approve { background: #16A34A; color: #fff; border: none; padding: .45rem .85rem; border-radius: 8px; font-weight: 700; font-size: .75rem; cursor: pointer; transition: background .2s; display: inline-flex; align-items: center; gap: .3rem; }
.btn-approve:hover { background: #15803D; }
.btn-reject { background: #EF4444; color: #fff; border: none; padding: .45rem .85rem; border-radius: 8px; font-weight: 700; font-size: .75rem; cursor: pointer; transition: background .2s; display: inline-flex; align-items: center; gap: .3rem; }
.btn-reject:hover { background: #DC2626; }
.btn-resource { background: #F1F5F9; color: #2563EB; text-decoration: none; padding: .35rem .75rem; border-radius: 8px; font-weight: 600; font-size: .75rem; display: inline-flex; align-items: center; gap: .35rem; }
.btn-resource:hover { background: #DBEAFE; }
</style>

<div class="wl-page">
    <div class="wl-head">
        <div>
            <h1 class="wl-title">Review & Approval White Label</h1>
            <p class="wl-sub">Verifikasi aset produk agar bebas watermark dan siap dijual kembali oleh reseller.</p>
        </div>
        <form method="GET" action="{{ route('admin.whitelabel.index') }}" style="display:flex; gap:.5rem;">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk / creator..." style="padding:.5rem .9rem; border-radius:10px; border:1.5px solid #CBD5E1; font-size:.82rem; outline:none;">
            <button type="submit" style="background:#0F172A; color:#fff; border:none; padding:.5rem 1rem; border-radius:10px; font-weight:700; font-size:.82rem; cursor:pointer;">Cari</button>
        </form>
    </div>

    <!-- Tab Filter -->
    <div class="wl-tabs">
        <a href="{{ route('admin.whitelabel.index', ['status' => 'pending']) }}" class="wl-tab-btn {{ $status === 'pending' ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Menunggu Review <span class="wl-count-badge">{{ $counts['pending'] }}</span>
        </a>
        <a href="{{ route('admin.whitelabel.index', ['status' => 'approved']) }}" class="wl-tab-btn {{ $status === 'approved' ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Disetujui <span class="wl-count-badge">{{ $counts['approved'] }}</span>
        </a>
        <a href="{{ route('admin.whitelabel.index', ['status' => 'rejected']) }}" class="wl-tab-btn {{ $status === 'rejected' ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Ditolak <span class="wl-count-badge">{{ $counts['rejected'] }}</span>
        </a>
        <a href="{{ route('admin.whitelabel.index', ['status' => 'all']) }}" class="wl-tab-btn {{ $status === 'all' ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            Semua
        </a>
    </div>

    <div class="wl-table-card">
        <table class="wl-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Creator / Penjual</th>
                    <th>Harga & Markup</th>
                    <th>Aset / Link Digital</th>
                    <th>Status Approval</th>
                    <th style="text-align:right;">Aksi Persetujuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:.8rem;">
                            <img src="{{ $product->main_image }}" style="width:48px; height:48px; object-fit:cover; border-radius:10px; border:1px solid #E2E8F0;">
                            <div>
                                <strong style="color:#0F172A; font-size:.88rem; display:block;">{{ $product->name }}</strong>
                                <span style="font-size:.75rem; color:#94A3B8;">Kategori: {{ $product->category->name ?? 'Umum' }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong style="color:#1E293B;">{{ $product->seller->name ?? 'Sistem' }}</strong>
                        <div style="font-size:.75rem; color:#64748B;">{{ $product->seller->email ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:700; color:#0F172A;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @if($product->whitelabel_price)
                            <div style="font-size:.73rem; color:#16A34A;">Harga Modal WL: Rp {{ number_format($product->whitelabel_price, 0, ',', '.') }}</div>
                        @endif
                    </td>
                    <td>
                        @if($product->digital_resource)
                            <a href="{{ $product->digital_resource }}" target="_blank" class="btn-resource">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                Cek Link File Aset
                            </a>
                        @else
                            <span style="color:#94A3B8; font-size:.75rem;">Tidak Ada Link</span>
                        @endif
                    </td>
                    <td>
                        @if($product->whitelabel_approval_status === 'pending')
                            <span class="badge-status badge-pending">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Menunggu Review
                            </span>
                        @elseif($product->whitelabel_approval_status === 'approved')
                            <span class="badge-status badge-approved">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                Disetujui (Approved)
                            </span>
                        @else
                            <span class="badge-status badge-rejected">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                Ditolak
                            </span>
                            @if($product->whitelabel_rejection_reason)
                                <div style="font-size:.72rem; color:#DC2626; margin-top:.2rem; max-width:200px;">
                                    Catatan: {{ $product->whitelabel_rejection_reason }}
                                </div>
                            @endif
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <div style="display:inline-flex; gap:.4rem; justify-content:flex-end;">
                            @if($product->whitelabel_approval_status !== 'approved')
                            <form method="POST" action="{{ route('admin.whitelabel.approve', $product) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui produk ini sebagai White Label?')">
                                @csrf
                                <button type="submit" class="btn-approve">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    Approve
                                </button>
                            </form>
                            @endif

                            @if($product->whitelabel_approval_status !== 'rejected')
                            <button type="button" class="btn-reject" onclick="openRejectModal({{ $product->id }}, '{{ addslashes($product->name) }}')">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                Reject
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:3rem; color:#94A3B8;">
                        Tidak ada pengajuan produk White Label pada status ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $products->links() }}
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:18px; padding:1.75rem; width:100%; max-width:440px; box-shadow:0 10px 25px rgba(0,0,0,0.2); font-family:'Montserrat',sans-serif;">
        <h3 style="margin:0 0 .5rem; font-weight:800; color:#0F172A; font-size:1.1rem;">Tolak Pengajuan White Label</h3>
        <p style="font-size:.82rem; color:#64748B; margin-bottom:1rem;" id="rejectProdName"></p>
        <form id="rejectForm" method="POST">
            @csrf
            <div style="margin-bottom:1.2rem;">
                <label style="font-size:.78rem; font-weight:700; color:#334155; display:block; margin-bottom:.4rem;">Alasan Penolakan / Catatan Perbaikan</label>
                <textarea name="reason" rows="3" placeholder="Misal: File Google Drive masih memerlukan akses / Terdapat logo personal di slide 2..." style="width:100%; border:1.5px solid #CBD5E1; border-radius:10px; padding:.7rem; font-size:.82rem; outline:none; font-family:'Montserrat',sans-serif;"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:.5rem;">
                <button type="button" onclick="closeRejectModal()" style="background:#F1F5F9; color:#475569; border:none; padding:.55rem 1rem; border-radius:10px; font-weight:700; font-size:.8rem; cursor:pointer;">Batal</button>
                <button type="submit" style="background:#DC2626; color:#fff; border:none; padding:.55rem 1.1rem; border-radius:10px; font-weight:700; font-size:.8rem; cursor:pointer;">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(id, name) {
    document.getElementById('rejectProdName').innerText = 'Produk: ' + name;
    document.getElementById('rejectForm').action = '/admin/whitelabel-approval/' + id + '/reject';
    document.getElementById('rejectModal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>

@endsection
