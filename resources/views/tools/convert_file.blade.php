@extends('layouts.app')

@section('title', $seo['title'] ?? 'Convert File & Foto Online Gratis - Buyle Tools')

@section('content')
<div class="convert-page-wrapper">
    {{-- Breadcrumb --}}
    <div class="convert-breadcrumb-container">
        <nav class="convert-breadcrumb" aria-label="Breadcrumb">
            <ol>
                <li>
                    <a href="{{ route('home') }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Beranda</span>
                    </a>
                </li>
                <li><span class="breadcrumb-separator">/</span></li>
                <li><span>Tools</span></li>
                <li><span class="breadcrumb-separator">/</span></li>
                <li class="active" aria-current="page"><span>Convert File</span></li>
            </ol>
        </nav>
    </div>

    {{-- Hero Section --}}
    <section class="convert-hero">
        <div class="convert-hero-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
            </svg>
            <span>Buyle Tools - Media Converter</span>
        </div>
        <h1 class="convert-hero-title">Convert Format Foto & Gambar Online Gratis</h1>
        <p class="convert-hero-desc">
            Ubah format gambar JPG, PNG, WEBP, AVIF, GIF, dan BMP secara instan tanpa batas kuota. Dilengkapi opsi kompresi kualitas gambar yang fleksibel sesuai kebutuhan Anda.
        </p>
    </section>

    {{-- Main Converter Card --}}
    <section class="convert-card-section">
        <div class="convert-main-card">
            
            {{-- Setting Bar: Format Selection & Quality Options --}}
            <div class="convert-settings-grid">
                {{-- Format Source & Target --}}
                <div class="setting-col format-selection-col">
                    <label class="setting-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        <span>Pilihan Format</span>
                    </label>

                    <div class="format-selectors-wrap">
                        <div class="format-select-group">
                            <span class="sub-label">Format Asal</span>
                            <div class="custom-select-box">
                                <select id="sourceFormatSelect" class="form-select-control">
                                    <option value="auto" selected>Auto Detect (Otomatis)</option>
                                    <option value="image/jpeg">JPG / JPEG</option>
                                    <option value="image/png">PNG</option>
                                    <option value="image/webp">WEBP</option>
                                    <option value="image/avif">AVIF</option>
                                    <option value="image/gif">GIF</option>
                                    <option value="image/bmp">BMP</option>
                                    <option value="image/svg+xml">SVG</option>
                                </select>
                                <svg class="select-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                        </div>

                        <div class="format-arrow-icon" title="Dikonversi Menjadi">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </div>

                        <div class="format-select-group">
                            <span class="sub-label">Format Tujuan</span>
                            <div class="custom-select-box">
                                <select id="targetFormatSelect" class="form-select-control">
                                    <option value="image/webp" selected>WEBP (Rekomendasi Web)</option>
                                    <option value="image/jpeg">JPG / JPEG</option>
                                    <option value="image/png">PNG (Transparan)</option>
                                    <option value="image/avif">AVIF (Kompresi Tinggi)</option>
                                    <option value="image/bmp">BMP</option>
                                </select>
                                <svg class="select-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quality Reduction Settings --}}
                <div class="setting-col quality-control-col">
                    <div class="quality-toggle-header">
                        <label class="setting-label" for="enableQualityToggle">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path>
                            </svg>
                            <span>Turunkan Kualitas Gambar</span>
                        </label>
                        <div class="switch-toggle-wrap">
                            <input type="checkbox" id="enableQualityToggle" class="toggle-checkbox" checked>
                            <label for="enableQualityToggle" class="toggle-slider"></label>
                        </div>
                    </div>

                    <div id="qualitySliderContainer" class="quality-slider-container">
                        <div class="slider-val-row">
                            <span class="slider-text">Persentase Kualitas:</span>
                            <div class="slider-badge-val">
                                <span id="qualityValDisplay">80</span>%
                            </div>
                        </div>

                        <div class="slider-input-track">
                            <input type="range" id="qualityRangeInput" min="10" max="100" step="5" value="80" class="custom-range-slider">
                        </div>

                        <div class="quality-level-indicator" id="qualityLevelLabel">
                            <span class="indicator-dot"></span>
                            <span id="qualityPresetDesc">Rekomendasi Seimbang: Ukuran file hemat hingga 60% dengan visual tajam.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Drag & Drop Upload Zone --}}
            <div id="dropZone" class="convert-drop-zone">
                <input type="file" id="fileInput" multiple accept="image/*,.heic,.heif,.webp,.avif,.svg" class="hidden-file-input">
                
                <div class="drop-zone-content">
                    <div class="drop-icon-circle">
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>

                    <h3 class="drop-zone-title">Tarik & Lepaskan File Foto di Sini</h3>
                    <p class="drop-zone-subtitle">atau klik tombol di bawah untuk memilih foto dari galeri/perangkat Anda</p>

                    <button type="button" class="btn-select-files" onclick="document.getElementById('fileInput').click()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14"></path>
                        </svg>
                        <span>Pilih Foto dari Perangkat</span>
                    </button>

                    <div class="drop-format-support">
                        <span>Format didukung: JPG, PNG, WEBP, AVIF, GIF, BMP, SVG, HEIC</span>
                        <span class="support-privacy-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            Diproses langsung di browser Anda (100% Aman &amp; Privat)
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Bar when files are queued --}}
            <div id="queueActionBar" class="queue-action-bar" style="display: none;">
                <div class="queue-count-info">
                    <span id="queueTotalCount">0</span> Foto siap dikonversi
                </div>

                <div class="queue-action-buttons">
                    <button type="button" class="btn-queue-action btn-clear-all" onclick="clearAllFiles()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <span>Hapus Semua</span>
                    </button>

                    <button type="button" id="btnDownloadAll" class="btn-queue-action btn-download-all" onclick="downloadAllConverted()" style="display: none;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Download Semua Hasil</span>
                    </button>

                    <button type="button" id="btnStartConvert" class="btn-queue-action btn-convert-now" onclick="startConvertQueue()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                        <span>Mulai Konversi</span>
                    </button>
                </div>
            </div>

            {{-- File Queue List --}}
            <div id="fileQueueContainer" class="file-queue-container" style="display: none;">
                <div id="fileQueueList" class="file-queue-list"></div>
            </div>

        </div>
    </section>

    {{-- SEO Content Section: Panduan, Keunggulan & FAQ --}}
    <section class="convert-seo-content">
        {{-- 3 Step Guide --}}
        <div class="convert-guide-section">
            <h2 class="section-title">Cara Mudah Mengubah Format Foto di Buyle Tools</h2>
            <p class="section-subtitle">Tiga langkah praktis untuk mengonversi gambar dengan cepat dan menjaga kualitas terbaik</p>

            <div class="guide-steps-grid">
                <div class="guide-step-card">
                    <div class="step-num-badge">1</div>
                    <div class="step-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <h3 class="step-title">Pilih atau Drag Foto</h3>
                    <p class="step-desc">Tarik file foto Anda langsung ke area upload atau klik tombol pilih foto dari galeri komputer atau smartphone Anda.</p>
                </div>

                <div class="guide-step-card">
                    <div class="step-num-badge">2</div>
                    <div class="step-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </div>
                    <h3 class="step-title">Tentukan Format &amp; Kualitas</h3>
                    <p class="step-desc">Pilih format output yang diinginkan (JPG, PNG, WEBP, AVIF). Anda dapat mengaktifkan kompresi kualitas gambar sesuai kebutuhan.</p>
                </div>

                <div class="guide-step-card">
                    <div class="step-num-badge">3</div>
                    <div class="step-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <h3 class="step-title">Konversi &amp; Unduh Hasil</h3>
                    <p class="step-desc">Klik Mulai Konversi. Proses berlangsung dalam hitungan detik secara instan di peramban dan foto siap Anda unduh langsung.</p>
                </div>
            </div>
        </div>

        {{-- Feature Highlights --}}
        <div class="convert-features-section">
            <h2 class="section-title">Keunggulan Convert Foto Buyle Tools</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                        </svg>
                    </div>
                    <h4>Proses Cepat &amp; Ringan</h4>
                    <p>Konversi diproses langsung menggunakan mesin browser modern tanpa antrian server, menghasilkan waktu pengerjaan super kilat.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <h4>100% Aman &amp; Privat</h4>
                    <p>Gambar foto Anda tidak pernah dikirim atau disimpan di server luar. Semua pemrosesan berlangsung aman di perangkat Anda sendiri.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    </div>
                    <h4>Format Populer Lengkap</h4>
                    <p>Dukungan penuh untuk format gambar utama: JPG, PNG transparan, WEBP modern untuk web, hingga AVIF generasi terbaru.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <h4>Gratis Tanpa Batas</h4>
                    <p>Gunakan sepuasnya tanpa batasan jumlah file per hari, tanpa biaya langganan, dan tanpa perlu mendaftar akun terlebih dahulu.</p>
                </div>
            </div>
        </div>

        {{-- FAQ Accordion --}}
        <div class="convert-faq-section">
            <h2 class="section-title">Pertanyaan Umum (FAQ)</h2>
            <div class="faq-accordion-list">
                <details class="faq-item" open>
                    <summary class="faq-question">
                        <span>Format gambar apa saja yang bisa dikonversi?</span>
                        <svg class="faq-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>
                    <div class="faq-answer">
                        <p>Anda dapat mengunggah file foto dalam format JPG, JPEG, PNG, WEBP, AVIF, GIF, BMP, SVG, serta HEIC. Format tujuan konversi yang tersedia meliputi WEBP, JPG, PNG, AVIF, dan BMP.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-question">
                        <span>Bagaimana cara kerja opsi pengaturan kualitas gambar?</span>
                        <svg class="faq-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>
                    <div class="faq-answer">
                        <p>Ketika opsi kompresi diaktifkan, Anda dapat menggeser persentase kualitas dari 10% sampai 100%. Nilai 80% adalah setelan rekomendasi standar yang mampu memangkas ukuran berkas hingga 60% tanpa mengurangi ketajaman visual yang terlihat oleh mata.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-question">
                        <span>Apakah foto yang saya unggah aman dan tidak bocor?</span>
                        <svg class="faq-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>
                    <div class="faq-answer">
                        <p>Sangat aman. Sistem Buyle Tools memproses seluruh konversi secara lokal langsung di memori browser perangkat Anda (Client-Side HTML5 Canvas). Foto Anda tidak pernah diunggah atau disimpan ke server manapun.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary class="faq-question">
                        <span>Apakah format PNG yang dikonversi ke JPG tetap transparan?</span>
                        <svg class="faq-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>
                    <div class="faq-answer">
                        <p>Format JPG tidak mendukung latar belakang transparan. Jika Anda mengonversi file PNG transparan ke JPG, bagian transparan akan secara otomatis terisi warna putih bersih. Jika ingin mempertahankan transparansi, pilihlah format tujuan PNG atau WEBP.</p>
                    </div>
                </details>
            </div>
        </div>
    </section>
