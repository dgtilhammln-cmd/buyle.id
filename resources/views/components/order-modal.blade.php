{{-- Request Order / Kerjasama Modal (Minimalist Buyle TIX Theme) --}}
@php $services = \App\Models\Product::active()->pluck('name')->toArray(); @endphp

{{-- Overlay & Modal Styling --}}
<style>
    #order-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.48);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.28s ease;
        padding: 1rem;
        box-sizing: border-box;
    }
    #order-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .tix-modal-box {
        background: #ffffff;
        width: 100%;
        max-width: 520px;
        max-height: 92vh;
        border-radius: 24px;
        box-shadow: 0 24px 60px -15px rgba(0, 0, 0, 0.18), 0 0 0 1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        transform: translateY(16px) scale(0.97);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
        font-family: 'Montserrat', sans-serif;
    }
    #order-modal-overlay.active .tix-modal-box {
        transform: translateY(0) scale(1);
    }

    /* Minimalist Header */
    .tix-modal-header {
        padding: 1.5rem 1.75rem 1.15rem;
        border-bottom: 1px solid #F1F5F9;
        position: relative;
        background: #ffffff;
    }

    .tix-modal-top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.65rem;
    }

    .tix-modal-pill-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        background: #F0FDF4;
        border: 1px solid #DCFCE7;
        color: #15803D;
        font-size: 0.74rem;
        font-weight: 600;
        letter-spacing: 0.2px;
    }

    .tix-modal-pill-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #16a34a;
        box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
    }

    .tix-modal-close-btn {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #64748B;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        padding: 0;
        flex-shrink: 0;
    }
    .tix-modal-close-btn:hover {
        background: #F1F5F9;
        color: #0F172A;
        border-color: #CBD5E1;
        transform: scale(1.05);
    }

    .tix-modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0F172A;
        margin: 0 0 0.35rem;
        letter-spacing: -0.01em;
        line-height: 1.3;
    }

    .tix-modal-subtitle {
        font-size: 0.82rem;
        font-weight: 400;
        color: #64748B;
        margin: 0;
        line-height: 1.45;
    }

    /* Modal Body & Form */
    .tix-modal-body {
        padding: 1.35rem 1.75rem 1.65rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        box-sizing: border-box;
    }
    .tix-modal-body::-webkit-scrollbar {
        width: 5px;
    }
    .tix-modal-body::-webkit-scrollbar-thumb {
        background: #E2E8F0;
        border-radius: 10px;
    }

    .tix-modal-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.85rem;
    }

    .tix-modal-field {
        display: flex;
        flex-direction: column;
    }

    .tix-modal-label {
        font-size: 0.8125rem;
        font-weight: 500;
        color: #334155;
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .tix-modal-req {
        color: #EF4444;
        font-size: 0.85rem;
    }

    .tix-modal-opt {
        font-size: 0.74rem;
        color: #94A3B8;
        font-weight: 400;
        margin-left: 2px;
    }

    .tix-modal-input {
        width: 100%;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #0F172A;
        font-size: 0.875rem;
        font-weight: 400;
        border-radius: 12px;
        outline: none;
        padding: 0.72rem 0.95rem;
        transition: all 0.2s ease;
        font-family: 'Montserrat', sans-serif;
        box-sizing: border-box;
    }
    .tix-modal-input::placeholder {
        color: #94A3B8;
        font-weight: 400;
    }
    .tix-modal-input:focus {
        background: #ffffff;
        border-color: #1eb349;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
    }

    textarea.tix-modal-input {
        resize: vertical;
        min-height: 84px;
        line-height: 1.45;
    }

    .tix-modal-error {
        display: none;
        background: #FEF2F2;
        border: 1px solid #FECACA;
        color: #DC2626;
        font-size: 0.82rem;
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        line-height: 1.4;
    }

    /* Submit Button */
    .tix-modal-submit-btn {
        background: linear-gradient(135deg, #1eb349 0%, #84ce00 100%);
        color: #ffffff;
        width: 100%;
        height: 46px;
        border: none;
        font-size: 0.88rem;
        font-weight: 600;
        border-radius: 999px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 20px rgba(30, 179, 73, 0.28);
        font-family: 'Montserrat', sans-serif;
        margin-top: 0.25rem;
    }
    .tix-modal-submit-btn:hover {
        background: linear-gradient(135deg, #16a34a 0%, #65a30d 100%);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(30, 179, 73, 0.38);
    }
    .tix-modal-submit-btn:active {
        transform: translateY(0);
    }

    .tix-modal-footer-hint {
        text-align: center;
        font-size: 0.74rem;
        color: #94A3B8;
        font-weight: 400;
        margin: 0.55rem 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    /* Mobile Friendly Optimization */
    @media(max-width: 600px) {
        #order-modal-overlay {
            padding: 0.75rem;
            align-items: flex-end;
        }

        .tix-modal-box {
            border-radius: 20px 20px 0 0;
            max-height: 94vh;
            transform: translateY(100%);
        }

        .tix-modal-header {
            padding: 1.25rem 1.25rem 1rem;
        }

        .tix-modal-title {
            font-size: 1.15rem;
        }

        .tix-modal-body {
            padding: 1.15rem 1.25rem 1.5rem;
            gap: 0.85rem;
        }

        .tix-modal-grid-2 {
            grid-template-columns: 1fr;
            gap: 0.85rem;
        }
    }
</style>

<div id="order-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modal-title" onclick="if(event.target===this)closeOrderModal()">
    <div class="tix-modal-box" onclick="event.stopPropagation()">
        {{-- Minimalist Header --}}
        <div class="tix-modal-header">
            <div class="tix-modal-top-row">
                <div class="tix-modal-pill-tag">
                    <span class="tix-modal-pill-dot"></span>
                    Kemitraan & Event
                </div>
                <button type="button" class="tix-modal-close-btn" onclick="closeOrderModal()" aria-label="Tutup Formulir">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <h2 id="modal-title" class="tix-modal-title">Konsultasi & Kerja Sama Event</h2>
            <p class="tix-modal-subtitle">Diskusikan kebutuhan ticketing, event organizer, atau scanner gate langsung bersama tim kami.</p>
        </div>

        {{-- Minimalist Form --}}
        <form id="order-form" class="tix-modal-body">
            @csrf
            <input type="hidden" name="source" id="order-source" value="Buyle TIX">

            {{-- Row: Nama Lengkap & WhatsApp --}}
            <div class="tix-modal-grid-2">
                <div class="tix-modal-field">
                    <label class="tix-modal-label" for="order-name">
                        Nama Lengkap <span class="tix-modal-req">*</span>
                    </label>
                    <input class="tix-modal-input" type="text" id="order-name" name="name"
                           placeholder="Nama Anda / PIC" required autocomplete="name">
                </div>
                <div class="tix-modal-field">
                    <label class="tix-modal-label" for="order-phone">
                        Nomor WhatsApp <span class="tix-modal-req">*</span>
                    </label>
                    <input class="tix-modal-input" type="tel" id="order-phone" name="phone"
                           placeholder="08xxxxxxxxxx" required autocomplete="tel">
                </div>
            </div>

            {{-- Row: EO / Instansi / Komunitas --}}
            <div class="tix-modal-field">
                <label class="tix-modal-label" for="order-company">
                    Nama EO / Instansi / Komunitas <span class="tix-modal-opt">(Opsional)</span>
                </label>
                <input class="tix-modal-input" type="text" id="order-company" name="company"
                       placeholder="Contoh: Soundfest Live / BEM Kampus / Komunitas" autocomplete="organization">
            </div>

            {{-- Row: Pesan / Kebutuhan Event --}}
            <div class="tix-modal-field">
                <label class="tix-modal-label" for="order-message">
                    Rencana & Kebutuhan Event <span class="tix-modal-opt">(Opsional)</span>
                </label>
                <textarea class="tix-modal-input" id="order-message" name="message" rows="3"
                          placeholder="Ceritakan rencana event Anda (nama acara, estimasi tiket, tanggal, atau layanan yang dibutuhkan)..."></textarea>
            </div>

            {{-- Error Message Box --}}
            <div id="order-error" class="tix-modal-error"></div>

            {{-- Submit Action --}}
            <div>
                <button type="submit" id="order-submit" class="tix-modal-submit-btn">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    <span>Lanjut ke WhatsApp</span>
                </button>
                <p class="tix-modal-footer-hint">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Pesan otomatis disiapkan & terhubung ke WhatsApp resmi Buyle TIX
                </p>
            </div>
        </form>
    </div>
</div>

<script>
async function trackModalWaClick() {
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        await fetch('/track/wa_click', {
            method: 'POST',
            keepalive: true,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ url: window.location.href })
        });
    } catch(e) {}
}

