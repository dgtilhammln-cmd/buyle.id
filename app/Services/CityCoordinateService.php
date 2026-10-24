<?php

namespace App\Services;

class CityCoordinateService
{
    /**
     * Database koordinat Kota & Kabupaten utama di Indonesia (Latitude, Longitude, Provinsi)
     */
    protected static array $cities = [
        // Jawa Timur
        'sidoarjo'       => ['lat' => -7.4478, 'lng' => 112.7183, 'name' => 'Sidoarjo', 'province' => 'Jawa Timur'],
        'surabaya'       => ['lat' => -7.2575, 'lng' => 112.7521, 'name' => 'Surabaya', 'province' => 'Jawa Timur'],
        'malang'         => ['lat' => -7.9666, 'lng' => 112.6326, 'name' => 'Malang', 'province' => 'Jawa Timur'],
        'batu'           => ['lat' => -7.8703, 'lng' => 112.5271, 'name' => 'Batu', 'province' => 'Jawa Timur'],
        'gresik'         => ['lat' => -7.1566, 'lng' => 112.6555, 'name' => 'Gresik', 'province' => 'Jawa Timur'],
        'pasuruan'       => ['lat' => -7.6453, 'lng' => 112.9075, 'name' => 'Pasuruan', 'province' => 'Jawa Timur'],
        'mojokerto'      => ['lat' => -7.4726, 'lng' => 112.4385, 'name' => 'Mojokerto', 'province' => 'Jawa Timur'],
        'jombang'        => ['lat' => -7.5460, 'lng' => 112.2331, 'name' => 'Jombang', 'province' => 'Jawa Timur'],
        'kediri'         => ['lat' => -7.8480, 'lng' => 112.0178, 'name' => 'Kediri', 'province' => 'Jawa Timur'],
        'blitar'         => ['lat' => -8.0983, 'lng' => 112.1681, 'name' => 'Blitar', 'province' => 'Jawa Timur'],
        'madiun'         => ['lat' => -7.6298, 'lng' => 111.5239, 'name' => 'Madiun', 'province' => 'Jawa Timur'],
        'jember'         => ['lat' => -8.1724, 'lng' => 113.7007, 'name' => 'Jember', 'province' => 'Jawa Timur'],
        'banyuwangi'     => ['lat' => -8.2191, 'lng' => 114.3691, 'name' => 'Banyuwangi', 'province' => 'Jawa Timur'],
        'probolinggo'    => ['lat' => -7.7569, 'lng' => 113.2115, 'name' => 'Probolinggo', 'province' => 'Jawa Timur'],
        'lumajang'       => ['lat' => -8.1331, 'lng' => 113.2246, 'name' => 'Lumajang', 'province' => 'Jawa Timur'],
        'tuban'          => ['lat' => -6.8976, 'lng' => 112.0649, 'name' => 'Tuban', 'province' => 'Jawa Timur'],
        'lamongan'       => ['lat' => -7.1198, 'lng' => 112.4158, 'name' => 'Lamongan', 'province' => 'Jawa Timur'],
        'bojonegoro'     => ['lat' => -7.1502, 'lng' => 111.8818, 'name' => 'Bojonegoro', 'province' => 'Jawa Timur'],
        'ngawi'          => ['lat' => -7.4039, 'lng' => 111.4452, 'name' => 'Ngawi', 'province' => 'Jawa Timur'],
        'magetan'        => ['lat' => -7.6534, 'lng' => 111.3281, 'name' => 'Magetan', 'province' => 'Jawa Timur'],
        'ponorogo'       => ['lat' => -7.8685, 'lng' => 111.4621, 'name' => 'Ponorogo', 'province' => 'Jawa Timur'],
        'pacitan'        => ['lat' => -8.2066, 'lng' => 111.0921, 'name' => 'Pacitan', 'province' => 'Jawa Timur'],
        'trenggalek'     => ['lat' => -8.0833, 'lng' => 111.7167, 'name' => 'Trenggalek', 'province' => 'Jawa Timur'],
        'tulungagung'    => ['lat' => -8.0667, 'lng' => 111.9000, 'name' => 'Tulungagung', 'province' => 'Jawa Timur'],
        'nganjuk'        => ['lat' => -7.6053, 'lng' => 111.9035, 'name' => 'Nganjuk', 'province' => 'Jawa Timur'],
        'bondowoso'      => ['lat' => -7.9135, 'lng' => 113.8214, 'name' => 'Bondowoso', 'province' => 'Jawa Timur'],
        'situbondo'      => ['lat' => -7.7061, 'lng' => 114.0044, 'name' => 'Situbondo', 'province' => 'Jawa Timur'],
        'bangkalan'      => ['lat' => -7.0314, 'lng' => 112.7483, 'name' => 'Bangkalan', 'province' => 'Jawa Timur'],
        'sampang'        => ['lat' => -7.1873, 'lng' => 113.2394, 'name' => 'Sampang', 'province' => 'Jawa Timur'],
        'pamekasan'      => ['lat' => -7.1585, 'lng' => 113.4735, 'name' => 'Pamekasan', 'province' => 'Jawa Timur'],
        'sumenep'        => ['lat' => -7.0167, 'lng' => 113.8667, 'name' => 'Sumenep', 'province' => 'Jawa Timur'],

        // DKI Jakarta & Bodetabek
        'jakarta'        => ['lat' => -6.2088, 'lng' => 106.8456, 'name' => 'Jakarta', 'province' => 'DKI Jakarta'],
        'jakarta pusat'  => ['lat' => -6.1805, 'lng' => 106.8284, 'name' => 'Jakarta Pusat', 'province' => 'DKI Jakarta'],
        'jakarta selatan'=> ['lat' => -6.2615, 'lng' => 106.8106, 'name' => 'Jakarta Selatan', 'province' => 'DKI Jakarta'],
        'jakarta barat'  => ['lat' => -6.1683, 'lng' => 106.7589, 'name' => 'Jakarta Barat', 'province' => 'DKI Jakarta'],
        'jakarta timur'  => ['lat' => -6.2250, 'lng' => 106.9004, 'name' => 'Jakarta Timur', 'province' => 'DKI Jakarta'],
        'jakarta utara'  => ['lat' => -6.1384, 'lng' => 106.8640, 'name' => 'Jakarta Utara', 'province' => 'DKI Jakarta'],
        'bogor'          => ['lat' => -6.5971, 'lng' => 106.8060, 'name' => 'Bogor', 'province' => 'Jawa Barat'],
        'depok'          => ['lat' => -6.4025, 'lng' => 106.7942, 'name' => 'Depok', 'province' => 'Jawa Barat'],
        'tangerang'      => ['lat' => -6.1783, 'lng' => 106.6319, 'name' => 'Tangerang', 'province' => 'Banten'],
        'tangerang selatan'=> ['lat' => -6.2889, 'lng' => 106.7179, 'name' => 'Tangerang Selatan', 'province' => 'Banten'],
        'tangsel'        => ['lat' => -6.2889, 'lng' => 106.7179, 'name' => 'Tangerang Selatan', 'province' => 'Banten'],
        'bekasi'         => ['lat' => -6.2383, 'lng' => 106.9756, 'name' => 'Bekasi', 'province' => 'Jawa Barat'],

        // Jawa Barat & Banten
        'bandung'        => ['lat' => -6.9175, 'lng' => 107.6191, 'name' => 'Bandung', 'province' => 'Jawa Barat'],
        'bandung barat'  => ['lat' => -6.8436, 'lng' => 107.4988, 'name' => 'Bandung Barat', 'province' => 'Jawa Barat'],
        'cimahi'         => ['lat' => -6.8723, 'lng' => 107.5422, 'name' => 'Cimahi', 'province' => 'Jawa Barat'],
        'cirebon'        => ['lat' => -6.7320, 'lng' => 108.5523, 'name' => 'Cirebon', 'province' => 'Jawa Barat'],
        'sukabumi'       => ['lat' => -6.9277, 'lng' => 106.9300, 'name' => 'Sukabumi', 'province' => 'Jawa Barat'],
        'tasikmalaya'    => ['lat' => -7.3274, 'lng' => 108.2207, 'name' => 'Tasikmalaya', 'province' => 'Jawa Barat'],
        'garut'          => ['lat' => -7.2167, 'lng' => 107.9000, 'name' => 'Garut', 'province' => 'Jawa Barat'],
        'karawang'       => ['lat' => -6.3054, 'lng' => 107.3006, 'name' => 'Karawang', 'province' => 'Jawa Barat'],
        'purwakarta'     => ['lat' => -6.5569, 'lng' => 107.4433, 'name' => 'Purwakarta', 'province' => 'Jawa Barat'],
        'subang'         => ['lat' => -6.5694, 'lng' => 107.7600, 'name' => 'Subang', 'province' => 'Jawa Barat'],
        'serang'         => ['lat' => -6.1104, 'lng' => 106.1640, 'name' => 'Serang', 'province' => 'Banten'],
        'cilegon'        => ['lat' => -6.0174, 'lng' => 106.0538, 'name' => 'Cilegon', 'province' => 'Banten'],

        // Jawa Tengah & D.I. Yogyakarta
        'semarang'       => ['lat' => -6.9667, 'lng' => 110.4167, 'name' => 'Semarang', 'province' => 'Jawa Tengah'],
        'solo'           => ['lat' => -7.5755, 'lng' => 110.8243, 'name' => 'Surakarta (Solo)', 'province' => 'Jawa Tengah'],
        'surakarta'      => ['lat' => -7.5755, 'lng' => 110.8243, 'name' => 'Surakarta (Solo)', 'province' => 'Jawa Tengah'],
        'yogyakarta'     => ['lat' => -7.7956, 'lng' => 110.3695, 'name' => 'Yogyakarta', 'province' => 'D.I. Yogyakarta'],
        'jogja'          => ['lat' => -7.7956, 'lng' => 110.3695, 'name' => 'Yogyakarta', 'province' => 'D.I. Yogyakarta'],
        'sleman'         => ['lat' => -7.7167, 'lng' => 110.3556, 'name' => 'Sleman', 'province' => 'D.I. Yogyakarta'],
        'bantul'         => ['lat' => -7.8833, 'lng' => 110.3333, 'name' => 'Bantul', 'province' => 'D.I. Yogyakarta'],
        'magelang'       => ['lat' => -7.4797, 'lng' => 110.2177, 'name' => 'Magelang', 'province' => 'Jawa Tengah'],
        'salatiga'       => ['lat' => -7.3305, 'lng' => 110.5084, 'name' => 'Salatiga', 'province' => 'Jawa Tengah'],
        'pekalongan'     => ['lat' => -6.8886, 'lng' => 109.6753, 'name' => 'Pekalongan', 'province' => 'Jawa Tengah'],
        'tegal'          => ['lat' => -6.8694, 'lng' => 109.1402, 'name' => 'Tegal', 'province' => 'Jawa Tengah'],
        'kudus'          => ['lat' => -6.8048, 'lng' => 110.8405, 'name' => 'Kudus', 'province' => 'Jawa Tengah'],
        'pati'           => ['lat' => -6.7561, 'lng' => 111.0380, 'name' => 'Pati', 'province' => 'Jawa Tengah'],
        'jepara'         => ['lat' => -6.5925, 'lng' => 110.6789, 'name' => 'Jepara', 'province' => 'Jawa Tengah'],
        'purwokerto'     => ['lat' => -7.4243, 'lng' => 109.2301, 'name' => 'Purwokerto (Banyumas)', 'province' => 'Jawa Tengah'],
        'banyumas'       => ['lat' => -7.5186, 'lng' => 109.2941, 'name' => 'Banyumas', 'province' => 'Jawa Tengah'],
        'cilacap'        => ['lat' => -7.7198, 'lng' => 109.0067, 'name' => 'Cilacap', 'province' => 'Jawa Tengah'],
        'kebumen'        => ['lat' => -7.6699, 'lng' => 109.6521, 'name' => 'Kebumen', 'province' => 'Jawa Tengah'],
        'klaten'         => ['lat' => -7.7058, 'lng' => 110.6069, 'name' => 'Klaten', 'province' => 'Jawa Tengah'],

        // Bali & Nusa Tenggara
        'denpasar'       => ['lat' => -8.6705, 'lng' => 115.2126, 'name' => 'Denpasar', 'province' => 'Bali'],
        'bali'           => ['lat' => -8.4095, 'lng' => 115.1889, 'name' => 'Bali', 'province' => 'Bali'],
        'badung'         => ['lat' => -8.5819, 'lng' => 115.1772, 'name' => 'Badung', 'province' => 'Bali'],
        'mataram'        => ['lat' => -8.5833, 'lng' => 116.1167, 'name' => 'Mataram (Lombok)', 'province' => 'NTB'],
        'lombok'         => ['lat' => -8.5833, 'lng' => 116.1167, 'name' => 'Lombok', 'province' => 'NTB'],
        'kupang'         => ['lat' => -10.1772, 'lng' => 123.6070, 'name' => 'Kupang', 'province' => 'NTT'],

        // Sumatera
        'medan'          => ['lat' => 3.5952, 'lng' => 98.6722, 'name' => 'Medan', 'province' => 'Sumatera Utara'],
        'palembang'      => ['lat' => -2.9761, 'lng' => 104.7754, 'name' => 'Palembang', 'province' => 'Sumatera Selatan'],
        'pekanbaru'      => ['lat' => 0.5071, 'lng' => 101.4478, 'name' => 'Pekanbaru', 'province' => 'Riau'],
        'batam'          => ['lat' => 1.1301, 'lng' => 104.0529, 'name' => 'Batam', 'province' => 'Kepulauan Riau'],
        'padang'         => ['lat' => -0.9471, 'lng' => 100.4172, 'name' => 'Padang', 'province' => 'Sumatera Barat'],
        'bandar lampung' => ['lat' => -5.4500, 'lng' => 105.2667, 'name' => 'Bandar Lampung', 'province' => 'Lampung'],
        'lampung'        => ['lat' => -5.4500, 'lng' => 105.2667, 'name' => 'Lampung', 'province' => 'Lampung'],
        'jambi'          => ['lat' => -1.6101, 'lng' => 103.6131, 'name' => 'Jambi', 'province' => 'Jambi'],
        'bengkulu'       => ['lat' => -3.8004, 'lng' => 102.2655, 'name' => 'Bengkulu', 'province' => 'Bengkulu'],
        'banda aceh'     => ['lat' => 5.5483, 'lng' => 95.3238, 'name' => 'Banda Aceh', 'province' => 'Aceh'],
        'pangkal pinang' => ['lat' => -2.1333, 'lng' => 106.1167, 'name' => 'Pangkal Pinang', 'province' => 'Bangka Belitung'],

        // Kalimantan
        'balikpapan'     => ['lat' => -1.2379, 'lng' => 116.8529, 'name' => 'Balikpapan', 'province' => 'Kalimantan Timur'],
        'samarinda'      => ['lat' => -0.5022, 'lng' => 117.1537, 'name' => 'Samarinda', 'province' => 'Kalimantan Timur'],
        'banjarmasin'    => ['lat' => -3.3167, 'lng' => 114.5900, 'name' => 'Banjarmasin', 'province' => 'Kalimantan Selatan'],
        'banjarbaru'     => ['lat' => -3.4406, 'lng' => 114.8306, 'name' => 'Banjarbaru', 'province' => 'Kalimantan Selatan'],
        'pontianak'      => ['lat' => -0.0263, 'lng' => 109.3425, 'name' => 'Pontianak', 'province' => 'Kalimantan Barat'],
        'palangkaraya'   => ['lat' => -2.2077, 'lng' => 113.9165, 'name' => 'Palangka Raya', 'province' => 'Kalimantan Tengah'],
        'tarakan'        => ['lat' => 3.3275, 'lng' => 117.5925, 'name' => 'Tarakan', 'province' => 'Kalimantan Utara'],

        // Sulawesi
        'makassar'       => ['lat' => -5.1477, 'lng' => 119.4327, 'name' => 'Makassar', 'province' => 'Sulawesi Selatan'],
        'manado'         => ['lat' => 1.4748, 'lng' => 124.8421, 'name' => 'Manado', 'province' => 'Sulawesi Utara'],
        'palu'           => ['lat' => -0.9003, 'lng' => 119.8780, 'name' => 'Palu', 'province' => 'Sulawesi Tengah'],
        'kendari'        => ['lat' => -3.9985, 'lng' => 122.5126, 'name' => 'Kendari', 'province' => 'Sulawesi Tenggara'],
        'gorontalo'      => ['lat' => 0.5435, 'lng' => 123.0568, 'name' => 'Gorontalo', 'province' => 'Gorontalo'],
        'mamuju'         => ['lat' => -2.6775, 'lng' => 118.8897, 'name' => 'Mamuju', 'province' => 'Sulawesi Barat'],

        // Maluku & Papua
        'ambon'          => ['lat' => -3.6554, 'lng' => 128.1908, 'name' => 'Ambon', 'province' => 'Maluku'],
        'ternate'        => ['lat' => 0.7833, 'lng' => 127.3667, 'name' => 'Ternate', 'province' => 'Maluku Utara'],
        'jayapura'       => ['lat' => -2.5916, 'lng' => 140.6690, 'name' => 'Jayapura', 'province' => 'Papua'],
        'sorong'         => ['lat' => -0.8762, 'lng' => 131.2558, 'name' => 'Sorong', 'province' => 'Papua Barat Daya'],
        'manokwari'      => ['lat' => -0.8615, 'lng' => 134.0620, 'name' => 'Manokwari', 'province' => 'Papua Barat'],
        'merauke'        => ['lat' => -8.4991, 'lng' => 140.4019, 'name' => 'Merauke', 'province' => 'Papua Selatan'],
    ];

