@extends('auth.layout')

@section('title', 'Verifikasi Kode OTP – buyle.id')

@section('content')
<h1 class="auth-title">Verifikasi Kode OTP</h1>
<p class="auth-subtitle" style="margin-bottom: 1.25rem;">
    Kode verifikasi 6 digit telah dikirim ke email:<br>
    <strong style="color: #0F172A;">{{ session('register_data.email') ?? session('otp_email') ?? 'email Anda' }}</strong>
</p>

@if(session('success'))
    <div class="alert-error" style="background:#F0FDF4; border-color:#BBF7D0; color:#15803D;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert-error" style="background:#FEF2F2; border-color:#FCA5A5; color:#DC2626;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
    </div>
@endif

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

<form method="POST" action="{{ route('otp.verify.submit') }}" id="otpForm">
    @csrf

    <div class="form-group" style="text-align: center;">
        <label class="form-label" style="margin-bottom: 0.75rem; text-align: center;">Masukkan 6-Digit Kode OTP <span>*</span></label>
        
        <input type="text" id="otp" name="otp" class="form-input" 
               placeholder="123456" maxlength="6" pattern="[0-9]*" inputmode="numeric" autocomplete="one-time-code" required autofocus
               style="text-align: center; font-size: 1.75rem; font-weight: 700; letter-spacing: 0.5rem; padding: 0.75rem; font-family: monospace; border-radius: 12px;">
    </div>

    <button type="submit" class="btn-primary" style="margin-top: 1rem;">Verifikasi & Buat Akun</button>
</form>

<div style="margin-top: 1.5rem; text-align: center; font-size: 0.8125rem; color: #64748B;">
    <div id="timerContainer" style="margin-bottom: 0.75rem; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 6px;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span>Kode berlaku selama: <span id="countdown" style="color: #DC2626; font-weight: 700; font-family: monospace; font-size: 0.95rem;">01:00</span></span>
    </div>

    <form method="POST" action="{{ route('otp.resend') }}" id="resendForm" style="display: inline-block;">
        @csrf
        <button type="submit" id="resendBtn" disabled 
                style="background: none; border: none; color: #94A3B8; font-size: 0.8125rem; font-weight: 600; cursor: not-allowed; text-decoration: underline; padding: 4px 8px;">
            Kirim Ulang Kode OTP
        </button>
    </form>
</div>

@php
    $expiresAtTimestamp = session('otp_expires_at', time() + 60);
    $remainingSeconds = max(0, $expiresAtTimestamp - time());
@endphp

<script>
document.addEventListener('DOMContentLoaded', () => {
    let remainingSeconds = {{ $remainingSeconds }};
    const countdownEl = document.getElementById('countdown');
    const resendBtn = document.getElementById('resendBtn');
    const timerContainer = document.getElementById('timerContainer');

    function updateTimer() {
        if (remainingSeconds <= 0) {
            countdownEl.innerText = "00:00 (Kadaluarsa)";
            timerContainer.style.color = "#DC2626";
            resendBtn.disabled = false;
            resendBtn.style.color = "#1eb349";
            resendBtn.style.cursor = "pointer";
            return;
        }

        const mins = Math.floor(remainingSeconds / 60);
        const secs = remainingSeconds % 60;
        countdownEl.innerText = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

        remainingSeconds--;
        setTimeout(updateTimer, 1000);
    }

    updateTimer();
});
</script>
@endsection