function openOrderModal(source = 'Buyle TIX') {
    trackModalWaClick();
    const o = document.getElementById('order-modal-overlay');
    const sourceInput = document.getElementById('order-source');
    if (sourceInput) sourceInput.value = source;
    o.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('order-name')?.focus(), 250);
}

function closeOrderModal() {
    const o = document.getElementById('order-modal-overlay');
    if (o) o.classList.remove('active');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeOrderModal();
});

// Form submit handler
document.getElementById('order-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('order-submit');
    const err = document.getElementById('order-error');
    if (err) err.style.display = 'none';

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span>Menyiapkan WhatsApp...</span>';
    }

    const data = new FormData(this);

    try {
        const res = await fetch('/request-order', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            },
            body: data,
        });
        const json = await res.json();

        if (json.success && json.wa_url) {
            closeOrderModal();
            this.reset();

            // Gunakan URL khusus dari in-text link jika ada, jika tidak gunakan fallback JSON
            let finalUrl = window.pendingWaUrl ? window.pendingWaUrl : json.wa_url;
            window.pendingWaUrl = null; // reset

            // Small delay so modal closes first
            setTimeout(() => {
                window.open(finalUrl, '_blank', 'noopener,noreferrer');
            }, 180);
        } else {
            throw new Error(json.message ?? 'Terjadi kesalahan saat memproses data.');
        }
    } catch (ex) {
        if (err) {
            err.textContent = ex.message || 'Gagal mengirim data. Silakan coba lagi.';
            err.style.display = 'block';
        }
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = `
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                <span>Lanjut ke WhatsApp</span>
            `;
        }
    }
});
</script>
