@php
    $bioName      = $config['name'] ?? $profile->store_name ?? $username;
    $bioText      = $config['bio'] ?? $profile->store_description ?? 'Solusi digital terpercaya untuk kebutuhan bisnis & personal Anda.';
    $avatarUrl    = !empty($config['avatar'])
        ? asset('storage/' . $config['avatar'])
        : (!empty($config['_user_avatar']) ? (Str::startsWith($config['_user_avatar'], ['http://', 'https://']) ? $config['_user_avatar'] : asset('storage/' . $config['_user_avatar'])) : null);

    $locationText = !empty($config['location']) ? $config['location'] : ($profile->address ?? $profile->store_location ?? null);
    $sellerWa     = !empty($config['wa']) ? $config['wa'] : ($profile->phone ?? '');

    // Footer Custom Colors & Background Image
    $footerBgColor1 = $config['footer_bg_color1'] ?? '#09121a';
    $footerBgColor2 = $config['footer_bg_color2'] ?? '#064e3b';
    $footerBgImg    = !empty($config['footer_bg_image']) ? asset('storage/' . $config['footer_bg_image']) : null;
    $footerOpacity  = isset($config['footer_bg_opacity']) ? ((float)$config['footer_bg_opacity'] / 100) : 0.3;
@endphp

<style>
    /* ── THEME 5 REDESIGNED FOOTER (MATCHING DESIGN MOCKUP) ── */
    .t5-footer-wrapper {
        margin: 3rem auto 2rem;
        max-width: 1280px;
        padding: 0 1.25rem;
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .t5-footer-card {
        position: relative;
        background: linear-gradient(135deg, {{ $footerBgColor1 }} 0%, {{ $footerBgColor2 }} 100%);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 32px;
        padding: 3.5rem 3rem 2rem;
        color: #ffffff;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45), 0 0 40px rgba(30, 179, 73, 0.12);
        overflow: hidden;
    }

    /* Ambient background glow & matrix pattern */
    .t5-footer-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.3;
        pointer-events: none;
        z-index: 2;
    }

    .t5-footer-card::after {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(30, 179, 73, 0.25) 0%, transparent 70%);
        pointer-events: none;
        filter: blur(40px);
        z-index: 2;
    }

    /* Top Navigation Links */
    .t5-footer-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
        margin-bottom: 2.75rem;
        position: relative;
        z-index: 3;
    }

    .t5-footer-nav a {
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.25s ease;
        padding: 0.3rem 0.6rem;
        border-radius: 8px;
    }

    .t5-footer-nav a:hover {
        color: #4ade80;
        background: rgba(255, 255, 255, 0.06);
    }

    /* Center Main Section */
    .t5-footer-center {
        text-align: center;
        max-width: 680px;
        margin: 0 auto;
        position: relative;
        z-index: 3;
    }

    .t5-footer-title {
        font-size: 2.75rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 60%, #4ade80 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.15;
    }

    .t5-footer-sub {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.72);
        line-height: 1.5;
        margin-bottom: 1.75rem;
    }

    /* Capsule Input Bar (Email Address -> Kebutuhan, Subscribe -> Kirim) */
    .t5-capsule-bar {
        position: relative;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.12);
        border: 1.5px solid rgba(255, 255, 255, 0.22);
        border-radius: 999px;
        padding: 0.35rem 0.35rem 0.35rem 1.4rem;
        backdrop-filter: blur(16px);
        max-width: 580px;
        margin: 0 auto;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2), 0 8px 30px rgba(0, 0, 0, 0.25);
        transition: border-color 0.25s ease;
    }

    .t5-capsule-bar:focus-within {
        border-color: #4ade80;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2), 0 0 20px rgba(74, 222, 128, 0.3);
    }

    .t5-capsule-input {
        background: none;
        border: none;
        color: #ffffff;
        font-size: 0.92rem;
        width: 100%;
        outline: none;
        padding-right: 0.5rem;
    }

    .t5-capsule-input::placeholder {
        color: rgba(255, 255, 255, 0.55);
    }

    .t5-capsule-btn {
        background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        color: #ffffff;
        border: none;
        border-radius: 999px;
        padding: 0.7rem 1.6rem;
        font-weight: 700;
        font-size: 0.88rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 4px 16px rgba(30, 179, 73, 0.4);
        transition: all 0.25s ease;
        flex-shrink: 0;
    }

    .t5-capsule-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(30, 179, 73, 0.6);
    }

    /* Bottom Row (Address Left, Credit Center, Social Right) */
    .t5-footer-bottom-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 3.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        z-index: 3;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .t5-footer-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .t5-location-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 999px;
        padding: 0.45rem 0.95rem;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.78rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .t5-location-pill:hover {
        background: rgba(30, 179, 73, 0.2);
        border-color: #4ade80;
        color: #ffffff;
        transform: translateY(-2px);
    }

    .t5-location-icon {
        color: #4ade80;
        flex-shrink: 0;
    }

    .t5-footer-credit {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.6);
        text-align: center;
    }

    .t5-footer-credit a {
        color: #4ade80;
        text-decoration: none;
        font-weight: 600;
    }

    .t5-footer-social-wrap {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .t5-footer-social-wrap .social-row {
        justify-content: flex-end !important;
        margin: 0 !important;
    }

    .t5-footer-social-wrap .social-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff !important;
        transition: all 0.25s ease;
    }

    .t5-footer-social-wrap .social-icon:hover {
        background: #1eb349;
        border-color: #1eb349;
        transform: translateY(-2px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .t5-footer-card {
            padding: 2.25rem 1.25rem 1.5rem;
            border-radius: 24px;
        }

        .t5-footer-title {
            font-size: 1.85rem;
        }

        .t5-footer-nav {
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .t5-footer-bottom-row {
            flex-direction: column;
            text-align: center;
            align-items: center;
            gap: 1rem;
            margin-top: 2.5rem;
        }

        .t5-footer-left {
            justify-content: center;
        }

        .t5-footer-social-wrap .social-row {
            justify-content: center !important;
        }
    }

    /* ── POP-UP LEAD MODAL STYLES ── */
    .t5-lead-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(8, 14, 26, 0.75);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .t5-lead-modal-backdrop.show {
        opacity: 1;
        visibility: visible;
    }

    .t5-lead-modal-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        width: 100%;
        max-width: 460px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12), 0 4px 20px rgba(30, 179, 73, 0.08);
        position: relative;
        transform: translateY(20px) scale(0.97);
        transition: all 0.3s ease;
        color: #1e293b;
    }

    .t5-lead-modal-backdrop.show .t5-lead-modal-card {
        transform: translateY(0) scale(1);
    }

    .t5-modal-close-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        line-height: 1;
    }

    .t5-modal-close-btn:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fca5a5;
    }

    .t5-modal-head-box {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .t5-modal-badge {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        margin-bottom: 0.75rem;
        box-shadow: 0 4px 16px rgba(30, 179, 73, 0.4);
    }

    .t5-modal-h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
        color: #0f172a;
    }

    .t5-modal-subtext {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.5;
    }

    .t5-mform-group {
        margin-bottom: 1rem;
        text-align: left;
    }

    .t5-mform-label {
        display: block;
        font-size: 0.78rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.35rem;
    }

    .t5-mform-input {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.65rem 0.9rem;
        color: #0f172a;
        font-size: 0.85rem;
        font-family: inherit;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .t5-mform-input::placeholder {
        color: #b0bec5;
    }

    .t5-mform-input:focus {
        border-color: #1eb349;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
    }

    .t5-mform-submit-btn {
        width: 100%;
        background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 0.8rem 1.25rem;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 16px rgba(30, 179, 73, 0.4);
        margin-top: 1.25rem;
        transition: all 0.25s;
    }

    .t5-mform-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(30, 179, 73, 0.6);
    }
