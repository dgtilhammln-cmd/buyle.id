<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    @if(!empty($seoKeywords))
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endif
    <link rel="canonical" href="{{ $canonical }}">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">

    {{-- Favicon custom Tema 5 / default --}}
    @if(!empty($config['theme5_favicon']))
        <link rel="icon" href="{{ asset('storage/' . $config['theme5_favicon']) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=4">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=4">
    @endif

    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @php
        $bioName = $config['name'] ?? $profile->store_name ?? $username;
        $avatarUrl = null;
        if (!empty($config['avatar']))
            $avatarUrl = asset('storage/' . $config['avatar']);
        elseif (!empty($config['_user_avatar'])) {
            $ua = $config['_user_avatar'];
            $avatarUrl = \Illuminate\Support\Str::startsWith($ua, ['http://', 'https://']) ? $ua : asset('storage/' . $ua);
        }

        $homeUrl = !empty($profile->custom_domain)
            ? 'https://' . rtrim($profile->custom_domain, '/')
            : url('/' . $username);

        $productsUrl = !empty($profile->custom_domain)
            ? 'https://' . rtrim($profile->custom_domain, '/') . '/produk'
            : url('/' . $username . '/produk');

        $contactUrl = !empty($profile->custom_domain)
            ? 'https://' . rtrim($profile->custom_domain, '/') . '/kontak'
            : url('/' . $username . '/kontak');
    @endphp

    @php
        $schemaOrg = \App\Services\BioSchemaBuilder::buildSchema(
            $profile,
            $config,
            null,
            $canonical ?? null,
            $seoDesc ?? null,
            $ogImage ?? null
        );
    @endphp
    <script type="application/ld+json">
    {!! json_encode($schemaOrg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    <style>
        :root {
            --t5-emerald: #1eb349;
            --t5-emerald-dark: #15803d;
            --t5-emerald-light: #f0fdf4;
            --t5-emerald-border: #bbf7d0;
            --t5-slate-900: #0f172a;
            --t5-slate-800: #1e293b;
            --t5-slate-700: #334155;
            --t5-slate-600: #475569;
            --t5-slate-500: #64748b;
            --t5-slate-400: #94a3b8;
            --t5-slate-200: #e2e8f0;
            --t5-bg-main: #f8fafc;
            --t5-white: #ffffff;
            --t5-radius-md: 12px;
            --t5-radius-lg: 20px;
            --t5-shadow-card: 0 10px 30px rgba(15, 23, 42, 0.05);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html { scroll-behavior: smooth; font-size: 16px; }

        body {
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--t5-bg-main);
            color: var(--t5-slate-800);
            line-height: 1.6;
            overflow-x: hidden;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
        }

        /* HEADER NAVBAR */
        .t5-header {
            position: sticky;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--t5-slate-200);
        }

        .t5-header-container {
            max-width: 1340px;
            margin: 0 auto;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .t5-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .t5-brand-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--t5-emerald-border);
        }

        .t5-brand-avatar-fallback {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--t5-emerald), #15803d);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .t5-brand-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--t5-slate-900);
            letter-spacing: -0.02em;
        }

        .t5-nav-desktop {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .t5-nav-link {
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--t5-slate-600);
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .t5-nav-link:hover,
        .t5-nav-link.active {
            color: var(--t5-emerald-dark);
            font-weight: 600;
        }

        .t5-header-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .t5-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.42rem 0.85rem;
            border-radius: var(--t5-radius-sm);
            border: 1px solid var(--t5-slate-200);
            background: #fff;
            color: var(--t5-slate-700);
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .t5-action-btn:hover {
            border-color: var(--t5-emerald);
            color: var(--t5-emerald-dark);
        }

        .t5-btn-wa {
            background: var(--t5-emerald-light);
            color: var(--t5-emerald-dark);
            border-color: var(--t5-emerald-border);
        }

        /* BREADCRUMB BAR (SAME AS PRODUCTS PAGE) */
        .t5-breadcrumb-wrap {
            max-width: 1340px;
            margin: 0 auto;
            padding: 1.25rem 1.5rem 0;
        }

        .t5-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            color: var(--t5-slate-500);
            flex-wrap: wrap;
        }

        .t5-breadcrumb a {
            color: var(--t5-slate-600);
            text-decoration: none;
            font-weight: 400;
            transition: color 0.2s;
        }

        .t5-breadcrumb a:hover {
            color: var(--t5-emerald);
        }

        .t5-breadcrumb-sep { color: var(--t5-slate-400); }
        .t5-breadcrumb-current { color: var(--t5-slate-900); font-weight: 600; }

        /* CONTACT MAIN CONTAINER */
        .contact-container {
            max-width: 1340px;
            margin: 1.5rem auto 4rem;
            padding: 0 1.5rem;
        }

        .contact-header {
            margin-bottom: 2rem;
            text-align: left;
        }

        .contact-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--t5-slate-900);
            letter-spacing: -0.03em;
            margin-bottom: 0.5rem;
        }

        .contact-subtitle {
            font-size: 0.95rem;
            color: var(--t5-slate-600);
            max-width: 650px;
        }

        /* 2-COLUMN GRID LAYOUT */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 900px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }

        /* CARDS */
        .contact-card {
            background: #ffffff;
            border: 1.5px solid var(--t5-slate-200);
            border-radius: var(--t5-radius-lg);
            padding: 2rem;
            box-shadow: var(--t5-shadow-card);
            margin-bottom: 1.5rem;
        }

        .card-heading {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--t5-slate-900);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .card-heading i {
            color: var(--t5-emerald);
            font-size: 1.3rem;
        }

        /* ADDRESS INFO */
        .address-box {
            display: flex;
            gap: 1rem;
            background: #f8fafc;
            padding: 1.2rem;
            border-radius: var(--t5-radius-md);
            border: 1px solid var(--t5-slate-200);
            margin-bottom: 1.25rem;
        }

        .address-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--t5-emerald-light);
            color: var(--t5-emerald-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .address-text {
            font-size: 0.9rem;
            color: var(--t5-slate-700);
            line-height: 1.6;
        }

        /* EMBED MAPS CONTAINER */
        .maps-wrapper {
            border-radius: var(--t5-radius-md);
            overflow: hidden;
            border: 1px solid var(--t5-slate-200);
            background: #f1f5f9;
            min-height: 280px;
            position: relative;
        }

        .maps-wrapper iframe {
            width: 100%;
            height: 320px;
            border: none;
            display: block;
        }

        .maps-placeholder {
            padding: 3rem 1.5rem;
            text-align: center;
            color: var(--t5-slate-500);
            font-size: 0.88rem;
        }

        /* SOCIAL MEDIA LINKS GRID */
        .socials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 0.75rem;
        }

        .social-pill {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.65rem 0.9rem;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid var(--t5-slate-200);
            color: var(--t5-slate-700);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .social-pill:hover {
            border-color: var(--t5-emerald);
            color: var(--t5-emerald-dark);
            background: var(--t5-emerald-light);
            transform: translateY(-2px);
        }

        .social-pill i {
            font-size: 1.15rem;
        }

        /* INLINE LEADS FORM */
        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--t5-slate-700);
        }

        .form-label span.req {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1.5px solid var(--t5-slate-200);
            background: #ffffff;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.88rem;
            color: var(--t5-slate-900);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--t5-emerald);
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
        }

        .btn-submit-lead {
            width: 100%;
            padding: 0.9rem 1.5rem;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 8px 20px rgba(30, 179, 73, 0.25);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-submit-lead:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(30, 179, 73, 0.35);
        }

        /* ALERT MESSAGES */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            padding: 1rem 1.2rem;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.2rem;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 1rem 1.2rem;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 1.2rem;
        }

        /* FOOTER */
        .t5-footer {
            background: var(--t5-slate-900);
            color: var(--t5-slate-400);
            padding: 3rem 1.5rem 2rem;
            margin-top: 4rem;
            border-top: 1px solid #1e293b;
        }

        .t5-footer-inner {
            max-width: 1340px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .t5-footer-text {
            font-size: 0.85rem;
        }

        .t5-footer-links {
            display: flex;
            gap: 1.2rem;
        }

        .t5-footer-links a {
            color: var(--t5-slate-300);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .t5-footer-links a:hover {
            color: var(--t5-emerald);
        }
    </style>
</head>

<body>

    {{-- HEADER NAVBAR (SAME AS HOME & PRODUCTS PAGE) --}}
    @include('bio.theme5.header', ['products' => collect(), 'blocks' => collect(), 'config' => $config, 'profile' => $profile, 'username' => $username])

    {{-- BREADCRUMB --}}
    <div class="t5-breadcrumb-wrap">
        <div class="t5-breadcrumb">
            <a href="{{ $homeUrl }}">Beranda</a>
            <span class="t5-breadcrumb-sep">/</span>
            <span class="t5-breadcrumb-current">Kontak</span>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <main class="contact-container">
        <div class="contact-header">
            <h1 class="contact-title">Hubungi Kami</h1>
            <p class="contact-subtitle">Punya pertanyaan, butuh konsultasi, atau ingin bekerja sama? Silakan isi formulir di bawah atau hubungi kami melalui media sosial resmi kami.</p>
        </div>

        <div class="contact-grid">
            {{-- LEFT COLUMN: ADDRESS, MAPS & SOCIAL MEDIA --}}
            <div>
                {{-- CARD 1: ALAMAT & MAPS --}}
                <div class="contact-card">
                    <h2 class="card-heading">
                        <i class="ph-bold ph-map-pin"></i>
                        Alamat & Lokasi Kantor
                    </h2>

                    <div class="address-box">
                        <div class="address-icon">
                            <i class="ph-bold ph-buildings"></i>
                        </div>
                        <div class="address-text">
                            @if (!empty($contactAddress))
                                {!! nl2br(e($contactAddress)) !!}
                            @else
                                <span style="color: var(--t5-slate-400);">Alamat belum diatur oleh pemilik toko.</span>
                            @endif
                        </div>
                    </div>

                    {{-- MAPS EMBED --}}
                    <div class="maps-wrapper">
                        @if (!empty($contactMapsEmbed))
                            @if (\Illuminate\Support\Str::contains($contactMapsEmbed, '<iframe'))
                                {!! $contactMapsEmbed !!}
                            @else
                                <iframe src="{{ $contactMapsEmbed }}" loading="lazy" allowfullscreen></iframe>
                            @endif
                        @else
                            <div class="maps-placeholder">
                                <i class="ph-bold ph-map-trifold" style="font-size: 2.5rem; color: var(--t5-slate-400); margin-bottom: 0.5rem; display: block;"></i>
                                Peta lokasi Google Maps belum disematkan.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- CARD 2: SOCIAL MEDIA LINKS --}}
                <div class="contact-card">
                    <h2 class="card-heading">
                        <i class="ph-bold ph-share-network"></i>
                        Media Sosial & Kontak Resmi
                    </h2>

                    <div class="socials-grid">
                        @if (!empty($config['wa']))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $config['wa']) }}" target="_blank" class="social-pill">
                                <i class="ph-bold ph-whatsapp-logo" style="color: #25D366;"></i> WhatsApp
                            </a>
                        @endif

                        @if (!empty($config['ig']))
                            <a href="https://instagram.com/{{ ltrim($config['ig'], '@') }}" target="_blank" class="social-pill">
                                <i class="ph-bold ph-instagram-logo" style="color: #E1306C;"></i> Instagram
                            </a>
                        @endif

                        @if (!empty($config['tiktok']))
                            <a href="https://tiktok.com/@{{ ltrim($config['tiktok'], '@') }}" target="_blank" class="social-pill">
                                <i class="ph-bold ph-tiktok-logo" style="color: #000000;"></i> TikTok
                            </a>
                        @endif

                        @if (!empty($config['youtube']))
                            <a href="{{ $config['youtube'] }}" target="_blank" class="social-pill">
                                <i class="ph-bold ph-youtube-logo" style="color: #FF0000;"></i> YouTube
                            </a>
                        @endif

                        @if (!empty($config['facebook']))
                            <a href="{{ $config['facebook'] }}" target="_blank" class="social-pill">
                                <i class="ph-bold ph-facebook-logo" style="color: #1877F2;"></i> Facebook
                            </a>
                        @endif

                        @if (!empty($config['linkedin']))
                            <a href="{{ $config['linkedin'] }}" target="_blank" class="social-pill">
                                <i class="ph-bold ph-linkedin-logo" style="color: #0A66C2;"></i> LinkedIn
                            </a>
                        @endif

                        @if (!empty($config['x']))
                            <a href="https://x.com/{{ ltrim($config['x'], '@') }}" target="_blank" class="social-pill">
                                <i class="ph-bold ph-x-logo" style="color: #000000;"></i> X / Twitter
                            </a>
                        @endif

                        @if (!empty($config['telegram']))
                            <a href="https://t.me/{{ ltrim($config['telegram'], '@') }}" target="_blank" class="social-pill">
                                <i class="ph-bold ph-telegram-logo" style="color: #229ED9;"></i> Telegram
                            </a>
                        @endif

                        @if (!empty($config['website']))
                            <a href="{{ $config['website'] }}" target="_blank" class="social-pill">
                                <i class="ph-bold ph-globe" style="color: #1eb349;"></i> Website
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: INLINE FORM LEADS (NOT A POPUP) --}}
            <div>
                <div class="contact-card">
                    <h2 class="card-heading">
                        <i class="ph-bold ph-paper-plane-tilt"></i>
                        Kirim Pesan &amp; Konsultasi
                    </h2>
                    <p style="font-size: 0.88rem; color: var(--t5-slate-600); margin-bottom: 1.5rem;">
                        Isi formulir di bawah ini dengan lengkap. Tim kami akan segera merespons pesan Anda via WhatsApp.
                    </p>

                    @if(session('success'))
                        <div class="alert-success">
                            <i class="ph-bold ph-check-circle" style="font-size: 1.3rem; flex-shrink: 0;"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert-error">
                            <ul style="padding-left: 1rem; margin: 0;">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('bio.contact.lead', $username) }}" method="POST" class="contact-form">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor WhatsApp / HP <span class="req">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kota / Perusahaan (Opsional)</label>
                            <input type="text" name="city" value="{{ old('city') }}" placeholder="Contoh: Jakarta / PT Maju Jaya" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Pesan / Kebutuhan Anda <span class="req">*</span></label>
                            <textarea name="message" rows="5" placeholder="Tuliskan pertanyaan, penawaran, atau detail kebutuhan Anda disini..." class="form-control" required>{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn-submit-lead">
                            <i class="ph-bold ph-paper-plane-right" style="font-size: 1.15rem;"></i>
                            Kirim Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    {{-- FOOTER TEMA 5 (SAME AS HOME & PRODUCTS PAGE) --}}
    @include('bio.theme5.footer', ['products' => collect(), 'config' => $config, 'profile' => $profile, 'username' => $username])

    <script>
        function toggleT5Drawer() {
            var drawer = document.getElementById('t5MobileDrawer');
            if (drawer) drawer.classList.toggle('active');
        }
    </script>
</body>
</html>
