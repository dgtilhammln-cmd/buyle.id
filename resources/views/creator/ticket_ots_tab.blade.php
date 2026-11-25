{{-- ════════════════════════════════════════════════════════════════════════════════
     TAB 4: KASIR LOKET OTS (ON-THE-SPOT POS) - BUYLE.ID TICKETING
     Theme: Light Minimalist, Montserrat, Brand Green, 100% Mobile Friendly
     ════════════════════════════════════════════════════════════════════════════════ --}}
<div id="tab-ots" class="payout-tab-pane" style="display: {{ $activeTab === 'ots' ? 'block' : 'none' }}; font-family: 'Montserrat', sans-serif;">
    
    {{-- 1. HEADER BANNER LOKET OTS --}}
    <div class="prof-card" style="margin-bottom: 1.25rem; background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border: 1.5px solid #bbf7d0;">
        <div style="padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.85rem;">
                <div style="width: 44px; height: 44px; border-radius: 14px; background: linear-gradient(135deg, #1eb349, #a5cf37); display: flex; align-items: center; justify-content: center; color: #fff; box-shadow: 0 4px 14px rgba(30,179,73,0.3); flex-shrink: 0;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="2" y="4" width="20" height="16" rx="3" />
                        <line x1="2" y1="10" x2="22" y2="10" />
                        <circle cx="7" cy="15" r="1.5" />
                        <line x1="12" y1="15" x2="17" y2="15" />
                    </svg>
                </div>
                <div>
                    <h2 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                        Kasir Loket Tiket OTS (On-The-Spot)
                        <span style="font-size: 0.72rem; font-weight: 700; background: #dcfce7; color: #166534; padding: 3px 9px; border-radius: 999px;">Venue Walk-In POS</span>
                    </h2>
                    <p style="font-size: 0.78rem; color: #64748b; margin: 0.2rem 0 0 0;">
                        Penjualan tiket langsung di lokasi event. Transaksi kilat &lt; 10 detik, auto-check in gelang, dan struk thermal.
                    </p>
                </div>
            </div>

            {{-- Event Selector for OTS --}}
            <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #334155; white-space: nowrap;">Event Aktif:</label>
                <select id="otsEventSelector" class="form-input" onchange="handleOtsEventSelect(this.value)" style="height: 40px; font-size: 0.82rem; font-weight: 600; min-width: 220px; max-width: 300px; border-color: #bbf7d0; background: #fff;">
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

    {{-- 2. 4 STAT CARDS REKONSILIASI SHIFT OTS --}}
    <div class="stat-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.25rem;">
        {{-- Tiket OTS Terjual --}}
        <div class="stat-card" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 18px; padding: 1.15rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 18px rgba(0,0,0,0.02);">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="16" rx="2" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                    <circle cx="8" cy="15" r="1.5" />
                    <line x1="13" y1="15" x2="18" y2="15" />
                </svg>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Tiket OTS Terjual</div>
                <div style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-top: 0.15rem;" id="otsStatTotalTickets">
                    {{ $otsStats['total_tickets'] ?? 0 }} <span style="font-size: 0.85rem; font-weight: 600; color: #64748b;">Tiket</span>
                </div>
            </div>
        </div>

        {{-- Total Omzet OTS --}}
        <div class="stat-card" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 18px; padding: 1.15rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 18px rgba(0,0,0,0.02);">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #f0fdf4; color: #166534; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Total Penjualan OTS</div>
                <div style="font-size: 1.35rem; font-weight: 800; color: #166534; margin-top: 0.15rem;" id="otsStatTotalRevenue">
                    Rp {{ number_format($otsStats['total_revenue'] ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Tunai di Laci (Cash) --}}
        <div class="stat-card" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 18px; padding: 1.15rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 18px rgba(0,0,0,0.02);">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #fefce8; color: #ca8a04; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="2" y="6" width="20" height="12" rx="2"/>
                    <circle cx="12" cy="12" r="2.5"/>
                    <path d="M6 12h.01M18 12h.01"/>
                </svg>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Tunai di Laci (Cash)</div>
                <div style="font-size: 1.35rem; font-weight: 800; color: #854d0e; margin-top: 0.15rem;" id="otsStatTotalCash">
                    Rp {{ number_format($otsStats['total_cash'] ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Penerimaan QRIS --}}
        <div class="stat-card" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 18px; padding: 1.15rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 18px rgba(0,0,0,0.02);">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #faf5ff; color: #9333ea; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7"/>
                    <rect x="14" y="3" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/>
                    <rect x="14" y="14" width="3" height="3"/>
                    <line x1="20" y1="14" x2="20" y2="20"/>
                    <line x1="17" y1="20" x2="20" y2="20"/>
                </svg>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Penerimaan QRIS</div>
                <div style="font-size: 1.35rem; font-weight: 800; color: #7e22ce; margin-top: 0.15rem;" id="otsStatTotalQris">
                    Rp {{ number_format($otsStats['total_qris'] ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- 3. WORKSPACE POS KASIR 2 KOLOM --}}
    <div class="ots-workspace-grid" style="display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 1.25rem; align-items: start; margin-bottom: 1.5rem;">
        
        {{-- KOLOM KIRI: TIKET, QTY, FULFILLMENT, PEMBELI --}}
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            
            {{-- CARD 1: KATEGORI TIKET & JUMLAH --}}
            <div class="prof-card" style="margin-bottom: 0;">
                <div class="prof-card-head" style="background: #fafafa;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                        <span style="font-weight: 700; color: #0f172a; font-size: 0.92rem;">1. Pilih Tiket &amp; Jumlah</span>
                    </div>
                    <span id="otsStockBadge" style="font-size: 0.75rem; font-weight: 700; color: #166534; background: #dcfce7; padding: 2px 8px; border-radius: 999px;">
                        Tersisa: --
                    </span>
                </div>
                <div class="form-body" style="padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; padding: 1rem; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; margin-bottom: 1.15rem;">
                        <div>
                            <div style="font-size: 0.95rem; font-weight: 800; color: #0f172a;" id="otsSelectedTicketTitle">
                                Memuat Tiket...
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem; margin-top: 0.25rem;">
                                <span style="font-size: 0.8rem; color: #94a3b8; text-decoration: line-through;" id="otsNormalPriceCrossed"></span>
                                <span style="font-size: 1.1rem; font-weight: 800; color: #166534;" id="otsEffectivePriceDisplay">Rp 0</span>
                                <span style="font-size: 0.7rem; font-weight: 700; background: #dcfce7; color: #166534; padding: 1px 6px; border-radius: 4px;">Harga OTS</span>
                            </div>
                        </div>
                        
                        {{-- Quantity Stepper --}}
                        <div style="display: flex; align-items: center; gap: 0.4rem; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 4px;">
                            <button type="button" onclick="adjustOtsQty(-1)" style="width: 36px; height: 36px; border-radius: 8px; border: none; background: #f1f5f9; color: #334155; font-size: 1.2rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">-</button>
                            <input type="number" id="otsQtyInput" value="1" min="1" max="50" oninput="calculateOtsTotal()" style="width: 48px; height: 36px; border: none; text-align: center; font-size: 1.05rem; font-weight: 800; color: #0f172a; outline: none;">
                            <button type="button" onclick="adjustOtsQty(1)" style="width: 36px; height: 36px; border-radius: 8px; border: none; background: #1eb349; color: #ffffff; font-size: 1.2rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
                        </div>
                    </div>

                    {{-- Quick Qty Pills --}}
                    <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #64748b;">Pilihan Cepat:</span>
                        <button type="button" class="tax-tpl-btn" onclick="setOtsQty(1)" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:8px; cursor:pointer;">1 Tiket</button>
                        <button type="button" class="tax-tpl-btn" onclick="setOtsQty(2)" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:8px; cursor:pointer;">2 Tiket</button>
                        <button type="button" class="tax-tpl-btn" onclick="setOtsQty(3)" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:8px; cursor:pointer;">3 Tiket</button>
                        <button type="button" class="tax-tpl-btn" onclick="setOtsQty(5)" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:8px; cursor:pointer;">5 Tiket</button>
                        <button type="button" class="tax-tpl-btn" onclick="setOtsQty(10)" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-size:0.75rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:8px; cursor:pointer;">10 Tiket</button>
                    </div>
                </div>
            </div>

            {{-- CARD 2: METODE FULFILLMENT (MASUK VENUE) --}}
            <div class="prof-card" style="margin-bottom: 0;">
                <div class="prof-card-head" style="background: #fafafa;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="9 11 12 14 22 4"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                        <span style="font-weight: 700; color: #0f172a; font-size: 0.92rem;">2. Mode Masuk Venue (Fulfillment)</span>
                    </div>
                </div>
                <div class="form-body" style="padding: 1.25rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <label id="labelFulfillAuto" style="border: 2px solid #1eb349; background: #f0fdf4; border-radius: 14px; padding: 0.9rem; cursor: pointer; display: flex; flex-direction: column; gap: 0.35rem; transition: all 0.2s ease;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="radio" name="ots_fulfillment" value="auto_checkin" checked onchange="handleFulfillmentChange(this.value)" style="accent-color: #1eb349; width: 16px; height: 16px;">
                                <span style="font-weight: 800; font-size: 0.85rem; color: #166534;">⚡ Auto Check-In</span>
                            </div>
                            <span style="font-size: 0.72rem; color: #475569; line-height: 1.4;">
                                Pasang gelang di loket. Tiket langsung berstatus <strong>Hadir</strong> (Bypass antrean gate).
                            </span>
                        </label>

                        <label id="labelFulfillTicket" style="border: 2px solid #e2e8f0; background: #ffffff; border-radius: 14px; padding: 0.9rem; cursor: pointer; display: flex; flex-direction: column; gap: 0.35rem; transition: all 0.2s ease;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="radio" name="ots_fulfillment" value="issue_ticket" onchange="handleFulfillmentChange(this.value)" style="accent-color: #1eb349; width: 16px; height: 16px;">
                                <span style="font-weight: 800; font-size: 0.85rem; color: #334155;">🎫 Cetak Struk QR</span>
                            </div>
                            <span style="font-size: 0.72rem; color: #475569; line-height: 1.4;">
                                Status <strong>Valid</strong>. Pembeli membawa struk QR untuk discan petugas gate.
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- CARD 3: IDENTITAS PEMBELI KILAT --}}
            <div class="prof-card" style="margin-bottom: 0;">
                <div class="prof-card-head" style="background: #fafafa;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span style="font-weight: 700; color: #0f172a; font-size: 0.92rem;">3. Data Pembeli (Kilat)</span>
                    </div>
                </div>
                <div class="form-body" style="padding: 1.25rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
                        <div>
                            <label class="form-label" style="font-size: 0.78rem; font-weight: 700; margin-bottom: 0.35rem; color: #334155;">
                                Nama Lengkap Pembeli <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" id="otsBuyerName" class="form-input" placeholder="Contoh: Rian Pratama" required style="height: 42px; font-size: 0.85rem; font-weight: 600;">
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 0.78rem; font-weight: 700; margin-bottom: 0.35rem; color: #334155;">
                                Nomor WhatsApp (Kirim E-Tiket)
                            </label>
                            <input type="tel" id="otsBuyerPhone" class="form-input" placeholder="08123456789" style="height: 42px; font-size: 0.85rem; font-weight: 600;">
                        </div>
                    </div>
                    <div style="margin-top: 0.75rem;">
                        <label class="form-label" style="font-size: 0.78rem; font-weight: 700; margin-bottom: 0.35rem; color: #334155;">
                            Catatan Meja / Khusus (Opsional)
                        </label>
                        <input type="text" id="otsNotes" class="form-input" placeholder="Misal: Rombongan Mahasiswa, Sponsor, dll" style="height: 40px; font-size: 0.8rem;">
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN: RINCIAN TAGIHAN & PEMBAYARAN KASIR --}}
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            
            <div class="prof-card" style="margin-bottom: 0; border: 2px solid #bbf7d0; box-shadow: 0 8px 30px rgba(30,179,73,0.08);">
                <div class="prof-card-head" style="background: linear-gradient(135deg, #1eb349, #166534); color: #fff;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <line x1="2" y1="10" x2="22" y2="10"/>
                        </svg>
                        <span style="font-weight: 800; font-size: 0.95rem; color: #fff;">4. Tagihan &amp; Pembayaran</span>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 700; background: rgba(255,255,255,0.25); color: #fff; padding: 2px 8px; border-radius: 999px;">
                        Kasir: {{ auth()->user()->name }}
                    </span>
                </div>

                <div class="form-body" style="padding: 1.35rem;">
                    
                    {{-- Rincian Tagihan --}}
                    <div style="border-bottom: 1.5px dashed #e2e8f0; padding-bottom: 1rem; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #64748b; margin-bottom: 0.4rem;">
                            <span>Subtotal (<span id="otsSummaryQty">1</span> Tiket)</span>
                            <strong style="color: #0f172a;" id="otsSummarySubtotal">Rp 0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #64748b; margin-bottom: 0.4rem;" id="otsTaxRow">
                            <span id="otsTaxLabel">Pajak Daerah (10%)</span>
                            <strong style="color: #0f172a;" id="otsSummaryTax">Rp 0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.65rem; padding-top: 0.65rem; border-top: 1px solid #f1f5f9;">
                            <span style="font-size: 0.95rem; font-weight: 800; color: #0f172a;">Total Tagihan</span>
                            <span style="font-size: 1.45rem; font-weight: 900; color: #166534;" id="otsGrandTotalDisplay">Rp 0</span>
                        </div>
                    </div>

                    {{-- Pilihan Metode Pembayaran --}}
                    <div style="margin-bottom: 1.15rem;">
                        <label class="form-label" style="font-size: 0.78rem; font-weight: 700; margin-bottom: 0.4rem; color: #334155;">
                            Metode Pembayaran
                        </label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem;">
                            <button type="button" id="btnPayCash" onclick="selectOtsPayment('cash')" style="height: 42px; border-radius: 12px; border: 2px solid #1eb349; background: #f0fdf4; color: #166534; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.4rem; font-family:'Montserrat', sans-serif;">
                                💵 Tunai (Cash)
                            </button>
                            <button type="button" id="btnPayQris" onclick="selectOtsPayment('qris')" style="height: 42px; border-radius: 12px; border: 2px solid #e2e8f0; background: #ffffff; color: #64748b; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.4rem; font-family:'Montserrat', sans-serif;">
                                📱 QRIS Dinamis
                            </button>
                        </div>
                    </div>

                    {{-- Panel Tunai (Input Cash & Kembalian) --}}
                    <div id="otsCashPanel" style="display: block; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 1rem; margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-size: 0.78rem; font-weight: 700; margin-bottom: 0.35rem; color: #334155;">
                            Uang Tunai Diterima (Rp)
                        </label>
                        <div style="position: relative; margin-bottom: 0.5rem;">
                            <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #64748b; font-size: 0.85rem;">Rp</span>
                            <input type="number" id="otsCashReceivedInput" class="form-input" placeholder="0" oninput="calculateOtsChange()" style="height: 42px; padding-left: 2.5rem; font-size: 1.1rem; font-weight: 800; color: #0f172a;">
                        </div>

                        {{-- Quick Cash Pills --}}
                        <div style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 0.75rem;">
                            <button type="button" onclick="setExactCash()" style="background:#e2e8f0; border:none; color:#1e293b; font-size:0.72rem; font-weight:700; padding:0.25rem 0.5rem; border-radius:6px; cursor:pointer;">Uang Pas</button>
                            <button type="button" onclick="setQuickCash(50000)" style="background:#ffffff; border:1px solid #cbd5e1; color:#334155; font-size:0.72rem; font-weight:700; padding:0.25rem 0.5rem; border-radius:6px; cursor:pointer;">50rb</button>
                            <button type="button" onclick="setQuickCash(100000)" style="background:#ffffff; border:1px solid #cbd5e1; color:#334155; font-size:0.72rem; font-weight:700; padding:0.25rem 0.5rem; border-radius:6px; cursor:pointer;">100rb</button>
                            <button type="button" onclick="setQuickCash(150000)" style="background:#ffffff; border:1px solid #cbd5e1; color:#334155; font-size:0.72rem; font-weight:700; padding:0.25rem 0.5rem; border-radius:6px; cursor:pointer;">150rb</button>
                            <button type="button" onclick="setQuickCash(200000)" style="background:#ffffff; border:1px solid #cbd5e1; color:#334155; font-size:0.72rem; font-weight:700; padding:0.25rem 0.5rem; border-radius:6px; cursor:pointer;">200rb</button>
                            <button type="button" onclick="setQuickCash(500000)" style="background:#ffffff; border:1px solid #cbd5e1; color:#334155; font-size:0.72rem; font-weight:700; padding:0.25rem 0.5rem; border-radius:6px; cursor:pointer;">500rb</button>
                        </div>

                        {{-- Kembalian Box --}}
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.8rem; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 10px;">
                            <span style="font-size: 0.82rem; font-weight: 700; color: #475569;">Kembalian:</span>
                            <span id="otsChangeDisplay" style="font-size: 1.15rem; font-weight: 800; color: #166534;">Rp 0</span>
                        </div>
                    </div>

                    {{-- Panel QRIS --}}
                    <div id="otsQrisPanel" style="display: none; background: #faf5ff; border: 1.5px solid #e9d5ff; border-radius: 14px; padding: 1.1rem; text-align: center; margin-bottom: 1.25rem;">
                        <div style="font-size: 0.82rem; font-weight: 700; color: #6b21a8; margin-bottom: 0.25rem;">
                            📱 Scan QRIS Pembeli
                        </div>
                        <div style="font-size: 0.74rem; color: #7e22ce; margin-bottom: 0.85rem;">
                            Tunjukkan kode QRIS event kepada pembeli. Pastikan bukti bayar telah tertera di m-Banking/E-Wallet.
                        </div>
                        <div style="display: inline-block; padding: 8px; background: #fff; border: 1px solid #d8b4fe; border-radius: 12px; margin-bottom: 0.4rem;">
                            <svg width="64" height="64" fill="none" stroke="#6b21a8" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7"/>
                                <rect x="14" y="3" width="7" height="7"/>
                                <rect x="3" y="14" width="7" height="7"/>
                                <rect x="14" y="14" width="3" height="3"/>
                                <line x1="20" y1="14" x2="20" y2="20"/>
                                <line x1="17" y1="20" x2="20" y2="20"/>
                            </svg>
                        </div>
                        <div style="font-size: 0.72rem; font-weight: 700; color: #166534; background: #dcfce7; padding: 2px 8px; border-radius: 999px; display: inline-block;">
                            Lunas Otomatis / Dikonfirmasi Kasir
                        </div>
                    </div>

                    {{-- Tombol Proses Transaksi OTS --}}
                    <button type="button" id="otsSubmitBtn" onclick="submitOtsOrder()" style="width: 100%; height: 50px; border-radius: 999px; border: none; background: linear-gradient(135deg, #1eb349, #a5cf37); color: #ffffff; font-size: 1rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 4px 18px rgba(30,179,73,0.35); font-family:'Montserrat', sans-serif; transition: all 0.2s ease;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                        <span>⚡ Proses &amp; Terbitkan Tiket OTS</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- 4. TABEL 15 TRANSAKSI OTS TERAKHIR --}}
    <div class="prof-card" style="margin-bottom: 1.5rem;">
        <div class="prof-card-head" style="background: #ffffff; border-bottom: 1.5px solid #f1f5f9;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span style="font-weight: 800; color: #0f172a; font-size: 0.92rem;">Riwayat Transaksi Loket OTS (Hari Ini)</span>
            </div>
            <span style="font-size: 0.74rem; color: #64748b;">15 Transaksi Terakhir</span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0; color: #475569; font-weight: 700;">
                        <th style="padding: 0.75rem 1rem;">Waktu / ID Pesanan</th>
                        <th style="padding: 0.75rem 1rem;">Nama Pembeli</th>
                        <th style="padding: 0.75rem 1rem;">Event &amp; Qty</th>
                        <th style="padding: 0.75rem 1rem;">Metode Bayar</th>
                        <th style="padding: 0.75rem 1rem;">Total Tagihan</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody id="otsRecentTableBody">
                    @forelse($otsOrders as $ord)
                        @php
                            $addr = $ord->shipping_address ?? [];
                            $firstItem = $ord->items->first();
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem;">
                                <div style="font-weight: 800; color: #0f172a;">{{ $ord->order_number }}</div>
                                <div style="font-size: 0.7rem; color: #94a3b8;">{{ $ord->created_at->format('H:i · d M Y') }}</div>
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <div style="font-weight: 700; color: #1e293b;">{{ $addr['receiver_name'] ?? 'Pembeli OTS' }}</div>
                                <div style="font-size: 0.7rem; color: #64748b;">{{ $addr['phone'] ?? '-' }}</div>
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <div style="font-weight: 700; color: #0f172a;">{{ $firstItem?->product?->name ?? 'Tiket Event' }}</div>
                                <div style="font-size: 0.7rem; color: #166534; font-weight: 700;">{{ $firstItem?->qty ?? 1 }} Tiket</div>
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                @if(($addr['payment_method'] ?? 'cash') === 'cash')
                                    <span style="background: #fefce8; color: #854d0e; font-size: 0.72rem; font-weight: 700; padding: 2px 7px; border-radius: 6px;">💵 Cash</span>
                                @else
                                    <span style="background: #faf5ff; color: #7e22ce; font-size: 0.72rem; font-weight: 700; padding: 2px 7px; border-radius: 6px;">📱 QRIS</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <strong style="color: #166534; font-size: 0.88rem;">Rp {{ number_format($ord->total, 0, ',', '.') }}</strong>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: right;">
                                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.4rem;">
                                    <button type="button" onclick="reprintOtsOrder('{{ $ord->order_number }}', '{{ addslashes($firstItem?->product?->name ?? '') }}', '{{ addslashes($addr['receiver_name'] ?? '') }}', {{ $firstItem?->qty ?? 1 }}, {{ $ord->total }}, '{{ $addr['payment_method'] ?? 'cash' }}')" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #334155; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.55rem; border-radius: 6px; cursor: pointer;">
                                        🖨️ Struk
                                    </button>
                                    @if(!empty($addr['phone']))
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $addr['phone']) }}?text={{ rawurlencode('Halo ' . ($addr['receiver_name'] ?? '') . ', ini tiket pesanan OTS Anda: ' . $ord->order_number) }}" target="_blank" rel="noopener" style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.55rem; border-radius: 6px; text-decoration: none;">
                                            💬 WA
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: #94a3b8; font-size: 0.8rem;">
                                Belum ada transaksi tiket OTS hari ini. Layani pembeli walk-in pertama menggunakan form di atas!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ══ MODAL SUKSES TRANSAKSI OTS ══ --}}