</style>

<footer class="t5-footer-wrapper" id="footer-section">
    <div class="t5-footer-card">
        @if($footerBgImg)
            <div class="t5-footer-bg-layer" style="position:absolute; inset:0; width:100%; height:100%; background-image:url('{{ $footerBgImg }}'); background-size:cover; background-position:center; opacity:{{ $footerOpacity }}; mix-blend-mode:overlay; pointer-events:none; z-index:1;"></div>
        @endif

        {{-- Navigation Links (Top Row) --}}
        <div class="t5-footer-nav">
            <a href="{{ url('/' . $username) }}">Beranda</a>
            <a href="{{ url('/' . $username) }}#about-section">Profil</a>
            <a href="{{ url('/' . $username . '/produk') }}">Produk / Layanan</a>
            <a href="{{ url('/' . $username) }}#footer-section">Kontak</a>
        </div>

        {{-- Main Center Section --}}
        <div class="t5-footer-center">
            <h2 class="t5-footer-title">Hubungi Kami</h2>
            <p class="t5-footer-sub">Punya pertanyaan atau butuh konsultasi layanan? Tuliskan kebutuhan Anda di bawah ini.</p>

            {{-- Capsule Input Bar --}}
            <div class="t5-capsule-bar">
                <input type="text" id="t5FooterKebutuhanInput" class="t5-capsule-input" placeholder="Tuliskan kebutuhan Anda...">
                <button type="button" class="t5-capsule-btn" onclick="openT5LeadModal()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    <span>Kirim</span>
                </button>
            </div>
        </div>

        {{-- Bottom Row (Location left, Credit center, Social right) --}}
        <div class="t5-footer-bottom-row">
            {{-- Bottom Left: Location Address --}}
            <div class="t5-footer-left">
                @if(!empty($locationText))
                    <a href="https://maps.google.com/?q={{ urlencode($locationText) }}" target="_blank" rel="noopener" class="t5-location-pill">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="t5-location-icon">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span>{{ $locationText }}</span>
                    </a>
                @else
                    <a href="https://maps.google.com/?q=Indonesia" target="_blank" rel="noopener" class="t5-location-pill">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="t5-location-icon">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span>Indonesia</span>
                    </a>
                @endif
            </div>

            {{-- Bottom Center: Credit --}}
            <div class="t5-footer-credit">
                &copy; {{ date('Y') }} <strong>{{ $bioName }}</strong>. All Rights Reserved. Powered by <a href="https://buyle.id" target="_blank" rel="noopener">buyle.id</a> x <a href="https://hvm-digital.id" target="_blank" rel="noopener">HVM Digital</a>
            </div>

            {{-- Bottom Right: Social Icons --}}
            <div class="t5-footer-social-wrap">
                @include('bio._social_icons', ['profile' => $profile, 'config' => $config])
            </div>
        </div>
    </div>
