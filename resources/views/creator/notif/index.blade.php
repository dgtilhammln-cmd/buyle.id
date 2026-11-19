@extends('creator.layout')

@section('title', 'Notifikasi — Buyle Creator')

@section('content')
<div class="cr-notif-container">

    {{-- Mobile Top Header (100% Mirip https://buyle.id/creator/products) --}}
    <div class="prod-mobile-header">
        <a href="{{ route('creator.dashboard') }}" class="prod-mobile-btn" title="Kembali">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>
        <h2 class="prod-mobile-title">Notifikasi</h2>
        <div style="width: 40px;"></div>
    </div>

    {{-- Desktop Header --}}
    <div class="cr-notif-desktop-header">
        <div class="cnd-left">
            <a href="{{ route('creator.dashboard') }}" class="cnd-back-btn" title="Kembali ke Dashboard">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <div>
                <h1 class="cnd-title">Notifikasi</h1>
                <p class="cnd-subtitle">Update pesanan, tiket event, verifikasi, dan aktivitas akun toko Anda</p>
            </div>
        </div>
        @if($notifications->count() > 0)
        <div class="cnd-actions">
            @if($unreadCount > 0)
            <form action="{{ route('creator.notif.mark-all-read') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="cr-btn-subtle" title="Tandai semua notifikasi telah dibaca">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Tandai Semua Dibaca</span>
                </button>
            </form>
            @endif

            <form action="{{ route('creator.notif.delete-all') }}" method="POST" onsubmit="return confirm('Hapus semua riwayat notifikasi? Tindakan ini tidak dapat dibatalkan.')" style="margin: 0;">
                @csrf
                <button type="submit" class="cr-btn-danger-subtle" title="Hapus semua riwayat notifikasi">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>Hapus Semua</span>
                </button>
            </form>
        </div>
        @endif
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

    {{-- Mobile Action Bar (Compact) --}}
    @if($notifications->count() > 0)
    <div class="cr-notif-mobile-bar">
        <div class="cnm-count-badge">
            <span class="cnm-dot {{ $unreadCount > 0 ? 'active' : '' }}"></span>
            <span>{{ $unreadCount > 0 ? $unreadCount . ' belum dibaca' : 'Semua sudah dibaca' }}</span>
        </div>
        <div class="cnm-btns">
            @if($unreadCount > 0)
            <form action="{{ route('creator.notif.mark-all-read') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="cnm-action-pill" title="Tandai semua dibaca">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Baca Semua</span>
                </button>
            </form>
            @endif

            <form action="{{ route('creator.notif.delete-all') }}" method="POST" onsubmit="return confirm('Hapus semua notifikasi?')" style="margin: 0;">
                @csrf
                <button type="submit" class="cnm-action-pill danger" title="Hapus semua">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>Hapus</span>
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Notification List Card (Style Minimalis Compact mirip /creator/dashboard) --}}
    <div class="cr-notif-card">
        @if($notifications->count() > 0)
            <div class="cr-notif-list">
                @foreach($notifications as $item)
                @php
                    $isUnread = !$item->is_read;
                    $type = $item->type ?? 'system';
                    
                    // Icon & Color Scheme
                    $iconBg = '#f1f5f9';
                    $iconColor = '#475569';
                    if ($type === 'order') {
                        $iconBg = 'rgba(16, 185, 129, 0.12)';
                        $iconColor = '#059669';
                    } elseif ($type === 'ticket') {
                        $iconBg = 'rgba(14, 165, 233, 0.12)';
                        $iconColor = '#0284c7';
                    } elseif ($type === 'payout') {
                        $iconBg = 'rgba(99, 102, 241, 0.12)';
                        $iconColor = '#4f46e5';
                    } elseif ($type === 'kyc') {
                        $iconBg = 'rgba(245, 158, 11, 0.12)';
                        $iconColor = '#d97706';
                    }
                @endphp
                <a href="{{ route('creator.notif.read', $item->id) }}" class="cr-notif-item {{ $isUnread ? 'is-unread' : '' }}">
                    <div class="cr-notif-icon-col">
                        <div class="cr-notif-icon-box" style="background: {{ $iconBg }}; color: {{ $iconColor }};">
                            @if($type === 'order')
                                {{-- Shopping Bag / Receipt --}}
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                            @elseif($type === 'ticket')
                                {{-- Ticket --}}
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"></path>
                                    <path d="M13 5v14"></path>
                                </svg>
                            @elseif($type === 'payout')
                                {{-- Payout / Wallet --}}
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                    <line x1="2" y1="10" x2="22" y2="10"></line>
                                </svg>
                            @elseif($type === 'kyc')
                                {{-- KYC Shield --}}
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            @else
                                {{-- Bell System --}}
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <div class="cr-notif-content-col">
                        <div class="cr-notif-title-row">
                            <span class="cr-notif-title">{{ $item->title }}</span>
                            <span class="cr-notif-time">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="cr-notif-desc">{{ $item->message }}</p>
                    </div>

                    @if($isUnread)
                    <div class="cr-notif-dot-col">
                        <span class="cr-unread-dot" title="Belum dibaca"></span>
                    </div>
                    @else
                    <div class="cr-notif-dot-col">
                        <svg width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                    @endif
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
                    <svg width="36" height="36" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <h3 class="cne-title">Tidak Ada Notifikasi</h3>
                <p class="cne-desc">Saat ada pesanan baru, tiket terjual, atau informasi penting toko, kami akan menampilkannya di sini.</p>
                <a href="{{ route('creator.dashboard') }}" class="cne-btn">Kembali ke Dashboard</a>
            </div>
        @endif
    </div>

</div>

{{-- Sinkronisasi Cache Ringan ke Perangkat User (Tanpa Polling / Interval Database) --}}
<script>
    (function() {
        try {
            // Update cache count di localStorage/sessionStorage perangkat user
            const unreadCount = {{ (int)$unreadCount }};
            localStorage.setItem('buyle_cr_unread_notif', unreadCount);
            localStorage.setItem('buyle_cr_notif_last_sync', Date.now());
        } catch (e) {
            // Silent fallback jika browser storage dinonaktifkan
        }
    })();
</script>

<style>
/* Layout Container */
.cr-notif-container {
    max-width: 760px;
    margin: 0 auto;
    padding: 1rem 1rem 3rem;
}

/* Mobile Header (100% Mirip https://buyle.id/creator/products) */
.prod-mobile-header {
    display: none;
}
@media (max-width: 991px) {
    .prod-mobile-header {
        display: flex !important;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0 1rem;
        margin-bottom: 0.25rem;
    }
    .prod-mobile-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ffffff;
        border: 1.5px solid #f1ece4;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .prod-mobile-btn:active {
        transform: scale(0.92);
    }
    .prod-mobile-title {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.01em;
    }
}

