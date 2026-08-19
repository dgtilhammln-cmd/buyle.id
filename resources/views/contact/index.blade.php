@extends('layouts.app')
@section('content')

<style>
/* ═══════════════════════════════════════
   DESIGN TOKENS — seragam dengan semua page
═══════════════════════════════════════ */
:root {
    --c-bg:      #ffffff;
    --c-surface: #F8FAFC;
    --c-card:    #ffffff;
    --c-border:  #E2E8F0;
    --c-text:    #0F172A;
    --c-muted:   #64748B;
    --c-accent:  #1eb349;
    --c-accent-hover: #16a34a;
    --font:      'Montserrat', sans-serif;
    --ease:      cubic-bezier(0.22, 1, 0.36, 1);
}
*, *::before, *::after { box-sizing: border-box; }
body { background: var(--c-bg); font-family: var(--font); color: var(--c-text); }

/* ════ HERO — identik dengan semua page ════ */
.sv-hero-premium {
    position: relative; padding: 9rem 1.5rem 5rem;
    background: var(--c-surface); overflow: hidden;
    border-bottom: 1px solid var(--c-border);
}
.sv-hero-premium::before {
    content:''; position:absolute; top:-150px; right:-100px;
    width:500px; height:500px; border-radius:50%;
    background:radial-gradient(circle,rgba(30,179,73,0.06) 0%,transparent 70%);
    pointer-events:none;
}
.sv-hero-premium::after {
    content:''; position:absolute; bottom:-150px; left:-100px;
    width:600px; height:600px; border-radius:50%;
    background:radial-gradient(circle,rgba(30,179,73,0.04) 0%,transparent 70%);
    pointer-events:none;
}
.sv-hero-inner {
    max-width:1200px; margin:0 auto;
    position:relative; z-index:2; text-align:center;
}
.sv-breadcrumb {
    display:flex; align-items:center; justify-content:center;
    gap:0.5rem; font-size:0.75rem; font-weight:500;
    color:var(--c-muted); margin-bottom:2.5rem; font-family:var(--font);
}
.sv-breadcrumb a { color:var(--c-muted); text-decoration:none; transition:color 0.2s; }
.sv-breadcrumb a:hover { color:var(--c-accent); }
.sv-breadcrumb-sep { font-size:0.6rem; color:var(--c-muted); opacity:0.5; }
.sv-breadcrumb-current { color:var(--c-text); font-weight:600; }
.sv-label {
    display:inline-flex; align-items:center; justify-content:center;
    gap:0.5rem; font-size:0.75rem; font-weight:700;
    letter-spacing:0.15em; text-transform:uppercase;
    color:var(--c-muted); margin-bottom:1.25rem; font-family:var(--font);
}
.sv-label::before {
    content:''; display:block; width:5px; height:5px;
    background:var(--c-accent); border-radius:50%;
}
.sv-title {
    font-size:clamp(2rem,4vw,3.5rem); font-weight:500; color:var(--c-text);
    line-height:1.15; letter-spacing:-0.03em; font-family:var(--font);
    margin-bottom:1.5rem; max-width:800px; margin-left:auto; margin-right:auto;
}
.sv-intro {
    margin:0 auto; max-width:600px; font-size:1rem;
    font-weight:400; color:var(--c-muted); line-height:1.7; font-family:var(--font);
}

/* ════ CONTACT SECTION ════ */
.ct-section {
    padding: 5rem 1.5rem 6rem;
}
.ct-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 4rem;
    align-items: stretch;
}

/* ════ FORM ════ */
.ct-form-card {
    background: var(--c-card);
    border: 1.5px solid var(--c-border);
    border-radius: 24px;
    padding: 2.5rem;
    height: 100%;
}
.ct-form-title {
    font-size: 1.5rem; font-weight: 700; color: var(--c-text);
    margin: 0 0 0.5rem; font-family: var(--font);
    letter-spacing: -0.02em;
}
.ct-form-sub {
    font-size: 0.9rem; color: var(--c-muted);
    margin: 0 0 2.5rem; line-height: 1.6; font-family: var(--font);
}