</footer>

{{-- POP-UP LEAD FORM MODAL --}}
<div class="t5-lead-modal-backdrop" id="t5LeadModal">
    <div class="t5-lead-modal-card">
        <button type="button" class="t5-modal-close-btn" onclick="closeT5LeadModal()">&times;</button>
        
        <div class="t5-modal-head-box">
            <div class="t5-modal-badge">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M22 2L11 13"></path>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </div>
            <h3 class="t5-modal-h3">Hubungi Kami</h3>
            <p class="t5-modal-subtext">Isi data di bawah ini untuk berkonsultasi & terhubung langsung via WhatsApp.</p>
        </div>

        <form id="t5LeadForm" onsubmit="submitT5Lead(event)">
            <input type="hidden" name="seller_id" value="{{ $profile->user_id ?? 0 }}">

            <div class="t5-mform-group">
                <label class="t5-mform-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" id="t5LeadName" class="t5-mform-input" placeholder="Contoh: Budi Santoso" required>
            </div>

            <div class="t5-mform-group">
                <label class="t5-mform-label">No. WhatsApp <span style="color:#ef4444;">*</span></label>
                <input type="tel" name="phone" id="t5LeadPhone" class="t5-mform-input" placeholder="Contoh: 081234567890" required>
            </div>

            <div class="t5-mform-group">
                <label class="t5-mform-label">Kota / Perusahaan <span style="color:#ef4444;">*</span></label>
                <input type="text" name="city_company" id="t5LeadCity" class="t5-mform-input" placeholder="Contoh: Surabaya / PT HVM Digital" required>
            </div>

            <div class="t5-mform-group">
                <label class="t5-mform-label">Kebutuhan <span style="color:#ef4444;">*</span></label>
                <textarea name="kebutuhan" id="t5LeadKebutuhan" class="t5-mform-input" rows="3" placeholder="Deskripsikan kebutuhan Anda..." required></textarea>
            </div>

            <button type="submit" id="t5LeadSubmitBtn" class="t5-mform-submit-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                </svg>
                <span>Kirim & Hubungi via WhatsApp</span>
            </button>
        </form>
    </div>
</div>

<script>
    function openT5LeadModal() {
        const inputVal = document.getElementById('t5FooterKebutuhanInput')?.value || '';
        const modalKebutuhan = document.getElementById('t5LeadKebutuhan');
        if (modalKebutuhan && inputVal.trim() !== '') {
            modalKebutuhan.value = inputVal;
        }
        const modal = document.getElementById('t5LeadModal');
        if (modal) modal.classList.add('show');
    }

    function closeT5LeadModal() {
        const modal = document.getElementById('t5LeadModal');
        if (modal) modal.classList.remove('show');
    }

    async function submitT5Lead(e) {
        e.preventDefault();
        const btn = document.getElementById('t5LeadSubmitBtn');
        const form = document.getElementById('t5LeadForm');
        if (!form || !btn) return;

        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.innerHTML = '<span>Menyimpan Lead...</span>';

        const formData = new FormData(form);
        const sellerWa = @json($sellerWa ?? '');

        try {
            const response = await fetch('{{ route("lead.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });
            const resData = await response.json();

            if (resData.success) {
                const name = formData.get('name') || '';
                const phone = formData.get('phone') || '';
                const city = formData.get('city_company') || '';
                const kebutuhan = formData.get('kebutuhan') || '';

                let waNum = sellerWa.replace(/[^0-9]/g, '');
                if (waNum.startsWith('0')) waNum = '62' + waNum.substring(1);
                if (!waNum) waNum = '6281234567890'; // fallback

                const msg = `Halo ${@json($bioName)}, saya *${name}* dari *${city}*.\nNo. WA: ${phone}\n\nKebutuhan:\n${kebutuhan}`;
                const waUrl = `https://wa.me/${waNum}?text=${encodeURIComponent(msg)}`;

                closeT5LeadModal();
                form.reset();
                if (document.getElementById('t5FooterKebutuhanInput')) {
                    document.getElementById('t5FooterKebutuhanInput').value = '';
                }

                window.open(waUrl, '_blank');
            } else {
                alert('Gagal menyimpan lead. Silakan periksa isian Anda.');
            }
        } catch (err) {
            console.error(err);
            alert('Terjadi kesalahan koneksi.');
        } finally {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg> <span>Kirim & Hubungi via WhatsApp</span>';
        }
    }
</script>