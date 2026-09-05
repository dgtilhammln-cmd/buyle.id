@extends('auth.layout')

@section('title', 'Lupa Kata Sandi – buyle.id')

@section('content')
<h1 class="auth-title">Lupa Kata Sandi?</h1>
<p class="auth-subtitle">Masukkan alamat email Anda, kami akan mengirimkan tautan untuk mereset kata sandi Anda.</p>

@if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:10px; font-size:0.88rem; margin-bottom:1.25rem; line-height:1.5;">
        {{ session('success') }}
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

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="form-group">
        <label class="form-label" for="email">Email Terdaftar <span>*</span></label>
        <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
               placeholder="contoh@email.com" value="{{ old('email') }}" required autofocus>
    </div>

    <button type="submit" class="btn-primary" style="margin-top:0.5rem;">Kirim Tautan Reset Password</button>
</form>

<p style="text-align:center; margin-top:1.5rem; font-size:0.88rem; color:#64748b;">
    Kembali ke <a href="{{ route('login') }}" style="color:#1eb349; font-weight:700; text-decoration:none;">Halaman Masuk</a>
</p>
@endsection
