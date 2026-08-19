@extends('layouts.app')
@section('content')

{{-- Page Hero --}}
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb" style="margin-bottom:1.25rem;">
            <a href="{{ route_locale('home') }}">Home</a>
            <span class="breadcrumb-sep">/</span>
            <a href="{{ route_locale('gallery') }}">Galeri</a>
            <span class="breadcrumb-sep">/</span>
            <span class="breadcrumb-current">{{ $item->title }}</span>
        </div>
        <div class="section-label" style="margin-bottom:0.75rem;">{{ $item->category }}</div>
        <h1 style="font-size:clamp(1.75rem,4vw,3rem);font-weight:800;color:#fff;letter-spacing:-0.02em;margin:0 0 1rem;max-width:800px;">{{ $item->title }}</h1>
        <div style="display:flex;flex-wrap:wrap;gap:1.25rem;color:var(--text-3,#A0A0A8);font-size:0.875rem;">
            @if($item->client)
            <span style="display:flex;align-items:center;gap:.375rem;">
                <svg width="14" height="14" fill="none" stroke="#a5cf37" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                {{ $item->client }}
            </span>
            @endif
            @if($item->location)
            <span style="display:flex;align-items:center;gap:.375rem;">
                <svg width="14" height="14" fill="none" stroke="#a5cf37" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $item->location }}
            </span>
            @endif
            @if($item->year)
            <span style="display:flex;align-items:center;gap:.375rem;">
                <svg width="14" height="14" fill="none" stroke="#a5cf37" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Tahun {{ $item->year }}
            </span>
            @endif
        </div>
    </div>
</section>

{{-- Main Content --}}
<section style="padding:4rem 0;">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 380px;gap:3rem;align-items:start;">

            {{-- Gallery Image --}}
            <div>
                <div style="border-radius:2px;overflow:hidden;margin-bottom:1.5rem;background:var(--bg-2);">
                    <img src="{{ $item->image_url }}"
                         alt="{{ $item->alt_text }}"
                         style="width:100%;height:auto;display:block;max-height:600px;object-fit:cover;"
                         loading="eager">
                </div>
                @if($item->description || $item->content)
                <div style="background:var(--bg-2,#161618);border:1px solid rgba(255,255,255,0.07);padding:2rem;border-radius:4px;">
                    <h2 style="font-size:1.125rem;font-weight:700;color:#fff;margin:0 0 1rem;">Detail Proyek</h2>
                    @if($item->content)
                    <div class="article-content">{!! $item->content !!}</div>
                    @elseif($item->description)
                    <div style="color:var(--text-3,#A0A0A8);line-height:1.8;font-size:0.9375rem;font-weight:300;">{{ $item->description }}</div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Sidebar Info --}}
            <div style="position:sticky;top:100px;">
                {{-- Project Info Card --}}
                <div style="background:var(--bg-2,#161618);border:1px solid rgba(255,255,255,0.07);padding:1.75rem;margin-bottom:1.5rem;">
                    <h2 style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:#a5cf37;margin:0 0 1.25rem;">Informasi Proyek</h2>
                    <dl style="display:flex;flex-direction:column;gap:0.875rem;">
                        @if($item->client)
                        <div style="display:flex;justify-content:space-between;gap:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(255,255,255,0.05);">
                            <dt style="font-size:0.8rem;color:var(--text-3);">Klien</dt>
                            <dd style="font-size:0.875rem;font-weight:600;color:#fff;text-align:right;">{{ $item->client }}</dd>
                        </div>
                        @endif
                        @if($item->location)
                        <div style="display:flex;justify-content:space-between;gap:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(255,255,255,0.05);">
                            <dt style="font-size:0.8rem;color:var(--text-3);">Lokasi</dt>
                            <dd style="font-size:0.875rem;font-weight:600;color:#fff;text-align:right;">{{ $item->location }}</dd>
                        </div>
                        @endif
                        @if($item->year)
                        <div style="display:flex;justify-content:space-between;gap:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(255,255,255,0.05);">
                            <dt style="font-size:0.8rem;color:var(--text-3);">Tahun</dt>
                            <dd style="font-size:0.875rem;font-weight:600;color:#fff;">{{ $item->year }}</dd>
                        </div>
                        @endif
                        @if($item->category)
                        <div style="display:flex;justify-content:space-between;gap:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(255,255,255,0.05);">
                            <dt style="font-size:0.8rem;color:var(--text-3);">Kategori</dt>
                            <dd style="font-size:0.875rem;font-weight:600;color:#a5cf37;">{{ $item->category }}</dd>
                        </div>
                        @endif
                        @if($item->tags)
                        <div>
                            <dt style="font-size:0.8rem;color:var(--text-3);margin-bottom:0.5rem;">Tags</dt>
                            <dd style="display:flex;flex-wrap:wrap;gap:0.375rem;">
                                @foreach($item->tags_array as $tag)
                                <span style="background:rgba(165,207,55,0.1);border:1px solid rgba(165,207,55,0.2);color:#a5cf37;font-size:0.7rem;padding:0.2rem 0.625rem;border-radius:100px;">{{ $tag }}</span>
                                @endforeach
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- CTA Card --}}
                <div style="background:linear-gradient(135deg,rgba(255,215,0,0.08),rgba(255,215,0,0.03));border:1px solid rgba(255,215,0,0.2);padding:1.75rem;">
                    <h3 style="font-size:1rem;font-weight:700;color:#fff;margin:0 0 0.5rem;">Butuh Proyek Serupa?</h3>
                    <p style="font-size:0.875rem;color:var(--text-3);margin:0 0 1.25rem;line-height:1.7;">Konsultasikan kebutuhan crane & lift Anda dengan tim kami. Gratis!</p>
                    <button onclick="openOrderModal()" class="btn-primary" style="width:100%;justify-content:center;margin-bottom:0.75rem;">
                        Request Order Sekarang
                    </button>
                    @php $wa = \App\Models\WaSetting::primary(); @endphp
                    @if($wa)
                    <button onclick="openOrderModal('Galeri: {{ addslashes($item->title) }}')" class="btn-outline" style="width:100%;justify-content:center;text-align:center;">
                        Konsultasi via WhatsApp
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Related Projects --}}
@if($related->count())
<section style="padding:4rem 0;border-top:1px solid rgba(255,255,255,0.06);">
    <div class="container">
        <div style="margin-bottom:2rem;">
            <div class="section-label" style="margin-bottom:0.5rem;">Galeri</div>
            <h2 class="section-title">Proyek Terkait</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;">
            @foreach($related as $r)
            <a href="{{ route_locale('gallery.show', $r->slug ?? '#') }}" class="gallery-item" style="text-decoration:none;">
                <img src="{{ $r->image_url }}" alt="{{ $r->alt_text }}" loading="lazy">
                <div class="gallery-overlay">
                    <div>
                        <div style="font-size:0.8125rem;font-weight:700;color:#fff;">{{ $r->title }}</div>
                        @if($r->location)<div style="font-size:0.75rem;color:rgba(255,255,255,0.6);">{{ $r->location }}</div>@endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
