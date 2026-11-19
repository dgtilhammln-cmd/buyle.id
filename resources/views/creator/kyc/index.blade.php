@extends('creator.layout')
@section('title', 'Verifikasi Identitas e-KYC · Creator Studio')
@section('page_title', 'Verifikasi e-KYC')

@section('styles')
    <style>
        /* Mobile Header */
        .cr-mob-page-header {
            display: none;
        }

        @media (max-width: 991px) {
            .cr-mob-page-header {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                padding: max(0.65rem, calc(0.5rem + env(safe-area-inset-top, 0px))) 0 1rem;
                margin-bottom: 0.25rem;
            }

            .cr-mob-page-header .prod-mobile-btn {
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
                flex-shrink: 0;
            }

            .cr-mob-page-header .prod-mobile-btn:active {
                transform: scale(0.92);
            }

            .cr-mob-page-header .prod-mobile-title {
                margin: 0;
                font-size: 1.05rem;
                font-weight: 800;
                color: #0f172a;
                letter-spacing: -0.01em;
                text-align: center;
                flex: 1;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                padding: 0 0.5rem;
            }
        }
    </style>
@endsection

@section('content')

    {{-- ════ Mobile Header ════ --}}
    <div class="cr-mob-page-header">
        <button type="button" class="prod-mobile-btn" onclick="handleKycMobileBack()" title="Kembali">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </button>
        <h2 class="prod-mobile-title">Verifikasi Identitas e-KYC</h2>
        <div style="width:40px; height:40px; flex-shrink:0;"></div>
    </div>

    {{-- Desktop Header --}}
    <div class="creator-header" style="margin-bottom: 1.5rem; display: none;">
        <div>
            <h1 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem 0;">Verifikasi Identitas (e-KYC)</h1>
            <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Lengkapi verifikasi KTP dan biometrik wajah untuk legalitas penjualan event serta pencairan saldo.</p>
        </div>
    </div>

    <script>
        function handleKycMobileBack() {
            if (document.referrer && document.referrer.includes(window.location.host) && !document.referrer.includes('/creator/kyc')) {
                window.location.href = document.referrer;
            } else {
                window.location.href = "{{ route('creator.ticket.profile') }}";
            }
        }
    </script>

    <div style="max-width: 900px; margin: 0 auto; width: 100%;">
        @include('creator.partials.kyc_tab')
    </div>

@endsection
