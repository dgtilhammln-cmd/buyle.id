{{-- ══════════════════════════════════════════════════════════════════════════
     TAB 4: KASIR LOKET OTS (ON-THE-SPOT POS) - BUYLE.ID TICKETING
     Theme: Light Minimalist, Montserrat, Uniform with tab=data, 100% Mobile Friendly
     ══════════════════════════════════════════════════════════════════════════ --}}
<div id="tab-ots" class="payout-tab-pane" style="display: {{ $activeTab === 'ots' ? 'block' : 'none' }}; font-family: 'Montserrat', sans-serif;">
    
    {{-- TOAST NOTIFICATION CONTAINER (Custom Mobile-Friendly Animated Pop-up) --}}
    <div id="otsToastContainer" style="position: fixed; top: 1.25rem; right: 1.25rem; z-index: 999999; display: flex; flex-direction: column; gap: 0.6rem; max-width: 380px; width: calc(100% - 2.5rem); pointer-events: none;"></div>
    
    {{-- 1. HEADER CARD (Uniform with tab=data: Filter & Ekspor Data) --}}
    <div class="prof-card" style="margin-bottom: 1.25rem;">
        <div class="prof-card-head attendance-card-head" style="background: #ffffff; padding: 0.9rem 1.25rem;">
            <div class="att-head-left">
                <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="2" y="4" width="20" height="16" rx="3" />
                    <line x1="2" y1="10" x2="22" y2="10" />
                    <circle cx="7" cy="15" r="1.5" />
                    <line x1="12" y1="15" x2="17" y2="15" />
                </svg>
                <span class="att-head-title" style="font-size: 0.92rem; font-weight: 700; color: #0f172a;">Kasir Loket Tiket OTS</span>
                <span class="att-head-extra" style="font-size: 0.8rem; font-weight: 500; color: #64748b;">Walk-In Point of Sale</span>
            </div>

            <div class="att-head-actions" style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <span style="font-size: 0.76rem; font-weight: 600; color: #64748b;">Pilih Event:</span>
                <div style="position: relative; display: inline-flex; align-items: center;">
                    <svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 0.65rem; pointer-events: none;">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <select id="otsEventSelector" class="filter-select" onchange="handleOtsEventSelect(this.value)"
                        style="height: 34px; padding-left: 2rem; padding-right: 1.75rem; font-size: 0.8rem; font-weight: 600; min-width: 220px; max-width: 300px; border: 1.5px solid #e2e8f0; border-radius: 10px; background: #ffffff;">
                        @foreach($events as $ev)
                            <option value="{{ $ev->id }}" 
                                data-name="{{ $ev->name }}"
                                data-price="{{ $ev->price }}"
                                data-ots-price="{{ $ev->effective_ots_price }}"
                                data-stock="{{ $ev->stock ?? 0 }}"
                                data-tax-rate="{{ $ev->tax_enabled ? $ev->ticket_tax_rate : 0 }}"
                                data-tax-name="{{ $ev->tax_enabled ? $ev->ticket_tax_name : '' }}"
                                {{ (isset($primaryEvent) && $primaryEvent && $primaryEvent->id === $ev->id) ? 'selected' : '' }}>
                                {{ $ev->name }} (Rp {{ number_format($ev->effective_ots_price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. 4 STAT CARDS REKONSILIASI SHIFT OTS (Clean Monochrome, 4 Columns, Uniform with tab=data) --}}
    <div class="stat-grid" style="margin-bottom: 1.25rem;">
        {{-- Card 1: Tiket OTS Terjual --}}
        <div class="stat-card">
            <div class="stat-head">
                <span class="stat-title">Tiket OTS Terjual</span>
                <div class="stat-icon-bubble" style="background: #f0fdf4; color: #16a34a; border: 1.5px solid #bbf7d0;">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z" />
                        <path d="M13 5v14" />
                    </svg>
                </div>
            </div>
            <div class="stat-val" id="otsStatTotalTickets" style="color: #0f172a;">
                {{ $otsStats['total_tickets'] ?? 0 }} <span style="font-size: 0.55em; font-weight: 600; color: #64748b;">Tiket</span>
            </div>
            <div class="stat-sub">
                <span style="color: #64748b; font-weight: 500;">Penjualan Langsung</span>
            </div>
        </div>

        {{-- Card 2: Total Penjualan OTS --}}
        <div class="stat-card">
            <div class="stat-head">
                <span class="stat-title">Total Penjualan OTS</span>
                <div class="stat-icon-bubble" style="background: #f0fdf4; color: #16a34a; border: 1.5px solid #bbf7d0;">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
            </div>
            <div class="stat-val" id="otsStatTotalRevenue" style="color: #0f172a;">
                Rp {{ number_format($otsStats['total_revenue'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="stat-sub">
                <span style="color: #16a34a; font-weight: 600;">Omzet Kasir OTS</span>
            </div>
        </div>

        {{-- Card 3: Tunai di Laci (Cash) --}}
        <div class="stat-card">
            <div class="stat-head">
                <span class="stat-title">Tunai di Laci</span>
                <div class="stat-icon-bubble" style="background: #f0fdf4; color: #16a34a; border: 1.5px solid #bbf7d0;">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="12" cy="12" r="2.5" />
                        <path d="M6 12h.01M18 12h.01" />
                    </svg>
                </div>
            </div>
            <div class="stat-val" id="otsStatTotalCash" style="color: #0f172a;">
                Rp {{ number_format($otsStats['total_cash'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="stat-sub">
                <span style="color: #64748b; font-weight: 500;">Fisik Kas Masuk</span>
            </div>
        </div>

        {{-- Card 4: Penerimaan QRIS --}}
        <div class="stat-card">
            <div class="stat-head">
                <span class="stat-title">Penerimaan QRIS</span>
                <div class="stat-icon-bubble" style="background: #f0fdf4; color: #16a34a; border: 1.5px solid #bbf7d0;">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" />
                        <rect x="14" y="3" width="7" height="7" rx="1.5" />
                        <rect x="3" y="14" width="7" height="7" rx="1.5" />
                        <rect x="14" y="14" width="4" height="4" rx="1" />
                        <line x1="20" y1="14" x2="20" y2="20" />
                    </svg>
                </div>
            </div>
            <div class="stat-val" id="otsStatTotalQris" style="color: #0f172a;">
                Rp {{ number_format($otsStats['total_qris'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="stat-sub">
                <span style="color: #64748b; font-weight: 500;">Non-Tunai / Rekening</span>
            </div>
        </div>
    </div>

    {{-- 3. WORKSPACE POS KASIR 2 KOLOM (Clean, Compact, Minimalist) --}}
    <div class="ots-workspace-grid" style="display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 1rem; align-items: start; margin-bottom: 1.25rem;">
        
        {{-- KOLOM KIRI: TIKET, QTY, FULFILLMENT, PEMBELI --}}
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            
            {{-- CARD 1: KATEGORI TIKET & JUMLAH --}}
            <div class="prof-card" style="margin-bottom: 0;">
                <div class="prof-card-head" style="padding: 0.8rem 1.15rem; background: #ffffff; border-bottom: 1.5px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                        <span style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">1. Pilih Tiket &amp; Jumlah</span>
                    </div>
                    <span id="otsStockBadge" class="badge-status badge-valid" style="font-size: 0.7rem; font-weight: 700; padding: 2px 8px;">
                        Tersisa: --
                    </span>
                </div>
                <div class="prof-card-body" style="padding: 1rem 1.15rem;">
                    {{-- Selected Ticket Row --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.65rem; padding: 0.75rem 0.9rem; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; margin-bottom: 0.85rem;">
                        <div>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #0f172a;" id="otsSelectedTicketTitle">
                                Memuat Tiket...
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem; margin-top: 0.2rem;">
                                <span style="font-size: 0.75rem; color: #94a3b8; text-decoration: line-through; display: none;" id="otsNormalPriceCrossed"></span>
                                <span style="font-size: 0.98rem; font-weight: 700; color: #15803d;" id="otsEffectivePriceDisplay">Rp 0</span>
                                <span style="font-size: 0.68rem; font-weight: 700; background: #dcfce7; color: #15803d; padding: 1px 6px; border-radius: 4px;">Harga OTS</span>
                            </div>
                        </div>
                        
                        {{-- Compact Quantity Stepper --}}
                        <div style="display: flex; align-items: center; gap: 0.3rem; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 2px 4px;">
                            <button type="button" onclick="adjustOtsQty(-1)"
                                style="width: 30px; height: 30px; border-radius: 7px; border: none; background: #f1f5f9; color: #334155; font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s ease;">
                                -
                            </button>
                            <input type="number" id="otsQtyInput" value="1" min="1" max="50" oninput="calculateOtsTotal()"
                                style="width: 40px; height: 30px; border: none; text-align: center; font-size: 0.92rem; font-weight: 700; color: #0f172a; outline: none; background: transparent;">
                            <button type="button" onclick="adjustOtsQty(1)"
                                style="width: 30px; height: 30px; border-radius: 7px; border: none; background: #1eb349; color: #ffffff; font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s ease;">
                                +
                            </button>
                        </div>
                    </div>

                    {{-- Quick Qty Pills --}}
                    <div style="display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                        <span style="font-size: 0.72rem; font-weight: 600; color: #64748b; margin-right: 2px;">Pilihan Cepat:</span>
                        <button type="button" onclick="setOtsQty(1)" style="background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 7px; cursor: pointer; transition: all 0.15s ease;">1 Tiket</button>
                        <button type="button" onclick="setOtsQty(2)" style="background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 7px; cursor: pointer; transition: all 0.15s ease;">2 Tiket</button>
                        <button type="button" onclick="setOtsQty(3)" style="background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 7px; cursor: pointer; transition: all 0.15s ease;">3 Tiket</button>
                        <button type="button" onclick="setOtsQty(5)" style="background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 7px; cursor: pointer; transition: all 0.15s ease;">5 Tiket</button>
                        <button type="button" onclick="setOtsQty(10)" style="background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 7px; cursor: pointer; transition: all 0.15s ease;">10 Tiket</button>
                    </div>
                </div>
            </div>

            {{-- CARD 2: MODE MASUK VENUE (FULFILLMENT) --}}
            <div class="prof-card" style="margin-bottom: 0;">
                <div class="prof-card-head" style="padding: 0.8rem 1.15rem; background: #ffffff; border-bottom: 1.5px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <polyline points="9 12 11 14 15 10"/>
                        </svg>
                        <span style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">2. Mode Masuk Venue (Fulfillment)</span>
                    </div>
                </div>
                <div class="prof-card-body" style="padding: 1rem 1.15rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem;">
                        {{-- Mode A: Auto Check-In Gelang Langsung --}}
                        <label id="labelFulfillAuto"
                            style="display: flex; align-items: flex-start; gap: 0.65rem; padding: 0.75rem 0.85rem; border: 1.5px solid #1eb349; background: #f0fdf4; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;">
                            <input type="radio" name="ots_fulfillment" value="auto_checkin" checked onchange="handleFulfillmentChange(this.value)" style="margin-top: 3px; accent-color: #1eb349;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.35rem;">
                                    <svg width="14" height="14" fill="none" stroke="#15803d" stroke-width="2.2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9"/>
                                        <polyline points="9 12 11 14 15 10"/>
                                    </svg>
                                    <strong style="font-size: 0.82rem; color: #0f172a;">Auto Check-In Gelang</strong>
                                </div>
                                <div style="font-size: 0.71rem; color: #64748b; margin-top: 2px; line-height: 1.35;">
                                    Tiket langsung berstatus "Hadir", pasang gelang fisik ke pembeli.
                                </div>
                            </div>
                        </label>

                        {{-- Mode B: Cetak Struk Tiket QR (Scan di Gate) --}}
                        <label id="labelFulfillTicket"
                            style="display: flex; align-items: flex-start; gap: 0.65rem; padding: 0.75rem 0.85rem; border: 1.5px solid #e2e8f0; background: #ffffff; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;">
                            <input type="radio" name="ots_fulfillment" value="issue_ticket" onchange="handleFulfillmentChange(this.value)" style="margin-top: 3px; accent-color: #1eb349;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.35rem;">
                                    <svg width="14" height="14" fill="none" stroke="#64748b" stroke-width="2.2" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                                    </svg>
                                    <strong style="font-size: 0.82rem; color: #0f172a;">Cetak Struk Tiket QR</strong>
                                </div>
                                <div style="font-size: 0.71rem; color: #64748b; margin-top: 2px; line-height: 1.35;">
                                    Tiket berstatus "Valid", pengunjung tetap pindai QR di gate masuk.
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- CARD 3: DATA PEMBELI (KILAT) --}}
            <div class="prof-card" style="margin-bottom: 0;">
                <div class="prof-card-head" style="padding: 0.8rem 1.15rem; background: #ffffff; border-bottom: 1.5px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">3. Data Pembeli (Kilat)</span>
                    </div>
                </div>
                <div class="prof-card-body" style="padding: 1rem 1.15rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.3rem; color: #334155;">
                                Nama Lengkap Pembeli <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <svg width="14" height="14" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 0.65rem; pointer-events: none;">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <input type="text" id="otsBuyerName" placeholder="Contoh: Rian Pratama" required
                                    style="width: 100%; height: 36px; padding-left: 2rem; padding-right: 0.75rem; font-size: 0.82rem; font-weight: 600; border: 1.5px solid #e2e8f0; border-radius: 9px; outline: none; box-sizing: border-box; font-family: 'Montserrat', sans-serif;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.3rem; color: #334155;">
                                Nomor WhatsApp (Kirim E-Tiket)
                            </label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 0.65rem; pointer-events: none;">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                <input type="tel" id="otsBuyerPhone" placeholder="08123456789"
                                    style="width: 100%; height: 36px; padding-left: 2rem; padding-right: 0.75rem; font-size: 0.82rem; font-weight: 600; border: 1.5px solid #e2e8f0; border-radius: 9px; outline: none; box-sizing: border-box; font-family: 'Montserrat', sans-serif;">
                            </div>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 0.65rem;">
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.3rem; color: #334155;">
                                Email Pembeli (Kirim E-Tiket Otomatis)
                            </label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <svg width="14" height="14" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 0.65rem; pointer-events: none;">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <input type="email" id="otsBuyerEmail" placeholder="nama@email.com"
                                    style="width: 100%; height: 36px; padding-left: 2rem; padding-right: 0.75rem; font-size: 0.82rem; font-weight: 600; border: 1.5px solid #e2e8f0; border-radius: 9px; outline: none; box-sizing: border-box; font-family: 'Montserrat', sans-serif;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.3rem; color: #334155;">
                                Catatan Meja / Khusus (Opsional)
                            </label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <svg width="14" height="14" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 0.65rem; pointer-events: none;">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                </svg>
                                <input type="text" id="otsNotes" placeholder="Misal: Rombongan, VIP, dll"
                                    style="width: 100%; height: 36px; padding-left: 2rem; padding-right: 0.75rem; font-size: 0.82rem; font-weight: 600; border: 1.5px solid #e2e8f0; border-radius: 9px; outline: none; box-sizing: border-box; font-family: 'Montserrat', sans-serif;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN: RINCIAN TAGIHAN & PEMBAYARAN KASIR (Clean White Minimalist) --}}
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            
            <div class="prof-card" style="margin-bottom: 0;">
                <div class="prof-card-head" style="padding: 0.8rem 1.15rem; background: #ffffff; border-bottom: 1.5px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="17" height="17" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <line x1="2" y1="10" x2="22" y2="10"/>
                        </svg>
                        <span style="font-weight: 700; font-size: 0.88rem; color: #0f172a;">4. Tagihan &amp; Pembayaran</span>
                    </div>
                    <span class="badge-status" style="background: #f1f5f9; color: #475569; font-size: 0.7rem; padding: 2px 8px; font-weight: 600;">
                        Kasir: {{ auth()->user()->name }}
                    </span>
                </div>

                <div class="prof-card-body" style="padding: 1rem 1.15rem;">
                    
                    {{-- Rincian Tagihan --}}
                    <div style="border-bottom: 1.5px dashed #e2e8f0; padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #64748b; margin-bottom: 0.35rem;">
                            <span>Subtotal (<span id="otsSummaryQty">1</span> Tiket)</span>
                            <span style="font-weight: 600; color: #0f172a;" id="otsSummarySubtotal">Rp 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #64748b; margin-bottom: 0.35rem;" id="otsTaxRow">
                            <span id="otsTaxLabel">Pajak Daerah (10%)</span>
                            <span style="font-weight: 600; color: #0f172a;" id="otsSummaryTax">Rp 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid #f1f5f9;">
                            <span style="font-size: 0.88rem; font-weight: 700; color: #0f172a;">Total Tagihan</span>
                            <strong style="font-size: 1.15rem; font-weight: 800; color: #15803d; letter-spacing: -0.3px;" id="otsGrandTotalDisplay">
                                Rp 0
                            </strong>
                        </div>
                    </div>

                    {{-- Pilihan Metode Pembayaran (Clean Buttons without Emojis) --}}
                    <div style="margin-bottom: 0.85rem;">
                        <label style="display: block; font-size: 0.74rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.04em;">
                            Metode Pembayaran:
                        </label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                            {{-- Button Cash --}}
                            <button type="button" id="btnPayCash" onclick="selectOtsPayment('cash')"
                                style="height: 38px; border-radius: 9px; border: 1.5px solid #1eb349; background: #f0fdf4; color: #15803d; font-size: 0.8rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.4rem; transition: all 0.15s ease;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <rect x="2" y="6" width="20" height="12" rx="2" />
                                    <circle cx="12" cy="12" r="2.5" />
                                    <path d="M6 12h.01M18 12h.01" />
                                </svg>
                                <span>Tunai (Cash)</span>
                            </button>

                            {{-- Button QRIS --}}
                            <button type="button" id="btnPayQris" onclick="selectOtsPayment('qris')"
                                style="height: 38px; border-radius: 9px; border: 1.5px solid #e2e8f0; background: #ffffff; color: #64748b; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.4rem; transition: all 0.15s ease;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="7" height="7" rx="1.5" />
                                    <rect x="14" y="3" width="7" height="7" rx="1.5" />
                                    <rect x="3" y="14" width="7" height="7" rx="1.5" />
                                    <rect x="14" y="14" width="4" height="4" rx="1" />
                                    <line x1="20" y1="14" x2="20" y2="20" />
                                </svg>
                                <span>QRIS Dinamis</span>
                            </button>
                        </div>
                    </div>

                    {{-- Panel Cash / Uang Tunai --}}
                    <div id="otsCashPanel" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.85rem; margin-bottom: 0.85rem;">
                        <label style="display: block; font-size: 0.74rem; font-weight: 600; color: #475569; margin-bottom: 0.3rem;">
                            Nominal Uang Tunai Diterima:
                        </label>
                        <div style="position: relative; display: flex; align-items: center; margin-bottom: 0.45rem;">
                            <span style="position: absolute; left: 0.75rem; font-size: 0.8rem; font-weight: 700; color: #94a3b8; pointer-events: none;">Rp</span>
                            <input type="number" id="otsCashReceivedInput" placeholder="0" oninput="calculateOtsChange()"
                                style="width: 100%; height: 36px; padding-left: 2.2rem; padding-right: 0.75rem; font-size: 0.92rem; font-weight: 700; color: #0f172a; border: 1.5px solid #cbd5e1; border-radius: 8px; outline: none; background: #ffffff;">
                        </div>

                        {{-- Quick Cash Buttons --}}
                        <div style="display: flex; align-items: center; gap: 0.3rem; flex-wrap: wrap; margin-bottom: 0.6rem;">
                            <button type="button" onclick="setExactCash()" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669; font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 6px; cursor: pointer;">Uang Pas</button>
                            <button type="button" onclick="setQuickCash(50000)" style="background: #ffffff; border: 1px solid #e2e8f0; color: #475569; font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 6px; cursor: pointer;">50rb</button>
                            <button type="button" onclick="setQuickCash(100000)" style="background: #ffffff; border: 1px solid #e2e8f0; color: #475569; font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 6px; cursor: pointer;">100rb</button>
                            <button type="button" onclick="setQuickCash(150000)" style="background: #ffffff; border: 1px solid #e2e8f0; color: #475569; font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 6px; cursor: pointer;">150rb</button>
                            <button type="button" onclick="setQuickCash(200000)" style="background: #ffffff; border: 1px solid #e2e8f0; color: #475569; font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 6px; cursor: pointer;">200rb</button>
                        </div>

                        {{-- Kembalian Display Box --}}
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.45rem 0.65rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px;">
                            <span style="font-size: 0.78rem; font-weight: 600; color: #475569;">Kembalian:</span>
                            <span id="otsChangeDisplay" style="font-size: 0.98rem; font-weight: 700; color: #15803d;">Rp 0</span>
                        </div>
                    </div>

                    {{-- Panel QRIS --}}
                    <div id="otsQrisPanel" style="display: none; background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 10px; padding: 0.85rem; text-align: center; margin-bottom: 0.85rem;">
                        <div style="font-size: 0.78rem; font-weight: 700; color: #6b21a8; margin-bottom: 0.2rem; display: flex; align-items: center; justify-content: center; gap: 0.35rem;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1.5" />
                                <rect x="14" y="3" width="7" height="7" rx="1.5" />
                                <rect x="3" y="14" width="7" height="7" rx="1.5" />
                                <rect x="14" y="14" width="4" height="4" rx="1" />
                            </svg>
                            <span>Pindai QRIS Pembeli</span>
                        </div>
                        <div style="font-size: 0.71rem; color: #7e22ce; margin-bottom: 0.6rem;">
                            Tunjukkan kode QRIS event ke pembeli. Pastikan bukti bayar tertera di aplikasi m-Banking.
                        </div>
                        <div style="display: inline-block; padding: 6px; background: #ffffff; border: 1px solid #d8b4fe; border-radius: 8px; margin-bottom: 0.35rem;">
                            <svg width="52" height="52" fill="none" stroke="#6b21a8" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7"/>
                                <rect x="14" y="3" width="7" height="7"/>
                                <rect x="3" y="14" width="7" height="7"/>
                                <rect x="14" y="14" width="3" height="3"/>
                                <line x1="20" y1="14" x2="20" y2="20"/>
                                <line x1="17" y1="20" x2="20" y2="20"/>
                            </svg>
                        </div>
                        <div>
                            <span style="font-size: 0.68rem; font-weight: 700; color: #166534; background: #dcfce7; padding: 2px 8px; border-radius: 999px;">
                                Dikonfirmasi Kasir Loket
                            </span>
                        </div>
                    </div>

                    {{-- Tombol Proses Transaksi OTS --}}
                    <button type="button" id="otsSubmitBtn" onclick="submitOtsOrder()"
                        style="width: 100%; height: 42px; border-radius: 10px; border: none; background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color: #ffffff; font-size: 0.84rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.45rem; box-shadow: 0 3px 12px rgba(30,179,73,0.25); font-family: 'Montserrat', sans-serif; transition: all 0.2s ease;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        <span>Proses &amp; Terbitkan Tiket OTS</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- 4. TABEL 15 TRANSAKSI OTS TERAKHIR (Uniform Table Styling as tab=data) --}}
    <div class="prof-card" style="margin-bottom: 1.5rem;">
        <div class="prof-card-head attendance-card-head" style="background: #ffffff; padding: 0.85rem 1.25rem;">
            <div class="att-head-left">
                <svg width="17" height="17" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span class="att-head-title" style="font-size: 0.88rem; font-weight: 700; color: #0f172a;">Riwayat Transaksi Loket OTS</span>
                <span class="att-head-extra" style="font-size: 0.8rem; font-weight: 500; color: #64748b;">15 Transaksi Terakhir Hari Ini</span>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="payout-table" style="width: 100%; border-collapse: collapse; font-size: 0.78rem; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0; color: #475569; font-weight: 700;">
                        <th style="padding: 0.7rem 0.9rem;">Waktu &amp; ID Pesanan</th>
                        <th style="padding: 0.7rem 0.9rem;">Nama Pembeli</th>
                        <th style="padding: 0.7rem 0.9rem;">Event &amp; Qty</th>
                        <th style="padding: 0.7rem 0.9rem;">Metode Bayar</th>
                        <th style="padding: 0.7rem 0.9rem;">Total Tagihan</th>
                        <th style="padding: 0.7rem 0.9rem; text-align: right;">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody id="otsRecentTableBody">
                    @forelse($otsOrders as $ord)
                        @php
                            $addr = $ord->shipping_address ?? [];
                            $firstItem = $ord->items->first();
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.7rem 0.9rem;">
                                <div style="font-weight: 700; color: #0f172a;">{{ $ord->order_number }}</div>
                                <div style="font-size: 0.7rem; color: #94a3b8;">{{ $ord->created_at->format('H:i · d M Y') }}</div>
                            </td>
                            <td style="padding: 0.7rem 0.9rem;">
                                <div style="font-weight: 600; color: #1e293b;">{{ $addr['receiver_name'] ?? 'Pembeli OTS' }}</div>
                                <div style="font-size: 0.7rem; color: #64748b;">{{ $addr['phone'] ?? '-' }}</div>
                            </td>
                            <td style="padding: 0.7rem 0.9rem;">
                                <div style="font-weight: 600; color: #0f172a;">{{ $firstItem?->product?->name ?? 'Tiket Event' }}</div>
                                <div style="font-size: 0.7rem; color: #15803d; font-weight: 700;">{{ $firstItem?->qty ?? 1 }} Tiket</div>
                            </td>
                            <td style="padding: 0.7rem 0.9rem;">
                                @if(($addr['payment_method'] ?? 'cash') === 'cash')
                                    <span class="badge-status" style="background: #fefce8; color: #854d0e; font-size: 0.7rem; font-weight: 700; padding: 2px 7px; border-radius: 6px; display: inline-flex; align-items: center; gap: 3px;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                                        <span>Cash</span>
                                    </span>
                                @else
                                    <span class="badge-status" style="background: #faf5ff; color: #7e22ce; font-size: 0.7rem; font-weight: 700; padding: 2px 7px; border-radius: 6px; display: inline-flex; align-items: center; gap: 3px;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                        <span>QRIS</span>
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 0.7rem 0.9rem;">
                                <strong style="color: #15803d; font-size: 0.84rem;">Rp {{ number_format($ord->total, 0, ',', '.') }}</strong>
                            </td>
                            <td style="padding: 0.7rem 0.9rem; text-align: right;">
                                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.35rem;">
                                    <button type="button" onclick="reprintOtsOrder('{{ $ord->order_number }}', '{{ addslashes($firstItem?->product?->name ?? '') }}', '{{ addslashes($addr['receiver_name'] ?? '') }}', {{ $firstItem?->qty ?? 1 }}, {{ $ord->total }}, '{{ $addr['payment_method'] ?? 'cash' }}')"
                                        style="background: #f8fafc; border: 1px solid #e2e8f0; color: #334155; font-size: 0.72rem; font-weight: 600; padding: 0.25rem 0.55rem; border-radius: 7px; cursor: pointer; display: inline-flex; align-items: center; gap: 3px;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                        <span>Struk</span>
                                    </button>
                                    @if(!empty($addr['phone']))
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $addr['phone']) }}?text={{ rawurlencode('Halo ' . ($addr['receiver_name'] ?? '') . ', ini e-tiket resmi OTS BuyleTix Anda untuk pesanan ' . $ord->order_number) }}" target="_blank" rel="noopener"
                                            style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-size: 0.72rem; font-weight: 600; padding: 0.25rem 0.55rem; border-radius: 7px; text-decoration: none; display: inline-flex; align-items: center; gap: 3px;">
                                            <svg width="12" height="12" fill="none" stroke="#16a34a" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                            <span>WA</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 1.75rem; text-align: center; color: #94a3b8; font-size: 0.8rem;">
                                Belum ada transaksi tiket OTS hari ini. Layani pembeli walk-in pertama melalui form di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ════ MODAL SUKSES TRANSAKSI OTS (Clean, Minimalist, Light Theme) ════ --}}
<div id="otsReceiptModal" class="scanner-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 1rem; box-sizing: border-box;">
    <div style="background: #ffffff; border-radius: 18px; max-width: 440px; width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.18); overflow: hidden; border: 1.5px solid #e2e8f0; animation: modalPop 0.25s ease;">
        
        {{-- Modal Head --}}
        <div style="background: #ffffff; padding: 1.15rem 1.25rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 0.6rem;">
                <div style="width: 34px; height: 34px; border-radius: 50%; background: #f0fdf4; border: 1.5px solid #bbf7d0; display: flex; align-items: center; justify-content: center; color: #16a34a; flex-shrink: 0;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 0.95rem; font-weight: 700; margin: 0; color: #0f172a;">Transaksi Berhasil</h3>
                    <p style="font-size: 0.72rem; color: #64748b; margin: 1px 0 0 0;" id="otsModalOrderNumber">OTS-XXXXXX</p>
                </div>
            </div>
            
            <button type="button" onclick="closeOtsModal()" style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; width: 26px; height: 26px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1rem;">&times;</button>
        </div>

        {{-- Modal Body --}}
        <div style="padding: 1.15rem 1.25rem; max-height: 72vh; overflow-y: auto;">
            
            {{-- Status Badge --}}
            <div id="otsModalStatusBadge" style="text-align: center; margin-bottom: 0.5rem;">
                <span style="font-size: 0.72rem; font-weight: 700; background: #dcfce7; color: #166534; padding: 3px 10px; border-radius: 999px; display: inline-flex; align-items: center; gap: 4px;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Auto Check-In Sukses (Langsung Hadir)</span>
                </span>
            </div>

            {{-- Email Delivery Confirmation Badge --}}
            <div id="otsModalEmailBadge" style="display: none; align-items: center; justify-content: center; gap: 0.35rem; font-size: 0.72rem; font-weight: 700; color: #15803d; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 4px 10px; border-radius: 8px; margin-bottom: 0.85rem; text-align: center;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <span>E-Tiket resmi telah dikirim ke email pembeli</span>
            </div>

            {{-- Order Summary List --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.75rem 0.85rem; margin-bottom: 0.85rem; font-size: 0.78rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem;">
                    <span style="color: #64748b;">Event:</span>
                    <strong style="color: #0f172a;" id="otsModalEventName">-</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem;">
                    <span style="color: #64748b;">Pembeli:</span>
                    <strong style="color: #0f172a;" id="otsModalBuyerName">-</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem;">
                    <span style="color: #64748b;">Jumlah:</span>
                    <strong style="color: #15803d;" id="otsModalQty">1 Tiket</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem; border-top: 1px dashed #cbd5e1; padding-top: 0.3rem;">
                    <span style="color: #64748b;">Total Bayar:</span>
                    <strong style="color: #15803d; font-size: 0.88rem;" id="otsModalTotal">Rp 0</strong>
                </div>
                <div style="display: flex; justify-content: space-between;" id="otsModalChangeRow">
                    <span style="color: #64748b;">Kembalian:</span>
                    <strong style="color: #0f172a;" id="otsModalChange">Rp 0</strong>
                </div>
            </div>

            {{-- Ticket Passes QR List --}}
            <div style="margin-bottom: 0.85rem;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #334155; margin-bottom: 0.45rem;">
                    Daftar Kode &amp; QR Tiket Terbit:
                </div>
                <div id="otsModalTicketList" style="display: flex; flex-direction: column; gap: 0.5rem;">
                    {{-- Injected via JS --}}
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="display: flex; flex-direction: column; gap: 0.45rem;">
                <button type="button" onclick="printThermalReceipt()"
                    style="width: 100%; height: 38px; border-radius: 9px; border: 1.5px solid #1eb349; background: #f0fdf4; color: #166534; font-size: 0.82rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.4rem; font-family: 'Montserrat', sans-serif;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    <span>Cetak Struk Thermal (58mm/80mm)</span>
                </button>

                <a id="otsModalWaBtn" href="#" target="_blank" rel="noopener"
                    style="display: none; width: 100%; height: 38px; border-radius: 9px; border: none; background: #25D366; color: #ffffff; font-size: 0.82rem; font-weight: 700; text-decoration: none; align-items: center; justify-content: center; gap: 0.4rem; font-family: 'Montserrat', sans-serif; box-sizing: border-box;">
                    <svg width="15" height="15" fill="none" stroke="#ffffff" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>Kirim E-Tiket ke WhatsApp Pembeli</span>
                </a>

                <button type="button" onclick="closeOtsModal()"
                    style="width: 100%; height: 38px; border-radius: 9px; border: 1px solid #e2e8f0; background: #ffffff; color: #475569; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.35rem; font-family: 'Montserrat', sans-serif;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                    <span>Layani Pembeli Berikutnya</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ════ PRINTABLE THERMAL RECEIPT TEMPLATE (Hidden from screen, visible on window.print) ════ --}}
<div id="thermalReceiptPrint" style="display: none;">
    <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 6px; margin-bottom: 6px;">
        <div style="font-weight: bold; font-size: 13px;">{{ config('app.name', 'BuyleTix') }}</div>
        <div style="font-size: 10px;">LOKET TIKET RESMI (OTS)</div>
        <div style="font-size: 9px;" id="thPrintEventName">Nama Event</div>
        <div style="font-size: 9px;" id="thPrintDate">Tanggal Transaksi</div>
    </div>
    <div style="font-size: 9px; margin-bottom: 6px; border-bottom: 1px dashed #000; padding-bottom: 6px;">
        <div>Order: <span id="thPrintOrder">OTS-XXXX</span></div>
        <div>Kasir: <span id="thPrintCashier">{{ auth()->user()->name }}</span></div>
        <div>Nama : <span id="thPrintBuyer">Pembeli</span></div>
        <div>Metode: <span id="thPrintPayment">TUNAI</span></div>
    </div>
    <div style="font-size: 9px; margin-bottom: 6px; border-bottom: 1px dashed #000; padding-bottom: 6px;">
        <div style="display: flex; justify-content: space-between;">
            <span>Tiket OTS (<span id="thPrintQty">1</span>x)</span>
            <span id="thPrintSubtotal">Rp 0</span>
        </div>
        <div style="display: flex; justify-content: space-between;" id="thPrintTaxRow">
            <span>Pajak:</span>
            <span id="thPrintTax">Rp 0</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 11px; margin-top: 3px;">
            <span>TOTAL:</span>
            <span id="thPrintTotal">Rp 0</span>
        </div>
        <div style="display: flex; justify-content: space-between;" id="thPrintCashRow">
            <span>Bayar Tunai:</span>
            <span id="thPrintCash">Rp 0</span>
        </div>
        <div style="display: flex; justify-content: space-between;" id="thPrintChangeRow">
            <span>Kembalian:</span>
            <span id="thPrintChange">Rp 0</span>
        </div>
    </div>
    <div id="thPrintTicketsContainer" style="text-align: center; padding-top: 4px;">
        {{-- Injected dynamically --}}
    </div>
    <div style="text-align: center; font-size: 8px; margin-top: 8px; border-top: 1px dashed #000; padding-top: 6px;">
        <div>Terima kasih atas kunjungan Anda!</div>
        <div>Simpan struk ini sebagai bukti resmi.</div>
        <div style="margin-top: 2px;">Powered by BuyleTix</div>
    </div>
</div>

{{-- ════ OTS THERMAL PRINT STYLING & RESPONSIVE BREAKPOINT ════ --}}
<style>
/* Enforce 4-Column Balanced Grid on Desktop (Tab Data Uniformity, No Empty 5th Space) */
#tab-ots .stat-grid {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 0.85rem !important;
}

@keyframes otsSlideIn {
    from {
        opacity: 0;
        transform: translateY(-12px) scale(0.96);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media print {
    body * {
        visibility: hidden !important;
    }
    #thermalReceiptPrint,
    #thermalReceiptPrint * {
        visibility: visible !important;
    }
    #thermalReceiptPrint {
        display: block !important;
        position: fixed !important;
        left: 0 !important;
        top: 0 !important;
        width: 58mm !important;
        max-width: 80mm !important;
        padding: 4mm !important;
        font-family: 'Courier New', Courier, monospace !important;
        font-size: 11px !important;
        line-height: 1.3 !important;
        color: #000000 !important;
        background: #ffffff !important;
    }
}
@media (max-width: 991px) {
    #tab-ots .stat-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.65rem !important;
    }
    .ots-workspace-grid {
        grid-template-columns: 1fr !important;
    }
}
@media (max-width: 575px) {
    #tab-ots .stat-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.5rem !important;
    }
    #tab-ots .stat-card {
        padding: 0.75rem 0.85rem !important;
    }
    #tab-ots .stat-val {
        font-size: 1.15rem !important;
    }
}
</style>

{{-- ════ OTS JAVASCRIPT ENGINE ════ --}}
<script>
    let currentOtsData = {
        productId: null,
        eventName: '',
        price: 0,
        otsPrice: 0,
        taxRate: 0,
        taxName: '',
        stock: 0,
        qty: 1,
        paymentMethod: 'cash',
        fulfillment: 'auto_checkin',
        lastResult: null
    };

    function initOtsEngine() {
        const selector = document.getElementById('otsEventSelector');
        if (selector && selector.value) {
            handleOtsEventSelect(selector.value);
        }
    }

    function handleOtsEventSelect(prodId) {
        const selector = document.getElementById('otsEventSelector');
        if (!selector) return;
        const opt = selector.options[selector.selectedIndex];
        if (!opt) return;

        currentOtsData.productId = prodId;
        currentOtsData.eventName = opt.getAttribute('data-name') || '';
        currentOtsData.price = parseFloat(opt.getAttribute('data-price') || 0);
        currentOtsData.otsPrice = parseFloat(opt.getAttribute('data-ots-price') || currentOtsData.price);
        currentOtsData.stock = parseInt(opt.getAttribute('data-stock') || 0);
        currentOtsData.taxRate = parseFloat(opt.getAttribute('data-tax-rate') || 0);
        currentOtsData.taxName = opt.getAttribute('data-tax-name') || 'Pajak Tiket';

        const titleEl = document.getElementById('otsSelectedTicketTitle');
        if (titleEl) titleEl.innerText = currentOtsData.eventName;

        const effPriceEl = document.getElementById('otsEffectivePriceDisplay');
        if (effPriceEl) effPriceEl.innerText = 'Rp ' + currentOtsData.otsPrice.toLocaleString('id-ID');

        const normalCrossed = document.getElementById('otsNormalPriceCrossed');
        if (normalCrossed) {
            if (currentOtsData.otsPrice !== currentOtsData.price && currentOtsData.price > 0) {
                normalCrossed.innerText = 'Rp ' + currentOtsData.price.toLocaleString('id-ID');
                normalCrossed.style.display = 'inline';
            } else {
                normalCrossed.style.display = 'none';
            }
        }

        const stockBadge = document.getElementById('otsStockBadge');
        if (stockBadge) {
            stockBadge.innerText = 'Tersisa: ' + currentOtsData.stock + ' Tiket';
        }

        calculateOtsTotal();
    }

    function adjustOtsQty(delta) {
        const inp = document.getElementById('otsQtyInput');
        if (!inp) return;
        let val = parseInt(inp.value || 1) + delta;
        if (val < 1) val = 1;
        if (val > 50) val = 50;
        inp.value = val;
        calculateOtsTotal();
    }

    function setOtsQty(qty) {
        const inp = document.getElementById('otsQtyInput');
        if (!inp) return;
        inp.value = qty;
        calculateOtsTotal();
    }

    function handleFulfillmentChange(val) {
        currentOtsData.fulfillment = val;
        const lblAuto = document.getElementById('labelFulfillAuto');
        const lblTicket = document.getElementById('labelFulfillTicket');

        if (val === 'auto_checkin') {
            if (lblAuto) {
                lblAuto.style.borderColor = '#1eb349';
                lblAuto.style.backgroundColor = '#f0fdf4';
            }
            if (lblTicket) {
                lblTicket.style.borderColor = '#e2e8f0';
                lblTicket.style.backgroundColor = '#ffffff';
            }
        } else {
            if (lblAuto) {
                lblAuto.style.borderColor = '#e2e8f0';
                lblAuto.style.backgroundColor = '#ffffff';
            }
            if (lblTicket) {
                lblTicket.style.borderColor = '#1eb349';
                lblTicket.style.backgroundColor = '#f0fdf4';
            }
        }
    }

    function selectOtsPayment(method) {
        currentOtsData.paymentMethod = method;
        const btnCash = document.getElementById('btnPayCash');
        const btnQris = document.getElementById('btnPayQris');
        const cashPanel = document.getElementById('otsCashPanel');
        const qrisPanel = document.getElementById('otsQrisPanel');

        if (method === 'cash') {
            if (btnCash) {
                btnCash.style.borderColor = '#1eb349';
                btnCash.style.backgroundColor = '#f0fdf4';
                btnCash.style.color = '#15803d';
                btnCash.style.fontWeight = '700';
            }
            if (btnQris) {
                btnQris.style.borderColor = '#e2e8f0';
                btnQris.style.backgroundColor = '#ffffff';
                btnQris.style.color = '#64748b';
                btnQris.style.fontWeight = '600';
            }
            if (cashPanel) cashPanel.style.display = 'block';
            if (qrisPanel) qrisPanel.style.display = 'none';
            calculateOtsChange();
        } else {
            if (btnQris) {
                btnQris.style.borderColor = '#9333ea';
                btnQris.style.backgroundColor = '#faf5ff';
                btnQris.style.color = '#7e22ce';
                btnQris.style.fontWeight = '700';
            }
            if (btnCash) {
                btnCash.style.borderColor = '#e2e8f0';
                btnCash.style.backgroundColor = '#ffffff';
                btnCash.style.color = '#64748b';
                btnCash.style.fontWeight = '600';
            }
            if (cashPanel) cashPanel.style.display = 'none';
            if (qrisPanel) qrisPanel.style.display = 'block';
        }
    }

    function calculateOtsTotal() {
        const qtyInp = document.getElementById('otsQtyInput');
        const qty = parseInt(qtyInp ? qtyInp.value : 1) || 1;
        currentOtsData.qty = qty;

        const subtotal = currentOtsData.otsPrice * qty;
        let tax = 0;
        if (currentOtsData.taxRate > 0) {
            tax = Math.round(subtotal * (currentOtsData.taxRate / 100));
        }
        const grandTotal = subtotal + tax;

        const summaryQty = document.getElementById('otsSummaryQty');
        if (summaryQty) summaryQty.innerText = qty;

        const summarySub = document.getElementById('otsSummarySubtotal');
        if (summarySub) summarySub.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');

        const taxRow = document.getElementById('otsTaxRow');
        const taxLabel = document.getElementById('otsTaxLabel');
        const summaryTax = document.getElementById('otsSummaryTax');
        if (taxRow) {
            if (tax > 0) {
                taxRow.style.display = 'flex';
                if (taxLabel) taxLabel.innerText = currentOtsData.taxName + ' (' + currentOtsData.taxRate + '%)';
                if (summaryTax) summaryTax.innerText = 'Rp ' + tax.toLocaleString('id-ID');
            } else {
                taxRow.style.display = 'none';
            }
        }

        const grandDisplay = document.getElementById('otsGrandTotalDisplay');
        if (grandDisplay) grandDisplay.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

        calculateOtsChange();
        return grandTotal;
    }

    function calculateOtsGrandTotalRaw() {
        const qty = currentOtsData.qty || 1;
        const subtotal = currentOtsData.otsPrice * qty;
        let tax = 0;
        if (currentOtsData.taxRate > 0) {
            tax = Math.round(subtotal * (currentOtsData.taxRate / 100));
        }
        return subtotal + tax;
    }

    function calculateOtsChange() {
        const grandTotal = calculateOtsGrandTotalRaw();
        const cashInp = document.getElementById('otsCashReceivedInput');
        const cashReceived = parseFloat(cashInp ? cashInp.value : 0) || 0;
        const changeDisplay = document.getElementById('otsChangeDisplay');

        if (currentOtsData.paymentMethod === 'cash') {
            const change = cashReceived - grandTotal;
            if (changeDisplay) {
                if (cashReceived === 0) {
                    changeDisplay.innerText = 'Rp 0';
                    changeDisplay.style.color = '#64748b';
                } else if (change >= 0) {
                    changeDisplay.innerText = 'Rp ' + change.toLocaleString('id-ID');
                    changeDisplay.style.color = '#15803d';
                } else {
                    changeDisplay.innerText = 'Kurang Rp ' + Math.abs(change).toLocaleString('id-ID');
                    changeDisplay.style.color = '#ef4444';
                }
            }
        }
    }

    function setExactCash() {
        const grandTotal = calculateOtsGrandTotalRaw();
        const cashInp = document.getElementById('otsCashReceivedInput');
        if (cashInp) {
            cashInp.value = grandTotal;
            calculateOtsChange();
        }
    }

    function setQuickCash(amount) {
        const cashInp = document.getElementById('otsCashReceivedInput');
        if (cashInp) {
            cashInp.value = amount;
            calculateOtsChange();
        }
    }

    // Custom Interactive Toast Pop-up Notification (No Native Alert)
    function showOtsToast(message, type = 'error') {
        const container = document.getElementById('otsToastContainer');
        if (!container) return;

        const isSuccess = type === 'success';
        const toast = document.createElement('div');
        toast.style.cssText = `
            pointer-events: auto;
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            padding: 0.85rem 1rem;
            background: #ffffff;
            border: 1.5px solid ${isSuccess ? '#86efac' : '#fca5a5'};
            border-left: 4.5px solid ${isSuccess ? '#16a34a' : '#ef4444'};
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
            font-family: 'Montserrat', sans-serif;
            animation: otsSlideIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            transition: opacity 0.3s ease, transform 0.3s ease;
        `;

        const iconSvg = isSuccess 
            ? `<svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;"><polyline points="20 6 9 17 4 12"/></svg>`
            : `<svg width="18" height="18" fill="none" stroke="#ef4444" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;

        toast.innerHTML = `
            ${iconSvg}
            <div style="flex: 1; font-size: 0.82rem; font-weight: 600; color: #0f172a; line-height: 1.4;">
                ${message}
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0 0 0 4px; font-size: 1.1rem; line-height: 1;">&times;</button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => toast.remove(), 300);
        }, 4500);
    }

    function submitOtsOrder() {
        const buyerNameInp = document.getElementById('otsBuyerName');
        const buyerName = buyerNameInp ? buyerNameInp.value.trim() : '';
        if (!buyerName) {
            showOtsToast('Silakan masukkan Nama Lengkap Pembeli terlebih dahulu.', 'error');
            if (buyerNameInp) buyerNameInp.focus();
            return;
        }

        const buyerPhoneInp = document.getElementById('otsBuyerPhone');
        const buyerPhone = buyerPhoneInp ? buyerPhoneInp.value.trim() : '';

        const buyerEmailInp = document.getElementById('otsBuyerEmail');
        const buyerEmail = buyerEmailInp ? buyerEmailInp.value.trim() : '';

        const notesInp = document.getElementById('otsNotes');
        const notes = notesInp ? notesInp.value.trim() : '';

        const grandTotal = calculateOtsGrandTotalRaw();
        const cashInp = document.getElementById('otsCashReceivedInput');
        const cashReceived = parseFloat(cashInp ? cashInp.value : 0) || 0;

        if (currentOtsData.paymentMethod === 'cash' && cashReceived < grandTotal) {
            showOtsToast('Nominal uang tunai yang diterima kurang dari total tagihan (Rp ' + grandTotal.toLocaleString('id-ID') + ').', 'error');
            if (cashInp) cashInp.focus();
            return;
        }

        const submitBtn = document.getElementById('otsSubmitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Memproses Tiket OTS...</span>';
        }

        const payload = {
            product_id: currentOtsData.productId,
            qty: currentOtsData.qty,
            buyer_name: buyerName,
            buyer_phone: buyerPhone,
            buyer_email: buyerEmail,
            payment_method: currentOtsData.paymentMethod,
            cash_received: cashReceived,
            fulfillment_type: currentOtsData.fulfillment,
            notes: notes,
            _token: '{{ csrf_token() }}'
        };

        fetch('{{ route("creator.ticket.scanner.ots.order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg><span>Proses &amp; Terbitkan Tiket OTS</span>';
            }

            if (data.success) {
                currentOtsData.lastResult = data;
                showOtsToast(data.message || 'Transaksi Tiket OTS berhasil!', 'success');
                showOtsSuccessModal(data);
                prependRecentOtsRow(data);
                updateOtsStatNumbers(data);
            } else {
                showOtsToast('Gagal: ' + (data.message || 'Terjadi kesalahan.'), 'error');
            }
        })
        .catch(err => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg><span>Proses &amp; Terbitkan Tiket OTS</span>';
            }
            console.error(err);
            showOtsToast('Terjadi kesalahan jaringan atau server. Silakan coba lagi.', 'error');
        });
    }

    function showOtsSuccessModal(data) {
        const modal = document.getElementById('otsReceiptModal');
        if (!modal) return;

        document.getElementById('otsModalOrderNumber').innerText = data.order_number;
        document.getElementById('otsModalEventName').innerText = data.event_name;
        document.getElementById('otsModalBuyerName').innerText = data.buyer_name;
        document.getElementById('otsModalQty').innerText = data.qty + ' Tiket';
        document.getElementById('otsModalTotal').innerText = 'Rp ' + data.total.toLocaleString('id-ID') + ' (' + data.payment_method.toUpperCase() + ')';

        const changeRow = document.getElementById('otsModalChangeRow');
        if (data.payment_method === 'cash') {
            if (changeRow) {
                changeRow.style.display = 'flex';
                document.getElementById('otsModalChange').innerText = 'Rp ' + (data.change || 0).toLocaleString('id-ID');
            }
        } else {
            if (changeRow) changeRow.style.display = 'none';
        }

        const statusBadge = document.getElementById('otsModalStatusBadge');
        if (statusBadge) {
            if (data.fulfillment === 'auto_checkin') {
                statusBadge.innerHTML = '<span style="font-size:0.72rem; font-weight:700; background:#dcfce7; color:#166534; padding:3px 10px; border-radius:999px; display:inline-flex; align-items:center; gap:4px;"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg><span>Auto Check-In Sukses (Langsung Hadir)</span></span>';
            } else {
                statusBadge.innerHTML = '<span style="font-size:0.72rem; font-weight:700; background:#eff6ff; color:#1d4ed8; padding:3px 10px; border-radius:999px; display:inline-flex; align-items:center; gap:4px;"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg><span>Tiket Valid Terbit (Scan di Gate)</span></span>';
            }
        }

        const emailBadge = document.getElementById('otsModalEmailBadge');
        if (emailBadge) {
            if (data.email_sent) {
                emailBadge.style.display = 'flex';
            } else {
                emailBadge.style.display = 'none';
            }
        }

        const ticketList = document.getElementById('otsModalTicketList');
        if (ticketList) {
            let html = '';
            (data.tickets || []).forEach((t, i) => {
                html += `
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:0.5rem 0.65rem; background:#ffffff; border:1px solid #e2e8f0; border-radius:8px;">
                        <div>
                            <div style="font-size:0.78rem; font-weight:700; color:#0f172a; font-family:monospace;">${t.ticket_code}</div>
                            <div style="font-size:0.68rem; color:#64748b;">Tiket #${i+1} · ${t.holder_name}</div>
                        </div>
                        <img src="${t.qr_url}" alt="QR" style="width:36px; height:36px; border-radius:4px; border:1px solid #e2e8f0;">
                    </div>
                `;
            });
            ticketList.innerHTML = html;
        }

        const waBtn = document.getElementById('otsModalWaBtn');
        if (waBtn) {
            if (data.whatsapp_url) {
                waBtn.href = data.whatsapp_url;
                waBtn.style.display = 'flex';
            } else {
                waBtn.style.display = 'none';
            }
        }

        modal.style.display = 'flex';
    }

    function closeOtsModal() {
        const modal = document.getElementById('otsReceiptModal');
        if (modal) modal.style.display = 'none';

        // Reset form
        const nameInp = document.getElementById('otsBuyerName');
        const phoneInp = document.getElementById('otsBuyerPhone');
        const emailInp = document.getElementById('otsBuyerEmail');
        const notesInp = document.getElementById('otsNotes');
        const cashInp = document.getElementById('otsCashReceivedInput');
        if (nameInp) nameInp.value = '';
        if (phoneInp) phoneInp.value = '';
        if (emailInp) emailInp.value = '';
        if (notesInp) notesInp.value = '';
        if (cashInp) cashInp.value = '';
        setOtsQty(1);
    }

    function printThermalReceipt() {
        const data = currentOtsData.lastResult;
        if (!data) {
            window.print();
            return;
        }

        document.getElementById('thPrintEventName').innerText = data.event_name;
        document.getElementById('thPrintDate').innerText = data.created_at || new Date().toLocaleString('id-ID');
        document.getElementById('thPrintOrder').innerText = data.order_number;
        document.getElementById('thPrintBuyer').innerText = data.buyer_name;
        document.getElementById('thPrintPayment').innerText = data.payment_method.toUpperCase();
        document.getElementById('thPrintQty').innerText = data.qty;
        document.getElementById('thPrintSubtotal').innerText = 'Rp ' + (data.subtotal || data.total).toLocaleString('id-ID');

        const taxRow = document.getElementById('thPrintTaxRow');
        if (data.tax_total > 0) {
            if (taxRow) taxRow.style.display = 'flex';
            document.getElementById('thPrintTax').innerText = 'Rp ' + data.tax_total.toLocaleString('id-ID');
        } else {
            if (taxRow) taxRow.style.display = 'none';
        }

        document.getElementById('thPrintTotal').innerText = 'Rp ' + data.total.toLocaleString('id-ID');

        const cashRow = document.getElementById('thPrintCashRow');
        const changeRow = document.getElementById('thPrintChangeRow');
        if (data.payment_method === 'cash') {
            if (cashRow) cashRow.style.display = 'flex';
            if (changeRow) changeRow.style.display = 'flex';
            document.getElementById('thPrintCash').innerText = 'Rp ' + (data.cash_received || data.total).toLocaleString('id-ID');
            document.getElementById('thPrintChange').innerText = 'Rp ' + (data.change || 0).toLocaleString('id-ID');
        } else {
            if (cashRow) cashRow.style.display = 'none';
            if (changeRow) changeRow.style.display = 'none';
        }

        const tCont = document.getElementById('thPrintTicketsContainer');
        if (tCont) {
            let html = '';
            (data.tickets || []).forEach(t => {
                html += `
                    <div style="margin: 6px 0; border: 1px solid #000; padding: 4px; border-radius: 4px;">
                        <div style="font-weight:bold; font-size:10px;">${t.ticket_code}</div>
                        <div style="font-size:8px;">${t.holder_name}</div>
                        <img src="${t.qr_url}" style="width: 70px; height: 70px; margin: 4px auto; display:block;">
                        <div style="font-size:8px; font-weight:bold;">${data.fulfillment === 'auto_checkin' ? '[ SUDAH CHECK-IN / HADIR ]' : '[ TIKET MASUK GATE ]'}</div>
                    </div>
                `;
            });
            tCont.innerHTML = html;
        }

        window.print();
    }

    function reprintOtsOrder(orderNumber, eventName, buyerName, qty, total, paymentMethod) {
        currentOtsData.lastResult = {
            order_number: orderNumber,
            event_name: eventName,
            buyer_name: buyerName,
            qty: qty,
            subtotal: total,
            tax_total: 0,
            total: total,
            payment_method: paymentMethod,
            cash_received: total,
            change: 0,
            fulfillment: 'auto_checkin',
            created_at: new Date().toLocaleDateString('id-ID'),
            tickets: [{
                ticket_code: orderNumber,
                holder_name: buyerName,
                qr_url: 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(orderNumber)
            }]
        };
        printThermalReceipt();
    }

    function prependRecentOtsRow(data) {
        const tbody = document.getElementById('otsRecentTableBody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid #f1f5f9';
        tr.innerHTML = `
            <td style="padding: 0.7rem 0.9rem;">
                <div style="font-weight: 700; color: #0f172a;">${data.order_number}</div>
                <div style="font-size: 0.7rem; color: #94a3b8;">Baru saja</div>
            </td>
            <td style="padding: 0.7rem 0.9rem;">
                <div style="font-weight: 600; color: #1e293b;">${data.buyer_name}</div>
                <div style="font-size: 0.7rem; color: #64748b;">${data.buyer_phone || data.buyer_email || '-'}</div>
            </td>
            <td style="padding: 0.7rem 0.9rem;">
                <div style="font-weight: 600; color: #0f172a;">${data.event_name}</div>
                <div style="font-size: 0.7rem; color: #15803d; font-weight: 700;">${data.qty} Tiket</div>
            </td>
            <td style="padding: 0.7rem 0.9rem;">
                <span class="badge-status" style="background:${data.payment_method === 'cash' ? '#fefce8' : '#faf5ff'}; color:${data.payment_method === 'cash' ? '#854d0e' : '#7e22ce'}; font-size:0.7rem; font-weight:700; padding:2px 7px; border-radius:6px; display:inline-flex; align-items:center; gap:3px;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                    <span>${data.payment_method === 'cash' ? 'Cash' : 'QRIS'}</span>
                </span>
            </td>
            <td style="padding: 0.7rem 0.9rem;">
                <strong style="color: #15803d; font-size: 0.84rem;">Rp ${data.total.toLocaleString('id-ID')}</strong>
            </td>
            <td style="padding: 0.7rem 0.9rem; text-align: right;">
                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.35rem;">
                    <button type="button" onclick="printThermalReceipt()"
                        style="background: #f8fafc; border: 1px solid #e2e8f0; color: #334155; font-size: 0.72rem; font-weight: 600; padding: 0.25rem 0.55rem; border-radius: 7px; cursor: pointer; display: inline-flex; align-items: center; gap: 3px;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        <span>Struk</span>
                    </button>
                </div>
            </td>
        `;

        if (tbody.children.length === 1 && tbody.children[0].innerText.includes('Belum ada transaksi')) {
            tbody.innerHTML = '';
        }

        tbody.insertBefore(tr, tbody.firstChild);
    }

    function updateOtsStatNumbers(data) {
        const totEl = document.getElementById('otsStatTotalTickets');
        if (totEl) {
            const cur = parseInt(totEl.innerText) || 0;
            totEl.innerHTML = (cur + data.qty) + ' <span style="font-size:0.55em; font-weight:600; color:#64748b;">Tiket</span>';
        }

        const revEl = document.getElementById('otsStatTotalRevenue');
        if (revEl) {
            const raw = parseInt(revEl.innerText.replace(/[^0-9]/g, '')) || 0;
            revEl.innerText = 'Rp ' + (raw + data.total).toLocaleString('id-ID');
        }

        if (data.payment_method === 'cash') {
            const cashEl = document.getElementById('otsStatTotalCash');
            if (cashEl) {
                const raw = parseInt(cashEl.innerText.replace(/[^0-9]/g, '')) || 0;
                cashEl.innerText = 'Rp ' + (raw + data.total).toLocaleString('id-ID');
            }
        } else {
            const qrisEl = document.getElementById('otsStatTotalQris');
            if (qrisEl) {
                const raw = parseInt(qrisEl.innerText.replace(/[^0-9]/g, '')) || 0;
                qrisEl.innerText = 'Rp ' + (raw + data.total).toLocaleString('id-ID');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initOtsEngine();
    });
</script>