</div>

{{-- JSON-LD Structured Data Schema for SEO --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Convert File & Foto Online Gratis - Buyle Tools",
  "url": "{{ route('tools.convert_file') }}",
  "description": "Konversi format foto gambar JPG, PNG, WEBP, AVIF, GIF, dan BMP online gratis dengan opsi pengaturan kualitas kompresi.",
  "applicationCategory": "MultimediaApplication",
  "operatingSystem": "All",
  "browserRequirements": "Requires JavaScript. Requires HTML5.",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "IDR"
  }
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Format gambar apa saja yang bisa dikonversi?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Mendukung JPG, JPEG, PNG, WEBP, AVIF, GIF, BMP, SVG, dan HEIC. Format tujuan meliputi WEBP, JPG, PNG, AVIF, dan BMP."
      }
    },
    {
      "@type": "Question",
      "name": "Bagaimana cara kerja opsi pengaturan kualitas gambar?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Pengguna dapat menggeser persentase kualitas dari 10% hingga 100%. Rekomendasi 80% memberikan rasio ukuran terbaik tanpa kehilangan ketajaman visual."
      }
    },
    {
      "@type": "Question",
      "name": "Apakah foto yang diunggah aman?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Sangat aman karena diproses lokal langsung di browser pengguna tanpa pernah dikirimkan ke server luar."
      }
    }
  ]
}
</script>

