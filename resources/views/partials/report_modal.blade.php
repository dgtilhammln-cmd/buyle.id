<!-- Report Abuse Modal & Floating Trigger -->
<style>
/* Floating Button (Positioned at Bottom-Left to avoid overlapping Chat Buyle.id on Bottom-Right) */
.buyle-report-btn {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 9980;
    background: rgba(15, 23, 42, 0.88);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(10px);
    padding: 7px 14px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    cursor: pointer;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.18);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.25s ease;
    text-decoration: none;
}
.buyle-report-btn:hover {
    background: #1eb349;
    color: #ffffff;
    border-color: #1eb349;
    transform: translateY(-2px);
    box-shadow: 0 6px 22px rgba(30, 179, 73, 0.35);
}

/* Modal Overlay & Box */
.buyle-report-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.72);
    backdrop-filter: blur(6px);
    z-index: 99999;
    align-items: center;
    justify-content: center;
    padding: 16px;
    font-family: 'Montserrat', sans-serif;
}
.buyle-report-modal-box {
    background: #ffffff;
    width: 100%;
    max-width: 420px;
    border-radius: 22px;
    padding: 24px;
    box-shadow: 0 24px 48px rgba(0, 0, 0, 0.25);
    color: #0F172A;
    position: relative;
    box-sizing: border-box;
    overflow: visible;
}
.buyle-report-modal-title {
    font-size: 1.1rem;
    font-weight: 800;
    margin: 0 0 4px;
    color: #0F172A;
    letter-spacing: -0.01em;
}
.buyle-report-modal-sub {
    font-size: 0.78rem;
    color: #64748B;
    margin-bottom: 18px;
    line-height: 1.45;
}
.buyle-report-form-group {
    margin-bottom: 14px;
    text-align: left;
    position: relative;
}
.buyle-report-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}

/* Premium Custom Select Component */
.buyle-custom-select-wrap {
    position: relative;
    width: 100%;
}
.buyle-custom-select-trigger {
    width: 100%;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 0.8125rem;
    color: #0F172A;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s ease;
    box-sizing: border-box;
    user-select: none;
}
.buyle-custom-select-trigger:hover, .buyle-custom-select-trigger.active {
    border-color: #1eb349;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
}
.buyle-custom-select-trigger svg {
    transition: transform 0.2s ease;
    flex-shrink: 0;
    color: #64748B;
}
.buyle-custom-select-trigger.active svg {
    transform: rotate(180deg);
    color: #1eb349;
}
.buyle-select-options {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.14), 0 4px 12px rgba(30, 179, 73, 0.08);
    z-index: 999999;
    overflow: hidden;
    padding: 4px;
}
.buyle-select-options.open {
    display: block;
    animation: buyleFadeIn 0.15s ease-out;
}
@keyframes buyleFadeIn {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}
.buyle-option-item {
    padding: 9px 12px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #334155;
    border-radius: 9px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s ease;
}
.buyle-option-item:hover {
    background: #F0FDF4;
    color: #1eb349;
}
.buyle-option-item.selected {
    background: #1eb349;
    color: #ffffff;
}

/* Inputs & Textarea */
.buyle-report-input, .buyle-report-textarea {
    width: 100%;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 0.8125rem;
    color: #0F172A;
    font-weight: 500;
    outline: none;
    font-family: inherit;
    box-sizing: border-box;
    transition: all 0.2s ease;
}
.buyle-report-input:focus, .buyle-report-textarea:focus {
    border-color: #1eb349;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
}

.buyle-report-submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #ffffff;
    border: none;
    padding: 12px;
    border-radius: 100px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(30, 179, 73, 0.35);
    transition: all 0.2s ease;
    margin-top: 6px;
}
.buyle-report-submit-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
}
.buyle-report-close-btn {
    position: absolute;
    top: 18px;
    right: 18px;
    background: #F1F5F9;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748B;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.2s;
}
.buyle-report-close-btn:hover {
    background: #E2E8F0;
    color: #0F172A;
}
</style>

<button type="button" class="buyle-report-btn" onclick="openBuyleReportModal()">
    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
    Laporkan
</button>

