@extends('layouts.app')
@section('title', 'Akses Terbatas — 403 | buyle.id')
@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --c-bg:     #ffffff;
    --c-surface:#F8FAFC;
    --c-border: #E2E8F0;
    --c-text:   #0F172A;
    --c-muted:  #64748B;
    --c-accent: #1eb349;
    --c-accent-hover: #16a34a;
    --font:     'Montserrat', sans-serif;
}
body { background: var(--c-bg); font-family: var(--font); }

.err-page {
    min-height: 80vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 6rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    background: var(--c-surface);
}
.err-page::before {
    content: '';
    position: absolute;
    top: -150px; right: -100px;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(30,179,73,0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.err-page::after {
    content: '';
    position: absolute;
    bottom: -150px; left: -100px;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(30,179,73,0.05) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.err-card {
    position: relative;
    z-index: 2;
    max-width: 540px;
    width: 100%;
    background: #ffffff;
    border: 1.5px solid var(--c-border);
    border-radius: 24px;
    padding: 3rem 2rem;
    box-shadow: 0 20px 50px rgba(15,23,42,0.06);
}
.err-icon-wrap {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #F0FDF4;
    border: 2px solid #BBF7D0;
    color: var(--c-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}
.err-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.85rem;
    border-radius: 999px;
    background: #FEF3C7;
    color: #92400E;
    font-size: 0.75rem;
    font-weight: 700;
    margin-bottom: 1rem;
}
.err-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--c-text);
    margin: 0 0 0.75rem;
    letter-spacing: -0.02em;
}
.err-desc {
    font-size: 0.9rem;
    color: var(--c-muted);
    line-height: 1.6;
    margin: 0 0 2rem;
}
.err-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.err-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.85rem 1.5rem;
    border-radius: 12px;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #fff;
    font-weight: 700;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.2s;
    box-shadow: 0 4px 14px rgba(30,179,73,0.3);
}
.err-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30,179,73,0.4);
}
.err-btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    background: #F1F5F9;
    color: #334155;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.2s;
}
.err-btn-secondary:hover {
    background: #E2E8F0;
    color: var(--c-text);
}
</style>

<div class="err-page">
    <div class="err-card">
        <div class="err-icon-wrap">
            <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>

        <div class="err-badge">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Akses Terbatas (403)
        </div>

        <h1 class="err-title">Yuk, Lengkapi Pendaftaran Creator! 🚀</h1>

        <p class="err-desc">
            {{ $exception->getMessage() ?: 'Halaman ini khusus untuk Creator terdaftar di BUYLE.ID. Jika Anda ingin mulai menjual karya atau produk digital, yuk selesaikan pendaftaran tokomu (Gratis & Cepat)!' }}
        </p>

        <div class="err-actions">
            @auth
                @if(auth()->user()->role === 'buyer')
                    <a href="{{ route('creator.onboarding') }}" class="err-btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        Jadilah Creator Sekarang (Gratis)
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="err-btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Masuk ke Akun Anda
                </a>
            @endauth

            <a href="{{ route('home') }}" class="err-btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

@endsection