<div id="otsReceiptModal" class="scanner-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 1rem; box-sizing: border-box;">
    <div style="background: #ffffff; border-radius: 20px; max-width: 480px; width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden; border: 1.5px solid #e2e8f0; animation: modalPop 0.25s ease;">
        
        {{-- Modal Head --}}
        <div style="background: linear-gradient(135deg, #1eb349, #166534); padding: 1.25rem 1.5rem; color: #fff; text-align: center; position: relative;">
            <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.6rem; border: 2px solid rgba(255,255,255,0.4);">
                <svg width="28" height="28" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <h3 style="font-size: 1.15rem; font-weight: 800; margin: 0; color: #fff;">Transaksi OTS Berhasil!</h3>
            <p style="font-size: 0.78rem; color: rgba(255,255,255,0.9); margin: 0.25rem 0 0 0;" id="otsModalOrderNumber">OTS-XXXXXX</p>
            
            <button type="button" onclick="closeOtsModal()" style="position: absolute; right: 1rem; top: 1rem; background: rgba(0,0,0,0.15); border: none; color: #fff; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">&times;</button>
        </div>

        {{-- Modal Body --}}
        <div style="padding: 1.25rem 1.5rem; max-height: 70vh; overflow-y: auto;">
            
            {{-- Status Badge --}}
            <div id="otsModalStatusBadge" style="text-align: center; margin-bottom: 1rem;">
                <span style="font-size: 0.75rem; font-weight: 800; background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 999px;">
                    ⚡ Auto Check-In Sukses (Langsung Hadir)
                </span>
            </div>

            {{-- Order Summary List --}}
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1rem; font-size: 0.82rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                    <span style="color: #64748b;">Event:</span>
                    <strong style="color: #0f172a;" id="otsModalEventName">-</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                    <span style="color: #64748b;">Pembeli:</span>
                    <strong style="color: #0f172a;" id="otsModalBuyerName">-</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                    <span style="color: #64748b;">Jumlah:</span>
                    <strong style="color: #166534;" id="otsModalQty">1 Tiket</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem; border-top: 1px dashed #cbd5e1; padding-top: 0.35rem;">
                    <span style="color: #64748b;">Total Bayar:</span>
                    <strong style="color: #166534; font-size: 0.95rem;" id="otsModalTotal">Rp 0</strong>
                </div>
                <div style="display: flex; justify-content: space-between;" id="otsModalChangeRow">
                    <span style="color: #64748b;">Kembalian:</span>
                    <strong style="color: #0f172a;" id="otsModalChange">Rp 0</strong>
                </div>
            </div>

            {{-- Ticket Passes QR List --}}
            <div style="margin-bottom: 1rem;">
                <div style="font-size: 0.78rem; font-weight: 800; color: #334155; margin-bottom: 0.5rem;">
                    Daftar Kode &amp; QR Tiket Terbit:
                </div>
                <div id="otsModalTicketList" style="display: flex; flex-direction: column; gap: 0.6rem;">
                    {{-- Injected via JS --}}
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <button type="button" onclick="printThermalReceipt()" style="width: 100%; height: 44px; border-radius: 999px; border: 1.5px solid #1eb349; background: #f0fdf4; color: #166534; font-size: 0.88rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.4rem; font-family:'Montserrat', sans-serif;">
                    🖨️ Cetak Struk Thermal (58mm/80mm)
                </button>

                <a id="otsModalWaBtn" href="#" target="_blank" rel="noopener" style="display: none; width: 100%; height: 44px; border-radius: 999px; border: none; background: #25D366; color: #ffffff; font-size: 0.88rem; font-weight: 800; text-decoration: none; align-items: center; justify-content: center; gap: 0.4rem; font-family:'Montserrat', sans-serif; box-sizing: border-box;">
                    💬 Kirim E-Tiket ke WhatsApp Pembeli
                </a>

                <button type="button" onclick="closeOtsModal()" style="width: 100%; height: 44px; border-radius: 999px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #475569; font-size: 0.88rem; font-weight: 700; cursor: pointer; font-family:'Montserrat', sans-serif;">
                    🔄 Layani Pembeli Berikutnya (Transaksi Baru)
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ PRINTABLE THERMAL RECEIPT TEMPLATE (Hidden from screen, visible on window.print) ══ --}}
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

