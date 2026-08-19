@extends('layouts.admin')
@section('title', 'Manajemen Lisensi')
@section('page-title', 'Manajemen Lisensi')
@section('content')

<style>
.lic-card { background:#fff; border-radius:20px; padding:2rem; box-shadow:0 2px 20px rgba(0,0,0,0.05); }
.lic-badge-active { display:inline-flex;align-items:center;gap:.4rem;background:#ECFDF5;border:1.5px solid #6EE7B7;color:#065F46;font-size:.8rem;font-weight:800;padding:.4rem 1rem;border-radius:100px; }
.lic-badge-suspended { display:inline-flex;align-items:center;gap:.4rem;background:#FEF2F2;border:1.5px solid #FCA5A5;color:#991B1B;font-size:.8rem;font-weight:800;padding:.4rem 1rem;border-radius:100px; }
.lic-btn-activate { width:100%;padding:1rem;background:#3B82F6;color:#fff;border:none;border-radius:12px;font-size:1rem;font-weight:800;cursor:pointer;transition:all .2s;letter-spacing:.01em; }
.lic-btn-activate:hover { background:#2563EB;transform:translateY(-1px);box-shadow:0 6px 20px rgba(59,130,246,0.35); }
.lic-btn-suspend { width:100%;padding:1rem;background:#EF4444;color:#fff;border:none;border-radius:12px;font-size:1rem;font-weight:800;cursor:pointer;transition:all .2s;letter-spacing:.01em; }
.lic-btn-suspend:hover { background:#DC2626;transform:translateY(-1px);box-shadow:0 6px 20px rgba(239,68,68,0.35); }
.lic-input { width:100%;padding:.75rem 1rem;border:1.5px solid #E4E7F0;border-radius:10px;font-size:.95rem;font-family:inherit;outline:none;transition:border-color .2s;background:#F8FAFC;box-sizing:border-box; }
.lic-input:focus { border-color:#3B82F6;background:#fff; }
</style>

<div style="max-width:640px;margin:0 auto;">

  {{-- Page Header --}}
  <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
    <div style="width:44px;height:44px;background:linear-gradient(135deg,#3B82F6,#6366F1);border-radius:12px;display:flex;align-items:center;justify-content:center;">
      <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
    </div>
    <div>
      <h1 style="font-size:1.375rem;font-weight:800;color:#1E293B;margin:0 0 .1rem;letter-spacing:-.02em;">Manajemen Lisensi</h1>
      <p style="font-size:.8rem;color:#94A3B8;margin:0;">Kelola status aktif & periode layanan website klien</p>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#ECFDF5;border:1.5px solid #6EE7B7;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#065F46;font-size:.875rem;font-weight:600;display:flex;align-items:center;gap:.75rem;">
      <svg width="18" height="18" fill="none" stroke="#059669" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      {{ session('success') }}
    </div>
  @endif

  {{-- Current Status Card --}}
  <div class="lic-card" style="margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
      <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0;">Status Saat Ini</h3>
      @if($status === 'active')
        <span class="lic-badge-active">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
          AKTIF
        </span>
      @else
        <span class="lic-badge-suspended">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          DIBEKUKAN
        </span>
      @endif
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
      <div style="background:#F8FAFC;border-radius:12px;padding:1rem;">
        <div style="font-size:.7rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.25rem;">Nama Website</div>
        <div style="font-size:.9rem;font-weight:700;color:#1E293B;">{{ $clientName ?: 'Tidak disetel' }}</div>
      </div>
      <div style="background:#F8FAFC;border-radius:12px;padding:1rem;">
        <div style="font-size:.7rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.25rem;">Aktif Sampai</div>
        <div style="font-size:.9rem;font-weight:700;color:{{ $status === 'active' ? '#059669' : '#EF4444' }};">
          @if($expiry)
            {{ \Carbon\Carbon::parse($expiry)->locale('id')->isoFormat('D MMMM YYYY') }}
          @else
            Belum Disetel
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Update Form --}}
  <div class="lic-card">
    <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;margin:0 0 1.25rem;">Perbarui Lisensi</h3>

    <form method="POST" action="{{ route('admin.license.update') }}" id="license-form">
      @csrf

      {{-- Status Toggle --}}
      <div style="margin-bottom:1.25rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.625rem;">Status Website</label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
          <label style="cursor:pointer;" id="lbl-active">
            <input type="radio" name="status" value="active" {{ $status === 'active' ? 'checked' : '' }} style="display:none;" onchange="updateStatusUI()">
            <div id="card-active" style="padding:1rem;border:2px solid {{ $status === 'active' ? '#3B82F6' : '#E4E7F0' }};border-radius:12px;text-align:center;transition:all .2s;background:{{ $status === 'active' ? '#EFF6FF' : '#fff' }};">
              <svg width="24" height="24" fill="none" stroke="{{ $status === 'active' ? '#3B82F6' : '#94A3B8' }}" stroke-width="2" viewBox="0 0 24 24" style="margin-bottom:.5rem;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              <div style="font-size:.8rem;font-weight:800;color:{{ $status === 'active' ? '#3B82F6' : '#64748B' }};">Aktifkan</div>
              <div style="font-size:.7rem;color:#94A3B8;margin-top:.2rem;">Website berjalan normal</div>
            </div>
          </label>
          <label style="cursor:pointer;" id="lbl-suspended">
            <input type="radio" name="status" value="suspended" {{ $status === 'suspended' ? 'checked' : '' }} style="display:none;" onchange="updateStatusUI()">
            <div id="card-suspended" style="padding:1rem;border:2px solid {{ $status === 'suspended' ? '#EF4444' : '#E4E7F0' }};border-radius:12px;text-align:center;transition:all .2s;background:{{ $status === 'suspended' ? '#FEF2F2' : '#fff' }};">
              <svg width="24" height="24" fill="none" stroke="{{ $status === 'suspended' ? '#EF4444' : '#94A3B8' }}" stroke-width="2" viewBox="0 0 24 24" style="margin-bottom:.5rem;"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
              <div style="font-size:.8rem;font-weight:800;color:{{ $status === 'suspended' ? '#EF4444' : '#64748B' }};">Bekukan</div>
              <div style="font-size:.7rem;color:#94A3B8;margin-top:.2rem;">Perlu perpanjangan</div>
            </div>
          </label>
        </div>
      </div>

      {{-- Expiry Date --}}
      <div style="margin-bottom:1.5rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">
          Tanggal Akhir Lisensi
          <span style="font-weight:500;color:#94A3B8;font-size:.75rem;">— akan tampil di dashboard klien</span>
        </label>
        <input type="date" name="expiry" class="lic-input"
          value="{{ $expiry ? \Carbon\Carbon::parse($expiry)->format('Y-m-d') : '' }}"
          min="{{ date('Y-m-d') }}">
        <p style="font-size:.75rem;color:#94A3B8;margin:.375rem 0 0;">Setelah tanggal ini, sistem <strong>tidak otomatis membekukan</strong> — Anda tetap perlu klik Bekukan secara manual.</p>
      </div>

      {{-- Submit --}}
      <button type="submit" id="submit-btn" class="{{ $status === 'active' ? 'lic-btn-suspend' : 'lic-btn-activate' }}">
        {{ $status === 'active' ? '🔒 Bekukan Website Sekarang' : '✅ Aktifkan Website & Perbarui Periode' }}
      </button>
    </form>
  </div>

  {{-- Warning Box --}}
  <div style="background:#FFFBEB;border:1.5px solid #FDE68A;border-radius:12px;padding:1rem 1.25rem;margin-top:1.5rem;display:flex;gap:.75rem;align-items:flex-start;">
    <svg width="18" height="18" fill="none" stroke="#D97706" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:.1rem;"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <div>
      <div style="font-size:.8rem;font-weight:800;color:#92400E;margin-bottom:.25rem;">Penting untuk diketahui</div>
      <div style="font-size:.78rem;color:#B45309;line-height:1.5;">
        Halaman <code style="background:#FEF3C7;padding:.1rem .3rem;border-radius:4px;">/admin/lisensi</code> tidak terdaftar di menu sidebar — hanya bisa diakses dengan URL langsung. Simpan URL ini baik-baik.
      </div>
    </div>
  </div>

</div>

<script>
function updateStatusUI() {
  const isActive = document.querySelector('input[name="status"][value="active"]').checked;
  const cardActive = document.getElementById('card-active');
  const cardSuspended = document.getElementById('card-suspended');
  const btn = document.getElementById('submit-btn');

  if (isActive) {
    cardActive.style.borderColor = '#3B82F6';
    cardActive.style.background = '#EFF6FF';
    cardSuspended.style.borderColor = '#E4E7F0';
    cardSuspended.style.background = '#fff';
    btn.className = 'lic-btn-suspend';
    btn.textContent = '🔒 Bekukan Website Sekarang';
  } else {
    cardSuspended.style.borderColor = '#EF4444';
    cardSuspended.style.background = '#FEF2F2';
    cardActive.style.borderColor = '#E4E7F0';
    cardActive.style.background = '#fff';
    btn.className = 'lic-btn-activate';
    btn.textContent = '✅ Aktifkan Website & Perbarui Periode';
  }
}
</script>
@endsection
