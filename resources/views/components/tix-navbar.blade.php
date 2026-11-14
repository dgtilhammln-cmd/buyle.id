{{-- ══════════════════════════════════════════════════════════════════
     BUYLE TIX — DEDICATED MODERN HEADER
     Gradient: #1db349 to #84ce00
     Inspired by Artatix / Creative Agency Reference
     100% Mobile Friendly & Responsive
     Fixed Sticky (Always Stays at Top on Scroll)
     ══════════════════════════════════════════════════════════════════ --}}
@php
    $isHome = request()->routeIs('tix.index') && !request()->has('q');
    $isAbout = request()->routeIs('tix.info');
    $waNumber = '085179982373';
    $waUrl = 'https://wa.me/6285179982373?text=' . urlencode('Halo Buyle TIX, saya ingin konsultasi dan kerjasama ticketing event.');
@endphp

<style>
    /* ── TIX HEADER SCOPED STYLES ── */
    :root {
        --tix-h-desktop: 80px;
        --tix-h-mobile: 66px;
    }

    /* Page container adjustments for fixed header */
    body {
        margin: 0;
        padding-top: 0 !important;
        background-color: #ffffff;
    }

    .buyle-tix-page {
        padding-top: 1.5rem !important;
    }
    @media (max-width: 991px) {
        .buyle-tix-page {
            padding-top: 1rem !important;
        }
    }

    .pd-page {
        padding-top: 1.25rem !important;
    }

    /* Fixed Header Bar — Always pinned to top, never sinks on scroll */
    .tix-main-header {
        position: fixed !important;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: var(--tix-h-desktop);
        background: linear-gradient(135deg, #1db349 0%, #84ce00 100%);
        box-shadow: 0 4px 24px rgba(29, 179, 73, 0.28), 0 1px 3px rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        z-index: 99999;
        font-family: 'Montserrat', sans-serif !important;
        box-sizing: border-box;
    }

    .tix-main-header * {
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif !important;
    }

    /* Spacer element under fixed header to prevent content overlap */
    .tix-header-spacer {
        display: block;
        width: 100%;
        height: var(--tix-h-desktop);
        pointer-events: none;
        visibility: hidden;
    }

    /* Page container adjustments for pages using tix navbar */
    .buyle-ticketing-section {
        padding-top: 3.5rem !important;
    }
    @media (max-width: 991px) {
        .buyle-ticketing-section {
            padding-top: 2rem !important;
        }
    }

    .tix-header-container {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 1.25rem;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    /* ── LEFT: Logo ── */
    .tix-header-left {
        display: flex;
        align-items: center;
        flex-shrink: 0;
        height: 100%;
    }

    .tix-header-logo-link {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }

    .tix-header-logo-link:hover {
        transform: scale(1.02);
        opacity: 0.95;
    }

    .tix-header-logo-img {
        height: 42px;
        width: auto;
        max-width: 215px;
        object-fit: contain;
        display: block;
        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.12));
    }

    /* ── CENTER: Pill Capsule (Desktop & Tablet) ── */
    .tix-header-center {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1 1 auto;
        min-width: 0;
        height: 100%;
    }

    .tix-nav-capsule {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.32);
        border-radius: 999px;
        padding: 5px 6px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06), inset 0 1px 1px rgba(255, 255, 255, 0.3);
    }

    .tix-nav-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        font-size: 0.88rem;
        font-weight: 600;
        color: #ffffff;
        text-decoration: none;
        padding: 0.5rem 1.35rem;
        border-radius: 999px;
        white-space: nowrap;
        line-height: 1.2;
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .tix-nav-pill:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* Active State (Solid Crisp Pill like Artatix reference) */
    .tix-nav-pill.is-active {
        background: #ffffff;
        color: #15803D;
        font-weight: 700;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
    }

    .tix-nav-pill.is-active:hover {
        background: #ffffff;
        color: #15803D;
        transform: none;
    }

    /* ── RIGHT: Actions (Search, Auth, Drawer Toggle) ── */
    .tix-header-right {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.65rem;
        flex-shrink: 0;
        height: 100%;
    }

    /* Search Circle Button */
    .tix-search-btn {
        width: 42px;
        height: 42px;
        min-width: 42px;
        min-height: 42px;
        max-height: 42px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.32);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: center;
        cursor: pointer;
        padding: 0;
        margin: 0;
        line-height: 1;
        vertical-align: middle;
        transition: all 0.2s ease;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .tix-search-btn:hover {
        background: rgba(255, 255, 255, 0.32);
        transform: scale(1.05);
        color: #ffffff;
    }

    .tix-search-btn:active {
        transform: scale(0.96);
    }

    /* Auth Buttons */
    .tix-auth-masuk-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: center;
        height: 42px;
        min-height: 42px;
        max-height: 42px;
        padding: 0 1.35rem;
        border-radius: 999px;
        background: #ffffff;
        color: #15803D;
        font-size: 0.88rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
        margin: 0;
        line-height: 1;
        vertical-align: middle;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        white-space: nowrap;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .tix-auth-masuk-btn:hover {
        background: #F0FDF4;
        color: #166534;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .tix-auth-daftar-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: center;
        height: 42px;
        min-height: 42px;
        max-height: 42px;
        padding: 0 1.25rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.32);
        color: #ffffff;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        margin: 0;
        line-height: 1;
        vertical-align: middle;
        transition: all 0.2s ease;
        white-space: nowrap;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .tix-auth-daftar-btn:hover {
        background: rgba(255, 255, 255, 0.28);
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* User Profile Dropdown Button (when logged in) */
    .tix-user-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: center;
        height: 42px;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .tix-user-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: center;
        gap: 0.45rem;
        height: 42px;
        min-height: 42px;
        max-height: 42px;
        padding: 0 12px 0 5px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #ffffff;
        cursor: pointer;
        margin: 0;
        line-height: 1;
        vertical-align: middle;
        transition: all 0.2s ease;
        white-space: nowrap;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .tix-user-btn:hover {
        background: rgba(255, 255, 255, 0.32);
    }

    .tix-user-avatar {
        width: 32px;
        height: 32px;
        min-width: 32px;
        min-height: 32px;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
        border: 1.5px solid rgba(255, 255, 255, 0.85);
        display: block;
        margin: 0;
        padding: 0;
        flex-shrink: 0;
    }

    .tix-user-name {
        font-size: 0.84rem;
        font-weight: 700;
        color: #ffffff;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .tix-user-btn svg {
        display: block;
        vertical-align: middle;
        margin: 0;
        flex-shrink: 0;
    }

    /* Dropdown Flyout */
    .tix-user-menu {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 240px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.15), 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #E2E8F0;
        padding: 0.6rem;
        z-index: 10000;
        animation: tixDropIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .tix-user-wrapper.open .tix-user-menu {
        display: block;
    }

    @keyframes tixDropIn {
        from { opacity: 0; transform: translateY(-8px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .tix-menu-header {
        padding: 0.5rem 0.75rem 0.6rem;
        border-bottom: 1px solid #F1F5F9;
        margin-bottom: 0.4rem;
    }

    .tix-menu-name {
        font-size: 0.88rem;
        font-weight: 700;
        color: #0F172A;
        line-height: 1.3;
    }

    .tix-menu-role {
        font-size: 0.72rem;
        color: #16A34A;
        font-weight: 600;
        margin-top: 2px;
    }

    .tix-menu-item {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.55rem 0.75rem;
        border-radius: 10px;
        color: #334155;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .tix-menu-item:hover {
        background: #F0FDF4;
        color: #15803D;
    }

    .tix-menu-item svg {
        color: #64748B;
        flex-shrink: 0;
    }

    .tix-menu-item:hover svg {
        color: #15803D;
    }

    .tix-menu-logout-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.55rem 0.75rem;
        border-radius: 10px;
        color: #EF4444;
        font-size: 0.82rem;
        font-weight: 600;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
        margin-top: 0.3rem;
        border-top: 1px solid #F1F5F9;
        padding-top: 0.65rem;
    }

    .tix-menu-logout-btn:hover {
        background: #FEF2F2;
    }

    /* Mobile Hamburger Button */
    .tix-hamburger-btn {
        display: none;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.32);
        color: #ffffff;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
        margin: 0;
        line-height: 1;
        vertical-align: middle;
        transition: all 0.2s ease;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .tix-hamburger-btn:hover {
        background: rgba(255, 255, 255, 0.32);
    }

    /* ══════════════════════════════════════════════════════════════
       SEARCH OVERLAY POPUP (Desktop & Mobile)
       ══════════════════════════════════════════════════════════════ */
    .tix-search-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 100000;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 6rem 1.25rem 2rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
    }

    .tix-search-overlay.open {
        opacity: 1;
        pointer-events: auto;
    }

    .tix-search-modal {
        width: 100%;
        max-width: 680px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid #E2E8F0;
        transform: translateY(-16px) scale(0.98);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .tix-search-overlay.open .tix-search-modal {
        transform: translateY(0) scale(1);
    }

    .tix-search-modal-form {
        display: flex;
        align-items: center;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #F1F5F9;
        gap: 0.75rem;
    }

    .tix-search-modal-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 1.05rem;
        font-weight: 600;
        color: #0F172A;
        background: transparent;
    }

    .tix-search-modal-input::placeholder {
        color: #94A3B8;
        font-weight: 400;
    }

    .tix-search-close-btn {
        background: #F1F5F9;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748B;
        cursor: pointer;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .tix-search-close-btn:hover {
        background: #E2E8F0;
        color: #0F172A;
    }

    .tix-search-chips {
        padding: 1rem 1.25rem 1.25rem;
        background: #FAFAFA;
    }

    .tix-chips-title {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.65rem;
    }

    .tix-chips-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .tix-chip-link {
        font-size: 0.78rem;
        font-weight: 600;
        color: #334155;
        background: #ffffff;
        border: 1px solid #E2E8F0;
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .tix-chip-link:hover {
        background: #F0FDF4;
        border-color: #86EFAC;
        color: #15803D;
    }

    /* ══════════════════════════════════════════════════════════════
       MOBILE DRAWER MENU (Right off-canvas)
       ══════════════════════════════════════════════════════════════ */
    .tix-drawer-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 100000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.28s ease;
    }

    .tix-drawer-backdrop.open {
        opacity: 1;
        pointer-events: auto;
    }

    .tix-mobile-drawer {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 320px;
        max-width: 86vw;
        background: #ffffff;
        z-index: 100001;
        box-shadow: -10px 0 30px rgba(0, 0, 0, 0.15);
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    .tix-drawer-backdrop.open .tix-mobile-drawer {
        transform: translateX(0);
    }

    .tix-drawer-head {
        padding: 1.25rem;
        background: linear-gradient(135deg, #1db349 0%, #84ce00 100%);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tix-drawer-logo-img {
        height: 32px;
        width: auto;
        max-width: 155px;
        object-fit: contain;
    }

    .tix-drawer-close-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        border: none;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
    }

    .tix-drawer-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .tix-drawer-nav {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .tix-drawer-link {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        color: #1E293B;
        font-size: 0.92rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .tix-drawer-link.is-active {
        background: #F0FDF4;
        color: #15803D;
        font-weight: 700;
        border: 1px solid #BBF7D0;
    }

    .tix-drawer-link:hover {
        background: #F8FAFC;
        color: #15803D;
    }

    .tix-drawer-link-icon {
        width: 20px;
        height: 20px;
        color: #64748B;
        flex-shrink: 0;
    }

    .tix-drawer-link.is-active .tix-drawer-link-icon {
        color: #15803D;
    }

    .tix-drawer-wa-card {
        background: linear-gradient(135deg, #F0FDF4 0%, #ECFDF5 100%);
        border: 1.5px solid #BBF7D0;
        border-radius: 14px;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .tix-drawer-wa-title {
        font-size: 0.76rem;
        font-weight: 700;
        color: #15803D;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .tix-drawer-wa-desc {
        font-size: 0.78rem;
        color: #334155;
        line-height: 1.4;
        margin: 0;
    }

    .tix-drawer-wa-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        background: #1eb349;
        color: #ffffff;
        font-size: 0.84rem;
        font-weight: 700;
        padding: 0.6rem 1rem;
        border-radius: 10px;
        text-decoration: none;
        margin-top: 0.35rem;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.25);
    }

    .tix-drawer-auth-box {
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #E2E8F0;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    /* ══════════════════════════════════════════════════════════════
       RESPONSIVE BREAKPOINTS (Mobile-Friendly & Perfectly Aligned)
       ══════════════════════════════════════════════════════════════ */
    @media (max-width: 991px) {
        .tix-main-header {
            height: var(--tix-h-mobile) !important;
        }

        .tix-header-spacer {
            height: var(--tix-h-mobile) !important;
        }

        .tix-header-container {
            padding: 0 1rem;
        }

        .tix-header-logo-img {
            height: 34px;
            max-width: 165px;
        }

        /* Center pill capsule is hidden on mobile to avoid any collision */
        .tix-header-center {
            display: none !important;
        }

        .tix-auth-daftar-btn {
            display: none !important;
        }

        .tix-header-right {
            gap: 0.5rem !important;
            align-items: center !important;
            justify-content: flex-end !important;
            height: 100% !important;
        }

        /* All right buttons: exact same 38px height & perfectly centered */
        .tix-search-btn {
            width: 38px !important;
            height: 38px !important;
            min-width: 38px !important;
            min-height: 38px !important;
            max-height: 38px !important;
            border-radius: 50% !important;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            align-self: center !important;
            line-height: 1 !important;
            vertical-align: middle !important;
        }

        .tix-search-btn svg {
            width: 16px !important;
            height: 16px !important;
            display: block !important;
            margin: 0 !important;
        }

        .tix-user-wrapper {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            align-self: center !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 38px !important;
            box-sizing: border-box !important;
        }

        .tix-user-btn {
            height: 38px !important;
            min-height: 38px !important;
            max-height: 38px !important;
            padding: 0 8px 0 4px !important;
            margin: 0 !important;
            gap: 5px !important;
            box-sizing: border-box !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            align-self: center !important;
            line-height: 1 !important;
            vertical-align: middle !important;
            border-radius: 999px !important;
        }

        .tix-user-avatar {
            width: 28px !important;
            height: 28px !important;
            min-width: 28px !important;
            min-height: 28px !important;
            margin: 0 !important;
            padding: 0 !important;
            display: block !important;
            border-radius: 50% !important;
            flex-shrink: 0 !important;
            border-width: 1.5px !important;
        }

        .tix-user-name {
            display: none !important;
        }

        .tix-user-btn svg {
            width: 11px !important;
            height: 11px !important;
            margin: 0 !important;
            flex-shrink: 0 !important;
            display: block !important;
        }

        .tix-hamburger-btn {
            display: inline-flex !important;
            width: 38px !important;
            height: 38px !important;
            min-width: 38px !important;
            min-height: 38px !important;
            max-height: 38px !important;
            border-radius: 10px !important;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
            align-items: center !important;
            justify-content: center !important;
            align-self: center !important;
            line-height: 1 !important;
            vertical-align: middle !important;
        }

        .tix-hamburger-btn svg {
            width: 20px !important;
            height: 20px !important;
            display: block !important;
            margin: 0 !important;
        }

        .tix-auth-masuk-btn {
            height: 38px !important;
            min-height: 38px !important;
            max-height: 38px !important;
            padding: 0 1rem !important;
            font-size: 0.8rem !important;
            margin: 0 !important;
            box-sizing: border-box !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            align-self: center !important;
            line-height: 1 !important;
            vertical-align: middle !important;
        }
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
    }

    @media (max-width: 420px) {
        .tix-header-logo-img {
            height: 30px;
            max-width: 135px;
        }

        .tix-header-right {
            gap: 0.35rem;
        }

        .tix-auth-masuk-btn {
            padding: 0 0.8rem !important;
            font-size: 0.76rem !important;
        }
    }
</style>

{{-- ── TIX MAIN HEADER (FIXED TOP) ── --}}
<header class="tix-main-header" id="tixHeader">
    <div class="tix-header-container">

        {{-- ── LEFT: Logo Buyle TIX ── --}}
        <div class="tix-header-left">
            <a href="{{ route('tix.index') }}" class="tix-header-logo-link" title="Buyle TIX — Platform Tiket Event">
                <picture>
                    <source srcset="{{ asset('images/logobuyletix.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logobuyletix.png') }}"
                         alt="Buyle TIX"
                         class="tix-header-logo-img"
                         width="215"
                         height="42">
                </picture>
            </a>
        </div>

        {{-- ── CENTER: Pill Capsule (Desktop & Tablet) ── --}}
        <div class="tix-header-center">
            <nav class="tix-nav-capsule" aria-label="Menu Utama Buyle TIX">
                {{-- Beranda --}}
                <a href="{{ route('tix.index') }}"
                   class="tix-nav-pill {{ $isHome ? 'is-active' : '' }}">
                    Beranda
                </a>

                {{-- Tentang --}}
                <a href="{{ route('tix.info') }}"
                   class="tix-nav-pill {{ $isAbout ? 'is-active' : '' }}">
                    Tentang
                </a>

                {{-- Kerja Sama (Clean tanpa nomor di pill) --}}
                <a href="{{ $waUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="tix-nav-pill"
                   title="Kerjasama Event Organizer / Penyelenggara: {{ $waNumber }}">
                    Kerja Sama
                </a>
            </nav>
        </div>

        {{-- ── RIGHT: Actions (Search & Auth) ── --}}
        <div class="tix-header-right">

            {{-- Search Button --}}
            <button type="button" class="tix-search-btn" onclick="openTixSearch()" title="Cari Event">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="M21 21l-4.35-4.35"></path>
                </svg>
            </button>

            {{-- Auth Section --}}
            @auth
                {{-- Logged In User Dropdown --}}
                <div class="tix-user-wrapper" id="tixUserWrapper">
                    <button type="button" class="tix-user-btn" onclick="toggleTixUserMenu(event)">
                        @php
                            $userAvatar = Auth::user()->avatar
                                ? (Str::startsWith(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar))
                                : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=ffffff&color=15803d&bold=true';
                        @endphp
                        <img src="{{ $userAvatar }}" class="tix-user-avatar" alt="{{ Auth::user()->name }}">
                        <span class="tix-user-name">{{ Str::limit(Auth::user()->name, 12) }}</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </button>

                    <div class="tix-user-menu" id="tixUserMenu">
                        <div class="tix-menu-header">
                            <div class="tix-menu-name">{{ Auth::user()->name }}</div>
                            <div class="tix-menu-role">
                                {{ Auth::user()->role === 'creator' ? 'Penyelenggara / Creator' : 'Member Buyle' }}
                            </div>
                        </div>

                        {{-- Tiket Saya --}}
                        <a href="{{ Route::has('account.orders') ? route('account.orders') : url('/akun/pesanan') }}" class="tix-menu-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            <span>Tiket Saya</span>
                        </a>

                        @if(Auth::user()->role !== 'buyer')
                            {{-- Dashboard Creator --}}
                            <a href="{{ Route::has('creator.dashboard') ? route('creator.dashboard') : url('/creator') }}" class="tix-menu-item">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7"/>
                                    <rect x="14" y="3" width="7" height="7"/>
                                    <rect x="14" y="14" width="7" height="7"/>
                                    <rect x="3" y="14" width="7" height="7"/>
                                </svg>
                                <span>Dashboard Creator</span>
                            </a>

                            {{-- Scanner Tiket Gate --}}
                            <a href="{{ Route::has('creator.ticket.scanner') ? route('creator.ticket.scanner') : url('/event-attendance') }}" class="tix-menu-item">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 7V5a2 2 0 0 1 2-2h2"/>
                                    <path d="M17 3h2a2 2 0 0 1 2 2v2"/>
                                    <path d="M21 17v2a2 2 0 0 1-2 2h-2"/>
                                    <path d="M7 21H5a2 2 0 0 1-2-2v-2"/>
                                    <line x1="7" y1="12" x2="17" y2="12"/>
                                </svg>
                                <span>Gate Scanner</span>
                            </a>
                        @endif

                        {{-- Akun Saya --}}
                        <a href="{{ Route::has('account.overview') ? route('account.overview') : url('/akun') }}" class="tix-menu-item">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>Pengaturan Akun</span>
                        </a>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="tix-menu-logout-btn">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Guest: Masuk & Daftar --}}
                <a href="{{ route('login') }}" class="tix-auth-masuk-btn">Masuk</a>
                <a href="{{ route('register') }}" class="tix-auth-daftar-btn">Daftar</a>
            @endauth

            {{-- Mobile Hamburger Toggle --}}
            <button type="button" class="tix-hamburger-btn" onclick="openTixDrawer()" aria-label="Buka Menu Mobile">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

        </div>

    </div>
</header>

{{-- Spacer element so page content doesn't hide behind fixed header --}}
<div class="tix-header-spacer"></div>

{{-- ══════════════════════════════════════════════════════════════
     SEARCH MODAL POPUP
     ══════════════════════════════════════════════════════════════ --}}
<div class="tix-search-overlay" id="tixSearchOverlay" onclick="handleSearchOverlayClick(event)">
    <div class="tix-search-modal">
        <form action="{{ route('tix.index') }}" method="GET" class="tix-search-modal-form">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#15803D" stroke-width="2.5" style="flex-shrink:0;">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="M21 21l-4.35-4.35"></path>
            </svg>
            <input type="text"
                   name="q"
                   id="tixSearchModalInput"
                   class="tix-search-modal-input"
                   placeholder="Cari nama event, artis, seminar, kota..."
                   value="{{ request('q') }}"
                   autocomplete="off">
            <button type="button" class="tix-search-close-btn" onclick="closeTixSearch()" aria-label="Tutup Pencarian">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </form>

        <div class="tix-search-chips">
            <div class="tix-chips-title">Kategori Populer</div>
            <div class="tix-chips-wrap">
                <a href="{{ route('tix.index', ['q' => 'Konser']) }}" class="tix-chip-link">🎵 Konser Musik</a>
                <a href="{{ route('tix.index', ['q' => 'Webinar']) }}" class="tix-chip-link">💻 Webinar & Edukasi</a>
                <a href="{{ route('tix.index', ['q' => 'Festival']) }}" class="tix-chip-link">🎪 Festival & Expo</a>
                <a href="{{ route('tix.index', ['q' => 'Workshop']) }}" class="tix-chip-link">🚀 Workshop</a>
                <a href="{{ route('tix.index', ['q' => 'Surabaya']) }}" class="tix-chip-link">📍 Event Surabaya</a>
                <a href="{{ route('tix.index', ['q' => 'Jakarta']) }}" class="tix-chip-link">📍 Event Jakarta</a>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MOBILE OFFCANVAS DRAWER
     ══════════════════════════════════════════════════════════════ --}}
<div class="tix-drawer-backdrop" id="tixDrawerBackdrop" onclick="handleDrawerBackdropClick(event)">
    <div class="tix-mobile-drawer" id="tixMobileDrawer">
        {{-- Drawer Head --}}
        <div class="tix-drawer-head">
            <a href="{{ route('tix.index') }}" onclick="closeTixDrawer()">
                <picture>
                    <source srcset="{{ asset('images/logobuyletix.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logobuyletix.png') }}"
                         alt="Buyle TIX"
                         class="tix-drawer-logo-img">
                </picture>
            </a>
            <button type="button" class="tix-drawer-close-btn" onclick="closeTixDrawer()" aria-label="Tutup Menu">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Drawer Body --}}
        <div class="tix-drawer-body">

            {{-- Quick Search Bar inside Drawer --}}
            <form action="{{ route('tix.index') }}" method="GET" style="position:relative; width:100%;">
                <input type="text"
                       name="q"
                       class="form-control"
                       placeholder="Cari event di Buyle TIX..."
                       value="{{ request('q') }}"
                       style="width:100%; height:42px; border-radius:12px; border:1.5px solid #E2E8F0; padding:0 1rem 0 2.4rem; font-size:0.85rem; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2.5" style="position:absolute; left:0.8rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="M21 21l-4.35-4.35"></path>
                </svg>
            </form>

            {{-- Navigation Links --}}
            <div class="tix-drawer-nav">
                <a href="{{ route('tix.index') }}" class="tix-drawer-link {{ $isHome ? 'is-active' : '' }}" onclick="closeTixDrawer()">
                    <svg class="tix-drawer-link-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>Beranda Buyle TIX</span>
                </a>

                <a href="{{ route('tix.info') }}" class="tix-drawer-link {{ $isAbout ? 'is-active' : '' }}" onclick="closeTixDrawer()">
                    <svg class="tix-drawer-link-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    <span>Tentang Buyle TIX</span>
                </a>

                @auth
                    <a href="{{ Route::has('account.orders') ? route('account.orders') : url('/akun/pesanan') }}" class="tix-drawer-link" onclick="closeTixDrawer()">
                        <svg class="tix-drawer-link-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                        <span>Tiket Saya</span>
                    </a>
                @endauth
            </div>

            {{-- WhatsApp Partnership Card (Kerja Sama) --}}
            <div class="tix-drawer-wa-card">
                <div class="tix-drawer-wa-title">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>Kerja Sama Event (EO)</span>
                </div>
                <p class="tix-drawer-wa-desc">
                    Kelola penjualan tiket event, gate scanner QR kilat, dan settlement otomatis bersama Buyle TIX.
                </p>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="tix-drawer-wa-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>Hubungi WA ({{ $waNumber }})</span>
                </a>
            </div>

            {{-- Auth Section at Bottom of Drawer --}}
            <div class="tix-drawer-auth-box">
                @auth
                    <div style="display:flex; align-items:center; gap:0.65rem; margin-bottom:0.5rem;">
                        <img src="{{ $userAvatar }}" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:1px solid #E2E8F0;">
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:0.86rem; font-weight:700; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ Auth::user()->name }}
                            </div>
                            <div style="font-size:0.72rem; color:#16A34A; font-weight:600;">
                                {{ Auth::user()->role === 'creator' ? 'Penyelenggara / Creator' : 'Member' }}
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" style="width:100%;">
                        @csrf
                        <button type="submit" style="width:100%; display:flex; align-items:center; justify-content:center; gap:0.5rem; background:#FEF2F2; color:#EF4444; border:1px solid #FECACA; padding:0.6rem 1rem; border-radius:10px; font-size:0.84rem; font-weight:700; cursor:pointer;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            <span>Keluar dari Akun</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="tix-auth-masuk-btn" style="width:100%; background:#16a34a; color:#ffffff; height:42px; box-shadow:none;">
                        Masuk ke Akun
                    </a>
                    <a href="{{ route('register') }}" class="tix-auth-daftar-btn" style="width:100%; color:#16a34a; background:#F0FDF4; border-color:#BBF7D0; height:42px; justify-content:center;">
                        Daftar Akun Baru
                    </a>
                @endauth
            </div>

        </div>
    </div>
</div>

{{-- ── INTERACTIVE JAVASCRIPT FOR TIX HEADER ── --}}
<script>
    // Search Overlay
    function openTixSearch() {
        const overlay = document.getElementById('tixSearchOverlay');
        const input = document.getElementById('tixSearchModalInput');
        if (overlay) {
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
            setTimeout(() => { if (input) input.focus(); }, 120);
        }
    }

    function closeTixSearch() {
        const overlay = document.getElementById('tixSearchOverlay');
        if (overlay) {
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }
    }

    function handleSearchOverlayClick(e) {
        if (e.target.id === 'tixSearchOverlay') {
            closeTixSearch();
        }
    }

    // User Menu Dropdown (Desktop)
    function toggleTixUserMenu(e) {
        e.stopPropagation();
        const wrapper = document.getElementById('tixUserWrapper');
        if (wrapper) wrapper.classList.toggle('open');
    }

    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('tixUserWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            wrapper.classList.remove('open');
        }
    });

    // Mobile Drawer
    function openTixDrawer() {
        const backdrop = document.getElementById('tixDrawerBackdrop');
        if (backdrop) {
            backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeTixDrawer() {
        const backdrop = document.getElementById('tixDrawerBackdrop');
        if (backdrop) {
            backdrop.classList.remove('open');
            document.body.style.overflow = '';
        }
    }

    function handleDrawerBackdropClick(e) {
        if (e.target.id === 'tixDrawerBackdrop') {
            closeTixDrawer();
        }
    }

    // Keyboard Shortcuts (ESC to close)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTixSearch();
            closeTixDrawer();
            const wrapper = document.getElementById('tixUserWrapper');
            if (wrapper) wrapper.classList.remove('open');
        }
    });
</script>
