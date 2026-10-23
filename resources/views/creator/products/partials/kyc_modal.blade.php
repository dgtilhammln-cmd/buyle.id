{{-- MODAL VERIFIKASI e-KYC DIWAJIBKAN UNTUK PRODUK TIKET --}}
<div id="kycRequiredModal" style="position:fixed; inset:0; z-index:999999; background:rgba(15,23,42,0.65); backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px); display:none; align-items:center; justify-content:center; padding:1.25rem;">
    <div style="background:#ffffff; border-radius:24px; max-width:440px; width:100%; padding:2.25rem 1.75rem; text-align:center; box-shadow:0 25px 60px rgba(0,0,0,0.25); border:1.5px solid #e2e8f0; animation:kycPop 0.28s cubic-bezier(0.16,1,0.3,1); position:relative;">
        <button type="button" onclick="closeKycRequiredModal()" style="position:absolute; top:1rem; right:1rem; width:32px; height:32px; border-radius:50%; background:#f1f5f9; color:#64748b; border:none; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:14px; font-weight:700; transition:all 0.15s;" onmouseover="this.style.background='#e2e8f0';this.style.color='#0f172a';" onmouseout="this.style.background='#f1f5f9';this.style.color='#64748b';">
            ✕
        </button>

        <div style="width:68px; height:68px; border-radius:50%; background:#fef3c7; border:2px solid #fde68a; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; color:#d97706;">
            <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <path d="M12 8v4M12 16h.01"/>
            </svg>
        </div>

        <h3 style="font-family:'Montserrat',sans-serif; font-size:1.15rem; font-weight:800; color:#0f172a; margin-bottom:0.6rem;">Verifikasi e-KYC Diperlukan</h3>
        <p style="font-size:0.84rem; color:#64748b; line-height:1.55; margin-bottom:1.6rem;">
            Untuk membuat dan menjual produk tipe <strong>Tiket Event / Wisata / Webinar</strong>, akun Creator Anda wajib terverifikasi e-KYC terlebih dahulu demi keamanan transaksi & data pengunjung.
        </p>

        <div style="display:flex; flex-direction:column; gap:0.65rem;">
            <a href="https://buyle.id/creator/event-attendance?tab=settings" 
                style="width:100%; padding:0.85rem 1.25rem; border-radius:999px; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; font-family:'Montserrat',sans-serif; font-size:0.88rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; gap:0.5rem; box-shadow:0 4px 14px rgba(30,179,73,0.35); transition:all 0.2s;"
                onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 18px rgba(30,179,73,0.45)';"
                onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 14px rgba(30,179,73,0.35)';">
                <span>Verifikasi Akun Sekarang</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <button type="button" onclick="closeKycRequiredModal()" 
                style="width:100%; padding:0.75rem 1.25rem; border-radius:999px; background:#f1f5f9; color:#475569; font-family:'Montserrat',sans-serif; font-size:0.85rem; font-weight:600; border:none; cursor:pointer; transition:background 0.15s;"
                onmouseover="this.style.background='#e2e8f0';"
                onmouseout="this.style.background='#f1f5f9';">
                Nanti Saja
            </button>
        </div>
    </div>
</div>

<script>
    function showKycRequiredModal() {
        const m = document.getElementById('kycRequiredModal');
        if (m) {
            m.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    function closeKycRequiredModal() {
        const m = document.getElementById('kycRequiredModal');
        if (m) {
            m.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
</script>
