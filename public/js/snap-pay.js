document.addEventListener('DOMContentLoaded', function () {
    const payBtn = document.getElementById('pay-button');
    if (!payBtn) return;

    const snapToken = payBtn.getAttribute('data-snap-token');
    if (!snapToken) return;

    payBtn.addEventListener('click', function () {
        if (typeof window.snap === 'undefined') {
            alert('Layanan pembayaran Midtrans sedang disiapkan. Silakan muat ulang halaman atau tunggu beberapa detik.');
            return;
        }

        payBtn.disabled = true;
        const originalContent = payBtn.innerHTML;
        payBtn.innerHTML = `
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:8px;vertical-align:-4px;animation:spin 1s linear infinite;">
                <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
                <path stroke-linecap="round" d="M4 12a8 8 0 018-8"></path>
            </svg> Memuat Pembayaran...`;

        window.snap.pay(snapToken, {
            onSuccess: function (result) {
                payBtn.innerHTML = `
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:8px;vertical-align:-4px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg> Pembayaran Berhasil!`;
                payBtn.style.background = 'linear-gradient(135deg, #10B981, #059669)';
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            onPending: function (result) {
                payBtn.disabled = false;
                payBtn.innerHTML = originalContent;
                alert('Pembayaran menunggu konfirmasi. Silakan selesaikan instruksi pembayaran Anda.');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            onError: function (result) {
                payBtn.disabled = false;
                payBtn.innerHTML = originalContent;
                alert('Pembayaran gagal diproses. Silakan coba metode pembayaran lainnya.');
            },
            onClose: function () {
                payBtn.disabled = false;
                payBtn.innerHTML = originalContent;
            }
        });
    });
});
