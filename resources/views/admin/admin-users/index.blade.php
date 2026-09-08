@extends('layouts.admin')
@section('title', 'Manajemen Akun Admin')
@section('page-title', 'Manajemen Akun Admin & Hak Akses Menu')
@section('content')

<style>
    /* ── Admin Management System (Montserrat Theme) ── */
    .au-page {
        font-family: 'Montserrat', sans-serif;
        color: #1E293B;
    }

    .au-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .au-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0F172A;
        margin: 0 0 0.2rem;
        letter-spacing: -0.02em;
    }

    .au-sub {
        font-size: 0.8125rem;
        color: #64748B;
        margin: 0;
        font-weight: 500;
    }

    .au-btn-primary {
        background: #0F172A;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.55rem 1rem;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
    }
    .au-btn-primary:hover {
        background: #1E293B;
    }

    /* Stats Grid */
    .au-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.85rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 992px) {
        .au-stat-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .au-stat-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .au-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Table Container */
    .au-table-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        overflow-x: auto;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .au-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8125rem;
    }

    .au-table th {
        background: #FAFBFA;
        padding: 0.65rem 0.85rem;
        border-bottom: 1px solid #E2E8F0;
        color: #64748B;
        font-weight: 600;
        font-size: 0.725rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        white-space: nowrap;
    }

    .au-table td {
        padding: 0.75rem 0.85rem;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
    }

    .au-table tr:hover td {
        background: #F8FAFC;
    }

    /* Badges */
    .au-badge {
        display: inline-block;
        padding: 0.2rem 0.55rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .au-badge-super { background: #F3E8FF; color: #7E22CE; }
    .au-badge-admin { background: #E0F2FE; color: #0369A1; }
    .au-badge-active { background: #DCFCE7; color: #15803D; }
    .au-badge-inactive { background: #FEF2F2; color: #DC2626; }
    .au-badge-menu { background: #F1F5F9; color: #475569; margin: 2px; }

    /* Action Buttons */
    .au-btn-act {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        padding: 0.35rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .au-btn-act:hover {
        background: #0F172A;
        color: #fff;
        border-color: #0F172A;
    }

    /* Modal Overlay & Card */
    .au-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
    }

    .au-modal-card {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .au-modal-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #F1F5F9;
    }

    .au-form-group {
        margin-bottom: 1rem;
    }

    .au-label {
        display: block;
        font-size: 0.775rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.35rem;
    }

    .au-input, .au-select {
        width: 100%;
        padding: 0.55rem 0.75rem;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.8125rem;
        outline: none;
        background: #fff;
        transition: all 0.15s ease;
        color: #0F172A;
    }

    .au-input:focus, .au-select:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
    }

    /* Checkbox Grid for Permissions */
    .perm-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        background: #F8FAFC;
        padding: 0.85rem;
        border-radius: 10px;
        border: 1px solid #E2E8F0;
    }
    @media (max-width: 480px) {
        .perm-grid { grid-template-columns: 1fr; }
    }

    .perm-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.775rem;
        font-weight: 500;
        color: #334155;
        cursor: pointer;
    }
    .perm-item input[type="checkbox"] {
        accent-color: #10B981;
        width: 15px;
        height: 15px;
        cursor: pointer;
    }
</style>

<div class="au-page">

    {{-- Page Header --}}
    <div class="au-head">
        <div>
            <h1 class="au-title">Manajemen Akun Admin</h1>
            <p class="au-sub">Kelola pengguna admin, hak akses menu, dan atur kata sandi secara independen</p>
        </div>

        <button type="button" onclick="openCreateModal()" class="au-btn-primary">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Admin Baru
        </button>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div style="background:#F0FDF4;color:#15803D;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;border:1px solid #BBF7D0;font-size:0.8125rem;font-weight:600;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#FEF2F2;color:#DC2626;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;border:1px solid #FECACA;font-size:0.8125rem;font-weight:600;">
            ! {{ session('error') }}
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="au-stat-grid">
        <div class="au-stat-card">
            <div class="au-stat-icon" style="background:#F0FDF4;color:#16A34A;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div style="font-size:0.725rem;color:#64748B;font-weight:600;">Total Akun Admin</div>
                <div style="font-size:1.15rem;font-weight:700;color:#0F172A;">{{ number_format($admins->total()) }}</div>
            </div>
        </div>

        <div class="au-stat-card">
            <div class="au-stat-icon" style="background:#F3E8FF;color:#7E22CE;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
            </div>
            <div>
                <div style="font-size:0.725rem;color:#64748B;font-weight:600;">Super Admin</div>
                <div style="font-size:1.15rem;font-weight:700;color:#7E22CE;">{{ $admins->where('role', 'super_admin')->count() }}</div>
            </div>
        </div>

        <div class="au-stat-card">
            <div class="au-stat-icon" style="background:#E0F2FE;color:#0369A1;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <div>
                <div style="font-size:0.725rem;color:#64748B;font-weight:600;">Admin Terbatasi</div>
                <div style="font-size:1.15rem;font-weight:700;color:#0369A1;">{{ $admins->where('role', 'admin')->count() }}</div>
            </div>
        </div>

        <div class="au-stat-card">
            <div class="au-stat-icon" style="background:#DCFCE7;color:#15803D;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div>
                <div style="font-size:0.725rem;color:#64748B;font-weight:600;">Status Aktif</div>
                <div style="font-size:1.15rem;font-weight:700;color:#15803D;">{{ $admins->where('is_active', true)->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('admin.admin-users.index') }}" style="margin-bottom:1.25rem;display:flex;gap:0.65rem;background:#fff;padding:0.65rem 0.85rem;border-radius:10px;border:1px solid #E2E8F0;">
        <svg width="16" height="16" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24" style="margin-top:0.4rem;"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" name="q" class="au-input" placeholder="Cari nama atau email admin..." value="{{ $q }}" style="border:1px solid #CBD5E1;background:#F8FAFC;">
        <button type="submit" class="au-btn-primary">Cari</button>
        @if($q)
            <a href="{{ route('admin.admin-users.index') }}" class="au-btn-act" style="padding:0.55rem 0.85rem;">Reset</a>
        @endif
    </form>

    {{-- Main Admin Table --}}
    <div class="au-table-card">
        <table class="au-table">
            <thead>
                <tr>
                    <th>Pengguna Admin</th>
                    <th>Role</th>
                    <th>Hak Akses Menu (Izin Ceklis)</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $adm)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.65rem;">
                                @if($adm->avatar)
                                    <img src="{{ asset('storage/' . $adm->avatar) }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:1px solid #E2E8F0;">
                                @else
                                    <div style="width:32px;height:32px;border-radius:50%;background:#0F172A;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;">
                                        {{ strtoupper(substr($adm->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:600;color:#0F172A;">{{ $adm->name }}</div>
                                    <div style="font-size:0.75rem;color:#64748B;">{{ $adm->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($adm->role === 'super_admin')
                                <span class="au-badge au-badge-super">★ Super Admin</span>
                            @else
                                <span class="au-badge au-badge-admin">Admin</span>
                            @endif
                        </td>
                        <td style="max-width:320px;">
                            @if($adm->role === 'super_admin' || is_null($adm->menu_permissions))
                                <span class="au-badge au-badge-active">Full Access (Semua Menu)</span>
                            @else
                                @php $perms = (array) ($adm->menu_permissions ?? []); @endphp
                                @if(empty($perms))
                                    <span style="font-size:0.75rem;color:#94A3B8;font-style:italic;">Tidak Ada Akses Menu</span>
                                @else
                                    <div style="display:flex;flex-wrap:wrap;gap:2px;">
                                        @foreach($perms as $pKey)
                                            <span class="au-badge au-badge-menu">{{ $menuOptions[$pKey] ?? $pKey }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.admin-users.toggle', $adm) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="au-badge {{ $adm->is_active ? 'au-badge-active' : 'au-badge-inactive' }}" style="border:none;cursor:pointer;" title="Klik untuk ubah status">
                                    {{ $adm->is_active ? '● Aktif' : '○ Non-Aktif' }}
                                </button>
                            </form>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex;gap:0.35rem;flex-wrap:wrap;justify-content:flex-end;">
                                <button type="button" class="au-btn-act" onclick="openEditModal({{ json_encode($adm) }})">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Ubah Akses
                                </button>
                                <button type="button" class="au-btn-act" onclick="openPasswordModal({{ json_encode($adm) }})">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Ganti Pass
                                </button>
                                @if(auth()->id() !== $adm->id)
                                    <form action="{{ route('admin.admin-users.destroy', $adm) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus akun admin ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="au-btn-act" style="color:#DC2626;">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:2.5rem 1rem;color:#94A3B8;">
                            Belum ada akun admin yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $admins->links() }}
    </div>

</div>

{{-- MODAL TAMBAH ADMIN --}}
<div id="createModal" class="au-modal-overlay">
    <div class="au-modal-card">
        <div class="au-modal-head">
            <div style="font-weight:600;font-size:0.95rem;color:#0F172A;">Tambah Akun Admin Baru</div>
            <button type="button" onclick="closeCreateModal()" style="background:none;border:none;color:#94A3B8;cursor:pointer;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.admin-users.store') }}" method="POST">
            @csrf
            <div class="au-form-group">
                <label class="au-label">Nama Lengkap</label>
                <input type="text" name="name" class="au-input" placeholder="Contoh: Admin Operasional" required>
            </div>
            <div class="au-form-group">
                <label class="au-label">Alamat Email</label>
                <input type="email" name="email" class="au-input" placeholder="admin@buyle.id" required>
            </div>
            <div class="au-form-group">
                <label class="au-label">Kata Sandi (Password)</label>
                <input type="password" name="password" class="au-input" placeholder="Minimal 6 karakter" required minlength="6">
            </div>
            <div class="au-form-group">
                <label class="au-label">Tipe Role Akun</label>
                <select name="role" id="create_role_select" class="au-select" onchange="toggleCreatePerms(this.value)">
                    <option value="admin">Admin (Akses Dibatasi Sesuai Ceklis)</option>
                    <option value="super_admin">Super Admin (Akses Penuh Semua Menu)</option>
                </select>
            </div>

            {{-- Permission Checkboxes --}}
            <div class="au-form-group" id="create_perms_wrap">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.35rem;">
                    <label class="au-label" style="margin-bottom:0;">Hak Akses Menu (Ceklis yang diizinkan)</label>
                    <label style="font-size:0.75rem;color:#10B981;cursor:pointer;font-weight:600;">
                        <input type="checkbox" onchange="toggleAllCheckboxes('create_perms_grid', this.checked)"> Pilih Semua
                    </label>
                </div>
                <div class="perm-grid" id="create_perms_grid">
                    @foreach($menuOptions as $k => $label)
                        <label class="perm-item">
                            <input type="checkbox" name="menu_permissions[]" value="{{ $k }}" checked>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="au-btn-primary" style="width:100%;justify-content:center;padding:0.65rem;">
                Simpan Akun Admin
            </button>
        </form>
    </div>
</div>

{{-- MODAL EDIT ADMIN & PERMISSIONS --}}
<div id="editModal" class="au-modal-overlay">
    <div class="au-modal-card">
        <div class="au-modal-head">
            <div style="font-weight:600;font-size:0.95rem;color:#0F172A;">Ubah Hak Akses & Data Admin</div>
            <button type="button" onclick="closeEditModal()" style="background:none;border:none;color:#94A3B8;cursor:pointer;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="au-form-group">
                <label class="au-label">Nama Lengkap</label>
                <input type="text" name="name" id="edit_name" class="au-input" required>
            </div>
            <div class="au-form-group">
                <label class="au-label">Alamat Email</label>
                <input type="email" name="email" id="edit_email" class="au-input" required>
            </div>
            <div class="au-form-group">
                <label class="au-label">Tipe Role Akun</label>
                <select name="role" id="edit_role_select" class="au-select" onchange="toggleEditPerms(this.value)">
                    <option value="admin">Admin (Akses Dibatasi Sesuai Ceklis)</option>
                    <option value="super_admin">Super Admin (Akses Penuh Semua Menu)</option>
                </select>
            </div>

            {{-- Permission Checkboxes --}}
            <div class="au-form-group" id="edit_perms_wrap">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.35rem;">
                    <label class="au-label" style="margin-bottom:0;">Hak Akses Menu (Ceklis yang diizinkan)</label>
                    <label style="font-size:0.75rem;color:#10B981;cursor:pointer;font-weight:600;">
                        <input type="checkbox" onchange="toggleAllCheckboxes('edit_perms_grid', this.checked)"> Pilih Semua
                    </label>
                </div>
                <div class="perm-grid" id="edit_perms_grid">
                    @foreach($menuOptions as $k => $label)
                        <label class="perm-item">
                            <input type="checkbox" name="menu_permissions[]" value="{{ $k }}" class="edit-perm-cb" id="edit_perm_{{ $k }}">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="au-btn-primary" style="width:100%;justify-content:center;padding:0.65rem;">
                Perbarui Hak Akses
            </button>
        </form>
    </div>
</div>

{{-- MODAL GANTI PASSWORD --}}
<div id="passwordModal" class="au-modal-overlay">
    <div class="au-modal-card" style="max-width:400px;">
        <div class="au-modal-head">
            <div style="font-weight:600;font-size:0.95rem;color:#0F172A;">Ganti Password Admin</div>
            <button type="button" onclick="closePasswordModal()" style="background:none;border:none;color:#94A3B8;cursor:pointer;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="passwordForm" method="POST">
            @csrf
            <div style="font-size:0.8rem;color:#64748B;margin-bottom:1rem;" id="password_admin_info">
                Mengubah password untuk akun admin.
            </div>

            <div class="au-form-group">
                <label class="au-label">Password Baru</label>
                <input type="password" name="password" class="au-input" placeholder="Minimal 6 karakter" required minlength="6">
            </div>
            <div class="au-form-group">
                <label class="au-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="au-input" placeholder="Ulangi password baru" required minlength="6">
            </div>

            <button type="submit" class="au-btn-primary" style="width:100%;justify-content:center;padding:0.65rem;">
                Simpan Password Baru
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openCreateModal() {
    document.getElementById('createModal').style.display = 'flex';
}
function closeCreateModal() {
    document.getElementById('createModal').style.display = 'none';
}

function toggleCreatePerms(role) {
    document.getElementById('create_perms_wrap').style.display = role === 'super_admin' ? 'none' : 'block';
}

function openEditModal(adm) {
    document.getElementById('editForm').action = '/admin/admin-users/' + adm.id;
    document.getElementById('edit_name').value = adm.name;
    document.getElementById('edit_email').value = adm.email;
    document.getElementById('edit_role_select').value = adm.role;

    toggleEditPerms(adm.role);

    // Fill permissions checkboxes
    const cbs = document.querySelectorAll('.edit-perm-cb');
    const perms = adm.menu_permissions || [];

    cbs.forEach(cb => {
        if (adm.role === 'super_admin' || adm.menu_permissions === null) {
            cb.checked = true;
        } else {
            cb.checked = perms.includes(cb.value);
        }
    });

    document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function toggleEditPerms(role) {
    document.getElementById('edit_perms_wrap').style.display = role === 'super_admin' ? 'none' : 'block';
}

function openPasswordModal(adm) {
    document.getElementById('passwordForm').action = '/admin/admin-users/' + adm.id + '/password';
    document.getElementById('password_admin_info').innerText = 'Mengubah password untuk akun ' + adm.name + ' (' + adm.email + ').';
    document.getElementById('passwordModal').style.display = 'flex';
}
function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
}

function toggleAllCheckboxes(containerId, checked) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const checkboxes = container.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = checked);
}
</script>
@endpush

@endsection
