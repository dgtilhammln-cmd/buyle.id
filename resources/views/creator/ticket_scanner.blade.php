@extends('creator.layout')
@section('title', 'Scan Tiket Event · Creator Dashboard')
@section('page_title', 'Scan Tiket Event')

@section('styles')
<style>
    .bio-layout {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
    }

    .bio-sidebar {
        width: 300px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 20px;
        padding: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border: 1px solid #f0fdf4;
        position: sticky;
        top: 1.5rem;
    }

    .bio-content {
        flex: 1;
        min-width: 0;
    }

    .prof-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e7f0e7;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .prof-card-head {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f7f3;
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #fafdfb;
    }

    .form-body {
        padding: 1.5rem;
    }

    #reader {
        width: 100%;
        border-radius: 20px;
        overflow: hidden;
        border: 2px dashed #bbf7d0 !important;
        background: #f8fafc;
        padding: 1.5rem 1rem !important;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    #reader select {
        height: 44px;
        padding: 0 2rem 0 1rem;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1 !important;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: #0f172a;
        background: #fff;
        outline: none;
        margin: 0.5rem;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: all 0.2s;
        max-width: 320px;
        display: inline-block;
    }
    #reader select:focus {
        border-color: #1eb349 !important;
        box-shadow: 0 0 0 3px rgba(30,179,73,0.15);
    }

    #reader button {
        height: 42px;
        padding: 0 1.5rem !important;
        border-radius: 999px !important;
        background: linear-gradient(135deg, #1eb349, #a5cf37) !important;
        border: none !important;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: #fff !important;
        cursor: pointer;
        margin: 0.5rem;
        box-shadow: 0 4px 14px rgba(30,179,73,0.35) !important;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        text-decoration: none !important;
    }
    #reader button:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(30,179,73,0.45) !important;
    }

    #reader a, #reader__dashboard_section_swaplink {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: #1eb349 !important;
        text-decoration: none !important;
        display: inline-block;
        margin-top: 0.75rem;
        padding: 0.45rem 1rem;
        border-radius: 12px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        transition: all 0.2s;
    }
    #reader a:hover {
        background: #dcfce7;
        transform: translateY(-1px);
    }

    #reader img[alt="Info icon"] {
        display: none !important;
    }
    #reader img[alt="Camera implementation"] {
        width: 52px !important;
        height: 52px !important;
        opacity: 0.85;
        margin-bottom: 0.75rem;
    }
    #reader__header_message {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.75rem;
    }
    #reader__dashboard_section_csr span {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        color: #334155;
    }

    #reader video {
        border-radius: 14px;
        object-fit: cover;
    }

    .res-box {
        margin-top: 1.5rem;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .res-valid { background: #F0FDF4; border: 1.5px solid #86EFAC; color: #166534; }
    .res-used { background: #FEFCE8; border: 1.5px solid #FDE047; color: #854D0E; }
    .res-invalid { background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; }

    .form-input-code {
        height: 44px;
        padding: 0 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px 0 0 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.875rem;
        color: #1a1a1a;
        background: #f8fafc;
        outline: none;
        flex: 1;
        transition: all 0.2s;
    }

    .form-input-code:focus {
        border-color: #1eb349;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
    }

    .btn-submit-scan {
        height: 44px;
        padding: 0 1.5rem;
        border-radius: 0 12px 12px 0;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 991px) {
        .bio-layout { flex-direction: column; }
        .bio-sidebar { width: 100%; position: static; }
    }
</style>
@endsection

@section('content')
<div class="bio-layout">

    {{-- SUB SIDEBAR --}}
    <div class="bio-sidebar">
        <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-radius: 16px; padding: 1.25rem; margin-bottom: 1.25rem;">
            <div style="font-size: 0.85rem; font-weight: 800; color: #15803d; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                Gatekeeper Scanner
            </div>
            <div style="font-size: 0.78rem; color: #166534; line-height: 1.5; font-weight: 500;">
                Gunakan kamera smartphone atau ketik kode tiket secara manual untuk memverifikasi kedatangan pengunjung event.
            </div>
        </div>

        <div style="background: #fafdfb; border: 1px solid #e7f0e7; border-radius: 14px; padding: 1rem; font-size: 0.8rem; color: #475569;">
            <strong style="color: #0f172a; display: block; margin-bottom: 0.4rem;">💡 Tips Verifikasi:</strong>
            • Izinkan akses kamera browser saat diminta.<br>
            • Pastikan pencahayaan layar pengunjung cukup.<br>
            • Tiket yang berhasil diverifikasi otomatis terdaftar sebagai <strong>Checked-In</strong>.
        </div>
    </div>

    {{-- CONTENT AREA --}}
    <div class="bio-content">
        <div class="prof-card">
            <div class="prof-card-head">
                <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                Live Camera QR Ticket Scanner
            </div>

            <div class="form-body">
                {{-- Input Manual --}}
                <form id="manualScanForm" class="mb-4" onsubmit="handleManualSubmit(event)">
                    <label class="form-label" style="font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:0.4rem; display:block;">Pemeriksaan Kode Tiket / Token (Manual)</label>
                    <div style="display: flex;">
                        <input type="text" id="manualCodeInput" class="form-input-code" placeholder="Ketik Kode Tiket (misal: TKT-20260905-XXXX)...">
                        <button class="btn-submit-scan" type="submit">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                            Periksa
                        </button>
                    </div>
                </form>

                {{-- Camera Viewport --}}
                <div id="reader"></div>

                {{-- Result Box --}}
                <div id="resultBox" class="res-box">
                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div id="resultIcon" style="flex-shrink: 0; margin-top: 2px;"></div>
                        <div style="flex: 1;">
                            <h5 id="resultTitle" style="font-weight: 800; font-size: 1rem; margin-bottom: 0.25rem;"></h5>
                            <p id="resultMsg" style="font-size: 0.85rem; margin-bottom: 0.75rem; line-height: 1.4;"></p>
                            <div id="ticketDetails" style="font-size: 0.8rem; background: rgba(255,255,255,0.8); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid rgba(0,0,0,0.05); display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrcodeScanner = null;
    let isProcessing = false;

    document.addEventListener("DOMContentLoaded", function() {
        html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            rememberLastUsedCamera: true
        }, false);

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        verifyCode(decodedText);
    }

    function onScanFailure(error) {
        // ignore scan failures
    }

    function handleManualSubmit(e) {
        e.preventDefault();
        const code = document.getElementById('manualCodeInput').value.trim();
        if (code) verifyCode(code);
    }

    function verifyCode(code) {
        isProcessing = true;

        fetch("{{ route('creator.ticket.scanner.verify') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(data => {
            renderResult(data);
            setTimeout(() => { isProcessing = false; }, 2000);
        })
        .catch(err => {
            renderResult({
                status: 'invalid',
                title: 'Gagal Memproses',
                message: 'Terjadi kesalahan sistem saat memverifikasi tiket.'
            });
            setTimeout(() => { isProcessing = false; }, 2000);
        });
    }

    function renderResult(data) {
        const box = document.getElementById('resultBox');
        const iconDiv = document.getElementById('resultIcon');
        const titleEl = document.getElementById('resultTitle');
        const msgEl = document.getElementById('resultMsg');
        const detailsEl = document.getElementById('ticketDetails');

        box.className = 'res-box res-' + data.status;
        box.style.display = 'block';

        titleEl.textContent = data.title || 'Status Tiket';
        msgEl.textContent = data.message || '';

        // Clean SVG Icons
        if (data.status === 'valid') {
            iconDiv.innerHTML = `<svg width="36" height="36" fill="none" stroke="#166534" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`;
        } else if (data.status === 'used') {
            iconDiv.innerHTML = `<svg width="36" height="36" fill="none" stroke="#854D0E" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`;
        } else {
            iconDiv.innerHTML = `<svg width="36" height="36" fill="none" stroke="#991B1B" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
        }

        if (data.ticket) {
            detailsEl.style.display = 'block';
            detailsEl.innerHTML = `
                <div><strong>Kode Tiket:</strong> ${data.ticket.code || '-'}</div>
                <div><strong>Nama Event:</strong> ${data.ticket.event_name || '-'}</div>
                <div><strong>Pemegang Tiket:</strong> ${data.ticket.holder_name || '-'}</div>
            `;
        } else {
            detailsEl.style.display = 'none';
        }
    }
</script>
@endsection
