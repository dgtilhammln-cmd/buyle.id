@extends('auth.layout')

@section('title', 'Buat Kata Sandi Baru – buyle.id')

@section('content')
<h1 class="auth-title">Reset Kata Sandi</h1>
<p class="auth-subtitle">Masukkan kata sandi baru Anda di bawah ini.</p>

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

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
        <label class="form-label" for="email">Alamat Email <span>*</span></label>
        <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
               placeholder="contoh@email.com" value="{{ old('email', $email) }}" required readonly style="background:#f8fafc; cursor:not-allowed;">
    </div>

    <div class="form-group">
        <label class="form-label" for="password">Kata Sandi Baru <span>*</span></label>
        <div class="input-wrap">
            <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Minimal 6 karakter" required autofocus>
            <span class="input-icon" onclick="togglePwd('password', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </span>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi Baru <span>*</span></label>
        <div class="input-wrap">
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                   placeholder="Ketik ulang kata sandi baru" required>
            <span class="input-icon" onclick="togglePwd('password_confirmation', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </span>
        </div>
    </div>

    <button type="submit" class="btn-primary" style="margin-top:0.5rem;">Perbarui Kata Sandi</button>
</form>

<p style="text-align:center; margin-top:1.5rem; font-size:0.88rem; color:#64748b;">
    Kembali ke <a href="{{ route('login') }}" style="color:#1eb349; font-weight:700; text-decoration:none;">Halaman Masuk</a>
</p>
@endsection
