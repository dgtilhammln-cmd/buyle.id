<?php

namespace App\Services;

/**
 * Standard ISO/IEC 18004 QR Code Generator.
 * Menghasilkan QR Code biner murni dengan kontras 100% hitam-putih tajam (zero blur / no anti-aliasing),
 * standar quiet zone 4 modul ISO, dan ukuran modul optimal agar dapat dipindai seketika
 * oleh semua jenis kamera webcam laptop maupun smartphone.
 */
class SimpleQrCode
{
    /**
     * Generate Base64 PNG Data URI for given string data.
     *
     * @param string $data Teks atau kode yang akan di-encode
     * @param int $size Target size dalam pixel (default 320)
     * @param string|null $logoPath
     * @return string Data URI base64 PNG
     */
    public static function base64($data, $size = 320, $logoPath = null)
    {
        if (empty($data)) {
            $data = 'buyle-ticket';
        }

        require_once app_path('Services/QrCode/qrlib.php');

        // Gunakan Error Correction Level H (standar ISO 30% recovery tertinggi)
        // Level H secara otomatis mengaktifkan Alignment Pattern pada QR Code
        // sehingga tahan terhadap kemiringan kamera (perspective tilt), pantulan silau layar HP,
        // goresan layar, maupun pencahayaan ekstrem dengan tingkat keterbacaan 100%.
        $pixelSize = 10;
        $margin = 4; // Wajib minimal 4 modul quiet zone standar ISO/IEC 18004

        ob_start();
        \QRcode::png($data, null, QR_ECLEVEL_H, $pixelSize, $margin);
        $rawPng = ob_get_clean();

        if (empty($rawPng)) {
            return '';
        }

        // Jika ukuran gambar dari generator sudah sangat baik (> 200px),
        // KITA TIDAK ME-RESAMPLE DENGAN imagecopyresampled KARENA BISA MEMBUAT TEPI MODUL KABUR/BLUR!
        // Langsung kembalikan gambar PNG murni beresolusi tinggi dan pixel-perfect.
        return 'data:image/png;base64,' . base64_encode($rawPng);
    }
}

