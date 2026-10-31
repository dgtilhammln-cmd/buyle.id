@extends('layouts.app')

@section('title', $seo['title'] ?? 'Download Video TikTok Tanpa Watermark & Reels IG HD Gratis - Buyle Tools')

@section('content')
<div class="dl-page-wrapper">
    {{-- Breadcrumb --}}
    <div class="dl-breadcrumb-container">
        <nav class="dl-breadcrumb" aria-label="Breadcrumb">
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
                <li class="active" aria-current="page"><span>Social Downloader</span></li>
            </ol>
        </nav>
    </div>

    {{-- Hero Section --}}
    <section class="dl-hero">
        <h1 class="dl-hero-title">Download Video TikTok &amp; Reels Instagram HD Gratis</h1>
        <p class="dl-hero-desc">
            Unduh video TikTok tanpa watermark, Instagram Reels, postingan carousel, dan foto profil (DP) kualitas jernih secara instan tanpa perlu aplikasi tambahan.
        </p>
    </section>

    {{-- Main Downloader Card --}}
    <section class="dl-card-section">
        <div class="dl-main-card">
            
            {{-- Platform Tabs --}}
            <div class="dl-platform-tabs">
                <button type="button" class="platform-tab-btn active" data-platform="auto" onclick="switchPlatformTab('auto', this)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Auto Detect</span>
                </button>

                <button type="button" class="platform-tab-btn" data-platform="tiktok" onclick="switchPlatformTab('tiktok', this)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.29 0 .58.04.86.12V9.42a6.33 6.33 0 0 0-.86-.06 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.71a8.21 8.21 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.14z"/>
                    </svg>
                    <span>TikTok (No Watermark)</span>
                </button>

                <button type="button" class="platform-tab-btn" data-platform="instagram" onclick="switchPlatformTab('instagram', this)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                    <span>Instagram (Reels, Post, DP)</span>
                </button>
            </div>

            {{-- Input Search Form --}}
            <form id="dlForm" onsubmit="handleFetchMedia(event)" class="dl-form-box">
                <div class="dl-input-wrapper">
                    <div class="dl-input-icon" id="dlInputPlatformIcon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                        </svg>
                    </div>

                    <input type="text" id="dlUrlInput" name="url" class="dl-url-input" 
                           placeholder="Tempel link TikTok, Reels IG, Post, atau username profil di sini..."
                           autocomplete="off" required>

                    <button type="button" class="btn-paste-clipboard" id="btnPaste" onclick="pasteFromClipboard()" title="Tempel dari Clipboard">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                        <span>Tempel</span>
                    </button>

                    <button type="button" class="btn-clear-input" id="btnClear" onclick="clearInputUrl()" style="display:none;" title="Bersihkan">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <button type="submit" id="btnSubmitDl" class="btn-fetch-dl">
                    <svg class="btn-icon-ready" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <span id="btnSubmitLabel">Download Sekarang</span>
                </button>
            </form>

            <div class="dl-help-hint">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>Contoh: Link video TikTok (vt.tiktok.com/..), Instagram Reel (/reel/..), atau @username untuk foto profil HD</span>
            </div>

            {{-- Loading State --}}
            <div id="dlLoadingState" class="dl-loading-box" style="display: none;">
                <div class="dl-spinner"></div>
                <div class="dl-loading-text">Sedang memproses tautan media Anda...</div>
                <div class="dl-loading-sub">Mohon tunggu beberapa detik, sistem sedang mengambil kualitas terbaik.</div>
            </div>

            {{-- Error Alert --}}
            <div id="dlErrorAlert" class="dl-error-box" style="display: none;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div id="dlErrorMessage" class="dl-error-text">Terjadi kesalahan saat memproses link.</div>
            </div>

            {{-- Media Result Card --}}
            <div id="dlResultBox" class="dl-result-card" style="display: none;">
                <div class="dl-result-header">
                    <div class="dl-author-wrap">
                        <img id="resAuthorAvatar" class="dl-author-avatar" src="" alt="Avatar">
                        <div class="dl-author-info">
                            <div class="dl-author-name" id="resAuthorName">Kreator</div>
                            <div class="dl-author-username" id="resAuthorUsername">@user</div>
                        </div>
                    </div>
                    <div class="dl-platform-tag" id="resPlatformTag">TikTok</div>
                </div>

                <div class="dl-result-body">
                    {{-- Media Preview (Video or Image) --}}
                    <div class="dl-preview-container">
                        <div id="resVideoWrapper" style="display:none;" class="dl-video-wrapper">
                            <video id="resVideoPlayer" controls playsinline preload="metadata" poster="" class="dl-preview-video"></video>
                        </div>
                        <div id="resImageWrapper" style="display:none;" class="dl-image-wrapper">
                            <img id="resImagePlayer" src="" alt="Preview Media" class="dl-preview-img">
                        </div>
                    </div>

                    {{-- Details & Actions --}}
                    <div class="dl-details-container">
                        <h3 class="dl-media-title" id="resMediaTitle">Judul Media</h3>

                        <div class="dl-buttons-stack" id="resDownloadButtons">
                            {{-- Dynamic Download Buttons Injected by JS --}}
                        </div>

                        <div class="dl-safety-note">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            <span>Unduhan aman, tanpa iklan jebakan, dan file langsung tersimpan di galeri perangkat.</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- SEO & Educational Content --}}
    <section class="dl-seo-section">
        {{-- 3 Step Guide --}}
        <div class="dl-guide-block">
            <h2 class="section-title">Cara Download Video TikTok &amp; Reels Instagram</h2>
            <p class="section-subtitle">Tiga langkah mudah untuk mengunduh konten media sosial favorit langsung ke perangkat Anda</p>

            <div class="guide-grid">
                <div class="guide-card">
                    <div class="guide-num">1</div>
                    <div class="guide-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                        </svg>
                    </div>
                    <h4>Salin Tautan Media</h4>
                    <p>Buka aplikasi TikTok atau Instagram, pilih video Reels, postingan foto, atau profil, lalu klik tombol Bagikan dan Salin Tautan.</p>
                </div>

                <div class="guide-card">
                    <div class="guide-num">2</div>
                    <div class="guide-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                    </div>
                    <h4>Tempel Link di Buyle Tools</h4>
                    <p>Tempel tautan yang telah disalin ke dalam kolom pencarian di atas. Sistem akan mendeteksi platform secara otomatis.</p>
                </div>

                <div class="guide-card">
                    <div class="guide-num">3</div>
                    <div class="guide-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </div>
                    <h4>Unduh Kualitas HD</h4>
                    <p>Klik tombol Download Sekarang. Anda dapat memilih unduhan video kualitas HD tanpa watermark, audio musik MP3, atau foto jernih.</p>
                </div>
            </div>
        </div>

        {{-- Features Grid --}}
        <div class="dl-features-block">
            <h2 class="section-title">Fitur Unggulan Buyle Social Downloader</h2>
            <div class="features-grid">
                <div class="feat-card">
                    <div class="feat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                    </div>
                    <h4>TikTok Tanpa Watermark</h4>
                    <p>Dapatkan video TikTok yang bersih tanpa logo watermark atau teks melayang dengan resolusi full HD asli.</p>
                </div>

                <div class="feat-card">
                    <div class="feat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </div>
                    <h4>Instagram Reels &amp; Carousel</h4>
                    <p>Unduh video Reels berdurasi penuh dan postingan slide carousel multi-foto dalam satu kali klik praktis.</p>
                </div>

                <div class="feat-card">
                    <div class="feat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="10" r="3"></circle>
                            <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"></path>
                        </svg>
                    </div>
                    <h4>Download Foto Profil (DP) HD</h4>
                    <p>Lihat dan simpan foto profil Instagram pengguna publik dalam ukuran asli beresolusi tinggi tanpa terpotong.</p>
                </div>

                <div class="feat-card">
                    <div class="feat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <h4>Gratis &amp; Tanpa Registrasi</h4>
                    <p>Gunakan seluruh fitur downloader tanpa perlu login akun, tanpa batas unduhan harian, dan bebas iklan mengganggu.</p>
                </div>
            </div>
        </div>

        {{-- FAQ Accordion --}}
        <div class="dl-faq-block">
            <h2 class="section-title">Pertanyaan Umum (FAQ)</h2>
            <div class="faq-list">
                <details class="faq-card" open>
                    <summary class="faq-q">
                        <span>Apakah video TikTok yang diunduh benar-benar bebas watermark?</span>
                        <svg class="faq-caret" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>
                    <div class="faq-a">
                        <p>Ya, sistem secara otomatis mengambil aliran berkas asli langsung dari server media TikTok dan menghapus tanda air (watermark) serta username pembuat di dalam video.</p>
                    </div>
                </details>

                <details class="faq-card">
                    <summary class="faq-q">
                        <span>Bagaimana cara mengunduh foto profil (DP) Instagram ukuran penuh?</span>
                        <svg class="faq-caret" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>
                    <div class="faq-a">
                        <p>Cukup masukkan tautan profil akun Instagram (contoh: https://instagram.com/username) atau ketikkan username akun (@username) pada kolom input, lalu tekan Download. Sistem akan menampilkan foto profil HD asli untuk disimpan.</p>
                    </div>
                </details>

                <details class="faq-card">
                    <summary class="faq-q">
                        <span>Apakah layanan ini bisa digunakan di iPhone dan Android?</span>
                        <svg class="faq-caret" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>
                    <div class="faq-a">
                        <p>Tentu saja. Buyle Social Downloader berbasis web responsif modern dan kompatibel dengan browser Safari (iOS), Chrome, Firefox, serta browser bawaan smartphone Android tanpa perlu memasang aplikasi tambahan.</p>
                    </div>
                </details>

                <details class="faq-card">
                    <summary class="faq-q">
                        <span>Bisakah mengunduh video dari akun yang dikunci (private)?</span>
                        <svg class="faq-caret" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>
                    <div class="faq-a">
                        <p>Untuk mematuhi kebijakan privasi, sistem hanya dapat mengunduh konten dari akun atau postingan yang bersifat publik. Postingan dari akun privat tidak dapat diakses.</p>
                    </div>
                </details>
            </div>
        </div>
    </section>
</div>

{{-- JSON-LD Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Social Media Downloader - Buyle Tools",
  "url": "{{ route('tools.social_downloader') }}",
  "description": "Download video TikTok tanpa watermark, Instagram Reels HD, dan foto profil Instagram secara gratis dan cepat.",
  "applicationCategory": "MultimediaApplication",
  "operatingSystem": "All",
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
      "name": "Apakah video TikTok yang diunduh benar-benar bebas watermark?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ya, video diunduh tanpa tanda air atau logo watermark melayang."
      }
    },
    {
      "@type": "Question",
      "name": "Bagaimana cara mengunduh foto profil Instagram ukuran penuh?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Cukup masukkan link profil atau @username akun Instagram yang dituju."
      }
    }
  ]
}
</script>