/* Desktop Header */
.cr-notif-desktop-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
@media (max-width: 991px) {
    .cr-notif-desktop-header {
        display: none;
    }
}
.cnd-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.cnd-back-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #334155;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}
.cnd-back-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}
.cnd-title {
    margin: 0;
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
}
.cnd-subtitle {
    margin: 0.15rem 0 0;
    font-size: 0.85rem;
    color: #64748b;
}
.cnd-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.cr-btn-subtle {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 0.85rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    transition: all 0.18s ease;
}
.cr-btn-subtle:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}
.cr-btn-danger-subtle {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 0.85rem;
    background: #ffffff;
    border: 1px solid #fee2e2;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #dc2626;
    cursor: pointer;
    transition: all 0.18s ease;
}
.cr-btn-danger-subtle:hover {
    background: #fef2f2;
    border-color: #fca5a5;
}

/* Alert Flash */
.cr-alert-success {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

/* Mobile Action Bar */
.cr-notif-mobile-bar {
    display: none;
}
@media (max-width: 991px) {
    .cr-notif-mobile-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.6rem 0.85rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        margin-bottom: 0.85rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .cnm-count-badge {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #475569;
    }
    .cnm-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
    }
    .cnm-dot.active {
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
    .cnm-btns {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .cnm-action-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.35rem 0.65rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
    }
    .cnm-action-pill.danger {
        color: #dc2626;
        border-color: #fee2e2;
        background: #fff5f5;
    }
}

/* Card Wrapper (Style Mirip /creator/dashboard) */
.cr-notif-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
}

/* Notification Items */
.cr-notif-list {
    display: flex;
    flex-direction: column;
}
.cr-notif-item {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    padding: 1rem 1.15rem;
    border-bottom: 1px solid #f1f5f9;
    text-decoration: none;
    transition: background 0.15s ease;
    position: relative;
}
.cr-notif-item:last-child {
    border-bottom: none;
}
.cr-notif-item:hover {
    background: #f8fafc;
}
.cr-notif-item.is-unread {
    background: #f0fdf4;
}
.cr-notif-item.is-unread:hover {
    background: #e6fced;
}

/* Column Icon */
.cr-notif-icon-col {
    flex-shrink: 0;
    padding-top: 2px;
}
.cr-notif-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}

/* Column Content */
.cr-notif-content-col {
    flex: 1;
    min-width: 0;
}
.cr-notif-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}
.cr-notif-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
.cr-notif-time {
    font-size: 0.72rem;
    color: #94a3b8;
    white-space: nowrap;
    flex-shrink: 0;
}
.cr-notif-desc {
    margin: 0;
    font-size: 0.82rem;
    color: #475569;
    line-height: 1.45;
    word-break: break-word;
}

/* Column Dot */
.cr-notif-dot-col {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-top: 10px;
    padding-left: 4px;
}
.cr-unread-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
}

/* Empty State */
.cr-notif-empty {
    text-align: center;
    padding: 3.5rem 1.5rem;
}
.cne-icon {
    width: 68px;
    height: 68px;
    margin: 0 auto 1.25rem;
    border-radius: 50%;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cne-title {
    margin: 0 0 0.4rem;
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
}
.cne-desc {
    margin: 0 auto 1.5rem;
    max-width: 380px;
    font-size: 0.84rem;
    color: #64748b;
    line-height: 1.5;
}
.cne-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.65rem 1.25rem;
    background: #0f172a;
    color: #ffffff;
    border-radius: 12px;
    font-size: 0.84rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}
.cne-btn:hover {
    background: #1e293b;
    color: #ffffff;
    transform: translateY(-1px);
}

/* Pagination */
.cr-notif-pagination {
    padding: 1rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: center;
}
</style>
@endsection
