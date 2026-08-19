@extends('account.layout')
@section('title', 'Alamat – Akun')

@section('acc_page')
<style>
.label-chip {
    padding: 0.4rem 1rem;
    border-radius: 99px;
    border: 1.5px solid #E2E8F0;
    background: #fff;
    color: #475569;
    font-size: 0.8rem;
    font-weight: 600;
    font-family: 'Montserrat', sans-serif;
    cursor: pointer;
    transition: all 0.2s;
}
.label-chip.active {
    background: #0EA5E9;
    color: #fff;
    border-color: #0EA5E9;
}
</style>
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem;">
    <div>
        <h2 style="font-size:1.125rem; font-weight:700; color:#0f172a;">Alamat Tersimpan</h2>
        <p style="font-size:0.8rem; color:#64748B;">Kelola alamat pengiriman Anda</p>
    </div>
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <button type="button" class="btn-add-addr" style="text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; background:#F8FAFC; color:#0EA5E9; border:1px solid #0EA5E9; cursor:pointer;" onclick="document.getElementById('addAddrModal').classList.add('active')">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Alamat
        </button>
        <a href="{{ route('cart.index') }}" class="btn-add-addr" style="text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
            via Checkout
        </a>
    </div>
</div>

@if($addresses->count())
    <div class="addr-grid">
        @foreach($addresses as $addr)
        <div class="addr-card {{ $addr->is_default ? 'is-default' : '' }}">
            @if($addr->is_default)
                <span class="addr-default-badge">
                    <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Utama
                </span>
            @endif
            <div class="addr-label">{{ $addr->label }}</div>
            <div class="addr-name">{{ $addr->receiver_name }}</div>
            <div class="addr-detail">
                {{ $addr->full_address }}<br>
                {{ $addr->village ? $addr->village.', ' : '' }}{{ $addr->district }},<br>
                {{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}
            </div>
            <div style="font-size:0.78rem; color:#64748B; margin-top:0.4rem; display:flex; align-items:center; gap:0.25rem;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                {{ $addr->phone }}
            </div>
            <div class="addr-actions" style="display:flex;gap:0.5rem;margin-top:1rem;">
                @if(!$addr->is_default)
                <form method="POST" action="{{ route('account.addresses.default', $addr) }}" style="flex:2;">
                    @csrf
                    <button type="submit" class="btn-addr-default" style="width:100%;">Jadikan Utama</button>
                </form>
                @else
                <span style="flex:2;"></span>
                @endif
                <button type="button" class="btn-addr-del" style="flex:1;background:#F1F5F9;color:#0F172A;border:1px solid #E2E8F0;" data-addr="{{ $addr->toJson() }}" onclick="openEditModal(JSON.parse(this.dataset.addr))">Edit</button>
                <form method="POST" action="{{ route('account.addresses.destroy', $addr) }}" style="flex:1;" onsubmit="return confirm('Hapus alamat ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-addr-del" style="width:100%;">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="acc-card">
        <div class="empty-state">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <h3>Belum ada alamat</h3>
            <p style="margin-bottom:1.5rem;">Silakan tambahkan alamat pengiriman pertama Anda.</p>
            <button type="button" class="btn-add-addr" style="margin:0 auto; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem;" onclick="document.getElementById('addAddrModal').classList.add('active')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Alamat
            </button>
        </div>
    </div>
@endif

{{-- ADD ADDRESS MODAL --}}
<div class="modal-overlay" id="addAddrModal" onclick="if(event.target===this) this.classList.remove('active')">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <div class="modal-title">Tambah Alamat</div>
                <div class="modal-subtitle">Form ini digunakan untuk menambahkan data alamat</div>
            </div>
            <button class="modal-close" onclick="document.getElementById('addAddrModal').classList.remove('active')" type="button">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('account.addresses.store') }}" id="addAddrForm">
            @csrf
            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Label <span>*</span></label>
                <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem; flex-wrap:wrap;">
                    <button type="button" class="label-chip" onclick="selectLabel(this, 'Rumah')">Rumah</button>
                    <button type="button" class="label-chip" onclick="selectLabel(this, 'Kantor')">Kantor</button>
                    <button type="button" class="label-chip" onclick="selectLabel(this, 'Toko')">Toko</button>
                </div>
                <input type="text" name="label" id="labelInput" class="form-input" placeholder="Atau ketik label lainnya..." required>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Nama Penerima <span>*</span></label>
                    <input type="text" name="receiver_name" class="form-input" placeholder="Nama lengkap penerima" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Telepon <span>*</span></label>
                    <input type="tel" name="phone" class="form-input" placeholder="08xxxxxxxxxx" required>
                </div>
            </div>

            {{-- Province: select drives hidden text input that gets submitted --}}
            <input type="hidden" name="province" id="province_name_hidden">
            <input type="hidden" name="city" id="city_name_hidden">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Provinsi <span>*</span></label>
                    <select id="province_select" class="form-input" required style="appearance:none;background-repeat:no-repeat;background-position:right 0.75rem center;background-size:1em;">
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kota / Kabupaten <span>*</span></label>
                    <select id="city_select" class="form-input" required style="appearance:none;background-repeat:no-repeat;background-position:right 0.75rem center;background-size:1em;">
                        <option value="">Pilih Provinsi Dulu</option>
                    </select>
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Kecamatan <span>*</span></label>
                    <input type="text" name="district" class="form-input" placeholder="Nama kecamatan" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelurahan / Desa</label>
                    <input type="text" name="village" class="form-input" placeholder="Kelurahan / Desa">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Kode Pos <span>*</span></label>
                <input type="text" name="postal_code" class="form-input" placeholder="Kode pos" required pattern="[0-9]{5}" maxlength="5">
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="form-label">Alamat Lengkap <span>*</span></label>
                <textarea name="full_address" class="form-input" rows="3" placeholder="Nomor rumah, nama jalan, RT/RW, patokan, dll." required></textarea>
            </div>
            <div class="btn-save-row">
                <button type="button" onclick="document.getElementById('addAddrModal').classList.remove('active')"
                    style="background:#F1F5F9; color:#374151; border:none; border-radius:10px; padding:0.6rem 1.25rem; font-family:'Montserrat',sans-serif; font-size:0.85rem; font-weight:600; cursor:pointer; margin-right:0.75rem;">
                    Batal
                </button>
                <button type="submit" class="btn-save">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT ADDRESS MODAL --}}