{{-- Styles --}}
<style>
    .dl-page-wrapper {
        max-width: 1140px;
        margin: 0 auto;
        padding: 7rem 1.25rem 4rem;
        font-family: 'Montserrat', sans-serif;
        color: #1e293b;
    }

    /* Breadcrumbs */
    .dl-breadcrumb-container {
        margin-bottom: 1.5rem;
    }
    .dl-breadcrumb ol {
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
    .dl-breadcrumb a {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .dl-breadcrumb a:hover {
        color: #1eb349;
    }
    .dl-breadcrumb .breadcrumb-separator {
        color: #cbd5e1;
    }
    .dl-breadcrumb .active span {
        color: #0f172a;
        font-weight: 700;
    }

    /* Hero */
    .dl-hero {
        text-align: center;
        max-width: 780px;
        margin: 0 auto 2.5rem;
    }
    .dl-hero-title {
        font-size: 2.15rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 0.85rem;
        letter-spacing: -0.02em;
    }
    .dl-hero-desc {
        font-size: 0.95rem;
        color: #64748b;
        line-height: 1.6;
        margin: 0 auto;
    }

    /* Main Card */
    .dl-main-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        padding: 1.75rem;
        margin-bottom: 3.5rem;
    }

    /* Platform Tabs */
    .dl-platform-tabs {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .platform-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 0.55rem 1.15rem;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .platform-tab-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .platform-tab-btn.active {
        background: #f0fdf4;
        border-color: #86efac;
        color: #15803d;
        box-shadow: 0 2px 8px rgba(30, 179, 73, 0.15);
    }

    /* Input Form */
    .dl-form-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .dl-input-wrapper {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
    }
    .dl-input-icon {
        position: absolute;
        left: 1rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        transition: color 0.15s;
    }
    .dl-url-input {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 999px;
        padding: 0.95rem 5.5rem 0.95rem 2.85rem;
        font-size: 0.88rem;
        font-weight: 600;
        font-family: 'Montserrat', sans-serif;
        color: #0f172a;
        outline: none;
        transition: all 0.2s ease;
    }
    .dl-url-input:focus {
        background: #ffffff;
        border-color: #1eb349;
        box-shadow: 0 0 0 4px rgba(30, 179, 73, 0.14);
    }
    .btn-paste-clipboard {
        position: absolute;
        right: 0.6rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        padding: 0.4rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-paste-clipboard:hover {
        background: #f1f5f9;
        color: #1eb349;
        border-color: #86efac;
    }
    .btn-clear-input {
        position: absolute;
        right: 4.8rem;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0.35rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-clear-input:hover {
        color: #ef4444;
    }

    .btn-fetch-dl {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #ffffff;
        border: none;
        padding: 0.95rem 1.85rem;
        border-radius: 999px;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-fetch-dl:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
    }

    .dl-help-hint {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.75rem;
        color: #64748b;
        margin-left: 0.5rem;
    }

    /* Loading Box */
    .dl-loading-box {
        text-align: center;
        padding: 2.5rem 1rem;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        margin-top: 1.5rem;
    }
    .dl-spinner {
        width: 38px;
        height: 38px;
        border: 3.5px solid #e2e8f0;
        border-top-color: #1eb349;
        border-radius: 50%;
        animation: dlSpin 0.75s linear infinite;
        margin: 0 auto 1rem;
    }
    @keyframes dlSpin {
        to { transform: rotate(360deg); }
    }
    .dl-loading-text {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .dl-loading-sub {
        font-size: 0.8rem;
        color: #64748b;
    }

    /* Error Alert */
    .dl-error-box {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 0.85rem 1.15rem;
        border-radius: 12px;
        font-size: 0.84rem;
        font-weight: 600;
        margin-top: 1.25rem;
    }

    /* Result Card */
    .dl-result-card {
        background: #ffffff;
        border: 1.5px solid #bbf7d0;
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 1.75rem;
        box-shadow: 0 8px 24px rgba(30, 179, 73, 0.08);
        animation: dlFadeIn 0.3s ease;
    }
    @keyframes dlFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dl-result-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 1.25rem;
    }
    .dl-author-wrap {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .dl-author-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        background: #f1f5f9;
    }
    .dl-author-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
    }
    .dl-author-username {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
    }
    .dl-platform-tag {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #86efac;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .dl-result-body {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 1.5rem;
        align-items: start;
    }
    .dl-preview-container {
        border-radius: 12px;
        overflow: hidden;
        background: #0f172a;
        max-height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .dl-preview-video {
        width: 100%;
        max-height: 380px;
        display: block;
        border-radius: 12px;
    }
    .dl-preview-img {
        width: 100%;
        height: auto;
        max-height: 380px;
        object-fit: cover;
        display: block;
    }

    .dl-details-container {
        display: flex;
        flex-direction: column;
    }
    .dl-media-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.45;
        margin-bottom: 1.25rem;
    }
    .dl-buttons-stack {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        margin-bottom: 1rem;
    }
    .btn-dl-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
        font-size: 0.88rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }
    .btn-dl-primary {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
    }
    .btn-dl-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
    }
    .btn-dl-secondary {
        background: #f1f5f9;
        color: #1e293b;
        border: 1px solid #cbd5e1;
    }
    .btn-dl-secondary:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .dl-safety-note {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 500;
    }
    .dl-safety-note svg {
        color: #15803d;
        flex-shrink: 0;
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

    .dl-guide-block {
        margin-bottom: 3.5rem;
    }
    .guide-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
    .guide-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.5rem;
        position: relative;
        text-align: center;
    }
    .guide-num {
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
    .guide-icon {
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
    .guide-card h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.45rem;
    }
    .guide-card p {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.55;
    }

    .dl-features-block {
        margin-bottom: 3.5rem;
    }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .feat-card {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 1.25rem;
    }
    .feat-icon {
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
    .feat-card h4 {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }
    .feat-card p {
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.55;
        margin: 0;
    }

    .dl-faq-block {
        max-width: 800px;
        margin: 0 auto;
    }
    .faq-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .faq-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        transition: all 0.2s ease;
    }
    .faq-card[open] {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .faq-q {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        cursor: pointer;
        list-style: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .faq-q::-webkit-details-marker {
        display: none;
    }
    .faq-caret {
        color: #94a3b8;
        transition: transform 0.2s ease;
    }
    .faq-card[open] .faq-caret {
        transform: rotate(180deg);
        color: #1eb349;
    }
    .faq-a {
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f1f5f9;
        font-size: 0.84rem;
        color: #64748b;
        line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .dl-result-body {
            grid-template-columns: 1fr;
        }
        .dl-preview-container {
            max-height: 320px;
        }
        .guide-grid {
            grid-template-columns: 1fr;
        }
        .features-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .dl-hero-title {
            font-size: 1.55rem;
        }
        .dl-form-box {
            flex-direction: column;
            align-items: stretch;
        }
        .btn-fetch-dl {
            width: 100%;
            justify-content: center;
        }
        .features-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

{{-- Client JavaScript Handler --}}
<script>
var currentPlatform = 'auto';

function switchPlatformTab(platform, btn) {
    currentPlatform = platform;
    document.querySelectorAll('.platform-tab-btn').forEach(function(b) {
        b.classList.remove('active');
    });
    btn.classList.add('active');

    var input = document.getElementById('dlUrlInput');
    if (platform === 'tiktok') {
        input.placeholder = 'Tempel link video TikTok di sini...';
    } else if (platform === 'instagram') {
        input.placeholder = 'Tempel link Reels IG, Postingan, atau username profil di sini...';
    } else {
        input.placeholder = 'Tempel link TikTok, Reels IG, Post, atau username profil di sini...';
    }
    input.focus();
}

function pasteFromClipboard() {
    if (navigator.clipboard && navigator.clipboard.readText) {
        navigator.clipboard.readText().then(function(text) {
            if (text) {
                var input = document.getElementById('dlUrlInput');
                input.value = text.trim();
                onUrlInputChange();
                input.focus();
            }
        }).catch(function() {
            promptPasteFallback();
        });
    } else {
        promptPasteFallback();
    }
}

function promptPasteFallback() {
    var input = document.getElementById('dlUrlInput');
    input.focus();
    input.select();
}

function clearInputUrl() {
    var input = document.getElementById('dlUrlInput');
    input.value = '';
    onUrlInputChange();
    input.focus();
}

function onUrlInputChange() {
    var val = document.getElementById('dlUrlInput').value.trim();
    var clearBtn = document.getElementById('btnClear');
    var iconEl = document.getElementById('dlInputPlatformIcon');

    clearBtn.style.display = val ? 'flex' : 'none';

    // Auto-detect platform icon
    if (/tiktok\.com/i.test(val)) {
        iconEl.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="#0f172a"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.29 0 .58.04.86.12V9.42a6.33 6.33 0 0 0-.86-.06 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.71a8.21 8.21 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.14z"/></svg>';
    } else if (/instagram\.com/i.test(val) || /^@[a-zA-Z0-9_\.]+$/.test(val)) {
        iconEl.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#E1306C" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>';
    } else {
        iconEl.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('dlUrlInput');
    if (input) {
        input.addEventListener('input', onUrlInputChange);
    }
});

function handleFetchMedia(e) {
    e.preventDefault();
    var url = document.getElementById('dlUrlInput').value.trim();
    if (!url) return;

    var btn = document.getElementById('btnSubmitDl');
    var btnLabel = document.getElementById('btnSubmitLabel');
    var loadingBox = document.getElementById('dlLoadingState');
    var errorBox = document.getElementById('dlErrorAlert');
    var resultBox = document.getElementById('dlResultBox');

    // Reset states
    errorBox.style.display = 'none';
    resultBox.style.display = 'none';
    loadingBox.style.display = 'block';
    btn.disabled = true;
    btn.style.opacity = '0.6';
    btnLabel.textContent = 'Memproses...';

    fetch('{{ route("tools.social_downloader.fetch") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ url: url })
    })
    .then(function(res) {
        return res.json().then(function(data) {
            return { status: res.status, ok: res.ok, data: data };
        });
    })
    .then(function(resObj) {
        loadingBox.style.display = 'none';
        btn.disabled = false;
        btn.style.opacity = '1';
        btnLabel.textContent = 'Download Sekarang';

        if (!resObj.ok || !resObj.data.success) {
            document.getElementById('dlErrorMessage').textContent = resObj.data.message || 'Gagal memproses media. Pastikan tautan bersifat publik dan benar.';
            errorBox.style.display = 'flex';
            return;
        }

        renderResult(resObj.data);
    })
    .catch(function(err) {
        loadingBox.style.display = 'none';
        btn.disabled = false;
        btn.style.opacity = '1';
        btnLabel.textContent = 'Download Sekarang';

        document.getElementById('dlErrorMessage').textContent = 'Terjadi kesalahan jaringan. Silakan periksa koneksi Anda dan coba lagi.';
        errorBox.style.display = 'flex';
    });
}

function renderResult(data) {
    var resultBox = document.getElementById('dlResultBox');

    // Author
    var author = data.author || {};
    document.getElementById('resAuthorName').textContent = author.name || 'Kreator';
    document.getElementById('resAuthorUsername').textContent = author.username || '@user';
    var avatarEl = document.getElementById('resAuthorAvatar');
    avatarEl.src = author.avatar || 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'44\' height=\'44\' viewBox=\'0 0 24 24\' fill=\'%23cbd5e1\'%3E%3Ccircle cx=\'12\' cy=\'8\' r=\'4\'/%3E%3Cpath d=\'M6 20v-2a6 6 0 0 1 12 0v2\'/%3E%3C/svg%3E';

    // Platform tag
    var tagEl = document.getElementById('resPlatformTag');
    tagEl.textContent = (data.platform || 'Media').toUpperCase();

    // Media Title
    document.getElementById('resMediaTitle').textContent = data.title || 'Pratinjau Media';

    // Video vs Image Preview
    var videoWrap = document.getElementById('resVideoWrapper');
    var imageWrap = document.getElementById('resImageWrapper');
    var videoPlayer = document.getElementById('resVideoPlayer');
    var imagePlayer = document.getElementById('resImagePlayer');

    if (data.is_video && data.video_url) {
        videoWrap.style.display = 'block';
        imageWrap.style.display = 'none';
        videoPlayer.src = data.video_url;
        if (data.preview) videoPlayer.poster = data.preview;
    } else {
        videoWrap.style.display = 'none';
        imageWrap.style.display = 'block';
        imagePlayer.src = data.preview || (data.downloads && data.downloads[0] ? data.downloads[0].url : '');
    }

    // Download buttons
    var btnStack = document.getElementById('resDownloadButtons');
    btnStack.innerHTML = '';

    if (data.downloads && data.downloads.length > 0) {
        data.downloads.forEach(function(item, idx) {
            var btnLink = document.createElement('a');
            btnLink.className = (idx === 0) ? 'btn-dl-action btn-dl-primary' : 'btn-dl-action btn-dl-secondary';
            btnLink.href = item.direct_download_url || item.url;
            btnLink.setAttribute('download', '');

            var iconSvg = (item.type === 'video')
                ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>'
                : (item.type === 'audio'
                    ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>'
                    : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>');

            btnLink.innerHTML = iconSvg + '<span>' + item.label + '</span>';
            btnStack.appendChild(btnLink);
        });
    }

    resultBox.style.display = 'block';
    resultBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
</script>
@endsection

