@extends('creator.layout')
@section('title', 'Link in Bio · Pilih Tipe Profil')
@section('page_title', 'Link in Bio')

@section('content')
<div style="max-width: 680px; margin: 0 auto; padding-top: 1rem;">

    {{-- Hero --}}
    <div style="text-align:center; margin-bottom:2.5rem;">
        <div style="width:72px; height:72px; border-radius:20px; background:linear-gradient(135deg,#1eb349,#a5cf37); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
            <svg width="36" height="36" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke-linecap="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke-linecap="round"/></svg>
        </div>
        <h2 style="font-size:1.6rem; font-weight:800; color:#0b120c; margin:0 0 0.5rem; font-family:'Montserrat',sans-serif;">Buat Link in Bio Anda</h2>
        <p style="color:#64748b; font-size:0.9rem; line-height:1.6; margin:0;">Satu link untuk semua konten Anda. Pilih tipe profil yang sesuai untuk pengalaman terbaik.</p>
    </div>

    {{-- Role Cards --}}
    <form action="{{ route('creator.bio.set-role') }}" method="POST" id="roleForm">
        @csrf
        <input type="hidden" name="bio_role" id="selectedRole" required>

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem; margin-bottom:2rem;">

            {{-- Content Creator --}}
            <div class="role-card" data-role="content_creator" onclick="selectRole('content_creator', this)">
                <div class="role-icon" style="background:#f0fdf4; color:#1eb349;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 10l4.553-2.069A1 1 0 0 1 21 8.845v6.31a1 1 0 0 1-1.447.894L15 14M3 8a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8z"/></svg>
                </div>
                <div class="role-title">Content Creator</div>
                <div class="role-desc">TikToker, YouTuber, Blogger, Podcaster</div>
            </div>

            {{-- Affiliator --}}
            <div class="role-card" data-role="affiliator" onclick="selectRole('affiliator', this)">
                <div class="role-icon" style="background:#fffbeb; color:#d97706;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
                <div class="role-title">Affiliator</div>
                <div class="role-desc">Shopee Affiliate, Tokopedia, Amazon KOL</div>
            </div>

            {{-- Business / Brand --}}
            <div class="role-card" data-role="business" onclick="selectRole('business', this)">
                <div class="role-icon" style="background:#f0f9ff; color:#0ea5e9;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="role-title">Business / Brand</div>
                <div class="role-desc">Personal Brand, UMKM, Startup, Agensi</div>
            </div>
        </div>

        <div style="text-align:center;">
            <button type="submit" id="proceedBtn" class="btn-primary" style="opacity:0.4; pointer-events:none; font-size:0.95rem; padding:0.85rem 2.5rem;">
                Lanjut Buat Profil →
            </button>
        </div>
    </form>
</div>

<style>
.role-card {
    background: #fff;
    border: 2px solid #e7f0e7;
    border-radius: 20px;
    padding: 1.5rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}
.role-card:hover { border-color: #1eb349; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(30,179,73,0.1); }
.role-card.selected { border-color: #1eb349; background: #f0fdf4; box-shadow: 0 0 0 4px rgba(30,179,73,0.1); }
.role-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
.role-title { font-weight: 800; font-size: 0.9rem; color: #0b120c; margin-bottom: 0.35rem; font-family:'Montserrat',sans-serif; }
.role-desc { font-size: 0.72rem; color: #64748b; line-height: 1.4; }
@media(max-width:600px) { div[style*="grid-template-columns:1fr 1fr 1fr"] { grid-template-columns: 1fr !important; } }
</style>

<script>
function selectRole(role, el) {
    document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selectedRole').value = role;
    const btn = document.getElementById('proceedBtn');
    btn.style.opacity = '1';
    btn.style.pointerEvents = 'auto';
}
</script>
@endsection
