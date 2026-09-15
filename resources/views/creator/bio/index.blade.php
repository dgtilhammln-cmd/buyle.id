@extends('creator.layout')
@section('title', 'Link in Bio · Dashboard')
@section('page_title', 'Link in Bio')

@section('topbar_actions')
    @if($profile->store_slug)
        <a href="{{ url('/' . $profile->store_slug) }}" target="_blank" class="btn-primary"
            style="background:transparent; color:#1eb349; border:1.5px solid #1eb349; box-shadow:none;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                <polyline points="15 3 21 3 21 9" />
                <line x1="10" y1="14" x2="21" y2="3" />
            </svg>
            Lihat Bio
        </a>
    @endif
@endsection

@section('styles')
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <style>
        .bio-layout {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .bio-sidebar {
            width: 320px;
            flex-shrink: 0;
            background: #fff;
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            position: sticky;
            top: 1.5rem;
            max-height: calc(100vh - 3rem);
            overflow-y: auto;
            scrollbar-width: none;
        }

        .bio-sidebar::-webkit-scrollbar {
            display: none;
        }

        .bio-content {
            flex: 1;
            min-width: 0;
        }

        .tab-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1.5px solid transparent;
            background: #ffffff;
            color: #475569;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 14px;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s ease;
            margin-bottom: 0.35rem;
            position: relative;
        }

        .tab-btn:hover {
            background: #F8FAFC;
            color: #0F172A;
            border-color: #E2E8F0;
            transform: translateX(2px);
        }

        .tab-btn:active {
            transform: scale(0.99);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            font-weight: 700;
            border-color: transparent !important;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.32);
        }

        .tab-btn.active svg,
        .tab-btn.active i {
            stroke: #ffffff !important;
            color: #ffffff !important;
        }

        .tab-btn svg,
        .tab-btn i {
            transition: stroke 0.2s ease, color 0.2s ease;
            flex-shrink: 0;
            stroke: #64748B;
        }

        .tab-btn:hover svg,
        .tab-btn:hover i {
            stroke: #1eb349;
            color: #1eb349;
        }

        .tab-text-full {
            display: inline-block;
        }

        .tab-text-mob {
            display: none;
        }

        .tab-pane {
            display: none;
            animation: fadeIn 0.3s;
        }

        .tab-pane.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .prof-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .prof-card-head {
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            font-size: 0.95rem;
            font-weight: 800;
            color: #0b120c;
        }

        .card-body {
            padding: 1.5rem 1.75rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #374151;
        }

        .form-input {
            height: 44px;
            padding: 0 1rem;
            border: 1.5px solid #e7f0e7;
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
            color: #1a1a1a;
            background: #f9fefb;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: #1eb349;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
        }

        textarea.form-input {
            height: auto;
            padding: 0.75rem 1rem;
            resize: vertical;
            min-height: 80px;
        }

        .form-hint {
            font-size: 0.72rem;
            color: #94a3b8;
        }

        /* Theme grid */
        .theme-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .theme-card {
            border: 2px solid #e7f0e7;
            border-radius: 14px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s;
            background: #f9fefb;
        }

        .theme-card:hover {
            border-color: #1eb349;
            transform: scale(1.02);
        }

        .theme-card.active {
            border-color: #1eb349;
            box-shadow: 0 0 0 4px rgba(30, 179, 73, 0.15);
        }

        .theme-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        .theme-label {
            padding: 0.6rem 0.8rem;
            font-weight: 700;
            font-size: 0.8rem;
            color: #0b120c;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* Block list */
        .block-item {
            background: #f9fefb;
            border: 1px solid #e7f0e7;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.65rem;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .block-item:hover {
            border-color: #1eb349;
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.12);
        }

        .block-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .block-info {
            flex: 1;
            min-width: 0;
        }

        .block-title {
            font-weight: 700;
            font-size: 0.85rem;
            color: #0b120c;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .block-sub {
            font-size: 0.72rem;
            color: #64748b;
        }

        .block-actions {
            display: flex;
            gap: 0.4rem;
            flex-shrink: 0;
        }

        .btn-icon-sm {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        /* Add block modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            background: #fff;
            border-radius: 24px;
            max-width: 500px;
            width: calc(100% - 2rem);
            padding: 2rem;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.25s;
        }

        .modal-box-wide {
            background: #fff;
            border-radius: 20px;
            max-width: 1440px;
            width: 96vw;
            padding: 1.5rem 1.75rem;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.22);
            animation: fadeIn 0.25s;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .modal-catalog-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 0.85rem;
            max-height: 65vh;
            overflow-y: auto;
            padding: 0.35rem;
        }

        .modal-card-item {
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            border-radius: 14px;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        }

        .modal-card-item:hover {
            border-color: #CBD5E1;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }

        @media (max-width: 1550px) {
            .modal-catalog-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .modal-catalog-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 850px) {
            .modal-catalog-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 550px) {
            .modal-box-wide {
                width: 95vw;
                padding: 1rem;
            }

            .modal-catalog-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
        }

        /* Uniform card action buttons */
        .modal-card-btn-detail,
        .modal-card-btn-add,
        .modal-card-btn-remove {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 8px;
            height: 32px;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: opacity 0.15s;
        }

        .modal-card-btn-detail {
            color: #475569;
            background: #F1F5F9;
            border: 1px solid #CBD5E1 !important;
        }

        .modal-card-btn-detail:hover {
            background: #E2E8F0;
        }

        .modal-card-btn-add {
            color: #fff;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
        }

        .modal-card-btn-add:hover {
            opacity: 0.9;
        }

        .modal-card-btn-remove {
            color: #DC2626;
            background: #FEF2F2;
            border: 1px solid #FCA5A5 !important;
        }

        .modal-card-btn-remove:hover {
            background: #FEE2E2;
        }

        /* Filter sort buttons */
        .modal-filter-btn {
            height: 44px;
            padding: 0 0.85rem;
            border-radius: 10px;
            border: 1.5px solid #E2E8F0;
            background: #FAFAFA;
            color: #64748B;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s;
        }

        .modal-filter-btn:hover {
            border-color: #94A3B8;
            background: #F1F5F9;
            color: #334155;
        }

        .modal-filter-btn.active {
            border-color: #1eb349;
            background: #F0FDF4;
            color: #166534;
        }

        .btn-submit-sm {
            height: 40px;
            padding: 0 1.25rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 0.82rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.32);
            transition: all 0.2s ease;
        }

        .spin-icon {
            animation: spin 0.8s linear infinite !important;
            transform-origin: center !important;
            display: inline-block !important;
        }

        /* Affiliate product card */
        .aff-card {
            border: 1px solid #e7f0e7;
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            display: flex;
            gap: 0;
            margin-bottom: 1rem;
        }

        .aff-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .aff-info {
            padding: 0.75rem;
            flex: 1;
        }

        .aff-title {
            font-weight: 700;
            font-size: 0.82rem;
            color: #0b120c;
            line-height: 1.3;
            margin-bottom: 0.3rem;
        }

        .aff-sub {
            font-size: 0.7rem;
            color: #64748b;
        }

        /* Icon Picker */
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
            gap: 0.5rem;
            max-height: 250px;
            overflow-y: auto;
            padding-right: 0.5rem;
        }

        .icon-grid::-webkit-scrollbar {
            width: 6px;
        }

        .icon-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .icon-btn {
            font-size: 24px;
            color: #64748b;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            padding: 0;
        }

        .icon-btn:hover {
            background: #e0f2fe;
            color: #0284c7;
            border-color: #7dd3fc;
            transform: scale(1.1);
        }

        /* Custom Confirm Modal */
        .confirm-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(6px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .confirm-modal-overlay.open {
            display: flex;
        }

        .confirm-modal-box {
            background: #fff;
            border-radius: 24px;
            max-width: 380px;
            width: calc(100% - 2rem);
            padding: 2rem;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.2s;
            text-align: center;
        }

        .confirm-modal-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .confirm-modal-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0b120c;
            margin-bottom: 0.4rem;
            font-family: 'Montserrat', sans-serif;
        }

        .confirm-modal-desc {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .confirm-modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .confirm-btn-cancel {
            height: 40px;
            padding: 0 1.25rem;
            border-radius: 999px;
            border: 1.5px solid #e7f0e7;
            background: #fff;
            color: #64748b;
            font-weight: 700;
            font-size: 0.82rem;
            cursor: pointer;
        }

        .confirm-btn-danger {
            height: 40px;
            padding: 0 1.5rem;
            border-radius: 999px;
            border: none;
            background: #ef4444;
            color: #fff;
            font-weight: 700;
            font-size: 0.82rem;
            cursor: pointer;
        }

        .confirm-btn-primary {
            height: 40px;
            padding: 0 1.5rem;
            border-radius: 999px;
            border: none;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            font-weight: 700;
            font-size: 0.82rem;
            cursor: pointer;
        }

        @media(max-width:768px) {
            .bio-layout {
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
                max-width: 100%;
                overflow-x: hidden;
            }

            .bio-sidebar {
                width: 100%;
                position: sticky;
                top: 64px;
                z-index: 80;
                padding: 0.25rem;
                border-radius: 14px;
                background: #ffffff;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
                border: 1px solid #e2e8f0;
                margin-bottom: 0.25rem;
                box-sizing: border-box;
            }

            .bio-tabs-list {
                display: grid !important;
                grid-template-columns: repeat(6, 1fr) !important;
                gap: 3px !important;
                background: #f8fafc;
                padding: 3px;
                border-radius: 12px;
                width: 100%;
                box-sizing: border-box;
            }

            .tab-btn {
                width: 100% !important;
                min-width: 0 !important;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 0.45rem 2px !important;
                gap: 2px;
                border-radius: 8px;
                margin-bottom: 0;
                border: none;
                box-sizing: border-box;
            }

            .tab-btn svg,
            .tab-btn i {
                width: 16px !important;
                height: 16px !important;
            }

            .tab-text-full {
                display: none !important;
            }

            .tab-text-mob {
                display: block !important;
                font-size: 0.6rem !important;
                font-weight: 600;
                line-height: 1.1;
                text-align: center;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 100%;
            }

            .sidebar-extra-info,
            .bio-mockup-wrapper {
                display: none !important;
            }

            /* Compact Mobile Cards & Form Elements */
            .prof-card {
                border-radius: 14px;
                margin-bottom: 0.85rem;
                width: 100%;
                box-sizing: border-box;
            }

            .prof-card-head {
                padding: 0.75rem 0.85rem;
                font-size: 0.825rem;
                font-weight: 700;
            }

            .card-body {
                padding: 0.85rem;
            }

            /* Convert all 2-column inline style grids inside cards to 1 column on mobile */
            .card-body [style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
                gap: 0.75rem !important;
            }

            .form-group {
                gap: 0.3rem;
                margin-bottom: 0.75rem;
                width: 100%;
            }

            .form-label {
                font-size: 0.75rem;
                font-weight: 700;
            }

            .form-input {
                height: 38px;
                padding: 0 0.65rem;
                font-size: 0.8rem;
                border-radius: 8px;
                width: 100%;
                box-sizing: border-box;
            }

            textarea.form-input {
                padding: 0.5rem 0.65rem;
                min-height: 65px;
            }

            .theme-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.5rem !important;
            }

            .theme-mockup {
                height: 100px;
                padding-top: 0.75rem;
            }

            .theme-label {
                padding: 0.4rem 0.5rem;
                font-size: 0.72rem;
            }

            .block-item {
                padding: 0.6rem 0.65rem;
                gap: 0.5rem;
                border-radius: 10px;
                margin-bottom: 0.5rem;
            }

            .block-icon {
                width: 30px;
                height: 30px;
                border-radius: 8px;
            }

            .block-title {
                font-size: 0.78rem;
            }

            .block-sub {
                font-size: 0.65rem;
            }

            .aff-card {
                border-radius: 10px;
                margin-bottom: 0.5rem;
            }

            .aff-img {
                width: 60px;
                height: 60px;
            }

            .aff-info {
                padding: 0.45rem;
            }

            .aff-title {
                font-size: 0.75rem;
            }

            .btn-submit-sm,
            .btn-primary {
                font-size: 0.8rem;
                height: 38px;
                padding: 0 1rem;
            }
        }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg)
            }

            to {
                transform: rotate(360deg)
            }
        }

        /* Theme Mockups */
        /* Theme Mockups */
        .theme-mockup {
            width: 100%;
            height: 140px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 1.5rem;
            gap: 0.5rem;
        }

        .mockup-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
        }

        .mockup-title {
            width: 50px;
            height: 6px;
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }

        .mockup-btn {
            width: 75%;
            height: 14px;
        }

        /* Theme 1: Gelap Elegan */
        .theme-mockup.theme1 {
            background: #0b120c;
        }

        .theme-mockup.theme1 .mockup-avatar {
            background: #1a231b;
            border: 1px solid #1eb349;
        }

        .theme-mockup.theme1 .mockup-title {
            background: #fff;
        }

        .theme-mockup.theme1 .mockup-btn {
            background: #1a231b;
            border: 1px solid #1eb349;
            border-radius: 6px;
        }

        /* Theme 2: Minimalis Pro */
        .theme-mockup.theme2 {
            background: #f8fafc;
        }

        .theme-mockup.theme2 .mockup-avatar {
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
        }

        .theme-mockup.theme2 .mockup-title {
            background: #0b120c;
        }

        .theme-mockup.theme2 .mockup-btn {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
        }

        /* Theme 3: Gradient Neon */
        .theme-mockup.theme3 {
            background: linear-gradient(135deg, #1e293b, #0f172a);
        }

        .theme-mockup.theme3 .mockup-avatar {
            background: #334155;
        }

        .theme-mockup.theme3 .mockup-title {
            background: #38bdf8;
        }

        .theme-mockup.theme3 .mockup-btn {
            background: transparent;
            border: 1.5px solid #38bdf8;
            border-radius: 6px;
        }

        /* Theme 4: Clean Light */
        .theme-mockup.theme4 {
            background: #ffffff;
        }

        .theme-mockup.theme4 .mockup-avatar {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
        }

        .theme-mockup.theme4 .mockup-title {
            background: #334155;
        }

        .theme-mockup.theme4 .mockup-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }

        /* Theme 5: Landing Page Pro */
        .theme-mockup.theme5 {
            background: #f8fafc;
            border-left: 3px solid #0f172a;
        }

        .theme-mockup.theme5 .mockup-avatar {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 6px;
        }

        .theme-mockup.theme5 .mockup-title {
            background: #0f172a;
        }

        .theme-mockup.theme5 .mockup-btn {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    @php
        $cfg = $profile->bio_config ?? [];
        $currentTheme = $profile->bio_theme ?? 'theme1';
        $roleLabels = ['content_creator' => 'Content Creator', 'affiliator' => 'Affiliator', 'business' => 'Business / Brand'];
        $bioUrl = $profile->store_slug ? url('/' . $profile->store_slug) : null;
    @endphp

    <div class="bio-layout">

        {{-- Sidebar Nav --}}
        <div class="bio-sidebar">
            <div class="bio-tabs-list">
                <button class="tab-btn active" data-tab="tab-theme">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3" />
                        <path
                            d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                    </svg>
                    <span class="tab-text-full">Tampilan & Tema</span>
                    <small class="tab-text-mob">Tema</small>
                </button>
                <button class="tab-btn" data-tab="tab-profile">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M20 21a8 8 0 1 0-16 0" />
                    </svg>
                    <span class="tab-text-full">Pengaturan Profil</span>
                    <small class="tab-text-mob">Profil</small>
                </button>
                <button class="tab-btn" data-tab="tab-social">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M22 12A10 10 0 1 0 12 22a10 10 0 0 0 10-10zM8 12a4 4 0 1 0 8 0 4 4 0 0 0-8 0z" />
                    </svg>
                    <span class="tab-text-full">Social Links</span>
                    <small class="tab-text-mob">Social</small>
                </button>
                <button class="tab-btn" data-tab="tab-blocks">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    <span class="tab-text-full">Kelola Block</span>
                    <small class="tab-text-mob">Block</small>
                </button>
                <button class="tab-btn" data-tab="tab-catalog">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    <span class="tab-text-full">Katalog & Affiliate</span>
                    <small class="tab-text-mob">Katalog</small>
                </button>
                <button class="tab-btn" data-tab="tab-embed">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    <span class="tab-text-full">Lokasi / Map</span>
                    <small class="tab-text-mob">Lokasi</small>
                </button>
            </div>

            <div class="sidebar-extra-info" style="margin-top:1.5rem; padding-top:1rem; border-top:1px solid #e7f0e7;">
                <div
                    style="font-size:0.7rem; color:#94a3b8; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.3rem;">
                    Tipe Profil</div>
                <div style="font-size:0.82rem; font-weight:700; color:#1eb349;">{{ $roleLabels[$profile->bio_role] ?? '-' }}
                </div>
                @if($bioUrl)
                    <a href="{{ $bioUrl }}" target="_blank"
                        style="display:flex; align-items:center; gap:0.4rem; margin-top:0.5rem; font-size:0.72rem; color:#64748b; text-decoration:none; word-break:break-all;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke-linecap="round" />
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke-linecap="round" />
                        </svg>
                        {{ $bioUrl }}
                    </a>
                @endif
            </div>

            {{-- Mobile Preview Mockup --}}
            @if($bioUrl)
                <div class="bio-mockup-wrapper" style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #e7f0e7;">
                    <div
                        style="font-size:0.7rem; color:#94a3b8; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:1rem; text-align:center;">
                        Live Preview</div>
                    <div
                        style="width: 260px; height: 530px; margin: 0 auto; border: 12px solid #1a1a1a; border-radius: 36px; overflow: hidden; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); background:#fff;">
                        {{-- Notch --}}
                        <div
                            style="position:absolute; top:0; left:50%; transform:translateX(-50%); width:100px; height:20px; background:#1a1a1a; border-bottom-left-radius:12px; border-bottom-right-radius:12px; z-index:10;">
                        </div>
                        <iframe src="{{ $bioUrl }}" style="width:100%; height:100%; border:none; background:#fff;"
                            id="bioPreviewFrame"></iframe>
                    </div>
                    <p style="text-align:center; font-size:0.7rem; color:#94a3b8; margin-top:0.75rem;">Perubahan profil akan
                        terupdate otomatis saat disimpan.</p>
                </div>
            @endif
        </div>

        {{-- Main Content --}}
        <div class="bio-content">

            {{-- ══ TAB 1: TEMA ══ --}}
            <div class="tab-pane active" id="tab-theme">
                <div class="prof-card">
                    <div class="prof-card-head">
                        <span>Pilih Tema Visual</span>
                    </div>
                    <div class="card-body">
                        <p style="font-size:0.82rem; color:#64748b; margin-bottom:1.5rem;">Tema menentukan tampilan halaman
                            publik Link in Bio Anda. Klik untuk memilih, lalu klik Simpan.</p>
                        <form action="{{ route('creator.bio.save-theme') }}" method="POST">
                            @csrf
                            <div class="theme-grid">
                                @foreach(['theme1' => 'Gelap Elegan', 'theme2' => 'Minimalis Pro', 'theme3' => 'Gradient Neon', 'theme4' => 'Clean Light', 'theme5' => 'Landing Page Pro'] as $key => $label)
                                    <label class="theme-card {{ $currentTheme === $key ? 'active' : '' }}">
                                        <input type="radio" name="bio_theme" value="{{ $key }}" {{ $currentTheme === $key ? 'checked' : '' }} style="display:none;" onchange="this.closest('form').submit()">
                                        <div class="theme-mockup {{ $key }}">
                                            <div class="mockup-avatar"></div>
                                            <div class="mockup-title"></div>
                                            <div class="mockup-btn"></div>
                                            <div class="mockup-btn"></div>
                                        </div>
                                        <div class="theme-label">
                                            @if($currentTheme === $key) <svg width="14" height="14" fill="#1eb349"
                                                viewBox="0 0 24 24">
                                                <path d="M20 6L9 17l-5-5" />
                                            </svg> @endif
                                            {{ $label }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Custom Background & Color Customizer --}}
                <div class="prof-card" style="margin-top:0;">
                    <div class="prof-card-head">
                        <span>Kustomisasi Background & Warna</span>
                        <span style="font-size:0.72rem; font-weight:600; color:#64748b;">Override tema (Warna Kustom /
                            Gambar WebP)</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('creator.bio.save-profile') }}" method="POST" id="colorForm"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="bio_name" value="{{ $cfg['name'] ?? '' }}">
                            <input type="hidden" name="bio_bio" value="{{ $cfg['bio'] ?? '' }}">
                            <input type="hidden" name="bio_location" value="{{ $cfg['location'] ?? '' }}">
                            <input type="hidden" name="bio_wa" value="{{ $cfg['wa'] ?? '' }}">
                            <input type="hidden" name="bio_ig" value="{{ $cfg['ig'] ?? '' }}">
                            <input type="hidden" name="bio_tiktok" value="{{ $cfg['tiktok'] ?? '' }}">
                            <input type="hidden" name="bio_youtube" value="{{ $cfg['youtube'] ?? '' }}">

                            @php
                                $curBgType = $cfg['bg_type'] ?? 'color';
                                $curBgImg = $cfg['bg_image'] ?? null;
                            @endphp

                            {{-- 2 Opsi Background --}}
                            <div style="margin-bottom: 1.25rem;">
                                <label class="form-label"
                                    style="font-weight:700; margin-bottom:0.5rem; display:block;">Pilih Tipe
                                    Background</label>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                                    <label id="bg_mode_card_color" class="bg-mode-card"
                                        style="display:flex; align-items:center; gap:0.6rem; padding:0.75rem 1rem; border-radius:10px; border:2px solid {{ $curBgType === 'color' ? '#1eb349' : '#e2e8f0' }}; cursor:pointer; background: {{ $curBgType === 'color' ? '#f0fdf4' : '#fff' }};">
                                        <input type="radio" name="bg_type" value="color" {{ $curBgType === 'color' ? 'checked' : '' }} onchange="toggleBgMode('color')" style="accent-color:#1eb349;">
                                        <div>
                                            <strong
                                                style="display:flex; align-items:center; gap:0.35rem; font-size:0.85rem; color:#0f172a;">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path
                                                        d="M12 21a9 9 0 1 1 0-18c4.97 0 9 3.58 9 8 0 2.21-1.79 4-4 4h-1.5c-.83 0-1.5.67-1.5 1.5 0 .39.15.74.39 1.01l.21.24c.4.45.65 1.05.65 1.75 0 1.38-1.12 2.5-2.5 2.5z" />
                                                    <circle cx="7.5" cy="11.5" r="1.5" />
                                                    <circle cx="12" cy="7.5" r="1.5" />
                                                    <circle cx="16.5" cy="11.5" r="1.5" />
                                                </svg>
                                                Warna Kustom
                                            </strong>
                                            <span style="font-size:0.73rem; color:#64748b;">Warna solid / gradasi</span>
                                        </div>
                                    </label>
                                    <label id="bg_mode_card_image" class="bg-mode-card"
                                        style="display:flex; align-items:center; gap:0.6rem; padding:0.75rem 1rem; border-radius:10px; border:2px solid {{ $curBgType === 'image' ? '#1eb349' : '#e2e8f0' }}; cursor:pointer; background: {{ $curBgType === 'image' ? '#f0fdf4' : '#fff' }};">
                                        <input type="radio" name="bg_type" value="image" {{ $curBgType === 'image' ? 'checked' : '' }} onchange="toggleBgMode('image')" style="accent-color:#1eb349;">
                                        <div>
                                            <strong
                                                style="display:flex; align-items:center; gap:0.35rem; font-size:0.85rem; color:#0f172a;">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                                    <polyline points="21 15 16 10 5 21" />
                                                </svg>
                                                Gambar Custom
                                            </strong>
                                            <span style="font-size:0.73rem; color:#64748b;">Upload (Auto WebP)</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Sub-panel Warna Background --}}
                            <div id="bg_color_panel"
                                style="display: {{ $curBgType === 'color' ? 'block' : 'none' }}; margin-bottom:1rem; background:#f8fafc; padding:0.85rem; border-radius:10px; border:1px solid #e2e8f0;">
                                <label class="form-label"
                                    style="font-weight:600; margin-bottom:0.4rem; display:block;">Pilih Warna
                                    Background</label>
                                <div style="display:flex; gap:0.5rem; align-items:center;">
                                    <input type="color" name="color_bg" id="color_bg_picker"
                                        value="{{ $cfg['color_bg'] ?? '#0b120c' }}"
                                        style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;"
                                        oninput="syncColor('color_bg', this.value)">
                                    <input type="text" id="color_bg_text" value="{{ $cfg['color_bg'] ?? '#0b120c' }}"
                                        class="form-input" style="flex:1;"
                                        oninput="syncColor('color_bg', this.value, true)">
                                </div>
                            </div>

                            {{-- Sub-panel Gambar Background --}}
                            <div id="bg_image_panel"
                                style="display: {{ $curBgType === 'image' ? 'block' : 'none' }}; margin-bottom:1rem; background:#f8fafc; padding:0.85rem; border-radius:10px; border:1px solid #e2e8f0;">
                                <label class="form-label"
                                    style="font-weight:600; margin-bottom:0.4rem; display:block;">Upload Gambar Background
                                    (Otomatis Konversi WebP)</label>
                                @if(!empty($curBgImg))
                                    <div
                                        style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem; background:#fff; padding:0.5rem 0.75rem; border-radius:8px; border:1px solid #e2e8f0;">
                                        <img src="{{ asset('storage/' . $curBgImg) }}" id="bg_image_thumb"
                                            style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                                        <div style="flex:1;">
                                            <span
                                                style="font-size:0.78rem; color:#475569; display:block; font-weight:600;">Gambar
                                                Terpasang (.webp)</span>
                                            <label
                                                style="font-size:0.75rem; color:#ef4444; cursor:pointer; display:inline-flex; align-items:center; gap:4px; margin-top:2px;">
                                                <input type="checkbox" name="delete_bg_image" value="1"
                                                    onchange="previewBgDelete(this)"> Hapus Gambar
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="bio_bg_image" id="bio_bg_image_input" accept="image/*"
                                    class="form-input" style="height:auto; padding:0.5rem;"
                                    onchange="previewUploadedBgImage(this)">
                                <span
                                    style="font-size:0.72rem; color:#64748b; margin-top:4px; display:flex; align-items:center; gap:4px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1eb349"
                                        stroke-width="2">
                                        <path
                                            d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z" />
                                    </svg>
                                    Semua format gambar (JPG, PNG, WebP) akan otomatis di-convert ke WebP resolusi tinggi
                                    secara ringan & cepat.
                                </span>
                            </div>

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                                <div class="form-group" style="margin-bottom:0.75rem;">
                                    <label class="form-label">Warna Teks</label>
                                    <div style="display:flex; gap:0.5rem; align-items:center;">
                                        <input type="color" name="color_text" id="color_text_picker"
                                            value="{{ $cfg['color_text'] ?? '#ffffff' }}"
                                            style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;"
                                            oninput="syncColor('color_text', this.value)">
                                        <input type="text" id="color_text_text"
                                            value="{{ $cfg['color_text'] ?? '#ffffff' }}" class="form-input" style="flex:1;"
                                            oninput="syncColor('color_text', this.value, true)">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.75rem;">
                                    <label class="form-label">Warna Tombol</label>
                                    <div style="display:flex; gap:0.5rem; align-items:center;">
                                        <input type="color" name="color_btn" id="color_btn_picker"
                                            value="{{ $cfg['color_btn'] ?? '#1eb349' }}"
                                            style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;"
                                            oninput="syncColor('color_btn', this.value)">
                                        <input type="text" id="color_btn_text" value="{{ $cfg['color_btn'] ?? '#1eb349' }}"
                                            class="form-input" style="flex:1;"
                                            oninput="syncColor('color_btn', this.value, true)">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.75rem;">
                                    <label class="form-label">Teks Tombol</label>
                                    <div style="display:flex; gap:0.5rem; align-items:center;">
                                        <input type="color" name="color_btn_text" id="color_btn_text_picker"
                                            value="{{ $cfg['color_btn_text'] ?? '#ffffff' }}"
                                            style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;"
                                            oninput="syncColor('color_btn_text', this.value)">
                                        <input type="text" id="color_btn_text_text"
                                            value="{{ $cfg['color_btn_text'] ?? '#ffffff' }}" class="form-input"
                                            style="flex:1;" oninput="syncColor('color_btn_text', this.value, true)">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.75rem;">
                                    <label class="form-label">Warna Aksen</label>
                                    <div style="display:flex; gap:0.5rem; align-items:center;">
                                        <input type="color" name="color_accent" id="color_accent_picker"
                                            value="{{ $cfg['color_accent'] ?? '#1eb349' }}"
                                            style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;"
                                            oninput="syncColor('color_accent', this.value)">
                                        <input type="text" id="color_accent_text"
                                            value="{{ $cfg['color_accent'] ?? '#1eb349' }}" class="form-input"
                                            style="flex:1;" oninput="syncColor('color_accent', this.value, true)">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0.75rem;">
                                    <label class="form-label">Warna Card</label>
                                    <div style="display:flex; gap:0.5rem; align-items:center;">
                                        <input type="color" name="color_card" id="color_card_picker"
                                            value="{{ $cfg['color_card'] ?? '#1a231b' }}"
                                            style="width:44px; height:40px; border-radius:8px; border:1.5px solid #e2e8f0; padding:2px; cursor:pointer; flex-shrink:0;"
                                            oninput="syncColor('color_card', this.value)">
                                        <input type="text" id="color_card_text"
                                            value="{{ $cfg['color_card'] ?? '#1a231b' }}" class="form-input" style="flex:1;"
                                            oninput="syncColor('color_card', this.value, true)">
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top:0.75rem; border-radius:14px; overflow:hidden; border:1px solid #e2e8f0;">
                                <div id="cpBg"
                                    style="padding:1.25rem; display:flex; flex-direction:column; align-items:center; gap:0.6rem; {{ $curBgType === 'image' && !empty($curBgImg) ? 'background: url(' . asset('storage/' . $curBgImg) . ') center center / cover no-repeat;' : 'background:' . ($cfg['color_bg'] ?? '#0b120c') . ';' }}">
                                    <div id="cpCard"
                                        style="width:46px; height:46px; border-radius:50%; background:{{ $cfg['color_card'] ?? '#1a231b' }}; border:2px solid {{ $cfg['color_accent'] ?? '#1eb349' }};">
                                    </div>
                                    <div id="cpText"
                                        style="font-weight:700; font-size:0.88rem; color:{{ $cfg['color_text'] ?? '#ffffff' }};">
                                        Preview Nama Anda</div>
                                    <div id="cpBtn"
                                        style="padding:0.45rem 1.25rem; border-radius:999px; font-size:0.78rem; font-weight:700; background:{{ $cfg['color_btn'] ?? '#1eb349' }}; color:{{ $cfg['color_btn_text'] ?? '#ffffff' }};">
                                        Tombol Contoh</div>
                                </div>
                            </div>
                            <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem;">
                                <button type="button" onclick="resetColors()"
                                    style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Reset
                                    Default</button>
                                <button type="submit" class="btn-submit-sm">Simpan Kustomisasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ══ TAB 2: PROFIL ══ --}}
            <div class="tab-pane" id="tab-profile">
                <form action="{{ route('creator.bio.save-profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="prof-card">
                        <div class="prof-card-head">Informasi Profil Kreator</div>
                        <div class="card-body">
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                                <div class="form-group">
                                    <label class="form-label">Nama Tampilan</label>
                                    <input type="text" name="bio_name"
                                        value="{{ old('bio_name', $cfg['name'] ?? $profile->store_name) }}"
                                        class="form-input">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Username / URL Publik</label>
                                    <div
                                        style="display:flex; align-items:center; border:1.5px solid #e7f0e7; border-radius:10px; background:#f9fefb; overflow:hidden;">
                                        <span
                                            style="padding:0 0.75rem; color:#94a3b8; font-size:0.8rem; border-right:1.5px solid #e7f0e7; background:#f1f5f9; height:44px; display:flex; align-items:center;">buyle.id/</span>
                                        <input type="text" name="bio_username"
                                            value="{{ old('bio_username', $profile->store_slug) }}"
                                            style="height:44px; border:none; background:transparent; padding:0 1rem; font-family:'Montserrat',sans-serif; font-size:0.875rem; color:#1a1a1a; outline:none; flex:1;"
                                            placeholder="username">
                                    </div>
                                    @error('bio_username')<span
                                    style="font-size:0.72rem; color:#ef4444;">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group" style="grid-column:1/-1;">
                                    <label class="form-label">Bio / Tagline</label>
                                    <textarea name="bio_bio" class="form-input" rows="2" maxlength="300"
                                        placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio_bio', $cfg['bio'] ?? '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Lokasi</label>
                                    <input type="text" name="bio_location"
                                        value="{{ old('bio_location', $cfg['location'] ?? '') }}" class="form-input"
                                        placeholder="Jakarta, Indonesia">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="prof-card">
                        <div class="prof-card-head">Foto Profil & Cover</div>
                        <div class="card-body" style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                            <div class="form-group">
                                <label class="form-label">Foto Profil / Avatar</label>
                                @if(!empty($cfg['avatar']))
                                    <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.5rem;">
                                        <img src="{{ asset('storage/' . $cfg['avatar']) }}"
                                            style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid #1eb349;">
                                        <label
                                            style="font-size:0.75rem; color:#ef4444; display:flex; align-items:center; gap:0.25rem; cursor:pointer;">
                                            <input type="checkbox" name="delete_avatar" value="1"> Hapus Avatar
                                        </label>
                                    </div>
                                @endif
                                <input type="file" name="bio_avatar" accept="image/*" class="form-input"
                                    style="height:auto; padding:0.5rem;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Foto Cover / Banner</label>
                                @if(!empty($cfg['cover']))
                                    <div style="margin-bottom:0.5rem;">
                                        <img src="{{ asset('storage/' . $cfg['cover']) }}"
                                            style="width:100%; height:60px; border-radius:10px; object-fit:cover; margin-bottom:0.25rem;">
                                        <label
                                            style="font-size:0.75rem; color:#ef4444; display:flex; align-items:center; gap:0.25rem; cursor:pointer;">
                                            <input type="checkbox" name="delete_cover" value="1"> Hapus Cover
                                        </label>
                                    </div>
                                @endif
                                <input type="file" name="bio_cover" accept="image/*" class="form-input"
                                    style="height:auto; padding:0.5rem;">
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end;">
                        <button type="submit" class="btn-submit-sm">Simpan Profil</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane" id="tab-social">
                <form action="{{ route('creator.bio.save-profile') }}" method="POST">
                    @csrf
                    <div class="prof-card">
                        <div class="prof-card-head">Social Media</div>
                        <div class="card-body">
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">

                                {{-- WhatsApp --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#25D366"
                                            style="vertical-align:middle;margin-right:4px">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                            <path
                                                d="M12 0C5.373 0 0 5.373 0 12c0 2.124.556 4.117 1.528 5.849L0 24l6.335-1.508A11.948 11.948 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.65-.52-5.154-1.422l-.37-.218-3.764.896.924-3.667-.243-.381A9.953 9.953 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z" />
                                        </svg>
                                        WhatsApp
                                    </label>
                                    <input type="text" name="bio_wa" value="{{ old('bio_wa', $cfg['wa'] ?? '') }}"
                                        class="form-input" placeholder="628xxxxxxxxx">
                                </div>

                                {{-- Instagram --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                            style="vertical-align:middle;margin-right:4px">
                                            <defs>
                                                <linearGradient id="ig2" x1="0%" y1="100%" x2="100%" y2="0%">
                                                    <stop offset="0%" stop-color="#f09433" />
                                                    <stop offset="50%" stop-color="#dc2743" />
                                                    <stop offset="100%" stop-color="#bc1888" />
                                                </linearGradient>
                                            </defs>
                                            <rect x="2" y="2" width="20" height="20" rx="5.5" fill="url(#ig2)" />
                                            <circle cx="12" cy="12" r="4.5" fill="none" stroke="white" stroke-width="1.8" />
                                            <circle cx="17.5" cy="6.5" r="1.2" fill="white" />
                                        </svg>
                                        Instagram
                                    </label>
                                    <input type="text" name="bio_ig" value="{{ old('bio_ig', $cfg['ig'] ?? '') }}"
                                        class="form-input" placeholder="@username">
                                </div>

                                {{-- TikTok --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"
                                            style="vertical-align:middle;margin-right:4px">
                                            <path
                                                d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.86 4.43 6.2 6.2 0 0 0 1.91-4.42V8.92a8.28 8.28 0 0 0 4.82 1.55v-3.47a4.91 4.91 0 0 1-1-.31z" />
                                        </svg>
                                        TikTok
                                    </label>
                                    <input type="text" name="bio_tiktok"
                                        value="{{ old('bio_tiktok', $cfg['tiktok'] ?? '') }}" class="form-input"
                                        placeholder="@username">
                                </div>

                                {{-- YouTube --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                            style="vertical-align:middle;margin-right:4px">
                                            <path
                                                d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"
                                                fill="#FF0000" />
                                        </svg>
                                        YouTube
                                    </label>
                                    <input type="text" name="bio_youtube"
                                        value="{{ old('bio_youtube', $cfg['youtube'] ?? '') }}" class="form-input"
                                        placeholder="@channel atau URL">
                                </div>

                                {{-- Facebook --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#1877F2"
                                            style="vertical-align:middle;margin-right:4px">
                                            <path
                                                d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.428c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.234 2.686.234v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z" />
                                        </svg>
                                        Facebook
                                    </label>
                                    <input type="text" name="bio_facebook"
                                        value="{{ old('bio_facebook', $cfg['facebook'] ?? '') }}" class="form-input"
                                        placeholder="username atau URL">
                                </div>

                                {{-- X / Twitter --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"
                                            style="vertical-align:middle;margin-right:4px">
                                            <path
                                                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                        </svg>
                                        X / Twitter
                                    </label>
                                    <input type="text" name="bio_x" value="{{ old('bio_x', $cfg['x'] ?? '') }}"
                                        class="form-input" placeholder="@username">
                                </div>

                                {{-- Threads --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"
                                            style="vertical-align:middle;margin-right:4px">
                                            <path
                                                d="M12.186 24h-.007c-3.581-.024-6.334-1.205-8.184-3.509C2.35 18.44 1.5 15.586 1.5 12.068c0-3.507.857-6.355 2.49-8.414C5.845 1.348 8.6.166 12.18.142h.014c2.746.019 5.037.744 6.811 2.154 1.828 1.454 3.02 3.552 3.547 6.236l-2.937.578c-.39-2.013-1.252-3.59-2.565-4.684-1.266-1.055-2.985-1.601-5.114-1.609-2.468.017-4.39.763-5.714 2.219C4.886 6.484 4.217 8.677 4.217 12.07c0 3.4.665 5.59 1.997 7.038 1.327 1.455 3.252 2.2 5.725 2.218 1.832-.011 3.38-.419 4.6-1.213 1.332-.867 2.093-2.14 2.261-3.785.17-1.65-.22-2.985-.937-3.76-.607-.657-1.485-1.032-2.523-1.087-.164 2.044-.741 3.51-1.717 4.366a4.06 4.06 0 0 1-2.757.974c-1.072 0-2.006-.341-2.703-1.007-.742-.706-1.12-1.712-1.062-2.833.11-2.152 1.792-3.676 4.382-3.676.5 0 .978.046 1.43.135a9.1 9.1 0 0 0-.014-.613c-.105-1.52-.885-2.314-2.316-2.361a3.43 3.43 0 0 0-.238-.008c-.921 0-1.76.341-2.375.96l-2.036-2.036C8.26 5.54 9.715 4.94 11.424 4.85a9.92 9.92 0 0 1 .388-.007c2.698 0 4.576 1.46 4.762 4.314.033.502.045 1.017.037 1.535a7.7 7.7 0 0 1 2.085.903c1.404.906 2.175 2.319 2.147 3.963-.005.26-.023.525-.055.793-.366 3.16-1.878 5.282-4.494 6.307-1.196.473-2.564.72-4.108.742z" />
                                        </svg>
                                        Threads
                                    </label>
                                    <input type="text" name="bio_threads"
                                        value="{{ old('bio_threads', $cfg['threads'] ?? '') }}" class="form-input"
                                        placeholder="@username">
                                </div>

                                {{-- LinkedIn --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#0A66C2"
                                            style="vertical-align:middle;margin-right:4px">
                                            <path
                                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                        </svg>
                                        LinkedIn
                                    </label>
                                    <input type="text" name="bio_linkedin"
                                        value="{{ old('bio_linkedin', $cfg['linkedin'] ?? '') }}" class="form-input"
                                        placeholder="username atau URL">
                                </div>

                                {{-- Pinterest --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#E60023"
                                            style="vertical-align:middle;margin-right:4px">
                                            <path
                                                d="M12 0C5.373 0 0 5.373 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 0 1 .083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z" />
                                        </svg>
                                        Pinterest
                                    </label>
                                    <input type="text" name="bio_pinterest"
                                        value="{{ old('bio_pinterest', $cfg['pinterest'] ?? '') }}" class="form-input"
                                        placeholder="username">
                                </div>

                                {{-- Telegram --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#26A5E4"
                                            style="vertical-align:middle;margin-right:4px">
                                            <path
                                                d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                                        </svg>
                                        Telegram
                                    </label>
                                    <input type="text" name="bio_telegram"
                                        value="{{ old('bio_telegram', $cfg['telegram'] ?? '') }}" class="form-input"
                                        placeholder="@username atau group">
                                </div>

                                {{-- Discord --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#5865F2"
                                            style="vertical-align:middle;margin-right:4px">
                                            <path
                                                d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057c.002.022.015.043.033.054a19.824 19.824 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z" />
                                        </svg>
                                        Discord
                                    </label>
                                    <input type="text" name="bio_discord"
                                        value="{{ old('bio_discord', $cfg['discord'] ?? '') }}" class="form-input"
                                        placeholder="https://discord.gg/...">
                                </div>

                                {{-- Snapchat --}}
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                            style="vertical-align:middle;margin-right:4px">
                                            <circle cx="12" cy="12" r="12" fill="#FFFC00" />
                                            <path
                                                d="M12.206 5.293c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.299 4.847l-.003.06c.053.022.11.03.171.03.317 0 .68-.13 1.038-.325.134-.073.36-.159.587-.159.14 0 .519.032.703.386.132.26.05.542-.23.77-.11.09-.699.51-1.599.933.12.343.25.7.42 1.035.49 1.013 1.244 1.814 2.298 2.404a.93.93 0 0 1-.24 1.742c-.374.062-.744.103-1.117.145-.49.055-.988.112-1.47.195-.5.087-.962.273-1.37.554-.415.286-.836.71-.836 1.436 0 .38.081.681.152.897-.137.12-.31.17-.484.17-.225 0-.44-.07-.62-.161-.376-.19-.739-.296-1.107-.296a3.02 3.02 0 0 0-.734.087c-.396.098-.792.302-1.213.54-.394.224-.809.462-1.306.548a2.8 2.8 0 0 1-.442.036c-.464 0-.9-.114-1.28-.319-.426-.23-.786-.42-1.163-.507a3.11 3.11 0 0 0-.728-.083c-.367 0-.73.104-1.102.292-.177.09-.383.16-.59.16-.186 0-.374-.054-.52-.18-.232-.196-.152-.432-.054-.725.074-.217.156-.518.156-.9 0-.71-.41-1.13-.817-1.417a4.45 4.45 0 0 0-1.361-.558c-.48-.088-.976-.146-1.463-.202a11.7 11.7 0 0 1-1.14-.148.935.935 0 0 1-.23-1.744c1.053-.591 1.809-1.392 2.297-2.405.172-.333.3-.694.42-1.035-.905-.425-1.492-.842-1.6-.932-.28-.228-.36-.51-.23-.77.184-.355.563-.387.703-.387.225 0 .455.086.587.16.353.193.712.323 1.027.323.064 0 .124-.009.18-.032l-.033-.569c-.104-1.628-.23-3.655.299-4.847C7.86 5.57 11.217 5.294 12.206 5.293z"
                                                fill="#000" />
                                        </svg>
                                        Snapchat
                                    </label>
                                    <input type="text" name="bio_snapchat"
                                        value="{{ old('bio_snapchat', $cfg['snapchat'] ?? '') }}" class="form-input"
                                        placeholder="username">
                                </div>

                            </div>

                            {{-- Website - full width --}}
                            <div class="form-group" style="margin-top:0.75rem;">
                                <label class="form-label">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1eb349"
                                        stroke-width="2" style="vertical-align:middle;margin-right:4px">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="2" y1="12" x2="22" y2="12" />
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                    </svg>
                                    Website / Portofolio / Linktree
                                </label>
                                <input type="url" name="bio_website" value="{{ old('bio_website', $cfg['website'] ?? '') }}"
                                    class="form-input" placeholder="https://portofolio-anda.com">
                            </div>

                            {{-- Food Delivery Merchant Links --}}
                            <div style="margin-top:1.5rem; padding-top:1.25rem; border-top:1px dashed #e2e8f0;">
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a; margin-bottom:0.85rem; display:flex; align-items:center; gap:0.4rem;">
                                    <svg width="18" height="18" fill="none" stroke="#e11d48" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                    </svg>
                                    Layanan Food Delivery (Order Online)
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.85rem;">
                                    <div class="form-group" style="margin:0;">
                                        <label class="form-label" style="font-size:0.75rem; font-weight:700; color:#dc2626;">GoFood Link</label>
                                        <input type="url" name="bio_gofood" value="{{ old('bio_gofood', $cfg['gofood'] ?? '') }}"
                                            class="form-input" placeholder="https://gofood.link/u/..." style="font-size:0.78rem;">
                                    </div>
                                    <div class="form-group" style="margin:0;">
                                        <label class="form-label" style="font-size:0.75rem; font-weight:700; color:#16a34a;">GrabFood Link</label>
                                        <input type="url" name="bio_grabfood" value="{{ old('bio_grabfood', $cfg['grabfood'] ?? '') }}"
                                            class="form-input" placeholder="https://food.grab.com/..." style="font-size:0.78rem;">
                                    </div>
                                    <div class="form-group" style="margin:0;">
                                        <label class="form-label" style="font-size:0.75rem; font-weight:700; color:#ea580c;">ShopeeFood Link</label>
                                        <input type="url" name="bio_shopeefood" value="{{ old('bio_shopeefood', $cfg['shopeefood'] ?? '') }}"
                                            class="form-input" placeholder="https://shopee.co.id/universal-link/now-food/m/..." style="font-size:0.78rem;">
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex; justify-content:flex-end; margin-top:1.25rem;">
                                <button type="submit" class="btn-submit-sm">Simpan Social & Food Delivery</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ══ TAB 4: BLOCKS ══ --}}
            <div class="tab-pane" id="tab-blocks">
                <div class="prof-card">
                    <div class="prof-card-head">
                        <span>Block Aktif</span>
                        <button onclick="document.getElementById('addBlockModal').classList.add('open')"
                            class="btn-submit-sm">+ Tambah Block</button>
                    </div>
                    <div class="card-body" id="sortable-blocks">
                        @php
                            $typeBlocks = $blocks->reject(fn($b) => in_array($b->type, ['buyle_product', 'buyle_affiliate', 'custom_product']));
                        @endphp
                        @forelse($typeBlocks as $block)
                            <div class="block-item" data-id="{{ $block->id }}"
                                style="{{ !$block->is_active ? 'opacity:0.5;' : '' }}">
                                <div class="drag-handle"
                                    style="cursor:grab; padding:0 0.5rem; color:#94a3b8; display:flex; align-items:center; touch-action:none;"
                                    title="Geser urutan block">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <circle cx="9" cy="6" r="1.5" fill="currentColor" />
                                        <circle cx="15" cy="6" r="1.5" fill="currentColor" />
                                        <circle cx="9" cy="12" r="1.5" fill="currentColor" />
                                        <circle cx="15" cy="12" r="1.5" fill="currentColor" />
                                        <circle cx="9" cy="18" r="1.5" fill="currentColor" />
                                        <circle cx="15" cy="18" r="1.5" fill="currentColor" />
                                    </svg>
                                </div>
                                <div class="block-icon"
                                    style="background:{{ ['link' => '#f0fdf4', 'pdf' => '#fef2f2', 'tiktok' => '#1a1a1a', 'reels' => '#fdf2f8'][$block->type] ?? '#f8fafc' }}; color:{{ ['link' => '#1eb349', 'pdf' => '#ef4444', 'tiktok' => '#fff', 'reels' => '#db2777'][$block->type] ?? '#64748b' }};">
                                    @if(!empty($block->data_json['image']))
                                        <img src="{{ Str::startsWith($block->data_json['image'], 'http') ? $block->data_json['image'] : asset('storage/' . $block->data_json['image']) }}"
                                            style="width:100%; height:100%; object-fit:cover; border-radius:6px;">
                                    @elseif(!empty($block->data_json['icon_class']))
                                        <i class="{{ $block->data_json['icon_class'] }}" style="font-size:18px;"></i>
                                    @elseif($block->type === 'link') <svg width="18" height="18" fill="none"
                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"
                                                stroke-linecap="round" />
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"
                                                stroke-linecap="round" />
                                        </svg>
                                    @elseif($block->type === 'pdf') <svg width="18" height="18" fill="none"
                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                        </svg>
                                    @elseif($block->type === 'reels') <svg width="18" height="18" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                        </svg>
                                    @else <svg width="18" height="18" fill="white" viewBox="0 0 24 24">
                                            <path
                                                d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.77 1.52V6.76a4.85 4.85 0 0 1-1-.07z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="block-info">
                                    <div class="block-title">{{ $block->title }}</div>
                                    <div class="block-sub">{{ $block->type }} · {{ Str::limit($block->url, 40) }}</div>
                                </div>
                                <div class="block-actions">
                                    <form action="{{ route('creator.bio.blocks.toggle', $block) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-icon-sm"
                                            style="background:{{ $block->is_active ? '#dcfce7' : '#f1f5f9' }}; color:{{ $block->is_active ? '#15803d' : '#94a3b8' }};"
                                            title="{{ $block->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="{{ $block->is_active ? 'M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z' : 'M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19' }}" />
                                                <circle cx="12" cy="12" r="3" {{ $block->is_active ? '' : 'style=display:none' }} />
                                            </svg>
                                        </button>
                                    </form>
                                    <button type="button" class="btn-icon-sm btn-edit-block" data-id="{{ $block->id }}"
                                        data-title="{{ addslashes($block->title) }}"
                                        data-url="{{ addslashes($block->url ?? '') }}"
                                        data-icon="{{ $block->data_json['icon_class'] ?? '' }}"
                                        style="background:#e0f2fe; color:#0284c7;" title="Edit">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('creator.bio.blocks.destroy', $block) }}" method="POST"
                                        class="form-delete-block" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-icon-sm btn-delete-block"
                                            style="background:#fef2f2; color:#ef4444;" title="Hapus">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                                <path d="M10 11v6M14 11v6" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.85rem;">
                                Belum ada block. Klik "+ Tambah Block" untuk menambahkan link, PDF, atau TikTok.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ══ TAB: LOKASI EMBED ══ --}}
            <div class="tab-pane" id="tab-embed">
                <div class="prof-card">
                    <div class="prof-card-head">
                        <span>Lokasi & Map Embed</span>
                    </div>
                    <div class="card-body">
                        <p style="font-size:0.82rem; color:#64748b; margin-bottom:1.5rem;">
                            Tambahkan Google Maps pada bagian bawah halaman bio Anda. Cocok untuk toko fisik, kantor, atau
                            lokasi acara.
                        </p>
                        <form action="{{ route('creator.bio.save-profile') }}" method="POST">
                            @csrf
                            <input type="hidden" name="bio_name" value="{{ $cfg['name'] ?? '' }}">
                            <input type="hidden" name="bio_bio" value="{{ $cfg['bio'] ?? '' }}">
                            <input type="hidden" name="bio_location" value="{{ $cfg['location'] ?? '' }}">
                            <input type="hidden" name="bio_wa" value="{{ $cfg['wa'] ?? '' }}">
                            <input type="hidden" name="bio_ig" value="{{ $cfg['ig'] ?? '' }}">
                            <input type="hidden" name="bio_tiktok" value="{{ $cfg['tiktok'] ?? '' }}">
                            <input type="hidden" name="bio_youtube" value="{{ $cfg['youtube'] ?? '' }}">
                            <input type="hidden" name="color_bg" value="{{ $cfg['color_bg'] ?? '' }}">
                            <input type="hidden" name="color_text" value="{{ $cfg['color_text'] ?? '' }}">
                            <input type="hidden" name="color_btn" value="{{ $cfg['color_btn'] ?? '' }}">
                            <input type="hidden" name="color_btn_text" value="{{ $cfg['color_btn_text'] ?? '' }}">
                            <input type="hidden" name="color_accent" value="{{ $cfg['color_accent'] ?? '' }}">
                            <input type="hidden" name="color_card" value="{{ $cfg['color_card'] ?? '' }}">
                            <input type="hidden" name="hero_size" value="{{ $cfg['hero_size'] ?? '200' }}">

                            <div class="form-group">
                                <label class="form-label">Kode Iframe Google Maps</label>
                                <textarea name="embed_location" class="form-input" style="height:100px; padding:0.75rem;"
                                    placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ $cfg['embed_location'] ?? '' }}</textarea>
                                <div
                                    style="font-size:0.75rem; color:#64748b; margin-top:0.5rem; background:#f8fafc; padding:0.75rem; border-radius:8px; border:1px solid #e2e8f0;">
                                    <strong style="color:#0b120c; display:block; margin-bottom:0.25rem;">Cara mendapatkan
                                        kode:</strong>
                                    1. Buka <a href="https://maps.google.com" target="_blank" style="color:#0284c7;">Google
                                        Maps</a> dan cari lokasi Anda.<br>
                                    2. Klik tombol <strong>Share (Bagikan)</strong>.<br>
                                    3. Pilih tab <strong>Embed a map (Sematkan peta)</strong>.<br>
                                    4. Klik <strong>Copy HTML (Salin HTML)</strong> dan paste ke dalam kotak di atas.
                                </div>
                            </div>

                            <div style="display:flex; justify-content:flex-end;">
                                <button type="submit" class="btn-submit-sm">Simpan Map</button>
                            </div>
                        </form>

                        @if(!empty($cfg['embed_location']))
                            <div style="margin-top:2rem;">
                                <h4 style="font-size:0.9rem; font-weight:700; margin-bottom:1rem;">Preview Map</h4>
                                <div style="border-radius:16px; overflow:hidden; border:2px solid #e2e8f0;">
                                    {!! $cfg['embed_location'] !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ══ TAB 4: KATALOG & AFFILIATE ══ --}}
            <div class="tab-pane" id="tab-catalog">

                {{-- 1. Affiliate buyle.id --}}
                <div class="prof-card"
                    style="border: 1px solid #E2E8F0; background: #ffffff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.03); margin-bottom:1.5rem; overflow:hidden;">
                    <div
                        style="display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; background:#F8FAFC; border-bottom:1px solid #F1F5F9; flex-wrap:wrap; gap:0.75rem;">
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <div
                                style="width:38px; height:38px; border-radius:10px; background:#F1F5F9; color:#334155; border:1px solid #E2E8F0; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                </svg>
                            </div>
                            <div>
                                <h3 style="font-size:0.95rem; font-weight:800; color:#0F172A; margin:0;">Katalog Produk
                                    Affiliate buyle.id</h3>
                                <p style="font-size:0.78rem; color:#64748B; margin:0.15rem 0 0;">Tampilkan produk affiliate
                                    buyle.id milik creator lain & dapatkan komisi penjualan.</p>
                            </div>
                        </div>
                        <button type="button"
                            onclick="document.getElementById('affiliateCatalogModal').classList.add('open')"
                            class="btn-submit-sm">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            Cari
                        </button>
                    </div>
                    <div class="card-body" style="padding:1.25rem;">

                        @php
                            $affiliateBlockIds = $affiliateProducts->pluck('id')->toArray();
                            $addedAffBlocks = $blocks->where('type', 'buyle_affiliate')->filter(function ($b) use ($affiliateBlockIds) {
                                $pId = $b->data_json['product_id'] ?? null;
                                return $pId && in_array((int) $pId, $affiliateBlockIds);
                            });
                        @endphp

                        @forelse($addedAffBlocks as $affBlock)
                            @php
                                $affProdId = $affBlock->data_json['product_id'] ?? null;
                                $affProd = $affiliateProducts->firstWhere('id', $affProdId) ?? ($affProdId ? \App\Models\Product::with('seller:id,name')->find($affProdId) : null);
                            @endphp
                            <div
                                style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:0.85rem 1rem; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap;">
                                @if($affProd)
                                    <img src="{{ $affProd->main_image }}"
                                        style="width:52px; height:52px; border-radius:10px; object-fit:cover; flex-shrink:0; border:1px solid #E2E8F0;">
                                @endif
                                <div style="flex:1; min-width:200px;">
                                    <div style="font-weight:700; font-size:0.875rem; color:#0F172A;">
                                        {{ $affBlock->title }}
                                    </div>
                                    <div style="font-size:0.75rem; color:#64748B; margin-top:0.2rem;">
                                        @if($affProd)
                                            Oleh: <strong style="color:#334155;">{{ $affProd->seller->name ?? 'Creator' }}</strong>
                                            &middot; Harga: <span style="color:#0F172A; font-weight:700;">Rp
                                                {{ number_format($affProd->sale_price ?? $affProd->price, 0, ',', '.') }}</span>
                                            &middot; Komisi: <span
                                                style="color:#166534; font-weight:700;">{{ $affProd->affiliate_commission_rate ?? 10 }}%
                                                (Rp
                                                {{ number_format(round((($affProd->sale_price ?? $affProd->price) * ($affProd->affiliate_commission_rate ?? 10)) / 100), 0, ',', '.') }})</span>
                                        @else
                                            Produk Affiliate buyle.id
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ route('creator.bio.blocks.destroy', $affBlock) }}" method="POST"
                                    class="form-delete-block" style="margin-left:auto;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete-block"
                                        style="background:#FEF2F2; color:#DC2626; border:1px solid #FCA5A5; padding:0.4rem 0.85rem; border-radius:8px; font-size:0.75rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.35rem;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        </svg>
                                        Hapus dari Bio
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p style="color:#94a3b8; font-size:0.85rem; text-align:center; padding:1.5rem 0; margin:0;">Belum
                                ada produk affiliate yang ditambahkan. Klik tombol di atas untuk memilih produk.</p>
                        @endforelse
                    </div>
                </div>

                {{-- 2. Produk White Label & Resell --}}
                <div class="prof-card"
                    style="border: 1px solid #E2E8F0; background: #ffffff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.03); margin-bottom:1.5rem; overflow:hidden;">
                    <div
                        style="display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; background:#F8FAFC; border-bottom:1px solid #F1F5F9; flex-wrap:wrap; gap:0.75rem;">
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <div
                                style="width:38px; height:38px; border-radius:10px; background:#F1F5F9; color:#334155; border:1px solid #E2E8F0; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                </svg>
                            </div>
                            <div>
                                <h3 style="font-size:0.95rem; font-weight:800; color:#0F172A; margin:0;">Katalog Produk
                                    White Label & Resell</h3>
                                <p style="font-size:0.78rem; color:#64748B; margin:0.15rem 0 0;">Bebas lisensi &Tentukan
                                    harga jual (markup) sendiri!</p>
                            </div>
                        </div>
                        <button type="button"
                            onclick="document.getElementById('whitelabelCatalogModal').classList.add('open')"
                            class="btn-submit-sm">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            Cari
                        </button>
                    </div>
                    <div class="card-body" style="padding:1.25rem;">

                        @php
                            $wlBlockIds = $whitelabelProducts->pluck('id')->toArray();
                            $addedWlBlocks = $blocks->where('type', 'buyle_product')->filter(function ($b) use ($wlBlockIds) {
                                $cat = strtolower(trim($b->data_json['category'] ?? ''));
                                if (in_array($cat, ['makanan', 'barang', 'jasa', 'lainnya', 'kuliner', 'fisik', 'umkm']))
                                    return false;
                                $title = strtolower($b->title ?? '');
                                if (preg_match('/(es|nasi|teh|kopi|jus|sirup|air|soto|bakso|mie|ayam|bebek|daging|ikan|kerupuk|lumpia|kasur|samsung|promo|sepatu|baju|celana)/i', $title))
                                    return false;
                                $pId = $b->data_json['product_id'] ?? null;
                                return $pId && in_array((int) $pId, $wlBlockIds);
                            });
                        @endphp

                        @forelse($addedWlBlocks as $wlBlock)
                            @php
                                $wlProdId = $wlBlock->data_json['product_id'] ?? null;
                                $wlProd = $whitelabelProducts->firstWhere('id', $wlProdId) ?? ($wlProdId ? \App\Models\Product::with('seller:id,name')->find($wlProdId) : null);
                            @endphp
                            <div
                                style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:0.85rem 1rem; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap;">
                                @if($wlProd)
                                    <img src="{{ $wlProd->main_image }}"
                                        style="width:52px; height:52px; border-radius:10px; object-fit:cover; flex-shrink:0; border:1px solid #E2E8F0;">
                                @endif
                                <div style="flex:1; min-width:200px;">
                                    <div style="font-weight:700; font-size:0.875rem; color:#0F172A;">
                                        {{ $wlBlock->title }}
                                    </div>
                                    <div style="font-size:0.75rem; color:#64748B; margin-top:0.2rem;">
                                        @if($wlProd)
                                            Oleh: <strong style="color:#334155;">{{ $wlProd->seller->name ?? 'Creator' }}</strong>
                                            @if($wlProd->whitelabel_price)
                                                &middot; Min. Resell: <span style="color:#0F172A; font-weight:700;">Rp
                                                    {{ number_format($wlProd->whitelabel_price, 0, ',', '.') }}</span>
                                            @else
                                                &middot; Harga Asli: <span style="color:#0F172A; font-weight:700;">Rp
                                                    {{ number_format($wlProd->price, 0, ',', '.') }}</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ route('creator.bio.blocks.destroy', $wlBlock) }}" method="POST"
                                    class="form-delete-block" style="margin-left:auto;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete-block"
                                        style="background:#FEF2F2; color:#DC2626; border:1px solid #FCA5A5; padding:0.4rem 0.85rem; border-radius:8px; font-size:0.75rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.35rem;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        </svg>
                                        Hapus dari Bio
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p style="color:#94a3b8; font-size:0.85rem; text-align:center; padding:1.5rem 0; margin:0;">Belum
                                ada produk White Label yang ditambahkan ke Bio.</p>
                        @endforelse
                    </div>
                </div>

                {{-- 3. Shopee Affiliate / Produk Eksternal --}}
                <div class="prof-card">
                    <div class="prof-card-head">
                        <span style="display:flex; align-items:center; gap:0.5rem;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                                <line x1="3" y1="6" x2="21" y2="6" />
                                <path d="M16 10a4 4 0 0 1-8 0" />
                            </svg>
                            Tambah Produk Affiliate (Shopee / Tokopedia)
                        </span>
                        <button onclick="document.getElementById('addAffModal').classList.add('open')"
                            class="btn-submit-sm">+ Tambah</button>
                    </div>
                    <div class="card-body">
                        @forelse($blocks->whereIn('type', ['shopee', 'affiliate']) as $block)
                            <div class="aff-card">
                                @if(!empty($block->data_json['image']))
                                    @php
                                        $affImgPath = $block->data_json['image'];
                                        $affImgUrl = \Illuminate\Support\Str::startsWith($affImgPath, 'http') ? $affImgPath : asset('storage/' . ltrim($affImgPath, '/'));
                                    @endphp
                                    <img src="{{ $affImgUrl }}" class="aff-img" onerror="this.style.display='none'">
                                @endif
                                <div class="aff-info">
                                    <div class="aff-title">{{ $block->title }}</div>
                                    <div class="aff-sub">{{ Str::limit($block->url, 50) }}</div>
                                </div>
                                <div style="padding:0.75rem; display:flex; align-items:center; gap:0.4rem;">
                                    <button type="button" class="btn-icon-sm btn-edit-block" data-id="{{ $block->id }}"
                                        data-title="{{ addslashes($block->title) }}"
                                        data-url="{{ addslashes($block->url ?? '') }}"
                                        data-icon="{{ $block->data_json['icon_class'] ?? '' }}"
                                        style="background:#e0f2fe; color:#0284c7;" title="Edit">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('creator.bio.blocks.destroy', $block) }}" method="POST"
                                        class="form-delete-block">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-icon-sm btn-delete-block"
                                            style="background:#fef2f2; color:#ef4444;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p style="color:#94a3b8; font-size:0.85rem; text-align:center; padding:1.5rem 0;">Belum ada link
                                eksternal (Shopee/Tokopedia). Masukkan link Shopee dan sistem akan mengambil gambar otomatis.
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- 4. Produk Fisik / UMKM --}}
                <div class="prof-card">
                    <div class="prof-card-head"
                        style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                        <span style="display:flex; align-items:center; gap:0.5rem;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                            Produk Fisik / UMKM
                        </span>
                        <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
                            {{-- Search --}}
                            <div style="position:relative; display:flex; align-items:center;">
                                <svg width="13" height="13" fill="none" stroke="#94a3b8" stroke-width="2.5"
                                    viewBox="0 0 24 24" style="position:absolute; left:0.55rem; pointer-events:none;">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                                <input type="text" id="umkmSearch" placeholder="Cari produk..."
                                    oninput="filterUmkmProducts()"
                                    style="height:32px; padding:0 0.75rem 0 2rem; border-radius:8px; border:1.5px solid #e2e8f0; font-size:0.75rem; background:#f8fafc; color:#1e293b; outline:none; width:150px;">
                            </div>
                            {{-- Sort --}}
                            <div style="display:flex; gap:0.25rem;">
                                <button id="sortTerbaru" onclick="sortUmkmProducts('terbaru')" title="Terbaru"
                                    style="height:32px; padding:0 0.65rem; border-radius:8px 0 0 8px; border:1.5px solid #e2e8f0; border-right:none; background:#0f172a; color:#fff; font-size:0.72rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.25rem;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <line x1="12" y1="19" x2="12" y2="5" />
                                        <polyline points="5 12 12 5 19 12" />
                                    </svg>
                                    Terbaru
                                </button>
                                <button id="sortTerlama" onclick="sortUmkmProducts('terlama')" title="Terlama"
                                    style="height:32px; padding:0 0.65rem; border-radius:0 8px 8px 0; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; font-size:0.72rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.25rem;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <polyline points="19 12 12 19 5 12" />
                                    </svg>
                                    Terlama
                                </button>
                            </div>
                            @if($blocks->where('type', 'custom_product')->count() > 0)
                                <form action="{{ route('creator.bio.blocks.destroy-all-custom-products') }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA produk fisik/UMKM dari Link Bio Anda?');"
                                    style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        style="background:#fef2f2; color:#dc2626; border:1px solid #fca5a5; font-weight:700; padding:0.4rem 0.75rem; border-radius:8px; font-size:0.75rem; cursor:pointer; display:inline-flex; align-items:center; gap:0.3rem; height:32px;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        </svg>
                                        Hapus Semua
                                    </button>
                                </form>
                            @endif
                            <button type="button" onclick="openScanMenuModal()"
                                style="background:linear-gradient(135deg, #0f172a, #1e293b); color:#fff; border:none; font-weight:700; display:inline-flex; align-items:center; gap:0.35rem; padding:0.4rem 0.85rem; border-radius:8px; font-size:0.78rem; cursor:pointer; height:32px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-4a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                Scan Menu AI
                            </button>
                            <button type="button" onclick="openAddUmkmWithCheck()"
                                style="background:linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color:#fff; border:none; font-weight:700; display:inline-flex; align-items:center; gap:0.35rem; padding:0.4rem 1.1rem; border-radius:999px; font-size:0.78rem; cursor:pointer; height:34px; box-shadow:0 4px 14px rgba(30, 179, 73, 0.32); transition:all 0.2s ease;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                Tambah
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="umkmProductList">
                        @php
                            $umkmBlocks = $blocks->filter(function ($b) use ($myProducts) {
                                if ($b->type === 'custom_product')
                                    return true;
                                $cat = strtolower(trim($b->data_json['category'] ?? ''));
                                if (in_array($cat, ['makanan', 'barang', 'jasa', 'lainnya', 'kuliner', 'fisik', 'umkm']))
                                    return true;
                                if (!empty($b->data_json['weight']) || !empty($b->data_json['sku']))
                                    return true;
                                $pid = $b->data_json['product_id'] ?? null;
                                if ($pid) {
                                    $p = $myProducts->firstWhere('id', $pid);
                                    if ($p) {
                                        $pType = strtolower($p->product_type ?? $p->type ?? '');
                                        if (in_array($pType, ['physical', 'makanan', 'service', 'product', 'umkm', 'barang', 'jasa', 'food']))
                                            return true;
                                    }
                                }
                                $title = strtolower($b->title ?? '');
                                return (bool) preg_match('/(es|nasi|teh|kopi|jus|sirup|air|soto|bakso|mie|ayam|bebek|daging|ikan|kerupuk|lumpia|kasur|samsung|promo|sepatu|baju|celana)/i', $title);
                            })->sortByDesc('id');
                        @endphp
                        @forelse($umkmBlocks as $block)
                                            @php
                                                $imgs = $block->data_json['images'] ?? [];
                                                $firstImg = !empty($imgs[0]) ? $imgs[0] : ($block->data_json['image'] ?? null);
                                            @endphp
                                            <div class="aff-card" data-id="{{ $block->id }}"
                                                style="display:flex; align-items:center; gap:0; padding:0; border-radius:14px; overflow:hidden; border:1px solid #e8f0e8; background:#fff; margin-bottom:0.6rem; box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                                                {{-- Thumbnail foto produk --}}
                                                @if(!empty($firstImg))
                                                    <div style="width:72px; height:72px; flex-shrink:0; overflow:hidden; background:#f1f5f9;">
                                                        <img src="{{ Str::startsWith($firstImg, 'http') ? $firstImg : asset('storage/' . $firstImg) }}"
                                                            style="width:72px; height:72px; object-fit:cover; display:block;"
                                                            onerror="this.parentElement.innerHTML='<div style=&quot;width:72px;height:72px;display:flex;align-items:center;justify-content:center;background:#f1f5f9;&quot;><svg width=\'28\' height=\'28\' fill=\'none\' stroke=\'#cbd5e1\' stroke-width=\'1.5\' viewBox=\'0 0 24 24\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><polyline points=\'21 15 16 10 5 21\'/></svg></div>'">
                                                    </div>
                                                @else
                                                    <div
                                                        style="width:72px; height:72px; flex-shrink:0; display:flex; align-items:center; justify-content:center; background:#f8fafc; border-right:1px solid #e8f0e8;">
                                                        <svg width="26" height="26" fill="none" stroke="#cbd5e1" stroke-width="1.5"
                                                            viewBox="0 0 24 24">
                                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                                            <polyline points="21 15 16 10 5 21" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="aff-info" style="flex:1; min-width:0; padding:0.55rem 0.75rem;">
                                                    <div class="aff-title"
                                                        style="font-size:0.83rem; font-weight:700; color:#0b120c; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:0.2rem;">
                                                        {{ $block->title }}
                                                    </div>
                                                    <div class="aff-sub" style="font-size:0.72rem; color:#64748b;">
                                                        @if(!empty($block->data_json['original_price']) && $block->data_json['original_price'] > ($block->data_json['price'] ?? 0))
                                                            <span style="text-decoration:line-through; color:#94a3b8; margin-right:0.3rem;">Rp
                                                                {{ number_format($block->data_json['original_price'], 0, ',', '.') }}</span>
                                                        @endif
                                                        <strong style="color:#1eb349;">Rp
                                                            {{ number_format($block->data_json['price'] ?? 0, 0, ',', '.') }}</strong>
                                                        &middot;
                                                        {{ ($block->data_json['payment_method'] ?? 'wa') === 'wa' ? 'Beli via WA' : 'Beli via Web' }}
                                                        @if(!empty($block->data_json['category']))
                                                            &middot; <span
                                                                style="background:#f0fdf4;color:#1eb349;padding:1px 6px;border-radius:4px;font-size:0.68rem;font-weight:600;">{{ $block->data_json['category'] }}</span>
                                                        @endif
                                                        @if(isset($block->data_json['stock']) && $block->data_json['stock'] !== null)
                                                            @if($block->data_json['stock'] == 0)
                                                                &middot; <span style="color:#ef4444;font-weight:700;">Habis</span>
                                                            @else
                                                                &middot; <span style="color:#64748b;">Stok: {{ $block->data_json['stock'] }}</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <div
                                                    style="padding:0.5rem 0.6rem; display:flex; align-items:center; gap:0.35rem; flex-shrink:0;">
                                                    @if(!empty($profile->store_slug))
                                                        <a href="{{ route('bio.product.show', [$profile->store_slug, $block->data_json['slug'] ?? $block->id]) }}"
                                                            target="_blank" class="btn-icon-sm" style="background:#f0fdf4; color:#1eb349;"
                                                            title="Lihat Halaman Produk">
                                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                                viewBox="0 0 24 24">
                                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                                                <polyline points="15 3 21 3 21 9" />
                                                                <line x1="10" y1="14" x2="21" y2="3" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    <button type="button" onclick="editUmkmProduct({{ json_encode([
                                'id' => $block->id,
                                'title' => $block->title,
                                'price' => $block->data_json['price'] ?? 0,
                                'original_price' => $block->data_json['original_price'] ?? '',
                                'category' => $block->data_json['category'] ?? 'Makanan',
                                'stock' => $block->data_json['stock'] ?? '',
                                'payment_method' => $block->data_json['payment_method'] ?? 'wa',
                                'description' => $block->data_json['description'] ?? '',
                                'wa_text' => $block->data_json['wa_text'] ?? '',
                                'url' => $block->url ?? '',
                                'images' => $block->data_json['images'] ?? []
                            ]) }})" class="btn-icon-sm" style="background:#eff6ff; color:#2563eb;" title="Edit Produk">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                            viewBox="0 0 24 24">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                        </svg>
                                                    </button>
                                                    <form action="{{ route('creator.bio.blocks.destroy', $block) }}" method="POST"
                                                        class="form-delete-block">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn-icon-sm btn-delete-block"
                                                            style="background:#fef2f2; color:#ef4444;" title="Hapus Produk">
                                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                                viewBox="0 0 24 24">
                                                                <polyline points="3 6 5 6 21 6" />
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                        @empty
                            <p style="color:#94a3b8; font-size:0.85rem; text-align:center; padding:1.5rem 0;">Belum ada produk
                                fisik/UMKM. Klik "+ Tambah Produk" untuk menambahkan.</p>
                        @endforelse
                    </div>
                </div>

                {{-- 5. Produk Buyle Saya --}}
                <div class="prof-card">
                    <div class="prof-card-head">
                        <span style="display:flex; align-items:center; gap:0.5rem;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path
                                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                <line x1="12" y1="22.08" x2="12" y2="12" />
                            </svg>
                            Tampilkan Produk Buyle Saya
                        </span>
                    </div>
                    <div class="card-body">
                        @forelse($myProducts as $product)
                            @php $alreadyAdded = $blocks->contains(fn($b) => ($b->data_json['product_id'] ?? null) == $product->id); @endphp
                            <div
                                style="display:flex; align-items:center; gap:0.75rem; padding:0.65rem 0; border-bottom:1px solid #f3f7f3;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        style="width:48px; height:48px; border-radius:8px; object-fit:cover; flex-shrink:0;">
                                @endif
                                <div style="flex:1; min-width:0;">
                                    <div
                                        style="font-weight:700; font-size:0.82rem; color:#0b120c; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $product->name }}
                                    </div>
                                    <div style="font-size:0.72rem; color:#64748b;">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </div>
                                @if($alreadyAdded)
                                    <span
                                        style="font-size:0.72rem; font-weight:700; color:#1eb349; background:#f0fdf4; padding:0.25rem 0.6rem; border-radius:6px;">Ditampilkan</span>
                                @else
                                    <form action="{{ route('creator.bio.blocks.store') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="type" value="buyle_product">
                                        <input type="hidden" name="title" value="{{ $product->name }}">
                                        <input type="hidden" name="url" value="{{ route('products.show', $product->slug) }}">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn-submit-sm" style="font-size:0.75rem; height:34px;">+
                                            Tampilkan</button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <p style="color:#94a3b8; font-size:0.85rem; text-align:center; padding:1.5rem 0;">Belum ada produk
                                milik Anda sendiri. <a href="{{ route('creator.products.create') }}"
                                    style="color:#1eb349;">Tambah Produk →</a></p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>{{-- /bio-content --}}
    </div>{{-- /bio-layout --}}

    {{-- ── Modal: Tambah Block Biasa ── --}}
    <div class="modal-overlay" id="addBlockModal" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="modal-box">
            <h3
                style="font-size:1.1rem; font-weight:800; margin:0 0 1.25rem; color:#0b120c; font-family:'Montserrat',sans-serif;">
                Tambah Block Baru</h3>
            <form action="{{ route('creator.bio.blocks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tipe Block</label>
                    <select name="type" class="form-input" id="blockType" onchange="handleTypeChange(this.value)">
                        <option value="link">Custom Link / Button</option>
                        <option value="pdf">File / Dokumen PDF</option>
                        <option value="tiktok">TikTok Video</option>
                        <option value="reels">Instagram Reels</option>
                        <option value="image">Gambar / Banner (Poster)</option>
                    </select>
                </div>

                {{-- Judul --}}
                <div class="form-group" id="addBlock-title-group">
                    <label class="form-label" id="addBlock-title-label">Judul / Label Tombol</label>
                    <input type="text" name="title" id="addBlock-title-input" class="form-input"
                        placeholder="Contoh: Download Portfolio" maxlength="150">
                </div>

                {{-- URL --}}
                <div class="form-group" id="addBlock-url-group">
                    <label class="form-label" id="addBlock-url-label">URL / Link</label>
                    <input type="url" name="url" id="addBlock-url-input" class="form-input" placeholder="https://...">
                </div>

                {{-- Ikon (hanya untuk link/pdf) --}}
                <div class="form-group" id="addBlock-icon-group">
                    <label class="form-label">Ikon (Pilih dari Galeri ATAU Upload)</label>
                    <div style="display:flex; gap:0.5rem; align-items:center;">
                        <button type="button" class="btn-primary"
                            style="background:#f8fafc; color:#1e293b; border:1.5px solid #cbd5e1; height:44px; padding:0 1rem; border-radius:10px; font-weight:600; display:flex; gap:0.5rem; align-items:center;"
                            onclick="openIconPicker('addBlockIconClass', 'addBlockIconPreview')">
                            <i class="ph ph-squares-four" style="font-size:1.2rem;"></i> Pilih Ikon
                        </button>
                        <div id="addBlockIconPreview"
                            style="display:none; font-size:24px; color:#1eb349; width:44px; height:44px; align-items:center; justify-content:center; border:1.5px solid #1eb349; border-radius:10px; background:#f0fdf4;">
                        </div>
                        <div style="flex:1;">
                            <input type="file" name="block_image" accept="image/*" class="form-input"
                                id="addBlock-icon-upload" style="height:44px; padding:0.5rem; width:100%;">
                        </div>
                    </div>
                    <input type="hidden" name="icon_class" id="addBlockIconClass">
                </div>

                {{-- Upload gambar banner (hanya untuk image) --}}
                <div class="form-group" id="addBlock-banner-group" style="display:none;">
                    <label class="form-label">Upload Gambar Banner / Poster <span style="color:#ef4444;">*</span></label>
                    <input type="file" name="block_image" accept="image/*" class="form-input" id="addBlock-banner-upload"
                        style="height:44px; padding:0.5rem; width:100%;" required>
                    <div style="font-size:0.75rem; color:#64748b; margin-top:4px;">Upload gambar poster/banner yang akan
                        ditampilkan. URL klik opsional.</div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem;">
                    <button type="button" onclick="document.getElementById('addBlockModal').classList.remove('open')"
                        style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Batal</button>
                    <button type="submit" class="btn-submit-sm">Tambah Block</button>
                </div>
            </form>
        </div>
    </div>


    {{-- ── Modal: Tambah Affiliate / Shopee ── --}}
    <div class="modal-overlay" id="addAffModal" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="modal-box">
            <h3
                style="font-size:1.1rem; font-weight:800; margin:0 0 0.5rem; color:#0b120c; font-family:'Montserrat',sans-serif;">
                Tambah Produk Affiliate</h3>
            <p style="font-size:0.78rem; color:#64748b; margin:0 0 1.25rem;">Tempel link Shopee/Tokopedia — sistem akan
                ambil gambar otomatis. Atau upload manual.</p>

            <div id="scrapePreview"
                style="display:none; background:#f0fdf4; border-radius:12px; padding:1rem; margin-bottom:1rem; display:none; align-items:center; gap:0.75rem;">
                <img id="scrapeImg" style="width:60px; height:60px; border-radius:8px; object-fit:cover;" src=""
                    onerror="this.src='https://placehold.co/60x60/fff/cbd5e1?text=Img'">
                <div>
                    <div id="scrapeTitle"
                        style="font-weight:700; font-size:0.82rem; color:#0b120c; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                    </div>
                    <div style="font-size:0.7rem; color:#1eb349;">✓ Info berhasil ditemukan</div>
                </div>
            </div>

            <form action="{{ route('creator.bio.blocks.store') }}" method="POST" enctype="multipart/form-data" id="affForm">
                @csrf
                <input type="hidden" name="type" value="shopee">
                <input type="hidden" name="scraped_image" id="affScrapedImage">
                <div class="form-group">
                    <label class="form-label">Link Produk (Shopee / Tokopedia / dll)</label>
                    <div style="display:flex; gap:0.5rem; align-items:center;">
                        <input type="text" name="url" id="affUrl" class="form-input"
                            placeholder="https://shopee.co.id/... atau https://tokopedia.com/..." style="flex:1;">
                        <button type="button" id="btnScrape"
                            onclick="scrapeUrl(document.getElementById('affUrl').value, true)"
                            style="height:44px; padding:0 1.1rem; border-radius:999px; background:linear-gradient(135deg,#1eb349,#16a34a); border:none; color:#fff; font-weight:700; font-size:0.82rem; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem; white-space:nowrap; flex-shrink:0; box-shadow:0 2px 8px rgba(30,179,73,0.25);">
                            <svg id="scrapeSpinner" width="14" height="14" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24" style="display:none;">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                            </svg>
                            <svg id="scrapeIcon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            Cari
                        </button>
                    </div>
                    <span class="form-hint" id="scrapeStatus">Klik "Scrape" untuk mengambil gambar & judul otomatis dari
                        link</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul Produk</label>
                    <input type="text" name="title" id="affTitle" class="form-input" placeholder="Nama produk..."
                        maxlength="150" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Upload Gambar Manual (jika scrape gagal)</label>
                    <input type="file" name="block_image" accept="image/*" class="form-input"
                        style="height:auto; padding:0.5rem;">
                </div>
                <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem;">
                    <button type="button" onclick="document.getElementById('addAffModal').classList.remove('open')"
                        style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Batal</button>
                    <button type="submit" class="btn-submit-sm">Tambah Produk</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal: Edit Block ── --}}
    <div class="modal-overlay" id="editBlockModal" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="modal-box">
            <h3
                style="font-size:1.1rem; font-weight:800; margin:0 0 1.25rem; color:#0b120c; font-family:'Montserrat',sans-serif;">
                Edit Block</h3>
            <form action="" method="POST" enctype="multipart/form-data" id="editBlockForm">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Judul / Label</label>
                    <input type="text" name="title" id="editBlockTitle" class="form-input" maxlength="150" required>
                </div>
                <div class="form-group">
                    <label class="form-label">URL / Link</label>
                    <input type="url" name="url" id="editBlockUrl" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Ganti Ikon (Pilih dari Galeri ATAU Upload)</label>
                    <div style="display:flex; gap:0.5rem; align-items:center;">
                        <button type="button" class="btn-primary"
                            style="background:#f8fafc; color:#1e293b; border:1.5px solid #cbd5e1; height:44px; padding:0 1rem; border-radius:10px; font-weight:600; display:flex; gap:0.5rem; align-items:center;"
                            onclick="openIconPicker('editBlockIconClass', 'editBlockIconPreview')">
                            <i class="ph ph-squares-four" style="font-size:1.2rem;"></i> Pilih Ikon
                        </button>
                        <div id="editBlockIconPreview"
                            style="display:none; font-size:24px; color:#1eb349; width:44px; height:44px; align-items:center; justify-content:center; border:1.5px solid #1eb349; border-radius:10px; background:#f0fdf4;">
                        </div>
                        <div style="flex:1;">
                            <input type="file" name="block_image" accept="image/*" class="form-input"
                                style="height:44px; padding:0.5rem; width:100%;">
                        </div>
                    </div>
                    <input type="hidden" name="icon_class" id="editBlockIconClass">
                </div>
                <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem;">
                    <button type="button" onclick="document.getElementById('editBlockModal').classList.remove('open')"
                        style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Batal</button>
                    <button type="submit" class="btn-submit-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal: Icon Picker ── --}}
    <div class="modal-overlay" id="iconPickerModal" onclick="if(event.target===this)this.classList.remove('open')"
        style="z-index: 1050;">
        <div class="modal-box">
            <h3
                style="font-size:1.1rem; font-weight:800; margin:0 0 1rem; color:#0b120c; font-family:'Montserrat',sans-serif;">
                Pilih Ikon</h3>
            <p style="font-size:0.75rem; color:#64748b; margin-bottom:1rem;">Pilih salah satu ikon di bawah ini untuk
                ditampilkan di tombol Anda.</p>

            <div class="icon-grid" id="iconGrid">
                <!-- Icons injected via JS -->
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.5rem;">
                <button type="button" onclick="document.getElementById('iconPickerModal').classList.remove('open')"
                    style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Tutup</button>
            </div>
        </div>
    </div>

    {{-- ── Modal Interactive: Error Upload Foto Oversized / Terlalu Besar ── --}}
    <div class="modal-overlay" id="imgOversizedModal" style="z-index:99999;"
        onclick="if(event.target===this)this.classList.remove('open')">
        <div class="modal-box"
            style="max-width:420px; border-radius:20px; padding:1.75rem; text-align:center; box-shadow: 0 20px 50px rgba(0,0,0,0.3);">
            <div
                style="width:60px; height:60px; border-radius:50%; background:#FEF2F2; color:#EF4444; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                    <line x1="12" y1="9" x2="12" y2="13" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
            </div>
            <h3 style="font-size:1.15rem; font-weight:800; color:#0F172A; margin:0 0 0.5rem; font-family:'Montserrat',sans-serif;"
                id="imgOversizedTitle">
                Ukuran Foto Melebihi Batas
            </h3>
            <p style="font-size:0.83rem; color:#64748B; margin:0 0 1rem; line-height:1.5;" id="imgOversizedDesc">
                Maksimal ukuran foto adalah <strong>1 MB per file</strong>. Silakan kurangi ukuran foto Anda terlebih
                dahulu.
            </p>
            <div id="imgOversizedFileList"
                style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:0.75rem 1rem; font-size:0.8rem; color:#334155; text-align:left; margin-bottom:1.25rem; max-height:120px; overflow-y:auto; font-weight:600;">
            </div>
            <div style="display:flex; flex-direction:column; gap:0.6rem;">
                <a href="https://tinypng.com" target="_blank" rel="noopener" class="btn-submit-sm"
                    style="display:inline-flex; align-items:center; justify-content:center; gap:0.5rem; width:100%; height:44px; font-size:0.88rem; font-weight:700; text-decoration:none; background:#1eb349; color:#fff; border-radius:999px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                    Kompres di TinyPNG.com
                </a>
                <button type="button" onclick="document.getElementById('imgOversizedModal').classList.remove('open')"
                    style="height:40px; width:100%; border-radius:999px; border:1.5px solid #E2E8F0; background:#fff; color:#64748B; font-weight:700; font-size:0.85rem; cursor:pointer;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal: Peringatan Lokasi Belum Lengkap ── --}}
    <div class="modal-overlay" id="locationWarningModal" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="modal-box" style="max-width:420px; text-align:center; padding:2rem 1.75rem;">
            {{-- Icon --}}
            <div
                style="width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg,#fef3c7,#fde68a); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                <svg width="28" height="28" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                    <circle cx="12" cy="10" r="3" />
                </svg>
            </div>
            <h3
                style="font-size:1.05rem; font-weight:800; color:#0f172a; margin:0 0 0.5rem; font-family:'Montserrat',sans-serif;">
                Lokasi Belum Dilengkapi
            </h3>
            <p style="font-size:0.82rem; color:#64748b; line-height:1.6; margin:0 0 1.5rem;">
                Untuk menambahkan <strong>Produk Fisik</strong>, kamu perlu mengisi
                <strong>kota/kabupaten</strong> asal pengiriman di profil toko terlebih dahulu.
                Ini digunakan untuk menghitung ongkos kirim ke pembeli secara otomatis.
            </p>
            <div style="display:flex; gap:0.75rem; justify-content:center;">
                <button type="button" onclick="document.getElementById('locationWarningModal').classList.remove('open')"
                    style="padding:0.55rem 1.25rem; border-radius:999px; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; font-weight:700; font-size:0.82rem; cursor:pointer;">
                    Nanti Saja
                </button>
                <a href="{{ route('creator.profile.edit') }}?tab=lokasi"
                    style="padding:0.55rem 1.4rem; border-radius:999px; background:linear-gradient(135deg,#1eb349,#a5cf37); color:#fff; font-weight:700; font-size:0.82rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; box-shadow:0 4px 14px rgba(30,179,73,0.28);">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    Lengkapi Lokasi
                </a>
            </div>
        </div>
    </div>

    {{-- ── Modal: Tambah Produk UMKM / Fisik ── --}}
    <div class="modal-overlay" id="addUmkmModal" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="modal-box" style="max-width:680px; width:94%; max-height:90vh; overflow-y:auto; border-radius:16px; padding:1.5rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; align-items:center; gap:0.6rem;">
                    <div style="width:36px; height:36px; border-radius:10px; background:#ecfdf5; display:flex; align-items:center; justify-content:center; color:#1eb349; flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:1.15rem; font-weight:800; margin:0; color:#0f172a; font-family:'Montserrat',sans-serif;">Tambah Produk Fisik / UMKM</h3>
                        <p style="font-size:0.75rem; color:#64748b; margin:0.1rem 0 0;">Lengkapi data produk fisik dan informasi pengiriman logistik.</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddUmkmModal()" style="background:#f1f5f9; border:none; width:32px; height:32px; border-radius:50%; font-size:1.1rem; cursor:pointer; color:#64748b; display:flex; align-items:center; justify-content:center;">&times;</button>
            </div>

            {{-- ── Scrape Shopee / Tokopedia ── --}}
            <div id="scrapePanel"
                style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:1rem; margin-bottom:1.25rem;">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.6rem;">
                    <svg width="16" height="16" fill="none" stroke="#0f172a" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <span style="font-size:0.82rem; font-weight:700; color:#0f172a;">Auto Import dari Shopee / Tokopedia</span>
                </div>
                <p style="font-size:0.75rem; color:#64748b; margin:0 0 0.75rem; line-height:1.5;">Tempel link produk Shopee atau Tokopedia — sistem akan otomatis mengambil nama, harga, deskripsi, dan foto produk.</p>
                <div style="display:flex; gap:0.5rem; align-items:flex-start;">
                    <div style="flex:1;">
                        <input type="text" id="scrapeUrlInput"
                            placeholder="https://shopee.co.id/... atau https://tokopedia.com/..."
                            style="width:100%; height:40px; padding:0 0.85rem; border-radius:8px; border:1.5px solid #cbd5e1; font-size:0.8rem; background:#fff; color:#1e293b; outline:none; box-sizing:border-box;">
                    </div>
                    <button type="button" onclick="doScrapeProduct()" id="scrapeBtn"
                        style="height:40px; padding:0 1.25rem; border-radius:999px; background:linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color:#fff; border:none; font-weight:700; font-size:0.82rem; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem; white-space:nowrap; flex-shrink:0; box-shadow:0 4px 14px rgba(30,179,73,0.32); transition:all 0.2s ease;">
                        <svg id="scrapeBtnIcon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <span id="scrapeBtnText">Cari</span>
                    </button>
                </div>
                <div id="scrapeStatus" style="margin-top:0.5rem; font-size:0.75rem; display:none;"></div>
                <div id="scrapePreview"
                    style="display:none; margin-top:0.75rem; background:#fff; border:1.5px solid #d1fae5; border-radius:10px; padding:0.75rem; display:flex; gap:0.75rem; align-items:flex-start;">
                    <img id="scrapePreviewImg" src="" alt=""
                        style="width:60px; height:60px; object-fit:cover; border-radius:8px; flex-shrink:0; border:1px solid #e2e8f0; display:none;">
                    <div style="flex:1; min-width:0;">
                        <div id="scrapePreviewTitle"
                            style="font-size:0.82rem; font-weight:700; color:#0f172a; margin-bottom:0.2rem;"></div>
                        <div id="scrapePreviewPrice" style="font-size:0.78rem; color:#1eb349; font-weight:700;"></div>
                    </div>
                </div>
            </div>

            <form action="{{ route('creator.bio.blocks.store') }}" method="POST" enctype="multipart/form-data"
                id="addUmkmForm">
                @csrf
                <input type="hidden" id="scrapeImageUrl" name="scrape_image_url" value="">
                <input type="hidden" name="type" value="custom_product">
                <div class="form-group">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="title" id="umkmTitle" class="form-input"
                        placeholder="Contoh: Tas Kulit Handmade" maxlength="150" required>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Harga Jual (Rp / IDR) *</label>
                        <input type="text" name="price" id="umkmPrice" class="form-input" placeholder="150.000"
                            oninput="formatRupiahInput(this)" required>
                        <span class="form-hint" style="color:#1eb349; font-size:0.7rem;">Otomatis dengan titik (contoh: 3.355.555)</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Sebelum Diskon (Rp) <span
                                style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                        <input type="text" name="original_price" id="umkmOriginalPrice" class="form-input"
                            placeholder="Contoh: 200.000 (Harga awal sebelum diskon)" oninput="formatRupiahInput(this)">
                        <span class="form-hint" style="color:#64748b; font-size:0.68rem;">Harga normal awal sebelum dipotong diskon (harus lebih besar dari Harga Jual).</span>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Kategori Produk *</label>
                        <select name="category" class="form-input" required style="padding:0.6rem;">
                            <option value="Makanan">Makanan / Minuman / Kuliner</option>
                            <option value="Barang">Barang / Produk Fisik</option>
                            <option value="Jasa">Jasa / Layanan / Service</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Stok <span
                                style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                        <input type="number" name="stock" class="form-input" min="0"
                            placeholder="Kosongkan jika Unlimited (0 = Habis)">
                        <span class="form-hint" style="color:#64748b; font-size:0.68rem;">Kosongkan jika unlimited (stok tidak terbatas). Isi 0 jika habis.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Produk</label>
                    <textarea name="description" id="umkmDescription" class="form-input"
                        style="height:80px; padding:0.75rem;" placeholder="Ceritakan detail produk Anda..."></textarea>
                </div>

                {{-- ── Informasi Pengiriman & Dimensi Ongkir ── --}}
                <div
                    style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:1rem; margin-bottom:1.25rem;">
                    <div
                        style="font-size:0.82rem; font-weight:700; color:#0f172a; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.5rem;">
                        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        Informasi Pengiriman & Berat Ongkir Logistik
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label" style="font-size:0.75rem;">SKU Produk <span
                                    style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                            <input type="text" name="sku" class="form-input" placeholder="Contoh: SKU-12345"
                                style="height:38px; font-size:0.8rem;">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label" style="font-size:0.75rem;">Berat Fizik (Gram) *</label>
                            <input type="number" name="weight" min="1" class="form-input" placeholder="Contoh: 1000 (1 kg)"
                                value="1000" style="height:38px; font-size:0.8rem;" required>
                            <span class="form-hint" style="font-size:0.68rem; color:#64748b;">1.000 gr = 1 kg</span>
                        </div>
                    </div>
                    <div style="font-size:0.75rem; color:#475569; margin-bottom:0.4rem; font-weight:700;">Dimensi Paket (Panjang x Lebar x Tinggi cm) — Ongkir Volumetrik:</div>
                    <div style="display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:0.4rem; width:100%; box-sizing:border-box;">
                        <div class="form-group" style="margin:0; min-width:0;">
                            <label class="form-label" style="font-size:0.7rem; font-weight:700; white-space:nowrap; margin-bottom:0.2rem;">P (cm)</label>
                            <input type="number" name="length" min="0" step="0.1" class="form-input umkm-dim-p"
                                placeholder="P" style="height:34px; font-size:0.78rem; padding:0 0.35rem; width:100%; box-sizing:border-box; text-align:center;" oninput="calcUmkmVolume(this)">
                        </div>
                        <div class="form-group" style="margin:0; min-width:0;">
                            <label class="form-label" style="font-size:0.7rem; font-weight:700; white-space:nowrap; margin-bottom:0.2rem;">L (cm)</label>
                            <input type="number" name="width" min="0" step="0.1" class="form-input umkm-dim-l"
                                placeholder="L" style="height:34px; font-size:0.78rem; padding:0 0.35rem; width:100%; box-sizing:border-box; text-align:center;" oninput="calcUmkmVolume(this)">
                        </div>
                        <div class="form-group" style="margin:0; min-width:0;">
                            <label class="form-label" style="font-size:0.7rem; font-weight:700; white-space:nowrap; margin-bottom:0.2rem;">T (cm)</label>
                            <input type="number" name="height" min="0" step="0.1" class="form-input umkm-dim-t"
                                placeholder="T" style="height:34px; font-size:0.78rem; padding:0 0.35rem; width:100%; box-sizing:border-box; text-align:center;" oninput="calcUmkmVolume(this)">
                        </div>
                        <div class="form-group" style="margin:0; min-width:0;">
                            <label class="form-label" style="font-size:0.7rem; font-weight:700; color:#1eb349; white-space:nowrap; margin-bottom:0.2rem;">Vol (cm³)</label>
                            <input type="number" name="volume" min="0" class="form-input umkm-dim-v" placeholder="Vol"
                                style="height:34px; font-size:0.78rem; padding:0 0.35rem; width:100%; box-sizing:border-box; text-align:center; background:#f1f5f9; font-weight:700; color:#0f172a;" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Produk</label>
                    {{-- Preview foto dari scrape --}}
                    <div id="scrapeImgPreviewBox"
                        style="display:none; margin-bottom:0.6rem; align-items:center; gap:0.75rem; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:10px; padding:0.6rem 0.75rem;">
                        <img id="scrapeImgThumb" src="" alt=""
                            style="width:48px; height:48px; object-fit:cover; border-radius:8px; border:1px solid #d1fae5;">
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:0.75rem; font-weight:700; color:#1eb349; margin-bottom:0.1rem;">Foto dari Shopee/Tokopedia</div>
                            <div id="scrapeImgUrl"
                                style="font-size:0.7rem; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:280px;">
                            </div>
                        </div>
                        <button type="button" onclick="clearScrapeImage()"
                            style="flex-shrink:0; border:none; background:none; color:#ef4444; cursor:pointer; font-size:1rem; line-height:1;"
                            title="Hapus">&times;</button>
                    </div>
                    <input type="file" name="custom_images[]" accept="image/*" multiple class="form-input"
                        style="height:auto; padding:0.5rem;" id="addUmkmImages"
                        onchange="validateUmkmImages(this, 'umkmImagePreview')">
                    <div id="umkmImagePreview" style="display:flex; gap:0.6rem; flex-wrap:wrap; margin-top:0.5rem;"></div>
                    <span class="form-hint" style="color:#64748b; font-size:0.72rem;">
                        Format: JPG, PNG, WEBP &middot; <strong>Maks 1 MB per foto.</strong>
                        Jika foto terlalu besar, kompres dulu di
                        <a href="https://tinypng.com" target="_blank" rel="noopener"
                            style="color:#1eb349; font-weight:700; text-decoration:underline;">TinyPNG.com</a>
                    </span>
                </div>
                <div class="form-group">
                    <label class="form-label">Metode Pembelian</label>
                    <div style="display:flex; gap:1rem; margin-top:0.35rem;">
                        <label
                            style="display:flex; align-items:center; gap:0.4rem; cursor:pointer; font-size:0.85rem; font-weight:600;">
                            <input type="radio" name="payment_method" value="wa" checked> Beli via WhatsApp
                        </label>
                        <label
                            style="display:flex; align-items:center; gap:0.4rem; cursor:pointer; font-size:0.85rem; font-weight:600;">
                            <input type="radio" name="payment_method" value="web"> Beli via Web (Buyle)
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">URL / Link (Opsional - isi jika ada halaman produk eksternal)</label>
                    <input type="text" name="url" class="form-input"
                        placeholder="https://shopee.co.id/... (kosongkan untuk pakai halaman otomatis)">
                </div>
                <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.25rem;">
                    <button type="button" onclick="closeAddUmkmModal()"
                        style="height:42px; padding:0 1.4rem; border-radius:999px; border:1.5px solid #e2e8f0; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Batal</button>
                    <button type="submit" class="btn-submit-sm" style="height:42px; padding:0 1.75rem; border-radius:999px;">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal: Edit Produk UMKM / Fisik ── --}}
    <div class="modal-overlay" id="editUmkmModal" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="modal-box" style="max-width:680px; width:94%; max-height:90vh; overflow-y:auto; border-radius:16px; padding:1.5rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; align-items:center; gap:0.6rem;">
                    <div style="width:36px; height:36px; border-radius:10px; background:#ecfdf5; display:flex; align-items:center; justify-content:center; color:#1eb349; flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:1.15rem; font-weight:800; margin:0; color:#0f172a; font-family:'Montserrat',sans-serif;">Edit Produk Fisik / UMKM</h3>
                        <p style="font-size:0.75rem; color:#64748b; margin:0.1rem 0 0;">Perbarui data produk fisik dan informasi pengiriman logistik.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditUmkmModal()" style="background:#f1f5f9; border:none; width:32px; height:32px; border-radius:50%; font-size:1.1rem; cursor:pointer; color:#64748b; display:flex; align-items:center; justify-content:center;">&times;</button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" id="editUmkmForm">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="title" id="edit_title" class="form-input" maxlength="150" required>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Harga Jual (Rp / IDR) *</label>
                        <input type="text" name="price" id="edit_price" class="form-input" oninput="formatRupiahInput(this)"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Sebelum Diskon (Rp) <span
                                style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                        <input type="text" name="original_price" id="edit_original_price" class="form-input"
                            placeholder="Contoh: 200.000 (Harga awal sebelum diskon)" oninput="formatRupiahInput(this)">
                        <span class="form-hint" style="color:#64748b; font-size:0.68rem;">Harga normal awal sebelum dipotong diskon.</span>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                    <div class="form-group">
                        <label class="form-label">Kategori Produk *</label>
                        <select name="category" id="edit_category" class="form-input" required style="padding:0.6rem;">
                            <option value="Makanan">Makanan / Minuman / Kuliner</option>
                            <option value="Barang">Barang / Produk Fisik</option>
                            <option value="Jasa">Jasa / Layanan / Service</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Stok <span
                                style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                        <input type="number" name="stock" id="edit_stock" class="form-input"
                            placeholder="Kosongkan jika Unlimited (0 = Habis)">
                        <span class="form-hint" style="color:#64748b; font-size:0.68rem;">Kosongkan jika unlimited (stok
                            tidak terbatas). Isi 0 jika habis.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Produk</label>
                    <textarea name="description" id="edit_description" class="form-input"
                        style="height:80px; padding:0.75rem;"></textarea>
                </div>

                {{-- ── Informasi Pengiriman & Dimensi Ongkir ── --}}
                <div
                    style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:10px; padding:0.85rem; margin-bottom:1rem;">
                    <div
                        style="font-size:0.78rem; font-weight:700; color:#0f172a; margin-bottom:0.5rem; display:flex; align-items:center; gap:0.4rem;">
                        <svg width="15" height="15" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        Informasi Pengiriman & Berat Ongkir
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:0.6rem;">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label" style="font-size:0.72rem;">SKU Produk <span
                                    style="font-weight:400;color:#94a3b8;">(Opsional)</span></label>
                            <input type="text" name="sku" id="edit_sku" class="form-input" placeholder="Contoh: SKU-12345"
                                style="height:36px; font-size:0.78rem;">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label" style="font-size:0.72rem;">Berat Fizik (Gram) *</label>
                            <input type="number" name="weight" id="edit_weight" min="1" class="form-input"
                                placeholder="1000 (1 kg)" style="height:36px; font-size:0.78rem;" required>
                            <span class="form-hint" style="font-size:0.65rem; color:#64748b;">1.000 gr = 1 kg</span>
                        </div>
                    </div>
                    <div style="font-size:0.75rem; color:#475569; margin-bottom:0.4rem; font-weight:700;">Dimensi Paket (Panjang x Lebar x Tinggi cm) — Ongkir Volumetrik:</div>
                    <div style="display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:0.4rem; width:100%; box-sizing:border-box;">
                        <div class="form-group" style="margin:0; min-width:0;">
                            <label class="form-label" style="font-size:0.7rem; font-weight:700; white-space:nowrap; margin-bottom:0.2rem;">P (cm)</label>
                            <input type="number" name="length" id="edit_length" min="0" step="0.1"
                                class="form-input umkm-dim-p" placeholder="P" style="height:34px; font-size:0.78rem; padding:0 0.35rem; width:100%; box-sizing:border-box; text-align:center;"
                                oninput="calcUmkmVolume(this)">
                        </div>
                        <div class="form-group" style="margin:0; min-width:0;">
                            <label class="form-label" style="font-size:0.7rem; font-weight:700; white-space:nowrap; margin-bottom:0.2rem;">L (cm)</label>
                            <input type="number" name="width" id="edit_width" min="0" step="0.1"
                                class="form-input umkm-dim-l" placeholder="L" style="height:34px; font-size:0.78rem; padding:0 0.35rem; width:100%; box-sizing:border-box; text-align:center;"
                                oninput="calcUmkmVolume(this)">
                        </div>
                        <div class="form-group" style="margin:0; min-width:0;">
                            <label class="form-label" style="font-size:0.7rem; font-weight:700; white-space:nowrap; margin-bottom:0.2rem;">T (cm)</label>
                            <input type="number" name="height" id="edit_height" min="0" step="0.1"
                                class="form-input umkm-dim-t" placeholder="T" style="height:34px; font-size:0.78rem; padding:0 0.35rem; width:100%; box-sizing:border-box; text-align:center;"
                                oninput="calcUmkmVolume(this)">
                        </div>
                        <div class="form-group" style="margin:0; min-width:0;">
                            <label class="form-label" style="font-size:0.7rem; font-weight:700; color:#1eb349; white-space:nowrap; margin-bottom:0.2rem;">Vol (cm³)</label>
                            <input type="number" name="volume" id="edit_volume" min="0" class="form-input umkm-dim-v"
                                placeholder="Vol" style="height:34px; font-size:0.78rem; padding:0 0.35rem; width:100%; box-sizing:border-box; text-align:center; background:#f1f5f9; font-weight:700; color:#0f172a;" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Produk Tersimpan & Preview (Maks 3 foto)</label>
                    {{-- Container for saved images with Delete ✕ button --}}
                    <div id="editSavedImagesContainer"
                        style="display:flex; gap:0.6rem; flex-wrap:wrap; margin-bottom:0.5rem;"></div>
                    <div id="deleteExistingImagesInputs"></div>

                    <label
                        style="font-size:0.75rem; font-weight:600; color:#64748b; margin-top:0.5rem; display:block;">Tambah
                        / Ganti Foto Baru:</label>
                    <input type="file" name="custom_images[]" accept="image/*" multiple class="form-input"
                        style="height:auto; padding:0.5rem;" id="editUmkmImages"
                        onchange="validateUmkmImages(this, 'editNewImagePreview')">
                    <div id="editNewImagePreview" style="display:flex; gap:0.6rem; flex-wrap:wrap; margin-top:0.5rem;">
                    </div>
                    <span class="form-hint"
                        style="color:#64748b; font-size:0.72rem; display:flex; align-items:center; gap:4px; margin-top:4px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1.3.5 2.6 1.5 3.5.8.7 1.3 1.5 1.5 2.5" />
                            <path d="M9 18h6" />
                            <path d="M10 22h4" />
                        </svg>
                        <strong>Maks 1 MB per foto.</strong>&nbsp;Jika terlalu besar, kompres dulu di&nbsp;
                        <a href="https://tinypng.com" target="_blank" rel="noopener"
                            style="color:#1eb349; font-weight:700;">TinyPNG.com</a>
                        &middot; Klik ✕ pada foto tersimpan untuk menghapus permanen.
                    </span>
                </div>

                <div class="form-group">
                    <label class="form-label">Metode Pembelian</label>
                    <div style="display:flex; gap:0.75rem; margin-top:0.25rem;">
                        <label
                            style="display:flex; align-items:center; gap:0.4rem; cursor:pointer; font-size:0.85rem; font-weight:600;">
                            <input type="radio" name="payment_method" id="edit_pm_wa" value="wa"> Beli via WhatsApp
                        </label>
                        <label
                            style="display:flex; align-items:center; gap:0.4rem; cursor:pointer; font-size:0.85rem; font-weight:600;">
                            <input type="radio" name="payment_method" id="edit_pm_web" value="web"> Beli via Web (Buyle)
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">URL / Link (Opsional)</label>
                    <input type="text" name="url" id="edit_url" class="form-input">
                </div>
                <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem;">
                    <button type="button" onclick="document.getElementById('editUmkmModal').classList.remove('open')"
                        style="height:40px; padding:0 1.25rem; border-radius:999px; border:1.5px solid #e7f0e7; background:#fff; color:#64748b; font-weight:700; cursor:pointer;">Batal</button>
                    <button type="submit" class="btn-submit-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal: Custom Confirm / Delete ── --}}
    <div class="confirm-modal-overlay" id="confirmModal">
        <div class="confirm-modal-box">
            <div class="confirm-modal-icon" id="confirmIcon" style="background:#fef2f2;">
                <svg width="26" height="26" fill="none" stroke="#ef4444" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                    <path d="M10 11v6M14 11v6" />
                    <path d="M9 6V4h6v2" />
                </svg>
            </div>
            <div class="confirm-modal-title" id="confirmTitle">Hapus Block Ini?</div>
            <div class="confirm-modal-desc" id="confirmDesc">Block yang dihapus tidak bisa dikembalikan. Pastikan Anda sudah
                yakin.</div>
            <div class="confirm-modal-actions">
                <button class="confirm-btn-cancel" onclick="closeConfirmModal()">Batal</button>
                <button class="confirm-btn-danger" id="confirmOkBtn" onclick="doConfirmAction()">Ya, Hapus</button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(this.dataset.tab).classList.add('active');
                localStorage.setItem('bio_active_tab', this.dataset.tab);
            });
        });

        // Color & Background Sync for Custom Theme
        function toggleBgMode(mode) {
            let colorPanel = document.getElementById('bg_color_panel');
            let imagePanel = document.getElementById('bg_image_panel');
            let cardColor = document.getElementById('bg_mode_card_color');
            let cardImage = document.getElementById('bg_mode_card_image');

            if (mode === 'image') {
                if (colorPanel) colorPanel.style.display = 'none';
                if (imagePanel) imagePanel.style.display = 'block';
                if (cardColor) { cardColor.style.borderColor = '#e2e8f0'; cardColor.style.background = '#fff'; }
                if (cardImage) { cardImage.style.borderColor = '#1eb349'; cardImage.style.background = '#f0fdf4'; }

                let imgThumb = document.getElementById('bg_image_thumb');
                let cpBg = document.getElementById('cpBg');
                if (cpBg && imgThumb && imgThumb.src) {
                    cpBg.style.background = `url("${imgThumb.src}") center center / cover no-repeat`;
                }
            } else {
                if (colorPanel) colorPanel.style.display = 'block';
                if (imagePanel) imagePanel.style.display = 'none';
                if (cardColor) { cardColor.style.borderColor = '#1eb349'; cardColor.style.background = '#f0fdf4'; }
                if (cardImage) { cardImage.style.borderColor = '#e2e8f0'; cardImage.style.background = '#fff'; }

                let bg = document.getElementById('color_bg_picker')?.value || '#0b120c';
                let cpBg = document.getElementById('cpBg');
                if (cpBg) cpBg.style.background = bg;
            }
        }

        function previewUploadedBgImage(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let cpBg = document.getElementById('cpBg');
                    if (cpBg) {
                        cpBg.style.background = `url("${e.target.result}") center center / cover no-repeat`;
                    }
                    let imgThumb = document.getElementById('bg_image_thumb');
                    if (imgThumb) {
                        imgThumb.src = e.target.result;
                    }
                    let radioImg = document.querySelector('input[name="bg_type"][value="image"]');
                    if (radioImg) {
                        radioImg.checked = true;
                        toggleBgMode('image');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewBgDelete(chk) {
            let cpBg = document.getElementById('cpBg');
            if (chk.checked) {
                let bg = document.getElementById('color_bg_picker')?.value || '#0b120c';
                if (cpBg) cpBg.style.background = bg;
            } else {
                let imgThumb = document.getElementById('bg_image_thumb');
                if (cpBg && imgThumb && imgThumb.src) {
                    cpBg.style.background = `url("${imgThumb.src}") center center / cover no-repeat`;
                }
            }
        }

        function syncColor(id, value, fromText = false) {
            if (fromText) {
                let p = document.getElementById(id + '_picker');
                if (p) p.value = value;
            } else {
                let t = document.getElementById(id + '_text');
                if (t) t.value = value;
            }

            let bgType = document.querySelector('input[name="bg_type"]:checked')?.value || 'color';
            if (bgType === 'color') {
                let bg = document.getElementById('color_bg_picker')?.value || '#0b120c';
                let cpBg = document.getElementById('cpBg');
                if (cpBg) cpBg.style.background = bg;
            }

            let text = document.getElementById('color_text_picker')?.value || '#ffffff';
            let btn = document.getElementById('color_btn_picker')?.value || '#1eb349';
            let btnText = document.getElementById('color_btn_text_picker')?.value || '#ffffff';
            let accent = document.getElementById('color_accent_picker')?.value || '#1eb349';
            let card = document.getElementById('color_card_picker')?.value || '#1a231b';

            let cpCard = document.getElementById('cpCard');
            if (cpCard) { cpCard.style.background = card; cpCard.style.borderColor = accent; }
            let cpText = document.getElementById('cpText');
            if (cpText) cpText.style.color = text;
            let cpBtn = document.getElementById('cpBtn');
            if (cpBtn) { cpBtn.style.background = btn; cpBtn.style.color = btnText; }
        }

        function resetColors() {
            let defs = { 'color_bg': '#0b120c', 'color_text': '#ffffff', 'color_btn': '#1eb349', 'color_btn_text': '#ffffff', 'color_accent': '#1eb349', 'color_card': '#1a231b' };
            for (let k in defs) {
                let p = document.getElementById(k + '_picker');
                let t = document.getElementById(k + '_text');
                if (p) p.value = defs[k];
                if (t) t.value = defs[k];
            }
            let radioColor = document.querySelector('input[name="bg_type"][value="color"]');
            if (radioColor) {
                radioColor.checked = true;
                toggleBgMode('color');
            }
            syncColor('color_bg', defs['color_bg']);
        }

        // Restore tab
        const savedTab = localStorage.getItem('bio_active_tab');
        if (savedTab) {
            const btn = document.querySelector(`[data-tab="${savedTab}"]`);
            if (btn) btn.click();
        }

        // Affiliate URL scraper AJAX
        let scrapeTimeout;
        function scrapeUrl(url, immediate) {
            var btn = document.getElementById('btnScrape');
            var spinner = document.getElementById('scrapeSpinner');
            var icon = document.getElementById('scrapeIcon');
            var status = document.getElementById('scrapeStatus');

            if (!url || !url.startsWith('http')) {
                if (status) { status.textContent = 'Masukkan link produk yang valid terlebih dahulu.'; status.style.color = '#ef4444'; }
                return;
            }

            var doScrape = function () {
                if (btn) btn.disabled = true;
                if (spinner) { spinner.style.display = 'inline'; }
                if (icon) icon.style.display = 'none';
                if (status) { status.textContent = 'Sedang mengambil data produk...'; status.style.color = '#64748b'; }

                fetch('{{ route("creator.bio.scrape-url") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ url: url })
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        var preview = document.getElementById('scrapePreview');
                        if (data.error) {
                            // Show friendly error popup
                            showScrapeFailedPopup(data.error);
                            if (status) { status.textContent = data.error; status.style.color = '#ef4444'; }
                            return;
                        }
                        if (data.image || data.title) {
                            if (data.image) {
                                var img = document.getElementById('scrapeImg');
                                var displayImg = data.image.startsWith('http') ? data.image : '/storage/' + data.image.replace(/^\//, '');
                                img.src = displayImg;
                                img.style.display = 'block';
                                document.getElementById('affScrapedImage').value = data.image;
                            }
                            if (data.title) {
                                var shortTitle = data.title.length > 145 ? data.title.substring(0, 145) + '...' : data.title;
                                document.getElementById('scrapeTitle').textContent = shortTitle;
                                document.getElementById('affTitle').value = shortTitle;
                            }
                            preview.style.display = 'flex';
                            var msg = data.partial
                                ? 'Info sebagian berhasil diambil. Lengkapi data yang kurang secara manual.'
                                : 'Data berhasil diambil! Periksa judul & gambar di atas.';
                            if (status) { status.textContent = msg; status.style.color = data.partial ? '#f59e0b' : '#1eb349'; }
                        } else {
                            showScrapeFailedPopup('Tidak ada data yang berhasil diambil dari link ini.');
                            if (status) { status.textContent = 'Tidak ditemukan otomatis. Silakan isi judul & gambar manual.'; status.style.color = '#f59e0b'; }
                        }
                    })
                    .catch(function () {
                        showScrapeFailedPopup('Gagal terhubung ke server.');
                        if (status) { status.textContent = 'Gagal koneksi. Silakan isi manual.'; status.style.color = '#ef4444'; }
                    })
                    .then(function () {
                        if (btn) btn.disabled = false;
                        if (spinner) spinner.style.display = 'none';
                        if (icon) icon.style.display = 'inline';
                    });
            };

            clearTimeout(scrapeTimeout);
            if (immediate) { doScrape(); } else { scrapeTimeout = setTimeout(doScrape, 800); }
        }

        function showScrapeFailedPopup(reason) {
            // Remove existing popup if any
            var existing = document.getElementById('scrapeFailedPopup');
            if (existing) existing.remove();

            var popup = document.createElement('div');
            popup.id = 'scrapeFailedPopup';
            popup.style.cssText = 'position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);';
            popup.innerHTML = `
                            <div style="background:#fff;border-radius:16px;padding:1.75rem 1.5rem;max-width:340px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);text-align:center;">
                                <div style="width:48px;height:48px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                                    <svg width="22" height="22" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                </div>
                                <div style="font-weight:800;font-size:0.95rem;color:#0f172a;margin-bottom:0.4rem;">Gagal Mengambil Data Otomatis</div>
                                <div style="font-size:0.78rem;color:#64748b;margin-bottom:1.25rem;line-height:1.5;">${reason}<br><br>Silakan isi data produk secara <strong>manual</strong> di form bawah, atau gunakan <strong>Scan Menu AI</strong> untuk foto produk.</div>
                                <div style="display:flex;gap:0.6rem;justify-content:center;">
                                    <button onclick="document.getElementById('scrapeFailedPopup').remove()" style="flex:1;height:38px;border-radius:999px;border:1.5px solid #e2e8f0;background:#f8fafc;color:#475569;font-weight:700;font-size:0.8rem;cursor:pointer;">Isi Manual</button>
                                    <button onclick="document.getElementById('scrapeFailedPopup').remove();openScanMenuModal();" style="flex:1;height:38px;border-radius:999px;border:none;background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;font-weight:700;font-size:0.8rem;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:0.35rem;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                        Scan AI
                                    </button>
                                </div>
                            </div>`;
            document.body.appendChild(popup);
            popup.addEventListener('click', function (e) { if (e.target === popup) popup.remove(); });
        }

        function editBlock(btn) {
            const id = btn.dataset.id;
            const title = btn.dataset.title;
            const url = btn.dataset.url;
            const iconClass = btn.dataset.icon || '';

            const form = document.getElementById('editBlockForm');
            form.action = '{{ url("creator/bio/blocks") }}/' + id;
            document.getElementById('editBlockTitle').value = title;
            document.getElementById('editBlockUrl').value = url;

            const iconClassInput = document.getElementById('editBlockIconClass');
            const iconPreview = document.getElementById('editBlockIconPreview');
            iconClassInput.value = iconClass;
            if (iconClass) {
                iconPreview.innerHTML = `<i class="${iconClass}" style="font-size:24px;"></i>`;
                iconPreview.style.display = 'flex';
            } else {
                iconPreview.style.display = 'none';
            }
            document.getElementById('editBlockModal').classList.add('open');
        }

        // Attach edit block listeners
        document.querySelectorAll('.btn-edit-block').forEach(btn => {
            btn.addEventListener('click', function () { editBlock(this); });
        });

        // ── handleTypeChange: show/hide fields based on selected block type ──
        function handleTypeChange(type) {
            const titleGroup = document.getElementById('addBlock-title-group');
            const titleLabel = document.getElementById('addBlock-title-label');
            const titleInput = document.getElementById('addBlock-title-input');
            const urlGroup = document.getElementById('addBlock-url-group');
            const urlLabel = document.getElementById('addBlock-url-label');
            const urlInput = document.getElementById('addBlock-url-input');
            const iconGroup = document.getElementById('addBlock-icon-group');
            const bannerGroup = document.getElementById('addBlock-banner-group');
            const bannerUpload = document.getElementById('addBlock-banner-upload');
            const iconUpload = document.getElementById('addBlock-icon-upload');

            // Reset all required states
            titleInput.removeAttribute('required');
            urlInput.removeAttribute('required');
            if (bannerUpload) bannerUpload.removeAttribute('required');
            if (iconUpload) iconUpload.removeAttribute('required');

            if (type === 'image') {
                // Gambar/Banner: judul opsional, url opsional, ikon disembunyikan, banner upload tampil
                titleGroup.style.display = 'block';
                titleLabel.textContent = 'Judul Gambar (Opsional)';
                titleInput.placeholder = 'Contoh: Promo Ramadan';

                urlGroup.style.display = 'block';
                urlLabel.textContent = 'URL Link (Opsional, jika gambar bisa diklik)';
                urlInput.placeholder = 'https://... (opsional)';
                urlInput.removeAttribute('required');

                iconGroup.style.display = 'none';
                bannerGroup.style.display = 'block';
                if (bannerUpload) {
                    bannerUpload.disabled = false;
                    bannerUpload.setAttribute('required', 'required');
                }
                if (iconUpload) iconUpload.disabled = true;

            } else if (type === 'tiktok' || type === 'reels') {
                // Video: judul opsional, url wajib, ikon disembunyikan
                titleGroup.style.display = 'block';
                titleLabel.textContent = 'Judul Video (Opsional)';
                titleInput.placeholder = 'Contoh: Tutorial Masak';

                urlGroup.style.display = 'block';
                urlLabel.textContent = 'URL Video';
                urlInput.placeholder = type === 'reels' ? 'https://www.instagram.com/reel/...' : 'https://www.tiktok.com/@.../video/...';
                urlInput.setAttribute('required', 'required');

                iconGroup.style.display = 'none';
                bannerGroup.style.display = 'none';
                if (bannerUpload) bannerUpload.disabled = true;
                if (iconUpload) iconUpload.disabled = true;

            } else if (type === 'pdf') {
                // PDF: judul wajib, url wajib, ikon tampil, banner disembunyikan
                titleGroup.style.display = 'block';
                titleLabel.textContent = 'Judul Dokumen';
                titleInput.placeholder = 'Contoh: Portfolio 2024';
                titleInput.setAttribute('required', 'required');

                urlGroup.style.display = 'block';
                urlLabel.textContent = 'URL File PDF';
                urlInput.placeholder = 'https://...';
                urlInput.setAttribute('required', 'required');

                iconGroup.style.display = 'block';
                bannerGroup.style.display = 'none';
                if (bannerUpload) bannerUpload.disabled = true;
                if (iconUpload) iconUpload.disabled = false;

            } else {
                // link (default): judul wajib, url wajib, ikon tampil, banner disembunyikan
                titleGroup.style.display = 'block';
                titleLabel.textContent = 'Judul / Label Tombol';
                titleInput.placeholder = 'Contoh: Download Portfolio';
                titleInput.setAttribute('required', 'required');

                urlGroup.style.display = 'block';
                urlLabel.textContent = 'URL / Link';
                urlInput.placeholder = 'https://...';
                urlInput.setAttribute('required', 'required');

                iconGroup.style.display = 'block';
                bannerGroup.style.display = 'none';
                if (bannerUpload) bannerUpload.disabled = true;
                if (iconUpload) iconUpload.disabled = false;
            }
        }

        // Init on page load (default: link)
        handleTypeChange('link');

        // Initialize Sortable for interactive block reordering
        document.addEventListener('DOMContentLoaded', () => {
            const sortableEl = document.getElementById('sortable-blocks');
            if (sortableEl && typeof Sortable !== 'undefined') {
                new Sortable(sortableEl, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    onEnd: function () {
                        const ids = Array.from(sortableEl.querySelectorAll('.block-item')).map(el => el.dataset.id);
                        fetch('{{ route("creator.bio.blocks.reorder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ ids: ids })
                        }).then(r => r.json()).then(data => {
                            if (data.ok) {
                                console.log('Urutan block berhasil disimpan');
                            }
                        }).catch(err => {
                            console.error('Reorder error:', err);
                        });
                    }
                });
            }
        });

        // Custom confirm modal
        let _pendingDeleteForm = null;
        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.remove('open');
            _pendingDeleteForm = null;
        }
        document.querySelectorAll('.btn-delete-block').forEach(btn => {
            btn.addEventListener('click', function () {
                _pendingDeleteForm = this.closest('.form-delete-block');
                document.getElementById('confirmModal').classList.add('open');
            });
        });
        function doConfirmAction() {
            if (_pendingDeleteForm) {
                _pendingDeleteForm.submit();
            }
            closeConfirmModal();
        }

        // ── Icon Picker ──────────────────────────────────────────
        const ICON_LIST = [
            // Communication
            'ph ph-chat-circle', 'ph ph-chat-dots', 'ph ph-phone', 'ph ph-phone-call', 'ph ph-envelope',
            'ph ph-envelope-simple', 'ph ph-telegram-logo', 'ph ph-whatsapp-logo', 'ph ph-instagram-logo',
            'ph ph-tiktok-logo', 'ph ph-youtube-logo', 'ph ph-twitter-logo', 'ph ph-facebook-logo',
            'ph ph-linkedin-logo', 'ph ph-discord-logo', 'ph ph-snapchat-logo', 'ph ph-pinterest-logo',
            // Commerce
            'ph ph-shopping-cart', 'ph ph-shopping-bag', 'ph ph-storefront', 'ph ph-tag', 'ph ph-currency-circle-dollar',
            'ph ph-wallet', 'ph ph-credit-card', 'ph ph-bank', 'ph ph-receipt', 'ph ph-package',
            'ph ph-gift', 'ph ph-percent', 'ph ph-barcode', 'ph ph-qr-code',
            // Media & Files
            'ph ph-play-circle', 'ph ph-video', 'ph ph-microphone', 'ph ph-music-note', 'ph ph-headphones',
            'ph ph-film-strip', 'ph ph-image', 'ph ph-images', 'ph ph-camera', 'ph ph-file-pdf',
            'ph ph-file-text', 'ph ph-file-zip', 'ph ph-download', 'ph ph-upload', 'ph ph-cloud',
            // Navigation
            'ph ph-house', 'ph ph-map-pin', 'ph ph-compass', 'ph ph-navigation', 'ph ph-globe',
            'ph ph-globe-hemisphere-east', 'ph ph-link', 'ph ph-link-simple', 'ph ph-arrow-right',
            'ph ph-arrow-circle-right', 'ph ph-arrow-square-out', 'ph ph-caret-right',
            // People & Profile
            'ph ph-user', 'ph ph-users', 'ph ph-user-circle', 'ph ph-identification-badge',
            'ph ph-smiley', 'ph ph-handshake', 'ph ph-hand-waving',
            // Business
            'ph ph-briefcase', 'ph ph-chart-bar', 'ph ph-chart-line', 'ph ph-presentation-chart',
            'ph ph-clipboard-text', 'ph ph-calendar', 'ph ph-clock', 'ph ph-bell', 'ph ph-megaphone',
            'ph ph-broadcast', 'ph ph-projector-screen', 'ph ph-article', 'ph ph-newspaper',
            // Creative
            'ph ph-palette', 'ph ph-pen', 'ph ph-pen-nib', 'ph ph-pencil', 'ph ph-paint-brush',
            'ph ph-magic-wand', 'ph ph-star', 'ph ph-sparkle', 'ph ph-crown', 'ph ph-trophy',
            'ph ph-medal', 'ph ph-lightning', 'ph ph-fire', 'ph ph-heart', 'ph ph-diamond',
            // Tech
            'ph ph-code', 'ph ph-code-block', 'ph ph-terminal', 'ph ph-laptop', 'ph ph-device-mobile',
            'ph ph-robot', 'ph ph-gear', 'ph ph-plugin', 'ph ph-cpu', 'ph ph-wifi',
            // Misc
            'ph ph-book', 'ph ph-book-open', 'ph ph-graduation-cap', 'ph ph-certificate',
            'ph ph-first-aid-kit', 'ph ph-leaf', 'ph ph-planet', 'ph ph-rocket', 'ph ph-key',
        ];

        let _activeIconInputId = null;
        let _activeIconPreviewId = null;

        function openIconPicker(iconInputId, iconPreviewId) {
            _activeIconInputId = iconInputId;
            _activeIconPreviewId = iconPreviewId;

            const grid = document.getElementById('iconGrid');
            if (!grid.hasChildNodes()) {
                ICON_LIST.forEach(iconClass => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'icon-btn';
                    btn.title = iconClass.replace('ph ph-', '').replace(/-/g, ' ');
                    btn.innerHTML = `<i class="${iconClass}"></i>`;
                    btn.onclick = () => selectIcon(iconClass);
                    grid.appendChild(btn);
                });
            }
            document.getElementById('iconPickerModal').classList.add('open');
        }

        function selectIcon(iconClass) {
            if (!_activeIconInputId) return;
            document.getElementById(_activeIconInputId).value = iconClass;
            const preview = document.getElementById(_activeIconPreviewId);
            preview.innerHTML = `<i class="${iconClass}" style="font-size:24px;"></i>`;
            preview.style.display = 'flex';
            document.getElementById('iconPickerModal').classList.remove('open');
        }

        // UMKM Modal Helpers
        function toggleWaText(show) {
            const el = document.getElementById('waTextGroup');
            if (el) el.style.display = show ? '' : 'none';
        }

        function validateUmkmImages(input, previewId) {
            const MAX_FILES = 3;
            const MAX_SIZE = 1 * 1024 * 1024; // 1 MB
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';

            const files = Array.from(input.files);

            // Check count
            if (files.length > MAX_FILES) {
                document.getElementById('imgOversizedTitle').innerText = 'Jumlah Foto Melebihi Batas';
                document.getElementById('imgOversizedDesc').innerHTML = 'Maksimal <strong>3 foto</strong> per produk.';
                document.getElementById('imgOversizedFileList').innerHTML = `<div style="color:#EF4444;">Anda memilih ${files.length} foto. Silakan pilih maksimal 3 foto.</div>`;
                document.getElementById('imgOversizedModal').classList.add('open');
                input.value = '';
                return;
            }

            // Check individual file sizes
            const oversized = files.filter(f => f.size > MAX_SIZE);
            if (oversized.length > 0) {
                document.getElementById('imgOversizedTitle').innerText = 'Ukuran Foto Melebihi Batas 1 MB';
                document.getElementById('imgOversizedDesc').innerHTML = 'Foto berikut melebihi batas <strong>1 MB per file</strong>. Harap kompres terlebih dahulu.';

                const listHtml = oversized.map(f => `
                                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.25rem 0; border-bottom:1px dashed #E2E8F0;">
                                    <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:220px;">• ${f.name}</span>
                                    <span style="color:#EF4444; font-weight:700;">${(f.size / 1024 / 1024).toFixed(2)} MB</span>
                                </div>
                            `).join('');

                document.getElementById('imgOversizedFileList').innerHTML = listHtml;
                document.getElementById('imgOversizedModal').classList.add('open');
                input.value = '';
                return;
            }

            // Show preview
            files.slice(0, MAX_FILES).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText = 'width:70px;height:70px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0;';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }

        // Keep old aliases for backward compatibility
        function previewUmkmImages(input) { validateUmkmImages(input, 'umkmImagePreview'); }
        function previewEditUmkmImages(input) { validateUmkmImages(input, 'editNewImagePreview'); }


        function formatRupiahInput(el) {
            let val = el.value.replace(/[^0-9]/g, '');
            if (!val) {
                el.value = '';
                return;
            }
            el.value = new Intl.NumberFormat('id-ID').format(parseInt(val, 10));
        }

        function validateUmkmForm(form) {
            let isValid = true;
            let firstInvalid = null;

            // Reset field error highlights
            form.querySelectorAll('.form-input, input, select, textarea').forEach(el => {
                el.style.borderColor = '';
                el.style.backgroundColor = '';
            });

            // 1. Nama Produk
            const titleEl = form.querySelector('[name="title"]');
            if (titleEl && !titleEl.value.trim()) {
                isValid = false;
                titleEl.style.borderColor = '#ef4444';
                titleEl.style.backgroundColor = '#fff5f5';
                if (!firstInvalid) firstInvalid = titleEl;
            }

            // 2. Harga Jual (Wajib > 0)
            const priceEl = form.querySelector('[name="price"]');
            let priceVal = 0;
            if (priceEl) {
                priceVal = parseFloat(priceEl.value.replace(/[^0-9]/g, '')) || 0;
                if (priceVal <= 0) {
                    isValid = false;
                    priceEl.style.borderColor = '#ef4444';
                    priceEl.style.backgroundColor = '#fff5f5';
                    if (!firstInvalid) firstInvalid = priceEl;
                }
            }

            // 3. Harga Sebelum Diskon (Harus > Harga Jual jika diisi)
            const origPriceEl = form.querySelector('[name="original_price"]');
            if (origPriceEl && origPriceEl.value.trim()) {
                let origVal = parseFloat(origPriceEl.value.replace(/[^0-9]/g, '')) || 0;
                if (origVal > 0 && origVal <= priceVal) {
                    isValid = false;
                    origPriceEl.style.borderColor = '#ef4444';
                    origPriceEl.style.backgroundColor = '#fff5f5';
                    alert('⚠️ Perhatian: Harga Sebelum Diskon (Harga Normal) HARUS lebih besar daripada Harga Jual (Harga Promo)!');
                    if (!firstInvalid) firstInvalid = origPriceEl;
                }
            }

            // 4. Berat Fisik (Gram) (Wajib >= 1 gram)
            const weightEl = form.querySelector('[name="weight"]');
            if (weightEl) {
                let weightVal = parseFloat(weightEl.value) || 0;
                if (weightVal < 1) {
                    isValid = false;
                    weightEl.style.borderColor = '#ef4444';
                    weightEl.style.backgroundColor = '#fff5f5';
                    if (!firstInvalid) firstInvalid = weightEl;
                }
            }

            if (!isValid) {
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }

            // Strip dot formatting before submit
            form.querySelectorAll('input[name="price"], input[name="original_price"]').forEach(inp => {
                inp.value = inp.value.replace(/[^0-9]/g, '');
            });

            return true;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('#addUmkmModal form, #editUmkmModal form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    if (!validateUmkmForm(this)) {
                        e.preventDefault();
                    }
                });
            });
        });

        function calcUmkmVolume(el) {
            const form = el.closest('form');
            if (!form) return;
            const p = parseFloat(form.querySelector('.umkm-dim-p')?.value || 0);
            const l = parseFloat(form.querySelector('.umkm-dim-l')?.value || 0);
            const t = parseFloat(form.querySelector('.umkm-dim-t')?.value || 0);
            const vEl = form.querySelector('.umkm-dim-v');
            if (vEl) {
                if (p > 0 && l > 0 && t > 0) {
                    vEl.value = Math.round(p * l * t);
                } else {
                    vEl.value = '';
                }
            }
        }

        function editUmkmProduct(data) {
            const form = document.getElementById('editUmkmForm');
            form.action = '/creator/bio/blocks/' + data.id;
            document.getElementById('edit_title').value = data.title || '';
            document.getElementById('edit_price').value = data.price ? new Intl.NumberFormat('id-ID').format(data.price) : '';
            document.getElementById('edit_original_price').value = data.original_price ? new Intl.NumberFormat('id-ID').format(data.original_price) : '';
            document.getElementById('edit_description').value = data.description || '';
            document.getElementById('edit_category').value = data.category || 'Makanan';
            document.getElementById('edit_stock').value = (data.stock !== undefined && data.stock !== null && data.stock !== '') ? data.stock : '';
            document.getElementById('edit_url').value = data.url || '';

            // Shipping & Volume fields
            document.getElementById('edit_sku').value = data.sku || '';
            document.getElementById('edit_weight').value = data.weight || 1000;
            document.getElementById('edit_length').value = data.length || '';
            document.getElementById('edit_width').value = data.width || '';
            document.getElementById('edit_height').value = data.height || '';
            document.getElementById('edit_volume').value = data.volume || '';

            if (data.payment_method === 'web') {
                document.getElementById('edit_pm_web').checked = true;
            } else {
                document.getElementById('edit_pm_wa').checked = true;
            }

            // Reset Containers
            const savedContainer = document.getElementById('editSavedImagesContainer');
            const deleteInputs = document.getElementById('deleteExistingImagesInputs');
            const newPreview = document.getElementById('editNewImagePreview');
            if (savedContainer) savedContainer.innerHTML = '';
            if (deleteInputs) deleteInputs.innerHTML = '';
            if (newPreview) newPreview.innerHTML = '';

            // Render Saved Images with SVG Delete Button
            const images = data.images || [];
            images.forEach(img => {
                const wrap = document.createElement('div');
                wrap.style.cssText = 'position:relative; width:75px; height:75px; border-radius:10px; overflow:hidden; border:2px solid #e2e8f0; box-shadow:0 2px 6px rgba(0,0,0,0.05);';

                const image = document.createElement('img');
                image.src = img.startsWith('http') ? img : '/storage/' + img;
                image.style.cssText = 'width:100%; height:100%; object-fit:cover; display:block;';

                const delBtn = document.createElement('button');
                delBtn.type = 'button';
                delBtn.innerHTML = '<svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>';
                delBtn.title = 'Hapus foto ini dari server';
                delBtn.style.cssText = 'position:absolute; top:3px; right:3px; width:22px; height:22px; border-radius:50%; background:#ef4444; color:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 4px rgba(0,0,0,0.3);';

                delBtn.onclick = function () {
                    wrap.remove();
                    if (deleteInputs) {
                        const inp = document.createElement('input');
                        inp.type = 'hidden';
                        inp.name = 'delete_existing_images[]';
                        inp.value = img;
                        deleteInputs.appendChild(inp);
                    }
                };

                wrap.appendChild(image);
                wrap.appendChild(delBtn);
                if (savedContainer) savedContainer.appendChild(wrap);
            });

            document.getElementById('editUmkmModal').classList.add('open');
        }

        function previewEditUmkmImages(input) {
            const preview = document.getElementById('editNewImagePreview');
            if (!preview) return;
            preview.innerHTML = '';
            const files = Array.from(input.files).slice(0, 3);
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const wrap = document.createElement('div');
                    wrap.style.cssText = 'position:relative; width:75px; height:75px; border-radius:10px; overflow:hidden; border:2px solid #1eb349; box-shadow:0 2px 6px rgba(0,0,0,0.05);';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText = 'width:100%; height:100%; object-fit:cover; display:block;';

                    const delBtn = document.createElement('button');
                    delBtn.type = 'button';
                    delBtn.title = 'Batalkan foto ini';
                    delBtn.innerHTML = '<svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>';
                    delBtn.style.cssText = 'position:absolute; top:3px; right:3px; width:22px; height:22px; border-radius:50%; background:#ef4444; color:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 4px rgba(0,0,0,0.3);';

                    delBtn.onclick = function () {
                        wrap.remove();
                    };

                    wrap.appendChild(img);
                    wrap.appendChild(delBtn);
                    preview.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });
        }

        function filterModalCatalog(className, query, sortKey) {
            const q = (query || '').toLowerCase().trim();
            const grid = document.querySelector('.' + className + '-grid');
            const items = Array.from(document.getElementsByClassName(className));

            // Show/hide based on search
            items.forEach(item => {
                const searchText = item.getAttribute('data-search') || '';
                item.style.display = (!q || searchText.includes(q)) ? '' : 'none';
            });

            // Sort
            if (sortKey && grid) {
                const visible = items.filter(i => i.style.display !== 'none');
                visible.sort((a, b) => {
                    const av = parseFloat(a.getAttribute('data-' + sortKey) || 0);
                    const bv = parseFloat(b.getAttribute('data-' + sortKey) || 0);
                    if (sortKey === 'date') return bv - av; // newest first by default, toggle via button
                    return bv - av; // descending for price/clicks
                });
                visible.forEach(el => grid.appendChild(el));
            }
        }

        function setCatalogSort(className, sortKey, btn) {
            // Toggle direction
            const currentDir = btn.getAttribute('data-dir') || 'desc';
            const newDir = currentDir === 'desc' ? 'asc' : 'desc';
            btn.setAttribute('data-dir', newDir);

            // Update active state
            const allBtns = btn.closest('.modal-filter-row').querySelectorAll('.modal-filter-btn[data-sort]');
            allBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const grid = document.querySelector('.' + className + '-grid');
            if (!grid) return;

            const items = Array.from(document.getElementsByClassName(className));
            const visible = items.filter(i => i.style.display !== 'none');

            visible.sort((a, b) => {
                const av = parseFloat(a.getAttribute('data-' + sortKey) || 0);
                const bv = parseFloat(b.getAttribute('data-' + sortKey) || 0);
                return newDir === 'desc' ? bv - av : av - bv;
            });
            visible.forEach(el => grid.appendChild(el));
        }
        // ── Produk Fisik: Guard — cek lokasi sebelum buka modal ───────────────
        // raja_city_id di-inject dari Blade server-side (null jika belum diisi)
        const _creatorRajaCityId = @json($profile->raja_city_id ?? null);
        const _creatorCityId = @json($profile->city_id ?? null);

        function openAddUmkmWithCheck() {
            // Dianggap lengkap kalau ada raja_city_id ATAU city_id (migrasi lama)
            if (_creatorRajaCityId || _creatorCityId) {
                document.getElementById('addUmkmModal').classList.add('open');
            } else {
                document.getElementById('locationWarningModal').classList.add('open');
            }
        }

        // ── UMKM: Search / Filter ──────────────────────────────────────────

        function filterUmkmProducts() {
            const q = (document.getElementById('umkmSearch')?.value || '').toLowerCase().trim();
            const items = document.querySelectorAll('#umkmProductList .aff-card');
            items.forEach(item => {
                const title = (item.querySelector('.aff-title')?.textContent || '').toLowerCase();
                item.style.display = (!q || title.includes(q)) ? '' : 'none';
            });
        }

        // ── UMKM: Sort Terbaru / Terlama ──────────────────────────────────
        let umkmSortDir = 'terbaru'; // default newest first (highest id first)
        function sortUmkmProducts(dir) {
            umkmSortDir = dir;
            const list = document.getElementById('umkmProductList');
            if (!list) return;

            const items = Array.from(list.querySelectorAll('.aff-card'));
            items.sort((a, b) => {
                const ai = parseInt(a.getAttribute('data-id') || 0, 10);
                const bi = parseInt(b.getAttribute('data-id') || 0, 10);
                return dir === 'terbaru' ? bi - ai : ai - bi;
            });
            items.forEach(el => list.appendChild(el));

            // Update button states
            const btnTerbaru = document.getElementById('sortTerbaru');
            const btnTerlama = document.getElementById('sortTerlama');
            if (dir === 'terbaru') {
                btnTerbaru.style.background = '#0f172a';
                btnTerbaru.style.color = '#fff';
                btnTerbaru.style.border = '1.5px solid #0f172a';
                btnTerlama.style.background = '#f8fafc';
                btnTerlama.style.color = '#64748b';
                btnTerlama.style.border = '1.5px solid #e2e8f0';
            } else {
                btnTerlama.style.background = '#0f172a';
                btnTerlama.style.color = '#fff';
                btnTerlama.style.border = '1.5px solid #0f172a';
                btnTerbaru.style.background = '#f8fafc';
                btnTerbaru.style.color = '#64748b';
                btnTerbaru.style.border = '1.5px solid #0f172a';
            }
        }

        // ── UMKM: Scrape Shopee / Tokopedia ───────────────────────────────
        let _scrapeImageUrl = '';

        function doScrapeProduct() {
            const url = (document.getElementById('scrapeUrlInput')?.value || '').trim();
            if (!url) {
                showScrapeStatus('Masukkan URL produk terlebih dahulu.', 'error');
                return;
            }

            const btn = document.getElementById('scrapeBtn');
            const btnText = document.getElementById('scrapeBtnText');
            const btnIcon = document.getElementById('scrapeBtnIcon');

            btn.disabled = true;
            btnText.textContent = 'Mengambil...';
            if (btnIcon) {
                btnIcon.classList.add('spin-icon');
                btnIcon.innerHTML = '<path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>';
            }
            hideScrapePreview();
            showScrapeStatus('Mengambil data dari ' + (url.includes('shopee') ? 'Shopee' : (url.includes('tokopedia') ? 'Tokopedia' : 'marketplace')) + '...', 'info');

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                || document.querySelector('input[name="_token"]')?.value || '';

            fetch('{{ route("creator.bio.scrape-url") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ url })
            })
                .then(res => res.json().then(data => ({ ok: res.ok, data })))
                .then(({ ok, data }) => {
                    btn.disabled = false;
                    btnText.textContent = 'Cari';
                    if (btnIcon) {
                        btnIcon.classList.remove('spin-icon');
                        btnIcon.innerHTML = '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>';
                    }

                    if (!ok || data.error) {
                        showScrapeStatus((data.error || 'Gagal mengambil data produk.'), 'error');
                        showScrapeFailedPopup(data.error || 'Gagal mengambil data produk.');
                        return;
                    }

                    // Fill form fields
                    const titleEl = document.getElementById('umkmTitle');
                    const priceEl = document.querySelector('#addUmkmForm input[name="price"]');
                    const origPriceEl = document.querySelector('#addUmkmForm input[name="original_price"]');
                    const descEl = document.getElementById('umkmDescription');

                    if (titleEl && data.title) titleEl.value = data.title;
                    if (priceEl && data.price) priceEl.value = new Intl.NumberFormat('id-ID').format(data.price);
                    if (origPriceEl && data.original_price) origPriceEl.value = new Intl.NumberFormat('id-ID').format(data.original_price);
                    if (descEl && data.description) descEl.value = data.description;

                    // Auto-select category to "Barang" for imported marketplace products
                    const catEl = document.querySelector('#addUmkmForm select[name="category"]');
                    if (catEl) catEl.value = 'Barang';

                    // Handle image
                    _scrapeImageUrl = data.image || '';
                    document.getElementById('scrapeImageUrl').value = _scrapeImageUrl;

                    if (_scrapeImageUrl) {
                        const box = document.getElementById('scrapeImgPreviewBox');
                        const thumb = document.getElementById('scrapeImgThumb');
                        const urlLabel = document.getElementById('scrapeImgUrl');
                        if (box) { box.style.display = 'flex'; }
                        if (thumb) { thumb.src = _scrapeImageUrl; thumb.onerror = () => { box.style.display = 'none'; }; }
                        if (urlLabel) { urlLabel.textContent = _scrapeImageUrl; }
                    }

                    // Show inline preview in scrape panel
                    showScrapePreview(data);
                    showScrapeStatus('Data berhasil diambil! Cek form di bawah.', 'success');
                })
                .catch(err => {
                    btn.disabled = false;
                    btnText.textContent = 'Cari';
                    if (btnIcon) {
                        btnIcon.classList.remove('spin-icon');
                        btnIcon.innerHTML = '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>';
                    }
                    showScrapeStatus('Gagal koneksi: ' + err.message, 'error');
                });
        }

        function showScrapeStatus(msg, type) {
            const el = document.getElementById('scrapeStatus');
            if (!el) return;
            const colors = { error: '#ef4444', success: '#1eb349', info: '#64748b' };
            el.style.color = colors[type] || '#64748b';
            el.style.fontWeight = type !== 'info' ? '700' : '500';
            el.textContent = msg;
            el.style.display = 'block';
        }

        function showScrapePreview(data) {
            const box = document.getElementById('scrapePreview');
            if (!box) return;
            box.style.display = 'flex';
            const img = document.getElementById('scrapePreviewImg');
            const titleEl = document.getElementById('scrapePreviewTitle');
            const priceEl = document.getElementById('scrapePreviewPrice');
            if (img && data.image) { img.src = data.image; img.style.display = 'block'; img.onerror = () => { img.style.display = 'none'; }; }
            if (titleEl) titleEl.textContent = data.title || '';
            if (priceEl) {
                let priceText = data.price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data.price) : '';
                if (data.original_price && data.original_price > data.price) {
                    priceText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.price) + ' (Sebelum Diskon: Rp ' + new Intl.NumberFormat('id-ID').format(data.original_price) + ')';
                }
                priceEl.textContent = priceText;
            }
        }

        function hideScrapePreview() {
            const box = document.getElementById('scrapePreview');
            if (box) box.style.display = 'none';
        }

        function clearScrapeImage() {
            _scrapeImageUrl = '';
            document.getElementById('scrapeImageUrl').value = '';
            const box = document.getElementById('scrapeImgPreviewBox');
            if (box) box.style.display = 'none';
        }

        function closeAddUmkmModal() {
            document.getElementById('addUmkmModal').classList.remove('open');
            // Reset scrape state
            const urlInput = document.getElementById('scrapeUrlInput');
            if (urlInput) urlInput.value = '';
            hideScrapePreview();
            const statusEl = document.getElementById('scrapeStatus');
            if (statusEl) { statusEl.textContent = ''; statusEl.style.display = 'none'; }
            clearScrapeImage();
        }

        // Enter key triggers scrape
        document.addEventListener('DOMContentLoaded', function () {
            const scrapeInput = document.getElementById('scrapeUrlInput');
            if (scrapeInput) {
                scrapeInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') { e.preventDefault(); doScrapeProduct(); }
                });
            }
        });
    </script>

    {{-- ── Modal Wide: Katalog Affiliate buyle.id ── --}}
    <div class="modal-overlay" id="affiliateCatalogModal" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="modal-box-wide" style="border: 1px solid #E2E8F0;">
            <div
                style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; padding-bottom:0.75rem; border-bottom:1px solid #F1F5F9;">
                <div>
                    <h3
                        style="font-size:1.1rem; font-weight:800; color:#0F172A; font-family:'Montserrat',sans-serif; margin:0; display:flex; align-items:center; gap:0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                        Katalog Produk Affiliate buyle.id
                    </h3>
                    <p style="font-size:0.78rem; color:#64748B; margin:0.25rem 0 0;">
                        Pilih produk dari creator buyle.id lain dan dapatkan komisi setiap penjualan.
                    </p>
                </div>
                <button type="button" onclick="document.getElementById('affiliateCatalogModal').classList.remove('open')"
                    style="background:#f1f5f9; border:none; width:34px; height:34px; border-radius:50%; font-size:1.2rem; cursor:pointer; color:#64748B; display:flex; align-items:center; justify-content:center;">&times;</button>
            </div>

            {{-- Search Bar --}}
            <div style="position:relative; margin-bottom:1rem;">
                <span style="position:absolute; left:0.85rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                    <svg width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </span>
                <input type="text" id="searchAffiliateModalInput"
                    onkeyup="filterModalCatalog('affiliateModalItem', this.value)" class="form-input"
                    placeholder="Cari nama produk atau creator..."
                    style="height:44px; padding-left:2.5rem; font-size:0.875rem; border-radius:12px; border:1.5px solid #E2E8F0; background:#FAFAFA;">
            </div>

            {{-- Grid Product List --}}
            <div class="modal-catalog-grid">
                @php
                    $addedAffProductIds = $blocks->where('type', 'buyle_affiliate')->pluck('data_json.product_id')->filter()->toArray();
                @endphp

                @forelse($affiliateProducts as $affProduct)
                    @php
                        $effPrice = $affProduct->sale_price ?? $affProduct->price;
                        $commRate = $affProduct->affiliate_commission_rate ?? 10;
                        $commRp = round(($effPrice * $commRate) / 100);
                        $isAdded = in_array($affProduct->id, $addedAffProductIds);
                        $matchingBlock = $blocks->where('type', 'buyle_affiliate')->first(fn($b) => ($b->data_json['product_id'] ?? null) == $affProduct->id);
                    @endphp
                    <div class="affiliateModalItem modal-card-item"
                        data-search="{{ strtolower($affProduct->name . ' ' . ($affProduct->seller->name ?? '')) }}">

                        {{-- 1:1 Aspect Ratio Photo --}}
                        <div
                            style="position:relative; width:100%; aspect-ratio:1/1; border-radius:10px; overflow:hidden; background:#F8FAFC; border:1px solid #E2E8F0;">
                            <img src="{{ $affProduct->main_image }}"
                                style="width:100%; height:100%; object-fit:cover; display:block;">
                        </div>

                        {{-- Details --}}
                        <div
                            style="display:flex; flex-direction:column; flex:1; justify-content:space-between; gap:0.4rem; margin-top:0.5rem;">
                            <div>
                                <div style="font-weight:700; font-size:0.8rem; color:#0F172A; line-height:1.25; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;"
                                    title="{{ $affProduct->name }}">
                                    {{ $affProduct->name }}
                                </div>
                                <div
                                    style="font-size:0.7rem; color:#64748B; margin-top:0.2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    Oleh: <strong
                                        style="color:#334155;">{{ $affProduct->seller->name ?? 'Creator buyle.id' }}</strong>
                                </div>
                            </div>

                            <div>
                                <div style="font-size:0.82rem; color:#0F172A; font-weight:800;">
                                    Rp {{ number_format($effPrice, 0, ',', '.') }}
                                </div>
                                <div
                                    style="font-size:0.68rem; font-weight:700; color:#166534; background:#ECFDF5; border:1px solid #A7F3D0; padding:0.15rem 0.4rem; border-radius:6px; margin-top:0.2rem; display:flex; align-items:center; gap:0.2rem;">
                                    <svg width="10" height="10" fill="none" stroke="#166534" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                    </svg>
                                    <span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Komisi
                                        {{ $commRate }}%: Rp {{ number_format($commRp, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div style="display:flex; flex-direction:column; gap:0.35rem; margin-top:0.35rem;">
                                <a href="{{ route('products.show', $affProduct->slug) }}" target="_blank"
                                    style="display:flex; align-items:center; justify-content:center; gap:0.25rem; font-size:0.72rem; font-weight:700; color:#475569; background:#F1F5F9; border:1px solid #CBD5E1; padding:0.35rem 0.4rem; border-radius:8px; text-decoration:none; transition:all 0.15s;">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                    Lihat Detail
                                </a>

                                @if($isAdded && $matchingBlock)
                                    <form action="{{ route('creator.bio.blocks.destroy', $matchingBlock) }}" method="POST"
                                        style="margin:0;">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="width:100%; border:1px solid #FCA5A5; color:#DC2626; background:#FEF2F2; font-size:0.72rem; padding:0.35rem 0.4rem; border-radius:8px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.25rem;">
                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                            </svg>
                                            Hapus dari Bio
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('creator.bio.blocks.store') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="type" value="buyle_affiliate">
                                        <input type="hidden" name="title" value="{{ $affProduct->name }}">
                                        <input type="hidden" name="url" value="{{ route('products.show', $affProduct->slug) }}">
                                        <input type="hidden" name="product_id" value="{{ $affProduct->id }}">
                                        <button type="submit"
                                            style="width:100%; background:#1eb349; color:#fff; font-size:0.72rem; padding:0.35rem 0.4rem; height:32px; border-radius:8px; font-weight:700; cursor:pointer; border:none; display:flex; align-items:center; justify-content:center; gap:0.25rem;">
                                            + Tambah ke Bio
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align:center; padding:2.5rem; color:#94A3B8; font-size:0.85rem;">
                        Belum ada produk affiliate yang tersedia saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Modal Wide: Katalog White Label buyle.id ── --}}
    <div class="modal-overlay" id="whitelabelCatalogModal" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="modal-box-wide" style="border: 1px solid #E2E8F0;">
            <div
                style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; padding-bottom:0.75rem; border-bottom:1px solid #F1F5F9;">
                <div>
                    <h3
                        style="font-size:1.15rem; font-weight:800; color:#0F172A; font-family:'Montserrat',sans-serif; margin:0; display:flex; align-items:center; gap:0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                        Katalog Produk White Label buyle.id
                    </h3>
                    <p style="font-size:0.78rem; color:#64748B; margin:0.25rem 0 0;">
                        Bebas watermark & disetujui Tim Buyle. Tentukan harga jual (markup) sendiri di Bio Page Anda!
                    </p>
                </div>
                <button type="button" onclick="document.getElementById('whitelabelCatalogModal').classList.remove('open')"
                    style="background:#f1f5f9; border:none; width:34px; height:34px; border-radius:50%; font-size:1.2rem; cursor:pointer; color:#64748B; display:flex; align-items:center; justify-content:center;">&times;</button>
            </div>

            {{-- Search Bar --}}
            <div style="position:relative; margin-bottom:1rem;">
                <span style="position:absolute; left:0.85rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                    <svg width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </span>
                <input type="text" id="searchWhitelabelModalInput"
                    onkeyup="filterModalCatalog('whitelabelModalItem', this.value)" class="form-input"
                    placeholder="Cari nama produk white label, creator, atau lisensi..."
                    style="height:44px; padding-left:2.5rem; font-size:0.875rem; border-radius:12px; border:1.5px solid #E2E8F0; background:#FAFAFA;">
            </div>

            {{-- Grid Product List --}}
            <div class="modal-catalog-grid">
                @php
                    $addedWlProductIds = $blocks->where('type', 'buyle_product')->pluck('data_json.product_id')->filter()->toArray();
                @endphp

                @forelse($whitelabelProducts as $wlProd)
                    @php
                        $isAddedWl = in_array($wlProd->id, $addedWlProductIds);
                        $matchingWlBlock = $blocks->where('type', 'buyle_product')->first(fn($b) => ($b->data_json['product_id'] ?? null) == $wlProd->id);
                    @endphp
                    <div class="whitelabelModalItem modal-card-item"
                        data-search="{{ strtolower($wlProd->name . ' ' . ($wlProd->seller->name ?? '') . ' ' . ($wlProd->whitelabel_terms ?? '')) }}">

                        {{-- 1:1 Aspect Ratio Photo --}}
                        <div
                            style="position:relative; width:100%; aspect-ratio:1/1; border-radius:10px; overflow:hidden; background:#F8FAFC; border:1px solid #E2E8F0;">
                            <img src="{{ $wlProd->main_image }}"
                                style="width:100%; height:100%; object-fit:cover; display:block;">
                        </div>

                        {{-- Details --}}
                        <div
                            style="display:flex; flex-direction:column; flex:1; justify-content:space-between; gap:0.4rem; margin-top:0.5rem;">
                            <div>
                                <div style="font-weight:700; font-size:0.8rem; color:#0F172A; line-height:1.25; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;"
                                    title="{{ $wlProd->name }}">
                                    {{ $wlProd->name }}
                                </div>
                                <div
                                    style="font-size:0.7rem; color:#64748B; margin-top:0.2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    Oleh: <strong
                                        style="color:#334155;">{{ $wlProd->seller->name ?? 'Creator buyle.id' }}</strong>
                                </div>
                            </div>

                            <div>
                                @if($wlProd->whitelabel_price)
                                    <div style="font-size:0.68rem; color:#64748B;">Min. Resell:</div>
                                    <div style="font-size:0.82rem; color:#166534; font-weight:800;">
                                        Rp {{ number_format($wlProd->whitelabel_price, 0, ',', '.') }}
                                    </div>
                                @else
                                    <div style="font-size:0.68rem; color:#64748B;">Harga Asli:</div>
                                    <div style="font-size:0.82rem; color:#166534; font-weight:800;">
                                        Rp {{ number_format($wlProd->price, 0, ',', '.') }}
                                    </div>
                                @endif
                                @if($wlProd->whitelabel_terms)
                                    <div
                                        style="font-size:0.65rem; color:#64748b; margin-top:0.2rem; display:flex; align-items:center; gap:0.2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        <svg width="10" height="10" fill="none" stroke="#94a3b8" stroke-width="2"
                                            viewBox="0 0 24 24" style="flex-shrink:0;">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                        </svg>
                                        <span>Lisensi: {{ Str::limit($wlProd->whitelabel_terms, 25) }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div style="display:flex; flex-direction:column; gap:0.35rem; margin-top:0.35rem;">
                                <a href="{{ route('products.show', $wlProd->slug) }}" target="_blank"
                                    style="display:flex; align-items:center; justify-content:center; gap:0.25rem; font-size:0.72rem; font-weight:700; color:#475569; background:#F1F5F9; border:1px solid #CBD5E1; padding:0.35rem 0.4rem; border-radius:8px; text-decoration:none; transition:all 0.15s;">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                    Lihat Detail
                                </a>

                                @if($isAddedWl && $matchingWlBlock)
                                    <form action="{{ route('creator.bio.blocks.destroy', $matchingWlBlock) }}" method="POST"
                                        style="margin:0;">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="width:100%; border:1px solid #FCA5A5; color:#DC2626; background:#FEF2F2; font-size:0.72rem; padding:0.35rem 0.4rem; border-radius:8px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.25rem;">
                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                            </svg>
                                            Hapus dari Bio
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('creator.bio.blocks.store') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="type" value="buyle_product">
                                        <input type="hidden" name="title" value="{{ $wlProd->name }}">
                                        <input type="hidden" name="url" value="{{ route('products.show', $wlProd->slug) }}">
                                        <input type="hidden" name="product_id" value="{{ $wlProd->id }}">
                                        <button type="submit"
                                            style="width:100%; background:#1eb349; color:#fff; font-size:0.72rem; padding:0.35rem 0.4rem; height:32px; border-radius:8px; font-weight:700; cursor:pointer; border:none; display:flex; align-items:center; justify-content:center; gap:0.25rem;">
                                            + Tambah ke Bio
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align:center; padding:2.5rem; color:#94a3b8; font-size:0.85rem;">
                        Belum ada produk White Label yang tersedia saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @include('partials.scan_menu_modal')
@endsection