@extends('auth.layout')

@section('title', 'Daftar via Email – buyle.id')

@section('content')
<div style="margin-bottom: 1.25rem;">
    <a href="{{ route('register') }}" style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.78rem; font-weight:600; color:#64748B; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='#1eb349'" onmouseout="this.style.color='#64748B'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5m7 7l-7-7 7-7"/></svg>
        Pilihan Pendaftaran
    </a>
</div>

<h1 class="auth-title">Daftar Akun Baru</h1>
<p class="auth-subtitle">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>

@if($errors->any())
    <div class="alert-error">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    </div>
@endif

<form method="POST" action="{{ route('register.submit') }}" id="registerEmailForm" novalidate>
    @csrf

    <div class="form-group">
        <label class="form-label" for="name">Nama Lengkap <span>*</span></label>
        <input type="text" id="name" name="name" class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
               placeholder="Nama lengkap Anda" value="{{ old('name') }}" autocomplete="name" required>
    </div>

    <div class="form-group">
        <label class="form-label" for="email">Email <span>*</span></label>
        <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
               placeholder="contoh@email.com" value="{{ old('email') }}" autocomplete="email" required>
    </div>

    <div class="form-group">
        <label class="form-label" for="phone">No. WhatsApp <span style="color:#94A3B8; font-weight:400; font-size:0.72rem;">(Opsional)</span></label>
        <input type="tel" id="phone" name="phone" class="form-input"
               placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" autocomplete="tel">
    </div>

    <div class="form-group" style="margin-bottom:0.75rem;">
        <label class="form-label" for="password">Kata Sandi <span>*</span></label>
        <div class="input-wrap">
            <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Minimal 8 karakter kuat" autocomplete="new-password" required>
            <span class="input-icon" onclick="togglePwd('password', this)" title="Lihat/Sembunyikan">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </span>
        </div>

        {{-- Strength Indicator Meter --}}
        <div style="margin-top:0.6rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem;">
                <span style="font-size:0.72rem; font-weight:600; color:#64748B;">Kekuatan Sandi:</span>
                <span id="strengthText" style="font-size:0.72rem; font-weight:700; color:#94A3B8;">Belum diisi</span>
            </div>
            <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:4px; height:5px; background:#F1F5F9; border-radius:99px; overflow:hidden; padding:1px;">
                <div id="bar1" style="height:100%; border-radius:99px; background:#E2E8F0; transition:all 0.3s ease;"></div>
                <div id="bar2" style="height:100%; border-radius:99px; background:#E2E8F0; transition:all 0.3s ease;"></div>
                <div id="bar3" style="height:100%; border-radius:99px; background:#E2E8F0; transition:all 0.3s ease;"></div>
                <div id="bar4" style="height:100%; border-radius:99px; background:#E2E8F0; transition:all 0.3s ease;"></div>
            </div>
        </div>

        {{-- Active Requirements Checklist --}}
        <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:0.65rem 0.85rem; margin-top:0.65rem;">
            <div style="font-size:0.7rem; font-weight:700; color:#475569; margin-bottom:0.4rem; text-transform:uppercase; letter-spacing:0.03em;">
                Persyaratan Keamanan Sandi:
            </div>
            <div style="display:flex; flex-direction:column; gap:0.3rem;">
                <div class="pwd-req-item" id="req-len">
                    <span class="req-icon">○</span>
                    <span class="req-label">Minimal 8 karakter</span>
                </div>
                <div class="pwd-req-item" id="req-case">
                    <span class="req-icon">○</span>
                    <span class="req-label">Huruf besar (A-Z) & huruf kecil (a-z)</span>
                </div>
                <div class="pwd-req-item" id="req-num">
                    <span class="req-icon">○</span>
                    <span class="req-label">Minimal 1 angka (0-9)</span>
                </div>
                <div class="pwd-req-item" id="req-sym">
                    <span class="req-icon">○</span>
                    <span class="req-label">Minimal 1 simbol / karakter unik (@$!%*#?&)</span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group" style="margin-top:1rem;">
        <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi <span>*</span></label>
        <div class="input-wrap">
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-input" placeholder="Ulangi kata sandi di atas" autocomplete="new-password" required>
            <span class="input-icon" onclick="togglePwd('password_confirmation', this)" title="Lihat/Sembunyikan">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </span>
        </div>
        <div class="pwd-req-item" id="req-match" style="margin-top:0.4rem; padding-left:0.25rem;">
            <span class="req-icon">○</span>
            <span class="req-label">Konfirmasi kata sandi cocok</span>
        </div>
    </div>

    <button type="submit" class="btn-primary" id="submitBtn" disabled style="margin-top:1.25rem; opacity:0.55; cursor:not-allowed; filter:grayscale(30%); transition:all 0.25s ease;">
        Daftar Akun Sekarang
    </button>
</form>

<div class="divider">atau daftar instant</div>

<a href="{{ route('auth.google') }}" class="btn-google">
    <svg width="20" height="20" viewBox="0 0 48 48">
        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
        <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
    </svg>
    Daftar dengan Google
</a>

<p style="text-align:center; font-size:0.75rem; color:#94A3B8; margin-top:1.5rem; line-height:1.6;">
    Dengan mendaftar, Anda menyetujui <a href="#" style="color:#1eb349;">Syarat & Ketentuan</a><br>dan <a href="#" style="color:#1eb349;">Kebijakan Privasi</a> kami.
</p>

<style>
.pwd-req-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.73rem;
    color: #94A3B8;
    transition: all 0.2s ease;
}
.pwd-req-item .req-icon {
    font-size: 0.8rem;
    font-weight: 700;
    line-height: 1;
    width: 14px;
    display: inline-block;
    text-align: center;
}
.pwd-req-item.valid {
    color: #10B981 !important;
    font-weight: 600;
}
.pwd-req-item.valid .req-icon {
    color: #10B981 !important;
}
</style>

