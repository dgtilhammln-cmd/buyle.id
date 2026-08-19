{{-- Request Order Modal (Redesigned) --}}
@php $services = \App\Models\Product::active()->pluck('name')->toArray(); @endphp

{{-- Overlay --}}
<style>
    #order-modal-overlay {
        position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        z-index: 9999; display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
    }
    #order-modal-overlay.active { opacity: 1; pointer-events: auto; }
    
    .modal-box-light {
        background: #ffffff; width: 100%; max-width: 580px; max-height: 90vh;
        border-radius: 24px; box-shadow: 0 24px 50px rgba(30, 179, 73, 0.15);
        display: flex; flex-direction: column; position: relative; overflow: hidden;
        transform: translateY(20px) scale(0.95); transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: 'Montserrat', sans-serif;
    }
    #order-modal-overlay.active .modal-box-light { transform: translateY(0) scale(1); }

    .order-modal-header { 
        padding: 2rem 2.5rem 1.5rem; 
        background: linear-gradient(135deg, #0064D2 0%, #1eb349 100%); 
        border-bottom: none; 
        display: flex; 
        align-items: flex-start; 
        justify-content: space-between; 
        gap: 1rem; 
        position: relative; 
    }
    
    .order-modal-body { padding: 1.75rem 2.5rem 2.5rem; overflow-y: auto; display: flex; flex-direction: column; gap: 1.25rem; }
    .order-modal-body::-webkit-scrollbar { width: 6px; }
    .order-modal-body::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }

    .modal-close-btn {
        background: #F1F5F9; border: none; color: #64748B; width: 36px; height: 36px; border-radius: 50%;
        cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;
    }
    .modal-close-btn:hover { background: #E2E8F0; color: #0F172A; transform: rotate(90deg); }

    .light-input {
        width: 100%; background: #ffffff; border: 1.5px solid #E2E8F0; color: #1E293B;
        font-size: 0.9375rem; border-radius: 12px; outline: none; padding: 0.875rem 1rem;
        transition: all 0.2s; font-family: 'Montserrat', sans-serif;
    }
    .light-input:focus { border-color: #1eb349; box-shadow: 0 0 0 4px rgba(30, 179, 73, 0.1); }
    .light-label { font-size: 0.8125rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem; display: block; }
    
    .order-submit-btn-light {
        background: #1eb349; color: #fff; width: 100%; padding: 1.125rem; border: none;
        font-size: 0.95rem; font-weight: 700; border-radius: 14px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 0.75rem;
        transition: all 0.3s; box-shadow: 0 8px 20px rgba(30, 179, 73, 0.25);
    }
    .order-submit-btn-light:hover { background: #16a34a; transform: translateY(-2px); box-shadow: 0 12px 25px rgba(30, 179, 73, 0.35); }
    
    @media(max-width: 600px) {
        .modal-box-light { border-radius: 20px 20px 0 0; max-height: 95vh; transform: translateY(100%); }
        #order-modal-overlay { align-items: flex-end; }
        .order-modal-header { padding: 1.5rem 1.5rem 1.25rem; }
        .order-modal-body { padding: 1.25rem 1.5rem 2rem; }
    }
</style>

<div id="order-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modal-title" onclick="if(event.target===this)closeOrderModal()">
    <div class="modal-box-light" onclick="event.stopPropagation()">
        {{-- Header --}}
        <div class="order-modal-header">
            <div>
                <h2 id="modal-title" style="font-size:1.35rem;font-weight:800;color:#fff;margin:0 0 0.25rem;letter-spacing:-0.02em;">Customer Service</h2>
                <p style="font-size:0.825rem;font-weight:400;color:rgba(255,255,255,0.85);margin:0;">Ada pertanyaan? Jangan ragu untuk menghubungi tim kami via WhatsApp.</p>
            </div>
            <button class="modal-close-btn" onclick="closeOrderModal()" aria-label="Tutup" style="background:rgba(255,255,255,0.2); color:#fff;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="order-form" class="order-modal-body">
            @csrf
            <input type="hidden" name="source" id="order-source" value="Website">
            
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:1.25rem;">
                <div>
                    <label class="light-label" for="order-name">Nama Lengkap <span style="color:#EF4444;">*</span></label>
                    <input class="light-input" type="text" id="order-name" name="name" placeholder="Nama Anda" required autocomplete="name">
                </div>
                <div>
                    <label class="light-label" for="order-phone">Telepon / WhatsApp <span style="color:#EF4444;">*</span></label>
                    <input class="light-input" type="tel" id="order-phone" name="phone" placeholder="08xxxxxxxxxx" required autocomplete="tel">
                </div>
            </div>
            
            <div>
                <label class="light-label" for="order-company">Perusahaan (Opsional)</label>
                <input class="light-input" type="text" id="order-company" name="company" placeholder="Nama instansi/perusahaan" autocomplete="organization">
            </div>
            
            <div>
                <label class="light-label" for="order-message">Pesan / Kebutuhan</label>
                <textarea class="light-input" id="order-message" name="message" rows="3" placeholder="Contoh: Kami membutuhkan buyle.id tipe 24 inch untuk gudang kami..." style="resize:vertical;min-height:90px;"></textarea>
            </div>

            {{-- Error --}}
            <div id="order-error" style="display:none;background:#FEF2F2;border:1px solid #FCA5A5;color:#EF4444;font-size:0.875rem;padding:0.875rem 1rem;border-radius:10px;"></div>

            {{-- Submit --}}
            <div>
                <button type="submit" id="order-submit" class="order-submit-btn-light">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Kirim & Lanjut ke WhatsApp
                </button>
                <p style="text-align:center;font-size:0.75rem;color:#94A3B8;margin:0.75rem 0 0;">Data Anda 100% aman dan dirahasiakan.</p>
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

function openOrderModal(source = 'Website') {
    trackModalWaClick();
    const o = document.getElementById('order-modal-overlay');
    const sourceInput = document.getElementById('order-source');
    if (sourceInput) sourceInput.value = source;
    o.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('order-name')?.focus(), 300);
}
function closeOrderModal() {
    document.getElementById('order-modal-overlay').classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeOrderModal(); });

// Form submit
document.getElementById('order-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('order-submit');
    const err = document.getElementById('order-error');
    err.style.display = 'none';
    btn.disabled = true;
    btn.textContent = 'Mengirim...';

    const data = new FormData(this);

    try {
        const res = await fetch('/request-order', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
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
            setTimeout(() => { window.open(finalUrl, '_blank', 'noopener,noreferrer'); }, 200);
        } else {
            throw new Error(json.message ?? 'Terjadi kesalahan.');
        }
    } catch (ex) {
        err.textContent = ex.message || 'Gagal mengirim. Coba lagi atau hubungi WA langsung.';
        err.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg> Kirim & Lanjut ke WhatsApp';
    }
});
</script>
