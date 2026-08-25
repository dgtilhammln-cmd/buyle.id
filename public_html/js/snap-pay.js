/**
 * buyle.id - Midtrans Snap Payment Handler
 * External script to avoid CSP inline script violations
 */
(function () {
    'use strict';

    const btn = document.getElementById('pay-button');
    if (!btn) return;

    const snapToken = btn.getAttribute('data-snap-token');
    if (!snapToken) return;

    const svgCard  = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>';
    const svgSpin  = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;animation:buyle-spin 1s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
    const svgCheck = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
    const svgWarn  = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';

    if (!document.getElementById('buyle-snap-style')) {
        var s = document.createElement('style');
        s.id = 'buyle-snap-style';
        s.textContent = '@keyframes buyle-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
        document.head.appendChild(s);
    }

    btn.addEventListener('click', function () {
        btn.disabled = true;
        btn.innerHTML = svgSpin + ' Memuat Pembayaran...';

        if (typeof snap === 'undefined') {
            btn.disabled = false;
            btn.innerHTML = svgCard + ' Bayar Sekarang';
            alert('Gagal memuat sistem pembayaran. Mohon refresh halaman dan coba lagi.');
            return;
        }

        snap.pay(snapToken, {
            onSuccess: function () {
                btn.innerHTML = svgCheck + ' Pembayaran Berhasil!';
                btn.style.background = 'linear-gradient(135deg,#1eb349,#a5cf37)';
                setTimeout(function () { window.location.reload(); }, 1500);
            },
            onPending: function () {
                btn.disabled = false;
                btn.innerHTML = svgCard + ' Bayar Sekarang';
                alert('Pembayaran menunggu konfirmasi. Cek email untuk instruksi selanjutnya.');
            },
            onError: function () {
                btn.disabled = false;
                btn.innerHTML = svgWarn + ' Coba Lagi';
                alert('Pembayaran gagal. Silakan coba metode pembayaran lain.');
            },
            onClose: function () {
                btn.disabled = false;
                btn.innerHTML = svgCard + ' Bayar Sekarang';
            }
        });
    });
})();