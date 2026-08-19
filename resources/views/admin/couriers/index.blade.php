@extends('layouts.admin')
@section('title','Manajemen Kurir')
@section('page-title','Pengiriman / Kurir')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#1E293B;margin:0 0 .25rem;letter-spacing:-.02em;">Metode Pengiriman</h1>
    <p style="font-size:.875rem;color:#94A3B8;margin:0;">Kelola kurir/ekspedisi yang aktif di halaman checkout.</p>
  </div>
  <button onclick="document.getElementById('add-modal').style.display='flex'" style="display:inline-flex;align-items:center;gap:.5rem;background:#3B82F6;color:#fff;font-size:.875rem;font-weight:700;padding:.625rem 1.25rem;border-radius:12px;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(59,130,246,0.35);">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Kurir
  </button>
</div>

@if(session('success'))
  <div style="background:#F0FDF4;color:#166534;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;border:1px solid #BBF7D0;font-size:.875rem;">{{ session('success') }}</div>
@endif
@if($errors->any())
  <div style="background:#FEF2F2;color:#991B1B;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;border:1px solid #FCA5A5;font-size:.875rem;">{{ $errors->first() }}</div>
@endif

<div style="background:#fff;border-radius:20px;box-shadow:0 2px 20px rgba(0,0,0,0.04);overflow:hidden;">
  <table style="width:100%;border-collapse:collapse;">
    <thead>
      <tr style="background:#F8FAFC;">
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Nama Kurir</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Kode</th>
        <th style="padding:1rem 1.5rem;text-align:left;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Tipe</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Status</th>
        <th style="padding:1rem 1.5rem;text-align:center;font-size:.75rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid #F1F5F9;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($couriers as $c)
      <tr style="border-bottom:1px solid #F8FAFC;transition:background .15s;" onmouseover="this.style.background='#FAFBFF'" onmouseout="this.style.background='transparent'">
        <td style="padding:1.25rem 1.5rem;font-weight:700;color:#1E293B;">{{ $c->name }}</td>
        <td style="padding:1.25rem 1.5rem;">
          <code style="background:#F1F5F9;padding:.2rem .6rem;border-radius:6px;font-size:.8rem;color:#475569;">{{ $c->code }}</code>
        </td>
        <td style="padding:1.25rem 1.5rem;">
          <span style="font-size:.8rem;padding:.25rem .75rem;border-radius:50px;background:{{ $c->type==='expedition' ? '#EFF6FF' : '#F0FDF4' }};color:{{ $c->type==='expedition' ? '#1D4ED8' : '#15803D' }};font-weight:600;">
            {{ $c->type==='expedition' ? 'Ekspedisi' : 'Kurir Toko' }}
          </span>
        </td>
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          <form action="{{ route('admin.couriers.toggle', $c) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;" title="{{ $c->is_active ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan' }}">
              <span style="display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .9rem;border-radius:50px;font-size:.75rem;font-weight:700;background:{{ $c->is_active ? '#DCFCE7' : '#FEE2E2' }};color:{{ $c->is_active ? '#16A34A' : '#DC2626' }};">
                <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                {{ $c->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </button>
          </form>
        </td>
        <td style="padding:1.25rem 1.5rem;text-align:center;">
          <div style="display:flex;align-items:center;justify-content:center;gap:.5rem;">
            <button onclick="openEdit({{ $c->id }}, '{{ addslashes($c->name) }}', {{ $c->is_active ? 'true' : 'false' }})" style="background:#EFF6FF;color:#1D4ED8;border:none;border-radius:8px;padding:.4rem .8rem;font-size:.8rem;font-weight:600;cursor:pointer;">Edit</button>
            <form action="{{ route('admin.couriers.destroy', $c) }}" method="POST" onsubmit="return confirm('Hapus kurir ini?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:#FEE2E2;color:#DC2626;border:none;border-radius:8px;padding:.4rem .8rem;font-size:.8rem;font-weight:600;cursor:pointer;">Hapus</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" style="padding:3rem;text-align:center;color:#94A3B8;font-size:.9rem;">Belum ada kurir. Tambahkan kurir pertama Anda.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ADD MODAL --}}
<div id="add-modal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
  <div style="background:#fff;border-radius:20px;padding:2rem;width:100%;max-width:440px;box-shadow:0 24px 64px rgba(0,0,0,0.2);margin:1rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <h3 style="font-size:1.1rem;font-weight:800;color:#1E293B;margin:0;">Tambah Kurir Baru</h3>
      <button onclick="document.getElementById('add-modal').style.display='none'" style="background:#F1F5F9;border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem;color:#64748B;">✕</button>
    </div>
    <form action="{{ route('admin.couriers.store') }}" method="POST">
      @csrf
      <div style="margin-bottom:1rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#475569;margin-bottom:.4rem;">Nama Kurir</label>
        <input type="text" name="name" required placeholder="cth: JNE Reguler" style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:inherit;">
      </div>
      <div style="margin-bottom:1rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#475569;margin-bottom:.4rem;">Kode Unik <span style="font-weight:400;color:#94A3B8;">(huruf kecil, tanpa spasi)</span></label>
        <input type="text" name="code" required placeholder="cth: jne, jnt, sicepat" style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:inherit;">
      </div>
      <div style="margin-bottom:1.5rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#475569;margin-bottom:.4rem;">Tipe</label>
        <select name="type" style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;background:#fff;font-family:inherit;">
          <option value="expedition">Ekspedisi (JNE, J&T, SiCepat dll)</option>
          <option value="custom">Kurir Toko / Antar Sendiri</option>
        </select>
      </div>
      <div style="display:flex;gap:.75rem;">
        <button type="button" onclick="document.getElementById('add-modal').style.display='none'" style="flex:1;padding:.75rem;background:#F1F5F9;color:#475569;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Batal</button>
        <button type="submit" style="flex:2;padding:.75rem;background:#3B82F6;color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- EDIT MODAL --}}
<div id="edit-modal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
  <div style="background:#fff;border-radius:20px;padding:2rem;width:100%;max-width:440px;box-shadow:0 24px 64px rgba(0,0,0,0.2);margin:1rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
      <h3 style="font-size:1.1rem;font-weight:800;color:#1E293B;margin:0;">Edit Kurir</h3>
      <button onclick="document.getElementById('edit-modal').style.display='none'" style="background:#F1F5F9;border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem;color:#64748B;">✕</button>
    </div>
    <form id="edit-form" action="" method="POST">
      @csrf @method('PUT')
      <div style="margin-bottom:1rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;color:#475569;margin-bottom:.4rem;">Nama Kurir</label>
        <input type="text" name="name" id="edit-name" required style="width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;box-sizing:border-box;font-family:inherit;">
      </div>
      <div style="margin-bottom:1.5rem;">
        <label style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;font-weight:600;color:#475569;cursor:pointer;">
          <input type="checkbox" name="is_active" value="1" id="edit-active" style="width:16px;height:16px;accent-color:#3B82F6;">
          Aktif (tampil di halaman checkout)
        </label>
      </div>
      <div style="display:flex;gap:.75rem;">
        <button type="button" onclick="document.getElementById('edit-modal').style.display='none'" style="flex:1;padding:.75rem;background:#F1F5F9;color:#475569;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Batal</button>
        <button type="submit" style="flex:2;padding:.75rem;background:#3B82F6;color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-family:inherit;">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEdit(id, name, isActive) {
  document.getElementById('edit-form').action = '/admin/couriers/' + id;
  document.getElementById('edit-name').value = name;
  document.getElementById('edit-active').checked = isActive;
  document.getElementById('edit-modal').style.display = 'flex';
}
document.getElementById('add-modal').addEventListener('click', function(e){ if(e.target===this) this.style.display='none'; });
document.getElementById('edit-modal').addEventListener('click', function(e){ if(e.target===this) this.style.display='none'; });
</script>
@endsection