<div class="buyle-report-modal-overlay" id="buyleReportModal" onclick="if(event.target === this) closeBuyleReportModal()">
    <div class="buyle-report-modal-box">
        <button type="button" class="buyle-report-close-btn" onclick="closeBuyleReportModal()">&times;</button>
        <h3 class="buyle-report-modal-title">Laporkan Halaman / Konten Ini</h3>
        <p class="buyle-report-modal-sub">Bantu buyle.id menjaga ekosistem yang aman & terpercaya.</p>
        
        <form id="buyleReportForm" onsubmit="submitBuyleReport(event)">
            @csrf
            <input type="hidden" name="report_type" value="{{ $reportType ?? 'link_in_bio' }}">
            <input type="hidden" name="target_url" value="{{ $targetUrl ?? url()->current() }}">
            <input type="hidden" name="target_name" value="{{ $targetName ?? '' }}">
            <input type="hidden" name="reason" id="buyleReportReasonInput" value="" required>

            <div class="buyle-report-form-group">
                <label class="buyle-report-label">Kategori Laporan *</label>
                <div class="buyle-custom-select-wrap">
                    <div class="buyle-custom-select-trigger" id="buyleSelectTrigger" onclick="toggleBuyleCustomDropdown(event)">
                        <span id="buyleSelectedText" style="color:#94A3B8;">-- Pilih Alasan Laporan --</span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                    <div class="buyle-select-options" id="buyleSelectOptions">
                        <div class="buyle-option-item" onclick="selectBuyleReportReason('penipuan', 'Penipuan / Toko Palsu')" data-key="penipuan">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            Penipuan / Toko Palsu
                        </div>
                        <div class="buyle-option-item" onclick="selectBuyleReportReason('hak_cipta', 'Pelanggaran Hak Cipta / Brand')" data-key="hak_cipta">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Pelanggaran Hak Cipta / Brand
                        </div>
                        <div class="buyle-option-item" onclick="selectBuyleReportReason('konten_ilegal', 'Konten Ilegal / Melanggar Hukum')" data-key="konten_ilegal">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            Konten Ilegal / Melanggar Hukum
                        </div>
                        <div class="buyle-option-item" onclick="selectBuyleReportReason('spam', 'Spam / Ujaran Kebencian / SARA')" data-key="spam">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                            Spam / Ujaran Kebencian / SARA
                        </div>
                        <div class="buyle-option-item" onclick="selectBuyleReportReason('lainnya', 'Alasan Lainnya')" data-key="lainnya">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                            Alasan Lainnya
                        </div>
                    </div>
                </div>
            </div>

            <div class="buyle-report-form-group">
                <label class="buyle-report-label">Deskripsi / Penjelasan Singkat</label>
                <textarea name="description" rows="3" class="buyle-report-textarea" placeholder="Jelaskan detail laporan Anda secara singkat..."></textarea>
            </div>

            <div class="buyle-report-form-group">
                <label class="buyle-report-label">Email Anda (Opsional)</label>
                <input type="email" name="reporter_email" class="buyle-report-input" placeholder="email@domain.com (untuk info tindak lanjut)">
            </div>

            <button type="submit" class="buyle-report-submit-btn" id="buyleReportSubmitBtn">Kirim Laporan</button>
        </form>
    </div>
</div>

<script>
function openBuyleReportModal() {
    document.getElementById('buyleReportModal').style.display = 'flex';
}
function closeBuyleReportModal() {
    document.getElementById('buyleReportModal').style.display = 'none';
    closeBuyleDropdown();
}
function toggleBuyleCustomDropdown(e) {
    if (e) e.stopPropagation();
    const trigger = document.getElementById('buyleSelectTrigger');
    const options = document.getElementById('buyleSelectOptions');
    trigger.classList.toggle('active');
    options.classList.toggle('open');
}
function closeBuyleDropdown() {
    const trigger = document.getElementById('buyleSelectTrigger');
    const options = document.getElementById('buyleSelectOptions');
    if (trigger) trigger.classList.remove('active');
    if (options) options.classList.remove('open');
}
function selectBuyleReportReason(val, text) {
    document.getElementById('buyleReportReasonInput').value = val;
    const txtSpan = document.getElementById('buyleSelectedText');
    txtSpan.innerText = text;
    txtSpan.style.color = '#0F172A';
    
    document.querySelectorAll('.buyle-option-item').forEach(el => el.classList.remove('selected'));
    const activeEl = document.querySelector(`.buyle-option-item[data-key="${val}"]`);
    if (activeEl) activeEl.classList.add('selected');
    closeBuyleDropdown();
}
document.addEventListener('click', function(e) {
    const wrap = document.querySelector('.buyle-custom-select-wrap');
    if (wrap && !wrap.contains(e.target)) {
        closeBuyleDropdown();
    }
});

function submitBuyleReport(e) {
    e.preventDefault();
    const reason = document.getElementById('buyleReportReasonInput').value;
    if (!reason) {
        alert('Silakan pilih Kategori Laporan terlebih dahulu.');
        return;
    }

    const btn = document.getElementById('buyleReportSubmitBtn');
    btn.disabled = true;
    btn.innerText = 'Mengirim...';

    const form = document.getElementById('buyleReportForm');
    const formData = new FormData(form);

    fetch('{{ route("report.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerText = 'Kirim Laporan';
        if (data.success) {
            alert(data.message);
            closeBuyleReportModal();
            form.reset();
            document.getElementById('buyleSelectedText').innerText = '-- Pilih Alasan Laporan --';
            document.getElementById('buyleSelectedText').style.color = '#94A3B8';
            document.getElementById('buyleReportReasonInput').value = '';
        } else {
            alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerText = 'Kirim Laporan';
        alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
    });
}
</script>
