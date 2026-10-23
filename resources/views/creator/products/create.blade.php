@extends('creator.layout')

@section('title', 'Tambah Produk Digital')
@section('page_title', 'Tambah Produk')
@section('breadcrumb', 'Katalog › Produk › Tambah Baru')

@section('styles')
    <style>
        /* Reusing some layout from profile */
        .prof-layout {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .prof-sidebar {
            width: 250px;
            flex-shrink: 0;
            background: #fff;
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            position: sticky;
            top: 1.5rem;
        }

        .prof-content {
            flex: 1;
            min-width: 0;
        }

        .tab-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 1rem;
            border: none;
            background: transparent;
            color: #64748B;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s;
            margin-bottom: 0.25rem;
        }

        .tab-btn:hover {
            background: #f8fafc;
            color: #1eb349;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.2);
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
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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
            gap: 0.6rem;
            font-size: 0.95rem;
            font-weight: 800;
            color: #0b120c;
        }

        .form-body {
            padding: 1.5rem 1.75rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            margin-bottom: 1.25rem;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group.full {
            grid-column: 1/-1;
            width: 100%;
            box-sizing: border-box;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #374151;
            display: block;
        }

        .form-label span {
            color: #ef4444;
        }

        .form-input {
            width: 100%;
            box-sizing: border-box;
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
            display: block;
        }

        .form-input:focus {
            border-color: #1eb349;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
        }

        select.form-input {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
            padding-right: 2.75rem;
            cursor: pointer;
            font-weight: 600;
            color: #0f172a;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        select.form-input:hover {
            border-color: #1eb349;
            box-shadow: 0 3px 10px rgba(30, 179, 73, 0.08);
        }

        select.form-input:focus {
            border-color: #1eb349;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(30, 179, 73, 0.15);
        }

        select.form-input option {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            color: #0f172a;
            background-color: #ffffff;
            padding: 10px 14px;
        }

        select.form-input optgroup {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 0.78rem;
            color: #1eb349;
            background-color: #f8fafc;
            padding: 8px 10px;
            letter-spacing: 0.03em;
        }

        textarea.form-input {
            height: auto;
            padding: 0.75rem 1rem;
            resize: vertical;
            min-height: 90px;
            width: 100%;
            box-sizing: border-box;
        }

        .form-hint {
            font-size: 0.72rem;
            color: #94A3B8;
            display: block;
            margin-top: 0.25rem;
        }

        .form-error {
            font-size: 0.72rem;
            color: #ef4444;
        }

        /* Toggle Switch */
        .toggle-slider-s {
            position: absolute;
            inset: 0;
            background: #cbd5e1;
            border-radius: 24px;
            transition: 0.3s;
        }

        .toggle-slider-s::before {
            content: '';
            position: absolute;
            left: 3px;
            top: 3px;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            transition: 0.3s;
        }

        input:checked+.toggle-slider-s {
            background: #1eb349;
        }

        input:checked+.toggle-slider-s::before {
            transform: translateX(20px);
        }

        /* File Upload */
        .img-upload-area {
            border: 2px dashed #bbf7d0;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #f9fefb;
            width: 100%;
            box-sizing: border-box;
        }

        .img-upload-area:hover {
            border-color: #1eb349;
            background: #f0fdf4;
        }

        /* Submit Footer */
        .form-footer {
            padding: 1rem 1.75rem;
            border-top: 1px solid #f3f7f3;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            background: #fff;
            border-radius: 0 0 20px 20px;
        }

        .btn-cancel {
            height: 44px;
            padding: 0 1.5rem;
            border-radius: 999px;
            border: 1.5px solid #e7f0e7;
            background: #fff;
            color: #64748B;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .btn-submit {
            height: 44px;
            padding: 0 1.5rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            border: none;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        @media(max-width:768px) {
            .prof-layout {
                flex-direction: column;
                width: 100%;
            }

            .prof-sidebar {
                width: 100%;
                position: static;
                box-sizing: border-box;
            }

            .prof-content {
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-body {
                padding: 1.25rem 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="prof-layout">

        <div class="prof-sidebar">
            <button type="button" class="tab-btn active" data-tab="tab-content">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Informasi Dasar
            </button>
            <button type="button" class="tab-btn" data-tab="tab-media">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <circle cx="8.5" cy="8.5" r="1.5" />
                    <path d="M20.4 14.5L16 10 4 20" />
                </svg>
                Media & File
            </button>
            <button type="button" class="tab-btn" data-tab="tab-seo" id="tab-seo-btn" style="display:none;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                SEO & FAQ
            </button>

            <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #e7f0e7;">
                <div
                    style="font-size:0.75rem; font-weight:700; color:#64748b; margin-bottom:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">
                    Pengaturan Form</div>

                <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer;">
                    <div style="position:relative; width:44px; height:24px; flex-shrink:0;">
                        <input type="checkbox" name="is_advanced" id="seoModeToggle" onchange="toggleSeoMode()"
                            style="opacity:0; width:0; height:0;" {{ old('is_advanced') ? 'checked' : '' }}>
                        <span class="toggle-slider-s"></span>
                    </div>
                    <div>
                        <div style="font-size:0.85rem; font-weight:700; color:#0b120c;">Mode Advanced SEO</div>
                        <div style="font-size:0.7rem; color:#64748b; line-height:1.4; margin-top:0.2rem;">Tampilkan tab SEO
                            & Schema FAQ</div>
                    </div>
                </label>
            </div>
        </div>

        <div class="prof-content">
            <form action="{{ route('creator.products.store') }}" method="POST" enctype="multipart/form-data"
                id="productForm">
                @csrf

                @if($errors->any())
                    <div
                        style="background:#fee2e2; color:#b91c1c; padding:1rem 1.5rem; border-radius:12px; margin-bottom:1.5rem; font-size:0.85rem; font-weight:600;">
                        Terdapat kesalahan isian:
                        <ul style="margin-top:0.5rem; margin-bottom:0; padding-left:1.5rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- TAB 1: KONTEN --}}
                <div class="tab-pane active" id="tab-content">
                    <div class="prof-card">
                        <div class="prof-card-head">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path
                                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                </path>
                            </svg>
                            Informasi Utama Produk
                        </div>
                        <div class="form-body">
                            <div class="form-grid">
                                <div class="form-group full">
                                    <label class="form-label">Nama Produk <span>*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-input"
                                        placeholder="Contoh: Tiket Konser Musik Fest 2026" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Tipe Produk <span>*</span></label>
                                    <select name="product_type" id="productTypeSelect" class="form-input"
                                        onchange="handleProductTypeSelectChange(this)" required>
                                        <option value="external_link" {{ old('product_type', 'external_link') == 'external_link' ? 'selected' : '' }}>Produk Digital / Link Access (Marketplace & Store)</option>
                                        <option value="ticket" {{ old('product_type') == 'ticket' ? 'selected' : '' }}>Tiket Event / Wisata / Webinar (Marketplace & Store)</option>
                                        <option value="service" {{ old('product_type') == 'service' ? 'selected' : '' }}>Jasa / Layanan / Service (Khusus Storefront Creator)</option>
                                        <option value="physical" {{ old('product_type') == 'physical' ? 'selected' : '' }}>Produk Fisik / Barang / UMKM (Khusus Storefront Creator)</option>
                                        <option value="makanan" {{ old('product_type') == 'makanan' ? 'selected' : '' }}>Makanan / Minuman / Kuliner (Khusus POS / Storefront Creator)</option>
                                    </select>
                                    <span style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem; display: block;">* Catatan: Marketplace buyle.id HANYA menampilkan 2 Tipe Produk yaitu: 1) Produk Digital / Link Access dan 2) Tiket Event / Wisata / Webinar. Tipe produk lainnya hanya tampil di Bio Link / Storefront pribadi Anda.</span>
                                </div>

                                <div class="form-group full" id="buyleCheckoutToggleWrap"
                                    style="display:none; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1rem; margin-top:0.25rem; margin-bottom:0.75rem;">
                                    <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer;">
                                        <div style="position:relative; width:44px; height:24px; flex-shrink:0;">
                                            <input type="checkbox" name="is_buyle_checkout" id="isBuyleCheckoutToggle"
                                                value="1" {{ old('is_buyle_checkout', 1) ? 'checked' : '' }}
                                                onchange="handleBuyleCheckoutToggle(this.checked)"
                                                style="opacity:0; width:0; height:0;">
                                            <span class="toggle-slider-s"></span>
                                        </div>
                                        <div>
                                            <div style="font-size:0.85rem; font-weight:700; color:#0f172a;">Pembayaran via
                                                Buyle</div>
                                            <div style="font-size:0.72rem; color:#64748b; line-height:1.4;">Aktifkan untuk
                                                menerima pembayaran langsung melalui sistem checkout Buyle.id & ekspedisi
                                                logistik.</div>
                                        </div>
                                    </label>
                                </div>

                                <div id="categoryWrap" class="form-group">
                                    <label class="form-label">Kategori Utama <span>*</span></label>
                                    <select name="product_category_id" id="catSelect" class="form-input" required
                                        onchange="loadSubCat(this.value)">
                                        <option value="">— Pilih Kategori —</option>
                                        <optgroup label="--- TIKET & EVENT ---">
                                            @foreach($categories->where('tab', 'event') as $cat)
                                                <option value="{{ $cat->id }}" data-tab="{{ $cat->tab }}" {{ old('product_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="--- PRODUK DIGITAL & LAINNYA ---">
                                            @foreach($categories->where('tab', '!=', 'event') as $cat)
                                                <option value="{{ $cat->id }}" data-tab="{{ $cat->tab }}" {{ old('product_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>

                                <div class="form-group full" id="ticketFieldsWrap"
                                    style="display:none; background:#F0FDF4; border:1.5px solid #BBF7D0; border-radius:16px; padding:1.5rem; width:100%; box-sizing:border-box;">
                                    <h5
                                        style="font-size:0.95rem; font-weight:800; color:#166534; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem; border-bottom:1px dashed #bbf7d0; padding-bottom:0.75rem;">
                                        <svg width="20" height="20" fill="none" stroke="#166534" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z" />
                                            <path d="M13 5v14" />
                                            <path d="M13 9h.01" />
                                            <path d="M13 15h.01" />
                                        </svg>
                                        Detail Informasi Event / Tiket
                                    </h5>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal Pelaksanaan Event <span>*</span></label>
                                            <input type="date" name="event_date" value="{{ old('event_date') }}"
                                                class="form-input">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Waktu Event (Jam) <span>*</span></label>
                                            <input type="text" name="event_time" value="{{ old('event_time') }}"
                                                class="form-input" placeholder="Contoh: 19:00 - 22:00 WIB">
                                        </div>
                                        <div class="form-group full">
                                            <label class="form-label">Jenis Pelaksanaan Event <span>*</span></label>
                                            <select name="event_type" id="eventTypeSelect" class="form-input"
                                                onchange="toggleEventTypeFields(this.value)">
                                                <option value="offline" {{ old('event_type') == 'offline' ? 'selected' : '' }}>Offline (Tatap Muka di Venue / Gedung / Tempat Acara)</option>
                                                <option value="online" {{ old('event_type') == 'online' ? 'selected' : '' }}>
                                                    Online (Webinar / Zoom Meeting / Live Streaming)</option>
                                            </select>
                                        </div>

                                        <div class="form-group full" id="offlineLocationWrap">
                                            <label class="form-label" style="display:flex; align-items:center; gap:0.4rem;">
                                                <svg width="15" height="15" fill="none" stroke="#166534" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                                    <circle cx="12" cy="10" r="3" />
                                                </svg>
                                                Alamat / Lokasi Venue Event (Offline) <span>*</span>
                                            </label>
                                            <input type="text" name="event_location_offline" id="eventLocationOffline"
                                                value="{{ old('event_location_offline') }}" class="form-input"
                                                placeholder="Contoh: Gedung Senayan Hall A, Jl. Asia Afrika, Jakarta Pusat">
                                            <span class="form-hint">Tuliskan nama tempat / alamat fisik tempat acara
                                                berlangsung.</span>
                                        </div>

                                        <div class="form-group full" id="onlineLocationWrap" style="display:none;">
                                            <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:14px; padding:1.25rem; display:flex; flex-direction:column; gap:1rem; box-shadow:0 1px 3px rgba(0,0,0,0.02);">
                                                
                                                <!-- Pilihan Platform: Banyak pilihan, hanya bisa pilih 1 -->
                                                <div>
                                                    <label class="form-label" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.6rem;">
                                                        <span style="display:flex; align-items:center; gap:0.4rem; font-weight:700;">
                                                            <svg width="15" height="15" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                                            Pilih Platform Acara Online <span>*</span>
                                                        </span>
                                                        <span style="font-size:0.75rem; color:#64748b; font-weight:500;">Pilih salah satu</span>
                                                    </label>

                                                    <div class="platform-chips-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:0.5rem;">
                                                        @php
                                                            $selPlat = old('event_platform', 'Zoom Meeting');
                                                            $platforms = [
                                                                [
                                                                    'id' => 'zoom', 
                                                                    'name' => 'Zoom Meeting', 
                                                                    'svg' => '<svg width="17" height="17" viewBox="0 0 24 24" fill="#2D8CFF"><path d="M4.5 5.5A2.5 2.5 0 0 0 2 8v8a2.5 2.5 0 0 0 2.5 2.5h9a2.5 2.5 0 0 0 2.5-2.5v-1.793l3.646 2.605A1 1 0 0 0 21 16V8a1 1 0 0 0-1.354-.912L16 9.693V8a2.5 2.5 0 0 0-2.5-2.5h-9z"/></svg>', 
                                                                    'placeholder' => 'https://zoom.us/j/123456789 atau link room Zoom', 
                                                                    'label' => 'Tautan Link Zoom Meeting'
                                                                ],
                                                                [
                                                                    'id' => 'gmeet', 
                                                                    'name' => 'Google Meet', 
                                                                    'svg' => '<svg width="17" height="17" viewBox="0 0 24 24"><path d="M15 8l4.5-3.375A1 1 0 0 1 21 5.425v13.15a1 1 0 0 1-1.5.8L15 16V8z" fill="#00AC47"/><rect x="3" y="6" width="12" height="12" rx="2" fill="#00832D"/></svg>', 
                                                                    'placeholder' => 'https://meet.google.com/abc-defg-hij', 
                                                                    'label' => 'Tautan Link Google Meet'
                                                                ],
                                                                [
                                                                    'id' => 'wagroup', 
                                                                    'name' => 'WhatsApp Group Event', 
                                                                    'svg' => '<svg width="17" height="17" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.556 4.117 1.528 5.849L0 24l6.335-1.508A11.948 11.948 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.65-.52-5.154-1.422l-.37-.218-3.764.896.924-3.667-.243-.381A9.953 9.953 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z"/></svg>', 
                                                                    'placeholder' => 'https://chat.whatsapp.com/...', 
                                                                    'label' => 'Tautan Undangan Grup WhatsApp Event'
                                                                ],
                                                                [
                                                                    'id' => 'youtube', 
                                                                    'name' => 'YouTube Live Streaming', 
                                                                    'svg' => '<svg width="17" height="17" viewBox="0 0 24 24" fill="#FF0000"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>', 
                                                                    'placeholder' => 'https://youtube.com/live/...', 
                                                                    'label' => 'Tautan YouTube Live Streaming'
                                                                ],
                                                                [
                                                                    'id' => 'teams', 
                                                                    'name' => 'Microsoft Teams', 
                                                                    'svg' => '<svg width="17" height="17" viewBox="0 0 24 24" fill="#5059C9"><path d="M19.5 7.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm1.5 2h-3c-.55 0-1 .45-1 1v4.5a3.5 3.5 0 0 0 7 0V10.5c0-.55-.45-1-1-1zM14 6a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm2 2H8c-.55 0-1 .45-1 1v7.5A4.5 4.5 0 0 0 11.5 21h5a4.5 4.5 0 0 0 4.5-4.5V9c0-.55-.45-1-1-1z"/></svg>', 
                                                                    'placeholder' => 'https://teams.microsoft.com/l/...', 
                                                                    'label' => 'Tautan Microsoft Teams'
                                                                ],
                                                                [
                                                                    'id' => 'other', 
                                                                    'name' => 'Webinar / Platform Lainnya', 
                                                                    'svg' => '<svg width="17" height="17" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>', 
                                                                    'placeholder' => 'https://...', 
                                                                    'label' => 'Tautan Akses Event Online'
                                                                ],
                                                            ];
                                                        @endphp

                                                        @foreach($platforms as $p)
                                                            @php $isActive = ($selPlat == $p['name']); @endphp
                                                            <label class="platform-chip-item" style="cursor:pointer; margin:0;">
                                                                <input type="radio" name="event_platform" value="{{ $p['name'] }}" {{ $isActive ? 'checked' : '' }}
                                                                    style="display:none;"
                                                                    data-placeholder="{{ $p['placeholder'] }}"
                                                                    data-label="{{ $p['label'] }}"
                                                                    onchange="selectOnlinePlatform(this)">
                                                                <div class="platform-chip-box {{ $isActive ? 'active' : '' }}" style="display:flex; align-items:center; gap:0.45rem; padding:0.55rem 0.75rem; border:1.5px solid {{ $isActive ? '#166534' : '#e2e8f0' }}; border-radius:10px; background:{{ $isActive ? '#f0fdf4' : '#f8fafc' }}; transition:all 0.15s ease;">
                                                                    <span style="display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">{!! $p['svg'] !!}</span>
                                                                    <span style="font-size:0.8rem; font-weight:{{ $isActive ? '700' : '600' }}; color:{{ $isActive ? '#166534' : '#334155' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p['name'] }}</span>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- 1 Input Link yang Menyesuaikan Platform Pilihan -->
                                                <div id="dynamicLinkFieldWrap" style="padding-top:0.75rem; border-top:1px dashed #e2e8f0;">
                                                    @php
                                                        $activePlatObj = collect($platforms)->firstWhere('name', $selPlat) ?? $platforms[0];
                                                    @endphp
                                                    <label class="form-label" id="dynamicLinkLabel" style="display:flex; align-items:center; gap:0.4rem;">
                                                        <svg width="15" height="15" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24">
                                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                                                        </svg>
                                                        <span>{{ $activePlatObj['label'] }}</span> <span>*</span>
                                                    </label>
                                                    <input type="text" name="event_location_online" id="eventLocationOnline"
                                                        value="{{ old('event_location_online') }}" class="form-input"
                                                        placeholder="{{ $activePlatObj['placeholder'] }}">
                                                    <span class="form-hint" style="color:#64748b; margin-top:0.35rem; display:flex; align-items:center; gap:5px;">
                                                        <svg width="13" height="13" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                        <span>Tautan ini aman & hanya dapat diakses oleh pembeli tiket resmi setelah transaksi berhasil.</span>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group" style="margin-top:0.5rem;">
                                            <label class="form-label" style="display:flex; align-items:center; gap:0.4rem; color:#b45309;">
                                                <svg width="15" height="15" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                Jadwal Mulai Penjualan (Ticket War / Live Drop)
                                            </label>
                                            <input type="datetime-local" name="ticket_war_start"
                                                value="{{ old('ticket_war_start') }}"
                                                class="form-input">
                                            <span class="form-hint">Kosongkan jika tiket langsung bisa dibeli saat ini. Isi jika ingin ada countdown hitung mundur sebelum tombol beli aktif otomatis.</span>
                                        </div>

                                        <div class="form-group" style="margin-top:0.5rem;">
                                            <label class="form-label" style="display:flex; align-items:center; gap:0.4rem;">
                                                <svg width="15" height="15" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                                Maksimal Tiket Per Transaksi (Anti Calo)
                                            </label>
                                            <input type="number" name="max_tickets_per_order" min="1" max="20"
                                                value="{{ old('max_tickets_per_order', 4) }}"
                                                class="form-input" placeholder="Default: 4">
                                            <span class="form-hint">Mencegah bot atau calo memborong tiket sekaligus. Default 4 tiket per order.</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="subCatWrap" style="display:none;">
                                    <label class="form-label">Sub-Kategori</label>
                                    <select name="product_sub_category_id" id="subCatSelect" class="form-input">
                                        <option value="">— Pilih Sub-Kategori —</option>
                                    </select>
                                </div>

                                <div class="form-group" id="groupWrap">
                                    <label class="form-label">Kelompok Produk</label>
                                    <select name="creator_group_id" class="form-input">
                                        <option value="">— Tidak Masuk Kelompok —</option>
                                        @foreach($groups as $grp)
                                            <option value="{{ $grp->id }}" {{ old('creator_group_id') == $grp->id ? 'selected' : '' }}>{{ $grp->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="form-hint"><a href="{{ route('creator.groups.index') }}"
                                            style="color:#1eb349;text-decoration:none;">Buat Kelompok Baru?</a></span>
                                </div>

                                {{-- PHYSICAL PRODUCT SPECIFIC FIELDS --}}
                                <div class="form-group full" id="physicalFieldsWrap"
                                    style="display:none; background:#F8FAFC; border:1.5px solid #E2E8F0; border-radius:16px; padding:1.5rem; width:100%; box-sizing:border-box;">
                                    <h5
                                        style="font-size:0.95rem; font-weight:800; color:#0F172A; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem; border-bottom:1px dashed #e2e8f0; padding-bottom:0.75rem;">
                                        <svg width="20" height="20" fill="none" stroke="#0F172A" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                        </svg>
                                        Spesifikasi Produk Fisik & Logistik
                                    </h5>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label class="form-label">SKU / Kode Stok (Opsional)</label>
                                            <input type="text" name="sku" value="{{ old('sku') }}" class="form-input"
                                                placeholder="Misal: BRG-001-RED">
                                            <span class="form-hint">Kode unik identifikasi stok barang Anda.</span>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Berat Produk (Gram) <span>*</span></label>
                                            <input type="number" name="weight" id="input_weight" value="{{ old('weight') }}"
                                                class="form-input" placeholder="Misal: 500" min="0">
                                            <span class="form-hint">Berat dalam hitungan gram (1 Kg = 1000 Gram).</span>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Panjang (P) (cm)</label>
                                            <input type="number" name="length" id="input_length" value="{{ old('length') }}"
                                                class="form-input" placeholder="Misal: 20" min="0" step="0.1"
                                                oninput="calculateProductVolume()">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Lebar (L) (cm)</label>
                                            <input type="number" name="width" id="input_width" value="{{ old('width') }}"
                                                class="form-input" placeholder="Misal: 15" min="0" step="0.1"
                                                oninput="calculateProductVolume()">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Tinggi (T) (cm)</label>
                                            <input type="number" name="height" id="input_height" value="{{ old('height') }}"
                                                class="form-input" placeholder="Misal: 10" min="0" step="0.1"
                                                oninput="calculateProductVolume()">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Volume (P x L x T) (cm³)</label>
                                            <input type="number" name="volume" id="input_volume" value="{{ old('volume') }}"
                                                class="form-input" placeholder="Otomatis dihitung" readonly
                                                style="background:#f1f5f9; color:#475569; font-weight:700;">
                                            <span class="form-hint">Dihitung otomatis: P × L × T.</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Harga Normal (Rp) <span>*</span></label>
                                    <input type="text" name="price" id="input_price"
                                        value="{{ old('price') ? number_format((float) preg_replace('/[^\d]/', '', old('price')), 0, ',', '.') : '' }}"
                                        class="form-input currency-input" placeholder="Misal: 50.000" required
                                        autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Harga Diskon / Promo (Rp)</label>
                                    <input type="text" name="sale_price" id="input_sale_price"
                                        value="{{ old('sale_price') ? number_format((float) preg_replace('/[^\d]/', '', old('sale_price')), 0, ',', '.') : '' }}"
                                        class="form-input currency-input" placeholder="Opsional, misal: 29.000"
                                        autocomplete="off">
                                </div>

                                @include('creator.products.partials.free-product-requirements', ['product' => null])

                                @include('creator.products.partials.variants-manager')

                                <div id="commissionWrap" class="form-group full" style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; margin-top:0.75rem; margin-bottom:0.75rem; overflow:hidden; box-sizing:border-box;">
                                    <label style="display:flex; align-items:center; gap:0.75rem; padding:0.85rem 1rem; cursor:pointer; user-select:none; margin:0; background:#f8fafc;">
                                        <div style="position:relative; width:44px; height:24px; flex-shrink:0;">
                                            <input type="checkbox" id="affiliateCommissionToggle" checked
                                                style="opacity:0; width:0; height:0;"
                                                onchange="handleAffiliateToggle(this.checked)">
                                            <span class="toggle-slider-s"></span>
                                        </div>
                                        <div style="flex:1;">
                                            <div style="font-size:0.88rem; font-weight:700; color:#0f172a;">Aktifkan Komisi Affiliate</div>
                                            <div style="font-size:0.73rem; color:#64748b; line-height:1.4;">Produk ini bisa dipromosikan oleh affiliate dan mereka mendapat komisi.</div>
                                        </div>
                                    </label>
                                    <div id="affiliateRateWrap" style="border-top:1px solid #e2e8f0; padding:1.25rem 1.4rem; background:#ffffff;">
                                        <label class="form-label">Besaran Komisi Affiliate (%)</label>
                                        <div style="position:relative; display:flex; align-items:center;">
                                            <input type="number" name="affiliate_commission_rate" id="affiliate_commission_rate"
                                                value="{{ old('affiliate_commission_rate', 10) }}" class="form-input" min="5"
                                                max="100" step="0.5" placeholder="10" style="padding-right:2.5rem;">
                                            <span style="position:absolute; right:1rem; font-weight:700; color:#64748B;">%</span>
                                        </div>
                                        <span class="form-hint" style="color:#0284C7; font-weight:600; margin-top:0.35rem; display:inline-flex; align-items:center; gap:0.35rem;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><line x1="12" y1="16" x2="12" y2="12" /><line x1="12" y1="8" x2="12.01" y2="8" /></svg>
                                            Minimum 5%, rekomendasi 10% untuk mendorong penjualan.
                                        </span>
                                    </div>
                                </div>

                                @if(auth()->user()->creatorProfile?->is_exclusive)
                                <div class="form-group full" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(217, 119, 6, 0.04)); border: 1.5px solid rgba(245, 158, 11, 0.3); border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; color: #b45309; font-weight: 700; font-size: 0.95rem;">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        Fitur Creator Eksklusif — Statistik Bintang & Penjual
                                    </div>
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                        <div class="form-group" style="margin-bottom:0;">
                                            <label class="form-label" style="color:#78350f;">Rating Produk (0.0 - 5.0)</label>
                                            <input type="number" name="rating" value="{{ old('rating', '') }}" class="form-input" min="0" max="5" step="0.1" placeholder="Kosongkan jika otomatis dari ulasan" style="border-color: rgba(245, 158, 11, 0.4);">
                                            <span class="form-hint" style="color:#92400e;">Atur rating manual (opsional, kosongkan agar otomatis dihitung dari ulasan asli pembeli).</span>
                                        </div>
                                        <div class="form-group" style="margin-bottom:0;">
                                            <label class="form-label" style="color:#78350f;">Jumlah Terjual (Sales)</label>
                                            <input type="number" name="sold_count" value="{{ old('sold_count', '0') }}" class="form-input" min="0" step="1" placeholder="0" style="border-color: rgba(245, 158, 11, 0.4);">
                                            <span class="form-hint" style="color:#92400e;">Atur angka produk terjual (default: 0).</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group full">
                                    <label class="form-label">Deskripsi Singkat (Exerp) <span>*</span></label>
                                    <textarea name="short_desc" class="form-input" rows="2"
                                        placeholder="Deskripsi singkat maksimal 160 karakter" maxlength="160"
                                        required>{{ old('short_desc') }}</textarea>
                                    <span class="form-hint">Digunakan sebagai meta description SEO jika mode advanced
                                        dimatikan.</span>
                                </div>

                                <div class="form-group full" style="margin-bottom:0;">
                                    <label class="form-label">Deskripsi Lengkap <span>*</span></label>
                                    @include('admin.partials.rich-editor', ['name' => 'description', 'value' => old('description'), 'height' => '280px'])
                                </div>

                                {{-- LINK VIDEO OPSIONAL - di dalam section deskripsi --}}
                                <div class="form-group full" id="videoLinkWrap" style="margin-top:1.25rem;">
                                    <div style="border:1.5px solid #e2e8f0; border-radius:14px; overflow:hidden; background:#fff;">
                                        <div style="display:flex; align-items:center; gap:0.65rem; padding:0.75rem 1rem; background:#f8fafc; border-bottom:1px solid #f1f5f9; cursor:pointer; user-select:none;" onclick="toggleVideoLinkPanel()" id="videoLinkToggleBtn">
                                            <div style="width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg,#1eb349,#a5cf37); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                <svg width="15" height="15" fill="#fff" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                            </div>
                                            <div style="flex:1;">
                                                <div style="font-size:0.82rem; font-weight:700; color:#0f172a;">Link Video <span style="font-size:0.72rem; font-weight:500; color:#94a3b8;">(Opsional)</span></div>
                                                <div style="font-size:0.7rem; color:#64748b;">YouTube, TikTok, dan video lainnya</div>
                                            </div>
                                            <svg id="videoLinkChevron" width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2.5" viewBox="0 0 24 24" style="transition:transform 0.2s;"><path d="m6 9 6 6 6-6"/></svg>
                                        </div>
                                        <div id="videoLinkPanel" style="display:none; padding:1rem;">
                                            <input type="url" name="youtube_video_url" id="videoUrlInput"
                                                value="{{ old('youtube_video_url') }}"
                                                class="form-input" placeholder="Paste link YouTube atau TikTok di sini..."
                                                oninput="debounceVideoPreview(this.value)"
                                                style="margin-bottom:0.75rem;">
                                            <div id="videoPreviewBox" style="display:none; border-radius:12px; overflow:hidden; background:#0f172a;"></div>
                                            <span class="form-hint" style="margin-top:0.5rem;">Mendukung: YouTube, YouTube Shorts, TikTok. Preview otomatis muncul di bawah saat link diinput.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: MEDIA --}}
                <div class="tab-pane" id="tab-media">
                    <div class="prof-card">
                        <div class="prof-card-head">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                            </svg>
                            Upload Visual Produk
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="form-label">Upload Thumbnail Utama <span>*</span></label>
                                <div class="img-upload-area" id="thumbDropzone"
                                    onclick="document.getElementById('thumb-input').click()" style="margin-bottom:0.75rem;">
                                    <div>
                                        <svg width="32" height="32" fill="none" stroke="#1eb349" stroke-width="1.6"
                                            viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg>
                                        <p style="font-size:0.82rem; color:#1E293B; margin-top:0.4rem; font-weight:700;">
                                            Pilih Thumbnail Utama (1 Foto)</p>
                                    </div>
                                </div>
                                <div id="thumbPreviewWrap" style="display:none; margin-bottom:1rem;">
                                    <div
                                        style="position:relative; display:inline-block; border-radius:14px; overflow:hidden; border:2px solid #1eb349; box-shadow:0 4px 14px rgba(0,0,0,0.08);">
                                        <img id="thumbPreviewImg" src=""
                                            style="width:130px; height:130px; object-fit:cover; display:block;">
                                        <div
                                            style="position:absolute; top:6px; right:6px; display:flex; gap:4px; background:rgba(11,18,12,0.65); padding:3px 5px; border-radius:20px; backdrop-filter:blur(4px);">
                                            <button type="button" onclick="document.getElementById('thumb-input').click()"
                                                style="background:#3b82f6; color:#fff; border:none; border-radius:50%; width:24px; height:24px; cursor:pointer; font-size:11px; font-weight:bold; display:flex; align-items:center; justify-content:center;"
                                                title="Ganti Foto">✎</button>
                                            <button type="button" onclick="removeThumbnail()"
                                                style="background:#ef4444; color:#fff; border:none; border-radius:50%; width:24px; height:24px; cursor:pointer; font-size:11px; font-weight:bold; display:flex; align-items:center; justify-content:center;"
                                                title="Hapus Foto">✕</button>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" name="image" id="thumb-input" accept="image/*"
                                    onchange="previewSingleThumb(event)" style="display:none;">
                            </div>

                            <div class="form-group" id="posterUploadWrap" style="display:none; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:14px; padding:1.25rem; margin-bottom:1.25rem;">
                                <label class="form-label" style="color:#166534; font-weight:800; display:flex; align-items:center; gap:0.4rem;">
                                    <svg width="18" height="18" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                                        <polygon points="10,9 15,12 10,15"/>
                                    </svg>
                                    Upload Poster Event (Landscape 16:9) <span style="color:#ef4444;">*</span>
                                </label>
                                <span class="form-hint" style="margin-bottom:0.75rem; color:#15803d; font-weight:600;">Khusus tipe produk Tiket Event / Wisata / Webinar. Diupload dalam format landscape 16:9.</span>
                                <div class="img-upload-area" id="posterDropzone"
                                    onclick="document.getElementById('poster-input').click()" style="margin-bottom:0.75rem; background:#fff;">
                                    <div>
                                        <svg width="32" height="32" fill="none" stroke="#1eb349" stroke-width="1.6"
                                            viewBox="0 0 24 24">
                                            <rect x="2" y="4" width="20" height="16" rx="2" />
                                            <circle cx="8" cy="10" r="2" />
                                            <path d="M22 15l-5-5-8 8" />
                                        </svg>
                                        <p style="font-size:0.82rem; color:#1E293B; margin-top:0.4rem; font-weight:700;">
                                            Pilih Poster Event Landscape (16:9)</p>
                                    </div>
                                </div>
                                <div id="posterPreviewWrap" style="display:none; margin-bottom:0.5rem;">
                                    <div
                                        style="position:relative; display:inline-block; border-radius:14px; overflow:hidden; border:2px solid #1eb349; box-shadow:0 4px 14px rgba(0,0,0,0.08);">
                                        <img id="posterPreviewImg" src=""
                                            style="width:240px; height:135px; object-fit:cover; display:block;">
                                        <div
                                            style="position:absolute; top:6px; right:6px; display:flex; gap:4px; background:rgba(11,18,12,0.65); padding:3px 5px; border-radius:20px; backdrop-filter:blur(4px);">
                                            <button type="button" onclick="document.getElementById('poster-input').click()"
                                                style="background:#3b82f6; color:#fff; border:none; border-radius:50%; width:24px; height:24px; cursor:pointer; font-size:11px; font-weight:bold; display:flex; align-items:center; justify-content:center;"
                                                title="Ganti Poster">✎</button>
                                            <button type="button" onclick="removePoster()"
                                                style="background:#ef4444; color:#fff; border:none; border-radius:50%; width:24px; height:24px; cursor:pointer; font-size:11px; font-weight:bold; display:flex; align-items:center; justify-content:center;"
                                                title="Hapus Poster">✕</button>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" name="og_image" id="poster-input" accept="image/*"
                                    onchange="previewPosterThumb(event)" style="display:none;">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Upload Foto Galeri / Screenshot (Opsional)</label>
                                <div class="img-upload-area" id="galleryDropzone"
                                    onclick="document.getElementById('gallery-input').click()"
                                    style="margin-bottom:0.75rem;">
                                    <div>
                                        <p style="font-size:0.8rem; color:#64748B; font-weight:600;">Klik untuk pilih hingga
                                            6 foto galeri pendukung</p>
                                    </div>
                                </div>
                                <div id="galleryPreview"
                                    style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:0.75rem;"></div>
                                <input type="file" name="gallery[]" id="gallery-input" accept="image/*" multiple max="6"
                                    onchange="previewGalleryList(event)" style="display:none;">
                                <span class="form-hint">Maksimal 6 foto. Preview akan muncul di atas dan Anda dapat menghapus foto menggunakan tombol (✕) sebelum disimpan.</span>
                            </div>

                            {{-- Link Video sudah dipindahkan ke section Deskripsi di tab Informasi Dasar --}}
                        </div>
                    </div>

                    <div class="prof-card" id="digitalAccessCard">
                        <div class="prof-card-head">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            Akses File Digital <span>*</span>
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="form-label">Tautan Eksternal (Google Drive / Dropbox / Mediafire /
                                    dll)</label>
                                <input type="url" name="digital_resource" id="externalLink"
                                    value="{{ old('digital_resource') }}" class="form-input" placeholder="https://..."
                                    required>
                                <span id="linkStatus"
                                    style="font-size:0.75rem; font-weight:600; display:none; margin-top:0.5rem; padding:0.5rem; border-radius:6px;"></span>
                            </div>
                        </div>
                    </div>

                    {{-- WHITE LABEL CARD --}}
                    <div class="prof-card" style="border: 2px solid #E2E8F0; transition: border-color 0.2s;"
                        id="whitelabelCard">
                        <div class="prof-card-head"
                            style="background: linear-gradient(135deg, #F8FAFC 0%, #F0FDF4 100%); display:flex; align-items:center; gap:0.5rem;">
                            <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                            </svg>
                            <span style="color:#1E293B; font-weight:800;">Fitur White Label & Lisensi Jual Kembali (Lisensi
                                Resell & Re-Brand)</span>
                        </div>
                        <div class="form-body">
                            <div
                                style="background:#F0FDF4; border:1px solid #BBF7D0; border-radius:12px; padding:1rem; margin-bottom:1.25rem;">
                                <label
                                    style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; font-weight:700; color:#15803D; font-size:0.88rem;">
                                    <input type="checkbox" name="is_whitelabel" id="isWhitelabelCheck" value="1" {{ old('is_whitelabel') ? 'checked' : '' }}
                                        onchange="toggleWhitelabelFields(this.checked)"
                                        style="width:18px; height:18px; accent-color:#1eb349; cursor:pointer;">
                                    <span>Tawarkan Produk Ini Sebagai White Label (Dapat Dijual Kembali Oleh Reseller /
                                        Creator Lain)</span>
                                </label>
                            </div>

                            <div id="whitelabelOptionsWrap" style="display:none;">
                                <div
                                    style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:12px; padding:1rem; margin-bottom:1.25rem; font-size:0.83rem; color:#92400E; line-height:1.5;">
                                    <strong
                                        style="display:flex; align-items:center; gap:0.4rem; margin-bottom:0.4rem; font-size:0.88rem; color:#B45309;">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" y1="16" x2="12" y2="12" />
                                            <line x1="12" y1="8" x2="12.01" y2="8" />
                                        </svg>
                                        Ketentuan & Panduan White Label:
                                    </strong>
                                    <ul
                                        style="margin:0; padding-left:1.2rem; display:flex; flex-direction:column; gap:0.25rem;">
                                        <li><strong>Aset Netral:</strong> File / link produk <u>TIDAK BOLEH</u> mengandung
                                            watermark, logo personal, atau nomor kontak yang mengikat.</li>
                                        <li><strong>Bebas Markup:</strong> Creator / reseller lain dapat menyalin link
                                            produk ini ke Bio Link / Store mereka dan menaikkan harga jual sesuai keinginan.
                                        </li>
                                        <li><strong>Harga Dasar:</strong> Menggunakan harga produk utama yang diinput pada
                                            Informasi Utama Produk.</li>
                                        <li><strong>Approval Tim Buyle:</strong> Setelah produk disimpan, statusnya akan
                                            <code>Menunggu Approval Tim Buyle (Pending)</code>. Admin akan memverifikasi
                                            file Anda sebelum muncul di katalog White Label Marketplace.
                                        </li>
                                    </ul>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group full">
                                        <label class="form-label">Ketentuan Lisensi / Catatan Reseller (Opsional)</label>
                                        <textarea name="whitelabel_terms" class="form-input" rows="2"
                                            placeholder="Contoh: Boleh ubah nama & cover produk, dilarang menjual di bawah harga normal...">{{ old('whitelabel_terms') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: SEO & FAQ --}}
                <div class="tab-pane" id="tab-seo">
                    <div class="prof-card">
                        <div class="prof-card-head">
                            Meta Data Custom
                        </div>
                        <div class="form-body">
                            <div class="form-hint" style="margin-bottom:1.25rem;">Jika dikosongkan, sistem akan otomatis
                                menggunakan Informasi Utama Produk untuk SEO.</div>
                            <div class="form-group">
                                <label class="form-label">Meta Title SEO</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Meta Description SEO</label>
                                <textarea name="meta_description" class="form-input"
                                    rows="2">{{ old('meta_description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                    class="form-input">
                            </div>
                        </div>
                    </div>

                    <div class="prof-card">
                        <div class="prof-card-head" style="justify-content:space-between;">
                            FAQ Schema
                            <button type="button" onclick="addFaqRow()"
                                style="background:#f0fdf4; color:#1eb349; border:none; padding:0.4rem 0.8rem; border-radius:6px; font-weight:700; cursor:pointer; font-size:0.8rem;">+
                                Tambah FAQ</button>
                        </div>
                        <div class="form-body" id="faqContainer">
                            @php $oldFaqs = old('faqs', [['question' => '', 'answer' => '']]); @endphp
                            @foreach($oldFaqs as $idx => $faq)
                                <div class="faq-row"
                                    style="background:#f8fafc; border:1px solid #e2e8f0; padding:1rem; border-radius:12px; margin-bottom:1rem; position:relative;">
                                    <div class="form-group">
                                        <label class="form-label">Pertanyaan</label>
                                        <input type="text" name="faqs[{{$idx}}][question]" value="{{ $faq['question'] ?? '' }}"
                                            class="form-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Jawaban</label>
                                        <textarea name="faqs[{{$idx}}][answer]" class="form-input"
                                            rows="2">{{ $faq['answer'] ?? '' }}</textarea>
                                    </div>
                                    @if($idx > 0)
                                        <button type="button" onclick="this.closest('.faq-row').remove()"
                                            style="position:absolute; top:1rem; right:1rem; background:#fee2e2; color:#ef4444; border:none; border-radius:6px; padding:0.25rem 0.5rem; cursor:pointer; font-size:0.75rem; font-weight:bold;">Hapus</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('creator.products.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan & Publikasikan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Tab switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(this.dataset.tab).classList.add('active');
            });
        });

        // SEO Mode Toggle
        function toggleSeoMode() {
            const isAdvanced = document.getElementById('seoModeToggle').checked;
            const seoBtn = document.getElementById('tab-seo-btn');
            if (isAdvanced) {
                seoBtn.style.display = 'flex';
            } else {
                seoBtn.style.display = 'none';
                // Switch tab if seo is active
                if (seoBtn.classList.contains('active')) {
                    document.querySelector('[data-tab="tab-content"]').click();
                }
            }
        }
        // Initialize
        toggleSeoMode();

        // FAQ Add Row
        let faqIdx = {{ count($oldFaqs) }};
        function addFaqRow() {
            const container = document.getElementById('faqContainer');
            const html = `
                            <div class="faq-row" style="background:#f8fafc; border:1px solid #e2e8f0; padding:1rem; border-radius:12px; margin-bottom:1rem; position:relative;">
                                <div class="form-group">
                                    <label class="form-label">Pertanyaan</label>
                                    <input type="text" name="faqs[${faqIdx}][question]" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jawaban</label>
                                    <textarea name="faqs[${faqIdx}][answer]" class="form-input" rows="2"></textarea>
                                </div>
                                <button type="button" onclick="this.closest('.faq-row').remove()" style="position:absolute; top:1rem; right:1rem; background:#fee2e2; color:#ef4444; border:none; border-radius:6px; padding:0.25rem 0.5rem; cursor:pointer; font-size:0.75rem; font-weight:bold;">Hapus</button>
                            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            faqIdx++;
        }

        // Money Formatter
        function formatRupiah(val) {
            let numberString = val.replace(/[^,\d]/g, '').toString();
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        }
        document.querySelectorAll('.currency-input').forEach(input => {
            input.addEventListener('input', function () { this.value = formatRupiah(this.value); });
        });

        // Single Thumb
        function previewSingleThumb(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.getElementById('thumbPreviewImg');
                    const wrap = document.getElementById('thumbPreviewWrap');
                    const drop = document.getElementById('thumbDropzone');
                    if (img) img.src = e.target.result;
                    if (wrap) wrap.style.display = 'block';
                    if (drop) drop.style.display = 'none';

                    // Launch Cropper for fine-tuning
                    initImageCropper(input, {
                        aspectRatio: 1,
                        width: 800,
                        height: 800,
                        title: 'Crop Foto Produk Utama (1:1 Square)',
                        onCropSuccess: function (file, dataUrl) {
                            if (img) img.src = dataUrl;
                        }
                    });
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function removeThumbnail() {
            document.getElementById('thumb-input').value = '';
            document.getElementById('thumbPreviewWrap').style.display = 'none';
            document.getElementById('thumbDropzone').style.display = 'block';
        }

        // Sub Kategori
        const subData = @json($categories->map(fn($c) => ['id' => $c->id, 'subs' => $c->subCategories->map(fn($s) => ['id' => $s->id, 'name' => $s->name])]));
        function loadSubCat(catId) {
            const wrap = document.getElementById('subCatWrap');
            const sel = document.getElementById('subCatSelect');
            if (!catId) { wrap.style.display = 'none'; return; }
            const found = subData.find(c => c.id == catId);
            if (!found || !found.subs.length) { wrap.style.display = 'none'; return; }
            sel.innerHTML = '<option value="">— Pilih Sub-Kategori —</option>';
            found.subs.forEach(s => { const o = document.createElement('option'); o.value = s.id; o.textContent = s.name; sel.appendChild(o); });
            wrap.style.display = 'block';
        }
        const initCat = document.getElementById('catSelect').value;
        if (initCat) loadSubCat(initCat);
        // Whitelabel toggle
        function toggleWhitelabelFields(checked) {
            const wrap = document.getElementById('whitelabelOptionsWrap');
            const card = document.getElementById('whitelabelCard');
            if (wrap) wrap.style.display = checked ? 'block' : 'none';
            if (card) card.style.borderColor = checked ? '#1eb349' : '#E2E8F0';
        }
        // Init check
        const wlCheck = document.getElementById('isWhitelabelCheck');
        if (wlCheck) toggleWhitelabelFields(wlCheck.checked);

        // Event Type Toggle (Offline vs Online)
        function toggleEventTypeFields(val) {
            const offWrap = document.getElementById('offlineLocationWrap');
            const onWrap = document.getElementById('onlineLocationWrap');
            if (val === 'online') {
                if (offWrap) offWrap.style.display = 'none';
                if (onWrap) onWrap.style.display = 'block';
            } else {
                if (offWrap) offWrap.style.display = 'block';
                if (onWrap) onWrap.style.display = 'none';
            }
        }

        // Single Platform Selector for Online Event
        function selectOnlinePlatform(radio) {
            document.querySelectorAll('.platform-chip-box').forEach(box => {
                box.classList.remove('active');
                box.style.borderColor = '#e2e8f0';
                box.style.background = '#f8fafc';
                const txt = box.querySelector('span:last-child');
                if (txt) {
                    txt.style.color = '#334155';
                    txt.style.fontWeight = '600';
                }
            });

            const activeBox = radio.closest('.platform-chip-item')?.querySelector('.platform-chip-box');
            if (activeBox) {
                activeBox.classList.add('active');
                activeBox.style.borderColor = '#166534';
                activeBox.style.background = '#f0fdf4';
                const txt = activeBox.querySelector('span:last-child');
                if (txt) {
                    txt.style.color = '#166534';
                    txt.style.fontWeight = '700';
                }
            }

            const placeholder = radio.dataset.placeholder;
            const label = radio.dataset.label;
            const input = document.getElementById('eventLocationOnline');
            const labelEl = document.querySelector('#dynamicLinkLabel span:first-of-type');

            if (input && placeholder) input.placeholder = placeholder;
            if (labelEl && label) labelEl.textContent = label;
        }

        // Volume Calculation (P x L x T)
        function calculateProductVolume() {
            const p = parseFloat(document.getElementById('input_length')?.value) || 0;
            const l = parseFloat(document.getElementById('input_width')?.value) || 0;
            const t = parseFloat(document.getElementById('input_height')?.value) || 0;
            const volInput = document.getElementById('input_volume');
            if (volInput) {
                if (p > 0 && l > 0 && t > 0) {
                    volInput.value = Math.round(p * l * t * 100) / 100;
                } else {
                    volInput.value = '';
                }
            }
        }

        function previewPosterThumb(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.getElementById('posterPreviewImg');
                    const wrap = document.getElementById('posterPreviewWrap');
                    const drop = document.getElementById('posterDropzone');
                    if (img) img.src = e.target.result;
                    if (wrap) wrap.style.display = 'block';
                    if (drop) drop.style.display = 'none';

                    if (typeof initImageCropper === 'function') {
                        initImageCropper(input, {
                            aspectRatio: 16 / 9,
                            width: 1280,
                            height: 720,
                            title: 'Crop Poster Event (16:9 Landscape)',
                            onCropSuccess: function (file, dataUrl) {
                                if (img) img.src = dataUrl;
                            }
                        });
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function removePoster() {
            const pInput = document.getElementById('poster-input');
            if (pInput) pInput.value = '';
            const pWrap = document.getElementById('posterPreviewWrap');
            if (pWrap) pWrap.style.display = 'none';
            const pDrop = document.getElementById('posterDropzone');
            if (pDrop) pDrop.style.display = 'block';
        }

        // Product Type Toggle
        function toggleProductTypeFields(val) {
            const isPhysicalOrFood = val === 'physical' || val === 'makanan';
            const isService = val === 'service';
            const wrap = document.getElementById('ticketFieldsWrap');
            const posterWrap = document.getElementById('posterUploadWrap');
            const digitalCard = document.getElementById('digitalAccessCard');
            const whitelabelCard = document.getElementById('whitelabelCard');
            const extInput = document.getElementById('externalLink');
            const evType = document.getElementById('eventTypeSelect');
            const wlCheck = document.getElementById('isWhitelabelCheck');
            const categoryWrap = document.getElementById('categoryWrap');
            const subCatWrap = document.getElementById('subCatWrap');
            const groupWrap = document.getElementById('groupWrap');
            const commissionWrap = document.getElementById('commissionWrap');
            const physicalWrap = document.getElementById('physicalFieldsWrap');
            const catSelect = document.getElementById('catSelect');
            const commissionInput = document.getElementById('affiliate_commission_rate');
            const weightInput = document.getElementById('input_weight');
            const buyleToggleWrap = document.getElementById('buyleCheckoutToggleWrap');
            const buyleToggle = document.getElementById('isBuyleCheckoutToggle');

            // Toggle Pembayaran Buyle: HANYA untuk service, physical, makanan
            if (isPhysicalOrFood || isService) {
                if (buyleToggleWrap) buyleToggleWrap.style.display = 'block';
            } else {
                // external_link & ticket: selalu checkout Buyle, toggle tidak perlu
                if (buyleToggleWrap) buyleToggleWrap.style.display = 'none';
            }

            if (isPhysicalOrFood) {
                const isBuyleActive = buyleToggle ? buyleToggle.checked : true;

                if (isBuyleActive && val === 'physical') {
                    if (physicalWrap) physicalWrap.style.display = 'block';
                    if (weightInput) weightInput.setAttribute('required', 'required');
                } else {
                    if (physicalWrap) physicalWrap.style.display = 'none';
                    if (weightInput) weightInput.removeAttribute('required');
                }

                // Hide Category, Sub-Category, and Group for Barang & Makanan
                if (categoryWrap) categoryWrap.style.display = 'none';
                if (subCatWrap) subCatWrap.style.display = 'none';
                if (groupWrap) groupWrap.style.display = 'none';
                if (catSelect) catSelect.removeAttribute('required');

                if (commissionWrap) commissionWrap.style.display = 'none';
                if (commissionInput) commissionInput.removeAttribute('required');
            } else if (isService) {
                if (physicalWrap) physicalWrap.style.display = 'none';
                if (categoryWrap) categoryWrap.style.display = 'none';
                if (subCatWrap) subCatWrap.style.display = 'none';
                if (groupWrap) groupWrap.style.display = 'none';
                if (catSelect) catSelect.removeAttribute('required');
                if (weightInput) weightInput.removeAttribute('required');
                if (commissionWrap) commissionWrap.style.display = 'none';
                if (commissionInput) commissionInput.removeAttribute('required');
            } else {
                // ticket or external_link (Digital)
                if (physicalWrap) physicalWrap.style.display = 'none';
                if (weightInput) weightInput.removeAttribute('required');
                if (categoryWrap) categoryWrap.style.display = 'block';
                if (groupWrap) groupWrap.style.display = 'block';
                if (catSelect) catSelect.setAttribute('required', 'required');
                // Komisi Affiliate hanya untuk digital & tiket
                if (commissionWrap) commissionWrap.style.display = 'block';
                if (commissionInput) commissionInput.removeAttribute('required'); // tidak required, pakai toggle
            }

            if (val === 'ticket') {
                if (wrap) wrap.style.display = 'block';
                if (posterWrap) posterWrap.style.display = 'block';
                if (digitalCard) digitalCard.style.display = 'none';
                if (whitelabelCard) whitelabelCard.style.display = 'none';
                if (wlCheck) {
                    wlCheck.checked = false;
                    toggleWhitelabelFields(false);
                }
                if (extInput) extInput.removeAttribute('required');
                if (evType) toggleEventTypeFields(evType.value);
            } else if (val === 'external_link') {
                if (wrap) wrap.style.display = 'none';
                if (posterWrap) posterWrap.style.display = 'none';
                if (digitalCard) digitalCard.style.display = 'block';
                if (whitelabelCard) whitelabelCard.style.display = 'block';
                if (extInput) extInput.setAttribute('required', 'required');
            } else {
                // physical, makanan, service
                if (wrap) wrap.style.display = 'none';
                if (posterWrap) posterWrap.style.display = 'none';
                if (digitalCard) digitalCard.style.display = 'none';
                if (whitelabelCard) whitelabelCard.style.display = 'none';
                if (extInput) extInput.removeAttribute('required');
            }

            if (typeof updateVariantTypeExample === 'function') {
                updateVariantTypeExample(val);
            }
        }

        function handleBuyleCheckoutToggle(checked) {
            const val = document.getElementById('productTypeSelect')?.value;
            toggleProductTypeFields(val);
        }

        function handleAffiliateToggle(checked) {
            const rateWrap = document.getElementById('affiliateRateWrap');
            const rateInput = document.getElementById('affiliate_commission_rate');
            if (rateWrap) rateWrap.style.display = checked ? 'block' : 'none';
            // Jika dinonaktifkan, set nilai 0 agar backend tahu affiliate off
            if (rateInput) {
                if (!checked) {
                    rateInput.dataset.prevValue = rateInput.value;
                    rateInput.value = '0';
                } else {
                    rateInput.value = rateInput.dataset.prevValue || '10';
                }
            }
        }

        const initPType = document.getElementById('productTypeSelect');
        if (initPType) toggleProductTypeFields(initPType.value);
        // Init affiliate toggle state
        const initAffToggle = document.getElementById('affiliateCommissionToggle');
        if (initAffToggle) handleAffiliateToggle(initAffToggle.checked);

        // Smart Import Auto-fill handler
        document.addEventListener('DOMContentLoaded', function () {
            const itemStr = sessionStorage.getItem('smart_imported_item');
            if (!itemStr) return;

            try {
                const item = JSON.parse(itemStr);
                sessionStorage.removeItem('smart_imported_item');

                // Banner alert
                const alertHtml = `
                                    <div style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:16px; padding:1.1rem 1.4rem; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; color:#0f172a; font-size:0.85rem; font-weight:700; box-shadow:0 2px 10px rgba(0,0,0,0.03);">
                                        <div style="display:flex; align-items:center; gap:0.6rem;">
                                            <svg width="22" height="22" fill="none" stroke="#0f172a" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                            <span>Data berhasil diimpor otomatis via Smart Import! Silakan periksa dan sesuaikan data di bawah sebelum menyimpan.</span>
                                        </div>
                                    </div>
                                `;
                const formBody = document.getElementById('productForm');
                if (formBody) {
                    formBody.insertAdjacentHTML('afterbegin', alertHtml);
                }

                // Fill Name
                if (item.name) {
                    const nameInput = document.querySelector('input[name="name"]');
                    if (nameInput) nameInput.value = item.name;
                }

                // Fill Normal Price & Sale Price (Promo)
                if (item.price !== undefined && item.price !== null) {
                    const priceInput = document.getElementById('input_price');
                    if (priceInput) {
                        const priceVal = parseFloat(item.price) || 0;
                        if (priceVal > 0) priceInput.value = formatRupiah(priceVal.toString());
                    }
                }
                if (item.sale_price !== undefined && item.sale_price !== null) {
                    const salePriceInput = document.getElementById('input_sale_price');
                    if (salePriceInput) {
                        const saleVal = parseFloat(item.sale_price) || 0;
                        if (saleVal > 0) salePriceInput.value = formatRupiah(saleVal.toString());
                    }
                }
                const pIn = document.getElementById('input_price');
                const spIn = document.getElementById('input_sale_price');
                if (pIn && spIn && pIn.value && spIn.value) {
                    const pVal = parseFloat(pIn.value.replace(/[^\d]/g, '')) || 0;
                    const spVal = parseFloat(spIn.value.replace(/[^\d]/g, '')) || 0;
                    if (spVal > pVal && pVal > 0) {
                        pIn.value = formatRupiah(spVal.toString());
                        spIn.value = formatRupiah(pVal.toString());
                    }
                }

                // Fill Product Type
                if (item.product_type) {
                    const pTypeSelect = document.getElementById('productTypeSelect');
                    if (pTypeSelect) {
                        pTypeSelect.value = item.product_type;
                        toggleProductTypeFields(item.product_type);
                    }
                }

                // Fill Short Description & Description (Clean scripts/styles & "Loading contents")
                if (item.description) {
                    let sanitizedHtml = item.description
                        .replace(/<script\b[^>]*>[\s\S]*?<\/script>/gim, '')
                        .replace(/<style\b[^>]*>[\s\S]*?<\/style>/gim, '')
                        .replace(/<iframe\b[^>]*>[\s\S]*?<\/iframe>/gim, '')
                        .replace(/<object\b[^>]*>[\s\S]*?<\/object>/gim, '')
                        .replace(/<embed\b[^>]*>/gim, '')
                        .trim();

                    if (sanitizedHtml.toLowerCase().includes('loading contents')) {
                        sanitizedHtml = '';
                    }

                    if (sanitizedHtml) {
                        // Deep-clean: parse via DOMParser and strip all event handlers + dangerous attrs
                        const domParser = new DOMParser();
                        const doc = domParser.parseFromString(sanitizedHtml, 'text/html');
                        doc.body.querySelectorAll('*').forEach(el => {
                            // Remove all event handler attributes (onclick, onerror, onload, etc.)
                            [...el.attributes].forEach(attr => {
                                if (/^on/i.test(attr.name)) el.removeAttribute(attr.name);
                                // Remove javascript: href/src
                                if ((attr.name === 'href' || attr.name === 'src') && /^\s*javascript:/i.test(attr.value)) {
                                    el.removeAttribute(attr.name);
                                }
                                // Remove data: URIs from src (potential vector)
                                if (attr.name === 'src' && /^\s*data:/i.test(attr.value)) {
                                    el.removeAttribute(attr.name);
                                }
                            });
                        });
                        let formattedDesc = doc.body.innerHTML;

                        if (!formattedDesc.includes('<p>') && !formattedDesc.includes('<br>') && !formattedDesc.includes('<div>')) {
                            formattedDesc = formattedDesc.replace(/\r\n|\r|\n/g, '<br>');
                        }

                        const cleanDescText = sanitizedHtml.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                        const shortDescInput = document.querySelector('textarea[name="short_desc"]');
                        if (shortDescInput) {
                            shortDescInput.value = cleanDescText.substring(0, 160);
                        }

                        const richEd = document.querySelector('[contenteditable="true"]');
                        const haTextarea = document.querySelector('textarea[name="description"]');
                        if (richEd) richEd.innerHTML = formattedDesc;
                        if (haTextarea) haTextarea.value = formattedDesc;
                    }
                }

                // Leave Tautan Eksternal / Digital File Access EMPTY for creator to fill manually
                const extLinkInput = document.getElementById('externalLink');
                if (extLinkInput) extLinkInput.value = '';

                // Fill Image & Gallery URLs if scraped (max 6 photos)
                const allScrapedImages = (item.images && item.images.length > 0) ? item.images : (item.image ? [item.image] : []);
                const validImages = allScrapedImages.filter(img => img && !img.includes('buyle-placeholder.svg')).slice(0, 6);

                if (validImages.length > 0) {
                    // Set Primary Thumbnail
                    const mainImg = validImages[0];
                    const thumbImg = document.getElementById('thumbPreviewImg');
                    const thumbWrap = document.getElementById('thumbPreviewWrap');
                    if (thumbImg && thumbWrap) {
                        thumbImg.src = mainImg;
                        thumbWrap.style.display = 'block';
                    }
                    let scrapedImgInput = document.getElementById('scraped_image_url');
                    if (!scrapedImgInput) {
                        scrapedImgInput = document.createElement('input');
                        scrapedImgInput.type = 'hidden';
                        scrapedImgInput.name = 'scraped_image_url';
                        scrapedImgInput.id = 'scraped_image_url';
                        document.getElementById('productForm').appendChild(scrapedImgInput);
                    }
                    scrapedImgInput.value = mainImg;

                    // Render Gallery Visual Previews (up to 6 photos)
                    const galleryContainer = document.getElementById('galleryPreview');
                    if (galleryContainer) {
                        galleryContainer.innerHTML = '';
                        validImages.forEach((imgUrl, idx) => {
                            const imgBox = document.createElement('div');
                            imgBox.style.cssText = 'position:relative; width:90px; height:90px; border-radius:12px; overflow:hidden; border:2px solid #1eb349; box-shadow:0 2px 8px rgba(0,0,0,0.06);';
                            imgBox.innerHTML = `
                                                <img src="${imgUrl}" style="width:100%; height:100%; object-fit:cover; display:block;">
                                                <span style="position:absolute; bottom:4px; left:4px; background:rgba(15,23,42,0.75); color:#fff; font-size:9px; font-weight:700; padding:2px 5px; border-radius:6px; backdrop-filter:blur(4px);">Foto ${idx + 1}</span>
                                            `;
                            galleryContainer.appendChild(imgBox);
                        });
                    }

                    let scrapedGalleryInput = document.getElementById('scraped_gallery_urls');
                    if (!scrapedGalleryInput) {
                        scrapedGalleryInput = document.createElement('input');
                        scrapedGalleryInput.type = 'hidden';
                        scrapedGalleryInput.name = 'scraped_gallery_urls';
                        scrapedGalleryInput.id = 'scraped_gallery_urls';
                        document.getElementById('productForm').appendChild(scrapedGalleryInput);
                    }
                    scrapedGalleryInput.value = JSON.stringify(validImages);
                }

            } catch (e) {
                console.error('Smart Import autofill error:', e);
            }
        });
        // ── KYC Verification Check on Product Type ──
        let lastProductType = '{{ old('product_type', 'external_link') }}';
        const IS_CREATOR_VERIFIED = {{ auth()->user()?->isKycVerified() ? 'true' : 'false' }};

        function handleProductTypeSelectChange(selectEl) {
            const val = selectEl.value;
            if (val === 'ticket' && !IS_CREATOR_VERIFIED) {
                selectEl.value = lastProductType;
                window.location.href = 'https://buyle.id/creator/event-attendance?tab=settings';
                return;
            }
            lastProductType = val;
            toggleProductTypeFields(val);
        }

        // ── Video Link Panel Toggle & Preview ──
        function toggleVideoLinkPanel() {
            const panel = document.getElementById('videoLinkPanel');
            const chevron = document.getElementById('videoLinkChevron');
            if (!panel) return;
            const isOpen = panel.style.display !== 'none';
            panel.style.display = isOpen ? 'none' : 'block';
            if (chevron) chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
            // If opening and there's a value, trigger preview
            if (!isOpen) {
                const inp = document.getElementById('videoUrlInput');
                if (inp && inp.value.trim()) renderVideoPreview(inp.value.trim());
            }
        }

        let _videoDebounceTimer = null;
        function debounceVideoPreview(url) {
            clearTimeout(_videoDebounceTimer);
            _videoDebounceTimer = setTimeout(() => renderVideoPreview(url), 700);
        }

        function renderVideoPreview(url) {
            const box = document.getElementById('videoPreviewBox');
            if (!box) return;
            url = url.trim();
            if (!url) { box.style.display = 'none'; box.innerHTML = ''; return; }

            // YouTube
            const ytMatch = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts|live)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
            if (ytMatch) {
                box.innerHTML = `<div style="position:relative;width:100%;aspect-ratio:16/9;"><iframe src="https://www.youtube-nocookie.com/embed/${ytMatch[1]}?rel=0" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" allowfullscreen loading="lazy"></iframe></div>`;
                box.style.display = 'block';
                return;
            }

            // TikTok
            const ttMatch = url.match(/(?:tiktok\.com\/.*\/video\/|tiktok\.com\/v\/)(\d+)/i);
            if (ttMatch) {
                box.innerHTML = `<div style="display:flex;justify-content:center;"><div style="width:100%;max-width:320px;aspect-ratio:9/16;max-height:500px;"><iframe src="https://www.tiktok.com/player/v1/${ttMatch[1]}?music_info=1&description=1" style="width:100%;height:100%;border:none;display:block;" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" allowfullscreen loading="lazy"></iframe></div></div>`;
                box.style.display = 'block';
                return;
            }

            // Other URL - show link card
            box.innerHTML = `<a href="${url}" target="_blank" rel="noopener" style="display:flex;align-items:center;gap:0.75rem;padding:0.85rem 1rem;background:#1e293b;color:#e2e8f0;text-decoration:none;font-size:0.82rem;"><svg width="18" height="18" fill="none" stroke="#a5cf37" stroke-width="2" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg><span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${url}</span></a>`;
            box.style.display = 'block';
        }

        // Auto-open video panel if value exists on page load
        document.addEventListener('DOMContentLoaded', function() {
            const vidInp = document.getElementById('videoUrlInput');
            if (vidInp && vidInp.value.trim()) {
                const panel = document.getElementById('videoLinkPanel');
                const chevron = document.getElementById('videoLinkChevron');
                if (panel) panel.style.display = 'block';
                if (chevron) chevron.style.transform = 'rotate(180deg)';
                renderVideoPreview(vidInp.value.trim());
            }
        });

    </script>

    @include('creator.products.partials.kyc_modal')
@endsection