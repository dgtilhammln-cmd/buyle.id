<!-- Report Abuse Modal & Floating Trigger -->
<style>
.buyle-report-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9990;
    background: rgba(15, 23, 42, 0.85);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(8px);
    padding: 6px 12px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
    font-family: 'Montserrat', sans-serif;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s ease;
    text-decoration: none;
}
.buyle-report-btn:hover {
    background: #0F172A;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    color: #FFD700;
}
.buyle-report-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(4px);
    z-index: 99999;
    align-items: center;
    justify-content: center;
    padding: 16px;
    font-family: 'Montserrat', sans-serif;
}
.buyle-report-modal-box {
    background: #ffffff;
    width: 100%;
    max-width: 440px;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    color: #0F172A;
    position: relative;
}
.buyle-report-modal-title {
    font-size: 1.1rem;
    font-weight: 800;
    margin: 0 0 6px;
    color: #0F172A;
}
.buyle-report-modal-sub {
    font-size: 0.8rem;
    color: #64748B;
    margin-bottom: 16px;
    line-height: 1.4;
}
.buyle-report-form-group {
    margin-bottom: 14px;
    text-align: left;
}
.buyle-report-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}
.buyle-report-input, .buyle-report-select, .buyle-report-textarea {
    width: 100%;
    background: #F8FAFC;
    border: 1.5px solid #CBD5E1;
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 0.82rem;
    color: #0F172A;
    outline: none;
    font-family: inherit;
    box-sizing: border-box;
}
.buyle-report-input:focus, .buyle-report-select:focus, .buyle-report-textarea:focus {
    border-color: #1eb349;
    background: #ffffff;
}
.buyle-report-submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #1eb349, #16a34a);
    color: #ffffff;
    border: none;
    padding: 12px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(30,179,73,0.3);
    transition: all 0.2s;
    margin-top: 6px;
}
.buyle-report-submit-btn:hover {
    background: linear-gradient(135deg, #16a34a, #15803d);
}
.buyle-report-close-btn {
    position: absolute;
    top: 18px;
    right: 18px;
    background: #F1F5F9;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748B;
    cursor: pointer;
    font-size: 16px;
}
</style>

<button type="button" class="buyle-report-btn" onclick="openBuyleReportModal()">
    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
    Laporkan
</button>

<div class="buyle-report-modal-overlay" id="buyleReportModal">
    <div class="buyle-report-modal-box">
        <button type="button" class="buyle-report-close-btn" onclick="closeBuyleReportModal()">&times;</button>
        <h3 class="buyle-report-modal-title">Laporkan Halaman / Konten Ini</h3>
        <p class="buyle-report-modal-sub">Bantu buyle.id menjaga ekosistem yang aman. Silakan isi alasan laporan Anda di bawah ini.</p>
        
        <form id="buyleReportForm" onsubmit="submitBuyleReport(event)">
            @csrf
            <input type="hidden" name="report_type" value="{{ $reportType ?? 'link_in_bio' }}">
            <input type="hidden" name="target_url" value="{{ $targetUrl ?? url()->current() }}">
            <input type="hidden" name="target_name" value="{{ $targetName ?? '' }}">

            <div class="buyle-report-form-group">
                <label class="buyle-report-label">Kategori Laporan *</label>
                <select name="reason" class="buyle-report-select" required>
                    <option value="" disabled selected>-- Pilih Alasan --</option>
                    <option value="penipuan">Penipuan / Toko Palsu (Fake Store)</option>
                    <option value="hak_cipta">Pelanggaran Hak Cipta / Brand</option>
                    <option value="konten_ilegal">Konten Ilegal / Melanggar Hukum</option>
                    <option value="spam">Spam / Ujaran Kebencian / SARA</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div class="buyle-report-form-group">
                <label class="buyle-report-label">Deskripsi / Penjelasan Singkat</label>
                <textarea name="description" rows="3" class="buyle-report-textarea" placeholder="Jelaskan alasan laporan secara singkat (opsional)..."></textarea>
            </div>

            <div class="buyle-report-form-group">
                <label class="buyle-report-label">Email Anda (Opsional)</label>
                <input type="email" name="reporter_email" class="buyle-report-input" placeholder="email@domain.com (untuk balasan tindak lanjut)">
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
}
function submitBuyleReport(e) {
    e.preventDefault();
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
