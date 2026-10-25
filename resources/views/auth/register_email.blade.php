@extends('auth.layout')

@section('title', 'Daftar via Email - buyle.id')

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

<div id="otpFeedback" style="display:none; margin-bottom:0.85rem;"></div>

<form method="POST" action="{{ route('register.submit') }}" id="registerEmailForm" novalidate>
    @csrf
    <input type="hidden" name="otp_code" id="hiddenOtpCode">

    <div class="form-group">
        <label class="form-label" for="name">Nama Lengkap <span>*</span></label>
        <input type="text" id="name" name="name" class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
               placeholder="Nama lengkap Anda" value="{{ old('name') }}" autocomplete="name" required>
    </div>

    <div class="form-group">
        <label class="form-label" for="email">Email <span>*</span></label>
        <div style="display:flex; gap:0.5rem; align-items:stretch;">
            <input type="email" id="email" name="email"
                   class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   placeholder="contoh@email.com" value="{{ old('email') }}"
                   autocomplete="email" required style="flex:1;">
            <button type="button" id="sendOtpBtn"
                style="flex-shrink:0; padding:0 0.9rem; background:#1eb349; color:#fff; border:none; border-radius:10px; font-size:0.75rem; font-weight:700; font-family:'Montserrat',sans-serif; cursor:pointer; white-space:nowrap; transition:all 0.2s; box-shadow:0 4px 10px rgba(30,179,73,0.2);"
                onclick="sendOtp()">Kirim OTP</button>
        </div>
        <div id="otpStatus" style="display:none; margin-top:0.5rem; font-size:0.72rem; font-weight:600; color:#64748B;"></div>
    </div>

    <div class="form-group" id="otpInputGroup" style="display:none;">
        <label class="form-label" for="otp_input">Kode OTP <span>*</span> <span style="color:#94A3B8; font-weight:400;">6 digit dari email Anda</span></label>
        <div style="position:relative;">
            <input type="text" id="otp_input" maxlength="6" inputmode="numeric" pattern="\d{6}"
                   class="form-input" placeholder="______"
                   style="letter-spacing:0.45em; font-size:1.1rem; font-weight:700; text-align:center;">
            <span id="otpCountdown" style="position:absolute; right:0.85rem; top:50%; transform:translateY(-50%); font-size:0.72rem; font-weight:700; color:#94A3B8;"></span>
        </div>
        <div style="display:flex; justify-content:flex-end; margin-top:0.4rem;">
            <button type="button" id="resendOtpBtn" onclick="sendOtp(true)"
                style="font-size:0.72rem; font-weight:600; color:#94A3B8; background:none; border:none; cursor:not-allowed; font-family:'Montserrat',sans-serif; text-decoration:underline; padding:0; transition:color 0.2s;"
                disabled>Kirim ulang OTP</button>
        </div>
        <div id="otpVerifiedBadge" style="display:none; margin-top:0.5rem; background:#F0FDF4; border:1px solid #BBF7D0; border-radius:8px; padding:0.5rem 0.75rem; font-size:0.75rem; font-weight:600; color:#15803D; align-items:center; gap:0.4rem;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Email berhasil diverifikasi
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="phone">No. WhatsApp <span>*</span></label>
        <input type="tel" id="phone" name="phone" class="form-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
               placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" autocomplete="tel" required>
        <span style="font-size:0.7rem; color:#94A3B8; margin-top:0.3rem; display:block;">Diperlukan untuk notifikasi pesanan via WhatsApp.</span>
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
        <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:0.65rem 0.85rem; margin-top:0.65rem;">
            <div style="font-size:0.7rem; font-weight:700; color:#475569; margin-bottom:0.4rem; text-transform:uppercase; letter-spacing:0.03em;">Persyaratan Keamanan Sandi:</div>
            <div style="display:flex; flex-direction:column; gap:0.3rem;">
                <div class="pwd-req-item" id="req-len"><span class="req-icon">o</span><span class="req-label">Minimal 8 karakter</span></div>
                <div class="pwd-req-item" id="req-case"><span class="req-icon">o</span><span class="req-label">Huruf besar (A-Z) &amp; huruf kecil (a-z)</span></div>
                <div class="pwd-req-item" id="req-num"><span class="req-icon">o</span><span class="req-label">Minimal 1 angka (0-9)</span></div>
                <div class="pwd-req-item" id="req-sym"><span class="req-icon">o</span><span class="req-label">Minimal 1 simbol / karakter unik (@$!%*#?&amp;)</span></div>
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
            <span class="req-icon">o</span>
            <span class="req-label">Konfirmasi kata sandi cocok</span>
        </div>
    </div>

    <button type="submit" class="btn-primary" id="submitBtn" disabled style="margin-top:1.25rem; opacity:0.55; cursor:not-allowed; filter:grayscale(30%); transition:all 0.25s ease;">
        Daftar Akun Sekarang
    </button>
</form>

<p style="text-align:center; font-size:0.75rem; color:#94A3B8; margin-top:1.5rem; line-height:1.6;">
    Dengan mendaftar, Anda menyetujui <a href="{{ route('faqs.show', 'syarat-ketentuan') }}" target="_blank" style="color:#1eb349; text-decoration:underline;">Syarat &amp; Ketentuan</a><br>dan <a href="{{ route('faqs.show', 'kebijakan-privasi') }}" target="_blank" style="color:#1eb349; text-decoration:underline;">Kebijakan Privasi</a> kami.
</p>

<style>
.pwd-req-item { display:flex; align-items:center; gap:0.4rem; font-size:0.73rem; color:#94A3B8; transition:all 0.2s ease; }
.pwd-req-item .req-icon { font-size:0.8rem; font-weight:700; line-height:1; width:14px; display:inline-block; text-align:center; }
.pwd-req-item.valid { color:#10B981 !important; font-weight:600; }
.pwd-req-item.valid .req-icon { color:#10B981 !important; }
#sendOtpBtn:hover { background:#16a34a !important; }
#sendOtpBtn:disabled { background:#94A3B8 !important; cursor:not-allowed !important; box-shadow:none !important; }
</style>

<script>
var otpVerified  = false;
var otpSentToken = null;
var otpTimerVal  = 0;
var otpTimerID   = null;

function sendOtp(isResend) {
    var email = document.getElementById('email').value.trim();
    var name  = document.getElementById('name').value.trim();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showOtpFeedback('danger', 'Masukkan alamat email yang valid terlebih dahulu.'); return;
    }
    if (!name) { showOtpFeedback('danger', 'Masukkan nama lengkap Anda terlebih dahulu.'); return; }

    var btn = document.getElementById('sendOtpBtn');
    btn.disabled = true;
    btn.textContent = 'Mengirim...';

    fetch('{{ route("register.send_otp") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ email: email, name: name })
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
        if (data.success) {
            otpSentToken = data.token;
            otpVerified  = false;
            document.getElementById('hiddenOtpCode').value = '';
            document.getElementById('otpInputGroup').style.display = 'block';
            document.getElementById('otp_input').value = '';
            document.getElementById('otp_input').style.borderColor = '';
            document.getElementById('otpVerifiedBadge').style.display = 'none';
            showOtpFeedback('success', 'Kode OTP dikirim ke ' + email + '. Cek inbox/spam Anda.');
            showOtpStatus('Menunggu kode OTP...');
            startCountdown(60);
            btn.disabled = false;
            btn.textContent = isResend ? 'Kirim Ulang' : 'Kirim OTP';
            checkValidity();
        } else {
            btn.disabled = false;
            btn.textContent = isResend ? 'Kirim Ulang' : 'Kirim OTP';
            showOtpFeedback('danger', data.message || 'Gagal mengirim OTP. Coba lagi.');
        }
    })
    .catch(function(){
        btn.disabled = false;
        btn.textContent = isResend ? 'Kirim Ulang' : 'Kirim OTP';
        showOtpFeedback('danger', 'Terjadi kesalahan jaringan. Coba lagi.');
    });
}

function startCountdown(seconds) {
    clearInterval(otpTimerID);
    otpTimerVal = seconds;
    var el = document.getElementById('otpCountdown');
    var resend = document.getElementById('resendOtpBtn');
    resend.disabled = true; resend.style.cursor = 'not-allowed'; resend.style.color = '#CBD5E1';
    el.style.color = '#94A3B8';
    function tick() {
        el.textContent = otpTimerVal + 's';
        if (otpTimerVal <= 0) {
            clearInterval(otpTimerID); el.textContent = 'Kadaluarsa'; el.style.color = '#EF4444';
            resend.disabled = false; resend.style.cursor = 'pointer'; resend.style.color = '#1eb349';
            showOtpStatus('Kode OTP kadaluarsa. Klik "Kirim ulang OTP".');
            otpVerified = false; checkValidity();
        }
        otpTimerVal--;
    }
    tick();
    otpTimerID = setInterval(tick, 1000);
}

document.addEventListener('DOMContentLoaded', function () {
    var otpInput = document.getElementById('otp_input');
    if (otpInput) {
        otpInput.addEventListener('input', function () {
            var val = this.value.replace(/\D/g, '').slice(0, 6);
            this.value = val;
            document.getElementById('hiddenOtpCode').value = val;
            if (val.length === 6 && otpSentToken) {
                fetch('{{ route("register.verify_otp_ajax") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ otp: val, token: otpSentToken })
                })
                .then(function(r){ return r.json(); })
                .then(function(data){
                    if (data.success) {
                        otpVerified = true;
                        clearInterval(otpTimerID);
                        var cd = document.getElementById('otpCountdown');
                        cd.textContent = 'v'; cd.style.color = '#10B981';
                        document.getElementById('otpVerifiedBadge').style.display = 'flex';
                        showOtpStatus('');
                        otpInput.style.borderColor = '#10B981';
                        otpInput.style.boxShadow = '0 0 0 3px rgba(16,185,129,0.12)';
                    } else {
                        otpVerified = false;
                        otpInput.style.borderColor = '#EF4444';
                        otpInput.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.1)';
                        document.getElementById('otpVerifiedBadge').style.display = 'none';
                        showOtpStatus('Kode OTP salah, coba lagi.');
                    }
                    checkValidity();
                });
            } else {
                otpVerified = false;
                otpInput.style.borderColor = ''; otpInput.style.boxShadow = '';
                document.getElementById('otpVerifiedBadge').style.display = 'none';
                checkValidity();
            }
        });
    }
});