<script>
function togglePwd(id, el) {
    const inp = document.getElementById(id);
    if (!inp) return;
    const isPwd = inp.type === 'password';
    inp.type = isPwd ? 'text' : 'password';
    if (el) {
        el.style.color = isPwd ? '#1eb349' : '#94A3B8';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const pwdInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('registerEmailForm');

    const reqLen = document.getElementById('req-len');
    const reqCase = document.getElementById('req-case');
    const reqNum = document.getElementById('req-num');
    const reqSym = document.getElementById('req-sym');
    const reqMatch = document.getElementById('req-match');

    const bar1 = document.getElementById('bar1');
    const bar2 = document.getElementById('bar2');
    const bar3 = document.getElementById('bar3');
    const bar4 = document.getElementById('bar4');
    const strengthText = document.getElementById('strengthText');

    function checkValidity() {
        const val = pwdInput.value || '';
        const confirmVal = confirmInput.value || '';

        // 1. Min 8 Karakter
        const hasLen = val.length >= 8;
        // 2. Kombinasi Huruf Besar & Kecil
        const hasCase = /[a-z]/.test(val) && /[A-Z]/.test(val);
        // 3. Mengandung Angka
        const hasNum = /[0-9]/.test(val);
        // 4. Mengandung Simbol / Karakter Unik
        const hasSym = /[^A-Za-z0-9]/.test(val);
        // 5. Konfirmasi Cocok
        const hasMatch = val.length > 0 && confirmVal === val;

        // Update checklist UI
        updateReq(reqLen, hasLen);
        updateReq(reqCase, hasCase);
        updateReq(reqNum, hasNum);
        updateReq(reqSym, hasSym);
        updateReq(reqMatch, hasMatch);

        // Calculate score (0-4)
        let score = 0;
        if (hasLen) score++;
        if (hasCase) score++;
        if (hasNum) score++;
        if (hasSym) score++;

        // Update Strength Meter Bar
        if (val.length === 0) {
            setBars('#E2E8F0', '#E2E8F0', '#E2E8F0', '#E2E8F0');
            strengthText.innerText = 'Belum diisi';
            strengthText.style.color = '#94A3B8';
        } else if (score <= 1) {
            setBars('#EF4444', '#E2E8F0', '#E2E8F0', '#E2E8F0');
            strengthText.innerText = 'Sangat Lemah';
            strengthText.style.color = '#EF4444';
        } else if (score === 2) {
            setBars('#F97316', '#F97316', '#E2E8F0', '#E2E8F0');
            strengthText.innerText = 'Lemah';
            strengthText.style.color = '#F97316';
        } else if (score === 3) {
            setBars('#F59E0B', '#F59E0B', '#F59E0B', '#E2E8F0');
            strengthText.innerText = 'Sedang';
            strengthText.style.color = '#F59E0B';
        } else if (score === 4) {
            setBars('#10B981', '#10B981', '#10B981', '#10B981');
            strengthText.innerText = 'Kuat & Aman ✓';
            strengthText.style.color = '#10B981';
        }

        // Syarat Lolos: Semua 5 kriteria password harus hijau + name & email tidak kosong
        const isAllPwdValid = hasLen && hasCase && hasNum && hasSym && hasMatch;
        const isFormFilled = nameInput.value.trim().length > 0 && emailInput.value.trim().length > 0;

        if (isAllPwdValid && isFormFilled) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
            submitBtn.style.filter = 'none';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.55';
            submitBtn.style.cursor = 'not-allowed';
            submitBtn.style.filter = 'grayscale(30%)';
        }

        return isAllPwdValid;
    }

    function updateReq(el, isValid) {
        if (!el) return;
        const icon = el.querySelector('.req-icon');
        if (isValid) {
            el.classList.add('valid');
            if (icon) icon.innerText = '✓';
        } else {
            el.classList.remove('valid');
            if (icon) icon.innerText = '○';
        }
    }

    function setBars(c1, c2, c3, c4) {
        bar1.style.background = c1;
        bar2.style.background = c2;
        bar3.style.background = c3;
        bar4.style.background = c4;
    }

    pwdInput.addEventListener('input', checkValidity);
    confirmInput.addEventListener('input', checkValidity);
    nameInput.addEventListener('input', checkValidity);
    emailInput.addEventListener('input', checkValidity);

    form.addEventListener('submit', function (e) {
        const isValid = checkValidity();
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua kriteria keamanan kata sandi sebelum mendaftar.');
        }
    });

    // Run once on load (e.g. if old values exist)
    checkValidity();
});
</script>
@endsection