{{-- Styles --}}
<style>
    .convert-page-wrapper {
        max-width: 1140px;
        margin: 0 auto;
        padding: 1.5rem 1rem 4rem;
        font-family: 'Montserrat', sans-serif;
        color: #1e293b;
    }

    /* Breadcrumbs */
    .convert-breadcrumb-container {
        margin-bottom: 1.5rem;
    }
    .convert-breadcrumb ol {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748b;
    }
    .convert-breadcrumb a {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .convert-breadcrumb a:hover {
        color: #1eb349;
    }
    .convert-breadcrumb .breadcrumb-separator {
        color: #cbd5e1;
    }
    .convert-breadcrumb .active span {
        color: #0f172a;
        font-weight: 700;
    }

    /* Hero Section */
    .convert-hero {
        text-align: center;
        max-width: 780px;
        margin: 0 auto 2.5rem;
    }
    .convert-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        margin-bottom: 1rem;
    }
    .convert-hero-title {
        font-size: 2.15rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 0.85rem;
        letter-spacing: -0.02em;
    }
    .convert-hero-desc {
        font-size: 0.95rem;
        color: #64748b;
        line-height: 1.6;
        margin: 0 auto;
    }

    /* Main Card */
    .convert-main-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        padding: 1.75rem;
        margin-bottom: 3.5rem;
    }

    /* Settings Grid */
    .convert-settings-grid {
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        gap: 1.5rem;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .setting-col {
        display: flex;
        flex-direction: column;
    }
    .setting-label {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
    }
    .setting-label svg {
        color: #1eb349;
    }

    /* Format Selectors */
    .format-selectors-wrap {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .format-select-group {
        flex: 1;
    }
    .format-select-group .sub-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.35rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .custom-select-box {
        position: relative;
    }
    .form-select-control {
        width: 100%;
        appearance: none;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.6rem 2rem 0.6rem 0.75rem;
        font-size: 0.82rem;
        font-weight: 600;
        font-family: 'Montserrat', sans-serif;
        color: #1e293b;
        cursor: pointer;
        outline: none;
        transition: all 0.15s ease;
    }
    .form-select-control:focus {
        border-color: #1eb349;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
    }
    .select-chevron {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #64748b;
    }
    .format-arrow-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        padding-top: 1.15rem;
    }

    /* Quality Controls */
    .quality-toggle-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .quality-toggle-header .setting-label {
        margin-bottom: 0;
    }
    .switch-toggle-wrap {
        position: relative;
        display: inline-block;
        width: 42px;
        height: 24px;
    }
    .toggle-checkbox {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .25s;
        border-radius: 34px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .25s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    .toggle-checkbox:checked + .toggle-slider {
        background-color: #1eb349;
    }
    .toggle-checkbox:checked + .toggle-slider:before {
        transform: translateX(18px);
    }

    .quality-slider-container {
        transition: opacity 0.2s ease;
    }
    .quality-slider-container.disabled {
        opacity: 0.45;
        pointer-events: none;
    }
    .slider-val-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.4rem;
    }
    .slider-text {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
    }
    .slider-badge-val {
        font-size: 0.85rem;
        font-weight: 800;
        color: #15803d;
        background: #dcfce7;
        padding: 0.15rem 0.55rem;
        border-radius: 6px;
    }
    .custom-range-slider {
        width: 100%;
        accent-color: #1eb349;
        cursor: pointer;
        height: 6px;
        border-radius: 4px;
        margin-bottom: 0.4rem;
    }
    .quality-level-indicator {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 500;
    }
    .indicator-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #1eb349;
        flex-shrink: 0;
    }

    /* Drop Zone */
    .convert-drop-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        background: #ffffff;
        padding: 3rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    .convert-drop-zone:hover,
    .convert-drop-zone.dragover {
        border-color: #1eb349;
        background: #f0fdf4;
    }
    .hidden-file-input {
        display: none;
    }
    .drop-icon-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(30, 179, 73, 0.1);
        color: #1eb349;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }
    .drop-zone-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }
    .drop-zone-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 1.25rem;
    }
    .btn-select-files {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #1eb349;
        color: #ffffff;
        border: none;
        padding: 0.75rem 1.75rem;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.28);
        transition: all 0.15s ease;
    }
    .btn-select-files:hover {
        background: #16a34a;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(30, 179, 73, 0.35);
    }
    .drop-format-support {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-top: 1.5rem;
        font-size: 0.75rem;
        color: #94a3b8;
    }
    .support-privacy-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        color: #15803d;
        font-weight: 600;
    }

    /* Queue Action Bar */
    .queue-action-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1rem 0 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        margin-top: 1rem;
    }
    .queue-count-info {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
    }
    .queue-action-buttons {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-wrap: wrap;
    }
    .btn-queue-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.15rem;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        cursor: pointer;
        transition: all 0.15s ease;
        border: none;
    }
    .btn-clear-all {
        background: #fee2e2;
        color: #dc2626;
    }
    .btn-clear-all:hover {
        background: #fca5a5;
    }
    .btn-download-all {
        background: #0f172a;
        color: #ffffff;
    }
    .btn-download-all:hover {
        background: #1e293b;
    }
    .btn-convert-now {
        background: #1eb349;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.28);
    }
    .btn-convert-now:hover {
        background: #16a34a;
    }

    /* File Queue List */
    .file-queue-container {
        margin-top: 1rem;
    }
    .file-queue-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .queue-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.15s ease;
    }
    .queue-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .queue-thumb {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        object-fit: cover;
        background: #f1f5f9;
        flex-shrink: 0;
    }
    .queue-details {
        flex: 1;
        min-width: 0;
    }
    .queue-filename {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0.25rem;
    }
    .queue-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: #64748b;
    }
    .badge-fmt {
        background: #f1f5f9;
        color: #334155;
        font-weight: 700;
        padding: 0.1rem 0.45rem;
        border-radius: 4px;
        text-transform: uppercase;
        font-size: 0.68rem;
    }
    .badge-fmt.target {
        background: #dcfce7;
        color: #15803d;
    }
    .queue-status-col {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .queue-status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
    }
    .queue-status-badge.pending {
        background: #f1f5f9;
        color: #64748b;
    }
    .queue-status-badge.processing {
        background: #fef3c7;
        color: #d97706;
    }
    .queue-status-badge.success {
        background: #dcfce7;
        color: #15803d;
    }
    .queue-status-badge.error {
        background: #fee2e2;
        color: #dc2626;
    }
    .saved-pill {
        font-size: 0.72rem;
        font-weight: 700;
        background: #dcfce7;
        color: #15803d;
        padding: 0.15rem 0.45rem;
        border-radius: 4px;
    }
    .btn-download-item {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #1eb349;
        color: #ffffff;
        border: none;
        padding: 0.45rem 0.85rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s;
    }
    .btn-download-item:hover {
        background: #16a34a;
    }
    .btn-remove-item {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0.35rem;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s;
    }
    .btn-remove-item:hover {
        color: #ef4444;
        background: #fee2e2;
    }

    /* SEO Content Sections */
    .section-title {
        font-size: 1.45rem;
        font-weight: 800;
        color: #0f172a;
        text-align: center;
        margin-bottom: 0.5rem;
        letter-spacing: -0.01em;
    }
    .section-subtitle {
        font-size: 0.88rem;
        color: #64748b;
        text-align: center;
        max-width: 650px;
        margin: 0 auto 2rem;
    }

    /* 3 Step Guide */
    .convert-guide-section {
        margin-bottom: 3.5rem;
    }
    .guide-steps-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
    .guide-step-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.5rem;
        position: relative;
        text-align: center;
    }
    .step-num-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #475569;
        font-size: 0.8rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .step-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: #f0fdf4;
        color: #1eb349;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0.5rem auto 1rem;
    }
    .step-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.45rem;
    }
    .step-desc {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.55;
    }

    /* Features Section */
    .convert-features-section {
        margin-bottom: 3.5rem;
    }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .feature-card {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 1.25rem;
    }
    .feature-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #1eb349;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.85rem;
    }
    .feature-card h4 {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }
    .feature-card p {
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.55;
        margin: 0;
    }

    /* FAQ Section */
    .convert-faq-section {
        max-width: 800px;
        margin: 0 auto;
    }
    .faq-accordion-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .faq-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        transition: all 0.2s ease;
    }
    .faq-item[open] {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .faq-question {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        cursor: pointer;
        list-style: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .faq-question::-webkit-details-marker {
        display: none;
    }
    .faq-icon {
        color: #94a3b8;
        transition: transform 0.2s ease;
    }
    .faq-item[open] .faq-icon {
        transform: rotate(180deg);
        color: #1eb349;
    }
    .faq-answer {
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f1f5f9;
        font-size: 0.84rem;
        color: #64748b;
        line-height: 1.6;
    }

    /* Responsive adjustments */
    @media (max-width: 900px) {
        .convert-settings-grid {
            grid-template-columns: 1fr;
        }
        .guide-steps-grid {
            grid-template-columns: 1fr;
        }
        .features-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .convert-hero-title {
            font-size: 1.55rem;
        }
        .features-grid {
            grid-template-columns: 1fr;
        }
        .format-selectors-wrap {
            flex-direction: column;
            align-items: stretch;
        }
        .format-arrow-icon {
            padding: 0;
            transform: rotate(90deg);
        }
        .queue-item {
            flex-direction: column;
            align-items: flex-start;
        }
        .queue-status-col {
            width: 100%;
            justify-content: space-between;
            margin-top: 0.5rem;
        }
    }
</style>

{{-- Client-Side Converter Script --}}
<script>
(function() {
    // State
    var queue = [];
    var dropZone = document.getElementById('dropZone');
    var fileInput = document.getElementById('fileInput');
    var queueContainer = document.getElementById('fileQueueContainer');
    var queueList = document.getElementById('fileQueueList');
    var queueActionBar = document.getElementById('queueActionBar');
    var queueTotalCount = document.getElementById('queueTotalCount');
    var btnStartConvert = document.getElementById('btnStartConvert');
    var btnDownloadAll = document.getElementById('btnDownloadAll');

    var sourceFormatSelect = document.getElementById('sourceFormatSelect');
    var targetFormatSelect = document.getElementById('targetFormatSelect');
    var enableQualityToggle = document.getElementById('enableQualityToggle');
    var qualitySliderContainer = document.getElementById('qualitySliderContainer');
    var qualityRangeInput = document.getElementById('qualityRangeInput');
    var qualityValDisplay = document.getElementById('qualityValDisplay');
    var qualityPresetDesc = document.getElementById('qualityPresetDesc');

    // Quality slider handler
    if (enableQualityToggle) {
        enableQualityToggle.addEventListener('change', function() {
            if (this.checked) {
                qualitySliderContainer.classList.remove('disabled');
            } else {
                qualitySliderContainer.classList.add('disabled');
            }
        });
    }

    if (qualityRangeInput) {
        qualityRangeInput.addEventListener('input', function() {
            var val = parseInt(this.value, 10);
            qualityValDisplay.textContent = val;
            if (val >= 85) {
                qualityPresetDesc.textContent = 'Kualitas Tinggi: Visual nyaris tanpa penurunan kompresi.';
            } else if (val >= 70) {
                qualityPresetDesc.textContent = 'Rekomendasi Seimbang: Ukuran file hemat hingga 60% dengan visual tajam.';
            } else if (val >= 40) {
                qualityPresetDesc.textContent = 'Kompresi Tinggi: Ukuran berkas sangat ringan untuk website dan sosmed.';
            } else {
                qualityPresetDesc.textContent = 'Kompresi Ekstrem: Ukuran berkas paling minimal.';
            }
        });
    }

    // Drag & Drop handlers
    ['dragenter', 'dragover'].forEach(function(eventName) {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('dragover');
        }, false);
    });

    ['dragleave', 'drop'].forEach(function(eventName) {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('dragover');
        }, false);
    });

    dropZone.addEventListener('drop', function(e) {
        var dt = e.dataTransfer;
        var files = dt.files;
        if (files && files.length) {
            handleFiles(files);
        }
    });

    dropZone.addEventListener('click', function(e) {
        if (e.target.tagName !== 'BUTTON' && !e.target.closest('button')) {
            fileInput.click();
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length) {
            handleFiles(this.files);
            this.value = ''; // Reset input so same file can be re-selected
        }
    });

    function formatBytes(bytes, decimals) {
        if (bytes === 0) return '0 Bytes';
        var k = 1024;
        var dm = decimals < 0 ? 0 : (decimals || 1);
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function getExtension(mime) {
        var map = {
            'image/jpeg': 'jpg',
            'image/png': 'png',
            'image/webp': 'webp',
            'image/avif': 'avif',
            'image/bmp': 'bmp',
            'image/gif': 'gif'
        };
        return map[mime] || 'jpg';
    }

    function handleFiles(files) {
        var targetMime = targetFormatSelect.value;
        var srcFilter = sourceFormatSelect.value;

        Array.from(files).forEach(function(file) {
            if (!file.type.match(/^image\//i) && !file.name.match(/\.(heic|heif|webp|avif|bmp|gif|jpg|jpeg|png|svg)$/i)) {
                return;
            }

            // If manual source filter is specified (not auto), skip mismatch
            if (srcFilter !== 'auto' && file.type && file.type !== srcFilter) {
                return;
            }

            var item = {
                id: 'img_' + Math.random().toString(36).substr(2, 9),
                file: file,
                name: file.name,
                originalSize: file.size,
                originalMime: file.type || 'image/jpeg',
                targetMime: targetMime,
                status: 'pending', // pending, converting, done, error
                convertedBlob: null,
                convertedSize: 0,
                convertedUrl: null,
                previewUrl: null
            };

            queue.push(item);
        });

        renderQueue();
    }

    function renderQueue() {
        if (queue.length === 0) {
            queueContainer.style.display = 'none';
            queueActionBar.style.display = 'none';
            return;
        }

        queueContainer.style.display = 'block';
        queueActionBar.style.display = 'flex';
        queueTotalCount.textContent = queue.length;

        var hasFinished = queue.some(function(it) { return it.status === 'done'; });
        btnDownloadAll.style.display = hasFinished ? 'inline-flex' : 'none';

        queueList.innerHTML = '';

        queue.forEach(function(item, index) {
            var itemEl = document.createElement('div');
            itemEl.className = 'queue-item';
            itemEl.id = 'queue-el-' + item.id;

            var origExt = item.name.split('.').pop().toUpperCase();
            var targetExt = getExtension(item.targetMime).toUpperCase();

            var statusBadge = '';
            var actionBtn = '';

            if (item.status === 'pending') {
                statusBadge = '<span class="queue-status-badge pending">Siap Convert</span>';
            } else if (item.status === 'converting') {
                statusBadge = '<span class="queue-status-badge processing">Mengonversi...</span>';
            } else if (item.status === 'done') {
                var pctSaved = Math.round(((item.originalSize - item.convertedSize) / item.originalSize) * 100);
                var savedHtml = pctSaved > 0 ? '<span class="saved-pill">-' + pctSaved + '%</span>' : '';
                statusBadge = '<span class="queue-status-badge success">Selesai</span>' + savedHtml;
                actionBtn = '<a href="' + item.convertedUrl + '" download="' + getConvertedName(item.name, item.targetMime) + '" class="btn-download-item">' +
                    '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>' +
                    '<span>Download</span></a>';
            } else if (item.status === 'error') {
                statusBadge = '<span class="queue-status-badge error">Gagal</span>';
            }

            var sizeText = formatBytes(item.originalSize);
            if (item.status === 'done' && item.convertedSize > 0) {
                sizeText += ' -> ' + formatBytes(item.convertedSize);
            }

            itemEl.innerHTML = 
                '<img class="queue-thumb" id="thumb-' + item.id + '" src="' + (item.previewUrl || 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'52\' height=\'52\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23cbd5e1\' stroke-width=\'1\'%3E%3Crect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/%3E%3C/svg%3E') + '" alt="Thumb">' +
                '<div class="queue-details">' +
                    '<div class="queue-filename">' + escapeHtml(item.name) + '</div>' +
                    '<div class="queue-meta">' +
                        '<span class="badge-fmt">' + origExt + '</span>' +
                        '<span>-></span>' +
                        '<span class="badge-fmt target">' + targetExt + '</span>' +
                        '<span>-</span>' +
                        '<span>' + sizeText + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="queue-status-col">' +
                    statusBadge +
                    actionBtn +
                    '<button type="button" class="btn-remove-item" onclick="removeQueueItem(' + index + ')" title="Hapus foto ini">' +
                        '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                    '</button>' +
                '</div>';

            queueList.appendChild(itemEl);

            // Lazy generate preview url
            if (!item.previewUrl) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    item.previewUrl = e.target.result;
                    var imgEl = document.getElementById('thumb-' + item.id);
                    if (imgEl) imgEl.src = e.target.result;
                };
                reader.readAsDataURL(item.file);
            }
        });
    }

    function escapeHtml(str) {
        return (str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function getConvertedName(origName, targetMime) {
        var baseName = origName.replace(/\.[^/.]+$/, '');
        var ext = getExtension(targetMime);
        return baseName + '.' + ext;
    }

    window.removeQueueItem = function(index) {
        if (queue[index]) {
            if (queue[index].convertedUrl) {
                URL.revokeObjectURL(queue[index].convertedUrl);
            }
            queue.splice(index, 1);
            renderQueue();
        }
    };

    window.clearAllFiles = function() {
        queue.forEach(function(it) {
            if (it.convertedUrl) URL.revokeObjectURL(it.convertedUrl);
        });
        queue = [];
        renderQueue();
    };

    window.startConvertQueue = function() {
        var targetMime = targetFormatSelect.value;
        var isQualityEnabled = enableQualityToggle.checked;
        var qualityVal = isQualityEnabled ? (parseInt(qualityRangeInput.value, 10) / 100) : 0.92;

        btnStartConvert.disabled = true;
        btnStartConvert.style.opacity = '0.6';

        var pendingItems = queue.filter(function(it) { return it.status !== 'done'; });
        if (pendingItems.length === 0) {
            btnStartConvert.disabled = false;
            btnStartConvert.style.opacity = '1';
            return;
        }

        var currentIndex = 0;

        function processNext() {
            if (currentIndex >= queue.length) {
                btnStartConvert.disabled = false;
                btnStartConvert.style.opacity = '1';
                renderQueue();
                return;
            }

            var item = queue[currentIndex];
            if (item.status === 'done') {
                currentIndex++;
                processNext();
                return;
            }

            item.status = 'converting';
            item.targetMime = targetMime;
            renderQueue();

            convertSingleImage(item, targetMime, qualityVal, function(err, resultBlob) {
                if (err) {
                    item.status = 'error';
                } else {
                    item.status = 'done';
                    item.convertedBlob = resultBlob;
                    item.convertedSize = resultBlob.size;
                    item.convertedUrl = URL.createObjectURL(resultBlob);
                }
                currentIndex++;
                processNext();
            });
        }

        processNext();
    };

    function convertSingleImage(item, targetMime, quality, callback) {
        var img = new Image();
        var objectUrl = URL.createObjectURL(item.file);

        img.onload = function() {
            URL.revokeObjectURL(objectUrl);
            try {
                var canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth || img.width;
                canvas.height = img.naturalHeight || img.height;

                var ctx = canvas.getContext('2d');
                if (!ctx) {
                    callback(new Error('Canvas context not available'));
                    return;
                }

                // If converting to JPEG or BMP which do not support alpha channel, fill background with white
                if (targetMime === 'image/jpeg' || targetMime === 'image/bmp') {
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                }

                ctx.drawImage(img, 0, 0);

                if (canvas.toBlob) {
                    canvas.toBlob(function(blob) {
                        if (blob) {
                            callback(null, blob);
                        } else {
                            callback(new Error('Conversion to blob failed'));
                        }
                    }, targetMime, quality);
                } else {
                    // Fallback using dataURL
                    var dataUrl = canvas.toDataURL(targetMime, quality);
                    var bin = atob(dataUrl.split(',')[1]);
                    var arr = new Uint8Array(bin.length);
                    for (var i = 0; i < bin.length; i++) {
                        arr[i] = bin.charCodeAt(i);
                    }
                    var blob = new Blob([arr], { type: targetMime });
                    callback(null, blob);
                }
            } catch (err) {
                callback(err);
            }
        };

        img.onerror = function() {
            URL.revokeObjectURL(objectUrl);
            callback(new Error('Failed to load image file'));
        };

        img.src = objectUrl;
    }

    window.downloadAllConverted = function() {
        var doneItems = queue.filter(function(it) { return it.status === 'done' && it.convertedUrl; });
        if (doneItems.length === 0) return;

        doneItems.forEach(function(item, idx) {
            setTimeout(function() {
                var a = document.createElement('a');
                a.href = item.convertedUrl;
                a.download = getConvertedName(item.name, item.targetMime);
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }, idx * 250);
        });
    };
})();
</script>
@endsection

