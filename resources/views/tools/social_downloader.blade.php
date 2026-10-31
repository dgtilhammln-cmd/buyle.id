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
        <div class="dl-rating-pill" aria-label="Rating 4.9 dari 5 bintang berdasarkan 1.480 lebih ulasan pengguna">
            <div class="dl-rating-stars">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            </div>
            <span class="dl-rating-val">4.9 / 5.0</span>
            <span class="dl-rating-divider">·</span>
            <span class="dl-rating-count">1.485+ Penilaian Pengguna</span>
        </div>
        <h1 class="dl-hero-title">Instagram Downloader &amp; TikTok Downloader HD Tanpa Watermark</h1>
        <p class="dl-hero-desc">
            Unduh video TikTok tanpa watermark, Instagram Reels, postingan carousel, cerita story, audio musik MP3, dan foto profil HD gratis, cepat, dan tanpa aplikasi tambahan.
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

                    <div class="dl-input-suffix-actions">
                        <button type="button" class="btn-clear-input" id="btnClear" onclick="clearInputUrl()" style="display:none;" title="Bersihkan kolom">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>

                        <button type="button" class="btn-paste-clipboard" id="btnPaste" onclick="pasteFromClipboard()" title="Tempel dari Clipboard">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                            <span>Tempel</span>
                        </button>
                    </div>
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

            {{-- Loading State with Layout Skeleton --}}
            <div id="dlLoadingState" class="dl-skeleton-wrapper" style="display: none;">
                <div class="dl-skeleton-status-pill">
                    <span class="dl-pulse-dot"></span>
                    <span id="dlLoadingText">Mengambil data media kualitas HD...</span>
                </div>

                <div class="dl-skeleton-card">
                    <div class="dl-skeleton-header">
                        <div class="dl-skeleton-avatar dl-shimmer"></div>
                        <div class="dl-skeleton-header-text">
                            <div class="dl-skeleton-bar dl-sk-w50 dl-shimmer"></div>
                            <div class="dl-skeleton-bar dl-sk-w30 dl-shimmer"></div>
                        </div>
                        <div class="dl-skeleton-pill dl-shimmer"></div>
                    </div>

                    <div class="dl-skeleton-grid">
                        <div class="dl-skeleton-item"><div class="dl-shimmer dl-skeleton-box"></div></div>
                        <div class="dl-skeleton-item"><div class="dl-shimmer dl-skeleton-box"></div></div>
                        <div class="dl-skeleton-item"><div class="dl-shimmer dl-skeleton-box"></div></div>
                        <div class="dl-skeleton-item"><div class="dl-shimmer dl-skeleton-box"></div></div>
                        <div class="dl-skeleton-item"><div class="dl-shimmer dl-skeleton-box"></div></div>
                        <div class="dl-skeleton-item"><div class="dl-shimmer dl-skeleton-box"></div></div>
                    </div>
                </div>
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
                <div class="dl-result-header" id="resHeaderWrap" style="display: none;">
                    <div class="dl-author-wrap" id="resAuthorWrap">
                        <img id="resAuthorAvatar" class="dl-author-avatar" src="" alt="Avatar">
                        <div class="dl-author-info">
                            <div class="dl-author-name" id="resAuthorName">Kreator</div>
                            <div class="dl-author-username" id="resAuthorUsername">@user</div>
                        </div>
                    </div>
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

            {{-- Profile Feed Result Card with Grid and Infinite Scroll --}}
            <div id="dlProfileFeedBox" class="dl-profile-feed-card" style="display: none;">
                <div class="pf-header">
                    <div class="pf-user-row">
                        <div class="pf-avatar-wrap">
                            <img id="pfAvatarImg" class="pf-avatar" src="" alt="Avatar Profil">
                        </div>
                        <div class="pf-info">
                            <div class="pf-username-wrap">
                                <h2 class="pf-username" id="pfUsername">@user</h2>
                                <span class="pf-badge-count" id="pfTotalCount">0 Postingan Tersedia</span>
                            </div>
                            <div class="pf-stats-row" id="pfStatsRow" style="display: none;">
                                <span class="pf-stat-item"><strong id="pfStatPosts">0</strong> Postingan</span>
                                <span class="pf-stat-dot">&bull;</span>
                                <span class="pf-stat-item"><strong id="pfStatFollowers">0</strong> Pengikut</span>
                                <span class="pf-stat-dot">&bull;</span>
                                <span class="pf-stat-item"><strong id="pfStatFollowing">0</strong> Mengikuti</span>
                            </div>
                            <p class="pf-desc">Pilih dan unduh foto maupun video reels berkualitas HD langsung dari galeri postingan publik ini.</p>
                            <div class="pf-action-row" id="pfHeaderActions">
                                {{-- Download DP button injected here --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Posts Grid Header --}}
                <div class="pf-grid-header">
                    <h3 class="pf-grid-title">Galeri Postingan &amp; Reels</h3>
                    <span class="pf-grid-counter" id="pfRenderedCounter">Menampilkan 0 postingan</span>
                </div>

                {{-- Posts Grid --}}
                <div id="pfPostsGrid" class="pf-posts-grid"></div>

                {{-- Sentinel for Infinite Scroll --}}
                <div id="pfSentinel" class="pf-sentinel"></div>

                {{-- Skeleton Loading indicator matching layout --}}
                <div id="pfLoadingMore" class="pf-skeleton-more-grid" style="display: none;">
                    <div class="pf-post-card pf-skeleton-post"><div class="dl-shimmer dl-skeleton-box"></div></div>
                    <div class="pf-post-card pf-skeleton-post"><div class="dl-shimmer dl-skeleton-box"></div></div>
                    <div class="pf-post-card pf-skeleton-post"><div class="dl-shimmer dl-skeleton-box"></div></div>
                </div>

                {{-- Manual Load More Button fallback --}}
                <div id="pfLoadMoreWrap" class="pf-load-more-wrap" style="display: none;">
                    <button type="button" class="btn-load-more-pf" onclick="renderNextProfileBatch()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        <span>Muat Lebih Banyak Postingan</span>
                    </button>
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

                <details class="faq-card">
                    <summary class="faq-q">
                        <span>Bagaimana cara menyimpan audio MP3 dari video TikTok?</span>
                        <svg class="faq-caret" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>
                    <div class="faq-a">
                        <p>Tempelkan tautan video TikTok ke kolom downloader, tekan tombol Download Sekarang, lalu pilih opsi tombol Download Audio Musik MP3 untuk menyimpan lagu atau backsound-nya saja.</p>
                    </div>
                </details>
            </div>
        </div>

        {{-- SEO Keyword Hub & Content --}}
        <div class="dl-keywords-hub">
            <h3 class="hub-title">Pencarian Populer Instagram Downloader &amp; TikTok Downloader</h3>
            <p class="hub-desc">
                Buyle Social Downloader dirancang untuk memenuhi kebutuhan pengunduhan media sosial secara instan, aman, dan tanpa iklan jebakan.
            </p>
            <div class="dl-tags-wrap">
                <span class="dl-tag-item">Instagram Downloader</span>
                <span class="dl-tag-item">TikTok Downloader</span>
                <span class="dl-tag-item">Download Video TikTok Tanpa Watermark</span>
                <span class="dl-tag-item">Download Reels IG HD</span>
                <span class="dl-tag-item">IG Downloader</span>
                <span class="dl-tag-item">TikTok Video Download No Watermark</span>
                <span class="dl-tag-item">TikTok MP3 Downloader</span>
                <span class="dl-tag-item">Download Foto Instagram</span>
                <span class="dl-tag-item">Download Carousel IG</span>
                <span class="dl-tag-item">Download Foto Profil IG HD</span>
                <span class="dl-tag-item">Save Instagram Reels</span>
                <span class="dl-tag-item">Download Story IG</span>
            </div>
        </div>
    </section>
