@extends('creator.layout')

@section('title', 'Notifikasi — Buyle Creator')

@section('content')
<div class="cr-notif-container">

    {{-- Header: Back Button + Title "Notifikasi" + 3-dot Menu --}}
    <div class="cr-notif-header">
        <a href="{{ route('creator.dashboard') }}" class="cr-notif-back-btn" title="Kembali ke Dashboard">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>

        <h1 class="cr-notif-page-title">Notifikasi</h1>

        {{-- 3-dot Menu Button --}}
        <div class="cr-notif-menu-wrap" id="notifMenuWrap">
            <button type="button" class="cr-notif-menu-btn" id="notifMenuBtn" onclick="toggleNotifMenu()" title="Opsi">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="5" r="1.5"></circle>
                    <circle cx="12" cy="12" r="1.5"></circle>
                    <circle cx="12" cy="19" r="1.5"></circle>
                </svg>
            </button>

            {{-- Dropdown 3-dot --}}
            <div class="cr-notif-dropdown" id="notifMenuDropdown">
                @if($unreadCount > 0)
                <form action="{{ route('creator.notif.mark-all-read') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="cr-dd-item">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Tandai Semua Dibaca</span>
                    </button>
                </form>
                @endif

                @if($notifications->count() > 0)
                <form action="{{ route('creator.notif.delete-all') }}" method="POST" onsubmit="return confirm('Hapus semua riwayat notifikasi?')" style="margin:0;">
                    @csrf
                    <button type="submit" class="cr-dd-item danger">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <span>Hapus Semua</span>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Alert Flash Message --}}
    @if(session('success'))
    <div class="cr-alert-success">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Unread Counter Badge --}}
    @if($notifications->count() > 0 && $unreadCount > 0)
    <div class="cr-unread-bar">
        <span class="cr-unread-pill">{{ $unreadCount }} belum dibaca</span>
    </div>
    @endif

    {{-- Card List --}}
    <div class="cr-notif-card">
        @if($notifications->count() > 0)
        <div class="cr-notif-list">
            @foreach($notifications as $item)
            @php
                $isUnread = !$item->is_read;
                $type = $item->type ?? 'system';

                // Icon & Color
                $iconBg = '#f1f5f9'; $iconColor = '#475569';
                if ($type === 'order')  { $iconBg = 'rgba(16,185,129,.12)'; $iconColor = '#059669'; }
                elseif ($type === 'ticket') { $iconBg = 'rgba(14,165,233,.12)'; $iconColor = '#0284c7'; }
                elseif ($type === 'payout') { $iconBg = 'rgba(99,102,241,.12)'; $iconColor = '#4f46e5'; }
                elseif ($type === 'kyc')    { $iconBg = 'rgba(245,158,11,.12)'; $iconColor = '#d97706'; }

                // Waktu: gunakan format jam (H:i) saja — tidak perlu diffForHumans
                $timeLabel = $item->created_at->format('H:i');
            @endphp
            <a href="{{ route('creator.notif.read', $item->id) }}" class="cr-notif-item {{ $isUnread ? 'is-unread' : '' }}">

                {{-- Icon Bulat --}}
                <div class="cr-ni-icon" style="background:{{ $iconBg }};color:{{ $iconColor }};">
                    @if($type === 'order')
                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    @elseif($type === 'ticket')
                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/><path d="M13 5v14"/></svg>
                    @elseif($type === 'payout')
                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    @elseif($type === 'kyc')
                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    @else
                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    @endif
                </div>

                {{-- Konten: 2 baris — Judul lebar penuh, Pesan, Waktu di bawah judul --}}
                <div class="cr-ni-body">
                    <div class="cr-ni-title-row">
                        <span class="cr-ni-title">{{ $item->title }}</span>
                        @if($isUnread)
                            <span class="cr-ni-unread-dot"></span>
                        @endif
                    </div>
                    <p class="cr-ni-desc">{{ $item->message }}</p>
                    <span class="cr-ni-time">{{ $timeLabel }}</span>
                </div>

                {{-- Arrow --}}
                <div class="cr-ni-arrow">
                    <svg width="14" height="14" fill="none" stroke="#cbd5e1" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>

        @if($notifications->hasPages())
        <div class="cr-notif-pagination">
            {{ $notifications->links() }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="cr-notif-empty">
            <div class="cne-icon">
                <svg width="34" height="34" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
            </div>
            <h3 class="cne-title">Belum Ada Notifikasi</h3>
            <p class="cne-desc">Saat ada pesanan baru, tiket terjual, atau pembaruan toko, kami akan tampilkan di sini.</p>
            <a href="{{ route('creator.dashboard') }}" class="cne-btn">Kembali ke Dashboard</a>
        </div>
        @endif
    </div>

</div>

<script>
function toggleNotifMenu() {
    const dd = document.getElementById('notifMenuDropdown');
    const isOpen = dd.classList.contains('open');
    dd.classList.toggle('open', !isOpen);
}
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('notifMenuWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('notifMenuDropdown')?.classList.remove('open');
    }
});

