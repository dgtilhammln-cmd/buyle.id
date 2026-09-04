@extends('layouts.app')

@section('content')
<style>
    .scanner-card {
        max-width: 540px;
        margin: 2rem auto;
        background: #ffffff;
        border-radius: 24px;
        padding: 1.5rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        border: 1px solid #E2E8F0;
    }
    #reader {
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        border: 2px dashed #CBD5E1;
        background: #F8FAFC;
    }
    #reader video {
        border-radius: 14px;
        object-fit: cover;
    }
    .res-box {
        margin-top: 1.5rem;
        padding: 1.25rem;
        border-radius: 16px;
        display: none;
        animation: fadeIn 0.3s ease;
    }
    .res-valid { background: #F0FDF4; border: 1.5px solid #86EFAC; color: #166534; }
    .res-used { background: #FEFCE8; border: 1.5px solid #FDE047; color: #854D0E; }
    .res-invalid { background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="container py-4">
    <div class="scanner-card">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 style="font-weight: 800; color: #0F172A; margin: 0;">Ticket Gate Scanner</h4>
                <p style="font-size: 0.82rem; color: #64748B; margin: 0;">Arahkan kamera ke QR Code E-Ticket pengunjung</p>
            </div>
            <div style="width:42px; height:42px; background:#F0FDF4; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#1eb349;">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
            </div>
        </div>

        {{-- Manual Code Input --}}
        <form id="manualScanForm" class="mb-3" onsubmit="handleManualSubmit(event)">
            <div class="input-group">
                <input type="text" id="manualCodeInput" class="form-control" placeholder="Atau ketik Kode Tiket / Token..." style="border-radius: 12px 0 0 12px; font-size: 0.85rem; padding: 0.65rem 1rem;">
                <button class="btn btn-primary px-3" type="submit" style="background:#1eb349; border-color:#1eb349; border-radius: 0 12px 12px 0; font-weight:600; font-size:0.85rem;">Periksa</button>
            </div>
        </form>

        {{-- Camera Scanner Container --}}
        <div id="reader"></div>

        {{-- Dynamic Result Container --}}
        <div id="resultBox" class="res-box">
            <div class="d-flex align-items-start gap-3">
                <div id="resultIcon" class="flex-shrink-0 mt-1"></div>
                <div class="flex-grow-1">
                    <h5 id="resultTitle" style="font-weight: 700; margin-bottom: 0.25rem;"></h5>
                    <p id="resultMsg" style="font-size: 0.85rem; margin-bottom: 0.75rem; line-height: 1.4;"></p>

                    <div id="ticketDetails" style="font-size: 0.8rem; background: rgba(255,255,255,0.7); padding: 0.75rem; border-radius: 10px; display: none;"></div>
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

        fetch("{{ route('ticket.scanner.verify') }}", {
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

        // SVG Icons without emojis
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
                <div><strong>Kode:</strong> ${data.ticket.code || '-'}</div>
                <div><strong>Event:</strong> ${data.ticket.event_name || '-'}</div>
                <div><strong>Pemegang:</strong> ${data.ticket.holder_name || '-'}</div>
            `;
        } else {
            detailsEl.style.display = 'none';
        }
    }
</script>
@endsection