/* Success alert */
.ct-success {
    background: rgba(34,197,94,0.08);
    border: 1.5px solid rgba(34,197,94,0.25);
    border-radius: 12px; padding: 1.25rem 1.5rem;
    margin-bottom: 2rem;
    display: flex; align-items: center; gap: 0.75rem;
}
.ct-success p { margin:0; color:#16A34A; font-size:0.9375rem; font-family:var(--font); font-weight:500; }

/* Form elements */
.ct-form { display:flex; flex-direction:column; gap:1.25rem; }
.ct-row { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
.ct-field { display:flex; flex-direction:column; gap:0.5rem; }
.ct-label {
    font-size:0.8rem; font-weight:600; color:var(--c-text);
    font-family:var(--font); letter-spacing:0.02em;
}
.ct-required { color:var(--c-accent); }
.ct-input, .ct-select, .ct-textarea {
    width:100%; padding:0.875rem 1.125rem;
    background:var(--c-surface); border:1.5px solid var(--c-border);
    border-radius:12px; font-size:0.9375rem; font-family:var(--font);
    color:var(--c-text); transition:border-color 0.25s, box-shadow 0.25s;
    outline:none;
}
.ct-input::placeholder, .ct-textarea::placeholder { color:#94A3B8; }
.ct-input:focus, .ct-select:focus, .ct-textarea:focus {
    border-color:var(--c-accent);
    box-shadow:0 0 0 3px rgba(30,179,73,0.12);
}
.ct-textarea { resize:vertical; min-height:130px; }
.ct-select { appearance:none; cursor:pointer; }
.ct-error { font-size:0.8rem; color:#EF4444; font-family:var(--font); }

.ct-submit {
    display:inline-flex; align-items:center; gap:0.625rem;
    background:var(--c-accent); color:#fff;
    font-family:var(--font); font-size:1rem; font-weight:600;
    padding:1rem 2rem; border-radius:50px; border:none; cursor:pointer;
    transition:all 0.3s; box-shadow:0 8px 20px rgba(30,179,73,0.25);
    text-decoration:none; align-self:flex-start;
}
.ct-submit:hover { background:var(--c-accent-hover); transform:translateY(-2px); }

/* ════ SIDEBAR ════ */
.ct-sidebar { display:flex; flex-direction:column; gap:1.25rem; height:100%; }
.ct-info-card {
    background:var(--c-card); border:1.5px solid var(--c-border);
    border-radius:20px; padding:1.75rem;
    display:flex; gap:1rem; align-items:flex-start;
    transition:all 0.3s var(--ease);
}
.ct-info-card:hover { border-color:rgba(30,179,73,0.3); transform:translateY(-3px); box-shadow:0 12px 30px rgba(30,179,73,0.08); }
.ct-info-icon {
    width:48px; height:48px; border-radius:14px;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
}
.ct-info-icon.blue { background:#dcfce7; color:var(--c-accent); }
.ct-info-icon.green { background:rgba(37,211,102,0.1); color:#25D366; }
.ct-info-icon.purple { background:rgba(139,92,246,0.1); color:#7C3AED; }
.ct-info-icon.orange { background:rgba(249,115,22,0.1); color:#EA580C; }
.ct-info-body { flex:1; }
.ct-info-label {
    font-size:0.65rem; font-weight:700; text-transform:uppercase;
    letter-spacing:0.15em; color:var(--c-muted);
    margin-bottom:0.375rem; font-family:var(--font);
}
.ct-info-value {
    font-size:0.9375rem; font-weight:600; color:var(--c-text);
    font-family:var(--font); text-decoration:none;
    transition:color 0.2s; display:block; line-height:1.45;
}
.ct-info-value:hover { color:var(--c-accent); }
.ct-info-sub {
    font-size:0.8rem; color:var(--c-muted);
    font-family:var(--font); margin-top:0.2rem;
}

/* WA button */
.ct-wa-card {
    background:linear-gradient(135deg,#25D366,#128C7E);
    border-radius:20px; padding:2rem;
    display:flex; flex-direction:column; gap:1rem;
    flex-grow:1; justify-content:center;
}
.ct-wa-title {
    font-size:1.125rem; font-weight:700; color:#fff;
    font-family:var(--font); margin:0;
}
.ct-wa-sub {
    font-size:0.875rem; color:rgba(255,255,255,0.85);
    font-family:var(--font); line-height:1.55; margin:0;
}
.ct-wa-btn {
    display:inline-flex; align-items:center; gap:0.5rem;
    background:#fff; color:#25D366;
    font-family:var(--font); font-size:0.9rem; font-weight:700;
    padding:0.875rem 1.5rem; border-radius:50px;
    border:none; cursor:pointer; text-decoration:none !important;
    transition:all 0.3s; align-self:flex-start;
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}
.ct-wa-btn:hover { transform:translateY(-2px); box-shadow:0 12px 25px rgba(0,0,0,0.2); }

/* ════ MAP ════ */
.ct-map {
    max-width:1200px; margin:5rem auto 6rem;
    padding: 0 1.5rem;
}
.ct-map-inner {
    width:100%; aspect-ratio:21/9; min-height:350px;
    background:var(--c-surface); border:1.5px solid var(--c-border);
    overflow:hidden; border-radius:20px;
}
.ct-map-inner iframe { width:100%; height:100%; border:none; display:block; }

/* ════ FAQ ════ */
.ct-faq-section {
    background:var(--c-surface); padding:5rem 1.5rem;
    border-top:1px solid var(--c-border);
}
.ct-faq-inner { max-width:800px; margin:0 auto; }
.ct-faq-header { text-align:center; margin-bottom:3rem; }
.ct-faq-label {
    display:inline-flex; align-items:center; justify-content:center;
    gap:0.5rem; font-size:0.75rem; font-weight:700;
    letter-spacing:0.15em; text-transform:uppercase;
    color:var(--c-muted); margin-bottom:1rem; font-family:var(--font);
}
.ct-faq-label::before { content:''; width:4px; height:4px; background:var(--c-accent); border-radius:50%; }
.ct-faq-title {
    font-size:clamp(1.75rem,3vw,2.5rem); font-weight:500;
    color:var(--c-text); letter-spacing:-0.025em; font-family:var(--font);
}
.ct-faq-list { display:flex; flex-direction:column; gap:0.75rem; }
.ct-faq-item {
    background:var(--c-card); border:1.5px solid var(--c-border);
    border-radius:16px; overflow:hidden; transition:border-color 0.3s;
}
.ct-faq-item:hover { border-color:rgba(30,179,73,0.3); }
.ct-faq-item[open] { border-color:var(--c-accent); }
.ct-faq-item summary {
    padding:1.125rem 1.5rem; font-size:0.9375rem; font-weight:600;
    color:var(--c-text); cursor:pointer; list-style:none;
    display:flex; justify-content:space-between; align-items:center;
    gap:1rem; user-select:none; font-family:var(--font);
}
.ct-faq-item summary::-webkit-details-marker { display:none; }
.ct-faq-chevron { flex-shrink:0; transition:transform 0.3s var(--ease); color:var(--c-accent); }
.ct-faq-item[open] .ct-faq-chevron { transform:rotate(180deg); }
.ct-faq-answer {
    padding:1rem 1.5rem 1.5rem;
    border-top:1px solid var(--c-border);
    font-size:0.9rem; color:var(--c-muted);
    line-height:1.75; font-family:var(--font);
}

/* ════ RESPONSIVE ════ */
@media (max-width:1024px) {
    .ct-inner { grid-template-columns:1fr; gap:2.5rem; }
    .ct-sidebar { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
    .ct-wa-card { grid-column:1/-1; }
}
@media (max-width:640px) {
    .sv-hero-premium { padding:7rem 1rem 4rem; }
    .ct-section { padding:3rem 1rem 4rem; }
    .ct-form-card { padding:1.75rem 1.25rem; }
    .ct-row { grid-template-columns:1fr; }
    .ct-sidebar { grid-template-columns:1fr; }
    .ct-map { padding:0 1rem; }
    .ct-map-inner { aspect-ratio:4/3; min-height:280px; }
    .ct-faq-section { padding:3.5rem 1rem; }
}
</style>

{{-- ════ HERO ════ --}}
<section class="sv-hero-premium">
    <div class="sv-hero-inner" data-aos="fade-up">
        <nav class="sv-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route_locale('home') }}">Beranda</a>
            <span class="sv-breadcrumb-sep">/</span>
            <span class="sv-breadcrumb-current">Hubungi Kami</span>
        </nav>
        <div class="sv-label">Kontak</div>
        <h1 class="sv-title">
            Konsultasi Gratis<br>
            dengan Tim Ahli Kami
        </h1>
        <p class="sv-intro">
            Kami siap membantu menemukan solusi ventilasi terbaik untuk bangunan Anda.
            Hubungi kami sekarang — respon cepat, gratis!
        </p>
    </div>
</section>

{{-- ════ CONTACT FORM + INFO ════ --}}
<section class="ct-section">
    <div class="ct-inner">

        {{-- Form --}}
        <div class="ct-form-card" data-aos="fade-up">
            <h2 class="ct-form-title">Kirim Pesan</h2>
            <p class="ct-form-sub">Isi form di bawah dan kami akan segera menghubungi Anda.</p>

            @if(session('success'))
            <div class="ct-success">
                <svg width="20" height="20" fill="none" stroke="#16A34A" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('contact.send') }}" class="ct-form">
                @csrf
                <div class="ct-row">
                    <div class="ct-field">
                        <label class="ct-label">Nama Lengkap <span class="ct-required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="ct-input" placeholder="Nama Anda" required>
                        @error('name')<span class="ct-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="ct-field">
                        <label class="ct-label">Perusahaan</label>
                        <input type="text" name="company" value="{{ old('company') }}" class="ct-input" placeholder="Nama perusahaan">
                    </div>
                </div>
                <div class="ct-row">
                    <div class="ct-field">
                        <label class="ct-label">Email <span class="ct-required">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="ct-input" placeholder="email@anda.com" required>
                        @error('email')<span class="ct-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="ct-field">
                        <label class="ct-label">Telepon <span class="ct-required">*</span></label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="ct-input" placeholder="08xxxxxxxxxx" required>
                        @error('phone')<span class="ct-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="ct-field">
                    <label class="ct-label">Produk yang Diminati</label>
                    <select name="product" class="ct-select">
                        <option value="">-- Pilih Produk --</option>
                        @foreach(['buyle.id 12"','buyle.id 14"','buyle.id 18"','buyle.id 24"','buyle.id Stainless','Instalasi buyle.id','Konsultasi Ventilasi','Lainnya'] as $p)
                        <option value="{{ $p }}" {{ old('product')===$p?'selected':'' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ct-field">
                    <label class="ct-label">Pesan <span class="ct-required">*</span></label>
                    <textarea name="message" class="ct-textarea" placeholder="Ceritakan kebutuhan Anda: jenis bangunan, luas atap, lokasi, jumlah unit yang dibutuhkan, dll." required>{{ old('message') }}</textarea>
                    @error('message')<span class="ct-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="ct-submit">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Kirim Pesan
                </button>
            </form>
        </div>

        {{-- Sidebar Info --}}
        <div class="ct-sidebar">

            {{-- Telepon --}}
            <div class="ct-info-card" data-aos="fade-up">
                <div class="ct-info-icon blue">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8a19.79 19.79 0 01-3.07-8.68A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.9a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                </div>
                <div class="ct-info-body">
                    <div class="ct-info-label">Telepon</div>
                    <a href="tel:{{ $settings['phone'] ?? '031-99171407' }}" class="ct-info-value" data-track="phone">
                        {{ $settings['phone'] ?? '031 - 9917 1407' }}
                    </a>
                    <div class="ct-info-sub">Senin–Sabtu 08.00–17.00 WIB</div>
                </div>
            </div>

            {{-- Email --}}
            <div class="ct-info-card" data-aos="fade-up" data-aos-delay="50">
                <div class="ct-info-icon purple">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <div class="ct-info-body">
                    <div class="ct-info-label">Email</div>
                    <a href="mailto:{{ $settings['email'] ?? 'info@buyle.id' }}" class="ct-info-value" data-track="email">
                        {{ $settings['email'] ?? 'info@buyle.id' }}
                    </a>
                    <div class="ct-info-sub">Balasan dalam 1×24 jam</div>
                </div>
            </div>

            {{-- Alamat --}}
            <div class="ct-info-card" data-aos="fade-up" data-aos-delay="100">
                <div class="ct-info-icon orange">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div class="ct-info-body">
                    <div class="ct-info-label">Alamat</div>
                    @php
                        $addrDetail = $settings['address'] ?? '';
                        $village    = $settings['village_name'] ?? '';
                        $district   = $settings['district_name'] ?? '';
                        $city       = $settings['city_name'] ?? 'Surabaya';
                        $province   = $settings['province_name'] ?? 'Jawa Timur';
                        $postal     = $settings['postal_code'] ?? '';
                        
                        $fullAddressParts = array_filter([$addrDetail, $village, $district, $city, $province, $postal]);
                        $fullAddress = !empty($fullAddressParts) ? implode(', ', $fullAddressParts) : 'Pergudangan Legundi Business Park Blok D-11, Gresik - Jawa Timur';
                    @endphp
                    <span class="ct-info-value" style="cursor:default;">{{ $fullAddress }}</span>
                    <div class="ct-info-sub">Kunjungi kami dengan janji</div>
                </div>
            </div>

            {{-- WhatsApp CTA Card --}}
            @if($wa)
            <div class="ct-wa-card" data-aos="fade-up" data-aos-delay="150">
                <svg width="32" height="32" fill="rgba(255,255,255,0.8)" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                <h3 class="ct-wa-title">Chat WhatsApp Langsung</h3>
                <p class="ct-wa-sub">Respons lebih cepat via WhatsApp. Tim kami online setiap hari kerja.</p>
                <button onclick="openOrderModal('Halaman Kontak')" class="ct-wa-btn" data-track="wa">
                    Chat Sekarang →
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- Map --}}
    <div class="ct-map" data-aos="fade-up">
        <div class="ct-map-inner">
            <iframe src="{{ $settings['maps_embed'] ?? 'https://maps.google.com/maps?q=-7.1583,112.6515&output=embed' }}"
                    allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi buyle.id"></iframe>
        </div>
    </div>
</section>

{{-- ════ FAQ ════ --}}
@if(!empty($faq) && count($faq))
<section class="ct-faq-section" data-aos="fade-up">
    <div class="ct-faq-inner">
        <div class="ct-faq-header">
            <div class="ct-faq-label">FAQ</div>
            <h2 class="ct-faq-title">Pertanyaan yang Sering Ditanya</h2>
        </div>
        <div class="ct-faq-list">
            @foreach($faq as $f)
            <details class="ct-faq-item">
                <summary>
                    {{ $f['q'] }}
                    <svg class="ct-faq-chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </summary>
                <div class="ct-faq-answer">{{ $f['a'] }}</div>
            </details>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