function showOtpFeedback(type, msg) {
    var el = document.getElementById('otpFeedback');
    if (!msg) { el.style.display = 'none'; return; }
    var bg = type === 'success' ? '#F0FDF4' : '#FEF2F2';
    var bc = type === 'success' ? '#BBF7D0' : '#FCA5A5';
    var fc = type === 'success' ? '#15803D' : '#EF4444';
    el.innerHTML = '<div style="background:' + bg + '; border:1px solid ' + bc + '; border-radius:8px; padding:0.6rem 0.85rem; font-size:0.78rem; font-weight:500; color:' + fc + ';">' + msg + '</div>';
    el.style.display = 'block';
}
function showOtpStatus(msg) {
    var el = document.getElementById('otpStatus');
    if (!msg) { el.style.display = 'none'; return; }
    el.textContent = msg; el.style.display = 'block';
}

function togglePwd(id, el) {
    var inp = document.getElementById(id);
    if (!inp) return;
    var isPwd = inp.type === 'password';
    inp.type = isPwd ? 'text' : 'password';
    if (el) el.style.color = isPwd ? '#1eb349' : '#94A3B8';
}

document.addEventListener('DOMContentLoaded', function () {
    var pwdInput     = document.getElementById('password');
    var confirmInput = document.getElementById('password_confirmation');
    var nameInput    = document.getElementById('name');
    var emailInput   = document.getElementById('email');
    var phoneInput   = document.getElementById('phone');
    var submitBtn    = document.getElementById('submitBtn');
    var form         = document.getElementById('registerEmailForm');

    var reqLen   = document.getElementById('req-len');
    var reqCase  = document.getElementById('req-case');
    var reqNum   = document.getElementById('req-num');
    var reqSym   = document.getElementById('req-sym');
    var reqMatch = document.getElementById('req-match');
    var bar1 = document.getElementById('bar1');
    var bar2 = document.getElementById('bar2');
    var bar3 = document.getElementById('bar3');
    var bar4 = document.getElementById('bar4');
    var strengthText = document.getElementById('strengthText');

    window.checkValidity = function () {
        var val        = pwdInput.value || '';
        var confirmVal = confirmInput.value || '';
        var hasLen   = val.length >= 8;
        var hasCase  = /[a-z]/.test(val) && /[A-Z]/.test(val);
        var hasNum   = /[0-9]/.test(val);
        var hasSym   = /[^A-Za-z0-9]/.test(val);
        var hasMatch = val.length > 0 && confirmVal === val;
        updateReq(reqLen, hasLen); updateReq(reqCase, hasCase);
        updateReq(reqNum, hasNum); updateReq(reqSym,  hasSym);
        updateReq(reqMatch, hasMatch);
        var score = (hasLen?1:0)+(hasCase?1:0)+(hasNum?1:0)+(hasSym?1:0);
        if (val.length === 0) {
            setBars('#E2E8F0','#E2E8F0','#E2E8F0','#E2E8F0');
            strengthText.innerText = 'Belum diisi'; strengthText.style.color = '#94A3B8';
        } else if (score <= 1) {
            setBars('#EF4444','#E2E8F0','#E2E8F0','#E2E8F0');
            strengthText.innerText = 'Sangat Lemah'; strengthText.style.color = '#EF4444';
        } else if (score === 2) {
            setBars('#F97316','#F97316','#E2E8F0','#E2E8F0');
            strengthText.innerText = 'Lemah'; strengthText.style.color = '#F97316';
        } else if (score === 3) {
            setBars('#F59E0B','#F59E0B','#F59E0B','#E2E8F0');
            strengthText.innerText = 'Sedang'; strengthText.style.color = '#F59E0B';
        } else {
            setBars('#10B981','#10B981','#10B981','#10B981');
            strengthText.innerText = 'Kuat & Aman'; strengthText.style.color = '#10B981';
        }
        var isAllPwdValid = hasLen && hasCase && hasNum && hasSym && hasMatch;
        var isFormFilled  = nameInput.value.trim().length > 0
                          && emailInput.value.trim().length > 0
                          && phoneInput.value.trim().length > 0;
        var canSubmit = isAllPwdValid && isFormFilled && otpVerified;
        submitBtn.disabled      = !canSubmit;
        submitBtn.style.opacity = canSubmit ? '1' : '0.55';
        submitBtn.style.cursor  = canSubmit ? 'pointer' : 'not-allowed';
        submitBtn.style.filter  = canSubmit ? 'none' : 'grayscale(30%)';
        return isAllPwdValid;
    };

    function updateReq(el, isValid) {
        if (!el) return;
        var icon = el.querySelector('.req-icon');
        if (isValid) { el.classList.add('valid'); if (icon) icon.innerText = 'v'; }
        else { el.classList.remove('valid'); if (icon) icon.innerText = 'o'; }
    }
    function setBars(c1, c2, c3, c4) {
        bar1.style.background = c1; bar2.style.background = c2;
        bar3.style.background = c3; bar4.style.background = c4;
    }
    pwdInput.addEventListener('input', checkValidity);
    confirmInput.addEventListener('input', checkValidity);
    nameInput.addEventListener('input', checkValidity);
    emailInput.addEventListener('input', checkValidity);
    phoneInput.addEventListener('input', checkValidity);
    form.addEventListener('submit', function (e) {
        if (!otpVerified) {
            e.preventDefault();
            showOtpFeedback('danger', 'Anda harus memverifikasi OTP email sebelum mendaftar.');
            return;
        }
        if (!checkValidity()) {
            e.preventDefault();
            showOtpFeedback('danger', 'Mohon lengkapi semua kriteria keamanan kata sandi.');
        }
    });
    checkValidity();
});
</script>
@endsection