</div>

{{-- JSON-LD Schema with AggregateRating --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Instagram Downloader & TikTok Downloader - Buyle Tools",
  "url": "{{ route('tools.social_downloader') }}",
  "description": "Instagram Downloader dan TikTok Downloader online gratis tercepat. Download video TikTok tanpa watermark, Reels IG HD, foto postingan carousel, cerita story, dan audio MP3 tanpa aplikasi.",
  "applicationCategory": "MultimediaApplication",
  "operatingSystem": "All",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "IDR"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.9",
    "bestRating": "5",
    "worstRating": "1",
    "ratingCount": "1485",
    "reviewCount": "1420"
  }
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Social Media Downloader",
  "applicationCategory": "UtilitiesApplication",
  "operatingSystem": "Web, Android, iOS, Windows, macOS",
  "url": "{{ route('tools.social_downloader') }}",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.9",
    "bestRating": "5",
    "worstRating": "1",
    "ratingCount": "1485",
    "reviewCount": "1420"
  },
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
        max-width: 840px;
        margin: 0 auto 2.5rem;
    }
    .dl-rating-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 0.38rem 0.95rem;
        border-radius: 999px;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        font-size: 0.78rem;
        font-weight: 600;
        color: #334155;
    }
    .dl-rating-stars {
        display: inline-flex;
        align-items: center;
        gap: 0.15rem;
    }
    .dl-rating-val {
        color: #0f172a;
        font-weight: 700;
    }
    .dl-rating-divider {
        color: #cbd5e1;
    }
    .dl-rating-count {
        color: #64748b;
    }
    .dl-hero-title {
        font-size: 2.15rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 0.85rem;
        letter-spacing: -0.02em;
        text-wrap: balance;
        max-width: 820px;
        margin-left: auto;
        margin-right: auto;
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
    .dl-input-suffix-actions {
        position: absolute;
        right: 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .btn-paste-clipboard {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        padding: 0.45rem 0.85rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }
    .btn-paste-clipboard:hover {
        background: #f1f5f9;
        color: #1eb349;
        border-color: #86efac;
    }
    .btn-clear-input {
        display: none;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e2e8f0;
        border: none;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-clear-input:hover {
        background: #fee2e2;
        color: #ef4444;
        transform: scale(1.05);
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

    /* Skeleton Loading & Shimmer Animation */
    @keyframes dlShimmerWave {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .dl-shimmer {
        background: linear-gradient(90deg, #f1f5f9 0%, #e2e8f0 35%, #f8fafc 50%, #e2e8f0 65%, #f1f5f9 100%);
        background-size: 300% 100%;
        animation: dlShimmerWave 1.8s ease-in-out infinite;
    }

    .dl-skeleton-wrapper {
        margin-top: 1.75rem;
    }

    .dl-skeleton-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 0.45rem 1rem;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        margin-bottom: 1rem;
    }

    .dl-pulse-dot {
        width: 8px;
        height: 8px;
        background: #1eb349;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(30, 179, 73, 0.7);
        animation: dlPulseDot 1.6s infinite;
    }

    @keyframes dlPulseDot {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(30, 179, 73, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(30, 179, 73, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(30, 179, 73, 0); }
    }

    .dl-skeleton-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        padding: 1.75rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    .dl-skeleton-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 1.5rem;
    }

    .dl-skeleton-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .dl-skeleton-header-text {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .dl-skeleton-bar {
        height: 12px;
        border-radius: 6px;
    }
    .dl-sk-w50 { width: 45%; max-width: 180px; height: 16px; }
    .dl-sk-w40 { width: 40%; max-width: 150px; }
    .dl-sk-w30 { width: 30%; max-width: 110px; }

    .dl-skeleton-pill {
        width: 110px;
        height: 32px;
        border-radius: 999px;
    }

    .dl-skeleton-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    @media (max-width: 768px) {
        .dl-skeleton-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    .dl-skeleton-item {
        position: relative;
        width: 100%;
        padding-top: 100%;
        border-radius: 14px;
        overflow: hidden;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .dl-skeleton-box {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
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

    /* Profile Feed Styles */
    .dl-profile-feed-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        padding: 2rem;
        margin-top: 1.5rem;
    }
    .pf-header {
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 1.75rem;
    }
    .pf-user-row {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .pf-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }
    .pf-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #1eb349;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.2);
    }
    .pf-info {
        flex: 1;
    }
    .pf-username-wrap {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 0.35rem;
    }
    .pf-username {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .pf-badge-count {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
    }
    .pf-desc {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0 0 1rem;
    }
    .pf-action-row {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .pf-grid-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }
    .pf-grid-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .pf-grid-counter {
        font-size: 0.82rem;
        color: #64748b;
        font-weight: 600;
    }

    .pf-posts-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
    .pf-post-card {
        background: #f8fafc;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .pf-post-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    .pf-thumb-container {
        position: relative;
        width: 100%;
        padding-top: 100%;
        overflow: hidden;
    }
    .pf-thumb-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .pf-post-card:hover .pf-thumb-img {
        transform: scale(1.05);
    }
    .pf-type-badge {
        position: absolute;
        top: 0.65rem;
        right: 0.65rem;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(4px);
        color: #ffffff;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }
    .pf-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.88) 0%, rgba(15, 23, 42, 0.2) 60%, transparent 100%);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding: 0.85rem;
        opacity: 0;
        transition: opacity 0.2s ease;
        z-index: 3;
    }
    .pf-post-card:hover .pf-overlay,
    .pf-thumb-container:active .pf-overlay {
        opacity: 1;
    }
    @media (hover: none) {
        .pf-overlay {
            opacity: 1;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.85) 0%, transparent 60%);
        }
    }
    .btn-pf-download {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #ffffff;
        text-decoration: none;
        padding: 0.55rem 0.95rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        width: 100%;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.4);
        transition: transform 0.15s ease;
    }
    .btn-pf-download:hover {
        transform: scale(1.02);
    }
    .pf-stats-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-top: 0.35rem;
        margin-bottom: 0.6rem;
        font-size: 0.8rem;
        color: #475569;
        flex-wrap: wrap;
    }
    .pf-stat-item strong {
        color: #0f172a;
        font-weight: 700;
    }
    .pf-stat-dot {
        color: #cbd5e1;
        font-size: 0.8rem;
    }

    .pf-sentinel {
        width: 100%;
        height: 20px;
    }
    .pf-skeleton-more-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-top: 1.25rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 768px) {
        .pf-skeleton-more-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }
    .pf-skeleton-post {
        position: relative;
        width: 100%;
        padding-top: 100%;
        border-radius: 14px;
        overflow: hidden;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
    }
    .pf-load-more-wrap {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
    }
    .btn-load-more-pf {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        color: #334155;
        padding: 0.65rem 1.35rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-load-more-pf:hover {
        background: #f8fafc;
        border-color: #1eb349;
        color: #1eb349;
    }

    @media (max-width: 768px) {
        .pf-posts-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.85rem;
        }
        .pf-user-row {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
        .pf-username-wrap {
            justify-content: center;
        }
        .pf-action-row {
            justify-content: center;
        }
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

    /* SEO Keywords Hub */
    .dl-keywords-hub {
        margin-top: 3.5rem;
        padding: 2.25rem 1.75rem;
        background: #f8fafc;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        text-align: center;
    }
    .dl-keywords-hub .hub-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    .dl-keywords-hub .hub-desc {
        font-size: 0.85rem;
        color: #64748b;
        max-width: 640px;
        margin: 0 auto 1.5rem;
        line-height: 1.55;
    }
    .dl-tags-wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
    }
    .dl-tag-item {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.85rem;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #334155;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        transition: all 0.15s ease;
    }
    .dl-tag-item:hover {
        background: #f0fdf4;
        border-color: #86efac;
        color: #16a34a;
        transform: translateY(-1px);
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
    var pasteBtn = document.getElementById('btnPaste');
    var iconEl = document.getElementById('dlInputPlatformIcon');

    if (val.length > 0) {
        clearBtn.style.display = 'inline-flex';
        pasteBtn.style.display = 'none';
    } else {
        clearBtn.style.display = 'none';
        pasteBtn.style.display = 'inline-flex';
    }

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
    var feedBox = document.getElementById('dlProfileFeedBox');
    if (feedBox) feedBox.style.display = 'none';

    loadingBox.style.display = 'block';
    btn.disabled = true;
    btn.style.opacity = '0.6';
    btnLabel.textContent = 'Memproses...';

    var isProfile = (/instagram\.com\/[a-zA-Z0-9_\.]+\/?$/i.test(url) && !url.includes('/p/') && !url.includes('/reel/')) || /^@[a-zA-Z0-9_\.]+$/.test(url);
    var statusText = document.getElementById('dlLoadingText');
    if (statusText) {
        statusText.textContent = isProfile 
            ? 'Sedang mengambil galeri postingan & profil Instagram...' 
            : 'Mengambil media kualitas HD tanpa watermark...';
    }

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

var PROFILE_BATCH_SIZE = 12;
window.allProfilePosts = [];
window.profilePostsRendered = 0;
window.profileScrollObserver = null;

function renderResult(data) {
    if (data.is_profile_feed && data.posts && data.posts.length > 0) {
        renderProfileFeed(data);
    } else {
        var feedBox = document.getElementById('dlProfileFeedBox');
        if (feedBox) feedBox.style.display = 'none';
        renderSingleMediaResult(data);
    }
}

function renderProfileFeed(data) {
    var resultBox = document.getElementById('dlResultBox');
    var feedBox = document.getElementById('dlProfileFeedBox');

    resultBox.style.display = 'none';
    feedBox.style.display = 'block';

    // Header info
    var author = data.author || {};
    var username = author.username || '@user';
    document.getElementById('pfUsername').textContent = username;

    // Tampilkan nominal riil postingan jika tersedia dari profil Instagram
    if (data.nominal_posts) {
        document.getElementById('pfTotalCount').textContent = data.nominal_posts + ' Postingan Tersedia';
    } else {
        document.getElementById('pfTotalCount').textContent = (data.total_posts || (data.posts ? data.posts.length : 0)) + ' Postingan Tersedia';
    }

    // Tampilkan baris statistik profil (Postingan riil, Pengikut, Mengikuti)
    var statsRow = document.getElementById('pfStatsRow');
    if (statsRow) {
        if (author.followers || author.following || data.nominal_posts) {
            statsRow.style.display = 'flex';
            document.getElementById('pfStatPosts').textContent = data.nominal_posts || (data.posts ? data.posts.length : '0');
            document.getElementById('pfStatFollowers').textContent = author.followers || '-';
            document.getElementById('pfStatFollowing').textContent = author.following || '-';
        } else {
            statsRow.style.display = 'none';
        }
    }

    var avatarEl = document.getElementById('pfAvatarImg');
    if (author.avatar) {
        avatarEl.src = author.avatar;
    } else {
        var initial = username.replace('@', '').charAt(0).toUpperCase();
        avatarEl.src = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'%3E%3Crect width=\'80\' height=\'80\' rx=\'40\' fill=\'%231eb349\'/%3E%3Ctext x=\'50%25\' y=\'55%25\' font-family=\'sans-serif\' font-size=\'32\' font-weight=\'bold\' fill=\'%23ffffff\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3E' + initial + '%3C/text%3E%3C/svg%3E';
    }

    // Tombol Download Foto Profil (DP)
    var actionRow = document.getElementById('pfHeaderActions');
    actionRow.innerHTML = '';
    if (data.downloads && data.downloads[0]) {
        var dpBtn = document.createElement('a');
        dpBtn.className = 'btn-dl-action btn-dl-primary';
        dpBtn.href = data.downloads[0].direct_download_url || data.downloads[0].url;
        dpBtn.setAttribute('download', '');
        dpBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg><span>Download Foto Profil (DP) HD</span>';
        actionRow.appendChild(dpBtn);
    }

    // Reset grid
    var grid = document.getElementById('pfPostsGrid');
    grid.innerHTML = '';

    window.allProfilePosts = data.posts || [];
    window.profilePostsRendered = 0;

    // Render batch pertama
    renderNextProfileBatch();

    // Aktifkan lazy load infinite scroll
    setupProfileInfiniteScroll();

    feedBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function renderNextProfileBatch() {
    var posts = window.allProfilePosts || [];
    var start = window.profilePostsRendered;
    var end = Math.min(start + PROFILE_BATCH_SIZE, posts.length);

    if (start >= posts.length) {
        document.getElementById('pfLoadMoreWrap').style.display = 'none';
        document.getElementById('pfLoadingMore').style.display = 'none';
        return;
    }

    var grid = document.getElementById('pfPostsGrid');
    for (var i = start; i < end; i++) {
        var card = createProfilePostCard(posts[i], i);
        grid.appendChild(card);
    }

    window.profilePostsRendered = end;
    document.getElementById('pfRenderedCounter').textContent = 'Menampilkan ' + end + ' dari ' + posts.length + ' postingan';

    var loadMoreWrap = document.getElementById('pfLoadMoreWrap');
    if (end < posts.length) {
        loadMoreWrap.style.display = 'flex';
    } else {
        loadMoreWrap.style.display = 'none';
    }
}

function createProfilePostCard(post, index) {
    var card = document.createElement('div');
    card.className = 'pf-post-card';

    var thumbUrl = post.direct_thumb_url || post.thumb || post.image_url || '';
    var isVideo = post.is_video;
    var dlUrl = post.direct_download_url || post.download_url;

    var typeBadge = isVideo
        ? '<div class="pf-type-badge" title="Video Reels"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg></div>'
        : '<div class="pf-type-badge" title="Foto Postingan"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></div>';

    var fallbackThumbSvg = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'300\' viewBox=\'0 0 300 300\'%3E%3Crect width=\'300\' height=\'300\' fill=\'%23f1f5f9\'/%3E%3Cpath d=\'M120 130a15 15 0 1 0 0-30 15 15 0 0 0 0 30zM80 210h140l-45-60-35 45-20-25-40 40z\' fill=\'%23cbd5e1\'/%3E%3C/svg%3E';

    var finalThumb = thumbUrl || fallbackThumbSvg;

    var html = '<div class="pf-thumb-container">' +
        '<img src="' + finalThumb + '" alt="Post ' + (index + 1) + '" class="pf-thumb-img" loading="lazy" onerror="this.onerror=null; this.src=\'' + fallbackThumbSvg + '\';">' +
        typeBadge +
        '<div class="pf-overlay">' +
            '<a href="' + dlUrl + '" class="btn-pf-download" download title="Download ' + (isVideo ? 'Video' : 'Foto') + ' HD">' +
                '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                    '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>' +
                    '<polyline points="7 10 12 15 17 10"></polyline>' +
                    '<line x1="12" y1="15" x2="12" y2="3"></line>' +
                '</svg>' +
                '<span>' + (isVideo ? 'Download Video' : 'Download Foto') + '</span>' +
            '</a>' +
        '</div>' +
    '</div>';

    card.innerHTML = html;
    return card;
}

function setupProfileInfiniteScroll() {
    if (window.profileScrollObserver) {
        window.profileScrollObserver.disconnect();
    }

    var sentinel = document.getElementById('pfSentinel');
    if (!sentinel || !window.IntersectionObserver) return;

    window.profileScrollObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                var posts = window.allProfilePosts || [];
                if (window.profilePostsRendered < posts.length) {
                    var skeletonLoader = document.getElementById('pfLoadingMore');
                    skeletonLoader.style.display = 'grid';
                    setTimeout(function() {
                        renderNextProfileBatch();
                        skeletonLoader.style.display = 'none';
                    }, 350);
                }
            }
        });
    }, { rootMargin: '300px' });

    window.profileScrollObserver.observe(sentinel);
}

