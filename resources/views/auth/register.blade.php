@extends('auth.layout')

@section('title', 'Daftar – buyle.id')

@section('content')
<h1 class="auth-title">Buat Akun Baru</h1>
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

<form method="POST" action="{{ route('register.submit') }}" id="registerForm">
    @csrf
    
    <!-- STEP 1 -->
    <div id="step1">
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

        <button type="button" class="btn-primary" style="margin-top:0.5rem;" onclick="nextStep()">Selanjutnya</button>
    </div>

    <!-- STEP 2 -->
    <div id="step2" style="display: none;">
        <button type="button" onclick="prevStep()" style="background:none; border:none; color:#64748B; font-size:0.8rem; font-weight:600; cursor:pointer; margin-bottom:1rem; display:flex; align-items:center; gap:0.25rem;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
            Kembali
        </button>

        <div class="form-group">
            <label class="form-label" for="phone">No. WhatsApp</label>
            <input type="tel" id="phone" name="phone" class="form-input"
                   placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" autocomplete="tel">
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Kata Sandi <span>*</span></label>
            <div class="input-wrap">
                <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="Minimal 6 karakter" autocomplete="new-password">
                <span class="input-icon" onclick="togglePwd('password', this)">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi <span>*</span></label>
            <div class="input-wrap">
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-input" placeholder="Ulangi kata sandi" autocomplete="new-password">
                <span class="input-icon" onclick="togglePwd('password_confirmation', this)">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </span>
            </div>
        </div>

        <button type="submit" class="btn-primary" style="margin-top:0.5rem;" id="submitBtn">Buat Akun</button>
    </div>
</form>

<div class="divider">atau daftar dengan</div>

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
    Dengan mendaftar, Anda menyetujui <a href="#" style="color:#0EA5E9;">Syarat & Ketentuan</a><br>dan <a href="#" style="color:#0EA5E9;">Kebijakan Privasi</a> kami.
</p>

<script>
function togglePwd(id) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
}

function nextStep() {
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    
    // Basic validation before next step
    if(!name.value || !email.value) {
        alert('Mohon lengkapi Nama dan Email terlebih dahulu.');
        return;
    }
    if(!email.value.includes('@')) {
        alert('Mohon masukkan email yang valid.');
        return;
    }
    
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
    
    // Required for step 2
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
}

function prevStep() {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
    
    // Remove required to prevent HTML5 validation error when hidden
    document.getElementById('password').required = false;
    document.getElementById('password_confirmation').required = false;
}
</script>
@endsection