{{-- ══ OTS THERMAL PRINT STYLING ══ --}}
<style>
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
    .ots-workspace-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

{{-- ══ OTS JAVASCRIPT ENGINE ══ --}}
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
                btnCash.style.color = '#166534';
            }
            if (btnQris) {
                btnQris.style.borderColor = '#e2e8f0';
                btnQris.style.backgroundColor = '#ffffff';
                btnQris.style.color = '#64748b';
            }
            if (cashPanel) cashPanel.style.display = 'block';
            if (qrisPanel) qrisPanel.style.display = 'none';
            calculateOtsChange();
        } else {
            if (btnQris) {
                btnQris.style.borderColor = '#9333ea';
                btnQris.style.backgroundColor = '#faf5ff';
                btnQris.style.color = '#7e22ce';
            }
            if (btnCash) {
                btnCash.style.borderColor = '#e2e8f0';
                btnCash.style.backgroundColor = '#ffffff';
                btnCash.style.color = '#64748b';
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
                    changeDisplay.style.color = '#166534';
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

    function submitOtsOrder() {
        const buyerNameInp = document.getElementById('otsBuyerName');
        const buyerName = buyerNameInp ? buyerNameInp.value.trim() : '';
        if (!buyerName) {
            alert('Silakan masukkan Nama Lengkap Pembeli terlebih dahulu.');
            if (buyerNameInp) buyerNameInp.focus();
            return;
        }

        const buyerPhoneInp = document.getElementById('otsBuyerPhone');
        const buyerPhone = buyerPhoneInp ? buyerPhoneInp.value.trim() : '';

        const notesInp = document.getElementById('otsNotes');
        const notes = notesInp ? notesInp.value.trim() : '';

        const grandTotal = calculateOtsGrandTotalRaw();
        const cashInp = document.getElementById('otsCashReceivedInput');
        const cashReceived = parseFloat(cashInp ? cashInp.value : 0) || 0;

        if (currentOtsData.paymentMethod === 'cash' && cashReceived < grandTotal) {
            alert('Nominal uang tunai yang diterima kurang dari total tagihan (Rp ' + grandTotal.toLocaleString('id-ID') + ').');
            if (cashInp) cashInp.focus();
            return;
        }

        const submitBtn = document.getElementById('otsSubmitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>⏳ Memproses Tiket OTS...</span>';
        }

        const payload = {
            product_id: currentOtsData.productId,
            qty: currentOtsData.qty,
            buyer_name: buyerName,
            buyer_phone: buyerPhone,
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
                submitBtn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg><span>⚡ Proses &amp; Terbitkan Tiket OTS</span>';
            }

            if (data.success) {
                currentOtsData.lastResult = data;
                showOtsSuccessModal(data);
                prependRecentOtsRow(data);
                updateOtsStatNumbers(data);
            } else {
                alert('Gagal: ' + (data.message || 'Terjadi kesalahan.'));
            }
        })
        .catch(err => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg><span>⚡ Proses &amp; Terbitkan Tiket OTS</span>';
            }
            console.error(err);
            alert('Terjadi kesalahan jaringan atau server. Silakan coba lagi.');
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
                statusBadge.innerHTML = '<span style="font-size:0.75rem; font-weight:800; background:#dcfce7; color:#166534; padding:4px 12px; border-radius:999px;">⚡ Auto Check-In Sukses (Langsung Hadir)</span>';
            } else {
                statusBadge.innerHTML = '<span style="font-size:0.75rem; font-weight:800; background:#eff6ff; color:#1d4ed8; padding:4px 12px; border-radius:999px;">🎫 Tiket Valid Terbit (Scan di Gate)</span>';
            }
        }

        const ticketContainer = document.getElementById('otsModalTicketList');
        if (ticketContainer) {
            let html = '';
            (data.tickets || []).forEach(t => {
                html += `
                    <div style="display:flex; align-items:center; justify-content:space-between; background:#fff; border:1px solid #cbd5e1; border-radius:10px; padding:0.6rem 0.8rem;">
                        <div>
                            <div style="font-weight:800; font-size:0.85rem; color:#0f172a; font-family:monospace;">${t.ticket_code}</div>
                            <div style="font-size:0.72rem; color:#64748b;">${t.holder_name}</div>
                        </div>
                        <img src="${t.qr_url}" alt="QR" style="width:40px; height:40px; border-radius:4px; border:1px solid #e2e8f0;">
                    </div>
                `;
            });
            ticketContainer.innerHTML = html;
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

        // Reset form for next customer
        const buyerNameInp = document.getElementById('otsBuyerName');
        if (buyerNameInp) {
            buyerNameInp.value = '';
            buyerNameInp.focus();
        }
        const buyerPhoneInp = document.getElementById('otsBuyerPhone');
        if (buyerPhoneInp) buyerPhoneInp.value = '';

        const notesInp = document.getElementById('otsNotes');
        if (notesInp) notesInp.value = '';

        const cashInp = document.getElementById('otsCashReceivedInput');
        if (cashInp) cashInp.value = '';

        setOtsQty(1);
    }

    function printThermalReceipt() {
        const data = currentOtsData.lastResult;
        if (!data) return;

        document.getElementById('thPrintEventName').innerText = data.event_name;
        document.getElementById('thPrintDate').innerText = data.created_at;
        document.getElementById('thPrintOrder').innerText = data.order_number;
        document.getElementById('thPrintBuyer').innerText = data.buyer_name;
        document.getElementById('thPrintPayment').innerText = data.payment_method.toUpperCase();
        document.getElementById('thPrintQty').innerText = data.qty;
        document.getElementById('thPrintSubtotal').innerText = 'Rp ' + data.subtotal.toLocaleString('id-ID');

        const taxRow = document.getElementById('thPrintTaxRow');
        if (taxRow) {
            if (data.tax_total > 0) {
                taxRow.style.display = 'flex';
                document.getElementById('thPrintTax').innerText = 'Rp ' + data.tax_total.toLocaleString('id-ID');
            } else {
                taxRow.style.display = 'none';
            }
        }

        document.getElementById('thPrintTotal').innerText = 'Rp ' + data.total.toLocaleString('id-ID');

        const cashRow = document.getElementById('thPrintCashRow');
        const changeRow = document.getElementById('thPrintChangeRow');
        if (data.payment_method === 'cash') {
            if (cashRow) cashRow.style.display = 'flex';
            if (changeRow) changeRow.style.display = 'flex';
            document.getElementById('thPrintCash').innerText = 'Rp ' + (data.cash_received || 0).toLocaleString('id-ID');
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
            <td style="padding: 0.75rem 1rem;">
                <div style="font-weight: 800; color: #0f172a;">${data.order_number}</div>
                <div style="font-size: 0.7rem; color: #94a3b8;">Baru saja</div>
            </td>
            <td style="padding: 0.75rem 1rem;">
                <div style="font-weight: 700; color: #1e293b;">${data.buyer_name}</div>
                <div style="font-size: 0.7rem; color: #64748b;">${data.buyer_phone || '-'}</div>
            </td>
            <td style="padding: 0.75rem 1rem;">
                <div style="font-weight: 700; color: #0f172a;">${data.event_name}</div>
                <div style="font-size: 0.7rem; color: #166534; font-weight: 700;">${data.qty} Tiket</div>
            </td>
            <td style="padding: 0.75rem 1rem;">
                <span style="background:${data.payment_method === 'cash' ? '#fefce8' : '#faf5ff'}; color:${data.payment_method === 'cash' ? '#854d0e' : '#7e22ce'}; font-size:0.72rem; font-weight:700; padding:2px 7px; border-radius:6px;">
                    ${data.payment_method === 'cash' ? '💵 Cash' : '📱 QRIS'}
                </span>
            </td>
            <td style="padding: 0.75rem 1rem;">
                <strong style="color: #166534; font-size: 0.88rem;">Rp ${data.total.toLocaleString('id-ID')}</strong>
            </td>
            <td style="padding: 0.75rem 1rem; text-align: right;">
                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.4rem;">
                    <button type="button" onclick="printThermalReceipt()" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-size:0.72rem; font-weight:700; padding:0.25rem 0.55rem; border-radius:6px; cursor:pointer;">
                        🖨️ Struk
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
            totEl.innerHTML = (cur + data.qty) + ' <span style="font-size:0.85rem; font-weight:600; color:#64748b;">Tiket</span>';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initOtsEngine();
    });
</script>