    /**
     * Cari koordinat berdasarkan nama kota.
     * Mengembalikan array ['lat' => float, 'lng' => float, 'name' => string, 'province' => string]
     */
    public static function lookup(string $cityName): ?array
    {
        $clean = trim(mb_strtolower($cityName));
        // Hilangkan kata 'kota', 'kabupaten', 'kab.', 'daerah'
        $clean = preg_replace('/^(kota|kabupaten|kab\.|kab|dki)\s+/i', '', $clean);
        $clean = trim($clean);

        if (isset(self::$cities[$clean])) {
            $data = self::$cities[$clean];
            // Tambahkan jitter kecil acak (~200 meter) agar pin di kota yang sama tidak tumpuk persis
            $jitterLat = (mt_rand(-30, 30) / 10000);
            $jitterLng = (mt_rand(-30, 30) / 10000);
            return [
                'lat'      => round($data['lat'] + $jitterLat, 6),
                'lng'      => round($data['lng'] + $jitterLng, 6),
                'name'     => $data['name'],
                'province' => $data['province'],
            ];
        }

        // Fuzzy partial match
        foreach (self::$cities as $key => $data) {
            if (str_contains($clean, $key) || str_contains($key, $clean)) {
                $jitterLat = (mt_rand(-30, 30) / 10000);
                $jitterLng = (mt_rand(-30, 30) / 10000);
                return [
                    'lat'      => round($data['lat'] + $jitterLat, 6),
                    'lng'      => round($data['lng'] + $jitterLng, 6),
                    'name'     => $data['name'],
                    'province' => $data['province'],
                ];
            }
        }

        // Default fallback: Sidoarjo/Surabaya (Pusat Jawa Timur) dengan jitter
        return [
            'lat'      => round(-7.4478 + (mt_rand(-100, 100) / 1000), 6),
            'lng'      => round(112.7183 + (mt_rand(-100, 100) / 1000), 6),
            'name'     => ucfirst($cityName),
            'province' => 'Indonesia',
        ];
    }

    /**
     * Daftar nama kota populer untuk autocomplete / datalist
     */
    public static function getCitySuggestions(): array
    {
        $list = [];
        foreach (self::$cities as $data) {
            $list[] = $data['name'] . ' (' . $data['province'] . ')';
        }
        return array_unique($list);
    }
}
