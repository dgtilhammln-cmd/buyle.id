<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiVisionService
{
    public const DEFAULT_GROQ_MODEL = 'llama-3.2-11b-vision-instruct';

    /**
     * Get configured AI Provider ('groq', 'openrouter', 'openai', 'gemini')
     */
    public function getProvider(): string
    {
        return Setting::get('ai_provider', 'groq');
    }

    /**
     * Get API Key with fallback to env or setting
     */
    public function getApiKey(): string
    {
        $key = Setting::get('ai_api_key');
        if (empty($key)) {
            $key = env('GROQ_API_KEY', '');
        }
        return trim((string)$key);
    }

    /**
     * Get Configured Model Name
     */
    public function getModel(): string
    {
        $provider = $this->getProvider();
        $model = Setting::get('ai_model');

        if (!empty($model)) {
            return trim($model);
        }

        return match ($provider) {
            'groq' => self::DEFAULT_GROQ_MODEL,
            'openrouter' => 'google/gemini-2.5-flash',
            'gemini' => 'gemini-2.5-flash',
            default => 'gpt-4o-mini',
        };
    }

    /**
     * Check if AI Menu Scan feature is enabled by admin
     */
    public function isEnabled(): bool
    {
        return (bool) Setting::get('ai_scan_enabled', true);
    }

    /**
     * Cooldown days limit (default 7 days)
     */
    public function getCooldownDays(): int
    {
        return (int) Setting::get('ai_scan_cooldown_days', 7);
    }

    /**
     * Monthly scan limit per creator (default 3x)
     */
    public function getMonthlyLimit(): int
    {
        return (int) Setting::get('ai_scan_monthly_limit', 3);
    }

    /**
     * Test API connection with custom/current credentials
     */
    public function testConnection(?string $provider = null, ?string $apiKey = null, ?string $model = null): array
    {
        $provider = $provider ?: $this->getProvider();
        $apiKey   = $apiKey ?: $this->getApiKey();
        $model    = $model ?: $this->getModel();

        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'API Key belum diisi. Silakan masukkan API Key AI.'
            ];
        }

        $startTime = microtime(true);

        try {
            if ($provider === 'gemini') {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
                $response = Http::timeout(15)->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => "Balas dengan persis kata: KONEKSI_OK"]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $duration = round((microtime(true) - $startTime) * 1000);
                    return [
                        'success' => true,
                        'message' => "Koneksi Google Gemini Berhasil! (Model: {$model}, Latency: {$duration}ms)",
                        'latency_ms' => $duration,
                        'provider' => $provider,
                        'model' => $model,
                    ];
                }
                
                $errorMsg = $response->json('error.message') ?? $response->body();
                return [
                    'success' => false,
                    'message' => "Gagal terhubung ke Gemini: " . substr($errorMsg, 0, 150)
                ];
            }

            // OpenAI Compatible API (Groq / OpenRouter / OpenAI)
            $baseUrl = match ($provider) {
                'groq' => 'https://api.groq.com/openai/v1',
                'openrouter' => 'https://openrouter.ai/api/v1',
                default => Setting::get('ai_base_url', 'https://api.groq.com/openai/v1'),
            };

            $headers = [
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type'  => 'application/json',
            ];

            if ($provider === 'openrouter') {
                $headers['HTTP-Referer'] = config('app.url', 'https://buyle.id');
                $headers['X-Title'] = 'Buyle.id Marketplace';
            }

            $response = Http::withHeaders($headers)->timeout(15)->post("{$baseUrl}/chat/completions", [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Ping check. Reply with exactly: KONEKSI_OK"
                    ]
                ],
                'max_tokens' => 20
            ]);

            $duration = round((microtime(true) - $startTime) * 1000);

            if ($response->successful()) {
                $reply = $response->json('choices.0.message.content') ?? '';
                return [
                    'success' => true,
                    'message' => "Koneksi " . strtoupper($provider) . " Berhasil! (Model: {$model}, Latency: {$duration}ms)",
                    'latency_ms' => $duration,
                    'provider' => $provider,
                    'model' => $model,
                    'raw_response' => trim($reply)
                ];
            }

            $errorDetail = $response->json('error.message') ?? $response->body();
            return [
                'success' => false,
                'message' => "Gagal terhubung ke " . strtoupper($provider) . " (" . $response->status() . "): " . substr($errorDetail, 0, 200)
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception error saat tes koneksi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Parse Menu Image into structured Product Catalog Array
     */
    public function scanMenuImage(string $imagePath): array
    {
        if (!$this->isEnabled()) {
            throw new \Exception('Fitur Scan Menu AI sedang dinonaktifkan oleh administrator.');
        }

        $provider = $this->getProvider();
        $apiKey   = $this->getApiKey();
        $model    = $this->getModel();

        if (empty($apiKey)) {
            throw new \Exception('API Key AI belum dikonfigurasi di Admin Settings.');
        }

        // Compress image to max 1024px to minimize token usage
        $base64Image = $this->compressAndEncodeImage($imagePath);

        $prompt = <<<PROMPT
Anda adalah asisten AI kasir dan pakar produk profesional.
Tugas Anda adalah membaca gambar daftar menu/katalog produk/layanan berikut.

EKSTRAK & HASILKAN DATA DALAM FORMAT JSON OBJECT RIGID DENGAN KEY "items":
Contoh format output wajib:
{
  "items": [
    {
      "name": "Nasi Goreng Spesial",
      "price": 25000,
      "category": "Makanan",
      "stock": null,
      "description": "Nasi goreng lezat dengan bumbu rempah khas yang menggugah selera."
    }
  ]
}

Aturan tiap item di dalam array "items":
1. "name": Nama produk (Singkat, jelas, kapitalisasi rapi).
2. "price": Harga dalam nominal angka murni integer tanpa 'Rp' atau titik/koma (Contoh: 25000 untuk 25k/Rp 25.000). Jika tidak ada harga, isi 0.
3. "category": Kategori (Wajib pilih salah satu: "Makanan", "Barang", "Jasa", atau "Lainnya").
4. "stock": Null jika unlimited/tidak ditulis di menu, atau isi angka kuantitas jika ada informasi stok.
5. "description": Deskripsi jualan menggiurkan singkat (1-2 kalimat menarik untuk mempromosikan menu ini).

PENTING:
- Keluarkan HANYA string JSON object valid murni `{"items": [...]}`.
- Jangan tambahkan teks intro, outro, atau penjelasan di luar JSON.
PROMPT;

        try {
            if ($provider === 'gemini') {
                return $this->scanWithGemini($apiKey, $model, $base64Image, $prompt);
            }

            return $this->scanWithOpenAiCompatible($provider, $apiKey, $model, $base64Image, $prompt);

        } catch (\Exception $e) {
            Log::error("AI Menu Scan Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Request Groq / OpenRouter / OpenAI Vision
     */
    protected function scanWithOpenAiCompatible(string $provider, string $apiKey, string $model, string $base64Image, string $prompt): array
    {
        $baseUrl = match ($provider) {
            'groq' => 'https://api.groq.com/openai/v1',
            'openrouter' => 'https://openrouter.ai/api/v1',
            default => Setting::get('ai_base_url', 'https://api.groq.com/openai/v1'),
        };

        $headers = [
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type'  => 'application/json',
        ];

        if ($provider === 'openrouter') {
            $headers['HTTP-Referer'] = config('app.url', 'https://buyle.id');
            $headers['X-Title'] = 'Buyle.id Marketplace';
        }

        $payload = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => "data:image/jpeg;base64,{$base64Image}"
                            ]
                        ]
                    ]
                ]
            ],
            'temperature' => 0.2,
            'max_tokens' => 2000
        ];

        // Enable json mode if provider supports it
        if ($provider === 'groq' || $provider === 'openai') {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        $response = Http::withHeaders($headers)->timeout(45)->post("{$baseUrl}/chat/completions", $payload);

        if (!$response->successful()) {
            $errorText = $response->json('error.message') ?? $response->body();
            throw new \Exception("Gagal membaca foto menu ({$provider}): " . substr($errorText, 0, 200));
        }

        $rawText = $response->json('choices.0.message.content') ?? '';
        return $this->parseJsonItems($rawText);
    }

    /**
     * Request Google Gemini Vision API
     */
    protected function scanWithGemini(string $apiKey, string $model, string $base64Image, string $prompt): array
    {
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
        
        $response = Http::timeout(45)->post($endpoint, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => 'image/jpeg',
                                'data' => $base64Image
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'temperature' => 0.2,
            ]
        ]);

        if (!$response->successful()) {
            $errorMsg = $response->json('error.message') ?? $response->body();
            throw new \Exception("Gagal membaca foto menu (Gemini): " . substr($errorMsg, 0, 200));
        }

        $rawText = $response->json('candidates.0.content.parts.0.text') ?? '';
        return $this->parseJsonItems($rawText);
    }

    /**
     * Safely parse raw AI output into clean Array of products
     */
    protected function parseJsonItems(string $rawText): array
    {
        $cleanJson = trim($rawText);

        // Robust JSON extraction using regex
        if (preg_match('/(\[.*\]|\{.*\})/s', $cleanJson, $matches)) {
            $cleanJson = $matches[1];
        }

        $data = json_decode($cleanJson, true);

        // If JSON root is an object (e.g. {"items": [...]}, {"products": [...]}, {"data": [...]})
        if (is_array($data) && !isset($data[0])) {
            foreach (['items', 'products', 'menu', 'data', 'katalog', 'daftar_menu', 'result'] as $key) {
                if (isset($data[$key]) && is_array($data[$key])) {
                    $data = $data[$key];
                    break;
                }
            }
            // Fallback: search for first array inside the root object
            if (is_array($data) && !isset($data[0])) {
                foreach ($data as $val) {
                    if (is_array($val) && (isset($val[0]) || empty($val))) {
                        $data = $val;
                        break;
                    }
                }
            }
        }

        if (!is_array($data)) {
            Log::error("AI Menu Scan Invalid JSON Raw Output: " . $rawText);
            throw new \Exception("Output dari AI bukan format JSON daftar menu yang valid.");
        }

        $items = [];
        foreach ($data as $row) {
            if (!is_array($row) || empty($row['name'])) continue;

            $price = isset($row['price']) ? (int) preg_replace('/[^\d]/', '', (string)$row['price']) : 0;
            $catRaw = trim($row['category'] ?? 'Makanan');
            $cat = match (strtolower($catRaw)) {
                'barang' => 'Barang',
                'jasa'   => 'Jasa',
                'lainnya' => 'Lainnya',
                default  => 'Makanan',
            };

            $stock = null;
            if (isset($row['stock']) && $row['stock'] !== '' && $row['stock'] !== null && is_numeric($row['stock'])) {
                $stock = (int)$row['stock'];
            }

            $items[] = [
                'name'        => trim($row['name']),
                'price'       => $price,
                'category'    => $cat,
                'stock'       => $stock,
                'description' => trim($row['description'] ?? ''),
            ];
        }

        if (empty($items)) {
            throw new \Exception("Tidak ada daftar menu makanan/minuman yang terdeteksi dari foto ini.");
        }

        return $items;
    }

    /**
     * Compress & Resize Image to max 1024px and return base64
     */
    protected function compressAndEncodeImage(string $path): string
    {
        if (!file_exists($path)) {
            throw new \Exception("File gambar tidak ditemukan.");
        }

        $info = @getimagesize($path);
        if (!$info) {
            return base64_encode(file_get_contents($path));
        }

        $mime = $info['mime'];
        $src  = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png'  => @imagecreatefrompng($path),
            'image/webp' => @imagecreatefromwebp($path),
            default      => null,
        };

        if (!$src) {
            return base64_encode(file_get_contents($path));
        }

        $origW = imagesx($src);
        $origH = imagesy($src);
        $maxDim = 1024;

        if ($origW > $maxDim || $origH > $maxDim) {
            if ($origW > $origH) {
                $newW = $maxDim;
                $newH = (int) round(($origH / $origW) * $maxDim);
            } else {
                $newH = $maxDim;
                $newW = (int) round(($origW / $origH) * $maxDim);
            }

            $dst = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
            imagedestroy($src);
            $src = $dst;
        }

        ob_start();
        imagejpeg($src, null, 75); // 75% quality JPEG for maximum token saving
        $jpegData = ob_get_clean();
        imagedestroy($src);

        return base64_encode($jpegData);
    }

    /**
     * Prepare Base64 string from path, uploaded file, or raw base64 data URI
     */
    public function prepareBase64Image(string $pathOrBase64): string
    {
        if (str_starts_with($pathOrBase64, 'data:image') || strlen($pathOrBase64) > 1000) {
            $clean = preg_replace('/^data:image\/\w+;base64,/', '', $pathOrBase64);
            $binary = base64_decode($clean);
            if ($binary) {
                $tempPath = tempnam(sys_get_temp_dir(), 'ktp_') . '.jpg';
                file_put_contents($tempPath, $binary);
                $encoded = $this->compressAndEncodeImage($tempPath);
                @unlink($tempPath);
                return $encoded;
            }
        }

        return $this->compressAndEncodeImage($pathOrBase64);
    }

    /**
     * Extract Indonesian e-KTP data using AI Vision with Multi-Tier Auto Failover
     */
    public function extractKtpData(string $imagePathOrBase64): array
    {
        $primaryProvider = $this->getProvider();
        $primaryKey      = $this->getApiKey();
        $primaryModel    = $this->getModel();

        // Build list of providers to try in order (Auto-failover when rate-limited/out of quota)
        $providers = [];

        if (!empty($primaryKey)) {
            $providers[] = [
                'provider' => $primaryProvider,
                'key'      => $primaryKey,
                'model'    => $primaryModel,
                'label'    => strtoupper($primaryProvider) . " ({$primaryModel})",
            ];
        }

        // Tier 2 Fallback: Groq Cloud if primary was not Groq
        $groqKey = env('GROQ_API_KEY', '');
        if ($primaryProvider !== 'groq' && !empty($groqKey)) {
            $providers[] = [
                'provider' => 'groq',
                'key'      => $groqKey,
                'model'    => self::DEFAULT_GROQ_MODEL,
                'label'    => 'GROQ Fallback (' . self::DEFAULT_GROQ_MODEL . ')',
            ];
        }

        // Tier 3 Fallback: OpenRouter if primary was not OpenRouter
        $openRouterKey = Setting::get('openrouter_api_key', '');
        if ($primaryProvider !== 'openrouter' && !empty($openRouterKey)) {
            $providers[] = [
                'provider' => 'openrouter',
                'key'      => $openRouterKey,
                'model'    => 'google/gemini-2.5-flash',
                'label'    => 'OpenRouter Fallback (google/gemini-2.5-flash)',
            ];
        }

        if (empty($providers)) {
            throw new \Exception('API Key AI belum dikonfigurasi di Pengaturan AI admin.');
        }

        $base64Image = $this->prepareBase64Image($imagePathOrBase64);
        $lastException = null;

        foreach ($providers as $config) {
            try {
                Log::info("Attempting e-KTP AI Extraction with: {$config['label']}");
                return $this->executeKtpExtraction(
                    $config['provider'],
                    $config['key'],
                    $config['model'],
                    $base64Image
                );
            } catch (\Exception $e) {
                $err = $e->getMessage();
                Log::warning("AI Provider [{$config['label']}] failed/limited: {$err}. Checking next provider...");
                $lastException = $e;
            }
        }

        throw $lastException ?? new \Exception('Seluruh provider AI sedang mencapai batas kuota.');
    }

    /**
     * Execute vision prompt against a specific provider
     */
    protected function executeKtpExtraction(string $provider, string $apiKey, string $model, string $base64Image): array
    {
        $prompt = <<<PROMPT
Anda adalah asisten AI OCR e-KTP Republik Indonesia (Kartu Tanda Penduduk) profesional dengan akurasi dan ketelitian tingkat tinggi.
Tugas Anda adalah membaca dan mengekstrak data dari gambar e-KTP berikut.

HASILKAN HANYA SATU JSON OBJECT VALID DENGAN STRUKTUR BERIKUT:
{
  "nik": "16 digit angka NIK tanpa spasi atau tanda baca lain",
  "full_name": "Nama lengkap pemilik KTP sesuai e-KTP",
  "dob": "YYYY-MM-DD",
  "gender": "L" atau "P",
  "address": "Alamat / nama jalan / dusun / nomor rumah",
  "rt_rw": "RT/RW contoh 004/009",
  "subdistrict": "Kelurahan atau Desa",
  "district": "Kecamatan",
  "city": "Kabupaten atau Kota",
  "province": "Provinsi"
}

ATURAN EKSTRAKSI:
1. "nik": Wajib 16 digit angka murni tanpa spasi atau karakter lain. Perhatikan angka dengan seksama.
2. "full_name": Nama lengkap persis sesuai e-KTP (huruf kapital rapi).
3. "dob": Tanggal lahir dari 'Tempat/Tgl Lahir' dalam format YYYY-MM-DD (contoh: JAKARTA, 23-07-2003 -> "2003-07-23").
4. "gender": "L" untuk LAKI-LAKI, "P" untuk PEREMPUAN.
5. "address": Alamat jalan/kampung/dusun/gang.
6. "rt_rw": Format RT/RW (misal: 004/009).
7. "subdistrict": Kel/Desa (contoh: PEGADUNGAN).
8. "district": Kecamatan (contoh: KALI DERES).
9. "city": Kota/Kabupaten (contoh: JAKARTA BARAT).
10. "province": Provinsi (contoh: DKI JAKARTA).

PENTING:
- Keluarkan HANYA string JSON valid murni tanpa markdown triple backticks.
- Jangan berikan kata pengantar atau catatan penutup apapun di luar JSON.
PROMPT;

        $rawText = '';
        if ($provider === 'gemini') {
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
            $response = Http::timeout(40)->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => 'image/jpeg',
                                    'data' => $base64Image
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                    'temperature' => 0.1,
                ]
            ]);

            if (!$response->successful()) {
                $errorMsg = $response->json('error.message') ?? $response->body();
                throw new \Exception("Gemini Vision Error (" . $response->status() . "): " . substr($errorMsg, 0, 200));
            }
            $rawText = $response->json('candidates.0.content.parts.0.text') ?? '';
        } else {
            $baseUrl = match ($provider) {
                'groq' => 'https://api.groq.com/openai/v1',
                'openrouter' => 'https://openrouter.ai/api/v1',
                default => Setting::get('ai_base_url', 'https://api.groq.com/openai/v1'),
            };

            $headers = [
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type'  => 'application/json',
            ];

            if ($provider === 'openrouter') {
                $headers['HTTP-Referer'] = config('app.url', 'https://buyle.id');
                $headers['X-Title'] = 'Buyle.id Marketplace';
            }

            $payload = [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => $prompt],
                            ['type' => 'image_url', 'image_url' => ['url' => "data:image/jpeg;base64,{$base64Image}"]]
                        ]
                    ]
                ],
                'temperature' => 0.1,
                'max_tokens' => 1000
            ];

            if ($provider === 'groq' || $provider === 'openai') {
                $payload['response_format'] = ['type' => 'json_object'];
            }

            $response = Http::withHeaders($headers)->timeout(40)->post("{$baseUrl}/chat/completions", $payload);

            if (!$response->successful()) {
                $errorText = $response->json('error.message') ?? $response->body();
                throw new \Exception("{$provider} Vision Error (" . $response->status() . "): " . substr($errorText, 0, 200));
            }

            $rawText = $response->json('choices.0.message.content') ?? '';
        }

        return $this->parseKtpJsonResponse($rawText);
    }

    /**
     * Safely parse raw AI output for e-KTP
     */
    protected function parseKtpJsonResponse(string $rawText): array
    {
        $clean = trim($rawText);
        if (preg_match('/\{[\s\S]*\}/', $clean, $matches)) {
            $clean = $matches[0];
        }

        $data = json_decode($clean, true);
        if (!is_array($data)) {
            throw new \Exception("Gagal mengurai respon AI e-KTP.");
        }

        // Clean NIK
        $nik = preg_replace('/\D/', '', (string)($data['nik'] ?? ''));

        // Clean full name
        $fullName = trim((string)($data['full_name'] ?? ''));

        // Clean gender
        $gender = strtoupper(trim((string)($data['gender'] ?? '')));
        if (!in_array($gender, ['L', 'P'])) {
            $gender = (str_contains($gender, 'LAK') || str_contains($gender, 'PRIA') || $gender === 'M') ? 'L' : ($gender === 'F' ? 'P' : null);
        }

        // Clean DOB
        $dob = trim((string)($data['dob'] ?? ''));
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
            if (preg_match('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/', $dob, $dMatches)) {
                $dob = sprintf('%04d-%02d-%02d', (int)$dMatches[3], (int)$dMatches[2], (int)$dMatches[1]);
            } else {
                $dob = null;
            }
        }

        return [
            'nik'         => $nik,
            'full_name'   => $fullName,
            'dob'         => $dob,
            'gender'      => $gender,
            'address'     => trim((string)($data['address'] ?? '')),
            'rt_rw'       => trim((string)($data['rt_rw'] ?? '')),
            'subdistrict' => trim((string)($data['subdistrict'] ?? '')),
            'district'    => trim((string)($data['district'] ?? '')),
            'city'        => trim((string)($data['city'] ?? '')),
            'province'    => trim((string)($data['province'] ?? '')),
            'raw_json'    => $data,
        ];
    }
}