function renderSingleMediaResult(data) {
    var resultBox = document.getElementById('dlResultBox');
    var headerWrap = document.getElementById('resHeaderWrap');

    // Author
    var authorWrap = document.getElementById('resAuthorWrap');
    var author = data.author;
    if (author && (author.avatar || (author.username && author.username !== '@instagram' && author.username !== '@user'))) {
        authorWrap.style.display = 'flex';
        if (headerWrap) headerWrap.style.display = 'flex';
        document.getElementById('resAuthorName').textContent = author.name || 'Kreator';
        document.getElementById('resAuthorUsername').textContent = author.username || '';
        var avatarEl = document.getElementById('resAuthorAvatar');
        if (author.avatar) {
            avatarEl.src = author.avatar;
        } else {
            var initial = (author.name || author.username).replace('@', '').charAt(0).toUpperCase();
            avatarEl.src = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'44\' height=\'44\' viewBox=\'0 0 44 44\'%3E%3Crect width=\'44\' height=\'44\' rx=\'22\' fill=\'%231eb349\'/%3E%3Ctext x=\'50%25\' y=\'55%25\' font-family=\'sans-serif\' font-size=\'18\' font-weight=\'bold\' fill=\'%23ffffff\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3E' + initial + '%3C/text%3E%3C/svg%3E';
        }
    } else {
        // Jika tidak ada info author asli, sembunyikan card author agar tidak polos abu-abu
        authorWrap.style.display = 'none';
        if (headerWrap) headerWrap.style.display = 'none';
    }

    // Platform tag element removed (no badge shown)

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

