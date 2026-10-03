@extends('layouts.app')
@section('content')

    <style>
        :root {
            --c-bg: #ffffff;
            --c-surface: #F8FAFC;
            --c-card: #ffffff;
            --c-border: #E2E8F0;
            --c-text: #0F172A;
            --c-muted: #64748B;
            --c-muted2: #94A3B8;
            --c-accent: #1eb349;
            --c-accent-h: #16a34a;
            --c-gradient: linear-gradient(135deg, #1eb349, #a5cf37);
            --c-wa: #25D366;
            --font: 'Montserrat', sans-serif;
            --ease: cubic-bezier(0.22, 1, 0.36, 1);
        }

        body {
            background: var(--c-bg);
        }

        /* TOP BAR */
        .sp-topbar {
            background: var(--c-surface);
            border-bottom: 1px solid var(--c-border);
            padding: 1rem 0;
            margin-top: 80px;
        }

        .sp-topbar-inner {
            max-width: 1320px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding: 0 1.5rem;
        }

        .sp-topbar-left h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--c-text);
            margin: 0 0 0.25rem;
            font-family: var(--font);
            letter-spacing: -0.01em;
        }

        .sp-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.75rem;
            font-family: var(--font);
            color: var(--c-muted);
        }

        .sp-breadcrumb a {
            color: var(--c-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .sp-breadcrumb a:hover {
            color: var(--c-accent);
        }

        .sp-breadcrumb-sep {
            opacity: 0.4;
        }

        .sp-breadcrumb-cur {
            color: var(--c-accent);
            font-weight: 600;
        }

        .sp-toolbar {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .sp-sort-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: var(--c-muted);
            font-family: var(--font);
        }

        .sp-sort-select {
            width: 100%;
            border: 1.5px solid var(--c-border);
            border-radius: 10px;
            padding: 0.55rem 2.25rem 0.55rem 0.875rem;
            font-size: 0.8125rem;
            font-weight: 500;
            font-family: var(--font);
            color: var(--c-text);
            background-color: var(--c-card);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%252364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 0.9rem;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            outline: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sp-sort-select:hover {
            border-color: var(--c-accent);
        }

        .sp-sort-select:focus {
            border-color: var(--c-accent);
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
        }

        .sp-view-btns {
            display: flex;
            gap: 0.375rem;
        }

        .sp-view-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid var(--c-border);
            border-radius: 8px;
            background: var(--c-card);
            color: var(--c-muted);
            cursor: pointer;
            transition: all 0.2s;
        }

        .sp-view-btn.active,
        .sp-view-btn:hover {
            border-color: var(--c-accent);
            color: var(--c-accent);
            background: rgba(30, 179, 73, 0.05);
        }

        .sp-result-count {
            font-size: 0.8125rem;
            color: var(--c-muted);
            font-family: var(--font);
            white-space: nowrap;
        }

        /* LAYOUT */
        .sp-layout {
            max-width: 1320px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
            align-items: start;
        }

        /* SIDEBAR */
        .sp-sidebar {
            position: sticky;
            top: calc(72px + 1rem);
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .sp-sidebar-card {
            background: var(--c-card);
            border: 1.5px solid var(--c-border);
            border-radius: 16px;
            overflow: hidden;
        }

        .sp-sidebar-head {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--c-border);
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: var(--c-text);
            font-family: var(--font);
        }

        .sp-sidebar-head-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--c-accent);
            flex-shrink: 0;
        }

        .sp-sidebar-body {
            padding: 1.25rem;
        }

        .sp-search-wrap {
            position: relative;
        }

        .sp-search-wrap svg {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--c-muted2);
            pointer-events: none;
        }

        .sp-search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1.5px solid var(--c-border);
            border-radius: 10px;
            font-size: 0.875rem;
            font-family: var(--font);
            color: var(--c-text);
            background: var(--c-surface);
            outline: none;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .sp-search-input:focus {
            border-color: var(--c-accent);
            background: #fff;
        }

        .sp-type-options {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sp-type-opt {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.625rem 0.875rem;
            border-radius: 10px;
            cursor: pointer;
            border: 1.5px solid var(--c-border);
            background: var(--c-surface);
            font-size: 0.875rem;
            font-family: var(--font);
            color: var(--c-text);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sp-type-opt:hover {
            border-color: var(--c-accent);
            color: var(--c-accent);
        }

        .sp-type-opt.active {
            border-color: var(--c-accent);
            background: rgba(30, 179, 73, 0.07);
            color: var(--c-accent);
        }

        .sp-type-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .sp-type-dot.produk {
            background: var(--c-accent);
        }

        .sp-type-dot.jasa {
            background: var(--c-wa);
        }

        .sp-type-dot.semua {
            background: var(--c-muted2);
        }

        .sp-price-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.625rem;
            margin-bottom: 1rem;
        }

        .sp-price-input {
            width: 100%;
            padding: 0.5rem 0.625rem;
            border: 1.5px solid var(--c-border);
            border-radius: 8px;
            font-size: 0.8125rem;
            font-family: var(--font);
            color: var(--c-text);
            background: var(--c-surface);
            outline: none;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        .sp-price-input:focus {
            border-color: var(--c-accent);
        }

        .sp-price-label {
            font-size: 0.7rem;
            color: var(--c-muted2);
            margin-bottom: 0.25rem;
            font-family: var(--font);
        }

        .sp-apply-btn {
            width: 100%;
            padding: 0.625rem;
            background: var(--c-accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: var(--font);
            font-weight: 700;
            font-size: 0.8125rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .sp-apply-btn:hover {
            background: var(--c-accent-h);
        }

        .sp-cat-list {
            display: flex;
            flex-direction: column;
        }

        .sp-cat-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0;
            border-bottom: 1px solid var(--c-border);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sp-cat-item:last-child {
            border-bottom: none;
        }

        .sp-cat-check {
            width: 18px;
            height: 18px;
            border: 2px solid var(--c-border);
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
            background: #fff;
        }

        .sp-cat-item.active .sp-cat-check,
        .sp-cat-item:hover .sp-cat-check {
            border-color: var(--c-accent);
            background: var(--c-accent);
        }

        .sp-cat-check svg {
            opacity: 0;
            transition: opacity 0.2s;
        }

        .sp-cat-item.active .sp-cat-check svg,
        .sp-cat-item:hover .sp-cat-check svg {
            opacity: 1;
        }

        .sp-cat-name {
            font-size: 0.8125rem;
            font-family: var(--font);
            color: var(--c-text);
            flex: 1;
            font-weight: 500;
            transition: color 0.2s;
        }

        .sp-cat-item.active .sp-cat-name,
        .sp-cat-item:hover .sp-cat-name {
            color: var(--c-accent);
            font-weight: 600;
        }

        .sp-cat-badge {
            font-size: 0.7rem;
            font-weight: 700;
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            color: var(--c-muted);
            padding: 0.125rem 0.5rem;
            border-radius: 20px;
            font-family: var(--font);
            transition: all 0.2s;
        }

        .sp-cat-item.active .sp-cat-badge,
        .sp-cat-item:hover .sp-cat-badge {
            background: rgba(30, 179, 73, 0.1);
            border-color: rgba(30, 179, 73, 0.3);
            color: var(--c-accent);
        }

        /* CATEGORY GROUP & SUBCATEGORY ANIMATED TREE */
        .sp-cat-group {
            border-bottom: 1px solid var(--c-border);
            transition: background 0.2s ease;
            border-radius: 8px;
        }
        .sp-cat-group:last-child {
            border-bottom: none;
        }
        .sp-cat-parent-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        .sp-cat-parent-row .sp-cat-item {
            flex: 1;
            border-bottom: none !important;
        }
        .sp-subcat-toggle-btn {
            background: transparent;
            border: none;
            padding: 6px 8px;
            cursor: pointer;
            color: var(--c-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
            margin-right: -4px;
        }
        .sp-subcat-toggle-btn:hover {
            background: rgba(30, 179, 73, 0.1);
            color: var(--c-accent);
        }
        .sp-chevron-icon {
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sp-cat-group.open .sp-chevron-icon {
            transform: rotate(180deg);
            color: var(--c-accent);
        }

        /* Subcategory Container Accordion & Hover Reveal */
        .sp-subcat-container {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.28s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
            opacity: 0;
            overflow: hidden;
        }
        @media (hover: hover) {
            .sp-cat-group:hover .sp-subcat-container {
                grid-template-rows: 1fr;
                opacity: 1;
            }
            .sp-cat-group:hover .sp-chevron-icon {
                transform: rotate(180deg);
                color: var(--c-accent);
            }
        }
        .sp-cat-group.open .sp-subcat-container {
            grid-template-rows: 1fr;
            opacity: 1;
        }

        .sp-subcat-list {
            min-height: 0;
            padding: 0.15rem 0 0.5rem 1.6rem;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            position: relative;
        }
        .sp-subcat-list::before {
            content: '';
            position: absolute;
            left: 0.8rem;
            top: 0.35rem;
            bottom: 0.6rem;
            width: 2px;
            background: var(--c-border);
            border-radius: 2px;
        }
        .sp-subcat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.6rem;
            font-size: 0.78rem;
            color: var(--c-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            position: relative;
            font-family: var(--font);
        }
        .sp-subcat-item:hover,
        .sp-subcat-item.active {
            background: rgba(30, 179, 73, 0.08);
            color: var(--c-accent);
            font-weight: 600;
            transform: translateX(3px);
        }
        .sp-subcat-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--c-muted2);
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .sp-subcat-item:hover .sp-subcat-dot,
        .sp-subcat-item.active .sp-subcat-dot {
            background: var(--c-accent);
            transform: scale(1.4);
            box-shadow: 0 0 6px rgba(30, 179, 73, 0.5);
        }
        .sp-subcat-name {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sp-subcat-badge {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--c-muted);
            background: #fff;
            border: 1px solid var(--c-border);
            padding: 0.1rem 0.4rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .sp-subcat-item.active .sp-subcat-badge,
        .sp-subcat-item:hover .sp-subcat-badge {
            border-color: var(--c-accent);
            color: var(--c-accent);
        }

        .sp-reset-link {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8rem;
            color: var(--c-muted);
            font-family: var(--font);
            text-decoration: none;
            margin-top: 0.5rem;
            transition: color 0.2s;
        }

        .sp-reset-link:hover {
            color: #EF4444;
        }

        /* ACTIVE CHIPS */
        .sp-active-filters {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .sp-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: rgba(30, 179, 73, 0.08);
            border: 1px solid rgba(30, 179, 73, 0.2);
            color: var(--c-accent);
            font-size: 0.8rem;
            font-weight: 600;
            font-family: var(--font);
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sp-filter-chip:hover {
            background: rgba(239, 68, 68, 0.08);
            border-color: rgba(239, 68, 68, 0.2);
            color: #EF4444;
        }

        .sp-filter-chip svg {
            width: 12px;
            height: 12px;
        }

        /* GRID */
        .sp-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }

        .sp-grid.list-view {
            grid-template-columns: 1fr;
        }

        /* CARD */
        .sp-card {
            background: var(--c-card);
            border: 1.5px solid var(--c-border);
            border-radius: 18px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.35s var(--ease);
            position: relative;
        }

        .sp-card:hover {
            border-color: var(--c-accent);
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(30, 179, 73, 0.08);
        }

        .sp-card-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            z-index: 2;
            font-size: 0.65rem;
            font-weight: 800;
            font-family: var(--font);
            letter-spacing: 0.02em;
            text-transform: uppercase;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .sp-card-badge.produk {
            background: rgba(255, 255, 255, 0.95);
            color: var(--c-accent);
            border: 1px solid rgba(30, 179, 73, 0.2);
        }

        .sp-card-badge.jasa {
            background: rgba(255, 255, 255, 0.95);
            color: var(--c-wa);
            border: 1px solid rgba(37, 211, 102, 0.2);
        }

        .sp-card-badge.diskon {
            background: #EF4444;
            color: #ffffff;
            border: none;
        }

        .sp-card-img {
            width: 100%;
            aspect-ratio: 1/1;
            overflow: hidden;
            background: var(--c-surface);
            position: relative;
            display: block;
            text-decoration: none;
        }

        .sp-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s var(--ease);
            display: block;
        }

        .sp-card:hover .sp-card-img img {
            transform: scale(1.06);
        }

        .sp-card-img-ph {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #E2E8F0, #F1F5F9);
            color: var(--c-muted2);
        }

        .sp-card-body {
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .sp-card-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--c-text);
            font-family: var(--font);
            line-height: 1.35;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .sp-card-footer {
            margin-top: auto;
        }

        .sp-card-price {
            display: flex;
            flex-direction: column;
        }

        .sp-card-price-old {
            font-size: 0.7rem;
            color: #94A3B8;
            text-decoration: line-through;
            font-family: var(--font);
            font-weight: 400;
            margin-bottom: 0.1rem;
        }

        .sp-card-price-main {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1eb349;
            font-family: var(--font);
        }

        .sp-card-price-jasa {
            font-size: 0.825rem;
            font-weight: 600;
            color: var(--c-text);
            font-family: var(--font);
        }

        /* 2-button row */
        .sp-card-meta-row {
            display: none;
        }

        .sp-grid.list-view .sp-card-meta-row {
            display: flex;
        }

        .sp-card-actions {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 0.5rem;
            margin-top: 0.75rem;
            align-items: center;
        }

        .sp-btn-main {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            background: var(--c-text);
            color: #fff;
            padding: 0.7rem 0.875rem;
            border-radius: 10px;
            border: none;
            font-family: var(--font);
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
            white-space: nowrap;
        }

        .sp-btn-main:hover {
            background: var(--c-accent);
            transform: translateY(-1px);
            color: #fff;
        }

        .sp-btn-main.wa {
            background: var(--c-wa);
        }

        .sp-btn-main.wa:hover {
            background: #1EBE5D;
        }

        .sp-btn-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1.5px solid var(--c-border);
            background: #fff;
            color: var(--c-text);
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
            flex-shrink: 0;
        }

        .sp-btn-icon:hover {
            border-color: var(--c-accent);
            color: var(--c-accent);
            background: rgba(30, 179, 73, 0.05);
        }

        .sp-btn-icon.icon-wa {
            border-color: rgba(37, 211, 102, 0.3);
            color: var(--c-wa);
            background: rgba(37, 211, 102, 0.06);
        }

        .sp-btn-icon.icon-wa:hover {
            background: rgba(37, 211, 102, 0.12);
        }

        /* LIST VIEW */
        .sp-grid.list-view {
            grid-template-columns: 1fr;
        }

        .sp-grid.list-view .sp-card {
            flex-direction: row;
            min-height: 180px;
        }

        .sp-grid.list-view .sp-card-img {
            width: 200px;
            min-height: 180px;
            aspect-ratio: auto;
            flex-shrink: 0;
        }

        .sp-grid.list-view .sp-card-body {
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .sp-grid.list-view .sp-card-name {
            font-size: 1.125rem;
        }

        .sp-grid.list-view .sp-card-desc {
            -webkit-line-clamp: 2;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .sp-grid.list-view .sp-card-meta-row {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }

        .sp-grid.list-view .sp-card-meta-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: var(--c-muted);
            font-family: var(--font);
        }

        .sp-grid.list-view .sp-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: auto;
        }

        .sp-grid.list-view .sp-card-price {
            margin-bottom: 0;
        }

        .sp-grid.list-view .sp-card-actions {
            grid-template-columns: auto auto;
            width: auto;
        }

        /* ════ TAB SWITCHER ════ */
        .sp-tab-switcher {
            display: flex;
            gap: 0.375rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--c-border);
            padding-bottom: 0.625rem;
        }

        .sp-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.5rem 1.1rem;
            border-radius: 8px;
            font-family: var(--font);
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            color: var(--c-muted);
            background: var(--c-surface);
            border: 1.5px solid transparent;
            transition: all 0.2s var(--ease);
            white-space: nowrap;
        }

        .sp-tab-btn:hover {
            color: var(--c-accent);
            background: rgba(30,179,73,0.06);
            border-color: rgba(30,179,73,0.25);
        }

        .sp-tab-btn.active {
            background: linear-gradient(135deg, #1eb349, #7db928);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(30,179,73,0.3);
        }

        /* ════ CREATOR GRID / LIST ════ */
        .sp-creator-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 1rem;
        }

        .sp-creator-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: inherit;
            transition: transform 0.25s var(--ease);
            cursor: pointer;
        }

        .sp-creator-card:hover {
            transform: translateY(-4px);
        }

        .sp-creator-avatar {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            border: 1.5px solid var(--c-border);
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: box-shadow 0.25s, border-color 0.25s;
        }

        .sp-creator-card:hover .sp-creator-avatar {
            border-color: var(--c-accent);
            box-shadow: 0 8px 24px rgba(30,179,73,0.15);
        }

        .sp-creator-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sp-creator-avatar-ph {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #F0FDF4, #DCFCE7);
            color: var(--c-accent);
            font-weight: 800;
            font-size: 1.5rem;
            font-family: var(--font);
        }

        .sp-creator-name {
            margin-top: 0.5rem;
            font-family: var(--font);
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--c-text);
            text-align: center;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sp-creator-cta {
            margin-top: 0.3rem;
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            background: linear-gradient(135deg, #1eb349, #7db928);
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.28rem 0.6rem;
            border-radius: 99px;
            box-shadow: 0 3px 10px rgba(30,179,73,0.25);
            font-family: var(--font);
            transition: box-shadow 0.2s;
        }

        .sp-creator-card:hover .sp-creator-cta {
            box-shadow: 0 5px 16px rgba(30,179,73,0.4);
        }

        /* Grid mode: .sp-creator-info visible, but sub hidden */
        .sp-creator-info {
            width: 100%;
        }

        .sp-creator-sub {
            display: none; /* hidden in grid, shown in list */
        }

        /* Creator LIST view */
        .sp-creator-grid.list-view {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .sp-creator-grid.list-view .sp-creator-card {
            flex-direction: row;
            align-items: center;
            gap: 0.875rem;
            background: var(--c-card);
            border: 1.5px solid var(--c-border);
            border-radius: 14px;
            padding: 0.75rem 1rem;
            transition: all 0.25s;
        }

        .sp-creator-grid.list-view .sp-creator-card:hover {
            transform: none;
            border-color: var(--c-accent);
            box-shadow: 0 4px 16px rgba(30,179,73,0.1);
        }

        .sp-creator-grid.list-view .sp-creator-avatar {
            width: 52px;
            aspect-ratio: 1/1;
            flex-shrink: 0;
            border-radius: 50%;
        }

        .sp-creator-grid.list-view .sp-creator-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .sp-creator-grid.list-view .sp-creator-name {
            margin-top: 0;
            text-align: left;
            font-size: 0.9rem;
        }

        .sp-creator-grid.list-view .sp-creator-sub {
            font-size: 0.75rem;
            color: var(--c-muted);
            font-family: var(--font);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sp-creator-grid.list-view .sp-creator-cta {
            margin-top: 0;
            flex-shrink: 0;
            font-size: 0.75rem;
            padding: 0.4rem 0.875rem;
        }

        /* ════ PAGINATION ════ */
        .sp-pagination { margin-top: 2.5rem; width: 100%; display: flex; justify-content: center; }
        .sp-pagination nav { display: flex; justify-content: center; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
        .sp-pagination .page-item .page-link,
        .sp-pagination a.page-link,
        .sp-pagination span.page-link,
        .sp-pagination nav a,
        .sp-pagination nav span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 38px; height: 38px; padding: 0 0.65rem; border-radius: 10px;
            border: 1.5px solid #E2E8F0;
            background: #ffffff; color: #0F172A;
            font-size: 0.85rem; font-weight: 700; font-family: 'Montserrat', sans-serif;
            transition: all 0.2s; text-decoration: none !important;
        }
        .sp-pagination .page-item.active .page-link,
        .sp-pagination nav span[aria-current="page"] span,
        .sp-pagination nav span[aria-current="page"],
        .sp-pagination a.page-link:hover,
        .sp-pagination nav a:hover {
            background: #1eb349; border-color: #1eb349; color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(30,179,73,0.3);
        }

        /* EMPTY */
        .sp-empty {
            text-align: center;
            padding: 5rem 2rem;
            grid-column: 1/-1;
        }

        .sp-empty-icon {
            width: 80px;
            height: 80px;
            background: var(--c-surface);
            border: 2px dashed var(--c-border);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--c-muted2);
        }

        .sp-empty h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--c-text);
            font-family: var(--font);
            margin: 0 0 0.5rem;
        }

        .sp-empty p {
            font-size: 0.9375rem;
            color: var(--c-muted);
            font-family: var(--font);
            margin: 0 0 1.5rem;
        }

        /* MODAL */
        .sp-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9998;
            background: rgba(15, 23, 42, 0.72);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sp-modal {
            background: var(--c-card);
            border-radius: 20px;
            width: 100%;
            max-width: 480px;
            padding: 2rem;
            position: relative;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        /* MOBILE FILTER BTN */
        .sp-mobile-filter-btn {
            display: none;
            align-items: center;
            gap: 0.5rem;
            background: var(--c-card);
            border: 1.5px solid var(--c-border);
            border-radius: 10px;
            padding: 0.625rem 1rem;
            font-family: var(--font);
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--c-text);
            cursor: pointer;
            transition: all 0.2s;
        }

        .sp-mobile-filter-btn:hover {
            border-color: var(--c-accent);
            color: var(--c-accent);
        }

        /* RESPONSIVE */
        @media(max-width:1100px) {
            .sp-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:900px) {
            .sp-layout {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 1.5rem 1rem 3rem;
            }

            .sp-sidebar {
                position: static;
                display: none;
            }

            .sp-sidebar.mobile-open {
                display: flex;
            }

            .sp-mobile-filter-btn {
                display: flex;
            }

            .sp-toolbar {
                width: 100%;
                justify-content: space-between;
            }

            .sp-topbar {
                margin-top: 0.5rem;
            }
        }

        @media(max-width:640px) {
            .sp-topbar {
                padding: 0.875rem 0;
                margin-top: 0.5rem;
            }

            .sp-topbar-inner {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.625rem;
                padding: 0 1rem;
            }

            .sp-toolbar {
                gap: 0.5rem;
            }

            /* MOBILE GRID VIEW (2 cols, compact) */
            .sp-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.625rem;
            }

            .sp-card-body {
                padding: 0.625rem;
            }

            .sp-card-name {
                font-size: 0.85rem;
                -webkit-line-clamp: 2;
            }

            .sp-card-price-main {
                font-size: 0.9rem;
            }

            .sp-card-badge {
                font-size: 0.55rem;
                padding: 0.15rem 0.35rem;
                top: 0.35rem;
                left: 0.35rem;
            }

            .sp-card-actions {
                grid-template-columns: 1fr auto;
                gap: 0.35rem;
                margin-top: 0.5rem;
            }

            .sp-btn-main {
                padding: 0.4rem 0.5rem;
                font-size: 0.7rem;
                gap: 0.25rem;
            }

            .sp-btn-main svg {
                width: 14px;
                height: 14px;
            }

            .sp-btn-icon {
                width: 32px;
                height: 32px;
            }

            .sp-btn-icon svg {
                width: 16px;
                height: 16px;
            }

            /* MOBILE LIST VIEW (1 col, horizontal) */
            .sp-grid.list-view {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .sp-grid.list-view .sp-card {
                flex-direction: row;
                min-height: 90px;
                border-radius: 12px;
            }

            .sp-grid.list-view .sp-card-img {
                width: 90px;
                min-height: 90px;
                aspect-ratio: 1/1;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0.25rem;
            }

            .sp-grid.list-view .sp-card-img img {
                object-fit: contain;
                width: 100%;
                height: 100%;
                border-radius: 8px;
            }

            .sp-grid.list-view .sp-card-body {
                padding: 0.5rem;
                flex: 1;
            }

            .sp-grid.list-view .sp-card-name {
                font-size: 0.85rem;
                margin-bottom: 0.2rem;
            }

            .sp-grid.list-view .sp-card-desc {
                display: none;
            }

            /* hide desc to save space */
            .sp-grid.list-view .sp-card-meta-row {
                gap: 0.5rem;
                margin-bottom: 0.4rem;
                flex-wrap: wrap;
            }

            .sp-grid.list-view .sp-card-meta-item {
                font-size: 0.68rem;
                gap: 0.25rem;
            }

            .sp-grid.list-view .sp-card-footer {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 0.4rem;
            }

            .sp-grid.list-view .sp-card-actions {
                grid-template-columns: auto auto;
                margin-top: 0;
            }

            .sp-grid.list-view .sp-btn-main {
                padding: 0.35rem 0.6rem;
                font-size: 0.65rem;
            }

            .sp-grid.list-view .sp-btn-icon {
                width: 28px;
                height: 28px;
            }

            /* Mobile creator grid: 2 columns */
            .sp-creator-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.75rem;
            }

            .sp-creator-grid.list-view {
                grid-template-columns: 1fr;
            }

            .sp-creator-grid.list-view .sp-creator-avatar {
                width: 44px;
            }

            .sp-creator-grid.list-view .sp-creator-name {
                font-size: 0.82rem;
            }

            .sp-creator-grid.list-view .sp-creator-cta {
                font-size: 0.68rem;
                padding: 0.3rem 0.6rem;
            }

            /* Mobile tab switcher */
            .sp-tab-btn {
                padding: 0.4rem 0.75rem;
                font-size: 0.78rem;
            }

        }
        
        /* Wishlist Modal */
        .sp-modal-backdrop {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center;
            z-index: 9999; opacity: 0; transition: opacity 0.3s;
        }
        .sp-modal {
            background: #fff; width: 90%; max-width: 400px;
            border-radius: 20px; padding: 2rem; position: relative;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
    </style>

    {{-- TOP BAR --}}
    <div class="sp-topbar">
        <div class="sp-topbar-inner">
            <div class="sp-topbar-left">
                <h1>Cari Produk</h1>
                <nav class="sp-breadcrumb" aria-label="breadcrumb">
                    <a href="{{ route_locale('home') }}">Beranda</a>
                    <span class="sp-breadcrumb-sep">/</span>
                    @if(request()->filled('q'))
                        <span class="sp-breadcrumb-cur">Hasil: "{{ request('q') }}"</span>
                    @elseif(request('type') === 'jasa')
                        <span class="sp-breadcrumb-cur">Layanan Jasa</span>
                    @elseif(request('type') === 'produk')
                        <span class="sp-breadcrumb-cur">Produk</span>
                    @else
                        <span class="sp-breadcrumb-cur">Semua Produk &amp; Layanan</span>
                    @endif
                </nav>
            </div>

            <div class="sp-toolbar">
                <button class="sp-mobile-filter-btn" onclick="toggleMobileFilter()">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="8" y1="6" x2="21" y2="6" />
                        <line x1="8" y1="12" x2="21" y2="12" />
                        <line x1="8" y1="18" x2="21" y2="18" />
                        <line x1="3" y1="6" x2="3.01" y2="6" />
                        <line x1="3" y1="12" x2="3.01" y2="12" />
                        <line x1="3" y1="18" x2="3.01" y2="18" />
                    </svg>
                    Filter
                </button>

                <div class="sp-sort-wrap">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="16" y1="4" x2="8" y2="4" />
                        <line x1="20" y1="9" x2="4" y2="9" />
                        <line x1="13" y1="14" x2="4" y2="14" />
                    </svg>
                </div>

                <div class="sp-result-count">{{ $services->count() }} produk</div>

                <div class="sp-view-btns">
                    <button class="sp-view-btn active" id="btnGrid" onclick="setView('grid')" title="Grid">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="7" height="7" rx="1" />
                        </svg>
                    </button>
                    <button class="sp-view-btn" id="btnList" onclick="setView('list')" title="List">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <line x1="8" y1="6" x2="21" y2="6" />
                            <line x1="8" y1="12" x2="21" y2="12" />
                            <line x1="8" y1="18" x2="21" y2="18" />
                            <line x1="3" y1="6" x2="3.01" y2="6" />
                            <line x1="3" y1="12" x2="3.01" y2="12" />
                            <line x1="3" y1="18" x2="3.01" y2="18" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        $activeCats = [];
        if (request()->filled('category')) {
            $raw = request('category');
            $activeCats = is_array($raw) ? $raw : explode(',', $raw);
            $activeCats = array_filter($activeCats);
        }
    @endphp

    {{-- MAIN LAYOUT --}}
    <div class="sp-layout">

        {{-- SIDEBAR --}}
        {{-- SIDEBAR --}}
        <aside class="sp-sidebar" id="spSidebar">

            {{-- Kategori (Paling Atas) --}}
            @if($categories->count() > 0)
                <div class="sp-sidebar-card">
                    <div class="sp-sidebar-head"><span class="sp-sidebar-head-dot"></span>Kategori</div>
                    <div class="sp-sidebar-body" style="padding:0.5rem 1.25rem;">
                        <div class="sp-cat-list">
                            @foreach($categories as $cat)
                                @php
                                    $isCatA = in_array($cat->slug, $activeCats);
                                    $hasSubs = $cat->subCategories && $cat->subCategories->count() > 0;
                                    $activeSubSlug = request('subcategory') ?? request('sub_category');
                                    $hasActiveSub = $hasSubs && $cat->subCategories->contains('slug', $activeSubSlug);

                                    // Single-select: klik kategori yang sudah aktif → hapus filter; klik yang lain → ganti
                                    $catQ = $isCatA
                                        ? request()->except(['category', 'subcategory', 'sub_category'])
                                        : array_merge(request()->except(['category', 'subcategory', 'sub_category']), ['category' => $cat->slug]);
                                @endphp
                                <div class="sp-cat-group {{ ($isCatA || $hasActiveSub) ? 'open' : '' }}">
                                    <div class="sp-cat-parent-row">
                                        <a href="{{ route_locale('products') }}?{{ http_build_query($catQ) }}"
                                            class="sp-cat-item {{ $isCatA ? 'active' : '' }}">
                                            <div class="sp-cat-check">
                                                <svg width="10" height="10" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="sp-cat-name">{{ $cat->name }}</span>
                                            <span class="sp-cat-badge">{{ $cat->services_count ?? $cat->products_count }}</span>
                                        </a>
                                        @if($hasSubs)
                                            <button type="button" class="sp-subcat-toggle-btn" aria-label="Toggle Sub Kategori" onclick="event.preventDefault(); event.stopPropagation(); this.closest('.sp-cat-group').classList.toggle('open');">
                                                <svg class="sp-chevron-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    @if($hasSubs)
                                        <div class="sp-subcat-container">
                                            <div class="sp-subcat-list">
                                                @foreach($cat->subCategories as $sub)
                                                    @php
                                                        $isSubActive = $activeSubSlug === $sub->slug;
                                                        $subQ = $isSubActive
                                                            ? request()->except(['subcategory', 'sub_category'])
                                                            : array_merge(request()->except(['subcategory', 'sub_category']), ['category' => $cat->slug, 'subcategory' => $sub->slug]);
                                                    @endphp
                                                    <a href="{{ route_locale('products') }}?{{ http_build_query($subQ) }}"
                                                       class="sp-subcat-item {{ $isSubActive ? 'active' : '' }}">
                                                        <span class="sp-subcat-dot"></span>
                                                        <span class="sp-subcat-name">{{ $sub->name }}</span>
                                                        @if(isset($sub->products_count) && $sub->products_count > 0)
                                                            <span class="sp-subcat-badge">{{ $sub->products_count }}</span>
                                                        @endif
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Filter Produk --}}
            <div class="sp-sidebar-card">
                <div class="sp-sidebar-head"><span class="sp-sidebar-head-dot"></span>Filter Produk</div>
                <div class="sp-sidebar-body">
                    <form method="GET" action="{{ route_locale('products') }}">
                        @foreach(request()->except(['sort', 'type', 'price_min', 'price_max', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ is_array($v) ? implode(',', $v) : $v }}">
                        @endforeach

                        {{-- Urutkan / Sort --}}
                        <div style="margin-bottom:1.25rem;">
                            <div style="font-size:0.75rem;font-weight:700;color:var(--c-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;font-family:var(--font);">Urutkan Berdasarkan</div>
                            <select class="sp-sort-select" name="sort" onchange="this.form.submit()" style="width:100%;box-sizing:border-box;">
                                <option value="default" {{ request('sort', 'default') === 'default' ? 'selected' : '' }}>Default</option>
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="name_az" {{ request('sort') === 'name_az' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                        </div>

                        {{-- Tipe Produk --}}
                        <div style="margin-bottom:1.25rem;">
                            <div style="font-size:0.75rem;font-weight:700;color:var(--c-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;font-family:var(--font);">Tipe Produk</div>
                            <div class="sp-type-options">
                                @php $curType = request('type', 'semua'); @endphp
                                <a href="{{ route_locale('products') }}?{{ http_build_query(array_merge(request()->except('type'), ['type' => 'semua'])) }}"
                                    class="sp-type-opt {{ $curType === 'semua' ? 'active' : '' }}">
                                    <span class="sp-type-dot semua"></span> Semua
                                </a>
                                <a href="{{ route_locale('products') }}?{{ http_build_query(array_merge(request()->except('type'), ['type' => 'produk'])) }}"
                                    class="sp-type-opt {{ $curType === 'produk' ? 'active' : '' }}">
                                    <span class="sp-type-dot produk"></span> Produk Digital
                                </a>
                                <a href="{{ route_locale('products') }}?{{ http_build_query(array_merge(request()->except('type'), ['type' => 'jasa'])) }}"
                                    class="sp-type-opt {{ $curType === 'jasa' ? 'active' : '' }}">
                                    <span class="sp-type-dot jasa"></span> Jasa Profesional
                                </a>
                            </div>
                        </div>

                        {{-- Filter Harga --}}
                        <div>
                            <div style="font-size:0.75rem;font-weight:700;color:var(--c-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;font-family:var(--font);">Rentang Harga</div>
                            <div class="sp-price-inputs">
                                <div>
                                    <div class="sp-price-label">Min (Rp)</div>
                                    <input type="number" name="price_min" class="sp-price-input" placeholder="0" value="{{ request('price_min') }}">
                                </div>
                                <div>
                                    <div class="sp-price-label">Max (Rp)</div>
                                    <input type="number" name="price_max" class="sp-price-input" placeholder="Tak terbatas" value="{{ request('price_max') }}">
                                </div>
                            </div>
                            <button type="submit" class="sp-apply-btn">Terapkan Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(request()->hasAny(['q', 'category', 'subcategory', 'sub_category', 'type', 'price_min', 'price_max', 'sort']))
                <a href="{{ route_locale('products') }}" class="sp-reset-link">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="23 4 23 10 17 10" />
                        <path d="M20.49 15a9 9 0 11-2.12-9.36L23 10" />
                    </svg>
                    Reset semua filter
                </a>
            @endif
        </aside>

        {{-- CONTENT --}}
        <div class="sp-content">

            {{-- Active Filter Chips --}}
            @php 
                $activeSubSlug = request('subcategory') ?? request('sub_category');
                $hasF = request()->hasAny(['q', 'category']) || !empty($activeSubSlug); 
            @endphp
            @if($hasF)
                <div class="sp-active-filters">
                    <span style="font-size:0.8rem;color:var(--c-muted);font-family:var(--font);font-weight:600;">Filter:</span>
                    @if(request()->filled('q'))
                        <a href="{{ route_locale('products') }}?{{ http_build_query(request()->except('q')) }}"
                            class="sp-filter-chip">
                            "{{ request('q') }}" <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </a>
                    @endif

                    @foreach($activeCats as $cs)
                        @php $cObj = $categories->firstWhere('slug', $cs); @endphp
                        @if($cObj)
                            @php $rem = array_values(array_diff($activeCats, [$cs])); @endphp
                            <a href="{{ route_locale('products') }}?{{ http_build_query(array_merge(request()->except(['category', 'subcategory', 'sub_category']), $rem ? ['category' => implode(',', $rem)] : [])) }}"
                                class="sp-filter-chip">
                                {{ $cObj->name }} <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </a>
                        @endif
                    @endforeach

                    @if(!empty($activeSubSlug))
                        @php
                            $subObj = null;
                            foreach($categories as $c) {
                                if($c->subCategories && $sFound = $c->subCategories->firstWhere('slug', $activeSubSlug)) {
                                    $subObj = $sFound;
                                    break;
                                }
                            }
                        @endphp
                        @if($subObj)
                            <a href="{{ route_locale('products') }}?{{ http_build_query(request()->except(['subcategory', 'sub_category'])) }}"
                                class="sp-filter-chip">
                                Sub: {{ $subObj->name }} <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </a>
                        @endif
                    @endif

                </div>
            @endif



            {{-- Tab Switcher: Semua Produk vs Daftar Creator --}}
            <div class="sp-tab-switcher">
                <a href="{{ route_locale('products') }}"
                   class="sp-tab-btn {{ request('tab') !== 'creators' ? 'active' : '' }}">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Semua Produk
                </a>
                <a href="{{ route_locale('products') }}?tab=creators"
                   class="sp-tab-btn {{ request('tab') === 'creators' ? 'active' : '' }}">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0-3-3.87"/></svg>
                    Daftar Creator
                </a>
            </div>

            @if(request('tab') === 'creators')
                <div style="margin-bottom: 2.5rem;">
                    {{-- Creator Tab Header --}}
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; flex-wrap:wrap; gap:0.5rem;">
                        <div style="font-size: 1rem; font-weight: 800; color: var(--c-text); font-family: var(--font);">
                            Semua Creator Terverifikasi
                            <span style="font-size:0.82rem; font-weight:600; color:var(--c-muted); margin-left:0.4rem;">({{ $allCreators->total() }})</span>
                        </div>
                        {{-- View Toggle for Creators --}}
                        <div class="sp-view-btns">
                            <button class="sp-view-btn active" id="btnCreatorGrid" onclick="setCreatorView('grid')" title="Grid">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                            </button>
                            <button class="sp-view-btn" id="btnCreatorList" onclick="setCreatorView('list')" title="List">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            </button>
                        </div>
                    </div>

                    <div id="spCreatorGrid" class="sp-creator-grid">
                        @foreach($allCreators as $creatorUser)
                            @php
                                $cp = $creatorUser->creatorProfile;
                                $cName = $cp?->store_name ?: ($creatorUser->name ?: 'Creator');
                                $cStoreSlug = $cp?->store_slug ?: $creatorUser->username;
                                $cDesc = $cp?->store_description ? Str::limit($cp->store_description, 50) : 'Kreator Digital Terverifikasi';
                                $cProductCount = $creatorUser->products_count ?? 0;

                                $cAvatar = null;
                                if (!empty($cp?->avatar)) {
                                    $cAvatar = asset('storage/' . $cp->avatar);
                                } elseif (!empty($creatorUser->avatar)) {
                                    $cAvatar = Str::startsWith($creatorUser->avatar, 'http') ? $creatorUser->avatar : asset('storage/' . $creatorUser->avatar);
                                } elseif (!empty($cp?->bio_config['avatar'])) {
                                    $cAvatar = asset('storage/' . $cp->bio_config['avatar']);
                                }

                                $isStoreActive = $cp ? $cp->isStoreActive() : false;

                                if ($cStoreSlug) {
                                    $targetUrl = $isStoreActive ? route_locale('store.show', $cStoreSlug) : url('/' . $cStoreSlug);
                                } else {
                                    $targetUrl = route_locale('products');
                                }
                            @endphp
                            <a href="{{ $targetUrl }}" class="sp-creator-card" title="{{ $cName }}">
                                <div class="sp-creator-avatar">
                                    @if($cAvatar)
                                        <img src="{{ $cAvatar }}" alt="{{ $cName }}" loading="lazy">
                                    @else
                                        <div class="sp-creator-avatar-ph">{{ strtoupper(substr($cName, 0, 1)) }}</div>
                                    @endif
                                </div>
                                {{-- Info wrapper (shown in list view) --}}
                                <div class="sp-creator-info">
                                    <div class="sp-creator-name">{{ $cName }}</div>
                                    <div class="sp-creator-sub">{{ $cDesc }}</div>
                                </div>
                                <span class="sp-creator-cta">
                                    Kunjungi
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7l7 7-7 7"/></svg>
                                </span>
                            </a>
                        @endforeach
                    </div>

                    <div style="margin-top: 2rem;">
                        {{ $allCreators->links() }}
                    </div>
                </div>
            @endif

            {{-- Creator Search Results --}}
            @if(request()->filled('q') && isset($foundCreators) && $foundCreators->count() > 0)
                <div style="margin-bottom: 2rem;">
                    <div style="font-size: 0.78rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.75rem;">
                        Creators Ditemukan ({{ $foundCreators->count() }})
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($foundCreators as $creator)
                            <div style="background: #ffffff; border: 1.5px solid #E2E8F0; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                {{-- Creator Row Header --}}
                                <div style="display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 1rem; justify-content: space-between; flex-wrap: nowrap;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0; flex: 1;">
                                        {{-- Avatar --}}
                                        <div style="width: 44px; height: 44px; border-radius: 50%; border: 2px solid #1eb349; flex-shrink: 0; background: #f0fdf4; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: #1eb349; overflow: hidden;">
                                            @if($creator->user && $creator->user->avatar)
                                                <img src="{{ asset('storage/' . $creator->user->avatar) }}" alt="{{ $creator->store_name }}" style="width:100%;height:100%;object-fit:cover;">
                                            @elseif($creator->avatar)
                                                <img src="{{ asset('storage/' . $creator->avatar) }}" alt="{{ $creator->store_name }}" style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                {{ strtoupper(substr($creator->store_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        {{-- Info --}}
                                        <div style="min-width: 0; flex: 1;">
                                            <div style="font-size: 0.95rem; font-weight: 800; color: #0F172A; font-family: var(--font, 'Montserrat', sans-serif); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $creator->store_name }}</div>
                                            <div style="font-size: 0.75rem; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;">{{ Str::limit($creator->store_description ?: 'Kreator Digital Terverifikasi', 40) }}</div>
                                            <span style="display: inline-block; margin-top: 0.2rem; background: rgba(30,179,73,0.1); color: #1eb349; padding: 0.1rem 0.5rem; border-radius: 5px; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.02em;">Official Creator</span>
                                        </div>
                                    </div>
                                    {{-- CTA Button --}}
                                    <a href="{{ route('store.show', $creator->store_slug) }}"
                                        style="flex-shrink: 0; display: inline-flex; align-items: center; gap: 0.35rem; background: linear-gradient(135deg, #1eb349, #a5cf37); color: #ffffff; padding: 0.5rem 0.95rem; border-radius: 999px; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 0.78rem; text-decoration: none; white-space: nowrap; box-shadow: 0 4px 14px rgba(30,179,73,0.35); transition: transform 0.2s, box-shadow 0.2s;"
                                        onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(30,179,73,0.45)';"
                                        onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 14px rgba(30,179,73,0.35)';">
                                        Kunjungi
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7l7 7-7 7"/></svg>
                                    </a>
                                </div>

                                {{-- Products Strip --}}
                                @if($creator->user && $creator->user->products && $creator->user->products->count() > 0)
                                <div style="border-top: 1px solid #F1F5F9; padding: 0.65rem 1rem 0.8rem;">
                                    <div style="font-size: 0.7rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Produk dari {{ $creator->store_name }}</div>
                                    <div style="display: flex; gap: 0.65rem; overflow-x: auto; scrollbar-width: none; -webkit-overflow-scrolling: touch; padding-bottom: 0.25rem;">
                                        @foreach($creator->user->products->take(6) as $cProd)
                                            <a href="{{ route_locale('products.show', $cProd->slug) }}"
                                                style="flex: 0 0 110px; text-decoration: none; color: inherit;">
                                                <div style="width: 110px; aspect-ratio: 1/1; border-radius: 10px; overflow: hidden; border: 1px solid #E2E8F0; background: #F8FAFC; position: relative; margin-bottom: 0.4rem;">
                                                    @if($cProd->sale_price > 0 && $cProd->sale_price < $cProd->price)
                                                        <div style="position: absolute; top: 0.3rem; left: 0.3rem; background: #EF4444; color: #fff; font-size: 0.6rem; font-weight: 700; padding: 0.1rem 0.35rem; border-radius: 4px;">Diskon</div>
                                                    @endif
                                                    @if($cProd->image)
                                                        <img src="{{ asset('storage/' . $cProd->image) }}" alt="{{ $cProd->name }}" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
                                                    @else
                                                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#CBD5E1;">
                                                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div style="font-size: 0.72rem; font-weight: 700; color: #0F172A; font-family: var(--font, 'Montserrat', sans-serif); line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 0.2rem;">{{ $cProd->name }}</div>
                                                <div style="font-size: 0.78rem; font-weight: 800; color: #16a34a; font-family: var(--font, 'Montserrat', sans-serif);">
                                                    @if($cProd->sale_price > 0 && $cProd->sale_price < $cProd->price)
                                                        Rp {{ number_format($cProd->sale_price, 0, ',', '.') }}
                                                    @elseif($cProd->price > 0)
                                                        Rp {{ number_format($cProd->price, 0, ',', '.') }}
                                                    @else
                                                        GRATIS
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(request('tab') !== 'creators')
            {{-- Grid --}}
            <div id="spGrid" class="sp-grid">
                @include('components.coming-soon-inline')

                @if($services->count() > 0)
                    @foreach($services as $sv)
                        @php
                            $svImg        = $sv->image ? asset('storage/'.$sv->image) : asset('images/buyle-og.png');
                            $svPrice      = $sv->sale_price > 0 && $sv->sale_price < $sv->price ? $sv->sale_price : $sv->price;
                            $svOrigPrice  = $sv->sale_price > 0 && $sv->sale_price < $sv->price ? $sv->price : null;
                            $svHasDiscount = !empty($svOrigPrice) && $svOrigPrice > $svPrice;
                            $svDiscountPct = $svHasDiscount ? round((($svOrigPrice - $svPrice) / $svOrigPrice) * 100) : 0;
                            $svSeller     = optional(optional($sv->seller)->creatorProfile)->store_name ?? optional($sv->seller)->name ?? 'Kreator';
                            $svAvatar     = optional(optional($sv->seller)->creatorProfile)->avatar ?? optional($sv->seller)->avatar ?? null;
                            $svRatingVal  = ($sv->rating && $sv->rating > 0) ? $sv->rating : (($sv->reviews_avg_rating && $sv->reviews_avg_rating > 0) ? $sv->reviews_avg_rating : null);
                            $svRating     = $svRatingVal ? number_format($svRatingVal, 1) : null;
                        @endphp
                        <a href="{{ route_locale('products.show', $sv->slug) }}"
                            style="text-decoration: none; color: inherit; display: flex; flex-direction: column;"
                            class="sp-card article-card-swipe-item">
                            <div style="background: #ffffff; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.75rem; display: flex; flex-direction: column; height: 100%; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.02);"
                                class="article-card-box sp-card-inner">

                                {{-- Image Wrap 1:1 Square (Matching Image 2a) --}}
                                <div style="position: relative; width: 100%; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; background: #F1F5F9;" class="sp-card-img-wrap">
                                    <img src="{{ $svImg }}" alt="{{ $sv->name }}"
                                        style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease;"
                                        class="article-banner-img" loading="lazy">

                                    {{-- Discount Badge Top-Left (-XX%) --}}
                                    @if($svHasDiscount)
                                        <div style="position: absolute; top: 8px; left: 8px; background: #EF4444; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 8px; font-family: 'Montserrat', sans-serif; z-index: 2; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);">
                                            -{{ $svDiscountPct }}%
                                        </div>
                                    @endif
                                </div>

                                {{-- Card Body --}}
                                <div style="padding: 0.85rem 0.25rem 0.25rem; display: flex; flex-direction: column; flex: 1; justify-content: space-between;" class="sp-card-body-wrap">
                                    <div>
                                        {{-- Rating & Verified Badge --}}
                                        <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; font-weight: 700; color: #1E293B; margin-bottom: 0.35rem;">
                                            @if($svRating)
                                            <div style="display: flex; align-items: center; gap: 0.3rem;">
                                                <span style="color: #F59E0B; font-size: 0.95rem;">★</span>
                                                <span style="font-family: 'Montserrat', sans-serif;">{{ $svRating }}</span>
                                            </div>
                                            @else
                                            <div></div>
                                            @endif
                                            <div style="display: inline-flex; align-items: center; gap: 0.25rem; color: #0D9488; font-size: 0.78rem; font-weight: 700;">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>
                                                Verified
                                            </div>
                                        </div>

                                        {{-- Title --}}
                                        <h3 style="font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 800; color: #0F172A; margin: 0 0 0.4rem; line-height: 1.35; height: 2.7em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                            {{ $sv->name }}
                                        </h3>
                                    </div>

                                    {{-- Footer Separator & Price CTA --}}
                                    <div>
                                        <div style="border-top: 1px solid #F1F5F9; margin: 0.65rem 0 0.75rem;"></div>
                                        <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 0.5rem;">
                                            <div>
                                                @if($svHasDiscount)
                                                    <span style="font-size: 0.72rem; color: #94A3B8; text-decoration: line-through; display: block; font-weight: 500;">
                                                        Rp {{ number_format($svOrigPrice, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                <span style="font-family: 'Montserrat', sans-serif; font-size: 1.05rem; font-weight: 900; color: #16a34a;">
                                                    @if($svPrice > 0)
                                                        Rp {{ number_format($svPrice, 0, ',', '.') }}
                                                    @else
                                                        <span style="color: #1eb349;">GRATIS</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <span style="background: linear-gradient(135deg, #1eb349, #7db928); color: #ffffff; padding: 0.45rem 1.1rem; border-radius: 99px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3); flex-shrink: 0;" class="retarget-btn-cta">
                                                Lihat <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7l7 7-7 7" /></svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div style="grid-column: 1/-1; text-align:center; padding: 4rem 1rem; color: #64748B;">
                        <svg width="64" height="64" fill="none" stroke="#CBD5E1" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 1rem; display:block;">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                        <p style="font-size: 1rem; font-weight: 700; color: #334155; margin: 0 0 0.4rem;">Produk tidak ditemukan</p>
                        <p style="font-size: 0.85rem; margin: 0 0 1.5rem;">Coba kata kunci lain atau hapus filter yang aktif</p>
                        <a href="{{ route_locale('products') }}" style="display:inline-flex;align-items:center;gap:0.4rem;background:#1eb349;color:#fff;padding:0.6rem 1.5rem;border-radius:10px;font-weight:700;text-decoration:none;font-size:0.875rem;">
                            Lihat Semua Produk
                        </a>
                    </div>
                @endif
            </div>

            {{-- Mobile-Friendly Pagination --}}
            @if(isset($services) && method_exists($services, 'hasPages') && $services->hasPages())
                <div class="sp-pagination" style="margin-top: 2.5rem; width: 100%; display: flex; justify-content: center;">
                    {{ $services->links() }}
                </div>
            @endif
            @endif
        </div>
    </div>

    </div>

    {{-- Wishlist Login Modal --}}
    <div id="wishlistModal" class="sp-modal-backdrop" onclick="if(event.target === this) closeWishlistModal()">
        <div class="sp-modal" style="text-align:center;">
            <button type="button" onclick="closeWishlistModal()"
                style="position:absolute;top:1rem;right:1rem;background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--c-muted);">&times;</button>
            <div
                style="width:64px;height:64px;border-radius:50%;background:#FEE2E2;color:#EF4444;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <svg width="32" height="32" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
            </div>
            <h3 style="font-size:1.5rem;font-weight:800;color:var(--c-text);font-family:var(--font);margin:0 0 0.75rem;">
                Yukk Buat Akun Dulu!</h3>
            <p style="font-size:0.95rem;color:var(--c-muted);font-family:var(--font);line-height:1.6;margin:0 0 1.5rem;">
                Agar produk favoritmu bisa tersimpan aman di Wishlist, silakan login atau buat akun baru ya! Gratis dan
                cepat kok.
            </p>
            <div style="display:flex;gap:1rem;justify-content:center;">
                <a href="{{ route('login') }}" class="sp-btn-main" style="width:140px;">Login</a>
                <a href="{{ route('register') }}" class="sp-btn-main"
                    style="width:140px;background:#F1F5F9;color:#0F172A;">Daftar</a>
            </div>
        </div>
    </div>

    <script>
        function setView(m) {
            var g = document.getElementById('spGrid'),
                b1 = document.getElementById('btnGrid'),
                b2 = document.getElementById('btnList');
            if (!g) return;
            if (m === 'list') {
                g.classList.add('list-view');
                b2 && b2.classList.add('active');
                b1 && b1.classList.remove('active');
                localStorage.setItem('sp_view', 'list');
            } else {
                g.classList.remove('list-view');
                b1 && b1.classList.add('active');
                b2 && b2.classList.remove('active');
                localStorage.setItem('sp_view', 'grid');
            }
        }
        function setCreatorView(m) {
            var g = document.getElementById('spCreatorGrid'),
                b1 = document.getElementById('btnCreatorGrid'),
                b2 = document.getElementById('btnCreatorList');
            if (!g) return;
            if (m === 'list') {
                g.classList.add('list-view');
                b2 && b2.classList.add('active');
                b1 && b1.classList.remove('active');
                localStorage.setItem('sp_creator_view', 'list');
            } else {
                g.classList.remove('list-view');
                b1 && b1.classList.add('active');
                b2 && b2.classList.remove('active');
                localStorage.setItem('sp_creator_view', 'grid');
            }
        }
        (function () {
            if (localStorage.getItem('sp_view') === 'list') setView('list');
            if (localStorage.getItem('sp_creator_view') === 'list') setCreatorView('list');
        })();
        function toggleMobileFilter() { document.getElementById('spSidebar').classList.toggle('mobile-open'); }

        function openWishlistModal() {
            var wModal = document.getElementById('wishlistModal');
            wModal.style.display = 'flex';
            void wModal.offsetWidth;
            wModal.style.opacity = '1';
        }

        function closeWishlistModal() {
            var wModal = document.getElementById('wishlistModal');
            wModal.style.opacity = '0';
            setTimeout(function () { wModal.style.display = 'none'; }, 300);
        }

        document.querySelector('.sp-search-input').addEventListener('keydown', function (e) { if (e.key === 'Enter') document.getElementById('searchForm').submit(); });
    </script>
@endsection