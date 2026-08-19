@extends('creator.layout')

@section('title', 'Kelompok Produk Digital')
@section('page_title', 'Kelompok Produk')
@section('breadcrumb', 'Katalog › Kelompok')

@section('topbar_actions')
<a href="{{ route('creator.groups.create') }}" class="btn-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Buat Kelompok Baru
</a>
@endsection

@section('content')
<div style="background:#fff; border-radius:16px; border:1px solid #e7f0e7; box-shadow:0 2px 8px rgba(0,0,0,0.04); overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:0.875rem; text-align:left;">
        <thead>
            <tr style="background:#f9fefb; border-bottom:1px solid #e7f0e7;">
                <th style="padding:1rem 1.5rem; font-weight:700; color:#64748B; width:50px;">No</th>
                <th style="padding:1rem 1.5rem; font-weight:700; color:#64748B;">Nama Kelompok</th>
                <th style="padding:1rem 1.5rem; font-weight:700; color:#64748B;">Slug</th>
                <th style="padding:1rem 1.5rem; font-weight:700; color:#64748B; text-align:center;">Urutan</th>
                <th style="padding:1rem 1.5rem; font-weight:700; color:#64748B; text-align:center;">Status</th>
                <th style="padding:1rem 1.5rem; font-weight:700; color:#64748B; text-align:right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $i => $grp)
            <tr style="border-bottom:1px solid #f3f7f3; transition:background 0.2s;" onmouseover="this.style.background='#fcfdfc'" onmouseout="this.style.background='transparent'">
                <td style="padding:1rem 1.5rem; color:#64748B;">{{ $i+1 }}</td>
                <td style="padding:1rem 1.5rem; font-weight:600; color:#1a1a1a;">
                    {{ $grp->name }}
                    <div style="font-size:0.7rem; color:#94A3B8; font-weight:500; margin-top:0.2rem;">{{ $grp->products()->count() }} produk</div>
                </td>
                <td style="padding:1rem 1.5rem; color:#64748B; font-family:monospace; font-size:0.8rem;">/{{ $grp->slug }}</td>
                <td style="padding:1rem 1.5rem; color:#64748B; text-align:center;">{{ $grp->order }}</td>
                <td style="padding:1rem 1.5rem; text-align:center;">
                    @if($grp->is_active)
                        <span style="display:inline-block; padding:0.2rem 0.6rem; border-radius:6px; background:#dcfce7; color:#15803d; font-size:0.7rem; font-weight:700;">Aktif</span>
                    @else
                        <span style="display:inline-block; padding:0.2rem 0.6rem; border-radius:6px; background:#f1f5f9; color:#64748B; font-size:0.7rem; font-weight:700;">Nonaktif</span>
                    @endif
                </td>
                <td style="padding:1rem 1.5rem; text-align:right;">
                    <div style="display:flex; align-items:center; justify-content:flex-end; gap:0.5rem;">
                        <a href="{{ route('creator.groups.edit', $grp) }}" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#f1f5f9; color:#475569; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#e2e8f0'; this.style.color='#0f172a'">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        <form action="{{ route('creator.groups.destroy', $grp) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus kelompok ini? Produk di dalamnya tidak akan terhapus, hanya tidak masuk kelompok.');">
                            @csrf @method('DELETE')
                            <button type="submit" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#fef2f2; border:none; color:#ef4444; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.background='#fee2e2'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:3rem 1.5rem; text-align:center; color:#94A3B8;">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem; opacity:0.5;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                    <p style="margin:0; font-weight:500;">Belum ada kelompok produk yang dibuat.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