// Simpan ke localStorage perangkat user
try {
    localStorage.setItem('buyle_cr_unread_notif', {{ (int)$unreadCount }});
    localStorage.setItem('buyle_cr_notif_last_sync', Date.now());
} catch(e) {}
</script>

<style>
/* ───── Container ───── */
.cr-notif-container {
    max-width: 680px;
    margin: 0 auto;
    padding: 0 1rem 5rem;
}

/* ───── Header ───── */
.cr-notif-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.9rem 0 1rem;
    position: relative;
}

.cr-notif-back-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0f172a;
    text-decoration: none;
    flex-shrink: 0;
    box-shadow: 0 1px 4px rgba(0,0,0,.03);
    transition: all .2s ease;
}
.cr-notif-back-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    transform: translateX(-1px);
}

.cr-notif-page-title {
    flex: 1;
    margin: 0;
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -.02em;
}

/* ───── 3-dot Menu ───── */
.cr-notif-menu-wrap {
    position: relative;
    flex-shrink: 0;
}

.cr-notif-menu-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #475569;
    cursor: pointer;
    box-shadow: 0 1px 4px rgba(0,0,0,.03);
    transition: all .2s ease;
}
.cr-notif-menu-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}

.cr-notif-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    min-width: 190px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 10px 36px rgba(0,0,0,.12), 0 2px 8px rgba(0,0,0,.04);
    overflow: hidden;
    z-index: 999;
    animation: ddFadeIn .15s ease;
}
.cr-notif-dropdown.open {
    display: block;
}
@keyframes ddFadeIn {
    from { opacity:0; transform:translateY(-6px); }
    to   { opacity:1; transform:translateY(0); }
}

.cr-dd-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    width: 100%;
    padding: 0.75rem 1rem;
    background: none;
    border: none;
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    text-align: left;
    transition: background .15s;
    font-family: inherit;
}
.cr-dd-item:hover {
    background: #f8fafc;
}
.cr-dd-item.danger {
    color: #dc2626;
}
.cr-dd-item.danger:hover {
    background: #fef2f2;
}

/* ───── Alert ───── */
.cr-alert-success {
    display: flex;
    align-items: center;
    gap: .65rem;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    padding: .7rem 1rem;
    border-radius: 12px;
    font-size: .84rem;
    font-weight: 600;
    margin-bottom: .85rem;
}

/* ───── Unread bar ───── */
.cr-unread-bar {
    margin-bottom: .6rem;
}
.cr-unread-pill {
    display: inline-flex;
    align-items: center;
    padding: .25rem .65rem;
    background: rgba(16,185,129,.1);
    color: #059669;
    border-radius: 999px;
    font-size: .72rem;
    font-weight: 700;
}

/* ───── Card Wrapper ───── */
.cr-notif-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,.025);
}

/* ───── Item ───── */
.cr-notif-list { display: flex; flex-direction: column; }

.cr-notif-item {
    display: flex;
    align-items: flex-start;
    gap: .9rem;
    padding: .95rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    text-decoration: none;
    transition: background .15s;
}
.cr-notif-item:last-child { border-bottom: none; }
.cr-notif-item:hover      { background: #f8fafc; }
.cr-notif-item.is-unread  { background: #f0fdf4; }
.cr-notif-item.is-unread:hover { background: #e8fdf4; }

/* Icon bulat */
.cr-ni-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Body: 2 baris */
.cr-ni-body {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: .2rem;
}

/* Baris 1: Judul + Dot unread */
.cr-ni-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .5rem;
}
.cr-ni-title {
    font-size: .88rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
    flex: 1;
}
.cr-ni-unread-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    flex-shrink: 0;
    box-shadow: 0 0 0 3px rgba(16,185,129,.22);
}

/* Baris 2: Pesan ringkas */
.cr-ni-desc {
    margin: 0;
    font-size: .8rem;
    color: #64748b;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Baris 3: Jam kecil */
.cr-ni-time {
    font-size: .7rem;
    color: #94a3b8;
    font-weight: 500;
    margin-top: .1rem;
}

/* Arrow */
.cr-ni-arrow {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    padding-top: 10px;
}

/* ───── Empty State ───── */
.cr-notif-empty {
    text-align: center;
    padding: 3.5rem 1.5rem;
}
.cne-icon {
    width: 68px; height: 68px;
    margin: 0 auto 1.25rem;
    border-radius: 50%;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: center;
}
.cne-title { margin: 0 0 .4rem; font-size: 1rem; font-weight: 700; color: #0f172a; }
.cne-desc  { margin: 0 auto 1.5rem; max-width: 340px; font-size: .82rem; color: #64748b; line-height: 1.5; }
.cne-btn {
    display: inline-flex; align-items: center;
    padding: .6rem 1.2rem;
    background: #0f172a; color: #ffffff;
    border-radius: 12px; font-size: .84rem; font-weight: 600;
    text-decoration: none; transition: all .2s;
}
.cne-btn:hover { background: #1e293b; transform: translateY(-1px); }

/* ───── Pagination ───── */
.cr-notif-pagination {
    padding: .85rem 1rem;
    border-top: 1px solid #f1f5f9;
    display: flex; justify-content: center;
}
</style>
@endsection
