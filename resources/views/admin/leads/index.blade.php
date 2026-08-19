@extends('layouts.admin')
@section('title','Laporan Chat CS & Leads')
@section('page-title','Laporan Chat CS & Leads')
@section('content')

<style>
/* Premium UI for Leads Index */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 2rem; }
.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.06); }
.stat-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.stat-val { font-size: 1.75rem; font-weight: 800; color: #1E293B; line-height: 1; margin-bottom: 0.25rem; }
.stat-label { font-size: 0.75rem; color: #64748B; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }

.table-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    border: 1px solid #E2E8F0;
    overflow: hidden;
}
.table-header-row {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #F1F5F9;
    display: flex; align-items: center; justify-content: space-between;
}
.th-cell {
    padding: 1rem 1.25rem;
    text-align: left;
    font-size: 0.7rem;
    font-weight: 700;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: #F8FAFC;
    border-bottom: 1px solid #F1F5F9;
    white-space: nowrap;
}
.td-cell {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
}
.tr-row { transition: background 0.15s; }
.tr-row:hover { background: #FAFBFF; }

.filter-input {
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
    background: #fff;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    color: #1E293B;
    font-family: inherit;
    outline: none;
    transition: all 0.2s;
}
.filter-input:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }

.export-btn {
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    text-decoration: none;
}
.export-xls { background: rgba(34,197,94,0.1); color: #16A34A; border: 1px solid rgba(34,197,94,0.2); }
.export-xls:hover { background: rgba(34,197,94,0.2); transform: translateY(-1px); }
.export-pdf { background: rgba(239,68,68,0.1); color: #DC2626; border: 1px solid rgba(239,68,68,0.2); }
.export-pdf:hover { background: rgba(239,68,68,0.2); transform: translateY(-1px); }

@media(max-width:1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width:640px) {
    .stats-grid { grid-template-columns: 1fr; }
}
</style>

{{-- Page Header --}}
<div style="margin-bottom:2rem; display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:1.5rem;">
    <div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Laporan Chat CS & Leads</h1>
        <p style="font-size:.875rem;color:#64748B;margin:0;">Daftar permintaan order dan kontak masuk dari website</p>
    </div>
    
    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:0.5rem;">
        <div style="display:flex; gap:.75rem; align-items:center; flex-wrap:wrap;">
            <input type="date" id="lead-start" class="filter-input">
            <span style="color:#94A3B8;font-size:0.8rem;font-weight:600;">s/d</span>
            <input type="date" id="lead-end" class="filter-input">
            <div style="width:1px; background:#E2E8F0; margin:0 .25rem; height:24px;"></div>
            <button onclick="downloadLeads('xls')" class="export-btn export-xls">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4m7-7l5-5 5 5m-5-5v12"/></svg>
                XLS
            </button>
            <button onclick="downloadLeads('pdf')" class="export-btn export-pdf">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4m7-7l5-5 5 5m-5-5v12"/></svg>
                PDF
            </button>
        </div>
        <div style="font-size:.7rem; color:#94A3B8; font-style:italic;">
            *Pilih rentang tanggal terlebih dahulu sebelum men-download laporan
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="stats-grid">
  @foreach([
    ['Total Laporan', $stats['total'], '#3B82F6', 'rgba(59,130,246,0.1)', 'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z M22,6 12,13 2,6'],
    ['Hari Ini', $stats['today'], '#10B981', 'rgba(16,213,129,0.1)', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ['Bulan Ini', $stats['this_month'], '#8B5CF6', 'rgba(139,92,246,0.1)', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
    ['Belum Ditindak', $stats['new'], '#F59E0B', 'rgba(245,158,11,0.1)', 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
  ] as $sc)
  <div class="stat-card">
    <div class="stat-icon" style="background:{{ $sc[3] }}; color:{{ $sc[2] }};">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="{{ $sc[4] }}"/></svg>
    </div>
    <div>
        <div class="stat-val">{{ number_format($sc[1]) }}</div>
        <div class="stat-label">{{ $sc[0] }}</div>
    </div>
  </div>
  @endforeach
</div>

{{-- Leads Table --}}
<div class="table-card">
  <div class="table-header-row">
    <div>
      <div style="font-size:1.125rem;font-weight:800;color:#1E293B;letter-spacing:-.01em;">Semua Chat & Leads</div>
      <div style="font-size:.8rem;color:#64748B;margin-top:.15rem;">{{ $leads->total() }} total entri data</div>
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr>
          @foreach(['#','Nama & Perusahaan','Telepon / Email','Produk','Sumber','Perangkat','Waktu','Status','Aksi'] as $h)
          <th class="th-cell">{{ $h }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @forelse($leads as $lead)
        <tr class="tr-row">
          <td class="td-cell" style="font-size:.75rem;color:#94A3B8;font-weight:600;">{{ $lead->id }}</td>
          <td class="td-cell">
            <div style="font-size:.875rem;font-weight:700;color:#1E293B;">{{ $lead->name }}</div>
            @if($lead->company)<div style="font-size:.75rem;color:#64748B;margin-top:2px;">{{ $lead->company }}</div>@endif
          </td>
          <td class="td-cell">
            <div style="font-size:.85rem;color:#334155;font-weight:600;white-space:nowrap;">{{ $lead->phone }}</div>
            @if($lead->email)<div style="font-size:.75rem;color:#3B82F6;margin-top:2px;">{{ $lead->email }}</div>@endif
          </td>
          <td class="td-cell" style="font-size:.85rem;color:#475569;max-width:160px;">
            <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block;">{{ $lead->product ?: '—' }}</span>
          </td>
          <td class="td-cell">
            @php
              $src = $lead->source ?? 'Website';
              $srcColor = match(true) {
                str_contains($src,'Hero')     => '#EAB308', // Yellow
                str_contains($src,'Navbar')   => '#3B82F6', // Blue
                str_contains($src,'Footer')   => '#8B5CF6', // Purple
                str_contains($src,'Artikel')  => '#F97316', // Orange
                str_contains($src,'Layanan')  => '#10B981', // Green
                str_contains($src,'Galeri')   => '#EC4899', // Pink
                str_contains($src,'Kontak')   => '#06B6D4', // Cyan
                str_contains($src,'Floating') => '#22C55E', // WhatsApp Green
                default                       => '#64748B', // Slate
              };
            @endphp
            <span style="font-size:.7rem;font-weight:600;padding:.25rem .625rem;border-radius:100px;background:{{ $srcColor }}15;color:{{ $srcColor }};white-space:nowrap;">
              {{ Str::limit($src, 20) }}
            </span>
          </td>
          <td class="td-cell" style="font-size:.8rem;color:#64748B;white-space:nowrap;">
            <div style="display:flex;align-items:center;gap:0.35rem;">
                @if(strtolower($lead->device_type) == 'mobile')
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                @else
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                @endif
                {{ ucfirst($lead->device_type ?: 'Unknown') }}
            </div>
          </td>
          <td class="td-cell" style="font-size:.8rem;color:#475569;white-space:nowrap;font-weight:500;">
            {{ $lead->created_at->diffForHumans() }}
            <div style="font-size:0.7rem;color:#94A3B8;margin-top:2px;font-weight:400;">{{ $lead->created_at->format('d/m/Y H:i') }}</div>
          </td>
          <td class="td-cell">
            @php
              $sc = match($lead->status) {
                'new'       => ['#F59E0B','rgba(245,158,11,.1)','Baru'],
                'contacted' => ['#3B82F6','rgba(59,130,246,.1)','Diproses'],
                'closed'    => ['#10B981','rgba(16,213,129,.1)','Selesai'],
                default     => ['#64748B','rgba(100,116,139,.1)','Lainnya'],
              };
            @endphp
            <span style="background:{{ $sc[1] }};color:{{ $sc[0] }};font-size:.7rem;font-weight:700;padding:.3rem .75rem;border-radius:100px;white-space:nowrap;display:inline-block;">{{ $sc[2] }}</span>
          </td>
          <td class="td-cell">
            <div style="display:flex;gap:.75rem;align-items:center;">
              <a href="{{ route('admin.leads.show',$lead) }}" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:#F1F5F9;color:#3B82F6;border-radius:8px;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#3B82F6';this.style.color='#fff';" onmouseout="this.style.background='#F1F5F9';this.style.color='#3B82F6';">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" title="Lihat"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
              
              @if($lead->wa_customer)
              <a href="https://wa.me/{{ $lead->wa_customer }}?text={{ urlencode('Follow up lead: '.$lead->name.' - '.$lead->phone) }}" target="_blank" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:rgba(34,197,94,0.1);color:#16A34A;border-radius:8px;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#22C55E';this.style.color='#fff';" onmouseout="this.style.background='rgba(34,197,94,0.1)';this.style.color='#16A34A';">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" title="WhatsApp"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
              </a>
              @endif
              
              <form method="POST" action="{{ route('admin.leads.destroy',$lead) }}" onsubmit="return confirm('Hapus lead ini secara permanen?')" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:rgba(239,68,68,0.1);color:#DC2626;border-radius:8px;border:none;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#EF4444';this.style.color='#fff';" onmouseout="this.style.background='rgba(239,68,68,0.1)';this.style.color='#DC2626';">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" title="Hapus"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" style="padding:4rem;text-align:center;color:#94A3B8;">
            <div style="width:64px;height:64px;background:#F8FAFC;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <svg width="32" height="32" fill="none" stroke="#CBD5E1" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div style="font-size:1.125rem;font-weight:700;color:#475569;margin-bottom:0.25rem;">Belum Ada Chat/Leads Masuk</div>
            <p style="font-size:0.875rem;margin:0;">Saat ini belum ada data permintaan order atau pesan yang masuk.</p>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div style="margin-top:1.5rem;">
    {{ $leads->links() }}
</div>

@push('scripts')
<script>
function downloadLeads(format) {
    const start = document.getElementById('lead-start').value;
    const end = document.getElementById('lead-end').value;
    if (!start || !end) {
        alert('Mohon isi rentang tanggal (s/d) terlebih dahulu sebelum men-download laporan!');
        return;
    }
    if (format === 'xls') {
        window.location.href = `/admin/leads/export?start_date=${start}&end_date=${end}`;
    } else {
        window.open(`/admin/leads/export-pdf?start_date=${start}&end_date=${end}`, '_blank');
    }
}
</script>
@endpush
@endsection
