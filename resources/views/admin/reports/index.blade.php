@extends('layouts.admin')
@section('title', 'Laporan Penyalahgunaan')
@section('page-title', 'Laporan Penyalahgunaan & Pelanggaran')
@section('content')

<style>
.rp-page { font-family: 'Montserrat', sans-serif; }
.rp-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.rp-title { font-size: 1.5rem; font-weight: 800; color: #0F172A; margin: 0 0 .2rem; }
.rp-sub { font-size: .85rem; color: #64748B; margin: 0; }

.rp-tabs { display: flex; gap: .5rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.rp-tab-btn {
    padding: .55rem 1.1rem; border-radius: 12px; font-size: .82rem; font-weight: 700;
    text-decoration: none; border: 1.5px solid #E2E8F0; color: #64748B; background: #fff;
    transition: all .2s; display: inline-flex; align-items: center; gap: .4rem;
}
.rp-tab-btn:hover { border-color: #CBD5E1; color: #1E293B; }
.rp-tab-btn.active { background: #0F172A; color: #fff; border-color: #0F172A; }
.rp-count-badge { background: rgba(255,255,255,0.2); padding: .15rem .45rem; border-radius: 20px; font-size: .75rem; }
.rp-tab-btn:not(.active) .rp-count-badge { background: #F1F5F9; color: #475569; }

.rp-card { background: #fff; border-radius: 18px; border: 1.5px solid #E2E8F0; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
.rp-table { width: 100%; border-collapse: collapse; text-align: left; font-size: .83rem; }
.rp-table th { background: #F8FAFC; color: #475569; font-weight: 700; padding: .85rem 1.2rem; border-bottom: 1.5px solid #E2E8F0; text-transform: uppercase; font-size: .72rem; letter-spacing: 0.05em; }
.rp-table td { padding: 1rem 1.2rem; border-bottom: 1px solid #F1F5F9; color: #334155; vertical-align: top; }
.rp-table tr:hover td { background: #F8FAFC; }

.badge-st { padding: .3rem .75rem; border-radius: 20px; font-size: .73rem; font-weight: 700; display: inline-flex; align-items: center; gap: .35rem; }
.st-pending { background: #FEF3C7; color: #D97706; }
.st-reviewed { background: #E0F2FE; color: #0284C7; }
.st-resolved { background: #DCFCE7; color: #16A34A; }
.st-dismissed { background: #F1F5F9; color: #64748B; }

.btn-action { background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; padding: .4rem .75rem; border-radius: 8px; font-weight: 600; font-size: .75rem; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: .3rem; text-decoration: none; }
.btn-action:hover { background: #E2E8F0; }
</style>

<div class="rp-page">
    <div class="rp-head">
        <div>
            <h1 class="rp-title">Laporan Penyalahgunaan (Abuse Reports)</h1>
            <p class="rp-sub">Daftar laporan pelanggaran konten, penipuan, atau hak cipta yang dikirimkan oleh audiens.</p>
        </div>
        <form method="GET" action="{{ route('admin.reports.index') }}" style="display:flex; gap:.5rem;">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari URL / email / deskripsi..." style="padding:.5rem .9rem; border-radius:10px; border:1.5px solid #CBD5E1; font-size:.82rem; outline:none;">
            <button type="submit" style="background:#0F172A; color:#fff; border:none; padding:.5rem 1rem; border-radius:10px; font-weight:700; font-size:.82rem; cursor:pointer;">Cari</button>
        </form>
    </div>

    <!-- Tab Filter -->
    <div class="rp-tabs">
        <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" class="rp-tab-btn {{ $status === 'pending' ? 'active' : '' }}">
            Menunggu Action <span class="rp-count-badge">{{ $counts['pending'] }}</span>
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'reviewed']) }}" class="rp-tab-btn {{ $status === 'reviewed' ? 'active' : '' }}">
            Sedang Direview <span class="rp-count-badge">{{ $counts['reviewed'] }}</span>
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}" class="rp-tab-btn {{ $status === 'resolved' ? 'active' : '' }}">
            Selesai / Resolved <span class="rp-count-badge">{{ $counts['resolved'] }}</span>
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'dismissed']) }}" class="rp-tab-btn {{ $status === 'dismissed' ? 'active' : '' }}">
            Ditolak / Dismissed <span class="rp-count-badge">{{ $counts['dismissed'] }}</span>
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'all']) }}" class="rp-tab-btn {{ $status === 'all' ? 'active' : '' }}">
            Semua <span class="rp-count-badge">{{ $counts['all'] }}</span>
        </a>
    </div>

    @if(session('success'))
        <div style="background:#DCFCE7; border:1px solid #86EFAC; color:#15803D; padding:.75rem 1.25rem; border-radius:12px; margin-bottom:1.25rem; font-size:.85rem; font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="rp-card">
        <table class="rp-table">
            <thead>
                <tr>
                    <th>Waktu & Pelapor</th>
                    <th>Target Halaman / URL</th>
                    <th>Kategori / Alasan</th>
                    <th>Detail Laporan</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td style="width:170px;">
                        <strong style="color:#0F172A; font-size:.82rem; display:block;">{{ $report->created_at->format('d M Y, H:i') }}</strong>
                        <div style="font-size:.75rem; color:#64748B;">IP: {{ $report->reporter_ip ?? '-' }}</div>
                        <div style="font-size:.75rem; color:#2563EB;">{{ $report->reporter_email ?? 'Anonim' }}</div>
                    </td>
                    <td>
                        @if($report->target_name)
                            <div style="font-weight:700; color:#0F172A; margin-bottom:.2rem;">{{ $report->target_name }}</div>
                        @endif
                        <a href="{{ $report->target_url }}" target="_blank" style="color:#2563EB; font-size:.78rem; text-decoration:underline; word-break:break-all;">
                            {{ $report->target_url }}
                        </a>
                        <div style="font-size:.7rem; color:#94A3B8; margin-top:.2rem;">Tipe: {{ strtoupper($report->report_type) }}</div>
                    </td>
                    <td style="width:160px;">
                        <span style="background:#F1F5F9; color:#0F172A; padding:.25rem .6rem; border-radius:6px; font-weight:700; font-size:.73rem; display:inline-block;">
                            {{ $report->reason_label }}
                        </span>
                    </td>
                    <td style="max-width:280px;">
                        <div style="font-size:.8rem; color:#334155; white-space:pre-line;">{{ $report->description ?? '-' }}</div>
                        @if($report->admin_notes)
                            <div style="margin-top:.4rem; padding:.4rem .6rem; background:#FEF3C7; border-radius:6px; font-size:.73rem; color:#92400E;">
                                <strong>Catatan Admin:</strong> {{ $report->admin_notes }}
                            </div>
                        @endif
                    </td>
                    <td style="width:140px;">
                        @if($report->status === 'pending')
                            <span class="badge-st st-pending">Pending</span>
                        @elseif($report->status === 'reviewed')
                            <span class="badge-st st-reviewed">Direview</span>
                        @elseif($report->status === 'resolved')
                            <span class="badge-st st-resolved">Selesai</span>
                        @else
                            <span class="badge-st st-dismissed">Ditolak</span>
                        @endif
                    </td>
                    <td style="text-align:right; width:130px;">
                        <div style="display:flex; flex-direction:column; gap:.3rem; align-items:flex-end;">
                            <button type="button" class="btn-action" onclick="openUpdateModal({{ $report->id }}, '{{ $report->status }}', '{{ addslashes($report->admin_notes ?? '') }}')">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit Status
                            </button>
                            <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" onsubmit="return confirm('Hapus laporan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:#EF4444; font-size:.72rem; cursor:pointer; font-weight:600;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:3rem; color:#94A3B8;">
                        Tidak ada laporan penyalahgunaan pada status ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $reports->links() }}
    </div>
</div>

<!-- Modal Update Status -->
<div id="updateModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:18px; padding:1.75rem; width:100%; max-width:440px; box-shadow:0 10px 25px rgba(0,0,0,0.2); font-family:'Montserrat',sans-serif;">
        <h3 style="margin:0 0 .5rem; font-weight:800; color:#0F172A; font-size:1.1rem;">Update Status Laporan</h3>
        <form id="updateForm" method="POST">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="font-size:.78rem; font-weight:700; color:#334155; display:block; margin-bottom:.4rem;">Status Laporan</label>
                <select name="status" id="modalStatus" style="width:100%; border:1.5px solid #CBD5E1; border-radius:10px; padding:.6rem .8rem; font-size:.82rem; outline:none; font-family:'Montserrat',sans-serif;">
                    <option value="pending">Pending (Menunggu Action)</option>
                    <option value="reviewed">Direview (Sedang Diproses)</option>
                    <option value="resolved">Resolved (Tindakan Selesai / Disetujui)</option>
                    <option value="dismissed">Dismissed (Laporan Ditolak / Tidak Valid)</option>
                </select>
            </div>
            <div style="margin-bottom:1.2rem;">
                <label style="font-size:.78rem; font-weight:700; color:#334155; display:block; margin-bottom:.4rem;">Catatan Internal Admin</label>
                <textarea name="admin_notes" id="modalNotes" rows="3" placeholder="Catatan internal mengenai tindakan yang diambil..." style="width:100%; border:1.5px solid #CBD5E1; border-radius:10px; padding:.7rem; font-size:.82rem; outline:none; font-family:'Montserrat',sans-serif;"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:.5rem;">
                <button type="button" onclick="closeUpdateModal()" style="background:#F1F5F9; color:#475569; border:none; padding:.55rem 1rem; border-radius:10px; font-weight:700; font-size:.8rem; cursor:pointer;">Batal</button>
                <button type="submit" style="background:#0F172A; color:#fff; border:none; padding:.55rem 1.1rem; border-radius:10px; font-weight:700; font-size:.8rem; cursor:pointer;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUpdateModal(id, status, notes) {
    document.getElementById('updateForm').action = '/admin/reports/' + id + '/status';
    document.getElementById('modalStatus').value = status;
    document.getElementById('modalNotes').value = notes;
    document.getElementById('updateModal').style.display = 'flex';
}
function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
}
</script>

@endsection