<div class="modal-overlay" id="editAddrModal" onclick="if(event.target===this) this.classList.remove('active')">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <div class="modal-title">Edit Alamat</div>
                <div class="modal-subtitle">Perbarui data alamat Anda</div>
            </div>
            <button class="modal-close" onclick="document.getElementById('editAddrModal').classList.remove('active')" type="button">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form method="POST" action="" id="editAddrForm">
            @csrf
            @method('PUT')
            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Label <span>*</span></label>
                <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem; flex-wrap:wrap;" id="editLabelChips">
                    <button type="button" class="label-chip" onclick="selectEditLabel(this, 'Rumah')">Rumah</button>
                    <button type="button" class="label-chip" onclick="selectEditLabel(this, 'Kantor')">Kantor</button>
                    <button type="button" class="label-chip" onclick="selectEditLabel(this, 'Toko')">Toko</button>
                </div>
                <input type="text" name="label" id="editLabelInput" class="form-input" placeholder="Atau ketik label lainnya..." required>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Nama Penerima <span>*</span></label>
                    <input type="text" name="receiver_name" id="editReceiver" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Telepon <span>*</span></label>
                    <input type="tel" name="phone" id="editPhone" class="form-input" required>
                </div>
            </div>

            <input type="hidden" name="province" id="edit_province_name_hidden">
            <input type="hidden" name="city" id="edit_city_name_hidden">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Provinsi <span>*</span></label>
                    <select id="edit_province_select" class="form-input" required style="appearance:none;background-repeat:no-repeat;background-position:right 0.75rem center;background-size:1em;">
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kota / Kabupaten <span>*</span></label>
                    <select id="edit_city_select" class="form-input" required style="appearance:none; background-repeat:no-repeat;background-position:right 0.75rem center;background-size:1em;">
                        <option value="">Pilih Provinsi Dulu</option>
                    </select>
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Kecamatan <span>*</span></label>
                    <input type="text" name="district" id="editDistrict" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelurahan / Desa</label>
                    <input type="text" name="village" id="editVillage" class="form-input">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Kode Pos <span>*</span></label>
                <input type="text" name="postal_code" id="editPostal" class="form-input" required pattern="[0-9]{5}" maxlength="5">
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="form-label">Alamat Lengkap <span>*</span></label>
                <textarea name="full_address" id="editFullAddress" class="form-input" rows="3" required></textarea>
            </div>
            <div class="btn-save-row">
                <button type="button" onclick="document.getElementById('editAddrModal').classList.remove('active')"
                    style="background:#F1F5F9; color:#374151; border:none; border-radius:10px; padding:0.6rem 1.25rem; font-family:'Montserrat',sans-serif; font-size:0.85rem; font-weight:600; cursor:pointer; margin-right:0.75rem;">
                    Batal
                </button>
                <button type="submit" class="btn-save">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/indo-regions.js') }}?v={{ time() }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initIndoRegions({
            provSelectId: 'province_select',
            citySelectId: 'city_select',
        });
        
        initIndoRegions({
            provSelectId: 'edit_province_select',
            citySelectId: 'edit_city_select',
        });

        // Sync province text → hidden input
        document.getElementById('province_select').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            document.getElementById('province_name_hidden').value = selected.value ? selected.text : '';
            document.getElementById('city_name_hidden').value = '';
        });

        // Sync city text → hidden input
        document.getElementById('city_select').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            document.getElementById('city_name_hidden').value = selected.value ? selected.text : '';
        });
        
        // Sync for Edit Form
        document.getElementById('edit_province_select').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            document.getElementById('edit_province_name_hidden').value = selected.value ? selected.text : '';
            document.getElementById('edit_city_name_hidden').value = '';
        });

        document.getElementById('edit_city_select').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            document.getElementById('edit_city_name_hidden').value = selected.value ? selected.text : '';
        });

        // Validate hidden inputs before submit
        document.getElementById('addAddrForm').addEventListener('submit', function(e) {
            if (!document.getElementById('province_name_hidden').value || !document.getElementById('city_name_hidden').value) {
                e.preventDefault();
                alert('Mohon pilih Provinsi dan Kota/Kabupaten terlebih dahulu.');
            }
        });
        
        document.getElementById('editAddrForm').addEventListener('submit', function(e) {
            if (!document.getElementById('edit_province_name_hidden').value || !document.getElementById('edit_city_name_hidden').value) {
                e.preventDefault();
                alert('Mohon pilih Provinsi dan Kota/Kabupaten terlebih dahulu.');
            }
        });
    });

    function selectLabel(btn, value) {
        document.querySelectorAll('#addAddrForm .label-chip').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('labelInput').value = value;
    }
    
    function selectEditLabel(btn, value) {
        document.querySelectorAll('#editAddrForm .label-chip').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('editLabelInput').value = value;
    }

    function openEditModal(addr) {
        const form = document.getElementById('editAddrForm');
        // Gunakan template literal untuk url form
        form.action = `/akun/alamat/${addr.id}`;
        
        document.getElementById('editLabelInput').value = addr.label;
        document.getElementById('editReceiver').value = addr.receiver_name;
        document.getElementById('editPhone').value = addr.phone;
        document.getElementById('editDistrict').value = addr.district;
        document.getElementById('editVillage').value = addr.village;
        document.getElementById('editPostal').value = addr.postal_code;
        document.getElementById('editFullAddress').value = addr.full_address;
        
        // Update label chip active state
        document.querySelectorAll('#editAddrForm .label-chip').forEach(el => {
            if(el.textContent === addr.label) el.classList.add('active');
            else el.classList.remove('active');
        });
        
        // Coba cocokan provinsi
        document.getElementById('edit_province_name_hidden').value = addr.province;
        document.getElementById('edit_city_name_hidden').value = addr.city;
        
        const provSelect = document.getElementById('edit_province_select');
        let provId = null;
        for (let i=0; i<provSelect.options.length; i++) {
            if (provSelect.options[i].text.toUpperCase() === addr.province.toUpperCase()) {
                provSelect.selectedIndex = i;
                provId = provSelect.options[i].value;
                break;
            }
        }
        
        // Trigger change untuk meload kota
        if (provId) {
            provSelect.dispatchEvent(new Event('change'));
            setTimeout(() => {
                const citySelect = document.getElementById('edit_city_select');
                for (let i=0; i<citySelect.options.length; i++) {
                    if (citySelect.options[i].text.toUpperCase() === addr.city.toUpperCase()) {
                        citySelect.selectedIndex = i;
                        break;
                    }
                }
            }, 100);
        }
        
        document.getElementById('editAddrModal').classList.add('active');
    }
</script>
@endsection


