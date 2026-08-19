@extends('account.layout')
@section('title', 'Profil – Akun')

@section('acc_page')
<div class="acc-card">
    <div class="acc-card-header">
        <h2>Informasi Profile</h2>
        <p>Kelola Informasi pribadi anda</p>
    </div>
    <div class="acc-card-body">
        <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data">
            @csrf

            {{-- AVATAR ROW --}}
            <div class="avatar-row">
                @if($user->avatar && str_starts_with($user->avatar, 'http'))
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="avatar-img">
                @elseif($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="avatar-img">
                @else
                    <div class="avatar-img">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif
                <div>
                    <label for="avatar" class="btn-upload" style="margin-bottom:0.5rem;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Ganti Foto
                    </label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
                    <p style="font-size:0.75rem; color:#94A3B8; margin-top:0.35rem;">JPG, PNG atau WEBP. Maks 2MB.</p>
                </div>
            </div>

            @if($errors->any())
                <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; padding:0.875rem 1rem; margin-bottom:1.25rem; font-size:0.8rem; color:#B91C1C;">
                    @foreach($errors->all() as $err) <div>• {{ $err }}</div> @endforeach
                </div>
            @endif

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="name">Nama <span>*</span></label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="username">Username <span>*</span></label>
                    <input type="text" id="username" name="username" class="form-input" value="{{ old('username', $user->username) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email <span>*</span></label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ $user->email }}" disabled
                           style="background:#F1F5F9; color:#94A3B8; cursor:not-allowed;">
                    <p style="font-size:0.72rem; color:#94A3B8; margin-top:0.25rem;">Email tidak bisa diubah.</p>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">No. Handphone (WhatsApp) <span>*</span></label>
                    <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            {{-- CHANGE PASSWORD TOGGLE --}}
            <div class="toggle-wrap">
                <label class="toggle-switch">
                    <input type="checkbox" id="changePasswordToggle" name="change_password" value="1"
                           {{ old('change_password') ? 'checked' : '' }}
                           onchange="document.getElementById('passwordFields').style.display = this.checked ? 'block' : 'none'">
                    <span class="toggle-slider"></span>
                </label>
                <span class="toggle-label">Ubah Sandi ?</span>
            </div>

            <div id="passwordFields" style="display: {{ old('change_password') ? 'block' : 'none' }};">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="password">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Kata sandi baru">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="konfirmasi kata sandi baru">
                    </div>
                </div>
            </div>

            <div class="btn-save-row">
                <button type="submit" class="btn-save">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.querySelector('.avatar-img');
            if (img.tagName === 'IMG') {
                img.src = e.target.result;
            } else {
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
                newImg.className = 'avatar-img';
                newImg.alt = 'Preview';
                img.parentNode.replaceChild(newImg, img);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
