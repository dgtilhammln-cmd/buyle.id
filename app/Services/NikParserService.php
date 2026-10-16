<?php

namespace App\Services;

class NikParserService
{
    /**
     * Province code mapping (partial list - major provinces of Indonesia).
     */
    private static array $provinces = [
        '11' => 'Aceh', '12' => 'Sumatera Utara', '13' => 'Sumatera Barat',
        '14' => 'Riau', '15' => 'Jambi', '16' => 'Sumatera Selatan',
        '17' => 'Bengkulu', '18' => 'Lampung', '19' => 'Kep. Bangka Belitung',
        '21' => 'Kep. Riau', '31' => 'DKI Jakarta', '32' => 'Jawa Barat',
        '33' => 'Jawa Tengah', '34' => 'DI Yogyakarta', '35' => 'Jawa Timur',
        '36' => 'Banten', '51' => 'Bali', '52' => 'Nusa Tenggara Barat',
        '53' => 'Nusa Tenggara Timur', '61' => 'Kalimantan Barat',
        '62' => 'Kalimantan Tengah', '63' => 'Kalimantan Selatan',
        '64' => 'Kalimantan Timur', '65' => 'Kalimantan Utara',
        '71' => 'Sulawesi Utara', '72' => 'Sulawesi Tengah',
        '73' => 'Sulawesi Selatan', '74' => 'Sulawesi Tenggara',
        '75' => 'Gorontalo', '76' => 'Sulawesi Barat',
        '81' => 'Maluku', '82' => 'Maluku Utara',
        '91' => 'Papua Barat', '94' => 'Papua',
    ];

    private static array $months = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
    ];

    public static function parse(string $nik): array
    {
        $nik = preg_replace('/\D/', '', $nik);

        if (strlen($nik) !== 16) {
            return ['valid' => false, 'error' => 'NIK harus tepat 16 digit angka.'];
        }

        $provinceCode = substr($nik, 0, 2);
        $cityCode     = substr($nik, 0, 4);
        $dateRaw      = (int) substr($nik, 6, 2);
        $monthRaw     = substr($nik, 8, 2);
        $yearRaw      = substr($nik, 10, 2);

        // Gender: perempuan = tanggal + 40
        $gender = $dateRaw > 40 ? 'P' : 'L';
        $date   = $dateRaw > 40 ? $dateRaw - 40 : $dateRaw;

        // Year estimation
        $currentYear  = (int) date('y');
        $fullYear     = (int) $yearRaw > $currentYear ? '19' . $yearRaw : '20' . $yearRaw;

        $monthName = self::$months[$monthRaw] ?? $monthRaw;
        $dob       = sprintf('%02d %s %s', $date, $monthName, $fullYear);

        return [
            'valid'         => true,
            'nik'           => $nik,
            'province_code' => $provinceCode,
            'city_code'     => $cityCode,
            'province'      => self::$provinces[$provinceCode] ?? 'Provinsi ' . $provinceCode,
            'gender'        => $gender,
            'gender_label'  => $gender === 'L' ? 'Laki-Laki' : 'Perempuan',
            'dob'           => $dob,
            'dob_raw'       => sprintf('%s-%s-%02d', $fullYear, $monthRaw, $date),
        ];
    }
}
