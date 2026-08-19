@if(isset($testimonials) && $testimonials->count())
    {{-- ════ PREMIUM TESTIMONIALS CSS ════ --}}
    <style>
    .cv-testi-premium {
        background: #F8FAFC;
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
    }
    .cv-testi-premium::before {
        content: '';
        position: absolute;
        bottom: -200px; left: -200px;
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(14,165,233,0.04) 0%, transparent 70%);
        pointer-events: none;
    }
    .cv-testi-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    .cv-testi-grid-v2 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    .cv-testi-card-v2 {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        padding: 2.5rem 2rem;
        position: relative;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        transition: transform 0.4s cubic-bezier(0.22,1,0.36,1), box-shadow 0.4s;
    }
    .cv-testi-card-v2:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(14,165,233,0.08);
        border-color: rgba(14,165,233,0.2);
    }
    .cv-testi-quote-icon {
        position: absolute;
        top: 2rem; right: 2rem;
        width: 48px; height: 48px;
        color: #F1F5F9;
        transition: color 0.3s;
    }
    .cv-testi-card-v2:hover .cv-testi-quote-icon {
        color: #E0F2FE;
    }
    .cv-testi-stars-v2 {
        color: #F59E0B;
        margin-bottom: 1.25rem;
        display: flex;
        gap: 0.15rem;
    }
    .cv-testi-text-v2 {
        font-size: 1rem;
        line-height: 1.7;
        color: #475569;
        margin-bottom: 2rem;
        font-style: italic;
        position: relative;
        z-index: 1;
    }
    .cv-testi-author-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        border-top: 1px solid #F1F5F9;
        padding-top: 1.5rem;
    }
    .cv-testi-avatar-v2 {
        width: 50px; height: 50px;
        border-radius: 50%;
        object-fit: cover;
        background: #F1F5F9;
    }
    .cv-testi-name-v2 {
        font-weight: 600;
        color: #0F172A;
        font-size: 0.95rem;
        margin-bottom: 0.15rem;
    }
    .cv-testi-pos-v2 {
        font-size: 0.8rem;
        color: #64748B;
    }
    @media (max-width: 1024px) {
        .cv-testi-grid-v2 { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .cv-testi-grid-v2 { grid-template-columns: 1fr; }
    }
    </style>

    <section class="cv-testi-premium" id="testimoni">
        <div class="cv-testi-inner">
            <div style="text-align:center; max-width:600px; margin:0 auto 3.5rem;">
                <div class="cv-adv-section-label" style="justify-content:center;">TESTIMONI KLIEN</div>
                <h2 class="cv-adv-section-title" style="margin-top:0.75rem;">Kata Mereka yang<br>Sudah Menggunakan</h2>
            </div>

            <div class="cv-testi-grid-v2">
                @foreach($testimonials->take(3) as $i => $testi)
                    <div class="cv-testi-card-v2" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                        <svg class="cv-testi-quote-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                        <div class="cv-testi-stars-v2">
                            @for($s = 0; $s < ($testi->rating ?? 5); $s++)
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endfor
                        </div>
                        <p class="cv-testi-text-v2">"{{ $testi->content }}"</p>
                        <div class="cv-testi-author-row">
                            <img src="{{ $testi->photo_url }}" alt="{{ $testi->name }}" class="cv-testi-avatar-v2">
                            <div>
                                <div class="cv-testi-name-v2">{{ $testi->name }}</div>
                                <div class="cv-testi-pos-v2">{{ $testi->position }} — {{ $testi->company }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
