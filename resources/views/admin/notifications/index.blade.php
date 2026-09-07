@extends('layouts.admin')

@section('title', 'Pusat Notifikasi')
@section('page-title', 'Pusat Notifikasi')

@section('content')
<div style="max-width:1000px; margin:0 auto;">

  {{-- HEADER --}}
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
    <div>
      <h1 style="font-size:1.5rem; font-weight:800; color:#1E293B; margin:0 0 .25rem; letter-spacing:-.02em;">Pusat Notifikasi</h1>
      <p style="font-size:.875rem; color:#94A3B8; margin:0;">Pantau aktivitas akun baru, pembayaran, approval whitelabel, dan leads terbaru.</p>
    </div>
  </div>

  {{-- FILTER TABS --}}
  <div style="display:flex; gap:.5rem; margin-bottom:1.5rem; flex-wrap:wrap; background:#fff; padding:6px; border-radius:16px; border:1px solid #E2E8F0; box-shadow:0 2px 10px rgba(0,0,0,0.02);">
    <a href="{{ route('admin.notifications.index', ['type' => 'all']) }}" 
       style="padding:.5rem 1rem; border-radius:10px; font-size:.825rem; font-weight:700; text-decoration:none; transition:all .2s; {{ $filterType === 'all' ? 'background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; box-shadow:0 4px 12px rgba(30,179,73,0.3);' : 'color:#64748B;' }}">
      Semua ({{ $counts['all'] }})
    </a>
    <a href="{{ route('admin.notifications.index', ['type' => 'user']) }}" 
       style="padding:.5rem 1rem; border-radius:10px; font-size:.825rem; font-weight:700; text-decoration:none; transition:all .2s; {{ $filterType === 'user' ? 'background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; box-shadow:0 4px 12px rgba(30,179,73,0.3);' : 'color:#64748B;' }}">
      👤 Akun Baru ({{ $counts['user'] }})
    </a>
    <a href="{{ route('admin.notifications.index', ['type' => 'payment']) }}" 
       style="padding:.5rem 1rem; border-radius:10px; font-size:.825rem; font-weight:700; text-decoration:none; transition:all .2s; {{ $filterType === 'payment' ? 'background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; box-shadow:0 4px 12px rgba(30,179,73,0.3);' : 'color:#64748B;' }}">
      💳 Pembayaran Baru ({{ $counts['payment'] }})
    </a>
    <a href="{{ route('admin.notifications.index', ['type' => 'whitelabel']) }}" 
       style="padding:.5rem 1rem; border-radius:10px; font-size:.825rem; font-weight:700; text-decoration:none; transition:all .2s; {{ $filterType === 'whitelabel' ? 'background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; box-shadow:0 4px 12px rgba(30,179,73,0.3);' : 'color:#64748B;' }}">
      🏷️ Approval Whitelabel ({{ $counts['whitelabel'] }})
    </a>
    <a href="{{ route('admin.notifications.index', ['type' => 'lead']) }}" 
       style="padding:.5rem 1rem; border-radius:10px; font-size:.825rem; font-weight:700; text-decoration:none; transition:all .2s; {{ $filterType === 'lead' ? 'background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; box-shadow:0 4px 12px rgba(30,179,73,0.3);' : 'color:#64748B;' }}">
      📩 Leads ({{ $counts['lead'] }})
    </a>
  </div>

  {{-- NOTIFICATIONS LIST CARD --}}
  <div style="background:#fff; border-radius:20px; border:1px solid #E2E8F0; box-shadow:0 4px 20px rgba(0,0,0,0.03); overflow:hidden;">
    @forelse($filteredNotifs as $notif)
      <a href="{{ $notif->link }}" style="display:flex; align-items:flex-start; gap:1.25rem; padding:1.25rem 1.5rem; border-bottom:1px solid #F1F5F9; text-decoration:none; transition:background .2s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
        
        {{-- Icon --}}
        <div style="width:44px; height:44px; border-radius:14px; background:{{ $notif->icon_bg }}; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
          @if($notif->type === 'user')
            <svg width="20" height="20" fill="none" stroke="{{ $notif->icon_color }}" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          @elseif($notif->type === 'payment')
            <svg width="20" height="20" fill="none" stroke="{{ $notif->icon_color }}" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
          @elseif($notif->type === 'whitelabel')
            <svg width="20" height="20" fill="none" stroke="{{ $notif->icon_color }}" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
          @else
            <svg width="20" height="20" fill="none" stroke="{{ $notif->icon_color }}" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          @endif
        </div>

        {{-- Content --}}
        <div style="flex:1; min-width:0;">
          <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:.35rem; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:.5rem;">
              <span style="font-size:.7rem; font-weight:700; padding:.2rem .6rem; border-radius:6px; background:{{ $notif->icon_bg }}; color:{{ $notif->icon_color }}; text-transform:uppercase; letter-spacing:.03em;">
                {{ $notif->category }}
              </span>
              <h3 style="font-size:.925rem; font-weight:700; color:#1E293B; margin:0;">{{ $notif->title }}</h3>
            </div>
            <span style="font-size:.75rem; font-weight:500; color:#94A3B8; white-space:nowrap;">
              {{ \Carbon\Carbon::parse($notif->created_at)->locale('id')->diffForHumans() }}
            </span>
          </div>
          <p style="font-size:.825rem; color:#64748B; margin:0; line-height:1.5;">
            {{ $notif->subtitle }}
          </p>
        </div>

        {{-- Arrow --}}
        <div style="color:#CBD5E1; margin-top:10px;">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

      </a>
    @empty
      <div style="padding:4rem 2rem; text-align:center;">
        <div style="width:56px; height:56px; background:#F8FAFC; border-radius:50%; border:1px solid #E2E8F0; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
          <svg width="24" height="24" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <h3 style="font-size:1rem; font-weight:700; color:#1E293B; margin-bottom:.35rem;">Belum Ada Notifikasi</h3>
        <p style="font-size:.825rem; color:#94A3B8; margin:0;">Tidak ada aktivitas notifikasi dalam kategori ini.</p>
      </div>
    @endforelse
  </div>

</div>
@endsection
